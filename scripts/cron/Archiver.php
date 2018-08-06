<?php
class Archiver
{
    var $logger;

    public function log($message = 'Init Archiver')
    {
     //   $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/ArchiverLog.txt');
     //   $logger = new Zend_Log($writer);
     //   $logger->info($message);
    }

    public static function processForm( //$oldSiteClient, $newSiteClient,
        $formRec,
        $formNumber,
        $counter,
        $config,
        $archiveConfig,
        &$formsNotArchived,
        &$formsIndexed,
        &$formsNotIndexed,
        &$formsArchived
    ) {
        // pdf archive config
        $sessUser = new Zend_Session_Namespace('user');
        $modelName = 'Model_Form' . $formNumber;

        /*
         * Store pdf in directory
         */
        $archiveData = self::store( //$oldSiteClient, $newSiteClient,
            $counter,
            $modelName,
            $formNumber,
            $sessUser,
            $formRec,
            $formsArchived,
            $formsNotArchived
        );

        if(false === $archiveData) {
            return false;
        }

         /* Store pdf contents in Solr
          */
        $indexResult = self::index($archiveData, $config, $formsIndexed, $formsNotIndexed);
        if(false === $indexResult) {
            return false;
        }

        // archive or update archived student


        self::archiveStudent($formRec, $formNumber, $config, $archiveConfig);

        /*
         * move main db records to to the archive db
         */


        $archiveToDbResult = self::archiveFormToDb($formRec, $formNumber, $config, $archiveConfig);
        if(false === $archiveToDbResult) {
            return false;
        }

        return true;
    }

    private static function store(
        $counter,
        $modelName,
        $formNumber,
        $sessUser,
        $formRec,
        &$formsArchived,
        &$formsNotArchived
    ) {
//        echo "INFO: Storing...\n";
        try {
            $archiveData = App_Application::archiveFormToPdf( //$oldSiteClient, $newSiteClient,
                $modelName,
                $formNumber,
                $sessUser,
                $formRec['id'],
                ''
            );
            if (false !== $archiveData && $archiveData['tmpPdfPath'] != false) {
                $updateRes = App_Application::updatePdfArchiveFlag($formNumber, $formRec['id']);
                if ($updateRes) {
                    $formsArchived[] = $counter . ". Form Archived: " . $formNumber . '-' . $formRec['id'] . " v:" .
                        $formRec['version_number'] . "\n";
                    return $archiveData;
                }
            }
            return false;
        } catch (Exception $e) {
            $formsNotArchived[] = $counter . ". ERROR Archiving: ".$formNumber.'-'.$formRec['id'].' v:'.
                            $formRec['version_number'] ."\n" . ". Exception ".$e."\n";
//            echo "WARNING: Error archiving...\n";
            return false;
        }
    }

    private static function index($archiveData, $config, &$formsIndexed, &$formsNotIndexed)
    {
//        echo "INFO: Indexing...\n";
    /*   Mike commented this out 2-15-2018 so that it does not get to the solr server.
        $indexed = App_Solr::indexBinary($archiveData, $config->solr->host, $config->solr->port);
        if ($indexed) {
            $formsIndexed[] = "File " . $archiveData['tmpPdfPath'] . " has been indexed.\n";
            return true;
        } else {
            $formsNotIndexed[] = "File " . $archiveData['tmpPdfPath'] . " could not be indexed:\n $indexed\n";
//            echo "WARNING: Could not index...\n";
            return false;
        } */
        return true;
    }

    public static function archiveFormToDb($formRec, $formNumber, $config, $archiveConfig)
    {
        /* Mike commented this out 2-22-2018 because we are not archiving the db of forms. */
//        echo "INFO: Archiving to db...\n";
        $archiveDb = Zend_Db::factory($archiveConfig->dbArchive);
        $archiveDb->beginTransaction();

        $mainDb = Zend_Db::factory($config->db2);
        $mainDb->beginTransaction();

        try {

            $modelName = 'Model_Table_Form' . $formNumber;
            $tableName = 'iep_form_' . $formNumber;
            $tableKey = 'id_form_' . $formNumber;

            // select the form that's being archived
            $dbTable = new $modelName();
            $mainDbForm = $dbTable->fetchRow($tableKey . ' = ' . $formRec[$tableKey]);

            // delete existing archives that match this form's id
            // loop over dependent tables and remove child records
            foreach($dbTable->getDependentTables() as $dependentTable) {
                $dependentTableModel = new $dependentTable();
                // loop over the dependent records
                foreach($mainDbForm->findDependentRowset($dependentTable) as $formRecDependent) {
                    $dependentTableKeyName = $dependentTableModel->getPrimaryKeyName();
                    $dependentTableName = $dependentTableModel->getTableName();
                    // delete existing archives
                    self::deleteExistingArchiveRecords($formRecDependent, $archiveDb, $dependentTableName, $dependentTableKeyName);
                }
            }

            self::deleteExistingArchiveRecords($mainDbForm, $archiveDb, $tableName, $tableKey);
           self::log("Delete archive records from $tableName where " . $tableKey . ' = ' . $formRec[$tableKey]);



            // insert into archive db
            $archiveResult = $archiveDb->insert($tableName, self::massageBools($mainDbForm, $mainDb->describeTable($tableName)));
            self::log("Archive Form: Insert into $tableName {$formRec[$tableKey]}");

            if(!$archiveResult) {
                throw new Exception('Archiving main form failed.');
                return false;
            } else {
                // loop over dependent tables
                foreach($dbTable->getDependentTables() as $dependentTable) {
                    $dependentTableModel = new $dependentTable();
                    // loop over the dependent records and insert child records
                    foreach($mainDbForm->findDependentRowset($dependentTable) as $formRecDependent) {
                        $dependentTableKeyName = $dependentTableModel->getPrimaryKeyName();
                        $dependentTableName = $dependentTableModel->getTableName();

                        // insert into archive db
                        $archiveResult = $archiveDb->insert($dependentTableName, self::massageBools($formRecDependent, $mainDb->describeTable($dependentTableName)));
                        if(!$archiveResult) {
                            throw new Exception('Archiving subform ' . $dependentTableName . ' failed.');
                            return false;
                        }
                      self::log("Archive Form Insert into $dependentTableName.");
                    }
                }
            }
            $archiveDb->commit();
            $mainDb->commit();
            return true;
        } catch (Exception $e) {
            $archiveDb->rollback();
            $mainDb->rollback();
//            echo "WARNING: Could not move data to archive db...\n";
            return false;
        }



    }

    public static function deleteForm($formRec, $formNumber, $config, $archiveConfig)
    {
        $archiveDb = Zend_Db::factory($archiveConfig->dbArchive);
        $mainDb = Zend_Db::factory($config->db2);

        $modelName = 'Model_Table_Form' . $formNumber;
        $tableName = 'iep_form_' . $formNumber;
        $tableKey = 'id_form_' . $formNumber;

        // select the form that's being archived
        $dbTable = new $modelName();
        $mainDbForm = $dbTable->fetchRow($tableKey . ' = ' . $formRec[$tableKey]);

        // loop over dependent tables
        foreach($dbTable->getDependentTables() as $dependentTable) {
            foreach($mainDbForm->findDependentRowset($dependentTable) as $formRecDependent) {
                $deleteResult = $formRecDependent->delete();
                self::log("Delete main db form from $dependentTable. result: $deleteResult");
            }
        }
        $deleteResult = $mainDbForm->delete();
        self::log("Delete main db form from $modelName. result: $deleteResult");
        return $deleteResult;

    }

    public static function restoreArchiveForm($formId, $formNumber, $config, $archiveConfig)
    {
        $archiveDb = Zend_Db::factory($archiveConfig->dbArchive);
        $mainDb = Zend_Db::factory($config->db2);

        $modelName = 'Model_Table_Form' . $formNumber;
        $tableName = 'iep_form_' . $formNumber;
        $tableKey = 'id_form_' . $formNumber;

        $dbTable = new $modelName();

        if(count(self::getMainDbRecord(array($tableKey => $formId), $mainDb, $tableName, $tableKey))) {
            // record exists in main system -- bail out
//            echo 'Form ' . $formId . ' exists in the main system. Please delete if you want to restore from archive.';
        } else {

            $archiveDbForm = self::fetchArchiveRow(array($tableKey => $formId), $archiveDb, $tableName, $tableKey, $formNumber);
            if($archiveDbForm) {
                $archiveResult = $mainDb->insert($tableName, self::massageBools($archiveDbForm, $mainDb->describeTable($tableName)));

                if($archiveResult) {
                    // loop over dependent tables
                    foreach($dbTable->getDependentTables() as $dependentTable) {
                        $dependentTableModel = new $dependentTable();
                        // loop over the dependent records
                        foreach($archiveDbForm->findDependentRowset($dependentTable) as $formRecDependent) {
                            $dependentTableKeyName = $dependentTableModel->getPrimaryKeyName();
                            $dependentTableName = $dependentTableModel->getTableName();

                            // insert into archive db
                            $mainDb->insert($dependentTableName, self::massageBools($formRecDependent, $mainDb->describeTable($dependentTableName)));
                        }
                    }
                }
            }
        }

    }


    public static function archiveStudent($formRec, $formNumber, $config, $archiveConfig)
    {
//        echo "INFO: Archiving student...\n";
        $archiveDb = Zend_Db::factory($archiveConfig->dbArchive);
        $mainDb = Zend_Db::factory($config->db2);

        $modelName = 'Model_Table_StudentTable';
        $tableName = 'iep_student';
        $tableKey = 'id_student';

        // select the form that's being archived
        $dbTable = new $modelName();
        $studentRecord = $dbTable->fetchRow($tableKey . ' = ' . $formRec[$tableKey]);

        if(false === self::getArchiveRecord($formRec, $archiveDb, $tableName, $tableKey)) {
            // insert
//            echo "INFO: Insert student...{$formRec[$tableKey]}\n";
            return $archiveDb->insert($tableName, self::massageBools($studentRecord, $mainDb->describeTable($tableName)));
        } else {
//            echo "INFO: Update student...{$formRec[$tableKey]}\n";
            return $archiveDb->update(
                $tableName,
                self::massageBools($studentRecord, $mainDb->describeTable($tableName)),
                $tableKey . ' = ' . $formRec[$tableKey]
            );
        }

    }

    /**
     * @param $form
     * @param $metadata
     * @param $data
     * @return mixed
     */
    public static function massageBools($form, $metadata)
    {
        $data = array();
        // massage bool fields to add quotes
        foreach ($form as $key => $column) {
            $colType = $metadata[$key]['DATA_TYPE'];
            if ('bool' == $colType) {
                if ('' === $column) {
                    $data[$key] = null;
                } elseif (true == $column) {
                    $data[$key] = 'true';
                } elseif (false == $column) {
                    $data[$key] = 'false';
                }

            } else {
                $data[$key] = $column;
            }
        }
        return $data;
    }

    /**
     * @param $formRec
     * @param $archiveDb
     * @param $tableName
     * @param $tableKey
     */
    public static function deleteExistingArchiveRecords($formRec, $archiveDb, $tableName, $tableKey)
    {
        // delete any existing rows in the archive db
        // that match the form being archived
        $select = new Zend_Db_Select($archiveDb);
        $select->from($tableName);
        $select->where($tableKey . ' = ' . $formRec[$tableKey]);
        $archiveRecord = $archiveDb->query($select)->fetchAll();
        if (count($archiveRecord) >= 1) {
            foreach ($archiveRecord as $aRec) {
                $archiveDb->delete($tableName, $tableKey . ' = ' . $archiveDb->quote($aRec[$tableKey]));
            }
        }
    }

    /**
     * @param $formRec
     * @param $archiveDb
     * @param $tableName
     * @param $tableKey
     */
    public static function getArchiveRecord($formRec, $archiveDb, $tableName, $tableKey)
    {
        // archive record
        $select = new Zend_Db_Select($archiveDb);
        $select->from($tableName);
        $select->where($tableKey . ' = ' . $archiveDb->quote($formRec[$tableKey]));
        return $archiveDb->fetchRow($select);
    }

    public static function fetchArchiveRow($formRec, $archiveDb, $tableName, $tableKey, $formNumber)
    {
        // archive record
        $modelName = 'Model_Table_Form' . $formNumber;
        $table = new $modelName($archiveDb);
        return $table->fetchRow($tableKey . ' = ' . $formRec[$tableKey]);
    }


    /**
     * @param $formRec
     * @param $mainDb
     * @param $tableName
     * @param $tableKey
     */
    public static function getMainDbRecord($formRec, $mainDb, $tableName, $tableKey)
    {
        // archive record
        $select = new Zend_Db_Select($mainDb);
        $select->from($tableName);
        $select->where($tableKey . ' = ' . $mainDb->quote($formRec[$tableKey]));
        return $mainDb->query($select)->fetchAll();
    }
}