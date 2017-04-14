<?php

class App_Auth_Authenticator {
	
	public $errorMessage;	
	
	const ERR_NOT_FOUND 	= "User is not found";
	const ERR_BAD_PASSWORD 	= "password is invalid"; 
	const ERR_NO_PRIVS 		= "no valid privileges"; 
	
	public function getCredentials($username, $password)
	{
		$validatedUser = $this->validateUser($username, $password, 'App_Auth_UserLookup');
		if ($validatedUser)
			return $validatedUser;

		if (isset($this->errorMessage))
			return false;
			
		$this->errorMessage = self::ERR_NOT_FOUND;
		return false;
		
	}
	public function getParentCredentials($username, $password)
	{
		$validatedUser = $this->validateParent($username, $password, 'App_Auth_UserLookup');
		if ($validatedUser)
			return $validatedUser;

		if (isset($this->errorMessage))
			return false;
			
		$this->errorMessage = self::ERR_NOT_FOUND;
		return false;
		
	}
	private function validateUser($username, $password, $lookup)
	{
        //
        // build auth on the personnel table
        // fields user_name and password will be used
        //
        $auth = Zend_Auth::getInstance();

        $authAdapter = new Zend_Auth_Adapter_DbTable(
		    Zend_Registry::get('db'),
		    'iep_personnel',
		    'user_name',
		    'password',
		    "status = 'Active' and exists (select count(1) from iep_privileges pr where iep_personnel.id_personnel = pr.id_personnel and pr.status = 'Active')"
		);
        
        // pass the posted user/pass
        $authAdapter->setIdentity($username)
                    ->setCredential($password);

        try {
            // authenticate this user
            $authResult = $auth->authenticate($authAdapter);
        }
        catch (Exception $e) { 
            var_dump($e); die;
            $this->errorMessage = $e;
            return false;
                 
        }

        // check login credentials here
        if ($authResult->isValid()) {
        	// fetch and return the user object
            $user = $this->validateUser_reflection($username, $password, $lookup);
            return $user;
        } else {
        	$this->errorMessage = self::ERR_BAD_PASSWORD;
        }
		return false;	
	}
 	private function validateUser_reflection($username, $password, $lookup)
	{
		$refClass  = new ReflectionClass($lookup);
		$refMethod = $refClass->getMethod('findByUsername');
		$user = $refMethod->invoke(null, $username);
		
		if ($user)
		{
			if ($user->password != $password)
			{
				$this->errorMessage = self::ERR_BAD_PASSWORD;
				return false;
			}

			if (count($user->privs) <= 0 )
			{
				$this->errorMessage = self::ERR_NO_PRIVS;
				return false;
			}
			return $user;			
		}
		return false;	
	}
	public function getErrorMessage()
	{
		return $this->errorMessage;
	}

	private function validateParent($username, $password, $lookup)
	{

        // build auth on the personnel table
        // fields user_name and password will be used
        //
        $auth = Zend_Auth::getInstance();

        $authAdapter = new Zend_Auth_Adapter_DbTable(
		    Zend_Registry::get('db'),
		    'iep_guardian',
		    'user_name',
		    'password',
		    'status = "Active"'
		);

		// pass the posted user/pass
        $authAdapter->setIdentity($username)
                    ->setCredential($password);

        try {
            // authenticate this user
            $authResult = $auth->authenticate($authAdapter);
        }
        catch (Exception $e) { 
            //var_dump($e); die;
            $this->errorMessage = $e;
            return false;
                 
        }

        // check login credentials here
        if ($authResult->isValid()) {
        	// fetch and return the user object
            $guardianObj = new Model_Table_GuardianTable();
            $guardian = $guardianObj->getParentByUsername($username);
            
			$account = new stdClass();
			$account->role	   = App_Roles::GUARDIAN;
			$account->username = $username;
			$account->password = $guardian['password'];
			$account->user = $guardian;
//			$account->privs = $privs;
			return $account;
        } else {
        	$this->errorMessage = self::ERR_BAD_PASSWORD;
        }
		return false;	
	}

	public static function check() {
		
		$auth = Zend_Auth::getInstance();
        
		if ($auth->hasIdentity()) {
            // Identity exists; get it
            return $auth->getIdentity();
       
        } else {
            $session = new Zend_Session_Namespace();
            $session->message = 'You must be logged in to do that';

            return false;
        }
	}

	public static function cleanupLogout() {
		Zend_Auth::getInstance()->clearIdentity();
		Zend_Session::writeClose();
	}
}
