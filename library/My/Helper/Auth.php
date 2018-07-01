<?php
/**
 * @category   My_Helper
 * @package    My_Helper_Auth
 */
class My_Helper_Auth {
	
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
}