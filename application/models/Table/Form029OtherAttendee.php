<?php

class Model_Table_Form029OtherAttendee extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_029_other_attendee';
    protected $_primary = 'id_form_029_other_attendee';
	protected $_sequence = 'form_029_other_attendee_id_form_029_other_attendee_seq';
    protected $_referenceMap    = array(
        'Model_Table_Form029' => array(
            'columns'           => array('id_form_029'),
            'refTableClass'     => 'Model_Table_Form029',
            'refColumns'        => array('id_form_029')
        )
    );
    
}