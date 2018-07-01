<?php
class Form_SearchStudent extends Zend_Form {
	
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
								'MDT/IEP Report' => 'MDT/IEP Report',
								'Mailing Label Data' => 'Mailing Label Data'
						)
				)
		);
		
		$this->searchStatus = new App_Form_Element_SearchRadio(
				'searchStatus',
				array(
						'label' => 'Status:',
						'multiOptions' => array(
								'Active' => 'Show only Active Students',
								'All' => 'Show ALL Students',
						)
				)
		);
		
		$this->searchFilter = new App_Form_Element_SearchSelect(
				'searchFilter',
				array(
						'label' => 'Filter:',
						'multiOptions' => array(
								'None' => 'None',
								'Missing NSSRS#' => 'Missing NSSRS#',
								'CM Only' => 'CM Only',
								'Team Member Only' => 'Team Member Only',
						)
				)
		);
		
		$this->searchStatus->removeDecorator('label');
		$this->searchStatus->setSeparator(' ');
		$this->searchStatus->setValue('Active');
		
		/*
		 * Removed search status
		 * 
		 * 'Inactive' => 'Inactive',
			'Never Qualified' => 'Never Qualified',
			'No Longer Qualifies' => 'No Longer Qualifies',
			'Transferred to Non-SRS Student' => 'Transferred to Non-SRS Student'
		 */
		
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
		$this->maxRecs->setValue('25');
		
		for ($i=0;$i<=5;$i++) {
		    $field = 'formatColumn'.$i;
		    $this->$field = new App_Form_Element_SearchSelect(
		            $field,
		            array(
		                    'label' => "Column " . ($i+1) . ":",
		                    'multiOptions' => array(
		                            '' => '-- Select Column --',
		                            'id_student' => 'SRS Student ID',
		                            'name_full' => 'Name',
		                            'name_county' => 'County',
		                            'name_district' => 'District',
		                            'name_school' => 'School',
		                            'role' => 'User Role',
		                            'manager' => 'Case Manager',
		                            'address' => 'Address',
		                            'phone' => 'Phone',
		                            'iep' => 'IEP/IFSP* Due Date',
		                            'mdt' => 'MDT/Det. Notice* Due Date',
		                            'primary_disability' => 'Primary Disability',
		                            'dob' => 'Date of Birth',
		                            'age' => 'Age',
		                    )
		            )
		    );
		    //$this->$field->setAttrib('disabled', 'disabled');
		
		    switch ($i)
		    {
		        case '0':
		            $this->$field->setValue('id_student');
		            break;
		        case '1':
		            $this->$field->setValue('name_full');
		            break;
		        case '2':
		            $this->$field->setValue('name_county');
		            break;
		        case '3':
		            $this->$field->setValue('name_district');
		            break;
		        case '4':
		            $this->$field->setValue('name_school');
		            break;
		        case '5':
		            $this->$field->setValue('role');
		            break;
		    }
		}
		
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
								'ethnic_group' => 'Ethnic Group',
								'primary_language' => 'Primary Language',
								'ward' => 'Ward of State (T/F)',
								'ell_student' => 'Ell Student? (T/F)',
								'primaryOrRelatedService' => 'Service',
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