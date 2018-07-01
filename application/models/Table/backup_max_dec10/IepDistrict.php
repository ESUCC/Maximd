<?php

class Model_Table_IepDistrict extends Zend_Db_Table_Abstract

{

    protected $_name = 'iep_district';

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
            'district_import_code'=>$t['district_import_code']
           
        );
        
        $county= $_SESSION["user"]["user"]->user["id_county"];
        $district = $_SESSION["user"]["user"]->user["id_district"];
        
        $where = "id_district ='$district' and id_county ='$county'";
        $this->update($data,$where);
        die();
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
  