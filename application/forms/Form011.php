<?php
class Form_Form011 extends Form_AbstractForm {
    
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		$this->id_form_011 = new App_Form_Element_Hidden('id_form_011');
      		$this->id_form_011->ignore = true;
      	
      		$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      		$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      		$this->form_editor_type->setRequired(false);
    		$this->form_editor_type->setAllowEmpty(true);
      		$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      		$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
	}
	public function view_p1_v1() {
		$this->initialize();
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form011/form011_view_page1_version1.phtml' ) ) ) );
		return $this;
	}
	
	public function view_p2_v1() {
		$this->initialize();
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form011/form011_view_page2_version1.phtml' ) ) ) );
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
		$this->setDecorators ( array (array ('ViewScript', array ( 'viewScript' => 'form011/form011_edit_page1_version1.phtml' ) ) ) );
		$this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date of Notice'));
		$this->date_notice->addErrorMessage("Date of Notice is empty.");
    
		$this->mdt_conf_date = new App_Form_Element_DatePicker('mdt_conf_date', array('label'=>'Date'));
    		$this->mdt_conf_date->setDecorators(App_Form_DecoratorHelper::inlineElement(false));
		$this->mdt_conf_date->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
		$this->mdt_conf_date->addErrorMessage("Conference Date is empty.");
		$this->mdt_conf_date->setAttrib('wrapped', 'dates_wrapper');
		$this->mdt_conf_date->setRequired(true);
		$this->mdt_conf_date->setAllowEmpty(false);
		$this->mdt_conf_time = new App_Form_Element_TimeTextBox('mdt_conf_time', array('label'=>'Time'));
		$this->mdt_conf_time->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
		$this->mdt_conf_time->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
		$this->mdt_conf_time->setAttrib('constraints' , '{timePattern: "h:mm a", formatLength: "short"}');
		$this->mdt_conf_time->setAttrib('wrapped', 'dates_wrapper');
		$this->mdt_conf_time->setRequired(true);
		$this->mdt_conf_time->setAllowEmpty(false);
		$this->mdt_conf_time->addErrorMessage("Conference Time is empty, please enter the proposed time of the conference.");
		
		$this->mdt_conf_loc = new App_Form_Element_Text('mdt_conf_loc', array('label'=>'Location'));
		$this->mdt_conf_loc->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->mdt_conf_loc->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
		$this->mdt_conf_loc->setAttrib('wrapped', 'dates_wrapper');
		$this->mdt_conf_loc->setRequired(true);
		$this->mdt_conf_loc->setAllowEmpty(false);
		$this->mdt_conf_loc->addErrorMessage("Conference Location is empty, please enter the proposed location of the conference.");
		
		$this->general_ed = new App_Form_Element_Text('general_ed', array('label'=>'1. A general education teacher of your child'));
		$this->general_ed->setRequired(true);
		$this->general_ed->setAllowEmpty(false);
		$this->general_ed->addErrorMessage("A general education teacher of your child is empty. If the item does not apply to this student, please explain why.");
		
		$this->special_ed = new App_Form_Element_Text('special_ed', array('label'=>'2. A special education teacher'));
		$this->special_ed->setRequired(true);
		$this->special_ed->setAllowEmpty(false);
		$this->special_ed->addErrorMessage("A special education teacher is empty. If the item does not apply to this student, please explain why.");
		
		$this->school_rep = new App_Form_Element_Text('school_rep', array('label'=>'3. A school representative'));
		$this->school_rep->setRequired(true);
		$this->school_rep->setAllowEmpty(false);
		$this->school_rep->addErrorMessage("A school representative is empty. If the item does not apply to this student, please explain why.");
		
		$this->other_attendees = new App_Form_Element_TextareaEditor('other_attendees', array('label'=>'Other attendees'));
		$this->other_attendees->setRequired(true);
		$this->other_attendees->setAllowEmpty(false);
		$this->other_attendees->addErrorMessage("The following individuals who can help explain the evaluation results or who have special knowledge or expertise regarding your child or services that may be needed is empty. If the item does not apply to this student, please explain why.");
		
		$this->other_staff = new App_Form_Element_TextareaEditor('other_staff', array('label'=>'Other staff'));
		$this->other_staff->setRequired(false);
		$this->other_staff->setAllowEmpty(false);
		$this->other_staff->addErrorMessage("Others is empty. If the item does not apply to this student, please explain why.");
		
		$this->rights_contact = new App_Form_Element_Text('rights_contact', array('label'=>'Name'));
		$this->rights_contact->setRequired(true);
		$this->rights_contact->setAllowEmpty(false);
		$this->rights_contact->addErrorMessage("Procedural Safeguards to protect parents rights contact name is empty. If the item does not apply to this student, please explain why.");
		
		$this->rights_contact_num = new App_Form_Element_Text('rights_contact_num', array('label'=>'Phone Number'));
		$this->rights_contact_num->setRequired(true);
		$this->rights_contact_num->setAllowEmpty(false);
		$this->rights_contact_num->addErrorMessage("Procedural Safeguards to protect parents rights contact number is empty. If the item does not apply to this student, please explain why.");
		
//		$this->date_sent = new App_Form_Element_DatePicker('date_sent', array('label'=>'Notice provided on'));
//		$this->sender = new App_Form_Element_Text('sender', array('label'=>'Sender'));
		
		return $this;
	}
	
    public function edit_p2_v2() { return $this->edit_p2_v1();}
    public function edit_p2_v3() { return $this->edit_p2_v1();}
    public function edit_p2_v4() { return $this->edit_p2_v1();}
    public function edit_p2_v5() { return $this->edit_p2_v1();}
    public function edit_p2_v6() { return $this->edit_p2_v1();}
    public function edit_p2_v7() { return $this->edit_p2_v1();}
    public function edit_p2_v8() { return $this->edit_p2_v1();}
    public function edit_p2_v9() { return $this->edit_p2_v1();}
	public function edit_p2_v1() {
		
		$this->initialize();
	
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array ( 'viewScript' => 'form011/form011_edit_page2_version1.phtml' ) ) ) );
		
		$this->name_list_guardian = new App_Form_Element_Text('name_list_guardian', array('label'=>'Parent/Guardian'));
		$this->name_list_guardian->setRequired(true);
		$this->name_list_guardian->setAllowEmpty(false);
		$this->name_list_guardian->addErrorMessage("One or more parents must be entered for this student before this form can be finalized.");
		
		$this->date_sent = new App_Form_Element_DatePicker('date_sent', array('label'=>'Date Sent'));
		$this->date_sent->setRequired(true);
		$this->date_sent->setAllowEmpty(false);
		$this->date_sent->addErrorMessage("Date Sent");
		
		$this->contact_name = new App_Form_Element_Text('contact_name', array('label'=>'Contact Person'));
		$this->contact_name->setRequired(true);
		$this->contact_name->setAllowEmpty(false);
		$this->contact_name->addErrorMessage("Contact Name is empty. If the item does not apply to this student, please explain why.");
		
		$this->contact_num = new App_Form_Element_Text('contact_num', array('label'=>'Phone'));
		$this->contact_num->setRequired(true);
		$this->contact_num->setAllowEmpty(false);
		$this->contact_num->addErrorMessage("Contact Number is empty. If the item does not apply to this student, please explain why.");
	
		$this->attend = new App_Form_Element_Radio('attend', array('label'=>'Alternate Assessments:'));
		$this->attend->setMultiOptions($this->valueListHelper->attendResponse());
		$this->attend->removeDecorator('label');
		$this->attend->setSeparator('<BR/>');
		$this->attend->addErrorMessage('You have not indicated that you will or will not attend.');  
		$this->attend ->setRequired(true);
		$this->attend ->setAllowEmpty(false);
		
		$this->pg_invites = new App_Form_Element_TextareaEditor('pg_invites', array('label'=>'Invite'));
		$this->pg_invites->addErrorMessage("You have not listed any invitees. If you have invited no one to attend, please indicate that this is the case.");
		$this->pg_invites->setRequired(true);
		$this->pg_invites->setAllowEmpty(false);
		
		$this->signature_on_file = new App_Form_Element_Checkbox('signature_on_file', array('label'=>'(check here to indicate that signature is on file)'));
		$this->signature_on_file->setRequired(false);
		$this->signature_on_file->setAllowEmpty(false);
		$this->signature_on_file->addErrorMessage("Parent signature must be on file.");
		$this->signature_on_file->setCheckedValue('t');
		$this->signature_on_file->setUncheckedValue('');
		
		$this->parent_date_1 = new App_Form_Element_DatePicker('parent_date_1', array('label'=>'Date of Parent Signature'));
		$this->parent_date_1->setRequired(false);
		$this->parent_date_1->setAllowEmpty(false);
		$this->parent_date_1->addValidator(new My_Validate_NotEmptyIf('signature_on_file', 't'));
		$this->parent_date_1->addErrorMessage("Signature Date");
		
		$this->no_signature_reason = new App_Form_Element_Text('no_signature_reason', array('label'=>'If no signature is on file, explain why'));
		$this->no_signature_reason->setRequired(false);
		$this->no_signature_reason->setAllowEmpty(false);
		$this->no_signature_reason->addValidator(new My_Validate_NotEmptyIf('signature_on_file', ''));
		$this->no_signature_reason->addErrorMessage("Please explain why the parent signature is NOT on file.");
		
		$this->school_contact = new App_Form_Element_Text('school_contact', array('label'=>'School Contact'));
		$this->school_contact->setRequired(true);
		$this->school_contact->setAllowEmpty(false);
		$this->school_contact->addErrorMessage("School Contact is empty. If the item does not apply to this student, please explain why.");
		
		$this->response_address = new App_Form_Element_Text('response_address', array('label'=>'Address'));
		$this->response_address->setRequired(true);
		$this->response_address->setAllowEmpty(false);
		$this->response_address->addErrorMessage("School Contact Address is empty. If the item does not apply to this student, please explain why.");
		
		$this->city_state_zip = new App_Form_Element_Text('city_state_zip', array('label'=>'City/State/Zip'));
		$this->city_state_zip->setRequired(true);
		$this->city_state_zip->setAllowEmpty(false);
		$this->city_state_zip->addErrorMessage("School Contact City/State/Zip is empty. If the item does not apply to this student, please explain why.");
		
		$this->school_phone = new App_Form_Element_Text('school_phone', array('label'=>'Phone'));
		$this->school_phone->setRequired(true);
		$this->school_phone->setAllowEmpty(false);
		$this->school_phone->addErrorMessage("School Contact Phone is empty. If the item does not apply to this student, please explain why.");
		  
		return $this;
	}
}