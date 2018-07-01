<?php
//require_once(APPLICATION_PATH . '/models/DbTable/user.php');
//require_once(APPLICATION_PATH . '/models/DbTable/survey_distribute.php');
//
//
//class ZendX_Dojo_Data extends Zend_Dojo_Data  
//{  
//   public function addChildren($id, $children, $reference = true)  
//   {  
//      if (! isset($this->_items[$id])) {  
//         require_once 'Zend/Dojo/Exception.php';  
//         throw new Zend_Dojo_Exception('You must set an item with this identifier prior to adding children');  
//      }  
//  
//      if (isset($this->_items[$id]['children']) && ! is_array($this->_items[$id]['children'])) {  
//         $this->_items[$id]['children'] = array();  
//      }  
//  
//      if ($reference === true) {  
//            foreach ($children as $child) {  
//               if (! isset($this->_items[$id])) {  
//                  require_once 'Zend/Dojo/Exception.php';  
//                  throw new Zend_Dojo_Exception('You must set an item with this identifier prior to reference as child');  
//               }  
//               $this->_items[$id]['children'][] = array('_reference' => $child);  
//            }  
//      } else {  
//         require_once 'Zend/Dojo/Exception.php';  
//         throw new Zend_Dojo_Exception('Method addChildren in hierarchy mode not implemented');  
//      }  
//   }  
//}  
//
///**
// * SurveyController 
// * 
// * @uses      Zend_Controller_Action
// * @package   Paste
// * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
// * @version   $Id: $
// */
//class ExampleController extends Zend_Controller_Action
//{
//    /**
//     * Home page; display site entrance form
//     * 
//     * @return void
//     */
//    public function init()
//    {
//        $this->_redirector = $this->_helper->getHelper('Redirector');
//        
//        //
//        // apply soria css theme, some theme required for proper display of dijits
//        //
//#        $this->view->headLink()->appendStylesheet('/js/dijit/themes/tundra/tundra.css');
//#        $this->view->headLink()->appendStylesheet('/js/dojox/grid/_grid/Grid.css');
////        $this->view->headLink()->appendStylesheet('/js/dojox/grid/_grid/nihiloGrid.css');
//
//    }
//
//    //
//    // executes before dispatch
//    // used here to check authentication
//    //
//    public function preDispatch()
//    {
////         $auth = Zend_Auth::getInstance();
////         if ($auth->hasIdentity()) {
////             // Identity exists; get it
////             $this->identity = $auth->getIdentity();
////             
////             //
////             // build acl based on user permissions
////             //
////             require_once(APPLICATION_PATH.'/../library/My/Classes/class_neb_acl.php');    
////             $test = new Zend_Session_Namespace('neb_acl');
////             $this->neb_acl = unserialize($test->neb_acl);
////     
////         }
////         else {
////             $session = new Zend_Session_Namespace();
////             $session->message = 'You must be logged in to do that';
//// 
////             $this->_redirect('/');
////             return;
////         }
//    }
//
//
//    public function example01Action()
//    {
//        //
//        // get incoming data
//        //        
//        $request = $this->getRequest();
//
//        //
//        // confirm keys exist
//        //
//        if (!$request->getParam('id')) {
//            $this->_redirect('/survey/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $this->view->id = $request->id;
//
//    }
//
//    public function example02Action()
//    {
//        //
//        // get incoming data
//        //        
//        $request = $this->getRequest();
//
//        //
//        // confirm keys exist
//        //
//        if (!$request->getParam('id')) {
//            $this->_redirect('/survey/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $this->view->id = $request->id;
//
//    }
//
//    public function example03Action()
//    {
//        //
//        // get incoming data
//        //        
//        $request = $this->getRequest();
//
//        //
//        // confirm keys exist
//        //
//        if (!$request->getParam('id')) {
//            $this->_redirect('/survey/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $this->view->id = $request->id;
//
//    }
//
//
//    public function example05Action()
//    {
//        //
//        // get incoming data
//        //        
//        $request = $this->getRequest();
//
//        //
//        // confirm keys exist
//        //
//        if (!$request->getParam('id')) {
//            $this->_redirect('/survey/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $this->view->id = $request->id;
//
//    }
//
//    public function example06Action()
//    {
//        //
//        // get incoming data
//        //        
//        $request = $this->getRequest();
//
//        //
//        // confirm keys exist
//        //
//        if (!$request->getParam('id')) {
//            $this->_redirect('/survey/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $this->view->id = $request->id;
//
//    }
//
//    public function example07Action()
//    {
//        //
//        // get incoming data
//        //        
//        $request = $this->getRequest();
//
//        //
//        // confirm keys exist
//        //
//        if (!$request->getParam('id')) {
//            $this->_redirect('/survey/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $this->view->id = $request->id;
//
//    }
//
//
//    public function example08Action()
//    {
//        //
//        // get incoming data
//        //        
//        $request = $this->getRequest();
//
//        //
//        // confirm keys exist
//        //
//        if (!$request->getParam('id')) {
//            $this->_redirect('/survey/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $this->view->id = $request->id;
//
//    }
//
//    public function example09Action()
//    {
//        //
//        // get incoming data
//        //        
//        $request = $this->getRequest();
//
//        //
//        // confirm keys exist
//        //
//        if (!$request->getParam('id')) {
//            $this->_redirect('/survey/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $this->view->id = $request->id;
//
//    }
//
//    public function example10Action()
//    {
//        //
//        // get incoming data
//        //        
//        $request = $this->getRequest();
//
//        //
//        // confirm keys exist
//        //
//        if (!$request->getParam('id')) {
//            $this->_redirect('/survey/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $this->view->id = $request->id;
//
//        //
//        // get html form definition
//        //
//        $this->view->form = $this->getForm('edit', '1', '1');
//        
//    }
//
//    public function getForm($formName = 'view', $page =1, $version = 1)
//    {
//
//        $formFunctionName = $formName . '_page' . $page . '_version' . $version;
//        $formLoader = $this->_helper->formLoader('Form004');
//        $form = $formLoader->$formFunctionName();
//        
//        // restore search form values from session
////         if (is_array($searchParams->searchValues)) {
////             $form->populate($searchParams->searchValues);
////         }
//        return $form;
//    }
//}
