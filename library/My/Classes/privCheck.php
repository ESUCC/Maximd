<?

class My_Classes_privCheck {

	public $sessUserPrivs;		// THE ARRAY OF USER PRIVS
	public $sessUserMinPriv;	// THE MINIMUM PRIVILEGE (this actually wont make sense if we go to non-liniar structure)

	public $accessObj;			# object created by the build access class
	public $accessArray;		# array returned to define access
	public $studentArea;		
	public $accessArrName;		# name of the access to this student
	public $accessArrDesc;		# description of the access to this student

		// user classes
	public $UC_SA;			// system admin
	public $UC_DM;			// district manager
	public $UC_ADM;		// associate district manager
	public $UC_SM;			// school manager
	public $UC_ASM;		// associate school manager
	public $UC_CM;			// case manager
	public $UC_SS;			// school staff
	public $UC_SP;			// specialist
	public $UC_PG;			// parent/guardian
	public $UC_SC;			// service coordinator
	
	public $standardPrivOrderArray = array(1,2,3,4,5,6,7,8,9);
	public $standardPrivNameArray = array("UC_SA","UC_DM","UC_ADM","UC_SM","UC_ASM","UC_CM","UC_SS","UC_SP","UC_PG","UC_SC");
	// =============================================================================
	// =============================================================================
	function __construct($sessUserPrivs, $parent = false) {
		//
		// SET INITIAL PARAMATERS
		$this->sessUserPrivs = $sessUserPrivs;
		$this->UC_SA  = 1;			// system admin
		$this->UC_DM  = 2;			// district manager
		$this->UC_ADM = 3;			// associate district manager
		$this->UC_SM  = 4;			// school manager
		$this->UC_ASM = 5;			// associate school manager
		$this->UC_CM  = 6;			// case manager
		$this->UC_SS  = 7;			// school staff
		$this->UC_SP  = 8;			// specialist
		$this->UC_PG  = 9;			// parent/guardian
		$this->UC_SC  = 10;			// service coordinator
		//
		// RUN INIT METHODS
		if($parent == true) {
			//echo "parent<BR>";
			$this->sessUserMinPriv = $this->UC_PG;
		} else {
			//echo "not parent<BR>";
			$this->getMinPriv();
		}
		//pre_print_r($sessUserPrivs);

	}
	function yessir()
	{
		return 'test';
	}
	// =============================================================================
	// =============================================================================
	function getMinPriv() {					
		$privCount = count($this->sessUserPrivs);
		$minClass = 1000;
		//
		// loop to get min priv
		for ($j = 0; $j < $privCount; $j++) {
			if($this->sessUserPrivs[$j]['class'] < $minClass){	// could change to positional analysis
				$minClass = $this->sessUserPrivs[$j]['class'];
			}
		}
		if($minClass == 1000) {
			throw new exception("no priv found.");
//			newPearErrorCheck("no priv found.");
		}
		$this->sessUserMinPriv = $minClass;
		return $minClass;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	SAME AS ABOVE BUT ON A PASSED ARRAY																									//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getMinPrivAny($privArr) {	
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	INITIAL VARS																													//		
		//																																	//
		$privCount = count($privArr);
		$minClass = 10000;
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// LOOP TO GET MIN PRIV																												//
		for ($j = 0; $j < $privCount; $j++) {
			if($privArr[$j]['class'] < $minClass){
				$minClass = $privArr[$j]['class'];
			}
		}
		//																																	//
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		return $minClass;
	}
	//																																		//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function arrayPosition($findMe, $orderedPrivArray='') {
		if( ($keyFound = array_search($findMe, $orderedPrivArray)) !== NULL ) {
			return $keyFound;
		} else {
			return false;
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	USED TO GET THE PROPER PRIV ARRAY																									//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getPrivCheckArray($privCheckArrayName) {
		switch($privCheckArrayName) {
			case 'standardPrivOrderArray';
				return $this->standardPrivOrderArray;
				break;
			case '':
			case 'standardPrivNameArray':
			default:
				return $this->standardPrivNameArray;
				break;
		}
	}
	// =============================================================================
	// =============================================================================
	function privCompare($class2Check, $compareFunc='==', $class2Beat = 0, $privCheckArrayName = '', $errorOnFail = false) {
//		Zend_debug::dump("$class2Check $compareFunc $class2Beat<BR>");die();
		$privCheckArray = $this->getPrivCheckArray($privCheckArrayName);
		//
		// IF BOTH NUMBERS ARE NUMERIC, JUST COMPARE THE NUMBERS WITH evalCompare AND compareFunc 
		if( is_numeric($class2Check) && is_numeric($class2Beat) ) {
			if( $this->evalCompare($class2Check, $class2Beat, $compareFunc) ) {
				return true;
			} else {
				if($errorOnFail) {
//					newPearErrorCheck("failed error check for betterThan.evalCompare");
					throw new exception("failed error check for betterThan.evalCompare");
				} else {
					return false;
				}
			}
		} elseif(is_string($class2Check) && is_string($class2Beat)) {
			//
			// NEW ARRAY CHECK
			$class2CheckKey = $this->arrayPosition($class2Check, $privCheckArray);
			$class2BeatKey = $this->arrayPosition($class2Beat, $privCheckArray);
			if( $class2CheckKey !== false && $class2BeatKey !== false ) {
				//
				// COMPARE THE LOCATION OF THE KEYS (if to the left, access is granted)
				if( $this->evalCompare($class2CheckKey, $class2BeatKey, $compareFunc) ) {
					return true;
				} else {
					if($errorOnFail) {
//					newPearErrorCheck("one of these privs does not exist in the allowance array");
					throw new exception("one of these privs does not exist in the allowance array");
					} else {
						return false;
					}
				}
			} else {
				if($errorOnFail) {
//					newPearErrorCheck("one of these privs does not exist in the allowance array");
					throw new exception("one of these privs does not exist in the allowance array");
				} else {
					return false;
				}
			}
		} else {
//			newPearErrorCheck("privCheck failed to identify the method of the check");
			throw new exception("privCheck failed to identify the method of the check");
		}

	}
	// =============================================================================
	// =============================================================================
	function evalCompare($value1, $value2, $compareFunc) {
		//
		// compare the two elements with $compareFunc
		$to_eval="\$evalled=" . $value1 . " " . $compareFunc . " " . $value2 . ";"; 
		//echo "to_eval: $to_eval<BR>";
		eval($to_eval);
		//
		// USE STANDARD INTEGER CHECK
		if( $evalled ) {
			return true;
		} else {
			return false;
		}
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	030910-JL																															//
	//																																		//
	//	SEE IF personnelID HAS DELETE PRIVS OVER ALL deleteID's PRIVILEGES																	//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	#function canIEditYourPriv($myMinPriv, $myPrivArray, $yourCounty, $yourDistrict, $yourSchool, $yourPrivClass) {
	function canDeletePriv($deleteID, $personnelID) {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	INITIAL VARS																													//		
		//																																	//
		//	PRIVS FOR PERSONNEL DOING THE DELETE																							//
		$personnelPrivArray = getUserPrivs($personnelID);
		$personnelPrivCount = count($personnelPrivArray);
		$personnelMinPriv = $this->getMinPrivAny($personnelPrivArray);
		//																																	//
		//	PRIVS FOR PERSONNEL BEING DELETED																								//
		$deletePrivArray = getUserPrivs($deleteID);			// function defined in function_general.inc									//
		$deletePrivCount = count($deletePrivArray);
		$deleteMinPriv = $this->getMinPrivAny($deletePrivArray);


// 		aecho("PRIVS FOR PERSONNEL DOING THE DELETE ".$personnelID);
// 		aecho("personnelMinPriv: ".$personnelMinPriv);
// 		pre_print_r($personnelPrivArray);
// 		
// 		aecho("PRIVS FOR PERSONNEL BEING DELETED: ".$deleteID);
// 		aecho("minpriv: ".$deleteMinPriv);
// 		pre_print_r($deletePrivArray);


		//																																	//
		//																																	//
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	SPECIAL CASES																													//
		//																																	//
		// CAN'T DELETE YOURSELF																											//
		if($deleteID == $personnelID) {
			return false;
		}
		//																																	//
		// IF PERSONNEL DOING THE DELETE'S BEST PRIV IS WORSE THAN SCHOOL MGR, WE'RE DONE -- MUST BE AT LEAST ASM TO EDIT PRIVS				//
		if($personnelMinPriv > UC_ASM) {
			return false;
		}
		//																																	//
		// IF PERSONNEL BEING DELETED IS A SYS ADMIN PRIV, WE'RE ALSO DONE, BECAUSE NO ONE MAY DELETE THESE									//
		if($deleteMinPriv == UC_SA ) {
			return false;
		}
		//																																	//
		//	CHECK FOR PERSONNEL DOING THE DELETE BEING A SYS ADMIN																			//
		//	IF SO WE'RE DONE -- SA MAY EDIT ANY OTHER PRIV (EXCEPT OTHER SA PRIVS AND WE ALREADY BAILED ON THOSE ABOVE)						//
		if($personnelMinPriv == UC_SA ) {
			return true;
		}
		//																																	//
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//																																	//
		//	LOOP THROUGH THE PRIVS OF THE PERSONNEL BEING DELETED AND MAKE SURE PERSONNEL DOING THE DELETE HAS ACCESS TO EVERY PRIV			//
		for ($i=0; $i < $deletePrivCount; $i++) {
			$deletePersonnelPrivLevel = $this->getBestAccessPriv($personnelPrivArray, $deletePrivArray[$i]['id_county'], $deletePrivArray[$i]['id_district'], $deletePrivArray[$i]['id_school']);
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//																																//
			//	PERSONNEL DOING THE DELETE HAS NO ACCESS TO THIS PRIV, FAIL AND BAIL														//
			if($deletePersonnelPrivLevel == 0) {
				return false;
			}
			//																																//
			//	PERSONNEL DOING THE DELETE HAS WORSE OR EQUAL ACCESS TO THE PERSONNEL BEING DELETED											//
			if($deletePersonnelPrivLevel >= $deletePrivArray[$i]['class']) {
				return false;
			}
			//																																//
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		//																																	//
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// ALL CHECKS FAILED, NO ACCESS
		return true;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//																																		//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function singlePrivCompare($masterPriv, $slavePriv) {
		if($masterPriv['id_county'] == $slavePriv['id_county'] ) {
			return true;
		}
		return false;
	}
	//																																		//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//																																		//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getBestAccessPriv($privArray, $id_county, $id_district, $id_school) {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	INITIAL VARS																													//		
		//																																	//
		$privCount = count($privArray);
		//																																	//
		//																																	//
		// LOOP TO CHECK FOR SUPER USER																										//
		for ($h = 0; $h < $privCount; $h++) {
			if( strlen(trim( $privArray[$h]['id_district'])) == 0 && strlen(trim( $privArray[$h]['id_school'])) == 0){
				$activeClass = $privArray[$h]['class'];
				return $activeClass;
			}
		}
		//aecho("super access");

		//																																	//
		//																																	//
		// LOOP TO CHECK FOR DISTRICT																										//
		for ($i = 0; $i < $privCount; $i++) {
			if($privArray[$i]['id_county'] == $id_county && $privArray[$i]['id_district'] == $id_district && strlen(trim( $privArray[$i]['id_school'])) == 0){
				$activeClass = $privArray[$i]['class'];
				return $activeClass;
			}
		}
		//aecho("DISTRICT access");
		//																																	//
		//																																	//
		// LOOP TO CHECK FOR SCHOOL																											//
		// EDITED 2003-03-05 SL/JL TO CHECK ALL SCHOOL-LEVEL PRIVS AND NOT BAIL ON FIRST MATCH												//
		$activeClass = 1000;
		for ($j = 0; $j < $privCount; $j++) {
			if( $privArray[$j]['id_county'] == $id_county && $privArray[$j]['id_district'] == $id_district && $privArray[$j]['id_school'] == $id_school){
				$activeClass = min( $activeClass, $privArray[$j]['class']);
			}
		}
		if ($activeClass != 1000 ) {
			return $activeClass;
		}
		//aecho("activeClass: $activeClass");
		//																																	//
		//																																	//
		// ALL CHECKS FAILED, NO ACCESS																										//
		return 0;
		//																																	//
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	//																																		//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	REMEMBER THAT ALL THESE CALLS ARE FOR MINPRIV																						//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function minPrivUC_SA() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_SA);
	}
	function minPrivUC_DM() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_DM);
	}
	function minPrivUC_ADM() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_ADM);
	}
	function minPrivUC_SM() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_SM);
	}
	function minPrivUC_ASM() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_ASM);
	}
	function minPrivUC_CM() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_CM);
	}
	function minPrivUC_SS() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_SS);
	}
	function minPrivUC_SP() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_SP);
	}
	function minPrivUC_PG() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_PG);
	}
	function minPrivUC_SC() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_SC);
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	REMEMBER THAT ALL THESE CALLS ARE FOR MINPRIV																						//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function minPrivUC_SAorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_SA);
	}
	function minPrivUC_DMorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_DM);
	}
	function minPrivUC_ADMorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_ADM);
	}
	function minPrivUC_SMorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_SM);
	}
	function minPrivUC_ASMorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_ASM);
	}
	function minPrivUC_CMorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_CM);
	}
	function minPrivUC_SSorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_SS);
	}
	function minPrivUC_SPorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_SP);
	}
	function minPrivUC_PGorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_PG);
	}
	function minPrivUC_SCorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_SC);
	}
	// =============================================================================
	// =============================================================================
	function minPrivUC_SAorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_SA);
	}
	function minPrivUC_DMorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_DM);
	}
	function minPrivUC_ADMorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_ADM);
	}
	function minPrivUC_SMorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_SM);
	}
	function minPrivUC_ASMorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_ASM);
	}
	function minPrivUC_CMorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_CM);
	}
	function minPrivUC_SSorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_SS);
	}
	function minPrivUC_SPorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_SP);
	}
	function minPrivUC_PGorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_PG);
	}
	function minPrivUC_SCorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_SC);
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	REMEMBER THAT ALL THESE CALLS ARE FOR MINPRIV																						//
	//		THESE CALLS SHOULD BE REPLACED WITH THE ONES ABOVE																				//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function isUC_SA() {
//		Zend_debug::dump($this);die();
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_SA);
	}
	function isUC_DM() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_DM);
	}
	function isUC_ADM() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_ADM);
	}
	function isUC_SM() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_SM);
	}
	function isUC_ASM() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_ASM);
	}
	function isUC_CM() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_CM);
	}
	function isUC_SS() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_SS);
	}
	function isUC_SP() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_SP);
	}
	function isUC_PG() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_PG);
	}
	function isUC_SC() {
		return $this->privCompare($this->sessUserMinPriv, "==", $this->UC_SC);
	}
	// =============================================================================
	// =============================================================================
	function isUC_SAorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_SA);
	}
	function isUC_DMorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_DM);
	}
	function isUC_ADMorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_ADM);
	}
	function isUC_SMorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_SM);
	}
	function isUC_ASMorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_ASM);
	}
	function isUC_CMorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_CM);
	}
	function isUC_SSorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_SS);
	}
	function isUC_SPorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_SP);
	}
	function isUC_PGorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_PG);
	}
	function isUC_SCorBetter() {
		return $this->privCompare($this->sessUserMinPriv, "<=", $this->UC_SC);
	}
	// =============================================================================
	// =============================================================================
	function isUC_SAorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_SA);
	}
	function isUC_DMorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_DM);
	}
	function isUC_ADMorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_ADM);
	}
	function isUC_SMorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_SM);
	}
	function isUC_ASMorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_ASM);
	}
	function isUC_CMorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_CM);
	}
	function isUC_SSorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_SS);
	}
	function isUC_SPorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_SP);
	}
	function isUC_PGorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_PG);
	}
	function isUC_SCorWorse() {
		return $this->privCompare($this->sessUserMinPriv, ">=", $this->UC_SC);
	}
	// end privCheck class
	function getStudent($id_student) {
		#print("get student: $id_student<BR>");
		$sqlStmt =	"SELECT *,
					CASE WHEN name_middle IS NOT NULL THEN name_first || ' ' || name_middle || ' ' || name_last ELSE name_first || ' ' || name_last END AS name_student_full,
					get_name_county(id_county) as name_county,
					get_name_district(id_county, id_district) as name_district,
					get_name_school(id_county, id_district, id_school) as name_school,
					get_name_personnel(id_case_mgr) as name_case_mgr,
					get_name_personnel(id_ei_case_mgr) as name_ei_case_mgr,
					get_name_personnel(id_ser_cord) as name_ser_cord\n";
		$sqlStmt .= 	"FROM iep_student\n";
		$sqlStmt .= 	"WHERE id_student = $id_student;\n";
		if($result = sqlExec($sqlStmt, $this->errorId, $this->errorMsg, true, true)) {
			$arrData = pg_fetch_array($result, 0);
			return $arrData;
		} else {
			return false;
		}
	}
	function defaultAccess($accessArray) {
		#
		# DECLARE VARS
		#
		$access = false;
		#
		# CHECK IF default EXISTS AND IS AN ARRAY
		$studentArea = "default";
		$studentSub = "default";
		
		if(array_key_exists($studentArea, $accessArray) && is_array($accessArray[$studentArea]) ) {
			#
			# GET THE SUB ARRAY 
			$studentSubArr = $accessArray[$studentArea];
			#
			# SEE IF SUB AREA EXISTS IN THE ACCES ARRAY 
			if( array_key_exists($studentSub, $studentSubArr) ) {
				if( $studentSubArr[$studentSub] == true ) {
					$access = true;
				}
			}
		}
		#
		return $access;
	}
	#
	#
	######################################################################################### 
	#																						#
	######################################################################################### 
	function grantAccess($accessArray, $accessVariablesArr) {
		
		global $ACCESS_VALIDATION;
		#
		#if($ACCESS_VALIDATION == false) return true;
		
		$sub = $accessVariablesArr['sub'];
		$area = $accessVariablesArr['area'];
		$mode = $accessVariablesArr['mode'];
		$option = $accessVariablesArr['option'];
		$formNumber = isset($accessVariablesArr['formNumber'])?$accessVariablesArr['formNumber']:"";
		$formStatus = isset($accessVariablesArr['formStatus'])?$accessVariablesArr['formStatus']:"";
		
//		echo "sub: $sub<BR>";
//		echo "area: $area<BR>";
//		echo "mode: $mode<BR>";
//		echo "option: $option<BR>";
//		echo "formNumber: $formNumber<BR>";
//		echo "formStatus: $formStatus<BR>";
		#
		# DEFAULT STATUS
		if($formStatus == '') {
			$formStatus = 'Draft';
		}
		#pre_print_r($accessVariablesArr);
		#
		# DECLARE VARS
		#
		$access = false;
		#
		# CHECK IF studentArea EXISTS AND IS AN ARRAY
		# 
		if( $sub == 'student') {
			
			if($option == $mode) {
				#
				# VIEW / EDIT ACCESS
				#
				if($grantedArr = $this->getArray($accessArray, array($option))) {
					return $this->trueKey($grantedArr, 'access');
				}
			} elseif($option == 'print') {
				#
				# FORM CENTER
				#
				if($grantedArr = $this->getArray($accessArray, array('print'))) {
					return $this->trueKey($grantedArr, 'access');
				}			
			} elseif($option == 'delete') {
				#
				# FORM CENTER
				#
				if($grantedArr = $this->getArray($accessArray, array('delete'))) {
					return $this->trueKey($grantedArr, 'access');
				}			
			} elseif($option == 'parents') {
				#
				# PARENTS LIST
				#
				if($grantedArr = $this->getArray($accessArray, array('parents'))) {
					return $this->trueKey($grantedArr, 'access');
				}			
			} elseif($option == 'team') {
				#
				# TEAM CENTER
				#
				if($grantedArr = $this->getArray($accessArray, array('team'))) {
					return $this->trueKey($grantedArr, 'access');
				}			
			} elseif($option == 'forms') {
				#
				# FORM CENTER
				#
				if($grantedArr = $this->getArray($accessArray, array('forms'))) {
					return $this->trueKey($grantedArr, 'access');
				}			
			} elseif($option == 'log') {
				#
				# FORM CENTER
				#
				if($grantedArr = $this->getArray($accessArray, array('log'))) {
					return $this->trueKey($grantedArr, 'access');
				}			
			}
			
		} elseif( $sub == 'guardian') {
			#
			# VIEW / EDIT DELETE / LOG GUARDIAN
			#
			if($grantedArr = $this->getArray($accessArray, array('parents'))) {
				return $this->trueKey($grantedArr, $option);
			}			
		} elseif( $sub == 'form_003_v2_p3teaminput') {
			#
			# FORM 003 TEAM MEMBER ABSENCES     
			#
			if($grantedArr = $this->getArray($accessArray, array('003', $formStatus))) {
				return $this->trueKey($grantedArr, 'temmMemberAbsence');
			}			
		} elseif( $sub == 'form_004dupe') {
			#
			# DUPE FORM 004
			#
			if($grantedArr = $this->getArray($accessArray, array($formNumber, $formStatus))) {
				return $this->trueKey($grantedArr, 'dupe');
			}			
		} elseif( $sub == 'form_004am') {
			#
			# FORM 004 Accomodations Checklist
			#
			if($grantedArr = $this->getArray($accessArray, array('004', $formStatus))) {
				return $this->trueKey($grantedArr, 'acc_check');
			}			
		} elseif( $sub == 'confirm_transfer') {
			//			if($grantedArr = $this->getArray($accessArray, array($sub))) {
			//				return $this->trueKey($grantedArr, 'access');
			//			}
			#
			# IN ORDER TO GET TO THE CONFIRM TRANSFER PAGE, YOU HAVE ONLY TO BE ADM
			# OF THE SCHOOL BEING TRANSFERRED TO. VALIDATESTUDENTACCESS WILL NOT NECESSARILY SHOW
			#  ACCESS OVER THE STUDENT. 
		} elseif(!empty($formNumber) && !empty($formStatus)) {
			#
			# FORMS VIEW / EDIT / PRINT
			#
			if($grantedArr = $this->getArray($accessArray, array($formNumber, $formStatus))) {
				return $this->trueKey($grantedArr, $option);
			}			
		} elseif($option == 'new' && substr($sub, 0, 4) == 'form') {
			#
			# NEW FORMS
			#
/* 			if( $sub == 'form_004am') { */
/* 			    $sub = "form_004"; */
/* 			} */
			if($grantedArr = $this->getArray($accessArray, array(substr($sub, -3, 3)))) {
				return $this->trueKey($grantedArr, $option);
			}			
		}
		#
		return $access;
	}
	#
	#
	######################################################################################### 
	#																						#
	######################################################################################### 
	function validateStudentAccess($id_student, $id_personnel, $accessVariablesArr, $studentArr = false) {
//		Zend_debug::dump('sessPrivCheckObj->validateStudentAccess');
	
		global $ERROR_ACCESS_DENIED, $sessIdUser, $sessPrivCheckObj, $FILE_ROOT;

		$sessUser = new Zend_Session_Namespace('user');
		$sessIdUser = $sessUser->sessIdUser;
		$sessPrivCheckObj = user_session_manager::getSessPrivCheckObj();
		
//		Zend_debug::dump($id_student);
//		Zend_debug::dump($id_personnel);
//		Zend_debug::dump($accessVariablesArr);
//		Zend_debug::dump($studentArr);
		$accessArrName = '';
		$accessDefsFolder = APPLICATION_PATH . '/configs/access_definitions/';

		#writeToLog( $FILE_ROOT . '/validateAccessLog.txt', "###### ===========================================================");
		#writeToLog( $FILE_ROOT . '/validateAccessLog.txt', "###### ===========================================================");

		#log_print_r($accessVariablesArr);
		########
		#
		# SIMPLE ERROR RETURNS
		if(empty($id_student)) {
			return false;
		}
		######################################################################################### 
		######################################################################################### 
		#
		# INCLUDE THE BUILDACCESS CLASS (ALL ACCESS IS GRANTED THERE)
		#
		//require_once('iep_class_buildAccess.php'); // moved to top
		#
		# IF THE studentSub IS EMPTY, SET IT TO THE studentArea
		#
//		if($accessVariablesArr['studentSub'] == '') {
//			$accessVariablesArr['studentSub'] = $accessVariablesArr['studentArea'];
//		}
		#
		######################################################################################### 
		#
		# DECLARE VARIABLES
		#
		$valid = false;
		$accessArray = array();
		#
		######################################################################################### 
		######################################################################################### 
		#
		# STUDENT ARRAY MAY BE PASSED INSTEAD OF HAVING TO BE RE-QUERIED FOR HERE
		#
		if($studentArr == false) {
//			Zend_debug::dump('GETTING STUDENT');
			#echo "GETTING STUDENT<BR>";
			#
			# GET STUDENT DATA
			#
			if($studentArr = $this->getStudent($id_student)) {
				//pre_print_r($studentArr);
				#debugLog("GET STUDENT DATA SUCCESSFUL");
				//Zend_debug::dump('GET STUDENT DATA SUCCESSFUL');
				#
				# SET PATH TO ACCESS DEFINITIONS
				#
	//			if($studentArr['id_county'] == '55' && $studentArr['id_district'] == '0001') {
	//				#
	//				# LPS HAS DIFFERENT ACCESS RIGHTS
	//				#
	//				$accessDefsFolder = 'access_definitions_LPS/';
	//				#echo "LPS<BR>";
	//			} else {
					#$accessDefsFolder = 'access_definitions/';
	//			}
			} else {
				//Zend_debug::dump('NO STUDENT');
				#
				# ERROR: NO STUDENT
				#
				echo "no student<BR>";
				return false;
			}
		}
		######################################################################################### 
		# STUDENT ACCESS MAY BE GRANTED TO USERS THAT DON'T HAVE PRIVS OVER THEM
		#  - ASM OR BETTER CAN GAIN ACCESS TO CONFIRM STUDENT TRANSFER TO THEIR SCHOOL
		######################################################################################### 
		if( $accessVariablesArr['sub'] == 'confirm_transfer' && $this->minPrivUC_ASMorBetter()) {
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt',  "GRANTING ACCESS FOR STUDENT TRANSFER, USER $sessIdUser IS ASM OR BETTER");
			return true;
		}
		######################################################################################### 
		######################################################################################### 
		#
		# CHECK TO SEE IF THE USER HAS ACCESS OVER THIS STUDENT BASED ON THEIR PRIVILEGES
		# 	getPrivClass RETURNS 0 WHEN THERE IS NO ACCESS
		#
		$privClass = My_Classes_iepFunctionGeneral::getPrivClass($studentArr['id_county'], $studentArr['id_district'], $studentArr['id_school']);
//		Zend_debug::dump($privClass);
//		Zend_debug::dump(UC_SA);
		
		//if($id_personnel == '1007964') pre_print_r($privClass);
		//		#
		//		#
		//		#debugLog('');
		//		#debugLog( "id_student: ".$studentArr['id_student']);
		//		#debugLog( "sessIdUser: $sessIdUser");
		//		#debugLog( "priv class: $privClass");
		//		#debugLog( "id_ser_cord: ".$studentArr['id_ser_cord']);
		//		#debugLog( "idCaseMgr: ".$studentArr['idCaseMgr']);
		//		#debugLog( "id_ei_case_mgr: ".$studentArr['id_ei_case_mgr']);
		//		#debugLog( "idListTeam: ".$studentArr['id_list_team']);
		//		#debugLog( "idListGuardian: ".$studentArr['id_list_guardian']);
		//		#debugLog('');
		######################################################################################### 
		######################################################################################### 		
		#
		# STRAIGHT PRIV ACCESS (USER IS IN A POSITION THAT GRANTS BROAD ACCESS)
		# 
		if( !$valid && $privClass !=0 && $privClass <= UC_ASM) {
			$valid = true;
			#debugLog("ACCESS - BASED ON PRIV");
			switch($privClass) {
				case UC_SA:
					$this->accessArrDesc = 'System Admin';
					$accessArrName = 'SA';
					break;
				case UC_ADM:
					$this->accessArrDesc = 'Associate District Manager';
					$accessArrName = 'Full';
					break;
				case UC_DM:
					$this->accessArrDesc = 'District Manager';
					$accessArrName = 'Full';
					break;
				case UC_ASM:
					$this->accessArrDesc = 'Associate School Manager';
					$accessArrName = 'ASM';
					break;
				case UC_SM:
					$this->accessArrDesc = 'School Manager';
					$accessArrName = 'ASM';
					break;
				default:
					#$accessArrName = 'view';
			}
		}
//		Zend_debug::dump($this->accessArrDesc, 'accessArrDesc');
		
		######################################################################################### 		
		#
		# CM ACCESS (SPECIFIC ACCESS)
		# 
		if( !$valid && $sessIdUser == $studentArr['id_case_mgr']){ // 
			$valid = true;
			#debugLog("ACCESS - THIS IS THE CASE MANAGER");
			#
			$this->accessArrDesc = 'Case Manager';
			$accessArrName = 'CM';
		}
		######################################################################################### 		
		#
		# EICM ACCESS (SPECIFIC ACCESS)
		# 
		if( !$valid && $sessIdUser == $studentArr['id_ei_case_mgr']){ // 
			$valid = true;
			#debugLog("ACCESS - THIS IS THE EI CASE MANAGER");
			#
			$this->accessArrDesc = 'EI Case Manager';
			$accessArrName = 'EICM';
		}
		######################################################################################### 		
		#
		# SC ACCESS (SPECIFIC ACCESS)
		# 
		if( !$valid && $sessIdUser == $studentArr['id_ser_cord']){ // 
			$valid = true;
			#debugLog("ACCESS - THIS IS THE SC");
			#
			$this->accessArrDesc = 'Service Coordinator';
			$accessArrName = 'SC';
		}
		######################################################################################### 		
		#
		# TEAM ACCESS (SPECIFIC ACCESS)
		# 
		require_once(APPLICATION_PATH . '/models/DbTable/iep_student_team.php');
		
		if(!$valid && iep_student_team::isOnTeam( $id_personnel, $id_student ) ){ // 
			$valid = true;
			#debugLog("ACCESS - IS ON STUDENT TEAM");
			#
			$teamRec = iep_student_team::getTeamRecord($id_student, $id_personnel);
			
			if($teamRec['flag_ei_only'] && $teamRec['flag_edit']) {
				$this->accessArrDesc = 'Early Intervention Team Member Edit';
				$accessArrName = 'TeamEIEdit';
			
			} elseif($teamRec['flag_ei_only']) {
				$this->accessArrDesc = 'Early Intervention Team Member View';
				$accessArrName = 'TeamEIView';
			
			} elseif($teamRec['flag_edit']) {
				$this->accessArrDesc = 'Team Member Edit';
				$accessArrName = 'TeamEdit';
			
			} elseif($teamRec['flag_view']) {
				$this->accessArrDesc = 'Team Member View';
				$accessArrName = 'TeamView';
			} else {
				$this->accessArrDesc = 'Team Member w/o Access';
				#$accessArrName = 'none';
			}
		}
		######################################################################################### 		
		#
		# GUARDIAN ACCESS (SPECIFIC ACCESS)
		# 
		if(!$valid && in_array($sessIdUser, explode(";", $studentArr['id_list_guardian']))){ // 
			$valid = true;
			#debugLog("ACCESS - IS GUARDIAN");
			#
			$this->accessArrDesc = 'Parent/Guardian';
			$accessArrName = 'PG';
		}
		######################################################################################### 		
		# BUILD THE ACCESS ARRAY
		######################################################################################### 		
		#
		#
		if( $accessArrName == '' ) {
			#debugLog("NO ACCESS ARRAY");
		} else {
            /*
            *  $accessArray is returned with the result of buildAccess
            */
//			Zend_debug::dump('asfd');
			
			$accessObj = new My_Classes_buildAccess($accessArray, $accessArrName, $accessDefsFolder);
		}
//		Zend_debug::dump($accessObj);
		//
		// APPLY CHANGE DATA TO THE ACCESS ARRAY IF THIS USER IS IN LPS
		//
		if($studentArr['id_county'] == '55' && $studentArr['id_district'] == '0001') {
			#
			# LPS HAS DIFFERENT ACCESS RIGHTS
			#
			if(file_exists('access_definitions/override_LPS.php')) include_once('access_definitions/override_LPS.php'); // include changeArray
			if(isset($changeArray[$accessArrName])) {
				$this->changePriv($accessArray, $changeArray[$accessArrName]);
			}
		}
        #if('1003832' == $sessIdUser) echo "accessArrName: $accessArrName<BR>";
//		Zend_debug::dump($accessArray);
//		Zend_debug::dump($accessVariablesArr);


		#
		# CHECK THE ACCESS ARRAY TO SEE IF USER HAS ACCESS TO THIS FEATURE
		#
		$result = $this->grantAccess($accessArray, $accessVariablesArr);
//		Zend_debug::dump($accessDefsFolder);

		#writeToLog( $FILE_ROOT . '/validateAccessLog.txt', "accessArrName: ".$accessArrName );
		#writeToLog( $FILE_ROOT . '/validateAccessLog.txt', "id: ".$studentArr['id_student'] );
		#writeToLog( $FILE_ROOT . '/validateAccessLog.txt', "name: ".$studentArr['name_first'] . ' ' .  $studentArr['name_last']);
		#log_print_r($accessVariablesArr);
		#pre_print_r($accessVariablesArr);
		#echo "count: " . count($accessArray) . "<BR>";
		if($result) {
			#
			# SAVE ACCESS OBJECT AND VARS
			#
			$this->accessObj = $accessObj;
			$this->accessArray = $accessArray;
			$this->studentArea = isset($accessVariablesArr['studentArea'])?$accessVariablesArr['studentArea']:"";;
			$this->accessArrName = $accessArrName;

			#pre_print_r($menuArr);
			#
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt', " $accessArrName ACCESS GRANTED" );
			
		} else {
			#echo "$id_student:{$studentArr['name_last']} NO ACCESS<BR><BR>";
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt',  "id_student: ".$studentArr['id_student']);
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt',  "sessIdUser: $sessIdUser");
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt',  "priv class: $privClass");
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt',  "id_ser_cord: ".$studentArr['id_ser_cord']);
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt',  "idCaseMgr: ".$studentArr['idCaseMgr']);
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt',  "id_ei_case_mgr: ".$studentArr['id_ei_case_mgr']);
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt',  "idListTeam: ".$studentArr['id_list_team']);
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt',  "idListGuardian: ".$studentArr['id_list_guardian']);
			
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt', "ACCESS DENIED" );
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt', "ACCESS DENIED" );
			#writeToLog( $FILE_ROOT . '/validateAccessLog.txt', "\t$id_student\tarea:$studentArea\tsub:$studentSub\taccessArrName:$accessArrName\tACCESS DENIED" );
		}
		#writeToLog( $FILE_ROOT . '/validateAccessLog.txt', "===========================================================\n\n");
		#
		######################################################################################### 		
		#
		#
		return $result;
		#
		#
		#
	}
	function changePriv(&$accessArray, $changeArray) {

		foreach($changeArray as $class => $changeLine) {
			
			list($pathArr, $newVal) = $changeLine;
			
			$setAccessArrayCode = '$accessArray[\''.implode("']['", $pathArr) . "'] = '$newVal';";
			#echo "setAccessArrayCode: $setAccessArrayCode<BR>";
			eval($setAccessArrayCode);
		}
		#pre_print_r($accessArray);
	}
	public function serialize()
	{
		if(isset($this->accessObj)) $this->accessObj = $this->accessObj->serialize();
		return serialize($this);
	}
	
	public function unserialize($serialized)
	{
//		Zend_debug::dump('unserialize');die();
		return unserialize($serialized);
	}

    function getArray($masterArray, $keyPathArr) {
        $searchArr = $masterArray;
        foreach($keyPathArr as $key) {
            if(array_key_exists($key, $searchArr) && is_array($searchArr[$key]) ) {
                $searchArr = $searchArr[$key];
            } else {
                return false;
            }
        }
        return $searchArr;
    }
    function trueKey($matchArr, $key) {
        if( array_key_exists($key, $matchArr) ) {
            if( $matchArr[$key] == true ) {
                return true;
            }
        } else {
            log_print_r($key, 'key');
            log_print_r($matchArr, 'matchArr');
        }
        return false;
    }
	
}
?>