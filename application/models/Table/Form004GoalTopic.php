<?php

class Model_Table_Form004GoalTopic extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_goal_topic';
   	protected $_primary = 'id_goal_topic';
	protected $_sequence = "iep_goal_topic_id_goal_topic_seq";
	
    function getTopics($code)
    {
        
        $select = $this->select()
            ->from(array('iep_goal_topic'), array('topic_code','topic_description'))
            ->where('domain_code = ?', $code)
            ->order('topic_description');
//            print($select);die();
        $results = $this->fetchAll($select);
        return $results;         
    }
    

}