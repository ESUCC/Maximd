<?php

class Form_Form009 extends Form_AbstractForm {
    
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditor');
	}
    
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_009 = new App_Form_Element_Hidden('id_form_009');
      	$this->id_form_009->ignore = true;
      	
      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
	
	
	public function view_p1_v1() {

		$this->initialize();
		
		$this->setDecorators(array(array('ViewScript', array('viewScript' => 'form009_view_page1_version1.phtml'))));
	
		
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
										'viewScript' => 'form009/form009_edit_page1_version1.phtml' ) ) ) );

        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label'=>'Date of Notice'));
        
        // Mike set this to false so that the calendar js would work
        //$this->date_notice->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
        $this->date_notice->setDecorators(App_Form_DecoratorHelper::inlineElement(false));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
        $this->date_notice->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
        $this->date_notice->setAllowEmpty(false);
        $this->date_notice->setRequired(true);
	
        /*
	$this->date_met = new App_Form_Element_DatePicker('date_met', array('label'=>'Date of Meeting'));
	$this->date_met->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
	$this->date_met->removeDecorator('label');
	$this->date_met->addErrorMessage("Date of Meeting is empty.");
        $this->date_met->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
        $this->date_met->setAttrib('wrapped', 'dates_wrapper');
	$this->date_met->setRequired(true);
        $this->date_met->setAllowEmpty(false);
        */

		
// 		$this->describe_discontinue = new App_Form_Element_TextareaEditor('describe_discontinue', array('label'=>'Describe Discontinue'));
		$this->describe_discontinue = $this->buildEditor('describe_discontinue', array('label'=>'Describe Discontinue'));
		$this->describe_discontinue->addErrorMessage("Describe reasons why the district proposes to discontinue services is empty. If the item does not apply to this student, please explain why.");
		$this->describe_discontinue->setRequired(true);
		$this->describe_discontinue->setAllowEmpty(false);
		
// 		$this->rejected_options = new App_Form_Element_TextareaEditor('rejected_options', array('label'=>'Rejected options'));
		$this->rejected_options = $this->buildEditor('rejected_options', array('label'=>'Rejected options'));
		$this->rejected_options->addErrorMessage("Provide a description of any options the district considered and the reasons why those options were rejected is empty. If the item does not apply to this student, please explain why.");
		$this->rejected_options->setRequired(true);
		$this->rejected_options->setAllowEmpty(false);
		
// 		$this->basis = new App_Form_Element_TextareaEditor('basis', array('label'=>'basis'));
		$this->basis = $this->buildEditor('basis', array('label'=>'basis'));
		$this->basis->addErrorMessage("The proposed discontinuation of special education services is based upon the following evaluation procedures, tests, records, or reports is empty. If the item does not apply to this student, please explain why.");
		$this->basis->setRequired(true);
		$this->basis->setAllowEmpty(false);
		
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
