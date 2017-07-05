<?php

class PasswordchangeController extends App_Zend_Controller_Action_Abstract
{
    
    
    public function indexAction()
    {
        $this->_helper->layout()->disableLayout();
        include("Writeit.php");
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
    
       // This is the function work added by Maxim Jun14 Mike 
       public function changepasswordAction()
       {
           $this->_helper->layout()->disableLayout();
           $postData = $this->getRequest()->getParams();
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
    