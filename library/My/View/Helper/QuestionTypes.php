<?php

class My_View_Helper_QuestionTypes extends Zend_View_Helper_Abstract
{
    public function questionTypes($position, $value = null)
    {

        $element = new Zend_Form_Element_Select('question_type'. $position, array(
            'multiOptions'  => array(
                'mc_one'        => 'Multiple Choice (only one answer)',
                'mc_many'       => 'Multiple Choice (multiple answers)',
                'rating_scale'  => 'Rating Scale',
                'text_box'      => 'Text Box'
            ),
            'id' => 'question_type'. $position,
            'onmouseup' => "survey_edit_refresh_question($position, this.value);"
        ));
        $element->setDecorators(array('ViewHelper' , 'Errors'));
        $element->setValue($value);
        $element->setLabel('Choose your question type');
        return $element;
    }
}

