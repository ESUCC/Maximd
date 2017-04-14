<?php

class Form_Form026 extends Form_AbstractForm {

    var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function init()
    {
        $this->setEditorType('App_Form_Element_TestEditor');
    }

    protected function initialize() {
        parent::initialize();

        $this->id_form_026 = new App_Form_Element_Hidden('id_form_026');
        $this->id_form_026->ignore = true;

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
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form026/form026_edit_page1_version1.phtml' ) ) ) );

		$this->revoke_consent = new App_Form_Element_Checkbox('revoke_consent', array('label'=>'I REVOKE my consent for my child to continue to receive special education and related services.'));
		$this->revoke_consent->removeDecorator('Label');
		
		$this->parent_sig_on_file = new App_Form_Element_Checkbox('parent_sig_on_file', array('label'=>'Signature on File:'));
		$this->parent_sig_on_file->removeDecorator('colorme');
		$this->parent_sig_on_file->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'parent_sig_on_file' . '-colorme') );
		$this->parent_sig_on_file->removeDecorator('Label');
		
		$this->parent_sig_on_file_date = new App_Form_Element_DatePicker('parent_sig_on_file_date', array('label'=>'Date'));
		$this->parent_sig_on_file_date->removeDecorator('colorme');
		$this->parent_sig_on_file_date->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'parent_sig_on_file_date' . '-colorme') );
		$this->parent_sig_on_file_date->removeDecorator('Label');
		
		$this->signature_of_school_district_official = new App_Form_Element_Checkbox('signature_of_school_district_official', array('label'=>'Signature of School District Official: '));
		$this->signature_of_school_district_official->removeDecorator('colorme');
		$this->signature_of_school_district_official->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'signature_of_school_district_official' . '-colorme') );
		$this->signature_of_school_district_official->removeDecorator('Label');

		$this->signature_of_school_district_official_date = new App_Form_Element_DatePicker('signature_of_school_district_official_date', array('label'=>'Date'));
		$this->signature_of_school_district_official_date->removeDecorator('colorme');
		$this->signature_of_school_district_official_date->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'signature_of_school_district_official_date' . '-colorme') );
		$this->signature_of_school_district_official_date->removeDecorator('Label');
		
		$this->date_prior_written_notice = new App_Form_Element_DatePicker('date_prior_written_notice', array('label'=>'Date'));
		$this->date_prior_written_notice->removeDecorator('colorme');
		$this->date_prior_written_notice->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'date_prior_written_notice' . '-colorme') );
		$this->date_prior_written_notice->removeDecorator('Label');
		
		$this->date_special_education = new App_Form_Element_DatePicker('date_special_education', array('label'=>'Date'));
		$this->date_special_education->removeDecorator('colorme');
		$this->date_special_education->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'date_special_education' . '-colorme') );
		$this->date_special_education->removeDecorator('Label');
		
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
        $this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form026/form026_edit_page2_version1.phtml' ) ) ) );

        $this->date_of_notice_discontinuation = new App_Form_Element_DatePicker('date_of_notice_discontinuation', array('label'=>'Date of Notice/Discontinuation:'));
        $this->date_of_notice_discontinuation->removeDecorator('colorme');
        $this->date_of_notice_discontinuation->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'date_of_notice_discontinuation' . '-colorme') );
        $this->date_of_notice_discontinuation->removeDecorator('Label');
        
        $this->description_of_the_action = $this->buildEditor('description_of_the_action', array('Label'=>'A description of the action proposed by the district:'));
        $this->description_of_the_action->addErrorMessage('A description of the action proposed by the district is empty. If the item does not apply to this student, please explain why.');
        
        $this->explanation_of_why = $this->buildEditor('explanation_of_why', array('Label'=>'Explanation of why the action is proposed:'));
        $this->explanation_of_why->addErrorMessage('Explanation of why the action is proposed is empty. If the item does not apply to this student, please explain why.');
        
        $this->options_considered = $this->buildEditor('options_considered', array('Label'=>'Options considered and why the options were rejected:'));
        $this->options_considered->addErrorMessage('Options considered and why the options were rejected is empty. If the item does not apply to this student, please explain why.');
        
        $this->description_of_evaluation = $this->buildEditor('description_of_evaluation', array('Label'=>'Description of the evaluation procedure, assessment, record or report used as a basis for the proposed or refused action:'));
        $this->description_of_evaluation->addErrorMessage('Description of the evaluation procedure is empty. If the item does not apply to this student, please explain why.');
        
        $this->other_factors = $this->buildEditor('other_factors', array('Label'=>'Other factors relevant to the proposal:'));
        $this->other_factors->addErrorMessage('Other factors relevant to the proposal is empty. If the item does not apply to this student, please explain why.');
        
        return $this;

    }
}