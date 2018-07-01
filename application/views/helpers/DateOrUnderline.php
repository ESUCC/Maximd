<?php

class Zend_View_Helper_DateOrUnderline extends Zend_View_Helper_Abstract {
	
	public function dateOrUnderline($view, $dateField) {
		
		if('print' != $view->mode || '' != $dateField->getValue() )
		{
			return $dateField;
		}
		
		$text = $dateField->getLabel() . '<span >_______________________</span>';
		
		if(1) {
			
		}
		
		return $text;
	}
}