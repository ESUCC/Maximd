<?php  
#1 Student ID need this to be the local Child ID I upload to you from SIMS
$configArr['SIMMS_ID']=array('id_student_local'=>'field');  // THIS is all  MIke for our benifit

#2 Alternate Assessment  Mostly need a yes or no if the student is on alternate assessment for NeSA.
$configArr['Alternate_Assessment'] = array( 'alternate_assessment' => 'field' ); // ok

#3 Case Manager
$configArr['CaseManager'] = array( 'caseManager' => 'function' );

#4 Primary Handicap (SRS: Primary Disability)
$configArr['PrimaryDisability'] = array( 'verifiedDisability' => 'function' );

#5 MDT Date for Diagnosis Date (see handicap record)
$configArr['MDT_Diagnose_Date'] = array( 'date_mdt' => '002-final' );

#6 Sped %
$configArr['Sped_without_Peers'] = array( 'special_ed_non_peer_percent' => '004-final' );

#7 Sped w/ Peers %
$configArr['Sped_With_Peers'] = array( 'special_ed_peer_percent' => '004-final' ); 


//#8 #9 and #10.  Should be able to grab the same on and determine a yes or no for each pending 
/*Speech Therapy Just a yes or no
Occupational Therapy Just a yes or no
Physical Therapy Just a yes or no
*/

$configArr['Occupational_Therapy']= array('booleanOcupationalTherapy'=>'function');
$configArr['PhysicalTherapy']=array('booleanPhysicalTherapy'=>'function');
$configArr['Speech_Language']=array('booleanSpeechLanguage'=>'function');


$configArr['Primary_Service'] = array( 'primaryService' => 'function' );
$configArr['related_services'] =  array( 'relatedServices' => 'function' );
/*$configArr['Speech_Therapy'] = array('related_service_drop'=>'004-final-Model_Table_Form004RelatedService');
$configArr['Occupational_Therapy'] = array('related_service_drop'=>'004-final-Model_Table_Form004RelatedService');
$configArr['Physical_Therapy'] = array('related_service_drop'=>'004-final-Model_Table_Form004RelatedService');
*/

#11
//Exit Reason Wade had me change this 1=20-2017
//$configArr['Exit_Reason']=array('describe_discontinue'=>'009-final');
$configArr['Exit_Reason']=array('sesis_exit_code'=>'field');

#12 
//Exit Date Mike changed 1-20-2016
//$configArr['Exit_Date']=array('date_notice'=>'009-final');
$configArr['Exit_Date']=array('sesis_exit_date'=>'field');
#13 
//Last IEP date
$configArr['Last_Iep']=array('date_conference'=>'004-final');

#14 Sped School ID
//$configArr('SPEdSChoolid')=array('id_school'=>'field');

#15
//Init Verification Date
$configArr['Initial_Verfication_Date']= array('initial_verification_date'=>'002-final');

#16
//Placement Type
$configArr['SPEdSChoolid']=array('pub_school_student'=>'field');

#17
//Instructional Setting
$configArr['Instructional_Setting']=array('primary_service_location'=>'004-final');



return $configArr;

// $configArr['NE_RelatedServices'] = array( 'relatedServices' => 'function' );
//$configArr['lastmdt'] = array( 'date_notice' => '002-final' );  // WE NEED THE MDT date not notice
//$configArr['primary_disability'] = array( 'verifiedDisability' => 'function' );
