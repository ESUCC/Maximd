<?php
class Zend_View_Helper_IndividualRadioButton extends Zend_View_Helper_FormRadio {
			
	public function IndividualRadioButton($button, $option)
	{
		$buttonc = clone($button);
		$buttonc->setMultiOptions(array($option => $button->getMultiOption($option)));
		return $buttonc;
	}
}

