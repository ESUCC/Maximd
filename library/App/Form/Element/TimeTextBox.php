<?php


class App_Form_Element_TimeTextBox extends Zend_Dojo_Form_Element_TimeTextBox
{
    var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
    
    public function init()
    {
        $decorators = array(
            array('Description', array('tag' => 'span')),
            'DijitElement',
            array('Label', array('tag' => 'span')),
            );
        
        $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
//        $this->setFormatLength('short');
        $this->setAttrib('alt', $this->getLabel());
        $this->setAttrib('title', $this->getLabel());
        $this->setDecorators($decorators);
        $this->setInvalidMessage('Invalid time specified');
        $this->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
        $this->setAllowEmpty(false);
        $this->setRequired(true);
    }
    
    function noValidation()
    {
        parent::clearValidators();
        $this->setAllowEmpty(true);
        $this->setRequired(false);  
    }
    
}

