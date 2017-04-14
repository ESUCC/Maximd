<?php

class Form_Form004AccomodationsChecklist extends Form_AbstractForm {
    
    private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function init()
    {
        $this->setEditorType('App_Form_Element_TinyMceTextarea');
    }

    public function labelRight()
    {
        $decorators = array(
            'ViewHelper',
            array('Label', array('placement'=>'append', 'tag' => 'td') ),
        );
        return $decorators;
    }
    public function accomodations_checklist_edit_version9() {
        return $this->accomodations_checklist_edit_version1();
    }
    public function accomodations_checklist_edit_version10() {
        return $this->accomodations_checklist_edit_version1();
    }
//    public function accomodations_checklist_edit_version11() {
//        $this->accomodations_checklist_edit_version1();
//
//        // loop through form elements and change selects to multi selects
//        $this->convertSelectToMultiSelect($this);
//
//        return $this;
//    }

    public function accomodations_checklist_edit_version1() {

        $this->setDecorators ( array (
                                array ('ViewScript',
                                    array (
                                        'viewScript' => 'form004/accomodations_checklist_edit_version1.phtml'
                                    )
                                )
                            ) );

        // allow html characters in multioptions and other display
        $this->getView()->setEscape('stripslashes');

        $this->hide = new App_Form_Element_Checkbox('hide', array('onclick'=>'javascript:enableDisableAccomodationsChecklist(this.checked)'));
        $this->hide->setCheckedValue('t');
        $this->hide->setUnCheckedValue('f');

        $this->id_accom_checklist = new App_Form_Element_Hidden('id_accom_checklist');

        //
        // these fields are currenly being used to
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        //
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;

        // NO DELETE ROW BUTTON!
        //$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check and save to remove the Accomodations Checklist:'));
        //$this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
        //$this->remove_row->ignore = true;

        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Assistive Technology Row");


        $this->pacing_extended_time = new App_Form_Element_Select('pacing_extended_time',array('label'=>"Extend time requirements"));
        $this->pacing_extended_time->setMultiOptions($this->getSubjects());
        $this->pacing_extended_time->setDecorators($this->labelRight());
        $this->pacing_extended_time->setDescription('Pacing');
        $this->pacing_extended_time->setAttrib('acc_menu', '1');
        $this->pacing_extended_time->setRequired(false);
        $this->pacing_extended_time->setAllowEmpty(false);
        $this->pacing_extended_time->addOnChange('otherHelper(this.value, \'*' . $this->pacing_extended_time->getDescription() . ' - ' .$this->pacing_extended_time->getLabel().'\')');

        $this->pacing_allow_breaks = new App_Form_Element_Select('pacing_allow_breaks',array('label'=>"Allow breaks, vary activity often"));
        $this->pacing_allow_breaks->setMultiOptions($this->getSubjects());
        $this->pacing_allow_breaks->setDecorators($this->labelRight());
        $this->pacing_allow_breaks->setDescription('Pacing');
        $this->pacing_allow_breaks->setAttrib('acc_menu', '1');
        $this->pacing_allow_breaks->setRequired(false);
        $this->pacing_allow_breaks->setAllowEmpty(false);
        $this->pacing_allow_breaks->addOnChange('otherHelper(this.value, \'*' . $this->pacing_allow_breaks->getDescription() . ' - ' .$this->pacing_allow_breaks->getLabel().'\')');


        $this->pacing_omit_assignments = new App_Form_Element_Select('pacing_omit_assignments',array('label'=>"Omit assignments requiring<BR>- copying, if timed"));
        $this->pacing_omit_assignments->setMultiOptions($this->getSubjects());
        $this->pacing_omit_assignments->setDecorators($this->labelRight());
        $this->pacing_omit_assignments->setDescription('Pacing');
        $this->pacing_omit_assignments->setAttrib('acc_menu', '1');
        $this->pacing_omit_assignments->setRequired(false);
        $this->pacing_omit_assignments->setAllowEmpty(false);
        $this->pacing_omit_assignments->addOnChange('otherHelper(this.value, \'*' . $this->pacing_omit_assignments->getDescription() . ' - ' .$this->pacing_omit_assignments->getLabel().'\')');


        $this->pacing_school_texts = new App_Form_Element_Select('pacing_school_texts',array('label'=>"School texts sent home for preview"));
        $this->pacing_school_texts->setMultiOptions($this->getSubjects());
        $this->pacing_school_texts->setDecorators($this->labelRight());
        $this->pacing_school_texts->setDescription('Pacing');
        $this->pacing_school_texts->setAttrib('acc_menu', '1');
        $this->pacing_school_texts->setRequired(false);
        $this->pacing_school_texts->setAllowEmpty(false);
        $this->pacing_school_texts->addOnChange('otherHelper(this.value, \'*' . $this->pacing_school_texts->getDescription() . ' - ' .$this->pacing_school_texts->getLabel().'\')');


        $this->pacing_vary_activity = new App_Form_Element_Select('pacing_vary_activity',array('label'=>"Vary activity often"));
        $this->pacing_vary_activity->setMultiOptions($this->getSubjects());
        $this->pacing_vary_activity->setDecorators($this->labelRight());
        $this->pacing_vary_activity->setDescription('Pacing');
        $this->pacing_vary_activity->setAttrib('acc_menu', '1');
        $this->pacing_vary_activity->setRequired(false);
        $this->pacing_vary_activity->setAllowEmpty(false);
        $this->pacing_vary_activity->addOnChange('otherHelper(this.value, \'*' . $this->pacing_vary_activity->getDescription() . ' - ' .$this->pacing_vary_activity->getLabel().'\')');


        $this->pacing_other = new App_Form_Element_Select('pacing_other',array('label'=>"Other"));
        $this->pacing_other->setMultiOptions($this->getSubjects());
        $this->pacing_other->setDecorators($this->labelRight());
        $this->pacing_other->setDescription('Pacing');
        $this->pacing_other->setAttrib('acc_menu', '1');
        $this->pacing_other->setRequired(false);
        $this->pacing_other->setAllowEmpty(false);
        $this->pacing_other->addOnChange('otherHelper(this.value, \'*' . $this->pacing_other->getDescription() . ' - ' .$this->pacing_other->getLabel().'\')');



        $this->lessson_teacher_emph = new App_Form_Element_Select('lessson_teacher_emph',array('label'=>"Teacher emphasize:<BR>- Visual<BR>- Auditory<BR>- Tactil<BR>- Multi-sensory"));
        $this->lessson_teacher_emph->setMultiOptions($this->getSubjects());
        $this->lessson_teacher_emph->setDecorators($this->labelRight());
        $this->lessson_teacher_emph->setDescription('Lesson Presentation');
        $this->lessson_teacher_emph->setAttrib('acc_menu', '1');
        $this->lessson_teacher_emph->setRequired(false);
        $this->lessson_teacher_emph->setAllowEmpty(false);
        $this->lessson_teacher_emph->addOnChange('otherHelper(this.value, \'*' . $this->lessson_teacher_emph->getDescription() . ' - ' .$this->lessson_teacher_emph->getLabel().'\')');


        $this->lessson_sm_grp_inst = new App_Form_Element_Select('lessson_sm_grp_inst',array('label'=>"Individual/Small group Instruction"));
        $this->lessson_sm_grp_inst->setMultiOptions($this->getSubjects());
        $this->lessson_sm_grp_inst->setDecorators($this->labelRight());
        $this->lessson_sm_grp_inst->setDescription('Lesson Presentation');
        $this->lessson_sm_grp_inst->setAttrib('acc_menu', '1');
        $this->lessson_sm_grp_inst->setRequired(false);
        $this->lessson_sm_grp_inst->setAllowEmpty(false);
        $this->lessson_sm_grp_inst->addOnChange('otherHelper(this.value, \'*' . $this->lessson_sm_grp_inst->getDescription() . ' - ' .$this->lessson_sm_grp_inst->getLabel().'\')');


        $this->lessson_spec_curr = new App_Form_Element_Select('lessson_spec_curr',array('label'=>"Utilize specialized curriculum"));
        $this->lessson_spec_curr->setMultiOptions($this->getSubjects());
        $this->lessson_spec_curr->setDecorators($this->labelRight());
        $this->lessson_spec_curr->setDescription('Lesson Presentation');
        $this->lessson_spec_curr->setAttrib('acc_menu', '1');
        $this->lessson_spec_curr->setRequired(false);
        $this->lessson_spec_curr->setAllowEmpty(false);
        $this->lessson_spec_curr->addOnChange('otherHelper(this.value, \'*' . $this->lessson_spec_curr->getDescription() . ' - ' .$this->lessson_spec_curr->getLabel().'\')');


        $this->lessson_tape_lectures = new App_Form_Element_Select('lessson_tape_lectures',array('label'=>"Tape lectures for replay"));
        $this->lessson_tape_lectures->setMultiOptions($this->getSubjects());
        $this->lessson_tape_lectures->setDecorators($this->labelRight());
        $this->lessson_tape_lectures->setDescription('Lesson Presentation');
        $this->lessson_tape_lectures->setAttrib('acc_menu', '1');
        $this->lessson_tape_lectures->setRequired(false);
        $this->lessson_tape_lectures->setAllowEmpty(false);
        $this->lessson_tape_lectures->addOnChange('otherHelper(this.value, \'*' . $this->lessson_tape_lectures->getDescription() . ' - ' .$this->lessson_tape_lectures->getLabel().'\')');


        $this->lessson_utilize_manip = new App_Form_Element_Select('lessson_utilize_manip',array('label'=>"Utilize manipulative materials"));
        $this->lessson_utilize_manip->setMultiOptions($this->getSubjects());
        $this->lessson_utilize_manip->setDecorators($this->labelRight());
        $this->lessson_utilize_manip->setDescription('Lesson Presentation');
        $this->lessson_utilize_manip->setAttrib('acc_menu', '1');
        $this->lessson_utilize_manip->setRequired(false);
        $this->lessson_utilize_manip->setAllowEmpty(false);
        $this->lessson_utilize_manip->addOnChange('otherHelper(this.value, \'*' . $this->lessson_utilize_manip->getDescription() . ' - ' .$this->lessson_utilize_manip->getLabel().'\')');


        $this->lessson_emph_info = new App_Form_Element_Select('lessson_emph_info',array('label'=>"Emphasize critical information"));
        $this->lessson_emph_info->setMultiOptions($this->getSubjects());
        $this->lessson_emph_info->setDecorators($this->labelRight());
        $this->lessson_emph_info->setDescription('Lesson Presentation');
        $this->lessson_emph_info->setAttrib('acc_menu', '1');
        $this->lessson_emph_info->setRequired(false);
        $this->lessson_emph_info->setAllowEmpty(false);
        $this->lessson_emph_info->addOnChange('otherHelper(this.value, \'*' . $this->lessson_emph_info->getDescription() . ' - ' .$this->lessson_emph_info->getLabel().'\')');


        $this->lessson_preteach_voc = new App_Form_Element_Select('lessson_preteach_voc',array('label'=>"Preteach vocabulary"));
        $this->lessson_preteach_voc->setMultiOptions($this->getSubjects());
        $this->lessson_preteach_voc->setDecorators($this->labelRight());
        $this->lessson_preteach_voc->setDescription('Lesson Presentation');
        $this->lessson_preteach_voc->setAttrib('acc_menu', '1');
        $this->lessson_preteach_voc->setRequired(false);
        $this->lessson_preteach_voc->setAllowEmpty(false);
        $this->lessson_preteach_voc->addOnChange('otherHelper(this.value, \'*' . $this->lessson_preteach_voc->getDescription() . ' - ' .$this->lessson_preteach_voc->getLabel().'\')');


        $this->lessson_reduce_lang = new App_Form_Element_Select('lessson_reduce_lang',array('label'=>"Reduce language level or reading<BR>- level of assignment"));
        $this->lessson_reduce_lang->setMultiOptions($this->getSubjects());
        $this->lessson_reduce_lang->setDecorators($this->labelRight());
        $this->lessson_reduce_lang->setDescription('Lesson Presentation');
        $this->lessson_reduce_lang->setAttrib('acc_menu', '1');
        $this->lessson_reduce_lang->setRequired(false);
        $this->lessson_reduce_lang->setAllowEmpty(false);
        $this->lessson_reduce_lang->addOnChange('otherHelper(this.value, \'*' . $this->lessson_reduce_lang->getDescription() . ' - ' .$this->lessson_reduce_lang->getLabel().'\')');


        $this->lessson_sign_lang = new App_Form_Element_Select('lessson_sign_lang',array('label'=>"Sign language Interpreter"));
        $this->lessson_sign_lang->setMultiOptions($this->getSubjects());
        $this->lessson_sign_lang->setDecorators($this->labelRight());
        $this->lessson_sign_lang->setDescription('Lesson Presentation');
        $this->lessson_sign_lang->setAttrib('acc_menu', '1');
        $this->lessson_sign_lang->setRequired(false);
        $this->lessson_sign_lang->setAllowEmpty(false);
        $this->lessson_sign_lang->addOnChange('otherHelper(this.value, \'*' . $this->lessson_sign_lang->getDescription() . ' - ' .$this->lessson_sign_lang->getLabel().'\')');


        $this->lessson_total_comm = new App_Form_Element_Select('lessson_total_comm',array('label'=>"Use specialized communication supports"));
        $this->lessson_total_comm->setMultiOptions($this->getSubjects());
        $this->lessson_total_comm->setDecorators($this->labelRight());
        $this->lessson_total_comm->setDescription('Lesson Presentation');
        $this->lessson_total_comm->setAttrib('acc_menu', '1');
        $this->lessson_total_comm->setRequired(false);
        $this->lessson_total_comm->setAllowEmpty(false);
        $this->lessson_total_comm->addOnChange('otherHelper(this.value, \'*' . $this->lessson_total_comm->getDescription() . ' - ' .$this->lessson_total_comm->getLabel().'\')');


        $this->lessson_oral_intrepreter = new App_Form_Element_Select('lessson_oral_intrepreter',array('label'=>"Oral Interpreter"));
        $this->lessson_oral_intrepreter->setMultiOptions($this->getSubjects());
        $this->lessson_oral_intrepreter->setDecorators($this->labelRight());
        $this->lessson_oral_intrepreter->setDescription('Lesson Presentation');
        $this->lessson_oral_intrepreter->setAttrib('acc_menu', '1');
        $this->lessson_oral_intrepreter->setRequired(false);
        $this->lessson_oral_intrepreter->setAllowEmpty(false);
        $this->lessson_oral_intrepreter->addOnChange('otherHelper(this.value, \'*' . $this->lessson_oral_intrepreter->getDescription() . ' - ' .$this->lessson_oral_intrepreter->getLabel().'\')');


        $this->lessson_present_demo = new App_Form_Element_Select('lessson_present_demo',array('label'=>"Present demonstration (model)"));
        $this->lessson_present_demo->setMultiOptions($this->getSubjects());
        $this->lessson_present_demo->setDecorators($this->labelRight());
        $this->lessson_present_demo->setDescription('Lesson Presentation');
        $this->lessson_present_demo->setAttrib('acc_menu', '1');
        $this->lessson_present_demo->setRequired(false);
        $this->lessson_present_demo->setAllowEmpty(false);
        $this->lessson_present_demo->addOnChange('otherHelper(this.value, \'*' . $this->lessson_present_demo->getDescription() . ' - ' .$this->lessson_present_demo->getLabel().'\')');


        $this->lessson_teacher_provides = new App_Form_Element_Select('lessson_teacher_provides',array('label'=>"Teacher or peer provides notes or outline"));
        $this->lessson_teacher_provides->setMultiOptions($this->getSubjects());
        $this->lessson_teacher_provides->setDecorators($this->labelRight());
        $this->lessson_teacher_provides->setDescription('Lesson Presentation');
        $this->lessson_teacher_provides->setAttrib('acc_menu', '1');
        $this->lessson_teacher_provides->setRequired(false);
        $this->lessson_teacher_provides->setAllowEmpty(false);
        $this->lessson_teacher_provides->addOnChange('otherHelper(this.value, \'*' . $this->lessson_teacher_provides->getDescription() . ' - ' .$this->lessson_teacher_provides->getLabel().'\')');


        $this->lessson_functional_app = new App_Form_Element_Select('lessson_functional_app',array('label'=>"Functional application of academic skills"));
        $this->lessson_functional_app->setMultiOptions($this->getSubjects());
        $this->lessson_functional_app->setDecorators($this->labelRight());
        $this->lessson_functional_app->setDescription('Lesson Presentation');
        $this->lessson_functional_app->setAttrib('acc_menu', '1');
        $this->lessson_functional_app->setRequired(false);
        $this->lessson_functional_app->setAllowEmpty(false);
        $this->lessson_functional_app->addOnChange('otherHelper(this.value, \'*' . $this->lessson_functional_app->getDescription() . ' - ' .$this->lessson_functional_app->getLabel().'\')');


        $this->lessson_visual_sequences = new App_Form_Element_Select('lessson_visual_sequences',array('label'=>"Use visual sequences"));
        $this->lessson_visual_sequences->setMultiOptions($this->getSubjects());
        $this->lessson_visual_sequences->setDecorators($this->labelRight());
        $this->lessson_visual_sequences->setDescription('Lesson Presentation');
        $this->lessson_visual_sequences->setAttrib('acc_menu', '1');
        $this->lessson_visual_sequences->setRequired(false);
        $this->lessson_visual_sequences->setAllowEmpty(false);
        $this->lessson_visual_sequences->addOnChange('otherHelper(this.value, \'*' . $this->lessson_visual_sequences->getDescription() . ' - ' .$this->lessson_visual_sequences->getLabel().'\')');


        $this->lessson_make_use_voc = new App_Form_Element_Select('lessson_make_use_voc',array('label'=>"Make/use vocabulary supports such as word cards, personal dictionaries, vocabulary files"));
        $this->lessson_make_use_voc->setMultiOptions($this->getSubjects());
        $this->lessson_make_use_voc->setDecorators($this->labelRight());
        $this->lessson_make_use_voc->setDescription('Lesson Presentation');
        $this->lessson_make_use_voc->setAttrib('acc_menu', '1');
        $this->lessson_make_use_voc->setRequired(false);
        $this->lessson_make_use_voc->setAllowEmpty(false);
        $this->lessson_make_use_voc->addOnChange('otherHelper(this.value, \'*' . $this->lessson_make_use_voc->getDescription() . ' - ' .$this->lessson_make_use_voc->getLabel().'\')');


        $this->lessson_other = new App_Form_Element_Select('lessson_other',array('label'=>"Other"));
        $this->lessson_other->setMultiOptions($this->getSubjects());
        $this->lessson_other->setDecorators($this->labelRight());
        $this->lessson_other->setDescription('Lesson Presentation');
        $this->lessson_other->setAttrib('acc_menu', '1');
        $this->lessson_other->setRequired(false);
        $this->lessson_other->setAllowEmpty(false);
        $this->lessson_other->addOnChange('otherHelper(this.value, \'*' . $this->lessson_other->getDescription() . ' - ' .$this->lessson_other->getLabel().'\')');



        $this->env_pref_seating = new App_Form_Element_Select('env_pref_seating',array('label'=>"Preferential seating"));
        $this->env_pref_seating->setMultiOptions($this->getSubjects());
        $this->env_pref_seating->setDecorators($this->labelRight());
        $this->env_pref_seating->setDescription('Environment');
        $this->env_pref_seating->setAttrib('acc_menu', '1');
        $this->env_pref_seating->setRequired(false);
        $this->env_pref_seating->setAllowEmpty(false);
        $this->env_pref_seating->addOnChange('otherHelper(this.value, \'*' . $this->env_pref_seating->getDescription() . ' - ' .$this->env_pref_seating->getLabel().'\')');


        $this->env_seat_near_teacher = new App_Form_Element_Select('env_seat_near_teacher',array('label'=>"Seat near teacher"));
        $this->env_seat_near_teacher->setMultiOptions($this->getSubjects());
        $this->env_seat_near_teacher->setDecorators($this->labelRight());
        $this->env_seat_near_teacher->setDescription('Environment');
        $this->env_seat_near_teacher->setAttrib('acc_menu', '1');
        $this->env_seat_near_teacher->setRequired(false);
        $this->env_seat_near_teacher->setAllowEmpty(false);
        $this->env_seat_near_teacher->addOnChange('otherHelper(this.value, \'*' . $this->env_seat_near_teacher->getDescription() . ' - ' .$this->env_seat_near_teacher->getLabel().'\')');


        $this->env_seat_near_role = new App_Form_Element_Select('env_seat_near_role',array('label'=>"Seat near positive role model"));
        $this->env_seat_near_role->setMultiOptions($this->getSubjects());
        $this->env_seat_near_role->setDecorators($this->labelRight());
        $this->env_seat_near_role->setDescription('Environment');
        $this->env_seat_near_role->setAttrib('acc_menu', '1');
        $this->env_seat_near_role->setRequired(false);
        $this->env_seat_near_role->setAllowEmpty(false);
        $this->env_seat_near_role->addOnChange('otherHelper(this.value, \'*' . $this->env_seat_near_role->getDescription() . ' - ' .$this->env_seat_near_role->getLabel().'\')');


        $this->env_avoid_distr = new App_Form_Element_Select('env_avoid_distr',array('label'=>"Avoid distracting stimuli"));
        $this->env_avoid_distr->setMultiOptions($this->getSubjects());
        $this->env_avoid_distr->setDecorators($this->labelRight());
        $this->env_avoid_distr->setDescription('Environment');
        $this->env_avoid_distr->setAttrib('acc_menu', '1');
        $this->env_avoid_distr->setRequired(false);
        $this->env_avoid_distr->setAllowEmpty(false);
        $this->env_avoid_distr->addOnChange('otherHelper(this.value, \'*' . $this->env_avoid_distr->getDescription() . ' - ' .$this->env_avoid_distr->getLabel().'\')');


        $this->env_increase_distance = new App_Form_Element_Select('env_increase_distance',array('label'=>"Increase distance between desks"));
        $this->env_increase_distance->setMultiOptions($this->getSubjects());
        $this->env_increase_distance->setDecorators($this->labelRight());
        $this->env_increase_distance->setDescription('Environment');
        $this->env_increase_distance->setAttrib('acc_menu', '1');
        $this->env_increase_distance->setRequired(false);
        $this->env_increase_distance->setAllowEmpty(false);
        $this->env_increase_distance->addOnChange('otherHelper(this.value, \'*' . $this->env_increase_distance->getDescription() . ' - ' .$this->env_increase_distance->getLabel().'\')');


        $this->env_planned_seating = new App_Form_Element_Select('env_planned_seating',array('label'=>"Planned seating:<BR>- Bus Classroom<BR>- Lunchroom <BR>- Auditorium"));
        $this->env_planned_seating->setMultiOptions($this->getSubjects());
        $this->env_planned_seating->setDecorators($this->labelRight());
        $this->env_planned_seating->setDescription('Environment');
        $this->env_planned_seating->setAttrib('acc_menu', '1');
        $this->env_planned_seating->setRequired(false);
        $this->env_planned_seating->setAllowEmpty(false);
        $this->env_planned_seating->addOnChange('otherHelper(this.value, \'*' . $this->env_planned_seating->getDescription() . ' - ' .$this->env_planned_seating->getLabel().'\')');


        $this->env_alter_physical_room = new App_Form_Element_Select('env_alter_physical_room',array('label'=>"Alter physical room arrangement"));
        $this->env_alter_physical_room->setMultiOptions($this->getSubjects());
        $this->env_alter_physical_room->setDecorators($this->labelRight());
        $this->env_alter_physical_room->setDescription('Environment');
        $this->env_alter_physical_room->setAttrib('acc_menu', '1');
        $this->env_alter_physical_room->setRequired(false);
        $this->env_alter_physical_room->setAllowEmpty(false);
        $this->env_alter_physical_room->addOnChange('otherHelper(this.value, \'*' . $this->env_alter_physical_room->getDescription() . ' - ' .$this->env_alter_physical_room->getLabel().'\')');


        $this->env_define_areas = new App_Form_Element_Select('env_define_areas',array('label'=>"Define areas concretely"));
        $this->env_define_areas->setMultiOptions($this->getSubjects());
        $this->env_define_areas->setDecorators($this->labelRight());
        $this->env_define_areas->setDescription('Environment');
        $this->env_define_areas->setAttrib('acc_menu', '1');
        $this->env_define_areas->setRequired(false);
        $this->env_define_areas->setAllowEmpty(false);
        $this->env_define_areas->addOnChange('otherHelper(this.value, \'*' . $this->env_define_areas->getDescription() . ' - ' .$this->env_define_areas->getLabel().'\')');


        $this->env_reduce_distractions = new App_Form_Element_Select('env_reduce_distractions',array('label'=>"Reduce/minimize distractions<BR>- Visual Auditory<BR>- Spatial Movement"));
        $this->env_reduce_distractions->setMultiOptions($this->getSubjects());
        $this->env_reduce_distractions->setDecorators($this->labelRight());
        $this->env_reduce_distractions->setDescription('Environment');
        $this->env_reduce_distractions->setAttrib('acc_menu', '1');
        $this->env_reduce_distractions->setRequired(false);
        $this->env_reduce_distractions->setAllowEmpty(false);
        $this->env_reduce_distractions->addOnChange('otherHelper(this.value, \'*' . $this->env_reduce_distractions->getDescription() . ' - ' .$this->env_reduce_distractions->getLabel().'\')');


        $this->env_teach_pos_rules = new App_Form_Element_Select('env_teach_pos_rules',array('label'=>"Teach positive rules for use of space"));
        $this->env_teach_pos_rules->setMultiOptions($this->getSubjects());
        $this->env_teach_pos_rules->setDecorators($this->labelRight());
        $this->env_teach_pos_rules->setDescription('Environment');
        $this->env_teach_pos_rules->setAttrib('acc_menu', '1');
        $this->env_teach_pos_rules->setRequired(false);
        $this->env_teach_pos_rules->setAllowEmpty(false);
        $this->env_teach_pos_rules->addOnChange('otherHelper(this.value, \'*' . $this->env_teach_pos_rules->getDescription() . ' - ' .$this->env_teach_pos_rules->getLabel().'\')');


        $this->env_comp_tech_work = new App_Form_Element_Select('env_comp_tech_work',array('label'=>"Complete work in technology setting"));
        $this->env_comp_tech_work->setMultiOptions($this->getSubjects());
        $this->env_comp_tech_work->setDecorators($this->labelRight());
        $this->env_comp_tech_work->setDescription('Environment');
        $this->env_comp_tech_work->setAttrib('acc_menu', '1');
        $this->env_comp_tech_work->setRequired(false);
        $this->env_comp_tech_work->setAllowEmpty(false);
        $this->env_comp_tech_work->addOnChange('otherHelper(this.value, \'*' . $this->env_comp_tech_work->getDescription() . ' - ' .$this->env_comp_tech_work->getLabel().'\')');


        $this->env_other = new App_Form_Element_Select('env_other',array('label'=>"Other"));
        $this->env_other->setMultiOptions($this->getSubjects());
        $this->env_other->setDecorators($this->labelRight());
        $this->env_other->setDescription('Environment');
        $this->env_other->setAttrib('acc_menu', '1');
        $this->env_other->setRequired(false);
        $this->env_other->setAllowEmpty(false);
        $this->env_other->addOnChange('otherHelper(this.value, \'*' . $this->env_other->getDescription() . ' - ' .$this->env_other->getLabel().'\')');



        $this->mat_taped_texts = new App_Form_Element_Select('mat_taped_texts',array('label'=>"Taped texts and/or other class materials"));
        $this->mat_taped_texts->setMultiOptions($this->getSubjects());
        $this->mat_taped_texts->setDecorators($this->labelRight());
        $this->mat_taped_texts->setDescription('Materials');
        $this->mat_taped_texts->setAttrib('acc_menu', '1');
        $this->mat_taped_texts->setRequired(false);
        $this->mat_taped_texts->setAllowEmpty(false);
        $this->mat_taped_texts->addOnChange('otherHelper(this.value, \'*' . $this->mat_taped_texts->getDescription() . ' - ' .$this->mat_taped_texts->getLabel().'\')');


        $this->mat_highlighted_texts = new App_Form_Element_Select('mat_highlighted_texts',array('label'=>"Highlighted texts/study guides"));
        $this->mat_highlighted_texts->setMultiOptions($this->getSubjects());
        $this->mat_highlighted_texts->setDecorators($this->labelRight());
        $this->mat_highlighted_texts->setDescription('Materials');
        $this->mat_highlighted_texts->setAttrib('acc_menu', '1');
        $this->mat_highlighted_texts->setRequired(false);
        $this->mat_highlighted_texts->setAllowEmpty(false);
        $this->mat_highlighted_texts->addOnChange('otherHelper(this.value, \'*' . $this->mat_highlighted_texts->getDescription() . ' - ' .$this->mat_highlighted_texts->getLabel().'\')');


        $this->mat_use_supp_mats = new App_Form_Element_Select('mat_use_supp_mats',array('label'=>"Use supplementary materials"));
        $this->mat_use_supp_mats->setMultiOptions($this->getSubjects());
        $this->mat_use_supp_mats->setDecorators($this->labelRight());
        $this->mat_use_supp_mats->setDescription('Materials');
        $this->mat_use_supp_mats->setAttrib('acc_menu', '1');
        $this->mat_use_supp_mats->setRequired(false);
        $this->mat_use_supp_mats->setAllowEmpty(false);
        $this->mat_use_supp_mats->addOnChange('otherHelper(this.value, \'*' . $this->mat_use_supp_mats->getDescription() . ' - ' .$this->mat_use_supp_mats->getLabel().'\')');


        $this->mat_note_taking = new App_Form_Element_Select('mat_note_taking',array('label'=>"Note taking assistance:Photocopy of notes of peer"));
        $this->mat_note_taking->setMultiOptions($this->getSubjects());
        $this->mat_note_taking->setDecorators($this->labelRight());
        $this->mat_note_taking->setDescription('Materials');
        $this->mat_note_taking->setAttrib('acc_menu', '1');
        $this->mat_note_taking->setRequired(false);
        $this->mat_note_taking->setAllowEmpty(false);
        $this->mat_note_taking->addOnChange('otherHelper(this.value, \'*' . $this->mat_note_taking->getDescription() . ' - ' .$this->mat_note_taking->getLabel().'\')');


        $this->mat_type_handwritten = new App_Form_Element_Select('mat_type_handwritten',array('label'=>"Type handwritten teacher material"));
        $this->mat_type_handwritten->setMultiOptions($this->getSubjects());
        $this->mat_type_handwritten->setDecorators($this->labelRight());
        $this->mat_type_handwritten->setDescription('Materials');
        $this->mat_type_handwritten->setAttrib('acc_menu', '1');
        $this->mat_type_handwritten->setRequired(false);
        $this->mat_type_handwritten->setAllowEmpty(false);
        $this->mat_type_handwritten->addOnChange('otherHelper(this.value, \'*' . $this->mat_type_handwritten->getDescription() . ' - ' .$this->mat_type_handwritten->getLabel().'\')');


        $this->mat_arrangement = new App_Form_Element_Select('mat_arrangement',array('label'=>"Arrangement of materials on page"));
        $this->mat_arrangement->setMultiOptions($this->getSubjects());
        $this->mat_arrangement->setDecorators($this->labelRight());
        $this->mat_arrangement->setDescription('Materials');
        $this->mat_arrangement->setAttrib('acc_menu', '1');
        $this->mat_arrangement->setRequired(false);
        $this->mat_arrangement->setAllowEmpty(false);
        $this->mat_arrangement->addOnChange('otherHelper(this.value, \'*' . $this->mat_arrangement->getDescription() . ' - ' .$this->mat_arrangement->getLabel().'\')');


        $this->mat_large_print = new App_Form_Element_Select('mat_large_print',array('label'=>"Provide large print materials"));
        $this->mat_large_print->setMultiOptions($this->getSubjects());
        $this->mat_large_print->setDecorators($this->labelRight());
        $this->mat_large_print->setDescription('Materials');
        $this->mat_large_print->setAttrib('acc_menu', '1');
        $this->mat_large_print->setRequired(false);
        $this->mat_large_print->setAllowEmpty(false);
        $this->mat_large_print->addOnChange('otherHelper(this.value, \'*' . $this->mat_large_print->getDescription() . ' - ' .$this->mat_large_print->getLabel().'\')');


        $this->mat_enlarge_notes = new App_Form_Element_Select('mat_enlarge_notes',array('label'=>"Enlarge notes/workbook pages (photocopier)"));
        $this->mat_enlarge_notes->setMultiOptions($this->getSubjects());
        $this->mat_enlarge_notes->setDecorators($this->labelRight());
        $this->mat_enlarge_notes->setDescription('Materials');
        $this->mat_enlarge_notes->setAttrib('acc_menu', '1');
        $this->mat_enlarge_notes->setRequired(false);
        $this->mat_enlarge_notes->setAllowEmpty(false);
        $this->mat_enlarge_notes->addOnChange('otherHelper(this.value, \'*' . $this->mat_enlarge_notes->getDescription() . ' - ' .$this->mat_enlarge_notes->getLabel().'\')');


        $this->mat_special_equip = new App_Form_Element_Select('mat_special_equip',array('label'=>"Special equipment:<BR>- Calculator<BR>- Computer<BR>- Video recorder<BR>- Audio Recorder<BR>- SGD: Speech Generating Device<BR>- Amplification System"));
        $this->mat_special_equip->setMultiOptions($this->getSubjects());
        $this->mat_special_equip->setDecorators($this->labelRight());
        $this->mat_special_equip->setDescription('Materials');
        $this->mat_special_equip->setAttrib('acc_menu', '1');
        $this->mat_special_equip->setRequired(false);
        $this->mat_special_equip->setAllowEmpty(false);
        $this->mat_special_equip->addOnChange('otherHelper(this.value, \'*' . $this->mat_special_equip->getDescription() . ' - ' .$this->mat_special_equip->getLabel().'\')');


        $this->mat_other = new App_Form_Element_Select('mat_other',array('label'=>"Other"));
        $this->mat_other->setMultiOptions($this->getSubjects());
        $this->mat_other->setDecorators($this->labelRight());
        $this->mat_other->setDescription('Materials');
        $this->mat_other->setAttrib('acc_menu', '1');
        $this->mat_other->setRequired(false);
        $this->mat_other->setAllowEmpty(false);
        $this->mat_other->addOnChange('otherHelper(this.value, \'*' . $this->mat_other->getDescription() . ' - ' .$this->mat_other->getLabel().'\')');



        $this->ass_give_directions = new App_Form_Element_Select('ass_give_directions',array('label'=>"Give directions in small, distinct steps"));
        $this->ass_give_directions->setMultiOptions($this->getSubjects());
        $this->ass_give_directions->setDecorators($this->labelRight());
        $this->ass_give_directions->setDescription('Assignments');
        $this->ass_give_directions->setAttrib('acc_menu', '1');
        $this->ass_give_directions->setRequired(false);
        $this->ass_give_directions->setAllowEmpty(false);
        $this->ass_give_directions->addOnChange('otherHelper(this.value, \'*' . $this->ass_give_directions->getDescription() . ' - ' .$this->ass_give_directions->getLabel().'\')');


        $this->ass_allow_copying = new App_Form_Element_Select('ass_allow_copying',array('label'=>"Allow copying an answer directly from paper/book"));
        $this->ass_allow_copying->setMultiOptions($this->getSubjects());
        $this->ass_allow_copying->setDecorators($this->labelRight());
        $this->ass_allow_copying->setDescription('Assignments');
        $this->ass_allow_copying->setAttrib('acc_menu', '1');
        $this->ass_allow_copying->setRequired(false);
        $this->ass_allow_copying->setAllowEmpty(false);
        $this->ass_allow_copying->addOnChange('otherHelper(this.value, \'*' . $this->ass_allow_copying->getDescription() . ' - ' .$this->ass_allow_copying->getLabel().'\')');


        $this->ass_provide_oral_directions = new App_Form_Element_Select('ass_provide_oral_directions',array('label'=>"Provide oral directions"));
        $this->ass_provide_oral_directions->setMultiOptions($this->getSubjects());
        $this->ass_provide_oral_directions->setDecorators($this->labelRight());
        $this->ass_provide_oral_directions->setDescription('Assignments');
        $this->ass_provide_oral_directions->setAttrib('acc_menu', '1');
        $this->ass_provide_oral_directions->setRequired(false);
        $this->ass_provide_oral_directions->setAllowEmpty(false);
        $this->ass_provide_oral_directions->addOnChange('otherHelper(this.value, \'*' . $this->ass_provide_oral_directions->getDescription() . ' - ' .$this->ass_provide_oral_directions->getLabel().'\')');


        $this->ass_lower_diff_level = new App_Form_Element_Select('ass_lower_diff_level',array('label'=>"Lower difficulty level"));
        $this->ass_lower_diff_level->setMultiOptions($this->getSubjects());
        $this->ass_lower_diff_level->setDecorators($this->labelRight());
        $this->ass_lower_diff_level->setDescription('Assignments');
        $this->ass_lower_diff_level->setAttrib('acc_menu', '1');
        $this->ass_lower_diff_level->setRequired(false);
        $this->ass_lower_diff_level->setAllowEmpty(false);
        $this->ass_lower_diff_level->addOnChange('otherHelper(this.value, \'*' . $this->ass_lower_diff_level->getDescription() . ' - ' .$this->ass_lower_diff_level->getLabel().'\')');


        $this->ass_shorten_assign = new App_Form_Element_Select('ass_shorten_assign',array('label'=>"Shorten assignment"));
        $this->ass_shorten_assign->setMultiOptions($this->getSubjects());
        $this->ass_shorten_assign->setDecorators($this->labelRight());
        $this->ass_shorten_assign->setDescription('Assignments');
        $this->ass_shorten_assign->setAttrib('acc_menu', '1');
        $this->ass_shorten_assign->setRequired(false);
        $this->ass_shorten_assign->setAllowEmpty(false);
        $this->ass_shorten_assign->addOnChange('otherHelper(this.value, \'*' . $this->ass_shorten_assign->getDescription() . ' - ' .$this->ass_shorten_assign->getLabel().'\')');


        $this->ass_reduce_paper_tasks = new App_Form_Element_Select('ass_reduce_paper_tasks',array('label'=>"Reduce paper and pencil tasks"));
        $this->ass_reduce_paper_tasks->setMultiOptions($this->getSubjects());
        $this->ass_reduce_paper_tasks->setDecorators($this->labelRight());
        $this->ass_reduce_paper_tasks->setDescription('Assignments');
        $this->ass_reduce_paper_tasks->setAttrib('acc_menu', '1');
        $this->ass_reduce_paper_tasks->setRequired(false);
        $this->ass_reduce_paper_tasks->setAllowEmpty(false);
        $this->ass_reduce_paper_tasks->addOnChange('otherHelper(this.value, \'*' . $this->ass_reduce_paper_tasks->getDescription() . ' - ' .$this->ass_reduce_paper_tasks->getLabel().'\')');


        $this->ass_read_directions = new App_Form_Element_Select('ass_read_directions',array('label'=>"Read directions to student"));
        $this->ass_read_directions->setMultiOptions($this->getSubjects());
        $this->ass_read_directions->setDecorators($this->labelRight());
        $this->ass_read_directions->setDescription('Assignments');
        $this->ass_read_directions->setAttrib('acc_menu', '1');
        $this->ass_read_directions->setRequired(false);
        $this->ass_read_directions->setAllowEmpty(false);
        $this->ass_read_directions->addOnChange('otherHelper(this.value, \'*' . $this->ass_read_directions->getDescription() . ' - ' .$this->ass_read_directions->getLabel().'\')');


        $this->ass_give_oral_cues = new App_Form_Element_Select('ass_give_oral_cues',array('label'=>"Give oral cues or prompts"));
        $this->ass_give_oral_cues->setMultiOptions($this->getSubjects());
        $this->ass_give_oral_cues->setDecorators($this->labelRight());
        $this->ass_give_oral_cues->setDescription('Assignments');
        $this->ass_give_oral_cues->setAttrib('acc_menu', '1');
        $this->ass_give_oral_cues->setRequired(false);
        $this->ass_give_oral_cues->setAllowEmpty(false);
        $this->ass_give_oral_cues->addOnChange('otherHelper(this.value, \'*' . $this->ass_give_oral_cues->getDescription() . ' - ' .$this->ass_give_oral_cues->getLabel().'\')');


        $this->ass_record_assignment = new App_Form_Element_Select('ass_record_assignment',array('label'=>"Record or type assignment"));
        $this->ass_record_assignment->setMultiOptions($this->getSubjects());
        $this->ass_record_assignment->setDecorators($this->labelRight());
        $this->ass_record_assignment->setDescription('Assignments');
        $this->ass_record_assignment->setAttrib('acc_menu', '1');
        $this->ass_record_assignment->setRequired(false);
        $this->ass_record_assignment->setAllowEmpty(false);
        $this->ass_record_assignment->addOnChange('otherHelper(this.value, \'*' . $this->ass_record_assignment->getDescription() . ' - ' .$this->ass_record_assignment->getLabel().'\')');


        $this->ass_adapt_worksheet = new App_Form_Element_Select('ass_adapt_worksheet',array('label'=>"Adapt worksheets, packets"));
        $this->ass_adapt_worksheet->setMultiOptions($this->getSubjects());
        $this->ass_adapt_worksheet->setDecorators($this->labelRight());
        $this->ass_adapt_worksheet->setDescription('Assignments');
        $this->ass_adapt_worksheet->setAttrib('acc_menu', '1');
        $this->ass_adapt_worksheet->setRequired(false);
        $this->ass_adapt_worksheet->setAllowEmpty(false);
        $this->ass_adapt_worksheet->addOnChange('otherHelper(this.value, \'*' . $this->ass_adapt_worksheet->getDescription() . ' - ' .$this->ass_adapt_worksheet->getLabel().'\')');


        $this->ass_provide_alternate = new App_Form_Element_Select('ass_provide_alternate',array('label'=>"Provide alternate assignments or strategies"));
        $this->ass_provide_alternate->setMultiOptions($this->getSubjects());
        $this->ass_provide_alternate->setDecorators($this->labelRight());
        $this->ass_provide_alternate->setDescription('Assignments');
        $this->ass_provide_alternate->setAttrib('acc_menu', '1');
        $this->ass_provide_alternate->setRequired(false);
        $this->ass_provide_alternate->setAllowEmpty(false);
        $this->ass_provide_alternate->addOnChange('otherHelper(this.value, \'*' . $this->ass_provide_alternate->getDescription() . ' - ' .$this->ass_provide_alternate->getLabel().'\')');


        $this->ass_avoide_penalizing = new App_Form_Element_Select('ass_avoide_penalizing',array('label'=>"Avoid penalizing for spelling errors"));
        $this->ass_avoide_penalizing->setMultiOptions($this->getSubjects());
        $this->ass_avoide_penalizing->setDecorators($this->labelRight());
        $this->ass_avoide_penalizing->setDescription('Assignments');
        $this->ass_avoide_penalizing->setAttrib('acc_menu', '1');
        $this->ass_avoide_penalizing->setRequired(false);
        $this->ass_avoide_penalizing->setAllowEmpty(false);
        $this->ass_avoide_penalizing->addOnChange('otherHelper(this.value, \'*' . $this->ass_avoide_penalizing->getDescription() . ' - ' .$this->ass_avoide_penalizing->getLabel().'\')');


        $this->ass_redo_for_grade = new App_Form_Element_Select('ass_redo_for_grade',array('label'=>"Redo for better grade"));
        $this->ass_redo_for_grade->setMultiOptions($this->getSubjects());
        $this->ass_redo_for_grade->setDecorators($this->labelRight());
        $this->ass_redo_for_grade->setDescription('Assignments');
        $this->ass_redo_for_grade->setAttrib('acc_menu', '1');
        $this->ass_redo_for_grade->setRequired(false);
        $this->ass_redo_for_grade->setAllowEmpty(false);
        $this->ass_redo_for_grade->addOnChange('otherHelper(this.value, \'*' . $this->ass_redo_for_grade->getDescription() . ' - ' .$this->ass_redo_for_grade->getLabel().'\')');


        $this->ass_allo_use_resource = new App_Form_Element_Select('ass_allo_use_resource',array('label'=>"Allow student to use resource assistance when necessary"));
        $this->ass_allo_use_resource->setMultiOptions($this->getSubjects());
        $this->ass_allo_use_resource->setDecorators($this->labelRight());
        $this->ass_allo_use_resource->setDescription('Assignments');
        $this->ass_allo_use_resource->setAttrib('acc_menu', '1');
        $this->ass_allo_use_resource->setRequired(false);
        $this->ass_allo_use_resource->setAllowEmpty(false);
        $this->ass_allo_use_resource->addOnChange('otherHelper(this.value, \'*' . $this->ass_allo_use_resource->getDescription() . ' - ' .$this->ass_allo_use_resource->getLabel().'\')');


        $this->ass_provide_electronic = new App_Form_Element_Select('ass_provide_electronic',array('label'=>"Provide assignments in an electronic format"));
        $this->ass_provide_electronic->setMultiOptions($this->getSubjects());
        $this->ass_provide_electronic->setDecorators($this->labelRight());
        $this->ass_provide_electronic->setDescription('Assignments');
        $this->ass_provide_electronic->setAttrib('acc_menu', '1');
        $this->ass_provide_electronic->setRequired(false);
        $this->ass_provide_electronic->setAllowEmpty(false);
        $this->ass_provide_electronic->addOnChange('otherHelper(this.value, \'*' . $this->ass_provide_electronic->getDescription() . ' - ' .$this->ass_provide_electronic->getLabel().'\')');


        $this->ass_other = new App_Form_Element_Select('ass_other',array('label'=>"Other"));
        $this->ass_other->setMultiOptions($this->getSubjects());
        $this->ass_other->setDecorators($this->labelRight());
        $this->ass_other->setDescription('Assignments');
        $this->ass_other->setAttrib('acc_menu', '1');
        $this->ass_other->setRequired(false);
        $this->ass_other->setAllowEmpty(false);
        $this->ass_other->addOnChange('otherHelper(this.value, \'*' . $this->ass_other->getDescription() . ' - ' .$this->ass_other->getLabel().'\')');



        $this->soc_perr_tutoring = new App_Form_Element_Select('soc_perr_tutoring',array('label'=>"Peer tutoring"));
        $this->soc_perr_tutoring->setMultiOptions($this->getSubjects());
        $this->soc_perr_tutoring->setDecorators($this->labelRight());
        $this->soc_perr_tutoring->setDescription('Motivation & Reinforcement');
        $this->soc_perr_tutoring->setAttrib('acc_menu', '1');
        $this->soc_perr_tutoring->setRequired(false);
        $this->soc_perr_tutoring->setAllowEmpty(false);
        $this->soc_perr_tutoring->addOnChange('otherHelper(this.value, \'*' . $this->soc_perr_tutoring->getDescription() . ' - ' .$this->soc_perr_tutoring->getLabel().'\')');


        $this->mot_nonverbal = new App_Form_Element_Select('mot_nonverbal',array('label'=>"Nonverbal"));
        $this->mot_nonverbal->setMultiOptions($this->getSubjects());
        $this->mot_nonverbal->setDecorators($this->labelRight());
        $this->mot_nonverbal->setDescription('Motivation & Reinforcement');
        $this->mot_nonverbal->setAttrib('acc_menu', '1');
        $this->mot_nonverbal->setRequired(false);
        $this->mot_nonverbal->setAllowEmpty(false);
        $this->mot_nonverbal->addOnChange('otherHelper(this.value, \'*' . $this->mot_nonverbal->getDescription() . ' - ' .$this->mot_nonverbal->getLabel().'\')');


        $this->mot_positive_reinforcement = new App_Form_Element_Select('mot_positive_reinforcement',array('label'=>"Positive reinforcement"));
        $this->mot_positive_reinforcement->setMultiOptions($this->getSubjects());
        $this->mot_positive_reinforcement->setDecorators($this->labelRight());
        $this->mot_positive_reinforcement->setDescription('Motivation & Reinforcement');
        $this->mot_positive_reinforcement->setAttrib('acc_menu', '1');
        $this->mot_positive_reinforcement->setRequired(false);
        $this->mot_positive_reinforcement->setAllowEmpty(false);
        $this->mot_positive_reinforcement->addOnChange('otherHelper(this.value, \'*' . $this->mot_positive_reinforcement->getDescription() . ' - ' .$this->mot_positive_reinforcement->getLabel().'\')');


        $this->mot_concrete_reinforcement = new App_Form_Element_Select('mot_concrete_reinforcement',array('label'=>"Concrete reinforcement"));
        $this->mot_concrete_reinforcement->setMultiOptions($this->getSubjects());
        $this->mot_concrete_reinforcement->setDecorators($this->labelRight());
        $this->mot_concrete_reinforcement->setDescription('Motivation & Reinforcement');
        $this->mot_concrete_reinforcement->setAttrib('acc_menu', '1');
        $this->mot_concrete_reinforcement->setRequired(false);
        $this->mot_concrete_reinforcement->setAllowEmpty(false);
        $this->mot_concrete_reinforcement->addOnChange('otherHelper(this.value, \'*' . $this->mot_concrete_reinforcement->getDescription() . ' - ' .$this->mot_concrete_reinforcement->getLabel().'\')');


        $this->mot_offer_choice = new App_Form_Element_Select('mot_offer_choice',array('label'=>"Offer choice"));
        $this->mot_offer_choice->setMultiOptions($this->getSubjects());
        $this->mot_offer_choice->setDecorators($this->labelRight());
        $this->mot_offer_choice->setDescription('Motivation & Reinforcement');
        $this->mot_offer_choice->setAttrib('acc_menu', '1');
        $this->mot_offer_choice->setRequired(false);
        $this->mot_offer_choice->setAllowEmpty(false);
        $this->mot_offer_choice->addOnChange('otherHelper(this.value, \'*' . $this->mot_offer_choice->getDescription() . ' - ' .$this->mot_offer_choice->getLabel().'\')');


        $this->mot_use_strengths_often = new App_Form_Element_Select('mot_use_strengths_often',array('label'=>"Use strengths/Interests often"));
        $this->mot_use_strengths_often->setMultiOptions($this->getSubjects());
        $this->mot_use_strengths_often->setDecorators($this->labelRight());
        $this->mot_use_strengths_often->setDescription('Motivation & Reinforcement');
        $this->mot_use_strengths_often->setAttrib('acc_menu', '1');
        $this->mot_use_strengths_often->setRequired(false);
        $this->mot_use_strengths_often->setAllowEmpty(false);
        $this->mot_use_strengths_often->addOnChange('otherHelper(this.value, \'*' . $this->mot_use_strengths_often->getDescription() . ' - ' .$this->mot_use_strengths_often->getLabel().'\')');


        $this->mot_allow_movement = new App_Form_Element_Select('mot_allow_movement',array('label'=>"Allow in-class movement"));
        $this->mot_allow_movement->setMultiOptions($this->getSubjects());
        $this->mot_allow_movement->setDecorators($this->labelRight());
        $this->mot_allow_movement->setDescription('Motivation & Reinforcement');
        $this->mot_allow_movement->setAttrib('acc_menu', '1');
        $this->mot_allow_movement->setRequired(false);
        $this->mot_allow_movement->setAllowEmpty(false);
        $this->mot_allow_movement->addOnChange('otherHelper(this.value, \'*' . $this->mot_allow_movement->getDescription() . ' - ' .$this->mot_allow_movement->getLabel().'\')');


        $this->mot_increase_rewards = new App_Form_Element_Select('mot_increase_rewards',array('label'=>"Increase immediacy of rewards"));
        $this->mot_increase_rewards->setMultiOptions($this->getSubjects());
        $this->mot_increase_rewards->setDecorators($this->labelRight());
        $this->mot_increase_rewards->setDescription('Motivation & Reinforcement');
        $this->mot_increase_rewards->setAttrib('acc_menu', '1');
        $this->mot_increase_rewards->setRequired(false);
        $this->mot_increase_rewards->setAllowEmpty(false);
        $this->mot_increase_rewards->addOnChange('otherHelper(this.value, \'*' . $this->mot_increase_rewards->getDescription() . ' - ' .$this->mot_increase_rewards->getLabel().'\')');


        $this->mot_use_contracts = new App_Form_Element_Select('mot_use_contracts',array('label'=>"Use behavioral contracts"));
        $this->mot_use_contracts->setMultiOptions($this->getSubjects());
        $this->mot_use_contracts->setDecorators($this->labelRight());
        $this->mot_use_contracts->setDescription('Motivation & Reinforcement');
        $this->mot_use_contracts->setAttrib('acc_menu', '1');
        $this->mot_use_contracts->setRequired(false);
        $this->mot_use_contracts->setAllowEmpty(false);
        $this->mot_use_contracts->addOnChange('otherHelper(this.value, \'*' . $this->mot_use_contracts->getDescription() . ' - ' .$this->mot_use_contracts->getLabel().'\')');


        $this->mot_other = new App_Form_Element_Select('mot_other',array('label'=>"Other"));
        $this->mot_other->setMultiOptions($this->getSubjects());
        $this->mot_other->setDecorators($this->labelRight());
        $this->mot_other->setDescription('Motivation & Reinforcement');
        $this->mot_other->setAttrib('acc_menu', '1');
        $this->mot_other->setRequired(false);
        $this->mot_other->setAllowEmpty(false);
        $this->mot_other->addOnChange('otherHelper(this.value, \'*' . $this->mot_other->getDescription() . ' - ' .$this->mot_other->getLabel().'\')');



        $this->soc_peer_advocacy = new App_Form_Element_Select('soc_peer_advocacy',array('label'=>"Peer advocacy"));
        $this->soc_peer_advocacy->setMultiOptions($this->getSubjects());
        $this->soc_peer_advocacy->setDecorators($this->labelRight());
        $this->soc_peer_advocacy->setDescription('Social Interaction Support');
        $this->soc_peer_advocacy->setAttrib('acc_menu', '1');
        $this->soc_peer_advocacy->setRequired(false);
        $this->soc_peer_advocacy->setAllowEmpty(false);
        $this->soc_peer_advocacy->addOnChange('otherHelper(this.value, \'*' . $this->soc_peer_advocacy->getDescription() . ' - ' .$this->soc_peer_advocacy->getLabel().'\')');


        $this->soc_perr_tutoring = new App_Form_Element_Select('soc_perr_tutoring',array('label'=>"Peer tutoring"));
        $this->soc_perr_tutoring->setMultiOptions($this->getSubjects());
        $this->soc_perr_tutoring->setDecorators($this->labelRight());
        $this->soc_perr_tutoring->setDescription('Social Interaction Support');
        $this->soc_perr_tutoring->setAttrib('acc_menu', '1');
        $this->soc_perr_tutoring->setRequired(false);
        $this->soc_perr_tutoring->setAllowEmpty(false);
        $this->soc_perr_tutoring->addOnChange('otherHelper(this.value, \'*' . $this->soc_perr_tutoring->getDescription() . ' - ' .$this->soc_perr_tutoring->getLabel().'\')');


        $this->soc_structure_activities = new App_Form_Element_Select('soc_structure_activities',array('label'=>"Structure activities to create or<BR>- discourage opportunities of social interaction"));
        $this->soc_structure_activities->setMultiOptions($this->getSubjects());
        $this->soc_structure_activities->setDecorators($this->labelRight());
        $this->soc_structure_activities->setDescription('Social Interaction Support');
        $this->soc_structure_activities->setAttrib('acc_menu', '1');
        $this->soc_structure_activities->setRequired(false);
        $this->soc_structure_activities->setAllowEmpty(false);
        $this->soc_structure_activities->addOnChange('otherHelper(this.value, \'*' . $this->soc_structure_activities->getDescription() . ' - ' .$this->soc_structure_activities->getLabel().'\')');


        $this->soc_social_process = new App_Form_Element_Select('soc_social_process',array('label'=>"Focus on social process rather than activity"));
        $this->soc_social_process->setMultiOptions($this->getSubjects());
        $this->soc_social_process->setDecorators($this->labelRight());
        $this->soc_social_process->setDescription('Social Interaction Support');
        $this->soc_social_process->setAttrib('acc_menu', '1');
        $this->soc_social_process->setRequired(false);
        $this->soc_social_process->setAllowEmpty(false);
        $this->soc_social_process->addOnChange('otherHelper(this.value, \'*' . $this->soc_social_process->getDescription() . ' - ' .$this->soc_social_process->getLabel().'\')');


        $this->soc_shared_experience = new App_Form_Element_Select('soc_shared_experience',array('label'=>"Structure shared experience in school"));
        $this->soc_shared_experience->setMultiOptions($this->getSubjects());
        $this->soc_shared_experience->setDecorators($this->labelRight());
        $this->soc_shared_experience->setDescription('Social Interaction Support');
        $this->soc_shared_experience->setAttrib('acc_menu', '1');
        $this->soc_shared_experience->setRequired(false);
        $this->soc_shared_experience->setAllowEmpty(false);
        $this->soc_shared_experience->addOnChange('otherHelper(this.value, \'*' . $this->soc_shared_experience->getDescription() . ' - ' .$this->soc_shared_experience->getLabel().'\')');


        $this->soc_coop_learning_groups = new App_Form_Element_Select('soc_coop_learning_groups',array('label'=>"Cooperative learning groups"));
        $this->soc_coop_learning_groups->setMultiOptions($this->getSubjects());
        $this->soc_coop_learning_groups->setDecorators($this->labelRight());
        $this->soc_coop_learning_groups->setDescription('Social Interaction Support');
        $this->soc_coop_learning_groups->setAttrib('acc_menu', '1');
        $this->soc_coop_learning_groups->setRequired(false);
        $this->soc_coop_learning_groups->setAllowEmpty(false);
        $this->soc_coop_learning_groups->addOnChange('otherHelper(this.value, \'*' . $this->soc_coop_learning_groups->getDescription() . ' - ' .$this->soc_coop_learning_groups->getLabel().'\')');


        $this->soc_multiple_peers = new App_Form_Element_Select('soc_multiple_peers',array('label'=>"Use multiple/rotating peers"));
        $this->soc_multiple_peers->setMultiOptions($this->getSubjects());
        $this->soc_multiple_peers->setDecorators($this->labelRight());
        $this->soc_multiple_peers->setDescription('Social Interaction Support');
        $this->soc_multiple_peers->setAttrib('acc_menu', '1');
        $this->soc_multiple_peers->setRequired(false);
        $this->soc_multiple_peers->setAllowEmpty(false);
        $this->soc_multiple_peers->addOnChange('otherHelper(this.value, \'*' . $this->soc_multiple_peers->getDescription() . ' - ' .$this->soc_multiple_peers->getLabel().'\')');


        $this->soc_teach_friendship = new App_Form_Element_Select('soc_teach_friendship',array('label'=>"Teach friendship<BR>- skills/sharing/negotiations"));
        $this->soc_teach_friendship->setMultiOptions($this->getSubjects());
        $this->soc_teach_friendship->setDecorators($this->labelRight());
        $this->soc_teach_friendship->setDescription('Social Interaction Support');
        $this->soc_teach_friendship->setAttrib('acc_menu', '1');
        $this->soc_teach_friendship->setRequired(false);
        $this->soc_teach_friendship->setAllowEmpty(false);
        $this->soc_teach_friendship->addOnChange('otherHelper(this.value, \'*' . $this->soc_teach_friendship->getDescription() . ' - ' .$this->soc_teach_friendship->getLabel().'\')');


        $this->soc_teach_social_com = new App_Form_Element_Select('soc_teach_social_com',array('label'=>"Teach social communications skills<BR>- Greetings<BR>- Negotiations<BR>- Turn taking <BR>- Sharing"));
        $this->soc_teach_social_com->setMultiOptions($this->getSubjects());
        $this->soc_teach_social_com->setDecorators($this->labelRight());
        $this->soc_teach_social_com->setDescription('Social Interaction Support');
        $this->soc_teach_social_com->setAttrib('acc_menu', '1');
        $this->soc_teach_social_com->setRequired(false);
        $this->soc_teach_social_com->setAllowEmpty(false);
        $this->soc_teach_social_com->addOnChange('otherHelper(this.value, \'*' . $this->soc_teach_social_com->getDescription() . ' - ' .$this->soc_teach_social_com->getLabel().'\')');


        $this->soc_other = new App_Form_Element_Select('soc_other',array('label'=>"Other"));
        $this->soc_other->setMultiOptions($this->getSubjects());
        $this->soc_other->setDecorators($this->labelRight());
        $this->soc_other->setDescription('Social Interaction Support');
        $this->soc_other->setAttrib('acc_menu', '1');
        $this->soc_other->setRequired(false);
        $this->soc_other->setAllowEmpty(false);
        $this->soc_other->addOnChange('otherHelper(this.value, \'*' . $this->soc_other->getDescription() . ' - ' .$this->soc_other->getLabel().'\')');



        $this->self_man_pos_reinforcement = new App_Form_Element_Select('self_man_pos_reinforcement',array('label'=>"Use positive reinforcement"));
        $this->self_man_pos_reinforcement->setMultiOptions($this->getSubjects());
        $this->self_man_pos_reinforcement->setDecorators($this->labelRight());
        $this->self_man_pos_reinforcement->setDescription('Self Management/Follow-through');
        $this->self_man_pos_reinforcement->setAttrib('acc_menu', '1');
        $this->self_man_pos_reinforcement->setRequired(false);
        $this->self_man_pos_reinforcement->setAllowEmpty(false);
        $this->self_man_pos_reinforcement->addOnChange('otherHelper(this.value, \'*' . $this->self_man_pos_reinforcement->getDescription() . ' - ' .$this->self_man_pos_reinforcement->getLabel().'\')');


        $this->self_man_con_reinforcement = new App_Form_Element_Select('self_man_con_reinforcement',array('label'=>"Use concrete reinforcement"));
        $this->self_man_con_reinforcement->setMultiOptions($this->getSubjects());
        $this->self_man_con_reinforcement->setDecorators($this->labelRight());
        $this->self_man_con_reinforcement->setDescription('Self Management/Follow-through');
        $this->self_man_con_reinforcement->setAttrib('acc_menu', '1');
        $this->self_man_con_reinforcement->setRequired(false);
        $this->self_man_con_reinforcement->setAllowEmpty(false);
        $this->self_man_con_reinforcement->addOnChange('otherHelper(this.value, \'*' . $this->self_man_con_reinforcement->getDescription() . ' - ' .$this->self_man_con_reinforcement->getLabel().'\')');


        $this->self_man_understand_review = new App_Form_Element_Select('self_man_understand_review',array('label'=>"Check often for understanding review<BR>- copying, if timed"));
        $this->self_man_understand_review->setMultiOptions($this->getSubjects());
        $this->self_man_understand_review->setDecorators($this->labelRight());
        $this->self_man_understand_review->setDescription('Self Management/Follow-through');
        $this->self_man_understand_review->setAttrib('acc_menu', '1');
        $this->self_man_understand_review->setRequired(false);
        $this->self_man_understand_review->setAllowEmpty(false);
        $this->self_man_understand_review->addOnChange('otherHelper(this.value, \'*' . $this->self_man_understand_review->getDescription() . ' - ' .$this->self_man_understand_review->getLabel().'\')');


        $this->self_man_peer_tutoring = new App_Form_Element_Select('self_man_peer_tutoring',array('label'=>"Peer tutoring"));
        $this->self_man_peer_tutoring->setMultiOptions($this->getSubjects());
        $this->self_man_peer_tutoring->setDecorators($this->labelRight());
        $this->self_man_peer_tutoring->setDescription('Self Management/Follow-through');
        $this->self_man_peer_tutoring->setAttrib('acc_menu', '1');
        $this->self_man_peer_tutoring->setRequired(false);
        $this->self_man_peer_tutoring->setAllowEmpty(false);
        $this->self_man_peer_tutoring->addOnChange('otherHelper(this.value, \'*' . $this->self_man_peer_tutoring->getDescription() . ' - ' .$this->self_man_peer_tutoring->getLabel().'\')');


        $this->self_man_req_par_reinforcement = new App_Form_Element_Select('self_man_req_par_reinforcement',array('label'=>"Request parent reinforcement"));
        $this->self_man_req_par_reinforcement->setMultiOptions($this->getSubjects());
        $this->self_man_req_par_reinforcement->setDecorators($this->labelRight());
        $this->self_man_req_par_reinforcement->setDescription('Self Management/Follow-through');
        $this->self_man_req_par_reinforcement->setAttrib('acc_menu', '1');
        $this->self_man_req_par_reinforcement->setRequired(false);
        $this->self_man_req_par_reinforcement->setAllowEmpty(false);
        $this->self_man_req_par_reinforcement->addOnChange('otherHelper(this.value, \'*' . $this->self_man_req_par_reinforcement->getDescription() . ' - ' .$this->self_man_req_par_reinforcement->getLabel().'\')');


        $this->self_man_repeat_directions = new App_Form_Element_Select('self_man_repeat_directions',array('label'=>"Have student repeat directions"));
        $this->self_man_repeat_directions->setMultiOptions($this->getSubjects());
        $this->self_man_repeat_directions->setDecorators($this->labelRight());
        $this->self_man_repeat_directions->setDescription('Self Management/Follow-through');
        $this->self_man_repeat_directions->setAttrib('acc_menu', '1');
        $this->self_man_repeat_directions->setRequired(false);
        $this->self_man_repeat_directions->setAllowEmpty(false);
        $this->self_man_repeat_directions->addOnChange('otherHelper(this.value, \'*' . $this->self_man_repeat_directions->getDescription() . ' - ' .$this->self_man_repeat_directions->getLabel().'\')');


        $this->self_man_voc_files = new App_Form_Element_Select('self_man_voc_files',array('label'=>"Make/Use vocabulary files"));
        $this->self_man_voc_files->setMultiOptions($this->getSubjects());
        $this->self_man_voc_files->setDecorators($this->labelRight());
        $this->self_man_voc_files->setDescription('Self Management/Follow-through');
        $this->self_man_voc_files->setAttrib('acc_menu', '1');
        $this->self_man_voc_files->setRequired(false);
        $this->self_man_voc_files->setAllowEmpty(false);
        $this->self_man_voc_files->addOnChange('otherHelper(this.value, \'*' . $this->self_man_voc_files->getDescription() . ' - ' .$this->self_man_voc_files->getLabel().'\')');


        $this->self_man_teach_study_skills = new App_Form_Element_Select('self_man_teach_study_skills',array('label'=>"Teach study skills"));
        $this->self_man_teach_study_skills->setMultiOptions($this->getSubjects());
        $this->self_man_teach_study_skills->setDecorators($this->labelRight());
        $this->self_man_teach_study_skills->setDescription('Self Management/Follow-through');
        $this->self_man_teach_study_skills->setAttrib('acc_menu', '1');
        $this->self_man_teach_study_skills->setRequired(false);
        $this->self_man_teach_study_skills->setAllowEmpty(false);
        $this->self_man_teach_study_skills->addOnChange('otherHelper(this.value, \'*' . $this->self_man_teach_study_skills->getDescription() . ' - ' .$this->self_man_teach_study_skills->getLabel().'\')');


        $this->self_man_study_sheets = new App_Form_Element_Select('self_man_study_sheets',array('label'=>"Use study sheets to organize material"));
        $this->self_man_study_sheets->setMultiOptions($this->getSubjects());
        $this->self_man_study_sheets->setDecorators($this->labelRight());
        $this->self_man_study_sheets->setDescription('Self Management/Follow-through');
        $this->self_man_study_sheets->setAttrib('acc_menu', '1');
        $this->self_man_study_sheets->setRequired(false);
        $this->self_man_study_sheets->setAllowEmpty(false);
        $this->self_man_study_sheets->addOnChange('otherHelper(this.value, \'*' . $this->self_man_study_sheets->getDescription() . ' - ' .$this->self_man_study_sheets->getLabel().'\')');


        $this->self_man_long_term_assign = new App_Form_Element_Select('self_man_long_term_assign',array('label'=>"Long term assignment time lines"));
        $this->self_man_long_term_assign->setMultiOptions($this->getSubjects());
        $this->self_man_long_term_assign->setDecorators($this->labelRight());
        $this->self_man_long_term_assign->setDescription('Self Management/Follow-through');
        $this->self_man_long_term_assign->setAttrib('acc_menu', '1');
        $this->self_man_long_term_assign->setRequired(false);
        $this->self_man_long_term_assign->setAllowEmpty(false);
        $this->self_man_long_term_assign->addOnChange('otherHelper(this.value, \'*' . $this->self_man_long_term_assign->getDescription() . ' - ' .$this->self_man_long_term_assign->getLabel().'\')');


        $this->self_man_repeated_review = new App_Form_Element_Select('self_man_repeated_review',array('label'=>"Repeated review/drill"));
        $this->self_man_repeated_review->setMultiOptions($this->getSubjects());
        $this->self_man_repeated_review->setDecorators($this->labelRight());
        $this->self_man_repeated_review->setDescription('Self Management/Follow-through');
        $this->self_man_repeated_review->setAttrib('acc_menu', '1');
        $this->self_man_repeated_review->setRequired(false);
        $this->self_man_repeated_review->setAllowEmpty(false);
        $this->self_man_repeated_review->addOnChange('otherHelper(this.value, \'*' . $this->self_man_repeated_review->getDescription() . ' - ' .$this->self_man_repeated_review->getLabel().'\')');


        $this->self_man_behavior_manage = new App_Form_Element_Select('self_man_behavior_manage',array('label'=>"Behavior management system"));
        $this->self_man_behavior_manage->setMultiOptions($this->getSubjects());
        $this->self_man_behavior_manage->setDecorators($this->labelRight());
        $this->self_man_behavior_manage->setDescription('Self Management/Follow-through');
        $this->self_man_behavior_manage->setAttrib('acc_menu', '1');
        $this->self_man_behavior_manage->setRequired(false);
        $this->self_man_behavior_manage->setAllowEmpty(false);
        $this->self_man_behavior_manage->addOnChange('otherHelper(this.value, \'*' . $this->self_man_behavior_manage->getDescription() . ' - ' .$this->self_man_behavior_manage->getLabel().'\')');


        $this->self_man_daily_schedule = new App_Form_Element_Select('self_man_daily_schedule',array('label'=>"Visual daily schedule"));
        $this->self_man_daily_schedule->setMultiOptions($this->getSubjects());
        $this->self_man_daily_schedule->setDecorators($this->labelRight());
        $this->self_man_daily_schedule->setDescription('Self Management/Follow-through');
        $this->self_man_daily_schedule->setAttrib('acc_menu', '1');
        $this->self_man_daily_schedule->setRequired(false);
        $this->self_man_daily_schedule->setAllowEmpty(false);
        $this->self_man_daily_schedule->addOnChange('otherHelper(this.value, \'*' . $this->self_man_daily_schedule->getDescription() . ' - ' .$this->self_man_daily_schedule->getLabel().'\')');


        $this->self_man_assignment_book = new App_Form_Element_Select('self_man_assignment_book',array('label'=>"Calendar/Assignment Book"));
        $this->self_man_assignment_book->setMultiOptions($this->getSubjects());
        $this->self_man_assignment_book->setDecorators($this->labelRight());
        $this->self_man_assignment_book->setDescription('Self Management/Follow-through');
        $this->self_man_assignment_book->setAttrib('acc_menu', '1');
        $this->self_man_assignment_book->setRequired(false);
        $this->self_man_assignment_book->setAllowEmpty(false);
        $this->self_man_assignment_book->addOnChange('otherHelper(this.value, \'*' . $this->self_man_assignment_book->getDescription() . ' - ' .$this->self_man_assignment_book->getLabel().'\')');


        $this->self_man_plan_general = new App_Form_Element_Select('self_man_plan_general',array('label'=>"Plan for generalizations"));
        $this->self_man_plan_general->setMultiOptions($this->getSubjects());
        $this->self_man_plan_general->setDecorators($this->labelRight());
        $this->self_man_plan_general->setDescription('Self Management/Follow-through');
        $this->self_man_plan_general->setAttrib('acc_menu', '1');
        $this->self_man_plan_general->setRequired(false);
        $this->self_man_plan_general->setAllowEmpty(false);
        $this->self_man_plan_general->addOnChange('otherHelper(this.value, \'*' . $this->self_man_plan_general->getDescription() . ' - ' .$this->self_man_plan_general->getLabel().'\')');


        $this->self_man_teach_skill_sev = new App_Form_Element_Select('self_man_teach_skill_sev',array('label'=>"Teach skill in several<BR>- settings/environments"));
        $this->self_man_teach_skill_sev->setMultiOptions($this->getSubjects());
        $this->self_man_teach_skill_sev->setDecorators($this->labelRight());
        $this->self_man_teach_skill_sev->setDescription('Self Management/Follow-through');
        $this->self_man_teach_skill_sev->setAttrib('acc_menu', '1');
        $this->self_man_teach_skill_sev->setRequired(false);
        $this->self_man_teach_skill_sev->setAllowEmpty(false);
        $this->self_man_teach_skill_sev->addOnChange('otherHelper(this.value, \'*' . $this->self_man_teach_skill_sev->getDescription() . ' - ' .$this->self_man_teach_skill_sev->getLabel().'\')');


        $this->self_man_redo_assignment = new App_Form_Element_Select('self_man_redo_assignment',array('label'=>"Redo assignment for a better grade"));
        $this->self_man_redo_assignment->setMultiOptions($this->getSubjects());
        $this->self_man_redo_assignment->setDecorators($this->labelRight());
        $this->self_man_redo_assignment->setDescription('Self Management/Follow-through');
        $this->self_man_redo_assignment->setAttrib('acc_menu', '1');
        $this->self_man_redo_assignment->setRequired(false);
        $this->self_man_redo_assignment->setAllowEmpty(false);
        $this->self_man_redo_assignment->addOnChange('otherHelper(this.value, \'*' . $this->self_man_redo_assignment->getDescription() . ' - ' .$this->self_man_redo_assignment->getLabel().'\')');


        $this->self_man_other = new App_Form_Element_Select('self_man_other',array('label'=>"Other"));
        $this->self_man_other->setMultiOptions($this->getSubjects());
        $this->self_man_other->setDecorators($this->labelRight());
        $this->self_man_other->setDescription('Self Management/Follow-through');
        $this->self_man_other->setAttrib('acc_menu', '1');
        $this->self_man_other->setRequired(false);
        $this->self_man_other->setAllowEmpty(false);
        $this->self_man_other->addOnChange('otherHelper(this.value, \'*' . $this->self_man_other->getDescription() . ' - ' .$this->self_man_other->getLabel().'\')');



        $this->testing_extended_time = new App_Form_Element_Select('testing_extended_time',array('label'=>"Provide extended time"));
        $this->testing_extended_time->setMultiOptions($this->getSubjects());
        $this->testing_extended_time->setDecorators($this->labelRight());
        $this->testing_extended_time->setDescription('Testing Accommodations');
        $this->testing_extended_time->setAttrib('acc_menu', '1');
        $this->testing_extended_time->setRequired(false);
        $this->testing_extended_time->setAllowEmpty(false);
        $this->testing_extended_time->addOnChange('otherHelper(this.value, \'*' . $this->testing_extended_time->getDescription() . ' - ' .$this->testing_extended_time->getLabel().'\')');


        $this->testing_oral = new App_Form_Element_Select('testing_oral',array('label'=>"Allowed to answer questions orally"));
        $this->testing_oral->setMultiOptions($this->getSubjects());
        $this->testing_oral->setDecorators($this->labelRight());
        $this->testing_oral->setDescription('Testing Accommodations');
        $this->testing_oral->setAttrib('acc_menu', '1');
        $this->testing_oral->setRequired(false);
        $this->testing_oral->setAllowEmpty(false);
        $this->testing_oral->addOnChange('otherHelper(this.value, \'*' . $this->testing_oral->getDescription() . ' - ' .$this->testing_oral->getLabel().'\')');


        $this->testing_short_ans = new App_Form_Element_Select('testing_short_ans',array('label'=>"Allow for short answer format"));
        $this->testing_short_ans->setMultiOptions($this->getSubjects());
        $this->testing_short_ans->setDecorators($this->labelRight());
        $this->testing_short_ans->setDescription('Testing Accommodations');
        $this->testing_short_ans->setAttrib('acc_menu', '1');
        $this->testing_short_ans->setRequired(false);
        $this->testing_short_ans->setAllowEmpty(false);
        $this->testing_short_ans->addOnChange('otherHelper(this.value, \'*' . $this->testing_short_ans->getDescription() . ' - ' .$this->testing_short_ans->getLabel().'\')');


        $this->testing_taped = new App_Form_Element_Select('testing_taped',array('label'=>"Taped"));
        $this->testing_taped->setMultiOptions($this->getSubjects());
        $this->testing_taped->setDecorators($this->labelRight());
        $this->testing_taped->setDescription('Testing Accommodations');
        $this->testing_taped->setAttrib('acc_menu', '1');
        $this->testing_taped->setRequired(false);
        $this->testing_taped->setAllowEmpty(false);
        $this->testing_taped->addOnChange('otherHelper(this.value, \'*' . $this->testing_taped->getDescription() . ' - ' .$this->testing_taped->getLabel().'\')');


        $this->testing_mult_choice = new App_Form_Element_Select('testing_mult_choice',array('label'=>"Utilize multiple choice"));
        $this->testing_mult_choice->setMultiOptions($this->getSubjects());
        $this->testing_mult_choice->setDecorators($this->labelRight());
        $this->testing_mult_choice->setDescription('Testing Accommodations');
        $this->testing_mult_choice->setAttrib('acc_menu', '1');
        $this->testing_mult_choice->setRequired(false);
        $this->testing_mult_choice->setAllowEmpty(false);
        $this->testing_mult_choice->addOnChange('otherHelper(this.value, \'*' . $this->testing_mult_choice->getDescription() . ' - ' .$this->testing_mult_choice->getLabel().'\')');


        $this->testing_read_test = new App_Form_Element_Select('testing_read_test',array('label'=>"Read test to student"));
        $this->testing_read_test->setMultiOptions($this->getSubjects());
        $this->testing_read_test->setDecorators($this->labelRight());
        $this->testing_read_test->setDescription('Testing Accommodations');
        $this->testing_read_test->setAttrib('acc_menu', '1');
        $this->testing_read_test->setRequired(false);
        $this->testing_read_test->setAllowEmpty(false);
        $this->testing_read_test->addOnChange('otherHelper(this.value, \'*' . $this->testing_read_test->getDescription() . ' - ' .$this->testing_read_test->getLabel().'\')');


        $this->testing_mod_format = new App_Form_Element_Select('testing_mod_format',array('label'=>"Modify format"));
        $this->testing_mod_format->setMultiOptions($this->getSubjects());
        $this->testing_mod_format->setDecorators($this->labelRight());
        $this->testing_mod_format->setDescription('Testing Accommodations');
        $this->testing_mod_format->setAttrib('acc_menu', '1');
        $this->testing_mod_format->setRequired(false);
        $this->testing_mod_format->setAllowEmpty(false);
        $this->testing_mod_format->addOnChange('otherHelper(this.value, \'*' . $this->testing_mod_format->getDescription() . ' - ' .$this->testing_mod_format->getLabel().'\')');


        $this->testing_shorten_length = new App_Form_Element_Select('testing_shorten_length',array('label'=>"Shorten length"));
        $this->testing_shorten_length->setMultiOptions($this->getSubjects());
        $this->testing_shorten_length->setDecorators($this->labelRight());
        $this->testing_shorten_length->setDescription('Testing Accommodations');
        $this->testing_shorten_length->setAttrib('acc_menu', '1');
        $this->testing_shorten_length->setRequired(false);
        $this->testing_shorten_length->setAllowEmpty(false);
        $this->testing_shorten_length->addOnChange('otherHelper(this.value, \'*' . $this->testing_shorten_length->getDescription() . ' - ' .$this->testing_shorten_length->getLabel().'\')');


        $this->testing_sign_directions = new App_Form_Element_Select('testing_sign_directions',array('label'=>"Sign test directions"));
        $this->testing_sign_directions->setMultiOptions($this->getSubjects());
        $this->testing_sign_directions->setDecorators($this->labelRight());
        $this->testing_sign_directions->setDescription('Testing Accommodations');
        $this->testing_sign_directions->setAttrib('acc_menu', '1');
        $this->testing_sign_directions->setRequired(false);
        $this->testing_sign_directions->setAllowEmpty(false);
        $this->testing_sign_directions->addOnChange('otherHelper(this.value, \'*' . $this->testing_sign_directions->getDescription() . ' - ' .$this->testing_sign_directions->getLabel().'\')');


        $this->testing_prev_lang = new App_Form_Element_Select('testing_prev_lang',array('label'=>"Preview language to test questions"));
        $this->testing_prev_lang->setMultiOptions($this->getSubjects());
        $this->testing_prev_lang->setDecorators($this->labelRight());
        $this->testing_prev_lang->setDescription('Testing Accommodations');
        $this->testing_prev_lang->setAttrib('acc_menu', '1');
        $this->testing_prev_lang->setRequired(false);
        $this->testing_prev_lang->setAllowEmpty(false);
        $this->testing_prev_lang->addOnChange('otherHelper(this.value, \'*' . $this->testing_prev_lang->getDescription() . ' - ' .$this->testing_prev_lang->getLabel().'\')');


        $this->testing_sign_test = new App_Form_Element_Select('testing_sign_test',array('label'=>"Sign test to students; student signs answers"));
        $this->testing_sign_test->setMultiOptions($this->getSubjects());
        $this->testing_sign_test->setDecorators($this->labelRight());
        $this->testing_sign_test->setDescription('Testing Accommodations');
        $this->testing_sign_test->setAttrib('acc_menu', '1');
        $this->testing_sign_test->setRequired(false);
        $this->testing_sign_test->setAllowEmpty(false);
        $this->testing_sign_test->addOnChange('otherHelper(this.value, \'*' . $this->testing_sign_test->getDescription() . ' - ' .$this->testing_sign_test->getLabel().'\')');


        $this->testing_test_admin = new App_Form_Element_Select('testing_test_admin',array('label'=>"Test administered by special services personnel"));
        $this->testing_test_admin->setMultiOptions($this->getSubjects());
        $this->testing_test_admin->setDecorators($this->labelRight());
        $this->testing_test_admin->setDescription('Testing Accommodations');
        $this->testing_test_admin->setAttrib('acc_menu', '1');
        $this->testing_test_admin->setRequired(false);
        $this->testing_test_admin->setAllowEmpty(false);
        $this->testing_test_admin->addOnChange('otherHelper(this.value, \'*' . $this->testing_test_admin->getDescription() . ' - ' .$this->testing_test_admin->getLabel().'\')');


        $this->testing_check_understand = new App_Form_Element_Select('testing_check_understand',array('label'=>"Check for understanding"));
        $this->testing_check_understand->setMultiOptions($this->getSubjects());
        $this->testing_check_understand->setDecorators($this->labelRight());
        $this->testing_check_understand->setDescription('Testing Accommodations');
        $this->testing_check_understand->setAttrib('acc_menu', '1');
        $this->testing_check_understand->setRequired(false);
        $this->testing_check_understand->setAllowEmpty(false);
        $this->testing_check_understand->addOnChange('otherHelper(this.value, \'*' . $this->testing_check_understand->getDescription() . ' - ' .$this->testing_check_understand->getLabel().'\')');


        $this->testing_provide_visual = new App_Form_Element_Select('testing_provide_visual',array('label'=>"Provide visual information/pictures"));
        $this->testing_provide_visual->setMultiOptions($this->getSubjects());
        $this->testing_provide_visual->setDecorators($this->labelRight());
        $this->testing_provide_visual->setDescription('Testing Accommodations');
        $this->testing_provide_visual->setAttrib('acc_menu', '1');
        $this->testing_provide_visual->setRequired(false);
        $this->testing_provide_visual->setAllowEmpty(false);
        $this->testing_provide_visual->addOnChange('otherHelper(this.value, \'*' . $this->testing_provide_visual->getDescription() . ' - ' .$this->testing_provide_visual->getLabel().'\')');


        $this->testing_para_test = new App_Form_Element_Select('testing_para_test',array('label'=>"Paraphrase test items"));
        $this->testing_para_test->setMultiOptions($this->getSubjects());
        $this->testing_para_test->setDecorators($this->labelRight());
        $this->testing_para_test->setDescription('Testing Accommodations');
        $this->testing_para_test->setAttrib('acc_menu', '1');
        $this->testing_para_test->setRequired(false);
        $this->testing_para_test->setAllowEmpty(false);
        $this->testing_para_test->addOnChange('otherHelper(this.value, \'*' . $this->testing_para_test->getDescription() . ' - ' .$this->testing_para_test->getLabel().'\')');


        $this->testing_utilize_writing_sys = new App_Form_Element_Select('testing_utilize_writing_sys',array('label'=>"Utilize specialized writing systems/devices"));
        $this->testing_utilize_writing_sys->setMultiOptions($this->getSubjects());
        $this->testing_utilize_writing_sys->setDecorators($this->labelRight());
        $this->testing_utilize_writing_sys->setDescription('Testing Accommodations');
        $this->testing_utilize_writing_sys->setAttrib('acc_menu', '1');
        $this->testing_utilize_writing_sys->setRequired(false);
        $this->testing_utilize_writing_sys->setAllowEmpty(false);
        $this->testing_utilize_writing_sys->addOnChange('otherHelper(this.value, \'*' . $this->testing_utilize_writing_sys->getDescription() . ' - ' .$this->testing_utilize_writing_sys->getLabel().'\')');


        $this->testing_color_coded = new App_Form_Element_Select('testing_color_coded',array('label'=>"Color-coded test"));
        $this->testing_color_coded->setMultiOptions($this->getSubjects());
        $this->testing_color_coded->setDecorators($this->labelRight());
        $this->testing_color_coded->setDescription('Testing Accommodations');
        $this->testing_color_coded->setAttrib('acc_menu', '1');
        $this->testing_color_coded->setRequired(false);
        $this->testing_color_coded->setAllowEmpty(false);
        $this->testing_color_coded->addOnChange('otherHelper(this.value, \'*' . $this->testing_color_coded->getDescription() . ' - ' .$this->testing_color_coded->getLabel().'\')');


        $this->testing_retest_options = new App_Form_Element_Select('testing_retest_options',array('label'=>"Retest after student demonstrates review of material"));
        $this->testing_retest_options->setMultiOptions($this->getSubjects());
        $this->testing_retest_options->setDecorators($this->labelRight());
        $this->testing_retest_options->setDescription('Testing Accommodations');
        $this->testing_retest_options->setAttrib('acc_menu', '1');
        $this->testing_retest_options->setRequired(false);
        $this->testing_retest_options->setAllowEmpty(false);
        $this->testing_retest_options->addOnChange('otherHelper(this.value, \'*' . $this->testing_retest_options->getDescription() . ' - ' .$this->testing_retest_options->getLabel().'\')');


        $this->testing_flash_cards = new App_Form_Element_Select('testing_flash_cards',array('label'=>"Flash cards with key points"));
        $this->testing_flash_cards->setMultiOptions($this->getSubjects());
        $this->testing_flash_cards->setDecorators($this->labelRight());
        $this->testing_flash_cards->setDescription('Testing Accommodations');
        $this->testing_flash_cards->setAttrib('acc_menu', '1');
        $this->testing_flash_cards->setRequired(false);
        $this->testing_flash_cards->setAllowEmpty(false);
        $this->testing_flash_cards->addOnChange('otherHelper(this.value, \'*' . $this->testing_flash_cards->getDescription() . ' - ' .$this->testing_flash_cards->getLabel().'\')');


        $this->testing_provide_study = new App_Form_Element_Select('testing_provide_study',array('label'=>"Provide study guides 2 to 3 days in advance"));
        $this->testing_provide_study->setMultiOptions($this->getSubjects());
        $this->testing_provide_study->setDecorators($this->labelRight());
        $this->testing_provide_study->setDescription('Testing Accommodations');
        $this->testing_provide_study->setAttrib('acc_menu', '1');
        $this->testing_provide_study->setRequired(false);
        $this->testing_provide_study->setAllowEmpty(false);
        $this->testing_provide_study->addOnChange('otherHelper(this.value, \'*' . $this->testing_provide_study->getDescription() . ' - ' .$this->testing_provide_study->getLabel().'\')');


        $this->testing_word_bank = new App_Form_Element_Select('testing_word_bank',array('label'=>"Word bank for short answer or fill in the blank questions"));
        $this->testing_word_bank->setMultiOptions($this->getSubjects());
        $this->testing_word_bank->setDecorators($this->labelRight());
        $this->testing_word_bank->setDescription('Testing Accommodations');
        $this->testing_word_bank->setAttrib('acc_menu', '1');
        $this->testing_word_bank->setRequired(false);
        $this->testing_word_bank->setAllowEmpty(false);
        $this->testing_word_bank->addOnChange('otherHelper(this.value, \'*' . $this->testing_word_bank->getDescription() . ' - ' .$this->testing_word_bank->getLabel().'\')');


        $this->testing_circle_items = new App_Form_Element_Select('testing_circle_items',array('label'=>"Circle # of items for which the student needs assistance or completed with help"));
        $this->testing_circle_items->setMultiOptions($this->getSubjects());
        $this->testing_circle_items->setDecorators($this->labelRight());
        $this->testing_circle_items->setDescription('Testing Accommodations');
        $this->testing_circle_items->setAttrib('acc_menu', '1');
        $this->testing_circle_items->setRequired(false);
        $this->testing_circle_items->setAllowEmpty(false);
        $this->testing_circle_items->addOnChange('otherHelper(this.value, \'*' . $this->testing_circle_items->getDescription() . ' - ' .$this->testing_circle_items->getLabel().'\')');


        $this->testing_correct_test = new App_Form_Element_Select('testing_correct_test',array('label'=>"Correct test items listing p.# of text"));
        $this->testing_correct_test->setMultiOptions($this->getSubjects());
        $this->testing_correct_test->setDecorators($this->labelRight());
        $this->testing_correct_test->setDescription('Testing Accommodations');
        $this->testing_correct_test->setAttrib('acc_menu', '1');
        $this->testing_correct_test->setRequired(false);
        $this->testing_correct_test->setAllowEmpty(false);
        $this->testing_correct_test->addOnChange('otherHelper(this.value, \'*' . $this->testing_correct_test->getDescription() . ' - ' .$this->testing_correct_test->getLabel().'\')');


        $this->testing_reteach_material = new App_Form_Element_Select('testing_reteach_material',array('label'=>"Re-teach and re-test material"));
        $this->testing_reteach_material->setMultiOptions($this->getSubjects());
        $this->testing_reteach_material->setDecorators($this->labelRight());
        $this->testing_reteach_material->setDescription('Testing Accommodations');
        $this->testing_reteach_material->setAttrib('acc_menu', '1');
        $this->testing_reteach_material->setRequired(false);
        $this->testing_reteach_material->setAllowEmpty(false);
        $this->testing_reteach_material->addOnChange('otherHelper(this.value, \'*' . $this->testing_reteach_material->getDescription() . ' - ' .$this->testing_reteach_material->getLabel().'\')');


        $this->testing_divide_test = new App_Form_Element_Select('testing_divide_test',array('label'=>"Divide test/assignments into smaller sections which are administered separately"));
        $this->testing_divide_test->setMultiOptions($this->getSubjects());
        $this->testing_divide_test->setDecorators($this->labelRight());
        $this->testing_divide_test->setDescription('Testing Accommodations');
        $this->testing_divide_test->setAttrib('acc_menu', '1');
        $this->testing_divide_test->setRequired(false);
        $this->testing_divide_test->setAllowEmpty(false);
        $this->testing_divide_test->addOnChange('otherHelper(this.value, \'*' . $this->testing_divide_test->getDescription() . ' - ' .$this->testing_divide_test->getLabel().'\')');


        $this->testing_use_more_objective = new App_Form_Element_Select('testing_use_more_objective',array('label'=>"Use more objective test items (Less essay)"));
        $this->testing_use_more_objective->setMultiOptions($this->getSubjects());
        $this->testing_use_more_objective->setDecorators($this->labelRight());
        $this->testing_use_more_objective->setDescription('Testing Accommodations');
        $this->testing_use_more_objective->setAttrib('acc_menu', '1');
        $this->testing_use_more_objective->setRequired(false);
        $this->testing_use_more_objective->setAllowEmpty(false);
        $this->testing_use_more_objective->addOnChange('otherHelper(this.value, \'*' . $this->testing_use_more_objective->getDescription() . ' - ' .$this->testing_use_more_objective->getLabel().'\')');


        $this->testing_provide_reminders = new App_Form_Element_Select('testing_provide_reminders',array('label'=>"Provide reminders on the test, i.e. watch math signs"));
        $this->testing_provide_reminders->setMultiOptions($this->getSubjects());
        $this->testing_provide_reminders->setDecorators($this->labelRight());
        $this->testing_provide_reminders->setDescription('Testing Accommodations');
        $this->testing_provide_reminders->setAttrib('acc_menu', '1');
        $this->testing_provide_reminders->setRequired(false);
        $this->testing_provide_reminders->setAllowEmpty(false);
        $this->testing_provide_reminders->addOnChange('otherHelper(this.value, \'*' . $this->testing_provide_reminders->getDescription() . ' - ' .$this->testing_provide_reminders->getLabel().'\')');


        $this->testing_allow_students = new App_Form_Element_Select('testing_allow_students',array('label'=>"Allow the student to refer to notes/text"));
        $this->testing_allow_students->setMultiOptions($this->getSubjects());
        $this->testing_allow_students->setDecorators($this->labelRight());
        $this->testing_allow_students->setDescription('Testing Accommodations');
        $this->testing_allow_students->setAttrib('acc_menu', '1');
        $this->testing_allow_students->setRequired(false);
        $this->testing_allow_students->setAllowEmpty(false);
        $this->testing_allow_students->addOnChange('otherHelper(this.value, \'*' . $this->testing_allow_students->getDescription() . ' - ' .$this->testing_allow_students->getLabel().'\')');


        $this->testing_other = new App_Form_Element_Select('testing_other',array('label'=>"Other"));
        $this->testing_other->setMultiOptions($this->getSubjects());
        $this->testing_other->setDecorators($this->labelRight());
        $this->testing_other->setDescription('Testing Accommodations');
        $this->testing_other->setAttrib('acc_menu', '1');
        $this->testing_other->setRequired(false);
        $this->testing_other->setAllowEmpty(false);
        $this->testing_other->addOnChange('otherHelper(this.value, \'*' . $this->testing_other->getDescription() . ' - ' .$this->testing_other->getLabel().'\')');



        $this->writing_dictate_ideas = new App_Form_Element_Select('writing_dictate_ideas',array('label'=>"Dictate ideas to a peer or an adult"));
        $this->writing_dictate_ideas->setMultiOptions($this->getSubjects());
        $this->writing_dictate_ideas->setDecorators($this->labelRight());
        $this->writing_dictate_ideas->setDescription('Writing Accommodations');
        $this->writing_dictate_ideas->setAttrib('acc_menu', '1');
        $this->writing_dictate_ideas->setRequired(false);
        $this->writing_dictate_ideas->setAllowEmpty(false);
        $this->writing_dictate_ideas->addOnChange('otherHelper(this.value, \'*' . $this->writing_dictate_ideas->getDescription() . ' - ' .$this->writing_dictate_ideas->getLabel().'\')');


        $this->writing_shorten_assignment = new App_Form_Element_Select('writing_shorten_assignment',array('label'=>"Shorten writing assignments"));
        $this->writing_shorten_assignment->setMultiOptions($this->getSubjects());
        $this->writing_shorten_assignment->setDecorators($this->labelRight());
        $this->writing_shorten_assignment->setDescription('Writing Accommodations');
        $this->writing_shorten_assignment->setAttrib('acc_menu', '1');
        $this->writing_shorten_assignment->setRequired(false);
        $this->writing_shorten_assignment->setAllowEmpty(false);
        $this->writing_shorten_assignment->addOnChange('otherHelper(this.value, \'*' . $this->writing_shorten_assignment->getDescription() . ' - ' .$this->writing_shorten_assignment->getLabel().'\')');


        $this->writing_use_tape_recorder = new App_Form_Element_Select('writing_use_tape_recorder',array('label'=>"Use a tape recorder to dictate writing"));
        $this->writing_use_tape_recorder->setMultiOptions($this->getSubjects());
        $this->writing_use_tape_recorder->setDecorators($this->labelRight());
        $this->writing_use_tape_recorder->setDescription('Writing Accommodations');
        $this->writing_use_tape_recorder->setAttrib('acc_menu', '1');
        $this->writing_use_tape_recorder->setRequired(false);
        $this->writing_use_tape_recorder->setAllowEmpty(false);
        $this->writing_use_tape_recorder->addOnChange('otherHelper(this.value, \'*' . $this->writing_use_tape_recorder->getDescription() . ' - ' .$this->writing_use_tape_recorder->getLabel().'\')');


        $this->writing_allow_computer = new App_Form_Element_Select('writing_allow_computer',array('label'=>"Allow for use of computer and special software for outlining, word-processing, spelling, and/or grammar check"));
        $this->writing_allow_computer->setMultiOptions($this->getSubjects());
        $this->writing_allow_computer->setDecorators($this->labelRight());
        $this->writing_allow_computer->setDescription('Writing Accommodations');
        $this->writing_allow_computer->setAttrib('acc_menu', '1');
        $this->writing_allow_computer->setRequired(false);
        $this->writing_allow_computer->setAllowEmpty(false);
        $this->writing_allow_computer->addOnChange('otherHelper(this.value, \'*' . $this->writing_allow_computer->getDescription() . ' - ' .$this->writing_allow_computer->getLabel().'\')');


        $this->writing_visual_rep_ideas = new App_Form_Element_Select('writing_visual_rep_ideas',array('label'=>"Allow visual representation of ideas"));
        $this->writing_visual_rep_ideas->setMultiOptions($this->getSubjects());
        $this->writing_visual_rep_ideas->setDecorators($this->labelRight());
        $this->writing_visual_rep_ideas->setDescription('Writing Accommodations');
        $this->writing_visual_rep_ideas->setAttrib('acc_menu', '1');
        $this->writing_visual_rep_ideas->setRequired(false);
        $this->writing_visual_rep_ideas->setAllowEmpty(false);
        $this->writing_visual_rep_ideas->addOnChange('otherHelper(this.value, \'*' . $this->writing_visual_rep_ideas->getDescription() . ' - ' .$this->writing_visual_rep_ideas->getLabel().'\')');


        $this->writing_provide_structure = new App_Form_Element_Select('writing_provide_structure',array('label'=>"Provide a structure for the writing"));
        $this->writing_provide_structure->setMultiOptions($this->getSubjects());
        $this->writing_provide_structure->setDecorators($this->labelRight());
        $this->writing_provide_structure->setDescription('Writing Accommodations');
        $this->writing_provide_structure->setAttrib('acc_menu', '1');
        $this->writing_provide_structure->setRequired(false);
        $this->writing_provide_structure->setAllowEmpty(false);
        $this->writing_provide_structure->addOnChange('otherHelper(this.value, \'*' . $this->writing_provide_structure->getDescription() . ' - ' .$this->writing_provide_structure->getLabel().'\')');


        $this->writing_allow_flow_chart = new App_Form_Element_Select('writing_allow_flow_chart',array('label'=>"Allow use of flow chart for organizing before the student writes"));
        $this->writing_allow_flow_chart->setMultiOptions($this->getSubjects());
        $this->writing_allow_flow_chart->setDecorators($this->labelRight());
        $this->writing_allow_flow_chart->setDescription('Writing Accommodations');
        $this->writing_allow_flow_chart->setAttrib('acc_menu', '1');
        $this->writing_allow_flow_chart->setRequired(false);
        $this->writing_allow_flow_chart->setAllowEmpty(false);
        $this->writing_allow_flow_chart->addOnChange('otherHelper(this.value, \'*' . $this->writing_allow_flow_chart->getDescription() . ' - ' .$this->writing_allow_flow_chart->getLabel().'\')');


        $this->writing_grade_content = new App_Form_Element_Select('writing_grade_content',array('label'=>"Grade on the basis of content, do not penalize for errors in mechanics, grammar, or spelling"));
        $this->writing_grade_content->setMultiOptions($this->getSubjects());
        $this->writing_grade_content->setDecorators($this->labelRight());
        $this->writing_grade_content->setDescription('Writing Accommodations');
        $this->writing_grade_content->setAttrib('acc_menu', '1');
        $this->writing_grade_content->setRequired(false);
        $this->writing_grade_content->setAllowEmpty(false);
        $this->writing_grade_content->addOnChange('otherHelper(this.value, \'*' . $this->writing_grade_content->getDescription() . ' - ' .$this->writing_grade_content->getLabel().'\')');


        $this->writing_other = new App_Form_Element_Select('writing_other',array('label'=>"Other"));
        $this->writing_other->setMultiOptions($this->getSubjects());
        $this->writing_other->setDecorators($this->labelRight());
        $this->writing_other->setDescription('Writing Accommodations');
        $this->writing_other->setAttrib('acc_menu', '1');
        $this->writing_other->setRequired(false);
        $this->writing_other->setAllowEmpty(false);
        $this->writing_other->addOnChange('otherHelper(this.value, \'*' . $this->writing_other->getDescription() . ' - ' .$this->writing_other->getLabel().'\')');



        $this->grade_pass_fail = new App_Form_Element_Select('grade_pass_fail',array('label'=>"Pass/fail"));
        $this->grade_pass_fail->setMultiOptions($this->getSubjects());
        $this->grade_pass_fail->setDecorators($this->labelRight());
        $this->grade_pass_fail->setDescription('Grading / Reporting Progress');
        $this->grade_pass_fail->setAttrib('acc_menu', '1');
        $this->grade_pass_fail->setRequired(false);
        $this->grade_pass_fail->setAllowEmpty(false);
        $this->grade_pass_fail->addOnChange('otherHelper(this.value, \'*' . $this->grade_pass_fail->getDescription() . ' - ' .$this->grade_pass_fail->getLabel().'\')');


        $this->grade_attendance = new App_Form_Element_Select('grade_attendance',array('label'=>"Attendance pass/fail"));
        $this->grade_attendance->setMultiOptions($this->getSubjects());
        $this->grade_attendance->setDecorators($this->labelRight());
        $this->grade_attendance->setDescription('Grading / Reporting Progress');
        $this->grade_attendance->setAttrib('acc_menu', '1');
        $this->grade_attendance->setRequired(false);
        $this->grade_attendance->setAllowEmpty(false);
        $this->grade_attendance->addOnChange('otherHelper(this.value, \'*' . $this->grade_attendance->getDescription() . ' - ' .$this->grade_attendance->getLabel().'\')');


        $this->grade_regular_grading = new App_Form_Element_Select('grade_regular_grading',array('label'=>"Regular grading"));
        $this->grade_regular_grading->setMultiOptions($this->getSubjects());
        $this->grade_regular_grading->setDecorators($this->labelRight());
        $this->grade_regular_grading->setDescription('Grading / Reporting Progress');
        $this->grade_regular_grading->setAttrib('acc_menu', '1');
        $this->grade_regular_grading->setRequired(false);
        $this->grade_regular_grading->setAllowEmpty(false);
        $this->grade_regular_grading->addOnChange('otherHelper(this.value, \'*' . $this->grade_regular_grading->getDescription() . ' - ' .$this->grade_regular_grading->getLabel().'\')');


        $this->grade_commensurate_effort = new App_Form_Element_Select('grade_commensurate_effort',array('label'=>"Credit given if effort is commensurate"));
        $this->grade_commensurate_effort->setMultiOptions($this->getSubjects());
        $this->grade_commensurate_effort->setDecorators($this->labelRight());
        $this->grade_commensurate_effort->setDescription('Grading / Reporting Progress');
        $this->grade_commensurate_effort->setAttrib('acc_menu', '1');
        $this->grade_commensurate_effort->setRequired(false);
        $this->grade_commensurate_effort->setAllowEmpty(false);
        $this->grade_commensurate_effort->addOnChange('otherHelper(this.value, \'*' . $this->grade_commensurate_effort->getDescription() . ' - ' .$this->grade_commensurate_effort->getLabel().'\')');


        $this->grade_modified_grading = new App_Form_Element_Select('grade_modified_grading',array('label'=>"Modified grading scale"));
        $this->grade_modified_grading->setMultiOptions($this->getSubjects());
        $this->grade_modified_grading->setDecorators($this->labelRight());
        $this->grade_modified_grading->setDescription('Grading / Reporting Progress');
        $this->grade_modified_grading->setAttrib('acc_menu', '1');
        $this->grade_modified_grading->setRequired(false);
        $this->grade_modified_grading->setAllowEmpty(false);
        $this->grade_modified_grading->addOnChange('otherHelper(this.value, \'*' . $this->grade_modified_grading->getDescription() . ' - ' .$this->grade_modified_grading->getLabel().'\')');


        $this->grade_oral_presentation = new App_Form_Element_Select('grade_oral_presentation',array('label'=>"Student given credit for oral presentation"));
        $this->grade_oral_presentation->setMultiOptions($this->getSubjects());
        $this->grade_oral_presentation->setDecorators($this->labelRight());
        $this->grade_oral_presentation->setDescription('Grading / Reporting Progress');
        $this->grade_oral_presentation->setAttrib('acc_menu', '1');
        $this->grade_oral_presentation->setRequired(false);
        $this->grade_oral_presentation->setAllowEmpty(false);
        $this->grade_oral_presentation->addOnChange('otherHelper(this.value, \'*' . $this->grade_oral_presentation->getDescription() . ' - ' .$this->grade_oral_presentation->getLabel().'\')');


        $this->grade_graded_on_skills = new App_Form_Element_Select('grade_graded_on_skills',array('label'=>"Student graded only on skills being taught"));
        $this->grade_graded_on_skills->setMultiOptions($this->getSubjects());
        $this->grade_graded_on_skills->setDecorators($this->labelRight());
        $this->grade_graded_on_skills->setDescription('Grading / Reporting Progress');
        $this->grade_graded_on_skills->setAttrib('acc_menu', '1');
        $this->grade_graded_on_skills->setRequired(false);
        $this->grade_graded_on_skills->setAllowEmpty(false);
        $this->grade_graded_on_skills->addOnChange('otherHelper(this.value, \'*' . $this->grade_graded_on_skills->getDescription() . ' - ' .$this->grade_graded_on_skills->getLabel().'\')');


        $this->grade_other = new App_Form_Element_Select('grade_other',array('label'=>"Other"));
        $this->grade_other->setMultiOptions($this->getSubjects());
        $this->grade_other->setDecorators($this->labelRight());
        $this->grade_other->setDescription('Grading / Reporting Progress');
        $this->grade_other->setAttrib('acc_menu', '1');
        $this->grade_other->setRequired(false);
        $this->grade_other->setAllowEmpty(false);
        $this->grade_other->addOnChange('otherHelper(this.value, \'*' . $this->grade_other->getDescription() . ' - ' .$this->grade_other->getLabel().'\')');



        $this->asstech_supp_writ_device = new App_Form_Element_Select('asstech_supp_writ_device',array('label'=>"Use supported writing device"));
        $this->asstech_supp_writ_device->setMultiOptions($this->getSubjects());
        $this->asstech_supp_writ_device->setDecorators($this->labelRight());
        $this->asstech_supp_writ_device->setDescription('Assistive Technology');
        $this->asstech_supp_writ_device->setAttrib('acc_menu', '1');
        $this->asstech_supp_writ_device->setRequired(false);
        $this->asstech_supp_writ_device->setAllowEmpty(false);
        $this->asstech_supp_writ_device->addOnChange('otherHelper(this.value, \'*' . $this->asstech_supp_writ_device->getDescription() . ' - ' .$this->asstech_supp_writ_device->getLabel().'\')');


        $this->asstech_pro_writ_sw = new App_Form_Element_Select('asstech_pro_writ_sw',array('label'=>"Provide supported writing software"));
        $this->asstech_pro_writ_sw->setMultiOptions($this->getSubjects());
        $this->asstech_pro_writ_sw->setDecorators($this->labelRight());
        $this->asstech_pro_writ_sw->setDescription('Assistive Technology');
        $this->asstech_pro_writ_sw->setAttrib('acc_menu', '1');
        $this->asstech_pro_writ_sw->setRequired(false);
        $this->asstech_pro_writ_sw->setAllowEmpty(false);
        $this->asstech_pro_writ_sw->addOnChange('otherHelper(this.value, \'*' . $this->asstech_pro_writ_sw->getDescription() . ' - ' .$this->asstech_pro_writ_sw->getLabel().'\')');


        $this->asstech_speech_gen = new App_Form_Element_Select('asstech_speech_gen',array('label'=>"Use speech generating device"));
        $this->asstech_speech_gen->setMultiOptions($this->getSubjects());
        $this->asstech_speech_gen->setDecorators($this->labelRight());
        $this->asstech_speech_gen->setDescription('Assistive Technology');
        $this->asstech_speech_gen->setAttrib('acc_menu', '1');
        $this->asstech_speech_gen->setRequired(false);
        $this->asstech_speech_gen->setAllowEmpty(false);
        $this->asstech_speech_gen->addOnChange('otherHelper(this.value, \'*' . $this->asstech_speech_gen->getDescription() . ' - ' .$this->asstech_speech_gen->getLabel().'\')');


        $this->asstech_aug_options = new App_Form_Element_Select('asstech_aug_options',array('label'=>"Use supported augmented speech options"));
        $this->asstech_aug_options->setMultiOptions($this->getSubjects());
        $this->asstech_aug_options->setDecorators($this->labelRight());
        $this->asstech_aug_options->setDescription('Assistive Technology');
        $this->asstech_aug_options->setAttrib('acc_menu', '1');
        $this->asstech_aug_options->setRequired(false);
        $this->asstech_aug_options->setAllowEmpty(false);
        $this->asstech_aug_options->addOnChange('otherHelper(this.value, \'*' . $this->asstech_aug_options->getDescription() . ' - ' .$this->asstech_aug_options->getLabel().'\')');


        $this->asstech_physical_access = new App_Form_Element_Select('asstech_physical_access',array('label'=>"Provide physical access strategies"));
        $this->asstech_physical_access->setMultiOptions($this->getSubjects());
        $this->asstech_physical_access->setDecorators($this->labelRight());
        $this->asstech_physical_access->setDescription('Assistive Technology');
        $this->asstech_physical_access->setAttrib('acc_menu', '1');
        $this->asstech_physical_access->setRequired(false);
        $this->asstech_physical_access->setAllowEmpty(false);
        $this->asstech_physical_access->addOnChange('otherHelper(this.value, \'*' . $this->asstech_physical_access->getDescription() . ' - ' .$this->asstech_physical_access->getLabel().'\')');


        $this->asstech_enlarged_print = new App_Form_Element_Select('asstech_enlarged_print',array('label'=>"Provide vision supports for enlarged print"));
        $this->asstech_enlarged_print->setMultiOptions($this->getSubjects());
        $this->asstech_enlarged_print->setDecorators($this->labelRight());
        $this->asstech_enlarged_print->setDescription('Assistive Technology');
        $this->asstech_enlarged_print->setAttrib('acc_menu', '1');
        $this->asstech_enlarged_print->setRequired(false);
        $this->asstech_enlarged_print->setAllowEmpty(false);
        $this->asstech_enlarged_print->addOnChange('otherHelper(this.value, \'*' . $this->asstech_enlarged_print->getDescription() . ' - ' .$this->asstech_enlarged_print->getLabel().'\')');


        $this->asstech_braille = new App_Form_Element_Select('asstech_braille',array('label'=>"Braille"));
        $this->asstech_braille->setMultiOptions($this->getSubjects());
        $this->asstech_braille->setDecorators($this->labelRight());
        $this->asstech_braille->setDescription('Assistive Technology');
        $this->asstech_braille->setAttrib('acc_menu', '1');
        $this->asstech_braille->setRequired(false);
        $this->asstech_braille->setAllowEmpty(false);
        $this->asstech_braille->addOnChange('otherHelper(this.value, \'*' . $this->asstech_braille->getDescription() . ' - ' .$this->asstech_braille->getLabel().'\')');


        $this->asstech_aud_trainer = new App_Form_Element_Select('asstech_aud_trainer',array('label'=>"Utilize auditory trainer systems"));
        $this->asstech_aud_trainer->setMultiOptions($this->getSubjects());
        $this->asstech_aud_trainer->setDecorators($this->labelRight());
        $this->asstech_aud_trainer->setDescription('Assistive Technology');
        $this->asstech_aud_trainer->setAttrib('acc_menu', '1');
        $this->asstech_aud_trainer->setRequired(false);
        $this->asstech_aud_trainer->setAllowEmpty(false);
        $this->asstech_aud_trainer->addOnChange('otherHelper(this.value, \'*' . $this->asstech_aud_trainer->getDescription() . ' - ' .$this->asstech_aud_trainer->getLabel().'\')');


        $this->asstech_other = new App_Form_Element_Select('asstech_other',array('label'=>"Other"));
        $this->asstech_other->setMultiOptions($this->getSubjects());
        $this->asstech_other->setDecorators($this->labelRight());
        $this->asstech_other->setDescription('Assistive Technology');
        $this->asstech_other->setAttrib('acc_menu', '1');
        $this->asstech_other->setRequired(false);
        $this->asstech_other->setAllowEmpty(false);
        $this->asstech_other->addOnChange('otherHelper(this.value, \''.$this->asstech_other->getLabel().'\')');

        $this->asstech_other->setAttrib('onchange',
            "if(this.value == 'q') dojo.byId('accomodations_checklist_1-other').value += '*"
            .$this->asstech_other->getDescription()." - ".$this->asstech_other->getLabel()."\\n';"
            . $this->asstech_other->getAttrib('onchange')
        );


        //$this->asstech_other_text = new App_Form_Element_TextareaEditor('asstech_other_text', array('label'=>'Other Assistive Technologies'));
        $this->asstech_other_text = $this->buildEditor('asstech_other_text', array('label'=>'Other Assistive Technologies'));
	$this->asstech_other_text->setAttrib('minheight', "90px");
        $this->asstech_other_text->setRequired(false);
        $this->asstech_other_text->setAllowEmpty(true);

//      " onChange=\"if(this.value == 'q') forms[0].other.value += '*Pacing - Extend time requirements\\n';\""

        //$this->other= new App_Form_Element_TextareaEditor('other', array('label'=>'Other'));
        $this->other = $this->buildEditor('other', array('label'=>'Other'));
	$this->other->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');");
	$this->other->setAttrib('minheight', "90px");
        // $this->other->addErrorMessage("The checkbox insuring you have recorded subjects in the \"Other\" field has been checked but no value has been entered in the \"Other\" field.");
        $this->other->addErrorMessage("Accommodations Checklist - The subject \"other\" has been selected, but there is no value in the \"Other\" text box.");
        $this->other->setRequired(false);
        $this->other->setAllowEmpty(false);
        $this->other->addValidator(new My_Validate_NotEmptyIf('flag_subject_areas_entered', 't'));

        $this->flag_subject_areas_entered = new App_Form_Element_Checkbox('flag_subject_areas_entered', array('label'=>'Have you recorded the subject areas in which these modifications/accommodations should be addressed?'));
//        $this->flag_subject_areas_entered->getDecorator('Label')->setOption('class', 'noprint');
        // $this->flag_subject_areas_entered->addErrorMessage("The checkbox insuring you have recorded subjects in the \"Other\" field must be checked.");
        $this->flag_subject_areas_entered->setRequired(false);
        $this->flag_subject_areas_entered->setAllowEmpty(false);
        $this->flag_subject_areas_entered->addOnChange('otherHelper(this.value, \''.$this->flag_subject_areas_entered->getLabel().'\')');
        // $this->flag_subject_areas_entered->addValidator(new My_Validate_NoValidationIf('other', ''));

        return $this;
    }

    public function accomodations_checklist_edit_version11() {

        $this->setDecorators ( array (
                array ('ViewScript',
                    array (
                        'viewScript' => 'form004/accomodations_checklist_edit_version1.phtml'
                    )
                )
            ) );

        // allow html characters in multioptions and other display
        $this->getView()->setEscape('stripslashes');

        $this->hide = new App_Form_Element_Checkbox('hide', array('onclick'=>'javascript:enableDisableAccomodationsChecklist(this.checked)'));
        $this->hide->setCheckedValue('t');
        $this->hide->setUnCheckedValue('f');

        $this->id_accom_checklist = new App_Form_Element_Hidden('id_accom_checklist');

        //
        // these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        //
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;

        // NO DELETE ROW BUTTON!
        //$this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check and save to remove the Accomodations Checklist:'));
        //$this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
        //$this->remove_row->ignore = true;

        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Assistive Technology Row");


        $this->pacing_extended_time = new App_Form_Element_MultiSelect('pacing_extended_time',array('label'=>"Extend time requirements"));
        $this->pacing_extended_time->setMultiOptions($this->getSubjects());
        $this->pacing_extended_time->setDecorators($this->labelRight());
        $this->pacing_extended_time->setDescription('Pacing');
        $this->pacing_extended_time->setAttrib('acc_menu', '1');
        $this->pacing_extended_time->setRequired(false);
        $this->pacing_extended_time->setAllowEmpty(false);
        $this->pacing_extended_time->addOnChange('otherHelper(this.value, \'*' . $this->pacing_extended_time->getDescription() . ' - ' .$this->pacing_extended_time->getLabel().'\')');

        $this->pacing_allow_breaks = new App_Form_Element_MultiSelect('pacing_allow_breaks',array('label'=>"Allow breaks, vary activity often"));
        $this->pacing_allow_breaks->setMultiOptions($this->getSubjects());
        $this->pacing_allow_breaks->setDecorators($this->labelRight());
        $this->pacing_allow_breaks->setDescription('Pacing');
        $this->pacing_allow_breaks->setAttrib('acc_menu', '1');
        $this->pacing_allow_breaks->setRequired(false);
        $this->pacing_allow_breaks->setAllowEmpty(false);
        $this->pacing_allow_breaks->addOnChange('otherHelper(this.value, \'*' . $this->pacing_allow_breaks->getDescription() . ' - ' .$this->pacing_allow_breaks->getLabel().'\')');


        $this->pacing_omit_assignments = new App_Form_Element_MultiSelect('pacing_omit_assignments',array('label'=>"Omit assignments requiring<BR>- copying, if timed"));
        $this->pacing_omit_assignments->setMultiOptions($this->getSubjects());
        $this->pacing_omit_assignments->setDecorators($this->labelRight());
        $this->pacing_omit_assignments->setDescription('Pacing');
        $this->pacing_omit_assignments->setAttrib('acc_menu', '1');
        $this->pacing_omit_assignments->setRequired(false);
        $this->pacing_omit_assignments->setAllowEmpty(false);
        $this->pacing_omit_assignments->addOnChange('otherHelper(this.value, \'*' . $this->pacing_omit_assignments->getDescription() . ' - ' .$this->pacing_omit_assignments->getLabel().'\')');


        $this->pacing_school_texts = new App_Form_Element_MultiSelect('pacing_school_texts',array('label'=>"School texts sent home for preview"));
        $this->pacing_school_texts->setMultiOptions($this->getSubjects());
        $this->pacing_school_texts->setDecorators($this->labelRight());
        $this->pacing_school_texts->setDescription('Pacing');
        $this->pacing_school_texts->setAttrib('acc_menu', '1');
        $this->pacing_school_texts->setRequired(false);
        $this->pacing_school_texts->setAllowEmpty(false);
        $this->pacing_school_texts->addOnChange('otherHelper(this.value, \'*' . $this->pacing_school_texts->getDescription() . ' - ' .$this->pacing_school_texts->getLabel().'\')');


        $this->pacing_vary_activity = new App_Form_Element_MultiSelect('pacing_vary_activity',array('label'=>"Vary activity often"));
        $this->pacing_vary_activity->setMultiOptions($this->getSubjects());
        $this->pacing_vary_activity->setDecorators($this->labelRight());
        $this->pacing_vary_activity->setDescription('Pacing');
        $this->pacing_vary_activity->setAttrib('acc_menu', '1');
        $this->pacing_vary_activity->setRequired(false);
        $this->pacing_vary_activity->setAllowEmpty(false);
        $this->pacing_vary_activity->addOnChange('otherHelper(this.value, \'*' . $this->pacing_vary_activity->getDescription() . ' - ' .$this->pacing_vary_activity->getLabel().'\')');


        $this->pacing_other = new App_Form_Element_MultiSelect('pacing_other',array('label'=>"Other"));
        $this->pacing_other->setMultiOptions($this->getSubjects());
        $this->pacing_other->setDecorators($this->labelRight());
        $this->pacing_other->setDescription('Pacing');
        $this->pacing_other->setAttrib('acc_menu', '1');
        $this->pacing_other->setRequired(false);
        $this->pacing_other->setAllowEmpty(false);
        $this->pacing_other->addOnChange('otherHelper(this.value, \'*' . $this->pacing_other->getDescription() . ' - ' .$this->pacing_other->getLabel().'\')');



        $this->lessson_teacher_emph = new App_Form_Element_MultiSelect('lessson_teacher_emph',array('label'=>"Teacher emphasize:<BR>- Visual<BR>- Auditory<BR>- Tactil<BR>- Multi-sensory"));
        $this->lessson_teacher_emph->setMultiOptions($this->getSubjects());
        $this->lessson_teacher_emph->setDecorators($this->labelRight());
        $this->lessson_teacher_emph->setDescription('Lesson Presentation');
        $this->lessson_teacher_emph->setAttrib('acc_menu', '1');
        $this->lessson_teacher_emph->setRequired(false);
        $this->lessson_teacher_emph->setAllowEmpty(false);
        $this->lessson_teacher_emph->addOnChange('otherHelper(this.value, \'*' . $this->lessson_teacher_emph->getDescription() . ' - ' .$this->lessson_teacher_emph->getLabel().'\')');


        $this->lessson_sm_grp_inst = new App_Form_Element_MultiSelect('lessson_sm_grp_inst',array('label'=>"Individual/Small group Instruction"));
        $this->lessson_sm_grp_inst->setMultiOptions($this->getSubjects());
        $this->lessson_sm_grp_inst->setDecorators($this->labelRight());
        $this->lessson_sm_grp_inst->setDescription('Lesson Presentation');
        $this->lessson_sm_grp_inst->setAttrib('acc_menu', '1');
        $this->lessson_sm_grp_inst->setRequired(false);
        $this->lessson_sm_grp_inst->setAllowEmpty(false);
        $this->lessson_sm_grp_inst->addOnChange('otherHelper(this.value, \'*' . $this->lessson_sm_grp_inst->getDescription() . ' - ' .$this->lessson_sm_grp_inst->getLabel().'\')');


        $this->lessson_spec_curr = new App_Form_Element_MultiSelect('lessson_spec_curr',array('label'=>"Utilize specialized curriculum"));
        $this->lessson_spec_curr->setMultiOptions($this->getSubjects());
        $this->lessson_spec_curr->setDecorators($this->labelRight());
        $this->lessson_spec_curr->setDescription('Lesson Presentation');
        $this->lessson_spec_curr->setAttrib('acc_menu', '1');
        $this->lessson_spec_curr->setRequired(false);
        $this->lessson_spec_curr->setAllowEmpty(false);
        $this->lessson_spec_curr->addOnChange('otherHelper(this.value, \'*' . $this->lessson_spec_curr->getDescription() . ' - ' .$this->lessson_spec_curr->getLabel().'\')');


        $this->lessson_tape_lectures = new App_Form_Element_MultiSelect('lessson_tape_lectures',array('label'=>"Tape lectures for replay"));
        $this->lessson_tape_lectures->setMultiOptions($this->getSubjects());
        $this->lessson_tape_lectures->setDecorators($this->labelRight());
        $this->lessson_tape_lectures->setDescription('Lesson Presentation');
        $this->lessson_tape_lectures->setAttrib('acc_menu', '1');
        $this->lessson_tape_lectures->setRequired(false);
        $this->lessson_tape_lectures->setAllowEmpty(false);
        $this->lessson_tape_lectures->addOnChange('otherHelper(this.value, \'*' . $this->lessson_tape_lectures->getDescription() . ' - ' .$this->lessson_tape_lectures->getLabel().'\')');


        $this->lessson_utilize_manip = new App_Form_Element_MultiSelect('lessson_utilize_manip',array('label'=>"Utilize manipulative materials"));
        $this->lessson_utilize_manip->setMultiOptions($this->getSubjects());
        $this->lessson_utilize_manip->setDecorators($this->labelRight());
        $this->lessson_utilize_manip->setDescription('Lesson Presentation');
        $this->lessson_utilize_manip->setAttrib('acc_menu', '1');
        $this->lessson_utilize_manip->setRequired(false);
        $this->lessson_utilize_manip->setAllowEmpty(false);
        $this->lessson_utilize_manip->addOnChange('otherHelper(this.value, \'*' . $this->lessson_utilize_manip->getDescription() . ' - ' .$this->lessson_utilize_manip->getLabel().'\')');


        $this->lessson_emph_info = new App_Form_Element_MultiSelect('lessson_emph_info',array('label'=>"Emphasize critical information"));
        $this->lessson_emph_info->setMultiOptions($this->getSubjects());
        $this->lessson_emph_info->setDecorators($this->labelRight());
        $this->lessson_emph_info->setDescription('Lesson Presentation');
        $this->lessson_emph_info->setAttrib('acc_menu', '1');
        $this->lessson_emph_info->setRequired(false);
        $this->lessson_emph_info->setAllowEmpty(false);
        $this->lessson_emph_info->addOnChange('otherHelper(this.value, \'*' . $this->lessson_emph_info->getDescription() . ' - ' .$this->lessson_emph_info->getLabel().'\')');


        $this->lessson_preteach_voc = new App_Form_Element_MultiSelect('lessson_preteach_voc',array('label'=>"Preteach vocabulary"));
        $this->lessson_preteach_voc->setMultiOptions($this->getSubjects());
        $this->lessson_preteach_voc->setDecorators($this->labelRight());
        $this->lessson_preteach_voc->setDescription('Lesson Presentation');
        $this->lessson_preteach_voc->setAttrib('acc_menu', '1');
        $this->lessson_preteach_voc->setRequired(false);
        $this->lessson_preteach_voc->setAllowEmpty(false);
        $this->lessson_preteach_voc->addOnChange('otherHelper(this.value, \'*' . $this->lessson_preteach_voc->getDescription() . ' - ' .$this->lessson_preteach_voc->getLabel().'\')');


        $this->lessson_reduce_lang = new App_Form_Element_MultiSelect('lessson_reduce_lang',array('label'=>"Reduce language level or reading<BR>- level of assignment"));
        $this->lessson_reduce_lang->setMultiOptions($this->getSubjects());
        $this->lessson_reduce_lang->setDecorators($this->labelRight());
        $this->lessson_reduce_lang->setDescription('Lesson Presentation');
        $this->lessson_reduce_lang->setAttrib('acc_menu', '1');
        $this->lessson_reduce_lang->setRequired(false);
        $this->lessson_reduce_lang->setAllowEmpty(false);
        $this->lessson_reduce_lang->addOnChange('otherHelper(this.value, \'*' . $this->lessson_reduce_lang->getDescription() . ' - ' .$this->lessson_reduce_lang->getLabel().'\')');


        $this->lessson_sign_lang = new App_Form_Element_MultiSelect('lessson_sign_lang',array('label'=>"Sign language Interpreter"));
        $this->lessson_sign_lang->setMultiOptions($this->getSubjects());
        $this->lessson_sign_lang->setDecorators($this->labelRight());
        $this->lessson_sign_lang->setDescription('Lesson Presentation');
        $this->lessson_sign_lang->setAttrib('acc_menu', '1');
        $this->lessson_sign_lang->setRequired(false);
        $this->lessson_sign_lang->setAllowEmpty(false);
        $this->lessson_sign_lang->addOnChange('otherHelper(this.value, \'*' . $this->lessson_sign_lang->getDescription() . ' - ' .$this->lessson_sign_lang->getLabel().'\')');


        $this->lessson_total_comm = new App_Form_Element_MultiSelect('lessson_total_comm',array('label'=>"Use specialized communication supports"));
        $this->lessson_total_comm->setMultiOptions($this->getSubjects());
        $this->lessson_total_comm->setDecorators($this->labelRight());
        $this->lessson_total_comm->setDescription('Lesson Presentation');
        $this->lessson_total_comm->setAttrib('acc_menu', '1');
        $this->lessson_total_comm->setRequired(false);
        $this->lessson_total_comm->setAllowEmpty(false);
        $this->lessson_total_comm->addOnChange('otherHelper(this.value, \'*' . $this->lessson_total_comm->getDescription() . ' - ' .$this->lessson_total_comm->getLabel().'\')');


        $this->lessson_oral_intrepreter = new App_Form_Element_MultiSelect('lessson_oral_intrepreter',array('label'=>"Oral Interpreter"));
        $this->lessson_oral_intrepreter->setMultiOptions($this->getSubjects());
        $this->lessson_oral_intrepreter->setDecorators($this->labelRight());
        $this->lessson_oral_intrepreter->setDescription('Lesson Presentation');
        $this->lessson_oral_intrepreter->setAttrib('acc_menu', '1');
        $this->lessson_oral_intrepreter->setRequired(false);
        $this->lessson_oral_intrepreter->setAllowEmpty(false);
        $this->lessson_oral_intrepreter->addOnChange('otherHelper(this.value, \'*' . $this->lessson_oral_intrepreter->getDescription() . ' - ' .$this->lessson_oral_intrepreter->getLabel().'\')');


        $this->lessson_present_demo = new App_Form_Element_MultiSelect('lessson_present_demo',array('label'=>"Present demonstration (model)"));
        $this->lessson_present_demo->setMultiOptions($this->getSubjects());
        $this->lessson_present_demo->setDecorators($this->labelRight());
        $this->lessson_present_demo->setDescription('Lesson Presentation');
        $this->lessson_present_demo->setAttrib('acc_menu', '1');
        $this->lessson_present_demo->setRequired(false);
        $this->lessson_present_demo->setAllowEmpty(false);
        $this->lessson_present_demo->addOnChange('otherHelper(this.value, \'*' . $this->lessson_present_demo->getDescription() . ' - ' .$this->lessson_present_demo->getLabel().'\')');


        $this->lessson_teacher_provides = new App_Form_Element_MultiSelect('lessson_teacher_provides',array('label'=>"Teacher or peer provides notes or outline"));
        $this->lessson_teacher_provides->setMultiOptions($this->getSubjects());
        $this->lessson_teacher_provides->setDecorators($this->labelRight());
        $this->lessson_teacher_provides->setDescription('Lesson Presentation');
        $this->lessson_teacher_provides->setAttrib('acc_menu', '1');
        $this->lessson_teacher_provides->setRequired(false);
        $this->lessson_teacher_provides->setAllowEmpty(false);
        $this->lessson_teacher_provides->addOnChange('otherHelper(this.value, \'*' . $this->lessson_teacher_provides->getDescription() . ' - ' .$this->lessson_teacher_provides->getLabel().'\')');


        $this->lessson_functional_app = new App_Form_Element_MultiSelect('lessson_functional_app',array('label'=>"Functional application of academic skills"));
        $this->lessson_functional_app->setMultiOptions($this->getSubjects());
        $this->lessson_functional_app->setDecorators($this->labelRight());
        $this->lessson_functional_app->setDescription('Lesson Presentation');
        $this->lessson_functional_app->setAttrib('acc_menu', '1');
        $this->lessson_functional_app->setRequired(false);
        $this->lessson_functional_app->setAllowEmpty(false);
        $this->lessson_functional_app->addOnChange('otherHelper(this.value, \'*' . $this->lessson_functional_app->getDescription() . ' - ' .$this->lessson_functional_app->getLabel().'\')');


        $this->lessson_visual_sequences = new App_Form_Element_MultiSelect('lessson_visual_sequences',array('label'=>"Use visual sequences"));
        $this->lessson_visual_sequences->setMultiOptions($this->getSubjects());
        $this->lessson_visual_sequences->setDecorators($this->labelRight());
        $this->lessson_visual_sequences->setDescription('Lesson Presentation');
        $this->lessson_visual_sequences->setAttrib('acc_menu', '1');
        $this->lessson_visual_sequences->setRequired(false);
        $this->lessson_visual_sequences->setAllowEmpty(false);
        $this->lessson_visual_sequences->addOnChange('otherHelper(this.value, \'*' . $this->lessson_visual_sequences->getDescription() . ' - ' .$this->lessson_visual_sequences->getLabel().'\')');


        $this->lessson_make_use_voc = new App_Form_Element_MultiSelect('lessson_make_use_voc',array('label'=>"Make/use vocabulary supports such as word cards, personal dictionaries, vocabulary files"));
        $this->lessson_make_use_voc->setMultiOptions($this->getSubjects());
        $this->lessson_make_use_voc->setDecorators($this->labelRight());
        $this->lessson_make_use_voc->setDescription('Lesson Presentation');
        $this->lessson_make_use_voc->setAttrib('acc_menu', '1');
        $this->lessson_make_use_voc->setRequired(false);
        $this->lessson_make_use_voc->setAllowEmpty(false);
        $this->lessson_make_use_voc->addOnChange('otherHelper(this.value, \'*' . $this->lessson_make_use_voc->getDescription() . ' - ' .$this->lessson_make_use_voc->getLabel().'\')');


        $this->lessson_other = new App_Form_Element_MultiSelect('lessson_other',array('label'=>"Other"));
        $this->lessson_other->setMultiOptions($this->getSubjects());
        $this->lessson_other->setDecorators($this->labelRight());
        $this->lessson_other->setDescription('Lesson Presentation');
        $this->lessson_other->setAttrib('acc_menu', '1');
        $this->lessson_other->setRequired(false);
        $this->lessson_other->setAllowEmpty(false);
        $this->lessson_other->addOnChange('otherHelper(this.value, \'*' . $this->lessson_other->getDescription() . ' - ' .$this->lessson_other->getLabel().'\')');



        $this->env_pref_seating = new App_Form_Element_MultiSelect('env_pref_seating',array('label'=>"Preferential seating"));
        $this->env_pref_seating->setMultiOptions($this->getSubjects());
        $this->env_pref_seating->setDecorators($this->labelRight());
        $this->env_pref_seating->setDescription('Environment');
        $this->env_pref_seating->setAttrib('acc_menu', '1');
        $this->env_pref_seating->setRequired(false);
        $this->env_pref_seating->setAllowEmpty(false);
        $this->env_pref_seating->addOnChange('otherHelper(this.value, \'*' . $this->env_pref_seating->getDescription() . ' - ' .$this->env_pref_seating->getLabel().'\')');


        $this->env_seat_near_teacher = new App_Form_Element_MultiSelect('env_seat_near_teacher',array('label'=>"Seat near teacher"));
        $this->env_seat_near_teacher->setMultiOptions($this->getSubjects());
        $this->env_seat_near_teacher->setDecorators($this->labelRight());
        $this->env_seat_near_teacher->setDescription('Environment');
        $this->env_seat_near_teacher->setAttrib('acc_menu', '1');
        $this->env_seat_near_teacher->setRequired(false);
        $this->env_seat_near_teacher->setAllowEmpty(false);
        $this->env_seat_near_teacher->addOnChange('otherHelper(this.value, \'*' . $this->env_seat_near_teacher->getDescription() . ' - ' .$this->env_seat_near_teacher->getLabel().'\')');


        $this->env_seat_near_role = new App_Form_Element_MultiSelect('env_seat_near_role',array('label'=>"Seat near positive role model"));
        $this->env_seat_near_role->setMultiOptions($this->getSubjects());
        $this->env_seat_near_role->setDecorators($this->labelRight());
        $this->env_seat_near_role->setDescription('Environment');
        $this->env_seat_near_role->setAttrib('acc_menu', '1');
        $this->env_seat_near_role->setRequired(false);
        $this->env_seat_near_role->setAllowEmpty(false);
        $this->env_seat_near_role->addOnChange('otherHelper(this.value, \'*' . $this->env_seat_near_role->getDescription() . ' - ' .$this->env_seat_near_role->getLabel().'\')');


        $this->env_avoid_distr = new App_Form_Element_MultiSelect('env_avoid_distr',array('label'=>"Avoid distracting stimuli"));
        $this->env_avoid_distr->setMultiOptions($this->getSubjects());
        $this->env_avoid_distr->setDecorators($this->labelRight());
        $this->env_avoid_distr->setDescription('Environment');
        $this->env_avoid_distr->setAttrib('acc_menu', '1');
        $this->env_avoid_distr->setRequired(false);
        $this->env_avoid_distr->setAllowEmpty(false);
        $this->env_avoid_distr->addOnChange('otherHelper(this.value, \'*' . $this->env_avoid_distr->getDescription() . ' - ' .$this->env_avoid_distr->getLabel().'\')');


        $this->env_increase_distance = new App_Form_Element_MultiSelect('env_increase_distance',array('label'=>"Increase distance between desks"));
        $this->env_increase_distance->setMultiOptions($this->getSubjects());
        $this->env_increase_distance->setDecorators($this->labelRight());
        $this->env_increase_distance->setDescription('Environment');
        $this->env_increase_distance->setAttrib('acc_menu', '1');
        $this->env_increase_distance->setRequired(false);
        $this->env_increase_distance->setAllowEmpty(false);
        $this->env_increase_distance->addOnChange('otherHelper(this.value, \'*' . $this->env_increase_distance->getDescription() . ' - ' .$this->env_increase_distance->getLabel().'\')');


        $this->env_planned_seating = new App_Form_Element_MultiSelect('env_planned_seating',array('label'=>"Planned seating:<BR>- Bus Classroom<BR>- Lunchroom <BR>- Auditorium"));
        $this->env_planned_seating->setMultiOptions($this->getSubjects());
        $this->env_planned_seating->setDecorators($this->labelRight());
        $this->env_planned_seating->setDescription('Environment');
        $this->env_planned_seating->setAttrib('acc_menu', '1');
        $this->env_planned_seating->setRequired(false);
        $this->env_planned_seating->setAllowEmpty(false);
        $this->env_planned_seating->addOnChange('otherHelper(this.value, \'*' . $this->env_planned_seating->getDescription() . ' - ' .$this->env_planned_seating->getLabel().'\')');


        $this->env_alter_physical_room = new App_Form_Element_MultiSelect('env_alter_physical_room',array('label'=>"Alter physical room arrangement"));
        $this->env_alter_physical_room->setMultiOptions($this->getSubjects());
        $this->env_alter_physical_room->setDecorators($this->labelRight());
        $this->env_alter_physical_room->setDescription('Environment');
        $this->env_alter_physical_room->setAttrib('acc_menu', '1');
        $this->env_alter_physical_room->setRequired(false);
        $this->env_alter_physical_room->setAllowEmpty(false);
        $this->env_alter_physical_room->addOnChange('otherHelper(this.value, \'*' . $this->env_alter_physical_room->getDescription() . ' - ' .$this->env_alter_physical_room->getLabel().'\')');


        $this->env_define_areas = new App_Form_Element_MultiSelect('env_define_areas',array('label'=>"Define areas concretely"));
        $this->env_define_areas->setMultiOptions($this->getSubjects());
        $this->env_define_areas->setDecorators($this->labelRight());
        $this->env_define_areas->setDescription('Environment');
        $this->env_define_areas->setAttrib('acc_menu', '1');
        $this->env_define_areas->setRequired(false);
        $this->env_define_areas->setAllowEmpty(false);
        $this->env_define_areas->addOnChange('otherHelper(this.value, \'*' . $this->env_define_areas->getDescription() . ' - ' .$this->env_define_areas->getLabel().'\')');


        $this->env_reduce_distractions = new App_Form_Element_MultiSelect('env_reduce_distractions',array('label'=>"Reduce/minimize distractions<BR>- Visual Auditory<BR>- Spatial Movement"));
        $this->env_reduce_distractions->setMultiOptions($this->getSubjects());
        $this->env_reduce_distractions->setDecorators($this->labelRight());
        $this->env_reduce_distractions->setDescription('Environment');
        $this->env_reduce_distractions->setAttrib('acc_menu', '1');
        $this->env_reduce_distractions->setRequired(false);
        $this->env_reduce_distractions->setAllowEmpty(false);
        $this->env_reduce_distractions->addOnChange('otherHelper(this.value, \'*' . $this->env_reduce_distractions->getDescription() . ' - ' .$this->env_reduce_distractions->getLabel().'\')');


        $this->env_teach_pos_rules = new App_Form_Element_MultiSelect('env_teach_pos_rules',array('label'=>"Teach positive rules for use of space"));
        $this->env_teach_pos_rules->setMultiOptions($this->getSubjects());
        $this->env_teach_pos_rules->setDecorators($this->labelRight());
        $this->env_teach_pos_rules->setDescription('Environment');
        $this->env_teach_pos_rules->setAttrib('acc_menu', '1');
        $this->env_teach_pos_rules->setRequired(false);
        $this->env_teach_pos_rules->setAllowEmpty(false);
        $this->env_teach_pos_rules->addOnChange('otherHelper(this.value, \'*' . $this->env_teach_pos_rules->getDescription() . ' - ' .$this->env_teach_pos_rules->getLabel().'\')');


        $this->env_comp_tech_work = new App_Form_Element_MultiSelect('env_comp_tech_work',array('label'=>"Complete work in technology setting"));
        $this->env_comp_tech_work->setMultiOptions($this->getSubjects());
        $this->env_comp_tech_work->setDecorators($this->labelRight());
        $this->env_comp_tech_work->setDescription('Environment');
        $this->env_comp_tech_work->setAttrib('acc_menu', '1');
        $this->env_comp_tech_work->setRequired(false);
        $this->env_comp_tech_work->setAllowEmpty(false);
        $this->env_comp_tech_work->addOnChange('otherHelper(this.value, \'*' . $this->env_comp_tech_work->getDescription() . ' - ' .$this->env_comp_tech_work->getLabel().'\')');


        $this->env_other = new App_Form_Element_MultiSelect('env_other',array('label'=>"Other"));
        $this->env_other->setMultiOptions($this->getSubjects());
        $this->env_other->setDecorators($this->labelRight());
        $this->env_other->setDescription('Environment');
        $this->env_other->setAttrib('acc_menu', '1');
        $this->env_other->setRequired(false);
        $this->env_other->setAllowEmpty(false);
        $this->env_other->addOnChange('otherHelper(this.value, \'*' . $this->env_other->getDescription() . ' - ' .$this->env_other->getLabel().'\')');



        $this->mat_taped_texts = new App_Form_Element_MultiSelect('mat_taped_texts',array('label'=>"Taped texts and/or other class materials"));
        $this->mat_taped_texts->setMultiOptions($this->getSubjects());
        $this->mat_taped_texts->setDecorators($this->labelRight());
        $this->mat_taped_texts->setDescription('Materials');
        $this->mat_taped_texts->setAttrib('acc_menu', '1');
        $this->mat_taped_texts->setRequired(false);
        $this->mat_taped_texts->setAllowEmpty(false);
        $this->mat_taped_texts->addOnChange('otherHelper(this.value, \'*' . $this->mat_taped_texts->getDescription() . ' - ' .$this->mat_taped_texts->getLabel().'\')');


        $this->mat_highlighted_texts = new App_Form_Element_MultiSelect('mat_highlighted_texts',array('label'=>"Highlighted texts/study guides"));
        $this->mat_highlighted_texts->setMultiOptions($this->getSubjects());
        $this->mat_highlighted_texts->setDecorators($this->labelRight());
        $this->mat_highlighted_texts->setDescription('Materials');
        $this->mat_highlighted_texts->setAttrib('acc_menu', '1');
        $this->mat_highlighted_texts->setRequired(false);
        $this->mat_highlighted_texts->setAllowEmpty(false);
        $this->mat_highlighted_texts->addOnChange('otherHelper(this.value, \'*' . $this->mat_highlighted_texts->getDescription() . ' - ' .$this->mat_highlighted_texts->getLabel().'\')');


        $this->mat_use_supp_mats = new App_Form_Element_MultiSelect('mat_use_supp_mats',array('label'=>"Use supplementary materials"));
        $this->mat_use_supp_mats->setMultiOptions($this->getSubjects());
        $this->mat_use_supp_mats->setDecorators($this->labelRight());
        $this->mat_use_supp_mats->setDescription('Materials');
        $this->mat_use_supp_mats->setAttrib('acc_menu', '1');
        $this->mat_use_supp_mats->setRequired(false);
        $this->mat_use_supp_mats->setAllowEmpty(false);
        $this->mat_use_supp_mats->addOnChange('otherHelper(this.value, \'*' . $this->mat_use_supp_mats->getDescription() . ' - ' .$this->mat_use_supp_mats->getLabel().'\')');


        $this->mat_note_taking = new App_Form_Element_MultiSelect('mat_note_taking',array('label'=>"Note taking assistance:Photocopy of notes of peer"));
        $this->mat_note_taking->setMultiOptions($this->getSubjects());
        $this->mat_note_taking->setDecorators($this->labelRight());
        $this->mat_note_taking->setDescription('Materials');
        $this->mat_note_taking->setAttrib('acc_menu', '1');
        $this->mat_note_taking->setRequired(false);
        $this->mat_note_taking->setAllowEmpty(false);
        $this->mat_note_taking->addOnChange('otherHelper(this.value, \'*' . $this->mat_note_taking->getDescription() . ' - ' .$this->mat_note_taking->getLabel().'\')');


        $this->mat_type_handwritten = new App_Form_Element_MultiSelect('mat_type_handwritten',array('label'=>"Type handwritten teacher material"));
        $this->mat_type_handwritten->setMultiOptions($this->getSubjects());
        $this->mat_type_handwritten->setDecorators($this->labelRight());
        $this->mat_type_handwritten->setDescription('Materials');
        $this->mat_type_handwritten->setAttrib('acc_menu', '1');
        $this->mat_type_handwritten->setRequired(false);
        $this->mat_type_handwritten->setAllowEmpty(false);
        $this->mat_type_handwritten->addOnChange('otherHelper(this.value, \'*' . $this->mat_type_handwritten->getDescription() . ' - ' .$this->mat_type_handwritten->getLabel().'\')');


        $this->mat_arrangement = new App_Form_Element_MultiSelect('mat_arrangement',array('label'=>"Arrangement of materials on page"));
        $this->mat_arrangement->setMultiOptions($this->getSubjects());
        $this->mat_arrangement->setDecorators($this->labelRight());
        $this->mat_arrangement->setDescription('Materials');
        $this->mat_arrangement->setAttrib('acc_menu', '1');
        $this->mat_arrangement->setRequired(false);
        $this->mat_arrangement->setAllowEmpty(false);
        $this->mat_arrangement->addOnChange('otherHelper(this.value, \'*' . $this->mat_arrangement->getDescription() . ' - ' .$this->mat_arrangement->getLabel().'\')');


        $this->mat_large_print = new App_Form_Element_MultiSelect('mat_large_print',array('label'=>"Provide large print materials"));
        $this->mat_large_print->setMultiOptions($this->getSubjects());
        $this->mat_large_print->setDecorators($this->labelRight());
        $this->mat_large_print->setDescription('Materials');
        $this->mat_large_print->setAttrib('acc_menu', '1');
        $this->mat_large_print->setRequired(false);
        $this->mat_large_print->setAllowEmpty(false);
        $this->mat_large_print->addOnChange('otherHelper(this.value, \'*' . $this->mat_large_print->getDescription() . ' - ' .$this->mat_large_print->getLabel().'\')');


        $this->mat_enlarge_notes = new App_Form_Element_MultiSelect('mat_enlarge_notes',array('label'=>"Enlarge notes/workbook pages (photocopier)"));
        $this->mat_enlarge_notes->setMultiOptions($this->getSubjects());
        $this->mat_enlarge_notes->setDecorators($this->labelRight());
        $this->mat_enlarge_notes->setDescription('Materials');
        $this->mat_enlarge_notes->setAttrib('acc_menu', '1');
        $this->mat_enlarge_notes->setRequired(false);
        $this->mat_enlarge_notes->setAllowEmpty(false);
        $this->mat_enlarge_notes->addOnChange('otherHelper(this.value, \'*' . $this->mat_enlarge_notes->getDescription() . ' - ' .$this->mat_enlarge_notes->getLabel().'\')');


        $this->mat_special_equip = new App_Form_Element_MultiSelect('mat_special_equip',array('label'=>"Special equipment:<BR>- Calculator<BR>- Computer<BR>- Video recorder<BR>- Audio Recorder<BR>- SGD: Speech Generating Device<BR>- Amplification System"));
        $this->mat_special_equip->setMultiOptions($this->getSubjects());
        $this->mat_special_equip->setDecorators($this->labelRight());
        $this->mat_special_equip->setDescription('Materials');
        $this->mat_special_equip->setAttrib('acc_menu', '1');
        $this->mat_special_equip->setRequired(false);
        $this->mat_special_equip->setAllowEmpty(false);
        $this->mat_special_equip->addOnChange('otherHelper(this.value, \'*' . $this->mat_special_equip->getDescription() . ' - ' .$this->mat_special_equip->getLabel().'\')');


        $this->mat_other = new App_Form_Element_MultiSelect('mat_other',array('label'=>"Other"));
        $this->mat_other->setMultiOptions($this->getSubjects());
        $this->mat_other->setDecorators($this->labelRight());
        $this->mat_other->setDescription('Materials');
        $this->mat_other->setAttrib('acc_menu', '1');
        $this->mat_other->setRequired(false);
        $this->mat_other->setAllowEmpty(false);
        $this->mat_other->addOnChange('otherHelper(this.value, \'*' . $this->mat_other->getDescription() . ' - ' .$this->mat_other->getLabel().'\')');



        $this->ass_give_directions = new App_Form_Element_MultiSelect('ass_give_directions',array('label'=>"Give directions in small, distinct steps"));
        $this->ass_give_directions->setMultiOptions($this->getSubjects());
        $this->ass_give_directions->setDecorators($this->labelRight());
        $this->ass_give_directions->setDescription('Assignments');
        $this->ass_give_directions->setAttrib('acc_menu', '1');
        $this->ass_give_directions->setRequired(false);
        $this->ass_give_directions->setAllowEmpty(false);
        $this->ass_give_directions->addOnChange('otherHelper(this.value, \'*' . $this->ass_give_directions->getDescription() . ' - ' .$this->ass_give_directions->getLabel().'\')');


        $this->ass_allow_copying = new App_Form_Element_MultiSelect('ass_allow_copying',array('label'=>"Allow copying an answer directly from paper/book"));
        $this->ass_allow_copying->setMultiOptions($this->getSubjects());
        $this->ass_allow_copying->setDecorators($this->labelRight());
        $this->ass_allow_copying->setDescription('Assignments');
        $this->ass_allow_copying->setAttrib('acc_menu', '1');
        $this->ass_allow_copying->setRequired(false);
        $this->ass_allow_copying->setAllowEmpty(false);
        $this->ass_allow_copying->addOnChange('otherHelper(this.value, \'*' . $this->ass_allow_copying->getDescription() . ' - ' .$this->ass_allow_copying->getLabel().'\')');


        $this->ass_provide_oral_directions = new App_Form_Element_MultiSelect('ass_provide_oral_directions',array('label'=>"Provide oral directions"));
        $this->ass_provide_oral_directions->setMultiOptions($this->getSubjects());
        $this->ass_provide_oral_directions->setDecorators($this->labelRight());
        $this->ass_provide_oral_directions->setDescription('Assignments');
        $this->ass_provide_oral_directions->setAttrib('acc_menu', '1');
        $this->ass_provide_oral_directions->setRequired(false);
        $this->ass_provide_oral_directions->setAllowEmpty(false);
        $this->ass_provide_oral_directions->addOnChange('otherHelper(this.value, \'*' . $this->ass_provide_oral_directions->getDescription() . ' - ' .$this->ass_provide_oral_directions->getLabel().'\')');


        $this->ass_lower_diff_level = new App_Form_Element_MultiSelect('ass_lower_diff_level',array('label'=>"Lower difficulty level"));
        $this->ass_lower_diff_level->setMultiOptions($this->getSubjects());
        $this->ass_lower_diff_level->setDecorators($this->labelRight());
        $this->ass_lower_diff_level->setDescription('Assignments');
        $this->ass_lower_diff_level->setAttrib('acc_menu', '1');
        $this->ass_lower_diff_level->setRequired(false);
        $this->ass_lower_diff_level->setAllowEmpty(false);
        $this->ass_lower_diff_level->addOnChange('otherHelper(this.value, \'*' . $this->ass_lower_diff_level->getDescription() . ' - ' .$this->ass_lower_diff_level->getLabel().'\')');


        $this->ass_shorten_assign = new App_Form_Element_MultiSelect('ass_shorten_assign',array('label'=>"Shorten assignment"));
        $this->ass_shorten_assign->setMultiOptions($this->getSubjects());
        $this->ass_shorten_assign->setDecorators($this->labelRight());
        $this->ass_shorten_assign->setDescription('Assignments');
        $this->ass_shorten_assign->setAttrib('acc_menu', '1');
        $this->ass_shorten_assign->setRequired(false);
        $this->ass_shorten_assign->setAllowEmpty(false);
        $this->ass_shorten_assign->addOnChange('otherHelper(this.value, \'*' . $this->ass_shorten_assign->getDescription() . ' - ' .$this->ass_shorten_assign->getLabel().'\')');


        $this->ass_reduce_paper_tasks = new App_Form_Element_MultiSelect('ass_reduce_paper_tasks',array('label'=>"Reduce paper and pencil tasks"));
        $this->ass_reduce_paper_tasks->setMultiOptions($this->getSubjects());
        $this->ass_reduce_paper_tasks->setDecorators($this->labelRight());
        $this->ass_reduce_paper_tasks->setDescription('Assignments');
        $this->ass_reduce_paper_tasks->setAttrib('acc_menu', '1');
        $this->ass_reduce_paper_tasks->setRequired(false);
        $this->ass_reduce_paper_tasks->setAllowEmpty(false);
        $this->ass_reduce_paper_tasks->addOnChange('otherHelper(this.value, \'*' . $this->ass_reduce_paper_tasks->getDescription() . ' - ' .$this->ass_reduce_paper_tasks->getLabel().'\')');


        $this->ass_read_directions = new App_Form_Element_MultiSelect('ass_read_directions',array('label'=>"Read directions to student"));
        $this->ass_read_directions->setMultiOptions($this->getSubjects());
        $this->ass_read_directions->setDecorators($this->labelRight());
        $this->ass_read_directions->setDescription('Assignments');
        $this->ass_read_directions->setAttrib('acc_menu', '1');
        $this->ass_read_directions->setRequired(false);
        $this->ass_read_directions->setAllowEmpty(false);
        $this->ass_read_directions->addOnChange('otherHelper(this.value, \'*' . $this->ass_read_directions->getDescription() . ' - ' .$this->ass_read_directions->getLabel().'\')');


        $this->ass_give_oral_cues = new App_Form_Element_MultiSelect('ass_give_oral_cues',array('label'=>"Give oral cues or prompts"));
        $this->ass_give_oral_cues->setMultiOptions($this->getSubjects());
        $this->ass_give_oral_cues->setDecorators($this->labelRight());
        $this->ass_give_oral_cues->setDescription('Assignments');
        $this->ass_give_oral_cues->setAttrib('acc_menu', '1');
        $this->ass_give_oral_cues->setRequired(false);
        $this->ass_give_oral_cues->setAllowEmpty(false);
        $this->ass_give_oral_cues->addOnChange('otherHelper(this.value, \'*' . $this->ass_give_oral_cues->getDescription() . ' - ' .$this->ass_give_oral_cues->getLabel().'\')');


        $this->ass_record_assignment = new App_Form_Element_MultiSelect('ass_record_assignment',array('label'=>"Record or type assignment"));
        $this->ass_record_assignment->setMultiOptions($this->getSubjects());
        $this->ass_record_assignment->setDecorators($this->labelRight());
        $this->ass_record_assignment->setDescription('Assignments');
        $this->ass_record_assignment->setAttrib('acc_menu', '1');
        $this->ass_record_assignment->setRequired(false);
        $this->ass_record_assignment->setAllowEmpty(false);
        $this->ass_record_assignment->addOnChange('otherHelper(this.value, \'*' . $this->ass_record_assignment->getDescription() . ' - ' .$this->ass_record_assignment->getLabel().'\')');


        $this->ass_adapt_worksheet = new App_Form_Element_MultiSelect('ass_adapt_worksheet',array('label'=>"Adapt worksheets, packets"));
        $this->ass_adapt_worksheet->setMultiOptions($this->getSubjects());
        $this->ass_adapt_worksheet->setDecorators($this->labelRight());
        $this->ass_adapt_worksheet->setDescription('Assignments');
        $this->ass_adapt_worksheet->setAttrib('acc_menu', '1');
        $this->ass_adapt_worksheet->setRequired(false);
        $this->ass_adapt_worksheet->setAllowEmpty(false);
        $this->ass_adapt_worksheet->addOnChange('otherHelper(this.value, \'*' . $this->ass_adapt_worksheet->getDescription() . ' - ' .$this->ass_adapt_worksheet->getLabel().'\')');


        $this->ass_provide_alternate = new App_Form_Element_MultiSelect('ass_provide_alternate',array('label'=>"Provide alternate assignments or strategies"));
        $this->ass_provide_alternate->setMultiOptions($this->getSubjects());
        $this->ass_provide_alternate->setDecorators($this->labelRight());
        $this->ass_provide_alternate->setDescription('Assignments');
        $this->ass_provide_alternate->setAttrib('acc_menu', '1');
        $this->ass_provide_alternate->setRequired(false);
        $this->ass_provide_alternate->setAllowEmpty(false);
        $this->ass_provide_alternate->addOnChange('otherHelper(this.value, \'*' . $this->ass_provide_alternate->getDescription() . ' - ' .$this->ass_provide_alternate->getLabel().'\')');


        $this->ass_avoide_penalizing = new App_Form_Element_MultiSelect('ass_avoide_penalizing',array('label'=>"Avoid penalizing for spelling errors"));
        $this->ass_avoide_penalizing->setMultiOptions($this->getSubjects());
        $this->ass_avoide_penalizing->setDecorators($this->labelRight());
        $this->ass_avoide_penalizing->setDescription('Assignments');
        $this->ass_avoide_penalizing->setAttrib('acc_menu', '1');
        $this->ass_avoide_penalizing->setRequired(false);
        $this->ass_avoide_penalizing->setAllowEmpty(false);
        $this->ass_avoide_penalizing->addOnChange('otherHelper(this.value, \'*' . $this->ass_avoide_penalizing->getDescription() . ' - ' .$this->ass_avoide_penalizing->getLabel().'\')');


        $this->ass_redo_for_grade = new App_Form_Element_MultiSelect('ass_redo_for_grade',array('label'=>"Redo for better grade"));
        $this->ass_redo_for_grade->setMultiOptions($this->getSubjects());
        $this->ass_redo_for_grade->setDecorators($this->labelRight());
        $this->ass_redo_for_grade->setDescription('Assignments');
        $this->ass_redo_for_grade->setAttrib('acc_menu', '1');
        $this->ass_redo_for_grade->setRequired(false);
        $this->ass_redo_for_grade->setAllowEmpty(false);
        $this->ass_redo_for_grade->addOnChange('otherHelper(this.value, \'*' . $this->ass_redo_for_grade->getDescription() . ' - ' .$this->ass_redo_for_grade->getLabel().'\')');


        $this->ass_allo_use_resource = new App_Form_Element_MultiSelect('ass_allo_use_resource',array('label'=>"Allow student to use resource assistance when necessary"));
        $this->ass_allo_use_resource->setMultiOptions($this->getSubjects());
        $this->ass_allo_use_resource->setDecorators($this->labelRight());
        $this->ass_allo_use_resource->setDescription('Assignments');
        $this->ass_allo_use_resource->setAttrib('acc_menu', '1');
        $this->ass_allo_use_resource->setRequired(false);
        $this->ass_allo_use_resource->setAllowEmpty(false);
        $this->ass_allo_use_resource->addOnChange('otherHelper(this.value, \'*' . $this->ass_allo_use_resource->getDescription() . ' - ' .$this->ass_allo_use_resource->getLabel().'\')');


        $this->ass_provide_electronic = new App_Form_Element_MultiSelect('ass_provide_electronic',array('label'=>"Provide assignments in an electronic format"));
        $this->ass_provide_electronic->setMultiOptions($this->getSubjects());
        $this->ass_provide_electronic->setDecorators($this->labelRight());
        $this->ass_provide_electronic->setDescription('Assignments');
        $this->ass_provide_electronic->setAttrib('acc_menu', '1');
        $this->ass_provide_electronic->setRequired(false);
        $this->ass_provide_electronic->setAllowEmpty(false);
        $this->ass_provide_electronic->addOnChange('otherHelper(this.value, \'*' . $this->ass_provide_electronic->getDescription() . ' - ' .$this->ass_provide_electronic->getLabel().'\')');


        $this->ass_other = new App_Form_Element_MultiSelect('ass_other',array('label'=>"Other"));
        $this->ass_other->setMultiOptions($this->getSubjects());
        $this->ass_other->setDecorators($this->labelRight());
        $this->ass_other->setDescription('Assignments');
        $this->ass_other->setAttrib('acc_menu', '1');
        $this->ass_other->setRequired(false);
        $this->ass_other->setAllowEmpty(false);
        $this->ass_other->addOnChange('otherHelper(this.value, \'*' . $this->ass_other->getDescription() . ' - ' .$this->ass_other->getLabel().'\')');



        $this->soc_perr_tutoring = new App_Form_Element_MultiSelect('soc_perr_tutoring',array('label'=>"Peer tutoring"));
        $this->soc_perr_tutoring->setMultiOptions($this->getSubjects());
        $this->soc_perr_tutoring->setDecorators($this->labelRight());
        $this->soc_perr_tutoring->setDescription('Motivation & Reinforcement');
        $this->soc_perr_tutoring->setAttrib('acc_menu', '1');
        $this->soc_perr_tutoring->setRequired(false);
        $this->soc_perr_tutoring->setAllowEmpty(false);
        $this->soc_perr_tutoring->addOnChange('otherHelper(this.value, \'*' . $this->soc_perr_tutoring->getDescription() . ' - ' .$this->soc_perr_tutoring->getLabel().'\')');


        $this->mot_nonverbal = new App_Form_Element_MultiSelect('mot_nonverbal',array('label'=>"Nonverbal"));
        $this->mot_nonverbal->setMultiOptions($this->getSubjects());
        $this->mot_nonverbal->setDecorators($this->labelRight());
        $this->mot_nonverbal->setDescription('Motivation & Reinforcement');
        $this->mot_nonverbal->setAttrib('acc_menu', '1');
        $this->mot_nonverbal->setRequired(false);
        $this->mot_nonverbal->setAllowEmpty(false);
        $this->mot_nonverbal->addOnChange('otherHelper(this.value, \'*' . $this->mot_nonverbal->getDescription() . ' - ' .$this->mot_nonverbal->getLabel().'\')');


        $this->mot_positive_reinforcement = new App_Form_Element_MultiSelect('mot_positive_reinforcement',array('label'=>"Positive reinforcement"));
        $this->mot_positive_reinforcement->setMultiOptions($this->getSubjects());
        $this->mot_positive_reinforcement->setDecorators($this->labelRight());
        $this->mot_positive_reinforcement->setDescription('Motivation & Reinforcement');
        $this->mot_positive_reinforcement->setAttrib('acc_menu', '1');
        $this->mot_positive_reinforcement->setRequired(false);
        $this->mot_positive_reinforcement->setAllowEmpty(false);
        $this->mot_positive_reinforcement->addOnChange('otherHelper(this.value, \'*' . $this->mot_positive_reinforcement->getDescription() . ' - ' .$this->mot_positive_reinforcement->getLabel().'\')');


        $this->mot_concrete_reinforcement = new App_Form_Element_MultiSelect('mot_concrete_reinforcement',array('label'=>"Concrete reinforcement"));
        $this->mot_concrete_reinforcement->setMultiOptions($this->getSubjects());
        $this->mot_concrete_reinforcement->setDecorators($this->labelRight());
        $this->mot_concrete_reinforcement->setDescription('Motivation & Reinforcement');
        $this->mot_concrete_reinforcement->setAttrib('acc_menu', '1');
        $this->mot_concrete_reinforcement->setRequired(false);
        $this->mot_concrete_reinforcement->setAllowEmpty(false);
        $this->mot_concrete_reinforcement->addOnChange('otherHelper(this.value, \'*' . $this->mot_concrete_reinforcement->getDescription() . ' - ' .$this->mot_concrete_reinforcement->getLabel().'\')');


        $this->mot_offer_choice = new App_Form_Element_MultiSelect('mot_offer_choice',array('label'=>"Offer choice"));
        $this->mot_offer_choice->setMultiOptions($this->getSubjects());
        $this->mot_offer_choice->setDecorators($this->labelRight());
        $this->mot_offer_choice->setDescription('Motivation & Reinforcement');
        $this->mot_offer_choice->setAttrib('acc_menu', '1');
        $this->mot_offer_choice->setRequired(false);
        $this->mot_offer_choice->setAllowEmpty(false);
        $this->mot_offer_choice->addOnChange('otherHelper(this.value, \'*' . $this->mot_offer_choice->getDescription() . ' - ' .$this->mot_offer_choice->getLabel().'\')');


        $this->mot_use_strengths_often = new App_Form_Element_MultiSelect('mot_use_strengths_often',array('label'=>"Use strengths/Interests often"));
        $this->mot_use_strengths_often->setMultiOptions($this->getSubjects());
        $this->mot_use_strengths_often->setDecorators($this->labelRight());
        $this->mot_use_strengths_often->setDescription('Motivation & Reinforcement');
        $this->mot_use_strengths_often->setAttrib('acc_menu', '1');
        $this->mot_use_strengths_often->setRequired(false);
        $this->mot_use_strengths_often->setAllowEmpty(false);
        $this->mot_use_strengths_often->addOnChange('otherHelper(this.value, \'*' . $this->mot_use_strengths_often->getDescription() . ' - ' .$this->mot_use_strengths_often->getLabel().'\')');


        $this->mot_allow_movement = new App_Form_Element_MultiSelect('mot_allow_movement',array('label'=>"Allow in-class movement"));
        $this->mot_allow_movement->setMultiOptions($this->getSubjects());
        $this->mot_allow_movement->setDecorators($this->labelRight());
        $this->mot_allow_movement->setDescription('Motivation & Reinforcement');
        $this->mot_allow_movement->setAttrib('acc_menu', '1');
        $this->mot_allow_movement->setRequired(false);
        $this->mot_allow_movement->setAllowEmpty(false);
        $this->mot_allow_movement->addOnChange('otherHelper(this.value, \'*' . $this->mot_allow_movement->getDescription() . ' - ' .$this->mot_allow_movement->getLabel().'\')');


        $this->mot_increase_rewards = new App_Form_Element_MultiSelect('mot_increase_rewards',array('label'=>"Increase immediacy of rewards"));
        $this->mot_increase_rewards->setMultiOptions($this->getSubjects());
        $this->mot_increase_rewards->setDecorators($this->labelRight());
        $this->mot_increase_rewards->setDescription('Motivation & Reinforcement');
        $this->mot_increase_rewards->setAttrib('acc_menu', '1');
        $this->mot_increase_rewards->setRequired(false);
        $this->mot_increase_rewards->setAllowEmpty(false);
        $this->mot_increase_rewards->addOnChange('otherHelper(this.value, \'*' . $this->mot_increase_rewards->getDescription() . ' - ' .$this->mot_increase_rewards->getLabel().'\')');


        $this->mot_use_contracts = new App_Form_Element_MultiSelect('mot_use_contracts',array('label'=>"Use behavioral contracts"));
        $this->mot_use_contracts->setMultiOptions($this->getSubjects());
        $this->mot_use_contracts->setDecorators($this->labelRight());
        $this->mot_use_contracts->setDescription('Motivation & Reinforcement');
        $this->mot_use_contracts->setAttrib('acc_menu', '1');
        $this->mot_use_contracts->setRequired(false);
        $this->mot_use_contracts->setAllowEmpty(false);
        $this->mot_use_contracts->addOnChange('otherHelper(this.value, \'*' . $this->mot_use_contracts->getDescription() . ' - ' .$this->mot_use_contracts->getLabel().'\')');


        $this->mot_other = new App_Form_Element_MultiSelect('mot_other',array('label'=>"Other"));
        $this->mot_other->setMultiOptions($this->getSubjects());
        $this->mot_other->setDecorators($this->labelRight());
        $this->mot_other->setDescription('Motivation & Reinforcement');
        $this->mot_other->setAttrib('acc_menu', '1');
        $this->mot_other->setRequired(false);
        $this->mot_other->setAllowEmpty(false);
        $this->mot_other->addOnChange('otherHelper(this.value, \'*' . $this->mot_other->getDescription() . ' - ' .$this->mot_other->getLabel().'\')');



        $this->soc_peer_advocacy = new App_Form_Element_MultiSelect('soc_peer_advocacy',array('label'=>"Peer advocacy"));
        $this->soc_peer_advocacy->setMultiOptions($this->getSubjects());
        $this->soc_peer_advocacy->setDecorators($this->labelRight());
        $this->soc_peer_advocacy->setDescription('Social Interaction Support');
        $this->soc_peer_advocacy->setAttrib('acc_menu', '1');
        $this->soc_peer_advocacy->setRequired(false);
        $this->soc_peer_advocacy->setAllowEmpty(false);
        $this->soc_peer_advocacy->addOnChange('otherHelper(this.value, \'*' . $this->soc_peer_advocacy->getDescription() . ' - ' .$this->soc_peer_advocacy->getLabel().'\')');


        $this->soc_perr_tutoring = new App_Form_Element_MultiSelect('soc_perr_tutoring',array('label'=>"Peer tutoring"));
        $this->soc_perr_tutoring->setMultiOptions($this->getSubjects());
        $this->soc_perr_tutoring->setDecorators($this->labelRight());
        $this->soc_perr_tutoring->setDescription('Social Interaction Support');
        $this->soc_perr_tutoring->setAttrib('acc_menu', '1');
        $this->soc_perr_tutoring->setRequired(false);
        $this->soc_perr_tutoring->setAllowEmpty(false);
        $this->soc_perr_tutoring->addOnChange('otherHelper(this.value, \'*' . $this->soc_perr_tutoring->getDescription() . ' - ' .$this->soc_perr_tutoring->getLabel().'\')');


        $this->soc_structure_activities = new App_Form_Element_MultiSelect('soc_structure_activities',array('label'=>"Structure activities to create or<BR>- discourage opportunities of social interaction"));
        $this->soc_structure_activities->setMultiOptions($this->getSubjects());
        $this->soc_structure_activities->setDecorators($this->labelRight());
        $this->soc_structure_activities->setDescription('Social Interaction Support');
        $this->soc_structure_activities->setAttrib('acc_menu', '1');
        $this->soc_structure_activities->setRequired(false);
        $this->soc_structure_activities->setAllowEmpty(false);
        $this->soc_structure_activities->addOnChange('otherHelper(this.value, \'*' . $this->soc_structure_activities->getDescription() . ' - ' .$this->soc_structure_activities->getLabel().'\')');


        $this->soc_social_process = new App_Form_Element_MultiSelect('soc_social_process',array('label'=>"Focus on social process rather than activity"));
        $this->soc_social_process->setMultiOptions($this->getSubjects());
        $this->soc_social_process->setDecorators($this->labelRight());
        $this->soc_social_process->setDescription('Social Interaction Support');
        $this->soc_social_process->setAttrib('acc_menu', '1');
        $this->soc_social_process->setRequired(false);
        $this->soc_social_process->setAllowEmpty(false);
        $this->soc_social_process->addOnChange('otherHelper(this.value, \'*' . $this->soc_social_process->getDescription() . ' - ' .$this->soc_social_process->getLabel().'\')');


        $this->soc_shared_experience = new App_Form_Element_MultiSelect('soc_shared_experience',array('label'=>"Structure shared experience in school"));
        $this->soc_shared_experience->setMultiOptions($this->getSubjects());
        $this->soc_shared_experience->setDecorators($this->labelRight());
        $this->soc_shared_experience->setDescription('Social Interaction Support');
        $this->soc_shared_experience->setAttrib('acc_menu', '1');
        $this->soc_shared_experience->setRequired(false);
        $this->soc_shared_experience->setAllowEmpty(false);
        $this->soc_shared_experience->addOnChange('otherHelper(this.value, \'*' . $this->soc_shared_experience->getDescription() . ' - ' .$this->soc_shared_experience->getLabel().'\')');


        $this->soc_coop_learning_groups = new App_Form_Element_MultiSelect('soc_coop_learning_groups',array('label'=>"Cooperative learning groups"));
        $this->soc_coop_learning_groups->setMultiOptions($this->getSubjects());
        $this->soc_coop_learning_groups->setDecorators($this->labelRight());
        $this->soc_coop_learning_groups->setDescription('Social Interaction Support');
        $this->soc_coop_learning_groups->setAttrib('acc_menu', '1');
        $this->soc_coop_learning_groups->setRequired(false);
        $this->soc_coop_learning_groups->setAllowEmpty(false);
        $this->soc_coop_learning_groups->addOnChange('otherHelper(this.value, \'*' . $this->soc_coop_learning_groups->getDescription() . ' - ' .$this->soc_coop_learning_groups->getLabel().'\')');


        $this->soc_multiple_peers = new App_Form_Element_MultiSelect('soc_multiple_peers',array('label'=>"Use multiple/rotating peers"));
        $this->soc_multiple_peers->setMultiOptions($this->getSubjects());
        $this->soc_multiple_peers->setDecorators($this->labelRight());
        $this->soc_multiple_peers->setDescription('Social Interaction Support');
        $this->soc_multiple_peers->setAttrib('acc_menu', '1');
        $this->soc_multiple_peers->setRequired(false);
        $this->soc_multiple_peers->setAllowEmpty(false);
        $this->soc_multiple_peers->addOnChange('otherHelper(this.value, \'*' . $this->soc_multiple_peers->getDescription() . ' - ' .$this->soc_multiple_peers->getLabel().'\')');


        $this->soc_teach_friendship = new App_Form_Element_MultiSelect('soc_teach_friendship',array('label'=>"Teach friendship<BR>- skills/sharing/negotiations"));
        $this->soc_teach_friendship->setMultiOptions($this->getSubjects());
        $this->soc_teach_friendship->setDecorators($this->labelRight());
        $this->soc_teach_friendship->setDescription('Social Interaction Support');
        $this->soc_teach_friendship->setAttrib('acc_menu', '1');
        $this->soc_teach_friendship->setRequired(false);
        $this->soc_teach_friendship->setAllowEmpty(false);
        $this->soc_teach_friendship->addOnChange('otherHelper(this.value, \'*' . $this->soc_teach_friendship->getDescription() . ' - ' .$this->soc_teach_friendship->getLabel().'\')');


        $this->soc_teach_social_com = new App_Form_Element_MultiSelect('soc_teach_social_com',array('label'=>"Teach social communications skills<BR>- Greetings<BR>- Negotiations<BR>- Turn taking <BR>- Sharing"));
        $this->soc_teach_social_com->setMultiOptions($this->getSubjects());
        $this->soc_teach_social_com->setDecorators($this->labelRight());
        $this->soc_teach_social_com->setDescription('Social Interaction Support');
        $this->soc_teach_social_com->setAttrib('acc_menu', '1');
        $this->soc_teach_social_com->setRequired(false);
        $this->soc_teach_social_com->setAllowEmpty(false);
        $this->soc_teach_social_com->addOnChange('otherHelper(this.value, \'*' . $this->soc_teach_social_com->getDescription() . ' - ' .$this->soc_teach_social_com->getLabel().'\')');


        $this->soc_other = new App_Form_Element_MultiSelect('soc_other',array('label'=>"Other"));
        $this->soc_other->setMultiOptions($this->getSubjects());
        $this->soc_other->setDecorators($this->labelRight());
        $this->soc_other->setDescription('Social Interaction Support');
        $this->soc_other->setAttrib('acc_menu', '1');
        $this->soc_other->setRequired(false);
        $this->soc_other->setAllowEmpty(false);
        $this->soc_other->addOnChange('otherHelper(this.value, \'*' . $this->soc_other->getDescription() . ' - ' .$this->soc_other->getLabel().'\')');



        $this->self_man_pos_reinforcement = new App_Form_Element_MultiSelect('self_man_pos_reinforcement',array('label'=>"Use positive reinforcement"));
        $this->self_man_pos_reinforcement->setMultiOptions($this->getSubjects());
        $this->self_man_pos_reinforcement->setDecorators($this->labelRight());
        $this->self_man_pos_reinforcement->setDescription('Self Management/Follow-through');
        $this->self_man_pos_reinforcement->setAttrib('acc_menu', '1');
        $this->self_man_pos_reinforcement->setRequired(false);
        $this->self_man_pos_reinforcement->setAllowEmpty(false);
        $this->self_man_pos_reinforcement->addOnChange('otherHelper(this.value, \'*' . $this->self_man_pos_reinforcement->getDescription() . ' - ' .$this->self_man_pos_reinforcement->getLabel().'\')');


        $this->self_man_con_reinforcement = new App_Form_Element_MultiSelect('self_man_con_reinforcement',array('label'=>"Use concrete reinforcement"));
        $this->self_man_con_reinforcement->setMultiOptions($this->getSubjects());
        $this->self_man_con_reinforcement->setDecorators($this->labelRight());
        $this->self_man_con_reinforcement->setDescription('Self Management/Follow-through');
        $this->self_man_con_reinforcement->setAttrib('acc_menu', '1');
        $this->self_man_con_reinforcement->setRequired(false);
        $this->self_man_con_reinforcement->setAllowEmpty(false);
        $this->self_man_con_reinforcement->addOnChange('otherHelper(this.value, \'*' . $this->self_man_con_reinforcement->getDescription() . ' - ' .$this->self_man_con_reinforcement->getLabel().'\')');


        $this->self_man_understand_review = new App_Form_Element_MultiSelect('self_man_understand_review',array('label'=>"Check often for understanding review<BR>- copying, if timed"));
        $this->self_man_understand_review->setMultiOptions($this->getSubjects());
        $this->self_man_understand_review->setDecorators($this->labelRight());
        $this->self_man_understand_review->setDescription('Self Management/Follow-through');
        $this->self_man_understand_review->setAttrib('acc_menu', '1');
        $this->self_man_understand_review->setRequired(false);
        $this->self_man_understand_review->setAllowEmpty(false);
        $this->self_man_understand_review->addOnChange('otherHelper(this.value, \'*' . $this->self_man_understand_review->getDescription() . ' - ' .$this->self_man_understand_review->getLabel().'\')');


        $this->self_man_peer_tutoring = new App_Form_Element_MultiSelect('self_man_peer_tutoring',array('label'=>"Peer tutoring"));
        $this->self_man_peer_tutoring->setMultiOptions($this->getSubjects());
        $this->self_man_peer_tutoring->setDecorators($this->labelRight());
        $this->self_man_peer_tutoring->setDescription('Self Management/Follow-through');
        $this->self_man_peer_tutoring->setAttrib('acc_menu', '1');
        $this->self_man_peer_tutoring->setRequired(false);
        $this->self_man_peer_tutoring->setAllowEmpty(false);
        $this->self_man_peer_tutoring->addOnChange('otherHelper(this.value, \'*' . $this->self_man_peer_tutoring->getDescription() . ' - ' .$this->self_man_peer_tutoring->getLabel().'\')');


        $this->self_man_req_par_reinforcement = new App_Form_Element_MultiSelect('self_man_req_par_reinforcement',array('label'=>"Request parent reinforcement"));
        $this->self_man_req_par_reinforcement->setMultiOptions($this->getSubjects());
        $this->self_man_req_par_reinforcement->setDecorators($this->labelRight());
        $this->self_man_req_par_reinforcement->setDescription('Self Management/Follow-through');
        $this->self_man_req_par_reinforcement->setAttrib('acc_menu', '1');
        $this->self_man_req_par_reinforcement->setRequired(false);
        $this->self_man_req_par_reinforcement->setAllowEmpty(false);
        $this->self_man_req_par_reinforcement->addOnChange('otherHelper(this.value, \'*' . $this->self_man_req_par_reinforcement->getDescription() . ' - ' .$this->self_man_req_par_reinforcement->getLabel().'\')');


        $this->self_man_repeat_directions = new App_Form_Element_MultiSelect('self_man_repeat_directions',array('label'=>"Have student repeat directions"));
        $this->self_man_repeat_directions->setMultiOptions($this->getSubjects());
        $this->self_man_repeat_directions->setDecorators($this->labelRight());
        $this->self_man_repeat_directions->setDescription('Self Management/Follow-through');
        $this->self_man_repeat_directions->setAttrib('acc_menu', '1');
        $this->self_man_repeat_directions->setRequired(false);
        $this->self_man_repeat_directions->setAllowEmpty(false);
        $this->self_man_repeat_directions->addOnChange('otherHelper(this.value, \'*' . $this->self_man_repeat_directions->getDescription() . ' - ' .$this->self_man_repeat_directions->getLabel().'\')');


        $this->self_man_voc_files = new App_Form_Element_MultiSelect('self_man_voc_files',array('label'=>"Make/Use vocabulary files"));
        $this->self_man_voc_files->setMultiOptions($this->getSubjects());
        $this->self_man_voc_files->setDecorators($this->labelRight());
        $this->self_man_voc_files->setDescription('Self Management/Follow-through');
        $this->self_man_voc_files->setAttrib('acc_menu', '1');
        $this->self_man_voc_files->setRequired(false);
        $this->self_man_voc_files->setAllowEmpty(false);
        $this->self_man_voc_files->addOnChange('otherHelper(this.value, \'*' . $this->self_man_voc_files->getDescription() . ' - ' .$this->self_man_voc_files->getLabel().'\')');


        $this->self_man_teach_study_skills = new App_Form_Element_MultiSelect('self_man_teach_study_skills',array('label'=>"Teach study skills"));
        $this->self_man_teach_study_skills->setMultiOptions($this->getSubjects());
        $this->self_man_teach_study_skills->setDecorators($this->labelRight());
        $this->self_man_teach_study_skills->setDescription('Self Management/Follow-through');
        $this->self_man_teach_study_skills->setAttrib('acc_menu', '1');
        $this->self_man_teach_study_skills->setRequired(false);
        $this->self_man_teach_study_skills->setAllowEmpty(false);
        $this->self_man_teach_study_skills->addOnChange('otherHelper(this.value, \'*' . $this->self_man_teach_study_skills->getDescription() . ' - ' .$this->self_man_teach_study_skills->getLabel().'\')');


        $this->self_man_study_sheets = new App_Form_Element_MultiSelect('self_man_study_sheets',array('label'=>"Use study sheets to organize material"));
        $this->self_man_study_sheets->setMultiOptions($this->getSubjects());
        $this->self_man_study_sheets->setDecorators($this->labelRight());
        $this->self_man_study_sheets->setDescription('Self Management/Follow-through');
        $this->self_man_study_sheets->setAttrib('acc_menu', '1');
        $this->self_man_study_sheets->setRequired(false);
        $this->self_man_study_sheets->setAllowEmpty(false);
        $this->self_man_study_sheets->addOnChange('otherHelper(this.value, \'*' . $this->self_man_study_sheets->getDescription() . ' - ' .$this->self_man_study_sheets->getLabel().'\')');


        $this->self_man_long_term_assign = new App_Form_Element_MultiSelect('self_man_long_term_assign',array('label'=>"Long term assignment time lines"));
        $this->self_man_long_term_assign->setMultiOptions($this->getSubjects());
        $this->self_man_long_term_assign->setDecorators($this->labelRight());
        $this->self_man_long_term_assign->setDescription('Self Management/Follow-through');
        $this->self_man_long_term_assign->setAttrib('acc_menu', '1');
        $this->self_man_long_term_assign->setRequired(false);
        $this->self_man_long_term_assign->setAllowEmpty(false);
        $this->self_man_long_term_assign->addOnChange('otherHelper(this.value, \'*' . $this->self_man_long_term_assign->getDescription() . ' - ' .$this->self_man_long_term_assign->getLabel().'\')');


        $this->self_man_repeated_review = new App_Form_Element_MultiSelect('self_man_repeated_review',array('label'=>"Repeated review/drill"));
        $this->self_man_repeated_review->setMultiOptions($this->getSubjects());
        $this->self_man_repeated_review->setDecorators($this->labelRight());
        $this->self_man_repeated_review->setDescription('Self Management/Follow-through');
        $this->self_man_repeated_review->setAttrib('acc_menu', '1');
        $this->self_man_repeated_review->setRequired(false);
        $this->self_man_repeated_review->setAllowEmpty(false);
        $this->self_man_repeated_review->addOnChange('otherHelper(this.value, \'*' . $this->self_man_repeated_review->getDescription() . ' - ' .$this->self_man_repeated_review->getLabel().'\')');


        $this->self_man_behavior_manage = new App_Form_Element_MultiSelect('self_man_behavior_manage',array('label'=>"Behavior management system"));
        $this->self_man_behavior_manage->setMultiOptions($this->getSubjects());
        $this->self_man_behavior_manage->setDecorators($this->labelRight());
        $this->self_man_behavior_manage->setDescription('Self Management/Follow-through');
        $this->self_man_behavior_manage->setAttrib('acc_menu', '1');
        $this->self_man_behavior_manage->setRequired(false);
        $this->self_man_behavior_manage->setAllowEmpty(false);
        $this->self_man_behavior_manage->addOnChange('otherHelper(this.value, \'*' . $this->self_man_behavior_manage->getDescription() . ' - ' .$this->self_man_behavior_manage->getLabel().'\')');


        $this->self_man_daily_schedule = new App_Form_Element_MultiSelect('self_man_daily_schedule',array('label'=>"Visual daily schedule"));
        $this->self_man_daily_schedule->setMultiOptions($this->getSubjects());
        $this->self_man_daily_schedule->setDecorators($this->labelRight());
        $this->self_man_daily_schedule->setDescription('Self Management/Follow-through');
        $this->self_man_daily_schedule->setAttrib('acc_menu', '1');
        $this->self_man_daily_schedule->setRequired(false);
        $this->self_man_daily_schedule->setAllowEmpty(false);
        $this->self_man_daily_schedule->addOnChange('otherHelper(this.value, \'*' . $this->self_man_daily_schedule->getDescription() . ' - ' .$this->self_man_daily_schedule->getLabel().'\')');


        $this->self_man_assignment_book = new App_Form_Element_MultiSelect('self_man_assignment_book',array('label'=>"Calendar/Assignment Book"));
        $this->self_man_assignment_book->setMultiOptions($this->getSubjects());
        $this->self_man_assignment_book->setDecorators($this->labelRight());
        $this->self_man_assignment_book->setDescription('Self Management/Follow-through');
        $this->self_man_assignment_book->setAttrib('acc_menu', '1');
        $this->self_man_assignment_book->setRequired(false);
        $this->self_man_assignment_book->setAllowEmpty(false);
        $this->self_man_assignment_book->addOnChange('otherHelper(this.value, \'*' . $this->self_man_assignment_book->getDescription() . ' - ' .$this->self_man_assignment_book->getLabel().'\')');


        $this->self_man_plan_general = new App_Form_Element_MultiSelect('self_man_plan_general',array('label'=>"Plan for generalizations"));
        $this->self_man_plan_general->setMultiOptions($this->getSubjects());
        $this->self_man_plan_general->setDecorators($this->labelRight());
        $this->self_man_plan_general->setDescription('Self Management/Follow-through');
        $this->self_man_plan_general->setAttrib('acc_menu', '1');
        $this->self_man_plan_general->setRequired(false);
        $this->self_man_plan_general->setAllowEmpty(false);
        $this->self_man_plan_general->addOnChange('otherHelper(this.value, \'*' . $this->self_man_plan_general->getDescription() . ' - ' .$this->self_man_plan_general->getLabel().'\')');


        $this->self_man_teach_skill_sev = new App_Form_Element_MultiSelect('self_man_teach_skill_sev',array('label'=>"Teach skill in several<BR>- settings/environments"));
        $this->self_man_teach_skill_sev->setMultiOptions($this->getSubjects());
        $this->self_man_teach_skill_sev->setDecorators($this->labelRight());
        $this->self_man_teach_skill_sev->setDescription('Self Management/Follow-through');
        $this->self_man_teach_skill_sev->setAttrib('acc_menu', '1');
        $this->self_man_teach_skill_sev->setRequired(false);
        $this->self_man_teach_skill_sev->setAllowEmpty(false);
        $this->self_man_teach_skill_sev->addOnChange('otherHelper(this.value, \'*' . $this->self_man_teach_skill_sev->getDescription() . ' - ' .$this->self_man_teach_skill_sev->getLabel().'\')');


        $this->self_man_redo_assignment = new App_Form_Element_MultiSelect('self_man_redo_assignment',array('label'=>"Redo assignment for a better grade"));
        $this->self_man_redo_assignment->setMultiOptions($this->getSubjects());
        $this->self_man_redo_assignment->setDecorators($this->labelRight());
        $this->self_man_redo_assignment->setDescription('Self Management/Follow-through');
        $this->self_man_redo_assignment->setAttrib('acc_menu', '1');
        $this->self_man_redo_assignment->setRequired(false);
        $this->self_man_redo_assignment->setAllowEmpty(false);
        $this->self_man_redo_assignment->addOnChange('otherHelper(this.value, \'*' . $this->self_man_redo_assignment->getDescription() . ' - ' .$this->self_man_redo_assignment->getLabel().'\')');


        $this->self_man_other = new App_Form_Element_MultiSelect('self_man_other',array('label'=>"Other"));
        $this->self_man_other->setMultiOptions($this->getSubjects());
        $this->self_man_other->setDecorators($this->labelRight());
        $this->self_man_other->setDescription('Self Management/Follow-through');
        $this->self_man_other->setAttrib('acc_menu', '1');
        $this->self_man_other->setRequired(false);
        $this->self_man_other->setAllowEmpty(false);
        $this->self_man_other->addOnChange('otherHelper(this.value, \'*' . $this->self_man_other->getDescription() . ' - ' .$this->self_man_other->getLabel().'\')');



        $this->testing_extended_time = new App_Form_Element_MultiSelect('testing_extended_time',array('label'=>"Provide extended time"));
        $this->testing_extended_time->setMultiOptions($this->getSubjects());
        $this->testing_extended_time->setDecorators($this->labelRight());
        $this->testing_extended_time->setDescription('Testing Accommodations');
        $this->testing_extended_time->setAttrib('acc_menu', '1');
        $this->testing_extended_time->setRequired(false);
        $this->testing_extended_time->setAllowEmpty(false);
        $this->testing_extended_time->addOnChange('otherHelper(this.value, \'*' . $this->testing_extended_time->getDescription() . ' - ' .$this->testing_extended_time->getLabel().'\')');


        $this->testing_oral = new App_Form_Element_MultiSelect('testing_oral',array('label'=>"Allowed to answer questions orally"));
        $this->testing_oral->setMultiOptions($this->getSubjects());
        $this->testing_oral->setDecorators($this->labelRight());
        $this->testing_oral->setDescription('Testing Accommodations');
        $this->testing_oral->setAttrib('acc_menu', '1');
        $this->testing_oral->setRequired(false);
        $this->testing_oral->setAllowEmpty(false);
        $this->testing_oral->addOnChange('otherHelper(this.value, \'*' . $this->testing_oral->getDescription() . ' - ' .$this->testing_oral->getLabel().'\')');


        $this->testing_short_ans = new App_Form_Element_MultiSelect('testing_short_ans',array('label'=>"Allow for short answer format"));
        $this->testing_short_ans->setMultiOptions($this->getSubjects());
        $this->testing_short_ans->setDecorators($this->labelRight());
        $this->testing_short_ans->setDescription('Testing Accommodations');
        $this->testing_short_ans->setAttrib('acc_menu', '1');
        $this->testing_short_ans->setRequired(false);
        $this->testing_short_ans->setAllowEmpty(false);
        $this->testing_short_ans->addOnChange('otherHelper(this.value, \'*' . $this->testing_short_ans->getDescription() . ' - ' .$this->testing_short_ans->getLabel().'\')');


        $this->testing_taped = new App_Form_Element_MultiSelect('testing_taped',array('label'=>"Taped"));
        $this->testing_taped->setMultiOptions($this->getSubjects());
        $this->testing_taped->setDecorators($this->labelRight());
        $this->testing_taped->setDescription('Testing Accommodations');
        $this->testing_taped->setAttrib('acc_menu', '1');
        $this->testing_taped->setRequired(false);
        $this->testing_taped->setAllowEmpty(false);
        $this->testing_taped->addOnChange('otherHelper(this.value, \'*' . $this->testing_taped->getDescription() . ' - ' .$this->testing_taped->getLabel().'\')');


        $this->testing_mult_choice = new App_Form_Element_MultiSelect('testing_mult_choice',array('label'=>"Utilize multiple choice"));
        $this->testing_mult_choice->setMultiOptions($this->getSubjects());
        $this->testing_mult_choice->setDecorators($this->labelRight());
        $this->testing_mult_choice->setDescription('Testing Accommodations');
        $this->testing_mult_choice->setAttrib('acc_menu', '1');
        $this->testing_mult_choice->setRequired(false);
        $this->testing_mult_choice->setAllowEmpty(false);
        $this->testing_mult_choice->addOnChange('otherHelper(this.value, \'*' . $this->testing_mult_choice->getDescription() . ' - ' .$this->testing_mult_choice->getLabel().'\')');


        $this->testing_read_test = new App_Form_Element_MultiSelect('testing_read_test',array('label'=>"Read test to student"));
        $this->testing_read_test->setMultiOptions($this->getSubjects());
        $this->testing_read_test->setDecorators($this->labelRight());
        $this->testing_read_test->setDescription('Testing Accommodations');
        $this->testing_read_test->setAttrib('acc_menu', '1');
        $this->testing_read_test->setRequired(false);
        $this->testing_read_test->setAllowEmpty(false);
        $this->testing_read_test->addOnChange('otherHelper(this.value, \'*' . $this->testing_read_test->getDescription() . ' - ' .$this->testing_read_test->getLabel().'\')');


        $this->testing_mod_format = new App_Form_Element_MultiSelect('testing_mod_format',array('label'=>"Modify format"));
        $this->testing_mod_format->setMultiOptions($this->getSubjects());
        $this->testing_mod_format->setDecorators($this->labelRight());
        $this->testing_mod_format->setDescription('Testing Accommodations');
        $this->testing_mod_format->setAttrib('acc_menu', '1');
        $this->testing_mod_format->setRequired(false);
        $this->testing_mod_format->setAllowEmpty(false);
        $this->testing_mod_format->addOnChange('otherHelper(this.value, \'*' . $this->testing_mod_format->getDescription() . ' - ' .$this->testing_mod_format->getLabel().'\')');


        $this->testing_shorten_length = new App_Form_Element_MultiSelect('testing_shorten_length',array('label'=>"Shorten length"));
        $this->testing_shorten_length->setMultiOptions($this->getSubjects());
        $this->testing_shorten_length->setDecorators($this->labelRight());
        $this->testing_shorten_length->setDescription('Testing Accommodations');
        $this->testing_shorten_length->setAttrib('acc_menu', '1');
        $this->testing_shorten_length->setRequired(false);
        $this->testing_shorten_length->setAllowEmpty(false);
        $this->testing_shorten_length->addOnChange('otherHelper(this.value, \'*' . $this->testing_shorten_length->getDescription() . ' - ' .$this->testing_shorten_length->getLabel().'\')');


        $this->testing_sign_directions = new App_Form_Element_MultiSelect('testing_sign_directions',array('label'=>"Sign test directions"));
        $this->testing_sign_directions->setMultiOptions($this->getSubjects());
        $this->testing_sign_directions->setDecorators($this->labelRight());
        $this->testing_sign_directions->setDescription('Testing Accommodations');
        $this->testing_sign_directions->setAttrib('acc_menu', '1');
        $this->testing_sign_directions->setRequired(false);
        $this->testing_sign_directions->setAllowEmpty(false);
        $this->testing_sign_directions->addOnChange('otherHelper(this.value, \'*' . $this->testing_sign_directions->getDescription() . ' - ' .$this->testing_sign_directions->getLabel().'\')');


        $this->testing_prev_lang = new App_Form_Element_MultiSelect('testing_prev_lang',array('label'=>"Preview language to test questions"));
        $this->testing_prev_lang->setMultiOptions($this->getSubjects());
        $this->testing_prev_lang->setDecorators($this->labelRight());
        $this->testing_prev_lang->setDescription('Testing Accommodations');
        $this->testing_prev_lang->setAttrib('acc_menu', '1');
        $this->testing_prev_lang->setRequired(false);
        $this->testing_prev_lang->setAllowEmpty(false);
        $this->testing_prev_lang->addOnChange('otherHelper(this.value, \'*' . $this->testing_prev_lang->getDescription() . ' - ' .$this->testing_prev_lang->getLabel().'\')');


        $this->testing_sign_test = new App_Form_Element_MultiSelect('testing_sign_test',array('label'=>"Sign test to students; student signs answers"));
        $this->testing_sign_test->setMultiOptions($this->getSubjects());
        $this->testing_sign_test->setDecorators($this->labelRight());
        $this->testing_sign_test->setDescription('Testing Accommodations');
        $this->testing_sign_test->setAttrib('acc_menu', '1');
        $this->testing_sign_test->setRequired(false);
        $this->testing_sign_test->setAllowEmpty(false);
        $this->testing_sign_test->addOnChange('otherHelper(this.value, \'*' . $this->testing_sign_test->getDescription() . ' - ' .$this->testing_sign_test->getLabel().'\')');


        $this->testing_test_admin = new App_Form_Element_MultiSelect('testing_test_admin',array('label'=>"Test administered by special services personnel"));
        $this->testing_test_admin->setMultiOptions($this->getSubjects());
        $this->testing_test_admin->setDecorators($this->labelRight());
        $this->testing_test_admin->setDescription('Testing Accommodations');
        $this->testing_test_admin->setAttrib('acc_menu', '1');
        $this->testing_test_admin->setRequired(false);
        $this->testing_test_admin->setAllowEmpty(false);
        $this->testing_test_admin->addOnChange('otherHelper(this.value, \'*' . $this->testing_test_admin->getDescription() . ' - ' .$this->testing_test_admin->getLabel().'\')');


        $this->testing_check_understand = new App_Form_Element_MultiSelect('testing_check_understand',array('label'=>"Check for understanding"));
        $this->testing_check_understand->setMultiOptions($this->getSubjects());
        $this->testing_check_understand->setDecorators($this->labelRight());
        $this->testing_check_understand->setDescription('Testing Accommodations');
        $this->testing_check_understand->setAttrib('acc_menu', '1');
        $this->testing_check_understand->setRequired(false);
        $this->testing_check_understand->setAllowEmpty(false);
        $this->testing_check_understand->addOnChange('otherHelper(this.value, \'*' . $this->testing_check_understand->getDescription() . ' - ' .$this->testing_check_understand->getLabel().'\')');


        $this->testing_provide_visual = new App_Form_Element_MultiSelect('testing_provide_visual',array('label'=>"Provide visual information/pictures"));
        $this->testing_provide_visual->setMultiOptions($this->getSubjects());
        $this->testing_provide_visual->setDecorators($this->labelRight());
        $this->testing_provide_visual->setDescription('Testing Accommodations');
        $this->testing_provide_visual->setAttrib('acc_menu', '1');
        $this->testing_provide_visual->setRequired(false);
        $this->testing_provide_visual->setAllowEmpty(false);
        $this->testing_provide_visual->addOnChange('otherHelper(this.value, \'*' . $this->testing_provide_visual->getDescription() . ' - ' .$this->testing_provide_visual->getLabel().'\')');


        $this->testing_para_test = new App_Form_Element_MultiSelect('testing_para_test',array('label'=>"Paraphrase test items"));
        $this->testing_para_test->setMultiOptions($this->getSubjects());
        $this->testing_para_test->setDecorators($this->labelRight());
        $this->testing_para_test->setDescription('Testing Accommodations');
        $this->testing_para_test->setAttrib('acc_menu', '1');
        $this->testing_para_test->setRequired(false);
        $this->testing_para_test->setAllowEmpty(false);
        $this->testing_para_test->addOnChange('otherHelper(this.value, \'*' . $this->testing_para_test->getDescription() . ' - ' .$this->testing_para_test->getLabel().'\')');


        $this->testing_utilize_writing_sys = new App_Form_Element_MultiSelect('testing_utilize_writing_sys',array('label'=>"Utilize specialized writing systems/devices"));
        $this->testing_utilize_writing_sys->setMultiOptions($this->getSubjects());
        $this->testing_utilize_writing_sys->setDecorators($this->labelRight());
        $this->testing_utilize_writing_sys->setDescription('Testing Accommodations');
        $this->testing_utilize_writing_sys->setAttrib('acc_menu', '1');
        $this->testing_utilize_writing_sys->setRequired(false);
        $this->testing_utilize_writing_sys->setAllowEmpty(false);
        $this->testing_utilize_writing_sys->addOnChange('otherHelper(this.value, \'*' . $this->testing_utilize_writing_sys->getDescription() . ' - ' .$this->testing_utilize_writing_sys->getLabel().'\')');


        $this->testing_color_coded = new App_Form_Element_MultiSelect('testing_color_coded',array('label'=>"Color-coded test"));
        $this->testing_color_coded->setMultiOptions($this->getSubjects());
        $this->testing_color_coded->setDecorators($this->labelRight());
        $this->testing_color_coded->setDescription('Testing Accommodations');
        $this->testing_color_coded->setAttrib('acc_menu', '1');
        $this->testing_color_coded->setRequired(false);
        $this->testing_color_coded->setAllowEmpty(false);
        $this->testing_color_coded->addOnChange('otherHelper(this.value, \'*' . $this->testing_color_coded->getDescription() . ' - ' .$this->testing_color_coded->getLabel().'\')');


        $this->testing_retest_options = new App_Form_Element_MultiSelect('testing_retest_options',array('label'=>"Retest after student demonstrates review of material"));
        $this->testing_retest_options->setMultiOptions($this->getSubjects());
        $this->testing_retest_options->setDecorators($this->labelRight());
        $this->testing_retest_options->setDescription('Testing Accommodations');
        $this->testing_retest_options->setAttrib('acc_menu', '1');
        $this->testing_retest_options->setRequired(false);
        $this->testing_retest_options->setAllowEmpty(false);
        $this->testing_retest_options->addOnChange('otherHelper(this.value, \'*' . $this->testing_retest_options->getDescription() . ' - ' .$this->testing_retest_options->getLabel().'\')');


        $this->testing_flash_cards = new App_Form_Element_MultiSelect('testing_flash_cards',array('label'=>"Flash cards with key points"));
        $this->testing_flash_cards->setMultiOptions($this->getSubjects());
        $this->testing_flash_cards->setDecorators($this->labelRight());
        $this->testing_flash_cards->setDescription('Testing Accommodations');
        $this->testing_flash_cards->setAttrib('acc_menu', '1');
        $this->testing_flash_cards->setRequired(false);
        $this->testing_flash_cards->setAllowEmpty(false);
        $this->testing_flash_cards->addOnChange('otherHelper(this.value, \'*' . $this->testing_flash_cards->getDescription() . ' - ' .$this->testing_flash_cards->getLabel().'\')');


        $this->testing_provide_study = new App_Form_Element_MultiSelect('testing_provide_study',array('label'=>"Provide study guides 2 to 3 days in advance"));
        $this->testing_provide_study->setMultiOptions($this->getSubjects());
        $this->testing_provide_study->setDecorators($this->labelRight());
        $this->testing_provide_study->setDescription('Testing Accommodations');
        $this->testing_provide_study->setAttrib('acc_menu', '1');
        $this->testing_provide_study->setRequired(false);
        $this->testing_provide_study->setAllowEmpty(false);
        $this->testing_provide_study->addOnChange('otherHelper(this.value, \'*' . $this->testing_provide_study->getDescription() . ' - ' .$this->testing_provide_study->getLabel().'\')');


        $this->testing_word_bank = new App_Form_Element_MultiSelect('testing_word_bank',array('label'=>"Word bank for short answer or fill in the blank questions"));
        $this->testing_word_bank->setMultiOptions($this->getSubjects());
        $this->testing_word_bank->setDecorators($this->labelRight());
        $this->testing_word_bank->setDescription('Testing Accommodations');
        $this->testing_word_bank->setAttrib('acc_menu', '1');
        $this->testing_word_bank->setRequired(false);
        $this->testing_word_bank->setAllowEmpty(false);
        $this->testing_word_bank->addOnChange('otherHelper(this.value, \'*' . $this->testing_word_bank->getDescription() . ' - ' .$this->testing_word_bank->getLabel().'\')');


        $this->testing_circle_items = new App_Form_Element_MultiSelect('testing_circle_items',array('label'=>"Circle # of items for which the student needs assistance or completed with help"));
        $this->testing_circle_items->setMultiOptions($this->getSubjects());
        $this->testing_circle_items->setDecorators($this->labelRight());
        $this->testing_circle_items->setDescription('Testing Accommodations');
        $this->testing_circle_items->setAttrib('acc_menu', '1');
        $this->testing_circle_items->setRequired(false);
        $this->testing_circle_items->setAllowEmpty(false);
        $this->testing_circle_items->addOnChange('otherHelper(this.value, \'*' . $this->testing_circle_items->getDescription() . ' - ' .$this->testing_circle_items->getLabel().'\')');


        $this->testing_correct_test = new App_Form_Element_MultiSelect('testing_correct_test',array('label'=>"Correct test items listing p.# of text"));
        $this->testing_correct_test->setMultiOptions($this->getSubjects());
        $this->testing_correct_test->setDecorators($this->labelRight());
        $this->testing_correct_test->setDescription('Testing Accommodations');
        $this->testing_correct_test->setAttrib('acc_menu', '1');
        $this->testing_correct_test->setRequired(false);
        $this->testing_correct_test->setAllowEmpty(false);
        $this->testing_correct_test->addOnChange('otherHelper(this.value, \'*' . $this->testing_correct_test->getDescription() . ' - ' .$this->testing_correct_test->getLabel().'\')');


        $this->testing_reteach_material = new App_Form_Element_MultiSelect('testing_reteach_material',array('label'=>"Re-teach and re-test material"));
        $this->testing_reteach_material->setMultiOptions($this->getSubjects());
        $this->testing_reteach_material->setDecorators($this->labelRight());
        $this->testing_reteach_material->setDescription('Testing Accommodations');
        $this->testing_reteach_material->setAttrib('acc_menu', '1');
        $this->testing_reteach_material->setRequired(false);
        $this->testing_reteach_material->setAllowEmpty(false);
        $this->testing_reteach_material->addOnChange('otherHelper(this.value, \'*' . $this->testing_reteach_material->getDescription() . ' - ' .$this->testing_reteach_material->getLabel().'\')');


        $this->testing_divide_test = new App_Form_Element_MultiSelect('testing_divide_test',array('label'=>"Divide test/assignments into smaller sections which are administered separately"));
        $this->testing_divide_test->setMultiOptions($this->getSubjects());
        $this->testing_divide_test->setDecorators($this->labelRight());
        $this->testing_divide_test->setDescription('Testing Accommodations');
        $this->testing_divide_test->setAttrib('acc_menu', '1');
        $this->testing_divide_test->setRequired(false);
        $this->testing_divide_test->setAllowEmpty(false);
        $this->testing_divide_test->addOnChange('otherHelper(this.value, \'*' . $this->testing_divide_test->getDescription() . ' - ' .$this->testing_divide_test->getLabel().'\')');


        $this->testing_use_more_objective = new App_Form_Element_MultiSelect('testing_use_more_objective',array('label'=>"Use more objective test items (Less essay)"));
        $this->testing_use_more_objective->setMultiOptions($this->getSubjects());
        $this->testing_use_more_objective->setDecorators($this->labelRight());
        $this->testing_use_more_objective->setDescription('Testing Accommodations');
        $this->testing_use_more_objective->setAttrib('acc_menu', '1');
        $this->testing_use_more_objective->setRequired(false);
        $this->testing_use_more_objective->setAllowEmpty(false);
        $this->testing_use_more_objective->addOnChange('otherHelper(this.value, \'*' . $this->testing_use_more_objective->getDescription() . ' - ' .$this->testing_use_more_objective->getLabel().'\')');


        $this->testing_provide_reminders = new App_Form_Element_MultiSelect('testing_provide_reminders',array('label'=>"Provide reminders on the test, i.e. watch math signs"));
        $this->testing_provide_reminders->setMultiOptions($this->getSubjects());
        $this->testing_provide_reminders->setDecorators($this->labelRight());
        $this->testing_provide_reminders->setDescription('Testing Accommodations');
        $this->testing_provide_reminders->setAttrib('acc_menu', '1');
        $this->testing_provide_reminders->setRequired(false);
        $this->testing_provide_reminders->setAllowEmpty(false);
        $this->testing_provide_reminders->addOnChange('otherHelper(this.value, \'*' . $this->testing_provide_reminders->getDescription() . ' - ' .$this->testing_provide_reminders->getLabel().'\')');


        $this->testing_allow_students = new App_Form_Element_MultiSelect('testing_allow_students',array('label'=>"Allow the student to refer to notes/text"));
        $this->testing_allow_students->setMultiOptions($this->getSubjects());
        $this->testing_allow_students->setDecorators($this->labelRight());
        $this->testing_allow_students->setDescription('Testing Accommodations');
        $this->testing_allow_students->setAttrib('acc_menu', '1');
        $this->testing_allow_students->setRequired(false);
        $this->testing_allow_students->setAllowEmpty(false);
        $this->testing_allow_students->addOnChange('otherHelper(this.value, \'*' . $this->testing_allow_students->getDescription() . ' - ' .$this->testing_allow_students->getLabel().'\')');


        $this->testing_other = new App_Form_Element_MultiSelect('testing_other',array('label'=>"Other"));
        $this->testing_other->setMultiOptions($this->getSubjects());
        $this->testing_other->setDecorators($this->labelRight());
        $this->testing_other->setDescription('Testing Accommodations');
        $this->testing_other->setAttrib('acc_menu', '1');
        $this->testing_other->setRequired(false);
        $this->testing_other->setAllowEmpty(false);
        $this->testing_other->addOnChange('otherHelper(this.value, \'*' . $this->testing_other->getDescription() . ' - ' .$this->testing_other->getLabel().'\')');



        $this->writing_dictate_ideas = new App_Form_Element_MultiSelect('writing_dictate_ideas',array('label'=>"Dictate ideas to a peer or an adult"));
        $this->writing_dictate_ideas->setMultiOptions($this->getSubjects());
        $this->writing_dictate_ideas->setDecorators($this->labelRight());
        $this->writing_dictate_ideas->setDescription('Writing Accommodations');
        $this->writing_dictate_ideas->setAttrib('acc_menu', '1');
        $this->writing_dictate_ideas->setRequired(false);
        $this->writing_dictate_ideas->setAllowEmpty(false);
        $this->writing_dictate_ideas->addOnChange('otherHelper(this.value, \'*' . $this->writing_dictate_ideas->getDescription() . ' - ' .$this->writing_dictate_ideas->getLabel().'\')');


        $this->writing_shorten_assignment = new App_Form_Element_MultiSelect('writing_shorten_assignment',array('label'=>"Shorten writing assignments"));
        $this->writing_shorten_assignment->setMultiOptions($this->getSubjects());
        $this->writing_shorten_assignment->setDecorators($this->labelRight());
        $this->writing_shorten_assignment->setDescription('Writing Accommodations');
        $this->writing_shorten_assignment->setAttrib('acc_menu', '1');
        $this->writing_shorten_assignment->setRequired(false);
        $this->writing_shorten_assignment->setAllowEmpty(false);
        $this->writing_shorten_assignment->addOnChange('otherHelper(this.value, \'*' . $this->writing_shorten_assignment->getDescription() . ' - ' .$this->writing_shorten_assignment->getLabel().'\')');


        $this->writing_use_tape_recorder = new App_Form_Element_MultiSelect('writing_use_tape_recorder',array('label'=>"Use a tape recorder to dictate writing"));
        $this->writing_use_tape_recorder->setMultiOptions($this->getSubjects());
        $this->writing_use_tape_recorder->setDecorators($this->labelRight());
        $this->writing_use_tape_recorder->setDescription('Writing Accommodations');
        $this->writing_use_tape_recorder->setAttrib('acc_menu', '1');
        $this->writing_use_tape_recorder->setRequired(false);
        $this->writing_use_tape_recorder->setAllowEmpty(false);
        $this->writing_use_tape_recorder->addOnChange('otherHelper(this.value, \'*' . $this->writing_use_tape_recorder->getDescription() . ' - ' .$this->writing_use_tape_recorder->getLabel().'\')');


        $this->writing_allow_computer = new App_Form_Element_MultiSelect('writing_allow_computer',array('label'=>"Allow for use of computer and special software for outlining, word-processing, spelling, and/or grammar check"));
        $this->writing_allow_computer->setMultiOptions($this->getSubjects());
        $this->writing_allow_computer->setDecorators($this->labelRight());
        $this->writing_allow_computer->setDescription('Writing Accommodations');
        $this->writing_allow_computer->setAttrib('acc_menu', '1');
        $this->writing_allow_computer->setRequired(false);
        $this->writing_allow_computer->setAllowEmpty(false);
        $this->writing_allow_computer->addOnChange('otherHelper(this.value, \'*' . $this->writing_allow_computer->getDescription() . ' - ' .$this->writing_allow_computer->getLabel().'\')');


        $this->writing_visual_rep_ideas = new App_Form_Element_MultiSelect('writing_visual_rep_ideas',array('label'=>"Allow visual representation of ideas"));
        $this->writing_visual_rep_ideas->setMultiOptions($this->getSubjects());
        $this->writing_visual_rep_ideas->setDecorators($this->labelRight());
        $this->writing_visual_rep_ideas->setDescription('Writing Accommodations');
        $this->writing_visual_rep_ideas->setAttrib('acc_menu', '1');
        $this->writing_visual_rep_ideas->setRequired(false);
        $this->writing_visual_rep_ideas->setAllowEmpty(false);
        $this->writing_visual_rep_ideas->addOnChange('otherHelper(this.value, \'*' . $this->writing_visual_rep_ideas->getDescription() . ' - ' .$this->writing_visual_rep_ideas->getLabel().'\')');


        $this->writing_provide_structure = new App_Form_Element_MultiSelect('writing_provide_structure',array('label'=>"Provide a structure for the writing"));
        $this->writing_provide_structure->setMultiOptions($this->getSubjects());
        $this->writing_provide_structure->setDecorators($this->labelRight());
        $this->writing_provide_structure->setDescription('Writing Accommodations');
        $this->writing_provide_structure->setAttrib('acc_menu', '1');
        $this->writing_provide_structure->setRequired(false);
        $this->writing_provide_structure->setAllowEmpty(false);
        $this->writing_provide_structure->addOnChange('otherHelper(this.value, \'*' . $this->writing_provide_structure->getDescription() . ' - ' .$this->writing_provide_structure->getLabel().'\')');


        $this->writing_allow_flow_chart = new App_Form_Element_MultiSelect('writing_allow_flow_chart',array('label'=>"Allow use of flow chart for organizing before the student writes"));
        $this->writing_allow_flow_chart->setMultiOptions($this->getSubjects());
        $this->writing_allow_flow_chart->setDecorators($this->labelRight());
        $this->writing_allow_flow_chart->setDescription('Writing Accommodations');
        $this->writing_allow_flow_chart->setAttrib('acc_menu', '1');
        $this->writing_allow_flow_chart->setRequired(false);
        $this->writing_allow_flow_chart->setAllowEmpty(false);
        $this->writing_allow_flow_chart->addOnChange('otherHelper(this.value, \'*' . $this->writing_allow_flow_chart->getDescription() . ' - ' .$this->writing_allow_flow_chart->getLabel().'\')');


        $this->writing_grade_content = new App_Form_Element_MultiSelect('writing_grade_content',array('label'=>"Grade on the basis of content, do not penalize for errors in mechanics, grammar, or spelling"));
        $this->writing_grade_content->setMultiOptions($this->getSubjects());
        $this->writing_grade_content->setDecorators($this->labelRight());
        $this->writing_grade_content->setDescription('Writing Accommodations');
        $this->writing_grade_content->setAttrib('acc_menu', '1');
        $this->writing_grade_content->setRequired(false);
        $this->writing_grade_content->setAllowEmpty(false);
        $this->writing_grade_content->addOnChange('otherHelper(this.value, \'*' . $this->writing_grade_content->getDescription() . ' - ' .$this->writing_grade_content->getLabel().'\')');


        $this->writing_other = new App_Form_Element_MultiSelect('writing_other',array('label'=>"Other"));
        $this->writing_other->setMultiOptions($this->getSubjects());
        $this->writing_other->setDecorators($this->labelRight());
        $this->writing_other->setDescription('Writing Accommodations');
        $this->writing_other->setAttrib('acc_menu', '1');
        $this->writing_other->setRequired(false);
        $this->writing_other->setAllowEmpty(false);
        $this->writing_other->addOnChange('otherHelper(this.value, \'*' . $this->writing_other->getDescription() . ' - ' .$this->writing_other->getLabel().'\')');



        $this->grade_pass_fail = new App_Form_Element_MultiSelect('grade_pass_fail',array('label'=>"Pass/fail"));
        $this->grade_pass_fail->setMultiOptions($this->getSubjects());
        $this->grade_pass_fail->setDecorators($this->labelRight());
        $this->grade_pass_fail->setDescription('Grading / Reporting Progress');
        $this->grade_pass_fail->setAttrib('acc_menu', '1');
        $this->grade_pass_fail->setRequired(false);
        $this->grade_pass_fail->setAllowEmpty(false);
        $this->grade_pass_fail->addOnChange('otherHelper(this.value, \'*' . $this->grade_pass_fail->getDescription() . ' - ' .$this->grade_pass_fail->getLabel().'\')');


        $this->grade_attendance = new App_Form_Element_MultiSelect('grade_attendance',array('label'=>"Attendance pass/fail"));
        $this->grade_attendance->setMultiOptions($this->getSubjects());
        $this->grade_attendance->setDecorators($this->labelRight());
        $this->grade_attendance->setDescription('Grading / Reporting Progress');
        $this->grade_attendance->setAttrib('acc_menu', '1');
        $this->grade_attendance->setRequired(false);
        $this->grade_attendance->setAllowEmpty(false);
        $this->grade_attendance->addOnChange('otherHelper(this.value, \'*' . $this->grade_attendance->getDescription() . ' - ' .$this->grade_attendance->getLabel().'\')');


        $this->grade_regular_grading = new App_Form_Element_MultiSelect('grade_regular_grading',array('label'=>"Regular grading"));
        $this->grade_regular_grading->setMultiOptions($this->getSubjects());
        $this->grade_regular_grading->setDecorators($this->labelRight());
        $this->grade_regular_grading->setDescription('Grading / Reporting Progress');
        $this->grade_regular_grading->setAttrib('acc_menu', '1');
        $this->grade_regular_grading->setRequired(false);
        $this->grade_regular_grading->setAllowEmpty(false);
        $this->grade_regular_grading->addOnChange('otherHelper(this.value, \'*' . $this->grade_regular_grading->getDescription() . ' - ' .$this->grade_regular_grading->getLabel().'\')');


        $this->grade_commensurate_effort = new App_Form_Element_MultiSelect('grade_commensurate_effort',array('label'=>"Credit given if effort is commensurate"));
        $this->grade_commensurate_effort->setMultiOptions($this->getSubjects());
        $this->grade_commensurate_effort->setDecorators($this->labelRight());
        $this->grade_commensurate_effort->setDescription('Grading / Reporting Progress');
        $this->grade_commensurate_effort->setAttrib('acc_menu', '1');
        $this->grade_commensurate_effort->setRequired(false);
        $this->grade_commensurate_effort->setAllowEmpty(false);
        $this->grade_commensurate_effort->addOnChange('otherHelper(this.value, \'*' . $this->grade_commensurate_effort->getDescription() . ' - ' .$this->grade_commensurate_effort->getLabel().'\')');


        $this->grade_modified_grading = new App_Form_Element_MultiSelect('grade_modified_grading',array('label'=>"Modified grading scale"));
        $this->grade_modified_grading->setMultiOptions($this->getSubjects());
        $this->grade_modified_grading->setDecorators($this->labelRight());
        $this->grade_modified_grading->setDescription('Grading / Reporting Progress');
        $this->grade_modified_grading->setAttrib('acc_menu', '1');
        $this->grade_modified_grading->setRequired(false);
        $this->grade_modified_grading->setAllowEmpty(false);
        $this->grade_modified_grading->addOnChange('otherHelper(this.value, \'*' . $this->grade_modified_grading->getDescription() . ' - ' .$this->grade_modified_grading->getLabel().'\')');


        $this->grade_oral_presentation = new App_Form_Element_MultiSelect('grade_oral_presentation',array('label'=>"Student given credit for oral presentation"));
        $this->grade_oral_presentation->setMultiOptions($this->getSubjects());
        $this->grade_oral_presentation->setDecorators($this->labelRight());
        $this->grade_oral_presentation->setDescription('Grading / Reporting Progress');
        $this->grade_oral_presentation->setAttrib('acc_menu', '1');
        $this->grade_oral_presentation->setRequired(false);
        $this->grade_oral_presentation->setAllowEmpty(false);
        $this->grade_oral_presentation->addOnChange('otherHelper(this.value, \'*' . $this->grade_oral_presentation->getDescription() . ' - ' .$this->grade_oral_presentation->getLabel().'\')');


        $this->grade_graded_on_skills = new App_Form_Element_MultiSelect('grade_graded_on_skills',array('label'=>"Student graded only on skills being taught"));
        $this->grade_graded_on_skills->setMultiOptions($this->getSubjects());
        $this->grade_graded_on_skills->setDecorators($this->labelRight());
        $this->grade_graded_on_skills->setDescription('Grading / Reporting Progress');
        $this->grade_graded_on_skills->setAttrib('acc_menu', '1');
        $this->grade_graded_on_skills->setRequired(false);
        $this->grade_graded_on_skills->setAllowEmpty(false);
        $this->grade_graded_on_skills->addOnChange('otherHelper(this.value, \'*' . $this->grade_graded_on_skills->getDescription() . ' - ' .$this->grade_graded_on_skills->getLabel().'\')');


        $this->grade_other = new App_Form_Element_MultiSelect('grade_other',array('label'=>"Other"));
        $this->grade_other->setMultiOptions($this->getSubjects());
        $this->grade_other->setDecorators($this->labelRight());
        $this->grade_other->setDescription('Grading / Reporting Progress');
        $this->grade_other->setAttrib('acc_menu', '1');
        $this->grade_other->setRequired(false);
        $this->grade_other->setAllowEmpty(false);
        $this->grade_other->addOnChange('otherHelper(this.value, \'*' . $this->grade_other->getDescription() . ' - ' .$this->grade_other->getLabel().'\')');



        $this->asstech_supp_writ_device = new App_Form_Element_MultiSelect('asstech_supp_writ_device',array('label'=>"Use supported writing device"));
        $this->asstech_supp_writ_device->setMultiOptions($this->getSubjects());
        $this->asstech_supp_writ_device->setDecorators($this->labelRight());
        $this->asstech_supp_writ_device->setDescription('Assistive Technology');
        $this->asstech_supp_writ_device->setAttrib('acc_menu', '1');
        $this->asstech_supp_writ_device->setRequired(false);
        $this->asstech_supp_writ_device->setAllowEmpty(false);
        $this->asstech_supp_writ_device->addOnChange('otherHelper(this.value, \'*' . $this->asstech_supp_writ_device->getDescription() . ' - ' .$this->asstech_supp_writ_device->getLabel().'\')');


        $this->asstech_pro_writ_sw = new App_Form_Element_MultiSelect('asstech_pro_writ_sw',array('label'=>"Provide supported writing software"));
        $this->asstech_pro_writ_sw->setMultiOptions($this->getSubjects());
        $this->asstech_pro_writ_sw->setDecorators($this->labelRight());
        $this->asstech_pro_writ_sw->setDescription('Assistive Technology');
        $this->asstech_pro_writ_sw->setAttrib('acc_menu', '1');
        $this->asstech_pro_writ_sw->setRequired(false);
        $this->asstech_pro_writ_sw->setAllowEmpty(false);
        $this->asstech_pro_writ_sw->addOnChange('otherHelper(this.value, \'*' . $this->asstech_pro_writ_sw->getDescription() . ' - ' .$this->asstech_pro_writ_sw->getLabel().'\')');


        $this->asstech_speech_gen = new App_Form_Element_MultiSelect('asstech_speech_gen',array('label'=>"Use speech generating device"));
        $this->asstech_speech_gen->setMultiOptions($this->getSubjects());
        $this->asstech_speech_gen->setDecorators($this->labelRight());
        $this->asstech_speech_gen->setDescription('Assistive Technology');
        $this->asstech_speech_gen->setAttrib('acc_menu', '1');
        $this->asstech_speech_gen->setRequired(false);
        $this->asstech_speech_gen->setAllowEmpty(false);
        $this->asstech_speech_gen->addOnChange('otherHelper(this.value, \'*' . $this->asstech_speech_gen->getDescription() . ' - ' .$this->asstech_speech_gen->getLabel().'\')');


        $this->asstech_aug_options = new App_Form_Element_MultiSelect('asstech_aug_options',array('label'=>"Use supported augmented speech options"));
        $this->asstech_aug_options->setMultiOptions($this->getSubjects());
        $this->asstech_aug_options->setDecorators($this->labelRight());
        $this->asstech_aug_options->setDescription('Assistive Technology');
        $this->asstech_aug_options->setAttrib('acc_menu', '1');
        $this->asstech_aug_options->setRequired(false);
        $this->asstech_aug_options->setAllowEmpty(false);
        $this->asstech_aug_options->addOnChange('otherHelper(this.value, \'*' . $this->asstech_aug_options->getDescription() . ' - ' .$this->asstech_aug_options->getLabel().'\')');


        $this->asstech_physical_access = new App_Form_Element_MultiSelect('asstech_physical_access',array('label'=>"Provide physical access strategies"));
        $this->asstech_physical_access->setMultiOptions($this->getSubjects());
        $this->asstech_physical_access->setDecorators($this->labelRight());
        $this->asstech_physical_access->setDescription('Assistive Technology');
        $this->asstech_physical_access->setAttrib('acc_menu', '1');
        $this->asstech_physical_access->setRequired(false);
        $this->asstech_physical_access->setAllowEmpty(false);
        $this->asstech_physical_access->addOnChange('otherHelper(this.value, \'*' . $this->asstech_physical_access->getDescription() . ' - ' .$this->asstech_physical_access->getLabel().'\')');


        $this->asstech_enlarged_print = new App_Form_Element_MultiSelect('asstech_enlarged_print',array('label'=>"Provide vision supports for enlarged print"));
        $this->asstech_enlarged_print->setMultiOptions($this->getSubjects());
        $this->asstech_enlarged_print->setDecorators($this->labelRight());
        $this->asstech_enlarged_print->setDescription('Assistive Technology');
        $this->asstech_enlarged_print->setAttrib('acc_menu', '1');
        $this->asstech_enlarged_print->setRequired(false);
        $this->asstech_enlarged_print->setAllowEmpty(false);
        $this->asstech_enlarged_print->addOnChange('otherHelper(this.value, \'*' . $this->asstech_enlarged_print->getDescription() . ' - ' .$this->asstech_enlarged_print->getLabel().'\')');


        $this->asstech_braille = new App_Form_Element_MultiSelect('asstech_braille',array('label'=>"Braille"));
        $this->asstech_braille->setMultiOptions($this->getSubjects());
        $this->asstech_braille->setDecorators($this->labelRight());
        $this->asstech_braille->setDescription('Assistive Technology');
        $this->asstech_braille->setAttrib('acc_menu', '1');
        $this->asstech_braille->setRequired(false);
        $this->asstech_braille->setAllowEmpty(false);
        $this->asstech_braille->addOnChange('otherHelper(this.value, \'*' . $this->asstech_braille->getDescription() . ' - ' .$this->asstech_braille->getLabel().'\')');


        $this->asstech_aud_trainer = new App_Form_Element_MultiSelect('asstech_aud_trainer',array('label'=>"Utilize auditory trainer systems"));
        $this->asstech_aud_trainer->setMultiOptions($this->getSubjects());
        $this->asstech_aud_trainer->setDecorators($this->labelRight());
        $this->asstech_aud_trainer->setDescription('Assistive Technology');
        $this->asstech_aud_trainer->setAttrib('acc_menu', '1');
        $this->asstech_aud_trainer->setRequired(false);
        $this->asstech_aud_trainer->setAllowEmpty(false);
        $this->asstech_aud_trainer->addOnChange('otherHelper(this.value, \'*' . $this->asstech_aud_trainer->getDescription() . ' - ' .$this->asstech_aud_trainer->getLabel().'\')');


        $this->asstech_other = new App_Form_Element_MultiSelect('asstech_other',array('label'=>"Other"));
        $this->asstech_other->setMultiOptions($this->getSubjects());
        $this->asstech_other->setDecorators($this->labelRight());
        $this->asstech_other->setDescription('Assistive Technology');
        $this->asstech_other->setAttrib('acc_menu', '1');
        $this->asstech_other->setRequired(false);
        $this->asstech_other->setAllowEmpty(false);
        $this->asstech_other->addOnChange('otherHelper(this.value, \''.$this->asstech_other->getLabel().'\')');

        $this->asstech_other->setAttrib('onchange',
            "if(this.value == 'q') dojo.byId('accomodations_checklist_1-other').value += '*"
                .$this->asstech_other->getDescription()." - ".$this->asstech_other->getLabel()."\\n';"
                . $this->asstech_other->getAttrib('onchange')
        );


        //$this->asstech_other_text = new App_Form_Element_TextareaEditor('asstech_other_text', array('label'=>'Other Assistive Technologies'));
        $this->asstech_other_text = $this->buildEditor('asstech_other_text', array('label'=>'Other Assistive Technologies'));
        $this->asstech_other_text->setAttrib('minheight', "90px");
        $this->asstech_other_text->setRequired(false);
        $this->asstech_other_text->setAllowEmpty(true);

//      " onChange=\"if(this.value == 'q') forms[0].other.value += '*Pacing - Extend time requirements\\n';\""

        //$this->other= new App_Form_Element_TextareaEditor('other', array('label'=>'Other'));
        $this->other = $this->buildEditor('other', array('label'=>'Other'));
        $this->other->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id, 'tpd_wrapper');");
        $this->other->setAttrib('minheight', "90px");
        // $this->other->addErrorMessage("The checkbox insuring you have recorded subjects in the \"Other\" field has been checked but no value has been entered in the \"Other\" field.");
        $this->other->addErrorMessage("Accommodations Checklist - The subject \"other\" has been selected, but there is no value in the \"Other\" text box.");
        $this->other->setRequired(false);
        $this->other->setAllowEmpty(false);
        $this->other->addValidator(new My_Validate_NotEmptyIf('flag_subject_areas_entered', 't'));

        $this->flag_subject_areas_entered = new App_Form_Element_Checkbox('flag_subject_areas_entered', array('label'=>'Have you recorded the subject areas in which these modifications/accommodations should be addressed?'));
//        $this->flag_subject_areas_entered->getDecorator('Label')->setOption('class', 'noprint');
        // $this->flag_subject_areas_entered->addErrorMessage("The checkbox insuring you have recorded subjects in the \"Other\" field must be checked.");
        $this->flag_subject_areas_entered->setRequired(false);
        $this->flag_subject_areas_entered->setAllowEmpty(false);
        $this->flag_subject_areas_entered->addOnChange('otherHelper(this.value, \''.$this->flag_subject_areas_entered->getLabel().'\')');
        // $this->flag_subject_areas_entered->addValidator(new My_Validate_NoValidationIf('other', ''));

        return $this;
    }

    function getSubjects()
    {
        return array(   '' =>' ',
                        'a'=>'All Subjects', 
                        'b'=>'Reading', 
                        'c'=>'English/Language Arts', 
                        'd'=>'Spelling', 
                        'e'=>'Math', 
                        'f'=>'Science', 
                        'g'=>'Social Studies', 
                        'h'=>'Health', 
                        'i'=>'Economics', 
                        'j'=>'Physical Education', 
                        'k'=>'Music/Art', 
                        'l'=>'Career Education', 
                        'm'=>'Lunch', 
                        'n'=>'Family and Consumer Science', 
                        'o'=>'Library', 
                        'p'=>'Title 1', 
                        'q'=>'Other'
                        );
    }   

    public function subform_header_edit_version9($subformName, $addNotReq = false) {
        return $this->edit_subform_version1_header($subformName, $addNotReq);
    }
    
    public function edit_subform_version1_header($subformName, $addNotReq) {//, $addNewRowButton=true, $addNotReq=true

        $this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form004/accomodations_checklist_header.phtml' ) ) ) );
        
        // hidden element to tell the system to add a row
        $this->addrow = new App_Form_Element_Hidden('addrow');

        $this->accomodations_checklist_hide = new App_Form_Element_Checkbox('accomodations_checklist_hide');
        $this->accomodations_checklist_hide->setAttrib('onclick', 'enableDisableAccomodationsChecklist(this.checked)');
        
        // button to call addSubformRow for the subform
        // sets the above to 1 I believe
        // NO "ADD ROW" BUTTON
        //$this->add_subform_row= new App_Form_Element_Button('add_subform_row', 'Add Row');
        //$this->add_subform_row->setAttrib('onclick', 'addSubformRow(\''.$subformName.'\');');
        
        if($addNotReq) {
            $this->override= new App_Form_Element_Checkbox('override', array('label'=>'Not Required'));
        }
        
        $this->count = new App_Form_Element_Hidden('count');

        $this->subformTitle = new Zend_Form_Element_Hidden('subformTitle');
                
        // add hidden elements for subform counts
        $this->subformName = new App_Form_Element_Hidden('subformName', $subformName);
        
        return $this;
    }
}

