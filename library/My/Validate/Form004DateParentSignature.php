<?php

require_once 'Zend/Validate/Abstract.php';  

class My_Validate_Form004DateParentSignature extends Zend_Validate_Abstract
{
	
    const NOT_EMPTY = 'notEmpty'; 
    const NOT_FILLED = 'notMatch'; 
    protected $_messageTemplates = array(
    	self::NOT_EMPTY => 'Value should be empty when Parent Signature is false.',
    	self::NOT_FILLED => 'Value cannot be empty when Parent Signature is true.'
    ); 

    public function isValid($value, $context = null)
    {
    	$formData = $context; // get the data passed to the function
//    	Zend_debug::dump($formData);
    	$value = (string) $value;
    	$this->_setValue($value);
		
		$parentSigOnFile = $formData['doc_signed_parent'];
//    	Zend_debug::dump($parentSigOnFile);
		
    	if(is_null($parentSigOnFile)) // do nothing because parent sig is null
		{	
			return true;
		}
		elseif(false === $parentSigOnFile && !is_null($value)) // invalid
		{
			$this->_error(self::NOT_EMPTY);
			Zend_debug::dump($value, 'false');
			return false;
		}
		elseif(true === $parentSigOnFile && is_null($value)) // invalid
		{
			$this->_error(self::NOT_FILLED);
			Zend_debug::dump($value, 'true');
			return false;
		}
    }

}
