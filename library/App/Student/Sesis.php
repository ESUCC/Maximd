<?php
class App_Student_Sesis // file: class_nssrs_collect_08
{

    var $outputData;	// data to output
    var $sesisFilename; // location of output
    var $studentsProcessedArr; // students processed so far

    var $formObj;   // container for the form object used to access form functions

    var $studentID;

    var $nssrsSubmissionPeriod = "";// 035 grabbed from admin_settings table
    var $nssrsSnapshotDate = "";    // 003 grabbed from admin_settings table
    var $nssrsTransitionCutoffDate = "8/31/2008"; // used?
    var $mostRecentIEP;
    var $mostRecentDraftIEP;

    var $mostRecentIEPCard;
    var $mostRecentDraftIEPCard;
    var $mostRecentIEPCardUsed;
    var $iepUsed = null;
    var $mdtUsed = null;

    var $mostRecentMDT;
    var $mostRecentMDTCard;

    var $mostRecentDraftMDT;
    var $mostRecentDraftMDTCard;

    var $mostRecent009;
    var $mostRecent012;
    var $mostRecent013 = -1;
    var $mostRecentDraft013;
    var $studentData;
    var $JSmodifiedCode;


    // 20090304 jlavere - add MH code
    var $disPrimeArrLabel = array("Autism (AU)",
        "Behavioral Disorder (BD)",
        "Deaf Blindness (DB)",
        "Hearing Impairment (HI)",
        "Mental Handicap",
        "Mental Handicap: Mild (MH:MI)",
        "Mental Handicap: Moderate (MH:MO)",
        "Mental Handicap: Severe/Profound (MH:S/P)",
        "Multiple Impairments (MULTI)",
        "Orthopedic Impairment (OI)",
        "Other Health Impairment (OHI)",
        "Specific Learning Disability (SLD)",
        "Speech Language Impairment (SLI)",
        "Traumatic Brain Injury (TBI)",
        "Visual Impairment (VI)",
        "Developmental Delay (DD)");
    var $disPrimeArrValue = array("AU",
        "BD",
        "DB",
        "HI",
        "MH",
        "MHMI",
        "MHMO",
        "MHSP",
        "MULTI",
        "OI",
        "OHI",
        "SLD",
        "SLI",
        "TBI",
        "VI",
        "DD");

    function __construct() {
        $this->getAdminReportingSettings();
        $this->build_validation();
        $this->JSmodifiedCode = "onFocus=\"javascript:modified('', '', '', '', '', '');\"";

    }

    function sesis_collection($studentData) {

        if(!is_array($studentData)) {
            // student data is most likely a student id in this case
            $this->studentID = $studentData;
            return $this->sesis_collection_wSearch($studentData);
        }

        $this->studentID = $studentData['id_student'];
        $this->studentData = $studentData;
        #
        # COLLECT THE SESIS DATA (IN FUNCTION GENERAL)
        #
        $this->sesisData = $this->build_sesis_data($studentData);

        $this->validate_sesis_data($this->sesisData);

        return $this->sesisData;
    }
    function sesis_collection_transfer(&$transferData)
    {

        $this->studentID = $studentData['id_student'];

        #
        # SET SESIS DATA FROM TRANSFER RECORD
        #
        $sesisData = array();
        for($n=1; $n<=52; $n++)
        {
            $keyNum = substr('000'.$n, -3 , 3);
            $sesisData[$keyNum] = $transferData['nssrs_' . $keyNum];
            unset($transferData['nssrs_' . $keyNum]);
        }

        $this->sesisData = $sesisData;
        $this->studentData = $transferData;
        $this->studentID = $transferData['id_student'];
        $this->exclude_from_nssrs_report = $transferData['exclude_from_nssrs_report'];

        $this->sesisData = $this->build_sesis_data_transfer($sesisData);

        $ageArr = Model_Table_StudentTable::age_calculate(getdate(strtotime($transferData['dob'])), getdate(strtotime($this->mostRecentMDT['date_mdt'])));
        $ageAtSubmissionDate = $ageArr['years'];
        $this->mostRecentMDT_ageAtDateNotice = $ageAtSubmissionDate;
        $this->mostRecentMDT_date_notice = $this->mostRecentMDT['date_mdt'];



        $this->validate_sesis_data($this->sesisData);

        $this->sesisData['035'] = $this->nssrsSubmissionPeriod;
        $this->sesisData['003'] = $this->nssrsSnapshotDate;

        return $this->sesisData;
    }

//    function getCutoffDatesFromDb() {
//
//        $sql = "select * from admin_settings; \n";
//        $result = xmlRpcslqExec($sql, $errorId, $errorMsg, true, false);
//        //print_r($result);
//        if(false === $result) {
//            include("error.php");
//            exit;
//        }
//        return $result;
//    }

    function sesis_collection_wSearch($id_student) {


        $studentObj = new Model_Table_StudentTable();
        $studentRows = $studentObj->studentInfo($id_student);
        if(count($studentRows)) {
            $studentData = $studentRows[0];
            $this->studentID = $studentData['id_student'];
            $this->studentData = $studentData;
            #
            # COLLECT THE SESIS DATA (IN FUNCTION GENERAL)
            #
            $this->sesisData = $this->build_sesis_data($studentData);

            $this->validate_sesis_data($this->sesisData);

            return $this->sesisData;
        } else {
            return false;
        }
    }

    function build_most_recent_form_data_draft()
    {
        // these return -1 when the forms do not exist
        $this->mostRecentDraftIEP = $this->formObj->getMostRecentDraftIEP($this->studentID);
        $this->mostRecentDraft013 = form_013::getMostRecentDraftForm013($this->studentID);
        $this->mostRecentDraftMDT = $this->formObj->getMostRecentDraftMDT($this->studentID);
        $this->mostRecentDraftMDTCard = $this->formObj->getMostRecentDraftMDTCard($this->studentID);
        $this->mostRecentDraftIEPCard = $this->formObj->getMostRecentDraftIEPCard($this->studentID);
    }

    function laterDate($date1, $date2)
    {
        if(!isset($date1) && !isset($date2))
        {
            // neither date is set
            return 0;

        } elseif(isset($date2) && !isset($date1)) {
            // use date 2
            return 2;

        } elseif(isset($date1) && !isset($date2)) {
            // use date 1
            return 1;

        } elseif(strtotime($date2) > strtotime($date1)) {
            // use date 2
            return 2;

        } elseif(strtotime($date1) > strtotime($date2)) {
            // use date 1
            return 1;

        } elseif(strtotime($date1) == strtotime($date2)) {
            // dates are the same
            return 3;
        }
    }

    function build_most_recent_form_data($DOB)
    {
        global $sessIdUser;

        $DOB = date('m/d/Y', strtotime($DOB));

        // ==========================================================================================
        #
        # GET IEP FORM DATA
        #
        $form004Obj = new Model_Table_Form004();
        $this->mostRecentIEP = $form004Obj->mostRecentFinalForm($this->studentID, 'date_conference');
        if($this->mostRecentIEP instanceof Zend_Db_Table_Row) {
            $this->mostRecentIEP = $this->mostRecentIEP->toArray();
        }
        // do no use ieps that are over one year old
        if(strtotime('today - 1 year') > strtotime($this->mostRecentIEP['date_conference']))
        {
            $this->mostRecentIEP = -1;
        }

        $form023Obj = new Model_Table_Form023();
        $this->mostRecentIEPCard = $form023Obj->mostRecentFinalForm($this->studentID, 'date_conference');
        if($this->mostRecentIEPCard instanceof Zend_Db_Table_Row) {
            $this->mostRecentIEPCard = $this->mostRecentIEPCard->toArray();
        }
        // do no use iep cards that are over one year old
        if(strtotime('today - 1 year') > strtotime($this->mostRecentIEPCard['date_conference']))
        {
            $this->mostRecentIEPCard = -1;
        }

        //
        // determine which IEP/IEP data card to use
        $dateCompareKey = $this->laterDate($this->mostRecentIEP['date_conference'], $this->mostRecentIEPCard['date_conference']);
        switch($dateCompareKey)
        {
            case '0':
                //echo "both dates null<BR>";
                break;
            case '1':
                //echo "use mostRecentIEP<BR>";
                $this->iepUsed = "iep";
                break;
            case '2':
                //echo "use mostRecentIEPCard<BR>";
                $this->mostRecentIEP = $this->mostRecentIEPCard;
                $this->mostRecentIEPCardUsed = true;
                $this->iepUsed = "iepcard";
                break;
            case '3':
                //echo "dates equal<BR>";
                $dateCompareKey = $this->laterDate($this->mostRecentIEP['timestamp_created'], $this->mostRecentIEPCard['timestamp_created']);
                switch($dateCompareKey)
                {
                    case '0':
                        //echo "both dates null<BR>";
                        break;
                    case '1':
                        //echo "use mostRecentIEP<BR>";
                        $this->iepUsed = "iep";
                        break;
                    case '2':
                        //echo "use mostRecentIEPCard<BR>";
                        $this->mostRecentIEP = $this->mostRecentIEPCard;
                        $this->mostRecentIEPCardUsed = true;
                        $this->iepUsed = "iepcard";
                        break;
                    case '3':
                        //echo "dates equal<BR>";
                        $this->iepUsed = "iep";
                        break;
                }
                break;
        }
        if('iep' == $this->iepUsed) //'' != $this->mostRecentIEP['date_conference']
        {

            $seisServicesDropLabels = array_values(App_ValueLists::getLabelValues("seisServicesDrop_v2"));
            $seisServicesDropValues = array_keys(App_ValueLists::getLabelValues("seisServicesDrop_v2"));

            $servicesDropOverTwoLabels = array_values(App_ValueLists::getLabelValues("servicesDropOverTwo"));
            $servicesDropOverTwoValues = array_keys(App_ValueLists::getLabelValues("servicesDropOverTwo"));

//            App_ValueLists::getLabelValues("seisServicesDrop_v2", $seisServicesDropLabels, $seisServicesDropValues);
//            App_ValueLists::getLabelValues("servicesDropOverTwo", $servicesDropOverTwoLabels, $servicesDropOverTwoValues);
            $seisServicesDropLabels = array_merge($seisServicesDropLabels, $servicesDropOverTwoLabels);
            $seisServicesDropValues = array_merge($seisServicesDropValues, $servicesDropOverTwoValues);

            $locationValueListName = "serviceLocation";
            $devDelayCategory = Model_Table_IepStudent::devDelay ( $DOB, $this->mostRecentIEP['date_conference'] );
            switch($devDelayCategory) {
                case 0:
                    $locationValueListName = "serviceLocationBirthToTwo";
                    $seisServicesDropUnder2 = App_ValueLists::getLabelValues("servicesDropBirthToTwo", $servicesDropBirthToTwoLabels, $servicesDropBirthToTwoValues);
                    break;
                case 1:
                    $locationValueListName = "serviceLocationThreeToFive";
                    break;
                default:
                    $locationValueListName = "serviceLocationSixTo21";
            }

            if(!empty($this->mostRecentIEP['primary_disability_drop']) && !in_array($this->mostRecentIEP['primary_disability_drop'], $seisServicesDropValues)) {
                $seisServicesDropLabels = array_merge($seisServicesDropLabels, array($this->mostRecentIEP['primary_disability_drop']));
                $seisServicesDropValues = array_merge($seisServicesDropValues, array($this->mostRecentIEP['primary_disability_drop']));
            }

            $this->IEP_locationValueListName = $locationValueListName;
            $this->IEP_seisServicesDropLabels = $seisServicesDropLabels;
            $this->IEP_seisServicesDropValues = $seisServicesDropValues;

            $this->IEP_seisServicesDropArr = array_merge($this->IEP_seisServicesDropLabels, $this->IEP_seisServicesDropValues);

            $ageArr = Model_Table_StudentTable::age_calculate(getdate(strtotime($DOB)), getdate(strtotime($this->mostRecentIEP['date_conference'])));
            $ageAtSubmissionDate = $ageArr['years'];
            $this->mostRecentIEP_ageAtDateNotice = $ageAtSubmissionDate;

            $this->mostRecentIEP_date_notice = $this->mostRecentIEP['date_conference'];

        }
        // ==========================================================================================

        // ==========================================================================================
        #
        # GET MDT FORM DATA
        #
        $form002Obj = new Model_Table_Form002();
        $this->mostRecentMDT = $form002Obj->mostRecentFinalForm($this->studentID, 'date_mdt');
        if($this->mostRecentMDT instanceof Zend_Db_Table_Row) {
            $this->mostRecentMDT = $this->mostRecentMDT->toArray();
        }
        $this->mostRecentMDT = is_null($this->mostRecentMDT) ? -1 : $this->mostRecentMDT;

        $form022Obj = new Model_Table_Form022();
        $this->mostRecentMDTCard = $form022Obj->mostRecentFinalForm($this->studentID, 'date_mdt');
        if($this->mostRecentMDTCard instanceof Zend_Db_Table_Row) {
            $this->mostRecentMDTCard = $this->mostRecentMDTCard->toArray();
        }
        $this->mostRecentMDTCard = is_null($this->mostRecentMDTCard) ? -1 : $this->mostRecentMDTCard;

        //
        // WARNING = HACK
        // USING THE NEXT BACK MDT WITH THE DATE OF THE CURRENT MDT
        if('A' == $this->mostRecentMDT['mdt_00603e2a']) {
            $mdtList = $this->formObj->getMDTs($this->studentID);
            foreach($mdtList as $k => $mdt) {
                if(1 == $k) {
                    $tempMdt = $mdt;
                }
            }

            $tempMdtCard = $this->mostRecentMDTCard;
            //pre_print_r($this->mostRecentMDTCard);
            if( -1 == $this->mostRecentMDTCard ) {
                $mdtCardList = $this->formObj->getMDTs($this->studentID);
                foreach($mdtCardList as $k => $mdtCard) {
                    if(1 == $k) {
                        $tempMdtCard = $mdtCard;
                    }
                }
            }

            if($tempMdt['date_mdt'] >= $tempMdtCard['date_mdt']) {
                $tempMdt['date_mdt'] = $this->mostRecentMDT['date_mdt'];
                $this->mostRecentMDT = $tempMdt;
                $this->mostRecentMDTCard = -1;
            } else {
                $tempMdtCard['date_mdt'] = $this->mostRecentMDTCard['date_mdt'];
                $this->mostRecentMDTCard = $tempMdtCard;
                $this->mostRecentMDT = -1;
            }

        }

        $mostRecentMDT_date = $this->mostRecentMDT['date_mdt'];
        $mostRecentMDTCard_date = $this->mostRecentMDTCard['date_mdt'];

        // determine which MDT/MDT data card to use
        //
        $dateCompareKey = $this->laterDate($this->mostRecentMDT['date_mdt'], $this->mostRecentMDTCard['date_mdt']);
        switch($dateCompareKey)
        {
            case '0':
                //echo "both dates null<BR>";
                break;
            case '1':
                //echo "use mostRecentMDT<BR>";
                $this->mdtUsed = "mdt";
                break;
            case '2':
                //echo "use mostRecentMDTCard<BR>";
                $this->mostRecentMDT = $this->mostRecentMDTCard;
                $this->mostRecentMDTCardUsed = true;
                $this->mdtUsed = "mdtcard";
                break;
            case '3':
                //echo "dates equal<BR>";
                $dateCompareKey = $this->laterDate($this->mostRecentMDT['timestamp_created'], $this->mostRecentMDTCard['timestamp_created']);
                switch($dateCompareKey)
                {
                    case '0':
                        //echo "both dates null<BR>";
                        break;
                    case '1':
                        //echo "use mostRecentMDT<BR>";
                        $this->mdtUsed = "mdt";
                        break;
                    case '2':
                        //echo "use mostRecentMDTCard<BR>";
                        $this->mostRecentMDT = $this->mostRecentMDTCard;
                        $this->mostRecentMDTCardUsed = true;
                        $this->mdtUsed = "mdtcard";
                        break;
                    case '3':
                        //echo "dates equal<BR>";
                        $this->mdtUsed = "mdt";
                        break;
                }
                break;
        }


        if('mdt' == $this->mdtUsed || 'mdtcard' == $this->mdtUsed)
        {
            $this->mostRecentMDT_date = $this->mostRecentMDT['date_mdt'];

            if(!$this->check_MDT_validity($this->mostRecentMDT_date))
            {
                // no useable MDT
                unset($this->mostRecentMDT);
                unset($this->mostRecentMDT_date);
            }
        }
        // if the mdt is more than three years old
        // check to see if there is a determination notice
        // ==========================================================================================

        $form009Obj = new Model_Table_Form009();
        $this->mostRecent009 = $form009Obj->mostRecentFinalForm($this->studentID);
        $this->mostRecent009 = is_null($this->mostRecent009) ? -1 : $this->mostRecent009;

        if('iep' != $this->iepUsed && 'iepcard' != $this->iepUsed) {
            $form013Obj = new Model_Table_Form013();
            $this->mostRecent013 = $form013Obj->mostRecentFinalForm($this->studentID);
            $this->mostRecent013 = is_null($this->mostRecent013) ? -1 : $this->mostRecent013;
        } else {

        }
        if('' != $this->mostRecent013['id_form_013'] && -1 == $this->mostRecentIEP) {
            $ageArr = Model_Table_StudentTable::age_calculate(getdate(strtotime($DOB)), getdate(strtotime($this->mostRecent013['date_notice'])));
            $ageAtSubmissionDate = $ageArr['years'];
            $this->mostRecentIEP_ageAtDateNotice = $ageAtSubmissionDate;
            $this->mostRecentIEP_date_notice = $this->mostRecentIEP['date_conference'];

            $seisServicesDropLabels = array_values(App_ValueLists::getLabelValues("serviceLocationBirthToTwo"));
            $seisServicesDropValues = array_keys(App_ValueLists::getLabelValues("serviceLocationBirthToTwo"));
            $this->IEP_locationValueListName = "serviceLocationBirthToTwo";
            $this->IEP_seisServicesDropLabels = $seisServicesDropLabels;
            $this->IEP_seisServicesDropValues = $seisServicesDropValues;
            $this->IEP_seisServicesDropArr = array_merge($this->IEP_seisServicesDropLabels, $this->IEP_seisServicesDropValues);
        }


        $ageArr = Model_Table_StudentTable::age_calculate(getdate(strtotime($DOB)), getdate(strtotime($this->mostRecentMDT['date_mdt'])));
        $ageAtSubmissionDate = $ageArr['years'];
        $this->mostRecentMDT_ageAtDateNotice = $ageAtSubmissionDate;
        $this->mostRecentMDT_date_notice = $this->mostRecentMDT['date_mdt'];

    }

    function validate_sesis_data($sesisData)
    {
        $this->arrValidationResults = array();
        #
        # LOAD VALIDATION RULES FROM USER EDITABLE FILE
        # BUILDS THE sesisValidation VARIABLE IN THIS OBJECT
        #
        $evalObj = new App_Student_SesisValidate();
        $evalObj->validate($sesisData, $this->sesisValidation, $this->arrValidationResults);
    }

    function sesis_checkDate($thedate, $emptyOk = true)
    {
        $thedate = self::date_massage($thedate);
        list($mm,$dd,$yyyy)=explode("/", $thedate);
        if("" == $thedate && $emptyOk) return true;
        if(is_numeric($yyyy) && is_numeric($mm) && is_numeric($dd) && checkdate($mm,$dd,$yyyy));
        return false;
    }
    function save($studentID, $ad)
    {
        global $sessIdUser;

        $pkeyName	= "id_student";
        $tableName	= "iep_student";

        $arrFieldList = array(
            "unique_id_state" 			=> 	array("", "", "", ""),
            "sesis_exit_date" 			=> 	array("", "", "", ""),
            "sesis_exit_code" 			=> 	array("", "", "", ""),
            "parental_placement" 			=> 	array("", "", "", ""),
        );


        // SAVE THE STUDENT
        require_once("iep_class_student.inc");
        $saveStdObj = new student();

        // ================================================================================================

        if('' != $ad['unique_id_state']) {
            if(check_unique_id_state($ad['unique_id_state'], $ad['student'])) {
                #echo "id is valid and does not currently exist in the system<BR>";
                $ad['unique_id_state_duplicate'] = '';
            } else {
                // place it in the duplicate id field and erase the real state id field
                $ad['unique_id_state_duplicate'] = $ad['unique_id_state'];
                $ad['unique_id_state'] = '';
            }

        }
        if (!$save = $saveStdObj->save($studentID, $arrFieldList, $ad, $tableName, $pkeyName)) {
            $errorId = $saveStdObj->errorId;
            $errorMsg = $saveStdObj->errorMsg;
            include_once("error.php");
            exit;
        }


    }

    function saveTransfer($transferID, $data)
    {
        global $sessIdUser;

        //pre_print_r($data);

        // Code Description
        // 1 Occupational Therapy
        // 2 Physical Therapy
        // 3 Speech-Language Therapy
        // 4 Occupational Therapy - Physical Therapy
        // 5 Physical Therapy - Speech-Language Therapy
        // 6 Speech-Language Therapy - Occupational Therapy
        // 7 All
        // 8 None

        $pkeyName	= "id_nssrs_transfers";
        $tableName	= "nssrs_transfers";

        $arrFieldListAll = array(
            "nssrs_001" 			=> 	array("", "", "", ""),
            "nssrs_002" 			=> 	array("", "", "", ""),
            "nssrs_005" 			=> 	array("", "", "", ""),
            "nssrs_011" 			=> 	array("", "", "", ""),
            "nssrs_016" 			=> 	array("", "", "", ""),
            "nssrs_023" 			=> 	array("", "", "", ""),
            "nssrs_032" 			=> 	array("", "", "", ""),
            "nssrs_033" 			=> 	array("", "", "", ""),
            "nssrs_034" 			=> 	array("", "", "", ""),
            "nssrs_044" 			=> 	array("", "", "", ""),
            "nssrs_047" 			=> 	array("", "", "", ""),
            "nssrs_048" 			=> 	array("", "", "", ""),
            "nssrs_050" 			=> 	array("", "", "", ""),
            "nssrs_051" 			=> 	array("", "", "", ""),
            "nssrs_052" 			=> 	array("", "", "", ""),
            "transfer_name_full"    => 	array("", "", "", ""),
            "exclude_from_nssrs_report"    => 	array("", "", "", ""),
        );

        $arrFieldList = array();
        foreach($arrFieldListAll as $fieldName => $validationArr)
        {
            if(isset($data[$fieldName]) || 'exclude_from_nssrs_report' == $fieldName) $arrFieldList[$fieldName] = $validationArr;

        }

        if (!$save = $this->insertOrUpdate($transferID, $arrFieldList, $data, $tableName, $pkeyName)) {
            $errorId = $saveStdObj->errorId;
            $errorMsg = $saveStdObj->errorMsg;
            include_once("error.php");
            exit;
        }


    }

    function build_validation()
    {
        $nssrsSubmissionPeriod = $this->nssrsSubmissionPeriod;
        $nssrsSnapshotDate = $this->nssrsSnapshotDate;
        $juneCutoff = $this->getJuneCutoff();
        $octoberCuttoff = $this->octoberCuttoff;

        $this->sesisValidation = array(
            '001' => array("evalPhp", 'if( 7 == strlen($arrData[\'001\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '002' => array("evalPhp", 'if( 3 == strlen($arrData[\'002\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '003' => array("evalPhp", 'if( $arrData[\'003\'] == \''.$nssrsSnapshotDate.'\' ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '005' => array("evalPhp", 'if( 10 == strlen($arrData[\'005\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '011' => array("evalPhp", 'if( "" != $arrData[\'011\'] && is_numeric($arrData[\'011\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '016' => array("evalPhp", 'if( 1 <= $arrData[\'016\'] || 8 >= $arrData[\'016\']) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '023' => array("evalPhp", 'if(1 == $arrData[\'023\'] || 2 == $arrData[\'023\'] ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '032' => array("evalPhp", 'if( -1 == $arrData[\'032\']  || 0 == $arrData[\'032\']  || 1 == $arrData[\'032\'] ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '033' => array("evalPhp", 'if( strlen($arrData[\'033\']) == 10 ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '034' => array("evalPhp", 'if(\'\' == $arrData[\'034\'] || strtotime($arrData[\'034\']) >= strtotime(\''.$juneCutoff.'\') && ( \'\' == $arrData[\'052\'] || (\'\' != $arrData[\'052\'] && 10 == strlen($arrData[\'034\']) && $arrData[\'034\'] >= '.$octoberCuttoff.') )) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '035' => array("evalPhp", 'if( $arrData[\'035\'] == \''.$nssrsSubmissionPeriod.'\' ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '044' => array("evalPhp", 'if(\'\' != $arrData[\'044\'] && -1 != $arrData[\'044\'] ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            //'047' => array("evalPhp", 'if( "Part B" == $arrData[\'047\'] || "Part C" == $arrData[\'047\'] ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '047' => array("evalPhp", 'if(1) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '048' => array("evalPhp", 'if( 1 == $arrData[\'048\'] || 2 == $arrData[\'048\'] ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '050' => array("evalPhp", 'if( !App_Student_SesisValidate::emptyAndNotZero($arrData[\'050\']) && 0 <= $arrData[\'050\'] && 100 >= $arrData[\'050\'] && $arrData[\'050\'] == intval($arrData[\'050\'])) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '051' => array("evalPhp", 'if(( 1 == $arrData[\'051\'] || 0 == $arrData[\'051\'] ) && !App_Student_SesisValidate::emptyAndNotZero($arrData[\'051\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),
            '052' => array("evalPhp", 'if( (\'\' == $arrData[\'052\'] && \'\' == $arrData[\'034\']) || (10 == strlen($arrData[\'034\']) && \'\' != $arrData[\'052\']) ) { echo \'PASS\'; } else { echo \'FAIL\'; }', ''),


        );
    }

    function confirmSetting($settingCode, $settingCodeValueList) {

        App_ValueLists::getLabelValues($settingCodeValueList, $arrLabel, $arrValue);

        for($i=0; $i < count($arrLabel); $i++)
        {
            if( $arrValue[$i] == $settingCode )
            {
                //echo $arrLabel[$i] ." (" . $arrValue[$i] . ")<BR>";
                return true;
            }
        }
        return false;
    }
    function getJuneCutoff() {
        if(date("m", strtotime("today")) >= 7)
        {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today"))));
        } else {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today")) ."-1 year"));
        }
        return $juneCutoff;
    }
    function primary_setting_code($dob, $code, $parentalPlacement)
    {
        global $sessIdUser;
        $settingCode = null;

        $fromIEP = 0;
        $fromIFSP = 0;
        // aged birth thru 2 (part C)

        // 6 thru 22 (part B)


        // determine if the student is part C, part B, or transitional
        $settingCat = $this->primary_setting_category($dob);

        // get the student's setting code
        if('iep' == $this->iepUsed) {
            // does the code from IEP
            $fromIEP = 1;
            $settingCode = $this->mostRecentIEP['primary_service_location'];

        } elseif('iepcard' == $this->iepUsed) {
            $fromIEP = 1;
            $settingCode = $this->mostRecentIEPCard['service_where'];

        } elseif('' != $this->mostRecent013['id_form_013']) {
            $fromIFSP = 1;
            $settingCode = $this->service_whereArr[0];
            $this->IEP_locationValueListName = "service_where";

        } else {
            $this->IEP_locationValueListName = "NO DOC";
        }

        // if null setting code, check exit date
        if('' == $settingCode) {
            $exitDate = $this->studentData['sesis_exit_date'];
            // if exit date valid, release iep date restrictions
            if(strtotime($exitDate) >= strtotime($this->getJuneCutoff())) {
                $iepArr = $this->formObj->getIEPs($this->studentID);
                foreach($iepArr as $row) {
                    if('' != $row['primary_service_location']) {
                        $fromIEP = 1;
                        $settingCode = $row['primary_service_location'];
                        unset($this->IEP_locationValueListName);
                    }
                }
            }
        }
        $settingCode = intval($settingCode); // remove leading zeros
        $this->settingCode = $settingCode;
        $this->settingCat = $settingCat;


        if('' == $settingCode) {
            return null;
        }

        // does the student have an ifsp
        $hasIFSP = "";

        $ageArr = Model_Table_StudentTable::age_calculate(getdate(strtotime($dob)), getdate(strtotime($this->nssrsSubmissionPeriod)));
        $ageAtSubmissionDate = $ageArr['years'];

        $devDelayCategoryAtSubmission = Model_Table_IepStudent::devDelay (self::date_massage($dob), $this->nssrsSubmissionPeriod);
        switch($devDelayCategoryAtSubmission) {
            case 0:
                $convertToLocationName = "sesisExitCodesBirthToTwo";
                break;
            case 1:
            case 2:
                $convertToLocationName = "sesisExitCodesThreeTo21";
                break;
            case 3:
                $convertToLocationName = "sesisExitCodesOver21";
                break;
        }

        if(isset($this->IEP_locationValueListName) && 'NO DOC' == $this->IEP_locationValueListName) return null;

        //if(1000254 == $sessIdUser) echo "convert from " . $this->IEP_locationValueListName . "(" . "" .  ") to " . $convertToLocationName . " " . $settingCat . "<BR>";


        if(isset($this->IEP_locationValueListName) && 'serviceLocationSixTo21' == $this->IEP_locationValueListName) {
            // check if code is in this value list
        }

        // 20101202 jlavere - SRSSUPP-95
        // Can you add a NSSRS rule so that any student who is over 6 years old AND
        // has has code "1"(yes) in the Parental Placement Field (Field 51) will automatically
        // have a code of "14" (private school) added to Field 44 (Primary Setting)
        // regardless of what the student's IEP or Data card say.
        if($ageAtSubmissionDate >= 6 && 1 == $parentalPlacement) {
            return 14;
        }

        // if v9 (zend forms) return now.
        if($fromIEP && 9 <= $this->mostRecentIEP['version_number']) {
            return $settingCode;
        }

        // todo: describe the purpose of the setting category
        // can we skip on v9 forms?
        //if('Part B' == $settingCat || ('Transitional' == $settingCat && $fromIEP) )
        if('Y' == $settingCat ) // IEP
        {
            // Part B
            // convert existing settingCode to setting needed at submission
            if(6 <= $ageAtSubmissionDate) {
                //if(1000254 == $sessIdUser) echo "six and over<BR>";
                if($this->confirmSetting($settingCode, 'serviceLocationThreeToFive'))
                {
                    //if(1000254 == $sessIdUser) echo "11<BR>";
                    return $this->oldThreeToFive_SixTo21($settingCode);
                } else {
                    //if(1000254 == $sessIdUser) echo "12<BR>";
                    return $this->sixTo21_SixTo21($settingCode);
                }

            } elseif(3 <= $ageAtSubmissionDate) {
                //if(1000254 == $sessIdUser) echo "three to five<BR>";
                if($this->confirmSetting($settingCode, 'serviceLocationBirthToTwo'))
                {
                    //if( 1000254 == $sessIdUser) echo "here2";
                    return $this->oldBirthToTwo_newThreeToFive($settingCode);
                } else {
                    //if( 1000254 == $sessIdUser) echo "here1";
                    return $this->oldThreeToFive_ThreeToFive($settingCode, $this->mostRecentIEP['code_4_conversion']);
                }

            } else {
                //if(1000254 == $sessIdUser) echo "under three<BR>";
                if($this->confirmSetting($settingCode, 'serviceLocationBirthToTwo'))
                {
                    return $this->oldBirthToTwo_newThreeToFive($settingCode);
                } else {
                    //if(1000254 == $sessIdUser) "cannot confirm serviceLocationBirthToTwo<BR>";
                }
            }

        } elseif('N' == $settingCat) {
            // Part C
            // IFSP
            // convert existing settingCode to setting needed at submission
            if(6 <= $ageAtSubmissionDate) {

                if($this->confirmSetting($settingCode, 'serviceLocationThreeToFive'))
                {
                    return $this->oldThreeToFive_ThreeToFive($settingCode, $this->mostRecentIEP['code_4_conversion']);
                } else {
                    //if(1000254 == $sessIdUser) echo "cannot confirm serviceLocationThreeToFive<BR>";
                    if($this->confirmSetting($settingCode, 'serviceLocationSixTo21'))
                    {
                        return $this->oldThreeToFive_SixTo21($settingCode);
                    } else {
                        //if(1000254 == $sessIdUser) echo "cannot confirm serviceLocationBirthToTwo<BR>";
                    }
                }

            } elseif(3 <= $ageAtSubmissionDate) {

                if($this->confirmSetting($settingCode, 'serviceLocationBirthToTwo'))
                {
                    return $this->oldBirthToTwo_newBirthToTwo($settingCode);
                } else {
                    if($this->confirmSetting($settingCode, 'serviceLocationThreeToFive'))
                    {
                        return $this->oldThreeToFive_ThreeToFive($settingCode, $this->mostRecentIEP['code_4_conversion']);
                    } else {
                        //if(1000254 == $sessIdUser) echo "cannot confirm serviceLocationBirthToTwo<BR>";
                    }
                }

            } else {
                if($this->confirmSetting($settingCode, 'serviceLocationBirthToTwo'))
                {
                    return $this->oldBirthToTwo_newBirthToTwo($settingCode);
                } else {
                    //if(1000254 == $sessIdUser) "cannot confirm serviceLocationBirthToTwo<BR>";
                }
            }


        }
    }
    function sixTo21_SixTo21($code)
    {
        global $sessIdUser;
        switch($code)
        {
            case '1':
                return 10;
            case '2':
                return 5;
            case '3':
                return 7;
            case '6':
                return 5;
            case '7':
                return 7;
            case '10':
                return 13;
            case '9':
                return 9;
            case '14':
                return 15;

            case '16':
                return 16;
                break;
            case '17':
                return 17;
                break;
            case '18':
                return 18;
                break;
            case '19':
                return 19;
                break;


            default:
                return -1;
        }
    }

    function oldThreeToFive_ThreeToFive($code, $code4conversion)
    {
        global $sessIdUser;
        switch($code)
        {
            case '11':
                return $code4conversion;
            case '12':
                return 6;
            case '13':
                return 6;
            case '10':
                return 8;
            case '9':
                return 7;
            case '22':
                return 7;
            case '23':
                return 5;
            case '15':
                return $code4conversion;

            case '16':
                return 16;
                break;
            case '17':
                return 17;
                break;
            case '18':
                return 18;
                break;
            case '19':
                return 19;
                break;




            default:
                return -1;
        }
    }

    function oldThreeToFive_SixTo21($code)
    {
        global $sessIdUser;
        switch($code)
        {
            case '11':
                return 10;
            case '12':
                return 10;
            case '13':
                return 10;
            case '10':
                return 13;
            case '9':
                return 13;
            case '22':
                return 7;
            case '23':
                return 5;
            case '15':
                return 10;

            default:
                return -1;
        }
    }

    function oldBirthToTwo_newBirthToTwo($code)
    {
        global $sessIdUser;
        switch($code)
        {
            case '4':
                return 2;
            case '8':
                return 2;
            case '10':
                return 1;
            case '9':
                return 1;
            case '22':
                return 2;
            case '19':
                return 3;
            case '21':
                return 3;

            default:
                return -1;
        }
    }

    function oldBirthToTwo_newThreeToFive($code)
    {
        global $sessIdUser;
        switch($code)
        {
            case '4':
                return 5;
            case '8':
                return 4;
            case '10':
                return 8;
            case '9':
                return 7;
            case '22':
                return 7;
            case '21':
                return 'Not Available';

            case '16':
                return 16;
                break;
            case '17':
                return 17;
                break;
            case '18':
                return 18;
                break;
            case '19':
                return 19;
                break;




            default:
                return -1;
        }
    }
    function primary_setting_partB_0thru2($code)
    {
        // Note:  If the student is in PART B (not transitional) then his data must come from an IEP.
        switch($code)
        {
            case '10':
                return 1;
            case '09':
                return 7;
            case '21':
                return '';
            case '04':
                return 5;
            case '08':
                return 4;
            case '22':
                return 7;
            case '19':
                return 9;
            default:
                return -1;
        }
    }
    function primary_setting_partB_3thru5($code)
    {
        // Note:  If the student is in PART B (not transitional) then his data must come from an IEP.
        switch($code)
        {
            case '11':
                return 10;
            case '12':
                return 10;
            case '13':
                return 10;
            case '10':
                return 1;
            case '09':
                return 7;
            case '22':
                return 7;
            case '23':
                return 5;
            case '15':
                return 10;
            default:
                return -1;
        }
    }
    function primary_setting_partB_6thru21($code)
    {
        switch($code)
        {
            case '01':
                return 10;
            case '02':
                return 5;
            case '03':
                return 7;
            case '06':
                return 7;
            case '07':
                return 7;
            case '10':
                return 1;
            case '09':
                return 9;
            case '14':
                return 15;
            default:
                return -1;
        }
    }
    function primary_setting_partC($code)
    {
        //echo "primary_setting_partC: $code<BR>";
        switch($code)
        {
            case '04':
                return 2;
            case '08':
                return 2;
            case '10':
                return 1;
            case '09':
                return 3;
            case '22':
                return 2;
            case '19':
                return 3;
            case '21':
                return 3;
            default:
                return -1;
        }

    }
    function primary_setting_transitional()
    {

    }
    function primary_setting_category($dob)
    {
        if(empty($dob)) {
            return null;
        }
        $ageArr = Model_Table_StudentTable::age_calculate(getdate(strtotime($dob)), getdate(strtotime($this->nssrsSubmissionPeriod)));
        $ageAtSubmissionDate = $ageArr['years'];

        if($ageAtSubmissionDate < 3)
        {
            return "N";
            //return "Part C";
        } elseif($ageAtSubmissionDate >= 4)
        {
            return "Y";
            //return "Part B";
        } else {
            if('iep' == $this->iepUsed || 'iepcard' == $this->iepUsed)
            {
                return "Y";
            } elseif('' != $this->mostRecent013['id_form_013']) {
                return "N";
            }
        }
    }
    function build_hearing_impairment($disability_hi, $disPrime)
    {
        global $sessIdUser;
        switch ( $disPrime )
        {
            case 2:
            case 3:
            case 12:
                if(strlen($disability_hi) > 0) return 1;
                break;

            default:
                return 0;
                break;
        }
        return 0;
    }

    function build_VI_impairment($disability_vi, $disPrime)
    {
        global $sessIdUser;
        switch ( $disPrime )
        {
            case 2:
            case 3:
            case 12:
                if(strlen($disability_vi) > 0) return 1;
                break;

            default:
                return 0;
                break;
        }
        return 0;

    }

    function build_alternate_assessment($aa)
    {
        global $sessIdUser;

        if('t' == $aa || 1 == $aa) {
            return 1;
        } elseif('f' == $aa || 0 == $aa) {
            return 2;
        }
        return 0;

    }

    function build_sesis_data($arr)
    {
        global $sessIdUser;

        $import = array(); # IMPORT RECORD LAYOUT

        $this->init_form();
        $this->build_most_recent_form_data($arr['dob']);

        $this->build_disability_arr();
        $this->build_form013_services();

        $this->ageYears = $arr['age'];

        #################################################################################################

        //  Field#1	County District Number
        //  This is essentially the district number that we user right now.
        //  -- It will look like this “00-0000”
        $import['001'] = new App_Student_SesisItem($this->build_CD($arr['id_county'], $arr['id_district'], $arr['id_school']),'RESIDENT COUNTY DISTRICT','Varchar','7','','County District (00-0000)');

        // Field#2 School Number
        // This is the three digit school number.  They want this on a separate line.
        //     -- It will look like thie “001”
        $import['002'] = new App_Student_SesisItem($arr['id_school'],'RESIDENT SCHOOL','Varchar','3','','School Code');

        // Field#3 School Year Date
        // Every file should have the following date:  2008-06-30
        $import['003'] = new App_Student_SesisItem($this->nssrsSnapshotDate,'School Year Date','Varchar','10','','School Code');

        // Field#4 Unused – Leave Blank
        $import['004'] = new App_Student_SesisItem('','','','','');

        // Field#5 NDE Student ID
        // This is the 10 digit NSSRS ID# from the EDIT STUDENT PAGE
        $import['005'] = new App_Student_SesisItem($arr['unique_id_state'],'NSSRSID','Int','10','','nssrs id');

        // Field#6 Unused – Leave Blank
        $import['006'] = new App_Student_SesisItem('','','','','');

        // Field#7 Unused – Leave Blank
        $import['007'] = new App_Student_SesisItem('','','','','');

        // Field#8 Unused – Leave Blank
        $import['008'] = new App_Student_SesisItem('','','','','');

        // Field#9 Unused – Leave Blank
        $import['009'] = new App_Student_SesisItem('','','','','');

        // Field#10 Unused – Leave Blank
        $import['010'] = new App_Student_SesisItem('','','','','');


        // Field#11 Primary Disability
        // This is the primary Disability from page 3 of the most recent MDT form.
        $import['011'] = new App_Student_SesisItem($this->build_disPrime(),'PRIMARY DISABILITY','Int','4','','Required, must be numeric, see Initial Disability Table below');//,''

        // Field#12 Unused – Leave Blank
        $import['012'] = new App_Student_SesisItem('','','','','');

        // Field#13 Unused – Leave Blank
        $import['013'] = new App_Student_SesisItem('','','','','');

        // Field#14 Unused – Leave Blank
        $import['014'] = new App_Student_SesisItem('','','','','');

        // Field#15 Unused – Leave Blank
        $import['015'] = new App_Student_SesisItem('','','','','');


        // Field#16 Related Services
        // This section is exactly the same as section 1-15 on the current SESIS report.
        // We will want to look at the most recent IEP (provided it is less than 1 year old).
        // This data is pulled from the primary and related services on page 6 of the IEP.
        // All this report is interested in is the following three services: Speech-Language Therapy - Occupational Therapy.
        // The codes below shall be used for reporting the various combinations of these three services.
        $import['016'] = new App_Student_SesisItem($this->build_speechLngThrpy(),'Related Services','Int','1','','','');

        // Field#17 Unused – Leave Blank
        $import['017'] = new App_Student_SesisItem('','','','','');

        // Field#18 Unused – Leave Blank
        $import['018'] = new App_Student_SesisItem('','','','','');

        // Field#19 Unused – Leave Blank
        $import['019'] = new App_Student_SesisItem('','','','','');

        // Field#20 Unused – Leave Blank
        $import['020'] = new App_Student_SesisItem('','','','','');

        // Field#21 Unused – Leave Blank
        $import['021'] = new App_Student_SesisItem('','','','','');

        // Field#22 Unused – Leave Blank
        $import['022'] = new App_Student_SesisItem('','','','','');

        // Field#23 Unused – Leave Blank
        $import['023'] = new App_Student_SesisItem($this->build_alternate_assessment($arr['alternate_assessment']),'Alternate Assessment','Int','1','','Alternate Assessment must be 1 or 2.');

        // Field#24 Unused – Leave Blank
        $import['024'] = new App_Student_SesisItem('','','','','');

        // Field#25 Unused – Leave Blank
        $import['025'] = new App_Student_SesisItem('','','','','');

        // Field#26 Unused – Leave Blank
        $import['026'] = new App_Student_SesisItem('','','','','');

        // Field#27 Unused – Leave Blank
        $import['027'] = new App_Student_SesisItem('','','','','');

        // Field#28 Unused – Leave Blank
        $import['028'] = new App_Student_SesisItem('','','','','');

        // Field#29 Unused – Leave Blank
        $import['029'] = new App_Student_SesisItem('','','','','');

        // Field#30 Unused – Leave Blank
        $import['030'] = new App_Student_SesisItem('','','','','');

        // Field#31 Unused – Leave Blank
        $import['031'] = new App_Student_SesisItem('','','','','');

        // Field#32 Placement Type (public vs Non-public)
        // This field wants to know if the student is a Public School Student or not.
        // This data will come from the EDIT STUDENT PAGE in the “Public Student?” section.  If YES then use code “0”(zero) if NO then use “1”.
        $import['032'] = new App_Student_SesisItem($this->build_pubSchoolStudent($arr['age'], $arr['pub_school_student']),'PUBLIC SCHOOL','Int','4','Required, 0, 1 or -1, 0 = No, 1 = Yes, -1 = No Value If age < 6, value must be -1, else value must be 0 or 1','','also returns -1 if student is under 6 or pub is blank');

        // Field#33 Entry Date
        // This date is the initial verification date from page 1 of the MDT.
        //
        // This is going to be a little more difficult since they are going to require this date for
        // ALL records but our forms do not require all records to have this data.
        // To get around this could we just have they system start with the most current MDT and work
        // its way backward in time until a date is found.  For example.
        // If I had 3 MDTs dated 2008, 2005 and 2002, the system would first look for the date in the 2008 MDT.
        // If the date wasn’t found, then it would look at the 2005 MDT.  If no date was found there,
        // I would look at the 2003 MDT.  Since you are only required to enter the Initial Verification date on the first MDT,
        // we will probably find most of these dates on the student’s first MDT form.
        //
        // The good thing about this is that it doesn’t matter how old the MDTs are, nor does it matter
        // if they have a Determination Notice or not.  We just need to find a date from one of the student’s MDTs
        //
        // This date should be displayed like this: YYYY-MM-DD
        $entryDate = $this->build_entryDate($arr['id_student']);
        if(empty($entryDate)) {
            $entryDate = null;
        }
        $import['033'] = new App_Student_SesisItem($entryDate,'ENTRY DATE','Datetime','10','','','');
        //echo (strlen($import['033']->data) == 10) . "<BR>";

        // Field#34 Exit Date
        //
        // This is the exit date from the EDIT STUDENT PAGE.
        if(is_null($arr['sesis_exit_date'])) {
            $exitDate = null;
        } else {
            $exitDate = date('Y-m-d', strtotime($arr['sesis_exit_date']));
        }
        $import['034'] = new App_Student_SesisItem($exitDate,'EXITDATE','Datetime','10','Empty or a valid date, see Exit table below, if present, exit reason must be present','','Formerly row 55');

        // Field#35 SNAPSHOT DATE
        // I have a feeling that they are going to eliminate this.  But in case they don’t.
        // This field will be just like Field #3.  Just fill each box with:  2008-06-30
        $import['035'] = new App_Student_SesisItem($this->nssrsSubmissionPeriod,'SNAPSHOT DATE','Varchar','10','','School Code');

        $import['036'] = new App_Student_SesisItem('','','','','');
        $import['037'] = new App_Student_SesisItem('','','','','');
        $import['038'] = new App_Student_SesisItem('','','','','');
        $import['039'] = new App_Student_SesisItem('','','','','');
        $import['040'] = new App_Student_SesisItem('','','','','');
        $import['041'] = new App_Student_SesisItem('','','','','');
        $import['042'] = new App_Student_SesisItem('','','','','');
        $import['043'] = new App_Student_SesisItem('','','','','');

        //Field#44 Primary Setting Code
        if(is_null($arr['dob'])) {
            $dobDate = null;
        } else {
            $dobDate = date('m/d/Y', strtotime($arr['dob']));
        }
        $import['044'] = new App_Student_SesisItem($this->primary_setting_code($dobDate, $this->build_spedSetting_pre2007(), $this->build_parentalPlacement2008($arr['parental_placement'])),'Primary Setting Code','','','','');

        //echo "44: " . $import['044'] . "<BR>";

        // Field#45 Unused – Leave Blank
        $import['045'] = new App_Student_SesisItem('','','','','');

        // Field#46 Unused – Leave Blank
        $import['046'] = new App_Student_SesisItem('','','','','');

        // Field#47 School Aged Indicator
        // This is where we can place the a code to say if the student is PART B or PART C (see above).
        // Code Description
        // 0 Not Applicable
        // 1 Parent Placement
        $import['047'] = new App_Student_SesisItem($this->settingCat,'School Aged Indicator','','','','');

        // Field#48 Surrogate Appointed Code
        // This data will come from the EDIT STUDENT PAGE under the section labeled “Has a surrogate parent been appointed?:
        // YES = 1
        // NO = 2
        $import['048'] = new App_Student_SesisItem($this->build_ward_surrogate($arr['ward_surrogate']),'Surrogate','Int','4','','','');

        // Field#49 Unused – Leave Blank
        $import['049'] = new App_Student_SesisItem('','','','','');


        // Field#50  Special Education Percentage
        // This data will come from page 6 of the most current IEP.  We need whatever number was entered into the “Not with regular peers” blank.
        //$import['050'] = new App_Student_SesisItem($this->mostRecentIEP['special_ed_non_peer_percent'],'Special Education Percentage','','','','');
        $import['050'] = new App_Student_SesisItem($this->buildSpecialEdPercentage(),'Special Education Percentage','','','','');

        // Field#51  Placement Reason
        // This data is pulled from the new “Parental Placement” section on the EDIT STUDENT page.
        // If they have selected “YES” in this section then we will use code “1”.  If NO then “0”(zero)
        //
        // Code Description
        // 0 Not Applicable
        // 1 Parent Placement
        $import['051'] = new App_Student_SesisItem($this->build_parentalPlacement2008($arr['parental_placement']),'Parental Placement','Int','4','','','NEW');

        // Field#52 Exit Reason
        // This data will come from the “Exit Reason” section of the IEP.
        // I don’t believe that the codes have changed much (except for the noted exceptions).
        // We will want to use the same type of calculations to figure out if the student should
        // receive a “Part C” list of exit reasons or a “Part B” list.  If the student is
        // considered “Transitional” then we will probably have to look at the student’s forms
        // and look to see if he has an IEP.  If he has a finalized IEP, then we will need to give a PART B list.  If he doesn’t then PART C.
        $import['052'] = new App_Student_SesisItem($this->build_exitCode($arr['sesis_exit_code']),'','','','','');

        #################################################################################################

        $retArr = array();
        foreach($import as $key => $sesisRowArr)
        {
            $retArr[$key] = $this->format_sesis_row($sesisRowArr);
        }

        $this->import = $import;
        return $retArr;
    }
    function buildSpecialEdPercentage() {
        global $sessIdUser;
        if('' != $this->mostRecentIEP['special_ed_non_peer_percent']) {
            return $this->spEdPercentage($this->mostRecentIEP['special_ed_non_peer_percent']);
        } else {
            $exitDate = $this->studentData['sesis_exit_date'];
            if(strtotime($exitDate) >= strtotime($this->getJuneCutoff())) {
                $iepArr = $this->formObj->getIEPs($this->studentID);
                foreach($iepArr as $row) {
                    if('' != $row['special_ed_non_peer_percent']) {
                        return $this->spEdPercentage($row['special_ed_non_peer_percent']);
                    }
                }
            }
        }
        if('' == $this->mostRecentIEP['special_ed_non_peer_percent'])
        {
            return 0;
        } else {
            return round($this->mostRecentIEP['special_ed_non_peer_percent'], 0);
        }
    }
    function spEdPercentage($special_ed_non_peer_percent)
    {
        if('' == $special_ed_non_peer_percent)
        {
            return 0;
        } else {
            return round($special_ed_non_peer_percent, 0);
        }
    }
    function build_sesis_data_transfer($arr)
    {
        //pre_print_r($arr);
        global $sessIdUser;
        //if(1000254 == $sessIdUser) pre_print_r($arr);

        $import = array(); # IMPORT RECORD LAYOUT

        //$this->build_disPrimeDisplay();

        $this->init_form();
        $this->build_most_recent_form_data($arr['dob']);

        $this->build_disability_arr();
        $this->build_form013_services();

        $ageYears = $this->build_age($arr['id_student']);
        $this->ageYears = $ageYears;

        //echo "MDT Date: {$this->mostRecentMDT_date}<BR>";
        #################################################################################################

        //  Field#1	County District Number
        //  This is essentially the district number that we user right now.
        //  -- It will look like this “00-0000”
        $import['001'] = new App_Student_SesisItem($arr['001'],'RESIDENT COUNTY DISTRICT','Varchar','7','','County District (00-0000)');

        // Field#2 School Number
        // This is the three digit school number.  They want this on a separate line.
        //     -- It will look like thie “001”
        $import['002'] = new App_Student_SesisItem($arr['002'],'RESIDENT SCHOOL','Varchar','3','','School Code');

        // Field#3 School Year Date
        // Every file should have the following date:  2008-06-30
        $import['003'] = new App_Student_SesisItem($arr['003'],'School Year Date','Varchar','10','','School Code');

        // Field#4 Unused – Leave Blank
        $import['004'] = new App_Student_SesisItem('','','','','');

        // Field#5 NDE Student ID
        // This is the 10 digit NSSRS ID# from the EDIT STUDENT PAGE
        $import['005'] = new App_Student_SesisItem($arr['005'],'NSSRSID','Int','10','','nssrs id');

        // Field#6 Unused – Leave Blank
        $import['006'] = new App_Student_SesisItem('','','','','');

        // Field#7 Unused – Leave Blank
        $import['007'] = new App_Student_SesisItem('','','','','');

        // Field#8 Unused – Leave Blank
        $import['008'] = new App_Student_SesisItem('','','','','');

        // Field#9 Unused – Leave Blank
        $import['009'] = new App_Student_SesisItem('','','','','');

        // Field#10 Unused – Leave Blank
        $import['010'] = new App_Student_SesisItem('','','','','');


        // Field#11 Primary Disability
        // This is the primary Disability from page 3 of the most recent MDT form.
        $import['011'] = new App_Student_SesisItem($arr['011'],'PRIMARY DISABILITY','Int','4','','Required, must be numeric, see Initial Disability Table below');//,''

        // Field#12 Unused – Leave Blank
        $import['012'] = new App_Student_SesisItem('','','','','');

        // Field#13 Unused – Leave Blank
        $import['013'] = new App_Student_SesisItem('','','','','');

        // Field#14 Unused – Leave Blank
        $import['014'] = new App_Student_SesisItem('','','','','');

        // Field#15 Unused – Leave Blank
        $import['015'] = new App_Student_SesisItem('','','','','');


        // Field#16 Related Services
        // This section is exactly the same as section 1-15 on the current SESIS report.
        // We will want to look at the most recent IEP (provided it is less than 1 year old).
        // This data is pulled from the primary and related services on page 6 of the IEP.
        // All this report is interested in is the following three services: Speech-Language Therapy - Occupational Therapy.
        // The codes below shall be used for reporting the various combinations of these three services.
        $import['016'] = new App_Student_SesisItem($arr['016'],'Related Services','Int','1','','','');

        // Field#17 Unused – Leave Blank
        $import['017'] = new App_Student_SesisItem('','','','','');

        // Field#18 Unused – Leave Blank
        $import['018'] = new App_Student_SesisItem('','','','','');

        // Field#19 Unused – Leave Blank
        $import['019'] = new App_Student_SesisItem('','','','','');

        // Field#20 Unused – Leave Blank
        $import['020'] = new App_Student_SesisItem('','','','','');

        // Field#21 Unused – Leave Blank
        $import['021'] = new App_Student_SesisItem('','','','','');

        // Field#22 Unused – Leave Blank
        $import['022'] = new App_Student_SesisItem('','','','','');

        // Field#23 Unused – Leave Blank
        $import['023'] = new App_Student_SesisItem($arr['023'],'Alternate Assessment','Int','1','','Alternate Assessment must be 1 or 2.');
//        $import['023'] = new App_Student_SesisItem('','','','','');

        // Field#24 Unused – Leave Blank
        $import['024'] = new App_Student_SesisItem('','','','','');

        // Field#25 Unused – Leave Blank
        $import['025'] = new App_Student_SesisItem('','','','','');

        // Field#26 Unused – Leave Blank
        $import['026'] = new App_Student_SesisItem('','','','','');

        // Field#27 Unused – Leave Blank
        $import['027'] = new App_Student_SesisItem('','','','','');

        // Field#28 Unused – Leave Blank
        $import['028'] = new App_Student_SesisItem('','','','','');

        // Field#29 Unused – Leave Blank
        $import['029'] = new App_Student_SesisItem('','','','','');

        // Field#30 Unused – Leave Blank
        $import['030'] = new App_Student_SesisItem('','','','','');

        // Field#31 Unused – Leave Blank
        $import['031'] = new App_Student_SesisItem('','','','','');

        // Field#32 Placement Type (public vs Non-public)
        // This field wants to know if the student is a Public School Student or not.
        // This data will come from the EDIT STUDENT PAGE in the “Public Student?” section.  If YES then use code “0”(zero) if NO then use “1”.
        $import['032'] = new App_Student_SesisItem($arr['032'],'PUBLIC SCHOOL','Int','4','Required, 0, 1 or -1, 0 = No, 1 = Yes, -1 = No Value If age < 6, value must be -1, else value must be 0 or 1','','also returns -1 if student is under 6 or pub is blank');

        // Field#33 Entry Date
        // This date is the initial verification date from page 1 of the MDT.
        //
        // This is going to be a little more difficult since they are going to require this date for
        // ALL records but our forms do not require all records to have this data.
        // To get around this could we just have they system start with the most current MDT and work
        // its way backward in time until a date is found.  For example.
        // If I had 3 MDTs dated 2008, 2005 and 2002, the system would first look for the date in the 2008 MDT.
        // If the date wasn’t found, then it would look at the 2005 MDT.  If no date was found there,
        // I would look at the 2003 MDT.  Since you are only required to enter the Initial Verification date on the first MDT,
        // we will probably find most of these dates on the student’s first MDT form.
        //
        // The good thing about this is that it doesn’t matter how old the MDTs are, nor does it matter
        // if they have a Determination Notice or not.  We just need to find a date from one of the student’s MDTs
        //
        // This date should be displayed like this: YYYY-MM-DD
        $import['033'] = new App_Student_SesisItem($arr['033'],'ENTRY DATE','Datetime','10','','','');
        //echo (strlen($import['033']->data) == 10) . "<BR>";

        // Field#34 Exit Date
        //
        // This is the exit date from the EDIT STUDENT PAGE.
        $import['034'] = new App_Student_SesisItem($arr['034'],'EXITDATE','Datetime','10','Empty or a valid date, see Exit table below, if present, exit reason must be present','','Formerly row 55');

        // Field#35 SNAPSHOT DATE
        // I have a feeling that they are going to eliminate this.  But in case they don’t.
        // This field will be just like Field #3.  Just fill each box with:  2008-06-30
        $import['035'] = new App_Student_SesisItem($arr['035'],'SNAPSHOT DATE','Varchar','10','','School Code');

        $import['036'] = new App_Student_SesisItem('','','','','');
        $import['037'] = new App_Student_SesisItem('','','','','');
        $import['038'] = new App_Student_SesisItem('','','','','');
        $import['039'] = new App_Student_SesisItem('','','','','');
        $import['040'] = new App_Student_SesisItem('','','','','');
        $import['041'] = new App_Student_SesisItem('','','','','');
        $import['042'] = new App_Student_SesisItem('','','','','');
        $import['043'] = new App_Student_SesisItem('','','','','');

        //Field#44 Primary Setting Code
        $import['044'] = new App_Student_SesisItem($arr['044'],'Primary Setting Code','','','','');

        //echo "44: " . $import['044'] . "<BR>";

        // Field#45 Unused – Leave Blank
        $import['045'] = new App_Student_SesisItem('','','','','');

        // Field#46 Unused – Leave Blank
        $import['046'] = new App_Student_SesisItem('','','','','');

        // Field#47 School Aged Indicator
        // This is where we can place the a code to say if the student is PART B or PART C (see above).
        // Code Description
        // 0 Not Applicable
        // 1 Parent Placement
        $import['047'] = new App_Student_SesisItem($arr['047'],'School Aged Indicator','','','','');

        // Field#48 Surrogate Appointed Code
        // This data will come from the EDIT STUDENT PAGE under the section labeled “Has a surrogate parent been appointed?:
        // YES = 1
        // NO = 2
        $import['048'] = new App_Student_SesisItem($arr['048'],'Surrogate','Int','4','','','');

        // Field#49 Unused – Leave Blank
        $import['049'] = new App_Student_SesisItem('','','','','');


        // Field#50  Special Education Percentage
        // This data will come from page 6 of the most current IEP.  We need whatever number was entered into the “Not with regular peers” blank.
        //$import['050'] = new App_Student_SesisItem($this->mostRecentIEP['special_ed_non_peer_percent'],'Special Education Percentage','','','','');
        $import['050'] = new App_Student_SesisItem($arr['050'],'Special Education Percentage','','','','');

        // Field#51  Placement Reason
        // This data is pulled from the new “Parental Placement” section on the EDIT STUDENT page.
        // If they have selected “YES” in this section then we will use code “1”.  If NO then “0”(zero)
        //
        // Code Description
        // 0 Not Applicable
        // 1 Parent Placement
        $import['051'] = new App_Student_SesisItem($arr['051'],'Parental Placement','Int','4','','','NEW');

        // Field#52 Exit Reason
        // This data will come from the “Exit Reason” section of the IEP.
        // I don’t believe that the codes have changed much (except for the noted exceptions).
        // We will want to use the same type of calculations to figure out if the student should
        // receive a “Part C” list of exit reasons or a “Part B” list.  If the student is
        // considered “Transitional” then we will probably have to look at the student’s forms
        // and look to see if he has an IEP.  If he has a finalized IEP, then we will need to give a PART B list.  If he doesn’t then PART C.
        $import['052'] = new App_Student_SesisItem($arr['052'],'','','','','');


        #################################################################################################

        $retArr = array();
        foreach($import as $key => $sesisRowArr)
        {
            $retArr[$key] = $this->format_sesis_row($sesisRowArr);
        }

        #pre_print_r($retArr);
        #pre_print_r($import);

        $this->import = $import;

        return $retArr;
    }
    function build_exitCode($code) // takes pre2007 code
    {
        //echo "build_exitCode: |$code|<BR>";
        return $code;
        switch($code)
        {
            case '4': return "5";
            case '8': return "4";
            case '9': return "7";
            case '10': return "8";
            case '11': return "4";
            case '12': return "6";
            case '13': return "6";
            case '15': return "4";
            case '19': return "9";
            case '22': return "7";
            case '23': return "5";
            default: return "unknown";
        }

    }


    function build_spedSetting_pre2007()
    {
        global $sessIdUser;
        $debug = false;
        if(1000254 == $sessIdUser) $debug = false;
        if($debug) echo "======== build_spedSetting_pre2007 ========<BR>";

        if('iep' == $this->iepUsed) {
            if($debug) echo "build_spedSetting_pre2007 primary_service_location: " .  $this->mostRecentIEP['primary_service_location'] . "<BR>";
            return (int) $this->mostRecentIEP['primary_service_location'];

        } elseif('iepcard' == $this->iepUsed) {
            return preg_replace("/^0/", "", $this->mostRecentIEPCard['service_where']);
        } elseif($this->mostRecent013 != -1) {
            if($debug) pre_print_r($this->service_whereArr);
            if(is_numeric($this->service_whereArr[0])) {
                if($debug) echo "build_spedSetting_pre2007 service_where 1: " .  ereg_replace("^0", "", $this->service_whereArr[0]) . "<BR>";
                return  preg_replace("^0", "", $this->service_whereArr[0]);
            } else {
                if($debug) echo "build_spedSetting_pre2007 service_where 2: " .  $this->service_whereArr[0] . "<BR>";
                return $this->get_setting_key($this->service_whereArr[0]);
            }
        } else {
            return "";
        }

    }

    function build8a($DOB, $NONpubSchool, $parentalPlacement)
    {

        global $sessIdUser;
        $debug = false;
        if(1000254 == $sessIdUser) $debug = false;


        $age = $this->build_ageAtCutoff($DOB);

        if($debug) echo "age: $age<BR>";
        if($debug) echo "NONpubSchool: $NONpubSchool<BR>";
        if($debug) echo "parentalPlacement: $parentalPlacement<BR>";
        if(6 > $age)
        {
            return "Under 6, not required";
        } else {
            if(1 == $NONpubSchool)
            {
                return $parentalPlacement;
            } else {
                return -1;
            }
        }
    }
    function build_ageAtCutoff($DOB) // takes pre2007 code
    {

        $DOB = self::date_massage($DOB);
        $today = date("m/d/Y", strtotime("today"));
        $decCutoff = date("m/d/Y", strtotime("12/1/" . date("Y", strtotime("today"))));
        if(date("m", strtotime("today")) >= 7)
        {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today"))));
        } else {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today")) ."-1 year"));
        }

        if($today <= $juneCutoff || $today > $decCutoff)
        {
            $cutoff = $juneCutoff;
            $ageArr = Model_Table_StudentTable::age_calculate(getdate(strtotime($DOB)), getdate(strtotime($juneCutoff)));
            $age = $ageArr['years'];
        } elseif($today > $juneCutoff && $today <= $decCutoff)
        {
            $cutoff = $decCutoff;
            $ageArr = Model_Table_StudentTable::age_calculate(getdate(strtotime($DOB)), getdate(strtotime($decCutoff)));
            if($debug) pre_print_r($ageArr);
            $age = $ageArr['years'];

        }

        return $age;
    }

    function build_spedSetting_2007($DOB, $code, $parentalPlacement) // takes pre2007 code
    {
        global $sessIdUser;
        $debug = false;
        if(1000254 == $sessIdUser) $debug = false;

        $DOB = self::date_massage($DOB);
        $today = date("m/d/Y", strtotime("today"));
        $decCutoff = date("m/d/Y", strtotime("12/1/" . date("Y", strtotime("today"))));
        if(date("m", strtotime("today")) >= 7)
        {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today"))));
        } else {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today")) ."-1 year"));
        }

        if($debug) echo "parentalPlacement: $parentalPlacement<BR>";
        if($debug) echo "DOB: $DOB<BR>";
        if($debug) echo "today: $today<BR>";
        if($debug) echo "decCutoff: $decCutoff<BR>";
        if($debug) echo "juneCutoff: $juneCutoff<BR>";
        if($today <= $juneCutoff || $today > $decCutoff)
        {
            if($debug) echo "June cutoff<BR>";
            $cutoff = $juneCutoff;
            $ageArr = Model_Table_StudentTable::age_calculate(getdate(strtotime($DOB)), getdate(strtotime($juneCutoff)));
            $age = $ageArr['years'];
            if($debug) echo "age: $age<BR>";
        } elseif($today > $juneCutoff && $today <= $decCutoff)
        {
            if($debug) echo "Dec cutoff<BR>";
            $cutoff = $decCutoff;
            $ageArr = Model_Table_StudentTable::age_calculate(getdate(strtotime($DOB)), getdate(strtotime($decCutoff)));
            if($debug) pre_print_r($ageArr);
            $age = $ageArr['years'];
            if($debug) echo "age: $age<BR>";

        }

        if($debug) echo "build_spedSetting_2007 - age on $cutoff: $age<BR>";
        if($debug) echo "build_spedSetting_2007 - code: $code<BR>";

        if($age < 3)
        {
            return $this->build_spedSetting_2007_birth_to_two($code);
        } elseif($age < 6)
        {
            return $this->build_spedSetting_2007_threeToFive($code);
        } else
        {
            if(1 == $parentalPlacement)
            {
                return 14;
            } else {
                return $this->build_spedSetting_2007_sixToTwentyOne($code);
            }
        }


    }

    function build_spedSetting_2007_birth_to_two($code) // takes pre2007 code
    {
        switch($code)
        {
            case '1': return "10";
            case '2': return "11";
            case '3': return "12";
            case '4': return "2";
            case '6': return "11";
            case '7': return "12";
            case '8': return "2";
            case '9': return "1";
            case '9': return "7";
            case '9': return "9";
            case '10': return "1";
            case '10': return "8";
            case '10': return "13";
            case '11': return "4";
            case '12': return "6";
            case '13': return "6";
            case '14': return "15";
            case '15': return "4";
            case '19': return "3";
            case '21': return "3";
            case '22': return "2";
            case '22': return "7";
            case '23': return "5";
            default: return "unknown";
        }

    }

    function build_spedSetting_2007_threeToFive($code) // takes pre2007 code
    {
        //echo "build_spedSetting_2007_threeToFive: |$code|<BR>";
        switch($code)
        {
            case '4': return "5";
            case '8': return "4";
            case '9': return "7";
            case '10': return "8";
            case '11': return "4";
            case '12': return "6";
            case '13': return "6";
            case '15': return "4";
            case '19': return "9";
            case '22': return "7";
            case '23': return "5";
            default: return "unknown";
        }

    }

    function build_spedSetting_2007_sixToTwentyOne($code) // takes pre2007 code
    {
        //echo "over 6 code: $code<BR>";
        switch($code)
        {
            case "1":	return "10";
            case "2":	return "11";
            case "3":	return "12";
            case "6":	return "11";
            case "7":	return "12";
            case "9":	return "9";
            case "10":	return "13";
            case "11":	return "10";
            case "12":	return "10";
            case "13":	return "10";
            case "14":	return "15";
            case "15":	return "10";
            case "22":	return "12";
            case "23":	return "11";
            default: return "unknown";
        }

    }

    function get_setting_key($settingName) { // 1-11
        //echo "get_setting_key settingName: $settingName<BR>";

        if($settingName == '') return 'No Finalized IFSP/IEP on FILE';


        switch($settingName) {
            case 'Child Care Center':
                return '4';
                break;
            case 'Family Child Care Home':
                return '10';
                break;
            case 'Head Start':
                return '8';
                break;
            case 'Home':
                return '10';
                break;
            case 'Hospital':
                return '9';
                break;
            case 'Other':
                return '21';
                break;
            case 'Part-Time Early Childhood':
                return '4';
                break;
            case 'Part-Time Early Childhood Special Education Setting':
                return '4';
                break;
            case 'Residential Facility':
                return '22';
                break;
            case 'Separate classroom for children with disabilities':
                return '4';
                break;
            case 'Service Provider Location':
                return '19';
                break;

        }

        $locArr1 = App_ValueLists::getLabelValues('serviceLocationBirthToTwo');
        $locArr2 = App_ValueLists::getLabelValues('serviceLocationThreeToFive');
        $locArr3 = App_ValueLists::getLabelValues('serviceLocationSixTo21');

        $locArr = $locArr1 + $locArr2 + $locArr3;
        $settingKey = array_search($settingName, $locArr);
        return $settingKey;
    }

    function init_form()
    {
        #
        # CREATE FUNCTION LEVEL ONLY FORM OBJ
        #
        $formObj = new Zend_Form();
        $this->formObj = $formObj;

    }

    function check_MDT_validity($mostRecentMDT_date)
    {
        global $sessIdUser;
        $debug = false;
        if(1000254 == $sessIdUser) $debug = false;

        if($debug) echo "Most Recent MDT: $mostRecentMDT_date<BR>";




        if( strtotime($mostRecentMDT_date) >= strtotime('today -3 years'))
        {
            if($debug)
            {
                echo "mdt is within the time frame<BR>\n";
                echo "true = ".strtotime($mostRecentMDT_date).">".strtotime('today -3 years')."<BR>\n";
                echo "true = ".self::date_massage($mostRecentMDT_date).">".date('m/d/Y', strtotime('today -3 years'))."<BR>\n";

                if(1083387600 >= 1118898000) echo "yes<BR>\n";
                if(1083387600 < 1118898000) echo "no<BR>\n";
            }
            return true;
        } else {
            //echo "<B>No MDT Found in last three years.</B> = ".strtotime($mostRecentMDT_date).">".strtotime('today -3 years')."<BR>\n";
            //echo "<B>No MDT Found in last three years.</B> = ".self::date_massage($mostRecentMDT_date).">".date('m/d/Y', strtotime('today -3 years'))."<BR>\n";
            // mdt is NOT within the time frame
            if($debug) echo "check if there is a determination notice<BR>";

            $form012Obj = new Model_Table_Form012();
            $this->mostRecent012 = $form012Obj->mostRecentFinalForm($this->studentID);
            $this->mostRecent012 = is_null($this->mostRecent012) ? -1 : $this->mostRecent012;
            if($debug) echo "Most Recent Determination Notice: " . $this->mostRecent012['date_notice'] . "<BR>";

            if( -1 != $this->mostRecent012 && strtotime($this->mostRecent012['date_notice']) >= strtotime('today -3 years'))
            {
                if($debug) echo "Determination Notice Found within last three years ({$this->mostRecent012['date_notice']}).<BR>\n";
                // allow MDTs to be used that are as much as three years older than the Notice of Determination
                //echo "3 before det: " . date('m/d/Y', strtotime($this->mostRecent012['date_notice'] . ' -3 years')) . "<BR>\n";
                //if(strtotime($mostRecentMDT_date) >= strtotime($this->mostRecent012['date_notice'] . ' -3 years') )
                if(1)
                {
                    if($debug) echo "This MDT is within three years of Notice of Determination.<BR>\n";
                    return true;
                } else {
                    if($debug) echo "<B>This MDT is NOT within three years of Notice of Determination.</B><BR>\n";
                    return false;
                }
            } else {
                if($debug) echo "<B>Determination Notice NOT Found within last three years.</B><BR>\n";
                return false;
            }
        }
    }
    function build_disability_arr()
    {
        #
        # BUILD MERGED DISABILITIES TEXT
        #
        // 20120127 jlavere - add && 't'!=$this->mostRecentIEP['override_related']
        // to make sure hidded related services are not used
        if($this->mostRecentIEP != -1 && isset($this->mostRecentIEP['id_form_004']) && 't'!=$this->mostRecentIEP['override_related']) {
            if($this->mostRecentIEP['version_number'] >=9) {
                $disabilitiesArr = array();
                $relatedServicesObj = new Model_Table_Form004RelatedService();
                $select = $relatedServicesObj->select()
                    ->where("id_form_004 = ?", $this->mostRecentIEP['id_form_004']);
                $relatedServices = $relatedServicesObj->fetchAll($select);
                if ( count($relatedServices)) {
                    foreach($relatedServices as $service) {
                        $disabilitiesArr[] = $service->related_service_drop;
                    }
                }

            } else {
                $disabilities = $this->mostRecentIEP['related_service_drop'];
                $disabilitiesArr = explode("|", $disabilities);

                $disOther = $this->mostRecentIEP['related_service'];
                $disOtherArr = explode("|", $disOther);
                #
                # MERGE THE ARRAYS FOR USAGE LATER
                #
                $disMergeArr = array();
                for($i=0, $j=count($disabilitiesArr); $i<$j;$i++) {
                    if($disOtherArr[$i] != '') {
                        $disMergeArr[$i] = $disabilitiesArr[$i] . " : " . $disOtherArr[$i];
                    } else {
                        if($disabilitiesArr[$i] !== '') $disMergeArr[$i] = $disabilitiesArr[$i];
                    }
                }

            }

        } else {
            $disabilitiesArr = array();
        }
        #
        # 040930 JL - ADD PRI DIS TO THE DIS ARR
        if(isset($this->mostRecentIEP['primary_disability_drop'])) {
            $priDis = $this->mostRecentIEP['primary_disability_drop'];
            $disabilitiesArr[] = $priDis;
        }
        $this->disabilitiesArr = $disabilitiesArr;

    }
    function build_disability_arr_from_array($iep)
    {
        #
        # BUILD MERGED DISABILITIES TEXT
        #
        if($iep != -1) {

            $priDis = $iep['primary_disability_drop'];

            $disabilities = $iep['related_service_drop'];
            $disabilitiesArr = explode("|", $disabilities);

            $disOther = $iep['related_service'];
            $disOtherArr = explode("|", $disOther);
            #
            # MERGE THE ARRAYS FOR USAGE LATER
            #
            $disMergeArr = array();
            for($i=0, $j=count($disabilitiesArr); $i<$j;$i++) {
                if($disOtherArr[$i] != '') {
                    $disMergeArr[$i] = $disabilitiesArr[$i] . " : " . $disOtherArr[$i];
                } else {
                    if($disabilitiesArr[$i] !== '') $disMergeArr[$i] = $disabilitiesArr[$i];
                }
            }
            #
            # 040930 JL - ADD PRI DIS TO THE DIS ARR
            #
            $disabilitiesArr[] = $priDis;
        }

        return $disabilitiesArr;
    }

    function build_form013_services()
    {
        # GET SERVICES FROM IFSP
        if($this->mostRecent013 != -1) {
            $form013Obj = new Model_Table_Form013();
            $serviceArr = $form013Obj->getServices($this->mostRecent013['id_form_013']);
            $this->service_serviceArr = array();
            $this->service_whereArr = array();
            $otherText = "";
            $whereText = "";
            $this->otherHasBeenSelected = false;
            $this->otherWhereHasBeenSelected = false;
            if(count($serviceArr) > 0)
            {
                foreach($serviceArr as $serviceRow) {
                    if(!empty($serviceRow['service_service'])) {
                        $serviceRow['service_service'] = strtolower($serviceRow['service_service']); //040929 JL lowered because we have different versions of data
                        array_push($this->service_serviceArr, $serviceRow['service_service']);
                        if(strtolower($serviceRow['service_service']) == 'other') {
                            if(strlen($otherText) > 0 && substr($otherText, -1) != ':') $otherText .= ":";
                            $otherText .= $serviceRow['service_other'];
                            $this->otherHasBeenSelected = true;
                        }
                    }
                    if(!empty($serviceRow['service_where'])) {
                        $serviceRow['service_where'] = strtolower($serviceRow['service_where']); //040929 JL lowered because we have different versions of data
                        array_push($this->service_whereArr, $serviceRow['service_where']);
                        if(strtolower($serviceRow['service_where']) == 'other') {
                            if(strlen($whereText) > 0 && substr($whereText, -1) != ':') $whereText .= ":";
                            $whereText .= $serviceRow['service_where_other'];
                            $this->otherWhereHasBeenSelected = true;
                        }
                    }

                }
            }
        }

    }
    function build_ethnicity($eth)
    {
        $ethGrp = "";
        $arrLabel = array("White, Not Hispanic", "Black, Not Hispanic", "Hispanic", "American Indian / Alaska Native", "Asian / Pacific Islander");
        $arrValue = array("A", "B", "C", "D", "E");
        if(!empty($eth)) {
            switch($eth) {
                case "A":
                    $ethGrp = 3;
                    break;
                case "B":
                    $ethGrp = 4;
                    break;
                case "C":
                    $ethGrp = 5;
                    break;
                case "D":
                    $ethGrp = 1;
                    break;
                case "E":
                    $ethGrp = 2;
                    break;
            }
            return $ethGrp;
        }

    }
    function build_entryDate($id_student) {

        $form002Obj = new Model_Table_Form002();
        $mdtRows = $form002Obj->getAllFinalForms($id_student, 'timestamp_created');
        foreach ($mdtRows as $mdtCard) {
                if( '' != $mdtCard['initial_verification_date']) return date('Y-m-d', strtotime($mdtCard['initial_verification_date']));
                if( '' != $mdtCard['initial_verification_date_sesis']) return date('Y-m-d', strtotime($mdtCard['initial_verification_date_sesis']));
        }

        $form022Obj = new Model_Table_Form022();
        $mdtCardRows = $form022Obj->getAllFinalForms($id_student, 'timestamp_created');
        foreach ($mdtCardRows as $mdtCard) {
                if( '' != $mdtCard['initial_verification_date']) return date('Y-m-d', strtotime($mdtCard['initial_verification_date']));
                if( '' != $mdtCard['initial_verification_date_sesis']) return date('Y-m-d', strtotime($mdtCard['initial_verification_date_sesis']));
        }

        return '';
    }

    function build_initialVersion()
    {
        global $sessIdUser;
        #if(1000254 == $sessIdUser) pre_print_r($this->mostRecentMDT);
        #if(1000254 == $sessIdUser) echo "initial_verification_date: " . self::date_massage($this->mostRecentMDT['initial_verification_date'], 'm/d/Y') . "<BR>";

        if('' == $this->mostRecentMDT['initial_verification_date'] && '' != $this->mostRecentMDT['initial_verification_date_sesis'])
        {
            return date('m/d/Y', strtotime($this->mostRecentMDT['initial_verification_date_sesis']));
        } elseif('' == $this->mostRecentMDT['initial_verification_date'] && '' != $this->mostRecentMDT['never_verified']) {
            return "Did not Qualify";
        } elseif('' == $this->mostRecentMDT['initial_verification_date']) {
            return -1;
        } else {
            return date('m/d/Y', strtotime($this->mostRecentMDT['initial_verification_date']));
        }

        $initVer = '';
        switch($this->mostRecentMDT['initial_verification']) {
            case "t":
                $initVer = 1;
                break;
            case "f":
                $initVer = 0;
                break;
            default:
                $returnArr = array();
                $returnCount = '';
                $this->formObj->customSelect($returnArr, $returnCount, "select 1 from iep_form_002 where id_student = '".$this->studentID."' and status = 'Final';");
                if($returnCount > 1) {
                    $initVer = 0;
                } else {
                    $initVer = "";
                }
                break;
        }
        return $initVer;
    }

    function build_uniqueID($stateID)
    {
        if($stateID != '') {
            return $stateID;
        } else {
            return "";
        }
    }

    function build_gender($gender)
    {
        if(in_array($gender, array('f', 'Female'))) {
            return 1;
        } elseif(in_array($gender, array('M', 'Male'))) {
            return 0;
        } else {
            return "";
        }
    }
    function format_sesis_row($sesisRowArr)
    {
        #list($value, $name, $type, $length, $validation, $note) = $sesisRowArr;
        #return $value;
        if(is_object($sesisRowArr))
        {
            return $sesisRowArr->data;
        } else {
            return $sesisRowArr['data'];
        }
    }

    function build_sesis_status()
    {
        return 0;
    }
    function build_CDS($c, $d, $s)
    {
        return "$c-$d-$s";
    }
    function build_CD($c, $d, $s)
    {
        return "$c-$d";
    }

    function build_pubSchoolStudent($age, $pub)
    {
        //if(6 > $age) return -1;
        if($pub=="t") {
            return 0;
        } elseif($pub=="f") {
            return 1;
        } else {
            return -1;
        }
    }

    function build_parentalPlacement2008($parentalPlacement) {
        //echo "parentalPlacement: $parentalPlacement:";
        if($parentalPlacement=="t") {
            return 1;
        } elseif($parentalPlacement=="f") {
            return 0;
        } else {
            return 0;
        }

    }
    function build_parentalPlacement($DOB, $parPlace, $row013)
    {
        // 20071025 jlavere - If we are putting a 0(zero) in row 013 then we must also put a 0(zero) in for 014.
        if($row013 == 0) return 0;


        $DOB = self::date_massage($DOB);
        $today = date("m/d/Y", strtotime("today"));
        $decCutoff = date("m/d/Y", strtotime("12/1/" . date("Y", strtotime("today"))));
        if(date("m", strtotime("today")) >= 7)
        {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today"))));
        } else {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today")) ."-1 year"));
        }

        if($today <= $juneCutoff || $today > $decCutoff)
        {
            //echo "June cutoff<BR>";
            $cutoff = $juneCutoff;
            $ageArr = Model_Table_StudentTable::age_calculate(getdate(strtotime($DOB)), getdate(strtotime($juneCutoff)));
            $age = $ageArr['years'];
            //echo "age: $age<BR>";
        } elseif($today > $juneCutoff && $today <= $decCutoff)
        {
            //echo "Dec cutoff<BR>";
            $cutoff = $decCutoff;
            $ageArr = Model_Table_StudentTable::age_calculate(getdate(strtotime($DOB)), getdate(strtotime($decCutoff)));
            //pre_print_r($ageArr);
            $age = $ageArr['years'];
            //echo "age: $age<BR>";

        }

        //echo "age: $age<BR>";

        if(6 > $age) return -1;
        if($parPlace=="t") {
            return 1;
        } elseif($parPlace=="f") {
            return 0;
        } else {
            return 'unknown';
        }
    }

    function build_limited_english_proficient($id_student, $ell_student)
    {
        $ageArr = student_age($id_student);
        //pre_print_r($ageArr);

        if($ageArr['years'] < 6)
        {
            //return -1;
        }
        if($ell_student=="t") {
            return 1;
        } elseif($ell_student=="f") {
            return 0;
        } else {
            return -1;
        }
    }
    function build_grade($grade)
    {
        // echo "grade: $grade<BR>";
        switch($grade) {
            case "ECSE":
            case "EI 0-2":
                return 2;
            case "K":
                return 3;
            case "01":
            case "1":
                return 4;
            case "02":
            case "2":
                return 5;
            case "03":
            case "3":
                return 6;
            case "04":
            case "4":
                return 7;
            case "05":
            case "5":
                return 8;
            case "06":
            case "6":
                return 9;
            case "07":
            case "7":
                return 10;
            case "08":
            case "8":
                return 11;
            case "09":
            case "9":
                return 12;
            case "10":
                return 13;
            case "11":
                return 14;
            case "12":
                return 15;
            case "12+":
                return 16;
            default:
                return 1;
        }
    }
    function build_age($id_student)
    {
        $ageArr = student_age($id_student);
        return $ageArr['years'];
    }
    function build_disPrime()
    {
        global $sessIdUser;

        $disPrime = "";
        if($this->mostRecentMDT != -1) {
            if(!empty($this->mostRecentMDT['disability_primary'])) {
                $this->disPrime = $this->massage_disPrime($this->mostRecentMDT['disability_primary']);
                return $this->disPrime;
            }
        }

        // if an exit date exits, check previous MDTS for primary disability
        $exitDate = $this->studentData['sesis_exit_date'];
        if(strtotime($exitDate) >= strtotime($this->getJuneCutoff())) {
//            $mdtArr = $this->formObj->getMDTs($this->studentID);
            $mdtArr = $this->getMdts($this->studentID);

            foreach($mdtArr as $row) {
                if('' != $row['disability_primary']) {
                    $this->disPrime = $this->massage_disPrime($row['disability_primary']);
                    return $this->disPrime;
                }
            }
        }

    }
    function getMdts($id_student)
    {
        $mdtTable = new Model_Table_Form002();
        $select = $mdtTable->select()->where("id_student = ?", $id_student)
            ->where("status = 'Final'")
            ->order(array('date_mdt desc', 'timestamp_created desc'));
        $mdts = $mdtTable->fetchAll($select);
        if(count($mdts)) {
            return $mdts->toArray();
        }
        return false;
    }
    function build_disPrimeDisplay()
    {
        global $sessIdUser;
        $disPrime = "";
        if($this->mostRecentMDT != -1) {
            if(!empty($this->mostRecentMDT['disability_primary'])) {
                $positionKey = array_search($this->mostRecentMDT['disability_primary'], $this->disPrimeArrValue);
                #if(1000254==$sessIdUser) pre_print_r($this->mostRecentMDT['disability_primary']);
                #if(1000254==$sessIdUser) pre_print_r($this->disPrimeArrValue);
                #if(1000254==$sessIdUser) echo "positionKey: $positionKey<BR>";
                if(false === $positionKey) {
                    $this->disPrimeDisplay = '<B>There is an error in the NSSRS collection. Please contact an administrator.</B>';
                } else {
                    $this->disPrimeDisplay = $this->disPrimeArrLabel[$positionKey];
                }
                return $this->disPrimeDisplay;
            }
        }
    }


    function massage_disPrime($dis) {
        // With one exception (Men// tal Handicap) the codes are exactly the same as in the SESIS report.
        // The only difference is that Mental Handicap is now 16 (it used to be 04).
        //
        // 01 Behavioral Disorder
        // 02 Deaf-Blindness
        // 03 Hearing Impaired
        // 07 Multiple Impairment
        // 08 Orthopedic Impairment
        // 09 Other Health Impairment
        // 10 Specific Learning Disability
        // 11 Speech Language Impairment
        // 12 Visual Impairment
        // 13 Autism
        // 14 Traumatic Brain Injury
        // 15 Developmental Delay
        // 16 Mental Handicap
        //echo "dis: $dis<BR>";

        switch($dis) {
            case "AU":
                return "13";
                break;
            case "BD":
                return "01";
                break;
            case "DB":
                return "02";
                break;
            case "DD":
                return "15";
                break;
            case "HI":
                return "03";
                break;

            case "MH:MI":
            case "MHMI":
                return "16";
                break;
            case "MH:MO":
            case "MHMO":
                return "16";
                break;
            case "MH:S/P":
            case "MHSP":
                return "16";
                break;

            // 20090304 jlavere - add MH code
            case "MH":
                return "16";
                break;

            case "MULTI":
                return "07";
                break;
            case "OI":
                return "08";
                break;
            case "OHI":
                return "09";
                break;
            case "SLD":
                return "10";
                break;
            case "TBI":
                return "14";
                break;
            case "VI":
                return "12";
                break;
            case "SLI":
                return "11";
                break;
        }
    }

    function build_hearing_impair_status($hearingImparement, $detail, $disPrime )
    {
        switch ( $disPrime )
        {
            case 2:
            case 3:
                //case 7:
            case 12:
                if($detail=="Deaf (Severe Profound)") {
                    return 1;
                } elseif($detail=="Hard of Hearing (Mild/Moderate)") {
                    return 2;
                } else {
                    return -1;
                }
                break;

            default:
                return -1;
                break;
        }

    }
    function build_visual_impair_status($visualImparement, $detail, $disPrime)
    {
        switch ( $disPrime )
        {
            case 2:
            case 3:
                //case 7:
            case 12:
                if($detail=="Blind") {
                    return 1;
                } elseif($detail=="Legally Blind") {
                    return 2;
                } elseif($detail=="Partially Sighted") {
                    return 3;
                } else {
                    return -1;
                }
                break;

            default:
                return -1;
                break;
        }
    }
    function build_program_provider($prov)
    {
        $progProv = NULL;
        if(!empty($prov)) {
            switch($prov) {
                case "Resident school district":
                    $progProv = 1;
                    break;
                case "Another school district":
                    $progProv = 2;
                    break;
                case "Agency/educational service unit":
                    $progProv = 3;
                    break;
            }
            return $progProv;
        }
    }
    function build_majorprovidernumber($providerType, $program_provider_name, $program_provider_code, $program_provider_id_school)
    {
        if($providerType == 1) { // Resident school district
            return -1;
        } elseif($providerType == 2) { // Another school district
            // program_provider_name will contain id_county
            // program_provider_code will contain id_district
            // program_provider_id_school will contain id_school
            return $program_provider_name . "-" . $program_provider_code . "-" . $program_provider_id_school;

        } elseif($providerType == 3) { // Agency/educational service unit
            return $program_provider_code;
        }
    }

    function build_speechLngThrpy()
    {
        global $sessIdUser;

        // Code Description
        // 1 Occupational Therapy
        // 2 Physical Therapy
        // 3 Speech-Language Therapy
        // 4 Occupational Therapy - Physical Therapy
        // 5 Physical Therapy - Speech-Language Therapy
        // 6 Speech-Language Therapy - Occupational Therapy
        // 7 All
        // 8 None
        //
        // speech lang
        if(-1 != $this->mostRecent013) {
            #if(1000254 == $sessIdUser) pre_print_r($this->service_serviceArr);
            if( in_array(("Speech-language therapy"), $this->service_serviceArr) ||
                in_array(("Speech-Language therapy"), $this->service_serviceArr) ||
                in_array(("Speech-language Therapy"), $this->service_serviceArr) ||
                in_array(("Speech-Language Therapy"), $this->service_serviceArr) ||
                in_array(("Speech/language therapy"), $this->service_serviceArr) ||
                in_array(("Speech/Language therapy"), $this->service_serviceArr) ||
                in_array(("Speech/language Therapy"), $this->service_serviceArr) ||
                in_array(("Speech/Language Therapy"), $this->service_serviceArr) ||
                in_array(strtolower("Speech-language Therapy"), $this->service_serviceArr) ||
                in_array(strtolower("Speech/language Therapy"), $this->service_serviceArr)
            ) {
                $spLang =  1;
            } else {
                $spLang =  0;
            }

            if( in_array(("Occupational Therapy Services"), $this->service_serviceArr) ||
                in_array(strtolower("Occupational Therapy Services"), $this->service_serviceArr)
            ) {
                $occTherSer =  1;
            } else {
                $occTherSer =  0;
            }

            if( in_array(("Physical Therapy"), $this->service_serviceArr) ||
                in_array(("physical therapy"), $this->service_serviceArr)
            ) {
                $phyTherSer =  1;
            } else {
                $phyTherSer =  0;
            }

            if($spLang == 1 && $occTherSer == 1 && $phyTherSer == 1)
            {
                return 7;
            } elseif($spLang == 1 && $occTherSer == 1 && $phyTherSer == 0) {
                return 6;
            } elseif($spLang == 1 && $occTherSer == 0 && $phyTherSer == 1) {
                return 5;
            } elseif($spLang == 0 && $occTherSer == 1 && $phyTherSer == 1) {
                return 4;
            } elseif($spLang == 1 && $occTherSer == 0 && $phyTherSer == 0) {
                return 3;
            } elseif($spLang == 0 && $occTherSer == 0 && $phyTherSer == 1) {
                return 2;
            } elseif($spLang == 0 && $occTherSer == 1 && $phyTherSer == 0) {
                return 1;
            }
            return 8;

        } elseif($this->mostRecentIEPCardUsed) {
            if($this->mostRecentIEP['service_ot']) {
                $occTherSer =  1;
            } else {
                $occTherSer =  0;
            }
            if($this->mostRecentIEP['service_pt']) {
                $phyTherSer =  1;
            } else {
                $phyTherSer =  0;
            }
            if($this->mostRecentIEP['service_slt']) {
                $spLang =  1;
            } else {
                $spLang =  0;
            }


            if($spLang == 1 && $occTherSer == 1 && $phyTherSer == 1)
            {
                return 7;
            } elseif($spLang == 1 && $occTherSer == 1 && $phyTherSer == 0) {
                return 6;
            } elseif($spLang == 1 && $occTherSer == 0 && $phyTherSer == 1) {
                return 5;
            } elseif($spLang == 0 && $occTherSer == 1 && $phyTherSer == 1) {
                return 4;
            } elseif($spLang == 1 && $occTherSer == 0 && $phyTherSer == 0) {
                return 3;
            } elseif($spLang == 0 && $occTherSer == 0 && $phyTherSer == 1) {
                return 2;
            } elseif($spLang == 0 && $occTherSer == 1 && $phyTherSer == 0) {
                return 1;
            }
            return 8;

        } elseif($this->mostRecentIEP != -1) {


            if( in_array(("Speech-language therapy"), $this->disabilitiesArr) ||
                in_array(("Speech-Language therapy"), $this->disabilitiesArr) ||
                in_array(("Speech-language Therapy"), $this->disabilitiesArr) ||
                in_array(("Speech-Language Therapy"), $this->disabilitiesArr) ||
                in_array(("Speech/language therapy"), $this->disabilitiesArr) ||
                in_array(("Speech/Language therapy"), $this->disabilitiesArr) ||
                in_array(("Speech/language Therapy"), $this->disabilitiesArr) ||
                in_array(("Speech/Language Therapy"), $this->disabilitiesArr)
            ) {
                $spLang =  1;
            } else {
                $spLang =  0;
            }

            if( in_array(("Occupational Therapy Services"), $this->disabilitiesArr)
            ) {
                $occTherSer =  1;
            } else {
                $occTherSer =  0;
            }

            if( in_array(("Physical Therapy"), $this->disabilitiesArr)
            ) {
                $phyTherSer =  1;
            } else {
                $phyTherSer =  0;
            }

            if($spLang == 1 && $occTherSer == 1 && $phyTherSer == 1)
            {
                return 7;
            } elseif($spLang == 1 && $occTherSer == 1 && $phyTherSer == 0) {
                return 6;
            } elseif($spLang == 1 && $occTherSer == 0 && $phyTherSer == 1) {
                return 5;
            } elseif($spLang == 0 && $occTherSer == 1 && $phyTherSer == 1) {
                return 4;
            } elseif($spLang == 1 && $occTherSer == 0 && $phyTherSer == 0) {
                return 3;
            } elseif($spLang == 0 && $occTherSer == 0 && $phyTherSer == 1) {
                return 2;
            } elseif($spLang == 0 && $occTherSer == 1 && $phyTherSer == 0) {
                return 1;
            }
            return 8;
        }

        // if null setting code, check exit date
        $exitDate = $this->studentData['sesis_exit_date'];

        // if exit date valid, release iep date restrictions
        if(strtotime($exitDate) >= strtotime($this->getJuneCutoff())) {
            $iepArr = $this->formObj->getIEPs($this->studentID);
            foreach($iepArr as $row) {
                $disArr = $this->build_disability_arr_from_array($row);
                if( in_array(("Speech-language therapy"), $disArr) ||
                    in_array(("Speech-Language therapy"), $disArr) ||
                    in_array(("Speech-language Therapy"), $disArr) ||
                    in_array(("Speech-Language Therapy"), $disArr) ||
                    in_array(("Speech/language therapy"), $disArr) ||
                    in_array(("Speech/Language therapy"), $disArr) ||
                    in_array(("Speech/language Therapy"), $disArr) ||
                    in_array(("Speech/Language Therapy"), $disArr)
                ) {
                    $spLang =  1;
                } else {
                    $spLang =  0;
                }

                if( in_array(("Occupational Therapy Services"), $disArr)
                ) {
                    $occTherSer =  1;
                } else {
                    $occTherSer =  0;
                }

                if( in_array(("Physical Therapy"), $disArr)
                ) {
                    $phyTherSer =  1;
                } else {
                    $phyTherSer =  0;
                }

                if($spLang == 1 && $occTherSer == 1 && $phyTherSer == 1)
                {
                    return 7;
                } elseif($spLang == 1 && $occTherSer == 1 && $phyTherSer == 0) {
                    return 6;
                } elseif($spLang == 1 && $occTherSer == 0 && $phyTherSer == 1) {
                    return 5;
                } elseif($spLang == 0 && $occTherSer == 1 && $phyTherSer == 1) {
                    return 4;
                } elseif($spLang == 1 && $occTherSer == 0 && $phyTherSer == 0) {
                    return 3;
                } elseif($spLang == 0 && $occTherSer == 0 && $phyTherSer == 1) {
                    return 2;
                } elseif($spLang == 0 && $occTherSer == 1 && $phyTherSer == 0) {
                    return 1;
                }
            }
            return 8;
        }

    }

    function build_ward_surrogate($wardsurrogate )
    {
        if($wardsurrogate=="t") {
            return 1;
        } elseif($wardsurrogate=="f") {
            return 2;
        } else {
            return 2;
        }
    }
    function build_ward_surrogate_not_needed($ward, $wardnn, $wardother )
    {
        if('' == $wardother) {
            return 1;
        } elseif($ward == "t") {
            return 0;
        } elseif($wardnn=="t") {
            return 1;
        } elseif($wardnn=="f") {
            return 0;
        } else {
            return 0;
        }
    }

    function build_ward_surrogate_other($ward, $wardother )
    {
        if('' != $wardother) {
            return 1;
        } else {
            return 0;
        }
    }

    function build_ward_surrogate_reason($ward, $wardReason )
    {
        if('' == $wardReason) {
            return 0;
        } else {
            return $wardReason;
        }
    }

    function buildStudentListData($studentListData)
    {

        $this->studentListData = $studentListData;

    }

    function buildStudentListData_fromSessionSearch()
    {

        $this->studentListData = array();

        if('No search defined yet!' != $_SESSION['sessCurrentStudentSearch'] && !empty($_SESSION['sessCurrentStudentSearch'])) {
            if (!$result = buildStudentListData_fromSessionSearch($_SESSION['sessCurrentStudentSearch'], $errorId, $errorMsg, true, false)) {
                include_once("error.php");
                exit;
            }
            for ($i = 0; $i < 30 && $i < pg_num_rows($result); $i++) {
                array_push($this->studentListData, pg_fetch_array($result, $i, PGSQL_ASSOC));
            }
        }
    }

    function buildNav($currentStudentID, $studentListArray, &$prevLinkStudentID, &$nextLinkStudentID)
    {
        $lastStudentID = "";
        $studentID = "";

        $prevLinkStudentID = "";
        $nextLinkStudentID = "";
        $setNextLink = false;
        foreach($studentListArray as $key => $data)
        {
            $studentID = $data['id_student'];

            if($setNextLink)
            {
                $nextLinkStudentID = $studentID;
                return true;
            }

            // this is the currently displayed student
            if($studentID == $currentStudentID)
            {
                // set prev link to student from previous iteration
                $prevLinkStudentID = $lastStudentID;

                $setNextLink = true;
            }
            $lastStudentID = $studentID;

            #echo "id_student: " . $data['id_student'] . "<BR>";
        }
    }

    function output_tableOutfacingMaster($import, $arrValidationResults, $sesisValidation, $additionalData, $formAction, $pos)
    {
        global $DOC_ROOT, $sessIdUser;

        if(1000254 == $sessIdUser)
        {

            if
            (
                ( 1 == $arrValidationResults['051']['data'] || 0 == $arrValidationResults['051']['data'] ) && !emptyAndNotZero($arrValidationResults['051']['data'])
            )
            {
            } else {
            }
        }
        $additionalData['mostRecentIEP_ageAtDateNotice'] = $this->mostRecentIEP_ageAtDateNotice;
        $additionalData['mostRecentIEP_date_notice'] = $this->mostRecentIEP_date_notice;
        $additionalData['nssrsSubmissionPeriod'] = $this->nssrsSubmissionPeriod;

        $additionalData['mostRecentMDT_ageAtDateNotice'] = $this->mostRecentMDT_ageAtDateNotice;
        $additionalData['mostRecentMDT_date_notice'] = $this->mostRecentMDT_date_notice;

        $additionalData['settingCode'] = $this->settingCode;

        $currentStudentID = $additionalData['studentData']['id_student'];

        $prevLinkStudentID = "";
        $nextLinkStudentID = "";
        $this->buildNav($currentStudentID, $this->studentListData, $prevLinkStudentID, $nextLinkStudentID);

        $output = "";
        //$output .= "<form action=\"$formAction\" method=\"post\">";
        $output .= "<input type=\"hidden\" name=\"nssrs_form_submitted\" value=\"1\">";
        $output .= "<input type=\"hidden\" name=\"nssrs_goto_student\" value=\"\">";

        //$output .= "<input type=\"hidden\" name=\"\" value=\"\">";

        $output .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\">";

        $output .= "<TR class=\"\">";

        $output .= "<TD COLSPAN=\"3\" style=\"font-size:12px;\">";
        if('' != $prevLinkStudentID) $output .= "<a href=\"#\" onClick=\"document.forms[0].nssrs_goto_student.value='".$prevLinkStudentID."';javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');recordAction(document.forms[0], 'save');\">" . "<img src=\"images/button_prev.gif\" alt=\"\">" . "</a> ";
        if('' != $nextLinkStudentID) $output .= "<a href=\"#\" onClick=\"document.forms[0].nssrs_goto_student.value='".$nextLinkStudentID."';javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');recordAction(document.forms[0], 'save');\">" . "<img src=\"images/button_next.gif\" alt=\"\">" . "</a> ";
        $output .= "</TD>";

        $output .= "<TD COLSPAN=\"2\"style=\"TEXT-ALIGN:right;font-size:12px;\">";
        $output .= "<a accesskey=\"d\" href=\"javascript:recordAction(document.forms[0], 'done');\"><img src=\"images/button_done.gif\" alt=\"Done\" title=\"Done (shortcut key = D)\"></a>";
        $output .= "<span id=\"refresh\"><a href=\"javascript:history.go(0);\"><img src=\"images/button_refresh.gif\" alt=\"refresh\" title=\"No changes have been made.\"></a></span>";
        $output .= "<span id=\"revert\"><a ><img src=\"images/button_revert_off.gif\" alt=\"Revert\" title=\"No changes have been made.\"></a></span>";
        $output .= "<span id=\"save\"><a ><img src=\"images/button_save_off.gif\" alt=\"Save\" title=\"No changes have been made.\"></a></span>";
        $output .= "</TD>";

        $output .= "</TR>";

        $output .= "<TR class=\"bgDark\">";

        $this->build_most_recent_form_data_draft();

        if('' != $this->mostRecentIEP['id_form_004'] && -1 == $this->mostRecentDraftIEP) {
            $output .= "<TD class=\"btsbWhite\" style=\"font-size:11px;\">";
            $output .= "<a href=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_004&document=" . $this->mostRecentIEP['id_form_004'] . "&page=&option=dupe_form_004&dupe_type=full\" target=\"_blank\" class=\"btsbWhite\">DUPE LAST IEP</a>";
            $output .= "</TD>";

        } elseif(-1 != $this->mostRecentDraftIEP) {
            $output .= "<TD class=\"btsbWhite\" style=\"font-size:11px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_004&document=" . $this->mostRecentDraftIEP['id_form_004'] . "&page=&option=view\" target=\"_blank\" class=\"btsbWhite\"><B>VIEW DRAFT IEP</B></a>";
            $output .= "</TD>";
        } else {
            $output .= "<TD class=\"btsb\" style=\"font-size:11px;\">";
            $output .= "NO IEP FOUND";
            $output .= "</TD>";
        }

        if('' != $this->mostRecentMDT['id_form_002'] && -1 == $this->mostRecentDraftMDT) {
            $output .= "<TD class=\"btsbWhite\" style=\"font-size:11px;\">";
            $output .= "<a href=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_002&document=" . $this->mostRecentMDT['id_form_002'] . "&page=&option=dupe&fullUpdate=1\" target=\"_blank\" class=\"btsbWhite\">DUPE LAST MDT</a>";
            $output .= "</TD>";

        } elseif(-1 != $this->mostRecentDraftMDT) {
            $output .= "<TD class=\"btsbWhite\" style=\"font-size:11px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_002&document=" . $this->mostRecentDraftMDT['id_form_002'] . "&page=&option=view\" target=\"_blank\" class=\"btsbWhite\"><B>VIEW DRAFT MDT</B></a>";
            $output .= "</TD>";
        } else {
            $output .= "<TD class=\"btsb\" style=\"font-size:11px;\">";
            $output .= "NO MDT FOUND";
            $output .= "</TD>";
        }

        if('' != $this->mostRecent013['id_form_013'] && -1 == $this->mostRecentDraft013) {
            $output .= "<TD class=\"btsbWhite\" style=\"font-size:11px;\">";
            $output .= "<a href=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_013&document=" . $this->mostRecent013['id_form_013'] . "&page=&option=dupe_form_013_update\" target=\"_blank\" class=\"btsbWhite\">DUPE LAST IFSP</a>";
            $output .= "</TD>";

        } elseif(-1 != $this->mostRecentDraft013) {
            $output .= "<TD class=\"btsbWhite\" style=\"font-size:11px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_013&document=" . $this->mostRecentDraft013['id_form_013'] . "&page=&option=view\" target=\"_blank\" class=\"btsbWhite\"><B>VIEW DRAFT IFSP</B></a>";
            $output .= "</TD>";
        } else {
            $output .= "<TD class=\"btsb\" style=\"font-size:11px;\">";
            $output .= "NO IFSP FOUND";
            $output .= "</TD>";
        }
        if(-1 == $this->mostRecentDraftMDTCard) {
            $output .= "<TD class=\"btsbWhite\" style=\"font-size:11px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_022&student=" . $currentStudentID . "&option=new\" target=\"_blank\" class=\"btsbWhite\"><B>NEW MDT CARD</B></a>";
            $output .= "</TD>";

        } elseif(-1 != $this->mostRecentDraftMDTCard) {
            $output .= "<TD class=\"btsbWhite\" style=\"font-size:11px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_022&document=" . $this->mostRecentDraftMDTCard['id_form_022'] . "&page=&option=view\" target=\"_blank\" class=\"btsbWhite\"><B>VIEW DRAFT MDT CARD</B></a>";
            $output .= "</TD>";
        } else {
            $output .= "<TD class=\"btsb\" style=\"font-size:11px;\">";
            $output .= "NO MDT FOUND";
            $output .= "</TD>";
        }

        if(-1 == $this->mostRecentDraftIEPCard) {


            $newLink = "<a href=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_023&student=" . $currentStudentID . "&option=new";
            $newLink .= "&percent={$this->import['050']->data}";

            //pre_print_r($this->import);
            if(7 == $this->import['016']->data) {
                $newLink .= "&service_ot=t";
                $newLink .= "&service_pt=t";
                $newLink .= "&service_slt=t";
            } elseif(6 == $this->import['016']->data) {
                $newLink .= "&service_ot=t";
                $newLink .= "&service_slt=t";
            } elseif(5 == $this->import['016']->data) {
                $newLink .= "&service_pt=t";
                $newLink .= "&service_slt=t";
            } elseif(4 == $this->import['016']->data) {
                $newLink .= "&service_ot=t";
                $newLink .= "&service_pt=t";
            } elseif(3 == $this->import['016']->data) {
                $newLink .= "&service_slt=t";
            } elseif(2 == $this->import['016']->data) {
                $newLink .= "&service_pt=t";
            } elseif(1 == $this->import['016']->data) {
                $newLink .= "&service_ot=t";
            } else {
                $newLink .= "&service_none=t";
            }
            $newLink .= "&date_conference=".self::date_massage($nssrsObj->mostRecentIEP['date_conference'] . '+1 day');
            $newLink .= "\" target=\"_blank\">Create IEP Data Card</a>";

            $output .= "<TD class=\"btsbWhite\" style=\"font-size:11px;\">";
            $output .= $newLink;
            $output .= "</TD>";

        } elseif(-1 != $this->mostRecentDraftIEPCard) {
            $output .= "<TD class=\"btsbWhite\" style=\"font-size:11px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_023&document=" . $this->mostRecentDraftIEPCard['id_form_023'] . "&page=&option=view\" target=\"_blank\" class=\"btsbWhite\"><B>VIEW DRAFT IEP CARD</B></a>";
            $output .= "</TD>";
        } else {
            $output .= "<TD class=\"btsb\" style=\"font-size:11px;\">";
            $output .= "NO IEP/IFSP FOUND";
            $output .= "</TD>";
        }

        $output .= "</TR>";

        // DISPLAY NSSRS COMPLETE STATUS
        $output .= "<TR class=\"bgLight2\">";
        $sesisValObj = new sesis_validation_2007($this->sesisData, $this->sesisValidation);
        $sesisComplete = $sesisValObj->check_all_pass();
        if(true === $sesisComplete) {
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<B>RECORD STATUS:</B> " . " COMPLETE<BR>";
            $output .= "</TD>";
        } else {
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<B>RECORD STATUS:</B> " . " INCOMPLETE";
            $output .= "</TD>";
        }

        $output .= "</TR>";

        // DISPLAY LINK TO STUDENT
        if('' != $currentStudentID) {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=student&student=" . $currentStudentID . "&option=view\" target=\"_blank\"><B>VIEW STUDENT PROFILE</B></a>";
            $output .= "</TD>";
            $output .= "</TR>";
        }

        // DISPLAY LINK TO IEP IF EXISTS
        if('' != $this->mostRecentIEP['id_form_004']) {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_004&document=" . $this->mostRecentIEP['id_form_004'] . "&page=&option=view\" target=\"_blank\"><B>VIEW IEP</B></a> Used for this report";
            $output .= "</TD>";
            $output .= "</TR>";
        } elseif('' != $this->mostRecentIEPCard['id_form_023']) {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_023&document=" . $this->mostRecentIEPCard['id_form_023'] . "&page=&option=view\" target=\"_blank\"><B>VIEW IEP/IFSP Card</B></a> Used for this report";
            $output .= "</TD>";
            $output .= "</TR>";
        } else {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "No Finalized IEP used";
            $output .= "</TD>";
            $output .= "</TR>";
        }

        // DISPLAY LINK TO IFSP IF EXISTS
        if('' != $this->mostRecent013['id_form_013']) {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_013&document=" . $this->mostRecent013['id_form_013'] . "&page=&option=view\" target=\"_blank\"><B>VIEW IFSP</B></a> Used for this report";
            $output .= "</TD>";
            $output .= "</TR>";
        } else {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "No Finalized IFSP used";
            $output .= "</TD>";
            $output .= "</TR>";
        }

        // DISPLAY LINK TO FORM 009 IF EXISTS
        if('' != $this->mostRecent009['id_form_009']) {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_009&document=" . $this->mostRecent009['id_form_009'] . "&page=&option=view\" target=\"_blank\"><B>VIEW FORM 009</B></a> Used for this report";
            $output .= "</TD>";
            $output .= "</TR>";
        } else {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "No Finalized Form 009 used";
            $output .= "</TD>";
            $output .= "</TR>";
        }

        // DISPLAY LINK TO FORM 012 IF EXISTS
        if('' != $this->mostRecent012['id_form_012']) {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_012&document=" . $this->mostRecent012['id_form_012'] . "&page=&option=view\" target=\"_blank\"><B>VIEW FORM 012</B></a> Used for this report";
            $output .= "</TD>";
            $output .= "</TR>";
        } else {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "No Finalized Form 012 used";
            $output .= "</TD>";
            $output .= "</TR>";
        }

        // DISPLAY LINK TO MDT IF EXISTS
        if('' != $this->mostRecentMDT['id_form_002']) {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_002&document=" . $this->mostRecentMDT['id_form_002'] . "&page=&option=view\" target=\"_blank\"><B>VIEW MDT</B></a> Used for this report";
            $output .= "</TD>";
            $output .= "</TR>";
        } elseif('' != $this->mostRecentMDTCard['id_form_022']) {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=form_022&document=" . $this->mostRecentMDTCard['id_form_022'] . "&page=&option=view\" target=\"_blank\"><B>VIEW MDT CARD</B></a> Used for this report";
            $output .= "</TD>";
            $output .= "</TR>";
        } else {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "No Finalized MDT used";
            $output .= "</TD>";
            $output .= "</TR>";
        }

        $output .= "<TR class=\"bgLight2\">";

        $output .= "<TD colspan=\"4\" style=\"vertical-align: top; font-size:12px;\">";
        $output .= $this->output_tableOutfacing($import, $arrValidationResults, $sesisValidation, $additionalData, $formAction);
        $output .= "</TD>";

        $output .= "<TD colspan=\"1\" style=\"vertical-align:top;font-size:12px;\">";
        $output .= "<table width=\"100%\" border=\"0\">";

        for ($i = 0; $i < 30 && $i < count($this->studentListData); $i++) {
            $studentData = $this->studentListData[$i];



            if($currentStudentID == $studentData['id_student']) {

                if(false === $sesisComplete) {
                    $studentLinkColor = "red";
                } elseif(true === $sesisComplete) {
                    $studentLinkColor = "green";
                }

                $output .= "<TR class=\"\">";
                $output .= "<TD style=\"width:70%;font-size: 12px;\" >";
                $output .= "<span style=\"color:$studentLinkColor\">" . $studentData['name_full'] . "</span>";
                $output .= "</TD>";
                $output .= "</TR>";

            } else {

                // get nssrs complete status for this student
                $nssrsListCheck = new sesis();
                $nssrsTempData = $nssrsListCheck->sesis_collection($studentData['id_student']);

                $sesisValObj = new sesis_validation_2007($nssrsTempData, $this->sesisValidation);
                $sesisCompleteTemp = $sesisValObj->check_all_pass();

                if(false === $sesisCompleteTemp) {
                    $studentLinkColor = "red";
                } elseif(true === $sesisCompleteTemp) {
                    $studentLinkColor = "green";
                }


                $output .= "<TR class=\"bgLight\">";
                $output .= "<TD style=\"width:70%;font-size:12px;\" >";
#                            $output .= "<a href=\"#\" style=\"color:$studentLinkColor\" onClick=\"document.forms[0].nssrs_goto_student.value='".$studentData['id_student']."';javascript:modified('$DOC_ROOT', '$area', '$sub', '$keyName', '$pkey', '$page');recordAction(document.forms[0], 'save');\">" . $studentData['name_full'] . "</a>";
                $output .= "<a href=\"javascript:document.forms[0].pos.value=$pos; if ( document.forms[0].count != null ) { document.forms[0].count.value=2; document.forms[0].nssrs_goto_student.value='".$studentData['id_student']."'; }  document.forms[0].submit();\" style=\"color:$studentLinkColor\">" . $studentData['name_full'] . "</a>";
                $output .= "</TD>";
                $output .= "</TR>";

            }
        }

        $output .= "</table>";
        $output .= "</TD>";

        $output .= "</TR>";
        //}

        $output .= "</table>";
        //$output .= "</form>";

        echo $output;
    }
    function output_tableOutfacingMaster_transfer($import, $arrValidationResults, $sesisValidation, $additionalData, $formAction, $pos, $importMessage = '')
    {
        global $DOC_ROOT, $sessIdUser;
        $additionalData['mostRecentIEP_ageAtDateNotice'] = $this->mostRecentIEP_ageAtDateNotice;
        $additionalData['mostRecentIEP_date_notice'] = $this->mostRecentIEP_date_notice;
        $additionalData['nssrsSubmissionPeriod'] = $this->nssrsSubmissionPeriod;

        $additionalData['mostRecentMDT_ageAtDateNotice'] = $this->mostRecentMDT_ageAtDateNotice;
        $additionalData['mostRecentMDT_date_notice'] = $this->mostRecentMDT_date_notice;


        $additionalData['settingCode'] = $this->settingCode;

        $currentStudentID = $additionalData['studentData']['id_student'];

        $prevLinkStudentID = "";
        $nextLinkStudentID = "";
        $this->buildNav($currentStudentID, $this->studentListData, $prevLinkStudentID, $nextLinkStudentID);

        $output = "";
        //$output .= "<form action=\"$formAction\" method=\"post\">";
        $output .= "<input type=\"hidden\" name=\"nssrs_form_submitted\" value=\"1\">";
        $output .= "<input type=\"hidden\" name=\"nssrs_goto_student\" value=\"\">";
        $output .= "<input type=\"hidden\" name=\"id_nssrs_transfers\" value=\"".$additionalData['id_nssrs_transfers']."\">";


        $output .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\">";

        $formInput = new form_element('function', 'return');
        if('' != $importMessage) {
            $output .= "<TR class=\"bgLight2\">";
            $output .= "    <TD style=\"font-size:12px;\"><B>";
            $output .= $importMessage;
            $output .= "    </B></TD>";
            $output .= "</TR>";
        }
        $output .= "<TR class=\"bgLight2\">";
        $output .= "    <TD style=\"font-size:12px;\">";
        $output .= "        Enter NSSRS ID # number here and save to import student data: ";
        $output .= $formInput->form_input_text("import_student_data_nssrs", '', true, ' size="10" '. $this->JSmodifiedCode);
        $output .= "    </TD>";
        $output .= "</TR>";


        $output .= "<TR class=\"\">";


        $output .= "<TD COLSPAN=\"2\"style=\"TEXT-ALIGN:right;font-size:12px;\">";
        $output .= "<a accesskey=\"d\" href=\"javascript:recordAction(document.forms[0], 'done');\"><img src=\"images/button_done.gif\" alt=\"Done\" title=\"Done (shortcut key = D)\"></a>";
        $output .= "<span id=\"refresh\"><a href=\"javascript:history.go(0);\"><img src=\"images/button_refresh.gif\" alt=\"refresh\" title=\"No changes have been made.\"></a></span>";
        $output .= "<span id=\"revert\"><a ><img src=\"images/button_revert_off.gif\" alt=\"Revert\" title=\"No changes have been made.\"></a></span>";
        $output .= "<span id=\"save\"><a ><img src=\"images/button_save_off.gif\" alt=\"Save\" title=\"No changes have been made.\"></a></span>";
        $output .= "</TD>";

        $output .= "</TR>";

        // DISPLAY NSSRS COMPLETE STATUS
        $output .= "<TR class=\"bgLight2\">";
        $sesisValObj = new sesis_validation_2007($this->sesisData, $this->sesisValidation);
        //pre_print_r($this->sesisData);
        //pre_print_r($this->arrValidationResults);
        $sesisComplete = $sesisValObj->check_all_pass();
        if(true === $sesisComplete) {
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<B>RECORD STATUS:</B> " . " COMPLETE<BR>";
            $output .= "</TD>";
        } else {
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<B>RECORD STATUS:</B> " . " INCOMPLETE";
            $output .= "</TD>";
        }

        $output .= "</TR>";

        //pre_print_r($this->mostRecentIEPCard);
        // DISPLAY LINK TO STUDENT
        if('' != $currentStudentID) {
            $output .= "<TR class=\"bgLight\">";
            $output .= "<TD colspan=\"5\" style=\"font-size:12px;\">";
            $output .= "<A HREF=\"" . $DOC_ROOT . "/srs.php?area=student&sub=student&student=" . $currentStudentID . "&option=view\" target=\"_blank\"><B>VIEW STUDENT PROFILE</B></a>";
            $output .= "</TD>";
            $output .= "</TR>";
        }

        $output .= "<TR class=\"bgLight2\">";

        $output .= "<TD colspan=\"5\" style=\"vertical-align: top; font-size:12px;\">";
        $output .= $this->output_tableOutfacing($import, $arrValidationResults, $sesisValidation, $additionalData, $formAction, true);
        $output .= "</TD>";

        $output .= "</TR>";
        $output .= "<TR class=\"bgLight2\">";

        $output .= "<TD colspan=\"5\" style=\"vertical-align: top; font-size:12px;\">";
        $checked = ('t' == $this->exclude_from_nssrs_report) ? ' checked=\"checked\"' : '';
        $output .= "Exclude file from NSSRS Upload <input type=\"checkbox\" name=\"exclude_from_nssrs_report\" onfocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\" value=\"t\" $checked>";
        $output .= "</TD>";
        $output .= "</TR>";
        //}

        $output .= "</table>";
        //$output .= "</form>";

        echo $output;
    }
    function output_tableOutfacing($import, $arrValidationResults, $sesisValidation, $additionalData, $formAction, $transfer = false)
    {

        global $sessIdUser;

        $output = "";
        $output .= "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">";
        $output .= "<TR>";
        $output .= "<TD class=\"bgLight2\" style=\"font-size:12px;\">";
        $output .= "&nbsp;";
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight2\" style=\"font-size:12px;\">";
        $output .= "<B>Name</B>";

        $output .= "</TD>";

        if('' != $additionalData['studentData']['id_student'])
        {
            $output .= "<TD class=\"bgLight\" style=\"font-size:12px;\">";
            $output .= $additionalData['studentData']['name_student_full'];
            $output .= "</TD>";

        } else {
            $output .= "<TD class=\"bgLight\" style=\"font-size:12px;\">";
            $output .= "<input type=\"text\" name=\"transfer_name_full\" onfocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\" value=\"".$additionalData['studentData']['transfer_name_full']."\">";
            $output .= "</TD>";
        }

        $output .= "</TR>";

        foreach($import as $key => $sesisRow)
        {
            if($transfer)
            {
                $sesisRow->html_row_displayOutfacing_transfer($key, $output, $arrValidationResults, $sesisValidation, $additionalData, $import);
            } else {
                $sesisRow->html_row_displayOutfacing($key, $output, $arrValidationResults, $sesisValidation, $additionalData);
            }
        }

        //
        // TEMP: DOB
        //
        $output .= "<TR class=\"bgLight\">";

        $output .= "<TD class=\"bgLight2\" colspan=\"1\" style=\"font-size:12px;\">";
        $output .= "&nbsp;";
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight2\" colspan=\"1\" style=\"font-size:12px;\">";
        $output .= "Student DOB: ";
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight\" colspan=\"3\" style=\"font-size:12px;\">";
        $output .= $additionalData['studentData']['dob'];
        $output .= "</TD>";

        $output .= "</TR>";

        //
        // TEMP: Student Age
        //
        $output .= "<TR class=\"bgLight\">";

        $output .= "<TD class=\"bgLight2\" colspan=\"1\" style=\"font-size:12px;\">";
        $output .= "&nbsp;";
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight2\" colspan=\"1\" style=\"font-size:12px;\">";
        $output .= "Current Student Age: ";
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight\" colspan=\"3\" style=\"font-size:12px;\">";
        $output .= $additionalData['studentData']['age'];
        $output .= "</TD>";

        $output .= "</TR>";

        // TEMP: Student Age at MDT Date Notice
        //
        $output .= "<TR class=\"bgLight\">";

        $output .= "<TD class=\"bgLight2\" colspan=\"1\" style=\"font-size:12px;\">";
        $output .= "&nbsp;";
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight2\" colspan=\"1\" style=\"font-size:12px;\">";
        $output .= "Age at MDT Date Notice: ";
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight\" colspan=\"3\" style=\"font-size:12px;\">";
        $output .= $additionalData['mostRecentMDT_ageAtDateNotice'] . " (" . $additionalData['mostRecentMDT_date_notice'] . ")";
        $output .= "</TD>";

        $output .= "</TR>";

        //
        // is an IEP used for this report? yes/no
        //
        $output .= "<TR class=\"bgLight\">";

        $output .= "<TD class=\"bgLight2\" colspan=\"1\" style=\"font-size:12px;\">";
        $output .= "&nbsp;";
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight2\" colspan=\"1\" style=\"font-size:12px;\">";
        $output .= "Current IEP found: ";
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight\" colspan=\"3\" style=\"font-size:12px;\">";
        if($this->mostRecentIEPCard !== true && '' != $this->mostRecentIEP['id_form_004']) {
            $output .= "Yes";
        } else {
            $output .= "No";
        }
        $output .= "</TD>";

        $output .= "</TR>";

        //
        // is an MDT used for this report? yes/no
        //
        $output .= "<TR class=\"bgLight\">";

        $output .= "<TD class=\"bgLight2\" colspan=\"1\" style=\"font-size:12px;\">";
        $output .= "&nbsp;";
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight2\" colspan=\"1\" style=\"font-size:12px;\">";
        $output .= "Current MDT found: ";
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight\" colspan=\"3\" style=\"font-size:12px;\">";
        if($this->mostRecentMDTCard !== true && '' != $this->mostRecentMDT['id_form_002']) {
            $output .= "Yes";
        } else {
            $output .= "No";
        }
        $output .= "</TD>";

        $output .= "</TR>";


        $output .= "<TR class=\"bgLight\">";

        $output .= "<TD class=\"bgLight\" colspan=\"3\" style=\"font-size:12px;\">";
        $output .= "&nbsp;";
        $output .= "</TD>";

        $output .= "</TR>";

        $output .= "<TR class=\"bgLight\">";

        $output .= "<TD class=\"bgLight\" colspan=\"3\" style=\"font-size:12px;\">";
        $output .= "UPLOAD CODE";
        $output .= "</TD>";

        $output .= "</TR>";

        $output .= "<TR class=\"bgLight\">";

        $output .= "<TD class=\"bgLight\" colspan=\"3\" style=\"font-size:12px;\">";
        $output .= implode(", ", $this->sesisData);
        $output .= "</TD>";

        $output .= "</TR>";

        $output .= "</table>";
        //$output .= "</form>";

        return $output;
    }

    function output_table($import, $arrValidationResults, $sesisValidation)
    {
        //pre_print_r($sesisValidation);
        $output = "";
        $output .= "<table border=\"0\">";

        $output .= "<TD class=\"bgLight2\" style=\"font-size:12px;\">";
        $output .= "number";
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight2\" style=\"font-size:12px;\">";
        $output .= "name";
        $output .= "</TD>";

        $output .= "<TD style=\"font-size:12px;\">";
        $output .= "arrData";
        $output .= "</TD>";

        $output .= "<TD style=\"font-size:12px;\">";
        $output .= "type";
        $output .= "</TD>";

        $output .= "<TD style=\"font-size:12px;\">";
        $output .= "length";
        $output .= "</TD>";

        $output .= "<TD style=\"font-size:12px;\">";
        $output .= "validation";
        $output .= "</TD>";

        $output .= "<TD style=\"font-size:12px;\">";
        $output .= "validation code";
        $output .= "</TD>";

        $output .= "<TD style=\"font-size:12px;\">";
        $output .= "note";
        $output .= "</TD>";

        $output .= "<TD style=\"font-size:12px;\">";
        $output .= "other errors";
        $output .= "</TD>";

        foreach($import as $key => $sesisRow)
        {
            $sesisRow->html_row_display($key, $output, $arrValidationResults, $sesisValidation);
        }
        $output .= "</table>";

        echo $output;

    }

    function check_exitReason($age, $exitReason)
    {
        #echo "exitReason: $exitReason<BR>";
        if( 3 > $age)
        {
            switch($exitReason)
            {
                case 1:
                case 6:
                case 9:
                case 10:
                case 12:
                case 13:
                case 14:
                case 15:
                case 16:
                case 17:
                    return true;
                    break;
            }
            return false;
        } elseif( 3 <= $age && 22 > $age ) {
            switch($exitReason)
            {
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                case 10:
                case 11:
                    return true;
                    break;
            }
            return false;
        } elseif( 22 <= $age) {
            switch($exitReason)
            {
                case 18:
                    return true;
                    break;
            }
            return false;

        }
    }

    function makeSESISpercent($floatNum) {
        if('' == $floatNum) return 0;
        return $floatNum;
    }


    function insertOrUpdate(&$pkey, &$arrFieldList, &$arrData, &$tableName, &$pkeyName, $sqlStmt = "") {
        global $sessIdUser;
        // if no stmt is supplied, build default stmt
        if (empty($sqlStmt)) {
            reset($arrFieldList);

            if ($pkey) {
                //print_r( $arrFieldList );
                //print_r( $arrData );
                $sqlStmt = $this->buildUpdateStmt($pkey, $arrFieldList, $arrData, $tableName, $pkeyName);
            } else {
                $sqlStmt = $this->buildInsertStmt($arrFieldList, $arrData, $tableName, $pkeyName);
            }
        }

        // execute stmt
        //if('1000254' == $sessIdUser) print( "save student sqlStmt: ".str_replace("\n", " ", $sqlStmt));
        if($result = sqlExec($sqlStmt, $this->errorId, $this->errorMsg, true, true)) {
            // if new record, get id of record just inserted
            if (empty($pkey)) {
                $oid = pg_getlastoid($result);
                $sqlStmt = "SELECT $pkeyName FROM $tableName WHERE oid = $oid;";
                if($result = sqlExec($sqlStmt, $this->errorId, $this->errorMsg, true, true)) {
                    $arrData = pg_fetch_array($result, 0);
                    return $arrData[0];
                } else {
                    return false;
                }
            } else {
                $logType = 3;
                $tableName = $tableName;
                if (writeLog($pkey, $logType, $tableName, $this->errorId, $this->errorMsg)) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            #debugLog("FAILED");
            return false;
        }
    }

    /* build INSERT statement */
    function buildInsertStmt(&$arrFieldList, &$arrData, &$tableName, &$pkeyName) {

        reset($arrFieldList);

        $sqlStmt = 	"INSERT INTO $tableName (id_author, id_author_last_mod, ";
        while (list($fieldName, $value) = each($arrFieldList)) {
            if ($i++) {
                $sqlStmt .= ", ";
            }
            $sqlStmt .= $fieldName;
        }
        $sqlStmt .= ")\nVALUES ('0', '0', ";
        reset($arrFieldList);
        $i = 0;
        while (list($fieldName, $value) = each($arrFieldList)) {
            if ($i++) {
                $sqlStmt .= ", ";
            }
            $dataElement = addslashes( stripslashes( $arrData[$fieldName] ) );
            $sqlStmt .= "'$dataElement'";
        }
        $sqlStmt .= ");\n";

        return $sqlStmt;
    }

    /* build UPDATE statement */
    function buildUpdateStmt(&$pkey, &$arrFieldList, &$arrData, &$tableName, &$pkeyName) {

        global $sessIdUser;

        reset($arrFieldList);
        $sqlStmt = 	"UPDATE $tableName\nSET ";
        while (list($fieldName, $value) = each($arrFieldList)) {
            if ($i++) {
                $sqlStmt .= ", ";
            }
            $dataElement = addslashes( stripslashes( $arrData[$fieldName] ) );
            $sqlStmt .= $fieldName . " = '" . $dataElement . "'";
        }
        $sqlStmt .= "\nWHERE $pkeyName = $pkey;\n";

        return $sqlStmt;
    }

    function getAdminReportingSettings()
    {
        $adminSettingsObj = new Model_Table_AdminSettings();
        $adminSettingRows = $adminSettingsObj->fetchAll();
        if(count($adminSettingRows)>0) {
            $adminRec = $adminSettingRows->current();
            $this->nssrsSubmissionPeriod = date('Y-m-d', strtotime($adminRec['nssrs_submition_date']));
            $this->nssrsSnapshotDate = date('Y-m-d', strtotime($adminRec['nssrs_school_year']));
            $this->octoberCuttoff = date('Y-m-d', strtotime($adminRec['october_cutoff']));
        } else {
            throw new Exception("Couldn't get admin reporting record.");
        }
    }

    static function date_massage($dateField, $dateFormat = 'm/d/Y') {

        if(empty($dateField) ) {
            return;
        }

        # strtotime mishandles dates with '-' 
        $dateField=str_replace("-","/",$dateField);
        date_default_timezone_set('GMT');
        return date($dateFormat, strtotime($dateField));

    }

}


?>