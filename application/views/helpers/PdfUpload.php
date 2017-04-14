<?php
/**
 * Helper for retrieving base URL
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   Paste
 * @author    Matthew Weier O'Phinney <matthew@weierophinney.net> 
 * @copyright Copyright (C) 2008 - Present, Matthew Weier O'Phinney
 * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version   $Id: $
 */
class Zend_View_Helper_PdfUpload extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $returnText;

    /**
     * Return base URL of application
     * 
     * @return string
     */
    public function pdfUpload($view, $document_id, $formNum)
    {
    	
		
		// refactor for production
		// if buildscript are used and the widgets are included
		// in the includes.js file, then css is automatically rolled in
		// meaning the css includes should 'just work' on production and iepweb03 after the build is run
		$view->dojo()->requireModule('soliant.widget.FileUploader');
		$view->dojo()->requireModule('soliant.widget.FileList');
    	$view->headLink()->appendStylesheet('/js/dojo_development/dojo_source/dojox/form/resources/FileInput.css', 'screen');    
		$view->headLink()->appendStylesheet('/js/dojo_development/soliant/widget/FileUploader/FileUploader.css', 'screen');    
		$view->headLink()->appendStylesheet('/js/dojo_development/soliant/widget/FileList/FileList.css', 'screen');    
    	
		if('edit' == $view->mode) {
			$this->returnText .= '<div data-dojo-type="soliant.widget.FileList"
					data-dojo-props=\'form_number:"'.$formNum.'",use_file_uploader:true, document_id:"'.$document_id.'"\'
			></div>';
			
		} elseif('view' == $view->mode) {
			$this->returnText .= '<div data-dojo-type="soliant.widget.FileList"
					data-dojo-props=\'form_number:"'.$formNum.'",use_file_uploader:false, document_id:"'.$document_id.'"\'
			></div>';
		}
		    	
        return $this->returnText;
    }
}
