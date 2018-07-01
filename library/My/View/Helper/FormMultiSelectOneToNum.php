<?php

class My_View_Helper_FormMultiSelectOneToNum extends Zend_View_Helper_Abstract
{
    public function FormMultiSelectOneToNum($elementName, $num, $currentvalue, $label = null )
    {

        $element = new Zend_Form_Element_Select($elementName, array(
            'id' => $elementName,
//            'onmouseup' => "survey_edit_refresh_question($position, this.value);"
        ));
        for($i=1; $i <= $num; $i++) {
            $element->addMultiOption($i, $i);
        }
        $element->setDecorators(array('ViewHelper'));
        $element->setValue($currentvalue);
        $element->setLabel($label);

        return $element;

    }
}

