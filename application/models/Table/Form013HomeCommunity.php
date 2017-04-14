<?php

class Model_Table_Form013HomeCommunity extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_013_home_community';
    protected $_primary = 'id_form_013_home_community';
	protected $_sequence = 'form_013_home_community_id_form_013_home_community_seq';
    protected $_referenceMap    = array(
        'Model_Table_Form013' => array(
            'columns'           => array('id_form_013'),
            'refTableClass'     => 'Model_Table_Form013',
            'refColumns'        => array('id_form_013')
        )
    );
    
}