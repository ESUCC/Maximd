<?php

/**
 * Collection
 *
 * @author jlavere
 * @version
 */

class Model_Table_CollectionItem extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'collection_item';
    protected $_primary = 'id_collection_item';
    protected $_dependentTables = array();
    protected $_referenceMap    = array(
        'Model_Table_Collection' => array(
            'columns'           => array('id_collection'),
            'refTableClass'     => 'Model_Table_Collection',
            'refColumns'        => array('id_collection')
        )
    );
}

