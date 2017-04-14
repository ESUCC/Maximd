<?php

class App_Form_Element_Text extends Zend_Form_Element_Text
{
	public $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function init()
	{
		if($this->getLabel() == '') $this->setLabel($this->getName());
		$simpleDecorators = array(
        	'ViewHelper',
	    	array('Label', array('tag' => 'span', 'class' => 'srsTextLabel srsLabel')),
	    	array('Description', array('tag' => 'span')),
	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
    	);
    	
		$this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
		$this->setAttrib('onfocus', $this->JSmodifiedCode . "colorMeById(this.id);");
		$this->setAttrib('alt', $this->getLabel());
		$this->setAttrib('title', $this->getLabel());
		$this->setDecorators ( $simpleDecorators);
		$this->addDecorator(array('colorme'=>'HtmlTag'), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
        
		// default validation - do not allow empty
		$this->setAllowEmpty(false);
		$this->setRequired(true);  
	}
    public function reportDecorators()
	{
        $simpleDecorators = array(
            'ViewHelper',
            'Errors',
            array('Label', array('tag' => 'span', 'class' => 'srsTextLabel srsLabel')),
            array('Description', array('tag' => 'span')),
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
        );

        $this->setDecorators ( $simpleDecorators);
        $this->addDecorator(array('colorme'=>'HtmlTag'), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
	}
    public function normalLabel()
	{
		$simpleDecorators = array(
        	'ViewHelper',
            array('Label', array('tag' => 'span', 'class' => 'srsTextLabel srsLabel boldLabel')),
	    	array('Description', array('tag' => 'span')),
	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
    	);

		$this->setDecorators ( $simpleDecorators);
		$this->addDecorator(array('colorme'=>'HtmlTag'), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
	}

    function boldLabelPrint()
    {
        if($this->getLabel() == '') $this->setLabel($this->getName());
        $simpleDecorators = array(
            'ViewHelper',
            array('Label', array('tag' => 'span', 'class' => 'srsTextLabel srsLabel printBoldLabel')),
            array('Description', array('tag' => 'span')),
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
        );

        $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
        $this->setAttrib('alt', $this->getLabel());
        $this->setAttrib('title', $this->getLabel());
        $this->setDecorators ( $simpleDecorators);
        $this->addDecorator(array('colorme'=>'HtmlTag'), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );

        // default validation - do not allow empty
        $this->setAllowEmpty(false);
        $this->setRequired(true);
    }

    function noBoldLabelPrintIndent()
    {
        if($this->getLabel() == '') $this->setLabel($this->getName());
        $simpleDecorators = array(
            'ViewHelper',
            array('Label', array('tag' => 'span', 'class' => 'srsTextLabel srsLabelNoBold')),
            array('Description', array('tag' => 'span')),
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
        );

        $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
        $this->setAttrib('alt', $this->getLabel());
        $this->setAttrib('title', $this->getLabel());
        $this->setDecorators ( $simpleDecorators);
        $this->addDecorator(array('colorme'=>'HtmlTag'), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
    }
	
    function boldLabel() {
    	if($this->getLabel() == '') $this->setLabel($this->getName());
        $simpleDecorators = array(
            'ViewHelper',
            array('Label', array('tag' => 'span', 'class' => 'srsTextLabel srsLabel boldLabel')),
            array('Description', array('tag' => 'span')),
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
        );

        $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
        $this->setAttrib('alt', $this->getLabel());
        $this->setAttrib('title', $this->getLabel());
        $this->setDecorators ( $simpleDecorators);
        $this->addDecorator(array('colorme'=>'HtmlTag'), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );

        // default validation - do not allow empty
        $this->setAllowEmpty(false);
        $this->setRequired(true);
    }
    
	function noValidation()
	{
		parent::clearValidators();
		$this->setAllowEmpty(true);
		$this->setRequired(false);  
	}
	
	function setWidth($width) {
		$this->setAttrib('style', 'width:' . $width . 'px;');
	}

    function addDojoNumberValidator()
    {
        $this->setAttrib('dojoType', 'dijit.form.ValidationTextBox');
        $this->setAttrib('regExp', '[0-9]+');
        $this->setAttrib('required', 'true');
        $this->setAttrib('invalidMessage', 'You must enter a number only');
        $this->setAttrib('style', 'width:60px;height:15px;font-size:13px;');
    }
}
