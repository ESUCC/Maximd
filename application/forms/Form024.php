<?php

class Form_Form024 extends Form_AbstractForm {

	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_024 = new App_Form_Element_Hidden('id_form_024');
      	$this->id_form_024->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
	public function view_p1_v1() {

		$this->initialize();
		
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options:
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form024/form024_view_page1_version1.phtml' ) ) ) );		

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
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form024/form024_edit_page1_version1.phtml' ) ) ) );		

		$this->parent_names = new App_Form_Element_Text('parent_names', array('label'=>'Dear'));

		$this->date_notice = new App_Form_Element_Text('date_notice', array('label'=>'Date Notice'));

		$this->iep_date = new App_Form_Element_DatePicker('iep_date', array('label'=>'IEP Date'));
        $this->iep_date->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
        $this->iep_date->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
        $this->iep_date->setAttrib('wrapped', 'dates_wrapper');
        $this->iep_date->removeDecorator('label');
		
		$this->options_considered = new App_Form_Element_TextareaEditor('options_considered', array('label'=>'Options considered'));
//		$this->options_considered->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
//        $this->options_considered->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
//        $this->options_considered->setAttrib('wrapped', 'dates_wrapper');
//        $this->options_considered->removeDecorator('label');
		
		$this->return_contact = new App_Form_Element_Text('return_contact', array('label'=>'School Contact'));
		
		$this->return_address = new App_Form_Element_Text('return_address', array('label'=>'Address'));
		
		$this->return_city_st_zip = new App_Form_Element_Text('return_city_st_zip', array('label'=>'City, State, Zip '));
		
		$this->district_contact_name_title = new App_Form_Element_Text('district_contact_name_title', array('label'=>'District contact name title'));
        $this->district_contact_name_title->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->district_contact_name_title->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'sig_wrapper');");
        $this->district_contact_name_title->setAttrib('wrapped', 'sig_wrapper');
        $this->district_contact_name_title->removeDecorator('label');
		
		$this->district_contact = new App_Form_Element_Text('district_contact', array('label'=>'District contact'));
        $this->district_contact->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->district_contact->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'sig_wrapper');");
        $this->district_contact->setAttrib('wrapped', 'sig_wrapper');
        $this->district_contact->removeDecorator('label');
		
		$this->district_contact_phone = new App_Form_Element_Text('district_contact_phone', array('label'=>'District contact phone'));
        $this->district_contact_phone->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->district_contact_phone->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'sig_wrapper');");
        $this->district_contact_phone->setAttrib('wrapped', 'sig_wrapper');
        $this->district_contact_phone->removeDecorator('label');
		
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
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form024/form024_edit_page2_version1.phtml' ) ) ) );
				
// 		$this->give_consent = new Zend_Form_Element_Checkbox ( 'give_consent' );
// 		$this->give_consent->setCheckedValue(1);
// 		$this->give_consent->setAttrib ( 'class', 'textbox' );
// 		$this->give_consent->setAttrib ( 'onFocus', $this->JSmodifiedCode );
// 		$this->give_consent->setDecorators ( array ('ViewHelper' ) );
// 
// 		$this->consent_receiver = new Zend_Form_Element_Text ( 'consent_receiver' );
// 		$this->consent_receiver->setAttrib ( 'class', 'textbox' );
// 		$this->consent_receiver->setAttrib ( 'onFocus', $this->JSmodifiedCode );
// 		$this->consent_receiver->setDecorators ( array ('ViewHelper' ) );
		
		return $this;
	}
}