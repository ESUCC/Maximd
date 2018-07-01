<?php
//
//    require_once('../application/forms/DojoForm.php');
//    require_once('../application/forms/DojotwoForm.php');
//
//
//class DojoController extends Zend_Controller_Action {
//
//    protected $identity;
//
//
//    public function init()
//    {
//        $this->_redirector = $this->_helper->getHelper('Redirector');
//        
//        //
//        // apply soria css theme, some theme required for proper display of dijits
//        //
//        $this->view->headLink()->appendStylesheet('/js/dijit/themes/soria/soria.css');
//
//        Zend_Dojo::enableView($this->view);
//    }
//
//    public function preDispatch()
//    {
//    
//    }
//
//    function testAction()
//    {
//#        $formLoader = $this->_helper->formLoader('DojoForm');
//#        $form = $formLoader->cal();
//        
//        $form = new DojoForm();
//        $this->view->form=$form;
//    }
//
//    function test2Action()
//    {
//#        $formLoader = $this->_helper->formLoader('DojoForm');
//#        $form = $formLoader->cal();
//        
//        $form = new DojotwoForm();
//        $this->view->form=$form;
//        
//        return $this->render('test');
//    }
//
//}