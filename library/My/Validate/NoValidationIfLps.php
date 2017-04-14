<?php
/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';
 
class My_Validate_NoValidationIfLps extends Zend_Validate_Abstract {
 
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
    self::KEY_IS_EMPTY   => 'Based on your answer above, this field is required',
  );
 
  /**
   * Key to test against
   *
   * @var string|array
   */
  protected $_contextKey;
 
  /**
   * String for the date interval 
   *
   * @var string
   */
  protected $_interval;

  /**
   * String for the type value
   *
   * @var string
   */
  protected $_typeValue;
 
  /**
   * FieldDepends constructor
   *
   * @param string $contextKey Name of parent field to test against
   * @param string $interval Value 
   */
  public function __construct($contextKey, $matchValue) {
    $this->setContextKey($contextKey);
    $this->setMatchValue($matchValue);
  }
 
  /**
   * Defined by Zend_Validate_Interface
   *
   * Wrapper around doValid()
   *
   * @param  string $value
   * @param  array  $context
   * @return boolean
   */
  public function isValid($value, $context = null) {
 
  	$contextKey = $this->getContextKey();
  	$matchValue = $this->getMatchValue();
  	if(isset($context[$contextKey])) {
	    if (is_array($context[$contextKey])) {
	      $flag = $context[$contextKey][0];
	    } else {
	      $flag = $context[$contextKey];
	    }
	
	    if($flag == $matchValue) {
	//    	Zend_Debug::dump('false');
	    	return false;
	    } else {
	//    	Zend_Debug::dump('true');
	    	return true;
	    }
  	} else {
  		return false;
  	}
  }
 
  /**
   * @return string
   */
  protected function getContextKey() {
    return $this->_contextKey;
  }
 
  /**
   * @param string $contextKey
   */
  protected function setContextKey($contextKey) {
    $this->_contextKey = $contextKey;
  }
 
  /**
   * @return string
   */
  protected function getMatchValue () {
    return $this->_matchValue;
  }
 
  /**
   * @param string $intervalValue
   */
  protected function setMatchValue ($matchValue) {
    $this->_matchValue = $matchValue;
  }

}
