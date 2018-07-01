<?php

class Form_Form018Goal extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

	public function iep_form_018_goal_edit_version9() {
		return $this->iep_form_018_goal_edit_version1();
	}
	public function iep_form_018_goal_edit_version1() {
		$this->setDecorators ( array (
//				                'PrepareElements', 
								array ('ViewScript', 
									array (
										'viewScript' => 'form018/goal_edit_v1.phtml' 
									) 
								) 
							) );
		
	$this->id_form_018_goal = new Zend_Form_Element_Hidden('id_form_018_goal');
        $this->id_form_018_goal = new App_Form_Element_Hidden('id_form_018_goal');
        $this->id_form_018_goal->ignore = true;
				
		// required field for subform
		$this->rownumber = new App_Form_Element_TextareaEditor('rownumber');
		$this->rownumber->ignore = true;
		        
        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Goal");
		
		$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check and save to remove tab:'));
		$this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->remove_row->ignore = true;

		
        $this->post_secondary =  new App_Form_Element_TextareaEditor('post_secondary', array('label'=>'Post Secondary Goal:'));
	$this->post_secondary->setRequired(true);
        $this->post_secondary->setAllowEmpty(false);
	$this->post_secondary->addErrorMessage("Post Secondary Goal must be entered.");
        // $this->post_secondary->setDecorators ( My_Classes_Decorators::$dojoSubformEditorDecorators);
        
        $this->related_activity =  new App_Form_Element_TextareaEditor('related_activity', array('label'=>'Related Activity:'));
	$this->related_activity->setRequired(true);
        $this->related_activity->setAllowEmpty(false);
	$this->related_activity->addErrorMessage("Related Activities/Accomplishments must be entered.");
        // $this->related_activity->setDecorators ( My_Classes_Decorators::$dojoSubformEditorDecorators);
        
        $this->recommendation =  new App_Form_Element_TextareaEditor('recommendation', array('label'=>'Recommendation:'));
	$this->recommendation->setRequired(true);
        $this->recommendation->setAllowEmpty(false);
	$this->recommendation->addErrorMessage("Recommendations to Assist must be entered.");
        // $this->recommendation->setDecorators ( My_Classes_Decorators::$dojoSubformEditorDecorators);
        
		return $this;
	}
	
}
