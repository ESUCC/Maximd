<?php
class Form_TranslationFormAndPageSelection extends Zend_Form {
    public function init() {
        $this->form = new Zend_Form_Element_Select('form', array(
                        'Label' => 'Keys From Form:',
                        'multiOptions' => array(
                                'form001' => 'form001',
                                'form002' => 'form002',
                                'form003' => 'form003',
                                'form004' => 'form004',
                                'form010' => 'form010',
                                'global' => 'global'
                        ),
        ));
        $this->form->setValue('form001');
        $this->page = new Zend_Form_Element_Select('page', array(
                'Label' => 'And Page:',
                'multiOptions' => array(
                        '1'=>'1','2'=>'2','3'=>'3','4'=>'4',
                        '5'=>'5','6'=>'6','7'=>'7','8'=>'8'
                ),
        ));
        $this->page->setValue('1');
    }
}