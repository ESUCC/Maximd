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
class My_Validate_Form004MipsParentConsent extends Zend_Validate_Abstract {
	
	/**
	 * Validation failure message key for when the value of the parent field is an empty string
	 */
	const KEY_NOT_FOUND = 'keyNotFound';
	
	/**
	 * Validation failure message key for when the value is an empty string
	 */
	const KEY_IS_EMPTY = 'keyIsEmpty';
	
	/**
	 * Validation failure message template definitions
	 *
	 * @var array
	 */
	protected $_messageTemplates = array (self::KEY_NOT_FOUND => 'Parent field does not exist in form input', self::KEY_IS_EMPTY => 'Based on your answer above, this field is required' );
	
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
	public function __construct($parentFieldName, $testValue = null) {
//		echo "parentFieldName: $parentFieldName<BR>";
//		echo "test: $testValue<BR>";
		$this->setTestValue ( $testValue );
		$this->setContextKey ( $parentFieldName );
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
	public function isValid($value, $formData = null, $defaultValidation = true) {
//		echo "here";die();
		$parentFieldName = $this->getContextKey ();
//		echo $parentFieldName . "\n";
//		echo $formData[$parentFieldName] . "\n";
		if(!isset($formData[$parentFieldName])) return $defaultValidation;
//		print_r($formData);
		
		// If context key is an array, doValid for each context key
		if (is_array ( $parentFieldName )) {
			foreach ( $parentFieldName as $pfName ) {
				$this->setContextKey ( $pfName );
				if (! $this->doValid ( $value, $formData )) {
					return false;
				}
			}
		} else {
			if (! $this->doValid ( $value, $formData )) {
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
	 */
	public function doValid($value, $formData = null) {
		/*
		 * It depends on the Primary and Related services.
		* If use has selected "Physical Therapy", "Occupational Therapy"
		* or "Speech Language Therapy" then the MIPS form should appear.
		* If not, then it should be hidden
		*/
		$showMips = false;
		if('Occupational Therapy Services'==$formData ['primary_disability_drop'] ||
		        'Physical Therapy'==$formData ['primary_disability_drop'] ||
		        'Speech-language therapy'==$formData ['primary_disability_drop']) {
		        $showMips = true;
		}
		// related services
		$i =1;
		while(isset($formData['related_services_'.$i])) {
		    $serviceArr = $formData['related_services_'.$i];
		    if('Occupational Therapy Services'==$serviceArr ['related_service_drop'] ||
		            'Physical Therapy'==$serviceArr ['related_service_drop'] ||
		            'Speech-language therapy'==$serviceArr ['related_service_drop']) {
		            $showMips = true;
		    }
		    $i++;
		}
		
		// only validate when mips is shown
		if(false===$showMips) {
		    return true;
		}
		// dont validate unless doc signed is true
		if(!isset($formData['pg6_doc_signed_parent']) || true!=$formData['pg6_doc_signed_parent']) {
		    return true;
		}
		// parent sig is true and field is empty
		if(true==$formData['pg6_doc_signed_parent'] && null==$value) {
		    $this->_error ( self::KEY_IS_EMPTY );
		    return false;
		}
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
	protected function getTestValue() {
		return $this->_testValue;
	}
	
	/**
	 * @param string $testValue
	 */
	protected function setTestValue($testValue) {
		$this->_testValue = $testValue;
	}
}