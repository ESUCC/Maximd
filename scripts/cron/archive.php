<?php

echo "We got here"."\n";


function writevar1($var1,$var2) {

    ob_start();
    var_dump($var1);
    $data = ob_get_clean();
    $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
    $fp = fopen("/tmp/textfile.txt", "a");
    fwrite($fp, $data2);
    fclose($fp);
}

writevar1('we got here','we got here');

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
writevar1($args,'these are the arguments');



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

     writevar1(APPLICATION_PATH,'this is the app path');



// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $passedEnv));

writevar1(APPLICATION_ENV,'this is the app environment');

require_once 'Zend/Application.php';

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

$formsToArchiveCount = count($formsToArchive);

$counter = 1;
$formsArchived = array();
$formsNotArchived = array();
$formsIndexed = array();
$formsNotIndexed = array();

foreach ($formsToArchive as $formRec) {
	// exit if queue is deactivated
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
    echo "Archiving result: $processResult\n";

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