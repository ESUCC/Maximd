<?php

/**
 * Form032
 *  
 * @author jlavere
 * @version 
 */
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Form032 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_032';
    protected $_primary = 'id_form_032';
	protected $_dependentTables = array(
			'Model_Table_Form032OtherAttendee',
			'Model_Table_Form032OutsideAgency',
			'Model_Table_Form032TeamMemberAbsences',
			'Model_Table_Form032ContactAttempts'
	);
    
}

