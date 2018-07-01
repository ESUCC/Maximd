<?php

class Form_Form004RelatedService extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function related_services_edit_version2() {return $this->related_services_edit_version1();}
    public function related_services_edit_version3() {return $this->related_services_edit_version1();}
    public function related_services_edit_version4() {return $this->related_services_edit_version1();}
    public function related_services_edit_version5() {return $this->related_services_edit_version1();}
    public function related_services_edit_version6() {return $this->related_services_edit_version1();}
    public function related_services_edit_version7() {return $this->related_services_edit_version1();}
    public function related_services_edit_version8() {return $this->related_services_edit_version1();}
    public function related_services_edit_version9() {return $this->related_services_edit_version1();}
    public function related_services_edit_version10() {return $this->related_services_edit_version1();}
    public function related_services_edit_version11() {
        $this->related_services_edit_version1();

        $this->setDecorators ( array (
//				                'PrepareElements',
                array ('ViewScript',
                    array (
                        'viewScript' => 'form004/related_services_edit_version11.phtml'
                    )
                )
            ) );

        $this->related_service_drop->removeDecorator('label');
        $this->removeElement('related_service_mpy');
        return $this;
    }

	
	public function related_services_edit_version1() {
		
		$this->setDecorators ( array (
//				                'PrepareElements', 
								array ('ViewScript', 
									array (
										'viewScript' => 'form004/related_services_edit_version1.phtml' 
									) 
								) 
							) );
		
		//Add elements here
		$this->related_service_drop = new App_Form_Element_Select('related_service_drop', array('label'=>"Related Service:"));
		$this->related_service_drop->setMultiOptions($this->getPrimaryDisability_version1());
			$this->related_service_drop->addErrorMessage('Please specify the related service (you have chosen \'Other\').|Related Service - You have not chosen &quot;Other&quot; for related service so the \'specify\' field should be blank.');
		
		// dynamic related services location drop - populated by form004 controller
		$this->related_service_location = new App_Form_Element_Select('related_service_location', array('label'=>"Special Education Related Services: Location:"));
		$this->related_service_location->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		
		$this->related_service_from_date = new App_Form_Element_DatePicker('related_service_from_date', array('label'=>'Related Service Date From:'));
		$this->related_service_from_date->addErrorMessage('You have not chosen the duration \'from\' date of the service.');
//        $this->related_service_from_date->setAttrib('style', "width:80px;");
		$this->related_service_from_date->setInlineDecorators();
			//
			// custom addition - elements wrappers - highlight a group of elements instead of just one
			// 
			// field wrapper - highlighting around more than one element
			// pass a tag unique to the encapsulated elements as the second parameter of colorMeById - to change wrapper yellow
			// set the wrapper_tag attribute of  
			$this->related_service_from_date->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
			$this->related_service_from_date->setAttrib('wrapped', 'dates_wrapper');
        $this->related_service_from_date->setAttrib('class', 'from_date ' . $this->related_service_from_date->getAttrib('class'));

        $this->related_service_to_date = new App_Form_Element_DatePicker('related_service_to_date', array('label'=>'Related Service Date To:'));
		$this->related_service_to_date->addErrorMessage('You have not chosen the duration \'to\' date of the service.');
//		$this->related_service_to_date->setAttrib('style', "width:80px;");
		$this->related_service_to_date->setInlineDecorators();
			$this->related_service_to_date->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
			$this->related_service_to_date->setAttrib('wrapped', 'dates_wrapper');
			$this->related_service_to_date->setAttrib('class', 'to_date ' . $this->related_service_to_date->getAttrib('class'));

		$this->related_service = new App_Form_Element_Textarea('related_service');
		
		//
		// unit quantity 
		//
		$this->related_service_tpd = new App_Form_Element_Text('related_service_tpd', array('label'=>"min/day / hours/day value"));
		$this->related_service_tpd->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->related_service_tpd->setAttrib('size', '6');
		$this->related_service_tpd->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');");
		$this->related_service_tpd->addErrorMessage('You have not chosen the min/day / hours/day value.');
			$this->related_service_tpd->setAttrib('wrapped', 'tpd_wrapper');
		
		//
		// unit quantity type
		//
		$arrLabel = array("min/day", "hrs/day", "min/week");
		$arrValue = array("m", "h", "mw");
		$this->related_service_tpd_unit = new App_Form_Element_Select('related_service_tpd_unit', array('label'=>"min/day / hours/day / min/week"));
		$this->related_service_tpd_unit->setMultiOptions(array_combine($arrValue, $arrLabel));
		$this->related_service_tpd_unit->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->related_service_tpd_unit->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');");
		$this->related_service_tpd_unit->addErrorMessage('You have not chosen the min/day / hours/day unit type.');
        $this->related_service_tpd_unit->setAttrib('wrapped', 'tpd_wrapper');
        $this->related_service_tpd_unit->setAttrib('class', 'related_service_tpd_unit');

		//
		// months
		//
		$this->related_service_mpy = new App_Form_Element_Text('related_service_mpy', array('label'=>"Months"));
		$this->related_service_mpy->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->related_service_mpy->setAttrib('size', '6');
		$this->related_service_mpy->addErrorMessage('You have not entered a number of months.');
		
		
		$this->related_service_days_value = new App_Form_Element_Text('related_service_days_value', array('label'=>"Days value"));
		$this->related_service_days_value->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->related_service_days_value->setAttrib('size', '6');
		$this->related_service_days_value->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'days_wrapper');");
			$this->related_service_days_value->setAttrib('wrapped', 'days_wrapper');
		$this->related_service_days_value->addErrorMessage('You have not entered a days value.');
			
		
		$arrLabel = array("days/week", "days/month", "days/quarter", "days/semester", "days/year", "weeks/month", "weeks/semester", "weeks/year");
		$arrValue = array("w", "m", "q", "s", "y", "wm", "ws", "wy");
		$this->related_service_days_unit = new App_Form_Element_Select('related_service_days_unit', array('label'=>"Days Unit"));
		$this->related_service_days_unit->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->related_service_days_unit->setMultiOptions(array_combine($arrValue, $arrLabel));
		$this->related_service_days_unit->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'days_wrapper');");
			$this->related_service_days_unit->setAttrib('wrapped', 'days_wrapper');
		$this->related_service_days_unit->addErrorMessage('You have not entered a days unit.');
        $this->related_service_days_unit->setAttrib('class', 'related_service_days_unit');

        $arrLabel = array("Yes", "No");
		$arrValue = array("t", "f");
		$this->related_service_calendar = new App_Form_Element_Select('related_service_calendar');
		$this->related_service_calendar->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->related_service_calendar->setMultiOptions(array_combine($arrValue, $arrLabel));
		
		$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check to remove row'));
		$this->remove_row->setDecorators ( My_Classes_Decorators::$labelDecorators );
		$this->remove_row->setLabel("Remove Row");
		$this->remove_row->ignore = true;
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
        $this->subform_label->setLabel("Related Services Row");
        
        
        $this->id_form_004_related_service = new App_Form_Element_Hidden('id_form_004_related_service');
				
		$this->dev_delay = new App_Form_Element_Hidden('dev_delay');
		$this->dev_delay->ignore = true;
		
		return $this;
				
	}

	function getPrimaryDisability_version1()
	{
        $arrLabel = array(
		    "Choose Related Service",
            "Assistive technology services/devices", 
            "Audiology",
            "Family training, counseling, home visits and other supports",
            "Health services",
            "Interpreting Services",
            "Medical services (for diagnostic or evaluation purposes)",
            "Nursing services",
            "Nutrition services",
            "Occupational Therapy Services",
            "Orientation and Mobility",
            "Physical Therapy",
            "Psychological services",
            "Respite care",
            "Services coordination",
            "Social work services",
            "Special Instruction (Resource)",
            "Speech/Language Therapy",
            "Teacher of the Hearing Impaired",
            "Teacher of the Visually Impaired",
        	"Transportation",
            "Vision Services",
        );
        
        $arrValue = array(
            "",
            "Assistive technology services/devices", 
            "Audiology",
            "Family training, counseling, home visits and other supports",
            "Health services",
            "Interpreting Services",
            "Medical services (for diagnostic or evaluation purposes)",
            "Nursing services",
            "Nutrition services",
            "Occupational Therapy Services",
            "Orientation and Mobility",
            "Physical Therapy",
            "Psychological services",
            "Respite care",
            "Services coordination",
            "Social work services",
            "Special Instruction (Resource)",
            "Speech-language therapy",
            "Teacher of the Hearing Impaired",
            "Teacher of the Visually Impaired",
        	"Transportation",
            "Vision Services",
        );
        
        return array_combine($arrValue, $arrLabel);
	}

    public function use_fte_report()
    {
        $multiOptions = array('Special Education'=>'Special Education', 'Special Education with regular Ed Peers'=>'Special Education with regular Ed Peers');
        $this->fte_special_education_time = new App_Form_Element_Radio('fte_special_education_time', array('Label'=>'Special Education Time', 'multiOptions'=>$multiOptions));
        $this->fte_special_education_time->addErrorMessage('Please select an option for Special Education.');
        $this->fte_special_education_time->setAllowEmpty(false);
        $this->fte_special_education_time->setRequired(true);
        $this->fte_special_education_time->removeDecorator('Label');

        $this->fte_qualifying_minutes = new App_Form_Element_Text('fte_qualifying_minutes', array('Label' => 'Qualifying FTE Minutes ='));
        $this->fte_qualifying_minutes->setAttrib('size', '4');
        $this->fte_qualifying_minutes->setAllowEmpty(false);
        $this->fte_qualifying_minutes->setRequired(true);
        $this->fte_qualifying_minutes->addValidator(new Zend_Validate_Digits());
        $this->fte_qualifying_minutes->addFilter('Digits');

    }

}

