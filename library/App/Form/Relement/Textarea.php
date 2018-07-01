<?php

class App_Form_Relement_Textarea extends Zend_Form_Element_Textarea
{
	public $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function init()
	{
		$simpleDecorators = array(
	        'ViewHelper',
//	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
    	);
    	
		$this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
		$this->setAttrib('alt', $this->getLabel());
		$this->setAttrib('title', $this->getLabel());

		$this->setAttrib('extraPlugins', '[ \'dijit._editor.plugins.AlwaysShowToolbar\']');
		$this->setAttrib('height', "");
		$this->setAttrib('dojoType', "dijit.Editor");
		$this->setAttrib('AlwaysShowToolbar', 'true');

		$this->setDecorators ( $simpleDecorators);
//		$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
	}
}