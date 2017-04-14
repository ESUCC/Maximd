<?php

class My_Form_Subform {
	
	public $subtableName; // sub table name
	public $subformName; // subform name
	public $subtableKey; // primary key for the subtable
	public $subtableParentKey; // parent key field in the subtable
	public $formName; // form name containing subform definition
	public $mode; // mode
	public $version; // version
	public $subrowsCount; // count of related rows
	public $title;
	public $addRow; // show the add row button
	public $validationOverride;	// show the override validation checkbox
	

	public $actions = array(); // array of Action objects
	
	
	
	public function __construct($subtableName) {
		$this->setSubtableName($subtableName);
		$this->addRow = true;
		$this->validationOverride = true;
		$this->minRows = 1;
		$this->maxRows = 5;
	}
	
	public function setSubformTitle($title) {
		$this->title = $title;
	}
	public function setActions($actions) {
		if(is_array($actions))
		{
			$this->actions = $actions;
		} else {
			$this->actions = array($actions);
		}
	}
	public function addAction($action) {
		$this->actions[] = $action;
	}
	
	public function setVersion($version) {
		$this->version = $version;
	}

	public function setMaxRows($maxRows) {
		$this->maxRows = $maxRows;
	}

	public function getMaxRows() {
		return $this->maxRows;
	}
	
	public function setMinRows($minRows) {
		$this->minRows = $minRows;
	}

	public function getMinRows() {
		return $this->minRows;
	}
	
	public function setMode($mode) {
		$this->mode = $mode;
	}

	public function setFormName($formName) {
		$this->formName = $formName;
	}

	public function setSubtableParentKey($subtableParentKey) {
		$this->subtableParentKey = $subtableParentKey;
	}

	public function setSubtableKey($subtableKey) {
		$this->subtableKey = $subtableKey;
	}
	
	public function setModelFormName($modelFormName) {
		$this->modelFormName = $modelFormName;
	}
	
	public function getModelFormName() {
		return $this->modelFormName;
	}
	
	public function getSubformTitle() {
		return $this->title;
	}
	
	public function setSubformName($subformName) {
		$this->subformName = $subformName;
	}

	public function getSubformName() {
		return $this->subformName;
	}

	public function setSubtableName($subtableName) {
		$this->subtableName = $subtableName;
	}

	
	public function setSubrowsCount($subrowsCount) {
		$this->subrowsCount = $subrowsCount;
	}

	public function getSubrowsCount() {
		return $this->subrowsCount;
	}


	public function getActions() {
		return $this->actions;
	}

	public function getVersion() {
		return $this->version;
	}

	public function getMode() {
		return $this->mode;
	}

	public function getFormName() {
		return $this->formName;
	}

	public function getSubtableParentKey() {
		return $this->subtableParentKey;
	}

	public function getSubtableKey() {
		return $this->subtableKey;
	}

	public function getSubtableName() {
		return $this->subtableName;
	}
	public function getValidationOverride() {
		return $this->validationOverride;
	}
	public function setValidationOverride($flag) {
		return $this->validationOverride = $flag;
	}
	public function getAddRow() {
		return $this->addRow;
	}
	public function setAddRow($flag) {
		return $this->addRow = $flag;
	}
	

	public function runSubformChecks($subformName, My_Form_AbstractFormController $ownerObject)
	{
			//
			// check to make sure the subform header exists
			//
			if(!$ownerObject->retrieveSubFormHeaderExists($this->getSubformName(), $this->getFormName(), $this->getMode(), $this->getVersion()))
			{
				Zend_debug::dump("subform header (form definition) for form $subformName does not exist");
				return false;
			}
			
			//
			// check to make sure the subform  exists
			//
			if(!$ownerObject->retrieveSubFormExists($this->getSubformName(), $this->getFormName(), $this->getMode(), $this->getVersion()))
			{
				Zend_debug::dump("subform (form definition) for form $subformName does not exist");
				return false;
			}
			
			//
			// check to make sure the model exists
			//
			//Zend_debug::dump($this->getModelFormName());
			
			//			$zendSubForm = $ownerObject->retrieveSubForm ($this->getSubformName(), $this->getFormName(), $this->getMode(), $this->getVersion());
//			Zend_Controller_Front::throwExceptions(false);
//		throw new exception("subform: $subform->subformName is already added");
			return true;
	} 
	

}