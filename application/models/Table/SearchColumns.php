<?php

/**
 * SearchColumns Table
 *
 * @author sbennett
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_SearchColumns extends Model_Table_AbstractIepForm
{
    /**
     * The default table name
     */
    protected $_name = 'search_columns';
    protected $_primary = array('search_column_id');

    /**
     * Adds a search column for given type id
     * @param int $searchTypeId
     * @param mixed $definition
     */
    public function addSearchColumnsForTypeId($searchTypeId, $definition) {
    	if (!empty($definition['search_columns'])) {
    		foreach ($definition['search_columns'] AS $key => $value) {
    			$row = $this->createRow();
    			$row->search_column_type_id = $searchTypeId;
    			$row->search_column_key = $key;
    			$row->search_column_value = $value;
    			if (!empty($definition['search_columns_css_ids'][$key])) {
    				$row->search_column_css_id = $definition['search_columns_css_ids'][$key];
    			}
    			$row->save();
    		}
    	}
    }
    
    /**
     * Get the column ID for a given key
     * @param string $key
     * @return boolean / int
     */
    public function getColumnIdForKey($key) {
    	$row = $this->fetchRow($this->select()->where('search_column_key = ?', $key));
    	if (!empty($row))
    		return $row->search_column_id;
    	else 
    		return false;
    }
    
    /**
     * Get the column ID from type and value
     * @param string $typeId
     * @param string $value
     * @return boolean / int
     */
    public function getColumnIdFromTypeAndValue($typeId, $value) {
    	$row = $this->fetchRow(
    			$this->select()
    			     ->where(
    			     		'search_column_value = ?', $value
    			     )
    				 ->where(
    				 		'search_column_type_id = ?', $typeId
    				 )
    			);
    	if (!empty($row))
    		return $row->search_column_id;
    	else
    		return false;
    }
    
    /**
     * Returns an array of search columns
     * @param string $typeId
     * @return array search columns.
     */
    public function getSearchColumnsForTypeId($typeId) {
    	return $this->getAdapter()
    	->fetchPairs(
    			$this->select()
    			->from(
    					$this->_name, 
    					array(
    							'search_column_value',
    							'search_column_key'
    					)
    			)
    			->where(
    					'search_column_type_id = ?',
    					$typeId
    			)
    	);
    }
    
    /**
     * Returns an array of search column css ids
     * @param string $typeId
     * @return array search columns.
     */
    public function getSearchColumnCSSIdsForTypeId($typeId) {
    	return $this->getAdapter()
    	->fetchPairs(
    			$this->select()
    			->from(
    					$this->_name,
    					array(
    							'search_column_value',
    							'search_column_css_id'
    					)
    			)
    			->where(
    					'search_column_type_id = ?',
    					$typeId
    			)
    	);
    }
}
