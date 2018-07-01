<?php

class Model_Table_IepSessionZend extends Zend_Db_Table_Abstract {
 
    protected $_name = 'iep_session_zend';
    protected $_primary = 'id_session';
    
//    static public function getSessionByToken($token)
//    {
//
//        $db = Zend_Registry::get('db');
//        $select = $db->select('*')
//                     ->from( 'iep_session_zend' )
//                     ->where( "status = 'Active' and token_key = ?", $token );
//    
//        $result = $db->fetchAll($select);
//        
//        return $result[0];
//        
//    }
//
//    static public function getSessionBySessId($sessId)
//    {
//
//        $db = Zend_Registry::get('db');
//        $select = $db->select('*')
//                     ->from( 'iep_session_zend' )
//                     ->where( "status = 'Active' and id_session = ?", $sessId );
//    
//        $result = $db->fetchAll($select);
//        
//        if(count($result) > 0) {
//            return $result[0];
//        } else {
//            return false;
//        }
//        
//    }
    
    static public function getSessionRecordBySessId($sessId)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select('*')
                     ->from( 'iep_session_zend' )
                     ->where( "id_session = ?", $sessId );
    
        $result = $db->fetchAll($select);
        
        if(count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
        
    }

    static public function hasSiteAccess($sessId)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select('exists')
                     ->from( 'iep_session_zend' )
                     ->where( "status = 'Active' and siteaccessgranted = true and id_session = ?", $sessId );
    
        $result = $db->fetchAll($select);
        Zend_Debug::dump(count($result));
        if(count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
        
    }
	static public function expireSessionRecord($id)
	{
        try
        {
        	$data = array(
        				'status' => 'Expired', 
        				'siteaccessgranted' => false, 
        				'note' => 'expireSessionRecord'
        	);
            $db = Zend_Registry::get('db');
            $where[] = "id_session = '$id'";
            $result = $db->update('iep_session_zend', $data, $where);
            return $result;
        }
        catch (Zend_Db_Statement_Exception $e) {
            // generate error
            echo "error: $e";
        }
        return false;
		
	}   
}