<?php

/**
 * SearchTypes Table
 *
 * @author sbennett
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_SearchTypes extends Model_Table_AbstractIepForm
{
    /**
     * The default table name
     */
    protected $_name = 'search_types';
    protected $_primary = array('search_type_id');
    protected $_row;
    
    /**
     * Add new search type row
     * @param string $method
     * @param mixed $definition
     */
    public function addSearchType($method, $definition) {
    	$this->_row = $this->createRow();
    	$this->_row->search_type_name = $method;
    	$this->_row->search_type_display_name = $definition['display_name'];
    	$this->_row->save();
    }
    
    /**
     * Check to see if the search type exists
     * @return boolean
     */
    public function isSearchType() {
    	if (empty($this->_row))
    		return false;
    	else
    		return true;
    }
    
    /**
     * Set the row for the given search type
     * @param string $type
     */
    public function setRowForType($type) {
    	$this->_row = $this->fetchRow(
    			$this->select()->where('search_type_name = ?', $type)
    	);
    }
    
    /**
     * Set the row for the given search type ID
     * @param string $type
     */
    public function setRowForTypeId($typeId) {
    	$this->_row = $this->fetchRow(
    			$this->select()->where('search_type_id = ?', $typeId)
    	);
    }
    
    /**
     * Return the row ID of current search type.
     * @return int
     */
    public function getRowId() {
    	return $this->_row->search_type_id;
    }
    
    /**
     * Return the display name of current search type.
     * @return int
     */
    public function getDisplayName() {
    	return $this->_row->search_type_name;
    }
}
