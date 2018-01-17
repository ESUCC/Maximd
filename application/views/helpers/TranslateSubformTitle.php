<?php
/**
 * Helper for translating subformTitles
 *
 */
class Zend_View_Helper_TranslateSubformTitle extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_baseUrl;

    /**
     * Translate title
     *
     * @return string
     */
    public function translateSubformTitle($title)
    {
        if (preg_match('/<span class=\".+?\">(.+?)<\/span>/i', $title))
            return preg_replace_callback('/<span class=\".+?\">(.+?)<\/span>/i',
                    function($matches) {
                        $view = Zend_Layout::getMvcInstance()->getView();
                        return $view->translate('Goals');
                    }, $title);
        else
           return $this->view->translate($title);
    }
}