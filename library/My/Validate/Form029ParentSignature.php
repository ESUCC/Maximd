<?php

require_once 'Zend/Validate/Abstract.php';  

class My_Validate_Form029ParentSignature extends Zend_Validate_Abstract
{
	
    const NOT_BLANK = 'notEmpty'; 
    protected $_messageTemplates = array(
    	self::NOT_BLANK => 'Form cannot be finalized when Signature on File is Blank.',
    ); 

    public function isValid($value, $context = null)
    {
    	$value = (string) $value;
    	$this->_setValue($value);
    	
    	//if ($value == 'Blank') {
    	//    $this->_error(self::NOT_BLANK);
    	//    return false;
    	//}
		return true;
    }

}
