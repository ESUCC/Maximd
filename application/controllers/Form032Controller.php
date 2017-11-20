<?php
        
class Form032Controller extends My_Form_AbstractFormController {
 	
    protected $identity;
	protected $version = 9;
	protected $startPage = 1;
// Mike added multipleDrafts for wade on SRS-124 10-26-2017 
	protected $multipleDrafts = true;
	
	protected $_subformTypes = array(
			'OtherAttendee' => 'other_attendee'
	);
	
    public function init(){
        $this->_redirector = $this->_helper->getHelper('Redirector');

        // form parameters
        $this->view->pageCount = 2;
        
        parent::setPrimaryKeyName('id_form_032');
        parent::setFormNumber('032');
        parent::setModelName('Model_Form032');
        parent::setFormClass('Form_Form032');
     //   parent::setFormTitle('Notice of Meeting (IFSP)');
        parent::setFormTitle('Notice of Meeting (Part C)');
        parent::setFormRev('08/08');
    }

	protected function buildSrsForm($document, $page, $raw = false)
    {
		parent::buildSrsForm($document, $page);
	
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

		if($page == 2 && $this->useNewFormStructure != true) {
			$this->addSubformSection("outside_agency", "Form_Form032OutsideAgency", "Model_Table_Form032OutsideAgency");
		}
		
		// fill the html form with db data
		$this->form->populate($this->view->db_form_data);
		
		// Assign the form to the view
        $this->view->form = $this->form;
        return $this->view->form;	
    }
    protected function createAdditional($newId) {
    	$form032OtherAttendee = new Model_Table_Form032OtherAttendee();
		$data = array(
    				'id_form_032'       => $newId,
    				'id_author'		=> $this->usersession->sessIdUser,
    				'id_author_last_mod'=>$this->usersession->sessIdUser,
    				'id_student'	=> $this->getRequest()->student,
    	);
    	$form032OtherAttendee->insert($data);

        /**
         * Pre-populate the parents based on the student info
         */
        $studentObj = new Model_Table_ViewAllStudent();
        $student = $studentObj->find($this->getRequest()->student)->current()->toArray();

        $form032Obj = new Model_Table_Form032;
        $form032 = $form032Obj->find($newId)->current();
        if (!empty($student['parents']))
        {
            $form032->notice_to = $student['parents'];
            $form032->save();
        }

        $absenceObj = new Model_Table_Form032TeamMemberAbsences();
    	$data = array(
    			'id_form_032'       => $newId,
    			'id_author'		=> $this->usersession->sessIdUser,
    			'id_author_last_mod'=>$this->usersession->sessIdUser,
    			'id_student'	=> $this->getRequest()->student,
    	);
    	$absenceObj->insert($data);
    	
    	$contactAttemptsObj = new Model_Table_Form032ContactAttempts();
    	$data = array(
    			'id_form_032'       => $newId,
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
	    				$subFormsArray[] = $this->addSubformSectionNew($form, $modelData[$value]['count'], $config, $value, "Form_Form032".$key, "Model_Table_Form032".$key);
	    			}
    			}
    			break;
    		default:
    			break;
    	}
		
		if($page == 2 && isset($modelData['contact_attempts']['count'])) {
			$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['contact_attempts']['count'], $config, "contact_attempts", "Form_Form032ContactAttempts", "Model_Table_Form032ContactAttempts");
		}
    	
    	return $subFormsArray;
    }
    
}
