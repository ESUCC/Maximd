<?php
/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';
 
class My_Validate_Form013Justification extends Zend_Validate_Abstract {
 
  /**
   * Validation failure message key for when the value of the parent field is an empty string
   */
  const KEY_NOT_FOUND  = 'keyNotFound';
 
  /**
   * Validation failure message key for when the value is an empty string
   */
  const KEY_IS_EMPTY   = 'keyIsEmpty';
  const FIELD_EMPTY   = 'fieldIsEmpty';

  /**
   * Validation failure message template definitions
   *
   * @var array
   */
  protected $_messageTemplates = array(
    self::KEY_NOT_FOUND  => 'Parent field does not exist in form input',
    self::KEY_IS_EMPTY   => 'Based on your answer above, this field is required',
    self::FIELD_EMPTY => 'Field must have a value',
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
  public function __construct() {
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

      if(0==$context['service_natural'] && 'School district' == $context['service_who_pays']) {
          if('' === $value) {
              $this->_error ( self::FIELD_EMPTY );
              return false;
          }
          if('<br _moz_editor_bogus_node="TRUE" />' === $value) {
              $this->_error ( self::FIELD_EMPTY );
              return false;
          }
          if('<br />'=== $value) {
              $this->_error ( self::FIELD_EMPTY );
              return false;
          }
      }
      return true;

//    switch ($type)
//    {
//        case 'boolean':
//            if (is_null($value) && $olderThan15) {
////            	Zend_Debug::dump('false 1');
//                return false;
//            } else {
////            	Zend_Debug::dump('true 1');
//                return true;
//            }
//        break;
//        default:
//            if (strlen($value) < 1 && $olderThan15) {
////            	Zend_Debug::dump('false 2');
//                return false;
//            } else {
////            	Zend_Debug::dump($overrideCheckbox, 'true 2');
//                return true;
//            }
//        break;
//    }
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
