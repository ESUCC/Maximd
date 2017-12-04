<?php


//class NssrsTransfer extends Zend_Db_Table_Abstract {
class App_Student_NssrsTransfer extends Zend_Db_Table_Abstract {

    protected $_name = 'nssrs_transfers';
    public $fieldArr;

    function __construct()
    {
        $this->fieldArr = array(
            'timestamp_created',
            'timestamp_last_mod',
            'nssrs_001',
            'nssrs_002',
            'nssrs_003',
            'nssrs_004',
            'nssrs_005',
            'nssrs_006',
            'nssrs_007',
            'nssrs_008',
            'nssrs_009',
            'nssrs_010',
            'nssrs_011',
            'nssrs_012',
            'nssrs_013',
            'nssrs_014',
            'nssrs_015',
            'nssrs_016',
            'nssrs_017',
            'nssrs_018',
            'nssrs_019',
            'nssrs_020',
            'nssrs_021',
            'nssrs_022',
            'nssrs_023',
            'nssrs_024',
            'nssrs_025',
            'nssrs_026',
            'nssrs_027',
            'nssrs_028',
            'nssrs_029',
            'nssrs_030',
            'nssrs_031',
            'nssrs_032',
            'nssrs_033',
            'nssrs_034',
            'nssrs_035',
            'nssrs_036',
            'nssrs_037',
            'nssrs_038',
            'nssrs_039',
            'nssrs_040',
            'nssrs_041',
            'nssrs_042',
            'nssrs_043',
            'nssrs_044',
            'nssrs_045',
            'nssrs_046',
            'nssrs_047',
            'nssrs_048',
            'nssrs_049',
            'nssrs_050',
            'nssrs_051',
            'nssrs_052',
            'id_author',
            'id_author_last_mod',
            'address_street1',
            'address_street2',
            'address_city',
            'address_state',
            'address_zip',
            'date_last_iep',
            'date_last_iep_update',
            'date_last_mdt',
            'dob',
            'email_address',
            'ethnic_group',
            'exit_code',
            'xxxgender',
            'grade',
            'id_case_mgr',
            'id_county',
            'id_district',
            'id_school',
            'id_student',
            'name_first',
            'name_middle',
            'name_last',
            'phone',
            'primary_disability',
            'primary_language',
            'xxxprimary_language_family',
            'program_provider',
            'xxxstatus',
            'ward',
            'ward_surrogate',
            'ward_surrogate_nn',
            'ward_surrogate_other',
            'id_list_team',
            'id_list_guardian',
            'status',
            'gender',
            'checkout_id_user',
            'checkout_time',
            'date_web_notify',
            'id_student_local',
            'change_type',
            'last_auto_update',
            'transition_plan',
            'pub_school_student',
            'id_case_mgr_old',
            'id_team_list_old',
            'data_source',
            'sesis_exit_code',
            'program_provider_name',
            'program_provider_code',
            'ssn',
            'medicaid',
            'ei_ref_date',
            'eval_date',
            'medicaid_off',
            'ssn_off',
            'id_ser_cord',
            'id_ei_case_mgr',
            'transitioned',
            'nonpubcounty',
            'nonpubdistrict',
            'nonpubschool',
            'vere_ss_update',
            'sesis_exit_date',
            'program_provider_id_school',
            'ell_student',
            'unique_id_state',
            'unique_id_state_duplicate',
            'id_county_orphan',
            'id_district_orphan',
            'id_school_orphan',
            'parental_placement',
            'name_student_full',
            'age',
            'address'
        );
    }

    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    public function insert_transfer($sesisData, $studentData) {
        // build temp data array from other arrays
        $tmpData = array();
        foreach($sesisData as $key => $value)
        {
            $tmpData['nssrs_'.$key] = $value;
        }
        foreach($studentData as $key => $value)
        {
            $tmpData[$key] = $value;
        }

        $insertData = array();
        foreach($this->fieldArr as $key)
        {
            $insertData[$key] = $tmpData[$key];
        }

        $data = array_combine($this->fieldArr, $insertData);
        $nssrsTransfersObj = new Model_Table_NssrsTransfers();
        
        
        /*
         * Mike added this 12-3-2017 SRS-141 date issue in line 34 of the nesses report
         * Please note that the code does get the date elsewhere, but if nobody exists the students 
         * then transfering the student does not disable the student or create an exist date.  Therefore
         * unless this happens this field will always be null.  
         */
        if($data['nssrs_034']==null ) {
            $date=date("Y-m-d");
        
            $data['nssrs_034']=$date;
        }  
      //  $this->writevar1($data,'this is the data line 177 nssrstransfer app-student');
        $transferId = $nssrsTransfersObj->insert($data);
        
        $transfer = $nssrsTransfersObj->find($transferId);
        if(count($transfer)) {
            return $transfer->current()->toArray();
        } else {
            return false;
        }
    }

    public function getTransfer($idStudent) {
        $nssrsTransfersObj = new Model_Table_NssrsTransfers();
        $transfer = $nssrsTransfersObj->mostRecentForm($idStudent);
        if(!is_null($transfer) && count($transfer)) {
            return $transfer->toArray();
        } else {
            return array();
        }
    }

//    public function insert_blank_transfer($sesisData) {
//        global $sessIdUser;
//        //
//        // build temp data array from other arrays
//        //
//        $tmpData = array();
//        foreach($sesisData as $key => $value)
//        {
//            //echo "key:$key<BR>";
//            $tmpData['nssrs_'.$key] = $value;
//        }
////         foreach($studentData as $key => $value)
////         {
////         //echo "key:$key<BR>";
////         $tmpData[$key] = $value;
////         }
//
//
//        $insertData = array();
//
//        foreach($this->fieldArr as $key)
//        {
//            $insertData[$key] = $tmpData[$key];
//        }
//        $insertData['timestamp_created'] = 'now';
//        $insertData['timestamp_last_mod'] = 'now';
//
//        $sqlFields = implode(',' , $this->fieldArr);
//
//        $fieldValues = '\'' . implode('\', \'', $insertData) . '\'';
//
//        $sqlStmt  =	"INSERT INTO nssrs_transfers (".$sqlFields.")\n";
//        $sqlStmt .= "VALUES (".$fieldValues.")\n";
//#        echo "sqlStmt: $sqlStmt<BR>";
//#        die();
//
//        if($result = sqlExec($sqlStmt, $errorId, $errorMsg, true, true)) {
//            $oid = pg_getlastoid($result);
//            #vecho("oid: $oid");
//            $sqlStmt = "SELECT id_nssrs_transfers FROM nssrs_transfers WHERE oid = $oid;";
//            #vecho("sqlStmt: $sqlStmt");
//            if($result = sqlExec($sqlStmt, $this->errorId, $this->errorMsg, true, true, true)) {
//                $arrData = pg_fetch_array($result, 0);
//                return $arrData[0];
//            } else {
//                return false;
//            }
//            return true;
//        } else {
//            return false;
//        }
//
//        #pre_print_r($sesisData);
//        #pre_print_r($studentData);
//        #pre_print_r($insertData);
//        return true;
//    }
//
//    public function get_transfer($id_transfer) {
//
//        $stmt = "SELECT * FROM nssrs_transfers WHERE id_nssrs_transfers = '$id_transfer'";
//        //vecho("stmt: $stmt");
//        if (!$result = sqlExec( $stmt, $errorId, $errorMsg, true, true, true ) ) {
//            include_once("error.php");
//            exit;
//        }
//        $arrData = pg_fetch_array($result, 0, PGSQL_ASSOC);
//        return $arrData;
//    }
//
//    public function delete_transfer($pkey) {
//
//        $delsqlStmt  = "DELETE\n";
//        $delsqlStmt .= "FROM nssrs_transfers\n";
//        $delsqlStmt .= "WHERE id_nssrs_transfers = '$pkey';\n";
//        if(!$delresult = sqlExec($delsqlStmt, $errorId, $errorMsg, true, true)) {
//            return false;
//        }
//        return true;
//    }
//
//    function saveStudentData($transferID, $data)
//    {
//        if('' == $transferID) return false;
//
//        $pkeyName	= "id_nssrs_transfers";
//        $tableName	= "nssrs_transfers";
//
//        $arrFieldList = array();
//        foreach($this->fieldArr as $fieldName)
//        {
//            //echo "checking: $fieldName<BR>";
//            if(isset($data[$fieldName])) $arrFieldList[$fieldName] = array('','','','');
//
//        }
//        if (!$save = $this->insertOrUpdate($transferID, $arrFieldList, $data, $tableName, $pkeyName)) {
//            $errorId = $saveStdObj->errorId;
//            $errorMsg = $saveStdObj->errorMsg;
//            include_once("error.php");
//            exit;
//        }
//
//
//    }
//
//    function insertOrUpdate(&$pkey, &$arrFieldList, &$arrData, &$tableName, &$pkeyName, $sqlStmt = "") {
//        global $sessIdUser;
//        // if no stmt is supplied, build default stmt
//        if (empty($sqlStmt)) {
//            reset($arrFieldList);
//
//            if ($pkey) {
//                //print_r( $arrFieldList );
//                //print_r( $arrData );
//                $sqlStmt = $this->buildUpdateStmt($pkey, $arrFieldList, $arrData, $tableName, $pkeyName);
//            } else {
//                $sqlStmt = $this->buildInsertStmt($arrFieldList, $arrData, $tableName, $pkeyName);
//            }
//        }
//
//        // execute stmt
//        #print( "save student sqlStmt: ".str_replace("\n", "<BR>", $sqlStmt));
//        #die();
//        if($result = sqlExec($sqlStmt, $this->errorId, $this->errorMsg, true, true)) {
//            // if new record, get id of record just inserted
//            if (empty($pkey)) {
//                $oid = pg_getlastoid($result);
//                $sqlStmt = "SELECT $pkeyName FROM $tableName WHERE oid = $oid;";
//                if($result = sqlExec($sqlStmt, $this->errorId, $this->errorMsg, true, true)) {
//                    $arrData = pg_fetch_array($result, 0);
//                    return $arrData[0];
//                } else {
//                    return false;
//                }
//            } else {
//                $logType = 3;
//                $tableName = $tableName;
//                if (writeLog($pkey, $logType, $tableName, $this->errorId, $this->errorMsg)) {
//                    return true;
//                } else {
//                    return false;
//                }
//            }
//        } else {
//            #debugLog("FAILED");
//            return false;
//        }
//    }
//
//    /* build INSERT statement */
//    function buildInsertStmt(&$arrFieldList, &$arrData, &$tableName, &$pkeyName) {
//
//        reset($arrFieldList);
//
//        $sqlStmt = 	"INSERT INTO $tableName (id_author, id_author_last_mod, ";
//        while (list($fieldName, $value) = each($arrFieldList)) {
//            if ($i++) {
//                $sqlStmt .= ", ";
//            }
//            $sqlStmt .= $fieldName;
//        }
//        $sqlStmt .= ")\nVALUES ('0', '0', ";
//        reset($arrFieldList);
//        $i = 0;
//        while (list($fieldName, $value) = each($arrFieldList)) {
//            if ($i++) {
//                $sqlStmt .= ", ";
//            }
//            $dataElement = addslashes( stripslashes( $arrData[$fieldName] ) );
//            $sqlStmt .= "'$dataElement'";
//        }
//        $sqlStmt .= ");\n";
//
//        return $sqlStmt;
//    }
//
//    /* build UPDATE statement */
//    function buildUpdateStmt(&$pkey, &$arrFieldList, &$arrData, &$tableName, &$pkeyName) {
//
//        global $sessIdUser;
//
//        reset($arrFieldList);
//        $sqlStmt = 	"UPDATE $tableName\nSET ";
//        while (list($fieldName, $value) = each($arrFieldList)) {
//            if ($i++) {
//                $sqlStmt .= ", ";
//            }
//            $dataElement = addslashes( stripslashes( $arrData[$fieldName] ) );
//            $sqlStmt .= $fieldName . " = '" . $dataElement . "'";
//        }
//        $sqlStmt .= "\nWHERE $pkeyName = $pkey;\n";
//
//        return $sqlStmt;
//    }

}