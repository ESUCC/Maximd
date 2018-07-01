<?php

class Model_Table_Form003OutsideAgency extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_003_agency_representitive'; // table name
    protected $_primary = 'id_form_003_agency_representitive';
    protected $_sequence = 'form_003_agency_representitiv_id_form_003_agency_representi_seq';

//    protected $_dependentTables = array(
//							'Form003TeamMemberInput', 
//    						);
    
	protected $_referenceMap    = array(
        'Model_Table_Form003' => array(
            'columns'           => array('id_form_003'),
            'refTableClass'     => 'Model_Table_Form003',
            'refColumns'        => array('id_form_003')
        )
    );
    
}