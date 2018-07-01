<?php

class Form_Form030 extends Form_AbstractForm {
    
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_030 = new App_Form_Element_Hidden('id_form_030');
      	$this->id_form_030->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
	public function view_p1_v1() {
		$this->initialize();
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form030/form030_view_page1_version1.phtml' ) ) ) );
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
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form030/form030_edit_page1_version1.phtml' ) ) ) ); 

        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
		
		$this->notice_to = new App_Form_Element_Text('notice_to', array('label'=>'To (Parents)'));
		$this->notice_to->setAllowEmpty(false);
		$this->notice_to->setRequired(true);
		$this->notice_to->setAttrib ( 'style', 'width:97%;' );
		$this->notice_to->addErrorMessage("Notification To parent is empty, please enter the name of the parent for this student.");
		
		$this->address = new App_Form_Element_Text('address', array('label'=>'Address'));
		$this->address->setAttrib ( 'style', 'width:97%;' );
		$this->address->setAllowEmpty(false);
		$this->address->setRequired(true);
		$this->address->addErrorMessage("Notification Address is empty, please enter address of the parent for this student.");
		
		$this->iep_meeting = new App_Form_Element_Text('iep_meeting', 
		  array('label'=>'IEP Meeting', 
		        'description' => 'An Equitable Service Plan meeting has been scheduled for (proposed meeting date, time and place)'
		  )
		);
		$this->iep_meeting->removeDecorator('label');
		$this->iep_meeting->setAttrib ( 'style', 'width:97%;' );
		$this->iep_meeting->getDecorator('description')->setOption('placement', 'prepend');
		$this->iep_meeting->setAllowEmpty(false);
		$this->iep_meeting->setRequired(true);
		$this->iep_meeting->addErrorMessage("Proposed IFSP meeting date, time and place is empty.");
		      
		$this->general_ed = new App_Form_Element_Text('general_ed', array('label'=>'1. Special Education Teacher:'));
		$this->general_ed->setAllowEmpty(false);
		$this->general_ed->setRequired(true);
        $this->general_ed->getDecorator('label')->setOption('class', ''); // stop label from being bold
        $this->general_ed->getDecorator('data')->setOption('class', 'printBold'); // print bold
		$this->general_ed->addErrorMessage("A general education teacher of your child is empty. If the item does not apply to this student, please explain why.");
	
		$this->special_ed = new App_Form_Element_Text('special_ed', array('label'=>'2. Public School Representative:'));
		$this->special_ed->setAllowEmpty(false);
		$this->special_ed->setRequired(true);
        $this->special_ed->getDecorator('label')->setOption('class', ''); // stop label from being bold
		$this->special_ed->addErrorMessage("A special education teacher is empty. If the item does not apply to this student, please explain why.");

		$this->school_rep = new App_Form_Element_Text('school_rep', array('label'=>'3. Nonpublic School Representative:'));
		$this->school_rep->setAllowEmpty(false);
		$this->school_rep->setRequired(true);
        $this->school_rep->getDecorator('label')->setOption('class', ''); // stop label from being bold
		$this->school_rep->addErrorMessage("A school representative is empty. If the item does not apply to this student, please explain why.");

		
		$this->other_attendees = new App_Form_Element_Text('other_attendees', 
		  array(
		      'label'=>'4. Other Attendees',
		      'description'=>' 4. And the following individuals who can help explain the evaluation results and other individuals who have special knowledge or expertise regarding your child or services that may be needed:'
		  )
		);
		$this->other_attendees->removeDecorator('label');
		$this->other_attendees->setAttrib ( 'style', 'width:97%;' );
		$this->other_attendees->getDecorator('description')->setOption('placement', 'prepend');
		$this->other_attendees->setAllowEmpty(false);
		$this->other_attendees->setRequired(true);
		$this->other_attendees->addErrorMessage("The following individuals who can help explain the evaluation results or who have special knowledge or expertise regarding your child or services that may be needed is empty. If the item does not apply to this student, please explain why.");
		
		/*
		$this->other_staff = new App_Form_Element_Text('other_staff',
		  array(
              'label'=>'Other Staff',
              'description'=>'An advocate or person outside of the family and Other family members (if feasible to do so).'
          )
		);
		$this->other_staff->removeDecorator('label');
		$this->other_staff->setAttrib ( 'style', 'width:97%;' );
		$this->other_staff->getDecorator('description')->setOption('placement', 'prepend');
		$this->other_staff->setAllowEmpty(false);
		$this->other_staff->setRequired(true);
		$this->other_staff->addErrorMessage("Their names and the agencies they represent are shown below is empty. If the item does not apply to this student, please explain why.");
		*/
		
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
        $this->setDecorators ( array (array ('ViewScript', array (
                                        'viewScript' => 'form030/form030_edit_page2_version1.phtml' ) ) ) ); 

        $this->contact_name = new App_Form_Element_Text('contact_name', array('label'=>'Services Coordinator/Case Manager'));
	$this->contact_name->setAllowEmpty(false);
	$this->contact_name->setRequired(true);
	$this->contact_name->addErrorMessage("Contact Name is empty. If the item does not apply to this student, please explain why.");
		
        $this->contact_num = new App_Form_Element_Text('contact_num', array('label'=>'Phone Number'));
	$this->contact_num->setAllowEmpty(false);
	$this->contact_num->setRequired(true);
	$this->contact_num->addErrorMessage("Contact Number is empty. If the item does not apply to this student, please explain why.");
		
		/*
		 * Removing Per SRSSUPP-705
		 *
        $this->rights_contact = new App_Form_Element_Text('rights_contact', array('label'=>'Name'));
	$this->rights_contact->setAllowEmpty(false);
	$this->rights_contact->setRequired(true);
	$this->rights_contact->addErrorMessage("Procedural Safeguards to protect parents rights contact name is empty. If the item does not apply to this student, please explain why.");
		
        $this->rights_contact_num = new App_Form_Element_Text('rights_contact_num', array('label'=>'Phone Number'));
	$this->rights_contact_num->setAllowEmpty(false);
	$this->rights_contact_num->setRequired(true);
	$this->rights_contact_num->addErrorMessage("Procedural Safeguards to protect parents rights contact number is empty. If the item does not apply to this student, please explain why.");
		*/
        
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
    public function edit_p3_v1() {
        
        $this->initialize();
        
        // Setting the decorator for the element to a single, ViewScript, decorator,
        // specifying the viewScript as an option, and some extra options: 
        $this->setDecorators ( array (array ('ViewScript', array (
                                        'viewScript' => 'form030/form030_edit_page3_version1.phtml' ) ) ) ); 

        $this->attend = new App_Form_Element_Radio('attend', array('label'=>'Attend'));
        $this->attend->setMultiOptions(App_Form_ValueListHelper::form030Attend());
        $this->attend->setSeparator('<BR/>');
        
        $this->schedule_meeting = new App_Form_Element_TextareaEditor('schedule_meeting', array('label'=>'Recscedule Explanation'));
        $this->schedule_meeting->setRequired(false);
        $this->schedule_meeting->setAllowEmpty(false);
        $this->schedule_meeting->addValidator(new My_Validate_NotEmptyIf('attend', 0));
	$this->schedule_meeting->addErrorMessage("I am unable to attend the meeting and would like to schedule the meeting at the following date, time and place is empty. If the item does not apply to this student, please explain why.");
        
        $this->date_sent = new App_Form_Element_DatePicker('date_sent', array('label'=>'Date Sent'));
	$this->date_sent->setRequired(true);
        $this->date_sent->setAllowEmpty(false);
        $this->date_sent->addErrorMessage("Date Sent is empty.");
	
        $this->parent_date_1 = new App_Form_Element_DatePicker('parent_date_1', array('label'=>'Date of Parent Signature'));
	$this->parent_date_1->setRequired(true);
        $this->parent_date_1->setAllowEmpty(false);
        $this->parent_date_1->addErrorMessage("Parent Signature Date 1 is empty.");
	
        $this->signature_on_file = new App_Form_Element_Checkbox('signature_on_file', array('label'=>'(check here to indicate that signature is on file)'));
	$this->signature_on_file->setRequired(true);
	$this->signature_on_file->setAllowEmpty(false);
        $this->signature_on_file->addErrorMessage("Parent signature must be on file.");
	$this->signature_on_file->setCheckedValue('t');
	$this->signature_on_file->setUncheckedValue('');
		
        $this->school_contact = new App_Form_Element_Text('school_contact', array('label'=>'School Contact'));
        $this->school_contact->setAttrib('style', 'width:250px;');
	$this->school_contact->setRequired(true);
        $this->school_contact->setAllowEmpty(false);
        $this->school_contact->addErrorMessage("School Contact is empty. If the item does not apply to this student, please explain why.");
	
        $this->response_address = new App_Form_Element_Text('response_address', array('label'=>'Response Address'));
        $this->response_address->setAttrib('style', 'width:250px;');
	$this->response_address->setRequired(true);
        $this->response_address->setAllowEmpty(false);
        $this->response_address->addErrorMessage("School Contact Address is empty. If the item does not apply to this student, please explain why.");
	
        $this->city_state_zip = new App_Form_Element_Text('city_state_zip', array('label'=>'City state zip'));
        $this->city_state_zip->setAttrib('style', 'width:250px;');
	$this->city_state_zip->setRequired(true);
        $this->city_state_zip->setAllowEmpty(false);
        $this->city_state_zip->addErrorMessage("School Contact City/State/Zip is empty. If the item does not apply to this student, please explain why.");
	
        $this->school_phone = new App_Form_Element_Text('school_phone', array('label'=>'School Phone'));
        $this->school_phone->setAttrib('style', 'width:250px;');
	$this->school_phone->setRequired(true);
        $this->school_phone->setAllowEmpty(false);
        $this->school_phone->addErrorMessage("School Contact Phone is empty. If the item does not apply to this student, please explain why.");
	
        return $this;
    }

}