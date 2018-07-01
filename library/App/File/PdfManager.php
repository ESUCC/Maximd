<?php
class App_File_PdfManager {

	var $fileList = array();
	public $folderPath;
	
	function getFiles($form_number, $document_id) {
		
		$conf = Zend_Registry::get(('config'));
		$this->folderPath = $conf->formUploadsPath . '/PDF_' . 
	    				str_pad($form_number, 3, '0', STR_PAD_LEFT) . '_' . 
	    				$document_id;
		
		$this->folderPath2 = $conf->formUploadsPath . '_new/'
		. substr(MD5($document_id), 0, 1).'/'
		. substr(MD5($document_id), 1, 1).'/'
		. substr(MD5($document_id), 2, 1).'/PDF_'
		. str_pad ( $form_number, 3, '0', STR_PAD_LEFT )
		. '_' . $document_id . '/';
		
		if (is_dir($this->folderPath2)) {
		    $this->folderPath = $this->folderPath2;
		}
	    
		if(!is_dir($this->folderPath)) {
	    	// return error
			return false;
		}
		
		$dir = new DirectoryIterator($this->folderPath);
		foreach($dir as $fileInfo) {
			if($fileInfo->isDot()) {
				// do nothing
			} elseif(substr(strrchr($fileInfo->getFilename(), '.'), 1)  != 'pdf') {
				// do nothing
			} else {
				$fileInfo->__toString()."<br>";
				$this->fileList[] = array('filename'=>$fileInfo->__toString());
			}
		}
		return $this->fileList;
	}

	function deleteFile($form_number, $document_id, $data_file_to_delete) {
		
	    $this->folderPath = APPLICATION_PATH . '/user_images/uploaded_pdf/PDF_' . 
	    				str_pad($form_number, 3, '0', STR_PAD_LEFT) . '_' . 
	    				$document_id;
	    
	    $this->folderPath2 = APPLICATION_PATH . '/user_images/uploaded_pdf_new/'
	       	               . substr(MD5($document_id), 0, 1).'/'
	       	               . substr(MD5($document_id), 1, 1).'/'
	                       . substr(MD5($document_id), 2, 1).'/PDF_' 
	                       . str_pad ( $form_number, 3, '0', STR_PAD_LEFT ) 
	                       . '_' . $document_id . '/';
	    
	    if (is_dir($this->folderPath2)) {
	        $this->folderPath = $this->folderPath2;
	    }
	    
		if(!is_dir($this->folderPath)) {
	    	// return error
			return false;
		}
		
	    try {
			while(is_file($this->folderPath . '/' . $data_file_to_delete) == TRUE)
	        {
	            chmod($this->folderPath . '/' . $data_file_to_delete, 0666);
	            unlink($this->folderPath . '/' . $data_file_to_delete);
	        }
	    	return true;
	    } catch (Exception $e) {
	    	return false;
	    }
        
	}
	
}
