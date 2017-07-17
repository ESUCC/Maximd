<?php
class PersonnelmController extends My_Form_AbstractFormController
{
 var  $statusG;
    public function init(){
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
    
    public function updateprivsAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();

    //    include("Writeit.php");
        
        $changePrivs=$this->_getAllParams();

// Mike added this 2-14-2017 so that only district and asst district admins can save these changes.
        $classLevel=5;
        $iep_priv = new Model_Table_PrivilegeTable();
        $UserPrivTable=$iep_priv->getUserInfo2($classLevel);
        $proceed='no';
  //      writevar($UserPrivTable,'this is the user privilege table.');
     
        
        foreach($UserPrivTable as $priv) {
            if($priv['id_district']==$changePrivs['id_district'] and $priv['id_county']==$changePrivs['id_county']
                and $priv['class']<=3 and $priv['status']=='Active')
            {
                $proceed='yes';// This will allow one to edit the district page.
            }
            
        }
      //  writevar($changePrivs,'this is the change privis');
      //  // End of Mike add the if statement encased the updatePrivs command below.
        if($proceed=='yes'){
            $updatePrivs= new Model_Table_IepPrivileges();
            $updatePrivs->updatePrivs($changePrivs);
        }
	

    }

    public function indexbAction()
    {
    //    $this->_helper->layout()->disableLayout();
    //    $this->_helper->viewRenderer->setNoRender(false);
        $request = $this->getRequest();
        $this->view->area = $request->area; 
        $this->view->status = $request->status; 

	if (isset($this->view->area) && $this->view->area != "") {
          $this->_helper->layout()->disableLayout();
        }

      $this->_helper->viewRenderer->setNoRender(true);

      //  include("Writeit.php");
      
       // Get a list of users that are in the iep_privileges table for this school 
       $schoolChoice=$this->_getAllParams();
     //  writevar($schoolChoice,'these are the parameters that came in');
      
       
       
       
       $id_county=$schoolChoice['id_county'];
       $id_district=$schoolChoice['id_district'];
       $id_school=$schoolChoice['id_school'];
       $status=$schoolChoice['status'];
     // $status='true';
    
        
       
       // Get a school list for the user logged in
       $schoolList= new Model_Table_School();
       $listOfSchools= $schoolList->districtSchools($id_county,$id_district);
       $this->view->schools=$listOfSchools;
       //find the default school to render to the view script indexb.phtml
       foreach ($listOfSchools as $schools){
           if($schools['id_school']==$id_school){
               $this->view->schoolName=$schools['name_school'];
               $this->view->districtId=$schools['id_district'];
               $this->view->countyId=$schools['id_county'];
               $this->view->schoolId=$schools['id_school'];
          //     writevar($schools['id_district'],'this is the schools');
           }
           }
      
       $staff= new Model_Table_IepPersonnel();
       $this->statusG=$status;
       $staffList=$staff->getNameFromPrivTable($id_county,$id_district,$id_school,$status);
       $this->view->staffListb=$staffList;
      // writevar($staffList,'this is the staff list');
       
       // Get a school list for the user logged in
       $schoolList= new Model_Table_School();
       $listOfSchools= $schoolList->districtSchools($id_county,$id_district);
       $this->view->schools=$listOfSchools;
       
       
       $privileges= $privs= $_SESSION["user"]["user"]->privs;
      //  $this->writevar1($privileges,'these are the privileges');
        
      
        
        $this->view->privsUser=$privileges;
       
       
       
       
       
       
       echo $this->view->render('personnelm/indexb.phtml');


    }
    
    public function indexAction()
    {
      //  include("Writeit.php");
        $sessUser = new Zend_Session_Namespace ( 'user' );
        $this->view->id_personnel = $sessUser->sessIdUser;
        $id_personnel=$this->view->id_personnel;
        $this->view->name_full=$_SESSION["user"]["user"]->user["name_full"];
        
        
        // Get the demographic info on the user and the privileges for that user.
        $personnelObj = new Model_Table_PersonnelTable();
        $privilegesObj = new Model_Table_IepPrivileges();
        
        
        
        
        // Many staff members have access to staff at other school districts.
        //Need to get this info in order to display the schools in the district from /personnelm/index.phtml
        

        // Mike added this 2-14-2017 because it was showing multiples of the same district in the pull down.
        $temp=$privilegesObj->getPrivileges($id_personnel);
        
       // $this->writevar1($temp,'these are the privs');
      
        
       // Mike added 7-15-2017 to check to see if superuser or class==1
       $this->view->superAmin=false;
       $superAdmin=false;
         
         foreach($temp as $tempo){
           $this->writevar1($tempo['status'],$tempo['class'].' '.'this is the tempo');
           if($tempo['class']==1){ 
               
               $superAdmin=true;
               
               $dists=new Model_Table_IepDistrict();  
               $listOfDistricts=$dists->getAllDistricts();
               $this->view->allDistricts=$listOfDistricts;
               $this->view->superAdmin=true;
           } 
         }
       // end of Mike add to check for superAdmin 
        
        $newTemp[0]=$temp[0];
          //writevar($temp,'lets make sure we got it.');
         
        $x=0;
        foreach($temp as $temporary){
            $found='no';
            //  writevar($temporary['name_district'],'name of the district');
            foreach($newTemp as $newt){
                if($newt['name_district']==$temporary['name_district']) $found='yes';
                 
            }
        
            if($found=='no'){
                $x=$x+1;
                $newTemp[$x]=$temporary;
                
            }
        
        }
         
        
        $this->view->privileges = $newTemp;
  
  
        foreach($this->view->privileges as $key => $priv) {
            $this->view->privileges[$key]['access'] = $personnelObj->validatePrivAccess(
                $priv['id_county'],
                $priv['id_district'],
                $priv['id_school'],
                $priv['class'],
                $this->usersession->sessIdUser,
                $id_personnel
                );
        }
        
        // Get a list of the schools and throw them to the idex view
        
        $id_district=$_SESSION["user"]["user"]->user["id_district"];
        $id_county=$_SESSION["user"]["user"]->user["id_county"];
        
        // Get a list of the schols from the model and bring it back
        $schoolList= new Model_Table_School();
        $listOfSchools= $schoolList->districtSchools($id_county,$id_district);
        $this->view->schools=$listOfSchools;
        
       // writevar($this->view->schools,'this is the school list');
        
        // Get a list of users registered as staff members at the school
      
        //Get a list of users registered at different schools, but still are assigned with privileges at this school
        
    }
    
    public function stylescopeAction()
    {
        // override layout css and remove left panel
        $this->view->headLink()->appendStylesheet('/css/layout_wide.css', 'screen');

        $sessUser = new Zend_Session_Namespace ( 'user' );
        $this->view->id_personnel = $sessUser->sessIdUser;

    }
    public function searchAction()
    {

    }
    public function teamMemberTimeAction()
    {
        if(!$this->getRequest()->getParam('id')) {
            throw new Exception('Id required.');
        } else {
            $id_personnel = $this->getRequest()->getParam('id');
        }
        $this->limitToAdminAccess();
        $this->view->hideLeftBar = true;

        $personnelObj = new Model_Table_PersonnelTable();
        $teamMemberObj = new Model_Table_StudentTeamMember();
        $this->view->students = $teamMemberObj->studentsWithPersonnelAsTeamMember($id_personnel);
        $this->view->personnel = $personnelObj->find($id_personnel)->current();

    }

    /**
     * url format: /personnel/edit/id_personnel/1018569
     */
    
    
    public function viewAction()
    {
        $this->view->hideLeftBar = true;
        /*
         * passed from view
         */
        $this->view->viewOnly = $this->getRequest()->getParam('viewOnly')?true:false;
    
        $id_personnel = $this->getRequest()->getParam('id_personnel');
    
        $personnelObj = new Model_Table_PersonnelTable();
        $privilegesObj = new Model_Table_IepPrivileges();
    
        $this->view->privileges = $privilegesObj->getPrivileges($id_personnel);
        foreach($this->view->privileges as $key => $priv) {
            $this->view->privileges[$key]['access'] = $personnelObj->validatePrivAccess(
                $priv['id_county'],
                $priv['id_district'],
                $priv['id_school'],
                $priv['class'],
                $this->usersession->sessIdUser,
                $id_personnel
                );
        }
    
        // @todo confirm user has access to edit this personnel
        // @todo confirm we have id_county, id_district, id_personnel
    
        $postData = $this->getRequest()->getParams();
    
        if(!$id_personnel) {
            // redirect to search personnel?
            throw new Exception('Personnel Id required');
        }
        // get the model
        $personnelModel = $personnelObj->find($id_personnel)->current();
        if(is_null($personnelModel)) {
            throw new Exception('Personnel not found');
        }
    
        /**
         * validate access to this record by this user
         */
        if($this->usersession->sessIdUser == $id_personnel) {
            // user can edit self
        } elseif(!$personnelObj->validateAccess($id_personnel, $this->usersession->sessIdUser)) {
            throw new Exception('Access Denied');
        }
    
        // get the zend form
        $form = new Form_Personnel();
    
        /**
         * convert to view only form
         */
        if($this->view->viewOnly) {
            $this->convertFormToView($form);
            $form->getElement('cancel')->setLabel('Done');
            $form->getElement('user_name')->removeDecorator('description');
            $form->getElement('address_zip')->removeDecorator('description');
            $form->getElement('phone_work')->removeDecorator('description');
            $form->getElement('email_address')->removeDecorator('description');
            $form->removeElement('online_access');
            $form->removeElement('temp_password');
            $form->removeElement('id_personnel');
            $form->removeElement('update_email_address');
            $form->removeElement('date_expiration');
        }
        // populate the form
        $form->populate($personnelModel->toArray());
    
        // if post - save form
        if ($this->getRequest()->isPost()) {
    
            // don't allow update to match existing email
            //            if($postData['update_email_address'] == $personnelModel->email_address) {
            //                $postData['update_email_address'] = null;
            //            }
    
                // if email changed, update temp field and send email
                if($form->isValid($postData)) {
                     
                    $data = $form->getValues();
                    // do things that affect the form AND the data saved
                    // reset password
                    if ('Reset' == $form->getElement('online_access')->getValue()) {
                        $form->getElement('temp_password')->setValue(App_Password::generatePassword());
                        $form->getElement('online_access')->setValue('');
                        $data['password'] = $form->getElement('temp_password')->getValue();
                        $form->getElement('date_last_pw_change')->setValue(date('Y-m-d'));
                        $data['date_last_pw_change'] = $form->getElement('date_last_pw_change')->getValue();
                        $data['password_encourage_reset'] = true;
                    }
    
                    // data only changes
                    unset($data['id_personnel']);
                    unset($data['online_access']);//no longer saved - used on page though and needs to be submitted
    
                    // submitted update address is valid and is different from database
                    //                if(''!=$postData['update_email_address'] && $postData['update_email_address']!=$personnelModel->update_email_address) {
                    //                    $personnelModel->update_email_address = $form->getElement('update_email_address')->getValue();
                    //                    $personnelModel->update_email_hash = $personnelObj->getUniqueHash('Model_Table_PersonnelTable', 'update_email_hash');
                    //                    if($personnelModel->save()) {
                    //                        App_Email_PasswordHelper::initEmail();
                    //                        App_Email_PasswordHelper::sendEmailChangeConfirm(null,
                    //                            $personnelModel->update_email_address,
                    //                            $personnelModel->name_first . ' ' . $personnelModel->name_last,
                    //                            $personnelModel->update_email_hash);
                    //                        $this->view->message = "A confirmation email has been sent to: " . $personnelModel->update_email_address;
                    //
                    //                    }
                    //                }
    
                    // save
                    $where = $personnelObj->getAdapter()->quoteInto('id_personnel = ?', $id_personnel);
                    //                if(''==$data['update_email_address']) {
                    //                    $data['update_email_hash'] = '';
                    //                }
                    //                unset($data['update_email_address']);
                    unset($data['temp_password']);
                    unset($data['update_email_hash']);
                    $data['online_access'] = 'Enabled';
                    $personnelObj->update($data, $where);
                    $this->addGlobalMessage('Personnel record saved successfully.');
                    }
    
    
                    // restore display only values
                    // loop through form elements and update if disabled
                    foreach($form->getElements() as $n => $e) {
                        if('disabled'==$e->getAttrib('disabled') || $e->getAttrib('disabled')){
                            $form->getElement($n)->setValue($personnelModel->$n);
                        }
                    }
                }
                $this->view->form_personnel = $form;
    
    
                /**
                 * student charts
                 */
                $sessUser = new Zend_Session_Namespace ( 'user' );
                $privCheck = new My_Classes_privCheck($sessUser->user->privs);
                $admin = 1==$privCheck->getMinPriv()?true:false;
                if (!$this->view->viewOnly && ($sessUser->sessIdUser == $id_personnel || $admin)) {
                    $studentChartsTable = new Model_Table_StudentChartTemplate();
                    $this->view->templates = $studentChartsTable->getMyCharts($id_personnel);
                }
    
    
            }
    
       public function editAction()  {
           // include("Writeit.php");
            
                //   include("Writeit.php");
                $this->view->hideLeftBar = true;
                /*
                 * passed from view
                 */
                $this->view->viewOnly = $this->getRequest()->getParam('viewOnly')?true:false;
            
                $id_personnel = $this->getRequest()->getParam('id_personnel');
            
            
                $personnelObj = new Model_Table_PersonnelTable();
                $privilegesObj = new Model_Table_IepPrivileges();
            
                $this->view->privileges = $privilegesObj->getPrivileges($id_personnel);
                foreach($this->view->privileges as $key => $priv) {
                    $this->view->privileges[$key]['access'] = $personnelObj->validatePrivAccess(
                        $priv['id_county'],
                        $priv['id_district'],
                        $priv['id_school'],
                        $priv['class'],
                        $this->usersession->sessIdUser,
                        $id_personnel
                        );
                }
            
                // @todo confirm user has access to edit this personnel
                // Done at the bottom
            
                // @todo confirm we have id_county, id_district, id_personnel
            
                $postData = $this->getRequest()->getParams();
            
                if(!$id_personnel) {
                    // redirect to search personnel?
                    throw new Exception('Personnel Id required');
                }
                // get the model
                $personnelModel = $personnelObj->find($id_personnel)->current();

                
                // Mike added this 2-2-2017 because wants user to see existing privilege list Name of the person being edited.
                $this->view->userInfo=$personnelModel;
                //  writevar($this->view->userInfo['id_school'],'this is the personnel in personnelm');
                if(is_null($personnelModel)) {
                    throw new Exception('Personnel not found');
                }
            
                /**
                 * validate access to this record by this user
                 Mike shut this down on 1-27-2017.  Still have to take into accout the logged in user
                 */
                 
                /*
                 *
                 if($this->usersession->sessIdUser == $id_personnel) {
                 // user can edit self
                 } elseif(!$personnelObj->validateAccess($id_personnel, $this->usersession->sessIdUser)) {
                 // throw new Exception('Access Denied');
                 }
                 */
                // get the zend form
                $form = new Form_Personnel();
            
                /**
                 * convert to view only form
                 */
                if($this->view->viewOnly) {
                    $this->convertFormToView($form);
                    $form->getElement('cancel')->setLabel('Done');
                    $form->getElement('user_name')->removeDecorator('description');
                    $form->getElement('address_zip')->removeDecorator('description');
                    $form->getElement('phone_work')->removeDecorator('description');
                    $form->getElement('email_address')->removeDecorator('description');
                    $form->removeElement('online_access');
                    $form->removeElement('temp_password');
                    $form->removeElement('id_personnel');
                    $form->removeElement('update_email_address');
                    $form->removeElement('date_expiration');
                }
                // populate the form
                $form->populate($personnelModel->toArray());
            
                // if post - save form
                if ($this->getRequest()->isPost()) {
            
                    // don't allow update to match existing email
                    //            if($postData['update_email_address'] == $personnelModel->email_address) {
                    //                $postData['update_email_address'] = null;
                    //            }
            
                        // if email changed, update temp field and send email
                        if($form->isValid($postData)) {
                             
                            $data = $form->getValues();
                            // do things that affect the form AND the data saved
                            // reset password
                            //        writevar($data,'this is the data');
                            if ('Reset' == $form->getElement('online_access')->getValue()) {
                                $form->getElement('temp_password')->setValue(App_Password::generatePassword());
                                $form->getElement('online_access')->setValue('');
                                $data['password'] = $form->getElement('temp_password')->getValue();
                                $form->getElement('date_last_pw_change')->setValue(date('Y-m-d'));
                                $data['date_last_pw_change'] = $form->getElement('date_last_pw_change')->getValue();
                                $data['password_encourage_reset'] = true;
                            } 
            
                            // data only changes
                            unset($data['id_personnel']);
                            unset($data['online_access']);//no longer saved - used on page though and needs to be submitted
            
                            // submitted update address is valid and is different from database
                            //                if(''!=$postData['update_email_address'] && $postData['update_email_address']!=$personnelModel->update_email_address) {
                            //                    $personnelModel->update_email_address = $form->getElement('update_email_address')->getValue();
                            //                    $personnelModel->update_email_hash = $personnelObj->getUniqueHash('Model_Table_PersonnelTable', 'update_email_hash');
                            //                    if($personnelModel->save()) {
                            //                        App_Email_PasswordHelper::initEmail();
                            //                        App_Email_PasswordHelper::sendEmailChangeConfirm(null,
                            //                            $personnelModel->update_email_address,
                            //                            $personnelModel->name_first . ' ' . $personnelModel->name_last,
                            //                            $personnelModel->update_email_hash);
                            //                        $this->view->message = "A confirmation email has been sent to: " . $personnelModel->update_email_address;
                            //
                            //                    }
                            //                }
            
                            // save
                            $where = $personnelObj->getAdapter()->quoteInto('id_personnel = ?', $id_personnel);
                            //                if(''==$data['update_email_address']) {
                            //                    $data['update_email_hash'] = '';
                            //                }
                            //                unset($data['update_email_address']);
                            unset($data['temp_password']);
                            unset($data['update_email_hash']);
                            $data['online_access'] = 'Enabled';
                            //    writevar($where,'this is the data we want to grab in the where');
                            $personnelObj->update($data, $where);
                            $this->addGlobalMessage('Personnel record saved successfully.');
                            }
            
            
                            // restore display only values
                            // loop through form elements and update if disabled
                            foreach($form->getElements() as $n => $e) {
                                 
                                if('disabled'==$e->getAttrib('disabled') || $e->getAttrib('disabled')){
                                    $form->getElement($n)->setValue($personnelModel->$n);
                                }
                            }
                        }
                        /* Mike added this 2-9-2017 so that the edit form does not show up if the existing user does not have access to this iep_personnel form.
                         $where holds the id of the current user we are working on.
                         Mike added this before the final $this->view->form_personnel = $form;
                         */
                        // get the id_district and id_county of $where person;
            
                        //find out the privileges from the logged in user
                        $sessionUserId=$_SESSION["user"]["id_personnel"];
                        //  writevar($sessionUserId,'this is the session user id');
            
            
                        $privilegeForForm = new Model_Table_IepPrivileges();
                        $sessUserPrivileges = $privilegeForForm->getPrivileges($sessionUserId);
                        //   writevar($sessUserPrivileges,'here are the privileges');
            
            
                        $personnelDistrict=new Model_Table_IepPersonnel();
                        $personInfo=$personnelDistrict->getIepPersonnel($id_personnel);
                         
                         
            
                        $countyUser=$personInfo['id_county'];
                        $districtUser=$personInfo['id_district'];
                         
                        //   writevar($countyUser,'this is the county info');
                        //   writevar($districtUser,'this is the district user');
                        /*
                         * Note Mike did this so that just Privileges show up for the end user. Still have to take into account
                         * editing for oneself which is not programmed in yet.  Also, need to throw the users name to the view script.
                         */
                        
                        $found='false';
                        
                        
                        foreach($sessUserPrivileges as $sessUser){
                          // Mike added this July 17th 2017 so that superadmins can get access to 
                          // the edit function in personnelm
                          
                            if($sessUser['class']==1 && $sessUser['status']=='Active') $found=true;
                          // End of Mike add July 17th.  
                            if($sessUser['id_district']==$districtUser && $sessUser['id_county']==$countyUser && $sessUser['class']<=3
                                && $sessUser['status']=='Active') $found='true';
                        }
                        if($found=='true'){
                            $this->view->form_personnel = $form;
                            $this->view->changePassword=true;
                            // setup the link in the NavigationSubmenu.php so that we have a link to 
                            // the New Privilege
                            // Mike 3-15-2017
                            $this->view->staffmember=$personInfo;
                //            writevar($form,'this is the form for the person info.');
                        }
            
                         
                        /**
                         * student charts
                         */
                        $sessUser = new Zend_Session_Namespace ( 'user' );
                        $privCheck = new My_Classes_privCheck($sessUser->user->privs);
                        $admin = 1==$privCheck->getMinPriv()?true:false;
                        if (!$this->view->viewOnly && ($sessUser->sessIdUser == $id_personnel || $admin)) {
                            $studentChartsTable = new Model_Table_StudentChartTemplate();
                            $this->view->templates = $studentChartsTable->getMyCharts($id_personnel);
                        }
            
            
      }

    public function addGlobalMessage($msg) {
        $messageSpace = new Zend_Session_Namespace('message');
        if(isset($messageSpace->globalMessage)) {
            $messageSpace->globalMessage .= $msg . "<BR />";
        } else {
            $messageSpace->globalMessage = $msg . "<BR />";
        }
    }
    
    public function hasActivePrivilegesAction() {
    	// ajax requests for this page should not include site layout
    	if ($this->getRequest()->isXmlHttpRequest()) {
    		$this->_helper->layout()->disableLayout();
    		$this->_helper->viewRenderer->setNoRender(true);
    	}
    	
    	$id_personnel = $this->getRequest()->getParam('id_personnel');
    	
    	$personnelObj = new Model_Table_PersonnelTable();
    	$privilegesObj = new Model_Table_IepPrivileges();
    	
    	$privileges = $privilegesObj->getPrivileges($id_personnel);
    	$response = false;
    	
    	foreach ($privileges AS $privilege) {
    		if ($privilege['status'] == 'Active') {
    			$response = true;
    		}
    	}
    	
    	echo Zend_Json::encode(
    			array(
    					'response' => $response,
    			)
    	);
    }
    
    public function deletePrivilegeAction()
    {
        // ajax requests for this page should not include site layout
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
        }

        $privilegeId = $this->getRequest()->getParam('id_privileges');
        $privilegeObj = new Model_Table_PrivilegeTable();
        $privilege = $privilegeObj->find($privilegeId)->current();
        if (is_null($privilege)) {
            throw new Exception('Privilege not found');
        }

        /**
         * make sure current user has access to remove the privilege
         * validate access
         */
        $personnelObj = new Model_Table_PersonnelTable();
        if(!$personnelObj->validateAccess($privilege['id_personnel'], $this->usersession->sessIdUser)) {
            throw new Exception('Access Denied');
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();
            if (isset($postData['submit'])) {
                $privilege->status = 'Removed';
                $result = $privilege->save();
                if ($this->getRequest()->isXmlHttpRequest()) {
                    echo Zend_Json::encode(
                        array(
                            'deleted' => true,
                            'result' => 'Privilege record deleted successfully.'
                        )
                    );
                } else {
                    $this->addGlobalMessage('Privilege record deleted successfully.');
                }
                die;
            }
        }

        // if ajax, render just the form
        if ($this->getRequest()->isXmlHttpRequest()) {
            echo Zend_Json::encode(array('result' => $this->view->form->render()));
        }
    }
    public function updatePrivilegeAction()
    {
        // ajax requests for this page should not include site layout
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
        }

        $privilegeId = $this->getRequest()->getParam('id_privileges');
        $privilegeObj = new Model_Table_PrivilegeTable();
        $privilege = $privilegeObj->find($privilegeId)->current();
        if (is_null($privilege)) {
            throw new Exception('Privilege not found');
        }

        /**
         * make sure current user has access to remove the privilege
         * validate access
         */
        $personnelObj = new Model_Table_PersonnelTable();
        if(!$personnelObj->validateAccess($privilege['id_personnel'], $this->usersession->sessIdUser)) {
            throw new Exception('Access Denied');
        }
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();
            switch($this->getRequest()->getParam('status')) {
                case 'Active':
                    $newStatus = 'Active';
                    break;
                case 'Inactive':
                    $newStatus = 'Inactive';
                    break;
                default:
                    $newStatus = 'Inactive';
                    break;
            }

            if (isset($postData['submit'])) {
                $privilege->status = $newStatus;
                $result = $privilege->save();
                if ($this->getRequest()->isXmlHttpRequest()) {
                    echo Zend_Json::encode(
                        array(
                            'success' => 1,
                            'status' => $newStatus,
                            'result' => 'Privilege record updated successfully.'
                        )
                    );
                } else {
                    $this->addGlobalMessage('Privilege record deleted successfully.');
                }
                die;
            }
        }

        // if ajax, render just the form
        if ($this->getRequest()->isXmlHttpRequest()) {
            echo Zend_Json::encode(array('result' => $this->view->form->render()));
        }
    }
    public function deleteStudentChartTemplateAction() {
        // ajax requests for this page should not include site layout
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
        }

        $studentChartTemplateId = $this->getRequest()->getParam('id_student_chart_template');
        $studentChartTemplateTable = new Model_Table_StudentChartTemplate();
        $studentChartTemplate = $studentChartTemplateTable->find($studentChartTemplateId)->current();
        if (is_null($studentChartTemplate)) {
            throw new Exception('Student Chart Template not found');
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();
            if (isset($postData['submit'])) {
                $studentChartTemplate->delete();
                if ($this->getRequest()->isXmlHttpRequest()) {
                    echo Zend_Json::encode(
                        array(
                            'deleted' => true,
                            'result' => 'Student Chart Template record deleted successfully.'
                        )
                    );
                } else {
                    $this->addGlobalMessage('Student Chart Template record deleted successfully.');
//                    $this->_redirector->gotoSimple('Privileges', 'student', null, array('student' => $id_student));
                }
                die;
            }
        }

//        $this->view->form = $form;

        // if ajax, render just the form
        if ($this->getRequest()->isXmlHttpRequest()) {
            echo Zend_Json::encode(array('result' => $this->view->form->render()));
        }

    }

    public function sendLoginInfoAction()
    {
        if (!$this->getRequest()->isPost()) {
            echo Zend_Json::encode(
                array(
                    'success' => false,
                    'result' => 'Only posts are allowed.'
                )
            );
        } else {
            if (!$this->getRequest()->getParam('id_personnel')) {
                throw new Exception('Id required.');
            } else {
                $id_personnel = $this->getRequest()->getParam('id_personnel');
            }
            $personnelObj = new Model_Table_PersonnelTable();
            $personnel = $personnelObj->find($id_personnel)->current();
            App_Email_PasswordHelper::initEmail();
            $sent = App_Email_PasswordHelper::sendLoginInfo(null,
                $personnel->email_address,
                $personnel->name_first . ' ' . $personnel->name_last,
                $personnelObj->getUserName($id_personnel),
                $personnel->password
            );
            echo Zend_Json::encode(
                array(
                    'success' => $sent,
                )
            );
        }
        die;
    }

}
