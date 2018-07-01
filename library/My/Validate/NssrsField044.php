<?php
require_once 'Zend/Validate/Abstract.php';

class My_Validate_NssrsField044 extends Zend_Validate_Abstract
{
    const BADDATE = 'baddate';

    protected $_messageTemplates = array(
        self::BADDATE => "'%value%' is not valid."
    );

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        if('' != $value && -1 != $value )
        {
            return true;
        } else {
            $this->_error(self::BADDATE);
            return false;
        }

        return true;
    }
}
