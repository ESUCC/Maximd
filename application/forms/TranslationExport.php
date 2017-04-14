<?php
class Form_TranslationExport extends Zend_Form {
    public function init() {
        $this->locale = new Zend_Form_Element_Select('locale', array(
                'Label' => 'Locale to export:',
                'multiOptions' => array(
                        'es' => 'Spanish',
                        'en' => 'English',
                )
        ));
        $this->form = new Zend_Form_Element_Select('form', array(
                        'Label' => 'Form to export:',
                        'multiOptions' => array(
                                'form001' => 'form001',
                                'form002' => 'form002',
                                'form003' => 'form003',
                                'form004' => 'form004',
                                'form010' => 'form010',
                                'global'  => 'global',
                        ),
        ));
        $this->page = new Zend_Form_Element_Select('page', array(
                'Label' => 'Page to export:',
                'multiOptions' => array(
                        '1'=>'1','2'=>'2','3'=>'3','4'=>'4',
                        '5'=>'5','6'=>'6','7'=>'7','8'=>'8'
                ),
        ));
        $this->submit = new Zend_Form_Element_Submit('submit');
        $this->submit->setLabel('Export to CSV');
        $this->setAction('/translation/export');
        $this->setMethod('post');
    }
}