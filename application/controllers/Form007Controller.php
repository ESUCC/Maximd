<?php
        
class Form007Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 9; 
	protected $startPage = 1;
	
    public function init() {
    //    include("Writeit.php");
       $this->_redirector = $this->_helper->getHelper('Redirector');
     //  writevar($this->_redirector,'this is the redirector');
      
        $this->view->pageCount = 3;
        
        parent::setPrimaryKeyName('id_form_007');
        parent::setFormNumber('007');
        parent::setModelName('Model_Form007');
        parent::setFormClass('Form_Form007');
        parent::setFormTitle('Notice and Consent for Reevaluation');
        parent::setFormRev('09/09');
    }

    protected function buildSrsForm($document, $page, $raw = false)
    {
		parent::buildSrsForm($document, $page);
		$this->formStructure = array(
		    'version' => 2
		);
		
		// build subforms
		// $this->config set in parent buildSrsForm method
		$this->useNewFormStructure = true;
		
		$subFormBuilder = new App_Form_SubformBuilder($this->config);
		
		// student_data form used to display the student info header on the top of forms
		$zendSubForm = $subFormBuilder->buildSubform("student_data", "student_data_header");
		$this->form->addSubForm($zendSubForm, "student_data");
		
//		if($page == 1) {
//			$this->addSubformSection("team_member", "Form_Form004TeamMember", "Form004TeamMember");	
//			$this->addSubformSection("team_other", "Form_Form004TeamOther", "Form004TeamOther");		
//			$this->addSubformSection("team_district", "Form_Form004TeamDistrict", "Form004TeamDistrict");
//		}
		
		

		// fill the html form with db data
		$this->form->populate($this->view->db_form_data);

		// Assign the form to the view
        $this->view->form = $this->form;
        return $this->view->form;	
    }
    
}