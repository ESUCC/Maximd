<?php
use App_Encoding_Encode as Encode;

class Form013Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 10;
	protected $startPage = 1;
	
	protected $_subformTypes = array(
	    'OtherServices' => 'other_services',
	    'HomeCommunity' => 'home_community',
	);
	
    public function init() {
        $this->_redirector = $this->_helper->getHelper('Redirector');

        // form parameters
        $this->view->pageCount = 9;
        
        parent::setPrimaryKeyName('id_form_013');
        parent::setFormNumber('013');
        parent::setModelName('Model_Form013');
        parent::setFormClass('Form_Form013');
        parent::setFormTitle('Individual Family Service Plan');
        parent::setFormRev('08/08');
    }
	protected function preCreateRequirements() {
        if(!$this->getRequest()->getParam('ifsptype') 
        	|| !$this->getRequest()->getParam('import')
        	|| ('duplicate' == $this->getRequest()->getParam('import') && !$this->getRequest()->getParam('updateFromThisIfsp'))
        	|| ('update' == $this->getRequest()->getParam('import') && !$this->getRequest()->getParam('updateFromThisIfsp'))
        	) {
    	    // previous ifsps
        	
        	
    	    $view = new Zend_View();
	        $formObj = new Form_Form013();
	        $view->form = $formObj->required_p1_v1();
	        
	        $view->student = $this->getRequest()->getParam('student');
// 	        $view->ifsptype = $this->getRequest()->getParam('import');
	        $view->params = $this->getRequest()->getParams();
	        
        	$form013Obj = new Model_Table_Form013();
    	    $view->finalForms = $form013Obj->finalForms($this->getRequest ()->student)->toArray();
    	    
    	    if(count($view->finalForms) > 0) {

                $idArray = array_map(function($value) { return $value['id_form_013']; }, $view->finalForms);
                // remove archived forms
                foreach($view->finalForms as $key => $ifspFinal) {
                    if($archiveIfspId = false !== array_search($ifspFinal['id_form_013_duped_from'], $idArray)) {
                        unset($view->finalForms[$key]);
                        unset($idArray[$archiveIfspId]);
                        $idArray = array_values($idArray);
                    }
                }
				$displayName = array_map(function($value) { return $value['meeting_date']; }, $view->finalForms);
				$multiOptions = array_combine($idArray, $displayName);
			    $view->form->updateFromThisIfsp = new Zend_Form_Element_Radio('updateFromThisIfsp', array('label' => 'Select IFSP to update from.'));
				$view->form->updateFromThisIfsp->setMultiOptions($multiOptions);
				$view->form->updateFromThisIfsp->setValue($this->getRequest()->getParam('updateFromThisIfsp'));
				
				$importOptions = array(
					'duplicate' => '<B>Duplicate</B> - This method for creating a NEW IFSP will import all of the data from the student\'s previous IFSP except for dates and parent signatures.',
					'blank' => '<B>Blank</B> - This method will carry nothing over from the previous IFSP.  This will give you a blank IFSP form.',
					'update' => '<B>Update/Modification</B> - Use this method if you are making a minor correction or update to the student\'s last IFSP form. <span style="color:red;">This is not the preferred method for creating a new version of an IFSP.</span>'
				);
				
    	    } else {
				$importOptions = array(
					'blank' => '<B>Blank</B> - This method will carry nothing over from the previous IFSP.  This will give you a blank IFSP form.',
				);
    	    	
    	    }
			$view->form->import = new Zend_Form_Element_Radio('import', array('escape'=>false, 'label'=>'Importing Data -- Please select how you would like to import data into this IFSP?'));
			$view->form->import->setMultiOptions($importOptions);
			$view->form->import->setValue($this->getRequest()->getParam('import'));
			
			
			$view->form->button = new Zend_Form_Element_Button('send', array('label'=>'Submit'));
			$view->form->button->setAttrib('onclick', "dojo.byId('submitted').value=1;dojo.byId('myform').submit()");
			
			$multiOptions = App_Form_ValueListHelper::ifspType();
			$view->form->ifsptype = new App_Form_Element_Select('ifsptype', array('label' => 'IFSP Type', 'multiOptions' => $multiOptions));
			if(isset($view->params['ifsptype'])) {
				$view->form->ifsptype->setValue($view->params['ifsptype']);
			}
			$view->form->ifsptype->setAttrib('onchange', '');	
				
			
			
	        $view->setScriptPath(APPLICATION_PATH . '/views/scripts/form013');
	        echo $view->render("create.phtml");
            $this->render('data');
            return false;
        }
        
		$this->preCreateRequirementsArray = array();
		$this->preCreateRequirementsArray['ifsptype'] = $this->getRequest()->getParam('ifsptype');
		
		if('blank' == $this->getRequest()->getParam('import')) {
			return true;
		} else {
			if('duplicate' == $this->getRequest()->getParam('import')) {
				$full = 0;
			} else {
				$full = 1;
			}
			
			// redirect to dupe
			$this->_redirector->gotoSimple ( 'dupe', 'form'.$this->formNumber, null, 
				array (
					'student' => $this->getRequest()->getParam('student'), 
					'document' => $this->getRequest()->getParam('updateFromThisIfsp'), 
					'ifsptype' => $this->getRequest()->getParam('ifsptype'), 
					'full' => $full, 
					'page' => 1 ) 
			);
			
		}
	}
	protected function dupe($document, $params) {

		$formObj = new Model_Table_Form013();
		$newId = $formObj->dupe($document, $params['ifsptype'], $params['full']);

		$modelName = "Model_Table_Form" . $this->getFormNumber ();
		$formObj = new $modelName ();
		$current = $formObj->find ( $newId )->current ();
		$current->version_number = $this->version;
		if (isset ( $this->preCreateRequirementsArray )) {
			// these are chosen by the user in preCreateRequirements
			foreach ( $this->preCreateRequirementsArray as $key => $value ) {
				$current->$key = $value;
			}
		}
		$current->save();
		
		return $newId;
	}
	protected function createOverride($studentId, $options = array()) {
		if('blank' == $options['import']) {
		    // retrieve data from the request and create a new row
			$newId = $this->createTableRow($this->getRequest()->student);
			
		} elseif('update' == $options['import']) {

    	    $form013Obj = new Model_Table_Form013();
    	    $mostRecentFinalForm = $form013Obj->mostRecentFinalForm($studentId);
    	    
    	    $newId = $form013Obj->dupe($mostRecentFinalForm['id_form_013'], $options['ifsptype'], 1);
			
		} elseif('duplicate' == $options['import']) {

    	    $form013Obj = new Model_Table_Form013();
    	    $mostRecentFinalForm = $form013Obj->mostRecentFinalForm($studentId);
    	    
    	    $newId = $form013Obj->dupe($mostRecentFinalForm['id_form_013'], $options['ifsptype'], 0);
    	    
    	    // clear more fields
            $form013OGoalsbj = new Model_Table_Form013Goals();
            $goals = $form013OGoalsbj->fetchAll("id_form_013 = '{$newId}'");
            foreach($goals as $goal) {
                $goal->goal_review_date = null;
                $result = $goal->save();
            }
            $form013ServicesObj = new Model_Table_Form013Services();
            $services = $form013ServicesObj->fetchAll("id_form_013 = '{$newId}'");
            foreach($services as $service) {
                $service->service_start = null;
                $service->service_end = null;
                $result = $service->save();
            }
            $form013TeamMemberObj = new Model_Table_Form013TeamMembers();
            $team = $form013TeamMemberObj->fetchAll("id_form_013 = '{$newId}'");
            foreach($team as $teamMember) {
                $teamMember->tm_sig_on_file = null;
                $result = $teamMember->save();
            }
		}
		return $newId;
	}
    protected function createAdditional($newId, $options = array())
    {
    	
        if('blank' != $options['import']) {
	        return;
		}
    	
        $studentObj = new Model_Table_StudentTable();
        $personnelObj = new Model_Table_PersonnelTable();
        $personnelRec = $personnelObj->getById($this->user->user['id_personnel']);


        $form002Obj = new Model_Table_Form002();
        $form013Obj = new Model_Table_Form013();
        $form014Obj = new Model_Table_Form014();
        $form015Obj = new Model_Table_Form015();
        
        $current = $form013Obj->find($newId)->current();        
        $student = $studentObj->find($current->id_student)->current()->toArray();

        
        $form002 = $form002Obj->mostRecentForm($current->id_student);
        $form014 = $form014Obj->mostRecentForm($current->id_student);
        $form015 = $form015Obj->mostRecentForm($current->id_student);

        $current->ssn_form = $student['ssn'];
        $current->medicaid_form = $student['medicaid'];

        if(null != $student['id_ser_cord']) {
            $sc = $personnelObj->getById($student['id_ser_cord']);
            $current->sc_name = $sc['name_first'] . ' ' . $sc['name_last'];
            $current->sc_phone = $sc['phone_work'];
            $current->sc_address = $sc['address_street1'] . "\n" .
            (null != $sc['address_street2']) ? $sc['address_street2'] . "\n" : '' .
                $sc['address_city'] . ", " . $sc['address_state'] . " " . $sc['address_zip'];
            $current->sc_agency = $sc['agency'];
        }

        $current->eval_date = $form015['date_notice'];
        $current->date_notice = $form014['date_notice'];
        $current->date_mdt = $form002['date_mdt'];


        $formO13Helper = new Model_Form013("013", $this->usersession);
        $ifsp_history = $formO13Helper->getIfspHistory($student['id_student'], $newId);

        foreach($ifsp_history as $ifspHistoryForm) {
            $current->dev_vision_print_ifsps .= $ifspHistoryForm['id_form_013']."\n";
            $current->dev_hearing_print_ifsps .= $ifspHistoryForm['id_form_013']."\n";
            $current->dev_health_print_ifsps .= $ifspHistoryForm['id_form_013']."\n";
            $current->dev_cognitive_print_ifsps .= $ifspHistoryForm['id_form_013']."\n";
            $current->dev_communication_print_ifsps .= $ifspHistoryForm['id_form_013']."\n";
            $current->dev_social_print_ifsps .= $ifspHistoryForm['id_form_013']."\n";
            $current->dev_self_print_ifsps .= $ifspHistoryForm['id_form_013']."\n";
            $current->dev_fine_motor_print_ifsps .= $ifspHistoryForm['id_form_013']."\n";
            $current->dev_gross_motor_print_ifsps .= $ifspHistoryForm['id_form_013']."\n";

            $current->child_strengths_print_ifsps .= $ifspHistoryForm['id_form_013']."\n";
            $current->family_concerns_print_ifsps .= $ifspHistoryForm['id_form_013']."\n";
        }



//        Zend_Debug::dump($ifsp_history[0]['id_form_013']);
//        die;

        $meetingDates = $formO13Helper->getMeetingDateList($current->id_student, $current->ifsptype);
        $idString = '';
        foreach ($meetingDates as $id => $ifsp) {
            $idString .= $ifsp['id_form_013'] . "\n";
        }
        $current->previous_dates_hide_print_ids = $idString;
        $current->save();

        // get ei case manager if exists
        if(null != $student['id_ei_case_mgr']) {
        	$eicm = $personnelObj->getById($student['id_ei_case_mgr']);
		    // Make new team members row to start off, for page6
		    $teamMemberObj = new Model_Table_Form013TeamMembers();
		    $address = $eicm['address_street1'] . "\n";
		    if(null != $eicm['address_street2']) {
		    	$address .= $eicm['address_street2'] . "\n";	
		    }
		    $address .= $eicm['address_city'] . ", " .$eicm['address_state'] . " " . $eicm['address_zip'];
		    					
		    $data = array(
		        'id_form_013'       => $newId,
		        'id_author'     => $this->usersession->sessIdUser,
		        'id_author_last_mod'=> $this->usersession->sessIdUser,
		        'id_student'    => $this->getRequest()->student,
		    	'tm_signature' => $eicm['name_first'] . ' ' . $eicm['name_last'],
		    	'tm_role' => 'Service Coordinator',
		    	'tm_address' => $address,
		    	'status' => 'Active',
			);
		    $teamMemberObj->insert($data);
        }
        // get service coordinator if exists
        if(null != $student['id_ser_cord']) {
        	$sc = $personnelObj->getById($student['id_ser_cord']);
		    // Make new team members row to start off, for page6
		    $teamMemberObj = new Model_Table_Form013TeamMembers();
		    $address = $sc['address_street1'] . "\n";
		    if(null != $sc['address_street2']) {
		    	$address .= $sc['address_street2'] . "\n";	
		    }
		    $address .= $sc['address_city'] . ", " .$sc['address_state'] . " " . $sc['address_zip'];
		    					
		    $data = array(
		        'id_form_013'       => $newId,
		        'id_author'     => $this->usersession->sessIdUser,
		        'id_author_last_mod'=> $this->usersession->sessIdUser,
		        'id_student'    => $this->getRequest()->student,
		    	'tm_signature' => $sc['name_first'] . ' ' . $sc['name_last'],
		    	'tm_role' => 'Service Coordinator',
		    	'tm_address' => $address,
		    	'status' => 'Active',
			);
		    $teamMemberObj->insert($data);
        }
        
        // page 5 default to 1 goal
		$form013GoalObj = new Model_Table_Form013Goals;
	    $data = array(
	        'id_form_013'       => $newId,
	        'id_author'     => $this->usersession->sessIdUser,
	        'id_author_last_mod'=> $this->usersession->sessIdUser,
	        'id_student'    => $this->getRequest()->student,
	    );
		$form013GoalObj->insert($data);
        
        
	    // Make new team members row to start off, for page6
	    $form013SERVICESObj = new Model_Table_Form013Services();
	    $data = array(
	        'id_form_013'       => $newId,
	        'id_author'     => $this->usersession->sessIdUser,
	        'id_author_last_mod'=> $this->usersession->sessIdUser,
	        'id_student'    => $this->getRequest()->student,
	    );
	    $form013SERVICESObj->insert($data);
		
		// Make new team members row to start off, for page 8
		$form013TMObj = new Model_Table_Form013TeamMembers();
		$data = array(
		    'id_form_013'       => $newId,
		    'id_author'		=> $this->usersession->sessIdUser,
		    'id_author_last_mod'=> $this->usersession->sessIdUser,
		    'id_student'	=> $this->getRequest()->student,
		);
		$form013TMObj->insert($data);
		
		// Make new tran plan row to start off, for page 7
		$form013TranObj = new Model_Table_Form013TranPlan();
		$data = array(
		    'id_form_013'       => $newId,
		    'id_author'		=> $this->usersession->sessIdUser,
		    'id_author_last_mod'=> $this->usersession->sessIdUser,
		    'id_student'	=> $this->getRequest()->student,
		);
		$form013TranObj->insert($data);
		
		// Make new parents/guardian entry, for page 1
		$studentObj = new Model_Table_ViewAllStudent();
	    $student = $studentObj->find($this->getRequest()->student)->current()->toArray();

	    $guardanObj = new Model_Table_GuardianTable();
	    $parents = $guardanObj->fetchAll("id_student = '{$this->getRequest()->student}'")->toArray();
	    
	    $form013ParentsObj = new Model_Table_Form013Parents();
	    foreach($parents as $parent) {
	    	$address = $parent["address_street1"]." ".$parent["address_street2"]." \n".$parent["address_city"].
	    			   ", ".$parent["address_state"]." ".$parent["address_zip"];
			$data = array(
			    'id_form_013'       => $newId,
			    'id_author'		=> $this->usersession->sessIdUser,
			    'id_author_last_mod'=> $this->usersession->sessIdUser,
			    'id_student'	=> $this->getRequest()->student,
			    'pg_name'		=> $parent['name_first'] . ' ' . $parent['name_last'],
			    'pg_address'	=> $address,
			    'pg_home_phone'	=> $parent['phone_home'],
			    'pg_work_phone'	=> $parent['phone_work'],
			    'pg_role'	=> $parent['relation_to_child'],
			);
			$form013ParentsObj -> insert($data);
	    }
    }
    
    protected function saveAdditional($post)
    {
    	// parse print ids from page 1
    	if(isset($post['previous_dates_hide_print_ids']))
    	{
            $form013Obj = new Model_Table_Form013;
            $current = $form013Obj->find($this->view->db_form_data[$this->getPrimaryKeyName()])->current();
            $current->previous_dates_hide_print_ids = implode("\n", $post['previous_dates_hide_print_ids'])."\n";
            $current->save();
    	}
    	
    	// parse print ids from page 2
    	if(isset($post['family_concerns_print_ifsps']))
    	{
            $form013Obj = new Model_Table_Form013;
            $current = $form013Obj->find($this->view->db_form_data[$this->getPrimaryKeyName()])->current();
            $current->family_concerns_print_ifsps = implode("\n", $post['family_concerns_print_ifsps'])."\n";
            $current->save();
        } elseif(2 == $post['page']) {
            $form013Obj = new Model_Table_Form013;
            $current = $form013Obj->find($this->view->db_form_data[$this->getPrimaryKeyName()])->current();
            $current->family_concerns_print_ifsps = null;
            $current->save();
        }

    	// parse print ids from page 3
    	if(isset($post['child_strengths_print_ifsps']))
    	{
            $form013Obj = new Model_Table_Form013;
            $current = $form013Obj->find($this->view->db_form_data[$this->getPrimaryKeyName()])->current();
            $current->child_strengths_print_ifsps = implode("\n", $post['child_strengths_print_ifsps'])."\n";
            $current->save();
        } elseif(3 == $post['page']) {
            $form013Obj = new Model_Table_Form013;
            $current = $form013Obj->find($this->view->db_form_data[$this->getPrimaryKeyName()])->current();
            $current->child_strengths_print_ifsps = null;
            $current->save();
    	}

    	// parse print ids from page 4
    	if(
//    		isset($post['dev_vision_print_ifsps']) ||
//    		isset($post['dev_hearing_print_ifsps']) ||
//    		isset($post['dev_health_print_ifsps']) ||
//    		isset($post['dev_cognitive_print_ifsps']) ||
//    		isset($post['dev_communication_print_ifsps']) ||
//    		isset($post['dev_social_print_ifsps']) ||
//    		isset($post['dev_self_print_ifsps']) ||
//    		isset($post['dev_fine_motor_print_ifsps']) ||
//    		isset($post['dev_gross_motor_print_ifsps']) ||
            4 == $post['page']
		) {
            $form013Obj = new Model_Table_Form013;
            $current = $form013Obj->find($this->view->db_form_data[$this->getPrimaryKeyName()])->current();
            if(isset($post['dev_vision_print_ifsps'])) {
            	$current->dev_vision_print_ifsps = implode("\n", $post['dev_vision_print_ifsps'])."\n";
            } else {
                $current->dev_vision_print_ifsps = null;
            }
            if(isset($post['dev_hearing_print_ifsps'])) {
	            $current->dev_hearing_print_ifsps = implode("\n", $post['dev_hearing_print_ifsps'])."\n";
            } else {
                $current->dev_hearing_print_ifsps = null;
            }
            if(isset($post['dev_health_print_ifsps'])) {
	            $current->dev_health_print_ifsps = implode("\n", $post['dev_health_print_ifsps'])."\n";
            } else {
                $current->dev_health_print_ifsps = null;
            }
            if(isset($post['dev_cognitive_print_ifsps'])) {
	            $current->dev_cognitive_print_ifsps = implode("\n", $post['dev_cognitive_print_ifsps'])."\n";
            } else {
                $current->dev_cognitive_print_ifsps = null;
            }
            if(isset($post['dev_communication_print_ifsps'])) {
	            $current->dev_communication_print_ifsps = implode("\n", $post['dev_communication_print_ifsps'])."\n";
            } else {
                $current->dev_communication_print_ifsps = null;
            }
            if(isset($post['dev_social_print_ifsps'])) {
	            $current->dev_social_print_ifsps = implode("\n", $post['dev_social_print_ifsps'])."\n";
            } else {
                $current->dev_social_print_ifsps = null;
            }
            if(isset($post['dev_self_print_ifsps'])) {
	            $current->dev_self_print_ifsps = implode("\n", $post['dev_self_print_ifsps'])."\n";
            } else {
                $current->dev_self_print_ifsps = null;
            }
            if(isset($post['dev_fine_motor_print_ifsps'])) {
	            $current->dev_fine_motor_print_ifsps = implode("\n", $post['dev_fine_motor_print_ifsps'])."\n";
            } else {
                $current->dev_fine_motor_print_ifsps = null;
            }
            if(isset($post['dev_gross_motor_print_ifsps'])) {
	            $current->dev_gross_motor_print_ifsps = implode("\n", $post['dev_gross_motor_print_ifsps'])."\n";
            } else {
                $current->dev_gross_motor_print_ifsps = null;
            }
            $current->save();
    	}

    	// parse print ids from page 5 goals
    	if(isset($post['ifsp_goals']))
    	{
    		for($g = 1; $g <= $post['ifsp_goals']['count']; $g++) {
    			if(isset($post['ifsp_goals_'.$g]['goal_progress_print_ifsps'])) {
		            $form013GoalObj = new Model_Table_Form013Goals;
		            $current = $form013GoalObj->find($post['ifsp_goals_'.$g]['id_ifsp_goals'])->current();
    				$current->goal_progress_print_ifsps = implode("\n", $post['ifsp_goals_'.$g]['goal_progress_print_ifsps'])."\n";
    				$current->save();
    			}
    		}
    	}

    	if(isset($post['autofill_meeting_date']) && 't' == $post['autofill_meeting_date'])
    	{
            $form013Obj = new Model_Table_Form013;
            $current = $form013Obj->find($this->view->db_form_data[$this->getPrimaryKeyName()])->current();
            $current->date_family_concerns = $current->meeting_date;
            $current->date_child_strengths = $current->meeting_date;
            $current->dev_vision_date = $current->meeting_date;
            
            $current->dev_hearing_date = $current->meeting_date;
            $current->dev_health_status_date = $current->meeting_date;
            $current->dev_cognitive_date = $current->meeting_date;
            $current->dev_communication_date = $current->meeting_date;
            $current->dev_social_date = $current->meeting_date;
            $current->dev_self_help_date = $current->meeting_date;
            $current->dev_fine_motor_date = $current->meeting_date;
            $current->dev_gross_motor_date = $current->meeting_date;
            
            $current->save();
    	}
    	
    }
    protected function buildAdditional($form, $page, $modelData, $config)
    {
    	$model = new Model_Form013("013", $this->usersession);
    	
    	// Get guardian info
    	$guardians = array();
    	$guardianTable = new Model_Table_GuardianTable();
    	$guardianIds = explode(';', $modelData['student_data']['id_list_guardian']);
    	foreach($guardianIds as $guardianId) {
    		if($guardianId != '')
    			$guardians[] = $guardianTable->getGuardianById($guardianId);
    	}
    	$this->view->guardians = $guardians;
    	
    	foreach($guardians as $row=>$guardian) {
    		$this->view->db_form_data['student_data']['ifsp_parents_' . $row]['pg_name'] = null;
    	}
		// end Get guardian info
		
		// build ifsp history
		if(	('edit' == $this->getRequest()->getActionName() || 
			'view' == $this->getRequest()->getActionName() || 
			'print' == $this->getRequest()->getActionName() ) &&
			1 == $page
		) {
			$this->view->db_form_data['ifsp_history'] = $model->getIfspHistory($this->view->db_form_data['id_student'], $this->view->db_form_data['id_form_013']);
		}
    	// build subforms (related table data)
    	// as well as any page specific additions
    	$subFormsArray = array();
        if($page == 1) {
        	if(isset($modelData['ifsp_parents']['count'])) {
        		$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['ifsp_parents']['count'], $config, "ifsp_parents", "Form_Form013Parents", "Model_Table_Form013Parents");
        	}
        	// create dynamic select menu
//            $this->createSelectMenu($form, 'ifsptype_secondary_role', 'getIfspSecondaryRole', array('ifsptype'=>$modelData['ifsptype']));
            // populate previous meeting dates
            if('' != $modelData['ifsptype']) {
                $this->view->db_form_data['previousMeetingDates'] = $model->getMeetingDateList($modelData['id_student'], $modelData['ifsptype']);
            }
        }
        if($page == 5 && isset($modelData['ifsp_goals']['count'])) {
            $subFormsArray[] = $this->addSubformSectionNew($form, $modelData['ifsp_goals']['count'], $config, "ifsp_goals", "Form_Form013Goals", "Model_Table_Form013Goals");  
        }
        if($page == 6 && isset($modelData['ifsp_services']['count'])) {
            $subFormsArray[] = $this->addSubformSectionNew($form, $modelData['ifsp_services']['count'], $config, "ifsp_services", "Form_Form013Services", "Model_Table_Form013Services");  
        }
        if ($page == 6) {
            foreach ($this->_subformTypes AS $key => $value) {
            
                /*
                 * Add subform
                 */
                if(isset($modelData[$value]['count'])) {
                    $subFormsArray[] = $this->addSubformSectionNew($form, $modelData[$value]['count'], $config, $value, "Form_Form013".$key, "Model_Table_Form013".$key);
                }
            }
        }
        if($page == 7) {

            /** 
             * Check to see if student is younger than 3 
             */
            if ((time()-strtotime($modelData['student_data']['dob'])) < (strtotime('+2 years 275 day') - time()))
            {
                $this->view->studentYoungerThan3 = true;
            }
            else
                $this->view->studentYoungerThan3 = false;
            /**
             * End Check
             */
        	if(isset($modelData['tran_plan']['count'])) {
        		$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['tran_plan']['count'], $config, "tran_plan", "Form_Form013TranPlan", "Model_Table_Form013TranPlan");
        	}

        	// get data for dynamic drop down on page 7
        	$this->view->db_form_data['value_list']['student_team'] = Form_Form013TranPlan::get_student_team(array('id_student' => $this->view->db_form_data['id_student']));
        }
        if($page == 8) {
        	if(isset($modelData['team_members']['count'])) {
        		$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['team_members']['count'], $config, "team_members", "Form_Form013TeamMembers", "Model_Table_Form013TeamMembers");
        	}
        	if(isset($modelData['team_other']['count'])) {
        		$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['team_other']['count'], $config, "team_other", "Form_Form013TeamOther", "Model_Table_Form013TeamOther");
        	}

            if('edit' == $this->view->mode) {
                // build helper data for inserting personnel used on previous ifsps
                $teamMemberObj = new Model_Table_Form013TeamMembers();
                $prevMembers = $teamMemberObj->previousIfspTeamMembers($this->view->db_form_data['id_student'], $this->view->db_form_data['id_form_013']);
                $this->view->previousTeamMembers = new Zend_Dojo_Data('id_my_template_data', Encode::forceUtf8($prevMembers), 'id_my_template_data');
            }
        }
        // end build subforms (related table data)
        
		return $subFormsArray;
    }
    
}
