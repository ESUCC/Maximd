<?php

class App_Form_Relement_Submit extends Zend_Form_Element_Submit
{
	public $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function init()
	{
		if($this->getLabel() == '') $this->setLabel($this->getName());
		$simpleDecorators = array(
        	'ViewHelper',
	    	'Label',
	    	'Description',
    	);
    	
		$this->setAttrib('alt', $this->getLabel());
		$this->setAttrib('title', $this->getLabel());
		$this->setDecorators ( $simpleDecorators);
//		$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );

		// default validation - do not allow empty
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