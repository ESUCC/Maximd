<?php

class App_Helper_Session extends Zend_Acl 
{

	static function cleanSessionForReuse($force = false) {
		
        $defaultNamespace = new Zend_Session_Namespace();
	    $sessUser = new Zend_Session_Namespace('user');
        if($force || $defaultNamespace->validSession || $defaultNamespace->siteaccessgranted || null != $sessUser->id_personnel) {
        	// user already has a session
        	foreach($defaultNamespace as $k => $v) {
	    		unset($defaultNamespace->$k);
        	}
        	foreach($sessUser as $k => $v) {
	    		unset($sessUser->$k);
        	}
        	Zend_Auth::getInstance()->clearIdentity();
	    	Zend_Session::forgetMe();
        }
		
	}	

	static function siteAccessGranted() {
		
        $defaultNamespace = new Zend_Session_Namespace();
	    $sessUser = new Zend_Session_Namespace('user');
        if($defaultNamespace->validSession && $defaultNamespace->siteaccessgranted && (null != $sessUser->id_personnel || null != $sessUser->id_guardian)) {
        	return true;
        }
		return false;
	}	
	
	static public function grantSiteAccess($user, $parent) {
        $defaultNamespace = new Zend_Session_Namespace();
		$defaultNamespace->validSession = true;
		$defaultNamespace->siteaccessgranted = true;

	    $sessUser = new Zend_Session_Namespace('user');
        $sessUser->user = $user;
        if(empty($sessUser->user->user['name_middle'])) {
            $sessUser->user->user['name_full'] = $sessUser->user->user['name_first'] . ' ' . $sessUser->user->user['name_last'];
        } else {
            $sessUser->user->user['name_full'] = $sessUser->user->user['name_first'] . ' ' .
                $sessUser->user->user['name_middle'] . ' ' . $sessUser->user->user['name_last'];
        }

        $sessUser->testUser = false;
        if($parent) {
            $sessUser->parent = true;
            $sessUser->id_guardian = $user->user['id_guardian'];
        } else {
            $sessUser->parent = false;
	        $sessUser->id_personnel = $user->user['id_personnel'];
	        $sessUser->sessIdUser = $user->user['id_personnel'];;
        }
	    return false;
		
	} 
	
	static public function grantArchiverSiteAccess($user, $parent) 
	{
		$config = Zend_Registry::get ( 'config' );
	
		$httpParams = array(
			'maxredirects' => 5,
			'timeout'  => 600,
		);

        // new HTTP request to new
        $newSiteClient = new Zend_Http_Client($config->DOC_ROOT.'login', $httpParams);
        $newSiteClient->setMethod(Zend_Http_Client::POST);
        $newSiteClient->setCookieJar();
        $newSiteClient->setParameterPost('email', 'archiver');
        $newSiteClient->setParameterPost('password', 'thisIsTheLoginForTheArchiver123');
        $newSiteClient->setParameterPost('submit', 'Continue');
        $newSiteClient->setParameterPost('agree', 't');
        $response = $newSiteClient->request();

        $dom = new Zend_Dom_Query($response->getBody());
        if($dom->query('#agree')->count()>=1) {
            // login failed
            Zend_Debug::dump('login failed to '.$config->DOC_ROOT);
            return false;
        } else {
            Zend_Debug::dump('login SUCCESS to '.$config->DOC_ROOT);
        }

        // new HTTP request to old site
        $oldSiteClient = new Zend_Http_Client('https://iep.unl.edu/logon.php?option=1', $httpParams);
        $oldSiteClient->setMethod(Zend_Http_Client::POST);
        $oldSiteClient->setCookieJar();
        $oldSiteClient->setParameterPost('userName', 'archiver');
        $oldSiteClient->setParameterPost('password', 'thisIsTheLoginForTheArchiver123');
        $oldSiteClient->setParameterPost('ferpa', '1');
        $oldSiteClient->setParameterPost('count', '1');
        $response = $oldSiteClient->request();

        $dom = new Zend_Dom_Query($response->getBody());
        if($dom->query('#ferpa')->count()>=1) {
            // login failed
            Zend_Debug::dump('login failed on iep.unl.edu');
            return false;
        } else {
            Zend_Debug::dump('login SUCCESS to iep.unl.edu');
        }

        $sessUser = new Zend_Session_Namespace('user');
        $sessUser->user = $user;
        if(empty($sessUser->user->user['name_middle'])) {
            $sessUser->user->user['name_full'] = $sessUser->user->user['name_first'] . ' ' . $sessUser->user->user['name_last'];
        } else {
            $sessUser->user->user['name_full'] = $sessUser->user->user['name_first'] . ' ' .
                $sessUser->user->user['name_middle'] . ' ' . $sessUser->user->user['name_last'];
        }

        $sessUser->newSiteClient = $newSiteClient;
        $sessUser->oldSiteClient = $oldSiteClient;
        $sessUser->testUser = false;
        $sessUser->testUser = false;
        if($parent) {
            $sessUser->parent = true;
            $sessUser->id_guardian = $user->user['id_guardian'];
        } else {
            $sessUser->parent = false;
            $sessUser->id_personnel = $user->user['id_personnel'];
            $sessUser->sessIdUser = $user->user['id_personnel'];;
        }

        return true;
	}

}
