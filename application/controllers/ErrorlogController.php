<?php
        
class ErrorlogController extends My_Form_AbstractFormController {
 	
	
	public function testErrorAction() {
	}
	
	public function listAction() {
		
		$eLogObj = new Model_Table_Errorlog();
		$this->view->results = $eLogObj->fetchAll()->toArray();
		
	}
	
	public function jslistAction()
	{
		// hide left bar
		$this->view->hideLeftBar = true;
	
		$this->view->jQuery()->enable();
		$this->view->jQuery()->uiEnable();
		$this->view->jQuery()->addStylesheet('/js/jquery_addons/DataTables-1.8.2/media/css/demo_page.css', 'screen');
		$this->view->jQuery()->addStylesheet('/js/jquery_addons/DataTables-1.8.2/media/css/demo_table.css', 'screen');
		$this->view->jQuery()->addStylesheet('/js/jquery_addons/DataTables-1.8.2/media/css/demo_table_jui.css', 'screen');
	
		$this->view->jQuery()->addJavascriptFile('/js/jquery_addons/DataTables-1.8.2/media/js/jquery.dataTables.js');
		$this->view->jQuery()->addJavascriptFile('/js/jquery_addons/jquery.dataTables.columnFilter.js');
		$this->view->students = array(
			array('id_student'=>'Loading....', 'name_first'=>'', 'name_last'=>''),
		);
	}
	
	public function getJsErrLogAction()
	{
		// config datatable
		// determins order of columns in display
		$aColumns = array(
			'timestamp_created', 
			'userid', 
			'user_name',
			'computer_platform',
			'browser_type',
			'browser_version',
			'sn', 
			'err', 
			'fl',
			'ln', 
		);
		$ilikColumns = array('user_name', 'computer_platform', 'browser_type', 'browser_version', 'sn', 'err', 'fl');

		$dt = new App_JQueryDatatable();
		$dt->setSwhere("timestamp_created >= (now()- interval '1 week')");
		echo $dt->datatable($aColumns, $ilikColumns, "js_err_log", "js_err_log_id");
		exit;
	}

	public function logAction() {
// 		error_reporting(1);
// 		    	Zend_Debug::dump($this->getRequest()->getParams());
// 		    	die();
// 		try {

		// get browser object for reporting platform and browser info
		require_once APPLICATION_PATH.'/../library/App/Classes/Browser.php';
		$browser = new Browser();
		
			$data = $this->getRequest()->getParams();
			$data['computer_platform'] = $browser->getPlatform();
			$data['browser_type'] = $browser->getBrowser();
			$data['browser_version'] = $browser->getVersion();
			
			Model_Table_JsErrLog::log($data);
			echo "jsErrLog.removeScript('".$this->getRequest()->getParam('i')."');console.debug('errors logged');";
// 		} catch (Exception $e) {
// 			echo "console.debug('Error with the JS debugger: ' . $e);";
// 		}
		exit;
	}
	
}
