<?php

class App_Form_Element_NumberTextBox extends Zend_Dojo_Form_Element_NumberTextBox{
	
	public function init() {
		if ($this->getLabel () == '')
			$this->setLabel ( $this->getName () );
		
		$decorators = array (
		      'Label', 
		      'DijitElement', 
//		      'Errors', 
//		      array (array ('data' => 'HtmlTag' ), array ('tag' => 'li' ) )
		);
		
        $this->setAttrib ( 'alt', $this->getLabel () );
        $this->setAttrib ( 'title', $this->getLabel () );
		$this->setDecorators ( $decorators );
		
//		// default validation - do not allow empty
//		$this->setAllowEmpty ( false );
//		$this->setRequired ( true );
	}
	
	function noValidation() {
		parent::clearValidators ();
		$this->setAllowEmpty ( true );
		$this->setRequired ( false );
	}
    function addOnchange($jsCode) {
        
        $this->setAttrib('onchange', $this->getAttrib('onchange') . $jsCode);
    }
    function disable() {
        $this->setAttrib('disabled', 'disabled');
        $this->setIgnore( true );
    }
    
}