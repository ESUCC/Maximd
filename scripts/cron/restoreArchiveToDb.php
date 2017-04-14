<?php
/**
 * php -c /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/php.ini  /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/moveToArchiveDb.php -e jesselocal -n 004 -b 1/1/2012
 *
 * /usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini /etc/httpd/srs-zf/scripts/cron/restoreArchiveToDb.php -e iepweb03 -n 004 -i 1238052
 */
/**
 * Script for creating archives
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once 'ArchiverHelper.php';
require_once 'Archiver.php';

// get cmd line params
$args = getopt("e:n:i:");
$passedEnv = @$args["e"];
$formNumber = @$args["n"];
$formId = @$args["i"];
if(null==$passedEnv || null==$formNumber || null==$formId || strlen($formNumber)!=3) {
    echo "ERROR - Usage: archive.php -e environment(iepweb03) -n formNum(004) -i formId \n";
    echo "passedEnv: $passedEnv\n";
    echo "formNumber: $formNumber\n";
    echo "formId: $formId\n";
    print_r($argv);
    die();
}

// application path - relative to file in scripts/cron/
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application/'));
    
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $passedEnv));

require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
$application->bootstrap();

// setup registry with config
ArchiverHelper::setupRegistry();

$session = new Zend_Session_Namespace('user');
if (empty($session->locale)) {
    $session->locale = 'en';
}

// load the maing and archive configs
$config = Zend_Registry::get('config');
$archiveConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/archive.ini', APPLICATION_ENV);
echo "Configs loaded.\n";

// setup db
ArchiverHelper::seetupDb($config);

//setup zend translate
ArchiverHelper::setupTranslate($config, $formNumber, $session);

// login - this grants student access
$auth = new App_Auth_Authenticator();
$user = $auth->getCredentials($archiveConfig->siteAccess->username, $archiveConfig->siteAccess->password);
if($user) {
	App_Helper_Session::grantSiteAccess($user, false);
	echo "User found. \n";
} else {
	echo "Failed login.\n";
	die();
}
echo "User logged in.\n";

echo "form id: $formId\n";

$startTime = strtotime('now');


Archiver::restoreArchiveForm($formId, $formNumber, $config, $archiveConfig);



//// get forms to be archived
//$formsToArchive = ArchiverHelper::formsToBeMovedToArchivDb('iep_form_' . $formNumber, 'id_form_' . $formNumber);
//
//$formsToArchiveCount = count($formsToArchive);
//Zend_Debug::dump('Archiving forms ' . $formsToArchiveCount);
//
//$counter = 1;
//$formsArchived = array();
//$formsNotArchived = array();
//$formsIndexed = array();
//$formsNotIndexed = array();
//
//foreach ($formsToArchive as $formRec) {
//	// exit if queue is deactivated
//	$queue = ArchiverHelper::getQueueStatus();
//	if('on'!=$queue['status']) {
//		Zend_Debug::dump("Queue is off.");
//		die();
//	}
//    echo "Processing $counter of $formsToArchiveCount\n";
//    echo "Student {$formRec['id_student']}\n\n";
//
//    // archive or update archived student
//	Archiver::archiveStudent($formRec, $formNumber, $config, $archiveConfig);
//    //Store (in the filesystem) and index (in Solr) each form
//	Archiver::archiveForm($formRec, $formNumber, $config, $archiveConfig);
//    $counter++;
//}
//foreach($formsArchived as $formArchived) echo $formArchived;
//foreach($formsIndexed as $formIndexed) echo $formIndexed;
//foreach($formsNotArchived as $formNotArchived) echo $formNotArchived;
//foreach($formsNotIndexed as $formNotIndexed) echo $formNotIndexed;

echo "*** Restore finished ***\n";

echo round((strtotime('now') - $startTime) / 60,2). " minute(s)\n";

return true;

