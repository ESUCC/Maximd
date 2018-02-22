<?php




function writevar1($var1,$var2) {

    ob_start();
    var_dump($var1);
    $data = ob_get_clean();
    $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
    $fp = fopen("/tmp/textfile.txt", "a");
    fwrite($fp, $data2);
    fclose($fp);
}



/**
 * Script for creating archives
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once 'ArchiverHelper.php';
require_once 'Archiver.php';

// get cmd line params
$args = getopt("e:n:b:d:");
$passedEnv = @$args["e"];
$formNumber = @$args["n"];
$beforeDate = @$args["b"];
$delete = @$args["d"];




if(null==$passedEnv || null==$formNumber || null==$beforeDate || strlen($formNumber)!=3) {
    echo "ERROR - Usage: archive.php -e environment(iepweb02) -n formNum(004) -b beginDate(2009-01-01) \n";
    echo "passedEnv: $passedEnv\n";
    echo "formNumber: $formNumber\n";
    echo "beforeDate: $beforeDate\n";
    print_r($argv);

    die();
}

// application path - relative to file in scripts/cron/
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application/'));

 //    writevar1(APPLICATION_PATH,'this is the app path');
//     writevar1(realpath(realpath(dirname(__FILE__) . '/../../application/')),'this is real file path');


// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $passedEnv));

//writevar1(APPLICATION_ENV,'this is the app environment');

//writevar1('we got to zend application.php','line 60');


error_reporting(E_ALL|E_STRICT);
ini_set('display_errors',1);
require_once 'Zend/Application.php';
//writevar1('we got to zend application.php','line 62');



// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');


$application->bootstrap();

// setup registry with config
ArchiverHelper::setupRegistry();




$session = new Zend_Session_Namespace('user');
if (empty($session->locale))
    $session->locale = 'en';

echo "Config loaded.\n";



$config = Zend_Registry::get('config');

// setup db
ArchiverHelper::seetupDb($config);

//setup zend translate
ArchiverHelper::setupTranslate($config, $formNumber, $session);

// load the maing and archive configs
$config = Zend_Registry::get('config');
$archiveConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/archive.ini', APPLICATION_ENV);
echo "Configs loaded.\n";

// login - this grants student access
$auth = new App_Auth_Authenticator();
$user = $auth->getCredentials($archiveConfig->siteAccess->username, $archiveConfig->siteAccess->password);
if($user && App_Helper_Session::grantArchiverSiteAccess($user, false)) {
	echo "User found and both logins successful. \n";
} else {
	echo "Failed login.\n";
	die();
}
echo "User logged in.\n";

$startTime = strtotime('now');

// get forms to be archived
$formsToArchive = ArchiverHelper::formsToBeArchived('iep_form_' . $formNumber, 'id_form_' . $formNumber,
                'date_conference', $beforeDate);
//writevar1($formsToArchive,'these are the forms to archive');

/* forms to archive looks like this .  All 200,000 plus
 * [234486]=>
  array(6) {
    ["version_number"]=>
    int(11)
    ["pdf_archived"]=>
    bool(false):
    ["id"]=>
    int(1626279)
    ["id_form_004"]=>
    int(1626279)
    ["id_student"]=>
    int(1219515)
    ["date"]=>
    string(10) "0201-04-06"

 */


$formsToArchiveCount = count($formsToArchive);

$counter = 1;
$formsArchived = array();
$formsNotArchived = array();
$formsIndexed = array();
$formsNotIndexed = array();

foreach ($formsToArchive as $formRec) {

	// exit if queue is deactivated
  writevar1($formRec,'this is a form');

    $queue = ArchiverHelper::getQueueStatus();
	if('on'!=$queue['status']) {
		Zend_Debug::dump("Queue is off.");
		die();
	}
    echo "Processing $counter of $formsToArchiveCount\n";

	/*
	 * Store (in the filesystem) and index (in Solr) each form
	 */
    $processResult = Archiver::processForm(//$oldSiteClient, $newSiteClient,
        $formRec,
        $formNumber,
        $counter,
        $config,
        $archiveConfig,
        $formsNotArchived,
        $formsIndexed,
        $formsNotIndexed,
        $formsArchived
    );
   // echo "Archiving result: $processResult\n";

    /*
     * delete from main db
     */
    if($processResult && $delete) {
        $deleteResult = Archiver::deleteForm($formRec, $formNumber, $config, $archiveConfig);
        echo "Delete result: $deleteResult\n";
    }
    echo "\n";
    $counter++;
}

foreach($formsArchived as $formArchived) echo $formArchived;
foreach($formsIndexed as $formIndexed) echo $formIndexed;
foreach($formsNotArchived as $formNotArchived) echo $formNotArchived;
foreach($formsNotIndexed as $formNotIndexed) echo $formNotIndexed;
echo "***All archive jobs finished***\n";

echo round((strtotime('now') - $startTime) / 60,2). " minute(s)\n";

return true;