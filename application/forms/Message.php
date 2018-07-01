<?php
class Form_Message extends Form_AbstractForm
{
	public function init() {
        $this->setMethod ( 'post' );
        $this->setAttrib( 'class', $this->getAttrib( 'class' ) . ' zend_form' );

        /**
         * navigation buttons
         */
        $this->submit = new App_Form_Element_Submit('submit', array('label' => 'Save'));
        $this->submit->removeDecorator('label');
        $this->submit->setAllowEmpty(true);
        $this->submit->setRequired(false);

        $this->cancel = new App_Form_Element_Submit('cancel', array('label' => 'Cancel'));
        $this->cancel->removeDecorator('label');
        $this->cancel->setAllowEmpty(true);
        $this->cancel->setRequired(false);
//        $this->addDisplayGroup(array(
//                $this->cancel,
//                $this->submit,
//            ), 'navigation1');
//        $this->getDisplayGroup('navigation1')->setAttrib('style', 'text-align:right;');
//        $this->getDisplayGroup('navigation1')->setAttrib('class', 'navigation');


        $this->id_message = new App_Form_Element_Text('id_message', array('label' => 'Message ID:'));
        $this->id_message->setAttrib('readonly', 'readonly');
        $this->id_message->setIgnore(true);
        $this->id_message->setAllowEmpty(true);
        $this->id_message->setRequired(false);

        $this->status = new App_Form_Element_Select('status', array('label' => 'Status:'));
        $this->status->setMultiOptions(array('Active'=>'Active', 'Inactive'=>'Inactive', 'Removed'=>'Removed'));
        $this->status->getDecorator('label')->setOption('class', 'srsLabel');

        $this->id_user= new App_Form_Element_Text('id_user', array('label' => 'User ID'));
        $this->id_user->setRequired(false);
        $this->id_user->setAllowEmpty(true);
        $this->id_user->addFilter(new Zend_Filter_Int());
        $this->id_user->addFilter(new Zend_Filter_Null());

        $this->subject= new App_Form_Element_Text('subject', array('label' => 'Subject'));
        $this->subject->setRequired(false);
        $this->subject->setAllowEmpty(true);

        $this->message= new App_Form_Element_Textarea('message', array('label' => 'Message'));
        $this->message->setRequired(false);
        $this->message->setAllowEmpty(true);

        $this->addDisplayGroup(array(
            $this->id_message,
            $this->status,
            $this->id_user,
            $this->subject,
            $this->message,
        ), 'main', array('legend'=>'Message Information'));

        $this->addDisplayGroup(array(
                $this->cancel,
                $this->submit,
            ), 'navigation2');

        $this->getDisplayGroup('navigation2')->setAttrib('style', 'text-align:right;');
        $this->getDisplayGroup('navigation2')->setAttrib('class', 'navigation');

        $this->removeSrsFormHelpers($this);
        $this->addErrorDecorator($this);
        return $this;
    }
}
