<?php

class App_Form_Element_TextareaPlain extends Zend_Form_Element_Textarea
{

    public function init()
	{
		$simpleDecorators = array(
	    	array('Label', array('tag' => 'span', 'class' => 'srsTextLabel srsLabel', 'style' => 'vertical-align:top;')),
			'ViewHelper',
	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
    	);
    	
		$this->setAttrib('onchange', "javascript:modified('', '', '', '', '', '');colorMeById(this.id);");
		$this->setAttrib('onfocus', "javascript:modified('', '', '', '', '', '');colorMeById(this.id);");
		
		$this->setAttrib('alt', $this->getLabel());
		$this->setAttrib('title', $this->getLabel());
		$this->setAttrib('style', 'background-color:#CCFFCC;');

		$this->setDecorators ( $simpleDecorators);
		$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
	}
}
