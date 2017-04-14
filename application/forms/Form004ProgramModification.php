<?php

class Form_Form004ProgramModification extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditorTab');
	}
	
    public function program_modifications_edit_version2() {return $this->program_modifications_edit_version1();}
    public function program_modifications_edit_version3() {return $this->program_modifications_edit_version1();}
    public function program_modifications_edit_version4() {return $this->program_modifications_edit_version1();}
    public function program_modifications_edit_version5() {return $this->program_modifications_edit_version1();}
    public function program_modifications_edit_version6() {return $this->program_modifications_edit_version1();}
    public function program_modifications_edit_version7() {return $this->program_modifications_edit_version1();}
    public function program_modifications_edit_version8() {return $this->program_modifications_edit_version1();}
    public function program_modifications_edit_version9() {return $this->program_modifications_edit_version1();}
    public function program_modifications_edit_version10() {return $this->program_modifications_edit_version1();}
    public function program_modifications_edit_version11() {
        $this->program_modifications_edit_version1();
        $this->setDecorators ( array (
//				                'PrepareElements',
                array ('ViewScript',
                    array (
                        'viewScript' => 'form004/program_modifications_edit_version11.phtml'
                    )
                )
            ) );

        $this->removeElement('prog_mod_mpy');
//        $this->removeElement('prog_mod_from_date');
//        $this->removeElement('prog_mod_to_date');
        $this->removeElement('prog_mod_tpd');
        $this->removeElement('prog_mod_tpd_unit');
        $this->removeElement('prog_mod_days_value');
        $this->removeElement('prog_mod_days_unit');

        return $this;
    }

	
	public function program_modifications_edit_version1() {
		
		$this->setDecorators ( array (
//				                'PrepareElements', 
								array ('ViewScript', 
									array (
										'viewScript' => 'form004/program_modifications_edit_version1.phtml' 
									) 
								) 
							) );
		
		//Add elements here
        $this->prog_mod =  $this->buildEditor('prog_mod', array('label', 'Program Modifications Description:'));
		// $this->prog_mod->setDecorators ( My_Classes_Decorators::$dojoSubformEditorDecorators);
//		$this->prog_mod->setAllowEmpty(false);
//		$this->prog_mod->setRequired(true);
//		$this->prog_mod->setLabel('Program Modifications Description:');  
		$this->prog_mod->addErrorMessage('Program Modifications Description is empty.');  
			
							
		// related services drop down menu
		$arrLabel = array("Audiological Services", "Braile/LP/Recorded Material", "Counseling", "Medical Diagnostic Services", "Home School Liaison", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy", "Psychological Services", "Reader", "Recreation", "School Health", "Speech/Language Therapy", "Transportation", "Vocational Training", "Assistive Technology Device", "Assistive Technology Service", "Other (Please Specify)");
		$arrValue = array("Audiological Services", "Braile/LP/Recorded Material", "Counseling", "Medical Diagnostic Services", "Home School Liaison", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy", "Psychological Services", "Reader", "Recreation", "School Health", "Speech/Language Therapy", "Transportation", "Vocational Training", "Assistive Technology Device", "Assistive Technology Service", "Other (Please Specify)");	
		
		// dynamic related services location drop - populated by form004 controller
		$this->prog_mod_location = new App_Form_Element_Select('prog_mod_location', array('label', 'Program Modifications & Accommodations: Location'));
		$this->prog_mod_location->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		//		$this->prog_mod_location->setLabel( 'Program Modifications & Accommodations: Location' );
			
		
		$this->prog_mod_from_date = new App_Form_Element_DatePicker('prog_mod_from_date', array('label', 'Program Modifications Date From:'));
//        $this->prog_mod_from_date->setLabel('Program Modifications Date From:');
		$this->prog_mod_from_date->addErrorMessage('You have not chosen the duration \'from\' date of the service.');
//        $this->prog_mod_from_date->setAttrib('style', "width:80px;");
		$this->prog_mod_from_date->setInlineDecorators();
        $this->prog_mod_from_date->setAttrib('class', 'from_date ' . $this->prog_mod_from_date->getAttrib('class'));
			//
			// custom addition - elements wrappers - highlight a group of elements instead of just one
			// 
			// field wrapper - highlighting around more than one element
			// pass a tag unique to the encapsulated elements as the second parameter of colorMeById - to change wrapper yellow
			// set the wrapper_tag attribute of  
			$this->prog_mod_from_date->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
			$this->prog_mod_from_date->setAttrib('wrapped', 'dates_wrapper');
//		$this->prog_mod_from_date->setRequired(true);
//        $this->prog_mod_from_date->setAllowEmpty(false);
		
        		
		$this->prog_mod_to_date = new App_Form_Element_DatePicker('prog_mod_to_date', array('label', 'Program Modifications Date To:'));
//        $this->prog_mod_to_date->setLabel('Program Modifications Date To:');
		$this->prog_mod_to_date->addErrorMessage('You have not chosen the duration \'to\' date of the service.');
//		$this->prog_mod_to_date->setAttrib('style', "width:80px;");
		$this->prog_mod_to_date->setInlineDecorators();
			$this->prog_mod_to_date->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
			$this->prog_mod_to_date->setAttrib('wrapped', 'dates_wrapper');
        $this->prog_mod_to_date->setAttrib('class', 'to_date ' . $this->prog_mod_to_date->getAttrib('class'));
//		$this->prog_mod_to_date->setRequired(true);
//        $this->prog_mod_to_date->setAllowEmpty(false);
		
		// with reg education peers
		// value per day quantity 
		$this->prog_mod_tpd = new App_Form_Element_Text('prog_mod_tpd', array('label', "min/day / hours/day value"));
		$this->prog_mod_tpd->setAttrib('size', '6');
		$this->prog_mod_tpd->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		//		$this->prog_mod_tpd->setLABEL("min/day / hours/day value");
//		$this->prog_mod_tpd->setAllowEmpty(false);
//		$this->prog_mod_tpd->setRequired(true);
		$this->prog_mod_tpd->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');");
			$this->prog_mod_tpd->setAttrib('wrapped', 'tpd_wrapper');
		
		$arrLabel = array("min/day", "hrs/day");
		$arrValue = array("m", "h");
		$this->prog_mod_tpd_unit = new App_Form_Element_Select('prog_mod_tpd_unit', array('label', "min/day / hours/day"));
		$this->prog_mod_tpd_unit->setMultiOptions(array_combine($arrValue, $arrLabel));
		$this->prog_mod_tpd_unit->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		//		$this->prog_mod_tpd_unit->setLABEL("min/day / hours/day");
//		$this->prog_mod_tpd_unit->setAllowEmpty(false);
//		$this->prog_mod_tpd_unit->setRequired(true);
		$this->prog_mod_tpd_unit->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');");
			$this->prog_mod_tpd_unit->setAttrib('wrapped', 'tpd_wrapper');
		
		// with reg education peers
		// value per day units
		$this->prog_mod_days_value = new App_Form_Element_Text('prog_mod_days_value');
		$this->prog_mod_days_value->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->prog_mod_days_value->setAttrib('size', '6');
//		$this->prog_mod_days_value->setAllowEmpty(false);
//		$this->prog_mod_days_value->setRequired(true);
		$this->prog_mod_days_value->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'days_wrapper');");
			$this->prog_mod_days_value->setAttrib('wrapped', 'days_wrapper');
		
		$arrLabel = array("days/week", "days/month", "days/quarter", "days/semester", "days/year");
		$arrValue = array("w", "m", "q", "s", "y");
		$this->prog_mod_days_unit = new App_Form_Element_Select('prog_mod_days_unit', array('label', "Days value"));
		$this->prog_mod_days_unit->setMultiOptions(array_combine($arrValue, $arrLabel));
		$this->prog_mod_days_unit->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		//		$this->prog_mod_days_unit->setAllowEmpty(false);
//		$this->prog_mod_days_unit->setRequired(true);
		$this->prog_mod_days_unit->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'days_wrapper');");
			$this->prog_mod_days_unit->setAttrib('wrapped', 'days_wrapper');
//		$this->prog_mod_days_unit->setLabel("Days value");
		$this->prog_mod_days_unit->addErrorMessage('You have not entered a days value.');
			
		// months input
		$this->prog_mod_mpy = new App_Form_Element_Text('prog_mod_mpy', array('label', "Months"));
		$this->prog_mod_mpy->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		//		$this->prog_mod_mpy->setAllowEmpty(false);
//		$this->prog_mod_mpy->setRequired(true);
		$this->prog_mod_mpy->setAttrib('size', '6');
//		$this->prog_mod_mpy->setLabel("Months");
		$this->prog_mod_mpy->addErrorMessage('You have not entered a number of months.');
		
		
		
		$arrLabel = array("Yes", "No");
		$arrValue = array("t", "f");
		$this->prog_mod_calendar = new App_Form_Element_Select('prog_mod_calendar');
		$this->prog_mod_calendar->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->prog_mod_calendar->setMultiOptions(array_combine($arrValue, $arrLabel));

		$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label', 'Check to remove row'));
		$this->remove_row->setDecorators ( My_Classes_Decorators::$labelDecorators );
		$this->remove_row->setLabel("Remove Row");
		$this->remove_row->ignore = true;
		
        // these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;
        
        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Program Modifications Row");
        
        $this->id_form_004_prog_mods = new App_Form_Element_Hidden('id_form_004_prog_mods');
				
		$this->dev_delay = new App_Form_Element_Hidden('dev_delay');
		$this->dev_delay->ignore = true;
				
		return $this;
				
	}
		
}

