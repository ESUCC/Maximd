<?php

class Model_Table_Form029OutsideAgency extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_029_agency_representitive'; // table name
    protected $_primary = 'id_form_029_agency_representitive';
    protected $_sequence = 'form_029_agency_representitiv_id_form_029_agency_representi_seq';

//    protected $_dependentTables = array(
//							'Form029TeamMemberInput', 
//    						);
    
	protected $_referenceMap    = array(
        'Model_Table_Form029' => array(
            'columns'           => array('id_form_029'),
            'refTableClass'     => 'Model_Table_Form029',
            'refColumns'        => array('id_form_029')
        )
    );
    
}