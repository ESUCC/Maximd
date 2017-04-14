<?php

class PgSyncController extends App_Zend_Controller_Action_Abstract {
	
	function indexAction() {
		
		$path = APPLICATION_PATH . '/../library/Pgdbsync/';
		set_include_path(get_include_path() . PATH_SEPARATOR . $path);
		include("lib/Pgdbsync/includes.php");
		$conf  = parse_ini_file(APPLICATION_PATH . "/configs/pgsync-conf.ini", true);
		
		$this->view->from_database = 'jesselocalprimary'; //primary';
		$this->view->to_database = 'jesselocalarchive'; //archive';
		
		$this->view->fromConf = $conf[$this->view->from_database];
		$this->view->toConf = $conf[$this->view->to_database];
		
		if($this->getRequest()->getParam('database_action')) {
			$this->view->database_action = $this->getRequest()->getParam('database_action');
		} else {
			$this->view->database_action = 'summary';
		}
		$sessUser = new Zend_Session_Namespace ( 'user' );
		if (1000254 == $sessUser->sessIdUser || 1010818 == $sessUser->sessIdUser) {
			// allow access
		} else {
			throw new Exception ( 'You do not have permission to view this area.' );
			return;
		}
		
		$dbVc = new Pgdbsync\Db();
		$dbVc->setMasrer(new Pgdbsync\DbConn($this->view->fromConf));
		$dbVc->setSlave(new Pgdbsync\DbConn($this->view->toConf));

		if('diff' == $this->getRequest()->getParam('database_action')) {
			$this->view->result = $dbVc->diff('public');
		} elseif('run' == $this->getRequest()->getParam('database_action')) {
			$errors = $dbVc->run('public');
			if(0 == count($errors)) {
				$this->view->result = "Sync Done";
			} else {
				$this->view->result = implode("\n", array_shift(array_shift($errors)));
			}
		} else {
			$this->view->result = $dbVc->summary('public');
		}
		
		
	}
}