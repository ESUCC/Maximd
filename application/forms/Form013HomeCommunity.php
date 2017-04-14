<?php

class Form_Form013HomeCommunity extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function home_community_edit_version10() {
	    return $this->home_community_edit_version1();
	}
    public function home_community_edit_version9() {
        return $this->home_community_edit_version1();
    }
    public function home_community_edit_version8() {
        return $this->home_community_edit_version1();
    }
    public function home_community_edit_version7() {
        return $this->home_community_edit_version1();
    }
    public function home_community_edit_version6() {
        return $this->home_community_edit_version1();
    }
    public function home_community_edit_version5() {
        return $this->home_community_edit_version1();
    }
    public function home_community_edit_version4() {
        return $this->home_community_edit_version1();
    }
    public function home_community_edit_version3() {
        return $this->home_community_edit_version1();
    }
    public function home_community_edit_version2() {
        return $this->home_community_edit_version1();
    }
    public function home_community_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form013/home_community_edit_version1.phtml' ) ) ) );
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
        $this->subform_label->setLabel("Waiver Services");
                
        $this->id_form_013_home_community = new App_Form_Element_Hidden('id_form_013_home_community', array('label'=>''));
    	
        $this->home_service = new App_Form_Element_Text('home_service', array('Label' => 'Service'));
        $this->home_service->setRequired(true);
        $this->home_service->setAllowEmpty(false);
        $this->home_service->removeDecorator('Label');
        $this->home_service->setDecorators(My_Classes_Decorators::$emptyDecorators);
        $this->home_service->setAttrib('style', 'width:270px;');
         
        $this->home_how_much = new App_Form_Element_Text('home_how_much', array('Label' => 'How Much'));
        $this->home_how_much->setRequired(true);
        $this->home_how_much->setAllowEmpty(false);
        $this->home_how_much->removeDecorator('Label');
        $this->home_how_much->setDecorators(My_Classes_Decorators::$emptyDecorators);
         
        $this->home_to_help_with_outcome = new App_Form_Element_Text('home_to_help_with_outcome', array('Label' => 'To Help with Outcome'));
        $this->home_to_help_with_outcome->setRequired(true);
        $this->home_to_help_with_outcome->setAllowEmpty(false);
        $this->home_to_help_with_outcome->removeDecorator('Label');
        $this->home_to_help_with_outcome->setDecorators(My_Classes_Decorators::$emptyDecorators);
        $this->home_to_help_with_outcome->setAttrib('style', 'width:200px;');
        
        $this->home_funding_source = new App_Form_Element_Text('home_funding_source', array('Label' => 'Funding Source'));
        $this->home_funding_source->setRequired(true);
        $this->home_funding_source->setAllowEmpty(false);
        $this->home_funding_source->removeDecorator('Label');
        $this->home_funding_source->setDecorators(My_Classes_Decorators::$emptyDecorators);
        $this->home_funding_source->setAttrib('style', 'width:340px;');
         
        $this->home_start = new App_Form_Element_DatePicker('home_start', array('label'=>'Start Date'));
        $this->home_start->removeDecorator('colorme');
        $this->home_start->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'home_start' . '-colorme') );
        $this->home_start->setRequired(true);
        $this->home_start->setAllowEmpty(false);
        $this->home_start->removeDecorator('Label');
         
        $this->home_end = new App_Form_Element_DatePicker('home_end', array('label'=>'End Date'));
        $this->home_end->removeDecorator('colorme');
        $this->home_end->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'home_end' . '-colorme') );
        $this->home_end->setRequired(true);
        $this->home_end->setAllowEmpty(false);
        $this->home_end->removeDecorator('Label');
        
		return $this;			
	}
	
	// override default
	public function subform_header_edit_version9($subformName, $addNotReq = false) {
	
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form013/home_community_header.phtml' ) ) ) );
	
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