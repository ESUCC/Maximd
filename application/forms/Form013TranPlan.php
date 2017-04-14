<?php

class Form_Form013TranPlan extends Form_AbstractForm {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public function tran_plan_edit_version10() {
	    return $this->tran_plan_edit_version1();
	}
	public function tran_plan_edit_version9() {
		return $this->tran_plan_edit_version1();
	}
	public function tran_plan_edit_version1() {

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form013/tran_plan_edit_version1.phtml' ) ) ) );

		// these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;
		
        // named displayed in validation output
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Parent/Guardian Row");

        $this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label'=>'Check to remove'));
        $this->remove_row->setDecorators ( My_Classes_Decorators::$labelDecorators);
        $this->remove_row->ignore = true;
        
        $this->id_ifsp_tran_plan_participants = new App_Form_Element_Hidden('id_ifsp_tran_plan_participants', array('label'=>''));
        
        // visible fields

        $this->tpp_responsible = new App_Form_Element_ComboBox('tpp_responsible', array('label'=>'Who is responsible?'));
        $this->tpp_responsible->setDecorators(App_Form_DecoratorHelper::inlineElement(true));
        $this->tpp_responsible->removeDecorator('label');
		$this->tpp_responsible->setRequired(false);
        $this->tpp_responsible->setAllowEmpty(false);
        $this->tpp_responsible->setStoreId('studentTeamStore');
        $this->tpp_responsible->setStoreParams(array('searchAttr'=>'name'));
        $this->tpp_responsible->setStoreType('dojo.data.ItemFileReadStore');
		$this->tpp_responsible->addErrorMessage("Who is responsible must be entered.");
				
				
		$this->tpp_time_line = new App_Form_Element_Text('tpp_time_line', array('label'=>'Timeline'));
        $this->tpp_time_line->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->tpp_time_line->removeDecorator('label');
        $this->tpp_time_line->setRequired(false);
        $this->tpp_time_line->setAllowEmpty(false);
		$this->tpp_time_line->addErrorMessage("Time Line must be entered.");
	
        $this->tpp_date_completed = new App_Form_Element_DatePicker('tpp_date_completed', array('label'=>'Date Completed'));
        $this->tpp_date_completed->removeDecorator('label');
		$this->tpp_date_completed->setRequired(false);
        $this->tpp_date_completed->setAllowEmpty(false);
		$this->tpp_date_completed->addErrorMessage("Date Completed must be entered.");
	
        $this->tpp_to_be_done =  new App_Form_Element_TestEditor('tpp_to_be_done', array('label'=>'What needs to be done?'));
		$this->tpp_to_be_done->setRequired(false);
        $this->tpp_to_be_done->setAllowEmpty(false);
		$this->tpp_to_be_done->addErrorMessage("What needs to be done must be entered.");
        
        
        return $this;			
	}

    static function get_student_team($options)
	{
		$db = Zend_Registry::get('db');
        $select = $db->select()
        	->from(array('st' => 'iep_student_team'),
        		array('id_personnel'))
            ->join(array('p' => 'iep_personnel'),
            	'st.id_personnel = p.id_personnel')
            ->where( 'st.id_student'.' = ?', $options['id_student'])
            ->where( 'st.status'.' = ?', 'Active')
            ->order( 'p.name_first', 'p.name_last');
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		
		$retArr = array();//''=>''
		foreach($results as $key => $person) {
			$fullName = $person['name_first'] . ' ' . $person['name_last'];
			$retArr[$fullName] = $fullName;
		}
//		Zend_Debug::dump($retArr);
        return $retArr;
	}
	
}
