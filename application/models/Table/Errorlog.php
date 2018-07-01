<?php

/**
 * iep_district
 *  
 * @author jlavere
 * @version 
 */
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Errorlog extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'error_log';
    protected $_primary = 'id_error_log';

    static public function insertSimpleLog($code, $text) {
    	
        $eLog = new Model_Table_Errorlog();
        $data = array(
            "error_code" => $code,
		    "error_text" => serialize($text)
        );
        
        $newId = $eLog->insert($data);
    	
    }
	static public function insertLog($data) {
    	
        $eLog = new Model_Table_Errorlog();
        $data = array(
            "error_code" => $data["error_code"],
		    "error_text" => $data["error_text"],
		    "id_county" => $data["id_county"],
		    "id_district" => $data["id_district"],
		    "id_school" => $data["id_school"],
		    "id_student" => $data["id_student"],
		    "id_form" => $data["id_form"],
		    "form_table" => $data["form_table"],
		    "form_key" => $data["form_key"],
        );
        
        $newId = $eLog->insert($data);
    	
    }
}

