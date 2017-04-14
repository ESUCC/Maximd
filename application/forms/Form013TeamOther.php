<?php

class Form_Form013TeamOther extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function team_other_edit_version10() {
	    return $this->team_other_edit_version1();
	}
	public function team_other_edit_version9() {
		return $this->team_other_edit_version1();
	}
	public function team_other_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form013/team_other_edit_version1.phtml' ) ) ) );
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
        $this->subform_label->setLabel("Parent/Guardian Row");

        $this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check to remove'));
        $this->remove_row->setDecorators ( My_Classes_Decorators::$labelDecorators);
		$this->remove_row->removeDecorator('label');
        $this->remove_row->ignore = true;
        
        $this->id_ifsp_team_other = new App_Form_Element_Hidden('id_ifsp_team_other', array('label'=>''));
        
        // visible fields
        $this->tmo_name = new App_Form_Element_Text('tmo_name', array('label'=>'Name'));
        $this->tmo_name->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->tmo_name->setRequired(true);
        $this->tmo_name->setAllowEmpty(false);
        $this->tmo_name->removeDecorator('label');
		$this->tmo_name->addErrorMessage("Name must be entered.");

        $this->tmo_role = new App_Form_Element_Text('tmo_role', array('label'=>'Role'));
        $this->tmo_role->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->tmo_role->setAttrib('size', '15');
        $this->tmo_role->setRequired(true);
        $this->tmo_role->setAllowEmpty(false);
        $this->tmo_role->addValidator(new My_Validate_NotEmptyIf('tmo_name'));
        $this->tmo_role->removeDecorator('label');
		$this->tmo_role->addErrorMessage("Role must be entered.");
        
        $this->tmo_address = new App_Form_Element_TextareaPlain('tmo_address', array('label'=>'Address'));
        $this->tmo_address->setDecorators(App_Form_DecoratorHelper::inlineElement());
//        $this->tmo_address->setAttrib('size', '35');
        $this->tmo_address->setRequired(true);
		$this->tmo_address->setAllowEmpty(false);
        $this->tmo_address->addValidator(new My_Validate_NotEmptyIf('tmo_name'));
        $this->tmo_address->removeDecorator('label');
        $this->tmo_address->addErrorMessage("Address must be entered.");
        $this->tmo_address->setAttrib('rows', 3);
        $this->tmo_address->setAttrib('cols', 40);
        
        $this->tmo_initial = new App_Form_Element_Text('tmo_initial', array('label'=>'Family initial for copy of pages sent'));
        $this->tmo_initial->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->tmo_initial->addValidator(new My_Validate_NotEmptyIf('tmo_name'));
        $this->tmo_initial->setRequired(false);
        $this->tmo_initial->setAttrib('size', '12');
        $this->tmo_initial->removeDecorator('label');
                
        
        return $this;			
	}
	
	
	
}

