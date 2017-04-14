<?php

/**
 * Form025
 *  
 * @author jlavere
 * @version 
 */
	
class Model_Table_Form026 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_026';
    protected $_primary = 'id_form_026';

    public function defaultTextarea($id, $field, $value) {
    	$row = $this->fetchRow($this->select()->where('id_form_026 = ?', $id));
    	$row->$field = $value;
    	$row->save();
    }
}

