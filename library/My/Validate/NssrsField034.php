<?php
require_once 'Zend/Validate/Abstract.php';

class My_Validate_NssrsField034 extends Zend_Validate_Abstract
{
    const BADDATE = 'baddate';

    protected $nssrsSnapshotDate;
    protected $octoberCuttoff;

    protected $_messageTemplates = array(
        self::BADDATE => "'%value%' is not between the cuttoffs."
    );

    public function __construct($juneCutoff, $octoberCuttoff)
    {
        $this->nssrsSnapshotDate = $juneCutoff;
        $this->octoberCuttoff = $octoberCuttoff;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);
        if (
            ('' == $value || strtotime($value) >= strtotime($this->nssrsSnapshotDate)) &&
            ('' == $context['field52'] || ('' != $context['field52'] && 10 == strlen($value) && strtotime($value) <= strtotime($this->octoberCuttoff)))
        ) {
            return true;
        } else {
            $this->_error(self::BADDATE);
            return false;
        }

        return true;
    }

}
