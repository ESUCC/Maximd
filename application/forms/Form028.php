<?php

class Form_Form028 extends Form_AbstractForm {

    var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function init()
    {
        $this->setEditorType('App_Form_Element_TestEditor');
    }

    protected function initialize() {
        parent::initialize();

        $this->id_form_028 = new App_Form_Element_Hidden('id_form_028');
        $this->id_form_028->ignore = true;

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
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form028/form028_edit_page1_version1.phtml' ) ) ) );

		$this->mdt_re_evaluation = new App_Form_Element_DatePicker('mdt_re_evaluation', array('label'=>'Date'));
		$this->mdt_re_evaluation->removeDecorator('colorme');
		$this->mdt_re_evaluation->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'mdt_re_evaluation' . '-colorme') );
		$this->mdt_re_evaluation->removeDecorator('Label');
		
		$this->today_date = new App_Form_Element_DatePicker('today_date', array('label'=>'Today\'s Date'));
		$this->today_date->removeDecorator('colorme');
		$this->today_date->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'today_date' . '-colorme') );
		$this->today_date->removeDecorator('Label');

		$this->parent_concerns = $this->buildEditor('parent_concerns', array('Label'=>'Parent Concerns:'));
		$this->parent_concerns->addErrorMessage('You must enter a value for parent concerns.');
		
		$this->present_levels_of_education_performance = $this->buildEditor('present_levels_of_education_performance', array('Label'=>'Present Levels of Education Performance:'));
		$this->present_levels_of_education_performance->addErrorMessage('You must enter a value for present levels of education performance.');
		
		$this->measureable_annual_goals = $this->buildEditor('measureable_annual_goals', array('Label'=>'Measureable Annual Goals:'));
		$this->measureable_annual_goals->addErrorMessage('You must enter a value for measureable annual goals.');
		
		$this->description_of_service_to_be_provided = $this->buildEditor('description_of_service_to_be_provided', array('Label'=>'Description of Service(s) to be Provided by:'));
		$this->description_of_service_to_be_provided->addErrorMessage('You must enter a value for description of services to be provided by.');

		$this->date_of_initiation_of_services = new App_Form_Element_DatePicker('date_of_initiation_of_services', array('label'=>'Date'));
		$this->date_of_initiation_of_services->removeDecorator('colorme');
		$this->date_of_initiation_of_services->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'date_of_initiation_of_services' . '-colorme') );
		$this->date_of_initiation_of_services->removeDecorator('Label');
		
		$this->duration_of_services_from = new App_Form_Element_DatePicker('duration_of_services_from', array('label'=>'Date'));
		$this->duration_of_services_from->removeDecorator('colorme');
		$this->duration_of_services_from->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'duration_of_services_from' . '-colorme') );
		$this->duration_of_services_from->removeDecorator('Label');
		
		$this->duration_of_services_to = new App_Form_Element_DatePicker('duration_of_services_to', array('label'=>'Date'));
		$this->duration_of_services_to->removeDecorator('colorme');
		$this->duration_of_services_to->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'duration_of_services_to' . '-colorme') );
		$this->duration_of_services_to->removeDecorator('Label');

		$this->fape_contact = new App_Form_Element_Text('fape_contact');
		$this->fape_contact->setAttrib('style', 'width:150px;');
		$this->fape_contact->removeDecorator('Label');
		$this->fape_contact->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'fape_contact' . '-colorme') );

		$this->fape_contact_location = new App_Form_Element_Text('fape_contact_location');
		$this->fape_contact_location->setAttrib('style', 'width:150px;');
		$this->fape_contact_location->removeDecorator('Label');
		$this->fape_contact_location->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'fape_contact_location' . '-colorme') );
		
		$this->public_school_district_providing_services = new App_Form_Element_Text('public_school_district_providing_services');
		$this->public_school_district_providing_services->setAttrib('style', 'width:150px;');
		$this->public_school_district_providing_services->removeDecorator('Label');
		$this->public_school_district_providing_services->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'public_school_district_providing_services' . '-colorme') );
		
		return $this;
	}
}