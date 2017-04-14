<?php

class Form_Form007 extends Form_AbstractForm {
	
	public function __construct()
	{
        $this->setEditorType('App_Form_Element_TestEditor');
        parent::__construct();

	}
    
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_007 = new App_Form_Element_Hidden('id_form_007');
      	$this->id_form_007->ignore = true;
      	
      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
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
										'viewScript' => 'form007/form007_edit_page1_version1.phtml' ) ) ) );

		
// 		$this->explanation = new App_Form_Element_TextareaEditor('explanation', array('label' => 'Explanation'));
		$this->explanation = $this->buildEditor('explanation', array('label' => 'Explanation'));
		$this->explanation->setRequired(true);
		$this->explanation->setAllowEmpty(false);
		$this->explanation->addErrorMessage("Explanation of why the district proposes to reevaluate your child is empty. If the item does not apply to this student, please explain why.");
		
// 		$this->options = new App_Form_Element_TextareaEditor('options', array('label' => 'Options'));
		$this->options = $this->buildEditor('options', array('label' => 'Options'));
		$this->options->setRequired(true);
		$this->options->setAllowEmpty(false);
		$this->options->addErrorMessage("Any options the district considered is empty. If the item does not apply to this student, please explain why.");
		
// 		$this->reasons = new App_Form_Element_TextareaEditor('reasons', array('label' => 'Reasons'));
		$this->reasons = $this->buildEditor('reasons', array('label' => 'Reasons'));
		$this->reasons->setRequired(true);
		$this->reasons->setAllowEmpty(false);
		$this->reasons->addErrorMessage("Reasons why the above options were rejected is empty. If the item does not apply to this student, please explain why.");
		
// 		$this->proposal = new App_Form_Element_TextareaEditor('proposal', array('label' => 'Proposal'));
		$this->proposal = $this->buildEditor('proposal', array('label' => 'Proposal'));
		$this->proposal->setRequired(true);
		$this->proposal->setAllowEmpty(false);
		$this->proposal->addErrorMessage("This proposal is based on the evaluation procedures, tests, records, or reports described below is empty. If the item does not apply to this student, please explain why.");
		
// 		$this->other_factors = new App_Form_Element_TextareaEditor('other_factors', array('label' => 'Other Factors'));
		$this->other_factors = $this->buildEditor('other_factors', array('label' => 'Other Factors'));
		$this->other_factors->setRequired(true);
		$this->other_factors->setAllowEmpty(false);
		$this->other_factors->addErrorMessage("Any other factors which are relevant to this proposal is empty. If the item does not apply to this student, please explain why.");
		
		$this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
		$this->date_notice->addErrorMessage("Date of Notice is empty.");
		$this->date_notice->setRequired(true);
		$this->date_notice->setAllowEmpty(false);
		
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
		
		//	Setting the decorator for the element to a single, ViewScript, decorator,
		//	specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form007/form007_edit_page2_version1.phtml' ) ) ) );


// 		$this->academic = new App_Form_Element_TextareaEditor('academic', array('label' => 'Academic'));
		$this->academic = $this->buildEditor('academic', array('label' => 'Academic'));
		$this->academic->setRequired(false);
        $this->academic->setAllowEmpty(false);
		$this->academic->addValidator(new My_Validate_NotEmptyIf('academic_required', true));
		
		
// 		$this->intellectual = new App_Form_Element_TextareaEditor('intellectual', array('label' => 'Intellectual'));
		$this->intellectual = $this->buildEditor('intellectual', array('label' => 'Intellectual'));
		$this->intellectual->setRequired(false);
        $this->intellectual->setAllowEmpty(false);
		$this->intellectual->addValidator(new My_Validate_NotEmptyIf('intellectual_required', true));
		$this->intellectual->addErrorMessage('Intellectual Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
		
// 		$this->perceptual = new App_Form_Element_TextareaEditor('perceptual', array('label' => 'Perceptual and Motor'));
		$this->perceptual = $this->buildEditor('perceptual', array('label' => 'Perceptual and Motor'));
		$this->perceptual->setRequired(false);
        $this->perceptual->setAllowEmpty(false);
		$this->perceptual->addValidator(new My_Validate_NotEmptyIf('perceptual_required', true));
		$this->perceptual->addErrorMessage('Perceptual and Motor Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
		
// 		$this->social = new App_Form_Element_TextareaEditor('social', array('label' => 'Social and Emotional'));
		$this->social = $this->buildEditor('social', array('label' => 'Social and Emotional'));
		$this->social->setRequired(false);
        $this->social->setAllowEmpty(false);
		$this->social->addValidator(new My_Validate_NotEmptyIf('social_required', true));
		$this->social->addErrorMessage('Social and Emotional Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
		
// 		$this->speech = new App_Form_Element_TextareaEditor('speech', array('label' => 'Speech and Language'));
		$this->speech = $this->buildEditor('speech', array('label' => 'Speech and Language'));
		$this->speech->setRequired(false);
        $this->speech->setAllowEmpty(false);
		$this->speech->addValidator(new My_Validate_NotEmptyIf('speech_required', true));
		$this->speech->addErrorMessage('Speech and Language Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
		
// 		$this->other = new App_Form_Element_TextareaEditor('other', array('label' => 'Other'));
		$this->other = $this->buildEditor('other', array('label' => 'Other'));
		$this->other->setRequired(false);
        $this->other->setAllowEmpty(false);
		$this->other->addValidator(new My_Validate_NotEmptyIf('other_required', true));
		
		$this->academic_required = new App_Form_Element_Checkbox('academic_required', array('label' => 'Academic'));
		
		$this->intellectual_required = new App_Form_Element_Checkbox('intellectual_required', array('label' => 'Intellectual'));
		
		$this->perceptual_required = new App_Form_Element_Checkbox('perceptual_required', array('label' => 'Perceptual and Motor'));
		
		$this->social_required = new App_Form_Element_Checkbox('social_required', array('label' => 'Social and Emotional'));
		
		$this->speech_required = new App_Form_Element_Checkbox('speech_required', array('label' => 'Speech and Language'));
		
		$this->other_required = new App_Form_Element_Checkbox('other_required', array('label' => 'Other'));
		
		$this->contact_name = new App_Form_Element_Text('contact_name', array('label' => 'Name'));
		$this->contact_name->addErrorMessage("IDEA contact name is empty. If the item does not apply to this student, please explain why.");
		$this->contact_name->setRequired(true);
		$this->contact_name->setAllowEmpty(false);
		
		$this->contact_num = new App_Form_Element_Text('contact_num', array('label' => 'Phone Number'));
		$this->contact_num->addErrorMessage("IDEA contact name is empty. If the item does not apply to this student, please explain why.");
		$this->contact_num->setRequired(true);
		$this->contact_num->setAllowEmpty(false);
		
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
		// allow html characters in multioptions and other display
		$this->getView()->setEscape('stripslashes');
		
		//	Setting the decorator for the element to a single, ViewScript, decorator,
		//	specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form007/form007_edit_page3_version1.phtml' ) ) ) );

		$yes = "I have received a copy of the Notice of this proposed evaluation and my 
		parental rights, understand the content of the Notice and <B>give consent</B> 
		for the multidisciplinary evaluation specified in this Notice. I 
		understand this consent is voluntary and may be revoked at any time.";
		$no = "I have received a copy of the Notice of this proposed evaluation and my 
		parental rights, understand the content of the Notice and <B>do not give 
		consent</B> for the multidisciplinary evaluation specified in this Notice.
		 The reason for not giving consent to the evaluation is:";
		$multiOptions = array('1'=>$yes, '0'=>$no);
		$this->consent = new App_Form_Element_Radio('consent', array('label'=>'Consent:', 'multiOptions'=>$multiOptions));
		$this->consent->removeDecorator('label');
		$this->consent->addErrorMessage("Parent consent is empty. If the item does not apply to this student, please explain why.");
		$this->consent->setAttrib('label_class', 'sc_bulletInputLeft');
		$this->consent->setRequired(true);
		$this->consent->addValidator(new My_Validate_BooleanNotEmpty('consent'));
//		$this->consent->getDecorator('description')->setOption('escape', false);
		
// 		$this->no_consent_reason = new App_Form_Element_TextareaEditor('no_consent_reason', array('label'=>"No Consent Reason"));
		$this->no_consent_reason = $this->buildEditor('no_consent_reason', array('label'=>"No Consent Reason"));
		$this->no_consent_reason->setRequired(false);
		$this->no_consent_reason->setAllowEmpty(false);
		$this->no_consent_reason->addValidator(new My_Validate_NotEmptyIf('consent', 0));
		$this->no_consent_reason->addErrorMessage("The reason for not giving consent to the evaluation is empty. If the item does not apply to this student, please explain why.");
				
		$this->signature_on_file = new App_Form_Element_Checkbox('signature_on_file', array('label' => 'Signature', 'description'=>'(check here to indicate that signature is on file')); 
		$this->signature_on_file->removeDecorator('label');
		$this->signature_on_file->addErrorMessage("Parent signature must be on file.");
		$this->signature_on_file->setRequired(true);
		$this->signature_on_file->setCheckedValue('t');
		$this->signature_on_file->setUncheckedValue('');
		
		$this->consent_date = new App_Form_Element_DatePicker('consent_date', array('label' => 'Date of Parent Signature:'));
		$this->consent_date->setRequired(false);
		$this->consent_date->setAllowEmpty(false);
		$this->consent_date->addValidator(new My_Validate_EmptyIf('signature_on_file', false));
		$this->consent_date->addValidator(new My_Validate_NotEmptyIf('signature_on_file', true));
				
		$this->date_district_received = new App_Form_Element_DatePicker('date_district_received', array('label' => 'Date District Received:'));
		$this->date_district_received->setRequired(true);
		$this->date_district_received->setAllowEmpty(false);
		$this->date_district_received->addErrorMessage("Date received by District is empty.");
		
		return $this;
	}

}