<?php

class Model_Table_Form032ContactAttempts extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_032_contact_attempts';
    protected $_primary = 'id_form_032_contact_attempts';
	protected $_sequence = 'form_032_contact_attempts_id_form_032_contact_attempts_seq';
    protected $_referenceMap    = array(
        'Model_Table_Form032' => array(
            'columns'           => array('id_form_032'),
            'refTableClass'     => 'Model_Table_Form032',
            'refColumns'        => array('id_form_032')
        )
    );
    
}