<?php

class Form_Form027 extends Form_AbstractForm {

    var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function init()
    {
        $this->setEditorType('App_Form_Element_TestEditor');
    }

    protected function initialize() {
        parent::initialize();

        $this->id_form_027 = new App_Form_Element_Hidden('id_form_027');
        $this->id_form_027->ignore = true;

        $multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
        $this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
        $this->form_editor_type->setRequired(false);
        $this->form_editor_type->setAllowEmpty(true);
        $this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
        $this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');

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

		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options:
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form027/form027_edit_page1_version1.phtml' ) ) ) );

		$this->conduct_screening_procedures = $this->buildEditor('conduct_screening_procedures', array('Label'=>'The district proposes to conduct screening procedures with your child because:'));
		$this->conduct_screening_procedures->addErrorMessage('The district proposes to conduct screening procedures with your child is empty. If the item does not apply to this student, please explain why.');
		
		$this->rights_contact = new App_Form_Element_Text('rights_contact');
		$this->rights_contact->setAttrib('style', 'width:150px;');
		$this->rights_contact->removeDecorator('Label');
		$this->rights_contact->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'rights_contact' . '-colorme') );
		
		$this->rights_contact_at = new App_Form_Element_Text('rights_contact_at');
		$this->rights_contact_at->setAttrib('style', 'width:150px;');
		$this->rights_contact_at->removeDecorator('Label');
		$this->rights_contact_at->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'rights_contact_at' . '-colorme') );
		
		return $this;
	}
    public function edit_p2_v2() { return $this->edit_p2_v1();}
    public function edit_p2_v3() { return $this->edit_p2_v1();}
    public function edit_p2_v4() { return $this->edit_p2_v1();}
    public function edit_p2_v5() { return $this->edit_p2_v1();}
    public function edit_p2_v6() { return $this->edit_p2_v1();}
    public function edit_p2_v7() { return $this->edit_p2_v1();}
    public function edit_p2_v8() { return $this->edit_p2_v1();}
    public function edit_p2_v9() { return $this->edit_p2_v1();}
    public function edit_p2_v1() {

        $this->initialize();

        //Setting the decorator for the element to a single, ViewScript, decorator,
        //specifying the viewScript as an option, and some extra options:
        $this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form027/form027_edit_page2_version1.phtml' ) ) ) );

        $this->parent_sig_screening_consent = new App_Form_Element_Checkbox('parent_sig_screening_consent', array('label'=>'Signature on File:'));
        $this->parent_sig_screening_consent->removeDecorator('colorme');
        $this->parent_sig_screening_consent->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'parent_sig_screening_consent' . '-colorme') );
        $this->parent_sig_screening_consent->removeDecorator('Label');
        $this->parent_sig_screening_consent->setRequired(false);
        $this->parent_sig_screening_consent->setAllowEmpty(false);
        $this->parent_sig_screening_consent->addValidator(new My_Validate_BooleanAtLeastOne("t", array('parent_sig_screening_consent', 'parent_sig_screening_non_consent', 'parent_sig_screening_non_consent_request')));
        $this->parent_sig_screening_consent->addErrorMessage(
        		'You must check at least one signature on file option.'
        );
        
        $this->parent_sig_screening_consent_date = new App_Form_Element_DatePicker('parent_sig_screening_consent_date', array('label'=>'Date'));
        $this->parent_sig_screening_consent_date->removeDecorator('colorme');
        $this->parent_sig_screening_consent_date->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'parent_sig_screening_consent_date' . '-colorme') );
        $this->parent_sig_screening_consent_date->removeDecorator('Label');
        $this->parent_sig_screening_consent_date->setRequired(false);
        $this->parent_sig_screening_consent_date->setAllowEmpty(false);
        $this->parent_sig_screening_consent_date->addValidator(new My_Validate_NotEmptyIf('parent_sig_screening_consent', 't'));
        
        $this->parent_sig_screening_non_consent = new App_Form_Element_Checkbox('parent_sig_screening_non_consent', array('label'=>'Signature on File:'));
        $this->parent_sig_screening_non_consent->removeDecorator('colorme');
        $this->parent_sig_screening_non_consent->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'parent_sig_screening_non_consent' . '-colorme') );
        $this->parent_sig_screening_non_consent->removeDecorator('Label');
        
        $this->parent_sig_screening_non_consent_date = new App_Form_Element_DatePicker('parent_sig_screening_non_consent_date', array('label'=>'Date'));
        $this->parent_sig_screening_non_consent_date->removeDecorator('colorme');
        $this->parent_sig_screening_non_consent_date->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'parent_sig_screening_non_consent_date' . '-colorme') );
        $this->parent_sig_screening_non_consent_date->removeDecorator('Label');
        $this->parent_sig_screening_non_consent_date->setRequired(false);
        $this->parent_sig_screening_non_consent_date->setAllowEmpty(false);
        $this->parent_sig_screening_non_consent_date->addValidator(new My_Validate_NotEmptyIf('parent_sig_screening_non_consent', 't'));
        
        $this->parent_sig_screening_non_consent_request = new App_Form_Element_Checkbox('parent_sig_screening_non_consent_request', array('label'=>'Signature on File:'));
        $this->parent_sig_screening_non_consent_request->removeDecorator('colorme');
        $this->parent_sig_screening_non_consent_request->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'parent_sig_screening_non_consent_request' . '-colorme') );
        $this->parent_sig_screening_non_consent_request->removeDecorator('Label');
        
        $this->parent_sig_screening_non_consent_request_date = new App_Form_Element_DatePicker('parent_sig_screening_non_consent_request_date', array('label'=>'Date'));
        $this->parent_sig_screening_non_consent_request_date->removeDecorator('colorme');
        $this->parent_sig_screening_non_consent_request_date->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'parent_sig_screening_non_consent_request_date' . '-colorme') );
        $this->parent_sig_screening_non_consent_request_date->removeDecorator('Label');
        $this->parent_sig_screening_non_consent_request_date->setRequired(false);
        $this->parent_sig_screening_non_consent_request_date->setAllowEmpty(false);
        $this->parent_sig_screening_non_consent_request_date->addValidator(new My_Validate_NotEmptyIf('parent_sig_screening_non_consent_request', 't'));
    
        return $this;

    }
}