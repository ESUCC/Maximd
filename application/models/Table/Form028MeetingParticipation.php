<?php

class Model_Table_Form028MeetingParticipation extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_028_meeting_participation';
    protected $_primary = 'id_form_028_meeting_participation';
	protected $_sequence = 'form_028_meeting_participation_id_form_028_meeting_participation_seq';
    protected $_referenceMap    = array(
        'Model_Table_Form028' => array(
            'columns'           => array('id_form_028'),
            'refTableClass'     => 'Model_Table_Form028',
            'refColumns'        => array('id_form_028')
        )
    );
    
}