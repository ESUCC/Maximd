<?php
/**
 * /usr/local/zend/bin/php -c /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/php.ini  /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/movePPNFilesToFolder.php -e jesselocal -n 004 -b 1/1/2012
 *
 * php -c /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/php.ini  /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/movePPNFilesToFolder.php -e production -n 004 -b 1/1/2015
 *
 * IEPWEB03
 * /usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/movePPNFilesToFolder.php -e iepweb03 -n 004 -b 1/1/2015 ; /etc/httpd/srs-zf/scripts/cron/PPN/putpdfs.exp
 *
 */
/**
 * Script for creating archives
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once 'ArchiverHelper.php';
require_once 'Archiver.php';
require_once 'PpnHelper.php';
require_once 'showStatus.php';

// get cmd line params
$args = getopt("e:n:b:d:");
$passedEnv = @$args["e"];
if (null == $passedEnv) {
    echo "ERROR - Usage: archive.php -e environment(iepweb03) -b beginDate(2009-01-01) \n";
    echo "passedEnv: $passedEnv\n";
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
$localConfig = new Zend_Config_Ini(APPLICATION_PATH . '/../scripts/cron/PPN/config.ini', APPLICATION_ENV);
echo "Configs loaded.\n";

// setup db
ArchiverHelper::seetupDb($config);

// login - this grants student access
$auth = new App_Auth_Authenticator();
$user = $auth->getCredentials($localConfig->siteAccess->username, $localConfig->siteAccess->password);
if ($user && App_Helper_Session::grantArchiverSiteAccess($user, false)) {
    echo "User found and both logins successful. \n";
//    App_Helper_Session::grantSiteAccess($user, false);
    echo "User found. \n";
} else {
    echo "Failed login.\n";
    die();
}
echo "User logged in.\n";


$startTime = strtotime('now');

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
ArchiverHelper::setupTranslate($config, '004', $session);

// get forms to be archived
$students = PpnHelper::getStudentsForArchiving();
foreach ($students as $student) {
    ShowStatus::show_status($counter, count($students));
    $form = PpnHelper::mostRecentFinalForm($student['id_student'], '004');
    if(!is_null($form)) {
        if(isset($student['id_student_local'])) {
            $fileName = $student['id_student_local'] . '_' . date('Ymd', strtotime($form['date_conference']));
        } else {
            $fileName = 'srs_' . $student['id_student'] . '_' . date('Ymd', strtotime($form['date_conference']));
        }

        $sessUser = new Zend_Session_Namespace('user');
        $archiveData = PpnHelper::archiveFormToPdf(
            'Model_Form004',
            '004',
            $sessUser,
            $form['id_form_004'],
            '',
            APPLICATION_PATH . '/../' . $localConfig->pdf->folder,
            $fileName,
            false
        );
    }
    $counter++;
}


foreach ($formsArchived as $formArchived) {
    echo $formArchived;
}
foreach ($formsIndexed as $formIndexed) {
    echo $formIndexed;
}
foreach ($formsNotArchived as $formNotArchived) {
    echo $formNotArchived;
}
foreach ($formsNotIndexed as $formNotIndexed) {
    echo $formNotIndexed;
}
echo "*** All archive jobs finished ***\n";
echo round((strtotime('now') - $startTime) / 60, 2) . " minute(s)\n";
return true;


?>
