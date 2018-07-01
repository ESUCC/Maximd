<?php

/**
 * SearchFormatColumns Table
 *
 * @author sbennett
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_SearchFormatColumns extends Model_Table_AbstractIepForm
{
    /**
     * The default table name
     */
    protected $_name = 'search_format_columns';
    protected $_primary = array('search_format_column_id');

    /**
     * Set the column value for Format
     * @param int $formatId
     * @param int $columnId
     * @param int $order
     */
    public function setColumnForFormat($formatId, $columnId, $order) {
    	if (!empty($formatId) && !empty($columnId)) {
	    	$row = $this->createRow();
	    	$row->search_format_column_format_id = $formatId;
	    	$row->search_format_column_column_id = $columnId;
	    	$row->search_format_column_order = $order;
	    	$row->save();
    	}
    }
    
    /**
     * Returns the columns set for a format.
     * @param int $formatId
     * @return multitype:string NULL
     */
    public function getColumnsForFormat($formatId) {
    	$columns = array(
    		'formatColumn0' => '',
    		'formatColumn1' => '',
    		'formatColumn2' => '',
    		'formatColumn3' => '',
    		'formatColumn4' => '',
    		'formatColumn5' => ''
    	);
		$rows = $this->fetchAll(
				$this->select()
					 ->from(array('search_format_columns'))
					 ->join(
					 		array('search_columns'), 
					 		'search_column_id = search_format_column_column_id', 
					 		array('search_column_value')
					 )
				     ->where(
						'search_format_column_format_id = ?', 
						$formatId
					 )
				     ->order('search_format_column_order ASC')->setIntegrityCheck(false)
		)->toArray();
		if (!empty($rows)) {
			for ($i=0;$i<count($rows);$i++) {
				$columns['formatColumn'.$i] = $rows[$i]['search_column_value'];
			}
		}
		return $columns;
    }
    
    /**
     * Update Column For Format
     * @param int $formatId
     * @param int $columnId
     * @param string $order
     */
    public function updateColumnForFormat($formatId, $columnId, $order) {
    	$row = $this->fetchRow(
    			$this->select()
    			     ->where(
    						'search_format_column_format_id = ?', 
    						$formatId
    			     )
    				 ->where('search_format_column_order = ?', $order)
    			);
		if (!empty($row)) {
			$row->search_format_column_column_id = $columnId;
			$row->save();
		} else {
			$row = $this->createRow();
			$row->search_format_column_format_id = $formatId;
			$row->search_format_column_column_id = $columnId;
			$row->search_format_column_order = $order;
			$row->save();
		}
    }
}
