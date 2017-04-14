<?php

class Form_Form032OtherAttendee extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function other_attendee_edit_version9() {
        return $this->other_attendee_edit_version1();
    }
    public function other_attendee_edit_version8() {
        return $this->other_attendee_edit_version1();
    }
    public function other_attendee_edit_version7() {
        return $this->other_attendee_edit_version1();
    }
    public function other_attendee_edit_version6() {
        return $this->other_attendee_edit_version1();
    }
    public function other_attendee_edit_version5() {
        return $this->other_attendee_edit_version1();
    }
    public function other_attendee_edit_version4() {
        return $this->other_attendee_edit_version1();
    }
    public function other_attendee_edit_version3() {
        return $this->other_attendee_edit_version1();
    }
    public function other_attendee_edit_version2() {
        return $this->other_attendee_edit_version1();
    }
    public function other_attendee_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form032/other_attendee_edit_version1.phtml' ) ) ) );
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
                
        $this->id_form_032_other_attendee = new App_Form_Element_Hidden('id_form_032_other_attendee', array('label'=>''));
        
		//
        // visible fields
        //
		$multiOptions = array(
				'' => 'Choose ...',
				'Non-Public School Representative'=>'Non-Public School Representative', 
				'Service Agency Representative'=>'Service Agency Representative',
				'Teacher of Hearing Impaired' => 'Teacher of Hearing Impaired',
				'Teacher of Visually Impaired' => 'Teacher of Visually Impaired',
				'Other Family Member as Requested by the Parent' => 'Other Family Member as Requested by the Parent'
		);
        $this->other_type = new App_Form_Element_Select('other_type',array('Label'=>"Other"));
		$this->other_type->setMultiOptions($multiOptions);
		$this->other_type->removeDecorator('Label');
		$this->other_type->setRequired(false);
		$this->other_type->setAllowEmpty(true);
        
    	$this->other_name = new App_Form_Element_Text('other_name', array('Label' => 'Other Name'));
    	$this->other_name->setRequired(true);
    	$this->other_name->setAllowEmpty(false);
    	$this->other_name->removeDecorator('Label');
    	$this->other_name->setDecorators(My_Classes_Decorators::$emptyDecorators);
    	$this->other_name->setRequired(false);
    	$this->other_name->setAllowEmpty(true);
    	
		return $this;			
	}
	
	// override default
	public function subform_header_edit_version9($subformName, $addNotReq = false) {
	
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form032/other_attendee_header.phtml' ) ) ) );
	
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