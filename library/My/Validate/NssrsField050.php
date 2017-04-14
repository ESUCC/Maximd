<?php
require_once 'Zend/Validate/Abstract.php';

class My_Validate_NssrsField050 extends Zend_Validate_Abstract
{
    const BADDATE = 'baddate';

    protected $_messageTemplates = array(
        self::BADDATE => "'%value%' is not valid."
    );

    //'050' => array("evalPhp", 'if( !emptyAndNotZero($arrData[\'050\']) && 0 <= $arrData[\'050\'] && 100 >= $arrData[\'050\'] && $arrData[\'050\'] == intval($arrData[\'050\'])) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        if( !is_null($value) && 0 <= $value && 100 >= $value && $value == intval($value))
        {
            return true;
        } else {
            $this->_error(self::BADDATE);
            return false;
        }

        return true;
    }
}
