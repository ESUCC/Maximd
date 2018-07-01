<?php

class App_Form_Relement_Radio extends Zend_Form_Element_Radio
{
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	var $wrapper = null;
	public function setWrapper($wrapper) { $this->wrapper = $wrapper;}
	
    public function init()
	{
		$decorators = array(
            'ViewHelper',
            array('Label', array ('class' => 'sc_bulletInputLeft')),
            'Description',
            
            // wraps all above tags in a span
//            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
    	);
    	
    	if(is_string($this->wrapper))
    	{
			$this->setAttrib('onFocus', $this->JSmodifiedCode);
//			$this->setAttrib('onchange', "colorMeById(this.id, '".$this->wrapper."');");
	        $this->setSeparator('');
			$this->setDecorators($decorators);
    	} else {
			$this->setAttrib('onFocus', $this->JSmodifiedCode);
//			$this->setAttrib('onchange', "colorMeById(this.id);");
	        $this->setSeparator('');
			$this->setDecorators($decorators);
//			$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
    	}

		// default validation - do not allow empty
		$this->setAllowEmpty(false);
		$this->setRequired(true);  
		
	}
}
