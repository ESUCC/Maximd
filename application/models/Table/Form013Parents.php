<?php

class Model_Table_Form013Parents extends Model_Table_AbstractIepForm {
	
	protected $_name = 'ifsp_parents'; // table name
    protected $_primary = 'id_ifsp_parents';
    protected $_sequence = 'ifsp_parents_id_ifsp_parent_seq';
    protected $_referenceMap    = array(
        'Model_Table_Form013' => array(
            'columns'           => array('id_form_013'),
            'refTableClass'     => 'Model_Table_Form013',
            'refColumns'        => array('id_form_013')
        )
    );
    
}