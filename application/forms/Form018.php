<?php

class Form_Form018 extends Form_AbstractForm {
    
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_018 = new App_Form_Element_Hidden('id_form_018');
      	$this->id_form_018->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
	public function view_p1_v1() {

		$this->initialize();
		
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form018/form018_view_page1_version1.phtml' ) ) ) );

		
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
										'viewScript' => 'form018/form018_edit_page1_version1.phtml' ) ) ) );
		
        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date of Summary'));
        $this->date_notice->addErrorMessage("Date of Summary is empty.");
	$this->date_notice->setRequired(true);
        $this->date_notice->setAllowEmpty(false);
		        
        $this->date_graduation = new App_Form_Element_DatePicker('date_graduation', array('label' => 'Date of Graduation/Exit'));
        $this->date_graduation->addErrorMessage("Date of Graduation/Exit is empty.");
        $this->date_graduation->setRequired(true);
        $this->date_graduation->setAllowEmpty(false);
        
	$this->summary_of_performance = new App_Form_Element_TextareaEditor('summary_of_performance', array('label'=>'summary_of_performance'));
	$this->summary_of_performance->setRequired(true);
        $this->summary_of_performance->setAllowEmpty(false);
	$this->summary_of_performance->addErrorMessage("Summary of Performance must be entered.");
	
	$this->school_district_contact = new App_Form_Element_Text('school_district_contact', array('label'=>'School District Contact'));
	$this->school_district_contact->setRequired(true);
        $this->school_district_contact->setAllowEmpty(false);
	$this->school_district_contact->addErrorMessage("School District Contact must be entered.");
	
	$this->school_district_contact_phone = new App_Form_Element_Text('school_district_contact_phone', array('label'=>'Phone'));
	$this->school_district_contact_phone->setRequired(true);
        $this->school_district_contact_phone->setAllowEmpty(false);
	$this->school_district_contact_phone->addErrorMessage("You must enter a valid School District Contact Phone, example: 111-222-3333.");
	
	$this->date_summary_performance = new App_Form_Element_DatePicker('date_summary_performance', array('label'=>'Summary of Performance Date'));
	$this->date_summary_performance->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
        $this->date_summary_performance->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'sop_wrapper');");
        $this->date_summary_performance->setAttrib('wrapped', 'sop_wrapper');
	$this->date_summary_performance->setRequired(true);
        $this->date_summary_performance->setAllowEmpty(false);
	$this->date_summary_performance->addErrorMessage("Summary of Performance Date");
	
	$this->name_summary_performance = new App_Form_Element_Text('name_summary_performance', array('label'=>'Summary of Performance approved by'));
	$this->name_summary_performance->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->name_summary_performance->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'sop_wrapper');");
        $this->name_summary_performance->setAttrib('wrapped', 'sop_wrapper');
	$this->name_summary_performance->setRequired(true);
        $this->name_summary_performance->setAllowEmpty(false);
	$this->name_summary_performance->addErrorMessage("Name Summary of Performance must be entered.");
	
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
        $this->setDecorators ( array (array ('ViewScript', array (
                                        'viewScript' => 'form018/form018_edit_page2_version1.phtml' ) ) ) );
        
        
        return $this;
    }
    
}