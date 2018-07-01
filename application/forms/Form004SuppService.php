<?php

class Form_Form004SuppService extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditorTab');
	}
	
    public function supp_services_edit_version2() {return $this->supp_services_edit_version1();}
    public function supp_services_edit_version3() {return $this->supp_services_edit_version1();}
    public function supp_services_edit_version4() {return $this->supp_services_edit_version1();}
    public function supp_services_edit_version5() {return $this->supp_services_edit_version1();}
    public function supp_services_edit_version6() {return $this->supp_services_edit_version1();}
    public function supp_services_edit_version7() {return $this->supp_services_edit_version1();}
    public function supp_services_edit_version8() {return $this->supp_services_edit_version1();}
    public function supp_services_edit_version9() {return $this->supp_services_edit_version1();}
    public function supp_services_edit_version10() {return $this->supp_services_edit_version1();}
    public function supp_services_edit_version11() {
        $this->supp_services_edit_version1();

        $this->setDecorators ( array (
//				                'PrepareElements',
                array ('ViewScript',
                    array (
                        'viewScript' => 'form004/supp_services_edit_version11.phtml'
                    )
                )
            ) );

        $this->removeElement('supp_service_mpy');
//        $this->removeElement('supp_service_from_date');
//        $this->removeElement('supp_service_to_date');
        $this->removeElement('supp_service_tpd');
        $this->removeElement('supp_service_tpd_unit');
        $this->removeElement('supp_service_days_value');
        $this->removeElement('supp_service_days_unit');
        return $this;
    }

	
	public function supp_services_edit_version1() {
				
		$this->setDecorators ( array (
//				                'PrepareElements', 
								array ('ViewScript', 
									array (
										'viewScript' => 'form004/supp_services_edit_version1.phtml' 
									) 
								) 
							) );
		
		//Add elements here

        $this->supp_service =  $this->buildEditor('supp_service', array('label'=>'Supplemental Service Description:'));
		// $this->supp_service->setDecorators ( My_Classes_Decorators::$dojoSubformEditorDecorators);
//		$this->supp_service->setAllowEmpty(false);
//		$this->supp_service->setRequired(true);
//		$this->supp_service->setLabel('Supplemental Service Description:');
		$this->supp_service->addErrorMessage('Supplemental Service Description is empty.');
			
		
		// related services drop down menu
		$arrLabel = array("Audiological Services", "Braile/LP/Recorded Material", "Counseling", "Medical Diagnostic Services", "Home School Liaison", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy", "Psychological Services", "Reader", "Recreation", "School Health", "Speech/Language Therapy", "Transportation", "Vocational Training", "Assistive Technology Device", "Assistive Technology Service", "Other (Please Specify)");
		$arrValue = array("Audiological Services", "Braile/LP/Recorded Material", "Counseling", "Medical Diagnostic Services", "Home School Liaison", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy", "Psychological Services", "Reader", "Recreation", "School Health", "Speech/Language Therapy", "Transportation", "Vocational Training", "Assistive Technology Device", "Assistive Technology Service", "Other (Please Specify)");	
		
		// dynamic related services location drop - populated by form004 controller
		$this->supp_service_location = new App_Form_Element_Select('supp_service_location', array('label'=>'Supplementary Aids and Services: Location'));
		$this->supp_service_location->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		//		$this->supp_service_location->setLabel( 'Supplementary Aids and Services: Location' );

		
		$this->supp_service_from_date = new App_Form_Element_DatePicker('supp_service_from_date', array('label'=>'Supplemental Service Date From:'));
//        $this->supp_service_from_date->setLabel('Supplemental Service Date From:');
		$this->supp_service_from_date->addErrorMessage('You have not chosen the duration \'from\' date of the service.');
//        $this->supp_service_from_date->setAttrib('style', "width:80px;");
		$this->supp_service_from_date->setInlineDecorators();
        $this->supp_service_from_date->setAttrib('class', 'from_date ' . $this->supp_service_from_date->getAttrib('class'));
			//
			// custom addition - elements wrappers - highlight a group of elements instead of just one
			// 
			// field wrapper - highlighting around more than one element
			// pass a tag unique to the encapsulated elements as the second parameter of colorMeById - to change wrapper yellow
			// set the wrapper_tag attribute of  
			$this->supp_service_from_date->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
			$this->supp_service_from_date->setAttrib('wrapped', 'dates_wrapper');
//		$this->supp_service_from_date->setRequired(true);
//        $this->supp_service_from_date->setAllowEmpty(false);

        		
		$this->supp_service_to_date = new App_Form_Element_DatePicker('supp_service_to_date', array('label'=>'Supplemental Service Date To:'));
//        $this->supp_service_to_date->setLabel('Supplemental Service Date To:');
		$this->supp_service_to_date->addErrorMessage('You have not chosen the duration \'to\' date of the service.');
//		$this->supp_service_to_date->setAttrib('style', "width:80px;");
		$this->supp_service_to_date->setInlineDecorators();
			$this->supp_service_to_date->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
			$this->supp_service_to_date->setAttrib('wrapped', 'dates_wrapper');
        $this->supp_service_to_date->setAttrib('class', 'to_date ' . $this->supp_service_to_date->getAttrib('class'));
//		$this->supp_service_to_date->setRequired(true);
//        $this->supp_service_to_date->setAllowEmpty(false);

		// with reg education peers
		// value per day quantity 
		//
		$this->supp_service_tpd = new App_Form_Element_Text('supp_service_tpd', array('label'=>'min/day / hours/day value'));
		$this->supp_service_tpd->setAttrib('size', '6');
		$this->supp_service_tpd->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		//		$this->supp_service_tpd->setLABEL("min/day / hours/day value");
//		$this->supp_service_tpd->setAllowEmpty(false);
//		$this->supp_service_tpd->setRequired(true);
		$this->supp_service_tpd->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');");
			$this->supp_service_tpd->setAttrib('wrapped', 'tpd_wrapper');
		
		$arrLabel = array("min/day", "hrs/day");
		$arrValue = array("m", "h");
		$this->supp_service_tpd_unit = new App_Form_Element_Select('supp_service_tpd_unit', array('label'=>''));
		$this->supp_service_tpd_unit->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->supp_service_tpd_unit->setMultiOptions(array_combine($arrValue, $arrLabel));
		$this->supp_service_tpd_unit->setLABEL("min/day / hours/day");
//		$this->supp_service_tpd_unit->setAllowEmpty(false);
//		$this->supp_service_tpd_unit->setRequired(true);
		$this->supp_service_tpd_unit->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');");
			$this->supp_service_tpd_unit->setAttrib('wrapped', 'tpd_wrapper');
		
		
		// with reg education peers
		// value per day units
		//
		$this->supp_service_days_value = new App_Form_Element_Text('supp_service_days_value', array('label'=>''));
		$this->supp_service_days_value->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->supp_service_days_value->setAttrib('size', '6');
//		$this->supp_service_days_value->setAllowEmpty(false);
//		$this->supp_service_days_value->setRequired(true);
		$this->supp_service_days_value->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'days_wrapper');");
			$this->supp_service_days_value->setAttrib('wrapped', 'days_wrapper');
		
		$arrLabel = array("days/week", "days/month", "days/quarter", "days/semester", "days/year");
		$arrValue = array("w", "m", "q", "s", "y");
		$this->supp_service_days_unit = new App_Form_Element_Select('supp_service_days_unit', array('label'=>'Days value'));
		$this->supp_service_days_unit->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->supp_service_days_unit->setMultiOptions(array_combine($arrValue, $arrLabel));
//		$this->supp_service_days_unit->setAllowEmpty(false);
//		$this->supp_service_days_unit->setRequired(true);
		$this->supp_service_days_unit->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'days_wrapper');");
			$this->supp_service_days_unit->setAttrib('wrapped', 'days_wrapper');
//		$this->supp_service_days_unit->setLabel("Days value");
		$this->supp_service_days_unit->addErrorMessage('You have not entered a days value.');
			
		//
		// months input
		//
		$this->supp_service_mpy = new App_Form_Element_Text('supp_service_mpy', array('label'=>'Months'));
		$this->supp_service_mpy->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->supp_service_mpy->setAllowEmpty(false);
		$this->supp_service_mpy->setRequired(true);
		$this->supp_service_mpy->setAttrib('size', '6');
		$this->supp_service_mpy->setLabel("Months");
		$this->supp_service_mpy->addErrorMessage('You have not entered a number of months.');
		
		$arrLabel = array("Yes", "No");
		$arrValue = array("t", "f");
		$this->supp_service_calendar = new App_Form_Element_Select('supp_service_calendar', array('label'=>'supp_service_calendar'));
		$this->supp_service_calendar->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->supp_service_calendar->setMultiOptions(array_combine($arrValue, $arrLabel));
		
		
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
        $this->subform_label = new App_Form_Element_Hidden('subform_label ', array('label'=>'Supplemental Services Row'));
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Supplemental Services Row");
        
        $this->id_form_004_supp_service = new App_Form_Element_Hidden('id_form_004_supp_service');
				
		$this->dev_delay = new App_Form_Element_Hidden('dev_delay');
		$this->dev_delay->ignore = true;
				
		return $this;		
	}
	
}

