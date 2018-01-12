<?php

class NewedfiController extends Zend_Controller_Action {

    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp); 
    }
    
	public function studentAction() {
	    // Mike Changed this 12-15-2017 and it works.  Put it back to the sandbox for Wade demo
	    $student_id = $this->_getParam('student_id');
	   $edFiClientDraft = new Model_DraftEdfiClient("https://sandbox.nebraskacloud.org/ng/api", "g3uiYKK0Pros", "bjRB3D3ahbsV33YgXxApZLyG");
	  // https://adviserstagingods.nebraskacloud.org/api
	  // $edFiClientDraft = new Model_DraftEdfiClient("https://adviserstagingods.nebraskacloud.org/api","6BA1704F69654457", "9138D4CA4CFE");
	    $jsonStudent = $edFiClientDraft->getStudent($student_id);

 		$this->getHelper('Layout')->disableLayout();
    	$this->getHelper('ViewRenderer')->setNoRender();
    	$this->getResponse()->setHeader('Content-Type', 'application/json');
  //      $this->writevar1($jsonStudent,'this is the jason');
    	return $this->_helper->json->sendJson($jsonStudent, true);
	}
	
	public function studentparentsAction() {
	    $student_id = $this->_getParam('student_id');
	   $edFiClientDraft = new Model_DraftEdfiClient("https://sandbox.nebraskacloud.org/ng/api", "g3uiYKK0Pros", "bjRB3D3ahbsV33YgXxApZLyG");
	  // $edFiClientDraft = new Model_DraftEdfiClient("https://adviserstagingods.nebraskacloud.org/api", "6BA1704F69654457","9138D4CA4CFE");
	     
//	    $jsonParents = $edFiClientDraft->getParents($student_id);
	    
	    $this->getHelper('Layout')->disableLayout();
	    $this->getHelper('ViewRenderer')->setNoRender();
	    $this->getResponse()->setHeader('Content-Type', 'application/json');
	    
	    return $this->_helper->json->sendJson($jsonParents, true);
	}

} 
