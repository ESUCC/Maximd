<?php

class Model_Table_IepSession extends Zend_Db_Table_Abstract {
 
    protected $_name = 'iep_session';
    protected $_primary = 'id_session';
    
    
    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    static public function getSessionByToken($token)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select('*')
                     ->from( 'iep_session' )
//                     ->where( "status = 'Active' and token_key = ?", $token );
                     ->where( "status = 'Active'")
                     ->where( "expiration > ?", time())
                     ->where( "token_key = ?", $token );
                     
        $result = $db->fetchAll($select);
        if($result) {
	        return $result[0];
        } else {
        	return false;
        }
        
    }

    static public function getSessionBySessId($sessId)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select('*')
                     ->from( 'iep_session' )
                     ->where( "status = 'Active' and id_session = ?", $sessId );
    
        $result = $db->fetchAll($select);
        
        if(count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
        
    }
    
    static public function getSessionRecordBySessId($sessId)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select('*')
                     ->from( 'iep_session' )
                     ->where( "id_session = ?", $sessId );
    
        $result = $db->fetchAll($select);
        
        if(count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
        
    }

    static public function exists($sessId)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select('exists')
                     ->from( 'iep_session' )
                     ->where( "status = 'Active' and id_session = ?", $sessId );
    
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
        	$data = array('status' => 'Expired', 'note' => 'expireSessionRecord');
            $db = Zend_Registry::get('db');
            $where[] = "id_session = '$id'";
            $result = $db->update('iep_session', $data, $where);
            return $result;
        }
        catch (Zend_Db_Statement_Exception $e) {
            // generate error
            echo "error: $e";
        }
        return false;
		
	}   
}