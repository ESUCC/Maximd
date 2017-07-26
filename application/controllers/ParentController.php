<?php
class ParentController extends My_Form_AbstractFormController
{
    public function init()
    { 
        // Auth check
        $this->identity = My_Helper_Auth::check();
        if($this->identity == false) { $this->_redirect('/'); }
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }
    public function indexAction() { }
   
    public function searchAction() 
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $session = new Zend_Session_Namespace('searchParentForm');
        $parentQuery = new Model_Table_Parent();
	      // Read all Privileges
        $x = 0;
        $priv = Array();
        while ($x < count($this->usersession->user->privs))
        {
            $priv[$x] = array( key($this->usersession->user->privs), $this->usersession->user->privs[$x]['id_school'] );
            $x++;
        }
        if (intval($this->getRequest()->getParam('nb') * 1) == 1) 
        {
            $options = array();
            if ($session->id_student > 0)
                $options['id_student'] = $session->id_student;
	          else 
                $options['id_student'] = 0;
            $result = $parentQuery->studentDetails($options['id_student']); // Get result
	          // Get Priv
	          $priv_student = 0;
	          foreach($priv as $priv_array)
            {
  	            foreach($priv_array as $key => $value)
                {
	                  if ( (($key >= 0 && $key <= 3) && $value == "") || (($key >= 4) && $value == $result["id_school"]) ) 
                        $this->view->priv_student = 1;
    	          }
	          }
            $this->_helper->layout()->disableLayout();
	          $options['page'] = intval($this->getRequest()->getParam('page') * 1);
            $options['maxRecs'] = 25; // default 25
          	// Generate KEY by district and county
          	if (!isset($options['key']) || !isset($session->searchKey)) 
            {
                $options['key'] = "parents_".$options['id_student'];
                $session->searchKey = $options['key'];
            }
            $result = $parentQuery->parentList($options); // Get result
            $this->view->id_student = $options['id_student'];
            $this->view->page = $options['page'];
            $this->view->maxResultsExceeded = $result[0];  // Max limit rows exceeded
            $this->view->paginator = $result[1];
            $this->view->key = $options['key'];
            $this->view->maxRecs = $options['maxRecs'];
            $this->view->resultCount = $result[3];
            $this->view->results = $result[1];
            // Generate pagination
            if(count($result[3]) > 0) 
            {
          	    $this->view->page = $options['page'];
          	    $paginator = Zend_Paginator::factory($this->view->resultCount);
          	    $paginator->setItemCountPerPage($options['maxRecs']);		// set number of pages in pagenator
          	    $paginator->setCurrentPageNumber($options['page']);			// put current page number into the pagenator
          	    $this->view->paginator = $paginator;					          // put the pagenator in the view
            }
            echo $this->view->render('parent/list.phtml');
        }
        else 
        {
            //	$OptionsMenu = new Zend_View_Helper_StudentOptions();
            //	$result = $OptionsMenu->StudentOptions($id_student, $id_district = false);
            //	print $result;
            // Read all Privileges
            $x = 0;
            $priv = Array();
            while ($x < count($this->usersession->user->privs))
            {
                $priv[$x] = array( key($this->usersession->user->privs), $this->usersession->user->privs[$x]['id_school'] );
                $x++;
            }
	    $id_student = intval($this->getRequest()->getParam('id_student') * 1);
            $result = $parentQuery->studentDetails($id_student); // Get result
	    // Get Priv
	    $priv_student = 0;
	    foreach($priv as $priv_array)
            {
  	        foreach($priv_array as $key => $value) 
                {
	              if ( (($key >= 0 && $key <= 3) && $value == "") || (($key >= 4) && $value == $result["id_school"]) ) 
                      $this->view->priv_student = 1;
    	          }
	    }
            $this->view->id_student = $id_student;
            $this->view->name_first = $result['name_first'];
            $this->view->name_last = $result['name_last'];
            $session->id_student = ($id_student > 0) ? $id_student : 0;
            echo $this->view->render('parent/search.phtml');
        }
    }
    public function viewAction()
    {
        $parentQuery = new Model_Table_Parent();
        $options = array();
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $options['id_guardian'] = $this->getRequest()->getParam('id_guardian');
        $options['id_student'] = $this->getRequest()->getParam('id_student');
        $result = $parentQuery->parentView($options); // Get Parent View result
        $result_student = $parentQuery->studentDetails($options['id_student']); // Get result
        $this->view->student = $result_student['name_first']." ".$result_student['name_last']." (".$options['id_student'].")";
        $this->view->result = $result;
        $this->view->print = $this->getRequest()->getParam('print');
        $unit = $this->getRequest()->getParam('parent');
        // Current Year
        $datetime = new DateTime();
        $this->view->CurrentSchoolYear = $datetime->format('Y');
        $this->view->fullname = $this->usersession->user->user['name_first'] . " " . $this->usersession->user->user['name_middle'] . " " . $this->usersession->user->user['name_last'];
        if ($this->view->print == 1) 
        {
            echo $this->view->render('parent/header.phtml');
            echo $this->view->render('parent/view.phtml');
            echo $this->view->render('parent/footer.phtml');
	} 
        else 
            echo $this->view->render('parent/view.phtml');
    }
    public function editAction()
    {
        $parentQuery = new Model_Table_Parent();
        $options = array();
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $options['id_guardian'] = intval($this->getRequest()->getParam('id_guardian') * 1);
        $options['id_student'] = intval($this->getRequest()->getParam('id_student') * 1);
        // ----   Read all Privileges ------------------------
        $x = 0;
        $priv = Array();
        while ($x < count($this->usersession->user->privs))
        {
            $priv[$x] = array( key($this->usersession->user->privs), $this->usersession->user->privs[$x]['id_school'] );
            $x++;
        }
 	$result = $parentQuery->studentDetails($options['id_student']); // Get result
	// Get Priv
	$priv_student = 0;
	foreach($priv as $priv_array)
        {
  	    foreach($priv_array as $key => $value) 
            {
	          if ( (($key >= 0 && $key <= 3) && $value == "") || (($key >= 4) && $value == $result["id_school"]) ) 
                  $priv_student = 1;
    	    }
	}
        // --------------------------------------------------------
      
        if ( $options['id_guardian'] > 0) 
        {
            $result = $parentQuery->parentView($options); // Get Parent View result
            $this->view->result = $result;
        }
        $result_student = $parentQuery->studentDetails($options['id_student']); // Get result
        $this->view->student = $result_student['name_first']." ".$result_student['name_last']." (".$options['id_student'].")";
        $this->view->print = $this->getRequest()->getParam('print');
        $this->view->id_guardian = $options['id_guardian'];
        $this->view->id_student = $options['id_student'];
        $unit = $this->getRequest()->getParam('parent');
        // Current Year
        $datetime = new DateTime();
        $this->view->CurrentSchoolYear = $datetime->format('Y');
        $this->view->fullname = $this->usersession->user->user['name_first'] . " " . $this->usersession->user->user['name_middle'] . " " . $this->usersession->user->user['name_last'];
        if ($priv_student == 1) 
            echo $this->view->render('parent/edit.phtml');
    }
    public function saveAction ()
    {
        $parentQuery = new Model_Table_Parent();
        
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->contextSwitch()->addActionContext('getJsonResponse', array('json'))->initContext();
	$options = Array();
	$options_reports = Array();
        $request = $this->_request->getParams();
	foreach ($request as $nameField => $valueField)
        {
	  $value = urldecode($nameField);
	  $options[$value] = urldecode(strip_tags($valueField));
	}
        // ----   Read all Privileges ------------------------
        $x = 0;
        $priv = Array();
        while ($x < count($this->usersession->user->privs))
        {
            $priv[$x] = array( key($this->usersession->user->privs), $this->usersession->user->privs[$x]['id_school'] );
            $x++;
        }
 	$result = $parentQuery->studentDetails($options['id_student']); // Get result
	// Get Priv
	$priv_student = 0;
	foreach($priv as $priv_array)
        {
  	    foreach($priv_array as $key => $value) 
            {
	          if ( (($key >= 0 && $key <= 3) && $value == "") || (($key >= 4) && $value == $result["id_school"]) ) 
                  $priv_student = 1;
    	    }
	}
        // --------------------------------------------------------
        if ($priv_student == 1) 
        {
            $session = new Zend_Session_Namespace('searchParentForm');
            $options['key'] = $session->searchKey;
            // Validate Data from form
            $validator_digital = new Zend_Validate_Regex('/\d+$/');
            $validator_status = new Zend_Validate_Regex('/^([A-Za-z]+)$/');
            $validator_state = new Zend_Validate_Regex('/\w{2}$/');
            $validator_zip = new Zend_Validate_Regex('/\d{5}-\d{4}$|^\d{5}$/');
            $validator_phone = new Zend_Validate_Regex('/^\d{3}-\d{3}-\d{4}$/');
            $validator_email = new Zend_Validate_Regex('/\S+@\S+\.\S+/');
            if (intval($options["id_guardian"] * 1) >= 0 && 
                intval($options["id_student"] * 1) > 0 && 
                strlen($options["name_first"]) >= 2 && 
                strlen($options["name_last"]) >= 2 && 
                strlen($options["address_city"]) > 2 && 
                strlen($options["address_street1"]) > 2 && 
                strlen($options["relation_to_child"]) > 2 && 
                strlen($options["password"]) > 2 && 
                strlen($options["user_name"]) > 2 && 
                $validator_state->isValid( $options["address_state"] ) &&   
                ($validator_phone->isValid( $options["phone_home"] ) || strlen( $options["phone_home"] ) == 0) && 
                ($validator_phone->isValid( $options["phone_work"] ) || strlen( $options["phone_work"] ) == 0)  && 
                $validator_zip->isValid( $options["address_zip"] ) &&
                ($validator_email->isValid( $options["email_address"] ) || strlen( $options["email_address"] ) == 0) ) 
            {
                $parentQuery = new Model_Table_Parent();
                $result = $parentQuery->parentSave($options);  // Save data
                $msg = 'Saved!';
                $err = 0;
                $id_guardian = $result;
            }  
            else 
            { 
                $msg = 'Please, fill all Field with asterisk "*"';
                $err = 1;
            }
 
            $jsonData = array ( 'msg' => $msg, 'err' => $err, 'id_guardian' => $id_guardian ); // your json response
            $this->_helper->json->sendJson($jsonData);
        }
    }
}