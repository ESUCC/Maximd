<?php

class Form_Form029 extends Form_AbstractForm {
    
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditor');
	}
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_029 = new App_Form_Element_Hidden('id_form_029');
      	$this->id_form_029->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
	
	public function edit_p1_v1() {
			
		$this->initialize();
        // allow html characters in multioptions and other display
        $this->getView()->setEscape('stripslashes');
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form029/form029_edit_page1_version1.phtml' ) ) ) );
		
        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
        
		$this->notice_to = new App_Form_Element_Text('notice_to');
		$this->notice_to->setAttrib('style', 'width:150px;');
		$this->notice_to->removeDecorator('Label');
		$this->notice_to->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'notice_to' . '-colorme') );

		$multiOptions = array(
				'3' => "To review the Multidisciplinary Team (MDT) Report, and determine your child's eligibility for special education and related services. We must meet with you to review these results for your child to determine, with your input, whether your child meets the criteria to qualify for special education services. It is very important that you attend this meeting. You may bring other individuals with you who are knowledgeable about your child or his or her needs.",
				'2' => "To develop, review and/or revise your child's Individualized Educational Plan (IEP). It is very important that you attend this meeting. With your input, we can develop an individualized education plan that is appropriate for your child. If you would like, you may review your child's education records prior to the meeting. At the IEP meeting we will be discussing:",
				'1' => "To develop, review and/or revise your child's Individualized Family Service Plan (IFSP). Together, we can develop an IFSP that is appropriate for you and your child. At the IFSP meeting we will be discussing:"
		);
		$this->meeting_type = new App_Form_Element_Radio('meeting_type', array('Label'=>'Meeting Type', 'multiOptions'=>$multiOptions));
		$this->meeting_type->setAllowEmpty(false);
		$this->meeting_type->setRequired(true);
		$this->meeting_type->setSeparator('<br/>');
		$this->meeting_type->removeDecorator('Label');
		
		$this->meeting_type_eligible_iep = new App_Form_Element_Checkbox('meeting_type_eligible_iep');
		$this->meeting_type_eligible_iep->addErrorMessage('MDT + IEP/IFSP Combined IEP checkbox must be blank if MDT + IEP/IFSP Combined not selected or IFSP checkbox is checked.');
		$this->meeting_type_eligible_iep->setRequired(false);
		$this->meeting_type_eligible_iep->setAllowEmpty(false);
		$this->meeting_type_eligible_iep->addValidator(new My_Validate_EmptyIfNot('meeting_type', '3'));
		$this->meeting_type_eligible_iep->addValidator(new My_Validate_EmptyIf('meeting_type_eligible_ifsp', 't'));
		// Mike changed 10-23-2017 jira ticket SRS-133 education was spelled Educaton
		$this->meeting_type_eligible_iep->setLabel(" &nbsp; If the team determines that your child is eligible for special education and related services, a meeting will follow to develop your child's Individualized Education Plan (IEP). At the IEP meeting we will typically be discussing:");
		$this->meeting_type_eligible_iep->setDescription("<ul>
 				<li>Any special education and related services and supplementary aids and services which your child may require</li>
				<li>Appropriate annual goals</li>
				<li>The extent of your child's participation in the general curriculum and/or necessary modifications.</li>
			</ul>");
		
		$this->meeting_type_eligible_ifsp = new App_Form_Element_Checkbox('meeting_type_eligible_ifsp');
		$this->meeting_type_eligible_ifsp->addErrorMessage('MDT + IEP/IFSP Combined IFSP checkbox must be blank if MDT + IEP/IFSP Combined not selected or IEP checkbox is checked.');
		$this->meeting_type_eligible_ifsp->setRequired(false);
		$this->meeting_type_eligible_ifsp->setAllowEmpty(false);
		$this->meeting_type_eligible_ifsp->addValidator(new My_Validate_EmptyIfNot('meeting_type', '3'));
		$this->meeting_type_eligible_ifsp->addValidator(new My_Validate_EmptyIf('meeting_type_eligible_iep', 't'));
		$this->meeting_type_eligible_ifsp->setLabel(" &nbsp; If the Team determines that your child is eligible for special education and related services, a meeting will follow to develop your child's Individualized Family Service Plan (IFSP). At the IFSP meeting we will typically be discussing:");
		$this->meeting_type_eligible_ifsp->setDescription("<ul>
 				<li>Your child's present levels of physical, cognitive, communication, social/emotional, and adaptive development;</li>
				<li>Any services which you or your child may require as it relates to your child's disability;</li>
				<li>A statement of measureable results and outcomes (including pre-literacy, language, and numeracy if developmentally appropriate); and</li>
				<li>Family concerns, strengths, and priorities.</li>
			</ul>");
		
		$this->meeting_is_turing_16 = new App_Form_Element_Checkbox('meeting_is_turing_16');
		$this->meeting_is_turing_16->addErrorMessage('Notice of IEP Meeting checkbox must be blank if Notice of IEP Meeting not selected.');
		$this->meeting_is_turing_16->setRequired(false);
		$this->meeting_is_turing_16->setAllowEmpty(false);
		$this->meeting_is_turing_16->addValidator(new My_Validate_EmptyIfNot('meeting_type', '2'));
		//$this->meeting_is_turing_16->addValidator(new My_Validate_EmptyIf('meeting_type_eligible_iep', 't'));
		$this->meeting_is_turing_16->setLabel(" &nbsp; Your child is 16 years old, or will be turning 16 during this IEP period. We must begin to plan for your child's transition from school, and look at postsecondary goals and transition services. To assist in this process, your child has been invited to participate in this IEP meeting and take an active role in determining his/her future. If the child does not attend, steps will be taken to include his/her preferences and interests.");

		$this->meeting_transition_conference = new App_Form_Element_Checkbox('meeting_transition_conference');
		$this->meeting_transition_conference->addErrorMessage('Notice of IFSP Meeting checkbox must be blank if Notice of IFSP Meeting not selected.');
		$this->meeting_transition_conference->setRequired(false);
		$this->meeting_transition_conference->setAllowEmpty(false);
		$this->meeting_transition_conference->addValidator(new My_Validate_EmptyIfNot('meeting_type', '1'));
		//$this->meeting_is_turing_16->addValidator(new My_Validate_EmptyIf('meeting_type_eligible_iep', 't'));
		$this->meeting_transition_conference->setLabel(" &nbsp; <strong>This meeting will also serve as a transition conference.</strong> For toddlers with disabilities ages three or older, the school district or approved cooperative shall ensure a smooth transition to preschool by participating in the transition conference, between the parents and the school district or approved cooperative, not fewer than 90 days and, at the discretion of all parties, not more than 9 months before the child will no longer be eligible to receive, or no longer receives, FAPE early intervention services under this section, to discuss any services that the child may receive under 92 NAC 51 and establishing a transition plan in the IFSP not fewer than 90 days and, at the discretion of all parties, not more than 9 months before the child will no longer be eligible to receive, or no longer receives FAPE early intervention services.");
		
		$this->combined_transition_conference = new App_Form_Element_Checkbox('combined_transition_conference');
		$this->combined_transition_conference->addErrorMessage('Transition Conference checkbox may only be checked if MDT + IEP/IFSP Combined and IFSP Meeting selected / checked.');
		$this->combined_transition_conference->setRequired(false);
		$this->combined_transition_conference->setAllowEmpty(false);
		$this->combined_transition_conference->addValidator(new My_Validate_EmptyIfNot('meeting_type', '3'));
		$this->combined_transition_conference->addValidator(new My_Validate_EmptyIfNot('meeting_type_eligible_ifsp', 't'));
        $this->combined_transition_conference->setLabel(" &nbsp; <strong>This meeting will also serve as a transition conference.</strong> For toddlers with disabilities ages three or older, the school district or approved cooperative shall ensure a smooth transition to preschool by participating in the transition conference, between the parents and the school district or approved cooperative, not fewer than 90 days and, at the discretion of all parties, not more than 9 months before the child will no longer be eligible to receive, or no longer receives, FAPE early intervention services under this section, to discuss any services that the child may receive under 92 NAC 51 and establishing a transition plan in the IFSP not fewer than 90 days and, at the discretion of all parties, not more than 9 months before the child will no longer be eligible to receive, or no longer receives FAPE early intervention services.");
		
		$this->general_ed = new App_Form_Element_Text('general_ed', array('label'=>'A general education teacher of your child:'));
		$this->general_ed->removeDecorator('label');
		$this->general_ed->setRequired(false);
		$this->general_ed->setAllowEmpty(true);
		$this->general_ed->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'general_ed' . '-colorme') );
		
		$this->special_ed = new App_Form_Element_Text('special_ed', array('label'=>'A special education teacher:'));
		$this->special_ed->removeDecorator('label');
		$this->special_ed->setRequired(false);
		$this->special_ed->setAllowEmpty(true);
		$this->special_ed->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'special_ed' . '-colorme') );
		
		$this->school_rep = new App_Form_Element_Text('school_rep', array('label'=>'A school representative:'));
		$this->school_rep->removeDecorator('label');
		$this->school_rep->setRequired(false);
		$this->school_rep->setAllowEmpty(true);
		$this->school_rep->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'school_rep' . '-colorme') );
		
		$this->indv_eval_results = new App_Form_Element_Text('indv_eval_results', array('label'=>'Individuals who can help explain the evaluation results:'));
		$this->indv_eval_results->setAttrib('style', 'width:98%;');
		$this->indv_eval_results->removeDecorator('label');
		$this->indv_eval_results->setRequired(false);
		$this->indv_eval_results->setAllowEmpty(true);
		$this->indv_eval_results->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'indv_eval_results' . '-colorme') );
		
		$this->indv_spc_knowledge = new App_Form_Element_Text('indv_spc_knowledge', array('label'=>'Individuals who have special knowledge or expertise regarding your child or services that may be needed:'));
		$this->indv_spc_knowledge->setAttrib('style', 'width:98%;');
		$this->indv_spc_knowledge->removeDecorator('label');
		$this->indv_spc_knowledge->setRequired(false);
		$this->indv_spc_knowledge->setAllowEmpty(true);
		$this->indv_spc_knowledge->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'indv_spc_knowledge' . '-colorme') );

		$this->service_rep = new App_Form_Element_Text('service_rep', array('label'=>'Service Agency Representative:'));
		$this->service_rep->removeDecorator('label');
		$this->service_rep->setRequired(false);
		$this->service_rep->setAllowEmpty(false);
		$validator = new Zend_Validate();
		$this->service_rep->addValidator(new My_Validate_NotEmptyIf('meeting_type_eligible_ifsp', 't'));
		$this->service_rep->addValidator(new My_Validate_NotEmptyIf('meeting_type', '1'));
		$this->service_rep->addValidator($validator);
		$this->service_rep->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'service_rep' . '-colorme') );
		
		$this->part_c_coordinator = new App_Form_Element_Text('part_c_coordinator', array('label'=>'Part C Coordinator:'));
		$this->part_c_coordinator->removeDecorator('label');
		$this->part_c_coordinator->setRequired(false);
		$this->part_c_coordinator->setAllowEmpty(false);
		$validator = new Zend_Validate();
		$this->part_c_coordinator->addValidator(new My_Validate_NotEmptyIf('meeting_type_eligible_ifsp', 't'));
		$this->part_c_coordinator->addValidator(new My_Validate_NotEmptyIf('meeting_type', '1'));
		$this->part_c_coordinator->addValidator($validator);
		$this->part_c_coordinator->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'part_c_coordinator' . '-colorme') );
		
		$this->meeting_location_date = new App_Form_Element_DatePicker('meeting_location_date', array('label'=>'Date'));
		$this->meeting_location_date->removeDecorator('colorme');
		$this->meeting_location_date->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'meeting_location_date' . '-colorme') );
		$this->meeting_location_date->removeDecorator('Label');
		
		$this->meeting_location_time = new App_Form_Element_Text('meeting_location_time');
		$this->meeting_location_time->setAttrib('style', 'width:150px;');
		$this->meeting_location_time->removeDecorator('Label');
		$this->meeting_location_time->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'meeting_location_time' . '-colorme') );
		
		$this->meeting_location_place = new App_Form_Element_Text('meeting_location_place');
		$this->meeting_location_place->setAttrib('style', 'width:150px;');
		$this->meeting_location_place->removeDecorator('Label');
		$this->meeting_location_place->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'meeting_location_place' . '-colorme') );
		
		$this->change_name = new App_Form_Element_Text('change_name');
		$this->change_name->setAttrib('style', 'width:150px;');
		$this->change_name->removeDecorator('Label');
		$this->change_name->setAttrib('placeholder', '[ Name ]');
		$this->change_name->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'change_name' . '-colorme') );
		
		$this->change_phone_email = new App_Form_Element_Text('change_phone_email');
		$this->change_phone_email->setAttrib('style', 'width:150px;');
		$this->change_phone_email->removeDecorator('Label');
		$this->change_phone_email->setAttrib('placeholder', '[ email or phone number ]');
		$this->change_phone_email->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'change_phone_email' . '-colorme') );
		
		$this->copy_of_rights = new App_Form_Element_Checkbox('copy_of_rights');
		$this->copy_of_rights->setRequired(false);
		$this->copy_of_rights->setAllowEmpty(true);
		$this->copy_of_rights->setLabel("A copy of your <a class=\"printNoLink\" href=\"/pdf/PARENTSRIGHTS.pdf\">Parental Rights</a> is included.  Read them carefully and, if you have any questions regarding your rights, you may contact:");

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
	
	public function edit_p1_v10()
	{
	    $this->edit_p1_v1();
	
	    /**
	     * override view script
	    */
	    $this->setDecorators ( array (array ('ViewScript', array (
	        'viewScript' => 'form029/form029_edit_page1_version10.phtml' ) ) ) );
	    
	    $this->removeElement('meeting_type_eligible_ifsp');
	    
	    $this->meeting_type_eligible_iep->setValidators(array());
	    $this->meeting_type_eligible_iep->addValidator(new My_Validate_EmptyIfNot('meeting_type', '3'));
	    
	    $this->combined_transition_conference->setValidators(array());
	    $this->combined_transition_conference->addValidator(new My_Validate_EmptyIfNot('meeting_type', '3'));
	    
	    $this->removeElement('service_rep');
	    $this->removeElement('part_c_coordinator');
	    $this->removeElement('meeting_transition_conference');
	    $this->removeElement('combined_transition_conference');
	    
	    $multiOptions = array(
	        '3' => "To review the Multidisciplinary Team (MDT) Report, and determine your child's eligibility for special education and related services. We must meet with you to review these results for your child to determine, with your input, whether your child meets the criteria to qualify for special education services. It is very important that you attend this meeting. You may bring other individuals with you who are knowledgeable about your child or his or her needs.",
	        '2' => "To develop, review and/or revise your child's Individualized Educational Plan (IEP). It is very important that you attend this meeting. With your input, we can develop an individualized education plan that is appropriate for your child. If you would like, you may review your child's education records prior to the meeting. You may also bring other individuals with you who are knowledgeable about your child or his or her needs. At the IEP meeting we will be discussing:",
	    );
	    $this->meeting_type->setMultiOptions($multiOptions);
	    
	    $this->meeting_is_turing_16_mdt = new App_Form_Element_Checkbox('meeting_is_turing_16_mdt');
	    $this->meeting_is_turing_16_mdt->addErrorMessage('Notice of IEP Meeting checkbox must be blank if MDT and IEP Combined not selected.');
	    $this->meeting_is_turing_16_mdt->setRequired(false);
	    $this->meeting_is_turing_16_mdt->setAllowEmpty(false);
	    $this->meeting_is_turing_16_mdt->addValidator(new My_Validate_EmptyIfNot('meeting_type', '3'));
	    //$this->meeting_is_turing_16->addValidator(new My_Validate_EmptyIf('meeting_type_eligible_iep', 't'));
	    $this->meeting_is_turing_16_mdt->setLabel(" &nbsp; Your child is 16 years old, or will be turning 16 during this IEP period. We must begin to plan for your child's transition from school, and look at postsecondary goals and transition services. To assist in this process, your child has been invited to participate in this IEP meeting and take an active role in determining his/her future. If the child does not attend, steps will be taken to include his/her preferences and interests.");
	     
	    
	    return $this;	    
	}
	
	public function edit_p1_v11()
	{
	    $this->edit_p1_v10();
	
	    /**
	     * override view script
	    */
	    $this->setDecorators ( array (array ('ViewScript', array (
	        'viewScript' => 'form029/form029_edit_page1_version11.phtml' ) ) ) );
	     
	    return $this;
	}
	
	/**
	 * Removing Per Request From Wade for SRSSUPP-701
	 *
	public function edit_p2_v1() {
	
		$this->initialize();
	
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options:
		$this->setDecorators ( array (array ('ViewScript', array (
				'viewScript' => 'form029/form029_edit_page2_version1.phtml' ) ) ) );
	
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
	*/
	
	
	public function edit_p2_v1() {
	
		$this->initialize();
	
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options:
		$this->setDecorators ( array (array ('ViewScript', array (
				'viewScript' => 'form029/form029_edit_page2_version1.phtml' ) ) ) );
	
		$this->on_off_checkbox = new App_Form_Element_Checkbox('on_off_checkbox', array('label'=>'Check the box to report team member absence(s).'));
		$this->on_off_checkbox->setAttrib('onclick', 'toggleTeamMemberAbsences(this.checked);');
		//		$this->on_off_checkbox->setDecorators(array('viewHelper')); // override standard decorators
	
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
	public function edit_p2_v10() { return $this->edit_p2_v1();}
	public function edit_p2_v11() { return $this->edit_p2_v1();}
	
	
	
	
	public function edit_p3_v1() {
	
		$this->initialize();
	
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options:
		$this->setDecorators ( array (array ('ViewScript', array (
				'viewScript' => 'form029/form029_edit_page3_version1.phtml' ) ) ) );
	
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
	public function edit_p3_v2() { return $this->edit_p3_v1();}
	public function edit_p3_v3() { return $this->edit_p3_v1();}
	public function edit_p3_v4() { return $this->edit_p3_v1();}
	public function edit_p3_v5() { return $this->edit_p3_v1();}
	public function edit_p3_v6() { return $this->edit_p3_v1();}
	public function edit_p3_v7() { return $this->edit_p3_v1();}
	public function edit_p3_v8() { return $this->edit_p3_v1();}
	public function edit_p3_v9() { return $this->edit_p3_v1();}
	public function edit_p3_v10() { return $this->edit_p3_v1();}
	public function edit_p3_v11() { return $this->edit_p3_v1();}
	
	
	public function edit_p4_v1() {
	
		$this->initialize();
	
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options:
		$this->setDecorators ( array (array ('ViewScript', array (
				'viewScript' => 'form029/form029_edit_page4_version1.phtml' ) ) ) );
	
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
		
		$this->on_off_contact_attempts = new App_Form_Element_Checkbox('on_off_contact_attempts', array('Description'=>'Print parent contact documentation?'));
		$this->on_off_contact_attempts->setDecorators(array(
				'ViewHelper',
				array(array('data' => 'HtmlTag'))));
		$this->on_off_contact_attempts->removeDecorator('data');
	
		return $this;
	}
	public function edit_p4_v2() { return $this->edit_p4_v1();}
	public function edit_p4_v3() { return $this->edit_p4_v1();}
	public function edit_p4_v4() { return $this->edit_p4_v1();}
	public function edit_p4_v5() { return $this->edit_p4_v1();}
	public function edit_p4_v7() { return $this->edit_p4_v1();}
	public function edit_p4_v8() { return $this->edit_p4_v1();}
	public function edit_p4_v9() { return $this->edit_p4_v1();}
	public function edit_p4_v11() { return $this->edit_p4_v10();}
	
	public function edit_p4_v10()
	{
	    $this->edit_p4_v1();
	
	    /**
	     * override view script
	    */
	    $this->setDecorators ( array (array ('ViewScript', array (
	        'viewScript' => 'form029/form029_edit_page4_version10.phtml' ) ) ) );
	    
	    $multiOptions = array(
	        '1'=>"I plan to attend the meeting as scheduled.",
	        '0'=>"I am unable to attend the meeting as scheduled and I would like to schedule the meeting at the following date, time and place:",
	    );
	    $this->attend = new App_Form_Element_Radio('attend', array('label'=>'Attend', 'multiOptions'=>$multiOptions));
	    $this->attend->setAttrib('label_class', 'sc_bulletInputLeft');
	    $this->attend->setSeparator('<br/>');
	    $this->attend->removeDecorator('label');
	    $this->attend->setRequired(false);
	    $this->attend->setAllowEmpty(false);
	    $this->attend->addValidator(new My_Validate_BooleanAllowEmptyIf('attend_no_response', true));
	    
	    $multiOptions = array(
	        '1'=>"No Parental Response",);
	    $this->attend_no_response = new App_Form_Element_Radio('attend_no_response', array('label'=>'', 'multiOptions'=>$multiOptions));
	    $this->attend_no_response->setAttrib('label_class', 'sc_bulletInputLeft');
	    $this->attend_no_response->setSeparator('<br/>');
	    $this->attend_no_response->removeDecorator('label');
	    $this->attend_no_response->setRequired(false);
	    $this->attend_no_response->setAllowEmpty(true);
	
		$this->sig_no_explain = $this->buildEditor('sig_no_explain', array('label'=>'(If No selected above, please explain)'));
		$this->sig_no_explain->removeEditorEmptyValidator();
		$this->sig_no_explain->addValidator(new My_Validate_NotEmptyIf('signature_on_file_v10', 'NO'));
		$this->sig_no_explain->setRequired(false);
		$this->sig_no_explain->setAllowEmpty(false);
		$this->sig_no_explain->addErrorMessage("must be filled in when Signature on File is NO.");
		
		$multiOptions = array(
		    'YES' => 'YES',
		    'NO' => 'NO',
		    'Blank' => 'Blank'
		);
		$this->signature_on_file_v10 = new App_Form_Element_Radio('signature_on_file_v10', array('label'=>'Signature on File', 'multiOptions' => $multiOptions));
		$this->signature_on_file_v10->setDecorators($this->checkBoxLeft('signature_on_file_v10'));
		$this->signature_on_file_v10->setSeparator('<br/>');
		$this->signature_on_file_v10->removeDecorator('label');
		$this->signature_on_file_v10->setAllowEmpty(false);
		$this->signature_on_file_v10->setRequired(true);
		$this->signature_on_file_v10->addValidator(new My_Validate_Form029ParentSignature());
		
		$this->parent_date_1->addValidator(new My_Validate_NotEmptyIf('signature_on_file_v10', 'YES'));
		$this->parent_date_1->setRequired(false);
		$this->parent_date_1->setAllowEmpty(false);
		
		return $this;
	}
	
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
