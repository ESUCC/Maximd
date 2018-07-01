<?php

class App_Form_Relement_Hidden extends Zend_Form_Element_Hidden
{
	public $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function init()
	{
		$simpleDecorators = array(
	        'ViewHelper'
    	);
    	
		$this->setDecorators ( $simpleDecorators);
	}
}