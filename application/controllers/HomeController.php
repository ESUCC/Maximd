<?php

class HomeController extends My_Form_AbstractFormController {

    protected $identity;


    public function indexAction()
    {

        $config = Zend_Registry::get ( 'config' );
        $refreshCode = '?refreshCode=' . $config->externals->refresh;

//        $this->view->hideLeftBar = true;

        $this->_redirector = $this->_helper->getHelper('Redirector');
        $this->view->baseUrl = $this->getRequest()->getBaseUrl();

        $sessUser = new Zend_Session_Namespace('user');

        // append action and controller to the browser title bar
        // base code is set in the config/application.ini file.
        //
//        $this->view->headTitle()->setSeparator(' / ');
//        $request = Zend_Controller_Front::getInstance()->getRequest();
//        $this->view->headTitle($request->getControllerName())
//                   ->headTitle($request->getActionName());
                   
    	if('production' == APPLICATION_ENV) {
     		$this->_redirect('https://iep.nebraskacloud.orgu/srs.php?area=home&sub=home');
    	}
//    	if('iepweb03' == APPLICATION_ENV && '1018436' == $sessUser->id_personnel) {
//    		$this->_redirect('/student/forms/student/1366090');
//    	}
//    	if('iepweb03' == APPLICATION_ENV && '1000445' == $sessUser->id_personnel) {
//    		$this->_redirect('/student/forms/student/1280440');
//    	}
// last option
//    	if('xandev' == APPLICATION_ENV || 'iepweb03' == APPLICATION_ENV || 'jesselocal' == APPLICATION_ENV) {
//    		$this->_redirect('/student/forms/student/1198891');
//    	}
//        $this->_redirector->gotoSimple('search', 'student', null,array());
    }

    public function messageCenterAction()
    {
        $sessUser = new Zend_Session_Namespace('user');

        // ------------------------------------------------------------------------------------------
        // messages
        $messageModel = new Model_Table_Message();
        $this->view->results = $messageModel->getWhere("id_user", "$sessUser->id_personnel", 'timestamp_created');

        $this->view->page = $sessUser->message_center_page>0 ? $sessUser->message_center_page : 1;
        if('messages' == $this->getRequest()->getParam('formName') && $this->getRequest()->getParam('page')) {
            $this->view->page = $this->getRequest()->getParam('page');
            $sessUser->message_center_page = $this->view->page;
        }
        $this->view->paginator = Zend_Paginator::factory($this->view->results);
        $this->view->paginator->setCurrentPageNumber($this->view->page);

        // ------------------------------------------------------------------------------------------
        // students
        $studentModel = new Model_Table_MyStudents();
        $this->view->students = $studentModel->getStudentsAboutToDevDelay($sessUser->id_personnel);

        $this->view->page_student = $sessUser->message_center_page_student>0 ? $sessUser->message_center_page_student : 1;
        if('students' == $this->getRequest()->getParam('formName') && $this->getRequest()->getParam('page')) {
            $this->view->page_student = $this->getRequest()->getParam('page');
            $sessUser->message_center_page_student = $this->view->page;
        }
        $this->view->paginator_student = Zend_Paginator::factory($this->view->students);
        $this->view->paginator_student->setCurrentPageNumber($this->view->page_student);


        // ------------------------------------------------------------------------------------------
        // transfers
        $transferRequestsTable = new Model_Table_TransferRequest();
//        if($this->getRequest()->getParam('id_transfer_request')) {
//            if('confirm' == $this->getRequest()->getParam('confirmTransferAction')) {
//                $transferRequestsTable->confirmTransferRequest($this->getRequest()->getParam('id_transfer_request'));
//
//            } elseif('delete' == $this->getRequest()->getParam('confirmTransferAction')) {
//                $transferRequestsTable->deleteTransferRequest($this->getRequest()->getParam('id_transfer_request'));
//            }
//            $this->redirect('student/confirm-transfer');
//        }
        $this->view->myTransferRequests = $transferRequestsTable->getMyTransferRequests(array('initiate'));
        $this->view->myRecentlyChangedTransferRequests = $transferRequestsTable->getMyTransferRequests(array('Cancelled', 'Confirmed'), 20);

    }

    public function messageCenterViewAction()
    {
        $this->forward('message-center-edit', null, null, array('viewOnly' => true, 'id_message' => $this->getRequest()->getParam('id_message')));
    }
    /**
     * url format: /home/message-center-edit/id_personnel/1018569
     */
    public function messageCenterEditAction()
    {
        /*
         * passed from view
         */
        $this->view->viewOnly = $this->getRequest()->getParam('viewOnly')?true:false;

        $id_message = $this->getRequest()->getParam('id_message');
        $messageObj = new Model_Table_Message();
        $postData = $this->getRequest()->getParams();

        if(!$id_message) {
            // redirect to search personnel?
            throw new Exception('Message Id required');
        }
        // get the model
        $messageModel = $messageObj->find($id_message)->current();
        if(is_null($messageModel)) {
            throw new Exception('Message not found');
        }

        /**
         * validate access to this record by this user
         */
//        if($this->usersession->sessIdUser == $id_message) {
//            // user can edit self
//        } elseif(!$messageObj->validateAccess($id_message, $this->usersession->sessIdUser)) {
//            throw new Exception('Access Denied');
//        }

        // get the zend form
        $form = new Form_Message();

        /**
         * convert to view only form
         */
        if($this->view->viewOnly) {
            $this->convertFormToView($form);
            $form->getElement('cancel')->setLabel('Done');
            $form->removeElement('submit');
        }

        // populate the form
        $form->populate($messageModel->toArray());

        // if post - save form
        if ($this->getRequest()->isPost()) {

            // if email changed, update temp field and send email
            if($form->isValid($postData)) {
                // do things that affect the form AND the data saved
                // reset password
                $data = $form->getValues();

                // data only changes
                unset($data['id_message']);

                // save
                $where = $messageObj->getAdapter()->quoteInto('id_message = ?', $id_message);
                $messageObj->update($data, $where);
//                $this->addGlobalMessage('Message record saved successfully.');
            }

            // restore display only values
            // loop through form elements and update if disabled
            foreach($form->getElements() as $n => $e) {
                if('disabled'==$e->getAttrib('disabled') || $e->getAttrib('disabled')){
                    $form->getElement($n)->setValue($messageModel->$n);
                }
            }
        }
        $this->view->form_message = $form;
    }

}
