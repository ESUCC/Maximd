<?php

class Form_Form022 extends Form_AbstractForm {
    
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_022 = new App_Form_Element_Hidden('id_form_022');
      	$this->id_form_022->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
	public function view_p1_v1() {
				
		$this->initialize();
		
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form022/form022_view_page1_version1.phtml' ) ) ) );
	
		
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
										'viewScript' => 'form022/form022_edit_page1_version1.phtml' ) ) ) );

        $this->date_mdt = new App_Form_Element_DatePicker('date_mdt', array('label' => 'Date of MDT'));
        $this->date_mdt->addErrorMessage("Date of MDT is empty.");
		$this->date_mdt->setRequired(false);
        $this->date_mdt->setAllowEmpty(true);


        $options = $this->getPrimaryDisability_version1();
        $this->disability_primary = new App_Form_Element_Select('disability_primary', array('label'=>'Primary Disability'));
		$this->disability_primary->setMultiOptions($options);
		$this->disability_primary->setRequired(false);
        $this->disability_primary->setAllowEmpty(true);
		
		$this->initial_verification_date = new App_Form_Element_DatePicker('initial_verification_date', array('label'=>'Initial Verification Date'));
//		$this->initial_verification_date->removeDecorator('label');
		$this->initial_verification_date->setRequired(false);
	        $this->initial_verification_date->setAllowEmpty(true);
		return $this;
	}

    /**
     * @return array
     */
    public function getPrimaryDisability_version1()
    {
        $options = array(
            'AU' => 'Autism',
            'BD' => 'Emotional Disturbance (BD)',
            'DB' => 'Deaf Blindness (DB)',
            'HI' => 'Hearing Impairment (HI)',
            'MH' => 'Mental Handicap',
            'MH:MI' => 'Intellectual Disability (Mental Handicap: Mild (MH:MI))',
            'MH:MO' => 'Intellectual Disability (Mental Handicap:Moderate (MH:MO))',
            'MH:S/P' => 'Intellectual Disability (Mental Handicap:Severe/Profound (MH:S/P))',
            'MULTI' => 'Multiple Impairments (MULTI)',
            'OI' => 'Orthopedic Impairment (OI)',
            'OHI' => 'Other Health Impairment (OHI)',
            'SLD' => 'Specific Learning Disability',
            'SLI' => 'Speech Language Impairment (SLI)',
            'TBI' => 'Traumatic Brain Injury (TBI)',
            'VI' => 'Visual Impairment (VI)',
            'DD' => 'Developmental Delay (DD)'
        );
        return $options;
    }
}