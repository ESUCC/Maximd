<?php

/**
 * Form032 model
 *
 */
class Model_Form032 extends Model_AbstractForm
{

	protected $_subformTypes = array(
			'OtherAttendee' => 'other_attendee',
	);
	
	protected $_subformTitles = array(
			'OtherAttendee' => 'Other Attendee',
	);
	
    /**
     * $subformIndexToModel - array
     *
     * @var array
     */
	var $subformIndexToModel = array();
	
    /**
     * $db_form_data - array
     *
     * @var array
     */
	var $db_form_data = array();
	
	public function find($id, $accessMode = "view", $page =1, $versionNumber = 1, $checkout = 0)
	{
		if(false === parent::buildDbForm($id, $accessMode, $page, $versionNumber, $checkout))
		{
			return false;
		}
				
		// build sub forms
		if('all' == $page || 1 == $page)
		{
			foreach ($this->_subformTypes AS $key => $value) {
				$modelName		= 'Model_Table_Form032'.$key;
				$select 		= $this->db_form->select()->where("status != 'Deleted'")->order('timestamp_created ASC');
				$subformResults = $this->db_form->findDependentRowset($modelName, 'Model_Table_Form032', $select);
				$this->db_form_data[$value]['count'] = count($subformResults);
				$this->db_form_data[$value]['subformTitle'] = '<span>'.$this->_subformTitles[$key].'</span>';
				$rownum = 1;
				//			Zend_Debug::dump($teamMember);
				foreach($subformResults as $db_row)
				{
					$this->db_form_data[$value.'_'.$rownum] = $db_row->toArray();
					$this->db_form_data[$value.'_'.$rownum]['rownumber'] = $rownum;
					$rownum++;
				}
				$this->subformIndexToModel[$value] = "Model_Table_Form032".$key;
			}
		}
		
		if('all' == $page || 2 == $page)
		{
			$modelName		= 'Model_Table_Form032ContactAttempts';
			$select 		= $this->db_form->select()->where("status != 'Deleted'")->order('timestamp_created ASC');
			$transcriptResults = $this->db_form->findDependentRowset($modelName, 'Model_Table_Form032', $select);
			$this->db_form_data['contact_attempts']['count'] = count($transcriptResults);
			$this->db_form_data['contact_attempts']['subformTitle'] = '<span>Contact Attempts</span>';
			$rownum = 1;
			//			Zend_Debug::dump($teamMember);
			foreach($transcriptResults as $db_row)
			{
				$this->db_form_data['contact_attempts_'.$rownum] = $db_row->toArray();
				$this->db_form_data['contact_attempts_'.$rownum]['rownumber'] = $rownum;
				$rownum++;
			}
			$this->subformIndexToModel['contact_attempts'] = "Model_Table_Form032ContactAttempts";
		}
		// build sub forms		
				
//		echo "<PRE>";
//		print_r($this->db_form_data);
//		echo $select;
		return $this->db_form_data;
	}	
}
