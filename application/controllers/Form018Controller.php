<?php

class Form018Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 9;
	protected $startPage = 1;
	
    public function init() {
        $this->_redirector = $this->_helper->getHelper('Redirector');

        // form parameters
        $this->view->pageCount = 2;
        
        parent::setPrimaryKeyName('id_form_018');
        parent::setFormNumber('018');
        parent::setModelName('Model_Form018');
        parent::setFormClass('Form_Form018');
        parent::setFormTitle('Summary of Performance');
        parent::setFormRev('08/08');
    }

    protected function buildSrsForm($document, $page, $raw = false)
    {
		parent::buildSrsForm($document, $page);
		
		// build subforms
		// $this->config set in parent buildSrsForm method
		$subFormBuilder = new App_Form_SubformBuilder($this->config);
		
		// student_data form used to display the student info header on the top of forms
		$zendSubForm = $subFormBuilder->buildSubform("student_data", "student_data_header");
		$this->form->addSubForm($zendSubForm, "student_data");
		
        if($page == 1) {
            $this->addSubformSection("iep_form_018_goal", "Form_Form018Goal", "Model_Table_Form018Goal");
            $this->addSubformSection("iep_form_018_agency", "Form_Form018Agency", "Model_Table_Form018Agency");
            $this->addSubformSection("iep_form_018_team_member", "Form_Form018TeamMember", "Model_Table_Form018TeamMember");
        }       
        
        if($page == 2) {
            $this->addSubformSection("iep_form_018_supp", "Form_Form018SupplementalForm", "Model_Table_Form018SupplementalForm");
        }       
        
		// fill the html form with db data
		$this->form->populate($this->view->db_form_data);

		// Assign the form to the view
        $this->view->form = $this->form;
        return $this->view->form;	
    }

    protected function createAdditional($newId)
    {
    	// ==================================================
    	// business rule - fill post secondary text in from
    	// most recent iep
    	// ==================================================
    	// get the most recent FINAL IEP for this student
        $form004Obj = new Model_Table_Form004;
        $form004 = $form004Obj->mostRecentFinalForm($this->getRequest()->student);
        if($form004) {
        
	//        Zend_Debug::dump($form004);die();
	        // build resourse to table where we'll be inserting
	        $form018GoalTable = new Model_Table_Form018Goal;
	        
	        // get the post secondary goals related to this IEP
	        $select         = $form004->select()->where("lower(status) != 'deleted' or status is null")->order('timestamp_created ASC');
	        $postSecGoals = $form004->findDependentRowset('Model_Table_Form004SecondaryGoal', 'Model_Table_Form004', $select);
	        foreach($postSecGoals as $psGoal)
	        {
	//        	Zend_Debug::dump($psGoal);die();
	        	// create a goal for form 018 for each found post secondary goal
	            $data = array(
	                'id_form_018' => $newId,
	                'id_student' => $this->getRequest()->student,
	                'post_secondary' => $psGoal['post_secondary']
	            );
	        	$form018GoalTable->inserForm($data);
	        }
	        
	        // ==================================================
	        // business rule - fill Summary of Performance
	        // text in from most recent iep
	        // ==================================================
	        $form018Table = new Model_Table_Form018;
	        $form018 = $form018Table->find($newId)->current();
	        $form018['summary_of_performance'] = $form004['present_lev_perf'];
	        $form018->save();
        }
    }

    protected function buildAdditional($form, $page, $modelData, $config)
    {
    	$subFormsArray = array();
    	if($page == 4 && isset($modelData['team_member']['count'])) {
			$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['team_member']['count'], $config, "team_member", "Form_Form002TeamMember", "Model_Table_Form002TeamMember");	
		} elseif($page == 5 && isset($modelData['form_002_suppform']['count'])) {
			$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['form_002_suppform']['count'], $config, "form_002_suppform", "Form_Form002SupplementalForm", "Model_Table_Form002SupplementalForm");
		}

        if($page == 1) {
        	if(isset($modelData['iep_form_018_goal']['count'])) {
        		$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['iep_form_018_goal']['count'], $config, "iep_form_018_goal", "Form_Form018Goal", "Model_Table_Form018Goal");
        	}
        	if(isset($modelData['iep_form_018_agency']['count'])) {
        		$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['iep_form_018_agency']['count'], $config, "iep_form_018_agency", "Form_Form018Agency", "Model_Table_Form018Agency");
        	}
        	if(isset($modelData['iep_form_018_team_member']['count'])) {
        		$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['iep_form_018_team_member']['count'], $config, "iep_form_018_team_member", "Form_Form018TeamMember", "Model_Table_Form018TeamMember");
        	}
        }       
        
        if($page == 2 && isset($modelData['iep_form_018_supp']['count'])) {
            $subFormsArray[] = $this->addSubformSectionNew($form, $modelData['iep_form_018_supp']['count'], $config, "iep_form_018_supp", "Form_Form018SupplementalForm", "Model_Table_Form018SupplementalForm");
        }       
		return $subFormsArray;
    }
}

