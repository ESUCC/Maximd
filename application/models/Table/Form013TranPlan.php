<?php

class Model_Table_Form013TranPlan extends Model_Table_AbstractIepForm {
	
	protected $_name = 'ifsp_tran_plan_participants'; // table name
    protected $_primary = 'id_ifsp_tran_plan_participants';
    protected $_sequence = 'ifsp_tran_pla_id_ifsp_tran__seq';
    protected $_referenceMap    = array(
        'Model_Table_Form013' => array(
            'columns'           => array('id_form_013'),
            'refTableClass'     => 'Model_Table_Form013',
            'refColumns'        => array('id_form_013')
        )
    );
    
}