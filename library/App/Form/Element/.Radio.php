<?php


class App_Form_Element_Radio extends Zend_Form_Element_Radio
{
	var $wrapper = null;
	public function setWrapper($wrapper) { $this->wrapper = $wrapper;}
	
    public function init()
	{
		$this->setDisableLoadDefaultDecorators(true);
		$decorators = array(
            'ViewHelper',
            array('Label', array('tag' => 'span', 'placement' => 'append', 'class' => 'srsRadioLabel srsLabel')),
            array('Description', array('tag' => 'span', 'class' => 'srsRadioDescription srsDescription')),
            // wraps all above tags in a span
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
    	);
    	
    	if(is_string($this->wrapper))
    	{
			$this->setAttrib('onFocus', "modified('', '', '', '', '', '');");
			$this->setAttrib('onchange', "colorMeById(this.id, '".$this->wrapper."');modified('', '', '', '', '', '');");
	        $this->setSeparator('');
			$this->setDecorators($decorators);
    	} else {
			$this->setAttrib('onFocus', "modified('', '', '', '', '', '');");
			$this->setAttrib('onchange', "colorMeById(this.id);modified('', '', '', '', '', '');");
	        $this->setSeparator('');
			$this->setDecorators($decorators);
			$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
    	}
    	
    	//$this->setAttrib('style', "margin-left:10px;");
    	
		// default validation - do not allow empty
		$this->setAllowEmpty(false);
		$this->setRequired(true);  
	    $this->setAttrib('class', 'radio');	
	}
	public function boldLabel()
    {
        $decorators = array(
            'ViewHelper',
            array('Label', array('tag' => 'span',
                                 'class' => 'boldLabel')),
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
        );

        $this->setAttrib('onchange', "modified('', '', '', '', '', '');" . "colorMeById(this.id);");
        $this->setDecorators($decorators);
        $this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
        $this->setAllowEmpty(false);
        $this->setRequired(true);
    }
	
}
