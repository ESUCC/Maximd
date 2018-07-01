<?php

class Model_Table_Form004ProgramModifications extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_004_prog_mods';
    protected $_primary = 'id_form_004_prog_mods';
	
    protected $_referenceMap    = array(
        'Model_Table_Form004' => array(
            'columns'           => array('id_form_004'),
            'refTableClass'     => 'Model_Table_Form004',
            'refColumns'        => array('id_form_004')
        )
    );
    
}