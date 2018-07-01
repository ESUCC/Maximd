<?php

class Form_Form004SecondaryGoal extends Form_AbstractForm {

    private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	public function init()
	{
		$this->setEditorType('App_Form_Element_TinyMceTextarea');
	}
    
    public function iep_form_004_secondary_goal_edit_version11() { return $this->iep_form_004_secondary_goal_edit_version1(); }
    public function iep_form_004_secondary_goal_edit_version10() { return $this->iep_form_004_secondary_goal_edit_version1(); }
    public function iep_form_004_secondary_goal_edit_version9() { return $this->iep_form_004_secondary_goal_edit_version1(); }
    public function iep_form_004_secondary_goal_edit_version8() { return $this->iep_form_004_secondary_goal_edit_version1(); }
    public function iep_form_004_secondary_goal_edit_version7() { return $this->iep_form_004_secondary_goal_edit_version1(); }
    public function iep_form_004_secondary_goal_edit_version6() { return $this->iep_form_004_secondary_goal_edit_version1(); }
    public function iep_form_004_secondary_goal_edit_version5() { return $this->iep_form_004_secondary_goal_edit_version1(); }
    public function iep_form_004_secondary_goal_edit_version4() { return $this->iep_form_004_secondary_goal_edit_version1(); }
    public function iep_form_004_secondary_goal_edit_version3() { return $this->iep_form_004_secondary_goal_edit_version1(); }
    public function iep_form_004_secondary_goal_edit_version2() { return $this->iep_form_004_secondary_goal_edit_version1(); }
    public function iep_form_004_secondary_goal_edit_version1() {
        
        $this->setDecorators ( array (
//                              'PrepareElements', 
                                array ('ViewScript', 
                                    array (
                                        'viewScript' => 'form004/iep_form_004_secondary_goal_edit_version1.phtml' 
                                    ) 
                                ) 
                            ) );

        $view = $this->getView();        

        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;

        $this->id_form_004_secondary_goal = new App_Form_Element_Hidden('id_form_004_secondary_goal');
        
        $this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>' '));
        $this->remove_row->setDecorators ( My_Classes_Decorators::$labelDecoratorsNoSpan);
        $this->remove_row->ignore = true;

        $this->dob_sub = new App_Form_Element_Hidden('dob_sub');
        $this->dob_sub->ignore = true;

        $this->transition_plan_sub = new App_Form_Element_Hidden('transition_plan_sub');
        $this->transition_plan_sub->ignore = true;
        
        $this->post_secondary =  $this->buildEditor('post_secondary', array('label'=>'Post Secondary:'));
        // $this->post_secondary->setDecorators ( My_Classes_Decorators::$dojoSubformEditorDecorators);
        $this->post_secondary->addErrorMessage('Post Secondary is empty.');  
        $this->post_secondary->setRequired(false);
        $this->post_secondary->setAllowEmpty(false);
        $this->post_secondary->removeEditorEmptyValidator();
        $this->post_secondary->addValidator(new My_Validate_NoValidationIfAgeUnder('dob_sub', '+15 years 1 day'));
        $this->post_secondary->addValidator(new My_Validate_EditorNotEmptyIf('transition_plan_sub', 't'));
        
        return $this;
                
    }

    public function edit_subform_version1_header($subformName, $addNotReq) {//, $addNewRowButton=true, $addNotReq=true

        $this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form004/secondary_goal_header.phtml' ) ) ) );
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

        return $this;
    }

}
