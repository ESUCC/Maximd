<?php
///**
// * UserController 
// * 
// * @uses      Zend_Controller_Action
// * @package   Paste
// * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
// * @version   $Id: $
// */
//class UserController extends Zend_Controller_Action
//{
//    public function init()
//    {
//        $this->_redirector = $this->_helper->getHelper('Redirector');
//
//        $this->view->privStatus = array('active'=>'Active', 'inactive'=>'Inactive');
//
//    }
//    
//    protected $_identity;
//
//    //
//    // executes before dispatch
//    // used here to check authentication
//    //
//    public function preDispatch()
//    {
//        $auth = Zend_Auth::getInstance();
//        if ($auth->hasIdentity()) {
//            // Identity exists; get it
//            $this->_identity = $auth->getIdentity();
//        }
//        else {
//            $session = new Zend_Session_Namespace();
//            $session->message = 'You must be logged in to do that';
//
//            $this->_redirect('/');
//            return;
//        }
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
//        // get form - userForm
//        //
//        // what's with the weird blank form? i'm just gonna redirect to /user/list so i can be lazy
//        //$this->view->userForm = $this->getForm_userForm();
//        $this->_redirect('/user/list');
//        return;
//    }
//    
//    
//    public function viewAction()
//    {
//        $request = $this->getRequest();
//        
//        $userID = $request->id;
//                
//        $this->view->id = $userID;
//        
//        $user = $this->getUser($userID);
//
//        //
//        // get form - userForm
//        //
//        $this->view->userForm = $this->getForm_userForm(false, $userID, $user);
//
//        //
//        // get privileges
//        //
//        $this->view->privResults = $this->getPrivileges($userID);
//    }
//
//    
//    public function editAction()
//    {
//        $request = $this->getRequest();
//        
//        $userID = $request->id;
//                
//        $this->view->id = $userID;
//
//        $user = $this->getUser($userID);
//
//        //
//        // get form - userForm
//        //
//        $this->view->userForm = $this->getForm_userForm(true, $userID, $user);
//
//        //
//        // get privileges
//        //
//        $this->view->privResults = $this->getPrivileges($userID);
//    }
//    
//    private function getUser($userID)
//    {
//
//        $db = Zend_Registry::get('db');
//        $select = $db->select('name_first, name_middle, name_last')
//                     ->from( 'neb_user' )
//                     ->where( "id_neb_user = ?", $userID )
//                     ->order( "name_first", "name_last" );
//    
//        $result = $db->fetchAll($select);
//        
//        return $result[0];
//        
//    }
//
//    private function getPrivileges($userID)
//    {
//        /*
//          "get_name_county(id_county) as county_name", 
//          "get_name_district(id_county, id_district) as district_name",
//          "get_name_school(id_county, id_district, id_school) as school_name",
//          "name_school",
//          "id_neb_privilege as id"
//        */                        
//        $db = Zend_Registry::get('db');
//        $select = $db->select()
//                     ->from( array('p' => 'neb_privilege'),
//                             array('id' => 'id_neb_privilege',
//                                   'class',
//                                   'status',
//                                   'name_county' => 'get_name_county(id_county)',
//                                   'name_district' => 'get_name_district(id_county, id_district)',
//                                   'name_school' => 'get_name_school(id_county, id_district, id_school)'
//                           ))
//                     ->where( "id_neb_user = '$userID'" )
//                     ->order( "name_county");
//
//        $result = $db->fetchAll($select);
//        
//        return $result;
//        
//    }
//
//    public function listAction()
//    {
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
//        //
//        // get search form
//        // note it uses a different part of the config file
//        // than the getUser function
//        //
//        $form = $this->getForm_searchForm();
//
//        $userSearch = new Zend_Session_Namespace('user');
//        if ($userSearch->searchfield && $userSearch->searchvalue) {
//
//            // there's a sticky search saved in the session, so do the search and display results
//            $form->searchfield->setValue($userSearch->searchfield);
//            $form->searchvalue->setValue($userSearch->searchvalue);
//
//            try
//            {
//                $db = Zend_Registry::get('db');
//                $select = $db->select('name_first, name_middle, name_last')
//                    ->from( 'neb_user' )
//                    ->where( $userSearch->searchfield . " ilike ?", '%'.$userSearch->searchvalue.'%' );
//                
//                $result = $db->fetchAll($select);   //"select * from neb_user where '" . $post['searchfield'] . "' = '" . $post['searchvalue'] ."';"
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
//        $this->view->searchForm = $form;
//    }
//
//    public function clearsearchAction()
//    {
//        $session = new Zend_Session_Namespace('user');
//        unset($session->searchfield);
//        unset($session->searchvalue);
//        $this->_redirect('/user/list');
//        return;
//    }
//
//    private function insertUser ($data = array())
//    {
//    
//        $data = array(
//            'search_phrase'      => $search_phrase,
//            'search_date' => $search_date,
//            'search_user'      => $search_user,
//            'search_seconds' => $searchSeconds,
//            'search_table' => $searchTable,
//            'search_rows' => $searchRows,
//            'query' => $sqlStmt
//        );
//        
//        $db = Zend_Registry::get('db');
//        
//        $db->insert('neb_user', $data);
//        
//        return;
//    }
//
//    private function saveUserForm($id, $data)
//    {
//        unset($data['submit']);
//        unset($data['id']);
//        
//        try
//        {
//            $db = Zend_Registry::get('db');
//            $auth = Zend_Auth::getInstance();        
//            $where[] = "id_neb_user = '$id'";
//            $result = $db->update('neb_user', $data, $where);
//            
//            return $result;
//        }
//        catch (Zend_Db_Statement_Exception $e) {
//            // generate error
//            echo "error: $e";
//        }
//        return false;
//    }
//
//    public function saveAction()
//    {
//        //
//        // get form
//        //
//        $form = $this->getForm_userForm(true);
//
//        $post = $this->getRequest()->getPost();
//
//        
//        //
//        // validate form
//        //        
//	    if(!$form->isValid($post)) {
//            // if not valid, return to the search page with no results
//	        
//        } else {
//            // if valid, continue to save
//            $data = $form->getValues();
//            $id = $data['id'];
//            unset($data['confirm_email']);
//            $this->saveUserForm($id, $data);
//            
//            // Redirect to 'view' of 'my-controller' in the current
//            // module, using the params param1 => test and param2 => test2
//            $this->_redirector->gotoSimple('edit',
//                                           'user',
//                                           null,
//                                           array('id' => $id
//                                                 )
//                                           );
//            return;
//            //return $this->_redirect($redirectto);
//        }
//        
//        $this->view->userForm = $form;
//                
//        //
//        // return to county page and display save result message
//        //
//        
//        return $this->render('index');
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
//	        return $this->render('index');
//        } else {
//            // save the search parameters in the session and redirect to the list page
//            $userSearch = new Zend_Session_Namespace('user');
//            $userSearch->searchfield = $post['searchfield'];
//            $userSearch->searchvalue = $post['searchvalue'];
//            $this->_redirect('/user/list');
//            return;
//        }
//    }
//
//    public function dosearchAction() {
//
//        if (!$this->getRequest()->isPost()) {
//            $this->_redirect('/user/list');
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
//            // put the post parameters in the session and redirect to /user/list
//            $session = new Zend_Session_Namespace('user');
//            $session->searchfield = $post['searchfield'];
//            $session->searchvalue = $post['searchvalue'];
//        }
//            $this->_redirect('/user/list');
//            return;
//    }
//
//    /* FORMS - get forms functions */
//
//    private function getForm_userForm($edit = false, $id = false, $data = false)
//    {
//        
//        if($edit)
//        {
//            //
//            // get user form
//            //
//            $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/User.ini', 'user');
//            $form = new Zend_Form($config->userForm);
//
//            $form->addElement('hidden', 'id', array(
//                'value' => $id
//            ));
//        } else {
//            //
//            // get user form
//            //
//            $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/User.ini', 'userReadOnly');
//            $form = new Zend_Form($config->userForm);
//
//#            $form->name_first->setDecorators(array())
//#                             ->addDecorator("Simple")
//#                             ->addPrefixPath('My_Form_Decorator',
//#                                        'My/Form/Decorator/',
//#                                        'decorator');
//
//            //$form->addDecorator('ViewHelper', array('helper' => 'FormReadonly'));        
//        }
//        if(false !== $data) $form->populate($data);
//        
//        return $form;
//    }
//
//
//    private function getForm_searchForm($data = false)
//    {
//        
//        //
//        // get user form
//        //
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/User.ini', 'search');
//        $form = new Zend_Form($config->userForm);
//
//        if(false !== $data) $form->populate($data);
//        
//        return $form;
//    }
//
//
//
//}
