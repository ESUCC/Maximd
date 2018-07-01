<?php
class Model_LimitedRollout {
    
	/**
	 * Limited Districts 
	 * @var array
	 */
	protected $limitedRollout;
	
	/**
	 * Iep Student Model 
	 * @var Model_Table_IepStudent;
	 */
	protected $iepStudentTable;
	
	/**
	 * __construct()
	 */
	public function __construct($limitedRollout, Model_Table_IepStudent $iepStudentTable) {
		$this->setlimitedRollout($limitedRollout);
		$this->setIepStudentTable($iepStudentTable);
	}
	
	/**
	 * Student Is In Limited Rollout District For Resource
	 * @param int $studentId
	 * @param string $resource
	 * @return boolean
	 */
	public function studentIsInLimitedRolloutDistrictForResource($studentId,$resource,$districtId = false) {
		
		if (empty($districtId)) {
			$districtId = $this->getIepStudentTable()->getDistrictForStudentId($studentId);
		}
		
		if (in_array($districtId, $this->limitedRollout[$resource]['districts'])) {
			return true;
		}
		
		return false;
	}
		
	/**
	 * set Limited Rollout
	 * @param array $limitedRollout
	 */
	public function setlimitedRollout($limitedRollout) {
		$this->limitedRollout = $limitedRollout;
	}
	
	/**
	 * Set iep Student Table
	 * @param unknown $iepStudentTable
	 */
	public function setIepStudentTable($iepStudentTable) {
		$this->iepStudentTable = $iepStudentTable;
	}
	
	/**
	 * get Iep Student Table
	 * @return Model_Table_IepStudent;
	 */
	public function getIepStudentTable() {
		return $this->iepStudentTable;
	}
}