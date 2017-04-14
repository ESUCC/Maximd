<?php

class App_Form_Relement_Select extends Zend_Form_Element_Select
{
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function init()
	{
		$decorators = array(
	        'ViewHelper',
	        'Label',
//			array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
    	);

//		$this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
		$this->setDecorators($decorators);
//		$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
		$this->setAllowEmpty(false);
		$this->setRequired(true);
	}
	
	
}
