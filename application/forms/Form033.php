<?php

class Form_Form033 extends Form_AbstractForm {

    private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    protected function initialize() {
        parent::initialize();

        $this->id_form_033 = new App_Form_Element_Hidden('id_form_033');
        $this->id_form_033->ignore = true;

        $multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
        $this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
        $this->form_editor_type->setRequired(false);
        $this->form_editor_type->setAllowEmpty(true);
        $this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
        $this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');

    }

    public function view_p1_v1() {
        $this->initialize();
        $this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form033/form033_view_page1_version1.phtml' ) ) ) );
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

        //Setting the decorator for the element to a single, ViewScript, decorator,
        //specifying the viewScript as an option, and some extra options: 
        $this->setDecorators ( array (array ('ViewScript', array (
            'viewScript' => 'form033/form033_edit_page1_version1.phtml' ) ) ) );

        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");

        // custom fields
        $this->contact_for_questions = new App_Form_Element_Text('contact_for_questions', array('label' => 'If there are any questions regarding your rights, please contact:'));
        $this->contact_for_questions->setRequired(false);
        $this->contact_for_questions->setAllowEmpty(true);

        $this->services_and_locations = new App_Form_Element_TextareaEditor('services_and_locations', array('label' => 'Types of Services and Locations Discussed'));
        $this->services_and_locations->addErrorMessage("Types of Services and Locations Discussed is empty.");
        $this->services_and_locations->setRequired(true);
        $this->services_and_locations->setAllowEmpty(false);

        $this->signature_on_file = new App_Form_Element_Checkbox('signature_on_file', array('label'=>'Signature on file', 'description'=>'(check here to indicate that signature is on file)'));
        $this->signature_on_file->addErrorMessage("must be checked.");
        $this->signature_on_file->addValidator(new Zend_Validate_InArray(array('t')), false);

        $this->signature_on_file_date = new App_Form_Element_DatePicker('signature_on_file_date', array('label'=>'Date of Parent Signature'));
        $this->signature_on_file_date->setRequired(false);
        $this->signature_on_file_date->setAllowEmpty(false);
        $this->signature_on_file_date->addValidator(new My_Validate_NotEmptyIf('signature_on_file', 't'));
        $this->signature_on_file_date->addErrorMessage("Signature Date");

        return $this;
    }
    public function checkBoxLeft($name)
    {
        $decorators = array(
            array('Label', array('tag' => 'span')),
            'ViewHelper',
            array('Description', array('tag' => 'span')),
            array('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $name . '-colorme')),
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
        );
        return $decorators;
    }

}