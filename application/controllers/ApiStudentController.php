<?php
class ApiStudentController extends My_Form_AbstractFormController
{

    /**
     * called from the student search page
     * passed a collection key that is used to get a current
     * collection of students
     * then assigns the selected case manager to those students
     * @throws Exception
     *
     */
    protected function assignCaseManagerAction()
    {
        if(!$this->getRequest()->getParam('collection')) {
            throw new Exception('Missing required parameter.');
        }

        /**
         * confirm we have students
         */
        $studentCollection = new App_Collection_Student();
        $collectionItems = $studentCollection->getNames($this->usersession->sessIdUser, $this->getRequest()->getParam('collection'), isset($additionalFields)?$additionalFields:array());
        if(0 == count($collectionItems)) {
            $this->_helper->json(
                array(
                    'success' => 0,
                    'errorMessage' => 'No students in collection.'
                )
            );
        }

        $sessUser = new Zend_Session_Namespace('user');
        $studentObj = new Model_Table_StudentTable();
        $caseManagers = null;
        $currentCDS = '';
        $errorMessage = '';
        $teamMemberExists = false;
        foreach($collectionItems as $studentIdAndName) {
            /**
             * check to be sure all students are at the same school
             */
            $student = Model_StudentSearch::getMyStudent($sessUser->id_personnel, $studentIdAndName['id']);
            $student = $student->toArray();
            if(false != $student && $student['class'] <= UC_ASM) {
                if(strlen($currentCDS) == 0) {
                    $currentCDS = $student['id_county'].$student['id_district'].$student['id_school'];
                }
                if($currentCDS != $student['id_county'].$student['id_district'].$student['id_school']) {
                    $this->_helper->json(
                        array(
                            'success' => 0,
                            'errorMessage' => 'All students must be from the same school for this process to work.'
                        )
                    );
                }

                /**
                 * populate $possibleCaseManagers for return data
                 */
                if(is_null($caseManagers)) {
                    $personnelObj = new Model_Table_PersonnelTable();
                    $caseManagers = $personnelObj->getCaseManagers(
                        $student['id_county'],
                        $student['id_district'],
                        $student['id_school']
                    );
                }
            } else {
                $errorMessage .= 'You do not appear to have the correct privileges to modify ' . $studentIdAndName['name'] . "<BR>";
            }
        }

        if('' != $errorMessage) {
            $this->_helper->json(
                array(
                    'success' => 0,
                    'errorMessage' => $errorMessage
                )
            );
        }
        /**
         * do case manager addition
         */
        $returnStudentData = array();
        foreach($collectionItems as $studentIdAndName) {
            $returnStudentData[$studentIdAndName['id']] = $this->assignCaseManager($studentIdAndName['id']);
        }

        $this->_helper->json(
            array(
                'success' => 1,
                'data' => array(
                    'case_managers' => $caseManagers,
                    'returnStudentData' => $returnStudentData
                )
            )
        );

        die();
    }


    protected function assignTeamMemberAction()
    {
        if(!$this->getRequest()->getParam('collection')) {
            throw new Exception('Missing required parameter.');
        }

        $sessUser = new Zend_Session_Namespace('user');
        $possibleStudentTeamMembers = null;
        $teamMemberId = $this->getRequest()->getParam('id_team_member');
        $confirmTeamMemberOverride = $this->getRequest()->getParam('confirm');
        $limitToFields = array(
            'id_student',
            'id_case_mgr',
            'name_first',
            'name_last',
            'case_mgr_name_first',
            'case_mgr_name_last',
        );
        $studentObj = new Model_Table_StudentTable();


        /**
         * confirm we have students
         */
        $studentCollection = new App_Collection_Student();
        $collectionItems = $studentCollection->getNames($this->usersession->sessIdUser, $this->getRequest()->getParam('collection'), isset($additionalFields)?$additionalFields:array());
        if(0 == count($collectionItems)) {
            $this->_helper->json(
                array(
                    'success' => 0,
                    'errorMessage' => 'No students in collection.'
                )
            );
        }

        $currentCDS = '';
        $errorMessage = '';
        $teamMemberExists = false;
        $studentNotAllowed = false;
        foreach($collectionItems as $studentIdAndName) {
            /**
             * check to be sure all students are at the same school
             */
            $student = Model_StudentSearch::getMyStudent($sessUser->id_personnel, $studentIdAndName['id']);
            $student = $student->toArray();
            if(false != $student && $student['class'] <= UC_CM) {
                if(strlen($currentCDS) == 0) {
                    $currentCDS = $student['id_county'].$student['id_district'].$student['id_school'];
                }
                if($currentCDS != $student['id_county'].$student['id_district'].$student['id_school']) {
                    $this->_helper->json(
                        array(
                            'success' => 0,
                            'errorMessage' => 'All students must be from the same school for this process to work.'
                        )
                    );
                }

                /**
                 * team member check
                 */
                $teamMembers = $studentObj->getStudentTeamMembers($studentIdAndName['id']);
                if(count($teamMembers)) {
                    foreach ($teamMembers as $teamMember) {
                        if($teamMember['id_personnel'] == $teamMemberId) {
                            $errorMessage .= 'This user already exists as a team member for: ' . $student['name_student_full'] . "<BR>";
                            $teamMemberExists = true;
                        }
                    }
                }

                /**
                 * populate $possibleStudentTeamMembers for return data
                 */
                $personnelObj = new Model_Table_PersonnelTable();
                if(is_null($possibleStudentTeamMembers)) {
                    $possibleStudentTeamMembers = $personnelObj->getStudentTeamOptions(
                        $student['id_county'],
                        $student['id_district'],
                        $student['id_school']
                    );
                }
            } else {
                $errorMessage .= 'You do not appear to have the correct privileges to modify ' . $studentIdAndName['name'] . "<BR>";;
                $studentNotAllowed = true;
            }

        }

        if(($teamMemberExists || $studentNotAllowed) && $confirmTeamMemberOverride != 1) {
            $this->_helper->json(
                array(
                    'success' => 0,
                    'errorMessage' => $errorMessage
                )
            );
        }
        /**
         * do team member addition
         */
        $returnStudentData = array();
        foreach($collectionItems as $studentIdAndName) {
            $returnStudentData[$studentIdAndName['id']] = $this->addTeamMember($studentIdAndName['id']);
        }

        $this->_helper->json(
            array(
                'success' => 1,
                'data' => array(
                    'students' => $returnStudentData,
                    'possibleStudentTeamMembers' => $possibleStudentTeamMembers,
                    'studentTeamMembers' => $teamMembers
                )
            )
        );

        die();
    }

    protected function addTeamMember($studentId)
    {

        // fake like the request is coming from the student list/search page
        $params = array();
        $params['id_student_search_rows'] = '1';
        $params['search_field'] = 'id_student';
        $params['search_value'] = $studentId;
        $params['recs_per'] = '1';
        $limitToFields = array(
            'id_student',
            'id_case_mgr',
            'name_first',
            'name_last',
            'case_mgr_name_first',
            'case_mgr_name_last',
        );

        $sessUser = new Zend_Session_Namespace('user');
        $results = Model_StudentSearch::search($sessUser->id_personnel, $params);

        if (count($results) > 0) {
            /**
             * get options
             */
            $studentObj = new Model_Table_StudentTable();
            $teamMembers = $studentObj->getStudentTeamMembers($studentId);

            /**
             * if posted with a case mgr id, save and refresh info
             * $this->getRequest()->isPost() &&
             */
            if ($this->getRequest()->getParam('id_team_member') && $this->getRequest()->getParam('role')) {
                /**
                 * because we have results in the Model_StudentSearch::search fetch
                 * we know we have access and that the student exists
                 *
                 * save the team member id
                 */
                $teamMemberId = $this->getRequest()->getParam('id_team_member');
                $teamMemberExists = false;

                if (count($teamMembers)) {
                    foreach ($teamMembers as $teamMember) {
                        if ($teamMember['id_personnel'] == $teamMemberId) {
                            $teamMemberExists = true;
                        }
                    }
                }

                switch ($this->getRequest()->getParam('role')) {
                    case 'View Forms':
                        $insertData = array('flag_view' => 1);
                        break;
                    case 'Edit Forms':
                        $insertData = array('flag_edit' => 1);
                        break;
                    case 'Limit to Early Education':
                        $insertData = array('flag_ei_only' => 1);
                        break;
                }

                $studentObj->insertStudentTeamMember(
                    $studentId,
                    $teamMemberId,
                    $insertData,
                    true
                );


                /**
                 * refresh info
                 */
                $updatedStudent = $studentObj->studentInfo($studentId);
                $updatedStudent = $updatedStudent[0];
                $returnStudentData['name_student_full'] = $updatedStudent['name_student_full'];
                $returnStudentData['team_member_names'] = $updatedStudent['team_member_names'];

            }
            return true;
        }
    }

    /**
     * @param $studentId
     */
    public function assignCaseManager($studentId)
    {
// fake like the request is coming from the student list/search page
        $params = array();
        $params['id_student_search_rows'] = '1';
        $params['search_field'] = 'id_student';
        $params['search_value'] = $studentId;
        $params['recs_per'] = '1';
        $limitToFields = array(
            'id_student',
            'id_case_mgr',
            'name_first',
            'name_last',
            'case_mgr_name_first',
            'case_mgr_name_last',
        );

        $sessUser = new Zend_Session_Namespace('user');
        $results = Model_StudentSearch::search($sessUser->id_personnel, $params);

        if (count($results) > 0) {

            $fullStudentData = $results->current()->toArray();
            $returnStudentData = array_intersect_key($fullStudentData, array_flip($limitToFields));

            /**
             * get case manager options
             */
            $personnelObj = new Model_Table_PersonnelTable();
            $caseManagers = $personnelObj->getCaseManagers(
                $fullStudentData['id_county'],
                $fullStudentData['id_district'],
                $fullStudentData['id_school']
            );

            /**
             * if posted with a case mgr id, save and refresh info
             * $this->getRequest()->isPost() &&
             */
            if ($this->getRequest()->getParam('id_case_mgr')) {
                /**
                 * because we have results in the Model_StudentSearch::search fetch
                 * we know we have access and that the student exists
                 *
                 * save the new case mgr id
                 */
                $studentObj = new Model_Table_StudentTable();
                $student = $studentObj->find($studentId)->current();
                $student->id_case_mgr = $this->getRequest()->getParam('id_case_mgr');
                $student->save();
                /**
                 * refresh info
                 */
                $updatedStudent = $studentObj->studentInfo($studentId);
                $updatedStudent = $updatedStudent[0];
                $returnStudentData['id_case_mgr'] = $updatedStudent['id_case_mgr'];
                $returnStudentData['name_case_mgr'] = $updatedStudent['name_case_mgr'];
                $returnStudentData['name_student_full'] = $updatedStudent['name_student_full'];
            }
			return $returnStudentData;
//             $this->_helper->json(
//                 array(
//                     'success' => 1,
//                     'data' => array(
//                         'student' => $returnStudentData,
//                         'case_managers' => $caseManagers
//                     )
//                 )
//             );
//         } else {
//             $this->_helper->json(
//                 array(
//                     'success' => 0,
//                     'errorMessage' => 'Student not found.'
//                 )
//             );
        }
    }

    public function getCaseManagersAction() {
        $id_county = $this->getRequest()->getParam('id_county');
        $id_district = $this->getRequest()->getParam('id_district');
        $id_school = $this->getRequest()->getParam('id_school');
        if($id_county && $id_district && $id_school) {
            $personnelTable = new Model_Table_PersonnelTable();
            $this->_helper->json(
                array(
                    'success' => 1,
                    'data' => $personnelTable->getCaseManagers($id_county, $id_district, $id_school)
                )
            );
        } else {
            $this->_helper->json(
                array(
                    'success' => 0,
                    'errorMessage' => 'County, district and school are required parameters.'
                )
            );

        }
    }

    function getStudentsInCollectionAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        switch ($this->getRequest()->getParam('name')) {
            case null;
                $groupName = 'student';
                break;
            default:
                $groupName = $this->getRequest()->getParam('name');
        }

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
        $collectionItems = $studentCollection->getNames($this->usersession->sessIdUser, $groupName, isset($additionalFields)?$additionalFields:array());

//        Zend_Debug::dump($collectionItems);die;
        if (!is_null($collectionItems)) {
            echo Zend_Json::encode($collectionItems);
        } else
            echo Zend_Json::encode(array('success' => '0'));
        exit;
    }

    /**
     * function used by the checkboxes in the collection functionality
     * designed to be called via ajax and update global lists
     * add student to the collection
     */
    function addStudentToCollectionAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
//        switch ($this->getRequest()->getParam('collection')) {
//            case null:
//                $groupName = 'student';
//                break;
//            default:
//                $groupName = $this->getRequest()->getParam('collection');
//        }
        if($this->getRequest()->getParam('id') && $this->getRequest()->getParam('collectionName')) {
            // we have an id to add
            /**
             * collection of students
             */
            $studentCollection = new App_Collection_Student();
            $newCollectionItem = $studentCollection->add(
                $this->usersession->sessIdUser,
                $this->getRequest()->getParam('id'),
                $this->getRequest()->getParam('collectionName')
            );
        }
        if (isset($newCollectionItem) && !is_null($newCollectionItem)) {
            $studentObj = new Model_Table_MyStudents();
            $studentRows = $studentObj->find($this->usersession->sessIdUser, $this->getRequest()->getParam('id'));
            if(count($studentRows)) {
                $studentData = $studentRows->current()->toArray();
                $fullName = empty($studentData['name_middle']) ? $studentData['name_first'] .' '. $studentData['name_last']:$studentData['name_first'] .' '. $studentData['name_middle'] .' '. $studentData['name_last'];
                echo Zend_Json::encode(array('name'=>$fullName));
            } else {
                echo Zend_Json::encode(array('success' => '0'));
            }
        } else {
            echo Zend_Json::encode(array('success' => '0'));
        }
        exit;
    }
    /**
     * function used by the checkboxes in the collection functionality
     * designed to be called via ajax and update global lists
     * remove student from the collection
     */
    function removeStudentFromCollectionAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if (is_null($this->getRequest()->getParam('collectionName'))) {
            $groupName = 'student';
        } else {
            $groupName = $this->getRequest()->getParam('collectionName');
        }
        $studentId = $this->getRequest()->getParam('id');
        if(''!=$studentId) {
            // we have an id to remove
            /**
             * collection of students
             */
            $studentCollection = new App_Collection_Student();
            $collectionItems = $studentCollection->remove($this->usersession->sessIdUser, $studentId, $groupName);
        }
        if (!is_null($collectionItems)) {
            echo Zend_Json::encode(array('success' => '1', 'items'=>$collectionItems));
        } else
            echo Zend_Json::encode(array('success' => '0'));
        exit;
    }

    function getInfoForReplaceAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if(!$this->getRequest()->getParam('id')) {
            echo Zend_Json::encode(array('success' => '0'));
            exit;
        }

        $myStudentsObj = new Model_Table_MyStudents();
        $student = $myStudentsObj->find($this->usersession->sessIdUser, $this->getRequest()->getParam('id'));

//        Zend_Debug::dump($collectionItems);die;
        if (!is_null($student)) {
            echo Zend_Json::encode(array('success' => '1', 'data' => $student->toArray()));
        } else
            echo Zend_Json::encode(array('success' => '0'));
        exit;
    }

}
