<?php
/**
 * 
 * Sets default decorators for Select elements
 * @author stevebennett
 *
 */
class App_Form_Element_SearchSelect extends Zend_Form_Element_Select 
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
    }
}