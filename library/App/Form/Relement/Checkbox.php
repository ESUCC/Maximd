<?php

class App_Form_Relement_Checkbox extends Zend_Form_Element_Checkbox
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
            'Label',
            'Description',
	    	
        );	
    	$this->setDecorators($decorators);
    	if(is_string($this->wrapper))
    	{
//		    $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, '".$this->wrapper."');");
		    $this->setAttrib('onchange', $this->JSmodifiedCode);
    	} else {
//		    $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
            $this->setAttrib('onchange', $this->JSmodifiedCode);
		    // be sure to set decorators before adding this one 
//			$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
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
//          array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') ),
            'Label',
            'Description',
                    
        );  
        $this->setDecorators($decorators);
        if(is_string($this->wrapper))
        {
//            $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, '".$this->wrapper."');");
            $this->setAttrib('onchange', $this->JSmodifiedCode);
        } else {
//            $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
            $this->setAttrib('onchange', $this->JSmodifiedCode);
            // be sure to set decorators before adding this one 
//            $this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
        }
        $this->setCheckedValue('t');
        $this->setAttrib('title', $this->getLabel());
        if('' != $this->getLabel()) {
            $this->setAttrib('alt', $this->getLabel());
        } else { 
            $this->setAttrib('alt', $this->getDescription());
        }
    }
    
	
	
}