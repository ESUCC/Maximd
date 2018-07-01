<?php

class Model_Table_Form004GoalStandard extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_goal_standard';
   	protected $_primary = 'id_goal_standard';
	protected $_sequence = "iep_goal_standard_id_goal_standard_seq";
	
    function distinctStandards()
    {
        
        $select = $this->select()
            ->distinct()
            ->from(array('iep_goal_standard'), 'standard_domain')
            ->order('standard_domain');
        $results = $this->fetchAll($select);
        return $results;         
    }
    
    function getStandardsByCode($code)
    {
        
        $select = $this->select()
            ->distinct()
//            ->from(array('iep_goal_standard'), 'standard_code')
            ->where('standard_code = ?', $code);
//            ->order('standard_code');
        $results = $this->fetchAll($select);
        return $results;         
    }
    function getStandardsByDomain($code)
    {
        
        $select = $this->select()
            ->distinct()
//            ->from(array('iep_goal_standard'), 'standard_domain')
            ->where('standard_domain = ?', $code);
//            ->order('standard_code');
        $results = $this->fetchAll($select);
        return $results;         
    }
    
    
}