<?php

/**
 * StudentTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_FileUploader extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
	protected $_name = 'file_uploader';
	protected $_primary = 'id_file_uploader';
	
	public function search($id_file_uploader) {
	    $db = Zend_Registry::get('db');
		$results = $this->find($id_file_uploader)->toArray();
	    return $results[0];
	}
	
	public function save($id, $data) {
		$table = new Model_Table_FileUploader();
        $where = $table->getAdapter()->quoteInto('id_file_uploader = ?', $id);
        $table->update($data, $where);
	}

	
}
