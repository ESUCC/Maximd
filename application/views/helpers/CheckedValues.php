<?php
/**
 * Helper for retrieving base URL
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   Paste
 * @author    Matthew Weier O'Phinney <matthew@weierophinney.net> 
 * @copyright Copyright (C) 2008 - Present, Matthew Weier O'Phinney
 * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version   $Id: $
 */
class Zend_View_Helper_CheckedValues extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_linkBar;

    /**
     * Return base URL of application
     * 
     * @return string
     */
    public function checkedValues($e, $delimiter = "; ")
    {
        $this->_linkBar = "";
		    
		foreach($e->getMultiOptions() as $k => $o)
		{
			if(array_search($k, $e->getValue()) !== false) $this->_linkBar .= $o . $delimiter;
		}
		
        return $this->_linkBar;
    }
}
