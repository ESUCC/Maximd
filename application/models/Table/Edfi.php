<?php
class Model_Table_Edfi extends Model_Table_AbstractIepForm {
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
       // $this->writevar1($stuData,'this isthe student data');
        $id=$stuData['id_student'];
        $table = new Model_Table_Edfi();
        $item=$table->fetchrow('id_student = '."'".$id."'");
      //  $this->writevar1($item,'this is the item');
      //  if ($stuData['id_student']=='1118982')$this->writevar1($item['id_student'],'here is the student');
        
        if(empty($item)) {
            $this->insertEdfi($stuData);
         //   $this->writevar1($item['id_student'],'this is the student not entered');
        }
        
  }
    
    function insertEdfi($stuData){
    //    $this->writevar1($stuData['specialeducationsettingdescriptor'],' '.$stuData['id_student']);
      //  if($stuData['id_student']=='1384091'){
            $this->writevar1($stuData,'this is the student data edfi.model');
      //  }
        $data = array(
            'educationorganzationid'        => $stuData['educationOrganizationID'],
            'id_student'                   => $stuData['id_student'],
            'studentuniqueid'              =>$stuData['studentUniqueID'],
            'begindate'                    =>$this->changeDatetoNull($stuData['beginDate']),
            'enddate'                      =>$stuData['enddate'],
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
            'disabilities'                 =>$stuData['disabilities'],
        
            'edfipublishstatus'            =>$stuData['edfiPublishStatus'],
            'edfiresultcode'               =>$stuData['edfiResultCode'],
            'edfipublishtime'              =>$stuData['edfiPublishTime'],
            'id_author'                    =>$stuData['id_author']
            );
       
        //$this->writevar1($data,'this is the data before it goes in');
        
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
    
    function updateEditStudentEdfi($student){
     //   $this->writevar1($student,'this is the student line 119 ');
        /*
         * 1 check for district edfi
         * 2 check for existing entry in edfi table
         * 3  if no entry create edfi table for the student
         *  else update the edfi table for the student
         */
        
        // #1
        $dist=new Model_Table_District();
        $disId=$student['id_district'];
        $countyId=$student['id_county'];
        $distUseEdfi=$dist->getDistrictUseEdfi($disId,$countyId);
      //  $this->writevar1($distUseEdfi,'this is the district info');
        
         
        if($distUseEdfi==true) {
            // #2
           $studentEdfiEntry=$this->checkForTableEntry($student['id_student']); 
         
           // Note: Mike put this in for the sandbox stuff take the and part out when goto 
           // Staging 6-20-2017
           
           if($studentEdfiEntry!=null ){
               
        //      $this->writevar1($student,'this is the exit code line 144');
             
             if($student['sesis_exit_code']==""|| $student['sesis_exit_code']==null || !isset($student['sesis_exit_code'])) {
                 $student['sesis_exit_code']=null; 
               }
           
           
              if($student['sesis_exit_date']!=null ){
                  $date=$student['sesis_exit_date'];
              }
                  else {
                      $date=null;
                  }
                  
                  
               // get the correct placement type
                  if($student['pub_school_student']!=true) {
                      $placementType=$student['parental_placement'];
                  }
                  else {
                      $placementType=0;
                  }
                     
                  
               $data=array(
                   'reasonexiteddescriptor'=>$student['sesis_exit_code'],
                   
                   'enddate'=>$date, 
                   'placementtypedescriptor'=>$placementType,
                   'totakealternateassessment'=>$student['alternate_assessment'],
                   'edfipublishstatus'=>'W',
                   'id_author_last_mod'=>$_SESSION['user']['id_personnel']
               );
                
                $id=$student['id_student'];
                $where =  "id_student = '$id' ";
                //   $this->writevar1($data,' This is line 219  '.$where);
                $this->update($data,$where);
               
               
               
               
              
           }
           else {
               //create if they have a unique_state_id
               // add other fields as well 
           }
            
        }
    }
    
    function checkForTableEntry($student){
      //  $this->writevar1($student,'this is the studentline 171');
        $tab= new Model_Table_Edfi();
        $row = $tab->fetchrow($this->select()
            ->where('id_student = ?',$student));
        
        
       
        
       // $this->writevar1($row,'this is the row line 177');
        
        //$row = $this->fetchRow('id_student = ' . "'" . $student['id_student'] . "'");
       return $row;
    }
    
    function updateOneStudent($currentForm){
    //    $this->writevar1($currentForm,'this is the current form line 118');
        $studentId=$currentForm['id_student'];
        $ot=null;
        $pt=null;
        $slt=null;
        
        if(isset($currentForm['id_form_004'])) {
        
        
        if ($currentForm['primary_disability_drop']=='Occupational Therapy Services'){
        $ot=1;
        
        }
        if ($currentForm['primary_disability_drop']=='Physical Therapy'){
            $pt=2;
        }
        if ($currentForm['primary_disability_drop']=='Speech-language therapy'){
            $slt=3;
        }
        // Get related services
        $relatedServices=new Model_Table_Form004RelatedService();
        $result=$relatedServices->getRelatedServicesState($currentForm['id_form_004']);
        if($result['serviceDescriptor_ot']==1) $ot=1;
        if($result['serviceDescriptor_pt']==2) $pt=2;
        if($result['serviceDescriptor_slt']==3) $slt=3;
        
        
        $data = array(
            'servicedescriptor_ot'=>$ot,
            'servicedescriptor_pt'=>$pt,
            'servicedescriptor_slt'=>$slt,
            'edfipublishstatus'=>'W',
            'id_author_last_mod'=>$_SESSION['user']['id_personnel'],
            'levelofprogramparticipationdescriptor'=>"06",
            'specialeducationpercentage'=>$currentForm['special_ed_non_peer_percent']
            );
        
        
       // $where = "id_district = '$id_district' and id_county='$id_county' ";
        $where =  "id_student = '$studentId'";
        $this->update($data,$where);
       
        } // end of if form_oo4 
        
        if(isset($currentForm['id_form_023'])){
           
            if($currentForm['service_ot']==true){
                $ot=1;
            }
            if($currentForm['service_pt']==true){
                $pt=2;
            }
            if($currentForm['service_slt']==true){
                $slt=3;
            }
            $data = array(
                'servicedescriptor_ot'=>$ot,
                'servicedescriptor_pt'=>$pt,
                'servicedescriptor_slt'=>$slt,
                'edfipublishstatus'=>'W',
                'id_author_last_mod'=>$_SESSION['user']['id_personnel'],
                'levelofprogramparticipationdescriptor'=>"06",
                'specialeducationpercentage'=>$currentForm['special_ed_non_peer_percent']
            );
           
           
            $where =  "id_student = '$studentId'";
          //  $this->writevar1($data,'  '.$where);
            
            $this->update($data,$where);
        }  //end of iep data card;
        
        if(isset($currentForm['id_form_002'])){
          // $this->writevar1($currentForm['disability_primary'],'disability primary line 191'); 
           $mdtCode=$this->getMdtCode($currentForm['disability_primary']);
         //  $this->writevar1($mdtCode,'this is the number of the code line 193');
           
           $data=array(
               'disabilities'=>$mdtCode,
               'edfipublishstatus'=>'W',
               'id_author_last_mod'=>$_SESSION['user']['id_personnel']
           );
           
           
           $where =  "id_student = '$studentId'";
           //$this->writevar1($data,'  '.$where);
           $this->update($data,$where);
           
        }
        // end of the id_form_002  
        
      //  $this->writevar1($currentForm['id_form_022'],' this is hte id of form 022 line 208');
        if(isset($currentForm['id_form_022'])){
            $mdtCode=$this->getMdtCode($currentForm['disability_primary']);
            $data=array(
                'disabilities'=>$mdtCode,
                'edfipublishstatus'=>'W',
                'id_author_last_mod'=>$_SESSION['user']['id_personnel']
            );
             
             
            $where =  "id_student = '$studentId'";
         //   $this->writevar1($data,' This is line 219  '.$where);
            $this->update($data,$where);
        }
        // this is the end of form_022
        
        
    } 
     
        
    
}  // This is the end of the class



