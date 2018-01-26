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

/*
 * Mike finished this 1-25-2017 so that a person such as a site admin for multiple sites can find a student.  Some staff have
 * no less than 14 sites they are managing.
 *
 */

        $x=0;
        $sysAdmin=false;
        $found=false;
      //  $this->writevar1($_SESSION['user']['user']->privs[0]['class'],'these are the privs');
	    foreach($_SESSION['user']['user']->privs as $priv)  {
	        if ($priv['class']==1 && $priv['status']=='Active') $sysAdmin=true;
	        if ($priv['class']<=6 && $priv['status']=='Active') {
               if(!$found ){


                $keySecret= new Model_Table_District();
	            $t=$keySecret->getKeys($priv['id_county'],$priv['id_district']);
                $key=$t['edfi_key'];
                $secret=$t['edfi_secret'];

                if($key!=null and $secret!=null and !$found){
                $edFiClientDraft = new Model_DraftEdfiClient("https://adviserods.nebraskacloud.org/api/",$key,$secret);
                $student_id = $this->_getParam('student_id');
                $jsonStudent = $edFiClientDraft->getStudent($student_id);
                if(isset($jsonStudent->id)) $found=true;

                }

               }

	        }
	    }  // End the for each

	    // Check for system admin. This one takes a while to complete.

	    if($sysAdmin==true){
	        $dist= new Model_Table_District();
	        $allDistricts=$dist->getAllDists();
            $found=false;
            $student_id = $this->_getParam('student_id');
            foreach($allDistricts as $dist){
               if($dist['edfi_key']!=null and $dist['edfi_secret']!=null and $found==false){
                   $edFiClientDraft= new Model_DraftEdfiClient("https://adviserods.nebraskacloud.org/api/",$dist['edfi_key'],$dist['edfi_secret']);
                   $jsonStudent = $edFiClientDraft->getStudent($student_id);
                   if(isset($jsonStudent->id)) $found=true;

               }

            }
	    }
// End of Mike add 1-25-2017





	    // Mike Changed this 12-15-2017 and it works.  Put it back to the sandbox for Wade demo




	    $student_id = $this->_getParam('student_id');


	 // $edFiClientDraft = new Model_DraftEdfiClient("https://sandbox.nebraskacloud.org/ng/api", "g3uiYKK0Pros", "bjRB3D3ahbsV33YgXxApZLyG");

	  // https://adviserstagingods.nebraskacloud.org/api
	  //  $edFiClientDraft = new Model_DraftEdfiClient("https://adviserstagingods.nebraskacloud.org/api","6BA1704F69654457", "9138D4CA4CFE");
	  // $edFiClientDraft = new Model_DraftEdfiClient("https://adviserods.nebraskacloud.org/api/","5268BB4BE4B3458B","393D3CC9B1E4");
	    // Winnebago
	  //  $key="72211943390944B7";
	  //  $secret="F1510515770B";
	//    $edFiClientDraft = new Model_DraftEdfiClient("https://adviserods.nebraskacloud.org/api/","72211943390944B7","F1510515770B");
	  //   $edFiClientDraft = new Model_DraftEdfiClient("https://adviserods.nebraskacloud.org/api/",$key,$secret);
	  // $jsonStudent = $edFiClientDraft->getStudent($student_id);
	//   $this->writevar1($jsonStudent,'this is the json student');

 		$this->getHelper('Layout')->disableLayout();
    	$this->getHelper('ViewRenderer')->setNoRender();
    	$this->getResponse()->setHeader('Content-Type', 'application/json');

    	return $this->_helper->json->sendJson($jsonStudent, true);
	}



/*


ODS URLs:
2017-2018 API URL: https://adviserods.nebraskacloud.org/api/api/v2.0/2018/

2017-2018 Authentication URL: https://adviserods.nebraskacloud.org/api/

	 *
	 *
	 *
	 */

	public function studentparentsAction() {
	     $student_id = $this->_getParam('student_id');
	//   $edFiClientDraft = new Model_DraftEdfiClient("https://sandbox.nebraskacloud.org/ng/api", "g3uiYKK0Pros", "bjRB3D3ahbsV33YgXxApZLyG");
	    //  $edFiClientDraft = new Model_DraftEdfiClient("https://adviserstagingods.nebraskacloud.org/api","6BA1704F69654457", "9138D4CA4CFE");
	  //   $edFiClientDraft = new Model_DraftEdfiClient("https://adviserods.nebraskacloud.org/api/","5268BB4BE4B3458B","393D3CC9B1E4");

	   // Winnebago
	     $edFiClientDraft = new Model_DraftEdfiClient("https://adviserods.nebraskacloud.org/api/","72211943390944B7","F1510515770B");

	    $jsonParents = $edFiClientDraft->getParents($student_id);

	    $this->getHelper('Layout')->disableLayout();
	    $this->getHelper('ViewRenderer')->setNoRender();
	    $this->getResponse()->setHeader('Content-Type', 'application/json');

	    return $this->_helper->json->sendJson($jsonParents, true);
	}

}
