<?php

class App_Debug extends Zend_Debug 
{

	
    public static function dump($var, $label=null, $echo=true)
    {
		$sess = new Zend_Session_Namespace('user');
		$conf = Zend_Registry::get('config');
		
		$userArr = App_Debug::buildUserArray($conf->debug->users);
		if(array_search($sess->id_personnel, $userArr) !== false) {
			Zend_Debug::dump($var, $label, $echo);
		}
		return null;
    }
    
    protected static function buildUserArray($userString) {
    	if(substr_count($userString, ',') > 0) {
    		$retArr = explode(',', $userString);
    		array_map('trim', $retArr);
    		return $retArr;
    	}
    	return array(trim($userString));
    }

    public static function adminDebugConsoleEnabled()
    {
		$sess = new Zend_Session_Namespace('user');
		$conf = Zend_Registry::get('config');
		
		$userArr = App_Debug::buildUserArray($conf->debug->users);
		if(array_search($sess->id_personnel, $userArr) !== false) {
			return true;
		}
		return false;
    }    
    
}
