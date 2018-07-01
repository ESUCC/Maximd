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
class My_Validate_Form004MipsEditorExplanation extends Zend_Validate_Abstract {
 
  /**
   * Validation failure message key for when the value of the parent field is an empty string
   */
  const KEY_NOT_FOUND  = 'keyNotFound';
 
  /**
   * Validation failure message key for when the value is an empty string
   */
  const KEY_IS_EMPTY   = 'keyIsEmpty';
 
  const FIELD_EMPTY = 'fieldEmpty';
  
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
   * String to test for
   *
   * @var string
   */
  protected $_testValue;
 
  /**
   * FieldDepends constructor
   *
   * @param string $contextKey Name of parent field to test against
   * @param string $testValue Value of multi option that, if selected, child field required
   */
  public function __construct($contextKey, $testValue = null) {
    $this->setTestValue($testValue);
    $this->setContextKey($contextKey);
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
    $testValue  = $this->getTestValue();
    $contextKey = $this->getContextKey();
    $value      = (string) $value;			// value of the field being validated
    $this->_setValue($value);

    if ((null === $context) || !is_array($context) || !array_key_exists($contextKey, $context)) {
      $this->_error(self::KEY_NOT_FOUND);
      return false;
    }
 
    if (is_array($context[$contextKey])) {
      $parentField = $context[$contextKey][0];
    } else {
      $parentField = $context[$contextKey];
    }
    
    /*
     * It depends on the Primary and Related services.  
     * If use has selected "Physical Therapy", "Occupational Therapy" 
     * or "Speech Language Therapy" then the MIPS form should appear.  
     * If not, then it should be hidden
     */
    $showMips = false;
    if('Occupational Therapy Services'==$context ['primary_disability_drop'] ||
            'Physical Therapy'==$context ['primary_disability_drop'] ||
            'Speech-language therapy'==$context ['primary_disability_drop']) {
        $showMips = true;
    }
    // related services
    $i =1;
    while(isset($context['related_services_'.$i])) {
        $serviceArr = $context['related_services_'.$i];
        if('Occupational Therapy Services'==$serviceArr ['related_service_drop'] ||
                'Physical Therapy'==$serviceArr ['related_service_drop'] ||
                'Speech-language therapy'==$serviceArr ['related_service_drop']) {
                $showMips = true;
        }
        $i++;
    }
    
    // only validate when mips is shown
    if(false==$showMips) {
        return true;
    }
    
    $fieldConsideredEmpty = false;
    if('' === $value) {
    	$fieldConsideredEmpty = true;
    }
    if('<br _moz_editor_bogus_node="TRUE" />' === $value) {
    	$fieldConsideredEmpty = true;
    }
    if('<br />'=== $value) {
    	$fieldConsideredEmpty = true;
    }
//     App_Form_FormHelper::zdebugUsageToFile ( $testValue, "testValue", APPLICATION_PATH . "/../temp/buildSrsFormAction.txt" );
    
//     Zend_Debug::dump($testValue, 'Match Value');
//     Zend_Debug::dump($parentField, 'Parent Signature');
//     Zend_Debug::dump($fieldConsideredEmpty, 'field empty');
    
    
    if (!is_null($testValue)) {
//         Zend_Debug::dump($testValue, 'Match Value NOT NULL');
//         Zend_Debug::dump($testValue == ($parentField), '$testValue(Match Value) == ($parentField(Parent Signature))');
    	if ($testValue == ($parentField) && $fieldConsideredEmpty) {
//     	    Zend_Debug::dump('INVALID');
        	$this->_error(self::FIELD_EMPTY);
        	return false;
      	}
    } else {
//         Zend_Debug::dump($testValue, 'Match Value NULL');
      	if (!empty($parentField) && $fieldConsideredEmpty) {
        	$this->_error(self::FIELD_EMPTY);
        	return false;
      	}
    }
//     Zend_Debug::dump('VALID');
    return true;
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
  protected function getTestValue () {
    return $this->_testValue;
  }
 
  /**
   * @param string $testValue
   */
  protected function setTestValue ($testValue) {
    $this->_testValue = $testValue;
  }
}