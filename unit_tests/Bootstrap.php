<?php
/**
 * determine application environment
 * -------------------------------------------------------------
 */
$htAccessFile = findHtAccessFile(dirname(__FILE__));
if(null!=$htAccessFile) {
    $subject = file_get_contents($htAccessFile);
    $pattern = '/SetEnv APPLICATION_ENV (.+)/';
    preg_match($pattern, $subject, $matches);
    if(isset($matches[1])) {
        $argv = array('', $matches[1]);;
    }
}
/**
 * if no htaccess, set default for passed parameter
 */
if(!isset($argv)) {
    $argv = array('', 'jesselocal');
}
$paramArr = array_slice($argv, 1);

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $paramArr[0]));

bootCli(APPLICATION_PATH, APPLICATION_ENV);

/**
 * required functions
 */

function bootCli($applicationPath, $applicationEnv='production') {
    /**
     * Allow massive execution time on large live server
     */
//    ini_set("max_execution_time", 79200); // 24 hours
//    ini_set("memory_limit", "128M"); // got RAM to burn on the main machine so ...
//    error_reporting(E_ALL | E_NOTICE | E_PARSE );

    /** Zend_Application */
    require_once 'Zend/Application.php';

    // Create application
    $application = new Zend_Application($applicationEnv, $applicationPath . '/configs/application.ini');
    $application->bootstrap();
//    $config = new Zend_Config_Ini($applicationPath . '/configs/application.ini', $applicationEnv);
//
//    Zend_Registry::set('config', $config);
//    $db = Zend_Db::factory($config->db2);    // returns instance of Zend_Db_Adapter class
//
//    Zend_Registry::set('db', $db);
//    Zend_Db_Table_Abstract::setDefaultAdapter($db);
//
//    // add custom paths and helpers
//    initAutoload();
    return $application;
}
function initAutoload()
{
    $autoloader = Zend_Loader_Autoloader::getInstance();
    $autoloader->registerNamespace(array('App_'));
    $autoloader->registerNamespace(array('My_'));
    $autoloader->registerNamespace('DbTable_');

    $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH)
    );

    return $moduleLoader;
}
function findHtAccessFile($dir, $counter = 1)
{
    /**
     * look for .htaccess file in downward path
     */
    if (file_exists($dir . '/public/.htaccess')) {
        return $dir. '/public/.htaccess';
    } else {
        /**
         * prevent endless loop
         */
        if($counter > 10) {
            return null;
        }
        /**
         * search parent dir for .htaccess
         */
        return findHtAccessFile(dirname($dir), $counter++);
    }
}
