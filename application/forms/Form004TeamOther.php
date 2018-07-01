<?php

class Form_Form004TeamOther extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function team_other_edit_version2() {return $this->team_other_edit_version1();}
	public function team_other_edit_version3() {return $this->team_other_edit_version1();}
	public function team_other_edit_version4() {return $this->team_other_edit_version1();}
	public function team_other_edit_version5() {return $this->team_other_edit_version1();}
	public function team_other_edit_version6() {return $this->team_other_edit_version1();}
	public function team_other_edit_version7() {return $this->team_other_edit_version1();}
	public function team_other_edit_version8() {return $this->team_other_edit_version1();}
	public function team_other_edit_version9() {return $this->team_other_edit_version1();}
	public function team_other_edit_version10() {return $this->team_other_edit_version1();}
	public function team_other_edit_version11() {return $this->team_other_edit_version1();}

	
	public function team_other_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form004/team_other_edit_version1.phtml' ) ) ) );
		//
        // these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        //
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;

		$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check to remove row'));
		$this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->remove_row->ignore = true;
		$this->remove_row->setAttrib('onchange', "toggleShowHideRow(this, 'team_other_container_', this.checked);");
        $this->remove_row->removeDecorator('label'); // -dboor to remove lable from cell rows
        
        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Team Other Row");
        
        $this->id_iep_team_other = new App_Form_Element_Hidden('id_iep_team_other');

		$this->participant_name = new App_Form_Element_Text('participant_name', array('label'=>'Name'));
		$this->participant_name->setDecorators(My_Classes_Decorators::$emptyDecorators);
//		$this->participant_name->setLabel("Name");
//		$this->participant_name->setAllowEmpty(false);
//		$this->participant_name->setRequired(true);
		
		$this->relationship_desc = new App_Form_Element_Select('relationship_desc', array('label'=>'Relationship'));
		$this->relationship_desc->setDecorators(My_Classes_Decorators::$emptyDecorators);
		$this->relationship_desc->setMultiOptions($this->getStudentRelationship_version1());
		$this->relationship_desc->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);subformShowHideField(this.id, 'relationship_other', this.value, 'Other (Please Specify)');");
		
		$this->relationship_other = new App_Form_Element_Text('relationship_other', array('label'=>'Relationship Other'));
		$this->relationship_other->setDecorators(My_Classes_Decorators::$emptyDecorators);
//		$this->participant_name->setAllowEmpty(true);
//		$this->participant_name->setRequired(false);
		$this->relationship_other->setRequired(false);
        $this->relationship_other->setAllowEmpty(false);
		$this->relationship_other->addValidator(new My_Validate_NotEmptyIf('relationship_desc', 'Other (Please Specify)'));  
        $this->relationship_other->addErrorMessage('Relationship Other cannot be empty when Relationship is Other (Please Specify).');  
		
//		$this->relationship_other->setLabel("Relationship Other");
		//		$this->relationship_other->setAllowEmpty(false);
//		$this->relationship_other->setRequired(true);
		
		return $this;
				
	}
		
	/*
	 * Select Definitions - this code duplicated in TeamDistrict.php
	 */	
	function getStudentRelationship_version1()
	{
        $arrLabel = array(
            "",
            "Adaptive Physical Education",
            "Assistive Technology",
            "Audiologist",
            "Counselor",
            "Early Childhood Special Educator",
            "General Education Teacher",
            "Interpreter",
            "Notetaker",
            "Occupational Therapist",
            "Parent Trainer",
            "Physical  Therapist",
            "Physician",
            "Reader",
            "Recreational Therapist",
            "School Nurse",
            "Speech Language Pathologist",
            "Transportation Services",
            "Vocational Education",
            "Other (Please Specify)"
        );
        $arrValue = array(
            "Choose...",
            "Adaptive Physical Education",
            "Assistive Technology",
            "Audiologist",
            "Counselor",
            "Early Childhood Special Educator",
            "General Education Teacher",
            "Interpreter",
            "Notetaker",
            "Occupational Therapist",
            "Parent Trainer",
            "Physical  Therapist",
            "Physician",
            "Reader",
            "Recreational Therapist",
            "School Nurse",
            "Speech Language Pathologist",
            "Transportation Services",
            "Vocational Education",
            "Other (Please Specify)"
        );
        return array_combine($arrLabel, $arrValue);
	}
	
	
}

