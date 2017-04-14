<?php

/**
 * PersonnelTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_GuardianTable extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
	protected $_name = 'iep_guardian';

    public function getNameByUsername($userName) {

        $select = $this->db->select();
        $select->from('iep_guardian')->where('user_name = ?', $userName);
        $result = $select->query()->fetchAll();
        return '' . @$result[0]['name_first'] . ' ' . @$result[0]['name_last'];

    }

    function getParentByUsername($userName) {
    
        if(null == $userName)
        {
            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                // Identity exists; get it
                $userName = $auth->getIdentity();
            } else {
                return false;
            }
        }
        
        $db = Zend_Registry::get('db');

        $userName = $db->quote($userName);

        $select = $db->select()
                     ->from( array('u' => 'iep_guardian'),
                             array('*')
                           )
                     ->where( "user_name = $userName" );
                     //->order( "" );
        $result = $db->fetchAll($select);
        
        return $result[0];
    }
    
    function getGuardianById($id) {
    	$db = Zend_Registry::get('db');

        $select = $db->select()
                     ->from( array('u' => 'iep_guardian'),
                             array('*')
                           )
                     ->where( 'id_guardian = ?', $id );
                     //->order( "" );
        $result = $db->fetchAll($select);
        
        return $result[0];
    }

    function getParentNames($studentId) {
        $db = Zend_Registry::get('db');

        $result = $this->fetchAll("id_student='".$db->quote($studentId)."' and status='Active'");

        $retString = '';
        $comma = "";
        foreach($result->toArray() as $guardian) {
            $retString .= $comma . $guardian['name_first'] . " " . $guardian['name_last'];
            $comma = ", ";
        }
        return $retString;

    }
    
    function getParentInfoForStudent($studentId) {
    	$db = Zend_Registry::get('db');
    	$select = $db->select()
    	->from( array('u' => 'iep_guardian'),
    			array('*')
    	)
    	->where( 'id_student = ?', $studentId )
    	->where( 'status = ?', 'Active');
    	//->order( "" );
    	$result = $db->fetchAll($select);
    	return $result;
    }
}
