<?php

class My_View_Helper_StudentOptionsMenu extends Zend_View_Helper_Abstract
{
	//
	// function should be priv aware
	//
	public function studentOptionsMenu($multiOptions, $id_student)
    {
		if(is_null($id_student)) return '';
//    	$session = new Zend_Session_Namespace('studentOptionsMenu');
		$multiOptions = array(
                'choose'=>'Choose...', 
                'view'=>'View Student', 
                'edit'=>'Edit Student', 
                'charts'=>'Student Charting',
                'parent'=>'Parent/Guardians',
                'team'=>'Student Team',
                'search-forms'=>'Student Forms',
                'log'=>'Student Log',
		);

        $element = new Zend_Form_Element_Select('options', array(
            'id' => 'options',
        ));
        foreach ($multiOptions as $key => $value) {
            $element->addMultiOption($key, $value);
        }
        $element->setDecorators(array('Label', 'ViewHelper'));
        $element->setAttrib('onchange', "javascript:if(this.value) location.href='/student/'+this.value+'/id_student/'+$id_student;");
//        $element->setValue($session->currentvalue);
        $element->setLabel('Options:');

        return $element;

    }

}

