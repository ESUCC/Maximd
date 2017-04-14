<?php

class Model_Table_Form032OutsideAgency extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_032_agency_representitive'; // table name
    protected $_primary = 'id_form_032_agency_representitive';
    protected $_sequence = 'form_032_agency_representitiv_id_form_032_agency_representi_seq';

//    protected $_dependentTables = array(
//							'Form032TeamMemberInput', 
//    						);
    
	protected $_referenceMap    = array(
        'Model_Table_Form032' => array(
            'columns'           => array('id_form_032'),
            'refTableClass'     => 'Model_Table_Form032',
            'refColumns'        => array('id_form_032')
        )
    );
    
}