<?php
error_reporting(E_ALL);

/**
 * general setup of the application
 */
require_once('../../cmd_line_helper.php');
bootCli(APPLICATION_PATH, APPLICATION_ENV);

$vars = ZendJobQueue::getCurrentJobParams();

if(!isset($vars['students'])) {
    $vars = array (
        "students" => array (
            0 => array (
                "id" => 1136578,
                "name" => 'Michael Paul  Porter',
                "PHPSESSID" => "866aruom838t3gvj449vlff6r3"
            ),
        ),
        "sessIdUser" => 1000254,
        "id_county" => "99",
        "id_district" => "9999",
        "id_school" => "996",
    );
}

if(isset($vars['students'])) {

    $studentTable = new Model_Table_StudentTable();
    $uniqueSchools = array();
    foreach($vars['students'] as $passedStudent) {
        $studentInfo = $studentTable->getInfo($passedStudent['id']);
        $cds = $studentInfo['id_county'] . $studentInfo['id_district'] . $studentInfo['id_school'];
        if(!isset($uniqueSchools[$cds])) {
            $uniqueSchools[$cds] = array(
                'student_count'=>0,
                'student_name_list'=>'',
                'students' => array()
            );
        }
        $uniqueSchools[$cds]['student_count'] += 1;
        $uniqueSchools[$cds]['student_name_list'] .= $studentInfo['name_student_full'] . "\n";
        $uniqueSchools[$cds]['students'][] = array(
            'id_county_from'=>$studentInfo['id_county'],
            'id_district_from'=>$studentInfo['id_district'],
            'id_school_from'=>$studentInfo['id_school'],
        );
    }

    $transferTable = new Model_Table_TransferRequest();
    foreach($uniqueSchools as $uniqueSchool) {
        $transferTable->insertStudentTransferRequest(
            $vars['sessIdUser'],
            $uniqueSchool['id_county_from'], $uniqueSchool['id_district_from'], $uniqueSchool['id_school_from'],
            $vars['id_county'], $vars['id_district'], $vars['id_school'],
            $uniqueSchool['student_count'],
            $uniqueSchool['student_id_list'],
            $uniqueSchool['student_name_list'],
            'request'
        );
    }

}

