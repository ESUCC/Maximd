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
class Zend_View_Helper_YesNo extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $retText;

    /**
     * Return base URL of application
     * 
     * @return string
     */
    public function YesNo($e)
    {
        $this->retText = "";
		Zend_Debug::dump($e->getValue());
		foreach($e->getMultiOptions() as $k => $o)
		{
			Zend_Debug::dump($k);
			Zend_Debug::dump($o);
//			if(array_search($k, $e->getValue()) !== false) $this->retText .= $o . $delimiter;
		}
		
        return $this->retText;
    }
}
