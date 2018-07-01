<?php

class Form_Form001 extends Form_AbstractForm {
    	
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditor');
	}
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_001 = new App_Form_Element_Hidden('id_form_001');
      	$this->id_form_001->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	
	}
		
	
	public function edit_p1_v1() {
		
		$this->initialize();

		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options:
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form001/form001_edit_page1_version1.phtml' ) ) ) );
		
        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date of Notice'));
        $this->date_notice->boldLabelPrint();
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
        
		$this->explanation = $this->buildEditor('explanation', array('label' => 'Explanation'));
		$this->explanation->addErrorMessage('Explanation of why the district proposes to evaluate your child is empty. If the item does not apply to this student, please explain why.');
		
		$this->options = $this->buildEditor('options', array('label' => 'Options'));
		$this->options->addErrorMessage("Any options the district considered is empty. If the item does not apply to this student, please explain why.");
		
        $this->rejected_reasons = $this->buildEditor('rejected_reasons', array('label' => 'Reasons'));
        $this->rejected_reasons->addErrorMessage("Reasons why the above options were rejected is empty. If the item does not apply to this student, please explain why.");
        
		$this->proposal = $this->buildEditor('proposal', array('label' => 'Proposal'));
		$this->proposal->addErrorMessage("This proposal is based on the following procedures, tests, records or reports described below is empty. If the item does not apply to this student, please explain why.");
		
		$this->other_factors = $this->buildEditor('other_factors', array('label' => 'Other Factors'));
		$this->other_factors->addErrorMessage("Any other factors which are relevant to this proposal is empty. If the item does not apply to this student, please explain why.");
		
		$this->amount_time = $this->buildEditor('amount_time', array('label' => 'Amount Time'));
		$this->amount_time->addErrorMessage("The estimated amount of time for completing the multidisciplinary evaluation and making the verification decision is empty. If the item does not apply to this student, please explain why.");
		
		return $this;
	}
    public function edit_p1_v9_raw() {
        
        $this->initialize();

        //Setting the decorator for the element to a single, ViewScript, decorator,
        //specifying the viewScript as an option, and some extra options:
        $this->setDecorators ( array (array ('ViewScript', array (
                                        'viewScript' => 'form001/form001_edit_page1_version1_raw.phtml' ) ) ) );
        
        $this->date_notice = new App_Form_Relement_DateTextBox('date_notice', array('label' => 'Date of Notice'));
        $this->date_notice->boldLabelPrint();
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
        
        $this->explanation = new App_Form_Relement_Editor('explanation', array('label' => 'Explanation'));
        $this->explanation->addErrorMessage('Explanation of why the district proposes to evaluate your child is empty. If the item does not apply to this student, please explain why.');
        
        $this->options = new App_Form_Relement_Editor('options', array('label' => 'Options'));
        $this->options->addErrorMessage("Any options the district considered is empty. If the item does not apply to this student, please explain why.");
        
        $this->rejected_reasons = new App_Form_Relement_Editor('rejected_reasons', array('label' => 'Reasons'));
        $this->rejected_reasons->addErrorMessage("Reasons why the above options were rejected is empty. If the item does not apply to this student, please explain why.");
        
        $this->proposal = new App_Form_Relement_Editor('proposal', array('label' => 'Proposal'));
        $this->proposal->addErrorMessage("This proposal is based on the following procedures, tests, records or reports described below is empty. If the item does not apply to this student, please explain why.");
        
        $this->other_factors = new App_Form_Relement_Editor('other_factors', array('label' => 'Other Factors'));
        $this->other_factors->addErrorMessage("Any other factors which are relevant to this proposal is empty. If the item does not apply to this student, please explain why.");

        $this->amount_time = new App_Form_Relement_Editor('amount_time', array('label' => 'Amount Time'));
        $this->amount_time->addErrorMessage("The estimated amount of time for completing the multidisciplinary evaluation and making the verification decision is empty. If the item does not apply to this student, please explain why.");
        
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

    public function edit_p1_v2_raw() { return $this->edit_p1_v9_raw();}
    public function edit_p1_v3_raw() { return $this->edit_p1_v9_raw();}
    public function edit_p1_v4_raw() { return $this->edit_p1_v9_raw();}
    public function edit_p1_v5_raw() { return $this->edit_p1_v9_raw();}
    public function edit_p1_v6_raw() { return $this->edit_p1_v9_raw();}
    public function edit_p1_v7_raw() { return $this->edit_p1_v9_raw();}
    public function edit_p1_v8_raw() { return $this->edit_p1_v9_raw();}
    
	public function edit_p2_v1() {
		
		$this->initialize();
				
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options:
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form001/form001_edit_page2_version1.phtml' ) ) ) );
			

		$this->academic_required = new App_Form_Element_Checkbox('academic_required', array('label' => 'Academic'));
		$this->academic_required->boldLabelPrintAndDisplay();
		$this->academic_required->setRequired(false);
        $this->academic_required->setAllowEmpty(true);
		
		$this->academic = $this->buildEditor('academic', array('label' => 'academic'));
		$this->academic->setRequired(false);
        $this->academic->setAllowEmpty(false);
        $this->academic->removeEditorEmptyValidator();
        $this->academic->addValidator(new My_Validate_EditorNotEmptyIf('academic_required', true));
		$this->academic->addErrorMessage('Academic Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
		
		
		$this->intellectual_required = new App_Form_Element_Checkbox('intellectual_required', array('label' => 'Intellectual'));
        $this->intellectual_required->boldLabelPrintAndDisplay();
		$this->intellectual_required->setRequired(false);
        $this->intellectual_required->setAllowEmpty(true);
		
		$this->intellectual = $this->buildEditor('intellectual', array('label' => 'intellectual'));
		$this->intellectual->setRequired(false);
        $this->intellectual->setAllowEmpty(false);
        $this->intellectual->removeEditorEmptyValidator();
        $this->intellectual->addValidator(new My_Validate_EditorNotEmptyIf('intellectual_required', true));
		$this->intellectual->addErrorMessage('Intellectual Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
		
		
		$this->perceptual_required = new App_Form_Element_Checkbox('perceptual_required', array('label' => 'Perceptual and Motor'));
        $this->perceptual_required->boldLabelPrintAndDisplay();
		$this->perceptual_required->addErrorMessage('');
		$this->perceptual_required->setRequired(false);
        $this->perceptual_required->setAllowEmpty(true);
		
		$this->perceptual = $this->buildEditor('perceptual', array('label' => 'perceptual'));
		$this->perceptual->setRequired(false);
        $this->perceptual->setAllowEmpty(false);
        $this->perceptual->removeEditorEmptyValidator();
        $this->perceptual->addValidator(new My_Validate_EditorNotEmptyIf('perceptual_required', true));
		$this->perceptual->addErrorMessage('Perceptual and Motor Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
		
		
		$this->social_required = new App_Form_Element_Checkbox('social_required', array('label' => 'Social and Emotional'));
        $this->social_required->boldLabelPrintAndDisplay();
		$this->social_required->addErrorMessage('');
		$this->social_required->setRequired(false);
        $this->social_required->setAllowEmpty(true);
		
		$this->social = $this->buildEditor('social', array('label' => 'social'));
		$this->social->setRequired(false);
        $this->social->setAllowEmpty(false);
        $this->social->removeEditorEmptyValidator();
        $this->social->addValidator(new My_Validate_EditorNotEmptyIf('social_required', true));
		$this->social->addErrorMessage('Social and Emotional Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
		

		$this->speech_required = new App_Form_Element_Checkbox('speech_required', array('label' => 'Speech and Language'));
        $this->speech_required->boldLabelPrintAndDisplay();
		$this->speech_required->addErrorMessage('');
		$this->speech_required->setRequired(false);
        $this->speech_required->setAllowEmpty(true);
		
		$this->speech = $this->buildEditor('speech', array('label' => 'speech'));
		$this->speech->setRequired(false);
        $this->speech->setAllowEmpty(false);
        $this->speech->removeEditorEmptyValidator();
        $this->speech->addValidator(new My_Validate_EditorNotEmptyIf('speech_required', true));
		$this->speech->addErrorMessage('Speech and Language Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
		

		$this->other_required = new App_Form_Element_Checkbox('other_required', array('label' => 'Other'));
        $this->other_required->boldLabelPrintAndDisplay();
		$this->other_required->addErrorMessage('Other Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
		$this->other_required->setRequired(false);
        $this->other_required->setAllowEmpty(true);
		
		$this->other = $this->buildEditor('other', array('label' => 'other'));
		$this->other->setRequired(false);
        $this->other->setAllowEmpty(false);
        $this->other->removeEditorEmptyValidator();
        $this->other->addValidator(new My_Validate_EditorNotEmptyIf('other_required', true));
		$this->other->addErrorMessage('');
		
		
		$this->contact_name = new App_Form_Element_Text('contact_name', array('label' => 'Name'));
		$this->contact_name->addErrorMessage('Contact name for IDEA safeguards is empty.');
		
		$this->contact_num = new App_Form_Element_Text('contact_num', array('label' => 'Phone Number'));
		$this->contact_num->addErrorMessage('Contact phone for IDEA safeguards is empty.');
		
		return $this;
	}
    public function edit_p2_v9_raw() {
        
        $this->initialize();
                
        //Setting the decorator for the element to a single, ViewScript, decorator,
        //specifying the viewScript as an option, and some extra options:
        $this->setDecorators ( array (array ('ViewScript', array (
                                        'viewScript' => 'form001/form001_edit_page2_version1.phtml' ) ) ) );
            

        $this->academic_required = new App_Form_Relement_Checkbox('academic_required', array('label' => 'Academic'));
        $this->academic_required->boldLabelPrint();
        $this->academic_required->setRequired(false);
        $this->academic_required->setAllowEmpty(true);
        
        $this->academic = new App_Form_Relement_Editor('academic', array('label' => 'academic'));
        $this->academic->setRequired(false);
        $this->academic->setAllowEmpty(false);
        $this->academic->addValidator(new My_Validate_NotEmptyIf('academic_required', true));
        $this->academic->addErrorMessage('Academic Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
        
        
        $this->intellectual_required = new App_Form_Relement_Checkbox('intellectual_required', array('label' => 'Intellectual'));
        $this->intellectual_required->boldLabelPrint();
        $this->intellectual_required->setRequired(false);
        $this->intellectual_required->setAllowEmpty(true);
        
        $this->intellectual = new App_Form_Relement_Editor('intellectual', array('label' => 'intellectual'));
        $this->intellectual->setRequired(false);
        $this->intellectual->setAllowEmpty(false);
        $this->intellectual->addValidator(new My_Validate_NotEmptyIf('intellectual_required', true));
        $this->intellectual->addErrorMessage('Intellectual Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
        
        
        $this->perceptual_required = new App_Form_Relement_Checkbox('perceptual_required', array('label' => 'Perceptual and Motor'));
        $this->perceptual_required->boldLabelPrint();
        $this->perceptual_required->addErrorMessage('');
        $this->perceptual_required->setRequired(false);
        $this->perceptual_required->setAllowEmpty(true);
        
        $this->perceptual = new App_Form_Relement_Editor('perceptual', array('label' => 'perceptual'));
        $this->perceptual->setRequired(false);
        $this->perceptual->setAllowEmpty(false);
        $this->perceptual->addValidator(new My_Validate_NotEmptyIf('perceptual_required', true));
        $this->perceptual->addErrorMessage('Perceptual and Motor Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
        
        
        $this->social_required = new App_Form_Relement_Checkbox('social_required', array('label' => 'Social and Emotional'));
        $this->social_required->boldLabelPrint();
        $this->social_required->addErrorMessage('');
        $this->social_required->setRequired(false);
        $this->social_required->setAllowEmpty(true);
        
        $this->social = new App_Form_Relement_Editor('social', array('label' => 'social'));
        $this->social->setRequired(false);
        $this->social->setAllowEmpty(false);
        $this->social->addValidator(new My_Validate_NotEmptyIf('social_required', true));
        $this->social->addErrorMessage('Social and Emotional Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
        

        $this->speech_required = new App_Form_Relement_Checkbox('speech_required', array('label' => 'Speech and Language'));
        $this->speech_required->boldLabelPrint();
        $this->speech_required->addErrorMessage('');
        $this->speech_required->setRequired(false);
        $this->speech_required->setAllowEmpty(true);
        
        $this->speech = new App_Form_Relement_Editor('speech', array('label' => 'speech'));
        $this->speech->setRequired(false);
        $this->speech->setAllowEmpty(false);
        $this->speech->addValidator(new My_Validate_NotEmptyIf('speech_required', true));
        $this->speech->addErrorMessage('Speech and Language Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
        

        $this->other_required = new App_Form_Relement_Checkbox('other_required', array('label' => 'Other'));
        $this->other_required->boldLabelPrint();
        $this->other_required->addErrorMessage('Other Multidisciplinary Evaluation Description is empty. If the item does not apply to this student, please uncheck the checkbox.');
        $this->other_required->setRequired(false);
        $this->other_required->setAllowEmpty(true);
        
        $this->other = new App_Form_Relement_Editor('other', array('label' => 'other'));
        $this->other->setRequired(false);
        $this->other->setAllowEmpty(false);
        $this->other->addValidator(new My_Validate_NotEmptyIf('other_required', true));
        $this->other->addErrorMessage('');
        
        
        $this->contact_name = new App_Form_Relement_Text('contact_name', array('label' => 'Name'));
        $this->contact_name->addErrorMessage('Contact name for IDEA safeguards is empty.');
        
        $this->contact_num = new App_Form_Relement_Text('contact_num', array('label' => 'Phone Number'));
        $this->contact_num->addErrorMessage('Contact phone for IDEA safeguards is empty.');
        
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
    
    public function edit_p2_v2_raw() { return $this->edit_p2_v9_raw();}
    public function edit_p2_v3_raw() { return $this->edit_p2_v9_raw();}
    public function edit_p2_v4_raw() { return $this->edit_p2_v9_raw();}
    public function edit_p2_v5_raw() { return $this->edit_p2_v9_raw();}
    public function edit_p2_v6_raw() { return $this->edit_p2_v9_raw();}
    public function edit_p2_v7_raw() { return $this->edit_p2_v9_raw();}
    public function edit_p2_v8_raw() { return $this->edit_p2_v9_raw();}
    
    public function edit_p3_v1() {
        
        $this->initialize();
        // allow html characters in multioptions and other display
        $this->getView()->setEscape('stripslashes');
        
        //Setting the decorator for the element to a single, ViewScript, decorator,
        //specifying the viewScript as an option, and some extra options:
        $this->setDecorators ( array (array ('ViewScript', array (
                                        'viewScript' => 'form001/form001_edit_page3_version1.phtml' ) ) ) );
            
        $yes = "I have received a copy of the Notice";
        $no = "I do not give consent";
                
        $multiOptions = array('1'=>$yes, '0'=>$no);
        $this->consent = new App_Form_Element_Radio('consent', array('label'=>'Consent:', 'multiOptions'=>$multiOptions));
        $this->consent->addErrorMessage("Parent consent is empty. If the item does not apply to this student, please explain why.");
	$this->consent->setRequired(true);
	$this->consent->addValidator(new My_Validate_BooleanNotEmpty('consent'));
        
        $this->no_consent_reason = $this->buildEditor('no_consent_reason', array('label'=>"No Consent Reason"));
        $this->no_consent_reason->setRequired(false);
        $this->no_consent_reason->setAllowEmpty(false);
        $this->no_consent_reason->removeEditorEmptyValidator();
        $this->no_consent_reason->addValidator(new My_Validate_EditorNotEmptyIf('consent', 0));
        $this->no_consent_reason->addErrorMessage("The reason for not giving consent to the evaluation is empty. If the item does not apply to this student, please explain why.");
                
        $this->consent_date = new App_Form_Element_DatePicker('consent_date', array('label' => 'Date of Parent Signature:'));

        $this->received_rule_55 = new App_Form_Element_Checkbox('received_rule_55', array('label' => 'I have received a copy of NDE Rule 55 at no cost.'));
        $this->received_rule_55->addErrorMessage("NDE Rule 55 must be given.");
        $this->received_rule_55->addValidator(new Zend_Validate_InArray(array(1)), false);

        $this->signature_on_file = new App_Form_Element_Checkbox('signature_on_file', array('label' => 'Signature on File')); 
        $this->signature_on_file->addErrorMessage("Parent signature must be on file.");
        
        $this->date_district_received = new App_Form_Element_DatePicker('date_district_received', array('label' => 'Date Received by District:'));
    
        return $this;
    }
    public function edit_p3_v9_raw() {
        
        $this->initialize();
        // allow html characters in multioptions and other display
        $this->getView()->setEscape('stripslashes');
        
        //Setting the decorator for the element to a single, ViewScript, decorator,
        //specifying the viewScript as an option, and some extra options:
        $this->setDecorators ( array (array ('ViewScript', array (
                                        'viewScript' => 'form001/form001_edit_page3_version1.phtml' ) ) ) );
            
        $yes = "I have received a copy of the Notice";
        $no = "I have received a copy of the Notice of this proposed evaluation and my 
        parental rights, understand the content of the Notice and <B>do not give 
        consent</B> for the multidisciplinary evaluation specified in this Notice.";
                
        $multiOptions = array('1'=>$yes, '0'=>$no);
        $this->consent = new App_Form_Relement_Radio('consent', array('label'=>'Consent:', 'multiOptions'=>$multiOptions));
        $this->consent->addErrorMessage("Parent consent is empty. If the item does not apply to this student, please explain why.");
        
        $this->no_consent_reason = new App_Form_Relement_Editor('no_consent_reason', array('label'=>"No Consent Reason"));
        $this->no_consent_reason->setRequired(false);
        $this->no_consent_reason->setAllowEmpty(false);
        $this->no_consent_reason->addValidator(new My_Validate_NotEmptyIf('consent', 0));
        $this->no_consent_reason->addErrorMessage("The reason for not giving consent to the evaluation is empty. If the item does not apply to this student, please explain why.");
                
        $this->consent_date = new App_Form_Relement_DateTextBox('consent_date', array('label' => 'Date of Parent Signature:'));

        $this->signature_on_file = new App_Form_Relement_Checkbox('signature_on_file', array('label' => 'Signature on File')); 
        $this->signature_on_file->addErrorMessage("Parent signature must be on file.");
        
        $this->date_district_received = new App_Form_Relement_DateTextBox('date_district_received', array('label' => 'Date Received by District:'));
    
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

    public function edit_p3_v2_raw() { return $this->edit_p3_v9_raw();}
    public function edit_p3_v3_raw() { return $this->edit_p3_v9_raw();}
    public function edit_p3_v4_raw() { return $this->edit_p3_v9_raw();}
    public function edit_p3_v5_raw() { return $this->edit_p3_v9_raw();}
    public function edit_p3_v6_raw() { return $this->edit_p3_v9_raw();}
    public function edit_p3_v7_raw() { return $this->edit_p3_v9_raw();}
    public function edit_p3_v8_raw() { return $this->edit_p3_v9_raw();}
    
}
