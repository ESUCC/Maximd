<?php

require_once (APPLICATION_PATH . '/../library/App/Filter/Htmlpurifier/HTMLPurifier.auto.php');

class App_Filter_HtmlPurifier implements Zend_Filter_Interface
{
    public function filter($value)
    {
        
        
	    $purifier = new HTMLPurifier();
	    $purifier->config->set('HTML.TidyLevel', 'heavy');
		$clean_html = $purifier->purify( $value );
		return $clean_html;
    }
}
