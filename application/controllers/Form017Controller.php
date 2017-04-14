<?php

class Form017Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 9;
	protected $startPage = 1;
	protected $multipleDrafts = true;
	
    public function init() {
        $this->_redirector = $this->_helper->getHelper('Redirector');

        // form parameters
        $this->view->pageCount = 1;
        
        parent::setPrimaryKeyName('id_form_017');
        parent::setFormNumber('017');
        parent::setModelName('Model_Form017');
        parent::setFormClass('Form_Form017');
        parent::setFormTitle('Notes');
        parent::setFormRev('08/08');
        
//         $this->view->headLink()->appendStylesheet('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css', 'screen', array('id'=>'theme'));
//         $this->view->headLink()->appendStylesheet('/js/temp/jquery.fileupload-ui.css', 'screen');
        
//         $this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
//         $this->view->headScript()->appendFile('http://ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js');
//         $this->view->headScript()->appendFile('/js/temp/jquery.iframe-transport.js');
//         $this->view->headScript()->appendFile('/js/temp/jquery.fileupload.js');
//         $this->view->headScript()->appendFile('/js/temp/jquery.fileupload-ui.js');
//         $this->view->headScript()->appendFile('/js/jquery_addons/jquery-init-fileupload.js');
        
    }
    protected function printAdditional($modelData)
    {
//     	Zend_Debug::dump($modelData);die();
    	parent::setFormTitle($modelData['title']);
    }
    
    
//     protected function buildSrsForm($document, $page)
//     {
// 		parent::buildSrsForm($document, $page);
		
// 		// build subforms
// 		// $this->config set in parent buildSrsForm method
// 		$subFormBuilder = new App_Form_SubformBuilder($this->config);
		
// 		// student_data form used to display the student info header on the top of forms
// 		$zendSubForm = $subFormBuilder->buildSubform("student_data", "student_data_header");
// 		$this->form->addSubForm($zendSubForm, "student_data");
		
// //		if($page == 1) {
// //			$this->addSubformSection("team_member", "Form_Form004TeamMember", "Form004TeamMember");	
// //			$this->addSubformSection("team_other", "Form_Form004TeamOther", "Form004TeamOther");		
// //			$this->addSubformSection("team_district", "Form_Form004TeamDistrict", "Form004TeamDistrict");
// //		}
		
		

// 		// fill the html form with db data
// 		$this->form->populate($this->view->db_form_data);

// 		// Assign the form to the view
//         $this->view->form = $this->form;
//         return $this->view->form;	
//     }
    
}