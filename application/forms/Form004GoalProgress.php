<?php

class Form_Form004GoalProgress extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	public function init()
	{
	    $this->setEditorType('App_Form_Element_TestEditorTab');
	}
	
    public function goal_progress_edit_version2() {return $this->goal_progress_edit_version1();}
    public function goal_progress_edit_version3() {return $this->goal_progress_edit_version1();}
    public function goal_progress_edit_version4() {return $this->goal_progress_edit_version1();}
    public function goal_progress_edit_version5() {return $this->goal_progress_edit_version1();}
    public function goal_progress_edit_version6() {return $this->goal_progress_edit_version1();}
    public function goal_progress_edit_version7() {return $this->goal_progress_edit_version1();}
    public function goal_progress_edit_version8() {return $this->goal_progress_edit_version1();}
    public function goal_progress_edit_version9() {return $this->goal_progress_edit_version1();}
    public function goal_progress_edit_version10() {return $this->goal_progress_edit_version1();}
    public function goal_progress_edit_version11() {return $this->goal_progress_edit_version1();}

	public function goal_progress_edit_version1() {
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form004/goal_progress_edit_v1.phtml' ) ) ) );
		//
		// these fields are currenly being used to 
		// help build district optional parts of the form
		// they exist so that we can access data that is populated into the form
		//
		$this->rownumber = new App_Form_Element_Hidden('rownumber');
		$this->rownumber->ignore = true;
		
		$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check to remove row'));
		$this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->remove_row->ignore = true;
		
		//
		// named displayed in validation output
		//
		$this->subform_label = new App_Form_Element_Hidden('subform_label');
		$this->subform_label->ignore = true;
		$this->subform_label->setLabel("Goal");
		
		$this->id_goal_progress = new App_Form_Element_Hidden('id_goal_progress');

		// related services drop down menu
		$multiOptions = array(""=>"Choose...", "A" => "Goal Met", "B" => "Progress Made, Goal Not Met", "C" => "Little or No Progress", "D" => "Other specify");
		$this->progress_measurement = new App_Form_Element_Select('progress_measurement',array('label'=>"Progress Measurement"));
		$this->progress_measurement->setMultiOptions($multiOptions);
        $this->progress_measurement->setDecorators(App_Form_DecoratorHelper::inlineElement());
//		$this->progress_measurement->addOnChange("toggleShowOnMatchById(this.value, 'D', 'show_hide_progress_measurement_explain')");
// 		$this->progress_measurement->addOnChange("toggleShowOnMatchByIdSubform(this.value, 'D', 'show_hide_progress_measurement_explain', this.id)");
		
		$this->progress_measurement_explain = $this->buildEditor('progress_measurement_explain', array('label'=>'Progress Measurement Explain'));
//        $this->progress_measurement_explain->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
		$this->progress_measurement_explain->setRequired(false);
        $this->progress_measurement_explain->setAllowEmpty(false);
        $this->progress_measurement_explain->removeEditorEmptyValidator();
        //$this->progress_measurement_explain->addValidator(new My_Validate_Form010ProgressMeasurementExplainEditor('progress_measurement', 'D'));
        $this->progress_measurement_explain->addErrorMessage('cannot be empty when Progress Measurement is "Other" and must be empty when Progress Measurement is not "Other".');  
		
		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->progress_sufficient = new App_Form_Element_Radio('progress_sufficient', array('label'=>'Sufficient', 'multiOptions'=>$multiOptions)); 
		$this->progress_sufficient->addErrorMessage('Please indicate whether the progress has been sufficient.');  
		$this->progress_sufficient->addValidator(new My_Validate_BooleanNotEmpty(), true);  
		$this->progress_sufficient->setAllowEmpty(false);  
		$this->progress_sufficient->setDecorators(App_Form_DecoratorHelper::inlineElement());
		
		$this->progress_comment = $this->buildEditor('progress_comment', array('label'=>'Comments'));
//		$this->progress_comment->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
		
        $this->progress_chart_id = new App_Form_Element_Select('progress_chart_id',array('label'=>"Chart List"));
        $this->progress_chart_id->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->progress_chart_id->addOnChange("setToRefresh();");
		$this->progress_chart_id->noValidation();
		$this->progress_chart_id->setRegisterInArrayValidator(false); 

		$multiOptions = array('100'=>'Very Large', '80'=>'Large', '65'=>'Medium', '50'=>'Small');
		$this->progress_chart_scale = new App_Form_Element_Select('progress_chart_scale',array('label'=>"Scale"));
		$this->progress_chart_scale->setMultiOptions($multiOptions);
        $this->progress_chart_scale->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->progress_chart_scale->addOnChange("setToRefresh();");
        $this->progress_chart_scale->noValidation();
		$this->progress_chart_scale->setRegisterInArrayValidator(false);
		
        
		return $this;
	}
	
}
