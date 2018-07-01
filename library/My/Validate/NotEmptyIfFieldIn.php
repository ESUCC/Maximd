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
 * @category SRS
 * @package  SRS_Validate
 * @author   Jesse LaVere 
 */
class My_Validate_NotEmptyIfFieldIn extends Zend_Validate_Abstract {
 
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
  protected $_matchField;
 
  /**
   * String to test for
   *
   * @var string
   */
  protected $_thresholdValue;
 
  /**
   * FieldDepends constructor
   *
   * @param string $matchField Name of parent field to test against
   * @param string $thresholdValue Value of multi option that, if selected, child field required
   */
  public function __construct($matchField, $thresholdValue = null) {
    $this->setThresholdValue($thresholdValue);
    $this->setMatchField($matchField);
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
 
  	$matchField = $this->getMatchField();
  	
    // If context key is an array, doValid for each context key
    if (is_array($matchField)) {
      foreach ($matchField as $ck) {
        $this->setMatchField($ck);
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
    $thresholdValue  = $this->getThresholdValue();
    $matchField = $this->getMatchField();
    $validationFieldValue      = (string) $validationFieldValue;			// value of the field being validated
    $this->_setValue($validationFieldValue);

//    Zend_debug::dump($thresholdValue);
//    Zend_debug::dump($matchField);
//    Zend_debug::dump($validationFieldValue);
//    Zend_debug::dump($rowDataArray);
    
    if ((null === $rowDataArray) || !is_array($rowDataArray) || !array_key_exists($matchField, $rowDataArray)) {
      $this->_error(self::KEY_NOT_FOUND);
      return false;
    }
 
    if (is_array($rowDataArray[$matchField])) {
      $matchFieldValue = $rowDataArray[$matchField][0];
    } else {
      $matchFieldValue = $rowDataArray[$matchField];
    }
    
//    Zend_debug::dump($matchFieldValue);
    
    if (!is_null($thresholdValue)) {
    	if (in_array($matchFieldValue,$thresholdValue) && empty($validationFieldValue)) {
        	$this->_error(self::KEY_IS_EMPTY);
        	return false;
      	}
    } else {
      	if (!empty($matchFieldValue) && empty($validationFieldValue)) {
        	$this->_error(self::KEY_IS_EMPTY);
        	return false;
      	}
    }
    return true;
  }
 
  /**
   * @return string
   */
  protected function getMatchField() {
    return $this->_matchField;
  }
 
  /**
   * @param string $matchField
   */
  protected function setMatchField($matchField) {
    $this->_matchField = $matchField;
  }
 
  /**
   * @return string
   */
  protected function getThresholdValue () {
    return $this->_thresholdValue;
  }
 
  /**
   * @param string $thresholdValue
   */
  protected function setThresholdValue ($thresholdValue) {
    $this->_thresholdValue = $thresholdValue;
  }
}
