<?php

class Form_Form020 extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_020 = new App_Form_Element_Hidden('id_form_020');
      	$this->id_form_020->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
    
	public function view_p1_v1() {
				
		$this->initialize();
		
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form020/form020_view_page1_version1.phtml' ) ) ) );
	
		
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
										'viewScript' => 'form020/form020_edit_page1_version1.phtml' ) ) ) );

        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
        
		
		$this->bip_weight = new App_Form_Element_Text('bip_weight', array('label'=>'Weight'));
		$this->bip_weight->setRequired(false);
		$this->bip_weight->setAllowEmpty(true);
		
		$this->bip_height = new App_Form_Element_Text('bip_height', array('label'=>'Height'));
		$this->bip_height->setRequired(false);
		$this->bip_height->setAllowEmpty(true);
		
		$this->st_contact_1 = new App_Form_Element_Text('st_contact_1', array('label'=>'Contact 1'));
		$this->st_contact_1->setRequired(false);
        $this->st_contact_1->setAllowEmpty(true);
        
		$this->st_name_1 = new App_Form_Element_Text('st_name_1', array('label'=>'Name'));
		$this->st_name_1->setRequired(false);
        $this->st_name_1->setAllowEmpty(true);
        $this->st_name_1->noBoldLabelPrintIndent();
        
		$this->st_phone_home_1 = new App_Form_Element_Text('st_phone_home_1', array('label'=>'Phone Home'));
		$this->st_phone_home_1->setRequired(false);
        $this->st_phone_home_1->setAllowEmpty(true);
        $this->st_phone_home_1->noBoldLabelPrintIndent();
        
		$this->st_phone_work_1 = new App_Form_Element_Text('st_phone_work_1', array('label'=>'Phone Work'));
		$this->st_phone_work_1->setRequired(false);
        $this->st_phone_work_1->setAllowEmpty(true);
        $this->st_phone_work_1->noBoldLabelPrintIndent();
		
		$this->st_contact_2 = new App_Form_Element_Text('st_contact_2', array('label'=>'Contact 2'));
        $this->st_contact_2->setRequired(false);
        $this->st_contact_2->setAllowEmpty(true);
		
        $this->st_name_2 = new App_Form_Element_Text('st_name_2', array('label'=>'Name'));
        $this->st_name_2->setRequired(false);
        $this->st_name_2->setAllowEmpty(true);
        $this->st_name_2->noBoldLabelPrintIndent();
        
        $this->st_phone_home_2 = new App_Form_Element_Text('st_phone_home_2', array('label'=>'Phone Home'));
        $this->st_phone_home_2->setRequired(false);
        $this->st_phone_home_2->setAllowEmpty(true);
        $this->st_phone_home_2->noBoldLabelPrintIndent();
        
        $this->st_phone_work_2 = new App_Form_Element_Text('st_phone_work_2', array('label'=>'Phone Work'));
        $this->st_phone_work_2->setRequired(false);
        $this->st_phone_work_2->setAllowEmpty(true);
        $this->st_phone_work_2->noBoldLabelPrintIndent();
        
		$this->pu_add1_name = new App_Form_Element_Text('pu_add1_name', array('label'=>'Pick Up Name'));
        $this->pu_add1_name->setRequired(false);
        $this->pu_add1_name->setAllowEmpty(true);
        $this->pu_add1_name->noBoldLabelPrintIndent();
        
		$this->pu_add1_address = new App_Form_Element_Text('pu_add1_address', array('label'=>'Pick Up Address'));
        $this->pu_add1_address->setRequired(false);
        $this->pu_add1_address->setAllowEmpty(true);
        $this->pu_add1_address->noBoldLabelPrintIndent();
        
		$this->pu_add1_phone = new App_Form_Element_Text('pu_add1_phone', array('label'=>'Pick Up Phone'));
        $this->pu_add1_phone->setRequired(false);
        $this->pu_add1_phone->setAllowEmpty(true);
        $this->pu_add1_phone->noBoldLabelPrintIndent();
        
		$this->pu_add2_name = new App_Form_Element_Text('pu_add2_name', array('label'=>'Emergency Name'));
        $this->pu_add2_name->setRequired(false);
        $this->pu_add2_name->setAllowEmpty(true);
        $this->pu_add2_name->noBoldLabelPrintIndent();
        
		$this->pu_add2_address = new App_Form_Element_Text('pu_add2_address', array('label'=>'Emergency Address'));
        $this->pu_add2_address->setRequired(false);
        $this->pu_add2_address->setAllowEmpty(true);
        $this->pu_add2_address->noBoldLabelPrintIndent();
        
		$this->pu_add2_phone = new App_Form_Element_Text('pu_add2_phone', array('label'=>'Emergency Phone'));
        $this->pu_add2_phone->setRequired(false);
        $this->pu_add2_phone->setAllowEmpty(true);
        $this->pu_add2_phone->noBoldLabelPrintIndent();
        
		
		$this->spec_transportation = new App_Form_Element_Radio('spec_transportation', array('label'=>'Specialized transportation'));
		$this->spec_transportation->setMultiOptions(App_Form_ValueListHelper::specializedTransportation());
		$this->spec_transportation->removeDecorator('label');
        $this->spec_transportation->setRequired(false);
        $this->spec_transportation->setAllowEmpty(true);
		
		$this->med_side_effect = new App_Form_Element_Text('med_side_effect', array('label'=>'Medication Side Effects'));
        $this->med_side_effect->setRequired(false);
        $this->med_side_effect->setAllowEmpty(true);
        
		$this->med_allergies = new App_Form_Element_Text('med_allergies', array('label'=>'Medication Allergies'));
        $this->med_allergies->setRequired(false);
        $this->med_allergies->setAllowEmpty(true);
        
		$this->med_physician = new App_Form_Element_Text('med_physician', array('label'=>'Medication Physician'));
        $this->med_physician->setRequired(false);
        $this->med_physician->setAllowEmpty(true);
        
		$this->parental_sig = new App_Form_Element_Checkbox('parental_sig', array('label'=>'Check to indicate parental signature'));
		
		$this->office_use_spec_transportation = new App_Form_Element_Radio('office_use_spec_transportation', array('label'=>'Office Use Specialized transportation'));
		$this->office_use_spec_transportation->setMultiOptions(App_Form_ValueListHelper::yesNo());
        $this->office_use_spec_transportation->removeDecorator('label');
		
		$this->office_use_based_on = new App_Form_Element_Radio('office_use_based_on', array('label'=>'Office Use Based On'));
		$this->office_use_based_on->setMultiOptions(App_Form_ValueListHelper::disabilityBasedOn());
		$this->office_use_based_on->removeDecorator('label');
		
		$this->ssr_wheel_chair_lift = new App_Form_Element_Radio('ssr_wheel_chair_lift', array('label'=>'Wheel chair lift'));
		$this->ssr_wheel_chair_lift->setMultiOptions(App_Form_ValueListHelper::yesNo());
		
		$this->ssr_nurse_aid = new App_Form_Element_Radio('ssr_nurse_aid', array('label'=>'Nurse/Aide'));
		$this->ssr_nurse_aid->setMultiOptions(App_Form_ValueListHelper::yesNo());
		
		$this->srs_safety_restraint = new App_Form_Element_Radio('srs_safety_restraint', array('label'=>'Safety Restraint System'));
		$this->srs_safety_restraint->setMultiOptions(App_Form_ValueListHelper::yesNo());
		
		$this->ssr_child_seat = new App_Form_Element_Radio('ssr_child_seat', array('label'=>'Child Seat'));
		$this->ssr_child_seat->setMultiOptions(App_Form_ValueListHelper::yesNo());
		
		$this->handi_specific_learning = new App_Form_Element_Checkbox('handi_specific_learning', array('label'=>'Specific Learning Disability'));
		$this->handi_behavior_disorder = new App_Form_Element_Checkbox('handi_behavior_disorder', array('label'=>'Behavior Disorder'));
		$this->handi_mild_mental_dis = new App_Form_Element_Checkbox('handi_mild_mental_dis', array('label'=>'Mild Mental Disability'));
		$this->handi_mod_mental_dis = new App_Form_Element_Checkbox('handi_mod_mental_dis', array('label'=>'Moderate Mental Disability'));
		$this->handi_sev_mental_dis = new App_Form_Element_Checkbox('handi_sev_mental_dis', array('label'=>'Severe/Profound Mental Disability'));
		$this->handi_speech_imp = new App_Form_Element_Checkbox('handi_speech_imp', array('label'=>'Speech-Language Impairment'));
		$this->handi_autism = new App_Form_Element_Checkbox('handi_autism', array('label'=>'Autism'));
		$this->handi_hearing_imp = new App_Form_Element_Checkbox('handi_hearing_imp', array('label'=>'Hearing Impairment'));
		$this->handi_orth_imp = new App_Form_Element_Checkbox('handi_orth_imp', array('label'=>'Orthopedic Impairment'));
		$this->handi_visual_dis = new App_Form_Element_Checkbox('handi_visual_dis', array('label'=>'Visual Disability'));
		$this->handi_deaf_blind = new App_Form_Element_Checkbox('handi_deaf_blind', array('label'=>'Deaf-Blindness'));
		$this->handi_multihandi = new App_Form_Element_Checkbox('handi_multihandi', array('label'=>'Multihandicapped'));
		$this->handi_tram_brain = new App_Form_Element_Checkbox('handi_tram_brain', array('label'=>'Traumatic Brain Injury'));
		$this->handi_other_hearing = new App_Form_Element_Checkbox('handi_other_hearing', array('label'=>'Other Health Impairment'));
		$this->handi_dev_dis = new App_Form_Element_Checkbox('handi_dev_dis', array('label'=>'Developmental Disability'));
		$this->handi_other = new App_Form_Element_Checkbox('handi_other', array('label'=>'Other'));
		
		$this->handi_specific_learning->addTableFix();
		$this->handi_behavior_disorder->addTableFix();
		$this->handi_mild_mental_dis->addTableFix();
		$this->handi_mod_mental_dis->addTableFix();
		$this->handi_sev_mental_dis->addTableFix();
		$this->handi_speech_imp->addTableFix();
		$this->handi_autism->addTableFix();
		$this->handi_hearing_imp->addTableFix();
		$this->handi_orth_imp->addTableFix();
		$this->handi_visual_dis->addTableFix();
		$this->handi_deaf_blind->addTableFix();
		$this->handi_multihandi->addTableFix();
		$this->handi_tram_brain->addTableFix();
		$this->handi_other_hearing->addTableFix();
		$this->handi_dev_dis->addTableFix();
		$this->handi_other->addTableFix();
		
		
		$this->attending_school = new App_Form_Element_Text('attending_school', array('label'=>'Attending school'));
        $this->attending_school->setRequired(false);
        $this->attending_school->setAllowEmpty(true);
        
		$this->preschool_program = new App_Form_Element_Radio('preschool_program', array('label'=>'Preschool program'));
        $this->preschool_program->setRequired(false);
        $this->preschool_program->setAllowEmpty(true);
        
	    $this->preschool_program->setMultiOptions(App_Form_ValueListHelper::amPm());

	    $this->mode = new App_Form_Element_Hidden('mode');
        $this->mode->ignore = true;
		
		return $this;
	}
}