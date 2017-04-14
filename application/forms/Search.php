<?php
class Form_Search extends Zend_Form {
	
	public function init()
	{
		$this->addSearchField('searchFieldTemplate0', 'searchFieldTemplate');
		$this->getElement('searchFieldTemplate0')->setAttrib('class', 'searchTemplate');
		$this->addSearchValue('searchValueTemplate0', 'searchValueTemplate');
		$this->getElement('searchValueTemplate0')->setAttrib('class', 'searchTemplate');
		
		$this->searchType = new App_Form_Element_SearchSelect(
				'searchType',
				array(
						'label' => 'Type:',
						'multiOptions' => array(
								'AND' => 'AND',
								'OR' => 'OR'
						)
				)
		);
		
		$this->format = new App_Form_Element_SearchSelect(
				'format',
				array(
						'label' => 'Format:',
						'multiOptions' => array(
								'School List' => 'School List',
								'Phonebook' => 'Phonebook',
								'MDT/IEP Report' => 'MDT/IEP Report'
						)
				)
		);
		
		$this->searchStatus = new App_Form_Element_SearchSelect(
				'searchStatus',
				array(
						'label' => 'Status:',
						'multiOptions' => array(
								'Active' => 'Active',
								'All' => 'All',
								'Inactive' => 'Inactive',
								'Never Qualified' => 'Never Qualified',
								'No Longer Qualifies' => 'No Longer Qualifies',
								'Transferred to Non-SRS District' => 'Transferred to Non-SRS District'
						)
				)
		);
		
		$this->maxRecs = new App_Form_Element_SearchSelect(
				'maxRecs',
				array(
						'label' => 'Records Per Page:',
						'multiOptions' => array(
								'5' => '5',
								'10' => '10',
								'15' => '15',
								'25' => '25',
								'50' => '50',
								'75' => '75',
								'100' => '100',
						)
				)
		);
		
		$this->setDecorators(array(array('ViewScript',
				array('viewScript' =>
						'student/student-search-form.phtml'))));
	}
	
	public function addSearchField($fieldName, $belongsTo)
	{
		$this->$fieldName = new App_Form_Element_SearchSelect($fieldName);
		$this->$fieldName->addMultiOptions(
				array(
						'multiOptions' => array(
								'' => '-- Select Field --',
								'name_first' => 'Student First Name',
								'name_last' => 'Student Last Name',
								'id_student' => 'Student Id (SRS)',
								'id_student_local' => 'Student Id (District)',
								's.unique_id_state' => 'NSSRS ID#',
								'get_name_first(id_case_mgr)' => 'Case Manager First Name',
								'get_name_last(id_case_mgr)' => 'Case Manager Last Name',
								'get_name_school(id_county,id_district,id_school)' => 'School Name',
								'get_name_district(id_county,id_district)' => 'District Name',
								'pub_school_student' => 'Public School Student (T/F)',
								'onteam' => 'Student Team (ID personnel)',
								'isCM' => 'Student CM (ID personnel)',
								'isSC' => 'Student SC (ID personnel)',
								'isEICM' => 'Student EI CM (ID personnel)',
								's.grade' => 'Grade',
								's.gradegreaterthan' => 'Grade Greater Than',
								's.gradelessthan' => 'Grade Less Than',
								's.alternate_assessment' => 'Alternate Assessment (Y/N)',
								'case_load_first_name' => 'Case Load First Name',
								'case_load_last_name' => 'Case Load Last Name',
								'team_member_first_name' => 'Team Member First Name',
								'team_member_last_name' => 'Team Member Last Name',
						)
				)
		)->setBelongsTo($belongsTo);
	}
	
	public function addSearchValue($fieldName, $belongsTo)
	{
		$this->$fieldName = new App_Form_Element_SearchText($fieldName);
		$this->$fieldName->setBelongsTo($belongsTo);
	}
}