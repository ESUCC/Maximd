<?php

/**
 * Form002 model
 *
 */
class Model_Form002 extends Model_AbstractForm
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

        // if date_mdt is empty, update with date from most recent final form 11
        if('' == $this->db_form_data['date_mdt'] && 1 == $page)
        {
            $form011Obj = new Model_Table_Form011;
            $form011 = $form011Obj->mostRecentFinalForm($this->db_form_data['id_student']);
            $this->db_form_data['date_mdt'] = $form011['date_notice'];
        }
		
        // set date at time of mdt
        if(isset($this->db_form_data['date_mdt']) && isset($this->db_form_data['dob']) && !isset($this->db_form_data['ageAtDateOfMdt'])) {
        	$this->db_form_data['ageAtDateOfMdt'] = $this->ageAtDateOfMdt($this->db_form_data['dob'], $this->db_form_data['date_mdt']);
//        	Zend_Debug::dump($this->db_form_data['ageAtDateOfMdt']);
        }
        
        // build sub forms		
		if('all' == $page || 4 == $page)
		{
			// PAGE 4
			// TEAM MEMBERS - 10 ROWS AT TOP OF PAGE
			$modelName		= 'Model_Table_Form002TeamMember';
			$select 		= $this->db_form->select()->where("status != 'Deleted'")->order('timestamp_created ASC');
			$teamMember 	= $this->db_form->findDependentRowset($modelName, 'Model_Table_Form002', $select);
			$this->db_form_data['team_member']['count'] = count($teamMember);
			$this->db_form_data['team_member']['subformTitle'] = '<span class="printBoldLabeWithBorderWidth">Listing of required Team Members</span>';
			$rownum = 1;
//			Zend_Debug::dump($teamMember);
			foreach($teamMember as $db_row)
			{
				$this->db_form_data['team_member_'.$rownum] = $db_row->toArray();
				$this->db_form_data['team_member_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['team_member'] = "Model_Table_Form002TeamMember";
		} 

		if('all' == $page || 5 == $page)
		{
			// ==========================================================================================
			// SUPPLEMENTAL FORMS
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Model_Table_Form002SupplementalForm', 'Model_Table_Form002', $select);
			$translate = Zend_Registry::get('Zend_Translate');
			$this->db_form_data['form_002_suppform']['count'] = count($subformRecords);
			$this->db_form_data['form_002_suppform']['subformTitle'] = $translate->_('Supplemental Forms');
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['form_002_suppform_'.$rownum] = $db_row->toArray();
				$this->db_form_data['form_002_suppform_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['form_002_suppform'] = "Model_Table_Form002SupplementalForm";
		}
		
		return $this->db_form_data;
	}	

	public function ageAtDateOfMdt ($dob, $dateMdt = null)
    {
    	
    	$dobDate = new Zend_Date($dob, Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	$mdtDate = new Zend_Date($dateMdt, Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	$times = Model_Table_IepStudent::dateDiff($dobDate->toString(), $mdtDate->toString());
    	
//		$sessUser = new Zend_Session_Namespace('user');
//		if(1000254 == $sessUser->sessIdUser) {
//			Zend_Debug::dump($times);
//			Zend_Debug::dump($dob, $dobDate);
//			Zend_Debug::dump($dateMdt, $mdtDate);
//		}
    	
    	if(isset($times['years'])) {
        	return $times['years'];
		}
		return '';
	}
	
}
