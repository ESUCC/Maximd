<?php

/**
 * Form003 model
 *
 */
class Model_Form003 extends Model_AbstractForm
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
				
		// build sub forms		
		if('all' == $page || 3 == $page)
		{
			// TEAM MEMBER ABSENCES
			$select 		= $this->db_form->select()->where("(status is null OR status != 'Deleted')")->order('timestamp_created ASC');
			$subformRecords 	= $this->db_form->findDependentRowset('Model_Table_Form003TeamMemberAbsences', 'Model_Table_Form003', $select);
						
			$this->db_form_data['team_member_absences']['count'] = count($subformRecords);
			$this->db_form_data['team_member_absences']['subformTitle'] = 'Team Member Absences';
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				// add the db data for this subform to the main form data array
				$this->db_form_data['team_member_absences_'.$rownum] = $db_row->toArray();
				// add a row number based on sort position - just used for user display
				$this->db_form_data['team_member_absences_'.$rownum]['rownumber'] = $rownum;
				$this->db_form_data['team_member_absences_'.$rownum]['subformIdentifier'] = 'team_member_absences_'.$rownum;
				
				// TEAM MEMBER ABSENCES
				$select 		= $db_row->select()->where("status != 'Deleted'")->order('timestamp_created ASC');
				$teamInput 	= $db_row->findDependentRowset('Model_Table_Form003TeamMemberInput', 'Model_Table_Form003TeamMemberAbsences', $select);
				$this->db_form_data['team_member_absences_'.$rownum]['team_member_input']['count'] = count($teamInput);
				$this->db_form_data['team_member_absences_'.$rownum]['team_member_input']['subformTitle'] = 'Team Member Input';
				
				$subrownum = 1;
				foreach($teamInput as $db_subrow)
				{
					// add the db data for this subform to the main form data array
					$this->db_form_data['team_member_absences_'.$rownum]['team_member_input_'.$subrownum] = $db_subrow->toArray();
					// add a row number based on sort position - just used for user display
					$this->db_form_data['team_member_absences_'.$rownum]['team_member_input_'.$subrownum]['rownumber'] = $subrownum;
					
					$subrownum++;
				}
//				$this->subformIndexToModel['team_member_input'] = "Form003TeamMemberInput";
				
				$rownum++;
			}
			$this->subformIndexToModel['team_member_absences'] = "Model_Table_Form003TeamMemberAbsences";

			
		}
		
		if('all' == $page || 4 == $page)
		{
			// TEAM MEMBER ABSENCES
			$select 		= $this->db_form->select()->where("(status is null OR status != 'Deleted')")->order('timestamp_created ASC');
			$subformRecords 	= $this->db_form->findDependentRowset('Model_Table_Form003OutsideAgency', 'Model_Table_Form003', $select);
			$this->db_form_data['outside_agency']['count'] = count($subformRecords);
//			echo "count: " .count($subformRecords);
			$this->db_form_data['outside_agency']['subformTitle'] = 'Outside Agencies';
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				// add the db data for this subform to the main form data array
				$this->db_form_data['outside_agency_'.$rownum] = $db_row->toArray();
				// add a row number based on sort position - just used for user display
				$this->db_form_data['outside_agency_'.$rownum]['rownumber'] = $rownum;
				$this->db_form_data['outside_agency_'.$rownum]['subformIdentifier'] = 'outside_agency_'.$rownum;
				
				$rownum++;
			}
			$this->subformIndexToModel['outside_agency'] = "Model_Table_Form003OutsideAgency";

			
		}
				
//		echo "<PRE>";
//		print_r($this->db_form_data);
//		echo $select;
		return $this->db_form_data;
	}	
}
