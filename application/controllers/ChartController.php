<?

class ChartController extends App_Zend_Controller_Action_Abstract {

    
    public function preDispatch() {

        //Zend_Debug::dump();
        
	    $tk = new Model_Table_IepSession();
		$session = $tk->getSessionBySessId($this->getRequest()->getParam('PHPSESSID'));
		
    	//https://iepweb02.unl.edu/chart/chart/PHPSESSID/gu94io8esn5glvtu3g1umbouc4/chartid/3282
		//Zend_Debug::dump($this->getRequest()->getParam('PHPSESSID'));
		// all controllers that extend App_Zend_Controller_Action_Abstract
		// require user to be logged in
		
		$sessObj = new Model_Table_IepSessionZend();
		
		if($this->getRequest()->getParam('PHPSESSID')) {
			$sessionRec = $sessObj->getSessionRecordBySessId($this->getRequest()->getParam('PHPSESSID'));
			if('Active' != $sessionRec['status'] || true != $sessionRec['siteaccessgranted']) {
	    		return $this->_redirect('https://iep.esucc.org/srs.php?area=personnel&sub=gettoken&destination='.str_replace('/', '-', $_SERVER['REQUEST_URI']));
			}
		} elseif(!App_Helper_Session::siteAccessGranted()) {
	    	if('production' == APPLICATION_ENV) {
	    		// try to get the token from iep and relogin
	    		return $this->_redirect('https://iep.esucc.org/srs.php?area=personnel&sub=gettoken&destination='.str_replace('/', '-', $_SERVER['REQUEST_URI']));
	    	} else {
	    		// redirect home
	    		return $this->redirectWithMessage('/', "You are not logged in an cannot access this image.");
	    	}
    	}
	    // logged in	
	}
	
	function chartAction() {
        
        set_include_path(get_include_path() . PATH_SEPARATOR . APPLICATION_PATH . '/..');

        require_once ('library/jpgraph/src/jpgraph.php');
        require_once ('library/jpgraph/src/jpgraph_line.php');
        require_once( "library/jpgraph/src/jpgraph_date.php" );
        
        require_once('library/jpgraph/src/jpgraph_utils.inc.php');
    
        include "library/jpgraph/src/jpgraph_scatter.php";
        include "library/jpgraph/src/jpgraph_regstat.php";

		// we disable the layout because we're returning an image
		$this->_helper->layout->disableLayout(true);
		$response = Zend_Controller_Front::getInstance()->getResponse();
		$response->setHeader('Content-Type', 'image/png', true); 
     	$this->view->img = App_Classes_StudentChart::renderJGraph($this->getRequest()->getParam('chartid'));

	}
}