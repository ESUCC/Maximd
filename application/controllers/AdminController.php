<?php
class AdminController extends My_Form_AbstractFormController
{
    public function init()
    {
	// Auth check
        $this->identity = My_Helper_Auth::check();
        if($this->identity == false) { $this->_redirect('/'); }
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }
 
    public function serverAction() 
    {
       $this->_helper->viewRenderer->setNoRender(true);
       $options = array();

       $options['id_user'] = $this->usersession->user->user['id_personnel'];
       $adminQuery = new Model_Table_Admin();
       $result = $adminQuery->checkPrivs($options); // Get result

       if ($result['class'] == 1 || $result['class'] == 0) { // Check privs
         echo $this->view->render('admin/server.phtml');
       } else echo $this->view->render('admin/access-denied.phtml');

    } 

    public function sessionsAction() 
    {
     $options = array();

     $this->_helper->viewRenderer->setNoRender(true);

     $options['id_user'] = $this->usersession->user->user['id_personnel'];

     $session = new Zend_Session_Namespace('sessionList');
     $adminQuery = new Model_Table_Admin();
     $result = $adminQuery->checkPrivs($options); // Get result

     if ($result['class'] == 1 || $result['class'] == 0) { // Check privs

	if (intval($this->getRequest()->getParam('nb') * 1) == 1){
            $this->_helper->layout()->disableLayout(); 	

        $options['maxRecs'] = 25; // default 25

	$session_date = preg_replace('/_/', '-', $this->getRequest()->getParam('session_date'));

        if (preg_match("/([0-9]{2})-([0-9]{2})-([0-9]{4})/", $session_date))
                    $options['session_date'] = $session_date;
            else 
                    $options['session_date'] = (isset($session->sessionDate) && isset($session->sessionDate) > 0) ? $session->sessionDate : date("m-d-Y");

	$options['page'] = intval($this->getRequest()->getParam('page') * 1);

	if ($options['page'] == 0 && $session->sessionPage > 0 && $session->sessionDate == $options['session_date']) { // Reload page
            $options['page'] = $session->sessionPage; // number page
            $options['session_date'] = $session->sessionDate;
	  } else { // Change date or page
            $session->sessionPage = $options['page']; // number page
            $session->sessionDate = $options['session_date'];
         }

	$result = $adminQuery->sessionList($options); // Get result

        $this->view->page = $options['page'];
        $this->view->maxRecs = $options['maxRecs'];
        $this->view->maxResultsExceeded = $result[0];  // Max limit rows exceeded
        $this->view->paginator = $result[1];
        $this->view->resultCount = $result[2];
        $this->view->usersCount = $result[3];
        $this->view->results = $result[1];

	// Generate pagination
        if(count($result[3]) > 0) {
	    $this->view->page = $options['page'];
	    $paginator = Zend_Paginator::factory($this->view->resultCount);
	    $paginator->setItemCountPerPage($options['maxRecs']);		// set number of pages in pagenator
	    $paginator->setCurrentPageNumber($options['page']);			// put current page number into the pagenator
	    $this->view->paginator=$paginator;					// put the pagenator in the view
        }

         echo $this->view->render('admin/session_list.phtml');

      }  else {

   	    $this->view->sessionDate = (isset($session->sessionDate)) ? preg_replace('/-/', '/', $session->sessionDate) : date('m/d/Y');
            echo $this->view->render('admin/session.phtml');

      }

    } else echo $this->view->render('admin/access-denied.phtml');

  }

    public function announcementsAction() 
    {
       $this->_helper->viewRenderer->setNoRender(true);
       $options = array();

       $options['id_user'] = $this->usersession->user->user['id_personnel'];
       $adminQuery = new Model_Table_Admin();
       $result = $adminQuery->checkPrivs($options); // Get result

       if ($result['class'] == 1 || $result['class'] == 0) { // Check privs

         echo $this->view->render('admin/announcements.phtml');

       } else echo $this->view->render('admin/access-denied.phtml');

    }

    public function announcementssaveAction() 
    {
       $options = array();
       $options['id_user'] = $this->usersession->user->user['id_personnel'];

       $adminQuery = new Model_Table_Admin();
       $result = $adminQuery->checkPrivs($options); // Get result
       if ($result['class'] == 1 || $result['class'] == 0) { // Check privs

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->getRequest();

        $options['msg_title'] = urldecode ( $request->msg_title );
        $options['message_text'] = urldecode ( $request->message_text );
        $options['create_date'] = date("m-d-Y");
        $options['display_until_date'] = urldecode ( $request->display_until_date );
        $options['msg_type'] = urldecode ( $request->msg_type );

         if ($options['msg_title'] != "" && $options['message_text'] != "" && $options['create_date'] != "" && $options['display_until_date'] != "" && $options['msg_type'] != "")
         {
            $adminQuery->announcementAdd($options);
            if ($options['msg_type'] == "Announcement") $msg = "Announcement was added!"; else $msg = "Warning was added!";
           } else {
            $msg = "no data";
         }

        $jsonData = array ( 'msg' => $msg );

        $this->_helper->json->sendJson($jsonData);
       }

       return;

    }

    public function reportingAction() 
    {
       $options = array();

       $this->_helper->viewRenderer->setNoRender(true);
       $options['id_user'] = $this->usersession->user->user['id_personnel'];
       $adminQuery = new Model_Table_Admin();
       $result = $adminQuery->checkPrivs($options); // Get result

       if ($result['class'] == 1 || $result['class'] == 0) { // Check privs
         $result = $adminQuery->reportingLoad();
         $this->view->result = $result;
         echo $this->view->render('admin/reporting.phtml');

       } else echo $this->view->render('admin/access-denied.phtml');

    }


    public function reportingsaveAction() 
    {
       $options = array();

       $options['id_user'] = $this->usersession->user->user['id_personnel'];

       $adminQuery = new Model_Table_Admin();
       $result = $adminQuery->checkPrivs($options); // Get result
       if ($result['class'] == 1 || $result['class'] == 0) { // Check privs

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->getRequest();

        $options['nssrs_submition_date'] = urldecode ( $request->nssrs_submition_date );
        $options['nssrs_school_year'] = urldecode ( $request->nssrs_school_year );
        $options['october_cutoff'] = urldecode ( $request->october_cutoff );
        $options['transfer_report_cutoff'] = urldecode ( $request->transfer_report_cutoff );


         if ($options['nssrs_submition_date'] != "" && $options['nssrs_school_year'] != "" && $options['october_cutoff'] != "" && $options['transfer_report_cutoff'] != "")
         {
            $adminQuery->reportingSave($options);
            $msg = "Saved!";
           } else {
            $msg = "no data";
         }

        $jsonData = array ( 'msg' => $msg );

        $this->_helper->json->sendJson($jsonData);
       }

       return;

    }


    public function dataadminAction() 
    {
       $options = array();

       $this->_helper->viewRenderer->setNoRender(true);

       $options['id_user'] = $this->usersession->user->user['id_personnel'];
       $adminQuery = new Model_Table_Admin();
       $result = $adminQuery->checkPrivs($options); // Get result

       if ($result['class'] == 1 || $result['class'] == 0) { // Check privs

         echo $this->view->render('admin/dataadmin.phtml');

       } else echo $this->view->render('admin/access-denied.phtml');

    }

    public function dataadminformAction() 
    {
       $options = array();

       $this->_helper->layout()->disableLayout();
       $this->_helper->viewRenderer->setNoRender(true);
       
       $request = $this->getRequest();
       $options['id_user'] = $this->usersession->user->user['id_personnel'];
       $adminQuery = new Model_Table_Admin();
       $result = $adminQuery->checkPrivs($options); // Get result

       if ($result['class'] == 1 || $result['class'] == 0) { // Check privs

        $result = $adminQuery->countyList(); // Get result
        $this->view->result = $result[0];

        switch($request->do) {
          case "createdistrict": echo $this->view->render('admin/createdistrict.phtml');
                  break;
          case "createschool": echo $this->view->render('admin/createschool.phtml');
                  break;
          case "trasferteacher": echo $this->view->render('admin/trasferteacher.phtml');
                  break;
        }
       
      } else echo $this->view->render('admin/access-denied.phtml');
    }

    public function dataadminsaveAction() 
    {
       $this->_helper->layout()->disableLayout();
       $this->_helper->viewRenderer->setNoRender(true);

       $options = array();
       $request = $this->getRequest()->getPost();

       $options['id_user'] = $this->usersession->user->user['id_personnel'];
       $adminQuery = new Model_Table_Admin();
       $result = $adminQuery->checkPrivs($options); // Get result

       if ($result['class'] == 1 || $result['class'] == 0) { // Check privs

          foreach ($request as $nameField => $valueField){
             $value = urldecode($nameField);
             $options[$value] = urldecode(strip_tags($valueField));
          }

         // Validate Data from form
         $validator_state = new Zend_Validate_Regex('/\w{2}$/');
         $validator_zip = new Zend_Validate_Regex('/\d{5}-\d{4}$|^\d{5}$/');

         if (
              ($options["doaction"] == "createdistrict" && 
               $options["id_county"] != "" && 
               $options["name_district"] != "" && 
               $options["address_street1"] != "" && 
               $options["address_city"] != "" && 
               $validator_state->isValid($options["address_state"]) && 
               $validator_zip->isValid($options["address_zip"])) ||
              ($options["doaction"] == "createschool" && 
               $options["id_county"] != "" && 
               $options["id_district"] != "" && 
               $options["name_school"] != "" && 
               $options["address_street1"] != "" && 
               $options["address_city"] != "" && 
               $validator_state->isValid($options["address_state"]) && 
               $validator_zip->isValid($options["address_zip"]))
            )
         {

            $adminQuery->dataadminAdd($options);

            if ($options['doaction']  == "createdistrict") $msg = "District was added!"; else if ($options['doaction']  == "createschool") $msg = "School was added!"; else $msg = "Error...";
           } else {
            $msg = "no data";
         }

        $jsonData = array ( 'msg' => $msg );

        $this->_helper->json->sendJson($jsonData);
        }

       return;

    }

    public function districtlistAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $request = $this->getRequest();
        $adminQuery = new Model_Table_Admin();
        $result = $adminQuery->districtList($request->id_county); // Get result
        $this->_helper->json->sendJson($result[0]);
        return;
    }



}