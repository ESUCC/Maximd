<?php
        
class Form002Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 10;
	protected $startPage = 1;
	
//    public function buildErrorTestAction(){
//		
//    	$form002Obj = new Model_Table_Form002;
//    	
//        $newId = $this->createTableRow ( $this->getRequest ()->student );
//        $current = $form002Obj->find($newId)->current();
//        
//        $current->mdt_00603e2b = "";
//		
//        
//        
//        $current->version_number = $this->version;
//		if (isset ( $this->preCreateRequirementsArray )) {
//			// these are chosen by the user in preCreateRequirements
//			foreach ( $this->preCreateRequirementsArray as $key => $value ) {
//				$current->$key = $value;
//			}
//		}
//        $current->save();
////        die();
//		$this->_redirector->gotoSimple ( 'edit', 'form' . $this->formNumber, null, array ('document' => $newId, 'page' => 3 ) );
//        
//    }

	function grepHelperAction() {
		$this->view->mode = 'edit';
		$this->view->page = 3;
		$this->view->version = 9;
		$form002Obj = new Model_Table_Form002;
        $this->view->db_form_data = $form002Obj->find($this->getRequest()->getParam('document'))->current()->toArray();
		
//        $config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->version, 'lps' => $this->view->lps );
//        $this->view->form = $this->buildZendForm ( $this->getFormClass (), $this->view->db_form_data, $this->view->version, $config, $this->view->page );

		$editor = new App_Form_Element_TestEditor ( 'tempEditor' );
		$editor->setValue ( $this->view->db_form_data['mdt_00603e2b'] );
		
		$tempForm = new Zend_Form ();
		$tempForm->addElement ( $editor );
        
        echo"<pre>";
        echo $editor->getValue();
		die();
	}
	
    public function init(){
        $this->_redirector = $this->_helper->getHelper('Redirector');

        // form parameters
        $this->view->pageCount = 5;
        
        parent::setPrimaryKeyName('id_form_002');
        parent::setFormNumber('002');
        parent::setModelName('Model_Form002');
        parent::setFormClass('Form_Form002');
        parent::setFormTitle('Multidisciplinary Evaluation Team MDT Report');
        parent::setFormRev('08/08');

    }

	function dupe() {
		// validate user can 
        $modelName = "Model_Table_Form".$this->getFormNumber();
        $formObj = new $modelName;
        $newId = $formObj->dupe($this->getRequest()->document);
		
		$sessUser = new Zend_Session_Namespace ( 'user' );
		$mdtObj = new Model_Table_Form002();
//		$mdt = $mdtObj->find($newId)->current();

		$oldMdt = $mdtObj->find($this->getRequest ()->document)->current();
		
		// convert \n to <BR> when duping from old forms
		if($oldMdt['version_number'] < 9) {
			$mdt = $mdtObj->find($newId)->current();
			
			$this->view->db_form_data = $this->buildModel ( $newId, $this->view->mode );
			
			// render all the form pages into an array in the new view 
			// they are then ouput on print_paper.phtml
			$config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->version, 'lps' => $this->view->lps );
			$pagesArr = array ();
			$this->formArr = array ();

			$tempFormPages = $this->buildZendForm ( $this->getFormClass (), $this->view->db_form_data, $this->view->version, $config );
			for($i = 1; $i <= $this->view->pageCount; $i ++) {
				// not the most efficient way to do this, but right now
				// we need the form in the view in order to be rendered by the viewscript
				$this->view->form = $tempFormPages [$i];
				$mdt = $this->convertReturnsInEditors ( $this->view->form, $mdt);
	            
				// convert subform model fields
				$x = 1;
				// setSubFormsForDuping built and set in buildZendForm()
	            foreach($this->getSubFormsForDuping($i) as $tableConfig) {
	            	$tempModelObj = new $tableConfig['model'] ();
	            	try {
		            	$modelRows = $tempModelObj->getWhere('id_form_004', $mdt->id_form_004, 'timestamp_created');
		            	foreach($modelRows as $row) {
		            		$row = $this->convertReturnsInEditors ( $this->view->form->getSubform($tableConfig['subformIndex'].'_'.$x), $row);
		            		$row->save();
		            	}
	            	} catch (Exception $e) {
	            	}
	            	unset($tempModelObj);
	            	$x++;
	            }
			}
			$mdt->save();
		}
		
		// clean the duped iep in case there is bad existing text
//		$mdt = $this->dupeRepair($mdt);
		return $newId;
	}
    function repairEditorsAction() {
		$sessUser = new Zend_Session_Namespace ( 'user' );
		if (1000254 == $sessUser->sessIdUser || 1010818 == $sessUser->sessIdUser) {
			// allow form to be unfinalized
		} else {
			throw new Exception ( 'You do not have permission to repair a form.' );
			return;
		}
		
		$id = $this->getRequest ()->document;
		
		$mdtObj = new Model_Table_Form002();
		$mdt = $mdtObj->find($id)->current();
		$mdt = $this->dupeRepair($mdt);
		$this->_redirector->gotoSimple ( 'edit', 'form' . $this->getFormNumber (), null, array ('document' => $id, 'page' => 1 ) );
	}
	function dupeRepair($mdt) {
		$filter = new App_Filter_TestWordFilter();

		$config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->version, 'lps' => $this->view->lps );
		$tempForm = new Form_Form002 ( $config );
		
		$pageNum = 1;
		// build zend form pages and run the filter on each editor
		while ( method_exists ( 'Form_Form002', $methodName = 'edit_p' . $pageNum . '_v' . $this->version ) ) {
			$formPage = $tempForm->$methodName();
			foreach ( $formPage->getElements () as $n => $e ) {
				if('App_Form_Element_TestEditor' == $e->getType ()) {
					// process editor text
					$value = $filter->filter($mdt[$n]);
					$value = preg_replace('/<meta content(.*?)\/>/ism', '', $value);
					$value = preg_replace('/<!--(.*?)-->/ism', '', $value);
			       	$mdt[$n] = $value;
				}
			}
			$pageNum ++;
		} 
		$mdt->save();
		return $mdt;
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
		
		if($page == 4) {
			$this->addSubformSection("team_member", "Form_Form002TeamMember", "Model_Table_Form002TeamMember");	
		} elseif($page == 5) {
			$this->addSubformSection("form_002_suppform", "Form_Form002SupplementalForm", "Model_Table_Form002SupplementalForm");
		}

		// fill the html form with db data
		$this->form->populate($this->view->db_form_data);

		// Assign the form to the view
        $this->view->form = $this->form;
        return $this->view->form;	
    }

    protected function createAdditional($newId)
    {
        $form002Obj = new Model_Table_Form002;
        $prevMdts = $form002Obj->fetchAll("status = 'Final' and id_student = '".$this->getRequest()->student."'", "date_mdt desc");
        $dateVerified = "";
        foreach($prevMdts as $mdt) {
        	if('' != $mdt['initial_verification_date']) {
        		$dateVerified = $mdt['initial_verification_date'];
                $currentMdt = $form002Obj->find($newId)->current();
                $currentMdt['initial_verification_date'] = $dateVerified;
                $currentMdt->save();
        		break;
        	}
        }
	
		$form002TeamMemberObj = new Model_Table_Form002TeamMember();
		$data = array(
		    'id_form_002'       => $newId,
		    'id_author'		=> $this->usersession->sessIdUser,
		    'id_author_last_mod'=> $this->usersession->sessIdUser,
		    'id_student'	=> $this->getRequest()->student,
		);
		$form002TeamMemberObj->insert($data);

		$form002TeamMemberObj = new Model_Table_Form002TeamMember();
		for ($i = 1; $i < 8; $i++) {
			$data = array(
					'id_form_002'       => $newId,
					'id_author'		=> $this->usersession->sessIdUser,
					'id_author_last_mod'=> $this->usersession->sessIdUser,
					'id_student'	=> $this->getRequest()->student,
			);
			$form002TeamMemberObj->insert($data);
		}
		
    }
//    function dupeAction() {
//        // moved to dupe function
//        // validate user can 
//        $modelName = "Model_Table_Form".$this->getFormNumber();
//        $formObj = new $modelName;
//        $newId = $formObj->dupe($this->getRequest()->document);
//        if(false !== $newId) {
//            $this->_redirector->gotoSimple(
//                'edit', 
//                'form'.$this->getFormNumber(), 
//                null,
//                array('document' => $newId, 'page' => 1)
//            );
//            return;
//        } else {
//            
//        }
//    }

    protected function buildAdditional(Zend_Form $form, $page, $modelData, $config)
    {
    	$subFormsArray = array();
    	if($page == 4 && isset($modelData['team_member']['count'])) {
			$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['team_member']['count'], $config, "team_member", "Form_Form002TeamMember", "Model_Table_Form002TeamMember");	
			
    	    /*
    	     * Remove validation on MDT radio button Yes/No's
    	     * for any row after the first.
    	     */
			for ($i=1;$i<=$modelData['team_member']['count'];$i++)
			{
			    if ($i>1)
			    {
			        $form->getSubForm('team_member_'.$i)->getElement('team_member_agree')->clearValidators();
				    $form->getSubForm('team_member_'.$i)->getElement('team_member_agree')->setAllowEmpty(true);
				    $form->getSubForm('team_member_'.$i)->getElement('team_member_agree')->setRequired(false);
			    }
			}
			
		} elseif($page == 5 && isset($modelData['form_002_suppform']['count'])) {
			$subFormsArray[] = $this->addSubformSectionNew($form, $modelData['form_002_suppform']['count'], $config, "form_002_suppform", "Form_Form002SupplementalForm", "Model_Table_Form002SupplementalForm", false, null, 'override_school_supp', $modelData['form_editor_type']);
		}
		
		// if the student is over 9, REMOVE some form options
		// option form elements should check existance before display in viewscript
		if(isset($modelData['ageAtDateOfMdt']) && $modelData['ageAtDateOfMdt'] > 9 ) {
			// disable/remove developmental delay on page 3
			if($form->disability_dd) {
				$form->removeElement('disability_dd');
			}
			// remove dd from primary_disability
			if($form->disability_primary) {
				$form->disability_primary->removeMultiOption('DD');
			}
						
		}
		return $subFormsArray;
    }
    protected function basisHelperAction()
    {
    	// get refresh code for externals
    	// changing this code will cause clients
    	// to get fresh coppies of the external files
    	$config = Zend_Registry::get ( 'config' );
    	$refreshCode = '?refreshCode=' . $config->externals->refresh;
    	
    	// style the edit page
    	$this->view->headLink ()->appendStylesheet ( '/css/site_edit.css' . $refreshCode );
    	$this->view->headLink ()->appendStylesheet ( '/css/srs_style_additions.css' . $refreshCode );
    	
    	//		$this->view->headScript ()->appendFile ( '/js/startSpellCheck.js' . $refreshCode );
    	//		$this->view->headScript ()->appendFile ( '/sproxy/sproxy.php?cmd=script&doc=wsc' . $refreshCode );
    	
    	// configure options
    	$this->view->mode = 'edit';
    	
    	// get requested page, if any
    	$this->view->page = ($this->getRequest ()->getParam ( 'page' ) > 0) ? $this->getRequest ()->getParam ( 'page' ) : $this->startPage;
    	
    	// set form title
    	$this->view->headTitle ( ' - ' . $this->getFormTitle () . ' Page ' . $this->view->page );
    	
    	// build the model
    	$this->view->db_form_data = $this->buildModel ( $this->getRequest ()->getParam ( 'document' ), $this->view->mode );
    	
    	// error reporter
    	$this->view->dojo()->requireModule('soliant.widget.ErrorReporter');
    	$this->view->formNum = $this->formNumber;
    	$this->view->userName = $this->user->user['name_first'] . ' ' . $this->user->user['name_last'];
    	$this->view->formId = $this->getRequest ()->getParam ( 'document' );
    	
    	
    	/*
    	 * redirect to view if the form is not Draft
    	* or if current user is a parent
    	*/
    	$sessUser = new Zend_Session_Namespace ( 'user' );
    	if (true == $sessUser->parent || 'Draft' != $this->view->db_form_data ['status']) {
    		$this->_redirector->gotoSimple ( 'view', 'form' . $this->formNumber, null, array ('document' => $this->getRequest ()->getParam ( 'document' ), 'page' => $this->getRequest ()->getParam ( 'page' ) ) );
    		return;
    	}
    	
    	$config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->version, 'lps' => $this->view->lps );
    	
    	// build zend form
    	$this->view->form = $this->buildZendForm ( $this->getFormClass (), $this->view->db_form_data, $this->view->version, $config, $this->view->page );
    	
    	
    	$processThroughGoogle = true;
    	if($processThroughGoogle) {
	    	$fileToUpload = APPLICATION_PATH . '/../temp/test.html';
	    	
	    	file_put_contents($fileToUpload, $this->view->form->getElement('mdt_00603e2b')->getValue());
	    	
	    	$service = Zend_Gdata_Docs::AUTH_SERVICE_NAME;
	    	$client = Zend_Gdata_ClientLogin::getHttpClient($this->googleUser, $this->googlePass, $service);
	    	$docs = new Zend_Gdata_Docs($client);
	    	$newDocumentEntry = $docs->uploadFile($fileToUpload, 'test.html', null, Zend_Gdata_Docs::DOCUMENTS_LIST_FEED_URI);
	    	list($url, $docId) = explode('%3A', $newDocumentEntry->id->text);
	    	$doc = $docs->getDocument($docId, 'html');
	    	$client->setUri($doc->getContent()->getSrc());
	    	$response = $client->request();
	    	
	    	$this->view->form->getElement('mdt_00603e2b')->setValue($response->getBody());
			//     	echo $response->getBody();
    	} 
    	
    	
    	 
    }
    protected function googleEditorsAction()
    {
    	// get refresh code for externals
    	// changing this code will cause clients
    	// to get fresh coppies of the external files
    	$config = Zend_Registry::get ( 'config' );
    	$refreshCode = '?refreshCode=' . $config->externals->refresh;
    
    	// style the edit page
    	$this->view->headLink ()->appendStylesheet ( '/css/site_edit.css' . $refreshCode );
    	$this->view->headLink ()->appendStylesheet ( '/css/srs_style_additions.css' . $refreshCode );
    
    	//		$this->view->headScript ()->appendFile ( '/js/startSpellCheck.js' . $refreshCode );
    	//		$this->view->headScript ()->appendFile ( '/sproxy/sproxy.php?cmd=script&doc=wsc' . $refreshCode );
    
    	// configure options
    	$this->view->mode = 'edit';
    
    	// get requested page, if any
    	$this->view->page = ($this->getRequest ()->getParam ( 'page' ) > 0) ? $this->getRequest ()->getParam ( 'page' ) : $this->startPage;
    
    	// set form title
    	$this->view->headTitle ( ' - ' . $this->getFormTitle () . ' Page ' . $this->view->page );
    
    	// build the model
    	$this->view->db_form_data = $this->buildModel ( $this->getRequest ()->getParam ( 'document' ), $this->view->mode );
    
    	// error reporter
    	$this->view->dojo()->requireModule('soliant.widget.ErrorReporter');
    	$this->view->formNum = $this->formNumber;
    	$this->view->userName = $this->user->user['name_first'] . ' ' . $this->user->user['name_last'];
    	$this->view->formId = $this->getRequest ()->getParam ( 'document' );
    
    
    	/*
    	 * redirect to view if the form is not Draft
    	* or if current user is a parent
    	*/
    	$sessUser = new Zend_Session_Namespace ( 'user' );
    	if (true == $sessUser->parent || 'Draft' != $this->view->db_form_data ['status']) {
    		$this->_redirector->gotoSimple ( 'view', 'form' . $this->formNumber, null, array ('document' => $this->getRequest ()->getParam ( 'document' ), 'page' => $this->getRequest ()->getParam ( 'page' ) ) );
    		return;
    	}
    
    	$config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->version, 'lps' => $this->view->lps );
    
    	// build zend form
    	$this->view->form = $this->buildZendForm ( $this->getFormClass (), $this->view->db_form_data, $this->view->version, $config, $this->view->page );
    
    	// build array of boolean page validity from the internal var
    	// built in buildZendForm()
    	$pagesValidArr = $this->arraysKeyExtract ( $this->formPagesValidArr, 'valid', 1 );
    	$this->view->pageValidationListTop = $this->view->form->formValidPagesDisplay ( $this->view->db_form_data ['status'], $pagesValidArr, 'pagesValidTop' );
    	$this->view->pageValidationList = $this->view->form->formValidPagesDisplay ( $this->view->db_form_data ['status'], $pagesValidArr, 'pagesValid' );
    
    	// build checklist of messages to help user understand issues
    	// set the validation results into the view for insertion into the validation output
    	// in application/views/srs_includes/include_form_head_024.php
    	// which currently gets included in application/view/scripts/form004/edit.phtml
    	if (! $pagesValidArr [$this->view->page]) {
    		$this->errors = $this->view->form->getErrors ();
    		$this->messages = $this->view->form->getMessages ();
    		$this->view->valid = false;
    		$this->view->validationArr = $this->view->form->formHelper->buildValidationArray ( $this->view->form, $this->view->form->getMessages () );
    	} else {
    		$this->view->valid = true;
    		$this->view->validationArr = array ();
    	}
    
    	//		if ('Form_Form002Editor' == $this->getFormClass () || 'Form_Form004Editor' == $this->getFormClass ()) {
    	//			$this->addJsPurifierToTextareaEditors ( $this->view->form );
    	//		}
    	// add Choose option for radio buttons
    	$sessUser = new Zend_Session_Namespace ( 'user' );
    	if (1000254 != $sessUser->sessIdUser) {
    		// hiding from myself but allowing for admin
    		$this->addAdminEmptyOptions ( $this->view->form, $this->view->db_form_data ['id_student'] );
    	}
    
    
    
    	// build quick links
    	$this->buildStudentQuickLinks($this->view->db_form_data ['id_student']);
    
    }

    private $googleUser = 'wadelovescheese@gmail.com';
    private $googlePass = 'wadelikesmoney';
 
}