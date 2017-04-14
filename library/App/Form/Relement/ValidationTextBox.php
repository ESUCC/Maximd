<?php

class App_Form_Relement_ValidationTextBox extends Zend_Dojo_Form_Element_ValidationTextBox
{
	public $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function init()
	{
		$simpleDecorators = array(
        	'DijitElement',
            'Label',
		    'Description'
    	);
    	
//		$this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
		$this->setAttrib('alt', $this->getLabel());
		$this->setAttrib('title', $this->getLabel());

		$this->setDecorators ( $simpleDecorators);
//		$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
	}
}