<?php

class Model_Table_Form018SupplementalForm extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_form_018_supp';
	protected $_primary = 'id_form_018_supp';
	protected $_sequence = 'iep_form_018_supp_id_form_018_supp_seq';
	
   protected $_referenceMap    = array(
   	'Model_Table_Form018' => array(
      	'columns'           => array('id_form_018'),
         'refTableClass'     => 'Model_Table_Form018',
         'refColumns'        => array('id_form_018')
		)
	);
    
}