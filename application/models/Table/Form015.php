<?php

/**
 * Form015
 *  
 * @author jlavere
 * @version 
 */
	
class Model_Table_Form015 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_015';
    protected $_primary = 'id_form_015';
    
    public function setDateOfReferral($formId, $dateOfReferral) {
    	$row = $this->fetchRow($this->select()->where('id_form_015 = ?', $formId));
    	$row->date_notice = $dateOfReferral;
    	$row->save();
    }
}

