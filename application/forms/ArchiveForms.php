<?php

class Form_ArchiveForms extends Zend_Dojo_Form {
    	
    public function init() { 
    	
		$this->before_date = new App_Form_Element_DatePicker('before_date', array('Label' => 'Before Date:'));

		$multiOptions = array(
			'001' => 'Form 001',
			'002' => 'Form 002',
			'003' => 'Form 003',
			'004' => 'Form 004',
			'005' => 'Form 005',
			'006' => 'Form 006',
			'007' => 'Form 007',
			'008' => 'Form 008',
			'009' => 'Form 009',
			'010' => 'Form 010',
			'011' => 'Form 011',
			'012' => 'Form 012',
			'013' => 'Form 013',
			'014' => 'Form 014',
			'015' => 'Form 015',
			'016' => 'Form 016',
			'017' => 'Form 017',
			'018' => 'Form 018',
			'019' => 'Form 019',
			'020' => 'Form 020',
			'021' => 'Form 021',
			'022' => 'Form 022',
			'023' => 'Form 023',
			'024' => 'Form 024',
		);
		$this->form_num = new App_Form_Element_Select('form_num', array('Label' => 'Form Number:'));
		$this->form_num->setMultiOptions($multiOptions);
    
		$this->submit = new App_Form_Element_Submit('submit', array('Label' => 'Submit:'));
		$this->submit->removeDecorator('label');
		return $this;
    }
    
    public function addArchiveFuncationality()
    {
		// disable existing fields 
//    	$this->before_date->setDijitParam('disabled', true);
    	$this->form_num->setAttrib('disable', 'true');
		$this->submit->setAttrib('disable', 'true');
    			
		// add two new buttons
		$this->reset = new App_Form_Element_Submit('reset', array('Label' => 'Reset:'));
		$this->reset->removeDecorator('label');
		
		return $this;
    	
    }
}
