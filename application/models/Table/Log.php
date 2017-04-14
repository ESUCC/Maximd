<?php

/**
 * Log
 *  
 * @author mthomson
 * @version 
 */
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Log extends Zend_Db_Table_Abstract
{
	
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_log';
    protected $_primary = 'id_log';
    protected $_sequence = "iep_log_id_log_seq";
    
	static public function save($id, $data)
    {
        unset($data['submit']);
        unset($data['id_log']);
        
        try
        {
            $db = Zend_Registry::get('db');
            $where[] = "id_log = '$id'";
            $result = $db->update('iep_log', $data, $where);
            return $result;
        }
        catch (Zend_Db_Statement_Exception $e) {
            // generate error
            echo "error: $e";
        }
        return false;
    }
    
    public function insertLog($data = null) {
    	unset($data['submit']);
    	unset($data[$this->_primary]);
    	try {
	    	$class = get_class($this);
	    	$table = new $class;
	    	$result = $table->insert($data);
    	}
    	catch (Zend_Db_Statement_Exception $e) {
            // generate error
            echo "error: $e";
        }
    	return $result;
    	
    }

    
	function writeLog($idRelRecord, $type, $tableName, $errorId, $errorMsg, $id_student = "", $page = "") {
			
//		global $sessIdUser, $sessUserClass, $LOGGING_ON, $sessPrivCheckObj;
		
		$sessUser = new Zend_Session_Namespace ( 'user' );
		
		if("" != $id_student) {
			$student_auth = new App_Auth_StudentAuthenticator ();
			$formAccess = $student_auth->validateStudentAccess ( $id_student, $sessUser );
//			Zend_Debug::dump($formAccess->description);
			// do not log actions of master users
			// sl 2003-08-30 edited to just use session priv check object
			if ('Admin' == $formAccess->description) {
			    return true;
			}
		}		
		$data = array(
			'id_rel_record' => $idRelRecord, 
			'id_student' => $id_student, 
			'type' => $type, 
			'table_name' => $tableName, 
			'page' => $page,
			'id_guardian' => $sessUser->parent ? $sessUser->sessIdUser : 0,
			'id_author' => $sessUser->parent ? 0 : $sessUser->sessIdUser,
		);
		
    	try {
	    	$class = get_class($this);
	    	$table = new $class;
	    	$result = $table->insert($data);
	    	return true;
    	}
    	catch (Zend_Db_Statement_Exception $e) {
            // generate error
            //echo "error: $e";
            return false;
        }
	}
	
	public function getLogsForDocument($documentId, $formNum)
	{
		return $this->fetchAll(
			$this->select()
				 ->from(array('l' => 'iep_log'),
				 		array('id_author' => 'get_name_personnel(id_author)',
				 			  'id_gaurdian' => 'get_name_guardian(id_guardian)',
				 			  'type',
				 			  'page',
				 			  'timestamp_created'))
				 ->where('id_rel_record = ?', $documentId)
				 ->where('table_name = ?', $formNum)
				 ->order('timestamp_created DESC')
		)->toArray();
	}
    	
}
