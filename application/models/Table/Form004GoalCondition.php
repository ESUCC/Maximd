<?php

class Model_Table_Form004GoalCondition extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_goal_condition';
   	protected $_primary = 'id_goal_condition';
	protected $_sequence = "iep_goal_condition_id_goal_condition_seq";
	
    function distinctConditions()
    {
        
        $select = $this->select()
            ->distinct()
            ->from(array('iep_goal_condition'), 'domain')
            ->order('domain');
        $results = $this->fetchAll($select);
        return $results;         
    }
    
    function getConditions($code)
    {
        
        $select = $this->select()
            ->where('condition_code = ?', $code);
//            print($select);
        $results = $this->fetchAll($select);
        return $results;         
    }
    
    function getConditionsByDomain($code)
    {
        
        $select = $this->select()
            ->where('domain = ?', $code);
//            print($select);
        $results = $this->fetchAll($select);
        return $results;         
    }
    
    
}


