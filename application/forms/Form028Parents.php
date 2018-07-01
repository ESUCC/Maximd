<?php

class Form_Form028Parents extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function parents_edit_version9() {
        return $this->parents_edit_version1();
    }
    public function parents_edit_version8() {
        return $this->parents_edit_version1();
    }
    public function parents_edit_version7() {
        return $this->parents_edit_version1();
    }
    public function parents_edit_version6() {
        return $this->parents_edit_version1();
    }
    public function parents_edit_version5() {
        return $this->parents_edit_version1();
    }
    public function parents_edit_version4() {
        return $this->parents_edit_version1();
    }
    public function parents_edit_version3() {
        return $this->parents_edit_version1();
    }
    public function parents_edit_version2() {
        return $this->parents_edit_version1();
    }
    public function parents_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form028/parents_edit_version1.phtml' ) ) ) );
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
                
        $this->id_form_028_parents = new App_Form_Element_Hidden('id_form_028_parents', array('label'=>''));
        
		//
        // visible fields
        //
		$this->parent_name = new App_Form_Element_Text('parent_name', array('Label' => 'Parent\'s Name'));
    	$this->parent_name->setRequired(true);
    	$this->parent_name->setAllowEmpty(false);
    	$this->parent_name->removeDecorator('Label');
    	$this->parent_name->setDecorators(My_Classes_Decorators::$emptyDecorators);
    	
    	$this->home_phone = new App_Form_Element_Text('home_phone', array('Label' => 'Home Phone'));
    	$this->home_phone->setRequired(true);
    	$this->home_phone->setAllowEmpty(false);
    	$this->home_phone->removeDecorator('Label');
    	$this->home_phone->setDecorators(My_Classes_Decorators::$emptyDecorators);
    	
    	$this->work_phone = new App_Form_Element_Text('work_phone', array('Label' => 'Work Phone'));
    	$this->work_phone->setRequired(false);
    	$this->work_phone->setAllowEmpty(true);
    	$this->work_phone->removeDecorator('Label');
    	$this->work_phone->setDecorators(My_Classes_Decorators::$emptyDecorators);
    	 
    	$this->email_address = new App_Form_Element_Text('email_address', array('Label' => 'Email Address'));
    	$this->email_address->setRequired(false);
    	$this->email_address->setAllowEmpty(true);
    	$this->email_address->removeDecorator('Label');
    	$this->email_address->setDecorators(My_Classes_Decorators::$emptyDecorators);
    	
		return $this;			
	}
	
	// override default
	public function subform_header_edit_version9($subformName, $addNotReq = false) {
	
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form028/parents_header.phtml' ) ) ) );
	
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