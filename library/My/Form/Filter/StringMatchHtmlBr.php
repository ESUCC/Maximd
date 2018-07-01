<?php

class My_Form_Filter_StringMatchHtmlBr implements Zend_Filter_Interface
{
    public function filter($value)
    {
        // perform some transformation upon $value to arrive on $valueFiltered
		if('<br />' == $value) return null;
		if('<br _moz_editor_bogus_node="TRUE" />' == $value) return null;
		
		return $value;
    }
}