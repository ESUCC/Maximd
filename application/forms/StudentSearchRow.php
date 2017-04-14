<?php

class Form_StudentSearchRow extends Zend_Dojo_Form {
    	
	public function init() {
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'student/viewscripts/student_search_row.phtml' ) ) ) );
		
//		$this->addElement(new App_Form_Element_Text('row_number'));
		$this->addElement(new App_Form_Element_Hidden('id_student_search_rows', array('isArray'=>true)));
		
		// defining select as array screws up the display
		// do it in the viewscript
		$this->addElement (new App_Form_Element_Select ( 'search_field', array ('label' => 'Search This Field', 'multioptions' => array (
			'name_first' => 'Name First',
			'name_last' => 'Name Last',
			'id_student' => 'Student ID(SRS)',
			'id_student_local' => 'Student ID(District)',
			'unique_id_state' => 'unique_id_state',
			'name_case_mgr_first' => 'name_case_mgr_first',
			'name_case_mgr_last' => 'name_case_mgr_last',
			'school_name' => 'school_name',
			'distric_name' => 'distric_name',
			'pub_school_student' => 'pub_school_student',
			'onteam' => 'onteam',
			'isCM' => 'isCM',
			'isSC' => 'isSC',
			'isEICM' => 'isEICM',
			'grade' => 'grade',
			'grade_greater_than' => 'grade_greater_than',
			'grade_less_than' => 'grade_less_than',
			'alternate_assessment' => 'alternate_assessment'
		) ) ) );
		
		$this->addElement(new App_Form_Element_Text('search_value', array('isArray'=>true, 'label' => 'Search Value')));
		//'onkeypress'=>"if(window.event && window.event.keyCode == 13) {dojo.byId('studentSearchParams').submit();} else {return false;};",
	}
	
//	function buildSubformsArray($count, $subformPrefix) {
//		for($x=1; $x<=$count; $x++) {
//			$this->addSubForm(new Form_StudentSearchBasic(), $subformPrefix.'_'.$x);
//		}
//	}
}
