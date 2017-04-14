<?php

class Form_Form002TeamMember extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function team_member_edit_version2() {
		return $this->team_member_edit_version1();
	}
	public function team_member_edit_version3() {
		return $this->team_member_edit_version1();
	}
	public function team_member_edit_version4() {
		return $this->team_member_edit_version1();
	}
	public function team_member_edit_version5() {
		return $this->team_member_edit_version1();
	}
	public function team_member_edit_version6() {
		return $this->team_member_edit_version1();
	}
	public function team_member_edit_version9() {
		return $this->team_member_edit_version1();
	}
	public function team_member_edit_version10() {
		return $this->team_member_edit_version1();
	}
	public function team_member_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form002/team_member_edit_version1.phtml' ) ) ) );
		//
		$this->addElementPrefixPath('My','My/'); // required to use BooleanNotEmpty validator
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
                
        $this->id_form_002_team_member = new App_Form_Element_Hidden('id_form_002_team_member', array('label'=>''));
        
		//
        // visible fields
        //
		$this->team_member_name = new App_Form_Element_Text('team_member_name', array('label'=>'Team Member Name'));
		$this->team_member_name->setDecorators(My_Classes_Decorators::$emptyDecorators);
		
		$this->team_member_position = new App_Form_Element_Text('team_member_position', array('label'=>'Team Member Position'));
		$this->team_member_position->setDecorators(My_Classes_Decorators::$emptyDecorators);
		
		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->team_member_agree = new App_Form_Element_Radio('team_member_agree', array('label'=>'Agree', 'multiOptions'=>$multiOptions));
		$this->team_member_agree->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->team_member_agree->addValidator('BooleanNotEmpty', true);
        $this->team_member_agree->removeDecorator('label');
        
		$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check to remove row'));
		$this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->remove_row->removeDecorator('label');
		$this->remove_row->ignore = true;
		
		return $this;			
	}

	// override default
	public function subform_header_edit_version9($subformName, $addNotReq = false) {
	
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form002/team_member_header.phtml' ) ) ) );
	
		// hidden element to tell the system to add a row
		$this->addrow = new App_Form_Element_Hidden('addrow');
	
		// button to call addSubformRow for the subform
		// sets the above to 1 I believe
		$this->add_subform_row= new App_Form_Element_Button('add_subform_row', 'Add Row');
		$this->add_subform_row->setAttrib('onclick', 'addSubformRow(\''.$subformName.'\');');
	
		if($addNotReq) {
			$this->override = new App_Form_Element_Checkbox('override', array('label'=>'Not Required'));
			$this->override->setAttrib('onclick', "override(this.id, this.checked);");
		}
	
		$this->count = new App_Form_Element_Hidden('count');
	
		$this->subformTitle = new Zend_Form_Element_Hidden('subformTitle');
	
		//
		// add hidden elements for subform counts
		//
		$this->subformName = new App_Form_Element_Hidden('subformName', $subformName);
		$this->subformName->setValue($subformName);
		return $this;
	}
	
	
}

