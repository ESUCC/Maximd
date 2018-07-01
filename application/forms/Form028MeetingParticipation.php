<?php

class Form_Form028MeetingParticipation extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function meeting_participation_edit_version9() {
        return $this->meeting_participation_edit_version1();
    }
    public function meeting_participation_edit_version8() {
        return $this->meeting_participation_edit_version1();
    }
    public function meeting_participation_edit_version7() {
        return $this->meeting_participation_edit_version1();
    }
    public function meeting_participation_edit_version6() {
        return $this->meeting_participation_edit_version1();
    }
    public function meeting_participation_edit_version5() {
        return $this->meeting_participation_edit_version1();
    }
    public function meeting_participation_edit_version4() {
        return $this->meeting_participation_edit_version1();
    }
    public function meeting_participation_edit_version3() {
        return $this->meeting_participation_edit_version1();
    }
    public function meeting_participation_edit_version2() {
        return $this->meeting_participation_edit_version1();
    }
    public function meeting_participation_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form028/meeting_participation_edit_version1.phtml' ) ) ) );
		//
        // these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        //
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;
        
        $this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>' '));
        $this->remove_row->setDecorators ( My_Classes_Decorators::$labelDecoratorsNoSpan);
        $this->remove_row->ignore = true;
		
        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Math Row");
                
        $this->id_form_028_meeting_participation = new App_Form_Element_Hidden('id_form_028_meeting_participation', array('label'=>''));
        
		//
        // visible fields
        //
		$this->full_name = new App_Form_Element_Text('full_name', array('Label' => 'Full Name'));
    	$this->full_name->setRequired(true);
    	$this->full_name->setAllowEmpty(false);
    	$this->full_name->removeDecorator('Label');
    	$this->full_name->setDecorators(My_Classes_Decorators::$emptyDecorators);
    	
    	$this->role = new App_Form_Element_Text('role', array('Label' => 'Role'));
    	$this->role->setRequired(true);
    	$this->role->setAllowEmpty(false);
    	$this->role->removeDecorator('Label');
    	$this->role->setDecorators(My_Classes_Decorators::$emptyDecorators);
    	
    	$this->meeting_date = new App_Form_Element_DatePicker('meeting_date', array('Label' => 'Meeting Date'));
    	$this->meeting_date->setRequired(true);
    	$this->meeting_date->setAllowEmpty(false);
    	$this->meeting_date->removeDecorator('Label');
    	$this->meeting_date->setDecorators(My_Classes_Decorators::$emptyDecorators);
    	
		return $this;			
	}
	
	// override default
	public function subform_header_edit_version9($subformName, $addNotReq = false) {
	
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form028/meeting_participation_header.phtml' ) ) ) );
	
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

    public function isValid($data)
    {
        /**
         * only validate the first two rows
         */
        if(method_exists($this->rownumber, 'getValue') && $this->rownumber->getValue() > 2) {
            return true;
        }
        return parent::isValid($data);
    }
}