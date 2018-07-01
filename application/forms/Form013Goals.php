<?php

class Form_Form013Goals extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function ifsp_goals_edit_version10() {
	    return $this->ifsp_goals_edit_version1();
	}
	
	public function ifsp_goals_edit_version9() {
		return $this->ifsp_goals_edit_version1();
	}
	public function ifsp_goals_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form013/ifsp_goals_edit_version1.phtml' ) ) ) );
		//
        // these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        //
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;
        
        $this->ifsptype = new App_Form_Element_Hidden('ifsptype');
        $this->ifsptype->ignore = true;
		
        // named displayed in validation output
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("IFSP Goals");

        $this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check to remove'));
        $this->remove_row->setDecorators ( My_Classes_Decorators::$labelDecorators);
        $this->remove_row->ignore = true;
        
        $this->id_ifsp_goals = new App_Form_Element_Hidden('id_ifsp_goals', array('label'=>''));
        
        // visible fields
        $this->goal_met = new App_Form_Element_Checkbox('goal_met', array('label'=>'Goal Met'));
        $this->goal_met->setDecorators(App_Form_DecoratorHelper::inlineElement());
        
        $this->goal_outcome = new App_Form_Element_TinyMceTextarea('goal_outcome', array('label'=>'Goal Outcome'));
        $this->goal_outcome->removeDecorator('label');
		$this->goal_outcome->addErrorMessage("Goal / Outcome must be entered.");
//		$this->goal_outcome->insideTabContainer();

        $this->goal_strengths = new App_Form_Element_TinyMceTextarea('goal_strengths', array('label'=>'Child / Family strengths and resources related to this goal:'));
        $this->goal_strengths->removeDecorator('label');
		$this->goal_strengths->addErrorMessage("Child / Family Strengths must be entered.");
//		$this->goal_strengths->insideTabContainer();

        $this->goal_what_done = new App_Form_Element_TinyMceTextarea('goal_what_done', array('label'=>'What will be done / by whom:'));
        $this->goal_what_done->removeDecorator('label');
        $this->goal_what_done->addErrorMessage("What will be done must be entered.");
//		$this->goal_what_done->insideTabContainer();

        $this->goal_prog_often = new App_Form_Element_Text('goal_prog_often', array('label'=>'Progress will be reviewed'));
        $this->goal_prog_often->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->goal_prog_often->addErrorMessage("How Often must be entered.");

        $this->goal_prog_whom = new App_Form_Element_Text('goal_prog_whom', array('label'=>'By Whom'));
        $this->goal_prog_whom->setDecorators(App_Form_DecoratorHelper::inlineElement());
	 	$this->goal_prog_whom->addErrorMessage("By Whom must be entered.");

        $this->goal_prog_measured = new App_Form_Element_Text('goal_prog_measured', array('label'=>'How Measured'));
        $this->goal_prog_measured->setDecorators(App_Form_DecoratorHelper::inlineElement());
		$this->goal_prog_measured->addErrorMessage("How Measured must be entered.");

        $this->goal_review_date = new App_Form_Element_DatePicker('goal_review_date', array('label'=>'Plan Review for this Goal'));
        $this->goal_review_date->setDecorators(App_Form_DecoratorHelper::inlineElement(false));
        $this->goal_review_date->setRequired(false);
        $this->goal_review_date->setAllowEmpty(false);
        $this->goal_review_date->addValidator(new My_Validate_NotEmptyIf('goal_met', 0));
		$this->goal_review_date->addErrorMessage("Review Date must be entered.");

        $this->goal_progress = new App_Form_Element_TinyMceTextarea('goal_progress', array('label'=>'Goal Progress'));
        $this->goal_progress->removeDecorator('label');
        $this->goal_progress->setRequired(false);
        $this->goal_progress->setAllowEmpty(false);
        $this->goal_progress->addValidator(new My_Validate_NotEmptyIfIfsptype('goal_met', 0));
		$this->goal_progress->addErrorMessage("How Much Progress must be entered.");
//		$this->goal_progress->insideTabContainer();

        $this->goal_comments = new App_Form_Element_TinyMceTextarea('goal_comments', array('label'=>'Goal Comments'));
        $this->goal_comments->removeDecorator('label');
        $this->goal_comments->setRequired(false);
        $this->goal_comments->setAllowEmpty(false);
        $this->goal_comments->addValidator(new My_Validate_NotEmptyIfIfsptype('goal_met', 0));
		$this->goal_comments->addErrorMessage("Next Steps / Comments must be entered.");
//		$this->goal_comments->insideTabContainer();
		
        
//        $multiOptions = App_Form_ValueListHelper::parentGuardian();
//        $this->pg_role = new App_Form_Element_Select('pg_role', array('label'=>'Role', 'multiOptions' => $multiOptions));
//        $this->pg_role->setDecorators(App_Form_DecoratorHelper::inlineElement());
//        $this->pg_role->removeDecorator('label');
//        
//        $this->pg_home_phone = new App_Form_Element_Text('pg_home_phone', array('label'=>'Home Phone'));
//        $this->pg_home_phone->setDecorators(App_Form_DecoratorHelper::inlineElement());
//        $this->pg_home_phone->removeDecorator('label');        
//        
//        $this->pg_work_phone = new App_Form_Element_Text('pg_work_phone', array('label'=>'Work Phone'));
//        $this->pg_work_phone->setDecorators(App_Form_DecoratorHelper::inlineElement());
//        $this->pg_work_phone->removeDecorator('label');
//        
//        $this->pg_address = new App_Form_Element_Text('pg_address', array('label'=>'Address'));
//        $this->pg_address->setDecorators(App_Form_DecoratorHelper::inlineElement());
//        $this->pg_address->removeDecorator('label');

		
        $multiOptions = array('all'=>'All', 'sinceannual'=>'Since Last Annual', 'none'=>'None');
		$this->goal_progress_show_ifsps = new App_Form_Element_Radio('goal_progress_show_ifsps', array('label'=>'Show:', 'multiOptions'=>$multiOptions));
		$this->goal_progress_show_ifsps->addValidator(new My_Validate_BooleanNotEmpty('goal_progress_show_ifsps'));
		$this->goal_progress_show_ifsps->setRequired(true);
		$this->goal_progress_show_ifsps->addErrorMessage("Goal Progress radio button must be selected.");
		$this->goal_progress_show_ifsps->getDecorator('Label')->setOption('placement', 'prepend');
//        $this->goal_progress_show_ifsps->setAttrib('onclick', 'adjustIfspHistoryDisplaygoal_review_date();');
		
        return $this;			
	}

    /**
     * Validate the form
     *
     * @param  array $data
     * @return boolean
     */
//    public function isValid($data)
//    {
//        if('Initial' == $data['ifsp_goals_1']['ifsptype']) {
//            /**
//             * For an INITIAL IFSP only, on page 5
//             * please make it so the "How Much Progress"
//             * and "Next Steps/Comments" text boxes
//             * are not required. The initial IFSP will only have new goals.
//             */
//            if($this->goal_progress) {
//                $this->goal_progress->setAllowEmpty(true);
//                $this->goal_progress->setRequired(false);
//            }
//            if($this->goal_progress) {
//                $this->goal_comments->setAllowEmpty(true);
//                $this->goal_comments->setRequired(false);
//            }
//
//        }
//        return parent::isValid($data);
//    }

}

