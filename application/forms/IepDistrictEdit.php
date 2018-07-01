<?php

class Application_Form_IepDistrictEdit extends Zend_Form
{

   
   public $defaultDecorators =array('ViewHelper'=>array(),'Errors'=>array(),'HtmlTag'=>array(),'Label'=>array());
    
    public $formDecorators = array(
        array(
            'FormElement'
        ),
        array(
            'Form'
        )
    );

    public $elementDecoratorsChk = array(
        'ViewHelper' => array(),
        'Label'=> array(
            'placement' => 'append'
        ),
        'Errors'=> array(),
        'HtmlTag'=>array('tag'=>'b')
    );
    
    
    public $elementDecorators = array(
        array('ViewHelper'),
        array('Errors'),
   //     array('HtmlTag'=>array('tag'=>'h3')),
        array('Label'=>array('placement'=>'wrap'))
        );

    public $buttondecorators = array(
        array(
            'ViewHelper'
        ),
        array(
            'HtmlTag',
            array(
                'tag' => 'p'
            )
        )
    );
    
 
 
    public function init()
    {
       $this->clearDecorators();
        
        
        $this->setName('name_district');
        
        $name_district = new Zend_Form_Element_Text('name_district');
        $name_district->setLabel('District Names')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')
            ->setDecorators($this->elementDecorators);
            
          
        
        $id_district = new Zend_Form_Element_Text('id_district');
        $id_district->setLabel('District Id')
            ->
        // ->$id_district->addFilter('Int')
        addValidator('NotEmpty')
            ->addValidator('Digits')
            ->setDecorators($this->elementDecorators);
            
        
        $id_county = new Zend_Form_Element_Hidden('id_county');
        
        $phone_main = new Zend_Form_Element_Text('phone_main');
        $phone_main->setLabel('Phone')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        
        $address_street1 = new Zend_Form_Element_Text('address_street1');
        $address_street1->setLabel('Address')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
        
        $add_resource1 = new Zend_Form_Element_Text('add_resource1');
        $add_resource1->setLabel('Resource 1')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        
        // added auto by mike start
        $id_author = new Zend_Form_Element_Hidden('id_author');
        
        $id_author_last_mod = new Zend_Form_Element_Hidden('id_author_last_mod');
        $timestamp_created = new Zend_Form_Element_Hidden('timestamp_created');
        $timestamp_last_mod = new Zend_Form_Element_Hidden('timestamp_last_mod');
        
        $status = new Zend_Form_Element_Text('status');
        $status->setLabel('Status')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
            
        
        $id_district_mgr = new Zend_Form_Element_Text('id_district_mgr');
        $id_district_mgr->setLabel('District Manager ID')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
            
        
        $id_account_sprv = new Zend_Form_Element_Text('id_account_sprv');
        $id_account_sprv->setLabel('Account Supervisor')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
            
        
        $checkout_id_user = new Zend_Form_Element_Hidden('checkout_id_user');
        
        $checkout_time = new Zend_Form_Element_Hidden('checkout_time');
        
        $address_street2 = new Zend_Form_Element_Text('address_street2');
        $address_street2->setLabel('Address 2')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $address_city = new Zend_Form_Element_Text('address_city');
        $address_city->setLabel('address_city')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $address_state = new Zend_Form_Element_Text('address_state');
        $address_state->setLabel('State')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $address_zip = new Zend_Form_Element_Text('address_zip');
        $address_zip->setLabel('Zip Code')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $logo_flag = new Zend_Form_Element_Hidden('logo_flag');
        
        $add_resource2 = new Zend_Form_Element_Text('add_resource2');
        $add_resource2->setLabel('Resource 2')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $sch_yr_start_mth = new Zend_Form_Element_Text('sch_yr_start_mth');
        $sch_yr_start_mth->setLabel('Start Mounth')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $sch_yr_start_day = new Zend_Form_Element_Text('sch_yr_start_day');
        $sch_yr_start_day->setLabel('Start Day')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $sch_yr_end_month = new Zend_Form_Element_Text('sch_yr_end_month');
        $sch_yr_end_month->setLabel('End Month')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $sch_yr_end_day = new Zend_Form_Element_Text('sch_yr_end_day');
        $sch_yr_end_day->setLabel('End Day')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        // Developmental Delay Cutoff
        $dev_delay_cutoff_age = new Zend_Form_Element_Text('dev_delay_cutoff_age');
        $dev_delay_cutoff_age->setLabel('Development Delay Cutoff Age')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        // This is a yes or no
        $use_goal_helper = new Zend_Form_Element_Checkbox('use_goal_helper');
        $use_goal_helper->setLabel('Use Goal Helr') 
            
            ->setRequired(false);
        die();
        
        $use_form_011 = new Zend_Form_Element_Checkbox('use_form_011');
        $use_form_011->setLabel('MDT Conference')
            ->setRequired(false)
           
            ->setRequired(false);
        
        $use_form_012 = new Zend_Form_Element_Checkbox('use_form_012');
        $use_form_012->setLabel('Determination Notice')
       
            ->setRequired(false);
           
        
        $optional_features = new Zend_Form_Element_CheckBox('optional_features');
        $optional_features->setLabel('Accept Optional Features')
            ->setRequired(false);
            
        
        $id_district_mgr_old = new Zend_Form_Element_Hidden('id_district_mgr_old');
        // can take this one out
        
        $assurance_stmt = new Zend_Form_Element_checkbox('assurance_stmt');
        $assurance_stmt->setLabel('Assurance Statement')
        ->setRequired(false)
        ->setRequired(false);
        
        
       
          
           
            
            
            
            
            
            
            
            
            
            
        $pref_district_imports = new Zend_Form_Element_Checkbox('pref_district_imports');
        $pref_district_imports->setLabel('District to Import')->setRequired(false);
        
        $district_import_code = new Zend_Form_Element_Text('district_import_code');
        $district_import_code->setLabel('district_import_code')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $use_accomodations_checklist = new Zend_Form_Element_Checkbox('use_accomodations_checklist');
        $use_accomodations_checklist->setLabel('Accomondations Checklist')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            // array('HtmlTag',array('tag'=>'br')),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $use_iep_benchmarks = new Zend_Form_Element_Checkbox('use_iep_benchmarks');
        $use_iep_benchmarks->setLabel('Include benchmarks on progress reports')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            // array('HtmlTag',array('tag'=>'br')),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $fedrep_email = new Zend_Form_Element_Text('fedrep_email');
        $fedrep_email->setLabel('Fed Rep Email')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            array(
                'HtmlTag',
                array(
                    'tag' => 'br'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $fedrep_send_tonight = new Zend_Form_Element_Checkbox('fedrep_send_tonight');
        $fedrep_send_tonight->setLabel('Send SPP Data to:')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            // array('HtmlTag',array('tag'=>'br')),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $use_form_019 = new Zend_Form_Element_Checkbox('use_form_019');
        $use_form_019->setLabel('Functional Assessment')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            // array('HtmlTag',array('tag'=>'br')),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $use_form_020 = new Zend_Form_Element_Checkbox('use_form_020');
        $use_form_020->setLabel('Specialized Transportation')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            array(
                'HtmlTag',
                array(
                    'tag' => 'br'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $use_form_021 = new Zend_Form_Element_Checkbox('use_form_021');
        $use_form_021->setLabel('Asstive Tech Considerations')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            // array('HtmlTag',array('tag'=>'br')),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $use_nssrs = new Zend_Form_Element_Checkbox('use_nssrs');
        $use_nssrs->setLabel('Create NSSRS File')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            array(
                'HtmlTag',
                array(
                    'tag' => 'br'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $email_nssrs = new Zend_Form_Element_Text('email_nssrs');
        $email_nssrs->setLabel('NSSRS Email')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            // array('HtmlTag',array('tag'=>'br')),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $nssrs_send_tonight = new Zend_Form_Element_Checkbox('nssrs_send_tonight');
        $nssrs_send_tonight->setLabel('Send nssrs file tonight')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            array(
                'HtmlTag',
                array(
                    'tag' => 'br'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $use_nssrs_overview = new Zend_Form_Element_Checkbox('use_nssrs_overview');
        $use_nssrs_overview->setLabel('Create NSSRS Overview file')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            // array('HtmlTag',array('tag'=>'br')),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $email_student_transfers_to = new Zend_Form_Element_Text('email_student_transfers_to');
        $email_student_transfers_to->setLabel('Email Transfers To:')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            // array('HtmlTag',array('tag'=>'br')),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $use_zf_forms = new Zend_Form_Element_Checkbox('use_zf_forms');
        $use_zf_forms->setLabel('Use New Style Forms')
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            array(
                'HtmlTag',
                array(
                    'tag' => 'br'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        )
        );
        
        $iep_summary_special_considerations = new Zend_Form_Element_Checkbox('iep_summary_special_considerations');
        $iep_summary_special_considerations->setLabel('Special Considerations')->setRequired(false);
        
        // Student Strengths iep sum form
        $iep_summary_student_strengths = new Zend_Form_Element_Text('iep_summary_student_strengths');
        $iep_summary_student_strengths->setLabel('Student Strengths')->setRequired(false);
        
        // Parental Information checkbox iep sum form
        $iep_summary_parental_concerns = new Zend_Form_Element_Checkbox('iep_summary_parental_concerns');
        $iep_summary_parental_concerns->setLabel('Parental Information')->setRequired(false);
        
        // Results of Initial Recent Evaluation checkbox
        $iep_summary_results_evaluation = new Zend_Form_Element_Checkbox('iep_summary_results_evaluation');
        $iep_summary_results_evaluation->setLabel('Results of Initial Evaluation')->setRequired(false);
        
        $iep_summary_results_perf = new Zend_Form_Element_Checkbox('iep_summary_results_perf');
        $iep_summary_results_perf->setLabel('Performance of any general stat and district-wide-assessments')->setRequired(false);
        
        // Performance of any general stat and district wide assessments iep sum checkbox
        $iep_summary_behavioral_strategies = new Zend_Form_Element_Checkbox('iep_summary_behavioral_strategies');
        $iep_summary_behavioral_strategies->setLabel('Consideration of appropriate behavioral strategies');
        
        // Consideration of language needs iep sum form checkbox
        $iep_summary_language_needs = new Zend_Form_Element_Checkbox('iep_summary_language_needs');
        $iep_summary_language_needs->setLabel('Consideration of language needs:')->setRequired(false);
        
        // Braille instruction iep sum form checkbox
        $iep_summary_braille_instruction = new Zend_Form_Element_Checkbox('iep_summary_braille_instruction');
        $iep_summary_braille_instruction->setLabel('Braille Instruction')->setRequired(false);
        
        // Communication Needs iep form checkbox
        $iep_summary_comm_needs = new Zend_Form_Element_Checkbox('iep_summary_comm_needs');
        $iep_summary_comm_needs->setLabel('Communication Needs')->setRequired(false);
        
        // Deaf/Hard of Hearing : Communication Needs iep sum form checkbox
        $iep_summary_deaf_comm_needs = new Zend_Form_Element_Checkbox('iep_summary_deaf_comm_needs');
        $iep_summary_deaf_comm_needs->setLabel('Deaf Hard of Hearing : Communication Needs')->setRequired(false);
        
        // Deaf/Hard of Hearing Direct Communication iep sum form checkbox
        $iep_summary_deaf_comm_opp = new Zend_Form_Element_Checkbox('iep_summary_deaf_comm_opp');
        $iep_summary_deaf_comm_opp->setLabel('Deaf/Hard of Hearing Direct Communication')->setRequired(false);
        
        $iep_summary_deaf_academic_lev = new Zend_Form_Element_Checkbox('iep_summary_deaf_academic_lev');
        $iep_summary_deaf_academic_lev->setLabel('Deaf Hard of Hearing: Opportunities for Direct Instruction')->setRequired(false);
        
        $iep_summary_assistive_tech = new Zend_Form_Element_Checkbox('iep_summary_assistive_tech');
        $iep_summary_assistive_tech->setLabel('Considerations of Child\'s need for assistive Technology')->setRequired(false);
        
        $iep_summary_present_lev_perf = new Zend_Form_Element_Checkbox('iep_summary_present_lev_perf');
        $iep_summary_present_lev_perf->setLabel('Present level of Performance')->setRequired(false);
        
        $iep_summary_goals = new Zend_Form_Element_Text('iep_summary_goals');
        $iep_summary_goals->setLabel('Goals')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $iep_summary_measurable_ann_goal = new Zend_Form_Element_Text('iep_summary_measurable_ann_goal');
        $iep_summary_measurable_ann_goal->setLabel('Measuarable Annual Goals')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $iep_summary_short_term_obj = new Zend_Form_Element_Text('iep_summary_short_term_obj');
        $iep_summary_short_term_obj->setLabel('Short Term Objective')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $iep_summary_schedule = new Zend_Form_Element_Text('iep_summary_schedule');
        $iep_summary_schedule->setLabel('Schedule')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $iep_summary_eval_procedure = new Zend_Form_Element_Text('iep_summary_eval_procedure');
        $iep_summary_eval_procedure->setLabel('Evaluation Procedure')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $iep_summary_person_responsible = new Zend_Form_Element_Text('iep_summary_person_responsible');
        $iep_summary_person_responsible->setLabel('Person Responsible')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $iep_summary_progress = new Zend_Form_Element_Text('iep_summary_progress');
        $iep_summary_progress->setLabel('Progress')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $iep_summary_transition = new Zend_Form_Element_Checkbox('iep_summary_transition');
        $iep_summary_transition->setLabel('Transition')->setRequired(false);
        
        $iep_summary_transition_secgoals = new Zend_Form_Element_Checkbox('iep_summary_transition_secgoals');
        $iep_summary_transition_secgoals->setLabel('Post Secondary Goals')->setRequired(false);
        
        $iep_summary_transition_16_course_study = new Zend_Form_Element_Checkbox('iep_summary_transition_16_course_study');
        $iep_summary_transition_16_course_study->setLabel('Course of Study')->setRequired(false);
        
        $iep_summary_transition_16_instruction = new Zend_Form_Element_Checkbox('iep_summary_transition_16_instruction');
        $iep_summary_transition_16_instruction->setLabel('Statement of Needed Transition Service')->setRequired(false);
        
        $iep_summary_transition_16_rel_services = new Zend_Form_Element_Checkbox('iep_summary_transition_16_rel_services');
        $iep_summary_transition_16_rel_services->setLabel('Related Transition Services')->setRequired(false);
        
        $iep_summary_transition_16_comm_exp = new Zend_Form_Element_Checkbox('iep_summary_transition_16_comm_exp');
        $iep_summary_transition_16_comm_exp->setLabel('Community Experiences')->setRequired(false);
        
        $iep_summary_transition_16_emp_options = new Zend_Form_Element_Checkbox('iep_summary_transition_16_emp_options');
        $iep_summary_transition_16_emp_options->setLabel('Employment and Adult Living Objectives')->setRequired(false);
        
        $iep_summary_transition_16_dly_liv_skills = new Zend_Form_Element_Checkbox('iep_summary_transition_16_dly_liv_skills');
        $iep_summary_transition_16_dly_liv_skills->setLabel('Daily Living Skills')->setRequired(false);
        
        $iep_summary_transition_16_func_voc_eval = new Zend_Form_Element_Checkbox('iep_summary_transition_16_func_voc_eval');
        $iep_summary_transition_16_func_voc_eval->setLabel('Functional Vocational Evaluation')->setRequired(false);
        
        $iep_summary_transition_16_inter_agency_link = new Zend_Form_Element_Checkbox('iep_summary_transition_16_inter_agency_link');
        $iep_summary_transition_16_inter_agency_link->setLabel('Interagency Linkages')->setRequired(false);
        
        $iep_summary_transition_activity = new Zend_Form_Element_Checkbox('iep_summary_transition_activity');
        $iep_summary_transition_activity->setLabel('Transition Activities')->setRequired(false);
        
        $iep_summary_services = new Zend_Form_Element_Checkbox('iep_summary_services');
        $iep_summary_services->setLabel('Services')->setRequired(false);
        
        $iep_summary_primary_disability = new Zend_Form_Element_Checkbox('iep_summary_primary_disability');
        $iep_summary_primary_disability->setLabel('Statement of Special Education and Related Services')->setRequired(false);
        
        $iep_summary_primary_service = new Zend_Form_Element_Checkbox('iep_summary_primary_service');
        $iep_summary_primary_service->setLabel('Primary Services')->setRequired(false);
        
        $iep_summary_related_service = new Zend_Form_Element_Checkbox('iep_summary_related_service');
        $iep_summary_related_service->setLabel('Related Services')->setRequired(false);
        
        $iep_summary_supp_service = new Zend_Form_Element_Checkbox('iep_summary_supp_service');
        $iep_summary_supp_service->setLabel('Supplementary Aids and Services')->setRequired(false);
        
        $iep_summary_prog_mod = new Zend_Form_Element_Checkbox('iep_summary_prog_mod');
        $iep_summary_prog_mod->setLabel('Program Modifications and Accommodations')->setRequired(false);
        
        $iep_summary_modifications_accommodations = new Zend_Form_Element_Checkbox('iep_summary_modifications_accommodations');
        $iep_summary_modifications_accommodations->setLabel('Modifications and Accommodations checklist')->setRequired(false);
        
        $iep_summary_ass_tech = new Zend_Form_Element_Checkbox('iep_summary_ass_tech');
        $iep_summary_ass_tech->setLabel('Assistive Technology')->setRequired(false);
        
        $iep_summary_supports = new Zend_Form_Element_Checkbox('iep_summary_supports');
        $iep_summary_supports->setLabel('Supports for School Personnel')->setRequired(false);
        
        $iep_summary_transportation = new Zend_Form_Element_Checkbox('iep_summary_transportation');
        $iep_summary_transportation->setLabel('Transportation')->setRequired(false);
        
        $iep_summary_assessment = new Zend_Form_Element_Checkbox('iep_summary_assessment');
        $iep_summary_assessment->setLabel('Assessment')->setRequired(false);
        
        $iep_summary_extended_school_services = new Zend_Form_Element_Checkbox('iep_summary_extended_school_services');
        $iep_summary_extended_school_services->setLabel('Extended School Year Services')->setRequired(false);
        
        $iep_summary_supplemental_pages = new Zend_Form_Element_Checkbox('iep_summary_supplemental_pages');
        $iep_summary_supplemental_pages->setLabel('Supplemental Pages')->setRequired(false);
        
        $use_mips_consent_form = new Zend_Form_Element_Checkbox('use_mips_consent_form');
        $use_mips_consent_form->setLabel('Use Mips Consent Form')->setRequired(false);
        
        $use_fte_report = new Zend_Form_Element_Checkbox('use_fte_report');
        $use_fte_report->setLabel('Allow SRS to calculate FTE')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setRequired(false)
            
        
        $require_mips_validation = new Zend_Form_Element_Checkbox('require_mips_validation');
        $require_mips_validation->setLabel('Require MIPS when Appropriate')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            array(
                'HtmlTag',
                array(
                    'tag' => 'br'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        // end auto by mike
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('name_district', 'submitbutton');
        
        $this->addElements(array(
            $name_district,
            $id_district,
            $id_county,
            $phone_main,
            $address_street1,
            $add_resource1,
            $id_author,
            $id_author_last_mod,
            $timestamp_created,
            $timestamp_last_mod,
            $status,
            $id_district_mgr,
            $id_account_sprv,
            $checkout_id_user,
            $checkout_time,
            $address_street2,
            $address_city,
            $address_state,
            $address_zip,
            $logo_flag,
            $add_resource2,
            $sch_yr_start_mth,
            $sch_yr_start_day,
            $sch_yr_end_month,
            $sch_yr_end_day,
            $dev_delay_cutoff_age,
            $use_goal_helper,
            $use_form_011,
            $use_form_012,
            $optional_features,
            $approving_mgr_id,
            $id_district_mgr_old,
            $assurance_stmt,
            $pref_district_imports,
            $district_import_code,
            $use_accomodations_checklist,
            $use_iep_benchmarks,
            $fedrep_email,
            $fedrep_send_tonight,
            $use_form_019,
            $use_form_020,
            $use_form_021,
            $use_nssrs,
            $email_nssrs,
            $nssrs_send_tonight,
            $use_nssrs_overview,
            $email_student_transfers_to,
            $use_zf_forms,
            $iep_summary_special_considerations,
            $iep_summary_student_strengths,
            $iep_summary_parental_concerns,
            $iep_summary_results_evaluation,
            $iep_summary_results_perf,
            $iep_summary_behavioral_strategies,
            $iep_summary_language_needs,
            $iep_summary_braille_instruction,
            $iep_summary_comm_needs,
            $iep_summary_deaf_comm_needs,
            $iep_summary_deaf_comm_opp,
            $iep_summary_deaf_academic_lev,
            $iep_summary_assistive_tech,
            $iep_summary_present_lev_perf,
            $iep_summary_goals,
            $iep_summary_measurable_ann_goal,
            $iep_summary_short_term_obj,
            $iep_summary_schedule,
            $iep_summary_eval_procedure,
            $iep_summary_person_responsible,
            $iep_summary_progress,
            $iep_summary_transition,
            $iep_summary_transition_secgoals,
            $iep_summary_transition_16_course_study,
            $iep_summary_transition_16_instruction,
            $iep_summary_transition_16_rel_services,
            $iep_summary_transition_16_comm_exp,
            $iep_summary_transition_16_emp_options,
            $iep_summary_transition_16_dly_liv_skills,
            $iep_summary_transition_16_func_voc_eval,
            $iep_summary_transition_16_inter_agency_link,
            $iep_summary_transition_activity,
            $iep_summary_services,
            $iep_summary_primary_disability,
            $iep_summary_primary_service,
            $iep_summary_related_service,
            $iep_summary_supp_service,
            $iep_summary_prog_mod,
            $iep_summary_modifications_accommodations,
            $iep_summary_ass_tech,
            $iep_summary_supports,
            $iep_summary_transportation,
            $iep_summary_assessment,
            $iep_summary_extended_school_services,
            $iep_summary_supplemental_pages,
            $use_mips_consent_form,
            $use_fte_report,
            $require_mips_validation,
            $submit
        ));
        
        $districtDemo = "DIST";
        $this->addDisplayGroup(array(
            'name_district',
            'id_district',
            'status',
            'id_district_mgr',
            'id_account_sprv',
            'email_student_transfers_to',
            'address_street1',
            'address_street2',
            'address_city',
            'address_state',
            'address_zip',
            'phone_main',
            'id_county',
            'submit'
        ), $districtDemo);
        $this->getDisplayGroup($districtDemo)
            ->setLegend('District Demographics To Change');
        
        
        $districtSettings = "DIST_SETTINGS";
        $this->addDisplayGroup(array(
            'sch_yr_start_mth',
            'sch_yr_start_day',
            'sch_yr_end_month',
            'sch_yr_end_day',
            'dev_delay_cutoff_age',
            'add_resource1',
            'add_resource2'
        ), $districtSettings);
        $this->getDisplayGroup($districtSettings)
            ->setLegend('District Settings');
        
        $assuranceStmt = "OPTIONAL_FEATURES";
        $this->addDisplayGroup(array(
            'assurance_stmt',
            'optional_features',
            'use_goal_helper',
            'use_form_011',
            'use_form_012',
            'use_form_019',
            'use_form_020',
            'use_form_021',
            'use_fte_report',
            'fedrep_send_tonight',
            'fedrep_email',
            'use_accomodations_checklist',
            'require_mips_validation',
            'use_iep_benchmarks',
            'use_nssrs',
            'use_nssrs_overview',
            'nssrs_send_tonight',
            'email_nssrs',
            'use_zf_forms'
        ), $assuranceStmt);
        $this->getDisplayGroup($assuranceStmt)
            ->setLegend('Optional Features2');
        
        $summaryForm = 'IEPSummaryForm';
        $this->addDisplayGroup(array(
            'iep_summary_special_considerations',
            'iep_summary_student_strengths',
            'iep_summary_parental_concerns',
            'iep_summary_results_evaluation',
            'iep_summary_results_perf',
            'iep_summary_behavioral_strategies',
            'iep_summary_language_needs',
            'iep_summary_braille_instruction',
            'iep_summary_comm_needs',
            'iep_summary_deaf_comm_needs',
            'iep_summary_deaf_comm_opp',
            'iep_summary_deaf_academic_lev',
            'iep_summary_assistive_tech',
            'iep_summary_present_lev_perf',
            'iep_summary_goals',
            'iep_summary_measurable_ann_goal',
            'iep_summary_short_term_obj',
            'iep_summary_schedule',
            'iep_summary_person_responsible',
            'iep_summary_eval_procedure',
            'iep_summary_progress'
        ), $summaryForm);
        $this->getDisplayGroup($summaryForm)->setLegend('IEP Summary Form A');
        
        $transitionForm = 'Transition';
        $this->addDisplayGroup(array(
            'iep_summary_transition',
            'iep_summary_transition_secgoals',
            'iep_summary_transition_16_course_study',
            'iep_summary_transition_16_instruction',
            'iep_summary_transition_16_rel_services',
            'iep_summary_transition_16_comm_exp',
            'iep_summary_transition_16_emp_options',
            'iep_summary_transition_16_dly_liv_skills',
            'iep_summary_transition_16_func_voc_eval',
            'iep_summary_transition_16_inter_agency_link',
            'iep_summary_transition_activity'
        ), $transitionForm);
        $this->getDisplayGroup($transitionForm)
            ->setLegend('Transition');
            
        
        $servicesForm = 'Services';
        $this->addDisplayGroup(array(
            'iep_summary_services',
            'iep_summary_primary_disability',
            'iep_summary_primary_service',
            'iep_summary_related_service',
            'iep_summary_supp_service',
            'iep_summary_prog_mod',
            'iep_summary_modifications_accommodations',
            'iep_summary_ass_tech',
            'iep_summary_supports'
        ), $servicesForm);
        $this->getDisplayGroup($servicesForm)
            ->setLegend('Services');
            
          
     
        
        $servicesForm2 = 'Services2';
        $this->addDisplayGroup(array(
            'iep_summary_transportation',
            'iep_summary_assessment',
            'iep_summary_extended_school_services',
            'iep_summary_supplemental_pages',
            'use_mips_consent_form',
            'pref_district_imports',
            'district_import_code'
        ), $servicesForm2);
        $this->getDisplayGroup($servicesForm)
        ->setLegend('Supplemental');
        
    }
}
