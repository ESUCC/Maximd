<?php

class Form_AbstractForm extends Zend_Dojo_Form {

	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public $accessMode;
	public $page;
	public $version;
	public $data;
	public $lps;

    public $states = array(
        'AL'=>'ALABAMA',
        'AK'=>'ALASKA',
        'AZ'=>'ARIZONA',
        'AR'=>'ARKANSAS',
        'CA'=>'CALIFORNIA',
        'CO'=>'COLORADO',
        'CT'=>'CONNECTICUT',
        'DE'=>'DELAWARE',
        'DC'=>'DISTRICT OF COLUMBIA',
        'FL'=>'FLORIDA',
        'GA'=>'GEORGIA',
        'HA'=>'HAWAII',
        'ID'=>'IDAHO',
        'IL'=>'ILLINOIS',
        'IN'=>'INDIANA',
        'IA'=>'IOWA',
        'KS'=>'KANSAS',
        'KY'=>'KENTUCKY',
        'LA'=>'LOUISIANA',
        'ME'=>'MAINE',
        'MD'=>'MARYLAND',
        'MA'=>'MASSACHUSETTS',
        'MI'=>'MICHIGAN',
        'MN'=>'MINNESOTA',
        'MS'=>'MISSISSIPPI',
        'MO'=>'MISSOURI',
        'MT'=>'MONTANA',
        'NE'=>'NEBRASKA',
        'NV'=>'NEVADA',
        'NH'=>'NEW HAMPSHIRE',
        'NJ'=>'NEW JERSEY',
        'NM'=>'NEW MEXICO',
        'NY'=>'NEW YORK',
        'NC'=>'NORTH CAROLINA',
        'ND'=>'NORTH DAKOTA',
        'OH'=>'OHIO',
        'OK'=>'OKLAHOMA',
        'OR'=>'OREGON',
        'PA'=>'PENNSYLVANIA',
        'RI'=>'RHODE ISLAND',
        'SC'=>'SOUTH CAROLINA',
        'SD'=>'SOUTH DAKOTA',
        'TN'=>'TENNESSEE',
        'TX'=>'TEXAS',
        'UT'=>'UTAH',
        'VT'=>'VERMONT',
        'VA'=>'VIRGINIA',
        'WA'=>'WASHINGTON',
        'WV'=>'WEST VIRGINIA',
        'WI'=>'WISCONSIN',
        'WY'=>'WYOMING');

    /**
     * $formHelper - App_Form_FormHelper
     *
     * @var App_Form_FormHelper
     */
    public $formHelper;
    
    /**
     * $valueListHelper - App_Form_ValueListHelper
     *
     * @var valueListHelper
     */
    public $valueListHelper;
    
    /**
     * $decoratorHelper - App_Form_DecoratorHelper
     *
     * @var decoratorHelper
     */
    public $decoratorHelper;
    
    /**
     * $form - Zend_Dojo_Form
     *
     * @var Zend_Dojo_Form
     */
	public $form;
	
	// ===========================================================================================
	public function __construct($options = null)
	{
		$this->formHelper = new App_Form_FormHelper();
		
        // build the helper for common reference for value lists
        $this->valueListHelper = new App_Form_ValueListHelper();
        
        // build the helper for common reference for value lists
        $this->decoratorHelper = new App_Form_DecoratorHelper();
        
        try {
			$this->accessMode = $options['mode'];
			$this->page = $options['page'];
			$this->version = $options['version'];
			$this->lps = $options['lps'];
        } catch (Exception $e) {
			echo "$e";die();
			return $e;
		}
		parent::__construct();
	}
	
	// ===========================================================================================
	protected $editorType;
    function setEditorType($type) {
    	$this->editorType = $type;
    }
    function getEditorType() {
    	return $this->editorType;
    }
    function buildEditor($name, $options = null, $additional = null) {
    	$editorClass = $this->getEditorType();
    	$editor = new $editorClass($name, $options, $additional);
    	if('App_Form_Element_TextareaPlain' == $editorClass) {
    		$editor->removeDecorator('label');
    		$editor->setAttrib('rows', "8");
    	}
    	return $editor;
    }
	// ===========================================================================================
	
	public function buildForm($raw = false)
	{
		// build the name of the function used to build this form
		// edit_p1_v1
		$buildFunctionName = $this->accessMode . '_p' . $this->page . '_v' . $this->version;
		//echo "page: $buildFunctionName<BR>";die();
		// call the internal function to build the zend form
		
		if($raw) {
			$buildFunctionName .= '_raw';
			$this->form = $this->$buildFunctionName($raw);
		} else {
		    
		    
			$this->form = $this->$buildFunctionName($raw);
		}
		
	}	
	
	protected function initialize() {
	    
	    /*
	     * Initialize translation for all site forms.
	     */
	    $translate = Zend_Registry::get('Zend_Translate');
	    $this->setDefaultTranslator($translate);
	    
		$this->setAction('search/search')->setMethod('post')->setAttrib('class', 'srchFrmHome');

		$this->id_student = new App_Form_Element_Hidden('id_student');
		$this->id_student->ignore = true;
		
		$this->page = new App_Form_Element_Hidden('page');
		$this->page->ignore = true;
		
		$this->zend_checkout_time = new App_Form_Element_Hidden('zend_checkout_time');

        $this->page_status = new App_Form_Element_Hidden('page_status');
        $this->page_status->setRequired(false);
        $this->page_status->setAllowEmpty(true);
		
	}
	
	public function student_data_header($subformName, $addNotReq) {//, $addNewRowButton=true, $addNotReq=true

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'edit_form_student_info_header.phtml' ) ) ) );
		
		$this->name_student = new App_Form_Element_Text('name_student');
		$this->name_student->setAttrib('disable', 'true');
		$this->name_student->noValidation();
		
		$this->age = new App_Form_Element_Text('age');
		$this->age->setAttrib('disable', 'true');
		$this->age->noValidation();
		
		$this->name_district = new App_Form_Element_Text('name_district');
		$this->name_district->setAttrib('disable', 'true');
		$this->name_district->noValidation();
		
		$this->dob = new App_Form_Element_DatePicker('dob');
		$this->dob->setAttrib('disable', 'true');
		$this->dob->noValidation();
		
		$this->gender = new App_Form_Element_Text('gender');
		$this->gender->setAttrib('disable', 'true');
		$this->gender->noValidation();
		
		$this->name_school = new App_Form_Element_Text('name_school');
		$this->name_school->setAttrib('disable', 'true');
		$this->name_school->noValidation();
		
		$this->grade = new App_Form_Element_Text('grade');
		$this->grade->setAttrib('disable', 'true');
		$this->grade->noValidation();
		
        $this->address = new App_Form_Element_Text('address');
        $this->address->setAttrib('disable', 'true');
        $this->address->noValidation();
        
        $this->parents = new App_Form_Element_Text('parents');
        $this->parents->setAttrib('disable', 'true');
        $this->parents->noValidation();
        
        return $this;
	}

	public function subform_header_edit_version1($subformName, $addNotReq = false) {
		return $this->edit_subform_version1_header($subformName, $addNotReq);
	}
	public function subform_header_edit_version2($subformName, $addNotReq = false) {
		return $this->edit_subform_version1_header($subformName, $addNotReq);
	}
	public function subform_header_edit_version3($subformName, $addNotReq = false) {
		return $this->edit_subform_version1_header($subformName, $addNotReq);
	}
	public function subform_header_edit_version4($subformName, $addNotReq = false) {
		return $this->edit_subform_version1_header($subformName, $addNotReq);
	}
	public function subform_header_edit_version5($subformName, $addNotReq = false) {
		return $this->edit_subform_version1_header($subformName, $addNotReq);
	}
	public function subform_header_edit_version9($subformName, $addNotReq = false) {
		return $this->edit_subform_version1_header($subformName, $addNotReq);
	}
	public function subform_header_edit_version10($subformName, $addNotReq = false) {
		return $this->edit_subform_version1_header($subformName, $addNotReq);
	}
	public function subform_header_edit_version11($subformName, $addNotReq = false) {
		return $this->edit_subform_version1_header($subformName, $addNotReq);
	}

	public function edit_subform_version1_header($subformName, $addNotReq) {//, $addNewRowButton=true, $addNotReq=true

		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'subformHeader.phtml' ) ) ) );
		
		// hidden element to tell the system to add a row
		$this->addrow = new App_Form_Element_Hidden('addrow');
		
		// button to call addSubformRow for the subform
		// sets the above to 1 I believe    
		$this->add_subform_row= new App_Form_Element_Button('add_subform_row', 'Add Row');
		$this->add_subform_row->setAttrib('onclick', 'addSubformRow(\''.$subformName.'\');');
		
		if($addNotReq) {
			$this->override = new App_Form_Element_Checkbox('override', array('label'=>'Not Required'));
			$this->override->setAttrib('onclick', "override(this.id, this.checked);");
		}
		
      	$this->count = new App_Form_Element_Hidden('count');

        $this->subformTitle = new Zend_Form_Element_Hidden('subformTitle');
      	                
        //
      	// add hidden elements for subform counts
      	//
      	$this->subformName = new App_Form_Element_Hidden('subformName', $subformName);
        $this->subformName->setValue($subformName);
		return $this;
	}
	
    function clearValidation()
    {
		unset($this->errors);
		unset($this->messages);
        unset($this->validationArr);
    }
	public function buildFormPagesArray(Form_AbstractForm $formClass, $version)
	{
		$i = 1;
		$formPages = array();
		while (method_exists($formClass, $methodName = $this->accessMode . '_p' . $i . '_v' . $version)) {
			$formPages[$i++] = $formClass->$methodName();
		}
		return $formPages;
	}
    
	// ===========================================================================================
    // rewrite
	public function validateFormPages(array $formPages)
	{
		$validationArray = array();
		foreach($formPages as $formPage)
		{
			$validationArray[] = $this->validateFormPage($formPage);
		}
		return $validationArray;
	}
	
    public function validateFormPage(Zend_Form $formPage)
    {	
    	$retArr = array();
    	
    	// @todo: can this be integrated into the validation checks themselves?
    	// override validation if Not Required is checked
    	foreach($formPage->getSubforms() as $k => $sf)
    	{
    		if($sf->getElement('override') && $sf->getElement('override')->getValue())
    		{
    			$count = $sf->getElement('count')->getValue();
    			for($i=1; $i<=$count; $i++)
    			{
    				$this->removeValidation($formPage->getSubform($k.'_'.$i));
    			}
    		}
    	}
    	
    	$retArr['valid'] = $formPage->isValid($formPage->getValues());
    	$retArr['errors'] = $formPage->getErrors();
    	$retArr['messages'] = $formPage->getMessages();
		return $retArr;
    }

    public function removeValidation($form, $exceptions = array())
    {
        // loop through form elements and change the helper
        foreach($form->getElements() as $n => $e)
        {
			if(false === array_search($n, $exceptions))
			{
	            // disable elements that have options
	            // so that the proper option is displayed
				$form->$n->clearValidators();
				$form->$n->setAllowEmpty(true);
				$form->$n->setRequired(false);
			}
        }
        // loop through the subforms and pass them to 
        // this function as forms
        $subforms = $form->getSubforms();
        foreach($subforms as $n => $sf)
        {
			$this->removeValidation($sf);
        }
    }

	public function formValidPagesDisplay($status, $validationArray, $id = 'pagesValid')
	{
		$retString = "<span id=\"{$id}\" style=\"white-space:nowrap;\">" . ucfirst($status) . " [";
		$page = 1;
		foreach($validationArray as $valid)
		{
			$class = $valid ? "btsb" : "btsbRed"; 
			$retString .= "<span class=\"".$class."\">" .$page++ ."</span>"; 
		}
		$retString .= "]</span>";
		return $retString;
	}

	public function formValidPagesString($status, $validationArray)
	{
		$retString = '';
		$page = 1;
		foreach($validationArray as $valid)
		{
			$retString .= $valid ? '1' : '0'; 
		}
		return $retString;
	}

	static function selectBoolConverter($value) {

    	if(is_null($value)) // null
		{	
			return null;
		}
		elseif((boolean) $value === false || 'f' === strtolower($value)
            || "no" === strtolower($value) || '0' === $value || 0 === $value)
		{
			return 'f';
		} 
		elseif((boolean) $value === true || 't' === strtolower($value)
            || 'yes' === strtolower($value) || '1' === $value || 1 === $value)
		{
			return 't';
		}
		
		
	}

    public function removeSrsFormHelpers($form, $exceptions = array())
    {
        // loop through form elements and change the helper
        foreach($form->getElements() as $n => $e)
        {
            if(false === array_search($n, $exceptions))
            {
                $form->$n->setAttrib('onchange', NULL);
                $form->$n->setAttrib('onfocus', NULL);
                $form->$n->removeDecorator('colorme');
            }
        }
    }
    public function removeLabels($form, $exceptions = array())
    {
        // loop through form elements and change the helper
        foreach($form->getElements() as $n => $e)
        {
            if(false === array_search($n, $exceptions))
            {
                $form->$n->removeDecorator('label');
            }
        }
    }

    public function removeNamedDecorator($form, $decoratorName, $exceptions = array())
    {
        // loop through form elements and change the helper
        foreach($form->getElements() as $n => $e)
        {
            if(false === array_search($n, $exceptions))
            {
                $form->$n->removeDecorator($decoratorName);
            }
        }
    }

    public function convertValueToDescription($form, $exceptions = array())
    {
        // loop through form elements and change the helper
        foreach($form->getElements() as $n => $e)
        {
            if(false === array_search($n, $exceptions))
            {
                $form->$n->addDecorator('description', array ('tag' => 'span', 'class'=>'list-item-description') );
                $form->$n->setDescription($form->$n->getValue());
            }
        }
    }

    public function wrapWithDivs($form, $exceptions = array())
    {
        // loop through form elements and change the helper
        foreach($form->getElements() as $n => $e)
        {
            if(false === array_search($n, $exceptions))
            {
                $form->$n->addDecorator(array('wrapper'=>'HtmlTag'), array ('tag' => 'div', 'class'=>'wrapperDiv') );
            }
        }
    }
    public function addErrorDecorator($form, $exceptions = array())
    {
        // loop through form elements and change the helper
        foreach($form->getElements() as $n => $e)
        {
            if(false === array_search($n, $exceptions))
            {
                $form->$n->addDecorator('errors');
                $form->$n->getDecorator('errors')->setOption('escape', false);
            }
        }
    }
    public function replaceElementDecorators($form, $decorators, $exceptions = array())
    {
        // loop through form elements and change the helper
        foreach($form->getElements() as $n => $e)
        {
            if(false === array_search($n, $exceptions) && 'App_Form_Element_Hidden'!=$form->$n->getType())
            {
                $form->$n->setDecorators($decorators);
            }
        }
    }
    public function convertSelectToMultiSelect($form)
    {
        // loop through form elements and change the helper
        foreach($form->getElements() as $n => $e)
        {
            if('App_Form_Element_Select' == $form->$n->getType())
            {
                $ne = new App_Form_Element_MultiSelect($n);
//                foreach($e->getOptions() as $oName => $oValue) {
//                    $ne->setOption($oName, $oValue);
//                }
                foreach($e->getAttribs() as $aName => $aValue) {
                    $ne->setAttrib($aName, $aValue);
                }
                $ne->setValue($e->getValue());
                $ne->setDescription($e->getDescription());
                $form->$n = $ne;
            }
        }
    }

}