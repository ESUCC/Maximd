<?php

class Form_Form031 extends Form_AbstractForm {
    
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_031 = new App_Form_Element_Hidden('id_form_031');
      	$this->id_form_031->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
	public function view_p1_v1() {

		$this->initialize();
		
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form031/form031_view_page1_version1.phtml' ) ) ) );
	
		
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
		
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form031/form031_edit_page1_version1.phtml' ) ) ) );

        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date of Referral'));
        $this->date_notice->setRequired(true);
        $this->date_notice->setAllowEmpty(false);
        $this->date_notice->addErrorMessage("Date of Referral is empty.");

        $this->explanation = $this->buildEditor('explanation', array('Label' =>
            'The district proposes to evaluate your child because:'));
        $this->explanation->setRequired(true);
        $this->explanation->setAllowEmpty(false);
        $this->explanation->addErrorMessage("The district proposes to evaluate your child is empty. " .
            "If the item does not apply to this student, please explain why.");

        $this->academic = new App_Form_Element_TextareaEditor('academic', array('label'=>'Academic'));
        $this->academic->setRequired(true);
        $this->academic->setAllowEmpty(false);
        $this->academic->addErrorMessage("Academic Multidisciplinary Evaluation Description is empty. " .
            "If the item does not apply to this student, please explain why.");

        $this->intellectual = new App_Form_Element_TextareaEditor('intellectual', array('label'=>'Intellectual'));
        $this->intellectual->setRequired(true);
        $this->intellectual->setAllowEmpty(false);
        $this->intellectual->addErrorMessage("Intellectual Multidisciplinary Evaluation Description is empty. " .
            "If the item does not apply to this student, please explain why.");

        $this->perceptual = new App_Form_Element_TextareaEditor('perceptual', array('label'=>'Perceptual'));
        $this->perceptual->setRequired(true);
        $this->perceptual->setAllowEmpty(false);
        $this->perceptual->addErrorMessage("Perceptual and Motor Multidisciplinary Evaluation Description is empty. " .
            "If the item does not apply to this student, please explain why.");

        $this->social = new App_Form_Element_TextareaEditor('social', array('label'=>'Social'));
        $this->social->setRequired(true);
        $this->social->setAllowEmpty(false);
        $this->social->addErrorMessage("Social and Emotional Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please explain why.");

        $this->speech = new App_Form_Element_TextareaEditor('speech', array('label'=>'Speech'));
        $this->speech->setRequired(true);
        $this->speech->setAllowEmpty(false);
        $this->speech->addErrorMessage("Speech and Language Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please explain why.");

        $this->other = new App_Form_Element_TextareaEditor('other', array('label'=>'Other'));
        $this->other->setRequired(true);
        $this->other->setAllowEmpty(false);
        $this->other->addErrorMessage("Other Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please explain why.");

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
        
        //Setting the decorator for the element to a single, ViewScript, decorator,
        //specifying the viewScript as an option, and some extra options: 
        $this->setDecorators ( array (array ('ViewScript', array (
                                        'viewScript' => 'form031/form031_edit_page2_version1.phtml' ) ) ) );

        $this->consent = new App_Form_Element_Radio('consent',
            array(
                'label'=>'Consent:',
                'multiOptions' => App_Form_ValueListHelper::form031Consent(),
            )
        );
        $this->consent->removeDecorator('label');
        $this->consent->addErrorMessage("Parent consent is empty. If the item does not apply to this student, please explain why.");

        $this->no_consent_reason = new App_Form_Element_TextareaEditor('no_consent_reason', array('label'=>"No Consent Reason"));
        $this->no_consent_reason->setRequired(false);
        $this->no_consent_reason->setAllowEmpty(false);
        $this->no_consent_reason->addValidator(new My_Validate_NotEmptyIf('consent', 0));
        $this->no_consent_reason->addErrorMessage("The reason for not giving consent to the evaluation is empty. If the item does not apply to this student, please explain why.");

        $this->signature_on_file = new App_Form_Element_Checkbox('signature_on_file', array('label' => 'Signature', 'description'=>'(check here to indicate that signature is on file'));
        $this->signature_on_file->removeDecorator('label');
        $this->signature_on_file->setRequired(true);
        $this->signature_on_file->setCheckedValue('t');
        $this->signature_on_file->setUncheckedValue('');
        $this->signature_on_file->addErrorMessage("Parent signature must be on file.");

        $this->consent_date = new App_Form_Element_DatePicker('consent_date', array('label' => 'Date of Parent Signature'));
        $this->consent_date->setRequired(true);
        $this->consent_date->setAllowEmpty(false);
        $this->consent_date->addValidator(new My_Validate_EmptyIf('signature_on_file', false));
        $this->consent_date->addValidator(new My_Validate_NotEmptyIf('signature_on_file', true));
        $this->consent_date->addErrorMessage("Date of Parent Signature");

        $this->contact_name = new App_Form_Element_Text('contact_name', array('label'=>'Name'));
        $this->contact_name->setRequired(true);
        $this->contact_name->setAllowEmpty(false);
        $this->contact_name->addErrorMessage("Contact name for IDEA safeguards is empty.");

        $this->contact_num = new App_Form_Element_Text('contact_num', array('label'=>'Phone Number'));
        $this->contact_num->setRequired(true);
        $this->contact_num->setAllowEmpty(false);
        $this->contact_num->addErrorMessage("Contact phone for IDEA safeguards is empty.");

        return $this;
    }
}
