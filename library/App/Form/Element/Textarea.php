<?php

class App_Form_Element_Textarea extends Zend_Form_Element_Textarea
{

    public function init()
	{
		$simpleDecorators = array(
	        'ViewHelper',
	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
    	);
    	
		$this->setAttrib('onchange', "javascript:modified('', '', '', '', '', '');colorMeById(this.id);updateInlineValueTextArea(this.id, arguments[0]);");
		$this->setAttrib('onfocus', "javascript:modified('', '', '', '', '', '');colorMeById(this.id);");
		
//    	$this->setAttrib('onchange', "javascript:updateInlineValueTest(this.id, arguments[0], true);");
//		$this->setAttrib('onfocus', "javascript:modified('', '', '', '', '', '');colorMeById(this.id);return true;");
    	
    	$this->setAttrib('alt', $this->getLabel());
		$this->setAttrib('title', $this->getLabel());

		$this->setAttrib('extraPlugins', '[ \'dijit._editor.plugins.AlwaysShowToolbar\']');
		$this->setAttrib('height', "50");
		$this->setAttrib('dojoType', "dijit.Editor");
		$this->setAttrib('AlwaysShowToolbar', 'true');
//		$this->setAttrib('style', 'background-color:green;width:800px;'); // grey
		
		$this->setDecorators ( $simpleDecorators);
		$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
	}
}
