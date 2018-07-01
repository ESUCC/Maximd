<?php

class Form_StudentSearch extends Zend_Dojo_Form {
    	
	public function init() {
		$this->setAction('student/search');
		$this->setAttrib('id', 'studentSearchParams');		

		
//        $this->name_first = new App_Form_Element_Text('name_first', array('label' => 'Name First'));
		$this->setMethod('post');
		$this->addElement(new App_Form_Element_Button('search', array('label' => 'Search', 'onclick'=>'javascript:startStudentSearch();')));
		$this->addElement(new App_Form_Element_Select('limitto', array('label' => 'Limit to', 'multioptions'=>array('all'=>'All', 'caseload'=>'caseload'))));
		$this->addElement ( new App_Form_Element_Select ( 'status', array ('label' => 'Status', 'multioptions' => array ('All' => 'All', 'Active' => 'Active', 'Inactive' => 'Inactive', 'Never Qualified' => 'Never Qualified', 'No Longer Qualifies' => 'No Longer Qualifies', 'Transferred to Non-SRS District' => 'Transferred to Non-SRS District' ) ) ) );
		$this->addElement ( new App_Form_Element_Select ( 'orderby', array ('label' => 'Sort', 'multioptions' => array ('name' => 'Name', 'school'=>'School' ) ) ) );
		$this->addElement ( new App_Form_Element_Select ( 'recsPer', array ('label' => 'Number of records', 'multioptions' => array ('2'=>'2', '5'=>'5', '15'=>'15' ) ) ) );
		
		
		
		$this->addElement(new App_Form_Element_Text('name_first', array('label' => 'Name First')));
		$this->addElement(new App_Form_Element_Text('name_last', array('label' => 'Name Last')));
		$this->addElement(new App_Form_Element_Text('id_student', array('label' => 'Student ID (SRS)')));
		$this->addElement(new App_Form_Element_Text('id_student_local', array('label' => 'Student ID (District)')));
		$this->addElement(new App_Form_Element_Text('unique_id_state', array('label' => 'NSSRS ID#')));
		$this->addElement(new App_Form_Element_Text('name_case_mgr_first', array('label' => 'Case Manager First Name')));
		$this->addElement(new App_Form_Element_Text('name_case_mgr_last', array('label' => 'Case Manager Last Name')));
		$this->addElement(new App_Form_Element_Text('school_name', array('label' => 'School Name')));
		$this->addElement(new App_Form_Element_Text('distric_name', array('label' => 'District Name')));
		$this->addElement(new App_Form_Element_Text('pub_school_student', array('label' => 'Public School Student (T/F)')));
		$this->addElement(new App_Form_Element_Text('onteam', array('label' => 'Student Team (ID personnel)')));
		$this->addElement(new App_Form_Element_Text('isCM', array('label' => 'Student CM (ID personnel)')));
		$this->addElement(new App_Form_Element_Text('isSC', array('label' => 'Student SC (ID personnel)')));
		$this->addElement(new App_Form_Element_Text('isEICM', array('label' => 'Student EI CM (ID personnel)')));
		$this->addElement(new App_Form_Element_Text('grade', array('label' => 'Grade')));
		$this->addElement(new App_Form_Element_Text('grade_greater_than', array('label' => 'Grade Greater Than')));
		$this->addElement(new App_Form_Element_Text('grade_less_than', array('label' => 'Grade Less Than')));
		$this->addElement(new App_Form_Element_Text('alternate_assessment', array('label' => 'Alternate Assessment (Y/N)')));
	}
	
}
