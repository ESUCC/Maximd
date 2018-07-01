<?php


class App_Form_Element_Select extends Zend_Form_Element_Select
{
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function init()
	{
		$decorators = array(
	        'ViewHelper',
	        array('Label', array('tag' => 'span', 'class' => 'srsTextLabel srsLabel')),
			array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
    	);

		$this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
		$this->setDecorators($decorators);
		$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
		$this->setAllowEmpty(false);
		$this->setRequired(true);
	}

    public function boldLabelPrint()
    {
        $decorators = array(
            'ViewHelper',
            array('Label', array('tag' => 'span',
                                 'class' => 'printBoldLabel')),
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
        );

        $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
        $this->setDecorators($decorators);
        $this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
        $this->setAllowEmpty(false);
        $this->setRequired(true);
    }
    
	public function boldLabel()
    {
        $decorators = array(
            'ViewHelper',
            array('Label', array('tag' => 'span',
                                 'class' => 'boldLabel')),
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
        );

        $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
        $this->setDecorators($decorators);
        $this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
        $this->setAllowEmpty(false);
        $this->setRequired(true);
    }
	
	function addOnChange($newCode) {
		$this->setAttrib('onchange', $this->getAttrib('onchange') . $newCode);
	}
	
	function noValidation()
	{
		parent::clearValidators();
		$this->setAllowEmpty(true);
		$this->setRequired(false);  
	}
	
}
