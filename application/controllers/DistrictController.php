<?php

class DistrictController extends Zend_Controller_Action
{ 
    // Mike on commit 12-12-2016
// June 10th lat commit
    public function init()  
    {
        ini_set('memory_limit', '-1');
        // $this->_redirector = $this->_helper->getHelper('Redirector');
        // $this->view->headLink()->appendStylesheet('/js/dijit/themes/soria/soria.css');
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
       
    public function listtableAction() {
        $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);       
        $database = Zend_Db::factory($dbConfig->db2);
        
       $sql="SELECT table_name FROM information_schema.tables WHERE table_schema='public'";
    //  $sql="select table_name,pg_relation_size(table_name) from information_schema.tables where table_schema='public' order by 2 ";
       
   //    $sql="SELECT table_name,pg_size_pretty(table_size) AS table_size,pg_size_pretty(indexes_size) AS indexes_size,pg_size_pretty(total_size) AS total_size FROM ( SELECT table_name,pg_table_size(table_name) AS table_size,pg_indexes_size(table_name) AS indexes_size,pg_total_relation_size(table_name) AS total_sizeFROM (SELECT ('"' || table_schema || '"."' || table_name || '"') AS table_name FROM information_schema.tables) AS all_tables ORDER BY total_size DESC) AS pretty_sizes"
        $result=$database->fetchAll($sql);
       $this->writevar1($result,'this is the list of tables');
       $this->view->tables=$result;
       
     
      /* 
       $value=array();
       $x=0;
        foreach($result as $res){
           $value[$x]['name']=$res['table_name'];
            
           $sql2="select count(*) from ". $res['table_name']."";
          // $resultSize=$database->fetchAll($sql2);
           $msg='this is the size result for '.$res['table_name'];
         //  $this->writevar1($resultSize,$msg);
           $this->writevar1($res['table_name'],'this is the table name');
           $x=$x+1;
        }
        $this->writevar1($value,'this is  the value var line 39');
        
        die();
      */  
        
        
    }
    
    public function edfireportAction(){
        $districtModel = new Model_Table_EdFiReport();
        $page = $this->_getParam('page');
        $maxRecs=20;
    
        $fieldname = $this->_getParam('fieldname');
        if ($fieldname == "") $fieldname = "name_district";
    
        $sort = $this->_getParam('sort');
        if ($sort == "") $sort = "asc";
    
        $fieldname = $fieldname . " " . $sort;
    
        if($page==""){
            $page=1;
        }
    
        $results = $districtModel->getDistrictsResume($page, $maxRecs, $fieldname);
    
        if($sort=="asc"){
            $this->view->sort="desc";
        } else {
            $this->view->sort="asc";
        }
         
        $this->view->districtModel= $results;
    
    }
    
    
    public function edfidetailAction(){
        $districtModel = new Model_Table_EdFiReport();
        $id_district = $this->_getParam('id_district');
        $id_county = $this->_getParam('id_county');
    
        $fieldname = $this->_getParam('fieldname');
        if ($fieldname == "") $fieldname = "edfipublishtime";
    
        $sort = $this->_getParam('sort');
        if ($sort == "") $sort = "desc";
    
        $fieldname = $fieldname . " " . $sort;
    
    
        $page = $this->_getParam('page');
        $maxRecs=20;
    
        if($page==""){
            $page=1;
        }
    
        if($sort=="asc"){
            $this->view->sort="desc";
        } else {
            $this->view->sort="asc";
        }
    
        $this->view->id_district= $id_district;
        $this->view->id_county= $id_county;
    
        $results = $districtModel->getDistrictsDetail($id_district, $id_county, $page, $maxRecs, $fieldname);
    
        $this->view->districtModel= $results;
    
    } 
    
    public function indexAction()
    {
          
        
      // include("Writeit.php");
    
        // $data = range(1,10);
        $this->view->current_date_and_time = date('M d, Y - H:i:s');
    
        $fieldname = $this->_getParam('fieldname');
        if ($fieldname == "") $fieldname = "name_district asc"; else $fieldname = $fieldname." asc";
       // $this->writevar1($fieldname,'this is the field name');
        $iep_district = new Model_Table_IepDistrict();
        $data1 = $iep_district->fetchAll($iep_district->select()->order($fieldname));
      
       // $this->writevar1($data1,'this is the data');
        
        $paginator3 = Zend_Paginator::factory($data1);
        $paginator3->setCurrentPageNumber($this->_getParam('page'));
        $paginator3->setItemCountPerPage(20);
        $this->view->paginator3 = $paginator3;
    
        $iep_county = new Model_Table_IepCounty();
        $this->view->iep_county = $iep_county->fetchAll($iep_county->select());
        
      //  $this->writevar1($this->view->iep_county,'this is the list of the counties');
        
        $this->view->ListDistricts = $data1;
    
        $iep_priv = new Model_Table_PrivilegeTable();
        // District Managers and Associate District Managers have the rights to edit District Data
        //If they do not have the rights then the view->districtlist returns the # 0 instead of the array of privileges
        $classLevel=3;
        $this->view->districtList=$iep_priv->getUserInfo2($classLevel);
        
        $district_list = array();
        foreach ($iep_priv->getUserInfo2($classLevel) as $index => $val) {
            $district_list[$index] = $val['name_district'];
        }
    
        asort($district_list);
    //   $distlist=json_encode($district_list);
        $this->view->districtListAll = $district_list;
        $this->view->person = $this->getUserInfo($data1);
        
    }  
        
    
  
     function sortAction()
    {
        $this->view->current_date_and_time = date('M d, Y - H:i:s');
        $iep_district = new Model_Table_IepDistrict();
       
        // get the sort on the column name from the view script sort.phtml
        $field = $this->getRequest()->getParam('fieldname');
        
        $data = $iep_district->fetchAll($iep_district->select()->order($field));
        $paginator = Zend_Paginator::factory($data);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(20);
        $this->view->paginator = $paginator;
        
        //Get the counties and pass them to the view
        $iep_county = new Model_Table_IepCounty();
        $this->view->iep_county = $iep_county->fetchAll($iep_county->select());
       
        $this->view->person=$this->getUserInfo($data);
        
        $iep_priv = new Model_Table_PrivilegeTable();
        $classLevel=10;
        $this->view->districtList=$iep_priv->getUserInfo2($classLevel);
    }

    function editAction()
    {
      // include("Writeit.php");
        
        $form = new My_Form_IepDistrictEdit();
        $form->submit->setLabel('Save');
      // $this->view->form = $form;
       
       // Mike added this so that they cannot get into the view/district/edit.phtml screen 11-15-2016
       
       // Needed to do this here so that users cannot paste the url data into the view.
     
        if ($this->getRequest()->isPost()) {
           /* $formData = $this->getRequest()->getPost();
          // Decide 
            
            
            if ($form->isValid($formData)) {
             
                $district = new Model_Table_IepDistrict();
               
                $district->updateIepDistrict2($formData);
                $this->_helper->redirector('index');
           } else {
                $form->populate($formData);
            } */
        } else {
            
            $name_district = $this->_getParam('name_district', '');
            $id_county =$this->_getParam('id_county');
            $id_district = $this->_getParam('id_district');
           
            // Go find out if the user has access to this page
            $proceed='no';
            $iep_priv = new Model_Table_PrivilegeTable();
            $classLevel=3;
          
            // Mike needs to change this 2-22-2017 to the iep_privileges table
            // NOTE the user id will be picked up from the _SESSION['user'][['id_personnel'] variable in the getuserInfor2 funcition
            $UserPrivTable=$iep_priv->getUserInfo2($classLevel);       
            $proceed='no';
            foreach($UserPrivTable as $priv) {   
                if($priv['id_district']==$id_district and $priv['id_county']==$id_county and $priv['class']<=3 and $priv['status']=='Active')
                {
                    $proceed='yes';// This will allow one to edit the district page.  
                }
             }
            
            if ($name_district != ''and $proceed=='yes') {
                $this->view->rights='yes';
                $district = new Model_Table_IepDistrict();
                $disTable=$district->getIepDistrict($name_district);
                $form->populate($district->getIepDistrict($name_district));
                
                // Get the district peoples names to display instead of numbers
                $accountName = new Model_Table_IepPersonnel();
                
                $personnelInfo= $accountName->getIepPersonnel($disTable['id_district_mgr']);
                $disTable['id_district_mgr_name'] =$personnelInfo['name_first']." ".$personnelInfo['name_last'];
                
                $personnelInfo= $accountName->getIepPersonnel($disTable['id_account_sprv']);
            //    $disTable['id_account_sprv_name'] =$personnelInfo['name_first']." ".$personnelInfo['name_last'];
                
                $personnelInfo= $accountName->getIepPersonnel($disTable['email_student_transfers_to']);
                
                if($proceed=='yes'){
                $form->populate($disTable);                
            } 
         
            }
        }
    
        
        $field=' ';
        $field = $this->getRequest()->getParam('fieldname');
        $allStaff=$this->getDistrictStaff($id_county,$id_district,$field);
        $paginatorE = Zend_Paginator::factory($allStaff);
        $paginatorE->setCurrentPageNumber($this->_getParam('page'));
        $paginatorE->setItemCountPerPage(30);
       
        $this->view->paginatorE = $paginatorE;
       
        // This will let you see the school each of the users belongs to
        
        $nameOfSchool = new Model_Table_IepSchoolm();
        $a = $nameOfSchool->fetchAll($nameOfSchool->select()->where('id_district = ?',"$id_district" )->where( 'id_county = ?',"$id_county"));
        $this->view->nameOfSchool=$a;
  
    }  // End of the editAction
 
    function viewAction()
    {
      //  include("Writeit.php");
        // Get the name of the district Manager
      $nameManager = new Model_Table_IepPersonnel();
        // Get the id_county,id_district from the session variables.
      $county= $_SESSION["user"]["user"]->user["id_county"];  
      $district = $_SESSION["user"]["user"]->user["id_district"];
      
     //  $acctManager = $nameManager->fetchRow($nameManager->select()->order($field));
        
        
        $form = new My_Form_IepDistrictView();
        $form->submit->setLabel('Press When Done');
        $this->view->form = $form;


        // you can leave this in but all is a a get.
       if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
    	        $id_district = $form->getValue('id_district');
                $id_county = $form->getValue('id_county');
                $name_district = $form->getValue('name_district');

                $this->view->id_county = $id_county;
	        $this->view->id_district = $id_district;
	        $this->view->name_district = $name_district;

                $phone_main = $form->getValue('phone_main');
                $id_district_mgr = $form->getValue('id_district_mgr');
                $add_resource1 = $form->getValue('add_resource1');
                // $address_street1=$form->getValue('address_street1');
                $address_city = $form->getValue('address_city');
                $address_zip = $form->getValue('address_zip');
                $id_author = $form->getValue('id_author');
                $id_author_last_mod = $form->getValue('id_author_last_mod');
            
             //  $form=$form->populate($data);
             //  $district->updateIepDistrict($name_district, $id_district, $id_county, $phone_main, $add_resource1, $id_author, $id_author_last_mod);
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        } else { 
            $name_district = $this->_getParam('name_district', '');
            $countyid = $this->_getParam('id_county');
            $districtid = $this->_getParam('id_district');

            $this->view->id_county = $countyid;
            $this->view->id_district = $districtid;
            $this->view->name_district = $name_district;
            
            if ($name_district != '') {
               $district = new Model_Table_IepDistrict();
               $disTable=$district->getIepDistrict($name_district);
            
              // Find out the full names of the district manager and account managers and add it to the distable for form rendering.

	      // SEARCH BY NAME THIS IS NOT CORRECT!!!!
              
              $accountName = new Model_Table_IepPersonnel();
              
              $personnelInfo= $accountName->getIepPersonnel($disTable['id_district_mgr']);
              $disTable['id_district_mgr_name'] =$personnelInfo['name_first']." ".$personnelInfo['name_last'];
                
              $personnelInfo= $accountName->getIepPersonnel($disTable['id_account_sprv']);
              $disTable['id_account_sprv_name'] =$personnelInfo['name_first']." ".$personnelInfo['name_last'];
              
              $personnelInfo= $accountName->getIepPersonnel($disTable['email_student_transfers_to']);
              $disTable['email_student_transfers_to_name'] =$personnelInfo['name_first']." ".$personnelInfo['name_last']."    ".$personnelInfo['email_address'];
        
              $form->populate($disTable);
               
               
         
            }
       }
  
   // Get a list of privileges for this user: NOTE means class value of 10 or less. 
   //User id is checked vi session variables in getUserInfo2 method.
   
       $iep_priv = new Model_Table_PrivilegeTable();
       $classLevel=10;
       $privileges=$iep_priv->getUserInfo2($classLevel);
       $this->view->edit='fasle';
       $this->view->edit2='false';
       $x=0;
       foreach($privileges as $priv){
           if(($priv['id_district']==$districtid and $priv['id_county']==$countyid and $priv['class']<=3 and $priv['status']=='Active') or $priv['class']<=2){
               $this->view->edit='true';
               $x=$x+1;
               }
           if($priv['class']<=3 and $priv['status']=='Active') {
               $this->view->edit2='true';
           }
           }
               
   
   // Get a list of the district staff and sort them by last name
   
       $field=' ';
       $field = $this->getRequest()->getParam('fieldname');

       $allStaff=$this->getDistrictStaff($countyid,$districtid,$field);
       $this->view->allStaff = $allStaff;

       $paginator2 = Zend_Paginator::factory($allStaff);
       $paginator2->setCurrentPageNumber($this->_getParam('page'));
       $paginator2->setItemCountPerPage(30);
       $this->view->paginator2 = $paginator2;

       $nameOfSchool = new Model_Table_IepSchoolm();
       $a = $nameOfSchool->fetchAll($nameOfSchool->select()->where('id_district = ?',"$districtid" )->where( 'id_county = ?',"$countyid"));
       $this->view->nameOfSchool=$a;
       $this->view->districtStaff=$allStaff;
          
  
    }

    function editdistrictAction()
    {
      //  include("Writeit.php");
       $this->_helper->layout()->disableLayout();
       $this->_helper->viewRenderer->setNoRender(true);

        $id_county = $this->getRequest()->getParam('id_county');
        $id_district = $this->getRequest()->getParam('id_district');

        // Go find out if the user has access to this page
        $proceed='no';
        $iep_priv = new Model_Table_PrivilegeTable();
        $classLevel = 3;
        // NOTE the user id will be picked up from the _SESSION['user'][['id_personnel'] variable in the getuserInfor2 funcition
        $UserPrivTable=$iep_priv->getUserInfo2($classLevel);       
        $proceed='no';
        
        /* Mike added this Feb 9 because have to check the iep_personnel table as well to see if
         * user is active in the district. If not, we don't want them showing up in the pulldown
         */
        
        
        foreach($UserPrivTable as $priv) {   
            if($priv['id_district']==$id_district and $priv['id_county']==$id_county and $priv['class']<=3 and $priv['status']=='Active')
            {
                    $proceed='yes';// This will allow one to edit the district page.  
            }
         }

       if ($proceed == 'yes') {
// end of Mike add sort of
        $formData = $this->getRequest()->getPost();

        $district = new Model_Table_IepDistrict();
        $disTable=$district->getIepDistrictByID($id_county, $id_district);
        
        /* Mike added this 5-11-2017 so that it would appear on the forms.
         * This way we can set it to false and catch to see if it was pressed on 
         * the save action
        */
        $disTable['publish_edfi']=false;
        // End of Mike Add. 
        
        $this->view->data = $disTable;
     
        $iep_priv = new Model_Table_PrivilegeTable();

        $this->view->acc_superviser = $district->getIepDistrictManagers($id_county, $id_district, 3);
        $this->view->distr_manager = $district->getIepDistrictManagers($id_county, $id_district, 3);

        echo $this->view->render('district/district_edit_form.phtml');

      } else echo $this->view->render('district/access-denied.phtml');

    }

    function saveAction()
    {
        
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $id_county = $this->getRequest()->getParam('id_county');
        $id_district = $this->getRequest()->getParam('id_district');

        // Go find out if the user has access to this page
        $proceed='no';
        $iep_priv = new Model_Table_PrivilegeTable();
        $classLevel = 3;

        // NOTE the user id will be picked up from the _SESSION['user'][['id_personnel'] variable in the getuserInfor2 funcition
        $UserPrivTable=$iep_priv->getUserInfo2($classLevel);       
        $proceed='no';
        foreach($UserPrivTable as $priv) {   
            if($priv['id_district']==$id_district and $priv['id_county']==$id_county and $priv['class']<=3 and $priv['status']=='Active')
            {
                    $proceed='yes';// This will allow one to edit the district page.  
            }
         }

       if ($proceed == 'yes') {

        $post = $this->getRequest()->getPost();
        $options = Array();

        foreach ($post as $nameField => $valueField){
         $value = urldecode($nameField);
         // Mike added this if May 11th to get the edfi publishing going. 
         if ($nameField!='publish_edfi'){
         $options[$value] = urldecode(strip_tags($valueField));
         }
         else {
             $publish=urldecode(strip_tags($valueField));
             $string='f';
             $options['edfi_refresh']=$string;
         }
        }
         
        

        $district = new Model_Table_IepDistrict();
        $district->updateIepDistrictForm($options);
      
         /* Mike added this if else 5-11-2017 so that publishing to advisor will show up as 
         * a status message on the screen. 
         */
        if($publish==true){
            $publishAdvisor= new Model_Table_EdfiAuto;
         //   $publishAdvisor->advisorsetAction(0,0);
         
            // Mike needs to start here tomnorrow 5-11-2017
            $msg2="District Information Saved and Data Published to Advisor Staging";
            $jsonData2 = array ( 'msg' => $msg2 );
            echo  $this->_helper->json->sendJson($jsonData2);
            $publishAdvisor->advisorsetAction($id_county,$id_district);
            $msg ='District Information Saved and Data Published to Advisor Staging';
        }
         else {   
        $msg = "District Information Saved!";
         }
        
        $jsonData = array ( 'msg' => $msg ); // your json response
         echo  $this->_helper->json->sendJson($jsonData);

      } else echo $this->view->render('district/access-denied.phtml');

    }


    function viewdistrictAction()
    {
       $this->_helper->layout()->disableLayout();
       $this->_helper->viewRenderer->setNoRender(true);

        $formData = $this->getRequest()->getPost();
        $id_county = $this->getRequest()->getParam('id_county');
        $id_district = $this->getRequest()->getParam('id_district');

        $district = new Model_Table_IepDistrict();
        $disTable=$district->getIepDistrictById($id_county, $id_district);

        // Get the district peoples names to display instead of numbers
        $accountName = new Model_Table_IepPersonnel();

        $personnelInfo= $accountName->getIepPersonnel($disTable['id_district_mgr']);
        $disTable['id_district_mgr_name'] =$personnelInfo['name_first']." ".$personnelInfo['name_last'];

        $personnelInfo= $accountName->getIepPersonnel($disTable['id_account_sprv']);
        $disTable['id_account_sprv_name'] =$personnelInfo['name_first']." ".$personnelInfo['name_last'];

        $personnelInfo= $accountName->getIepPersonnel($disTable['email_student_transfers_to']);
        $disTable['email_student_transfers_to_name'] = $personnelInfo['name_first']." ".$personnelInfo['name_last']." <a href='mailto:".$personnelInfo['email_address']."'>".$personnelInfo['email_address']."</a>";
        $this->view->data = $disTable;


	// JUNY 30, 2017
	// get all Schools from county and district
        $results = $district->getIepSchoolList($id_county, $id_district);
        $this->view->schools = $results;

	// get all Managers 
        $results = $district->getIepManagersList();
        $this->view->managers = $results;		
	    foreach($results as $idx => $val) {
		$res[$val["id_personnel"]] = $val["name_first"] . " " . $val["name_last"];
	    }

	$this->view->managers = $res;

        echo $this->view->render('district/district_view_form.phtml');

    }

    
    /*
     * https://akrabat.com/exploring-zend-paginator/
     * https://gist.github.com/tankist/954971
     * Here is how to do paginator via a db view:http://stackoverflow.com/questions/35947308/zend-1-paginator-limit
     */
    
    // THIS IS THE FUNCTION TO GET THE PERSON ARRAY FILLED UP FOR USE IN OTHER CONTROLLER FUNCTIONS
   
    
     
    public function disAbleLayout()
    {
       // $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }
    
     function getDistrictStaff($id_county,$id_district,$field) { 
       
     /*    $county;
         $district;
         
         
         $county= $_SESSION["user"]["user"]->user["id_county"];
         
         $district = $_SESSION["user"]["user"]->user["id_district"];
      */
         $staff = new Model_Table_PersonnelTable();
         
         $sortOrder='name_last';
         
         if ($field =='school') {
             $sortOrder = 'id_school';
                 }
               else {
                   if($field =='name') {
                       $sortOrder = 'name_last';
                      }
                  }
         if($field == 'status') {
             $sortOrder ='status';
         }
              
             
         
         $staffMembers = $staff->fetchAll($staff->select()
             ->where('id_district = ?', $id_district)->where('id_county = ?',$id_county)->order($sortOrder)->order('name_last'));
          
         $rowCount=count($staffMembers);
       
         return $staffMembers;
    }
   
    
    
     protected function getUserInfo ($data)
    {
       
     
        
        $person['id_personnel']= $_SESSION["user"]["id_personnel"];
        $person['id_county']= $_SESSION["user"]["user"]->user["id_county"];
        $person['status']= $_SESSION["user"]["user"]->user["status"];
        $person['id_district']= $_SESSION["user"]["user"]->user["id_district"];
         
       
        
         
        // Find out how many times she has been registered and use the lowest class
         
        $cnt= $_SESSION["user"]["user"]->privs;
        $privCount= count($cnt);
       
         
        $finalClass=$_SESSION["user"]["user"]->privs[0]["class"];
        
        if($privCount > 0) {
            $previousClass = 100;
        
            for ($x=0; $x< $privCount;$x++) {
                 
                $currentClass=$_SESSION["user"]["user"]->privs[$x]["class"];
                if(($currentClass <= $finalClass) and ($_SESSION["user"]["user"]->privs[$x]["id_district"]==$person["id_district"])
                    and ($_SESSION["user"]["user"]->privs[$x]["id_county"]==$person["id_county"] )) {
                        $finalClass=$currentClass;
                    }  //end the iff
        
            } // end the for loop
        
        }   //Done with looking at more than one district per
        
        // Get the district name
        foreach($data as $distname) {
             
            if (( $distname->id_county == $person['id_county']) and
                ( $distname->id_district == $person['id_district']))
            {
                $person['name_district']= $distname->name_district;
             
                // mike added 5-20
                $person['id_district']= $distname->id_district;
                $person['id_county'] = $distname->id_county;
            }
        }
        
        $person['class']=$finalClass;
        if($finalClass <=3 ) {
            $person['edit']="true";
        }
        else {
            $person['edit']="false";
        }  //end of edit or view
        
       
        return $person;
    }  // This is the end of the funciton 
}
