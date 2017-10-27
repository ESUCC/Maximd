<?php
        
class Form029Controller extends My_Form_AbstractFormController {
 	
    protected $identity;
	protected $version = 11;
	protected $startPage = 1;
	// Mike added multipleDrafts for wade on SRS-124 10-26-2017
	protected $multipleDrafts = true;
	
	
	protected $_subformTypes = array(
			'OtherAttendee' => 'other_attendee',
	        'GenEdTeacher'  => 'gen_ed_teacher',
	        'SpecialEdTeacher' => 'special_ed_teacher',
	        'SchoolRepresentative' => 'school_representative',
	        'EvalResults' => 'eval_results',
	        'SpecialKnowledge' => 'special_knowledge'
	);
	
    public function init(){
        $this->_redirector = $this->_helper->getHelper('Redirector');

        // form parameters
        $this->view->pageCount = 4;
        
        parent::setPrimaryKeyName('id_form_029');
        parent::setFormNumber('029');
        parent::setModelName('Model_Form029');
        parent::setFormClass('Form_Form029');
        parent::setFormTitle('Notice of Meeting');
        parent::setFormRev('08/08');
    }

	protected function buildSrsForm($document, $page, $raw = false)
    {
		parent::buildSrsForm($document, $page);
		
		if($page == 2) {
			$this->formStructure['subforms'] = array(
					// subform definition - team member absences
					array(
							'name'=>'team_member_absences',
							'form' => 'Form_Form029TeamMemberAbsences',
							'model'=> 'Model_Table_Form029TeamMemberAbsences',
					),
			);
		
		}
		if($page == 3) {
			$this->formStructure['subforms'] = array(
					// subform definition - outside_agency
					array(
							'name'=>'outside_agency',
							'form' => 'Form_Form029OutsideAgency',
							'model'=> 'Model_Table_Form029OutsideAgency',
					),
			);
		
		}
		$this->useNewFormStructure = true;
		
		// build subforms
		// $this->config set in parent buildSrsForm method
		$subFormBuilder = new App_Form_SubformBuilder($this->config);
		
		// student_data form used to display the student info header on the top of forms
		$zendSubForm = $subFormBuilder->buildSubform("student_data", "student_data_header");
		$this->form->addSubForm($zendSubForm, "student_data");
		
		if(isset($this->useNewFormStructure) && $this->useNewFormStructure == true && isset($this->formStructure['subforms']))
		{
			// build subforms
			// $this->config set in parent buildSrsForm method
			// adds subforms to the main zend form based on counts in
			// the model data
			$subFormBuilderNew = new App_Form_SubformBuilderNew($this->formStructure);
			$subFormBuilderNew->buildSubforms($this->form, $this->view->db_form_data, $this->formStructure['subforms']);
		}
		
		if($page == 3 && $this->useNewFormStructure != true) {
			$this->addSubformSection("team_member_absences", "Form_Form029TeamMemberAbsences", "Model_Table_Form029TeamMemberAbsences");
		}
		if($page == 4 && $this->useNewFormStructure != true) {
			$this->addSubformSection("outside_agency", "Form_Form029OutsideAgency", "Model_Table_Form029OutsideAgency");
		}
		
		// fill the html form with db data
		$this->form->populate($this->view->db_form_data);
		
		
    	// remove team member absences validation if not used 
        if($page == 2 && 't' != $this->view->db_form_data['on_off_checkbox']) {
            $count = $this->form->getSubform('team_member_absences')->getElement('count')->getValue();
            for($i=1; $i<=$count; $i++)
            {
                $this->clearValidation($this->form->getSubform('team_member_absences_'.$i));
            }
        }
        
		// remove outside agency validation if not used
		if($page == 3 && true != $this->view->db_form_data['on_off_checkbox_page_4']) {
			$count = $this->form->getSubform('outside_agency')->getElement('count')->getValue();
            for($i=1; $i<=$count; $i++)
            {
                $this->clearValidation($this->form->getSubform('outside_agency_'.$i));
            }
		}
		
		// Assign the form to the view
        $this->view->form = $this->form;
        return $this->view->form;	
    }
    protected function createAdditional($newId) {
        
        $models = array('OtherAttendee', 'GenEdTeacher');
        
        foreach ($this->_subformTypes AS $key => $value) {
            $modelName = 'Model_Table_Form029'.$key;
        	$form029Model = new $modelName();
    		$data = array(
        				'id_form_029'       => $newId,
        				'id_author'		=> $this->usersession->sessIdUser,
        				'id_author_last_mod'=>$this->usersession->sessIdUser,
        				'id_student'	=> $this->getRequest()->student,
        	);
        	$form029Model->insert($data);
        }

        /**
         * Pre-populate the parents based on the student info
         */
        $studentObj = new Model_Table_ViewAllStudent();
        $student = $studentObj->find($this->getRequest()->student)->current()->toArray();

        $form029Obj = new Model_Table_Form029;
        $form029 = $form029Obj->find($newId)->current();
        if (!empty($student['parents']))
        {
            $form029->notice_to = $student['parents'];
            $form029->save();
        }

        $absenceObj = new Model_Table_Form029TeamMemberAbsences();
    	$data = array(
    			'id_form_029'       => $newId,
    			'id_author'		=> $this->usersession->sessIdUser,
    			'id_author_last_mod'=>$this->usersession->sessIdUser,
    			'id_student'	=> $this->getRequest()->student,
    	);
    	$absenceObj->insert($data);
    	
    	$contactAttemptsObj = new Model_Table_Form029ContactAttempts();
    	$data = array(
    			'id_form_029'       => $newId,
    			'id_author'		=> $this->usersession->sessIdUser,
    			'id_author_last_mod'=>$this->usersession->sessIdUser,
    			'id_student'	=> $this->getRequest()->student,
    	);
    	$contactAttemptsObj->insert($data);
    }
    protected function buildAdditional($form, $page, $modelData, $config)
    {
    	$subFormsArray = array();
    	switch ($page) {
    		case 1:
    			$credits = 0;
    			foreach ($this->_subformTypes AS $key => $value) {
    				
    				/*
    				 * Add subform
    				 */
	    			if(isset($modelData[$value]['count'])) {
	    				$subFormsArray[] = $this->addSubformSectionNew($form, $modelData[$value]['count'], $config, $value, "Form_Form029".$key, "Model_Table_Form029".$key);
	    			}
	    			/* Clear the validation if its not a combo meeting */	
	    			$count = $modelData[$value]['count'];
	    			for($i=1; $i<=$count; $i++) {
	    			    if (!$modelData['meeting_type_eligible_iep']) {
	    			        $this->clearValidation($form->getSubform($value.'_'.$i));
	    			    }
	    			}
    			}
    			break;
    		default:
    			break;
    	}
    	
    	if($page == 2 && isset($modelData['team_member_absences']['count'])) {
    		$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['team_member_absences']['count'], $config, "team_member_absences", "Form_Form029TeamMemberAbsences", "Model_Table_Form029TeamMemberAbsences");
    	}
    	if($page == 3 && isset($modelData['outside_agency']['count'])) {
    		$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['outside_agency']['count'], $config, "outside_agency", "Form_Form029OutsideAgency", "Model_Table_Form029OutsideAgency");
    	}
    	
        // remove team member absences validation if not used 
        if($page == 2 && true != $modelData['on_off_checkbox']) {
            $count = $modelData['team_member_absences']['count'];
            for($i=1; $i<=$count; $i++)
            {
                $this->clearValidation($form->getSubform('team_member_absences_'.$i));
            }
        }
        
		// remove outside agency validation if not used
		if($page == 3 && true != $modelData['on_off_checkbox_page_4']) {
            $count = $modelData['outside_agency']['count'];
            for($i=1; $i<=$count; $i++)
            {
                $this->clearValidation($form->getSubform('outside_agency_'.$i));
            }
		}
		
		if($page == 4 && isset($modelData['contact_attempts']['count'])) {
			$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['contact_attempts']['count'], $config, "contact_attempts", "Form_Form029ContactAttempts", "Model_Table_Form029ContactAttempts");
		}
    	
    	return $subFormsArray;
    }
    
}
