<?php


class App_Form_Element_ComboBox extends Zend_Dojo_Form_Element_ComboBox
{
    public function init()
	{
		$decorators = array(
	        'ViewHelper',
	        array('Label', array('tag' => 'span')),
			array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
    	);

		$this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
		$this->setDecorators($decorators);
		$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
		$this->setAllowEmpty(false);
		$this->setRequired(true);
		$this->setAttrib('dojoType', "dijit.form.ComboBox");
		
	}
	
	function addOnChange($newCode) {
		$this->setAttrib('onchange', $this->getAttrib('onchange') . $newCode);
	}
	
	function noValidation()
	{
		parent::clearValidators();
		$this->setAllowEmpty(true);
		$this->setRequired(false);  
	}
	
}
