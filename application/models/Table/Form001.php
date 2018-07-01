<?php

/**
 * Form001
 *  
 * @author jlavere
 * @version 
 */
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Form001 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_001';
    protected $_primary = 'id_form_001';
//    protected $_dependentTables = array(
//							'Form004TeamMember', 
//    						);
    
}

