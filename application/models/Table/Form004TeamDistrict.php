<?php

class Model_Table_Form004TeamDistrict extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_team_district';
    protected $_primary = 'id_iep_team_district';
		
    protected $_referenceMap    = array(
        'Model_Table_Form004' => array(
            'columns'           => array('id_form_004'),
            'refTableClass'     => 'Model_Table_Form004',
            'refColumns'        => array('id_form_004')
        )
    );
    
}