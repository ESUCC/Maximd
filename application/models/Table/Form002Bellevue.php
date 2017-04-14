<?php

/**
 * Form002
 *  
 * @author jlavere
 * @version 
 */
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Form002Bellevue extends Model_Table_AbstractIepForm
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
    
    function exportForBellevue($mdt) {
        $formId=$mdt['id_form_002'];
        $formMostRecent=$mdt['timestamp_last_mod'];
        $previousFormId=$mdt['id_form_002'];
        
       // writevar($mdt['id_student'],'this is the student inside the table');
      //  writevar($formId,'this is the form id');
        $row = $this->fetchAll($this->select()
            ->where('id_student =?',$mdt['id_student'])
            ->order('timestamp_created DESC'));
            
       //writevar($row,'here we go');
       
        $num=count($row);
     //   writevar($num,'this is the number of forms besides this one');
        
        if($num>1) {
       //     writevar($row,'these are the rows');
          $t=$row[1]['disability_primary'];
          return $t;
        } 
        else {
            $t=$mdt['disability_primary'];
            return $t;
        }
       
        
    //  writevar($t,'this is the disability');
       
    }
    
}

