<?php

//
// to add a subform
//
// 1) add code to the buildDbForm function to build the subform arrays in the $this->db_form_data array
// 2) add a function to build the zend form and add to the buildZendForm function in this class  
// 3) add code to save the data to persistData in the main zend form definition file
// 4) build a class for the subtable
// 5) add subform table to class Form004 table dependent tables array
//
class App_Service_Form004Service extends App_Service_AbstractFormService
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
	
	public function getModelFromSubformHeaderIndex($index)
	{
		if(isset($this->subformIndexToModel[$index])) return $this->subformIndexToModel[$index];
		return false; 
	} 
	public function buildDbForm($id, $accessMode = "view", $page =1, $versionNumber = 1, $checkout = 0)
	{
		
		if(false === parent::buildDbForm($id, $accessMode, $page, $versionNumber, $checkout))
		{
			return false;
		}
		
		if('all' == $page || 1 == $page)
		{
			// build sub forms
			// ==========================================================================================
			// PAGE 1
			//
			// TEAM MEMBERS - 10 ROWS AT TOP OF PAGE 1
			$modelName		= 'Form004TeamMember';
			$select 		= $this->db_form->select()->order('sortnum ASC');
			$teamMember 	= $this->db_form->findDependentRowset($modelName, 'Form004', $select);
			$this->db_form_data['team_member']['count'] = count($teamMember);
			$this->db_form_data['team_member']['subformTitle'] = 'The Following Participants Were In Attendance At The IEP Meeting';
			$rownum = 1;
			foreach($teamMember as $db_row)
			{
				$this->db_form_data['team_member_'.$rownum] = $db_row->toArray();
				$this->db_form_data['team_member_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['team_member'] = "Form004TeamMember";
	 		
			
			// TEAM OTHER - 1-5 ROWS
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('sortnum ASC');
			$teamOther 		= $this->db_form->findDependentRowset('Form004TeamOther', 'Form004', $select);
			$this->db_form_data['team_other']['count'] = count($teamOther);
			$this->db_form_data['team_other']['subformTitle'] = 'Others, as determined by the parent';
			$rownum = 1;
			foreach($teamOther as $db_row)
			{
				$this->db_form_data['team_other_'.$rownum] = $db_row->toArray();
				$this->db_form_data['team_other_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['team_other'] = "Form004TeamOther";
			
			
			// TEAM DISTRICT - 1-5 ROWS
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('sortnum ASC');
			$teamDistrict 	= $this->db_form->findDependentRowset('Form004TeamDistrict', 'Form004', $select);
			$this->db_form_data['team_district']['count'] = count($teamDistrict);
			$this->db_form_data['team_district']['subformTitle'] = 'Districts, as determined by the parent';
			$rownum = 1;
			foreach($teamDistrict as $db_row)
			{
				$this->db_form_data['team_district_'.$rownum] = $db_row->toArray();
				$this->db_form_data['team_district_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['team_district'] = "Form004TeamDistrict";
		} 
		
		if('all' == $page || 4 == $page)
		{
			
			
			// IEP GOALS
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords 	= $this->db_form->findDependentRowset('Form004Goal', 'Form004', $select);
			$this->db_form_data['iep_form_004_goal']['count'] = count($subformRecords);
			$this->db_form_data['iep_form_004_goal']['subformTitle'] = 'Goals';
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				// add the db data for this subform to the main form data array
				$this->db_form_data['iep_form_004_goal_'.$rownum] = $db_row->toArray();
				// add a row number based on sort position - just used for user display
				$this->db_form_data['iep_form_004_goal_'.$rownum]['rownumber'] = $rownum;
				
				// convert fields stored in the db as return delimited lists into arrays
				// fields updated here should be added to the storeasarray config in the zend form definition 
				//
				$this->db_form_data['iep_form_004_goal_'.$rownum]['eval_procedure'] = $this->storeFieldAsArray($this->db_form_data['iep_form_004_goal_'.$rownum]['eval_procedure']);
				$this->db_form_data['iep_form_004_goal_'.$rownum]['person_responsible'] = $this->storeFieldAsArray($this->db_form_data['iep_form_004_goal_'.$rownum]['person_responsible']);
				
				$rownum++;
			}
			$this->subformIndexToModel['iep_form_004_goal'] = "Form004Goal";
		}
		
		if('all' == $page || 6 == $page)
		{
			// ==========================================================================================
			// RELATED SERVICES
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Form004RelatedService', 'Form004', $select);

			$this->db_form_data['related_services']['override'] = $this->db_form_data['override_related'];
			$this->db_form_data['related_services']['count'] = count($subformRecords);
			$this->db_form_data['related_services']['subformTitle'] = 'Related Services';
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['related_services_'.$rownum] = $db_row->toArray();
				$this->db_form_data['related_services_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['related_services'] = "Form004RelatedService";
			
			
			// ==========================================================================================
			// SUPPLEMENTAL SERVICES
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Form004SupplementalService', 'Form004', $select);

			$this->db_form_data['supp_services']['override'] = $this->db_form_data['override_supp'];
			$this->db_form_data['supp_services']['count'] = count($subformRecords);
			$this->db_form_data['supp_services']['subformTitle'] = 'Supplemental Services';
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['supp_services_'.$rownum] = $db_row->toArray();
				$this->db_form_data['supp_services_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['supp_services'] = "Form004SupplementalService";
			
			
			// ==========================================================================================
			// PROGRAM MODIFICATIONS
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Form004ProgramModifications', 'Form004', $select);

			$this->db_form_data['program_modifications']['override'] = $this->db_form_data['override_prog_mod'];
			$this->db_form_data['program_modifications']['count'] = count($subformRecords);
			$this->db_form_data['program_modifications']['subformTitle'] = 'Program Modifications';
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['program_modifications_'.$rownum] = $db_row->toArray();
				$this->db_form_data['program_modifications_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['program_modifications'] = "Form004ProgramModifications";
			
			
			// ==========================================================================================
			// ASSISTIVE TECHNOLOGY
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Form004AssistiveTechnology', 'Form004', $select);

			$this->db_form_data['assist_tech']['override'] = $this->db_form_data['override_prog_mod'];
			$this->db_form_data['assist_tech']['count'] = count($subformRecords);
			$this->db_form_data['assist_tech']['subformTitle'] = 'Assistive Technology';
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['assist_tech_'.$rownum] = $db_row->toArray();
				$this->db_form_data['assist_tech_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['assist_tech'] = "Form004AssistiveTechnology";
			

			// ==========================================================================================
			// SCHOOL_SUPPORT
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Form004SchoolSupport', 'Form004', $select);

			$this->db_form_data['school_supp']['override'] = $this->db_form_data['override_school_supp'];
			$this->db_form_data['school_supp']['count'] = count($subformRecords);
			$this->db_form_data['school_supp']['subformTitle'] = 'Supports for School Personnel';
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['school_supp_'.$rownum] = $db_row->toArray();
				$this->db_form_data['school_supp_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['school_supp'] = "Form004SchoolSupport";
			
		}
		
		if('all' == $page || 8 == $page)
		{
			// ==========================================================================================
			// SUPPLEMENTAL FORMS
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Form004SupplementalForm', 'Form004', $select);

			$this->db_form_data['iep_form_004_suppform']['override'] = $this->db_form_data['override_iep_form_004_suppform'];
			$this->db_form_data['iep_form_004_suppform']['count'] = count($subformRecords);
			$this->db_form_data['iep_form_004_suppform']['subformTitle'] = 'Supports for School Personnel';
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['iep_form_004_suppform_'.$rownum] = $db_row->toArray();
				$this->db_form_data['iep_form_004_suppform_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['iep_form_004_suppform'] = "Form004SupplementalForm";
			
		}
		return $this->db_form_data;
	}	
	function buildTeamMembers($accessMode, $page, $version)
	{
		// subform header row
		$zendSubForm = $this->buildZendSubform('Form_Form004', 'subform_header', $accessMode, $version, 'team_member');
        $zendSubForm->setIsArray(true);
        $zendSubForm->setElementsBelongTo('team_member');
        $this->form->addSubForm($zendSubForm, 'team_member');
		
		// subform rows
        for($rownum = 1; $rownum <= $this->db_form_data['team_member']['count']; $rownum++)
		{
			$zendSubForm = $this->buildZendSubform('Form_Form004', 'team_member', $accessMode, $version);
	        $zendSubForm->setIsArray(true);
	        $zendSubForm->setElementsBelongTo('team_member_' . $rownum);
	        $this->form->addSubForm($zendSubForm, 'team_member_' . $rownum);
		}
		
	}
	function buildTeamOther($accessMode, $page, $version)
	{
		// subform header row
		$zendSubForm = $this->buildZendSubform('Form_Form004', 'subform_header', $accessMode, $version, 'team_other');
        $zendSubForm->setIsArray(true);
        $zendSubForm->setElementsBelongTo('team_other');
        $this->form->addSubForm($zendSubForm, 'team_other');

		// subform rows
        for($rownum = 1; $rownum <= $this->db_form_data['team_other']['count']; $rownum++)
		{
			$zendSubForm = $this->buildZendSubform('Form_Form004', 'team_other', $accessMode, $version);
	        $zendSubForm->setIsArray(true);
	        $zendSubForm->setElementsBelongTo('team_other_' . $rownum);
	        $this->form->addSubForm($zendSubForm, 'team_other_' . $rownum);
		}
	}
	function buildTeamDistrict($accessMode, $page, $version)
	{
		// subform header row
		$zendSubForm = $this->buildZendSubform('Form_Form004', 'subform_header', $accessMode, $version, 'team_district');
        $zendSubForm->setIsArray(true);
        $zendSubForm->setElementsBelongTo('team_district');
        $this->form->addSubForm($zendSubForm, 'team_district');

		// subform rows
        for($rownum = 1; $rownum <= $this->db_form_data['team_district']['count']; $rownum++)
		{
			$zendSubForm = $this->buildZendSubform('Form_Form004', 'team_district', $accessMode, $version);
	        $zendSubForm->setIsArray(true);
	        $zendSubForm->setElementsBelongTo('team_district_' . $rownum);
	        $this->form->addSubForm($zendSubForm, 'team_district_' . $rownum);
		}
		
	}
	function buildGoals($accessMode, $page, $version)
	{
		// subform header row
		$zendSubForm = $this->buildZendSubform('Form_Form004', 'subform_header', $accessMode, $version, 'iep_form_004_goal');
        $zendSubForm->setIsArray(true);
        $zendSubForm->setElementsBelongTo('iep_form_004_goal');
        $this->form->addSubForm($zendSubForm, 'iep_form_004_goal');

		// subform rows
        for($rownum = 1; $rownum <= $this->db_form_data['iep_form_004_goal']['count']; $rownum++)
		{
			$zendSubForm = $this->buildZendSubform('Form_Form004', 'iep_form_004_goal', $accessMode, $version);
	        $zendSubForm->setIsArray(true);
	        $zendSubForm->setElementsBelongTo('iep_form_004_goal_' . $rownum);
	        $this->form->addSubForm($zendSubForm, 'iep_form_004_goal_' . $rownum);
		}
		
	}
	function buildRelatedService($accessMode, $page, $version)
	{
		$overrideValidation = true;
		// subform header row
		$zendSubForm = $this->buildZendSubform('Form_Form004', 'subform_header', $accessMode, $version, 'related_services', $overrideValidation);
        $zendSubForm->setIsArray(true);
        $zendSubForm->setElementsBelongTo('related_services');
        $this->form->addSubForm($zendSubForm, 'related_services');

		// subform rows
        for($rownum = 1; $rownum <= $this->db_form_data['related_services']['count']; $rownum++)
		{
			$zendSubForm = $this->buildZendSubform('Form_Form004', 'related_services', $accessMode, $version);
	        $zendSubForm->setIsArray(true);
	        $zendSubForm->setElementsBelongTo('related_services_' . $rownum);
	        
	        // related service location
	        $zendSubForm->related_service_location->setMultiOptions($this->locationValueList($this->studentData['dob'], $this->db_form_data['date_conference']));
	        
	        // related service location
	        $zendSubForm->related_service_drop->setMultiOptions($this->specialEducationValueList());
	        
	        // override validation
	        $this->subformValidationOverride('related_services');
	        $this->form->addSubForm($zendSubForm, 'related_services_' . $rownum);
		}
		
	}
	function buildSupplementalService($accessMode, $page, $version)
	{
		$overrideValidation = true;
		// subform header row
		$zendSubForm = $this->buildZendSubform('Form_Form004', 'subform_header', $accessMode, $version, 'supp_services', $overrideValidation);
        $zendSubForm->setIsArray(true);
        $zendSubForm->setElementsBelongTo('supp_services');
        $this->form->addSubForm($zendSubForm, 'supp_services');

		// subform rows
        for($rownum = 1; $rownum <= $this->db_form_data['supp_services']['count']; $rownum++)
		{
			$zendSubForm = $this->buildZendSubform('Form_Form004', 'supp_services', $accessMode, $version);
	        $zendSubForm->setIsArray(true);
	        $zendSubForm->setElementsBelongTo('supp_services_' . $rownum);
	        
	        // supp service location
	        $zendSubForm->supp_service_location->setMultiOptions($this->locationValueList($this->studentData['dob'], $this->db_form_data['date_conference']));
	        
	        // related service location
//	        $zendSubForm->related_service_drop->setMultiOptions($this->specialEducationValueList());
	        
	        // override validation
	        $this->subformValidationOverride('supp_services');
	        $this->form->addSubForm($zendSubForm, 'supp_services_' . $rownum);
		}
		
	}
	function buildProgramModifications($accessMode, $page, $version)
	{
		$overrideValidation = true;
		// subform header row
		$zendSubForm = $this->buildZendSubform('Form_Form004', 'subform_header', $accessMode, $version, 'program_modifications', $overrideValidation);
        $zendSubForm->setIsArray(true);
        $zendSubForm->setElementsBelongTo('program_modifications');
        $this->form->addSubForm($zendSubForm, 'program_modifications');

		// subform rows
        for($rownum = 1; $rownum <= $this->db_form_data['program_modifications']['count']; $rownum++)
		{
			$zendSubForm = $this->buildZendSubform('Form_Form004', 'program_modifications', $accessMode, $version);
	        $zendSubForm->setIsArray(true);
	        $zendSubForm->setElementsBelongTo('program_modifications_' . $rownum);
	        
	        // prog mod location
	        $zendSubForm->prog_mod_location->setMultiOptions($this->locationValueList($this->studentData['dob'], $this->db_form_data['date_conference']));
	        
	        // override validation
	        $this->subformValidationOverride('program_modifications');
	        $this->form->addSubForm($zendSubForm, 'program_modifications_' . $rownum);
		}
		
	}
	function buildAssistiveTechnology($accessMode, $page, $version)
	{
		$overrideValidation = true;
		// subform header row
		$zendSubForm = $this->buildZendSubform('Form_Form004', 'subform_header', $accessMode, $version, 'assist_tech', $overrideValidation);
        $zendSubForm->setIsArray(true);
        $zendSubForm->setElementsBelongTo('assist_tech');
        $this->form->addSubForm($zendSubForm, 'assist_tech');

		// subform rows
        for($rownum = 1; $rownum <= $this->db_form_data['assist_tech']['count']; $rownum++)
		{
			$zendSubForm = $this->buildZendSubform('Form_Form004', 'assist_tech', $accessMode, $version);
	        $zendSubForm->setIsArray(true);
	        $zendSubForm->setElementsBelongTo('assist_tech_' . $rownum);
	        
	        // prog mod location
	        $zendSubForm->assist_tech_location->setMultiOptions($this->locationValueList($this->studentData['dob'], $this->db_form_data['date_conference']));
	        
	        // override validation
	        $this->subformValidationOverride('assist_tech');
	        $this->form->addSubForm($zendSubForm, 'assist_tech_' . $rownum);
		}
		
	}
	function buildSchoolSupports($accessMode, $page, $version)
	{
		$overrideValidation = true;
		// subform header row
		$zendSubForm = $this->buildZendSubform('Form_Form004', 'subform_header', $accessMode, $version, 'school_supp', $overrideValidation);
        $zendSubForm->setIsArray(true);
        $zendSubForm->setElementsBelongTo('school_supp');
        $this->form->addSubForm($zendSubForm, 'school_supp');

		// subform rows
        for($rownum = 1; $rownum <= $this->db_form_data['school_supp']['count']; $rownum++)
		{
			$zendSubForm = $this->buildZendSubform('Form_Form004', 'school_supp', $accessMode, $version);
	        $zendSubForm->setIsArray(true);
	        $zendSubForm->setElementsBelongTo('school_supp_' . $rownum);
	        
	        // prog mod location
	        $zendSubForm->school_supp_location->setMultiOptions($this->locationValueList($this->studentData['dob'], $this->db_form_data['date_conference']));
	        
	        // override validation
	        $this->subformValidationOverride('school_supp');
	        $this->form->addSubForm($zendSubForm, 'school_supp_' . $rownum);
		}
		
	}
	function buildSupplementalForms($accessMode, $page, $version)
	{
		$overrideValidation = true;
		// subform header row
		$zendSubForm = $this->buildZendSubform('Form_Form004', 'subform_header', $accessMode, $version, 'iep_form_004_suppform', $overrideValidation);
		$zendSubForm->setIsArray(true);
		$zendSubForm->setElementsBelongTo('iep_form_004_suppform');
		$this->form->addSubForm($zendSubForm, 'iep_form_004_suppform');
		
		// subform rows
		for($rownum = 1; $rownum <= $this->db_form_data['iep_form_004_suppform']['count']; $rownum++)
		{
			$zendSubForm = $this->buildZendSubform('Form_Form004', 'iep_form_004_suppform', $accessMode, $version);
			$zendSubForm->setIsArray(true);
			$zendSubForm->setElementsBelongTo('iep_form_004_suppform_' . $rownum);
			
			// override validation
			$this->subformValidationOverride('iep_form_004_suppform');
			$this->form->addSubForm($zendSubForm, 'iep_form_004_suppform_' . $rownum);
		}
		
	}
	function buildZendForm($accessMode = "view", $page =1, $version = 1)
	{
		parent::buildZendForm($accessMode, $page, $version);
		
		// additional actions for individual pages and 
		// build sub forms
		// ==========================================================================================
		if('all' == $page || 1 == $page)
		{
			// PAGE 1
			//
			$this->buildTeamMembers($accessMode, $page, $version);
			$this->buildTeamOther($accessMode, $page, $version);
			$this->buildTeamDistrict($accessMode, $page, $version);
		} 
		elseif('all' == $page || 4 == $page)
		{
			$this->buildGoals($accessMode, $page, $version);
			
		}
		elseif('all' == $page || 6 == $page)
		{
			// primary service location
			$this->form->primary_service_location->setMultiOptions($this->locationValueList($this->studentData['dob'], $this->db_form_data['date_conference']));
			// primary service drop
			$this->form->primary_disability_drop->setMultiOptions($this->specialEducationValueList());
			
			
			$this->buildRelatedService($accessMode, $page, $version);
			$this->buildSupplementalService($accessMode, $page, $version);
			$this->buildProgramModifications($accessMode, $page, $version);
			$this->buildAssistiveTechnology($accessMode, $page, $version);
			$this->buildSchoolSupports($accessMode, $page, $version);
		}
		elseif('all' == $page || 8 == $page)
		{
			$this->buildSupplementalForms($accessMode, $page, $version);
		}
		return $this->form;
	}
	
    function validateBasic($data = null)
    {
    	if(null == $data) $data = $this->db_form_data;
//		echo "<pre>";
//		print_r($data);
    	// validate the form with db data
    	if(count($this->subformIndexToModel) > 0)
    	{
	    	foreach($this->subformIndexToModel as $sfIndex => $modelName)
	    	{
	    		// override validation on all sub rows
	    		$this->subformValidationOverride($sfIndex);
	    	}
    	}
//    	echo $data['iep_form_004_goal_1']['person_responsible'] . "\n";
//    	echo $data['iep_form_004_goal_1']['eval_procedure'] . "\n";
    	
    	parent::validateBasic($data);
    }
}
