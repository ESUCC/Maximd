<?php

class App_Form_Relement_Editor extends Zend_Dojo_Form_Element_Editor
{
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function init()
	{
		$decorators = array(
//			array('Description', array('tag' => 'span')),
//	    	array('Label', array('tag' => 'span')),
			'DijitElement',
//            array (array ('data' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'printEditor') ),
    	);
				
		$this->setAttrib('onFocus', $this->JSmodifiedCode);
//		$this->setAttrib('class', 'textbox');
		$this->setAttrib('alt', $this->getLabel());
		$this->setAttrib('title', $this->getLabel());
//		$this->setAttrib('style', "background-color:yellow;");
		
		// the height setting line below causes an error on page 4 when there
		// is more than one goal
		// the ertror message returned is (s = null)
//		$this->setAttrib('height', ""); // set to "" to enable autoexpand when AlwaysShowToolbar is true
		
//        $this->setAttrib('plugins', "['bold','italic','|','createLink']");
		$this->setAttrib('extraPlugins', "['dijit._editor.plugins.AlwaysShowToolbar']");
		$this->setAttrib('minHeight', '100px');
//		$this->setAttrib('height', "");
		
		// add onchange to update hidden input with value to submit
		// and a function to color the div on modification
//        $this->setAttrib ( 'onchange', $this->JSmodifiedCode. "updateInlineValue(this.id, arguments[0]);colorMeById(this.id);" ); 
        $this->setAttrib ( 'onchange', $this->JSmodifiedCode. "updateInlineValue(this.id, arguments[0]);" ); 
        
		$this->addFilter(new My_Form_Filter_StringMatchHtmlBr());

		// default validation - do not allow empty
		$this->setAllowEmpty(false);
		$this->setRequired(true);  
	
		$this->setDecorators ( $decorators);
//        $this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
	}
	
	
}