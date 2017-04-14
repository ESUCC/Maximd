<?php

class My_View_Helper_FormMultiSelectArray extends Zend_View_Helper_Abstract
{
    public function FormMultiSelectArray($elementName, $multiOptions, $currentvalue, $label=null)
    {

        $element = new Zend_Form_Element_Select($elementName, array(
            'id' => $elementName,
//            'onmouseup' => "survey_edit_refresh_question($position, this.value);"
        ));
        foreach ($multiOptions as $key => $value) {
            $element->addMultiOption($key, $value);
        }
        $element->setDecorators(array('ViewHelper'));
        $element->setValue($currentvalue);
        $element->setLabel($label);

        return $element;

    }
}

