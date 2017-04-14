<?php
/**
 * Created by PhpStorm.
 * User: jlavere
 * Date: 4/8/15
 * Time: 3:53 PM
 */
namespace App\Report;

class Sesis
{

    function build_sesis_data($arr, $sessIdUser)
    {
        $import = array(); # IMPORT RECORD LAYOUT
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
        $import['001'] = new sesis_item($this->build_CD($arr['id_county'], $arr['id_district'], $arr['id_school']),'RESIDENT COUNTY DISTRICT','Varchar','7','','County District (00-0000)');

        // Field#2 School Number
        // This is the three digit school number.  They want this on a separate line.
        //     -- It will look like thie “001”
        $import['002'] = new sesis_item($arr['id_school'],'RESIDENT SCHOOL','Varchar','3','','School Code');

        // Field#3 School Year Date
        // Every file should have the following date:  2008-06-30
        $import['003'] = new sesis_item($this->nssrsSnapshotDate,'School Year Date','Varchar','10','','School Code');

        // Field#4 Unused – Leave Blank
        $import['004'] = new sesis_item('','','','','');

        // Field#5 NDE Student ID
        // This is the 10 digit NSSRS ID# from the EDIT STUDENT PAGE
        $import['005'] = new sesis_item($arr['unique_id_state'],'NSSRSID','Int','10','','nssrs id');

        // Field#6 Unused – Leave Blank
        $import['006'] = new sesis_item('','','','','');

        // Field#7 Unused – Leave Blank
        $import['007'] = new sesis_item('','','','','');

        // Field#8 Unused – Leave Blank
        $import['008'] = new sesis_item('','','','','');

        // Field#9 Unused – Leave Blank
        $import['009'] = new sesis_item('','','','','');

        // Field#10 Unused – Leave Blank
        $import['010'] = new sesis_item('','','','','');


        // Field#11 Primary Disability
        // This is the primary Disability from page 3 of the most recent MDT form.
        $import['011'] = new sesis_item($this->build_disPrime(),'PRIMARY DISABILITY','Int','4','','Required, must be numeric, see Initial Disability Table below');//,''

        // Field#12 Unused – Leave Blank
        $import['012'] = new sesis_item('','','','','');

        // Field#13 Unused – Leave Blank
        $import['013'] = new sesis_item('','','','','');

        // Field#14 Unused – Leave Blank
        $import['014'] = new sesis_item('','','','','');

        // Field#15 Unused – Leave Blank
        $import['015'] = new sesis_item('','','','','');


        // Field#16 Related Services
        // This section is exactly the same as section 1-15 on the current SESIS report.
        // We will want to look at the most recent IEP (provided it is less than 1 year old).
        // This data is pulled from the primary and related services on page 6 of the IEP.
        // All this report is interested in is the following three services: Speech-Language Therapy - Occupational Therapy.
        // The codes below shall be used for reporting the various combinations of these three services.
        $import['016'] = new sesis_item($this->build_speechLngThrpy(),'Related Services','Int','1','','','');

        // Field#17 Unused – Leave Blank
        $import['017'] = new sesis_item('','','','','');

        // Field#18 Unused – Leave Blank
        $import['018'] = new sesis_item('','','','','');

        // Field#19 Unused – Leave Blank
        $import['019'] = new sesis_item('','','','','');

        // Field#20 Unused – Leave Blank
        $import['020'] = new sesis_item('','','','','');

        // Field#21 Unused – Leave Blank
        $import['021'] = new sesis_item('','','','','');

        // Field#22 Unused – Leave Blank
        $import['022'] = new sesis_item('','','','','');

        // Field#23 Unused – Leave Blank
        $import['023'] = new sesis_item($this->build_alternate_assessment($arr['alternate_assessment']),'Alternate Assessment','Int','1','','Alternate Assessment must be 1 or 2.');

        // Field#24 Unused – Leave Blank
        $import['024'] = new sesis_item('','','','','');

        // Field#25 Unused – Leave Blank
        $import['025'] = new sesis_item('','','','','');

        // Field#26 Unused – Leave Blank
        $import['026'] = new sesis_item('','','','','');

        // Field#27 Unused – Leave Blank
        $import['027'] = new sesis_item('','','','','');

        // Field#28 Unused – Leave Blank
        $import['028'] = new sesis_item('','','','','');

        // Field#29 Unused – Leave Blank
        $import['029'] = new sesis_item('','','','','');

        // Field#30 Unused – Leave Blank
        $import['030'] = new sesis_item('','','','','');

        // Field#31 Unused – Leave Blank
        $import['031'] = new sesis_item('','','','','');

        // Field#32 Placement Type (public vs Non-public)
        // This field wants to know if the student is a Public School Student or not.
        // This data will come from the EDIT STUDENT PAGE in the “Public Student?” section.  If YES then use code “0”(zero) if NO then use “1”.
        $import['032'] = new sesis_item($this->build_pubSchoolStudent($ageYears, $arr['pub_school_student'], $arr['parental_placement']),'Placement Type','Int','4','Required, 0, 1 or -1, 0 = No, 1 = Yes, -1 = No Value If age < 6, value must be -1, else value must be 0 or 1','','also returns -1 if student is under 6 or pub is blank');

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
        $import['033'] = new sesis_item($this->build_entryDate($arr['id_student']),'ENTRY DATE','Datetime','10','','','');
        //echo (strlen($import['033']->data) == 10) . "<BR>";

        // Field#34 Exit Date
        //
        // This is the exit date from the EDIT STUDENT PAGE.
        $import['034'] = new sesis_item(date_massage($arr['sesis_exit_date'], 'Y-m-d'),'EXITDATE','Datetime','10','Empty or a valid date, see Exit table below, if present, exit reason must be present','','Formerly row 55');

        // Field#35 SNAPSHOT DATE
        // I have a feeling that they are going to eliminate this.  But in case they don’t.
        // This field will be just like Field #3.  Just fill each box with:  2008-06-30
        $import['035'] = new sesis_item($this->nssrsSubmissionPeriod,'SNAPSHOT DATE','Varchar','10','','School Code');

        $import['036'] = new sesis_item('','','','','');
        $import['037'] = new sesis_item('','','','','');
        $import['038'] = new sesis_item('','','','','');
        $import['039'] = new sesis_item('','','','','');
        $import['040'] = new sesis_item('','','','','');
        $import['041'] = new sesis_item('','','','','');
        $import['042'] = new sesis_item('','','','','');
        $import['043'] = new sesis_item('','','','','');

        //Field#44 Primary Setting Code
        $import['044'] = new sesis_item($this->primary_setting_code(date_massage($arr['dob'], 'm/d/Y'), $this->build_spedSetting_pre2007(), $this->build_parentalPlacement2008($arr['parental_placement'])),'Primary Setting Code','','','','');

        //echo "44: " . $import['044'] . "<BR>";

        // Field#45 Unused – Leave Blank
        $import['045'] = new sesis_item('','','','','');

        // Field#46 Unused – Leave Blank
        $import['046'] = new sesis_item('','','','','');

        // Field#47 School Aged Indicator
        // This is where we can place the a code to say if the student is PART B or PART C (see above).
        // Code Description
        // 0 Not Applicable
        // 1 Parent Placement
        $import['047'] = new sesis_item($this->settingCat,'School Aged Indicator','','','','');

        // Field#48 Surrogate Appointed Code
        // This data will come from the EDIT STUDENT PAGE under the section labeled “Has a surrogate parent been appointed?:
        // YES = 1
        // NO = 2
//        $import['048'] = new sesis_item($this->build_ward_surrogate($arr['ward_surrogate']),'Surrogate','Int','4','','','');
        $import['048'] = new sesis_item('','','','','');

        // Field#49 Unused – Leave Blank
        $import['049'] = new sesis_item('','','','','');


        // Field#50  Special Education Percentage
        // This data will come from page 6 of the most current IEP.  We need whatever number was entered into the “Not with regular peers” blank.
        //$import['050'] = new sesis_item($this->mostRecentIEP['special_ed_non_peer_percent'],'Special Education Percentage','','','','');
        $import['050'] = new sesis_item($this->buildSpecialEdPercentage(),'Special Education Percentage','','','','');

        // Field#51  Placement Reason
        // This data is pulled from the new “Parental Placement” section on the EDIT STUDENT page.
        // If they have selected “YES” in this section then we will use code “1”.  If NO then “0”(zero)
        //
        // Code Description
        // 0 Not Applicable
        // 1 Parent Placement
//        $import['051'] = new sesis_item($this->build_parentalPlacement2008($arr['parental_placement']),'Parental Placement','Int','4','','','NEW');
        $import['051'] = new sesis_item('','','','','');

        // Field#52 Exit Reason
        // This data will come from the “Exit Reason” section of the IEP.
        // I don’t believe that the codes have changed much (except for the noted exceptions).
        // We will want to use the same type of calculations to figure out if the student should
        // receive a “Part C” list of exit reasons or a “Part B” list.  If the student is
        // considered “Transitional” then we will probably have to look at the student’s forms
        // and look to see if he has an IEP.  If he has a finalized IEP, then we will need to give a PART B list.  If he doesn’t then PART C.
        if('Y' == $this->settingCat && 1 == $arr['sesis_exit_code']) {
            // switch to code 11 if current code 1 and age = 3-21
            // also done on edit student
            $arr['sesis_exit_code'] = 11;
        }
        $import['052'] = new sesis_item($this->build_exitCode($arr['sesis_exit_code']),'','','','','');


        /*
        $import['001'] = new sesis_item($this->studentID,'STUDENT_ID','Int','7','No Validation','SAME');
        $import['002'] = new sesis_item($this->build_uniqueID($arr['unique_id_state']),'STATEID','Int','10','Required, must be a length of 10 characters','Formerly row 60');
        $import['003'] = new sesis_item(substr($arr['name_last'], 0, 30),'LASTNAME','Varchar','30','Required, must be a length between 1 and 30 alphabetic characters or spaces','Formerly row 5');
        $import['004'] = new sesis_item(substr($arr['name_first'], 0, 20),'FIRSTNAME','Varchar','20','Required, must be a length between 1 and 20 alphabetic characters or spaces','Formerly row 6');
        $import['005'] = new sesis_item(substr($arr['name_middle'], 0, 1),'MIDDLENAME','Varchar','1','Can be empty or contain 1 alphabetic character','Formerly row 7');
        $import['006'] = new sesis_item(date_massage($arr['dob'], 'm/d/Y'),'DOB','Datetime','10','Required, valid date that is less than today, format MM/DD/CCYY','Formerly row 8');
        $import['007'] = new sesis_item(-1,'SESIS_STATUS','Int','4','Required, must be -1,  Application will set the status based on the DOB, Exit Code, reporting period.  See SESIS Status Table Below','NEW FIELD');
        $import['008'] = new sesis_item($this->build_ethnicity($arr['ethnic_group']),'ETHNIC CODE','Int','4','Required, see Ethnic Validation Table Below','Formerly row 12');
        $import['009'] = new sesis_item($this->build_gender($arr['gender']),'GENDER CODE','Int','4','Required, 0 or 1, 0 = Male, 1 = Female ','Formerly row 61');
        $import['010'] = new sesis_item(-1,'PROVIDER TYPE','Int','4','Empty or 0','NEW (just put in 0)');
        $import['011'] = new sesis_item($this->build_CDS($arr['id_county'], $arr['id_district'], $arr['id_school']),'RESIDENT COUNTY DISTRICT','Varchar','11','Required, length of 11 characters, validated against an Agency table','This is county, district, school number.  ');
        $import['012'] = new sesis_item(-1,'UNUSED','Int','2','','NEW');
        $import['013'] = new sesis_item($this->build_pubSchoolStudent($ageYears, $arr['pub_school_student']),'NON PUBLIC SCHOOL','Int','4','Required, 0, 1 or -1, 0 = No, 1 = Yes, -1 = No Value If age < 6, value must be -1, else value must be 0 or 1','Will stay at 15');
        $import['014'] = new sesis_item($this->build_parentalPlacement(date_massage($arr['dob'], 'm/d/Y'), $arr['parental_placement'], $import['013']->data),'PARENTAL PLACEMENT','Int','4','Required, 0, 1 or -1, 0 = No, 1 = Yes, -1 = No Value If age < 6, value must be -1, else if #13 value = 0, #14 value must be 0, else value must be 0 or 1', 'NEW');
        $import['015'] = new sesis_item($this->build_limited_english_proficient($this->studentID, $arr['ell_student']),'LIMITED ENGLISH PROFICIENCY','Int','4','Required, 0 or 1, 0 = No, 1 = Yes    ','Formerly row 58');
        $import['016'] = new sesis_item($this->build_grade($arr['grade']),'GRADE','Int','4','Required if SESIS Status Code is 3, see Grade Validation Table Below','Formerly row 59 *** Grade Codes have changed ***');
        $import['017'] = new sesis_item($this->build_initialVersion(),'INITIAL DATE','Datetime','10','Required if Primary Disability value is entered, must be a valid date','Formerly row 2');
        $import['018'] = new sesis_item($this->build_disPrime(),'PRIMARY DISABILITY','Int','4','Required, must be numeric, see Initial Disability Table below','Formerly row 19');
        $import['019'] = new sesis_item( $this->build_hearing_impairment($this->mostRecentMDT['disability_hi'], $import['018']->data) ,'HEARING_IMPAIRMENT','Int','4','Required, 0 or 1, 0 = False, 1 = True','Formerly row 20');
//        $import['020'] = new sesis_item(oneOrZero(!empty($this->mostRecentMDT['disability_vi'])),'VISUAL_IMPAIRMENT','Int','4','Required, 0 or 1, 0 = False, 1 = True','Formerly row 21');

        $import['020'] = new sesis_item($this->build_VI_impairment($this->mostRecentMDT['disability_vi'], $import['018']->data),'VISUAL_IMPAIRMENT','Int','4','Required, 0 or 1, 0 = False, 1 = True','Formerly row 21');
        $import['021'] = new sesis_item(-1,'MULTIPLE_IMPAIRMENT','Int','4','Must be -1','NEW -- JUST ADD ZERO');
        $import['022'] = new sesis_item($this->build_hearing_impair_status($import['019']->data, $this->mostRecentMDT['disability_hi_detail'], $import['018']->data),'HEARING_IMPAIR_STATUS','Int','4','See Hearing Impairment Status Table below', 'NEW -- We removed Hearing Impairment Tertiary Codes, so just fill this with ZEROS');
        $import['023'] = new sesis_item($this->build_visual_impair_status($import['020']->data, $this->mostRecentMDT['disability_vi'], $import['018']->data),'VISUAL_IMPAIR_STATUS','Int','4','See Visual Impairment Status Table below', 'NEW -- Relates to tertiary characteristics on page 3 MDT');
        $import['024'] = new sesis_item($this->build_program_provider($arr['program_provider']),'MAJORPROVIDERTYPE','Int','4','Required, valid values 1, 2, or 3, see Major Provider Table below','Formerly row 27');
        $import['025'] = new sesis_item($this->build_majorprovidernumber($import['024']->data, $arr['program_provider_name'], $arr['program_provider_code'], $arr['program_provider_id_school']),'MAJORPROVIDERNUMBER','Varchar','11', 'Required if the Major Provider Type = 2 or 3, see Major Provider Table below','Formerly row 28');
//        $import['026'] = new sesis_item($ageYears,'AGE','Int','4','Input ignored, age calculated based on DOB value','Can put age in, but it is ignored');
        $import['026'] = new sesis_item(-1,'AGE','Int','4','Input ignored, age calculated based on DOB value','Can put age in, but it is ignored');
        $import['027'] = new sesis_item($this->build_spedSetting_2007(date_massage($arr['dob'], 'm/d/Y'), $this->build_spedSetting_pre2007(), $import['014']->data),'SPED_SETTING','Int','4','Required, valid for age range, see SPED Setting Table below','Formerly row 30.  ***Big changes here,  will need to update IEP and IFSP*** -- WAITING FOR INFO FROM WADE');
        $import['028'] = new sesis_item($this->build_speechLngThrpy(),'SPEECHLNGTHRPY','Int','1','No validation','Formerly row 46');
        $import['029'] = new sesis_item($this->makeSESISpercent($this->mostRecentIEP['special_ed_peer_percent']),'SPDWREGEDPEERS','Float','8','Required, numeric, total of columns 29, 30 and 31 must = 100','Formerly row 52');
        $import['030'] = new sesis_item($this->makeSESISpercent($this->mostRecentIEP['special_ed_non_peer_percent']),'SPEDWOREGEDPEERS','Float','8','Required, numeric, total of columns 29, 30 and 31 must = 100', 'Formerly row 53');
        $import['031'] = new sesis_item($this->makeSESISpercent($this->mostRecentIEP['reg_ed_percent']),'REGULAREDUCATION','Float','8','Required, numeric, total of columns 29, 30 and 31 must = 100', 'Formerly row 54');
        $import['032'] = new sesis_item(oneOrZero($arr['ward']),'WARD OF THE STATE','Int','4','Required, 0 or 1, 0 = No, 1  = Yes, see Ward/Surrogate Table below for other validations','Formerly row 14');
        $import['033'] = new sesis_item($this->build_ward_surrogate(oneOrZero($arr['ward']), $arr['ward_surrogate']),'SURROGATE','Int','4','Required, 0 or 1, 0 = No, 1  = Yes,  see Ward/Surrogate Table below for other validations','Formerly row 15');
        $import['034'] = new sesis_item($this->build_ward_surrogate_not_needed($arr['ward'], $arr['ward_surrogate_nn'], $arr['ward_surrogate_other']),'NOSURROGATEPARENT','Int','4','Required, 0 or 1, 0 for False, 1 for True, see Ward/Surrogate Table below for other validations','Formerly row 16');
        $import['035'] = new sesis_item($this->build_ward_surrogate_other($arr['ward'], $arr['ward_surrogate_other']),'NOSURROGATEOTHER','Int','4','Required, 0 or 1, 0 for False, 1 for True, see Ward/Surrogate Table below for other validations','Formerly row 17');
        $import['036'] = new sesis_item(substr($this->build_ward_surrogate_reason($arr['ward'], $arr['ward_surrogate_other']), 0, 81),'OTHERREASON','Varchar','80','See Ward/Surrogate Table below for validation, length must be less than 81 characters','Formerly row 18');
        $import['037'] = new sesis_item(date_massage($arr['sesis_exit_date']),'EXITDATE','Datetime','10','Empty or a valid date, see Exit table below, if present, exit reason must be present','Formerly row 55');
        $import['038'] = new sesis_item($arr['sesis_exit_code'],'EXITREASON','Int','4','Empty or a valid reason code, see Exit table below, if present, exit date must be present','Formerly row 56');


        // 8a helper
        $import['097'] = new sesis_item($this->build8a(date_massage($arr['dob'], 'm/d/Y'), $import['013']->data, $import['014']->data,'parental_placement','','',''));

        // MDT
        $import['098'] = new sesis_item($this->mostRecentMDT,'MDT','','','');

        // age
        $import['099'] = new sesis_item($ageYears,'AGE','Int','4','');

        // some data is needed by the sesis report that is not passed to the state
        if($this->mostRecentIEP != -1) {
            #if(1000254 == $sessIdUser) echo "mostRecentIEP<BR>";
            //pre_print_r($this->mostRecentIEP);
            //$import['100'] = date_massage($this->mostRecentIEP['date_conference']);
            $import['100'] = new sesis_item(date_massage($this->mostRecentIEP['date_conference']),'date_conference','Datetime','10','','Formerly row 57');
        } elseif($this->mostRecent013 != -1) {
            #if(1000254 == $sessIdUser) echo "mostRecent013<BR>";
            // 050307 changed to meeting_date frm date_notice Ben and Jesse
            $import['100'] = new sesis_item(date_massage($this->mostRecent013['meeting_date']),'date_conference','Datetime','10','','Formerly row 57');

        } else {
            $import['100'] = new sesis_item("",'','','','','');
        }
        */
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

}