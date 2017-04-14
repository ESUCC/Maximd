<?php
/**
 * SRS Extensions
 * 
 * @category SRS
 * @package  SRS_Validate
 * @author   Jesse LaVere
 */
 
/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';
 
/**
 * IMPORTANT: For this validator to work, allowEmpty must be set to false on 
 * the child element being validated.
 * 
 * From Zend Framework Documentation 15.3: "By default, when an 
 * element is required, a flag, 'allowEmpty', is also true. This means that if 
 * a value evaluating to empty is passed to isValid(), the validators will be 
 * skipped. You can toggle this flag using the accessor setAllowEmpty($flag); 
 * when the flag is false, then if a value is passed, the validators will still 
 * run."
 * 
 * @uses     Zend_Validate_Abstract
 * @category SRS
 * @package  SRS_Validate
 * @author   Jesse LaVere
 */
class My_Validate_IepCustomAbsent extends Zend_Validate_Abstract {
 
  /**
   * Validation failure message key for when the value of the parent field is an empty string
   */
  const KEY_NOT_FOUND  = 'keyNotFound';
 
  /**
   * Validation failure message key for when the value is an empty string
   */
  const KEY_IS_EMPTY   = 'keyIsEmpty';
 
  /**
   * Validation failure message template definitions
   *
   * @var array
   */
  protected $_messageTemplates = array(
    self::KEY_NOT_FOUND  => 'Parent field does not exist in form input',
    self::KEY_IS_EMPTY   => 'This field is required',
  );
 
  /**
   * Key to test against
   *
   * @var string|array
   */
  protected $_subformPrefix;
 
  /**
   * String to test for
   *
   * @var string
   */
  protected $_subFormFieldName;
 
  /**
   * String to test for
   *
   * @var string
   */
  protected $_valueThree;
 
  /**
   * FieldDepends constructor
   *
   * @param string $subformPrefix Name of parent field to test against
   * @param string $subFormFieldName Value of multi option that, if selected, child field required
   */
  public function __construct($subformPrefix, $subFormFieldName = null, $three = null) {
  	
    $this->setValueOne($subformPrefix);
    $this->setValueTwo($subFormFieldName);
  	$this->setValueThree($three);
  }
 
  /**
   * Defined by Zend_Validate_Interface
   *
   * Wrapper around doValid()
   *
   * @param  string $validationFieldValue
   * @param  array  $rowDataArray
   * @return boolean
   */
  public function isValid($validationFieldValue, $rowDataArray = null) {
//  	Zend_debug::dump('validationFieldValue:'.$validationFieldValue);
	// If context key is an array, doValid for each context key
    if(!$this->doValid($validationFieldValue, $rowDataArray)) {
        return false;
	}
    return true;
  }
 
  /**
   * Returns true if dependant field value is not empty when parent field value
   * indicates that the dependant field is required
   *
   * @param  string $validationFieldValue
   * @param  array  $rowDataArray
   * @return boolean
   * 
   * if the field is not validating and you think it should
   * double check that required is set to false
   * and if it's a radio or select, that setRegisterInArrayValidator to false 
   * if you want to allow empty values 
   */
  public function doValid($validationFieldValue, $rowDataArray = null) {
    
  	$subformPrefix = $this->getValueOne();
    $subFormFieldName  = $this->getValueTwo();
    $valueThree  = $this->getValueThree();
    
    $this->_setValue($validationFieldValue);


    if(isset($rowDataArray[$subformPrefix]['count'])) {
	    for($x=1; $x<= $rowDataArray[$subformPrefix]['count']; $x++)
	    {
	    	
	    	// for each sub form row
	    	$row = $rowDataArray[$subformPrefix.'_'.$x];
	    	
	    	if(isset($row[$subFormFieldName])) {
	    		$subformEleVal = $row[$subFormFieldName];
	    	} else {
	    		$subformEleVal = null;
	    	}
	    	
		    if ((null === $subformEleVal)) {
		      $this->_error(self::KEY_NOT_FOUND);
		      return false;
		    }
		    if (!is_null($subformEleVal)) {
		    	if ($subformEleVal == 1 && is_null($validationFieldValue) ) {
		    	    // no value entered - allowed if parent sig is null
		    	    // CUSTOM HACK
		    	    if(false==$rowDataArray['doc_signed_parent']) {
		    	        return true;
		    	    }
		    	    $this->_error(self::KEY_IS_EMPTY);
		        	return false;
		      	}
		    } else {
		      	if (!empty($subformEleVal) && empty($validationFieldValue)) {
		        	$this->_error(self::KEY_IS_EMPTY);
		        	return false;
		      	}
		    }
	    }
    }
    return true;
  }
 
  /**
   * @return string
   */
  protected function getValueOne() {
    return $this->_subformPrefix;
  }
 
  /**
   * @param string $subformPrefix
   */
  protected function setValueOne($subformPrefix) {
    $this->_subformPrefix = $subformPrefix;
  }
 
  /**
   * @return string
   */
  protected function getValueTwo () {
    return $this->_subFormFieldName;
  }
 
  /**
   * @param string $subFormFieldName
   */
  protected function setValueTwo ($subFormFieldName) {
    $this->_subFormFieldName = $subFormFieldName;
  }
  
  /**
   * @return string
   */
  protected function getValueThree () {
    return $this->_valueThree;
  }
 
  /**
   * @param string $valueThree
   */
  protected function setValueThree ($valueThree) {
    $this->_valueThree = $valueThree;
  }

}