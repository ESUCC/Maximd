<?php

class Form_Form032 extends Form_AbstractForm {
    
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditor');
	}
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_032 = new App_Form_Element_Hidden('id_form_032');
      	$this->id_form_032->ignore = true;

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
										'viewScript' => 'form032/form032_edit_page1_version1.phtml' ) ) ) );
		
        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
        
		$this->notice_to = new App_Form_Element_Text('notice_to');
		$this->notice_to->setAttrib('style', 'width:150px;');
		$this->notice_to->removeDecorator('Label');
		$this->notice_to->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'notice_to' . '-colorme') );

		$multiOptions = array(
				'3' => "To review the Multidisciplinary Team (MDT) Report, and determine your child's eligibility for early intervention services. We must meet with you to review these results for your child to determine, with your input, whether your child meets the criteria to qualify for early intervention services. You may bring other individuals with you who are knowledgeable about your child and his or her needs.",
				'2' => "To develop, review and/or revise your child's Individualized Family Service Plan (IFSP). It is very important that you attend this meeting. With your input, we can develop an Individualized Family Service Plan that is appropriate for your child. If you would like, you may review your child's education records prior to the meeting. At the IFSP meeting we will be discussing:",
				'1' => "To develop, review and/or revise your child's [meeting_type_2_menu] Individualized Family Service Plan (IFSP). It is very important that you attend this meeting. With your input, we can develop an Individualized Family Service Plan that is appropriate for your child. If you would like, you may review your child's education records prior to the meeting. At the IFSP meeting we will be discussing:"
		);
		$this->meeting_type = new App_Form_Element_Radio('meeting_type', array('Label'=>'Meeting Type', 'multiOptions'=>$multiOptions));
		$this->meeting_type->setAllowEmpty(false);
		$this->meeting_type->setRequired(true);
		$this->meeting_type->setSeparator('<br/>');
		$this->meeting_type->removeDecorator('Label');
		
		$multiOptions = array(
		    'Initial'=>'Initial',
		    'Periodic'=>' Periodic',
		    'Annual' => 'Annual',
		    'Interim' => 'Interim'
		);
		$this->meeting_type_2_options = new App_Form_Element_Select('meeting_type_2_options');
		$this->meeting_type_2_options->setMultiOptions($multiOptions);
		$this->meeting_type_2_options->removeDecorator('Label');
		$this->meeting_type_2_options->removeDecorator('HtmlTag');
		$this->meeting_type_2_options->setRequired(true);
		$this->meeting_type_2_options->setAllowEmpty(false);
		$this->meeting_type_2_options->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'meeting_type_2_options' . '-colorme') );
		
		
		/* Removing per SRSSUPP-761
		$this->meeting_type_eligible_iep = new App_Form_Element_Checkbox('meeting_type_eligible_iep');
		$this->meeting_type_eligible_iep->addErrorMessage('MDT + IEP/IFSP Combined IEP checkbox must be blank if MDT + IEP/IFSP Combined not selected or IFSP checkbox is checked.');
		$this->meeting_type_eligible_iep->setRequired(false);
		$this->meeting_type_eligible_iep->setAllowEmpty(false);
		$this->meeting_type_eligible_iep->addValidator(new My_Validate_EmptyIfNot('meeting_type', '3'));
		$this->meeting_type_eligible_iep->addValidator(new My_Validate_EmptyIf('meeting_type_eligible_ifsp', 't'));
		$this->meeting_type_eligible_iep->setLabel(" &nbsp; If the team determines that your child is eligible for special education and related services, a meeting will follow to develop your child's Individualized Educaton Plan (IEP). At the IEP meeting we will typically be discussing:");
		$this->meeting_type_eligible_iep->setDescription("<ul>
 				<li>Any special education and related services and supplementary aids and services which your child may require</li>
				<li>Appropriate annual goals</li>
				<li>The extent of your child's participation in the general curriculum and/or necessary modifications.</li>
			</ul>");
			*/
		
		$this->meeting_type_eligible_ifsp = new App_Form_Element_Checkbox('meeting_type_eligible_ifsp');
		$this->meeting_type_eligible_ifsp->addErrorMessage('MDT + IEP/IFSP Combined IFSP checkbox must be blank if MDT + IEP/IFSP Combined not selected or IEP checkbox is checked.');
		$this->meeting_type_eligible_ifsp->setRequired(false);
		$this->meeting_type_eligible_ifsp->setAllowEmpty(false);
		$this->meeting_type_eligible_ifsp->addValidator(new My_Validate_EmptyIfNot('meeting_type', '3'));
		//$this->meeting_type_eligible_ifsp->addValidator(new My_Validate_EmptyIf('meeting_type_eligible_iep', 't'));
		$this->meeting_type_eligible_ifsp->setLabel("[replace_ifsp_label]");
		$this->meeting_type_eligible_ifsp->setDescription("<ul>
 				<li>Your child's present levels of physical, cognitive, communication, social/emotional, and adaptive development;</li>
				<li>Any services which you and/or your child may require as it relates to your child's disability;</li>
				<li>A statement of measureable results and outcomes (including pre-literacy, language, and numeracy if developmentally appropriate); and</li>
				<li>Family concerns, strengths, and priorities.</li>
			</ul>");
		
		/*
		$multiOptions = array(
		    'Initial'=>'Initial',
		    'Periodic'=>' Periodic',
		    'Annual' => 'Annual',
		    'Interim' => 'Interim'
		);
		$this->ifsp_type = new App_Form_Element_Select('ifsp_type');
		$this->ifsp_type->setMultiOptions($multiOptions);
		$this->ifsp_type->removeDecorator('Label');
		$this->ifsp_type->removeDecorator('HtmlTag');
		$this->ifsp_type->setRequired(true);
		$this->ifsp_type->setAllowEmpty(false);
		$this->ifsp_type->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'ifsp_type' . '-colorme') );
        */
		
		/*
		$this->meeting_is_turing_16 = new App_Form_Element_Checkbox('meeting_is_turing_16');
		$this->meeting_is_turing_16->addErrorMessage('Notice of IEP Meeting checkbox must be blank if Notice of IEP Meeting not selected.');
		$this->meeting_is_turing_16->setRequired(false);
		$this->meeting_is_turing_16->setAllowEmpty(false);
		$this->meeting_is_turing_16->addValidator(new My_Validate_EmptyIfNot('meeting_type', '2'));
		//$this->meeting_is_turing_16->addValidator(new My_Validate_EmptyIf('meeting_type_eligible_iep', 't'));
		$this->meeting_is_turing_16->setLabel(" &nbsp; Your child is 16 years old, or will be turning 16 during this IEP period. We must begin to plan for your child's transition from school, and look at postsecondary goals and transition services. To assist in this process, your child has been invited to participate in this IEP meeting and take an active role in determining his/her future. If the child does not attend, steps will be taken to include his/her preferences and interests.");
        */
		
		$this->meeting_transition_conference = new App_Form_Element_Checkbox('meeting_transition_conference');
		$this->meeting_transition_conference->addErrorMessage('Notice of IFSP Meeting checkbox must be blank if Notice of IFSP Meeting not selected.');
		$this->meeting_transition_conference->setRequired(false);
		$this->meeting_transition_conference->setAllowEmpty(false);
		$this->meeting_transition_conference->addValidator(new My_Validate_EmptyIfNot('meeting_type', '1'));
		//$this->meeting_is_turing_16->addValidator(new My_Validate_EmptyIf('meeting_type_eligible_iep', 't'));
		$this->meeting_transition_conference->setLabel(" &nbsp; <strong>This meeting will also serve as a transition conference.</strong>This meeting will also serve as a transition conference. The Services Coordinator, along with the family and IFSP team, must ensure for each toddler with a disability, the transition plan is contained in the IFSP not fewer than 90 days, and at the discretion of all parties, not more than 9 months, before the toddlers third birthday, and includes, as appropriate:");
		$this->meeting_transition_conference->setDescription("<ul>
 				<li>A review of the program options for the toddler with a disability for the period from the toddler's third birthday through the remainder of the school year;</li>
				<li>The family in the development of the transition plan for the child</li>
				<li>Steps for the toddler with a disability and his or her family to exit from the early intervention program to support the smooth transition of the toddler, to include discussions with, and training of, parents, as appropriate, regarding future placements and other matters related to the child's transition; and procedures to prepare the child for changes in service delivery, including steps to help the child adjust to, and function in a new setting;</li>
				<li>Any transition services or other activities that the IFSP Team identifies as needed by the child and family.</li>
		        <li>Confirmation that information about the child has been transmitted to the designated program if parental consent was obtained.</li>
		        <li>Transmission of additional information needed, with parental consent, to ensure continuity of services to the receiving program, including a copy of the most recent evaluation and assessments of the child and family and the most recent IFSP.</li>
			</ul>");
		
		$this->combined_transition_conference = new App_Form_Element_Checkbox('combined_transition_conference');
		$this->combined_transition_conference->addErrorMessage('Transition Conference checkbox may only be checked if MDT + IEP/IFSP Combined and IFSP Meeting selected / checked.');
		$this->combined_transition_conference->setRequired(false);
		$this->combined_transition_conference->setAllowEmpty(false);
		$this->combined_transition_conference->addValidator(new My_Validate_EmptyIfNot('meeting_type', '3'));
		$this->combined_transition_conference->addValidator(new My_Validate_EmptyIfNot('meeting_type_eligible_ifsp', 't'));
        $this->combined_transition_conference->setLabel(" &nbsp; <strong>This meeting will also serve as a transition conference.</strong> The Services Coordinator, along with the family and IFSP team, must ensure for each toddler with a disability, the transition plan is contained in the IFSP not fewer than 90 days, and at the discretion of all parties, not more than 9 months, before the toddlers third birthday, and includes, as appropriate:");
        $this->combined_transition_conference->setDescription("<ul>
 				<li>A review of the program options for the toddler with a disability for the period from the toddler's third birthday through the remainder of the school year;</li>
				<li>The family in the development of the transition plan for the child</li>
				<li>Steps for the toddler with a disability and his or her family to exit from the early intervention program to support the smooth transition of the toddler, to include discussions with, and training of, parents, as appropriate, regarding future placements and other matters related to the child's transition; and procedures to prepare the child for changes in service delivery, including steps to help the child adjust to, and function in a new setting;</li>
				<li>Any transition services or other activities that the IFSP Team identifies as needed by the child and family.</li>
		        <li>Confirmation that information about the child has been transmitted to the designated program if parental consent was obtained.</li>
		        <li>Transmission of additional information needed, with parental consent, to ensure continuity of services to the receiving program, including a copy of the most recent evaluation and assessments of the child and family and the most recent IFSP.</li>
			</ul>");
        
		$this->eval_persons = new App_Form_Element_Text('eval_persons', array('label'=>'Person(s) directly involved in conducting evaluations and assessments:'));
		$this->eval_persons->removeDecorator('label');
		$this->eval_persons->setRequired(false);
		$this->eval_persons->setAllowEmpty(true);
		$this->eval_persons->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'eval_persons' . '-colorme') );
		
		$this->service_coordinator = new App_Form_Element_Text('service_coordinator', array('label'=>'Services Coordinator:'));
		$this->service_coordinator->removeDecorator('label');
		$this->service_coordinator->setRequired(false);
		$this->service_coordinator->setAllowEmpty(true);
		$this->service_coordinator->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'service_coordinator' . '-colorme') );

		$this->erly_int_svcs = new App_Form_Element_Text('erly_int_svcs', array('label'=>'As appropriate, person(s) who will be providing early intervention services to the child or family:'));
		$this->erly_int_svcs->removeDecorator('label');
		$this->erly_int_svcs->setRequired(false);
		$this->erly_int_svcs->setAllowEmpty(true);
		$this->erly_int_svcs->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'erly_int_svcs' . '-colorme') );
		
		$this->other_family_mbr = new App_Form_Element_Text('other_family_mbr', array('label'=>'Other Family Member as Requested by the Parent:'));
		$this->other_family_mbr->removeDecorator('label');
		$this->other_family_mbr->setRequired(false);
		$this->other_family_mbr->setAllowEmpty(true);
		$this->other_family_mbr->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'other_family_mbr' . '-colorme') );
		
		$this->advocate = new App_Form_Element_Text('advocate', array('label'=>'Advocate or person outside of family, as requested by parent:'));
		$this->advocate->removeDecorator('label');
		$this->advocate->setRequired(false);
		$this->advocate->setAllowEmpty(true);
		$this->advocate->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'advocate' . '-colorme') );
		
		
		$this->school_rep = new App_Form_Element_Text('school_rep', array('label'=>'A school representative:'));
		$this->school_rep->removeDecorator('label');
		$this->school_rep->setRequired(false);
		$this->school_rep->setAllowEmpty(true);
		$this->school_rep->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'school_rep' . '-colorme') );
		
		/*
		$this->indv_spc_knowledge = new App_Form_Element_Text('indv_spc_knowledge', array('label'=>'Individuals who have special knowledge or expertise regarding your child or services that may be needed:'));
		$this->indv_spc_knowledge->setAttrib('style', 'width:98%;');
		$this->indv_spc_knowledge->removeDecorator('label');
		$this->indv_spc_knowledge->setRequired(false);
		$this->indv_spc_knowledge->setAllowEmpty(true);
		$this->indv_spc_knowledge->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'indv_spc_knowledge' . '-colorme') );
	    */
	
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
		$this->copy_of_rights->setLabel("Parents of children with a suspected disability have protection under the procedural safeguards of the Individuals with Disabilities Education Act (IDEA). A copy of the \"Part C Procedural Safeguards,\" as well as the procedures for filing a complaint and request for a due process hearing are provided with this notice. You should carefully read the information and, if you have questions regarding your rights, you may contact:");

		$this->rights_name = new App_Form_Element_Text('rights_name');
		$this->rights_name->setAttrib('style', 'width:150px;');
		$this->rights_name->removeDecorator('Label');
		$this->rights_name->setAttrib('placeholder', '[ Name ]');
		$this->rights_name->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'rights_name' . '-colorme') );
		
		$this->rights_phone = new App_Form_Element_Text('rights_phone');
		$this->rights_phone->setAttrib('style', 'width:150px;');
		$this->rights_phone->removeDecorator('Label');
		$this->rights_phone->setAttrib('placeholder', '[ phone or email ]');
		$this->rights_phone->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'rights_phone' . '-colorme') );
		
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
				'viewScript' => 'form032/form032_edit_page2_version1.phtml' ) ) ) );
	
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
	public function edit_p2_v2() { return $this->edit_p2_v1();}
	public function edit_p2_v3() { return $this->edit_p2_v1();}
	public function edit_p2_v4() { return $this->edit_p2_v1();}
	public function edit_p2_v5() { return $this->edit_p2_v1();}
	public function edit_p2_v7() { return $this->edit_p2_v1();}
	public function edit_p2_v8() { return $this->edit_p2_v1();}
	public function edit_p2_v9() { return $this->edit_p2_v1();}
	
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
