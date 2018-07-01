<?php


class App_Form_Element_Checkbox extends Zend_Form_Element_Checkbox
{
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	var $wrapper = null;
	public function setWrapper($wrapper) { $this->wrapper = $wrapper;}
	
    public function init()
	{
	    $decorators = array(
            'ViewHelper',
	        
	        // wraps all above tags in a span
//	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') ),
            array('Label', array('tag' => 'span', 'placement' => 'append', 'class' => 'srsCheckboxLabel srsLabel')),
            array('Description', array('tag' => 'span', 'class' => 'srsCheckboxDescription srsDescription')),
	    	
        );	
    	$this->setDecorators($decorators);
    	
    	$this->addPrefixPath('App_Form_Decorator', 'App/Form/Decorator/', 'decorator');
    	
    	if(is_string($this->wrapper))
    	{
		    $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, '".$this->wrapper."');");
    	} else {
		    $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
		    // be sure to set decorators before adding this one 
			$this->addDecorator(array('colormeDecorator'=>'HtmlTag'), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
    	}
    	$this->setCheckedValue('t');
    	$this->setAttrib('title', $this->getLabel());
    	if('' != $this->getLabel()) {
	    	$this->setAttrib('alt', $this->getLabel());
		} else { 
	    	$this->setAttrib('alt', $this->getDescription());
		}
	}

    public function boldLabelPrint()
    {
        $decorators = array(
            'ViewHelper',
            
            // wraps all above tags in a span
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') ),
            array('Label', array('tag' => 'span', 'placement' => 'append', 'class' => 'printBoldLabel srsCheckboxLabel srsLabel')),
            array('Description', array('tag' => 'span', 'class' => 'srsCheckboxDescription srsDescription')),
            
        );  
        $this->setDecorators($decorators);
        if(is_string($this->wrapper))
        {
            $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, '".$this->wrapper."');");
        } else {
            $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
            // be sure to set decorators before adding this one 
            $this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
        }
        $this->setCheckedValue('t');
        $this->setAttrib('title', $this->getLabel());
        if('' != $this->getLabel()) {
            $this->setAttrib('alt', $this->getLabel());
        } else { 
            $this->setAttrib('alt', $this->getDescription());
        }
    }

    public function boldLabelPrintAndDisplay()
    {
        $decorators = array(
            'ViewHelper',

            // wraps all above tags in a span
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') ),
            array('Label', array('tag' => 'span', 'placement' => 'append', 'class' => 'printBoldLabel srsCheckboxLabel srsLabel displayBoldLabel')),
            array('Description', array('tag' => 'span', 'class' => 'srsCheckboxDescription srsDescription')),

        );
        $this->setDecorators($decorators);
        if(is_string($this->wrapper))
        {
            $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, '".$this->wrapper."');");
        } else {
            $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
            // be sure to set decorators before adding this one 
            $this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
        }
        $this->setCheckedValue('t');
        $this->setAttrib('title', $this->getLabel());
        if('' != $this->getLabel()) {
            $this->setAttrib('alt', $this->getLabel());
        } else {
            $this->setAttrib('alt', $this->getDescription());
        }
    }
    
	public function addTablefix() {
		//$this->removeDecorator(array('colormeDecorator'=>'HtmlTag'), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
		$this->addDecorator(array('colormeDecorator'=>'HtmlTag'), array ('tag' => 'span', 'class' => 'tablefix colorme', 'id'  => $this->getName() . '-colorme') );
		$this->addDecorator(array('colormeDecorator2'=>'HtmlTag'), array ('tag' => 'div', 'class' => 'tablefixContainer colorme', 'id'  => $this->getName() . '-colormeContainer') );
		
		
	}

	function addOnChange($newCode) {
		$this->setAttrib('onchange', $this->getAttrib('onchange') . $newCode);
	}
	
	
}
