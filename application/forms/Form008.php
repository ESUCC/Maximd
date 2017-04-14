<?php

class Form_Form008 extends Form_AbstractForm {
    
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditor');
	}
    
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_008 = new App_Form_Element_Hidden('id_form_008');
      	$this->id_form_008->ignore = true;
      	
      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
	
	
	public function view_p1_v1() {

		$this->initialize();
		
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form008/form008_view_page1_version1.phtml' ) ) ) );
		
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
										'viewScript' => 'form008/form008_edit_page1_version1.phtml' ) ) ) );

		$this->date_met = new App_Form_Element_DatePicker('date_met', array('label' => 'Date met'));
		$this->date_met->addErrorMessage("Date of Meeting is empty.");
		
		// Mike Changed this to false in order to get the calendar to apper
       // $this->date_met->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
		$this->date_met->setDecorators(App_Form_DecoratorHelper::inlineElement(false));
        
		$this->date_met->removeDecorator('label');
        $this->date_met->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
        $this->date_met->setAttrib('wrapped', 'dates_wrapper');
	$this->date_met->setRequired(true);
	$this->date_met->setAllowEmpty(false);

        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
	$this->date_notice->setRequired(true);
	$this->date_notice->setAllowEmpty(false);
//        $this->date_notice->setDecorators($this->decoratorHelper->inlineElement());
//        $this->date_notice->removeDecorator('label');
//        $this->date_notice->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
//        $this->date_notice->setAttrib('wrapped', 'dates_wrapper');
        
        
// 		$this->describe_changes = new App_Form_Element_TextareaEditor('describe_changes', array('label' => 'Describe changes'));
		$this->describe_changes = $this->buildEditor('describe_changes', array('label' => 'Describe changes'));
		$this->describe_changes->addErrorMessage("Describe the proposed change is empty. If the item does not apply to this student, please explain why.");
		$this->describe_changes->setRequired(true);
		$this->describe_changes->setAllowEmpty(false);
		
// 		$this->reasons = new App_Form_Element_TextareaEditor('reasons', array('label' => 'Reasons'));
		$this->reasons = $this->buildEditor('reasons', array('label' => 'Reasons'));
		$this->reasons->addErrorMessage("Provide a description of any options the district considered and the reasons why those options were rejected is empty. If the item does not apply to this student, please explain why.");
		$this->reasons->setRequired(true);
		$this->reasons->setAllowEmpty(false);
		
// 		$this->rejected = new App_Form_Element_TextareaEditor('rejected', array('label' => 'Rejected'));
		$this->rejected = $this->buildEditor('rejected', array('label' => 'Rejected'));
		$this->rejected->addErrorMessage("Provide a description of any options the district considered and the reasons why those options were rejected is empty. If the item does not apply to this student, please explain why.");
		$this->rejected->setRequired(true);
		$this->rejected->setAllowEmpty(false);
		
// 		$this->change_based = new App_Form_Element_TextareaEditor('change_based', array('label' => 'Change based'));
		$this->change_based = $this->buildEditor('change_based', array('label' => 'Change based'));
		$this->change_based->addErrorMessage("The proposed change of placement is based upon the following procedures, tests, records, or reports is empty. If the item does not apply to this student, please explain why.");
		$this->change_based->setRequired(true);
		$this->change_based->setAllowEmpty(false);
		
// 		$this->other_factors = new App_Form_Element_TextareaEditor('other_factors', array('label' => 'Other Factors'));
		$this->other_factors = $this->buildEditor('other_factors', array('label' => 'Other Factors'));
		$this->other_factors->addErrorMessage("Other factors which are relevant to the school district's proposal, if any, are is empty. If the item does not apply to this student, please explain why.");
		$this->other_factors->setRequired(true);
		$this->other_factors->setAllowEmpty(false);
		
		$this->contact_name = new App_Form_Element_Text('contact_name', array('label' => 'Name'));
		$this->contact_name->addErrorMessage("IDEA contact name is empty. If the item does not apply to this student, please explain why.");
		$this->contact_name->setRequired(true);
		$this->contact_name->setAllowEmpty(false);
		
		$this->contact_num = new App_Form_Element_Text('contact_num', array('label' => 'Phone Number'));
		$this->contact_num->addErrorMessage("IDEA contact number is empty. If the item does not apply to this student, please explain why.");
		$this->contact_num->setRequired(true);
		$this->contact_num->setAllowEmpty(false);
		
		return $this;
	}
}