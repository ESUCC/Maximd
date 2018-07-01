<?php

class Form_Form012 extends Form_AbstractForm {
    
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_012 = new App_Form_Element_Hidden('id_form_012');
      	$this->id_form_012->ignore = true;
      	
      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
	
	public function view_p1_v1() {
		$this->initialize();
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form012/form012_view_page1_version1.phtml' ) ) ) );
		return $this;
	}
	
    public function edit_p1_v2() { return $this->edit_p1_v1();}
    public function edit_p1_v3() { return $this->edit_p1_v1();}
    public function edit_p1_v4() { return $this->edit_p1_v1();}
    public function edit_p1_v5() { return $this->edit_p1_v1();}
    public function edit_p1_v6() { return $this->edit_p1_v1();}
    public function edit_p1_v7() { return $this->edit_p1_v1();}
    public function edit_p1_v8() { return $this->edit_p1_v1();}
    public function edit_p1_v9() { return $this->edit_p1_v1();}
	public function edit_p1_v1() {
		
		$this->initialize();
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array (
                'ViewScript', array (
				    'viewScript' => 'form012/form012_edit_page1_version1.phtml' 
	            ) 
		)));

        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date of Notice'));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
        
		$this->determination = new App_Form_Element_TextareaEditor('determination');
		$this->determination->addErrorMessage("The Determination is empty. If the item does not apply to this student, please explain why.");
		
        $this->existing_evals = new App_Form_Element_TextareaEditor('existing_evals');
		$this->existing_evals->addErrorMessage("Existing Evaluations is empty. If the item does not apply to this student, please explain why.");
		
        $this->pg_report = new App_Form_Element_TextareaEditor('pg_report');
		$this->pg_report->addErrorMessage("Parent Report is empty. If the item does not apply to this student, please explain why.");
		
        $this->classroom_performance = new App_Form_Element_TextareaEditor('classroom_performance');
		$this->classroom_performance->addErrorMessage("Classroom Performance is empty. If the item does not apply to this student, please explain why.");
		
        $this->actual_achievement = new App_Form_Element_TextareaEditor('actual_achievement');
		$this->actual_achievement->addErrorMessage("Actual Achievement is empty. If the item does not apply to this student, please explain why.");
		
        $this->performance_measurements = new App_Form_Element_TextareaEditor('performance_measurements');
		$this->performance_measurements->addErrorMessage("Performance Measurements is empty. If the item does not apply to this student, please explain why.");
		
        $this->staff_observations = new App_Form_Element_TextareaEditor('staff_observations');
		$this->staff_observations->addErrorMessage("Staff Observations is empty. If the item does not apply to this student, please explain why.");
		
        $this->other_information = new App_Form_Element_TextareaEditor('other_information');
		$this->other_information->addErrorMessage("Other Information is empty. If the item does not apply to this student, please explain why.");
		
        
        $this->contact_name = new App_Form_Element_Text('contact_name', array('label'=>'Name'));
		$this->contact_name->addErrorMessage("Contact Name is empty. If the item does not apply to this student, please explain why.");
		
        $this->contact_num = new App_Form_Element_Text('contact_num', array('label'=>'Number'));
		$this->contact_num->addErrorMessage("Contact Number is empty. If the item does not apply to this student, please explain why.");
		

        $this->date_sent = new App_Form_Element_DatePicker('date_sent', array('label'=>'Date provided'));
        $this->date_sent->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
        $this->date_sent->removeDecorator('label');
//        $this->date_sent->addErrorMessage("Date of Meeting is empty.");
        $this->date_sent->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'provided_wrapper');");
        $this->date_sent->setAttrib('wrapped', 'provided_wrapper');
        
        $this->sender = new App_Form_Element_Text('sender', array('label'=>'Sender'));
        $this->sender->setDecorators($this->decoratorHelper->inlineElement(false));
        $this->sender->removeDecorator('label');
//        $this->sender->addErrorMessage("Date of Meeting is empty.");
        $this->sender->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'provided_wrapper');");
        $this->sender->setAttrib('wrapped', 'provided_wrapper');
        
        $this->agree = new App_Form_Element_Radio('agree', array('label'=>'Agree radio:'));
        $this->agree->setMultiOptions($this->valueListHelper->agreeResponse());
        $this->agree->removeDecorator('label');
        $this->agree->setSeparator('<BR/>');
                        
        $this->signature_on_file = new App_Form_Element_Checkbox('signature_on_file', array('label' => 'Signature', 'description'=>'(check here to indicate that signature is on file')); 
        $this->signature_on_file->removeDecorator('label');
        $this->signature_on_file->addErrorMessage("Parent signature must be on file.");
        
        $this->parent_date_1 = new App_Form_Element_DatePicker('parent_date_1', array('label'=>'Date of Parent Signature'));
        
        $this->no_signature_reason = new App_Form_Element_Text('no_signature_reason', array('label'=>'If no signature is on file, explain why'));
        $this->no_signature_reason->setRequired(false);
        $this->no_signature_reason->setAllowEmpty(false);
        $this->no_signature_reason->addValidator(new My_Validate_NotEmptyIf('signature_on_file', 0));
        $this->no_signature_reason->addErrorMessage("The reason for not giving consent to the evaluation is empty. If the item does not apply to this student, please explain why.");
        
        
        return $this;
	}
}