<?php

class App_Form_Relement_DateTextBox extends Zend_Dojo_Form_Element_DateTextBox
{
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function init()
	{
		$decorators = array(
	    	'Description',
			'DijitElement',
			'Label',
	    	);
		
//        $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
        $this->setAttrib('onchange', $this->JSmodifiedCode);
        $this->setFormatLength('short');
        $this->setAttrib('alt', $this->getLabel());
        $this->setAttrib('style', "width:120px;");
        $this->setAttrib('title', $this->getLabel());
        $this->setDecorators($decorators);
		$this->setInvalidMessage('Invalid date specified');
//		$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
		$this->setAllowEmpty(false);
		$this->setRequired(true);
	}

    public function boldLabelPrint()
    {
        $decorators = array(
            'Description',
            'DijitElement',
            'Label',
            );
        
//        $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
        $this->setAttrib('onchange', $this->JSmodifiedCode);
        $this->setFormatLength('short');
        $this->setAttrib('alt', $this->getLabel());
        $this->setAttrib('style', "width:120px;");
        $this->setAttrib('title', $this->getLabel());
        $this->setDecorators($decorators);
        $this->setInvalidMessage('Invalid date specified');
//        $this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
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

