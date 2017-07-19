<?php

class App_Auth_StudentAuthenticator {
	
	protected $errorMessage;	
	
	const ERR_NOT_FOUND 	= "student not found";
	const ERR_NO_ACCESS 	= "access not allowed"; 
	
	public function validateStudentAccess($id_student, $userObj)
	{
		// get the student
		$studentObj = new Model_Table_IepStudent();
		$studentRows = $studentObj->find($id_student);
		if(false == $studentRows)
		{
			$this->errorMessage = self::ERR_NOT_FOUND;
			return false;		
		} else {
			$student = $studentRows[0];
		}

		if($userObj->parent) {
            $formAccess = new stdClass();
            $formAccess->access_level      = App_FormRoles::FORM_VIEW;
            $formAccess->description = "Guardian";
            return $formAccess;
						
		} else {
				
			
	        // $userObj is created at login
			// and should contain the user privs
			foreach($userObj->user->privs as $priv)
			{
//				Zend_Debug::dump($priv);
				if(1 == $priv['class'])
				{
					$formAccess = new stdClass();
					$formAccess->access_level	   = App_FormRoles::FORM_EDIT;
					$formAccess->description = "Admin";
					return $formAccess;
						
				} elseif(	2 == $priv['class'] && 
							$priv['id_county'] == $student['id_county'] &&
							$priv['id_district'] == $student['id_district']
							) {
					$formAccess = new stdClass();
					$formAccess->access_level	   = App_FormRoles::FORM_EDIT;
					$formAccess->description = "District Manager";
					return $formAccess;
	
				} elseif(	3 == $priv['class'] && 
							$priv['id_county'] == $student['id_county'] &&
							$priv['id_district'] == $student['id_district']
							) {
					$formAccess = new stdClass();
					$formAccess->access_level	   = App_FormRoles::FORM_EDIT;
					$formAccess->description = "Associate District Manager";
					return $formAccess;
					
				} elseif(	4 == $priv['class'] && 
							$priv['id_county'] == $student['id_county'] &&
							$priv['id_district'] == $student['id_district'] &&
							$priv['id_school'] == $student['id_school']
							) {
					$formAccess = new stdClass();
					$formAccess->access_level	   = App_FormRoles::FORM_EDIT;
					$formAccess->description = "School Manager";
					return $formAccess;
					
				} elseif(	5 == $priv['class'] && 
							$priv['id_county'] == $student['id_county'] &&
							$priv['id_district'] == $student['id_district'] &&
							$priv['id_school'] == $student['id_school']
							) {
					$formAccess = new stdClass();
					$formAccess->access_level	   = App_FormRoles::FORM_EDIT;
					$formAccess->description = "Associate School Manager";
					return $formAccess;
					
				} elseif(	6 == $priv['class'] && 
	//						$priv['id_county'] == $student['id_county'] &&
	//						$priv['id_district'] == $student['id_district'] &&
	//						$priv['id_school'] == $student['id_school'] &&
							$userObj->user->user['id_personnel'] == $student['id_case_mgr']
							) {
					$formAccess = new stdClass();
					$formAccess->access_level	   = App_FormRoles::FORM_EDIT;
					$formAccess->description = "Case Manager";
					return $formAccess;
					
		//		} elseif(	7 == $priv['class'] && 
		//					$priv['id_county'] == $student['id_county'] &&
		//					$priv['id_district'] == $student['id_district'] &&
		//					$priv['id_school'] == $student['id_school']
		//					) {
		//			$formAccess = new stdClass();
		//			$formAccess->access_level	   = App_FormRoles::FORM_EDIT;
		//			$formAccess->description = "Student Team";
					//$formAccess->description = "Team Member View";
		//			return $formAccess;
					
	//			} elseif(	8 == $priv['class'] && 
	//						$userObj->user->user['id_personnel'] == $student['id_ei_case_mgr']
	//						) {
	//				$formAccess = new stdClass();
	//				$formAccess->access_level	   = App_FormRoles::FORM_EDIT;
	//				$formAccess->description = "Specialist";
	//				return $formAccess;
	//				
	//			} elseif(	9 == $priv['class'] && 
	//						$priv['id_county'] == $student['id_county'] &&
	//						$priv['id_district'] == $student['id_district'] &&
	//						$priv['id_school'] == $student['id_school']
	//						) {
	//				$formAccess = new stdClass();
	//				$formAccess->access_level	   = App_FormRoles::FORM_EDIT;
	//				$formAccess->description = "Parent/guardian";
	//				return $formAccess;
	//				
	//			} elseif(	10 == $priv['class'] && 
	//						$userObj->user->user['id_personnel'] == $student['id_ser_cord']
	//						) {
	//				$formAccess = new stdClass();
	//				$formAccess->access_level	   = App_FormRoles::FORM_EDIT;
	//				$formAccess->description = "Service Coordinator";
	//				return $formAccess;
				}
	
				//define( "UC_SA",  1);       // system admin
				//define( "UC_DM",  2);       // district manager
				//define( "UC_ADM", 3);       // associate district manager
				//define( "UC_SM",  4);       // school manager
				//define( "UC_ASM", 5);       // associate school manager
				//define( "UC_CM",  6);       // case manager
				//define( "UC_SS",  7);       // school staff
				//define( "UC_SP",  8);       // specialist
				//define( "UC_PG",  9);       // parent/guardian
				//define( "UC_SC",  10);      // service coordinator
	
			}
//			Zend_Debug::dump($userObj->user->user['id_personnel'] .' - '. $student['id_case_mgr']);
//			die();

            if( $userObj->user->user['id_personnel'] == $student['id_ser_cord'] ) {
                $formAccess = new stdClass();
                $formAccess->access_level      = App_FormRoles::FORM_EDIT;
                $formAccess->description = "Service Coordinator";
                return $formAccess;
            }

	        // is user on student team
	        $modelName      = 'Model_Table_StudentTeamMember';
	        $select         = $student->select()->where("status = 'Active' and id_personnel = '".$userObj->user->user['id_personnel']."'")->order('timestamp_created ASC');
	        $teamMember     = $student->findDependentRowset($modelName, 'Model_Table_IepStudent', $select);
			if($teamMember->count() > 0) {
	            $formAccess = new stdClass();
	            if($teamMember[0]['flag_create']) {
	            	$formAccess->access_level      = App_FormRoles::FORM_CREATE;
	            } elseif($teamMember[0]['flag_edit']) {
	            	$formAccess->access_level      = App_FormRoles::FORM_EDIT;
	            } elseif($teamMember[0]['flag_view']) {
	            	$formAccess->access_level      = App_FormRoles::FORM_VIEW;
	            }
	            
	            $formAccess->description = "Team Member";
	            return $formAccess;
			}
			
	        if( $userObj->user->user['id_personnel'] == $student['id_ei_case_mgr'] ) {
	            $formAccess = new stdClass();
	            $formAccess->access_level      = App_FormRoles::FORM_EDIT;
	            $formAccess->description = "Specialist";
	            return $formAccess;
	        }
		}
        
        if (isset($this->errorMessage)) {
			return false;
		}
		
		$this->errorMessage = self::ERR_NO_ACCESS;
		return false;		
	}
	public function getErrorMessage()
	{
		return $this->errorMessage;
	}
	
}
