<?php
/**
 * Helper class for Search
 * @author sbennett
 *
 */
class My_SearchHelper 
{
	/**
	 * Returns an array definition to populate student search 
	 * form values.
	 * 
	 * @param string $type
	 */
	public static function getDefenitionForSearchType($type) {
		switch($type) {
			case 'student':
				return array(
					'display_name' => 'Search Students',
					'search_fields' => array(
						'Student First Name' => 'name_first',
						'Student Last Name' => 'name_last',
						'Student Id (SRS)' => 'id_student',
						'Student Id (District)' => 'id_student_local',
						'NSSRS ID#' => 's.unique_id_state',
						'Case Manager First Name' => 'get_name_first(id_case_mgr)',
						'Case Manager Last Name' => 'get_name_last(id_case_mgr)',
						'School Name' => 'get_name_school(id_county,id_district,id_school)',
						'District Name' => 'get_name_district(id_county,id_district)',
						'Public School Student (T/F)' => 'pub_school_student',
						'Student Team (ID personnel)' => 'onteam',
						'Student CM (ID personnel)' => 'isCM',
						'Student SC (ID personnel)' => 'isSC',
						'Student EI CM (ID personnel)' => 'isEICM',
						'Grade' => 's.grade',
						'Grade Greater Than' => 's.gradegreaterthan',
						'Grade Less Than' => 's.gradelessthan',
						'Alternate Assessment (Y/N)' => 's.alternate_assessment',
						'Case Load First Name' => 'case_load_first_name',
						'Case Load Last Name' => 'case_load_last_name',
						'Team Member First Name' => 'team_member_first_name',
						'Team Member Last Name' => 'team_member_last_name',
					),
					'search_columns' => array(
						'SRS Student ID' => 'id_student',
						'Name' => 'name_full',
						'County' => 'name_county',
						'District' => 'name_district',
						'School' => 'name_school',
						'User Role' => 'role',
						'Case Manager' => 'name_case_mgr',
						'Address' => 'address',
						'Phone' => 'phone',
						'IEP/IFSP* Due Date' => 'iep',
						'MDT/Det. Notice* Due Date' => 'mdt',
						'Primary Disability' => 'primary_disability',
						'Date of Birth' => 'dob',
						'Age' => 'age',
					),
					'search_columns_css_ids' => array(
						'SRS Student ID' => 'cssid',
						'Name' => 'cssid',
						'County' => 'cssid',
						'District' => 'cssid',
						'School' => 'cssid',
						'User Role' => 'cssid',
						'Case Manager' => 'cssid',
						'Address' => 'cssid',
						'Phone' => 'cssid',
						'IEP/IFSP* Due Date' => 'cssid',
						'MDT/Det. Notice* Due Date' => 'cssid',
						'Primary Disability' => 'cssid',
						'Date of Birth' => 'cssid',
						'Age' => 'cssid',
					),
					'search_formats' => array(
						'School List' => array(
							1 => 'SRS Student ID',
							2 => 'Name',
							3 => 'County',
							4 => 'District',
							5 => 'School',
							6 => 'User Role',
						),
						'Phonebook' => array(
								1 => 'Name',
								2 => 'Case Manager',
								3 => 'Address',
								4 => 'Phone',
								5 => '',
								6 => '',
						),
						'MDT/IEP Report' => array(
								1 => 'SRS Student ID',
								2 => 'Name',
								3 => 'IEP/IFSP* Due Date',
								4 => 'Phone',
								5 => 'MDT/Det. Notice* Due Date',
								6 => '',
						),
					),
				);
				break;
			case 'district':
				return array(
					'display_name' => 'Search Districts',
					'search_fields' => array(
						'District Name' => 'name_district',
						'County' => 'get_name_county(id_county)',
						'Status' => 'status',
					),
					'search_columns' => array(
						'District Name' => 'name_district',
						'County' => 'name_county',
					),
					'search_columns_css_ids' => array(
						'District Name' => 'default-result-width',
						'County' => 'default-result-width',
					),
					'search_formats' => array(
						'District List' => array(
							1 => 'County',
							2 => 'District Name',
						),
					),
				);
				break;
			case 'personnel':
				return array(
				'display_name' => 'Search Personnel',
				'search_fields' => array(
					'First Name' => 'name_first',
					'Last Name' => 'name_last',
					'County' => 'county',
					'District' => 'district',
					'School' => 'school',
					'Status' => 'status',
				),
				'search_columns' => array(
					'Name' => 'name_last_first',
					'ID' => 'id_personnel',
					'Address' => 'address',
					'Phone' => 'phone_work',
					'Email' => 'email_address',
					'School' => 'school_name',
					'Class' => 'cdx',
					'Status' => 'status',
				),
				'search_columns_css_ids' => array(
					'Name' => 'searchName',
					'ID' => 'searchId',
					'Address' => 'searchAddress',
					'Phone' => 'searchPhone',
					'Email' => 'searchName',
					'School' => 'searchCounty',
					'Class' => 'searchCounty',
					'Status' => 'searchCounty',
				),
				'search_formats' => array(
					'Phonebook' => array(
						1 => 'Name',
						2 => 'ID',
						3 => 'Address',
						4 => 'Phone',
						5 => 'Email',
					),
					'Status' => array(
						1 => 'Name',
						2 => 'School',
						3 => 'Class',
						4 => 'Status',
					),
				),
				);
				break;
		}
	}
}