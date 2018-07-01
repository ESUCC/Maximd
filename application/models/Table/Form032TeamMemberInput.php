<?php

class Model_Table_Form032TeamMemberInput extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_team_member_input'; // table name
    protected $_primary = 'id_iep_team_member_input';

    protected $_referenceMap    = array(
        'Model_Table_Form032TeamMemberAbsences' => array(
            'columns'           => array('id_iep_absence'),
            'refTableClass'     => 'Model_Table_Form032TeamMemberAbsences',
            'refColumns'        => array('id_iep_absence')
        )
    );
    
}
