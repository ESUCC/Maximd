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
    
}

