<?php
/*
 * jQuery File Upload Plugin PHP Example 5.2.9
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://creativecommons.org/licenses/MIT/
 */
error_reporting(E_ALL | E_STRICT);

class JfileUploadController extends My_Form_AbstractFormController
{
	function uploadAction() {
		$this->_helper->layout()->disableLayout();
		
		$upload_handler = new App_Classes_JQueryUploadHandler(
			$this->getRequest()->getParam('form_number'),
			$this->getRequest()->getParam('document')
		);
		
		header('Pragma: no-cache');
		header('Cache-Control: private, no-cache');
		header('Content-Disposition: inline; filename="files.json"');
		header('X-Content-Type-Options: nosniff');
		
		if('delete'==$this->getRequest()->getParam('requestType')) {
			$upload_handler->delete($this->getRequest()->getParam('file'));
		} elseif('download'==$this->getRequest()->getParam('requestType')) {
// 			$upload_handler->get($this->getRequest()->getParam('file'));

			
			
		} else {
			switch ($_SERVER['REQUEST_METHOD']) {
				case 'HEAD':
				case 'GET':
// 					$upload_handler->postData = $this->getRequest()->getParams();
					if($this->getRequest()->getParam('file')) {
						$upload_handler->get($this->getRequest()->getParam('file'));
					} else {
						$upload_handler->get();
					}
					break;
				case 'POST':
					$upload_handler->post();
					break;
// 				case 'DELETE':
// 					$upload_handler->delete();
// 					break;
				case 'OPTIONS':
					break;
				default:
					header('HTTP/1.0 405 Method Not Allowed');
			}
		}
		exit;
	} 
	
	function printpdfAction() {
		// configure options
		$this->view->mode = 'print';
		$this->view->valid = true;
		// we disable the layout because we're returning ajax
		$this->_helper->layout->disableLayout ( true );
	
		$formNum = str_pad($this->getRequest()->getParam('form_number'), 3, 0);
		// retrieve data from the request
		$request = $this->getRequest();
		
		// =====================================================================================
		// WRITE THE WEB PAGE TO A FILE AND CREATE THE PDF
		// SETUP PRINCEXML FOR PDF CREATION
		// PRINCE XML CAN CONVERT XML/HTML DOCUMENTS TO PDF
		// =====================================================================================
		//	    $dir = "/usr/local/zend/apache2/htdocs/srs-zf/temp/"; // WRITE THE FILES TO THE TMP DIR - TMP IS IN OUR MAIN WEB FOLDER
		$pdfManager = new App_File_PdfManager();
		$pdfList = $pdfManager->getFiles($formNum, $this->getRequest()->getParam('document'));
	
		if (file_exists ( $pdfManager->folderPath . '/' .  $this->getRequest()->getParam('file'))) {
			$tmpPDFpath = $pdfManager->folderPath . '/' .  $this->getRequest()->getParam('file');
		}
	
// 		Zend_Debug::dump($formNum);
// 		Zend_Debug::dump($this->getRequest()->getParam('document'));
// 		Zend_Debug::dump($this->getRequest()->getParam('filename'));
// 		Zend_Debug::dump($this->getRequest()->getParams());
// 		die();
		/*
		 * Issue SRSZF-287 Mod.  Download PDF instead of printing
		* anything to screen.
		*/
		header ( "Cache-Control: public, must-revalidate" );
		header ( "Pragma: hack" );
		header ( "Content-Description: File Transfer" );
		header ( 'Content-disposition: attachment;filename=' . str_replace(' ', '_', basename($tmpPDFpath)) );
		header ( "Content-Type: application/pdf" );
		//                 header("Content-Type: text/html; charset=utf-8");
		header ( "Content-Transfer-Encoding: binary" );
		header ( 'Content-Length: ' . filesize ( $tmpPDFpath ) );
		readfile ( $tmpPDFpath );
		exit ();
		/*
		 * END SRSZF-287
		*/
	
	}
	
}
