<?php

class Form025Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 9;
	protected $startPage = 1;
	
    public function init()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
        
        // form parameters
        $this->view->pageCount = 2;
        
        parent::setPrimaryKeyName('id_form_025');
        parent::setFormNumber('025');
        parent::setModelName('Model_Form025');
        parent::setFormClass('Form_Form025');
        parent::setFormTitle('Notification Of Multidisciplinary Team Planning Meeting');
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

    protected function createAdditional($newId) {

        /**
         * helpers
         */
        $modelFormObj = new Model_Table_Form025();
        $parentObj = new Model_Table_GuardianTable();
        $studentObj = new Model_Table_StudentTable();

        // get the form
        $modelForm = $modelFormObj->find($newId)->current();
        $student = $studentObj->find($modelForm->id_student)->current();

        // build student name
        $middle = strlen($student->name_middle)>0?$student->name_middle.' ':'';
        $name_full = $student->name_first . ' ' . $middle . $student->name_last;

        // add initial data
        $modelForm->parent_names = $parentObj->getParentNames($modelForm->id_student);
        $modelForm->student_name = $name_full;
        $modelForm->student_dob = $student->dob;
        $modelForm->save();
    }
}