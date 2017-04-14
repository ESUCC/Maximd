<?php

/**
 * StudentTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_JsErrLog extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'js_err_log';
	protected $_primary = 'js_err_log_id';
	
	public  static function log($data) {
		
		$acceptableValues = array(
			"info",
			"i",
			"sn",
			"fl",
			"ln",
			"err",
			"ui", 
			"userid",
			"user_name", 
			"form_number", 
			"form_key"
		);
		array_push($acceptableValues, 'parent_url');
		array_push($acceptableValues, 'page_number');
		array_push($acceptableValues, 'student_id');
		array_push($acceptableValues, 'computer_platform');
		array_push($acceptableValues, 'browser_type');
		array_push($acceptableValues, 'browser_version');
		
		// limit to desired fields
		foreach (array_keys($data) as $key) {
			if(false===array_search($key, $acceptableValues) || !isset($data[$key]) || ''==$data[$key]) {
				unset($data[$key]);
			}
		}
		
		$table = new Model_Table_JsErrLog();
        return $table->insert($data);
	}

	
}



