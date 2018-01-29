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
require_once 'PpnHelperGRT.php';
require_once 'showStatus.php';


// get cmd line params
$args = getopt("e:n:b:d:");
$passedEnv = @$args["e"];
if (null == $passedEnv) {
    /*
     * Mike changed this 12-14-2017 -e environment(iepwe03) to the following.  SRS-148
     */
    echo "ERROR - Usage: archive.php -e environment(iepweb02) -b beginDate(2009-01-01) \n";
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
$localConfig = new Zend_Config_Ini(APPLICATION_PATH . '/../scripts/cron/GRT/config.ini', APPLICATION_ENV);
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
PpnHelperGRT::setMissingIdStudentLocals($localConfig->idCounty,$localConfig->idDistrict);
$students = PpnHelperGRT::getStudentsForArchiving();

// Mike PpnHelperGRT::writevar1($students,'these are the students');

$filenames = array();
$iterator = new DirectoryIterator(APPLICATION_PATH . '/../' . $localConfig->pdf->folder);
foreach ($iterator as $fileinfo) {
    if ($fileinfo->isFile()) {
        $filenames[$fileinfo->getFilename()] = $fileinfo->getFilename();
    }
}
foreach ($students as $student) {
// Mike    PpnHelperGRT::writevar1($student,'here is one student' );

    ShowStatus::show_status($counter, count($students));
    $form = PpnHelperGRT::mostRecentFinalForm($student['id_student'], '004');

// Mike   PpnHelper::writevar1($form,'this is the form');

    if(!is_null($form)) {
        if(isset($student['id_student_local'])) {
            $fileName = $student['id_student_local'] . '_' . date('Ymd', strtotime($form['date_conference']));
            unset($filenames[$student['id_student_local'] . '_' . date('Ymd', strtotime($form['date_conference'])) . '.pdf']);


            $sessUser = new Zend_Session_Namespace('user');
   //         PpnHelper::writevar1($sessUser,'this isthe sessis user');
   //         PpnHelper::writevar1($form['id_form_004'],'this is the form id');
   //         PpnHelper::writevar1($localConfig->pdf->folder,'localconfig');

            $archiveData = PpnHelperGRT::archiveFormToPdf(
                'Model_Form004',
                '004',
                $sessUser,
                $form['id_form_004'],
                '',
                APPLICATION_PATH . '/../' . $localConfig->pdf->folder,
                $fileName,
                false
            );
     //       PpnHelper::writevar1($archiveData,'this is hte data archive' );

       }
    }
    $counter++;
}

// Remove old Summary Forms
if (!empty($filenames)) {
    foreach ($filenames AS $filename) {
        echo "removing old file " . APPLICATION_PATH . '/../' . $localConfig->pdf->folder . '/' . $filename . " \r\n";
        unlink(APPLICATION_PATH . '/../' . $localConfig->pdf->folder . '/' . $filename);
    }
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
