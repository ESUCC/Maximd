<?php

/**
 * Form023
 *  
 * @author jlavere
 * @version 
 */
	
class Model_Table_Form023 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_023';
    protected $_primary = 'id_form_023';
    
    
    // Mike added 10-17-2017 to make ods work
    
    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    function getMostRecentIepCardState($id_student){
        $sql="select * from iep_form_023 where id_student='$id_student' and status='Final' order by date_conference DESC";
        $forms=$this->db->fetchAll($sql);
    
        if (!empty($forms)) {
    
            return $forms;
        }
        else {
    
            return null;
        }
    
    }   // end of the function
}

