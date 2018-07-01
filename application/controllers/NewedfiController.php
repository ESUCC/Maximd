<?php

class NewedfiController extends Zend_Controller_Action {

	public function studentAction() {
	    $student_id = $this->_getParam('student_id');
	    $edFiClientDraft = new Model_DraftEdfiClient("https://sandbox.nebraskacloud.org/ng/api", "g3uiYKK0Pros", "bjRB3D3ahbsV33YgXxApZLyG");
	    $jsonStudent = $edFiClientDraft->getStudent($student_id);

 		$this->getHelper('Layout')->disableLayout();
    	$this->getHelper('ViewRenderer')->setNoRender();
    	$this->getResponse()->setHeader('Content-Type', 'application/json');

    	return $this->_helper->json->sendJson($jsonStudent, true);
	}
	
	public function studentparentsAction() {
	    $student_id = $this->_getParam('student_id');
	    $edFiClientDraft = new Model_DraftEdfiClient("https://sandbox.nebraskacloud.org/ng/api", "g3uiYKK0Pros", "bjRB3D3ahbsV33YgXxApZLyG");
	    $jsonParents = $edFiClientDraft->getParents($student_id);
	    
	    $this->getHelper('Layout')->disableLayout();
	    $this->getHelper('ViewRenderer')->setNoRender();
	    $this->getResponse()->setHeader('Content-Type', 'application/json');
	    
	    return $this->_helper->json->sendJson($jsonParents, true);
	}

}
