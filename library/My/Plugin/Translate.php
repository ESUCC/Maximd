<?php
/**
 * 
 * Handles setting up of translation for site.
 * @author stevebennett
 * @version 1.0
 * 
 */
class My_Plugin_Translate extends Zend_Controller_Plugin_Abstract
{
    protected $_translate;
    
    /**
     * (non-PHPdoc)
     * @see Zend_Controller_Plugin_Abstract::preDispatch()
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        /*
	     * Give the cache object to zend translate so 
	     * we can cache the language files.
	     */
        Zend_Translate::setCache(Zend_Registry::get('translateCache'));
        $this->_translate = new Zend_Translate(
                array(
                        'adapter' => 'tmx',
                        'content' => APPLICATION_PATH . '/translation/global.tmx',
                        'scan' => Zend_Translate::LOCALE_FILENAME,
                        'disableNotices' => true
                )
        );
        
        $session = new Zend_Session_Namespace('user');
        if (empty($session->locale))
            $session->locale = 'en';
            
        Zend_Registry::set('locale', $session->locale);
        
        if (!$this->_translate->isAvailable(Zend_Registry::get('locale')))
            $this->_translate->setLocale('en');
        else
            $this->_translate->setLocale(Zend_Registry::get('locale'));
       
	$pageNumber = $request->getParam('page');
 
        if ('print' == $request->getActionName()) 
            for($i=0;$i<9;$i++)
                $this->addTmxToTranslation(
                        APPLICATION_PATH . '/translation/'
                        . $request->getControllerName() . '/page'
                        . $i . '.tmx'
                );
	elseif(!isset($pageNumber))
            $this->addTmxToTranslation(
                            APPLICATION_PATH . '/translation/'
                            . $request->getControllerName() . '/page1.tmx'
            );
        else 
            $this->addTmxToTranslation(
                    APPLICATION_PATH . '/translation/'
                     . $request->getControllerName() . '/page'
                     . $pageNumber . '.tmx'
            );
    
        Zend_Registry::set('Zend_Translate', $this->_translate);
    }
    
    protected function addTmxToTranslation($tmxFile) {
        if (file_exists($tmxFile)) {
            $actionTranslation = new Zend_Translate(
                    array(
                            'adapter' => 'tmx',
                            'content' => $tmxFile,
                            'scan' => Zend_Translate::LOCALE_FILENAME,
                            'disableNotices' => true
                    )
            );
        
            $this->_translate->addTranslation(
                    array('content' => $actionTranslation)
            );
        }
    }
}
