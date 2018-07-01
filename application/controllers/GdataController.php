<?php
class GdataController extends Zend_Controller_Action 
{
    private $googleUser = 'wadelovescheese@gmail.com';
    private $googlePass = 'wadelikesmoney';
    private $googleToken;
    	
	public function indexAction()
	{
		$fileToUpload = APPLICATION_PATH . '/../data/test.html';
		$service = Zend_Gdata_Docs::AUTH_SERVICE_NAME;
		$client = Zend_Gdata_ClientLogin::getHttpClient($this->googleUser, $this->googlePass, $service);
		$docs = new Zend_Gdata_Docs($client);
		$newDocumentEntry = $docs->uploadFile($fileToUpload, 'test.html',
				null, Zend_Gdata_Docs::DOCUMENTS_LIST_FEED_URI);
		list($url, $docId) = explode('%3A', $newDocumentEntry->id->text);
		$doc = $docs->getDocument($docId, 'html');
		$client->setUri($doc->getContent()->getSrc());
		$response = $client->request();
		echo $response->getBody();
	}
	
	public function deleteAction()
	{
		$service = Zend_Gdata_Docs::AUTH_SERVICE_NAME;
		$client = Zend_Gdata_ClientLogin::getHttpClient($this->googleUser, $this->googlePass, $service);
		$docs = new Zend_Gdata_Docs($client);
		$doc = $docs->getDocument($this->getRequest()->getParam('docId'), 'html');
		$doc->delete();
		exit;
	}
	
	function setupClient($singleUseToken = null) {
	    $client = null;
	
	    // Fetch a new AuthSub token?
	    if (!$singleUseToken) {
	        $next = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	        $scope = 'https://www.google.com/health/feeds';
	        $authSubHandler = 'https://www.google.com/health/authsub';
	        $secure = 1;
	        $session = 1;
	        $permission = 1;  // 1 - allows posting notices && allows reading profile data
	        $authSubURL =  Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure, $session, $authSubHandler);
	
	        $authSubURL .= '&permission=' . $permission;
	
	        echo '<a href="' . $authSubURL . '">Link your Google Health Account</a>';
	    } else {
	        $client = new Zend_Gdata_HttpClient();
	
	        // This sets your private key to be used to sign subsequent requests
	        $client->setAuthSubPrivateKeyFile('/path/to/myrsakey.pem', null, true);
	
	        $sessionToken = Zend_Gdata_AuthSub::getAuthSubSessionToken(trim($singleUseToken), $client);
	        // Set the long-lived session token for subsequent requests
	        $client->setAuthSubToken($sessionToken);
	    }
	    return $client;
	}	
	public function processEditorAction()
	{
	    $returnData = $this->getRequest()->getParam('data');
	    $googleProcessSuccess = true;
	    
	    
	    //
	    // if designated, process through google
	    //
    	if($this->getRequest()->getParam('sendToGoogle')) {
    	    $googleProcessSuccess = false;
    	    
    	    /*
    	     * build a temp file for uploading to google
    	     */
	    	$fileToUpload = tempnam('/tmp', 'GOO');
	    	file_put_contents($fileToUpload, $this->getRequest()->getParam('data'));
	    	rename($fileToUpload, $fileToUpload.'.html');
	    	$fileToUpload = $fileToUpload.'.html';

	    	try {
	    	    // login and get the docs client
	    	    $client = Zend_Gdata_ClientLogin::getHttpClient($this->googleUser, $this->googlePass, Zend_Gdata_Docs::AUTH_SERVICE_NAME);
	    	} catch (Zend_Gdata_App_CaptchaRequiredException $cre) {
	    	    //echo 'URL of CAPTCHA image: ' . $cre->getCaptchaUrl() . "\n";
	    	    //echo 'Token ID: ' . $cre->getCaptchaToken() . "\n";
	    	} catch (Zend_Gdata_App_AuthException $ae) {
	    	    //echo 'Problem authenticating: ' . $ae->exception() . "\n";
	    	}
	    	
	    	try {
	    	    // setup client for google docs
    	    	$docs = new Zend_Gdata_Docs($client);
    	    	
    	    	// upload our doc
    	    	$newDocumentEntry = $docs->uploadFile($fileToUpload, 'test.html', null, 'https://docs.google.com/feeds/documents/private/full');
    	    	
    	    	// get the doc id of our uploaded doc and build the url for fetching
    	    	list($url, $docId) = explode('%3A', $newDocumentEntry->id->text);
    	    	$strURL = "https://docs.google.com/feeds/download/documents/export/Export?id=".$docId;
        	    
    	    	// fetch the doc and get it's contents
    	    	// strip out unwanted html wrapping
    	    	$fetchedData = $docs->get($strURL)->getBody();
    	    	$fetchedData = str_replace('<html><head><title>test.html</title>', '', $fetchedData);
    	    	$fetchedData = str_replace('<body>', '', $fetchedData);
    	    	$fetchedData = str_replace('</body></html>', '', $fetchedData);
    	    	
    	    	$returnData = $fetchedData;
    	    	
	    	    // delete the temp file
    	    	unlink($fileToUpload);
    	    	$googleProcessSuccess = true;
	    	} catch(Zend_Gdata_App_HttpException $e) {
	    	    // delete the temp file
	    	    unlink($fileToUpload);
	    	    // get the exception message
	    	    $message = $e->getMessage();
	    	}
    	}
    	
    	// save to editor history
    	$editorHistorySuccess = false;
    	try {
    	    $sessUser = new Zend_Session_Namespace ( 'user' );
    	    	
    	    // save the editor - $editor->getValue ()
    	    // insert into editor_save_log
    	    $editorLogObj = new Model_Table_EditorSaveLog();
    	    $data = array(
    	            'form_number' => $this->getRequest()->getParam('form_number'),
    	            'id_form' => $this->getRequest()->getParam('id_form'),
    	            'field_name' => $this->getRequest()->getParam ( 'id_editor' ),
    	            'field_value' => $returnData,
    	            'id_user' => $sessUser->sessIdUser,
    	    );
    	    if(false!=$editorLogObj->insert($data)) {
    	        $editorHistorySuccess = true;
    	    }
    	} catch (Exception $e) {
    	    // failed to write to editor history
    	}
    	 
    	echo Zend_Json::encode(array('response' => $returnData, 'googleProcessSuccess' => $googleProcessSuccess, 'editorHistorySuccess' => $editorHistorySuccess));
    	exit;
	}
    
}



