<?php

/**
 * Form005 model
 *
 */
class Model_Form005 extends Model_AbstractForm
{

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

		// build student info for this form
				
		// build sub forms		
//		if('all' == $page || 1 == $page)
//		{
//			// PAGE 1
//			//
//			// TEAM MEMBERS - 10 ROWS AT TOP OF PAGE 1
//			$modelName		= 'Form005TeamMember';
//			$select 		= $this->db_form->select()->order('sortnum ASC');
//			$teamMember 	= $this->db_form->findDependentRowset($modelName, 'Form005', $select);
//			$this->db_form_data['team_member']['count'] = count($teamMember);
//			$this->db_form_data['team_member']['subformTitle'] = 'The Following Participants Were In Attendance At The IEP Meeting';
//			$rownum = 1;
//			foreach($teamMember as $db_row)
//			{
//				$this->db_form_data['team_member_'.$rownum] = $db_row->toArray();
//				$this->db_form_data['team_member_'.$rownum]['rownumber'] = $rownum;
//				$rownum++;
//			}
//			$this->subformIndexToModel['team_member'] = "Form005TeamMember";
//	 		
//			
//			// TEAM OTHER - 1-5 ROWS
//			$select 		= $this->db_form->select()->where("status = 'Active'")->order('sortnum ASC');
//			$teamOther 		= $this->db_form->findDependentRowset('Form005TeamOther', 'Form005', $select);
//			$this->db_form_data['team_other']['count'] = count($teamOther);
//			$this->db_form_data['team_other']['subformTitle'] = 'Others, as determined by the parent';
//			$rownum = 1;
//			foreach($teamOther as $db_row)
//			{
//				$this->db_form_data['team_other_'.$rownum] = $db_row->toArray();
//				$this->db_form_data['team_other_'.$rownum]['rownumber'] = $rownum;
//				$rownum++;
//			}
//			$this->subformIndexToModel['team_other'] = "Form005TeamOther";
//			
//			
//			// TEAM DISTRICT - 1-5 ROWS
//			$select 		= $this->db_form->select()->where("status = 'Active'")->order('sortnum ASC');
//			$teamDistrict 	= $this->db_form->findDependentRowset('Form005TeamDistrict', 'Form005', $select);
//			$this->db_form_data['team_district']['count'] = count($teamDistrict);
//			$this->db_form_data['team_district']['subformTitle'] = 'Districts, as determined by the parent';
//			$rownum = 1;
//			foreach($teamDistrict as $db_row)
//			{
//				$this->db_form_data['team_district_'.$rownum] = $db_row->toArray();
//				$this->db_form_data['team_district_'.$rownum]['rownumber'] = $rownum;
//				$rownum++;
//			}
//			$this->subformIndexToModel['team_district'] = "Form005TeamDistrict";
//		} 
		
//		echo "<PRE>";
//		print_r($this->db_form_data);
		return $this->db_form_data;
	}	
}
