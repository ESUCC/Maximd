<?php
/**
 * Helper for printing in bold 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <mdanahy@esucc.org> 
 * @version   $Id: $
 */
class Zend_View_Helper_PrintBold extends Zend_View_Helper_Abstract
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
    public function printBold($viewMode, $dataToWrap)
    {
    	if('print' != $viewMode) {
    		return $dataToWrap;
    	}
    	$this->_retString = "";
//    	return $this->_retString;
		$this->_retString .= '<B>';
		$this->_retString .= $dataToWrap;
		$this->_retString .= '</B>';
		    	
    	return $this->_retString;
    }


}
