<?php

class Form_Form005 extends Form_AbstractForm
{

    var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function init()
    {
        $this->setEditorType('App_Form_Element_TestEditor');
    }

    protected function initialize()
    {
        parent::initialize();

        $this->id_form_005 = new App_Form_Element_Hidden('id_form_005');
        $this->id_form_005->ignore = true;

        $multiOptions = array('testEditor' => 'Existing Editor', 'tinyMce' => 'TinyMce');
        $this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions' => $multiOptions));
        $this->form_editor_type->setRequired(false);
        $this->form_editor_type->setAllowEmpty(true);
        $this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange') . 'setToRefresh();');
        $this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');

    }

    public function view_page1_version1()
    {

        $this->initialize();

        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'form005/form005_view_page1_version1.phtml'))));

        return $this;
    }

    public function edit_p1_v2()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v3()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v4()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v5()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v6()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v7()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v8()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v9()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v1()
    {

        $this->initialize();

        // Setting the decorator for the element to a single, ViewScript, decorator,
        // specifying the viewScript as an option, and some extra options:
        $this->setDecorators(array(array('ViewScript', array(
            'viewScript' => 'form005/form005_edit_page1_version1.phtml'))));

        $descriptionBefore = array(
            array('Description', array('tag' => 'span')),
            'DijitElement',
            array('HtmlTag', array('tag' => 'div', 'class' => 'colorme', 'id' => $this->getName() . '-colorme'))
        );

        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");

        $this->date_met = new App_Form_Element_DatePicker('date_met', array('label' => "Date Met"));
        $this->date_met->setInlineDecorators();
        $this->date_met->setRequired(true);
        $this->date_met->setAllowEmpty(false);
        $this->date_met->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
        $this->date_met->addErrorMessage("Meeting Date");
        //dates_wrapper

        $this->describe_program = $this->buildEditor('describe_program', array('label' => "Describe the proposed program"));
        $this->describe_program->setRequired(true);
        $this->describe_program->setAllowEmpty(false);
        $this->describe_program->addErrorMessage('Describe the proposed program is empty. If the item does not apply to this student, please explain why.');

        $this->describe_reason = $this->buildEditor('describe_reason', array('label' => "Describe Reason"));
        $this->describe_reason->setAttrib('onchange', "javascript:modified('', '', '', '', '', '');updateInlineValueTextArea(this.id, arguments[0]);colorMeById(this.id);");
        $this->describe_reason->setRequired(true);
        $this->describe_reason->setAllowEmpty(false);
        $this->describe_reason->addErrorMessage('Describe the reasons why the district proposes to place is empty. If the item does not apply to this student, please explain why.');

        $this->provide_description = $this->buildEditor('provide_description', array('label' => "Provide Description"));
        $this->provide_description->setRequired(true);
        $this->provide_description->setAllowEmpty(false);
        $this->provide_description->addErrorMessage('Provide a description of any options the district considered and the reasons why those options were rejected is empty. If the item does not apply to this student, please explain why.');

        $this->proposed_placement = $this->buildEditor('proposed_placement', array('label' => "Propose Placement"));
        $this->proposed_placement->setRequired(true);
        $this->proposed_placement->setAllowEmpty(false);
        $this->proposed_placement->addErrorMessage('The proposed placement is based upon the following evaluation procedures, tests, records or reports is empty. If the item does not apply to this student, please explain why.');

        $this->other_factors = $this->buildEditor('other_factors', array('label' => "Other Factors"));
        $this->other_factors->setRequired(true);
        $this->other_factors->setAllowEmpty(false);
        $this->other_factors->addErrorMessage('Other factors which are relevant to the school district\'s proposal, if any, are is empty. If the item does not apply to this student, please explain why.');

        $this->contact_name = new App_Form_Element_Text('contact_name', array('label' => "Name"));
        $this->contact_name->setRequired(true);
        $this->contact_name->setAllowEmpty(false);
        $this->contact_name->addErrorMessage('IDEA conatct name is empty. If the item does not apply to this student, please explain why.');

        $this->contact_num = new App_Form_Element_Text('contact_num', array('label' => "Phone Number"));
        $this->contact_num->setRequired(true);
        $this->contact_num->setAllowEmpty(false);
        $this->contact_num->addErrorMessage('IDEA contact number is empty. If the item does not apply to this student, please explain why.');

        return $this;
    }

    public function edit_p2_v2()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v3()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v4()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v5()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v6()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v7()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v8()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v9()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v1()
    {

        $this->initialize();

        // allow html characters in multioptions and other display
        $this->getView()->setEscape('stripslashes');

        // Setting the decorator for the element to a single, ViewScript, decorator,
        // specifying the viewScript as an option, and some extra options:
        $this->setDecorators(array(array('ViewScript', array(
            'viewScript' => 'form005/form005_edit_page2_version1.phtml'))));

        $yes = "I have received a copy of the Notice of this proposed evaluation and my
		parental rights, understand the content of the Notice and <B>give consent</b> 
		for the multidisciplinary evaluation specified in this Notice. I 
		understand this consent is voluntary and may be revoked at any time.";
        $no = "I have received a copy of the Notice of this proposed evaluation and my
		parental rights, understand the content of the Notice and <b>do not give 
		consent</b> for the multidisciplinary evaluation specified in this Notice. The reason for not giving consent to the evaluation is:";

        $multiOptions = array('1' => $yes, '0' => $no);
        $this->consent = new App_Form_Element_Radio('consent', array('label' => 'Consent:', 'multiOptions' => $multiOptions));
        $this->consent->removeDecorator('label');
        $this->consent->setRequired(true);
        $this->consent->addErrorMessage("Parent consent is empty. If the item does not apply to this student, please explain why.");
        $this->consent->addValidator(new My_Validate_BooleanNotEmpty('consent'));

        $this->no_consent_reason = $this->buildEditor('no_consent_reason', array('label' => "No Consent Reason"));
        $this->no_consent_reason->setRequired(false);
        $this->no_consent_reason->setAllowEmpty(false);
        $this->no_consent_reason->addValidator(new My_Validate_NotEmptyIf('consent', 0));
        $this->no_consent_reason->addErrorMessage("The reason for not giving consent to the evaluation is empty. If the item does not apply to this student, please explain why.");

        $this->signature_on_file = new App_Form_Element_Checkbox('signature_on_file', array('label' => 'Signature', 'description' => '(check here to indicate that signature is on file)'));
        $this->signature_on_file->removeDecorator('label');
        $this->signature_on_file->addErrorMessage("Parent signature must be on file.");
        $this->signature_on_file->setRequired(true);
        $this->signature_on_file->setCheckedValue('t');
        $this->signature_on_file->setUncheckedValue('');

        $this->consent_date = new App_Form_Element_DatePicker('consent_date', array('label' => 'Date of Parent Signature'));
        $this->consent_date->setRequired(true);
        $this->consent_date->setAllowEmpty(false);
        $this->consent_date->addValidator(new My_Validate_EmptyIf('signature_on_file', false));
        $this->consent_date->addValidator(new My_Validate_NotEmptyIf('signature_on_file', true));
        $this->consent_date->addErrorMessage("Date of Parent Signature");

        return $this;
    }
}
