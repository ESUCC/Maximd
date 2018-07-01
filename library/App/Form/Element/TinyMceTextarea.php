<?php

class App_Form_Element_TinyMceTextarea extends Zend_Form_Element_Textarea
{
    public function appendAttrib($name, $value)
    {
        $existingAttrib = $this->getAttrib($name);
        $this->setAttrib($name, $existingAttrib . $value);
    }

    public function init()
    {
        $simpleDecorators = array(
            'ViewHelper',
            array(array('maindiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'tinyMceEditor')),
        );

        $this->setDecorators($simpleDecorators);
        $this->addDecorator(array('colormediv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'colorme', 'id' => $this->getName() . '-colorme'));
        $this->setAttrib('alt', $this->getLabel());
        $this->setAttrib('title', $this->getLabel());
        $this->setAttrib('rows', '3');

        $this->addFilter(new Zend_Filter_PregReplace(
            array(
                'match' => '/\<br data\-mce\-bogus\=\"1\"\>/',
                'replace' => '',
            )
        ));

        // default validation - do not allow empty
        $this->setAllowEmpty(false);
        $this->setRequired(true);
    }

    public function removeEditorEmptyValidator()
    {
        $this->removeValidator('My_Validate_EditorEmpty');
    }

}
