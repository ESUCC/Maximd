<?php

class App_Vlog
{
	
	public static function file()
	{
		foreach (func_get_args() as $msg) {
			try {
				$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH.'/../logs/App_Vlog.txt');
				$logger = new Zend_Log($writer);
				if(is_array($msg)) {
					$logger->log(print_r($msg, 1), 1);
				} else {
					$logger->log($msg, 1);
				}
			} catch (Exception $e) {
				// whoops
			
			}
		}
	}
}