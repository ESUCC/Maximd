<?php

class Form_Form017 extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		$this->id_form_017 = new App_Form_Element_Hidden('id_form_017');
      	$this->id_form_017->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
	}
	public function view_p1_v1() {

		$this->initialize();
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form017/form017_view_page1_version1.phtml' ) ) ) );
		return $this;
	}
	
    public function edit_p1_v2() { return $this->edit_p1_v1();}
    public function edit_p1_v3() { return $this->edit_p1_v1();}
    public function edit_p1_v4() { return $this->edit_p1_v1();}
    public function edit_p1_v5() { return $this->edit_p1_v1();}
    public function edit_p1_v6() { return $this->edit_p1_v1();}
    public function edit_p1_v7() { return $this->edit_p1_v1();}
    public function edit_p1_v8() { return $this->edit_p1_v1();}
    public function edit_p1_v9() { return $this->edit_p1_v1();}
	public function edit_p1_v1() {
		
		$this->initialize();
		
		$this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
		$this->date_notice->addErrorMessage("Date of Notice is empty.");
		$this->date_notice->removeDecorator('Label');
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form017/form017_edit_page1_version1.phtml' ) ) ) );

		$this->title = new App_Form_Element_Text('title', array('label'=>'Title'));
		$this->title->removeDecorator('Label');
		
		$this->title->setAttrib('style', 'width: 99%;');
		$this->dialog_text = new App_Form_Element_TestEditor('dialog_text', array('label'=>'Text'));
		$this->dialog_text->setAttrib('height', '350px');
		
		return $this;
	}
}