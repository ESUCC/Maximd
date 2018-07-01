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
        $table->update($data, $where);
	}

	
}
