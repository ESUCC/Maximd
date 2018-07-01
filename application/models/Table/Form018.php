<?php

/**
 * Form018
 *  
 * @author jlavere
 * @version 
 */
	
class Model_Table_Form018 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_018';
    protected $_primary = 'id_form_018';

    protected $_dependentTables = array(
        'Model_Table_Form018Goal',
        'Model_Table_Form018Agency',
        'Model_Table_Form018SupplementalForm',
    );
    
}

