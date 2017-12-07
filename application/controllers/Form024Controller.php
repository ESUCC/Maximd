<?php

class Form024Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 9;
	protected $startPage = 1;
	
    public function init()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
        
        // apply soria css theme, some theme required for proper display of dijits
        $this->view->headLink()->appendStylesheet('/js/dijit/themes/soria/soria.css');

        // form parameters
        $this->view->pageCount = 2;
        
        parent::setPrimaryKeyName('id_form_024');
        parent::setFormNumber('024');
        parent::setModelName('Model_Form024');
        parent::setFormClass('Form_Form024');
        parent::setFormTitle('Agency Consent Invitation');
        parent::setFormRev('08/08');
    }

    protected function buildSrsForm($document, $page)
    {
		parent::buildSrsForm($document, $page);
		
		// build subforms
		// $this->config set in parent buildSrsForm method
		$subFormBuilder = new App_Form_SubformBuilder($this->config);
		
		// student_data form used to display the student info header on the top of forms
		$zendSubForm = $subFormBuilder->buildSubform("student_data", "student_data_header");
		$this->form->addSubForm($zendSubForm, "student_data");
		
//		if($page == 1) {
//			$this->addSubformSection("team_member", "Form_Form004TeamMember", "Form004TeamMember");	
//			$this->addSubformSection("team_other", "Form_Form004TeamOther", "Form004TeamOther");		
//			$this->addSubformSection("team_district", "Form_Form004TeamDistrict", "Form004TeamDistrict");
//		}
		
		

		// fill the html form with db data
		$this->form->populate($this->view->db_form_data);

		// Assign the form to the view
        $this->view->form = $this->form;
        return $this->view->form;	
    }

// override functions to use new architecture
// uncomment if needed
//    function editAction()
//    {
//        parent::editAction();
//
//        $formName = 'DbTable_iepForm'.$this->formNumber;
//	    $objForm = new $formName();
//        $this->view->consent_form_count = $objForm->countSubforms('iep_form_024_consent', 'id_form_024', 
//            															$this->view->document);
//            
//        // sub-form
//        // means data on this page can link to one of many records in a related table
//        if(2 == $this->view->page)
//        {
//                $this->view->subformnum = isset($request->subformnum) ? $request->subformnum : 1;
//                //echo "subformnum: " . $this->view->subformnum . "<BR>";die();
//
//                // page 2 consent form
//                $objForm024Consent = new DbTable_iepForm024Consent();
//                if(!$this->view->subformData = $objForm024Consent->getForm($this->view->document, 
//                															$this->view->subformnum))
//                {
//                    //
//                    // reorder subforms to be sure there's no issues
//                    //
//                    $objForm024->updateSubformsOrder('iep_form_024_consent', 'id_form_024_consent', 
//                    								'id_form_024', $this->view->document, 'subformnum');
//
//                }
//                if(!$this->view->subformData = $objForm024Consent->getForm($this->view->document, 
//                														$this->view->subformnum))
//                {
//                    // try getting first subform instead
//                    echo "subform " . $this->view->subformnum. " could not be found<br/>";
//                    if(!$this->view->subformData = $objForm024Consent->getForm($this->view->document, 1))
//                    {
//                        echo "subform 1 could not be found<br/>";
//                        // no subforms exists - insert one and load it
//                        $objForm024Consent->inserForm($this->view->document, 1);
//                        $this->view->subformData = $objForm024Consent->getForm($this->view->document, 1);
//                    } 
//
//                    // redirect to subform 1
//                    $this->_redirector->gotoSimple('edit', 'form024', null,
//                                                    array('document' => $this->view->document, 
//                                                    	'page' => $this->view->page, 'subformnum' => 1)
//                                                  );
//                    return;
//
//         		}
//
//         		// get html subform definition
//         		$subform = $this->getSubForm('Form024Consent', 'edit', $this->view->page, $this->view->version);
//                
//        		// populate the sbuform with db data
//         		if(!$subform->isValid($this->view->subformData)) {
//
//                    if(is_array($this->view->errors)) {
//                        $this->view->errors = array_merge($this->view->errors, $subform->getErrors());
//                    } else {
//                        $this->view->errors = $subform->getErrors();
//                    }
//                    if(is_array($this->view->messages)) {
//                        $this->view->messages = array_merge($this->view->messages, $subform->getMessages());
//                    } else {
//                        $this->view->messages = $subform->getMessages();
//                    }
//                    #print_r($this->view->messages);
//                    $this->view->valid = false;
//          		}
//          
//          		$subform->populate($this->view->subformData);
//          		$subform->setName('consent');
//          		parent::addSubForm($subform, 'subform');
//
//		}
//
//	    $this->view->mode = 'edit';
//	    $this->view->objStudent = new My_Classes_Student();
//		if (!$this->view->objStudent->select($this->view->formData['id_student'], $this->view->mode, $asd, "iep_student", "student", false, false, '', 0, '024', $this->view->formData['status'])) 
//		{
//			$errorId = $this->view->objStudent->errorId;
//			$errorMsg = $this->view->objStudent->errorMsg;
//			exit;
//		}
//    }
//
//    function saveAction()
//    {
//        // get incoming data      
//        $request = $this->getRequest();
//        $this->view->page = $request->page;
//        $this->view->version = 1;
//        $this->view->sub = 'form_024';        
//
//        // make sure incoming data is a post
//        if(!$this->getRequest()->isPost()) {
//	        $this->_redirect('/error1');
//            return;
//        }
//
//        // confirm keys exist
//        if (!($request->getParam('document'))) {
//            $this->_redirect('/error2');
//            return;
//        } else {
//            // get keys into vars
//            $document = $request->document;
//            $goto_page = $request->goto_page;
//            $goto_subformnum = $request->goto_subformnum;
//            $page_action = $request->page_action;
//
//            // set key vars in view
//            $this->view->document = $document;
//        }
//
//        // get incoming data
//        $post = $this->getRequest()->getPost();
//
//        // get form
//        $form = $this->getForm('edit', $this->view->page, $this->view->version);
//
//        // instanciate table object
//        $objForm024 = new DbTable_iepForm024();
//        // get db form data
//        $this->view->formData = $objForm024->getForm($this->view->document);
//
//        // recordAction javascript will set action
//        if('cancel' == $post['action'])
//        {          
//            // return to the student list
//            $stuID = $this->view->formData['id_student'];
//            header("Location: https://iep.nebraskacloud.org/srs.php?area=student&sub=student&student=$stuID&option=forms");
//            exit;
//        }
//
//        // get subform
//        if(2 == $this->view->page)
//        {
//            $this->view->subformnum = $request->subformnum;
//            $this->view->consent_form_count = $objForm024->countSubforms('iep_form_024_consent', 'id_form_024', $this->view->document);
//            $subform = $this->getSubForm('Form024Consent', 'edit', $this->view->page, $this->view->version);
//        }
//
//        // validate form    
//        // add validation for questions
//	    if(!$form->isValid($post)) { // || $subform->isValid($post)
//            // if not valid, return to the search page with no results
//	        echo "not valid<br/><pre>";
//            $errors   = $form->getErrors();
//            $messages = $form->getMessages();
//
//            $this->view->objStudent = new My_Classes_Student();
//            $this->view->mode = 'edit';
//            if (!$this->view->objStudent->select($this->view->formData['id_student'], $this->view->mode, $asd, "iep_student", "student", false, false, '', 0, '024', $this->view->formData['status'])) {
//                $errorId = $this->view->objStudent->errorId;
//                $errorMsg = $this->view->objStudent->errorMsg;
//                include_once("error.php");
//                exit;
//            }
//            
//            $this->view->id_student = $this->view->formData['id_student'];
//            $this->view->studentData = $asd;
//            if(!empty($asd['id_student_local']))
//            {
//                $this->view->studentData['studentDisplay'] = $asd['id_student_local'];
//            } elseif(!empty($afd['id_student'])) {
//                $this->view->studentData['studentDisplay'] = $this->view->formData['id_student'];
//            } elseif(!empty($asd['id_student'])) {
//                $this->view->studentData['studentDisplay'] = $asd['id_student'];
//            }
//            
//            $this->view->form = $form;
//
//        } 
//
//        // if valid, continue to save
//        $data = $form->getValues();
//
//        if(count($data) > 0)
//        {
//            if(!$saveResult = $objForm024->saveForm($document, $data)) {
//                $this->_redirect('/error1');
//                return;
//            }
//        }
//
//        if(2 == $this->view->page)
//        {
//            if(!$subform->isValid($post)) echo "not valid<BR>";
//
//            $subdata = $subform->getValues();
//            
//            $objForm024Consent = new DbTable_iepForm024Consent();
//            if('' != $subdata['id_form_024_consent'] && count($subdata) > 0)
//            {
//                if(!$saveResult = $objForm024Consent->saveForm($subdata['id_form_024_consent'], $subdata)) {
//                    $this->_redirect('/error1');
//                    return;
//                }
//            }
//            
//            if('add' == $page_action)
//            {
//                // add a subform and go to it
//                $objForm024Consent->inserForm($this->view->document, $this->view->consent_form_count+1);
//                $this->_redirector->gotoSimple('edit', 'form024', null,
//                                                array('document' => $document, 'page' => $this->view->page, 'subformnum' => $this->view->consent_form_count+1)
//                                              );
//                return;
//            }
//
//            if('delete' == $page_action)
//            {
//                // delete the subform
//                $objForm024Consent->deleteForm($subdata['id_form_024_consent']);
//                $this->view->consent_form_count--;
//                
//                // reorder remaining subforms
//                $objForm024->updateSubformsOrder('iep_form_024_consent', 'id_form_024_consent', 'id_form_024', $this->view->document, 'subformnum');
//                
//                // go to same subform number if it exists
//                if($this->view->consent_form_count < $this->view->subformnum)
//                {
//                    $this->view->subformnum = $this->view->consent_form_count;
//                }
//
//                $this->_redirector->gotoSimple('edit', 'form024', null,
//                                                array('document' => $document, 'page' => $this->view->page, 'subformnum' => $this->view->subformnum)
//                                              );
//                return;
//            }
//            
//            // go to subform select has be chosen
//            if($goto_subformnum != $this->view->subformnum)
//            {
//                // go to anther page
//                $this->_redirector->gotoSimple('edit', 'form024', null,
//                                                array('document' => $document, 'page' => $this->view->page, 'subformnum' => $goto_subformnum)
//                                              );
//                return;
//            }
//            
//            $this->_redirector->gotoSimple('edit', 'form024', null,
//                                            array('document' => $document, 'page' => $this->view->page, 'subformnum' => $subdata['subformnum'])
//                                          );
//            return;
//
//        }
//
//        // go to page has been selected
//        if($goto_page != $this->view->page)
//        {
//            // go to anther page
//            $this->_redirector->gotoSimple('edit', 'form024', null,
//                                            array('document' => $document, 'page' => $goto_page)
//                                          );
//            return;
//        }
//        
//
//        // Redirect to 'view' of 'my-controller' in the current
//        // module, using the params param1 => test and param2 => test2
//
//        $this->_redirector->gotoSimple('edit', 'form024', null,
//                                       array('document' => $document, 'page' => $this->view->page)
//                                      );
//        return;
//        
//        //
//        // render will render the edit.phtml page without running the edit action
//        // so we should popluate everything needed before rendering
//        return $this->render('edit'); 
//    }
}