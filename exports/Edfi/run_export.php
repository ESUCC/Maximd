<?php
$paramArr = array_slice($argv, 1); 

/**
 * jesselocal
    /usr/local/zend/bin/php-cli /usr/local/zend/apache2/htdocs/srs-zf/exports/Fremont/run_export.php jesselocal
 *
 * Production
    /usr/local/zend/bin/php /etc/httpd/srs-zf/exports/Fremont/run_export.php production
 */

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));
echo (APPLICATION_PATH);
// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $paramArr[0]));

set_include_path(get_include_path() . PATH_SEPARATOR . APPLICATION_PATH.'/../exports');

require_once('cmd_line_helper.php');
require_once('Edfi/EdfiExport.php');
 

/**
 * general setup of the application
 */
bootCli(APPLICATION_PATH, APPLICATION_ENV);

/**
 * PROCESS THE STUDENT FILE
 */

$importFactory = new EdfiExport();