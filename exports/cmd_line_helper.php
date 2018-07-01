<?php

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

function bootCli($applicationPath, $applicationEnv) {
    /**
     * Allow massive execution time on large live server
     */
    ini_set("max_execution_time", 79200); // 24 hours
    ini_set("memory_limit", "128M"); // got RAM to burn on the main machine so ...
    error_reporting(E_ALL | E_NOTICE | E_PARSE );

    /** Zend_Application */
   // require_once 'Zend/Application.php';

    // Create application
   // $application = new Zend_Application($applicationEnv, $applicationPath . '/configs/application.ini');

    // add custom paths and helpers
    initAutoload();

}
