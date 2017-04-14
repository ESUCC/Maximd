<?php

class App_Form_Element_Password extends Zend_Form_Element_Password {
	public $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function init() {
		if ($this->getLabel () == '')
			$this->setLabel ( $this->getName () );
		
		$decorators = array (
		      'Label', 
		      'ViewHelper', 
//		      'Errors', 
//		      array (array ('data' => 'HtmlTag' ), array ('tag' => 'li' ) )
		);
		
		$this->setAttrib ( 'alt', $this->getLabel () );
		$this->setAttrib ( 'title', $this->getLabel () );
		$this->setDecorators ( $decorators );
		
		// default validation - do not allow empty
		$this->setAllowEmpty ( false );
		$this->setRequired ( true );
	}
	
	function noValidation() {
		parent::clearValidators ();
		$this->setAllowEmpty ( true );
		$this->setRequired ( false );
	}
    function addOnchange($jsCode) {
        
        $this->setAttrib('onchange', $this->getAttrib('onchange') . $jsCode);
    }
}