<?php
class Model_Search {
	
	protected $student;
	protected $session;
	
	public function __construct(Model_Table_StudentTable $student, Zend_Session_Namespace $session)
	{
		$this->setStudent($student);
		$this->setSession($session);
	}
	
	public function searchStudents($request)
	{
		/* 
		 * Included in all searches
		 */
		$sql = "SELECT s.id_student, " 
			 . " get_name_personnel(id_case_mgr) AS name_case_mgr, ";
		
		/*
		 * Included in all 3 formats (Search List, Phonebook, MDT/IEP Report).
		 * There appears to be an IEP Report format that is no longer included.  
		 */
		$sql .= " get_most_recent_mdt_disability_primary(id_student) as mdt_primary_disability, " 
             .  " get_most_recent_mdt_date_conference(id_student) as mdt_date_conference, "
			 .  " get_most_recent_determination_notice(id_student) as det_notice_date, "
			 .  " most_recent_final_mdt_id(id_student) as mdt_id, "
             .  " get_most_recent_mdt_draft_id(id_student) as mdt_draft_id, "
			 .  " rpt_draft_form_type(id_student) as draft_form_type, " 
             .  " rpt_draft_date_created(id_student) as draft_iep_date_created, "
             .  " rpt_draft_id(id_student) as draft_iep_id, "
			 .  " rpt_final_form_type(id_student) as form_type, "
             .  " rpt_final_date_created(id_student) as iep_date_conference, "
             .  " rpt_final_id(id_student) as iep_id, "
			 .  " mdtorform001_draft_form_type(id_student) as mdtorform001_draft_form_type, "
             .  " mdtorform001_draft_date_created(id_student) as mdtorform001_draft_date_created, "
             .  " mdtorform001_draft_id(id_student) as mdtorform001_draft_id, "
			 .  " mdtorform001_final_form_type(id_student) as mdtorform001_final_form_type, "
             .  " mdtorform001_final_date_created(id_student) as mdtorform001_final_date_created, "
             .  " mdtorform001_final_id(id_student) as mdtorform001_final_id, ";
		
		/*
		 * Preface these fields with table alias so we don't get 
		 * an error with joined tables.  Old code had an if statment 
		 * for this but there's no reason to not go ahead and do this.
		 */
		$sql .= " get_name_county(s.id_county) as name_county, "
			 .  " get_name_district(s.id_county, s.id_district) as name_district, " 
		     .  " get_name_school(s.id_county, s.id_district, s.id_school) as name_school, "
		     .  " CASE WHEN s.name_middle IS NOT NULL THEN s.name_first || ' ' || s.name_middle || ' ' || s.name_last ELSE s.name_first || ' ' || s.name_last END AS name_full, "
		     .  " s.name_last || ', ' || s.name_first as name_last_first, "
		     .  " s.address_street1 || ', ' || s.address_city || ' ' || CAST(s.address_state AS TEXT) || ', ' || CAST(s.address_zip AS TEXT) as address\n";
		
		/*
		 * @todo This will need to be an if statment to control access to students
		 * that user has access to.
		 */
		$sql .= " FROM iep_student AS s WHERE ";
		// else $sql .= " FROM my_students s ";
		
		/*
		 * @todo Add join for personnel based on search form
		 */
		//  $sql .= " LEFT JOIN iep_personnel p ON s.id_case_mgr=p.id_personnel ";

		/*
		 * @todo Add join for student team based on search form
		 */
		// $sql .= " LEFT JOIN iep_personnel p ON s.id_case_mgr=p.id_personnel LEFT JOIN iep_student_team st ON s.id_student = st.id_student LEFT JOIN iep_personnel team ON st.id_personnel = team.id_personnel ";
		
		/*
		 * @todo Check privileges set here for ($sessUserMinPriv == UC_PG)
		 * @todo insert session id
		 */
		// $sql .= " WHERE s.id_student IN (SELECT id_student FROM iep_guardian WHERE id_guardian = ?) ";
		// else $sql .= " WHERE s.id_personnel = ? ";
		
		/*
		 * @todo Limit the results to the users case load if not admin
		*/
		// $sql .= " AND (id_ei_case_mgr = '$sessIdUser' OR id_list_team ilike '%$sessIdUser;%')";
		
		/*
		 * @todo bunch of stuff to exclude based on some variables
		 * $area = controller
		 * $sub = action?
		 * I think all of these will be custom URL's to the search page
		 */
		/*
		 * if("reports" == $area && "transportation" == $sub && "Qualified for Transport" == $tranStatus) {
    $sqlStmt .= " and 't' = (select transportation_yn from iep_form_004 where id_form_004 = (select most_recent_final_iep_id(s.id_student))) ";

} elseif("reports" == $area && "transportation" == $sub && "Did Not Qualify" == $tranStatus) {
    $sqlStmt .= " and 'f' = (select transportation_yn from iep_form_004 where id_form_004 = (select most_recent_final_iep_id(s.id_student))) ";

} elseif("student" == $area && "list" == $sub && "No NSSRS ID#" == $search_other) {
    $sqlStmt .= " and unique_id_state is null ";

} elseif("reports" == $area && "nssrs" == $sub && "No NSSRS ID#" == $search_other) {
    $sqlStmt .= " and unique_id_state is null ";

} elseif("reports" == $area && "nssrs_transfers" == $sub && "No NSSRS ID#" == $search_other) {
    $sqlStmt .= " and unique_id_state is null ";

} elseif("reports" == $area && "nssrs" == $sub && "excluded" == $format) {
    $sqlStmt .= " and exclude_from_nssrs_report = true ";

}
		 */
		
		/*
		 * Check the search status (Active, All, Inactive, Never Qualified, No Longer Qualifies, Transferred to non srs district)
		 */
		if ('All' !== $request->getParam('searchStatus'))
			$sql .= " s.status = ? AND s.name_last LIKE '%Test%' ";
		
		/*
		 * @todo more stuff based on reports to add
		 */
		/*
		 * if("reports" == $area && "evaluation_date_report" == $sub) { // test building for eval report
        $sqlStmt .= "and (most_recent_noticeofeval_createdate(id_student) >= get_most_recent_mdt_date_conference(id_student)::date OR
                        (most_recent_noticeofeval_createdate(id_student) is not null and get_most_recent_mdt_date_conference(id_student) is null))";
}
		 */
		
		/*
		 * @todo add exclusions based on user defined search fields
		 * see original code for this.
		 */
		
		/*
		 * @todo include original sorting code
		 */
		/*
		switch ($sort) {
			case "Name":
				$sortArray = array ("lower(name_last) ASC", "lower(name_first) ASC", "id_student ASC");
				break;
			case "School":
				#$sortArray = array ("get_name_county(id_county)", "get_name_district(id_county, id_district)", "get_name_school(id_county, id_district, id_school)", "lower(name_last) ASC", "lower(name_first) ASC", "lower(id_student) ASC");
				$sortArray = array ("name_county", "name_district", "name_school", "name_last ASC, name_first ASC");
				break;
			case "Last MDT Ascending":
				$sortArray = array ("name_county", "name_district", "name_school", "name_last ASC, name_first ASC");
				//
				// 20061122 jlavere - STUDENT REPORT
				//
				array_unshift($sortArray, "mdt_primary_disability");
			break;
			case "Last MDT Descending":
				$sortArray = array ("name_county", "name_district", "name_school", "name_last ASC, name_first ASC");
				//
				// 20061122 jlavere - STUDENT REPORT
				//
				array_unshift($sortArray, "mdt_primary_disability desc");
			break;
			case "Last IEP Ascending":
				$sortArray = array ("name_county", "name_district", "name_school", "name_last ASC, name_first ASC");
				//
				// 20061122 jlavere - STUDENT REPORT
				//
				array_unshift($sortArray, "iep_date_conference");
			break;
			case "Last IEP Descending":
				$sortArray = array ("name_county", "name_district", "name_school", "name_last ASC, name_first ASC");
				//
				// 20061122 jlavere - STUDENT REPORT
				//
				array_unshift($sortArray, "iep_date_conference desc");
			break;
			case "IEP Due Ascending":
				$sortArray = array ("name_county", "name_district", "name_school", "name_last ASC, name_first ASC");
				//
				// 20061122 jlavere - STUDENT REPORT
				//
				array_unshift($sortArray, "rpt_date_sort(id_student)");
				break;
				case "IEP Due Descending":
				$sortArray = array ("name_county", "name_district", "name_school", "name_last ASC, name_first ASC");
				//
				// 20061122 jlavere - STUDENT REPORT
				//
				array_unshift($sortArray, "rpt_date_sort(id_student) desc");
				break;
				case "MDT Due Ascending":
				$sortArray = array ("name_county", "name_district", "name_school", "name_last ASC, name_first ASC");
				//
				// 20061122 jlavere - STUDENT REPORT
				//
				array_unshift($sortArray, "mdt_date_conference");
				break;
				case "MDT Due Descending":
				$sortArray = array ("name_county", "name_district", "name_school", "name_last ASC, name_first ASC");
				//
				// 20061122 jlavere - STUDENT REPORT
				//
				array_unshift($sortArray, "mdt_date_conference desc");
				break;
				case "days_since_eval":
				$sortArray = array ("name_county", "name_district", "name_school", "name_last ASC, name_first ASC");
				//
				// 20061122 jlavere - STUDENT REPORT
				//
				array_unshift($sortArray, "days_since_eval desc");
			break;
		}
		
        $sql .= " ORDER BY ";
        for ($i=0; $i < count($sortArray); $i++) {
                $sql .= $sortArray[$i];
                if ( $i < count($sortArray) - 1 ) {
                                $sql .= ",";
                }
        }
        $sql .= " ;";

		*/	

		return $paginator = Zend_Paginator::factory(
				$this->student->getAdapter()->fetchAll(
					$sql, 
					array(
						$request->getParam('searchStatus')
					)
				)
		)->setItemCountPerPage(5)
		 ->setCurrentPageNumber(1);
	}
	
	public function getFormatToRender($request) {
		switch($request->getParam('format')) {
			case 'School List':
				return 'school-list.phtml';
			break;
			case 'Phonebook':
				return 'phonebook.phtml';
			break;
			case 'MDT/IEP Report':
				return 'mdt-iep-report.phtml';
			break;
			default:
				return 'school-list.phtml';
		}
	}
	
	protected function setStudent(Model_Table_StudentTable $student)
	{
		$this->student = $student;
	}
	
	protected function setSession(Zend_Session_Namespace $session)
	{
		$this->session = $session;
	}
}