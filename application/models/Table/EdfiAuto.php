<?php
class Model_Table_EdfiAuto extends Model_Table_AbstractIepForm {
    protected $_name = 'edfi';
    protected $_primary = 'id_edfi_entry';
   
    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    function setupAdvisor($stuData){
        $id=$stuData['id_student'];
        $table = new Model_Table_EdfiAuto();
        $item=$table->fetchrow('id_student = '."'".$id."'");
         
    
        if(empty($item)) {
            $this->insertEdfi($stuData);
        }
    
    }
    
   // 1430949
    
    function insertEdfi($stuData){
        
        
        $data = array(
            'educationorganzationid'        => $stuData['educationOrganizationID'],
            'id_student'                   => $stuData['id_student'],
            'studentuniqueid'              =>$stuData['studentUniqueID'],
            'begindate'                    =>$this->changeDatetoNull($stuData['beginDate']),
            'reasonexiteddescriptor'       =>$stuData['reasonExitedDescriptor'],
            'specialeducationsettingdescriptor'    =>$stuData['specialEducationSettingDescriptor'],
            'levelofprogramparticipationdescriptor'=>$stuData['levelOfProgramParticipationDescriptor'],
            'placementtypedescriptor'      =>$stuData['placementTypeDescriptor'],
            'specialeducationpercentage'   =>$stuData['specialEducationPercentage'],
            'totakealternateassessment'    =>$stuData['toTakeAlternateAssessment'],
            'servicedescriptor_pt'         =>$stuData['serviceDescriptor_pt'],
            'servicebegindate_pt'          =>$this->changeDatetoNull($stuData['serviceBeginDate_pt']),
            'servicedescriptor_ot'         =>$stuData['serviceDescriptor_ot'],
            'servicebegindate_ot'          =>$this->changeDatetoNull($stuData['serviceBeginDate_ot']),
            'servicedescriptor_slt'        =>$stuData['serviceDescriptor_slt'],
            'servicebegindate_slt'         =>$this->changeDatetoNull($stuData['serviceBeginDate_slt']),
            'disabilities'                 =>$stuData['disabilities']
            );
        
        $db = Zend_Registry::get('db');
        $db->insert('edfi', $data);
        
    } // End of the function
    
    function changeDatetoNull ($t){
        if ($t=='') return null;
    return $t;
    }
    
    function changeBool ($t) {
        if ($t==null) return 0;
        if ($t == false)return 0;
        if ($t== true) return 1;
    }
 
    function decideWhereToGetData($mdt,$mdtCard) {
      
        if ($mdt[0]['date_mdt']>=$mdtCard[0]['date_mdt']){
            $result['disabilities']=$this->getMdtCode($mdt[0]['disability_primary']);
            $result['beginDate']=$mdt[0]['date_mdt'];
            return $result;
        }
         
         
        if ($mdtCard[0]['date_mdt']>$mdt[0]['date_mdt']){
            $result['disability_primary']=$this->getMdtCode($mdtCard[0]['disability_primary']);
            $result['beginDate']=$mdtCard[0]['date_mdt'];
    
            return $result;
        }
    }
    
    function decideWhereToGetIepData($iep,$iepCard) {
         
        if($iep[0]['date_conference']>= $iepCard[0]['date_conference']) {
    
            $relatedServices=new Model_Table_Form004RelatedService();
            $result=$relatedServices->getRelatedServicesState($iep[0]['id_form_004']);
            $result['specialEducationSettingDescriptor']=$iep[0]['primary_service_location'];
    
        }
    
        if($iep[0]['date_conference']< $iepCard[0]['date_conference']) {
             
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
            $result['specialEducationSettingDescriptor']=$fm[0]['service_where'];
    
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
    
    function getJuneCutoff()
    {
        if (date("m", strtotime("today")) >= 7) {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today"))));
        } else {
            $juneCutoff = date("m/d/Y", strtotime("7/1/" . date("Y", strtotime("today")) . "-1 year"));
        }
    
        return $juneCutoff;
    }    
    
 public function advisorsetAction($id_county,$id_district) {
     $iep=new Model_Table_Form004();
     $ifsp=new Model_Table_Form013();
     $iepCard=new Model_Table_Form023();
     $relatedServices=new Model_Table_Form004RelatedService();
     
     $id_county='11';
     $id_district='0014';
     $this->writevar1($id_county,'this is the county id line  182');
     $listStudents = new Model_Table_StudentTable();
     $juneCutoff=$this->getJuneCutoff();
     $districtStudents=$listStudents->studentsInDistrict($id_county,$id_district,$juneCutoff);
    /*
     * 6-11-2017
     * Right now the districtStudents does not take into account the juneCuttoff
     */
    // $this->writevar1($districtStudents,'this is a list of the districts studetns line 185');
     
     
     
      
     foreach($districtStudents as $student){
     
         $continue=true;
          
         $advisorStudentData='';
       
         
      //   $advisorStudentData['educationOrganizationID']=$student['id_county'].$student['id_district'];
         $advisorStudentData['educationOrganizationID']='255901';
          
         
         // Mike changed this 9-14-2017 back to unique id state
         $advisorStudentData['studentUniqueID']=$student['unique_id_state'];
       //  $advisorStudentData['studentUniqueID']=$student['tempid'];
         $advisorStudentData['id_student']=$student['id_student'];
     
     /*
      * The following is located in the iep_student table
      */
         $advisorStudentData['reasonExitedDescriptor']=$student['sesis_exit_code'];
         
         /*
          * The follwing is located on the student edit screen of iep_student
          */
         if($student['alternate_assessment']==null ) {
             $advisorStudentData['toTakeAlternateAssessment']=0;
         }
     
         if($student['alternate_assessment']==true ) {
             $advisorStudentData['toTakeAlternateAssessment']=1;
         }
     
         if($student['alternate_assessment']==false ) {
             $advisorStudentData['toTakeAlternateAssessment']=0;
         }
     
         $advisorStudentData['beginDate']=null;
        
         
         $advisorStudentData['specialEducationPercentage']=0;
         $advisorStudentData['specialEducationSettingDescriptor']='00';
         $advisorStudentData['disabilities']='0';
         $advisorStudentData['levelOfProgramParticipationDescriptor']=0;
     
         if($student['pub_school_student']!=true) {
             $advisorStudentData['placementTypeDescriptor']=$student['parental_placement'];
         }
         else {
             $advisorStudentData['placementTypeDescriptor']='00';
         }
          
         /*Form 002 find the beginDate in the mdt form.  It will either be in
          * the mdt card latest or the form002 latest for the child
          */
         $stuId=$student['id_student'];
     
         $lastMdt=new Model_Table_Form002();
         $mostRecentMdt=$lastMdt->getMostRecentMDT($stuId);
          
         $lastMdtCard= new Model_Table_Form022();
         $mostRecentMdtCard=$lastMdtCard->getMostRecentMdtCard($stuId);
     
          
     
         if($mostRecentMdtCard==null && $mostRecentMdt!=null) {
              
             // $this->writevar1($mostRecentMdt,'this is the most mdt card');
             $primaryDisability=$this->getMdtCode($mostRecentMdt[0]['disability_primary']);
             $advisorStudentData['disabilities']=$primaryDisability;
             $advisorStudentData['beginDate']=$mostRecentMdt[0]['date_mdt'];
     
              
         }
          
         if($mostRecentMdt==null && $mostRecentMdtCard!=null) {
             // $this->writevar1($mostRecentMdt,'this is the most mdt card');
             $primaryDisability=$this->getMdtCode($mostRecentMdtCard[0]['disability_primary']);
             $advisorStudentData['disabilities']=$primaryDisability;
             $advisorStudentData['beginDate']=$mostRecentMdtCard[0]['date_mdt'];
     
         }
          
         if($mostRecentMdt!=null && $mostRecentMdtCard!=null ){
              
             $result= $this->decideWhereToGetData($mostRecentMdt, $mostRecentMdtCard);
             $advisorStudentData['disabilities']=$result['disabilities'];
             $advisorStudentData['beginDate']=$result['beginDate'];
     
         }
          
     
          
         if($mostRecentMdt==null && $mostRecentMdtCard==null && $ifsp==null ) {
             $continue=false;
         }
         /* Note: Mike added this to the iep_student table and uploaded all the ids from the ods
          so that we would not destroy the unique_id_state field.
          */
          
         if($student['unique_id_state']<='1000000000'){
             $continue=false;
         }
     
          
          
         // No need to go out to the database if there is no mdt or mdt card
         if($continue==true){
             $mostRecentIep=null;
             $mostRecentIepCard=null;
             $mostRecentIfsp=null;
              
             $mostRecentIep=$iep->getMostRecentIepState($stuId);
             $mostRecentIfsp=$ifsp->getMostRecentIfspState($stuId);
             $mostRecentIepCard=$iepCard->getMostRecentIepCardState($stuId);
         }
     
     
     
         if (($mostRecentIfsp!=null || $mostRecentIep!=null || $mostRecentIepCard!=null)
             && $continue==true) {
     
                 $advisorStudentData['serviceDescriptor_ot']=0;
                 $advisorStudentData['serviceBeginDate_ot']=null;
                 $advisorStudentData['serviceDescriptor_pt']=0;
                 $advisorStudentData['serviceBeginDate_pt']=null;
                 $advisorStudentData['serviceDescriptor_slt']=0;
                 $advisorStudentData['serviceBeginDate_slt']=null;
     
                  
     
     
     
                  
     
                 if ($mostRecentIfsp!=null and $mostRecentIep==null and $mostRecentIepCard==null ){
                     $advisorStudentData['section']='ONLY HAS IFSP';
                     $decidedOnForm=$mostRecentIfsp;
                     $advisorStudentData['levelOfProgramParticipationDescriptor']='05';
     
                     $form013=$ifsp->getMostRecentIfspState($stuId);
                     $id_form013=$form013[0]['id_form_013'];
                     $serviceifsp=new Model_Table_Form013Services();
                     $serviceDescription=$serviceifsp->getIfspServicesState($id_form013);
     
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
                      
                     $advisorStudentData['levelOfProgramParticipationDescriptor']='06';
                     $advisorStudentData['specialEducationPercentage']=$fm[0]['special_ed_non_peer_percent'];
                     $advisorStudentData['specialEducationSettingDescriptor']=$fm[0]['primary_service_location'];
     
                      
                      
                     if ($fm[0]['primary_disability_drop']=='Occupational Therapy Services'){
                         $advisorStudentData['serviceDescriptor_ot']='1';
                         $advisorStudentData['serviceBeginDate_ot']=$fm[0]['primary_service_from'];
                     }
                     if ($fm[0]['primary_disability_drop']=='Physical Therapy'){
                         $advisorStudentData['serviceDescriptor_pt']='2';
                         $advisorStudentData['serviceBeginDate_pt']=$fm[0]['primary_service_from'];
                     }
                     if ($fm[0]['primary_disability_drop']=='Speech-language therapy'){
                         $advisorStudentData['serviceDescriptor_slt']='3';
                         $advisorStudentData['serviceBeginDate_slt']=$fm[0]['primary_service_from'];
                     }
     
                     $result=$relatedServices->getRelatedServicesState($fm[0]['id_form_004']);
     
                     if($result!=null) {
                         if ($result['serviceDescriptor_ot']=='1'){
                             $advisorStudentData['serviceDescriptor_ot']='1';
                             $advisorStudentData['serviceBeginDate_ot']=$result['serviceBeginDate_ot'];
                         }
                         if ($result['serviceDescriptor_pt']=='2'){
                             $advisorStudentData['serviceDescriptor_pt']='2';
                             $advisorStudentData['serviceBeginDate_pt']=$result['serviceBeginDate_pt'];
                         }
                         if ($result['serviceDescriptor_slt']=='3'){
                             $advisorStudentData['serviceDescriptor_slt']='3';
                             $advisorStudentData['serviceBeginDate_slt']=$result['serviceBeginDate_slt'];
                         }
                     } // end of check for related services form form_004_related_services
                 }   // end of the existence of an iep only.
     
     
     
                  
                 if($mostRecentIepCard !=null and $mostRecentIep==null){
                     $advisorStudentData['section']='ONLY HAS IEP CARD';
                      
                     $card=$mostRecentIepCard;
                      
                     $advisorStudentData['levelOfProgramParticipationDescriptor']='06';
                     $advisorStudentData['specialEducationPercentage']=$card[0]['special_ed_non_peer_percent'];
                     $advisorStudentData['specialEducationSettingDescriptor']=$card[0]['service_where'];
     
     
                     if ($card[0]['service_ot']==true){
                         $advisorStudentData['service_ot']=1;
                     } else {
                         $advisorStudentData['service_ot']=0;
                     }
                     if ($card[0]['service_pt']==true){
                         $advisorStudentData['service_pt']=2;
                     }
                     else {
                         $advisorStudentData['service_pt']=0;
                     }
                     if ($card[0]['service_slt']==true){
                         $advisorStudentData['service_slt']=3;
                     }
                     else {
                         $advisorStudentData['service_slt']=0;
                     }
     
                 }
     
     
     
     
                 if($mostRecentIep!=null and $mostRecentIepCard!=null ) {
                     $advisorStudentData['section']='HAS BOTH IEP AND IEPCARD';
                     $advisorStudentData['levelOfProgramParticipationDescriptor']='06';
                      
                     $result=$this->decideWhereToGetIepData($mostRecentIep,$mostRecentIepCard);
                      
                     $advisorStudentData['specialEducationPercentage']=$result['special_ed_non_peer'];
                      
                     $advisorStudentData['service_ot']=$result['service_ot'];
                     $advisorStudentData['serviceBeginDate_ot']=$result['serviceBeginDate_ot'];
     
                     $advisorStudentData['service_pt']=$result['service_pt'];
                     $advisorStudentData['serviceBeginDate_pt']=$result['serviceBeginDate_pt'];
     
     
                     $advisorStudentData['service_slt']=$result['service_slt'];
                     $advisorStudentData['serviceBeginDate_slt']=$result['serviceBeginDate_slt'];
     
                 } // end of getting values if using iep
     
     
     
             }  // end of the if continue
     
             if (($mostRecentIfsp!=null || $mostRecentIep!=null || $mostRecentIepCard!=null)
                 && $continue==true && $advisorStudentData['studentUniqueID']>='600000') {
                      
                     //  $this->writevar1($advisorStudentData['beginDate'],$stuId);
                     $advisorStudentData['edfiPublishStatus']='W';
                     $advisorStudentData['edfiResultCode']=null;
                     $advisorStudentData['edfiPublishTime']=date("Y-m-d");
                     //    $advisorStudentData['id_author']=$_SESSION["user"]["user"]->user["id_personnel"];
                     $advisorStudentData['id_author']=0;
                     // $this->writevar1($advisorStudentData,'this is it');
                     $insert=new Model_Table_Edfi();
                     $insert->setupAdvisor($advisorStudentData);
                      
                      
                      
     
                     //  $this->_redirect('/#');
                 }
                  
                  
     } // end of the for loop
     
     $insertEdfi=new Model_EdfiOds();
      
     $insertEdfi->receiveFromOdsController($id_county,$id_district);
      
   //  $this->_redirector = $this->_helper->getHelper('Redirector');
   //  $this->_redirect( '/district/edfireport/page/11');
     
      
       
    } // end of the advisorSetAction function
    
}  // This is the end of the class



