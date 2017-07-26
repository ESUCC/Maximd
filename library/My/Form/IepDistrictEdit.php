<?php

class My_Form_IepDistrictEdit extends Zend_Form
{
  
  
    public $formDecorators = array(
        array(
            'FormElement' 
        ),
        array(
            'Form'
        )
    );
   
    public $elementDecoratorsChk = array(
     
        'ViewHelper',
        'Errors', 
        
        array(
            'Label',
            array( 'tag'=>'b','placement'=>'append')
            )
        );
    
    
    
  
    public $elementDecorators = array(
        array(
            'ViewHelper'
        ),
        array(
            'Label'),
        array(
            'Errors'
        )
    );
   
    public $carriagReturn = array(
        array('HtmlTag',array('tag'=>'br'))
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
    
    public $endDiv = array(
    array('closeDiv' =>'HtmlTag'),
    array('tag' => 'div', 'closeOnly' => true)
);
    
    public function init()
    {
      //  include("Writeit.php");
      
        $county_sv = $_SESSION["user"]["user"]->user["id_county"];
        $district_sv = $_SESSION["user"]["user"]->user["id_district"];
        $user_id  = $_SESSION["user"]["user"]->user["id_personnel"];
        $privm = $_SESSION['user']['user']->privs[0]['class'];
      
        $this->clearDecorators();
        $this->setName('name_district');
        
        $name_district = new Zend_Form_Element_Text('name_district');
        $name_district->setLabel('District Name')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')
            ->setDecorators($this->elementDecorators)
            ->addDecorators($this->carriagReturn);
        
      
        
        $id_district = new Zend_Form_Element_Text('id_district');
        $id_district->setLabel('District Id')
            ->setAttrib('readonly', 'readonly');
        // ->$id_district->addFilter('Int')
        
         //   ->setDecorators($this->elementDecorators);
            
        $id_county = new Zend_Form_Element_Hidden('id_county');
        
        $phone_main = new Zend_Form_Element_Text('phone_main');
        $phone_main->setLabel('Phone')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')
            ->setDecorators($this->elementDecorators);
        
        $address_street1 = new Zend_Form_Element_Text('address_street1');
        $address_street1->setLabel('Address')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')
            ->setDecorators($this->elementDecorators)
            ->addDecorators($this->carriagReturn);
            //->addDecorators($this->carriagReturn);
            
        
        $add_resource1 = new Zend_Form_Element_Text('add_resource1');
        $add_resource1->setLabel('Resource 1')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')
            ->setDecorators($this->elementDecorators)
             ->addDecorators($this->carriagReturn);
        
        // added auto by mike start
        $id_author = new Zend_Form_Element_Hidden('id_author');
        
        $id_author_last_mod = new Zend_Form_Element_Hidden('id_author_last_mod');
        $timestamp_created = new Zend_Form_Element_Hidden('timestamp_created');
        $timestamp_last_mod = new Zend_Form_Element_Hidden('timestamp_last_mod');
        
        $status = new Zend_Form_Element_Text('status');
        $status->setLabel('Status')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators($this->carriagReturn);
        
    
        
            $pullDownAdmins= new Model_Table_IepPrivileges();
            $adminDistArray=$pullDownAdmins->getListOfDistAdmins($county_sv,$district_sv);
            $adminArray=$pullDownAdmins->getListOfAdmins($county_sv,$district_sv);
            
            
            foreach ($adminDistArray as $admin){
                 $selectArray1[$admin['id_personnel']]=$admin['name_first']." ".$admin['name_last'];
            }
            
            
            $id_district_mgr = new Zend_Form_Element_Select('id_district_mgr');
            $id_district_mgr->setLabel('District Manager')
            ->setMultiOptions($selectArray1);
               
        
            foreach ($adminArray as $admin){
                $selectArray2[$admin['id_personnel']]=$admin['name_first']." ".$admin['name_last'];
            }
        
           $id_account_sprv = new Zend_Form_Element_Select('id_account_sprv');
           $id_account_sprv->setLabel('Account Supervisor')
           ->setMultiOptions($selectArray2);
    
            
            
        $checkout_id_user = new Zend_Form_Element_Hidden('checkout_id_user');
        
        $checkout_time = new Zend_Form_Element_Hidden('checkout_time');
        
        $address_street2 = new Zend_Form_Element_Text('address_street2');
        $address_street2->setLabel('Address 2')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators);
           //>addDecorators($this->carriagReturn);
        
        $address_city = new Zend_Form_Element_Text('address_city');
        $address_city->setLabel('City')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators($this->carriagReturn);
        
        $address_state = new Zend_Form_Element_Text('address_state');
        $address_state->setLabel('State')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators);
         // ->addDecorators($this->carriagReturn);
   
        $address_zip = new Zend_Form_Element_Text('address_zip');
        $address_zip->setLabel('Zip Code')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators($this->carriagReturn);
        
        $logo_flag = new Zend_Form_Element_Hidden('logo_flag');
        
        $add_resource2 = new Zend_Form_Element_Text('add_resource2');
        $add_resource2->setLabel('Resource 2')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators);
        
        $sch_yr_start_mth = new Zend_Form_Element_Text('sch_yr_start_mth');
        $sch_yr_start_mth->setLabel('Start Month')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators($this->carriagReturn);
            
        
        $sch_yr_start_day = new Zend_Form_Element_Text('sch_yr_start_day');
        $sch_yr_start_day->setLabel('Start Day')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators($this->carriagReturn);
            
        
        $sch_yr_end_month = new Zend_Form_Element_Text('sch_yr_end_month');
        $sch_yr_end_month->setLabel('End Month')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators);
          //  ->addDecorators($this->carriagReturn);
            
        
        $sch_yr_end_day = new Zend_Form_Element_Text('sch_yr_end_day');
        $sch_yr_end_day->setLabel('End Day')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators($this->carriagReturn);
            
        
        // Developmental Delay Cutoff
        $dev_delay_cutoff_age = new Zend_Form_Element_Text('dev_delay_cutoff_age');
        $dev_delay_cutoff_age->setLabel('Development Delay Cutoff Age')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators($this->carriagReturn);
        
        // This is a yes or no
        $use_goal_helper = new Zend_Form_Element_Checkbox('use_goal_helper');
        $use_goal_helper->setLabel('Use Goal Helper')
        ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk)
            ->addDecorators($this->carriagReturn);
           
           
            
        
        $use_form_011 = new Zend_Form_Element_Checkbox('use_form_011');
        $use_form_011->setLabel('MDT Conference')
            ->setDecorators($this->elementDecoratorsChk)
         //   ->addDecorators($this->carriagReturn)
            ->setRequired(false);
         
            
            
            
            
        $use_form_012 = new Zend_Form_Element_Checkbox('use_form_012');
        $use_form_012->setLabel('Determination Notice')
            ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk)
            ->addDecorators($this->carriagReturn);
            
        
        $optional_features = new Zend_Form_Element_CheckBox('optional_features');
        $optional_features->setLabel('Accept Optional Features')
            ->setRequired(false)
            ->setDecorators($this->carriagReturn)
            ->addDecorators($this->elementDecoratorsChk);
            
            
         //   ->addDecorator(array('label'=>array('tag'=>'br')));
         //   ->setDecorators($this->elementDecorators)
         //   ->getDecorator($this->elementDecoratorsChk);
        
        $approving_mgr_id = new Zend_Form_Element_Hidden('approving_mgr_id');
        
        $id_district_mgr_old = new Zend_Form_Element_Hidden('id_district_mgr_old');
        // can take this one out
        
        $assurance_stmt = new Zend_Form_Element_checkbox('assurance_stmt');
        $assurance_stmt->setLabel('Please Check Box if you Agree')
          ->setDecorators($this->elementDecoratorsChk);
         //->addDecorators($this->carriagReturn);
           
            
            
            
        
        
        $pref_district_imports = new Zend_Form_Element_Checkbox('pref_district_imports');
        $pref_district_imports->setLabel('District to Import')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $district_import_code = new Zend_Form_Element_Text('district_import_code');
        $district_import_code->setLabel('District Import Code')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators);
            //->addDecorators($this->carriagReturn);
        
        $use_accomodations_checklist = new Zend_Form_Element_Checkbox('use_accomodations_checklist');
        $use_accomodations_checklist->setLabel('Accomondations Checklist')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk)
            ->addDecorators($this->carriagReturn);
            
        
        $use_iep_benchmarks = new Zend_Form_Element_Checkbox('use_iep_benchmarks');
        $use_iep_benchmarks->setLabel('Include benchmarks on progress reports')
            ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk)
            ->addDecorators($this->carriagReturn);
        
            
        
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
            ->setDecorators($this->elementDecoratorsChk)
            ->addDecorators($this->carriagReturn);
            
        
        $use_form_019 = new Zend_Form_Element_Checkbox('use_form_019');
        $use_form_019->setLabel('Functional Assessment')
            ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk)
            ->addDecorators($this->carriagReturn);
            
       
        
        $use_form_020 = new Zend_Form_Element_Checkbox('use_form_020');
        $use_form_020->setLabel('Specialized Transportation')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk);
           
        
        $use_form_021 = new Zend_Form_Element_Checkbox('use_form_021');
        $use_form_021->setLabel('Asstive Tech Considerations')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk)
            ->addDecorators($this->carriagReturn);
            
        
        $use_nssrs = new Zend_Form_Element_Checkbox('use_nssrs');
        $use_nssrs->setLabel('Create NSSRS File')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk);
            
        
        $email_nssrs = new Zend_Form_Element_Text('email_nssrs');
        $email_nssrs->setLabel('NSSRS Email')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators($this->carriagReturn);
            
        
        $nssrs_send_tonight = new Zend_Form_Element_Checkbox('nssrs_send_tonight');
        $nssrs_send_tonight->setLabel('Send nssrs file tonight')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk);
           
           
        
        $use_nssrs_overview = new Zend_Form_Element_Checkbox('use_nssrs_overview');
        $use_nssrs_overview->setLabel('Create NSSRS Overview file')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk)
            ->addDecorators($this->carriagReturn);
            
        
            
            
       
            
            
            $email_student_transfers_to= new Zend_Form_Element_Select('email_student_transfers_to_name');
            $email_student_transfers_to->setLabel('Email Transfers to:')
            ->setMultiOptions($selectArray2);
            
            
        $use_zf_forms = new Zend_Form_Element_Checkbox('use_zf_forms');
        $use_zf_forms->setLabel('Use New Style Forms')
            ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk);
            
           
        
        $iep_summary_special_considerations = new Zend_Form_Element_Checkbox('iep_summary_special_considerations');
        $iep_summary_special_considerations->setLabel('Special Considerations')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
        //->addDecorators($this->carriagReturn);
        
        // Student Strengths iep sum form
        $iep_summary_student_strengths = new Zend_Form_Element_Checkbox('iep_summary_student_strengths');
        $iep_summary_student_strengths->setLabel('Student Strengths')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        // Parental Information checkbox iep sum form
        $iep_summary_parental_concerns = new Zend_Form_Element_Checkbox('iep_summary_parental_concerns');
        $iep_summary_parental_concerns->setLabel('Parental Information')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
       //->addDecorators($this->carriagReturn);
        
        // Results of Initial Recent Evaluation checkbox
        $iep_summary_results_evaluation = new Zend_Form_Element_Checkbox('iep_summary_results_evaluation');
        $iep_summary_results_evaluation->setLabel('Results of Initial Evaluation')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        
        $iep_summary_results_perf = new Zend_Form_Element_Checkbox('iep_summary_results_perf');
        $iep_summary_results_perf->setLabel('Performance of any general state-district wide assessments')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
        //->addDecorators($this->carriagReturn);
        
        // Performance of any general stat and district wide assessments iep sum checkbox
        $iep_summary_behavioral_strategies = new Zend_Form_Element_Checkbox('iep_summary_behavioral_strategies');
        $iep_summary_behavioral_strategies->setLabel('Consideration of appropriate behavioral strategies')
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        // Consideration of language needs iep sum form checkbox
        $iep_summary_language_needs = new Zend_Form_Element_Checkbox('iep_summary_language_needs');
        $iep_summary_language_needs->setLabel('Consideration of language needs:')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        // Braille instruction iep sum form checkbox
        $iep_summary_braille_instruction = new Zend_Form_Element_Checkbox('iep_summary_braille_instruction');
        $iep_summary_braille_instruction->setLabel('Braille Instruction')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
       // ->addDecorators($this->carriagReturn);
        
        // Communication Needs iep form checkbox
        $iep_summary_comm_needs = new Zend_Form_Element_Checkbox('iep_summary_comm_needs');
        $iep_summary_comm_needs->setLabel('Communication Needs')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        // Deaf/Hard of Hearing : Communication Needs iep sum form checkbox
        $iep_summary_deaf_comm_needs = new Zend_Form_Element_Checkbox('iep_summary_deaf_comm_needs');
        $iep_summary_deaf_comm_needs->setLabel('Deaf Hard of Hearing : Communication Needs')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
        //->addDecorators($this->carriagReturn);
        
        // Deaf/Hard of Hearing Direct Communication iep sum form checkbox
        $iep_summary_deaf_comm_opp = new Zend_Form_Element_Checkbox('iep_summary_deaf_comm_opp');
        $iep_summary_deaf_comm_opp->setLabel('Deaf/Hard of Hearing Direct Communication')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_deaf_academic_lev = new Zend_Form_Element_Checkbox('iep_summary_deaf_academic_lev');
        $iep_summary_deaf_academic_lev->setLabel('Deaf Hard of Hearing: Opportunities for Direct Instruction')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_assistive_tech = new Zend_Form_Element_Checkbox('iep_summary_assistive_tech');
        $iep_summary_assistive_tech->setLabel('Considerations of Child\'s need for assistive Technology')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_present_lev_perf = new Zend_Form_Element_Checkbox('iep_summary_present_lev_perf');
        $iep_summary_present_lev_perf->setLabel('Present level of Performance')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
       ->addDecorators($this->carriagReturn);
        
        $iep_summary_goals = new Zend_Form_Element_Checkbox('iep_summary_goals');
        $iep_summary_goals->setLabel('Goals')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecoratorsChk);
           // ->addDecorators($this->carriagReturn);
        
        $iep_summary_measurable_ann_goal = new Zend_Form_Element_Checkbox('iep_summary_measurable_ann_goal');
        $iep_summary_measurable_ann_goal->setLabel('Measuarable Annual Goals')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecoratorsChk)
           ->addDecorators($this->carriagReturn);
        
        $iep_summary_short_term_obj = new Zend_Form_Element_Checkbox('iep_summary_short_term_obj');
        $iep_summary_short_term_obj->setLabel('Short Term Objective')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecoratorsChk);
          //  ->addDecorators($this->carriagReturn);
        
        $iep_summary_schedule = new Zend_Form_Element_checkbox('iep_summary_schedule');
        $iep_summary_schedule->setLabel('Schedule')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecoratorsChk)
            ->addDecorators($this->carriagReturn);
        
        $iep_summary_eval_procedure = new Zend_Form_Element_checkbox('iep_summary_eval_procedure');
        $iep_summary_eval_procedure->setLabel('Evaluation Procedure')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecoratorsChk)
            ->addDecorators($this->carriagReturn);
        
        $iep_summary_person_responsible = new Zend_Form_Element_checkbox('iep_summary_person_responsible');
        $iep_summary_person_responsible->setLabel('Person Responsible')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecoratorsChk);
            //->addDecorators($this->carriagReturn);
        
        $iep_summary_progress = new Zend_Form_Element_checkbox('iep_summary_progress');
        $iep_summary_progress->setLabel('Progress')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecoratorsChk);
            //->addDecorators($this->carriagReturn);
        
        $iep_summary_transition = new Zend_Form_Element_Checkbox('iep_summary_transition');
        $iep_summary_transition->setLabel('Transition')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
     //  ->addDecorators($this->carriagReturn);
        
        $iep_summary_transition_secgoals = new Zend_Form_Element_Checkbox('iep_summary_transition_secgoals');
        $iep_summary_transition_secgoals->setLabel('Post Secondary Goals')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_transition_16_course_study = new Zend_Form_Element_Checkbox('iep_summary_transition_16_course_study');
        $iep_summary_transition_16_course_study->setLabel('Course of Study')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
       // ->addDecorators($this->carriagReturn);
        
        $iep_summary_transition_16_instruction = new Zend_Form_Element_Checkbox('iep_summary_transition_16_instruction');
        $iep_summary_transition_16_instruction->setLabel('Statement of Needed Transition Service')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_transition_16_rel_services = new Zend_Form_Element_Checkbox('iep_summary_transition_16_rel_services');
        $iep_summary_transition_16_rel_services->setLabel('Related Transition Services')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
       // ->addDecorators($this->carriagReturn);
        
        $iep_summary_transition_16_comm_exp = new Zend_Form_Element_Checkbox('iep_summary_transition_16_comm_exp');
        $iep_summary_transition_16_comm_exp->setLabel('Community Experiences')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_transition_16_emp_options = new Zend_Form_Element_Checkbox('iep_summary_transition_16_emp_options');
        $iep_summary_transition_16_emp_options->setLabel('Employment and Adult Living Objectives')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
      //  ->addDecorators($this->carriagReturn);
        
        $iep_summary_transition_16_dly_liv_skills = new Zend_Form_Element_Checkbox('iep_summary_transition_16_dly_liv_skills');
        $iep_summary_transition_16_dly_liv_skills->setLabel('Daily Living Skills')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_transition_16_func_voc_eval = new Zend_Form_Element_Checkbox('iep_summary_transition_16_func_voc_eval');
        $iep_summary_transition_16_func_voc_eval->setLabel('Functional Vocational Evaluation')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
       // ->addDecorators($this->carriagReturn);
        
        $iep_summary_transition_16_inter_agency_link = new Zend_Form_Element_Checkbox('iep_summary_transition_16_inter_agency_link');
        $iep_summary_transition_16_inter_agency_link->setLabel('Interagency Linkages')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_transition_activity = new Zend_Form_Element_Checkbox('iep_summary_transition_activity');
        $iep_summary_transition_activity->setLabel('Transition Activities')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
        //->addDecorators($this->carriagReturn)
        
        $iep_summary_services = new Zend_Form_Element_Checkbox('iep_summary_services');
        $iep_summary_services->setLabel('Services')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_primary_disability = new Zend_Form_Element_Checkbox('iep_summary_primary_disability');
        $iep_summary_primary_disability->setLabel('Statement of Special Education and Related Services')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
        //->addDecorators($this->carriagReturn);
        
        $iep_summary_primary_service = new Zend_Form_Element_Checkbox('iep_summary_primary_service');
        $iep_summary_primary_service->setLabel('Primary Services')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_related_service = new Zend_Form_Element_Checkbox('iep_summary_related_service');
        $iep_summary_related_service->setLabel('Related Services')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
        //->addDecorators($this->carriagReturn);
        
        $iep_summary_supp_service = new Zend_Form_Element_Checkbox('iep_summary_supp_service');
        $iep_summary_supp_service->setLabel('Supplementary Aids and Services')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_prog_mod = new Zend_Form_Element_Checkbox('iep_summary_prog_mod');
        $iep_summary_prog_mod->setLabel('Program Modifications and Accommodations')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
        //->addDecorators($this->carriagReturn);
        
        $iep_summary_modifications_accommodations = new Zend_Form_Element_Checkbox('iep_summary_modifications_accommodations');
        $iep_summary_modifications_accommodations->setLabel('Modifications and Accommodations checklist')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_ass_tech = new Zend_Form_Element_Checkbox('iep_summary_ass_tech');
        $iep_summary_ass_tech->setLabel('Assistive Technology')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
       //->addDecorators($this->carriagReturn);
        
        $iep_summary_supports = new Zend_Form_Element_Checkbox('iep_summary_supports');
        $iep_summary_supports->setLabel('Supports for School Personnel')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        
        $iep_summary_transportation = new Zend_Form_Element_Checkbox('iep_summary_transportation');
        $iep_summary_transportation->setLabel('Transportation')
        ->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
      // ->addDecorators($this->carriagReturn);
        
        $iep_summary_assessment = new Zend_Form_Element_Checkbox('iep_summary_assessment');
        $iep_summary_assessment->setLabel('Assessment')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $iep_summary_extended_school_services = new Zend_Form_Element_Checkbox('iep_summary_extended_school_services');
        $iep_summary_extended_school_services->setLabel('Extended School Year Services')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
       // ->addDecorators($this->carriagReturn);
        
        $iep_summary_supplemental_pages = new Zend_Form_Element_Checkbox('iep_summary_supplemental_pages');
        $iep_summary_supplemental_pages->setLabel('Supplemental Pages')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk)
        ->addDecorators($this->carriagReturn);
        
        $use_mips_consent_form = new Zend_Form_Element_Checkbox('use_mips_consent_form');
        $use_mips_consent_form->setLabel('Use Mips Consent Form')->setRequired(false)
        ->setDecorators($this->elementDecoratorsChk);
        //->addDecorators($this->carriagReturn);
        
        
        $use_fte_report = new Zend_Form_Element_Checkbox('use_fte_report');
       
        $use_fte_report->setLabel('Allow SRS to calculate FTE')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk);
        
            
     
        
        $require_mips_validation = new Zend_Form_Element_Checkbox('require_mips_validation');
        $require_mips_validation->setLabel('Require MIPS when Appropriate')
            ->setRequired(false)
            ->setRequired(false)
            ->setDecorators($this->elementDecoratorsChk);
            
        
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
            'id_district_mgr_name',
            
            'id_account_sprv',
            'id_account_sprv_name',
            'email_student_transfers_to',
            'email_student_transfers_to_name',
            
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
            ->setLegend('District Demographics to Change')
            ->setDecorators(array(
            'FormElements',
            'Fieldset',
            array(
                'HtmlTag',array( 'tag' => 'div','id'=>'DistrictDemographics','openOnly' => false
                 //   'style' => 'width:24%;;float:left 
                 )          
            )
               
                
        ));
        
        $districtSettings = "DistrictSettings";
        $this->addDisplayGroup(array(
            'sch_yr_start_mth',
            'sch_yr_start_day',
            'sch_yr_end_month',
            'sch_yr_end_day',
            'dev_delay_cutoff_age',
            'add_resource1',
            'add_resource2',
            'submit'
        ), $districtSettings);
        $this->getDisplayGroup($districtSettings)
            ->setLegend('District Settings')
            ->setDecorators(array('FormElements','Fieldset', array(
                'HtmlTag',array('tag' => 'div','id'=>'DistrictSettings','openOnly' => false)
                    
                 //   'style' => 'width:24%;;float:left'
                
            )
        ));
        
            
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
            $this->getDisplayGroup($summaryForm)->setLegend('IEP Summary Form A')
            ->setDecorators(array(
                'FormElements',
                'Fieldset',
                array(
                    'HtmlTag',
                    array(
                        'tag' => 'div','id'=>'IepSummaryFormA',
                        'openOnly' => false
                    )
                )
            ));
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
            'use_zf_forms',
            'submit'
        ), $assuranceStmt);
        $this->getDisplayGroup($assuranceStmt)
            ->setLegend('Optional Features')
            ->setDecorators(array(
            'FormElements',
            'Fieldset',
            array(
                'HtmlTag',
                array(
                    'tag' => 'div','id'=>'OptionalFeatures',
                   'openOnly' => false
                )
            )
        ));
           
        
       
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
            ->setLegend('Transition')
            ->setDecorators(array(
            'FormElements',
            'Fieldset',
            array(
                'HtmlTag',
                array(
                    'tag' => 'div','id'=>'Transition',
                    'openOnly' => false,
                   
                )
            )
        ));
        
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
            ->setLegend('Services')
            ->setDecorators(array(
            'FormElements',
            'Fieldset',
            array(
                'HtmlTag',
                array(
                    'tag' => 'div','id'=>'Services',
                    'openOnly' => false,
                   
                )
            )
        ), $servicesForm);
        
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
        
        $this->getDisplayGroup($servicesForm2)
            ->setLegend('Services2')
            ->setDecorators(array(
            'FormElements',
            'Fieldset',
            array(
                'HtmlTag',
                array(
                    'tag' => 'div','id'=>'Services2',
                    'openOnly' => false,
                    //'style' => 'width:24%;;float:left'
                )
            )
        ), $servicesForm);
    }
}

