<?php

class Model_Table_Form004SupplementalForm extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_form_004_supplemental_form';
	protected $_primary = 'id_form_004_supplemental_form';
	protected $_sequence = 'iep_form_004_supplemental_for_id_form_004_supplemental_form_seq';
	
   protected $_referenceMap    = array(
        'Model_Table_Form004' => array(
            'columns'           => array('id_form_004'),
            'refTableClass'     => 'Model_Table_Form004',
            'refColumns'        => array('id_form_004'),
        )
	);
    
}