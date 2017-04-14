<?php

class Model_Table_Form032TeamMemberAbsences extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_absence'; // table name
    protected $_primary = 'id_iep_absence';

    protected $_dependentTables = array(
							'Model_Table_Form032TeamMemberInput', 
    						);
    
	protected $_referenceMap    = array(
        'Model_Table_Form032' => array(
            'columns'           => array('id_form_032'),
            'refTableClass'     => 'Model_Table_Form032',
            'refColumns'        => array('id_form_032')
        )
    );
    
}