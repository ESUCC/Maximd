<?php
//require_once('Zend/Dojo/Exception.php');
//
///**
// * AjaxController 
// * 
// * @uses      Zend_Controller_Action
// * @package   Paste
// * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
// * @version   $Id: $
// */
//class AjaxController extends My_Form_AbstractFormController
//{
//    /**
//     * Home page; display site entrance form
//     * 
//     * @return void
//     */
//    function dataAction()
//    {
//        $this->_helper->layout->disableLayout(true);
//
//        $this->view->data = "{
//        identifier: 'part_num',
//        label: 'part_num',
//        items: [
//            { part_num: '4001', min_temp: -946, max_temp: 931, max_pres: 647, type: 1, thick: 0.25, inner: 0.9375, outer: 13.4375 },
//            { part_num: '4002', min_temp: -601, max_temp: 1894, max_pres: 208, type: 1, thick: 0.03125, inner: 4.0, outer: 13.75 },
//            { part_num: '4003', min_temp: 456, max_temp: 791, max_pres: 132, type: 1, thick: 0.125, inner: 2.3125, outer: 6.1875 },
//            { part_num: '4004', min_temp: -259, max_temp: 2433, max_pres: 840, type: 0, thick: 0.0625, inner: 15.75, outer: 15.875 },
//            { part_num: '4005', min_temp: -982, max_temp: 3543, max_pres: 230, type: 0, thick: 0.0625, inner: 17.0625, outer: 19.4375 },
//            { part_num: '4006', min_temp: 652, max_temp: 3285, max_pres: 195, type: 1, thick: 0.25, inner: 13.0, outer: 21.25 },
//            { part_num: '4007', min_temp: 326, max_temp: 1187, max_pres: 110, type: 1, thick: 0.0625, inner: 5.125, outer: 23.125 },
//            { part_num: '4008', min_temp: -960, max_temp: -271, max_pres: 70, type: 1, thick: 0.25, inner: 11.75, outer: 12.5 },
//            { part_num: '4009', min_temp: 904, max_temp: 3529, max_pres: 188, type: 0, thick: 0.0625, inner: 8.5, outer: 9.8125 },
//            { part_num: '4010', min_temp: -149, max_temp: 406, max_pres: 412, type: 0, thick: 0.0625, inner: 9.4375, outer: 13.9375 },
//            { part_num: '4011', min_temp: 972, max_temp: 2686, max_pres: 492, type: 0, thick: 0.25, inner: 10.4375, outer: 14.375 },
//            { part_num: '4012', min_temp: -321, max_temp: 346, max_pres: 724, type: 0, thick: 0.03125, inner: 5.0, outer: 16.1875 },
//            { part_num: '4499', min_temp: 398, max_temp: 3690, max_pres: 708, type: 0, thick: 0.03125, inner: 8.875, outer: 9.75 }
//        ]}
//        ";
//
//
//    }
//
//    function jsongetiepteammemberAction()
//    {
//        $this->_helper->layout->disableLayout(true);
//
//        // make sure incoming data is a post
//        /*
//         if(!$this->getRequest()->isPost()) {
// 	        $this->_redirect('/error1');
//         }
//		*/
//        
//        $request = $this->getRequest();//->getPost();
//        
//        $id = $request->id;
//        if('' == $id) {
//	        $this->_redirect('/error1');
//        }
//        
//		//$this->view->data = $this->dojo_buildTeamMembers($id);
//        $teamMemberRows = $this->getIepTeamMembers($id);
//        
//        $dateFieldList = array('meeting_date');
//        
//        $objArr = array();
//        foreach($teamMemberRows as $q)
//        {
//	        foreach($dateFieldList as $fieldName)
//	        {
//	        	if(null != $q[$fieldName])
//	        	{
//		        	$q[$fieldName] = array(
//					        	'_type'=>'Date', 
//					        	"_value"=>date('Y-m-d', strtotime($q[$fieldName]))."T00:00:00-00:00"
//					);
//	        	} else {
//		        	$q[$fieldName] = null;
//	        	}
//	        }
//            $objArr[] =	$q;
//        }
//        $this->view->data = new Zend_Dojo_Data('id_iep_team_member', $objArr, 'id');
//
//        return $this->render('data');
//    }
//
//    function jsongetteamotherAction()
//    {
//        $this->_helper->layout->disableLayout(true);
//        $request = $this->getRequest();//->getPost();
//        $id = $request->id;
//        if('' == $id) {
//	        $this->_redirect('/error1');
//        }
//		//$this->view->data = $this->dojo_buildTeamOther($id);
//
//        $teamotherrows = $this->getIepTeamOther($id);
//        
//        $objArr = array();
//        foreach($teamotherrows as $q)
//        {
//            
//            $objArr[] = $q;
//        }
//        $this->view->data = new Zend_Dojo_Data('id_iep_team_other', $objArr, 'id');
//        return $this->render('data');
//    }
//
//    function getformiepAction()
//    {
//     	$this->_helper->layout->disableLayout(true);
//        $request = $this->getRequest();//->getPost();
//        $id = $request->id;
//        if('' == $id) {
//        	$this->_redirect('/error1');
//        }
//        $this->view->data = $this->dojo_getIep($id);
//        return $this->render('data');
//	}
//        
//	function dojo_getIep($id_form_004)
//    {
//		$questions = $this->getIep($id_form_004);
//            
//        $objArr = array();
//        foreach($questions as $q) {         
//        	$objArr[] = $q;
//        }
//        $dojoData = new Zend_Dojo_Data('id_form_004', $objArr, 'id');
//    
//        return $dojoData;
//    }
//        
//	function getIep($id)
//	{
//            $db = Zend_Registry::get('db');
//    
//            $id = $db->quote($id);
//    
//            $select = $db->select()
//                         ->from( array('t' => 'iep_form_004'),
//                                 array( 'date_doc_signed_parent',
//                                        'doc_signed_parent',
//                                        'necessary_action',
//                                        'received_copy',
//                                        'no_sig_explanation',
//                                        'id_form_004'
//                                        )
//                               )
//                         ->where( "id_form_004 = $id" );
//            //echo $select;
//            $result = $db->fetchAll($select);
//            
//            return $result;
//            
//	}       
//
//    function buildValidationArray($form, $formMessages, $prefix = false, $suffix = false)
//    {
//        $valArr = array();
//        foreach($formMessages as $keyName => $msgArr)
//        {
//        	if($form->getSubform($keyName)) 
//        	{	
//        		$i=1;
//        		foreach($this->buildValidationArray($form->getSubform($keyName), $msgArr, '', ' (Row '.$i++.')') as $v)
//        		{
//        			$valArr[] = $v;
//        		}
//                
//        	} else {
//            	foreach($msgArr as $msgType => $msg)
//            	{
//            		$label = ($form->getElement($keyName)) ? $form->getElement($keyName)->getLabel() : '';
//            		
//            		if(false !== $prefix) $label = $prefix . $label;
//            		if(false !== $suffix) $label = $label . $suffix;
//            		
//            		$valArr[] = array(
//                					'field' => $keyName, 
//                					'label' => $label, 
//                					'message' => $msg
//                	);
//            	}
//        	}
//        	
////        	print_r($keyName);
//        }
//        //Zend_debug::dump($valArr);
//        return $valArr;
//    }
//
//    function getparentsAction()
//    {
//        $this->_helper->layout->disableLayout(true);
//        $request = $this->getRequest();//->getPost();        
//        $id = $request->id;
//        if('' == $id) {
//	        $this->_redirect('/error1');
//        }
//        $this->view->data = $this->dojo_buildTeamParents($id);
//        return $this->render('data');
//    }
//
//    function dojo_buildTeamParents($id_form_004)
//    {
//
//        $questions = $this->getIepTeamParents($id_form_004);
//        
//        $objArr = array();
//        foreach($questions as $q)
//        {
//            
//            $objArr[] = $q;
//        }
//        $dojoData = new Zend_Dojo_Data('id_iep_team_parent', $objArr, 'id');
//
//        return $dojoData;
//        
//    }
//
//    function getIepTeamParents($id)
//    {
//
//        $db = Zend_Registry::get('db');
//
//        $id = $db->quote($id);
//
//        $select = $db->select()
//                     ->from( array('t' => 'iep_team_parent'),
//                             array( 'id_iep_team_parent',
//                                    'absent',
//                                    'positin_desc',
//                                    'absent_reason',
//                                    'sortnum', 
//                                    'participant_name'
//                                    )
//                           )
//                     ->where( "id_form_004 = $id" )
//                     ->order( "sortnum asc" );
//
//        $result = $db->fetchAll($select);
//        
//        return $result;
//    }
//
//    function getIepTeamMembers($id)
//    {
//
//        $db = Zend_Registry::get('db');
//
//        $id = $db->quote($id);
//        
//        $meetingDate = 'date_part(\'month\',meeting_date) || \'/\' || date_part(\'day\',meeting_date) || \'/\' || date_part(\'year\',meeting_date)';
//
//        $select = $db->select()
//                     ->from( array('t' => 'iep_team_member'),
//                             array( 'id_iep_team_member',
//                                    'absent',
//                                    'positin_desc',
//                                    'absent_reason',
//                                    'sortnum', 
//                                    'participant_name',
//                                    'meeting_date'  // => $meetingDate
//                                    )
//                           )
//                     ->where( "id_form_004 = $id" )
//                     ->order( "sortnum asc" );
//
//        $result = $db->fetchAll($select);
//        
//        return $result;  
//    }
//    
//    function getIepTeamOther($id)
//    {
//
//        $db = Zend_Registry::get('db');
//
//        $id = $db->quote($id);
//
//        $select = $db->select()
//                     ->from( array('t' => 'iep_team_other'),
//                             array( '*'
//                                    )
//                           )
//                     ->where( "id_form_004 = $id" )
//                     ->order( "sortnum asc" );
//
//        $result = $db->fetchAll($select);
//        
//        return $result; 
//    }
//    
//    function jsonupdateiepteammemberAction() {
//
//    	require_once 'Zend/Dojo/Exception.php';
//
//        $this->_helper->layout->disableLayout(true);
//        $request = $this->getRequest();
//        $post = $this->getRequest()->getPost();        
//
//        $decodedData = Zend_Json::decode(implode('', $post));
//
//        try
//        {
//            $tmObj = new Model_Form004TeamMember();
//            $i =0;
//            $objArr = array();
//            if(0 < count($decodedData['modifiedItems'])) {
//                foreach($decodedData['modifiedItems'] as $row)
//                {
//                    $tmObj->saveForm($row['id_iep_team_member'], $row);
//                }
//            }        
//            $this->view->data = new Zend_Dojo_Data('id_iep_team_member', $objArr, 'id'); //"success";
//            return $this->render('data');
//        }
//        catch (Zend_Db_Statement_Exception $e) {
//            throw new Zend_Dojo_Exception($e);
//        }
//        
//    }
//    
//    function jsonupdateteamotherAction()
//    {
//        require_once 'Zend/Dojo/Exception.php';
//		
//        // disable the layout
//        $this->_helper->layout->disableLayout(true);
//		
//        // get the post
//        $request = $this->getRequest();
//        $post = $this->getRequest()->getPost();        
//		
//        // decode the post
//        $decodedData = Zend_Json::decode(implode('', $post));
//        
//        try
//        {
//            $tmObj = new Model_Form004TeamOther();
//            $objArr = array();
//            
//            $i =0;
//            
//            // update items
//            if(0 < count($decodedData['modifiedItems'])) {
//                foreach($decodedData['modifiedItems'] as $row)
//                {
//                    $tmObj->saveForm($row['id_iep_team_other'], $row);
//                }
//            }            
//
//            // add new items
//            if(0 < count($decodedData['newItems'])) {
//                foreach($decodedData['newItems'] as $key => $row)
//                {
//                	if('tempkey' == substr($key, 0, 7)) unset($row['id_iep_team_other']);
//                	  
//                	if(!isset($row['sortnum'])) {
//                		$newId = $tmObj->formHelper->insertForm($row['id_form_004'], '', $row);	
//                	} else {
//                		$newId = $tmObj->formHelper->insertForm($row['id_form_004'], $row['sortnum'], $row);
//                	}
//                	$objArr[$i] = $row;
//                	$objArr[$i]['oldKey'] = $key;
//                	$objArr[$i]['id_iep_team_other'] = $newId;
//                	$i++;
//                }
//            }
//
//            // delete items
//            if(0 < count($decodedData['deletedItems'])) {
//                foreach($decodedData['deletedItems'] as $row)
//                {
//                	if('tempkey' == substr($row['id_iep_team_other'], 0, 7)) continue; // do not process new rows
//                    $tmObj->deleteForm($row['id_iep_team_other']);
//                }
//            }
//
//            $this->view->data = new Zend_Dojo_Data('id_iep_team_other', $objArr, 'id');
//            
//			//$decodedData['ajaxResult'] = 'success';
//			//$this->view->data = $decodedData;	//"success";
//            return $this->render('data');
//        }
//        catch (Zend_Db_Statement_Exception $e) {
//            throw new Zend_Dojo_Exception($e);
//        }
//        
//    }
//
//    function updateparentAction()
//    {
//        require_once 'Zend/Dojo/Exception.php';
//        require_once('DbTable/iep_form_004_team_parent.php');
//
//
//        $this->_helper->layout->disableLayout(true);
//
//        $request = $this->getRequest();
//        $post = $this->getRequest()->getPost();        
//
//        $decodedData = Zend_Json::decode(implode('', $post));
//
//        try
//        {
//            $tmObj = new iep_form_004_team_parent();
//            $i =0;
//            if(0 < count($decodedData['modifiedItems'])) {
//                foreach($decodedData['modifiedItems'] as $row)
//                {
//                    $tmObj->saveForm($row['id_iep_team_parent'], $row);
//                }
//            }        
//            $this->view->data = "success";
//            return $this->render('data');
//        }
//        catch (Zend_Db_Statement_Exception $e) {
//            throw new Zend_Dojo_Exception($e);
//        }
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
//
//
//    // =======================================================================
//    // DATAGRID FUNCTIONS - IEP TEAM DISTRICT
//    // =======================================================================
//
//        function jsongetteamdistrictAction()
//        {
//            $this->_helper->layout->disableLayout(true);
//            $request = $this->getRequest();//->getPost();
//            $id = $request->id;
//            if('' == $id) {
//                $this->_redirect('/error1');
//            }
//
////            $records = $this->getIepTeamDistrict($id);
//            //require_once('DbTable/iep_form_004.php');
//
//            try
//            {
//                $tmObj = new DbTable_iepForm004();
//                $records = $tmObj->getChildRecords('iep_team_district', 'id_form_004', $id, 'sortnum');
//                
//            }
//            catch (Zend_Db_Statement_Exception $e) {
//                throw new Zend_Dojo_Exception($e);
//            }
//            
//            
//            $objArr = array();
//            foreach($records as $q)
//            {
//                $objArr[] = $q;
//            }
//            $this->view->data = new Zend_Dojo_Data('id_iep_team_district', $objArr, 'id');;
//            return $this->render('data');
//        }
//    
//        function jsonupdateteamdistrictAction()
//        {
//            require_once 'Zend/Dojo/Exception.php';
//            //require_once('DbTable/iep_form_004_team_district.php');
//    
//    
//            $this->_helper->layout->disableLayout(true);
//    
//            $request = $this->getRequest();
//            $post = $this->getRequest()->getPost();        
//    
//            $decodedData = Zend_Json::decode(implode('', $post));
//
//            try
//            {
//                $tmObj = new DbTable_iepForm004TeamDistrict();
//            	$objArr = array();
//                $i =0;
//                if(0 < count($decodedData['modifiedItems'])) {
//                    foreach($decodedData['modifiedItems'] as $row)
//                    {
//                        $tmObj->saveForm($row['id_iep_team_district'], $row);
//                    }
//                }        
//
//	            if(0 < count($decodedData['newItems'])) {
//	                foreach($decodedData['newItems'] as $key => $row)
//	                {
//	                	if('tempkey' == substr($key, 0, 7)) unset($row['id_iep_team_district']);
//	                	  
//	                	if(!isset($row['sortnum'])) {
//	                		$newId = $tmObj->formHelper->insertForm($row['id_form_004'], '', $row);	
//	                	} else {
//	                		$newId = $tmObj->formHelper->insertForm($row['id_form_004'], $row['sortnum'], $row);
//	                	}
//	                	$objArr[$i] = $row;
//	                	$objArr[$i]['oldKey'] = $key;
//	                	$objArr[$i]['id_iep_team_district'] = $newId;
//	                	$i++;
//	                }
//	            }
//
//	            // delete items
//	            if(0 < count($decodedData['deletedItems'])) {
//	                foreach($decodedData['deletedItems'] as $row)
//	                {
//                		if('tempkey' == substr($row['id_iep_team_other'], 0, 7)) continue; // do not process new rows
//	                	$tmObj->deleteForm($row['id_iep_team_district']);
//	                }
//	            }        
//            
//	            $this->view->data = new Zend_Dojo_Data('id_iep_team_district', $objArr, 'id');
//	                
////	            $this->view->data = "success";
//                return $this->render('data');
//            }
//            catch (Zend_Db_Statement_Exception $e) {
//                throw new Zend_Dojo_Exception($e);
//            }
//            
//        }
//    // =======================================================================
//    // END DATAGRID FUNCTIONS
//    // =======================================================================
//
//    // =======================================================================
//    // Common form functions
//    // =======================================================================
//    	function getFormAux($iepForm, $formID) {
//    		$this->_helper->layout->disableLayout(true);
//            $request = $this->getRequest();//->getPost();
//            $id = $request->id;
//            if('' == $id) {
//                $this->_redirect('/error1');
//            }
//
//            $this->view->data = new Zend_Dojo_Data($formID, array($iepForm->getForm($id)), 'id');
//            return $this->render('data');
//    	}
//    	
//		function updateFormAux($iepForm, $formID) {	
//
//        	// update the main IEP form
//        	// validation is done here and passed back to the form
//            $this->_helper->layout->disableLayout(true);
//    
//            $request = $this->getRequest();
//            $post = $this->getRequest()->getPost();        
//
//            // confirm keys exist
//            if (!($request->getParam($formID))) {
//            	throw new exception('Error, form id must be passed.');
//            	return;
//            }
//
//            try {
//                $i =0;
//                $iepForm->saveForm($post[$formID], $post);
//
//                $this->view->data = "success";
//                
//                $this->view->page = $post['page'];
//                $this->view->version = 1;
//                
//                $form = $this->getForm('edit', $this->view->page, $this->view->version);
//                
//                $objArr = array($iepForm->getForm($post[$formID]));
//
//                // populate the form with db data
//                if(!$form->isValid($post)) {
//
//                    $this->view->errors   = $form->getErrors();
//                    $this->view->messages = $form->getMessages();
//                    //$this->view->valid = false;
//
//                    $objArr[0]['validationArr'] = $this->buildValidationArray($form, $this->view->messages);
//                }
//                $form->populate($post); 
//                
//                $this->view->data = new Zend_Dojo_Data($formID, $objArr, 'id');
//                return $this->render('data');
//            }
//            catch (Zend_Db_Statement_Exception $e) {
//                throw new Zend_Dojo_Exception($e);
//            }     
//        }
//    	
//    // =======================================================================
//    // form 001 functions
//    // =======================================================================
//        function getform001Action() { 
//        	$form = new DbTable_iepForm001();
//            $formid = 'id_form_001';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform001Action() {  	
//        	$form = new DbTable_iepForm001();
//            $formid = 'id_form_001';     
//            return $this->updateFormAux($form, $formid);
//        }  
//    // =======================================================================
//    // form 002 functions
//    // =======================================================================
//        function getform002Action() { 
//        	$form = new DbTable_iepForm002();
//            $formid = 'id_form_002';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform002Action() {  	
//        	$form = new DbTable_iepForm002();
//            $formid = 'id_form_002';     
//            return $this->updateFormAux($form, $formid);
//        }       
//    // =======================================================================
//    // form 003 functions
//    // =======================================================================
//    	function getform003Action() { 
//        	$form = new DbTable_iepForm003();
//            $formid = 'id_form_003';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform003Action() {  	
//        	$form = new DbTable_iepForm003();
//            $formid = 'id_form_003';     
//            return $this->updateFormAux($form, $formid);
//        } 
//	// =======================================================================
//    // form 004 functions
//    // =======================================================================
//    	function getform004Action() { 
//        	$form = new DbTable_iepForm004();
//            $formid = 'id_form_004';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform004Action() {  	
//        	$form = new DbTable_iepForm004();
//            $formid = 'id_form_004';     
//            return $this->updateFormAux($form, $formid);
//        }
//        
//        
//		public function changetransitionplanAction() {
//	        
//			$this->_helper->layout->disableLayout(true);     
//	        $helper = new My_View_Helper_form004page5Helper(); 
//	        
//	        $value =  $this->_request->getParam('value'); 
//
//	        $transition_secgoals_value = $this->_request->getParam('secgoals');
//	        $transition_16_course_study_value = $this->_request->getParam('course_study');
//	        $transition_16_instruction_value = $this->_request->getParam('instruction');
//	        $transition_16_rel_services_value = $this->_request->getParam('rel_services');
//	        $transition_16_comm_exp_value = $this->_request->getParam('comm_exp');
//	        $transition_16_emp_options_value = $this->_request->getParam('emp_options');
//	        $transition_16_dly_liv_skills_value = $this->_request->getParam('dly_liv_skills');
//	        $transition_16_func_voc_eval_value = $this->_request->getParam('func_voc_eval');
//	        $transition_16_inter_agency_link_value = $this->_request->getParam('inter_agency_link');
//	        
//	        if($value == 1) {
//		        $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
//		        
//		        //We might need to change the setValue method to something else for the checkbox
//		        $transition_secgoals = My_Form_formHelper::getZend_Form_Element_Checkbox('transition_secgoals', '', $JSmodifiedCode);
//		        $transition_secgoals->setValue($transition_secgoals_value);
//		        
//		        $transition_16_course_study = My_Form_formHelper::getZend_Dojo_Form_Element_Editor('transition_16_course_study', $JSmodifiedCode);
//				$transition_16_course_study->setValue($transition_16_course_study_value);
//		        $transition_16_course_study->setAttrib('style', 'width:100%;');
//		        
//				$transition_16_instruction = My_Form_formHelper::getZend_Dojo_Form_Element_Editor('transition_16_instruction', $JSmodifiedCode);
//				$transition_16_instruction->setValue($transition_16_instruction_value);
//				$transition_16_instruction->setAttrib('style', 'width:100%;');
//				
//				$transition_16_rel_services = My_Form_formHelper::getZend_Dojo_Form_Element_Editor('transition_16_rel_services', $JSmodifiedCode);
//				$transition_16_rel_services->setValue($transition_16_rel_services_value);
//				$transition_16_rel_services->setAttrib('style', 'width:100%;');
//				
//				$transition_16_comm_exp = My_Form_formHelper::getZend_Dojo_Form_Element_Editor('transition_16_comm_exp', $JSmodifiedCode);
//				$transition_16_comm_exp->setValue($transition_16_comm_exp_value);
//				$transition_16_comm_exp->setAttrib('style', 'width:100%;');
//				
//				$transition_16_emp_options = My_Form_formHelper::getZend_Dojo_Form_Element_Editor('transition_16_emp_options', $JSmodifiedCode);
//				$transition_16_emp_options->setValue($transition_16_emp_options_value);
//				$transition_16_emp_options->setAttrib('style', 'width:100%;');
//				
//				$transition_16_dly_liv_skills = My_Form_formHelper::getZend_Dojo_Form_Element_Editor('transition_16_dly_liv_skills', $JSmodifiedCode);
//				$transition_16_dly_liv_skills->setValue($transition_16_dly_liv_skills_value);
//				$transition_16_dly_liv_skills->setAttrib('style', 'width:100%;');
//				
//				$transition_16_func_voc_eval = My_Form_formHelper::getZend_Dojo_Form_Element_Editor('transition_16_func_voc_eval', $JSmodifiedCode);
//				$transition_16_func_voc_eval->setValue($transition_16_func_voc_eval_value);
//				$transition_16_func_voc_eval->setAttrib('style', 'width:100%;');
//				
//				$transition_16_inter_agency_link = My_Form_formHelper::getZend_Dojo_Form_Element_Editor('transition_16_inter_agency_link', $JSmodifiedCode);	
//				$transition_16_inter_agency_link->setValue($transition_16_inter_agency_link_value);
//				$transition_16_inter_agency_link->setAttrib('style', 'width:100%;');
//				
//		        $this->view->data = $helper->getTransitionPlanSubform($transition_secgoals, $transition_16_course_study, 
//								$transition_16_instruction, $transition_16_rel_services, $transition_16_emp_options, 
//								$transition_16_comm_exp, $transition_16_dly_liv_skills, $transition_16_func_voc_eval,
//								$transition_16_inter_agency_link);
//				
//	        } else {	        	
//	        	$this->view->data = '';
//	        }
//	        
//			return $this->render('data');
//			
//	    }
//
//    // =======================================================================
//    // form 005 functions
//    // =======================================================================
//    	function getform005Action() { 
//        	$form = new DbTable_iepForm005();
//            $formid = 'id_form_005';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform005Action() {  	
//        	$form = new DbTable_iepForm005();
//            $formid = 'id_form_005';     
//            return $this->updateFormAux($form, $formid);
//        }    
//    // =======================================================================
//    // form 006 functions
//    // =======================================================================
//    	function getform006Action() { 
//        	$form = new DbTable_iepForm006();
//            $formid = 'id_form_006';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform006Action() {  	
//        	$form = new DbTable_iepForm006();
//            $formid = 'id_form_006';     
//            return $this->updateFormAux($form, $formid);
//        }
//    // =======================================================================
//    // form 007 functions
//    // =======================================================================
//    	function getform007Action() { 
//        	$form = new DbTable_iepForm007();
//            $formid = 'id_form_007';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform007Action() {  	
//        	$form = new DbTable_iepForm007();
//            $formid = 'id_form_007';     
//            return $this->updateFormAux($form, $formid);
//        }  
//    // =======================================================================
//    // form 008 functions
//    // =======================================================================
//    	function getform008Action() { 
//        	$form = new DbTable_iepForm008();
//            $formid = 'id_form_008';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform008Action() {  	
//        	$form = new DbTable_iepForm008();
//            $formid = 'id_form_008';     
//            return $this->updateFormAux($form, $formid);
//        } 
//	// =======================================================================
//    // form 009 functions
//    // =======================================================================
//		function getform009Action() { 
//        	$form = new DbTable_iepForm009();
//            $formid = 'id_form_009';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform009Action() {  	
//        	$form = new DbTable_iepForm009();
//            $formid = 'id_form_009';     
//            return $this->updateFormAux($form, $formid);
//        }  
//    // =======================================================================
//    // form 010 functions
//    // =======================================================================
//    	function getform010Action() { 
//        	$form = new DbTable_iepForm010();
//            $formid = 'id_form_010';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform010Action() {  	
//        	$form = new DbTable_iepForm010();
//            $formid = 'id_form_010';     
//            return $this->updateFormAux($form, $formid);
//        } 
//	// =======================================================================
//    // form 011 functions
//    // =======================================================================
//    	function getform011Action() { 
//        	$form = new DbTable_iepForm011();
//            $formid = 'id_form_011';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform011Action() {  	
//        	$form = new DbTable_iepForm011();
//            $formid = 'id_form_011';     
//            return $this->updateFormAux($form, $formid);
//        }
//	// =======================================================================
//    // form 012 functions
//    // =======================================================================
//    	function getform012Action() { 
//        	$form = new DbTable_iepForm012();
//            $formid = 'id_form_012';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform012Action() {  	
//        	$form = new DbTable_iepForm012();
//            $formid = 'id_form_012';     
//            return $this->updateFormAux($form, $formid);
//        }
//	// =======================================================================
//    // form 013 functions
//    // =======================================================================
//		function getform013Action() { 
//        	$form = new DbTable_iepForm013();
//            $formid = 'id_form_013';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform013Action() {  	
//        	$form = new DbTable_iepForm013();
//            $formid = 'id_form_013';     
//            return $this->updateFormAux($form, $formid);
//        }
//	// =======================================================================
//    // form 014 functions
//    // =======================================================================
//    	function getform014Action() { 
//        	$form = new DbTable_iepForm014();
//            $formid = 'id_form_014';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform014Action() {  	
//        	$form = new DbTable_iepForm014();
//            $formid = 'id_form_014';     
//            return $this->updateFormAux($form, $formid);
//        }  
//	// =======================================================================
//    // form 015 functions
//    // =======================================================================
//    	function getform015Action() { 
//        	$form = new DbTable_iepForm015();
//            $formid = 'id_form_015';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform015Action() {  	
//        	$form = new DbTable_iepForm015();
//            $formid = 'id_form_015';     
//            return $this->updateFormAux($form, $formid);
//        }
//	// =======================================================================
//    // form 016 functions
//    // =======================================================================
//    	function getform016Action() { 
//        	$form = new DbTable_iepForm016();
//            $formid = 'id_form_016';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform016Action() {  	
//        	$form = new DbTable_iepForm016();
//            $formid = 'id_form_016';     
//            return $this->updateFormAux($form, $formid);
//        }
//	// =======================================================================
//    // form 017 functions
//    // =======================================================================
//    	function getform017Action() { 
//        	$form = new DbTable_iepForm017();
//            $formid = 'id_form_017';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform017Action() {  	
//        	$form = new DbTable_iepForm017();
//            $formid = 'id_form_017';     
//            return $this->updateFormAux($form, $formid);
//        }
//	// =======================================================================
//    // form 018 functions
//    // =======================================================================
//    	function getform018Action() { 
//        	$form = new DbTable_iepForm018();
//            $formid = 'id_form_018';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform018Action() {  	
//        	$form = new DbTable_iepForm018();
//            $formid = 'id_form_018';     
//            return $this->updateFormAux($form, $formid);
//        }
//	// =======================================================================
//    // form 019 functions
//    // =======================================================================
//    	function getform019Action() { 
//        	$form = new DbTable_iepForm019();
//            $formid = 'id_form_019';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform019Action() {  	
//        	$form = new DbTable_iepForm019();
//            $formid = 'id_form_019';     
//            return $this->updateFormAux($form, $formid);
//        }
//    // =======================================================================
//    // form 020 functions
//    // =======================================================================
//        function getform020Action() { 
//        	$form = new DbTable_iepForm020();
//            $formid = 'id_form_020';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform020Action() {  	
//        	$form = new DbTable_iepForm020();
//            $formid = 'id_form_020';     
//            return $this->updateFormAux($form, $formid);
//        } 
//	// =======================================================================
//    // form 021 functions
//    // =======================================================================
//        function getform021Action() { 
//        	$form = new DbTable_iepForm021();
//            $formid = 'id_form_021';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform021Action() {  	
//        	$form = new DbTable_iepForm021();
//            $formid = 'id_form_021';     
//            return $this->updateFormAux($form, $formid);
//        }  
//    // =======================================================================
//    // form 022 functions
//    // =======================================================================
//        function getform022Action() { 
//        	$form = new DbTable_iepForm022();
//            $formid = 'id_form_022';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform022Action() {  	
//        	$form = new DbTable_iepForm022();
//            $formid = 'id_form_022';     
//            return $this->updateFormAux($form, $formid);
//        }  
//    // =======================================================================
//    // form 023 functions
//    // =======================================================================
//        function getform023Action() { 
//        	$form = new DbTable_iepForm023();
//            $formid = 'id_form_023';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform023Action() {  	
//        	$form = new DbTable_iepForm023();
//            $formid = 'id_form_023';     
//            return $this->updateFormAux($form, $formid);
//        }  
//	// =======================================================================
//    // form 024 functions
//    // =======================================================================
//        function getform024Action() { 
//        	$form = new DbTable_iepForm024();
//            $formid = 'id_form_024';          
//            return $this->getFormAux($form, $formid);
//        }    
//
//        function updateform024Action() {  	
//        	$form = new DbTable_iepForm024();
//            $formid = 'id_form_024';     
//            return $this->updateFormAux($form, $formid);
//        }  
//}