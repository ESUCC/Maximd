<?php

class Form_Form032OutsideAgency extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function outside_agency_edit_version9() {
		return $this->outside_agency_edit_version1();
	}
	public function outside_agency_edit_version2() {
		return $this->outside_agency_edit_version1();
	}
	public function outside_agency_edit_version3() {
		return $this->outside_agency_edit_version1();
	}
	public function outside_agency_edit_version4() {
		return $this->outside_agency_edit_version1();
	}
	public function outside_agency_edit_version5() {
		return $this->outside_agency_edit_version1();
	}
	public function outside_agency_edit_version6() {
		return $this->outside_agency_edit_version1();
	}
	public function outside_agency_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form032/outside_agency_edit_version1.phtml' ) ) ) );
		//
        // these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        //
        $this->subformIdentifier = new App_Form_Element_Hidden('subformIdentifier');
        $this->subformIdentifier->ignore = true;
		
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;
		
        $this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check and save to remove this Outside Agency:'));
		$this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->remove_row->ignore = true;
		
        // named displayed in validation output
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->setLabel("Outside Agency Row");
        $this->subform_label->ignore = true;
                
        $this->id_form_032_agency_representitive = new App_Form_Element_Hidden('id_form_032_agency_representitive');
        
        // visible fields
        // =========================================================================================================
		$this->representitive_name = new App_Form_Element_Text('representitive_name', array('label'=>'Agency'));
		$this->representitive_name->removeDecorator('label');
		
		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->consent_invite = new App_Form_Element_Radio('consent_invite', array('label'=>'Consent to invite', 'multiOptions'=>$multiOptions));
		$this->consent_invite->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->consent_invite->addValidator(new My_Validate_BooleanNotEmpty(), true);  
		$this->consent_invite->setAllowEmpty(false);  
		$this->consent_invite->setSeparator('<br/>');
		$this->consent_invite->removeDecorator('label');
		
		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->consent_release = new App_Form_Element_Radio('consent_release', array('label'=>'Consent to release', 'multiOptions'=>$multiOptions));
		$this->consent_release->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->consent_release->setSeparator('<br/>');
		$this->consent_release->removeDecorator('label');
		$this->consent_release->addValidator(new My_Validate_BooleanNotEmpty(), true);  
		$this->consent_release->setAllowEmpty(false);  
        $this->consent_release->setAttrib('onchange', 
          $this->consent_release->getAttrib('onchange') . 
          "subformShowHideField(this.id, 'release_records_show', this.value, '1');"
        ); 
        
        $this->consent_release->addErrorMessage('Consent for release must be entered. If "Yes" then at least one checkbox must be checked.');  
        $this->consent_release->addValidator(new My_Validate_BooleanAtLeastOneIf('consent_release', "1", 
            array('release_records_all', 
                  'release_records_scholastic_grades',
                  'release_records_psychological_evaluations',
                  'release_records_activity_records',
                  'release_records_discipline_records',
                  'release_records_health_records',
                  'release_records_standardized_test_scores',
                  'release_records_special_educational_records',
                  'release_records_other',
            )
        ));
		
		$this->release_records_all = new App_Form_Element_Checkbox('release_records_all', array('label'=>'All records about Student and any other information requested by Recepient '));
		
		$this->release_records_scholastic_grades = new App_Form_Element_Checkbox('release_records_scholastic_grades', array('label'=>'Scholastic Grades'));
        
		$this->release_records_psychological_evaluations = new App_Form_Element_Checkbox('release_records_psychological_evaluations', array('label'=>'Psychological Evaluations'));
        
		$this->release_records_activity_records = new App_Form_Element_Checkbox('release_records_activity_records', array('label'=>'Activity Records'));
        
		$this->release_records_discipline_records = new App_Form_Element_Checkbox('release_records_discipline_records', array('label'=>'Discipline Records'));
        
		$this->release_records_health_records = new App_Form_Element_Checkbox('release_records_health_records', array('label'=>'Health Records'));
        
		$this->release_records_standardized_test_scores = new App_Form_Element_Checkbox('release_records_standardized_test_scores', array('label'=>'Standardized Test Scores'));
        
		$this->release_records_special_educational_records = new App_Form_Element_Checkbox('release_records_special_educational_records', array('label'=>'Special Educational Records'));
        
		
		$this->release_records_other = new App_Form_Element_Checkbox('release_records_other', array('label'=>'Other'));
		
		$this->release_records_other_description = new App_Form_Element_Text('release_records_other_description', array('label'=>'Other Description'));
        $this->release_records_other_description->setRequired(false);
        $this->release_records_other_description->setAllowEmpty(false);
        $this->release_records_other_description->addValidator(new My_Validate_NotEmptyIf('release_records_other', 't'));
        $this->release_records_other_description->addValidator(new My_Validate_EmptyIf('release_records_other', false));
        $this->release_records_other_description->addErrorMessage('Other Description cannot be empty when Other is checked and must be empty when Other is not checked.');  
		
		return $this;			
	}
	
}

