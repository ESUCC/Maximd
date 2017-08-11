<?php
class SchoolController extends My_Form_AbstractFormController
{
    public function init()
    {
	// Auth check
        $this->identity = My_Helper_Auth::check();
        if($this->identity == false) { $this->_redirect('/'); }
        $this->_redirector = $this->_helper->getHelper('Redirector');

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

    public function searchAction() 
    {
       // include("Writeit.php");
        $options = array();
        $privs = array();

        $this->_helper->viewRenderer->setNoRender(true);

	if (intval($this->getRequest()->getParam('nb') * 1) == 1){
            $this->_helper->layout()->disableLayout(); 	
	
	$options['page'] = intval($this->getRequest()->getParam('page') * 1);
	$options['sort'] = intval($this->getRequest()->getParam('sort') * 1);
        $options['maxRecs'] = 25; // default 25

        $options['districtUser'] = $this->usersession->user->user['id_district'];
        $this->countyID = $this->usersession->user->user['id_district'];
        $options['countyUser'] = $this->usersession->user->user['id_county'];
	    $userID = $this->usersession->user->user['id_personnel'];


	// Check Permissions
        $options['privsAdmin'] = count($this->usersession->user->privs);
   //  $this->writevar1($options,'this is the options--privsAdmin');
    //    writevar($userID,'this is the user id');
	$x=0;
	while ($x < count($this->usersession->user->privs))
	{
	    $privs[$x] = array($this->usersession->user->privs[$x]['id_district'], 
	        $this->usersession->user->privs[$x]['id_county'],
	       // $this->usersession->user->privs[$x]['id_school'],
	        $this->usersession->user->privs[$x]['class']);
	    $x++;
	}
    //   $this->writevar1($privs,'these are the privs');
        $options['privsUser'] = $privs;
    //    $this->writevar1($options,'these are the options');
       
        $session = new Zend_Session_Namespace('searchSchoolForm');
     //   writevar($session,'this is the session search page');
        
        if (isset($session->searchPage) && $session->searchPage > 0 && $options['page'] == 0) 
           $options['page'] = $session->searchPage; 
         else {
		if ($options['page'] == 0) $options['page'] = 1;
                $session->searchPage = $options['page']; // number page
         }
        if (isset($session->searchSort) && $session->searchSort > 0 && $options['sort'] == 0) 
           $options['sort'] = $session->searchSort; 
         else {
		if ($options['sort'] == 0) $options['sort'] = 11;
                $session->searchSort = $options['sort']; // Sort method
         }

	// Generate KEY by district and county
	if (!isset($options['key']) || !isset($session->searchKey)) {
             $options['key'] = $options['countyUser'].$options['districtUser'].$userID.$options['sort'];
             $session->searchKey = $options['key'];
        }


	$schoolQuery = new Model_Table_School();
	$result = $schoolQuery->schoolList($options); // Get result

        $this->view->page = $options['page'];
        $this->view->sort = $options['sort'];
        $this->view->maxResultsExceeded = $result[0];  // Max limit rows exceeded
        $this->view->paginator = $result[1];
        $this->view->key = $options['key'];
        $this->view->resultCount = $result[3];
        $this->view->results = $result[1];

	// Generate pagination
        if(count($result[3]) > 0) {
	    $this->view->page = $options['page'];
	    $paginator = Zend_Paginator::factory($this->view->resultCount);
	    $paginator->setItemCountPerPage($options['maxRecs']);		// set number of pages in pagenator
	    $paginator->setCurrentPageNumber($options['page']);			// put current page number into the pagenator
	    $this->view->paginator=$paginator;					// put the pagenator in the view
        }

        echo $this->view->render('school/list.phtml');

      }  else  echo $this->view->render('school/search.phtml');


    }

    public function viewAction()
    {
       $options = array();
       $this->_helper->layout()->disableLayout();
       $this->_helper->viewRenderer->setNoRender(true);
       $this->view->print = $this->getRequest()->getParam('print');

       // Current Year 
       $datetime = new DateTime(); 
       $this->view->CurrentSchoolYear = $datetime->format('Y');
       $this->view->fullname = $this->usersession->user->user['name_first'] . " " . $this->usersession->user->user['name_middle'] . " " . $this->usersession->user->user['name_last'];

// -------- sent query & get school info ------------------
       $options['id_school'] = $this->getRequest()->getParam('id_school');
       $options['id_county'] = $this->getRequest()->getParam('id_county');
       $options['id_district'] = $this->getRequest()->getParam('id_district');
       $options['CurrentSchoolYear'] = $this->view->CurrentSchoolYear;
       $schoolQuery = new Model_Table_School();
       $result = $schoolQuery->schoolView($options); // Get School View result
// --------------------------------------------------------

       $this->view->school = $result[0];
       $this->view->county = $result[1];
       $this->view->district = $result[2];
       $this->view->schoolmng = $result[3];
       $this->view->schoolsprv = $result[4];
       $this->view->reports = $result[5];

       if ($this->view->print == 1) {
           echo $this->view->render('school/header.phtml');
           echo $this->view->render('school/view.phtml');
           echo $this->view->render('school/footer.phtml');
	} else {

   	  $classPriv = $this->usersession->user->user['class'];
   	  $schoolPriv = $this->usersession->user->user['id_school'];
   	  $districtPriv = $this->usersession->user->user['id_district'];
   	  $countyPriv = $this->usersession->user->user['id_county'];

// ------ Check all privs for get access -------------------
         $x=0;
         $schools = Array();
 	 while ($x < count($this->usersession->user->privs))
	 {
	    
	    // school manager (4) and associate school manager (5)
            if ($this->usersession->user->privs[$x]['status'] == 'Active' && 
                $this->usersession->user->privs[$x]['id_county'] == $options['id_county'] && 
		        $this->usersession->user->privs[$x]['id_district'] == $options['id_district'] && 
		        $this->usersession->user->privs[$x]['id_school'] == $options['id_school'] && 
		        $this->usersession->user->privs[$x]['class'] == 4 || $this->usersession->user->privs[$x]['class'] == 5)
                  {  
                    $schools[] = $this->usersession->user->privs[$x]['class'];
	              }
	        
	         
	    
	   $x++;
	 } 
// --------------------------------------------------------
     $editSchool=false;
     
     foreach($_SESSION["user"]["user"]->privs as $privs ) {
         if($privs['class']< 4 && $privs['id_county']==$options['id_county']
             && $privs['id_district']==$options['id_district'] && $privs['status']=='Active')
             $editSchool=true;
     }
     
     
	
	
	if ( $editSchool==true)
            echo $this->view->render('school/edit.phtml');
          else  echo $this->view->render('school/view.phtml');

       }

    }

    public function saveAction ()
    {

          $this->_helper->layout()->disableLayout();
          $this->_helper->viewRenderer->setNoRender(true);
     
          $this->_helper->contextSwitch()->addActionContext('getJsonResponse', array('json'))->initContext();

          $options['id_school'] = $this->getRequest()->getParam('id_school');
          $options['id_county'] = $this->getRequest()->getParam('id_county');
          $options['id_district'] = $this->getRequest()->getParam('id_district');

   	  $classPriv = $this->usersession->user->user['class'];
   	  $schoolPriv = $this->usersession->user->user['id_school'];
   	  $districtPriv = $this->usersession->user->user['id_district'];
   	  $countyPriv = $this->usersession->user->user['id_county'];

// ------ Check all privs for get access -------------------
         $x=0;
         $schools = Array();
 	 while ($x < count($this->usersession->user->privs))
	 {
	    // school manager (4) and associate school manager (5)
            if (($this->usersession->user->privs[$x]['status'] == 'Active' && 
                $this->usersession->user->privs[$x]['id_county'] == $options['id_county'] && 
		$this->usersession->user->privs[$x]['id_district'] == $options['id_district'] && 
		$this->usersession->user->privs[$x]['id_school'] == $options['id_school'] && 
		($this->usersession->user->privs[$x]['class'] == 4 || $this->usersession->user->privs[$x]['class'] == 5)) || 
		($this->usersession->user->privs[$x]['class'] == 2 || $this->usersession->user->privs[$x]['class'] == 3)) {
        	    	$schools[] = $this->usersession->user->privs[$x]['class'];
	    }
	   $x++;
	 } 
// --------------------------------------------------------

	if ($classPriv == 2 || $classPriv == 3 || $school.length > 0)

	$options = Array();
	$options_reports = Array();
        $request = $this->_request->getParams();
   
	foreach ($request as $nameField => $valueField){
	 if ($nameField != "reports"){
	  $value = urldecode($nameField);
	  $options[$value] = urldecode(strip_tags($valueField));
         } else if ($nameField == "reports"){
     	    foreach ($request["reports"] as $nameFieldReport => $valueFieldReport){

	      if ($valueFieldReport[0] != "" || $valueFieldReport[1] != "" || $valueFieldReport[2] != "" || $valueFieldReport[3] != "" || $valueFieldReport[4] != "" || $valueFieldReport[5] != "") 
                   $options_reports [$nameFieldReport] = [ urldecode(strip_tags($valueFieldReport[0])), urldecode(strip_tags($valueFieldReport[1])), urldecode(strip_tags($valueFieldReport[2])), urldecode(strip_tags($valueFieldReport[3])), urldecode(strip_tags($valueFieldReport[4])), urldecode(strip_tags($valueFieldReport[5]))];

            }

         }
	}

// Validate Data from form
$validator_digital = new Zend_Validate_Regex('/\d+$/');
$validator_status = new Zend_Validate_Regex('/^([A-Za-z]+)$/');
$validator_state = new Zend_Validate_Regex('/\w{2}$/');
$validator_zip = new Zend_Validate_Regex('/\d{5}-\d{4}$|^\d{5}$/');
$validator_phone = new Zend_Validate_Regex('/^\d{3}-\d{3}-\d{4}$/');

if (strlen($options["name_school"]) > 2 && strlen($options["address_city"]) > 2 && strlen($options["address_city"]) > 2 && 
    $validator_state->isValid( $options["address_state"] ) &&   
    $validator_status->isValid( $options["status"] ) && 
    $validator_digital->isValid( $options["id_school"] ) && 
    $validator_digital->isValid( $options["id_county"] ) && 
    $validator_digital->isValid( $options["id_district"] ) && 
    $validator_digital->isValid( $options["schoolmng"] ) && 
    $validator_digital->isValid( $options["schoolsprv"] ) && 
    $validator_phone->isValid( $options["phone_main"] ) && 
    $validator_zip->isValid( $options["address_zip"] ) &&
    $validator_digital->isValid( $options["minutes_per_week"] ) ) {

    $session = new Zend_Session_Namespace('searchSchoolForm');
    $options["key"] = $session->searchKey;

    $schoolQuery = new Model_Table_School();
    $result = $schoolQuery->schoolSave($options, $options_reports);  // Save data
    $msg = 'Saved!';
    $err = 0;

 } else { 
    $msg = 'Please, fill all Field with asterisk "*"';
    $err = 1;
}

    $jsonData = array ( 'msg' => $msg, 'err' => $err ); // your json response
    $this->_helper->json->sendJson($jsonData);

 }

}
