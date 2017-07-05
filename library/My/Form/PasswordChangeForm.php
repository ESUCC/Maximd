<?php
//class Form_Personnel extends Form_AbstractForm {
 
 class My_Form_PasswordChangeForm extends Form_AbstractForm
  {
     
      public $elementDecorators = array(
          array('ViewHelper'),
          array('Errors'),
          //     array('HtmlTag'=>array('tag'=>'h3')),
          array('Label'=>array('placement'=>'wrap'))
      );

      public function init()
      {
      
          $this->newPassword = new Zend_Form_Element_Password('password', array('label' => 'Password:'));
          $this->newPassword->renderPassword = true;
          $this->newPassword->setDecorators(array(
              'ViewHelper',
              'Errors',
              'Label'
          
          ));
          $this->newPassword->addValidator(new Zend_Validate_StringLength(array('min' => 6)));
          
          $this->newPassword_confirm = new App_Form_Element_Password('password_confirm', array('label' => 'Confirm Password:'));
          $this->newPassword_confirm->renderPassword = true;
          $this->newPassword_confirm->setDecorators(array(
              'ViewHelper',
              'Errors',
              'Label'
          
          ));
          $this->newPassword_confirm->addValidator(new Zend_Validate_Identical('password'));
          $this->newPassword_confirm->setErrorMessages(array('Passwords must match and be six or more characters.'));
      
         
      
          $this->submit = new App_Form_Element_Submit('Save');
          $this->submit->setAttrib('Save', 'submitbutton');
      }
}