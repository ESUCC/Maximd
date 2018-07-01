<?php
/**
 * App_Form_SubformBuilderNew
 *
 * @category   Zend
 * @package    App_Form
 */
class App_Form_SubformBuilderNew 
{

	public function __construct($formStructure)
	{
		$this->formStructure = $formStructure;
	}
	
	function buildSubforms(&$form, $dbData, $subformStructure, $prefixArray = array())
	{
		foreach ($subformStructure as $subform) {
			//print_r($subform0;;
			// =================================================================================
			// build and add subform header row
			// get the name of the form to use for this header row
			if(!isset($subform['zendSubFormName'])) 
				$zendSubFormName = "subform_header_edit_version" . $this->formStructure['version'];
			else 
				$zendSubFormName = $subform['zendSubFormName'];

			// build the subform header from the subform if it exists
			if(isset($subform['form'])) 
			{
				$subclassName = $subform['form'];
				if(class_exists($subclassName)) $subFormObj = new $subclassName();
				if(!method_exists($subFormObj, $zendSubFormName)) unset($subFormObj);
			}
			// create the zend form object
			if(!isset($subFormObj) && isset($subform['className']))
			{
				$className = $subform['className'];
				$subFormObj = new $className();			
			}
			
			// build the form contents
			$zendSubForm = $subFormObj->$zendSubFormName($subform['name']);//, $notRequiredCheckbox
			
			// mark form to be stored in an array in the main form
	        // get count of subrows
	        $prefixText = "";
	        if(count($prefixArray) > 0)
	        {	
	        	foreach ($prefixArray as $pre) {
	        		$prefixText .= "[$pre]";
	        	}
	        }
			$zendSubForm->setIsArray(true);
//			echo $prefixText.$subform['name'] . "\n";
	        $zendSubForm->setElementsBelongTo($prefixText.$subform['name']);

	        $form->addSubForm($zendSubForm, $subform['name']);
	        // end add header
	        // =================================================================================
	        
	        // get count of subrows
	        if(count($prefixArray) <= 0)
	        {	// count is set in the model for this form
	        	$count = $dbData[$subform['name']]['count'];
	        } else {
	        	$data = $dbData;
	        	foreach ($prefixArray as $pre) {
	        		$data = $data[$pre];
	        	}
	        	$count = $data[$subform['name']]['count'];
	        }
	    	//Create sequential subform rows 
	    	$zendSubForms = $this->buildSubformArray($subform['name'], $subform['form'], $this->formStructure['version'], $count);
	    	
	    	// add them to parent form
	    	foreach ($zendSubForms as $subformName => $sf) {
	    		$form->addSubForm($sf, $subformName);
		    	
	    		// rerun the process for any children of this row
	    		if(isset($subform['subforms']))
		    	{
		    		$passArr = $prefixArray;
		    		array_push($passArr, $subformName);
		    		$this->buildSubforms($form, $dbData, $subform['subforms'], $passArr);
		    	}
	    	}

			return $form;
		}
//		}
		
	}
	function buildSubformArray($subformHeaderName, $className, $version, $count)
	{
		$zendSubForms = Array();
		
		// subform rows
		for($rownum = 1; $rownum <= $count; $rownum++)
		{
			$subFormObj = new $className();
			$subformName = $subformHeaderName."_".$rownum;
			$zendSubFormName = $subformHeaderName. "_" . 'edit' . "_version" . $version;
			$zendSubForm = $subFormObj->$zendSubFormName($subformName, false);
	      
			$zendSubForm->setIsArray(true);
	      	$zendSubForm->setElementsBelongTo($subformName);
	      	$zendSubForms[$subformName] = $zendSubForm;
		}
		
		return $zendSubForms;
	}
	
}
