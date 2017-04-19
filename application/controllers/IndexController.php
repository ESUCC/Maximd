<?php
/**
 * IndexController 
 *  Mike added this 4-14-2017 at 9:50
 * @uses      Zend_Controller_Action
 * @package   Paste
 * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version   $Id: $
 */
class IndexController extends App_Zend_Controller_Action_Abstract
{
    /**
     * Home page; display site entrance form
     * 
     * @return void
     */
	
	
	public function preDispatch() {
		// load jquery
		$this->view->jQuery()->enable();
	}
    public function indexAction()
    {
    	$this->view->hideLeftBar = true;
        //$this->view->siteEntranceForm_php = $this->SiteEntrance();

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/forms/Login.ini', 'SiteEntrance');
        $this->view->config = $config;
        $this->view->siteEntranceForm = new Zend_Form($config->login->SiteEntrance);
		
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        //Zend_Debug::dump($config->NONZEND_ROOT);die();
    	if('production' == APPLICATION_ENV) {
    		$this->_redirect('https://iep.esucc.org/');
    	}
    	$this->restoreMessage();
        //ERROR_REPORTING(E_ALL);//  ^ E_NOTICE ^ E_WARNING
        
//        $session = new Zend_Session_Namespace();
//        if ($session->message) {
//            $this->view->message = $session->message;
//            unset($session->message);
//        }
//        echo 'frank1';die();
    }

    public function loginredirectAction() {
    	
		$request = $this->getRequest();
		$token = $request->getParam('token');
		$parent = $request->getParam('parent', '0');
		
		if('' != $token) {
    		
			// clean out any vars that may remain from a previous session
			App_Helper_Session::cleanSessionForReuse();
	        
	        // get IEP session record from passed token
			// this is the session record for the iep.esucc.org site
			$tk = new Model_Table_IepSession();
			$session = $tk->getSessionByToken($token);
			if($session) {
				if($parent) {
					// get personnel record
					$guardianObj = new Model_Table_GuardianTable();
					$userRecords = $guardianObj->find($session['id_user']);
				} else {
					// get personnel record
					$personnelObj = new Model_Table_PersonnelTable();
					$userRecords = $personnelObj->find($session['id_user']);
				}
				if(count($userRecords) > 0)
				{
					// log the user in
					$user = $userRecords->current();
			        $userName = $user['user_name'];
			        $password = $user['password'];
					
					$this->auth = new App_Auth_Authenticator();
					if($parent) {
						$authenticatedUser = $this->auth->getParentCredentials($userName, $password);
					} else {
						$authenticatedUser = $this->auth->getCredentials($userName, $password);			
					}
					
					if($authenticatedUser)
					{			
			            // set the expiration time to two weeks
			            // expiration will actually be controlled in My_Session_SaveHandler_Db
			            $authSession = new Zend_Session_Namespace('Zend_Auth');
			            $authSession->setExpirationSeconds(60*60*24*14);
						
			            // setup proper session variables for access
			            App_Helper_Session::grantSiteAccess($authenticatedUser, $parent);
			            
			            // store the legacy site session id (mostly for curling the old site)
			            if(isset($session['id_session'])) {
				            $sessUser = new Zend_Session_Namespace('user');
				            $sessUser->legacySiteSessionId = trim($session['id_session']);
			            }
			        } else {
			        	Zend_Auth::getInstance()->clearIdentity();
			            return $this->redirectWithMessage('/login',"Login failed. " . $this->auth->getErrorMessage());
			        }
				}
			}
			
		} else {
			// try to get the token from iep and relogin
	    	if('production' == APPLICATION_ENV) {
	    		
//	    		$sessUser = new Zend_Session_Namespace('user');
//	    		if(1000254 == $sessIdUser->id_personnel) {
//	    			$this->getRequest()->getParam('destination');die();
//	    		}
	    		
	    		
	    		return $this->_redirect('https://iep.esucc.org/srs.php?area=personnel&sub=gettoken&destination='.$this->getRequest()->getParam('destination'));
	    	} else {
	    		return $this->_redirect('/home');
	    	}
			
		}        
        if($this->getRequest()->getParam('destination')) {
        	$this->_redirect(str_replace('-', '/', $this->getRequest()->getParam('destination')));
        } elseif('production' == APPLICATION_ENV) {
    		$this->_redirect('https://iep.esucc.org/srs.php?area=home&sub=home&option=1');
    	} else {
    		$this->_redirect('/home');
    	}
		            	
    }
    

    public function loginredirectchooseAction()
    {
        $this->_helper->redirector('choose', 'login');
        return;
    }


    //
    // forms
    //
//     public function SiteEntrance()
//     {
//         $form = $this->_helper->formLoader('Login');
//         return $form->SiteEntrance();
//     }

    public function confirmEmailUpdateAction() {
        $this->view->message = "There was an error updating your email. Please try again.";
        if($this->getRequest()->getParam('hash')) {
            $table = new Model_Table_PersonnelTable();
            $where = $table->getAdapter()->quoteInto('update_email_hash = ?', $this->getRequest()->getParam('hash'));
            $personnel = $table->fetchRow ( $where );
            if(!is_null($personnel)) {
                $personnel->email_address = $personnel->update_email_address;
                $personnel->update_email_address = '';
                $personnel->update_email_hash = '';
                $personnel->save();
                $this->view->message = "Your email address has been updated.";
            }
        }

    }

}
