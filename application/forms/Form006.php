<?php

class Form_Form006 extends Form_AbstractForm {

    var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditor');
	}
    
    protected function initialize() {
		parent::initialize();
		
		$this->id_form_006 = new App_Form_Element_Hidden('id_form_006');
      	$this->id_form_006->ignore = true;
      	
      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
		
	public function view_page1_version1() {

		$this->initialize();
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form006/form006_view_page1_version1.phtml' ) ) ) );
		
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
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form006/form006_edit_page1_version1.phtml' ) ) ) );

	
        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date of Notice'));
	$this->date_notice->setRequired(true);
	$this->date_notice->setAllowEmpty(false);
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
		
//     $this->describe_action = new App_Form_Element_TextareaEditor('describe_action', array('label'=>'Proposed Placement'));
	$this->describe_action = $this->buildEditor('describe_action', array('label'=>'Proposed Placement'));
	$this->describe_action->setRequired(true);
	$this->describe_action->setAllowEmpty(false);
        $this->describe_action->addErrorMessage("Describe action refused is empty. If the item does not apply to this student, please explain why.");
	
		$multiOptions = array('1'=>'proposes', '0'=>'refuses');
		$this->proposition_reason = new App_Form_Element_Radio('proposition_reason', array('label'=>'Proposition Reason', 'multiOptions'=>$multiOptions));
        $this->proposition_reason->setDecorators($this->decoratorHelper->inlineElement());
        $this->proposition_reason->removeDecorator('label');
        $this->proposition_reason->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'reason_wrapper');");
        $this->proposition_reason->setAttrib('wrapped', 'reason_wrapper');
	$this->proposition_reason->setRequired(true);
	$this->proposition_reason->setAllowEmpty(false);
        $this->proposition_reason->addErrorMessage("The reason the school district (proposes/refuses) to take this action is: radio button has not been chosen.");
		
//         $this->describe_reason = new App_Form_Element_TextareaEditor('describe_reason', array('label'=>'Describe Reason'));
    $this->describe_reason = $this->buildEditor('describe_reason', array('label'=>'Describe Reason'));
        $this->describe_reason->removeDecorator('label');
        $this->describe_reason->setRequired(true);
	$this->describe_reason->setAllowEmpty(false);
        $this->describe_reason->addErrorMessage("The reason the school district (proposes/refuses) to take this action is: text box is empty.");
	
// 		$this->options_considered = new App_Form_Element_TextareaEditor('options_considered', array('label'=>'Options Considered'));
	$this->options_considered = $this->buildEditor('options_considered', array('label'=>'Options Considered'));
		$this->options_considered->setRequired(true);
		$this->options_considered->setAllowEmpty(false);
		$this->options_considered->addErrorMessage("The school district considered the following option(s) is empty. If the item does not apply to this student, please explain why.");
		
		$multiOptions = array('1'=>'accepted', '0'=>'rejected');
		$this->proposition_option_accept = new App_Form_Element_Radio('proposition_option_accept', array('label'=>'Proposition Options Accept/Reject', 'multiOptions'=>$multiOptions));
        $this->proposition_option_accept->setDecorators($this->decoratorHelper->inlineElement());
        $this->proposition_option_accept->removeDecorator('label');
        $this->proposition_option_accept->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'proposition_wrapper');");
        $this->proposition_option_accept->setAttrib('wrapped', 'proposition_wrapper');
	$this->proposition_option_accept->setRequired(true);
	$this->proposition_option_accept->setAllowEmpty(false);
        $this->proposition_option_accept->addErrorMessage("The options were (accepted/rejected) because: radio button has not been chosen.");
		
        
//     $this->options_rejected = new App_Form_Element_TextareaEditor('options_rejected', array('label'=>'Options Rejected'));
    $this->options_rejected = $this->buildEditor('options_rejected', array('label'=>'Options Rejected'));
	$this->options_rejected->setRequired(true);
	$this->options_rejected->setAllowEmpty(false);
        $this->options_rejected->addErrorMessage("The options were (accepted/rejected) because: text box is empty.");
		
//         $this->decision_based = new App_Form_Element_TextareaEditor('decision_based', array('label'=>'Decision Based'));
	$this->decision_based = $this->buildEditor('decision_based', array('label'=>'Decision Based'));
	$this->decision_based->setRequired(true);
	$this->decision_based->setAllowEmpty(false);
        $this->decision_based->addErrorMessage("This decision is based on the evaluation procedures, tests, and records or reports described below is empty. If the item does not apply to this student, please explain why.");
		
// 	$this->other_factors = new App_Form_Element_TextareaEditor('other_factors', array('label'=>'Other Factors'));
	$this->other_factors = $this->buildEditor('other_factors', array('label'=>'Other Factors'));
	$this->other_factors->setRequired(true);
	$this->other_factors->setAllowEmpty(false);
        $this->other_factors->addErrorMessage("Any other factors relevant to the district's decision are is empty. If the item does not apply to this student, please explain why.");
		
	$this->contact_name = new App_Form_Element_Text('contact_name', array('label'=>'Name'));
	$this->contact_name->setRequired(true);
	$this->contact_name->setAllowEmpty(false);
        $this->contact_name->addErrorMessage("IDEA contact name is empty. If the item does not apply to this student, please explain why.");
		
	$this->contact_num = new App_Form_Element_Text('contact_num', array('label'=>'Phone Number'));
	$this->contact_num->setRequired(true);
	$this->contact_num->setAllowEmpty(false);
        $this->contact_num->addErrorMessage("IDEA contact number is empty. If the item does not apply to this student, please explain why.");
		
		return $this;
	}
	
//    public function edit_p2_v2() { return $this->edit_p2_v1();}
//    public function edit_p2_v3() { return $this->edit_p2_v1();}
//    public function edit_p2_v4() { return $this->edit_p2_v1();}
//    public function edit_p2_v5() { return $this->edit_p2_v1();}
//    public function edit_p2_v6() { return $this->edit_p2_v1();}
//    public function edit_p2_v7() { return $this->edit_p2_v1();}
//    public function edit_p2_v8() { return $this->edit_p2_v1();}
//    public function edit_p2_v9() { return $this->edit_p2_v1();}
//	public function edit_p2_v1() {
//		
//		$this->initialize();
//		
//		// Setting the decorator for the element to a single, ViewScript, decorator,
//		// specifying the viewScript as an option, and some extra options: 
//		$this->setDecorators ( array (array ('ViewScript', array (
//										'viewScript' => 'form006/form006_edit_page2_version1.phtml' ) ) ) );
//		
//		
//		return $this;
//	}
}
