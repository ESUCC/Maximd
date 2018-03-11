<?php

/**
 * Form029 model
 *
 */
class Model_Form029 extends Model_AbstractForm
{

	protected $_subformTypes = array(
			'OtherAttendee' => 'other_attendee',
	        'GenEdTeacher' => 'gen_ed_teacher',
    	    'SpecialEdTeacher' => 'special_ed_teacher',
    	    'SchoolRepresentative' => 'school_representative',
    	    'EvalResults' => 'eval_results',
    	    'SpecialKnowledge' => 'special_knowledge'
	);

	protected $_subformTitles = array(
			'OtherAttendee' => 'Other Attendee:',
	        'GenEdTeacher'  => 'A general education teacher of your child:',
    	    'SpecialEdTeacher' => 'A special education teacher:',
    	    'SchoolRepresentative' => 'A school representative:',
    	    'EvalResults' => 'Individuals who can help explain the evaluation results:',
    	    'SpecialKnowledge' => 'Individuals who have special knowledge or expertise regarding your child or services that may be needed:'
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
	   // $this->writevar1($id,'this is the id line 44 form029');

		if(false === parent::buildDbForm($id, $accessMode, $page, $versionNumber, $checkout))
		{
			//return false;
		}

		// build sub forms
		if('all' == $page || 1 == $page)
		{
			foreach ($this->_subformTypes AS $key => $value) {
				$modelName		= 'Model_Table_Form029'.$key;
				$select 		= $this->db_form->select()->where("status != 'Deleted'")->order('timestamp_created ASC');
				$subformResults = $this->db_form->findDependentRowset($modelName, 'Model_Table_Form029', $select);
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
				$this->subformIndexToModel[$value] = "Model_Table_Form029".$key;
			}
		}

		// build sub forms
		if('all' == $page || 2 == $page)
		{
			// TEAM MEMBER ABSENCES
			$select 		= $this->db_form->select()->where("(status is null OR status != 'Deleted')")->order('timestamp_created ASC');
			$subformRecords 	= $this->db_form->findDependentRowset('Model_Table_Form029TeamMemberAbsences', 'Model_Table_Form029', $select);

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
				$teamInput 	= $db_row->findDependentRowset('Model_Table_Form029TeamMemberInput', 'Model_Table_Form029TeamMemberAbsences', $select);
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
				//				$this->subformIndexToModel['team_member_input'] = "Form029TeamMemberInput";

				$rownum++;
			}
			$this->subformIndexToModel['team_member_absences'] = "Model_Table_Form029TeamMemberAbsences";


		}

		if('all' == $page || 3 == $page)
		{
			// TEAM MEMBER ABSENCES
			$select 		= $this->db_form->select()->where("(status is null OR status != 'Deleted')")->order('timestamp_created ASC');
			$subformRecords 	= $this->db_form->findDependentRowset('Model_Table_Form029OutsideAgency', 'Model_Table_Form029', $select);
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
			$this->subformIndexToModel['outside_agency'] = "Model_Table_Form029OutsideAgency";


		}

		if('all' == $page || 4 == $page)
		{
			$modelName		= 'Model_Table_Form029ContactAttempts';
			$select 		= $this->db_form->select()->where("status != 'Deleted'")->order('timestamp_created ASC');
			$transcriptResults = $this->db_form->findDependentRowset($modelName, 'Model_Table_Form029', $select);
			$this->db_form_data['contact_attempts']['count'] = count($transcriptResults);
			$this->db_form_data['contact_attempts']['subformTitle'] = '<span>Contact Attempts</span>';
			$rownum = 1;
			//			Zend_Debug::dump($teamMember);
			foreach($transcriptResults as $db_row)
			{
				$this->db_form_data['contact_attempts_'.$rownum] = $db_row->toArray();
				$this->db_form_data['contact_attempts_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['contact_attempts'] = "Model_Table_Form029ContactAttempts";
		}
		// build sub forms

//		echo "<PRE>";
//		print_r($this->db_form_data);
//		echo $select;
		return $this->db_form_data;
	}
}
