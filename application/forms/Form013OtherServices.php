<?php

class Form_Form013OtherServices extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function other_services_edit_version10() {
	    return $this->other_services_edit_version1();
	}
    public function other_services_edit_version9() {
        return $this->other_services_edit_version1();
    }
    public function other_services_edit_version8() {
        return $this->other_services_edit_version1();
    }
    public function other_services_edit_version7() {
        return $this->other_services_edit_version1();
    }
    public function other_services_edit_version6() {
        return $this->other_services_edit_version1();
    }
    public function other_services_edit_version5() {
        return $this->other_services_edit_version1();
    }
    public function other_services_edit_version4() {
        return $this->other_services_edit_version1();
    }
    public function other_services_edit_version3() {
        return $this->other_services_edit_version1();
    }
    public function other_services_edit_version2() {
        return $this->other_services_edit_version1();
    }
    public function other_services_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form013/other_services_edit_version1.phtml' ) ) ) );
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
        $this->subform_label->setLabel("Other Services Row");
                
        $this->id_form_013_other_services = new App_Form_Element_Hidden('id_form_013_other_services', array('label'=>''));
        
		$this->other_service = new App_Form_Element_Text('other_service', array('Label' => 'Service'));
    	$this->other_service->setRequired(true);
    	$this->other_service->setAllowEmpty(false);
    	$this->other_service->removeDecorator('Label');
    	$this->other_service->setDecorators(My_Classes_Decorators::$emptyDecorators);
    	$this->other_service->setAttrib('style', 'width:200px;');
    	
    	$this->other_person_responsible = new App_Form_Element_Text('other_person_responsible', array('Label' => 'Person Responsible'));
    	$this->other_person_responsible->setRequired(true);
    	$this->other_person_responsible->setAllowEmpty(false);
    	$this->other_person_responsible->removeDecorator('Label');
    	$this->other_person_responsible->setDecorators(My_Classes_Decorators::$emptyDecorators);
    	$this->other_person_responsible->setAttrib('style', 'width:200px;');
    	
    	$this->other_funding_source = new App_Form_Element_Text('other_funding_source', array('Label' => 'Funding Source'));
    	$this->other_funding_source->setRequired(true);
    	$this->other_funding_source->setAllowEmpty(false);
    	$this->other_funding_source->removeDecorator('Label');
    	$this->other_funding_source->setDecorators(My_Classes_Decorators::$emptyDecorators);
    	$this->other_funding_source->setAttrib('style', 'width:265px;');
    	
    	$this->other_service_start = new App_Form_Element_DatePicker('other_service_start', array('label'=>'Start Date'));
    	$this->other_service_start->removeDecorator('colorme');
    	$this->other_service_start->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'other_service_start' . '-colorme') );
    	$this->other_service_start->setRequired(true);
    	$this->other_service_start->setAllowEmpty(false);
    	$this->other_service_start->removeDecorator('Label');
    	
    	$this->other_service_end = new App_Form_Element_DatePicker('other_service_end', array('label'=>'End Date'));
    	$this->other_service_end->removeDecorator('colorme');
    	$this->other_service_end->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'other_service_end' . '-colorme') );
    	$this->other_service_end->setRequired(true);
    	$this->other_service_end->setAllowEmpty(false);
    	$this->other_service_end->removeDecorator('Label');
    	
		return $this;			
	}
	
	// override default
	public function subform_header_edit_version9($subformName, $addNotReq = false) {
	
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form013/other_services_header.phtml' ) ) ) );
	
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