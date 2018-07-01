<?php
/**
 * 
 * @author sbennett
 *
 */
class Form_LanguageSelector extends Zend_Form
{
    /**
     * 
     * @var Zend_Translate
     */
    protected $translate;
    
    /**
     * 
     * @var string locale
     */
    protected $locale;
    
    /**
     * 
     * @param Zend_Translate $translate
     */
    public function __construct(Zend_Translate $translate, $locale)
    {
        $this->setTranslate($translate); 
        $this->setLocale($locale);
        parent::__construct();   
    }
    
    /**
     * (non-PHPdoc)
     * @see App_Form::init()
     */
    public function init()
    {
        $this->languages = new Zend_Form_Element_Select('languages',
                array('multiOptions' => $this->getLanguageOptions())
        );
        $this->languages->setDecorators(array(
            'ViewHelper',
            array('Description', array('tag' => 'span')),
                array('Label', array('tag' => 'span')),
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span'))
        ));
        $this->languages->setValue($this->locale);
    }
    
    /**
     * 
     * @param Zend_Translate $translate
     * @return mixed $options
     */
    protected function getLanguageOptions()
    {
        $options = array();
        foreach ($this->translate->getList() AS $l)
            $options[$l] = $this->getLanguageFromLocale($l);
        return $options;
    }
    
    /**
     * Setter for translation 
     * 
     * @param Zend_Translate $translate
     */
    protected function setTranslate(Zend_Translate $translate)
    {
        $this->translate = $translate;
    }
    
    /**
     * Settor for locale
     *
     * @param string $locale
     */
    protected function setLocale($locale)
    {
        $this->locale = $locale;
    }
    
    /**
     *
     * @param string $locale
     * @return string $language
     */
    protected function getLanguageFromLocale($locale)
    {
        $languages = array(
                'en' => 'English',
                'es' => 'Spanish',
        );
        return $languages[$locale];
    }
}