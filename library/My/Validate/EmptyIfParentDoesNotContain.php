<?php
/**
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 */
class My_Validate_EmptyIfParentDoesNotContain extends Zend_Validate_Abstract {
	
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
	public function isValid($value, $formData = null) {
		
		$parentFieldName = $this->getContextKey ();

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
		$testValue = $this->getTestValue ();
		$contextKey = $this->getContextKey ();
		$value = ( string ) $value;
		$this->_setValue ( $value );
		
		$parentContainsValue = false;
		
		if ((null === $formData) || ! is_array ( $formData ) || ! array_key_exists ( $contextKey, $formData )) {
			$this->_error ( self::KEY_NOT_FOUND );
			return false;
		}
		
		if (is_array ( $formData [$contextKey] )) {
			$repeatingValuesArr = $formData [$contextKey];
			foreach ( $repeatingValuesArr as $parentValue ) {
				if (! is_null ( $testValue )) {
					if (is_array($testValue)) {
						foreach($testValue as $tval) {
//							Zend_debug::dump('Parent:'.$parentValue.'/TestValue:'.$testValue.'/Value:'.$value, "EINC ARR single parent/single test - ".$contextKey);
							if ($tval == ($parentValue)) {
								$parentContainsValue = true;
							}
						}
					} else {
//						Zend_debug::dump('Parent:'.$parentValue.'/TestValue:'.$testValue.'/Value:'.$value, "EINC multiple parent/single test - ".$contextKey);
						if ($testValue == ($parentValue)) {
							$parentContainsValue = true;
						}
					}
				}
			
			}
		
		} else {
			$parentValue = $formData [$contextKey];
			if (! is_null ( $testValue )) {
				
				if (is_array($testValue)) {
					foreach($testValue as $tval) {
//						Zend_debug::dump('Parent:'.$parentValue.'/TestValue:'.$testValue.'/Value:'.$value, "EINC ARR single parent/single test - ".$contextKey);
						if ($tval == ($parentValue)) {
							$parentContainsValue = true;
						}
					}
				} else {
					// single value in form data
					// single value in test values
//					Zend_debug::dump('Parent:'.$parentValue.'/TestValue:'.$testValue.'/Value:'.$value, "EINC single parent/single test - ".$contextKey);
					if ($testValue == ($parentValue)) {
						$parentContainsValue = true;
					}
				}
			}
		}
//		Zend_debug::dump('parentContainsValue -'.$parentContainsValue, $value);
		if(false === $parentContainsValue && !empty($value))
		{
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