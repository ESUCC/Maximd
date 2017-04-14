<?php


class App_Form_Element_MultiCheckbox extends Zend_Form_Element_MultiCheckbox
{
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function init()
	{
	    $decorators = array(
	        array('Label', array('tag' => 'span')),
	    	'ViewHelper',
	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
	    );	
		$this->setDecorators($decorators);
	    
	    $this->setAttrib('onFocus', $this->JSmodifiedCode);
		$this->setAttrib('onchange',"colorMeById(this.id);");
	    $this->setAttrib('alt', $this->getLabel());
		$this->setAttrib('title', $this->getLabel());

		// default validation - do not allow empty
		$this->setAllowEmpty(false);
		$this->setRequired(true);  
		
//		$this->setCheckedValue('t');
		$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
	}
	
	
}


