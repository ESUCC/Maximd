<?php

/**
 * StudentTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_StudentTable extends Model_Table_AbstractIepForm {
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

	// Added by Mike 5-4-2017 in order to get a list of students in the districts
	
	function writevar1($var1,$var2) {
	
	    ob_start();
	    var_dump($var1);
	    $data = ob_get_clean();
	    $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
	    $fp = fopen("/tmp/textfile.txt", "a");
	    fwrite($fp, $data2);
	    fclose($fp);
	}
	
	public function studentsInDistrict($id_cnty,$id_dist,$juneCutoff){
	    //$sql = "SELECT id_student,tempid,alternate_assessment,pub_school_student,parental_placement,unique_id_state,name_first,name_last,sesis_exit_code,status,id_county,id_district,id_school FROM iep_student WHERE id_county = '$id_cnty' AND id_district = '$id_dist' AND status = 'Active'";
	    $sql = "SELECT id_student,grade,alternate_assessment,
	            pub_school_student,parental_placement,
	            unique_id_state,name_first,name_last,
	            sesis_exit_code,sesis_exit_date,status,id_county,id_district,id_school 
	            FROM iep_student WHERE id_county = '$id_cnty' 
	            AND id_district = '$id_dist' 
	            AND ( status = 'Active' or sesis_exit_date > '$juneCutoff' )";
	     
	    $result = $this->db->fetchAll($sql);
	  //  $this->writevar1($sql,'this is the sql statement line 40');
	  ///---  $this->writevar1($result,'this is the result in table line 40');
	    return $result;
	    
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
			                'age_months_into_year' =>
			                    new Zend_Db_Expr("date_part('months',age(dob))"),
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
			                'nonpublicschool_name' =>
			                    new Zend_Db_Expr("get_name_school_nonpublic(nonpubcounty, nonpubdistrict, nonpubschool)"),
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
			                'team_member_names' =>
			                    new Zend_Db_Expr("get_team_member_names(id_student)"),
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
			                'nonpublicschool_name' =>
			                    new Zend_Db_Expr("get_name_school_nonpublic(nonpubcounty, nonpubdistrict, nonpubschool)"),
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
             		->where("(s.id_case_mgr = '$id' OR s.id_list_team ilike '%$id%') and s.status='Active'")
                    ->order(array('name_last asc', 'name_first asc'));
// 		echo "select: $select<BR>";
// 		die();
		$results = $db->fetchAll($select);
	    return $results;
	}

    static public function age_calculate($date_from, $date_to) { // params are getDate arrays
//        define("A_DAY", 86400); // One day in seconds
//        define("A_WEEK", 604800); // One week in seconds

        $aDay = 86400;  // One day in seconds
        $aWeek = 604800;// One week in seconds

        $age['years'] = $date_to['year'] - $date_from['year'];
        $age['monthsTotal'] = ( $date_to['mon'] + (12*$date_to['year']) ) - ( $date_from['mon'] + (12*$date_from['year']) );

        // month and monthday is smaller than birth
        if ( ($date_to['mon'] <= $date_from['mon']) && ($date_to['mday'] <= $date_from['mday']) ) {
            $age['years'] -= 1;
            $age['monthsTotal'] -= 1;
        }

        $age['months'] = $age['monthsTotal'] - ( $age['years'] * 12 );
        $age['daysExcact'] = ($date_to[0] - $date_from[0]) / $aDay;
        $age['daysExact'] = ($date_to[0] - $date_from[0]) / $aDay; // added to correct spelling
        $age['days'] = floor( ($date_to[0] - $date_from[0]) / $aDay );
        $age['weeksExcact'] = ($date_to[0] - $date_from[0]) / $aWeek;
        $age['weeks'] = floor( ($date_to[0] - $date_from[0]) / $aWeek );
        if ( $date_to['mday'] < $date_from['mday'] ) {
            $age['modMonthDays'] = self::days_in_month($date_to['mon']-1,$date_to['year']) - ($date_from['mday'] - $date_to['mday']);
        } else {
            $age['modMonthDays'] = $date_to['mday'] - $date_from['mday'];
        }

        // 20111122 jlavere - was returning a -month and a year too high in some cases
        if($age['months'] <0) {
            $age['months'] += 12;
            $age['years'] -= 1;
        }

        return $age;
    }
    static function days_in_month($month, $year) {
        $lastday = mktime (0,0,0,($month+1),0,$year);
        $days = strftime("%d", $lastday);
        return $days;
    }

    static public function getEntryDate($id)
    {
        $db = Zend_Registry::get('db');
        $id = $db->quote($id);

        $select = $db->select()
            ->from( 'iep_student',
                array(
                    'entry_date' => new Zend_Db_Expr("get_student_entry_date(id_student)"),
                )
            )
            ->where( "id_student = $id" );
        $results = $db->fetchAll($select);
        if(count($results)) {
            return $results[0]['entry_date'];
        }
        return null;
    }

    static public function getStudentTeamMembers($studentId)
    {
        $db = Zend_Registry::get('db');
        $id = $db->quote($studentId);

        $select = $db->select()
            ->from( 'iep_student_team')
            ->where( "id_student = $id and status='Active'" );
        $results = $db->fetchAll($select);
        if(count($results)) {
            return $results;
        }
        return null;
    }
    public function insertStudentTeamMember($studentId, $personnelId, $insertData = array(), $deleteExisting = false)
    {
        include("Writeit.php");
        $db = Zend_Registry::get('db');
        $sid = $db->quote($studentId);
        $pid = $db->quote($personnelId);

        $studentTeamObj = new Model_Table_StudentTeamMember();

        if($deleteExisting) {
            $studentTeamRecords = $studentTeamObj->fetchAll("id_student = '$sid' and id_personnel = $pid and status = 'Active' " );
            if(count($studentTeamRecords)) {
                foreach($studentTeamRecords as $studentTeam) {
                    $studentTeam->status = 'Removed';
                    $studentTeam->save();
                }
            }
        }

        $insertData['id_student'] = $studentId;
        $insertData['id_personnel'] = $personnelId;
        $newId = $studentTeamObj->insert($insertData);

        /**
         * update student record
         */
        $data = array(
            'id_list_team'=>new Zend_Db_Expr("get_id_list_team(id_student)"),
        );
        $table = new $this->className();
        $where = $table->getAdapter()->quoteInto('id_student = ?', $studentId);
        $this->update($data, $where);
        writevar($data,'this is the data to insert');
        return $newId;
    }

    public function removeStudentTeamMembers($studentId)
    {
        $db = Zend_Registry::get('db');
        $sid = $db->quote($studentId);

        $studentTeamObj = new Model_Table_StudentTeamMember();
        $studentTeamRecords = $studentTeamObj->fetchAll("id_student = $sid and status = 'Active' " );
        if(count($studentTeamRecords)) {
            foreach($studentTeamRecords as $studentTeam) {
                $studentTeam->status = 'Removed';
                $studentTeam->save();
            }
        }
    }
    
    /**
     * Returns true if user is in demo county, district or school
     *
     * @return string
     */
    public function IsDemoStudent($county, $district, $school)
    {
    	$counties = array(99);
    	$districts = array(9999);
    	$schools = array(999);
    	if (in_array($county, $counties) || in_array($district, $districts) || in_array($school, $schools)) {
    		return true;
    	}
    	return false;
    }
// Mike Added this funciton 12-14-2017 see JIRA ticket 148 SRS-148
    public function setMissingIdStudentLocals($county, $district) {
        $sql = "SELECT id_student, id_student_local, id_district, s.Status, name_first, name_last, name_middle, dob FROM iep_student AS s WHERE s.id_county = '{$county}' AND id_district = '{$district}' AND (id_student_local = '0' OR id_student_local IS NULL) AND status = 'Active'";
        $result = $this->db->fetchAll($sql);
        
        $found = array();
        $not_found = array();
        foreach ($result as $r) {
            $sql_sub = "SELECT * FROM iep_student sub WHERE sub.id_county = '{$county}' AND sub.id_district = '{$district}' AND sub.name_first = '{$r['name_first']}' AND sub.name_last = '{$r['name_last']}' AND sub.name_middle = '{$r['name_middle']}' AND sub.dob = '{$r['dob']}' AND status != 'Active'";
            $result_sub = $this->db->fetchRow($sql_sub);
            if (isset($result_sub['id_student_local'])) {
                $sql_update = "UPDATE iep_student SET id_student_local = '{$result_sub['id_student_local']}' WHERE id_student = '{$r['id_student']}'";
                $this->db->query($sql_update);
                $found[] = $r;
            } else {
                $not_found[] = $r;
            }
        }
        return array($found, $not_found);
    }
}
