<?php

/**
 * Form028 model
 *
 */
class Model_Form028 extends Model_AbstractForm
{

	protected $_subformTypes = array(
			'Parents' => 'parents',
			'MeetingParticipation' => 'meeting_participation'
	);
	
	protected $_subformTitles = array(
			'Parents' => 'Parents',
			'MeetingParticipation' => 'Meeting Participation'
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
				$modelName		= 'Model_Table_Form028'.$key;
				$select 		= $this->db_form->select()->where("status != 'Deleted'")->order('timestamp_created ASC');
				$subformResults = $this->db_form->findDependentRowset($modelName, 'Model_Table_Form028', $select);
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
				$this->subformIndexToModel[$value] = "Model_Table_Form028".$key;
			}
		}
		
		return $this->db_form_data;
	}	
}
