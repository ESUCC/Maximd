<?php
class Form_NewAccountRequest extends Form_AbstractForm
{

    function init()
    {
        $options = array(
            2 => '<span class="focusLink">District Manager</span>',
            3 => '<span class="focusLink">Associate District Manager (ADM)</span>',
            4 => '<span class="focusLink">School Manager</span>',
            5 => '<span class="focusLink">Associate School Manager (ASM)</span>',
            6 => '<span class="focusLink">Case Manager</span>',
            8 => '<span class="focusLink">Specialist/Consultant</span>',
            7 => '<span class="focusLink">School Staff/Teachers</span>',
            10 => '<span class="focusLink">Service Coordinator</span>'
        );

        $this->user_type = new App_Form_Element_Radio('user_type', array('label' => "Please choose one of the following options:"));
        $this->user_type->setMultiOptions($options);
        $this->user_type->setSeparator("<BR />");
        $this->user_type->removeDecorator('Label');
        $this->user_type->escape = false;
        $this->user_type->setAttrib('onFocus', "");
        $this->user_type->setAttrib('onchange', "");

        $this->submit = new App_Form_Element_Submit('submit', array('label' => "Next"));
        $this->submit->setAttrib('style', 'display:none;');
        $this->submit->setDecorators(array('viewHelper'));

        $this->cancel = new App_Form_Element_Button('cancel', array('label' => "Cancel"));
        $this->cancel->setDecorators(array('viewHelper'));
        $this->cancel->setAttrib('onclick', "window.location='/'");

        return $this;
    }

}
