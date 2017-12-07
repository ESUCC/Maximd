<?php

/**
 * Created by PhpStorm.
 * User: jlavere
 * Date: 3/25/15
 * Time: 12:04 PM
 */
class App_Report_Nssrs
{
    public $idStudent;

    public $forms = array(
        '002' => array(
            'id' => '002',
            'title' => 'MDT',
        ),
        '004' => array(
            'id' => '004',
            'title' => 'IEP',
        ),
        '009' => array(
            'id' => '009',
            'title' => 'Notice of Discontinuation of Special Education Services',
        ),
        '012' => array(
            'id' => '012',
            'title' => 'Determination Notice',
        ),
        '013' => array(
            'id' => '013',
            'title' => 'IFSP',
        ),
        '022' => array(
            'id' => '022',
            'title' => 'MDT Card',
        ),
        '023' => array(
            'id' => '023',
            'title' => 'IEP/IFSP Card',
        ),
    );

    public $nssrsSubmissionPeriod = "";// 035 grabbed from admin_settings table
    public $nssrsSnapshotDate = "";    // 003 grabbed from admin_settings table
    public $nssrsTransitionCutoffDate = "8/31/2008"; // used?
    public $form;
    public $valid;
    public $type = 'NSSRS';

    function __construct($idStudent, $useTransfer = false, $forceTransferData = null, $forceFields = false)
    {
        $this->idStudent = $idStudent;
        $this->student = $this->getStudent($this->idStudent);

        $this->getAdminReportingSettings();
        $this->selectFormsForUse($this->idStudent);
        $this->markExistsDates();
        $this->removeExpiredForms();

        if (false === $this->student) {
            $formData = array();
        } else {
            $formData = $this->buildReportData();
        }

        if (!is_null($forceTransferData)) {
            if (count($forceTransferData)) {
                $this->type = 'Transfer';
                // map transfer data to local keys
                $formData = array();
                foreach ($forceTransferData as $fieldName => $fieldValue) {
                    if ('nssrs_0' == substr($fieldName, 0, 7)) {
                        $formData[substr($fieldName, -3, 3)] = $fieldValue;
                    }
                }
            }
        }

        if ($forceFields && !is_null($forceTransferData)) {
            if (count($forceTransferData)) {
                $this->type = 'Transfer';
                // map transfer data to local keys
                $formData = array();
                foreach ($forceTransferData as $fieldName => $fieldValue) {
                    if ('nssrs_0' == substr($fieldName, 0, 7)) {
                        $formData['field'.substr($fieldName, -2, 2)] = $fieldValue;
                    }
                }
                if(empty($formData['field06'])) {
                    $formData['field06'] = $this->student['name_first'];
                }
                if(empty($formData['field07'])) {
                    $formData['field07'] = $this->student['name_last'];
                }
            }
        }

        $options = array();
        $options['nssrsSnapshotDate'] = $this->nssrsSnapshotDate;
        $options['nssrsSubmissionPeriod'] = $this->nssrsSubmissionPeriod;
        $options['octoberCuttoff'] = $this->octoberCuttoff;
        $this->form = new Form_ReportNssrs($options);
        if($this->type == 'Transfer') {
            $this->form->buildReport(false);
        } else {
            $this->form->buildReport(true);
        }
        $this->form->buildFormOptions($formData);
        $this->form->populate($formData);
        $this->valid = $this->form->isValid($formData);

//        if(!$this->valid) {
//            Zend_Debug::dump($this->form->getMessages());
//            Zend_Debug::dump($formData);
//            die;
//        }

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
    public function buildReportData()
    {
        $data = array();
        $data['field01'] = $this->student->id_county . '-' . $this->student->id_district;
        $data['field02'] = $this->student->id_school;
        $data['field03'] = $this->nssrsSnapshotDate;
        $data['field05'] = (string)$this->student->unique_id_state; // force to string for StringLength form Validator
        $data['field06'] = $this->student->name_first;
        $data['field07'] = $this->student->name_last;
        $data['field11'] = $this->buildPrimaryDisability();
        $data['field16'] = $this->buildRelatedServices();
        $data['field23'] = $this->buildAlternateAssessment();
        $data['field32'] = $this->buildPlacementType();
        $data['field33'] = $this->build_entryDate($this->student->id_student);
        $data['field34'] = $this->student->sesis_exit_date;
        $data['field35'] = $this->nssrsSubmissionPeriod;
        $data['field44'] = $this->primarySettingCode();
        $data['field47'] = $this->primary_setting_category();
        $data['field50'] = $this->buildSpecialEdPercentage();
        $data['field52'] = $this->student->sesis_exit_code;

        return $data;
    }

    function primary_setting_category()
    {
        $DOB = $this->student->dob;
        $ageArr = $this->age_calculate(getdate(strtotime($DOB)), getdate(strtotime($this->nssrsSubmissionPeriod)));
        $ageAtSubmissionDate = $ageArr['years'];
        if ($ageAtSubmissionDate < 3) {
            return "N";
            //return "Part C";
        } elseif ($ageAtSubmissionDate >= 4) {
            return "Y";
            //return "Part B";
        } else {
            if (!is_null($this->getForm('004')) && false !== $this->getForm('004')) {
                return "Y";
            } elseif (!is_null($this->getForm('022')) && false !== $this->getForm('022')) {
                return "Y";
            } elseif (!is_null($this->getForm('013')) && false !== $this->getForm('013')) {
                return "N";
            }
        }
    }

    function age_calculate($date_from, $date_to)
    { // params are getDate arrays
        $daySeconds = 86400;
        $weekSeconds = 604800;

        $age['years'] = $date_to['year'] - $date_from['year'];
        $age['monthsTotal'] = ($date_to['mon'] + (12 * $date_to['year'])) - ($date_from['mon'] + (12 * $date_from['year']));

        // month and monthday is smaller than birth
        if (($date_to['mon'] <= $date_from['mon']) && ($date_to['mday'] <= $date_from['mday'])) {
            $age['years'] -= 1;
            $age['monthsTotal'] -= 1;
        }

        $age['months'] = $age['monthsTotal'] - ($age['years'] * 12);
        $age['daysExcact'] = ($date_to[0] - $date_from[0]) / $daySeconds;
        $age['daysExact'] = ($date_to[0] - $date_from[0]) / $daySeconds; // added to correct spelling
        $age['days'] = floor(($date_to[0] - $date_from[0]) / $daySeconds);
        $age['weeksExcact'] = ($date_to[0] - $date_from[0]) / $weekSeconds;
        $age['weeks'] = floor(($date_to[0] - $date_from[0]) / $weekSeconds);
        if ($date_to['mday'] < $date_from['mday']) {
            $age['modMonthDays'] = days_in_month($date_to['mon'] - 1,
                    $date_to['year']) - ($date_from['mday'] - $date_to['mday']);
        } else {
            $age['modMonthDays'] = $date_to['mday'] - $date_from['mday'];
        }

        // 20111122 jlavere - was returning a -month and a year too high in some cases
        if ($age['months'] < 0) {
            $age['months'] += 12;
            $age['years'] -= 1;
        }

        return $age;
    }

    function buildAlternateAssessment()
    {
        $aa = $this->student->alternate_assessment;
        if ('t' == $aa || 1 == $aa) {
            return 1;
        } elseif ('f' == $aa || 0 == $aa) {
            return 2;
        }

        return 0;

    }

    function buildPlacementType()
    {
        $pub = $this->student->pub_school_student;
        $parentalPlacement = $this->student->parental_placement;
        if ($pub) {
            return 0;
        } elseif (!$pub && $parentalPlacement) {
            return 1;
        } elseif (!$pub && !$parentalPlacement) {
            return 2;
        }
    }

    function build_entryDate($id_student)
    {
        $form002Obj = new Model_Table_Form002();
        $mdtRows = $form002Obj->getAllFinalForms($id_student, 'timestamp_created');
        foreach ($mdtRows as $mdtCard) {
            if ('' != $mdtCard['initial_verification_date']) {
                return date('Y-m-d', strtotime($mdtCard['initial_verification_date']));
            }
            if ('' != $mdtCard['initial_verification_date_sesis']) {
                return date('Y-m-d', strtotime($mdtCard['initial_verification_date_sesis']));
            }
        }

        $form022Obj = new Model_Table_Form022();
        $mdtCardRows = $form022Obj->getAllFinalForms($id_student, 'timestamp_created');
        foreach ($mdtCardRows as $mdtCard) {
            if ('' != $mdtCard['initial_verification_date']) {
                return date('Y-m-d', strtotime($mdtCard['initial_verification_date']));
            }
        }

        return '';
    }

    function buildPrimaryDisability()
    {
        $dis = null;
        if (!is_null($this->getForm('002')) && false !== $this->getForm('002')) {
            $dis = $this->getForm('002', 'final', 'disability_primary');
        } elseif (!is_null($this->getForm('022')) && false !== $this->getForm('022')) {
            $dis = $this->getForm('022', 'final', 'disability_primary');
        } else {
            // if an exit date exits, check previous MDTS for primary disability
//            $exitDate = $this->student['sesis_exit_date'];
//            if(strtotime($exitDate) >= strtotime($this->getJuneCutoff())) {
            $mdtArr = $this->selectAllFormsFromDatabase($this->idStudent, '002', 'final', 'date_mdt');
            foreach ($mdtArr as $mdt) {
                if ('' != $mdt['disability_primary']) {
                    $dis = $mdt['disability_primary'];
                }
            }
            if (is_null($dis)) {
                $mdtCardArr = $this->selectAllFormsFromDatabase($this->idStudent, '022', 'final', 'date_mdt');
                foreach ($mdtCardArr as $mdtCard) {
                    if ('' != $mdtCard['disability_primary']) {
                        $dis = $mdtCard['disability_primary'];
                    }
                }
            }
//            }
        }

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

        switch ($dis) {
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

    function getJuneCutoff()
    {
        if (date("m", strtotime("today")) >= 7) {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today"))));
        } else {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today")) . "-1 year"));
        }

        return $juneCutoff;
    }

    function buildRelatedServices()
    {
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
        //pre_print_r($this->disabilitiesArr);
        // speech lang

        $speechLngThrpy = null;
        if (!is_null($this->getForm('013')) && false !== $this->getForm('013', 'final')) {

            $form013Obj = new Model_Table_Form013();
            $serviceArr = $form013Obj->getServices($this->getForm('013', 'final', 'id_form_013'));
            $service_serviceArr = array();
            $service_whereArr = array();
            $otherText = "";
            $whereText = "";
//            $this->otherHasBeenSelected = false;
//            $this->otherWhereHasBeenSelected = false;
            if (count($serviceArr) > 0) {
                foreach ($serviceArr as $serviceRow) {
                    if (!empty($serviceRow['service_service'])) {
                        $serviceRow['service_service'] = strtolower($serviceRow['service_service']); //040929 JL lowered because we have different versions of data
                        array_push($service_serviceArr, $serviceRow['service_service']);
                        if (strtolower($serviceRow['service_service']) == 'other') {
                            if (strlen($otherText) > 0 && substr($otherText, -1) != ':') {
                                $otherText .= ":";
                            }
                            $otherText .= $serviceRow['service_other'];
//                            $this->otherHasBeenSelected = true;
                        }
                    }
                    if (!empty($serviceRow['service_where'])) {
                        $serviceRow['service_where'] = strtolower($serviceRow['service_where']); //040929 JL lowered because we have different versions of data
                        array_push($service_whereArr, $serviceRow['service_where']);
                        if (strtolower($serviceRow['service_where']) == 'other') {
                            if (strlen($whereText) > 0 && substr($whereText, -1) != ':') {
                                $whereText .= ":";
                            }
                            $whereText .= $serviceRow['service_where_other'];
//                            $this->otherWhereHasBeenSelected = true;
                        }
                    }
                }
            }

            return $this->getSpeechLangCodeForForm013($service_serviceArr);

//        } elseif (!is_null($this->getForm('023')) && false !== $this->getForm('023')) {
//
//
        } elseif (!is_null($this->getForm('004')) && false !== $this->getForm('004')) {
            $iep = $this->getForm('004');
            if ('t' != $iep['override_related']) { // don't use hidden services
                if ($iep['version_number'] >= 9) {
                    $disabilitiesArr = array();
                    $relatedServicesObj = new Model_Table_Form004RelatedService();
                    $select = $relatedServicesObj->select()
                        ->where("id_form_004 = ?", $iep['id_form_004']);
                    $relatedServices = $relatedServicesObj->fetchAll($select);
                    if (count($relatedServices)) {
                        foreach ($relatedServices as $service) {
                            $disabilitiesArr[] = $service->related_service_drop;
                        }
                    }

                } else {
                    $disabilities = $iep['related_service_drop'];
                    $disabilitiesArr = explode("|", $disabilities);

                    $disOther = $iep['related_service'];
                    $disOtherArr = explode("|", $disOther);
                    $disMergeArr = array();
                    for ($i = 0, $j = count($disabilitiesArr); $i < $j; $i++) {
                        if ($disOtherArr[$i] != '') {
                            $disMergeArr[$i] = $disabilitiesArr[$i] . " : " . $disOtherArr[$i];
                        } else {
                            if ($disabilitiesArr[$i] !== '') {
                                $disMergeArr[$i] = $disabilitiesArr[$i];
                            }
                        }
                    }

                }
            } else {
                $disabilitiesArr = array();
            }
            $priDis = $iep['primary_disability_drop'];
            $disabilitiesArr[] = $priDis;

            return $this->buildSpeechLangCode($disabilitiesArr);
        } else {
            return 8;
        }
    }

    function getStudent($idStudent)
    {
        $model = new Model_Table_IepStudent();
        $student = $model->find($idStudent);
        if ($student->count() == 1) {
            return $student->current();
        }

        return false;
    }

    function getAdminReportingSettings()
    {
        $adminSettingsObj = new Model_Table_AdminSettings();
        $adminSettingRows = $adminSettingsObj->fetchAll();
        if (count($adminSettingRows) > 0) {
            $adminRec = $adminSettingRows->current();
            $this->nssrsSubmissionPeriod = date('Y-m-d', strtotime($adminRec['nssrs_submition_date']));
            $this->nssrsSnapshotDate = date('Y-m-d', strtotime($adminRec['nssrs_school_year']));
            $this->octoberCuttoff = date('Y-m-d', strtotime($adminRec['october_cutoff']));
        } else {
            throw new Exception("Couldn't get admin reporting record.");
        }
    }

    function selectFormFromDatabase($idStudent, $formNum, $status = 'draft', $dateField = 'date_notice')
    {
        # CREATE FUNCTION LEVEL ONLY FORM OBJ
        $formName = 'Model_Table_Form' . $formNum;
        $tmpForm = new $formName();
        if ('draft' == $status) {
            $returnForm = $tmpForm->mostRecentDraftForm($idStudent, $dateField);
            if (!is_null($returnForm)) {
                return $returnForm;
            }
        } else {
            $returnForm = $tmpForm->mostRecentFinalForm($idStudent, $dateField);
            if (!is_null($returnForm)) {
                return $returnForm;
            }
        }

        return false;
    }

    function selectAllFormsFromDatabase($idStudent, $formNum, $status = 'draft', $dateField = 'date_notice')
    {
        # CREATE FUNCTION LEVEL ONLY FORM OBJ
        $formName = 'Model_Table_Form' . $formNum;
        $tmpForm = new $formName();
        if ('draft' == $status) {
            $returnForms = $tmpForm->getAllFinalForms($idStudent, $dateField);
            if (!is_null($returnForms)) {
                return $returnForms;
            }
        } else {
            $returnForms = $tmpForm->getAllFinalForms($idStudent, $dateField);
            if (!is_null($returnForms)) {
                return $returnForms;
            }
        }

        return false;
    }

    function selectFormsForUse($idStudent)
    {
        $this->setForm('002', 'final', $this->selectFormFromDatabase($idStudent, '002', 'final', 'date_mdt'));
        $this->setForm('004', 'final', $this->selectFormFromDatabase($idStudent, '004', 'final', 'date_conference'));
        $this->setForm('009', 'final', $this->selectFormFromDatabase($idStudent, '009', 'final', 'date_notice'));
        $this->setForm('012', 'final', $this->selectFormFromDatabase($idStudent, '012', 'final', 'date_notice'));
        $this->setForm('013', 'final', $this->selectFormFromDatabase($idStudent, '013', 'final', 'date_notice'));
        $this->setForm('022', 'final', $this->selectFormFromDatabase($idStudent, '022', 'final', 'date_mdt'));
        $this->setForm('023', 'final', $this->selectFormFromDatabase($idStudent, '023', 'final', 'date_conference'));

    }

    function markExistsDates()
    {
        /*
         * record the date for existing final forms
         */
        if (!is_null($this->getForm('002')) && false !== $this->getForm('002')) {
            $this->forms['002']['existing_date'] = $this->getForm('002', 'final', 'date_mdt');
        } elseif (!is_null($this->getForm('022')) && false !== $this->getForm('022')) {
            $this->forms['022']['existing_date'] = $this->getForm('022', 'final', 'date_mdt');
        } else {
            $this->forms['002']['existing_date'] = null;
            $this->forms['022']['existing_date'] = null;
        }
        if (!is_null($this->getForm('004')) && false !== $this->getForm('004')) {
            $this->forms['004']['existing_date'] = $this->getForm('004', 'final', 'date_conference');
        } elseif (!is_null($this->getForm('013')) && false !== $this->getForm('013')) {
            $this->forms['013']['existing_date'] = $this->getForm('013', 'final', 'date_notice');
        } elseif (!is_null($this->getForm('023')) && false !== $this->getForm('023')) {
            $this->forms['023']['existing_date'] = $this->getForm('023', 'final', 'date_conference');
        } else {
            $this->forms['004']['existing_date'] = null;
            $this->forms['013']['existing_date'] = null;
            $this->forms['023']['existing_date'] = null;
        }
    }

    function removeExpiredForms()
    {
        // do no use ieps or iep data cards that are over one year old
        $this->limitByTime('004', 'final', 'date_conference', 'today - 1 year');
        $this->limitByTime('013', 'final', 'date_notice', 'today - 1 year');
        $this->limitByTime('023', 'final', 'date_conference', 'today - 1 year');

        // limit mdt and mdt data card usage, allow longer if form012 exists
        $this->limitByTime('012', 'final', 'date_mdt', 'today - 3 years');
        if ($this->getForm('012') && !is_null($this->getForm('012', 'final', 'date_notice'))) {
            $form12DateNotice = strtotime($this->getForm('012', 'final', 'date_notice'));
            $this->limitByTime('002', 'final', 'date_mdt', date('m/d/Y', $form12DateNotice) . ' - 3 years');
            $this->limitByTime('022', 'final', 'date_mdt', date('m/d/Y', $form12DateNotice) . ' - 3 years');
        } else {
            $this->limitByTime('002', 'final', 'date_mdt', 'today - 3 years');
            $this->limitByTime('022', 'final', 'date_mdt', 'today - 3 years');
        }

        // IFSP/IEP/IEP Card
        $this->determineFormToUse('004', '023', 'final', 'date_conference');
        $this->determineFormToUse('004', '013', 'final', 'date_conference', 'date_notice');
        $this->determineFormToUse('023', '013', 'final', 'date_conference', 'date_notice');

        // MDT/MDT Card
        $this->determineFormToUse('002', '022', 'final', 'date_mdt');
    }

    /**
     * @param $form1
     * @param $form2
     * @return int
     */
    private function determineFormToUse(
        $form1Num,
        $form2Num,
        $status = 'final',
        $form1DateFieldName,
        $form2DateFieldName = 'timestamp_created'
    ) {
        // get forms
        $form1 = $this->getForm($form1Num, $status);
        $form2 = $this->getForm($form2Num, $status);

        if (is_object($form1) && is_object($form2)) {
            // IEP and IEP Card exist
            $form1Date = $form1[$form1DateFieldName];
            $form2Date = $form2[$form1DateFieldName];
            if (strtotime($form1Date) === strtotime($form2Date)) {
                $form1CreatedDate = $form1[$form2DateFieldName];
                $form2CreatedDate = $form2[$form2DateFieldName];
                if (strtotime($form1CreatedDate) === strtotime($form2CreatedDate)) {
                    // forms have same create date, unset form 2
                    $this->setForm($form2Num, $status, false);
                } elseif (strtotime($form1CreatedDate) > strtotime($form2CreatedDate)) {
                    // unset form 2
                    $this->setForm($form2Num, $status, false);
                } else {
                    // unset form 1
                    $this->setForm($form1Num, $status, false);
                }
            } elseif (strtotime($form1Date) > strtotime($form2Date)) {
                // unset form 2
                $this->setForm($form2Num, $status, false);
            } else {
                // unset form 1
                $this->setForm($form1Num, $status, false);
            }
        } elseif (is_object($form1)) {
            // unset form 2
            $this->setForm($form2Num, $status, false);
        } elseif (is_object($form2)) {
            // unset form 1
            $this->setForm($form1Num, $status, false);
        } else {
            // unset both forms
            $this->setForm($form1Num, $status, false);
            $this->setForm($form2Num, $status, false);
        }
    }

    public function getForm($num, $status = 'final', $field = null)
    {
        if (is_null($num)) {
            return $this->forms;

        } elseif (is_null($status) && isset($this->forms[$num])) {
            return $this->forms[$num];

        } elseif (is_null($field) && isset($this->forms[$num][$status])) {
            return $this->forms[$num][$status];

        } elseif (isset($this->forms[$num][$status][$field])) {
            return $this->forms[$num][$status][$field];
        }

        return null;
    }

    public function setForm($num, $status = 'final', $form, $x = 0)
    {
        if (!is_null($num) && isset($this->forms[$num])) {
            if ('final' == $status) {
                $this->forms[$num]['final'] = $form;
            } else {
                $this->forms[$num]['draft'] = $form;
            }
        }
    }

    public function getConfig($num = null, $param = null)
    {
        if (is_null($num)) {
            return $this->forms;
        } elseif (is_null($param) && isset($this->forms[$num])) {
            return $this->forms[$num];
        } elseif (isset($this->forms[$num][$param])) {
            return $this->forms[$num][$param];
        }

        return null;
    }

    private function limitByTime($formNumber, $status = 'final', $dateField = 'date_notice', $time = 'today - 1 year')
    {
        $dateString = $this->getForm($formNumber, $status, $dateField);
        if (!is_null($time) && !is_null($dateString) && strtotime($time) > strtotime($dateString)) {
            $this->setForm($formNumber, $status, false);
        }
    }

    /**
     * @param $disabilitiesArr
     * @return int
     */
    private function buildSpeechLangCode($disabilitiesArr)
    {
        if (in_array(("Speech-language therapy"), $disabilitiesArr) ||
            in_array(("Speech-Language therapy"), $disabilitiesArr) ||
            in_array(("Speech-language Therapy"), $disabilitiesArr) ||
            in_array(("Speech-Language Therapy"), $disabilitiesArr) ||
            in_array(("Speech/language therapy"), $disabilitiesArr) ||
            in_array(("Speech/Language therapy"), $disabilitiesArr) ||
            in_array(("Speech/language Therapy"), $disabilitiesArr) ||
            in_array(("Speech/Language Therapy"), $disabilitiesArr)
        ) {
            $spLang = 1;
        } else {
            $spLang = 0;
        }

        if (in_array(("Occupational Therapy Services"), $disabilitiesArr)
        ) {
            $occTherSer = 1;
        } else {
            $occTherSer = 0;
        }

        if (in_array(("Physical Therapy"), $disabilitiesArr)
        ) {
            $phyTherSer = 1;
        } else {
            $phyTherSer = 0;
        }

        if ($spLang == 1 && $occTherSer == 1 && $phyTherSer == 1) {
            $code = 7;
        } elseif ($spLang == 1 && $occTherSer == 1 && $phyTherSer == 0) {
            $code = 6;
        } elseif ($spLang == 1 && $occTherSer == 0 && $phyTherSer == 1) {
            $code = 5;
        } elseif ($spLang == 0 && $occTherSer == 1 && $phyTherSer == 1) {
            $code = 4;
        } elseif ($spLang == 1 && $occTherSer == 0 && $phyTherSer == 0) {
            $code = 3;
        } elseif ($spLang == 0 && $occTherSer == 0 && $phyTherSer == 1) {
            $code = 2;
        } elseif ($spLang == 0 && $occTherSer == 1 && $phyTherSer == 0) {
            $code = 1;
        } else {
            $code = 8;
        }

        return $code;
    }

    /**
     * @param $service_serviceArr
     * @return int
     */
    private function getSpeechLangCodeForForm013($service_serviceArr)
    {
        if (in_array(("Speech-language therapy"), $service_serviceArr) ||
            in_array(("Speech-Language therapy"), $service_serviceArr) ||
            in_array(("Speech-language Therapy"), $service_serviceArr) ||
            in_array(("Speech-Language Therapy"), $service_serviceArr) ||
            in_array(("Speech/language therapy"), $service_serviceArr) ||
            in_array(("Speech/Language therapy"), $service_serviceArr) ||
            in_array(("Speech/language Therapy"), $service_serviceArr) ||
            in_array(("Speech/Language Therapy"), $service_serviceArr) ||
            in_array(strtolower("Speech-language Therapy"), $service_serviceArr) ||
            in_array(strtolower("Speech/language Therapy"), $service_serviceArr)
        ) {
            $spLang = 1;
        } else {
            $spLang = 0;
        }

        if (in_array(("Occupational Therapy Services"), $service_serviceArr) ||
            in_array(strtolower("Occupational Therapy Services"), $service_serviceArr)
        ) {
            $occTherSer = 1;
        } else {
            $occTherSer = 0;
        }

        if (in_array(("Physical Therapy"), $service_serviceArr) ||
            in_array(("physical therapy"), $service_serviceArr)
        ) {
            $phyTherSer = 1;
        } else {
            $phyTherSer = 0;
        }

        if ($spLang == 1 && $occTherSer == 1 && $phyTherSer == 1) {
            return 7;
        } elseif ($spLang == 1 && $occTherSer == 1 && $phyTherSer == 0) {
            return 6;
        } elseif ($spLang == 1 && $occTherSer == 0 && $phyTherSer == 1) {
            return 5;
        } elseif ($spLang == 0 && $occTherSer == 1 && $phyTherSer == 1) {
            return 4;
        } elseif ($spLang == 1 && $occTherSer == 0 && $phyTherSer == 0) {
            return 3;
        } elseif ($spLang == 0 && $occTherSer == 0 && $phyTherSer == 1) {
            return 2;
        } elseif ($spLang == 0 && $occTherSer == 1 && $phyTherSer == 0) {
            return 1;
        }

        return 8;
    }

    /**
     * @return string
     */
    private function primarySettingCode()
    {
        
        $settingCode = null;
        if (!is_null($this->getForm('004')) && false !== $this->getForm('004')) {
            $iep = $this->getForm('004');
            $settingCode = $iep['primary_service_location'];
            $this->writevar1($settingCode,'this is the setting code');
        } elseif (!is_null($this->getForm('013')) && false !== $this->getForm('013')) {
            $form013Obj = new Model_Table_Form013();
            $form013 = $this->getForm('013');
            $serviceArr = $form013Obj->getServices($form013['id_form_013']);
            if (count($serviceArr) > 0) {
                switch ($serviceArr[0]['service_where']) {
                    case 'Home':
                        return 1;
                    case 'Community Based':
                        return 2;
                    case 'Other':
                        return 3;
                }
            }

        } elseif (!is_null($this->getForm('023')) && false !== $this->getForm('023')) {
            $iepCard = $this->getForm('023');
            $settingCode = $iepCard['service_where'];
        }

        $DOB = $this->student->dob;
        $ageArr = $this->age_calculate(getdate(strtotime($DOB)), getdate(strtotime($this->nssrsSubmissionPeriod)));
        $ageAtSubmissionDate = $ageArr['years'];

        if ($ageAtSubmissionDate == 6) {
            switch ($settingCode) {
                case 16;
                case 17;
                case 18;
                case 19;
                    $settingCode = 10;
            }
        }
      //   $this->writevar1($settingCode,'this is the setting code');
        return $settingCode;
    }

    function buildSpecialEdPercentage()
    {
        if (!is_null($this->getForm('004')) && false !== $this->getForm('004')) {
            $iep = $this->getForm('004');
            return $this->spEdPercentage($iep['special_ed_non_peer_percent']);
        }
        return 0;
    }

    function spEdPercentage($special_ed_non_peer_percent)
    {
        if ('' == $special_ed_non_peer_percent) {
            return 0;
        } else {
            return round($special_ed_non_peer_percent, 0);
        }
    }

    function getCurrentMdtOrCard($status = 'final')
    {
        $mdt = $this->getForm('002', $status);
        if (!is_null($mdt) && false !== $mdt) {
            return $mdt;
        }
        $mdtCard = $this->getForm('022', $status);
        if (!is_null($mdtCard) && false !== $mdtCard) {
            return $mdtCard;
        }

        return null;
    }

    function getCurrentIepIfspOrCard($status = 'final')
    {
        $iep = $this->getForm('004', $status);
        if (!is_null($iep) && false !== $iep) {
            return $iep;
        }
        $ifsp = $this->getForm('013', $status);
        if (!is_null($ifsp) && false !== $ifsp) {
            return $ifsp;
        }
        $iepCard = $this->getForm('023', $status);
        if (!is_null($iepCard) && false !== $iepCard) {
            return $iepCard;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @param mixed $valid
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
    }

    public function buildCommaSeparated()
    {
        $commaText = '';
        for($x = 1; $x <= 52; $x++) {
            $elementName = 'field' . substr('0'.$x, -2, 2);
            if(strlen($commaText) > 0) {
                $commaText .= ',';
            }
            if($element = $this->form->getElement($elementName)) {
                $commaText .= $element->getValue();
            }
        }
        return $commaText;
    }


}
