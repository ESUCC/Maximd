<?php

class App_Form_Element_TestEditorTab extends App_Form_Element_TestEditor
{
	
    public function init()
	{	
		parent::init();
		$this->setAttrib('height', "50");
		$this->setAttrib('style', 'width:675px;'); // grey no more //background-color:#dddddd;
		
	}
}