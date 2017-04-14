<?php

//	$formInput->form_input_text("initial_verification_date", date_massage($afd['initial_verification_date']), true, "size=\"15\" $JSmodifiedCode");
//	$formInput->form_input_checkbox("initial_verification_date", date_massage($afd['initial_verification_date']), true, "size=\"15\" $JSmodifiedCode");
// 
// 
// function form_input_hidden( $name, $currentValue, $render=false, $internalExtra='') {
// 	require_once('class_form_element.php');
// 	$formInput = &new form_element();
// 	$formInput->form_input_hidden( $name, $currentValue, $render, $internalExtra);
// 	return $formInput;
// }
// function form_input_text( $name, $currentValue, $render=false, $internalExtra='', $valueNullDisplay='') {
// 	require_once('class_form_element.php');
// 	$formInput = &new form_element();
// 	$formInput->form_input_text( $name, $currentValue, $render, $internalExtra, $valueNullDisplay);
// 	return $formInput;
// }
// function form_input_textonly( $currentValue, $render=false) {
// 	require_once('class_form_element.php');
// 	$formInput = &new form_element();
// 	$formInput->form_input_textonly( $currentValue, $render);
// 	return $formInput;
// }
// function form_input_textarea( $name, $currentValue, $render=false, $internalExtra='', $valueNullDisplay='') {
// 	require_once('class_form_element.php');
// 	$formInput = &new form_element();
// 	$formInput->form_input_textarea( $name, $currentValue, $render, $internalExtra, $valueNullDisplay);
// 	return $formInput;
// }
// function form_input_radio( $name, $currentValue, $render=false, $internalExtra='', $valueDisplayArray='') {
// 	require_once('class_form_element.php');
// 	$formInput = &new form_element();
// 	$formInput->form_input_radio( $name, $currentValue, $render, $internalExtra, $valueDisplayArray);
// 	return $formInput;
// }
// function form_input_radio_separater( $name, $currentValue, $render=false, $internalExtra='', $valueDisplayArray='', $separater) {
// 	require_once('class_form_element.php');
// 	$formInput = &new form_element('modeGlobal', 'echo', $separater);
// 	$formInput->form_input_radio( $name, $currentValue, $render, $internalExtra, $valueDisplayArray);
// 	return $formInput;
// }
// function form_input_checkbox( $name, $currentValue, $render=false, $internalExtra='', $displayValue='', $checkedMatchValue=false) { # bools often match 't'
// 	require_once('class_form_element.php');
// 	$formInput = &new form_element();
// 	$formInput->form_input_checkbox( $name, $currentValue, $render, $internalExtra, $displayValue, $checkedMatchValue);
// 	return $formInput;
// }
// function form_input_select( $name, $currentValue, $render=false, $internalExtra='', $valueDisplayArray='', $defaultLabel='', $defaultValue='' ) {
// 	#
// 	# valueDisplayArray should be in format array( label1 => value1, label2 => value2)
// 	#
// 	require_once('class_form_element.php');
// 	$formInput = &new form_element();
// 	$formInput->form_input_select( $name, $currentValue, $render, $internalExtra, $valueDisplayArray, $defaultLabel, $defaultValue);
// 	return $formInput;
// }

// **********************************************************************

//
//
//

class form_element {
	
	var $checkedMatchValue;
	var $defalutLabel;
	var $defaultValue;
	var $displayHidden;
	var $displayValue;
	var $hiddenPreText;
	var $inputType;
	var $internalExtra;
	var $name;	
	var $renderMode;
	var $returnSwitch;
	var $valueDisplayArray;
	var $valueNullDisplay;
	var $nl2brSwitch;
	
	function reset_class() {
		$this->name = '';
		$this->currentValue = '';
		$this->inputType = '';
		$this->internalExtra = '';
		$this->displayHidden = '';
		$this->hiddenPreText = '';
		$this->valueNullDisplay = '';
	}
	
	function form_element($renderMode = 'function', $returnSwitch = 'return', $separater = '&nbsp;', $nl2brSwitch = true)
	{
		// ===============================
		// INIIAL PARAMATERS
			$this->renderMode = $renderMode;
			$this->returnSwitch = $returnSwitch;
			$this->separater = $separater;
			$this->nl2brSwitch = $nl2brSwitch;
		// ===============================
	}
	
	function overrideMode($overrideModeGlobal = '')
	{
	    $this->overrideModeGlobal = $overrideModeGlobal;
	}
	
	// ==============================================================================================================================
	// ================= INPUT FUNCTIONS ============================================================================================
	// ==============================================================================================================================
	function form_input_textarea( $name, $currentValue, $render=false, $internalExtra='', $valueNullDisplay='')
	{
		$this->name = $name;
		$this->currentValue = $currentValue;
		$this->inputType = 'textarea';
		$this->internalExtra = $internalExtra;
		$this->displayArray = $displayArray;
		$this->valueArray = $valueArray;
		$this->valueNullDisplay = $valueNullDisplay;
		if($render) {
			return $this->render_element();
		}
	}
	// ==============================================================================================================================
	function form_input_text( $name, $currentValue, $render=false, $internalExtra='', $valueNullDisplay='')
	{
		$this->name = $name;
		$this->currentValue = $currentValue;
		$this->inputType = 'input';
		$this->internalExtra = $internalExtra;
#		$this->displayArray = $displayArray;
#		$this->valueArray = $valueArray;
		$this->valueNullDisplay = $valueNullDisplay;
		if($render) {
			return $this->render_element();
		}
	}
	// ==============================================================================================================================
	function form_input_textonly( $currentValue, $render=false)
	{
		$this->currentValue = $currentValue;
		$this->inputType = 'textonly';
		$this->renderMode = 'function';
		if($render) {
			return $this->render_element();
		}
	}
	// ==============================================================================================================================
	function form_input_checkbox( $name, $currentValue, $render=false, $internalExtra='', $displayValue='', $checkedMatchValue='')
	{
		$this->name = $name;
		$this->currentValue = $currentValue;
		$this->inputType = 'checkbox';
		$this->internalExtra = $internalExtra;
		$this->displayValue = $displayValue;
		$this->checkedMatchValue = $checkedMatchValue;
		if($render) {
			return $this->render_element();
		}
	}
	// ==============================================================================================================================
	function form_input_radio( $name, $currentValue, $render=false, $internalExtra='', $valueDisplayArray='') {
		$this->name = $name;
		$this->currentValue = $currentValue;
		$this->inputType = 'radio';
		$this->internalExtra = $internalExtra;
		$this->valueDisplayArray = $valueDisplayArray;
		if($render) {
			return $this->render_element();
		}
	}
	// ==============================================================================================================================
	function form_input_select( $name, $currentValue, $render=false, $internalExtra='', $valueDisplayArray='', $defaultLabel='', $defaultValue='' ) {
		$this->name = $name;
		$this->currentValue = $currentValue;
		$this->inputType = 'select';
		$this->internalExtra = $internalExtra;
		$this->valueDisplayArray = $valueDisplayArray;
		$this->defaultLabel = $defaultLabel;
		$this->defaultValue = $defaultValue;

		if($render) {
			return $this->render_element();
		}
	}
	// ==============================================================================================================================
	function form_link_list( $render=false, $valueDisplayArray='', $linkArray='', $internalExtra='', $preExtra = '', $postExtra = '') {
		$this->inputType = 'link_list';
		$this->valueDisplayArray = $valueDisplayArray;
		$this->linkArray = $linkArray;
		$this->preExtra = $preExtra;
		$this->postExtra = $postExtra;

		if($render) {
			return $this->render_element();
		}
	}
	// ==============================================================================================================================
	function form_input_hidden( $name, $currentValue, $render=false, $internalExtra='') {
		$this->name = $name;
		$this->currentValue = $currentValue;
		$this->inputType = 'hidden';
		$this->internalExtra = $internalExtra;
#		$this->displayArray = $displayArray;
#		$this->valueArray = $valueArray;
		if($render) {
			return $this->render_element();
		}
	}
	// ==============================================================================================================================
	// ==============================================================================================================================
	// ==============================================================================================================================




	function render_element() {
		switch($this->renderMode) {
			case 'function':
				$functionName = 'render_element_'.$this->inputType;
				return $this->$functionName();
				break;
			case 'modeGlobalForm':
				return $this->render_modeGlobalForm();
				break;
			case 'modeGlobal':
			default:
				// modeGlobal
				return $this->render_modeGlobal();
		}
	}
	
	function render_modeGlobal() {
		// GLOBAL FOR modeGlobal
		global $mode;
		global $printpages;
				
		if($mode == 'edit' || 'edit' == $this->overrideModeGlobal) {
			$functionName = 'render_element_'.$this->inputType;
			return $this->$functionName();
		} else {
			switch($this->inputType) {
				case 'checkbox':
				    if('all' == $printpages) {
                        return $this->render_element_textonly($this->displayValue);
                        break;
				    } elseif('view' == $mode && '' != $this->displayValue) {
                        return $this->render_element_textonly($this->displayValue);
                        break;
				    } else {
                        return $this->render_element_textonly($this->currentValue);
                        break;
				    }
				case 'textarea':
				case 'hidden':
				case 'input':
                    return $this->render_element_textonly($this->currentValue);
                    break;
				case 'select':
				case 'radio':
					//
					// FLIP THE valueDisplayArray SO WE CAN DISPLAY THE display VALUE AS OPPOSED TO JUST THE VALUE
					$flippedArray = array_flip($this->valueDisplayArray);
					//
					return $this->render_element_textonly($flippedArray[$this->currentValue]);
					break;
			} 
		}
	}
	function render_modeGlobalForm() {
		// GLOBAL FOR modeGlobal
		global $mode;

		if($mode == 'edit') {
			$functionName = 'render_element_'.$this->inputType;
			$this->$functionName();
		} else {
			switch($this->inputType) {
				case 'textarea':
				case 'hidden':
				case 'input':
				case 'checkbox':
					$this->internalExtra .= ' disabled ';
					$functionName = 'render_element_'.$this->inputType;
					$this->$functionName();
					break;
				case 'select':
				case 'radio':
					//
					// FLIP THE valueDisplayArray SO WE CAN DISPLAY THE display VALUE AS OPPOSED TO JUST THE VALUE
					$flippedArray = array_flip($this->valueDisplayArray);
					//
					$this->render_element_textonly($flippedArray[$this->currentValue]);
					break;
			} 
		}
	}
	// ==============================================================================================================================
	// ================= RENDER FUNCTIONS ===========================================================================================
	// ==============================================================================================================================
		function render_element_hidden() {
			$inputString = '<INPUT ';
			$inputString .= 'TYPE="hidden" ';
			$inputString .= 'NAME="' . $this->name . '" ';
			$inputString .= 'VALUE="' . $this->currentValue . '" ';
			$inputString .= $this->internalExtra;
			$inputString .= '>';
			
			return $this->returnSwitch($inputString);
		}
	// ==============================================================================================================================
	// ==============================================================================================================================
		function render_element_textonly($value) {
			if($this->nl2brSwitch == true) {
				return $this->returnSwitch(nl2br($value));
			} else {
				return $this->returnSwitch($value);
			}
		}
	// ==============================================================================================================================
	// ==============================================================================================================================
		function render_element_input() {
			
			$inputString = '<INPUT ';
			$inputString .= 'TYPE="text" ';
			$inputString .= 'NAME="' . $this->name . '" ';
			$inputString .= 'VALUE="' . $this->currentValue . '" ';
			$inputString .= $this->internalExtra;
			$inputString .= '>';
			//echo "inputString: " .$inputString. "<BR>";
			return $this->returnSwitch($inputString);
		}
	// ==============================================================================================================================
	// ==============================================================================================================================
		function render_element_checkbox() {
			
			$inputString = '<INPUT ';
			$inputString .= 'TYPE="checkbox" ';
			$inputString .= 'NAME="' . $this->name . '" ';
			$inputString .= 'VALUE="' . $this->checkedMatchValue . '" ';
			#print("<BR><BR>value: " . $this->currentValue);
			#print("checkedMatchValue: " . $this->checkedMatchValue);
			#print("<BR><BR>");
			if( $this->currentValue === $this->checkedMatchValue ) {
				$inputString .= " checked ";
			}
			$inputString .= $this->internalExtra;
			$inputString .= '>' . $this->displayValue . $this->separater;
			
			return $this->returnSwitch($inputString);
		}
	// ==============================================================================================================================
	// ==============================================================================================================================
		function render_element_textarea() {
			
			$inputString = '<textarea ';
			$inputString .= 'NAME="' . $this->name . '" ';
			$inputString .= $this->internalExtra;
			$inputString .= '>'.$this->currentValue.'</textarea>';
			
			return $this->returnSwitch($inputString);
		}
	// ==============================================================================================================================
	// ==============================================================================================================================
		function render_element_radio() {

			$inputString = '';
			foreach($this->valueDisplayArray as $dispVal => $internalValue) {
				$inputString .= '<INPUT ';
				$inputString .= 'TYPE="radio" ';
				$inputString .= 'NAME="' . $this->name . '" ';
				$inputString .= 'VALUE="' . $internalValue . '" ';
				if( $this->currentValue === $internalValue) {
					$inputString .= " checked ";
				}
				$inputString .= $this->internalExtra;
				$inputString .= '>' . $dispVal . $this->separater;
			}
			
			return $this->returnSwitch($inputString);
		}
	// ==============================================================================================================================
	// ==============================================================================================================================
		function render_element_select() {

			$selectString  = '<select ';
			$selectString .= 'NAME="' . $this->name . '" ';
			$selectString .= $this->internalExtra;
			$selectString .= ">";
			
			if($this->currentValue == "" && $this->defaultValue != "") {
				$this->currentValue = $this->defaultValue;
			}
			
			if($this->defaultLabel != "none") {
				$selectString .= "<option value=\"\" selected=\"selected\">".$this->defaultLabel."</option>";
			}
			
			
			$count = count($this->valueDisplayArray);
			foreach($this->valueDisplayArray as $label => $value) {
				if($this->currentValue == $value) {
					$selectString .= "<option value=\"$value\" selected=\"selected\">$label</option>";
				} else {
					$selectString .= "<option value=\"$value\">$label</option>";
				}
			}
		
			$selectString .= "</select>";
			return $this->returnSwitch($selectString);
		}
	// ==============================================================================================================================
	// ==============================================================================================================================
		function render_element_link_list() {
			#pre_print_r($this->preExtra);	
			$count = count($this->valueDisplayArray);
			#echo "count:$count<BR>";
			$c=0;
			$selectString = "";
			foreach($this->valueDisplayArray as $label => $value) {
				if($c!=0 && $c != $count) {
					$selectString .= " | ";
				}
				$selectString .= isset($this->preExtra[$label])?$this->preExtra[$label]:"";
				$selectString .= "<a href=\"" . $this->linkArray[$label] . "\" ";
				$selectString .= $this->internalExtra;
				$selectString .= " >$value</a>";
				$selectString .= isset($this->postExtra[$label])?$this->postExtra[$label]:"";
				
				$c++;
			}
		
			return $this->returnSwitch($selectString);
		}
	// ==============================================================================================================================
	// ==============================================================================================================================
	// ==============================================================================================================================

	function returnSwitch($value) {
		switch($this->returnSwitch) {
			case 'echo': 
				echo $value;
				return true;
				break;
			case 'return':
				return $value;
				break;
		}
	}
}

