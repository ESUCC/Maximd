<?php

class Form_Form002SupplementalForm extends Form_AbstractForm {
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditor');
	}

	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

	public function form_002_suppform_edit_version2() {
		return $this->form_002_suppform_edit_version1();
	}
	public function form_002_suppform_edit_version3() {
		return $this->form_002_suppform_edit_version1();
	}
	public function form_002_suppform_edit_version4() {
		return $this->form_002_suppform_edit_version1();
	}
	public function form_002_suppform_edit_version5() {
		return $this->form_002_suppform_edit_version1();
	}
	public function form_002_suppform_edit_version9() {
		return $this->form_002_suppform_edit_version1();
	}
	public function form_002_suppform_edit_version10() {
		return $this->form_002_suppform_edit_version1();
	}
	public function form_002_suppform_edit_version1() {
		$this->setDecorators( array( array('ViewScript', array('viewScript'=>'form002/form_002_suppform_edit_version1.phtml'))));
		
		$this->rownumber = new App_Form_Element_Hidden('rownumber');
		$this->rownumber->ignore = true;
		
		$this->id_form_002_supplemental_form = new App_Form_Element_Hidden('id_form_002_supplemental_form');
		
		$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check to remove'));
		$this->remove_row->setDecorators ( My_Classes_Decorators::$labelDecorators);
		$this->remove_row->ignore = true;
		
		$this->title = new App_Form_Element_Text('title', array('label'=>'Title'));
		$this->title->setDecorators ( My_Classes_Decorators::$emptyDecorators);
		$this->title->setAttrib('style', 'width: 98%');
		$this->title->addErrorMessage('Supplemental Form title is empty.');  
		
 		$this->text =  new App_Form_Element_TinyMceTextarea('text', array('label'=>'Content'));
		$this->text->setAttrib('height', '480px');
		$this->text->addErrorMessage('Supplemental Form content is empty.');

		
        // named displayed in validation output
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Supplemental Form");
		
        
        return $this;
	}
	
}

