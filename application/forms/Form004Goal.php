<?php

class Form_Form004Goal extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	public function init()
	{
		$this->setEditorType('App_Form_Element_TestEditorTab');
	}
	
    public function iep_form_004_goal_edit_version11() { return $this->iep_form_004_goal_edit_version1(); }
    public function iep_form_004_goal_edit_version10() { return $this->iep_form_004_goal_edit_version1(); }
    public function iep_form_004_goal_edit_version9() { return $this->iep_form_004_goal_edit_version1(); }
    public function iep_form_004_goal_edit_version8() { return $this->iep_form_004_goal_edit_version1(); }
    public function iep_form_004_goal_edit_version7() { return $this->iep_form_004_goal_edit_version1(); }
    public function iep_form_004_goal_edit_version6() { return $this->iep_form_004_goal_edit_version1(); }
    public function iep_form_004_goal_edit_version5() { return $this->iep_form_004_goal_edit_version1(); }
    public function iep_form_004_goal_edit_version4() { return $this->iep_form_004_goal_edit_version1(); }
    public function iep_form_004_goal_edit_version3() { return $this->iep_form_004_goal_edit_version1(); }
    public function iep_form_004_goal_edit_version2() { return $this->iep_form_004_goal_edit_version1(); }
	public function iep_form_004_goal_edit_version1() {
		$this->setDecorators ( array (
//				                'PrepareElements', 
								array ('ViewScript', 
									array (
										'viewScript' => 'form004/goal_edit_v1.phtml' 
									) 
								) 
							) );
		
		$this->id_form_004_goal = new Zend_Form_Element_Hidden('id_form_004_goal');
		$this->id_form_004_goal = new App_Form_Element_Hidden('id_form_004_goal');
		$this->id_form_004_goal->ignore = true;
		
		$sessUser = new Zend_Session_Namespace('user');
//		if(1000254 == $sessUser->sessIdUser
//		 //|| 1010818 == $sessUser->sessIdUser
//		 ) {
//			// Textareas used for forms inside contentPanes because auto expanding isn't working there
//			$this->measurable_ann_goal =  $this->buildEditor('measurable_ann_goal', array('label'=>'Measurable Annual Goals:'));
//		} else {
			// Textareas used for forms inside contentPanes because auto expanding isn't working there
			$this->measurable_ann_goal =  $this->buildEditor('measurable_ann_goal', array('label'=>'Measurable Annual Goals:'));
//		}
		// $this->measurable_ann_goal->setDecorators ( My_Classes_Decorators::$dojoSubformEditorDecorators);
		$this->measurable_ann_goal->addErrorMessage('Measurable annual goal is empty.');  
		
		
		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->alternate_assessments = new App_Form_Element_Radio('alternate_assessments', array('label'=>'Alternate Assessments:'));
		$this->alternate_assessments->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->alternate_assessments->setMultiOptions($multiOptions);
		$this->alternate_assessments->setSeparator(' ');
		$this->alternate_assessments->addErrorMessage('Alternate Assessments is empty.');
		
//		if(1000254 == $sessUser->sessIdUser
//		 //|| 1010818 == $sessUser->sessIdUser
//		 ) {
//			$this->short_term_obj = $this->buildEditor('short_term_obj', array('label'=>'Short Term Objectives:'));
//		} else {
			
//		}
		$this->short_term_obj = $this->buildEditor('short_term_obj', array('label'=>'Short Term Objectives:'));
		// $this->short_term_obj->setDecorators ( My_Classes_Decorators::$dojoSubformEditorDecorators);
		$this->short_term_obj->removeEditorEmptyValidator();
		$this->short_term_obj->addValidator(new My_Validate_EditorNotEmptyIf('alternate_assessments', true));
		$this->short_term_obj->addValidator(new My_Validate_EditorNotEmptyIf('alternate_assessments', '1'));
		$this->short_term_obj->addErrorMessage('Cannot be empty when Alternate Assessments is Yes.');  
		$this->short_term_obj->setRequired(false);
		$this->short_term_obj->setAllowEmpty(false);
		
		
//		$options = array(''=>'...Choose','A'=>'A - 6 Weeks', 'B'=>'B - 9 Weeks', 'C'=>'C - Semester', 'D'=>'D - Other');
		$this->schedule = new App_Form_Element_Select('schedule', array('label'=>'Schedule'));
		$this->schedule->setMultiOptions($this->valueListHelper->goalSchedule());
		$this->schedule->setDecorators ( My_Classes_Decorators::$emptyDecorators);
		$this->schedule->addErrorMessage('Schedule is empty.');  
		
		$this->schedule_other = new App_Form_Element_Text('schedule_other', array('label'=>'If other, please specify:'));
		$this->schedule_other->setDecorators ( My_Classes_Decorators::$labelDecorators);
		$this->schedule_other->setAllowEmpty(false);
		$this->schedule_other->setRequired(false);  
		$this->schedule_other->addValidator(new My_Validate_NotEmptyIf('schedule', 'D'));
//		$this->schedule_other->addValidator(new My_Validate_EmptyIf('schedule', array('A', 'B', 'C', '')));
		$this->schedule_other->addValidator(new My_Validate_EmptyIfNot('schedule', 'D'));
		$this->schedule_other->addErrorMessage('cannot be empty when Schedule is Other and must be empty when Schedule is not Other.');  
		
		$this->restore_schedule = new App_Form_Element_Checkbox('restore_schedule');
		$this->restore_schedule->setDecorators ( My_Classes_Decorators::$emptyDecorators);
		$this->restore_schedule->ignore = true;
		
		$this->restore_schedule_all = new App_Form_Element_Checkbox('restore_schedule_all');
		$this->restore_schedule_all->setDecorators ( My_Classes_Decorators::$emptyDecorators);
		$this->restore_schedule_all->ignore = true;
		
		$this->eval_procedure = new App_Form_Element_MultiCheckbox('eval_procedure', array('label'=>'Evaluation procedures'));
		$this->eval_procedure->setSeparator('<br/>');
		$this->eval_procedure->addMultiOption('A', 'Teacher Observation');
		$this->eval_procedure->addMultiOption('B', 'Written Performance');
		$this->eval_procedure->addMultiOption('C', 'Oral Performance');
		$this->eval_procedure->addMultiOption('D', 'Criterion Reference Test');
		$this->eval_procedure->addMultiOption('E', 'Parent Report');
		$this->eval_procedure->addMultiOption('F', 'Time Sample');
		$this->eval_procedure->addMultiOption('G', 'Report Cards');
		$this->eval_procedure->addMultiOption('H', 'Other');
		$this->eval_procedure->setDecorators ( My_Classes_Decorators::$elementDecorators );
		$this->eval_procedure->addFilter('StringTrim');
		$this->eval_procedure->addFilter('StripTags');
		$this->eval_procedure->addErrorMessage('Evaluation procedures is empty.');  
		
		
		$this->eval_procedure_other = new App_Form_Element_Text('eval_procedure_other', array('label'=>'Evaluation Procedure Other:'));
		$this->eval_procedure_other->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->eval_procedure_other->setRequired(false);
		$this->eval_procedure_other->setAllowEmpty(false);
		$this->eval_procedure_other->addValidator(new My_Validate_NotEmptyIfContains('eval_procedure', 'H'));
		$this->eval_procedure_other->addValidator(new My_Validate_EmptyIfParentDoesNotContain('eval_procedure', 'H'));
		$this->eval_procedure_other->addErrorMessage('cannot be empty when Evaluation Procedure contains Other and must be empty when Evaluation Procedure does not contain Other.');  
		
		$this->person_responsible = new App_Form_Element_MultiCheckbox('person_responsible', array('label'=>'Person Responsible'));
		$this->person_responsible->addMultiOption('P', 'Parent');
		$this->person_responsible->addMultiOption('CT', 'Classroom Teacher');
		$this->person_responsible->addMultiOption('RT', 'SPED Teacher');
		$this->person_responsible->addMultiOption('SLP', 'Speech-Language Pathologist');
		$this->person_responsible->addMultiOption('D/HH', 'Deaf/Hard of Hearing Specialist');
		$this->person_responsible->addMultiOption('ECS', 'Early Childhood Specialist');
		$this->person_responsible->addMultiOption('OT', 'Occupational Therapist');
		$this->person_responsible->addMultiOption('PT', 'Physical Therapist');
		$this->person_responsible->addMultiOption('AD', 'Audiologist');
		$this->person_responsible->addMultiOption('O', 'Other');
		$this->person_responsible->setDecorators ( My_Classes_Decorators::$elementDecorators );
		$this->person_responsible->addFilter('StringTrim');
		$this->person_responsible->addFilter('StripTags');
		$this->person_responsible->addErrorMessage('Person responsible is empty.');  
		
		$this->person_responsible_other = new App_Form_Element_Text('person_responsible_other', array('label'=>'Person Responsible Other:'));
		$this->person_responsible_other->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->person_responsible_other->setRequired(false);
		$this->person_responsible_other->setAllowEmpty(false);
		$this->person_responsible_other->addValidator(new My_Validate_NotEmptyIfContains('person_responsible', 'O'));
//		$this->person_responsible_other->addValidator(new My_Validate_EmptyIfParentDoesNotContain('person_responsible', 'O'));
		$this->person_responsible_other->addErrorMessage('cannot be empty when Person Responsible contains Other and must be empty when Person Responsible does not contain Other.');  
		
		$this->progress_date1 = new App_Form_Element_DatePicker('progress_date1', array('label'=>'Progress Date 1:'));
		$this->progress_date1->setAttrib ( 'size', 10 );
		$this->progress_date1->setAttrib('style', "width:80px;");
		$this->progress_date1->setInlineDecorators();
		$this->progress_date1->addErrorMessage('Progress Date 1 cannot be empty.');  
		        
		$this->progress_date2 = new App_Form_Element_DatePicker('progress_date2', array('label'=>'Progress Date 2:'));
		$this->progress_date2->setAttrib ( 'size', 10 );
		$this->progress_date2->setAttrib('style', "width:80px;");
		$this->progress_date2->setInlineDecorators();
		$this->progress_date2->addErrorMessage('Progress Date 2 cannot be empty.');  
		        
		$this->progress_date3 = new App_Form_Element_DatePicker('progress_date3', array('label'=>'Progress Date 3:'));
		$this->progress_date3->setAttrib ( 'size', 10 );
		$this->progress_date3->setAttrib('style', "width:80px;");
		$this->progress_date3->setInlineDecorators();
		$this->progress_date3->setRequired(false);
		$this->progress_date3->setAllowEmpty(false);
		$this->progress_date3->addValidator(new My_Validate_NotEmptyIfContains('schedule', array('A','B')));
		$this->progress_date3->addErrorMessage('cannot be empty when Schedule is 6 weeks or 9 weeks.');  
		
		$this->progress_date4 = new App_Form_Element_DatePicker('progress_date4', array('label'=>'Progress Date 4:'));
		$this->progress_date4->setAttrib ( 'size', 10 );
		$this->progress_date4->setAttrib('style', "width:80px;");
		$this->progress_date4->setInlineDecorators();
		$this->progress_date4->setRequired(false);
		$this->progress_date4->setAllowEmpty(false);
		$this->progress_date4->addValidator(new My_Validate_NotEmptyIfContains('schedule', array('A','B')));
		$this->progress_date4->addErrorMessage('cannot be empty when Schedule is 6 weeks or 9 weeks.');  
		
		$this->progress_date5 = new App_Form_Element_DatePicker('progress_date5', array('label'=>'Progress Date 5:'));
		$this->progress_date5->setAttrib ( 'size', 10 );
		$this->progress_date5->setAttrib('style', "width:80px;");
		$this->progress_date5->setInlineDecorators();
		$this->progress_date5->setRequired(false);
		$this->progress_date5->setAllowEmpty(false);
		$this->progress_date5->addValidator(new My_Validate_NotEmptyIfContains('schedule', 'A'));
		$this->progress_date5->addErrorMessage('cannot be empty when Schedule is 6 weeks.');  
		        
		$this->progress_date6 = new App_Form_Element_DatePicker('progress_date6', array('label'=>'Progress Date 6:'));
		$this->progress_date6->setAttrib ( 'size', 10 );
		$this->progress_date6->setAttrib('style', "width:80px;");
		$this->progress_date6->setInlineDecorators();
		$this->progress_date6->setRequired(false);
		$this->progress_date6->setAllowEmpty(false);
		$this->progress_date6->addValidator(new My_Validate_NotEmptyIfContains('schedule', 'A'));
		$this->progress_date6->addErrorMessage('cannot be empty when Schedule is 6 weeks.');  
		
//		if(1000254 == $sessUser->sessIdUser
//		 //|| 1010818 == $sessUser->sessIdUser
//		 ) {
//			$this->stmt_of_progress =  $this->buildEditor('stmt_of_progress', array('label'=>'Statement of progress:'));
//		} else {
			$this->stmt_of_progress =  $this->buildEditor('stmt_of_progress', array('label'=>'Statement of progress:'));
//		}
		
		// $this->stmt_of_progress->setDecorators ( My_Classes_Decorators::$dojoSubformEditorDecorators);
		$this->stmt_of_progress->addErrorMessage('Statement of progress is empty.');  
		
		// required field for subform
		$this->rownumber = new App_Form_Element_Text('rownumber');
		$this->rownumber->ignore = true;
		        
        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Goal");
		
		$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check and save to remove tab:'));
		$this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
		$this->remove_row->ignore = true;
		        
		return $this;
	}

	public function edit_subform_version1_header($subformName, $addNotReq) {//, $addNewRowButton=true, $addNotReq=true

		parent::edit_subform_version1_header($subformName, $addNotReq);
		$this->add_subform_row->setLabel('Add Goal');
		return $this;
	}
	
}
