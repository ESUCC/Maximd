<?php

class Model_Table_Form004GoalObjective extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_goal_objective';
   	protected $_primary = 'id_goal_objective';
	protected $_sequence = "iep_goal_objective_id_goal_objective_seq";
	
    function getObjectives($objectiveCode)
    {
        $select = $this->select()
            ->where('objective_code = ?', $objectiveCode)
            ->order('domain');
        $results = $this->fetchAll($select);
        return $results;         
    }

    function getObjectivesBySubstring($codeLength, $code)
    {
        $select = $this->select()
            ->where("SUBSTRING(objective_code, 1, $codeLength) = ?", $code)
            ->order('objective_code');
//            print($select);
        $results = $this->fetchAll($select);
        return $results;         
    }

    
    
    
}