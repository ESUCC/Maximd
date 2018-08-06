<?php
/**
 * Script for creating and loading test database
 */

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
        require_once 'Zend/Loader.php';
        Zend_Loader::registerAutoload();
    }
    $env = defined('APPLICATION_ENV')?APPLICATION_ENV:'devtest';
    $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env);
    Zend_Registry::set('config', $config);
    unset($base, $path, $config);
}

$config = Zend_Registry::get('config');

//if (file_exists($config->db->cxn->params->dbname)) {
//    unlink($config->db->cxn->params->dbname);
//}

$db = Zend_Db::factory($config->db2);
Zend_Db_Table_Abstract::setDefaultAdapter($db);

$statements = include $config->appPath . '/../data/pasteSchema.php';

foreach ($statements as $statement) {
    //echo "\nexecuted: $statement\n";
    $db->query($statement);
}

return true;
