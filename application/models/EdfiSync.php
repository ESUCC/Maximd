<?php

/**
 * 
 * Handles synchronization to EdFi for student 
 * special education program associations
 *
 * @author odiaz@doublelinepartners.com
 * @version 1.0
 *
 */ 

define("TOKENRETRY",     "TOKENRETRY");
define("PARTIALSYNCEND",     "PARTIALSYNCEND");

 class Model_EdfiSync {
	
	function writevar1($var1,$var2){
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
	
	
	/*DB params*/
	var $connstr="";
	var $APIUrl="";
	var $currToken="";
	var $edfi_client;
	var $edfi_models;
	var $updateList=array();

	/*List of dictricts to sync*/
	var $districts=array();
	var $pending_students=array();
	
	function __construct() {
		$this->edfi_client=new Model_EdfiClient(); 
		$this->edfi_models=new Model_EdfiJsonModels();
	}
	




	/*Set the connecion string*/
	function set_DbConnString($connString){
		$this->connstr=$connString;
	}

	function set_APIUrl($APIUrl){
		$this->APIUrl=$APIUrl;
	}
	


	public function receiveEdfiSync($edfiDistrictarray) {
		if(($this->connstr!="") and ($this->APIUrl!="")){

		 // $this->writevar1("", "Processing  " .  count($edfiDistrictarray) . " districts <br/><br/>");
            /*
             * This will look through each district that is edfi capable and look for the "W" in
             * the publish and do just that to the ods. If the ods is a success then a "S" is put 
             * in this field. If there is an error then an "E" gets put into this field.
             * 
             */
			foreach ($edfiDistrictarray as $value){
			 //  $this->writevar1($value,'this is the value');
			   
				$result="";

				while($result=="") {

					$result=$this->syncDistrict($value[0], $value[1], $value[2] , $value[3]);
	         
	          
	          // Returned PARTIALLYSYNCED				
	        //   $this->writevar1($result,'this is the results');
			  // When I change the db entry to W to simulate a finalized iep it works. 	
			  
					// Resutl 
					switch($result){
						case TOKENRETRY:
						/*IF token retry needed loops again to get a new one*/
						$result="";
						break;

						default:
						/*Commit pending changes to database*/
						$this->commitPendingUpdates();
						//$this->writevar1($result,'the result variable');
						// never seems to get here
					}

				} /*No more results*/ 
				
			} /*End districts loop*/
			

		//	$this->writevar1("", "Sync procces finished");
		 

		} else {

		//	$this->writevar1("", "Call set_DbConnString() and  set_APIUrl() to set configuration");

		}
	}



	private function commitPendingUpdates(){
		/* If pending db updates exists */
	 //  $this->writevar1($this->updateList,'this is the updatelist ');
		// always returned array(0);
		
		if(count($this->updateList)>0 ){
			/*Commit*/
			$con = pg_connect($this->connstr) or die ("Could not connect to server\n"); 
			foreach ($this->updateList as $query){
			//	  $this->writevar1("", "Executing " . $query);
				
				
				pg_query($con, $query);

			}
			pg_close($con); 
		}

		/*clears temp update list*/
		unset($this->updateList); 
		$this->updateList = array(); 
	}


	/*Get JSON representation from Students association*/
	private function getStudentJson($student){
	   
	  //  $this->writevar1($student,'student info from db line 136');
	   
	    
				$studentUniqueId=$student[8];
				$educationOrganizationId=$student[4];
				$beginDate=$student[9];
				$reasonExitedDescriptor=$student[11];
				$specialEducationSettingDescriptor=$student[12];
				$levelOfProgramParticipationDescriptor=$student[13];
				$placementTypeDescriptor=$student[14];
				$specialEducationPercentage=$student[15];
				$toTakeAlternateAssessment=$student[16];
				$endDate = $student[10];
				
				$disabilities=$student[20];
           //     $this->writevar1($student[20],'this should be disabilities');
				if(is_null($reasonExitedDescriptor)){
							$reasonExitedDescriptor="";
						}
 
						if(is_null($specialEducationSettingDescriptor)){
				 			$specialEducationSettingDescriptor="";
						}

						if(is_null($levelOfProgramParticipationDescriptor)){
							$levelOfProgramParticipationDescriptor="";
						}

						if(is_null($specialEducationPercentage)){
							$specialEducationPercentage=0;
						}

						if(is_null($endDate)){
							$endDate="";
						} else {
							$endDate=$endDate . "T00:00:00";
						}

						if(is_null($beginDate)){
							$beginDate="";
						} else {
							$beginDate=$beginDate . "T00:00:00";
						}
                        
						
						// Mike took this out because it was messing up the field
						// also added the =1 and =0 parts for true false
						//$toTakeAlternateAssessment="false";
						if(!is_null($toTakeAlternateAssessment)){
							if($toTakeAlternateAssessment==true){
								$toTakeAlternateAssessment="1";
								
							}
							else{
							    $toTakeAlternateAssessment='0';
							}
						} 

						$servicedescriptor_pt=$student[21];
						$servicedescriptor_ot=$student[22];
						$servicedescriptor_slt=$student[23];

						$servicebegindate_pt=$student[17];
						$servicebegindate_ot=$student[18];
						$servicebegindate_slt=$student[19];

						$services=array();
						if($servicedescriptor_pt!=0){
							array_push($services,array($servicedescriptor_pt,$servicebegindate_pt . "T00:00:00"));
						}

						if($servicedescriptor_ot!=0){
							array_push($services,array($servicedescriptor_ot,$servicebegindate_ot . "T00:00:00"));
						}

						if($servicedescriptor_slt!=0){
							array_push($services,array($servicedescriptor_slt,$servicebegindate_slt . "T00:00:00"));
						}

						$id="";
						$jsonUpdate = $this->edfi_models->createStudentSpecialEducationProgramAssociation(
  								$id,
        						$educationOrganizationId,
        						$studentUniqueId,
        						$beginDate,
        						$reasonExitedDescriptor,
        						$specialEducationSettingDescriptor,
       							$levelOfProgramParticipationDescriptor,
        						$placementTypeDescriptor,
        						$specialEducationPercentage,
        						$toTakeAlternateAssessment,
        						$endDate,
        						$services,
						        $disabilities
						); 
 
                   // $this->writevar1($jsonUpdate,'this is the jsonupdate line 219');
              //    $this->writevar1($jsonUpdate,'this is the jasonUpdate');
					return $jsonUpdate;

	}

	/*Synchronizes all pending students for a district*/
	public function syncDistrict($id_district, $id_county, $key, $secret){

		/* commit in case of reentrancy, ie after a token invalidation*/
		$this->commitPendingUpdates();
        //
        //
        
		
	//	$this->writevar1("",  "Get students for " . $id_district);	
		$this->pending_students = $this->getPendingSyncStudents($id_district,$id_county);
		
		//$this->writevar1($this->pending_students,'these are the pending studeents line 235');
		//$this->writevar1("", "Processing " . count($this->pending_students). " students for district " . $id_district);
        // student come back in array as expected
        
		
		//Get auth token for current disctrict call
	 	$this->currToken=$this->edfi_client->edfiApiAuthenticate($this->APIUrl,$key,$secret);
		
	//	$this->writevar1("TOKEN",$this->currToken);
        

		/*If a valid token is received*/
		if($this->currToken!=""){
			
			/*Sync each student*/
			foreach ($this->pending_students as $student){
				$studentUniqueId=$student[8];
              //  $this->writevar1($studentUniqueId,'this i the unique student id');
            //    $this->writevar1($this->edfi_client->studentExists($studentUniqueId),'the student exists');
				
			//	$this->writevar1("", "Found " . $studentUniqueId . "=" . "OK 1" );
				
				if($this->edfi_client->studentExists($studentUniqueId)){
			//		$this->writevar1("", "Found " . $studentUniqueId . "=" . "OK 2" );
						
								
						/*$result = $this->edfi_client->updateCurrentStudent();*/
						$data=$this->getStudentJson($student);
                       
						$result = $this->edfi_client->updateStudentSpecialEducationProgramAssociation($data);
                       // $this->writevar1($result,'this is the result');
                        
                   
						$status=$result->get_publishStatus();
					 // $this->writevar1("", "Status " . $status  );
					 // Just displays S or E

                         
						switch($result->get_publishStatus()){
							case "S":
								/*Sync data is OK*/
							//	$this->writevar1("", "Data OK");
								$this->updateStudent($studentUniqueId,
										$result->get_publishStatus(), 
										$result->get_resultCode(),
										$result->get_errorMessage()
										 );
							break;

							case "E":
								if($result->resultCode==401){
								//	$this->writevar1("", "401 get new toket");
									return TOKENRETRY;

								} else {
									// $this->writevar1("",  $result->errorMessage);
									$this->updateStudent($studentUniqueId,
										$result->get_publishStatus(), 
										$result->get_resultCode(),
										str_replace("'","''", $result->get_errorMessage()) 
										 );
								}
 							break;
						}

						 
				} else {
						/* What if?*/				
						}
			}

		} else {
			/*Wait for next sync proccess*/
		}

		return PARTIALSYNCEND;
	}
	
	
	/*Update stedent after PUT */
	private function updateStudent($id_student, $status, $code, $message){

		// $this->writevar1("",  "DB UPDATE " . $id_student . "=" . $status ); 
       //  $this->writevar1($id_student.' '.$status.' '.$message,' this is the id status and message');
	
	    
	    if($status!=""){
	        $d=date("h:i:sa");
	        $time=date('Y-m-d H:i:s');
			$query= "update edfi set edfipublishstatus='" . $status  . "' " .
					//", edfipublishtime=now() " .
					", edfipublishtime='".$time ."'".
					", edfierrormessage='" . $message  . "'" .
					", edfiresultcode=" . $code  . "" .
					" where studentuniqueid=" . $id_student;
			// $this->writevar1("",   $query ); 		
			$this->updateList[]=$query; 
		}
		
	}

	/*Get pending students for dictrict*/
	private function getPendingSyncStudents($district,$county){
		$students=array();
		
		$con = pg_connect($this->connstr) or die ("Could not connect to server\n"); 
		//$query = "select * from edfi where edfipublishstatus='W' and id_student in " .
		//		"(select id_student from iep_student where id_district='" . $district . "')";
		
		$query="select e.* from edfi e,iep_student s where e.edfipublishstatus='W' and 
		            s.id_student=e.id_student and s.id_district='".$district."' 
		            and s.id_county='".$county."'";
		
	//	$this->writevar1($query,'this is the bad query');
		// $this->writevar1($query,'this is the query');
		
		 $rs = pg_query($con, $query) or die("Cannot execute query: $query\n");
		  
     	 
		while ($row = pg_fetch_row($rs)) {
		  $students[]=$row;
		//  $this->writevar1($row,'this is the row data');
		}
		
		pg_close($con); 
	
	//	$this->writevar1($students,'here are the students');
		return $students;
		
	} 
	

	private function getCredential($id_county, $id_district){
		$credential = new credentials("","");
		$con = pg_connect($this->connstr) or die ("Could not connect to server\n"); 
		$query = "SELECT edfi_key, edfi_secret FROM iep_district WHERE id_county='" . $id_county .  "' AND id_district='" . $id_district . "'";
		 
		$rs = pg_query($con, $query) or die("Cannot execute query: $query\n");
		
		 if($rs){
			  if ( (pg_field_is_null($rs, 0, "edfi_key") == 0) and (pg_field_is_null($rs, 0, "edfi_secret") == 0)) {
				$row = pg_fetch_row($rs);
				$credential->set_key($row[0]);
				$credential->set_secret($row[1]);
     		 }
		 }
		
		pg_close($con); 
	
		return $credential;
	}


}




?>

