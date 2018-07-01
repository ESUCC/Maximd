<?php


class My_Validate_SameValueConfirmation extends Zend_Validate_Abstract
{
    const NOT_MATCH = 'notMatch'; 
    protected $_messageTemplates = array(self::NOT_MATCH => 'Value Does not match its repeated value'); 
    private $repeatedField; 
    public function __construct($repeatedField)
    { 
        $this->repeatedField = $repeatedField;
//        echo "repeatedField: $repeatedField<BR>";
    }

    public function isValid($value, $context = null)
    {
        $value = (string) $value;
        $this->_setValue($value);
    
        if (is_array($context)) {
            if (isset($context[$this->repeatedField])
                && ($value == $context[$this->repeatedField]))
            {
                return true;
            }
        } elseif (is_string($context) && ($value == $context)) {
            return true;
        }
    
        $this->_error(self::NOT_MATCH);
        return false;
    }

}
