<?php

class FileUploadController extends Zend_Controller_Action {
	
	public function loadfileAction() {
		
		$this->_helper->layout->disableLayout ( true );
		
      	$adapter = new Zend_File_Transfer_Adapter_Http();
      	$adapter->setDestination(APPLICATION_PATH . '/../temp')
      			->addValidator('Filessize', false, 25000);;
      
      	$error = false;
      	if(!$adapter->receive()){
//         	Zend_Debug::dump($adapter->getMessages());
         	foreach ($adapter->getMessages() as $msg) {
         		if("File '' exceeds the defined ini size" == $msg) {
         			// no error
         		} else {
         			//Zend_Debug::dump($msg);
         			$error = true;
         		}
         	}
      	}
		
		$writer = new Zend_Log_Writer_Stream ( APPLICATION_PATH . '/../temp/loadfile.txt' );
		$log = new Zend_Log ( $writer );
		$log->log ( 'in loadfileaction', Zend_Log::DEBUG );
//		$log->log (print_r($_POST, true), Zend_Log::DEBUG );
		
		$messages = "";
		$response = 'success';

		
		$formNum = $this->getRequest()->getParam('formnum');
		$document = $this->getRequest()->getParam('document');
		$location = $this->getRequest()->getParam('location');
		
		$log->log ("document: $document", Zend_Log::DEBUG );
		$log->log ("formNum: $formNum", Zend_Log::DEBUG );
		
		
		$upload = new Zend_File_Transfer_Adapter_Http ();
		$upload->setDestination ( APPLICATION_PATH . '/../temp' );
		$upload->addValidator ( 'Extension', true, 'pdf' );
//		$upload->addValidator ( 'ImageSize', true, array ('minwidth' => 4 ) ); //i preform this check to make sure that it's a real
//		//image, since IsImage only checks MIME. Good Idea?
//		$upload->addValidator ( 'IsImage', false );
		$upload->addValidator ( 'Size', true, array ('min' => '5kB', 'max' => '10MB', 'bytestring' => false ) );
		
		$target = APPLICATION_PATH . '/user_images/uploaded_pdf/form' .basename($formNum.'_'.$document.'_'.$location).'.pdf';
		@unlink($target);
		$upload->addFilter('Rename',array('target' => $target));		
		
		$files = $upload->getFileInfo();
		foreach($files as $fileName => $fileData) {
			// $fileName = uploadedfile when file is html post
			// $fileName = flashUploadFiles file when file is html post
			if('flashUploadFiles' == $fileName) {
				$log->log ('flash file processing', Zend_Log::DEBUG );
				
				$returnFlashdata = true; //for dev
				$m = move_uploaded_file($_FILES[$fieldName]['tmp_name'],  $upload_path . $_FILES[$fieldName]['name']);
				$name = $_FILES[$fieldName]['name'];
				$file = $upload_path . $name;
				try{
				  list($width, $height) = getimagesize($file);
				} catch(Exception $e){
				  $width=0;
				  $height=0;
				}
				$type = getImageType($file);
				trace("file: " . $file ."  ".$type." ".$width);
				// 		Flash gets a string back:
				
				//exit;
				
				$data .='file='.$file.',name='.$name.',width='.$width.',height='.$height.',type='.$type;
				if($returnFlashdata){
					trace("returnFlashdata:\n=======================");
					trace($data);
					trace("=======================");
					// echo sends data to Flash:
					echo($data);
					// return is just to stop the script:
					return;
				}
			} elseif('uploadedfile' == $fileName) {
				$log->log ('html file processing 1', Zend_Log::DEBUG );
				$log->log (print_r($fileData, true), Zend_Log::DEBUG );
				$log->log ('00', Zend_Log::DEBUG );
				
				$uploadedFileName = $fileData['name'];
				//
				// 	If the data passed has 'uploadedfile', then it's HTML. 
				//	There may be better ways to check this, but this *is* just a test file.
				//
				if (!$upload->receive()) {
					$log->log ('01', Zend_Log::DEBUG );
					$messages = $upload->getMessages ();
					$log->log ( implode ( "\n", $messages ), Zend_Log::DEBUG );
					$messages = implode ( "\n", $messages );
				}
				$log->log ('02', Zend_Log::DEBUG );
				
			    // file uploaded ?
			    if (!$upload->isUploaded($file)) {
			        $log->log ("Why havn't you uploaded the file ?", Zend_Log::DEBUG );
			        continue;
			    }
			 	$log->log ('12', Zend_Log::DEBUG );
			 	
			    // validators are ok ?
			    if (!$upload->isValid($file)) {
			        $log->log ("Sorry but $file is not what we wanted", Zend_Log::DEBUG );
			        continue;
			    }		
			    $log->log ('22', Zend_Log::DEBUG );
			    
				$filePath = $upload->getFileName($file, false);
				$fileName = $upload->getFileName($file);
				$type = $upload->getMimeType();
				try{
				  list($width, $height) = getimagesize($filePath);
				} catch(Exception $e){
				  $width=0;
				  $height=0;
				}
				$log->log ('23', Zend_Log::DEBUG );
				  				
				$htmldata['id'] = '1';
				$htmldata['file'] = $fileName;
				$htmldata['name'] = $filePath;
				$htmldata['width'] = $width;
				$htmldata['height'] = $height;
				$htmldata['type'] = $type;
				$htmldata['size'] = filesize($file);
				$htmldata['uploadedFileName'] = $uploadedFileName;
				//				$htmldata['additionalParams'] = $postdata;

			    $items = array(
			        $htmldata
			    ); 				
				$log->log ( print_r($items, true), Zend_Log::DEBUG );
			    
				$jsonResponse = new Zend_Dojo_Data('id', $items);
				
				$this->view->data = '<TEXTAREA>'.$jsonResponse->toJson().'</textarea>';
				$log->log ( 'RETURNING:' . $this->view->data, Zend_Log::DEBUG );
				
			}
		}
		return $this->render ( 'data' );
	}
	
	public function viewpdfAction()
	{
		$formNum = $this->getRequest()->getParam('formnum');
		$document = $this->getRequest()->getParam('document');
		$location = $this->getRequest()->getParam('location');
			
	    // The path to the real pdf
	    $pdfFile = APPLICATION_PATH . '/user_images/uploaded_pdf/form' .$formNum.'_'.$document.'_'.$location.'.gif';
	
	    // Disable rendering
	    $this->_helper->viewRenderer->setNoRender(true);
	
	    // Send the right mime headers for the content type
	    $this->getResponse()
	         ->setBody('')
	         ->setHeader('Cache-control', 'public') // needed for IE, I have read
	         ->setHeader('Content-type', 'image/gif')
	         ->setHeader('Content-Disposition', sprintf('attachment; filename="%s"', basename($pdfFile)));
	
	    // Send the content
	    readfile($pdfFile);
	}
		
}