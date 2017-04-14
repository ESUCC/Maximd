<?php

class Model_Table_Form029TeamMemberAbsences extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_absence'; // table name
    protected $_primary = 'id_iep_absence';

    protected $_dependentTables = array(
							'Model_Table_Form029TeamMemberInput', 
    						);
    
	protected $_referenceMap    = array(
        'Model_Table_Form029' => array(
            'columns'           => array('id_form_029'),
            'refTableClass'     => 'Model_Table_Form029',
            'refColumns'        => array('id_form_029')
        )
    );
    
}