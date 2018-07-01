<?php

require_once 'Zend/Validate/Abstract.php';  

class My_Validate_NotEmptyTrueFalse extends Zend_Validate_Abstract
{
	
    const NOT_MATCH = 'notMatch'; 
    protected $_messageTemplates = array(self::NOT_MATCH => 'Value must be Yes or No'); 

    public function isValid($value, $context = null)
    {
//		$value = $value;
		$this->_setValue($value);
    	if(is_null($value)) // null
		{	
			$this->_error(self::NOT_MATCH);
			return false;
		}
		elseif('t' == $value || 'f' == $value) // valid
		{
			return true;
		}
     
        $this->_error(self::NOT_MATCH);
        return false;
    }

}
