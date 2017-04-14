<?php

class Model_Table_Form013TeamOther extends Model_Table_AbstractIepForm {
	
	protected $_name = 'ifsp_team_other'; // table name
    protected $_primary = 'id_ifsp_team_other';
    protected $_sequence = 'ifsp_team_oth_id_ifsp_team__seq';
    protected $_referenceMap    = array(
        'Model_Table_Form013' => array(
            'columns'           => array('id_form_013'),
            'refTableClass'     => 'Model_Table_Form013',
            'refColumns'        => array('id_form_013')
        )
    );
    
}