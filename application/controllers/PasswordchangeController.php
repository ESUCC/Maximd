<?php

class PasswordchangeController extends App_Zend_Controller_Action_Abstract
{
    
    
    public function indexAction()
    {
        $this->_helper->layout()->disableLayout();
      //  include("Writeit.php");
        $postData = $this->getRequest()->getParams();
        if(!isset($postData['password'])) $postData['password']='';
        if(!isset($postData['password_confirm'])) $postData['password_confirm']='';
        $form = new My_Form_PasswordChangeForm();
        $updatePassword=new Model_Table_IepPersonnelInfo();
        $this->view->form = $form;
       
        $fullName=$postData['name_first']." ".$postData['name_last'];
        $this->view->fullName=$fullName;
        
     //   writevar($postData,'this is the data post');
         if($postData['password']!='' && ($postData['password']==$postData['password_confirm'])) {   
           
             $id_personnel=$postData['id_personnel'];
             $password=$postData['password'];
             
             $updatePassword->updatePassword($id_personnel, $password);
            
             
           
           $this->redirect('/personnelm/edit/id_personnel/'.$postData['id_personnel']);
       }
       else{
          
           if($postData['password']!='')
            if(!$form->isValid($this->getRequest()->getPost())) {
               $this->view->messages=$form->getmessages;
            }
           }
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
       
       public function playvideoAction() {
         //  $this->_helper->layout()->disableLayout();
           $this->_helper->layout->disableLayout();
          // $this->_helper->viewRenderer->setNoRender(true);
          
       }
       
       
       // This is the function work added by Maxim Jun14 Mike 
       public function changepasswordAction()
       {
           $this->_helper->layout()->disableLayout();
           $postData = $this->getRequest()->getParams();
          // mike
        //   $this->writevar1($postData,'this is the posted data');
           $personnelInfo= new Model_Table_IepPersonnel();
           $personInfo=$personnelInfo->getIepPersonnel($postData['id_personnel']);
          // $this->writevar1($personInfo,'this is the persons info');
           
           
           /* Mike added this 7-6-2017  in order to prevent unauthorized users from changing the 
              password for everyone.  
              
           */
           $allowChange=false;
          
           $idUser=$_SESSION['user']['id_personnel'];
           $listPrivs=$_SESSION['user']['user']->privs;
           $distId=$personInfo['id_district'];
           $countyId=$personInfo['id_county'];
           $schoolId=$personInfo['id_school'];
            
           foreach($listPrivs as $privilege) {
               if($privilege['class']==1) $allowChange=true;
                
               // Check to see if a dist admin or assoc dist admin
               if($distId==$privilege['id_district'] && $countyId==$privilege['id_county']
                   && $privilege['class']<=3 && $privilege['status']=='Active') {
                       $allowChange=true;
                   }
               // Check to see if School Admin or Assit School Admin
                   if($distId==$privilege['id_district'] && $countyId==$privilege['id_county']
                       && $privilege['class']<=5 && $privilege['status']=='Active' 
                       && $privilege['id_school']==$schoolId) 
                       {
                           $allowChange=true;
                       }
                 // Check to see if it the user who is logged in
                     if($idUser==$postData['id_personnel']) $allowChange=true;
                  
                    }
           
           
           // end mike
    //      $this->writevar1($allowChange,'this is the allowchange');
        
        if($allowChange==true) {
         //   $this->writevar1($postData['id_personnel'],'this is the id of hte personnel');
            $this->writevar1($postData['password'],'this is hte new pw');
            
            if(!isset($postData['newPassword']))
           {
               
               $form = new My_Form_PasswordChangeForm();
               $fullName=$postData['name_first']." ".$postData['name_last'];
               $id_personnel = $postData['id_personnel'];
               $this->view->form = new My_Form_PasswordChangeForm();
               $this->view->id_personnel = $id_personnel;
               $this->view->fullName = $fullName;
           } else {
               $updatePassword = new Model_Table_IepPersonnelInfo();
               $id_personnel = $postData['id_personnel'];
               $password = $postData['newPassword'];
               $message = 'Password updated successfully.';
               $success = 1;
               try {
                   $updatePassword->updatePassword($id_personnel, $password);
               } catch (\Exception $e) {
                   $message = 'Error occured during password change.'. $e;
                   $success = 0;
               }
       
       
               if ($this->getRequest()->isXmlHttpRequest()) {
                   echo Zend_Json::encode(
                       array(
                           'success' => $success,
                           'message' => $message
                       )
                       );
               } else {
                   $this->addGlobalMessage($message);
               }
               die;
           }
        } 
        if($allowChange==false) {
            
            $message='You dont have the correct rights to change the password.';
            $success=0;
            echo "<h2><center> $message</center></h2>";
            die();
            if ($this->getRequest()->isXmlHttpRequest()) {
                echo Zend_Json::encode(
                   array(
                    'success' => $success,
                    'message' => $message
                     )
                   );
            }
            else {
                $this->addGlobalMessage($message);
              }
        }
       }
       public function index2Action()
       {
           $this->_helper->layout()->disableLayout();
           include("Writeit.php");
           $postData = $this->getRequest()->getParams();
           if(!isset($postData['password'])) $postData['password']='';
           if(!isset($postData['password_confirm'])) $postData['password_confirm']='';
           $form = new My_Form_PasswordChangeForm();
           $updatePassword=new Model_Table_IepPersonnelInfo();
           $this->view->form = $form;
            writevar($form,'this is the form data');
           $fullName=$postData['name_first']." ".$postData['name_last'];
           $this->view->fullName=$fullName;
       
           //   writevar($postData,'this is the data post');
           if($postData['password']!='' && ($postData['password']==$postData['password_confirm'])) {
                
               $id_personnel=$postData['id_personnel'];
               $password=$postData['password'];
                
               $updatePassword->updatePassword($id_personnel, $password);
       
                
                
               $this->redirect('/personnelm/edit/id_personnel/'.$postData['id_personnel']);
           }
           else{
       
               if($postData['password']!='')
                   if(!$form->isValid($this->getRequest()->getPost())) {
                       $this->view->messages=$form->getmessages;
                   }
           }
       }
    
    public function subpasswordAction()
    {
       $fullName=$_SESSION['user']['user']->user['name_full'];
       $personnelId=$_SESSION['user']['id_personnel'];
  //     writevar($fullName,'this is the full name');
  //    writevar($personnelId,'this is the personnel id');
       
       
       $form = new My_Form_PasswordChangeForm();
       $updatePassword=new Model_Table_IepPersonnelInfo();
       $this->view->form = $form;
       $this->view->fullName=$fullName;
       
       $postData = $this->getRequest()->getParams();
   //    writevar($postData,'thios is the first post data');
       if(!isset($postData['password'])) $postData['password']='';
       if(!isset($postData['password_confirm'])) $postData['password_confirm']='';
       
       
     //  writevar($postData,'this is the post data');
      
       if($postData['password']!='' && ($postData['password']==$postData['password_confirm'])) {
            
           $id_personnel=$postData['id_personnel'];
           $password=$postData['password'];
           $updatePassword->updatePassword($personnelId, $password);
            $this->redirect('home');
       }
       else{
       
           if($postData['password']!='')
               if(!$form->isValid($this->getRequest()->getPost())) {
                   $this->view->messages=$form->getmessages;
               }
       }
       
       
       
       }
    
     }// end of class
    