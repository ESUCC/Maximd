<?php

/**
 * StudentTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php'; 

class Model_Table_StudentTable2 extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
	protected $_name = 'iep_student';
	protected $_primary = 'id_student';
	
	 
	 
	 
	
	

// 	protected $_referenceMap = array(
// 		'ContactTypes' => array(
// 			'columns' => array('contacttype_id'),
// 			'refTableClass' => 'ContactTypesTable',
// 			'refColumns'	=> array('id')
// 		)
// 	);

	

	public function setRights($Id) {
	    // include("Writeit.php");
	    //  writevar($Id,'this is the array');
	
	    $x=1;
	    while ($x <= $Id['Count'] ){
	
	        $staffid = $x."_id_personnel" ;
	        $did=$Id['id_district'];
	        //  $row = $row = $this->fetchRow('id_district='".$did.")
	        //  writevar($row,'this is the row');
	        $x=$x+1;
	    }
	    
	
	    /*
	     *
	     select count(*) from foo where id = 5
	      
	     */
	}
	
	public function getStudentList($idCounty,$idDistrict,$idSchool)   // Written by Mike for staffController. teamAction to get just a list of students
	{
	    
	    $studentsInfo=new Model_Table_StudentTable2();
	    $results=$studentsInfo->fetchAll($studentsInfo->select()
	    ->where('id_county = ?',$idCounty)
	    ->where('id_district = ?',$idDistrict)
	    ->where('id_school = ?',$idSchool)
	    ->where('status = ?', 'Active')
            ->order('name_last'));

	    return $results->toArray();
	   
	    
	}
	
	public function studentInfo2($id){
	    
	    $studentsInfo=new Model_Table_StudentTable2();
	    $results=$studentsInfo->fetchAll($studentsInfo->select()
	        ->where('id_student = ?',$id));
	    return $results;
	       
	}
	
	public function studentInfo($id)
	{
	    $db = Zend_Registry::get('db');
	    $id = $db->quote($id);
	
	    $select = $db->select()
	                 ->from( 'iep_student',
			            array(
			                '*',
			                'name_student_full' =>
			                    new Zend_Db_Expr("CASE WHEN name_middle IS NOT NULL THEN name_first || ' ' || name_middle || ' ' || name_last ELSE name_first || ' ' || name_last END"),
			                'age' =>
			                    new Zend_Db_Expr("date_part('year',age(dob))"),
			                'age_days_into_year' =>
			                    new Zend_Db_Expr("date_part('days',age(dob))"),
			                'address' =>
			                    new Zend_Db_Expr("CASE WHEN address_street2 IS NOT NULL THEN address_street1 || ' ' || address_street2 || ' ' || address_city || ', ' || address_state || ' ' || address_zip ELSE address_street1 || ' ' || address_city || ', ' || address_state || ' ' || address_zip END"), 
			                'most_recent_dis' =>
			                    new Zend_Db_Expr("get_most_recent_mdt_disability_primary(id_student)"),
			                'most_recent_date_mdt' =>
			                    new Zend_Db_Expr("get_most_recent_mdt_date_conference(id_student)"),
			                'most_recent_date_iep' =>
			                    new Zend_Db_Expr("get_most_recent_iep_date_conference(id_student)"),
			                'name_county' =>
			                    new Zend_Db_Expr("get_name_county(id_county)"),
			                'name_district' =>
			                    new Zend_Db_Expr("get_name_district(id_county, id_district)"),
			                'name_school' =>
			                    new Zend_Db_Expr("get_name_school(id_county, id_district, id_school)"),
//			                'nonpublicschool_name' =>
//			                    new Zend_Db_Expr("get_name_school_nonpublic(nonpubcounty, nonpubdistrict, nonpubschool)"),
			                'name_case_mgr' =>
			                    new Zend_Db_Expr("get_name_personnel(id_case_mgr)"),
			                'name_ei_case_mgr' =>
			                    new Zend_Db_Expr("get_name_personnel(id_ei_case_mgr)"),
			                'name_ser_cord"' =>
			                    new Zend_Db_Expr("get_name_personnel(id_ser_cord)"),
			                'studentDisplay' =>
			                    new Zend_Db_Expr("CASE WHEN id_student_local IS NOT NULL THEN id_student_local ELSE id_student END"),
			                'guardian_names' =>
			                    new Zend_Db_Expr("get_guardian_names(id_student)"),
			            )
	                 )
	                 ->where( "id_student = $id" );
	    $results = $db->fetchAll($select);
	    return $results;
	}
	public function studentInfoByUniqueId($id)
	{
	    $db = Zend_Registry::get('db');
	    $id = $db->quote($id);

	    $select = $db->select()
	                 ->from( 'iep_student',
			            array(
			                '*',
			                'name_student_full' =>
			                    new Zend_Db_Expr("CASE WHEN name_middle IS NOT NULL THEN name_first || ' ' || name_middle || ' ' || name_last ELSE name_first || ' ' || name_last END"),
			                'age' =>
			                    new Zend_Db_Expr("date_part('year',age(dob))"),
			                'age_days_into_year' =>
			                    new Zend_Db_Expr("date_part('days',age(dob))"),
			                'address' =>
			                    new Zend_Db_Expr("CASE WHEN address_street2 IS NOT NULL THEN address_street1 || ' ' || address_street2 || ' ' || address_city || ', ' || address_state || ' ' || address_zip ELSE address_street1 || ' ' || address_city || ', ' || address_state || ' ' || address_zip END"),
			                'most_recent_dis' =>
			                    new Zend_Db_Expr("get_most_recent_mdt_disability_primary(id_student)"),
			                'most_recent_date_mdt' =>
			                    new Zend_Db_Expr("get_most_recent_mdt_date_conference(id_student)"),
			                'most_recent_date_iep' =>
			                    new Zend_Db_Expr("get_most_recent_iep_date_conference(id_student)"),
			                'name_county' =>
			                    new Zend_Db_Expr("get_name_county(id_county)"),
			                'name_district' =>
			                    new Zend_Db_Expr("get_name_district(id_county, id_district)"),
			                'name_school' =>
			                    new Zend_Db_Expr("get_name_school(id_county, id_district, id_school)"),
//			                'nonpublicschool_name' =>
//			                    new Zend_Db_Expr("get_name_school_nonpublic(nonpubcounty, nonpubdistrict, nonpubschool)"),
			                'name_case_mgr' =>
			                    new Zend_Db_Expr("get_name_personnel(id_case_mgr)"),
			                'name_ei_case_mgr' =>
			                    new Zend_Db_Expr("get_name_personnel(id_ei_case_mgr)"),
			                'name_ser_cord"' =>
			                    new Zend_Db_Expr("get_name_personnel(id_ser_cord)"),
			                'studentDisplay' =>
			                    new Zend_Db_Expr("CASE WHEN id_student_local IS NOT NULL THEN id_student_local ELSE id_student END"),
			                'guardian_names' =>
			                    new Zend_Db_Expr("get_guardian_names(id_student)"),
			            )
	                 )
	                 ->where( "unique_id_state = $id" );
        echo $select;
	    $results = $db->fetchAll($select);
	    return $results;
	}

	public function prHelper($id) {
	    $db = Zend_Registry::get('db');
	    $select = $db->select()
             		->from(array('s' => 'iep_student'),
			            array(
			                '*',
			                'name_full' =>
			                    new Zend_Db_Expr("CASE WHEN name_middle IS NOT NULL THEN name_first || ' ' || name_middle || ' ' || name_last ELSE name_first || ' ' || name_last END"),
			                'age' =>
			                    new Zend_Db_Expr("date_part('year',age(dob))"),
			            )
					)
             		->where("(s.id_case_mgr = '$id' OR s.id_list_team ilike '%$id%') and s.status='Active'");
// 		echo "select: $select<BR>";
// 		die();
		$results = $db->fetchAll($select);
	    return $results;
	    
	}
	
	public function getEntryAndExitDates($studentId) {
		return $this->fetchRow(
				$this->select()
				     ->from(
				     		$this->_name, 
				     		array(
				     				'entry_date', 
				     				'exit_date')
				     		)
				     ->where(
				     		'id_student = ?',
				     		$studentId
				     )
				)->toArray();
	}
}
