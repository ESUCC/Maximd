<?php

class Form_StudentSearchBasic extends Zend_Dojo_Form {
    	
	public function init() {
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'student/viewscripts/student_search.phtml' ) ) ) );

		$this->setAction('student/search');
		$this->setAttrib('id', 'studentSearchParams');		

		
//        $this->name_first = new App_Form_Element_Text('name_first', array('label' => 'Name First'));

//		$this->search = new App_Form_Element_Button('search', array('label' => 'Search', 'onclick'=>'javascript:startStudentSearch();'));
//		$this->limitto = new App_Form_Element_Select('limitto', array('label' => 'Limit to', 'multioptions'=>array('all'=>'All', 'caseload'=>'caseload')));
		
		
		$this->setMethod('post');
		$this->addElement(new App_Form_Element_Hidden('id_student_search'));
		$this->addElement(new App_Form_Element_Button('search', array('label' => 'Search', 'onclick'=>'javascript:startStudentSearch();', 'value'=>'name_first')));
		$this->addElement(new App_Form_Element_Select('limitto', array('label' => 'Limit to', 'multioptions'=>array('all'=>'All', 'caseload'=>'caseload'))));
		$this->addElement ( new App_Form_Element_Select ( 'status', array ('label' => 'Status', 'multioptions' => array ('All' => 'All', 'Active' => 'Active', 'Inactive' => 'Inactive', 'Never Qualified' => 'Never Qualified', 'No Longer Qualifies' => 'No Longer Qualifies', 'Transferred to Non-SRS District' => 'Transferred to Non-SRS District' ) ) ) );
		$this->addElement ( new App_Form_Element_Select ( 'orderby', array ('label' => 'Sort', 'multioptions' => array ('name' => 'Name', 'school'=>'School' ) ) ) );
		$this->addElement ( new App_Form_Element_Select ( 'recsPer', array ('label' => 'Number of records', 'multioptions' => array ('2'=>'2', '5'=>'5', '15'=>'15' ) ) ) );

//		$this->addElement ( new App_Form_Element_Select ( 'searchField', array ('label' => 'Search This Field', 'multioptions' => array (
//			'name_first' => 'Name First',
//			'name_last' => 'Name Last',
//			'id_student' => 'Student ID(SRS)',
//			'id_student_local' => 'Student ID(District)',
//			'unique_id_state' => 'unique_id_state',
//			'name_case_mgr_first' => 'name_case_mgr_first',
//			'name_case_mgr_last' => 'name_case_mgr_last',
//			'school_name' => 'school_name',
//			'distric_name' => 'distric_name',
//			'pub_school_student' => 'pub_school_student',
//			'onteam' => 'onteam',
//			'isCM' => 'isCM',
//			'isSC' => 'isSC',
//			'isEICM' => 'isEICM',
//			'grade' => 'grade',
//			'grade_greater_than' => 'grade_greater_than',
//			'grade_less_than' => 'grade_less_than',
//			'alternate_assessment' => 'alternate_assessment'
//		) ) ) );
//		
//		$this->addElement(new App_Form_Element_Text('searchValue', array('label' => 'Name First')));
		
	}
	/*
	 * build a search row for each search row
	 */
	function buildSubformsArray($numberOfSearchRows, $subformPrefix, $subformClass = 'Form_StudentSearchRow') {
		for($x=1; $x<=$numberOfSearchRows; $x++) {
			$subFormRow = new $subformClass();
//			$subFormRow->setElementsBelongTo($subformPrefix.'_'.$x);
//			$subFormRow->setIsArray(true);
//			$subFormRow->getElement('row_number')->setValue($x);
			$this->addSubForm($subFormRow, $subformPrefix.'_'.$x);
		}
	}
}
