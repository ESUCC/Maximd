<?php

class Model_Table_Form004TeamMember extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_team_member'; // table name
    protected $_primary = 'id_iep_team_member';

    protected $_referenceMap    = array(
        'Model_Table_Form004' => array(
            'columns'           => array('id_form_004'),
            'refTableClass'     => 'Model_Table_Form004',
            'refColumns'        => array('id_form_004')
        )
    );
    
}