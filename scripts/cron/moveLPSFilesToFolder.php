<?php
/**
 * php -c /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/php.ini  /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/moveLPSFilesToFolder.php -e jesselocal -n 004 -b 1/1/2012
 *
 * php -c /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/php.ini  /usr/local/zend/apache2/htdocs/srs-zf/scripts/cron/moveLPSFilesToFolder.php -e production -n 004 -b 1/1/2015
 *
 * /usr/local/zend/bin/php -c /etc/httpd/srs-zf/scripts/cron/php.ini  /etc/httpd/srs-zf/scripts/cron/moveLPSFilesToFolder.php -e iepweb03 -n 004 -b 1/1/2015
 *
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
$beforeDate = @$args["b"];
$delete = @$args["d"];

if (null == $passedEnv || null == $beforeDate) {
    echo "ERROR - Usage: archive.php -e environment(iepweb03) -b beginDate(2009-01-01) \n";
    echo "passedEnv: $passedEnv\n";
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
//ArchiverHelper::setupTranslate($config, $formNumber, $session);

// login - this grants student access
$auth = new App_Auth_Authenticator();
$user = $auth->getCredentials($archiveConfig->siteAccess->username, $archiveConfig->siteAccess->password);
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
for ($i = 1; $i <= 30; $i++) {
    $formNumber = substr('000' . $i, -3, 3);
    ArchiverHelper::setupTranslate($config, $formNumber, $session);

    // get forms to be archived
    $formsToArchive = LpsHelper::formsToBeArchived(
        'iep_form_' . $formNumber,
        'id_form_' . $formNumber,
        'date_conference',
        $beforeDate,
        $formNumber
    );

    $formsToArchiveCount = count($formsToArchive);
    Zend_Debug::dump($formNumber . ': Archiving forms ' . $formsToArchiveCount);

    foreach ($formsToArchive as $formRec) {
        echo "Processing $counter of $formsToArchiveCount\n";
        $sessUser = new Zend_Session_Namespace('user');
        $modelName = 'Model_Form' . $formNumber;
        $archiveData = LpsHelper::archiveFormToPdf(
            $modelName,
            $formNumber,
            $sessUser,
            $formRec['id'],
            '',
            '/pdf-archive/Form' . $formNumber,
            $formRec['file_name']
        );
        /*
         * Store (in the filesystem) and index (in Solr) each form
         */
//        $processResult = LpsHelper::storePDFOnly(
//            $formRec,
//            $formNumber,
//            $counter,
//            $config,
//            $archiveConfig,
//            $formsNotArchived,
//            $formsIndexed,
//            $formsNotIndexed,
//            $formsArchived,
//            false
//        );
        $counter++;
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

class LpsHelper
{
    static public function archiveFormToPdf(
        $modelName,
        $formNumber,
        $usersession,
        $document,
        $legacySiteSessionId = '',
        $path = null,
        $shortName = null
    ) {

        if (!$document) {
            return false;
        }

        try {
            $config = Zend_Registry::get('config');
            if (is_null($shortName)) {
                $shortName = 'form-' . $formNumber . "-" . $document . "-archived";
            }
            $sessUser = new Zend_Session_Namespace('user');

            // just to get version number
            $modelform = new $modelName ($formNumber, $usersession);
            $dbData = $modelform->find($document, 'print', 'all', null, true);

            if (is_null($path)) {
                $path = realpath($config->archivePath) . '/' . $dbData['id_student']
                    . '/' . $dbData['id_county'] . '_' . $dbData['id_district']
                    . '_' . $dbData['id_school'];

            }
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $tmpPDFpath = $path . '/' . $shortName . ".pdf";

            if (App_Application::isCli()) {
                $sid = 'Archive0123456789012345678901234567890Archive';
            }
            // new site
            if ($dbData['version_number'] >= 9) {
                $url = $config->DOC_ROOT . 'form' . $formNumber . '/print/document/' . $document . '/page';
                if (!isset($sid) && isset($_COOKIE['PHPSESSID'])) {
                    $sid = $_COOKIE['PHPSESSID'];
                }
                $client = $sessUser->newSiteClient;
            } else {
                // old site
                $url = 'https://iep.nebraskacloud.org/form_print.php?form=form_' . $formNumber . '&document=' . $document . '&archive=true';
                if (!isset($sid)) {
                    $sid = trim($legacySiteSessionId);
                }
                $client = $sessUser->oldSiteClient;
            }

            // prepare client and get pdf from print action (zf or old site)
            $client->setUri($url);
            if ($dbData['version_number'] >= 9) {
                $cookie = new Zend_Http_Cookie('PHPSESSID-ARCHIVE', $sid, $config->DOC_ROOT);
            } else {
                $cookie = new Zend_Http_Cookie('PHPSESSID-ARCHIVE', $sid, 'iep.nebraskacloud.org');
            }

            $client->setCookie($cookie);
            $body = $client->request()->getBody();

//        echo "==================================================================================================\n";
//        echo "Store path: $tmpPDFpath\n";
//            echo "url: $url\n";
//            echo "sid: $sid\n";
            try {
                $pdf = Zend_Pdf::parse($body);
                $pdf->save($tmpPDFpath);

            } catch (Exception $e) {
                //Zend_Debug::dump($body);
                return false;
            }

            return file_exists($tmpPDFpath);

        } catch (Exception $e) {
//            throw new Exception ('Error trying to archive a form to pdf.' . $e);
            echo "Error trying to archive a form to pdf.\n\n";
            return false;
        }
    }

    public static function formsToBeArchived(
        $tableName,
        $keyName,
        $dateField = 'date_notice',
        $beforeDate = null,
        $formNum
    ) {
        $columnExistsQuery = "SELECT attname FROM pg_attribute WHERE attrelid =
        (SELECT oid FROM pg_class WHERE relname = '$tableName') AND attname = '$dateField';";
        $exists = Zend_Registry::get('db')->query($columnExistsQuery)->fetch();

        if(false == $exists) {
            $dateField = 'date_notice';
        }

        $stmt = "SELECT ";
        $stmt .= "f.version_number, f.pdf_archived, f.$keyName as id, f.$keyName, f.id_student, ";
        $stmt .= "f.$dateField as date, ";
        $stmt .= "s.id_student_local || '_' || to_char(f.$dateField, 'MMDDYYYY') || '_-_' || formname('$formNum') as file_name ";
        $stmt .= "FROM $tableName f left join iep_student s on f.id_student = s.id_student ";
        $stmt .= "where s.status='Active' ";
        $stmt .= "and f.status = 'Final' ";
        $stmt .= "and s.id_county = '55' ";
        $stmt .= "and s.id_district = '0001' ";
        if (null != $beforeDate) {
//            $stmt .= "and $dateField > date 'today' - interval '3 years' ";
            $stmt .= "and $dateField > '6/1/2011'  ";
        }
        $stmt .= "and f.id_county = '55' and f.id_district = '0001' ";
        $stmt .= "order by $dateField desc;";


        echo "$stmt\n";
//        $countQuery = Zend_Registry::get('db')->query($count);
//        $countRow = $countQuery->fetch();
//        echo "COUNT: " . $countRow['count'] . "\n";
        $result = Zend_Registry::get('db')->query($stmt);
        return $result->fetchAll();
    }

}