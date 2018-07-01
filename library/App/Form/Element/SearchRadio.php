<?php
/**
 * 
 * Sets default decorators for Radio elements
 * @author stevebennett
 *
 */
class App_Form_Element_SearchRadio extends Zend_Form_Element_Radio 
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