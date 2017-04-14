<?php

class App_Service_AbstractFormService
{
	protected $formNumber;
	
	protected $usersession;
	public $validationArr;
	
    /**
     * $form - Zend_Dojo_Form
     *
     * @var Zend_Dojo_Form
     */
	public $form;
	
    /**
     * $db_form - Zend_Db_Table_Row reference
     *
     * @var Zend_Db_Table_Row
     */
	protected $db_form;
	
	function __construct($formNumber, $usersession)
	{
		$this->formNumber = $formNumber;
		$this->db = Zend_Registry::get('db');
		Zend_Db_Table_Abstract::setDefaultAdapter($this->db);
		
		$tableClassName = "Form$formNumber";
		include_once("Table/$tableClassName.php");
		$this->table = new $tableClassName;
		
		$this->usersession = $usersession;
		
		// set the form validity to true
        $this->valid = true;
	}
	
	public function buildFormAccess($id_student)
	{
        $student_auth = new App_Auth_StudentAuthenticator();
        $this->formAccessObj = $student_auth->validateStudentAccess($id_student, $this->usersession);		
		return $this->formAccessObj;
	}
	public function getStudent($id_student)
	{
		$stuObj = new StudentTable();
		return $stuObj->getForm($id_student);
	}
	
	public function getFormAccessObj()
	{
		return $this->formAccessObj;
	}
	
	function convertDatePostgresToJavascript($pgDate)
	{
		$pgDate = str_replace('-', '/', $pgDate);
		$pos = strpos($pgDate, '.');
		if ($pos === false) { // note: three equal signs
    		// not found...
    		return $pgDate;
		} else {
			// strip off .12333
			return substr($pgDate, 0, $pos);
		}
	}
	
	public function buildDbForm($id, $accessMode = "view", $page =1, $versionNumber = 1, $checkout = 0)
	{
		if(null != $id)
		{
			// retrieve form from the db
			$db_form_rows = $this->getForm($id);
			
			// isolate current row
			$this->db_form = $db_form_rows->current();
			
			// convert obj to array
			$this->db_form_data = $this->db_form->toArray();  
			$this->db_form_data['page'] = $page;
			$this->db_form_data['zend_checkout_time'] = $this->convertDatePostgresToJavascript($this->db_form_data['zend_checkout_time']);
		} else {
			return false;
		}
		
		// confirm user access to this student
        //
        // build access object
        $formAccessObj = $this->buildFormAccess($this->db_form['id_student']);
        Zend_debug::dump($formAccessObj);
        if(false === $formAccessObj)
        {
            return false;
        }
        
        // build student data for dynamic value lists and other
        $this->studentData = $this->getStudent($this->db_form['id_student']);
		        
        // confirm form is not checked out
        //$this->studentData = $this->getStudent($this->db_form['id_student']);
        if('edit' == $accessMode)
        {
        	$chiTime = new DateTime($this->db_form_data['zend_checkout_time']);
        	$nowTime = new DateTime("now");
        	if($nowTime < $chiTime)
        	{
        		// checkout active
        		if($this->db_form_data['zend_checkout_user'] != $this->usersession->sessIdUser)
        		{
        			return false;
        		}
        	}
        	// checkout the form
        	if($checkout) 
        	{	
        		// checkout also occurs when the form is saved
        		// in persistData in the zend html form definition
        		$result = $this->table->checkout($id, $this->usersession->sessIdUser);
        		if(false !== $result) $this->db_form_data['zend_checkout_time'] = $this->convertDatePostgresToJavascript($result);
        	}
        	
        }
	}
	
	
	public function getFormCheckoutTime($id)
	{
		if(null != $id)
		{
			// retrieve form from the db
			$db_form_rows = $this->getForm($id);
			
			// isolate current row
			$this->db_form = $db_form_rows->current();
			
			// convert obj to array
			$this->db_form_data = $this->db_form->toArray();
			return $this->convertDatePostgresToJavascript($this->db_form_data['zend_checkout_time']);
		}
		return false;		
	}
	
	// extend to add custom functionality
	public function getForm($id)
	{
		return $this->table->find($id);	
	}
	
	public function buildZendForm($accessMode = "view", $page =1, $version = 1)
	{
        $formContainerName = "Form_Form".$this->formNumber;
		$this->form = $this->getZendForm($formContainerName, $accessMode, $page, $version);
		return $this->form;
	}
	
	public function getZendForm($formContainerName, $accessMode = "view", $page =1, $version = 1)
	{
        $formFunctionName = $accessMode . '_page' . $page . '_version' . $version;
		$formContainer = new $formContainerName();
		$form = $formContainer->$formFunctionName();
		return $form; 
	}
	
	public function buildZendSubform($formContainerName, $subformFunctionPrefix, $accessMode = "view", $version = 1, $subformHeaderName=null, $addNotReq = false)
	{
		$subform = $this->getZendSubform($formContainerName, $subformFunctionPrefix, $accessMode, $version, $subformHeaderName, $addNotReq);
		return $subform;
	}
	
	public function getZendSubform($formContainerName, $subformFunctionPrefix, $accessMode, $version, $subformHeaderName, $addNotReq = false)
	{
        $formFunctionName = $subformFunctionPrefix . '_' . $accessMode  . '_version' . $version;
		$formContainer = new $formContainerName();
		$form = $formContainer->$formFunctionName($subformHeaderName, $addNotReq);
		return $form; 	
	}
	
    function validateBasic($data = null)
    {
    	if(null == $data) {
    		$data = $this->db_form_data;
    	} else {
    		$tmp = array();
    		foreach($this->db_form_data as $k => $v)
    		{
    			if(isset($data[$k])) $tmp[$k] = $data[$k];
    		}
    		$data = $tmp;
    	}
//    	print_r($data);die();
        // validate the form with db data
        if(!$this->form->isValid($data)) {
            $this->errors   = $this->form->getErrors();
            $this->messages = $this->form->getMessages();
            $this->valid = false;
            $this->validationArr = $this->buildValidationArray($this->form, $this->messages);
        }
    }
    
    function clearValidation()
    {
		unset($this->errors);
		unset($this->messages);
        $this->valid = true;
        unset($this->validationArr);
    }
    
    public function buildValidationArray($form, $zendFormMessages) {
        $valArr = array();
        $valRowID = 1;
        foreach($zendFormMessages as $keyName => $msgArr) {
        	
        	$view = new Zend_View;
        	
            if($form->getSubform($keyName)) {
                //getSubForms
                if($form->getSubform($keyName)->getElement('subform_label'))
                {
                    $subformLabel = $form->getSubform($keyName)->getElement('subform_label')->getLabel();
                } else {
                    $subformLabel = 'Row';
                }
                
                //
                // process subform element
                // get row number from the field name
                $keyNameArr = explode('_', $keyName);
                $rowNum = $keyNameArr[(count($keyNameArr)-1)];
                
                // add message for each error
                foreach($msgArr as $fieldName => $msg) {
                    $fieldWrapper = $form->getSubform($keyName)->getElement($fieldName)->getAttrib('wrapped');
                    
                    $outputMsg = $form->getSubform($keyName)->getMessages($fieldName); // get error msgs
                    // sometimes, I haven't figured out why, the message is in an array/array
                    // sometimes the array is numericly indexed, sometimes it has the error name
                    // if it's in an array, get the first row message
                    if(is_array($outputMsg)) $outputMsg = array_pop($outputMsg);
                    if(is_array($outputMsg)) $outputMsg = array_pop($outputMsg);
                    $label = $form->getSubform($keyName)->getElement($fieldName)->getLabel();
                    if(false !== $label)
                    {
                        $label = $subformLabel . ' '. $rowNum . ': ' . $label;
                        $valArr[] = array(
                                            'id' => $valRowID++, 
                                            'subform' => true, 
                                            'field' => $keyName . '-' . $fieldName, 
                                            'label' => $label, 
                                            'message' => $view->escape($outputMsg),
                                            'wrapper' => $fieldWrapper
                        );
                    } else {
                        
                        $valArr[] = array(
                                            'id' => $valRowID++,
                                            'subform' => true,
                                            'field' => $keyName . '-' . $fieldName, 
                                            'label' => 'Label is not defined for this object', 
                                            'message' => $view->escape($outputMsg),
                                            'wrapper' => $fieldWrapper
                        );
                    }	                
                }
            } else {
                // main form elements
                $fieldWrapper = $form->getElement($keyName)->getAttrib('wrapped');
                foreach($msgArr as $msgType => $msg) {
                	$label = $form->getElement($keyName)->getLabel();
                    if(false !== $label)
                    {
                        $valArr[] = array(
                                            'id' => $valRowID++,
                                            'subform' => false,
                                            'field' => $keyName, 
                                            'label' => $label, 
                                            'message' => $view->escape($msg),
                                            'wrapper' => $fieldWrapper
                        );
                    } else {
                        $valArr[] = array(
                                            'id' => $valRowID++,
                                            'subform' => false,
                                            'field' => $keyName, 
                                            'label' => 'Label is not defined for this object', 
                                            'message' => $view->escape($msg),
                                            'wrapper' => $fieldWrapper
                        );
                    }	                
                }
            }
            
        }
        return $valArr;
    }

	public function populateForm($data)
	{
		$this->form->populate($data);
	}

	public function createAjaxData($pkName, $returningData = null, $page = 1, $validationArray = null, $rowsToRebuild=null)
	{
		
		if(null == $validationArray) $validationArray = isset($this->validationArr)?$this->validationArr:array();
		if(null == $returningData) $returningData = isset($this->form)?$this->form->getValues():array();

        $objArr = array(
        	'0' => array(	$pkName => $returningData[$pkName])
        );
        
        // add validation errors to the returned object
        $objArr[0]['validationArr'] = $validationArray;
        $objArr[0]['zend_checkout_time'] = $returningData['zend_checkout_time'];
        
        // insert the page into the returned data
        $objArr[0]['page'] = $page;
        
        // add any subforms that have had rows removed
		$this->addHtmlReturnedRows($objArr, $rowsToRebuild);

		return new Zend_Dojo_Data($pkName, $objArr, 'id');
	}
	
	public function createAjaxAddRowData($pkName, $returningData = null, $page = 1, $validationArray = null, $rowsToRebuild=null, $newRowNum=1)
	{
		
		if(null == $validationArray) $validationArray = isset($this->validationArr)?$this->validationArr:array();
		if(null == $returningData) $returningData = isset($this->form)?$this->form->getValues():array();

        $objArr = array(
        	'0' => array(	$pkName => $returningData[$pkName])
        );
        
        // add validation errors to the returned object
        $objArr[0]['validationArr'] = $validationArray;

        // insert the page into the returned data
        $objArr[0]['page'] = $page;
        $objArr[0]['new_html'] = $this->form->getSubform($rowsToRebuild[0].'_'.$newRowNum)->render($this->form->getView());
        $objArr[0]['countSubrows'] = $newRowNum;
        return new Zend_Dojo_Data($pkName, $objArr, 'id');
	}
	
	function addHtmlReturnedRows(&$objArr, $rowsToRebuild)
    {
        
        // loop through subforms that have had rows removed
        foreach($rowsToRebuild as $subFormRow)
        {
        	$subFormName = $subFormRow['subformName'];
        	
            $objArr[0]['subform_options'][$subFormName]['rows_removed'] = true;
            $objArr[0]['subform_options'][$subFormName]['new_html_header'] = $this->form->getSubform($subFormName)->render($this->form->getView());
            for($i=1; $i <= $this->form->getSubform($subFormName)->getElement('count')->getValue(); $i++)
            {
                $objArr[0]['subform_options'][$subFormName]['new_html'][$i] = $this->form->getSubform($subFormName.'_'.$i)->render($this->form->getView());
            }
        }
    }

    public function subformValidationOverride($headerIndex)
    {
	    $i = 1;	
	    if(isset($this->db_form_data[$headerIndex]['override']) && 't' == $this->db_form_data[$headerIndex]['override'])
	    {
	        while($subform = $this->form->getSubform($headerIndex.'_'.$i))
	        {
	            // remove all validation
	            if($elements = $subform->getElements())
	            {
		            foreach($elements as $ename => $element)
		            {
		                // validators appear to be cleared, but they still fire on validation
		                $element->clearValidators();
		                
		                // we also have to clear the auto entered do not allow empty
		                $element->setAllowEmpty(true);
		                $element->setRequired(false);
		            }
	            }
	            $i++; 
	            
	        }
	    }
    }
    
    
	function locationValueList($studentDob, $formDate) {
		
		/*  
		 *  Model_Table_IepStudent::devDelay return code: 
		  	
		  	if($age < 3) {
				return 0;		
			} elseif($age < 6) {
				return 1;		
			} elseif($age < 22) {
				return 2;		
			} else {
				return 3;		
			}
		 */
		$devDelayCategory = Model_Table_IepStudent::devDelay ( $studentDob, $formDate );

		switch ($devDelayCategory) {
			case 0 :
				$returnArray = array(
									"1" => "Home",
									"2" => "Community Based",
									"3" => "Other",
				);
				break;
			case 1 :
				$returnArray = array(
									"4" => "Regular Early Childhood Program",
									"5" => "Separate School",
									"6" => "Separate Class",
									"7" => "Residential Facility",
									"8" => "Home",
									"9" => "Service Provider Location",
				);
				break;
			default :
				$returnArray = array(
									"5" => "Separate School",
									"7" => "Residential Facility",
									"10" => "Public School",
									"13" => "Home/Hospital",
									"14" => "Private School",
									"15" => "Correction/Detention Facility",
				);
		}
		
		return $returnArray;
	}
	
	function specialEducationValueList() {
		// wade says this menu is going to become dynamic
		
		// related services drop down menu
		$arrLabel = array("Choose...", "Audiological Services", "Braile/LP/Recorded Material", "Counseling", "Medical Diagnostic Services", "Home School Liaison", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy", "Psychological Services", "Reader", "Recreation", "School Health", "Speech/Language Therapy", "Transportation", "Vocational Training", "Assistive Technology Device", "Assistive Technology Service", "Other (Please Specify)");
		$arrValue = array("", "Audiological Services", "Braile/LP/Recorded Material", "Counseling", "Medical Diagnostic Services", "Home School Liaison", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy", "Psychological Services", "Reader", "Recreation", "School Health", "Speech/Language Therapy", "Transportation", "Vocational Training", "Assistive Technology Device", "Assistive Technology Service", "Other (Please Specify)");
		return array_combine($arrValue, $arrLabel);	
	}

	function storeFieldAsArray($dbData) {
		if(null == $dbData || '' == $dbData || substr_count($dbData, "\n") > 0) return array();
		$dbValuesArr = explode("\n", $dbData);
		return $dbValuesArr;
	}
	
}