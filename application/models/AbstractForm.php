<?php

class Model_AbstractForm
{
	protected $formNumber;

	protected $usersession;
	protected $config;
	public $validationArr;
	public $db;

    /**
     * $db_form - Zend_Dojo_Form
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

		$tableClassName = "Model_Table_Form$formNumber";
//		include_once("Table/$tableClassName.php");
		$this->table = new $tableClassName;

		$this->usersession = $usersession;

		// set the form validity to true
        $this->valid = true;
	}

	public function writevar1($var1,$var2) {
	
	    ob_start();
	    var_dump($var1);
	    $data = ob_get_clean();
	    $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
	    $fp = fopen("/tmp/textfile.txt", "a");
	    fwrite($fp, $data2);
	    fclose($fp);
	}
	
	public function buildFormAccess($id_student)
	{
      	$student_auth = new App_Auth_StudentAuthenticator();
      	$this->formAccessObj = $student_auth->validateStudentAccess($id_student, $this->usersession);
//      	Zend_Debug::dump($this->formAccessObj);
       //  $this->writevar1($this->formAccessObj,'this is ithe form access');
       // returns editaccess and role in an array
		return $this->formAccessObj;
	}
	public function getStudent($id_student)
	{
//		$stuObj = new StudentTable();
//		return $stuObj->getForm($id_student);
		$studentObj = new Model_Table_IepStudent();
		return $studentObj->find($id_student);

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
		// called from the Form0xx in application/models
		if(null != $id)
		{
			// retrieve form from the db
			$db_form_rows = $this->getForm($id);

			// isolate current row
			$this->db_form = $db_form_rows->current();

			if(null != $db_form_rows->current())
			{
				// convert obj to array
				$this->db_form_data = $this->db_form->toArray();

				$studentObj = new Model_Table_ViewAllStudent();
				$student = $studentObj->find($this->db_form['id_student'])->current();

				if(null!=$this->db_form_data['id_county']) {
					// set county, district and school names
					$countyObj = new Model_Table_County();
					$county = $countyObj->find($this->db_form_data['id_county'])->current();

					$districtObj = new Model_Table_District();
					$district = $districtObj->find($this->db_form_data['id_county'], $this->db_form_data['id_district'])->current();

					$schoolObj = new Model_Table_School();
					$school = $schoolObj->find($this->db_form_data['id_county'], $this->db_form_data['id_district'], $this->db_form_data['id_school'])->current();
				} else {
					// set county, district and school names
					$countyObj = new Model_Table_County();
					$county = $countyObj->find($student['id_county'])->current();

					$districtObj = new Model_Table_District();
					$district = $districtObj->find($student['id_county'], $student['id_district'])->current();

					$schoolObj = new Model_Table_School();
					$school = $schoolObj->find($student['id_county'], $student['id_district'], $student['id_school'])->current();

				}

				if(null!=$county) {
					// set names into form data
					$this->db_form_data['name_county'] = @$county->name_county;
				}
				if(null!=$district) {
					$this->db_form_data['name_district'] = @$district->name_district;
				}
				if(null!=$school) {
					$this->db_form_data['name_school'] = @$school->name_school;
				}

				$this->db_form_data['page'] = $page;
				$this->db_form_data['zend_checkout_time'] = $this->convertDatePostgresToJavascript($this->db_form_data['zend_checkout_time']);
			} else {
				throw new exception('Form not found.');
				return false;
			}
		} else {
			throw new exception('no document id.');
			return false;
		}

        // build student info
        $studentObj = new Model_Table_ViewAllStudent();
        $student = $studentObj->find($this->db_form['id_student'])->current();
        if (null == $student) {
            throw new Zend_Db_Table_Exception("Student Not Found");
            $this->db_form_data['student_data'] = array();
        } else {
            $this->db_form_data['student_data'] = $student->toArray();
		    // set name_student
		    if (!empty($this->db_form_data['student_data']['name_middle'])) {
				$this->db_form_data['student_data']['name_student'] = $this->db_form_data['student_data']['name_first'].' '. $this->db_form_data['student_data']['name_middle']
					.' '. $this->db_form_data['student_data']['name_last'];
		    } else {
				$this->db_form_data['student_data']['name_student'] = $this->db_form_data['student_data']['name_first'].' '. $this->db_form_data['student_data']['name_last'];
		    }

		    // override student values with form values
		    // this guarantees that the displayed names are those from where the form
		    // was finalized and not where the student resides
		    if('Final' == $this->db_form_data['status']) {

		    	// for older forms, finalized_id_county will not be set
		    	if(isset($this->db_form_data['finalized_id_county']) &&
		    			isset($this->db_form_data['finalized_id_county']) &&
		    			isset($this->db_form_data['finalized_id_district']) &&
		    			'' != $this->db_form_data['finalized_id_school'] &&
		    			'' != $this->db_form_data['finalized_id_district'] &&
		    			'' != $this->db_form_data['finalized_id_school']) {
		    		$finalCounty = $countyObj->find($this->db_form_data['finalized_id_county'])->current();
			    	$finalDistrict = $districtObj->find($this->db_form_data['finalized_id_county'], $this->db_form_data['finalized_id_district'])->current();
			    	$finalSchool = $schoolObj->find($this->db_form_data['finalized_id_county'], $this->db_form_data['finalized_id_district'], $this->db_form_data['finalized_id_school'])->current();
		    	} else {
		    		$finalCounty = $countyObj->find($this->db_form_data['id_county'])->current();
			    	$finalDistrict = $districtObj->find($this->db_form_data['id_county'], $this->db_form_data['id_district'])->current();
			    	$finalSchool = $schoolObj->find($this->db_form_data['id_county'], $this->db_form_data['id_district'], $this->db_form_data['id_school'])->current();
		    	}

		    	if(null != $finalCounty) $this->db_form_data['finalized_name_county'] = $finalCounty->name_county;
				if(null != $finalDistrict) $this->db_form_data['finalized_name_district'] = $finalDistrict->name_district;
				if(null != $finalSchool) $this->db_form_data['finalized_name_school'] = $finalSchool->name_school;

				if('' == $this->db_form_data['finalized_student_name']) {
					$this->db_form_data['finalized_student_name'] = $this->db_form_data['student_data']['name_student'];
				}
				if('' == $this->db_form_data['finalized_parents']) {
					$this->db_form_data['finalized_parents'] = $this->db_form_data['student_data']['parents'];
				}
				if('' == $this->db_form_data['finalized_grade']) {
					$this->db_form_data['finalized_grade'] = $this->db_form_data['student_data']['grade'];
				}
				if('' == $this->db_form_data['finalized_gender']) {
					$this->db_form_data['finalized_gender'] = $this->db_form_data['student_data']['gender'];
				}


		    }
		    if('Suspended' == $this->db_form_data['status']) {

		    	// for older forms, suspended_id_county will not be set
		    	if(isset($this->db_form_data['suspended_id_county']) &&
		    			isset($this->db_form_data['suspended_id_county']) &&
		    			isset($this->db_form_data['suspended_id_district']) &&
		    			'' != $this->db_form_data['suspended_id_school'] &&
		    			'' != $this->db_form_data['suspended_id_district'] &&
		    			'' != $this->db_form_data['suspended_id_school']) {
		    		$finalCounty = $countyObj->find($this->db_form_data['suspended_id_county'])->current();
			    	$finalDistrict = $districtObj->find($this->db_form_data['suspended_id_county'], $this->db_form_data['suspended_id_district'])->current();
			    	$finalSchool = $schoolObj->find($this->db_form_data['suspended_id_county'], $this->db_form_data['suspended_id_district'], $this->db_form_data['suspended_id_school'])->current();
		    	} else {
		    		$finalCounty = $countyObj->find($this->db_form_data['id_county'])->current();
			    	$finalDistrict = $districtObj->find($this->db_form_data['id_county'], $this->db_form_data['id_district'])->current();
			    	$finalSchool = $schoolObj->find($this->db_form_data['id_county'], $this->db_form_data['id_district'], $this->db_form_data['id_school'])->current();
		    	}

		    	if(null != $finalCounty) $this->db_form_data['suspended_name_county'] = $finalCounty->name_county;
				if(null != $finalDistrict) $this->db_form_data['suspended_name_district'] = $finalDistrict->name_district;
				if(null != $finalSchool) $this->db_form_data['suspended_name_school'] = $finalSchool->name_school;

				if('' == $this->db_form_data['suspended_student_name']) {
					$this->db_form_data['suspended_student_name'] = $this->db_form_data['student_data']['name_student'];
				}
				if('' == $this->db_form_data['suspended_parents']) {
					$this->db_form_data['suspended_parents'] = $this->db_form_data['student_data']['parents'];
				}
				if('' == $this->db_form_data['suspended_grade']) {
					$this->db_form_data['suspended_grade'] = $this->db_form_data['student_data']['grade'];
				}
				if('' == $this->db_form_data['suspended_gender']) {
					$this->db_form_data['suspended_gender'] = $this->db_form_data['student_data']['gender'];
				}


		    }
        }

        $accessObj = $this->buildFormAccess($this->db_form['id_student']);
        if(false === $accessObj) {
        	throw new App_Exception_NoAccess("No access to this student's forms.");
            return false;
        }
        $this->db_form_data['student_data']['user_access']['access_level'] = $accessObj->access_level;
        $this->db_form_data['student_data']['user_access']['description'] = $accessObj->description;


        // build age arr


//        Zend_Debug::dump($this->db_form_data);die();
        // confirm form is not checked out
        if('edit' == $accessMode)
        {
        	$chiTime = new DateTime($this->db_form_data['zend_checkout_time']);
        	$nowTime = new DateTime("now");
        	if($nowTime < $chiTime)
        	{
        		// checkout active
        		if($this->db_form_data['zend_checkout_user'] != $this->usersession->sessIdUser)
        		{
        		    
                    $personnel = new Model_Table_PersonnelTable();
                  
                
                    
                    $person = $personnel->find($this->db_form_data['zend_checkout_user'])->current()->toArray();
                   // $this->writevar1($person,'this is hte person');
                    
                    throw new App_Exception_Checkout("This form is currently being used by {$person['name_first']} {$person['name_last']}. This form will remain locked until " . $this->db_form_data['zend_checkout_time'] . " or until {$person['name_first']} {$person['name_last']} clicks the DONE button on this form.");
                  
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
	public function finalizeForm($id)
	{

		try {
			// get the current form
			$currentForm = $this->table->find($id)->current();

			$stuObj = new Model_Table_StudentTable();
			$studentArr = $stuObj->studentInfo($currentForm['id_student']);
			$studentInfo = $studentArr[0];

			$formTable = $this->table;

			// Mike added this 11-10-2017 so that we can finalize edfi forms in the db table edfi.
			
			
			// end of Mike add
			
			
			
			// derive pk name
			$pkArr = $formTable->get_primary();
			$keyName = is_array($pkArr) ? array_shift($pkArr) : $pkArr;
			$where = $formTable->getAdapter()
					 ->quoteInto($keyName.' = ?', $id);

        //
		// validate the date_conference is not too far in the future
        //
        // ========================================================================
//		$formNum = substr($tableName, -2, 2);
//		if("02" == $formNum) {
//			$form_timestamp = strtotime($afd['date_mdt']);
//		} elseif("04" == $formNum) {
//			$form_timestamp = strtotime($afd['date_conference']);
//		} elseif("13" == $formNum) {
//			$form_timestamp = strtotime($afd['meeting_date']);
//		}
//		$timelimit_timestamp = strtotime("today + 1 week");
//		if (("02" == $formNum || "04" == $formNum || "13" == $formNum) && ($form_timestamp > $timelimit_timestamp) ) {
//			$msgFlag = 2;
//			$msgType = "finalize";
//			$msgText = "Sorry, this form can&rsquo;t be finalized because the date of conference is too far in the future.";
//			return true;
//		}
      
      // Mike added this 11-10-2017 in order to update the edfi db when a form is finalized.
      
					 $edfi=new Model_Table_Edfi();
					
					 // Mike changed this 1-8-2018 because people couuld not finalize form010 or progress reports.
					// if(isset($currentForm['id_form_004']).  there is an id_form_004 in form010
					
					 if(isset($currentForm['id_form_004']) && !isset($currentForm['id_form_010'])){
					     $edfi->updateOneStudent($currentForm);
					 }
					 	
					 if(isset($currentForm['id_form_023'])){
					     $edfi->updateOneStudent($currentForm);
					 }
					 	
					 if(isset($currentForm['id_form_002'])){
					     $edfi->updateOneStudent($currentForm);
					 }
					 	
					 if(isset($currentForm['id_form_022'])){
					     //      $this->writevar1($currentForm,'this is current form in abstractform model line 349');
					      
					     $edfi->updateOneStudent($currentForm);
					      
					 }			 
					 
					 
		return $formTable->update(
			array(
				'status'=>'Final',
				'finalized_date'=> date('Y-m-d', strtotime('now')),
				'finalized_student_name'=> $studentInfo['name_student_full'],
				'finalized_dob'=> $studentInfo['dob'],
				'finalized_grade'=> $studentInfo['grade'],
				'finalized_age'=> $studentInfo['age'],
				'finalized_gender'=> $studentInfo['gender'],
				'finalized_parents'=> $studentInfo['guardian_names'],
				'finalized_id_county'=> $studentInfo['id_county'],
				'finalized_id_district'=> $studentInfo['id_district'],
				'finalized_id_school'=> $studentInfo['id_school'],
				'finalized_address'=> $studentInfo['address'],
			),$where);

		} catch (Exception $e) {
			throw new Exception($e);
			return false;
		}
	}

	public function resumeForm($id)
	{
        try {
            // get the current form
            $currentForm = $this->table->find($id)->current();

            $stuObj = new Model_Table_StudentTable();
            $studentArr = $stuObj->studentInfo($currentForm['id_student']);
            $studentInfo = $studentArr[0];

            $formTable = $this->table;

            // derive pk name
            $pkArr = $formTable->get_primary();
            $keyName = is_array($pkArr) ? array_shift($pkArr) : $pkArr;
            $where = $formTable->getAdapter()
                ->quoteInto($keyName.' = ?', $id);

            return $formTable->update(
                array(
                    'status'=>'Draft',
                    'suspended_date'=> null,
                    'suspended_student_name'=> null,
                    'suspended_dob'=> null,
                    'suspended_grade'=> null,
                    'suspended_age'=> null,
                    'suspended_gender'=> null,
                    'suspended_parents'=> null,
                    'suspended_id_county'=> null,
                    'suspended_id_district'=> null,
                    'suspended_id_school'=> null,
                    'suspended_address'=> null,
                ),$where);
        } catch (Exception $e) {
            throw new Exception($e);
            return false;
        }

    }

	public function suspendForm($id)
	{
		try {
			// get the current form
			$currentForm = $this->table->find($id)->current();

			$stuObj = new Model_Table_StudentTable();
			$studentArr = $stuObj->studentInfo($currentForm['id_student']);
			$studentInfo = $studentArr[0];

			$formTable = $this->table;

			// derive pk name
			$pkArr = $formTable->get_primary();
			$keyName = is_array($pkArr) ? array_shift($pkArr) : $pkArr;
			$where = $formTable->getAdapter()
					 ->quoteInto($keyName.' = ?', $id);

            return $formTable->update(
			array(
				'status'=>'Suspended',
				'suspended_date'=> date('Y-m-d', strtotime('now')),
				'suspended_student_name'=> $studentInfo['name_student_full'],
				'suspended_dob'=> $studentInfo['dob'],
				'suspended_grade'=> $studentInfo['grade'],
				'suspended_age'=> $studentInfo['age'],
				'suspended_gender'=> $studentInfo['gender'],
				'suspended_parents'=> $studentInfo['guardian_names'],
				'suspended_id_county'=> $studentInfo['id_county'],
				'suspended_id_district'=> $studentInfo['id_district'],
				'suspended_id_school'=> $studentInfo['id_school'],
				'suspended_address'=> $studentInfo['address'],
			),$where);
		} catch (Exception $e) {
			throw new Exception($e);
			return false;
		}
	}

	public function unfinalizeForm($id)
	{
		$formTable = $this->table;
		$pkArr = $formTable->get_primary();
		$keyName = is_array($pkArr) ? array_shift($pkArr) : $pkArr;
		$where = $formTable->getAdapter()
				 ->quoteInto($keyName.' = ?', $id);
		return $formTable->update(array('status'=>'Draft'),$where);
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
	    	$subform = $this->form->getSubform($headerIndex.'_'.$i);
	        while(false !== $subform)
	        {
	            // remove all validation
	            $elements = $subform->getElements();
	            if(false !== $elements)
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


	function sesisExitCategory($studentDob, $formDate) {
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
        switch($devDelayCategory) {
            case 0:
                $arrLabel = array(
                    "Birth to Two",
                    "Completion of the IFSP prior to reaching maximum age for Part C.",
                    "Not Eligible for Part B, Exit to other program",
                    "Not Eligible for Part B, Exit with no referral",
                    "Part B eligibility not determined",
                    "Deceased",
                    "Moved Out of State",
                    "Withdrawn by parent",
                    "Attempts to contact parents unsuccessful",
                    "Transferred to another School District",
                    "Duplicate or keying error",
                );
                $arrValue = array(
                    "",
                    "12",
                    "13",
                    "14",
                    "15",
                    "06",
                    "16",
                    "09",
                    "17",
                    "01",
                    "10",
                );
                break;
            case 1:
            case 2:
            $arrLabel = array(
                "Three to 21",
                "Returned to Full-Time Regular Education Program",
                "Graduated with a regular high school diploma",
                "Graduated with a Certificate of Completion",
                "Reached maximum age",
                "Deceased",
                "Dropped Out",
                "Expulsion",
                "Duplicate or keying error",
                "Transferred to another School District",
                "Moved known to be continuing",
            );
            $arrValue = array(
                "",
                "02",
                "03",
                "04",
                "05",
                "06",
                "07",
                "08",
                "10",
                "01",
                "11",
            );
                break;
            case 3:
                $arrLabel = array(
                    "Three to 21",
                    "Returned to Full-Time Regular Education Program",
                    "Graduated with a regular high school diploma",
                    "Graduated with a Certificate of Completion",
                    "Reached maximum age",
                    "Deceased",
                    "Dropped Out",
                    "Expulsion",
                    "Duplicate or keying error",
                    "Transferred to another School District",
                    "Moved not known to be continuing",
                );
                $arrValue = array(
                    "disable",
                    "02",
                    "03",
                    "04",
                    "05",
                    "06",
                    "07",
                    "08",
                    "10",
                    "1",
                    "11",
                );
                break;
        }
        return array_combine($arrValue, $arrLabel);

    }


	function locationValueList($studentDob, $formDate) {
		// is this ever called?
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
		if(null == $dbData || '' == $dbData || substr_count($dbData, "\n") <= 0)
        {
            return array($dbData);
        }

		$dbValuesArr = explode("\n", $dbData);
		return $dbValuesArr;
	}

    public function deleteForm($pkName, $id)
    {
        $this->table->delete("$pkName = '$id'");
    }

    function deleteFormInsert($pkeyName, $document, $formNum) {

    	$sessUser = new Zend_Session_Namespace('user');

    	$row = $this->getForm($document)->current()->toArray();
        if(false === $row)
        {
            return false;
        }

        $data = addslashes(implode("xx|||xx|||xx", $row));
        $dataKeys = addslashes(implode("xx|||xx|||xx", array_keys($row)));
        if(array_key_exists("date_notice", $row)) {
            $date_deleted = $row['date_notice'];
        } elseif(array_key_exists("date_conference", $row)) {
            $date_deleted = $row['date_conference'];
        }

        $deletedFormsObj = new Model_Table_DeletedForms();
        $data = array(
            "id_form" => $row[$pkeyName],
            "form_name" => 'iep_form_' . $formNum,
            "form_data" => $data,
            "id_student" => $row['id_student'],
            "date_created" => $row['timestamp_created'],
            "date_deleted" => 'today',
            "deleted_by" => $sessUser->id_personnel,
        );

        $newId = $deletedFormsObj->insert($data);
        if(false === $newId) {
        	return false;
        } else {
        	return true;
        }
    }

    public function studentFormOptions($status)
    {
    	// these array names should match the access_levels defined in App_FormRoles
		$editaccess = array("View", "Edit", "Finalize", "Log", "Print");
		$viewaccess = array("View", "Log", "Print");

		if('Draft' != $status)
        {
            unset($editaccess[array_search('Edit', $editaccess)]);
        	unset($editaccess[array_search('Finalize', $editaccess)]);
        }

		$accessLevel = $this->formAccessObj->access_level;
		return $$accessLevel;
    }

    public function buildAccessObj() {
		if('Team Member' == $this->formAccessObj->description) {
			if('viewaccess' == $this->formAccessObj->access_level) {
				$accessArrayClassName = 'App_Auth_Role_' . str_replace(' ', '', $this->formAccessObj->description) . 'View';
				$accessArrayObj = new $accessArrayClassName();
			} else {
				$accessArrayClassName = 'App_Auth_Role_' . str_replace(' ', '', $this->formAccessObj->description) . 'Edit';
				$accessArrayObj = new $accessArrayClassName();
			}
		} else {
			$accessArrayClassName = 'App_Auth_Role_' . str_replace(' ', '', $this->formAccessObj->description);
			$accessArrayObj = new $accessArrayClassName();
		}
		// horrible place for this to be set into the view
		//$this->view->accessArrayObj = $accessArrayObj;
    	return $accessArrayObj;
    }
}

