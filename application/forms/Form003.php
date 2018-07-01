<?php

class Form_Form003 extends Form_AbstractForm {
    
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditor');
	}
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_003 = new App_Form_Element_Hidden('id_form_003');
      	$this->id_form_003->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
		
	public function view_p1_v1() {
		
		$this->initialize();
		
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form003/form003_view_page1_version1.phtml' ) ) ) );

		
		return $this;
	}
	
	public function edit_p1_v1() {
			
		$this->initialize();
        // allow html characters in multioptions and other display
        $this->getView()->setEscape('stripslashes');
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form003/form003_edit_page1_version1.phtml' ) ) ) );
		
        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
        
// 		$this->notice_to = new App_Form_Element_TextareaEditor('notice_to', array('label'=>'Notice To (Parents)'));
		$this->notice_to = $this->buildEditor('notice_to', array('label'=>'Notice To (Parents)'));
		$this->notice_to->setAttrib('style', 'width:98%;');
//        $this->notice_to->reduceTextareaSize();
        $this->notice_to->addDecorator('Label');
		
// 		$this->address = new App_Form_Element_TextareaEditor('address', array('label'=>'Address'));
		$this->address = $this->buildEditor('address', array('label'=>'Address'));
		$this->address->setAttrib('style', 'width:98%;');
//        $this->address->reduceTextareaSize();
        $this->address->addDecorator('Label');
		
// 		$this->iep_meeting = new App_Form_Element_TextareaEditor('iep_meeting', array('label'=>'IEP Meeting', 'description'=>'An Individualized Education Program meeting has been scheduled for <span class="noprint">(proposed meeting date, time and place)</span>:'));
		$this->iep_meeting = $this->buildEditor('iep_meeting', array('label'=>'IEP Meeting', 'description'=>'An Individualized Education Program meeting has been scheduled for <span class="noprint">(proposed meeting date, time and place)</span>:<br/>'));
		$this->iep_meeting->setAttrib ('style', 'width:98%;');
// 		$this->iep_meeting->setDecorators($this->descriptionLeftDijit('iep_meeting'));
		$this->iep_meeting->removeDecorator('label');

		$this->general_ed = new App_Form_Element_Text('general_ed', array('label'=>'General Ed'));
		$this->general_ed->setDecorators($this->descriptionLeft('iep_meeting'));
		$this->general_ed->removeDecorator('label');
		
		$this->special_ed = new App_Form_Element_Text('special_ed', array('label'=>'Special Ed'));
		$this->special_ed->setDecorators($this->descriptionLeft('iep_meeting'));
		$this->special_ed->removeDecorator('label');
		
		$this->school_rep = new App_Form_Element_Text('school_rep', array('label'=>'School Representative'));
		$this->school_rep->setDecorators($this->descriptionLeft('iep_meeting'));
		$this->school_rep->removeDecorator('label');
		
		$this->other_attendees = new App_Form_Element_Text('other_attendees', array('label'=>'Individuals who can help explain the evaluation results'));
		$this->other_attendees->setAttrib('style', 'width:98%;');
		$this->other_attendees->removeDecorator('label');
		
		$this->other_attendees_sp_knowledge = new App_Form_Element_Text('other_attendees_sp_knowledge', array('label'=>'Individuals who have special knowledge or expertise regarding your child or services that may be needed'));
		$this->other_attendees_sp_knowledge->setAttrib('style', 'width:98%;');
		$this->other_attendees_sp_knowledge->setRequired(false);
		$this->other_attendees_sp_knowledge->removeDecorator('label');

//		$this->other_staff = new App_Form_Element_Text('other_staff', array('label'=>'Other Staff'));
//		$this->other_staff->setAttrib('style', 'width:98%;');
		
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
	
	
	public function edit_p2_v1() {
		
		$this->initialize();
	
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form003/form003_edit_page2_version1.phtml' ) ) ) );

		$this->contact_name = new App_Form_Element_Text('contact_name', array('label'=>'Name'));
		
		$this->contact_num = new App_Form_Element_Text('contact_num', array('label'=>'Phone Number'));
		
		$this->rights_contact = new App_Form_Element_Text('rights_contact', array('label'=>'Name'));
		
		$this->rights_contact_num = new App_Form_Element_Text('rights_contact_num', array('label'=>'Phone Number'));
		
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
	
	
	
	public function edit_p3_v1() {
		
		$this->initialize();
		
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form003/form003_edit_page3_version1.phtml' ) ) ) );

		$this->on_off_checkbox = new App_Form_Element_Checkbox('on_off_checkbox', array('label'=>'Check the box to report team member absence(s).'));
        $this->on_off_checkbox->setAttrib('onclick', 'toggleTeamMemberAbsences(this.checked);');
//		$this->on_off_checkbox->setDecorators(array('viewHelper')); // override standard decorators
		
		return $this;
	}
	public function edit_p3_v2() { return $this->edit_p3_v1();}
	public function edit_p3_v3() { return $this->edit_p3_v1();}
	public function edit_p3_v4() { return $this->edit_p3_v1();}
	public function edit_p3_v5() { return $this->edit_p3_v1();}
	public function edit_p3_v6() { return $this->edit_p3_v1();}
	public function edit_p3_v7() { return $this->edit_p3_v1();}
	public function edit_p3_v8() { return $this->edit_p3_v1();}
	public function edit_p3_v9() { return $this->edit_p3_v1();}
		
	
	
	
	
	public function edit_p4_v1() {
		
		$this->initialize();
		
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form003/form003_edit_page4_version1.phtml' ) ) ) );

		$this->on_off_checkbox_page_4 = new App_Form_Element_Checkbox('on_off_checkbox_page_4', array('Description'=>'Check box if you are planning to invite representatives from an outside agency to the IEP meeting for the purpose of post-secondary transition planning.'));
        $this->on_off_checkbox_page_4->setAttrib('onclick', 'toggleOutsideAgency(this.checked);');
        $this->on_off_checkbox_page_4->setDecorators(array(
                                'ViewHelper',
                                array(array('data' => 'HtmlTag')))); 
        $this->on_off_checkbox_page_4->removeDecorator('data');

//		$this->consent_deny = new App_Form_Element_Checkbox('consent_deny', array('label'=>'I do not give consent for the above listed agency representative(s) to be invited to the IEP meeting.'));
//		$this->consent_deny_records = new App_Form_Element_Checkbox('consent_deny_records', array('label'=>'I do not give consent to release the listed educational records.'));

        $this->voluntary_consent = new App_Form_Element_Checkbox('voluntary_consent', array('description'=>'I understand that my consent is voluntary and may be revoked at any time before the identified representative(s) has/have been invited.'));
        $this->voluntary_consent->setDecorators(array(
                                'ViewHelper',
                                array(array('data' => 'HtmlTag'))));         
        $this->voluntary_consent->removeDecorator('data');

		$multiOptions = array('1'=>'Yes', '0'=>'No');
//		$this->p4_signature_on_file = new App_Form_Element_Radio('p4_signature_on_file', array('label'=>'Parent Signature', 'description'=>"(select 'Yes' to indicate signature is on file.)", 'multiOptions'=>$multiOptions));
        $this->p4_signature_on_file = new App_Form_Element_Radio('p4_signature_on_file', array('label'=>'Parent Signature on file:', 'multiOptions'=>$multiOptions));       
        $this->p4_signature_on_file->setDecorators(App_Form_DecoratorHelper::inlineElement());
//        $this->p4_signature_on_file->setSeparator('<br/>');
        $this->p4_signature_on_file->setRequired(false);
		$this->p4_signature_on_file->setAllowEmpty(true);  
		
		$this->p4_parent_date_1 = new App_Form_Element_DatePicker('p4_parent_date_1', array('label'=>'Date of Parent Signature'));        
		$this->p4_parent_date_1->setRequired(false);
        $this->p4_parent_date_1->setAllowEmpty(false);
		$this->p4_parent_date_1->addValidator(new My_Validate_NotEmptyIf('p4_signature_on_file', true));
		$this->p4_parent_date_1->addValidator(new My_Validate_EmptyIf('p4_signature_on_file', false));
		$this->p4_parent_date_1->addErrorMessage('cannot be empty when Parent Signature is Yes and must be empty when Parent Signature is No.');  
		
		$this->p4_signature_on_file_other = new App_Form_Element_Text('p4_signature_on_file_other', array('label'=>"(If 'No' selected above, please explain)"));
		$this->p4_signature_on_file_other->setAttrib('size', '26');
		$this->p4_signature_on_file_other->setRequired(false);
        $this->p4_signature_on_file_other->setAllowEmpty(true);
        
		return $this;
	}
	public function edit_p4_v2() { return $this->edit_p4_v1();}
	public function edit_p4_v3() { return $this->edit_p4_v1();}
	public function edit_p4_v4() { return $this->edit_p4_v1();}
	public function edit_p4_v5() { return $this->edit_p4_v1();}
	public function edit_p4_v6() { return $this->edit_p4_v1();}
	public function edit_p4_v7() { return $this->edit_p4_v1();}
	public function edit_p4_v8() { return $this->edit_p4_v1();}
	public function edit_p4_v9() { return $this->edit_p4_v1();}
	
	
	public function edit_p5_v1() {
		
		$this->initialize();
		
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form003/form003_edit_page5_version1.phtml' ) ) ) ); 

		$this->date_sent = new App_Form_Element_DatePicker('date_sent', array('label'=>'Date Sent'));
		
	    $multiOptions = array('1'=>"I plan to attend the meeting as scheduled.", '0'=>"I am unable to attend the meeting as scheduled and I would like to schedule the meeting at the following date, time and place:");
		$this->attend = new App_Form_Element_Radio('attend', array('label'=>'Attend', 'multiOptions'=>$multiOptions));
		$this->attend->setAttrib('label_class', 'sc_bulletInputLeft');
		$this->attend->setSeparator('<br/>');
		$this->attend->removeDecorator('label');
        $this->attend->addValidator(new My_Validate_BooleanNotEmpty());  
		
// 		$this->schedule_meeting = new App_Form_Element_TextareaEditor('schedule_meeting', array('label'=>'Scheduling Meeting'));
		$this->schedule_meeting = $this->buildEditor('schedule_meeting', array('label'=>'Scheduling Meeting'));
        $this->schedule_meeting->setRequired(false);
        $this->schedule_meeting->setAllowEmpty(false);
        $this->schedule_meeting->removeEditorEmptyValidator();
        $this->schedule_meeting->addValidator(new My_Validate_EditorNotEmptyIf('attend', '0'), true);  
        $this->schedule_meeting->addValidator(new My_Validate_EmptyIf('attend', '1'), true);  
        $this->schedule_meeting->addErrorMessage("Scheduling Meeting must be filled in when you plan NOT to attend the meeting and must NOT be filled in when you plan to attend.");
        
		$this->signature_on_file = new App_Form_Element_Checkbox('signature_on_file', array('label'=>'Signature on file', 'description'=>'(check here to indicate that signature is on file)'));
		$this->signature_on_file->setDecorators($this->checkBoxLeft('signature_on_file'));
// 		$this->signature_on_file->addValidator('GreaterThan', false, array(0));
        $this->signature_on_file->addErrorMessage("must be checked.");
        $this->signature_on_file->addValidator(new My_Validate_BooleanNotEmpty(), true);
        $this->signature_on_file->setAllowEmpty(false);
        
		$this->parent_date_1 = new App_Form_Element_DatePicker('parent_date_1', array('label'=>'Date of Parent Signature'));
		
		$this->school_contact = new App_Form_Element_Text('school_contact', array('label'=>'School Contact'));
		$this->school_contact->setAttrib('style', 'width:250px;');
		
		$this->response_address = new App_Form_Element_Text('response_address', array('label'=>'Response Address'));
		$this->response_address->setAttrib('style', 'width:250px;');
		
		$this->city_state_zip = new App_Form_Element_Text('city_state_zip', array('label'=>'City state zip'));
		$this->city_state_zip->setAttrib('style', 'width:250px;');
		
		$this->school_phone = new App_Form_Element_Text('school_phone', array('label'=>'School Phone'));
		$this->school_phone->setAttrib('style', 'width:250px;');
		
		return $this;
	}
	public function edit_p5_v2() { return $this->edit_p5_v1();}
	public function edit_p5_v3() { return $this->edit_p5_v1();}
	public function edit_p5_v4() { return $this->edit_p5_v1();}
	public function edit_p5_v5() { return $this->edit_p5_v1();}
	public function edit_p5_v6() { return $this->edit_p5_v1();}
	public function edit_p5_v7() { return $this->edit_p5_v1();}
	public function edit_p5_v8() { return $this->edit_p5_v1();}
	public function edit_p5_v9() { return $this->edit_p5_v1();}
	
	public function descriptionLeft($name)
	{
	    $decorators = array(
	    	array('Label', array('tag' => 'span')),
	    	array('Description', array('tag' => 'span')),
	    	'ViewHelper',
	    	array('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $name . '-colorme')), 
	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
	    );	
		return $decorators;
	}
	public function descriptionLeftDijit($name)
	{
	    $decorators = array(
	    	array('Label', array('tag' => 'span')),
	    	array('Description', array('tag' => 'span')),
	    	'DijitElement',
	    	array('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $name . '-colorme')), 
	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
	    );	
		return $decorators;
	}
	public function checkBoxLeft($name)
	{
	    $decorators = array(
	    	array('Label', array('tag' => 'span')),
	    	'ViewHelper',
	    	array('Description', array('tag' => 'span')),
	    	array('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $name . '-colorme')), 
	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
	    );	
		return $decorators;
	}
	
}
