<?php

/**
 * Model_Table_PrivilegeTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_PrivilegeTable extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_privileges';
    protected $_primary = 'id_privileges';
    protected $_sequence = "iep_priv_id_priv_seq";

    // 	protected $_referenceMap = array(
// 		'ContactTypes' => array(
// 			'columns' => array('contacttype_id'),
// 			'refTableClass' => 'ContactTypesTable',
// 			'refColumns'	=> array('id')
// 		)
// 	);
}
