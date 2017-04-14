<?php
/**
 * Helper for printing in bold 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <mdanahy@esucc.org> 
 * @version   $Id: $
 */
class Zend_View_Helper_PrintBoldElementContent extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_retString;

    /**
     * wrap data in bold
     * 
     * @return string
     */
    public function printBoldElementContent($viewMode, $element, $label = true)
    {
    	if('print' != $viewMode) {
    		return $element;
    	}
    	if($label) {
    		$this->_retString = $element->getLabel() . ' ';
    	} else {
    	   $this->_retString = $element->getDescription() . ' ';
    	}
//    	$element->removeDecorator('label');
//    	return $this->_retString;
		$this->_retString .= '<B>';
		$this->_retString .= $element->getValue();
		$this->_retString .= '</B>';
		    	
    	return $this->_retString;
    }


}
