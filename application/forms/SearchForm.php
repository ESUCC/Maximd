<?php
/*

 */
class Form_SearchForm extends Zend_Form { 

    protected $_district;

    public $formInfo = array(
        "029" => array( "fullName" => "Notice of Meeting", "shortName" => "Notice of Meeting", 'date' => "date_notice" ),
        "001" => array( "fullName" => "Notice and Consent For Initial Evaluation", "shortName" => "Notice/Consent Initial Eval", 'date' => "date_notice" ),
        "002" => array( "fullName" => "Multi-disciplinary Team Report", "shortName" => "MDT Report", 'date' => "date_notice" ),
        "003" => array( "fullName" => "Notification of Individualized Education Program Meeting", "shortName" => "Notice of IEP Mtg", 'date' => "date_notice" ),
        "004" => array( "fullName" => "Individualized Education Plan", "shortName" => "IEP", 'date' => "date_conference" ),
        "005" => array( "fullName" => "Notice and Consent for Initial Placement", "shortName" => "Notice/Consent Initial Placement", 'date' => "date_notice" ),
        "006" => array( "fullName" => "Notice of School District&rsquo;s Decision Regarding Requested Special Education Services", "shortName" => "Notice of Decision", 'date' => "date_notice" ),
        "007" => array( "fullName" => "Notice and Consent for Reevaluation", "shortName" => "Notice/Consent Reeval", 'date' => "date_notice" ),
        "008" => array( "fullName" => "Notice of Change of Placement", "shortName" => "Notice of Change of Placement", 'date' => "date_notice" ),
        "009" => array( "fullName" => "Notice of Discontinuation of Special Education Services", "shortName" => "Notice of Discontinuation", 'date' => "date_notice" ),
        "010" => array( "fullName" => "Progress Report", "shortName" => "Progress Report", 'date' => "date_notice" ),
        "011" => array( "fullName" => "Notification of Multidisciplinary Team (MDT) Conference", "shortName" => "Notice of MDT Conference", 'date' => "mdt_conf_date" ),
        "012" => array( "fullName" => "Notice That No Additional Information Is Needed To Determine Continued Eligibility", "shortName" => "Determination Notice", 'date' => "date_notice" ),
        "013" => array( "fullName" => "Early Intervention Program", "shortName" => "IFSP", 'date' => "date_notice" ),
        "014" => array( "fullName" => "Notification of Individualized Family Service Plan", "shortName" => "Notice of IFSP Mtg", 'date' => "date_notice" ),
        "015" => array( "fullName" => "Notice and Consent For Initial Evaluation (IFSP)", "shortName" => "Notice/Consent Initial Eval (IFSP)", 'date' => "date_notice" ),
        "016" => array( "fullName" => "Notice and Consent for Initial Placement (IFSP)", "shortName" => "Notice/Consent Initial Placement (IFSP)", 'date' => "date_notice" ),
        "017" => array( "fullName" => "Note Page", "shortName" => "Notes", 'date' => "date_notice" ),
        "018" => array( "fullName" => "Summary of Performance", "shortName" => "SOP", 'date' => "date_notice" ),
        "019" => array( "fullName" => "Functional Assessment", "shortName" => "Functional Assessment", 'date' => "date_notice" ),
        "020" => array( "fullName" => "Specialized Transportation", "shortName" => "Specialized Transportation", 'date' => "date_notice" ),
        "021" => array( "fullName" => "Assistive Technology Considerations", "shortName" => "Assistive Technology Considerations", 'date' => "date_notice" ),
        "022" => array( "fullName" => "MDT Card", "shortName" => "MDT Card", 'date' => "date_notice" ),
        "023" => array( "fullName" => "IEP/IFSP Card", "shortName" => "IEP/IFSP Card", 'date' => "date_notice" ),
        "024" => array( "fullName" => "Agency Consent Invitation", "shortName" => "Agency Consent Invitation", 'date' => "date_notice" ),
        "025" => array( "fullName" => "Notification Of Multidisciplinary Team Planning Meeting", "shortName" => "Notification Of Multidisciplinary Team Planning Meeting", 'date' => "date_notice" ),
        "026" => array( "fullName" => "Revocation of Consent for Special Education and Related Services", "shortName" => "Revocation of Consent Form", 'date' => "date_notice" ),
        "027" => array( "fullName" => "Notice and Consent for Early Intervention Initial Screening", "shortName" => "Notice and Consent for Early Intervention Initial Screening", 'date' => "date_notice" ),
        "028" => array( "fullName" => "Equitable Service Plan", "shortName" => "Equitable Service Plan", 'date' => "date_notice" ),
        "030" => array( "fullName" => "Notice of Equitable Service Meeting", "shortName" => "Notice of Equitable Service Meeting", 'date' => "date_notice" ),
        "031" => array( "fullName" => "Notice of Initial Eval and child Assessment", "shortName" => "Notice of Initial Eval and child Assessment", 'date' => "date_notice" ),
        "032" => array( "fullName" => "Notice of Meeting (IFSP)", "shortName" => "Notice of Meeting (IFSP)", 'date' => "date_notice" ),
        "034" => array( "fullName" => "Prior Written Notice", "shortName" => "Prior Written Notice", 'date' => "date_notice" ),
        
        );

    function getFormInfo($formId, $key = 'fullName') {
        if(isset($this->formInfo[$formId])) {
            return $this->formInfo[$formId][$key];
        }
        return false;
    }

    public function __construct($district = null) {
        $this->setDistrict($district);
        $this::init();
    }

    public function init()
    {
        $this->formType = new App_Form_Element_SearchSelect(
            'formType',
            array(
                'label' => 'Form Type:',
                'multiOptions' => array(
                    '' => '--- All Forms Types ---',
                    'PART B FORMS' => array(
                        '029' => "Notice of Meeting",
                        '001' => "Notice and Consent for Initial Evaluation (IEP)",
                        '005' => "Notice and Consent for Initial Placement (IEP)",
                        '002' => "Multidisciplinary Evaluation Team (MDT) Report",
                 //       '003' => "Notification of IEP Meeting",
                        '004' => "Individual Education Program (IEP)",
                        '006' => "Notice and Consent for School Districts Decision",
                        '007' => "Notice and Consent for Reevaluation",
                        '008' => "Notice of Change of Placement",
                        '009' => "Notice of Discontinuation",
                        '010' => "Progress Report",
                        '017' => "Notes Page",
                        '018' => "Summary of Performance",
                        '026' => "Revocation of Consent for Special Education and Related Services",
                        '028' => "Equitable Service Plan",
                        '030' => "Notice of Equitable Service Meeting",
                        '024' => "Agency Consent Invitation",
                        '034' => "Prior Written Notice",
                    ),
                    'OPTIONAL FORMS' => array(
                        '011' => "Notice of MDT Conference",
                        '012' => "Determination Notice",
                        '019' => "Functional Assessment",
                        '020' => "Specialized Transportation",
                        '021' => "Assistive Technology Considerations",
                        '022' => "MDT Data Card",
                        '023' => "IEP Data Card",
                    ),
                    'PART C FORMS' => array(
                        '013' => "IFSP",
                       /* '032' => "Notice of Meeting (IFSP)",
                        * Mike changed this 4-18-2017 jira SRS-50
                        */
                        '032' => "Notice of Meeting (Part C)",
                   //     '014' => "Notification of IFSP Meeting (Outdated)",
                        '016' => "Notice and Consent for Initial Placement (IFSP)",
                        '027' => "Notice and Consent for Early Intervention Initial Screening",
                        '031' => "Notice of Initial Eval and Child Assessment",
                      //  '025' => "Notification Of Multidisciplinary Team Planning Meeting",
                        '015' => "Notice and Consent for Initial Evaluation (IFSP)",
                        '033' => "Annual Transition Notice",
                        
                    ),
                )
            )
        );

        $createForms = $this->getCreateFormsOptions();

        $optionalForms = array(
            '011' => 'use_form_011',
            '012' => 'use_form_012',
            '019' => 'use_form_019',
            '020' => 'use_form_020',
            '021' => 'use_form_021',
        );

        $optionalGroup = array(
            '011' => 'OPTIONAL FORMS',
            '012' => 'OPTIONAL FORMS',
            '019' => 'OPTIONAL FORMS',
            '020' => 'OPTIONAL FORMS',
            '021' => 'OPTIONAL FORMS',
        );

        foreach($optionalForms AS $number => $sql_name) {
            if(!is_null($this->getDistrict())) {
                if (true !== $this->getDistrict()->{$sql_name}) {
                    unset($createForms[$optionalGroup[$number]][$number]);
                }
            }
        }

        $this->formCreateType = new App_Form_Element_SearchSelect(
            'formCreateType',
            array(
                'label' => 'Form Type:',
                'multiOptions' => $createForms,
            )
        );

        $this->student = new App_Form_Element_Hidden('student');
        $this->page = new App_Form_Element_Hidden('page');

        $this->searchStatus = new App_Form_Element_SearchRadio(
            'searchStatus',
            array(
                'label' => 'Status:',
                'multiOptions' => array(
                    '' => 'Show ALL Forms',
                    'current' => 'Show only Current Forms',
                )
            )
        );
        $this->searchStatus->removeDecorator('label');
//		$this->searchStatus->setSeparator(' ');
        $this->searchStatus->setValue('');

        $this->maxRecs = new App_Form_Element_SearchSelect(
            'maxRecs',
            array(
                'label' => 'Records Per Page:',
                'multiOptions' => array(
                    '5' => '5',
                    '10' => '10',
                    '15' => '15',
                    '25' => '25',
                    '50' => '50',
                    '75' => '75',
                    '100' => '100',
                )
            )
        );
        $this->maxRecs->setValue('25');

        $this->archivedOnly = new App_Form_Element_Checkbox('archivedOnly', array('label' => 'Archived Only'));
        $this->archivedOnly->setCheckedValue('1');
        $this->archivedOnly->setAttrib('onchange', '');

        $this->hideSuspended = new App_Form_Element_Checkbox('hideSuspended', array('label' => 'Hide Suspended'));
        $this->hideSuspended->setCheckedValue('1');
        $this->hideSuspended->setAttrib('onchange', '');

        $this->setDecorators(array(array('ViewScript',
            array('viewScript' =>
                'student/search-forms/form-search-form.phtml'))));
    }

    public function addSearchField($fieldName, $belongsTo)
    {
        $this->$fieldName = new App_Form_Element_SearchSelect($fieldName);
        $this->$fieldName->addMultiOptions(
            array(
                'multiOptions' => array(
                    '' => '-- Select Field --',
                    'team_member_last_name' => 'Team Member Last Name',
                )
            )
        )->setBelongsTo($belongsTo);

    }

    public function addSearchValue($fieldName, $belongsTo)
    {
        $this->$fieldName = new App_Form_Element_SearchText($fieldName);
        $this->$fieldName->setBelongsTo($belongsTo);
    }

    /**
     * @return the $_district
     */
    public function getDistrict() {
        return $this->_district;
    }

    /**
     * @param field_type $_district
     */
    public function setDistrict($_district) {
        $this->_district = $_district;
    }

    public function limitFormCreationMenus($id_student) {
        $options = array();
        $student_auth = new App_Auth_StudentAuthenticator();
        $session = new Zend_Session_Namespace('user');
        $access = $student_auth->validateStudentAccess($id_student, $session);

        // should be refactored to not use description.
        if ('Team Member' == $access->description) {
            if ('viewaccess' == $access->access_level) {
                $accessArrayClassName = 'App_Auth_Role_' . str_replace ( ' ', '', $access->description ) . 'View';
                $accessArrayObj = new $accessArrayClassName ();
            } else {
                $accessArrayClassName = 'App_Auth_Role_' . str_replace ( ' ', '', $access->description ) . 'Edit';
                $accessArrayObj = new $accessArrayClassName ();
            }
        } else {
            $accessArrayClassName = 'App_Auth_Role_' . str_replace ( ' ', '', $access->description );
            $accessArrayObj = new $accessArrayClassName ();
        }
        $createForms = $this->getCreateFormsOptions();
//        Zend_Debug::dump(get_class_methods($accessArrayObj));
//        foreach($createForms as $key => $form) {
        $keepForms = array();
        foreach($accessArrayObj as $key => $accessObj) {
            $formNum = substr($key, -3);
            if(intval($formNum) > 0 && isset($accessObj['new']) && true == $accessObj['new']) {
                // keep this item
                $keepForms[] = $formNum;
            }
        }

        $formGroups = array(
            'PART B FORMS',
            'OPTIONAL FORMS',
            'PART C FORMS',
        );

        foreach ($formGroups AS $group) {
            foreach($createForms[$group] as $key => $formName) {
                if('' == $key) {
                    continue;
                }
                if(false === array_search($key, $keepForms)) {
                    unset($createForms[$group][$key]);
                }
            }
        }

        $optionalForms = array(
            '011' => 'use_form_011',
            '012' => 'use_form_012',
            '019' => 'use_form_019',
            '020' => 'use_form_020',
            '021' => 'use_form_021',
        );

        $optionalGroup = array(
            '011' => 'OPTIONAL FORMS',
            '012' => 'OPTIONAL FORMS',
            '019' => 'OPTIONAL FORMS',
            '020' => 'OPTIONAL FORMS',
            '021' => 'OPTIONAL FORMS',
        );

        foreach($optionalForms AS $number => $sql_name) {
            if(!is_null($this->getDistrict())) {
                if (true !== $this->getDistrict()->{$sql_name}) {
                    unset($createForms[$optionalGroup[$number]][$number]);
                }
            }
        }
        $this->formCreateType->setMultiOptions($createForms);
    }

    /**
     * @return array
     */
    private function getCreateFormsOptions()
    {
        $createForms = array(
            '' => '--- All Forms Types ---',
            'PART B FORMS' => array(
                '029' => "Notice of Meeting",
                '001' => "Notice and Consent for Initial Evaluation (IEP)",
                '005' => "Notice and Consent for Initial Placement (IEP)",
                '002' => "Multidisciplinary Evaluation Team (MDT) Report",
                //'003' => "Notification of IEP Meeting",
                '004' => "Individual Education Program (IEP)",
                '006' => "Notice and Consent for School Districts Decision",
                '007' => "Notice and Consent for Reevaluation",
                '008' => "Notice of Change of Placement",
                '009' => "Notice of Discontinuation",
                '010' => "Progress Report",
                '017' => "Notes Page",
                '018' => "Summary of Performance",
                '026' => "Revocation of Consent for Special Education and Related Services",
                '028' => "Equitable Service Plan",
                '030' => "Notice of Equitable Service Meeting",
            ),
            'OPTIONAL FORMS' => array(
                '011' => "Notice of MDT Conference",
                '012' => "Determination Notice",
                '019' => "Functional Assessment",
                '020' => "Specialized Transportation",
                '021' => "Assistive Technology Considerations",
                '022' => "MDT Data Card",
                '023' => "IEP Data Card",
            ),
            'PART C FORMS' => array(
                '013' => "IFSP",
              /* '032' => "Notice of Meeting (IFSP)",
               * Mike changed this 4-18-2017 jira ticket SRS-50
               * 
               */
                '032' => "Notice of Meeting (Part C)",
         //       '014' => "Notification of IFSP Meeting (Outdated)",
                '016' => "Notice and Consent for Initial Placement (IFSP)",
                '027' => "Notice and Consent for Early Intervention Initial Screening",
                '031' => "Notice of Initial Eval and Child Assessment",
         //      '025' => "Notification Of Multidisciplinary Team Planning Meeting",
                '033' => "Annual Transition Notice",
                '034' => "Prior Written Notice",
            ),
        );
        return $createForms;
    }

}
