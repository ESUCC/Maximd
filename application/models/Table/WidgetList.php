<?php

/**
 * StudentTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_WidgetList extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
	protected $_name = 'widget_list';
	protected $_primary = 'id_widget_list';
	
	public function search($id_widget_list) {
	    $db = Zend_Registry::get('db');
		$results = $this->find($id_widget_list)->toArray();
	    return $results[0];
	}
	
	public function save($id, $data) {
		$table = new Model_Table_WidgetList();
        $where = $table->getAdapter()->quoteInto('id_widget_list = ?', $id);
        return $table->update($data, $where);
	}

	public function relatedRecords($id_foreign_key, $id) {
		$table = new Model_Table_WidgetList();
		$db = Zend_Registry::get('db');
        $where = $table->getAdapter()->quoteInto('id_foreign_key = ?', $id);
	    $results = $table->fetchAll($where);

	    if($results->count() > 0 ) {
        	return $results->toArray();
        } else {
        	return false;
        }
	}
	public function insertRow($data = null) {
		$table = new $this->className ();
		$result = $table->insert ( $data);
        return $result;
    }
	public function deleteRow($id_widget_list) {
		$table = new $this->className ();
		$id_widget_list = $this->db->quote($id_widget_list);
		$result = $table->delete("id_widget_list = $id_widget_list");
        return $result;
    }
	
}
        
