<?
//require_once(APPLICATION_PATH . '/../library/My/Classes/privCheck.php');

class user_session_manager {
	
	
	static public function getSessPrivCheckObj()
	{
		$sessUser = new Zend_Session_Namespace('user');
//		new My_Classes_privCheck(array());
//		Zend_debug::dump($sessUser->sessPrivCheckObjSerialized);
		return unserialize($sessUser->sessPrivCheckObjSerialized);
	}
	
	
}