<?php  
 // Request to remove put everything to 34
$configArr['PS_ID']=array('id_student_local'=>'field');  // THIS is all  MIke for our benifit
//$configArr['State_StudentNumber'] = array( 'unique_id_state' => 'field' );
$configArr['S_NE_STU_SPED_X.Alternate_Assessment'] = array( 'alternate_assessment' => 'field' ); // ok
//$configArr['U_Student_Extension.SPED.ALTDiscipline'] = array('fa_appropriate_alternative'=>'019-final');// make it a zend form. 

$configArr['U_Student_Extension.SPED_BehaviorMNGMNT'] = array('bi_behavior_management'=>'019-final');
$configArr['U_Student_Extension.SPED_BehaviorGoals'] = array('bi_behavioral_goal'=>'019-final');

$configArr['U_SPED.SPED_CaseManager'] = array( 'caseManager' => 'function' );
$configArr['U_Student_Extension.SPED_CrisisPlan'] = array('bi_crisis_intervention'=>'019-final');

$configArr['U_Student_Extension.SPED_BehaviorDescrip'] = array('fa_desc_of_problem'=>'019-final');

$configArr['U_SPED.SPED_IEP_EndDate'] = array( 'effect_to_date' => '004-final' );

$configArr['U_SPED.SPED_IEP_StartDate'] = array( 'effect_from_date' => '004-final' );

$configArr['U_SPED.SPED_MDTDate'] = array( 'date_mdt' => '002-final' );

$configArr['S_NE_STU_SPED_X.Primary_Disability'] = array( 'verifiedDisability' => 'function' );
$configArr['U_Students_Extension.SPED_Classroom_Acc'] = array('prog_mod'=>'004-final-Model_Table_Form004ProgramModifications');
//13 tp here
//$configArr['U_Student_Extension.SPED_Classroom_Acc'] = array( 'prog_mod' => '004-final-Model_Table_Form004ProgramModifications' );


  
$configArr['U_Student_Extension.SPED_Transport_Reason'] = array( 'transportation_why' => '004-final' );
$configArr['U_Student_Extension.SPED_Transport_Condition'] = array( 'transportation_desc' => '004-final' );
$configArr['Serv_Transp'] = array( 'transportation_yn' => '004-final' );

// Switch these two around
//$configArr['S_NE_STU_SPED_X.Sped_Time_nw_RegEd_Peers'] = array( 'special_ed_peer_percent' => '004-final' );
//$configArr['S_NE_STU_SPED_X.Sped_Time_w_RegEd_Peers'] = array( 'special_ed_non_peer_percent' => '004-final' );
$configArr['S_NE_STU_SPED_X.Sped_Time_nw_RegEd_Peers'] = array( 'special_ed_non_peer_percent' => '004-final' );
$configArr['S_NE_STU_SPED_X.Sped_Time_w_RegEd_Peers'] = array( 'special_ed_peer_percent' => '004-final' );

//$configArr['RegEd'] = array( 'reg_ed_percent' => '004-final' );

$configArr['U_Students_Extension.SPED_IEPStrengths'] = array( 'studentStrengths' => 'function' );


//$configArr['U_SPED.MIPS_Consent_Recvd'] = array( 'student_strengths' => '004-final' );
$configArr['U_SPED.MIPS_Consent_Recvd']= array('pg6_date_doc_signed_parent'=>'004-final');
$configArr['U_SPED_MIPS_ParentSignature'] = array( 'doc_signed_parent' => '004-final' );

// Michelle asked to remove 9-1-2016
//$configArr['U_SPED_IEP_ParentSignature'] = array( 'date_doc_signed_parent' => '004-final' );



$configArr['U_SPED.EI_Referal']=array('ei_ref_date'=>'field');


// $configArr['U_SPED.ReEval_Consent_Recvd']=array('date_notice'=>'001-final');
$configArr['U_SPED.ReEval_Consent_Recvd']=array('date_district_received'=>'007-final');
// Change Request
// added this one
$configArr['initial_Consent_Recvd']=array('date_district_received'=>'001-final');// This is new
///    ************************************** Go back to add

//$configArr['NE_alternateassessment'] = array( 'assessment_alt' => 'field' );
$configArr['NE_alternateassessment'] = array('assessment_alt'=>'004-final'); 



$configArr['related_services'] = array( 'related_service_drop' => '004-final-Model_Table_Form004RelatedService' );

// Michelle asked to change from this to the one following 9-1-2016
// $configArr['U_Student_Extension.SPED_ALTDdiscipline'] = array('fa_appropriate_alternative'=>'019-final');
$configArr['U_Student_Extension.SPED_ALTDdiscipline'] = array('bi_alternative_discipline_reason'=>'019-final');


//done above
//$configArr['U_Student_Extension.SPED_BehaviorDescrip'] = array('fa_desc_of_problem'=>'019-final');

$configArr['U_Student_Extension.SPED_BehaviorAntedcedent']= array('fa_specific_antecedents'=>'019-final');
$configArr['U_Student_Extension.SPED_FBAModifications']= array('bi_modifications'=>'019-final');

//$configArr['S_NE_STU_SPED_X.Sped_Entry_Date']= array('date_notice'=>'002-final');
$configArr['S_NE_STU_SPED_X.Sped_Entry_Date']= array('initial_verification_date'=>'002-final');

$configArr['S_NE_STU_SPED_X.Sped_Inst_Setting'] = array('primary_service_location'=>'004-final');

// IFSP Stuff('
//$configArr['U_SPED.Current_IFSP_START']=array('meeting_date'=>'013-final'); Dropedd by Mike 8-16-2016
// $configArr['U_SPED.Current_IFSP_END']=array('); Wade says 180 days from start.  NOT IN DB


// Switch these two

//$configArr['S_NE_STU_SPED_X.PrimaryService'] = array('primary_disability_drop'=>'004-final');
//$configArr['S_NE_STU_SPED_X.RelatedService'] = array('related_service_drop'=>'004-final-Model_Table_Form004RelatedService');
$configArr['S_NE_STU_SPED_X.PrimaryService'] = array('related_service_drop'=>'004-final-Model_Table_Form004RelatedService');
$configArr['S_NE_STU_SPED_X.RelatedService'] =  array('primary_disability_drop'=>'004-final');


$configArr['S_NSSRS_SERVICE_NUM']=array('nssrsServiceId'=>'function');

return $configArr;

// $configArr['NE_RelatedServices'] = array( 'relatedServices' => 'function' );
//$configArr['lastmdt'] = array( 'date_notice' => '002-final' );  // WE NEED THE MDT date not notice
//$configArr['primary_disability'] = array( 'verifiedDisability' => 'function' );
