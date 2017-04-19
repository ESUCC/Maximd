<?php
class Form_Form002 extends Form_AbstractForm {

	public function init()

	{

		$this->setEditorType('App_Form_Element_TestEditor');
	}

	
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_002 = new App_Form_Element_Hidden('id_form_002');
      	$this->id_form_002->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
		
	public function view_page1_version1() {
		
		$this->initialize();
		
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form002/form002_view_page1_version1.phtml' ) ) ) );
	
		
		return $this;
	}
	public function edit_p1_v1() {
		
		$this->initialize();
				
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form002/form002_edit_page1_version1.phtml' ) ) ) );
	
        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
        
        $this->date_mdt = new App_Form_Element_DatePicker('date_mdt', array('label' => 'Date MDT'));
        $this->date_mdt->addErrorMessage("Date of MDT is empty.");
        
        $multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->initial_verification = new App_Form_Element_Radio('initial_verification', array('description'=>'Is this an initial Special Education verification according to 92 NAC 51 (Rule 51) and 92 NAC 52 (Rule 52)?', 'multiOptions'=>$multiOptions));
		$this->initial_verification->getDecorator('description')->setOption('class', 'srsRadioDescription');
		$this->initial_verification->getDecorator('description')->setOption('placement','PREPEND');
        $this->initial_verification->setRequired(false);
        $this->initial_verification->setAllowEmpty(true);
		//$this->initial_verification->removeDecorator('label');
		$this->initial_verification->setAttrib('label_class', 'sc_bulletInputLeft');
        $this->initial_verification->setAttrib('onClick', 'sudoRadioButton(this.id);');
		
		$this->initial_verification_date = new App_Form_Element_DatePicker('initial_verification_date', array('label'=>'Initial Verification Date'));
		$this->initial_verification_date->setRequired(false);
        $this->initial_verification_date->setAllowEmpty(false);
        $this->initial_verification_date->addErrorMessage('Field must be blank if Initial Verification is "Student Has Never Verified" and must be entered if Initial Verification is "Yes" or "No"');
//		$this->initial_verification_date->addValidator(new My_Validate_NotEmptyIfUnless('initial_verification', array('equals'=>1, 'unless'=>array('never_verified'=>1))));
		$this->initial_verification_date->addValidator(new My_Validate_NotEmptyIfAndEmptyIfOther('initial_verification', 'never_verified'));		
		
		$this->never_verified = new App_Form_Element_Radio('never_verified', array('description'=>'Never Verified', 'multiOptions'=>array(0=>'0', 1 =>'1')));
        $this->never_verified->setRequired(false);
        $this->never_verified->setAllowEmpty(true);
        $this->never_verified->addErrorMessage('Value must be left unchecked if answered "Yes" to Initial Verification"');
				
		$this->mdt_00602b1a = new App_Form_Element_Checkbox('mdt_00602b1a', array('label'=>"Testing materials and procedures", 'description'=>'The testing materials and procedures selected and administered were not racially or culturally discriminatory.'));	
		$this->mdt_00602b1a->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b1a->removeDecorator('label');
		
		$option1 = "The MDT evaluation was completed in the language and form most likely to yield accurate information on what the child knows and can do academically, developmentally and functionally.";
		$option2 = "It was not feasible to complete the MDT evaluation in the child's predominant or native language or other mode of communication.";
	    $multiOptions = array('A'=>$option1, 'B'=>$option2);
		$this->mdt_00602b1b = new App_Form_Element_Radio('mdt_00602b1b', array('label'=>'MDT completion', 'multiOptions'=>$multiOptions));
		$this->mdt_00602b1b->setAttrib('label_class', 'sc_bulletInputLeft');
		$this->mdt_00602b1b->setSeparator('<br/>');
		$this->mdt_00602b1b->removeDecorator('label');
		
// 		$this->mdt_00602b1b_text = new App_Form_Element_TestEditor('mdt_00602b1b_text', array('label'=>'Explanation'));
		$this->mdt_00602b1b_text = $this->buildEditor('mdt_00602b1b_text', array('label'=>'Explanation'));
		$this->mdt_00602b1b_text->setRequired(false);
        $this->mdt_00602b1b_text->setAllowEmpty(false);
        $this->mdt_00602b1b_text->removeEditorEmptyValidator();
		$this->mdt_00602b1b_text->addValidator(new My_Validate_EditorNotEmptyIf('mdt_00602b1b', 'B'));
		

		$this->mdt_00602b2 = new App_Form_Element_Checkbox('mdt_00602b2', array('label'=>'mdt_00602b2', 'description'=>"Materials and procedures used to assess a child with limited English proficiency were selected and administered to insure that they measure the extent to which the child has a disability and needs special education, rather than measuring the child's English language skills."));
//		$this->mdt_00602b2->setDecorators($this->checkBoxLeft('mdt_00602b2'));
		$this->mdt_00602b2->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b2->removeDecorator('label');
		
		$this->mdt_00602b3 = new App_Form_Element_Checkbox('mdt_00602b3', 
		  array('label'=>'mdt_00602b3', 
		        'description'=>"A variety of assessment tools and strategies are used to gather relevant functional, developmental and academic information about the child, including information provided by the parent, and information related to enabling the child to be involved in and progress in the general education curriculum (or for a preschool child, to participate in appropriate activities), that may assist in determining whether the child is a child with a disability according to 92 NAC 51 (Rule 51), and the content of the child's IEP or IFSP."));
        $this->mdt_00602b3->getDecorator('description')->setOption('escape', false);
		$this->mdt_00602b3->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b3->removeDecorator('label');
		
		$this->mdt_00602b4 = new App_Form_Element_Checkbox('mdt_00602b4', array('label'=>'mdt_00602b4', 'description'=>"All data information obtained from the parent was considered for the purpose of making the verification decision."));
//		$this->mdt_00602b4->setDecorators($this->checkBoxLeft('mdt_00602b4'));
		$this->mdt_00602b4->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b4->removeDecorator('label');
		
// 		$this->mdt_00602b4_text = new App_Form_Element_TestEditor('mdt_00602b4_text', array('label'=>'Summary of data obtained'));
		$this->mdt_00602b4_text = $this->buildEditor('mdt_00602b4_text', array('label'=>'Summary of data obtained'));
//		$this->mdt_00602b4_text->setDecorators($this->checkBoxLeft('mdt_00602b4_text'));
//		$this->mdt_00602b4_text->removeDecorator('label');
		
		$this->mdt_00602b4a = new App_Form_Element_Checkbox('mdt_00602b4a', array('label'=>'mdt_00602b4a', 'description'=>"Instruments used to complete the MDT evaluation have been validated for the specific purpose for which they were used."));
//		$this->mdt_00602b4a->setDecorators($this->checkBoxLeft('mdt_00602b4a'));
		$this->mdt_00602b4a->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b4a->removeDecorator('label');
		
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
    public function edit_p1_v10() { return $this->edit_p1_v1();}
    
	
	public function edit_p2_v1() {
		
		$this->initialize();
				
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form002/form002_edit_page2_version1.phtml' ) ) ) );

		$this->mdt_00602b4b = new App_Form_Element_Checkbox('mdt_00602b4b', array('description'=>'The assessments are administered by trained and knowledgeable personnel in accordance with any instructions provided by the producer of the assessments.'));
//		$this->mdt_00602b4b->setDecorators($this->checkBoxLeft('mdt_00602b4b'));
        $this->mdt_00602b4b->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b4b->removeDecorator('label');
		
// 		$this->mdt_00602b4b_text = new App_Form_Element_TestEditor('mdt_00602b4b_text', array('label'=>'If the assessment was not conducted under standard conditions, state the description of the extent to which the assessment varied from standard conditions.'));
		$this->mdt_00602b4b_text = $this->buildEditor('mdt_00602b4b_text', array('label'=>'If the assessment was not conducted under standard conditions, state the description of the extent to which the assessment varied from standard conditions.'));
		$this->mdt_00602b4b_text->setRequired(false);
        $this->mdt_00602b4b_text->setAllowEmpty(true);
		
		$this->mdt_00602b1c = new App_Form_Element_Checkbox('mdt_00602b1c', array('description'=>'Assessments and other evaluation materials were used for purposes for which the assessments or measures are valid and reliable.'));
//		$this->mdt_00602b1c->setDecorators($this->checkBoxLeft('mdt_00602b1c'));
		$this->mdt_00602b1c->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b1c->removeDecorator('label');
		
		$this->mdt_00602b5 = new App_Form_Element_Checkbox('mdt_00602b5', array('label'=>'', 'description'=>'Tests and other evaluation materials included those tailored to assess specific areas of educational need and not merely those that are designed to provide a single general intelligence quotient.'));
//		$this->mdt_00602b5->setDecorators($this->checkBoxLeft('mdt_00602b5'));
        $this->mdt_00602b5->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b5->removeDecorator('label');
		
		$this->mdt_00602b6 = new App_Form_Element_Checkbox('mdt_00602b6', array('label'=>'', 'description'=>'Tests were selected and administered so as best to insure that if a test is administered to a child with impaired sensory, manual, or speaking skills, the test results accurately reflect the child\'s aptitude or achievement level or whatever other factors the test purports to measure, rather than reflecting the child\'s impaired sensory, manual, or speaking skills (unless those skills are the factors that the test purports to measure).'));
//		$this->mdt_00602b6->setDecorators($this->checkBoxLeft('mdt_00602b6'));
        $this->mdt_00602b6->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b6->removeDecorator('label');
		
		$this->mdt_00602b7 = new App_Form_Element_Checkbox('mdt_00602b7', array('label'=>'', 'description'=>'No single measure or assessment was used as the sole criterion for determining whether a child is a child with a disability and for determining an appropriate educational program for the child.'));
//		$this->mdt_00602b7->setDecorators($this->checkBoxLeft('mdt_00602b7'));
        $this->mdt_00602b7->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b7->removeDecorator('label');
		
		$this->mdt_00602b8 = new App_Form_Element_Checkbox('mdt_00602b8', array('label'=>'', 'description'=>'The child was assessed in all areas related to the suspected disability, including if appropriate, health, vision, hearing, social and emotional status, general intelligence, academic performance, communicative status, and motor abilities.'));
//		$this->mdt_00602b8->setDecorators($this->checkBoxLeft('mdt_00602b8'));
        $this->mdt_00602b8->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b8->removeDecorator('label');
		
		$this->mdt_00602b9 = new App_Form_Element_Checkbox('mdt_00602b9', array('label'=>'', 'description'=>'The evaluation was sufficiently comprehensive to identify all of the child\'s special education and related services needs, whether or not commonly linked to the disability category in which the child has been classified.'));
//		$this->mdt_00602b9->setDecorators($this->checkBoxLeft('mdt_00602b9'));
        $this->mdt_00602b9->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b9->removeDecorator('label');
		
		$this->mdt_00602b10 = new App_Form_Element_Checkbox('mdt_00602b10', array('label'=>'', 'description'=>'The team used technically sound instruments to assess the relative contribution of cognitive and behavioral factors in addition to physical or development factors.'));
//		$this->mdt_00602b10->setDecorators($this->checkBoxLeft('mdt_00602b10'));
        $this->mdt_00602b10->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b10->removeDecorator('label');
		
		$this->mdt_00602b11 = new App_Form_Element_Checkbox('mdt_00602b11', array('label'=>'', 'description'=>'The team used assessment tools and strategies that provide relevant information that directly assists persons in determining the educational needs of the child.'));
//		$this->mdt_00602b11->setDecorators($this->checkBoxLeft('mdt_00602b11'));
        $this->mdt_00602b11->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602b11->removeDecorator('label');
		
		$this->mdt_00602c1 = new App_Form_Element_Checkbox('mdt_00602c1', array('label'=>'', 'description'=>'Drew upon information from a variety of sources, including aptitude and achievement tests, parent input, teacher recommendations, physical condition, social or cultural background, and adaptive behavior;'));
//		$this->mdt_00602c1->setDecorators($this->checkBoxLeft('mdt_00602c1'));
        $this->mdt_00602c1->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602c1->removeDecorator('label');
		
		$this->mdt_00602c2 = new App_Form_Element_Checkbox('mdt_00602c2', array('label'=>'', 'description'=>'The information obtained from all of these sources was documented and carefully considered.'));
//		$this->mdt_00602c2->setDecorators($this->checkBoxLeft('mdt_00602c2'));
        $this->mdt_00602c2->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00602c2->removeDecorator('label');
		
		$this->mdt_00603c = new App_Form_Element_Checkbox('mdt_00603c', array('label'=>'', 'description'=>'In making a determination of eligibility, a child shall not be determined to be a child with a disability if the determining factor is lack of appropriate instruction in reading, lack of instruction in math, or limited English proficiency.'));
//		$this->mdt_00603c->setDecorators($this->checkBoxLeft('mdt_00603c'));
        $this->mdt_00603c->addValidator(new My_Validate_BooleanTrue());
		$this->mdt_00603c->removeDecorator('label');

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
    
	public function edit_p3_v1() {
		
		$this->initialize();

		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form002/form002_edit_page3_version1.phtml' ) ) ) );

		$a = "A. No disability verified.";
		$b = "B. The child has met the written verification requirements as per one or more of the following:";
	    $multiOptions = array('A'=>$a, 'B'=>$b);
		$this->mdt_00603e2a = new App_Form_Element_Radio('mdt_00603e2a', array('label'=>'mdt_00603e2a', 'multiOptions'=>$multiOptions, 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->mdt_00603e2a->removeDecorator('label');
		//
		// A
		//
		$this->mdt_00603g = new App_Form_Element_DatePicker('mdt_00603g', array('label'=>'Date Referred to SAT'));
		$this->mdt_00603g->setRequired(false);
        $this->mdt_00603g->setAllowEmpty(false);
		$this->mdt_00603g->addValidator(new My_Validate_NotEmptyIfAndEmptyIf('mdt_00603e2a', "A", "B"));
        $this->mdt_00603g->noBoldLabelPrintIndent();
    

		$this->mdt_00603g_contact = new App_Form_Element_Text('mdt_00603g_contact', array('label'=>'SAT Contact Person'));
		$this->mdt_00603g_contact->setRequired(false);
        $this->mdt_00603g_contact->setAllowEmpty(false);
        $this->mdt_00603g_contact->addValidator(new My_Validate_NotEmptyIfAndEmptyIf('mdt_00603e2a', "A", "B"));
        $this->mdt_00603g_contact->noBoldLabelPrintIndent();
		
//		$this->mdt_00603g_contact->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'mdt_00603e2a_B_wrapper');");
		//
		// B
		//
		$this->disability_au = new App_Form_Element_Checkbox('disability_au', array('label'=>'Autism (AU)', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_au->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_au->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
        
		
		$this->disability_bd = new App_Form_Element_Checkbox('disability_bd', array('label'=>'Behavioral Disorder (BD)', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_bd->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_bd->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$this->disability_db = new App_Form_Element_Checkbox('disability_db', array('label'=>'Deaf Blindness (DB)', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_db->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_db->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$this->disability_hi = new App_Form_Element_Checkbox('disability_hi', array('label'=>'Hearing Impairment (HI)', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
        $this->disability_hi->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_hi->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
        
        
		$option1 = "Deaf (Severe Profound)";
		$option2 = "Hard of Hearing (Mild/Moderate)";
	    $multiOptions = array($option1=>$option1, $option2=>$option2);
		$this->disability_hi_detail = new App_Form_Element_Radio('disability_hi_detail', array('label'=>'Hearing Disability', 'multiOptions'=>$multiOptions, 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_hi_detail->setSeparator('<br/>');
		$this->disability_hi_detail->setAttrib('label_placement', "prepend");
		$this->disability_hi_detail->removeDecorator('label');
        $this->disability_hi_detail->setRequired(false);
        $this->disability_hi_detail->setAllowEmpty(false);
        $this->disability_hi_detail->setRegisterInArrayValidator(false);
        $this->disability_hi_detail->addValidator(new My_Validate_NotEmptyIf('disability_hi', true));
        // $this->disability_hi_detail->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
        
		$this->disability_mhmi = new App_Form_Element_Checkbox('disability_mhmi', array('label'=>'Mental Handicap', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_mhmi->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_mhmi->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$this->disability_multi = new App_Form_Element_Checkbox('disability_multi', array('label'=>'Multiple Impairments (MULTI)', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_multi->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_multi->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$this->disability_oi = new App_Form_Element_Checkbox('disability_oi', array('label'=>'Orthopedic Impairment (OI)', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_oi->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_oi->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$this->disability_ohi = new App_Form_Element_Checkbox('disability_ohi', array('label'=>'Other Health Impairment (OHI)', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_ohi->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_ohi->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$this->disability_sld = new App_Form_Element_Checkbox('disability_sld', array('label'=>'Specific Learning Disability (SLD)', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_sld->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_sld->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$this->disability_sli_language = new App_Form_Element_Checkbox('disability_sli_language', array('label'=>'Language', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_sli_language->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_sli_language->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$this->disability_sli_articulation = new App_Form_Element_Checkbox('disability_sli_articulation', array('label'=>'Articulation', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_sli_articulation->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_sli_articulation->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$this->disability_sli_voice = new App_Form_Element_Checkbox('disability_sli_voice', array('label'=>'Voice', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_sli_voice->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_sli_voice->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$this->disability_sli_fluency = new App_Form_Element_Checkbox('disability_sli_fluency', array('label'=>'Fluency', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_sli_fluency->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_sli_fluency->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$this->disability_tbi = new App_Form_Element_Checkbox('disability_tbi', array('label'=>'Traumatic Brain Injury (TBI)', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
        $this->disability_tbi->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_tbi->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
        

		$this->disability_vi_checkbox = new App_Form_Element_Checkbox('disability_vi_checkbox', array('label'=>'Visual Impairment (VI) in the area of:', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_vi_checkbox->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_vi_checkbox->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$multiOptions = array("Blind"=>"Blind", "Legally Blind"=>"Legally Blind", "Partially Sighted"=>"Partially Sighted");
		$this->disability_vi = new App_Form_Element_Radio('disability_vi', array('label'=>'Visual Impairment', 'multiOptions'=>$multiOptions, 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_vi->setSeparator('<br/>');
		$this->disability_vi->setAttrib('label_placement', "prepend");
		$this->disability_vi->removeDecorator('label');
        $this->disability_vi->setRequired(false);
        $this->disability_vi->setAllowEmpty(false);
        $this->disability_vi->setRegisterInArrayValidator(false);
        $this->disability_vi->addValidator(new My_Validate_NotEmptyIf('disability_vi_checkbox', true));
        // $this->disability_vi->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
        
		$this->disability_dd = new App_Form_Element_Checkbox('disability_dd', array('label'=>'Developmental Delay (DD)', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
		$this->disability_dd->getDecorator('label')->setOption('placement', 'prepend');
        $this->disability_dd->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$this->educationalneeds_ddview = new App_Form_Element_Checkbox('educationalneeds_ddview', array('label'=>'Click Here to View Developmental Areas'));
		$this->educationalneeds_ddview->setAttrib('onclick', "toggleShowDevelopmentalAreas();");
        /*
        $this->educationalneeds_ddview->addErrorMessage(':  If "Verification Decision" is "B", then value is required.  If "Verification Decision" is "A", value must be blank.');
        $this->educationalneeds_ddview->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
        */
		
// 		$this->educationalneeds_cognitive_dev = new App_Form_Element_TestEditor('educationalneeds_cognitive_dev', array('label'=>'1. Cognitive Development'));
		$this->educationalneeds_cognitive_dev = $this->buildEditor('educationalneeds_cognitive_dev', array('label'=>'1. Cognitive Development'));
		$this->educationalneeds_cognitive_dev->setRequired(false);
        $this->educationalneeds_cognitive_dev->setAllowEmpty(false);
        $this->educationalneeds_cognitive_dev->removeEditorEmptyValidator();
        //$this->educationalneeds_cognitive_dev->setRegisterInArrayValidator(false);
		$this->educationalneeds_cognitive_dev->addValidator(new My_Validate_EditorNotEmptyIf('educationalneeds_ddview', "t"), true);
		
// 		$this->educationalneeds_fine_dev = new App_Form_Element_TestEditor('educationalneeds_fine_dev', array('label'=>'2. Fine and Gross Motor Development'));
		/*
		 * Mike Change 4-28-2017 per jira SRS-52
		$this->educationalneeds_fine_dev = $this->buildEditor('educationalneeds_fine_dev', array('label'=>'2. Fine and Gross Motor Development'));
		*/
		$this->educationalneeds_fine_dev = $this->buildEditor('educationalneeds_fine_dev', array('label'=>'2. Physical Development'));
		
		$this->educationalneeds_fine_dev->setRequired(false);
        $this->educationalneeds_fine_dev->setAllowEmpty(false);
        $this->educationalneeds_fine_dev->removeEditorEmptyValidator();
		$this->educationalneeds_fine_dev->addValidator(new My_Validate_EditorNotEmptyIf('educationalneeds_ddview', "t"), true);
		
// 		$this->educationalneeds_lang_dev = new App_Form_Element_TestEditor('educationalneeds_lang_dev', array('label'=>'3. Language Development'));
		/* Mike changed this 4-18-2017 per jira SRS-52
		$this->educationalneeds_lang_dev = $this->buildEditor('educationalneeds_lang_dev', array('label'=>'3. Language Development'));
		*/
		$this->educationalneeds_lang_dev = $this->buildEditor('educationalneeds_lang_dev', array('label'=>'3. Communication Development'));
		
		$this->educationalneeds_lang_dev->setRequired(false);
        $this->educationalneeds_lang_dev->setAllowEmpty(false);
        $this->educationalneeds_lang_dev->removeEditorEmptyValidator();
		$this->educationalneeds_lang_dev->addValidator(new My_Validate_EditorNotEmptyIf('educationalneeds_ddview', "t"), true);
		
// 		$this->educationalneeds_social_dev = new App_Form_Element_TestEditor('educationalneeds_social_dev', array('label'=>'4. Social/Emotional Development'));
		$this->educationalneeds_social_dev = $this->buildEditor('educationalneeds_social_dev', array('label'=>'4. Social/Emotional Development'));
		$this->educationalneeds_social_dev->setRequired(false);
        $this->educationalneeds_social_dev->setAllowEmpty(false);
        $this->educationalneeds_social_dev->removeEditorEmptyValidator();
		$this->educationalneeds_social_dev->addValidator(new My_Validate_EditorNotEmptyIf('educationalneeds_ddview', "t"), true);
		
// 		$this->educationalneeds_self_help_skills = new App_Form_Element_TestEditor('educationalneeds_self_help_skills', array('label'=>'5. Self Help Skills'));
		/* Mike changed this 4-18-2017 per jira SRS-52
		$this->educationalneeds_self_help_skills = $this->buildEditor('educationalneeds_self_help_skills', array('label'=>'5. Self Help Skills'));
		*/
		$this->educationalneeds_self_help_skills = $this->buildEditor('educationalneeds_self_help_skills', array('label'=>'5. Adaptive Development'));
		
		$this->educationalneeds_self_help_skills->setRequired(false);
        $this->educationalneeds_self_help_skills->setAllowEmpty(false);
        $this->educationalneeds_self_help_skills->removeEditorEmptyValidator();
		$this->educationalneeds_self_help_skills->addValidator(new My_Validate_EditorNotEmptyIf('educationalneeds_ddview', "t"), true);
		
// 		$this->mdt_00603f2 = new App_Form_Element_TestEditor('mdt_00603f2', array('label'=>'A. Relevant behavior noted during observation:'));
		$this->mdt_00603f2 = $this->buildEditor('mdt_00603f2', array('label'=>'A. Relevant behavior noted during observation:'));
		$this->mdt_00603f2->setRequired(false);
        $this->mdt_00603f2->setAllowEmpty(false);
        $this->mdt_00603f2->removeEditorEmptyValidator();

		$this->mdt_00603f2->addValidator(new My_Validate_EditorNotEmptyIf('disability_primary', "SLD"));
// 		$this->mdt_00603f2->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));

        $this->disability_primary = new App_Form_Element_Select('disability_primary', array('label'=>'Primary Disability', 'multiOptions'=>$this->getPrimaryDisabilityOptions()));
		$this->disability_primary->setAttrib('onclick', "modified();");
		//toggleShowSLDAreas();toggleShowMultiAreas()
        $this->disability_primary->setRequired(false);
        $this->disability_primary->setAllowEmpty(false);
        $this->disability_primary->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
        $this->disability_primary->addValidator(new My_Validate_NotEmptyIf('mdt_00603e2a', "B"));
        $this->disability_primary->addErrorMessage("This field should be empty when No disability has been verified and must be filled in when a disability has been verified.");        
		
// 		$this->mdt_00603e2b = new App_Form_Element_TestEditor('mdt_00603e2b', array('label'=>'Basis for making the determination'));
		$this->mdt_00603e2b = $this->buildEditor('mdt_00603e2b', array('label'=>'Basis for making the determination'));
		$this->mdt_00603e2b->setRequired(false);
        $this->mdt_00603e2b->setAllowEmpty(false);
        $this->mdt_00603e2b->removeEditorEmptyValidator();
		$this->mdt_00603e2b->addValidator(new My_Validate_EditorNotEmptyIf('disability_primary', "SLD"));
        $this->mdt_00603e2b->addValidator(new My_Validate_EditorNotEmptyIf('mdt_00603e2a', "B"));
        
// 		$this->mdt_00603f2d = new App_Form_Element_TestEditor('mdt_00603f2d', array('label'=>'B. Relationship of relevant behavior to the child&rsquo;s academic functioning:'));
		$this->mdt_00603f2d = $this->buildEditor('mdt_00603f2d', array('label'=>"B. Relationship of relevant behavior to the child's academic functioning:"));
		$this->mdt_00603f2d->setRequired(false);
        $this->mdt_00603f2d->setAllowEmpty(false);
        $this->mdt_00603f2d->removeEditorEmptyValidator();
		$this->mdt_00603f2d->addValidator(new My_Validate_EditorNotEmptyIf('disability_primary', "SLD"));
// 		$this->mdt_00603f2d->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
// 		$this->mdt_00603f2e = new App_Form_Element_TestEditor('mdt_00603f2e', array('label'=>'C. Educationally relevant medical findings, if any:'));
		$this->mdt_00603f2e = $this->buildEditor('mdt_00603f2e', array('label'=>'C. Educationally relevant medical findings, if any:'));
		$this->mdt_00603f2e->setRequired(false);
        $this->mdt_00603f2e->setAllowEmpty(false);
        $this->mdt_00603f2e->removeEditorEmptyValidator();
		$this->mdt_00603f2e->addValidator(new My_Validate_EditorNotEmptyIf('disability_primary', "SLD"));
// 		$this->mdt_00603f2e->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		
		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->mdt_00603f2f = new App_Form_Element_Radio('mdt_00603f2f', array('label'=>'D. Is there a severe discrepancy between achievement and ability?', 'multiOptions'=>$multiOptions)); 
		$this->mdt_00603f2f->setRequired(false);
        $this->mdt_00603f2f->setAllowEmpty(false);
		$this->mdt_00603f2f->addValidator(new My_Validate_BooleanNotEmptyIf('disability_primary', "SLD"));
		
		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->mdt_00603f2f_correctable = new App_Form_Element_Radio('mdt_00603f2f_correctable', array('label'=>'E. If yes, is the discrepancy correctable without special education?', 'multiOptions'=>$multiOptions)); 
		$this->mdt_00603f2f_correctable->setRequired(false);
        $this->mdt_00603f2f_correctable->setAllowEmpty(false);
		$this->mdt_00603f2f_correctable->addValidator(new My_Validate_BooleanNotEmptyIf('disability_primary', "SLD"));
		
// 		$this->mdt_00603f2g = new App_Form_Element_TestEditor('mdt_00603f2g', array('label'=>'F. Summarize the effects of environmental, cultural or economic disadvantages:'));
		$this->mdt_00603f2g = $this->buildEditor('mdt_00603f2g', array('label'=>'F. Summarize the effects of environmental, cultural or economic disadvantages:'));
		$this->mdt_00603f2g->setRequired(false);
        $this->mdt_00603f2g->setAllowEmpty(false);
        $this->mdt_00603f2g->removeEditorEmptyValidator();
		$this->mdt_00603f2g->addValidator(new My_Validate_EditorNotEmptyIf('disability_primary', "SLD"));
// 		$this->mdt_00603f2g->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
		

		
		$this->sesisdisability_bl = new App_Form_Element_Checkbox('sesisdisability_bl', array('label'=>'Hearing Impairments'));
		$this->sesisdisability_bl->setRequired(false);
        $this->sesisdisability_bl->setAllowEmpty(false);
		$this->sesisdisability_bl->addValidator(new My_Validate_BooleanAtLeastOneIf('disability_primary', "MULTI", array('sesisdisability_bl', 'sesisdisability_dl', 'sesisdisability_none')));
		
		$this->sesisdisability_dl = new App_Form_Element_Checkbox('sesisdisability_dl', array('label'=>'Visual Impairments'));
		$this->sesisdisability_dl->setRequired(false);
        $this->sesisdisability_dl->setAllowEmpty(false);
		$this->sesisdisability_dl->addValidator(new My_Validate_BooleanAtLeastOneIf('disability_primary', "MULTI", array('sesisdisability_bl', 'sesisdisability_dl', 'sesisdisability_none')));
        		
		$this->sesisdisability_none = new App_Form_Element_Checkbox('sesisdisability_none', array('label'=>'None of the above'));
		$this->sesisdisability_none->setRequired(false);
        $this->sesisdisability_none->setAllowEmpty(false);
		$this->sesisdisability_none->addValidator(new My_Validate_BooleanAtLeastOneIf('disability_primary', "MULTI", array('sesisdisability_bl', 'sesisdisability_dl', 'sesisdisability_none')));
        $this->sesisdisability_none->addValidator(new My_Validate_EmptyIf('sesisdisability_bl', 1));		
//		$this->sesisdisability_none->addValidator(new My_Validate_EmptyIf('sesisdisability_dl', 1));
		
// 		$this->educationalneeds_text = new App_Form_Element_TestEditor('educationalneeds_text', array('label'=>'Educational needs'));
		$this->educationalneeds_text = $this->buildEditor('educationalneeds_text', array('label'=>'Educational needs'));
		$this->educationalneeds_text->setRequired(false);
        $this->educationalneeds_text->setAllowEmpty(false);
        $this->educationalneeds_text->removeEditorEmptyValidator();

        $this->educationalneeds_text->addValidator(new My_Validate_EditorNotEmptyIfNot('educationalneeds_ddview', "t"));
		
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
    
    public function edit_p3_v10() {
    	$this->edit_p3_v1();
    	
    	$this->disability_mhmi = new App_Form_Element_Checkbox('disability_mhmi', array('label'=>'Intellectual Disability', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
    	$this->disability_mhmi->getDecorator('label')->setOption('placement', 'prepend');
    	$this->disability_mhmi->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
    	
    	$this->disability_bd = new App_Form_Element_Checkbox('disability_bd', array('label'=>'Emotional Disturbance (ED)', 'wrapper'=>'mdt_00603e2a_B_wrapper'));
    	$this->disability_bd->getDecorator('label')->setOption('placement', 'prepend');
    	$this->disability_bd->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
    	
    	$multiOptions = array(''=>'Choose...', 'AU'=>'Autism (AU)', 'BD'=>'Emotional Disturbance (ED)', 'DB'=>'Deaf Blindness (DB)', 'HI'=>'Hearing Impairment (HI)',
    			'MH'=>'Intellectual Disability (ID)', 'MULTI'=>'Multiple Impairment (MULTI)', 'OI'=>'Orthopedic Impairment (OI)', 'OHI'=>'Other Health Impairment (OHI)',
    			'SLD'=>'Specific Learning Disability (SLD)', 'SLI'=>'Speech Language Impairment (SLI)', 'TBI'=>'Traumatic Brain Injury (TBI)', 'VI'=>'Visual Impairment (VI)',
    			'DD'=>'Developmental Delay (DD)');
    	$this->disability_primary = new App_Form_Element_Select('disability_primary', array('label'=>'Primary Disability', 'multiOptions'=>$multiOptions));
    	$this->disability_primary->setAttrib('onclick', "modified();");
    	//toggleShowSLDAreas();toggleShowMultiAreas()
    	$this->disability_primary->setRequired(false);
    	$this->disability_primary->setAllowEmpty(false);
    	$this->disability_primary->addValidator(new My_Validate_EmptyIf('mdt_00603e2a', "A"));
    	$this->disability_primary->addValidator(new My_Validate_NotEmptyIf('mdt_00603e2a', "B"));
    	$this->disability_primary->addErrorMessage("This field should be empty when No disability has been verified and must be filled in when a disability has been verified.");
    	
    	return $this;
    }
    
	public function edit_p4_v1() {
		
		$this->initialize();
				
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form002/form002_edit_page4_version1.phtml' ) ) ) ); 

		
		
		$this->date_provided = new App_Form_Element_DatePicker('date_provided', array('label'=>'Date Provided', 'description'=>'The parent was provided a copy of the multidisciplinary evaluation team report on:'));
		$this->date_provided->removeDecorator('label');
		
		$this->name_provided_by = new App_Form_Element_Text('name_provided_by', array('label'=>'By:', 'description'=>' (name)'));
		$this->name_provided_by->getDecorator('Description')->setOption('class', 'noprint'); // tell description decorator not to print

		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->print_blank_rows = new App_Form_Element_Checkbox('print_blank_rows', array('label'=>'Print blank rows', 'multiOptions'=>$multiOptions));
		
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
    public function edit_p4_v10() { return $this->edit_p4_v1();}
    
	public function edit_p5_v1() {
		
		$this->initialize();
				
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form002/form002_edit_page5_version1.phtml' ) ) ) );

		
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
    public function edit_p5_v10() { return $this->edit_p5_v1();}
    
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

    /**
     * @return array
     */
   
   /* Mike changed this April 18th per jira SRS-43 
    function getPrimaryDisabilityOptions()
    {
        $multiOptions = array('' => 'Choose...', 'AU' => 'Autism (AU)', 'BD' => 'Behavioral Disorder (BD)', 'DB' => 'Deaf Blindness (DB)', 'HI' => 'Hearing Impairment (HI)',
            'MH' => 'Mental Handicap (MH)', 'MULTI' => 'Multiple Impairment (MULTI)', 'OI' => 'Orthopedic Impairment (OI)', 'OHI' => 'Other Health Impairment (OHI)',
            'SLD' => 'Specific Learning Disability (SLD)', 'SLI' => 'Speech Language Impairment (SLI)', 'TBI' => 'Traumatic Brain Injury (TBI)', 'VI' => 'Visual Impairment (VI)',
            'DD' => 'Developmental Delay (DD)');
        return $multiOptions;
    }
    */ 
	
	function getPrimaryDisabilityOptions()
	{
	    $multiOptions = array('' => 'Choose...', 'AU' => 'Autism (AU)', 'BD' => 'Behavioral Disorder (BD)', 'DB' => 'Deaf Blindness (DB)', 'HI' => 'Hearing Impairment (HI)',
	        'MH' => 'Intellectual Disability (ID)', 'MULTI' => 'Multiple Impairment (MULTI)', 'OI' => 'Orthopedic Impairment (OI)', 'OHI' => 'Other Health Impairment (OHI)',
	        'SLD' => 'Specific Learning Disability (SLD)', 'SLI' => 'Speech Language Impairment (SLI)', 'TBI' => 'Traumatic Brain Injury (TBI)', 'VI' => 'Visual Impairment (VI)',
	        'DD' => 'Developmental Delay (DD)');
	    return $multiOptions;
	}
}
