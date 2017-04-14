<?php

class Form_StudentDemographics extends Form_AbstractForm {

    public function init() {
        $this->setMethod ( 'post' );
        $this->setAttrib( 'class', $this->getAttrib( 'class' ) . ' zend_form' );

        /**
         * navigation buttons
         */
        $this->submit = new App_Form_Element_Submit('submit', array('label' => 'Save'));
        $this->submit->removeDecorator('label');
        $this->submit->setAllowEmpty(true);
        $this->submit->setRequired(false);

        $this->cancel = new App_Form_Element_Submit('cancel', array('label' => 'Cancel'));
        $this->cancel->removeDecorator('label');
        $this->cancel->setAllowEmpty(true);
        $this->cancel->setRequired(false);
        $this->addDisplayGroup(array(
            $this->cancel,
            $this->submit,
        ), 'navigation1');
        $this->getDisplayGroup('navigation1')->setAttrib('style', 'text-align:right;');
        $this->getDisplayGroup('navigation1')->setAttrib('class', 'navigation');



        $this->name_first = new App_Form_Element_Text('name_first');
        $this->name_first->setLabel('First Name');
        $this->name_first->setRequired(true);
        $this->name_first->setAllowEmpty(false);

        $this->name_middle = new App_Form_Element_Text('name_middle');
        $this->name_middle->setLabel('Middle Name');
        $this->name_middle->setRequired(false);
        $this->name_middle->setAllowEmpty(true);

        $this->name_last = new App_Form_Element_Text('name_last');
        $this->name_last->setLabel('Last Name');
        $this->name_last->setrequired(true);

        $this->unique_id_state= new App_Form_Element_Text('unique_id_state', array('label' => 'NSSRS ID#'));
        $this->unique_id_state->setRequired(false);
        $this->unique_id_state->setAllowEmpty(true);
        $this->unique_id_state->addFilter(new Zend_Filter_Int());
        $this->unique_id_state->addFilter(new Zend_Filter_Null());


        $this->exclude_from_nssrs_report = new App_Form_Element_Checkbox('exclude_from_nssrs_report', array('label' => 'Exclude file from NSSRS Upload'));
        $this->exclude_from_nssrs_report->getDecorator('label')->setOption('placement', 'prepend');
//        $this->exclude_from_nssrs_report->addFilter(new Zend_Filter_Int());

        $this->id_student = new App_Form_Element_Text('id_student', array('label' => 'Student Id'));
        $this->id_student->setAllowEmpty(true);
        $this->id_student->setRequired(false);
        $this->id_student->setAttrib('readonly', 'readonly');
        $this->id_student->setIgnore(true);

        $this->id_student_local = new App_Form_Element_Text('id_student_local', array('label' => 'Local ID'));
        $this->id_student_local->setAllowEmpty(true);
        $this->id_student_local->setRequired(false);
        $this->id_student_local->addFilter(new Zend_Filter_Int());

        $options = array('Active', 'Inactive', 'Never Qualified', 'Equitable Service');
        $this->status= new App_Form_Element_Select('status', array('label' => 'Status'));
        $this->status->setMultiOptions(array_combine($options, $options));
        $this->status->setRequired(true);
        $this->status->setAllowEmpty(false);

        $this->notice_date= new App_Form_Element_DatePicker('notice_date', array('label' => 'Date Created (Optional)'));
        $this->notice_date->setRequired(false);
        $this->notice_date->setAllowEmpty(true);
        $this->notice_date->addFilter(new Zend_Filter_Null());

        $this->sesis_exit_code = new App_Form_Element_Select('sesis_exit_code', array('label' => 'Exit Reason'));
        $this->sesis_exit_code->setRequired(false);
        $this->sesis_exit_code->setAllowEmpty(true);
        $this->sesis_exit_code->addFilter(new Zend_Filter_Int());
        $this->sesis_exit_code->addFilter(new Zend_Filter_Null());
        $this->sesis_exit_code->addValidator(new My_Validate_NotEmptyIf('status', 'Inactive'));

        $this->sesis_exit_date= new App_Form_Element_DatePicker('sesis_exit_date', array('label' => 'Exit Date'));
        $this->sesis_exit_date->setRequired(false);
        $this->sesis_exit_date->setAllowEmpty(true);
        $this->sesis_exit_date->addValidator(new My_Validate_NotEmptyIf('status', 'Inactive'));
//        $this->sesis_exit_date->addValidator(new My_Validate_NotEmptyIf('status', 'Never Qualified'));
        $this->sesis_exit_date->addFilter(new Zend_Filter_Null());


        $this->addDisplayGroup(array(
            $this->name_first,
            $this->name_middle,
            $this->name_last,
            $this->unique_id_state,
            $this->exclude_from_nssrs_report,
            $this->id_student,
            $this->id_student_local,
            $this->status,
            $this->notice_date,
            $this->sesis_exit_code,
            $this->sesis_exit_date,
        ), 'demo_info', array('legend'=>'Student Information'));

        $this->id_county = new App_Form_Element_Select('id_county', array('label' => 'County'));
        $this->id_district = new App_Form_Element_Select('id_district', array('label' => 'District'));
        $this->id_school = new App_Form_Element_Select('id_school', array('label' => 'School'));

        $this->id_county_display = new App_Form_Element_Text('id_county_display', array('label' => 'County'));
        $this->id_county_display->setIgnore(true);
        $this->id_county_display->setAllowEmpty(true);
        $this->id_county_display->setRequired(false);
        $this->id_county_display->setAttrib('readonly', 'readonly');


        $this->id_district_display = new App_Form_Element_Text('id_district_display', array('label' => 'District'));
        $this->id_district_display->setIgnore(true);
        $this->id_district_display->setAllowEmpty(true);
        $this->id_district_display->setRequired(false);
        $this->id_district_display->setAttrib('readonly', 'readonly');

        $this->id_school_display = new App_Form_Element_Text('id_school_display', array('label' => 'School'));
        $this->id_school_display->setIgnore(true);
        $this->id_school_display->setAllowEmpty(true);
        $this->id_school_display->setRequired(false);
        $this->id_school_display->setAttrib('readonly', 'readonly');

        $this->id_case_mgr = new App_Form_Element_Select('id_case_mgr', array('label' => 'Case Manager'));
        $this->id_case_mgr->setRequired(false);
        $this->id_case_mgr->setAllowEmpty(true);
        $this->id_case_mgr->addFilter(new Zend_Filter_Int());
        $this->id_case_mgr->addFilter(new Zend_Filter_Null());



        $this->pub_school_student = new App_Form_Element_Radio('pub_school_student', array('label' => 'Public school student'));
        $this->pub_school_student->getDecorator('label')->setOption('placement', 'prepend');
        $this->pub_school_student->addFilter(new Zend_Filter_Int());
        $this->pub_school_student->setMultiOptions(array(
            1 => 'Yes',
            0 => 'No'
        ));

        $this->parental_placement = new App_Form_Element_Radio('parental_placement', array('label' => 'Parental Placement'));
        $this->parental_placement->setRequired(false);
        $this->parental_placement->setAllowEmpty(true);
        $this->parental_placement->getDecorator('label')->setOption('placement', 'prepend');
        $this->parental_placement->addFilter(new Zend_Filter_Int());
        $this->parental_placement->setMultiOptions(array(
            1 => 'Yes',
            0 => 'No'
        ));

        $this->nonpubcounty = new App_Form_Element_Select('nonpubcounty', array('label' => 'Non Public County'));
        $this->nonpubcounty->setRequired(false);
        $this->nonpubcounty->setAllowEmpty(true);
        $this->nonpubcounty->addFilter(new Zend_Filter_Digits());
//        $this->nonpubcounty->addFilter(new Zend_Filter_Null());

        $this->nonpubdistrict = new App_Form_Element_Select('nonpubdistrict', array('label' => 'Non Public District'));
        $this->nonpubdistrict->setRequired(false);
        $this->nonpubdistrict->setAllowEmpty(true);
        $this->nonpubdistrict->addFilter(new Zend_Filter_Digits());
        $this->nonpubdistrict->addFilter(new Zend_Filter_Null());

        $this->nonpubschool = new App_Form_Element_Select('nonpubschool', array('label' => 'Non Public School'));
        $this->nonpubschool->setRequired(false);
        $this->nonpubschool->setAllowEmpty(true);
        $this->nonpubschool->addFilter(new Zend_Filter_Digits());
        $this->nonpubschool->addFilter(new Zend_Filter_Null());

        $this->dob = new App_Form_Element_DatePicker('dob');
        $this->dob->setLabel('Date of Birth');
        $this->dob->setRequired(true);
        $this->dob->addFilter(new Zend_Filter_Null());

        $this->age = new App_Form_Element_Text('age');
        $this->age->setLabel('Age');
        $this->age->setAttrib('readonly', 'readonly');
        $this->age->setIgnore(true);
        $this->age->setRequired(false);
        $this->age->setAllowEmpty(true);

        $arrLabel = array("Choose...", "EI 0-2", "ECSE (Age 3-5)", "K", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "12+");
        $arrValue = array("", "EI 0-2", "ECSE", "K", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "12+");
        $this->grade = new App_Form_Element_Select('grade', array('label' => 'Grade'));
        $this->grade->setMultiOptions(array_combine($arrValue, $arrLabel));

        /****************************************************************************************************
         *
         */
        $this->id_ser_cord = new App_Form_Element_Select('id_ser_cord', array('label' => 'Service Coordinator'));
        $this->id_ser_cord->setRequired(false);
        $this->id_ser_cord->setAllowEmpty(true);
        $this->id_ser_cord->addFilter(new Zend_Filter_Int());

        $this->id_ei_case_mgr = new App_Form_Element_Select('id_ei_case_mgr', array('label' => 'Early Intervention Case Manager'));
        $this->id_ei_case_mgr->setRequired(false);
        $this->id_ei_case_mgr->setAllowEmpty(true);
        $this->id_ei_case_mgr->addFilter(new Zend_Filter_Int());

        $this->medicaid = new App_Form_Element_Text('medicaid', array('label' => 'Medicaid', 'description' => 'xxxxxxxxx-xx'));
        $this->medicaid->setRequired(false);
        $this->medicaid->setAllowEmpty(true);
        $this->medicaid->addErrorMessage('Please enter a number in the format xxxxxxxxx-xx.');
        $validator = new Zend_Validate_Regex(array('pattern' => '/^([0-9]{9})[-]([0-9]{2})$/'));
        $this->medicaid->addValidator($validator);

        $this->ssn = new App_Form_Element_Text('ssn', array('label' => 'SSN', 'description' => 'xxx-xx-xxxx'));
        $this->ssn->setRequired(false);
        $this->ssn->setAllowEmpty(true);
        $this->ssn->addErrorMessage('Please enter a number in the format xxxxxxxxx-xx.');
        $validator = new Zend_Validate_Regex(array('pattern' => '/^([0-9]{3})[-]([0-9]{2})[-]([0-9]{4})$/'));
        $this->ssn->addValidator($validator);

        $this->ei_ref_date = new App_Form_Element_DatePicker('ei_ref_date', array('label' => 'Early Intervention Referral Date'));
        $this->ei_ref_date->setRequired(false);
        $this->ei_ref_date->setAllowEmpty(true);

        $options = array('Male', 'Female');
        $this->gender = new App_Form_Element_Radio('gender');
        $this->gender->getDecorator('label')->setOption('placement', 'prepend');
        $this->gender->setLabel('Gender');
        $this->gender->setRequired(true);
        $this->gender->setMultiOptions(array(
            'Male' => 'Male',
            'Female' => 'Female'
        ));
        $this->gender->setMultiOptions(array_combine($options, $options));

        $this->ethnic_group = new App_Form_Element_Select('ethnic_group');
        $this->ethnic_group->setLabel('Ethnic Group');
        $this->ethnic_group->getDecorator('label')->setOption('class', 'srsLabel');
        $this->ethnic_group->setRequired(true);
        $this->ethnic_group->setMultiOptions(array(
            ''=>'Choose...',
            'A' =>'White, Not Hispanic',
            'B' =>'Black, Not Hispanic',
            'C'=>'Hispanic',
            'D'=>'American Indian / Alaska Native',
            'E'=>'Asian / Pacific Islander'
        ));

        $this->ell_student = new App_Form_Element_Radio('ell_student');
        $this->ell_student->getDecorator('label')->setOption('placement', 'prepend');
        $this->ell_student->setLabel('Ell Student?');
        $this->ell_student->setRequired(true);
        $this->ell_student->addFilter(new Zend_Filter_Int());
        $this->ell_student->setMultiOptions(array(
            '1' => 'Yes',
            '0' => 'No'
        ));

        $this->alternate_assessment = new App_Form_Element_Radio('alternate_assessment', array('label' => 'Alternate assessment'));
        $this->alternate_assessment->getDecorator('label')->setOption('placement', 'prepend');
//        $this->alternate_assessment->setAttrib('readonly', 'readonly');
        $this->alternate_assessment->addFilter(new Zend_Filter_Int());
         $this->alternate_assessment->setMultiOptions(array(
            '1' => 'Yes',
            '0' => 'No'
        ));

        $this->primary_language = new App_Form_Element_Select('primary_language');
        $this->primary_language->setLabel('Primary language');
        $this->primary_language->getDecorator('label')->setOption('class', 'srsLabel');
        $this->primary_language->setRequired(true);
        $this->primary_language->setMultiOptions(array(
            '' => 'Choose...',
            'Sign primary_language' => 'Sign primary_language',
            'English' => 'English',
            'Afrikaans' => 'Afrikaans',
            'Albanian' => 'Albanian',
            'Amharic' => 'Amharic',
            'Arabic' => 'Arabic',
            'Bangle' => 'Bangle',
            'Bhutanese' => 'Bhutanese',
            'Bosnian' => 'Bosnian',
            'Chinese' => 'Chinese',
            'Croatian' => 'Croatian',
            'Czech' => 'Czech',
            'Danish' => 'Danish',
            'Dari' => 'Dari',
            'Dinka' => 'Dinka',
            'Dutch' => 'Dutch',
            'Farsi' => 'Farsi',
            'Finnish' => 'Finnish',
            'French' => 'French',
            'German' => 'German',
            'Gujarati' => 'Gujarati',
            'Hindi' => 'Hindi',
            'Hungarian' => 'Hungarian',
            'Indonesian' => 'Indonesian',
            'Italian' => 'Italian',
            'Japanese' => 'Japanese',
            'Khana' => 'Khana',
            'Khmer' => 'Khmer',
            'Korean' => 'Korean',
            'Kurdish' => 'Kurdish',
            'Latvian' => 'Latvian',
            'Luganda' => 'Luganda',
            'Lumasaba' => 'Lumasaba',
            'Mandarin' => 'Mandarin',
            'Nepalis' => 'Nepalis',
            'Nuer' => 'Nuer',
            'Nyanja' => 'Nyanja',
            'Ogoni' => 'Ogoni',
            'Oriya' => 'Oriya',
            'Pashtu' => 'Pashtu',
            'Persian' => 'Persian',
            'Pilipino' => 'Pilipino',
            'Polish' => 'Polish',
            'Portuguese' => 'Portuguese',
            'Punjabi' => 'Punjabi',
            'Romanian' => 'Romanian',
            'Russian' => 'Russian',
            'Serbo-Croat' => 'Serbo-Croat',
            'Sinhala' => 'Sinhala',
            'Somali' => 'Somali',
            'Spanish' => 'Spanish',
            'Swahili' => 'Swahili',
            'Tagalog' => 'Tagalog',
            'Tajik' => 'Tajik',
            'Tamil' => 'Tamil',
            'Telegu' => 'Telegu',
            'Thai' => 'Thai',
            'Tigrbea' => 'Tigrbea',
            'Tigrigna' => 'Tigrigna',
            'Tonga' => 'Tonga',
            'Tswana' => 'Tswana',
            'Turkish' => 'Turkish',
            'Ukrainian' => 'Ukrainian',
            'Urdu' => 'Urdu',
            'Vietnamese' => 'Vietnamese',
            'Other' => 'Other',
        ));


        $this->ward = new App_Form_Element_Radio('ward', array('label' => 'Ward of state'));
        $this->ward->getDecorator('label')->setOption('placement', 'prepend');
        $this->ward->addFilter(new Zend_Filter_Int());
        $this->ward->setMultiOptions(array(
            '1' => 'Yes',
            '0' => 'No'
        ));

        $this->ward_surrogate = new App_Form_Element_Radio('ward_surrogate', array('label' => 'Has a surrogate parent been appointed?'));
        $this->ward_surrogate->setAllowEmpty(true);
        $this->ward_surrogate->setRequired(false);
        $this->ward_surrogate->addFilter(new Zend_Filter_Int());
        $this->ward_surrogate->addValidator(new My_Validate_BooleanNotEmptyIf('ward', 1));
        $this->ward_surrogate->getDecorator('label')->setOption('placement', 'prepend');
        $this->ward_surrogate->setMultiOptions(array(
            '1' => 'Yes',
            '0' => 'No'
        ));

        $this->ward_surrogate_nn = new App_Form_Element_Radio('ward_surrogate_nn', array('label' => 'If surrogate not needed, is it because parents are involved?'));
        $this->ward_surrogate_nn->setAllowEmpty(true);
        $this->ward_surrogate_nn->setRequired(false);
        $this->ward_surrogate_nn->addValidator(new My_Validate_BooleanNotEmptyIf('ward', 1));
        $this->ward_surrogate_nn->getDecorator('label')->setOption('placement', 'prepend');
        $this->ward_surrogate_nn->addFilter(new Zend_Filter_Int());
        $this->ward_surrogate_nn->setMultiOptions(array(
            '1' => 'Yes',
            '0' => 'No'
        ));

        $this->ward_surrogate_other = new App_Form_Element_Text('ward_surrogate_other', array('label' => 'Other reason that surrogate is not needed, please explain'));
        $this->ward_surrogate_other->setAllowEmpty(true);
        $this->ward_surrogate_other->setRequired(false);
        $this->ward_surrogate_other->addValidator(new My_Validate_NotEmptyIf('ward', 1));

        $this->primary_disability_display = new App_Form_Element_Text('primary_disability_display', array('label'
        => 'Primary Disability'));
        $this->primary_disability_display->setIgnore(true);
        $this->primary_disability_display->setAllowEmpty(true);
        $this->primary_disability_display->setRequired(false);
        $this->primary_disability_display->setAttrib('readonly', 'readonly');

        $this->addDisplayGroup(array(
            $this->id_county,
            $this->id_district,
            $this->id_school,
            $this->id_county_display,
            $this->id_district_display,
            $this->id_school_display,
            $this->id_case_mgr,
            $this->pub_school_student,
            $this->parental_placement,
            $this->nonpubcounty,
            $this->nonpubdistrict,
            $this->nonpubschool,
            $this->dob,
            $this->age,
            $this->grade,
            $this->id_ser_cord,
            $this->id_ei_case_mgr,
            $this->medicaid,
            $this->ssn,
            $this->ei_ref_date,
            $this->gender,
            $this->ethnic_group,
            $this->primary_language,
            $this->ell_student,
            $this->alternate_assessment,
            $this->ward,
            $this->ward_surrogate,
            $this->ward_surrogate_nn,
            $this->ward_surrogate_other,
            $this->primary_disability_display,
        ), 'add_info', array('legend'=>'Additional Information'));

        $this->address_street1 = new App_Form_Element_Text('address_street1', array('label'=>'Street Line 1'));
        $this->address_street1->setAllowEmpty(false);
        $this->address_street1->setRequired(true);

        $this->address_street2 = new App_Form_Element_Text('address_street2', array('label'=>'Street Line 2'));
        $this->address_street2->setAllowEmpty(true);
        $this->address_street2->setRequired(false);

        $this->address_city = new App_Form_Element_Text('address_city', array('label'=>'City'));
        $this->address_city->setAllowEmpty(false);
        $this->address_city->setRequired(true);

        $this->address_state = new App_Form_Element_Text('address_state', array('label'=>'State'));
        $this->address_state->setAllowEmpty(false);
        $this->address_state->setRequired(true);

        $this->address_zip = new App_Form_Element_Text('address_zip', array('label'=>'Zip'));
        $this->address_zip->setDescription('zip or zip+4, example: 55555 or 55555-5555');
        $this->address_zip->setAllowEmpty(false);
        $this->address_zip->setRequired(true);
        $postalCode = new Zend_Validate_PostCode('en_US');
        $this->address_zip->addValidator($postalCode);

        $this->phone = new App_Form_Element_Text('phone', array('label' => 'Phone Number'));
        $this->phone->setDescription('include area code, example: 123-222-3333');
        $this->phone->setAllowEmpty(true);
        $this->phone->setRequired(false);
        $this->phone->addErrorMessage('Please enter a 7 or 10 digit phone number.');
        $validator = new Zend_Validate_Regex(array('pattern' => '/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/'));
        $this->phone->addValidator($validator);

        $this->email_address = new App_Form_Element_Text('email_address');
        $this->email_address->setLabel('Email');
        $this->email_address->setAllowEmpty(true);
        $this->email_address->setRequired(false);

        $this->confirm_email = new App_Form_Element_Text('confirm_email');
        $this->confirm_email->setLabel('Confirm Email');
        $this->confirm_email->setRequired(false);
        $this->confirm_email->setAllowEmpty(true);
        $this->confirm_email->addValidator(new App_Form_Validate_RequiredIfContextNotEmpty('email'));
        $this->confirm_email->addValidator(new Zend_Validate_Identical(array('token'=>'email_address')));


        $this->addDisplayGroup(array(
            $this->address_street1,
            $this->address_street2,
            $this->address_city,
            $this->address_state,
            $this->address_zip,
            $this->phone,
            $this->email_address,
            $this->confirm_email,
        ), 'contact_info', array('legend'=>'Contact Information'));


//        $this->program_provider = new App_Form_Element_Text('program_provider', array('label' => 'You must choose a Program Provider.'));
//
//        $this->date_web_notify = new App_Form_Element_Text('date_web_notify', array('label' => 'Date of parental notification'));

        /**
         * put each element on it's own line
         */
        $this->wrapWithDivs($this);


        $this->addDisplayGroup(array(
            $this->cancel,
            $this->submit,
        ), 'navigation2');

        $this->getDisplayGroup('navigation2')->setAttrib('style', 'text-align:right;');
        $this->getDisplayGroup('navigation2')->setAttrib('class', 'navigation');

        $this->removeSrsFormHelpers($this);
        $this->addErrorDecorator($this);
        $this->addAstriskToRequired($this);
        return $this;

    }
    public function addAstriskToRequired() {
        foreach($this->getElements() as $element) {
            if($element->isRequired()) {
                $element->setLabel($element->getLabel().'*');
            }
        }
    }
    public function setMultiOptionsAndConditionalFields($studentData, $usersession) {

//        $this->id_county->setMultiOptions(Model_Table_County::countyMultiOtions());
//        if(!empty($studentData['id_county'])) {
//            $this->id_district->setMultiOptions(Model_Table_District::districtMultiOtions($studentData['id_county']));
//            if(!empty($studentData['id_district'])) {
//                $this->id_school->setMultiOptions(Model_Table_School::schoolMultiOtions($studentData['id_county'], $studentData['id_district']));
//            }
//        }
        if(isset($studentData['status']) && is_null($this->status->getMultiOption($this->status->getMultiOption($studentData['status'])))) {
            $this->status->addMultiOption($studentData['status'], $studentData['status']);
        }

        $this->nonpubcounty->addMultiOption('', 'Choose a county...');
        $this->nonpubcounty->addMultiOption('00', 'Home School');
        $this->nonpubcounty->addMultiOptions(Model_Table_County::getNonPublicCounties());

        if(!empty($studentData['nonpubcounty'])) {
            $this->nonpubdistrict->addMultiOption('', 'Choose a district...');
            $this->nonpubdistrict->addMultiOptions(Model_Table_District::getNonPublicDistricts($studentData['nonpubcounty']));
            if(!empty($studentData['nonpubdistrict'])) {
                $this->nonpubschool->addMultiOption('', 'Choose a school...');
                $this->nonpubschool->addMultiOptions(Model_Table_School::getNonPublicSchools($studentData['nonpubcounty'], $studentData['nonpubdistrict']));
            } else {
                $this->nonpubschool->addMultiOption('', 'Choose a county and save...');
            }
        } else {
            $this->nonpubdistrict->addMultiOption('', 'Choose a county and save...');
            $this->nonpubschool->addMultiOption('', 'Choose a county and save...');
        }

        $this->id_county->addMultiOption('', 'Choose a county...');
        $this->id_district->addMultiOption('', 'Choose a district...');
        $this->id_school->addMultiOption('', 'Choose a school...');

        $this->id_county->addMultiOptions(Model_Table_County::countyMultiOtions(true));

        if(isset($studentData['id_county']) && '' != $studentData['id_county']) {
            $this->id_county->addMultiOptions(Model_Table_County::countyMultiOtions(true));
            if(isset($studentData['id_county'])) {
                $this->id_district->addMultiOptions(Model_Table_District::districtMultiOtions($studentData['id_county'], true));
                if(isset($studentData['id_district'])) {
                    $this->id_school->addMultiOptions(Model_Table_School::schoolMultiOtions($studentData['id_county'], $studentData['id_district'], true));
                }
            }
        }
        if(isset($studentData['id_student'])) {
            // disable
            $this->id_county->helper = 'formHidden';
            $this->id_county->setDecorators(array('viewHelper'));
            $this->id_county->setAttrib('readonly', 'readonly');

            $this->id_district->helper = 'formHidden';
            $this->id_district->setDecorators(array('viewHelper'));
            $this->id_district->setAttrib('readonly', 'readonly');

            $this->id_school->helper = 'formHidden';
            $this->id_school->setDecorators(array('viewHelper'));
            $this->id_school->setAttrib('readonly', 'readonly');

        } else {
            $this->id_county_display->helper = 'formHidden';
            $this->id_district_display->helper = 'formHidden';
            $this->id_school_display->helper = 'formHidden';

        }



        /**
         * build case managers
         */
        if(isset($studentData['id_county']) && isset($studentData['id_district']) && isset($studentData['id_school'])) {
            $personnelTable = new Model_Table_PersonnelTable();
            $this->id_case_mgr->addMultiOption('', 'Choose...');
            $this->id_case_mgr->addMultiOptions(
                $personnelTable->getCaseManagers($studentData['id_county'], $studentData['id_district'], $studentData['id_school'])
            );
        }
        /**
         * exit code is based on the student age
         * exit reason is based on the district profile
         */
        if(isset($studentData['dob'])) {
            $abstractForm = new Model_AbstractForm('001', $usersession);
            $this->sesis_exit_code->setMultiOptions($abstractForm->sesisExitCategory($studentData['dob'], date('m/d/Y')));
        }

        if(isset($studentData['id_county']) && isset($studentData['id_district'])) {
            $districtTable = new Model_Table_District();
            $district = $districtTable->getDistrict($studentData['id_county'], $studentData['id_district']);

            if($district['use_nssrs'] && 'Inactive' == $district['status']) {
                /**
                 * exit code
                 */
                $this->sesis_exit_code->setRequired(true);
                $this->sesis_exit_code->setAllowEmpty(false);
                $this->sesis_exit_code->setLabel('Exit Category (Required)');
                /**
                 * exit date
                 */
                $this->sesis_exit_date->setRequired(true);
                $this->sesis_exit_date->setAllowEmpty(false);
                $this->sesis_exit_date->setLabel('Exit Date (Required)');
            }
        }


        /**
         * populate service coordinator and ei cm
         */
        $personnelObj = new Model_Table_PersonnelTable();

        if(isset($studentData['id_county']) && isset($studentData['id_district']) && isset($studentData['id_school'])) {
            $serviceCoordinators = $personnelObj->getServiceCoordinatorsOptions($studentData['id_county'], $studentData['id_district'], $studentData['id_school']);
            $this->id_ser_cord->addMultiOption('', 'Choose a Service Coordinator...');
            $this->id_ser_cord->addMultiOptions($serviceCoordinators);

            $eiCaseManagers = $personnelObj->getEiCaseManagers($studentData['id_county'], $studentData['id_district'], $studentData['id_school']);
            $this->id_ei_case_mgr->addMultiOption('', 'Choose a EI Case Manager...');
            $this->id_ei_case_mgr->addMultiOptions($eiCaseManagers);
        }

    }

    public function setDisabledValues() {
        $this->id_county_display->setValue($this->id_county->getMultiOption($this->id_county->getValue()));
        $this->id_county_display->setAttrib('disabled', true);
        $this->id_county_display->setIgnore(true);
        $this->id_county_display->setAllowEmpty(true);
        $this->id_county_display->setRequired(false);

        $this->id_district_display->setValue($this->id_district->getMultiOption($this->id_district->getValue()));
        $this->id_district_display->setAttrib('disabled', true);
        $this->id_district_display->setIgnore(true);
        $this->id_district_display->setAllowEmpty(true);
        $this->id_district_display->setRequired(false);

        $this->id_school_display->setValue($this->id_school->getMultiOption($this->id_school->getValue()));
        $this->id_school_display->setAttrib('disabled', true);
        $this->id_school_display->setIgnore(true);
        $this->id_school_display->setAllowEmpty(true);
        $this->id_school_display->setRequired(false);

    }

    public function populate(array $values) {
        parent::populate($values);

        /**
         * age
         */
//        $studentTable = new Model_Table_StudentTable();
//        $studentInfo = $studentTable->studentInfo($values['id_student']);
        if(isset($values['age']) && isset($values['age_months_into_year'])) {
            $this->age->setValue($values['age'] . ' years and ' . $values['age_months_into_year'] . ' months');
        }

        /**
         * alternate assessment
         */

// Uncommited out 1-8-2016
        $modelForm004 = new Model_Table_Form004();
        $form004 = $modelForm004->mostRecentFinalForm($values['id_student'], 'date_conference');
        if(null != $form004) {
            switch($form004->assessment_accom) {
                case 'The child will participate in district-wide assessment WITHOUT accommodations':
                    $this->alternate_assessment->setValue(0);
                    break;
                case 'The child will participate in district-wide assessment WITH accommodations, as specified:':
                    $this->alternate_assessment->setValue(0);
                    break;
                case 'The child will participate in a combination of assessment systems as specified.':
                    $this->alternate_assessment->setValue(1);
                    break;
                case 'The child will NOT participate in district-wide assessment, for the following reasons:':
                    $this->alternate_assessment->setValue(1);
                    break;
                case '':
                    break;
            }
  
        }
 
// Mike Test as specified
echo $form004->assessment_accom ."\n";
$b= 'The child will participate in district-wide assessment WITH accommodations, as specified:';
if ($form004->assessment_accom == $b)
 {
   $this->alternate_assessment->setValue(0);
   echo $b . "\n";
 }
// end of Mike test.  $b is the only value coming into $form004->assessment_accom




// end of the commented out.

        $this->setDisabledValues();
    }

    public function isValid($data = array())
    {
        /**
         * run validation
         */
        $result = parent::isValid($data);

        /**
         * additional data checks
         */

        /*
         *  -- Lets NOT include the validation check of the numbers
         *  -- This is OPTIONAL
         *  -- Could we have the site (on save) detect if this number is in use elsewhere
         *  and IF it is have a message appear that says "This ID# is already in use by
         *  another student at the following school [INSERT County, District, and School Building of where the duplicate ID# is located]. You may want to consider requesting this record from the district above and then using the SRS student merge feature to combine the records"
         */
        if(''!=$data['unique_id_state']) {
            $studentTable = new Model_Table_StudentTable();
            $where = "unique_id_state = '".$data['unique_id_state'] . "'";
            if('' != $data['id_student']) {
                $where .= " and id_student != '".$data['id_student']."'";
            }
            $matchedStudents = $studentTable->fetchAll($where);
            if(count($matchedStudents)>0) {
                $studentInfo = $studentTable->studentInfo($matchedStudents->current()->id_student);
                $this->unique_id_state->addError(
                    "NSSRS ID# {$data['unique_id_state']} is already in use by another student at the following school: <B>Name </B>" .
                    $studentInfo[0]['name_first'] . ' ' . $studentInfo[0]['name_last'] . ' <B>County </B>' .
                    $studentInfo[0]['name_county'] . ' <B>District </B>' . $studentInfo[0]['name_district'] . ' <B>School </B>' . $studentInfo[0]['name_school'] .
                    '). You may want to consider requesting this record from the district above and then using the ' .
                    'SRS student merge feature to combine the records'
                );
                $result = false;
            }
        }


        /**
         * parental placement
         */
        if(1 == (integer) $data['pub_school_student'] && isset($data['parental_placement']) && 1 == (integer) $data['parental_placement']) {
            /**
             * if public school student is yes, parental placement cannot be Yes
             */
            $this->parental_placement->addError("Parental Placement can only be YES if Pubic School = NO.");
            $result = false;
        } elseif(0 == (integer) $data['pub_school_student'] && isset($data['parental_placement']) && '' == $data['parental_placement']) {
            /**
             * if public school student is , parental placement must not be empty
             */
            $this->parental_placement->addError("Parental Placement cannot be empty when Public School = No.");
            $result = false;
        }

//        if('EI 0-2' == $data['grade'] && (!isset($data['id_ser_cord']) || '' == $data['id_ser_cord'])) {
//            $this->id_ser_cord->addError("Service Coordinator must be entered when grade is EI 0-2.");
//            $result = false;
//        }

        if('EI 0-2' == $data['grade'] && (!isset($data['id_ei_case_mgr']) || '' == $data['id_ei_case_mgr'])) {
            $this->id_ei_case_mgr->addError("Early Intervention Case Manager must be entered when grade is EI 0-2.");
            $result = false;
        }

//        if('EI 0-2' == $data['grade'] && (!isset($data['medicaid']) || '' == $data['medicaid'])) {
//            $this->medicaid->addError("Medicaid must be entered when grade is EI 0-2.");
//            $result = false;
//        }

        if('EI 0-2' == $data['grade'] && (!isset($data['ei_ref_date']) || '' == $data['ei_ref_date'])) {
            $this->ei_ref_date->addError("Early Intervention Referral Date must be entered when grade is EI 0-2.");
            $result = false;
        }

        /**
         * set class on wrapper container to turn red
         */
        foreach($this->getElements() as $element) {
            if($element->hasErrors()) {
                $existingClass = $element->getDecorator('wrapper');
                if($existingClass) {
                    $existingClass->setOption('class', 'error ' . $existingClass->getOption('class'));
                }
            }
        }
        return $result;
    }

}
