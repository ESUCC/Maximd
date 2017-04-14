<?php

class Form_Form021 extends Form_AbstractForm {
    
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		parent::initialize();
		
		$this->id_form_021 = new App_Form_Element_Hidden('id_form_021');
      	$this->id_form_021->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');
      	 
	}
	public function view_p1_v1() {
				
		$this->initialize();
		
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form021/form021_view_page1_version1.phtml' ) ) ) );

		
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
	public function edit_p1_v1() {

		$this->initialize();
		
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options: 
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form021/form021_edit_page1_version1.phtml' ) ) ) );

        $this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date Notice'));
        $this->date_notice->addErrorMessage("Date of Notice is empty.");
        
		$this->wl_pencil = new App_Form_Element_Checkbox('wl_pencil', array('label'=>'Pencil/Pen with adapted grip'));
		$this->wl_pencil->addTablefix();
	    $this->wl_adapted_paper = new App_Form_Element_Checkbox('wl_adapted_paper', array('label'=>'Adapted paper'));
		$this->wl_adapted_paper->addTablefix();
	    $this->wl_slantboard = new App_Form_Element_Checkbox('wl_slantboard', array('label'=>'Slantboard'));
		$this->wl_slantboard->addTablefix();
	    $this->wl_word_cards = new App_Form_Element_Checkbox('wl_word_cards', array('label'=>'Word Cards/Word Book/Word Wall'));
		$this->wl_word_cards->addTablefix();
	    $this->wl_pocket_dict = new App_Form_Element_Checkbox('wl_pocket_dict', array('label'=>'Pocket Dictionary/Thesaurus'));
		$this->wl_pocket_dict->addTablefix();
	    $this->wl_electronic_dict = new App_Form_Element_Checkbox('wl_electronic_dict', array('label'=>'Electronic Dictionary/Thesaurus'));
		$this->wl_electronic_dict->addTablefix();
	    $this->wl_use_class_computer = new App_Form_Element_Checkbox('wl_use_class_computer', array('label'=>'Use of classroom computer'));
		$this->wl_use_class_computer->addTablefix();
	    $this->wl_talk_word_process = new App_Form_Element_Checkbox('wl_talk_word_process', array('label'=>'Talking word processing software'));
		$this->wl_talk_word_process->addTablefix();
	    $this->wl_wp_spell_check = new App_Form_Element_Checkbox('wl_wp_spell_check', array('label'=>'Word processor with spell check software'));
		$this->wl_wp_spell_check->addTablefix();
	    $this->wl_wp_grammar_check = new App_Form_Element_Checkbox('wl_wp_grammar_check', array('label'=>'Word processor with grammar check software'));
		$this->wl_wp_grammar_check->addTablefix();
	    $this->wl_portable_wp = new App_Form_Element_Checkbox('wl_portable_wp', array('label'=>'***Portable word processor'));
		$this->wl_portable_wp->addTablefix();
	    $this->wl_laptop_computer = new App_Form_Element_Checkbox('wl_laptop_computer', array('label'=>'***Laptop computer'));
		$this->wl_laptop_computer->addTablefix();
	    $this->wl_word_predict = new App_Form_Element_Checkbox('wl_word_predict', array('label'=>'***Word Prediction software'));
		$this->wl_word_predict->addTablefix();
	    $this->wl_easy_access = new App_Form_Element_Checkbox('wl_easy_access', array('label'=>'***Easy Access Software'));
		$this->wl_easy_access->addTablefix();
	    $this->wl_keyguard = new App_Form_Element_Checkbox('wl_keyguard', array('label'=>'***Keyguard'));
		$this->wl_keyguard->addTablefix();
	    $this->wl_arm_support = new App_Form_Element_Checkbox('wl_arm_support', array('label'=>'***Arm support'));
		$this->wl_arm_support->addTablefix();
	    $this->wl_tracball = new App_Form_Element_Checkbox('wl_tracball', array('label'=>'***TracBall/Track Pad/Joystick'));
		$this->wl_tracball->addTablefix();
	    $this->wl_alternate_keyboard = new App_Form_Element_Checkbox('wl_alternate_keyboard', array('label'=>'***Alternate keyboard'));
		$this->wl_alternate_keyboard->addTablefix();
	    $this->wl_onscreen_keyboard = new App_Form_Element_Checkbox('wl_onscreen_keyboard', array('label'=>'***Onscreen Keyboard'));
		$this->wl_onscreen_keyboard->addTablefix();
	    $this->wl_mouth_stick = new App_Form_Element_Checkbox('wl_mouth_stick', array('label'=>'***Mouth stick/Head Stick'));
		$this->wl_mouth_stick->addTablefix();
	    $this->wl_switch_input = new App_Form_Element_Checkbox('wl_switch_input', array('label'=>'***Single/multiple switch input'));
		$this->wl_switch_input->addTablefix();
	    $this->wl_morse_code = new App_Form_Element_Checkbox('wl_morse_code', array('label'=>'***Morse code software'));
		$this->wl_morse_code->addTablefix();
	    $this->wl_scanning_sw = new App_Form_Element_Checkbox('wl_scanning_sw', array('label'=>'***Scanning software'));
		$this->wl_scanning_sw->addTablefix();
	    $this->wl_name_stamp = new App_Form_Element_Checkbox('wl_name_stamp', array('label'=>'***Name Stamp'));
		$this->wl_name_stamp->addTablefix();
	    $this->wl_other = new App_Form_Element_Checkbox('wl_other', array('label'=>'***Other:'));
		$this->wl_other->addTablefix();
	    $this->com_dict = new App_Form_Element_Checkbox('com_dict', array('label'=>'Communication Dictionary'));
		$this->com_dict->addTablefix();
	    $this->com_obj_symbols = new App_Form_Element_Checkbox('com_obj_symbols', array('label'=>'Object Symbols'));
		$this->com_obj_symbols->addTablefix();
	    $this->com_graphic_symbol = new App_Form_Element_Checkbox('com_graphic_symbol', array('label'=>'Graphic Symbol'));
		$this->com_graphic_symbol->addTablefix();
	    $this->com_picture_symbol = new App_Form_Element_Checkbox('com_picture_symbol', array('label'=>'Picture Symbols'));
		$this->com_picture_symbol->addTablefix();
	    $this->com_behavior_cue_cards = new App_Form_Element_Checkbox('com_behavior_cue_cards', array('label'=>'Behavior Cue Cards'));
		$this->com_behavior_cue_cards->addTablefix();
	    $this->com_books_boards = new App_Form_Element_Checkbox('com_books_boards', array('label'=>'***Communication Book/Communication Boards'));
		$this->com_books_boards->addTablefix();
	    $this->com_voice_output = new App_Form_Element_Checkbox('com_voice_output', array('label'=>'***Voice Output Communication Device'));
		$this->com_voice_output->addTablefix();
	    $this->com_eye_gaze_board = new App_Form_Element_Checkbox('com_eye_gaze_board', array('label'=>'***Eye Gaze Board'));
		$this->com_eye_gaze_board->addTablefix();
	    $this->com_laptop_computer = new App_Form_Element_Checkbox('com_laptop_computer', array('label'=>'***Laptop computer with voice output'));
		$this->com_laptop_computer->addTablefix();
	    $this->com_other = new App_Form_Element_Checkbox('com_other', array('label'=>'***Other:'));
		$this->com_other->addTablefix();
	    $this->aca_low_tech = new App_Form_Element_Checkbox('aca_low_tech', array('label'=>'Low Tech aids for locating information/materials (e.g. Highlighting, index tabs, etc.)'));
		$this->aca_low_tech->addTablefix();
	    $this->aca_abacus = new App_Form_Element_Checkbox('aca_abacus', array('label'=>'Abacus or math line'));
		$this->aca_abacus->addTablefix();
	    $this->aca_standard_calc = new App_Form_Element_Checkbox('aca_standard_calc', array('label'=>'Standard Calculator'));
		$this->aca_standard_calc->addTablefix();
	    $this->aca_large_key_calc = new App_Form_Element_Checkbox('aca_large_key_calc', array('label'=>'Large key calculator'));
		$this->aca_large_key_calc->addTablefix();
	    $this->aca_adapted_book = new App_Form_Element_Checkbox('aca_adapted_book', array('label'=>'Adapted book for page turning (e.g. page fluffers)'));
		$this->aca_adapted_book->addTablefix();
	    $this->aca_book_holder = new App_Form_Element_Checkbox('aca_book_holder', array('label'=>'Book holder'));
		$this->aca_book_holder->addTablefix();
	    $this->aca_talking_electronic = new App_Form_Element_Checkbox('aca_talking_electronic', array('label'=>'Talking electronic device (e.g. Bookman, Language Master)'));
		$this->aca_talking_electronic->addTablefix();
	    $this->aca_taped_text = new App_Form_Element_Checkbox('aca_taped_text', array('label'=>'***Taped Text Books'));
		$this->aca_taped_text->addTablefix();
	    $this->aca_voice_output = new App_Form_Element_Checkbox('aca_voice_output', array('label'=>'Voice output reminder for assignments, steps of task, etc.'));
		$this->aca_voice_output->addTablefix();
	    $this->aca_sw_pict_text = new App_Form_Element_Checkbox('aca_sw_pict_text', array('label'=>'***Software combining pictures with text'));
		$this->aca_sw_pict_text->addTablefix();
	    $this->aca_scanner_talking_sw = new App_Form_Element_Checkbox('aca_scanner_talking_sw', array('label'=>'***Scanner with Talking Processor'));
		$this->aca_scanner_talking_sw->addTablefix();
	    $this->aca_electronic_book = new App_Form_Element_Checkbox('aca_electronic_book', array('label'=>'***Electronic Books'));
		$this->aca_electronic_book->addTablefix();
	    $this->aca_talking_calc = new App_Form_Element_Checkbox('aca_talking_calc', array('label'=>'***Talking Calculator'));
		$this->aca_talking_calc->addTablefix();
	    $this->aca_adapted_aca_sw = new App_Form_Element_Checkbox('aca_adapted_aca_sw', array('label'=>'***Adapted academic software'));
		$this->aca_adapted_aca_sw->addTablefix();
	    $this->aca_tactile_measuring = new App_Form_Element_Checkbox('aca_tactile_measuring', array('label'=>'***Tactile or voice output measuring devices'));
		$this->aca_tactile_measuring->addTablefix();
	    $this->aca_electronic_research = new App_Form_Element_Checkbox('aca_electronic_research', array('label'=>'***Electronic research materials'));
		$this->aca_electronic_research->addTablefix();
	    $this->aca_other = new App_Form_Element_Checkbox('aca_other', array('label'=>'***Other:'));
		$this->aca_other->addTablefix();
	    $this->rec_adapted_toys = new App_Form_Element_Checkbox('rec_adapted_toys', array('label'=>'Adapted toys and games'));
		$this->rec_adapted_toys->addTablefix();
	    $this->rec_adapted_sport_eq = new App_Form_Element_Checkbox('rec_adapted_sport_eq', array('label'=>'Adapted sporting equipment'));
		$this->rec_adapted_sport_eq->addTablefix();
	    $this->rec_switch_ordered_toys = new App_Form_Element_Checkbox('rec_switch_ordered_toys', array('label'=>'Switch Operated Toys'));
		$this->rec_switch_ordered_toys->addTablefix();
	    $this->rec_app_relay = new App_Form_Element_Checkbox('rec_app_relay', array('label'=>'Appliance Relay'));
		$this->rec_app_relay->addTablefix();
	    $this->rec_adapted_sw = new App_Form_Element_Checkbox('rec_adapted_sw', array('label'=>'***Adapted Computer software (games, music, art programs)'));
		$this->rec_adapted_sw->addTablefix();
	    $this->rec_universal_cuff = new App_Form_Element_Checkbox('rec_universal_cuff', array('label'=>'***Universal Cuff'));
		$this->rec_universal_cuff->addTablefix();
	    $this->rec_adapted_scissors = new App_Form_Element_Checkbox('rec_adapted_scissors', array('label'=>'***Adapted scissors'));
		$this->rec_adapted_scissors->addTablefix();
	    $this->rec_env_control_unit = new App_Form_Element_Checkbox('rec_env_control_unit', array('label'=>'***Environmental Control Unit'));
		$this->rec_env_control_unit->addTablefix();
	    $this->rec_other = new App_Form_Element_Checkbox('rec_other', array('label'=>'***Other:'));
		$this->rec_other->addTablefix();
	    $this->otpt_adapted_eating = new App_Form_Element_Checkbox('otpt_adapted_eating', array('label'=>'Adapted eating/drinking utensils'));
		$this->otpt_adapted_eating->addTablefix();
	    $this->otpt_adapted_dressing = new App_Form_Element_Checkbox('otpt_adapted_dressing', array('label'=>'Adapted dressing equipment'));
		$this->otpt_adapted_dressing->addTablefix();
	    $this->otpt_walker = new App_Form_Element_Checkbox('otpt_walker', array('label'=>'Walker/Crutches'));
		$this->otpt_walker->addTablefix();
	    $this->otpt_grab_rails = new App_Form_Element_Checkbox('otpt_grab_rails', array('label'=>'Grab rails'));
		$this->otpt_grab_rails->addTablefix();
	    $this->otpt_adapted_toilet = new App_Form_Element_Checkbox('otpt_adapted_toilet', array('label'=>'Adapted Toilet'));
		$this->otpt_adapted_toilet->addTablefix();
	    $this->otpt_adapted_class = new App_Form_Element_Checkbox('otpt_adapted_class', array('label'=>'Adapted classroom chair/desk'));
		$this->otpt_adapted_class->addTablefix();
	    $this->otpt_position_eq = new App_Form_Element_Checkbox('otpt_position_eq', array('label'=>'Positioning equipment (Riftom chair, stander, etc)'));
		$this->otpt_position_eq->addTablefix();
	    $this->otpt_wheelchair = new App_Form_Element_Checkbox('otpt_wheelchair', array('label'=>'Wheelchair'));
		$this->otpt_wheelchair->addTablefix();
	    $this->otpt_other = new App_Form_Element_Checkbox('otpt_other', array('label'=>'Other:'));
		$this->otpt_other->addTablefix();
	    $this->vis_magnigier = new App_Form_Element_Checkbox('vis_magnigier', array('label'=>'Magnifier'));
		$this->vis_magnigier->addTablefix();
	    $this->vis_large_print = new App_Form_Element_Checkbox('vis_large_print', array('label'=>'Large Print Books'));
		$this->vis_large_print->addTablefix();
	    $this->vis_adapted_computer = new App_Form_Element_Checkbox('vis_adapted_computer', array('label'=>'Adapted computer software (Zoomtext, ScreenReader, etc.)'));
		$this->vis_adapted_computer->addTablefix();
	    $this->vis_cctv = new App_Form_Element_Checkbox('vis_cctv', array('label'=>'CCTV'));
		$this->vis_cctv->addTablefix();
	    $this->vis_braille_keyboard = new App_Form_Element_Checkbox('vis_braille_keyboard', array('label'=>'Braille Keyboard and Note Taker (e.g. Braille n Speak)'));
		$this->vis_braille_keyboard->addTablefix();
	    $this->vis_braille_sw = new App_Form_Element_Checkbox('vis_braille_sw', array('label'=>'vis_brBraille Translation Softwareaille_sw'));
		$this->vis_braille_sw->addTablefix();
	    $this->vis_braille_printer = new App_Form_Element_Checkbox('vis_braille_printer', array('label'=>'Braille Printer'));
		$this->vis_braille_printer->addTablefix();
	    $this->vis_other = new App_Form_Element_Checkbox('vis_other', array('label'=>'Other:'));
		$this->vis_other->addTablefix();
	    $this->hear_amplification = new App_Form_Element_Checkbox('hear_amplification', array('label'=>'Amplification'));
		$this->hear_amplification->addTablefix();
	    $this->hear_auditory_trainer = new App_Form_Element_Checkbox('hear_auditory_trainer', array('label'=>'Auditory Trainer'));
		$this->hear_auditory_trainer->addTablefix();
	    $this->hear_captioning = new App_Form_Element_Checkbox('hear_captioning', array('label'=>'Captioning'));
		$this->hear_captioning->addTablefix();
	    $this->hear_signaling = new App_Form_Element_Checkbox('hear_signaling', array('label'=>'Signaling Device (e.g. vibrating pager)'));
		$this->hear_signaling->addTablefix();
	    $this->hear_tdd_tty = new App_Form_Element_Checkbox('hear_tdd_tty', array('label'=>'TDD/TTY'));
		$this->hear_tdd_tty->addTablefix();
	    $this->hear_other = new App_Form_Element_Checkbox('hear_other', array('label'=>'Other:'));
		$this->hear_other->addTablefix();
	    
	    $this->nothing_recommended = new App_Form_Element_Checkbox('nothing_recommended', array('label'=>'Nothing Recommended', 'description' => 'After consideration of the previous assistive technology devices, no assistive technology is recommended at this time.'));
	    $this->other_assistave_technologies = new App_Form_Element_Text('other_assistave_technologies', array('label'=>'Other assistave technologies'));
		$this->other_assistave_technologies->removeDecorator('label');
	    $this->other_assistave_technologies->setAttrib('style', 'width:99%;');
	    $this->other_assistave_technologies->setRequired(false);
	    $this->other_assistave_technologies->setAllowEmpty(true);
	    
	    $this->mode = new App_Form_Element_Hidden('mode');
        $this->mode->ignore = true;
	    
		return $this;
	}
}