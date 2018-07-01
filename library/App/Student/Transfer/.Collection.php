<?php


$myfile = fopen("TEST.txt","w");
        $tt ="John Doe\n";
        fwrite($myfile,$tt);
        fclose($myfile);
class App_Student_Transfer_Collection extends Zend_Acl
{
    public $uniqueSchools = array();
    public $notificationMessage = '';

    public function transferCollection($studentList, $idCounty, $idDistrict, $idSchool, $sessIdUser, $autoMoveForAsmOrBetter=true)
    {
        $studentTable = new Model_Table_StudentTable();
        $uniqueSchools = array();
        foreach($studentList as $passedStudent) {
            $studentsInfo = $studentTable->studentInfo($passedStudent['id']);
            $studentInfo = $studentsInfo[0];
            $cds = $studentInfo['id_county'] . $studentInfo['id_district'] . $studentInfo['id_school'];
            if(!isset($uniqueSchools[$cds])) {
                $uniqueSchools[$cds] = array(
                    'student_count'=>0,
                    'student_name_list'=>'',
                    'student_id_list' => '',
                    'id_county'=>$studentInfo['id_county'],
                    'id_district'=>$studentInfo['id_district'],
                    'id_school'=>$studentInfo['id_school'],
                );
            }
            $uniqueSchools[$cds]['student_count'] += 1;

            if(strlen($uniqueSchools[$cds]['student_name_list'])>0) {
                $uniqueSchools[$cds]['student_name_list'] .= ", ";
            }
            $uniqueSchools[$cds]['student_name_list'] .= $studentInfo['name_student_full'];

            if(strlen($uniqueSchools[$cds]['student_id_list'])>0) {
                $uniqueSchools[$cds]['student_id_list'] .= "||";
            }
            $uniqueSchools[$cds]['student_id_list'] .= $studentInfo['id_student'];
        }

        $transferRequestsTable = new Model_Table_TransferRequest();
        $insertedTransferRequestIds = array();
        foreach($uniqueSchools as $uniqueSchool) {
            $insertedTransferRequestIds[] = $transferRequestsTable->insertStudentTransferRequest(
                $sessIdUser,
                $uniqueSchool['id_county'], $uniqueSchool['id_district'], $uniqueSchool['id_school'],
                $idCounty, $idDistrict, $idSchool,
                $uniqueSchool['student_count'],
                "|" . $uniqueSchool['student_id_list'] . "|",
                $uniqueSchool['student_name_list'],
                'initiate'
            );
        }

        /**
         * auto-confirm transfers if requested
         */
        if('true'==$autoMoveForAsmOrBetter) {
            foreach($insertedTransferRequestIds as $transferRequestId) {
                $transferRequestsTable->confirmTransferRequest($transferRequestId);
            }
        }

        $this->uniqueSchools = $uniqueSchools;
        $this->notificationMessage = $transferRequestsTable->buildNotifictionFromTransferRequestIds($insertedTransferRequestIds);

        return true;
    }

}
