<?php
require_once(APPLICATION_PATH.'/../library/My/Classes/privCheck.php');
class Model_Table_IepStudent extends Zend_Db_Table_Abstract {
 
    protected $_name = 'iep_student';
    protected $_primary = 'id_student';
    protected $_sequence = "iep_student_id_student_seq";
    
    protected $_dependentTables = array(
        'Model_Table_StudentTeamMember',
    );
    
    static public function getUserById($id_student)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select('name_first, name_middle, name_last')
                     ->from( 'iep_student' )
                     ->where( "id_student = ?", $id_student )
                     ->order( "name_first", "name_last" );
    
        $result = $db->fetchAll($select);
        
        return $result[0];
        
    }
    
    public function getStudentForParentById($id){
        $db = Zend_Registry::get('db');
        $select = $db->select('name_first, name_middle, name_last,id_case_mgr')
        ->from( 'iep_student' )
        ->where( "id_student = ?", $id);
        $result = $db->fetchrow($select);
        
        return $result;
    }
    
	public function studentPersonnelAccess($id_student, $id_personnel, $checkout = true, $writeLog = false)
	{
		$sessUser = new Zend_Session_Namespace('user');
		$sessPrivCheckObj = $sessUser->sessPrivCheckObj;
		$sessIdUser = $sessUser->sessIdUser;
		
		// get the current user priv object
		//		Zend_debug::dump( Model_Table_IepStudent::getStudent($id_student));
		
		$student = Model_Table_IepStudent::getStudent($id_student);
		if(false == $student)
		{	
			// student doesn't exist
			return false;
		} else {

			$arrData = $student;
        	$accessVariablesArr = array(	'area' 		 => $area = 'student',
                                            'sub' 		 => $sub = 'student',
                                            'mode' 		 => $mode = 'view',
                                            'option' 	 => $option = 'forms',
                                            'formNumber' => $formNumber ='',
                                            'formStatus' => $formStatus ='',
        	);

//        	if($forceOverrideAccessLvl==0) { // used when access check is not required. for example, when running the sesis snapshot
        		if (!$this->accessValidation($arrData['id_student'], $sessIdUser, $accessVariablesArr, $arrData)) {
        			$this->errorId = 'ERROR_ACCESS_DENIED';
//        			Zend_debug::dump( "iep_class_student: accessValidation failed!");
//        			throw new exception('ERROR_ACCESS_DENIED');
        			return false;
        		}
//        	}


        	// if the record has been selected in edit mode and checkout is true, attempt to lock the record or return a record locked error
        	if ($mode == "edit" && $checkout) {
				Model_Table_IepStudent::checkout($mode, $arrData);
        	}

        	// logging
//        	if ($writeLog && $action != "save" && $option != "log") {
//        		if ($mode == "view") {
//        			if ($writeLog > 1) {
//        				return true;
//        			}
//        			$logType = 1;
//        		} else {
//        			if ($writeLog > 2) {
//        				return true;
//        			}
//        			$logType = 2;
//        		}
//        		if (writeLog($pkey, $logType, $tableName, $this->errorId, $this->errorMsg)) {
//        			return true;
//        		} else {
//        			return false;
//        		}
//        	} else {
//        		Zend_debug::dump('access granted');
        		return true;
//        	}
			
			
		}
	}

	function accessValidation($id_student, $id_personnel, $accessVariablesArr, $studentArr = false) {
		
//		Zend_debug::dump("Model_Table_IepStudent::accessValidation");
		#
		# 11/03 JL new validation through privCheck
		#
//		global $sessPrivCheckObj;
		
		$sessUser = new Zend_Session_Namespace('user');
//		$sessPrivCheckObj = $sessUser->sessPrivCheckObj;
		$sessPrivCheckObj = user_session_manager::getSessPrivCheckObj();
		//		Zend_debug::dump($sessPrivCheckObj);
//		Zend_debug::dump($sessPrivCheckObj->yessir());
		##
		#########################################################################################
		#
		# VALIDATE USER HAS ACCESS TO STUDENT/AREA/SUB

		if($sessPrivCheckObj->validateStudentAccess($id_student, $id_personnel, $accessVariablesArr, $studentArr)) { 
//			$this->id_student = $id_student;
			$this->accessArrName = $sessPrivCheckObj->accessArrName;
//			Zend_debug::dump($this->accessArrName);
			return true;
		} else {
			return false;
		}

		#
		#
		#########################################################################################
		##		
	}
	
    static private function getStudent($id_student)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
                     ->from( array('p' => 'iep_student'),
                             array(
								'*',
//								'CASE WHEN name_middle IS NOT NULL THEN name_first || \' \' || name_middle || \' \' || name_last ELSE name_first || \' \' || name_last END AS name_student_full',
								'date_part(\'year\',age(dob)) as age',
//								'CASE WHEN address_street2 IS NOT NULL THEN address_street1 || \' \' || address_street2 || \' \' || address_city || \', \' || address_state || \' \' || address_zip ELSE address_street1 || \' \' || address_city || \', \' || address_state || \' \' || address_zip END AS address',
								'get_name_county(id_county) as name_county',
								'get_name_district(id_county, id_district) as name_district',
								'get_name_school(id_county, id_district, id_school) as name_school',
								'get_name_personnel(id_case_mgr) as name_case_mgr',
								'get_name_personnel(id_ei_case_mgr) as name_ei_case_mgr',
								'get_name_personnel(id_ser_cord) as name_ser_cord',   
                             )
                        )
                     ->where( "id_student = '$id_student'" )
                     ->order('name_first',  'name_middle', 'name_last');

        $result = $db->fetchRow($select);
            
//        if (count($result) <= 0) {
//        	return false;
//        } else {
//
//        	$arrData = $result[0];
//        	$accessVariablesArr = array(	'area' 		=> $area,
//                                                    'sub' 		=> $sub,
//                                                    'mode' 		=> $mode,
//                                                    'option' 		=> $option,
//                                                    'formNumber' 		=> $formNumber,
//                                                    'formStatus' 		=> $formStatus,
//        	);
//
//        	if($forceOverrideAccessLvl==0) { // used when access check is not required. for example, when running the sesis snapshot
//        		if (!$this->accessValidation($arrData['id_student'], $sessIdUser, $accessVariablesArr, $arrData)) {
//        			$this->errorId = $ERROR_ACCESS_DENIED;
//        			#debugLog( "iep_class_student: accessValidation failed!");
//        			return false;
//        		}
//        	}
//
//        	// if the record has been selected in edit mode and checkout is true, attempt to lock the record or return a record locked error
//        	if ($mode == "edit" && $checkout) {
//        		if ($mode == "edit" && !empty($arrData['checkout_id_user']) && $arrData['checkout_id_user'] != $sessIdUser && $arrData['checkout_time'] > (time() - $RECORD_CHECKOUT_SECONDS)) {
//        			$this->errorId = $ERROR_RECORD_LOCKED;
//        			$this->errorMsg = $arrData['checkout_id_user'];
//        			return false;
//        		} else {
//        			$sqlStmt  =	"UPDATE $tableName\nSET checkout_id_user = '$sessIdUser', checkout_time = '" . time() . "'\n";
//        			$sqlStmt .= "WHERE $pkeyName = $pkey;\n";
//
//        			$result = xmlRpcslqExec($sqlStmt, $this->errorId, $this->errorMsg, true, true);
//        			if (false === $result) {
//        				return false;
//        			} else {
//        				global $sub;
//        				$sessCurrentRecord = time() . ";$sub;$keyName;$pkey;$tableName;$pkeyName";
//        			}
//        		}
//        	}
//
//        	if ($writeLog && $action != "save" && $option != "log") {
//        		if ($mode == "view") {
//        			if ($writeLog > 1) {
//        				return true;
//        			}
//        			$logType = 1;
//        		} else {
//        			if ($writeLog > 2) {
//        				return true;
//        			}
//        			$logType = 2;
//        		}
//        		if (writeLog($pkey, $logType, $tableName, $this->errorId, $this->errorMsg)) {
//        			return true;
//        		} else {
//        			return false;
//        		}
//        	} else {
//        		return true;
//        	}
//        }
        return $result;
        
    }

    static public function save($id, $data)
    {
        unset($data['submit']);
        unset($data['id_student']);
        
        try
        {
            $db = Zend_Registry::get('db');
            $where[] = "id_student = '$id'";
            $result = $db->update('iep_student', $data, $where);
            return $result;
        }
        catch (Zend_Db_Statement_Exception $e) {
            // generate error
            echo "error: $e";
        }
        return false;
    }
    
	static function searchStudent($searchfield, $searchvalue)
	{
            try
            {
                $db = Zend_Registry::get('db');
                $select = $db->select('name_first, name_middle, name_last')
                    		  ->from( 'iep_student' )
                    		  ->where( $searchfield . " ilike ?", '%'.$searchvalue.'%' );
                
                $results = $db->fetchAll($select);
                return $results;
            }
            catch (Zend_Db_Statement_Exception $e) {
                // should log errors
                // generate error
                // but as long as we're not
                // don't swallow the exception
                throw new Zend_Db_Statement_Exception($e);    
            }
	}
	
	function checkout($mode, $studentData)
	{
		$sessUser = new Zend_Session_Namespace('user');
		$sessIdUser = $sessUser->sessIdUser;
//		Zend_debug::dump(RECORD_CHECKOUT_SECONDS);
		if ($mode == "edit" && !empty($studentData['checkout_id_user']) && $studentData['checkout_id_user'] != $sessIdUser && $studentData['checkout_time'] > (time() - RECORD_CHECKOUT_SECONDS)) {
			$this->errorId = 'ERROR_RECORD_LOCKED';
			$this->errorMsg = $studentData['checkout_id_user'];
			return false;
		} else {
			
			$result = Model_Table_IepStudent::save($studentData['id_student'], array('checkout_id_user' => $sessIdUser, 'checkout_time' => time()));
			if (false === $result) {
				return false;
			} else {
				// 20090903 jlavere - not sure why this is used below. not turning on until I know
//				global $sub;
//				$sessCurrentRecord = time() . ";$sub;$keyName;$pkey;$tableName;$pkeyName";
			}
		}
	}



	function buildAdminStudentSearch()
	{
		
		// IF WE GOT HERE BY HITTING A SEARCH BUTTON ON THIS PAGE, BUILD NEW SQL STMT
		// COUNT = 1 INDICATES THE SUBMISSION CAME FROM THE FORM SEE ELSE CLAUSE FOR THE ALTERNATIVE
		#debugLog("sesis test");
		
        $sessStudentSearch = new Zend_Session_Namespace('student');
		
        $format = $sessStudentSearch->sessCurrentStudentFormat;

		$sessUser = new Zend_Session_Namespace('user');
		$sessPrivCheckObj = $sessUser->sessPrivCheckObj;
		$sessIdUser = $sessUser->sessIdUser;
		$sessUserPrivs = $sessUser->sessUserPrivs;
		$sessUserMinPriv = $sessUser->sessUserMinPriv;
		$showRowsSEARCH = $sessUser->showRowsSEARCH;

		$searchType = $sessStudentSearch->searchType;
		$searchField = $sessStudentSearch->searchfield;
		$searchValue = $sessStudentSearch->searchvalue;
		
		$showRowsSEARCH = $sessStudentSearch->showRowsSEARCH;
		
//		Zend_debug::dump($searchType, 'searchType');
//		Zend_debug::dump($sessStudentSearch->sessCurrentSearchStatus, 'sessCurrentSearchStatus');
		//		foreach($sessUser as $k => $v)
//		{
//			Zend_debug::dump($v, $k);
//		}
		# ================================================================
		# STATUS SETTINGS
		#
		if($sessStudentSearch->search_status == '') {
			$sessStudentSearch->sessCurrentSearchStatus = 'Active';
		} else {
			$sessStudentSearch->sessCurrentSearchStatus = $sessStudentSearch->search_status;
		}
		if($sessStudentSearch->sessCurrentSearchStatus == "") {
			$statusSQL = "";
		} elseif($sessStudentSearch->sessCurrentSearchStatus == "All") {
			$statusSQL = "";
		} else {
			$statusSQL = " and status = '$sessStudentSearch->sessCurrentSearchStatus' ";
		}
		# ================================================================
		// set sortField
		// build sort array cretaed 11/5/2002 sthoms
		// added for debugging sl 1-11-2003. $sort is only set if we post here from the search form, but not everyone does
		// we need to give non-searchers a way to manipulate sort order and display format
		$sort = !isset($sort)?"School":$sort;
		# ================================================================
		$sqlStmtTop  = "SELECT *,
			id_student, 
			get_name_personnel(id_case_mgr) AS name_case_mgr, 
    		\n";

		//
		// 20061122 jlavere - STUDENT REPORT ADDITIONS
		//
		
		switch ($format) {
			case "Phonebook":
			case "School List":
				break;
			case "MDT/IEP Report":
				//
				// 20061122 jlavere - STUDENT REPORT
				//
				//            rpt_date_sort(id_student) as rpt_date_sort,
				$sqlStmtTop  .= "
            get_most_recent_mdt_disability_primary(id_student) as mdt_primary_disability,
            get_most_recent_mdt_date_conference(id_student) as mdt_date_conference,

            get_most_recent_determination_notice(id_student) as det_notice_date,

            most_recent_final_mdt_id(id_student) as mdt_id,
            get_most_recent_mdt_draft_id(id_student) as mdt_draft_id,


            rpt_draft_form_type(id_student) as draft_form_type,
            rpt_draft_date_created(id_student) as draft_iep_date_created,
            rpt_draft_id(id_student) as draft_iep_id,

            rpt_final_form_type(id_student) as form_type,
            rpt_final_date_created(id_student) as iep_date_conference,
            rpt_final_id(id_student) as iep_id,


            mdtorform001_draft_form_type(id_student) as mdtorform001_draft_form_type,
            mdtorform001_draft_date_created(id_student) as mdtorform001_draft_date_created,
            mdtorform001_draft_id(id_student) as mdtorform001_draft_id,

            mdtorform001_final_form_type(id_student) as mdtorform001_final_form_type,
            mdtorform001_final_date_created(id_student) as mdtorform001_final_date_created,
            mdtorform001_final_id(id_student) as mdtorform001_final_id,

            \n";

				break;
			case "IEP Report":
				//
				// 20061122 jlavere - STUDENT REPORT
				//
				$sqlStmtTop  .= "
            most_recent_final_iep_id(id_student) as iep_id,
            get_most_recent_iep_draft_id(id_student) as iep_draft_id,
            get_most_recent_iep_date_conference(id_student) as iep_date_conference,
            \n";
				break;
		}



		/*
		 ** EVALUATION DATE REPORT
		 */
		// ================================================================================
//		if("reports" == $area && "evaluation_date_report" == $sub) { // test building for eval report
//			$sqlStmtTop  .= "
//        get_most_recent_mdt_date_conference(id_student) as mdt_date_conference,
//        get_most_recent_determination_notice(id_student) as det_notice_date,
//        most_recent_final_initEval_id(id_student) as initial_eval_id,
//        most_recent_final_reEval_id(id_student) as reeval_id,
//
//        most_recent_noticeofeval_id(id_student) as eval_notice_id,
//        most_recent_noticeofeval_date(id_student) as eval_notice_date,
//        most_recent_noticeofeval_createdate(id_student) as eval_notice_createdate,
//        most_recent_noticeofeval_status(id_student) as eval_notice_status,
//        most_recent_noticeofeval_formnum(id_student) as eval_notice_formnum,
//
//        extract('days' from (now() - most_recent_noticeofeval_date(id_student)) ) as days_since_eval,
//        \n";
//		}
		// ================================================================================



		$sqlStmtTop  .= "
	get_name_county(id_county) as name_county, 
	get_name_district(id_county, id_district) as name_district, 
	get_name_school(id_county, id_district, id_school) as name_school,
	CASE WHEN name_middle IS NOT NULL THEN name_first || ' ' || name_middle || ' ' || name_last ELSE name_first || ' ' || name_last END AS name_full,
	name_last || ', ' || name_first as name_last_first,
	address_street1 || ', ' || address_city || ' ' || CAST(address_state AS TEXT) || ', ' || CAST(address_zip AS TEXT) as address\n";

		$sqlStmt = "FROM iep_student s ";


		########################################################################################################################
		##
		## SQL CODE GENERATED IN THIS FILE IS FOR ADMIN AND PARENT SEARCH ONLY
		## include_student_psrchcode.php generates the code for other users
		##
		########################################################################################################################
//		include_once("include_student_psrchcode.php");
		########################################################################################################################
		########################################################################################################################


		//
		// GET THE COUNT OF SESSION PRIVILEGES
		if(isset($sessUserPrivs['id_personnel'])) $sessUserPrivs = array($sessUserPrivs);
		$privCount = count($sessUserPrivs);
		//Zend_debug::dump($sessUserPrivs);
		//
		// USED TO MAKE SURE THAT WE DON'T ADD THE CASE MGR SEARCH MORE THAN ONCE TO THE QUERY.
		// CASE MGR SEARCHES ACROSS SCHOOL IDS SO IT'S NOT NEEDED MORE THAN ONCE.
		$caseMgrCount = 0;
		$SSCount = 0;
		//
		$sqlStmt = $sqlStmt."WHERE (";
		// LOOP THROUGH PRIVILEGES TO BUILD A SEARCH THAT GIVES ACCESS ONLY TO ALLOWED AREAS
		if ($sessUserMinPriv == UC_PG) {
			$sqlStmt .= "id_student IN (SELECT id_student FROM iep_guardian WHERE id_guardian = '$sessIdUser') ";
		} else {
			for($i = 0; $i < $privCount; $i++) {
				$searchClass = $sessUserPrivs[$i]['class'];
				$searchCounty = $sessUserPrivs[$i]['id_county'];
				$searchDistrict = $sessUserPrivs[$i]['id_district'];
				$searchSchool = $sessUserPrivs[$i]['id_school'];
				// OR for case mgr is handled in the switch statement.
				if($i > 0 && $searchClass != UC_CM && $searchClass != UC_SS & $searchClass != UC_SP) {
					$sqlStmt .= "OR ";
				}
				switch ($searchClass) {
					case UC_PG: // PARENT / GUARDIAN
						$sqlStmt .= "id_student IN (SELECT id_student FROM iep_guardian WHERE id_guardian = '$sessIdUser') ";
						break;
					case UC_SC:
						$sqlStmt .= "id_ser_cord  = '$sessIdUser' OR ";
						$sqlStmt .= "id_ei_case_mgr = '$sessIdUser' OR ";
						$sqlStmt .= "id_student IN (SELECT id_student FROM iep_student_team WHERE id_personnel IN ('".implode("', '", $sessIdPersonnelArr )."') AND status = 'Active') ";
						break;
					case UC_SP:
					case UC_SS:
						#
						# SELECT SS STUDENTS (ONLY NEEDS TO BE DONE ONCE) - USER SPECIFIC NOT SCHOOL SPECIFIC
						#
						if($SSCount++ ==0) {
							if($i > 0) {
								$sqlStmt .= "OR ";
							}
							#if(1) { // SET TO 1 UNTIL SINGLE LOGIN IS MERGED IN (select id_personnel from iep_personnel where id_personnel_master = '$sessIdUser' AND status = 'Active')
							#debugLog("new UC_SS STMT");
							$sqlStmt .= "id_student IN (SELECT id_student FROM iep_student_team WHERE id_personnel IN ('".implode("', '", $sessIdPersonnelArr )."') AND status = 'Active')\n";
							#} else {
							#	$sqlStmt .= "id_student IN (SELECT id_student FROM iep_student_team WHERE id_personnel = '$sessIdUser' AND status = 'Active')\n";
							#}
						}
						break;
				case UC_CM:
					#
					# SELECT CM STUDENTS (ONLY NEEDS TO BE DONE ONCE) - USER SPECIFIC NOT SCHOOL SPECIFIC
					#
					if($caseMgrCount++ ==0) {
						if($i > 0) {
							$sqlStmt .= "OR ";
						}
						$sqlStmt .= "id_ser_cord  = '$sessIdUser' OR ";
						$sqlStmt .= "id_ei_case_mgr = '$sessIdUser' OR ";
						#if(1) { // SET TO 1 UNTIL SINGLE LOGIN IS MERGED
						#debugLog("new UC_CM STMT");
						$sqlStmt .= "id_case_mgr IN ('".implode("', '", $sessIdPersonnelArr )."') OR id_student IN (SELECT id_student FROM iep_student_team WHERE id_personnel IN ('".implode("', '", $sessIdPersonnelArr )."') AND status = 'Active')\n";
						#} else {
						#	$sqlStmt .= "id_case_mgr = '$sessIdUser' OR id_student IN (SELECT id_student FROM iep_student_team WHERE id_personnel = '$sessIdUser' AND status = 'Active')\n";
						#}
					}
					break;
				case UC_ASM:
				case UC_SM:
					$sqlStmt .= "id_county = '$searchCounty' AND id_district = '$searchDistrict' AND id_school = '$searchSchool'\n";
					break;
				case UC_ADM:
				case UC_DM:
					if (empty($count)) {
						$sqlStmt = "";
					} else {
						$sqlStmt .= "id_county = '$searchCounty' AND id_district = '$searchDistrict'\n";
					}
					break;
				case UC_SA:
//					Zend_debug::dump('here');
//					if (empty($count)) {
//						$sqlStmt = "";
//					} else {
						// edited sl 2003-03-20 in response to task 502
						// sys admin cannot see students with null status and some are popping up this way
						// need to keep some statement in here or query breaks
						$sqlStmt .= "status IS NOT NULL or status IS NULL\n";
//					}
					break;
				}
			}
		}
		$sqlStmt .= ") ";

		// 20080506 jlavere
		// transportation report
		//
//		if("reports" == $area && "transportation" == $sub && "Qualified for Transport" == $tranStatus) {
//			$sqlStmt .= " and 't' = (select transportation_yn from iep_form_004 where id_form_004 = (select most_recent_final_iep_id(s.id_student))) ";
//
//		} elseif("reports" == $area && "transportation" == $sub && "Did Not Qualify" == $tranStatus) {
//			$sqlStmt .= " and 'f' = (select transportation_yn from iep_form_004 where id_form_004 = (select most_recent_final_iep_id(s.id_student))) ";
//
//		} elseif("student" == $area && "list" == $sub && "No NSSRS ID#" == $search_other) {
//			$sqlStmt .= " and unique_id_state is null ";
//
//		} elseif("reports" == $area && "nssrs" == $sub && "No NSSRS ID#" == $search_other) {
//			$sqlStmt .= " and unique_id_state is null ";
//
//		} elseif("reports" == $area && "nssrs_transfers" == $sub && "No NSSRS ID#" == $search_other) {
//			$sqlStmt .= " and unique_id_state is null ";
//		}

		##############################################################################################################################################
		## ======================================================================================================================================== ##
		# ADD VARIABLE STATUS WHERE NOT ALREADY ADDED (FOR ASM AND BETTER)
		#
//		Zend_debug::dump($sessPrivCheckObj->minPrivUC_ASMorBetter());
		if($sessPrivCheckObj->minPrivUC_ASMorBetter()) {
			$sqlStmt .= $statusSQL;
		}
		##############################################################################################################################################
		##############################################################################################################################################

//		if("reports" == $area && "evaluation_date_report" == $sub) { // test building for eval report
//			$sqlStmt .= "and (most_recent_noticeofeval_createdate(id_student) >= get_most_recent_mdt_date_conference(id_student) OR
//	                (most_recent_noticeofeval_createdate(id_student) is not null and get_most_recent_mdt_date_conference(id_student) is null))";
//		}

		##############################################################################################################################################
		## ======================================================================================================================================== ##
		#	THIS IS AN OVERRIDE OF THE ABOVE CODE. IT IS DESIGNED TO MOVE THE SEARCH OFF OF THE STUDENT TABLE AN ON TO STUDENT_SEARCH
		#
		#$sqlStmt  = "SELECT DISTINCT id_county, id_district, id_school, name_case_mgr, name_county, name_district, name_school, name_full, name_last_first, address FROM student_search WHERE id_personnel = $sessIdUser ";
		/* $sqlStmt  = "SELECT distinct id_author, id_author_last_mod, timestamp_created, timestamp_last_mod, address_street1, address_street2, address_city, address_state, address_zip, date_last_iep, date_last_iep_update, date_last_mdt, dob, email_address, ethnic_group, exit_code, xxxgender, grade, id_case_mgr, id_county, id_district, id_school, id_student, name_first, name_middle, name_last, phone, primary_disability, primary_language, xxxprimary_language_family, program_provider, xxxstatus, ward, ward_surrogate, ward_surrogate_nn, ward_surrogate_other, id_list_team, id_list_guardian, status, gender, checkout_id_user, checkout_time, date_web_notify, id_student_local, change_type, last_auto_update, transition_plan, pub_school_student, id_case_mgr_old, id_team_list_old, sesis_exit_code, program_provider_name, program_provider_code, data_source, ssn, medicaid, ei_ref_date, eval_date, medicaid_off, ssn_off, id_ser_cord, id_ei_case_mgr, transitioned,  */
		/* 			get_name_personnel(id_case_mgr) AS name_case_mgr, get_name_county(id_county) as name_county, get_name_district(id_county, id_district) as name_district, get_name_school(id_county, id_district, id_school) as name_school, */
		/* 			CASE WHEN name_middle IS NOT NULL THEN name_first || ' ' || name_middle || ' ' || name_last ELSE name_first || ' ' || name_last END AS name_full, */
		/* 			name_last || ', ' || name_first as name_last_first, */
		/* 			address_street1 || ', ' || address_city || ' ' || CAST(address_state AS TEXT) || ', ' || CAST(address_zip AS TEXT) as address\n"; */
		/*  */
		/* if($sessUserMinPriv == UC_SA) { */
		/* 	$sqlStmt .= "FROM student_search WHERE id_personnel = $sessIdUser "; */
		/* } else { */
		/* 	$sqlStmt .= "FROM iep_student WHERE id_personnel = $sessIdUser "; */
		/*  */
		/* } */
		## ======================================================================================================================================== ##
		## ======================================================================================================================================== ##
		##############################################################################################################################################

		$errorArr = array();

		// add criteria that was submitted in the search form to the query
		$op = "LIKE"; // this will eventually be added to search form, but for now it's hard coded
		
//		Zend_debug::dump($showRowsSEARCH, 'showRowsSEARCH');
		
		for ($i=1; $i <= $showRowsSEARCH; $i++) {
			$v = $i - 1;
//			Zend_debug::dump($searchType, "searchType");
			if (!empty($showAll)) {
				$searchField[$v] = "";
				$searchValue[$v] = "";
			} else {
				if (!empty($searchField[$v])) {
					$value = empty($searchValue[$v]) ? "NULL" : strtolower($searchValue[$v]);
					if ( $i > 1 ) {
						$conditional = "$searchType";
					} else {
						$conditional = "AND";
					}
					#debugLog("SEARCH TYPE: ".$searchField[$v]);
					#debugLog("SEARCH VALUE: ".$searchValue[$v]);
						
					// CASE ADDED 1/28/03 FOR PUBLIC STUDENT SEARCH - JL
					if($searchField[$v] == "pub_school_student") {
						if ($value == "T" || $value == "t" || $value == "true") {
							$sqlStmt .= " $conditional ";
							$sqlStmt .= " pub_school_student = 'TRUE'\n";
						} elseif($value == "F" || $value == "f" || $value == "false") {
							$sqlStmt .= " $conditional ";
							$sqlStmt .= " pub_school_student = 'FALSE'\n";
						} else {
							$errorArr[] = "Bad value entered for Public School Student";
						}
							
					} elseif($searchField[$v] == "onteam") {
						//
						// search for students on this users team
						//
						$sqlStmt .= " $conditional ";
						$sqlStmt .= " id_student IN (SELECT id_student FROM iep_student_team WHERE id_personnel = '$value' AND status = 'Active') ";
							
					} elseif($searchField[$v] == "isCM") {
						//
						// search for students on this users team
						//
						$sqlStmt .= " $conditional ";
						$sqlStmt .= " id_case_mgr = '$value' ";
							
					} elseif($searchField[$v] == "isSC") {
						//
						// search for students on this users team
						//
						$sqlStmt .= " $conditional ";
						$sqlStmt .= " id_ser_cord = '$value' ";
							
					} elseif($searchField[$v] == "isEICM") {
						//
						// search for students on this users team
						//
						$sqlStmt .= " $conditional ";
						$sqlStmt .= " id_ei_case_mgr = '$value' ";
							
					} elseif($searchField[$v] == "id_student") {
						//
						// SCHOOL NAME
						//
						$sqlStmt .= " $conditional ";
						if ( substr_count($value, ",") > 0 ) {
							$sqlStmt .= " $searchField[$v] in ($value) \n";
						}  else {
							$sqlStmt .= " $searchField[$v] = '$value' \n";
						}

					} elseif($searchField[$v] == "s.grade") {
						//
						// GRADE
						//
						$sqlStmt .= " $conditional ";
						if ( substr_count($value, ",") > 0 ) {
							$sqlStmt .= " $searchField[$v] in ($value) \n";

						}  elseif('ei' == $value) {
							$sqlStmt .= " $searchField[$v] ilike 'ei 0-2' \n";

						}  elseif('ecse' == $value) {
							$sqlStmt .= " $searchField[$v] ilike '$value' \n";

						}  else {
							$sqlStmt .= " $searchField[$v] = '$value' \n";
						}

					} elseif($searchField[$v] == "s.gradegreaterthan") {
						//
						// GRADE GREATER THAN
						//
						$sqlStmt .= " $conditional ";

						if('ei' == $value) {
							$sqlStmt .= "(";
							$sqlStmt .= " s.grade ilike 'ei 0-2' OR \n";
							$sqlStmt .= " s.grade ilike 'ecse' OR \n";
							$sqlStmt .= " s.grade > '$value' \n";
							$sqlStmt .= ") ";

						}  elseif('ecse' == $value) {
							$sqlStmt .= "(";
							$sqlStmt .= " s.grade ilike 'ecse' OR \n";
							$sqlStmt .= " s.grade > '$value' \n";
							$sqlStmt .= ") ";

						}  else {
							$sqlStmt .= "(";
							$sqlStmt .= " s.grade > '$value' \n";
							$sqlStmt .= ") ";
						}

					} elseif($searchField[$v] == "s.gradelessthan") {
						//
						// GRADE LESS THAN
						//
						$sqlStmt .= " $conditional ";

						if('ei' == $value) {
							$sqlStmt .= " s.grade ilike 'ei 0-2' \n";

						}  elseif('ecse' == $value) {
							$sqlStmt .= "(";
							$sqlStmt .= " s.grade ilike '$value' OR \n";
							$sqlStmt .= " s.grade ilike 'ei 0-2' \n";
							$sqlStmt .= ") ";

						}  else {
							$sqlStmt .= "(";
							$sqlStmt .= " s.grade ilike 'ecse' OR \n";
							$sqlStmt .= " s.grade ilike 'ei 0-2' OR \n";
							$sqlStmt .= " s.grade < '$value' \n";
							$sqlStmt .= ") ";
						}

					} else {
						$sqlStmt .= " $conditional ";
						if ( is_numeric($value) ) { // use exact match for numeric search sl 8/25/2002
							//$sqlStmt .= " lower($searchField[$v]) = '$value'\n"; // 20070713 jlavere - lower removed for numerics
							$sqlStmt .= " $searchField[$v] = '$value'\n";
						}  else {
							$sqlStmt .= " lower($searchField[$v]) $op '$value%'\n";
						}
					}
				}
			}
		}

		// admin counter - flag on/off below
		$countSql = "Select count(*) $sqlStmt";


		// 20061124 jlavere - moved sort initial setting code to top
		//echo "sort: $sort<BR>";

		switch ($sort) {
			case "Name":
				$sortArray = array ("lower(name_last) ASC", "lower(name_first) ASC", "lower(id_student) ASC");
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
		//
		// created array 9/27/2002 sthoms
		//
		if(!empty($sqlStmt)) {
			$sqlStmt .= "ORDER BY ";
			for ($i=0; $i < count($sortArray); $i++) {
				$sqlStmt .= $sortArray[$i];
				if ( $i < count($sortArray) - 1 ) {
					$sqlStmt .= ",";
				}
			}
			#$sqlStmt .= " limit $sessStudentSearch->sessCurrentStudentMaxRecs;\n";
			$sqlStmt .= ";\n";
		}
		$sqlStmt = $sqlStmtTop . $sqlStmt;
		#echo "countSql: $countSql<BR><BR>";
//		if(0 && 1006628 ==$sessIdUser)
//		{
//			echo '<font size="1" face="Monaco">';
//			echo "<B>sqlStmt: </B>$sqlStmt<BR><BR>";
//			echo '</font>';
//			echo '<font size="1" face="Monaco">';
//			echo "<B>psqlStmt: </B>$psqlStmt<BR><BR>";
//			echo '</font>';
//		}
		/*
		 #
		 # for testing, run following line from psql cmd line:
		 #
		 \i '/usr/apachesecure0/htdocs/srs/archive/vere/sqlstmt.txt'
		 */
		#file_put_contents( "archive/vere/sqlstmt.txt", "explain analyze " . $sqlStmt);
//		if(!$sessPrivCheckObj->minPrivUC_SA() && $sessUserMinPriv != UC_PG) {
//			// if user is NOT super user
//			// I am not sure why the PG restriction is there. jlavere 20061112
//			$sqlStmt = $psqlStmt;
//			outputLogic("sqlStmt replaced with psqlStmt");
//		} else {
//			outputLogic("sqlStmt used");
//		}
//		echo $sqlStmt."<BR>";
		//if($sessIdUser == '1000254') print($sqlStmt);
		// done building a new SQL stmt
		
		
//		echo $sqlStmt;
// die();
		return $this->performQuery($sqlStmt);
	}

    private function performQuery($sql)
    {
    		try
    		{
    			$db = Zend_Registry::get('db');
                $results = $db->fetchAll($sql);
    		}
    		catch (Zend_Db_Statement_Exception $e) {
    			// should log errors
    			// generate error
    			// but as long as we're not
    			// don't swallow the exception
    			throw new Zend_Db_Statement_Exception($e);
    		}
    	
    	return $results;	
    } 
	
	static public function devDelay($startDate, $endDate) {
			//	Zend_debug::dump($startDate, 'startDate');
			//	Zend_debug::dump($endDate, 'endDate');
			
			if( $startDate == '' || $endDate == '') return "";
			$today = getdate(strtotime($endDate));
			$doy = $today['yday'];
			$mday = $today['mday'];
			$year = $today['year'];
			$month = $today['mon'];
	
			$b_day = getdate(strtotime($startDate));
			$b_doy = $b_day['yday'];
			$b_mday = $b_day['mday'];
			$b_year = $b_day['year'];
			$b_month = $b_day['mon'];
	
			# if ( $doy < $b_doy ) {
			# This code seems suspect - if both days are
			# March 1, but startDate is in a leap year,
			# and endDate is not, then $doy < $b_doy, but
			# we still want $age = $year-$b_year. - thomaso
			# Probably should be keyed off month days.
			if ($b_month>$month or ($b_month==$month and $b_mday>$mday)) {
                $age = $year - $b_year - 1;
			} else {
                $age = $year - $b_year;
			}
	
			if ($mday<$b_mday) {
			   $month=$month-1;
			   if ($month<1) { $month=12; }
			}
	
			if( ($month - $b_month) < 0 ) {
                $months = $month - $b_month + 12;
			} else {
                $months = $month - $b_month;
			}
		
			if($age < 3) {
				return 0;		
			} elseif($age < 6) {
				return 1;		
			} elseif($age < 22) {
				return 2;		
			} else {
				return 3;		
			}
	}

  // Time format is UNIX timestamp or
  // PHP strtotime compatible strings
  static function dateDiff($time1, $time2, $precision = 6) {
    // If not numeric then convert texts to unix timestamps
    if (!is_int($time1)) {
      $time1 = strtotime($time1);
    }
    if (!is_int($time2)) {
      $time2 = strtotime($time2);
    }
 
    // If time1 is bigger than time2
    // Then swap time1 and time2
    if ($time1 > $time2) {
      $ttime = $time1;
      $time1 = $time2;
      $time2 = $ttime;
    }
 
    // Set up intervals and diffs arrays
    $intervals = array('year','month','day','hour','minute','second');
    $diffs = array();
 
    // Loop thru all intervals
    foreach ($intervals as $interval) {
      // Set default diff to 0
      $diffs[$interval] = 0;
      // Create temp time from time1 and interval
      $ttime = strtotime("+1 " . $interval, $time1);
      // Loop until temp time is smaller than time2
      while ($time2 >= $ttime) {
	$time1 = $ttime;
	$diffs[$interval]++;
	// Create new temp time from time1 and interval
	$ttime = strtotime("+1 " . $interval, $time1);
      }
    }
 
    $count = 0;
    $times = array();
    // Loop thru all diffs
    foreach ($diffs as $interval => $value) {
      // Break if we have needed precission
      if ($count >= $precision) {
	break;
      }
      // Add value and interval 
      // if value is bigger than 0
      if ($value > 0) {
	// Add s if value is not 1
	if ($value != 1) {
	  $interval .= "s";
	}
	// Add value and interval to times array
	$times[$interval] = $value;
	$count++;
      }
    }
 return $times;
    // Return string with times
    return implode(", ", $times);
  }
	
  /**
   * Get District For Student Id
   * @param int $idStudent
   * @return int
   */
  public function getDistrictForStudentId($idStudent) {
   		$student = $this->getStudent($idStudent);
   		return $student['id_district'];
  }
  
}