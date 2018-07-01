<?php
class My_Validate_ConfirmValueIfMatchCondition extends Zend_Validate_Abstract
{
    const FLOAT = 'float';
    const NO_MATCH = 'float';

    protected $_messageTemplates = array(
        self::FLOAT => "'%value%' is not a floating point value",
        self::NO_MATCH => "'%value%' does not match the required value",
    );

    /**
     * FieldDepends constructor
     *
     * @param string $contextKey Name of parent field to test against
     * @param string $testValue Value of multi option that, if selected, child field required
     */
    var $confirmValue;
    var $matchField;
    var $matchValue;
    var $value;

    public function setConfirmValue($confirmValue)
    {
        $this->confirmValue = $confirmValue;
    }

    public function getConfirmValue()
    {
        return $this->confirmValue;
    }

    public function setMatchField($matchField)
    {
        $this->matchField = $matchField;
    }

    public function getMatchField()
    {
        return $this->matchField;
    }

    public function setMatchValue($matchValue)
    {
        $this->matchValue = $matchValue;
    }

    public function getMatchValue()
    {
        return $this->matchValue;
    }

    public function __construct($confirmValue, $matchField, $matchValue) {
        $this->setConfirmValue($confirmValue);
        $this->setMatchField($matchField);
        $this->setMatchValue($matchValue);
    }
    public function isValid($value, $context = null)
    {
//        Zend_Debug::dump('value', $value);
//        Zend_Debug::dump('$context[getMatchField]', $context[$this->getMatchField()]);
//        Zend_Debug::dump('getMatchValue', $this->getMatchValue());
//        die;
        $this->_setValue($value);
        if($context[$this->getMatchField()] == $this->getMatchValue()) {
            // match condition met
//            Zend_Debug::dump('match condition met - do next level of validation');
            if(is_array($this->getConfirmValue()) && false !== array_search($value, $this->getConfirmValue())) {
//                Zend_Debug::dump('valid');
                return true;
            } else {
//                Zend_Debug::dump('false');
                $this->_error(self::FLOAT);
                return false;
            }
        } else {
            // valid as match conditions are not met
            return true;

        }
        return true;
    }


}