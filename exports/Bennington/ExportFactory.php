<?php

class ExportFactory
{

    var $type;
    var $debug = true;
    var $dataSource;

    var $sourceFields = array();
    var $extraFields = array();
    var $extraInsertFields = array();
    var $extraUpdateFields = array();

    var $currentLine = 0;
    var $currentRecord;
    var $currentChangeType;
    var $delimiter = "\t";
    var $eol = "\r\n";

    var $log = array();
    var $timeOfImport = array();

    var $form001 = null;
    var $form002 = null;
    var $form003 = null;
    var $form004 = null;
    var $form005 = null;
    var $form006 = null;
    var $form007 = null;
    var $form008 = null;
    var $form009 = null;
    var $form010 = null;
    var $form011 = null;
    var $form012 = null;
    var $form013 = null;
    var $form014 = null;
    var $form015 = null;
    var $form016 = null;
    var $form017 = null;
    var $form018 = null;
    var $form019 = null;
    var $form020 = null;
    var $form021 = null;
    var $form022 = null;
    var $form023 = null;
    var $form024 = null;
    var $form025 = null;

    public function __construct()
    {
        $this->timeOfImport = date("c", strtotime('now'));
        $mike='Mike';
    }

    function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }

    public function clearMetaData()
    {
        $this->currentLine = 0;
        $this->setLog(array());
    }

    function initEmail($emailConfig)
    {
        $transport = new Zend_Mail_Transport_Smtp($emailConfig->host, $emailConfig->toArray());
        Zend_Mail::setDefaultTransport($transport);
    }

    function sendErrorEmail($emailConfig, $message)
    {
        $transport = Zend_Mail::getDefaultTransport();
        if ($emailConfig->sendEmailNotification) {
            $mail = new Zend_Mail();

            $msgTxt = "The following error occurred on " . ucfirst(APPLICATION_ENV) . " at " . date(
                    'h:i:sA D F dS Y'
                ) . "\r\n \r\n";
            $msgTxt .= " \r\n \r\n" . $message . " \r\n \r\n";

            $mail->setBodyText($msgTxt);
            $mail->setFrom($emailConfig->from);
            $mail->setSubject('SRS District Import ' . ucfirst(APPLICATION_ENV) . ' System Error');
            $mail->addTo("jlavere@soliantconsulting.com", "Jesse");

            if (!empty($emailConfig->to)) {
                foreach ($emailConfig->to as $contact) {
                    $mail->addTo($contact->email, '<' . $contact->name . '>');
                }
            }
            $mail->send($transport);
        }
    }

    function sendNotificationEmail($emailConfig, $message, $success, $attach = false)
    {
        $transport = Zend_Mail::getDefaultTransport();
        if ($emailConfig->sendEmailNotification) {
            $mail = new Zend_Mail();

            $msgTxt = "The following export ran on " . ucfirst(APPLICATION_ENV) . " at " . date(
                    'h:i:sA D F dS Y'
                ) . "\r\n \r\n";
            $msgTxt .= " \r\n \r\n" . $message . " \r\n \r\n";

            $mail->setBodyText($msgTxt);
            $mail->setFrom($emailConfig->from);
            if ($success) {
                $mail->setSubject('SRS District Export ' . ucfirst(APPLICATION_ENV) . ' Successful');
            } else {
                $mail->setSubject('SRS District Export ' . ucfirst(APPLICATION_ENV) . ' FAILED');
            }
            if ($attach) {
                $attachment = $mail->createAttachment(
                    file_get_contents($this->exportConfig->studentExportFile->filename)
                );
                $attachment->type = 'application/csv';
                $attachment->filename = $this->exportConfig->studentExportFile->filename;
            }

            if (!empty($emailConfig->to)) {
                foreach ($emailConfig->to as $contact) {
                    $mail->addTo($contact->email, '<' . $contact->name . '>');
                }
            }
            $mail->send($transport);
        }
    }

    public function addLog($key, $msg)
    {
        return $this->log[$key] = $msg;
    }


    function countEmptyDataSource()
    {
        $table = new Model_Table_StudentTable();
        $select = $table->select();
//        $select->where('id_county = ?', $this->exportConfig->id_county);
        /**
         * allow multiple districts
         */
        $whereAdd = '';
        if (isset($this->exportConfig->id_county_district) && count($this->exportConfig->id_county_district)) {
            $first = true;
            foreach ($this->exportConfig->id_county_district as $id_county_district) {
                // ensure proper formatting of passed numbers
                if(1 !== substr_count($id_county_district, '-')) {
                    continue;
                }
                list($id_county, $id_district) = explode('-', $id_county_district);
                if($first) {
                    $whereAdd = '(id_county = \''.$id_county.'\' and id_district = \''.$id_district.'\') ';
                } else {
                    $whereAdd .= 'or (id_county = \''.$id_county.'\' and id_district = \''.$id_district.'\') ';
                }
                $first = false;
            }
            $select->where($whereAdd);
        } elseif (count($this->exportConfig->id_district) >= 2) {
            $first = true;
            foreach ($this->exportConfig->id_district as $id_district) {
                if ($first) {
                    $whereAdd = '(id_county = \'' . $this->exportConfig->id_county . '\' and id_district = \'' . $id_district . '\') ';
                } else {
                    $whereAdd .= 'or (id_county = \'' . $this->exportConfig->id_county . '\' and id_district = \'' . $id_district . '\') ';
                }
                $first = false;
            }
            $select->where($whereAdd);
        } else {
            $select->where('id_county = ?', $this->exportConfig->id_county);
            $select->where('id_district = ?', $this->exportConfig->id_district);
        }
        $select->where('data_source = NULL');
        echo $select;

        $db = Zend_Registry::get('db');
        $stmt = $db->query($select);
      // $this->writevar1($select,'this is the select statement');
      //  $this->writevar1($stmt,'this is the sql statement');


        $studentCount = 0;
        while ($data = $stmt->fetch()) {
            $studentCount++;
        }
        $this->writevar1($studentCount,'this is the student count');
        return $studentCount;
    }

    public function setLog($log)
    {
        $this->log = $log;
    }

    public function getLog()
    {
        return $this->log;
    }

    public function dumpLog($lineSep = "\n")
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

    public function setSourceFields($sourceFields)
    {
        $this->sourceFields = $sourceFields;
    }

    public function getSourceFields()
    {
        return $this->sourceFields;
    }

   public function getForm013MeetingDate($id)
   {
       $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
       $database = Zend_Db::factory($dbConfig->db2);
       $sql="select get_most_recent_ifsp_date_conference(".$id.") as ifsp_date";
       $ifsp_date=$database->fetchAll($sql);

       return $ifsp_date[0]['ifsp_date'];


   }

   public function getStudentExitInfo($id)  {

       $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
       $database = Zend_Db::factory($dbConfig->db2);

       //Get all the students


       $sql="select id_student,sesis_exit_code,sesis_exit_date from iep_student where id_student='".$id."' ";
   //    $this->writevar1($sql,'this is the sql command');
       $studentData=$database->fetchAll($sql);
   //    $this->writevar1($studentData,'this is the student data');


       return $studentData;
   }

   public function getTransferReportCutoff() {
       $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
       $database = Zend_Db::factory($dbConfig->db2);
       $sql="select transfer_report_cutoff from admin_settings";
       $datetransfer=$database->fetchAll($sql);
      // $this->writevar1($datetransfer[0]['transfer_report_cutoff'],'this  is the date transfer');
       return($datetransfer[0]['transfer_report_cutoff']);

   }

   public function getPublicSchoolValues($id){
       $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
       $database = Zend_Db::factory($dbConfig->db2);

       $sql="select id_student,pub_school_student,parental_placement from iep_student where id_student= '".$id."' ";
     //  $this->writevar1($sql,'thisis the sql command');

        $resultArray=$database->fetchAll($sql);
      // $this->writevar1($resultArray,'this is the result array');
       if($resultArray[0]['pub_school_student']=='true'){
           return '0';
       }
       else {
        if($resultArray[0]['parental_placement']=='0') return '2';
        if($resultArray[0]['parental_placement']=='1') return '3';

       }


   }
    public function getForm013($id,$id13)
    {
        $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $database = Zend_Db::factory($dbConfig->db2);
        $sql2='';
        $sql3='';
        $sql2="select get_most_recent_iep_date_conference(".$id.") as iep_date";
        $sql3="select get_most_recent_ifsp_date_conference(".$id.") as ifsp_date";


        $iep_date=$database->fetchAll($sql2);
        $ifsp_date=$database->fetchAll($sql3);

     //   if ($id=='1475756'){
     //       $this->writevar1($iep_date,'this is the iep_date');
        //    $this->writevar1($ifsp_date,'this is the ifsp_date');
     //   }

         $iepDate=$iep_date[0]['iep_date'];
         $ifspDate=$ifsp_date[0]['ifsp_date'];

         if($ifspDate> $iepDate ) {

          //   if ($id=='1475756') $this->writevar1($ifspDate,'we got here');



         $sqlFirst="select get_most_recent_ifsp_id($id) as ifsp_id";
         $form13Id=$database->fetchAll($sqlFirst);
     //    if ($id=='1475756') $this->writevar1($form13Id,'this is the array form 13 id');
         $fm13id=$form13Id[0]['ifsp_id'];

         $sql="select id_form_013,service_where, status from ifsp_services where id_form_013='".$fm13id."' order by timestamp_last_mod DESC";

         //  $this->writevar1($sql,'this is the sql statement');
         $result=$database->fetchAll($sql);

          //     if ($id=='1475756') $this->writevar1($result,'this is the result');

         $service=$result[0]['service_where'];
         if ($service=='Home') $serviceNum='1';
         if ($service=='Community') $serviceNum='2';
         if ($service=='Other')$serviceNum='3';

          //  if ($id=='1475756') $this->writevar1($serviceNum,'we got here inside the ifsp');


         return $serviceNum;
         }
         else {
            // if ($id=='1475756') $this->writevar1($serviceNum,'we got here outside the ifsp');

             return $id13;
         }



    }

    public function exportStudents()
    {
        $db = Zend_Registry::get('db');

        /**
         * get fields defined in the import file
         */
        $exportArrayConfig = include APPLICATION_PATH . $this->exportConfig->studentExportFile->configPath;

        $this->setSourceFields($exportArrayConfig);
        $table = new Model_Table_StudentTable();

        $select = $table->select();
        /**
         * allow multiple districts
         */
        $whereAdd = '';
        if (isset($this->exportConfig->id_county_district) && count($this->exportConfig->id_county_district)) {
            $first = true;
            foreach ($this->exportConfig->id_county_district as $id_county_district) {
                // ensure proper formatting of passed numbers
                if(1 !== substr_count($id_county_district, '-')) {
                    continue;
                }
                list($id_county, $id_district) = explode('-', $id_county_district);
                if($first) {
                    $whereAdd = '(id_county = \''.$id_county.'\' and id_district = \''.$id_district.'\') ';
                } else {
                    $whereAdd .= 'or (id_county = \''.$id_county.'\' and id_district = \''.$id_district.'\') ';
                }
                $first = false;
            }
            $select->where($whereAdd);
        } else
            if (count($this->exportConfig->id_district) >= 2) {
            $first = true;
            foreach ($this->exportConfig->id_district as $id_district) {
                if ($first) {
                    $whereAdd = '(id_county = \'' . $this->exportConfig->id_county . '\' and id_district = \'' . $id_district . '\') ';
                } else {
                    $whereAdd .= 'or (id_county = \'' . $this->exportConfig->id_county . '\' and id_district = \'' . $id_district . '\') ';
                }
                $first = false;
            }
            $select->where($whereAdd);
        } else {
            $select->where('id_county = ?', $this->exportConfig->id_county);
            $select->where('id_district = ?', $this->exportConfig->id_district);
        }
        $dateCutoff=$this->getTransferReportCutoff();
        if (isset($this->exportConfig->limitToActive) && $this->exportConfig->limitToActive) {
            $select->where("(status ='Active' or sesis_exit_date > '".$dateCutoff."' )");
        }



//        $select->limit(10);
        echo "\n" . "\n" . $select . "\n";
        $stmt = $db->query($select);
      //  $this->writevar1($stmt,'this is the sql statement');
        $date1=$this->getTransferReportCutoff();
     //   $this->writevar1($date1,'last date of transfer');
        $data = array();
        $studentCount = 1;




        while ($data = $stmt->fetch()) {
            $studentCount++;
        }

        $counter = 1;
        $exportPath = realpath(
                APPLICATION_PATH . '/../' . $this->exportConfig->studentExportFile->filepath . '/'
            ) . '/' . $this->exportConfig->studentExportFile->filename;
        $fp = fopen($exportPath, 'w');
        if (false == $fp) {
            // @todo add log entry
            Zend_Debug::dump('error on file creation');
            return false;
        } else {
            $headerLine = $this->buildHeaderLine();
            file_put_contents($exportPath, $this->arrayToCsv($headerLine, "\t", '"', true) . $this->eol);
            $stmt = $db->query($select);
            while ($student = $stmt->fetchObject()) {
                if ($this->debug) {
                    echo 'Processing student: ' . $student->id_student . " ($counter of $studentCount)\n";
                } else {
                    echo '.';
                }
                $exportLine = $this->buildExportLine($student);



                // Mike says put it right here man 2-27-2017
                $exportLine[12]=$this->getPublicSchoolValues($student->id_student);
                $item13=$exportLine[13];
                $exportLine[13]=$this->getForm013($student->id_student,$item13);

                $dateTransfer= $this->getTransferReportCutoff();
                $studentExitInfo=$this->getStudentExitInfo($student->id_student);
              //  $this->writevar1($studentExitInfo,'this is the student exit info');
                $temp=date('2010-10-02 00:00:00');
            //
            //    $this->writevar1($studentExitInfo,'this is the student info');




              //  $this->writevar1($t,'this is transfer report cutoff date');
                if($exportLine[19]!='1') $exportLine[19]='2';


                $exportLine[20]=$this->getForm013MeetingDate($student->id_student);

                file_put_contents($exportPath, $this->arrayToCsv($exportLine, "\t", '"', true) . $this->eol, FILE_APPEND);
                $counter++;
            }
            fclose($fp);
            return true;
        }

    }

    /**
     * Formats a line (passed as a fields  array) as CSV and returns the CSV as a string.
     * Adapted from http://us3.php.net/manual/en/function.fputcsv.php#87120
     */
    function arrayToCsv(array &$fields, $delimiter = ';', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false)
    {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');

        $output = array();
        foreach ($fields as $field) {
            if ($field === null && $nullToMysqlNull) {
                $output[] = 'NULL';
                continue;
            }

            // Enclose fields containing $delimiter, $enclosure or whitespace
            if ($encloseAll || preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field)) {
                $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
            } else {
                $output[] = $field;
            }
        }

        return implode($delimiter, $output);
    }

    function fputcsv_eol($handle, $array, $delimiter = ',', $enclosure = '"', $eol = "\r\n")
    {
        $return = fputcsv($handle, $array, $delimiter, $enclosure);
        if ($return !== FALSE && "\n" != $eol && 0 === fseek($handle, -1, SEEK_CUR)) {
            fwrite($handle, $eol);
        }
        return $return;
    }

    public function uniqueKey($key, &$arr)
    {
        if (!array_key_exists($key, $arr)) {
            $arr[$key] = $data;
        } else {
            Zend_Debug::dump($arr, 'key exists ' . $key);
        }

    }

    public function buildHeaderLine()
    {
        $retArr = array();
        foreach ($this->getSourceFields() as $key => $configLine) {
            foreach ($configLine as $ffName => $type) {
                if (is_string($key)) {
                    $retArr[] = $key;
                } else {
                    $retArr[] = $ffName;
                }

            }
        }
        return $retArr;
    }

    function getSubFormField($form, $subFormName, $fieldName)
    {
        $subForm = $form->findDependentRowset($subFormName);
        if ($subForm->count()) {
            $retString = '';
            foreach ($subForm as $relatedRow) {
                if (!isset($relatedRow->$fieldName)) {
                    echo $fieldName . " not found\n";
                }
                if (!isset($relatedRow->$fieldName) || '' == $relatedRow->$fieldName) {
                    continue;
                }
                if ('' != $retString) {
                    $retString .= $this->delimiter;
                }
                $retString .= $relatedRow->$fieldName;
            }
            return $retString;
        }
        return '';
    }

    public function buildExportLine($student)
    {

        /**
         * reset forms
         */
        for ($i = 1; $i <= 25; $i++) {
            $formVarName = 'form' . substr('000' . $i, -3, 3);
            $this->$formVarName = null;
        }

        $retArr = array();
        foreach ($this->getSourceFields() as $configLine) {
            foreach ($configLine as $ffName => $fieldFormOrFunction) {
                if (substr_count($fieldFormOrFunction, '-') > 0) {
                    if (substr_count($fieldFormOrFunction, '-') == 1) {
                        list($formNumber, $draftOrFinal) = explode('-', $fieldFormOrFunction);
                    } elseif (substr_count($fieldFormOrFunction, '-') == 2) {
                        list($formNumber, $draftOrFinal, $subFormName) = explode('-', $fieldFormOrFunction);
                    }

                    // fetch the form
                    switch ($formNumber) {
                        case '001':
                        case '002':
                        case '003':
                        case '004':
                        case '005':
                        case '008':
                        case '009':
                        case '010':
                        case '013':
                        case '017':
                            $formKey = 'form' . $formNumber;
                            $form = $this->$formKey = $this->mostRecentFinalForm($student, $formNumber);
                            break;
                        default:
                            Zend_Debug::dump(
                                $fieldFormOrFunction,
                                'form number out of range or not allowed (' . $formNumber . ')'
                            );
                    }
                    if (is_null($form)) {
//                    Zend_Debug::dump('form is null:'.$formNumber );
                        $retArr[] = '';
                    } elseif (isset($subFormName)) {
                        $subformData = $this->getSubFormField($form, $subFormName, $ffName);
                        if (is_array($subformData)) {
                            Zend_Debug::dump($subformData, 'subformData');
                        }
                        if (!is_null($subformData) && '' != $subformData) {
                            $retArr[] = $this->sanitizeOutput($this->getSubFormField($form, $subFormName, $ffName));
                        } else {
                            $retArr[] = '';
                        }
                    } elseif (!isset($form->$ffName)) {
                        // @todo add log entry
                        Zend_Debug::dump($ffName, $form->$ffName . 'field not set');
                        $retArr[] = '';
                    } else {
                        $retArr[] = $this->sanitizeOutput($form->$ffName);
                    }
                    unset($subFormName);

                } else {
                    if ('field' == $fieldFormOrFunction) {
                        /**
                         * use the straight field
                         */
                        $retArr[] = $this->sanitizeOutput($student->$ffName);
                    } elseif ('function' == $fieldFormOrFunction) {
                        /*
                         * $fieldDefOrFunctionName is not 'field'
                         * it is now the name of the function to call
                         */
                        if (!method_exists($this, $ffName)) {
                            // @todo add log entry
                            Zend_Debug::dump($ffName, 'no function found');
                        } else {
                            $retArr[] = $this->sanitizeOutput($this->$ffName($student));
                        }
                    }
                }
            }
        }
        return $retArr;
    }

    public function sanitizeOutput($data)
    {
        $allowedTags = array(
            'a',
            'b',
            'em',
            'strong'
        );
        $allowedAttributes = array('href');

        // Filter html tags
        $stripTags = new Zend_Filter_StripTags($allowedTags, $allowedAttributes);

        // remove returns
        $data = $this->removeReturns($data);

        // now filter the string
        return $stripTags->filter($data);
    }

    public function lastMdt($student)
    {
        if (!is_null($this->form002)) {
            return $this->form002;
        }
        try {
            $form002Obj = new Model_Table_Form002();
            $form002 = $form002Obj->mostRecentFinalForm($student->id_student);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        if (count($form002)) {
            $this->form002 = $form002;
            return $form002;
        } else {
            return null;
        }
    }

    public function lastIep($student)
    {
        if (!is_null($this->form004)) {
            return $this->form004;
        }
        try {
            $form004Obj = new Model_Table_Form004();
            $form004 = $form004Obj->mostRecentFinalForm($student->id_student);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        if (count($form004)) {
            $this->form004 = $form004;
            return $form004;
        } else {
            return null;
        }
    }

    public function mostRecentFinalForm($student, $formNo)
    {
        $localVar = 'form' . substr('000' . $formNo, -3, 3);
        $modelName = 'Model_Table_Form' . substr('000' . $formNo, -3, 3);
        if (!is_null($this->$localVar)) {
            return $this->$localVar;
        }
        try {
            $formObj = new $modelName();
            $form = $formObj->mostRecentFinalForm($student->id_student);

        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        if (count($form)) {
            $this->$localVar = $form;
            return $form;
        } else {
            return null;
        }
    }

    public function lastMdtDateMdt($student)
    {
        $mdt = $this->lastMdt($student);
        if (is_object($mdt)) {
            return $mdt->date_mdt;
        } else {
            return null;
        }
    }

    public function lastIepDateConference($student)
    {
        $iep = $this->lastIep($student);
        if (is_object($iep)) {
            return $iep->date_conference;
        } else {
            return null;
        }
    }

    public function getPersonnel($personnelId)
    {
        $personnelObj = new Model_Table_PersonnelTable();
        $personnel = $personnelObj->fetchRow("id_personnel = '$personnelId'");
        if (count($personnel)) {
            return $personnel;
        } else {
            return null;
        }
    }

    public function nameDistrict($student)
    {
        $districtObj = new Model_Table_District();
        $district = $districtObj->fetchRow("id_county = '" . $student->id_county . "' and id_district = '" . $student->id_district . "'");
        if (count($district)) {
            return $district->name_district;
        } else {
            return null;
        }

    }

    public function removeReturns($text)
    {
        $text = str_replace("\n", "", $text);
        $text = str_replace("\r", "", $text);
        $text = str_replace("&apos;", "'", $text);
        $text = $this->removeImages($text);
        $text = $this->removeBadCharacters($text);
        $text = html_entity_decode($text);
        return $text;
    }

    public function removeImages($text)
    {
        if (is_array($text)) {
            Zend_Debug::dump($text);
        }
        if (preg_match('/<img(.*?)\>/', $text)) {
            $text = preg_replace('/<img(.*?)\>/', "$2", $text);
        }
        return $text;
    }

    public function removeBadCharacters($text)
    {
        $regex = <<<'END'
/
  (
    (?: [\x00-\x7F]               # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]    # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2} # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3} # quadruple-byte sequence 11110xxx 10xxxxxx * 3
    ){1,100}                      # ...one or more times
  )
| ( [\x80-\xBF] )                 # invalid byte in range 10000000 - 10111111
| ( [\xC0-\xFF] )                 # invalid byte in range 11000000 - 11111111
/x
END;
        return preg_replace($regex, '$1', $text);
    }


}


