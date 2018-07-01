<?php
require_once 'Zend/Validate/Abstract.php';

class My_Validate_NssrsField052 extends Zend_Validate_Abstract
{
    const BADVALUE = 'badvalue';
    const NOEXITDATE = 'noexitdate';

    protected $_messageTemplates = array(
        self::BADVALUE => "'%value%' is not a valid value.",
        self::NOEXITDATE => "Exit Date must have a value when Exit Reason is set."
    );

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        if( (empty($value) && '' == $context['field34']) || (10 == strlen($context['field34']) && !empty($value)) )
        {
            return true;
        } else {
            if(empty($value)) {
                $this->_error(self::BADVALUE);
            }
            if(10 != strlen($context['field34'])) {
                $this->_error(self::NOEXITDATE);
            }
            return false;
        }

        return true;
    }
}
