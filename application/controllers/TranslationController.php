<?php
class TranslationController extends Zend_Controller_Action {
    
    private $_translationIndex = array('es' => 0, 'en' => '1');
    
    public function preDispatch() {
        
        if (! App_Helper_Session::siteAccessGranted ()) {
            if ('production' == APPLICATION_ENV) {
                // try to get the token from iep and relogin
                return $this->_redirect ( 'https://iep.esucc.org/srs.php?area=personnel&sub=gettoken&destination=' . str_replace ( '/', '-', $_SERVER ['REQUEST_URI'] ) );
            } else {
                // redirect home
                return $this->redirectWithMessage ( '/', "You do not have site access granted." );
            }
        }
        
        // restore user from session
        $this->usersession = new Zend_Session_Namespace ( 'user' );
        $this->user = $this->usersession->user;
        
        $this->acl = new App_Acl ();
        
        if (! $this->acl->isAllowed ( $this->user->role, App_Resources::TRANSLATION_SECTION )) {
            $this->_redirect ( '/home' );
        }
        
        $this->view->hideLeftBar = true;
        $this->view->headLink()->appendStylesheet('/css/translation.css');
        $this->view->action = $this->getRequest()->getActionName();
    }
    
    public function indexAction() {
        $toTranslate = new Model_Table_ToTranslate();
        $this->view->pendingTranslations = $toTranslate->getOpenKeys();
    }
    
    public function manualKeyUpdateAction() {
        $translationFormAndPageSelection = new Form_TranslationFormAndPageSelection();
        
            $translationFormAndPageSelection->populate(
                $this->_request->getParams()
            );
        
        $keyMaster = new Model_KeyMaster($translationFormAndPageSelection);
        $this->view->formAndPageSelection = $translationFormAndPageSelection;
        $gateKeeper = new Form_TranslationGateKeeper($keyMaster->getKeys());
        
        if ($this->getRequest()->isPost())
        {
            if ($gateKeeper->isValid($this->getRequest()->getPost()))
            {
                $toTranslate = new Model_Table_ToTranslate();
                $toTranslate->flagKeysToTranslate(
                    $gateKeeper, 
                    $keyMaster->getKeys(),
                    $this->_request->getParam('form'),
                    $this->_request->getParam('page'),
                    $this->user->user['id_personnel']);
                $keyMaster->updateKeys(
                    $gateKeeper, 
                    $this->_request->getParam('form'),
                    $this->_request->getParam('page'));
                $this->view->updateSuccess = true; 
            }
        }
        
        $this->view->form = $this->_request->getParam('form');
        $this->view->page = $this->_request->getParam('page');
        $this->view->gateKeeper = $gateKeeper;
        $this->view->keys = $keyMaster->getKeys();
    }
    
    public function exportAction() {
        
        $translationExportForm = new Form_TranslationExport();
        
        if ($this->getRequest()->isPost())
        {
            if ($translationExportForm->isValid($this->getRequest()->getPost()))
            {
                $this->_helper->layout()->disableLayout();
                $this->_helper->viewRenderer->setNoRender(true);
                
                switch ($translationExportForm->getValue('form')) {
                    case 'global':
                        $tmxFilename = APPLICATION_PATH . '/translation/'.
                                $translationExportForm->getValue('form').'.tmx';
                        break;
                    default:
                        $tmxFilename = APPLICATION_PATH . '/translation/'.
                                    $translationExportForm->getValue('form').'/page'.
                                    $translationExportForm->getValue('page').'.tmx';
                }
                
                $translate = new Zend_Translate(
                        array(
                                'adapter' => 'tmx',
                                'content' => $tmxFilename,
                                'scan' => Zend_Translate::LOCALE_FILENAME,
                                'disableNotices' => true
                        )
                );
                $translate->setLocale($translationExportForm->getValue('locale'));
                switch ($translationExportForm->getValue('form')) {
                    case 'global':
                        $fileName = $translationExportForm->getValue('form');
                        break;
                    default:
                        $fileName = $translationExportForm->getValue('form') . '_Page' . $translationExportForm->getValue('page');
                        break;
                }
                header("Cache-Control: must-revalidate, " .
                        "post-check=0, pre-check=0");
                header("Pragma: hack");
                header("Content-type: text/csv");
                header("Content-Disposition: attachment; filename={$fileName}.csv");
                
                $fp = fopen('php://output', 'w');
                
                $heading = array('Key', 'To Translate', 'Translation');
                fputcsv($fp, $heading);
                foreach ($translate->getMessages() AS $key => $value)
                    fputcsv($fp, array($key, utf8_decode($value)));
                exit;
            }   
        }

        $this->view->form = $translationExportForm;
    }
    
    public function importAction() {
        $translationImportForm = new Form_TranslationImport();
        
        if ($this->getRequest()->isPost())
        {  
            if ($translationImportForm->isValid($this->getRequest()->getPost()))
            {
                if ($translationImportForm->csv->receive()) {
                    $this->_helper->layout()->disableLayout();
                    $this->_helper->viewRenderer->setNoRender(true);
            
                    $keys = array();
                    if (($handle = fopen($translationImportForm->csv->getFileName(), "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $keys[$data[0]] = $data[2];
                        }
                        fclose($handle);
                    }
                    
                    switch ($translationImportForm->getValue('form')) {
                        case 'global':
                            $translation_xml = new SimpleXMLElement(
                                    file_get_contents(
                                            APPLICATION_PATH . '/translation/'.
                                            $translationImportForm->getValue('form').'.tmx'
                                            ));
                        break;
                        default:
                            $translation_xml = new SimpleXMLElement(
                                    file_get_contents(
                                            APPLICATION_PATH . '/translation/'.
                                            $translationImportForm->getValue('form').'/page'.
                                            $translationImportForm->getValue('page').'.tmx'
                                    ));
                            break;
                    }
                    
                    for ($i=0;$i<count($translation_xml->body->tu);$i++) {
                        if (array_key_exists((string)$translation_xml->body->tu[$i]->attributes()->tuid, $keys)) {
                            $translation_xml->body->tu[$i]->tuv[$this->_translationIndex[$translationImportForm->getValue('locale')]]->seg = utf8_encode(str_replace('SPANISH: ', '', $keys[(string)$translation_xml->body->tu[$i]->attributes()->tuid]));
                        }
                    }
                   
                    switch ($translationImportForm->getValue('form')) {
                        case 'global':
                            $translation_xml->asXML(
                                    APPLICATION_PATH . '/translation/'.
                                    $translationImportForm->getValue('form').'.tmx'
                                    );
                        break;
                        default:
                            $translation_xml->asXML(
                                    APPLICATION_PATH . '/translation/'.
                                    $translationImportForm->getValue('form').'/page'.
                                    $translationImportForm->getValue('page').'.tmx'
                            );
                            break;
                    }
                    
                    echo 'CSV imported successfully.';
                }
            }
        }
        
        $this->view->form = $translationImportForm;
    }
    
    public function testAction() {
    	
    }
}