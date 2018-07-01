<?php

/**
 * PersonnelTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_MyTemplateFormData extends Zend_Db_Table_Abstract {

	protected $_name = 'my_template_form_data';
    protected $_primary = 'id_my_template_data';


	public function getTemplates($idPersonnel, $templateType) {
		
        $db = Zend_Registry::get('db');
		$select = $db->select();
		$select
			->from($this->_name)
			->where('id_personnel = ?', $idPersonnel)
			->where('template_type = ?', $templateType)
			->order('id_my_template_data asc');
			//		Zend_Debug::dump($select, 'select');
		$results = $select->query()->fetchAll();
		return $results;
	}
    
	public function addTemplate($idPersonnel, $templateType, $data) {
		
		$submitData = array();
		$submitData['template_type'] = $templateType;
		$submitData['table_data'] = $data;
		$submitData['id_personnel'] = $idPersonnel;

		$result = $this->insert($submitData);
		return $result;
	}
	
	public function deleteTemplate($pk) {
		
		$where = $this->getAdapter()->quoteInto('id_my_template_data = ?', $pk);
		
		$result = $this->delete($where);
		return $result;
	}
	
	public function saveTemplate($idPersonnel, $templateType, $data, $pk) {
		
//		Zend_Debug::dump($pk);die();
		$submitData = array();
		$submitData['table_data'] = $data;

		$where = $this->getAdapter()->quoteInto('id_my_template_data = ?', $pk);
		
		$result = $this->update($submitData, $where);
		return $result;
	}
	
}
