<?php
/**
 *
 * Handles available languages
 * @author stevebennett
 * @version 1.0
 *
 */
class Zend_View_Helper_LanguageSelector extends Zend_View_Helper_Abstract
{
    /**
     *
     * Builds select for languages choices
     */
    public function LanguageSelector()
    {
        $controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $languageSelector = 
            new Form_LanguageSelector(
                    Zend_Registry::get('Zend_Translate'),
                    Zend_Registry::get('locale')
                    );
        if (in_array($controller, $this->getTranslatedFormsArray())) {
            $this->view->headScript()->appendFile('/js/language-selector.js');
            return '<tr><td>&nbsp;</td>'
                  . '<td style="text-align:right;" class="bts">'
                  . 'Translation: '
                  . $languageSelector->getElement('languages')
                  . '</td></tr>';
        }
    }
    
    private function getTranslatedFormsArray() {
        return array(
                'form001',
                'form002',
                'form003',
                'form004',
                'form010' 
                );
    }
}