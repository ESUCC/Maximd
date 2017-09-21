<?php
class StudentController extends My_Form_AbstractFormController
{
 
    public function init(){ 
        $this->_redirector = $this->_helper->getHelper('Redirector');

        $this->searchConfig['searchStudent'] = array(
            'updateId' => '#searchResults',
            'sessionName' => 'searchStudent',
            'formName' => 'Form_SearchStudent',
            'modelName' => 'Model_Table_StudentTable',
            'searchModelName' => 'Model_SearchStudent',
            'searchModelFunction' => 'searchStudent',
            'pagenateLink' => '/student/pagenate',
            'viewscript' => 'student/search-student-table.phtml',
        );

        $this->_formatFieldHeadings = array(
            'id_student' => array('SRS Student ID', 'searchId'),
            'name_full' => array('Name', 'searchName'),
            'name_county' => array('County', 'searchCounty'),
            'name_district' => array('District', 'searchDistrict'),
            'name_school' => array('School', 'searchSchool'),
            'role' => array('User Role', 'searchUserRole'),
            'manager' => array('Case Manager', 'searchCaseMGR'),
            'address' => array('Address', 'searchAddress'),
            'phone' => array('Phone', 'searchPhone'),
            'iep' => array('IEP/IFSP* Due Date', 'searchIEP'),
            'mdt' => array('MDT/Det. Notice* Due Date', 'searchMDT'),
            'primary_disability' => array('Primary Disability', 'searchIEP'),
            'age' => array('Age', 'searchId'),
            'dob' => array('Date of Birth', 'searchMDT'),
        );

        $this->_formatFieldValues = array(
            'id_student' => array('id_student', 'searchId'),
            'name_full' => array('name_full', 'searchName'),
            'name_county' => array('name_county', 'searchCounty'),
            'name_district' => array('name_district', 'searchDistrict'),
            'name_school' => array('name_school', 'searchSchool'),
            'role' => array('user_role', 'searchUserRole'),
            'manager' => array('name_case_mgr', 'searchCaseMGR'),
            'address' => array('address', 'searchAddress'),
            'phone' => array('phone', 'searchPhone'),
            'iep' => array('iep_due_date_link', 'searchIEP'),
            'mdt' => array('mdt_due_date_link', 'searchMDT'),
            'primary_disability' => array('mdt_primary_disability', 'searchMDT'),
            'age' => array('age', 'searchId'),
            'dob' => array('dob', 'searchMDT'),
        );
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
    
// Maxim modified this code October 20 2016
    public function logAction()
    {
      $id_student = intval($this->getRequest()->getParam('id_student') * 1);

      if ($id_student > 0) {

        $nb = intval($this->getRequest()->getParam('nb') * 1);

        if ($nb == 0) 
            $this->_helper->viewRenderer->setNoRender(true);
         else {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
         }

        $session = new Zend_Session_Namespace('searchLogForm');
        $options = array();

        if ($session->id_student > 0 && $id_student == 0)
          $options['id_student'] = $session->id_student;
        else if ($id_student > 0 || $id_student != $session->id_student)
        {
          $options['id_student'] = $id_student; 
          $session->id_student = $id_student;
        }
        else 
        { 
           $options['id_student'] = 0; 
           $session->id_student = 0;  
        }

        $options['page'] = intval($this->getRequest()->getParam('page') * 1);
        $options['maxRecs'] = 25; // default 25

        // Generate KEY by district and county
        if (!isset($options['key']) || !isset($session->searchKey)) {
             $options['key'] = "studentlog_".$options['id_student'];
             $session->searchKey = $options['key'];
        }

        $studentQuery = new Model_Table_StudentLog();
        $result = $studentQuery->studentlogList($options); // Get result

        $this->view->nb = $nb;
        $this->view->id_student = $options['id_student'];
        $this->view->page = $options['page'];
        $this->view->maxResultsExceeded = $result[0];  // Max limit rows exceeded
        $this->view->paginator = $result[1];
        $this->view->key = $options['key'];
        $this->view->maxRecs = $options['maxRecs'];
        $this->view->resultCount = $result[3];
        $this->view->results = $result[1];
 


        // Generate pagination
        if(count($result[3]) > 0) {
            $this->view->page = $options['page'];
            $paginator = Zend_Paginator::factory($this->view->resultCount);
            $paginator->setItemCountPerPage($options['maxRecs']);               // set number of pages in pagenator
            $paginator->setCurrentPageNumber($options['page']);                 // put current page number into the pagenator
            $this->view->paginator=$paginator;                                  // put the pagenator in the view
        }


       echo $this->view->render('student/log.phtml');
     }

      return;
    }

    public function studentaddAction()
    {
	$this->_helper->viewRenderer->setNoRender(true);

        // ----   Read all Privileges ------------------------
	$x = 0;
	$priv = Array();
	$priv_student = 0;
	while ($x < count($this->usersession->user->privs))
        {
        	if (key($this->usersession->user->privs) >= 0) { $priv_student = 1; break; }
		$x++;
        }
        // --------------------------------------------------------
       	if ($priv_student == 1)
       	{
        	$options = array();
	        $options['id_county'] = $this->usersession->user->user['id_county'];
	        $userid = $this->usersession->id_personnel;

    	    // Get data for Form
	        $studentAddFormQuery = new Model_Table_StudentFormAdd();
	        $result = $studentAddFormQuery->studentFormCountyList($userid); // Get result
		$this->view->county = $result;


		// NonPublic schools
		$nonpubcounty = new Model_Table_County();
		$result = $nonpubcounty->getNonPublicCounties();
		$this->view->nonpubcounty = $result;

    	        echo $this->view->render('student/student-add.phtml');


	   } else {
			echo $this->view->render('student/access-denied.phtml');
	   }
       return;
    }
    
    public function listdistrictAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $userid = $this->usersession->id_personnel;
        $request = $this->getRequest();
        // Get data for District List
        $studentDistrictListQuery = new Model_Table_StudentFormAdd();
        $result = $studentDistrictListQuery->studentDistrictList($request->id_county, $userid); // Get result 
        $this->_helper->json->sendJson($result[0]);
        return;
    }


    public function listnonpublicdistrictsAction()
    {
	$id = $this->getRequest()->getParam('nonpubcounty');
	if ($id == "") $id = "00";
	$nonpubdistrict = new Model_Table_District();
	$result = $nonpubdistrict->getNonPublicDistricts($id);
        $this->_helper->json->sendJson($result);
        return;
    }

    public function listnonpublicschoolsAction()
    {
	$id1 = $this->getRequest()->getParam('nonpubcounty');
	if ($id1 == "") $id1 = "00";
	$id2 = $this->getRequest()->getParam('nonpubdistrict');
	if ($id2 == "") $id2 = "00";
	$nonpubschool = new Model_Table_School();
	$result = $nonpubschool->getNonPublicSchools($id1, $id2);
        $this->_helper->json->sendJson($result);
        return;
    }


    public function listschoolAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $request = $this->getRequest();
        // Get data for School List
        $studentSchoolListQuery = new Model_Table_StudentFormAdd();
        $result = $studentSchoolListQuery->studentSchoolList($request->id_county, $request->id_district); // Get result 
        $this->_helper->json->sendJson($result[0]);
        return;
    }

    public function listmanagersAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->getRequest();
        // Get data for Managers List
        $studentManagersListQuery = new Model_Table_StudentFormAdd();
        
        // Mike changed this on 8-4-2017 from
        // $result = $studentManagersListQuery->studentManagersList($re... to the following
        //the studentManagersListm was put in special just for 
        $result = $studentManagersListQuery->studentManagersList($request->id_county, $request->id_district, $request->id_school); // Get result
        $this->writevar1($result,'this is the result');
       $this->_helper->json->sendJson($result[0]);

        return;
    }

    public function listsesisAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $do_action = ($this->getRequest()->getParam('do_action') != "") ? $this->getRequest()->getParam('do_action') : "";
        $id_county = ($this->getRequest()->getParam('id_county') != "") ? $this->getRequest()->getParam('id_county') : "";
        $id_district = ($this->getRequest()->getParam('id_district') != "") ? $this->getRequest()->getParam('id_district') : "" ;

        $studentSesisListQuery = new Model_Table_StudentFormAdd();
        $result = $studentSesisListQuery->studentSesisList($do_action, $id_county, $id_district); // Get result
        $this->_helper->json->sendJson($result[0]);

        return;
    }

	public function checknssrsAction()
    {

        $options["unique_id_state"] = intval($this->getRequest()->getParam('unique_id_state') * 1);

        $studentNssrsCheckQuery = new Model_Table_StudentFormAdd();
        $result = $studentNssrsCheckQuery->studentNssrsCheck($options); // Get result

        $this->_helper->json->sendJson($result[0]);

        return;
    }

    public function parentAction()
    {
        $request = $this->getRequest();
        $this->view->request = $this->getRequest();
        $post = $this->getRequest()->getPost();

        if(!isset($request->id_student))
        {
            throw new Exception('student required');
        } else {
            $this->view->student = $request->id_student;
        }

        header("Location:https://iep.esucc.org/srs.php?area=student&sub=student&student=".$request->id_student."&option=parents");
        exit;
    }

    public function saveAction()
    {
//        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

	   	// ----   Read all Privileges ------------------------/
       	$x = 0;
       	$priv = Array();
       	$priv_student = 0;
       	while ($x < count($this->usersession->user->privs))
        {
	    	if (key($this->usersession->user->privs) >= 0) { $priv_student = 1; break; }
        	    $x++;
        }
        // --------------------------------------------------------
       	if ($priv_student == 1)
       	{
        	$post = $this->getRequest()->getPost();

	        $options = Array();
    	    $options_reports = Array();

		    foreach ($post as $nameField => $valueField)
			{
		      	$value = urldecode($nameField);
		      	$options[$value] = urldecode(strip_tags($valueField));
			 // 	print $nameField ."=>". $valueField."<br>";
    		}


			// Validate Data from form
			$validator_digital = new Zend_Validate_Regex('/\d+$/');
			$validator_state = new Zend_Validate_Regex('/\w{2}$/');
			$validator_zip = new Zend_Validate_Regex('/\d{5}-\d{4}$|^\d{5}$/');
			$validator_phone = new Zend_Validate_Regex('/^\d{3}-\d{3}-\d{4}$/');
			$validator_email = new Zend_Validate_Regex('/\S+@\S+\.\S+/');
			$validator_date = new Zend_Validate_Regex('/^\d{2}\/\d{2}\/\d{4}$/');

			$options['id_district'] = $this->usersession->user->user['id_district'];
			$options['id_county'] = $this->usersession->user->user['id_county'];


			if (!isset($options["exclude_from_nssrs_report"])) $options["exclude_from_nssrs_report"] = "No";
			if (!isset($options["ward_surrogate"])) $options["ward_surrogate"] = "No";
			if (!isset($options["ward_surrogate_nn"])) $options["ward_surrogate_nn"] = "No";

			if (!isset($options["id_county_school"])) $options["id_county_school"] = "";
			if (!isset($options["id_district_school"])) $options["id_district_school"] = "";
			if (!isset($options["id_school"])) $options["id_school"] = "";
			if (!isset($options["case_manager"])) $options["case_manager"] = 0; // int

			if (!isset($options["program_provider_name"])) $options["program_provider_name"] = "";
			if (!isset($options["program_provider_code"])) $options["program_provider_code"] = "";
			if (!isset($options["program_provider_id_school"])) $options["program_provider_id_school"] = "";

			if (!isset($options["unique_id_state"]) || $options["unique_id_state"] == "") $options["unique_id_state"] = 0;

			if ($validator_date->isValid($options["date_web_notify"]) &&
				strlen($options["name_first"]) > 2 &&
				strlen($options["name_last"]) > 2 &&
				$options["id_county_school"] != "" &&
				$options["id_district_school"] != "" &&
				$options["id_school"] != "" &&
				$options["case_manager"] != "" &&
				$options["pub_school_student"] != "" &&
				$validator_date->isValid($options["dob"]) &&
				$options["grade"] != "" &&
				($options["gender"] != "Female" || $options["gender"] != "Male") &&
				$options["alternate_assessment"] != "" &&
				$options["ethnic_group"] != "" &&
				$options["primary_language"] != "" &&
				$options["ell_student"] != "" &&
				$options["ward"] != "" &&
				strlen($options["address_street1"]) > 2 &&
				$options["address_city"] != "" &&
				$validator_state->isValid($options["address_state"]) && 
				$validator_zip->isValid($options["address_zip"]) &&
				($validator_phone->isValid($options["phone"]) || $options["phone"] == "") &&
				(($validator_email->isValid($options["email_address"]) || $options["email_address"] == "") && ($validator_email->isValid($options["email_address_confirm"])
					 || $options["email_address_confirm"] == "") && $options["email_address"] == $options["email_address_confirm"]) 
			) 
			{



				$options["exclude_from_nssrs_report"] = ($options["exclude_from_nssrs_report"] == "Yes") ? 'True': 'False';
				$options["pub_school_student"] = ($options["pub_school_student"] == "Yes") ? 'True' : 'False';
				$options["ell_student"] = ($options["ell_student"] == "Yes") ? 'True' : 'False';
				$options["ward"] = ($options["ward"] == "Yes") ? 'True' : 'False';
				$options["alternate_assessment"] = ($options["alternate_assessment"] == "Yes") ? 'True' : 'False'; 
				$options["ward_surrogate"] = ($options["ward_surrogate"] == "Yes") ? 'True' : 'False';
				$options["ward_surrogate_nn"] = ($options["ward_surrogate_nn"] == "Yes") ? 'True' : 'False';

//				print_r($options);
//				exit;

				$studentQuery = new Model_Table_StudentFormAdd();
				$result = $studentQuery->studentSave($options);  // Save data

				header("Location: /student/edit/id_student/".$result);
				exit;

				//    $this->view->msg = "User ID: ".$result." was created";

		 	} else {

 			    $this->view->msg = "Incorrect data";

		 	}
			echo $this->view->render('student/student-save.phtml');

	       } else {
	        echo $this->view->render('student/access-denied.phtml');
	    }

        return;
    }

 	public function deleteAction()
    {

//        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        echo $this->view->render('student/student-delete.phtml');
        return;
    }

    public function teamAction()
    {
        $request = $this->getRequest();
        $this->view->request = $this->getRequest();
        $post = $this->getRequest()->getPost();

        if(!isset($request->id_student))
        {
            throw new Exception('student required');
        } else {
            $this->view->student = $request->id_student;
        }


        //header("Location:https://iep.esucc.org/srs.php?area=student&sub=student&student=".$request->id_student."&option=team");
        exit;

    }

    public function chartsAction()
    {


        $request = $this->getRequest();
        $this->view->request = $this->getRequest();
        $post = $this->getRequest()->getPost();

        if(!isset($request->id_student))
        {
            throw new Exception('student required');
        } else {
            $this->view->student = $request->id_student;
        }


        header("Location:https://iep.esucc.org/srs.php?area=student&sub=student&student=".$request->id_student."&option=charting");
        exit;


        $formStudent = new Form_Student();
        $this->view->form = $formStudent->student_chart();

        // initialize pos if not set
        if(!isset($request->pos)) $this->view->pos = 0;
        if(!isset($this->view->maxRecs)) $this->view->maxRecs = 250;

        if(isset($post['doaction']) && $post['doaction'] == 'insert' && !empty($request->id_student)) {

            // if action is insert, insert a chart for this student and select it.
            $newId = App_Classes_StudentChart::insert($request->id_student);
            $this->_redirector->gotoSimple('charts', 'student', null,
                array('student' => $this->view->student, 'view_chart_id' => $newId)
            );

        } elseif(isset($post['doaction']) && $post['doaction'] == 'save' && isset($post['id_student_chart']) && '' != $post['id_student_chart']) {
            // parse out other charts to plot on this chart
            $secPlotText = "";
            if(isset($post['secondaryPlots']) && '' != $post['secondaryPlots'])
            {
                foreach($post['secondaryPlots'] as $index => $sp)
                {

                    $secPlotText .= $sp . "\n";
                }
                $post['secondary_plot_charts'] = $secPlotText;
            }

            // if action is save, save the form
            $saveResult = App_Classes_StudentChart::save($request->view_chart_id, $post);
        }

        // redirect if goto_id_student is set
        if(isset($post->goto_id_student) && '' != $post->goto_id_student)
        {
            Zend_Debug::dump('die 2');
            die();
//            header("Location:$DOC_ROOT/srs.php?area=student&sub=student&student=".$post->goto_id_student."&option=charting");
        }

        // get selected chart
        if(isset($request->view_chart_id)) {
            $this->view->view_chart_id = $request->view_chart_id;
            $this->view->selectedChart = App_Classes_StudentChart::get($request->id_student, $request->view_chart_id);

            $this->view->form->populate($this->view->selectedChart->toArray());
        }

    }

    /**
     * admin action to view recent forms for a student
     * @throws Exception
     */
    function formsAction() {
        $this->view->hideLeftBar = true;
     //   $this->limitToAdminAccess();

        $student_auth = new App_Auth_StudentAuthenticator();
        $accessObj = $student_auth->validateStudentAccess($this->getRequest()->getParam('student'), $this->usersession);
         if(false == $accessObj) {
        //    throw new Exception('You do not have access.');
        } 

        $this->view->student = $this->getRequest()->getParam('student');
        $this->view->draftFormsArr = array();
        $this->view->finalFormsArr = array();

        $sessUser = new Zend_Session_Namespace('user');
        // build array of most recent form ids
        for ($i = 1; $i <= 31; $i++) {
            $dateField = 'date_notice';
            $formCode = str_pad($i, 3, '0', STR_PAD_LEFT);

            if('004' == $formCode) {
                $dateField = 'date_conference';
            }
            $modelName = "Model_Table_Form".$formCode;
            $formObj = new $modelName();
            $mostRecentDraft = $formObj->mostRecentDraftForm($this->getRequest()->getParam('student'), $dateField);
            $this->view->draftFormsArr[$formCode] = $mostRecentDraft["id_form_$formCode"];

            $mostRecentFinalForm = $formObj->mostRecentFinalForm($this->getRequest()->getParam('student'), $dateField);
            $this->view->finalFormsArr[$formCode] = $mostRecentFinalForm["id_form_$formCode"];

        }

    }
//    public function listAction() {
//        // student search screen
//
//        $this->view->dojo()->requireModule('soliant.widget.StudentSearch');
//
//        // get searches and make it look nice
//        $searchObj = new Model_Table_StudentSearch();
//
//        // get the list of all searches for this user
//        $mySearches = $searchObj->mySearches($this->usersession->sessIdUser, 'my_searches');
//
//        // get the main search options (limit/status/sort)
//        // as well as the the search row data
//        $mySearch = $searchObj->getSearch($mySearches[0]['id_student_search'], 'my_searches');
//
//        // build the html form for the search
//        $this->view->form = new Form_StudentSearchBasic();
//        $this->view->form->buildSubformsArray(count($mySearch['subforms']['my_searches']), 'my_searches', 'Form_StudentSearchRow');
//
//        // populate html form - subforms (search rows) and form (main search options)
//        $this->view->form->populate($mySearch['subforms']['my_searches']);
//        $this->view->form->populate($mySearch);
//
//        $data = new Zend_Dojo_Data('id_student_search_rows', array());
//        $data->addItems($mySearch['subforms']['my_searches']);
//        $this->view->searchRowData = $data->toJson();
//
//        // store count of subforms
//        $this->view->subform_counts = array('my_searches'=>count($mySearch['subforms']['my_searches']));
//
//    }

    public function searchStudentsAction() {
       //include("Writeit.php");
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $studentTable = new Model_Table_StudentTable();
        $session = new Zend_Session_Namespace('user');
        $searchModel = new Model_Search($studentTable, $session);
        $this->view->records = $searchModel->searchStudents($this->getRequest());
        echo Zend_Json::encode(
            array(
                'result' => $this->view->render('student/'.$searchModel->getFormatToRender($this->getRequest()))
            )
        ); 
    }

    protected function getStudentDataAction()
    {
        // fake like the request is coming from the student list/search page
        $params = array();
        $params['id_student_search_rows'] = '1';
        $params['status'] = 'Active';
        $params['search_field'] = 'id_student';
        $params['search_value'] = $this->getRequest()->getParam('id_student');
        $params['recs_per'] = '1';

        $sessUser = new Zend_Session_Namespace('user');
        $results = Model_StudentSearch::search($sessUser->id_personnel, $params);

        $data = new Zend_Dojo_Data ( 'id_student', array($results[0]->toArray()));
        echo $data;
        die();
    }

    public function searchAction() {
       //include("Writeit.php");
        /**
         * update personnel if user changes pref for student search screen
         */
        if (false == $this->usersession->parent && $this->getRequest()->getParam('pref_student_search_location')) {
            $personnelObj = new Model_Table_PersonnelTable();
            $personnel = $personnelObj->fetchRow(
                "id_personnel = " . $this->usersession->sessIdUser );
          
       //   writevar($personnel,"this is personnel search action in ctl line 296\n");
            $personnel->pref_student_search_location = $this->getRequest()->getParam('pref_student_search_location');
            $personnel->save();
           
        }
     //   writevar($personnel,"this is personnel search action in ctl line 301\n");
 
        $this->setupStudentSearch();
    }

    /**
     * called by /student/search
     */
    public function searchStudentAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->page = $this->getRequest()->getParam('page');
        for($i=0;$i<6;$i++) {
            $fieldName = 'formatColumn'.$i;
            $formatFields[] = $this->getRequest()->getParam($fieldName);
        }
        $this->view->formatFields = $formatFields;

        /*
           * Setup field sorting.
          */
        $sort = array();
        foreach ($formatFields AS $field) {
            if (!empty($field)) {
                if (0 === strpos($this->getRequest()->getParam('sort'), $field)) {
                    if (strpos($this->getRequest()->getParam('sort'), 'desc') > 0) {
                        $this->view->{$field.'Sort'} = $field.'Sort';
                        $sort['field'] = $field;
                        $sort['direction'] = 'DESC';
                    } elseif (strpos($this->getRequest()->getParam('sort'), 'asc') > 0) {
                        $this->view->{$field.'Sort'} = $field.'Sort-desc';
                        $sort['field'] = $field;
                        $sort['direction'] = 'ASC';
                    } else
                        $this->view->{$field.'Sort'} = $field.'Sort-asc';
                } else {
                    $this->view->{$field.'Sort'} = $field.'Sort-asc';
                }
            }
        }

        $search  = new Model_SearchStudent(
            new Model_Table_StudentTable(),
            new Zend_Session_Namespace('user'),
            Zend_Registry::get('searchCache')
        );
        $this->view->field = $search->getSortStatus($this->getRequest()->getParams());
        //Mike changed this   
        if (!array_key_exists('error', ($searchResults = $search->searchStudent($this->getRequest()->getParams(), $sort)))) {
            $this->view->maxResultsExceeded = $searchResults[0];
            $this->view->paginator = $searchResults[1];
            $this->view->key = $searchResults[2];
            $this->view->resultCount = $searchResults[3];
            $this->view->showAll = $this->getRequest()->getParam('showAll');
            $this->view->defaultCacheResult = $this->getRequest()->getParam('defaultCacheResult');
            $viewScript = 'student/search-student-table.phtml';
        } else {
            $this->view->error = $searchResults['error'];
            $viewScript = 'student/search-student-error.phtml';
        }

        echo Zend_Json::encode(
            array('result' => $this->view->render($viewScript))
        );
        exit;
    }

    public function printResultsAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $searchCache = Zend_Registry::get('searchCache');

        try {
            $results = $searchCache->load($this->getRequest()->getParam('id'));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        $fileName = $this->getRequest()->getParam('id');
        for($i=0;$i<6;$i++) {
            $fieldName = 'formatColumn'.$i;
            $formatFields[] = $this->getRequest()->getParam($fieldName);
        }

        $this->view->results = $results;
        $this->view->formatFields = $formatFields;
        echo $this->view->render('student/print-student-search-table.phtml');
    }

    public function exportResultListToCsvAction() {

        $searchCache = Zend_Registry::get('searchCache');

        try {
            $results = $searchCache->load($this->getRequest()->getParam('id'));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        $fileName = $this->getRequest()->getParam('id');
        for($i=0;$i<6;$i++) {
            $fieldName = 'formatColumn'.$i;
            $formatFields[] = $this->getRequest()->getParam($fieldName);
        }

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        header("Cache-Control: must-revalidate, " .
            "post-check=0, pre-check=0");
        header("Pragma: hack");
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$fileName}-" .
            @date("mdY") . ".csv");

        $fp = fopen('php://output', 'w');

        $heading = array();
        foreach ($formatFields AS $field) {
            if (!empty($field)) {
                switch ($field) {
                    default:
                        $heading[] = $this->_formatFieldHeadings[$field][0];
                        break;
                }
            }
        }
        fputcsv($fp, $heading);

        $row = array();
        foreach ($results AS $result) {
            foreach ($formatFields AS $field) {
                if (!empty($field)) {
                    switch ($field) {
                        case 'role':
                            $row[] = $this->view->UserRole($result['id_student']);
                            break;
                        case 'iep':
                            $iepTimeAddition = "";
                            $ifspTimeAddition = "-1 day +182 days";
                            $iep_due_date = '';

                            // Dupe IEP link
                            if(!empty($result['iep_id']) && "form_004" == $result['form_type']) {
                                // build display field for the date
                                $iep_due_date = date("m/d/y", strtotime($result['iep_date_conference_duration'] . $iepTimeAddition));
                            } elseif(isset($result['iep_id']) && "form_013" == $result['form_type']) {
                                // add astrik if ifsp
                                $iep_due_date = date("m/d/y", strtotime($result['iep_date_conference'] . $ifspTimeAddition))."*";
                            }
                            $row[] = $iep_due_date;
                            break;
                        case 'mdt':
                            $mdtTimeAddition = "+3 years -1 day";
                            $det_noticeTimeAddition = "+3 years -1 day";
                            $mdt_due_date = '';

                            if(!empty($result['mdtorform001_final_id']) && "form_002" == $result['mdtorform001_final_form_type']) {
                                $mdt_due_date = date("m/d/y", strtotime($result['mdtorform001_final_date_created'] . $mdtTimeAddition));
                            } elseif(isset($result['mdtorform001_final_id']) && "form_012" == $result['mdtorform001_final_form_type']) {
                                $mdt_due_date = date("m/d/y", strtotime($result['mdtorform001_final_date_created'] . $det_noticeTimeAddition))."*";
                            }
                            $row[] = $mdt_due_date;
                            break;
                        default:
                            $row[] = $result[$this->_formatFieldValues[$field][0]];
                            break;
                    }
                }
            }
            fputcsv($fp, $row);
            $row = array();
        }

        fclose($fp);
        exit;
    }

    public function addNewUserSearchFormatAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $format = new Model_Table_FormatColumns();
        echo Zend_Json::encode(
            $format->addNewUserSearchFormat(
                new Zend_Session_Namespace('user'),
                $this->getRequest()->getParam('format')
            )
        );
        exit;
    }
    public function deleteSearchFormatAction()
    {
        // ajax requests for this page should not include site layout
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
        }

        $searchFormatId = $this->getRequest()->getParam('id');
        $formatColumnsObj = new Model_Table_FormatColumns();
//        Zend_Debug::dump("format_name = '".$searchFormatId."' and id_user='".$this->usersession->sessIdUser."'");die;
//        $searchFormat = $formatColumnsObj->fetchAll("format_name = '".$searchFormatId."' and id_user='".$this->usersession->sessIdUser."'")->current();
        $searchFormat = $formatColumnsObj->find($searchFormatId)->current();
//        Zend_Debug::dump($searchFormat);die;
        if (is_null($searchFormat)) {
            throw new Exception('Search Format not found');
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();
            if (isset($postData['submit'])) {
                $result = $searchFormat->delete();
                if($result) {
                    echo Zend_Json::encode(
                        array(
                            'success' => 1,
                            'message' => 'Search Format record deleted successfully.'
                        )
                    );
                    die;
                }
            }
        }
        echo Zend_Json::encode(
            array(
                'success' => 0,
                'message' => 'Search Format could not be deleted.'
            )
        );

    }

    public function updateColumnForFormatAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $format = new Model_Table_FormatColumns();
        $format->updateColumnForFormat(
            $this->getRequest()->getParam('format'),
            $this->getRequest()->getParam('column'),
            $this->getRequest()->getParam('columnValue')
        );
        echo Zend_Json::encode(array('success' => 1));
    }

    public function getFormatColumnsAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $format = new Model_Table_FormatColumns();

        if (is_numeric($this->getRequest()->getParam('format')))
            $custom = 1;
        else
            $custom = 0;

        echo Zend_Json::encode(array_merge(array('customFormat' => $custom), $format->getFormatColumns($this->getRequest()->getParam('format'))));
        exit;
    }

//    public function pagenateAction() {
//        $this->_helper->layout()->disableLayout();
//        $this->_helper->viewRenderer->setNoRender(true);
//        Zend_Debug::dump($this->getRequest());
//        exit;
//        echo $this->pagenateSearchTable($this->searchConfig['searchStudent'], $this->getRequest()->getParam('p'), $this->getRequest()->getParam('c'));
//    }

    function searchFormsAction() {
        
        $this->view->hideLeftBar = true;
        if(!$this->getRequest()->getParam('id_student')) {
            $this->_redirector->gotoSimple('search', 'student', null, array());
        } else {
            $studentId = $this->getRequest()->getParam('id_student');
        }

        $personnelObj = new Model_Table_PersonnelTable();
        $personnel = $personnelObj->fetchRow(
            "id_personnel = ".$this->usersession->sessIdUser
        );


        $student_auth = new App_Auth_StudentAuthenticator();
        $accessObj = $student_auth->validateStudentAccess($this->getRequest()->getParam('id_student'), $this->usersession);
        if(false == $accessObj) {
            throw new Exception('You do not have access.');
        }

        /**
         * add student info
         */
        $studentObj = new Model_Table_StudentTable();
        $student = $studentObj->studentInfo($this->getRequest()->getParam('id_student'));
        $this->view->student = $student[0];

        $this->view->hideLeftBar = true;
        $district = new Model_Table_District();
        $searchForm  = new Form_SearchForm(
            $district->getDistrict(
                $this->view->student['id_county'],
                $this->view->student['id_district']
            )
        );
        $this->view->searchForm = $searchForm;
        $this->view->searchForm->limitFormCreationMenus($studentId);


        $session = new Zend_Session_Namespace('searchStudentForms');
        if(isset($session->options)) {
            $searchForm->populate($session->options);
        }
        if(isset($session->page)) {
            $searchForm->getElement('page')->setValue($session->page);
        } else {
            $searchForm->getElement('page')->setValue(1);
        }
        $searchForm->getElement('student')->setValue($studentId);

        $this->view->sessUser = new Zend_Session_Namespace('user');


    }

    public function searchStudentFormsAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->page = $this->getRequest()->getParam('page');
        $this->view->searchFormsUrl = '/student/search-student-forms/page/1';

        /**
         * fields to display in search table
         */
        $this->view->formatFields = array(
            'formname'          => array('cssClass'=>'formname', 'label'=>'Form'),
            'date_created' => array('cssClass'=>'date', 'label'=>'Date Created'),
            'date' => array('cssClass'=>'date', 'label'=>'Date'),
        );

        /**
         * Setup field sorting.
         */
        $sort = array();
        $search  = new Model_SearchStudentForms(
            new Model_Table_StudentTable(),
            new Zend_Session_Namespace('user'),
            Zend_Registry::get('searchCache')
        );

        /**
         * do the search
         */
        $params = $this->getRequest()->getParams();
        if (!array_key_exists('error', ($searchResults = $search->searchForms($params, $sort)))) {
            $this->view->maxResultsExceeded = $searchResults[0];
            $this->view->paginator = $searchResults[1];
            $this->view->key = $searchResults[2];
            $this->view->options = $this->getRequest()->getParams();

            $viewScript = 'student/search-forms/results-table.phtml';

            $session = new Zend_Session_Namespace('searchStudentForms');
            $session->options = $this->getRequest()->getParams();
            $session->sort = $sort;
            $session->page = $this->view->page;
           
            $studentObj = new Model_Table_StudentTable();
            $student = $studentObj->studentInfo($this->getRequest()->getParam('student'));
            $this->view->student = $student[0];

        } else {
            $this->view->error = $searchResults['error'];
            $viewScript = 'student/search-forms/search-student-error.phtml';
        }

        echo Zend_Json::encode(
            array('result' => $this->view->render($viewScript))
        );
        
        exit;
    }

    public function getCreateDistricts($privs) {
        foreach($privs as $priv) {
        }
    }
    public function viewAction()
    {
        $this->forward('edit', null, null, array('viewOnly' => true, 'id_student' => $this->getRequest()->getParam('id_student')));
    }

    public function editAction()
    {
     //  include("Writeit.php");
        $this->view->hideLeftBar = true;

        $postData = $this->getRequest()->getPost();
        $this->writevar1($this->getRequest()->getParam('id_student'),'this is the post data');
        
        $stuObject = new Model_Table_StudentTable();
        $form = new Form_StudentDemographics();

        if($this->getRequest()->getParam('id_student')) {
            $studentArr = $stuObject->studentInfo($this->getRequest()->getParam('id_student'));
          
           //  $this->writevar1($studentArr,'this is the student array');//this gets demo of student as well as firstnamme,lastname of teacm members.  T
            // this is not in the iep_student table.
            if(1!=count($studentArr)) {
                throw new Exception('Student not found');
            } else {
                $dbStudent = $studentArr[0];
                $dbStudent['primary_disability_display'] = $this->getPrimaryDisabilityDisplay($this->getRequest()->getParam('id_student'));
            }

            /**
             * ensure access
             */
            $ssObj = new Model_StudentSearch();
            if(!$ssObj->getMyStudent((int) $this->usersession->sessIdUser, (int) $this->getRequest()->getParam('id_student'))) {
                throw new Exception('Access Denied');
            }

           $this->view->id_student = $this->getRequest()->getParam('id_student');

            /**
             * redirect if not Papillion's
             * Removing restriction per Wade's request in SRSSUPP-666
             *
            if( !('99' == $dbStudent['id_county'] && '9999' == $dbStudent['id_district']) &&
            !('77' == $dbStudent['id_county'] && '0027' == $dbStudent['id_district']) &&
            '1010818' != $this->usersession->sessIdUser
            ) {
            $url = Zend_Controller_Request_Http::SCHEME_HTTPS . "://iep.esucc.org/srs.php?area=student&sub=student&student=".$dbStudent['id_student']."&option=edit";
            $this->_redirector->gotoUrl($url);
            exit;
            }
             */


        } elseif($this->getRequest()->getParam('viewOnly')) {
            throw new Exception('Viewing a new student page is not allowed. Please use the edit action.');
        }


        /**
         * came from view
         */
        if($this->getRequest()->getParam('viewOnly')) {
            // enforce view mode by changing the view helper on all elements to formNote
            $this->convertFormToView ( $form );
            $form->removeElement('submit');

            $form->setMultiOptionsAndConditionalFields(isset($dbStudent)?$dbStudent:array(), $this->usersession);
            $form->populate(isset($dbStudent)?$dbStudent:array());

        } elseif ($this->getRequest()->isPost()) {
            /**
             * set custom multi options / must be done to validate selects
             */
            $form->setMultiOptionsAndConditionalFields($this->getRequest()->getPost(), $this->usersession);
            $formValid = $form->isValid($this->getRequest()->getPost());
            $form->setDisabledValues();
            if($formValid) {
                // valid to save
                $data = $form->getValues();
              
               
                if($data['grade']!='EI 0-2'){
                    $data['id_ser_cord']=NULL;
                    $data['id_ei_case_mgr']=NULL;
                   // writevar($data,'this is the data after');
                }
                
                // submitted update address is valid and is different from database
                if(''!=$postData['confirm_email'] && $postData['confirm_email']==$postData['email_address']) {
                    // update email
                    unset($data['confirm_email']);
                } else {
                    unset($data['email_address']);
                    unset($data['confirm_email']);
                }

                $data['id_author_last_mod'] = $this->usersession->sessIdUser;
                if($this->getRequest()->getParam('id_student')) {
                    $where = $stuObject->getAdapter()->quoteInto('id_student = ?', $this->getRequest()->getParam('id_student'));
                    if($dbStudent['status'] != $data['status'] && 'Inactive' == $data['status'] || 'Never Qualified' == $data['status'] ) {
                        $data['id_case_mgr'] = null;
                        $forceRefresh = true;
                    } elseif($dbStudent['status'] != $data['status'] && 'Active' == $data['status']) {
                        $data['sesis_exit_code'] = null;
                        $data['sesis_exit_date'] = null;
                        $forceRefresh = true;
                    }
             //       writevar($data,'this is the data before the update');
                    $stuObject->update($data, $where);
                    if(isset($forceRefresh) && $forceRefresh) {
                        
                        $this->_redirector->gotoSimple('edit', 'student', null, array('id_student' => $this->getRequest()->getParam('id_student')));
                    }
                } else {
                    $data['id_author'] = $this->usersession->sessIdUser;
                    $studentId = $stuObject->insert($data);
                    $this->_redirector->gotoSimple('edit', 'student', null, array('id_student' => $studentId));
                }

            } else {
                // user saved but form is not valid
                // NSSRS ID should be cleared if it's errored
                // this is not done in the isValid fn because this field would
                // not error on the subsequent isValid calls
                $form->getElement('unique_id_state')->setValue(null);

            }

        } else {
            $form->setMultiOptionsAndConditionalFields(isset($dbStudent)?$dbStudent:array(), $this->usersession);
            $form->populate(isset($dbStudent)?$dbStudent:array(
                'address_state' => 'Nebraska'
            ));
        }
      //  $this->writevar1($form,'thisis the form');
        $this->view->form = $form;
        if(count($form->getMessages())) {
            $this->view->errorMessage = 'Record is incomplete, and cannot be saved.';
            $this->view->successMessage = '';
        } else {
            $this->view->errorMessage = '';
            if($this->getRequest()->isPost()) {
                $this->view->successMessage = 'Record is Complete and saved.';
            }
        }
    }

    public function getMostRecentAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        switch ($this->getRequest()->getParam('type')) {
            case 'MDT':
                $model = new Model_Table_Form002();
                $form = array('form002', 'id_form_002');
                break;
            case 'IEP':
                $model = new Model_Table_Form004();
                $form = array('form004', 'id_form_004');
                break;
            case 'IFSP':
                $model = new Model_Table_Form013();
                $form = array('form013', 'id_form_013');
                break;
            case 'Progress Report':
                $model = new Model_Table_Form010();
                $form = array('form010', 'id_form_010');
                break;
        }

        $select = $model->select()
            ->where('id_student = ?', $this->getRequest()->getParam('id_student'))
            ->where('status = ?', 'Draft')
            ->order('finalized_date')
            ->order('timestamp_created')
            ->limit(1);
        $result = $model->fetchRow($select);
        if (!is_null($result)) {
            $result = $result->toArray();
            echo Zend_Json::encode(array('success' => '1', 'url' => '/'.$form[0].'/edit/document/'.$result[$form[1]]));
        } else
            echo Zend_Json::encode(array('success' => '0'));
        exit;
    }

    function addCollectionAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if(!$this->getRequest()->getParam('collection')) {
            echo Zend_Json::encode(array('success' => 0));
            exit;
        }
        $studentCollection = new App_Collection_Student();
        $collectionExists = $studentCollection->collectionNameExists($this->usersession->sessIdUser, $this->getRequest()->getParam('collection'));

        if(!$collectionExists) {
            $newCollection = $studentCollection->addCollection($this->usersession->sessIdUser, $this->getRequest()->getParam('collection'));
            echo Zend_Json::encode(array('success' => 1, 'data' => array('id', $newCollection)));
        } elseif($this->getRequest()->getParam('overwrite')) {
            $studentCollection->removeAllCollectionItems($this->usersession->sessIdUser, $this->getRequest()->getParam('collection'));
            echo Zend_Json::encode(array('success' => 1, 'data' => array('itemsRemoved', $studentCollection)));
        } else {
            echo Zend_Json::encode(array('success' => 0, 'errorMessage' => 'A collection named "'.$this->getRequest()->getParam('collection').'" already exists. Select overwrite to clear the collection.'));
        }
        exit;
    }
    function removeCollectionAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if(!$this->getRequest()->getParam('collection_id')) {
            echo Zend_Json::encode(array('success' => 0));
            exit;
        }
        $studentCollection = new App_Collection_Student();
        $result = $studentCollection->removeCollectionById($this->usersession->sessIdUser, $this->getRequest()->getParam('collection_id'));
        if($result) {
            echo Zend_Json::encode(array('success' => 1, 'data' => array('result', $result)));
        } else {
            echo Zend_Json::encode(array('success' => 0, 'errorMessage' => 'An error occured while trying to remove this collection.'));
        }
        exit;
    }

    function groupAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        switch ($this->getRequest()->getParam('collection')) {
            case null;
                $groupName = 'student';
                break;
            default:
                $groupName = $this->getRequest()->getParam('collection');
        }

        $additionalFields = array(
            'name_case_mgr' => 'name_case_mgr',
            'id_case_mgr' => 'id_case_mgr',
            'id_case_mgr' => 'id_case_mgr',
            'team_member_names' => 'team_member_names',
        );

        /**
         * collection of students
         */
        $studentCollection = new App_Collection_Student();
        $collectionItems = $studentCollection->getNames($this->usersession->sessIdUser, $groupName, isset($additionalFields)?$additionalFields:array());

//        Zend_Debug::dump($collectionItems);die;
        if (!is_null($collectionItems)) {
            echo Zend_Json::encode(array('success' => '1', 'items'=>$collectionItems));
        } else
            echo Zend_Json::encode(array('success' => '0'));
        exit;
    }
    function getMyCollectionsAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $additionalFields = array(
            'name_case_mgr' => 'name_case_mgr',
            'id_case_mgr' => 'id_case_mgr',
            'id_case_mgr' => 'id_case_mgr',
            'team_member_names' => 'team_member_names',
        );

        /**
         * collection of students
         */
        $studentCollection = new App_Collection_Student();
        $collections = $studentCollection->getMyCollections($this->usersession->sessIdUser);
        if(0 == count($collections)) {
            // insert default collection

        }
        if (!is_null($collections)) {
            echo Zend_Json::encode(array('success' => '1', 'data' => array('collections'=>$collections->toArray())));
        } else
            echo Zend_Json::encode(array('success' => '0'));
        exit;
    }

    /**
     * function used by the checkboxes in the collection functionality
     * designed to be called via ajax and update global lists
     * add student to the collection
     */
    function groupAddAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        switch ($this->getRequest()->getParam('collection')) {
            case null:
                $groupName = 'student';
                break;
            default:
                $groupName = $this->getRequest()->getParam('collection');
        }
        if($this->getRequest()->getParam('id')) {
            // we have an id to add
            /**
             * collection of students
             */
            $studentCollection = new App_Collection_Student();
            $collectionItems = $studentCollection->add($this->usersession->sessIdUser, $this->getRequest()->getParam('id'), $groupName);
        }
        if (!is_null($collectionItems)) {
            echo Zend_Json::encode(array('success' => '1', 'items'=>$collectionItems));
        } else
            echo Zend_Json::encode(array('success' => '0'));
        exit;
    }
    /**
     * function used by the checkboxes in the collection functionality
     * designed to be called via ajax and update global lists
     * remove student from the collection
     */
    function groupRemoveAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if (is_null($this->getRequest()->getParam('collection'))) {
            $groupName = 'student';
        } else {
            $groupName = $this->getRequest()->getParam('collection');
        }
        $studentId = $this->getRequest()->getParam('id');
        if(''!=$studentId) {
            // we have an id to remove
            /**
             * collection of students
             */
            $studentCollection = new App_Collection_Student();
            $collectionItems = $studentCollection->remove($this->usersession->sessIdUser, $studentId, $groupName);
        }
        if (!is_null($collectionItems)) {
            echo Zend_Json::encode(array('success' => '1', 'items'=>$collectionItems));
        } else
            echo Zend_Json::encode(array('success' => '0'));
        exit;
    }

    function dojoeditorAction()
    {
        return parent::dojoeditorAction();
    }

    function doGroupAction() {
       // include("Writeit.php");
        $errorMessage = '';
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if(!$this->getRequest()->getParam('collection')) {
            echo Zend_Json::encode(array('success' => '0'));
            exit;
        }
        $groupName = $this->getRequest()->getParam('collection');
        $studentCollectionObj = new App_Collection_Student();
      
        $studentCollection = $studentCollectionObj->getNames($this->usersession->sessIdUser, $groupName);
        // writevar($studentCollection,'this is the collection of what we need');
        // This returns the students in the list array format with full name and as 'name' and id as 'id' ass array
      
        switch ($this->getRequest()->getParam('run')) {
            case 'print':
                /**
                 * job queue print action
                 */
                $filePath = APPLICATION_PATH.'/../tmp_printing/' . $this->usersession->sessIdUser.'-'.strtotime('now').'.pdf';
                $formList = $this->buildFormList($this->getRequest()->getParam('formNum'), $this->getRequest()->getParam('printType'), $studentCollection);
                if(count($formList)>0) {
                    $job = $this->printCollection($filePath, $formList);
                   // writevar($formList,'this is a list of the jobs'); // this will print out an array of students-id,id_school,distric,county and the form number 
                    $result = true;
                } else {
                    $errorMessage = "No forms found.";
                    $result = false;
                }
               // writevar($filePath,'this is the file path'); // It gets the file path correct
                break;
          
            case 'transfer':
                /**
                 * transfer students action
                 */  
                
                if(count($studentCollection)>0) {

                    $transferCollectionObj = new App_Student_Transfer_Collection();
                    $transferCollectionObj->transferCollection(
                        $studentCollection,
                        $this->getRequest()->getParam('county'),
                        $this->getRequest()->getParam('district'),
                        $this->getRequest()->getParam('school'),
                        $this->usersession->sessIdUser,
                        $this->getRequest()->getParam('autoMoveForAsmOrBetter')
                    );
                   echo Zend_Json::encode(array('success' => '1', 'message'=>$transferCollectionObj->notificationMessage));
                } else {
                    $errorMessage = "No students found.";
                    echo Zend_Json::encode(array('success' => '0', 'errorMessage'=>$errorMessage));
                }
                exit;
                break;
            default:
                $result = false;
        }

      //   writevar($result,'this is the value of t/f result'); this comes out true is students are there.
        
        if ($result) {
              //  writevar($filePath,'this is the filepath before jason encode');// it is set at this point
            //  writevar($job,'this is the job');  //this prints out an integer it was 184 in our case with 3 kids
            if(isset($filePath)) {
               echo Zend_Json::encode(array('success' => '1', 'job'=>$job, 'fileName'=>basename($filePath)));
            } else {
                echo Zend_Json::encode(array('success' => '1', 'job'=>$job));
            }
        } else
            echo Zend_Json::encode(array('success' => '0', 'errorMessage'=>$errorMessage));
        exit;
    }   // and so ends the function

    function printCollection($filePath, $formList) {
        
        $queue = new ZendJobQueue();
        // check that Job Queue is running
        if ($queue->isJobQueueDaemonRunning() && count($formList)>0) {
            $params = array(
                "formList" => $formList,
                "filePath" => $filePath,
            );
            
            $options = array(
                "priority" => ZendJobQueue::PRIORITY_NORMAL,
                "name" => 'Print Form Collection'
            );
            $config = Zend_Registry::get('config');
            $jobID = $queue->createHttpJob($config->job_queue_root.'/jobqueue/group-print/print-list-of-forms.php', $params, $options);
//            App_Vlog::file($config->job_queue_root.'/jobqueue/group-print/print-list-of-forms.php');
            //  writevar($params,'this is the params'); //Just returns an interger number for the id which was 187 in our case
            return $jobID;
        }
        return false;
    }

    public function buildFormList($formNumber, $type, $studentCollection)
    {
        switch ($formNumber) {
            case '004':
                $sortField = 'date_conference desc';
                break;
            case '004s':
                $formNumber = '004';
                $sortField = 'date_conference desc';
                $summary = true;
                $type = 'mostRecentFinal';
                break;
            default:
                $sortField = 'date_notice desc';
        }

        $formList = array();
        $userSessionId = $this->usersession->sessIdUser;
        $keyName = 'id_form_' . $formNumber;
        $modelName = 'Model_Table_Form' . $formNumber;
        $modelForm = new $modelName;

        foreach ($studentCollection as $collectionItem) {
            $studentId = $collectionItem['id'];
            switch ($type) {
                case 'mostRecentFinal':
                    $form = $modelForm->mostRecentFinalForm($studentId, $sortField);
                    break;
                case 'mostRecentDraft':
                    $form = $modelForm->mostRecentDraftForm($studentId, $sortField);
                    break;
                case 'mostRecent':
                    $form = $modelForm->mostRecentForm($studentId, $sortField);
                    break;
            }
            if (!is_null($form)) {
                $configuration = array(
                    'id' => $form[$keyName],
                    'version_number' => $form['version_number'],
                    'id_student' => $form['id_student'],
                    'id_county' => $form['id_county'],
                    'id_district' => $form['id_district'],
                    'id_school' => $form['id_school'],
                    'formNum' => $formNumber,
                    'sessIdUser' => $userSessionId,
                    'PHPSESSID' => $_COOKIE['PHPSESSID']
                );
                if(isset($summary) && $summary) {
                    if($form['version_number'] < 9) {
                        continue;
                    }
                    $configuration['summary'] = '1';
                }
                $formList[] = $configuration;
            }
        }
        return $formList;
    }


    public function getJobStatusAction() {
        
        $queue = new ZendJobQueue();
        $info = $queue->getJobStatus($this->getRequest()->getParam('id'));
       
        if ($info) {
            echo Zend_Json::encode(array('success' => '1', 'status'=>$info['status'], 'id'=>$this->getRequest()->getParam('id')));
        } else
            echo Zend_Json::encode(array('success' => '0', 'id'=>$this->getRequest()->getParam('id')));
        exit;

    }
    public function getJobDocumentAction() {
        
        $fileName = $this->getRequest()->getParam('fileName');
       $tmpPdfPath = APPLICATION_PATH.'/../tmp_printing/' . $fileName;
       
        /*
        * Issue SRSZF-287 Mod.  Download PDF instead of printing
        * anything to screen.
        */
        header("Cache-Control: public, must-revalidate");
        header("Pragma: hack");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment;filename=' . basename($tmpPdfPath));
        header("Content-Type: application/pdf");
        header("Content-Transfer-Encoding: binary");
        header('Content-Length: ' . filesize($tmpPdfPath));
        readfile($tmpPdfPath);
        exit();
    }
    public function confirmTransferAction() {
        $transferRequestsTable = new Model_Table_TransferRequest();

        if($this->getRequest()->getParam('id_transfer_request')) {
            if('confirm' == $this->getRequest()->getParam('confirmTransferAction')) {
                $transferRequestsTable->confirmTransferRequest($this->getRequest()->getParam('id_transfer_request'));

            } elseif('delete' == $this->getRequest()->getParam('confirmTransferAction')) {
                $transferRequestsTable->deleteTransferRequest($this->getRequest()->getParam('id_transfer_request'));
            }
            $this->redirect('student/confirm-transfer');
        }
        $this->view->myTransferRequests = $transferRequestsTable->getMyTransferRequests(array('initiate'));
        $this->view->myRecentlyChangedTransferRequests = $transferRequestsTable->getMyTransferRequests(array('Cancelled', 'Confirmed'), 20);
    }
    public function transferCenterAction() {
        $transferRequestsTable = new Model_Table_TransferRequest();
        $this->view->myTransferRequests = $transferRequestsTable->getMyTransferRequests(array('initiate'));
    }

    public function testSesisAction() {
        $this->limitToAdminAccess();
        $sesisObj = new App_Student_Sesis();
        $sesisData = $sesisObj->sesis_collection($this->getRequest()->getParam('id_student'));
        Zend_Debug::dump($sesisData);die;
    }

    /**
     * @param $studentArr
     * @return array
     */
    private function getPrimaryDisabilityDisplay($id_student)
    {
        $form002 = new Form_Form002();
        $form022 = new Form_Form022();

        $form002Obj = new Model_Table_Form002();
        $form022Obj = new Model_Table_Form022();

        $mostRecent002 = $form002Obj->mostRecentFinalForm($id_student);
        $mostRecent022 = $form022Obj->mostRecentFinalForm($id_student);

        $form002Options = $form002->getPrimaryDisabilityOptions();
        $form022Options = $form022->getPrimaryDisability_version1();

        if ($mostRecent002 && $mostRecent022) {
            if (strtotime($mostRecent002['date_mdt']) > strtotime($mostRecent022['date_mdt'])) {
                $optionValue = $mostRecent002['disability_primary'];
                if(isset($optionValue) && isset($form002Options[$optionValue])) {
                    return $form002Options[$optionValue];
                }
            } else {
                $optionValue = $mostRecent022['disability_primary'];
                if(isset($optionValue) && isset($form022Options[$optionValue])) {
                    return $form022Options[$optionValue];
                }
            }
        } else if ($mostRecent002) {
            $optionValue = $mostRecent002['disability_primary'];
            if(isset($optionValue) && isset($form002Options[$optionValue])) {
                return $form002Options[$optionValue];
            }
        } elseif ($mostRecent022) {
            $optionValue = $mostRecent022['disability_primary'];
            if(isset($optionValue) && isset($form022Options[$optionValue])) {
                return $form022Options[$optionValue];
            }
        }

        return 'Primary Disability not found.';
    }
}
