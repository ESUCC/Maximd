<?php

class Model_Table_Form004GoalProgress extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_form_004_goal_progress';
	protected $_primary = 'id_goal_progress';
	protected $_sequence = "iep_form_004__id_goal_progr_seq";

    protected $_dependentTables = array(
		'Form004Goal'
	);
	
    protected $_referenceMap    = array(
        'Model_Table_Form004' => array(
            'columns'           => array('id_form_004'),
            'refTableClass'     => 'Model_Table_Form004',
            'refColumns'        => array('id_form_004')
        ),
        'Model_Table_Form010' => array(
            'columns'           => array('id_form_010'),
            'refTableClass'     => 'Model_Table_Form010',
            'refColumns'        => array('id_form_010')
        ),
        'Model_Table_Form004Goal' => array(
            'columns'           => array('id_form_004_goal'),
            'refTableClass'     => 'Model_Table_Form004Goal',
            'refColumns'        => array('id_form_004_goal')
        )
    );
    
}