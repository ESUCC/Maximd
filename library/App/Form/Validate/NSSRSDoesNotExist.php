<?php

class App_Form_Validate_NSSRSDoesNotExist extends Zend_Validate_Abstract {
	
	private $dbToUse = null;
	
	/* (non-PHPdoc)
	 * @see Zend_Validate_Interface::isValid()
	 */
	public function isValid($value, $context = null) {
		$oldDb = null;
		if($this->dbToUse !== null) {
			$oldDb = Zend_Db_Table_Abstract::getDefaultAdapter();
			Zend_Db_Table_Abstract::setDefaultAdapter($this->dbToUse);
		}
		$table = new Model_Table_Student();
		$valid =  !$table->srsNSSRSIdExists($value);
		if($oldDb !== null) {
			Zend_Db_Table_Abstract::setDefaultAdapter($oldDb);
		}
		return $valid;
	}

	public function setDb($db) {
		$this->dbToUse = $db;
	}
	
}