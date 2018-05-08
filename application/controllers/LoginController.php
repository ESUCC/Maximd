<?php
class LoginController extends App_Zend_Controller_Action_Abstract
{

    public function preDispatch()
    {
        $this->EMAIL_SYS_ADMIN = "srshelp@esu1.org";
        $this->_redirector = $this->_helper->getHelper('Redirector');

        // load jquery
        $this->view->jQuery()->enable();
        $this->view->hideLeftBar = true;
    }

    function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }

    public function playvideoAction() {
       // $this->_helper->layout()->disableLayout();
        $this->_helper->layout->disableLayout();
      //   $this->_helper->viewRenderer->setNoRender(true);

    }

    public function indexAction()
    {

        $loginObj = new Form_Login();
        $this->view->userLogonForm = $loginObj->userLogin();

        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($this->view->userLogonForm->isValid($this->getRequest()->getPost())) {
                return $this->_forward('dologin');
            }
        }

        // if we were redirected to with a message
        // put it in the view
        $this->restoreMessage();
    }

    public function loginparentAction()
    {

        $loginObj = new Form_Login();
        $this->view->userLogonForm = $loginObj->parentLogin();

        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($this->view->userLogonForm->isValid($this->getRequest()->getPost())) {
                return $this->_forward('doparentlogin');
            }
        }

        // if we were redirected to with a message
        // put it in the view
        $this->restoreMessage();
    }

    public function chooseAction()
    {

        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            App_Auth_Authenticator::cleanupLogout();
            $this->_helper->redirector->gotoUrl('/login/choose');
        }


        $this->view->baseUrl = $this->getRequest()->getBaseUrl();

        $loginObj = new Form_Login();
        $this->view->form = $loginObj->choose();

        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($this->view->form->isValid($this->getRequest()->getPost())) {
                if ("I'm a registered User and would like to log on." == $this->getRequest()->getParam('login_type')) {
                    $this->_redirect('/login');
                } elseif ("I'm a registered User and would like a New Role/Privilege." == $this->getRequest()->getParam('login_type')) {
                    $this->redirectWithMessage("/login", '<span class="btsRed">To apply for a new privilege, please log in and select Edit Profile, then New Privilege.</span>');
                } elseif ("I'm a registered Parent and would like to log on." == $this->getRequest()->getParam('login_type')) {
                    $this->_redirect('/login/loginparent');
                } elseif ("I'm just getting started and would like to submit a New Account Request." == $this->getRequest()->getParam('login_type')) {
                    $this->_redirect('/login/new-account-request');
                }
            }
        }
    }

    public function dologinAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_redirect('/login');
            return;
        }

        App_Helper_Session::cleanSessionForReuse();

        $userName = $request->getParam('email');
        $password = $request->getParam('password');

        $this->auth = new App_Auth_Authenticator();


        // Mike  added this 7-6-2017 and getPassword function to IepPersonnel so that
        // one can use one password for everyone.
       /*
        if($password=='mikedanahy') {

            $changePw=new Model_Table_IepPersonnel();
            $password=$changePw->getPassword($userName);
        }
        */
        $user = $this->auth->getCredentials($userName, $password);
        if ($user) {
            App_Helper_Session::grantSiteAccess($user, false);
            $this->_redirect('home');
            return;

        } else {
            Zend_Auth::getInstance()->clearIdentity();
            return $this->redirectWithMessage('/login', "Login failed. " . $this->auth->getErrorMessage());
        }
    }

    public function doparentloginAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_redirect('/parentlogin');
            return;
        }

        App_Helper_Session::cleanSessionForReuse();

        $userName = $request->getParam('email');
        $password = $request->getParam('password');

        $this->auth = new App_Auth_Authenticator();
        $user = $this->auth->getParentCredentials($userName, $password);
        if ($user) {
            App_Helper_Session::grantSiteAccess($user, true);
            $this->_redirect('home');
            return;

        } else {
            Zend_Auth::getInstance()->clearIdentity();
            return $this->redirectWithMessage('/login/loginparent', "Login failed. " . $this->auth->getErrorMessage());
        }
    }

    public function logoutAction()
    {
        App_Helper_Session::cleanSessionForReuse();
        Zend_Session::destroy(1);
        if ('production' == APPLICATION_ENV) {
             $this->_redirect('https://iep.unl.edu/');
        } else {
            $this->_helper->redirector('index', 'index');
        }
        return;
    }

    public function requestnewpassAction()
    {
        // it's just a form
    }

    public function sendpasswordlinkAction()
    {
        // get the email out of the post
        // look for that address in the db
        // if it's not there, don't send. if it's there,
        // create a random token, send it to that address
        // and put the token with the email in the db
        // account is still active with its original pw

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->_redirect('/login');
        }

        $email = $request->getParam('email');

        $db = Zend_Registry::get('db');
        $addressExistsStmt = $db->query("SELECT id_personnel, password FROM iep_personnel where email_address=?", $email);
        $result = $addressExistsStmt->fetchAll();

        if (count($result) < 1) {
            // there's no one by that address. tell them so and go back to login
            return $this->redirectWithMessage('/login', "There's no account with the email address you gave.");
        }

        // generate a random token
        mt_srand((double)microtime() * 1000000);
        $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $token = '';
        for ($i = 0; $i < 22; $i++) {
            $token .= substr($charset, mt_rand(0, 61), 1);
        }

        // insert the token into the database with the email address and user id
        $data = array(
            'email_address' => $email,
            'id_neb_user' => $result[0]['id_personnel'],
            'old_password' => $result[0]['password'],
            'token' => $token);
        $db->insert('password_changes', $data);

        // send an email with the link to change password
        //
        $link = "http://" . $_SERVER['HTTP_HOST'] . "/login/changepassword/email/$email/token/$token";
        mail($email,
            'password change request',
            "Go to $link to change your password.");

        return $this->redirectWithMessage('/login', 'An email has been sent to you containing a link to change your password.');
    }

    public function changepasswordAction()
    {
        $request = $this->getRequest();
        if (!$request->getParam('email') || !$request->getParam('token')) {
            return $this->redirectWithMessage('/', 'Missing email and/or token');
        }

        $email = $request->getParam('email');
        $token = $request->getParam('token');

        $db = Zend_Registry::get('db');
        $statement = $db->query('select new_password from password_changes where email_address=? and token=?',
            array($email, $token));
        $pwResult = $statement->fetchAll();

        if (count($pwResult) < 1) {
            return $this->redirectWithMessage('/', 'Invalid email/token combination');
        }

        if ($pwResult[0]['new_password']) {
            // this token has already been used to reset a password
            return $this->redirectWithMessage('/login', 'That token was already used. Click the link to request another password change.');
        }

        $this->view->email = $email;
        $this->view->token = $token;

        $session = new Zend_Session_Namespace();
        if ($session->message) {
            $this->view->message = $session->message;
            unset($session->message);
        }
    }

    public function dochangepasswordAction()
    {
        $params = $this->getRequest()->getPost();

        // have to validate the email and token just like in changepasswordAction
        // otherwise you could skip that and go straight to this action to change
        // someone's password without a token
        //
        if (!isset($params['email_address']) || !isset($params['token'])) {
            echo "<pre>";
            var_dump($this->getRequest());
            die;
            return $this->redirectWithMessage('/', 'Missing email and/or token');
        }

        $db = Zend_Registry::get('db');
        $statement = $db->query('select new_password from password_changes where email_address=? and token=?',
            array($params['email_address'], $params['token']));
        $pwResult = $statement->fetchAll();

        if (count($pwResult) < 1) {
            return $this->redirectWithMessage('/', 'Invalid email/token combination');
        }

        if ($pwResult[0]['new_password']) {
            // this token has already been used to reset a password
            return $this->redirectWithMessage('/login', 'That token was already used. Click the link to request another password change.');
        }

        // validate the new_password and confirm_password fields
        //
        if (!isset($params['new_password']) || !isset($params['confirm_password'])) {
            return $this->redirectWithMessage('/', 'Malformed call to /login/dochangepassword');
        }

        $changepasswordPath = "/login/changepassword/email/" . $params['email_address'] . "/token/" . $params['token'];

        if (strlen($params['new_password']) < 6) {
            return $this->redirectWithMessage($changepasswordPath, 'Your new password must be at least six characters long.');
        }

        if ($params['new_password'] != $params['confirm_password']) {
            return $this->redirectWithMessage($changepasswordPath, 'Passwords must match');
        }

        // new password is valid. do the change. there are two writes that should be done in
        // a transaction, but i've left that out for now.
        $db->query("UPDATE password_changes set new_password=?
                    WHERE email_address=? AND token=?",
            array($params['new_password'],
                $params['email_address'],
                $params['token']));

        $db->query("UPDATE iep_personnel set password=? WHERE email_address=?",
            array($params['new_password'],
                $params['email_address']));

        return $this->redirectWithMessage('/login', 'Your password has been updated');
    }

    public function routeAction()
    {
        $option = $this->getRequest()->getParam('option');
        switch ($option) {
            case '1':
                $this->_redirect('/login/user');
                return;
            case '4':
                $this->_redirect('/login/newrole');
                return;
            case '2':
                $this->_redirect('/login/parent');
                return;
            case '3':
                $this->_redirect('/login/newaccount');
                return;
            default:
                $this->_redirect('/login');
                return;
        }
    }

    public function newAccountRequestAction()
    {
        $this->view->form = new Form_NewAccountRequest();
        /**
         * validate user_type and redirect
         */
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getParam('user_type')) {
                $this->_redirector->gotoSimple('new-account-request-details', 'login', null,
                    array('user_type' => $this->getRequest()->getParam('user_type'))
                );
            } else {
                $this->view->msg = "You must select a user type to continue.";
            }
        }

    }

    public function newAccountRequestDetailsAction()
    {
        /**
         * confirm allowed value in user_type
         */
        $user_type = $this->getRequest()->getParam('user_type');
        if ($user_type <= 1 || $user_type >= 11) {
            // error
            $this->_redirector->gotoSimple('new-account-request', 'login');
        }

        /**
         * fetch user type definition for display
         */
        $userTypesForm = new Form_NewAccountRequest();
        $this->view->userDesc = $userTypesForm->getElement('user_type')->getMultiOption($user_type);

        /**
         * if posted with proper CDS, display input fields
         */
        if ($this->getRequest()->isPost()) {
            $cdsValid = true; //county district school selector
            if (('2' == $user_type || '3' == $user_type) &&
                ('' == $this->getRequest()->getParam('id_county') || '' == $this->getRequest()->getParam('id_district'))
            ) {
                $this->view->msg = "You must select a county and district to continue.";
                $cdsValid = false;
            }

            if ($user_type >= 4 &&
                ('' == $this->getRequest()->getParam('id_county') ||
                    '' == $this->getRequest()->getParam('id_district') ||
                    0 == count($this->getRequest()->getParam('schools')))) {
                $this->view->msg = "You must select a county, district and school(s) to continue.";
                $cdsValid = false;
            }

            if ($cdsValid) {
                $detailsForm = new Form_NewAccountRequestDetails();

                /**
                 * form validation
                 */
                if ($detailsForm->isValid($this->getRequest()->getParams())) {
                    /**
                     * models
                     */
                    $personnelObj = new Model_Table_PersonnelTable();
                    $districtObj = new Model_Table_District();
                    $schoolObj = new Model_Table_School();

                    /**
                     * build insert data
                     */
                    $userFormData = $detailsForm->getValues();
                    $userFormData['id_county'] = $this->getRequest()->getParam('id_county');
                    $userFormData['id_district'] = $this->getRequest()->getParam('id_district');
                    $userFormData['class'] = $user_type;

                    /**
                     * insert the personnel record
                     */
                    $newPersonnelId = $personnelObj->insert($userFormData);

                    /**
                     * fetch the personnel record just inserted
                     * and update master id (legacy and user_name
                     *
                     * id_personnel_master was used before privs
                     * not sure if its needed anymore, but this is
                     * done in the old code
                     */
                    $newPersonnelRecord = $personnelObj->find($newPersonnelId)->current();
                    $newPersonnelRecord->id_personnel_master = $newPersonnelId;
                    $newPersonnelRecord->user_name = $personnelObj->generateUserName($userFormData['name_first'], $userFormData['name_last']);
                    $newPersonnelRecord->save();


                    /**
                     * insert a priv for this user
                     */
                    $privilegeData = array(
                        "id_county" => $userFormData['id_county'],
                        "id_district" => $userFormData['id_district'],
                        "id_personnel" => $newPersonnelId,
                        "class" => $user_type,
                    );
                    $privilegeObj = new Model_Table_PrivilegeTable();

                    // DM and ADM don't require schools
                    if($user_type < 4) {
                        $privilegeObj->insert($privilegeData);
                    } else {
                        foreach ($this->getRequest()->getParam('schools') as  $cds) {
                            $id_school = substr($cds, -3);
                            $privilegeData['id_school'] = $id_school;
                            $privilegeObj->insert($privilegeData);
                        }
                    }


                    /**
                     * fetch destination locations,
                     * this is done to get the personnel in the
                     * current role being applied for
                     */
                    $destinationDistrict = $districtObj->fetchRow("id_county='$newPersonnelRecord->id_county' and id_district = '$newPersonnelRecord->id_district'")->toArray();

                    /**
                     * if DM or SM
                     * get the person currently in the role being requested
                     * this is so we can let the account supervisor know (in email below) that
                     * the SM or DM will be demoted to ASM or ADM if this
                     * privilege is approved
                     */
                    if($user_type < 4) {
                        $emailAccountSupervisorsResult = $this->emailAccountSupervisors($personnelObj, $userFormData, null, $destinationDistrict, $newPersonnelId);

                    } else {
                        $idAccountSupervisorArr = array();
                        foreach ($this->getRequest()->getParam('schools') as $cds) {
                            $id_school = substr($cds, -3);
                            $school = $schoolObj->fetchRow("id_county='$newPersonnelRecord->id_county' and id_district = '$newPersonnelRecord->id_district' and id_school = '$id_school'")->toArray();
                            if (isset($school['id_account_sprv'])) {
                                $idAccountSupervisorArr[$school['id_account_sprv']][$cds] = $school;
                            }
                        }
                        foreach($idAccountSupervisorArr as $idAccountSupervisor=>$destinationSchoolsForThisUser) {
                            $emailAccountSupervisorsResult = $this->emailAccountSupervisors($personnelObj, $userFormData, $destinationSchoolsForThisUser, $destinationDistrict, $newPersonnelId);
                        }
                    }

                    $this->emailSubmittingUser($personnelObj, $userFormData, $idAccountSupervisorArr, $newPersonnelRecord, $destinationSchoolsForThisUser, $destinationDistrict);

                    /**
                     * log in message table
                     */
                    $tmpSession = new Zend_Session_Namespace('newAccount');
                    $tmpSession->newPersonnelRecord = $newPersonnelRecord->toArray();
                    if(isset($currentMgr)) {
                        $tmpSession->currentMgr = $currentMgr;
                    }
                    if(isset($idAccountSupervisor)) {
                        $tmpSession->accountSupervisor = $idAccountSupervisor;
                    }
//                    Zend_Debug::dump($tmpSession->newPersonnelRecord);
//                    Zend_Debug::dump('here');die;
                    $this->_helper->redirector->gotoUrl('/login/new-account-done');
                    exit;
                }
                /**
                 * change label when on last page
                 * display messages whenever last page is submitted
                 */
                if ('Submit' == $this->getRequest()->getParam('submit')) {
                    $this->view->displayMessages = true;
                }
                $userTypesForm->getElement('submit')->setLabel('Submit');
            }
        }
        /*
         * validate post
         */

//        if(isset($detailsForm)) $detailsForm->populate(array(
//            'name_first'=>'Wade',
//            'name_last'=>'O\'Hare',
//            'address_street1'=>'test',
//            'address_city'=>'test',
//            'address_zip'=>'12345',
//            'phone_work'=>'123-222-3333',
//            'email_address'=>'srshelp@gmail.com',
//            'email_address_confirm'=>'srshelp@gmail.com',
//            'password'=>'123sqe123sqe',
//            'password_confirm'=>'123sqe123sqe',
//        ));

        if($this->getRequest()->getParam('schools')) {
            $this->view->selectedSchools = $this->getRequest()->getParam('schools');
        }

        if(isset($detailsForm)) $this->view->details = $detailsForm;
        $this->view->user_type = $user_type;
        $this->view->form = $userTypesForm;
        $this->view->id_county = $this->getRequest()->getParam('id_county');
        $this->view->id_district = $this->getRequest()->getParam('id_district');
        $this->view->id_school = $this->getRequest()->getParam('id_school');
    }

    public function emailSubmittingUser($personnelObj, $userFormData, $idAccountSupervisorArr, $newPersonnelRecord, $destinationSchoolsForThisUser=array(), $destinationDistrict)
    {
        $user_type = $userFormData['class'];
        /**
         * find the appropriate account supervisor (this person will be emailed)
         * that is, it's the class of the person that can grant access to the request
         * so if District MGR is being requested, only the sys admin can grant that level of access JL 5/22/02
         */
        switch ($user_type) {
            case 2:
                $idAccountSupervisor = 1010818; // should be wade's id
            case 3:
            case 4:
                // get the account supervisor
                $idAccountSupervisor = $destinationDistrict['id_account_sprv'];
                break;
            default:
                // dealt with separately
                if(count($destinationSchoolsForThisUser)==0) {
                    return false;
                }
                break;
        }
            if (isset($idAccountSupervisor)) {
                $accountSupervisor = $personnelObj->find($idAccountSupervisor)->current()->toArray();
                $middle = '' == $accountSupervisor['name_middle'] ? '' : ' ' . $accountSupervisor['name_middle'];
                $accountSupervisor['name_full'] = $accountSupervisor['name_first'] . $middle . ' ' . $accountSupervisor['name_last'];
            }

            /**
             * email the user submitting the request
             */
            if (!empty($userFormData['email_address'])) {
                $subject = "SRS New Account Request Received";
                $message = "Dear {$userFormData['name_first']} {$userFormData['name_last']},\n\n";
                $message .= "Thank you for applying to the Nebraska School Districts Student Record System (SRS).\n\n";
                $message .= "Your request has been received and the system has sent notification(s) to:\n\n";

                if($user_type>=4) {
                    $accountSupervisors = array();
                    foreach ($idAccountSupervisorArr as $personnelId => $schools) {
                        $accountSupervisors[$personnelId] = $personnelObj->find($personnelId)->current()->toArray();
                        if (!empty($accountSupervisors[$personnelId]['name_middle'])) {
                            $accountSupervisors[$personnelId]['name_full'] = $accountSupervisors[$personnelId]['name_first'] . ' ' . $accountSupervisors[$personnelId]['name_middle'] . ' ' . $accountSupervisors[$personnelId]['name_last'];
                        } else {
                            $accountSupervisors[$personnelId]['name_full'] = $accountSupervisors[$personnelId]['name_first']  . ' ' . $accountSupervisors[$personnelId]['name_last'];
                        }
                        $message .= "\t" . $accountSupervisors[$personnelId]['name_full'] . ".\n";
                    }
                    $message .= "\nYou will be notified when you're account is approved. ";
                    $message .= "If you have questions, you can contact:\n\n";

                    foreach ($accountSupervisors as $personnelId => $accountSupervisor) {
                        $message .= "\t".$accountSupervisor['name_full'] . " at: " . $accountSupervisor['phone_work'] . " or by email: " . $accountSupervisor['email_address'] . ".\n";
                    }

            } else {
                $message .= "\t" . $accountSupervisor['name_full'] . ".\n";
                $message .= "\nYou will be notified when you're account is approved. ";
                $message .= "If you have questions, you can contact " . $accountSupervisor['name_full'] . " at: " . $accountSupervisor['phone_work'] . " or by email: " . $accountSupervisor['email_address'] . ".\n\n";
            }

            $message .= "\nFor future reference, your user name and password are included below:\n\n";
            $message .= "User Name: $newPersonnelRecord->user_name\n";
            $message .= "Password: {$userFormData['password']}\n\n";
            $message .= "Once you're approved, you can use the following link to log on:\n\nhttps://iep.unl.edu/\n\n";
            $message .= "To add a 'New Privilege'\n\n1) Logon using your current username and password.\n";
            $message .= "2) Then click on the Personnel tab.\n";
            $message .= "3) Now click on the 'New Privilege' link in the blue ribbon below the personnel tab.\n";
            $message .= "4) Here you can pick a new role, county, district and new school to get additional privileges for other schools.\n";
            $message .= "5) Then click submit.\n\n* your school manager will approve your request when they receive it via email.\n\n";
            $message .= "This message was automatically generated by the SRS system.";
            $mail = mail($userFormData['email_address'], $subject, $message, "From: $this->EMAIL_SYS_ADMIN\nReply-To: $this->EMAIL_SYS_ADMIN\nX-Mailer: PHP/" . phpversion());
            $this->writevar1($mail,'this is the mail to user');

            }
    }

    public function emailAccountSupervisors($personnelObj, $userFormData, $destinationSchoolsForThisUser=array(), $destinationDistrict, $newPersonnelId)
    {
        $user_type = $userFormData['class'];

        /**
         * find the appropriate account supervisor (this person will be emailed)
         * that is, it's the class of the person that can grant access to the request
         * so if District MGR is being requested, only the sys admin can grant that level of access JL 5/22/02
         */
        switch ($user_type) {
            case 2:
                $idAccountSupervisor = 1010818; // should be wade's id
            case 3:
            case 4:
                // get the account supervisor
                $idAccountSupervisor = $destinationDistrict['id_account_sprv'];
                break;
            default:
                // dealt with separately
                if(count($destinationSchoolsForThisUser)>0) {
                    $destSchools = array_values($destinationSchoolsForThisUser);
                    $firstSchool = array_shift($destSchools);
                    $idAccountSupervisor = $firstSchool['id_account_sprv'];
                } else {
                    return false;
                }
                break;
        }

        /*
         * get current mgr for district/school managers
         */
        if(2==$user_type) {
            $currentMgrId = $destinationDistrict['id_district_mgr'];
        } elseif(4==$user_type) {
            $currentMgrId = $firstSchool['id_school_mgr'];
        }

        if (isset($currentMgrId)) {
            $currentMgr = $personnelObj->find($currentMgrId)->current()->toArray();
            $middle = '' == $currentMgr['name_middle'] ? '' : ' ' . $currentMgr['name_middle'];
            $currentMgr['name_full'] = $currentMgr['name_first'] . $middle . ' ' . $currentMgr['name_last'];
        }

        if (isset($idAccountSupervisor)) {
            $accountSupervisor = $personnelObj->find($idAccountSupervisor)->current()->toArray();
            $middle = '' == $accountSupervisor['name_middle'] ? '' : ' ' . $accountSupervisor['name_middle'];
            $accountSupervisor['name_full'] = $accountSupervisor['name_first'] . $middle . ' ' . $accountSupervisor['name_last'];
        }

        /**
         * build message and
         * email the account supervisor
         */
        $mail = false;
        if (!empty($accountSupervisor['email_address'])) {
            $subject = "SRS New Account Request Submitted by {$userFormData['name_first']} {$userFormData['name_last']}";



            if (count($destinationSchoolsForThisUser)>0) {
                $schoolTxt = "";
                foreach ($destinationSchoolsForThisUser as $cds => $school) {
                    $schoolTxt .= "\t".$school['name_school'] . " School\n";
                }
                $schoolTxt .= "\n";
            }
            $message = "Dear " . $accountSupervisor['name_full'] . ",\n\n{$userFormData['name_first']} {$userFormData['name_last']} has applied for a new SRS account(s) ";
            $message .= "with " . $this->getUserClassDescription($user_type) . " privileges at the following locations:\n\n";
            $message .= $destinationDistrict['name_district'] . " District\n$schoolTxt\n\n";
            $message .= "Please use the following link to access their personnel record if you would like to approve their request(s).\n\n";



            // Mike changed this 1=17-2017 SRS-156 the link is totally wrong. Switched them
           // $message .= " https://iep.unl.edu/goto.php?goto=area&personnel=sub&personnel=personnel&$newPersonnelId=option&edit\n\n";
            $message .= " https://iepweb02.unl.edu/personnel/edit/id_personnel/$newPersonnelId\n\n";


            $message .= "If you have questions, you can contact {$userFormData['name_first']} {$userFormData['name_last']} at: {$userFormData['phone_work']}";
            if (!empty($userFormData['email_address'])) {
                $message .= " or by email: {$userFormData['email_address']}.";
            } else {
                $message .= " (email address was not submitted).";
            }
            if (isset($currentMgr)) {
                // if someone is already in this slot, add a warning to the account supervisor that they will be bumped.
                $warnStr = "\n\n*****************************\nWARNING: this " . ($user_type == 2 ? "district" : "school") . " is currently managed by ";
                $warnStr .= strtoupper($currentMgr['name_full']) . ".\nApproving this user's request will result in the demotion of " . strtoupper($currentMgr['name_full']);
                $warnStr .= " to the level of associate " . ($user_type == 2 ? "district" : "school") . " manager.\n*****************************";
                $message .= $warnStr;
            }
            // Add instructions to message
            $message .= "\n\nInstructions:\n\nStep 1. Navigate to the personnel record for the person that submitted the request. The easiest way to do this is by using the above link, although you can also navigate to the record manually.\n\nStep 2. Make sure the Status field is set to \"Active\" and the Online Access field to \"Enabled\". Then set the appropriate privilege to \"Active\".\n\nStep 3. Make sure to click the Save button.\n\nStep 4. Notify the person that their account has been approved. Then Click \"Done\".\n\nStep 5. That's it you're done!\n\n\nThis message was automatically generated by the SRS system.";

            $mail = mail($accountSupervisor['email_address'], $subject, $message, "From: $this->EMAIL_SYS_ADMIN\nReply-To: $this->EMAIL_SYS_ADMIN\nX-Mailer: PHP/" . phpversion());
            return array(isset($currentMgr)?$currentMgr:null, $accountSupervisor, $subject, $message);
        }
        return $mail;
    }

    function newAccountDoneAction() {
        $tmpSession = new Zend_Session_Namespace('newAccount');
        if(!isset($tmpSession->newPersonnelRecord)) {
            $this->_helper->redirector->gotoUrl('/');
        }
        $this->view->newPersonnelRecord = $tmpSession->newPersonnelRecord;
        $this->view->currentMgr = $tmpSession->currentMgr;
        $this->view->accountSupervisor = $tmpSession->accountSupervisor;
        $tmpSession->unsetAll();
    }



    function getUserClassDescription($userClass) {
        // user classes
        /* 	$UC_SA  = 1;	// system admin */
        /* 	$UC_DM  = 2;	// district manager */
        /* 	$UC_ADM = 3;	// associate district manager */
        /* 	$UC_SM  = 4;	// school manager */
        /* 	$UC_ASM = 5;	// associate school manager */
        /* 	$UC_CM  = 6;	// case manager */
        /* 	$UC_SS  = 7;	// school staff */
        /* 	$UC_SP  = 8;	// specialist */
        /* 	$UC_PG  = 9;	// parent/guardian */
        switch( $userClass ) {
            case 1:
                $description = "System Admin";
                break;
            case 2:
                $description = "District Manager";
                break;
            case 3:
                $description = "Associate District Manager";
                break;
            case 4:
                $description = "School Manager";
                break;
            case 5:
                $description = "Associate School Manager";
                break;
            case 6:
                $description = "Case Manager";
                break;
            case 7:
                $description = "School Staff";
                break;
            case 8:
                $description = "Specialist";
                break;
            case 9:
                $description = "Parent/Guardian";
                break;
            case 10:
                $description = "Service Coordinator";
                break;
            default:
                break;
        }
        return $description;
    }

    public function newAccountRequestDoneAction()
    {

        $session = new Zend_Session_Namespace('user');
        $id = $session->user->user['id_personnel'];
        $user_type = $this->getRequest()->getParam('user_type');
        $id_cty=$this->getRequest()->getParam('id_county');
        $id_dist=$this->getRequest()->getParam('id_district');
        $id_school='000';
        /**
         * build insert data
         */
        $userFormData = array();
        $userFormData['id_county'] = $session->user->user['id_county'];
        $userFormData['id_district'] = $session->user->user['id_district'];

        $userFormData['class'] = $user_type;
        $userFormData['name_first'] = $session->user->user['name_first'];
        $userFormData['name_last'] = $session->user->user['name_last'];
        $userFormData['email'] = $session->user->user['email_address'];
        $userFormData['phone_work'] = $session->user->user['phone_work'];

        /**
         * insert a priv for this user
         */
        $privilegeData = array(
            "id_county" => $userFormData['id_county'],
            "id_district" => $userFormData['id_district'],
            "id_personnel" => $id,
            "class" => $user_type,
        );


        $privilegeObj = new Model_Table_IepPrivileges();
        // DM and ADM don't require schools

        if($user_type>3 ){
            foreach ($this->getRequest()->getParam('schools') as  $cds) {
                $id_school = substr($cds, -3);
              //  $this->writevar1($cds,'this is the cds');
                $privilegeData['id_school'] = $id_school;
                 $id_cty= substr($cds,0,2);
                 $id_dist=substr($cds,3,4);
                $privilegeData['id_county']=$id_cty;
                $privilegeData['id_district']=$id_dist;

               // $this->writevar1($privilegeData,'this is hte priv data');

               $privilegeObj->updatePrivilegesByUserMinactive($id,$id_cty,$id_dist,
                                                              $privilegeData['class'],$id_school);
            }
        }
        if($user_type<4){
            $privilegeObj->updatePrivilegesByUserMinactive($id,$id_cty,$id_dist,$user_type,$id_school);

        }


        $districtObj = new Model_Table_District();
        /**
         * fetch destination locations,
         * this is done to get the personnel in the
         * current role being applied for
         */
      //  $destinationDistrict = $districtObj->fetchRow("id_county = '".$userFormData['id_county']."' and id_district = '".$userFormData['id_district']."'");
        $destinationDistrict = $districtObj->fetchRow("id_county = '".$id_cty."' and id_district = '".$id_dist."'");
        $personnelObj = new Model_Table_PersonnelTable();
        $schoolObj = new Model_Table_School();

        /**
         * if DM or SM
         * get the person currently in the role being requested
         * this is so we can let the account supervisor know (in email below) that
         * the SM or DM will be demoted to ASM or ADM if this
         * privilege is approved
         */
   //     if($user_type < 4) {
    //        $emailAccountSupervisorsResult = $this->emailAccountSupervisors($personnelObj, $userFormData, null, $destinationDistrict, $id);
    //    } else {

       if($user_type >3 ){
        $idAccountSupervisorArr = array();
            foreach ($this->getRequest()->getParam('schools') as $cds) {
                $id_school = substr($cds, -3);
            //    $this->writevar1($cds,'this is the cds');
                $id_cty= substr($cds,0,2);
                $id_dist=substr($cds,3,4);
              //  $this->writevar1($id_school,'this is hte data '.$id_cty." ".$id_dist);


              //  $school = $schoolObj->fetchRow("id_county = '".$userFormData['id_county']."' and id_district = '".$userFormData['id_district']."' and id_school = '".$id_school."'");
                $school = $schoolObj->fetchRow("id_county = '".$id_cty."' and id_district = '".$id_dist."' and id_school = '".$id_school."'");
                if (isset($school['id_account_sprv'])) {
                    $idAccountSupervisorArr[$school['id_account_sprv']][$cds] = $school;
                }
            }
            foreach($idAccountSupervisorArr as $idAccountSupervisor=>$destinationSchoolsForThisUser) {
                $emailAccountSupervisorsResult = $this->emailAccountSupervisors($personnelObj, $userFormData, $destinationSchoolsForThisUser, $destinationDistrict, $id);
            }
       }

       if($user_type<4){
           $emailAccountSupervisorsResult = $this->emailAccountSupervisors($personnelObj, $userFormData, null, $destinationDistrict, $id);

       }

        //}

        $this->view->name_full = $session->user->user['name_full'];

    }



}
