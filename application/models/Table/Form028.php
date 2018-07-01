<?php

/**
 * Form028
 *  
 * @author jlavere
 * @version 
 */
	
class Model_Table_Form028 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_028';
    protected $_primary = 'id_form_028';
    
    protected $_dependentTables = array(
    		'Model_Table_Form028Parents',
    );

    public function setDateOfReferral($formId, $dateOfReferral) {
    	$row = $this->fetchRow($this->select()->where('id_form_028 = ?', $formId));
    	$row->date_of_referral = $dateOfReferral;
    	$row->save();
    }
    
    public function setField($formId, $field, $value) {
    	$row = $this->fetchRow($this->select()->where('id_form_028 = ?', $formId));
    	$row->{$field} = $value;
    	$row->save();
    }
}

