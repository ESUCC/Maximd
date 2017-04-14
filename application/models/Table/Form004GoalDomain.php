<?php

class Model_Table_Form004GoalDomain extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_goal_domain';
   	protected $_primary = 'id_goal_domain';
	protected $_sequence = "iep_goal_domain_id_goal_domain_seq";
	
    function distinctDomains()
    {
        
        $select = $this->select()
            ->distinct()
            ->from(array('iep_goal_domain'), array('domain_code','domain_description'))
            ->order('domain_description');
	    $results = $this->fetchAll($select);
        return $results;         
    }
	
	
}