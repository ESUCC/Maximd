<?php

class Form026Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 9;
	protected $startPage = 1;
	
    public function init()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
        
        // form parameters
        $this->view->pageCount = 2;
        
        parent::setPrimaryKeyName('id_form_026');
        parent::setFormNumber('026');
        parent::setModelName('Model_Form026');
        parent::setFormClass('Form_Form026');
        parent::setFormTitle('Revocation of Consent for Special Education and Related Services');
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
    		case 2:
    			$districtModel = new Model_Table_District();
    			$district = $districtModel->getDistrict($modelData['id_county'], $modelData['id_district']);
    			$this->view->add_resource1 = $district->add_resource1;
    			$this->view->add_resource2 = $district->add_resource2;
    			break;
    	}
    }

    protected function createAdditional($newId) {

    	$form026Model = new Model_Table_Form026();
    	$form026Model->defaultTextarea(
    			$newId, 
    			'description_of_the_action', 
    			'All special education services, related services and supplementary aids and services specified in the IEP of the student named above will cease effective upon the issue date of this letter documented above.'
    	);
    	$form026Model->defaultTextarea(
    			$newId,
    			'explanation_of_why',
    			'Consent for all special education services, related services and supplementary aids and services have been revoked per written parent request.'
    	);
    	$form026Model->defaultTextarea(
    			$newId,
    			'options_considered',
    			'No options were considered. This is not a decision of the student\'s IEP team.  This is a unilateral action of the parent(s)/guardian(s) for this student, as authorized by special education regulations.'
    	);
    	$form026Model->defaultTextarea(
    			$newId,
    			'description_of_evaluation',
    			'No data was used as the basis for the proposed action.  This is not a decision of the student\'s IEP team.  This is a unilateral action of the parent(s)/guardian(s) for this student, as authorized by special education regulations.'
    	);
    	$form026Model->defaultTextarea(
    			$newId,
    			'other_factors',
    			'There are no other factors relevant to the proposed action.  This is not a decision of the student\'s IEP team.  This is a unilateral action of the parent(s)/guardian(s) for this student, as authorized by special education regulations.'
    	);
 
    }
}