<?php
class Model_KeyMaster {
    
    protected $_translationFormAndPageSelection;
    protected $_locales;
    protected $_keys = array();
    protected $_translationIndex = array('es' => 0, 'en' => 1);
    
    public function __construct(Form_TranslationFormAndPageSelection $form) {
        $this->setTranslationFormAndPageSelection($form);
        $this->setLocales();
        $this->setKeysFromFormAndPage();
    }
    
    public function setKeysFromFormAndPage() {
        foreach ($this->_locales AS $locale) {
            switch ($this->_translationFormAndPageSelection->getValue('form')) {
                case 'global':
                    $tmxFilename = APPLICATION_PATH . '/translation/'.
                    $this->_translationFormAndPageSelection->getValue('form').'.tmx';
                    break;
                default:
                    $tmxFilename = APPLICATION_PATH . '/translation/'.
                    $this->_translationFormAndPageSelection->getValue('form').'/page'.
                    $this->_translationFormAndPageSelection->getValue('page').'.tmx';
            }
            
            if (is_file($tmxFilename)) {
                $translate = new Zend_Translate(
                    array(
                        'adapter' => 'tmx',
                        'content' => $tmxFilename,
                        'scan' => Zend_Translate::LOCALE_FILENAME,
                        'disableNotices' => true
                    )
                );
                $translate->setLocale($locale);
                foreach ($translate->getMessages() AS $key => $value) {
                        $this->_keys[$key][$locale] = $value;
                }
            }
        }
    }
    
    public function updateKeys(Form_TranslationGateKeeper $form, $formNumber, $page) {
        switch ($formNumber) {
            case 'global':
                $translation_xml = new SimpleXMLElement(
                file_get_contents(
                APPLICATION_PATH . '/translation/'.
                $formNumber.'.tmx'
                ));
                break;
            default:
                $translation_xml = new SimpleXMLElement(
                file_get_contents(
                APPLICATION_PATH . '/translation/'.
                $formNumber.'/page'.
                $page.'.tmx'
                ));
                break;
        }
        
        foreach ($this->_translationIndex AS $key => $value) {
            $keys = array();
            for ($i=1;$i<=count($this->_keys);$i++) {
                $keys[$form->getValue('key_'.$i)] = $form->getValue($key.'_'.$i);
            }
        
            for ($i=0;$i<count($translation_xml->body->tu);$i++) {
                if (array_key_exists((string)$translation_xml->body->tu[$i]->attributes()->tuid, $keys)) {
                    $translation_xml->body->tu[$i]->tuv[$this->_translationIndex[$key]]->seg = utf8_encode(utf8_decode($keys[(string)$translation_xml->body->tu[$i]->attributes()->tuid]));
                }
            }
            
        }
         
        switch ($formNumber) {
            case 'global':
                $translation_xml->asXML(
                APPLICATION_PATH . '/translation/'.
                $formNumber.'.tmx'
                );
                break;
            default:
                $translation_xml->asXML(
                APPLICATION_PATH . '/translation/'.
                $formNumber.'/page'.
                $page.'.tmx'
                );
                break;
        }
        
        Zend_Translate::removeCache();  
    }
    
    public function setTranslationFormAndPageSelection(Form_TranslationFormAndPageSelection $form) {
        $this->_translationFormAndPageSelection = $form;
    }
    
    public function setLocales() {
        $this->_locales = array('en', 'es');
    }
    
    public function getKeys() {
        return $this->_keys;
    }
}