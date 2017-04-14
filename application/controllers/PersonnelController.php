<?php
class PersonnelController extends My_Form_AbstractFormController
{

    public function init(){
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }

    public function stylescopeAction()
    {
        // override layout css and remove left panel
        $this->view->headLink()->appendStylesheet('/css/layout_wide.css', 'screen');

        $sessUser = new Zend_Session_Namespace ( 'user' );
        $this->view->id_personnel = $sessUser->sessIdUser;

    }
    public function searchAction()
    {

    }
    public function teamMemberTimeAction()
    {
        if(!$this->getRequest()->getParam('id')) {
            throw new Exception('Id required.');
        } else {
            $id_personnel = $this->getRequest()->getParam('id');
        }
        $this->limitToAdminAccess();
        $this->view->hideLeftBar = true;

        $personnelObj = new Model_Table_PersonnelTable();
        $teamMemberObj = new Model_Table_StudentTeamMember();
        $this->view->students = $teamMemberObj->studentsWithPersonnelAsTeamMember($id_personnel);
        $this->view->personnel = $personnelObj->find($id_personnel)->current();

    }

    /**
     * url format: /personnel/edit/id_personnel/1018569
     */
    public function editAction()
    {
        $this->view->hideLeftBar = true;
        /*
         * passed from view
         */
        $this->view->viewOnly = $this->getRequest()->getParam('viewOnly')?true:false;

        $id_personnel = $this->getRequest()->getParam('id_personnel');

        $personnelObj = new Model_Table_PersonnelTable();
        $privilegesObj = new Model_Table_IepPrivileges();

        $this->view->privileges = $privilegesObj->getPrivileges($id_personnel);
        foreach($this->view->privileges as $key => $priv) {
            $this->view->privileges[$key]['access'] = $personnelObj->validatePrivAccess(
                $priv['id_county'],
                $priv['id_district'],
                $priv['id_school'],
                $priv['class'],
                $this->usersession->sessIdUser,
                $id_personnel
            );
        }

        // @todo confirm user has access to edit this personnel
        // @todo confirm we have id_county, id_district, id_personnel

        $postData = $this->getRequest()->getParams();

        if(!$id_personnel) {
            // redirect to search personnel?
            throw new Exception('Personnel Id required');
        }
        // get the model
        $personnelModel = $personnelObj->find($id_personnel)->current();
        if(is_null($personnelModel)) {
            throw new Exception('Personnel not found');
        }

        /**
         * validate access to this record by this user
         */
        if($this->usersession->sessIdUser == $id_personnel) {
            // user can edit self
        } elseif(!$personnelObj->validateAccess($id_personnel, $this->usersession->sessIdUser)) {
            throw new Exception('Access Denied');
        }

        // get the zend form
        $form = new Form_Personnel();

        /**
         * convert to view only form
         */
        if($this->view->viewOnly) {
            $this->convertFormToView($form);
            $form->getElement('cancel')->setLabel('Done');
            $form->getElement('user_name')->removeDecorator('description');
            $form->getElement('address_zip')->removeDecorator('description');
            $form->getElement('phone_work')->removeDecorator('description');
            $form->getElement('email_address')->removeDecorator('description');
            $form->removeElement('online_access');
            $form->removeElement('temp_password');
            $form->removeElement('id_personnel');
            $form->removeElement('update_email_address');
            $form->removeElement('date_expiration');
        }
        // populate the form
        $form->populate($personnelModel->toArray());

        // if post - save form
        if ($this->getRequest()->isPost()) {

            // don't allow update to match existing email
//            if($postData['update_email_address'] == $personnelModel->email_address) {
//                $postData['update_email_address'] = null;
//            }

            // if email changed, update temp field and send email
            if($form->isValid($postData)) {
            	
            	$data = $form->getValues();
                // do things that affect the form AND the data saved
                // reset password
                if ('Reset' == $form->getElement('online_access')->getValue()) {
                	$form->getElement('temp_password')->setValue(App_Password::generatePassword());
                	$form->getElement('online_access')->setValue('');
                	$data['password'] = $form->getElement('temp_password')->getValue();
                	$form->getElement('date_last_pw_change')->setValue(date('Y-m-d'));
                	$data['date_last_pw_change'] = $form->getElement('date_last_pw_change')->getValue();
                }

                // data only changes
                unset($data['id_personnel']);
                unset($data['online_access']);//no longer saved - used on page though and needs to be submitted

                // submitted update address is valid and is different from database
//                if(''!=$postData['update_email_address'] && $postData['update_email_address']!=$personnelModel->update_email_address) {
//                    $personnelModel->update_email_address = $form->getElement('update_email_address')->getValue();
//                    $personnelModel->update_email_hash = $personnelObj->getUniqueHash('Model_Table_PersonnelTable', 'update_email_hash');
//                    if($personnelModel->save()) {
//                        App_Email_PasswordHelper::initEmail();
//                        App_Email_PasswordHelper::sendEmailChangeConfirm(null,
//                            $personnelModel->update_email_address,
//                            $personnelModel->name_first . ' ' . $personnelModel->name_last,
//                            $personnelModel->update_email_hash);
//                        $this->view->message = "A confirmation email has been sent to: " . $personnelModel->update_email_address;
//
//                    }
//                }

                // save
                $where = $personnelObj->getAdapter()->quoteInto('id_personnel = ?', $id_personnel);
//                if(''==$data['update_email_address']) {
//                    $data['update_email_hash'] = '';
//                }
//                unset($data['update_email_address']);
                unset($data['temp_password']);
                unset($data['update_email_hash']);
                $data['online_access'] = 'Enabled';
                $personnelObj->update($data, $where);
                $this->addGlobalMessage('Personnel record saved successfully.');
            }


            // restore display only values
            // loop through form elements and update if disabled
            foreach($form->getElements() as $n => $e) {
                if('disabled'==$e->getAttrib('disabled') || $e->getAttrib('disabled')){
                    $form->getElement($n)->setValue($personnelModel->$n);
                }
            }
        }
        $this->view->form_personnel = $form;


        /**
         * student charts
         */
        $sessUser = new Zend_Session_Namespace ( 'user' );
        $privCheck = new My_Classes_privCheck($sessUser->user->privs);
        $admin = 1==$privCheck->getMinPriv()?true:false;
        if (!$this->view->viewOnly && ($sessUser->sessIdUser == $id_personnel || $admin)) {
            $studentChartsTable = new Model_Table_StudentChartTemplate();
            $this->view->templates = $studentChartsTable->getMyCharts($id_personnel);
        }


    }

    public function addGlobalMessage($msg) {
        $messageSpace = new Zend_Session_Namespace('message');
        if(isset($messageSpace->globalMessage)) {
            $messageSpace->globalMessage .= $msg . "<BR />";
        } else {
            $messageSpace->globalMessage = $msg . "<BR />";
        }
    }
    
    public function hasActivePrivilegesAction() {
    	// ajax requests for this page should not include site layout
    	if ($this->getRequest()->isXmlHttpRequest()) {
    		$this->_helper->layout()->disableLayout();
    		$this->_helper->viewRenderer->setNoRender(true);
    	}
    	
    	$id_personnel = $this->getRequest()->getParam('id_personnel');
    	
    	$personnelObj = new Model_Table_PersonnelTable();
    	$privilegesObj = new Model_Table_IepPrivileges();
    	
    	$privileges = $privilegesObj->getPrivileges($id_personnel);
    	$response = false;
    	
    	foreach ($privileges AS $privilege) {
    		if ($privilege['status'] == 'Active') {
    			$response = true;
    		}
    	}
    	
    	echo Zend_Json::encode(
    			array(
    					'response' => $response,
    			)
    	);
    }
    
    public function deletePrivilegeAction()
    {
        // ajax requests for this page should not include site layout
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
        }

        $privilegeId = $this->getRequest()->getParam('id_privileges');
        $privilegeObj = new Model_Table_PrivilegeTable();
        $privilege = $privilegeObj->find($privilegeId)->current();
        if (is_null($privilege)) {
            throw new Exception('Privilege not found');
        }

        /**
         * make sure current user has access to remove the privilege
         * validate access
         */
        $personnelObj = new Model_Table_PersonnelTable();
        if(!$personnelObj->validateAccess($privilege['id_personnel'], $this->usersession->sessIdUser)) {
            throw new Exception('Access Denied');
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();
            if (isset($postData['submit'])) {
                $privilege->status = 'Removed';
                $result = $privilege->save();
                if ($this->getRequest()->isXmlHttpRequest()) {
                    echo Zend_Json::encode(
                        array(
                            'deleted' => true,
                            'result' => 'Privilege record deleted successfully.'
                        )
                    );
                } else {
                    $this->addGlobalMessage('Privilege record deleted successfully.');
                }
                die;
            }
        }

        // if ajax, render just the form
        if ($this->getRequest()->isXmlHttpRequest()) {
            echo Zend_Json::encode(array('result' => $this->view->form->render()));
        }
    }
    public function updatePrivilegeAction()
    {
        // ajax requests for this page should not include site layout
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
        }

        $privilegeId = $this->getRequest()->getParam('id_privileges');
        $privilegeObj = new Model_Table_PrivilegeTable();
        $privilege = $privilegeObj->find($privilegeId)->current();
        if (is_null($privilege)) {
            throw new Exception('Privilege not found');
        }

        /**
         * make sure current user has access to remove the privilege
         * validate access
         */
        $personnelObj = new Model_Table_PersonnelTable();
        if(!$personnelObj->validateAccess($privilege['id_personnel'], $this->usersession->sessIdUser)) {
            throw new Exception('Access Denied');
        }
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();
            switch($this->getRequest()->getParam('status')) {
                case 'Active':
                    $newStatus = 'Active';
                    break;
                case 'Inactive':
                    $newStatus = 'Inactive';
                    break;
                default:
                    $newStatus = 'Inactive';
                    break;
            }

            if (isset($postData['submit'])) {
                $privilege->status = $newStatus;
                $result = $privilege->save();
                if ($this->getRequest()->isXmlHttpRequest()) {
                    echo Zend_Json::encode(
                        array(
                            'success' => 1,
                            'status' => $newStatus,
                            'result' => 'Privilege record updated successfully.'
                        )
                    );
                } else {
                    $this->addGlobalMessage('Privilege record deleted successfully.');
                }
                die;
            }
        }

        // if ajax, render just the form
        if ($this->getRequest()->isXmlHttpRequest()) {
            echo Zend_Json::encode(array('result' => $this->view->form->render()));
        }
    }
    public function deleteStudentChartTemplateAction() {
        // ajax requests for this page should not include site layout
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
        }

        $studentChartTemplateId = $this->getRequest()->getParam('id_student_chart_template');
        $studentChartTemplateTable = new Model_Table_StudentChartTemplate();
        $studentChartTemplate = $studentChartTemplateTable->find($studentChartTemplateId)->current();
        if (is_null($studentChartTemplate)) {
            throw new Exception('Student Chart Template not found');
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();
            if (isset($postData['submit'])) {
                $studentChartTemplate->delete();
                if ($this->getRequest()->isXmlHttpRequest()) {
                    echo Zend_Json::encode(
                        array(
                            'deleted' => true,
                            'result' => 'Student Chart Template record deleted successfully.'
                        )
                    );
                } else {
                    $this->addGlobalMessage('Student Chart Template record deleted successfully.');
//                    $this->_redirector->gotoSimple('Privileges', 'student', null, array('student' => $id_student));
                }
                die;
            }
        }

//        $this->view->form = $form;

        // if ajax, render just the form
        if ($this->getRequest()->isXmlHttpRequest()) {
            echo Zend_Json::encode(array('result' => $this->view->form->render()));
        }

    }


}
