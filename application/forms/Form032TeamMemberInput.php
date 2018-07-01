<?php

class Form_Form032TeamMemberInput extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function team_member_input_edit_version2() {
		return $this->team_member_input_edit_version1();
	}
	public function team_member_input_edit_version3() {
		return $this->team_member_input_edit_version1();
	}
	public function team_member_input_edit_version4() {
		return $this->team_member_input_edit_version1();
	}
	public function team_member_input_edit_version5() {
		return $this->team_member_input_edit_version1();
	}
	public function team_member_input_edit_version6() {
		return $this->team_member_input_edit_version1();
	}
	public function team_member_input_edit_version9() {
		return $this->team_member_input_edit_version1();
	}
	public function team_member_input_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form032/team_member_input_edit_version1.phtml' ) ) ) );
		//
        // these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        //
        $this->subformIdentifier = new App_Form_Element_Hidden('subformIdentifier');
        $this->subformIdentifier->ignore = true;
		
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;
		
		$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check and save to remove the Accomodations Checklist:'));
		$this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->remove_row->ignore = true;
		
        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Team Member Absence Row");
                
//        $this->input_information = new App_Form_Element_TextareaEditor('input_information');
        
        $this->id_iep_team_member_input = new App_Form_Element_Hidden('id_iep_team_member_input');
        
		//
        // visible fields
        //
//		$this->participant_name = new App_Form_Element_Text('participant_name', array('label'=>'Participant Name'));
//		$this->participant_name->setDecorators(My_Classes_Decorators::$emptyDecorators);
        return $this;			
	}
	
	
	
}