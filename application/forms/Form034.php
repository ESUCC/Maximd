<?php

class Form_Form034 extends Form_AbstractForm {
    
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditor');
	}
    
	protected function initialize() { 
		parent::initialize();
		
		$this->id_form_034 = new App_Form_Element_Hidden('id_form_034');
      	$this->id_form_034->ignore = true;
      	
      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
	
	
	public function view_p1_v1() {

		$this->initialize();  
		
		$this->setDecorators(array(array('ViewScript', array('viewScript' => 'form034_view_page1_version1.phtml'))));
	
		
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
		    'viewScript' => 'form034/form034_edit_page1_version1.phtml' ) ) ) );
		
		$this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label'=>'Date of Notice'));
		
		$this->date_notice->setDecorators(App_Form_DecoratorHelper::inlineElement(false));
		// Mike changed this to false in order to get the clock to work
		// $this->date_notice->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
		
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

        $this->describe_action = $this->buildEditor('describe_action', array('label' => "Describe the proposed action"));
        $this->describe_action->setRequired(true);
        $this->describe_action->setAllowEmpty(false);
        $this->describe_action->addErrorMessage('Describe the proposed action is empty. If the item does not apply to this student, please explain why.');
        
        $this->describe_reason = $this->buildEditor('describe_reason', array('label' => "Describe Reason"));
        $this->describe_reason->setAttrib('onchange', "javascript:modified('', '', '', '', '', '');updateInlineValueTextArea(this.id, arguments[0]);colorMeById(this.id);");
        $this->describe_reason->setRequired(true);
        $this->describe_reason->setAllowEmpty(false);
        $this->describe_reason->addErrorMessage('Describe the reasons why the district proposes to place is empty. If the item does not apply to this student, please explain why.');
        
        $this->options_other = $this->buildEditor('options_other', array('label' => "Options Other"));
        $this->options_other->setRequired(true);
        $this->options_other->setAllowEmpty(false);
        $this->options_other->addErrorMessage('Provide a description of any options the district considered and the reasons why those options were rejected is empty. If the item does not apply to this student, please explain why.');
        
        $this->justify_action = $this->buildEditor('justify_action', array('label' => "Justify Action"));
        $this->justify_action->setRequired(true);
        $this->justify_action->setAllowEmpty(false);
        $this->justify_action->addErrorMessage('The proposed placement is based upon the following evaluation procedures, tests, records or reports is empty. If the item does not apply to this student, please explain why.');
        
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
        
// 		
		return $this;
	}
}
