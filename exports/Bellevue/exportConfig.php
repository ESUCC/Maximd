<?php
// 0
$configArr['PS_ID']=array('id_student_local'=>'field');  // THIS is all  MIke for our benifit
//1
$configArr['S_NE_STU_SPED_X.Alternate_Assessment'] = array( 'alternate_assessment' => 'field' ); // ok
//2
$configArr['U_SPED.SPED_CaseManager'] = array( 'caseManager' => 'function' );
//3
$configArr['U_SPED.SPED_IEP_EndDate'] = array( 'effect_to_date' => '004-final' );
//4
$configArr['U_SPED.SPED_IEP_StartDate'] = array( 'effect_from_date' => '004-final' );
//5
$configArr['U_SPED.SPED_MDTDate'] = array( 'date_mdt' => '002-final' );
//6
$configArr['S_NE_STU_SPED_X.Primary_Disability'] = array( 'verifiedDisability' => 'function' );
//7
$configArr['U_Student_Extension.SPED_Transport_Reason'] = array( 'transportation_why' => '004-final' );
//8
$configArr['U_Student_Extension.SPED_Transport_Condition'] = array( 'transportation_desc' => '004-final' );
//9
$configArr['Serv_Transp'] = array( 'transportation_yn' => '004-final' );
//10
$configArr['S_NE_STU_SPED_X.Sped_Time_nw_RegEd_Peers'] = array( 'special_ed_non_peer_percent' => '004-final' );
//11
$configArr['S_NE_STU_SPED_X.Sped_Time_w_RegEd_Peers'] = array( 'special_ed_peer_percent' => '004-final' );
//12
//Changed this 11-21-2017 as per SRS-135 #1
$configArr['U_Students_Extension.SPED_IEPStrengths'] = array( 'student_strengths' => '004-final' );
//$configArr['U_Students_Extension.SPED_IEPStrengths'] = array( 'studentStrengths' => 'function' );
//13
$configArr['U_SPED.MIPS_Consent_Recvd']= array('pg6_date_doc_signed_parent'=>'004-final');
//14
$configArr['U_SPED_MIPS_ParentSignature'] = array( 'doc_signed_parent' => '004-final' );
//15
$configArr['U_SPED.EI_Referal']=array('ei_ref_date' => 'field');
//16
$configArr['U_SPED.ReEval_Consent_Recvd']=array('date_district_received'=>'007-final');
//17
$configArr['initial_Consent_Recvd']=array('date_district_received'=>'001-final');// This is new
//18
//$configArr['NE_alternateassessment'] = array('assessment_alt'=>'004-final');
//$configArr['U_SPED.AT_Considerations']=array('student_strengths'=>'004-final');
$configArr['U_SPED.AT_Considerations']=array('assistive_tech'=>'004-final');


//19  the troubleing one
//$configArr['related_services'] = array( 'related_service_drop' => '004-final-Model_Table_Form004RelatedService' );
$configArr['related_services2'] =  array( 'relatedServices2' => 'function' );
//20
$configArr['S_NE_STU_SPED_X.Sped_Entry_Date']= array('initial_verification_date'=>'002-final');
//21
// Mike made a new function in BellevueExport.php that will take into account iepdatacards and ifsps.
//$configArr['S_NE_STU_SPED_X.Sped_Inst_Setting'] = array('primary_service_location'=>'004-final');
$configArr['S_NE_STU_SPED_X.Sped_Inst_Setting'] = array('relatedServiceMultipleForms'=>'function');

// Start of Mike Change on 10-31-2016
//$configArr['S_NE_STU_SPED_X.PrimaryService'] = array('related_service_drop'=>'004-final-Model_Table_Form004RelatedService');
//22  page 6 as per wade 1-23-2017
$configArr['S_NE_STU_SPED_X.PrimaryService'] = array('primary_disability_drop'=>'004-final');
//23
$configArr['S_NE_STU_SPED_X.RelatedService'] =  array('relatedServices' => 'function');
//24
$configArr['S_NSSRS_SERVICE_NUM']=array('nssrsServiceId'=>'function');

// end of Mike Change 10-31-2016:
//25  S_NE_STU_SPED_X.Prog_SPED_Percent  add this for Michell Ande 1-9-2016
$configArr['2S_NE_STU_SPED_X.Sped_Time_w_RegEd_Peers'] = array( 'special_ed_peer_percent' => '004-final' );
//26
$configArr['U_Student_Extension.SPED_BehaviorMNGMNT'] = array('bi_behavior_management'=>'019-final');
//27
$configArr['U_Student_Extension.SPED_BehaviorGoals'] = array('bi_behavioral_goal'=>'019-final');
//28
$configArr['U_Student_Extension.SPED_CrisisPlan'] = array('bi_crisis_intervention'=>'019-final');
//29
$configArr['U_Student_Extension.SPED_BehaviorDescrip'] = array('fa_desc_of_problem'=>'019-final');
//30
$configArr['U_Student_Extension.SPED_ALTDdiscipline'] = array('bi_alternative_discipline_reason'=>'019-final');
//31
$configArr['U_Student_Extension.SPED_BehaviorAntedcedent']= array('fa_specific_antecedents'=>'019-final');
//32
$configArr['U_Student_Extension.SPED_FBAModifications']= array('bi_modifications'=>'019-final');
//33
$configArr['U_Students_Extension.SPED_Classroom_Acc'] = array('prog_mod'=>'004-final-Model_Table_Form004ProgramModifications');

return $configArr;

// $configArr['NE_RelatedServices'] = array( 'relatedServices' => 'function' );
//$configArr['lastmdt'] = array( 'date_notice' => '002-final' );  // WE NEED THE MDT date not notice
//$configArr['primary_disability'] = array( 'verifiedDisability' => 'function' );
