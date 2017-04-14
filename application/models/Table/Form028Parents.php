<?php

class Model_Table_Form028Parents extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_028_parents';
    protected $_primary = 'id_form_028_parents';
	protected $_sequence = 'form_028_parents_id_form_028_parents_seq';
    protected $_referenceMap    = array(
        'Model_Table_Form028' => array(
            'columns'           => array('id_form_028'),
            'refTableClass'     => 'Model_Table_Form028',
            'refColumns'        => array('id_form_028')
        )
    );
    
}