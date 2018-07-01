<?php

class Form028Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 9;
	protected $startPage = 1;
	
	protected $_subformTypes = array(
			//'Parents' => 'parents',
			'MeetingParticipation' => 'meeting_participation'
	);
	
	
    public function init()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
        
        // form parameters
        $this->view->pageCount = 1;
        
        parent::setPrimaryKeyName('id_form_028');
        parent::setFormNumber('028');
        parent::setModelName('Model_Form028');
        parent::setFormClass('Form_Form028');
        parent::setFormTitle('Equitable Service Plan');
        parent::setFormRev('08/08');
    }

    protected function buildSrsForm($document, $page, $raw = false)
    {
		parent::buildSrsForm($document, $page);
		
		// build subforms
		// $this->config set in parent buildSrsForm method
		$subFormBuilder = new App_Form_SubformBuilder($this->config);

        // student_data form used to display the student info header on the top of forms
        $zendSubForm = $subFormBuilder->buildSubform("student_data", "student_data_header");
        $this->form->addSubForm($zendSubForm, "student_data");

		// fill the html form with db data
		$this->form->populate($this->view->db_form_data);

		// Assign the form to the view
        $this->view->form = $this->form;
        return $this->view->form;	
    }
    
    public function buildAdditional(&$form, $page, $modelData, $config)
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
	    				$subFormsArray[] = $this->addSubformSectionNew($form, $modelData[$value]['count'], $config, $value, "Form_Form028".$key, "Model_Table_Form028".$key);
	    			}
    			}
    			break;
    		default:
    			break;
    	}
    	return $subFormsArray;
    }

    protected function createAdditional($newId) {

    	/*
    	$this->getRequest()->student;
        $studentModel = new Model_Table_StudentTable();
        $student = $studentModel->studentInfo($this->getRequest()->student);
        $form027Model = new Model_Table_Form027();
        $form027Model->setDateOfReferral($newId, $student[0]['ei_ref_date']);
        */
    	
        /*
    	 * Build default transcript fields
    	 */
    	$guardianTable = new Model_Table_GuardianTable();
    	$parents = $guardianTable->getParentInfoForStudent($this->getRequest()->student);
    	$form028Parent = new Model_Table_Form028Parents();
    	if (!empty($parents)) {
    		foreach ($parents AS $parent) {
		    	$data = array(
		    			'id_form_028'       => $newId,
		    			'id_author'		=> $this->usersession->sessIdUser,
				    	'id_author_last_mod'=>$this->usersession->sessIdUser,
				    	'id_student'	=> $this->getRequest()->student,
		    			'parent_name' => $parent['name_first'] . ' ' . $parent['name_last'],
		    			'home_phone' => $parent['phone_home'],
		    			'work_phone' => $parent['phone_work'],
		    			'email_address' => $parent['email_address']
		    	);
		    	$form028Parent->insert($data);
    		}
    	}
    	
    	$form028MeetingParticipation = new Model_Table_Form028MeetingParticipation();
    	for ($i=1;$i<=6;$i++) {
    		switch ($i) {
    			case '1':
    				$role = 'Parent/Guardian';
    				break;
    			case '2':
    				$role = 'Non-Public School Representative';
    				break;
    			default:
    				$role = '';
    				break;
    		}
    		$data = array(
    				'id_form_028'       => $newId,
    				'id_author'		=> $this->usersession->sessIdUser,
    				'id_author_last_mod'=>$this->usersession->sessIdUser,
    				'id_student'	=> $this->getRequest()->student,
    				'role' => $role
    		);
    		$form028MeetingParticipation->insert($data);
    	}
    	
    	$this->getRequest()->student;
    	$studentModel = new Model_Table_StudentTable();
    	$student = $studentModel->studentInfo($this->getRequest()->student);
    	$form028Model = new Model_Table_Form028();
    	$form028Model->setField($newId, 'public_school_district_providing_services', $student[0]['name_district']);

    }
}