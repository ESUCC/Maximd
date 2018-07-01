<?php
/**
 * App_Form_SubformBuilder
 *
 * @category   Zend
 * @package    App_Form
 */
class App_Form_SubformBuilder 
{
	public $accessMode;
	public $page;
	public $version;
	private $className;
	
	public function __construct($options = null)
	{
//		if(null != $options) 
//		{
			try {
				$this->className = $options['className'];
				if(isset($options['subclassName'])) $this->subclassName = $options['subclassName'];
				$this->accessMode = $options['mode'];
				$this->page = $options['page'];
				$this->version = $options['version'];
			} catch (Exception $e) {
				echo "$e";	die();
				return $e;
			}
//		}
	}
	
	function buildSubform($subformHeaderName, $zendSubFormName = null, $notRequiredCheckbox = false)
	{
		// subform header row
		// get the name of the form to use for this header row
		// accessMode, version, subclassName, className passed in via config
		if(null == $zendSubFormName) 
			$zendSubFormName = "subform_header_" . $this->accessMode . "_version" . $this->version;
		
		// build the subform header from the subform if it exists
		if(isset($this->subclassName))
		{
			$subclassName = $this->subclassName;
			if(class_exists($subclassName)) $subFormObj = new $subclassName();
			if(!method_exists($subFormObj, $zendSubFormName)) unset($subFormObj);
			
		}
		// create the zend form object
		if(!isset($subFormObj))
		{
			$className = $this->className;
			$subFormObj = new $className();			
		}
		
		// build the form contents
		$zendSubForm = $subFormObj->$zendSubFormName($subformHeaderName, $notRequiredCheckbox);
		
		// mark form to be stored in an array in the main form
		$zendSubForm->setIsArray(true);
        $zendSubForm->setElementsBelongTo($subformHeaderName);

		return $zendSubForm;
	}

	function buildSimpleSubform($formClassName, $zendSubFormName)
	{
		//, $subformHeaderName, $zendSubFormName = null, $notRequiredCheckbox = false
		// create the zend form object
		$className = $formClassName;
		$subFormObj = new $className();			
		
		// build the form contents
		$zendSubForm = $subFormObj->$zendSubFormName($subformHeaderName, $notRequiredCheckbox);
//		
//		// mark form to be stored in an array in the main form
//		$zendSubForm->setIsArray(true);
//        $zendSubForm->setElementsBelongTo($subformHeaderName);
//
//		return $zendSubForm;
	}

	function buildSubformArray($subformHeaderName, $className, $count, $plainTextEditors = false, $editorType=null)
	{
		$zendSubForms = Array();
		
		// subform rows
		for($rownum = 1; $rownum <= $count; $rownum++)
		{
			$subFormObj = new $className();
			$subformName = $subformHeaderName."_".$rownum;
			$zendSubFormName = $subformHeaderName. "_" . $this->accessMode . "_version" . $this->version;
			if ('google' === $editorType) {
			    $subFormObj->setEditorType('App_Form_Element_GoogleEditor');
			}
			if ('tinyMce' === $editorType) {
			    $subFormObj->setEditorType('App_Form_Element_TinyMceTextarea');
			}

			
	        // set editor type if not default
			if($plainTextEditors && method_exists($subFormObj, 'setEditorType')) {
				$subFormObj->setEditorType('App_Form_Element_TextareaPlain');
			}
			
			$zendSubForm = $subFormObj->$zendSubFormName($subformName, false);
	      
			$zendSubForm->setIsArray(true);
	      	$zendSubForm->setElementsBelongTo($subformName);
	      	$zendSubForms[$subformName] = $zendSubForm;
		}
		
		return $zendSubForms;
	}
}
