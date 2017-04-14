<?
class accessConstructor {
	
	var $formCount;
	
	function buildStudentAccess($definitionPath='', $accessName='') {
		#
		# THE FOLLOWING INCLUDE WILL BUILD THE ACCESS ARRAY
		#
		include($definitionPath . $accessName);
		#echo "getting file: " .$definitionPath . $accessName. "<BR>";
		
		$accessArray = $this->accessArray;
				
		$this->buildFormAccess($accessArray);		
		return $accessArray;
	}
	
	function buildFormAccess(&$accessArray) {
		#
		# FORM PARAMATERS
		#
		global $FORM_COUNT;
		
		for($i=1; $i<=$FORM_COUNT; $i++) {
			#
			# FORM NUMBER
			$formNum = substr('000'.$i, -3,3);
			$accessVarName = 'form_' . $formNum;
			
			#
			# ADD FORMS TO THE ACCESSARRAY
			#
			$accessArray[$formNum] = $this->$accessVarName;
		}
	}

}

class My_Classes_buildAccess {
	
	var $formCount;
	var $definitionPath;
	
	function __construct(&$returnedAccessArray, $accessMode = 'none', $pathOverride = 'access_definitions/') {
		
		global $FORM_COUNT;
		
		#
		# SET CLASS VARS
		#
		$this->formCount = $FORM_COUNT;
		
		#
		# SET PATH TO THE ACCESS DEFINITIONS
		$this->definitionPath = $pathOverride;

		#
		# BUILD THE NAME OF THE INCLUDE WITH THE ACCESS ARRAY
		#
//		Zend_debug::dump($accessMode, 'accessMode');
		$accessFilename = "class_access$accessMode.php";

		#
		# GET THE ACCESS ARRAY
		#
		$accessObj = new accessConstructor();
		$returnedAccessArray = $accessObj->buildStudentAccess($this->definitionPath, $accessFilename);
		
		#pre_print_r($returnedAccessArray);
	}
	#
	# BUILD LIST OF AVAILABLE FORMS
	#
	function availableForms($accessArr, $key = 'view') {
		#
		#
		for($i=1; $i<=$this->formCount; $i++) {
			$formName = substr('000'.$i, -3,3);
			$studentAreaArr = $accessArr[$formName];

			#echo "formName: $formName<BR>";
			#pre_print_r($studentAreaArr);
	
			if($studentAreaArr[$key] === true) {
				#
				# LIMITER, ONLY RETURN TRUE VALUES THAT ARE IN THE LIMITER ARR
				#
				$returnArr[$formName] = $this->accessDescriptions($formName, $key);
			}
		}
		return $returnArr;
	}
	


	#
	# BUILD LIST OF AVAILABLE FORM OPTIONS
	#
	function availableFormOptions($accessArr, $studentArea, $menuLimiterArr = false, $formStatus = 'Draft') {
		#
		#
		$studentAreaArr = $accessArr[$studentArea][$formStatus];
		if(!is_array($studentAreaArr)) {
			return false;
		}
		#pre_print_r($accessArr[$studentArea]);
		if($menuLimiterArr === false) {
			#
			# RETURN ALL VALUES IN SUB OF THE ACCESS ARRAY THAT ARE TRUE
			#
			return $this->valueMatchKeys($studentAreaArr, $studentArea);
		} else {
			#
			# LIMITER ON, ONLY RETURN TRUE VALUES THAT ARE IN THE LIMITER ARR
			#
			return $this->valueMatchKeys($studentAreaArr, $studentArea, $menuLimiterArr);
		}
	}
	#
	# BUILD LIST OF AVAILABLE TOP MENUS
	#
	function availableTopMenus($accessArr, $menuLimiterArr = false) {
		#
		#
		$returnArr = array();
		#
		#
		foreach($accessArr as $areaName => $areaArr) {

			#echo "areaName:$areaName<BR>";
			#pre_print_r($areaArr);
			if(isset($areaArr['access']) && $areaArr['access'] == true) {
				#
				# LIMITER, ONLY RETURN TRUE VALUES THAT ARE IN THE LIMITER ARR
				#
				if($menuLimiterArr === false) {
					# IF KEY HAS VALUE OF TRUE IN THE DATA ARR, SET THE KEY AND RETURN
					$returnArr[$areaName] = $this->accessDescriptions($areaName, 'access');
				} else {
					if(array_key_exists($areaName, $menuLimiterArr) || array_key_exists($areaName, array_flip($menuLimiterArr))) {
						# IF KEY HAS VALUE OF TRUE IN THE DATA ARR, SET THE KEY AND RETURN
						$returnArr[$areaName] = $this->accessDescriptions($areaName, 'access');
					}
				}
			}
		}
		return $returnArr;
	}
	#
	# BUILD LIST OF AVAILABLE SUB MENUS
	#
	function availableMenus($accessArr, $studentArea, $menuLimiterArr = false) {
		#
		#
		$studentAreaArr = $accessArr[$studentArea];
		
		if($menuLimiterArr === false) {
			#
			# RETURN ALL VALUES IN SUB OF THE ACCESS ARRAY THAT ARE TRUE
			#
			return $this->valueMatchKeys($studentAreaArr, $studentArea);
		} else {
			#
			# LIMITER ON, ONLY RETURN TRUE VALUES THAT ARE IN THE LIMITER ARR
			#
			return $this->valueMatchKeys($studentAreaArr, $studentArea, $menuLimiterArr);
		}
	}
	
	function valueMatchKeys($dataArr, $studentArea, $menuLimiterArr = false, $matchValue = true) {
		#for($i=0, $j=count($dataArr); $i<$j; $i++) {
		$returnArr = array();

		foreach($dataArr as $key => $value) {
			if($value == $matchValue) {
				#
				# LIMITER, ONLY RETURN TRUE VALUES THAT ARE IN THE LIMITER ARR
				#
				if($menuLimiterArr === false) {
					# IF KEY HAS VALUE OF TRUE IN THE DATA ARR, SET THE KEY AND RETURN
					$returnArr[$key] = $this->accessDescriptions($studentArea, $key);
				} else {
					if(array_key_exists($key, $menuLimiterArr) || array_key_exists($key, array_flip($menuLimiterArr))) {
						# IF KEY HAS VALUE OF TRUE IN THE DATA ARR, SET THE KEY AND RETURN
						$returnArr[$key] = $this->accessDescriptions($studentArea, $key);
					}
				}
			}
		}
		return $returnArr;
	}
	# #########################################################################################
	# #########################################################################################
	# #########################################################################################
	# #########################################################################################
	# #########################################################################################
	# #
	# #
	#	THIS CLASS BUILDS AN ACCESS ARRAY TO A STUDENT/THEIR FORMS/GUARDIANS/ECT
	# #
	# #
	# #########################################################################################
	# #########################################################################################
	# #########################################################################################
	#
	#
	# #########################################################################################
	#	ACCESS DESCRIPTIONS
	# #########################################################################################
	function accessDescriptions($studentArea, $sub) {
	
		global $FORM_NAMES;
		#
		# FULL ACCESS - ALL TRUE
		#
		$accessArray = array(	
			'default'		=> array( 'default' => false),
			#
			# STUDENT AREAS
			#
			'view' 			=> array( 	'access'=>'View Student',
										'view' 	=> true,
									),
			'new' 			=> array( 	'access'=>'',
										'new' 	=> true,
									),
			'edit'		 	=> array( 	'access'=>'Edit Student',
										'edit' 	=> true,
									),
			'delete'	 	=> array( 	'access'=>'Delete Student',
										'delete' 	=> true,
									),
			'parents'	 	=> array( 	'access'=>'Parent/Guardians',
										'view' 	=> 'View Parent',
										'edit' => 'Edit Parent',
										'new'	=> 'New Parent',
										'delete'	=> 'Delete Parent',
										'log'	=> 'Parent Log',
									),
			'team' 			=> array( 	'access'=>'Student Team',
										'view' 	=> 'View Personnel',
										'edit' => true,
										'new'	=> true,
										'delete'	=> 'Remove Personnel',
									),
			'forms'			=> array( 	'access'=>'Student Forms',
										'view' 	=> true,
									),
			'log'			=> array( 	'access'=>'Student Log',
										'view' 	=> true,
									),
			#
			# STUDENT FORMS
			#
			'001'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'002'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print', 	'dupe'	=> 'Dupe'),
			'003'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'004'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print', 	'dupe'	=> 'Dupe', 	'dupe_form_004'	=> 'Dupe', 	'createpr'	=> 'Create PR'),
			'005'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'006'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'007'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'008'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'009'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'010'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'011'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'012'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'013'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print', 	'dupe_form_013'	=> 'Dupe', 'dupe_form_013_update' => 'Update', 'unfinalize'	=> 'Un-Finalize'),
			'014'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'015'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'016'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'017'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'018'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'019'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'020'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'021'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'022'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'023'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
			'024'	 		=> array( 'view' 	=> 'View', 	'edit' => 'Edit', 	'new'	=> 'New Form', 	'delete'	=> 'Delete', 	'finalize'	=> 'Finalize', 	'log'	=> 'Log', 	'print'	=> 'Print'),
		);
		#pre_print_r($accessArray);
		return $accessArray[$studentArea][$sub];
	}

	public function serialize()
	{
		return serialize($this);
	}
	
	public function unserialize($serialized)
	{
//		Zend_debug::dump('unserialize');die();
		return unserialize($serialized);
	}
	
	
	
			//	# #########################################################################################
			//	# #########################################################################################
			//	# #
			//	# #									ACCESS ARRAYS
			//	# #
			//	# #########################################################################################
			//	#	NO ACCESS
			//	# #########################################################################################
			//	function noneAccess() {
			//		#
			//		# DEFAULT ACCESS - NONE
			//		#
			//		$accessArray = array(	
			//		);
			//		return $accessArray;
			//	}
			//	#
			//	#
			//	# #########################################################################################
			//	#	VIEW ONLY
			//	# #########################################################################################
			//	function viewAccess() {
			//		#
			//		# VIEW ACCESS
			//		#
			//		$accessArray = array(	
			//			'default'		=> array( 'default' => false),
			//			'view' 			=> array( 'view' => true),
			//			'parents'	 	=> array( 'view' => true),
			//			'team' 			=> array( 'view' => true),
			//			'forms'			=> array( 'view' => true),
			//			'log'			=> array( 'view' => true),
			//			'001'	 		=> array( 'view' => true),
			//			'002'	 		=> array( 'view' => true),
			//			'003'	 		=> array( 'view' => true),
			//			'004'	 		=> array( 'view' => true),
			//			'005'	 		=> array( 'view' => true),
			//			'006'	 		=> array( 'view' => true),
			//			'007'	 		=> array( 'view' => true),
			//			'008'	 		=> array( 'view' => true),
			//			'009'	 		=> array( 'view' => true),
			//			'010'	 		=> array( 'view' => true),
			//			'011'	 		=> array( 'view' => true),
			//			'012'	 		=> array( 'view' => true),
			//			'013'	 		=> array( 'view' => true),
			//			'014'	 		=> array( 'view' => true),
			//			'015'	 		=> array( 'view' => true),
			//			'016'	 		=> array( 'view' => true),
			//			'017'	 		=> array( 'view' => true),
			//		);
			//		return $accessArray;
			//	}
			//	#
			//	#
			//	# #########################################################################################
			//	#	FULL ACCESS
			//	# #########################################################################################
			//	function fullAccess() {
			//		$accessObj = &new accessFull();
			//		$accessArray = $accessObj->buildStudentAccess();
			//		return 	$accessArray;
			//	}
			//
			//	#
			//	#
			//	# #########################################################################################
			//	#	ASM ACCESS
			//	# #########################################################################################
			//	function asmAccess() {
			//		$accessObj = &new accessAsm();
			//		$accessArray = $accessObj->buildStudentAccess();
			//		return 	$accessArray;
			//	}
			//
			//	#
			//	#
			//	# #########################################################################################
			//	#	CASE MANAGER ACCESS
			//	# #########################################################################################
			//	function CMAccess() {
			//		$accessObj = &new accessCM();
			//		$accessArray = $accessObj->buildStudentAccess();
			//		return 	$accessArray;
			//	}
			//	#
			//	#
			//	# #########################################################################################
			//	#	SC ACCESS
			//	# #########################################################################################
			//	function SCAccess() {
			//		$accessObj = &new accessSC();
			//		$accessArray = $accessObj->buildStudentAccess();
			//		return 	$accessArray;
			//	}
			//	#
			//	#
			//	# #########################################################################################
			//	#	EI CASE MANAGER ACCESS
			//	# #########################################################################################
			//	function EICMAccess() {
			//		$accessObj = &new accessEICM();
			//		$accessArray = $accessObj->buildStudentAccess();
			//		return 	$accessArray;
			//	}
			//	#
			//	#
			//	# #########################################################################################
			//	#	TEAM VIEW ACCESS
			//	# #########################################################################################
			//	function TeamViewAccess() {
			//		$accessObj = &new accessTeamView();
			//		$accessArray = $accessObj->buildStudentAccess();
			//		return 	$accessArray;
			//	}
			//	#
			//	# #########################################################################################
			//	#	TEAM EDIT ACCESS
			//	# #########################################################################################
			//	function TeamEditAccess() {
			//		$accessObj = &new accessTeamEdit();
			//		$accessArray = $accessObj->buildStudentAccess();
			//		return 	$accessArray;
			//	}
			//	#
			//	# #########################################################################################
			//	#	TEAM EI VIEW ACCESS
			//	# #########################################################################################
			//	function TeamEIViewAccess() {
			//		$accessObj = &new accessTeamEIView();
			//		$accessArray = $accessObj->buildStudentAccess();
			//		return 	$accessArray;
			//	}
			//	#
			//	# #########################################################################################
			//	#	TEAM EI EDIT ACCESS
			//	# #########################################################################################
			//	function TeamEIEditAccess() {
			//		$accessObj = &new accessTeamEIEdit();
			//		$accessArray = $accessObj->buildStudentAccess();
			//		return 	$accessArray;
			//	}
			//	#
			//	#
			//	# #########################################################################################
			//	#	TEAM & SC ACCESS
			//	# #########################################################################################
			//	function TEAMSCAccess() {
			////		$accessObj = &new accessSCTeam();
			//		$accessArray = $accessObj->buildStudentAccess();
			//		return 	$accessArray;
			//	}
			//	#
			//	#
			//	# #########################################################################################
			//	#	GUARDIAN ACCESS
			//	# #########################################################################################
			//	function PGAccess() {
			//		$accessObj = &new accessParent();
			//		$accessArray = $accessObj->buildStudentAccess();
			//		return 	$accessArray;
			//	}
			//
}
			#
			#$accessObj = &new buildAccess($accessArray, 'full');
			##
			#########################################################################################
			#
			# VALIDATE USER HAS ACCESS TO A FORM
			#
			//if($option == 'new') {
			//	if(!$sessPrivCheckObj->validateStudentAccess($student, $sessIdUser, $mode, $formNo, $option)) { 
			//		$errorId = '500';
			//		$errorMsg = '<BR><BR><B>New Validation System has denyed you access</B>';
			//		include_once('error.php');
			//		exit;
			//	}
			//} else {
			//	if(!$sessPrivCheckObj->validateStudentAccess(getIDStudentFromForm($tableName, $pkeyName, $document), $sessIdUser, $mode, $formNo, $option)) { 
			//		$errorId = '500';
			//		$errorMsg = '<BR><BR><B>New Validation System has denyed you access</B>';
			//		include_once('error.php');
			//		exit;
			//	}
			//}
			#
			#########################################################################################
			##
			##
			#########################################################################################
			#
			# VALIDATE USER HAS ACCESS TO STUDENT/AREA/SUB
			#
			//if($student != '') {
			//	if(!$sessPrivCheckObj->validateStudentAccess($pkey, $sessIdUser, $mode, $option, $mode)) { 
			//		$errorId = '500';
			//		$errorMsg = '<BR><BR><B>Validation System has denyed you access</B>';
			//		include_once('error.php');
			//		exit;
			//	}
			//}
			#
			#########################################################################################
			##
?>