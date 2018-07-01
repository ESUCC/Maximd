<?php

class Form_Form018TeamMember extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

	public function iep_form_018_team_member_edit_version9() {
		return $this->iep_form_018_team_member_edit_version1();
	}
	public function iep_form_018_team_member_edit_version1() {
		$this->setDecorators ( array (
//				                'PrepareElements', 
								array ('ViewScript', 
									array (
										'viewScript' => 'form018/team_member_edit_v1.phtml' 
									) 
								) 
							) );
		
        $this->id_form_018_team_member = new App_Form_Element_Hidden('id_form_018_team_member');
//        $this->id_form_018_team_member->ignore = true;
				
	// required field for subform
	$this->rownumber = new App_Form_Element_TextareaEditor('rownumber');
	$this->rownumber->ignore = true;
		        
        // named displayed in validation output
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("TeamMember");
		
	$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check and save to remove tab:'));
	$this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
	$this->remove_row->ignore = true;

	$this->name_or_position = new App_Form_Element_Text('name_or_position', array('label'=>'Name/Position'));
        $this->name_or_position->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->name_or_position->setRequired(true);
        $this->name_or_position->setAllowEmpty(false);
	$this->name_or_position->addErrorMessage("Name/Position must be entered.");
	
        $this->phone = new App_Form_Element_Text('phone', array('label'=>'Phone'));
        $this->phone->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->phone->setRequired(true);
        $this->phone->setAllowEmpty(false);
	$this->phone->addErrorMessage("You must enter a valid Phone, example: 111-222-3333.");
	
        $this->email = new App_Form_Element_Text('email', array('label'=>'Email'));
        $this->email->setDecorators(App_Form_DecoratorHelper::inlineElement());
	$this->email->setRequired(true);
        $this->email->setAllowEmpty(false);
	$this->email->addErrorMessage("Email must be entered.");
	
		return $this;
	}
	
}
