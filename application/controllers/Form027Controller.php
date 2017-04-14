<?php

class Form027Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 9;
	protected $startPage = 1;
	
    public function init()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
        
        // form parameters
        $this->view->pageCount = 2;
        
        parent::setPrimaryKeyName('id_form_027');
        parent::setFormNumber('027');
        parent::setModelName('Model_Form027');
        parent::setFormClass('Form_Form027');
        parent::setFormTitle('Notice and Consent for Early Intervention Initial Screening');
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
    	switch ($page) {
    		case 2:
    			$districtModel = new Model_Table_District();
    			$district = $districtModel->getDistrict($modelData['id_county'], $modelData['id_district']);
    			$this->view->add_resource1 = $district->add_resource1;
    			$this->view->add_resource2 = $district->add_resource2;
    			break;
    	}
    }

    protected function createAdditional($newId) {

    	$this->getRequest()->student;
        $studentModel = new Model_Table_StudentTable();
        $student = $studentModel->studentInfo($this->getRequest()->student);
        $form027Model = new Model_Table_Form027();
        if (!empty($student[0]['ei_ref_date'])) {
        	$form027Model->setDateOfReferral($newId, $student[0]['ei_ref_date']);
        }
 
    }
}