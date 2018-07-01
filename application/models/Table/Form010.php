<?php

/**
 * Form010
 *  
 * @author jlavere
 * @version 
 */
	
class Model_Table_Form010 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_010';
    protected $_primary = 'id_form_010';
    protected $_sequence = "iep_progress__id_progress_r_seq";
    protected $_dependentTables = array(
		'Model_Table_Form004GoalProgress',
        'Model_Table_ViewGoalProgress',
    );
    protected $_referenceMap    = array(
        'Model_Table_Form004' => array(
            'columns'           => array('id_form_004'),
            'refTableClass'     => 'Model_Table_Form004',
            'refColumns'        => array('id_form_004')
        )
    );
    
}

