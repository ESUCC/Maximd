<?php
/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';
 
class My_Validate_IepTransition extends Zend_Validate_Abstract {
 
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
  public function __construct($contextKey, $endDateField, $type = false) {
    $this->setContextKey($contextKey);
    $this->setIntervalValue($endDateField);
    $this->setTypeValue($type);
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
    $endDateFieldName = $this->getIntervalValue();
    $type = $this->getTypeValue();

    if (is_array($context[$contextKey])) {
      $parentField = $context[$contextKey][0];
    } else {
      $parentField = $context[$contextKey];
    }

    try {
        if (is_array($context[$endDateFieldName])) {
            $endDateField = $context[$endDateFieldName][0];
        } else {
            $endDateField = $context[$endDateFieldName];
        }
    } catch (Exception $e) {
        
    }
    
    $birthdate = new DateTime($parentField); //birthdate
    $effectToDate = new DateTime($endDateField); //ending effect date
    $interval = $birthdate->diff($effectToDate);
    
    if($interval->y >= 16) {
        $olderThan15 = true;
    } else {
        $olderThan15 = false;
    }
    
    switch ($type)
    {
        case 'boolean':
            if (is_null($value) && $olderThan15)
                return false;
            else
                return true;
        break;
        default:
            if (strlen($value) < 1 && $olderThan15)
                return false;
            else
                return true;
        break;
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
  protected function getIntervalValue () {
    return $this->_intervalValue;
  }
 
  /**
   * @param string $intervalValue
   */
  protected function setIntervalValue ($intervalValue) {
    $this->_intervalValue = $intervalValue;
  }

  /**
   * @return string
   */
  protected function getTypeValue()
  {
    return $this->_typeValue;
  }

  /**
   * @param string $typeValue
   */
  protected function setTypeValue($typeValue) 
  {
    $this->_typeValue = $typeValue;
  }
}
