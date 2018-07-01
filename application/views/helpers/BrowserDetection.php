<?php
/**                            THIS FILE IS BEING PHASED OUT....
 *
 * @author mthomson
 * @version 
 */
require_once 'Zend/View/Interface.php';
/**
 * BrowserDetection helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_BrowserDetection
{
    /**
     * @var Zend_View_Interface 
     */
    public $view;
    /**
     * 
     */
    public function browserDetection ()
    {
    	$isRecent = false;
    	//$browser = get_browser();
    	$browscap = new App_Classes_Browscap(APPLICATION_PATH . '/../temp/');
    	$browscap->localFile = APPLICATION_PATH . '/resources/php_browscap.ini';
    	$browscap->updateMethod	= 'local';
    	
    	$browser = $browscap->getBrowser();
    	//print('<pre>');var_dump($browscap->getBrowser());die();
        // TODO Auto-generated Zend_View_Helper_BrowserDetection::browserDetection() helper 
       // return null;
        
        if($browser->Browser == 'Firefox') {
        	if($browser->MajorVer >= 3 && $browser->MinorVer >= 6) $isRecent = true;
        }
        elseif($browser->Browser == 'Chrome') {
        	if($browser->MajorVer >= 6) $isRecent = true;
        }
    	elseif($browser->Browser == 'IE') {
        	if($browser->MajorVer >= 8) $isRecent = true;
        }
    	elseif($browser->Browser == 'Safari') {
        	if($browser->MajorVer >= 5) $isRecent = true;
        }
        $this->view->isRecent = $isRecent;
    }
    /**
     * Sets the view field 
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
