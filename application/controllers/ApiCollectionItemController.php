<?php
class ApiCollectionItemController extends Zend_Rest_Controller
{
    public function preDispatch()
    {
        $this->usersession = new Zend_Session_Namespace ( 'user' );
        $this->user = $this->usersession->user;
    }


    function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
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
        $studentCollection = new App_Collection_Student();
        $collections = $studentCollection->getMyCollections($this->usersession->sessIdUser);
        if (!is_null($collections)) {
            echo Zend_Json::encode($collections->toArray());
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
    function createAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if(!$this->getRequest()->getParam('name')) {
            echo Zend_Json::encode(array('success' => 0));
            exit;
        }
        $studentCollection = new App_Collection_Student();
        $collectionExists = $studentCollection->collectionNameExists($this->usersession->sessIdUser, $this->getRequest()->getParam('name'));

        if(!$collectionExists) {

            $newCollection = $studentCollection->addCollection($this->usersession->sessIdUser, $this->getRequest()->getParam('name'));
             $this->writevar1($newCollection,'this is the new collection');
            $this->getResponse()->setHttpResponseCode(201);
            $this->_helper->json(array('id'=>$newCollection));

        } elseif($this->getRequest()->getParam('overwrite')) {
            $studentCollection->removeAllCollectionItems($this->usersession->sessIdUser, $this->getRequest()->getParam('name'));
            echo Zend_Json::encode(array('success' => 1, 'data' => array('itemsRemoved', $studentCollection)));
        } else {
            $this->getResponse()->setHttpResponseCode(400);
            $this->_helper->json(array('errors'=>array('message' => 'A collection named "'.$this->getRequest()->getParam('name').'" already exists. Select overwrite to clear the collection.')));
//            echo Zend_Json::encode(array('success' => 0, 'errorMessage' => 'A collection named "'.$this->getRequest()->getParam('name').'" already exists. Select overwrite to clear the collection.'));
        }
        exit;
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
