<?php
/**
 * Kendall Extensions
 * 
 * @category Kendall
 * @package  Kendall_Validate
 * @author   Jeremy Kendall 
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
 * @category Kendall
 * @package  Kendall_Validate
 * @author   Jeremy Kendall 
 */
class My_Validate_NotEmptyIfAndEmptyIfOther extends Zend_Validate_Abstract {
 
  /**
   * Validation failure message key for when the value of the parent field is an empty string
   */
  const KEY_NOT_FOUND  = 'keyNotFound';
 
  /**
   * Validation failure message key for when the value is an empty string
   */
  const KEY_IS_EMPTY   = 'keyIsEmpty';

  const FIELD_NOT_VALIDA = 'fieldNotValidA';
  const FIELD_NOT_VALIDB = 'fieldNotValidB';
 
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
   * Key to test against
   *
   * @var string|array
   */
  protected $_contextKey2;
 
  /**
   * FieldDepends constructor
   *
   * @param string $contextKey Name of parent field to test against
   */
  public function __construct($contextKey, $contextKey2 = false) {
    $this->setContextKey($contextKey);
    $this->setContextKey2($contextKey2);
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
    $contextKey2 = $this->getContextKey2();

    // If context key is an array, doValid for each context key
    if (is_array($contextKey)) {
      foreach ($contextKey as $ck) {
        $this->setContextKey($ck);
        if(!$this->doValid($value, $context)) {
          return false;
        }
      }
    } else {
      if(!$this->doValid($value, $context)) {
        return false;
      }
    }
    return true;
  }
 
  /**
   * Returns true if dependant field value is not empty when parent field value
   * indicates that the dependant field is required
   *
   * @param  string $value
   * @param  array  $context
   * @return boolean
   * 
   * if the field is not validating and you think it should
   * double check that required is set to false
   * and if it's a radio or select, that setRegisterInArrayValidator to false 
   * if you want to allow empty values 
   */
  public function doValid($value, $context = null) {

    $contextKey = $this->getContextKey();
    $contextKey2 = $this->getContextKey2();
    $value      = (string) $value;			// value of the field being validated
    $this->_setValue($value);

    if ((null === $context) || !is_array($context) || !array_key_exists($contextKey, $context)) {
      $this->_error(self::KEY_NOT_FOUND);
      // return false;
    }
 
    if (is_array($context[$contextKey])) {
      $parentField = $context[$contextKey][0];
    } else {
      $parentField = $context[$contextKey];
    }

    if (is_array($context[$contextKey2])) {
      $parentField2 = $context[$contextKey2][0];
    } else {
      $parentField2 = $context[$contextKey2];
    }
//    Zend_debug::dump($parentField);

    if ((($parentField) == '0' || ($parentField) == '1') && ($parentField2) != '1' && strlen($value) > 0)
    {
        return true;
    }
    elseif (($parentField2) == '1' && empty($value))
    {
        return true;
    }
    else
    {
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
  protected function getContextKey2() {
    return $this->_contextKey2;
  }

  /**
   * @param string $contextKey2
   */
  protected function setContextKey2($contextKey2) {
    $this->_contextKey2 = $contextKey2;
  }
}
