<?php

class Model_Table_Form013Goals extends Model_Table_AbstractIepForm {
	
	protected $_name = 'ifsp_goals'; // table name
    protected $_primary = 'id_ifsp_goals';
    protected $_sequence = 'ifsp_goals_id_ifsp_goals_seq';
    protected $_referenceMap    = array(
        'Model_Table_Form013' => array(
            'columns'           => array('id_form_013'),
            'refTableClass'     => 'Model_Table_Form013',
            'refColumns'        => array('id_form_013')
        )
    );
    
}