<?php
class Form_TranslationImport extends Zend_Form {
    public function init() {
        $this->locale = new Zend_Form_Element_Select('locale', array(
                'Label' => 'Locale to import:',
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
        
        $this->csv = new Zend_Form_Element_File('csv');
        $this->csv->setLabel('Translation Key CSV:')
                  ->setDestination('/tmp')
                  ->addValidator('Count', false, 1)
                  ->addValidator('Size', false, 10000000)
                  ->addValidator('Extension', false, 'csv');
        
        $this->submit = new Zend_Form_Element_Submit('submit');
        $this->submit->setLabel('Import CSV');
        
        $this->setAction('/translation/import');
        $this->setMethod('post');
        
        $this->setAttrib('enctype', 'multipart/form-data');
    }
}