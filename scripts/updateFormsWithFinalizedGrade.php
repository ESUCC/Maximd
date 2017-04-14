<?php
require_once('cmd_line_helper.php');
bootCli(APPLICATION_PATH, APPLICATION_ENV);
/** zend application now loaded */

/**
 * student grades
 */
$gradesLabel = array("EI 0-2", "ECSE (Age 3-5)", "K", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "12+");
$gradesValue = array("EI 0-2", "ECSE", "K", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "12+");

/**
 * loop over forms
 */
for($i = 1; $i <= 24; $i++) {
    $formNum = substr('00'.$i, -3, 3);
    $modelName = 'Model_Table_Form'.$formNum;
    $model = new $modelName();

    $select = $model->select()
        ->where('status = ?', 'Final')
        ->where('finalized_date is not null')
        ->order('finalized_date');
    echo $select . "\n";
    $finalForms = $model->fetchAll($select);
    Zend_Debug::dump($finalForms->toArray());

    foreach ($finalForms as $form) {
        /**
         * build the age
         */

    }

}


//$ageAtFinalize = duration(finalized_date - dob);
//	$grade = getGrade($ageAtFinalize);
//	update form with grade
//
echo "\ncomplete\n\n";


