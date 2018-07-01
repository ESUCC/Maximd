<?php
require_once 'Zend/Validate/Abstract.php';

class My_Validate_NssrsField003 extends Zend_Validate_Abstract
{
    const BADDATE = 'baddate';

    protected $nssrsSnapshotDate;

    protected $_messageTemplates = array(
        self::BADDATE => "'%value%' is not valid."
    );

    public function __construct($nssrsSnapshotDate)
    {
        $this->nssrsSnapshotDate = $nssrsSnapshotDate;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        if (strtotime($value) === strtotime($this->nssrsSnapshotDate))
        {
            return true;
        } else {
            $this->_error(self::BADDATE);
            return false;
        }

        return true;
    }
}
