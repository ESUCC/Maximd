<?php
/**
 * 
 * Sets defaults for Text elements
 * @author stevebennett
 *
 */
class App_Form_Element_SearchText extends Zend_Form_Element_Text 
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form_Element::init()
     */
    public function init()
    {
        $defaultDecorators = array(
            'ViewHelper',
            array('Description', array('tag' => 'span')),
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
        );
        $this->setDecorators($defaultDecorators);
        $this->addFilter(new Zend_Filter_StringTrim());
    }
}