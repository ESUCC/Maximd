<?php
/**
 * local
 * cd /usr/local/zend/apache2/htdocs/srs-zf/imports/Fremont/
 * /usr/local/zend/bin/php-cli /usr/local/zend/apache2/htdocs/srs-zf/imports/Fremont/run_import.php
 *
 * production
 * cd /etc/httpd/srs-zf/imports/Fremont
 * /usr/local/zend/bin/php /etc/httpd/srs-zf/imports/Fremont/run_import.php
 */


/**
 * general setup of the application
 */
require_once '../cmd_line_helper.php';

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'jesselocal'));

bootCli(APPLICATION_PATH, APPLICATION_ENV);

require_once('FremontImport.php');

/**
 * PROCESS THE STUDENT FILE
 */
$factory = new FremontImport(
    'student'
);

//Zend_Debug::dump($importFactory->log);



