<?php
class EditorHistoryController extends App_Zend_Controller_Action_Abstract
{

    public function preDispatch() {

        // load jquery
        parent::preDispatch();

        // require user to be logged in
        if (! App_Helper_Session::siteAccessGranted ()) {
            if ('production' == APPLICATION_ENV) {
                // try to get the token from iep and relogin
                return $this->_redirect ( 'https://iep.unl.edu/srs.php?area=personnel&sub=gettoken&destination=' . str_replace ( '/', '-', $_SERVER ['REQUEST_URI'] ) );
            } else {
                // redirect home
                return $this->redirectWithMessage ( '/', "You do not have site access granted." );
            }
        }

        $this->view->jQuery()->enable();

    }


    public function indexAction()
    {
        // hide left bar
        $this->view->hideLeftBar = true;

        $request = $this->getRequest();
        $eLogObj = new Model_Table_EditorSaveLog();

        $fieldName = $request->getParam('field');
        if(substr_count($fieldName, '[')) {
            $fieldName = str_replace(array('[', ']'), array('-', ''), $fieldName);
        }

        $this->view->res = $eLogObj->getHistory($request->getParam('formnum'), $request->getParam('id'), $fieldName);
    }
    public function displayAction()
    {
        // hide left bar
        $this->view->hideLeftBar = true;
        $this->view->jqueryLayout= true;

        $request = $this->getRequest();
        $eLogObj = new Model_Table_EditorSaveLog();

        $fieldName = $request->getParam('field');
     //   writevar($fieldName,'this is hte fieldname');
        if(substr_count($fieldName, '[')) {
            $fieldName = str_replace(array('[', ']'), array('-', ''), $fieldName);
        }

        $this->view->res = $eLogObj->getHistory($request->getParam('formnum'), $request->getParam('id'), $fieldName);
    }

    public function saveToEditorHistoryAction()
    {
        $returnData = $this->getRequest()->getParam('data');

        // save to editor history
        $editorHistorySuccess = false;
        try {
            $sessUser = new Zend_Session_Namespace ( 'user' );

            require_once APPLICATION_PATH.'/../library/App/Classes/Browser.php';
            $browser = new Browser();

            // save the editor - $editor->getValue ()
            // insert into editor_save_log
            $editorLogObj = new Model_Table_EditorSaveLog();
            $data = array(
                'form_number' => $this->getRequest()->getParam('form_number'),
                'id_form' => $this->getRequest()->getParam('id_form'),
                'field_name' => $this->getRequest()->getParam ( 'id_editor' ),
                'field_value' => $returnData,

                'computer_platform' => $browser->getPlatform(),
                'browser_type' => $browser->getBrowser(),
                'browser_version' => $browser->getVersion(),

                'id_user' => $sessUser->sessIdUser,
            );
            if(false!=$editorLogObj->insert($data)) {
                $editorHistorySuccess = true;
            }
        } catch (Exception $e) {
            // failed to write to editor history
        }

        echo Zend_Json::encode(array('response' => $returnData, 'editorHistorySuccess' => $editorHistorySuccess));
        exit;
    }

}
