<?php

/**
 * Form025
 *  
 * @author jlavere
 * @version 
 */
	
class Model_Table_Form027 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_027';
    protected $_primary = 'id_form_027';

    public function setDateOfReferral($formId, $dateOfReferral) {
    	$row = $this->fetchRow($this->select()->where('id_form_027 = ?', $formId));
    	$row->date_of_referral = $dateOfReferral;
    	$row->save();
    }
}

