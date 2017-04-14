<?php
/**
 * Action Helper for getting the module, controller and action
 * 
 * @uses Zend_Controller_Plugin_Abstract
 */
class My_Plugin_ViewSetup extends Zend_Controller_Plugin_Abstract
{
     public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
     {
         $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
         $viewRenderer->init();

         // set up variables that the view may want to know
         $viewRenderer->view->module = $request->getModuleName();
         $viewRenderer->view->controller = $request->getControllerName();
         $viewRenderer->view->action = $request->getActionName();

     }
} 