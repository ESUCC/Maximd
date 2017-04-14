<?php
/**
 * php -c /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/php.ini  /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/moveToArchiveDb.php -e jesselocal -n 004 -b 1/1/2012
 *
 * php -c /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/php.ini  /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/moveToArchiveDb.php -e production -n 004 -b 1/1/2012
 */
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
    echo "ERROR - Usage: archive.php -e environment(iepweb03) -n formNum(004) -b beginDate(2009-01-01) \n";
    echo "passedEnv: $passedEnv\n";
    echo "formNumber: $formNumber\n";
    echo "beforeDate: $beforeDate\n";
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


$startTime = strtotime('now');

// get forms to be archived
$formsToArchive = ArchiverHelper::formsToBeMovedToArchivDb('iep_form_' . $formNumber, 'id_form_' . $formNumber);

$formsToArchiveCount = count($formsToArchive);
Zend_Debug::dump('Archiving forms ' . $formsToArchiveCount);

$counter = 1;
$formsArchived = array();
$formsNotArchived = array();
$formsIndexed = array();
$formsNotIndexed = array();

/**
 * move to archive
 *
 * - delete main form in archive db if it exists
 * - delete sub forms in archive db if it exists
 */
foreach ($formsToArchive as $formRec) {
	// exit if queue is deactivated
	$queue = ArchiverHelper::getQueueStatus();
	if('on'!=$queue['status']) {
		Zend_Debug::dump("Queue is off.");
		die();
	}
    echo "Processing $counter of $formsToArchiveCount\n";
    echo "Student {$formRec['id_student']}\n\n";

    // archive or update archived student
	Archiver::archiveStudent($formRec, $formNumber, $config, $archiveConfig);

    $archiveResult = Archiver::archiveFormToDb($formRec, $formNumber, $config, $archiveConfig);

    if($archiveResult && $delete) {
        Archiver::deleteForm($formRec, $formNumber, $config, $archiveConfig);
    }
    $counter++;
}
foreach($formsArchived as $formArchived) echo $formArchived;
foreach($formsIndexed as $formIndexed) echo $formIndexed;
foreach($formsNotArchived as $formNotArchived) echo $formNotArchived;
foreach($formsNotIndexed as $formNotIndexed) echo $formNotIndexed;
echo "*** All archive jobs finished ***\n";

echo round((strtotime('now') - $startTime) / 60,2). " minute(s)\n";

return true;

