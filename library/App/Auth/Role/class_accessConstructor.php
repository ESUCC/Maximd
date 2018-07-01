<?

class accessConstructor {
	
	var $formCount;
	
	function buildStudentAccess() {
		
		$accessArray = $this->accessArray;
		
		$this->buildFormAccess($accessArray);
		
		return $accessArray;
	}
	
	function buildFormAccess(&$accessArray) {
		#
		# FORM PARAMATERS
		#
		$this->formCount = 32;		// SHOULD BE MOVED TO PREPEND
		
		for($i=1; $i<=$this->formCount; $i++) {
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
?>