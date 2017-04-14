<?php

class My_Form_Filter_StringReplaceWordFormatting implements Zend_Filter_Interface
{
    public function filter($value)
    {
        $pattern = "/<(\w+)>(\s|&nbsp;)*<\/\1>/";
        $value = preg_replace($pattern, '', $value);
        return mb_convert_encoding($value, 'HTML-ENTITIES', 'UTF-8');
    }
}
