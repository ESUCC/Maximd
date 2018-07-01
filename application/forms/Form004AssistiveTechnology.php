<?php

class Form_Form004AssistiveTechnology extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditorTab');
	}
	
	public function assist_tech_edit_version9() {
		return $this->assist_tech_edit_version1();
	}
	public function assist_tech_edit_version10() {
		return $this->assist_tech_edit_version1();
	}
	public function assist_tech_edit_version11() {
		$this->assist_tech_edit_version1();
        $this->setDecorators ( array (
//				                'PrepareElements',
                array ('ViewScript',
                    array (
                        'viewScript' => 'form004/assist_tech_edit_version11.phtml'
                    )
                )
            ) );

        $this->removeElement('assist_tech_mpy');
        return $this;
	}
	public function assist_tech_edit_version1() {
		
		$this->setDecorators ( array (
//				                'PrepareElements', 
								array ('ViewScript', 
									array (
										'viewScript' => 'form004/assist_tech_edit_version1.phtml' 
									) 
								) 
							) );
		
		//Add elements here
		
      $this->ass_tech = $this->buildEditor('ass_tech', array('label', 'Program Modifications Description:'));
		// $this->ass_tech->setDecorators ( My_Classes_Decorators::$dojoSubformEditorDecorators);
		$this->ass_tech->addErrorMessage('Program Modifications Description is empty.');  
			
							
		// related services drop down menu
		$arrLabel = array("Audiological Services", "Braile/LP/Recorded Material", "Counseling", "Medical Diagnostic Services", "Home School Liaison", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy", "Psychological Services", "Reader", "Recreation", "School Health", "Speech/Language Therapy", "Transportation", "Vocational Training", "Assistive Technology Device", "Assistive Technology Service", "Other (Please Specify)");
		$arrValue = array("Audiological Services", "Braile/LP/Recorded Material", "Counseling", "Medical Diagnostic Services", "Home School Liaison", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy", "Psychological Services", "Reader", "Recreation", "School Health", "Speech/Language Therapy", "Transportation", "Vocational Training", "Assistive Technology Device", "Assistive Technology Service", "Other (Please Specify)");	
		
		// dynamic related services location drop - populated by form004 controller
		$this->assist_tech_location = new App_Form_Element_Select('assist_tech_location', array('label', 'Assistive Technology Devices or Services: Location'));
		$this->assist_tech_location->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->assist_tech_location->setMultiOptions(array_combine($arrLabel, $arrValue));
		
		
		$this->assist_tech_from_date = new App_Form_Element_DatePicker('assist_tech_from_date', array('label', 'Assistive Technology Date From:'));
		$this->assist_tech_from_date->addErrorMessage('You have not chosen the duration \'from\' date of the service.');
//        $this->assist_tech_from_date->setAttrib('style', "width:80px;");
		$this->assist_tech_from_date->setInlineDecorators();
			//
			// custom addition - elements wrappers - highlight a group of elements instead of just one
			// 
			// field wrapper - highlighting around more than one element
			// pass a tag unique to the encapsulated elements as the second parameter of colorMeById - to change wrapper yellow
			// set the wrapper_tag attribute of  
			$this->assist_tech_from_date->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
			$this->assist_tech_from_date->setAttrib('wrapped', 'dates_wrapper');
		
        		
		$this->assist_tech_to_date = new App_Form_Element_DatePicker('assist_tech_to_date', array('label', 'Assistive Technology Date To:'));
		$this->assist_tech_to_date->addErrorMessage('You have not chosen the duration \'to\' date of the service.');
//		$this->assist_tech_to_date->setAttrib('style', "width:80px;");
		$this->assist_tech_to_date->setInlineDecorators();
			$this->assist_tech_to_date->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
			$this->assist_tech_to_date->setAttrib('wrapped', 'dates_wrapper');
		
		// with reg education peers
		// value per day quantity 
		$this->assist_tech_tpd = new App_Form_Element_Text('assist_tech_tpd', array('label', "min/day / hours/day value"));
		$this->assist_tech_tpd->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->assist_tech_tpd->setAttrib('size', '6');
		$this->assist_tech_tpd->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');");
			$this->assist_tech_tpd->setAttrib('wrapped', 'tpd_wrapper');
		
		$arrLabel = array("min/day", "hrs/day");
		$arrValue = array("m", "h");
		$this->assist_tech_tpd_unit = new App_Form_Element_Select('assist_tech_tpd_unit', array('label', "min/day / hours/day"));
		$this->assist_tech_tpd_unit->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->assist_tech_tpd_unit->setMultiOptions(array_combine($arrValue, $arrLabel));
		$this->assist_tech_tpd_unit->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');");
			$this->assist_tech_tpd_unit->setAttrib('wrapped', 'tpd_wrapper');
		
		// with reg education peers
		// value per day units
		$this->assist_tech_days_value = new App_Form_Element_Text('assist_tech_days_value');
		$this->assist_tech_days_value->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->assist_tech_days_value->setAttrib('size', '6');
		$this->assist_tech_days_value->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'days_wrapper');");
			$this->assist_tech_days_value->setAttrib('wrapped', 'days_wrapper');
		
		$arrLabel = array("days/week", "days/month", "days/quarter", "days/semester", "days/year");
		$arrValue = array("w", "m", "q", "s", "y");
		$this->assist_tech_days_unit = new App_Form_Element_Select('assist_tech_days_unit', array('label', "Days value"));
		$this->assist_tech_days_unit->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->assist_tech_days_unit->setMultiOptions(array_combine($arrValue, $arrLabel));
		$this->assist_tech_days_unit->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'days_wrapper');");
			$this->assist_tech_days_unit->setAttrib('wrapped', 'days_wrapper');
		$this->assist_tech_days_unit->addErrorMessage('You have not entered a days value.');
			
		// months input
		$this->assist_tech_mpy = new App_Form_Element_Text('assist_tech_mpy', array('label', "Months"));
		$this->assist_tech_mpy->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->assist_tech_mpy->setAttrib('size', '6');
		$this->assist_tech_mpy->addErrorMessage('You have not entered a number of months.');
		
		
		
		$arrLabel = array("Yes", "No");
		$arrValue = array("t", "f");
		$this->assist_tech_calendar = new App_Form_Element_Select('assist_tech_calendar');
		$this->assist_tech_calendar->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->assist_tech_calendar->setMultiOptions(array_combine($arrValue, $arrLabel));
		
		$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label', 'Check to remove row'));
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
        $this->subform_label->setLabel("Assistive Technology Row");
        
        $this->id_form_004_assist_tech = new App_Form_Element_Hidden('id_form_004_assist_tech');
				
		$this->dev_delay = new App_Form_Element_Hidden('dev_delay');
		$this->dev_delay->ignore = true;
				
		return $this;		
	}
	
}

