<?php

require_once 'Zend/View/Helper/FormElement.php';

class My_View_Helper_EditorDiv extends Zend_View_Helper_FormElement {
    
    public function EditorDiv($name, $value = null, $attribs = null, $elementAttribs) {
    	
//    	Zend_Debug::dump($t);
    	
    	$html = '<div id="'.$name.'"';
    	foreach($elementAttribs as $attrib => $value)
    	{
    		$html .= " $attrib=\"$value\" ";
    	}
    	$html .= '>'.$value;
    	$html .= "</div>";
        return $html;
    }
}