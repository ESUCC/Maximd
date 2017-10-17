<?php

/**
 * Form022
 *  
 * @author jlavere
 * @version 
 */
	
class Model_Table_Form022 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_022';
    protected $_primary = 'id_form_022';
    
    // Mike added 10-17-2017 in order to get the csv to work
    function getMostRecentMdtCard($id_student){
        $sql="select * from iep_form_022 where id_student='$id_student'  and status='Final' order by date_mdt DESC";
        $forms=$this->db->fetchAll($sql);
    
        if (!empty($forms)) {
            return $forms;
        }
        else {
            return null;
        }
    
    }   // end of the function
    
}

