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
 * Requires field presence based on provided value of radio element.  
 * 
 * Example would be radio element with Yes, No, Other option, followed by an "If 
 * other, please explain" text area.
 * 
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
class My_Validate_NotEmptyIfUnless extends Zend_Validate_Abstract {
 
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
  protected $_valueOne;
 
  /**
   * String to test for
   *
   * @var string
   */
  protected $_valueTwo;
 
  /**
   * String to test for
   *
   * @var string
   */
  protected $_valueThree;
 
  /**
   * FieldDepends constructor
   *
   * @param string $valueOne Name of parent field to test against
   * @param string $valueTwo Value of multi option that, if selected, child field required
   */
  public function __construct($valueOne, $valueTwo = null, $three = null) {
  	
    $this->setValueThree($three);
    $this->setValueTwo($valueTwo);
    $this->setValueOne($valueOne);
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
 
  	$valueOne = $this->getValueOne();
  	
    // If context key is an array, doValid for each context key
    if (is_array($valueOne)) {
      foreach ($valueOne as $ck) {
        $this->setValueOne($ck);
        if(!$this->doValid($validationFieldValue, $rowDataArray)) {
          return false;
        }
      }
    } else {
      if(!$this->doValid($validationFieldValue, $rowDataArray)) {
        return false;
      }
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
    
  	$valueOne = $this->getValueOne();
    $valueTwo  = $this->getValueTwo();
    $valueThree  = $this->getValueThree();
    
    $validationFieldValue      = (string) $validationFieldValue;			// value of the field being validated
    $this->_setValue($validationFieldValue);

//    Zend_debug::dump($valueOne);
//    Zend_debug::dump($valueTwo);
//    Zend_debug::dump($valueThree);
//    
//    Zend_debug::dump($validationFieldValue);
//    Zend_debug::dump($rowDataArray);
    
    // if unless conditions are true, field is valid
    foreach($valueTwo['unless'] as $fieldName => $matchValue) {
//    	Zend_debug::dump("fieldname: $fieldName");
//    	Zend_debug::dump("matchValue: $matchValue");
		if($rowDataArray[$fieldName] == $matchValue) return true;
    }
    
    // make sure row data exists and key for "if" match field exists
    if ((null === $rowDataArray) || !is_array($rowDataArray) || !array_key_exists($valueOne, $rowDataArray)) {
      $this->_error(self::KEY_NOT_FOUND);
      return false;
    }
 
    $valueOneValue = $rowDataArray[$valueOne];
    
//    Zend_debug::dump("$valueOne value is " .$valueOneValue);
//    Zend_debug::dump("equals: " .$valueTwo['equals']);
    
    // not empty if field value matches match value
    if(
    	$valueOneValue == $valueTwo['equals'] && 	// is the IF confdition met 
    	empty($validationFieldValue) 			// is the field value empty
   	) 
    {
    	$this->_error(self::KEY_IS_EMPTY);
    	return false;
    }
//    if (!is_null($valueOneValue)) {
//    	if ($valueTwo <= ($valueOneValue) && empty($validationFieldValue)) {
//        	$this->_error(self::KEY_IS_EMPTY);
//        	return false;
//      	}
//    } else {
//      	if (!empty($valueOneValue) && empty($validationFieldValue)) {
//        	$this->_error(self::KEY_IS_EMPTY);
//        	return false;
//      	}
//    }
    return true;
  }
 
  /**
   * @return string
   */
  protected function getValueOne() {
    return $this->_valueOne;
  }
 
  /**
   * @param string $valueOne
   */
  protected function setValueOne($valueOne) {
    $this->_valueOne = $valueOne;
  }
 
  /**
   * @return string
   */
  protected function getValueTwo () {
    return $this->_valueTwo;
  }
 
  /**
   * @param string $valueTwo
   */
  protected function setValueTwo ($valueTwo) {
    $this->_valueTwo = $valueTwo;
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