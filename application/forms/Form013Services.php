<?php

class Form_Form013Services extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function ifsp_services_edit_version9() {
		return $this->ifsp_services_edit_version1();
	}
	public function ifsp_services_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form013/ifsp_services_edit_version1.phtml' ) ) ) );
		//
        // these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        //
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;
		
        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Service Row");

        $this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check to remove'));
        $this->remove_row->setDecorators ( My_Classes_Decorators::$labelDecorators);
        $this->remove_row->ignore = true;
        
        $this->id_ifsp_services = new App_Form_Element_Hidden('id_ifsp_services', array('label'=>''));
        
        // visible fields
        
        $multiOptions = App_Form_ValueListHelper::ifspService();
        $this->service_service = new App_Form_Element_Select('service_service', array('label'=>'Service:', 'multiOptions' => $multiOptions));
        $this->service_service->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_service->boldLabelPrint();
		$this->service_service->addErrorMessage("Service must be selected.");
        $this->service_service->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);subformShowHideField(this.id, 'service_other', this.value, 'Other');");
        
        $this->service_other = new App_Form_Element_Text('service_other', array('label'=>'Service Other:'));
        $this->service_other->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_other->boldLabelPrint();
        $this->service_other->setRequired(false);
        $this->service_other->setAllowEmpty(false);
        $this->service_other->addValidator(new My_Validate_NotEmptyIf('service_service', 'Other'));
        //$this->service_other->addValidator(new My_Validate_EmptyIf('service_service', 'Other'));
		$this->service_other->addErrorMessage("Service Other must be entered when Other is selected.");
        
        $multiOptions = App_Form_ValueListHelper::ifspWhere();
        $this->service_where = new App_Form_Element_Select('service_where', array('label'=>'Setting:', 'multiOptions' => $multiOptions));
        $this->service_where->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_where->boldLabelPrint();
		$this->service_where->addErrorMessage("Where must be entered.");
        $this->service_where->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);subformShowHideField(this.id, 'service_where_other', this.value, 'Other');");
        $this->service_where->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_where->setAttrib('style', 'display:inline;');
        
        $this->service_where_other = new App_Form_Element_Text('service_where_other', array('label'=>'Setting Other:'));
        $this->service_where_other->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_where_other->boldLabelPrint();
        $this->service_where_other->setRequired(false);
        $this->service_where_other->setAllowEmpty(false);
        $this->service_where_other->addValidator(new My_Validate_NotEmptyIf('service_where', 'Other'));
        // $this->service_where_other->addValidator(new My_Validate_EmptyIf('service_where', 'Other'));
		$this->service_where_other->addErrorMessage("Where Other must be entered when Where is Other Settings.");
        
        
        
        $this->service_dwm = new App_Form_Element_Text('service_dwm', array('label'=>'How often?'));
        $this->service_dwm->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_dwm->removeDecorator('label');
		$this->service_dwm->setRequired(true);
		$this->service_dwm->addValidator(new Zend_Validate_Int());
        $this->service_dwm->addErrorMessage("How Often must be entered and must be an integer.");
		$this->service_dwm->setWidth('20px;');
        
        $multiOptions = App_Form_ValueListHelper::ifspHowOften();
        $this->service_dwm_unit = new App_Form_Element_Select('service_dwm_unit', array('label'=>'How often unit', 'multiOptions' => $multiOptions));
        $this->service_dwm_unit->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_dwm_unit->removeDecorator('label');
	
        $this->service_tpd = new App_Form_Element_Text('service_tpd', array('label'=>'How much?'));
        $this->service_tpd->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_tpd->removeDecorator('label');
		$this->service_tpd->setRequired(true);
		$this->service_tpd->addValidator(new Zend_Validate_Int());
		$this->service_tpd->addErrorMessage("How Much must be entered and must be an integer.");
        $this->service_tpd->setWidth('20px;');
        
        $multiOptions = App_Form_ValueListHelper::ifspHowMuch();
        $this->service_tpd_unit = new App_Form_Element_Select('service_tpd_unit', array('label'=>'How much unit', 'multiOptions' => $multiOptions));
        $this->service_tpd_unit->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_tpd_unit->removeDecorator('label');

        $multiOptions = App_Form_ValueListHelper::ifspGroupIndividual();
        $this->service_group_ind = new App_Form_Element_Select('service_group_ind', array('label'=>'Group/Individual', 'multiOptions' => $multiOptions));
        $this->service_group_ind->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_group_ind->removeDecorator('label');
		$this->service_group_ind->setRequired(true);
		$this->service_group_ind->addErrorMessage("Group / Individual must be selected.");
        
        $multiOptions = array('1'=>'Yes', '0'=>'No');
        $this->service_natural = new App_Form_Element_Radio('service_natural', array('label'=>'Natural Environment', 'multiOptions'=>$multiOptions));
        $this->service_natural->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_natural->removeDecorator('label');
		$this->service_natural->addValidator(new My_Validate_BooleanNotEmpty('service_natural'));
		$this->service_natural->setRequired(true);
		$this->service_natural->addErrorMessage("Natural Environment must be selected.");
		$this->service_natural->setAttrib('onchange', $this->JSmodifiedCode . "subformShowHideJustification(this.id, 'service_natural', 'service_who_pays');");


        $this->service_start = new App_Form_Element_DatePicker('service_start', array('label'=>'Srevice Start:'));
//        $this->service_start->setAttrib('style', "width:80px;");
        $this->service_start->setInlineDecorators();
        $this->service_start->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
        $this->service_start->setAttrib('wrapped', 'dates_wrapper');
        $this->service_start->setRequired(true);
        $this->service_start->setAllowEmpty(false);
		$this->service_start->addErrorMessage("Start Date");
            
        $this->service_end = new App_Form_Element_DatePicker('service_end', array('label'=>'Service End:'));
//        $this->service_end->setAttrib('style', "width:80px;");
        $this->service_end->setInlineDecorators();
        $this->service_end->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
        $this->service_end->setAttrib('wrapped', 'dates_wrapper');
        $this->service_end->setRequired(true);
        $this->service_end->setAllowEmpty(false);
		$this->service_end->addErrorMessage("End Date");
            
        $multiOptions = App_Form_ValueListHelper::ifspWhoPays();
        $this->service_who_pays = new App_Form_Element_Select('service_who_pays', array('label'=>'Who Pays?', 'multiOptions' => $multiOptions));
        $this->service_who_pays->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_who_pays->removeDecorator('label');
		$this->service_who_pays->setRequired(true);
        $this->service_who_pays->setAllowEmpty(false);
		$this->service_who_pays->addErrorMessage("Who Pays must be entered.");
        $this->service_who_pays->setAttrib('onchange', $this->JSmodifiedCode . 
        		"colorMeById(this.id);
        			subformShowHideField(this.id, 'service_who_pays_other', this.value, 'Other');
        			subformShowHideJustification(this.id, 'service_natural', 'service_who_pays');"
        );
        
        $multiOptions = App_Form_ValueListHelper::ifspWhoResponsible();
        $this->service_responsible = new App_Form_Element_Select('service_responsible', array('label'=>'Who\'s Responsible?', 'multiOptions' => $multiOptions));
        $this->service_responsible->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_responsible->removeDecorator('label');
        $this->service_responsible->setRequired(true);
        $this->service_responsible->setAllowEmpty(false);
		$this->service_responsible->addErrorMessage("Who's Responsible must be entered.");
	    $this->service_responsible->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);subformShowHideField(this.id, 'service_responsible_other', this.value, 'Other');");
		
        $this->service_who_pays_other = new App_Form_Element_Text('service_who_pays_other', array('label'=>'Service Who Pays Other'));
        $this->service_who_pays_other->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_who_pays_other->boldLabelPrint();
        $this->service_who_pays_other->setRequired(false);
        $this->service_who_pays_other->setAllowEmpty(false);
        $this->service_who_pays_other->addValidator(new My_Validate_NotEmptyIf('service_who_pays', 'Other'));
		$this->service_who_pays_other->addErrorMessage("Other must be entered when Who Pays is Other.");
        
		
        $this->service_responsible_other = new App_Form_Element_Text('service_responsible_other', array('label'=>'Service Who\'s Responsible Other'));
        $this->service_responsible_other->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->service_responsible_other->boldLabelPrint();
        $this->service_responsible_other->setRequired(false);
        $this->service_responsible_other->setAllowEmpty(false);
        $this->service_responsible_other->addValidator(new My_Validate_NotEmptyIf('service_responsible', 'Other'));
		$this->service_responsible_other->addErrorMessage("Other must be entered when Who's Responsible is Other.");

        
        $this->service_justification = new App_Form_Element_TestEditorTab('service_justification', array('label'=>'Goal Justification'));
        $this->service_justification->removeDecorator('label');
        $this->service_justification->setRequired(false);
        $this->service_justification->setAllowEmpty(false);
		//Require justification if not in a natural environment
		$this->service_justification->addValidator(new My_Validate_Form013Justification());
//		$this->service_justification->addValidator(new My_Validate_NotEmptyIf('service_natural', '0'));
		$this->service_justification->addErrorMessage("Justification must be entered when natural environment is No and Who Pays is School district.");
	    $this->service_justification->appendAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);subformShowHideJustification(this.id, 'service_natural', 'service_who_pays');");

        
        return $this;			
	}
	
	public function ifsp_services_edit_version10() {
	    $this->ifsp_services_edit_version1();
	    $this->service_responsible->setMultiOptions(App_Form_ValueListHelper::ifspWhoResponsible(10));
        return $this;
	}
	
	
}

