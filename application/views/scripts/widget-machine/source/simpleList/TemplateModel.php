<?php

/**
 * StudentTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_{WidgetName} extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
	protected $_name = '{UnderscoreName}';
	protected $_primary = 'id_{UnderscoreName}';
	
	public function search($id_{UnderscoreName}) {
	    $db = Zend_Registry::get('db');
		$results = $this->find($id_{UnderscoreName})->toArray();
	    return $results[0];
	}
	
	public function save($id, $data) {
		$table = new Model_Table_{WidgetName}();
        $where = $table->getAdapter()->quoteInto('id_{UnderscoreName} = ?', $id);
        return $table->update($data, $where);
	}

	public function relatedRecords(${ForeignKey}, $id) {
		$table = new Model_Table_WidgetList();
		$db = Zend_Registry::get('db');
        $where = $table->getAdapter()->quoteInto('{ForeignKey} = ?', $id);
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
	public function deleteRow($id_{UnderscoreName}) {
		$table = new $this->className ();
		$id_{UnderscoreName} = $this->db->quote($id_{UnderscoreName});
		$result = $table->delete("id_{UnderscoreName} = $id_{UnderscoreName}");
        return $result;
    }
	
}
        
