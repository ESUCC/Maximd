<?php
class FileListController extends My_Form_AbstractFormController
{
    public function indexAction(){
		$this->view->dojo()->requireModule('soliant.widget.FileList');
    }
    public function initAjax(){
        // we disable the layout because we're returning ajax
		$this->_helper->layout->disableLayout(true);

		$this->params = json_decode ( $this->getRequest ()->getParam ( 'data' ), true );
    }
    public function returnAjax($id, $results){
        // return ajax data
        $data = new Zend_Dojo_Data($id, $results);
        $this->view->data = $data->toJson();
        return $this->render('data');
    }
    public function searchAction(){
		$this->initAjax();

		$pdfManager = new App_File_PdfManager();
		$pdfList = $pdfManager->getFiles($this->getRequest()->getParam('form_number'), $this->getRequest()->getParam('document_id'));

		if(false === $pdfList) {
			return $this->returnAjax('result', array(array('result' => 'error')));
		}
		return $this->returnAjax('filename', $pdfList);
	}
	
    public function deleteAction(){
		$this->initAjax();
    	
		$this->deleteFiles = json_decode ( $this->getRequest ()->getParam ( 'deleteFiles' ), true );
		
		$pdfManager = new App_File_PdfManager();
		foreach($this->deleteFiles as $fileName) {
			$pdfList = $pdfManager->deleteFile(
				$this->getRequest()->getParam('form_number'), 
				$this->getRequest()->getParam('document_id'),
				$fileName
			);
		}
		
//		if(false === $pdfList) {
//			return $this->returnAjax('result', array(array('result' => 'error')));
//		}
//		return $this->returnAjax('filename', $pdfList);
		
		return $this->returnAjax('id_file_uploader', array(array('result' => 'success', 'id_file_uploader' => 1)));
	}
	

	public function saveAction () {
		$this->initAjax();
		
		// fields to save
        $saveData = array(
			'name'=> $this->params['name']        
        );
        
        // save
        $results = Model_Table_FileList::save($this->params['id_file_list'], $saveData);
		return $this->returnAjax('result', array(array('result' => 'success', 'id_file_list' => $this->params['id_file_list'])));
    }
	function printpdfAction() {
		// configure options
		$this->view->mode = 'print';
		$this->view->valid = true;
		// we disable the layout because we're returning ajax
		$this->_helper->layout->disableLayout ( true );
		
		$formNum = str_pad($this->getRequest()->getParam('form_number'), 3, 0);
		// retrieve data from the request
		$request = $this->getRequest ();
		// =====================================================================================
		// WRITE THE WEB PAGE TO A FILE AND CREATE THE PDF
		// SETUP PRINCEXML FOR PDF CREATION
		// PRINCE XML CAN CONVERT XML/HTML DOCUMENTS TO PDF
		// =====================================================================================
		//	    $dir = "/usr/local/zend/apache2/htdocs/srs-zf/temp/"; // WRITE THE FILES TO THE TMP DIR - TMP IS IN OUR MAIN WEB FOLDER
		$pdfManager = new App_File_PdfManager();
		$pdfList = $pdfManager->getFiles($formNum, $this->getRequest()->getParam('document'));
		
		if (file_exists ( $pdfManager->folderPath . '/' .  $this->getRequest()->getParam('filename'))) {
			$tmpPDFpath = $pdfManager->folderPath . '/' .  $this->getRequest()->getParam('filename');
		}
			
//		Zend_Debug::dump($formNum);
//		Zend_Debug::dump($this->getRequest()->getParam('document'));
//		Zend_Debug::dump($this->getRequest()->getParam('filename'));
//		die();
        /*
         * Issue SRSZF-287 Mod.  Download PDF instead of printing
         * anything to screen.
         */
        header ( "Cache-Control: public, must-revalidate" );
        header ( "Pragma: hack" );
        header ( "Content-Description: File Transfer" );
        header ( 'Content-disposition: attachment;
                    filename=' . str_replace(' ', '_', basename($tmpPDFpath)) );
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
