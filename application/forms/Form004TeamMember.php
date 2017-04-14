<?php

class Form_Form004TeamMember extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function team_member_edit_version11() {
        return $this->team_member_edit_version1();
    }
    public function team_member_edit_version10() {
        return $this->team_member_edit_version1();
    }
    public function team_member_edit_version9() {
        return $this->team_member_edit_version1();
    }
    public function team_member_edit_version8() {
        return $this->team_member_edit_version1();
    }
    public function team_member_edit_version7() {
        return $this->team_member_edit_version1();
    }
    public function team_member_edit_version6() {
        return $this->team_member_edit_version1();
    }
    public function team_member_edit_version5() {
        return $this->team_member_edit_version1();
    }
    public function team_member_edit_version4() {
        return $this->team_member_edit_version1();
    }
    public function team_member_edit_version3() {
        return $this->team_member_edit_version1();
    }
    public function team_member_edit_version2() {
        return $this->team_member_edit_version1();
    }
    public function team_member_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form004/team_member_edit_version1.phtml' ) ) ) );
		//
        // these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        //
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;
		
        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Team Member Row");
                
        $this->id_iep_team_member = new App_Form_Element_Hidden('id_iep_team_member', array('label'=>''));
        
		//
        // visible fields
        //
		$this->participant_name = new App_Form_Element_Text('participant_name', array('label'=>'Participant Name'));
		$this->participant_name->setDecorators(My_Classes_Decorators::$emptyDecorators);
		$this->participant_name->setRequired(false);
        $this->participant_name->setAllowEmpty(false);
		// $this->participant_name->addValidator(new My_Validate_NotEmptyIfFieldLessThan('rownumber', 6));
        $this->participant_name->addValidator(new My_Validate_NotEmptyIfFieldIn('rownumber', array(1, 3, 4, 5, 6)));
        $this->participant_name->setAttrib('onchange', "modified();colorMeById(this.id);updateDateConference(this.value);");
		
		$this->absent = new App_Form_Element_Checkbox('absent', array('label'=>'Absent'));
		$this->absent->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->absent->setAttrib('onclick', $this->JSmodifiedCode . "colorMeById(this.id);anyAbsence();return true;");
		$this->absent->setAttrib('class', $this->absent->getAttrib('style')." absentCheckbox");
        $this->absent->removeDecorator('label');
		
		
		$this->positin_desc = new App_Form_Element_Text('positin_desc', array('label'=>'Position Description'));
		$this->positin_desc->ignore = true;
		
		$this->meeting_date = new App_Form_Element_DatePicker('meeting_date', array('label'=>'Team Member Date:'));
		$this->meeting_date->noValidation();
		
		$options = array('Curriculum Area Not Discussed', 'Curriculum Area Not Discussed', 'Absent with Written Input (approved by parent)', 'Absent with Written Input (approved by parent)');
		$this->absent_reason = new App_Form_Element_Radio('absent_reason', array('label'=>'Absent Reason:'));
		$this->absent_reason->setMultiOptions(array_combine($options, $options));
		$this->absent_reason->setDecorators ( array('ViewHelper'));
		$this->absent_reason->setSeparator('<br/>');
		$this->absent_reason->setLabel('Absent Reason:');  
		$this->absent_reason->setRegisterInArrayValidator(false); // disable required value. Annoying.
		$this->absent_reason->setAllowEmpty(false);
		$this->absent_reason->setRequired(false);
		$this->absent_reason->addValidator(new My_Validate_NotEmptyIf('absent', true));
        
		
		return $this;			
	}
	
	
	
}

