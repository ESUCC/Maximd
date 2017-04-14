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
class My_Validate_AtLeastOneIf extends Zend_Validate_Abstract {
 
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
  protected $_parentFieldName;
 
  /**
   * String to test for
   *
   * @var string
   */
  protected $_matchValue;
 
  /**
   * String to test for
   *
   * @var string
   */
  protected $_allowedFieldValues;
 
  /**
   * FieldDepends constructor
   *
   * @param string $parentFieldName Name of parent field to test against
   * @param string $matchValue Value of multi option that, if selected, child field required
   */
  public function __construct($parentFieldName, $matchValue = null, $allowedFieldValues = null) {

    $this->setValueOne($parentFieldName);
    $this->setValueTwo($matchValue);
    $this->setValueThree($allowedFieldValues);
  }
  /**
   * Defined by Zend_Validate_Interface
   *
   * @param  string $validationFieldValue
   * @param  array  $rowDataArray
   * @return boolean
   */
  public function isValid($validationFieldValue, $rowDataArray = null) {
    
    $parentFieldName = $this->getValueOne();
    $matchValue  = $this->getValueTwo();
    $allowedFieldValues  = $this->getValueThree();

//    Zend_debug::dump($parentFieldName);
//    Zend_debug::dump($matchValue);
//    Zend_debug::dump($allowedFieldValues);
//    
//    Zend_debug::dump($validationFieldValue);
//    Zend_debug::dump($rowDataArray);
    
    if ((null === $rowDataArray) || !is_array($rowDataArray) || !array_key_exists($parentFieldName, $rowDataArray)) {
      $this->_error(self::KEY_NOT_FOUND);
      return false;
    }
 
    $parentFieldNameValue = $rowDataArray[$parentFieldName];
//    Zend_debug::dump($parentFieldNameValue);
    
    // check to make sure that "if" condition is met
    if($parentFieldNameValue != $matchValue)
    {
        return true;
    }
//    Zend_debug::dump($allowedFieldValues);
//    Zend_debug::dump($validationFieldValue);
    $valid = false;
    foreach($allowedFieldValues as $value)
    {
        // if field not empty, set valid to true
//      Zend_debug::dump($fieldName);
//      Zend_debug::dump($rowDataArray[$fieldName]);
        if($value == $validationFieldValue) $valid = true;
    }
    
    if($valid) {
        return true;
    } else {
        $this->_error(self::KEY_IS_EMPTY);
        return false;
    }
  }
 
  /**
   * @return string
   */
  protected function getValueOne() {
    return $this->_parentFieldName;
  }
 
  /**
   * @param string $parentFieldName
   */
  protected function setValueOne($parentFieldName) {
    $this->_parentFieldName = $parentFieldName;
  }
 
  /**
   * @return string
   */
  protected function getValueTwo () {
    return $this->_matchValue;
  }
 
  /**
   * @param string $matchValue
   */
  protected function setValueTwo ($matchValue) {
    $this->_matchValue = $matchValue;
  }
  
  /**
   * @return string
   */
  protected function getValueThree () {
    return $this->_allowedFieldValues;
  }
 
  /**
   * @param string $allowedFieldValues
   */
  protected function setValueThree ($allowedFieldValues) {
    $this->_allowedFieldValues = $allowedFieldValues;
  }

}