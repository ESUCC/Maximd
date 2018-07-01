<?php

class Form_Form024Consent extends Form_AbstractForm {

	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	protected function initialize() {
		
		$this->setAction('search/search')->setMethod('post')->setAttrib('class', 'srchFrmHome');
		
		$this->id_form_024_consent = new Zend_Form_Element_Hidden('id_form_024_consent');
        
		$page = new Zend_Form_Element_Hidden('page');
		$this->addElements(array($page));
	}
	
	public function view_page1_version1() {

		$this->initialize();
		
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options:
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form24/form024Consent_view_page1_version1.phtml' ) ) ) );

				
		return $this;
	}
	
	public function edit_page2_version1() {
		
		$this->initialize();
		
		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options:
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form24/form024Consent_edit_page1_version1.phtml' ) ) ) );
				
		$this->give_consent = new Zend_Form_Element_Checkbox ( 'give_consent' );
		$this->give_consent->setLabel ( 'Give Consent');
		$this->give_consent->setCheckedValue(1);
		$this->give_consent->setAttrib ( 'class', 'textbox' );
		$this->give_consent->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->give_consent->setDecorators ( array ('ViewHelper' ) );
		$this->give_consent->setRequired ( true );

		$this->do_not_give_consent = new Zend_Form_Element_Checkbox ( 'do_not_give_consent' );
		$this->do_not_give_consent->setCheckedValue(1);
		$this->do_not_give_consent->setAttrib ( 'class', 'textbox' );
		$this->do_not_give_consent->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->do_not_give_consent->setDecorators ( array ('ViewHelper' ) );

		$this->consent_receiver = new Zend_Form_Element_Text ( 'consent_receiver' );
		$this->consent_receiver->setLabel ( 'Consent Receiver');
		$this->consent_receiver->setAttrib ( 'class', 'textbox' );
		$this->consent_receiver->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_receiver->setDecorators ( array ('ViewHelper' ) );
		$this->consent_receiver->setRequired ( true );

		$this->subformnum = new Zend_Form_Element_Hidden ( 'subformnum' );

		$this->consent_all_records = new Zend_Form_Element_Checkbox ( 'consent_all_records' );
		$this->consent_all_records->setCheckedValue(1);
		$this->consent_all_records->setAttrib ( 'class', 'textbox' );
		$this->consent_all_records->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_all_records->setDecorators ( array ('ViewHelper' ) );

		$this->consent_scholastic = new Zend_Form_Element_Checkbox ( 'consent_scholastic' );
		$this->consent_scholastic->setCheckedValue(1);
		$this->consent_scholastic->setAttrib ( 'class', 'textbox' );
		$this->consent_scholastic->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_scholastic->setDecorators ( array ('ViewHelper' ) );

		$this->consent_psychological = new Zend_Form_Element_Checkbox ( 'consent_psychological' );
		$this->consent_psychological->setCheckedValue(1);
		$this->consent_psychological->setAttrib ( 'class', 'textbox' );
		$this->consent_psychological->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_psychological->setDecorators ( array ('ViewHelper' ) );

		$this->consent_activity = new Zend_Form_Element_Checkbox ( 'consent_activity' );
		$this->consent_activity->setCheckedValue(1);
		$this->consent_activity->setAttrib ( 'class', 'textbox' );
		$this->consent_activity->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_activity->setDecorators ( array ('ViewHelper' ) );

		$this->consent_discipline = new Zend_Form_Element_Checkbox ( 'consent_discipline' );
		$this->consent_discipline->setCheckedValue(1);
		$this->consent_discipline->setAttrib ( 'class', 'textbox' );
		$this->consent_discipline->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_discipline->setDecorators ( array ('ViewHelper' ) );

		$this->consent_health = new Zend_Form_Element_Checkbox ( 'consent_health' );
		$this->consent_health->setCheckedValue(1);
		$this->consent_health->setAttrib ( 'class', 'textbox' );
		$this->consent_health->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_health->setDecorators ( array ('ViewHelper' ) );

		$this->consent_standard_tests = new Zend_Form_Element_Checkbox ( 'consent_standard_tests' );
		$this->consent_standard_tests->setCheckedValue(1);
		$this->consent_standard_tests->setAttrib ( 'class', 'textbox' );
		$this->consent_standard_tests->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_standard_tests->setDecorators ( array ('ViewHelper' ) );

		$this->consent_special_ed = new Zend_Form_Element_Checkbox ( 'consent_special_ed' );
		$this->consent_special_ed->setCheckedValue(1);
		$this->consent_special_ed->setAttrib ( 'class', 'textbox' );
		$this->consent_special_ed->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_special_ed->setDecorators ( array ('ViewHelper' ) );

		$this->consent_other = new Zend_Form_Element_Checkbox ( 'consent_other' );
		$this->consent_other->setCheckedValue(1);
		$this->consent_other->setAttrib ( 'class', 'textbox' );
		$this->consent_other->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_other->setDecorators ( array ('ViewHelper' ) );

		$this->consent_other_comment = new Zend_Form_Element_Text ( 'consent_other_comment' );
		$this->consent_other_comment->setAttrib ( 'class', 'textbox' );
		$this->consent_other_comment->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_other_comment->setDecorators ( array ('ViewHelper' ) );

		$this->consent_all_of_the_above = new Zend_Form_Element_Checkbox ( 'consent_all_of_the_above' );
		$this->consent_all_of_the_above->setCheckedValue(1);
		$this->consent_all_of_the_above->setAttrib ( 'class', 'textbox' );
		$this->consent_all_of_the_above->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_all_of_the_above->setDecorators ( array ('ViewHelper' ) );

		$this->consent_none_of_the_above = new Zend_Form_Element_Checkbox ( 'consent_none_of_the_above' );
		$this->consent_none_of_the_above->setCheckedValue(1);
		$this->consent_none_of_the_above->setAttrib ( 'class', 'textbox' );
		$this->consent_none_of_the_above->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->consent_none_of_the_above->setDecorators ( array ('ViewHelper' ) );

		$this->sig_on_file = new Zend_Form_Element_Checkbox ( 'sig_on_file' );
		$this->sig_on_file->setCheckedValue(1);
		$this->sig_on_file->setAttrib ( 'class', 'textbox' );
		$this->sig_on_file->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->sig_on_file->setDecorators ( array ('ViewHelper' ) );

		$this->sig_date = new Zend_Form_Element_Text ( 'sig_date' );
		$this->sig_date->setAttrib ( 'class', 'textbox' );
		$this->sig_date->setAttrib ( 'onFocus', $this->JSmodifiedCode );
		$this->sig_date->setDecorators ( array ('ViewHelper' ) );

		return $this;
	}
}