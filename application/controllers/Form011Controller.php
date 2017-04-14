<?php

class Form011Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 9;
	protected $startPage = 1;
	
    public function init() {
        $this->_redirector = $this->_helper->getHelper('Redirector');

        // form parameters
        $this->view->pageCount = 2;
        
        parent::setPrimaryKeyName('id_form_011');
        parent::setFormNumber('011');
        parent::setModelName('Model_Form011');
        parent::setFormClass('Form_Form011');
        parent::setFormTitle('Notification of Multidisciplinary Team (MDT) Conference');
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
    
    protected function createAdditional($newId)
    {
	//Automatically fill in the parent/guardian field in form 11, based on the student's information!
	$studentObj = new Model_Table_ViewAllStudent();
        $student = $studentObj->find($this->getRequest()->student)->current()->toArray();
        $form011Obj = new Model_Table_Form011;
        $current = $form011Obj->find($newId)->current();
        $current->name_list_guardian = $student['parents'];
        $current->save();
    }
    
    
}

