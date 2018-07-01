<?php

class Model_Table_Form004SchoolSupport extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_004_school_supp';
    protected $_primary = 'id_form_004_school_supp';
	
    protected $_referenceMap    = array(
        'Model_Table_Form004' => array(
            'columns'           => array('id_form_004'),
            'refTableClass'     => 'Model_Table_Form004',
            'refColumns'        => array('id_form_004')
        )
    );
    
}