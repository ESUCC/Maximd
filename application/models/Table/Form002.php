<?php

/**
 * Form002
 *  
 * @author jlavere
 * @version 
 */
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Form002 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_002';
    protected $_primary = 'id_form_002';
    protected $_dependentTables = array(
							'Model_Table_Form002TeamMember', 
    						'Model_Table_Form002SupplementalForm',
    );

    function dupe($document) {

        $sessUser = new Zend_Session_Namespace('user');
        $stmt = $this->db->query("SELECT dupe_mdt_zend_to_zend('$document', '$sessUser->sessIdUser')");
        if($result = $stmt->fetchAll()) {
            return $result[0]['dupe_mdt_zend_to_zend'];
        } else {
            return false;
        }
    }
    
    // Mike added this 10-17-2017 in order to get the csv to work
    function getMostRecentMDT($id_student){
        $sql="select * from iep_form_002 where id_student='$id_student' and status='Final' order by date_mdt DESC";
        $forms=$this->db->fetchAll($sql);
    
        if (!empty($forms)) {
            return $forms;
        }
        else {
            return null;
        }
    
    }   // end of the function
    
}

