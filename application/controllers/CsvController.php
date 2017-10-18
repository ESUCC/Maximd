<?php 

class CsvController extends Zend_Controller_Action {
    
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
      echo "got here";die(); 
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
 
  public function advisorsetAction($id_county=0,$id_district=0) {
     $inx=0;
      $iep=new Model_Table_Form004();
      $ifsp=new Model_Table_Form013();
      $iepCard=new Model_Table_Form023();
      $relatedServices=new Model_Table_Form004RelatedService();
      $districtInfo=new Model_Table_District();
      $id_county=$this->getRequest()->getParam('id_county');
      $id_district=$this->getRequest()->getParam('id_district');
      
      
      $continue=false;
      foreach($_SESSION['user']['user']->privs as $privs){
      
          if ($privs['class']<=3 && $privs['id_county']==$id_county && $privs['id_district']==$id_district
              && $privs['status']=='Active')
              $continue=true;
      
              if($privs['class']==1 && $privs['status']=='Active') $continue=true;
      
      }
      
      if($continue==false){
          $this->_redirect( '/error/no-access');
      
      }
       
      $listStudents = new Model_Table_StudentTable();
      $juneCutoff=$this->getJuneCutoff();
      $districtStudents=$listStudents->studentsInDistrict($id_county,$id_district,$juneCutoff);
     // $this->writevar1($districtStudents,'this is a list of the districts studetns');
      
      // Mike added 10-11-2017 in order to get a list of the schools.
      $schoolList=new Model_Table_IepSchoolm();
      $schools=$schoolList->getIepSchoolInfo($id_county,$id_district);
      
     // This loop ends at line 398
      foreach($districtStudents as $student){
      
      $continue=true;
      $csvStudentData['supp_services']=null;
      $csvStudentData='';
      $csvStudentData['date_conference']=null;
      $csvStudentData['primary_disability_drop']=null;
      $csvStudentData['primary_service_tpd']=null;
      $csvStudentData['primary_service_tpd_unit']=null;
      $csvStudentData['primary_service_dpw']=null;
      // 
      foreach($schools as $school){
          if($school['id_school']==$student['id_school']){
              $csvStudentData['name_school']=$school['name_school'];
              $csvStudentData['id_school']=$school['id_school'];
          }
      }
      
     
      
      
      
      /*
       * Mike added this functionality so that we could track the forms that have errors
       * in them. 10-12-2017
       */
       $tmpDistrict=$districtInfo->getDistrict($id_county,$id_district);
       $csvStudentData['name_district']=$tmpDistrict['name_district'];
       
      $csvStudentData['name_first']=$student['name_first'];
      $csvStudentData['name_last']=$student['name_last'];
      $csvStudentData['mdt_code']='NOMDT';
      $csvStudentData['iep_ifsp_code']='noiepifsp';
      $csvStudentData['mdt_id']=1000;
      $csvStudentData['iep_ifsp_id']=1000;
      
      
      
     $csvStudentData['educationOrganizationID']=$student['id_county'].$student['id_district'];
   //   $this->writevar1($csvStudentData['educationOrganizationID'],'id ofthe school');
     // Took this out 9-14-2017 
     // $csvStudentData['educationOrganizationID']='255901';
     
     // $csvStudentData['studentUniqueID']=$student['unique_id_state'];
     
      
      // took this out 9-14-2017 $csvStudentData['studentUniqueID']=$student['tempid'];
      // Put this in 9-14-2017
      $csvStudentData['studentUniqueID']=$student['unique_id_state'];
      
      $csvStudentData['id_student']=$student['id_student'];
      
      $ps=$student['sesis_exit_code'];
      if(strlen($ps)==1){
          $ps="0".$ps; 
          $csvStudentData['reasonExitedDescriptor'] =$ps;
       }
      else {
      $csvStudentData['reasonExitedDescriptor']=$student['sesis_exit_code'];
      }
      
      $csvStudentData['enddate']=$student['sesis_exit_date'];
      if($student['alternate_assessment']==null ) {
          $csvStudentData['toTakeAlternateAssessment']=0;
          // This might have to be set to 2 by state requiremnet.  Not sure 
          // 10-9-2017
      }
      
      if($student['alternate_assessment']==true ) {
          $csvStudentData['toTakeAlternateAssessment']=1;
      }
      
      if($student['alternate_assessment']==false ) {
          $csvStudentData['toTakeAlternateAssessment']=0;
         if($student['grade']< "3") $csvStudentData['toTakeAlternateAssessment']="2";
      }
      
      $csvStudentData['beginDate']=null; 
      $csvStudentData['specialEducationPercentage']=0;
      //$csvStudentData['specialEducationSettingDescriptor']='00';
      $csvStudentData['disabilities']='0';
      $csvStudentData['levelOfProgramParticipationDescriptor']=0;
    
    
      
      
      if($student['pub_school_student']==false) {
                           
      $csvStudentData['placementtypedescriptor']=$student['parental_placement'];
   //   $this->writevar1($student['id_student']." ".$student['parental_placement'],' this is the parental placement');
      }
        else {
            $csvStudentData['placementtypedescriptor']='00';
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
           
          // $this->writevar1($mostRecentMdt,'this is the most mdt card');
           $primaryDisability=$this->getMdtCode($mostRecentMdt[0]['disability_primary']);
           $csvStudentData['disabilities']=$primaryDisability;
           $csvStudentData['beginDate']=$mostRecentMdt[0]['date_mdt'];
           $csvStudentData['mdt_id']=$mostRecentMdt[0]['id_form_002'];
           $csvStudentData['mdt_code']='form002';
       }
       
       if($mostRecentMdt==null && $mostRecentMdtCard!=null) {
           // $this->writevar1($mostRecentMdt,'this is the most mdt card');
           $primaryDisability=$this->getMdtCode($mostRecentMdtCard[0]['disability_primary']);
           $csvStudentData['disabilities']=$primaryDisability;
           $csvStudentData['beginDate']=$mostRecentMdtCard[0]['date_mdt'];
           $csvStudentData['mdt_id']=$mostRecentMdt[0]['id_form_022'];
           $csvStudentData['mdt_code']='form022';
            
       }
     
       if($mostRecentMdt!=null && $mostRecentMdtCard!=null ){
           
           $result= $this->decideWhereToGetData($mostRecentMdt, $mostRecentMdtCard);
           $csvStudentData['disabilities']=$result['disabilities'];
           $csvStudentData['beginDate']=$result['beginDate'];
           $csvStudentData['mdt_code']=$result['mdt_code'];
           $csvStudentData['mdt_id']=$result['mdt_id'];
           //$this->writevar1($result,'this i the array result for both mdts');
      }
       
      $mostRecentIep=$iep->getMostRecentIepState($stuId);
      $mostRecentIfsp=$ifsp->getMostRecentIfspState($stuId);
      $mostRecentIepCard=$iepCard->getMostRecentIepCardState($stuId);
      
       
       if($mostRecentMdt==null && $mostRecentMdtCard==null && $ifsp==null ) {
           $continue=false;
       }
     
       
       // No need to go out to the database if there is no mdt or mdt card
      // changed this 9-14-2017 from 'tempid' 
       if($student['unique_id_state']<='1000000000'){
           $continue=false;
       }
       
 
 // Start Here
 
      if($continue==true)   {   // end is down at 449 or so
       $mostRecentIep=null;
       $mostRecentIepCard=null;
       $mostRecentIfsp=null;
       
       
        
     
 
      
  
  // IEP ONly        
         if($mostRecentIep!=null){
             $csvStudentData['section']='ONLY HAS IEP';
             $fm=$mostRecentIep;         
             $csvStudentData['levelOfProgramParticipationDescriptor']='06';
             $csvStudentData['specialEducationPercentage']=$fm[0]['special_ed_non_peer_percent'];           
             $csvStudentData['iep_ifsp_code']='form004';
             $csvStudentData['iep_ifsp_id']=$fm[0]['id_form_004'];
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
              $csvStudentData['primary_service_dpw']=$fm[0]['primary_service_dpw'];
              $csvStudentData['specialeducationsettingdescriptor']=$fm[0]['primary_service_location'];              
              // added by Mike for report 10-15-2017
              $csvStudentData['date_conference']=$fm[0]['date_conference'];
              $csvStudentData['primary_disability_drop']=$fm[0]['primary_disability_drop'];
              $csvStudentData['primary_service_tpd_unit']=$fm[0]['primary_service_tpd_unit'];
              $csvStudentData['primary_service_tpd']=$fm[0]['primary_service_tpd'];        
             
              $csvStudentData['supp_services']=null;
              $relatedResults=$relatedServices->getRelatedServicesStateForCsv($fm[0]['id_form_004']);
            //  $this->writevar1($relatedResults,'related services');
              if($relatedResults[0]!=null) $csvStudentData['supp_services']=$relatedResults;
             
              
          }   
          
          
// end of the existence of an iep only.
 // End of IEP ONLY       
          
          
           
       
              
     
     } //end of the if check on iep true or false
    
      //$this->writevar1($result,'these are the related services az per this form004');
    //   $this->writevar1($csvStudentData,'this is the csv student data for the first go around');
       
       if($csvStudentData['date_conference']==null){
           $csvStudentData['date_conference']="No Conference Date";
          
       }
       if($csvStudentData['primary_disability_drop']==null){
           $csvStudentData['primary_disability_drop']="No Primary Disablity";
       
       }
     
       $listOfStudents[$inx]=$csvStudentData;
       
      // $this->writevar1($listOfStudents[$inx],'list of students');
       $inx=$inx+1;
    } // end of the for students
  
  
    
    $this->view->listOfStudents=$listOfStudents;
  } //end advisorsetAction
 
    

    
    
    
    
    
    
    
 
    
    
    
    
    
    function decideWhereToGetIepData($iep,$iepCard) {
   
        if($iep[0]['date_conference']>= $iepCard[0]['date_conference']) {
         
            $relatedServices=new Model_Table_Form004RelatedService();
            $result=$relatedServices->getRelatedServicesState($iep[0]['id_form_004']);
            $result['specialeducationsettingdescriptor']=$iep[0]['primary_service_location'];
            $result['iep_ifsp_code']='form004';
            $result['iep_ifsp_id']=$iep[0]['id_form_004'];
            return $result;
        }
        
        if($iep[0]['date_conference']< $iepCard[0]['date_conference']) {
            $result['iep_ifsp_code']='form023';
            $result['iep_ifsp_id']=$iepCard[0]['id_form_023'];
            
            if ($iepCard[0]['service_ot']==true){
                $result['service_ot']=1;
            } else {
                $result['service_ot']=0;
            }
            if ($iepCard[0]['service_pt']==true){
                $result['service_pt']=2;
            }
            else {
                $iepCard['service_pt']=0;
            }
            if ($iepCard[0]['service_slt']==true){
                $result['service_slt']=3;
            }
            else {
                $iepCard['service_slt']=0;
            }
            $result['specialeducationsettingdescriptor']=$iepCard[0]['service_where'];
            
            return $result;

        }
    
    }   
     
     function decideWhereToGetData($mdt,$mdtCard) {
     // $this->writevar1($mdt[0]['id_student'],'this is the mdt we have to decide on');
     // $this->writevar1($mdtCard[0]['id_student'],'this is the mdt card we have to decide on');
      
     if ($mdt[0]['date_mdt']>=$mdtCard[0]['date_mdt']){
         $result['disabilities']=$this->getMdtCode($mdt[0]['disability_primary']);
         $result['beginDate']=$mdt[0]['date_mdt'];
         // Mike added 10.10-2017 in order to get code and id for db
         $result['mdt_code']='form002';
         $result['mdt_id']=$mdt[0]['id_form_002'];
         return $result;
     }
     
     
     if ($mdtCard[0]['date_mdt']>$mdt[0]['date_mdt']){
         
         $result['disabilities']=$this->getMdtCode($mdtCard[0]['disability_primary']);
         $result['beginDate']=$mdtCard[0]['date_mdt'];
         
         // Mike added this 10-11-2017 in order to allow view to work correctly for the edfi
         // correction protocol.
         $result['mdt_code']='form022';
         $result['mdt_id']=$mdtCard[0]['id_form_022'];
         
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
