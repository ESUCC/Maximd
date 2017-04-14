<?php

class Model_Table_Form004SecondaryGoal extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_form_004_secondary_goal';
    protected $_primary = 'id_form_004_secondary_goal';
	protected $_sequence = 'iep_form_004_secondary_goal_id_form_004_secondary_goal_seq';
    protected $_referenceMap    = array(
        'Model_Table_Form004' => array(
            'columns'           => array('id_form_004'),
            'refTableClass'     => 'Model_Table_Form004',
            'refColumns'        => array('id_form_004')
        )
    );
    
}