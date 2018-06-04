<?php

class Form004Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 11;
	protected $startPage = 1;
	protected $pdfFieldNames = array('pdf_filepath_present_lev_perf');
	protected $displayFteReport = false;
	protected $fteMostRecentSave = null;

	protected $archiveDependencies = array(
		'iep_team_member',
		'iep_team_other',
		'iep_team_district',
		'form_004_supp_service',
		'iep_form_004_supplemental_form',
		'iep_form_004_secondary_goal',
		'form_004_school_supp',
		'form_004_related_service',
		'form_004_prog_mods',
		'iep_form_004_goal',
		'iep_form_004_goal_progress', // keep below iep_form_004_goal as it's dependent on it
		'form_004_assist_tech',
		'iep_accom_checklist',
	);

	public function init() {

        $this->_redirector = $this->_helper->getHelper('Redirector');

        // form parameters
        $this->view->pageCount = 8;
        parent::setPrimaryKeyName('id_form_004');
        parent::setFormNumber('004');
        parent::setModelName('Model_Form004');
		parent::setFormClass('Form_Form004');
        parent::setFormRev('08/08');
        parent::setFormTitle('Individualized Education Plan');
    }


	function dupe() {
		$modelName = "Model_Table_Form" . $this->getFormNumber ();
		$formObj = new $modelName ();


		if ('full' == $this->getRequest ()->getParam ( 'dupe_type' )) {
			// in Model_Table_Form004
			$newId = $formObj->dupeFull ( $this->getRequest ()->document );
		} else {
            // javascript:%20goToURLZend('http://iepweb02.unl.edu',%20'student',%20'form004/dupe',%20'document',%201392135,%20'/page//option/dupe_form_004');
            // option/dupe_form_004
			$newId = $formObj->dupe ( $this->getRequest ()->document );
		}

		$iepObj = new Model_Table_Form004();
		$oldIep = $iepObj->find($this->getRequest ()->document)->current();

		// convert \n to <BR> when duping from old forms
		if($oldIep['version_number'] < 9) {
			$iep = $iepObj->find($newId)->current();

			$this->view->db_form_data = $this->buildModel ( $newId, $this->view->mode );

			// render all the form pages into an array in the new view
			// they are then ouput on print_paper.phtml
			$config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->version, 'lps' => $this->view->lps );
			$pagesArr = array ();
			$this->formArr = array ();

			$tempFormPages = $this->buildZendForm ( $this->getFormClass (), $this->view->db_form_data, $this->view->version, $config );
			for($i = 1; $i <= $this->view->pageCount; $i ++) {
				// not the most efficient way to do this, but right now
				// we need the form in the view in order to be rendered by the viewscript
				$this->view->form = $tempFormPages [$i];
				$iep = $this->convertReturnsInEditors ( $this->view->form, $iep);

				// convert subform model fields
				$x = 1;
				// setSubFormsForDuping built and set in buildZendForm()
	            foreach($this->getSubFormsForDuping($i) as $tableConfig) {
	            	$tempModelObj = new $tableConfig['model'] ();
	            	try {
		            	$modelRows = $tempModelObj->getWhere('id_form_004', $iep->id_form_004, 'timestamp_created');
		            	foreach($modelRows as $row) {
		            		$row = $this->convertReturnsInEditors ( $this->view->form->getSubform($tableConfig['subformIndex'].'_'.$x), $row);
		            		$row->save();
		            	}
	            	} catch (Exception $e) {
	            	}
	            	unset($tempModelObj);
	            	$x++;
	            }
			}
			$iep->save();
		}
		return $newId;
	}
	function repairEditorsAction() {
		$sessUser = new Zend_Session_Namespace ( 'user' );
		if (1000254 == $sessUser->sessIdUser || 1010818 == $sessUser->sessIdUser) {
			// allow form to be repaired
		} else {
			throw new Exception ( 'You do not have permission to repair a form.' );
			return;
		}

		$id = $this->getRequest ()->document;

		$iepObj = new Model_Table_Form004();
		$iep = $iepObj->find($id)->current();
		$iep = $this->dupeRepair($iep);
		$this->_redirector->gotoSimple ( 'edit', 'form' . $this->getFormNumber (), null, array ('document' => $id, 'page' => 1 ) );
	}
	function dupeRepair($iep) {
		$filter = new App_Filter_TestWordFilter();

		$config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->version, 'lps' => $this->view->lps );
		$tempForm = new Form_Form004 ( $config );

		$pageNum = 1;
		// build zend form pages and run the filter on each editor
		while ( method_exists ( 'Form_Form004', $methodName = 'edit_p' . $pageNum . '_v' . $this->version ) ) {
			$formPage = $tempForm->$methodName();
			foreach ( $formPage->getElements () as $n => $e ) {
				if('App_Form_Element_TestEditor' == $e->getType ()) {
					// process editor text
					$value = $filter->filter($iep[$n]);
					$value = preg_replace('/<meta content(.*?)\/>/ism', '', $value);
					$value = preg_replace('/<!--(.*?)-->/ism', '', $value);
			       	$iep[$n] = $value;
				}
			}
			$pageNum ++;
		}
		$iep->save();
		return $iep;
	}

    public function goalhelperAction()
    {

        $student_auth = new App_Auth_StudentAuthenticator();
        $accessObj = $student_auth->validateStudentAccess($this->getRequest()->getParam('id_student'), $this->usersession);

        // build student info
        $studentObj = new Model_Table_ViewAllStudent();
        $student = $studentObj->find($this->getRequest()->getParam('id_student'))->current();
        if (null == $student) {
            throw new Zend_Db_Table_Exception("Student Not Found");
            $this->db_form_data['student_data'] = array();
        } else {
            $this->db_form_data['student_data'] = $student->toArray();
        }

        $this->db_form_data['student_data']['user_access']['access_level'] = $accessObj->access_level;
        $this->db_form_data['student_data']['user_access']['description'] = $accessObj->description;

        $this->view->db_form_data = $this->db_form_data;
        $this->view->runType = $this->getRequest()->getParam('runType');

        $this->view->domainSelect = $this->getRequest()->getParam('domainSelect');

        $this->view->objDomainSelect = $this->getRequest()->getParam('objDomainSelect');
        $this->view->topicSelect = $this->getRequest()->getParam('topicSelect');
        $this->view->subtopicSelect = $this->getRequest()->getParam('subtopicSelect');



        $this->view->standardMenu = $this->getRequest()->getParam('standardMenu');
        $this->view->goalnum = $this->getRequest()->getParam('goalnum');

        $conditionObj = new Model_Table_Form004GoalCondition();
        $this->view->conditionArrData = $conditionObj->distinctConditions()->toArray();


        $standardObj = new Model_Table_Form004GoalStandard();
        $this->view->standardArrData = $standardObj->distinctStandards()->toArray();

        $domainObj = new Model_Table_Form004GoalDomain();
        $this->view->domainArrData = $domainObj->distinctDomains()->toArray();

        if($this->getRequest()->getParam('objectiveCode'))
        {
            $domainObj = new Model_Table_Form004GoalObjective();
            $this->view->objCodeArrData = $domainObj->getObjectives($this->getRequest()->getParam('objectiveCode'))->toArray();
        } elseif(
            $this->getRequest()->getParam('objDomainSelect') != '' &&
            $this->getRequest()->getParam('topicSelect') != '' &&
            $this->getRequest()->getParam('subtopicSelect') != ''
            ) {

                $tempTopicSelect = substr($this->getRequest()->getParam('topicSelect'), 2, 2);
                $theLength  = ( strlen($this->getRequest()->getParam('objDomainSelect')) + strlen($tempTopicSelect) );

                $tempSubtopicSelect = substr($this->getRequest()->getParam('subtopicSelect'), $theLength, (strlen($this->getRequest()->getParam('subtopicSelect')) - $theLength) );

                $subObjCode = $this->getRequest()->getParam('objDomainSelect') . $tempTopicSelect . $tempSubtopicSelect;
                $subObjCodeLength = strlen($subObjCode);

                if($this->getRequest()->getParam('objDomainSelect') == 'LR') {
                    $subObjCode = $this->getRequest()->getParam('subtopicSelect');
                }
                $objectiveObj = new Model_Table_Form004GoalObjective();
                $this->view->objCodeArrData = $objectiveObj->getObjectivesBySubstring($subObjCodeLength, $subObjCode);
        }

		//==============================================================
		//=========   condition codes   ================================
		//==============================================================
        if($this->getRequest()->getParam('conditionCode'))
        {
            $this->view->conditionCodeArrData = $conditionObj->getConditions($this->getRequest()->getParam('conditionCode'))->toArray();

        } elseif($this->getRequest()->getParam('domainSelect')) {
            $this->view->conditionCodeArrData = $conditionObj->getConditionsByDomain($this->getRequest()->getParam('domainSelect'))->toArray();
        }

		//==============================================================
		//==========   standard codes   ================================
		//==============================================================
        $standardObj = new Model_Table_Form004GoalStandard();
        if($this->getRequest()->getParam('standardCode'))
        {
            $this->view->standardCodeArrData = $standardObj->getStandardsByCode($this->getRequest()->getParam('standardCode'))->toArray();

        } elseif($this->getRequest()->getParam('standardMenu')) {
            $this->view->standardCodeArrData = $standardObj->getStandardsByDomain($this->getRequest()->getParam('standardMenu'))->toArray();
        }

//		Zend_Debug::dump($this->view->standardCodeArrData);die();
        $this->view->jsArrString = "";
        $topicObj = new Model_Table_Form004GoalTopic();
        $subTopicObj = new Model_Table_Form004GoalSubTopic();

        $this->view->jsArrString .= "\nnewBarray();\n";

		for($i = 0; $i < count($this->view->domainArrData); $i++) {
	        $topicList = $topicObj->getTopics($this->view->domainArrData[$i]['domain_code'])->toArray();
			if(count($topicList) > 0) {
				$topicCount = count($topicList);
				// Create the new level 2 category (condition)
                $this->view->jsArrString .= "\nnewBarray();\n";

				for($rowNum = 0; $rowNum < $topicCount; $rowNum++) {
					$topicArrData[$rowNum] = $topicList[$rowNum]; //pg_fetch_array($topicList, ($rowNum));
					if($rowNum == 0) {
					   $this->view->jsArrString .= "  bO(\"Choose Topic\",\"\");\n";
					}
					$this->view->jsArrString .= "  bO(".json_encode(utf8_encode($topicArrData[$rowNum]['topic_description'])).",".json_encode($this->removeLeadingZeros($topicArrData[$rowNum]['topic_code'])).");\n";
					$subTopicList = $subTopicObj->getTopics($topicArrData[$rowNum]['topic_code'])->toArray();
					if(count($subTopicList) > 0) {
						$subtopicCount = count($subTopicList);
						for($subRowNum = 0; $subRowNum < $subtopicCount; $subRowNum++) {
							$subtopicArrData[$subRowNum] = $subTopicList[$subRowNum];
							if($subRowNum == 0) {
							    $this->view->jsArrString .= "          bOO(\"Choose Subtopic\",\"\");\n";
							}
                            $key = json_encode(utf8_encode($subtopicArrData[$subRowNum]['subtopic_description']));
							$this->view->jsArrString .= "         bOO(". $key .",".json_encode($this->removeLeadingZeros($subtopicArrData[$subRowNum]['subtopic_code'])).");\n";
						}

					}
				}

			}
        }
    }

    protected function createAdditional($newId)
    {
		// force old version of form
        $modelform = new Model_Table_Form004();
        $form004 = $modelform->find($newId)->current();

    	if (1==$this->getRequest()->getParam('forceOld')) {
    		// store the IEP key
    		$form004->version_number = '3';
    	}

        $schoolObj = new Model_Table_School();
        $school = $schoolObj->getSchool($form004->id_county, $form004->id_district, $form004->id_school);
        $form004->fte_minutes_per_week = $school->current()->minutes_per_week;
        $form004->save();


        $form004TeamMemberObj = new Model_Table_Form004TeamMember();

		$iepTeamMembers = array(
		    0 => array('sortnum'=> 1, 'positin_desc' => 'Parent', ),
		    1 => array('sortnum'=> 2, 'positin_desc' => 'Student (whenever appropriate, or if the student is 16 years of age or older)', ),
		    2 => array('sortnum'=> 3, 'positin_desc' => 'Regular education teacher', ),
		    3 => array('sortnum'=> 4, 'positin_desc' => 'Special education teacher or provider', ),
		    4 => array('sortnum'=> 5, 'positin_desc' => 'School district representative', ),
		    5 => array('sortnum'=> 6, 'positin_desc' => 'Individual to interpret evaluation results', ),
		    6 => array('sortnum'=> 7, 'positin_desc' => 'Service agency representative (If child is receiving services from an approved Service Agency)', ),
		    7 => array('sortnum'=> 8, 'positin_desc' => 'Nonpublic representative (if student is attending a nonpublic school)', ),
		    8 => array('sortnum'=> 9, 'positin_desc' => 'Other agency representative (when transition services are being provided or will be provided by another agency for children age 16 and older)', ),
		    9 => array('sortnum'=> 10, 'positin_desc' => 'Speech Language Pathologist', ),
            10 => array('sortnum'=> 11, 'positin_desc' => 'Hearing Resource Teacher')
	    );

        foreach($iepTeamMembers as $tm) {
            // for each goal on the iep
            // create a goal progress record
            $data = array(
                'id_form_004'         => $newId,
                'sortnum'             => $tm['sortnum'],
                'positin_desc'        => $tm['positin_desc'],
            );
            $form004TeamMemberObj->insert($data);
        }


		$form004GoalObj = new Model_Table_Form004Goal();
		$data = array(
		    'id_form_004'       => $newId,
		    'id_author'		=> $this->usersession->sessIdUser,
		    'id_author_last_mod'=>$this->usersession->sessIdUser,
		    'id_student'	=> $this->getRequest()->student,
		);
		$form004GoalObj->insert($data);

		$form004SecondaryGoalObj = new Model_Table_Form004SecondaryGoal();
		$data = array(
		    'id_form_004'       => $newId,
		);
		$form004SecondaryGoalObj->insert($data);

		$form004AccommodationCheckistObj = new Model_Table_Form004AccomodationsChecklist();
		$data = array(
		    'id_form_004'       => $newId,
		);
		$form004AccommodationCheckistObj->insert($data);

    }

    private function removeLeadingZeros($string)
    {
        return preg_replace('/^0+(.)/', "$1", $string);
    }

    protected function finalizeAdditional($id_form_004) {

		$iepObj = new Model_Table_Form004();
		$studentObj = new Model_Table_StudentTable();

		$iep = $iepObj->find($id_form_004)->current();
//		Zend_Debug::dump($iep['id_student']);
//		die();
//		$studentRec = $studentObj->find();

		$form010Obj = new Model_Table_Form010();
    	$goalProgressObj = new Model_Table_Form004GoalProgress();

    	$goalsFromLastIep = $iepObj->getChildRecords('iep_form_004_goal', 'id_form_004', $id_form_004, 'timestamp_created', 'Draft');

    	if(count($goalsFromLastIep) > 0) {
	    	$pgSchedule = $goalsFromLastIep[0];
	    	$pgDateArr = array();

			// pull all non null progress dates into an array
			// build pgDateArr
			for($i=1; $i<=6; $i++) {
			    if('' != $pgSchedule['progress_date'.$i]) $pgDateArr[] = $pgSchedule['progress_date'.$i];
			}

			// get count of pr dates and create PR for each one
			$numPrToCreate = count($pgDateArr);

            // these are progress reports being created, not goals
            for($createPrCounter =1; $createPrCounter <= $numPrToCreate; $createPrCounter++) // 20070612 jlavere - progress report update
            {
	        	/*
	        	 * foreach progress date that's filled in
	        	 * create a progress report and
	        	 * a goal progress record related to that report
	        	 */
				$data = array(
				    'id_form_004'   => $id_form_004,
				    'id_author'		=> $this->usersession->sessIdUser,
				    'id_author_last_mod'=>$this->usersession->sessIdUser,
				    'id_student'	=> $iep['id_student'],
					'date_notice'   => $pgSchedule['progress_date'.$createPrCounter],
				    'id_county'		=> $iep['id_county'],
					'id_district'	=> $iep['id_district'],
					'id_school'		=> $iep['id_school'],
					'page_status'	=> '0',
				);
				//Zend_Debug::dump($data);
	        	$newId = $form010Obj->insert($data);

	        	/*
	        	 * hack
	        	 * if you insert the version_number in the initial insert
	        	 * it is overridden by a postgres function that will set the version
	        	 * make sure the version is set properly
	        	 */
				$current = $form010Obj->find($newId)->current();
		        $current->version_number = 9; // Q: how can the varsion_number from the form010Controller be retrieved here?
		        $current->save();

		        // insert a goal progress record for each goal - for this pr
		        for($gCounter =0; $gCounter < count($goalsFromLastIep); $gCounter++) {
					$data = array(
					    'id_form_010'   => $newId,
					    'id_form_004'   => $id_form_004,
						'id_author'		=> $this->usersession->sessIdUser,
					    'id_author_last_mod'=>$this->usersession->sessIdUser,
					    'id_student'	=> $iep['id_student'],
						'progress_measurement' => '',
						'progress_sufficient' => null,
						'id_form_004_goal' => $goalsFromLastIep[$gCounter]['id_form_004_goal'],
						'progress_measurement_explain' => '',
						'progress_comment' => '',
					);
//					print_r($data);
//					die();
			        $goalProgressObj->insert($data);
                }
            }
    	}
//    	die();
    }

    function saveAdditional($post) {

    	if(isset($post['clearRadioButtons']) && 1 == $post['clearRadioButtons']) {
			$iepObj = new Model_Table_Form004();
			$iep = $iepObj->find($post[$this->getPrimaryKeyName()])->current();
    		$iep->absences_approved = null;
    		$iep->necessary_action = null;
	        $iep->received_copy = null;
	        $iep->parental_rights = null;
    	    $iep->doc_signed_parent = null;
    	    $iep->pwn_agree=null;
    		$iep->save();
    	}
        if(6 == $post['page']) {
            $iepObj = new Model_Table_Form004();
            $iep = $iepObj->find($post[$this->getPrimaryKeyName()])->current();
            $iep = $iepObj->buildFteMinutes($iep);
            $iep->save();
            $this->fteMostRecentSave = $iep;
        }
    }
    function postSaveAdditional($post, $dojoData)
    {
        $additionalFields = array();
        $additionalFields['fte_special_education_time'] = $this->fteMostRecentSave->fte_special_education_time;
        $additionalFields['fte_qualifying_minutes'] = $this->fteMostRecentSave->fte_qualifying_minutes;
        $additionalFields['fte_minutes_per_week'] = $this->fteMostRecentSave->fte_minutes_per_week;
        $additionalFields['fte_total_qualifying_min_se'] = $this->fteMostRecentSave->fte_total_qualifying_min_se;
        $additionalFields['fte_total_qualifying_min_re'] = $this->fteMostRecentSave->fte_total_qualifying_min_re;
        $dojoData[$post['id_form_004']] = array_merge($dojoData->getItem($post['id_form_004']), $additionalFields);


        return $dojoData;
    }

    protected function buildAdditional($form, $page, $modelData, $config)
    {

		if($this->betaTesters($modelData[$this->getPrimaryKeyName()])) {
			$config['isBeta'] = true;
		}

		// testing custom widget
//        $appConfig = Zend_Registry::get('config');
//    	$this->view->dojo()->requireModule('soliant.widget.FileInput');//->registerModulePath('soliant', '../../soliant')
//		$this->view->headLink()->appendStylesheet($appConfig->dojoCustomPath . '/widget/FileInput/FileInput.css');


    	// $this->subFormsArray is set in addSubformSectionNew
    	$subFormsArray = array();
		if($page == 1) {
			if(isset($modelData['team_member']['count'])) {
				$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['team_member']['count'], $config, "team_member", "Form_Form004TeamMember", "Model_Table_Form004TeamMember");
			}
			if(isset($modelData['team_other']['count'])) {
				$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['team_other']['count'], $config, "team_other", "Form_Form004TeamOther", "Model_Table_Form004TeamOther");
			}
			if(isset($modelData['team_district']['count'])) {
				$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['team_district']['count'], $config, "team_district", "Form_Form004TeamDistrict", "Model_Table_Form004TeamDistrict");
			}

		} elseif($page == 4 && isset($modelData['iep_form_004_goal']['count'])) {
			$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['iep_form_004_goal']['count'], $config, "iep_form_004_goal", "Form_Form004Goal", "Model_Table_Form004Goal", false, array('eval_procedure', 'person_responsible'), null, $modelData['form_editor_type']);

		} elseif($page == 5 && isset($modelData['iep_form_004_secondary_goal']['count'])) {
			$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['iep_form_004_secondary_goal']['count'], $config, "iep_form_004_secondary_goal", "Form_Form004SecondaryGoal", "Model_Table_Form004SecondaryGoal");
            /**
             * Check to see if student is older than 15
             * q: this feels like a bad place to set things into the view
             */

			$birthdate = new DateTime($modelData['student_data']['dob']); //birthdate
			$effectToDate = new DateTime($modelData['effect_to_date']); //ending effect date
			$interval = $birthdate->diff($effectToDate);
			if($interval->y >= 16) {
			    $this->view->studentOlderThan15 = true;
			} else {
			    $this->view->studentOlderThan15 = false;
			}

			/**
             * End Check
             */

		} elseif($page == 6) {
			if('print' != $this->view->mode && isset($modelData['accomodations_checklist']['count'])) {
			    $subFormsArray[] = $this->addSubformSectionNew($form, $modelData['accomodations_checklist']['count'], $config, "accomodations_checklist", "Form_Form004AccomodationsChecklist", "Model_Table_Form004AccomodationsChecklist");
			}
			if(isset($modelData['related_services']['count'])) {
				$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['related_services']['count'], $config, "related_services", "Form_Form004RelatedService","Model_Table_Form004RelatedService", true, null, 'override_related');
				$this->createSubformSelectMenu($form, $modelData, 'related_services', 'related_service_location', 'getLocation_version1', array('dob'=>$modelData['student_data']['dob'], 'lps'=>$form->lps, 'finalized_date'=>$modelData['finalized_date'],'status'=>$modelData['status']));
			}
			if(isset($modelData['supp_services']['count'])) {
				$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['supp_services']['count'], $config, "supp_services", "Form_Form004SuppService", "Model_Table_Form004SupplementalService", true, null, 'override_supp', $modelData['form_editor_type']);
				$this->createSubformSelectMenu($form, $modelData, 'supp_services', 'supp_service_location', 'getLocation_version1', array('dob'=>$modelData['student_data']['dob'], 'lps'=>$form->lps, 'finalized_date'=>$modelData['finalized_date'],'status'=>$modelData['status']));
			}
			if(isset($modelData['program_modifications']['count'])) {
				$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['program_modifications']['count'], $config, "program_modifications", "Form_Form004ProgramModification", "Model_Table_Form004ProgramModifications", true, null, 'override_prog_mod', $modelData['form_editor_type']);
				$this->createSubformSelectMenu($form, $modelData, 'program_modifications', 'prog_mod_location', 'getLocation_version1', array('dob'=>$modelData['student_data']['dob'], 'lps'=>$form->lps, 'finalized_date'=>$modelData['finalized_date'],'status'=>$modelData['status']));
			}
			if(isset($modelData['assist_tech']['count'])) {
				$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['assist_tech']['count'], $config, "assist_tech", "Form_Form004AssistiveTechnology", "Model_Table_Form004AssistiveTechnology", true, null, 'override_ass_tech', $modelData['form_editor_type']);
				$this->createSubformSelectMenu($form, $modelData, 'assist_tech', 'assist_tech_location', 'getLocation_version1', array('dob'=>$modelData['student_data']['dob'], 'lps'=>$form->lps, 'finalized_date'=>$modelData['finalized_date'],'status'=>$modelData['status']));
			}
			if(isset($modelData['school_supp']['count'])) {
				$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['school_supp']['count'], $config, "school_supp", "Form_Form004SchoolSupport", "Model_Table_Form004SchoolSupport", true, null, 'override_school_supp', $modelData['form_editor_type']);
				$this->createSubformSelectMenu($form, $modelData, 'school_supp', 'school_supp_location', 'getLocation_version1', array('dob'=>$modelData['student_data']['dob'], 'lps'=>$form->lps, 'finalized_date'=>$modelData['finalized_date'],'status'=>$modelData['status']));
			}
			$this->createSelectMenu($form, 'primary_service_location', 'getLocation_version1', array('dob'=>$modelData['student_data']['dob'], 'lps'=>$form->lps, 'finalized_date'=>$modelData['finalized_date'],'status'=>$modelData['status']));

            /**
             * remove validation on assistive tech for v11+ forms
             */
            if(11 >= $modelData['version_number']) {
                foreach ($form->getSubforms() as $key => $value) {
                    if(substr_count($key, 'assist_tech_') > 0) {
                        foreach($form->getSubform($key)->getElements() as $eKey => $element) {
                            $form->getSubform($key)->getElement($eKey)->setRequired(false);
                            $form->getSubform($key)->getElement($eKey)->setAllowEmpty(false);
                        }
                    }
                }
            }

            /**
             * setup the FTE Report
             */
            if(11 >= $modelData['version_number']) {
                $districtObj = new Model_Table_District();
                $district = $districtObj->getDistrict($modelData['id_county'], $modelData['id_district']);

                if(true == $district['use_fte_report']) {
                    $this->view->displayFteReport = $this->displayFteReport = true; // turns on some of the display when view is configured
                    $form->use_fte_report();
                    if(isset($modelData['related_services']['count'])) {
                        for($i = 1; $i <= $modelData['related_services']['count']; $i++) {
                            if($form->getSubForm('related_services_'.$i)) {
                                $form->getSubForm('related_services_'.$i)->use_fte_report();
                            }
                        }
                    }
                }

                $this->view->fte_total_qualifying_min_se = $modelData['fte_total_qualifying_min_se'];
                $this->view->fte_total_qualifying_min_re = $modelData['fte_total_qualifying_min_re'];
            }

        } elseif($page == 8) {
            if('print' == $this->view->mode && isset($modelData['accomodations_checklist']['count'])) {
                $subFormsArray[] = $this->addSubformSectionNew($form, $modelData['accomodations_checklist']['count'], $config, "accomodations_checklist", "Form_Form004AccomodationsChecklist", "Model_Table_Form004AccomodationsChecklist");
            }
			if(isset($modelData['iep_form_004_suppform']['count'])) {
            	$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['iep_form_004_suppform']['count'], $config, "iep_form_004_suppform", "Form_Form004SupplementalForm", "Model_Table_Form004SupplementalForm");
			}
		}
		//Zend_Debug::dump($modelData['student_data']);
		return $subFormsArray;
    }
}
