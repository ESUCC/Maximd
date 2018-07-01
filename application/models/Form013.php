<?php

/**
 * Form013 model
 *
 */
class Model_Form013 extends Model_AbstractForm
{

    
    protected $_subformTypes = array(
        'OtherServices' => 'other_services',
        'HomeCommunity' => 'home_community',
    );
    
    protected $_subformTitles = array(
        'OtherServices' => 'Other service/supports the child/family is receiving or needs but is not required nor funded by the early intervention program',
        'HomeCommunity' => 'Home and Community-based waiver services/supports that will be provided to support waiver outcomes:',
    );
    
    /**
     * $subformIndexToModel - array
     *
     * @var array
     */
	var $subformIndexToModel = array();
	
    /**
     * $db_form_data - array
     *
     * @var array
     */
	var $db_form_data = array();
	
	public function find($id, $accessMode = "view", $page =1, $versionNumber = 1, $checkout = 0)
	{
	/*
	     * Mike replaced this with the catch try
	    if(false === parent::buildDbForm($id, $accessMode, $page, $versionNumber, $checkout))
	    {
	        
	        return false;
	    }
	    */
	    
		try { 
			
			    $this->buildDbForm($id, $accessMode, $page, $versionNumber, $checkout);
			
			
			
			}
			catch (App_Exception_Checkout $e) {
			    $this->writevar1($e->getMessage(),'this is the error message');
			//    $this->view->scott=$e->getMessage();
			$t[0]['message']=$e->getMessage();
			    return $t;
			}

				
		// build sub forms		
        if('all' == $page || 1 == $page)
        {
            // PARENTS
            $modelName      = 'Model_Table_Form013Parents';
            $select         = $this->db_form->select()->where("status != 'Deleted' OR status is null")->order('timestamp_created ASC');
            $foundRows     = $this->db_form->findDependentRowset($modelName, 'Model_Table_Form013', $select);
            $this->db_form_data['ifsp_parents']['count'] = count($foundRows);
            $this->db_form_data['ifsp_parents']['subformTitle'] = 'Parent(s)/Guardian';
            $rownum = 1;
            foreach($foundRows as $db_row)
            {
                $this->db_form_data['ifsp_parents_'.$rownum] = $db_row->toArray();
                $this->db_form_data['ifsp_parents_'.$rownum]['rownumber'] = $rownum;
                $rownum++;
            }
            $this->subformIndexToModel['ifsp_parents'] = "Model_Table_Form013Parents";

            foreach ($this->_subformTypes AS $key => $value) {
                $modelName		= 'Model_Table_Form013'.$key;
                $select 		= $this->db_form->select()->where("status != 'Deleted'")->order('timestamp_created ASC');
                $subformResults = $this->db_form->findDependentRowset($modelName, 'Model_Table_Form013', $select);
                $this->db_form_data[$value]['count'] = count($subformResults);
                $this->db_form_data[$value]['subformTitle'] = '<span>'.$this->_subformTitles[$key].'</span>';
                $rownum = 1;
                //			Zend_Debug::dump($teamMember);
                foreach($subformResults as $db_row)
                {
                    $this->db_form_data[$value.'_'.$rownum] = $db_row->toArray();
                    $this->db_form_data[$value.'_'.$rownum]['rownumber'] = $rownum;
                    $rownum++;
                }
                $this->subformIndexToModel[$value] = "Model_Table_Form013".$key;
            }
        } 

        if('all' == $page || 2 == $page)
        {
            //$this->db_form_data['ifsp_history'] = $this->getIfspHistory($this->db_form_data['id_student'], $this->db_form_data['id_form_013']);
//            Zend_Debug::dump($result);
            
//			$rows = count($result);
//			$p = 0;
//			for($i = 0; $i < $rows; $i++) {
//				$data = $result[$i];
//				$id_form_013 = $data['id_form_013'];
//				$dupedFromID = $data['id_form_013_duped_from'];
//				if('' == $dupedFromID || false === array_search($dupedFromID, array_keys($ifspArray)))
//				{
//	                $data['meeting_date'] = date_massage($data['meeting_date']);
//    	            $data['meeting_date_sent']	= date_massage($data['meeting_date_sent']);
//                    if('' == $dupedFromID)
//                    {   
//                        // compare meeting_date 
//                        if(array_key_exists($id_form_013, $ifspArray))
//                        {
//                            if($data['meeting_date'] > $ifspArray[$id_form_013]['meeting_date']) $ifspArray[$id_form_013] = $data;
//                        } else {
//                            $ifspArray[$id_form_013] = $data;
//                        }
//                        
//                    } else {
//                        // compare meeting_date 
//                        if(array_key_exists($dupedFromID, $ifspArray))
//                        {
//                            if($data['meeting_date'] > $ifspArray[$dupedFromID]['meeting_date']) $ifspArray[$dupedFromID] = $data;
//                        } else {
//                            $ifspArray[$dupedFromID] = $data;
//                        }
//                    }
//				}
//			}
            
        } 
        
        if('all' == $page || 5 == $page)
        {
            // GOALS
            $modelName      = 'Model_Table_Form013Goals';
            $select         = $this->db_form->select()->where("status != 'Deleted' OR status is null")->order('timestamp_created ASC');
            $foundRows     = $this->db_form->findDependentRowset($modelName, 'Model_Table_Form013', $select);
            $this->db_form_data['ifsp_goals']['count'] = count($foundRows);
            $this->db_form_data['ifsp_goals']['subformTitle'] = 'Goals';
            $rownum = 1;
            foreach($foundRows as $db_row)
            {
                $this->db_form_data['ifsp_goals_'.$rownum] = $db_row->toArray();
                $this->db_form_data['ifsp_goals_'.$rownum]['rownumber'] = $rownum;
                $this->db_form_data['ifsp_goals_'.$rownum]['ifsptype'] = $this->db_form_data['ifsptype'];
                
                $rownum++;
            }
            //var_dump($this->db_form_data['ifsp_goals']);
            $this->db_form_data['ifsp_goals']['ifsptype'] = $this->db_form_data['ifsptype'];
            $this->subformIndexToModel['ifsp_goals'] = "Model_Table_Form013Goals";
        } 
        if('all' == $page || 6 == $page)
        {
            // SERVICES
            $modelName      = 'Model_Table_Form013Services';
            $select         = $this->db_form->select()->where("status != 'Deleted' OR status is null")->order('timestamp_created ASC');
            $foundRows     = $this->db_form->findDependentRowset($modelName, 'Model_Table_Form013', $select);
            $this->db_form_data['ifsp_services']['count'] = count($foundRows);
            $this->db_form_data['ifsp_services']['subformTitle'] = 'Services';
            $rownum = 1;
            foreach($foundRows as $db_row)
            {
                $this->db_form_data['ifsp_services_'.$rownum] = $db_row->toArray();
                $this->db_form_data['ifsp_services_'.$rownum]['rownumber'] = $rownum;
                $rownum++;
            }
            $this->subformIndexToModel['ifsp_services'] = "Model_Table_Form013Services";
        } 
        if('all' == $page || 7 == $page)
        {
            // SERVICES
            $modelName      = 'Model_Table_Form013TranPlan';
            $select         = $this->db_form->select()->where("status != 'Deleted' OR status is null")->order('timestamp_created ASC');
            $foundRows     = $this->db_form->findDependentRowset($modelName, 'Model_Table_Form013', $select);
            $this->db_form_data['tran_plan']['count'] = count($foundRows);
            $this->db_form_data['tran_plan']['subformTitle'] = 'Transition Plan';
            $rownum = 1;
            foreach($foundRows as $db_row)
            {
                $this->db_form_data['tran_plan_'.$rownum] = $db_row->toArray();
                $this->db_form_data['tran_plan_'.$rownum]['rownumber'] = $rownum;
                $rownum++;
            }
            $this->subformIndexToModel['tran_plan'] = "Model_Table_Form013TranPlan";
        } 

        if('all' == $page || 8 == $page)
        {
            // team members
            $modelName      = 'Model_Table_Form013TeamMembers';
            $select         = $this->db_form->select()->where("status != 'Deleted' OR status is null")->order('timestamp_created ASC');
            $foundRows     = $this->db_form->findDependentRowset($modelName, 'Model_Table_Form013', $select);
            $this->db_form_data['team_members']['count'] = count($foundRows);
            $this->db_form_data['team_members']['subformTitle'] = 'Team Members Present at the Meeting:';
            $rownum = 1;
            foreach($foundRows as $db_row)
            {
                $this->db_form_data['team_members_'.$rownum] = $db_row->toArray();
                $this->db_form_data['team_members_'.$rownum]['rownumber'] = $rownum;
                $rownum++;
            }
            $this->subformIndexToModel['team_members'] = "Model_Table_Form013TeamMembers";

            // team other
            $modelName      = 'Model_Table_Form013TeamOther';
            $select         = $this->db_form->select()->where("status != 'Deleted' OR status is null")->order('timestamp_created ASC');
            $foundRows     = $this->db_form->findDependentRowset($modelName, 'Model_Table_Form013', $select);
            $this->db_form_data['team_other']['count'] = count($foundRows);
            $this->db_form_data['team_other']['subformTitle'] = 'Others who are part of the Child/Family Team:';
            $rownum = 1;
            foreach($foundRows as $db_row)
            {
                $this->db_form_data['team_other_'.$rownum] = $db_row->toArray();
                $this->db_form_data['team_other_'.$rownum]['rownumber'] = $rownum;
                $rownum++;
            }
            $this->subformIndexToModel['team_other'] = "Model_Table_Form013TeamOther";
        } 
        
        //		echo "<PRE>";
//		print_r($this->db_form_data);
		return $this->db_form_data;
	}

	public function getMeetingDateList($studentId, $ifspType)
	{
        $modelform = new Model_Table_Form013();
        $select = $modelform
                        ->select("id_form_013, ifsp_master_parent(id_form_013) as archived, ifsptype || ':' || meeting_date || ':' ||  meeting_date_sent  || ';' as meeting_date_item")
                        ->where("status = 'Final' and id_student = '".$studentId."' and -2 != ifsp_master_parent(id_form_013)  and id_form_013_duped_from is null")
                        ->order('meeting_date_sent ASC');
		$foundRows = $modelform->fetchAll($select)->toArray();
        return $foundRows;
    }

	public function getIfspHistory($studentId, $thisIfspID)
	{
		$returnRows = array();
        $modelform = new Model_Table_Form013();
        $select = $modelform
                        ->select("id_form_013")
                        ->where("status = 'Final' and id_student = '".$studentId."' and meeting_date <= now()::date ")
                        ->order('meeting_date_sent ASC');
		if($thisIfspID != '') $select->where( " id_form_013 != '$thisIfspID' ");
		$foundRows = $modelform->fetchAll($select)->toArray();

		// fetch IFSPs and all their related data
		foreach($foundRows as $ifsp) {
			$returnRows[] = $this->find($ifsp['id_form_013'], "view", 'all');
		}
		
		return $returnRows;
    }
    
}
