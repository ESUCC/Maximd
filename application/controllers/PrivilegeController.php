<?php
///**
// * PrivilegeController 
// * 
// * @uses      Zend_Controller_Action
// * @package   Paste
// * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
// * @version   $Id: $
// */
//class PrivilegeController extends Zend_Controller_Action
//{
//    public function init()
//    {
//        $this->_redirector = $this->_helper->getHelper('Redirector');
//        $this->view->headLink()->appendStylesheet('/js/dijit/themes/soria/soria.css');
//
//    }
//
//    public function indexAction()
//    {
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
//                                   'id_county',
//                                   'id_district',
//                                   'id_school',
//                                   'id_neb_esu',
//                                   'class',
//                                   'status',
//                                   'name_county' => 'get_name_county(id_county)',
//                                   'name_district' => 'get_name_district(id_county, id_district)',
//                                   'name_school' => 'get_name_school(id_county, id_district, id_school)'
//                           ))
//                     ->where( "id_neb_user = '$userID' and status = 'Active'" )
//                     ->order( "name_county");
//
//        $result = $db->fetchAll($select);
//        
//        return $result;
//        
//    }
//
//    public function examineAction()
//    {
//        $request = $this->getRequest();
//        
//        $userID = $request->userid;
//                
//        $this->view->id = $userID;
//        
//        require_once(APPLICATION_PATH.'/../library/My/Classes/class_neb_acl.php');
//        $neb_acl = new My_Classes_class_neb_acl();
//        $perms = $neb_acl->getPermissions($userID);
////             echo "<PRE>";
////             print_r($perms);
////             echo "</PRE>";
//        if(-1 != $perms) $neb_acl->insertUserPermissions($perms);
//
//        //
//        // get privileges
//        //
//        $this->view->privResults = $this->getPrivileges($userID);
//
//            
//        $this->view->allowedContent = "";
//
//        //
//        // check isAllowed calls
//        //
//        $cds_id = "99_9999_999";
//        $this->view->allowedContent .= "checking school <B>$cds_id</B><BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cds_id, 'school_view', 'school') ? "$cds_id school_view <B>allowed</B><BR>" : "$cds_id school_view denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cds_id, 'school_edit', 'school') ? "$cds_id school_edit <B>allowed</B><BR>" : "$cds_id school_edit denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cds_id, 'school_create', 'school') ? "$cds_id school_create <B>allowed</B><BR>" : "$cds_id school_create denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cds_id, 'school_delete', 'school') ? "$cds_id school_delete <B>allowed</B><BR>" : "$cds_id school_delete denied<BR>";
//        $this->view->allowedContent .= "<BR>";
//        $cds_id = "99_9999_998";
//        $this->view->allowedContent .= "checking school <B>$cds_id</B><BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cds_id, 'school_view', 'school') ? "$cds_id school_view <B>allowed</B><BR>" : "$cds_id school_view denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cds_id, 'school_edit', 'school') ? "$cds_id school_edit <B>allowed</B><BR>" : "$cds_id school_edit denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cds_id, 'school_create', 'school') ? "$cds_id school_create <B>allowed</B><BR>" : "$cds_id school_create denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cds_id, 'school_delete', 'school') ? "$cds_id school_delete <B>allowed</B><BR>" : "$cds_id school_delete denied<BR>";
//        $this->view->allowedContent .= "<BR>";
//        $cds_id = "99_8888_998";
//        $this->view->allowedContent .= "checking school <B>$cds_id</B><BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cds_id, 'school_view', 'school') ? "$cds_id school_view <B>allowed</B><BR>" : "$cds_id school_view denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cds_id, 'school_edit', 'school') ? "$cds_id school_edit <B>allowed</B><BR>" : "$cds_id school_edit denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cds_id, 'school_create', 'school') ? "$cds_id school_create <B>allowed</B><BR>" : "$cds_id school_create denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cds_id, 'school_delete', 'school') ? "$cds_id school_delete <B>allowed</B><BR>" : "$cds_id school_delete denied<BR>";
//        $this->view->allowedContent .= "<BR>";
//        $cd_id = "99_9999";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cd_id, 'district_view', 'district') ? "$cd_id district_view <B>allowed</B><BR>" : "$cd_id district_view denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cd_id, 'district_edit', 'district') ? "$cd_id district_edit <B>allowed</B><BR>" : "$cd_id district_edit denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cd_id, 'district_create', 'district') ? "$cd_id district_create <B>allowed</B><BR>" : "$cd_id district_create denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cd_id, 'district_delete', 'district') ? "$cd_id district_delete <B>allowed</B><BR>" : "$cd_id district_delete denied<BR>";
//        $this->view->allowedContent .= "<BR>";
//        $cd_id = "99_8888";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cd_id, 'district_view', 'district') ? "$cd_id district_view <B>allowed</B><BR>" : "$cd_id district_view denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cd_id, 'district_edit', 'district') ? "$cd_id district_edit <B>allowed</B><BR>" : "$cd_id district_edit denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cd_id, 'district_create', 'district') ? "$cd_id district_create <B>allowed</B><BR>" : "$cd_id district_create denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cd_id, 'district_delete', 'district') ? "$cd_id district_delete <B>allowed</B><BR>" : "$cd_id district_delete denied<BR>";
//        $this->view->allowedContent .= "<BR>";
//        $cd_id = "5";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cd_id, 'esu_view', 'esu') ? "$cd_id esu_view <B>allowed</B><BR>" : "$cd_id esu_view denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cd_id, 'esu_edit', 'esu') ? "$cd_id esu_edit <B>allowed</B><BR>" : "$cd_id esu_edit denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cd_id, 'esu_create', 'esu') ? "$cd_id esu_create <B>allowed</B><BR>" : "$cd_id esu_create denied<BR>";
//        $this->view->allowedContent .= $neb_acl->isAllowed($cd_id, 'esu_delete', 'esu') ? "$cd_id esu_delete <B>allowed</B><BR>" : "$cd_id esu_delete denied<BR>";
//        
//        
//    }
//
//    /*
//     * The requestrole action will dispatch this when a county id was required but missing from a priv request.
//     * selectcounty takes all the previously entered info, adds the county, and tries the request again.
//     */
//    public function selectclassAction()
//    {
//        $params = $this->getRequest()->getUserParams();
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/RequestRole.ini', 'selectclass');
//        $form = new Zend_Form($config->form);
//
//        if (!$form->isValid($params)) {
//            $session = new Zend_Session_Namespace();
//            $session->message = '/privilege/selectclass called with bad parameters';
//
//            $this->_redirect('/');
//            return;
//        }
//        $this->view->form = $form;
//    }
//
//    /*
//     * The requestrole action will dispatch this when an esu (educational service unit) was required but missing from a priv request.
//     * selectesu takes all the previously entered info, adds the esu, and tries the request again.
//     */
//    public function selectesuAction()
//    {
//        $params = $this->getRequest()->getUserParams();
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/RequestRole.ini', 'selectesu');
//        $form = new Zend_Form($config->form);
//
//        if (!$form->isValid($params)) {
//            $session = new Zend_Session_Namespace();
//            $session->message = '/privilege/selectesu called with bad parameters';
//
//            $this->_redirect('/');
//            return;
//        }
//
//        // get the list of esus from the database
//        //
//        $db = Zend_Registry::get('db');
//        $esuStatement = $db->query("SELECT id_neb_esu, id_esu FROM neb_esu");
//        $esuResult = $esuStatement->fetchAll();
//
//        foreach ($esuResult as $k => $v) {
//
//            $form->getElement('id_neb_esu')->addMultiOption($v['id_neb_esu'],$v['id_esu']);
//        }
//
//        $this->view->form = $form;
//    }
//
//    /*
//     * The requestrole action will dispatch this when a district id was required but missing from a priv request.
//     * selectdistrict takes all the previously entered info, adds the district, and tries the request again.
//     */
//    public function selectdistrictAction()
//    {
//        $params = $this->getRequest()->getUserParams();
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/RequestRole.ini', 'selectdistrict');
//        $form = new Zend_Form($config->form);
//
//        if (!$form->isValid($params)) {
//            $session = new Zend_Session_Namespace();
//            $session->message = '/privilege/selectdistrict called with bad parameters';
//
//            $this->_redirect('/');
//            return;
//        }
//
//        // get the list of districts for the given esu
//        //
//        $db = Zend_Registry::get('db');
//        $districtStatement = $db->query("SELECT id_neb_district, name_district FROM neb_district WHERE id_neb_esu = ?", $params['id_neb_esu']);
//        $districtResult = $districtStatement->fetchAll();
//
//        foreach ($districtResult as $k => $v) {
//
//            $form->getElement('id_neb_district')->addMultiOption($v['id_neb_district'], $v['name_district']);
//        }
//
//        $this->view->form = $form;
//    }
//
//    /*
//     * The requestrole action will dispatch this when a county id was required but missing from a priv request.
//     * selectcounty takes all the previously entered info, adds the county, and tries the request again.
//     */
//    public function selectcountyAction()
//    {
//        $params = $this->getRequest()->getUserParams();
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/RequestRole.ini', 'selectcounty');
//        $form = new Zend_Form($config->form);
//
//        if (!$form->isValid($params)) {
//            $session = new Zend_Session_Namespace();
//            $session->message = '/privilege/selectcounty called with bad parameters';
//
//            $this->_redirect('/');
//            return;
//        }
//        $this->view->form = $form;
//    }
//
//    /*
//     * The requestrole action will dispatch this when a school id was required but missing from a priv request.
//     * selectschool takes all the previously entered info, adds the school, and tries the request again.
//     */
//    public function selectschoolAction()
//    {
//        $params = $this->getRequest()->getUserParams();
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/RequestRole.ini', 'selectschool');
//        $form = new Zend_Form($config->form);
//
//        if (!$form->isValid($params)) {
//            $session = new Zend_Session_Namespace();
//            $session->message = '/privilege/selectschool called with bad parameters';
//
//            $this->_redirect('/');
//            return;
//        }
//        $this->view->form = $form;
//    }
//
//    public function requestroleAction()
//    {
//        if (!$this->getRequest()->isPost()) {
//            $this->_redirect('/user/list');
//            return;
//        }
//
//        $post = $this->getRequest()->getPost();
//
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/RequestRole.ini', 'requestrole');
//        $form = new Zend_Form($config->form);
//
//        // validate
//        // only the userid and class are required. if nothing else is present it can be a request for a statewide role
//        //
//        if (!$form->isValid($post)) {
//            $session = new Zend_Session_Namespace();
//            $session->message = 'Your role request was badly formed';
//
//            $this->_redirect('/');
//            return;
//        }
//        else {
//            // it's ok that some of the fields might be null
//            $data = $post;
//            unset($data['submit']);
//
//            // if the missing fields are required, go get them. otherwise zero them.
//            // the database can check uniqueness better with zeros than with nulls
//            if ($data['class'] == 'sm') {
//                $data['id_neb_esu'] = 0;
//            }
//
//            if ($data['class'] == 'sm' || $data['class'] == 'esum' || $data['class'] == 'aesum') {
//                $data['id_neb_district'] = 0;
//            }
//
//            if (!isset($data['id_neb_esu'])) {
//                // there's no esu and it's not a state manager request, so get the esu
//                $this->_redirect('/privilege/selectesu/class/'.$data['class']);
//                return;
//            }
//
//            if (!isset($data['id_neb_district'])) {
//                // there's no district and it's a district manager or adm request, so get the district
//                $this->_redirect('/privilege/selectdistrict/class/'.$data['class'].'/id_neb_esu/'.$data['id_neb_esu']);
//                return;
//            }
//
//            //
//            // by this point, all data should be present and validated!!!
//            //
//
//            $auth = Zend_Auth::getInstance();
//
//            if ($auth->hasIdentity()) {
//                //
//                // get id_neb_user from login name
//                //
//                $identity = $auth->getIdentity();
//
//                $db = Zend_Registry::get('db');
//                $userStatement = $db->query("SELECT id_neb_user FROM neb_user WHERE email_address = ?", $identity);
//                $userResult = $userStatement->fetchAll();
//                $data['id_neb_user'] = $userResult[0]['id_neb_user'];
//
//                // check for an existing priv
//                $statement = $db->query("SELECT status
//                                     FROM neb_privilege
//                                     WHERE class=:class AND id_neb_user=:id_neb_user AND id_neb_esu=:id_neb_esu AND 
//                                           id_neb_district=:id_neb_district",
//                                        $data);
//                $result = $statement->fetchAll();
//
//                if (count($result) > 0) {
//                    
//                    // there's one in there, no insert. just check now whether it's active
//                    // to decide which error message to throw
//                    $session = new Zend_Session_Namespace();
//
//                    if ($result[0]['status'] == 'Active') {
//                        $session->message = 'You\'re already assigned to that role!';
//                    }
//                    else {
//                        $session->message = 'You\'ve already requested that role';
//                        // that's only sort of true. this just means the role is inactive.
//                        // something different should happen depending whether this was a new
//                        // request or a role the requester used to have. but there's no way to tell
//                        // the difference
//                    }
//                    $this->_redirect('/');
//                    return;
//                }
//                else {
//                    // no record in there, go ahead and insert one, then redirect to the view page
//
//                    $db->insert('neb_privilege', $data);
//                    $this->_redirect('/user/view/id/'.$data['id_neb_user']);
//                    return;
//                }
//            }
//            else {
//                $this->_redirect('/privilege/register/class/'.$data['class'].'/id_neb_esu/'.$data['id_neb_esu'].'/id_neb_district/'.$data['id_neb_district']);
//                return;
//            }
//        }
//    }
//
//    public function registerAction()
//    {
//        $params = $this->getRequest()->getUserParams();
//
//        // check for a form in the session (passed back from the doregister action). doesn't have to validate
//        //
//        $formSession = new Zend_Session_Namespace('register');
//        if ($formSession->form) {
//            $form = $formSession->form;
//            unset($formSession->form);
//        }
//
//        // if it's not there, build one from the form config and die if it doesn't validate
//        //
//        else {
//            $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/Register.ini', 'empty');
//            $form = new Zend_Form($config->form);
//
//            if (!$form->isValid($params)) {
//
//                $session = new Zend_Session_Namespace();
//                $session->message = '/privilege/register called with bad parameters';
//
//                $this->_redirect('/');
//                return;
//            }
//        }
//
//        $this->view->form = $form;
//    }
//
//    public function doregisterAction()
//    {
//        $params = $this->getRequest()->getPost();
//
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/Register.ini', 'filled');
//        $form = new Zend_Form($config->form);
//
//
//        // add a couple things to the validators that couldn't be set in config
//        //
//        $form->getElement('confirm_email')->addValidator(new Zend_Validate_Identical($params['email_address']));
//        $form->getElement('confirm_password')->addValidator(new Zend_Validate_Identical($params['password']));
//
//        $states = $form->getElement('address_state')->getMultiOptions();
//        unset($states[0]);
//        $form->getElement('address_state')->addValidator(new Zend_Validate_InArray($states));
//   
//
//        // if the form doesn't validate, put it in the session and go back to the input page
//        //
//        if (!$form->isValid($params)) {
//
//            $formSession = new Zend_Session_Namespace('register');
//            $formSession->form = $form;
//
//            $this->_redirect('/privilege/register/class/'.$params['class'].'/id_neb_esu/'.$params['id_neb_esu'].'/id_neb_district/'.$params['id_neb_district']);
//            return;
//        }
//
//        // validation passed
//        //
//        else {
//            // check for this user
//            //
//            $db = Zend_Registry::get('db');
//            $userExistsResult = $db->fetchAll("SELECT id_neb_user FROM neb_user where email_address = ?",$params['email_address']);
//
//            if (count($userExistsResult) > 0) {
//                // user already exists, abort
//                $session = new Zend_Session_Namespace();
//                $session->message = "That email address has already been registered";
//
//                $this->_redirect('/');
//                return;
//            }
//
//            // it's a new user and everything checks out, create a neb_user record
//            //
//            $data = $params;
//            unset($data['submit']);
//            unset($data['confirm_email']);
//            unset($data['confirm_password']);
//            unset($data['class']);
//            unset($data['id_neb_esu']);
//            unset($data['id_neb_district']);
//
//            $db->insert('neb_user',$data);
//
//            // find the new user's pk
//            $pkResult = $db->fetchAll("SELECT id_neb_user FROM neb_user WHERE email_address = ?",$data['email_address']);
//            $id_neb_user = $pkResult[0]['id_neb_user'];
//
//            // create the privilege. no need to check for existence, the user is brand new
//            //
//            $privData = array(
//                              'id_neb_user' => $id_neb_user,
//                              'id_neb_esu' => $params['id_neb_esu'],
//                              'id_neb_district' => $params['id_neb_district'],
//                              'class' => $params['class']
//                              );
//            $db->insert('neb_privilege',$privData);
//
//            // registration is complete, return to the home page
//            //
//            $session = new Zend_Session_Namespace();
//            $session->message = "Your registration was successful";
//            $this->_redirect('/');
//            return;
//        }
//    }
//}
