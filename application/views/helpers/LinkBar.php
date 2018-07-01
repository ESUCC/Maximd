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
class Zend_View_Helper_LinkBar extends Zend_View_Helper_Abstract
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
    public function linkBar()
    {
        if (null === $this->_linkBar) {
            $request = Zend_Controller_Front::getInstance()->getRequest();
            $this->_linkBar = $request->getBaseUrl();
        }

        return $this->_linkBar;
    }
}
