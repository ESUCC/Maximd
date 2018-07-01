<?php

/**
 * ReportController
 */
class ReportController extends My_Form_AbstractFormController

{
    
    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    public function indexAction()
    {
        if('production' == APPLICATION_ENV) {
            return $this->_redirect('https://iepd.nebraskacloud.org/srs.php?area=reports&sub=reports');
        }
        return $this->_redirect('/report/nssrs');

//        $this->view->hideLeftBar = true;
//        $this->view->headLink()->appendStylesheet('/css/student_search.css');
//
//        $reportService = $this->getReportService();
//        $this->view->reportTitles = $reportService->getReportTitles();
//
//        if ($this->getRequest()->isPost()) {
//            $postData = $this->getRequest()->getParams();
//            if (isset($postData['reportTitle'])) {
//                return $this->_redirect('/report/' . strtolower($this->view->reportTitles[$postData['reportTitle']]));
//            }
//        }

    }

    public function studentReportsAction()
    {
        $this->view->hideLeftBar = true;

        if ($this->getRequest()->getParam('id_student')) {
            $this->view->id_student = $this->getRequest()->getParam('id_student');
        } else {
            return $this->_redirect('/report');
        }

        $reportService = $this->getReportService();

        $this->view->reportTitles = $reportService->getReportTitles();

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();
            if (isset($postData['reportTitle'])) {
                return $this->_redirect('/report/' . strtolower($this->view->reportTitles[$postData['reportTitle']]));
            }
        }

    }

    public function nssrsAction()
    {
        $this->setupStudentSearch();
        $searchForm = $this->view->searchForm;
     /*   $this->writevar1($searchForm,'this is the student search form');
      * This setups the Search Fields on the left hand side of the screen when debgugging the
      * report/nssrs action.
      */
        
        
        $searchForm->getElement('format')->setMultiOptions(
            array(
                'both' => 'NSSRS and Transfer Reports',
                'nssrs' => 'NSSRS Records Only',
                'transfer' => 'Transfer Records Only',
                'incomplete' => 'Incomplete Records Only',
                'exit_dates' => 'Records with Exit dates',
                'no_iep' => 'Records without IEP/IFSP Data',
                'no_mdt' => 'Records without MDTData',
                'no_nssrs' => 'Records without a NSSRS ID#',
                'excluded' => 'Excluded',
                'edfi'=>'Publish to Advisor',
            ));
        
    } 

    public function reportSearchAction()
    {
        $this->_helper->layout()->disableLayout();
        $dt = new App_NssrsService();

        $searchData = $this->getRequest()->getParams();
        $searchData['format'] = isset($searchData['reportFormat']) ? $searchData['reportFormat'] : 'nssrs';
        $searchData['start'] = isset($searchData['start']) ? $searchData['start'] : 0;
        $searchData['page'] = 0 == $searchData['start'] ? 1 : intval($searchData['start'] / $searchData['length']);
        $searchData['maxRecs'] = $searchData['length'];
    //    $this->writevar1($searchData,'this is the search data');
    //    $this->writevar1($this->getRequest()->getPost('columns'),'this is the column request');
        
        echo json_encode($dt->buildTransfersDatatable($searchData, $this->getRequest()->getPost('columns')));
        exit;
    }

    public function nssrsCreateFormAction()
    {
        $this->_helper->layout()->disableLayout();
        $dt = new App_NssrsService();

        $searchData = $this->getRequest()->getParams();
        $searchData['format'] = $searchData['reportFormat'];
        $searchData['maxRecs'] = $searchData['length'];
        $searchData['page'] = $searchData['length'];
        echo json_encode($dt->buildTransfersDatatable($searchData, $this->getRequest()->getPost('columns')));
        exit;
    }

    public function nssrsGetStudentReportAction()
    {
        // Mike Start here on Friday 5-4-2017
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();
            if ('transfer' == $postData['nssrsType'] && isset($postData['id_nssrs_transfers'])) {
                $nssrsTransfer = $this->getNssrsTransfer($postData['id_nssrs_transfers']);
                $this->view->report = new App_Report_Nssrs($postData['id_student'], true, $nssrsTransfer, true);

            } elseif ('saveTransfer' == $postData['nssrsType'] && isset($postData['id_nssrs_transfers'])) {
                if (isset($postData['form'])) {
                    $formData = array();
                    foreach ($postData['form'] as $keyValueArray) {
                        $formData[$keyValueArray['name']] = $keyValueArray['value'];
                    }

                    // save the form
                    $saveResult = $this->saveNssrsTransfer($postData['id_nssrs_transfers'], $formData);

                    // build report with new data
                    $nssrsTransfer = $this->getNssrsTransfer($postData['id_nssrs_transfers']);
                    $report = new App_Report_Nssrs($postData['id_student'], true, $nssrsTransfer, true);

                    // return results
                    $this->_helper->json(
                        array(
                            'success' => $saveResult,
                            'errorMessages' => $report->form->getMessages(),
                            'commaSeparated' => $report->buildCommaSeparated(),
                        )
                    );
                    exit;
                }
            } else {
                // Mike this is the call for the students report 5-4-2017
                $this->view->report = new App_Report_Nssrs($postData['id_student'], false);
            //    $this->writevar1($this->view->report,'this is the students nssrs data');
            }
        } else {
            throw new Exception('Not a post.');
        }
    }

    /**
     * @return App_Service_ReportService
     */
    private function getReportService()
    {
        $sessUser = new Zend_Session_Namespace ('user');
        $privCheck = new My_Classes_privCheck($sessUser->user->privs);
        $reportService = new App_Service_ReportService($privCheck);

        return $reportService;
    }

    /**
     * @param $nssrsTransferId
     * @return Model_Table_NssrsTransfers|false
     */
    private function getNssrsTransfer($nssrsTransferId)
    {
        $nssrsTransfersObj = new Model_Table_NssrsTransfers();
        $select = $nssrsTransfersObj->select()->where("id_nssrs_transfers = '{$nssrsTransferId}' ")->order(array(
            'timestamp_created desc',
            'id_nssrs_transfers'
        ));

        return $nssrsTransfersObj->fetchRow($select);
    }

    private function saveNssrsTransfer($nssrsTransferId, $data)
    {
        $nssrsTransfersObj = new Model_Table_NssrsTransfers();
        $select = $nssrsTransfersObj->select()->where("id_nssrs_transfers = '{$nssrsTransferId}' ")->order(array(
            'timestamp_created desc',
            'id_nssrs_transfers'
        ));
        $nssrsTransfer = $nssrsTransfersObj->fetchRow($select);
        $nssrsTransfer->nssrs_002 = $data['field02'];
        $nssrsTransfer->nssrs_005 = $data['field05'];
        $nssrsTransfer->nssrs_011 = $data['field11'];
        $nssrsTransfer->nssrs_016 = $data['field16'];
        $nssrsTransfer->nssrs_023 = $data['field23'];
        $nssrsTransfer->nssrs_032 = $data['field32'];
        $nssrsTransfer->nssrs_033 = $data['field33'];
        $nssrsTransfer->nssrs_034 = $data['field34'];
        $nssrsTransfer->nssrs_044 = $data['field44'];
        $nssrsTransfer->nssrs_047 = $data['field47'];
        $nssrsTransfer->nssrs_050 = $data['field50'];
        $nssrsTransfer->nssrs_052 = $data['field52'];
        if (false === $nssrsTransfer->save()) {
            return false;
        } else {
            return true;
        }
    }
}
