<?php
//require_once('Zend/Dojo/Exception.php');
//require_once(APPLICATION_PATH . '/models/DbTable/iep_county.php');
//require_once(APPLICATION_PATH . '/models/DbTable/iep_district.php');
///**
// * DistrictController 
// * 
// * @uses      Zend_Controller_Action
// * @package   Paste
// * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
// * @version   $Id: $
// */
//class DistrictController extends Zend_Controller_Action
//{
//
//	private $rowsPerPage_list = 20;
//	
//	public function init()
//    {
//        $this->_redirector = $this->_helper->getHelper('Redirector');
////        $this->view->headLink()->appendStylesheet('/js/dijit/themes/soria/soria.css');
//    }
//    
//    /*
//     * executes before dispatch
//     * used here to check authentication
//     */
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
//        // get district form
//        // ok i'm just redirecting to the /district/list page because this form's not really doing anything
//        //$this->view->districtForm = $this->getForm_districtForm();
//        $this->_redirect('/district/list');
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
//        if (!($request->getParam('id_county') && $request->getParam('id_district'))) {
//            $this->_redirect('/district/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $countyID = $request->id_county;
//        $districtID = $request->id_district;
//
//        //
//        // set key vars in view
//        //
//        $this->view->countyID = $countyID;
//        $this->view->districtID = $districtID;
//
//        //
//        // get db data
//        //
////        $data = $this->getDistrict($countyID, $districtID);
//        $data = iep_district::getDistrict($countyID, $districtID);
//        
//        //
//        // get form - districtForm
//        //
//        $form = $this->getForm_districtForm(false, $countyID, $districtID, $data);
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
//        
//        //
//        // set form into view
//        //
//        $this->view->districtForm = $form;
//
//    }
//    
//    private function getDistrict($countyID, $districtID)
//    {
//
//
//        $db = Zend_Registry::get('db');
//
//        //        $countyID = $db->quote($countyID);
//        //        $districtID = $db->quote($districtID);
//
//        $result = $db->fetchAll("select name_district, d.status as status, name_county, id_district_mgr, id_account_sprv,
//                                        address_street1, address_street2, address_city, address_state, address_zip, phone_main
//                                 from neb_district d inner join neb_county c on d.id_county = c.id_county
//                                 where id_district = ? and d.id_county = ?",
//                                array($districtID, $countyID));
//        /*        $select = $db->select()    
//                     ->from( array('d' => 'neb_district'),
//                             array('name_district',
//                                   'status',
//                                   'countyName' => 'get_name_county(id_county)',
//                                   'districtName' => 'get_name_district(id_county, id_district)',
//                           ))
//                     ->where( "id_county = $countyID and id_district = $districtID" )
//                     ->order( array("countyName", "districtName"));
//
//                     $result = $db->fetchAll($select);*/
//        
//        return $result[0];
//        
//    }
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
//        // than the getDistrict function
//        //
//        $form = $this->getForm_searchForm();
//
//        $session = new Zend_Session_Namespace('district');
//        if ($session->searchfield && $session->searchvalue) {
//
//    		//
//    		// scrub multi options
//    		// because I can't figure out how to put periods in the select value
//    		if('status' == $session->searchfield) {
//    			$sql_searchfield = 'c.'.$session->searchfield;
//    		} else {
//    			$sql_searchfield = $session->searchfield;
//    		}
//        	
//    		// there's a sticky search saved in the session, so do the search and display results
//            $form->searchfield->setValue($session->searchfield);
//            $form->searchvalue->setValue($session->searchvalue);
//
//			$this->view->results = iep_district::searchDistricts($sql_searchfield, $session->searchvalue);
//        }
//
//	    $this->view->districtForm = $form;
//		
//	    if(count($this->view->results) > 0) {
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
//    }
//
//    public function clearsearchAction()
//    {
//        $session = new Zend_Session_Namespace('district');
//        unset($session->searchfield);
//        unset($session->searchvalue);
//        $this->_redirect('/district/list');
//        return;
//    }
//
//    private function insertDistrict ($data = array())
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
//    }
//
//    private function savedistrictForm($countyID, $districtID, $data)
//    {
//        unset($data['submit']);
//        unset($data['id']);
//        
//        try
//        {
//            $db = Zend_Registry::get('db');
//
//            $countyID_q = $db->quote($countyID);
//            $districtID_q = $db->quote($districtID);
//
//            $auth = Zend_Auth::getInstance();        
//            $where[] = "id_county = $countyID_q and id_district = $districtID_q ";
//            $result = $db->update('neb_district', $data, $where);
//            
//            return $result;
//        }
//        catch (Zend_Db_Statement_Exception $e) {
//            // generate error
//            echo "error: $e";
//        }
//
//    }
//    
//    public function editAction()
//    {
//        $request = $this->getRequest();
//
//        if (!($request->getParam('id_county') && $request->getParam('id_district'))) {
//            $this->_redirect('/district/list');
//            return;
//        }
//
//        $countyID = $request->id_county;
//        $districtID = $request->id_district;
//
//        $this->view->countyID = $countyID;
//        $this->view->districtID = $districtID;
//        
//        
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
////        $data = $this->getDistrict($countyID, $districtID);
//        $data = iep_district::getDistrict($countyID, $districtID);
//        
//        //
//        // get form - district form
//        //
//        $this->view->districtForm = $this->getForm_districtForm(true, $countyID, $districtID, $data);
//        $this->view->districtForm->addElement('hidden', 'id_county', array(
//            'value' => $countyID
//        ));
//        $this->view->districtForm->addElement('hidden', 'id_district', array(
//            'value' => $districtID
//        ));
//
//    }
//
//
//    public function updateAction() {
//        if (!$this->getRequest()->getParam('id')) {
//            $this->_redirect('/district');
//            return;
//        }
//
//        $id = $this->getRequest()->getParam('id');
//        if (!preg_match('/\d{6}/',$id)) {
//            $this->_redirect('/district');
//            return;
//        }
//        
//        $id_district = substr($id,2,6);
//        $id_county = substr($id,0,2);
//
//        if (!$this->getRequest()->isPost()) {
//            // echo for debugging, but redirect irl
//            echo "isn't a post!"; die;
//        }
//
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/DistrictEdit.ini', 'DistrictEdit');
//        $form = new Zend_Form($config->districtEditForm);
//
//        // if the form isn't valid, put it in the session and send it back to the edit page
//        if (!$form->isValid($this->getRequest()->getPost())) {
//            $form->setAction("/district/update/id/$id");
//            $session = new Zend_Session_Namespace();
//            $session->form = $form;
//            $this->_redirect("/district/edit/id/$id");
//            return;
//        }
//        // else, save the data
//        else {
//            $data = $this->getRequest()->getPost();
//            unset($data['submit']);
//
//            $db = Zend_Registry::get('db');
//            $n = $db->update('neb_district', $data,
//                             array("id_district = ".$db->quote($id_district),
//                                   "id_county = ".$db->quote($id_county)));
//
//            $this->_redirect("/district/edit/id/$id");
//            return;
//        }
//    }
//
//
//    public function saveAction()
//    {
//        //
//        // get incoming data
//        //
//        $request = $this->getRequest();
//        $post = $request->getPost();
//
//        //
//        // confirm keys exist
//        //
//        if (!($request->getParam('id_county') && $request->getParam('id_district'))) {
//            $this->_redirect('/district/list');
//            return;
//        }
//
//        //
//        // get keys into vars
//        //
//        $countyID = $request->id_county;
//        $districtID = $request->id_district;
//
//        //
//        // get in
//        //
//
//        //
//        // get form
//        //
//        $form = $this->getForm_districtForm(true, $countyID, $districtID);
//
//        //
//        // validate form
//        //        
//	    if(!$form->isValid($post)) {
//            // if not valid, return to the search page with no results
//	        Zend_debug::dump('jere');die();
//        } else {
//        	
//            // if valid, continue to save
//            $data = $form->getValues();
////		      Zend_debug::dump($data);die();
////            $this->savedistrictForm($countyID, $districtID, $data);
//            iep_district::saveDistrict($countyID, $districtID, $data);
//            
//            // Redirect to 'view' of 'my-controller' in the current
//            // module, using the params param1 => test and param2 => test2
//            $this->_redirector->gotoSimple('edit',
//                                           'district',
//                                           null,
//                                           array('id_county' => $countyID,
//                                                 'id_district' => $districtID
//                                                 )
//                                           );
//            return;
//            //return $this->_redirect($redirectto);
//        }
//        
//        $this->view->districtForm = $form;
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
//            $this->_redirect('/district/list');
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
//            // put the post parameters in the session and redirect to /district/list
//            $session = new Zend_Session_Namespace('district');
//            $session->searchfield = $post['searchfield'];
//            $session->searchvalue = $post['searchvalue'];
//        }
//            $this->_redirect('/district/list');
//            return;
//    }
//
//    /* FORMS - get forms functions */
//
//    private function getForm_districtForm($edit = false, $countyID = false, $districtID = false, $data = false)
//    {
//
//        if($edit)
//        {
//            //
//            // get district form
//            //
//            $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/District.ini', 'district');
//            $form = new Zend_Form($config->districtForm);
//
//            $form->addElement('hidden', 'id_county', array(
//                'value' => $countyID
//            ));
//            $form->addElement('hidden', 'id_district', array(
//                'value' => $districtID
//            ));
//        } else {
//            //
//            // get district form
//            //
//            $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/District.ini', 'districtReadOnly');
//            $form = new Zend_Form($config->districtForm);
//
//        }
//        if(false !== $data) $form->populate($data);
//        
//        return $form;
//
//    }
//
//
//    private function getForm_searchForm($data = false)
//    {
//
//        //
//        // get district form
//        //
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/District.ini', 'search');
//        $form = new Zend_Form($config->districtForm);
//
//        if(false !== $data) $form->populate($data);
//        
//        return $form;
//
//    }
//
//
//    
//}
