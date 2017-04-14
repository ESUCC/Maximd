<?php

class App_Form_Relement_Button extends Zend_Form_Element_Button
{
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function init()
	{
	    $decorators = array(
	        'ViewHelper',
//	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
	    );	

		$this->setAttrib('onFocus', $this->JSmodifiedCode);
//	    $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
		$this->setAttrib('alt', $this->getLabel());
		$this->setAttrib('title', $this->getLabel());

		$this->setDecorators($decorators);
//		$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
	}
	
	
}