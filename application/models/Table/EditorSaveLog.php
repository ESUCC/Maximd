<?php

/**
 * Form001
 *  
 * @author jlavere
 * @version 
 */
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_EditorSaveLog extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'editor_save_log';
    protected $_primary = 'id_editor_save_log';

    
    public function getHistory($pkName, $pk, $fieldName, $limit=10) {
    	
        $table = new $this->className();
        $select = $table->select(array('field_value'))            
            ->where( 'form_number = ?', $pkName )
            ->where( 'id_form = ?', $pk )
            ->where( "(field_name = '$fieldName' OR field_name ='$fieldName-Editor')" )
            ->order( 'timestamp_created desc' )
            ->limit( $limit );
//         echo $select;
//         die();
		return $table->fetchAll($select);
    }
}

