<?php

/**
 * Form008 model
 *
 */
class Model_Form008 extends Model_AbstractForm
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
//		} 
		
//		echo "<PRE>";
//		print_r($this->db_form_data);
		return $this->db_form_data;
	}	
}
