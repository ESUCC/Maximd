<?php

/**
 * StudentTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_FirstWidget extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
	protected $_name = 'first_widget';
	protected $_primary = 'id_first_widget';
	
	public function search($id_first_widget) {
	    $db = Zend_Registry::get('db');
		$results = $this->find($id_first_widget)->toArray();
	    return $results[0];
	}
	
	public function save($id, $data) {
		$table = new Model_Table_FirstWidget();
        $where = $table->getAdapter()->quoteInto('id_first_widget = ?', $id);
        $table->update($data, $where);
	}

	
}
