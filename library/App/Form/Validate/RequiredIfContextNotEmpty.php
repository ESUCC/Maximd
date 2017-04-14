<?php

class App_Form_Validate_RequiredIfContextNotEmpty extends Zend_Validate_Abstract {
	
	protected $contextKey;
	
	const VAL_REQUIRED = 'VAL_REQUIRED';
	
	protected $_messageTemplates = array(
			self::VAL_REQUIRED => "This is required",
	);
	
	public function __construct($contextKey) {
		$this->contextKey = $contextKey;
	}
	
	public function isValid($value, $context = null) {
		//Zend_Debug::Dump($context);die;
		if(isset($context[$this->contextKey]) && $context[$this->contextKey] != '') {
			if($value == '') {
				$this->_error(self::VAL_REQUIRED);
				return false;
			}
		}
		return true;
	}

	
}