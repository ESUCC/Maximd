<?php

class ArchiveController extends My_Form_AbstractFormController
{

    public function preDispatch()
    {
        parent::preDispatch();
        $this->limitToAdminAccess();
    }

    public function formsAction()
    {
        // hide left bar
        $this->view->hideLeftBar = true;

        $form = new Form_ArchiveForms();
        $form->setMethod('post');
        $form->before_date->setValue(date('Y-m-d', strtotime('now -3 years -1 day')));
        $form->populate($this->getRequest()->getParams());

        if ($this->getRequest()->isPost() && $form->before_date->getValue() && $form->form_num->getValue()) {
            if ('004' == $form->form_num->getValue()) {
                $dateFieldName = 'date_conference';
            } else {
                $dateFieldName = 'date_notice';
            }

            $this->view->formsToArchive = App_Application::formsToBeArchived(
                'iep_form_' . $form->form_num->getValue(),
                'id_form_' . $form->form_num->getValue(),
                $form->before_date->getValue(),
                $dateFieldName
            );

            // disable buttons and add reset button
            $form->addArchiveFuncationality();
        }

        $this->view->form = $form;
        $this->view->jQuery()->enable();
        $this->view->jQuery()->uiEnable();
        $this->view->jQuery()->addStylesheet('/js/jquery_addons/DataTables-1.8.2/media/css/demo_page.css', 'screen');
         $this->view->jQuery()->addStylesheet('/js/jquery_addons/DataTables-1.8.2/media/css/demo_table.css', 'screen');
        $this->view->jQuery()->addStylesheet(
           '/js/jquery_addons/DataTables-1.8.2/media/css/demo_table_jui.css','screen'
       );

        $this->view->jQuery()->addJavascriptFile('/js/jquery_addons/DataTables-1.8.2/media/js/jquery.dataTables.js');
        $this->view->jQuery()->addJavascriptFile('/js/jquery_addons/jquery.dataTables.columnFilter.js');
        $this->view->students = array(
            array('id_student' => 'Loading....', 'name_first' => '', 'name_last' => ''),
        );
    }

    public function archivePdfFormsAction()
    {
        // required params
        if (!$this->getRequest()->getParam('formNum')) {
            echo "missing parameters.";
            die();
        }

        // pdf archive config
        $sessUser = new Zend_Session_Namespace('user');
        $formNumber = $this->getRequest()->getParam('formNum');

        // move data to archive database
        App_Application::archiveFormsForTable($formNumber, true);
        die();
    }

    function datatableAction()
    {
        $this->_helper->layout()->disableLayout();

        $aColumns = array('id_student', 'name_first', 'name_last');
        $ilikColumns = array('name_first', 'name_last');

        $dt = new App_JQueryDatatable();
        $dt->setSwhere(
            "id_county='" . $this->getRequest()->getParam('id_county') . "' and id_district='" . $this->getRequest(
            )->getParam('id_district') . "' and id_school='" . $this->getRequest()->getParam('id_school') . "'"
        );
        echo $dt->datatable($aColumns, $ilikColumns, "iep_student", "id_student");
        exit;
    }

    function testArchiveFormAction()
    {
        $config = Zend_Registry::get('config');
        $driverOptions = array_merge($config->db2->params->toArray(), array('type' => 'pdo_pgsql'));
        $options = array(
            'driverOptions' => $driverOptions,
            'options' => array(
                // use Zend_Db_Select for update, not all databases can support this
                // feature.
                Zend_Db_Select::FOR_UPDATE => true
            )
        );

        // Create a database queue.
        $queue = new Zend_Queue('Db', $options);
        $archiveQueue = $queue->createQueue('archiveQueue');
// 		$queue->send('test');


// 		$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log_jobqueue.txt');
// 		$logger = new Zend_Log($writer);
// 		$logger->info('create job');

// 		$sessUser = new Zend_Session_Namespace('user');
// 		$params = array(
// 				'id_user' => $sessUser->sessIdUser,
// 				'form_number' => $this->getRequest()->getParam('form_number'),
// 				'document' => $this->getRequest()->getParam('document'),
// 		);

// 		//ajax function to schedule form for archiving
// 		$queue = new ZendJobQueue();
// 		$jobPath = '/scripts/ArchiveForm.php';
// 		$queue->createHttpJob('https://'.$_SERVER['HTTP_HOST'] . $jobPath, $params);
        exit;
    }
// 	public function jobArchiveFormToPdfAction()
// 	{
// 		$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log_jobqueue.txt');
// 		$logger = new Zend_Log($writer);

// 		try {
// 			$logger->info('jobArchiveFormToPdfAction');
// 			return ZendJobQueue::setCurrentJobStatus(ZendJobQueue::OK);
// 		} catch (Exception $e) {
// 			$logger->info('jobArchiveFormToPdfAction FAIL');
// 			return ZendJobQueue::setCurrentJobStatus(ZendJobQueue::FAILED);
// 		}
// 		die();
// 	}

    function getJobQueueAction()
    {
        $this->_helper->layout()->disableLayout();

        $aColumns = array('queue_name', 'status');
        $ilikColumns = array('queue_name');

        $dt = new App_JQueryDatatable();
        $dt->setSwhere("1=1");
        echo $dt->datatable($aColumns, $ilikColumns, "srs_queue", "srs_queue_id");
        exit;
    }


    function runQueueAction()
    {
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log_jobqueue.txt');
        $logger = new Zend_Log($writer);
        $logger->info('runQueueAction');
        echo new Zend_Dojo_Data ('id_my_template_data', array(), 'id_my_template_data');
        exit;
    }

    function restoreAction()
    {
        $returnMessage = '';
        $startTime = strtotime('now');

        $this->_helper->layout()->disableLayout();
        $post = $this->getRequest()->getParams();

        require_once APPLICATION_PATH . '/../scripts/cron/ArchiverHelper.php';
        require_once APPLICATION_PATH . '/../scripts/cron/Archiver.php';


        // get cmd line params
        $passedEnv = 'unknown';
        $formNumber = $post['form'];
        $formId = $post['id'];
        if (null == $passedEnv || null == $formNumber || null == $formId || strlen($formNumber) != 3) {
            $returnMessage .= "ERROR - Usage: archive.php -e environment(iepweb03) -n formNum(004) -i formId \n";
            $returnMessage .= "passedEnv: $passedEnv\n";
            $returnMessage .= "formNumber: $formNumber\n";
            $returnMessage .= "formId: $formId\n";
            die();
        }

        $session = new Zend_Session_Namespace('user');
        if (empty($session->locale)) {
            $session->locale = 'en';
        }

        // load the maing and archive configs
        $config = Zend_Registry::get('config');
        $archiveConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/archive.ini', APPLICATION_ENV);
        $returnMessage .= "Configs loaded.\n";

        // setup db
        ArchiverHelper::seetupDb($config);

        //setup zend translate
        ArchiverHelper::setupTranslate($config, $formNumber, $session);

//        // login - this grants student access
//        $auth = new App_Auth_Authenticator();
//        $user = $auth->getCredentials($archiveConfig->siteAccess->username, $archiveConfig->siteAccess->password);
//        if ($user) {
//            App_Helper_Session::grantSiteAccess($user, false);
//            $returnMessage .= "User found. \n";
//        } else {
//            $returnMessage .= "Failed login.\n";
//            echo Zend_Json::encode(
//                array(
//                    'success' => 1,
//                    'message' => $returnMessage,
//                )
//            );
//            exit;
//        }
        $returnMessage .= "User logged in.\n";
        $returnMessage .= "form id: $formId\n";

        Archiver::restoreArchiveForm($formId, $formNumber, $config, $archiveConfig);
        $returnMessage .= "*** Restore finished ***\n";
        echo Zend_Json::encode(
            array(
                'success' => 1,
                'message' => $returnMessage,
                'time' => round((strtotime('now') - $startTime) / 60, 2) . " minute(s)\n",
            )
        );
        exit;
    }

//    function tooManyFilesAction() {
//        $files = array();
//        $dir = opendir('/archive');
//
//        if (false !== ($file = readdir($dir))) {
//            echo "$file\n";
//        }
//
//
////        while(($file = readdir($dir)) !== false)
////        {
////            if($file !== '.' && $file !== '..' && !is_dir($file))
////            {
////                $files[] = $file;
////                break;
////            }
////        }
////        closedir($dir);
////        sort($files);
////        for($i=0; $i<count($files); $i++)
////        {
////            echo "$files[$i]";
////        }
//    }
}


