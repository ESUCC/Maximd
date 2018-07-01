<?php
//
////
//// required tables
////
//require_once(APPLICATION_PATH . '/models/DbTable/esu.php');
//
//
///**
// * EsuController 
// * 
// * @uses      Zend_Controller_Action
// * @package   Paste
// * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
// * @version   $Id: $
// */
//class EsuController extends Zend_Controller_Action
//{
//    public function init()
//    {
//        $this->_redirector = $this->_helper->getHelper('Redirector');
//    }
//    
//    //
//    // executes before dispatch
//    // used here to check authentication
//    //
//    public function preDispatch()
//    {
//    	$this->identity = My_Helper_Auth::check();
//        if($this->identity == false) { $this->_redirect('/'); }
//    }
//
//    /**
//     * Home page; display site entrance form
//     * 
//     * @return void
//     */
//    public function indexAction()
//    {
//        //
//        // get county form
//        // redirecting to /esu/list. makes more sense right?
//        //$this->view->esuForm = $this->getForm_esuForm();
//        $this->_redirect('/esu/list');
//        return;
//    }
//
//    public function viewAction()
//    {
//        //
//        // get incoming data
//        //        
//        $request = $this->getRequest();
//
//        //
//        // confirm keys exist
//        //
////         if (!($request->getParam('id_esu'))) {
////             $this->_redirect('/esu/list');
////             return;
////         }
//
//        //
//        // get keys into vars
//        //
//        $esuID = $request->id;
//
//        //
//        // set key vars in view
//        //
//        $this->view->esuID = $esuID;
//
//        //
//        // get db data
//        //
//        $ESU = new ESU();
//        $data = $ESU->getEsu($esuID);
//
//
//        //
//        // get form - esuForm and populate
//        //
//        $form = $this->getForm_esuForm(false, $esuID, $data);
//
//        //
//        // add keys to the form because this is view mode and keys are hidden
//        //
//        $form->addElement('hidden', 'id_esu', array(
//            'value' => $esuID
//        ));
//
//        //
//        // set form into view
//        //
//        $this->view->esuForm = $form;
//        
//    }
//    
//    public function editAction()
//    {
//        //
//        // get incoming data
//        //        
//        $request = $this->getRequest();
//
//        //
//        // confirm keys exist
//        //
////         if (!($request->getParam('id_county') && $request->getParam('id_district') && $request->getParam('id_esu'))) {
////             $this->_redirect('/esu/list');
////             return;
////         }
//
//        //
//        // get keys into vars
//        //
//        $esuID = $request->id;
//
//        //
//        // set key vars in view
//        //
//        $this->view->esuID = $esuID;
//
//
//        //
//        // get db data
//        //
//        $ESU = new ESU();
//        $data = $ESU->getEsu($esuID);
////        $data = $this->getEsu($countyID, $districtID, $esuID);
//
//        //
//        // get form - esuForm and populate
//        //
//        $form = $this->getForm_esuForm(true, $esuID, $data);
//
//        //
//        // add keys to the form because this is view mode and keys are hidden
//        //
//        $form->addElement('hidden', 'id_esu', array(
//            'value' => $esuID
//        ));
//
//        //
//        // set form into view
//        //
//        $this->view->esuForm = $form;
//        
//    }
//
//
//    public function listAction()
//    {
//#        require_once(APPLICATION_PATH.'/../library/My/Classes/class_neb_acl.php');
//#        $neb_acl = Zend_Registry::get('neb_acl');
//#        print_r($neb_acl);
//        //
//        // authenticate access
//        //
//        
//        
//        //
//        // authenticate view access
//        //
//        
//        
//        //
//        // authenticate edit access
//        //
//        
//
//        //
//        // get search form
//        // note it uses a different part of the config file
//        // than the getEsu function
//        //
//        $form = $this->getForm_searchForm();
//
//        $session = new Zend_Session_Namespace('esu');
//        if ($session->searchfield && $session->searchvalue) {
//
//            // there's a sticky search saved in the session, so do the search and display results
//            $form->searchfield->setValue($session->searchfield);
//            $form->searchvalue->setValue($session->searchvalue);
//
//            try
//            {
//                $db = Zend_Registry::get('db');
//                $result = $db->fetchAll("select * from neb_esu 
//                                         where ".$session->searchfield." ilike ?", '%'.$session->searchvalue.'%' );
//                
//                $this->view->results = $result;
//            }
//            catch (Zend_Db_Statement_Exception $e) {
//                // should log errors
//                // generate error
//                // but as long as we're not
//                // don't swallow the exception
//                throw new Zend_Db_Statement_Exception($e);    
//            }
//        }
//
//        //
//        // set form into view
//        //
//        $this->view->esuForm = $form;
//
//    }
//
//    public function clearsearchAction()
//    {
//        $session = new Zend_Session_Namespace('esu');
//        unset($session->searchfield);
//        unset($session->searchvalue);
//        $this->_redirect('/esu/list');
//        return;
//    }
//
//    /*
//    public function insertEsu ($data = array())
//    {
//    
////         $data = array(
////             'search_phrase'      => $search_phrase,
////             'search_date' => $search_date,
////             'search_user'      => $search_user,
////             'search_seconds' => $searchSeconds,
////             'search_table' => $searchTable,
////             'search_rows' => $searchRows,
////             'query' => $sqlStmt
////         );
////         
////         $db = Zend_Registry::get('db');
////         
////         $db->insert('neb_user', $data);
//        
//        return false;
//        }*/
//
//    public function saveAction()
//    {
//
//        //
//        // get incoming data
//        //
//        $post = $this->getRequest()->getPost();
//
//        //
//        // confirm keys exist
//        //
//        if (!($post['id_esu'])) {
//            $this->_redirect('/esu/list');
//            return;
//        }
//
//        //
//        // get form
//        //
//        $form = $this->getForm_esuForm(true, $post['id_esu']);
//
//        //
//        // validate form
//        //        
//	    if(!$form->isValid($post)) {
//            // if not valid, return to page with no results
//        } else {
//            // if valid, continue to save
//            
//            //
//            // get form data
//            //
//            $data = $form->getValues();
//            
//            //
//            // get database keys from incoming data keys
//            //
//            $id = $data['id_esu'];
//            
//            //
//            // save the form
//            // // is this function really only going to be used one time?
//            $ESU = new ESU();
//            $ESU->saveEsu($id, $data);
//            
//            // Redirect to 'view' of 'my-controller' in the current
//            // module, using the params param1 => test and param2 => test2
//            $this->_redirector->gotoSimple('edit',
//                                           'esu',
//                                           null,
//                                           array('id' => $post['id_esu'],)
//                                           );
//            return;
//            //return $this->_redirect($redirectto);
//        }
//        
//        $this->view->esuForm = $form;
//                
//        //
//        // return to county page and display save result message
//        //
//        
//        return $this->render('index');
//    }
//
//    public function dosearchAction() {
//
//        if (!$this->getRequest()->isPost()) {
//            $this->_redirect('/esu/list');
//            return;
//        }
//        $post = $this->getRequest()->getPost();
//
//        //
//        // get form
//        //
//        $form = $this->getForm_searchForm();
//	    $this->view->searchForm = $form;
//        $values = $form->getValues();
//
//        //
//        // validate form
//        //        
//	    if(!$form->isValid($post)) {
//            // if not valid, return to the search page with no results
//            // scratch that, this should always be valid unless the user hacked something
//	        // return $this->render('index');
//        } else {
//            // put the post parameters in the session and redirect to /esu/list
//            $session = new Zend_Session_Namespace('esu');
//            $session->searchfield = $post['searchfield'];
//            $session->searchvalue = $post['searchvalue'];
//        }
//            $this->_redirect('/esu/list');
//            return;
//    }
//
//    /* FORMS - get forms functions */
//
//    private function getForm_esuForm($edit = false, $esuID, $data = false)
//    {
//        
//        if($edit)
//        {
//            //
//            // get esu form
//            //
//            $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/Esu.ini', 'esu');
//            $form = new Zend_Form($config->esuForm);
//
//            $form->addElement('hidden', 'id_esu', array(
//                'value' => $esuID
//            ));
//
//        } else {
//
//            //
//            // get esu form
//            //
//            $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/Esu.ini', 'esuReadOnly');
//            $form = new Zend_Form($config->esuForm);
//
//        }
//        if(false !== $data) $form->populate($data);
//        
//        return $form;
//    }
//
//    private function getForm_searchForm($data = false)
//    {
//        
//        //
//        // get search form
//        //
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/Esu.ini', 'search');
//        $form = new Zend_Form($config->esuForm);
//
//        if(false !== $data) $form->populate($data);
//        
//        return $form;
//    }
//
//
//}
