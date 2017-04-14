<?php
require_once 'Zend/Validate/Abstract.php';

class My_Validate_NssrsField035 extends Zend_Validate_Abstract
{
    const BADDATE = 'baddate';

    protected $nssrsSubmissionPeriod;

    protected $_messageTemplates = array(
        self::BADDATE => "'%value%' is not valid."
    );

    public function __construct($nssrsSubmissionPeriod)
    {
        $this->nssrsSubmissionPeriod = $nssrsSubmissionPeriod;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        if (strtotime($value) === strtotime($this->nssrsSubmissionPeriod))
        {
            return true;
        } else {
            $this->_error(self::BADDATE);
            return false;
        }

        return true;
    }
}
