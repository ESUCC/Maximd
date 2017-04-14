<?php

class Form_Form018SupplementalForm extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

	public function iep_form_018_supp_edit_version9() {
		return $this->iep_form_018_supp_edit_version1();
	}
	public function iep_form_018_supp_edit_version1() {
		$this->setDecorators( array( array('ViewScript', array('viewScript'=>'form018/iep_form_018_suppform_edit_version1.phtml'))));
		
		$this->rownumber = new App_Form_Element_Hidden('rownumber');
		$this->rownumber->ignore = true;
		
		$this->id_form_018_supp = new App_Form_Element_Hidden('id_form_018_supp');
		
		$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check to remove'));
		$this->remove_row->setDecorators ( My_Classes_Decorators::$labelDecorators);
		$this->remove_row->ignore = true;
		
		$this->title = new App_Form_Element_Text('title', array('label'=>'Title'));
		$this->title->setDecorators ( My_Classes_Decorators::$emptyDecorators);
		$this->title->setAttrib('style', 'width: 98%');
		$this->title->addErrorMessage('Supplemental Form title is empty.');  
		$this->title->setRequired(true);
		$this->title->setAllowEmpty(false);
	
		$this->text_content =  new App_Form_Element_TextareaEditor('text_content', array('label'=>'Content'));
		$this->text_content->setAttrib('height', '160px');
		// $this->text_content->setDecorators ( My_Classes_Decorators::$dojoSubformEditorDecorators);
		$this->text_content->addErrorMessage('Supplemental Form content is empty.');  
		$this->text_content->setRequired(true);
		$this->text_content->setAllowEmpty(false);
		
        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Supplemental Form");
		
        
        return $this;
	}
	
}

