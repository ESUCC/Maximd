<?php

/**
 * SearchFields Table
 *
 * @author sbennett
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_SearchFields extends Model_Table_AbstractIepForm
{
    /**
     * The default table name
     */
    protected $_name = 'search_fields';
    protected $_primary = array('search_field_id');

    /**
     * Add the defined search fields
     * @param int $searchTypeId
     * @param mixed $definition
     */
    public function addSearchFieldsForTypeId($searchTypeId, $definition) {
    	if (!empty($definition['search_fields'])) {
    		foreach ($definition['search_fields'] AS $key => $value) {
    			$row = $this->createRow();
    			$row->search_field_key = $key;
    			$row->search_field_value = $value;
    			$row->search_field_type_id = $searchTypeId;
    			$row->save();
    		}
    	}
    }
    
    /**
     * Returns an array of search fields
     * @param string $typeId
     * @return array search fields.
     */
    public function getSearchFieldsForTypeId($typeId) {
    	return $this->getAdapter()
    	->fetchPairs(
    			$this->select()
    			->from($this->_name, array('search_field_value','search_field_key'))
    			->where(
    					'search_field_type_id = ?',
    					$typeId
    			)
    	);
    }
    
    /**
     * Returns an array of search va;ies
     * @param string $typeId
     * @return array search fields.
     */
    public function getSearchValuesForTypeId($typeId) {
    	return $this->getAdapter()
    	->fetchPairs(
    			$this->select()
    			->from($this->_name, array('search_field_key','search_field_value'))
    			->where(
    					'search_field_type_id = ?',
    					$typeId
    			)
    	);
    }
}
