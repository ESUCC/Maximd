<?
require_once('class_form_element.php');
require_once(APPLICATION_PATH . '/models/DbTable/iep_personnel.php');
require_once(APPLICATION_PATH . '/models/DbTable/iep_student.php');
require_once(APPLICATION_PATH . '/../library/My/Classes/privCheck.php');

class studentFormManagement {

    var $formDescriptions = array(
        '001'	=> "Notice and Consent for Initial Evaluation (IEP)", 
        '002'	=> "Multidisciplinary Evaluation Team (MDT) Report", 
        '003'	=> "Notification of IEP Meeting", 
        '004'	=> "Individual Education Program (IEP)", 
        '005'	=> "Notice and Consent for Initial Placement (IEP)", 
        '006'	=> "Notice of School District&rsquo;s Decision", 
        '007'	=> "Notice and Consent for Reevaluation", 
        '008'	=> "Notice of Change of Placement", 
        '009'	=> "Notice of Discontinuation", 
        '010'	=> "Progress Report", 
        '011'	=> "Notice of MDT Conference", 
        '012'	=> "Determination Notice", 
        '013'	=> "IFSP", 
        '014'	=> "Notification of IFSP Meeting (IFSP)", 
        '015'	=> "Notice and Consent for Initial Evaluation (IFSP)", 
        '016'	=> "Notice and Consent for Initial Placement (IFSP)", 
        '017'	=> "Notes Page", 
        '018'	=> "Summary of Performance", 
        '019'	=> "Functional Assessment", 
        '020'	=> "Specialized Transportation", 
        '021'	=> "Assistive Technology Considerations", 
        '022'	=> "MDT Data Card", 
        '023'	=> "IEP Data Card", 
        '024'	=> "Agency Consent Invitation", 
    );

    
//    function buildIsOnTeam($sessIdUser, $idStudent)
//    {
////        require_once("iep_function_general.php");
//    
//        #
//        # IS ON TEAM - USED IN FORM ACCESS
//        #
//        $this->isOnTeam = iep_personnel::isOnTeam($sessIdUser, $idStudent);
//        return $this->isOnTeam;
//
//    }
//    
//    
//    function buildCanCreate($sessIdUser, $pkey)
//    {
//        if ($this->isOnTeam) {
//            #
//            # CHECK TO SEE IF THIS PERSON HAS AN ACTIVE RECORD ON THIS STUDENT TEAM
//            #
//            $sqlStmt = "SELECT * from iep_student_team where id_student='$pkey' and id_personnel='$sessIdUser' and status='Active'";
//            if($pgDataArr = My_Classes_iepFunctionGeneral::xmlRpcslqExec($sqlStmt, $errorId, $errorMsg, true, $errorNoResults=false)) {
//                $ast = $pgDataArr[0];
//            } else {
//                include_once("error.php");
//                exit;
//            }
//        
//            $this->canCreate = $ast['flag_create'];
//            return $this->canCreate;
//        }
//    }
    
    
    function availableForms($sessIdUser, $pkey)
    {
//        global $sessPrivCheckObj, $sessIdUser;
        
        $sessUser = new Zend_Session_Namespace('user');
        $sessPrivCheckObj = $sessUser->sessPrivCheckObj;
//        $sessIdUser = $sessUser->id_personnel;
        
//        Zend_debug::dump($sessPrivCheckObj);
//        Zend_debug::dump($sessPrivCheckObj->accessObj);
        if(isset($sessPrivCheckObj->accessObj) ) {
            $availableForms = array_keys($sessPrivCheckObj->accessObj->availableForms($sessPrivCheckObj->accessArray));
            $this->availableForms = $availableForms;
        } else {
            $availableForms = array();
        }
        return $availableForms;
    }

    function availableCreateForms($sessIdUser, $pkey)
    {
//        global $sessPrivCheckObj;
        $sessUser = new Zend_Session_Namespace('user');
        $sessPrivCheckObj = $sessUser->sessPrivCheckObj;
        

        if(isset($sessPrivCheckObj->accessObj) ) {
        	$availableCreateForms = $sessPrivCheckObj->accessObj->availableForms($sessPrivCheckObj->accessArray, 'new');
        } else {
            $availableCreateForms = array();
        }

        $this->availableCreateForms = $availableCreateForms;
        return $availableCreateForms;
    }

    function buildOptionalForms($id_county, $id_district)
    {
        #
        # optional forms
        #
        $sqlStmtDist = "SELECT use_form_011, use_form_012, use_form_019, use_form_020, use_form_021 from iep_district where id_district = '".$id_district."' and id_county = '".$id_county."';";
        
        if(1)
        {
            if(!$resultDist = My_Classes_iepFunctionGeneral::xmlRpcslqExec($sqlStmtDist, $errorId, $errorMsg, true, $errorNoResults=false)) {
                include_once("error.php");
                exit;
            } else {
                $numRowsDist = count($resultDist);
                if ( $numRowsDist > 0 ) {
                    $arrDataDist = $resultDist[0];
                }
            }
        } else {
            $result = My_Classes_iepFunctionGeneral::xmlRpcslqExec($sqlStmt, $errorId, $errorMsg, true, false);
            if(false === $result) {
                include_once("error.php");
                exit;
            } else {
                $numRowsDist = count($result);
                //echo "nrD $numRowsDist";
                if ( $numRowsDist > 0 ) {
                    $arrDataDist = $resultDist[0];
                }
            }
        
        }
        $this->arrDataDist = $arrDataDist;
        return $arrDataDist;
        
    }

    function buildForcedOverride($id_ei_case_mgr, $sessIdUser)
    {
    
        $forceOverrideAccessLvl = 0; 	// DEFAULT VALUE, PROVIDES NO ADDITIONAL ACCESS
        $EIidCaseMgr = false; 			// DEFAULT VALUE, PROVIDES NO ADDITIONAL ACCESS

        //	ALLOW ACCESS IF EICM OR SC FOR STUDENT															//

        if($id_ei_case_mgr == $sessIdUser) {

            //	USER IS STUDENT'S EI CASE MGR																//
            //																								//
            $forceOverrideAccessLvl = UC_SC;	// GRANTS ACCESS IN BUILDOPTIONLIST
            $EIidCaseMgr = $ad['id_ei_case_mgr'];		// GRANTS GREATER ACCESS IN BUILDOPTIONLIST

        } elseif($id_ser_cord == $sessIdUser) {

            //	USER IS STUDENT'S SERVICE COORDINATOR														//
            //																								//
            $forceOverrideAccessLvl = UC_SC;	// GRANTS ACCESS IN BUILDOPTIONLIST
        }
        $this->forceOverrideAccessLvl = $forceOverrideAccessLvl;
        return $forceOverrideAccessLvl;
    
    }


    function buildFormsArray($view, $sessUserMinPriv, $pkey, $id_student_local, $area, $sub, $restore = false, $restoreDocument = '')
    {
        global $UC_PG, $sessPrivCheckObj, $sessUserMinPriv, $DOC_ROOT, $sessIdUser;
        
        $this->displayCreateForms = false;
        
        if( is_array($this->availableCreateForms) ) {
            $this->displayCreateForms = true;
            $createForms = array_keys($this->availableCreateForms);
            $this->createForms = $createForms;
            #pre_print_r($createForms);
            #
            # CHECK CREATE FORMS FOR SPECIAL PROCESSING OF PROGRESS REPORTS
            #
            #pre_print_r($createForms);
            if($prKey = array_search('010', $createForms)) {
                #
                # DISPLAY PR BELOW?
                #
                $this->createProgRpts = true;
                #
                # REMOVE PR FROM THE VIEWABLE FORMS
                #
                unset($createForms[$prKey]);
            }
            #
            # CHECK AVAILABLE FORMS FOR SPECIAL PROCESSING OF PROGRESS REPORTS
            #
            if($prKey = array_search('010', $this->availableForms)) {
                #
                # DISPLAY PR BELOW?
                #
                $this->displayProgRpts = true;
                #
                # REMOVE PR FROM THE VIEWABLE FORMS
                #
                unset($this->availableForms[$prKey]);
            }
        }
    

        
        if($view == "Deleted" || $view == "current" || "Archived") {
            
        } elseif($view == '' || !in_array(substr($view, -3, 3), $this->availableForms)) {
            $view = 'all';
        }
        
        if (isset($view)) {
            // build stmt
            if ($view == "all" || $view == "current") {
                if(count($this->availableForms) > 0) {
                    $formAllowed = true;
                    
                    /*
                     ** 20050321 - JLAVERE
                     ** add code to limit search to last 13 months when current forms are selected.
                     */
                    if("current" == $view) {
                        $dateLimitText = " and timestamp_created >= to_timestamp('".date('m/d/Y', strtotime("today-13 month"))."', 'MM/DD/YYYY') ";
                        #echo "dateLimitText: $dateLimitText<BR>";
                    }
                    // parents and guardians may only see finalized forms
                    // CAST UPDATED TO CHAR(3) rather than original CHAR, which in postgres 7.2 seems to be implicitly char(1) which breaks
                    if ($sessUserMinPriv == $UC_PG) {
                        $sqlStmt = "";
                        $firstRun = true;
                        foreach($this->availableForms as $formNum) {
                            if($firstRun) {
                                $firstRun = false;
                            } else {
                                $sqlStmt .= " UNION ALL ";
                            }
                            switch($formNum) {
                                case '001':
                                    $sqlStmt .= "SELECT CAST('001' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_001 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_001 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '002':
                                    $sqlStmt .= "SELECT CAST('002' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_002 as id, status, '' as title, date_mdt as date, page_status, id_case_mgr, CASE WHEN date_mdt IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_002 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '003':
                                    $sqlStmt .= "SELECT CAST('003' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_003 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_003 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '004':
                                    $sqlStmt .= "SELECT CAST('004' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_004 as id, status, '' as title, date_conference as date, page_status, id_case_mgr, CASE WHEN date_conference IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_004 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '005':
                                    $sqlStmt .= "SELECT CAST('005' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_005 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_005 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '006':
                                    $sqlStmt .= "SELECT CAST('006' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_006 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_006 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '007':
                                    $sqlStmt .= "SELECT CAST('007' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_007 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_007 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '008':
                                    $sqlStmt .= "SELECT CAST('008' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_008 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_008 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '009':
                                    $sqlStmt .= "SELECT CAST('009' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_009 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_009 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '010':
                                    $sqlStmt .= "SELECT CAST('010' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_010 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_010 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '011':
                                    $sqlStmt .= "SELECT CAST('011' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_011 as id, status, '' as title, mdt_conf_date as date, page_status, id_case_mgr, CASE WHEN mdt_conf_date IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_011 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '012':
                                    $sqlStmt .= "SELECT CAST('012' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_012 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_012 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '013':
                                    //$sqlStmt .= "SELECT CAST('013' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_013 as id, status, '' as title, meeting_date as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_013 WHERE id_student = '$pkey' AND status = 'Final' and 'parent exists' != get_parentexists(id_form_013) $dateLimitText";
                                    $sqlStmt .= "SELECT CAST('013' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_013 as id, status, '' as title, meeting_date as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM ifsp_active_forms('$pkey') "; //$dateLimitText
                                    break;
                                case '014':
                                    $sqlStmt .= "SELECT CAST('014' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_014 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_014 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '015':
                                    $sqlStmt .= "SELECT CAST('015' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_015 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_015 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '016':
                                    $sqlStmt .= "SELECT CAST('016' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_016 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_016 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '017':
                                    $sqlStmt .= "SELECT CAST('017' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_017 as id, status, title as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_017 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '018':
                                    $sqlStmt .= "SELECT CAST('018' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_018 as id, status, title as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_018 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '019':
                                    $sqlStmt .= "SELECT CAST('019' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_019 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_019 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '020':
                                    $sqlStmt .= "SELECT CAST('020' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_020 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_020 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '021':
                                    $sqlStmt .= "SELECT CAST('021' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_021 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_021 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '022':
                                    $sqlStmt .= "SELECT CAST('022' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_022 as id, status, '' as title, date_mdt as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_022 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '023':
                                    $sqlStmt .= "SELECT CAST('023' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_023 as id, status, '' as title, date_conference as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_023 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                                case '024':
                                    $sqlStmt .= "SELECT CAST('024' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_024 as id, status, '' as title, date_conference as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_024 WHERE id_student = '$pkey' AND status = 'Final' $dateLimitText";
                                    break;
                            
                            }
                        }
                        $sqlStmt .= "ORDER BY timestamp_created DESC, status ASC, date_null ASC, date DESC";
                        //echo "sqlStmt: $sqlStmt<BR>";
                        
                    } else {
                        
                        // for everyone else we drop the restriction to finalized docs
                        
                        $sqlStmt = "";
                        $firstRun = true;
                        
                        foreach($this->availableForms as $formNum) {
                            //echo "formNum: $formNum<BR>";
                            if($firstRun) {
                                $firstRun = false;
                            } else {
                                $sqlStmt .= " UNION ALL ";
                            }
                            
                            switch($formNum) {
                                case '001':
                                    $sqlStmt .= "SELECT CAST('001' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_001 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_001 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '002':
                                    $sqlStmt .= "SELECT CAST('002' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_002 as id, status, '' as title, date_mdt as date, page_status, id_case_mgr, CASE WHEN date_mdt IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_002 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '003':
                                    $sqlStmt .= "SELECT CAST('003' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_003 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_003 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '004':
                                    $sqlStmt .= "SELECT CAST('004' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_004 as id, status, '' as title, date_conference as date, page_status, id_case_mgr, CASE WHEN date_conference IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_004 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '005':
                                    $sqlStmt .= "SELECT CAST('005' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_005 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_005 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '006':
                                    $sqlStmt .= "SELECT CAST('006' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_006 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_006 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '007':
                                    $sqlStmt .= "SELECT CAST('007' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_007 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_007 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '008':
                                    $sqlStmt .= "SELECT CAST('008' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_008 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_008 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '009':
                                    $sqlStmt .= "SELECT CAST('009' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_009 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_009 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '010':
                                    $sqlStmt .= "SELECT CAST('010' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_010 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_010 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '011':
                                    $sqlStmt .= "SELECT CAST('011' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_011 as id, status, '' as title, mdt_conf_date as date, page_status, id_case_mgr, CASE WHEN mdt_conf_date IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_011 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '012':
                                    $sqlStmt .= "SELECT CAST('012' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_012 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_012 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '013':
                                    //$sqlStmt .= "SELECT CAST('013' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_013 as id, status, '' as title, meeting_date as date, page_status, id_case_mgr, CASE WHEN meeting_date IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_013 WHERE id_student = '$pkey' and 'parent exists' != get_parentexists(id_form_013) $dateLimitText";
                                    $sqlStmt .= "SELECT CAST('013' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_013 as id, status, '' as title, meeting_date as date, page_status, id_case_mgr, CASE WHEN meeting_date IS NULL THEN 0 ELSE 1 END AS date_null FROM ifsp_active_forms('$pkey')";
                                    break;
                                case '014':
                                    $sqlStmt .= "SELECT CAST('014' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_014 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_014 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '015':
                                    $sqlStmt .= "SELECT CAST('015' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_015 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_015 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '016':
                                    $sqlStmt .= "SELECT CAST('016' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_016 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_016 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '017':
                                    $sqlStmt .= "SELECT CAST('017' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_017 as id, status, title as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_017 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '018':
                                    $sqlStmt .= "SELECT CAST('018' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_018 as id, status, title as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_018 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '019':
                                    $sqlStmt .= "SELECT CAST('019' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_019 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_019 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '020':
                                    $sqlStmt .= "SELECT CAST('020' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_020 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_020 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '021':
                                    $sqlStmt .= "SELECT CAST('021' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_021 as id, status, '' as title, date_notice as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_021 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '022':
                                    $sqlStmt .= "SELECT CAST('022' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_022 as id, status, '' as title, date_mdt as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_022 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '023':
                                    $sqlStmt .= "SELECT CAST('023' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_023 as id, status, '' as title, date_conference as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_023 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                                case '024':
                                    $sqlStmt .= "SELECT CAST('024' as CHAR( 3 ) ) as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, id_form_024 as id, status, '' as title, date_conference as date, page_status, id_case_mgr, CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END AS date_null FROM iep_form_024 WHERE id_student = '$pkey' $dateLimitText";
                                    break;
                            }
                        }
                        $sqlStmt .= "ORDER BY timestamp_created DESC, status ASC, date_null ASC, date DESC";
                        //echo "sqlStmt2: $sqlStmt<BR>";
                    }
                } else {
                    $formAllowed = false;
                }
        
                        

            } elseif($view == "Deleted" && $sessPrivCheckObj->isUC_SA()) {
                #
                # IF REQUESTED, RESTORE A DELETED FORM
                #
                #if(1000254 == $sessIdUser && true == $restore) echo "restore: true $restoreDocument<BR>";
                #if(1000254 == $sessIdUser && true != $restore) echo "restore: false $restore<BR>";
                if(isset($restore) && $restore && !empty($restoreDocument)) {
                    #if(1000254 == $sessIdUser) echo 'here<BR>';
                    include_once('iep_class_form.inc');
                    $restoreResult = FORM::getDeletedForm($restoreDocument, $restoredRow);
        
                    $retDoc = $restoredRow['id_form'];
                    $retFormName = "form_" . substr($restoredRow['form_name'], -3,3);
                    $linkText = "$DOC_ROOT/srs.php?area=student&sub=$retFormName&document=$retDoc&page=&option=view";
                    
                    if($restoreResult === "exists") {
                        $msgFlag = 2;
                        $msgType = "delete";
                        $msgText = "Sorry, a form with this id is already in the database. <a href=\"$linkText\">View Form</a>";
                        echo "<html>";
                        echo "<head>";
                        include_once("include_head.php");
                        echo "</head>";
                        echo "<body>";
                        include_once("include_top.php");
                        include_once("message.php");
                        include_once("include_bottom.php");
                        exit;		
                    } else {
                        header("Location:$linkText");
                        exit;
                    }
                }
                #
                # GET A LIST OF DELETED FORMS
                #
                $formAllowed = true;
                $sqlStmt = 	"SELECT *\n";
                $sqlStmt .=	"FROM deleted_forms\n";
                $sqlStmt .=	"WHERE id_student = $pkey\n";
                $sqlStmt .=	"ORDER BY date_created DESC;\n";
            
            
            } elseif($view == "Archived" && $sessPrivCheckObj->isUC_SA()) {
                #
                # IF REQUESTED, RESTORE A ARCHIVED FORM
                #
                #if(1000254 == $sessIdUser && true == $restore) echo "restore: true $restoreDocument<BR>";
                #if(1000254 == $sessIdUser && true != $restore) echo "restore: false $restore<BR>";
                if(isset($restore) && $restore && !empty($restoreDocument)) {
                    #if(1000254 == $sessIdUser) echo 'here<BR>';
                    include_once('iep_class_form.inc');
                    $restoreResult = FORM::getArchivedForm($restoreDocument, $restoredRow);
        
                    $retDoc = $restoredRow['id_form'];
                    $retFormName = "form_" . substr($restoredRow['form_name'], -3,3);
                    $linkText = "$DOC_ROOT/srs.php?area=student&sub=$retFormName&document=$retDoc&page=&option=view";
                    
                    if($restoreResult === "exists") {
                        $msgFlag = 2;
                        $msgType = "delete";
                        $msgText = "Sorry, a form with this id is already in the database. <a href=\"$linkText\">View Form</a>";
                        echo "<html>";
                        echo "<head>";
                        include_once("include_head.php");
                        echo "</head>";
                        echo "<body>";
                        include_once("include_top.php");
                        include_once("message.php");
                        include_once("include_bottom.php");
                        exit;		
                    } else {
                        header("Location:$linkText");
                        exit;
                    }
                }
                #
                # GET A LIST OF ARCHIVED FORMS
                #
                $formAllowed = true;
                $sqlStmt = 	"SELECT *\n";
                $sqlStmt .=	"FROM archived_forms\n";
                $sqlStmt .=	"WHERE id_student = $pkey\n";
                $sqlStmt .=	"ORDER BY date_created DESC;\n";
            
            
            } else {
                // added 2/16/02 sl, a quick way to get the right form code for the individual form screens
                $formArr = explode( "_", $view );
                $formCode = $formArr[1];
                #
                # CHECK TO MAKE SURE USER CAN VIEW THIS FORM
                #
                //var_dump($formAllowed);
                if(!in_array($formCode, $this->availableForms)) {
                    $formAllowed = false;
                } else {
                    $formAllowed = true;
                    switch ($view) {
                        case "form_002":
                            $sqlStmt = 	"SELECT id_$view as id, '$formCode'::text as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, status, to_char(date_mdt::timestamp,'MM/DD/YYYY') as date,
                                            id_$view, page_status, id_case_mgr\n";
                            $sqlStmt .=	"FROM iep_$view\n";
                            if ($sessUserMinPriv == $UC_PG) {
                                $sqlStmt .=	"WHERE id_student = $pkey AND status = 'Final'\n";
                            } else {
                                $sqlStmt .=	"WHERE id_student = $pkey\n";
                            }
                            $sqlStmt .=	"ORDER BY timestamp_created DESC, status ASC, date_mdt DESC;\n";
                            break;
                        case "form_004":
                            $sqlStmt = 	"SELECT id_$view as id, '$formCode'::text as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, status, to_char(date_conference::timestamp,'MM/DD/YYYY') as date, 
                                            id_$view, page_status, id_case_mgr\n";
                            $sqlStmt .=	"FROM iep_$view\n";
                            if ($sessUserMinPriv == $UC_PG) {
                                $sqlStmt .=	"WHERE id_student = $pkey AND status = 'Final'\n";
                            } else {
                                $sqlStmt .=	"WHERE id_student = $pkey\n";
                            }
                            $sqlStmt .=	"ORDER BY timestamp_created DESC, status ASC, date_conference DESC;\n";
                            break;
                        case "form_013":
                            $sqlStmt = 	"SELECT id_$view as id, '$formCode'::text as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, status, to_char(meeting_date::timestamp,'MM/DD/YYYY') as date,
                                            id_$view, page_status, id_case_mgr\n";
                            //$sqlStmt .=	"FROM iep_$view\n";
                            $sqlStmt .=	"FROM ifsp_active_forms($pkey)\n";
                            if ($sessUserMinPriv == $UC_PG) {
                                //$sqlStmt .=	"WHERE id_student = $pkey AND status = 'Final' and 'parent exists' != get_parentexists(id_form_013) \n";
                                $sqlStmt .=	"WHERE id_student = $pkey AND status = 'Final'\n";
                            } else {
                                //$sqlStmt .=	"WHERE id_student = $pkey and 'parent exists' != get_parentexists(id_form_013)  \n";
                                $sqlStmt .=	"WHERE id_student = $pkey\n";
                            }
                            $sqlStmt .=	"ORDER BY timestamp_created DESC, status ASC, date_notice DESC;\n";
                            break;
                        default:
                            $sqlStmt = 	"SELECT id_$view as id, '$formCode'::text as form_no, timestamp_created,  to_char(timestamp_created,'MM/DD/YYYY') as create_date, status, to_char(date_notice::timestamp,'MM/DD/YYYY') as date,
                                            id_$view, page_status, id_case_mgr\n";
                            $sqlStmt .=	"FROM iep_$view\n";
                            if ($sessUserMinPriv == $UC_PG) {
                                $sqlStmt .=	"WHERE id_student = $pkey AND status = 'Final'\n";
                            } else {
                                $sqlStmt .=	"WHERE id_student = $pkey\n";
                            }
                            $sqlStmt .=	"ORDER BY timestamp_created DESC, status ASC, date_notice DESC;\n";
                            break;
                    }
                }
            }
            //echo "sqlStmt: $sqlStmt<BR>";
            
            #
            # IF THE FORM IS ALLOWED, EXECUTE THE SQL
            #
            if($formAllowed) {
                //if(1000254 == $sessIdUser) print("form SQL: $sqlStmt");
                // EXECUTE STMT
                if(1) {
                    /**
                      * APP SERVER UPDATE - 20080603 JLAVERE
                     */
                    $this->appServerMode = true;
                    //echo "sqlStmt: $sqlStmt<BR>";
                    $result = My_Classes_iepFunctionGeneral::xmlRpcslqExec($sqlStmt, $errorId, $errorMsg, true, $errorNoResults=false);
                    if('' == $errorId) {
                        $numRows = count($result);
                    } else {
                        include_once("error.php");
                        exit;
                    }
            
                } else {
                    $appServerMode = false;
                    $result = My_Classes_iepFunctionGeneral::xmlRpcslqExec($sqlStmt, $errorId, $errorMsg, true, false);
                    if(false === $result) {
                        include_once("error.php");
                        exit;
                    }
                    $numRows = $result ? count($result) : 0;	
                }
            }
            
            
            #
            # check for local student id for display
            $studentDisplay = $id_student_local==''?$pkey: $id_student_local;
        
            return $result;
        }

        
    }




	function displayCreateForms($displayCreateForms, $ad, $pkey, $area, $sub)
    {
        global $sessPrivCheckObj, $DOC_ROOT;

        if($displayCreateForms)
        {
            $html = "<tr class=\"bgLgrey\" style=\"height:30px;\">";
            $html .= "<td style=\"width:650px;\">&nbsp;</td>";
                
                $html .= "<td class=\"bts\" nowrap=\"nowrap\" align=\"right\"><span class=\"btsb\">New Form</span>:</td>";
                $html .= "<td class=\"bts\" style=\"padding-left:4;\">";
    
                    ########################################################################################
                    # BUILD AVAILABLE FORM LIST
                    #
                    $arrLabelMaster = array('--- SCHOOL AGED FORMS ---');
                    $arrValueMaster = array('disable');
    
                    $eiLabelMaster = array();
                    $eiValueMaster = array();
    
                    $optLabelMaster = array();
                    $optValueMaster = array();
                    
                    $anyEIform = false;
                    $anyOptionalForm = false;
                    
                    foreach($this->createForms as $formNum) {
                        switch($formNum) {
                            case '001':
                            case '002':
                            case '003':
                            case '004':
                            case '005':
                            case '006':
                            case '007':
                            case '008':
                            case '009':
                            case '010':
                            case '017':
                            case '018':
                            case '022':
                            case '023':
                            case '024':
                                array_push($arrLabelMaster, $this->formDescriptions[$formNum]);
                                array_push($arrValueMaster, 'form_'.$formNum);
                                break;
                            case '011':
                                if($this->arrDataDist['use_form_011']) {
                                    #
                                    # ADD OPTIONAL FORMS
                                    #
                                    if($anyOptionalForm == false) {
                                        $anyOptionalForm = true;
                                        array_push($optLabelMaster, '--- Optional Forms ---');
                                        array_push($optValueMaster, 'disable');
                                    }
                                    if($this->arrDataDist['use_form_011']) {
                                        array_push($optLabelMaster, $this->formDescriptions[$formNum]);
                                        array_push($optValueMaster, 'form_'.$formNum);							
                                    }
                                }
                                break;
                            case '012':
                                if($this->arrDataDist['use_form_012']) {
                                    #
                                    # ADD OPTIONAL FORMS
                                    #
                                    if($anyOptionalForm == false) {
                                        $anyOptionalForm = true;
                                        array_push($optLabelMaster, '--- Optional Forms ---');
                                        array_push($optValueMaster, 'disable');
                                    }
                                    if($this->arrDataDist['use_form_012']) {
                                        array_push($optLabelMaster, $this->formDescriptions[$formNum]);
                                        array_push($optValueMaster, 'form_'.$formNum);							
                                    }
                                }
                                break;
                            case '013':
                            case '014':
                            case '015':
                            case '016':
                                #
                                # CHECK IF STUDENT IS IN EI
                                #
                                if( eiAvailable($ad) ) { // $ad is student array
                                    #
                                    # ADD EI FORMS
                                    #
                                    if($anyEIform == false) {
                                        $anyEIform = true;
                                        array_push($eiLabelMaster, '--- EI FORMS ---');
                                        array_push($eiValueMaster, 'disable');
                                        
                                        $eiFormList = array("015", "016", "014", "013");
                                        #
                                        # MAKE SURE USER HAS ACCESS TO THESE FORMS IN THEIR PRIV ARRAY
                                        #
                                        foreach($eiFormList as $eiFormNum) {
                                            if(in_array($eiFormNum, $this->createForms)) {
                                                array_push($eiLabelMaster, $this->formDescriptions[$eiFormNum]);
                                                array_push($eiValueMaster, 'form_'.$eiFormNum);
                                            }
                                        }
                                    }
                                }
                                break;
                                array_push($arrLabelMaster, $this->formDescriptions[$formNum]);
                                array_push($arrValueMaster, 'form_'.$formNum);
                                break;
                            case '019':
                                if($this->arrDataDist['use_form_019']) {
                                    #
                                    # ADD OPTIONAL FORMS
                                    #
                                    if($anyOptionalForm == false) {
                                        $anyOptionalForm = true;
                                        array_push($optLabelMaster, '--- Optional Forms ---');
                                        array_push($optValueMaster, 'disable');
                                    }
                                    if($this->arrDataDist['use_form_019']) {
                                        array_push($optLabelMaster, $this->formDescriptions[$formNum]);
                                        array_push($optValueMaster, 'form_'.$formNum);							
                                    }
                                }
                                break;
                            case '020':
                                if($this->arrDataDist['use_form_020']) {
                                    #
                                    # ADD OPTIONAL FORMS
                                    #
                                    if($anyOptionalForm == false) {
                                        $anyOptionalForm = true;
                                        array_push($optLabelMaster, '--- Optional Forms ---');
                                        array_push($optValueMaster, 'disable');
                                    }
                                    if($this->arrDataDist['use_form_020']) {
                                        array_push($optLabelMaster, $this->formDescriptions[$formNum]);
                                        array_push($optValueMaster, 'form_'.$formNum);							
                                    }
                                }
                                break;
                            case '021':
                                if($this->arrDataDist['use_form_021']) {
                                    #
                                    # ADD OPTIONAL FORMS
                                    #
                                    if($anyOptionalForm == false) {
                                        $anyOptionalForm = true;
                                        array_push($optLabelMaster, '--- Optional Forms ---');
                                        array_push($optValueMaster, 'disable');
                                    }
                                    if($this->arrDataDist['use_form_021']) {
                                        array_push($optLabelMaster, $this->formDescriptions[$formNum]);
                                        array_push($optValueMaster, 'form_'.$formNum);							
                                    }
                                }
                                break;
                        }
                    }
                    if( $sessPrivCheckObj->isUC_PG() ) {
                        // optional forms
                        #$arrLabel = array_merge($arrLabelMaster, $ifspLabelMaster);
                        #$arrValue = array_merge($arrValueMaster, $ifspValueMaster);
                        $arrLabel = $arrLabelMaster;
                        $arrValue = $arrValueMaster;
                    } else {
                        $arrLabel = array_merge($arrLabelMaster, $eiLabelMaster, $optLabelMaster);
                        $arrValue = array_merge($arrValueMaster, $eiValueMaster, $optValueMaster);
                    }
                    #
                    # CREATE THE SELECT
                    #
                    $html .= valueListCustom("formListNew", "", $arrLabel, $arrValue, "", "Choose...", "onChange=\"javascript:if(this.value) { goToURL('$DOC_ROOT', '$area', this.value, 'student', $pkey, '&option=new') };\"");
    
                $html .= "</td>";
                $html .= "<td style=\"width:500px;\">&nbsp;</td>";
                $html .= "</tr>";
                $html .= "<tr class=\"bgLgrey\">";
                    $html .= "<td colspan=\"4\" style=\"height:1px;\">&nbsp;</td>";
                $html .= "</tr>";
                
                return $html;
        }

    }

    function displayViewForms($ad, $pkey, $view, $area, $sub)
    {
        global $sessPrivCheckObj, $DOC_ROOT;

        $html = "<tr class=\"bgLgrey\" style=\"height:30px;\">";
            $html .= "<td style=\"width:650px;\">&nbsp;</td>";
            $html .= "<td class=\"bts\" style=\"padding-left:8px;\" nowrap=\"nowrap\" align=\"right\"><span class=\"btsb\">View Form</span>:</td>";
            $html .= "<td class=\"bts\" style=\"padding-left:4;\">";
                ########################################################################################
                # BUILD AVAILABLE FORM LIST
                #
                $arrLabelMaster = array("All Forms", "Current Forms", '--- SCHOOL AGED FORMS ---');
                $arrValueMaster = array("all", "current", 'disable');

                $eiLabelMaster = array();
                $eiValueMaster = array();

                $optLabelMaster = array();
                $optValueMaster = array();
                
                $anyEIform = false;
                $anyOptionalForm = false;
                
                foreach($this->availableForms as $formNum) {
                    switch($formNum) {
                        case '001':
                        case '002':
                        case '003':
                        case '004':
                        case '005':
                        case '006':
                        case '007':
                        case '008':
                        case '009':
                        case '010':
                        case '017':
                        case '018':
                        case '022':
                        case '023':
                        case '024':
                            array_push($arrLabelMaster, $this->formDescriptions[$formNum]);
                            array_push($arrValueMaster, 'form_'.$formNum);
                            break;
                        case '011':
                            if($this->arrDataDist['use_form_011']) {
                                #
                                # ADD OPTIONAL FORMS
                                #
                                if($anyOptionalForm == false) {
                                    $anyOptionalForm = true;
                                    array_push($optLabelMaster, '--- Optional Forms ---');
                                    array_push($optValueMaster, 'disable');
                                }
                                if($this->arrDataDist['use_form_011']) {
                                    array_push($optLabelMaster, $this->formDescriptions[$formNum]);
                                    array_push($optValueMaster, 'form_'.$formNum);							
                                }
                            }
                            break;
                        case '012':
                            if($this->arrDataDist['use_form_012']) {
                                #
                                # ADD OPTIONAL FORMS
                                #
                                if($anyOptionalForm == false) {
                                    $anyOptionalForm = true;
                                    array_push($optLabelMaster, '--- Optional Forms ---');
                                    array_push($optValueMaster, 'disable');
                                }
                                if($this->arrDataDist['use_form_012']) {
                                    array_push($optLabelMaster, $this->formDescriptions[$formNum]);
                                    array_push($optValueMaster, 'form_'.$formNum);							
                                }
                            }
                            break;
                        case '013':
                        case '014':
                        case '015':
                        case '016':
                            #
                            # CHECK IF STUDENT IS IN EI
                            #
                            if( eiAvailable($ad) ) { // $ad is student array
                                #
                                # ADD EI FORMS
                                #
                                if($anyEIform == false) {
                                    $anyEIform = true;
                                    array_push($eiLabelMaster, '--- EI FORMS ---');
                                    array_push($eiValueMaster, 'disable');
                                    
                                    $eiFormList = array("015", "016", "014", "013");
                                    #
                                    # MAKE SURE USER HAS ACCESS TO THESE FORMS IN THEIR PRIV ARRAY
                                    #
                                    foreach($eiFormList as $eiFormNum) {
                                        if(in_array($eiFormNum, $this->availableForms)) {
                                            array_push($eiLabelMaster, $this->formDescriptions[$eiFormNum]);
                                            array_push($eiValueMaster, 'form_'.$eiFormNum);
                                        }
                                    }
                                }
                            }
                            break;
                            array_push($arrLabelMaster, $this->formDescriptions[$formNum]);
                            array_push($arrValueMaster, 'form_'.$formNum);
                            break;
                        case '019':
                            if($this->arrDataDist['use_form_019']) {
                                #
                                # ADD OPTIONAL FORMS
                                #
                                if($anyOptionalForm == false) {
                                    $anyOptionalForm = true;
                                    array_push($optLabelMaster, '--- Optional Forms ---');
                                    array_push($optValueMaster, 'disable');
                                }
                                if($this->arrDataDist['use_form_019']) {
                                    array_push($optLabelMaster, $this->formDescriptions[$formNum]);
                                    array_push($optValueMaster, 'form_'.$formNum);							
                                }
                            }
                            break;
                        case '020':
                            if($this->arrDataDist['use_form_020']) {
                                #
                                # ADD OPTIONAL FORMS
                                #
                                if($anyOptionalForm == false) {
                                    $anyOptionalForm = true;
                                    array_push($optLabelMaster, '--- Optional Forms ---');
                                    array_push($optValueMaster, 'disable');
                                }
                                if($this->arrDataDist['use_form_020']) {
                                    array_push($optLabelMaster, $this->formDescriptions[$formNum]);
                                    array_push($optValueMaster, 'form_'.$formNum);							
                                }
                            }
                            break;
                        case '021':
                            if($this->arrDataDist['use_form_021']) {
                                #
                                # ADD OPTIONAL FORMS
                                #
                                if($anyOptionalForm == false) {
                                    $anyOptionalForm = true;
                                    array_push($optLabelMaster, '--- Optional Forms ---');
                                    array_push($optValueMaster, 'disable');
                                }
                                if($this->arrDataDist['use_form_021']) {
                                    array_push($optLabelMaster, $this->formDescriptions[$formNum]);
                                    array_push($optValueMaster, 'form_'.$formNum);							
                                }
                            }
                            break;
                    }
                }
                $arrLabel = array_merge($arrLabelMaster, $eiLabelMaster, $optLabelMaster);
                $arrValue = array_merge($arrValueMaster, $eiValueMaster, $optValueMaster);
                if($sessPrivCheckObj->isUC_SA()) {
                    array_push($arrLabel, "--- SA Options ---", "Deleted Forms", "Archived Forms");
                    array_push($arrValue, "disable", "Deleted", "Archived");
                }
                #
                # CREATE THE SELECT
                #
                $html .= valueListCustom("formListView", $view, $arrLabel, $arrValue, "", "Choose...", "onChange=\"javascript:if(this.value) { goToURL('$DOC_ROOT', '$area', '$sub', 'student', $pkey, '&option=forms&view=' + this.value) };\"");
            $html .= "</td>";
            $html .= "<td style=\"width:650px;\">&nbsp;</td>";
        $html .= "</tr>";
        return $html;
    }
    
    function mergeStudentFormsMenu($formNum, $formID)
    {
	    $formInput = new form_element('function', 'return', $separater);
    	$retLinkList = $formInput->form_input_checkbox( 'moveForms[]', '', true, '', 'Move', $formNum . "|" .$formID);
        return $retLinkList;
    }

    function buildViewAllOrCurrent($arrFieldLabels, $arrFieldNames, $numRows, $result, $pkey, $area, $view, $mergeStudentForms = false)
    {
        global $FORM_NAMES, $sessUserMinPriv,$sessIdUser;
        
        //if($mergeStudentForms) echo "mergeStudentForms: true<BR>";
        #pre_print_r($arrFieldLabels);
        
        $html = "<table align=\"center\" style=\"width:700px;\" cellpadding=\"0\" cellspacing=\"5\">";
            $html .= "<tr>";
            for ($i = 0; $i < count($arrFieldLabels); $i++) { if ($i == 0) { $padding = 0; } else { $padding = 10; }
                $html .= "<th style=\"text-decoration:underline; padding-left:".$padding."px;\" class=\"btsb\" align=\"left\" nowrap=\"nowrap\">".$arrFieldLabels[$i]."</th>";
            }
            $html .= "</tr>";
            $html .= "<tr>";
                $html .= "<td colspan=\"" . (count($arrFieldLabels) + 1) . "\"><img src='images/line_grey.gif' height=2 width='100%'></td>";
            $html .= "</tr>";
            if ($numRows) {
                include_once("iep_class_form.inc");
                $objForm = new form();
                #for ($i = $pos; $i < $numRows; $i++) {

                for ($i = 0; $i < $numRows; $i++) {

                    if($this->appServerMode) {
                        $arrData = $result[$i];
                    } else {
                        $arrData = $result[$i];
                    }
                    $arrData['id_student'] = $pkey; // add student id to the array
                    
                    $html .= $this->displayRow($arrFieldLabels, $arrFieldNames, $arrData, $titleAddition, $objForm, $FORM_NAMES, $area, $view, 0, 10, $mergeStudentForms);

                    #
                    # PROGRESS REPORTS DISPLAY
                    #  DISPLAY PROGRESS REPORTS FOR THIS IEP
                    #
                    # ###############################################################################################################################################
                    if($arrData['form_no'] == '004') {
                        #
                        # GET PR
                        #
                        ########################################################################
                        $prObjForm = new form();
                        if($sessUserMinPriv == $UC_PG) {
                            //$prForms = $prObjForm->getProgressReports($arrData['id'], true);
                            $prForms = $prObjForm->getProgressReport_forFormCenter($arrData['id'], true);
                            $prForms = $prObjForm->getProgressReportsArray($arrData['id'], true);
                        } else {
                            //$prForms = $prObjForm->getProgressReports($arrData['id'], false);
                            //$prForms = $prObjForm->getProgressReport_forFormCenter($arrData['id'], false);
                            $prForms = $prObjForm->getProgressReportsArray($arrData['id'], false);
                        }
        
                        if($prForms !== -1) {
                            #
                            # GET THE COUNT OF PROGRESS REPORTS
                            #
                            $prCount = count($prForms);
                            
                            if(0)
                            {
                            // 20070702 jlavere - build progress report status display
                            $PRstatus = "";
                            $draftExists = false;
                            for($p=0; $p < $prCount; $p++)
                            {
                                if($prForms[$p]['status'] == 'Final')
                                {
                                    $PRstatus .= "<B>" . ($p+1) . "</B>";
                                } elseif($prForms[$p]['status'] == 'Draft')
                                {
                                    $PRstatus .= "<span class=\"btsred\"><B>". ($p+1) . "</B></span>";
                                    $draftExists = true;
                                }    				    
                            }
                            if($draftExists) 
                            {
                                $PRstatus = "Draft[" . $PRstatus . "]";
                            } else {
                                $PRstatus = "Final";
                            
                            }
                            // 20070702 jlavere - END build progress report status 
                            }
                            
                            if($prCount > 0) {
                                
                                //for ($x = 0; $x<1; $x++) { // limit to 1 PG
                                for ($x = 0; $x<$prCount; $x++) { // 20071031 jlavere - show all PG
                                    #
                                    # EXTRACT INDIVIDUAL FORM
                                    #
                                    $prData = $prForms[$x];
                                    $prData['id_student'] = $pkey;
        
                                    #
                                    # next three lines go and get abbreviated name of the form for display sl 12/2/01
                                    #
                                    $formCode = "form_" . $prData['form_no'];
                                    $formTitleData = $FORM_NAMES[$formCode];
                                    $formShortName = $formTitleData['shortName'];
        
                                    $html .=  "<tr>\n";
                                    for ($m = 0; $m < count($arrFieldLabels); $m++) { if ($m == 0) { $padding = 15; } else { $padding = 10; }
                                        #
                                        # 031804-JL COPIED FROM ABOVE AS THIS SECTION DIDN'T SEEM TO BE WORKING PROPERLY
                                        #
                                        if ($arrFieldNames[$m] == "date" || $arrFieldNames[$m] == "create_date") {
                                            $html .=  "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . My_Classes_iepFunctionGeneral::htmlEncode(My_Classes_iepFunctionGeneral::date_massage($prData[$arrFieldNames[$m]], 'm/d/y'), true) . "</td>\n";
                        
                                        } elseif ($arrFieldNames[$m] == "status")  {
        //                                  if(!isset($prData['finalformsexist'])) {
        //                                         //echo "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . $objForm->pageStatus($prData['status'], $prData['page_status']) .$suppDisplay. "</td>\n";
        //                                         echo "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>Final " .$suppDisplay. "</td>\n";
        // 								    } elseif('t' == $prData['finalformsexist']) {
        //                                         echo "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>Mixed " .$suppDisplay. "</td>\n";
        // 								    } else {
        //                                         echo "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>Draft " .$suppDisplay. "</td>\n";
        // 								    }
                                            $html .=  "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]> " . $objForm->pageStatus($prData['status'], $prData['page_status']) .$suppDisplay . "</td>\n";
                        
                                        } elseif ($arrFieldNames[$m] == "form_no")  {
                                            if($prData['title'] != '' ) {
                                                $titleAddition = " (".$prData['title'] .")";
                                            } else {
                                                $titleAddition = '';
                                            }
                                            $html .=  "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . $formShortName . $titleAddition . "</td>\n";
                        
                                        } else {
                                            $html .=  "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . $prData[$arrFieldNames[$m]] . "</td>\n";
                                        }
                                    }
                                    $view = "form_" . $prData['form_no'];
                                    //echo "<td align=\"right\" class=\"bts\" style=\"padding-left:10px;\">" . $objForm->buildOptionList($prData['id'], $prData['status'], $mode, $sub, $prData['id_case_mgr'], $prData['form_no'],'',$ast['flag_view'],$ast['flag_edit'],$ast['flag_create']) . "</td>\n";
                                    // changed so that the option list is built based on the case mgr id from the student record, NOT from the form record -- current cm can view all forms regardless of what the case mgr is on them is. sl 10/1/02
                                    
                                    if(true == $mergeStudentForms)
                                    {
                                        $html .=  "<td align=\"right\" class=\"bts\"  style=\"white-space:nowrap\">" . $this->mergeStudentFormsMenu($pkey . "|" . $arrData['form_no'], $arrData['id']) . "</td>\n";
                                    } else {
                                        $html .=  "<td align=\"right\" class=\"bts\"  style=\"white-space:nowrap\">" . $objForm->buildOptionListAccess($prData, $area, $view) . "</td>\n";
                                    }
                                
                                    $html .=  "</tr>\n<tr>\n<td colspan=\"" . (count($arrFieldLabels) + 1) . "\"><img src='images/line_grey.gif' height=1 width='100%'></td>\n</tr>\n";
                                }
                            }
                        }
                    } elseif($arrData['form_no'] == '013') {


                        #
                        # GET PR
                        #
                        ########################################################################
                        $prObjForm = new form();
                        if($sessUserMinPriv == $UC_PG) {
                            $ifspForms = $prObjForm->form_013_get_archived_ifsp($arrData['id'], true);
                        } else {
                            $ifspForms = $prObjForm->form_013_get_archived_ifsp($arrData['id'], false);
                        }
        
                        if($ifspForms !== -1) {
                            #
                            # GET THE COUNT OF IFSPs
                            #
                            $prCount = count($ifspForms);
        
        
                            if($prCount > 0) {
                                for ($x = 0; $x<$prCount; $x++) {
                                    #
                                    # EXTRACT INDIVIDUAL FORM
                                    #
                                    $prData = $ifspForms[$x];
                                    if(1)
                                    {

                                        if($prData['title'] != '' ) {
                                            $titleAddition = " (".$prData['title'] .")xx";
                                        } else {
                                            $titleAddition = '';
                                        }

                                        $html .= $this->displayRow($arrFieldLabels, $arrFieldNames, $prData, "(Archived)", $objForm, $FORM_NAMES, $area, $view, 15, 10);

                                    } else {
//                                         #
//                                         # next three lines go and get abbreviated name of the form for display sl 12/2/01
//                                         #
//                                         $formCode = "form_" . $prData['form_no'];
//                                         $formTitleData = $FORM_NAMES[$formCode];
//                                         $formShortName = $formTitleData['shortName'];
//             
//                                         //
//                                         // GET THE IFSP TYPE
//                                         if($prData['form_no'] == '013') {
//                                             // get the ifsp type
//                                             $form013Arr = getForm013($prData['id']);
//                                             $ifsptype = $form013Arr['ifsptype'];
//                                             $formShortName .= " ($ifsptype)";
//                                         }
//             
//             
//                                         $html .=  "<tr>\n";
//                                         for ($m = 0; $m < count($arrFieldLabels); $m++) {
//                                             
//                                             if ($m == 0) { $padding = 15; } else { $padding = 10; }
//                                             
//                                             #
//                                             # 031804-JL COPIED FROM ABOVE AS THIS SECTION DIDN'T SEEM TO BE WORKING PROPERLY
//                                             #
//                                             if ($arrFieldNames[$m] == "date" || $arrFieldNames[$m] == "create_date") {
//                                                 $html .=  "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . My_Classes_iepFunctionGeneral::htmlEncode(My_Classes_iepFunctionGeneral::date_massage($prData[$arrFieldNames[$m]], 'm/d/y'), true) . "</td>\n";
//                             
//                                             } elseif ($arrFieldNames[$m] == "status")  {
//                                                 $html .=  "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]> " .$prObjForm->pageStatus($prData['status'],$prData['page_status']). "</td>\n";
//                             
//                                             } elseif ($arrFieldNames[$m] == "form_no")  {
//                                                 if($prData['title'] != '' ) {
//                                                     $titleAddition = " (".$prData['title'] .")";
//                                                 } else {
//                                                     $titleAddition = '';
//                                                 }
//                                                 $html .=  "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . $formShortName . $titleAddition . "</td>\n";
//                             
//                                             } else {
//                                                 $html .=  "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . $prData[$arrFieldNames[$m]] . "</td>\n";
//                                             }
//                                         }
//                                         $view = "form_" . $prData['form_no'];
//                                         if(true == $mergeStudentForms)
//                                         {
//                                             $html .=  "<td align=\"right\" class=\"bts\"  style=\"white-space:nowrap\">" . $this->mergeStudentFormsMenu($pkey . "|" . $arrData['form_no'], $arrData['id']) . "</td>\n";
//                                         } else {
//                                             $html .=  "<td align=\"right\" class=\"bts\"  style=\"white-space:nowrap\">" . $objForm->buildOptionListAccess($prData, $area, $view) . "</td>\n";
//                                         }
//                                         $html .=  "</tr>\n<tr>\n<td colspan=\"" . (count($arrFieldLabels) + 1) . "\"><img src='images/line_grey.gif' height=1 width='100%'></td>\n</tr>\n";
                                    }
                                }
                            }
                        }
//                         if($arrData['title'] != '' ) {
//                             $titleAddition = " (".$arrData['title'] .")xx";
//                         } else {
//                             $titleAddition = '';
//                         }
                    }
                    # ###############################################################################################################################################
                    # ###############################################################################################################################################
                }
            }
        $html .= "</table>";
        return $html;        
    }


    function buildFoundRecordsDisplay($numRows, $maxRecs, $strNavigation)
    {

        $html = "<table align=\"center\" style=\"width:700px; margin-top:15px;\" cellpadding=\"0\" cellspacing=\"5\">";
            $html .= "<tr>";
                if ($numRows == 1) {
                    $html .= "<td class=\"bts\" style=\"white-space:nowrap;\" nowrap=\"nowrap\"><span class=\"btsb\">1</span> record was found.";
                } elseif ($numRows == 0) {
                    $html .= "<td class=\"bts\" style=\"white-space:nowrap;\" nowrap=\"nowrap\"><span class=\"btsb\">0</span> records were found.";
                } else {
                    $html .= "<td class=\"bts\" style=\"white-space:nowrap;\" nowrap=\"nowrap\"><span class=\"btsb\">$numRows</span> records were found.";
                }
                $html .= "<span class=\"btsb\">&nbsp;&nbsp;&nbsp;Note</span>: Pages listed in red are incomplete.</td>";
                if ($numRows) {
                    $html .= "<td align=\"right\" style=\"width:100%;\" class=\"bt\">&nbsp;</td>";
                } if (isset($maxRecs) && $numRows > $maxRecs) { $html .= isset($strNavigation)?$strNavigation:""; }
            $html .= "</tr>";
        $html .= "</table>";
        
        return $html;
    }

    function buildViewForm($arrFieldLabels, $arrFieldNames, $numRows, $result, $pkey, $area, $view)
    {
        global $FORM_NAMES, $sessUserMinPriv, $DOC_ROOT, $sessIdUser;

        #
        # WE ARE VIEWING ONLY ONE KIND OF FORM 
        #
    
        $html .= "<table align=\"center\" style=\"width:700px;\" cellpadding=\"0\" cellspacing=\"5\">";
            $html .= "<tr>";
            for ($i = 0; $i < count($arrFieldLabels); $i++) {
                $html .= "<th style=\"text-decoration:underline;\" class=\"btsb\" align=left nowrap=\"nowrap\">".$arrFieldLabels[$i]."</th>";
            }
            $html .= "</tr>";
            $html .= "<tr>";
                $html .= "<td colspan=\"" . (count($arrFieldLabels) + 1) . "\"><img src='images/line_grey.gif' height=2 width='100%'></td>";
            $html .= "</tr>";


            if($numRows) {
                // create the form display of line items
                include_once("iep_class_form.inc");
                $objForm = new form();

                for ($i = 0; $i < $numRows; $i++) {

                    $arrData = $result[$i];
                    $arrData['id_student'] = $pkey; // add student id to the array
                    $html .= "<tr>\n";
        
                    for ($j = 0; $j < count($arrFieldLabels); $j++) { 
                        if ($j == 0) { $padding = 0; } else { $padding = 10; }
        
                        $formCode = "form_" . $arrData['form_no'];
                        $formTitleData = $FORM_NAMES[$formCode];
                        $formShortName = $formTitleData['shortName'];
                        //
                        // GET THE IFSP TYPE
                        if($arrData['form_no'] == '013') {
                            // get the ifsp type
                            $form013Arr = getForm013($arrData['id']);
                            $ifsptype = $form013Arr['ifsptype'];
                            $formShortName .= " ($ifsptype)";
                        }
        
                        if ($arrFieldNames[$j] == "date" || $arrFieldNames[$j] == "create_date") {
                            $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$j]>" . My_Classes_iepFunctionGeneral::htmlEncode(My_Classes_iepFunctionGeneral::date_massage($arrData[$arrFieldNames[$j]], 'm/d/y'), true) . "</td>\n";
                        
                        } elseif ($arrFieldNames[$j] == "status")  {
                            #
                            # STATUS DISPLAY
                            #
                            if($view == "Deleted") {
                                require_once('lib/class_form_element.php');
                                $linkArray = array();
                                $linkArray['restore'] = "javascript: goToURL('$DOC_ROOT', '$area', 'student', 'document', ".$arrData['id_deleted_forms'].", '&student=$pkey&option=forms&view=Deleted&restore=true');";
                                $formInput = new form_element('function', 'return');
                                $suppDisplay = $formInput->form_link_list( true, array('restore'=>'Restore'), $linkArray);
                                $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$j]>" . $suppDisplay. "</td>\n";
                            } elseif($view == "Archived") {
                                require_once('lib/class_form_element.php');
                                $linkArray = array();
                                $linkArray['restore'] = "javascript: goToURL('$DOC_ROOT', '$area', 'student', 'document', ".$arrData['id_archived_forms'].", '&student=$pkey&option=forms&view=Archived&restore=true');";
                                $formInput = new form_element('function', 'return');
                                $suppDisplay = $formInput->form_link_list( true, array('restore'=>'Restore'), $linkArray);
                                $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$j]>" . $suppDisplay. "</td>\n";
                            } else {
                                $suppDisplay = $objForm->suppDisplay($arrData['id'], 'form_'.$arrData['form_no'], $arrData['status']);
                                $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$j]>" . $objForm->pageStatus($arrData['status'], $arrData['page_status']) .$suppDisplay. "</td>\n";
                            }
                        
                        } elseif ($arrFieldNames[$j] == "form_no")  {
                            if($arrData['title'] != '' ) {
                                $titleAddition = " (".$arrData['title'] .")";
                            } else {
                                $titleAddition = '';
                            }
                            $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$j]>" . $formShortName . $titleAddition . "</td>\n";
                        
                        } elseif ($arrFieldNames[$j] == "form_name")  {
                            
                            $formNo = substr($arrData['form_name'],-3,3);
                            $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$j]>" . $this->formDescriptions[$formNo] . "</td>\n";
                        
                        } elseif($arrFieldNames[$j] == "deleted_by") {
                            include_once("iep_class_personnel.inc");
                            $objPersonnel = new personnel();
                            
                            // select personnel record ($ad = array personnel data)
                            $perTableName = "iep_personnel";
                            $perPkeyName = "id_personnel";
                            $perPkey = $arrData['deleted_by'];			
                            $perMode = "view";			
                            if (!$objPersonnel->select($perPkey, $perMode, $perData, $perTableName, $perPkeyName, false, false)) {
                                #$errorId = $objPersonnel->errorId;
                                #$errorMsg = $objPersonnel->errorMsg;
                                #include_once("error.php");
                                #exit;
                                //
                                // 20050814 jlavere 
                                //  stop erroring when personnel not found.
                                // instead just display error in name
                                $perData['name_full'] = "Personnel not found.";
                            }
                            $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$j]>" . $perData['name_full'] . "</td>\n";
                        } else {
                            $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$j]>" . $arrData[$arrFieldNames[$j]] . "</td>\n";
                        
                        }
                    }
        
        
                    $sub = $view . "1";
                    //echo "<td align=right class=bts>" . $objForm->buildOptionList($afd[0], $afd[1], $mode, $sub, $afd[6]) . "</td>\n";
                    //Tweaked the above line in favor of the below. The above is missing options and seems out of order. sl 1/21/02
                    #
                    # IF THIS IS AN IEP, WE NEED THE ID OF THE STUDENT IN THE BUILD OPTION LIST FUNCTION
                    #
                    if($arrData['form_no'] == '004') {
                        $arrData['id_student'] = $pkey;
                    }
                    if($view == "Deleted") {
                        $html .= "<td align=right class=bts style=\"white-space:nowrap\">&nbsp;</td>\n";
                        $html .= "</tr>\n<tr>\n<td colspan=\"" . (count($arrFieldLabels) + 1) . "\"><img src='images/line_grey.gif' height=1 width='100%'></td>\n</tr>\n";
                    } elseif($view == "Archived") {
                        $html .= "<td align=right class=bts style=\"white-space:nowrap\">&nbsp;</td>\n";
                        $html .= "</tr>\n<tr>\n<td colspan=\"" . (count($arrFieldLabels) + 1) . "\"><img src='images/line_grey.gif' height=1 width='100%'></td>\n</tr>\n";
                    } else {
                        $html .= "<td align=right class=bts style=\"white-space:nowrap\">" . $objForm->buildOptionListAccess($arrData, $area, $view) . "</td>\n";
                        $html .= "</tr>\n<tr>\n<td colspan=\"" . (count($arrFieldLabels) + 1) . "\"><img src='images/line_grey.gif' height=1 width='100%'></td>\n</tr>\n";
                    }
        
                    #
                    # PROGRESS REPORTS DISPLAY
                    #  DISPLAY PROGRESS REPORTS FOR THIS IEP
                    #
                    # ###############################################################################################################################################
                    if($arrData['form_no'] == '004' && 1) {
                        #
                        # GET PR
                        #
                        ########################################################################
                        #
                        #$id_iep = FORM::getPRform004($arrData['id']);
                        #
                        ########################################################################
                        $prObjForm = new form();
                        if($sessUserMinPriv == $UC_PG) {
                            //$prForms = $prObjForm->getProgressReports($arrData['id'], true);
                            //$prForms = $prObjForm->getProgressReport_forFormCenter($arrData['id'], true);
                            $prForms = $prObjForm->getProgressReportsArray($arrData['id'], true);
                        } else {
                            //$prForms = $prObjForm->getProgressReports($arrData['id'], false);
                            //$prForms = $prObjForm->getProgressReport_forFormCenter($arrData['id'], false);
                            $prForms = $prObjForm->getProgressReportsArray($arrData['id'], false);
                        }
        
                        if($prForms !== -1) {
                            #
                            # GET THE COUNT OF PROGRESS REPORTS
                            #
                            $prCount = count($prForms);
                            
                            if($prCount > 0) {
                                
                                for ($x = 0; $x<$prCount; $x++) {
                                //for ($x = 0; $x<1; $x++) { // limit to 1 PG
                                    #
                                    # EXTRACT INDIVIDUAL FORM
                                    #
                                    $prData = $prForms[$x];
                                    $prData['id_student'] = $pkey;
        
                                    #
                                    # next three lines go and get abbreviated name of the form for display sl 12/2/01
                                    #
                                    $formCode = "form_" . $prData['form_no'];
                                    $formTitleData = $FORM_NAMES[$formCode];
                                    $formShortName = $formTitleData['shortName'];
        
                                    $html .= "<tr>\n";
                                    for ($m = 0; $m < count($arrFieldLabels); $m++) { if ($m == 0) { $padding = 15; } else { $padding = 10; }
                                        #
                                        # 031804-JL COPIED FROM ABOVE AS THIS SECTION DIDN'T SEEM TO BE WORKING PROPERLY
                                        #
                                        if ($arrFieldNames[$m] == "date" || $arrFieldNames[$m] == "create_date") {
                                            $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . My_Classes_iepFunctionGeneral::htmlEncode(My_Classes_iepFunctionGeneral::date_massage($prData[$arrFieldNames[$m]], 'm/d/y'), true) . "</td>\n";
                        
                                        } elseif ($arrFieldNames[$m] == "status")  {
        //                                  if(!isset($prData['finalformsexist'])) {
        //                                         //echo "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . $objForm->pageStatus($prData['status'], $prData['page_status']) .$suppDisplay. "</td>\n";
        //                                         echo "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>Final " .$suppDisplay. "</td>\n";
        // 								    } elseif('t' == $prData['finalformsexist']) {
        //                                         echo "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>Mixed " .$suppDisplay. "</td>\n";
        // 								    } else {
        //                                         echo "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>Draft " .$suppDisplay. "</td>\n";
        // 								    }
                                            $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]> " . $objForm->pageStatus($prData['status'], $prData['page_status']) .$suppDisplay . "</td>\n";
                        
                                        } elseif ($arrFieldNames[$m] == "form_no")  {
                                            if($prData['title'] != '' ) {
                                                $titleAddition = " (".$prData['title'] .")";
                                            } else {
                                                $titleAddition = '';
                                            }
                                            $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . $formShortName . $titleAddition . "</td>\n";
                        
                                        } else {
                                            $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . $prData[$arrFieldNames[$m]] . "</td>\n";
                                        }
                                    }
                                    $prView = "form_" . $prData['form_no'];
                                    //echo "<td align=\"right\" class=\"bts\" style=\"padding-left:10px;\">" . $objForm->buildOptionList($prData['id'], $prData['status'], $mode, $sub, $prData['id_case_mgr'], $prData['form_no'],'',$ast['flag_view'],$ast['flag_edit'],$ast['flag_create']) . "</td>\n";
                                    // changed so that the option list is built based on the case mgr id from the student record, NOT from the form record -- current cm can view all forms regardless of what the case mgr is on them is. sl 10/1/02
                                    
                                    $html .= "<td align=\"right\" class=\"bts\"  style=\"white-space:nowrap\">" . $objForm->buildOptionListAccess($prData, $area, $prView) . "</td>\n";
                                
                                    $html .= "</tr>\n<tr>\n<td colspan=\"" . (count($arrFieldLabels) + 1) . "\"><img src='images/line_grey.gif' height=1 width='100%'></td>\n</tr>\n";
                                }
                            }
                        }
                    } elseif($arrData['form_no'] == '013') {
                        #
                        # GET PR
                        #
                        ########################################################################
                        $prObjForm = new form();
                        if($sessUserMinPriv == $UC_PG) {
                            //$ifspForms = $prObjForm->form_013_has_children($arrData['id'], true);
                            $ifspForms = $prObjForm->form_013_get_archived_ifsp($arrData['id'], true);
                        } else {
                            //$ifspForms = $prObjForm->form_013_has_children($arrData['id'], false);
                            $ifspForms = $prObjForm->form_013_get_archived_ifsp($arrData['id'], false);
                        }
        
                        if($ifspForms !== -1) {
                            #
                            # GET THE COUNT OF PROGRESS REPORTS
                            #
                            $prCount = count($ifspForms);
        
        
                            if($prCount > 0) {
                                for ($x = 0; $x<$prCount; $x++) {
                                    #
                                    # EXTRACT INDIVIDUAL FORM
                                    #
                                    $prData = $ifspForms[$x];
                                    
                                    if(1) {
                                        $html .= $this->displayRow($arrFieldLabels, $arrFieldNames, $prData, "(Archived)", $objForm, $FORM_NAMES, $area, $view, 15, 10);
                                    
                                    } else {
                                        #
                                        # next three lines go and get abbreviated name of the form for display sl 12/2/01
                                        #
                                        $formCode = "form_" . $prData['form_no'];
                                        $formTitleData = $FORM_NAMES[$formCode];
                                        $formShortName = $formTitleData['shortName'];
            
                                        //
                                        // GET THE IFSP TYPE
                                        if($prData['form_no'] == '013') {
                                            // get the ifsp type
                                            $form013Arr = getForm013($prData['id']);
                                            $ifsptype = $form013Arr['ifsptype'];
                                            $formShortName .= " ($ifsptype)";
                                        }
            
                                        $html .= "<tr>\n";
                                        for ($m = 0; $m < count($arrFieldLabels); $m++) { 
                                            
                                            if ($m == 0) { $padding = 15; } else { $padding = 10; }
                                            #
                                            # 031804-JL COPIED FROM ABOVE AS THIS SECTION DIDN'T SEEM TO BE WORKING PROPERLY
                                            #
                                            if ($arrFieldNames[$m] == "date" || $arrFieldNames[$m] == "create_date") {
                                                $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . My_Classes_iepFunctionGeneral::htmlEncode(My_Classes_iepFunctionGeneral::ate_massage($prData[$arrFieldNames[$m]], 'm/d/y'), true) . "</td>\n";
                            
                                            } elseif ($arrFieldNames[$m] == "status")  {
                                                $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]> " .$prObjForm->pageStatus($prData['status'],$prData['page_status']). "</td>\n";
                            
                                            } elseif ($arrFieldNames[$m] == "form_no")  {
                                                if($prData['title'] != '' ) {
                                                    $titleAddition = " (".$prData['title'] .")";
                                                } else {
                                                    $titleAddition = '';
                                                }
                                                $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . $formShortName . $titleAddition . "</td>\n";
                            
                                            } else {
                                                $html .= "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap[$m]>" . $prData[$arrFieldNames[$m]] . "</td>\n";
                                            }
                                        }
                                        $view = "form_" . $prData['form_no'];
                                        $html .= "<td align=\"right\" class=\"bts\"  style=\"white-space:nowrap\">" . $objForm->buildOptionListAccess($prData, $area, $view) . "</td>\n";
                                        $html .= "</tr>\n<tr>\n<td colspan=\"" . (count($arrFieldLabels) + 1) . "\"><img src='images/line_grey.gif' height=1 width='100%'></td>\n</tr>\n";
                                    }
                                }
                            }
                        }
                    }
                    # ###############################################################################################################################################
                    # ###############################################################################################################################################
                }
            }

        $html .= "</table>";
        return $html;
    }
    
    
    function displayRow($arrFieldLabels, $arrFieldNames, $arrData, $titleAddition, $objForm, $FORM_NAMES, $area, $view, $paddingFirst=0, $paddingOthers=10, $mergeStudentForms = false)
    {
        global $sessIdUser;
        
        //if (1000254 == $sessIdUser) pre_print_r($arrData);
        //echo "titleAddition: $titleAddition<BR>";
        if("(Archived)" == $titleAddition) {
            $menuLimiterArr = array_flip(array( 'view', 'edit', 'delete', 'log', 'print', 'finalize'));
        } else {
            $menuLimiterArr = '';
        }
        $formCode = "form_" . $arrData['form_no'];
        $formTitleData = $FORM_NAMES[$formCode];
        $formShortName = $formTitleData['shortName'];

        #
        # get the count of supp pages
        #
        $suppDisplay = $objForm->suppDisplay($arrData['id'], 'form_'.$arrData['form_no'], $arrData['status']);

        // GET THE IFSP TYPE
        if($arrData['form_no'] == '013') {
            // get the ifsp type
            $form013Arr = getForm013($arrData['id']);
            $ifsptype = $form013Arr['ifsptype'];
            if("(Archived)" == $titleAddition) {
                $formShortName .= " ";
            } else {
                $formShortName .= " ($ifsptype)";
            }
        }


        if($arrData['form_no'] == '024') {
            //
            // get token for this session
            //
            require_once('class_session_tokenizer.php');
            $sessToke = new session_tokenizer();
            $token = $sessToke->update_token();
            //echo "token: $token<BR>";
            #echo session_id() . "<BR>";
            
            // add the token to the links and update the url
        }

        $html =  "<tr>\n";
        for ($j = 0; $j < count($arrFieldLabels); $j++) { 
            if ($j == 0) { $padding = $paddingFirst; } else { $padding = $paddingOthers; }
            $html .= $this->displayCell($arrFieldNames[$j], $arrData, $formShortName, $titleAddition, $padding, $arrNowrap, $objForm->pageStatus($arrData['status'], $arrData['page_status']), $suppDisplay);
        }
        $view = "form_" . $arrData['form_no'];
        
        #
        # IF THIS IS AN IEP, WE NEED THE ID OF THE STUDENT IN THE BUILD OPTION LIST FUNCTION
        #
//         if($arrData['form_no'] == '004') { // code commented out because id_student is being set in arrData when array first set
//             $arrData['id_student'] = $pkey;
//         }
        if(true == $mergeStudentForms)
        {
            $html .=  "<td align=\"right\" class=\"bts\"  style=\"white-space:nowrap\">" . $this->mergeStudentFormsMenu($arrData['id_student'] . "|" . $arrData['form_no'], $arrData['id']) . "</td>\n";
        } else {
            $html .=  "<td align=\"right\" class=\"bts\"  style=\"white-space:nowrap\">" . $objForm->buildOptionListAccess($arrData, $area, $view, '', $menuLimiterArr) . "</td>\n";
        }
        $html .=  "</tr>\n<tr>\n<td colspan=\"" . (count($arrFieldLabels) + 1) . "\"><img src='images/line_grey.gif' height=1 width='100%'></td>\n</tr>\n";
        return $html;
    }
    
    function displayCell($fieldName, $arrData, $formShortName, $titleAddition, $padding, $arrNowrap, $pageStatus, $suppDisplay)
    {
        //echo "titleAddition: $titleAddition<BR>";
        $html = '';
        if ($fieldName == "date" || $fieldName == "create_date") {
            $html .=  "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap>" . My_Classes_iepFunctionGeneral::htmlEncode(My_Classes_iepFunctionGeneral::date_massage($arrData[$fieldName], 'm/d/y'), true) . "</td>\n";
        
        } elseif ($fieldName == "status")  {
            $html .=  "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap>" . $pageStatus .$suppDisplay. "</td>\n";
        
        } elseif ($fieldName == "form_no")  {
            $html .=  "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap>" . $formShortName . $titleAddition . "</td>\n";
        
        } else {
            $html .=  "<td class=\"bts\" style=\"padding-left:{$padding}px;\"$arrNowrap>" . $arrData[$fieldName] . "</td>\n";
        }

        return $html;

    }
}
