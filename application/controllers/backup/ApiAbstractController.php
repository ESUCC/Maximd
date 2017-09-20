<?php
class ApiAbstractController extends Zend_Rest_Controller
{
    public $collectionModel = '';
    public $collectionKey = '';

    public function preDispatch()
    {
        $this->usersession = new Zend_Session_Namespace ( 'user' );
        $this->user = $this->usersession->user;

 

    }

    function putAction() {

    }

    function headAction() {

    }

    function getAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $additionalFields = array(
            'name_case_mgr' => 'name_case_mgr',
            'id_case_mgr' => 'id_case_mgr',
            'id_case_mgr' => 'id_case_mgr',
            'team_member_names' => 'team_member_names',
        );

        /**
         * collection of students
         */
        $model = new $this->collectionModel();
        $resultRecords = $model->fetchAll($model->select()->where($this->collectionKey. '= ?', $this->getRequest()->getParam($this->collectionKey)));
        if (!is_null($resultRecords)) {
            echo Zend_Json::encode($resultRecords->toArray());
        } else
            echo Zend_Json::encode(array('success' => '0'));
        exit;

    }

    function deleteAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if(!$this->getRequest()->getParam('id')) {
            echo Zend_Json::encode(array('success' => 0));
            exit;
        }
        $studentCollection = new App_Collection_Student();
        $result = $studentCollection->removeCollectionById($this->usersession->sessIdUser, $this->getRequest()->getParam('id'));
        if($result) {
            echo Zend_Json::encode(array('success' => 1, 'data' => array('result', $result)));
        } else {
            echo Zend_Json::encode(array('success' => 0, 'errorMessage' => 'An error occured while trying to remove this collection.'));
        }
        exit;
    }

    function indexAction() {
    }

    function postAction() {

    }


//    function getMyCollectionsAction() {
//        $this->_helper->layout()->disableLayout();
//        $this->_helper->viewRenderer->setNoRender(true);
//
//        $additionalFields = array(
//            'name_case_mgr' => 'name_case_mgr',
//            'id_case_mgr' => 'id_case_mgr',
//            'id_case_mgr' => 'id_case_mgr',
//            'team_member_names' => 'team_member_names',
//        );
//
//        /**
//         * collection of students
//         */
//        $studentCollection = new App_Collection_Student();
//        $collections = $studentCollection->getMyCollections($this->usersession->sessIdUser);
//        if (!is_null($collections)) {
//            echo Zend_Json::encode($collections->toArray());
//        } else
//            echo Zend_Json::encode(array('success' => '0'));
//        exit;
//    }

}
