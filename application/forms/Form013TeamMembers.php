<?php

class Form_Form013TeamMembers extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function team_members_edit_version10() {
	    return $this->team_members_edit_version1();
	}
	public function team_members_edit_version9() {
		return $this->team_members_edit_version1();
	}
	public function team_members_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form013/team_members_edit_version1.phtml' ) ) ) );
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
        $this->remove_row->ignore = true;
		$this->remove_row->removeDecorator('label');
        
        $this->id_ifsp_team_members = new App_Form_Element_Hidden('id_ifsp_team_members', array('label'=>''));
        
        // visible fields
        

        $this->tm_signature = new App_Form_Element_Text('tm_signature', array('label'=>'Print Name'));
        $this->tm_signature->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->tm_signature->setAttrib('size', '15');
        $this->tm_signature->removeDecorator('label');
		$this->tm_signature->addErrorMessage("Print Name must be entered.");
        
        $multiOptions = App_Form_ValueListHelper::ifspRole();
        $this->tm_role = new App_Form_Element_Select('tm_role', array('label'=>'Role', 'multiOptions' => $multiOptions));
        $this->tm_role->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->tm_role->removeDecorator('label');
		$this->tm_role->addErrorMessage("Role must be entered.");
        $this->tm_role->setAttrib('class', 'tm_role');

        $this->tm_role_other = new App_Form_Element_Text('tm_role_other', array('label'=>'Role Other'));
        $this->tm_role_other->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->tm_role_other->setRequired(false);
        $this->tm_role_other->setAllowEmpty(false);
        $this->tm_role_other->addValidator(new My_Validate_NotEmptyIf('tm_role', 'Other'));
        $this->tm_role_other->setAttrib('size', '15');
        $this->tm_role_other->removeDecorator('label');
		$this->tm_role_other->addErrorMessage("Other must be entered when Role is Other.");
        $this->tm_role_other->setAttrib('class', 'tm_role_other');

        $this->tm_address = new App_Form_Element_TextareaPlain('tm_address', array('label'=>'Address and Phone'));
        $this->tm_address->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->tm_address->removeDecorator('label');
		$this->tm_address->addErrorMessage("Address must be entered.");
        $this->tm_address->setAttrib('rows', 3);
        $this->tm_address->setAttrib('cols', 40);
		
        /* // Commenting this out for SRSSUPP-550
        $this->tm_sig_on_file = new App_Form_Element_Checkbox('tm_sig_on_file', array('label'=>'Sig on File'));
        $this->tm_sig_on_file->setRequired(true);        
        $this->tm_sig_on_file->setAllowEmpty(false);
        $this->tm_sig_on_file->setCheckedValue(1);
        $this->tm_sig_on_file->addValidator(new My_Validate_BooleanTrue(), true);
        $this->tm_sig_on_file->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->tm_sig_on_file->removeDecorator('label');
		$this->tm_sig_on_file->addErrorMessage("Sig on file must be entered.");
	    */ 
                
        return $this;			
	}
	
	
	
}

