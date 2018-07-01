<?php

class Form_Form029TeamMemberAbsences extends Form_AbstractForm {

	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditor');
	}
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function team_member_absences_edit_version11() {
	    return $this->team_member_absences_edit_version1();
	}
	
	public function team_member_absences_edit_version10() {
	    return $this->team_member_absences_edit_version1();
	}
	public function team_member_absences_edit_version9() {
		return $this->team_member_absences_edit_version1();
	}
	public function team_member_absences_edit_version2() {
		return $this->team_member_absences_edit_version1();
	}
	public function team_member_absences_edit_version3() {
		return $this->team_member_absences_edit_version1();
	}
	public function team_member_absences_edit_version4() {
		return $this->team_member_absences_edit_version1();
	}
	public function team_member_absences_edit_version5() {
		return $this->team_member_absences_edit_version1();
	}
	public function team_member_absences_edit_version6() {
		return $this->team_member_absences_edit_version1();
	}
	public function team_member_absences_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form029/team_member_absence_edit_version1.phtml' ) ) ) );
		//
        // these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        //
        $this->subformIdentifier = new App_Form_Element_Hidden('subformIdentifier');
        $this->subformIdentifier->ignore = true;
		
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;
		
        $this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check and save to remove this Team Member Absence:'));
		$this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->remove_row->ignore = true;
		
        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Team Member Absence Row");
                
        $this->id_iep_absence = new App_Form_Element_Hidden('id_iep_absence');
        
		//
        // visible fields
        //
		$this->absence_name = new App_Form_Element_Text('absence_name', array('label'=>'Name of Team Member'));
        $this->absence_name->setDecorators(App_Form_DecoratorHelper::inlineElement());

	    $multiOptions = array('notmod'=>"The IEP team member's area of curriculum or related services is not being modified or discussed in the meeting.", 
	                          'mod'=>"The IEP team member's area of curriculum or related services is being modified or discussed in the meeting and in lieu of his or her attendance has submitted written input into the development of the IEP to the parent(s) and school district prior to the meeting.");
		$this->area_of_curriculum = new App_Form_Element_Radio('area_of_curriculum', array('label'=>'Area of curriculum', 'multiOptions'=>$multiOptions));
        $this->area_of_curriculum->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->area_of_curriculum->setAttrib('label_class', 'sc_bulletInputLeft');
		$this->area_of_curriculum->setSeparator('<br/>');
		$this->area_of_curriculum->removeDecorator('label');
		$this->area_of_curriculum->setAttrib('onclick', "toggleTeamMemberInput(this.value, this.id);");
		
		$arrLabel = array("Parent", "Student", "Regular education teacher", "Special education teacher or provider", "School district representative", "Individual to interpret evaluation results", "Service agency representative", "Nonpublic representative", "Other agency representative", "Educator of the hearing impaired", "Other (Please Specify)");
		$arrValue = array("Parent", "Student", "Regular education teacher", "Special education teacher or provider", "School district representative", "Individual to interpret evaluation results", "Service agency representative", "Nonpublic representative", "Other agency representative", "Educator of the hearing impaired", "Other (Please Specify)");
        $multiOptions = array_combine($arrValue, $arrLabel);
		$this->absence_role = new App_Form_Element_Select('absence_role', array('label'=>'Roles/Responsibilities', 'multiOptions'=>$multiOptions));
        $this->absence_role->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->absence_role->setAttrib('onchange', 
			$this->absence_role->getAttrib('onchange') . 
		  	"subformShowHideField(this.id, 'absence_role_other', this.value, 'Other (Please Specify)');"
		); 
        $this->absence_role->removeDecorator('label');
		
		$this->absence_role_other = new App_Form_Element_Text('absence_role_other', array('label'=>'Other'));
        $this->absence_role_other->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->absence_role_other->setRequired(false);
        $this->absence_role_other->setAllowEmpty(false);
		$this->absence_role_other->addValidator(new My_Validate_NotEmptyIf('absence_role', 'Other (Please Specify)'));  
        $this->absence_role_other->addErrorMessage('Absence Role Other cannot be empty when Absence Role is Other (Please Specify).');  
        
		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->doc_signed_parent = new App_Form_Element_Radio('doc_signed_parent', array('label'=>'Parent Signature on file:', 'multiOptions'=>$multiOptions)); 
        $this->doc_signed_parent->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->doc_signed_parent->addErrorMessage('Please indicate whether the parent signature is on file.');  
		$this->doc_signed_parent->addValidator(new My_Validate_BooleanNotEmpty(), true);  
		$this->doc_signed_parent->setAllowEmpty(false);  
				
		$this->date_doc_signed_parent = new App_Form_Element_DatePicker('date_doc_signed_parent', array('label' => 'Date document signed by parent:'));
        $this->date_doc_signed_parent->setDecorators(App_Form_DecoratorHelper::inlineElement(false));
        $this->date_doc_signed_parent->setRequired(false);
        $this->date_doc_signed_parent->setAllowEmpty(false);
        $this->date_doc_signed_parent->addValidator(new My_Validate_NotEmptyIf('doc_signed_parent', true));
        $this->date_doc_signed_parent->addValidator(new My_Validate_EmptyIf('doc_signed_parent', false));
        $this->date_doc_signed_parent->addErrorMessage('cannot be empty when Parent Signature is Yes and must be empty when Parent Signature is No.');  
        
		$this->no_sig_explanation = new App_Form_Element_Text('no_sig_explanation', array('label'=>"(If 'No' selected above, please explain)"));
        $this->no_sig_explanation->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->no_sig_explanation->setAttrib('size', '26');
		$this->no_sig_explanation->setRequired(false);
        $this->no_sig_explanation->setAllowEmpty(false);
		$this->no_sig_explanation->addValidator(new My_Validate_NotEmptyIf('doc_signed_parent', false));
		$this->no_sig_explanation->addValidator(new My_Validate_EmptyIf('doc_signed_parent', true));
		$this->no_sig_explanation->addErrorMessage('cannot be empty when Parent Signature is No and must be empty when Parent Signature is Yes.');  
		$this->no_sig_explanation->getDecorator('Label')->setOption('class', 'noprint');
		
        $this->input_information = $this->buildEditor('input_information');
        $this->input_information->setRequired(false);
        $this->input_information->setAllowEmpty(false);
        $this->input_information->removeEditorEmptyValidator();
		$this->input_information->addErrorMessage('If the team member\'s area of curriculum will be discussed, team member input must be provided.');  
        $this->input_information->addValidator(new My_Validate_EditorNotEmptyIf('area_of_curriculum', 'mod'));

        return $this;			
	}
	
}

