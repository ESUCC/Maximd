<?php

/**
 * Login
 *  
 */
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Login extends Zend_Db_Table_Abstract
{
	
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_session';
    
    static public function logIn($user, $session, $ip, $agent)
    {
        
        $db = Zend_Registry::get('db');

          $data = array(
            'timestamp_created'    => date("m/d/Y H:i:s", time()),
            'timestamp_last_mod'   => date("m/d/Y H:i:s", time()),
	    'expiration'           => time(),
            'id_session'           => $session,
            'id_user'              => $user->user['id_personnel'],
            'ip'                   => $ip,
            'platform'             => $agent,
            'value'                => '',
            'version'              => 0,
            'status'               => '',
            'token_key'            => '',
            'token_timeout'        => date("m/d/Y H:i:s", time()),
            'zfvalue'              => '',
            'token_key_non_zf'     => '',
            'note'                 => '',
            'first_id_user'        => 0
          );

         $db->insert('iep_session', $data);

    }
    
    static public function logOut($user)
    {
        
        $db = Zend_Registry::get('db');

//        $where[] = "id_log = '$id'";
//        $result = $db->update('iep_log', $data, $where);

        return false;
    }
    

    	
}
