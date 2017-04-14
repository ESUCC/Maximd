<?
//$sesisObj = &new sesis_snapshot('1000098');

require_once('class_sesis_validation.php');

#
# USAGE
#
# student_student 			- doSnapshot WAS called on confirmTransition (commented out)
# student_confirm_transfer	- doSnapshot called on confirm transfer
# include_form_head1		- doSnapshot called on finalize of form_009
# form_004 pg 6 			- devDelay called staticly

class SesisSnapshot //sesis_snapshot
{
    //	FUNCTION REQUIRES SQLEXEC FUNCTIONS TO QUERY DATABASE
    //	USER CLASS CONSTANTS ARE ALSO REQUIRED FOR CLASS SWITCHES TO WORK
    var $id_student;
    var $id_county;
    var $id_district;
    var $studentData;

    function sesis_snapshot($id_student, $id_county, $id_district) {

        $this->id_student = $id_student;
        $this->id_county = $id_county;
        $this->id_district = $id_district;
        $this->studentData = array();

        $this->getStudentData();
    }
    function doSnapshot($type = "default") {
        // IF THE DISTRICT IS USING THE SESIS SNAPSHOT
        // THEN DO THE SNAPSHOT

        if($this->snapshotAvailable($this->id_county, $this->id_district)) {
            //
            // COLLECT THE SESIS DATA
            $sesisData = sesis_collect2($this->studentData, $sesisComplete, $sesisFailedArr);

            #
            # RUN VALIDATION TO SEE IF THIS STUDENT IS COMPLETE
            #
            $sesisValObj = new sesis_validation($sesisData);
            $sesisComplete = $sesisValObj->check_all_pass();
            if($sesisComplete) {
                #
                # CLEAR THE FORMS DATA (NOT USED IN THE DATA SENT TO STATE)
                #
                #unset($sesisData['forms_data']);
                #
                # STORE THE SESIS DATA IN A LOCAL VAR
                #
                #$this->outputData .= implode(",", $sesisData) . "\r\n";
                if("confirm_transfer" == $type) {
                    // write in sesis exit code
                    $sesisData['056'] = 1;
                    // update sesis exit date
                    $sesisData['055'] = date_massage("now");
                }
                //
                // INSERT THE DATA INTO THE SNAPSHOT TABLE
                //
                $this->sesis_insert($this->studentData, $sesisData);
            }
            return true;
        } else {
            //echo "SESIS not available id_student:{$this->id_student} county:{$this->id_county} disrtict:{$this->id_district}<BR>";
            return false;
        }
    }
    function getStudentData() {
        #debugLog("GET STUDENT DATA FUNCTION");
        $keyName	= "student";
        $objName	= "objStudent";
        $pkey		= isset($student)?$student:"";
        $pkeyName	= "id_student";
        $tableName	= "iep_student";

        include_once("iep_class_student.inc");
        //
        // GET STUDENT RECORD
        $objStudent = &new student();
        $pkey = $this->id_student;
        $mode = 'edit';
        #debugLog("GET STUDENT RECORD");
        if ($pkey) {
            // 20071120 jlavere - changed checkout to false as it was killing the done button
            if (!$objStudent->select($pkey, $mode, $this->studentData, $tableName, $pkeyName, false, $checkout = false, $sqlStmt = "", $forceOverrideAccessLvl=1)) {
                $errorId = $$objName->errorId;
                $errorMsg = $$objName->errorMsg;
                include_once("error.php");
                exit;
            } else {
                //pre_print_r($this->studentData);
                //$this->studentData = $studentData;
            }
        }
        #debugLog("END STUDENT DATA FUNCTION");

    }
    function devDelay($startDate, $endDate) {

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
    function snapshotAvailable($id_county, $id_district) {
        //
        // CHECK AVAILABILITY
        $sqlStmt = "SELECT use_sesis_snapshot from iep_district where id_county = '$id_county' and id_district = '$id_district';";
        if(!$result = sqlExec($sqlStmt, $errorId, $errorMsg, true, false)) {
            include_once("error.php");
            exit;
        } else {
            //
            // GET use_sesis_snapshot TO DETERMINE IF SNAPSHOT AVAILABLE
            $data = pg_fetch_array($result, 0);
            $available = $data[0];
            if($available) {
                return true;
            } else {
                return false;
            }
        }
    }
    function getSnapshotID($table) {
        if($oid = $this->getOID($table)) {
            //
            // GET SESIS SNAPSHOT ID
            $sqlStmt = "select id_sesis_snapshot from $table WHERE oid = $oid;";
            if(!$result = sqlExec($sqlStmt, $this->errorId, $this->errorMsg, true, false)) {
                return false;
            } else {
                $data = pg_fetch_array($result, 0);
                return $data[0];
            }

        } else {
            return false;
        }
    }

    function getOID($table, $sequence) {
        //
        // GET OID

        $sqlStmt = "select currval('$sequence'::text);";
        if(!$result = sqlExec($sqlStmt, $this->errorId, $this->errorMsg, true, false)) {
            //echo "select currval('$sequence'::text);<BR>";
            return false;
        } else {
            //
            // GET use_sesis_snapshot TO DETERMINE IF SNAPSHOT AVAILABLE
            $data = pg_fetch_array($result, 0);
            #debugLog("OIDOIDOIDOIDOIDOIDOID OID: ".$data[0]);
            return $data[0];
        }
    }
    // ==============================================================================
    // ==============================================================================
    // ==============================================================================
    // ==============================================================================
    function sesis_insert($studentData, $sesisData) {

        $fieldList = array(	//
            //
            // STUDENT FIELDS
            //
            "id_author",
            "id_author_last_mod",
            //"timestamp_created",
            "timestamp_last_mod",
            "address_street1",
            "address_street2",
            "address_city",
            "address_state",
            "address_zip",
            "date_last_iep",
            "date_last_iep_update",
            "date_last_mdt",
            "dob",
            "email_address",
            "ethnic_group",
            "exit_code",
            "xxxgender",
            "grade",
            "id_case_mgr",
            "id_county",
            "id_district",
            "id_school",
            "id_student",
            "name_first",
            "name_middle",
            "name_last",
            "phone",
            "primary_disability",
            "primary_language",
            "xxxprimary_language_family",
            "program_provider",
            "xxxstatus",
            "ward",
            "ward_surrogate",
            "ward_surrogate_nn",
            "ward_surrogate_other",
            "id_list_team",
            "id_list_guardian",
            "status",
            "gender",
            "checkout_id_user",
            "checkout_time",
            "date_web_notify",
            "id_student_local",
            "change_type",
            "last_auto_update",
            "transition_plan",
            "pub_school_student",
            "id_case_mgr_old",
            "id_team_list_old",
            "sesis_exit_code",
            "program_provider_name",
            "program_provider_code",
            "data_source",
            "ssn",
            "medicaid",
            "ei_ref_date",
            "eval_date",
            "medicaid_off",
            "ssn_off",
            "id_ser_cord",
            "id_ei_case_mgr",
            "transitioned",
            //
            //
            // SESIS FIELDS
            //
            "001",
            "002",
            "003",
            "004",
            "005",
            "006",
            "007",
            "008",
            "009",
            "010",
            "011",
            "012",
            "013",
            "014",
            "015",
            "016",
            "017",
            "018",
            "019",
            "020",
            "021",
            "022",
            "023",
            "024",
            "025",
            "026",
            "027",
            "028",
            "029",
            "030",
            "031",
            "032",
            "033",
            "034",
            "035",
            "036",
            "037",
            "038",
            "097",
            "098",
            "099",
            "100",
        );

        $mergedData = array_merge($sesisData, $studentData);
        // 	foreach($fieldList as $fieldName) {
        // 		echo "mergedData $fieldName: ". $mergedData[$fieldName] . "<BR>";
        // 	}

        $fieldCount = count($fieldList);
        //echo "fieldCount: $fieldCount<BR>";
        //
        // BUILD THE INSERT STATEMENT
        //
        $sqlStmt = "INSERT INTO iep_sesis_snapshot2 (";
        $i = 0;
        foreach($fieldList as $fieldName) {
            if($i == $fieldCount-1) {
                $sqlStmt .= "\"$fieldName\" ";
            } else {
                $sqlStmt .= "\"$fieldName\", ";
            }
            $i++;
        }
        $sqlStmt .= ") VALUES ( ";
        $i = 0;
        foreach($fieldList as $fieldName) {
            if($i == $fieldCount-1) {
                $sqlStmt .= "'".addslashes($mergedData[$fieldName])."' ";
            } else {
                $sqlStmt .= "'".addslashes($mergedData[$fieldName])."', ";
            }
            $i++;
        }
        $sqlStmt .= ");";
        //echo $sqlStmt . "<BR>";
        //
        //
        // EXECUTE THE STATEMENT
        //
        if(!$result = sqlExec($sqlStmt, $errorId, $errorMsg, true, false)) {
            $errorMsg = "error iep_sesis_snapshot2.";
            include_once("error.php");
            exit;
        } else {
            //$id_sesis_snapshot = $this->getOID('iep_sesis_snapshot2', 'iep_sesis_sna_id_sesis_snap_seq');
            //return $this->getOID('iep_sesis_snapshot2');
        }
    }
    // ==============================================================================
    // ==============================================================================
    // ==============================================================================
}
?>