<?php

class Model_Table_Form004GoalSubTopic extends Model_Table_AbstractIepForm {
	
	protected $_name = 'iep_goal_subtopic';
   	protected $_primary = 'id_goal_subtopic';
	protected $_sequence = "iep_goal_subtopic_id_goal_subtopic_seq";
	
    function getTopics($code)
    {
        
        $select = $this->select()
            ->from(array('iep_goal_subtopic'), array('subtopic_code', 'subtopic_description'))
            ->where('topic_code = ?', $code)
            ->order('subtopic_description');
//            print($select);die();
        $results = $this->fetchAll($select);
        return $results;         
    }
    

}