<?php 
/**
 * 
 * Handles Ajax calls to change form language
 * @author stevebennett
 * @version 1.0
 */
class LanguageController extends Zend_Controller_Action
{
    /**
     * (non-PHPdoc)
     * @see Zend_Controller_Action::init()
     */
    public function init()
    {
        /*
         * Make sure this is a JSON request
         */
        if ($this->getRequest()->isXmlHttpRequest())
       	{
			/*
             * We're only returning JSON responses so 
             * we don't want a layout to render.                 
             */
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender();
      	}
      	else
      	    throw new Exception('Trying to access the Ajax controller directly');
    }
    
    /**
     * 
     * Changes the language 
     */
    public function setLocaleAction()
    {
        $session = new Zend_Session_Namespace('user');
        $session->locale = $this->getRequest()->getParam('locale');
        echo Zend_Json::encode(array('success' => 1));
    }
}