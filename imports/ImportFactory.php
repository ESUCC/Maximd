<?php

class ImportFactory {

    var $type;
    var $dataSource;

    var $sourceFields=array();
    var $extraFields=array();
    var $extraInsertFields=array();
    var $extraUpdateFields=array();

    var $currentLine=0;
    var $currentRecord;
    var $currentChangeType;
    var $delimiter = ',';

    var $log=array();
    var $timeOfImport=array();

    public function __construct() {
        $this->timeOfImport = date("c", strtotime('now'));
    }

    public function clearMetaData() {
        $this->setExtraFields(array());
        $this->setExtraInsertFields(array());
        $this->setExtraUpdateFields(array());
        $this->currentLine = 0;
        $this->setLog(array());
    }


//    public function initEmail($emailConfig)
//    {
//        echo "initEmail";
//        die;
//        $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', array(
//            'ssl'     => 'ssl',
//            'port' => 465,
//            'auth'     => 'login',
//            'username' => $emailConfig->username,
//            'password' => $emailConfig->password,
//        ));
//        Zend_Mail::setDefaultTransport($transport);
//    }

    public function sendErrorEmail($emailConfig, $message)
    {
        $transport = Zend_Mail::getDefaultTransport();
        if ($emailConfig->sendEmailNotification) {
            $mail = new Zend_Mail();

            $msgTxt = "The following error occurred on ". ucfirst(APPLICATION_ENV). " at " . date('h:i:sA D F dS Y'). "\r\n \r\n";
            $msgTxt .= " \r\n \r\n".$message. " \r\n \r\n";

            $mail->setBodyText($msgTxt);
            $mail->setFrom($emailConfig->from);
            $mail->setSubject('SRS District Import ' .ucfirst(APPLICATION_ENV) .' System Error');
            $mail->addTo("mdanahy@esucc.org", "Jesse");

            if (!empty($emailConfig->to)) {
                foreach ($emailConfig->to as $contact) {
                    $mail->addTo($contact->email,'<'.$contact->name.'>');
                }
            }
            $mail->send($transport);
        }
    }
    public function sendNotificationEmail($emailConfig, $message)
    {
        $transport = Zend_Mail::getDefaultTransport();
        if ($emailConfig->sendEmailNotification) {
            $mail = new Zend_Mail();

            $msgTxt = "The following import ran on ". ucfirst(APPLICATION_ENV). " at " . date('h:i:sA D F dS Y'). "\r\n \r\n";
            $msgTxt .= " \r\n \r\n".$message. " \r\n \r\n";

            $mail->setBodyText($msgTxt);
            $mail->setFrom($emailConfig->from);
            $mail->setSubject('SRS District Import ' .ucfirst(APPLICATION_ENV) .' Successful');
            $mail->addTo("mdanahy@esucc.org", "Jesse");

            if (!empty($emailConfig->to)) {
                foreach ($emailConfig->to as $contact) {
                    $mail->addTo($contact->email,'<'.$contact->name.'>');
                }
            }
            $mail->send($transport);
        }
    }

    public function preFlightFile() {

        /**
         * get the main application AND import config files
         */
        $appConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $importConfig = new Zend_Config_Ini('import.ini', APPLICATION_ENV);
        $this->importConfig = $importConfig;
        $this->dataSource = $importConfig->data_source;
        $this->initEmail($importConfig->email);

        /** create database connection */
        $dbConfig = $appConfig->db2;
        $db = Zend_Db::factory($dbConfig);    // returns instance of Zend_Db_Adapter class
        Zend_Registry::set('db', $db);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);

        /**
         * get the district
         * check to make sure it's an import district
         * and that it has the right import code
         */
        $districtModel = new Model_Table_District();
        $district = $districtModel->getDistrict($importConfig->id_county, $importConfig->id_district);

        /**
         * *******************************************************************
         * PREFLIGHT
         * *******************************************************************
         * district MUST have pref_district_imports = true
         * and district_import_code != null
         * otherwise this is not a valid import
         */
        $errorMsg = '';

        /**
         * district checks
         */
        if(null!=$district) {
            if('t'!=$district->pref_district_imports) {
                $errorMsg .= "ERROR - DISTRICT IS NOT SET TO IMPORT STUDENTS".PHP_EOL;
            }
            if(''==$district->district_import_code) {
                $errorMsg .=  "ERROR - DISTRICT DOES NOT HAVE AN IMPORT CODE".PHP_EOL;
            }
            if($importConfig->data_source!=$district->district_import_code) {
                $errorMsg .=  "ERROR - IMPORT CODE DOES NOT MATCH ON DISTRICT AND IMPORT CONFIG FILE".PHP_EOL;
            }
        } else {
            $errorMsg .= "ERROR - DISTRICT NOT FOUND".PHP_EOL;
        }

        /**
         * file checks
         */
        if(!file_exists($importConfig->studentImportFile->filename)) {
            $errorMsg .= "ERROR - STUDENT FILE DOES NOT EXIST".PHP_EOL;
        }
        if(!file_exists($importConfig->parentImportFile->filename)) {
            $errorMsg .= "ERROR - PARENT FILE DOES NOT EXIST".PHP_EOL;
        }

        if(!empty($errorMsg)) {
            echo "import cancelled\n";
            // EMAIL THE RESULTS
            $this->sendErrorEmail($importConfig->email, $errorMsg);
            return false;
        }
        // END ******************************************************************* PREFLIGHT

    }
    function updateRecord( $data ) {
        /**
         * replace empty with NULL
         */
        foreach ($data as $key => $value) {
            if(''===$value) $data[$key] = NULL;
        }
        /**
         * set values into the current record
         */
        foreach ($data as $fieldName => $value) {
            $this->currentRecord->$fieldName = $value;
        }
        return $this->currentRecord->save();
    }
    function insertStudent( $data ) {
        /**
         * replace empty with NULL
         */
        foreach ($data as $key => $value) {
            if(''===$value) $data[$key] = NULL;
        }
        $table = new Model_Table_StudentTable();
        return $table->insert($data);
    }
    function insertGuardian( $data ) {
        $table = new Model_Table_GuardianTable();
        $result = $table->insert($data);
        return $result;
    }

    function processLine ( $lineBuf, $parseGuardianID = false) {
        # INCREMENT THE LINE COUNTER
        $this->currentLine++;
        echo $this->currentLine%100==0 ? $this->currentLine:'.';
//        echo("Processing line " . $this->currentLine . "\n");

        /**
         * error check line
         */
        if ( $lineBuf =='' ) {
            $this->addLog($this->currentLine, 'Line Empty');
            return false;
        }

        /**
         * preProcessLine: makes sure we take care of quotes in quotes
         * and trim extra spaces and carriage returns
         */
        $importRowColumns = explode( $this->delimiter, $this->preProcessLine( $lineBuf ));
//        Zend_Debug::dump($this->sourceFields);
//        Zend_Debug::dump($importRowColumns);
//        Zend_Debug::dump(array_combine(array_keys($this->sourceFields), $importRowColumns));
        $countImportRowColumns = count( $importRowColumns );

        /**
         * confirm we have the required number of columns
         */
        if($countImportRowColumns != count($this->sourceFields)) {
            $this->addLog($this->currentLine, 'Incorrect Field Count (Required Column Count: '. $countImportRowColumns. ', Got Count: '. count( $this->sourceFields ). ')');
            return false;
        }

        $indexedFields = array_keys($this->sourceFields);

        # FILL DATA ARRAY
        $dataArray = array();
        $i = 0;
        while( $i < $countImportRowColumns ) {
            /**
             * if designated as field, just insert data from the file
             */
            $fieldName = $indexedFields[$i];
            if('field'==$this->sourceFields[$fieldName]) {
                $dataArray[$fieldName] = str_replace( '\044', ',', $importRowColumns[$i]); // un-escape any escaped commas in the fields

            } elseif(is_array($this->sourceFields[$fieldName]) && isset($this->sourceFields[$fieldName]['function'])) {
                $funcName = $this->sourceFields[$fieldName]['function'];
                if(method_exists($this, $funcName)) {
                    $dataArray[$fieldName] = $this->$funcName($importRowColumns, $i);
                }
            }
            $i++;
        }

        // sl 2003-04-05 new code to add certain fields before determining whether something is an update or an insert
        // we need some of these fields, particularly data source, to determine uniqueness, so we need to insert them now.
        $extraFieldArray = $this->getExtraFields(); // grab the new extraFields array
        $extraFieldCount = count( $extraFieldArray );
        $i = 0;
        while( $i < $extraFieldCount ) {
            $fieldValue =  $extraFieldArray[$i][1];
            $fieldValue = $this->checkFunction( $fieldValue, $dataArray ); // if this field is a function call, fill in the params
            $dataArray[$extraFieldArray[$i][0]] = $fieldValue;
            $i++;
        }


        // add any extra values necessary
        if ( $this->currentChangeType == 'create' ) {
            $extraFieldArray = $this->getExtraInsertFields();
        } elseif ( $this->currentChangeType == 'update' ) {
            $extraFieldArray = $this->getExtraUpdateFields();
        }
        $extraFieldCount = count( $extraFieldArray );


        $i = 0;
        while( $i < $extraFieldCount ) {
            $fieldValue =  $extraFieldArray[$i][1];
            $fieldValue = $this->checkFunction( $fieldValue, $dataArray ); // if this field is a function call, fill in the params
            $dataArray[ $extraFieldArray[$i][0]] = $fieldValue;
            $i++;
        }

        /**
         * final checks
         */
        if(false===$this->setCurrentRecord($dataArray)) {
            $this->addLog($this->currentLine, 'Record is NOT unique.');
            return false;
        }

        if('student'==$this->getType()) {
            /**
             * final checks
             */
            if(!isset($dataArray['ethnic_group']) || strlen($dataArray['ethnic_group'])!=1) {
                $this->addLog($this->currentLine, 'Ethnic Group could not be determined.');
                return false;
            }
            if(!isset($dataArray['id_school']) || strlen($dataArray['id_school'])!=3) {
                $this->addLog($this->currentLine, 'id_school could not be determined.');
                return false;
            }
            if(!isset($dataArray['address_zip']) || ''==$dataArray['address_zip']) {
                $this->addLog($this->currentLine, 'address_zip could not be determined.');
                return false;
            }

            if ( $this->currentChangeType == 'create' ) {
                $result = $this->insertStudent( $dataArray );
            } elseif ( $this->currentChangeType == 'update' ) {
                $result = $this->updateRecord( $dataArray );
            }
        } else {
            /**
             * final checks
             */
            if(!isset($dataArray['id_student']) || ''==$dataArray['id_student'] || false === $dataArray['id_student']) {
                $this->addLog($this->currentLine, 'Student ID could not be determined.');
                return false;
            }
            if(!isset($dataArray['name_first']) || ''==$dataArray['name_first']) {
                $this->addLog($this->currentLine, 'name_first could not be determined.');
                return false;
            }
            if(!isset($dataArray['name_last']) || ''==$dataArray['name_last']) {
                $this->addLog($this->currentLine, 'name_last could not be determined.');
                return false;
            }
            if(!isset($dataArray['address_state']) || strlen($dataArray['address_state'])!=2) {
                $this->addLog($this->currentLine, 'address_state could not be determined. ' . $dataArray['address_state']);
                return false;
            }
//            Zend_Debug::dump($dataArray);
            if ( $this->currentChangeType == 'create' ) {
                $result = $this->insertGuardian( $dataArray );
            } elseif ( $this->currentChangeType == 'update' ) {
                $result = $this->updateRecord( $dataArray );
            }
        }

        return;

//        $this->request->sqlStmt = $sqlStmt;
//        $this->request->sqlExec();
//        if ( $this->request->errorId == ERROR_NO_ERROR ) {
//            if ( $this->currentChangeType == 'create' ) {
//                $this->insertCount++;
//            } elseif ( $this->currentChangeType == 'update' ) {
//                $this->updateCount++;
//            }
//        } else { // got some sql error
//            $this->addLog($this->currentLine, 'SQL Error');
//        }
////        echo "###### INSERTS " . $this->insertCount . " UPDATES " . $this->updateCount . " ERRORS " . $this->errorCount . "\n";
//        return;

    }

    /* The "extra" fields can contain function calls. If used, these calls have to have parameters that refer to the original source fields.
     This function will check to see if the field value is in fact a function call. If so it will replace the param names with their values
     from the data array and hand the result back*/
    function checkFunction( $fieldValue, $dataArray ) {
        //echo ("check function, value = $fieldValue<br />");
        $isMatch = preg_match( "/([^\(]+)\(([^\)]+)\)/", $fieldValue, $matches ); // regexp searches for function_name(param1, param2 ...)
        if ( ! $isMatch ) {
            return $fieldValue;
        } else {
            //echo ("got regex match<br />");
            $name = $matches[1]; $params = $matches[2]; // first sub expression is function name, second is param list
            //echo ("name = $name, params = $params");
            $paramArray = explode( ",", $params );
            while (list($key, $value) = each($paramArray)) {
                if ( strpos( $value, '::') != false ) { // literal value, pass it through
                    $paramArray[$key] = $value;
                } else {
                    $paramArray[$key] = $dataArray[$value];
                }
            }
            $newParams = implode( ",",  $paramArray );
            $ret =  "$name( $newParams )";
            //echo "<br />$ret<br />";
            return $ret;
        }
    }

    public function addLog($key, $msg)
    {
        return $this->log[$key] = $msg;
    }

    public function setSourceFields($sourceFields)
    {
        $this->sourceFields = $sourceFields;
    }

    public function getSourceFields()
    {
        return $this->sourceFields;
    }

    public function setExtraFields(array $extraFields)
    {
        $this->extraFields = array();
        foreach ($extraFields as $extraField) {
            $this->addExtraField($extraField);
        }
    }
    public function addExtraField(array $extraField)
    {
        $this->extraFields[] = $extraField;
    }

    public function getExtraFields()
    {
        return $this->extraFields;
    }

    public function setExtraInsertFields($extraInsertFields)
    {
        $this->extraInsertFields = $extraInsertFields;
    }

    public function getExtraInsertFields()
    {
        return $this->extraInsertFields;
    }

    public function setExtraUpdateFields($extraUpdateFields)
    {
        $this->extraUpdateFields = $extraUpdateFields;
    }

    public function getExtraUpdateFields()
    {
        return $this->extraUpdateFields;
    }


    public function getCurrentRecord()
    {
        return $this->currentRecord;
    }


    function processFile($sourceFilePath, $parseGuardianID = false) {
        # OPEN SOURCE FILE
        if ( !$fp = fopen( $sourceFilePath, 'r' ) ) {
            return false;
        }
        # PROCESS EACH LINE
        while ( !feof( $fp ) ) {
            $lineBuf = fgets( $fp);
            if(''==$lineBuf) continue; // skip empty lines
            $this->processLine( $lineBuf , $parseGuardianID);
        }
        fclose( $fp);
    }

    // initial massage on the line, in this case to handle commas inside quotes
    // all commas inside fields will be ASCII-converted: \044
    function preProcessLine ( $lineBuf ) {
        $count = 0;
        $inQuotes = 0;
        $output = '';
        $length = strlen( $lineBuf );
        while($count < $length ) {
            $currentChar = $lineBuf[$count++];
            if ($currentChar == '"' ) {
                $inQuotes = 1 - $inQuotes; // flip the flag for being in or out of quotes
            } else {
                if ( $currentChar != ',' ) { // if it's not a comma, pass it through
                    $output .= $currentChar;
                } else { // process a comma
                    if ( $inQuotes == 1 ) { // escape any commas inside quotes (turn to ASCII code)
                        $output .= '\044';
                    } else {
                        $output .= $currentChar;
                    }
                }
            }
        }
        return trim($output); // sl added trim 2003-04-20 to deal with possible trailing CR\LF problem
    }

    public function setCurrentRecord($data)
    {
        if('student'==$this->getType()) {
            $table = new Model_Table_StudentTable();
            $select = $table->select()
                ->where('id_student_local = ?', $data['id_student_local'])
                ->where('data_source = ?', $this->dataSource);
            $record = $table->fetchAll($select);
        } elseif ('guardian'==$this->getType()) {
            $table = new Model_Table_GuardianTable();
            $select = $table->select()
                ->where('id_student_local = ?', $data['id_student_local'])
                ->where('data_source = ?', $this->dataSource);
            $record = $table->fetchAll($select);
        }
        switch (count($record)) {
            case 0:
                $this->currentChangeType = 'create'; // record isn't there, force it to an insert
                $this->currentRecord = null;
                break;
            case 1:
                $this->currentChangeType = 'update'; // record is already there, force it to an update
                $this->currentRecord = $record->current();
                break;
            default:
                $this->currentChangeType = null;
                $this->currentRecord = null;
                return false;
        }
        return true;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    function processGuardianFile($filename=null) {

        if(null==$filename) {
            $filename = $this->importConfig->parentImportFile->filename;
        }

        $this->setType('guardian');

        /**
         * get fields defined in the import file
         */
        $this->setSourceFields($this->importConfig->parentImportFile->fields->toArray());

        /**
         * forced extra fields for insert and update
         */
        $this->addExtraField(array( "data_source", $this->importConfig->data_source));
        $this->addExtraField(array( "last_auto_update", $this->timeOfImport ));
        $this->addExtraField(array( "id_author_last_mod", 0 ));

        /**
         * add extra fields that contain constant values
         */
        if(isset($this->importConfig->parentImportFile->extraFields)) {
            foreach ($this->importConfig->parentImportFile->extraFields->toArray() as $fieldName => $constant) {
                $this->addExtraField(array($fieldName,$constant));
            }
        }

        /**
         * process the guardian file
         */
        $this->processFile($filename);

    }

    function processStudentFile($filename=null) {

        if(null==$filename) {
            $filename = $this->importConfig->studentImportFile->filename;
        }

        $this->setType('student');

        /**
         * get fields defined in the import file
         */
        $this->setSourceFields($this->importConfig->studentImportFile->fields->toArray());

        /**
         * forced extra fields for insert and update
         */
        $this->addExtraField(array( "data_source", $this->importConfig->data_source));
        $this->addExtraField(array( "last_auto_update", $this->timeOfImport ));
        $this->addExtraField(array( "id_author_last_mod", 0 ));

        /**
         * add extra fields that contain constant values
         */
        if($this->importConfig->studentImportFile->extraFields) {
            foreach ($this->importConfig->studentImportFile->extraFields->toArray() as $fieldName => $constant) {
                $this->addExtraField(array($fieldName,$constant));
            }
        }
        /**
         * add extra fields that pertain ONLY to UPDATES
         */
//        $this->setExtraUpdateFields(array(array( "status", "Inactive")));

        /**
         * process the student file
         */
        $this->processFile($filename);
    }

    function countEmptyDataSource() {
        $table = new Model_Table_StudentTable();
        $select = $table->select()
            ->where('id_county = ?', $this->importConfig->id_county)
            ->where('id_district = ?', $this->importConfig->id_district)
            ->where('data_source = NULL');
        $records = $table->fetchAll($select);
        return $records;
    }

    public function setLog($log)
    {
        $this->log = $log;
    }

    public function getLog()
    {
        return $this->log;
    }
    public function dumpLog($lineSep="\n")
    {
        $str = "";
        foreach ($this->log as $lineNumber => $msg) {
            $str .= "$lineNumber: $msg$lineSep";
        }
        return $str;

    }

    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function getDelimiter()
    {
        return $this->delimiter;
    }
}


