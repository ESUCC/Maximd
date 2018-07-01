<?php

/**
 * student search row
 * 
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_StudentSearchRow extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
	protected $_name = 'student_search_rows';
	protected $_primary = 'id_student_search_rows';
	
	public function insertRow($data = null) {
		
		$table = new $this->className ();
		$result = $table->insert ( $data);
        return $result;
    }
	public function deleteRow($id_student_search_rows) {
		$table = new $this->className ();
		$id_student_search_rows = $this->db->quote($id_student_search_rows);
		$result = $table->delete("id_student_search_rows = $id_student_search_rows");
        return $result;
    }
		
}
