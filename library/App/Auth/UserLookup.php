<?php

class App_Auth_UserLookup 
{
	private static $users = array(
		"john"	=> "pa$$",
		"emily"	=> "pa$$",
		"robert"=> "pa$$",
		"eric"	=> "pa$$"
	);
	
	public static function findByUsername($username)
	{
		$personnelService = new App_Service_PersonnelService();
		$user = $personnelService->getUser($username);
		$privs = $personnelService->getPrivilegesByUser($user['id_personnel']);
		
		if ($user)
		{
			
			$account = new stdClass();
			$account->role	   = App_Roles::USER;
			$account->username = $username;
			$account->password = $user['password'];
//			$account->description = "Free account";
			$account->user = $user;
			$account->privs = $privs;
			return $account;
		}
		return false;
	}
	
}
