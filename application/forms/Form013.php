<?php

class Form_Form013 extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_013 = new App_Form_Element_Hidden('id_form_013');
      	$this->id_form_013->ignore = true;
      	
      	$this->dob = new App_Form_Element_Hidden('dob');

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');

        return $this;
	}
	public function view_p1_v1() {
		$this->initialize();
				
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form013/form013_view_page1_version1.phtml' ) ) ) );

		
		return $this;
	}

    public function required_p1_v2() { return $this->required_p1_v1();}
    public function required_p1_v3() { return $this->required_p1_v1();}
    public function required_p1_v4() { return $this->required_p1_v1();}
    public function required_p1_v5() { return $this->required_p1_v1();}
    public function required_p1_v6() { return $this->required_p1_v1();}
    public function required_p1_v7() { return $this->required_p1_v1();}
    public function required_p1_v8() { return $this->required_p1_v1();}
    public function required_p1_v9() { return $this->required_p1_v1();}
    public function required_p1_v10() { return $this->required_p1_v1();}
	public function required_p1_v1() {
		$this->initialize();
		return $this;
	}
	
    public function edit_p1_v2() { return $this->edit_p1_v1();}
    public function edit_p1_v3() { return $this->edit_p1_v1();}
    public function edit_p1_v4() { return $this->edit_p1_v1();}
    public function edit_p1_v5() { return $this->edit_p1_v1();}
    public function edit_p1_v6() { return $this->edit_p1_v1();}
    public function edit_p1_v7() { return $this->edit_p1_v1();}
    public function edit_p1_v8() { return $this->edit_p1_v1();}
    public function edit_p1_v9() { return $this->edit_p1_v1();}
    public function edit_p1_v10() { return $this->edit_p1_v1();}
	public function edit_p1_v1() {
		
		$this->initialize();
		
		
		$multiOptions = App_Form_ValueListHelper::ifspType();
		/**
		 * Commented out by Steve Bennett.  This isn't being included
		 * on the form view and I don't see it on the original form.
		 * Having it was causing validation to not pass so I'm
		 * removing it until it's requested.
		 *
		 * Readded by Mike Thomson.
		 * 20122321 jlavere - moved from init to p1 
		 * when in the init, it was being overritten when saving on pages 2+
		 * but when not, create dies on page 1
		 */
		$this->ifsptype = new App_Form_Element_Select('ifsptype', array('label' => 'IFSP Type', 'multiOptions' => $multiOptions));
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form013/form013_edit_page1_version1.phtml' ) ) ) );

	   
//        $this->ssn_form = new App_Form_Element_Text('ssn_form', array('label'=>'SSN'));
        $this->ssn_form = new App_Form_Element_Hidden('ssn_form', array('label'=>'SSN'));
		$this->ssn_form->addErrorMessage("You must enter an SSN.");
//		$this->ssn_form->boldLabel();
//		$this->ssn_form->setWidth(120);
//		$this->ssn_form->noValidation();
		
//        $this->medicaid_form = new App_Form_Element_Text('medicaid_form', array('label'=>"Medicaid&nbsp;&#35;"));
        $this->medicaid_form = new App_Form_Element_Hidden('medicaid_form', array('label'=>"Medicaid&nbsp;&#35;"));
		$this->medicaid_form->addErrorMessage("You must enter a Medicaid number.");
//		$this->medicaid_form->boldLabel();
//		$this->medicaid_form->setWidth(120);
//		$this->medicaid_form->noValidation();
	
        $this->eval_date = new App_Form_Element_DatePicker('eval_date', array('label'=>'Date of Consent for Evaluation'));
        $this->eval_date->boldLabelPrint();
        $this->eval_date->boldLabel();
		$this->eval_date->setRequired(false);
        $this->eval_date->setAllowEmpty(false);
	
//        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
//        $this->date_notice->addErrorMessage("Date of Notice is empty.");
//        $this->date_notice->setRequired(true);
//        $this->date_notice->setAllowEmpty(false);
	
        $this->date_mdt = new App_Form_Element_DatePicker('date_mdt', array('label' => 'Date MDT'));
        $this->date_mdt->addErrorMessage("Date of MDT is empty.");
        $this->date_mdt->boldLabelPrint();
        $this->date_mdt->boldLabel();
        $this->date_mdt->setRequired(false);
        $this->date_mdt->setAllowEmpty(false);
	
        $multiOptions = App_Form_ValueListHelper::ifspLanguages();
        $this->family_lang = new App_Form_Element_Select('family_lang', array('label' => 'Family Language', 'multiOptions' => $multiOptions));
		$this->family_lang->setRequired(true);
        $this->family_lang->setAllowEmpty(false);
		$this->family_lang->addErrorMessage("Family's language choice must be selected.");
		$this->family_lang->boldLabel();
       
        $multiOptions = App_Form_ValueListHelper::ifspLanguages('None');
//        $this->family_lang_second = new App_Form_Element_Select('family_lang_second', array('label' => 'Second Family Language', 'multiOptions' => $multiOptions));
        $this->family_lang_second = new App_Form_Element_Select('family_lang_second', array('label' => 'Additional Language', 'multiOptions' => $multiOptions));
        $this->family_lang_second->boldLabel();
		$this->family_lang_second->setRequired(false);
        $this->family_lang_second->setAllowEmpty(true);
        
        $multiOptions = array('1'=>'Yes', '0'=>'No');
        $this->interpreter = new App_Form_Element_Radio('interpreter', array('label'=>'Family would like an Interpreter', 'multiOptions'=>$multiOptions));
        $this->interpreter->setRequired(true);
        $this->interpreter->setAllowEmpty(false);
		$this->interpreter->addErrorMessage("Family's language choice must be selected.");
		$this->interpreter->boldLabel();
		$this->interpreter->addValidator(new My_Validate_BooleanNotEmpty(), true);  
		
        $this->sc_name = new App_Form_Element_Text('sc_name', array('label'=>'Name'));
		$this->sc_name->setRequired(true);
        $this->sc_name->setAllowEmpty(false);
		$this->sc_name->addErrorMessage("Services coordinator name must be entered.");
		//$this->sc_name->boldLabel();
	
        $this->sc_phone = new App_Form_Element_Text('sc_phone', array('label'=>'Phone'));
        $this->sc_phone->addValidator('regex', false, array('/^[2-9][0-9]{2}-[1-9][0-9]{2}-[0-9]{4}$/'));
        $this->sc_phone->addErrorMessage("Services coordinator phone must be entered in the format xxx-xxx-xxxx.");
		$this->sc_phone->setRequired(true);
        $this->sc_phone->setAllowEmpty(false);
        //$this->sc_phone->boldLabel();
        
        $this->sc_agency = new App_Form_Element_Text('sc_agency', array('label'=>'Agency'));
		$this->sc_agency->setRequired(true);
        $this->sc_agency->setAllowEmpty(false);
		$this->sc_agency->addErrorMessage("Agency must be entered.");
        
        $this->sc_address = new App_Form_Element_TextareaPlain('sc_address', array('label'=>'Address'));
        $this->sc_address->setRequired(true);
        $this->sc_address->setAllowEmpty(false);
		$this->sc_address->addErrorMessage("Services coordinator agency must be entered.");
//		$this->sc_address->setWidth(280);
		//$this->sc_address->boldLabel();
        $this->sc_address->setAttrib('rows', 3);
        $this->sc_address->setAttrib('cols', 40);
		
        // multiOptions set dynamically based on ifsptype
        // see the controller for more details
//        $this->ifsptype_secondary_role = new App_Form_Element_Select('ifsptype_secondary_role', array('label'=>'Secondary Role (optional)'));
//        $this->ifsptype_secondary_role->removeDecorator('label');
//        $this->ifsptype_secondary_role->setRequired(false);
//        $this->ifsptype_secondary_role->setAllowEmpty(true);
        
        $this->meeting_date = new App_Form_Element_DatePicker('meeting_date', array('label'=>'Meeting Date'));
        $this->meeting_date->removeDecorator('label');
        $this->meeting_date->setRequired(true);
        $this->meeting_date->setAllowEmpty(false);
		$this->meeting_date->addErrorMessage("Meeting Date");
	
        $this->autofill_meeting_date = new App_Form_Element_Checkbox('autofill_meeting_date', array('label'=>'Auto Fill'));
        $this->autofill_meeting_date->removeDecorator('label');
        $this->autofill_meeting_date->ignore = true;
        
        $this->meeting_date_sent = new App_Form_Element_DatePicker('meeting_date_sent', array('label'=>'Meeting Date Sent'));
        $this->meeting_date_sent->removeDecorator('label');
        $this->meeting_date_sent->setRequired(true);
        $this->meeting_date_sent->setAllowEmpty(false);
		$this->meeting_date_sent->addErrorMessage("Date Sent");
		
		
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
    public function edit_p2_v10() { return $this->edit_p2_v1();}
	public function edit_p2_v1() {

		$this->initialize();
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form013/form013_edit_page2_version1.phtml' ) ) ) );

        
        $this->date_family_concerns = new App_Form_Element_DatePicker('date_family_concerns', array('label' => 'Date of Family Concerns'));
        //$this->date_family_concerns->setAttrib('style', "width:120px;margin-top:-14px;");
        $this->date_family_concerns->setRequired(true);
        $this->date_family_concerns->setAllowEmpty(false);
		$this->date_family_concerns->addErrorMessage("Date of family concerns");
	
        $this->family_concerns = new App_Form_Element_TestEditor('family_concerns', array('label' => 'Family Concerns'));
        $this->family_concerns->setRequired(true);
        $this->family_concerns->setAllowEmpty(false);
		$this->family_concerns->addErrorMessage("Family Concerns and Desired Priorities must be entered.");
        
		
        $multiOptions = array('all'=>'All', 'sinceannual'=>'Since Last Annual', 'none'=>'None');
		$this->family_concerns_show_ifsps = new App_Form_Element_Radio('family_concerns_show_ifsps', array('label'=>'Show:', 'multiOptions'=>$multiOptions));
		$this->family_concerns_show_ifsps->addValidator(new My_Validate_BooleanNotEmpty('family_concerns_show_ifsps'));
		$this->family_concerns_show_ifsps->setRequired(true);
		$this->family_concerns_show_ifsps->addErrorMessage("Parent Understand Content must be entered.");
		$this->family_concerns_show_ifsps->getDecorator('Label')->setOption('placement', 'prepend');
        $this->family_concerns_show_ifsps->setAttrib('onclick', 'adjustIfspHistoryDisplayfamily_concerns();');
		
        
		
		return $this;
	}
	
    public function edit_p3_v2() { return $this->edit_p3_v1();}
    public function edit_p3_v3() { return $this->edit_p3_v1();}
    public function edit_p3_v4() { return $this->edit_p3_v1();}
    public function edit_p3_v5() { return $this->edit_p3_v1();}
    public function edit_p3_v6() { return $this->edit_p3_v1();}
    public function edit_p3_v7() { return $this->edit_p3_v1();}
    public function edit_p3_v8() { return $this->edit_p3_v1();}
    public function edit_p3_v9() { return $this->edit_p3_v1();}
    public function edit_p3_v10() { return $this->edit_p3_v1();}
	public function edit_p3_v1() {
		
		$this->initialize();
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form013/form013_edit_page3_version1.phtml' ) ) ) );

        $this->date_child_strengths = new App_Form_Element_DatePicker('date_child_strengths', array('label' => 'Date of Child Strengths'));
        //$this->date_child_strengths->setAttrib('style', "width:120px;margin-top:-15px;");
        $this->date_child_strengths->setRequired(true);
        $this->date_child_strengths->setAllowEmpty(false);
		$this->date_child_strengths->addErrorMessage("Date of child strengths");
	
        $this->child_strengths = new App_Form_Element_TestEditor('child_strengths', array('label' => 'Child Strengths'));
		$this->child_strengths->setRequired(true);
        $this->child_strengths->setAllowEmpty(false);
		$this->child_strengths->addErrorMessage("Child Strengths must be entered.");
        
        $multiOptions = array('all'=>'All', 'sinceannual'=>'Since Last Annual', 'none'=>'None');
		$this->child_strengths_show_ifsps = new App_Form_Element_Radio('child_strengths_show_ifsps', array('label'=>'Show:', 'multiOptions'=>$multiOptions));
		$this->child_strengths_show_ifsps->addValidator(new My_Validate_BooleanNotEmpty('child_strengths_show_ifsps'));
		$this->child_strengths_show_ifsps->setRequired(true);
		$this->child_strengths_show_ifsps->addErrorMessage("Parent Understand Content must be entered.");
		$this->child_strengths_show_ifsps->getDecorator('Label')->setOption('placement', 'prepend');
        $this->child_strengths_show_ifsps->setAttrib('onclick', 'adjustIfspHistoryDisplaychild_strengths();');
		
		
		
		return $this;
	}
	
    public function edit_p4_v2() { return $this->edit_p4_v1();}
    public function edit_p4_v3() { return $this->edit_p4_v1();}
    public function edit_p4_v4() { return $this->edit_p4_v1();}
    public function edit_p4_v5() { return $this->edit_p4_v1();}
    public function edit_p4_v6() { return $this->edit_p4_v1();}
    public function edit_p4_v7() { return $this->edit_p4_v1();}
    public function edit_p4_v8() { return $this->edit_p4_v1();}
    public function edit_p4_v9() { return $this->edit_p4_v1();}
    public function edit_p4_v10() { return $this->edit_p4_v1();}
	public function edit_p4_v1() {

		$this->initialize();
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form013/form013_edit_page4_version1.phtml' ) ) ) ); 

	
        $this->dev_vision_desc          = new App_Form_Element_TestEditor('dev_vision_desc', array('label' => 'Vision - Current Abilities'));
        $this->dev_vision_desc->setRequired(true);
        $this->dev_vision_desc->setAllowEmpty(false);
        $this->dev_vision_desc->addErrorMessage("Vision Description must be entered.");
        
        $this->dev_vision_date          = new App_Form_Element_DatePicker('dev_vision_date', array('label' => 'Vision - Date of Evaluation'));
        //$this->dev_vision_date->setAttrib('style', "width:120px;margin-top:-15px;");
        $this->dev_vision_date->setRequired(true);
        $this->dev_vision_date->setAllowEmpty(false);
        $this->dev_vision_date->addErrorMessage("Vision date");
        
        $this->dev_hearing_desc         = new App_Form_Element_TestEditor('dev_hearing_desc', array('label' => 'Hearing - Current Abilities'));
        $this->dev_hearing_desc->setRequired(true);
        $this->dev_hearing_desc->setAllowEmpty(false);
        $this->dev_hearing_desc->addErrorMessage("Hearing Description must be entered.");
        
        $this->dev_hearing_date         = new App_Form_Element_DatePicker('dev_hearing_date', array('label' => 'Hearing - Date of Evaluation'));
        //$this->dev_hearing_date->setAttrib('style', "width:120px;margin-top:-15px;");
        $this->dev_hearing_date->setRequired(true);
        $this->dev_hearing_date->setAllowEmpty(false);
        $this->dev_hearing_date->addErrorMessage("Hearing date");
        
        $this->dev_health_status_desc    = new App_Form_Element_TestEditor('dev_health_status_desc  ', array('label' => 'Health/Status - Current Abilities'));
        $this->dev_health_status_desc->setRequired(true);
        $this->dev_health_status_desc->setAllowEmpty(false);
        $this->dev_health_status_desc->addErrorMessage("Health/Status Description must be entered.");
        
        $this->dev_health_status_date    = new App_Form_Element_DatePicker('dev_health_status_date ', array('label' => 'Health/Status - Date of Evaluation'));
        //$this->dev_health_status_date->setAttrib('style', "width:120px;margin-top:-15px;");
        $this->dev_health_status_date->setRequired(true);
        $this->dev_health_status_date->setAllowEmpty(false);
        $this->dev_health_status_date->addErrorMessage("Health/Status date");
        
        $this->dev_cognitive_desc        = new App_Form_Element_TestEditor('dev_cognitive_desc      ', array('label' => 'Cognitive/Thinking Skills - Current Abilities'));
        $this->dev_cognitive_desc->setRequired(true);
        $this->dev_cognitive_desc->setAllowEmpty(false);
        $this->dev_cognitive_desc->addErrorMessage("Cognitive/Thinking Skills Description must be entered.");
        
        $this->dev_cognitive_date        = new App_Form_Element_DatePicker('dev_cognitive_date     ', array('label' => 'Cognitive/Thinking Skills - Date of Evaluation'));
        //$this->dev_cognitive_date->setAttrib('style', "width:120px;margin-top:-15px;");
        $this->dev_cognitive_date->setRequired(true);
        $this->dev_cognitive_date->setAllowEmpty(false);
        $this->dev_cognitive_date->addErrorMessage("Cognitive/Thinking Skills date");
        
        $this->dev_communication_desc    = new App_Form_Element_TestEditor('dev_communication_desc  ', array('label' => 'Communication Skills - Current Abilities'));
        $this->dev_communication_desc->setRequired(true);
        $this->dev_communication_desc->setAllowEmpty(false);
        $this->dev_communication_desc->addErrorMessage("Communication Skills Description must be entered.");
        
        $this->dev_communication_date    = new App_Form_Element_DatePicker('dev_communication_date ', array('label' => 'Communication Skills - Date of Evaluation'));
        //$this->dev_communication_date->setAttrib('style', "width:120px;margin-top:-15px;");
        $this->dev_communication_date->setRequired(true);
        $this->dev_communication_date->setAllowEmpty(false);
        $this->dev_communication_date->addErrorMessage("Communication Skills date");
        
        $this->dev_social_desc           = new App_Form_Element_TestEditor('dev_social_desc', array('label' => 'Social/Behavior Skills - Current Abilities'));
        $this->dev_social_desc->setRequired(true);
        $this->dev_social_desc->setAllowEmpty(false);
        $this->dev_social_desc->addErrorMessage("Social/Behavior Skills Description must be entered.");
        
        $this->dev_social_date           = new App_Form_Element_DatePicker('dev_social_date            ', array('label' => 'Social/Behavior Skills - Date of Evaluation'));
        //$this->dev_social_date->setAttrib('style', "width:120px;margin-top:-14px;");
        $this->dev_social_date->setRequired(true);
        $this->dev_social_date->setAllowEmpty(false);
        $this->dev_social_date->addErrorMessage("Social/Behavior Skills date");
        
        $this->dev_self_help_desc        = new App_Form_Element_TestEditor('dev_self_help_desc      ', array('label' => 'Self-Help/Adaptive Skills - Current Abilities'));
        $this->dev_self_help_desc->setRequired(true);
        $this->dev_self_help_desc->setAllowEmpty(false);
        $this->dev_self_help_desc->addErrorMessage("Self-Help/Adaptive Skills Description must be entered.");
        
        $this->dev_self_help_date        = new App_Form_Element_DatePicker('dev_self_help_date     ', array('label' => 'Self-Help/Adaptive Skills - Date of Evaluation'));
        //$this->dev_self_help_date->setAttrib('style', "width:120px;margin-top:-14px;");
        $this->dev_self_help_date->setRequired(true);
        $this->dev_self_help_date->setAllowEmpty(false);
        $this->dev_self_help_date->addErrorMessage("Self-Help/Adaptive Skillsdate");
        
        $this->dev_fine_motor_desc       = new App_Form_Element_TestEditor('dev_fine_motor_desc     ', array('label' => 'Fine Motor Skills - Current Abilities'));
        $this->dev_fine_motor_desc->setRequired(true);
        $this->dev_fine_motor_desc->setAllowEmpty(false);
        $this->dev_fine_motor_desc->addErrorMessage("Fine Motor Skills Description must be entered.");
        
        $this->dev_fine_motor_date       = new App_Form_Element_DatePicker('dev_fine_motor_date        ', array('label' => 'Fine Motor Skills - Date of Evaluation'));
        //$this->dev_fine_motor_date->setAttrib('style', "width:120px;margin-top:-14px;");
        $this->dev_fine_motor_date->setRequired(true);
        $this->dev_fine_motor_date->setAllowEmpty(false);
        $this->dev_fine_motor_date->addErrorMessage("Fine Motor Skills date");
        
        $this->dev_gross_motor_desc      = new App_Form_Element_TestEditor('dev_gross_motor_desc        ', array('label' => 'Gross Motor Skills - Current Abilities'));
        $this->dev_gross_motor_desc->setRequired(true);
        $this->dev_gross_motor_desc->setAllowEmpty(false);
        $this->dev_gross_motor_desc->addErrorMessage("Gross Motor Skills Description must be entered.");
        
        $this->dev_gross_motor_date      = new App_Form_Element_DatePicker('dev_gross_motor_date       ', array('label' => 'Gross Motor Skills - Date of Evaluation'));
        //$this->dev_gross_motor_date->setAttrib('style', "width:120px;margin-top:-14px;");
        $this->dev_gross_motor_date->setRequired(true);
        $this->dev_gross_motor_date->setAllowEmpty(false);
        $this->dev_gross_motor_date->addErrorMessage("Gross Motor Skills date");

        
        $multiOptions = array('all'=>'All', 'sinceannual'=>'Since Last Annual', 'none'=>'None');
		$this->dev_vision_show_ifsps = new App_Form_Element_Radio('dev_vision_show_ifsps', array('label'=>'Show:', 'multiOptions'=>$multiOptions));
		$this->dev_vision_show_ifsps->addValidator(new My_Validate_BooleanNotEmpty('dev_vision_show_ifsps'));
		$this->dev_vision_show_ifsps->setRequired(true);
		$this->dev_vision_show_ifsps->addErrorMessage("Parent Understand Content must be entered.");
		$this->dev_vision_show_ifsps->getDecorator('Label')->setOption('placement', 'prepend');
        $this->dev_vision_show_ifsps->setAttrib('onclick', 'adjustIfspHistoryDisplaydev_vision_desc();');
		
        $multiOptions = array('all'=>'All', 'sinceannual'=>'Since Last Annual', 'none'=>'None');
		$this->dev_hearing_show_ifsps = new App_Form_Element_Radio('dev_hearing_show_ifsps', array('label'=>'Show:', 'multiOptions'=>$multiOptions));
		$this->dev_hearing_show_ifsps->addValidator(new My_Validate_BooleanNotEmpty('dev_hearing_show_ifsps'));
		$this->dev_hearing_show_ifsps->setRequired(true);
		$this->dev_hearing_show_ifsps->addErrorMessage("Parent Understand Content must be entered.");
		$this->dev_hearing_show_ifsps->getDecorator('Label')->setOption('placement', 'prepend');
        $this->dev_hearing_show_ifsps->setAttrib('onclick', 'adjustIfspHistoryDisplaydev_hearing_desc();');

        $multiOptions = array('all'=>'All', 'sinceannual'=>'Since Last Annual', 'none'=>'None');
		$this->dev_health_show_ifsps = new App_Form_Element_Radio('dev_health_show_ifsps', array('label'=>'Show:', 'multiOptions'=>$multiOptions));
		$this->dev_health_show_ifsps->addValidator(new My_Validate_BooleanNotEmpty('dev_health_show_ifsps'));
		$this->dev_health_show_ifsps->setRequired(true);
		$this->dev_health_show_ifsps->addErrorMessage("Parent Understand Content must be entered.");
		$this->dev_health_show_ifsps->getDecorator('Label')->setOption('placement', 'prepend');
        $this->dev_health_show_ifsps->setAttrib('onclick', 'adjustIfspHistoryDisplaydev_health_status_desc();');
        
        $multiOptions = array('all'=>'All', 'sinceannual'=>'Since Last Annual', 'none'=>'None');
		$this->dev_cognitive_show_ifsps = new App_Form_Element_Radio('dev_cognitive_show_ifsps', array('label'=>'Show:', 'multiOptions'=>$multiOptions));
		$this->dev_cognitive_show_ifsps->addValidator(new My_Validate_BooleanNotEmpty('dev_cognitive_show_ifsps'));
		$this->dev_cognitive_show_ifsps->setRequired(true);
		$this->dev_cognitive_show_ifsps->addErrorMessage("Parent Understand Content must be entered.");
		$this->dev_cognitive_show_ifsps->getDecorator('Label')->setOption('placement', 'prepend');
        $this->dev_cognitive_show_ifsps->setAttrib('onclick', 'adjustIfspHistoryDisplaydev_cognitive_desc();');
        
        $multiOptions = array('all'=>'All', 'sinceannual'=>'Since Last Annual', 'none'=>'None');
		$this->dev_communication_show_ifsps = new App_Form_Element_Radio('dev_communication_show_ifsps', array('label'=>'Show:', 'multiOptions'=>$multiOptions));
		$this->dev_communication_show_ifsps->addValidator(new My_Validate_BooleanNotEmpty('dev_communication_show_ifsps'));
		$this->dev_communication_show_ifsps->setRequired(true);
		$this->dev_communication_show_ifsps->addErrorMessage("Parent Understand Content must be entered.");
		$this->dev_communication_show_ifsps->getDecorator('Label')->setOption('placement', 'prepend');
        $this->dev_communication_show_ifsps->setAttrib('onclick', 'adjustIfspHistoryDisplaydev_communication_desc();');
        
        $multiOptions = array('all'=>'All', 'sinceannual'=>'Since Last Annual', 'none'=>'None');
		$this->dev_social_show_ifsps = new App_Form_Element_Radio('dev_social_show_ifsps', array('label'=>'Show:', 'multiOptions'=>$multiOptions));
		$this->dev_social_show_ifsps->addValidator(new My_Validate_BooleanNotEmpty('dev_social_show_ifsps'));
		$this->dev_social_show_ifsps->setRequired(true);
		$this->dev_social_show_ifsps->addErrorMessage("Parent Understand Content must be entered.");
		$this->dev_social_show_ifsps->getDecorator('Label')->setOption('placement', 'prepend');
        $this->dev_social_show_ifsps->setAttrib('onclick', 'adjustIfspHistoryDisplaydev_social_desc();');
        
        $multiOptions = array('all'=>'All', 'sinceannual'=>'Since Last Annual', 'none'=>'None');
		$this->dev_self_show_ifsps = new App_Form_Element_Radio('dev_self_show_ifsps', array('label'=>'Show:', 'multiOptions'=>$multiOptions));
		$this->dev_self_show_ifsps->addValidator(new My_Validate_BooleanNotEmpty('dev_self_show_ifsps'));
		$this->dev_self_show_ifsps->setRequired(true);
		$this->dev_self_show_ifsps->addErrorMessage("Parent Understand Content must be entered.");
		$this->dev_self_show_ifsps->getDecorator('Label')->setOption('placement', 'prepend');
        $this->dev_self_show_ifsps->setAttrib('onclick', 'adjustIfspHistoryDisplaydev_self_help_desc();');
        
        $multiOptions = array('all'=>'All', 'sinceannual'=>'Since Last Annual', 'none'=>'None');
		$this->dev_fine_motor_show_ifsps = new App_Form_Element_Radio('dev_fine_motor_show_ifsps', array('label'=>'Show:', 'multiOptions'=>$multiOptions));
		$this->dev_fine_motor_show_ifsps->addValidator(new My_Validate_BooleanNotEmpty('dev_fine_motor_show_ifsps'));
		$this->dev_fine_motor_show_ifsps->setRequired(true);
		$this->dev_fine_motor_show_ifsps->addErrorMessage("Parent Understand Content must be entered.");
		$this->dev_fine_motor_show_ifsps->getDecorator('Label')->setOption('placement', 'prepend');
        $this->dev_fine_motor_show_ifsps->setAttrib('onclick', 'adjustIfspHistoryDisplaydev_fine_motor_desc();');
        
        $multiOptions = array('all'=>'All', 'sinceannual'=>'Since Last Annual', 'none'=>'None');
		$this->dev_gross_motor_show_ifsps = new App_Form_Element_Radio('dev_gross_motor_show_ifsps', array('label'=>'Show:', 'multiOptions'=>$multiOptions));
		$this->dev_gross_motor_show_ifsps->addValidator(new My_Validate_BooleanNotEmpty('dev_gross_motor_show_ifsps'));
		$this->dev_gross_motor_show_ifsps->setRequired(true);
		$this->dev_gross_motor_show_ifsps->addErrorMessage("Parent Understand Content must be entered.");
		$this->dev_gross_motor_show_ifsps->getDecorator('Label')->setOption('placement', 'prepend');
        $this->dev_gross_motor_show_ifsps->setAttrib('onclick', 'adjustIfspHistoryDisplaydev_gross_motor_desc();');
        
        
        return $this;
	}
	
    public function edit_p5_v2() { return $this->edit_p5_v1();}
    public function edit_p5_v3() { return $this->edit_p5_v1();}
    public function edit_p5_v4() { return $this->edit_p5_v1();}
    public function edit_p5_v5() { return $this->edit_p5_v1();}
    public function edit_p5_v6() { return $this->edit_p5_v1();}
    public function edit_p5_v7() { return $this->edit_p5_v1();}
    public function edit_p5_v8() { return $this->edit_p5_v1();}
    public function edit_p5_v9() { return $this->edit_p5_v1();}
    public function edit_p5_v10() { return $this->edit_p5_v1();}
	public function edit_p5_v1() {
		
		$this->initialize();
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form013/form013_edit_page5_version1.phtml' ) ) ) );

		
	
		return $this;
	}
	
    public function edit_p6_v2() { return $this->edit_p6_v1();}
    public function edit_p6_v3() { return $this->edit_p6_v1();}
    public function edit_p6_v4() { return $this->edit_p6_v1();}
    public function edit_p6_v5() { return $this->edit_p6_v1();}
    public function edit_p6_v6() { return $this->edit_p6_v1();}
    public function edit_p6_v7() { return $this->edit_p6_v1();}
    public function edit_p6_v8() { return $this->edit_p6_v1();}
    public function edit_p6_v9() { return $this->edit_p6_v1();}
    public function edit_p6_v10() { return $this->edit_p6_v1();}
	public function edit_p6_v1() {

		$this->initialize();
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form013/form013_edit_page6_version1.phtml' ) ) ) );
	
        $this->service_sp_conditions = new App_Form_Element_TestEditor('service_sp_conditions', array('label'=>'Are there special conditions for safe transportation for this child?'));
		$this->service_sp_conditions->setRequired(true);
        $this->service_sp_conditions->setAllowEmpty(false);
		$this->service_sp_conditions->addErrorMessage("Special conditions for transportation must be entered.");
	
        
		return $this;
	}
	
    public function edit_p7_v2() { return $this->edit_p7_v1();}
    public function edit_p7_v3() { return $this->edit_p7_v1();}
    public function edit_p7_v4() { return $this->edit_p7_v1();}
    public function edit_p7_v5() { return $this->edit_p7_v1();}
    public function edit_p7_v6() { return $this->edit_p7_v1();}
    public function edit_p7_v7() { return $this->edit_p7_v1();}
    public function edit_p7_v8() { return $this->edit_p7_v1();}
    public function edit_p7_v9() { return $this->edit_p7_v1();}
    public function edit_p7_v10() { return $this->edit_p7_v1();}
	public function edit_p7_v1() {

		$this->initialize();
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form013/form013_edit_page7_version1.phtml' ) ) ) );

         $this->dob = new App_Form_Element_Hidden('dob');

        $this->transition_plan = new App_Form_Element_Checkbox('transition_plan');
        $this->transition_plan->setAttrib('onclick', 'enableDisableTransitionPlan(this.checked);');
        $this->transition_plan->setDecorators(array('viewHelper')); 
        // override standard decorators
	    
        $this->tran_con_date = new App_Form_Element_DatePicker('tran_con_date', array('label'=>'Transition Conference Date:'));
        $this->tran_con_date->boldLabelPrint();
		$this->tran_con_date->setRequired(false);
        $this->tran_con_date->setAllowEmpty(false);
		$this->tran_con_date->addErrorMessage("Transition Conference Date must be entered.");
		$this->tran_con_date->addValidator(new My_Validate_Form013TransitionPlan('dob', '+2 years 275 days'));
//		$this->tran_con_date->addValidator(new My_Validate_NoValidationIfAgeOver('dob', '+2 years 275 days'));
//        $this->tran_con_date->addValidator(new My_Validate_NoValidationIf('transition_plan', null));
        //$this->lps_sig_no_agree_reason->addValidator(new My_Validate_NotEmptyIf('lps_sig_agree', true));
        
        $this->extimated_tran_date = new App_Form_Element_DatePicker('extimated_tran_date', array('label'=>'Estimated Transition Date:'));
        $this->extimated_tran_date->boldLabelPrint();
		$this->extimated_tran_date->setRequired(false);
        $this->extimated_tran_date->setAllowEmpty(false);
		$this->extimated_tran_date->addErrorMessage("Estimated Transition Date must be entered.");
		$this->extimated_tran_date->addValidator(new My_Validate_Form013TransitionPlan('dob', '+2 years 275 days'));
//    	$this->extimated_tran_date->addValidator(new My_Validate_NoValidationIfAgeOver('dob', '+2 years 275 days'));
//        $this->extimated_tran_date->addValidator(new My_Validate_NoValidationIf('transition_plan', null));
    	
		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->parent_understand_content_p7 = new App_Form_Element_Radio('parent_understand_content_p7', array('label'=>'___I/We consent to the continuation of early intervention services for my/our child and family through an IFSP after my/our child\'s third birthday.', 'multiOptions'=>$multiOptions));
		//$this->parent_understand_content_p7->addValidator(new My_Validate_BooleanNotEmpty('parent_understand_content_p7'));
		$this->parent_understand_content_p7->setRequired(false);
		$this->parent_understand_content_p7->setAllowEmpty(true);
		$this->parent_understand_content_p7->addErrorMessage("Parent Understand Content must be entered.");
		
		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->parent_understand_distribition_p7 = new App_Form_Element_Radio('parent_understand_distribition_p7', array('label'=>'___I/We request initiation of preschool special education services for my/our child and family at or after age 3.', 'multiOptions'=>$multiOptions));
		$this->parent_understand_distribition_p7->addValidator(new My_Validate_BooleanNotEmpty('parent_understand_distribition_p7'));
		$this->parent_understand_distribition_p7->setRequired(false);
		$this->parent_understand_distribition_p7->setAllowEmpty(true);
		$this->parent_understand_distribition_p7->addErrorMessage("Parent Understand Distribution must be entered.");
		
		$this->parent_sig_1_p7 = new App_Form_Element_Text('parent_sig_1_p7', array('label'=>'Parent Signature 1'));
		$this->parent_sig_1_p7->setRequired(false);
		$this->parent_sig_1_p7->setAllowEmpty(true);
		$this->parent_sig_1_p7->addErrorMessage("Parent Signature 1 must be entered.");
		$this->parent_sig_1_p7->removeDecorator('label');
		
		$this->parent_sig_2_p7 = new App_Form_Element_Text('parent_sig_2_p7', array('label'=>'Parent Signature 2'));
		$this->parent_sig_2_p7->setRequired(false);
		$this->parent_sig_2_p7->setAllowEmpty(true);
		$this->parent_sig_2_p7->removeDecorator('label');
		
		$this->parent_date_1_p7 = new App_Form_Element_DatePicker('parent_date_1_p7', array('label'=>'Parent Signature Date 1'));
		$this->parent_date_1_p7->setRequired(false);
		$this->parent_date_1_p7->setAllowEmpty(true);
		$this->parent_date_1_p7->addErrorMessage("Parent Date 1 must be entered if there is a signature on file.");
		$this->parent_date_1_p7->removeDecorator('label');
		 
		$this->parent_date_2_p7 = new App_Form_Element_DatePicker('parent_date_2_p7', array('label'=>'Parent Signature Date 2'));
		$this->parent_date_2_p7->setRequired(false);
		$this->parent_date_2_p7->setAllowEmpty(true);
		$this->parent_date_2_p7->addValidator(new My_Validate_NotEmptyIf('parent_sig_2_on_file_p7', '1'));
		$this->parent_date_2_p7->addErrorMessage("Parent Date 2 must be entered if there is a signature on file.");
		$this->parent_date_2_p7->removeDecorator('label');
		
		
		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->parent_sig_1_on_file_p7 = new App_Form_Element_Radio('parent_sig_1_on_file_p7', array('label'=>'Parent Signature 1', 'multiOptions'=>$multiOptions));
		//$this->parent_sig_1_on_file_p7->addValidator(new My_Validate_BooleanNotEmpty());
		$this->parent_sig_1_on_file_p7->setRequired(false);
		$this->parent_sig_1_on_file_p7->setAllowEmpty(true);
		$this->parent_sig_1_on_file_p7->addErrorMessage("Parent Signature On File 1 must be entered.");
		$this->parent_sig_1_on_file_p7->removeDecorator('Label');
		$this->parent_sig_1_on_file_p7->removeDecorator('HtmlTag');
		
		$multiOptions = array('1'=>'Yes', '0'=>'No');
		$this->parent_sig_2_on_file_p7 = new App_Form_Element_Radio('parent_sig_2_on_file_p7', array('label'=>'Parent Signature 2', 'multiOptions'=>$multiOptions));
		$this->parent_sig_2_on_file_p7->setRequired(false);
		$this->parent_sig_2_on_file_p7->setAllowEmpty(true);
		$this->parent_sig_2_on_file_p7->removeDecorator('Label');
		$this->parent_sig_2_on_file_p7->removeDecorator('HtmlTag');
		$this->parent_sig_2_on_file_p7->addValidator(new My_Validate_BooleanNotEmpty());
		 
		$this->parent_sig_on_file_explain_p7 = new App_Form_Element_TestEditor('parent_sig_on_file_explain_p7', array('label'=>'Parent Signature On File Explanation'));
		$this->parent_sig_on_file_explain_p7->setRequired(false);
		$this->parent_sig_on_file_explain_p7->setAllowEmpty(false);
		$this->parent_sig_on_file_explain_p7->removeEditorEmptyValidator();
		$this->parent_sig_on_file_explain_p7->addValidator(new My_Validate_IFSPParentSigsP7());
		$this->parent_sig_on_file_explain_p7->addErrorMessage("Please indicate why signature(s) are not on file.");
		
		
        return $this;
	}
	
    public function edit_p8_v2() { return $this->edit_p8_v1();}
    public function edit_p8_v3() { return $this->edit_p8_v1();}
    public function edit_p8_v4() { return $this->edit_p8_v1();}
    public function edit_p8_v5() { return $this->edit_p8_v1();}
    public function edit_p8_v6() { return $this->edit_p8_v1();}
    public function edit_p8_v7() { return $this->edit_p8_v1();}
    public function edit_p8_v8() { return $this->edit_p8_v1();}
    public function edit_p8_v9() { return $this->edit_p8_v1();}
    public function edit_p8_v10() { return $this->edit_p8_v1();}
	public function edit_p8_v1() {

		$this->initialize();
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form013/form013_edit_page8_version1.phtml' ) ) ) );
	
		return $this;
	}
	
    public function edit_p9_v2() { return $this->edit_p9_v1();}
    public function edit_p9_v3() { return $this->edit_p9_v1();}
    public function edit_p9_v4() { return $this->edit_p9_v1();}
    public function edit_p9_v5() { return $this->edit_p9_v1();}
    public function edit_p9_v6() { return $this->edit_p9_v1();}
    public function edit_p9_v7() { return $this->edit_p9_v1();}
    public function edit_p9_v8() { return $this->edit_p9_v1();}
    public function edit_p9_v9() { return $this->edit_p9_v1();}
    public function edit_p9_v10() { return $this->edit_p9_v1();}
	public function edit_p9_v1() {

		$this->initialize();
		
		// Setting the decorator for the element to a single, ViewScript, decorator,
		// specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form013/form013_edit_page9_version1.phtml' ) ) ) );

	   
		
		/*
        $multiOptions = array('1'=>'Yes', '0'=>'No');
        $this->parent_understand_content = new App_Form_Element_Radio('parent_understand_content', array('label'=>'I (we) understand the content of the IFSP and give consent for all services in the IFSP to begin unless indicated below.', 'multiOptions'=>$multiOptions));
		$this->parent_understand_content->addValidator(new My_Validate_BooleanNotEmpty('parent_understand_content'));
		$this->parent_understand_content->setRequired(true);
		$this->parent_understand_content->addErrorMessage("Parent Understand Content must be entered.");
		*/
		
		/*
        $multiOptions = array('1'=>'Yes', '0'=>'No');
        $this->parent_understand_distribition = new App_Form_Element_Radio('parent_understand_distribition', array('label'=>'I (we) understand that a copy of the IFSP will be distributed within 7 calendar days.', 'multiOptions'=>$multiOptions));
        $this->parent_understand_distribition->addValidator(new My_Validate_BooleanNotEmpty('parent_understand_distribition'));
		$this->parent_understand_distribition->setRequired(true);
		$this->parent_understand_distribition->addErrorMessage("Parent Understand Distribution must be entered.");
	    */
	
        $this->parent_sig_1 = new App_Form_Element_Text('parent_sig_1', array('label'=>'Parent Signature 1'));
		$this->parent_sig_1->setRequired(false);
        $this->parent_sig_1->setAllowEmpty(true);
        $this->parent_sig_1->addValidator(new My_Validate_EmptyIf('parent_sig_1_on_file_frequency', '1'));
		$this->parent_sig_1->addErrorMessage("Parent Signature 1 must be entered if Parent Signature on File is Yes and blank if Parent Signature on File for Frequency Consent is Yes.");
		$this->parent_sig_1->removeDecorator('label');
	
        $this->parent_sig_2 = new App_Form_Element_Text('parent_sig_2', array('label'=>'Parent Signature 2'));
        $this->parent_sig_2->setRequired(false);
        $this->parent_sig_2->setAllowEmpty(true);
        $this->parent_sig_2->addValidator(new My_Validate_EmptyIf('parent_sig_1_on_file_frequency', '1'));
        $this->parent_sig_2->addErrorMessage("Parent Signature 2 must be blank if Parent Signature on File for Frequency Consent is Yes.");
		$this->parent_sig_2->removeDecorator('label');
		
        $this->parent_date_1 = new App_Form_Element_DatePicker('parent_date_1', array('label'=>'Parent Signature Date 1'));
		$this->parent_date_1->setRequired(false);
        $this->parent_date_1->setAllowEmpty(true);
        $this->parent_date_1->addValidator(new My_Validate_NotEmptyIf('parent_sig_1_on_file', '1'));
		$this->parent_date_1->addErrorMessage("Parent Date 1 must be entered if there is a signature on file.");
		$this->parent_date_1->removeDecorator('label');
					      
        $this->parent_date_2 = new App_Form_Element_DatePicker('parent_date_2', array('label'=>'Parent Signature Date 2'));
        $this->parent_date_2->setRequired(false);
        $this->parent_date_2->setAllowEmpty(true);
		$this->parent_date_2->addValidator(new My_Validate_NotEmptyIf('parent_sig_2_on_file', '1'));
		$this->parent_date_2->addErrorMessage("Parent Date 2 must be entered if there is a signature on file.");
		$this->parent_date_2->removeDecorator('label');

        
        $multiOptions = array('1'=>'Yes', '0'=>'No');
        $this->parent_sig_1_on_file = new App_Form_Element_Radio('parent_sig_1_on_file', array('label'=>'Parent Signature 1', 'multiOptions'=>$multiOptions));
        $this->parent_sig_1_on_file->setRequired(false);
		$this->parent_sig_1_on_file->setAllowEmpty(true);
		$this->parent_sig_1_on_file->addErrorMessage("Parent Signature On File 1 must be entered.");
    	$this->parent_sig_1_on_file->removeDecorator('Label');
    	$this->parent_sig_1_on_file->removeDecorator('HtmlTag');
        
        $multiOptions = array('1'=>'Yes', '0'=>'No');
        $this->parent_sig_2_on_file = new App_Form_Element_Radio('parent_sig_2_on_file', array('label'=>'Parent Signature 2', 'multiOptions'=>$multiOptions));
        $this->parent_sig_2_on_file->setRequired(false);
        $this->parent_sig_2_on_file->setAllowEmpty(true);
        $this->parent_sig_2_on_file->removeDecorator('Label');
    	$this->parent_sig_2_on_file->removeDecorator('HtmlTag');
		$this->parent_sig_2_on_file->addValidator(new My_Validate_BooleanNotEmpty());
    	
        $this->parent_sig_on_file_explain = new App_Form_Element_TestEditor('parent_sig_on_file_explain', array('label'=>'Parent Signature On File Explanation'));
        $this->parent_sig_on_file_explain->setRequired(false);
        $this->parent_sig_on_file_explain->setAllowEmpty(false);
        $this->parent_sig_on_file_explain->removeEditorEmptyValidator();
        $this->parent_sig_on_file_explain->addValidator(new My_Validate_IFSPParentSigs());
		$this->parent_sig_on_file_explain->addErrorMessage("Please indicate why signature(s) are not on file.");

        $this->parent_comments = new App_Form_Element_TestEditor('parent_comments', array('label'=>'Comments'));
        $this->parent_comments->setRequired(false);
        $this->parent_comments->setAllowEmpty(false);
        $this->parent_comments->removeEditorEmptyValidator();
        
        $this->frequency_consent = new App_Form_Element_TestEditor('frequency_consent', array('label'=>'I/We do not agree with the proposed IFSP as written. However, I/we do consent to the following services/frequency:'));
        $this->frequency_consent->setRequired(false);
        $this->frequency_consent->setAllowEmpty(false);
        $this->frequency_consent->removeEditorEmptyValidator();
        
        $this->parent_sig_1_frequency = new App_Form_Element_Text('parent_sig_1_frequency', array('label'=>'Parent Signature 1'));
        $this->parent_sig_1_frequency->setRequired(false);
        $this->parent_sig_1_frequency->setAllowEmpty(true);
        $this->parent_sig_1_frequency->addValidator(new My_Validate_EmptyIf('parent_sig_1_on_file', '1'));
		$this->parent_sig_1_frequency->addErrorMessage("Parent Signature 1 for Frequency Consent must be entered if Parent Signature on File for Frequency Consent is Yes and blank if Parent Signature on File is Yes.");
        $this->parent_sig_1_frequency->removeDecorator('label');
        
        $this->parent_sig_2_frequency = new App_Form_Element_Text('parent_sig_2_frequency', array('label'=>'Parent Signature 2'));
        $this->parent_sig_2_frequency->setRequired(false);
        $this->parent_sig_2_frequency->setAllowEmpty(true);
        $this->parent_sig_2_frequency->addValidator(new My_Validate_EmptyIf('parent_sig_1_on_file', '1'));
		$this->parent_sig_2_frequency->addErrorMessage("Parent Signature 2 for Frequency Consent must be blank if Parent Signature on File is Yes.");       
        $this->parent_sig_2_frequency->removeDecorator('label');
        
        $this->parent_date_1_frequency = new App_Form_Element_DatePicker('parent_date_1_frequency', array('label'=>'Parent Signature Date 1'));
        $this->parent_date_1_frequency->setRequired(false);
        $this->parent_date_1_frequency->setAllowEmpty(true);
        $this->parent_date_1_frequency->addValidator(new My_Validate_NotEmptyIf('parent_sig_1_on_file_frequency', '1'));
        $this->parent_date_1_frequency->addErrorMessage("Parent Date 1 must be entered if there is a signature on file.");
        $this->parent_date_1_frequency->removeDecorator('label');
         
        $this->parent_date_2_frequency = new App_Form_Element_DatePicker('parent_date_2_frequency', array('label'=>'Parent Signature Date 2'));
        $this->parent_date_2_frequency->setRequired(false);
        $this->parent_date_2_frequency->setAllowEmpty(true);
        $this->parent_date_2_frequency->addValidator(new My_Validate_NotEmptyIf('parent_sig_2_on_file_frequency', '1'));
        $this->parent_date_2_frequency->addErrorMessage("Parent Date 2 must be entered if there is a signature on file.");
        $this->parent_date_2_frequency->removeDecorator('label');
        
        
        $multiOptions = array('1'=>'Yes', '0'=>'No');
        $this->parent_sig_1_on_file_frequency = new App_Form_Element_Radio('parent_sig_1_on_file_frequency', array('label'=>'Parent Signature 1', 'multiOptions'=>$multiOptions));
        $this->parent_sig_1_on_file_frequency->setRequired(false);
        $this->parent_sig_1_on_file_frequency->setAllowEmpty(true);
        $this->parent_sig_1_on_file_frequency->removeDecorator('Label');
        $this->parent_sig_1_on_file_frequency->removeDecorator('HtmlTag');
        
        $multiOptions = array('1'=>'Yes', '0'=>'No');
        $this->parent_sig_2_on_file_frequency = new App_Form_Element_Radio('parent_sig_2_on_file_frequency', array('label'=>'Parent Signature 2', 'multiOptions'=>$multiOptions));
        $this->parent_sig_2_on_file_frequency->setRequired(false);
        $this->parent_sig_2_on_file_frequency->setAllowEmpty(true);
        $this->parent_sig_2_on_file_frequency->removeDecorator('Label');
        $this->parent_sig_2_on_file_frequency->removeDecorator('HtmlTag');
        $this->parent_sig_2_on_file_frequency->addValidator(new My_Validate_BooleanNotEmpty());
		//Require comments if parents do not understand content.
//        $this->parent_comments->addValidator(new My_Validate_NotEmptyIf('parent_understand_content', '0'));
//		$this->parent_comments->addErrorMessage("Comments must be entered if No is answered to the question of Parents Understanding the content of the IFSP.");
        
        return $this;
	}

    function getIfspSecondaryRole($options)
    {
    	// update
		if(!isset($options['ifsptype'])) {
                return array();
		}  else {
			$ifspType = $options['ifsptype'];
		}
    	switch($ifspType)
        {
            case "Annual":
                $arrLabel = array("Annual", "Annual/Transition");
                $arrValue = array("Annual", "Annual/Transition");
                break;
            case "Periodic":
                $arrLabel = array("Periodic", "Periodic/Transition");
                $arrValue = array("Periodic", "Periodic/Transition");
                break;
            case "Initial":
                $arrLabel = array("None", "Transition");
                $arrValue = array("None", "Transition");
                break;
            default:
                $arrLabel = array($ifspType);
                $arrValue = array($ifspType);
        }               
        return array_combine($arrValue, $arrLabel);
    }
}
