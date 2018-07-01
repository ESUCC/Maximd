<?php

class App_Form_FormHelper {
		
		//ceiroa 122309 - the only two references to this methods are in this same file,
		// in the "saveSubformRows" and "persistData" methods.
		function updateForm($formTableName, $tempForm, $formValues)
		{
			$formTable = new $formTableName();
			$id = $formValues[$formTable->get_primary()];
			$cleanData = $this->cleanForm($formValues, $tempForm, $formTable->get_primary());
			$where = $formTable->getAdapter()
					 ->quoteInto($formTable->get_primary().' = ?', $id);

			if(count($cleanData) > 0) $formTable->update($cleanData,$where);
			
		}
		
		//ceiroa 122309 - there are references to this method in AjaxController.php and abstractFormController.php
		function insertForm($modelName, $primaryKeyName, $parentKey, $idStudent, $currentRowCount = 0)
		{			
			$databaseSubformObj = new $modelName();
	        $newKey = $databaseSubformObj->inserForm(
	        	array(
	        		$primaryKeyName => $parentKey,
	                'id_author'=>'000',
	                'id_author_last_mod'=>'000',
	                'id_student'=>$idStudent
	            ));
//	        echo "newkey: $newKey<BR>";
	        $d = $databaseSubformObj->getForm($newKey);
//	        Zend_debug::dump($d);
	        
	        // add the row number to the form
	        $d['rownumber'] = $currentRowCount + 1;
	        return $d;
		}
		
		
		//ceiroa 122209 - this seems to be an auxiliary method that could be used
		//by many classes. If so we should extract it to a different class.
		public function convertArrayToText($form, $headerRowName, $rowPrefix, $fieldName) 
		{		
			// get the count of rows from the header form
			if($form->getSubForm($headerRowName) && $form->getSubForm($headerRowName)->getElement('count')->getValue())
			{ 
				$count = $form->getSubForm($headerRowName)->getElement('count')->getValue();
			} else {
				$count = 1;
			}
			$rowsDeleted = false;
			// save the rows
			for($rowNum = 1; $rowNum <= $count; $rowNum++)
			{
				$tempForm = $form->getSubForm($rowPrefix.$rowNum);
				if(false === $tempForm || null == $tempForm) continue;
				
				// update the form value with a return delimited string instead of an array
				$dbVal = $tempForm->getValue($fieldName);
	//			Zend_debug::dump($dbVal);
				if(is_array($dbVal)) $tempForm->getElement($fieldName)->setValue(implode("\n", $tempForm->getValue($fieldName)));
			}
			return $rowsDeleted;		
		} 
		
		//ceiroa 122209 - could we extract this method to another class?
		//There is not reference to it anywhere in the project
		public function cleanSubforms(&$service, $headerRowName, $modelName, $rowPrefix)
		{
			// get the count of rows from the header form 
			$count = $this->getSubForm($headerRowName)->getElement('count')->getValue();
			$rowsDeleted = false;
			// save the rows
			for($rowNum = 1; $rowNum <= $count; $rowNum++)
			{
				$service->form->removeSubForm($rowPrefix.$rowNum);
			}
			
			$service->form->removeSubForm($headerRowName);	
		} 
		
		//ceiroa 122209 - The only reference to it is in the 'jsonupdateiepAction' 
		//in abstractFormController.php
		public function persistData($formNum, $form, $accessMode, $page, $version, $checkout, $subformsArr)
		{
			$tempForm = clone($form);
			$formValues = $form->clearSubForms()->getValues();
			$subformsToReturn = array();
						
			if(isset($subformsArr) && count($subformsArr) > 0) {
				print_r($subformsArr);
				foreach($subformsArr as $subFormConfig) {
					// override
					if(isset($subFormConfig['override'])) 
					{
						$formValues[$subFormConfig['override']] = $tempForm->getSubForm($subFormConfig['subformIndex'])->getElement('override')->getValue();
					}
					
					// convert fields that are stored as arrays
					if(isset($subFormConfig['storeasarray']))
					{
						foreach($subFormConfig['storeasarray'] as $fieldName)
						{
							$this->convertArrayToText($tempForm, $subFormConfig['subformIndex'], $subFormConfig['subformIndex'].'_', $fieldName);
						}
					}
					
					// save and report on rows that must be rebuilt
					if($this->saveSubformRows($tempForm, $subFormConfig['subformIndex'], $subFormConfig['model'], $subFormConfig['subformIndex'].'_'))
					{	// if any subform has had rows removed, add it's name to the returned array
						array_unshift($subformsToReturn, array('subformName'=>$subFormConfig['subformIndex']));
					}
				}
			}
			// update the checkout time
			$usersession = new Zend_Session_Namespace('user');
			
			if($checkout)
			{
				// session also gets updated when the page is first edited by a user
				// this is called in App_Service_AbstractFormService
				// and ultimately calls a function in DbTable_abstractIepForm
				//$this->table->checkout($id, $this->usersession->sessIdUser);
				$formValues['zend_checkout_time'] = My_Helper_Date::date_at_timezone("r", "America/Chicago", strtotime('now+20 minutes'));
				$formValues['zend_checkout_user'] = $usersession->sessIdUser;
			}
//			print_r($formValues);
			// save the main form
			$this->updateForm('Form'.$formNum, $tempForm, $formValues);
			
			return $subformsToReturn;
		}
		
		//ceiroa 122209 - The only reference to it is in the 'updateForm' method in this same file
		public function cleanForm($passedData, Zend_form $form, $pkName)
		{
			$saveData = $passedData;
	        foreach($form->getElements() as $n => $e)
	        {
	            if('Zend_Dojo_Form_Element_DateTextBox' == $e->getType() && 
	            	  '' == $saveData[$e->getName()]
	            	)
	            {
	                //
	                // '' is not a valid date, change to null here
	                //
	//                echo "date box clean: ".$e->getName() ."\n";
	                $saveData[$e->getName()] = null;
	                                
	            } elseif('Zend_Dojo_Form_Element_Editor' == $e->getType() 
	            	//&& isset($this->view->filteredDataMainForm[$e->getName()]['Editor'])
	            	)
	            {
	                // if editor data is stored in an array
	                // move it to the main form data
	//            	$saveData[$e->getName()] = $saveData[$e->getName()]['Editor'];
	                
	            } elseif('Zend_Form_Element_Checkbox' == $e->getType() 
	            	//&& isset($this->view->filteredDataMainForm[$e->getName()]['Editor'])
	            	)
	            {
	                // if editor data is stored in an array
	                // move it to the main form data
	//            	$saveData[$e->getName()] = $saveData[$e->getName()]['Editor'];
	                
	            }
	            
	            if($e->getAttrib('ignore')) {
	                // don't save ignored forms
	//                echo "ignoring: ".$e->getName()."\n";
	                unset($saveData[$e->getName()]);
	
	            } elseif($e->getName() == $pkName) {
	                // unset the primary key
	                unset($saveData[$pkName]);
	            }
	            
	            if(isset($saveData[$e->getName()]) && $saveData[$e->getName()] == '') $saveData[$e->getName()] = null;
	        }
	        return $saveData;
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
                    if($label = $form->getSubform($keyName)->getElement($fieldName)->getLabel())
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
                    if($label = $form->getElement($keyName)->getLabel())
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

    
	public function createAjaxData($form, $pkName, $returningData = null, $page = 1, $validationArray, $rowsToRebuild=null)
	{
		
//		if(null == $validationArray) $validationArray = isset($this->validationArr)?$this->validationArr:array();
		if(null == $returningData) $returningData = isset($form)?$form->getValues():array();

		$objArr = array(
        	'0' => array(	$pkName => $returningData[$pkName])
        );
        
        // add validation errors to the returned object
        $objArr[0]['validationArr'] = $validationArray;
        $objArr[0]['zend_checkout_time'] = $returningData['zend_checkout_time'];
        
        // insert the page into the returned data
        $objArr[0]['page'] = $page;
        $objArr[0]['pageValidationList'] = $returningData['pageValidationList'];
        
        // add any subforms that have had rows removed
		$this->addHtmlReturnedRows($form, $objArr, $rowsToRebuild);

		return new Zend_Dojo_Data($pkName, $objArr, 'id');
	}
	function addHtmlReturnedRows($form, &$objArr, $rowsToRebuild)
    {
        
        // loop through subforms that have had rows removed
        foreach($rowsToRebuild as $subFormRow)
        {
        		$subFormName = $subFormRow['subformName'];
            $objArr[0]['subform_options'][$subFormName]['rows_removed'] = true;
            $objArr[0]['subform_options'][$subFormName]['new_html_header'] = $form->getSubform($subFormName)->render($form->getView());
            for($i=1; $i <= $form->getSubform($subFormName)->getElement('count')->getValue(); $i++)
            {
                $objArr[0]['subform_options'][$subFormName]['new_html'][$i] = $form->getSubform($subFormName.'_'.$i)->render($form->getView());
            }
        }
    }

	public function getModelFromSubformHeaderIndex($model, $index)
	{
		if(isset($model->subformIndexToModel[$index])) return $model->subformIndexToModel[$index];
		return false; 
	} 

	public function createAjaxAddRowData($form, $pkName, $returningData = null, $page = 1, $validationArray = null, $rowsToRebuild=null, $newRowNum=1)
	{
		
		if(null == $validationArray) $validationArray = isset($this->validationArr)?$this->validationArr:array();
		if(null == $returningData) $returningData = isset($form)?$form->getValues():array();

        $objArr = array(
        	'0' => array(	$pkName => $returningData[$pkName])
        );
        
        // add validation errors to the returned object
        $objArr[0]['validationArr'] = $validationArray;
//echo $rowsToRebuild[0].'_'.$newRowNum . '---' . $page; 
//Zend_debug::dump($form);
//die();
        // insert the page into the returned data
        $objArr[0]['page'] = $page;
        $objArr[0]['new_html'] = $form->getSubform($rowsToRebuild[0].'_'.$newRowNum)->render($form->getView());
        $objArr[0]['countSubrows'] = $newRowNum;
        return new Zend_Dojo_Data($pkName, $objArr, 'id');
	}

		public function persistDataNew($formNum, $form, $accessMode, $page, $version, $checkout, $formStructure)
		{
			$tempForm = clone($form);
			$formValues = $form->clearSubForms()->getValues();
			$subformsToReturn = array();

			// save subforms
			if(isset($formStructure['subforms'])) {
					$this->processSubformsArray($formStructure, $tempForm, &$formValues, &$subformsToReturn );
//				foreach($formStructure['subforms'] as $subform) {
//					// override - place a value from the header into the main form
//					if(isset($subform['override'])) 
//					{
//						$formValues[$subform['override']] = $tempForm->getSubForm($subform['name'])->getElement('override')->getValue();
//					}
//					
//					// convert fields that are stored as arrays
//					if(isset($subform['storeasarray']))
//					{
//						foreach($subform['storeasarray'] as $fieldName)
//						{
//							$this->convertArrayToText($tempForm, $subform['name'], $subform['name'].'_', $fieldName);
//						}
//					}
//					
//					// save and report on rows that must be rebuilt
//					if($this->saveSubformRows($tempForm, $subform['name'], $subform['model'], $subform['name'].'_'))
//					{	// if any subform has had rows removed, add it's name to the returned array
//						array_unshift($subformsToReturn, array('subformName'=>$subform['name']));
//					}
//				}
			}
			// update the checkout time
			$usersession = new Zend_Session_Namespace('user');
			
			if($checkout)
			{
				// session also gets updated when the page is first edited by a user
				// this is called in App_Service_AbstractFormService
				// and ultimately calls a function in DbTable_abstractIepForm
				//$this->table->checkout($id, $this->usersession->sessIdUser);
				$formValues['zend_checkout_time'] = My_Helper_Date::date_at_timezone("r", "America/Chicago", strtotime('now+20 minutes'));
				$formValues['zend_checkout_user'] = $usersession->sessIdUser;
			}
//			print_r($formValues);
			// save the main form
			$this->updateForm('Form'.$formNum, $tempForm, $formValues);
			
			return $subformsToReturn;
		}
		
		public function saveSubformRows($form, $headerRowName, $modelName, $rowPrefix) 
		{		
			// get the count of rows from the header form
//			echo "hrn:$headerRowName\n";
//			echo $form->getName() . "\n";
			$count = $form->getSubForm($headerRowName)->getElement('count')->getValue();

			$rowsDeleted = false;
			// save the rows
			for($rowNum = 1; $rowNum <= $count; $rowNum++)
			{
				$tempForm = $form->getSubForm($rowPrefix.$rowNum);
				if(false === $tempForm) continue;
				$subformValuesArr = $tempForm->getValues();
				
				if(count($subformValuesArr) > 0)
				{
					$formValues = array_shift($subformValuesArr);
	            if(isset($formValues['remove_row']) && $formValues['remove_row']) {//'t' == 
	            	$rowsDeleted = true;
	                $formValues['status'] = 'Deleted';
	            }
				} else {
					$formValues = array();
				}
				$this->updateForm($modelName, $tempForm, $formValues);
				
			}
			return $rowsDeleted;			
		} 
		public function saveSubformRowsNew($subform, $form, $headerRowName, $modelName, $rowPrefix) 
		{		
			// get the count of rows from the header form
			echo "hrn:$headerRowName\n";
			echo $form->getName() . "\n";
			$count = $form->getSubForm($headerRowName)->getElement('count')->getValue();

			$rowsDeleted = false;
			// save the rows
			for($rowNum = 1; $rowNum <= $count; $rowNum++)
			{
				$tempForm = $form->getSubForm($rowPrefix.$rowNum);
				if(false === $tempForm) continue;
				$subformValuesArr = $tempForm->getValues();
				
				if(count($subformValuesArr) > 0)
				{
					$formValues = array_shift($subformValuesArr);
	            if(isset($formValues['remove_row']) && $formValues['remove_row']) {//'t' == 
	            	$rowsDeleted = true;
	                $formValues['status'] = 'Deleted';
	            }
				} else {
					$formValues = array();
				}
				$this->updateForm($modelName, $tempForm, $formValues);
				
				if(isset($subform['subforms']))
				{
					$this->processSubformsArray($subform, $tempForm, &$formValues, &$subformsToReturn );
				}
				
			}
			return $rowsDeleted;			
		} 
		public function processSubformsArray($formStructure, $tempForm, &$formValues, &$subformsToReturn )
		{	
			foreach($formStructure['subforms'] as $subform) {

//				echo "processing form: " .$tempForm->getName() . " " . $subform['name'] . "\n";
//				foreach ($tempForm->getSubforms() as $sf) {
//					print_r($sf->getName());
//					echo "\n";
//				}
				
				// override - place a value from the header into the main form
				if(isset($subform['override'])) 
				{
					$formValues[$subform['override']] = $tempForm->getSubForm($subform['name'])->getElement('override')->getValue();
				}
				
				// convert fields that are stored as arrays
				if(isset($subform['storeasarray']))
				{
					foreach($subform['storeasarray'] as $fieldName)
					{
						$this->convertArrayToText($tempForm, $subform['name'], $subform['name'].'_', $fieldName);
					}
				}
				
				//$this->saveSubformRowsNew($subform, $tempForm, $subform['name'], $subform['model'], $subform['name'].'_')
				$count = $tempForm->getSubForm($subform['name'])->getElement('count')->getValue();
				$rowsDeleted = false;

				// save the rows
				$rowPrefix = $subform['name'].'_';
				
				for($rowNum = 1; $rowNum <= $count; $rowNum++)
				{
					$tempsubForm = $tempForm->getSubForm($rowPrefix.$rowNum);
					if(false === $tempsubForm) continue;
					$subformValuesArr = $tempsubForm->getValues();
					
					if(count($subformValuesArr) > 0)
					{
						$subformValues = array_shift($subformValuesArr);
		            if(isset($subformValues['remove_row']) && $subformValues['remove_row']) {//'t' == 
		            	$rowsDeleted = true;
		                $subformValues['status'] = 'Deleted';
		            }
					} else {
						$subformValues = array();
					}
					$this->updateForm($subform['model'], $tempsubForm, $subformValues);
					
					
					// process additional subforms 
					if(isset($subform['subforms']))
					{
//						$this->processSubformsArray($subform, $tempsubForm, &$formValues, &$subformsToReturn );
					}
					
				}
				
				// save and report on rows that must be rebuilt
				if($rowsDeleted)
				{	// if any subform has had rows removed, add it's name to the returned array
					array_unshift($subformsToReturn, array('subformName'=>$subform['name']));
				}
				
			}			
		}		
}