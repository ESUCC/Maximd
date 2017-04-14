<?php
//require_once(APPLICATION_PATH . '/models/DbTable/iep_school.php');
///**
// * SchoolController 
// * 
// * @uses      Zend_Controller_Action
// * @package   Paste
// * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
// * @version   $Id: $
// */
//class SchoolController extends Zend_Controller_Action
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
//        $this->identity = My_Helper_Auth::check();
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
//        // redirecting to /school/list. makes more sense right?
//        //$this->view->schoolForm = $this->getForm_schoolForm();
//        $this->_redirect('/school/list');
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
//        if (!($request->getParam('id_county') && $request->getParam('id_district') && $request->getParam('id_school'))) {
//            $this->_redirect('/school/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $countyID = $request->id_county;
//        $districtID = $request->id_district;
//        $schoolID = $request->id_school;
//
//        //
//        // set key vars in view
//        //
//        $this->view->countyID = $countyID;
//        $this->view->districtID = $districtID;
//        $this->view->schoolID = $schoolID;
//
//        //
//        // get db data
//        //
////        $data = $this->getSchool($countyID, $districtID, $schoolID);
//		$data = iep_school::getSchool($countyID, $districtID, $schoolID);
//        
//        //
//        // get form - schoolForm and populate
//        //
//        $form = $this->getForm_schoolForm(false, $countyID, $districtID, $schoolID, $data);
//
//        //
//        // add keys to the form because this is view mode and keys are hidden
//        //
//        $form->addElement('hidden', 'id_county', array(
//            'value' => $countyID
//        ));
//        $form->addElement('hidden', 'id_district', array(
//            'value' => $districtID
//        ));
//        $form->addElement('hidden', 'id_school', array(
//            'value' => $schoolID
//        ));
//
//        //
//        // set form into view
//        //
//        $this->view->schoolForm = $form;
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
//        if (!($request->getParam('id_county') && $request->getParam('id_district') && $request->getParam('id_school'))) {
//            $this->_redirect('/school/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $countyID = $request->id_county;
//        $districtID = $request->id_district;
//        $schoolID = $request->id_school;
//
//        //
//        // set key vars in view
//        //
//        $this->view->countyID = $countyID;
//        $this->view->districtID = $districtID;
//        $this->view->schoolID = $schoolID;
//
//        //
//        // get db data
//        //
////        $data = $this->getSchool($countyID, $districtID, $schoolID);
//		$data = iep_school::getSchool($countyID, $districtID, $schoolID);
//        //
//        // get form - schoolForm and populate
//        //
//        $form = $this->getForm_schoolForm(true, $countyID, $districtID, $schoolID, $data);
//
//        //
//        // add keys to the form because this is view mode and keys are hidden
//        //
//        $form->addElement('hidden', 'id_county', array(
//            'value' => $countyID
//        ));
//        $form->addElement('hidden', 'id_district', array(
//            'value' => $districtID
//        ));
//        $form->addElement('hidden', 'id_school', array(
//            'value' => $schoolID
//        ));
//
//        //
//        // set form into view
//        //
//        $this->view->schoolForm = $form;
//        
//    }
//
////    private function getSchool($countyID, $districtID, $schoolID)
////    {
////
////        $db = Zend_Registry::get('db');
////
////        $result = $db->fetchAll("SELECT name_county, name_district, name_school, id_school, s.status as status,
////                                        id_school_mgr, s.id_account_sprv as id_account_sprv, s.address_street1 as address_street1,
////                                        s.address_street2 as address_street2, s.address_city as address_city, s.address_state as address_state,
////                                        s.address_zip as address_zip, s.phone_main as phone_main
////                                 FROM neb_school s inner join neb_district d on s.id_district = d.id_district
////                                                   inner join neb_county c on d.id_county = c.id_county
////                                 WHERE id_school = ? and d.id_district = ? and c.id_county = ?",
////                                array($schoolID, $districtID, $countyID));
////
////        // hey jesse, i don't like throwing out your code like this but
////        // i don't know how to do joins in zend_db
////        /*        $countyID = $db->quote($countyID);
////                  $districtID = $db->quote($districtID);
////                  $schoolID = $db->quote($schoolID);
////                  
////                  $select = $db->select("name_school", "status")
////                  ->from( 'neb_school' )
////                  ->where( "id_county = $countyID and id_district = $districtID and id_school = $schoolID" )
////                  ->order( "name_school", "status" );
////                  
////                  $result = $db->fetchAll($select);*/
////        
////        return $result[0];
////        
////    }
//
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
//
//        //
//        // get search form
//        // note it uses a different part of the config file
//        // than the getSchool function
//        //
//        $form = $this->getForm_searchForm();
//
//        $session = new Zend_Session_Namespace('school');
//        if ($session->searchfield && $session->searchvalue) {
//    		//
//    		// scrub multi options
//    		// because I can't figure out how to put periods in the select value
//    		if('status' == $session->searchfield) {
//    			$sql_searchfield = 'c.'.$session->searchfield;
//    		} else {
//    			$sql_searchfield = $session->searchfield;
//    		}
//        	
//        	
//            // there's a sticky search saved in the session, so do the search and display results
//            $form->searchfield->setValue($session->searchfield);
//            $form->searchvalue->setValue($session->searchvalue);
//			
//            $this->view->results = iep_school::searchSchools($sql_searchfield, $session->searchvalue);
//        }
//
//        //
//        // set form into view
//        //
//        $this->view->schoolForm = $form;
//
//    	    if(count($this->view->results) > 0) {
//	        //
//	        // get page vars for pagenation
//	        //
//		    $page=$this->_getParam('page',1); // this is copied code, I need to learn more about this function
//		    $this->view->page = $page;
//		    
//		    // set up pagenation
//		    // pagenator is new in zf 1.8
//		    //
//		    $paginator = Zend_Paginator::factory($this->view->results);
//		    $paginator->setItemCountPerPage($this->rowsPerPage_list);				// set number of pages in pagenator
//		    $paginator->setCurrentPageNumber($page);			// put current page number into the pagenator
//		    $this->view->paginator=$paginator;					// put the pagenator in the view
//	    }
//    
//    }
//
//    public function clearsearchAction()
//    {
//        $session = new Zend_Session_Namespace('school');
//        unset($session->searchfield);
//        unset($session->searchvalue);
//        $this->_redirect('/school/list');
//        return;
//    }
//
//    /*
//    public function insertSchool ($data = array())
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
////    private function saveschoolForm($countyId, $districtId, $schoolId, $data)
////    {
////        unset($data['submit']);
////        unset($data['id']);
////        unset($data['name_county']);
////        unset($data['name_district']);
////        unset($data['id_school']);
////
////        try
////        {
////            $db = Zend_Registry::get('db');
////            $auth = Zend_Auth::getInstance();        
////            $result = $db->update('neb_school',
////                                  $data,
////                                  array('id_county ='.$db->quote($countyId),
////                                        'id_district ='.$db->quote($districtId),
////                                        'id_school ='.$db->quote($schoolId)));
////            
////            return $result;
////        }
////        catch (Zend_Db_Statement_Exception $e) {
////            // generate error
////            //echo "error: $e";
////            // echoing doesn't work when the calling action redirects before rendering
////            throw $e;
////        }
////        return false;
////    }
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
//        if (!($post['id_county'] && $post['id_district'] && $post['id_school'])) {
//            $this->_redirect('/school/list');
//            return;
//        }
//
//        //
//        // get form
//        //
//        $form = $this->getForm_schoolForm(true, $post['id_county'], $post['id_district'], $post['id_school']);
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
//            $id = $data['id'];
//            
//            //
//            // save the form
//            // // is this function really only going to be used one time?
////            $this->saveschoolForm($post['id_county'],$post['id_district'],$post['id_school'], $data);
//            iep_school::saveSchool($post['id_county'],$post['id_district'],$post['id_school'], $data);
//            // Redirect to 'view' of 'my-controller' in the current
//            // module, using the params param1 => test and param2 => test2
//            $this->_redirector->gotoSimple('edit',
//                                           'school',
//                                           null,
//                                           array('id_county' => $post['id_county'],
//                                                 'id_district' => $post['id_district'],
//                                                 'id_school' => $post['id_school'],
//                                                 )
//                                           );
//            return;
//            //return $this->_redirect($redirectto);
//        }
//        
//        $this->view->schoolForm = $form;
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
//            $this->_redirect('/school/list');
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
//            // put the post parameters in the session and redirect to /school/list
//            $session = new Zend_Session_Namespace('school');
//            $session->searchfield = $post['searchfield'];
//            $session->searchvalue = $post['searchvalue'];
//        }
//            $this->_redirect('/school/list');
//            return;
//    }
//
//    /* FORMS - get forms functions */
//
//    private function getForm_schoolForm($edit = false, $countyID, $districtID, $schoolID, $data = false)
//    {
//        
//        if($edit)
//        {
//            //
//            // get school form
//            //
//            $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/School.ini', 'school');
//            $form = new Zend_Form($config->schoolForm);
//
//            $form->addElement('hidden', 'id_county', array(
//                'value' => $countyID
//            ));
//            $form->addElement('hidden', 'id_district', array(
//                'value' => $districtID
//            ));
//            $form->addElement('hidden', 'id_school', array(
//                'value' => $schoolID
//            ));
//
//        } else {
//
//            //
//            // get school form
//            //
//            $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/School.ini', 'schoolReadOnly');
//            $form = new Zend_Form($config->schoolForm);
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
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/School.ini', 'search');
//        $form = new Zend_Form($config->schoolForm);
//
//        if(false !== $data) $form->populate($data);
//        
//        return $form;
//    }
//
//
//}
