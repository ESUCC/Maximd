<?php

class Model_Table_Form003TeamMemberAbsences extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_absence'; // table name
    protected $_primary = 'id_iep_absence';

    protected $_dependentTables = array(
							'Model_Table_Form003TeamMemberInput', 
    						);
    
	protected $_referenceMap    = array(
        'Model_Table_Form003' => array(
            'columns'           => array('id_form_003'),
            'refTableClass'     => 'Model_Table_Form003',
            'refColumns'        => array('id_form_003')
        )
    );
    
}