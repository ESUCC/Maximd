<?php

/**
 * SearchFormats Table
 *
 * @author sbennett
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_SearchFormats extends Model_Table_AbstractIepForm
{
    /**
     * The default table name
     */
    protected $_name = 'search_formats';
    protected $_primary = array('search_format_id');
    
    public function addNewUserSearchFormat($userId, $typeId, $formatName) {
    	$row = $this->createRow();
    	$row->search_format_type_id = $typeId;
    	$row->search_format_name = $formatName;
    	$row->search_format_custom = '1';
    	$row->search_format_user_id = $userId;
    	$row->save();
    	return $row->toArray();
    }

    public function addSearchFormatsForTypeId(
    		$searchTypeId, $definition, 
    		Model_Table_SearchFormatColumns $searchFormatColumnsModel,
    		Model_Table_SearchColumns $searchColumnsModel
    		) {
    	if (!empty($definition['search_formats'])) {
    		foreach ($definition['search_formats'] AS $key => $value) {
    			$row = $this->createRow();
    			$row->search_format_type_id = $searchTypeId;
    			$row->search_format_name = $key;
    			$row->save();
    			
    			/*
    			 * Assign default columns to column format.
    			 */
    			foreach ($value AS $order => $val) {
    				$searchFormatColumnsModel->setColumnForFormat(
    						$row->search_format_id,
    						$searchColumnsModel->getColumnIdForKey($val),
    						$order
    				);
    			}
    		}
    	}
    }
    
    /**
     * Returns an array of search Formats
     * @param string $typeId
     * @return array search formats.
     */
    public function getSearchFormatsForTypeId($typeId) {
    	return $this->getAdapter()
    	            ->fetchPairs(
    	            		$this->select()
    	            		     ->from(
    	            		     		$this->_name, 
    	            		     		array(
    	            		     				'search_format_id',
    	            		     				'search_format_name'
    	            		     		)
    	            		     )
    	            		     ->where(
    	            		     		'search_format_type_id = ?', 
    	            		     		$typeId
    	            		     )
    	            			 ->where(
    	            			 		'search_format_custom IS NULL'
    	            			 )
    	            		);
    }
    
    /**
     * Returns the first non custom search format for a given type
     * @param string $typeId
     * @return array search formats.
     */
    public function getDefaultSearchFormatForTypeId($typeId) {
    	$row = $this->fetchRow(
    			$this->select()
    			->from(
    					$this->_name,
    					array(
    							'search_format_id'
    					)
    			)
    			->where(
    					'search_format_type_id = ?',
    					$typeId
    			)
    			->where(
    					'search_format_custom IS NULL'
    			)
    	);
    	if (!empty($row)) {
    		return $row->search_format_id;
    	} 
    	return false;
    }
    
    /**
     * Returns an array of search Formats
     * @param int $typeId
     * @param int $userId
     * @return array search formats.
     */
    public function getSearchFormatsForTypeIdAndUser($typeId, $userId) {
    	return $this->getAdapter()
    	->fetchPairs(
    			$this->select()
    			->from(
    					$this->_name,
    					array(
    							'search_format_id',
    							'search_format_name'
    					)
    			)
    			->where(
    					'search_format_type_id = ?',
    					$typeId
    			)
    			->where(
    					'search_format_custom = 1'
    			)
    			->where(
    					'search_format_user_id = ?',
    					$userId
    		    )
    	);
    }
}
