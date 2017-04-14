<?php
class Model_Table_ViewGoalProgress extends Zend_Db_Table_Abstract {
 
    protected $_name = 'goal_progress';
    protected $_primary = 'id_goal_progress';
    
//    protected $_dependentTables = array(
//		'SchoolReportDates'
//	);
    protected $_referenceMap    = array(
        'Model_Table_Form004' => array(
            'columns'           => array('id_form_004'),
            'refTableClass'     => 'Model_Table_Form004',
            'refColumns'        => array('id_form_004')
        ),
        'Model_Table_Form010' => array(
            'columns'           => array('id_form_010'),
            'refTableClass'     => 'Model_Table_Form010',
            'refColumns'        => array('id_form_010')
        ),
    );
	
}
