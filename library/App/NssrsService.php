<?php

class App_NssrsService
{
    var $sWhere;
    var $debug = false;
    var $returnRows = array();
    var $nssrsError = false;
    var $noText = '<span class="btsRed">No</span>';
    var $noneText = '<span class="btsRed">None</span>';

    function __construct()
    {
        $config = Zend_Registry::get('config');

        /* Database connection information */
        $gaSql ['user'] = $config->db2->params->username;
        $gaSql ['password'] = $config->db2->params->password;
        $gaSql ['db'] = $config->db2->params->dbname;
        $gaSql ['server'] = $config->db2->params->host;

        $this->db = Zend_Registry::get('db');
        $this->formats = array(
            'nssrs' => false,
            'transfer' => false,
            'incomplete' => true,
            'exit_dates' => true,
            'no_iep' => true,
            'no_mdt' => true,
            'no_nssrs' => true,
            'excluded' => true,
        );
    }
    public function buildTransfersDatatable($request, $columns)
    {
        $sessUser = new Zend_Session_Namespace ('user');
        $search  = new Model_SearchStudent(
            new Model_Table_StudentTable(),
            $sessUser,
            Zend_Registry::get('searchCache')
        );

        $privCheck = new My_Classes_privCheck($sessUser->user->privs);
        $recordsTotal = $this->buildTotalRecordCount($request, $privCheck, $search, $sessUser->user->user['id_personnel']);

        /*
         * nssrs records - built from student data
         */
        if('nssrs' == $request['format'] || $this->formats[$request['format']]) {
            $search->reset();
            if (!array_key_exists('error', ($searchResults = $search->searchStudent($request, array())))) {
                foreach ($searchResults[1]->getCurrentItems() as $studentRow) {
                    $this->returnRows[] =  $this->buildNssrsRow($columns, $studentRow, $this->noText, $this->noneText);
                }
            } else {
                $this->nssrsError = true;
            }
        }

        /*
         * Transfer records - records in the transfer table
         */
        $transferError = false;
        if('transfer' == $request['format'] || $this->formats[$request['format']]) {
            $search->reset();
            $search->setOverrideSelectStmt("select distinct s.*, CASE WHEN s.name_middle IS NOT NULL THEN s.name_first || ' ' || s.name_last ELSE s.name_first || ' ' || s.name_last END AS name_full ");
            $search->setOverrideFromStmt('from my_nssrs_transfers s');
            if (!array_key_exists('error', ($searchResults = $search->searchStudent($request, array())))) {
                foreach ($searchResults[1]->getCurrentItems() as $row) {
                    $this->returnRows[] = $this->buildTransferRecords($columns, $row, $this->noText, $this->noneText);
                }
            } else {
                $this->nssrsError = true;
            }
        }

        if('incomplete' == $request['format']) {
            foreach ($this->returnRows as $index => $returnRow) {
                if($returnRow[0] != $this->noText) {
                    unset($this->returnRows[$index]);
                }
            }
            $this->returnRows = array_values($this->returnRows);
        }

        if('exit_dates' == $request['format']) {
            foreach ($this->returnRows as $index => $returnRow) {
                if(is_null($returnRow['DT_RowData']['field34'])) {
                    unset($this->returnRows[$index]);
                }
            }
            $this->returnRows = array_values($this->returnRows);
        }

        if('no_iep' == $request['format']) {
            foreach ($this->returnRows as $index => $returnRow) {
                if($this->noText !== $returnRow['DT_RowData']['currentIep']) {
                    unset($this->returnRows[$index]);
                }
            }
            $this->returnRows = array_values($this->returnRows);
        }

        if('no_mdt' == $request['format']) {
            foreach ($this->returnRows as $index => $returnRow) {
                if($this->noText !== $returnRow['DT_RowData']['currentMdt']) {
                    unset($this->returnRows[$index]);
                }
            }
            $this->returnRows = array_values($this->returnRows);
        }

        if('no_nssrs' == $request['format']) {
            foreach ($this->returnRows as $index => $returnRow) {
                if($this->noneText !== $returnRow['DT_RowData']['nssrsId']) {
                    unset($this->returnRows[$index]);
                }
            }
            $this->returnRows = array_values($this->returnRows);
        }

        if('excluded' == $request['format']) {
            foreach ($this->returnRows as $index => $returnRow) {
                if(true !== $returnRow['DT_RowData']['exclude_from_nssrs_report']) {
                    unset($this->returnRows[$index]);
                }
            }
            $this->returnRows = array_values($this->returnRows);
        }

        $recordsFiltered = count($this->returnRows); //$this->buildTotalFilteredCount($request, $search);

        if($this->nssrsError || $transferError) {
            return array(
                "draw"            => intval( $request['draw'] ),
                "recordsTotal"    => 0,
                "recordsFiltered" => 0,
                "data"            => array(),
                'datatable_error' => $searchResults['error'],
            );
        } else {
            return array(
                "draw"            => intval( $request['draw'] ),
                "recordsTotal"    => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data"            => $this->returnRows
            );
        }
    }

    /**
     * @param $nssrsReport
     * @return string
     */
    protected function buildSelectOptions($nssrsReport, $form022DraftId, $form023DraftId)
    {
        $nssrsOptions = '<select class="nssrsAction">';
        $nssrsOptions .='<option value="">Choose...</option>';
        if(!is_null($form022DraftId)) {
            $nssrsOptions .='<option value="Edit MDT Data Card">Edit MDT Data Card</option>';
        } else {
            $nssrsOptions .='<option value="Add MDT Data Card">Add MDT Data Card</option>';
        }
        if(!is_null($form023DraftId)) {
            $nssrsOptions .='<option value="Edit IEP Data Card">Edit IEP Data Card</option>';
        } else {
            $nssrsOptions .='<option value="Add IEP Data Card">Add IEP Data Card</option>';
        }
        $nssrsOptions .='<option value="Edit NSSRS ID#">Edit NSSRS ID#</option>';
        $nssrsOptions .='<option value="Forms Screen">Forms Screen</option>';
        $nssrsOptions .='<option value="Edit Student">Edit Student</option>';
        $nssrsOptions .='<option value="View Report Data">View Report Data</option>';
        $nssrsOptions .=($nssrsReport->type == 'Transfer' ? '<option value="Delete Record">Delete Record</option>' : '');
        $nssrsOptions .='</select>';

        return $nssrsOptions;
    }

    /**
     * @param $request
     * @param $privCheck
     * @param $search
     * @return int
     */
    protected function buildTotalRecordCount($request, $privCheck, $search, $idPersonnel)
    {
        // get the total number of records the user has access to
        $recordsTotal = 0;
        if (0 && 1 == $privCheck->getMinPriv()) {
            $search->setOverrideSelectStmt('select count(1)');
            $search->setOverrideFromStmt('from iep_student');
            $search->setOverrideWhereStmt('');
            $search->setOverrideOrderLimitStmt('');
            $search->setBinds(array());
            if (!array_key_exists('error', ($recordsTotalResult = $search->searchStudent($request, array(), true)))) {
                if (count($recordsTotalResult) && isset($recordsTotalResult[0]['count'])) {
                    $recordsTotal = $recordsTotalResult[0]['count'];
                    return $recordsTotal;
                }
            }
        } else {
            $search->setOverrideSelectStmt('select count(1)');
            $search->setOverrideWhereStmt("where id_personnel = '".$idPersonnel."'");
            $search->setOverrideOrderLimitStmt('');
            $search->setBinds(array());
            if (!array_key_exists('error', ($recordsTotalResult = $search->searchStudent($request, array(), true)))) {
                if (count($recordsTotalResult) && isset($recordsTotalResult[0]['count'])) {
                    $recordsTotal = $recordsTotalResult[0]['count'];
                    return $recordsTotal;
                }
                return $recordsTotal;
            }
        }
        return 0;
    }

    /**
     * @param $request
     * @param $search
     * @return int
     */
    protected function buildTotalFilteredCount($request, $search)
    {
// get the filtered number of records
        $recordsFiltered = 0;
        $search->reset();
        $search->setOverrideSelectStmt('select count(1)');
        $search->setOverrideOrderLimitStmt('');
        if (!array_key_exists('error', ($recordsFilteredResult = $search->searchStudent($request, array(), true)))) {
            if (count($recordsFilteredResult) && isset($recordsFilteredResult[0]['count'])) {
                $recordsFiltered = $recordsFilteredResult[0]['count'];

                return $recordsFiltered;
            }

            return $recordsFiltered;
        }

        return $recordsFiltered;
    }

    /**
     * @param $columns
     * @param $studentRow
     * @param $noText
     * @param $noneText
     * @return array
     */
    private function buildNssrsRow($columns, $studentRow, $noText, $noneText)
    {
        $draft022 = null;
        $draft023 = null;
        $nssrsReport = new App_Report_Nssrs($studentRow['id_student'], false);

        $currentMdt = is_null($nssrsReport->getCurrentMdtOrCard()) ? $noText : 'Yes';
        $currentIep = is_null($nssrsReport->getCurrentIepIfspOrCard()) ? $noText : 'Yes';

        $studentRow = array_merge(
            $nssrsReport->form->getValues(),
            $studentRow,
            array(
            'nssrsComplete' => $nssrsReport->getValid() ? 'Yes' : $noText,
            'nssrsType' => $nssrsReport->type,
            'currentMdt' => $currentMdt,
            'currentIep' => $currentIep,
            'nssrsValid' => $nssrsReport->getValid() ? 'Yes' : $noText,
            'nssrsId' => empty($studentRow['unique_id_state']) ? $noneText : $studentRow['unique_id_state'],
            'exitDate' => empty($studentRow['sesis_exit_date']) ? $noneText : $studentRow['sesis_exit_date'],
            'exitCode' => empty($studentRow['sesis_exit_code']) ? $noneText : $studentRow['sesis_exit_code'],
        ));

        if (false != $mdtDataCard = $nssrsReport->selectFormFromDatabase($studentRow['id_student'], '022', 'draft')) {
            $draft022 = $mdtDataCard['id_form_022'];
        }

        if (false != $iepDataCard = $nssrsReport->selectFormFromDatabase($studentRow['id_student'], '023', 'draft')) {
            $draft023 = $iepDataCard['id_form_023'];
        }

        $studentRow['nssrsOptions'] = $this->buildSelectOptions($nssrsReport, $draft022, $draft023);

        // build return data column data
        $rowData = array();
        foreach ($columns as $column) {
            $rowData[] = $studentRow[$column['name']];
        }
        $rowData['DT_RowData'] = array(
            'studentId' => $studentRow['id_student'],
            'nssrsType' => 'nssrs',
        );
        if (!is_null($draft022)) {
            $rowData['DT_RowData']['draft_id_form_022'] = $draft022;
        }
        if (!is_null($draft023)) {
            $rowData['DT_RowData']['draft_id_form_023'] = $draft023;
        }
        $rowData['DT_RowData']['field34'] = $studentRow['field34'];
        $rowData['DT_RowData']['currentIep'] = $studentRow['currentIep'];
        $rowData['DT_RowData']['currentMdt'] = $studentRow['currentMdt'];
        $rowData['DT_RowData']['nssrsId'] = $studentRow['nssrsId'];
        $rowData['DT_RowData']['exclude_from_nssrs_report'] = $studentRow['exclude_from_nssrs_report'];
        return $rowData;
    }

    /**
     * @param $columns
     * @param $studentRow
     * @param $noText
     * @param $noneText
     * @return array
     */
    private function buildTransferRecords($columns, $studentRow, $noText, $noneText)
    {
        $nssrsReport = new App_Report_Nssrs($studentRow['id_student'], null, $studentRow, true);
        $currentMdt = is_null($nssrsReport->getCurrentMdtOrCard()) ? $noText : 'Yes';
        $currentIep = is_null($nssrsReport->getCurrentIepIfspOrCard()) ? $noText : 'Yes';

        $studentRow = array_merge(
            $nssrsReport->form->getValues(),
            $studentRow,
            array(
            'nssrsComplete' => $nssrsReport->getValid() ? 'Yes' : $noText,
            'nssrsType' => $nssrsReport->type,
            'currentMdt' => $currentMdt,
            'currentIep' => $currentIep,
            'nssrsValid' => $nssrsReport->getValid() ? 'Yes' : $noText,
            'nssrsId' => empty($studentRow['nssrs_005']) ? $noneText : $studentRow['nssrs_005'],
            'exitDate' => empty($studentRow['nssrs_034']) ? $noneText : $studentRow['nssrs_034'],
            'exitCode' => empty($studentRow['nssrs_052']) ? $noneText : $studentRow['nssrs_052'],
            'nssrsOptions' => '<select class="nssrsAction">' .
                '<option value="">Choose...</option>' .
                '<option value="Add IEP Data Card">Add IEP Data Card</option>' .
                '<option value="Add MDT Data Card">Add MDT Data Card</option>' .
                '<option value="Edit NSSRS ID#">Edit NSSRS ID#</option>' .
                '<option value="Forms Screen">Forms Screen</option>' .
                '<option value="Edit Student">Edit Student</option>' .
                '<option value="View Report Data">View Report Data</option>' .
                ($nssrsReport->type == 'Transfer' ? '<option value="Delete Record">Delete Record</option>' : '') .
                '</select>',
        ));

        // build return data column data
        $rowData = array();
        foreach ($columns as $column) {
            $rowData[] = $studentRow[$column['name']];
        }
        $rowData['DT_RowData'] = array(
            'studentId' => $studentRow['id_student'],
            'exclude_from_nssrs_report' => $studentRow['exclude_from_nssrs_report'],
            'nssrsType' => 'transfer',
            'field34' => $studentRow['field34'],
            'id_nssrs_transfers' => $studentRow['id_nssrs_transfers'],
            'currentIep' => $studentRow['currentIep'],
            'currentMdt' => $studentRow['currentMdt'],
            'nssrsId' => $studentRow['nssrsId'],
        );

        return $rowData;
    }
}

