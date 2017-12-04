<?php


class App_Student_SesisItem
{
    var $data;
    var $name;
    var $type;
    var $length;
    var $validation;
    var $note;

    var $formInput;
    var $JSmodifiedCode;

    function __construct($data, $name, $type, $length, $validation, $note = '') {
        $this->data = $data;
        $this->name = $name;
        $this->type = $type;
        $this->length = $length;
        $this->validation = $validation;
        $this->note = $note;
//        Zend_Debug::dump($name, $type);
//        Zend_Debug::dump($data, 'data');

        $this->validate_type();
        $this->validate_length();

//        $this->formInput = new form_element('function', 'return');
        $this->JSmodifiedCode = "onFocus=\"javascript:modified('', '', 'nssrs', '', '', '');\"";

//        require_once("iep_class_student.inc");
        $this->tempStdObj = new Model_Table_StudentTable();
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
    
    function html_row_displayOutfacing($key, &$output, $arrValidationResults, $arrSesisValidation, $additionalData)
    {
        //var_dump($additionalData);

        $output .= "<TR>";

        if("PASS" == $arrValidationResults[$key]['resolution'])
        {
            $output .= "<TD class=\"bgLight2\" style=\"text-align:center;font-size:12px;\">";
            $output .= "<img src=\"images/circle_green.gif\" height=\"10\" width=\"10\">";
            $output .= "</TD>";
        } else {
            $output .= "<TD class=\"bgLight2\" style=\"text-align:center;font-size:12px;\">";
            $output .= "<img src=\"images/circle_red.gif\" height=\"10\" width=\"10\">";
            $output .= "</TD>";
        }

        $output .= "<TD class=\"bgLight2\" height=\"25px\" style=\"font-size:12px;\">";
        $output .= "Field " . intval($key).": {$this->name}";
        $output .= "</TD>";


        $output .= "<TD class=\"bgLight\" style=\"font-size:12px;\">";
        if('005' == $key || '034' == $key) {
//            $output .= $this->formInput->form_input_text($key, $this->data, true, ' size="10" '. $this->JSmodifiedCode);



        } elseif('032' == $key) {

            $output .= $this->data;

            if(1 == $this->data) {
                $output .= " (No)";
            } elseif(0 == $this->data) {
                $output .= " (Yes)";
            }


        } elseif('044' == $key) {

            switch($this->data) {
                case '1':
                    $AddDisplay = "Home";
                    break;
                case '2':
                    $AddDisplay = "Community Setting";
                    break;
                case '3':
                    $AddDisplay = "Other Setting";
                    break;
                case '4':
                    $AddDisplay = "Regular Early Childhood Program";
                    break;
                case '5':
                    $AddDisplay = "Separate School";
                    break;
                case '6':
                    $AddDisplay = "Separate Class";
                    break;
                case '7':
                    $AddDisplay = "Residential Facility";
                    break;
                case '8':
                    $AddDisplay = "Home";
                    break;
                case '9':
                    $AddDisplay = "Service Provider Location";
                    break;
                case '10':
                    $AddDisplay = "Public School";
                    break;
                case '11':
                    $AddDisplay = "Separate School"; // no longer used
                    break;
                case '12':
                    $AddDisplay = "Residential Facility";
                    break;
                case '14':
                    $AddDisplay = "Private School";
                    break;
                case '15':
                    $AddDisplay = "Correction/Detention Facility";
                    break;
                case '16':
                    $AddDisplay = "Regular Early Childhood Program, 10+ h/wk; Services at EC Program";
                    break;
                case '17':
                    $AddDisplay = "Regular Early Childhood Program, 10+ h/wk; Services outside EC Program";
                    break;
                case '18':
                    $AddDisplay = "Regular Early Childhood Program, <10 h/wk; Services at EC Program";
                    break;
                case '19':
                    $AddDisplay = "Regular Early Childhood Program, <10 h/wk; Services outside EC Program";
                    break;
                default:
                    $AddDisplay = "Incomplete";
                    break;
            }
            $output .= $AddDisplay . " (code: " . $this->data . ")";


            //} elseif('048' == $key) {

            //$output .= $this->tempStdObj->valueListArray("yn12", $key, $this->data, "", "Choose...", "onChange=\"javascript:modified('$DOC_ROOT', '$area', '$sub', '$keyName', '$pkey', '$page');\"") . "(" . $this->data . ")";

        } elseif('048' == $key) {

            if(2 == $this->data) {
                $output .= "No (2)";
            } elseif(1 == $this->data) {
                $output .= "Yes (1)";
            } else {
                $output .= $this->data;
            }
            //$output .= $this->tempStdObj->valueListArray("yn12", $key, $this->data, "", "Choose...", "onChange=\"javascript:modified('$DOC_ROOT', '$area', '$sub', '$keyName', '$pkey', '$page');\"") . "(" . $this->data . ")";

        } elseif('051' == $key) {

            //$output .= $additionalData['studentData']['parental_placement'];
            //$output .= $additionalData['studentData']['pub_school_student'];
            if('f' == $additionalData['studentData']['pub_school_student']) {
                $output .= $this->tempStdObj->valueListArray("yn10", $key, $this->data, "", "Choose...", "onChange=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"") . "(" . $this->data . ")";
            } else {
                $output .= $this->data;
            }

        } elseif('052' == $key) {

            $devDelayCategory = sesis_snapshot::devDelay(date_massage($additionalData['studentData']['dob']), $additionalData['nssrsSubmissionPeriod']);

            switch($devDelayCategory) {
                case 0:
                    $locationValueListName = "sesisExitCodesBirthToTwo";
                    break;
                case 1:
                case 2:
                    $locationValueListName = "sesisExitCodesThreeTo21";
                    break;
                case 3:
                    $locationValueListName = "sesisExitCodesOver21";
                    break;
            }

            $output .= valueListArray("$locationValueListName", $key, $this->data, "", "Choose Exit Category if Applicable", "onChange=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"") . "(" . $this->data . ")";

            //$output .= $this->tempStdObj->valueListArray("yn10", $key, $this->data, "", "Choose...", "onChange=\"javascript:modified('$DOC_ROOT', '$area', '$sub', '$keyName', '$pkey', '$page');\"") . "(" . $this->data . ")";
        } else {
            $output .= $this->data;
            if(isset($additionalData[$key])) {
                $output .= " (" . $additionalData[$key] . ")";
            }

        }
        $output .= "</TD>";

        $output .= "</TR>";

    }

    function html_row_displayOutfacing_transfer($key, &$output, $arrValidationResults, $arrSesisValidation, $additionalData, $import)
    {
        //var_dump($additionalData);
        $studentID = $additionalData['studentData']['id_student'];
        $output .= "<TR>";

        if("PASS" == $arrValidationResults[$key]['resolution'])
        {
            $output .= "<TD class=\"bgLight2\" style=\"text-align:center;font-size:12px;\">";
            $output .= "<img src=\"images/circle_green.gif\" height=\"10\" width=\"10\">";
            $output .= "</TD>";
        } else {
            $output .= "<TD class=\"bgLight2\" style=\"text-align:center;font-size:12px;\">";
            $output .= "<img src=\"images/circle_red.gif\" height=\"10\" width=\"10\">";
            $output .= "</TD>";
        }

        $output .= "<TD class=\"bgLight2\" height=\"25px\" style=\"font-size:12px;\">";
        $output .= "Field " . intval($key).": {$this->name}";
        $output .= "</TD>";


        $output .= "<TD class=\"bgLight\" style=\"font-size:12px;\">";

        if('001' == $key) {

            $output .= $this->data;
            $output .= " (";
            $output .= getCountyName(substr($this->data, 0,2));
            $output .= " - ";
            $output .= getDistrictName(substr($this->data, 0,2), substr($this->data, 3,4));
            $output .= ")";

            $output .= $selectHtml;

        } elseif('002' == $key && strlen($import['001']->data) == 7) {
            //pre_print_r($import);
            //echo "date: " .$import['001']->data . "<BR>";die();
            $schoolSQL  = "SELECT id_school, name_school FROM iep_school WHERE id_county='".substr($import['001']->data, 0, 2)."' and  id_district='".substr($import['001']->data, 3, 4)."' ORDER BY name_school ASC;";
            //echo "schoolSQL: $schoolSQL<BR>";
            Zend_Debug::dump('converto to zf html_row_displayOutfacing_transfer');die;
            if (!$schoolList = sqlExec($schoolSQL, $errorId, $errorMsg, true, false)) {
                $errorId = $errorResult;
                $errorMsg = "Couldn't build school value list.";
                include_once("error.php");
                exit;
            } else {
                $selectHtml  = "<SELECT name=\"$key\" onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\">";
                $selectHtml .= "    <OPTION VALUE=\"\">Choose School";
                for($h = 0; $h < pg_numrows($schoolList); $h++) {
                    $schoolRow = pg_fetch_array($schoolList, ($h));
                    ($this->data == $schoolRow['id_school'])?$selected="selected":$selected='';
                    $selectHtml .= "<OPTION VALUE=\"".$schoolRow['id_school']."\" $selected>".$schoolRow['name_school']."\n";
                }
                $selectHtml .= "</SELECT>";
            }


            $output .= $selectHtml;


        } elseif('005' == $key) {
//            $output .= $this->formInput->form_input_text($key, $this->data, true, ' size="10" '. $this->JSmodifiedCode);
            if(strlen($this->data) == 10 && '' == $studentID)
            {
                if($studentData = $this->get_student_by_unique_id_state($this->data))
                {
                    $output .= " <input type=\"checkbox\" name=\"importstudentdata\" value=\"1\" onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\">";
                    $output .= " Import Student Data from " . $studentData['name_first'] . ' ' . $studentData['name_last'];
                } else {
                    $output .= " No student found in system with nssrs id: " . $this->data;
                }
            } else {
                $output .= " Must be unique 10 digit number.";
            }
        } elseif( '011' == $key) {

            $pd['1'] = "Behavioral Disorder";
            $pd['2'] = "Deaf-Blindness";
            $pd['3'] = "Hearing Impairments";
            $pd['4'] = "Mental Handicap";
            $pd['7'] = "Multiple Impairments";
            $pd['8'] = "Orthopedic Impairments";
            $pd['9'] = "Other Health Impairments";
            $pd['10'] = "Specific Learning Disabilities";
            $pd['11'] = "Speech-Language Impairments";
            $pd['12'] = "Visual Impairments";
            $pd['13'] = "Autism";
            $pd['14'] = "Traumatic Brain Injury";
            $pd['15'] = "Developmental Delay";
            $pd['13'] = "AU";
            $pd['01'] = "BD";
            $pd['02'] = "DB";
            $pd['15'] = "DD";
            $pd['03'] = "HI";
            $pd['11'] = "SLI";
            $pd['16'] = "MH";
            $pd['07'] = "MULTI";
            $pd['08'] = "OI";
            $pd['09'] = "OHI";
            $pd['10'] = "SLD";
            $pd['14'] = "TBI";
            $pd['12'] = "VI";
            $pd['3'] = "HI";


            $selectHtml  = "<SELECT name=\"011\" onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\">";
            $selectHtml .= "<OPTION VALUE=\"\">Choose Disability";

            foreach($pd as $pdkey => $pdvalue) {

                ($this->data == $pdkey)?$selected="selected":$selected='';
                $selectHtml .= "<OPTION VALUE=\"".$pdkey."\" $selected>".$pdvalue."\n";
            }

            $selectHtml .= "</SELECT> ";
            $output .= $selectHtml;

            $output .= $this->data;
            if(isset($additionalData[$key])) {
                $output .= " (" . $additionalData[$key] . ")";
            }


        } elseif('016' == $key) {

            // Code Description
            // 1 Occupational Therapy
            // 2 Physical Therapy
            // 3 Speech-Language Therapy
            // 4 Occupational Therapy - Physical Therapy
            // 5 Physical Therapy - Speech-Language Therapy
            // 6 Speech-Language Therapy - Occupational Therapy
            // 7 All
            // 8 None

            if('1' == $this->data || '4' == $this->data || '6' == $this->data || '7' == $this->data)
            {
                $inputHtml  = "<input type=\"checkbox\" name=\"016_OT\" value=\"1\" checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> OT ";
            } else {
                $inputHtml  = "<input type=\"checkbox\" name=\"016_OT\" value=\"1\" onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> OT ";
            }

            if('2' == $this->data || '4' == $this->data || '5' == $this->data || '7' == $this->data)
            {
                $inputHtml  .= "<input type=\"checkbox\" name=\"016_PT\" value=\"1\" checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> PT ";
            } else {
                $inputHtml  .= "<input type=\"checkbox\" name=\"016_PT\" value=\"1\" onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> PT ";
            }

            if('3' == $this->data || '5' == $this->data || '6' == $this->data || '7' == $this->data)
            {
                $inputHtml  .= "<input type=\"checkbox\" name=\"016_Speech\" value=\"1\" checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> Speech ";
            } else {
                $inputHtml  .= "<input type=\"checkbox\" name=\"016_Speech\" value=\"1\" onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> Speech ";
            }

            if('8' == $this->data)
            {
                $inputHtml  .= "<input type=\"checkbox\" name=\"016_None\" value=\"1\" checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> None ";
            } else {
                $inputHtml  .= "<input type=\"checkbox\" name=\"016_None\" value=\"1\" onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> None ";
            }

            $output .= $inputHtml;


        } elseif('023' == $key) {

            //vecho($this->data);
            ($this->data == 1)?$checked="checked":$checked='';
            $inputHtml  .= "<input type=\"radio\" name=\"$key\" value=\"1\" $checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> Yes";

            ($this->data == 2)?$checked="checked":$checked='';
            $inputHtml  .= "<input type=\"radio\" name=\"$key\" value=\"2\" $checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> No";

            $output .= $inputHtml;

        } elseif('032' == $key) {


            ($this->data == 0)?$checked="checked":$checked='';
            $inputHtml  .= "<input type=\"radio\" name=\"$key\" value=\"0\" $checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> Public";

            ($this->data == 1)?$checked="checked":$checked='';
            $inputHtml  .= "<input type=\"radio\" name=\"$key\" value=\"1\" $checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> Non-Public";

            $output .= $inputHtml;
//             $output .= $this->data;
//
//             if(1 == $this->data) {
//                 $output .= " (No)";
//             } elseif(0 == $this->data) {
//                 $output .= " (Yes)";
//             }

        } elseif('033' == $key) {
      //   $output .= $this->formInput->form_input_text($key, date('Y-m-d', strtotime($this->data)), true, ' size="10" '. $this->JSmodifiedCode) . " Initial Verification Date (YYYY-MM-DD)";

        } elseif('034' == $key) {
            $output .= $this->formInput->form_input_text($key, date('Y-m-d', strtotime($this->data)), true, ' size="10" '. $this->JSmodifiedCode) . " Exit Date (YYYY-MM-DD)";
       //    $this->writevar1($output,'this is it so far line 409 sesisitem.php');

        } elseif('044' == $key) {

            $arr = array();
            $arr[1] = "Home";
            $arr[2] = "Community Setting";
            $arr[3] = "Other Setting";
            $arr[4] = "Regular Early Childhood Program";
            $arr[5] = "Separate School";
            $arr[6] = "Separate Class";
            $arr[7] = "Residential Facility";
            $arr[8] = "Home";
            $arr[9] = "Service Provider Location";
            $arr[10] = "Public School";
            $arr[11] = "Separate School";
            $arr[12] = "Residential Facility";
            $arr[14] = "Private School";
            $arr[15] = "Correction/Detention Facility";

            $arr[16] = "Regular Early Childhood Program, 10+ h/week; services at EC program";
            $arr[17] = "Regular Early Childhood Program, 10+ h/week; services outside EC program";
            $arr[18] = "Regular Early Childhood Program, <10 h/week; services at EC program";
            $arr[19] = "Regular Early Childhood Program, <10 h/week; services outside EC program";


            $selectHtml  = "<SELECT name=\"$key\" onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\">";
            $selectHtml .= "<OPTION VALUE=\"\">Choose Primary Setting Code";

            foreach($arr as $pdkey => $pdvalue) {

                ($this->data == $pdkey)?$selected="selected":$selected='';
                $selectHtml .= "<OPTION VALUE=\"".$pdkey."\" $selected>".$pdvalue."\n";
            }

            $selectHtml .= "</SELECT> ";

            $output .= $selectHtml;


            switch($this->data) {
                case '1':
                    $AddDisplay = "Home";
                    break;
                case '2':
                    $AddDisplay = "Community Setting";
                    break;
                case '3':
                    $AddDisplay = "Other Setting";
                    break;
                case '4':
                    $AddDisplay = "Regular Early Childhood Program";
                    break;
                case '5':
                    $AddDisplay = "Separate School";
                    break;
                case '6':
                    $AddDisplay = "Separate Class";
                    break;
                case '7':
                    $AddDisplay = "Residential Facility";
                    break;
                case '8':
                    $AddDisplay = "Home";
                    break;
                case '9':
                    $AddDisplay = "Service Provider Location";
                    break;
                case '10':
                    $AddDisplay = "Public School";
                    break;
                case '11':
                    $AddDisplay = "Separate School"; // no longer used
                    break;
                case '12':
                    $AddDisplay = "Residential Facility";
                    break;
                case '14':
                    $AddDisplay = "Private School";
                    break;
                case '15':
                    $AddDisplay = "Correction/Detention Facility";
                    break;
                default:
                    $AddDisplay = "Incomplete";
                    break;
            }
            $output .= $AddDisplay . " (code: " . $this->data . ")";


            //} elseif('048' == $key) {

            //$output .= $this->tempStdObj->valueListArray("yn12", $key, $this->data, "", "Choose...", "onChange=\"javascript:modified('$DOC_ROOT', '$area', '$sub', '$keyName', '$pkey', '$page');\"") . "(" . $this->data . ")";

        } elseif('047' == $key) {

            ($this->data == 'Y')?$checked="checked":$checked='';
            $inputHtml  .= "<input type=\"radio\" name=\"$key\" value=\"Y\" $checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> (Y) Part B";

            ($this->data == 'N')?$checked="checked":$checked='';
            $inputHtml  .= "<input type=\"radio\" name=\"$key\" value=\"N\" $checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> (N) Part C";

            $output .= $inputHtml; //"<BR><B>Y or N (Y = Part B, N = Part C)</B>";


        } elseif('048' == $key) {

            ($this->data == 1)?$checked="checked":$checked='';
            $inputHtml  .= "<input type=\"radio\" name=\"$key\" value=\"1\" $checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> (1) Surrogate Appointed";

            ($this->data == 2)?$checked="checked":$checked='';
            $inputHtml  .= "<input type=\"radio\" name=\"$key\" value=\"2\" $checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> (2) Not Appointed";

            $output .= $inputHtml; // "<BR><B>Must be 1 or 2 (1 = Surrogate Appointed, 2 = not)</B>";


//             if(2 == $this->data) {
//                 $output .= "No (2)";
//             } elseif(1 == $this->data) {
//                 $output .= "Yes (1)";
//             } else {
//                 $output .= $this->data;
//             }
            //$output .= $this->tempStdObj->valueListArray("yn12", $key, $this->data, "", "Choose...", "onChange=\"javascript:modified('$DOC_ROOT', '$area', '$sub', '$keyName', '$pkey', '$page');\"") . "(" . $this->data . ")";

        } elseif('050' == $key) {
//            $output .= $this->formInput->form_input_text($key, $this->data, true, ' size="3" '. $this->JSmodifiedCode) . " % not with regular education peers. (Whole number only)";

        } elseif('051' == $key) {

            //$output .= $additionalData['studentData']['parental_placement'];
            //$output .= $additionalData['studentData']['pub_school_student'];


            if('f' == $additionalData['studentData']['pub_school_student']) {
                #$output .= $this->tempStdObj->valueListArray("yn10", $key, $this->data, "", "Choose...", "onChange=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"") . "(" . $this->data . ")";
            } else {
                #$output .= $this->tempStdObj->valueListArray("yn10", $key, $this->data, "", "Choose...", "onChange=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"") . "(" . $this->data . ")";
            }
            ($this->data == 1)?$checked="checked":$checked='';
            $inputHtml  .= "<input type=\"radio\" name=\"$key\" value=\"1\" $checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> Yes";

            ('' != $this->data && $this->data == 0)?$checked="checked":$checked='';
            $inputHtml  .= "<input type=\"radio\" name=\"$key\" value=\"0\" $checked onFocus=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"> No";
            $output .= $inputHtml;

        } elseif('052' == $key) {

            $devDelayCategory = sesis_snapshot::devDelay(date_massage($additionalData['studentData']['dob']), $additionalData['nssrsSubmissionPeriod']);

            switch($devDelayCategory) {
                case 0:
                    $locationValueListName = "sesisExitCodesBirthToTwo";
                    break;
                case 1:
                case 2:
                    $locationValueListName = "sesisExitCodesThreeTo21_transfer";
                    break;
                case 3:
                    $locationValueListName = "sesisExitCodesOver21";
                    break;
            }




            $output .= valueListArray("$locationValueListName", $key, $this->data, "", "Choose Exit Category if Applicable", "onChange=\"javascript:modified('$DOC_ROOT', 'reports', 'nssrs', '$keyName', '$pkey', '$page');\"") . "(" . $this->data . ")";

            //$output .= $this->tempStdObj->valueListArray("yn10", $key, $this->data, "", "Choose...", "onChange=\"javascript:modified('$DOC_ROOT', '$area', '$sub', '$keyName', '$pkey', '$page');\"") . "(" . $this->data . ")";
        } else {
            $output .= $this->data;
            if(isset($additionalData[$key])) {
                $output .= " (" . $additionalData[$key] . ")";
            }

        }
        $output .= "</TD>";

        $output .= "</TR>";
        //$this->writevar1($output,'this is the out put line 589');

    }


    function html_row_display($key, &$output, $arrValidationResults, $arrSesisValidation)
    {
        //pre_print_r($arrSesisValidation[$key]);

        $output .= "<TR>";


        $output .= "<TD class=\"bgLight2\" style=\"font-size:12px;\">";
        $output .= $key;
        $output .= "</TD>";

        $output .= "<TD class=\"bgLight2\" style=\"font-size:12px;\">";
        $output .= $this->name;
        $output .= "</TD>";

        $output .= "<TD style=\"font-size:12px;\">";
        $output .= $this->data;
        $output .= "</TD>";

        if($this->validate_type(false))
        {
            $error = "";
        } else {
            $error = "background-color: salmon;";
        }
        $output .= "<TD style=\"font-size:12px;$error\">";
        $output .= $this->type;
        $output .= "</TD>";

        if($this->validate_length(false))
        {
            $error = "";
        } else {
            $error = "background-color: salmon;";
        }
        $output .= "<TD style=\"font-size:12px;$error\">";
        $output .= $this->length;
        $output .= "</TD>";

        if("PASS" == $arrValidationResults[$key]['resolution'])
        {
            $error = "background-color: green;";
        } else {
            $error = "background-color: salmon;";
        }
        $output .= "<TD style=\"font-size:12px;$error\">";
        $output .= $this->validation;
        $output .= "</TD>";

        $output .= "<TD style=\"font-size:12px;\">";
        $output .= $arrSesisValidation[$key][1];
        $output .= "</TD>";

        $output .= "<TD style=\"font-size:12px;\">";
        $output .= $this->note;
        $output .= "</TD>";

        if($this->errorArr != '')
        {
            $output .= "<TD style=\"font-size:12px;\">";
            $output .= "<pre>";
            foreach($this->errorArr as $error)
            {
                $output .= $error . "<BR>";
            }
            $output .= "</pre>";
            $output .= "</TD>";
        } else {
            $output .= "<TD style=\"font-size:12px;\">";
            $output .= "&nbsp;";
            $output .= "</TD>";
        }

        $output .= "</TR>";

    }
    function validate_type($addError = true)
    {
        // type check
        switch($this->type)
        {
            case "Int":
                if("" == $this->data) return true;
                if(!is_numeric($this->data) || substr_count($this->data, '.'))
                {
                    if($addError) $this->add_error("Value ({$this->data}) is of the wrong type (should be {$this->type}).");
                    return false;
                }
                break;
            case "Varchar":
                if("" == $this->data) return true;
                if(!is_string($this->data))
                {
                    if($addError) $this->add_error("Value ({$this->data}) is of the wrong type (should be {$this->type}).");
                    return false;
                }
                break;
            case "Float":
                if("" == $this->data) return true;
                if(!is_float($this->data) && !is_numeric($this->data) )
                {
                    if($addError) $this->add_error("Value ({$this->data}) is of the wrong type (should be {$this->type}).");
                    return false;
                }
                break;
            case "Datetime":
                if(null == $this->data) return true;
                if("" == $this->data) return true;
                if(substr_count($this->data, '/') > 0) {
                    list($mm,$dd,$yyyy)=explode("/",$this->data);
                } else {
                    list($mm,$dd,$yyyy)=explode("-",$this->data);
                }
                if (is_numeric($yyyy) && is_numeric($mm) && is_numeric($dd))
                {
                    if(!checkdate($mm,$dd,$yyyy))
                    {
                        if($addError) $this->add_error("Value ({$this->data}) is of the wrong type (should be {$this->type}).");
                        return false;
                    }
                } else {
                    if($addError) $this->add_error("Value ({$this->data}) is of the wrong type (should be {$this->type}).");
                    return false;
                }
                break;
            default:
                if($addError) $this->add_error("Type validator not found ({$this->type}).");
                return false;
        }
        return true;
    }

    function validate_length($addError = true)
    {
        // length check
        if(strlen($this->data) > intval($this->length))
        {
            if($addError) $this->add_error("Value ({$this->data}) over the length limit({$this->length}).");
            return false;
        }
        return true;
    }

    function add_error($msg)
    {
        if(!isset($this->errorArr))
        {
            $this->errorArr = array();
        }
        $this->errorArr[] = $msg;
    }


    function get_student_by_unique_id_state($state_id) {
        $sqlStmt  = "SELECT *, CASE WHEN name_middle IS NOT NULL THEN name_first || ' ' || name_middle || ' ' || name_last ELSE name_first || ' ' || name_last END AS name_student_full  \n";
        $sqlStmt .= "FROM iep_student \n";
        $sqlStmt .= "WHERE unique_id_state = '$state_id';";
        //echo "sqlStmt: $sqlStmt<BR>";die();
        Zend_Debug::dump('converto to zf get_student_by_unique_id_state');die;
        if($result = sqlExec($sqlStmt, $errorId, $errorMsg, true, true)) {
            $data = pg_fetch_array($result, 0, PGSQL_ASSOC);
            return $data;
        } else {
            return false;
        }
    }


}


class sesis_validation_2007 {


    var $sesisData;
    var $sesisValidation;
    var $arrValidationResults;



    function __construct($sesisData, $sesisValidation) {
        global $sessIdUser;
        #
        # STORE SESIS DATA
        #

        $this->arrValidationResults = array();
        $this->sesisData = $sesisData;
        //$this->build_report_data($sesisData);
        #
        # LOAD VALIDATION RULES FROM USER EDITABLE FILE
        # BUILDS THE sesisValidation VARIABLE IN THIS OBJECT
        #
        //include('definitions_validation/sesis_validation.php');
        #if(1000254 == $sessIdUser) print_r($this->sesisValidation);

        #
        #
        #
        include_once('class_evalvalidate.php');
        $evalObj = new evalidate();
        $evalObj->validate($sesisData, $sesisValidation, $this->arrValidationResults);

        //if(1000254 == $sessIdUser) pre_print_r($evalObj);
    }


    function __constructOld($sesisData) {
        global $sessIdUser;
        #
        # STORE SESIS DATA
        #

        $this->arrValidationResults = array();
        $this->sesisData = $sesisData;
        $this->build_report_data($sesisData);
        #
        # LOAD VALIDATION RULES FROM USER EDITABLE FILE
        # BUILDS THE sesisValidation VARIABLE IN THIS OBJECT
        #
        include('definitions_validation/sesis_validation.php');
        #if(1000254 == $sessIdUser) print_r($this->sesisValidation);

        #
        #
        #
        include_once('class_evalvalidate.php');
        $evalObj = new evalidate();
        $evalObj->validate($this->sesisReportData, $this->sesisValidation, $this->arrValidationResults);

        //if(1000254 == $sessIdUser) pre_print_r($evalObj);
    }


    function build_grade($sesisGradeCode)
    {
        // echo "sesisGradeCode: $sesisGradeCode<BR>";
        switch($sesisGradeCode) {
            case 1:
                return "No Grade Value";
            case 2:
                return "Pre-Kindergarten";
            case 3:
                return "Kindergarten";
            case 4:
                return "Grade-1";
            case 5:
                return "Grade-2";
            case 6:
                return "Grade-3";
            case 7:
                return "Grade-4";
            case 8:
                return "Grade-5";
            case 9:
                return "Grade-6";
            case 10:
                return "Grade-7";
            case 11:
                return "Grade-8";
            case 12:
                return "Grade-9";
            case 13:
                return "Grade-10";
            case 14:
                return "Grade-11";
            case 15:
                return "Grade-12";
            case 16:
                return "Grade-12+";
            default:
                return "Unknown";
        }
    }

    function surrogate_other($sesisData) { // 1-9b
        global $sessIdUser;
        #if($sessIdUser == 1000254) pre_print_r($sesisData);
        $othertxt = $sesisData['018'];
        if($sesisData['017'] == 0 && $sesisData['016'] == 1) {
            $othertxt = "Parents Involved";
        }

        return $othertxt;
    }

    function date_conf($confDate) { // 1-16
        if($confDate == '') return "None Entered";
    }

    function iep_percentages($sesisData, $dataType = '') { // 1-16
        global $sessIdUser;
        if($sesisData['forms_data']['mostRecentIEP'] == "-1") return "Not required for IFSP";
        switch($dataType) {
            case 'peer':
                //if(1000254 == $sessIdUser) pre_print_r($sesisData);
                #if(1000254 == $sessIdUser) echo $sesisData['052'] . "<BR>";
                if($sesisData['052'] == '10000') {
                    return "100%";
                } else {
                    return ($sesisData['052'] / 100)."%";
                }
            case 'nonpeer':
                #if(1000254 == $sessIdUser) echo $sesisData['053'] . "<BR>";
                if($sesisData['053'] == '10000') {
                    return "100%";
                } else {
                    return ($sesisData['053'] / 100)."%";
                }
            case 'reg_ed':
                #if(1000254 == $sessIdUser) echo $sesisData['054'] . "<BR>";
                if($sesisData['054'] == '10000') {
                    return "100%";
                } else {
                    return ($sesisData['054'] / 100)."%";
                }
        }
        return $serviceText;
    }
    function get_multi_dis($sesisData) { // 1-12a
        $serviceText = '';
        if($sesisData['019']) $serviceText .= "Hearing Impairment<BR>";
        if($sesisData['020']) $serviceText .= "Visual Impairment<BR>";
        if('' == $serviceText) $serviceText = "None of the above";
        return $serviceText;
    }
    function get_secondary_dis($sesisData) { // 1-12b
        $serviceText = '';
        if($sesisData['022'] == 2) $serviceText .= "Hard of Hearing (Mild/Moderate)<BR>";
        if($sesisData['022'] == 1) $serviceText .= "Deaf (severe profound)<BR>";
        if($sesisData['023'] == 3) $serviceText .= "Partially Sighted<BR>";
        if($sesisData['023'] == 2) $serviceText .= "Legally Blind<BR>";
        if($sesisData['023'] == 1) $serviceText .= "Blind<BR>";
        return $serviceText;
    }
    function get_services($sesisData) { //1-15
        $serviceText = '';
        for($i=31; $i<=49; $i++) {
            $xCode = '0'.$i;
            if($sesisData[$xCode]) {
                $serviceText .= $this->build_services($i) . "<BR>";
            }
        }
        if($sesisData['050'] == 1) $serviceText .= "Other: " . $sesisData['051'] . "<BR>";
        if($serviceText == "") return 'No Finalized IFSP/IEP on FILE';
        return $serviceText;
    }
    function get_build_speechLngThrpy($code) { //1-15
        //
        // If OT (code 1) only then say "Occupational Therapy"
        // If PT (2) only then "Physical Therapy"
        // If SLP (3) only then "Speech Language Pathology"
        // If OT AND PT (4) then "OT and PT"
        // if PT AND SLP (5) then "PT and SLP"
        // if SLP AND OT (6) then "SLP and OT"
        // if SLP AND OT AND PT (7) then "SLP,OT,PT"
        // if NONE (8) then "No qualifying Services (PT,OT,SLP) found"

        If($code == 1) return "Occupational Therapy";
        If($code == 2) return "Physical Therapy";
        If($code == 3) return "Speech Language Pathology";
        If($code == 4) return "OT and PT";
        If($code == 5) return "PT and SLP";
        If($code == 6) return "SLP and OT";
        If($code == 7) return "SLP,OT,PT";
        If($code == 8) return "No qualifying Services (PT,OT,SLP) found";

        return -1;
    }
    function build_services($serviceCode) {
        switch($serviceCode) {
            case '031': return "Assistive technology services/devices";
            case '032': return "Audiological Services";
            case '033': return "Extended School Year";
            case '034': return "Family training, counseling, home visits and other supports";
            case '035': return "Sign Language Interpreter";
            case '036': return "Medical sercices (for diagnostic or evaluation purposes)";
            case '037': return "Nursing services";
            case '038': return "Nutrition services";
            case '039': return "Occupational Therapy Services";
            case '040': return "Physical Therapy";
            case '041': return "Psychological services";
            case '042': return "Respite care";
            case '043': return "Health services";
            case '044': return "Social work services";
            case '045': return "Special Instruction (Resource)";
            case '046': return "Speech-language therapy";
            case '047': return "Services coordination";
            case '048': return "Transportation";
            case '049': return "Vision Services";
            case '050': return "Deaf (Severe Profound)";
            case '051': return "Vision Services";
        }
    }
    function get_setting($sesisData) { // 1-14
        //pre_print_r($sesisData);


        if($sesisData['mostRecent013'] == -1) return 'No Finalized IFSP/IEP on FILE';

        $setting = $sesisData['030'];
        #echo "setting: $setting<BR>";
        switch($setting) {

            case 'Child Care Center':
                $setting = '4';
                break;
            case 'Family Child Care Home':
                $setting = '10';
                break;
            case 'Head Start':
                $setting = '8';
                break;
            case 'Home':
                $setting = '10';
                break;
            case 'Hospital':
                $setting = '9';
                break;
            case 'Other':
                $setting = '21';
                break;
            case 'Part-Time Early Childhood':
                $setting = '4';
                break;
            case 'Part-Time Early Childhood Special Education Setting':
                $setting = '4';
                break;
            case 'Residential Facility':
                $setting = '22';
                break;
            case 'Separate classroom for children with disabilities':
                $setting = '4';
                break;
            case 'Service Provider Location':
                $setting = '19';
                break;

        }
        #
        # WE USE PADDED INTEGERS FOR SOME CODES, THEY'RE STRIPPED TO BE PASSED TO SESIS
        # HERE WE ARE PADDING THE ZERO BACK
        #
        if(strlen($setting) == 1 && is_numeric($setting)) $setting = '0' . $setting;

        include_once('iep_function_value_list.inc');
        #
        # GET NEEDED VALUE LISTS
        #
        $values=array();
        $keys=array();
        getLabelValues('serviceLocationBirthToTwo', $values, $keys);

        if(count($keys) > 0) {
            $locArr1 = array_combine($keys, $values);
        } else {
            $locArr1 = array();
        }

        $values=array();
        $keys=array();
        getLabelValues('serviceLocationThreeToFive', $values, $keys);
        if(count($keys) > 0) {
            $locArr2 = array_combine($keys, $values);
        } else {
            $locArr2 = array();
        }

        $values=array();
        $keys=array();
        getLabelValues('serviceLocationSixTo21', $values, $keys);
        if(count($keys) > 0) {
            $locArr3 = array_combine($keys, $values);
        } else {
            $locArr3 = array();
        }



        $devDelayCategory = sesis_snapshot::devDelay($sesisData['008'], $sesisData['057']);
        global $sessIdUser;
        //if(1000254 == $sessIdUser) echo "devDelayCategory: $devDelayCategory\n";
        switch($devDelayCategory) {
            case 0:
                $locationValueListName = "serviceLocationBirthToTwo";
                break;
            case 1:
                $locationValueListName = "serviceLocationThreeToFive";
                break;
            default:
                $locationValueListName = "serviceLocationSixTo21";
        }
        $arrLabel = array();
        $arrValue = array();
        getLabelValues($locationValueListName, $arrLabel, $arrValue);

        if(false === array_search($setting, $arrValue)) {
            return "Setting not legitimate for age of child.";
        }


        #
        # ADD THE ARRAYS
        #
        $locArr = $locArr1 + $locArr2 + $locArr3;

        return $locArr[$setting];
    }
    function get_programProvider($sesisData) {
        // check for bad data and fail if so.
//         $school = substr($sesisData['028'], -3, 3);
//         if($sesisData['027'] == 2 && ('000' == $school || empty($sesisData['028']))) {
//             return "Incomplete: Please select all required fields on Edit Student.";
//         }
//
//         if($sesisData['027'] == 3) {
//             if(!preg_match('/[0-9]{2}-[0-9]{4}-[0-9]{3}/',$sesisData['029'], $matches)) {
//                 return "Incomplete: Please select all required fields on Edit Student.";
//             }
//             if(strlen($sesisData['028']) == 0 || strlen($sesisData['028']) > 50) {
//                 return "Incomplete: Please select all required fields on Edit Student.";
//             }
//         }
        switch($sesisData['024']) {
            case '': return 'Field Empty on Edit Student Page';
            case 1: return 'Resident school district';
            case 2: return 'Another school district: (' . $sesisData['025'] . ")";
            case 3: return 'Other provider: (' . $sesisData['025'] . ")";
            default: return '';
        }
    }
    function get_primaryDisability($dis, $MDT) {
        global $sessIdUser;
//		include_once('iep_function_value_list.inc');
//		getLabelValues('disabilities', $arrLabel=array(), $arrValue=array());
//		$disArr = array_combine($arrValue, $arrLabel);
//		return $dis;
        #global $sessIdUser;
        #if(1000254 == $sessIdUser) echo "dis: $dis<BR>";

        if('' == $dis && -1 == $MDT) return 'No Finalized MDT on FILE';
        if('' == $dis && 'A' == $MDT['mdt_00603e2a']) return 'Did not Qualify';

        //if(1000254 == $sessIdUser) pre_print_r($MDT);

        switch($dis) {
            case '1': return "Behavioral Disorder";
            case '2': return "Deaf-Blindness";
            case '3': return "Hearing Impairments";
            case '4': return "Mental Handicap";
            case '7': return "Multiple Impairments";
            case '8': return "Orthopedic Impairments";
            case '9': return "Other Health Impairments";
            case '10': return "Specific Learning Disabilities";
            case '11': return "Speech-Language Impairments";
            case '12': return "Visual Impairments";
            case '13': return "Autism";
            case '14': return "Traumatic Brain Injury";
            case '15': return "Developmental Delay";

            case '': return 'No Finalized MDT on FILE';
            case "13": return "AU";
            case "01": return "BD";
            case "02": return "DB";
            case "15": return "DD";
            case "03": return "HI";
            case "11": return "SLI";
//				case "MH:MI":
//				case "MHMI":
//				case "MH:MO":
//				case "MHMO":
//				case "MH:S/P":
//				case "MHSP":
            case "16": return "MH";
            case "07": return "MULTI";
            case "08": return "OI";
            case "09": return "OHI";
            case "10": return "SLD";
            case "14": return "TBI";
            case "12": return "VI";
            case "3": return "HI";
            default: return "unknown";
        }
    }
    function get_initial_ver($datavar) { // 1-1
        $datavar = intval($datavar);
        //echo "datavar: $datavar<BR>";
        if(0 == $datavar) {
            return 'No';
        } elseif(1 == $datavar) {
            return 'Yes';
        } else {
            return 'Unknown';
        }
    }
    function get_ward($ward) {
        switch($ward) {
            case 0: return 'No';
            case 1: return 'Yes';
            default: return '';
        }
    }
    function get_nonPublic($nonPublic) {
        switch($nonPublic) {
            case 0: return 'No';
            case 1: return 'Yes';
            case -1: return 'Does Not Apply';
            default: return '';
        }
    }
    function yesno($val) {
        if(0 === $val) return "No";
        if(1 === $val) return "Yes";

        return "";

        switch($val) {
            case 0: return 'No';
            case 1: return 'Yes';
            default: return '';
        }
    }
    function get_gender($gender) {
        switch($gender) {
            case 0: return 'Male';
            case 1: return 'Female';
            default: return '';
        }
    }
    function get_surrogate($sur) { // 1-9a
        switch($sur) {
            case 0: return 'No';
            case 1: return 'Yes';
            default: return '';
        }
    }
    function get_ethnicity($ethnicity) {
        switch($ethnicity) {
            case 1: return 'Native Am';
            case 2: return 'Asian';
            case 3: return 'White';
            case 4: return 'Black';
            case 5: return 'Hispanic';
            default: return '';
        }
    }
    function check_all_pass($debug=false) {
        foreach($this->arrValidationResults as $key => $result) {
            //if($debug) pre_print_r($result);
            if($result['resolution'] != 'PASS') {
                if($debug) echo "resolution: {$result['resolution']}<BR>";
                if($debug) echo "key: {$key}<BR>";
                if($debug) pre_print_r($result);
                return false;
            }
        }
        return true;
    }
    function check_pass($fieldName) {
        if($this->arrValidationResults[$fieldName]['resolution'] == "PASS") return true;
        return false;
    }
    function display_class($fieldName, $passClass, $failClass) {
        if($this->arrValidationResults[$fieldName]['resolution'] == "PASS") return $passClass;
        return $failClass;
    }
    function sped_to_literal($DOB, $code)
    {
        global $sessIdUser;
        $debug = false;
        if(1000254 == $sessIdUser) $debug = false;

        if($debug) echo "sped_to_literal code: $code<BR>";

        $DOB = date_massage($DOB);
        $today = date("m/d/Y", strtotime("today"));
        $decCutoff = date("m/d/Y", strtotime("12/1/" . date("Y", strtotime("today"))));
        if(date("m", strtotime("today")) >= 7)
        {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today"))));
        } else {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today")) ."-1 year"));
        }

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

        if($debug) echo "cutoff: $cutoff<BR>";
        if($debug) echo "sped_to_literal age: $age<BR>";
        if($debug) echo "code: $code<BR>";


        if($code == 14)
        {
            return "Parental Placement";
        } elseif($age < 3)
        {
            switch ( $code )
            {
                case "1":	return "Home Setting";
                case "2":	return "Community Setting";
                case "3":	return "Other Setting";
            }
        } elseif($age < 6) {
            switch($code)
            {
                case "4":	return "Regular Early Childhood Program";
                case "5":	return "Separate School";
                case "6":	return "Separate Class";
                case "7":	return "Residential Facility";
                case "8":	return "Home";
                case "9":	return "Service Provider Location";
            }
        } else {
            switch($code)
            {
                case "9":	return "Home Hospital";
                case "10":	return "Public School";
                case "11":	return "Separate School";
                case "12":	return "Residential Facility";
                case "13":	return "Home";
                case "15":	return "Correction/Detention Facility";
            }
        }
    }
    function cds_to_literal($cds) // 99-8889-100 ->
    {
        list($id_county,$id_district,$id_school) = explode("-",$cds);
        $countyName = getCountyName($id_county);
        $districtName = getDistrictName($id_county, $id_district);
        $schoolName = getSchoolName($id_county, $id_district, $id_school);

        return "$countyName - $districtName - $schoolName";

    }
    function build_provider($majorProviderType, $majorProviderNumber, $residentCDS)
    {
        switch($majorProviderType)
        {
            case '1':
                $cdsLiteral = $this->cds_to_literal($residentCDS);
                return "Resident School District: $cdsLiteral ($residentCDS)";

            case '2':
                $cdsLiteral = $this->cds_to_literal($majorProviderNumber);
                return "Another School District: $cdsLiteral ($majorProviderNumber)";

            case '3':
                return "Other Provider: ($majorProviderNumber)";
        }
    }

    function build_9b($studentRec)
    {
        return $studentRec['ward_surrogate_nn'];
    }
    function build_9c($studentRec)
    {
        return $studentRec['ward_surrogate_other'];
    }
    function build_report_data($sesisData) {
        #pre_print_r($sesisData);
        global $sessIdUser;
        //if(1000254 == $sessIdUser) pre_print_r($sesisData);
        //if(1000254 == $sessIdUser) echo "get_initial_ver: " . $this->get_initial_ver($sesisData['002']);
        //if(1000254 == $sessIdUser) pre_print_r($sesisData['098']);
        //if(1000254 == $sessIdUser) echo "monkey: " . $sesisData['097'] . "<BR>";

        $this->sesisReportData = array(
            '1-1' => $sesisData['017'],
            '1-2' => $sesisData['002'],
            '1-3' => $sesisData['004'].", ".$sesisData['005']." ".$sesisData['003'],
            '1-4' => $sesisData['006'],
            '1-5' => $sesisData['011'],
            '1-6' => $this->get_gender($sesisData['009']),
            '1-7' => $this->get_ethnicity($sesisData['008']),
            '1-8' => $this->get_nonPublic($sesisData['013']),

            '1-8a' => $this->yesno($sesisData['097']),

            '1-9' => $this->get_ward($sesisData['032']),
            '1-9a' => $this->get_surrogate($sesisData['033']),
//				'1-9b' => $sesisData['036'],
            '1-9b' => $this->yesno($sesisData['034']),
            '1-9c' => $sesisData['036'],
            '1-10' => $this->yesno($sesisData['015']),
            '1-11' => $this->build_grade($sesisData['016']),
            '1-12' => $this->get_primaryDisability($sesisData['018'], $sesisData['098']),
            '1-12a' => $this->get_multi_dis($sesisData),
            '1-12b' => $this->get_secondary_dis($sesisData),
            '1-13' => $this->build_provider($sesisData['024'], $sesisData['025'], $sesisData['011']),
            '1-14' => $this->sped_to_literal($sesisData['006'], $sesisData['027']),
            '1-15' => $this->get_build_speechLngThrpy($sesisData['028']),
            //'1-15' => $this->get_services($sesisData),
            '1-16' => $this->iep_percentages($sesisData),
            '1-16-peer' => $sesisData['029'],
            '1-16-nonpeer' => $sesisData['030'],
            '1-16-reg_ed' => $sesisData['031'],
            '100' => $sesisData['100'],
        );
        //if(1000254 == $sessIdUser) pre_print_r($this);
    }

}

