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

    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    function dupe($document) {

        $sessUser = new Zend_Session_Namespace('user');
        $stmt = $this->db->query("SELECT dupe_mdt_zend_to_zend('$document', '$sessUser->sessIdUser')");
        if($result = $stmt->fetchAll()) {
            return $result[0]['dupe_mdt_zend_to_zend'];
        } else {
            return false;
        }
    }
    
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
