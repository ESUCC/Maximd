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
 * 
 * @uses     Zend_Validate_Abstract
 * @category SRS
 * @package  SRS_Validate
 * @author   Jesse LaVere
 */
class My_Validate_SumFieldsTo extends Zend_Validate_Abstract {
 
  /**
   * Validation failure message key for when the value of the parent field is an empty string
   */
  const KEY_NOT_FOUND  = 'keyNotFound';
 
  /**
   * Validation failure message key for when the sum is incorrect
   */
  const SUM_INCORRECT  = 'sumIncorrect';
 
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
    self::SUM_INCORRECT  => 'Sum of percent fields must be 100.',
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
  protected $_sumTo;
 
  /**
   * String to test for
   *
   * @var string
   */
  protected $_fieldsToSum;
 
  /**
   * FieldDepends constructor
   *
   * @param string $parentFieldName Name of parent field to test against
   * @param string $sumTo Value of multi option that, if selected, child field required
   */
  public function __construct($parentFieldName, $sumTo = null, $fieldsToSum = null) {

    $this->setValueOne($parentFieldName);
    $this->setValueTwo($sumTo);
    $this->setValueThree($fieldsToSum);
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
    $sumTo  = $this->getValueTwo();
    $fieldsToSum  = $this->getValueThree();

//    Zend_debug::dump($rowDataArray);
    $sum = 0;
    foreach($fieldsToSum as $value)
    {
    	$sum += $rowDataArray[$value];
    }
//    Zend_debug::dump($sum);
    if($sum != $sumTo) {
    	$this->_error(self::SUM_INCORRECT);
    	return false;
    } else {
    	return true;
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
    return $this->_sumTo;
  }
 
  /**
   * @param string $sumTo
   */
  protected function setValueTwo ($sumTo) {
    $this->_sumTo = $sumTo;
  }
  
  /**
   * @return string
   */
  protected function getValueThree () {
    return $this->_fieldsToSum;
  }
 
  /**
   * @param string $fieldsToSum
   */
  protected function setValueThree ($fieldsToSum) {
    $this->_fieldsToSum = $fieldsToSum;
  }

}