<?php

class Model_Table_Form029GenEdTeacher extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_029_gen_ed_teacher';
    protected $_primary = 'id_form_029_gen_ed_teacher';
	protected $_sequence = 'form_029_gen_ed_teacher_id_form_029_gen_ed_teacher_seq';
    protected $_referenceMap    = array(
        'Model_Table_Form029' => array(
            'columns'           => array('id_form_029'),
            'refTableClass'     => 'Model_Table_Form029',
            'refColumns'        => array('id_form_029')
        )
    );
    
}