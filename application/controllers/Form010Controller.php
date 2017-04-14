<?php

class Form010Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 9;
	protected $startPage = 1;
	protected $multipleDrafts = true;
	
    public function init(){
        $this->_redirector = $this->_helper->getHelper('Redirector');

        // form parameters
        $this->view->pageCount = 1;
        
        parent::setPrimaryKeyName('id_form_010');
        parent::setFormNumber('010');
        parent::setModelName('Model_Form010');
        parent::setFormClass('Form_Form010');
        parent::setFormTitle('Progress Report');
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
			$this->addSubformSection("goal_progress", "Form_Form004GoalProgress", "Model_Table_Form004GoalProgress");
		}

        //$dateNotice = $this->view->db_form_data['date_notice'] == null ? date('m/d/Y', strtotime('now')) : $this->view->db_form_data['date_notice'];
        //Zend_Debug::dump($dateNotice);die();
        //$modelform   = new Model_Table_Form010();
        //$select      = $modelform->select()->where("id_student = '{$this->view->db_form_data['id_student']}' and lower(status) = 'final' and date_notice < '{$dateNotice}' ")->order('timestamp_created ASC');
        //$prevForm010 = $modelform->fetchAll($select)->current();
		
		// = ['prevFormData'];
		//Zend_Debug::dump($this->view->db_form_data);
		 
		// fill the html form with db data
		$this->form->populate($this->view->db_form_data);

		// PR REPORT HELPER
		// build list of students where user is CM or on student team
		$sessUser = new Zend_Session_Namespace('user');
		$studentObj = new Model_Table_StudentTable();
		$this->view->prList = $studentObj->prHelper($sessUser->sessIdUser);
		$this->view->prCurrentStudent = $this->view->db_form_data['id_student'];
		
		// Assign the form to the view
        $this->view->form = $this->form;
        return $this->view->form;	
    }

    public function createAdditional($id_form_010)
    {
        $modelform = new Model_Table_Form010();
        $form010 = $modelform->find($id_form_010)->current();
        
        // store the IEP key
        $form010->id_form_004 = $this->getRequest()->getParam('parent_id');
        $form010->page_status = '0';
        $form010->save();

        /**
         * add goals from IEP
         */
        $modelform = new Model_Table_Form004Goal();
        if(!is_null($form010->id_form_004)) {
            $select         = $modelform->select()->where("id_form_004 = '{$form010->id_form_004}' and lower(status) != 'deleted' ")->order(array('timestamp_created ASC', 'id_form_004_goal'));
            $form004GoalRows = $modelform->fetchAll($select);
            if(count($form004GoalRows)) {
                $form004Goals = $form004GoalRows->toArray();
            }
            $modelform = new Model_Table_Form004GoalProgress();
            foreach($form004Goals as $goal) {
                // for each goal on the iep
                // create a goal progress record
                $data = array(
                    'id_author_last_mod'  => $this->usersession->sessIdUser,
                    'id_student'          => $goal['id_student'],
                    'id_form_004_goal'    => $goal['id_form_004_goal'],
                    'id_form_010'         => $id_form_010,
                    'id_form_004'         => $form010->id_form_004,
                );
                $newId = $modelform->insert($data);
            }
        } else {
            /**
             * no goals!! What should we do? the form is already created
             */
        }

        $this->_redirector->gotoSimple('edit', 'form'.$this->formNumber, null,
                array('document' => $id_form_010, 'page' => 1)
        );
        
    }
    protected function buildAdditional($form, $page, $modelData, $config)
    {
    	$subFormsArray = array();
    	if($page == 1 && isset($modelData['goal_progress']['count'])) {
			$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['goal_progress']['count'], $config, "goal_progress", "Form_Form004GoalProgress", "Model_Table_Form004GoalProgress", false, null, null, $modelData['form_editor_type']);
		}
		
	    $sessUser = new Zend_Session_Namespace('user');
        if(true !== $sessUser->parent) {
			// PR REPORT HELPER
			// build list of students where user is CM or on student team
			$studentObj = new Model_Table_StudentTable();
			$this->view->prList = $studentObj->prHelper($sessUser->sessIdUser);
			$this->view->prCurrentStudent = $this->view->db_form_data['id_student'];
        }
		return $subFormsArray;
		
    }
    
    
}


