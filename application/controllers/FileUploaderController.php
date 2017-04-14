<?php
class FileUploaderController extends My_Form_AbstractFormController
{
    public function indexAction(){
		$this->view->dojo()->requireModule('soliant.widget.FileUploader');
		$this->view->headLink()->appendStylesheet('/js/dojo_development/dojo_source/dojox/form/resources/FileInput.css', 'screen');    
		
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
    	
		$searchObj = new Model_Table_FileUploader();
		$results = $searchObj->search($this->params['id_file_uploader']);

		return $this->returnAjax('id_file_uploader', array($results));
	}

	public function saveAction () {
		$this->initAjax();
		
//	    Zend_Debug::dump($this->getRequest()->getParams());die();
	    
	    $folderPath = APPLICATION_PATH . '/user_images/uploaded_pdf/PDF_' . 
	    				str_pad($this->getRequest()->getParam('form_number'), 3, '0', STR_PAD_LEFT) . '_' . 
	    				$this->getRequest()->getParam('document_id');
	    
		if(!is_dir($folderPath)) {
			$oldumask = umask(0);
			mkdir($folderPath, 0777); // or even 01777 so you get the sticky bit set
			chmod($folderPath, 0777);
			umask($oldumask);
	    }
	    
	    $adapter = new Zend_File_Transfer_Adapter_Http();
	    $adapter->setDestination($folderPath);

      	$error = false;
      	$result = $adapter->receive();
      	if(!$result){
         	foreach ($adapter->getMessages() as $msg) {
         		if("File '' exceeds the defined ini size" == $msg) {
         			// no error
//         			Zend_Debug::dump($msg);
         		} else {
         			//Zend_Debug::dump($msg);
         			Zend_Debug::dump($msg);
         			$error = true;
         		}
         	}
         	
      	}
      	
		$fieldName = "flashUploadFiles";//Filedata";
		
		if( isset($_FILES[$fieldName]) || isset($_FILES['uploadedfileFlash'])){
			//
			// If the data passed has $fieldName, then it's Flash.
			// NOTE: "Filedata" is the default fieldname, but we're using a custom fieldname.
			// The SWF passes one file at a time to the server, so the files come across looking
			// very much like a single HTML file. The SWF remembers the data and returns it to
			// Dojo as an array when all are complete.
			//
		      	
			echo "flash";die();	
		}elseif( isset($_FILES['uploadedfile0']) ){
			//
			//	Multiple files have been passed from HTML
			echo "multiple";die();	
		}elseif( isset($_POST['uploadedfiles']) ){
			echo "uploadedfiles 1";die();	
		}elseif( isset($_FILES['uploadedfiles']) ){
			$foo = '[{"file":"000000001.pdf","name":"000000001.pdf","type":"pdf","size":5795821}]';
			echo "<textarea>".$foo."</textarea>";
			die();
			
		}elseif( isset($_FILES['uploadedfile']) ){
			echo "uploadedfile 3";die();	
		}elseif(isset($_GET['rmFiles'])){
			echo "rmFiles";die();	
		}else{
			echo "else";die();	
		}

      	return $this->returnAjax('result', array(array('result' => 'success', 'id_file_uploader' => 1)));
	    
	}
	public function saveIeAction () {
		
		App_Vlog::file("\n\n\n\n================================\n".'saveIeAction');
		App_Vlog::file(print_r($this->getRequest()->getParams(), 1));
		
		
		$this->initAjax();
		
		$conf = Zend_Registry::get(('config'));
	    $folderPath = $conf->formUploadsPath . '/PDF_' . 
	    				str_pad($this->getRequest()->getParam('form_number'), 3, '0', STR_PAD_LEFT) . '_' . 
	    				$this->getRequest()->getParam('document_id');
	    
		if(!is_dir($folderPath)) {
			$oldumask = umask(0);
			mkdir($folderPath, 0777); // or even 01777 so you get the sticky bit set
			chmod($folderPath, 0777);
      		chown($folderPath, 'daemon');
			umask($oldumask);
	    }
	    						
	    $adapter = new Zend_File_Transfer_Adapter_Http();
	    $adapter->setDestination($folderPath);
	    App_Vlog::file('folderPath:', $folderPath);
      	$error = false;
      	$result = $adapter->receive();
      	
      	if(!$result){
      		App_Vlog::file('adapter msgs', print_r($adapter->getMessages(), 1));
         	foreach ($adapter->getMessages() as $msg) {
         		App_Vlog::file('msg:', $msg);
         		if("File '' exceeds the defined ini size" == $msg) {
         			// no error
         			Zend_Debug::dump($msg);
         		} else {
         			//Zend_Debug::dump($msg);
         			Zend_Debug::dump($msg);
         			$returnMessage= $msg;
         			$error = true;
         		}
         	}
         	
         	// for ie
			$ar = array(
				'status' => "failed",
				'details' => ""
			);
         	
      	} else {
      		App_Vlog::file('else', var_dump($result));
      		$file_name = $adapter->getFileName('uploadFile', true);
      		chmod($file_name, 0777);
      		chown($file_name, 'daemon');
      		App_Vlog::file('$file_name', var_dump($file_name));
      		$conf = Zend_Registry::get('config');
			$mime = $this->get_mime_type($file_name, $conf->mimeTypeLocation);
			
			
			
			if($mime == 'application/pdf')
			{
			    # appears to be a PDF
			    $returnSuccess = "success";
			    $returnMessage= "";
			} else {
			    # doesn't appear to be a pdf
			    $returnSuccess = "error";
			    $returnMessage= "File does not appear to be a pdf.";
			    
			    // delete the uploaded file
				$pdfManager = new App_File_PdfManager();
				$pdfList = $pdfManager->deleteFile(
					$this->getRequest()->getParam('form_number'), 
					$this->getRequest()->getParam('document_id'),
					basename($file_name)
				);
			} 
			$ar = array(
				// lets just pass lots of stuff back and see what we find.
				// the _FILES aren't coming through in IE6 (maybe 7)
				'status' => $returnSuccess,
				// and some static subarray just to see
				'filename' => $file_name,
				'errorMessage' => $returnMessage,
			);
      	}

		$returnData = json_encode($ar);
		echo "<html><body><textarea>$returnData</textarea></body</html>";
		die();
	}

	function get_mime_type($filename, $mimePath = '../etc') {
	   $fileext = substr(strrchr($filename, '.'), 1);
	   if (empty($fileext)) return (false);
	   $regex = "/^([\w\+\-\.\/]+)\s+(\w+\s)*($fileext\s)/i";
	   $lines = file("$mimePath/mime.types");
	   foreach($lines as $line) {
	      if (substr($line, 0, 1) == '#') continue; // skip comments
	      $line = rtrim($line) . " ";
	      if (!preg_match($regex, $line, $matches)) continue; // no match to the extension
	      return ($matches[1]);
	   }
	   return (false); // no match at all
	} 
}
