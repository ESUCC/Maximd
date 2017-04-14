<?php

/**
 * require the main import factory
 */
require_once('../ImportFactory.php');

class FremontImport extends ImportFactory {

    var $fieldsArr = array(
        'student_number',
        'state_studentnumber',

        'cnt1_fname',
        'cnt1_lname',
        'cnt1_street',
        'cnt1_city',
        'cnt1_state',
        'cnt1_zip',
        'cnt1_email',
        'cnt1_hphone',
        'cnt1_wphone',

        'cnt2_fname',
        'cnt2_lname',
        'cnt2_street',
        'cnt2_city',
        'cnt2_state',
        'cnt2_zip',
        'cnt2_email',
        'cnt2_hphone',
        'cnt2_wphone',

        'cnt3_fname',
        'cnt3_lname',
        'cnt3_street',
        'cnt3_city',
        'cnt3_state',
        'cnt3_zip',
        'cnt3_email',
        'cnt3_hphone',
        'cnt3_wphone',

        'cnt4_fname',
        'cnt4_lname',
        'cnt4_street',
        'cnt4_city',
        'cnt4_state',
        'cnt4_zip',
        'cnt4_email',
        'cnt4_hphone',
        'cnt4_wphone',

        'cnt5_fname',
        'cnt5_lname',
        'cnt5_street',
        'cnt5_city',
        'cnt5_state',
        'cnt5_zip',
        'cnt5_email',
        'cnt5_hphone',
        'cnt5_wphone',

    );
    function initEmail($emailConfig)
    {
        $transport = new Zend_Mail_Transport_Smtp($emailConfig->host, $emailConfig->toArray());
        Zend_Mail::setDefaultTransport($transport);
    }

    function __construct() {
        error_reporting(E_ALL);
        ini_set('displayErrors', 1);
        parent::__construct();

        $importConfig = new Zend_Config_Ini('import.ini', APPLICATION_ENV);
        $this->importConfig = $importConfig;
        $this->initEmail($this->importConfig->email);
        $finalLog = "\n\nBegin Import...\n\n";
        if($this->importConfig->getFtpFiles) {
            /**
             * FTP RESULTS
             */
            $success = true;
            $conn_id = ftp_connect($this->importConfig->ftp->host) or die ("Cannot connect to host");
            // login with username and password
            $login_result = ftp_login($conn_id, $this->importConfig->ftp->username, $this->importConfig->ftp->password);
            if($login_result) {
                ftp_pasv($conn_id, true); // turn on passive mode
                ftp_login($conn_id, $this->importConfig->ftp->username, $this->importConfig->ftp->password) or die("Cannot login");
                /**
                 * get the files
                 */
                if (ftp_get($conn_id, $this->importConfig->parentImportFile->filename, $this->importConfig->parentImportFile->filename, FTP_BINARY)) {
                    echo "Successfully written to ".$this->importConfig->parentImportFile->filename."\n";
                } else {
                    $success = false;
                }
                if (ftp_get($conn_id, $this->importConfig->studentImportFile->filename, $this->importConfig->studentImportFile->filename, FTP_BINARY)) {
                    echo "Successfully written to ".$this->importConfig->studentImportFile->filename."\n";
                } else {
                    $success = false;
                }
                // close the FTP stream
                ftp_close($conn_id);
            } else {
                $success = false;
            }
            if(!$success) {
                echo "there was an error getting the files";
                return false;
            }
        }
        /**
         * preflight file
         */
        if(false===$this->preFlightFile()) {
            return false;
        }

        /**
         * custom mods for Fremont
         * parse custom file format into one that the importer can handle
         */
        echo "\n\nConverting Fremont Guardian File to SRS Guardian Import Format\n";
        $finalLog .= "Converting Fremont File to SRS Guardian Import Format\n";
        $sourceFile = $this->importConfig->parentImportFile->filename;
        $destinationFile = basename($sourceFile) . '_TEMP.txt';
        $this->convertFile($sourceFile, $destinationFile);

        $finalLog .= $this->dumpLog();
        $this->clearMetaData();

        /**
         * logging helper
         * must be fired after preflight file
         */
        $emptyDataSources = $this->countEmptyDataSource();
        $finalLog .= "\n\nPre-import students with an empty data_source field: " . count($emptyDataSources)."\n";

        $finalLog .= $this->dumpLog();
        $this->clearMetaData();

        /**
         * process the student file
         */
        $this->setDelimiter("\t");
        echo "\n\nProcess Student File...\n";
        $finalLog .= "\n\nProcess Student File...\n";
        if(false===$this->processStudentFile()) {
            return false;
        }
        /**
         * reset instance
         */
        $finalLog .= $this->dumpLog();
        $this->clearMetaData();

        /**
         * process the guardian file
         *
         * clear fields used by student file
         */
        echo "\n\nProcess Guardian File...\n";
        $this->setDelimiter(",");
        $finalLog .= "\n\nProcess Guardian File...\n";
        if(false===$this->processGuardianFile($destinationFile)) {
            return false;
        }
        $finalLog .= $this->dumpLog();

        /**
         * post import updates on the district
         */

        /**
         * EMAIL THE RESULTS
         */
        $this->sendNotificationEmail($this->importConfig->email, $finalLog);

        echo $finalLog;
        return true;
    }


    /**
     * custom functions
     */
    function convertFile($sourceFilePath, $destinationFile) {
        # OPEN SOURCE FILE
        if ( !$fp = fopen( $sourceFilePath, 'r' ) ) {
            return false;
        }
        # OPEN DESTINATION FILE
        if ( !$dfp = fopen( $destinationFile, 'w+' ) ) {
            return false;
        }
        # PROCESS EACH LINE
        while ( !feof( $fp ) ) {
            $lineBuf = fgets( $fp);
            $processLine = $this->convertLine($lineBuf);
            if(false!=$processLine) {
                if (fwrite($dfp, $processLine) === false) {
                    return false;
                }
            }
        }
        fclose( $fp);
    }
    function convertLine($lineBuf) {
        $this->currentLine++;
        echo '.';
        /**
         * preProcessLine: makes sure we take care of quotes in quotes
         * and trim extra spaces and carriage returns
         */
        $importRowColumns = explode( "\t", $this->preProcessLine( trim($lineBuf) ));
        $countImportRowColumns = count( $importRowColumns );

        /**
         * error check field count
         */
        if(count($this->fieldsArr) != count($importRowColumns)) {
            $convertedArr = array_combine(array_slice($this->fieldsArr, 0, count($importRowColumns)), $importRowColumns);
//            $this->fieldsArr = array_slice($this->fieldsArr, 0, count($importRowColumns));
//            if(count($this->fieldsArr) != count($importRowColumns)) {
//                print_r($this->fieldsArr);
//                print_r($importRowColumns);
//                die;
//            }

//            $this->addLog($this->currentLine, "Field Count Error: want:".count($this->fieldsArr) .' -- got:'. count($importRowColumns));
//            echo "Field Count Error: want:".count($this->fieldsArr) .' -- got:'. count($importRowColumns) . "\n";
//            print_r($importRowColumns);
//            die;
//            return false;
        } else {
            $convertedArr = array_combine($this->fieldsArr, $importRowColumns);
        }
        array_walk($convertedArr, create_function('&$val', '$val = trim($val);'));
        $retString = '';

        if(!isset($convertedArr['student_number']) || !isset($convertedArr['state_studentnumber'])) {
            echo "\nline failed: " . implode(',', $convertedArr)."\n";
            return false;
        }
        /**
         * The cnt1 fields are always biological mother.
         */
        $writeArr = array(
            'name_first' => $this->tryGetField($convertedArr, 'cnt1_fname'),
            'name_last' => $this->tryGetField($convertedArr, 'cnt1_lname'),
            'address_street1' => $this->tryGetField($convertedArr, 'cnt1_street'),
            'address_city' => $this->tryGetField($convertedArr, 'cnt1_city'),
            'address_state' => $this->tryGetField($convertedArr, 'cnt1_state'),
            'address_zip' => $this->tryGetField($convertedArr, 'cnt1_zip'),
            'email_address' => $this->tryGetField($convertedArr, 'cnt1_email'),
            'phone_home' => $this->tryGetField($convertedArr, 'cnt1_hphone'),
            'phone_work' => $this->tryGetField($convertedArr, 'cnt1_wphone'),
        );
        if(!$this->writeArrayEmpty($writeArr)) {
//            print_r($writeArr);
            $writeArr['id_student_local'] = $convertedArr['student_number'];
            $writeArr['unique_id_state'] = $convertedArr['state_studentnumber'];
            $retString .= implode($writeArr, ',') . "\n";
        }

        /**
         * The cnt2 fields are always the biological father.
         */
        $writeArr = array(
            'name_first' => $this->tryGetField($convertedArr, 'cnt2_fname'),
            'name_last' => $this->tryGetField($convertedArr, 'cnt2_lname'),
            'address_street1' => $this->tryGetField($convertedArr, 'cnt2_street'),
            'address_city' => $this->tryGetField($convertedArr, 'cnt2_city'),
            'address_state' => $this->tryGetField($convertedArr, 'cnt2_state'),
            'address_zip' => $this->tryGetField($convertedArr, 'cnt2_zip'),
            'email_address' => $this->tryGetField($convertedArr, 'cnt2_email'),
            'phone_home' => $this->tryGetField($convertedArr, 'cnt2_hphone'),
            'phone_work' => $this->tryGetField($convertedArr, 'cnt2_wphone'),
        );
        if(!$this->writeArrayEmpty($writeArr)) {
            $writeArr['id_student_local'] = $convertedArr['student_number'];
            $writeArr['unique_id_state'] = $convertedArr['state_studentnumber'];
            $retString .= implode($writeArr, ',') . "\n";
        }
        /**
         * The cnt3 fields are "other female" like step parents, grandparents who are guardians, etc.
         */
        $writeArr = array(
            'name_first' => $this->tryGetField($convertedArr, 'cnt3_fname'),
            'name_last' => $this->tryGetField($convertedArr, 'cnt3_lname'),
            'address_street1' => $this->tryGetField($convertedArr, 'cnt3_street'),
            'address_city' => $this->tryGetField($convertedArr, 'cnt3_city'),
            'address_state' => $this->tryGetField($convertedArr, 'cnt3_state'),
            'address_zip' => $this->tryGetField($convertedArr, 'cnt3_zip'),
            'email_address' => $this->tryGetField($convertedArr, 'cnt3_email'),
            'phone_home' => $this->tryGetField($convertedArr, 'cnt3_hphone'),
            'phone_work' => $this->tryGetField($convertedArr, 'cnt3_wphone'),
        );
        if(!$this->writeArrayEmpty($writeArr)) {
            $writeArr['id_student_local'] = $convertedArr['student_number'];
            $writeArr['unique_id_state'] = $convertedArr['state_studentnumber'];
            $retString .= implode($writeArr, ',') . "\n";
        }

        /**
         * The cnt4 fields are "other male".
         */
        $writeArr = array(
            'name_first' => $this->tryGetField($convertedArr, 'cnt4_fname'),
            'name_last' => $this->tryGetField($convertedArr, 'cnt4_lname'),
            'address_street1' => $this->tryGetField($convertedArr, 'cnt4_street'),
            'address_city' => $this->tryGetField($convertedArr, 'cnt4_city'),
            'address_state' => $this->tryGetField($convertedArr, 'cnt4_state'),
            'address_zip' => $this->tryGetField($convertedArr, 'cnt4_zip'),
            'email_address' => $this->tryGetField($convertedArr, 'cnt4_email'),
            'phone_home' => $this->tryGetField($convertedArr, 'cnt4_hphone'),
            'phone_work' => $this->tryGetField($convertedArr, 'cnt4_wphone'),
        );
        if(!$this->writeArrayEmpty($writeArr)) {
            $writeArr['id_student_local'] = $convertedArr['student_number'];
            $writeArr['unique_id_state'] = $convertedArr['state_studentnumber'];
            $retString .= implode($writeArr, ',') . "\n";
        }

        /**
         * cnt5 fields are "other"s like HHS worker or residential placement contacts.
         */

            $writeArr = array(
            'name_first' => $this->tryGetField($convertedArr, 'cnt5_fname'),
            'name_last' => $this->tryGetField($convertedArr, 'cnt5_lname'),
            'address_street1' => $this->tryGetField($convertedArr, 'cnt5_street'),
            'address_city' => $this->tryGetField($convertedArr, 'cnt5_city'),
            'address_state' => $this->tryGetField($convertedArr, 'cnt5_state'),
            'address_zip' => $this->tryGetField($convertedArr, 'cnt5_zip'),
            'email_address' => $this->tryGetField($convertedArr, 'cnt5_email'),
            'phone_home' => $this->tryGetField($convertedArr, 'cnt5_hphone'),
            'phone_work' => $this->tryGetField($convertedArr, 'cnt5_wphone'),
        );
        if(!$this->writeArrayEmpty($writeArr)) {
            $writeArr['id_student_local'] = $convertedArr['student_number'];
            $writeArr['unique_id_state'] = $convertedArr['state_studentnumber'];
            $retString .= implode($writeArr, ',') . "\n";
        }

        return $retString;
    }

    function tryGetField ($convertedArr, $field) {
        if(isset($convertedArr[$field])) {
            return $convertedArr[$field];
        } else {
            return '';
        }
    }


    function writeArrayEmpty(array $data) {
        if(''==$data['name_first'] && ''==$data['name_last']) return true;
        foreach ($data as $value) {
            if(''!=$value) return false;
        }
        return true;
    }
    function homeLanguage($rowData, $colIndex) {
        /**
         * English is
         * Spanish 4260
         * Chinese 0860
         * Vietnamese 4800
         * Kiche is 9999
         */
        switch ($rowData[$colIndex]) {
            case '1290':
                return 'English';
                break;
            case '4260':
                return 'Spanish';
                break;
            case '0860':
                return 'Chinese';
                break;
            case '4800':
                return 'Vietnamese';
                break;
            case '9999':
                return 'Kiche';
                break;
            default:
                return 'unknown';
        }
    }

    function formatSchoolId($rowData, $colIndex) {
        return substr('000'.$rowData[$colIndex], -3, 3);
    }

    function ethnicGroup($rowData, $colIndex) {
        switch ($rowData[$colIndex]) {
            case 'HI':
                return 'C';
                break;
            case 'WH':
                return 'A';
                break;
            case 'BL':
                return 'B';
                break;
            default:
                return $rowData[$colIndex];
        }
    }
    function massageNebraska($rowData, $colIndex) {
        switch (trim(strtolower($rowData[$colIndex]))) {
            case 'ne':
                return 'NE';
                break;
            case 'nebraska':
                return 'NE';
                break;
            default:
                return trim($rowData[$colIndex]);
        }
    }
    function padSchoolId($rowData, $colIndex) {
        return substr('000'.$rowData[$colIndex], 1, 3);
    }
    function validateDate($rowData, $colIndex) {
        return date('m/d/Y', strtotime($rowData[$colIndex]));
    }
    function getIdStudent($rowData, $colIndex) {
        /**
         * id student local is in the 10th column
         * col index = 9
         */
        $table = new Model_Table_StudentTable();
        $select = $table->select()
            ->where('id_student_local = ?', $rowData[9])
            ->where('data_source = ?', $this->dataSource);
        $record = $table->fetchAll($select);
        if(isset($record[0])) {
            return $record[0]->id_student;
        }
        return false;
    }


}



