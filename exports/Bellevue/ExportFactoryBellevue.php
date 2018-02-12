<?php

class ExportFactoryBellevue
{
// Note: On line 299 you can save a lot of time by adding a student id to the district sql command.
// It will only get that one student.

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
    }


    public function writevar1($var1,$var2) {

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
            $mail->addTo("mdanahy@esucc.org", "Jesse");

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
        $studentCount = 0;
        while ($data = $stmt->fetch()) {
            $studentCount++;
        }
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
     //    print_r($sourceFields); die();This certainly printed out all the fields from the ini.
    }

    public function getSourceFields()
    {
        return $this->sourceFields;
    }



    public function exportStudents()
    {
        $db = Zend_Registry::get('db');

        /**
         * get fields defined in the import file
         */
        $exportArrayConfig = include APPLICATION_PATH . $this->exportConfig->studentExportFile->configPath;
    //  print_r($exportArrayConfig);die();  //prints out the export ini tells if field,function or something else.
        $this->setSourceFields($exportArrayConfig);
        $table = new Model_Table_StudentTable();
    /* print_r($table); // certainly did not print out the table name
        [srs_id_number] => Array
        (
            [id_student] => field
            )
      */

        $select = $table->select();
        // This is setting up the sql command and parameters.
     //  $this->writevar1($select, 'the select command line 260 factore');

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
                    $whereAdd = '(id_county = \''.$id_county.'\' and id_district = \''.$id_district.'\'and id_student_local> \'10\') ';
                } else {
                    $whereAdd .= 'or (id_county = \''.$id_county.'\' and id_district = \''.$id_district.'\' and id_student_local> \'10\') ';
                }
                $first = false;
            }

            $select->where($whereAdd);
        } else

            /*
             * Mike added the and id_student_local > 10 at Michelle Andrade's request so  that studentss did not show up without a local id
             * 2-10-2018
             */
            if (count($this->exportConfig->id_district) >= 2) {
            $first = true;
            foreach ($this->exportConfig->id_district as $id_district) {
                if ($first) {
                    $whereAdd = '(id_county = \'' . $this->exportConfig->id_county . '\' and id_district = \'' . $id_district . '\' and id_student_local> \'10\') ';
                } else {
                    $whereAdd .= 'or (id_county = \'' . $this->exportConfig->id_county . '\' and id_district = \'' . $id_district . '\' and id_student_local> \'10\') ';
                }
                $first = false;
            }
            $select->where($whereAdd); //print_r($whereAdd);die();
        } else {
            $select->where('id_county = ?', $this->exportConfig->id_county);
            $select->where('id_district = ?', $this->exportConfig->id_district);
            $select->where('id_student_local > ?','10');

           // $select->where('id_student= ?','1451293');
        }
        if (isset($this->exportConfig->limitToActive) && $this->exportConfig->limitToActive) {
            $select->where('status = ?', 'Active');
            $select->where('id_student_local > ?','10');
         //   $select->where('id_student= ?','1282236');
        }
//        $select->limit(10);
       echo "\n" . "\n" . $select . "\n";
        $stmt = $db->query($select);
    //    $this->writevar1($stmt,'this is the db statement');
       // print_r($stmt); die(); //returns something fetall can understand  [queryString] => SELECT "iep_student".* FROM "iep_student" WHERE (id_county = '77') AND (id_district = '0001') AND (status = 'Active')
        $data = array();
        $studentCount = 1;
        while ($data = $stmt->fetch()) {
            $studentCount++;
       //    print_r($data);   //die();  Get the whole array of each student from iep_student
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
            //print_r($stmt); prints out the sql stmt with id_district and id_county set
            while ($student = $stmt->fetchObject()) {// print_r($student);
                if ($this->debug) {
                    echo 'Processing student: ' . $student->id_student . " ($counter of $studentCount)\n";
                } else {
                    echo '.';
                }
                $exportLine = $this->buildExportLine($student);// the buildExportLIne function goes and gets all the others based on the array type in exportConfig.php
        //     print_r($exportLine); // this prints the fields in array format.
          /*  * Array
            (

            [0] => 1414948
            )
            */
             // print_r($exportLine."   ");
       //        print_r($exportLine);

//changed by Mike D 11/01/2016
//$this->writevar1($exportLine[5],'this is line 5');
$exportLine[24]=$this->nssrsChangeValue($exportLine[22]);
            if ($exportLine[24]=='8') {
                $exportLine[24]=$this->nssrsChangeValue($exportLine[23]);
            }

 //  Mike  on 1-11-2016 did this so that the values would show up in powerschool as a 0 instead of a blank
      if($exportLine[10]==''||$exportLine[10]==NULL){
          $exportLine[10]=0;
      }

      if($exportLine[11]==''||$exportLine[11]==NULL){
          $exportLine[11]=0;
      }

//            $exportLine[24]=$this->nssrsChangeValue($exportLine[22]);
// end of Mike change

            //$exportLine[2]=$this->changeTrueFalse($exportLine[2]);

             // $exportLine[9]=changeDateFormat($exportLine[9])
         //   $dateString=$exportLine[9];


            $exportLine[3] = $this->chDateFormat($exportLine[3]);
            $exportLine[4] = $this->chDateFormat($exportLine[4]);
            $exportLine[5] = $this->chDateFormat($exportLine[5]);


        $exportLine[13] = $this->chDateFormat($exportLine[13]);
        //$exportLine[15] = $this->chDateFormat($exportLine[15]);
        $exportLine[16] = $this->chDateFormat($exportLine[16]);
         $exportLine[17] = $this->chDateFormat($exportLine[17]);



         $exportLine[18]= strip_tags($exportLine[18]);
         $search1 = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
         $replace1 = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
         $exportLine[18]=str_replace($search1,$replace1,$exportLine[18]);


         $exportLine[20] = $this->chDateFormat($exportLine[20]);

         if($exportLine[21]=='05') $exportLine[21]='5';


        //  $exportLine[30] = $this->chDateFormat($exportLine[30]);

         $t=$exportLine[33];
       //   $t=preg_replace('/\s+/', '', $t);
          $exportLine[33]=substr($t,0,3995);
          $exportLine[33]=$exportLine[33];

          if($exportLine[33]==NULL) $exportLine[33]='No_Qualified_FBA_is_Available';

          $t=strip_tags($exportLine[32]);
       //   $t=preg_replace('/\s+/', '', $t);
          $exportLine[32]=substr($t,0,3995);
          $exportLine[32]=$exportLine[32];
          if($exportLine[32]==NULL) $exportLine[32]='No Qualified  FBA is Available';

          $t=strip_tags($exportLine[31]);
     //     $t=preg_replace('/\s+/', '', $t);
          $exportLine[31]=substr($t,0,3995);
          $exportLine[31]=$exportLine[31];
          if($exportLine[31]==NULL) $exportLine[31]='No Qualified  FBA is Available';

          $t=$exportLine[30];
     //     $t=preg_replace('/\s+/', '', $t);
          $exportLine[30]=substr($t,0,3995);
          $exportLine[30]=$exportLine[30];
          if($exportLine[30]==NULL) $exportLine[30]='No Qualified  FBA is Available';

          $t=strip_tags($exportLine[29]);
        //  $t=preg_replace('/\s+/', '', $t);
          $exportLine[29]=substr($t,0,3995);
          $exportLine[29]=$exportLine[29];
          if($exportLine[29]==NULL) $exportLine[29]='No Qualified  FBA is Available';

          $t=$exportLine[28];
        //  $t=preg_replace('/\s+/', '', $t);
          $exportLine[28]=substr($t,0,3995);
          $exportLine[28]=$exportLine[28];
         if($exportLine[28]==NULL) $exportLine[28]='No Qualified  FBA is Available';

          $t=strip_tags($exportLine[27]);
      //    $t=preg_replace('/\s+/', '', $t);
          $exportLine[27]=substr($t,0,3995);
          $exportLine[27]=$exportLine[27];
         if($exportLine[27]==NULL) $exportLine[27]='No Qualified  FBA is Available';

          $t=strip_tags($exportLine[26]);
       //   $t=preg_replace('/\s+/', '', $t);
          $exportLine[26]=substr($t,0,3995);
          $exportLine[26]=$exportLine[26];
          if($exportLine[26]==NULL) $exportLine[26]='No Qualified  FBA is Available';


         // Mike added this 11/02/16 in order to make the field a 2 instead of null
           if ($exportLine[1]!='1'){
             $exportLine[1]=2;
          }
         $exportLine[25]=$exportLine[10];
         $exportLine[32]=$this->removeReturns($exportLine[32]);

         $exportLine[33]=$this->removeReturns($exportLine[33]);
         $exportLine[33]=strip_tags($exportLine[33]);
         // Mike changes 11-21-2017 SRS-135
        // $exportLine[12]='';
         $exportLine[13]='';
         $exportLine[14]='';
         $exportLine[16]='';
     //    $exportLine[18]='';
         $exportLine[30]='';
         $exportLine[28]='';


            file_put_contents($exportPath, $this->arrayToCsv($exportLine) . $this->eol, FILE_APPEND);
                $counter++; // This puts all the students in i.e. all the fields.  This is the line that writes it.
            }
            fclose($fp);
            return true;
        }

    }

    // Mike added this to change the dates"


    function changeTrueFalse($bool){

        if ($bool==1) {
             return 1;
         }
        else return 2;
    }

    function chDateFormat($dateline){
        if($dateline!=NULL){
        $newDateString = date_format(date_create_from_format('Y-m-d', $dateline), 'm-d-Y');
        return $newDateString;
    }  else
        {
          return $dateline;
        }
    }

    function nssrsChangeValue($exportLine) {


           $arr=explode(":",$exportLine);


           if( in_array(("Speech-language therapy"), $arr) ||
               in_array(("Speech-Language therapy"), $arr) ||
               in_array(("Speech-language Therapy"), $arr) ||
               in_array(("Speech-Language Therapy"), $arr) ||
               in_array(("Speech/language therapy"), $arr) ||
               in_array(("Speech/Language therapy"), $arr) ||
               in_array(("Speech/language Therapy"), $arr) ||
               in_array(("Speech/Language Therapy"), $arr) ||
               in_array(strtolower("Speech-language Therapy"), $arr) ||
               in_array(strtolower("Speech/language Therapy"), $arr)
               ) {
                   $spLang =  1;}
                   else {
                   $spLang =  0;}

               if( in_array(("Occupational Therapy Services"), $arr) ||
                   in_array(strtolower("Occupational Therapy Services"), $arr)
                   ) {
                       $occTherSer =  1; }
                       else {
                       $occTherSer =  0; }



                   if( in_array(("Physical Therapy"), $arr) ||
                       in_array(("physical therapy"), $arr)
                       ) {
                           $phyTherSer =  1;
                       } else {
                           $phyTherSer =  0;
                       }

                       #if(1000254 == $sessIdUser) echo "spLang: $spLang<BR>";
                       #if(1000254 == $sessIdUser) echo "occTherSer: $occTherSer<BR>";
                       #if(1000254 == $sessIdUser) echo "phyTherSer: $phyTherSer<BR>";

                       if($spLang == 1 && $occTherSer == 1 && $phyTherSer == 1)
                       {
                           return 7;
                       }
                       elseif($spLang == 1 && $occTherSer == 1 && $phyTherSer == 0) {
                           return 6; }
                       elseif($spLang == 1 && $occTherSer == 0 && $phyTherSer == 1) {
                           return 5; }
                       elseif($spLang == 0 && $occTherSer == 1 && $phyTherSer == 1) {
                           return 4;}
                       elseif($spLang == 1 && $occTherSer == 0 && $phyTherSer == 0) {
                           return 3; }
                       elseif($spLang == 0 && $occTherSer == 0 && $phyTherSer == 1) {
                           return 2;}
                       elseif($spLang == 0 && $occTherSer == 1 && $phyTherSer == 0) {
                           return 1; }
                       else { return 8; }



               }
    /**
     * Formats a line (passed as a fields  array) as CSV and returns the CSV as a string.
     * Adapted from http://us3.php.net/manual/en/function.fputcsv.php#87120
     */
    function arrayToCsv(array &$fields, $delimiter = "\t", $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false)
    {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');

        $output = array();
        foreach ($fields as $field) {
         //  print_r($fields); This prints out an array of field headers from 0-35 on the array.  Not sure why do it for every student
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
      //    print_r($output[36]);
        }

        // print_r(implode($delimiter,$output)); die(); // This actually prints out the headers to the csv file properly.

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
    /*    print_r($retArr);die();

    *  [0] => srs_id_number
    [1] => U_SPED.EI_Referal
    [2] => State_StudentNumber
    [3] => S_NE_STU_SPED_X.Alternate_Assessment
    */

        return $retArr;
    }

    function getSubFormField($form, $subFormName, $fieldName)
    {
      // print_r($form);die();// prints out the contents of the iep_form_004 for the form fields. Everything for the one student
        // print_r($subFormName); die(); //prints out something like this. Model_Table_Form004TeamMember
      //  print_r($fieldName); die(); prints out Processing student: 1457849 (1 of 2224) participant_name


        $subForm = $form->findDependentRowset($subFormName);
        //  print_r($subForm);die();
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
    // print_r($student); // Yea, this looks like the iep_student fields
        /**
         * reset forms
         */
        for ($i = 1; $i <= 25; $i++) {
            $formVarName = 'form' . substr('000' . $i, -3, 3);

     // print_r($formVarName);  //this prints out the 25 of the following: form001form002form003..etc till form025
         //   print_r($this->formVarName);die(); //prints nothing to the screen.
            $this->$formVarName = null;

        }

        $retArr = array();


        foreach ($this->getSourceFields() as $configLine) {

      // Prints out each line in exportConfig.php as per what Bellevue has and not the array field name
      //   $this->writevar1($configLine,'this is each config line');
            foreach ($configLine as $ffName => $fieldFormOrFunction) {
                if (substr_count($fieldFormOrFunction, '-') > 0) {
                    if (substr_count($fieldFormOrFunction, '-') == 1) {
                        list($formNumber, $draftOrFinal) = explode('-', $fieldFormOrFunction);
                   //     print_r($fieldFormOrFunction);echo "==\n";
                    } elseif (substr_count($fieldFormOrFunction, '-') == 2) {
                        list($formNumber, $draftOrFinal, $subFormName) = explode('-', $fieldFormOrFunction);
                    //   print_r($fieldFormOrFunction);echo "--\n";
                    }

                    // fetch the form
                    switch ($formNumber) {
                        case '001':
                        case '002':
                        case '003':
                        case '004':
                        case '005':
                        case '007':
                        case '008':
                        case '009':
                        case '010':
                        case '012':  // added by Mike
                        case '013':
                        case '017':
                        case '019':
                        case '022': // added by Mike
                            $formKey = 'form' . $formNumber;//print_r($formKey);echo "\n"; for each student it prints out form004form004form002 etc..
                            $form = $this->$formKey = $this->mostRecentFinalForm($student, $formNumber);// print_r($form);echo "\n";die(); //this returns the form004 last updated
                        //    $this->writevar1($formKey,'this is the formKey');
                         //   $this->writevar1($form,'this is the form');
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
           //             print_r($retArr); just sanitizes the output to an array of the iep_student
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


                       /*
                         if(!is_null($retArr[9])) {
                             print_r("We got here");print_r($retArr[9]); print_r("\n");
                           $new String = date_format(date_create_from_format('Y-m-d', $retArr[9]), 'd-m-Y');
                          //    $retArr[9]=$newDateString;
                           print_r($newDateString);print_r("\n");


                           $retArr[9]= date_format(date_create_from_format('Y-m-d', $retArr[9]), 'd-m-Y');
                         }
                          */


                           //$retArr[37]=9999;
                        //    print_r($this->$ffName($student)); Does not print what I think it should.  Need to find array name
                        }
                    }
                }
            }
        }

    //    $this->writevar1($retArr,'this is the array');  this actuall builds the export line according to the config file.
    //  print_r($retArr);
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

    public function lastIfsp($student){

    }

    public function lastIepCard($student){

    }


// Note there was one that
    public function mostRecentFinalForm($student, $formNo)
    {


        $localVar = 'form' . substr('000' . $formNo, -3, 3);
        $modelName = 'Model_Table_Form' . substr('000' . $formNo, -3, 3);

    //    $this->writevar1($modelName,'this is the model name');
     //   $this->writevar1($localVar,'this is the localvar');
      // returns something like Model_Table_Form004 and form004 respectively
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
            // Mike added this 1-21-2017 so that any forms over year were not displayed.  Lot of writeup
            if($modelName=='Model_Table_Form019') {

                $theDate=strtotime($form['date_notice']);
                $now =time();
                $differ=$now-$theDate;
                $differ2=$differ/(60*60*24);
// Mike added this section 1-24-2017 at reqeust
            /*    if ($form['bi_behavior_management']=='') $form['bi_behavior_management']='NO QUAlIFIED FBA Avaailable';
                if ($form['bi_behavioral_goal']=='') $form['bi_behavioral_goal']='NO QUAlIFIED FBA Avaailable';
                if ($form['bi_crisis_intervention']=='') $form['bi_crisis_intervention']='NO QUAlIFIED FBA Avaailable';
                if ($form['fa_desc_of_problem']=='') $form['fa_desc_of_problem']='NO QUAlIFIED FBA Avaailable';
                if ($form['bi_alternative_discipline_reason']=='') $form['bi_alternative_discipline_reason']='NO QUAlIFIED FBA Avaailable';
                if ($form['fa_specific_antecedents']=='') $form['fa_specific_antecedents']='NO QUAlIFIED FBA Avaailable';
                if ($form['bi_modifications']=='') $form['bi_modifications']='NO QUAlIFIED FBA Avaailable';
           */
             // Mike added this 1-21-2017 so that any forms over year were not displayed.  Lot of writeup
                if($differ2 >=365 ){
                  //  $this->writevar1($differ2,'this is the time difference');
                    $form['bi_behavior_management']='NO QUALIFIED FBA Available';
                    $form['bi_behavioral_goal']='NO QUALIFIED FBA Available';
                    $form['bi_crisis_intervention']='NO QUALIFIED FBA Available';
                    $form['fa_desc_of_problem']='NO QUALIFIED FBA Available';
                    $form['bi_alternative_discipline_reason']='NO QUALIFIED FBA Available';
                    $form['fa_specific_antecedents']='NO QUALIFIED FBA Available';
                    $form['bi_modifications']='NO QUALIFIED FBA Available';
                //    $this->writevar1($form,'this is the form modifield');
                }
            }
            // End of Mikes add.

            return $form;

        } else {
            return null;
        }
    }


        /* if (count($form)) {
            $this->$localVar = $form;
            return $form;
        } else {
            return null;
        } */





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
        // Michael or Mike added this June 1 beacuse PowerSChool does not like this combination. It adds a hashtag called ?

        $text =str_replace("- "," ",$text);
        $text=str_replace("<br />"," ",$text);
        $text = str_replace("\r", "  ", $text);
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


