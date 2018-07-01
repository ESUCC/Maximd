<?php

class Form_Form025 extends Form_AbstractForm {

    var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function init()
    {
        $this->setEditorType('App_Form_Element_TestEditor');
    }

    protected function initialize() {
        parent::initialize();

        $this->id_form_025 = new App_Form_Element_Hidden('id_form_025');
        $this->id_form_025->ignore = true;

        $multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
        $this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
        $this->form_editor_type->setRequired(false);
        $this->form_editor_type->setAllowEmpty(true);
        $this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
        $this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');

    }

    public function edit_p1_v2() { return $this->edit_p1_v1();}
    public function edit_p1_v3() { return $this->edit_p1_v1();}
    public function edit_p1_v4() { return $this->edit_p1_v1();}
    public function edit_p1_v5() { return $this->edit_p1_v1();}
    public function edit_p1_v6() { return $this->edit_p1_v1();}
    public function edit_p1_v7() { return $this->edit_p1_v1();}
    public function edit_p1_v8() { return $this->edit_p1_v1();}
    public function edit_p1_v9() { return $this->edit_p1_v1();}
	public function edit_p1_v1() {

		$this->initialize();

		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options:
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form025/form025_edit_page1_version1.phtml' ) ) ) );

		$this->parent_names = new App_Form_Element_Text('parent_names', array('label'=>'To:'));
		$this->parent_names->normalLabel();

        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label'=>'Date:'));
        $this->date_notice->boldLabelPrint();


        $this->schedule_discuss_concerns = new App_Form_Element_Checkbox('schedule_discuss_concerns', array('label'=>'Discuss concerns addressed through the Student Assistance Team Process'));
        $this->schedule_discuss_concerns->setDescription('Discuss concerns addressed through the Student Assistance Team Process and the results of problem solving and intervention strategies used to assist the teacher in the provision of general education. ');
        $this->schedule_discuss_concerns->getDecorator('Description')->setOption('escape', false);
        $this->schedule_discuss_concerns->removeDecorator('Label');

        $this->schedule_consider_referral = new App_Form_Element_Checkbox('schedule_consider_referral', array('label'=>'Consider a potential referral to the Multidisciplinary Evaluation Team for further assessment.'));
        $this->schedule_consider_referral->setDescription('Consider a potential referral to the Multidisciplinary Evaluation Team for further assessment. If the team determines evaluation is needed, the team will recommend needed assessments.');
        $this->schedule_consider_referral->getDecorator('Description')->setOption('escape', false);
        $this->schedule_consider_referral->removeDecorator('Label');

        $this->schedule_other = new App_Form_Element_Checkbox('schedule_other', array('label'=>'Other'));

        $this->schedule_other_text = new App_Form_Element_TestEditor('schedule_other_text', array('label'=>'Other Description'));
//        $this->schedule_other_text->addErrorMessage('Other is empty.');
        $this->schedule_other_text->setRequired(false);
        $this->schedule_other_text->setAllowEmpty(false);
        $this->schedule_other_text->removeEditorEmptyValidator();
        $this->schedule_other_text->addValidator(new My_Validate_EditorNotEmptyIf('schedule_other', 't'));

        /**
         * notice we replace colorme decorator with same but span instead of div
         */
        $this->scheduled_date = new App_Form_Element_DatePicker('scheduled_date', array('label'=>'Date:'));
        $this->scheduled_date->removeDecorator('colorme');
        $this->scheduled_date->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'scheduled_date' . '-colorme') );

        $this->scheduled_time = new App_Form_Element_TimeTextBox('scheduled_time', array('label'=>'Time:'));
        $this->scheduled_time->removeDecorator('colorme');
        $this->scheduled_time->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'scheduled_time' . '-colorme') );

        $this->scheduled_place = new App_Form_Element_Text('scheduled_place', array('label'=>'Place:'));
        $this->scheduled_place->removeDecorator('colorme');
        $this->scheduled_place->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'scheduled_place' . '-colorme') );

        // other attendees
        $this->general_ed_teachers = new App_Form_Element_Text('general_ed_teachers', array('label'=>'General Education Teacher(s)'));
        $this->general_ed_teachers->setDescription('General Education Teacher(s)');
        $this->general_ed_teachers->getDecorator('Description')->setOption('escape', false)->setOption('placement', "prepend");
        $this->general_ed_teachers->removeDecorator('Label');
        $this->general_ed_teachers->setAttrib('style', 'width:300px');

        $this->special_ed_teachers = new App_Form_Element_Text('special_ed_teachers', array('label'=>'Special Education Teacher(s)/provider(s)'));
        $this->special_ed_teachers->setDescription('Special Education Teacher(s)/provider(s)');
        $this->special_ed_teachers->getDecorator('Description')->setOption('escape', false)->setOption('placement', "prepend");
        $this->special_ed_teachers->removeDecorator('Label');
        $this->special_ed_teachers->setAttrib('style', 'width:300px');

        $this->school_district_rep = new App_Form_Element_Text('school_district_rep', array('label'=>'School District Representative'));
        $this->school_district_rep->setDescription('School District Representative');
        $this->school_district_rep->getDecorator('Description')->setOption('escape', false)->setOption('placement', "prepend");
        $this->school_district_rep->removeDecorator('Label');
        $this->school_district_rep->setAttrib('style', 'width:300px');

        $this->individuals_to_explain_implications = new App_Form_Element_Text('individuals_to_explain_implications', array('label'=>'Individuals who can explain the instructional implications of evaluation results'));
        $this->individuals_to_explain_implications->setDescription('Individuals who can explain the instructional implications of evaluation results');
        $this->individuals_to_explain_implications->getDecorator('Description')->setOption('escape', false)->setOption('placement', "prepend");
        $this->individuals_to_explain_implications->removeDecorator('Label');
        $this->individuals_to_explain_implications->setAttrib('style', 'width:300px');

        $this->individuals_with_special_knowledge = new App_Form_Element_Text('individuals_with_special_knowledge', array('label'=>'Individuals with special knowledge or expertise regarding the child, including related services personnel, as appropriate'));
        $this->individuals_with_special_knowledge->setDescription('Individuals with special knowledge or expertise regarding the child, including related services personnel, as appropriate');
        $this->individuals_with_special_knowledge->getDecorator('Description')->setOption('escape', false)->setOption('placement', "prepend");
        $this->individuals_with_special_knowledge->removeDecorator('Label');
        $this->individuals_with_special_knowledge->setAttrib('style', 'width:300px');

        $this->other_family_members = new App_Form_Element_Text('other_family_members', array('label'=>'Other family members, as requested by the parent'));
        $this->other_family_members->setDescription('Other family members, as requested by the parent');
        $this->other_family_members->getDecorator('Description')->setOption('escape', false)->setOption('placement', "prepend");
        $this->other_family_members->removeDecorator('Label');
        $this->other_family_members->setAttrib('style', 'width:300px');
        $this->other_family_members->setAllowEmpty(true);
        $this->other_family_members->setRequired(false);

        $this->nonpublic_reps = new App_Form_Element_Text('nonpublic_reps', array('label'=>'Nonpublic Representative'));
        $this->nonpublic_reps->setDescription('Nonpublic Representative');
        $this->nonpublic_reps->getDecorator('Description')->setOption('escape', false)->setOption('placement', "prepend");
        $this->nonpublic_reps->removeDecorator('Label');
        $this->nonpublic_reps->setAttrib('style', 'width:300px');
        $this->nonpublic_reps->setAllowEmpty(true);
        $this->nonpublic_reps->setRequired(false);

        // meeting contact
        $this->meeting_contact_name = new App_Form_Element_Text('meeting_contact_name', array('label'=>'Name'));
        $this->meeting_contact_name->removeDecorator('colorme');
        $this->meeting_contact_name->setAttrib('style', 'width:300px');
        $this->meeting_contact_name->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'meeting_contact_name' . '-colorme') );

        $this->meeting_contact_phone_or_email = new App_Form_Element_Text('meeting_contact_phone_or_email', array('label'=>'Phone or Email'));
        $this->meeting_contact_phone_or_email->removeDecorator('colorme');
        $this->meeting_contact_phone_or_email->getDecorator('Label')->setOption('placement', 'append');
        $this->meeting_contact_phone_or_email->setAttrib('style', 'width:300px');
        $this->meeting_contact_phone_or_email->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'meeting_contact_phone_or_email' . '-colorme') );



		return $this;
	}
    public function edit_p2_v2() { return $this->edit_p2_v1();}
    public function edit_p2_v3() { return $this->edit_p2_v1();}
    public function edit_p2_v4() { return $this->edit_p2_v1();}
    public function edit_p2_v5() { return $this->edit_p2_v1();}
    public function edit_p2_v6() { return $this->edit_p2_v1();}
    public function edit_p2_v7() { return $this->edit_p2_v1();}
    public function edit_p2_v8() { return $this->edit_p2_v1();}
    public function edit_p2_v9() { return $this->edit_p2_v1();}
    public function edit_p2_v1() {

        $this->initialize();

        //Setting the decorator for the element to a single, ViewScript, decorator,
        //specifying the viewScript as an option, and some extra options:
        $this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form025/form025_edit_page2_version1.phtml' ) ) ) );

        $this->parental_rights_included = new App_Form_Element_Checkbox('parental_rights_included', array('label'=>'Parental rights included'));
        $this->parental_rights_included->setDescription("A copy of your Parental Rights is included. Read them carefully and, if you have any questions regarding your rights, you may contact:");
        $this->parental_rights_included->getDecorator('Description')->setOption('escape', false);
        $this->parental_rights_included->removeDecorator('Label');

        $this->school_district_rep_name = new App_Form_Element_Text('school_district_rep_name', array('label'=>'School District Representative'));
        $this->school_district_rep_name->removeDecorator('colorme');
        $this->school_district_rep_name->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'school_district_rep_name' . '-colorme') );

        $this->school_district_rep_number = new App_Form_Element_Text('school_district_rep_number', array('label'=>'Number'));
        $this->school_district_rep_number->setAttrib('style', 'width:50px');
        $this->school_district_rep_number->removeDecorator('colorme');
        $this->school_district_rep_number->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'school_district_rep_number' . '-colorme') );
        $this->school_district_rep_number->setWidth(110);

        $this->parent_name_sig_line = new App_Form_Element_Text('parent_name_sig_line', array('label'=>'Parent Name'));
        $this->parent_name_sig_line->removeDecorator('colorme');
        $this->parent_name_sig_line->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'parent_name_sig_line' . '-colorme') );

        $this->parent_name_sig_line_date = new App_Form_Element_DatePicker('parent_name_sig_line_date', array('label'=>'Date'));
        $this->parent_name_sig_line_date->removeDecorator('colorme');
        $this->parent_name_sig_line_date->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'parent_name_sig_line_date' . '-colorme') );

        $this->able_to_attend = new App_Form_Element_Checkbox('able_to_attend', array('label'=>'I am able to attend the meeting at the time and place listed above.'));
        $this->able_to_attend->getDecorator('Label')->setOption('placement', 'append');

        $this->unable_to_attend = new App_Form_Element_Checkbox('unable_to_attend', array('label'=>'I am unable to attend the meeting at the time and place listed above, and I would like to reschedule.'));
        $this->unable_to_attend->getDecorator('Label')->setOption('placement', 'append');

        $this->parent_signed_name = new App_Form_Element_Text('parent_signed_name', array('label'=>'Parent Signature'));
        $this->parent_signed_name->removeDecorator('colorme');
        $this->parent_signed_name->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'parent_signed_name' . '-colorme') );

        $this->parent_signed_date = new App_Form_Element_DatePicker('parent_signed_date', array('label'=>'Date'));
        $this->parent_signed_date->removeDecorator('colorme');
        $this->parent_signed_date->addDecorator((array('colorme' => 'HtmlTag')), array ('tag' => 'span', 'class' => 'colorme', 'id'  => 'parent_signed_date' . '-colorme') );

        $this->date_response_received = new App_Form_Element_DatePicker('date_response_received', array('label'=>'Date Response Form Received by School District Representative'));


        $this->parental_sig_on_file = new App_Form_Element_Checkbox('parental_sig_on_file', array('label'=>'Parental signature on file.'));

        return $this;

    }
}