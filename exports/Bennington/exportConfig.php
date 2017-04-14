<?php  
#0 for Mikes benifit
$configArr['SRS_STudent_ID']=array('id_student'=>'field');
#1 Anna debug
$configArr['Name_First']=array('name_first'=>'field');
#2 Anna Debug
$configArr['Name_Last']=array('name_last'=>'field');
#3  Anna Debug
$configArr['Date_Of_Birth']=array('dob'=>'field');

#4 Student ID need this to be the local Child ID I upload to you from SIMS
$configArr['SIMMS_ID']=array('id_student_local'=>'field');  // THIS is all  MIke for our benifit

#5 Primary Handicap (SRS: Primary Disability)
$configArr['PrimaryDisability'] = array( 'verifiedDisability' => 'function' );

#6
//Init Verification Date
$configArr['Initial_Verfication_Date']= array('initial_verification_date'=>'002-final');

#7 MDT Date for Diagnosis Date (see handicap record)
$configArr['MDT_Diagnose_Date'] = array( 'date_mdt' => '002-final' );

#8
//Last IEP date
$configArr['Last_Iep_Date']=array('date_conference'=>'004-final');

#9
$configArr['Occupational_Therapy']= array('booleanOcupationalTherapy'=>'function');
#10
$configArr['PhysicalTherapy']=array('booleanPhysicalTherapy'=>'function');
#11
$configArr['Speech_Language']=array('booleanSpeechLanguage'=>'function');
#12
//Placement Type
$configArr['Placement_Type']=array('pub_school_student'=>'field');

#13
//Instructional Setting
$configArr['Instructional_Setting']=array('primary_service_location'=>'004-final');


#14
$configArr['Exit_Reason']=array('sesis_exit_code'=>'field');

#15
//Exit Date
$configArr['Exit_Date']=array('sesis_exit_date'=>'field');

#16 Case Manager
$configArr['CaseManager'] = array( 'caseManager' => 'function' );


#17 Sped %
$configArr['Sped_without_Peers'] = array( 'special_ed_non_peer_percent' => '004-final' );

#18 Sped w/ Peers %
$configArr['Sped_With_Peers'] = array( 'special_ed_peer_percent' => '004-final' );

#19 Alternate Assessment  Mostly need a yes or no if the student is on alternate assessment for NeSA.
$configArr['Alternate_Assessment'] = array( 'alternate_assessment' => 'field' ); // ok

#20 Most Recent ifsp form 013.  NOTE: none of the 013s work. Have to do it by hand before in factorExport
# 2-28-2017
# $exportLine[20]=$this->getForm013MeeingDate($student->id_student);            
# file_put_contents($exportPath, $this->arrayToCsv($exportLine, "\t", '"', true) . $this->eol, FILE_APPEND);
$configArr['Most_recent_ifsp_date'] = array('meeting_date'=> '013-final');
return $configArr;

// $configArr['NE_RelatedServices'] = array( 'relatedServices' => 'function' );
//$configArr['lastmdt'] = array( 'date_notice' => '002-final' );  // WE NEED THE MDT date not notice
//$configArr['primary_disability'] = array( 'verifiedDisability' => 'function' );
