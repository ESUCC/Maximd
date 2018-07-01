<?php

class My_View_Helper_FormValueDisplay extends Zend_View_Helper_FormHidden
{
    public function formValueDisplay($name, $value = null, array $attribs = null)
    {
        $str = '<span>' . $value . '</span>';
        return $str;
    }
}