<?php

/**
 * StudentTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_WidgetMachine extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
	protected $_name = 'widget_machine';
	protected $_primary = 'id_widget_machine';
	
	public function save($id, $data) {
		$table = new Model_Table_StudentSearch();
        $where = $table->getAdapter()->quoteInto('id_widget_machine = ?', $id);
        $saveData = array(
			'name'=> $data['name']        
        );
//        Zend_Debug::dump($saveData);die();
        $table->update($saveData, $where);
	}

	public function mySearches($id_personnel, $prefix) {
	    $db = Zend_Registry::get('db');
	    
	    $select = $db->select()
	                 ->from( array('wm'=>$this->_name),
			            array(
			                '*',
			            )
	                 );
//	                ->where("ss.id_personnel = '$id_personnel'" );
		$results = $db->fetchAll($select);
	    return $results;
	    
	}
	public function getSearch($widget_machine, $prefix) {
		$results = $this->find($widget_machine)->toArray();
	    return $results[0];
	}
	/*
     * put a prefix in front of each key
     */
	function array_add_prefix($array, $prefix, $startingIndex = 1, $addUnderscore = true) {
		$retArr = array();
		foreach ($array as $k => $v) {
			if($addUnderscore) {
				$retArr[$prefix.'_'.$startingIndex] = $v;
			} else {
		   		$retArr[$prefix.$startingIndex] = $v;
			}
			$startingIndex++;
		}
//		$retArr[$prefix.'_count'] = $startingIndex-1; 
		return $retArr;
	}
	
}
