#!/usr/bin/php
<?php

/*
 * running on jesse's local machine
 * /usr/local/php5/bin/php DataImporter.php
 * be sure to use this php as is has the pgsql driver required
 * 
 */

include_once('../library/My/Classes/class_evalvalidate.php');
require_once dirname(__FILE__) . '/DataImportHelper.php';


$emailAddress = "jlavere@soliantconsulting.com";

$fieldNameArr = explode(',', "full_category,keyword");
$importFilePathArr = array(	'../data/ImportData/Sorted1.txt',
                        	'../data/ImportData/Sorted2.txt',
                        	'../data/ImportData/Sorted3.txt',
                        	'../data/ImportData/Sorted4.txt',
                        	'../data/ImportData/Sorted5.txt',
                        	'../data/ImportData/Sorted6.txt',
                        	'../data/ImportData/Sorted7.txt',
                        	'../data/ImportData/Sorted8.txt',
                        	'../data/ImportData/Sorted9.txt' 
);

$fieldNameNoCatArr = explode(',', "keyword");
$importNoCatFilePathArr = array(	'../data/ImportData/NoCate1.txt',
                        			'../data/ImportData/NoCate2.txt',
                        			'../data/ImportData/NoCate3.txt',
                        			'../data/ImportData/NoCate4.txt',
                        			'../data/ImportData/NoCate5.txt',
                        			'../data/ImportData/NoCate6.txt',
                        			'../data/ImportData/NoCate7.txt'
);




if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application/'));
}

if (!class_exists('Zend_Registry', false) || !Zend_Registry::isRegistered('config')) {

    if (!class_exists('Zend_Registry')) {
        $paths = array(
            '.', 
            APPLICATION_PATH . '/../library',
        );
        ini_set('include_path', implode(PATH_SEPARATOR, $paths));
//        require_once 'Zend/Loader.php';
//        Zend_Loader::registerAutoload();
    }
    $env = defined('APPLICATION_ENV')?APPLICATION_ENV:'local';
    $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env);
//    Zend_Debug::dump($env);
    
    Zend_Registry::set('config', $config);
    unset($base, $path, $config);
}



$config = Zend_Registry::get('config');

$db = Zend_Db::factory($config->db2);
Zend_Db_Table_Abstract::setDefaultAdapter($db);

$errorExists = array();
foreach($importFilePathArr as $importFilePath) {
    $errorExists[] = processFile($importFilePath, $fieldNameArr, $message, $db);
}

$errorExists = array();
foreach($importNoCatFilePathArr as $importNoCatFilePath) {
    $errorExists[] = processNoCatFile($importNoCatFilePath, $fieldNameNoCatArr, $message, $db);
}

Zend_Debug::dump($errorExists);


//if($errorExists) {
//
//    $message = "Errors were encountered while preflight checking was being performed for the SRS import. The errors are listed below with their line numbers: \n\n";
//    if('' != $message) $message = "Student File Errors:\n" . $message;
//    if('' != $parentMessage) $message = "Parent File Errors:\n" . $parentMessage;
//    echo "message: $message \n";
//    //$emailAddress = "jlavere@soliantconsulting.com, mburns@soliantconsulting.com";
//    $subject = "Bellevue SRS Upload Preflight";
//    if(emailError( $emailAddress, $subject, $message, $fromAddress = '')) {
//        echo "email sent\n";
//    }
//    
//} else {
//
//    $message = "The preflight ran without error.\n\n";
//    echo "message: $message \n";
//
//    $subject = "Bellevue SRS Upload Preflight";
//    if(emailError( $emailAddress, $subject, $message, $fromAddress = '')) {
//        echo "email sent\n";
//    }
//
//}





die("death");



// ===================================================================================================
// MASSAGE DATA
// ===================================================================================================
function massageData($studentData)
{
    // TRIM ALL VALUES IN STUDENT DATA ARRAY
    array_walk_recursive($studentData, 'trimAll');
    
    // CONVERT M/F VALUES TO SRS VALUES
    array_walk_recursive($studentData, 'convertGender');
    
    // CONVERT 0X VALUES TO SRS VALUES WITHOUT 0 PREFIX
    array_walk_recursive($studentData, 'convertGrade');
    
    // CONVERT LANGUAGES TO Title Case
    array_walk_recursive($studentData, 'convertLanguage');
    
    return $studentData;
}
// ===================================================================================================
// END MASSAGE DATA
// ===================================================================================================

die("death");

$studentValidationArray = array(
    //
    // required fields
        // county id
        'SRSCNY'    => array("evalPhp", 'if( 2 == strlen($arrData[\'SRSCNY\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // district id
        'SRSDST'    => array("evalPhp", 'if( 4 == strlen($arrData[\'SRSDST\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // school id
        'SRSSCH'    => array("evalPhp", 'if( 3 == strlen($arrData[\'SRSSCH\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // local student id
        'SRSST'     => array("evalPhp", 'if( 0 < strlen($arrData[\'SRSST\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // student first name
        'SRSFNM'    => array("evalPhp", 'if( 0 < strlen($arrData[\'SRSFNM\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // student last name
        'SRSLNM'    => array("evalPhp", 'if( 0 < strlen($arrData[\'SRSLNM\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
    //
    // SRS Required Fields
        // address line 1
        'SRSAD1'    => array("evalPhp", 'if( 0 < strlen($arrData[\'SRSAD1\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // address city
        'SRSCTY'    => array("evalPhp", 'if( 0 < strlen($arrData[\'SRSCTY\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // address state
        'SRSSTE'    => array("evalPhp", 'if( 0 < strlen($arrData[\'SRSSTE\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // address zip
        'SRSZIP'    => array("evalPhp", 'if( 0 < strlen($arrData[\'SRSZIP\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // student dob
        'SRSDOB'    => array("evalPhp", 'if( 0 < strlen($arrData[\'SRSDOB\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // student ethnic group
        'SRSETH'    => array("evalPhp", 'if( 0 < strlen($arrData[\'SRSETH\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // primary language
        'SRSLNG'    => array("evalPhp", 'if( 0 < strlen($arrData[\'SRSLNG\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // grade
        'SRSCLS'    => array("evalPhp", 'if( 0 < strlen($arrData[\'SRSCLS\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
);


$parentValidationArray = array(
    //
    // required fields
        // local student id
        'SRFST'     => array("evalPhp", 'if( 0 < strlen($arrData[\'SRFST\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // parent first name
        'SRFFNM'    => array("evalPhp", 'if( 0 < strlen($arrData[\'SRFFNM\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
        // parent last name
        'SRFLNM'    => array("evalPhp", 'if( 0 < strlen($arrData[\'SRFLNM\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),

);





$message = "";
$parentMessage = "";
$arrValidationResults = array();

$errorExists = errorCheck($studentData, $studentValidationArray, $arrValidationResults, $message);

$parentErrorExists = parentErrorCheck($parentData, $parentValidationArray, $arrValidationResults, $parentMessage);


if($errorExists || $parentErrorExists) {

    $message = "Errors were encountered while preflight checking was being performed for the SRS import. The errors are listed below with their line numbers: \n\n";
    if('' != $message) $message = "Student File Errors:\n" . $message;
    if('' != $parentMessage) $message = "Parent File Errors:\n" . $parentMessage;

    $emailAddress = "jlavere@soliantconsulting.com";
    $subject = "Bellevue SRS Upload Preflight";
    if(emailError( $emailAddress, $subject, $message, $fromAddress = '')) {
        echo "email sent\n";
    }
    
}



function emailError( $emailAddress, $subject, $message, $fromAddress = '', $cc = '') {
	global $EMAIL_SYS_ADMIN;
	if('' == $fromAddress) {
	    $fromAddress = $EMAIL_SYS_ADMIN;
	}
    $mail = mail($emailAddress, $subject, $message, "From: $fromAddress\nReply-To: $fromAddress\nX-Mailer: PHP/" . phpversion());
    return $mail;
}

// ===================================================================================================
// FUNCTIONS
// ===================================================================================================
function convertCSVFileToArray($fileToOpen, $keyArr) {
    $row = 1;
    $handle = fopen($fileToOpen, "r");
    $outputArray = array();
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $outputArray[] = array_combine($keyArr, $data);
    }
    fclose($handle);
    return $outputArray;
}


function processFile($fileToOpen, $keyArr, &$message, $db) {
    $x = 1;
    $sendEmail = false;
    
    if(!$handle = fopen($fileToOpen, "r")) return false;
    
    while (!feof($handle)) {
        
//        if($x > 1000) exit;
        
        // read line from file
        $buffer = fgets($handle, 4096);

        // parse the line in to categories
        list($full_category, $keyword) = explode('	', $buffer);
        
        // get full category
        $full_category = pg_escape_string(trim($full_category));
        
        /*
         * Build the tier one category
         */
        if(substr_count($full_category, '::')) {
            $full_category_array = explode('::', $full_category);
            $tier_one_category = pg_escape_string($full_category_array[0]);
        } else {
            $tier_one_category = pg_escape_string($full_category);
        }
        
        // get keyword
        $keyword = pg_escape_string(trim($keyword));
        
        
        /*
         * check the line for errors
         */
        // check to make sure there are the right number of elements
        
        
        /*
         * insert the line into postgres
         */
        $data = array(
            'tier_one_category' => $tier_one_category,
            'full_category'    => $full_category,
            'keyword'    => $keyword,
            'f'    => new Zend_Db_Expr('NOW()')
        );
        
        $db->insert('cat_key', $data);  

        $id = $db->lastInsertId();
        echo "$x $tier_one_category | $full_category | $keyword \n";  
        
        $x++;
    }
    fclose($handle);
    return !$firstError;
}

function processNoCatFile($fileToOpen, $keyArr, &$message, $db) {
    $x = 1;
    $sendEmail = false;
    
    if(!$handle = fopen($fileToOpen, "r")) return false;
    
    while (!feof($handle)) {
        
//        if($x > 1000) exit;
        
        // read line from file
        $buffer = fgets($handle, 4096);

        // parse the line in to categories
        list($keyword) = explode('	', $buffer);
        
        
        // get keyword
        $keyword = pg_escape_string(trim($keyword));
        
        
        /*
         * check the line for errors
         */
        // check to make sure there are the right number of elements
        
        
        /*
         * insert the line into postgres
         */
        $data = array(
            'keywords'    => $keyword
        );
        
        $db->insert('uncategorized_keywords', $data);  

        $id = $db->lastInsertId();
        echo "uncat $x | $keyword \n";  
        
        $x++;
    }
    fclose($handle);
    return !$firstError;
}



function convertCSVFileToFileArray($fileToOpen, $saveFile, $keyArr) {
    $row = 1;
    if(!$handle = fopen($fileToOpen, "r"))
    {
        echo "could not open array file to read from\n";
        return false;
    }
    #echo "fileToOpen: $fileToOpen\n";
    #echo "handle: $handle\n";
        
    if(!$writeHandle = fopen($saveFile, "w"))
    {
        echo "could not open array file to write to\n";
        return false;
    }
    $x = 0;
    $outputArray = array();
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        echo $x++ . $data . "\n";
        //$outputArray[] = array_combine($keyArr, $data);
        fwrite($writeHandle, array_combine($keyArr, $data));
    }
    fclose($handle);
    fclose($writeHandle);
    //return $outputArray;
}


function check_all_pass($arrValidationResults, $debug=false) {
    foreach($arrValidationResults as $key => $result) {
        //if($debug) pre_print_r($result);
        if($result['resolution'] != 'PASS') {
            if($debug) echo "resolution: {$result['resolution']}<BR>";
            if($debug) echo "key: {$key}<BR>";
            if($debug) My_Classes_iepFunctionGeneral::pre_print_r($result);
            return false;
        }
    }
    return true;
}

// ===================================================================================================
// CONVERSION FUNCTIONS - MASSAGE THE DATA
// ===================================================================================================
function trimAll(&$item, $key)
{   
    //echo "item: $item |" . trim($item)."|\n";
    $item = trim($item);
    
}
function convertGender(&$item, $key)
{
    if('SRSSEX' == $key && 'F' == $item) {
        $item = "Female";

    } elseif('SRSSEX' == $key && 'M' == $item) {
        $item = "Male";
   }
}
function convertGrade(&$item, $key)
{
    if('SRSCLS' == $key) {
        $item = ltrim($item, "0"); // trim leading zeros
    }
}
function convertLanguage(&$item, $key)
{
    if('SRSLNG' == $key) {
        $item = strtolower($item); 
        $item = ucwords($item);
        
        if("Other Lang." == $item) {
            $item = "Other";
        }
    }
}
// ===================================================================================================
// END CONVERSION FUNCTIONS - MASSAGE THE DATA
// ===================================================================================================

function errorCheck($studentData, $validationArray, $arrValidationResults, &$message) { 
    
    global $gradeArr, $ethnicGroupArr, $genderArr, $trueFalseArr, $languageArr;

    
    $errorExists = false;
    $i = 0;
    //pre_print_r($studentData);
    //$studentDataValues = $studentData;
    foreach($studentData as $studentDataKey => $studentDataValues) { 
        
        //$startTime = time();
        
        set_time_limit(300);
        //echo ". ";
        //echo "grade: " . $studentDataValues['SRSCNY'] . " (" . getCountyName($studentDataValues['SRSCNY']) . ")\n";
    
        //if($i > 100) continue;
    
        $evalObj = new evalidate();
        $evalObj->validate($studentDataValues, $validationArray, $arrValidationResults);
    
        if(!check_all_pass($arrValidationResults)) {
            $message .= "$studentDataKey validation FAILS\n";
            $errorExists = true;
        };
        
        // make sure county, district, school exist
        $countyName = getCountyName($studentDataValues['SRSCNY']);
        if(false === $countyName || '' == $countyName) {
            $message .= "$studentDataKey FAIL County Doesn't Exist\n";
            $errorExists = true;
        }

        $districtName = getDistrictName($studentDataValues['SRSCNY'], $studentDataValues['SRSDST']);
        if(false === $districtName || '' == $districtName) {
            $message .= "$studentDataKey FAIL District Doesn't Exist\n"; 
            $errorExists = true;
        }

        $schoolName = getSchoolName($studentDataValues['SRSCNY'], $studentDataValues['SRSDST'], $studentDataValues['SRSSCH']);
        if(false === $schoolName || '' == $schoolName) {
            $message .= "$studentDataKey FAIL School Doesn't Exist\n"; 
            $errorExists = true;
        }
        
        // make sure there are not too many students with the local id in the district
        $countLocalIDStudentsInDistrict === confirmLocalStudentID($studentDataValues['SRSCNY'], $studentDataValues['SRSDST'], $studentDataValues['SRSSCH'], $studentDataValues['SRSST']);
        if($countLocalIDStudentsInDistrict > 1) {
            $message .= "$studentDataKey too many students found\n"; 
            $errorExists = true;
        }
        
        // make sure grade values conform to SRS standards
        
        // grade
        if(!array_key_exists($studentDataValues['SRSCLS'], $gradeArr)) {
            $message .= "$studentDataKey grade not compatible |" . $studentDataValues['SRSCLS'] . "|\n"; 
            $errorExists = true;
        }
        // ethnic group
        if(!array_key_exists($studentDataValues['SRSETH'], $ethnicGroupArr)) {
            $message .= "$studentDataKey ethnic group not compatible |" . $studentDataValues['SRSETH'] . "|\n"; 
            $errorExists = true;
        }
        // primary language languageArr
        if(!array_key_exists($studentDataValues['SRSLNG'], $languageArr)) {
            //if(false === confirmLanguage($studentDataValues['SRSLNG'])) {
            $message .= "$studentDataKey primary language not compatible |" . $studentDataValues['SRSLNG'] . "|\n"; 
            $errorExists = true;
        }
    
        // gender
        if(!array_key_exists($studentDataValues['SRSSEX'], $genderArr)) {
            $message .= "$studentDataKey gender not compatible |" . $studentDataValues['SRSSEX'] . "|\n"; 
            $errorExists = true;
        }
    
        // public school student
        if(!array_key_exists($studentDataValues['SRSPP'], $trueFalseArr)) {
            $message .= "$studentDataKey public school student not compatible |" . $studentDataValues['SRSPP'] . "|\n"; 
            $errorExists = true;
        }
    
        //         $seconds = time() - $startTime;
        // 
        //         /****************************
        //         Make Minutes
        //         ****************************/
        //         $minutes = floor( $seconds / ( int )60 );
        //         /****************************
        //         Get Left Over Seconds
        //         ****************************/
        //         $left = $minutes * ( int )60;
        //         $a_seconds = $seconds - $left;
        //         /****************************
        //         Output Message
        //         ****************************/
        //         echo( 'run time ' . $minutes . ' minute(s) and ' . $a_seconds . ' second(s)<BR>' );


        $i++;
    }

    return $errorExists;
}
