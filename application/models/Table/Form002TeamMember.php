<?php

class Model_Table_Form002TeamMember extends Model_Table_AbstractIepForm {
	
    protected $_name = 'form_002_team_member';
    protected $_primary = 'id_form_002_team_member';
	protected $_sequence = 'form_002_team_member_id_form_002_team_member_seq';
    
    protected $_referenceMap    = array(
        'Model_Table_Form002' => array(
            'columns'           => array('id_form_002'),
            'refTableClass'     => 'Model_Table_Form002',
            'refColumns'        => array('id_form_002')
        )
    );
}