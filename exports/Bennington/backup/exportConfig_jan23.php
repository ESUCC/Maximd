<?php  
#0 Student ID need this to be the local Child ID I upload to you from SIMS
$configArr['SIMMS_ID']=array('id_student_local'=>'field');  // THIS is all  MIke for our benifit

#1 Primary Handicap (SRS: Primary Disability)
$configArr['PrimaryDisability'] = array( 'verifiedDisability' => 'function' );

#2
//Init Verification Date
$configArr['Initial_Verfication_Date']= array('initial_verification_date'=>'002-final');

#2 MDT Date for Diagnosis Date (see handicap record)
$configArr['MDT_Diagnose_Date'] = array( 'date_mdt' => '002-final' );

#4
//Last IEP date
$configArr['Last_Iep_Date']=array('date_conference'=>'004-final');

#5
$configArr['Occupational_Therapy']= array('booleanOcupationalTherapy'=>'function');
#6
$configArr['PhysicalTherapy']=array('booleanPhysicalTherapy'=>'function');
#7
$configArr['Speech_Language']=array('booleanSpeechLanguage'=>'function');
#8
//Placement Type
$configArr['Placement_Type']=array('pub_school_student'=>'field');

#9
//Instructional Setting
$configArr['Instructional_Setting']=array('primary_service_location'=>'004-final');


#10
$configArr['Exit_Reason']=array('sesis_exit_code'=>'field');

#11
//Exit Date
$configArr['Exit_Date']=array('sesis_exit_date'=>'field');

#12 Case Manager
$configArr['CaseManager'] = array( 'caseManager' => 'function' );


#13 Sped %
$configArr['Sped_without_Peers'] = array( 'special_ed_non_peer_percent' => '004-final' );

#14 Sped w/ Peers %
$configArr['Sped_With_Peers'] = array( 'special_ed_peer_percent' => '004-final' );

#15 Alternate Assessment  Mostly need a yes or no if the student is on alternate assessment for NeSA.
$configArr['Alternate_Assessment'] = array( 'alternate_assessment' => 'field' ); // ok


return $configArr;

