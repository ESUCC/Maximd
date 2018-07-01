<?php

class My_View_Helper_StyleHideIf extends Zend_View_Helper_FormElement {
    
    public function styleHideIf($condition) {
    	if($condition) {
    		return "height:auto;display:none;";
    	}
    }
}