<?php

/**
 * Form003
 *  
 * @author jlavere
 * @version 
 */
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Form003 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_003';
    protected $_primary = 'id_form_003';
    protected $_dependentTables = array(
							'Model_Table_Form003TeamMemberAbsences', 
    						'Model_Table_Form003OutsideAgency',
    						);
    
}

