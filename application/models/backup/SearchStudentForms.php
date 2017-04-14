<?php
class Model_SearchStudentForms {

    protected $_student;
    protected $_session;
    protected $_cache;

    public function __construct(Model_Table_StudentTable $student, Zend_Session_Namespace $session, 
                    Zend_Cache_Core $cache)
    {
        $this->_setStudent($student);
        $this->_setSession($session);
        $this->_setCache($cache);
    }

    /*
    * search for students
    */
    public function searchForms($options, $sort = array(), $cache = true) {
        ini_set('memory_limit', '500M');
        /*
        * Check to make sure a search field was added.
        */
        if (empty($options['student']))
            return array('error' => 'You must enter a student id.');

        $binds = array();

        /*
         * conditionals
         */
        $formTypeCondition = '';
        if(''!=$options['formType']) {
            $formTypeCondition = ' and form_no = \''.$options['formType'].'\' ';
        } else {
            $formTypeCondition .= "and form_no != '010' "; // progress reports are shown under IEPs
        }

        $searchStatusCondition = '';
        if('current'==$options['searchStatus']) {
            $searchStatusCondition = " and timestamp_created >= to_timestamp('".date('m/d/Y', 
                            strtotime("today-13 month"))."', 'MM/DD/YYYY') ";
        }


        $sqlStmt = "SELECT s.*, to_char(timestamp_created, 'MM/DD/YYYY') as date_created,
                    to_timestamp(create_date, 'mm-dd-YYYY') as create_date_sort
                    from student_forms
                    s where id_student =  '" . $options['student'] . "' $searchStatusCondition $formTypeCondition ";
        if($options['hideSuspended']) {
            $sqlStmt .= " and status != 'Suspended' " ;
        }
        $sqlStmt .= "order by create_date_sort desc, date desc;";

        /**
         * Check to see if there is a cache key set
         */
        if (empty($options['key']))
            $key = Model_CacheManager::generateCacheKey();
        else
            $key = $options['key'];

        if (!Model_CacheManager::isCached(Zend_Registry::get('searchCache'), $key))
            $toCache = $this->_student
                ->getAdapter()
                ->fetchAll($sqlStmt, $binds);
        else
            $toCache = false;

        Zend_Paginator::setCache(Zend_Registry::get('searchCache'));
        $maxResultsExceeded = false;
        // Mike put key to cache at 50 from 2500 trying to get things knocked down.
        if (count($cacheResults = Model_CacheManager::getCacheForKey(Zend_Registry::get('searchCache'), 
                        $key, $toCache)) >= 250)
            $maxResultsExceeded = true;

        $archived = $this->getArchivedFilesForStudent($options);
        if($archived) {
            $this->addArchivedPathsToResults($cacheResults, $options, $archived);
            $this->addArchivedNotInDb($cacheResults, $archived, $options);

            if(isset($options['archivedOnly']) && '1'==$options['archivedOnly']) {
                $cacheResults = $this->getOnlyArchived($cacheResults);
            }
        }
//        Zend_Debug::dump($cacheResults, 'asdfasdfasdffasd');die;
        return array(
            $maxResultsExceeded,
            $paginator = Zend_Paginator::factory($cacheResults)->setItemCountPerPage($options['maxRecs'])
                ->setCurrentPageNumber(empty($options['page']) ? 1 : $options['page']),
            $key
        );
    }
    
    private function getFormNumFromArchiveFilename($basename)
    {
        $fileNameArr = preg_split('/-/', $basename);
        if(isset($fileNameArr[1]) && 3 == strlen($fileNameArr[1])) {
            return $fileNameArr[1];
        }
        return false;
    }

    private function addArchivedNotInDb(&$cacheResults, $archived, $options) {
//        Zend_Debug::dump($archived);
        $studentId = $options['student'];
        //Get those forms for which an archived version exists but that are not in the database
        // so we have a pdf but not a SOLR record
        $sf = new Form_SearchForm();

        // parse the formnumber from the file path


        foreach($archived as $ad) {
//            Zend_Debug::dump($ad, 'solrRecord');
            $splitPath = preg_split('/\//', $ad['path']);

            $formNum = ("" == $options['formType']) ? $this->getFormNumFromArchiveFilename(basename($ad['path'])) : $options['formType'];
            $formName = $sf->formInfo[$formNum]['shortName'];
            $formDateString = substr($ad['path'], strpos($ad['path'], '(')+1, 8);
            $formDate = date('m/d/Y', strtotime($formDateString));
            
            $fileNameArr = preg_split('/-/', basename($ad['path']));
            $realPathDbFormat = '/' . $splitPath[sizeof($splitPath)-3] . '/' . $splitPath[sizeof($splitPath)-2]
                                    . '/' . $splitPath[sizeof($splitPath)-1];
            $inDb = false;
            foreach($cacheResults as $result) {
//                Zend_Debug::dump($result, 'regular record');
                if(array_key_exists('filePath', $result)) {
                    // this record has a legit binary
                    if($result['filePath'] == $realPathDbFormat) {
//                    Zend_Debug::dump($realPathDbFormat, 'realPathDbFormat');
                        $inDb = true;
                        
                        break;
                    }
                }
            }
            if(!$inDb) {
                $tempArr = array(
                    'filePath' => $realPathDbFormat,
                    'id_student' => $studentId,
                    'formname' => $formName,
                    'form_no' => $formNum,
                    'date_created' => '',
                    'date' => $formDate,
                    'status' => 'Archived'
                );
                if(isset($fileNameArr[2])) {
                    // pulled from filename
                    $tempArr['id'] = $fileNameArr[2];
                }
                $cacheResults[] = $tempArr;
            }
        }
    }
    
    private function getArchivedFilesForStudent($options) {
        $config = Zend_Registry::get('config');
        //Get all files archived in Solr for the specified student and form
       
        // Michael commented this out to check the solr site which does not exist in the dev environment. 
       
       // $response =  App_Solr::queryJson(array('studentid'=>$options['student'], 'formnumber' => $options['formType']),
       //                 array('attr_stream_name', 'timestamp'), $config->solr->host, $config->solr->port);
        
        try {
            $response = App_Solr::queryJson(array('studentid'=>$options['student'], 'formnumber' => $options['formType']),
                array('attr_stream_name', 'timestamp'), $config->solr->host, $config->solr->port);
        } catch (\Exception $e){
            $response = false;
        }
       
        
        if($response) {
            $decodedResponse = json_decode($response);
            $pathsObjects = $decodedResponse->response->docs;
            $archived = array();
            foreach($pathsObjects as $po) {
            
                $archived[] = array('path' => $po->attr_stream_name[0], 'date' => $po->timestamp);
            }
            return $archived;
        } else {
            return false;
        }
    }
    
    private function getOnlyArchived($cacheResults) {
        $results = array();
        foreach($cacheResults as $result) {
             if(array_key_exists('filePath', $result)) {
                 $results[] = $result;
             }
        }
        return $results;
    }
    
    private function addArchivedPathsToResults(&$cacheResults, $options, $archived) {      
        foreach ($cacheResults as &$result) {        
            $filename = 'form-' . $result['form_no'] . '-' . $result['id'] . '-archived('.date('Ymd', strtotime($result['date'])). ').pdf';          
            foreach ($archived as $ad) { 
                if(file_exists($ad['path'])) {
                    $splitPath = preg_split('/\//', $ad['path']);     
                    $formDate = substr($ad['path'], strpos($ad['path'], '('), 8);
                    if($result['id_student'] == $splitPath[sizeof($splitPath)-3] 
                                    && $filename == $splitPath[sizeof($splitPath)-1]) {

                        // inject filePath into the result - this means there is an actual binary
                        $result['filePath'] = '/' . $splitPath[sizeof($splitPath)-3] 
                                            . '/' . $splitPath[sizeof($splitPath)-2] 
                                            . '/' . $splitPath[sizeof($splitPath)-1];
                        $result['date123'] = $formDate;
//                        Zend_Debug::dump($result['date'], 'asdfasdfasdffasd');die;
                        break;
                    }
                }
            }
        }
    }

    protected function buildSearchOptions($searchValues, $searchFields) {
        $i=0;
        $retArray = array();
        foreach ($searchFields AS $key => $value)
            if (strlen($searchValues[$key]) > 0 && strlen($searchFields[$key]) > 0)
                $retArray[$searchFields[$key]] = $searchValues[$key];
        return $retArray;
    }

    protected function _setStudent(Model_Table_StudentTable $student)
    {
        $this->_student = $student;
    }

    protected function _setSession(Zend_Session_Namespace $session)
    {
        $this->_session = $session;
    }

    protected function _setCache(Zend_Cache_Core $cache)
    {
        $this->_cache = $cache;
    }

}