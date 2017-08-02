<?php

class Form_Form018Agency extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

	public function iep_form_018_agency_edit_version9() {
		return $this->iep_form_018_agency_edit_version1();
	}
	public function iep_form_018_agency_edit_version1() {
		$this->setDecorators ( array (
//				                'PrepareElements', 
								array ('ViewScript', 
									array (
										'viewScript' => 'form018/agency_edit_v1.phtml' 
									) 
								) 
							) );
		
		$this->id_form_018_agency = new Zend_Form_Element_Hidden('id_form_018_agency');
		$this->id_form_018_agency = new App_Form_Element_Hidden('id_form_018_agency');
		$this->id_form_018_agency->ignore = true;
		        
		// required field for subform
		$this->rownumber = new App_Form_Element_Hidden('rownumber');
		$this->rownumber->ignore = true;
		        
		//
		// named displayed in validation output
		//
		$this->subform_label = new App_Form_Element_Hidden('subform_label ');
		$this->subform_label->ignore = true;
		$this->subform_label->setLabel("Community Contact");
		
		$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check and save to remove tab:'));
		$this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->remove_row->ignore = true;
		
		$this->agency = new App_Form_Element_Text('agency', array('label'=>'Agency'));
		$this->agency->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->agency->setRequired(true);
		$this->agency->setAllowEmpty(false);
		$this->agency->addErrorMessage("Agency must be entered.");
		
		$this->name_or_position = new App_Form_Element_Text('name_or_position', array('label'=>'Name/Position'));
		//$this->name_or_position->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->name_or_position->setRequired(true);
		$this->name_or_position->setAllowEmpty(false);
		$this->name_or_position->setAttrib("style", "width:110px;");
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
		
		$this->status_of_referral = new App_Form_Element_Select('status_of_referral', 
		    array(
		        'label'=>'Primary Disability', 
		        'multiOptions'=>App_Form_ValueListHelper::referralStatus()
		    )
		);
		//        $this->status_of_referral->setAttrib('onclick', "modified();toggleShowSLDAreas();toggleShowMultiAreas()");
		$this->status_of_referral->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->status_of_referral->setRequired(true);
		$this->status_of_referral->setAllowEmpty(false);
		$this->status_of_referral->addErrorMessage("Status of Referral must be entered.");
		
		$this->status_other = new App_Form_Element_Text('status_other', array('label'=>'Status Other'));
		$this->status_other->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->status_other->setRequired(false);
		$this->status_other->setAllowEmpty(false);
		$this->status_other->setAttrib("style", "width:110px;");
		$this->status_other->addValidator(new My_Validate_NotEmptyIf('status_of_referral', 'Other'));
		$this->status_other->addErrorMessage("Other must be entered when Status is Other.");
		
		return $this;
	}
	
}
