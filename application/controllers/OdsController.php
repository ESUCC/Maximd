<?php

class OdsController extends Zend_Controller_Action
{
    function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }

  public function indexAction(){

  }

  function districtodslistAction(){
      $sysAdmin=false;

      foreach($_SESSION['user']['user']->privs as $priv)  {
          if ($priv['class']==1 && $priv['status']=='Active') $sysAdmin=true;

      }

      if($sysAdmin==true){
      $getToUpdateList=new Model_Table_Edfi();

      $results=$getToUpdateList->getDistrictW();
      foreach ($results as $r ){

         $this->advisorsetAction($r['id_county'],$r['id_district'],1);
        }
      $this->view->districtList=$results;
      }
      else {
          $this->_redirect( '/district');
      }
  }
  function getJuneCutoff()
  {
      if (date("m", strtotime("today")) >= 7) {
          $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today"))));
      } else {
          $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today")) . "-1 year"));
      }

      return $juneCutoff;
  }

  public function advisorsetAction($id_county=0,$id_district=0,$cronjob=0) {

      // Check to see if they have publish to advisor set

      $iep=new Model_Table_Form004();
      $ifsp=new Model_Table_Form013();
      $iepCard=new Model_Table_Form023();
      $relatedServices=new Model_Table_Form004RelatedService();

      // Mike put this in 5-17-2018 SRS-235 cronjob being equal to 1 means it is a cronjob
      if($cronjob!=1){
      $id_county=$this->getRequest()->getParam('id_county');
      $id_district=$this->getRequest()->getParam('id_district');
      }


      $dist=new Model_Table_District();

     //$this->writevar1($id_county,'the county number ');
    //  $this->writevar1($id_district,'the district number ');


      if($dist->getDistrictUseEdfi($id_district,$id_county)!=true and $cronjob!=1){


       $this->_redirect( '/district/edfidetail2/id_district/'.$id_district.'/id_county/'.$id_county);

      }

      $checkForEdfiTable= new Model_Table_Edfi();
      $listStudents = new Model_Table_StudentTable();


      /*
       * Need to check the edfi table based on id_county and id_district
       * compare that against id_county id_districts of student in the iep_students table.
       * check to see if they match,
       * if they don't set edfipublishstatus to "T".
       */

      $allTableEntries=$checkForEdfiTable->returnAllTableEntries($id_county, $id_district);
      foreach ($allTableEntries as $tableEntry){
       //   $this->writevar1($tableEntry['id_student'],'here is the student id');
          $stu=$listStudents->getOneStudent($tableEntry['id_student']);

          if($tableEntry['id_county']!= $stu[0]['id_county']|| $tableEntry['id_district']!=$stu[0]['id_district']){
             $checkForEdfiTable->modifyEdfiTransfer($tableEntry['id_edfi_entry'],1);
          }

          if($tableEntry['id_county']== $stu[0]['id_county'] and  $tableEntry['id_district']==$stu[0]['id_district'] and
             $tableEntry['edfipublishstatus']=='T'){
                 $checkForEdfiTable->modifyEdfiTransfer($tableEntry['id_edfi_entry'],2);
          }

        //  if($tableEntry['publishstatus']=='T' )
      }






      $juneCutoff=$this->getJuneCutoff();
      // Mike added this 4-13-2018 SRS-221 so that the button to include in state report works from the edit screen.
      $includeInStateReport='1';
      $districtStudents=$listStudents->studentsInDistrict($id_county,$id_district,$juneCutoff,$includeInStateReport);





      // Mike added 10-11-2017 in order to get a list of the schools.
      $schoolList=new Model_Table_IepSchoolm();
      $schools=$schoolList->getIepSchoolInfo($id_county,$id_district);

     // This loop ends at line 446
      foreach($districtStudents as $student) {




      // CHeck to see if there already is an edfi entry
       $edfiEntry=$checkForEdfiTable->returnTableEntry($student['id_student']);


       $transfer=false;




    if($edfiEntry['edfipublishstatus']=='W'|| $edfiEntry['edfipublishstatus']=='E'|| $edfiEntry==Null) {



     // Take this out when ready to run the whole thing.
  //   if($student['id_student']=='1473206') {
      $continue=true;

      $advisorStudentData='';
      //
      foreach($schools as $school){
          if($school['id_school']==$student['id_school']){
              $advisorStudentData['name_school']=$school['name_school'];
          }
      }

      /*
       * Mike added this functionality so that we could track the forms that have errors
       * in them. 10-12-2017
       */

      $advisorStudentData['id_county']=$student['id_county'];
      $advisorStudentData['id_district']=$student['id_district'];
      $advisorStudentData['id_school']=$student['id_school'];
      $advisorStudentData['name_first']=$student['name_first'];
      $advisorStudentData['name_last']=$student['name_last'];
      $advisorStudentData['mdt_code']='NOMDT';
      $advisorStudentData['iep_ifsp_code']='noiepifsp';
      $advisorStudentData['mdt_id']=1000;
      $advisorStudentData['iep_ifsp_id']=1000;



     $advisorStudentData['educationOrganizationID']=$student['id_county'].$student['id_district'];
   //   $this->writevar1($advisorStudentData['educationOrganizationID'],'id ofthe school');
     // Took this out 9-14-2017
     // $advisorStudentData['educationOrganizationID']='255901';

     // $advisorStudentData['studentUniqueID']=$student['unique_id_state'];


      // took this out 9-14-2017 $advisorStudentData['studentUniqueID']=$student['tempid'];
      // Put this in 9-14-2017
      $advisorStudentData['studentUniqueID']=$student['unique_id_state'];

      $advisorStudentData['id_student']=$student['id_student'];

      $ps=$student['sesis_exit_code'];
      if(strlen($ps)==1){
          $ps="0".$ps;
          $advisorStudentData['reasonExitedDescriptor'] =$ps;
       }
      else {
      $advisorStudentData['reasonExitedDescriptor']=$student['sesis_exit_code'];
      }
      //$this->writevar1($advisorStudentData['reasonExitedDescriptor'],'this is the reson exited line 117 ods controller');

      $advisorStudentData['enddate']=$student['sesis_exit_date'];
      if($student['alternate_assessment']==null ) {
          $advisorStudentData['toTakeAlternateAssessment']=0;
          // This might have to be set to 2 by state requiremnet.  Not sure
          // 10-9-2017
      }

      if($student['alternate_assessment']==true ) {
          $advisorStudentData['toTakeAlternateAssessment']=1;
      }

      if($student['alternate_assessment']==false ) {
          $advisorStudentData['toTakeAlternateAssessment']=0;
         if($student['grade']< "3") $advisorStudentData['toTakeAlternateAssessment']="2";
      }

      $advisorStudentData['beginDate']=null;
      $advisorStudentData['specialEducationPercentage']=0;
      //$advisorStudentData['specialEducationSettingDescriptor']='00';
      $advisorStudentData['disabilities']='0';
      $advisorStudentData['levelOfProgramParticipationDescriptor']=0;




      if($student['pub_school_student']==false) {

      $advisorStudentData['placementtypedescriptor']=$student['parental_placement'];
   //  $this->writevar1($student['id_student']." ".$student['parental_placement'],' this is the parental placement');
      }
        else {
            $advisorStudentData['placementtypedescriptor']='00';
        }

      /*Form 002 find the beginDate in the mdt form.  It will either be in
       * the mdt card latest or the form002 latest for the child
      */
      $stuId=$student['id_student'];

      $lastMdt=new Model_Table_Form002();
      $mostRecentMdt=$lastMdt->getMostRecentMDT($stuId);

      $lastMdtCard= new Model_Table_Form022();
      $mostRecentMdtCard=$lastMdtCard->getMostRecentMdtCard($stuId);



      // This will use the MDT because no MDT card
       if($mostRecentMdtCard==null && $mostRecentMdt!=null) {




           $primaryDisability=$this->getMdtCode($mostRecentMdt[0]['disability_primary']);
           $advisorStudentData['disabilities']=$primaryDisability;


          //Mike changed this 5-9-2018 because begin date must be initial verification date

           //$advisorStudentData['beginDate']=$mostRecentMdt[0]['date_mdt'];
           $advisorStudentData['beginDate']=$mostRecentMdt[0]['initial_verification_date'];



           $advisorStudentData['mdt_id']=$mostRecentMdt[0]['id_form_002'];
           $advisorStudentData['mdt_code']='form002';
       }

       if($mostRecentMdt==null && $mostRecentMdtCard!=null) {
           // $this->writevar1($mostRecentMdt,'this is the most mdt card');
           $primaryDisability=$this->getMdtCode($mostRecentMdtCard[0]['disability_primary']);
           $advisorStudentData['disabilities']=$primaryDisability;

           // Mike changed this 5-9-2018 because the start date was incorrect
         //  $advisorStudentData['beginDate']=$mostRecentMdtCard[0]['date_mdt'];
           $advisorStudentData['beginDate']=$mostRecentMdtCard[0]['initial_verification_date'];

           $advisorStudentData['mdt_id']=$mostRecentMdt[0]['id_form_022'];
           $advisorStudentData['mdt_code']='form022';

       }

       if($mostRecentMdt!=null && $mostRecentMdtCard!=null ){

           $result= $this->decideWhereToGetData($mostRecentMdt, $mostRecentMdtCard);
           $advisorStudentData['disabilities']=$result['disabilities'];
           $advisorStudentData['beginDate']=$result['beginDate'];
           $advisorStudentData['mdt_code']=$result['mdt_code'];
           $advisorStudentData['mdt_id']=$result['mdt_id'];
           //$this->writevar1($result,'this i the array result for both mdts');
      }



       if($mostRecentMdt==null && $mostRecentMdtCard==null && $ifsp==null ) {
           $continue=false;

       }


       // No need to go out to the database if there is no mdt or mdt card
      // changed this 9-14-2017 from 'tempid'
       if($student['unique_id_state']<='1000000000'){
         //  $this->writevar1($student['unique_id_state'],' id of the state');
           $continue=false;
       }



      if($continue==true)   {   // end is down at 449 or so
       $mostRecentIep=null;
       $mostRecentIepCard=null;
       $mostRecentIfsp=null;

       $mostRecentIep=$iep->getMostRecentIepState($stuId);
       $mostRecentIfsp=$ifsp->getMostRecentIfspState($stuId);
       $mostRecentIepCard=$iepCard->getMostRecentIepCardState($stuId);

     // $this->writevar1($continue,'this is the value of continue line 227');


  if (($mostRecentIfsp!=null || $mostRecentIep!=null || $mostRecentIepCard!=null) && $continue==true) {

   //    $this->writevar1($mostRecentIepCard,'this is the most recent iep card line 232');
      $advisorStudentData['serviceDescriptor_ot']=0;
      $advisorStudentData['serviceBeginDate_ot']=null;
      $advisorStudentData['serviceDescriptor_pt']=0;
      $advisorStudentData['serviceBeginDate_pt']=null;
      $advisorStudentData['serviceDescriptor_slt']=0;
      $advisorStudentData['serviceBeginDate_slt']=null;







           if ($mostRecentIfsp!=null and $mostRecentIep==null and $mostRecentIepCard==null ){
               $advisorStudentData['section']='ONLY HAS IFSP';
               $decidedOnForm=$mostRecentIfsp;

               // Mike changed this 4-3-2018 as per wades request to flip.  SRS-212
              // $advisorStudentData['levelOfProgramParticipationDescriptor']='05';
               $advisorStudentData['levelOfProgramParticipationDescriptor']='06';





               $form013=$ifsp->getMostRecentIfspState($stuId);
               $id_form013=$form013[0]['id_form_013'];

               $advisorStudentData['iep_ifsp_code']='form013';
               $advisorStudentData['iep_ifsp_id']=$id_form013;
               //$serviceifsp=new Model_Table_Form013Services();
               //$serviceDescription=$serviceifsp->getIfspServicesState($id_form013);

               $serviceifsp=new Model_Table_Form013Services();
               $serviceDescription=$serviceifsp->getIfspServicesState($id_form013);


               /* Mike had to add this because the services for  specialEducationSettingDescriptor
                are in table ifsp_services
                Need to use Form013Services.php
                */
               //$this->writevar1($serviceDescription['specialeducationsettingdescriptor'],'this is the form set descript out of hte db');


                $edSetDescript='';
                  switch($serviceDescription['specialeducationsettingdescriptor']){

                      case 'Home':$edSetDescript='01';break;
                      case 'Community':$edSetDescript='02';break;
                      case 'Other':$edSetDescript='03';break;
                  }
                  $advisorStudentData['specialeducationsettingdescriptor']=$edSetDescript;


               $advisorStudentData['serviceDescriptor_ot']=$serviceDescription['serviceDescriptor_ot'];
               $advisorStudentData['serviceBeginDate_ot']=$serviceDescription['serviceBeginDate_ot'];
               $advisorStudentData['serviceDescriptor_pt']=$serviceDescription['serviceDescriptor_pt'];;
               $advisorStudentData['serviceBeginDate_pt']=$serviceDescription['serviceBeginDate_pt'];;
               $advisorStudentData['serviceDescriptor_slt']=$serviceDescription['serviceDescriptor_slt'];
               $advisorStudentData['serviceBeginDate_slt']=$serviceDescription['serviceBeginDate_slt'];;


             //  $this->writevar1($advisorStudentData,'this is the advisordata entry');
           }




         if($mostRecentIepCard==null and  $mostRecentIep!=null){



             $advisorStudentData['section']='ONLY HAS IEP';
             $fm=$mostRecentIep;
           // mike changed this 4-3-2018 SRS-212
           //  $advisorStudentData['levelOfProgramParticipationDescriptor']='06';
             $advisorStudentData['levelOfProgramParticipationDescriptor']='05';
             $advisorStudentData['specialEducationPercentage']=$fm[0]['special_ed_non_peer_percent'];

             $advisorStudentData['iep_ifsp_code']='form004';
             $advisorStudentData['iep_ifsp_id']=$fm[0]['id_form_004'];
             /*
              * Mike added the following check 6-17-2017 because in some instances it is coming out as
              * a single character and NDE will not accept that.
              *
              */
              $ps=$fm[0]['primary_service_location'];
              if(strlen($ps)==1){
                  $ps="0".$ps;
                  $fm[0]['primary_service_location']=$ps;
              }
              // End of mike check

              $advisorStudentData['specialeducationsettingdescriptor']=$fm[0]['primary_service_location'];


              if ($fm[0]['primary_disability_drop']=='Occupational Therapy Services'||
                  $fm[0]['primary_disability_drop']=='Occupational Therapy'){
                  $advisorStudentData['serviceDescriptor_ot']='1';
                  $advisorStudentData['serviceBeginDate_ot']=$fm[0]['primary_service_from'];
              }
              else {
                  $advisorStudentData['serviceDescriptor_ot']='0';
              }


              if ($fm[0]['primary_disability_drop']=='Physical Therapy'){
                  $advisorStudentData['serviceDescriptor_pt']='2';
                  $advisorStudentData['serviceBeginDate_pt']=$fm[0]['primary_service_from'];
              }
              else {
                  $advisorStudentData['serviceDescriptor_pt']='0';
              }



              if ($fm[0]['primary_disability_drop']=='Speech-language therapy'||
                  $fm[0]['primary_disability_drop']=='Speech/Language Therapy'){
                  $advisorStudentData['serviceDescriptor_slt']='3';
                  $advisorStudentData['serviceBeginDate_slt']=$fm[0]['primary_service_from'];

              }
              else {
                  $advisorStudentData['serviceDescriptor_slt']='0';
              }




              $result=$relatedServices->getRelatedServicesState($fm[0]['id_form_004']);

            if($result!=null) {
             if ($result['serviceDescriptor_ot']=='1'){
                 $advisorStudentData['serviceDescriptor_ot']='1';
                 $advisorStudentData['serviceBeginDate_ot']=$result['serviceBeginDate_ot'];
               }
               else {
                   $advisorStudentData['serviceDescriptor_ot']='0';
               }

              if ($result['serviceDescriptor_pt']=='2'){
                   $advisorStudentData['serviceDescriptor_pt']='2';
                   $advisorStudentData['serviceBeginDate_pt']=$result['serviceBeginDate_pt'];
               }
               else {
                   $advisorStudentData['serviceDescriptor_pt']='0';
               }

               if ($result['serviceDescriptor_slt']=='3'){
                   $advisorStudentData['serviceDescriptor_slt']='3';
                   $advisorStudentData['serviceBeginDate_slt']=$result['serviceBeginDate_slt'];
               }
               else {
                   $advisorStudentData['serviceDescriptor_slt']='0';
               }


            } // end of check for related services form form_004_related_services
         //  $this->writevar1($result,' data after decision');

          }   // end of the existence of an iep only.




         if($mostRecentIepCard !=null and $mostRecentIep==null){
             $advisorStudentData['section']='ONLY HAS IEP CARD';

             $advisorStudentData['iep_ifsp_code']='form023';
             $advisorStudentData['iep_ifsp_id']=$mostRecentIepCard[0]['id_form_023'];




              $card=$mostRecentIepCard;

              $advisorStudentData['levelOfProgramParticipationDescriptor']='06';
              $advisorStudentData['specialEducationPercentage']=$card[0]['special_ed_non_peer_percent'];

              if($card[0]['service_where']=='1')$card[0]['service_where']='01';
              $advisorStudentData['specialeducationsettingdescriptor']=$card[0]['service_where'];


          //    $this->writevar1($card[0]['service_where'],'student only has iep card line 419');

            //  if ($fm[0]['id_student']=='1345747')
             //     $this->writevar1($fm[0],' id of student iepcard'.$fm[0]['id_student']);

            // Mike put this in 4-3-2018 per Wades request SRS-215


            if ($card[0]['service_ot']==true){
                $advisorStudentData['serviceDescriptor_ot']='1';
            } else {
                    $advisorStudentData['serviceDescriptor_ot']='0';
                }
            if ($card[0]['service_pt']==true){
                $advisorStudentData['serviceDescriptor_pt']='2';
            }
                else {
                    $advisorStudentData['serviceDescriptor_pt']='0';
                }
             if ($card[0]['service_slt']==true){
                    $advisorStudentData['serviceDescriptor_slt']='3';
                }
                else {
                    $advisorStudentData['serviceDescriptor_slt']='0';
                }

          }




         if($mostRecentIep!=null and $mostRecentIepCard!=null ) {

         //   $this->writevar1($mostRecentIepCard,'this is the most recent iep card line 410');

             $advisorStudentData['section']='HAS BOTH IEP AND IEPCARD';
           // Mike changed this 4-3-2018 as  per wades clarification  SRS-212
         //    $advisorStudentData['levelOfProgramParticipationDescriptor']='06';
             $advisorStudentData['levelOfProgramParticipationDescriptor']='05';

            $result=$this->decideWhereToGetIepData($mostRecentIep,$mostRecentIepCard);
           // $this->writevar1($result,'this is the result for one student line 416 ods controller');
          //  $this->writevar1($result,' data after decision');
            $advisorStudentData['iep_ifsp_code']=$result['iep_ifsp_code'];
            $advisorStudentData['iep_ifsp_id']=$result['iep_ifsp_id'];

            $advisorStudentData['specialEducationPercentage']=$result['specialeducationpercentage'];


       //     $advisorStudentData['iep_ifsp_code']=
        //    $advisorStudentData['iep_ifsp_id']=




            $advisorStudentData['serviceDescriptor_ot']=$result['serviceDescriptor_ot'];
            $advisorStudentData['serviceBeginDate_ot']=$result['serviceBeginDate_ot'];

            $advisorStudentData['serviceDescriptor_pt']=$result['serviceDescriptor_pt'];
            $advisorStudentData['serviceBeginDate_pt']=$result['serviceBeginDate_pt'];


            $advisorStudentData['serviceDescriptor_slt']=$result['serviceDescriptor_slt'];
            $advisorStudentData['serviceBeginDate_slt']=$result['serviceBeginDate_slt'];

            if($advisorStudentData['serviceDescriptor_ot']!='1')$advisorStudentData['serviceDescriptor_ot']='0';
            if($advisorStudentData['serviceDescriptor_pt']!='2')$advisorStudentData['serviceDescriptor_pt']='0';
            if($advisorStudentData['serviceDescriptor_slt']!='3')$advisorStudentData['serviceDescriptor_slt']='0';

            $advisorStudentData['specialeducationsettingdescriptor']=$result['specialeducationsettingdescriptor'];


       //     if($advisorStudentData['studentUniqueID']=='1194008801')$this->writevar1($advisorStudentData,'this is the advisor student data line 441');
          //  if ($advisorStudentData['id_student']=='1345747')
        //   $this->writevar1($result,'this is the id of the student  for 2 nulls'.$result['specialEducationSettingDescriptor']);

         } // end of getting values if using iep



       }  // end of the if continue

       if (($mostRecentIfsp!=null || $mostRecentIep!=null || $mostRecentIepCard!=null)
           && $continue==true && $advisorStudentData['studentUniqueID']>='600000') {

         $advisorStudentData['edfiPublishStatus']='W';
         $advisorStudentData['edfiResultCode']=null;
         $advisorStudentData['edfiPublishTime']=date("Y-m-d");
     //    $advisorStudentData['id_author']=$_SESSION["user"]["user"]->user["id_personnel"];
           $advisorStudentData['id_author']=0;

        // if($advisorStudentData['id_student']=='1384091') $this->writevar1($advisorStudentData,'this is the advisor at the end line 381');
          $insert=new Model_Table_Edfi();

         $insert->setupAdvisor($advisorStudentData);




                       //  $this->_redirect('/#');
         }  //this is the end of publishing to the datastore 376


      } // end of the if continue line 218 if continue==true

  //    } // Take this out when ready to run the whole thing

   } //if($edfiEntry['edfipublishstatus']!='S') {  this entry will only go through those without an S
} // end of the for loop way up there searching for each districts students

     $insertEdfi=new Model_EdfiOds();

     $insertEdfi->receiveFromOdsController($id_county,$id_district);


     if($cronjob ==0){
     $this->_redirector = $this->_helper->getHelper('Redirector');
   //  $this->_redirect( '/district/edfireport/page/11');
   // $this->_redirect( '/district/edfidetail/id_district/5901/id_county/25');
     $this->_redirect( '/district/edfidetail2/id_district/'.$id_district.'/id_county/'.$id_county);
     }
 } // end of the advisorSetAction function


    function decideWhereToGetIepData($iep,$iepCard) {
      //$this->writevar1($iepCard[0]['service_where'],'this is the iepcard');
      if($iep[0]['date_conference']>= $iepCard[0]['date_conference']) {


         if($iep[0]['primary_disability_drop']=='Physical Therapy') $result['serviceDescriptor_pt']='2';

         if($iep[0]['primary_disability_drop']=='Occupational Therapy Services'||
            $iep[0]['primary_disability_drop']=='Occupational Therapy' ) $result['serviceDescriptor_ot']='1';

         if($iep[0]['primary_disability_drop']=='Speech-language therapy'||
            $iep[0]['primary_disability_drop']=='Speech/Language Therapy' ) $result['serviceDescriptor_slt']='3';

           // $this->writevar1($iep[0]['primary_disability_drop'],'primary disability');
            // $this->writevar1($result['serviceDescriptor_ot'],'primary disability ot');

            $relatedServices=new Model_Table_Form004RelatedService();
            $services=$relatedServices->getRelatedServicesState($iep[0]['id_form_004']);

            if($result['serviceDescriptor_pt']!=2)
             $result['serviceDescriptor_pt']=$services['serviceDescriptor_pt'];

            if($result['serviceDescriptor_ot']!=1)
            $result['serviceDescriptor_ot']=$services['serviceDescriptor_ot'];

            if($result['serviceDescriptor_slt']!='3')
            $result['serviceDescriptor_slt']=$services['serviceDescriptor_slt'];

            //$this->writevar1($services,'these are the services');


            $result['specialeducationsettingdescriptor']=$iep[0]['primary_service_location'];
            $result['iep_ifsp_code']='form004';
            $result['iep_ifsp_id']=$iep[0]['id_form_004'];
            $result['specialeducationpercentage']=$iep[0]['special_ed_non_peer_percent'];

            //$this->writevar1($result,'this is the result if iep under decide where to get iepdata');
            return $result;
        }

        if($iep[0]['date_conference']< $iepCard[0]['date_conference']) {
        //    $this->writevar1($iepCard[0]['service_where'],'this is the result from the card line 582');
            $result['iep_ifsp_code']='form023';
            $result['iep_ifsp_id']=$iepCard[0]['id_form_023'];
            $result['specialeducationpercentage']=$iepCard[0]['special_ed_non_peer_percent'];

          // $this->writevar1($iepCard[0],'this is the iep card bare on the db');
            if ($iepCard[0]['service_ot']==true){
              //  $result['service_ot']=1;
                $result['serviceDescriptor_ot']=1;
            } else {
                $result['serviceDescriptor_ot']='0';
            }
            if ($iepCard[0]['service_pt']==true){
                $result['serviceDescriptor_pt']=2;
            }
            else {
                $result['serviceDescriptor_pt']='0';
            }
            if ($iepCard[0]['service_slt']==true){
                $result['serviceDescriptor_slt']=3;
            }
            else {
                $result['serviceDescriptor_slt']='0';
            }
            if($iepCard[0]['service_where']=='1')$iepcard[0]['service_where']='01';
            $result['specialeducationsettingdescriptor']=$iepCard[0]['service_where'];


            return $result;

        }

    }

     function decideWhereToGetData($mdt,$mdtCard) {
     // $this->writevar1($mdt[0]['id_student'],'this is the mdt we have to decide on');
     // $this->writevar1($mdtCard[0]['id_student'],'this is the mdt card we have to decide on');



         if ($mdt[0]['date_mdt']>=$mdtCard[0]['date_mdt']){
         $result['disabilities']=$this->getMdtCode($mdt[0]['disability_primary']);

         // Mike changed this 5-9-2018 because it must be initial_verification date;
         //$result['beginDate']=$mdt[0]['date_mdt'];
         $result['beginDate']=$mdt[0]['initial_verification_date'];


         // Mike added 10.10-2017 in order to get code and id for db
         $result['mdt_code']='form002';
         $result['mdt_id']=$mdt[0]['id_form_002'];
     //    if($mdtCard[0]['id_student']=='1307343') $this->writevar1($result,'this is hte result line 581 mdt');
         return $result;
     }


        if ($mdtCard[0]['date_mdt']>$mdt[0]['date_mdt']){
         $result['disabilities']=$this->getMdtCode($mdtCard[0]['disability_primary']);

         // Mike changed 4-3-2018 SRS-212
       //  $result['beginDate']=$mdtCard[0]['date_mdt'];
       // Mike changed again 5-9-2018
      //    $result['beginDate']=$mdtCard[0]['date_mdt'];
         $result['beginDate']=$mdtCard[0]['initial_verification_date'];


         // Mike added this 10-11-2017 in order to allow view to work correctly for the edfi
         // correction protocol.
         $result['mdt_code']='form022';
         $result['mdt_id']=$mdtCard[0]['id_form_022'];

        // if($mdtCard[0]['id_student']=='1307343') $this->writevar1($result,'this is hte result line 597 card');
         return $result;
     }
    }

     function getMdtCode ($code) {
         switch ($code) {
             case 'MHSP':
                 return '16';
             case 'BD':
                 return '01';
             case 'TBI':
                 return '14';
             case 'AU':
                 return '13';
             case 'HI':
                 return '03';
             case 'OI':
                 return '08';
             case 'OHI':
                 return '09';
             case 'DD':
                 return '15';
             case 'MH':
                 return '16';
             case 'MHMI':
                 return '16';
             case 'MULTI':
                 return '07';
             case 'DB':
                 return '02';
             case 'SLI':
                 return '11';
             case 'MHMO':
                 return '16';
             case 'VI':
                 return '12';
             case 'SLD':
                 return '10';
             default:
                 return null;
         }
     }
  }


?>
