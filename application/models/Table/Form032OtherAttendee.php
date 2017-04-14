<?php

class Model_Table_Form032OtherAttendee extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_032_other_attendee';
    protected $_primary = 'id_form_032_other_attendee';
	protected $_sequence = 'form_032_other_attendee_id_form_032_other_attendee_seq';
    protected $_referenceMap    = array(
        'Model_Table_Form032' => array(
            'columns'           => array('id_form_032'),
            'refTableClass'     => 'Model_Table_Form032',
            'refColumns'        => array('id_form_032')
        )
    );
    
}