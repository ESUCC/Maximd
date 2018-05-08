<?

class ErrorReportingController extends App_Zend_Controller_Action_Abstract {

    
//    public function preDispatch() {  
//
//	    $tk = new Model_Table_IepSession();
//		$session = $tk->getSessionBySessId($this->getRequest()->getParam('PHPSESSID'));
//		
//		$sessObj = new Model_Table_IepSessionZend(); 
//		if($this->getRequest()->getParam('PHPSESSID')) {
//			$sessionRec = $sessObj->getSessionRecordBySessId($this->getRequest()->getParam('PHPSESSID'));
//			if('Active' != $sessionRec['status'] || true != $sessionRec['siteaccessgranted']) {
//	    		return $this->_redirect('https://iep.unl.eduu/srs.php?area=personnel&sub=gettoken&destination='.str_replace('/', '-', $_SERVER['REQUEST_URI']));
//			}
//		} elseif(!App_Helper_Session::siteAccessGranted()) {
//	    	if('production' == APPLICATION_ENV) {
//	    		// try to get the token from iep and relogin
//	    		return $this->_redirect('https://iep.unl.eduu/srs.php?area=personnel&sub=gettoken&destination='.str_replace('/', '-', $_SERVER['REQUEST_URI']));
//	    	} else {
//	    		// redirect home
//	    		return $this->redirectWithMessage('/', "You are not logged in an cannot access this image.");
//	    	}
//    	}
//	    // logged in	
//	}
	
	function reportAction() {
        
		// ajax returned - no layout info
		$this->_helper->layout->disableLayout ( true );
		
		// get passed parameters 
		$data = Zend_Json::decode ( $this->getRequest ()->getParam ( 'data' ) );
		
		// get browser object for reporting platform and browser info
		require_once APPLICATION_PATH.'/../library/App/Classes/Browser.php';
		$browser = new Browser();
	    $sessUser = new Zend_Session_Namespace ( 'user' );
		$config = Zend_Registry::get ( 'config' );
		
	    // Create transport
		if(isset($config->errorReporter->auth) && null != $config->errorReporter->auth) {
			$transport = new Zend_Mail_Transport_Smtp($config->errorReporter->host, $config->errorReporter->toArray());
		} else {
			// probably production
			// no auth needed
			$transport = new Zend_Mail_Transport_Smtp($config->errorReporter->host);
		}
	    
	    // Set From & Reply-To address and name for all emails to send.
	    Zend_Mail::setDefaultFrom($config->errorReporter->from, 'SRS Help');
	    Zend_Mail::setDefaultReplyTo($config->errorReporter->from, 'SRS Help');
		
	    // build the message
		$msg = "Name of Reporter: " . $sessUser->user->user['name_first'] . ' ' . $sessUser->user->user['name_last'] ."\n";
	    $msg .= "Email Address of Reporter: " . $sessUser->user->user['email_address'] ."\n";
	    $msg .= "Computer Platform: " . $browser->getPlatform() ."\n";
	    $msg .= "Browser Type: " . $browser->getBrowser() ."\n";
	    $msg .= "Browser Version: " . $browser->getVersion() ."\n";
	    $msg .= "Form Number: " . $data['formNumber'] ."\n";
	    $msg .= "Form Id: " . $data['formId'] ."\n";
	    $msg .= "Student Id: " . $data['studentId'] ."\n";
	    $msg .= "Page: " . $data['pageNumber'] ."\n";
	    $msg .= "Link: " . $data['link'] ."\n";
	    $msg .= "\n";
	    $msg .= "Error Description: " . $data['errorDescription'] ."\n";
	    
	    // instanciate the mail object
		$mail = new Zend_Mail();
		
	    // bellevue has a user that should be cc'd
	    // ------------------------------------------------------------------------
	    $stuObj = new Model_Table_StudentTable();
	    $student = $stuObj->find($data['studentId'])->current()->toArray();
//	    if(isset($student['id_county']) && isset($student['id_district']) &&
//			'77' == $student['id_county'] && '0001' == $student['id_district']) {
//	    	$msg .= "additional cc for bellevue:";
//	    }
	    // ------------------------------------------------------------------------
	    
	    
	    $mail->setBodyText($msg);
		$mail->setSubject('SRS ZF Error Report from '. $sessUser->user->user['user_name']);
	    
	    // add/Loop through recipients
	    foreach ($config->errorReporter->to->toArray() as $codedToArr) {
	    	list($email, $name) = explode(',', $codedToArr);
			$mail->addTo($email, $name);
	    }

		// ------------------------------------------------------------------------
		// mandrade_5819@yahoo.com
		// replace with above address once we confirm that these are only going to bellevue
	    if(isset($student['id_county']) && isset($student['id_district']) &&
			'77' == $student['id_county'] && '0001' == $student['id_district']) {
	    	$mail->addCc('mandrade_5819@yahoo.com', 'Bellevue Manager - ');
	    }
	    // ------------------------------------------------------------------------
	    
	    // send the email
	    $result = $mail->send($transport);
	    $returnData = $result ? 'success' : 'error';  
	    
		// Reset defaults
	    Zend_Mail::clearDefaultFrom();
	    Zend_Mail::clearDefaultReplyTo();	    
	    
	    
		$items = array (array ('id_editor' => 1, 'id_editor_data' => $returnData ) );
		$this->view->data = new Zend_Dojo_Data ( 'id_editor_data', $items );
		return $this->render ( 'data' );
	}
}