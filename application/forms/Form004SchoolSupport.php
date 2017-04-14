<?php

class Form_Form004SchoolSupport extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

	public function init()
	{
	    $this->setEditorType('App_Form_Element_TestEditorTab');
	}
	
    public function school_supp_edit_version2() {return $this->school_supp_edit_version1();}
    public function school_supp_edit_version3() {return $this->school_supp_edit_version1();}
    public function school_supp_edit_version4() {return $this->school_supp_edit_version1();}
    public function school_supp_edit_version5() {return $this->school_supp_edit_version1();}
    public function school_supp_edit_version6() {return $this->school_supp_edit_version1();}
    public function school_supp_edit_version7() {return $this->school_supp_edit_version1();}
    public function school_supp_edit_version8() {return $this->school_supp_edit_version1();}
    public function school_supp_edit_version9() {return $this->school_supp_edit_version1();}
    public function school_supp_edit_version10() {return $this->school_supp_edit_version1();}
    public function school_supp_edit_version11() {
        $this->school_supp_edit_version1();
        $this->setDecorators ( array (
//				                'PrepareElements',
                array ('ViewScript',
                    array (
                        'viewScript' => 'form004/school_supp_edit_version11.phtml'
                    )
                )
            ) );

        $this->removeElement('school_supp_mpy');
//        $this->removeElement('school_supp_from_date');
//        $this->removeElement('school_supp_to_date');
        $this->removeElement('school_supp_tpd');
        $this->removeElement('school_supp_tpd_unit');
        $this->removeElement('school_supp_days_value');
        $this->removeElement('school_supp_days_unit');
        return $this;
    }

	public function school_supp_edit_version1() {
		
		$this->setDecorators ( array (
//				                'PrepareElements', 
								array ('ViewScript', 
									array (
										'viewScript' => 'form004/school_supp_edit_version1.phtml' 
									) 
								) 
							) );
		
		//Add elements here
        $this->supports =  $this->buildEditor('supports', array('label'=>''));
		$this->supports->setAllowEmpty(false);
		$this->supports->setRequired(true);
		$this->supports->setLabel('School Supports Description:');  
		$this->supports->addErrorMessage('School Supports Description is empty.');  
		
		// related services drop down menu
		$arrLabel = array("Audiological Services", "Braile/LP/Recorded Material", "Counseling", "Medical Diagnostic Services", "Home School Liaison", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy", "Psychological Services", "Reader", "Recreation", "School Health", "Speech/Language Therapy", "Transportation", "Vocational Training", "Assistive Technology Device", "Assistive Technology Service", "Other (Please Specify)");
		$arrValue = array("Audiological Services", "Braile/LP/Recorded Material", "Counseling", "Medical Diagnostic Services", "Home School Liaison", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy", "Psychological Services", "Reader", "Recreation", "School Health", "Speech/Language Therapy", "Transportation", "Vocational Training", "Assistive Technology Device", "Assistive Technology Service", "Other (Please Specify)");	
		
		// dynamic related services location drop - populated by form004 controller
		$this->school_supp_location = new App_Form_Element_Select('school_supp_location', array('label'=>'Supports for School Personnel: Location'));
		$this->school_supp_location->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		
        
		$this->school_supp_from_date = new App_Form_Element_DatePicker('school_supp_from_date', array('label'=>'Supports for School Personnel Date From:'));
		$this->school_supp_from_date->addErrorMessage('You have not chosen the duration \'from\' date of the service.');
		$this->school_supp_from_date->setInlineDecorators();
        $this->school_supp_from_date->setAttrib('class', 'from_date ' . $this->school_supp_from_date->getAttrib('class'));

		// custom addition - elements wrappers - highlight a group of elements instead of just one
		// field wrapper - highlighting around more than one element
		// pass a tag unique to the encapsulated elements as the second parameter of colorMeById - to change wrapper yellow
		// set the wrapper_tag attribute of  
		$this->school_supp_from_date->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
		$this->school_supp_from_date->setAttrib('wrapped', 'dates_wrapper');
		
        		
		$this->school_supp_to_date = new App_Form_Element_DatePicker('school_supp_to_date', array('label'=>' Supports for School Personnel Date To:'));
		$this->school_supp_to_date->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->school_supp_to_date->addErrorMessage('You have not chosen the duration \'to\' date of the service.');
		$this->school_supp_to_date->setInlineDecorators();
		$this->school_supp_to_date->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');");
		$this->school_supp_to_date->setAttrib('wrapped', 'dates_wrapper');
        $this->school_supp_to_date->setAttrib('class', 'to_date ' . $this->school_supp_to_date->getAttrib('class'));

        // with reg education peers
		// value per day quantity 
		//
		$this->school_supp_tpd = new App_Form_Element_Text('school_supp_tpd', array('label'=>'min/day / hours/day value'));
		$this->school_supp_tpd->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->school_supp_tpd->setAttrib('size', '6');
		$this->school_supp_tpd->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');");
		$this->school_supp_tpd->setAttrib('wrapped', 'tpd_wrapper');
		
		$arrLabel = array("min/day", "hrs/day");
		$arrValue = array("m", "h");
		$this->school_supp_tpd_unit = new App_Form_Element_Select('school_supp_tpd_unit', array('label'=>'min/day / hours/day'));
		$this->school_supp_tpd_unit->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->school_supp_tpd_unit->setMultiOptions(array_combine($arrValue, $arrLabel));
//		$this->school_supp_tpd_unit->setLABEL("min/day / hours/day");
		$this->school_supp_tpd_unit->setAllowEmpty(false);
		$this->school_supp_tpd_unit->setRequired(true);
		$this->school_supp_tpd_unit->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');");
			$this->school_supp_tpd_unit->setAttrib('wrapped', 'tpd_wrapper');
		
		// with reg education peers
		// value per day units
		//
		$this->school_supp_days_value = new App_Form_Element_Text('school_supp_days_value');
		$this->school_supp_days_value->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->school_supp_days_value->setAttrib('size', '6');
		$this->school_supp_days_value->setAllowEmpty(false);
		$this->school_supp_days_value->setRequired(true);
		$this->school_supp_days_value->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'days_wrapper');");
			$this->school_supp_days_value->setAttrib('wrapped', 'days_wrapper');
		
		$arrLabel = array("days/week", "days/month", "days/quarter", "days/semester", "days/year");
		$arrValue = array("w", "m", "q", "s", "y");
		$this->school_supp_days_unit = new App_Form_Element_Select('school_supp_days_unit', array('label'=>'Days value'));
		$this->school_supp_days_unit->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		$this->school_supp_days_unit->setMultiOptions(array_combine($arrValue, $arrLabel));
//		$this->school_supp_days_unit->setAllowEmpty(false);
//		$this->school_supp_days_unit->setRequired(true);
		$this->school_supp_days_unit->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'days_wrapper');");
			$this->school_supp_days_unit->setAttrib('wrapped', 'days_wrapper');
//		$this->school_supp_days_unit->setLabel("Days value");
		$this->school_supp_days_unit->addErrorMessage('You have not entered a days value.');
			
		//
		// months input
		//
		$this->school_supp_mpy = new App_Form_Element_Text('school_supp_mpy', array('label'=>'Months'));
		$this->school_supp_mpy->setDecorators ( My_Classes_Decorators::$emptyDecorators );
//		$this->school_supp_mpy->setAllowEmpty(false);
//		$this->school_supp_mpy->setRequired(true);
		$this->school_supp_mpy->setAttrib('size', '6');
//		$this->school_supp_mpy->setLabel("Months");
		$this->school_supp_mpy->addErrorMessage('You have not entered a number of months.');
		
		$arrLabel = array("Yes", "No");
		$arrValue = array("t", "f");
		$this->school_supp_calendar = new App_Form_Element_Select('school_supp_calendar');
		$this->school_supp_calendar->setMultiOptions(array_combine($arrValue, $arrLabel));
		$this->school_supp_calendar->setDecorators ( My_Classes_Decorators::$emptyDecorators );
		
		
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
        $this->subform_label->setLabel("School Supports Row");
        
        $this->id_form_004_school_supp = new App_Form_Element_Hidden('id_form_004_school_supp');
				
		$this->dev_delay = new App_Form_Element_Hidden('dev_delay');
		$this->dev_delay->ignore = true;
				
		return $this;
				
	}
	
}

