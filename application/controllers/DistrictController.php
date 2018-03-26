<?php

class DistrictController extends Zend_Controller_Action
{
    // Mike on commit 12-12-2016 and 12/7/2017
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

    function testprintAction1() {

      //  $filename='/usr/local/zend/var/apps/https/iepweb02.nebraskacloud.org/443/1.0.0_268/srs-form-archive/NewRoot/01/0018/003/2012/1130587/1130587-002-1206342-archived(20121227).pdf';

        $pdf1=new Zend_Pdf;
        $result=$pdf1->load($filename);
      //  $pdfString=$pdf1->render();
        $this->_helper->layout()->disableLayout();
   //     $this->_helper->viewRenderer->setNoRender(true);
        header("Cache-Control: public, must-revalidate");
        header("Pragma: hack");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename=' . basename($filename));
        header("Content-Type: application/pdf");
        //                 header("Content-Type: text/html; charset=utf-8");
        //Mike line 1478 came up as an error in line 1481 because it was split on 3 lines.  put it together and it worked on the printing
        header("Content-Transfer-Encoding: binary");
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
       $this->view->pdfString=$pdf1->render($result);
      //$tmpPDFpath;

    }

    function testprintAction() {

     // $filename='/usr/local/zend/var/apps/https/iepweb02.nebraskacloud.org/443/1.0.0_268/srs-form-archive/NewRoot/01/0018/003/2012/1130587/1130587-002-1206342-archived(20121227).pdf';
       // $filename=$path.'/'.$file;
        $pdf1=new Zend_Pdf;
        $iep_form_number=$this->_getParam('id');
       // $filename=$this->_getParam('id');

        $getForm=new Model_Table_ArchiveNew();
        $formMetaData=$getForm->getFormMetaDataTableId($iep_form_number);
    //    $this->writevar1($formMetaData,'this is the form metadata');




        $filename=$formMetaData['path_location'];
        $filename .= "/";
        $filename .=$formMetaData['file_name'];
        $filename .='.pdf';


    //    $this->writevar1($filename,'this is the filename in dist controller');
        //  $pdfString=$pdf1->render();
        $this->_helper->layout()->disableLayout();
        //     $this->_helper->viewRenderer->setNoRender(true);
        header("Cache-Control: public, must-revalidate");
        header("Pragma: hack");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename=' . basename($filename));
        header("Content-Type: application/pdf");
        //                 header("Content-Type: text/html; charset=utf-8");
        //Mike line 1478 came up as an error in line 1481 because it was split on 3 lines.  put it together and it worked on the printing
        header("Content-Transfer-Encoding: binary");
     //Mike took this out 2-22-2018 because the file size was smaller than the actual file name.Thus it was only printing part of it.
      //  header('Content-Length: ' . filesize($filename));
        readfile($filename);
        $this->view->pdfString=$pdf1->render($result);
        //$tmpPDFpath;

    }
    // Mike added these two tables on 9-7-2017 for edfi reporting purposes

    public function edfireportAction(){
        $districtModel = new Model_Table_EdFiReport();
        $page = $this->_getParam('page');
        $maxRecs=20;

        $fieldname = $this->_getParam('fieldname');
        if ($fieldname == "") $fieldname = "name_district";

        //  $this->writevar1($fieldname,'the field name line 67');

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

    public function edfidetail2Action(){
        $districtModel = new Model_Table_EdFiReport();
        $id_district = $this->_getParam('id_district');
        $id_county = $this->_getParam('id_county');

        $this->view->countyId=$id_county;
        $this->view->districtId=$id_district;


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
        //   $this->writevar1($results,'this is the result dist controller line 122');
        $this->view->districtModel= $results;

        $resultsNoPag=$districtModel->getDistrictsDetailNoPag($id_district, $id_county, $page, $maxRecs, $fieldname);
        $this->view->studentsJqueryArray=$this->makeJqueryArray($resultsNoPag);


    }


    public function makeJqueryArray($data) {

        $x=0;
        foreach($data as $key => $val) {
            $studentArray .= ", [ '".addslashes($val['name_first'])."', '".addslashes($val['name_last'])."','"
            .$val['id_student']."', '".$val['studentuniqueid']."','".$val['educationorganzationid']."','".$val['edfipublishstatus'].
            "','".addslashes($val['edfierrormessage'])."','".addslashes($val['name_school'])."','".addslashes($val['edfipublishtime'])."'
            ,'".addslashes($val['begindate'])."','".addslashes($val['enddate'])."','".addslashes($val['reasonexiteddescriptor'])."'
             ,'".$val['iep_ifsp_code']."','".$val['iep_ifsp_id']."','".$val['mdt_code']."','".$val['mdt_id']."'
             ,'".$val['specialeducationsettingdescriptor']."','".$val['levelofprogramparticipationdescriptor']."','".$val['placementtypedescriptor']."'
              ,'".$val['specialeducationpercentage']."','".$val['totakealternateassessment']."','".$val['disabilities']."'
              ,'".$val['servicedescriptor_slt']."','".$val['servicedescriptor_ot']."','".$val['servicedescriptor_pt']."']";

            //$studentArray .= ", [ '".addslashes($val['name_first'])."', '".addslashes($val['name_last'])."']";


            $x=$x+1;
        }
        $this->writevar1($data,'this is the data line 174 in makeJqueryArray ');
        $this->writevar1($studentArray,'this is the student array line 171 dist controller');
        return $studentArray;
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
        //   $this->writevar1($results,'this is the result dist controller line 122');
        $this->view->districtModel= $results;

    }

    // end of Mike add 9-7-2017

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






    public function indexAction()
    {

      // include("Writeit.php");

        // $data = range(1,10);
        $this->view->current_date_and_time = date('M d, Y - H:i:s');

        $fieldname = $this->_getParam('fieldname');
        if ($fieldname == "") $fieldname = "name_district asc"; else $fieldname = $fieldname." asc";

        $iep_district = new Model_Table_IepDistrict();
        $data1 = $iep_district->fetchAll($iep_district->select()->order($fieldname));

        $paginator3 = Zend_Paginator::factory($data1);
        $paginator3->setCurrentPageNumber($this->_getParam('page'));
        $paginator3->setItemCountPerPage(20);
        $this->view->paginator3 = $paginator3;

        $iep_county = new Model_Table_IepCounty();
        $this->view->iep_county = $iep_county->fetchAll($iep_county->select());

        $this->view->ListDistricts = $data1;

        $iep_priv = new Model_Table_PrivilegeTable();
        // District Managers and Associate District Managers have the rights to edit District Data
        //If they do not have the rights then the view->districtlist returns the # 0 instead of the array of privileges
        $classLevel=3;
        $this->view->districtList=$iep_priv->getUserInfo2($classLevel);

       // $this->writevar1($this->view->districtList,'this is the district list ');

        $edFiTable=array();
        $x=0;
        foreach($this->view->districtList as $distList){
            if($distList['use_edfi']==true){
            $edFiTable[$x]=$distList;
            $x=$x+1;
            }
        }
        $this->view->EdFiList=$edFiTable;



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
        include("Writeit.php");

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


       // Mike added this 9-7-2017 so that site admin can view district edit page.
       foreach($_SESSION['user']['user']->privs as $privs)  {
           if ($privs['class']==1 && $privs['status']=='Active') $proceed='yes';
       }
       // end of mike add

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

	//  --- Get reports ----------------
        $options['id_county'] = $id_county;
        $options['id_district'] = $id_district;
        $datetime = new DateTime();
        $options['CurrentDistrictYear'] = $datetime->format('Y');
        $result = $district->getIepDistrictReport($options); // Get District View result
        $this->view->reports = $result;
        $this->view->CurrentDistrictYear = $options['CurrentDistrictYear'];
	// ----------------------------------


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
       // $this->writevar1($UserPrivTable,'this is the user priv table line 626');
        $proceed='no';
        foreach($UserPrivTable as $priv) {
            if($priv[status]=='Active' and $priv['class']==1){ $proceed='yes';}
            if($priv['id_district']==$id_district and $priv['id_county']==$id_county and $priv['class']<=3 and $priv['status']=='Active')
            {
                    $proceed='yes';// This will allow one to edit the district page.
            }
         }


       if ($proceed == 'yes') {

        $post = $this->getRequest()->getPost();
          //  $this->writevar1($post['use_form004_pwn'],'this is the post');


        // Mike made this false until we get the ok from Wade on SRS-151 3-5-2018
            if($post['use_form004_pwn']=='1') {
            $post['use_form004_pwn']=true;
             }

            if($post['use_form004_pwn']=='0') {
            $post['use_form004_pwn']=false;
            }


      //  $this->writevar1($post,'this is the post');
        $options = Array();
        $options_reports = Array();

        foreach ($post as $nameField => $valueField){
             $value = urldecode($nameField);
    	     // Mike added this if May 11th to get the edfi publishing going.

	    if ($nameField == "reports"){
    	          foreach ($post["reports"] as $nameFieldReport => $valueFieldReport){
        	      if ($valueFieldReport[0] != "" || $valueFieldReport[1] != "" || $valueFieldReport[2] != "" || $valueFieldReport[3] != "" || $valueFieldReport[4] != "" || $valueFieldReport[5] != "")
                	  $options_reports [$nameFieldReport] = [ urldecode(strip_tags($valueFieldReport[0])), urldecode(strip_tags($valueFieldReport[1])), urldecode(strip_tags($valueFieldReport[2])), urldecode(strip_tags($valueFieldReport[3])), urldecode(strip_tags($valueFieldReport[4])), urldecode(strip_tags($valueFieldReport[5]))];
        	  }

             } else $options[$value] = urldecode(strip_tags($valueField));
        }

        $district = new Model_Table_IepDistrict();
// Mike added this 2-27-2018 SRS-151
        if($options['use_form004_pwn']=='1') {
            $options['use_form004_pwn']=1;
        }

        else {
            $options['use_form004_pwn']=0;
        }

     //   $this->writevar1($options,'these are the options on line 665');


        $district->updateIepDistrictForm($options); // Save data
	$district->saveIepDistrictReports($options, $options_reports);  // Save reports

        $msg = "District Information Saved!";

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

    function uploadfileAction() {
        $rnd = time();
        $config = Zend_Registry::get ( 'config' );
        $uploaddir = $config->IMG_ROOT;
        $id_county = $_POST["id_county"];
        $id_district = $_POST["id_district"];
	      if ($_FILES) {
	        $mimetype = $_FILES['file']['type'];
	        $ext = explode( "/", $mimetype );
	        if ($ext[1] == "jpeg") $ext[1] = "jpg";
		$filename = $_POST["id_county"].$_POST["id_district"].".".$ext[1];
	        $uploadfile = $uploaddir.$filename;

    	        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)
            	    && $_POST["id_district"] != ""
            	    && $_POST["id_county"] != ""
            	    && ($_FILES["file"]["type"] == "image/gif"
            		|| $_FILES["file"]["type"] == "image/jpeg"
            		|| $_FILES["file"]["type"] == "image/jpg"
            		|| $_FILES["file"]["type"] == "image/png") ) {
                  $district = new Model_Table_IepDistrict();
                  $district->updateImageLocation($id_county, $id_district, $filename);
		  $msg = $config->IMG_LOCATION.$filename."?".$rnd;
		} else $msg = "";

        	$jsonData = array ( 'msg' => $msg );
	        echo $this->_helper->json->sendJson($jsonData);
	      }
    }

    function removefileAction() {
        $config = Zend_Registry::get ( 'config' );
        $uploaddir = $config->IMG_ROOT;
	      $filename = $_POST["id_county"].$_POST["id_district"];
        $id_county = $_POST["id_county"];
        $id_district = $_POST["id_district"];

        $district = new Model_Table_IepDistrict();
        $imagefile = $district->getImageLocation($id_county, $id_district);

        if (!empty($imagefile) && file_exists($uploaddir.$imagefile)) {
          unlink($uploaddir.$imagefile);
          $district->updateImageLocation($id_county, $id_district, '');
          $jsonData = array ( 'msg' => 'ok' );
        } else {
          $jsonData = array ( 'msg' => 'File not found' );
        }

	      echo $this->_helper->json->sendJson($jsonData);
    }

    function listfileAction() {
        $rnd = time();
        $config = Zend_Registry::get ( 'config' );
        $uploaddir = $config->IMG_ROOT;
	      $filename = $_POST["id_county"].$_POST["id_district"];
        $id_county = $_POST["id_county"];
        $id_district = $_POST["id_district"];

        $district = new Model_Table_IepDistrict();
        $imagefile = $district->getImageLocation($id_county, $id_district);

        if (!empty($imagefile) && file_exists($uploaddir.$imagefile)) {
          $jsonData = array ( 'status' => 'ok', 'msg' => $config->IMG_LOCATION.$imagefile."?".$rnd );
        } else {
          $jsonData = array ( 'status' => 'error', 'msg' => 'File not found'.$imagefile );
        }

        echo $this->_helper->json->sendJson($jsonData);
        return;
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
