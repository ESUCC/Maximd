<?php

/**
 * StudentTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_WidgetFactory extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
	protected $_name = 'widget_factory';
	protected $_primary = 'id_widget_factory';
	
	public function search($id_widget_factory) {
	    $db = Zend_Registry::get('db');
		$results = $this->find($id_widget_factory)->toArray();
	    return $results[0];
	}
	
	public function save($id, $data) {
		$table = new Model_Table_WidgetFactory();
        $where = $table->getAdapter()->quoteInto('id_widget_factory = ?', $id);
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
	public function deleteRow($id_widget_factory) {
		$table = new $this->className ();
		$id_widget_factory = $this->db->quote($id_widget_factory);
		$result = $table->delete("id_widget_factory = $id_widget_factory");
        return $result;
    }
	
}
        
