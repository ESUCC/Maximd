<?php

class Model_Table_IepDistrict extends Zend_Db_Table_Abstract

{

    protected $_name = 'iep_district';

    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    // Mike added this 7-24-2017 from Maxim so that the popup in the choose District School list would work
    function getIepSchoolList($id_county, $id_district)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
        ->from( 'iep_school' )
        ->where('id_district = ?', $id_district)
        ->where('id_county = ?', $id_county)
        ->order(array('name_school asc'));
    
        $results = $db->fetchAll($select);
    
        return $results;
    }
    // Mike added this 7-24-2017 from Maxim so that the popup in the choose District School list would work
    
    function getIepManagersList()
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
        ->from( 'iep_personnel' )
        ->where('class > 0');
        $results = $db->fetchAll($select);
    
        return $results;
    }
    
    public function getAllDistricts()
    {
        
        $db1= Zend_Registry::get('db');
        
        $sqlst="select d.id_district,d.id_county,d.name_district,c.name_county from iep_district d,iep_county c where c.id_county=d.id_county order by name_district";
        $allDis = $db1->fetchAll($sqlst);
        
        
      //  $this->writevar1($allDis,'this is the list of Districts');
        
        return $allDis;
        // return $row;
    }
    
    public function getIepDistrict($name_district)
    {
        
        $row = $this->fetchRow('name_district = ' . "'" . $name_district . "'");
        if (! $row) {
            throw new Exception("Could not find row $name_district");
        }
        // writeit($row->toArray(),"this is the row to array line 23 model_ieppersonnel.php \n");
       return $row->toArray();
     // return $row;
    }

    public function getIepDistrictManagers($id_county, $id_district, $class)
    {
      // include("writeit.php");
        $db = Zend_Registry::get('db');
     
        
        /* Mike changed this 2-9-2017 because it did not check to see if a user had been rendered inactive on
         * the personnel/edit screen.   
         * 
         * $select = $db->select()
                   ->distinct()
                   ->from( array('p' => 'iep_personnel'), array('p.id_personnel', 'p.name_first', 'p.name_last', 'p.email_address') )
                   ->joinLeft( array('r' => 'iep_privileges'), 'p.id_personnel = r.id_personnel', array() )
                   ->where('p.status = ?', 'Active')
                   ->where('r.class <= ?', $class)
                   ->where('r.id_county = ?', $id_county)
                   ->where('r.id_district = ?', $id_district)
                   ->order('p.name_last asc');
        $result = $db->fetchAll($select);
        
        $district->getAdapter()->fetchAll($sqlStmt, $binds);
     */
        $today=date("Y-m-d");
        $sqlStmt="select p.id_personnel,p.name_first,p.name_last,p.email_address from iep_personnel p, iep_privileges r 
                  where p.id_personnel=r.id_personnel and p.status='Active' and r.status='Active'  
                  and r.id_district='".$id_district."' and r.id_county='".$id_county."' and r.class<='".$class."' 
                  order  by p.name_last asc";
       // writevar($sqlStmt,'this is the sql statement');
        $result= $db->fetchAll($sqlStmt);
        
       // writevar($result,'this is the result for the pull down in district edit.');
        
        return $result;
    }

    public function getIepDistrictByID($id_cty, $id_dst)
    {
       $row = $this->fetchRow($this->select()
            ->where('id_county =?',$id_cty)
            ->where('id_district =?',$id_dst) );
       return $row->toArray();
    }
    
    public function getIepDistrictName($id_cty,$id_dst)
    {
   // include("Writeit.php");
        $row = $this->fetchAll($this->select()
            ->where('id_county =?',$id_cty)
            ->where('id_district =?',$id_dst) );
        if (! $row) {
            throw new Exception("Could not find row");
        }
       
        return ($row);
        // return $row;
    }

    public function updateIepDistrict2($formData)
    {   $t=$formData;
        writevar($t['email_student_transfers_to'],'this is the form data inside the model');
        
        $data=array(
            'checkout_id_user'=>$t['checkout_id_user'],
            'name_district'=>$t['name_district'],
            'id_district'=>$t['id_district'],
            'status'=>$t['status'],
            'id_district_mgr'=>$t['id_district_mgr'],
            'id_account_sprv'=>$t['id_account_sprv'],
            'email_student_transfers_to'=>$t['email_student_transfers_to'],
            'address_street1'=>$t['address_street1'],
            'address_street2'=>$t['address_street2'],
            'address_city'=>$t['address_city'],
            'address_state'=>$t['address_state'],
            'address_zip'=>$t['address_zip'],
            'phone_main'=>$t['phone_main'],
            'id_county'=>$t['id_county'],
            'sch_yr_start_mth'=>$t['sch_yr_start_mth'],
            'sch_yr_start_day'=>$t['sch_yr_start_day'],
            'sch_yr_end_month'=>$t['sch_yr_end_month'],
            'sch_yr_end_day'=>$t['sch_yr_end_day'],
            'dev_delay_cutoff_age'=>$t['dev_delay_cutoff_age'],
            'add_resource1'=>$t['add_resource1'],
            'add_resource2'=>$t['add_resource2'],
            'iep_summary_special_considerations'=>$t['iep_summary_special_considerations'],
            'iep_summary_student_strengths'=>$t['iep_summary_student_strengths'],
            'iep_summary_parental_concerns'=>$t['iep_summary_parental_concerns'],
            'iep_summary_results_evaluation'=>$t['iep_summary_results_evaluation'],
            'iep_summary_results_perf'=>$t['iep_summary_results_perf'],
            'iep_summary_behavioral_strategies'=>$t['iep_summary_behavioral_strategies'],
            'iep_summary_language_needs'=>$t['iep_summary_language_needs'],
            'iep_summary_braille_instruction'=>$t['iep_summary_braille_instruction'],
            'iep_summary_comm_needs'=>$t['iep_summary_comm_needs'],
            'iep_summary_deaf_comm_needs'=>$t['iep_summary_deaf_comm_needs'],
            'iep_summary_deaf_comm_opp'=>$t['iep_summary_deaf_comm_opp'],
            'iep_summary_deaf_academic_lev'=>$t['iep_summary_deaf_academic_lev'],
            'iep_summary_assistive_tech'=>$t['iep_summary_assistive_tech'],
            'iep_summary_present_lev_perf'=>$t['iep_summary_present_lev_perf'],
            'iep_summary_goals'=>$t['iep_summary_goals'],
            'iep_summary_measurable_ann_goal'=>$t['iep_summary_measurable_ann_goal'],
            'iep_summary_short_term_obj'=>$t['iep_summary_short_term_obj'],
            'iep_summary_schedule'=>$t['iep_summary_schedule'],
            'iep_summary_person_responsible'=>$t['iep_summary_person_responsible'],
            'iep_summary_eval_procedure'=>$t['iep_summary_eval_procedure'],
            'iep_summary_progress'=>$t['iep_summary_progress'],
            'assurance_stmt'=>$t['assurance_stmt'],
            'optional_features'=>$t['optional_features'],
            'use_goal_helper'=>$t['use_goal_helper'],
            'use_form_011'=>$t['use_form_011'],
            'use_form_012'=>$t['use_form_012'],
            'use_form_019'=>$t['use_form_019'],
            'use_form_020'=>$t['use_form_020'],
            'use_form_021'=>$t['use_form_021'],
            'use_fte_report'=>$t['use_fte_report'],
            'fedrep_send_tonight'=>$t['fedrep_send_tonight'],
            'fedrep_email'=>$t['fedrep_email'],
            'use_accomodations_checklist'=>$t['use_accomodations_checklist'],
            'require_mips_validation'=>$t['require_mips_validation'],
            'use_iep_benchmarks'=>$t['use_iep_benchmarks'],
            'use_nssrs'=>$t['use_nssrs'],
            'use_nssrs_overview'=>$t['use_nssrs_overview'],
            'nssrs_send_tonight'=>$t['nssrs_send_tonight'],
            'email_nssrs'=>$t['email_nssrs'],
            'use_zf_forms'=>$t['use_zf_forms'],
            'iep_summary_transition'=>$t['iep_summary_transition'],
            'iep_summary_transition_secgoals'=>$t['iep_summary_transition_secgoals'],
            'iep_summary_transition_16_course_study'=>$t['iep_summary_transition_16_course_study'],
            'iep_summary_transition_16_instruction'=>$t['iep_summary_transition_16_instruction'],
            'iep_summary_transition_16_rel_services'=>$t['iep_summary_transition_16_rel_services'],
            'iep_summary_transition_16_comm_exp'=>$t['iep_summary_transition_16_comm_exp'],
            'iep_summary_transition_16_emp_options'=>$t['iep_summary_transition_16_emp_options'],
            'iep_summary_transition_16_dly_liv_skills'=>$t['iep_summary_transition_16_dly_liv_skills'],
            'iep_summary_transition_16_func_voc_eval'=>$t['iep_summary_transition_16_func_voc_eval'],
            'iep_summary_transition_16_inter_agency_link'=>$t['iep_summary_transition_16_inter_agency_link'],
            'iep_summary_transition_activity'=>$t['iep_summary_transition_activity'],
            'iep_summary_services'=>$t['iep_summary_services'],
            'iep_summary_primary_disability'=>$t['iep_summary_primary_disability'],
            'iep_summary_primary_service'=>$t['iep_summary_primary_service'],
            'iep_summary_related_service'=>$t['iep_summary_related_service'],
            'iep_summary_supp_service'=>$t['iep_summary_supp_service'],
            'iep_summary_prog_mod'=>$t['iep_summary_prog_mod'],
            'iep_summary_modifications_accommodations'=>$t['iep_summary_modifications_accommodations'],
            'iep_summary_ass_tech'=>$t['iep_summary_ass_tech'],
            'iep_summary_supports'=>$t['iep_summary_supports'],
            'iep_summary_transportation'=>$t['iep_summary_transportation'],
            'iep_summary_assessment'=>$t['iep_summary_assessment'],
            'iep_summary_extended_school_services'=>$t['iep_summary_extended_school_services'],
            'iep_summary_supplemental_pages'=>$t['iep_summary_supplemental_pages'],
            'use_mips_consent_form'=>$t['use_mips_consent_form'],
            'pref_district_imports'=>$t['pref_district_imports'],
            'district_import_code'=>$t['district_import_code'],
            
           
        );
        $this->writevar1($data,'this is the data');
        
        $county= $_SESSION["user"]["user"]->user["id_county"];
        $district = $_SESSION["user"]["user"]->user["id_district"];
        
        $where = "id_district ='$district' and id_county ='$county'";
        $this->update($data,$where);
        die();
    }


    public function updateIepDistrictForm($options)
    {   
        // Mike added this 5-11-17 so that the edfi_refresh field would update
      //  $this->writevar1($options,'this are the options in the table');
        $data['edfi_refresh']=$options['edfi_refresh'];
        $data['use_edfi']=$options['use_edfi'];
        $data['name_district'] = $options['name_district'];
        $data['status'] = $options['status'];
        $data['id_district_mgr'] = $options['id_district_mgr'];
        $data['id_account_sprv'] = $options['id_account_sprv'];
        $data['email_student_transfers_to'] = $options['email_student_transfers_to'];
        $data['address_street1'] = $options['address_street1'];
        $data['address_street2'] = $options['address_street2'];
        $data['address_city'] = $options['address_city'];
        $data['address_state'] = $options['address_state'];
        $data['address_city'] = $options['address_city'];
        $data['address_zip'] = $options['address_zip'];
        $data['phone_main'] = $options['phone_main'];
        $data['sch_yr_start_mth'] = $options['sch_yr_start_mth'];
        $data['sch_yr_start_day'] = $options['sch_yr_start_day'];
        $data['sch_yr_end_month'] = $options['sch_yr_end_month'];
        $data['sch_yr_end_day'] = $options['sch_yr_end_day'];
        $data['dev_delay_cutoff_age'] = $options['dev_delay_cutoff_age'];
        $data['add_resource1'] = $options['add_resource1'];
        $data['add_resource2'] = $options['add_resource2'];
        $data['iep_summary_special_considerations'] = $options['iep_summary_special_considerations'];
        $data['iep_summary_student_strengths'] = $options['iep_summary_student_strengths'];
        $data['iep_summary_parental_concerns'] = $options['iep_summary_parental_concerns'];
        $data['iep_summary_results_evaluation'] = $options['iep_summary_results_evaluation'];
        $data['iep_summary_results_perf'] = $options['iep_summary_results_perf'];
        $data['iep_summary_behavioral_strategies'] = $options['iep_summary_behavioral_strategies'];
        $data['iep_summary_language_needs'] = $options['iep_summary_language_needs'];
        $data['iep_summary_braille_instruction'] = $options['iep_summary_braille_instruction'];
        $data['iep_summary_comm_needs'] = $options['iep_summary_comm_needs'];
        $data['iep_summary_deaf_comm_needs'] = $options['iep_summary_deaf_comm_needs'];
        $data['iep_summary_deaf_comm_opp'] = $options['iep_summary_deaf_comm_opp'];
        $data['iep_summary_deaf_academic_lev'] = $options['iep_summary_deaf_academic_lev'];
        $data['iep_summary_assistive_tech'] = $options['iep_summary_assistive_tech'];
        $data['iep_summary_present_lev_perf'] = $options['iep_summary_present_lev_perf'];
        $data['iep_summary_goals'] = $options['iep_summary_goals'];
        $data['iep_summary_measurable_ann_goal'] = $options['iep_summary_measurable_ann_goal'];
        $data['iep_summary_short_term_obj'] = $options['iep_summary_short_term_obj'];
        $data['iep_summary_schedule'] = $options['iep_summary_schedule'];
        $data['iep_summary_person_responsible'] = $options['iep_summary_person_responsible'];
        $data['iep_summary_eval_procedure'] = $options['iep_summary_eval_procedure'];
        $data['iep_summary_progress'] = $options['iep_summary_progress'];
        $data['iep_summary_transition'] = $options['iep_summary_transition'];
        $data['iep_summary_transition_secgoals'] = $options['iep_summary_transition_secgoals'];
        $data['iep_summary_transition_16_course_study'] = $options['iep_summary_transition_16_course_study'];
        $data['iep_summary_transition_16_instruction'] = $options['iep_summary_transition_16_instruction'];
        $data['iep_summary_transition_16_rel_services'] = $options['iep_summary_transition_16_rel_services'];
        $data['iep_summary_transition_16_comm_exp'] = $options['iep_summary_transition_16_comm_exp'];
        $data['iep_summary_transition_16_emp_options'] = $options['iep_summary_transition_16_emp_options'];
        $data['iep_summary_transition_16_dly_liv_skills'] = $options['iep_summary_transition_16_dly_liv_skills'];
        $data['iep_summary_transition_16_func_voc_eval'] = $options['iep_summary_transition_16_func_voc_eval'];
        $data['iep_summary_transition_16_inter_agency_link'] = $options['iep_summary_transition_16_inter_agency_link'];
        $data['iep_summary_transition_activity'] = $options['iep_summary_transition_activity'];
        $data['iep_summary_services'] = $options['iep_summary_services'];
        $data['iep_summary_primary_disability'] = $options['iep_summary_primary_disability'];
        $data['iep_summary_primary_service'] = $options['iep_summary_primary_service'];
        $data['iep_summary_related_service'] = $options['iep_summary_related_service'];
        $data['iep_summary_supp_service'] = $options['iep_summary_supp_service'];
        $data['iep_summary_prog_mod'] = $options['iep_summary_prog_mod'];
        $data['iep_summary_modifications_accommodations'] = $options['iep_summary_modifications_accommodations'];
        $data['iep_summary_ass_tech'] = $options['iep_summary_ass_tech'];
        $data['iep_summary_supports'] = $options['iep_summary_supports'];
        $data['iep_summary_transportation'] = $options['iep_summary_transportation'];
        $data['iep_summary_assessment'] = $options['iep_summary_assessment'];
        $data['iep_summary_extended_school_services'] = $options['iep_summary_extended_school_services'];
        $data['iep_summary_supplemental_pages'] = $options['iep_summary_supplemental_pages'];
        $data['district_import_code'] = $options['district_import_code'];
        $data['email_nssrs'] = $options['email_nssrs'];
        $data['fedrep_send_tonight'] = $options['fedrep_send_tonight'];
        $data['fedrep_email'] = $options['fedrep_email'];
        $data['nssrs_send_tonight'] = $options['nssrs_send_tonight'];
	if ($options['assurance_stmt'] == 1) $data['assurance_stmt'] = 'true'; else $data['assurance_stmt'] = 'false';
	if ($options['optional_features'] == 1) $data['optional_features'] = 'true'; else $data['optional_features'] = 'false';
	if ($options['use_goal_helper'] == 1) $data['use_goal_helper'] = 'true'; else $data['use_goal_helper'] = 'false';
	if ($options['use_form_012'] == 1) $data['use_form_012'] = 'true'; else $data['use_form_012'] = 'false';
	if ($options['use_form_019'] == 1) $data['use_form_019'] = 'true'; else $data['use_form_019'] = 'false';
	if ($options['use_form_020'] == 1) $data['use_form_020'] = 'true'; else $data['use_form_020'] = 'false';
	if ($options['use_form_021'] == 1) $data['use_form_021'] = 'true'; else $data['use_form_021'] = 'false';
	if ($options['use_fte_report'] == 1) $data['use_fte_report'] = 'true'; else $data['use_fte_report'] = 'false';

	if ($options['use_accomodations_checklist'] == 1) $data['use_accomodations_checklist'] = 'true'; else $data['use_accomodations_checklist'] = 'false';
	if ($options['require_mips_validation'] == 1) $data['require_mips_validation'] = 'true'; else $data['require_mips_validation'] = 'false';
	if ($options['use_iep_benchmarks'] == 1) $data['use_iep_benchmarks'] = 'true'; else $data['use_iep_benchmarks'] = 'false';
	if ($options['use_nssrs'] == 1) $data['use_nssrs'] = 'true'; else $data['use_nssrs'] = 'false';
	if ($options['use_nssrs_overview'] == 1) $data['use_nssrs_overview'] = 'true'; else $data['use_nssrs_overview'] = 'false';
	if ($options['use_zf_forms'] == 1) $data['use_zf_forms'] = 'true'; else $data['use_zf_forms'] = 'false';
	if ($options['use_mips_consent_form'] == 1) $data['use_mips_consent_form'] = 'true'; else $data['use_mips_consent_form'] = 'false';
	if ($options['pref_district_imports'] == 1) $data['pref_district_imports'] = 'true'; else $data['pref_district_imports'] = 'false';
    
        $where = "id_district ='".$options['id_district']."' and id_county = '".$options['id_county']."'";
        $this->update($data, $where);
	return;
    }

    
    public function updateIepDistrict($name_district, $id_district, $id_county, $phone_main, $address_street1, $add_resouce1)
    {
        /*
         * $data = array(
         * 'name_district' => $name_district,
         * 'id_district' => $id_district,
         * 'id_county' => $id_county,
         * 'phone_main'=> $phone_main
         * );
         */
        $data = array(
            'phone_main' => $phone_main,
            'address_street1' => $address_street1
        )
        ;
        $where = "id_district = '$id_district' and id_county='$id_county' ";
        // $where[] = "id_county = '$id_county'";
        
        // $this->update($data, 'name_district = '."'". $name_district ."'");
        $this->update($data, $where);
    }

    public function sortIepDistrict($conjunct)
    {
        // $d=array();
        
        // $outf= new Zend_Writeit;
        
        /*
         * $id_district=$dis_nun->districtAction($id_district);
         *
         * $d=$this->district($id_district);
         * $id_district=$d[0];
         * $id_county=$d[1];
         * // $id_district=$this->district($id_district);
         * $id_district='\''.$id_district . '\'';
         * $id_county='\''.$id_county . '\'';
         * $name_first ='\''.$name_first . '\'';
         * $name_last = '\''.$name_last . '\'';
         * $searchnames='(name_first = ' . $name_first.")";
         */
        $row = $this->fetchAll($this->select()
            ->order($conjunct));
        // $row=$this->fetchAll($this->select()->where("(name_first= $name_first or name_last= $name_last) $conjunct (id_district= $id_district and id_county=$id_county)")->order('name_last'));
        // $row = $this->fetchAll('name_first = ' . $t);
        // $row=$this->fetchAll($this->select()->where("name_first= $name_first and id_county= $id_county"));
        // fetchAll($iep_personnel->select()->where("id_district='0001' and id_county='77' ")->order("name_first"));
        if (! $row) {
            throw new Exception("Could not find row $name_first");
        }
        // die(); This returns an sql statement that is correct in zendserver.
        return $row;
    }
} 
  