<?php
        
class Form003Controller extends My_Form_AbstractFormController {
 	
    protected $identity;
	protected $version = 9;
	protected $startPage = 1;
    protected $multipleDrafts = true;
	
    public function init(){
        $this->_redirector = $this->_helper->getHelper('Redirector');

        // form parameters
        $this->view->pageCount = 5;
        
        parent::setPrimaryKeyName('id_form_003');
        parent::setFormNumber('003');
        parent::setModelName('Model_Form003');
        parent::setFormClass('Form_Form003');
        parent::setFormTitle('Notification of Individualized Education Program Meeting');
        parent::setFormRev('08/08');
    }

    protected function buildSrsForm($document, $page, $raw = false)
    {
		parent::buildSrsForm($document, $page);

		$this->formStructure = array(
			'version' => 2
		);

        if ($page == 1)
        {
            /**
             * Pre-populate the address based on the student info
             */    
            $address = 
                $this->view->db_form_data['student_data']['address_street1'] .
                        '<br />';
            if (strlen($this->view->db_form_data['student_data']['address_street2']))
            $address .= $this->view->db_form_data['student_data']['address_street2'] .
                        '<br />';

            $address .=  $this->view->db_form_data['student_data']['address_city'] .
                         ', ' .
                         $this->view->db_form_data['student_data']['address_state'] .
                         ' ' . 
                         $this->view->db_form_data['student_data']['address_zip'];

            $this->view->address_pre = $address;
            /**     
             * End Pre-populate     
             */

            /**
             * Pre-populate the parents based on the student info
             */                            
            if (!empty($modelData['student_data']['parents']))
            {
            $notice_to =                 
                    $modelData['student_data']['parents'] .
                        '<br />';            
            $this->view->notice_to_pre = $notice_to;
            }
            /**     
             * End Pre-populate                  
            */
        }
		
		if($page == 3) {
			$this->formStructure['subforms'] = array(
				// subform definition - team member absences
				array(
					'name'=>'team_member_absences',
					'form' => 'Form_Form003TeamMemberAbsences',
					'model'=> 'Model_Table_Form003TeamMemberAbsences',
				),
			);
	
		}	
		if($page == 4) {
			$this->formStructure['subforms'] = array(
				// subform definition - outside_agency
				array(
					'name'=>'outside_agency',
					'form' => 'Form_Form003OutsideAgency',
					'model'=> 'Model_Table_Form003OutsideAgency',
				),
			);
	
		}	
		$this->useNewFormStructure = true;
		
		// build subforms
		// $this->config set in parent buildSrsForm method
		$subFormBuilder = new App_Form_SubformBuilder($this->config);
		
		// student_data form used to display the student info header on the top of forms
		$zendSubForm = $subFormBuilder->buildSubform("student_data", "student_data_header");
		$this->form->addSubForm($zendSubForm, "student_data");
		
		if(isset($this->useNewFormStructure) && $this->useNewFormStructure == true && isset($this->formStructure['subforms']))
		{
			// build subforms
			// $this->config set in parent buildSrsForm method
			// adds subforms to the main zend form based on counts in
			// the model data
			$subFormBuilderNew = new App_Form_SubformBuilderNew($this->formStructure);
			$subFormBuilderNew->buildSubforms($this->form, $this->view->db_form_data, $this->formStructure['subforms']);
		}
		
		if($page == 3 && $this->useNewFormStructure != true) {
			$this->addSubformSection("team_member_absences", "Form_Form003TeamMemberAbsences", "Model_Table_Form003TeamMemberAbsences");
		}
		if($page == 4 && $this->useNewFormStructure != true) {
			$this->addSubformSection("outside_agency", "Form_Form003OutsideAgency", "Model_Table_Form003OutsideAgency");
		}
		
		// fill the html form with db data
		$this->form->populate($this->view->db_form_data);
        
        
        // remove team member absences validation if not used 
        if($page == 3 && 't' != $this->view->db_form_data['on_off_checkbox']) {
            $count = $this->form->getSubform('team_member_absences')->getElement('count')->getValue();
            for($i=1; $i<=$count; $i++)
            {
                $this->clearValidation($this->form->getSubform('team_member_absences_'.$i));
            }
        }
        
		// remove outside agency validation if not used
		if($page == 4 && true != $this->view->db_form_data['on_off_checkbox_page_4']) {
			$count = $this->form->getSubform('outside_agency')->getElement('count')->getValue();
            for($i=1; $i<=$count; $i++)
            {
                $this->clearValidation($this->form->getSubform('outside_agency_'.$i));
            }
		}
		
		// Assign the form to the view
        $this->view->form = $this->form;
        return $this->view->form;	
    }
    protected function createAdditional($newId) {
		// insert parent names
		
		// auto insert one absense record
		$absenceObj = new Model_Table_Form003TeamMemberAbsences();
		$data = array(
		    'id_form_003'       => $newId,
		    'id_author'		=> $this->usersession->sessIdUser,
		    'id_author_last_mod'=>$this->usersession->sessIdUser,
		    'id_student'	=> $this->getRequest()->student,
		);
		$absenceObj->insert($data);
        
    }
    protected function buildAdditional($form, $page, $modelData, $config)
    {
    	$subFormsArray = array();
    	if ($page == 1)
        {
            /**
             * Pre-populate the address based on the student info
             */    
            $address = 
                $modelData['student_data']['address_street1'] .
                        '<br />';
            if (strlen($modelData['student_data']['address_street2']))
            $address .= $modelData['student_data']['address_street2'] .
                        '<br />';

            $address .=  $modelData['student_data']['address_city'] .
                         ', ' .
                         $modelData['student_data']['address_state'] .
                         ' ' . 
                         $modelData['student_data']['address_zip'];

            $this->view->address_pre = $address;
            /**     
             * End Pre-populate     
             */

            /**
             * Pre-populate the parents based on the student info
             */                
            $notice_to =                 
                    $modelData['student_data']['parents'] .
                        '<br />';
            $this->view->notice_to_pre = $notice_to;
            /**     
             * End Pre-populate                  
            */
        }
    	
		if($page == 3 && isset($modelData['team_member_absences']['count'])) {
			$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['team_member_absences']['count'], $config, "team_member_absences", "Form_Form003TeamMemberAbsences", "Model_Table_Form003TeamMemberAbsences");
		}
		if($page == 4 && isset($modelData['outside_agency']['count'])) {
			$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['outside_agency']['count'], $config, "outside_agency", "Form_Form003OutsideAgency", "Model_Table_Form003OutsideAgency");
		}
		
        // remove team member absences validation if not used 
        if($page == 3 && true != $modelData['on_off_checkbox']) {
            $count = $modelData['team_member_absences']['count'];
            for($i=1; $i<=$count; $i++)
            {
                $this->clearValidation($form->getSubform('team_member_absences_'.$i));
            }
        }
        
		// remove outside agency validation if not used
		if($page == 4 && true != $modelData['on_off_checkbox_page_4']) {
            $count = $modelData['outside_agency']['count'];
            for($i=1; $i<=$count; $i++)
            {
                $this->clearValidation($form->getSubform('outside_agency_'.$i));
            }
		}
		return $subFormsArray;
    }
    
}
