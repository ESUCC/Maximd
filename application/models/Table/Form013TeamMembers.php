<?php

class Model_Table_Form013TeamMembers extends Model_Table_AbstractIepForm {
	
	protected $_name = 'ifsp_team_members'; // table name
    protected $_primary = 'id_ifsp_team_members';
    protected $_sequence = 'ifsp_team_mem_id_ifsp_team__seq';
    protected $_referenceMap    = array(
        'Model_Table_Form013' => array(
            'columns'           => array('id_form_013'),
            'refTableClass'     => 'Model_Table_Form013',
            'refColumns'        => array('id_form_013')
        )
    );
    
    function previousIfspTeamMembers($id_student, $id)
    {
	    $db = Zend_Registry::get('db');
	    $select = $db->select()
	        ->from( array('ifsp'=>'iep_form_013'),
	            array(
	                'id_form_013',
	                ''
	            )
	         )
	        ->join(array('tm' => 'ifsp_team_members'),
	            'ifsp.id_form_013 = tm.id_form_013',
	            array(
	                '*',
	                'id_my_template_data' => 'id_ifsp_team_members'
	            )
	        )
	        ->where("tm.status is null") // status is null or deleted
	        ->where("ifsp.status = 'Final' ")
	        ->where("ifsp.id_student = ? ", $id_student)
	        ->where("ifsp.id_form_013 != '$id'" );
		    
		$results = $db->fetchAll($select);
//		echo $select;
	    return $results;
	}
}

