<?php
/*
 * jsonupdateiepAction
 * 		buildModel
 * 		buildZendForm
 */
abstract class My_Form_AbstractFormController extends App_Zend_Controller_Action_Abstract {
	
	protected $primaryKeyName;
	protected $modelName;
	public $formNumber; 
	protected $formClass;
	protected $title;
	public $subFormsArray = array ();
	protected $subformHelper;
	public $additionalFormActions = array (); // array of Action objects
	
	protected $subFormsForDuping;
	
	public function setFormNumber($formNumber) {
		$this->formNumber = $formNumber;  
	}
	public function getFormNumber() {
		return $this->formNumber;
	}
	
	public function setFormClass($formClass) {
		$this->formClass = $formClass;
	}
	public function getFormClass() {
		return $this->formClass;
	}
	
	public function setPrimaryKeyName($primaryKeyName) {
		$this->primaryKeyName = $primaryKeyName;
	}
	public function getPrimaryKeyName() {
		return $this->primaryKeyName;
	}
	
	public function setModelName($modelName) {
		$this->modelName = $modelName;
	}
	public function getModelName() {
		return $this->modelName;
	}
	
	public function setFormTitle($title) {
		$this->title = $title;
		$this->view->title = $title;
	}
	public function getFormTitle() {
		return $this->title;
	}
	
	public function setFormRev($rev) {
		$this->rev = $rev;
	}
	public function getFormRev() {
		return $this->rev;
	}
	
	public function preDispatch() {

        /**
         *    run application level preDispatch to setup
         * initial email and other global config options
         */
        parent::preDispatch();  

		// ===============================================================================================================
		// archiver access
		if(isset($_COOKIE['PHPSESSID-ARCHIVE']) && 'Archive0123456789012345678901234567890Archive'==$_COOKIE['PHPSESSID-ARCHIVE']) {
			//return true;
            // load the maing and archive configs
            $config = Zend_Registry::get('config');
            $archiveConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/archive.ini', APPLICATION_ENV);

            // log in the archive user
			$auth = new App_Auth_Authenticator(); 
			$user = $auth->getCredentials($archiveConfig->siteAccess->username, $archiveConfig->siteAccess->password);
			if($user) 
			{
				App_Helper_Session::grantSiteAccess($user, false);
			}
		}
		// ===============================================================================================================
		// ===============================================================================================================
		
		
		// all controllers that extend App_Zend_Controller_Action_Abstract
		// require user to be logged in
		if (! App_Helper_Session::siteAccessGranted ()) {
			if ('production' == APPLICATION_ENV) {
				// try to get the token from iep and relogin
				return $this->_redirect ( 'https://iep.unl.edu/srs.php?area=personnel&sub=gettoken&destination=' . str_replace ( '/', '-', $_SERVER ['REQUEST_URI'] ) );
			} else {
				// redirect home
				return $this->redirectWithMessage ( '/', "You do not have site access granted." );
			}
		}

		// restore user from session
		$this->usersession = new Zend_Session_Namespace ( 'user' );
		$this->user = $this->usersession->user;
		
		// ====================================================================================================
		// confirm access through acl
		// ====================================================================================================
		// this confirms user access to this area
		$this->acl = new App_Acl ();
		if (! $this->acl->isAllowed ( $this->user->role, App_Resources::GUARDIAN_SECTION )) {
			$this->_redirect ( '/home' );
		}
		
		// load jquery
		$this->view->jQuery()->enable();
		$this->view->tinyMce = false;
		$this->view->jqueryLayout = false;
	}
	
	private function buildStudentOptionsMenu() {
		/*
		 * build student options menu based on user role
		 */
		$access = array ('Choose...' );
		
		if ('Team Member' == $this->model->formAccessObj->description) {
			if ('viewaccess' == $this->model->formAccessObj->access_level) {
				$accessArrayClassName = 'App_Auth_Role_' . str_replace ( ' ', '', $this->model->formAccessObj->description ) . 'View';
				$accessArrayObj = new $accessArrayClassName ();
			} else {
				$accessArrayClassName = 'App_Auth_Role_' . str_replace ( ' ', '', $this->model->formAccessObj->description ) . 'Edit';
				$accessArrayObj = new $accessArrayClassName ();
			}
		} else {
			$accessArrayClassName = 'App_Auth_Role_' . str_replace ( ' ', '', $this->model->formAccessObj->description );
			$accessArrayObj = new $accessArrayClassName ();
		}
		// horrible place for this to be set into the view
		$this->view->accessArrayObj = $accessArrayObj;
		
		if ($accessArrayObj->accessArray ['view'] ['access']) {
			$access [] = 'View Student';
		}
		if ($accessArrayObj->accessArray ['edit'] ['access']) {
			$access [] = 'Edit Student';
		}
		if (isset ( $accessArrayObj->accessArray ['charting'] ) && $accessArrayObj->accessArray ['charting'] ['access']) {
			$access [] = 'Student Charting';
		}
		if ($accessArrayObj->accessArray ['parents'] ['access']) {
			$access [] = 'Parent/Guardians';
		}
		if ($accessArrayObj->accessArray ['team'] ['access']) {
			$access [] = 'Student Team';
		}
		if ($accessArrayObj->accessArray ['forms'] ['access']) {
			$access [] = 'Student Forms';
		}
		if ($accessArrayObj->accessArray ['log'] ['access']) {
			$access [] = 'Student Log';
		}
		return $access;
	}
	protected function buildSrsForm($document, $page, $raw = false) {
		// I believe this function has been factored out
		
		// funciton still in use by the addrow calls
		//
		// function should be overridden to add subforms
		// see Form004Controller for an example
		//
		// mode determined by get call
		// page determined by get call  	
		

		// version - determined by db or internally
		$this->view->version = $this->version;
		
		// build the model
		// including subform data (related table rows)
		// also including student data
		$modelName = $this->getModelName ();
		$formClass = $this->getFormClass ();
		
		$modelform = new $modelName ( $this->getFormNumber (), $this->usersession );
		$this->model = $modelform;
		$this->view->db_form_data = $modelform->find ( $document, $this->view->mode, $page, null, true );
		$this->view->status = $this->view->db_form_data ['status'];
		
		// old site is for forms 1-8
		// redirect there if version is not greater or equal to 9
		if (9 > $this->view->db_form_data ['version_number']) {
			if ($this->getRequest()->getActionName() == 'print') {
				$this->_redirect('https://iep.unl.edu/form_print.php?form=form_'.$this->getFormNumber().'&document='.$document);
			} else {
				$this->_redirect('https://iep.unl.edu/srs.php?area=student&sub=form_' . $this->getFormNumber () . '&document=' . $document . '&option='.$this->getRequest()->getActionName());
			}
			die ();
		}
		$this->view->db_form_data ['form_config'] ['key_name'] = $this->getPrimaryKeyName ();
		$this->view->db_form_data ['form_config'] ['controller'] = 'form' . $this->getFormNumber ();
		$this->view->db_form_data ['form_config'] ['formAccess'] ['access_level'] = $this->model->formAccessObj->access_level;
		$this->view->db_form_data ['form_config'] ['formAccess'] ['description'] = $this->model->formAccessObj->description;
		
		// override version number if set in db row
		if (isset ( $this->view->db_form_data ['version_number'] )) {
			$this->view->version = $this->view->db_form_data ['version_number'];
		}
		
		/*
		 * lincoln public schools
		 * q: should this be tied to the form info or student info?
		 */
		if ('55' == $this->view->db_form_data ['id_county'] && '0001' == $this->view->db_form_data ['id_district']) {
			$this->view->lps = 1;
		} else {
			$this->view->lps = 0;
		}
		
		// get the html form (zend form)
		// including building subforms for each subform data row
		$this->config = array ('className' => $formClass, 'mode' => 'edit', 'page' => $page, 'version' => $this->view->version, 'lps' => $this->view->lps );
		
		$this->form = new $formClass ( $this->config );
		//The buildForm method in Form00X.php calls a method in that class
		//that creates all the form elements.
		$this->form->buildForm ( $raw );
		
		/*
		 * construct the menu of student options (view student, edit student, etc)
		 */
		$this->view->db_form_data ['student_options'] = $this->buildStudentOptionsMenu ();
		
		// this doesn't have to be a form element as it's not validated
		// but it does need to be passed in the form submission
		$this->form->page->setValue ( $page );
		
		// get refresh code for externals
		// changing this code will cause clients
		// to get fresh coppies of the external files
		$config = Zend_Registry::get ( 'config' );
		$refreshCode = '?refreshCode=' . $config->externals->refresh;

		if ('edit' == $this->view->mode) {
			$this->view->headLink ()->appendStylesheet ( '/css/site_edit.css' . $refreshCode );
			$this->view->headLink ()->appendStylesheet ( '/js/dojo_development/dojo/dojox/form/resources/FileUploader.css' . $refreshCode );
		} elseif ('view' == $this->view->mode) {
			$this->view->headLink ()->appendStylesheet ( '/css/site_view.css' . $refreshCode );
		} elseif ('print' == $this->view->mode) {
			$this->view->headLink ()->appendStylesheet ( '/css/site_print.css' . $refreshCode );
		}
		$this->view->headLink ()->appendStylesheet ( '/css/srs_style_additions.css' . $refreshCode );
		
		return $this->view->form;
	}
	
	protected function buildPageValidationArray($document, $pageCount, $raw = false) {
		/*
		 * function to validate each page of the form
		 * return boolean array of page validity
		 * e.g. (0,0,0,1,0) representing a five page form
		 * where only page 4 is valid
		 */
		$validationArray = array ();
		
		for($i = 1; $i <= $pageCount; $i ++) {
			$this->view->valid = true;
			$form = $this->buildSrsForm ( $document, $i, $raw );
			$validationArray [] = $this->validateBasic ( $this->view->db_form_data ) ? 1 : 0;
		}
		$this->mostRecentPageStatus = implode ( '', $validationArray );
		return $validationArray;
	}
	
	protected function buildPageValidationList($document, $pageCount, $raw = false, $id = 'pagesValid') {
		$page = 1;
		$validationArray = $this->buildPageValidationArray ( $document, $pageCount, $raw );
		
		//$retString = "<span style=\"white-space:nowrap;\">" . ucfirst($this->view->db_form_data['status']) . " [";
		$retString = "<span id=\"{$id}\" style=\"white-space:nowrap;\">" . ucfirst ( $this->view->db_form_data ['status'] ) . " [";
		foreach ( $validationArray as $valid ) {
			$class = $valid ? "btsb" : "btsbRed";
			$retString .= "<span class=\"" . $class . "\">" . $page ++ . "</span>";
		}
		$retString .= "]</span>";
		return $retString;
	}
	protected function addSubformSection($sectionName, $rowsSectionName, $modelName, $notRequiredCheckbox = false, $storeElementsAsArray = null, $override = null) {
		$this->subFormsArray [] = array ('subformIndex' => $sectionName, 'form' => $rowsSectionName, 'model' => $modelName, 'storeasarray' => $storeElementsAsArray, 'override' => $override );
		$this->config ['subclassName'] = $rowsSectionName;
		$subFormBuilder = new App_Form_SubformBuilder ( $this->config );
		
		//Create subform header and add it to form
		$zendSubForm = $subFormBuilder->buildSubform ( $sectionName, null, $notRequiredCheckbox );
		$this->form->addSubForm ( $zendSubForm, $sectionName );
		
		//    	Zend_Debug::dump($this->view->db_form_data);
		//Create subform rows and add them to form
		$zendSubForms = $subFormBuilder->buildSubformArray ( $sectionName, $rowsSectionName, $this->view->db_form_data [$sectionName] ['count'] );
		
		foreach ( $zendSubForms as $subformName => $subform ) {
			$this->form->addSubForm ( $subform, $subformName );
		}
	}
	//    protected function addSubformSectionForm($addToSubform, $sectionName, $rowsSectionName, $modelName, $notRequiredCheckbox = false, $storeElementsAsArray = null, $override = null)
	//    {
	//
	//    	$this->subFormsArray[] = array(
	//    		'subformIndex'=>$sectionName, 
	//    		'form'=>$rowsSectionName, 
	//    		'model'=>$modelName,
	//    		'storeasarray'=>$storeElementsAsArray,
	//    		'override'=>$override
	//    	);
	//    	$this->config[$addToSubform]['subclassName'] = $rowsSectionName;
	//    	$subFormBuilder = new App_Form_SubformBuilder($this->config);
	//
	//    	// for each subform
	//		// check to see if additional subs should be added
	//		$i = 0;
	//		echo $this->view->db_form_data[$addToSubform]['count'] . "<BR>";
	//		for ($i = 1; $i <= $this->view->db_form_data[$addToSubform]['count']; $i++) {
	//			// get each of the subform rows
	//			$tf = $this->form->getSubForm($addToSubform.'_'.$i);
	//			
	//			// if this row has related sub rows
	//			// fetch and add
	////			$subFormBuilder->buildSimpleSubform($sectionName, );
	//		}
	//    
	//    }
	protected function buildSubformSelectMenu($subformHeaderName, $elementName, $functionName, $options) {
		$count = $this->view->db_form_data [$subformHeaderName] ['count'];
		for($i = 1; $i <= $count; $i ++) {
			if (method_exists ( $this->form->getSubform ( $subformHeaderName . '_' . $i ), $functionName )) {
				$this->form->getSubform ( $subformHeaderName . '_' . $i )->getElement ( $elementName )->setMultiOptions ( $this->form->getSubform ( $subformHeaderName . '_' . $i )->$functionName ( $options ) );
			} else {
				$this->form->getSubform ( $subformHeaderName . '_' . $i )->getElement ( $elementName )->setMultiOptions ( $this->form->$functionName ( $options ) );
			}
		}
	}
	protected function createSubformSelectMenu($form, $dbData, $subformHeaderName, $elementName, $functionName, $options) {
		$count = $dbData [$subformHeaderName] ['count'];
		for($i = 1; $i <= $count; $i ++) {
			if (method_exists ( $form->getSubform ( $subformHeaderName . '_' . $i ), $functionName )) {
				$form->getSubform ( $subformHeaderName . '_' . $i )->getElement ( $elementName )->setMultiOptions ( $form->getSubform ( $subformHeaderName . '_' . $i )->$functionName ( $options ) );
			} else {
				$form->getSubform ( $subformHeaderName . '_' . $i )->getElement ( $elementName )->setMultiOptions ( $form->$functionName ( $options ) );
			}
		}
	}
	protected function buildSelectMenu($elementName, $functionName, $options) {
		// replaced in post ZendCon rewrite with createSelectMenu
		$this->form->getElement ( $elementName )->setMultiOptions ( $this->form->$functionName ( $options ) );
	}
	protected function createSelectMenu($form, $elementName, $functionName, $options) {
		$form->getElement ( $elementName )->setMultiOptions ( $form->$functionName ( $options ) );
	}
	public function allPagesValid($document, $pageCount) {
		$validationArray = $this->buildPageValidationArray ( $document, $pageCount );
		foreach ( $validationArray as $valid ) {
			if (! $valid)
				return false;
		}
		return true;
	}

	public function finalizeAction() {
		// is user access to this form checked?
		// make sure.
        $this->view->hideLeftBar = true;


        // retrieve data from the request
		$request = $this->getRequest ();
		$this->view->document = $request->document;
		$this->view->page = $request->page;
		$this->view->formNum = $this->getFormNumber ();
		
		$modelName = $this->getModelName ();
		$modelobj = new $modelName ( $this->getFormNumber (), $this->usersession );
		$formData = $modelobj->find ( $request->document );
		
		if ('Draft' != $formData ['status']) {
			$this->_helper->viewRenderer ( 'errorgoback', 'html', true );
			$this->view->message = "This form has already been finalized.";
			echo $this->view->render ( 'errorgoback.phtml' );
			return;
		}
		
		// if user hits cancel, redirect to edit
		if (isset ( $request->cancel ) && $request->cancel == "Cancel") {
			$this->_redirector->gotoSimple ( 'edit', 'form' . $this->getFormNumber (), null, array ('document' => $request->document, 'page' => $this->view->page ) );
			return;
		}
		
		// ====================================================================================================================================
		// build the model
		$this->view->db_form_data = $this->buildModel ( $this->getRequest ()->getParam ( 'document' ), $this->view->mode );
		
		if ('Draft' != $this->view->db_form_data ['status']) {
			$this->_redirector->gotoSimple ( 'view', 'form' . $this->formNumber, null, array ('document' => $this->getRequest ()->getParam ( 'document' ), 'page' => $this->getRequest ()->getParam ( 'page' ) ) );
			return;
		}
		
		$config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->version, 'lps' => $this->view->lps );
		
		// build zend form
		$this->view->form = $this->buildZendForm ( $this->getFormClass (), $this->view->db_form_data, $this->view->version, $config, $this->view->page );
		
		// build array of boolean page validity from the internal var
		// built in buildZendForm()
		$pagesValidArr = $this->arraysKeyExtract ( $this->formPagesValidArr, 'valid', 1 );
		// ====================================================================================================================================
		$this->view->form_valid = $this->finalizationAllowed ( $pagesValidArr );
		
		// update the form if confirmed
		if (isset ( $request->confirm ) && $this->view->form_valid && $request->confirm == "Confirm") {
			
			$modelobj->finalizeForm ( $request->document );
			
			if (method_exists ( $this, 'finalizeAdditional' )) {
				$this->finalizeAdditional ( $request->document );
			}
			
			$this->_redirector->gotoSimple ( 'view', 'form' . $this->getFormNumber (), null, array ('document' => $request->document, 'page' => $this->view->page ) );
			return;
		}
		$this->_helper->viewRenderer ( 'finalize', 'html', true );
		echo $this->view->render ( 'finalize.phtml' );
		return;
	
	}
	
	public function unfinalizeAction() {
		// is user access to this form checked?
		// make sure.
		$sessUser = new Zend_Session_Namespace ( 'user' );
		if (1000254 == $sessUser->sessIdUser || 1010818 == $sessUser->sessIdUser || 1013652 == $sessUser->sessIdUser) {
			// allow form to be unfinalized
		} else {
			throw new Exception ( 'You do not have permission to unfinalize a form.' );
			return;
		}
		// retrieve data from the request
		$request = $this->getRequest ();
		$post = $this->getRequest ()->getPost ();
		
		$this->view->document = $request->document;
		$this->view->page = $request->page;
		
		$modelName = $this->getModelName ();
		$modelobj = new $modelName ( $this->getFormNumber (), $this->usersession );
		$modelobj->unfinalizeForm ( $request->document );
		
		$this->_redirector->gotoSimple ( 'edit', 'form' . $this->getFormNumber (), null, array ('document' => $request->document, 'page' => $this->view->page ) );
	}
	
	public function changepageAction() {
		// TODO - make sure we dont' go past the last page
		// check session done in preDispatch
		

		// get request
		$request = $this->getRequest ();
		$post = $this->getRequest ()->getPost ();
		
		$changePageAction = $post ['changePageAction'];
		$mode = $post ['mode'] != '' ? $post ['mode'] : 'view';
		
		$checkout = $post ['zend_checkout'];
		if (0 == $checkout)
			$mode = 'view';
		
		$post ['page'] = ( int ) $post ['page'];
		switch ($changePageAction) {
			case 'next' :
				if ($post ['page'] < $this->view->pageCount) { // $this->view->pageCount set in this class's init() 
					$gotoPage = $post ['page'] + 1;
				} else {
					$gotoPage = $this->view->pageCount;
				}
				break;
			case 'prev' :
				if ($post ['page'] > 1) {
					$gotoPage = $post ['page'] - 1;
				} else {
					$gotoPage = 1;
				}
				break;
			case 'select' :
				if ($request->getParam ( 'button' ) == 3) {
					if ($request->getParam ( 'navPage3' ) >= 1 && $request->getParam ( 'navPage3' ) <= $this->view->pageCount) {
						$gotoPage = $request->getParam ( 'navPage3' );
					} else {
						$gotoPage = 1;
					}
				} elseif ($request->getParam ( 'button' ) == 2) {
					if ($post ['navPage2'] >= 1 && $post ['navPage2'] <= $this->view->pageCount) {
						$gotoPage = $post ['navPage2'];
					} else {
						$gotoPage = 1;
					}
				} else {
					if ($post ['navPage'] >= 1 && $post ['navPage'] <= $this->view->pageCount) {
						$gotoPage = $post ['navPage'];
					} else {
						$gotoPage = 1;
					}
				}
				break;
			default :
				$gotoPage = $post ['page'];
		}
		
		$formID = 'id_form_' . $this->formNumber;
		// go to the next page    	
		$this->_redirector->gotoSimple ( $mode, 'form' . $this->formNumber, null, array ('document' => $post [$formID], 'page' => $gotoPage ) );
	}
	
	public function addsubformrowAction() {
		
		// configure options
		$this->view->mode = 'edit';
		$this->view->valid = true;
		
		// retrieve data from the request
		$request = $this->getRequest ();
		
		// get incoming request data				
		$this->view->page = $request->page;
		
		// we disable the layout because we're returning ajax
		$this->_helper->layout->disableLayout ( true );
		
		$this->view->primaryKeyName = $this->getPrimaryKeyName ();
		$this->view->document = $request->id;
		
		// build the model
		$this->view->db_form_data = $this->buildModel ( $this->view->document, $this->view->mode );
		
		$config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->version, 'lps' => $this->view->lps );
		
		// build zend form
		$this->view->form = $this->buildZendForm ( $this->getFormClass (), $this->view->db_form_data, $this->view->version, $config, $this->view->page );
		
		$currentRowCount = $this->view->form->getSubform ( $request->subformname )->getElement ( 'count' )->getValue ();
		
		// insert the subform
		$this->insertForm ( $this->view->db_form_data ['subformIndexToModel'] [$request->subformname], $this->getPrimaryKeyName (), $this->view->document, $this->view->db_form_data ['id_student'], $currentRowCount );
		
		// rebuild the model and form with the new data
		$this->view->db_form_data = $this->buildModel ( $this->view->document, $this->view->mode );
		$this->view->form = $this->buildZendForm ( $this->getFormClass (), $this->view->db_form_data, $this->view->version, $config, $this->view->page );

		// validate db data
		// validate the form and build the validation arrays
		$this->validateBasic ( $this->view->db_form_data );
		
		$this->view->data = $this->view->form->formHelper->createAjaxAddRowData ( $this->view->form, $this->getPrimaryKeyName (), null, $this->view->page, $this->validationArr, array (0 => $request->subformname ), ($currentRowCount + 1) );
		
		return $this->render ( 'data' );
	}
	
	public function checkouttimeAction() {
		$this->_helper->layout->disableLayout ( true );
		// get incoming request data       
		$request = $this->getRequest ();
		$this->view->document = $request->$primaryKeyName;
		
		$this->view->data = $this->formService->getFormCheckoutTime ( $request->id );
		return $this->render ( 'data' );
	
	}
	
	public function validateBasic($data) {
		$this->view->form->populate ( $data );
		
		// override validation if Not Required is checked
		foreach ( $this->view->form->getSubforms () as $k => $sf ) {
			if ($sf->getElement ( 'override' ) && $sf->getElement ( 'override' )->getValue ()) {
				$count = $sf->getElement ( 'count' )->getValue ();
				for($i = 1; $i <= $count; $i ++) {
					$this->clearValidation ( $this->view->form->getSubform ( $k . '_' . $i ) );
				}
			}
		}
		
		if (! $this->view->form->isValid ( $this->view->form->getValues () )) {
			$this->errors = $this->view->form->getErrors ();
			$this->messages = $this->view->form->getMessages ();
			$this->view->valid = false;
			$this->validationArr = $this->view->form->formHelper->buildValidationArray ( $this->view->form, $this->messages );
		} else {
			$this->validationArr = array ();
		}
		return $this->view->valid;
	}
	
	public function clearValidation($form, $exceptions = array()) {
		// loop through form elements and change the helper
		foreach ( $form->getElements () as $n => $e ) {
			if (false === array_search ( $n, $exceptions )) {
				// disable elements that have options
				// so that the proper option is displayed
				$form->$n->clearValidators ();
				$form->$n->setAllowEmpty ( true );
				$form->$n->setRequired ( false );
			}
		}
		// loop through the subforms and pass them to 
		// this function as forms
		$subforms = $form->getSubforms ();
		foreach ( $subforms as $n => $sf ) {
			$this->clearValidation ( $sf );
		}
	}
	

	public function updateExistingDataWithNewData($existingData, $newData = null) {
		if (null == $newData) {
			return $existingData;
		} else {
			foreach ( $existingData as $k => $v ) {
				echo "checking $k\n";
				if (isset ( $existingData [$k] ) && isset ( $newData [$k] ))
					$existingData [$k] = $newData [$k];
			}
		}
		return $existingData;
	}
	
	public function convertFormToView($form) {
		// loop through form elements and change the helper
		foreach ( $form->getElements () as $n => $e ) {
			if ($e->getType () == "App_Form_Element_Hidden") {
				// do nothing to hidden elements
			} elseif ($e->getType () == "App_Form_Element_Submit") {
				// do nothing to button elements
			} elseif ($e->getType () == "App_Form_Element_Radio" || $e->getType () == "App_Form_Element_Checkbox" || $e->getType () == "App_Form_Element_MultiCheckbox" || $e->getType () == "App_Form_Element_Select") {
				$e->setAttrib ( 'readonly', 'true' );
				$e->setAttrib ( 'disable', 'disable' );
				$e->ignore = true;
		        // disable elements that have options
			    // so that the proper option is displayed
			} elseif ($e->getType () == "App_Form_Element_DatePicker") {
				// reformat date for human readability
				if (Zend_Date::isDate ( $e->getValue (), Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY )) {
					$e->setValue ( App_Form_Element_DatePicker::humanReadableDate ( $e->getValue () ) );
				}
				$e->helper = 'formNote'; // make view/text output only
			} else {
				$e->helper = 'formNote';
			}
		}
		// loop through the subforms and pass them to 
		// this function as forms
		foreach ( $form->getSubforms () as $n => $sf ) {
			$this->convertFormToView ( $sf );
		}
	}
	public function convertFormToPrint($form) {
		// if there is a print version for this page/subform
		// replace the edit viewscript with a print viewscript
		if($form->getDecorator ( 'Viewscript' )) {
			$myViewscript = $form->getDecorator ( 'Viewscript' )->getOption ( 'viewScript' );
			if (substr_count ( $myViewscript, 'edit_' ) > 0) {


                /**
                 * view script has edit in name (these are normally the edit pages and subpages (forms and subforms)
                 * also includes (edit_form_student_info_header.phtml)
                 *
                 * version suffix might be vX or versionX
                 */

                /**
                 * name of print file with matching version number (vXX) - checking for latest version print file
                 */
                $vXName = preg_replace ( array('/edit_/', '/v(\d{1,2})/'), array('print_', 'v'.$this->version), APPLICATION_PATH . "/views/scripts/" . $myViewscript );

                /**
                 * name of print file with matching version number (versionXX) - checking for latest version print file
                 */
                $versionXName = preg_replace ( array('/edit_/', '/version(\d{1,2})/'), array('print_', 'v'.$this->version), APPLICATION_PATH . "/views/scripts/" . $myViewscript );

                /**
                 * fallback to vXX from the view script
                 */
                if (file_exists ( $vXName )) {
                    $form->getDecorator ( 'Viewscript' )->setOption ( 'viewScript', str_replace(APPLICATION_PATH . "/views/scripts/", '', $vXName) );

                } elseif (file_exists ( $versionXName )) {
                    $form->getDecorator ( 'Viewscript' )->setOption ( 'viewScript', str_replace(APPLICATION_PATH . "/views/scripts/", '', $versionXName) );

                } elseif (file_exists ( $vXName )) {
                    $form->getDecorator ( 'Viewscript' )->setOption ( 'viewScript', str_replace(APPLICATION_PATH . "/views/scripts/", '', $vXName) );

                } elseif (file_exists ( $versionXName )) {
                    $form->getDecorator ( 'Viewscript' )->setOption ( 'viewScript', str_replace(APPLICATION_PATH . "/views/scripts/", '', $versionXName) );

                } elseif (file_exists ( str_replace ( 'edit_', 'print_', APPLICATION_PATH . "/views/scripts/" . $myViewscript ) )) {
                    $form->getDecorator ( 'Viewscript' )->setOption ( 'viewScript', str_replace ( 'edit_', 'print_', $myViewscript ) );
                }

			}
		}
			
		
		// loop through form elements and change the helper
		// so we don't show selects (just their display value) in the print
		foreach ( $form->getElements () as $n => $e ) {
			if ($e->getType () == "App_Form_Element_Hidden") {
				// do nothing to hidden elements
			} elseif ($e->getType () == "App_Form_Element_Radio" || $e->getType () == "App_Form_Element_Checkbox" || $e->getType () == "App_Form_Element_MultiCheckbox") {
				// disable elements that have options
				// so that the proper option is displayed
				$e->setAttrib ( 'readonly', 'true' );
				$e->setAttrib ( 'disable', 'disable' );
				$e->ignore = true;
			
			} elseif ($e->getType () == "App_Form_Element_Select") {
				// REPLCE SELECT WITH FORMNOTE AND UPDATE VALUE WITH SELECT DISPLAY VALUE
				foreach ( $e->getMultiOptions () as $k => $o ) {
					if ($e->getValue () == $k)
						$e->setValue ( $o );
				}
				$e->helper = 'formNote';
			
			} elseif ($e->getType () == "App_Form_Element_DatePicker") {
				// reformat date for human readability
				if (Zend_Date::isDate ( $e->getValue (), Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY )) {
					$e->setValue ( App_Form_Element_DatePicker::humanReadableDate ( $e->getValue () ) );
				}
				$e->helper = 'formNote'; // make view/text output only
			

			} else {
				$e->helper = 'formNote';
			}
		}
		// loop through the subforms and pass them to 
		// this function as forms
		foreach ( $form->getSubforms () as $n => $sf ) {
			$this->convertFormToPrint ( $sf );
		}
	}
	public function convertFormToSimpleEditors($form) {
		
		// loop through form elements and change the helper
		// so we don't show selects (just their display value) in the print
		foreach ( $form->getElements () as $n => $e ) {
			if ($e->getType () == "App_Form_Element_TestEditor") {
				$ne = new App_Form_Element_Textarea($n);
				$ne->setValue($e->getValue());
				$form->$n = $ne;
				
			}
		}
		// loop through the subforms and pass them to 
		// this function as forms
		foreach ( $form->getSubforms () as $n => $sf ) {
			$this->convertFormToPrint ( $sf );
		}
	}
	
	public function convertReturnsInEditors($form, &$model) {
		try {
			if(null != $form) {
				foreach ( $form->getElements () as $n => $e ) {
					//  || 'App_Form_Element_TestEditorTab' == $e->getType ()
					if('App_Form_Element_TestEditor' == $e->getType () || 'App_Form_Element_Textarea' == $e->getType ()) {
						$ne = new App_Form_Element_Textarea($n);
		                $model[$n] = preg_replace("/(\n|\r)/","<br />",$model[$n]);
					}
				}
			}
			return $model;
			
		} catch (Exception $e) {
			Zend_Debug::dump($form);
		}
	}
	
	public function buildStudentQuickLinks($id_student) {
		$sessQuickLinks = new Zend_Session_Namespace ( 'QuickLinks' );
		//$student = Model_Table_IepStudent::getStudent($id_student);        
		
		// this was caching ONE student and then not rebuilding when
		// the user went to another student.
//		if(true == $sessQuickLinks->alreadyBuilt) {
//			return true;
//		} else {
//			// first time function is called, do the build
//			$sessQuickLinks->alreadyBuilt = true;
//		}
		

		//			Primary Disability
		//			[Primary Disability from most recent MDT-002]
		// get form 002 model
		$form002Obj = new Model_Table_Form002 ();
		$prevMdt = $form002Obj->fetchRow ( "status = 'Final' and id_student = '" . $id_student . "'", "date_mdt desc" );
		
		// get form 002 form and convert db value to display value
		$form002Form = new Form_Form002 ();
		$form = $form002Form->edit_p3_v1 ();
		
		// create link to most recent MDT
		if (null !== $prevMdt) {
			if (9 <= $prevMdt ['version_number']) {
				$link = $this->view->url ( array ('controller' => 'form002', 'action' => 'view', 'document' => $prevMdt ['id_form_002'], 'page' => '1' ), null, true ); // 3rd param removes default values
				$sessQuickLinks->primary_disability = "<a href=\"" . $this->view->baseUrl () . $link . "\" target=\"_blank\">{$form->getElement('disability_primary')->getMultiOption($prevMdt['disability_primary'])}</a>";
			} else {
				$sessQuickLinks->primary_disability = "<a href=\"https://iep.unl.edu/srs.php?area=student&sub=form_002&document={$prevMdt['id_form_002']}&page=1&option=view\" target=\"_blank\">{$form->getElement('disability_primary')->getMultiOption($prevMdt['disability_primary'])}</a>";
			
			}
		} else {
			$sessQuickLinks->primary_disability = "No Finalized MDT";
		}
		//			Primary Service
		//			[Primary Service from most recent IEP-004]
		// get form 004 model
		$form004Obj = new Model_Table_Form004 ();
		$prevIep = $form004Obj->fetchRow ( "status = 'Final' and id_student = '" . $id_student . "'", "date_conference desc" );
		// create link to most recent IEP
		if (null !== $prevIep) {
			if (9 <= $prevIep ['version_number']) {
				$link = $this->view->url ( array ('controller' => 'form004', 'action' => 'view', 'document' => $prevIep ['id_form_004'], 'page' => '1' ), null, true ); // 3rd param removes default values
				$sessQuickLinks->primary_service = "<a href=\"" . $this->view->baseUrl () . $link . "\" target=\"_blank\">{$prevIep['primary_disability_drop']}</a>";
			} else {
				$sessQuickLinks->primary_service = "<a href=\"https://iep.unl.edu/srs.php?area=student&sub=form_004&document={$prevIep['id_form_004']}&page=1&option=view\" target=\"_blank\">{$prevIep['primary_disability_drop']}</a>";
			
			}
		} else {
			$sessQuickLinks->primary_service = "No Finalized IEP";
		}
		//			Related Services
		//			[List of all Related Services form most recent IEP-004]
		//			
		//			Last IEP
		//			Display the form�s date and make that date a link to most recent IEP-oo4]
		if (null !== $prevIep) {
			if (9 <= $prevIep ['version_number']) {
				$link = $this->view->url ( array ('controller' => 'form004', 'action' => 'view', 'document' => $prevIep ['id_form_004'], 'page' => '1' ), null, true ); // 3rd param removes default values
				$sessQuickLinks->prev_iep = "<a href=\"" . $this->view->baseUrl () . $link . "\" target=\"_blank\">{$prevIep['date_conference']}</a>";
			} else {
				$sessQuickLinks->prev_iep = "<a href=\"https://iep.unl.edu/srs.php?area=student&sub=form_004&document={$prevIep['id_form_004']}&page=1&option=view\" target=\"_blank\">{$prevIep['date_conference']}</a>";
			
			}
		} else {
			$sessQuickLinks->prev_iep = "No Finalized IEP";
		}
		
		//			Last Notice of IEP
		//			Display the form�s date and make that date a link to most recent Notice of IEP �003
		$form003Obj = new Model_Table_Form003 ();
		$prevForm003 = $form003Obj->fetchRow ( "status = 'Final' and id_student = '" . $id_student . "'", "date_notice desc" );
		// create link to most recent IEP
		if (null !== $prevForm003) {
			if (9 <= $prevForm003 ['version_number']) {
				$link = $this->view->url ( array ('controller' => 'form003', 'action' => 'view', 'document' => $prevForm003 ['id_form_003'], 'page' => '1' ), null, true ); // 3rd param removes default values
				$sessQuickLinks->prev_notice_iep = "<a href=\"" . $this->view->baseUrl () . $link . "\" target=\"_blank\">{$prevForm003['date_notice']}</a>";
			} else {
				$sessQuickLinks->prev_notice_iep = "<a href=\"https://iep.unl.edu/srs.php?area=student&sub=form_003&document={$prevForm003['id_form_003']}&page=1&option=view\" target=\"_blank\">{$prevForm003['date_notice']}</a>";
			
			}
		} else {
			$sessQuickLinks->prev_notice_iep = "No Finalized Notification of IEP";
		}
		
		//			Last PR
		//			Display the form�s date and make that date a link to the most recent PR �010]
		$form010Obj = new Model_Table_Form010 ();
		$prevForm010 = $form010Obj->fetchRow ( "status = 'Final' and id_student = '" . $id_student . "'", "date_notice desc" );
		// create link to most recent IEP
		if (null !== $prevForm010) {
			if (9 <= $prevForm010 ['version_number']) {
				$link = $this->view->url ( array ('controller' => 'form010', 'action' => 'view', 'document' => $prevForm010 ['id_form_010'], 'page' => '1' ), null, true ); // 3rd param removes default values
				$sessQuickLinks->prev_pr = "<a href=\"" . $this->view->baseUrl () . $link . "\" target=\"_blank\">{$prevForm010['date_notice']}</a>";
			} else {
				$sessQuickLinks->prev_pr = "<a href=\"https://iep.unl.edu/srs.php?area=student&sub=form_010&document={$prevForm010['id_form_010']}&page=1&option=view\" target=\"_blank\">{$prevForm010['date_notice']}</a>";
			
			}
		} else {
			$sessQuickLinks->prev_pr = "No Finalized Progress Report";
		}
		
		//			Last MDT
		//			Display the form�s date and make that date a link to most recent MDT-002]
		if (null !== $prevMdt) {
			if (9 <= $prevMdt ['version_number']) {
				$link = $this->view->url ( array ('controller' => 'form002', 'action' => 'view', 'document' => $prevMdt ['id_form_002'], 'page' => '1' ), null, true ); // 3rd param removes default values
				$sessQuickLinks->prev_mdt = "<a href=\"" . $this->view->baseUrl () . $link . "\" target=\"_blank\">{$prevMdt['date_mdt']}</a>";
			} else {
				$sessQuickLinks->prev_mdt = "<a href=\"https://iep.unl.edu/srs.php?area=student&sub=form_002&document={$prevMdt['id_form_002']}&page=1&option=view\" target=\"_blank\">{$prevMdt['date_mdt']}</a>";
			
			}
		} else {
			$sessQuickLinks->prev_mdt = "No Finalized MDT";
		}
		
		//			Last IFSP
		//			Display the form�s date and make that date a link to last IFSP]
		$form013Obj = new Model_Table_Form013 ();
		$prevForm013 = $form013Obj->fetchRow ( "status = 'Final' and id_student = '" . $id_student . "'", "date_notice desc" );
		// create link to most recent IEP
		if (null !== $prevForm013) {
			if (9 <= $prevForm013 ['version_number']) {
				$link = $this->view->url ( array ('controller' => 'form013', 'action' => 'view', 'document' => $prevForm013 ['id_form_013'], 'page' => '1' ), null, true ); // 3rd param removes default values
				$sessQuickLinks->prev_ifsp = "<a href=\"" . $this->view->baseUrl () . $link . "\" target=\"_blank\">{$prevForm013['meeting_date']}</a>";
			} else {
				$sessQuickLinks->prev_ifsp = "<a href=\"https://iep.unl.edu/srs.php?area=student&sub=form_013&document={$prevForm013['id_form_013']}&page=1&option=view\" target=\"_blank\">{$prevForm013['meeting_date']}</a>";
			
			}
		} else {
			$sessQuickLinks->prev_ifsp = "No Finalized IFSP";
		}
		
		//        $form002Obj = new Model_Table_Form002;
		//        $prevMdts = $form002Obj->fetchAll("status = 'Final' and id_student = '".$this->getRequest()->student."'", "date_mdt desc");
		//        $dateVerified = "";
		//        foreach($prevMdts as $mdt) {
		//            if('' != $mdt['initial_verification_date']) {
		//                $dateVerified = $mdt['initial_verification_date'];
		//                $currentMdt = $form002Obj->find($newId)->current();
		//                $currentMdt['initial_verification_date'] = $dateVerified;
		//                $currentMdt->save();
		//                break;
		//            }
		//        }
		

		//			Last Initial Evaluation
		//			Display the form's date and make that date a link to the form]
		$form001Obj = new Model_Table_Form001 ();
		$prevForm001 = $form001Obj->fetchRow ( "status = 'Final' and id_student = '" . $id_student . "'", "date_notice desc" );
		// create link to most recent IEP
		if (null !== $prevForm001) {
			if (9 <= $prevForm001 ['version_number']) {
				$link = $this->view->url ( array ('controller' => 'form001', 'action' => 'view', 'document' => $prevForm001 ['id_form_001'], 'page' => '1' ), null, true ); // 3rd param removes default values
				$sessQuickLinks->last_initial_eval = "<a href=\"" . $this->view->baseUrl () . $link . "\" target=\"_blank\">{$prevForm001['date_notice']}</a>";
			} else {
				$sessQuickLinks->last_initial_eval = "<a href=\"https://iep.unl.edu/srs.php?area=student&sub=form_001&document={$prevForm001['id_form_001']}&page=1&option=view\" target=\"_blank\">{$prevForm001['date_notice']}</a>";
			
			}
		} else {
			$sessQuickLinks->last_initial_eval = "No Finalized IFSP";
		}
		
		$form015Obj = new Model_Table_Form015 ();
		$prevForm015 = $form015Obj->fetchRow ( "status = 'Final' and id_student = '" . $id_student . "'", "date_notice desc" );
		// create link to most recent IEP
		if (null !== $prevForm015) {
			if (9 <= $prevForm015 ['version_number']) {
				$link = $this->view->url ( array ('controller' => 'form015', 'action' => 'view', 'document' => $prevForm015 ['id_form_015'], 'page' => '1' ), null, true ); // 3rd param removes default values
				$sessQuickLinks->last_initial_ifsp_eval = "<a href=\"" . $this->view->baseUrl () . $link . "\" target=\"_blank\">{$prevForm015['date_notice']}</a>";
			} else {
				$sessQuickLinks->last_initial_ifsp_eval = "<a href=\"https://iep.unl.edu/srs.php?area=student&sub=form_015&document={$prevForm015['id_form_015']}&page=1&option=view\" target=\"_blank\">{$prevForm015['date_notice']}</a>";
			
			}
		} else {
			$sessQuickLinks->last_initial_ifsp_eval = "No Finalized Initial Eval";
		}
		
		$form016Obj = new Model_Table_Form016 ();
		$prevForm016 = $form016Obj->fetchRow ( "status = 'Final' and id_student = '" . $id_student . "'", "date_notice desc" );
		// create link to most recent IEP
		if (null !== $prevForm016) {
			if (9 <= $prevForm016 ['version_number']) {
				$link = $this->view->url ( array ('controller' => 'form016', 'action' => 'view', 'document' => $prevForm016 ['id_form_016'], 'page' => '1' ), null, true ); // 3rd param removes default values
				$sessQuickLinks->last_initial_ifsp_placement = "<a href=\"" . $this->view->baseUrl () . $link . "\" target=\"_blank\">{$prevForm016['date_notice']}</a>";
			} else {
				$sessQuickLinks->last_initial_ifsp_placement = "<a href=\"https://iep.unl.edu/srs.php?area=student&sub=form_016&document={$prevForm016['id_form_016']}&page=1&option=view\" target=\"_blank\">{$prevForm016['date_notice']}</a>";
			
			}
		} else {
			$sessQuickLinks->last_initial_ifsp_placement = "No Finalized Initial Placement";
		}
		
		$form014Obj = new Model_Table_Form014 ();
		$prevForm014 = $form014Obj->fetchRow ( "status = 'Final' and id_student = '" . $id_student . "'", "date_notice desc" );
		// create link to most recent IEP
		if (null !== $prevForm014) {
			if (9 <= $prevForm014 ['version_number']) {
				$link = $this->view->url ( array ('controller' => 'form014', 'action' => 'view', 'document' => $prevForm014 ['id_form_014'], 'page' => '1' ), null, true ); // 3rd param removes default values
				$sessQuickLinks->last_notice_of_ifsp = "<a href=\"" . $this->view->baseUrl () . $link . "\" target=\"_blank\">{$prevForm014['date_notice']}</a>";
			} else {
				$sessQuickLinks->last_notice_of_ifsp = "<a href=\"https://iep.unl.edu/srs.php?area=student&sub=form_014&document={$prevForm014['id_form_014']}&page=1&option=view\" target=\"_blank\">{$prevForm014['date_notice']}</a>";
			
			}
		} else {
			$sessQuickLinks->last_notice_of_ifsp = "No Finalized Notice of Ifsp";
		}
	}
	
	public function buildStudentFormOptions() {
		// these array names should match the access_levels defined in App_FormRoles
		$editaccess = array ("View", "Edit", "Finalize", "Log", "Print" );
		$viewaccess = array ("View", "Log", "Print" );
		
		if ('Draft' != $this->view->db_form_data ['status']) {
			unset ( $editaccess [array_search ( 'Edit', $editaccess )] );
			unset ( $editaccess [array_search ( 'Finalize', $editaccess )] );
		}
		
		$accessLevel = $this->model->formAccessObj->access_level;
		$this->view->studentFormOptions = $$accessLevel;
	}
	
	function dojoeditorAction() {
		// I believe this function has been factored out
		$this->view->form->formHelper->logUsage ( __FUNCTION__ );
		
		// configure options
		$this->view->mode = 'edit';
		$this->view->valid = true;
		//		$this->dojo = 1.3; 				
		

		// retrieve data from the request
		$request = $this->getRequest ();
		
		// get requested page, if any				   
		$this->view->page = (isset ( $request->page ) && $request->page > 0) ? $request->page : $this->startPage;
		
		// build status's of all pages
		$this->view->pageValidationListTop = $this->buildPageValidationList ( $request->document, $this->view->pageCount, 'pagesValidTop' );
		$this->view->pageValidationListBottom = $this->buildPageValidationList ( $request->document, $this->view->pageCount, 'pagesValidBottom' );
		$this->view->pageValidationList = $this->buildPageValidationList ( $request->document, $this->view->pageCount, 'pagesValid' );
		
		// get the model and build the form
		// form will be in 		$this->view->form
		// model data array in 	$this->view->db_form_data
		$this->buildSrsForm ( $request->document, $this->view->page );
		
		if ('Draft' != $this->view->db_form_data ['status']) {
			$this->_redirector->gotoSimple ( 'view', 'form' . $this->formNumber, null, array ('document' => $request->document, 'page' => $request->page ) );
			return;
		}
		
		// validate the form and build the validation arrays
		$this->validateBasic ( $this->view->db_form_data );
		
		$this->buildStudentFormOptions ();
		// set the validation results into the view for insertion into the validation output 
		// in application/views/srs_includes/include_form_head_024.php
		// which currently gets included in application/view/scripts/form004/edit.phtml
		$this->view->validationArr = $this->validationArr;
	
	}
	
	function resourcepdfAction() {
		$request = $this->getRequest ();
		
		$file = appPath . '/resources/' . $request->document;
		$response = $this->_response;
		
		// Disable view and layout rendering
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout ()->disableLayout ();
		
		// Process the file
		//	    $file = 'whatever.zip';
		
		// Michael Danahy Changed this in order to prevent it from going out to the 72--206 server
		$bits = @file_get_contents ( $file );
		if (strlen ( $bits ) == 0) {
			$response->setBody ( 'Sorry, we could not find requested download file.' );
		} else {
			/* IE HACK FOR SECURE SITE DOWNLOAD */
			header ( "Cache-Control: public, must-revalidate" );
			header ( "Pragma: hack" );
			header ( "Content-Description: File Transfer" );
			header ( 'Content-disposition: attachment;
                   filename=' . basename ( $file ) );
			header ( "Content-Type: application/pdf" );
			header ( "Content-Transfer-Encoding: binary" );
			header ( 'Content-Length: ' . filesize ( $file ) );
			readfile ( $file );
			exit ();
			/* END IE HACK */
		/*
            $response->setHeader('Cache-Control', 'must-revalidate', true);
            $response->setHeader('Pragma', 'hack');
	        $response->setHeader('Content-type', 'application/pdf', true);
	        $response->setHeader('Content-Disposition', 'attachment; filename="'.$request->document.'"', true);
            $response->setBody($bits);
            */
		}
	
	}
	
	function deleteLocalImages($url) {
		unlink ( $_SERVER ['DOCUMENT_ROOT'] . '/temp/' . substr ( $url [1], strrpos ( $url [1], '/' ) + 1 ) );
	}
	
	function replaceSessionImages($url) {
		$strCookie = 'PHPSESSID=' . $_COOKIE ['PHPSESSID'] . '; path=/';
		$ch = curl_init ( $url [1] );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_COOKIE, $strCookie );
		$response = curl_exec ( $ch );
		curl_close ( $ch );
		$im = imagecreatefromstring ( $response );
		
		
		imagepng ( $im, $_SERVER ['DOCUMENT_ROOT'] . 'temp/' . substr ( $url [1], strrpos ( $url [1], '/' ) + 1 ) . '.png' );
		return '<img class="form010Chart" src="http://iepweb02.unl.edu/temp/' . substr ( $url [1], strrpos ( $url [1], '/' ) + 1 ) . '.png">';
	}
	
	public function archiveAction() {
		
		$this->limitToAdminAccess();
				
		// pdf archive config
		$sessUser = new Zend_Session_Namespace('user');
		$modelName = $this->getModelName();
		$formNumber = $this->getFormNumber ();
		$usersession = $this->usersession;
		$document = $this->getRequest()->getParam('document');

		$updateRes = false;
		// archive the form to pdf
		if(App_Application::archiveFormToPdf($modelName, $formNumber, $usersession, $document, $sessUser->legacySiteSessionId)) {
			$updateRes = App_Application::updatePdfArchiveFlag($formNumber, $document);
		}
			
		// return results as json data
		if(true == $this->getRequest()->getParam('ajaxRequest')) {
			$this->_helper->layout->disableLayout ( true );
			$this->view->setScriptPath ( APPLICATION_PATH . "/views/scripts" );
			$this->view->data = new Zend_Dojo_Data ( 'result', array(array('result'=> ($updateRes) ? 'true' : 'false'	)), 'result' );
			return $this->render ( 'data' );
		} else {
			Zend_Debug::dump($updateRes);
		}
//		App_Application::archiveFormsForTable($formNumber, $this->archiveDependencies);		
		die();
	}
	public function unarchiveAction() {
		
		
		// pdf archive config
		$sessUser = new Zend_Session_Namespace('user');
		$modelName = $this->getModelName();
		$formNumber = $this->getFormNumber ();
		$usersession = $this->usersession;
		$document = $this->getRequest()->getParam('document');

		// archive the form to pdf
		App_Application::unarchiveForm($formNumber, $document, $this->archiveDependencies);		
		die();
	}

//	public function archiveFormsForTableAction() {
//		
//		// required (passed) parameters
//		$document = $this->getRequest()->getParam('document');
//		
//		// required parameters
//		$formNumber = $this->getFormNumber(); // calculcated via controller
//		
//		// pdf archive config
//		$sessUser = new Zend_Session_Namespace('user');
//		$modelName = $this->getModelName();
//		
//		$usersession = $this->usersession;
//
//		// archive the form to pdf
//		App_Application::archiveFormToPdf($modelName, $formNumber, $usersession, $document, $sessUser->legacySiteSessionId);
//		App_Application::updatePdfArchiveFlag($formNumber, $document);		
//		App_Application::archiveFormsForTable($formNumber, $document);		
//		
//		die();
//	}
	
	function printAction() {
//        $this->printForm();
//    }
//
//    public function printForm()
//    {
        // configure options
        $this->view->mode = 'print';
        $this->view->valid = true;

        // retrieve data from the request
//		$request = $this->getRequest ();
        $document = $this->getRequest()->getParam('document');
        // =====================================================================================
        // WRITE THE WEB PAGE TO A FILE AND CREATE THE PDF
        // SETUP PRINCEXML FOR PDF CREATION
        // PRINCE XML CAN CONVERT XML/HTML DOCUMENTS TO PDF
        // =====================================================================================
        //	    $dir = "/usr/local/zend/apache2/htdocs/srs-zf/temp/"; // WRITE THE FILES TO THE TMP DIR - TMP IS IN OUR MAIN WEB FOLDER

        /*
        * Issue SRSZF-287 Mod.  New naming convention for temp files.
        */
        $shortName = 'form' . $this->formNumber . "-" . $this->usersession->user->privs [0] ['id_personnel'];
        /*
        * END SRSZF-287
        */

        $tmpfpath = TEMP_DIR . '/' . $shortName . ".html"; // NAME OF FILE WHERE WEB PAGE WILL BE WRITTEN
        $tmpPDFpath = TEMP_DIR . '/' . $shortName . ".pdf"; // NAME OF PDF THAT WILL BE CREATED BY PRINCEXML

        if (file_exists($tmpfpath))
            chmod($tmpfpath, 0755); // MAKE SURE WE CAN OVERWRITE EXISTING HTML FILE

        // =====================================================================================
        // RENDER ALL PAGES INTO A VARIABLE
        // =====================================================================================
        $view = new Zend_View ();
        // set script path to folder containing form view scripts
        $view->setScriptPath(APPLICATION_PATH . "/views/scripts/form" . $this->getFormNumber() . "/");

        // build the model
        $this->view->db_form_data = $this->buildModel($document, $this->view->mode);

        /*
           * Add summary fields if form is IEP and print is summary
           */
        if ('form004' == $this->getRequest()->getControllerName() && '1' == $this->getRequest()->getParam('summary')) {
            $district = new Model_Table_District();
            $this->view->db_form_data['district'] = $district->find($this->view->db_form_data['id_county'], $this->view->db_form_data['id_district'])->toArray();
            $this->view->db_form_data['summary_form'] = true;
            // jesse
            $this->setFormTitle('Individualized Education Plan Summary');
        } else {
            $this->view->db_form_data['summary_form'] = false;
        }

        if (method_exists($this, 'printAdditional')) {
            // add subforms defined in the parent controller
            $this->printAdditional($this->view->db_form_data);
        }

        // render all the form pages into an array in the new view
        // they are then ouput on print_paper.phtml
        $config = array('className' => $this->getFormClass(), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->version, 'lps' => $this->view->lps);
        //		$view->assign("pages", $this->buildAllFormPages($request->document, $this->view->pageCount));
        $pagesArr = array();
        $this->formArr = array();
        if (1) {
            $tempFormPages = $this->buildZendForm($this->getFormClass(), $this->view->db_form_data, $this->view->version, $config);

            for ($i = 1; $i <= $this->view->pageCount; $i++) {
                //				echo "i$i<BR>";
                // not the most efficient way to do this, but right now
                // we need the form in the view in order to be rendered by the viewscript
                $this->view->form = $tempFormPages [$i];
                $this->convertFormToPrint($this->view->form);
                $pagesArr [$i] = $this->view->form->render();
            }

        } else {
            for ($i = 1; $i <= $this->view->pageCount; $i++) {
                // not the most efficient way to do this, but right now
                // we need the form in the view in order to be rendered by the viewscript
                $this->view->form = $this->buildZendForm($this->getFormClass(), $this->view->db_form_data, $this->view->version, $config, $i);
                $this->convertFormToPrint($this->view->form);
                $pagesArr [$i] = $this->view->form->render();
            }
        }

        $view->assign("pages", $pagesArr);
        // student data is a subform used on the edit page
        // we put it in a variable in the new form here, other subforms don't need to be passed because
        // they're rendered in the buildAllFormPages function
        $view->assign("student_header", $this->view->studentInfoHeader($this->view->db_form_data, $this->getFormNumber()));

        if ($this->getFormNumber() == '026') {
        	$view->assign("student_header_026", $this->view->studentInfoHeader($this->view->db_form_data, $this->getFormNumber(), true));
        }

        // build the print header
        $img = 'http://iep.unl.edu/image_upload/' . $this->view->db_form_data ['id_county'] . $this->view->db_form_data ['id_district'] . '.jpg';
        $view->assign("header", $this->view->printHeader($img, $this->getFormTitle()));

        // build the print footer
        $view->assign("footer", $this->view->printFooter($this->view->db_form_data, $this->getFormTitle(), $this->getFormNumber(), $this->getFormRev()));

        $view->assign("title", $this->getFormTitle());

        $view->assign("db_form_data", $this->view->db_form_data);

        $viewRendering = '';
        $viewRendering = $view->render('print_paper.phtml'); // should still be utf-8 encoded at this point


        /*
        * Save out the restricted images locally so Price can read them
        */
        $viewRendering = preg_replace_callback('/<img class="form010Chart" src="(.+?)" \/>/', array(&$this, "replaceSessionImages"), $viewRendering);

        $this->view->viewRendering = $viewRendering;

        // add wrapper around the pages
        // and header/footer to tell prince what encoding this file is
        $headerAddition = '<?xml version="1.0" encoding="UTF-8"?>';
        $headerAddition .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "xhtml1/xhtml1-transitional.dtd">';
        $headerAddition .= '<html xmlns="http://www.w3.org/1999/xhtml">';
        $headerAddition .= '<head>';
        $headerAddition .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
        $headerAddition .= '</head>';
        $headerAddition .= '<body class="tundra" >';
        // footer
        $footerAddition = '</body>';
        $footerAddition .= '</html>';

        // wrap the pages in the header/footer
        $this->view->viewRendering = $headerAddition . $this->view->viewRendering . $footerAddition;
        $this->view->viewRendering = html_entity_decode($this->view->viewRendering, ENT_QUOTES, "utf-8");
        $this->view->viewRendering = preg_replace('/\s\<\s/', ' &lt; ', $this->view->viewRendering);

        /**
         * remove any remaining non-UTF8 characters from the print
         */
        $regex = <<<'END'
/
  (
    (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3
    ){1,100}                        # ...one or more times
  )
| .                                 # anything else
/x
END;
        $this->view->viewRendering = preg_replace($regex, '$1', $this->view->viewRendering);
        //Zend_Debug::dump($this->view->viewRendering);die;
//        if ($shortName == 'form002-1000254') {
//            $this->view->viewRendering = preg_replace(
//                '/<span class="s1">[\s\S]+<\/span>/',
//                '',
//                $this->view->viewRendering
//            );
//        }
        // REMOVE HTML OR PDF FILES IF THEY EXIST AND
        // WRITE THE PRINCEXML RESULT TO A FILE
        if ($this->removeFile($tmpfpath) && $this->removeFile($tmpPDFpath) && file_put_contents(
                $tmpfpath,
                $this->view->viewRendering
            )
        ) {
            // CREATE AN INSTANCE OF THE PRINCE CLASS
            // PRINCE_PATH IS THE CMD LINE PATH TO THE PRINCEXML APPLICATION
            $prince = new App_Classes_Prince_Prince (PRINCE_PATH);
            $prince->setHTML(true);
            $prince->setLog(APPLICATION_PATH . '/../public/temp/prince_log.txt');
            $prince->addStyleSheet(APPLICATION_PATH . "/../public/css/site_print.css");
            $prince->addStyleSheet(APPLICATION_PATH . "/../public/css/dojo_editor_additional_print.css");

            // FILE WILL HAVE SAME NAME AS HTML FILE WITH PDF EXTENSION
            $princeErrors = array();
            $princeResult = $prince->convert1($tmpfpath, $princeErrors);
            if ($princeResult) {
                $sessUser = new Zend_Session_Namespace ('user');
                // append user uploaded PDFs
                $pdfManager = new App_File_PdfManager();
                $pdfList = $pdfManager->getFiles($this->formNumber, $document);
                if (false !== $pdfList && count($pdfList) > 0) {
                    $pdf = Zend_Pdf::load($tmpPDFpath);
                    foreach ($pdfList as $pdfInfo) {
                        $convertTo14 = false;
                        $existingPdfPath = $pdfManager->folderPath . '/' . $pdfInfo['filename'];
                        if (file_exists($existingPdfPath)) {
                            try {
                                $userAddedPdf = Zend_Pdf::load($existingPdfPath);
                            } catch (Exception $e) {
                                // could not add pdf to form

                                //echo $existingPdfPath;

                                /*
                                             * some older srs printed pdfs were written in a bad way.
                                             * ('success' was written in the file before the pdf data)
                                             * if this file has success as it's first characters,
                                             * strip it off and replace the file
                                             */
                                $fp = @fopen($existingPdfPath, 'r') or die("Cannot open $pdf");
                                if (fgets($fp, strlen('success<BR><BR>') + 1) == 'success<BR><BR>') {
                                    $repairedFileName = $pdfManager->folderPath . '/' . basename(
                                            $pdfInfo['filename']
                                        ) . '-repaired.pdf';
                                    $newFile = @fopen($repairedFileName, 'w+') or die("Cannot open $pdf");
                                    fwrite($newFile, fread($fp, filesize($existingPdfPath)));
                                    fclose($fp);
                                    fclose($newFile);

                                    // delete the uploaded file
                                    $pdfManager = new App_File_PdfManager();
                                    $pdfList = $pdfManager->deleteFile(
                                        $this->formNumber,
                                        $document,
                                        basename($existingPdfPath)
                                    );
                                    $existingPdfPath = $repairedFileName;

                                    // Open the file to get existing content
                                } else {
                                    fclose($fp);
                                    if (isset($newFile)) {
                                        fclose($newFile);
                                    }
                                }
                            }
                            /*
                                        * second attempt - possible that file has been repaired.
                                        */
                            try {
                                $userAddedPdf = Zend_Pdf::load($existingPdfPath);
                            } catch (Exception $e) {
                                if ('Cross-reference streams are not supported yet.' == $e->getMessage()) {
                                    $convertTo14 = true;
                                } elseif ('PDF file syntax error. Offset - 0x74. Wrong W dictionary entry. Only type field of stream entries has default value and could be zero length.' == $e->getMessage(
                                    )
                                ) {
                                    $convertTo14 = true;
                                }
                                // could not add pdf to form
                                //echo $existingPdfPath;
                                if (isset($convertTo14) && $convertTo14) {
                                    $convertedTo14Path = App_Classes_Ghostscript::convertToPdf14($existingPdfPath);
                                    if (false === $convertedTo14Path) {
                                        // conversion failed, skip this file
                                        continue;
                                    }
                                    // delete the uploaded file
                                    $pdfManager = new App_File_PdfManager();
                                    $pdfList = $pdfManager->deleteFile(
                                        $this->formNumber,
                                        $document,
                                        basename($existingPdfPath)
                                    );
                                    $existingPdfPath = $convertedTo14Path;
                                } else {
                                    continue;
                                }
                            }
                            $userAddedPdf = Zend_Pdf::load($existingPdfPath);
                            try {
                                foreach ($userAddedPdf->pages as $x => $page) {
                                    $pdf->pages [] = clone $userAddedPdf->pages [$x];
                                }
                            } catch (Exception $e) {
                                // could not add pdf to form
                                echo "could not add pages to pdf";
                                die();
                            }
                        }
                    } // end add user pdfs
                    $tmpPDFpath = TEMP_DIR . '/' . $shortName . "-combined.pdf";
                    $pdf->save($tmpPDFpath);
                }

                /*
                * We're done with the tmp images so lets delete them
                */
                preg_replace_callback(
                    '/<img class="form010Chart" src="(.+?)">/',
                    array(&$this, "deleteLocalImages"),
                    $viewRendering
                );

                /*
                * Issue SRSZF-287 Mod.  Download PDF instead of printing
                * anything to screen.
                */
                header("Cache-Control: public, must-revalidate");
                header("Pragma: hack");
                header("Content-Description: File Transfer");
                header('Content-disposition: attachment; filename=' . basename($tmpPDFpath));
                header("Content-Type: application/pdf");
                //                 header("Content-Type: text/html; charset=utf-8");
             //Mike line 1478 came up as an error in line 1481 because it was split on 3 lines.  put it together and it worked on the printing
                header("Content-Transfer-Encoding: binary");
                header('Content-Length: ' . filesize($tmpPDFpath));
                readfile($tmpPDFpath);
                exit ();
                /*
                * END SRSZF-287
                */
            } else {
                throw new ErrorException ('There was an error printing the form.');
                //Comment previous line out and following line in if PrinceXML cannot generate the PDF. $princeErrors
                //will contain the errors/warnings we get back from PrinceXML.
                //Zend_Debug::dump($princeErrors);
                die ();
            }
        }

        // CURRENTLY THE VIEW WILL STILL BE REDERED
        // IT OUTPUTS $this->view->viewRendering
    }

    protected function buildAllFormPages($document, $pageCount) {
		/*
		 * function to validate each page of the form
		 * return boolean array of page validity
		 * e.g. (0,0,0,1,0) representing a five page form
		 * where only page 4 is valid
		 */
		$pagesArr = array ();
		$this->formArr = array ();
		for($i = 1; $i <= $pageCount; $i ++) {
			$this->view->valid = true;
			$this->buildSrsForm ( $document, $i );
			// OVERRIDE ELEMENTS FOR BETTER TEXT OUPUT
			// ALSO, CONVERT VIEWSCRIPTS TO PRINT VERSIONS
			$this->formArr [$i] = $this->view->form;
			$this->convertFormToPrint ( $this->view->form );
			$pagesArr [$i] = $this->view->form->render ();
		}
		return $pagesArr;
	}
	
	function removeFile($filePath) {
		if (is_file ( $filePath )) {
			//         $dsize += filesize($filePath);
			unlink ( $filePath );
		
		//         $deld++;
		}
		if (is_file ( $filePath )) {
			return false;
		} else {
			return true;
		}
	}
	
	public function deleteAction() {
		// is user access to this form checked?
		// make sure.
		// retrieve data from the request
		$request = $this->getRequest ();
		$this->view->document = $request->document;
		$this->view->page = $request->page;
		
		$modelName = $this->getModelName ();
		$modelobj = new $modelName ( $this->getFormNumber (), $this->usersession );
		
		$formData = $modelobj->find ( $request->document );
		
		/* Mike added this 4-18-2017 so that we can delete cards  anytime we want
		 * This is jira ticket SRS-42
		*/
		
		
		
		if ('Final' == $formData ['status'] && 'Admin' != $this->getAccess ( $formData ['id_student'], $this->usersession )->description) {
		
		    if(isset($formData['id_form_022'])||(isset($formData['id_form_023']))) {
		       // do nothing 
		    }
		    else {
		    $this->_helper->viewRenderer ( 'errorgoback', 'html', true );
			$this->view->message = "This form has already been finalized and cannot be deleted.";
			echo $this->view->render ( 'errorgoback.phtml' );
			return;
		    }
		  }
		
		
		
		
		if (isset ( $request->cancel ) && $request->cancel == "Cancel") {
			$this->_redirector->gotoSimple ( 'edit', 'form' . $this->getFormNumber (), null, array ('document' => $request->document, 'page' => $this->view->page ) );
			return;
		}
		
		// update the form if confirmed
		if (isset ( $request->confirm ) && $request->confirm == "Confirm") {
			# BACKUP THE FORM
			if (! $modelobj->deleteFormInsert ( $this->getPrimaryKeyName (), $request->document, $this->getFormNumber () )) {
				$errorId = $ERROR_SQL_EXEC;
				include ("error.php");
				exit ();
			} else {
				# DELETE THE FORM
				$modelobj->deleteForm ( $this->getPrimaryKeyName (), $request->document );
			
				/*
				 * Track access to logger
			     */
				Model_Logger::writeLog(
						$this->getRequest()->getParam('document'),
						4,
						$this->getFormNumber(),
						$formData['id_student'],
						$this->getRequest()->getParam('page')
				);
			}
			if ('iepweb03' == APPLICATION_ENV) {
				$this->_redirector->gotoSimple ( 'forms', 'student', null, array ('student' => '1198891' ) );
			} elseif ('jesselocal' == APPLICATION_ENV) {
				$this->_redirector->gotoSimple ( 'forms', 'student', null, array ('student' => '1198891' ) );
			} else {
				$this->_redirect ( 'https://iep.unl.edu/srs.php?area=student&sub=student&student=' . $formData ['id_student'] . '&option=forms' );
			}
			return;
		}
		
		/*
         * show the confirmation page
         */
		echo $this->renderConfirm ( 'delete', 'Are you sure you want to delete this form?', $request->document, $request->page );
		return;
	}
	public function resumeAction() {
		// is user access to this form checked?
		// make sure.
		// retrieve data from the request
		$request = $this->getRequest ();
		$this->view->document = $request->document;
		$this->view->page = $request->page;

		$modelName = $this->getModelName ();
		$modelobj = new $modelName ( $this->getFormNumber (), $this->usersession );

		$formData = $modelobj->find ( $request->document );

		if ('Suspended' != $formData ['status'] ) {
			$this->_helper->viewRenderer ( 'errorgoback', 'html', true );
			$this->view->message = "This form is not suspended and cannot be resumed.";
			echo $this->view->render ( 'errorgoback.phtml' );
			return;
		}
		if (isset ( $request->cancel ) && $request->cancel == "Cancel") {
			$this->_redirector->gotoSimple ( 'view', 'form' . $this->getFormNumber (), null, array ('document' => $request->document, 'page' => $this->view->page ) );
			return;
		}

		// update the form if confirmed
		if (isset ( $request->confirm ) && $request->confirm == "Confirm") {
			# BACKUP THE FORM
            # DELETE THE FORM
            $modelobj->resumeForm ( $request->document );

			if ('iepweb03' == APPLICATION_ENV) {
				$this->_redirector->gotoSimple ( 'forms', 'student', null, array ('student' => $formData ['id_student'] ) );
			} elseif ('jesselocal' == APPLICATION_ENV) {
				$this->_redirector->gotoSimple ( 'forms', 'student', null, array ('student' => $formData ['id_student'] ) );
			} else {
				$this->_redirect ( 'https://iep.unl.edu/srs.php?area=student&sub=student&student=' . $formData ['id_student'] . '&option=forms' );
			}
			return;
		}

		/*
         * show the confirmation page
         */
		echo $this->renderConfirm ( 'resume', 'Are you sure you want to resume draft status for this form?', $request->document, $request->page );
		return;
	}

	protected function renderConfirm($actionName, $message, $document, $page = 1) {
		$this->_helper->viewRenderer ( 'confirm', 'html', true );
		$this->view->formNum = $this->getFormNumber ();
		$this->view->formAction = $actionName;
		$this->view->message = $message;
		$this->view->document = $document;
		$this->view->page = $page;
		return $this->view->render ( 'confirm.phtml' );
	
	}
	public function createTableRow($studentId, $additionalFieldValues = array()) {
		
		// build the model
		// including subform data (related table rows)
		// also including student data
		$modelName = $this->getModelName ();
		//        $formClass = $this->getFormClass();
		$modelform = new $modelName ( $this->getFormNumber (), $this->usersession );
		
		$studentModel = new Model_Table_StudentTable();
		$studentData = $studentModel->find($studentId)->current();
		$data = array ('id_author' => $this->usersession->sessIdUser, 
				'id_author_last_mod' => $this->usersession->sessIdUser, 
				'id_student' => $studentId,
				'id_county' => $studentData->id_county,
				'id_district' => $studentData->id_district,
				'id_school' => $studentData->id_school,
		);
		
		foreach ( $additionalFieldValues as $fieldName => $value ) {
		
		}
		
		$newId = $modelform->table->insert ( $data );
		return $newId;
	}
	
	public function draftExists($studentId) {
		// build the model
		$modelName = $this->getModelName ();
		$modelform = new $modelName ( $this->getFormNumber (), $this->usersession );
		$draftForms = $modelform->table->fetchAll ( "status = 'Draft' and id_student = '$studentId'" );
		
		if (count ( $draftForms ) > 0) {
			return false;
		} else {
			return true;
		}
	}
	public function draftCount($studentId) {
		// build the model
		$modelName = $this->getModelName ();
		$modelform = new $modelName ( $this->getFormNumber (), $this->usersession );
		$draftForms = $modelform->table->fetchAll ( "status = 'Draft' and id_student = '$studentId'" );
		
		if (count ( $draftForms ) > 0) {
			return count ( $draftForms );
		} else {
			return 0;
		}
	}
	
	public function createAction() {

	    
	    
		$createOldForms = Zend_Registry::get('create-old-forms');
		$studentObj = new Model_Table_StudentTable();
		$student = $studentObj->studentInfo($this->getRequest()->student);
		
		if (!$studentObj->isDemoStudent($student[0]['id_county'],$student[0]['id_district'],$student[0]['id_school']) && in_array($this->getFormNumber(), $createOldForms['forms'])) {
			$this->_redirect('https://iep.unl.edu/srs.php?area=student&sub=form_' . $this->getFormNumber () . '&student=' . $this->getRequest()->student . '&option=new');
			exit;
		}
		
		if (isset ( $this->multipleDrafts ) && true == $this->multipleDrafts) {
			// no limit on drafts
		} elseif (false == $this->draftExists ( $this->getRequest ()->student )) {
			// draft not allowed (or only one draft allowed)
			$view = new Zend_View ();
			$view->setScriptPath ( appPath . '/views/scripts' );
			echo $view->render ( "error_draft_exists.phtml" );
			return $this->render ( 'data' );
			die ();
		}
		
		// require parents
		$garObj = new Model_Table_GuardianTable();
		$parents = $garObj->getWhere('id_student', $this->getRequest()->student, 'name_first');
//		Zend_Debug::dump();die();
		if (false === $parents || $parents->count()<=0) {
			// draft not allowed (or only one draft allowed)
			$view = new Zend_View ();
			$view->message = "Students must have at least one parent record to create forms.";
			$view->setScriptPath ( appPath . '/views/scripts' );
			echo $view->render ( "errorgoback.phtml" );
			return $this->render ( 'data' );
			die ();
		}
		
		if (method_exists ( $this, 'preCreateRequirements' )) {
			$continue = $this->preCreateRequirements ();
			if (! $continue) {
				return;
			}
		}
		
		if (method_exists ( $this, 'createOverride' )) {
			$newId = $this->createOverride ( $this->getRequest ()->student, $this->getRequest()->getParams());
		} else {
			// retrieve data from the request and create a new row
			$newId = $this->createTableRow ( $this->getRequest ()->student );
		}
		
		$modelName = "Model_Table_Form" . $this->getFormNumber ();
		$formObj = new $modelName ();
		$current = $formObj->find ( $newId )->current ();
		$current->version_number = $this->version;
	
		if (isset ( $this->preCreateRequirementsArray )) {
			// these are chosen by the user in preCreateRequirements
			foreach ( $this->preCreateRequirementsArray as $key => $value ) {
				$current->$key = $value;
			}
		}
		$current->save();
		
		if (method_exists ( $this, 'createAdditional' )) {
			$this->createAdditional ( $newId, $this->getRequest()->getParams());
		}
		
		$this->_redirector->gotoSimple ( 'edit', 'form' . $this->formNumber, null, array ('document' => $newId, 'page' => 1 ) );
		return;
	
	}
	
	function doneAction() {
		
		// validate user can 
		

	    
		$modelName = "Model_Table_Form" . $this->getFormNumber ();
		$formObj = new $modelName ();
		$formObj->checkoutComplete ( $this->getRequest ()->document );
		
		$current = $formObj->find ( $this->getRequest ()->document )->current ();
		
		if ('iepweb03' == APPLICATION_ENV) {
			$this->_redirector->gotoSimple ( 'forms', 'student', null, array ('student' => '1366090' ) );
		} else {
			$this->_redirect ( 'https://iep.unl.edu/srs.php?area=student&sub=student&student=' . $current ['id_student'] . '&option=forms' );
		}
	
	}
	
	function dupeAction() {
		// permissions
		
		if (method_exists ( $this, 'dupe' )) {
			$newId = $this->dupe ( $this->getRequest ()->document, $this->getRequest()->getParams());
		} else {
	
			// validate user can 
			$modelName = "Model_Table_Form" . $this->getFormNumber ();
			$formObj = new $modelName ();
			
			if ('full' == $this->getRequest ()->getParam ( 'dupe_type' )) {
				$newId = $formObj->dupeFull ( $this->getRequest ()->document );
			} else {
				$newId = $formObj->dupe ( $this->getRequest ()->document );
			}
		}

		
		if (false !== $newId) {
			
			if (method_exists ( $this, 'createAdditional' )) {
				//$this->createAdditional($newId);
			}
			
			$this->_redirector->gotoSimple ( 'edit', 'form' . $this->getFormNumber (), null, array ('document' => $newId, 'page' => 1 ) );
			return;
		} else {
		
		}
	}
	
	//    // rewrite
	//	protected function validateFormPages(array $formPages)
	//	{
	//		$validationArray = array();
	//		foreach($formPages as $formPage)
	//		{
	//			$validationArray[] = $this->validateFormPage($formPage);
	//		}
	//		return $validationArray;
	//	}
	//	
	//    public function validateFormPage(Zend_Form $formPage)
	//    {	
	//    	$retArr = array();
	//    	
	//    	// can this be integrated into the validation checks themselves?
	//    	// override validation if Not Required is checked
	//    	foreach($formPage->getSubforms() as $k => $sf)
	//    	{
	//    		if($sf->getElement('override') && $sf->getElement('override')->getValue())
	//    		{
	//    			$count = $sf->getElement('count')->getValue();
	//    			for($i=1; $i<=$count; $i++)
	//    			{
	//    				$this->clearValidation($formPage->getSubform($k.'_'.$i));
	//    			}
	//    		}
	//    	}
	//    	
	//    	$retArr['valid'] = $formPage->isValid($formPage->getValues());
	//    	$retArr['errors'] = $formPage->getErrors();
	//    	$retArr['messages'] = $formPage->getMessages();
	//		return $retArr;
	//    }
	//
	//	protected function formValidPagesDisplay($status, $validationArray)
	//	{
	//		$retString = "<span id=\"pagesValid\" style=\"white-space:nowrap;\">" . ucfirst($status) . " [";
	//		$page = 1;
	//		foreach($validationArray as $valid)
	//		{
	//			$class = $valid ? "btsb" : "btsbRed"; 
	//			$retString .= "<span class=\"".$class."\">" .$page++ ."</span>"; 
	//		}
	//		$retString .= "]</span>";
	//		return $retString;
	//	}
	//    public function studentFormOptions($status)
	//    {
	//    	// these array names should match the access_levels defined in App_FormRoles
	//		$editaccess = array("View", "Edit", "Finalize", "Log", "Print");
	//		$viewaccess = array("View", "Log", "Print");
	//
	//		if('Draft' != $status) 
	//        { 
	//            unset($editaccess[array_search('Edit', $editaccess)]);        
	//        	unset($editaccess[array_search('Finalize', $editaccess)]);
	//        }
	//
	//		$accessLevel = $this->model->formAccessObj->access_level;
	//		$this->view->studentFormOptions = $$accessLevel;
	//    }
	

	public function buildModel($document, $mode) {
		// build the model
		$modelName = $this->getModelName ();

		// get the db record for this form (and subforms)
		// including subform data (related table rows)
		// also including student data
		$modelform = new $modelName ( $this->getFormNumber (), $this->usersession );
		$dbData = $modelform->find ( $document, $mode, 'all', null, true );

		// Mike added this 4-24-2017 so that no 2 people can edit a form together.
		if(isset($dbData[0]['message'])) {
		    echo $this->view->partial('school/form-access-denied.phtml',array('note'=>$dbData[0]['message']));
		    	
		}
		// end of Mike Add
		
		// store the list of subforms
		// used in addSubformRow
		$dbData ['subformIndexToModel'] = $modelform->subformIndexToModel;
		
		$dbData ['form_config'] ['key_name'] = $this->getPrimaryKeyName ();
		$dbData ['form_config'] ['controller'] = 'form' . $this->getFormNumber ();
		$dbData ['form_config'] ['formAccess'] ['access_level'] = $modelform->formAccessObj->access_level;
		$dbData ['form_config'] ['formAccess'] ['description'] = $modelform->formAccessObj->description;
		
		/*
		 * construct the menu of student options (view student, edit student, etc)
		 */
		$this->view->accessArrayObj = $modelform->buildAccessObj ();
		$dbData ['student_options'] = $this->studentOptionsMenu ( $this->view->accessArrayObj );
		//$dbData['student_options'] = $this->buildStudentOptionsMenu();
		

		// get the version number
		if (isset ( $dbData ['version_number'] )) {
			$this->view->version = $dbData ['version_number'];
		}
		
		// old site is for form versions 1-8
		// redirect there if version is not greater or equal to 9
		if (9 > $dbData ['version_number']) {
			if ($this->getRequest()->getActionName() == 'print') {
				$this->_redirect('https://iep.unl.edu/form_print.php?form=form_'.$this->getFormNumber().'&document='.$this->getRequest ()->getParam ( 'document' ));
			} else {
				$this->_redirect ( 'https://iep.unl.edu/srs.php?area=student&sub=form_' . $this->getFormNumber () . '&document=' . $this->getRequest ()->getParam ( 'document' ) . '&option='.$this->getRequest()->getActionName());
			}
			die ();
		}
		
		/*
		 * lincoln public schools
		 * q: should this be tied to the form info or student info?
		 */
		if ('55' == $dbData ['id_county'] && '0001' == $dbData ['id_district']) {
			$this->view->lps = 1;
		} else {
			$this->view->lps = 0;
		}
		
		/*
		 * newform testing
		 */
		//		if('99' == $dbData['id_county'] && '9999' == $dbData['id_district']) {
		//			parent::setFormClass($this->getFormClass().'TempEditor');
		//		}
		

		$this->view->studentFormOptions = $modelform->studentFormOptions ( $dbData ['status'] );
		
		return $dbData;
	}
	protected function arraysKeyExtract($array, $key, $startingKey = 0) {
		$retArr = array ();
		foreach ( $array as $subArr ) {
			$retArr [$startingKey ++] = $subArr [$key];
		}
		return $retArr;
	}
	private function studentOptionsMenu($accessArrayObj) {
		/*
		 * build student options menu based on user role
		 * 
		 * @todo: can we put this into a student class?
		 */
		$access = array ('Choose...' );
		
		if ($accessArrayObj->accessArray ['view'] ['access']) {
			$access [] = 'View Student';
		}
		if ($accessArrayObj->accessArray ['edit'] ['access']) {
			$access [] = 'Edit Student';
		}
		if (isset ( $accessArrayObj->accessArray ['charting'] ) && $accessArrayObj->accessArray ['charting'] ['access']) {
			$access [] = 'Student Charting';
		}
		if ($accessArrayObj->accessArray ['parents'] ['access']) {
			$access [] = 'Parent/Guardians';
		}
		if ($accessArrayObj->accessArray ['team'] ['access']) {
			$access [] = 'Student Team';
		}
		if ($accessArrayObj->accessArray ['forms'] ['access']) {
			$access [] = 'Student Forms';
		}
		if ($accessArrayObj->accessArray ['log'] ['access']) {
			$access [] = 'Student Log';
		}
		return $access;
	}
	
	protected function addSubformSectionNew($form, $count, $config, $sectionName, $rowsSectionName, $modelName, $notRequiredCheckbox = false, $storeElementsAsArray = null, $override = null, $editorType=null) {
		// this class gets called from the Form00XControllers
		// set form class for main form in betaTester as well as return true for subforms
		// remove once ediors are live
		$subFormsArray = array ('subformIndex' => $sectionName, 'form' => $rowsSectionName, 'model' => $modelName, 'storeasarray' => $storeElementsAsArray, 'override' => $override );
		
		$config ['subclassName'] = $rowsSectionName;
		$subFormBuilder = new App_Form_SubformBuilder ( $config );
		
		//Create subform header and add it to form
		$zendSubForm = $subFormBuilder->buildSubform ( $sectionName, null, $notRequiredCheckbox);
		
		$form->addSubForm ( $zendSubForm, $sectionName );
		
		// -----------------------------------------------------------------------------------------------
		// force plain text editors for some districts or users
		$plainTextEditors = false;
		// force plain text because db record is flagged to use plain text
		// -----------------------------------------------------------------------------------------------
		// -----------------------------------------------------------------------------------------------
		
		//Create subform rows and add them to form
		$zendSubForms = $subFormBuilder->buildSubformArray ( $sectionName, $rowsSectionName, $count, $plainTextEditors, $editorType);
		
		foreach ( $zendSubForms as $subformName => $subform ) {
			$form->addSubForm ( $subform, $subformName );
		}
		return $subFormsArray;
	}
	
	public function buildZendForm($formClass, $data, $version, $config, $currentPage = null) {

        $appconfig = Zend_Registry::get ( 'config' );
        $refreshCode = '?refreshCode=' . $appconfig->externals->refresh;

        $this->formPagesValidArr = array ();
		$pageNum = 1;
		$formPages = array ();
		
		$plainTextEditors = false;

		while ( method_exists ( $formClass, $methodName = 'edit_p' . $pageNum . '_v' . $version ) ) {
			
			$subFormsArray = null;
			$tempForm = new $formClass ( $config );
			
			/*
			 * Cleanup for form_editor_type notices
			 */
			if (empty($data['form_editor_type']))
			    $data['form_editor_type'] = null;
			
			/*
			 * Conditionally set Google editors based on action
			 */
//			if ('google' === $data['form_editor_type'] || (isset($data['use_g_filter']) && true===$data['use_g_filter'])) {
//                Zend_Debug::dump('google editor 555666');die;
//			    $this->view->headScript()->appendFile('/js/google_editors_v2.js');
//				$tempForm->setEditorType('App_Form_Element_GoogleEditor');
//			}
            $this->view->tinyMce = true;
            $this->view->headScript()->appendFile('/js/jquery.autoresize.js'.$refreshCode);
            $this->view->headScript()->appendFile('/js/tiny_mce/jquery.tinymce.js'.$refreshCode);
            $this->view->headScript()->appendFile('/js/tinyMce.config.js'.$refreshCode);
            $tempForm->setEditorType('App_Form_Element_TestEditor');

			$formPage = $tempForm->$methodName ();
			
			// uses a config defined in abstract controller
			// adds subforms to $form
			//
			// build subforms
			$subFormBuilder = new App_Form_SubformBuilder ( $config );
			
			// student_data form used to display the student info header on the top of forms
			$zendSubForm = $subFormBuilder->buildSubform ( "student_data", "student_data_header");
			$formPage->addSubForm ( $zendSubForm, "student_data" );
			
			if (method_exists ( $this, 'buildAdditional' )) {
				// add subforms defined in the parent controller
				$subFormsArray = $this->buildAdditional ( $formPage, $pageNum, $data, $config );
			}
			$formPage->populate ( $data );
			$formPage->page->setValue ( $pageNum );
			
			// validate the form and save in class var for all form pages
			$this->formPagesValidArr [$pageNum] = $formPage->validateFormPage ( $formPage );
			
			if ($currentPage == $pageNum) {
				$retForm = $formPage;
				if (null != $subFormsArray && count ( $subFormsArray ) > 0) {
					$this->subFormsArray = $subFormsArray;
				}
			}
			// track subformsArray for duping
			if(null == $currentPage && null != $subFormsArray && count ( $subFormsArray ) > 0) {
				$this->setSubFormsForDuping($pageNum, $subFormsArray);
			}
			if (null == $currentPage) {
				$formPages [$pageNum] = $formPage;
			}
			$pageNum ++;
		}
		if (null == $currentPage) {
			return $formPages;
		}
		return $retForm;
	}
	public function editAction()
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

		/*
		 * Track access to logger
		 */
		Model_Logger::writeLog(
				$this->getRequest()->getParam('document'), 
				2,
				$this->getFormNumber(),
				$this->view->db_form_data['id_student'],
				$this->getRequest()->getParam('page')
		);
	}
	
	public function viewAction() {
		// get refresh code for externals
		// changing this code will cause clients 
		// to get fresh coppies of the external files
		$config = Zend_Registry::get ( 'config' );
		$refreshCode = '?refreshCode=' . $config->externals->refresh;
		
		// style the view page
		$this->view->headLink ()->appendStylesheet ( '/css/site_view.css' . $refreshCode );
		$this->view->headLink ()->appendStylesheet ( '/css/srs_style_additions.css' . $refreshCode );
		
		// configure options
		$this->view->mode = 'view';
		
		// get requested page, if any				   
		$this->view->page = ($this->getRequest ()->getParam ( 'page' ) > 0) ? $this->getRequest ()->getParam ( 'page' ) : $this->startPage;
		
		// set form title
		$this->view->headTitle ( ' - ' . $this->getFormTitle () . ' Page ' . $this->view->page );
		
		// build the model
		$this->view->db_form_data = $this->buildModel ( $this->getRequest ()->getParam ( 'document' ), $this->view->mode );
		
		$config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->version, 'lps' => $this->view->lps );
		
		// build zend form
		$this->view->form = $this->buildZendForm ( $this->getFormClass (), $this->view->db_form_data, $this->view->version, $config, $this->view->page );

		// validate the forms (all pages)
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
		
		// enforce view mode by changing the view helper on all elements to formNote
		$this->convertFormToView ( $this->view->form );
		
		// build quick links
		$this->buildStudentQuickLinks($this->view->db_form_data ['id_student']);

		/*
		 * Track access to logger
		 */
		Model_Logger::writeLog(
				$this->getRequest()->getParam('document'),
				1,
				$this->getFormNumber(),
				$this->view->db_form_data['id_student'],
				$this->getRequest()->getParam('page')
		);
	}
	function betaTesters($document) {
		return;
//		$sessUser = new Zend_Session_Namespace ( 'user' );
//		
//		if('Form_Form004Editor' == $this->getFormClass ()) {
//			// for subsequent checks
//			return true;
//		}
//		
//		if (('Form_Form002' == $this->getFormClass () && 1 == $document) || 
//			('Form_Form002' == $this->getFormClass () && 1167220 == $document)) {
//			$this->setFormClass ( 'Form_Form002Editor' );
//			return true;
//		}
//		if (('Form_Form004' == $this->getFormClass () && 2 == $document) || 
//			('Form_Form004' == $this->getFormClass () && 1339920 == $document) || 
//			('Form_Form004' == $this->getFormClass () && 1342698 == $document) || 
//			('Form_Form004' == $this->getFormClass () && 1358716 == $document) || 
//			('Form_Form004' == $this->getFormClass () && 1359605 == $document) || 
//			('Form_Form004' == $this->getFormClass () && 1018436 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1000254 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1012748 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018461 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018462 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018463 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018464 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018465 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018466 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018467 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018468 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018469 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018470 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018471 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018472 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1018472 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1010818 == $sessUser->sessIdUser) || 
//			('Form_Form004' == $this->getFormClass () && 1342438 == $document)) {
//			
//				$this->setFormClass ( 'Form_Form004Editor' );
////				Zend_Debug::dump('Form_Form004Editor returning true');
//				return true;
//		}
//		
//		// additional districts
//		if ('Form_Form004' == $this->getFormClass ()) {
//			
//			$formData = $this->buildModel($document, $this->view->mode );
//			if(('77' == $formData['id_county'] && '0027' == $formData['id_district']) ||
//			   ('77' == $formData['id_county'] && '0001' == $formData['id_district'])) {
//				
//			   	$this->setFormClass ( 'Form_Form004Editor' );
//			   	return true;
//			}
//		}
////		Zend_Debug::dump('Form_Form004Editor returning false');
//		return false;
	}
	
	// This method is called from JavaScript
	//    public function jsonupdateiepnewAction()
	public function jsonupdateiepAction() {
		ob_start();
		// check if this is a beta tester form
//		$this->betaTesters ( $this->getRequest ()->getPost ( $this->getPrimaryKeyName () ) );

		// configure options
		$this->view->mode = 'edit';
		$this->view->valid = true; // determinable

		// retrieve data from the request
		$request = $this->getRequest ();
		$post = $this->getRequest ()->getPost ();
		$checkout = ($this->getRequest ()->getParam ( 'zend_checkout' ) > 0) ? $this->getRequest ()->getParam ( 'zend_checkout' ) : 1;
		
		// get requested page, if any				   
		$this->view->page = ($this->getRequest ()->getParam ( 'page' ) > 0) ? $this->getRequest ()->getParam ( 'page' ) : $this->startPage;
		
		// build the model
		// getting db data so we can confirm version from db
		// also sets $this->view->lps based on county and district
		$this->view->db_form_data = $this->buildModel ( $this->getRequest ()->getPost ( $this->getPrimaryKeyName (), null ), $this->view->mode );

        // Check to see if this is a finalized form
        // If it is, we don't want to update so return an error and exit
        if ('Final' == $this->view->db_form_data['status']) {
            /**
             * Lets log this fo sho
             */
            $message = "User ID:{$this->usersession->sessIdUser} tried to save finalized "
                . "Form{$this->getRequest()->getParam('form_number')}:"
                . $this->view->db_form_data['id_form_' . $this->getRequest()->getParam('form_number')]
                . " Page:{$this->getRequest()->getParam('page')} ";
            App_Form_FormHelper::logUsageToFile($message, APPLICATION_PATH . "/../temp/voidedSaves.txt");

            echo Zend_Json::encode(array(
                'finalizedError' => true,
                'form' => 'form' . $this->getRequest()->getParam('form_number'),
                'page' => $this->getRequest()->getParam('page'),
                'document' => $this->view->db_form_data['id_form_' . $this->getRequest()->getParam('form_number')]
            ));
            exit;
        }


        $config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->db_form_data ['version_number'], 'lps' => $this->view->lps );
		
		// build zend form
		// validation is done and added to $this->formPagesValidArr 
		// when the form is built from db data
		// $this->formPagesValidArr is an array of validation results
    	// $this->formPagesValidArr[pageNumber]['valid'] = $formPage->isValid($formPage->getValues());
    	// $this->formPagesValidArr[pageNumber]['errors'] = $formPage->getErrors();
    	// $this->formPagesValidArr[pageNumber]['messages'] = $formPage->getMessages();
		$this->view->form = $this->buildZendForm ( $this->getFormClass (), $this->view->db_form_data, $this->view->db_form_data ['version_number'], $config, $this->view->page );
		// at this point the form has been built based on db data.
		
		/*
		 * build a string of zeros and ones 
		 * that represent the status of all the form pages
		 * this is still based only on DB data at this point
		 */
		$dbFormValidation = $this->view->form->formValidPagesString ( $this->view->db_form_data ['status'], $this->arraysKeyExtract ( $this->formPagesValidArr, 'valid', 1 ) );
		
		// populate from post
		// all pages should have the right
		// page_status EXCEPT the current page
		$this->view->form->populate ( $this->getRequest ()->getParams(), true);
		$valid = $this->view->form->isValid( $this->getRequest ()->getParams() );


		/*
		 * build the error message array for the CURRENT page
		 * if this has a length of 0, the page is valid
		 */
		$currentPageValidationArray = $this->view->form->formHelper->buildValidationArray ( $this->view->form, $this->view->form->getMessages () );

        /*
         * update the page_status string with the current
         * page's valid value and set into the form for saving
         */
		$dbFormValidation = substr_replace($dbFormValidation, (0==count($currentPageValidationArray))?1:0, $this->view->page-1, 1);
		$this->view->form->page_status->setValue($dbFormValidation);

        /**
         * SAVE THE FORM
         */
        $rowsToRebuild = $this->view->form->formHelper->persistData ( $this->getFormNumber (), $this->view->form, $this->view->mode, $this->view->page, $this->view->db_form_data ['version_number'], $checkout, $this->subFormsArray );
		if (method_exists ( $this, 'saveAdditional' )) {
			$this->saveAdditional ( $post );
		}
		// reset the validation so overrides and insert validation
		// is properly displayed in the returned validation array
		$this->view->form->clearValidation ();
		
		// build status's of all pages
		// validate the forms (all pages)
		$pagesValidArr = $this->arraysKeyExtract ( $this->formPagesValidArr, 'valid', 1 );
		
		// build the model
		// getting db data so we can confirm version from db
		// also sets $this->view->lps based on county and district
		$this->view->db_form_data = $this->buildModel ( $this->getRequest ()->getPost ( $this->getPrimaryKeyName (), null ), $this->view->mode );
		
		$config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->db_form_data ['version_number'], 'lps' => $this->view->lps );
		
		// build zend form
		$this->view->form = $this->buildZendForm ( $this->getFormClass (), $this->view->db_form_data, $this->view->db_form_data ['version_number'], $config, $this->view->page );

		// validate the forms (all pages)
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
        
        // add pageValidationList to the returned form
		$this->view->form->pageValidationList = new App_Form_Element_Text ( 'pageValidationList' );
		$this->view->form->pageValidationList->setValue ( $this->view->pageValidationList );
		
		// we disable the layout because we're returning ajax
		$this->_helper->layout->disableLayout ( true );

		// put the return rows (if any) and db data into a dojo json object
		$this->view->data = $this->view->form->formHelper->createAjaxData ( 
			$this->view->form,
            $this->getPrimaryKeyName (),
            null,
			$this->view->page,
            $this->view->validationArr,
            $rowsToRebuild
        );

        if (method_exists ( $this, 'postSaveAdditional' )) {
            $this->view->data = $this->postSaveAdditional ( $post, $this->view->data );
        }

        // log the save results
		require_once APPLICATION_PATH.'/../library/App/Classes/Browser.php';
		$browser = new Browser();
		$databaseAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
        $errorLog = new Model_Table_SaveErrorLog();
	    $errorLog->writeErrorToLog(
	        ob_get_clean(),
	        $this->usersession,
	        $request,
	        $this->getFormNumber(),
	        $this->getPrimaryKeyName (),
	        $databaseAdapter,
	        $browser
	    );
			
	    /*
	     * Track access to logger
	    */
	    Model_Logger::writeLog(
	    		$this->view->db_form_data['id_form_'.$this->getRequest()->getParam('form_number')],
	    		3,
	    		$this->getFormNumber(),
	    		$this->view->db_form_data['id_student'],
	    		$this->getRequest()->getParam('page')
	    );

		return $this->render ( 'data' );
	}
	
	function insertForm($modelName, $primaryKeyName, $parentKey, $idStudent, $currentRowCount = 0) {
		$databaseSubformObj = new $modelName ();
		$newKey = $databaseSubformObj->inserForm ( array ($primaryKeyName => $parentKey, 'id_author' => '000', 'id_author_last_mod' => '000', 'id_student' => $idStudent ) );
		$d = $databaseSubformObj->getForm ( $newKey );
		// add the row number to the form
		$d ['rownumber'] = $currentRowCount + 1;
		return $d;
	}
	public function finalizationAllowed($validationArr) {
		foreach ( $validationArr as $valid ) {
			if (! $valid)
				return false;
		}
		return true;
	}
	
	public function utf8write($fileName, $contents) {
		try {
			$fh = fopen ( $fileName, "w" );
			fwrite ( $fh, utf8_encode ( $contents ) );
			fclose ( $fh );
			return true;
		} catch ( exception $e ) {
			return false;
		}
	}
	
	public function addJsPurifierToTextareaEditors($form) {
		$revertLinkFlag = 0;
		$sessUser = new Zend_Session_Namespace ( 'user' );
		if (1000254 == $sessUser->sessIdUser || 1010818 == $sessUser->sessIdUser) {
			$revertLinkFlag = 0;
		}
		// loop through form elements and change the helper
		// so we don't show selects (just their display value) in the print
		foreach ( $form->getElements () as $n => $e ) {
			if ($e->getType () == "App_Form_Element_Editor") {
				$e->addJsPurify ( $revertLinkFlag );
			}
		}
		// loop through the subforms and pass them to 
		// this function as forms
		foreach ( $form->getSubforms () as $n => $sf ) {
			$this->addJsPurifierToTextareaEditors ( $sf );
		}
	}
	
	/**
	 * 
	 * Adds an empty form option of "...Choose" so 
	 * SRS admins can empty out a form choice.  Will
	 * only show for SRS Admins.
	 * 
	 * @param class $form
	 * @param int $id_student
	 */
	public function addAdminEmptyOptions($form, $id_student) {
		$revertLinkFlag = 0;
		$sessUser = new Zend_Session_Namespace ( 'user' );
		if ('Admin' != $this->getAccess ( $id_student, $this->usersession )->description) {
			return false;
		}
		// loop through form elements and change the helper
		// so we don't show selects (just their display value) in the print
		foreach ( $form->getElements () as $n => $e ) {
			if ($e->getType () == "App_Form_Element_Radio") {
				$e->addMultiOption ( '', '...Choose' );
			}
		}
		// loop through the subforms and pass them to 
		// this function as forms
		foreach ( $form->getSubforms () as $n => $sf ) {
			$this->addAdminEmptyOptions ( $sf, $id_student );
		}
	}
	/*
     * check to see which active priv gives the user
     * the best access to this student and their forms
     */
	protected function getAccess($id_student, $usersession) {
		$student_auth = new App_Auth_StudentAuthenticator ();
		return $student_auth->validateStudentAccess ( $id_student, $usersession );
	}
	
	function myrecordsAction() {
		// we disable the layout because we're returning ajax
		$this->_helper->layout->disableLayout ( true );
		
		$templates = new Model_Table_MyTemplateFormData ();
		
		$myRecords = $templates->getTemplates ( $this->usersession->sessIdUser, $this->getRequest ()->getParam ( 'myrecordsType' ) );
		//		Zend_Debug::dump($myRecords);die();
		$this->view->data = new Zend_Dojo_Data ( 'id_my_template_data', $myRecords, 'id_my_template_data' );
		return $this->render ( 'data' );
	}
	function savemyrecordsAction() {
		// we disable the layout because we're returning ajax
		$this->_helper->layout->disableLayout ( true );
		
		$templates = new Model_Table_MyTemplateFormData ();
		
		$data = Zend_Json::decode ( $this->getRequest ()->getParam ( 'data' ) );
		
		$result = $templates->saveTemplate ( $this->usersession->sessIdUser, $this->getRequest ()->getParam ( 'myrecordsType' ), $this->getRequest ()->getParam ( 'data' ), $data ['id_my_template_data'] );
		$this->view->data = new Zend_Dojo_Data ( 'id_my_template_data', array (), 'id_my_template_data' );
		return $this->render ( 'data' );
	}
	function insertmyrecordsAction() {
		// we disable the layout because we're returning ajax
		$this->_helper->layout->disableLayout ( true );
		
		if ($this->getRequest ()->getParam ( 'data' )) {
			$insertData = $this->getRequest ()->getParam ( 'data' );
		} else {
			$insertData = array ();
		}
		
		$templates = new Model_Table_MyTemplateFormData ();
		
		$result = $templates->addTemplate ( $this->usersession->sessIdUser, $this->getRequest ()->getParam ( 'myrecordsType' ), $insertData );
		$this->view->data = new Zend_Dojo_Data ( 'id_my_template_data', array (), 'id_my_template_data' );
		return $this->render ( 'data' );
	}
	
	function deletemyrecordsAction() {
		// we disable the layout because we're returning ajax
		$this->_helper->layout->disableLayout ( true );
		
		$templates = new Model_Table_MyTemplateFormData ();
		
		$data = Zend_Json::decode ( $this->getRequest ()->getParam ( 'data' ) );
		//		Zend_Debug::dump($data['myrecords_id_my_template_data']);die();
		

		$result = $templates->deleteTemplate ( $data ['myrecords_id_my_template_data'] );
		$this->view->data = new Zend_Dojo_Data ( 'id_my_template_data', array (), 'id_my_template_data' );
		return $this->render ( 'data' );
	
	}
	
	function processeditorAction() {
		// we disable the layout because we're returning ajax
		$this->_helper->layout->disableLayout ( true );
		
		$editor = new App_Form_Element_TestEditor ( 'tempEditor' );
		$editor->setValue ( Zend_Json::decode ( $this->getRequest ()->getParam ( 'data' ) ) );
		
		$tempForm = new Zend_Form ();
		$tempForm->addElement ( $editor );
		

		$updateRes = false;
		try {

			$sessUser = new Zend_Session_Namespace ( 'user' );
			
			// save the editor - $editor->getValue ()
			// insert into editor_save_log
			$editorLogObj = new Model_Table_EditorSaveLog();
			$data = array(
				'form_number' => $this->getFormNumber(),
				'id_form' => $this->getRequest()->getParam('id_form'),
				'field_name' => $this->getRequest()->getParam ( 'id_editor' ),
				'field_value' => $editor->getValue(),
				'id_user' => $sessUser->sessIdUser,
			);
			if(false!=$editorLogObj->insert($data)) {
				$updateRes = 'true';
			}
		} catch (Exception $e) {
			
		}
		
		$items = array (array ('id_editor' => $this->getRequest ()->getParam ( 'id_editor' ), 'id_editor_data' => $editor->getValue () , 'result'=>$updateRes) );
		
		$this->view->data = new Zend_Dojo_Data ( 'id_editor_data', $items );
		return $this->render ( 'data' );
	
	}
	function setSubFormsForDuping($page, $array) {
		$this->subFormsForDuping[$page] = $array; 
	}
	function getSubFormsForDuping($page) {
		if(isset($this->subFormsForDuping[$page])) {
			return $this->subFormsForDuping[$page];
		}
		return array(); 
	}
	
	public function logAction()
	{
		$config = Zend_Registry::get ( 'config' );
		$refreshCode = '?refreshCode=' . $config->externals->refresh;
		
		// style the view page
		$this->view->headLink ()->appendStylesheet ( '/css/site_view.css' . $refreshCode );
		$this->view->headLink ()->appendStylesheet ( '/css/srs_style_additions.css' . $refreshCode );
		
		// configure options
		$this->view->mode = 'log';
		
		// get requested page, if any
		$this->view->page = ($this->getRequest ()->getParam ( 'page' ) > 0) ? $this->getRequest ()->getParam ( 'page' ) : $this->startPage;
		
		// set form title
		$this->view->headTitle ( ' - ' . $this->getFormTitle () . ' Page ' . $this->view->page );
		
		// build the model
		$this->view->db_form_data = $this->buildModel ( $this->getRequest ()->getParam ( 'document' ), $this->view->mode );
		
		$config = array ('className' => $this->getFormClass (), 'mode' => 'edit', 'page' => 'all', 'version' => $this->view->version, 'lps' => $this->view->lps );
		
		// build zend form
		$this->view->form = $this->buildZendForm ( $this->getFormClass (), $this->view->db_form_data, $this->view->version, $config, $this->view->page );
		
		// validate the forms (all pages)
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
		
		// enforce view mode by changing the view helper on all elements to formNote
		$this->convertFormToView ( $this->view->form );
		
		// build quick links
		$this->buildStudentQuickLinks($this->view->db_form_data ['id_student']);
		
		$log = new Model_Table_Log();
		$this->view->results = $log->getLogsForDocument(
			$this->getRequest()->getParam('document'),
			$this->getFormNumber()
		);
		$this->render('/log/log', null, true);
	}
	
	// search helper functions
	// helper functions
	public function buildSearchOptions($searchValues, $searchFields) {
	    $i=0;
	    $retArray = array();
	    for ($i=0;$i<count($searchFields);$i++)
	        $retArray[$searchFields['search'.$i]] = $searchValues['search'.$i];
	        return $retArray;
	}
	public function buildPageLinks($pages, $url, $options) {
	$pageLinks = array();
	        $separator = ' | ';
	        for ($x = 1; $x <= $pages->pageCount; $x ++) {
	        if ($x == $pages->current) {
	        $pageLinks[] = $x;
	        } else {
	        $pageLinks[] = $this->view->srsAjaxLink($x,
	        $url,
	        array('update' => $options['updateId'], 'class' => 'ajaxLink',
	        'dataType' => 'html', 'addOnLoad' => false,
	        'attribs' => array('p' => $x,
	        'c' => $options['itemsPerPage'],
	'getUrl' => $url,
	'updateRef' => $options['updateId'])));
	}
	}
	return $pageLinks;
	}
	
	public function pagenateSearchTable($config, $currentPage=null, $itemsPerPage=null) {
	
	    $sess = new Zend_Session_Namespace ( $config['sessionName'] );
	
	    $options = array();
	    $options['updateId'] = $config['updateId'];
	    $options['currentPage'] = null==$currentPage?$sess->options['currentPage']:$currentPage;
	    $options['itemsPerPage'] = null==$itemsPerPage?$sess->options['itemsPerPage']:$itemsPerPage;
	    $options['searchValues'] = $sess->options['searchValues'];
	    $options['controller'] = $this->getRequest()->getControllerName();
	    $options['action'] = $this->getRequest()->getActionName();
	
	    $options['searchOther'] = $this->getRequest()->getParam('search_other');
	    $options['tranStatus'] = $this->getRequest()->getParam('tranStatus');
	    $options['format'] = $this->getRequest()->getParam('format');
	    $options['searchStatus'] = $this->getRequest()->getParam('searchStatus');
	    $options['searchFilter'] = $this->getRequest()->getParam('searchFilter');
	    $options['searchType'] = $this->getRequest()->getParam('searchType');
	
	    // do the search
	    if(isset($config['searchModelName'])) {
	        // we have a model and a search model
	        // create the search model and pass the model and session
	        $searchModel = new $config['searchModelName'](new $config['modelName'](), new Zend_Session_Namespace('user'));
	    } else {
	        // no search model abstraction
	        // just get the data from the model
	        $searchModel = new $config['modelName']();
	    }
	    $data = $searchModel->$config['searchModelFunction']($options);
	
	    // store search fields and values in session
	    $sess->options = $options;
	
	    // initialize pager with data set
	    $pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data));
	    $pager->setCurrentPageNumber($options['currentPage']);
	    $pager->setItemCountPerPage($options['itemsPerPage']);
	
	    // get page data
	    $pages = $pager->getPages();
	
	    // create page links
	    $pageLinks = $this->buildPageLinks($pages, $config['pagenateLink'], $options);
	
	    // add data to the view
	    $this->view->pager = $pager;
	    $this->view->pageLinks = $pageLinks;
	
	    // because we're using ajaxLinks we return the data as straight html
	    return $this->view->render($config['viewscript']);
	}
	
	public function buildSearchTable($config, $searchValues) {
	    $sess = new Zend_Session_Namespace ( $config['sessionName'] );
	
	    $options = array();
	    $options['updateId'] = $config['updateId'];
	    $options['currentPage'] = 1;
	    $options['itemsPerPage'] = $searchValues['maxRecs'];
	    $options['controller'] = $this->getRequest()->getControllerName();
	    $options['action'] = $this->getRequest()->getActionName();
	
	    $options['searchOther'] = $this->getRequest()->getParam('search_other');
	    $options['tranStatus'] = $this->getRequest()->getParam('tranStatus');
	    $options['format'] = $this->getRequest()->getParam('format');
	    $options['searchStatus'] = $this->getRequest()->getParam('searchStatus');
	    $options['searchType'] = $this->getRequest()->getParam('searchType');
	
	    // build the search form and then store the values in a session
	    if(isset($config['formName'])) {
	        $searchForm  = new $config['formName']();
	        $searchForm->populate($searchValues);
	        $sess->formParams = $searchForm->getValues();
	    }
	    // parse out the search fields and values
	    if(!empty($searchValues['searchValue'])) {
	        $options['searchValues'] = $this->buildSearchOptions($searchValues['searchValue'], $searchValues['searchField']);
	    }
	
	    // store search fields and values in session
	    $sess->options = $options;
	
	    // do the search
	    // do the search
	    if(isset($config['searchModelName'])) {
	        // we have a model and a search model
	        // create the search model and pass the model and session
	        $searchModel = new $config['searchModelName'](new $config['modelName'](), new Zend_Session_Namespace('user'));
	        $data = $searchModel->$config['searchModelFunction']($options);
	    } else {
	        // no search model abstraction
	        // just get the data from the model
	        $searchModel = new $config['modelName']();
	        $data = $searchModel->$config['searchModelFunction']($options);
	    }
	    // initialize pager with data set
	    $pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data));
	    $pager->setCurrentPageNumber($options['currentPage']);
	    $pager->setItemCountPerPage($options['itemsPerPage']);
	
	    // get page data
	    $pages = $pager->getPages();
	
	    // create page links
	
	    $pageLinks = $this->buildPageLinks($pages, $config['pagenateLink'], $options);
	
	    // add data to the view
	    $this->view->pager = $pager;
	    $this->view->pageLinks = $pageLinks;
	
	    if(isset($config['format']) && 'html'==$config['format']) {
	        return $this->view->render($config['viewscript']);
	    } else {
	        // kick out the jams
	        return Zend_Json::encode(
	                array(
	                        'result' => $this->view->render($config['viewscript'])
	                )
	        );
	    }
	
	}
	
	public function addSpaceCallback($match) {
		if (preg_match('/\&lt\;/', $match[0])) {
			return '&lt; ' . substr($match[0], 4, 1);
		} 
		return $match[0]{0} . ' ' . $match[0]{1};
	}
	
	/*
	 * Tiny Filter
	 */
	public function tinyFilterAction() {
		
		$db_form_data = $this->buildModel ( $this->getRequest ()->getParam ( 'document' ), 'edit' );
		$formName = "Form_Form".$this->getFormNumber()."";
		$page = "edit_p".$this->getRequest()->getParam('page')."_v1";
		$form = new $formName;
		$form->{$page}();
		$form->populate($db_form_data);
		$toFilter = array();
		foreach ($form->getElements() AS $element) {
			if ($element->getType() == 'App_Form_Element_TinyMceTextarea') {
				$value = $element->getValue();
				if (!empty($value)) {
					$toFilter[$element->getName()] = preg_replace_callback('/(\<|\&lt\;)[\x00-\x40\x5B-\x60\x7B-\x7F]/', array(&$this, "addSpaceCallback"), $value);
				}
			}
		}
		$this->view->headScript()->appendFile('/js/jquery/jquery-1.8.2.js');
		$this->view->headScript()->appendFile('//tinymce.cachefly.net/4.1/tinymce.min.js');
		$this->_helper->layout()->disableLayout();
		$this->view->toFilter = $toFilter;
		$this->view->document = $this->getRequest()->getParam('document');
		$this->view->formNumber = $this->getFormNumber();
		echo $this->view->render('tiny-filter/tiny-mce-filter.phtml');
		exit;
	}
	
	public function updateElementValueAction() {
		$this->_helper->layout()->disableLayout();
		$model = new Model_Table_Form004();
		$model->saveForm(
			$this->getRequest()->getParam('document'), 
			array(
				$this->getRequest()->getParam('element') => $this->getRequest()->getParam('value'),
			)
		);
		echo Zend_Json::encode(array('result' => 'success'));
		exit;
	}
}
