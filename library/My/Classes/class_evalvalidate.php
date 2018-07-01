<?php

// #############################
//$evalObj = new evalidate();
//	$FAIL = 'if(1==0) { echo \'PASS\'; } else { echo \'FAIL\'; }';
//	$FATAL = 'if(1==0) { echo \'PASS\'; } else { echo \'FATAL\'; }';
//	$PASS = 'if(1==1) { echo \'PASS\'; } else { echo \'FAIL\'; }';	
//
//
//if(1) {
//	$fieldName = "field_x";
//	$complexCheck = 'if(
//					($arrData[\'field_y\']==\'monkey\' ) && 
//					($arrData[\'field_a\']==\'\' || $arrData[\'field_b\']==\'\')				// comments are ok inside validation code
//				) { echo \'FAIL\'; } else { echo \'PASS\'; }';
//	$arrFieldList = array(
//		"field_x"	=> 	array("evalPhp", $complexCheck, "Blah blah 1."),
//		"field_x2"	=> 	array("evalPhp", $FAIL, "Blah blah 2."),
//		"field_x3"	=> 	array("evalPhp", $PASS, "Blah blah 3."),
//		"field_x4"	=> 	array("evalPhp", $FATAL, "Blah blah 4."),
//	);
//	$arrData = array(
//					'field_y' => 'monkey',
//					'field_a' => 'x',
//					'field_b' => 'x',
//					'field_x' => 'test',
//				);
//	echo $evalObj->validate($arrData, $arrFieldList, $arrValidationResults = array());
//}
//if(1) {
//	$msg = "";
//	$isFailFatal = false;
//	
//	#echo "FAIL = ".$evalObj->evalPhp($fieldName, $arrData['field_x'], $FAIL, $isFailFatal, $msg, $arrData, $arrIssues, $arrIssuesFatal) ."<BR>";
//	#echo "PASS = ".$evalObj->evalPhp($fieldName, $arrData['field_x'], $PASS, $isFailFatal, $msg, $arrData, $arrIssues, $arrIssuesFatal) ."<BR>";
//}
//echo "<pre>";
//print_r($evalObj);
//echo "</pre>";
//

class evalidate {
	#
	# eValidate by Jesse LaVere
	# version 1.1
	#
	# 
	#
	var $arrData;
	var $arrValidationRules;
	var $arrValidationResults;
	
	#
	#
	# arrValidationRules = ('validation function name', 'validation definition', 'fail message' )
	#
	#
	function validate(&$arrData, &$arrValidationRules, &$arrValidationResults) {
		
		$this->arrData = $arrData;
		$this->arrValidationRules = $arrValidationRules;

		reset($arrValidationRules);
		while (list($fieldName, $arrRule) = each($arrValidationRules)) {
			#
			# CONVERT THE RULE TO NAMED VARIABLES
			#
			list($func, $validationCode, $failMessage) = $arrRule;
			
			#
			# EVAL THE FUNCTION - DO THE VALIDATION
			#
			$funcResult = $this->$func($fieldName, $arrData[$fieldName], $validationCode, $arrData);

			#
			# RECORD RESULTS
			# (remember that we're setting the internal variable here, 
			# so we still need to set $arrValidationResults before exiting)
			#
			$this->arrValidationResults[$fieldName] = array('data'=>$arrData[$fieldName], 'resolution'=>$funcResult);
		}
		#
		# AS NOTED ABOVE, THIS VARIABLE SHOULD BE SET HERE TO PASS BACK UP THE FUNCTION CHAIN
		#
		$arrValidationResults = $this->arrValidationResults;
	}
	
	# =========================================================================================================
	# CORE VALIDATION FUNCTIONS
	# =========================================================================================================
	function eval_string($string) {
		$string = preg_replace_callback("/(<\?=)(.*?)\?>/si", array($this,'eval_print_buffer'),$string);
		return preg_replace_callback("/(<\?php|<\?)(.*?)\?>/si", array($this,'eval_buffer'),$string);
	}
	function eval_buffer($string) {
		ob_start();
		eval("$string[2];");
		$return = ob_get_contents();
		ob_end_clean();
		return $return;
	 }
	 function eval_print_buffer($string) {
		ob_start();
		eval("print $string[2];");
		$return = ob_get_contents();
		ob_end_clean();
		return $return;
	 }
	# =========================================================================================================
	function evalPhp(&$fieldName, &$value, &$validationCode, &$arrData) {
		#
		# PUT VARS INTO GLOBAL SPACE TO BE EXTRACTED IN EVAL SPACE
		#
		$GLOBALS['arrData'] = $arrData; 
		
		#
		# PREP THE CODE TO BE VALIDATES WITH EVAL
		#
/*		$code = "<?  extract(\$GLOBALS, EXTR_SKIP | EXTR_REFS); $validationCode;?> "; */
		$code = "<?  extract(\$GLOBALS, EXTR_SKIP); $validationCode;?> "; // EXTR_REFS was killing the code in php 5.2
		
		#
		# EVAL THE CODE AND GET OUTPUT INTO RESULT VAR
		#
		return trim($this->eval_string($code));
	}
	# =========================================================================================================
}
