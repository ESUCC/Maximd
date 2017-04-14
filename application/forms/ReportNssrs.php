<?php

class Form_ReportNssrs extends Form_AbstractForm
{
    protected $nssrsSnapshotDate;
    protected $nssrsSubmissionPeriod;
    protected $octoberCuttoff;

    public function __construct($options = null)
    {
        parent::__construct();

        if(isset($options['nssrsSnapshotDate'])) {
            $this->nssrsSnapshotDate = $options['nssrsSnapshotDate'];
        }
        if(isset($options['nssrsSubmissionPeriod'])) {
            $this->nssrsSubmissionPeriod = $options['nssrsSubmissionPeriod'];
        }
        if(isset($options['octoberCuttoff'])) {
            $this->octoberCuttoff = $options['octoberCuttoff'];
        }
    }

    private function getNssrsTransfer($nssrsTransferId)
    {
        $nssrsTransfersObj = new Model_Table_School();
        $select = $nssrsTransfersObj->select()->where("id_nssrs_transfers = '{$nssrsTransferId['id_nssrs_transfers']}' ")->order(array(
            'timestamp_created desc',
            'id_nssrs_transfers'
        ));
        return $nssrsTransfersObj->fetchRow($select);
    }

    public function buildFormOptions($formData)
    {
        if(isset($formData['field01']) && substr_count($formData['field01'], '-')) {

            $idCounty = substr($formData['field01'], 0, 2);
            $idDistrict = substr($formData['field01'], 3, 4);

            // build school options
            $this->field02->setMultiOptions(Model_Table_School::schoolMultiOtions($idCounty, $idDistrict, false));
            $this->field02->removeDecorator('Label');
        }


        // build primary disability
        $options = array(
            '' => 'Choose...',
            '01' => 'Emotional Disturbance (01)',
            '02' => 'Deaf-Blindness (02)',
            '03' => 'Hearing Impaired (03)',
            '07' => 'Multiple Impairment (07)',
            '08' => 'Orthopedic Impairment (08)',
            '09' => 'Other Health Impairment (09)',
            '10' => 'Specific Learning Disability (10)',
            '11' => 'Speech Language Impairment (11)',
            '12' => 'Visual Impairment (12)',
            '13' => 'Autism (13)',
            '14' => 'Traumatic Brain Injury (14)',
            '15' => 'Developmental Delay (15)',
            '16' => 'Intellectual Disability (16)',
        );
        $this->field11->setMultiOptions($options);

        // build primary disability
        $options = array(
            '' => 'Choose...',
            '1' => 'Occupational Therapy (1)',
            '2' => 'Physical Therapy (2)',
            '3' => 'Speech-Language Therapy (3)',
            '4' => 'Occupational Therapy - Physical Therapy (4)',
            '5' => 'Physical Therapy - Speech-Language Therapy (5)',
            '6' => 'Speech-Language Therapy - Occupational Therapy (6)',
            '7' => 'All (7)',
            '8' => 'None (8)',
        );
        $this->field16->setMultiOptions($options);

        $options = array(
            '1' => 'Yes (1)',
            '2' => 'No (2)',
        );
        $this->field23->setMultiOptions($options);

        $options = array(
            '0' => 'Public School (0)',
            '1' => 'Nonpublic Placement - Parental Placement (2)',
            '2' => 'Nonpublic Placement - Other than Parental Placement (3)',
        );
        $this->field32->setMultiOptions($options);

        $options = array(
            '' => 'Choose...',
            '– BIRTH THRU 3 –' => array(
                '1' => 'Home (1)',
                '2' => 'Community Based (2)',
                '3' => 'Other (3)',
            ),
            '– 3 To 5 Years Old –' => array(
                '5' => 'Separate School (5)',
                '6' => 'Separate Class (6)',
                '7' => 'Residential Facility (7)',
                '8' => 'Home (8)',
                '9' => 'Service Provider Location (9)',
                '16' => 'Regular Early Childhood Program, 10+ h/wk; Services at EC Program (16)',
                '17' => 'Regular Early Childhood Program, 10+ h/wk; Services outside EC Program (17)',
                '18' => 'Regular Early Childhood Program, <10 h/wk; Services at EC Program (18)',
                '19' => 'Regular Early Childhood Program, <10 h/wk; Services outside EC Program (19)',
            ),
            '– 6 to 21 Year Old –' => array(
                '5' => 'Separate School (5)',
                '7' => 'Residential Facility (7)',
                '10' => 'Public School (10)',
                '13' => 'Homebound/Hospital (13)',
                '14' => 'Private School or Exempt (Home) School (14)',
                '15' => 'Correction/Detention Facility (15)',
            ),
        );
        $this->field44->setMultiOptions($options);

        $options = array(
            '' => 'Choose...',
            'Y' => 'Yes (Part B)',
            'N' => 'No (Part C)',
        );
        $this->field47->setMultiOptions($options);

        $options = array(
            '' => 'Choose...',
            '– Birth to 3 Exit Reasons –' => array(
                '1' => 'Transferred to another school district in Nebraska (1)',
                '6' => 'Deceased (6)',
                '9' => 'Withdrawn by parent (9)',
                '12' => 'Completion of the IFSP prior to the age of 3 years old (12)',
                '13' => 'Not eligible for Part B, Exit to other program (13)',
                '14' => 'Not eligible for Part B, Exit with no referral (14)',
                '16' => 'Moved out of state (16)',
                '17' => 'Attempts to contact parents unsuccessful (17)',
            ),
            '– 3 to 21 Year Old Exit Reasons –' => array(
                '2' => 'Returned to full-time regular education program (2)',
                '3' => 'Graduated with a regular high school diploma (3)',
                '4' => 'Received a certificate of completion (4)',
                '5' => 'Reached maximum age (5)',
                '6' => 'Deceased (6)',
                '7' => 'Dropped Out (7)',
                '11' => 'Transferred to another school district OR Moved; known to be continuing (11)',
            ),
        );

        $this->field52->setMultiOptions($options);


        $this->field05->removeDecorator('Label');
        $this->field11->removeDecorator('Label');
        $this->field16->removeDecorator('Label');
        $this->field23->removeDecorator('Label');
        $this->field32->removeDecorator('Label');
        $this->field33->removeDecorator('Label');
        $this->field34->removeDecorator('Label');
        $this->field44->removeDecorator('Label');
        $this->field47->removeDecorator('Label');
        $this->field50->removeDecorator('Label');
        $this->field52->removeDecorator('Label');

    }

    public function buildReport($readonly = true)
    {
        $this->setMethod('post');
        $this->setAttrib('class', $this->getAttrib('class') . ' zend_form');

        $this->field01 = new App_Form_Element_Text('field01', array('label' => 'Field 1 Resident County and District'));
        $this->field01->setAllowEmpty(false);
        $this->field01->setRequired(true);
        $this->field01->addValidator('StringLength', false, array(7, 7));

        $this->field02 = new App_Form_Element_Select('field02', array('label' => 'Field 2 Resident School'));
        $this->field02->setAllowEmpty(false);
        $this->field02->setRequired(true);
        $this->field02->addValidator('StringLength', false, array(3, 3));

        $this->field03 = new App_Form_Element_DatePicker('field03', array('label' => 'Field 3 School Year Date'));
        $this->field03->setAllowEmpty(false);
        $this->field03->setRequired(true);
        $this->field03->addValidator(new My_Validate_NssrsField003($this->nssrsSnapshotDate));

        $this->field05 = new App_Form_Element_Text('field05', array('label' => 'Field 5 NSSRS Id Number'));
        $this->field05->setAllowEmpty(true);
        $this->field05->setRequired(false);
        $this->field05->addValidator('StringLength', false, array(10, 10));

        $this->field06 = new App_Form_Element_Hidden('field06', array('label' => 'Field 6 First Name'));
        $this->field06->setAllowEmpty(false);
        $this->field06->setRequired(true);

        $this->field07 = new App_Form_Element_Hidden('field06', array('label' => 'Field 7 Last Name'));
        $this->field07->setAllowEmpty(false);
        $this->field07->setRequired(true);

        $this->field11 = new App_Form_Element_Select('field11', array('label' => 'Field 11 Primary Disability'));
        $this->field11->setAllowEmpty(false);
        $this->field11->setRequired(true);
        $this->field11->addValidator('Digits', false);

        $this->field16 = new App_Form_Element_Select('field16', array('label' => 'Field 16 Related Services'));
        $this->field16->setAllowEmpty(false);
        $this->field16->setRequired(true);
        $this->field16->addValidator('Digits', true);
        $this->field16->addValidator('Between', false, array(1, 8));

        $this->field23 = new App_Form_Element_Radio('field23', array('label' => 'Field 23 Alternate Assessment'));
        $this->field23->setAllowEmpty(false);
        $this->field23->setRequired(true);
        $this->field23->addValidator('Digits', true);
        $this->field23->addValidator('Between', false, array(1, 2));

        $this->field32 = new App_Form_Element_Radio('field32', array('label' => 'Field 32 Placement Type'));
        $this->field32->setAllowEmpty(false);
        $this->field32->setRequired(true);
        $this->field32->addValidator('Digits', true);
        $this->field32->addValidator('Between', false, array(0, 2));

        $this->field33 = new App_Form_Element_DatePicker('field33', array('label' => 'Field 33 Entry Date (Initial Verification Date)'));
        $this->field33->setAllowEmpty(false);
        $this->field33->setRequired(true);
        $this->field33->addValidator('StringLength', false, array(10, 10));

        $this->field34 = new App_Form_Element_DatePicker('field34', array('label' => 'Field 34 Exit Date'));
        $this->field34->setAllowEmpty(true);
        $this->field34->setRequired(false);
        $this->field34->addValidator(new My_Validate_NssrsField034($this->nssrsSnapshotDate, $this->octoberCuttoff));

        $this->field35 = new App_Form_Element_DatePicker('field35', array('label' => 'Field 35 Snapshot Date'));
        $this->field35->setAllowEmpty(false);
        $this->field35->setRequired(true);
        $this->field35->addValidator(new My_Validate_NssrsField035($this->nssrsSubmissionPeriod));

        $this->field44 = new App_Form_Element_Select('field44', array('label' => 'Field 44 Primary Setting Code'));
        $this->field44->setAllowEmpty(false);
        $this->field44->setRequired(true);
        $this->field44->addValidator(new My_Validate_NssrsField044());

        $this->field47 = new App_Form_Element_Select('field47', array('label' => 'Field 47 School Aged Indicator'));
        $this->field47->setAllowEmpty(false);
        $this->field47->setRequired(true);

        $this->field50 = new App_Form_Element_Text('field50', array('label' => 'Field 50 Special Education Percantage'));
        $this->field50->setAllowEmpty(false);
        $this->field50->setRequired(true);
        $this->field50->addValidator(new My_Validate_NssrsField050());

        $this->field52 = new App_Form_Element_Select('field52', array('label' => 'Field 52 Exit Reason'));
        $this->field52->setAllowEmpty(true);
        $this->field52->setRequired(false);
        $this->field52->addValidator(new My_Validate_NssrsField052());

        $this->addDisplayGroup(array(
            $this->field01,
            $this->field02,
            $this->field03,
            $this->field05,
            $this->field11,
            $this->field16,
            $this->field23,
            $this->field32,
            $this->field33,
            $this->field34,
            $this->field35,
            $this->field44,
            $this->field47,
            $this->field50,
            $this->field52,
        ), 'demo_info');

        foreach($this->getElements() as $element) {
            if(method_exists($element, 'reportDecorators')) {
                $element->reportDecorators();
                if($readonly) {
                    $element->setAttrib('readonly', true);
                }
            }
        }
        return $this;
    }

}
