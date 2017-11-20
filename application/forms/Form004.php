<?php

class Form_Form004 extends Form_AbstractForm
{

    var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function init()
    {
        $this->setEditorType('App_Form_Element_TinyMceTextarea');
    }

    protected function initialize()
    {
        parent::initialize();

        $this->id_form_004 = new App_Form_Element_Hidden('id_form_004');
        $this->id_form_004->ignore = true;

        $this->dob = new App_Form_Element_Hidden('dob');

        $multiOptions = array('testEditor' => 'Existing Editor', 'tinyMce' => 'TinyMce');
        $this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array(
            'label' => 'Editor Type',
            'multiOptions' => $multiOptions
        ));
        $this->form_editor_type->setRequired(false);
        $this->form_editor_type->setAllowEmpty(true);
        $this->form_editor_type->setAttrib(
            'onchange',
            $this->form_editor_type->getAttrib('onchange') . 'setToRefresh();'
        );
        $this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');

    }


    public function view_p1_v1()
    {

        $this->initialize();

        // Setting the decorator for the element to a single, ViewScript, decorator,
        // specifying the viewScript as an option, and some extra options:
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'form004/view_p1_v1.phtml'))));

        $this->parent_names = new App_Form_Element_Text('parent_names');

        $this->date_notice = new App_Form_Element_Text('date_notice');

        $this->iep_date = new App_Form_Element_Text('iep_date');

        $this->options_considered = $this->buildEditor('options_considered');

        $this->return_contact = new App_Form_Element_Text('return_contact');

        $this->return_address = new App_Form_Element_Text('return_address');

        $this->return_city_st_zip = new App_Form_Element_Text('return_city_st_zip');

        $this->district_contact_name_title = new App_Form_Element_Text('district_contact_name_title');

        $this->district_contact = new App_Form_Element_Text('district_contact');

        $this->district_contact_phone = new App_Form_Element_Text('district_contact_phone');

        return $this;
    }

    public function use_fte_report()
    {
        $multiOptions = array(
            'Special Education' => 'Special Education',
            'Special Education with regular Ed Peers' => 'Special Education with regular Ed Peers'
        );
        $this->fte_special_education_time = new App_Form_Element_Radio('fte_special_education_time', array(
            'Label' => 'Special Education Time',
            'multiOptions' => $multiOptions
        ));
        $this->fte_special_education_time->addErrorMessage('Please select an option for Special Education.');
        $this->fte_special_education_time->setAllowEmpty(false);
        $this->fte_special_education_time->removeDecorator('Label');

        $this->fte_qualifying_minutes = new App_Form_Element_Text('fte_qualifying_minutes', array('Label' => 'Qualifying FTE Minutes ='));
        $this->fte_qualifying_minutes->setAttrib('size', '4');
        $this->fte_qualifying_minutes->setAllowEmpty(false);
        $this->fte_qualifying_minutes->setRequired(true);
        $this->fte_qualifying_minutes->addValidator(new Zend_Validate_Digits());
        $this->fte_qualifying_minutes->addFilter('Digits');

        $this->fte_minutes_per_week = new App_Form_Element_Text('fte_minutes_per_week', array('Label' => 'Total Minutes In school per week'));
        $this->fte_minutes_per_week->removeDecorator('Label');
        $this->fte_minutes_per_week->setAttrib('size', '4');
        $this->fte_minutes_per_week->setDecorators(array('viewHelper'));
//        $this->fte_minutes_per_week->setAllowEmpty(false);
//        $this->fte_minutes_per_week->setRequired(true);
        $this->fte_minutes_per_week->addValidator(new Zend_Validate_Digits());
        $this->fte_minutes_per_week->addFilter('Digits');

    }

    public function edit_p1_v1()
    {

        $this->initialize();

        // when using addElementPrefixPath
        // be sure to add elements to the form with $this->addElements(array($doc_signed_parent));
        // otherwise custom classes may not be found.
        $this->addElementPrefixPath('My', 'My/');

        // Setting the decorator for the element to a single, ViewScript, decorator,
        // specifying the viewScript as an option, and some extra options:
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'form004/edit_p1_v1.phtml'))));

        // should be empty if date of parent sig is no
        // should be a valid date if parent sig is yes
        $multiOptions = array('1' => 'Yes', '0' => 'No');
        $this->doc_signed_parent = new App_Form_Element_Radio('doc_signed_parent', array(
            'Label' => 'Parent Signature on file',
            'multiOptions' => $multiOptions
        ));
        $this->doc_signed_parent->addErrorMessage('Please indicate whether the parent signature is on file.');
        $this->doc_signed_parent->setDescription('Parent Signature:');
        $this->doc_signed_parent->addValidator(new My_Validate_BooleanNotEmpty(), true);
        $this->doc_signed_parent->setAllowEmpty(false);
        $this->doc_signed_parent->removeDecorator('Label');

        $this->date_conference = new App_Form_Element_DatePicker('date_conference', array('Label' => 'Conference Date:'));
        $this->date_conference->setAttrib(
            'onchange',
            "modified();colorMeById(this.id);updateDateConference(this.value);"
        );

        $this->effect_from_date = new App_Form_Element_DatePicker('effect_from_date', array('Label' => 'Effect From:'));
        $this->effect_from_date->setAttrib('onchange', "modified();colorMeById(this.id);");
        $this->effect_from_date->removeDecorator('Label');

        $this->effect_to_date = new App_Form_Element_DatePicker('effect_to_date', array('Label' => 'Effect To:'));
        $this->effect_to_date->setAttrib('onchange', "modified();colorMeById(this.id);");
        $this->effect_to_date->removeDecorator('Label');

        $this->date_doc_signed_parent = new App_Form_Element_DatePicker('date_doc_signed_parent', array('Label' => 'Date document signed by parent:'));

        // in order to create a not empty if validator,
        // we have to set the following flags
        // setRequired(false); 
        // setAllowEmpty(false); -- this doesn't make sense, but must be false to force the validator to fire.
        $this->date_doc_signed_parent->setRequired(false);
        $this->date_doc_signed_parent->setAllowEmpty(false);
        $this->date_doc_signed_parent->addValidator(new My_Validate_NotEmptyIf('doc_signed_parent', true));
        $this->date_doc_signed_parent->addValidator(new My_Validate_EmptyIf('doc_signed_parent', false));
        $this->date_doc_signed_parent->addErrorMessage(
            'cannot be empty when Parent Signature is Yes and must be empty when Parent Signature is No.'
        );

        $multiOptions = array('1' => 'Yes', '0' => 'No');
        $this->received_copy = new App_Form_Element_Radio('received_copy', array(
            'Label' => 'Received Copy if IEP',
            'multiOptions' => $multiOptions
        ));
        $this->received_copy->addErrorMessage('Please select Yes or No for the parent receiving a copy of the IEP.');
        $this->received_copy->setDescription('I have received a copy of the IEP at no cost:');
        $this->received_copy->addValidator(new My_Validate_BooleanNotEmptyIf('doc_signed_parent', true), true);
        $this->received_copy->setAllowEmpty(false);
        $this->received_copy->setRequired(false);
        $this->received_copy->removeDecorator('Label');

        $multiOptions = array('1' => 'Yes', '0' => 'No');
        $this->parental_rights = new App_Form_Element_Radio('parental_rights', array(
            'Label' => 'I have been offered a copy of my parental rights at no cost:',
            'multiOptions' => $multiOptions
        ));
        $this->parental_rights->addErrorMessage(
            'Please select Yes or No for the parent receiving a copy of the parental rights at no cost.'
        );
        $this->parental_rights->setDescription('I have been offered a copy of my parental rights at no cost:');
        $this->parental_rights->setAllowEmpty(false);
        $this->parental_rights->setRequired(false);
        $this->parental_rights->removeDecorator('Label');


        $multiOptions = array('1' => 'Yes', '0' => 'No');
        $this->necessary_action = new App_Form_Element_Radio('necessary_action', array(
            'Label' => 'Necessary action:',
            'multiOptions' => $multiOptions
        ));
        $this->necessary_action->setDescription(
            'The school district has taken the necessary action to insure that I understand the proceedings of this IEP conference (including arrangement for an interpreter, if appropriate).'
        );

        if (0) { // changed for email issue from wade
            $this->necessary_action->addValidator(new My_Validate_BooleanNotEmpty(), true);
            $this->necessary_action->setAllowEmpty(false);
        } else {
            $this->necessary_action->addValidator(new My_Validate_BooleanNotEmptyIf('doc_signed_parent', true), true);
            $this->necessary_action->setAllowEmpty(false);
            $this->necessary_action->setRequired(false);

        }

        $this->necessary_action->addErrorMessage('Please select Yes or No for necessary action.');
        $this->necessary_action->removeDecorator('Label');

        $this->no_sig_explanation = new App_Form_Element_Text('no_sig_explanation', array('Label' => "(If No selected above, please explain)"));
        $this->no_sig_explanation->setAttrib('size', '26');
        $this->no_sig_explanation->setRequired(false);
        $this->no_sig_explanation->setAllowEmpty(false);
        $this->no_sig_explanation->addValidator(new My_Validate_NotEmptyIf('doc_signed_parent', false));
        $this->no_sig_explanation->addValidator(new My_Validate_EmptyIf('doc_signed_parent', true));
        $this->no_sig_explanation->addErrorMessage(
            'cannot be empty when Parent Signature is No and must be empty when Parent Signature is Yes.'
        );


        $multiOptions = array('1' => 'Yes', '0' => 'No');
        $this->absences_approved = new App_Form_Element_Radio('absences_approved', array(
            'Label' => 'Team Member Absence Approval',
            'description' => "Team Member Absences",
            'multiOptions' => $multiOptions
        ));
        $this->absences_approved->addErrorMessage('Please indicate whether the Team Member Absences are approved.');
        $this->absences_approved->setRequired(false);
        $this->absences_approved->setAllowEmpty(false);
        $this->absences_approved->removeDecorator('Label');

        // complex validator needed here...this should be required if any absence checkbox is checked
        $this->absences_approved->addValidator(new My_Validate_IepCustomAbsent('team_member', 'absent'));


        /*
         * LPS Fields
         */
        if ($this->lps) {

            $multiOptions = array('1' => 'Yes', '0' => 'No');
            $this->lps_sig_understand_process = new App_Form_Element_Radio('lps_sig_understand_process', array(
                'Label' => 'Understand process:',
                'multiOptions' => $multiOptions
            ));
            $this->lps_sig_understand_process->setDescription(
                'I/We understand the Individual Education Program (IEP) process:'
            );
            $this->lps_sig_understand_process->addValidator(
                new My_Validate_BooleanNotEmptyIf('doc_signed_parent', true),
                true
            );
            $this->lps_sig_understand_process->setAllowEmpty(false);
            $this->lps_sig_understand_process->setRequired(false);
            $this->lps_sig_understand_process->addErrorMessage('Understand process must be checked.');
            $this->lps_sig_understand_process->removeDecorator('Label');


            $multiOptions = array('1' => 'Yes', '0' => 'No');
            $this->lps_sig_participated = new App_Form_Element_Radio('lps_sig_participated', array(
                'Label' => 'Participated in development:',
                'multiOptions' => $multiOptions
            ));
            $this->lps_sig_participated->setDescription('I/We participated in the development of this IEP:');
            $this->lps_sig_participated->addValidator(
                new My_Validate_BooleanNotEmptyIf('doc_signed_parent', true),
                true
            );
            $this->lps_sig_participated->setAllowEmpty(false);
            $this->lps_sig_participated->setRequired(false);
            $this->lps_sig_participated->addErrorMessage('Participated in development must be checked.');
            $this->lps_sig_participated->removeDecorator('Label');

            $multiOptions = array('1' => 'Yes', '0' => 'No');
            $this->lps_sig_agree = new App_Form_Element_Radio('lps_sig_agree', array(
                'Label' => 'Agreement to IEP:',
                'multiOptions' => $multiOptions
            ));
            $this->lps_sig_agree->setDescription('I/We agree to this IEP:');
            $this->lps_sig_agree->addValidator(new My_Validate_BooleanNotEmptyIf('doc_signed_parent', true), true);
            $this->lps_sig_agree->setAllowEmpty(false);
            $this->lps_sig_agree->setRequired(false);
            $this->lps_sig_agree->addErrorMessage('Agreement to IEP must be checked.');
            $this->lps_sig_agree->removeDecorator('Label');

            $multiOptions = array('1' => 'Yes', '0' => 'No');
            $this->lps_sig_no_agree_reason = $this->buildEditor(
                'lps_sig_no_agree_reason',
                array('Label' => 'Necessary action:', 'multiOptions' => $multiOptions)
            );
            $this->lps_sig_no_agree_reason->removeEditorEmptyValidator();
            $this->lps_sig_no_agree_reason->addValidator(new My_Validate_NotEmptyIfContains('lps_sig_agree', 0));
            $this->lps_sig_no_agree_reason->setDescription('Necessary Action');
            $this->lps_sig_no_agree_reason->setRequired(false);
            $this->lps_sig_no_agree_reason->setAllowEmpty(false);
            $this->lps_sig_no_agree_reason->addErrorMessage('Non-agreement with IEP must be explained.');
            $this->lps_sig_no_agree_reason->removeDecorator('Label');

            $multiOptions = array('1' => 'Yes', '0' => 'No');
            $this->lps_sig_understand_purpose = new App_Form_Element_Radio('lps_sig_understand_purpose', array(
                'Label' => 'Understand purpose and content:',
                'multiOptions' => $multiOptions
            ));
            $this->lps_sig_understand_purpose->setDescription('I/We understand the purpose and content of this IEP:');
            $this->lps_sig_understand_purpose->addValidator(
                new My_Validate_BooleanNotEmptyIf('doc_signed_parent', true),
                true
            );
            $this->lps_sig_understand_purpose->setAllowEmpty(false);
            $this->lps_sig_understand_purpose->setRequired(false);
            $this->lps_sig_understand_purpose->addErrorMessage('Understand purpose and content must be checked.');
            $this->lps_sig_understand_purpose->removeDecorator('Label');

            $multiOptions = array('1' => 'Yes', '0' => 'No');
            $this->lps_sig_understand_covers = new App_Form_Element_Radio('lps_sig_understand_covers', array(
                'Label' => 'Understand coverage:',
                'multiOptions' => $multiOptions
            ));
            $this->lps_sig_understand_covers->setDescription('Normal School Calendar');
            $this->lps_sig_understand_covers->addValidator(
                new My_Validate_BooleanNotEmptyIf('doc_signed_parent', true),
                true
            );
            $this->lps_sig_understand_covers->setAllowEmpty(false);
            $this->lps_sig_understand_covers->setRequired(false);
            $this->lps_sig_understand_covers->addErrorMessage(
                'Understand coverage is limited to normal school calendar must be checked.'
            );
            $this->lps_sig_understand_covers->removeDecorator('Label');

            $multiOptions = array('1' => 'Yes', '0' => 'No');
            $this->lps_sig_understand_receive = new App_Form_Element_Radio('lps_sig_understand_receive', array(
                'Label' => 'Understand that parents will receive a copy of the IEP:',
                'multiOptions' => $multiOptions
            ));
            $this->lps_sig_understand_receive->setDescription(
                'I/We understand that I/we will receive a copy of this IEP:'
            );
            $this->lps_sig_understand_receive->addValidator(
                new My_Validate_BooleanNotEmptyIf('doc_signed_parent', true),
                true
            );
            $this->lps_sig_understand_receive->setAllowEmpty(false);
            $this->lps_sig_understand_receive->setRequired(false);
            $this->lps_sig_understand_receive->addErrorMessage(
                'Understand that parents will receive a copy of the IEP must be checked.'
            );
            $this->lps_sig_understand_receive->removeDecorator('Label');

            $multiOptions = array('1' => 'Yes', '0' => 'No');
            $this->lps_sig_received_rights = new App_Form_Element_Radio('lps_sig_received_rights', array(
                'Label' => 'Have received a copy of Rights and Responsibilities:',
                'multiOptions' => $multiOptions
            ));
            $this->lps_sig_received_rights->removeDecorator('Label');
            $this->lps_sig_received_rights->setDescription(
                'I/We have received a copy of Rights and Responsibilities Regarding Identification and Placement of Students in Special Education:'
            );
            $this->lps_sig_received_rights->addValidator(
                new My_Validate_BooleanNotEmptyIf('doc_signed_parent', true),
                true
            );
            $this->lps_sig_received_rights->setAllowEmpty(false);
            $this->lps_sig_received_rights->setRequired(false);
            $this->lps_sig_received_rights->addErrorMessage(
                'Have received a copy of Rights and Responsibilities must be checked.'
            );

            $multiOptions = array('1' => 'Yes', '0' => 'No');
            $this->recieve_electronic_copy = new App_Form_Element_Radio('recieve_electronic_copy', array(
                'Label' => 'I would like to receive an electronic copy of my child\'s IEP:',
                'multiOptions' => $multiOptions
            ));
            $this->recieve_electronic_copy->removeDecorator('Label');
            $this->recieve_electronic_copy->setDescription(
                'I would like to receive an electronic copy of my child\'s IEP:'
            );
//			$this->recieve_electronic_copy->addValidator(new My_Validate_BooleanNotEmptyIf('doc_signed_parent', true), true);
            $this->recieve_electronic_copy->setAllowEmpty(false);
            $this->recieve_electronic_copy->setRequired(false);
            $this->recieve_electronic_copy->addErrorMessage(
                'Have received a copy of Rights and Responsibilities must be checked.'
            );

        }
        return $this;
    }

    public function edit_p1_v2()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v3()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v4()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v5()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v6()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v9()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v10()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p1_v11()
    {
        return $this->edit_p1_v1();
    }

    public function edit_p2_v2()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v3()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v4()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v5()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v6()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v7()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v8()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v9()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v10()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v11()
    {
        return $this->edit_p2_v1();
    }

    public function edit_p2_v1()
    {

        $this->initialize();

        // Setting the decorator for the element to a single, ViewScript, decorator,
        // specifying the viewScript as an option, and some extra options:
        $this->setDecorators(
            array(
                array(
                    'ViewScript',
                    array(
                        'viewScript' => 'form004/edit_p2_v1.phtml'
                    )
                )
            )
        );


        $this->student_strengths = $this->buildEditor('student_strengths', array('Label' => 'Student\'s strengths:'));
        $this->student_strengths->addErrorMessage(
            'Student\'s strengths is empty. If the item does not apply to this student, please explain why.'
        );

        $this->parental_concerns = $this->buildEditor('parental_concerns', array('Label' => 'Parental information:'));
        $this->parental_concerns->addErrorMessage(
            'Parental information is empty. If the item does not apply to this student, please explain why.'
        );

        $this->results_evaluation = $this->buildEditor(
            'results_evaluation',
            array('Label' => 'Results of initial or recent evaluation:')
        );
        $this->results_evaluation->addErrorMessage(
            'Results of initial or recent evaluation(s) is empty. If the item does not apply to this student, please explain why.'
        );

        $this->results_perf = $this->buildEditor(
            'results_perf',
            array('Label' => 'Performance on any general state and district-wide assessments:')
        );
        $this->results_perf->addErrorMessage(
            'Performance on any general state and district-wide assessments is empty. If the item does not apply to this student, please explain why.'
        );


        $this->behavioral_strategies = $this->buildEditor(
            'behavioral_strategies',
            array('Label' => 'Consideration of appropriate behavioral strategies:')
        );
        $this->behavioral_strategies->addErrorMessage(
            'Consideration of appropriate behavioral strategies is empty. If the item does not apply to this student, please explain why.'
        )
            ->addValidator(new My_Validate_NotEmptyIfContains('behavioral_strategies_checkbox', array('', 0)))
            ->setAllowEmpty(false)
            ->setRequired(false);


        // checkboxes have no validation
        $this->behavioral_strategies_checkbox = new App_Form_Element_Checkbox('behavioral_strategies_checkbox', array('Label' => 'Considered but not necessary'));
        $this->behavioral_strategies_checkbox->getDecorator('Label')->setOption('placement', 'append');
        $this->behavioral_strategies_checkbox->setAttrib(
            'onclick',
            "consideredButNotNecessary('behavioral_strategies', this.checked);"
        );


        $this->language_needs = $this->buildEditor(
            'language_needs',
            array('Label' => 'Consideration of language needs:')
        );
        $this->language_needs->addErrorMessage(
            'Consideration of language needs is empty. If the item does not apply to this student, please explain why.'
        )
            ->addValidator(new My_Validate_NotEmptyIfContains('language_needs_checkbox', array('', 0)))
            ->setAllowEmpty(false)
            ->setRequired(false);

        $this->language_needs_checkbox = new App_Form_Element_Checkbox('language_needs_checkbox', array('Label' => 'Considered but not necessary'));
        $this->language_needs_checkbox->getDecorator('Label')->setOption('placement', 'append');
        $this->language_needs_checkbox->setAttrib(
            'onclick',
            "consideredButNotNecessary('language_needs', this.checked);"
        );


        $this->braille_instruction = $this->buildEditor(
            'braille_instruction',
            array('Label' => 'Braille instruction:')
        );
        $this->braille_instruction->addErrorMessage(
            'Braille instruction is empty. If the item does not apply to this student, please explain why.'
        )
            ->addValidator(new My_Validate_NotEmptyIfContains('braille_instruction_checkbox', array('', 0)))
            ->setAllowEmpty(false)
            ->setRequired(false);

        $this->braille_instruction_checkbox = new App_Form_Element_Checkbox('braille_instruction_checkbox', array('Label' => 'Considered but not necessary'));
        $this->braille_instruction_checkbox->getDecorator('Label')->setOption('placement', 'append');
        $this->braille_instruction_checkbox->setAttrib(
            'onclick',
            "consideredButNotNecessary('braille_instruction', this.checked);"
        );


        $this->comm_needs = $this->buildEditor('comm_needs', array('Label' => 'Communication needs:'));
        $this->comm_needs->addErrorMessage(
            'Communication needs is empty. If the item does not apply to this student, please explain why.'
        )
            ->addValidator(new My_Validate_NotEmptyIfContains('comm_needs_checkbox', array('', 0)))
            ->setAllowEmpty(false)
            ->setRequired(false);

        $this->comm_needs_checkbox = new App_Form_Element_Checkbox('comm_needs_checkbox', array('Label' => 'Considered but not necessary'));
        $this->comm_needs_checkbox->getDecorator('Label')->setOption('placement', 'append');
        $this->comm_needs_checkbox->setAttrib('onclick', "consideredButNotNecessary('comm_needs', this.checked);");


        $this->deaf_comm_needs = $this->buildEditor('deaf_comm_needs', array('Label' => 'Communication needs:'));
        $this->deaf_comm_needs->addErrorMessage(
            'Communication needs for children who are deaf or hard of hearing is empty. If the item does not apply to this student, please explain why.'
        )
            ->addValidator(new My_Validate_NotEmptyIfContains('deaf_comm_needs_checkbox', array('', 0)))
            ->setAllowEmpty(false)
            ->setRequired(false);

        $this->deaf_comm_needs_checkbox = new App_Form_Element_Checkbox('deaf_comm_needs_checkbox', array('Label' => 'Considered but not necessary'));
        $this->deaf_comm_needs_checkbox->getDecorator('Label')->setOption('placement', 'append');
        $this->deaf_comm_needs_checkbox->setAttrib(
            'onclick',
            "consideredButNotNecessary('deaf_comm_needs', this.checked);"
        );


        $this->deaf_comm_opp = $this->buildEditor(
            'deaf_comm_opp',
            array('Label' => 'Opportunities for direct communication:')
        );
        $this->deaf_comm_opp->addErrorMessage(
            'Opportunities for direct communication for children who are deaf or hard of hearing is empty. If the item does not apply to this student, please explain why.'
        )
            ->addValidator(new My_Validate_NotEmptyIfContains('deaf_comm_opp_checkbox', array('', 0)))
            ->setAllowEmpty(false)
            ->setRequired(false);

        $this->deaf_comm_opp_checkbox = new App_Form_Element_Checkbox('deaf_comm_opp_checkbox', array('Label' => 'Considered but not necessary'));
        $this->deaf_comm_opp_checkbox->getDecorator('Label')->setOption('placement', 'append');
        $this->deaf_comm_opp_checkbox->setAttrib(
            'onclick',
            "consideredButNotNecessary('deaf_comm_opp', this.checked);"
        );


        $this->deaf_academic_lev = $this->buildEditor(
            'deaf_academic_lev',
            array('Label' => 'Opportunities for direct instruction:')
        );
        $this->deaf_academic_lev->addErrorMessage(
            'Opportunities for direct instruction for children who are deaf or hard of hearing is empty. If the item does not apply to this student, please explain why.'
        )
            ->addValidator(new My_Validate_NotEmptyIfContains('deaf_academic_lev_checkbox', array('', 0)))
            ->setAllowEmpty(false)
            ->setRequired(false);

        $this->deaf_academic_lev_checkbox = new App_Form_Element_Checkbox('deaf_academic_lev_checkbox', array('Label' => 'Considered but not necessary'));
        $this->deaf_academic_lev_checkbox->getDecorator('Label')->setOption('placement', 'append');
        $this->deaf_academic_lev_checkbox->setAttrib(
            'onclick',
            "consideredButNotNecessary('deaf_academic_lev', this.checked);"
        );


        $this->assistive_tech = $this->buildEditor(
            'assistive_tech',
            array('Label' => "Consideration of child's need for assistive technology:")
        );
        $this->assistive_tech->addErrorMessage(
            "Consideration of child's need for assistive technology is empty. If the item does not apply to this student, please explain why."
        )
            ->addValidator(new My_Validate_NotEmptyIfContains('assistive_tech_checkbox', array('', 0)))
            ->setAllowEmpty(false)
            ->setRequired(false);

        $this->assistive_tech_checkbox = new App_Form_Element_Checkbox('assistive_tech_checkbox', array('Label' => 'Considered but not necessary'));
        $this->assistive_tech_checkbox->getDecorator('Label')->setOption('placement', 'append');
        $this->assistive_tech_checkbox->setAttrib(
            'onclick',
            "consideredButNotNecessary('assistive_tech', this.checked);"
        );
        return $this;
    }

    public function edit_p3_v2()
    {
        return $this->edit_p3_v1();
    }

    public function edit_p3_v3()
    {
        return $this->edit_p3_v1();
    }

    public function edit_p3_v4()
    {
        return $this->edit_p3_v1();
    }

    public function edit_p3_v5()
    {
        return $this->edit_p3_v1();
    }

    public function edit_p3_v6()
    {
        return $this->edit_p3_v1();
    }

    public function edit_p3_v7()
    {
        return $this->edit_p3_v1();
    }

    public function edit_p3_v8()
    {
        return $this->edit_p3_v1();
    }

    public function edit_p3_v9()
    {
        return $this->edit_p3_v1();
    }

    public function edit_p3_v10()
    {
        return $this->edit_p3_v1();
    }

    public function edit_p3_v11()
    {
        return $this->edit_p3_v1();
    }

    public function edit_p3_v1()
    {

        $this->initialize();

        $this->setDecorators(
            array(
                array(
                    'ViewScript',
                    array(
                        'viewScript' => 'form004/edit_p3_v1.phtml'
                    )
                )
            )
        );

        $this->present_lev_perf = $this->buildEditor(
            'present_lev_perf',
            array('Label' => 'Present level of performance:')
        );
        $this->present_lev_perf->addErrorMessage(
            'Present level of performance is empty. If the item does not apply to this student, please explain why.'
        );

        $this->pdf_filepath_present_lev_perf = new App_Form_Element_Hidden('pdf_filepath_present_lev_perf', array('Label' => 'pdf_filepath_present_lev_perf:'));
        
        $this->addressed_physical_education = new App_Form_Element_Checkbox('addressed_physical_education', array('Label' => 'Please check to confirm that you have addressed the student\'s needs in the area of Physical Education'));
        $this->addressed_physical_education->setRequired(true);
        $this->addressed_physical_education->setAllowEmpty(false);
        $this->addressed_physical_education->setCheckedValue('t');
        $this->addressed_physical_education->setUncheckedValue(NULL);
        $this->addressed_physical_education->addErrorMessage(
            'Checkbox regarding Physical Education must be checked.'
        );

        return $this;
    }

    public function edit_p4_v2()
    {
        return $this->edit_p4_v1();
    }

    public function edit_p4_v3()
    {
        return $this->edit_p4_v1();
    }

    public function edit_p4_v4()
    {
        return $this->edit_p4_v1();
    }

    public function edit_p4_v5()
    {
        return $this->edit_p4_v1();
    }

    public function edit_p4_v6()
    {
        return $this->edit_p4_v1();
    }

    public function edit_p4_v7()
    {
        return $this->edit_p4_v1();
    }

    public function edit_p4_v8()
    {
        return $this->edit_p4_v1();
    }

    public function edit_p4_v9()
    {
        return $this->edit_p4_v1();
    }

    public function edit_p4_v10()
    {
        return $this->edit_p4_v1();
    }

    public function edit_p4_v11()
    {
        return $this->edit_p4_v1();
    }

    public function edit_p4_v1()
    {

        $this->initialize();

        $this->setDecorators(
            array(
                array(
                    'ViewScript',
                    array(
                        'viewScript' => 'form004/edit_p4_v1.phtml'
                    )
                )
            )
        );

        // resuired for restore schedule dates
        $this->date_conference = new App_Form_Element_DatePicker('date_conference', array('Label' => 'Conference Date:'));
        $this->date_conference->setAttrib('style', 'display:none;');
        $this->date_conference->removeDecorator('label');
        return $this;
    }

    public function edit_p5_v2()
    {
        return $this->edit_p5_v1();
    }

    public function edit_p5_v3()
    {
        return $this->edit_p5_v1();
    }

    public function edit_p5_v4()
    {
        return $this->edit_p5_v1();
    }

    public function edit_p5_v5()
    {
        return $this->edit_p5_v1();
    }

    public function edit_p5_v6()
    {
        return $this->edit_p5_v1();
    }

    public function edit_p5_v7()
    {
        return $this->edit_p5_v1();
    }

    public function edit_p5_v8()
    {
        return $this->edit_p5_v1();
    }

    public function edit_p5_v9()
    {
        return $this->edit_p5_v1();
    }

    public function edit_p5_v10()
    {
        return $this->edit_p5_v1();
    }

    public function edit_p5_v11()
    {
        return $this->edit_p5_v1();
    }

    public function edit_p5_v1()
    {

        $this->initialize();

        $view = $this->page->getView();

        $this->setDecorators(
            array(
                array(
                    'ViewScript',
                    array(
                        'viewScript' => 'form004/edit_p5_v1.phtml'
                    )
                )
            )
        );

        $this->effect_to_date = new App_Form_Element_Hidden('effect_to_date', array('Label' => 'Effect To:'));
        $this->effect_to_date->ignore = true;;

        $this->transition_secgoals = new App_Form_Element_Checkbox('transition_secgoals', array('Label' => 'The above goal(s) include education/training, employment, and when appropriate independent living goals.:'));
        $this->transition_secgoals->addErrorMessage('Post Secondary Goals checkbox has not been checked.');
        $this->transition_secgoals->setRequired(false);
        $this->transition_secgoals->setAllowEmpty(false);
        $this->transition_secgoals->addValidator(new My_Validate_IepTransition('dob', 'effect_to_date', 'boolean'));

        $this->transition_16_course_study = $this->buildEditor('transition_16_course_study');
        $this->transition_16_course_study->setLabel('Course of Study:');
        $this->transition_16_course_study->addErrorMessage(
            'Course of Study is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_16_course_study->setRequired(false);
        $this->transition_16_course_study->setAllowEmpty(false);
        $this->transition_16_course_study->removeEditorEmptyValidator();
        $this->transition_16_course_study->addValidator(new My_Validate_IepTransition('dob', 'effect_to_date'));
        $this->transition_16_course_study->addValidator(new My_Validate_EditorNotEmptyIf('transition_plan', 't'));

        $this->transition_16_instruction = $this->buildEditor('transition_16_instruction');
        $this->transition_16_instruction->setLabel('Instruction:');
        $this->transition_16_instruction->addErrorMessage(
            'Instruction is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_16_instruction->setRequired(false);
        $this->transition_16_instruction->setAllowEmpty(false);
        $this->transition_16_instruction->removeEditorEmptyValidator();
        $this->transition_16_instruction->addValidator(new My_Validate_IepTransition('dob', 'effect_to_date'));
        $this->transition_16_instruction->addValidator(new My_Validate_EditorNotEmptyIf('transition_plan', 't'));

        $this->transition_16_rel_services = $this->buildEditor('transition_16_rel_services');
        $this->transition_16_rel_services->setLabel('Related services:');
        $this->transition_16_rel_services->addErrorMessage(
            'Related services is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_16_rel_services->setRequired(false);
        $this->transition_16_rel_services->setAllowEmpty(false);
        $this->transition_16_rel_services->setDescription(
            '(i.e. transportation for community activities, job coaching, assistive technology, assistance to participate in community activities, include planning for related post-school needs)'
        );
        $this->transition_16_rel_services->removeEditorEmptyValidator();
        $this->transition_16_rel_services->addValidator(new My_Validate_IepTransition('dob', 'effect_to_date'));
        $this->transition_16_rel_services->addValidator(new My_Validate_EditorNotEmptyIf('transition_plan', 't'));

        $this->transition_16_comm_exp = $this->buildEditor('transition_16_comm_exp');
        $this->transition_16_comm_exp->setLabel('Community experiences:');
        $this->transition_16_comm_exp->setDescription(
            '(i.e. leisure, recreation and socialization activities, accessing transportation, volunteer work and civic responsibilities)'
        );
        $this->transition_16_comm_exp->addErrorMessage(
            'Community experiences is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_16_comm_exp->setRequired(false);
        $this->transition_16_comm_exp->setAllowEmpty(false);
        $this->transition_16_comm_exp->removeEditorEmptyValidator();
        $this->transition_16_comm_exp->addValidator(new My_Validate_IepTransition('dob', 'effect_to_date'));
        $this->transition_16_comm_exp->addValidator(new My_Validate_EditorNotEmptyIf('transition_plan', 't'));

        $this->transition_16_emp_options = $this->buildEditor('transition_16_emp_options');
        $this->transition_16_emp_options->setLabel('Development of employment:');
        $this->transition_16_emp_options->addErrorMessage(
            'Development of employment is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_16_emp_options->setRequired(false);
        $this->transition_16_emp_options->setAllowEmpty(false);
        $this->transition_16_emp_options->removeEditorEmptyValidator();
        $this->transition_16_emp_options->addValidator(new My_Validate_IepTransition('dob', 'effect_to_date'));
        $this->transition_16_emp_options->addValidator(new My_Validate_EditorNotEmptyIf('transition_plan', 't'));

        $this->transition_16_dly_liv_skills = $this->buildEditor('transition_16_dly_liv_skills');
        $this->transition_16_dly_liv_skills->setLabel('Daily living skills:');
        $this->transition_16_dly_liv_skills->setDescription(
            '(i.e. grooming, meal preparation, money management, laundry, maintaining residence)'
        );
        $this->transition_16_dly_liv_skills->addErrorMessage(
            'Daily living skills is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_16_dly_liv_skills->setRequired(false);
        $this->transition_16_dly_liv_skills->setAllowEmpty(false);
        $this->transition_16_dly_liv_skills->removeEditorEmptyValidator();
        $this->transition_16_dly_liv_skills->addValidator(new My_Validate_IepTransition('dob', 'effect_to_date'));
        $this->transition_16_dly_liv_skills->addValidator(new My_Validate_EditorNotEmptyIf('transition_plan', 't'));

        $this->transition_16_func_voc_eval = $this->buildEditor('transition_16_func_voc_eval');
        $this->transition_16_func_voc_eval->setLabel('Functional vocational evaluation:');
        $this->transition_16_func_voc_eval->setDescription(
            '(i.e. assessment process to identify interests, skills, and needs, usually through situational assessment/observation)'
        );
        $this->transition_16_func_voc_eval->addErrorMessage(
            'Functional vocational evaluation is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_16_func_voc_eval->setRequired(false);
        $this->transition_16_func_voc_eval->setAllowEmpty(false);
        $this->transition_16_func_voc_eval->removeEditorEmptyValidator();
        $this->transition_16_func_voc_eval->addValidator(new My_Validate_IepTransition('dob', 'effect_to_date'));
        $this->transition_16_func_voc_eval->addValidator(new My_Validate_EditorNotEmptyIf('transition_plan', 't'));

        $this->transition_16_inter_agency_link = $this->buildEditor('transition_16_inter_agency_link');
        $this->transition_16_inter_agency_link->setLabel('Interagency linkages and responsibilities:');
        $this->transition_16_inter_agency_link->addErrorMessage(
            'Interagency linkages and responsibilities is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_16_inter_agency_link->setRequired(false);
        $this->transition_16_inter_agency_link->setAllowEmpty(false);
        $this->transition_16_inter_agency_link->removeEditorEmptyValidator();
        $this->transition_16_inter_agency_link->addValidator(new My_Validate_IepTransition('dob', 'effect_to_date'));
        $this->transition_16_inter_agency_link->addValidator(new My_Validate_EditorNotEmptyIf('transition_plan', 't'));

        $this->transition_activity1 = $this->buildEditor('transition_activity1');
        $this->transition_activity1->setLabel('Transition activities:');
        $this->transition_activity1->addErrorMessage(
            'Transition activities is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_activity1->setRequired(false);
        $this->transition_activity1->setAllowEmpty(false);
        $this->transition_activity1->removeEditorEmptyValidator();
        $this->transition_activity1->addValidator(new My_Validate_IepTransition('dob', 'effect_to_date'));
        $this->transition_activity1->addValidator(new My_Validate_EditorNotEmptyIf('transition_plan', 't'));
        $this->transition_activity1->setAttrib(
            'style',
            $this->transition_activity1->getAttrib('style') . 'width:450px;'
        ); //override width style


        $this->transition_activity2 = $this->buildEditor('transition_activity2');
        $this->transition_activity2->setLabel('Interagency linkages and responsibilities:');
        $this->transition_activity2->removeEditorEmptyValidator();
        $this->transition_activity2->setAllowEmpty(true);
        $this->transition_activity2->setRequired(false);
        $this->transition_activity2->setAttrib(
            'style',
            $this->transition_activity2->getAttrib('style') . 'width:450px;'
        ); //override width style

        $this->transition_activity3 = $this->buildEditor('transition_activity3');
        $this->transition_activity3->setLabel('Interagency linkages and responsibilities:');
        $this->transition_activity3->addErrorMessage(
            'Interagency linkages and responsibilities is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_activity3->removeEditorEmptyValidator();
        $this->transition_activity3->setAllowEmpty(true);
        $this->transition_activity3->setRequired(false);
        $this->transition_activity3->setAttrib(
            'style',
            $this->transition_activity3->getAttrib('style') . 'width:450px;'
        ); //override width style

        $this->transition_agency1 = new App_Form_Element_Text('transition_agency1', array('Label' => 'Agency responsible:'));
        $this->transition_agency1->addErrorMessage(
            'Agency responsible is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_agency1->removeDecorator('Label');
        $this->transition_agency1->setRequired(false);
        $this->transition_agency1->setAllowEmpty(false);
        $this->transition_agency1->addValidator(new My_Validate_IepTransition('dob', 'effect_to_date'));
        $this->transition_agency1->addValidator(new My_Validate_NotEmptyIf('transition_plan', 't'));

        $this->transition_agency2 = new App_Form_Element_Text('transition_agency2', array('Label' => 'Agency responsible 2:'));
        $this->transition_agency2->addErrorMessage(
            'Agency responsible is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_agency2->setAllowEmpty(true);
        $this->transition_agency2->setRequired(false);
        $this->transition_agency2->removeDecorator('Label');

        $this->transition_agency3 = new App_Form_Element_Text('transition_agency3', array('Label' => 'Agency responsible 3:'));
        $this->transition_agency3->addErrorMessage(
            'Agency responsible is empty. If the item does not apply to this student, please explain why.'
        );
        $this->transition_agency3->setAllowEmpty(true);
        $this->transition_agency3->setRequired(false);
        $this->transition_agency3->removeDecorator('Label');

        $this->transition_date1 = new App_Form_Element_DatePicker('transition_date1', array('Label' => 'Transition Date 1:'));
        $this->transition_date1->addErrorMessage('Transition Date 1 is empty.');
        $this->transition_date1->removeDecorator('Label');
        $this->transition_date1->setRequired(false);
        $this->transition_date1->setAllowEmpty(false);
        $this->transition_date1->addValidator(new My_Validate_IepTransition('dob', 'effect_to_date'));
        $this->transition_date1->addValidator(new My_Validate_NotEmptyIf('transition_plan', 't'));

        $this->transition_date2 = new App_Form_Element_DatePicker('transition_date2', array('Label' => 'Transition Date 2:'));
        $this->transition_date2->setAllowEmpty(true);
        $this->transition_date2->setRequired(false);
        $this->transition_date2->removeDecorator('Label');

        $this->transition_date3 = new App_Form_Element_DatePicker('transition_date3', array('Label' => 'Transition Date 3:'));
        $this->transition_date3->setAllowEmpty(true);
        $this->transition_date3->setRequired(false);
        $this->transition_date3->removeDecorator('Label');

        $this->transition_plan = new App_Form_Element_Checkbox('transition_plan');
        $this->transition_plan->setAttrib('onclick', 'enableDisableTransitionPlan(this.checked);');
        $this->transition_plan->setDecorators(array('viewHelper')); // override standard decorators
        $this->transition_plan->setCheckedValue('t');
        $this->transition_plan->setUncheckedValue('f');

        return $this;
    }


    public function edit_p6_v2()
    {
        return $this->edit_p6_v1();
    }

    public function edit_p6_v3()
    {
        return $this->edit_p6_v1();
    }

    public function edit_p6_v4()
    {
        return $this->edit_p6_v1();
    }

    public function edit_p6_v5()
    {
        return $this->edit_p6_v1();
    }

    public function edit_p6_v6()
    {
        return $this->edit_p6_v1();
    }

    public function edit_p6_v7()
    {
        return $this->edit_p6_v1();
    }

    public function edit_p6_v8()
    {
        return $this->edit_p6_v1();
    }

    public function edit_p6_v9()
    {
        return $this->edit_p6_v1();
    }

    public function edit_p6_v10()
    {
        return $this->edit_p6_v1();
    }

    public function edit_p6_v11()
    {
        $this->edit_p6_v1();

        /**
         * override view script
         */
        $this->setDecorators(
            array(
                array(
                    'ViewScript',
                    array(
                        'viewScript' => 'form004/edit_p6_v11.phtml'
                    )
                )
            )
        );

        $this->getElement('primary_service_calendar')->setLabel('Does Service Follow School Calendar');
        $this->removeElement('primary_service_mpy');

        $multiOptions = array(
            '' => "No option selected",
            '1' => "I give CONSENT to the public school district named herein to (a) disclose my child’s personally identifiable information to the State agency responsible for administering my State’s Public Benefits or Insurance Program Under State and Federal law, including IDEA and FERPA, and (b) access Medicaid funding on behalf of my child (named above) and understand that I may withdraw this consent at any time upon written notice to the public school district.",
            '0' => "I REFUSE to give consent to the public school district to (a) disclose my child’s personally identifiable information to the State agency responsible for administering my State’s Public Benefits or Insurance Program Under State and Federal law, including IDEA and FERPA, or (b)access Medicaid funding on behalf of my child and understand that my refusal will not affect the district’s obligation to provide my child a Free Appropriate Public Education (FAPE) at no cost."
        );
        $this->fape_consent->setMultiOptions($multiOptions);

        $this->print_english_permission_to_access = new App_Form_Element_Checkbox('print_english_permission_to_access', array('label' => 'English'));

        $this->print_spanish_permission_to_access = new App_Form_Element_Checkbox('print_spanish_permission_to_access', array('label' => 'Spanish'));

        $this->expanded_options = new App_Form_Element_Checkbox('expanded_options', array('label'=>'Expanded Options'));
        $this->expanded_options->setAttrib('onclick', "toggleShowExpandedOptions();");
        
        $this->reg_edu_student_services = $this->buildEditor('reg_edu_student_services', array('label'=>'Services with Regular Education Students'));
        $this->reg_edu_student_services->setRequired(false);
        $this->reg_edu_student_services->setAllowEmpty(false);
        $this->reg_edu_student_services->removeEditorEmptyValidator();
        $this->reg_edu_student_services->addValidator(new My_Validate_EditorNotEmptyIf('expanded_options', "t"), true);
        
        $this->not_reg_edu_student_services = $this->buildEditor('not_reg_edu_student_services', array('label'=>'Services Not with Regular Education Students'));
        $this->not_reg_edu_student_services->setRequired(false);
        $this->not_reg_edu_student_services->setAllowEmpty(false);
        $this->not_reg_edu_student_services->removeEditorEmptyValidator();
        $this->not_reg_edu_student_services->addValidator(new My_Validate_EditorNotEmptyIf('expanded_options', "t"), true);
        
        $this->other_services = $this->buildEditor('other_services', array('label'=>'Other Services'));
        $this->other_services->setRequired(false);
        $this->other_services->setAllowEmpty(false);
        $this->other_services->removeEditorEmptyValidator();
        $this->other_services->addValidator(new My_Validate_EditorNotEmptyIf('expanded_options', "t"), true);
        
        return $this;
    }

    public function edit_p6_v1()
    {

        $this->initialize();

        $this->date_conference = new App_Form_Element_DatePicker('date_conference', array('Label' => 'Conference Date:'));
        $this->date_conference->setAttrib('style', 'display:none;');
        $this->date_conference->removeDecorator('label');


        $this->setDecorators(
            array(
//				                'PrepareElements', 
                array(
                    'ViewScript',
                    array(
                        'viewScript' => 'form004/edit_p6_v1.phtml'
                    )
                )
            )
        );

        $this->primary_disability = $this->buildEditor(
            'primary_disability',
            array('Label' => "Statement of special education and related services")
        );
        $this->primary_disability->addErrorMessage(
            'Statement of special education and related services provided to the child is empty.'
        );

        // related services drop down menu
        $arrLabel = array("build me");
        $arrValue = array("");
        $this->primary_disability_drop = new App_Form_Element_Select('primary_disability_drop', array('Label' => "Primary Service:"));
        $this->primary_disability_drop->setMultiOptions($this->getPrimaryDisability_version1());
        $this->primary_disability_drop->addErrorMessage('A Special Ed. Service must be chosen.');
        $this->primary_disability_drop->setAttrib('onchange', "modified();colorMeById(this.id);toggleShowHideMips();");

        $this->primary_service_from = new App_Form_Element_DatePicker('primary_service_from', array('Label' => 'Special Ed. service from:'));
        $this->primary_service_from->addErrorMessage('You have not chosen the duration \'from\' date of the service.');
        $this->primary_service_from->setInlineDecorators();
        $this->primary_service_from->setAttrib('class', 'from_date ' . $this->primary_service_from->getAttrib('class'));

        // custom addition - elements wrappers - highlight a group of elements instead of just one
        //
        // field wrapper - highlighting around more than one element
        // pass a tag unique to the encapsulated elements as the second parameter of colorMeById - to change wrapper yellow
        // set the wrapper_tag attribute of
        $this->primary_service_from->setAttrib(
            'onchange',
            $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');"
        );
        $this->primary_service_from->setAttrib('wrapped', 'dates_wrapper');
        $this->primary_service_from->setRequired(true);
        $this->primary_service_from->setAllowEmpty(false);


        $this->primary_service_to = new App_Form_Element_DatePicker('primary_service_to', array('Label' => 'Special Ed. service to:'));
        $this->primary_service_to->addErrorMessage('You have not chosen the duration \'to\' date of the service.');
        $this->primary_service_to->setInlineDecorators();
        $this->primary_service_to->setAttrib(
            'onchange',
            $this->JSmodifiedCode . "colorMeById(this.id, 'dates_wrapper');"
        );
        $this->primary_service_to->setAttrib('wrapped', 'dates_wrapper');
        $this->primary_service_to->setRequired(true);
        $this->primary_service_to->setAllowEmpty(false);
        $this->primary_service_to->setAttrib('class', 'to_date ' . $this->primary_service_to->getAttrib('class'));


        $this->primary_service_location = new App_Form_Element_Select('primary_service_location', array('Label' => "Special Ed. service location:"));
        $this->primary_service_location->setMultiOptions(array_combine($arrValue, $arrLabel));
        $this->primary_service_location->setAllowEmpty(false);
        $this->primary_service_location->setRequired(true);
        $this->primary_service_location->addErrorMessage('Special Ed. service location is empty.');
        $this->primary_service_location->addValidator(new My_Validate_EmptyIf('date_conference', null));


        $multiOptions = array('t' => 'Yes', 'f' => 'No');
        $this->primary_service_calendar = new App_Form_Element_Select('primary_service_calendar', array('Label' => "Primary Service Calendar"));
        $this->primary_service_calendar->setMultiOptions($multiOptions);
        $this->primary_service_calendar->addValidator(new My_Validate_NotEmptyTrueFalse());
        $this->primary_service_calendar->addFilter(
            new Zend_Filter_Callback(array('Form_AbstractForm', 'selectBoolConverter'))
        );

        // with reg education peers
        // value per day quantity
        //
        $this->primary_service_tpd = new App_Form_Element_Text('primary_service_tpd', array('Label' => "min/day / hours/day value"));
        $this->primary_service_tpd->setAttrib('size', '6');
        $this->primary_service_tpd->setDecorators(My_Classes_Decorators::$emptyDecorators);
        $this->primary_service_tpd->setAllowEmpty(false);
        $this->primary_service_tpd->setRequired(true);
        $this->primary_service_tpd->addValidator(new Zend_Validate_Digits());
        $this->primary_service_tpd->setAttrib(
            'onchange',
            $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');calculateAndSetQualifingMinutesPrimary();"
        );
        $this->primary_service_tpd->setAttrib('wrapped', 'tpd_wrapper');
        $this->primary_service_tpd->addDojoNumberValidator();
        $this->primary_service_tpd->addErrorMessage(
            'Special Education Service - min/day | hrs/day must be a number and cannot be empty.'
        );


        $arrLabel = array("min/day", "hrs/day", "min/week");
        $arrValue = array("m", "h", "mw");
        $this->primary_service_tpd_unit = new App_Form_Element_Select('primary_service_tpd_unit', array('Label' => "min/day / hours/day"));
        $this->primary_service_tpd_unit->setMultiOptions(array_combine($arrValue, $arrLabel));
        $this->primary_service_tpd_unit->setDecorators(My_Classes_Decorators::$emptyDecorators);
        $this->primary_service_tpd_unit->setAllowEmpty(false);
        $this->primary_service_tpd_unit->setRequired(true);
        $this->primary_service_tpd_unit->setAttrib(
            'onchange',
            $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');calculateAndSetQualifingMinutesPrimary();"
        );
        $this->primary_service_tpd_unit->setAttrib('wrapped', 'tpd_wrapper');


        // with reg education peers
        // value per day units
        $this->primary_service_days_value = new App_Form_Element_Text('primary_service_days_value', array('Label' => 'Days/Week/Month'));
        $this->primary_service_days_value->setDecorators(My_Classes_Decorators::$emptyDecorators);
        $this->primary_service_days_value->setAttrib('size', '6');
        $this->primary_service_days_value->setAllowEmpty(false);
        $this->primary_service_days_value->setRequired(true);
        $this->primary_service_days_value->addValidator(new Zend_Validate_Digits());
        $this->primary_service_days_value->setAttrib(
            'onchange',
            $this->JSmodifiedCode . "colorMeById(this.id, 'days_wrapper');calculateAndSetQualifingMinutesPrimary();"
        );
        $this->primary_service_days_value->setAttrib('wrapped', 'days_wrapper');
        $this->primary_service_days_value->addFilter('int');
        $this->primary_service_days_value->addDojoNumberValidator();
        $this->primary_service_days_value->addErrorMessage(
            'Special Education Service - days/week | days/month | days/quarter | days/semester | days/year must be a number and cannot be empty.'
        );


        $arrLabel = array(
            "days/week",
            "days/month",
            "days/quarter",
            "days/semester",
            "days/year",
            "weeks/month",
            "weeks/semester",
            "weeks/year"
        );
        $arrValue = array("w", "m", "q", "s", "y", "wm", "ws", "wy");

        $this->primary_service_days_unit = new App_Form_Element_Select('primary_service_days_unit', array('Label' => "Days value"));
        $this->primary_service_days_unit->setDecorators(My_Classes_Decorators::$emptyDecorators);
        $this->primary_service_days_unit->setMultiOptions(array_combine($arrValue, $arrLabel));
        $this->primary_service_days_unit->setAllowEmpty(false);
        $this->primary_service_days_unit->setRequired(true);
        $this->primary_service_days_unit->setAttrib(
            'onchange',
            $this->JSmodifiedCode . "colorMeById(this.id, 'days_wrapper');calculateAndSetQualifingMinutesPrimary();"
        );
        $this->primary_service_days_unit->setAttrib('wrapped', 'days_wrapper');
        $this->primary_service_days_unit->addErrorMessage('You have not entered a days value.');
//		$this->primary_service_days_unit->removeValidator('inArray');

        /**
         * artifical select used to popuplate primary_service_days_unit when min/week is selected
         * this field is not saved in the db
         */
        $arrLabel = array("weeks/month", "weeks/semester", "weeks/year");
        $arrValue = array("wm", "ws", "wy");

        $this->primary_service_tpd_unit_minperweek_helper = new App_Form_Element_Select('primary_service_tpd_unit_minperweek_helper', array('Label' => 'Days/Week/Month'));
        $this->primary_service_tpd_unit_minperweek_helper->setMultiOptions(array_combine($arrValue, $arrLabel));
        $this->primary_service_tpd_unit_minperweek_helper->setIgnore(true);
        $this->primary_service_tpd_unit_minperweek_helper->setAllowEmpty(true);
        $this->primary_service_tpd_unit_minperweek_helper->setRequired(false);
        $this->primary_service_tpd_unit_minperweek_helper->setDecorators(array('ViewHelper'));
        $this->primary_service_tpd_unit_minperweek_helper->setAttrib('style', "display:none");

        // months input
        $this->primary_service_mpy = new App_Form_Element_Text('primary_service_mpy', array('Label' => "Months"));
        $this->primary_service_mpy->setDecorators(My_Classes_Decorators::$emptyDecorators);
        $this->primary_service_mpy->setAllowEmpty(false);
        $this->primary_service_mpy->setRequired(true);
        $this->primary_service_mpy->addValidator(new Zend_Validate_Digits());
        $this->primary_service_mpy->setAttrib('size', '6');
        $this->primary_service_mpy->addDojoNumberValidator();
        $this->primary_service_mpy->addErrorMessage(
            'Special Education Service - months must be a number and cannot be empty.'
        );
        $this->primary_service_mpy->addFilter('int');

        /*
         * NON-LPS Fields
         */
        if (!$this->lps) {

            $this->special_ed_peer_percent = new App_Form_Element_ValidationTextBox('special_ed_peer_percent', array('Label' => "With regular education peers"));
            $this->special_ed_peer_percent->setAttrib('style', 'width:40px');
            $this->special_ed_peer_percent->addFilter('int');
            $this->special_ed_peer_percent->addValidator(
                new My_Validate_SumFieldsTo('', 100, array(
                    'special_ed_peer_percent',
                    'special_ed_non_peer_percent',
                    'reg_ed_percent'
                ))
            );
            //		$this->special_ed_peer_percent->setRegExp("^(?=.*[1-9].*$)d{0,3}$");

            $this->special_ed_non_peer_percent = new App_Form_Element_ValidationTextBox('special_ed_non_peer_percent', array('Label' => "Not with regular education peers"));
            $this->special_ed_non_peer_percent->setAttrib('style', 'width:40px');
            $this->special_ed_non_peer_percent->addFilter('int');
            $this->special_ed_non_peer_percent->addValidator(
                new My_Validate_SumFieldsTo('', 100, array(
                    'special_ed_peer_percent',
                    'special_ed_non_peer_percent',
                    'reg_ed_percent'
                ))
            );

            $this->reg_ed_percent = new App_Form_Element_ValidationTextBox('reg_ed_percent', array('Label' => "Regular education peers"));
            $this->reg_ed_percent->setAttrib('style', 'width:40px');
            $this->reg_ed_percent->addFilter('int');
            $this->reg_ed_percent->addValidator(
                new My_Validate_SumFieldsTo('', 100, array(
                    'special_ed_peer_percent',
                    'special_ed_non_peer_percent',
                    'reg_ed_percent'
                ))
            );
        }

        $multiOptions = array('1' => 'Yes', '0' => 'No');
        $this->pg6_doc_signed_parent = new App_Form_Element_Radio('pg6_doc_signed_parent', array(
            'Label' => 'Parent Signature on file',
            'multiOptions' => $multiOptions
        ));
        $this->pg6_doc_signed_parent->setAllowEmpty(true);
        $this->pg6_doc_signed_parent->setRequired(false);
        //$this->pg6_doc_signed_parent->addValidator(
        //    new My_Validate_BooleanNotEmptyIfContains('primary_disability_drop', 'Occupational Therapy Services')
        //);


        $this->pg6_date_doc_signed_parent = new App_Form_Element_DatePicker('pg6_date_doc_signed_parent', array('Label' => 'Date of Parent Signature:'));
        $this->pg6_date_doc_signed_parent->setAllowEmpty(true);
        $this->pg6_date_doc_signed_parent->setRequired(false);
        //$this->pg6_date_doc_signed_parent->addValidator(
        //    new My_Validate_Form004MipsParentConsent('primary_disability_drop', 'Occupational Therapy Services')
        //);

        $this->pg6_no_sig_explanation = $this->buildEditor(
            'pg6_no_sig_explanation',
            array('Label' => "No Signature Explanation")
        );
        $this->pg6_no_sig_explanation->setDescription('(If \'No\' selected above, please explain)');
        $this->pg6_no_sig_explanation->setAllowEmpty(true);
        $this->pg6_no_sig_explanation->setRequired(false);
        $this->pg6_no_sig_explanation->removeEditorEmptyValidator();
        $this->pg6_no_sig_explanation->addValidator(
            new My_Validate_Form004MipsEditorExplanation('pg6_doc_signed_parent', false)
        );


        $multiOptions = array(
            '' => "No option selected",
            '1' => "I give CONSENT to the public school district named herein to access Medicaid funding on behalf of my child (named above) and understand that I may withdraw this consent at any time upon written notice to the public school district.",
            '0' => "I REFUSE to give consent to the public school district to access Medicaid funding on behalf of my child and understand that my refusal will not affect the district's obligation to provide my child a Free Appropriate Public Education (FAPE) at no cost."
        );
//        $mipsRestrictions = array('Occupational Therapy Services', 'Physical Therapy', 'Speech-language therapy');
        $this->fape_consent = new App_Form_Element_Radio('fape_consent', array(
            'Label' => 'Mips Consent',
            'multiOptions' => $multiOptions
        ));
        $this->fape_consent->setAllowEmpty(true);
        $this->fape_consent->setRequired(false);
        //$this->fape_consent->addValidator(new My_Validate_NotEmptyIfSubformValueInArray('related_services', 'related_service_drop', $mipsRestrictions));
        //$this->fape_consent->addValidator(new My_Validate_BooleanNotEmptyIfContains('primary_disability_drop', $mipsRestrictions));
        $this->fape_consent->setSeparator('<br/>');
        $this->fape_consent->removeDecorator('Label');

        $this->print_english_permission_to_access = new App_Form_Element_Checkbox('print_english_permission_to_access', array('label' => 'English'));

        $this->print_spanish_permission_to_access = new App_Form_Element_Checkbox('print_spanish_permission_to_access', array('label' => 'Spanish'));

        return $this;
    }


    public function edit_p7_v2()
    {
        return $this->edit_p7_v1();
    }

    public function edit_p7_v3()
    {
        return $this->edit_p7_v1();
    }

    public function edit_p7_v4()
    {
        return $this->edit_p7_v1();
    }

    public function edit_p7_v5()
    {
        return $this->edit_p7_v1();
    }

    public function edit_p7_v6()
    {
        return $this->edit_p7_v1();
    }

    public function edit_p7_v7()
    {
        return $this->edit_p7_v1();
    }

    public function edit_p7_v8()
    {
        return $this->edit_p7_v1();
    }

    public function edit_p7_v9()
    {
        return $this->edit_p7_v1();
    }

    public function edit_p7_v10()
    {

        $this->edit_p7_v1();
        $this->addPage7Ext();
        return $this;
    }

    public function edit_p7_v11()
    {
        //Add elements here
        $multiOptions = array(
            'Modified NeSa-Reading' => 'Modified NeSa-Reading',
            'NeSa-Reading with Accommodations' => 'NeSa-Reading with Accommodations',
            'Modified NeSa-Writting' => 'Modified NeSa-Writting',
            'NeSa-Writting with Accommodations' => 'NeSa-Writting with Accommodations',
            'Extended Time on Test' => 'Extended Time on Test',
            'Read Test' => 'Read Test',
        );
        $this->district_assessments = new App_Form_Element_MultiCheckbox('district_assessments', array(
            'Label' => 'District Assessements:',
            'multiOptions' => $multiOptions
        ));
        $this->district_assessments->addErrorMessage(
            'District assessments must be entered when Assessment is "The child will participate in district-wide assessment WITH accommodations, as specified:".'
        );
        $this->district_assessments->setAllowEmpty(true);
        $this->district_assessments->setIsArray(true);
        $this->district_assessments->setRequired(false);
        $this->district_assessments->setSeparator('<br/>');
        $this->district_assessments->removeDecorator('label');
        $this->district_assessments->addFilter('StringTrim');
        $this->district_assessments->addFilter('StripTags');
        $this->district_assessments->setAttrib('onchange', "colorMeById(this.id);modified();");

        return $this->edit_p7_v10();
    }

    public function edit_p7_v1()
    {

        $this->initialize();

        $this->setDecorators(
            array(
                array(
                    'ViewScript',
                    array(
                        'viewScript' => 'form004/edit_p7_v1.phtml'
                    )
                )
            )
        );

        //Add elements here
        $multiOptions = array('1' => 'Yes', '0' => 'No');
        $this->transportation_yn = new App_Form_Element_Radio('transportation_yn', array(
            'Label' => 'Child qualifies for special education transportation:',
            'multiOptions' => $multiOptions
        ));
        $this->transportation_yn->addErrorMessage('Child qualifies for special education selection must be entered.');
        $this->transportation_yn->setAttrib('onclick', 'childQualifies();');

        $options = array(
            '' => '',
            'Not Necessary' => 'Not Necessary',
            'Child is below age 5' => 'Child is below age 5',
            'Child is required to attend a facility other than the normal attendance facility' => 'Child is required to attend a facility other than the normal attendance facility',
            'Nature of the child\'s disability is such that special education transportation is required' => 'Nature of the child\'s disability is such that special education transportation is required'
        );
        $this->transportation_why = new App_Form_Element_Select('transportation_why', array('Label' => "If child qualifies, why:"));
        $this->transportation_why->setMultiOptions($options);

        /*
         * Modifed By Steve Bennett 7-13-10
         * Add select drop down to new row
         */

        // $this->transportation_why->setDecorators(My_Classes_Decorators::$labelDecorators);
        $this->transportation_why->setDecorators(
            array(
                'ViewHelper',
                array(
                    'HtmlTag',
                    array(
                        'tag' => 'div',
                        'class' => 'colorme',
                        'id' =>
                            $this->transportation_why->getName() . '-colorme'
                    )
                ),
                array(
                    'Label',
                    array(
                        'tag' => 'div',
                    )
                )
            )
        );
        // $this->transportation_why->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->transportation_why->getName() . '-colorme') );

        /*
         * End Modification 
         */

        $this->transportation_why->addErrorMessage(
            'If child qualifies is empty. If the item does not apply to this student, please explain why.'
        );
        //$this->transportation_why->addValidator(new My_Validate_AtLeastOneIf('transportation_yn', 0, array('Not Necessary')));
        //$this->transportation_why->addValidator(new My_Validate_AtLeastOneIf('transportation_yn', 1, array('Child is below age 5')));
        $this->transportation_why->setAllowEmpty(false);
        $this->transportation_why->setRequired(false);
        $this->transportation_why->addValidator(new My_Validate_NotEmptyIf('transportation_yn', 1));

        $this->transportation_desc = $this->buildEditor(
            'transportation_desc',
            array('Label' => 'Special education transportation description:')
        );
// 		$this->transportation_desc->addErrorMessage('Special education transportation description is empty or a value has been entered and "Child qualifies for special education transportation:" is set to "No". If the item does not apply to this student, please explain why. If you have selected "No" to "Child qualifies for special education transportation:" please leave this blank.');
        $this->transportation_desc->addErrorMessage(
            'Special education transportation description cannot be empty when transportation is yes.'
        );
        $this->transportation_desc->setAllowEmpty(false);
        $this->transportation_desc->setRequired(false);
        $this->transportation_desc->removeEditorEmptyValidator();
        $this->transportation_desc->addValidator(new My_Validate_EditorNotEmptyIf('transportation_yn', 1));
// 		$this->transportation_desc->addValidator(new My_Validate_EditorEmptyNotEmpty('transportation_yn', 1));


        $assessment_accom_array = array(
            'The child will participate in district-wide assessment WITHOUT accommodations' => 'The child will participate in district-wide assessment WITHOUT accommodations',
            'The child will participate in district-wide assessment WITH accommodations, as specified:' => 'The child will participate in district-wide assessment WITH accommodations, as specified:',
            'The child will participate in a combination of assessment systems as specified.' => 'The child will participate in a combination of assessment systems as specified.',
            'The child will NOT participate in district-wide assessment, for the following reasons:' => 'The child will NOT participate in district-wide assessment, for the following reasons:'
        );

// MIKE ADDED the $this->assessment_accom to the App_Form_Element_Radio: it did not work
//Mike changed App_Form to Zend_Form : no effect


        $this->assessment_accom = new App_Form_Element_Radio('assessment_accom', array(
            'Label',
            "Assessment participation:"
        ));
        $this->assessment_accom->setDecorators(My_Classes_Decorators::$elementDecorators);
        $this->assessment_accom->addDecorator(
            'HtmlTag',
            array('tag' => 'div', 'class' => 'colorme', 'id' => $this->transportation_why->getName() . '-colorme')
        );
        $this->assessment_accom->setMultiOptions($assessment_accom_array);
        $this->assessment_accom->setSeparator('<br/>');
        $this->assessment_accom->addErrorMessage('Assessment participation selection must be entered.');



        $this->assessment_desc = $this->buildEditor('assessment_desc', array('Label', 'Assessment description:'));
        $this->assessment_desc->addErrorMessage(
            'Assessment text is empty. If the item does not apply to this student, please explain why.'
        );
        $this->assessment_desc->setAllowEmpty(false);
        $this->assessment_desc->setRequired(false);
        $this->assessment_desc->addValidator(
            new My_Validate_NotEmptyIfContains('assessment_accom', array(
//			'The child will participate in district-wide assessment WITH accommodations, as specified:',
                'The child will NOT participate in district-wide assessment, for the following reasons:'
            ))
        );



        $this->assessment_alt = $this->buildEditor('assessment_alt', array('Label', 'Alternate assessment:'));
        $this->assessment_alt->addErrorMessage(
            'Alternate assessment text is empty. If the item does not apply to this student, please explain why.'
        );

        /*
         * NON-LPS Fields
         */
        if (!$this->lps) {
            $this->addPage7Ext();
        }
        return $this;
    }

    public function addPage7Ext()
    {
        $multiOptions = array('1' => 'Yes', '0' => 'No');
        $this->ext_school_year_yn = new App_Form_Element_Radio('ext_school_year_yn', array(
            'Label' => 'Description of extended school year:',
            'multiOptions' => $multiOptions
        ));
        $this->ext_school_year_yn->addErrorMessage(
            'Description of extended school year services is empty. If the item does not apply to this student, please explain why.'
        );
        $this->ext_school_year_yn->addValidator(new My_Validate_BooleanNotEmpty(), true);

        $this->ext_school_year_desc = $this->buildEditor(
            'ext_school_year_desc',
            array('Label', 'Extended school year services')
        );
        $this->ext_school_year_desc->addErrorMessage('Extended school year services selection must be entered.');
        $this->ext_school_year_desc->removeEditorEmptyValidator();
        $this->ext_school_year_desc->addValidator(new My_Validate_EditorNotEmptyIf('ext_school_year_yn', '1'));
    }

    public function edit_p8_v2()
    {
        return $this->edit_p8_v1();
    }

    public function edit_p8_v3()
    {
        return $this->edit_p8_v1();
    }

    public function edit_p8_v4()
    {
        return $this->edit_p8_v1();
    }

    public function edit_p8_v5()
    {
        return $this->edit_p8_v1();
    }

    public function edit_p8_v6()
    {
        return $this->edit_p8_v1();
    }

    public function edit_p8_v7()
    {
        return $this->edit_p8_v1();
    }

    public function edit_p8_v8()
    {
        return $this->edit_p8_v1();
    }

    public function edit_p8_v9()
    {
        return $this->edit_p8_v1();
    }

    public function edit_p8_v10()
    {
        return $this->edit_p8_v1();
    }

    public function edit_p8_v11()
    {
        return $this->edit_p8_v1();
    }

    public function edit_p8_v1()
    {

        $this->initialize();

        $this->setDecorators(
            array(
                array(
                    'ViewScript',
                    array(
                        'viewScript' => 'form004/edit_p8_v1.phtml'
                    )
                )
            )
        );

        //Add elements here

        return $this;
    }

    /*
     * Select Definitions - removed in dec refactor
     */
    function getStudentRelationship_version1()
    {
        $arrLabel = array(
            "Adaptive Physical Education",
            "Assistive Technology",
            "Audiologist",
            "Counselor",
            "Interpreter",
            "General Education Teacher",
            "Notetaker",
            "Occupational Therapist",
            "Parent Trainer",
            "Physical  Therapist",
            "Physician",
            "Reader",
            "Recreational Therapist",
            "School Nurse",
            "Speech Language Pathologist",
            "Transportation Services",
            "Vocational Education",
            "Other (Please Specify)"
        );
        $arrValue = array(
            "Adaptive Physical Education",
            "Assistive Technology",
            "Audiologist",
            "Counselor",
            "Interpreter",
            "General Education Teacher",
            "Notetaker",
            "Occupational Therapist",
            "Parent Trainer",
            "Physical  Therapist",
            "Physician",
            "Reader",
            "Recreational Therapist",
            "School Nurse",
            "Speech Language Pathologist",
            "Transportation Services",
            "Vocational Education",
            "Other (Please Specify)"
        );
        return array_combine($arrLabel, $arrValue);

    }
    // Mike added mental healthf counseling services 11-10-2017 Jira 136
    function getPrimaryDisability_version1()
    {
        $arrLabel = array(
            "Choose Special Ed. Service",
            "Assistive Technology Services/Devices",
            "Audiology",
            "Family Training, Counseling, Home visits and other Supports",
            "Health Services",
            "Interpreting Services",
            "Medical Services (for diagnostic or evaluation purposes)",
            "Mental Health Counseling",
            "Nursing Services",
            "Nutrition Services",
            "Occupational Therapy Services",
            "Orientation and Mobility",
            "Physical Therapy",
            "Psychological Services",
            "Respite Care",
            "Services Coordination",
            "Social Work Services",
            "Special Instruction (Resource)",
            "Speech/language Therapy",
            "Teacher of the Hearing Impaired",
            "Teacher of the Visually Impaired",
            "Transportation",
            "Vision Services",
        );

        $arrValue = array(
            "",
            "Assistive technology services/devices",
            "Audiology",
            "Family training, counseling, home visits and other supports",
            "Health services",
            "Interpreting Services",
            "Medical services (for diagnostic or evaluation purposes)",
            "Mental Health Counseling",
            "Nursing services",
            "Nutrition services",
            "Occupational Therapy Services",
            "Orientation and Mobility",
            "Physical Therapy",
            "Psychological services",
            "Respite care",
            "Services coordination",
            "Social work services",
            "Special Instruction (Resource)",
            "Speech-language therapy", // notice that the label for this is changed above.
            "Teacher of the Hearing Impaired",
            "Teacher of the Visually Impaired",
            "Transportation",
            "Vision Services",
        );

        return array_combine($arrValue, $arrLabel);
    }

    function getLocation_version1($options)
    {
        if (!isset($options['dob'])) {
            return false;
        } else {
            $dob = $options['dob'];
            $lps = isset($options['lps']) ? $options['lps'] : false;
        }
        if (isset($options['finalized_date']) && isset($options['status']) && 'Final' == $options['status']) {
            $finalized_date = new Zend_Date($options['finalized_date'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        }
        /**
         * Check to see if student is under three years minus one day
         */
        $dob = new Zend_Date($dob, Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        $dob = new Zend_Date($dob, Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);

        $cutoffDate = new Zend_Date($dob . '+3 years -1 day', Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);

        if (isset($finalized_date)) { // form is final
            $today = $finalized_date;
        } else {
            $today = new Zend_Date(null, Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        }

        // student is under 3 years old
        if (-1 == $today->compareTimestamp($cutoffDate)) {
            // cutoffDate is earlier than today
            $arrLabel = array(
                '' => 'Choose Location',
                '1' => 'Home',
                '2' => 'Community Based',
                '3' => 'Other',
            );
            return $arrLabel;
        }


        $times = Model_Table_IepStudent::dateDiff($dob->toString(), $today->toString());
        if (!isset($times['years'])) {
            return array();
        }
        $years = $times['years'];

        switch ($years) {
            case 0:
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
                if ($lps) {
                    $arrLabel = array(
                        '' => "Choose Location",
                        '00' => 'Public School',
                        '5' => 'Separate School',
                        '6' => 'Separate Class',
                        '7' => 'Residential Facility',
                        '8' => 'Home',
                        '9' => 'Service Provider Location',
//							'16' => 'Regular Early Childhood Program, 10+ h/wk; Services at EC Program',
//							'17' => 'Regular Early Childhood Program, 10+ h/wk; Services outside EC Program',
//							'18' => 'Regular Early Childhood Program, <10 h/wk; Services at EC Program',
//							'19' => 'Regular Early Childhood Program, <10 h/wk; Services outside EC Program',
                    );
                } else {
                    $arrLabel = array(
                        '' => "Choose Location",
                        '5' => 'Separate School',
                        '6' => 'Separate Class',
                        '7' => 'Residential Facility',
                        '8' => 'Home',
                        '9' => 'Service Provider Location',
                        '16' => 'Regular Early Childhood Program, 10+ h/wk; Services at EC Program',
                        '17' => 'Regular Early Childhood Program, 10+ h/wk; Services outside EC Program',
                        '18' => 'Regular Early Childhood Program, <10 h/wk; Services at EC Program',
                        '19' => 'Regular Early Childhood Program, <10 h/wk; Services outside EC Program',
                    );
                }
                break;
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
                $arrLabel = array(
                    "Choose Location",
                    "Separate School",
                    "Residential Facility",
                    "Public School",
                    "Homebound/Hospital",
                    "Private School or Exempt (home) School",
                    "Correction/Detention Facility"
                );
                $arrValue = array(
                    "",
                    "05",
                    "07",
                    "10",
                    "13",
                    "14",
                    "15"
                );
                break;
            default:
                $arrLabel = array(
                    "Choose Location",
                    "Public School",
                    "Public Separate School",
                    "Public Residential Facility",
                    "NonPublic School",
                    "NonPublic Separate School",
                    "NonPublic Residential Facility",
                    "Hospital",
                    "Home"
                );
                $arrValue = array(
                    "",
                    "01",
                    "02",
                    "03",
                    "05",
                    "06",
                    "07",
                    "09",
                    "10"
                );
        }
        if (!isset($arrValue)) {
            return $arrLabel;
        }
        return array_combine($arrValue, $arrLabel);
    }


    public function isValid($data)
    {
        if ($this->page && 7 == $this->page->getValue()) {
            $studentObj = new Model_Table_StudentTable();
            if (isset($data['id_student'])) {
                $studentRows = $studentObj->find($data['id_student']);
                if (count($studentRows)) {
                    $student = $studentRows->current();
                }
                if ($student['id_county'] == "99" && $student['id_district'] == "9999") {
                    $this->district_assessments->addValidator(
                        new My_Validate_NotEmptyIf('assessment_accom', 'The child will participate in district-wide assessment WITH accommodations, as specified:')
                    );
                } else {
                    $this->removeElement('district_assessments');
                }
            }
        }

        $valid = parent::isValid($data);

        /**
         * additional data checks.
         */
//        if($this->page && 7 == $this->page->getValue()) {
//            Zend_Debug::dump($data['district_assessments']);
//        }
        /**
         * mips check (SRSSUPP-565)
         * if a mips district and a mips enabled form
         * run additional mips checks
         */
        if ($this->page && 6 == $this->page->getValue()) {
            $studentObj = new Model_Table_StudentTable();
            if (isset($data['id_student'])) {
                $studentRows = $studentObj->find($data['id_student']);
                if (count($studentRows)) {
                    $student = $studentRows->current();
                }
                $districtObj = new Model_Table_District();
                if (isset($student['id_county']) && isset($student['id_district'])) {
                    $district = $districtObj->getDistrict($student['id_county'], $student['id_district']);
                    if (isset($district['use_mips_consent_form']) && $district['use_mips_consent_form']) {
                        /**
                         * fape_consent must be filled in
                         */
                        if (isset($district['require_mips_validation']) && $district['require_mips_validation']) {
                            $mipsRestrictions = array('Occupational Therapy Services', 'Physical Therapy', 'Speech-language therapy');
                            // ensure fape_consent is not empty if primary or related disability contains one of the above.
                            if(
                                (isset($data['primary_disability_drop']) && in_array($data['primary_disability_drop'], $mipsRestrictions)) ||
                                (isset($data['related_service_drop']) && in_array($data['related_service_drop'], $mipsRestrictions))
                            ) {
                                if (array_key_exists('fape_consent', $data) && true !== $data['fape_consent'] && false !== $data['fape_consent']) {
                                    $this->getElement('fape_consent')->addError('Fape Consent is required in this district.');
                                    $valid = false;
                                }
                            }
                        }
                        /**
                         * Explanation required when sig is NOT on file
                         */
                        if (isset($data['pg6_doc_signed_parent']) && false == $data['pg6_doc_signed_parent'] && '' == $data['pg6_no_sig_explanation']) {
                            $this->getElement('pg6_no_sig_explanation')->addError(
                                'Explanation required when parent signature is not on file.'
                            );
                            $valid = false;
                        }

                    }
                }
            }
        }
//        if(isset($data['fape_consent'])) {
//            Zend_Debug::dump($data);
//            die;
//        }
        return $valid;

    }

//    public function populate(array $values, $post = false)
//    {
//        Zend_Debug::dump($post);die;
//        parent::populate($values);
//        if($post && is_array($values['district_assessments'])) {
//            Zend_Debug::dump('inside');
//            $values['district_assessments'] = implode('|', $values['district_assessments']);
//        } elseif(!$post && substr_count($values['district_assessments'], '|')) {
//            Zend_Debug::dump('inside');
//            $values['district_assessments'] = explode('|', $values['district_assessments']);
//        }
//    }
}
