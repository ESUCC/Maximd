<?php

$configArr['student_number'] = array( 'id_student_local' => 'field' );
$configArr['state_studentnumber'] = array( 'unique_id_state' => 'field' );
$configArr['lastiep'] = array( 'date_conference' => '004-final' );
$configArr['iepteam'] = array( 'participant_name' => '004-final-Model_Table_Form004TeamMember' );
$configArr['assessacc'] = array( 'assessment_desc' => '004-final' );
$configArr['accmod'] = array( 'prog_mod' => '004-final-Model_Table_Form004ProgramModifications' );
$configArr['lastmdt'] = array( 'date_notice' => '002-final' );
$configArr['primary_disability'] = array( 'verifiedDisability' => 'function' );
$configArr['NE_SpEdEntryDate'] = array( 'initial_verification_date' => '002-final' );
$configArr['SpEd_inst_setting'] = array( 'primarySetting' => 'function' );
$configArr['NE_ProgSpEdPercent'] = array( 'specialEducationPercentage' => 'function' );
$configArr['NE_alternateassessment'] = array( 'alternate_assessment' => 'field' );
$configArr['iepman'] = array( 'caseManager' => 'function' );
$configArr['strengths'] = array( 'studentStrengths' => 'function' );
$configArr['behavior'] = array( 'behavioralStrategies' => 'function' );
$configArr['assresults'] = array( 'resultsPerf' => 'function' );
$configArr['NE_RelatedServices'] = array( 'relatedServices' => 'function' );
$configArr['rule51'] = array( 'presentLevPerf' => 'function' );
$configArr['resclass'] = array( 'describe_program' => '005-final' );

return $configArr;
