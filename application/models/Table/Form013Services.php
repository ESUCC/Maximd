<?php

class Model_Table_Form013Services extends Model_Table_AbstractIepForm {
	
	protected $_name = 'ifsp_services'; // table name
    protected $_primary = 'id_ifsp_services';
    protected $_sequence = 'ifsp_services_id_ifsp_servi_seq';
    protected $_referenceMap    = array(
        'Model_Table_Form013' => array(
            'columns'           => array('id_form_013'),
            'refTableClass'     => 'Model_Table_Form013',
            'refColumns'        => array('id_form_013')
        )
    );
    
}