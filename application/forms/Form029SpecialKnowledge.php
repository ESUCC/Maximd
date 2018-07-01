<?php

class Form_Form029SpecialKnowledge extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function special_knowledge_edit_version11() {
	    return $this->special_knowledge_edit_version1();
	}
	
	public function special_knowledge_edit_version10() {
	    return $this->special_knowledge_edit_version1();
	}
    public function special_knowledge_edit_version9() {
        return $this->special_knowledge_edit_version1();
    }
    public function special_knowledge_edit_version8() {
        return $this->special_knowledge_edit_version1();
    }
    public function special_knowledge_edit_version7() {
        return $this->special_knowledge_edit_version1();
    }
    public function special_knowledge_edit_version6() {
        return $this->special_knowledge_edit_version1();
    }
    public function special_knowledge_edit_version5() {
        return $this->special_knowledge_edit_version1();
    }
    public function special_knowledge_edit_version4() {
        return $this->special_knowledge_edit_version1();
    }
    public function special_knowledge_edit_version3() {
        return $this->special_knowledge_edit_version1();
    }
    public function special_knowledge_edit_version2() {
        return $this->special_knowledge_edit_version1();
    }
    public function special_knowledge_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form029/special_knowledge_edit_version1.phtml' ) ) ) );
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
        $this->subform_label->setLabel("Individuals who have special knowledge or expertise regarding your child or services that may be needed:");
                
        $this->id_form_029_special_knowledge = new App_Form_Element_Hidden('id_form_029_special_knowledge', array('label'=>''));
        
    	$this->name = new App_Form_Element_Text('name', array('Label' => 'Name'));
    	$this->name->setRequired(true);
    	$this->name->setAllowEmpty(false);
    	$this->name->removeDecorator('Label');
    	$this->name->setDecorators(My_Classes_Decorators::$emptyDecorators);
    	$this->name->setRequired(false);
    	$this->name->setAllowEmpty(true);
    	
    	$multiOptions = array(
    	    '' => 'Choose ...',
    	    'IEP'=>'IEP',
    	    'MDT'=>'MDT',
    	    'Both Meetings' => 'Both Meetings'
    	);
    	$this->meeting_type = new App_Form_Element_Select('meeting_type',array('Label'=>"Attending"));
    	$this->meeting_type->setMultiOptions($multiOptions);
    	$this->meeting_type->removeDecorator('Label');
    	$this->meeting_type->setRequired(true);
    	$this->meeting_type->setAllowEmpty(false);
    	
		return $this;			
	}
	
	// override default
	public function subform_header_edit_version9($subformName, $addNotReq = false) {
	
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form029/special_knowledge_header.phtml' ) ) ) );
	
		// hidden element to tell the system to add a row
		$this->addrow = new App_Form_Element_Hidden('addrow');
	
		// button to call addSubformRow for the subform
		// sets the above to 1 I believe
		$this->add_subform_row= new App_Form_Element_Button('add_subform_row', 'Add Row');
		$this->add_subform_row->setAttrib('onclick', 'addSubformRow(\''.$subformName.'\',\'meetingTypeContainer\');');
	
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