<?php

/**
 * Form004 model
 *
 */
class Model_Form004 extends Model_AbstractForm
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

	function writevar1($var1,$var2) {

	    ob_start();
	    var_dump($var1);
	    $data = ob_get_clean();
	    $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
	    $fp = fopen("/tmp/textfile.txt", "a");
	    fwrite($fp, $data2);
	    fclose($fp);
	}

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
	    //   $this->writevar1($e->getMessage(),'this is the error message');
	       // $this->view->scott=$e->getMessage();
	        $t[0]['message']=$e->getMessage();


	        return $t;
	    }

//		} catch (App_Exception_NoAccess $e) {
//			Zend_Debug::dump('gorilla');
//			return $e;
//		}




        // transition plan -- force true when student is over 15 years 1 day
        /*
        if(true == $this->db_form_data['student_data']['force_tran_plan'])
        {
            $this->db_form_data['transition_plan'] = 't';
        }
        */

        // location page 6 - clear if date_conference is empty
        if(empty($this->db_form_data['date_conference']))
        {
            $this->db_form_data['primary_service_location'] = '';
        }

        $translate = Zend_Registry::get('Zend_Translate');

        // build sub forms
		if('all' == $page || 1 == $page)
		{
			// PAGE 1
			//
			// TEAM MEMBERS - 10 ROWS AT TOP OF PAGE 1
			$modelName		= 'Model_Table_Form004TeamMember';
			$select 		= $this->db_form->select()->order('sortnum ASC');
			$teamMember 	= $this->db_form->findDependentRowset($modelName, 'Model_Table_Form004', $select);
			$this->db_form_data['team_member']['count'] = count($teamMember);
			$this->db_form_data['team_member']['subformTitle'] = $translate->_('The Following Participants Were In Attendance At The IEP Meeting');
			$rownum = 1;
			foreach($teamMember as $db_row)
			{
				$this->db_form_data['team_member_'.$rownum] = $db_row->toArray();
				$this->db_form_data['team_member_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['team_member'] = "Model_Table_Form004TeamMember";


			// TEAM OTHER - 1-5 ROWS
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created');
			$teamOther 		= $this->db_form->findDependentRowset('Model_Table_Form004TeamOther', 'Model_Table_Form004', $select);
			$this->db_form_data['team_other']['count'] = count($teamOther);
			$this->db_form_data['team_other']['subformTitle'] = '<span class="printBoldLabeWithBorderWidthLandscape">'.$translate->_('Others as determined by the parent').'</span>';
			$rownum = 1;
			foreach($teamOther as $db_row)
			{
				$this->db_form_data['team_other_'.$rownum] = $db_row->toArray();
				$this->db_form_data['team_other_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['team_other'] = "Model_Table_Form004TeamOther";


			// TEAM DISTRICT - 1-5 ROWS
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created');
			$teamDistrict 	= $this->db_form->findDependentRowset('Model_Table_Form004TeamDistrict', 'Model_Table_Form004', $select);
			$this->db_form_data['team_district']['count'] = count($teamDistrict);
			$this->db_form_data['team_district']['subformTitle'] = '<span class="printBoldLabeWithBorderWidthLandscape">'.$translate->_('Others as determined by the district').'</span>';
			$rownum = 1;
			foreach($teamDistrict as $db_row)
			{
				$this->db_form_data['team_district_'.$rownum] = $db_row->toArray();
				$this->db_form_data['team_district_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['team_district'] = "Model_Table_Form004TeamDistrict";
		}

		if('all' == $page || 4 == $page)
		{


			// IEP GOALS
			$select 		= $this->db_form->select()->where("status = 'Draft'")->order(array('timestamp_created ASC', 'id_form_004_goal ASC'));
			$subformRecords 	= $this->db_form->findDependentRowset('Model_Table_Form004Goal', 'Model_Table_Form004', $select);
			$this->db_form_data['iep_form_004_goal']['count'] = count($subformRecords);
			$this->db_form_data['iep_form_004_goal']['subformTitle'] = '<span class="printBoldLabeWithBorderWidthLandscape">'.$translate->_('Goals').'</span>';
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
			$this->subformIndexToModel['iep_form_004_goal'] = "Model_Table_Form004Goal";


	        // get the school report dates for this student
			if(!empty($this->db_form_data['student_data']['date_conference'])) {
				$yearIdentifier = date('Y', strtotime($this->db_form_data['student_data']['date_conference']));
			} else {
				$yearIdentifier = date('Y');
			}
	        $studentObj = new Model_Table_ViewAllStudent();
	        $student = $studentObj->find($this->db_form['id_student'])->current();
			$select 		= $student->select()
								->where("year_identifier = '".$yearIdentifier."' OR year_identifier = '".($yearIdentifier-1)."' OR year_identifier = '".($yearIdentifier+1)."'")
								->order('year_identifier ASC');
			$schRptDateRows = $student->findDependentRowset('Model_Table_SchoolReportDates', 'Model_Table_ViewAllStudent', $select);

//			$sessUser = new Zend_Session_Namespace('user');
//
//			if(1000254 == $sessUser->id_personnel) {
//				echo $select;
//				Zend_Debug::dump($schRptDateRows->toArray());
//			}
			// collect and sort dates
			$reportDates = array();
			foreach ($schRptDateRows as $row) {
				for($i=1; $i<=6; $i++) {
					if('' != $row['date_report'.$i]) {
						$d = new Zend_Date($row['date_report'.$i], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
						if(-1 != $d->compareTimestamp(new Zend_Date())) {
							$reportDates[] = $row['date_report'.$i];
						}

					}
				}
			}
			asort($reportDates);
			$reportDates = array_values(array_unique($reportDates)); // reindex

			if(count($schRptDateRows) >0 ) {
				$this->db_form_data['student_data']['schoolReportDates'] = $reportDates;
			} else {
				$this->db_form_data['student_data']['schoolReportDates'] = array();
			}

		}

		if('all' == $page || 5 == $page)
		{
			/*
			 * hack to auto set transition date
			 */
		    $birthdate = new DateTime($this->db_form_data['student_data']['dob']); //birthdate
		    $effectToDate = new DateTime($this->db_form_data['effect_to_date']); //ending effect date
		    $interval = $birthdate->diff($effectToDate);
		    if($interval->y >= 16) {
		        // force if 16 or over
		        $this->db_form_data['transition_plan'] = true;
		    }


			// IEP GOALS
			$select 		= $this->db_form->select()->where("lower(status) != 'deleted' or status is null")->order('timestamp_created ASC');
			$subformRecords = $this->db_form->findDependentRowset('Model_Table_Form004SecondaryGoal', 'Model_Table_Form004', $select);
			$this->db_form_data['iep_form_004_secondary_goal']['count'] = count($subformRecords);
			$this->db_form_data['iep_form_004_secondary_goal']['subformTitle'] = $translate->_('Post Secondary Goals');
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				// add the db data for this subform to the main form data array
				$this->db_form_data['iep_form_004_secondary_goal_'.$rownum] = $db_row->toArray();
				// add a row number based on sort position - just used for user display
				$this->db_form_data['iep_form_004_secondary_goal_'.$rownum]['rownumber'] = $rownum;
				$this->db_form_data['iep_form_004_secondary_goal_'.$rownum]['transition_plan_sub'] = $this->db_form_data['transition_plan'];
				$this->db_form_data['iep_form_004_secondary_goal_'.$rownum]['dob_sub'] = $this->db_form_data['dob'];

				$rownum++;
			}
			$this->subformIndexToModel['iep_form_004_secondary_goal'] = "Model_Table_Form004SecondaryGoal";
		}

		if('all' == $page || 6 == $page)
		{
            if('print' != $accessMode)
            {
                $this->accChecklist();
            }
						// ==========================================================================================
			// RELATED SERVICES
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Model_Table_Form004RelatedService', 'Model_Table_Form004', $select);

			$this->db_form_data['related_services']['override'] = $this->db_form_data['override_related'];
			$this->db_form_data['related_services']['count'] = count($subformRecords);
			$this->db_form_data['related_services']['subformTitle'] = $translate->_('Special Education Related Services');
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['related_services_'.$rownum] = $db_row->toArray();
				$this->db_form_data['related_services_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['related_services'] = "Model_Table_Form004RelatedService";


			// ==========================================================================================
			// SUPPLEMENTAL SERVICES
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Model_Table_Form004SupplementalService', 'Model_Table_Form004', $select);

			$this->db_form_data['supp_services']['override'] = $this->db_form_data['override_supp'];
			$this->db_form_data['supp_services']['count'] = count($subformRecords);
			$this->db_form_data['supp_services']['subformTitle'] = $translate->_('Supplementary Aids Services');
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['supp_services_'.$rownum] = $db_row->toArray();
				$this->db_form_data['supp_services_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['supp_services'] = "Model_Table_Form004SupplementalService";


			// ==========================================================================================
			// PROGRAM MODIFICATIONS
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Model_Table_Form004ProgramModifications', 'Model_Table_Form004', $select);

			$this->db_form_data['program_modifications']['override'] = $this->db_form_data['override_prog_mod'];
			$this->db_form_data['program_modifications']['count'] = count($subformRecords);
			$this->db_form_data['program_modifications']['subformTitle'] = $translate->_('Program Modifications Accommodations');
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['program_modifications_'.$rownum] = $db_row->toArray();
				$this->db_form_data['program_modifications_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['program_modifications'] = "Model_Table_Form004ProgramModifications";


			// ==========================================================================================
			// ASSISTIVE TECHNOLOGY
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Model_Table_Form004AssistiveTechnology', 'Model_Table_Form004', $select);

			$this->db_form_data['assist_tech']['override'] = $this->db_form_data['override_ass_tech'];
			$this->db_form_data['assist_tech']['count'] = count($subformRecords);
			$this->db_form_data['assist_tech']['subformTitle'] = $translate->_('Assistive Technology Devices or Services');
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['assist_tech_'.$rownum] = $db_row->toArray();
				$this->db_form_data['assist_tech_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['assist_tech'] = "Model_Table_Form004AssistiveTechnology";


			// ==========================================================================================
			// SCHOOL_SUPPORT
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Model_Table_Form004SchoolSupport', 'Model_Table_Form004', $select);

			$this->db_form_data['school_supp']['override'] = $this->db_form_data['override_school_supp'];
			$this->db_form_data['school_supp']['count'] = count($subformRecords);
			$this->db_form_data['school_supp']['subformTitle'] = $translate->_('Supports for School Personnel');
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['school_supp_'.$rownum] = $db_row->toArray();
				$this->db_form_data['school_supp_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['school_supp'] = "Model_Table_Form004SchoolSupport";

		}

		if('all' == $page || 7 == $page)
		{
            // convert fields stored in the db as return delimited lists into arrays
            // fields updated here should be added to the storeasarray config in the zend form definition
            //
            $this->db_form_data['district_assessments'] = $this->storeFieldAsArray($this->db_form_data['district_assessments']);

        }

		if('all' == $page || 8 == $page)
		{
            if('print' == $accessMode)
			{
                $this->accChecklist();
			}
			// ==========================================================================================
			// SUPPLEMENTAL FORMS
			$select 		= $this->db_form->select()->where("status = 'Active'")->order('timestamp_created ASC');
			$subformRecords	= $this->db_form->findDependentRowset('Model_Table_Form004SupplementalForm', 'Model_Table_Form004', $select);

			$this->db_form_data['iep_form_004_suppform']['count'] = count($subformRecords);
			$this->db_form_data['iep_form_004_suppform']['subformTitle'] = $translate->_('Supplemental Forms');
			$rownum = 1;
			foreach($subformRecords as $db_row)
			{
				$this->db_form_data['iep_form_004_suppform_'.$rownum] = $db_row->toArray();
				$this->db_form_data['iep_form_004_suppform_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['iep_form_004_suppform'] = "Model_Table_Form004SupplementalForm";

		}
		$this->writevar1($this->db_form_data,'this is the db form data');
		return $this->db_form_data;
	}

    private function accChecklist()
    {
        $translate = Zend_Registry::get('Zend_Translate');
    	// function also maintains 1 acc checklist per form
    	// adds checklist if there is not one
    	// removes additional checklists
		// check district to see if option is available
		$districtObj = new Model_Table_District();
		$district = $districtObj->getDistrict($this->db_form_data['id_county'], $this->db_form_data['id_district']);

        // if district uses this feature and there is NOT only one list
        if($district['use_accomodations_checklist']) {
	        $select         = $this->db_form->select()->where("status = 'Active'")->order('id_accom_checklist ASC');
	        $subformRecords = $this->db_form->findDependentRowset('Model_Table_Form004AccomodationsChecklist', 'Model_Table_Form004', $select);

            $form004AccommodationCheckistObj = new Model_Table_Form004AccomodationsChecklist();
            // if there is no list, add one
            if($subformRecords->count() == 0) {
                $data = array(
                    'id_form_004'       => $this->db_form_data['id_form_004'],
                );
                $form004AccommodationCheckistObj->insert($data);
		        $subformRecords = $this->db_form->findDependentRowset('Model_Table_Form004AccomodationsChecklist', 'Model_Table_Form004', $select);
            }

            // if there are more than two, get checklists
            $firstList = true;
            foreach($subformRecords as $db_row) {
                if($firstList) {
                    $this->db_form_data['accomodations_checklist']['count'] = 1;
                    $this->db_form_data['accomodations_checklist']['subformTitle'] = $translate->_('Accommodations Checklist');
                    $this->db_form_data['accomodations_checklist_1'] = $db_row->toArray();
                    $this->db_form_data['accomodations_checklist_1']['rownumber'] = 1;
                    $this->subformIndexToModel['accomodations_checklist'] = "Model_Table_Form004AccomodationsChecklist";
                    $firstList = false;
                    continue;
                }
                // delete additional checklists
                $db_row->delete('id_accom_checklist', $db_row['id_accom_checklist']);
            }


            /**
             * if iep is v11 or greater
             * convert comma separated fields to arrays
             */
            if(11 <= $this->db_form_data['version_number']) {
                $this->db_form_data['accomodations_checklist_1'] = $form004AccommodationCheckistObj->convertToArrayNotation($this->db_form_data['accomodations_checklist_1']);
            }

        }


    }
}
