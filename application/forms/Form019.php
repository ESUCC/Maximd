<?php

class Form_Form019 extends Form_AbstractForm {

	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_019 = new App_Form_Element_Hidden('id_form_019');
      	$this->id_form_019->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
	public function view_p1_v1() {

		$this->initialize();
		
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form019/form019_view_page1_version1.phtml' ) ) ) );
	

		
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
										'viewScript' => 'form019/form019_edit_page1_version1.phtml' ) ) ) );

        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
        
		
        $this->fa_desc_of_problem = new App_Form_Element_TextareaEditor('fa_desc_of_problem', array('label'=>'Description of problem'));
        $this->fa_desc_of_problem->setRequired(false);
        $this->fa_desc_of_problem->setAllowEmpty(true);
        
		$this->fa_baseline_info = new App_Form_Element_TextareaEditor('fa_baseline_info', array('label'=>'Baseline info'));
		$this->fa_baseline_info->setRequired(false);
        $this->fa_baseline_info->setAllowEmpty(true);
        
		$this->fa_specific_antecedents = new App_Form_Element_TextareaEditor('fa_specific_antecedents', array('label'=>'Specific Antecedent'));
        $this->fa_specific_antecedents->setRequired(false);
        $this->fa_specific_antecedents->setAllowEmpty(true);
        
		$this->fa_current_consequences = new App_Form_Element_TextareaEditor('fa_current_consequences', array('label'=>'Current consequence'));
        $this->fa_current_consequences->setRequired(false);
        $this->fa_current_consequences->setAllowEmpty(true);
        
		$this->fa_function_of_the_problem = new App_Form_Element_TextareaEditor('fa_function_of_the_problem', array('label'=>'Function of the problem'));
        $this->fa_function_of_the_problem->setRequired(false);
        $this->fa_function_of_the_problem->setAllowEmpty(true);
        
		$this->fa_appropriate_alternative = new App_Form_Element_TextareaEditor('fa_appropriate_alternative', array('label'=>'Appropriate Alternative Behaviors'));
        $this->fa_appropriate_alternative->setRequired(false);
        $this->fa_appropriate_alternative->setAllowEmpty(true);
        
		$this->bi_behavioral_goal = new App_Form_Element_TextareaEditor('bi_behavioral_goal', array('label'=>'Behavioral Goal(s)'));
        $this->bi_behavioral_goal->setRequired(false);
        $this->bi_behavioral_goal->setAllowEmpty(true);
        
		$this->bi_list_data = new App_Form_Element_TextareaEditor('bi_list_data', array('label'=>'List/Describe data'));
        $this->bi_list_data->setRequired(false);
        $this->bi_list_data->setAllowEmpty(true);
        
		$this->bi_behavior_management = new App_Form_Element_TextareaEditor('bi_behavior_management', array('label'=>'Behavior Management'));
        $this->bi_behavior_management->setRequired(false);
        $this->bi_behavior_management->setAllowEmpty(true);
        
		$this->bi_skill_building = new App_Form_Element_TextareaEditor('bi_skill_building', array('label'=>'Skill building'));
        $this->bi_skill_building->setRequired(false);
        $this->bi_skill_building->setAllowEmpty(true);
        
		$this->bi_modifications = new App_Form_Element_TextareaEditor('bi_modifications', array('label'=>'Modifications'));
        $this->bi_modifications->setRequired(false);
        $this->bi_modifications->setAllowEmpty(true);

        $this->bi_alternative_discipline = new App_Form_Element_Radio('bi_alternative_discipline', array('label'=>'Alternative plan radio'));
        $this->bi_alternative_discipline->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->bi_alternative_discipline->removeDecorator('label');
        $this->bi_alternative_discipline->setMultiOptions(App_Form_ValueListHelper::yesNo());
        $this->bi_alternative_discipline->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'adp_wrapper');");
        $this->bi_alternative_discipline->setAttrib('wrapped', 'adp_wrapper');
        
		$this->bi_alternative_discipline_reason = new App_Form_Element_TextareaEditor('bi_alternative_discipline_reason', array('label'=>'Alternative plan'));
        $this->bi_alternative_discipline_reason->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
		$this->bi_alternative_discipline_reason->setAttrib('onchange', $this->JSmodifiedCode . "updateInlineValue(this.id, arguments[0]);colorMeById(this.id, 'adp_wrapper');");
        $this->bi_alternative_discipline_reason->setAttrib('wrapped', 'adp_wrapper');
        $this->bi_alternative_discipline_reason->removeDecorator('label');
        $this->bi_alternative_discipline_reason->setRequired(false);
        $this->bi_alternative_discipline_reason->setAllowEmpty(false);
        $this->bi_alternative_discipline_reason->addValidator(new My_Validate_NotEmptyIf('bi_alternative_discipline', 'yes'));
        $this->bi_alternative_discipline_reason->addValidator(new My_Validate_EmptyIf('bi_alternative_discipline', 'no'));
        $this->bi_alternative_discipline_reason->addErrorMessage("Alternative plan must be entered when Yes is selected and must be empty when No is selected.");
        
		$this->bi_crisis_intervention_reason = new App_Form_Element_TextareaEditor('bi_crisis_intervention_reason', array('label'=>'Crisis intervention'));
		$this->bi_crisis_intervention_reason->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
        $this->bi_crisis_intervention_reason->setAttrib('onchange', $this->JSmodifiedCode . "updateInlineValue(this.id, arguments[0]);colorMeById(this.id, 'ci_wrapper');");
        $this->bi_crisis_intervention_reason->setAttrib('wrapped', 'ci_wrapper');
		$this->bi_crisis_intervention_reason->removeDecorator('label');
        $this->bi_crisis_intervention_reason->setRequired(false);
        $this->bi_crisis_intervention_reason->setAllowEmpty(false);
        $this->bi_crisis_intervention_reason->addValidator(new My_Validate_NotEmptyIf('bi_crisis_intervention', 'yes'));
        $this->bi_crisis_intervention_reason->addValidator(new My_Validate_EmptyIf('bi_crisis_intervention', 'no'));
        $this->bi_crisis_intervention_reason->addErrorMessage("Crisis intervention must be entered when Yes is selected and must be empty when No is selected.");
        
		$this->bi_responsibilities = new App_Form_Element_TextareaEditor('bi_responsibilities', array('label'=>'Responsibilities for implementation'));
        $this->bi_responsibilities->setRequired(false);
        $this->bi_responsibilities->setAllowEmpty(true);
		
				
        $this->bi_crisis_intervention = new App_Form_Element_Radio('bi_crisis_intervention', array('label'=>'Crisis intervention radio'));
        $this->bi_crisis_intervention->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->bi_crisis_intervention->removeDecorator('label');
        $this->bi_crisis_intervention->setMultiOptions(App_Form_ValueListHelper::yesNo());
        $this->bi_crisis_intervention->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'ci_wrapper');");
        $this->bi_crisis_intervention->setAttrib('wrapped', 'ci_wrapper');
        
        
        $this->per_resp_informing_parties = new App_Form_Element_Text('per_resp_informing_parties', 
            array(
                'label'=>'Responsibility: Inform parties',
                'description' => 'Informing all parties who work with student of his/her responsibilities in implementing plan',
            )
        );
        $this->per_resp_informing_parties->removeDecorator('label');
        $this->bi_responsibilities->setRequired(false);
        $this->bi_responsibilities->setAllowEmpty(true);
		
      
        $this->per_resp_monitoring_progress = new App_Form_Element_Text('per_resp_monitoring_progress', 
            array(
                'label'=>'Responsibility: Progress',
                'description' => 'Monitoring progress through data collection and scheduling reviews to discuss progress ',
            )
        );
        $this->per_resp_monitoring_progress->removeDecorator('label');
        $this->per_resp_monitoring_progress->setRequired(false);
        $this->per_resp_monitoring_progress->setAllowEmpty(true);
		
        
        $this->per_resp_modifying_materials = new App_Form_Element_Text('per_resp_modifying_materials', 
            array(
                'label'=>'Responsibility: Materials',
                'description' => 'Modifying or providing modified materials ',
            )
        );
        $this->per_resp_modifying_materials->removeDecorator('label');
        $this->per_resp_modifying_materials->setRequired(false);
        $this->per_resp_modifying_materials->setAllowEmpty(true);
		
        
        $this->per_resp_other = new App_Form_Element_Text('per_resp_other', 
            array(
                'label'=>'Responsibility: Other',
                'description' => 'Other',
            )
        );
        $this->per_resp_other->removeDecorator('label');
        $this->per_resp_other->setRequired(false);
        $this->per_resp_other->setAllowEmpty(true);
		
        
        
		return $this;
	}
}