<?php

class Model_Table_Form004AccomodationsChecklist extends Model_Table_AbstractIepForm {
	
    protected $_name = 'iep_accom_checklist';
    protected $_primary = 'id_accom_checklist';
	protected $_sequence = 'iep_accom_checklist_id_accom_checklist_seq';
    
    protected $_referenceMap    = array(
        'Model_Table_Form004' => array(
            'columns'           => array('id_form_004'),
            'refTableClass'     => 'Model_Table_Form004',
            'refColumns'        => array('id_form_004')
        )
    );

    protected $checkFields = array(
        'ass_adapt_worksheet',
        'ass_allow_copying',
        'ass_allo_use_resource',
        'ass_avoide_penalizing',
        'ass_give_directions',
        'ass_give_oral_cues',
        'ass_lower_diff_level',
        'ass_other',
        'ass_provide_alternate',
        'ass_provide_oral_directions',
        'ass_read_directions',
        'ass_record_assignment',
        'ass_redo_for_grade',
        'ass_reduce_paper_tasks',
        'ass_shorten_assign',
        'env_alter_physical_room',
        'env_avoid_distr',
        'env_define_areas',
        'env_increase_distance',
        'env_other',
        'env_planned_seating',
        'env_pref_seating',
        'env_reduce_distractions',
        'env_seat_near_role',
        'env_seat_near_teacher',
        'env_teach_pos_rules',
        'grade_attendance',
        'grade_commensurate_effort',
        'grade_graded_on_skills',
        'grade_modified_grading',
        'grade_oral_presentation',
        'grade_other',
        'grade_pass_fail',
        'grade_regular_grading',
        'lessson_emph_info',
        'lessson_functional_app',
        'lessson_make_use_voc',
        'lessson_oral_intrepreter',
        'lessson_other',
        'lessson_present_demo',
        'lessson_preteach_voc',
        'lessson_reduce_lang',
        'lessson_sign_lang',
        'lessson_sm_grp_inst',
        'lessson_spec_curr',
        'lessson_tape_lectures',
        'lessson_teacher_emph',
        'lessson_teacher_provides',
        'lessson_total_comm',
        'lessson_utilize_manip',
        'lessson_visual_sequences',
        'mat_arrangement',
        'mat_enlarge_notes',
        'mat_highlighted_texts',
        'mat_large_print',
        'mat_note_taking',
        'mat_other',
        'mat_special_equip',
        'mat_taped_texts',
        'mat_type_handwritten',
        'mat_use_supp_mats',
        'mot_allow_movement',
        'mot_concrete_reinforcement',
        'mot_increase_rewards',
        'mot_nonverbal',
        'mot_offer_choice',
        'mot_other',
        'mot_positive_reinforcement',
        'mot_use_contracts',
        'mot_use_strengths_often',
        'mot_verbal',
        'pacing_allow_breaks',
        'pacing_extended_time',
        'pacing_omit_assignments',
        'pacing_other',
        'pacing_school_texts',
        'pacing_vary_activity',
        'self_man_assignment_book',
        'self_man_behavior_manage',
        'self_man_con_reinforcement',
        'self_man_daily_schedule',
        'self_man_long_term_assign',
        'self_man_other',
        'self_man_peer_tutoring',
        'self_man_plan_general',
        'self_man_pos_reinforcement',
        'self_man_redo_assignment',
        'self_man_repeated_review',
        'self_man_repeat_directions',
        'self_man_req_par_reinforcement',
        'self_man_study_sheets',
        'self_man_teach_skill_sev',
        'self_man_teach_study_skills',
        'self_man_understand_review',
        'self_man_voc_files',
        'soc_coop_learning_groups',
        'soc_multiple_peers',
        'soc_other',
        'soc_peer_advocacy',
        'soc_perr_tutoring',
        'soc_shared_experience',
        'soc_social_process',
        'soc_structure_activities',
        'soc_teach_friendship',
        'soc_teach_social_com',
        'testing_allow_students',
        'testing_app_settings',
        'testing_check_understand',
        'testing_circle_items',
        'testing_color_coded',
        'testing_correct_test',
        'testing_divide_test',
        'testing_extended_time',
        'testing_flash_cards',
        'testing_mod_format',
        'testing_mult_choice',
        'testing_oral',
        'testing_other',
        'testing_para_test',
        'testing_prev_lang',
        'testing_provide_reminders',
        'testing_provide_study',
        'testing_provide_visual',
        'testing_read_test',
        'testing_reteach_material',
        'testing_retest_options',
        'testing_shorten_length',
        'testing_short_ans',
        'testing_sign_directions',
        'testing_sign_test',
        'testing_taped',
        'testing_test_admin',
        'testing_use_more_objective',
        'testing_word_bank',
        'writing_allow_computer',
        'writing_allow_flow_chart',
        'writing_dictate_ideas',
        'writing_grade_content',
        'writing_other',
        'writing_provide_structure',
        'writing_shorten_assignment',
        'writing_use_tape_recorder',
        'writing_visual_rep_ideas',
        'other',
        'env_comp_tech_work',
        'ass_provide_electronic',
        'testing_utilize_writing_sys',
        'asstech_supp_writ_device',
        'asstech_pro_writ_sw',
        'asstech_speech_gen',
        'asstech_aug_options',
        'asstech_enlarged_print',
        'asstech_braille',
        'asstech_aud_trainer',
        'asstech_other',
        'asstech_physical_access',
        'asstech_other_text',
    );


    public function checklistEmpty($checklistData) {
        foreach($this->checkFields as $fieldName) {
            if(!is_null($checklistData[$fieldName])) return false;
        }
        return true;
    }
    public function convertToArrayNotation($checklistData) {
        $accChecklist = new Form_Form004AccomodationsChecklist();
        $form = $accChecklist->accomodations_checklist_edit_version1();
        foreach($this->checkFields as $fieldName) {
            if($form->getElement($fieldName)) {
                if('App_Form_Element_Select' == $form->getElement($fieldName)->getType() &&  isset($checklistData[$fieldName]) && substr_count($checklistData[$fieldName], ',') > 0) {
                    $checklistData[$fieldName] = explode(',',$checklistData[$fieldName]);
                }
            }
        }
        return $checklistData;
    }
    public function convertToCommaNotation($checklistData) {
        foreach($this->checkFields as $fieldName) {
            if(isset($checklistData[$fieldName]) && is_array($checklistData[$fieldName])) {
                $checklistData[$fieldName] = implode(',',$checklistData[$fieldName]);
            }
        }
        return $checklistData;
    }
}