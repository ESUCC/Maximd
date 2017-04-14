<?php

class Form_Form032ContactAttempts extends Form_AbstractForm {
	
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditor');
	}
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function contact_attempts_edit_version9() {
        return $this->contact_attempts_edit_version1();
    }
    public function contact_attempts_edit_version8() {
        return $this->contact_attempts_edit_version1();
    }
    public function contact_attempts_edit_version7() {
        return $this->contact_attempts_edit_version1();
    }
    public function contact_attempts_edit_version6() {
        return $this->contact_attempts_edit_version1();
    }
    public function contact_attempts_edit_version5() {
        return $this->contact_attempts_edit_version1();
    }
    public function contact_attempts_edit_version4() {
        return $this->contact_attempts_edit_version1();
    }
    public function contact_attempts_edit_version3() {
        return $this->contact_attempts_edit_version1();
    }
    public function contact_attempts_edit_version2() {
        return $this->contact_attempts_edit_version1();
    }
    public function contact_attempts_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form032/contact_attempts_edit_version1.phtml' ) ) ) );
		//
        // these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        //
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;
        
        $this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>' '));
        $this->remove_row->setDecorators ( My_Classes_Decorators::$labelDecoratorsNoSpan);
        $this->remove_row->ignore = true;
		
        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Contact Attempt Row");
                
        $this->id_form_032_contact_attempts = new App_Form_Element_Hidden('id_form_032_contact_attempts', array('label'=>''));
        
		//
        // visible fields
        //
    	$this->contact_attempt_date_attempt = new App_Form_Element_DatePicker('contact_attempt_date_attempt',array('Label' => 'Date of Attempt'));
    	$this->contact_attempt_date_attempt->setRequired(false);
    	$this->contact_attempt_date_attempt->setAllowEmpty(true);
    	$this->contact_attempt_date_attempt->removeDecorator('Label');
    	$this->contact_attempt_date_attempt->setDecorators(My_Classes_Decorators::$emptyDecorators);
        
    	$this->contact_attempt_comments = $this->buildEditor('contact_attempt_comments');
    	$this->contact_attempt_comments->setRequired(false);
    	$this->contact_attempt_comments->setAllowEmpty(true);
    	$this->contact_attempt_comments->removeEditorEmptyValidator();
    	$this->contact_attempt_comments->addErrorMessage('Contact Comments');
		
		return $this;			
	}
	
	// override default
	public function subform_header_edit_version9($subformName, $addNotReq = false) {
	
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form032/contact_attempts_header.phtml' ) ) ) );
	
		// hidden element to tell the system to add a row
		$this->addrow = new App_Form_Element_Hidden('addrow');
	
		// button to call addSubformRow for the subform
		// sets the above to 1 I believe
		$this->add_subform_row= new App_Form_Element_Button('add_subform_row', 'Add Row');
		$this->add_subform_row->setAttrib('onclick', 'addSubformRow(\''.$subformName.'\');');
	
		if($addNotReq) {
			$this->override = new App_Form_Element_Checkbox('override', array('label'=>'Not Required'));
			$this->override->setAttrib('onclick', "override(this.id, this.checked);");
		}
	
		$this->count = new App_Form_Element_Hidden('count');
	
		$this->subformTitle = new Zend_Form_Element_Hidden('subformTitle');
	
		//
		// add hidden elements for subform counts
		//
		$this->subformName = new App_Form_Element_Hidden('subformName', $subformName);
		$this->subformName->setValue($subformName);
		return $this;
	}
	
}

