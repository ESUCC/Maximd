<?php
/**
 * Helper for displaying a printed header 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <mdanahy@esucc.org> 
 * @version   $Id: $
 */
class Zend_View_Helper_PrintHeader extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_retString;

    /**
     * build header table string
     * 
     * @return string
     */
    public function printHeader($img, $title)
    {
	    $this->_retString = '<table class="formTitle"><tr>';
	    switch ($title) {
	        case 'Notice of Meeting (IFSP)':
	        case 'Notice of Initial Evaluation and Child Assessment':
	        case 'Individual Family Service Plan':
	        case 'Annual Transition Notice':
	            $ednImg = 'http://iepweb02.unl.edu/images/EDN.jpg';
	            $this->_retString .= '<td><img src="' . $ednImg . '" width="125" height="93" /></td>';
	            break;
	    }
	    $this->_retString .= '<td>';
	    $this->_retString .= '<img src="' . $img . '" ' . Zend_View_Helper_PrintHeader::getWidthHeightLimitTo($img) . ' />';
	    $this->_retString .= '</td>';
	    $this->_retString .= '<td>';
	    $this->_retString .= '<div class="formTitle" style="text-align:center;">'.str_replace('(IFSP)','',$title).'</div>';
	    $this->_retString .= '</td></tr>';
	    $this->_retString .= '</table>';
		
    	return $this->_retString;
    }
    
	static public function getWidthHeightLimitTo($imgPath, $limitToPixils=100)
	{
	    $wh = '';
	    if (@fopen($imgPath, 'r'))
	    {
	        $imgsize = getimagesize($imgPath);
	        if ($imgsize[0] > $imgsize[1])
	           $wh = "width=\"$limitToPixils\"";
	        else
	           $wh = "height=\"$limitToPixils\"";
		}
		return $wh;
	}
    

}
