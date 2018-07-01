<?php

	class My_Form_SubformHelper {
		
		
		public function addSubformRow($ownerObject, $zendSubForm, $subformConfig, $d)
		{
			$subformIndex = $subformConfig->getSubformName() . '_' . $d['rownumber'];
										
			if(count($subformConfig->getActions()) > 0)
			{
				// if additional data has been passed, give to each row
				foreach($subformConfig->getActions() as $adConf)
				{
					if('function' == $adConf->getType()) {
						// put the results of a function into a formData field for the subform
						$ownerObject->view->formData[$subformConfig->getSubformName().'_'.$d[$subformConfig->getSubtableKey()]][$adConf->getPutInto()] = eval( "return ".$adConf->getCode().";");
						
					} elseif('buildValueList' == $adConf->getType()) {
						// build conditional value list in the subform
						$codeToRun = '$ownerObject->'.$adConf->getcode();
						$multiOptions = eval( "return ".$codeToRun.";");
						$putIntoFieldName = $adConf->getPutInto();
						$zendSubForm->$putIntoFieldName->setMultiOptions($multiOptions);
					}
				}
			}
			// group the subform into an array
			$zendSubForm->setElementsBelongTo($subformIndex);
		}
		
		public function getSubforms(My_Form_AbstractFormController $ownerObject, $databaseForm, $zendForm, $ajax = false, $postData = array()) {
			
			if(count($ownerObject->subFormsArray ) > 0) {
				// loop through the added subform config objects
				foreach ( $ownerObject->subFormsArray as $k => $s ) {
					// ======================================================================
					$headerIndex = $s->getSubformName();
					
					if($ajax)
					{
						// get db records from post
						$tmpData = array();
						
						// update the subform config object with the record count
						$ownerObject->subFormsArray[$k]->setSubrowsCount( $postData[$k]['count'] );
						for($i=1; $i<=$postData[$k]['count']; $i++)
						{
							$tmpData[] = $postData[($k.'_'.$i)];
						}
						
					} else {
						// get db records for this subform
						$tmpData = $databaseForm->getChildRecords ( $s->getSubtableName(), $s->getSubtableParentKey(), $ownerObject->view->document, $s->getSubtableKey(), "Active");
//						print_r($tmpData);
						//die();
						// update the subform config object with the record count
						if(false !== $tmpData)
						{
							//echo "setting count:".count($tmpData)."\n";
							$ownerObject->subFormsArray[$k]->setSubrowsCount( count($tmpData) );
						} else {
							$ownerObject->subFormsArray[$k]->setSubrowsCount( 0 );
						}
					}
					
//					Zend_debug::dump($ownerObject->subFormsArray[$k]->getSubrowsCount());
					// ======================================================================

					// 
					// header form
					// ===========================================================================
                    // build the header form for this form
                    
//                    Zend_debug::dump($s);
                    
                    $subFormHeader = $ownerObject->retrieveSubFormHeader($headerIndex, $s->getFormName(), $s->getMode(), $s->getVersion(), $s->getAddrow(), $s->getValidationOverride());
//                    echo "ASdf: " . $s->getSubformTitle() . "<BR>";die();	
					                    
                    
                    
                    // put the subform data into the main form data array for proper population
                    if(!$ajax) $ownerObject->view->formData[$headerIndex]['count'] = $ownerObject->subFormsArray[$k]->getSubrowsCount();
                    if(!$ajax) $ownerObject->view->formData[$headerIndex]['subformTitle'] = $s->getSubformTitle();
                    
                    // group the subform into an array
                    $subFormHeader->setIsArray(true);
                    $subFormHeader->setElementsBelongTo($headerIndex);						
                    
                    
                    
                    // header actions
                    if(count($s->getActions()) > 0)
                    {
                        // if additional data has been passed, give to each row
                        foreach($s->getActions() as $adConf)
                        {
                            if('mapHeaderVariableToParent' == $adConf->getType()) {
                                // put subform data into into subform
                                if(!$ajax) {
                                	$ownerObject->view->formData[$headerIndex][$adConf->getCode()] = $ownerObject->view->formData[$adConf->getPutInto()];
                                } else {
                                	
                                }
                            }
                        }
                    }
                    
                    
                    // add the subform to the main form						
                    $zendForm->addSubForm($subFormHeader, $headerIndex);
					
                    //	echo "Adding header: ".$s->getSubformName()."\n";
					// ===========================================================================
					// ===========================================================================
				
					
					// if there are any db rows for this subform
					if(1) {
					if($ownerObject->subFormsArray[$k]->getSubrowsCount() > 0 ) {
						
						
						// loop through the rows in the db and add a sub form for each one
						$rowNumber = 1;
						foreach ( $tmpData as $d ) {
							// name of the array that will contain this subform in the parent form
							$subformIndex = $headerIndex . '_' . $rowNumber;
							
							// add the row number to the form
							$d['rownumber'] = $rowNumber++;
//							echo "subformIndex:" . $subformIndex . "<BR>";
//							echo "building row:" . $d['rownumber'] . "<BR>";
							// get the subform
							$zendSubForm = $ownerObject->retrieveSubForm ($headerIndex, $s->getFormName(), $s->getMode(), $s->getVersion());
							
		                    $zendSubForm->setIsArray(true);
							
							// put the subform data into the main form data array for proper population
							// this is true on the 
		                    // header actions
							if(!$ajax) $ownerObject->view->formData[$subformIndex] = $d;
		                    
							// if actions are defined in the subform config,
							// apply changes to subform data
							if(!$ajax && count($s->getActions()) > 0)
		                    {
		                        // if additional data has been passed, give to each row
		                        foreach($s->getActions() as $adConf)
		                        {
		                            if('storeFieldAsArray' == $adConf->getType()) {
		                                // convert db values to array for proper form population
										$codeToRun = '$ownerObject->'.$adConf->getcode();
		                            	$dbValueArray = eval( "return ".$codeToRun.";");
//		                            	Zend_debug::dump($ownerObject->view->formData[$subformIndex][$adConf->getPutInto()]);
		                            	$ownerObject->view->formData[$subformIndex][$adConf->getPutInto()] = $dbValueArray;
//		                            	Zend_debug::dump($ownerObject->view->formData[$subformIndex][$adConf->getPutInto()]);
		                            }
		                        }
		                    }
				            
							
							if(count($s->getActions()) > 0)
							{
								// if additional data has been passed, give to each row
								foreach($s->getActions() as $adConf)
								{
									if('function' == $adConf->getType()) {
										// put the results of a function into a formData field for the subform
//										$ownerObject->view->formData[$headerIndex.'_'.$d[$s->getSubtableKey()]][$adConf->getPutInto()] = eval( "return ".$adConf->getCode().";");
										
									} elseif('buildValueList' == $adConf->getType()) {
										// build conditional value list in the subform
										$codeToRun = '$ownerObject->'.$adConf->getcode();
										$multiOptions = eval( "return ".$codeToRun.";");
										$putIntoFieldName = $adConf->getPutInto();
										$zendSubForm->$putIntoFieldName->setMultiOptions($multiOptions);

									}
								}
							}
		                    
							// user has chosen to override form validation
		                    if(isset($ownerObject->view->formData[$headerIndex]['override']) && 't' == $ownerObject->view->formData[$headerIndex]['override'])
		                    {
		                    	// remove all validation
								foreach($zendSubForm->getElements() as $ename => $element)
								{
									// validators appear to be cleared, but they still fire on validation
									$element->clearValidators();
									
									// we also have to clear the auto entered do not allow empty
									$element->setAllowEmpty(true);
									$element->setRequired(false);
								}
		                    }
							
		                    // group the subform into an array
							$zendSubForm->setElementsBelongTo($subformIndex);

							// add the subform to the main form
							$zendForm->addSubForm($zendSubForm, $subformIndex);
//							echo "Adding row: ".$subformIndex."\n";
						}
					}
					}
				}
	        }
		}

		
		
		function processActions($s, Zend_Controller_Action $ownerObject, &$mainFormData, $subformData) {
			
//			if(count($ownerObject->subFormsArray ) > 0) {
//				// loop through the added subforms
//				foreach ( $ownerObject->subFormsArray as $k => $s ) {
						
						// header actions
						if(count($s->getActions()) > 0)
						{
							// if additional data has been passed, give to each row
							foreach($s->getActions() as $adConf)
							{
								if('mapHeaderVariableToParent' == $adConf->getType()) {
									// put a variable from the subform header into the main form data
									
//									echo "k: $k";
//									echo "put into: ".$adConf->getPutInto().":" . $subformData[$adConf->getCode()] ."\n";
									$mainFormData[$adConf->getPutInto()] = $subformData[$adConf->getCode()];
//									print_r($mainFormData);
//									die();
								}
							}
						}
//				}
//	        }
		}
		
}