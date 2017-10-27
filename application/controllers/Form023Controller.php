<?php

class Form023Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 9;
	protected $startPage = 1;

    public function init() {
        $this->_redirector = $this->_helper->getHelper('Redirector');
         
    
        // form parameters
        $this->view->pageCount = 1;
        
        parent::setPrimaryKeyName('id_form_023');
        parent::setFormNumber('023');
        parent::setModelName('Model_Form023');
        parent::setFormClass('Form_Form023');
        parent::setFormTitle('IEP/IFSP Card');
        parent::setFormRev('08/08');
        
    }
    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
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
		
		$this->buildSelectMenu('service_where', 'getLocation_version1', array('dob' => $this->view->db_form_data['student_data']['age'], 'lps' => $this->lps, 'finalized_date'=>$this->view->db_form_data['finalized_date'],'status'=>$this->view->db_form_data['status']));
		
		// fill the html form with db data
		$this->form->populate($this->view->db_form_data);

		// Assign the form to the view
        $this->view->form = $this->form;
        return $this->view->form;	
    }
    protected function buildAdditional($form, $page, $modelData, $config)
    {
        
    	/*
    	 * Calculate the Age at Submission
    	 */
        
        // Mike changed this 10-22-2017 needs to pull it from the db so that is not a problem
        
        
    	$nssrsSubmissionPeriod = "10/15/2017";
    	$nssrsTransitionCutoffDate = "7/1/2017";
    	$ageArr = Model_Table_StudentTable::age_calculate(
    			getdate(strtotime($this->view->db_form_data['student_data']['dob'])),
    			getdate(strtotime($nssrsSubmissionPeriod))
    	);
    //	$this->writevar1($ageArr,'this is the age array');
    	$this->view->db_form_data['student_data']['age_at_submission'] = $ageArr['years'];
    	
    	$subFormsArray = array();
    	$this->createSelectMenu($form, 'service_where', 'getLocation_version1', array('dob' => $this->view->db_form_data['student_data']['dob'], 'lps' => isset($this->lps)?$this->lps:false));
    	return $subFormsArray;
    }
    
}