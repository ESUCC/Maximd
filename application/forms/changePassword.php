<?php
class Form_NewAccountRequestDetails extends Form_AbstractForm
{

    function init()
    {
       

        $this->password = new App_Form_Element_Text('password', array('label' => 'Password'));
        $this->password->addValidator('stringLength', true, array(7, 99));
        $this->password->addValidator('regex', true, '/^(?=.*\d)(?=.*[a-zA-Z]).{7,99}$/');
        $this->password->addValidator('stringLength', false, array(6));
        $this->password->setDescription('Must contain at least 7 characters with both text & numbers');
        $this->password->addErrorMessage('Must contain at least 7 characters with both text & numbers');

        $this->password_confirm = new App_Form_Element_Text('password_confirm', array('label' => 'Confirm Password'));
        $this->password_confirm->addValidator('identical', true, 'password');
        $this->password_confirm->addErrorMessage('Password and Confirm Password must match. ');
        $this->password_confirm->setIgnore(true);

        return $this;
    }

}
