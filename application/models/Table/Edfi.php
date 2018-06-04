<?php
class Model_Table_Edfi extends Model_Table_AbstractIepForm {
    protected $_name = 'edfi';
    protected $_primary = 'id_edfi_entry';

    function writevar1($var1,$var2) {
// Mike was here.
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }


    function modifyEdfiTransfer($id_edFi_entry,$choose) {
     //  $this->writevar1($id_edFi_entry,'this is the edfi entry id');
       if ($choose==1) {
        $db = Zend_Registry::get('db');
        $data = array(
            'edfipublishstatus' => 'T'
        );
        $where = "id_edfi_entry = '$id_edFi_entry'";
        $table='edfi';
        $db->update($table,$data, $where);
       }

       if ($choose==2) {
           $db = Zend_Registry::get('db');
           $data = array(
               'edfipublishstatus' => 'W'
           );
           $where = "id_edfi_entry = '$id_edFi_entry'";
           $table='edfi';
           $db->update($table,$data, $where);
       }



        return;

    }


    function getDistrictW(){
        $db = Zend_Registry::get('db');

        $sql="select distinct(d.name_district),d.id_district,d.id_county from edfi e,iep_district d where d.id_district=e.id_district and e.id_county=d.id_county and e.edfipublishstatus='W' order by d.name_district";
        $result = $db->fetchAll($sql);


        return $result;
    }


   function returnAllTableEntries($id_cty,$id_dist){
       $db = Zend_Registry::get('db');
       $sql="select * from edfi where id_county ='".$id_cty."'and id_district='".$id_dist."'";
       $result = $db->fetchAll($sql);



       return $result;
   }

   function returnTableEntry($student){

       $db = Zend_Registry::get('db');

       // Mike changed it to this so that we can have multiple entries on students in different districts 5-31-2018
       $sql="select * from edfi where id_county ='".$student['id_county']."'and id_district='".$student['id_district']."' and
             id_student='".$student['id_student']."'";

       $result = $db->fetchAll($sql);

      // $result= $this->fetchrow('id_student = '."'".$student['id_student']."'");

       return $result;
   }



    function removeTableEntry($form_code,$form_id){
    // Mike added this 10-14-2017 so that we can remove table row entry from edfi should end user
    // decide to delete a form.  Best to start over with edfi.
        // id_form_022 and 'some number'

       if($form_code=='id_form_002') $this->delete('mdt_id =' . (int)$form_id);
       if($form_code=='id_form_022') $this->delete('mdt_id =' . (int)$form_id);
       if($form_code=='id_form_004') $this->delete('iep_ifsp_id =' . (int)$form_id);
       if($form_code=='id_form_023') $this->delete('iep_ifsp_id =' . (int)$form_id);



    }

    function removeTableEntryByStudentId($stuId){
        // Mike added this 10-14-2017 so that we can remove table row entry from edfi should end user
        // decide to delete a form.  Best to start over with edfi.
        // id_form_022 and 'some number'

         $this->delete('id_student =' . $stuId);


    }


    function setupAdvisor($stuData){
        $id=$stuData['id_student'];
        $table = new Model_Table_Edfi();
     // Mike Changed this 6-1-2018 so that we could see if this district had anything
     //   $item=$table->fetchrow('id_student = '."'".$id."'");

        $item=$table->fetchrow($this->select()
            ->where('id_student =?',$stuData['id_student'])
            ->where('id_county =?',$stuData['id_county'])
            ->where('id_district=?',$stuData['id_district']));


        if(empty($item)) {
            $this->insertEdfi($stuData);

        }

        else {

            // Mike changed this 6-2-2018 to make sure that if it is W to not change it with the other T district
        if($stuData['edfiPublishStatus']=='W' ) {

            $this->updateEdfi($stuData);
         }
        }

  }


  // Mike added this 5-8-2018 because reasonexited must be preceeded by SPED

  function  appendReasonExited ($reason){

      switch ($reason){
          case "01":$reason='SPED01';break;
          case "02":$reason='SPED02';break;
          case "03":$reason='SPED03';break;
          case "04":$reason='SPED04';break;
          case "05":$reason='SPED05';break;
          case "06":$reason='SPED06';break;
          case "07":$reason='SPED07';break;
          case "08":$reason='SPED08';break;
          case "09":$reason='SPED09';break;
          case "10":$reason='SPED10';break;
          case "11":$reason='SPED11';break;
          case "12":$reason='SPED12';break;
          case "13":$reason='SPED13';break;
          case "14":$reason='SPED14';break;
          case "15":$reason='SPED15';break;
          case "16":$reason='SPED16';break;
          case "17":$reason='SPED17';break;
      }

      return $reason;
  }
   function updateEdfi($stuData){

       // Mike added this 5-08-2018 because 200 entries have 1 instead of 01 or 0X and forgot to get this
       // as well as the new ones.  Same as function insertedfi
       if(strlen($stuData['specialeducationsettingdescriptor'])==1){
           $stuData['specialeducationsettingdescriptor']="0".$stuData['specialeducationsettingdescriptor'];


       }

       $stuData['reasonExitedDescriptor']=$this->appendReasonExited($stuData['reasonExitedDescriptor']);


      //$this->writevar1($stuData['educationOrganizationID'], 'looking for orgainizational id line 120 edfi.php');

       $data = array(
           //   'educationorganzationid'        => $stuData['educationOrganizationID'],

           'educationorganzationid'        => $stuData['educationOrganizationID'].'000',
           'id_student'                   => $stuData['id_student'],
           'studentuniqueid'              =>$stuData['studentUniqueID'],
           'begindate'                    =>$this->changeDatetoNull($stuData['beginDate']),
           'enddate'                      =>$stuData['enddate'],
           'reasonexiteddescriptor'       =>$stuData['reasonExitedDescriptor'],
           'specialeducationsettingdescriptor'    =>$stuData['specialeducationsettingdescriptor'],
           'levelofprogramparticipationdescriptor'=>$stuData['levelOfProgramParticipationDescriptor'],
           'placementtypedescriptor'      =>$stuData['placementtypedescriptor'],
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
           'id_author'                    =>$stuData['id_author'],
           'mdt_code'                     =>$stuData['mdt_code'],
           'mdt_id'                       =>$stuData['mdt_id'],
           'iep_ifsp_code'                =>$stuData['iep_ifsp_code'],
           'iep_ifsp_id'                  =>$stuData['iep_ifsp_id'],
           'name_first'                   =>$stuData['name_first'],
           'name_last'                    =>$stuData['name_last'],
           'name_school'                  =>$stuData['name_school'],
           'id_county'                    =>$stuData['id_county'],
           'id_district'                    =>$stuData['id_district'],
           'id_school'                    =>$stuData['id_school']
       );


       $id=$data['id_student'];
       /*
        * Mike changed the where Jun 2 2018 from $where=  "id_student = '$id'. Otherwise it will write over the other Transfered districts data
        */
       $cty=$stuData['id_county'];
       $dst=$stuData['id_district'];

       $where =  "id_student = '$id' and id_county='$cty' and id_district='$dst'";

       $db = Zend_Registry::get('db');
       $this->writevar1($where,'this is the where clause');
       $this->writevar1($data,'this is hte array data');
       die();

       $this->update($data,$where);
   }

    function insertEdfi($stuData){

        // Mike added this 4-12-2018 because 200 entries have 1 instead of 01 or 0X
        if(strlen($stuData['specialeducationsettingdescriptor'])==1){
            $stuData['specialeducationsettingdescriptor']="0".$stuData['specialeducationsettingdescriptor'];

        }

        $stuData['reasonExitedDescriptor']=$this->appendReasonExited($stuData['reasonExitedDescriptor']);

        $data = array(
         //   'educationorganzationid'        => $stuData['educationOrganizationID'],



            'educationorganzationid'        => $stuData['educationOrganizationID'].'000',
            'id_student'                   => $stuData['id_student'],
            'studentuniqueid'              =>$stuData['studentUniqueID'],
            'begindate'                    =>$this->changeDatetoNull($stuData['beginDate']),
            'enddate'                      =>$stuData['enddate'],
            'reasonexiteddescriptor'       =>$stuData['reasonExitedDescriptor'],
            'specialeducationsettingdescriptor'    =>$stuData['specialeducationsettingdescriptor'],
            'levelofprogramparticipationdescriptor'=>$stuData['levelOfProgramParticipationDescriptor'],
            'placementtypedescriptor'      =>$stuData['placementtypedescriptor'],
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
            'id_author'                    =>$stuData['id_author'],
            'mdt_code'                     =>$stuData['mdt_code'],
            'mdt_id'                       =>$stuData['mdt_id'],
            'iep_ifsp_code'                =>$stuData['iep_ifsp_code'],
            'iep_ifsp_id'                  =>$stuData['iep_ifsp_id'],
            'name_first'                   =>$stuData['name_first'],
            'name_last'                    =>$stuData['name_last'],
            'name_school'                  =>$stuData['name_school'],
            'id_county'                    =>$stuData['id_county'],
            'id_district'                    =>$stuData['id_district'],
            'id_school'                    =>$stuData['id_school']
            );

       // $this->writevar1($data,'this is the data before it goes in line 221');

        $db = Zend_Registry::get('db');

        if($data['id_student']=='1459029'){

        }
        // $this->writevar1($data,'this is the data from edfi.php ');
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
    //    $distUseEdfi=$dist->getDistrictUseEdfi($disId,$countyId);


        $distUseEdfi=true;

        if($distUseEdfi==true) {
            // #2

            // Mike changed 5-31-2018
        //  $studentEdfiEntry=$this->checkForTableEntry($student['id_student']);
            $studentEdfiEntry=$this->checkForTableEntry($student);


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

                $edFiPublishStatus='W';
                if($student['exclude_from_nssrs_report']==true) $edFiPublishStatus='X';
              //  $this->writevar1($edFiPublishStatus,'this is the edfi publish status');
               $data=array(
                   'reasonexiteddescriptor'=>$student['sesis_exit_code'],
                   'enddate'=>$date,
                   'placementtypedescriptor'=>$placementType,
                   'totakealternateassessment'=>$student['alternate_assessment'],
                   'edfipublishstatus'=>$edFiPublishStatus,
                   'id_author_last_mod'=>$_SESSION['user']['id_personnel']
               );
               // $this->writevar1($data,'this is the data to go into the db edfi line 292 edfi.php');
                $id=$student['id_student'];

                // Mike added 6-4-2018
                $cty=$student['id_county'];
                $dst=$student['id_district'];


              // Mike changed this
              //  $where =  "id_student = '$id' ";

                $where="id_student='".$id."' and id_county = '".$cty."' and id_district='".$dst."'";

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

        /*
         * Mike changed this 5-31-2018
        $tab= new Model_Table_Edfi();
        $row = $tab->fetchrow($this->select()
            ->where('id_student = ?',$student));
*/
        $t=$student['id_student'];
        $tab=new Model_Table_edfi();
        $row=$tab->fetchrow($this->select()
              ->where('id_student =?',$student['id_student'])
              ->where('id_county =?',$student['id_county'])
              ->where('id_district=?',$student['id_district']));

        if($t='1329927'){
   //         $this->writevar1($row,'this is the row after retrieving from table edfi');

        }

       // $this->writevar1($row,'this is the row line 177');

     //  $row = $this->fetchRow('id_student = ' . "'" . $student['id_student'] . "'");
       return $row;
    }

    function updateOneStudent($currentForm) {


        // Mike added this 4-13-2018 SRS-221
        $stu=new Model_Table_StudentTable();
        $student=$stu->getOneStudent($currentForm['id_student']);



        if($student[0]['exclude_from_nssrs_report']==true) {
            $edfiPublish='X';
        }
        else {
            $edfiPublish='W';
        }

        // Mike added 11-10-2017 to see if the district has edfi set
        $edfiDistrict= new Model_Table_IepDistrict();

        $edfiDist=$edfiDistrict->getEdfiSecretKey($currentForm['id_county'],$currentForm['id_district']);

        // if it is set update the edfi table otherwise go back to the abstractForm.php
      //  $this->writevar1($edfiDist,'this should be true for winne line 316 edfi.php table');
      //  $this->writevar1($currentForm,'this is the currentForm line 317 in abstractform.php');

        if($edfiDist['use_edfi']==true) {


    //    $this->writevar1($currentForm['id_district'],' this is the current form '.$currentForm['id_county']);
        // Mike appended the formcode and the form id to mdt and iep_ifsp edfi 10-12-2014
        $studentId=$currentForm['id_student'];
        $ot=null;
        $pt=null;
        $slt=null;

        if(isset($currentForm['id_form_004'])) {


        if ($currentForm['primary_disability_drop']=='Occupational Therapy Services'|| $currentForm['primary_disability_drop']=='Occupational Therapy'){
        $ot=1;

        }
        else {
            $ot='0';
        }

        if ($currentForm['primary_disability_drop']=='Physical Therapy'){
            $pt=2;
        }
        else {
            $pt='0';
        }


        if ($currentForm['primary_disability_drop']=='Speech-language therapy'|| $currentForm['primary_disability_drop']=='Speech/Language Therapy' ){
            $slt=3;
        }
        else {
            $slt='0';
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
            'edfipublishstatus'=>$edfiPublish,
            'id_author_last_mod'=>$_SESSION['user']['id_personnel'],
        // Mike changed this 4-3-2018 as per SRS-212
        //    'levelofprogramparticipationdescriptor'=>"06",
            'levelofprogramparticipationdescriptor'=>"05",
            'specialeducationpercentage'=>$currentForm['special_ed_non_peer_percent'],
            'iep_ifsp_code'=>'form004',
            'iep_ifsp_id'=>$currentForm['id_form_004'],


            );

       /*
        * Mike added this june 3rd 2018
        */
      $where =  "id_student = '$studentId' and id_district = '$id_district' and id_county='$id_county' ";
      //  $where =  "id_student = '$studentId'";
        $this->update($data,$where);

        } // end of if form_oo4

        if(isset($currentForm['id_form_023'])){

            if($currentForm['service_ot']==true){
                $ot=1;
            }
            else {
                $ot='0';
            }
            if($currentForm['service_pt']==true){
                $pt=2;
            }
            else{
                $pt='0';
            }
            if($currentForm['service_slt']==true){
                $slt=3;
            }
            else {
                $slt='0';
            }

            // Mike added this 4-12-2018 because 200 entries have 1 instead of 01 or 0X
          if(strlen($currentForm['service_where'])==1){
               $currentForm['service_where']="0".$currentForm['service_where'];

            }


            $data = array(
                'servicedescriptor_ot'=>$ot,
                'servicedescriptor_pt'=>$pt,
                'servicedescriptor_slt'=>$slt,
                'edfipublishstatus'=>$edfiPublish,
                'id_author_last_mod'=>$_SESSION['user']['id_personnel'],
                'levelofprogramparticipationdescriptor'=>"05",
                'specialeducationpercentage'=>$currentForm['special_ed_non_peer_percent'],
                'iep_ifsp_code'=>'form023',
                'iep_ifsp_id'=>$currentForm['id_form_023'],
                'specialeducationsettingdescriptor'=>$currentForm['service_where']
            );


            $where =  "id_student = '$studentId'";
           // $this->writevar1($data,'  '.$where);

            $this->update($data,$where);
        }  //end of iep data card;

        if(isset($currentForm['id_form_002'])){
         //  $this->writevar1($currentForm,'current form line 402');

           $mdtCode=$this->getMdtCode($currentForm['disability_primary']);

           $data=array(
               'disabilities'=>$mdtCode,
               'edfipublishstatus'=>$edfiPublish,
               'id_author_last_mod'=>$_SESSION['user']['id_personnel'],
               'mdt_code'=>'form002',
               'mdt_id'=>$currentForm['id_form_002'],
               'begindate'=>$currentForm['date_mdt']
           );
// Mike added begindate above SRS-212 not working correctly without the date.

           $where =  "id_student = '$studentId'";
           //$this->writevar1($data,'  '.$where);
           $this->update($data,$where);

        }
        // end of the id_form_002


        if(isset($currentForm['id_form_022'])){

          //  $this->writevar1($currentForm,'this is the current form022 line 495 so we got here');
            $mdtCode=$this->getMdtCode($currentForm['disability_primary']);
            $data=array(
                'disabilities'=>$mdtCode,
                'edfipublishstatus'=>$edfiPublish,
                'id_author_last_mod'=>$_SESSION['user']['id_personnel'],
                'mdt_code'=>'form022',
                'mdt_id'=>$currentForm['id_form_022'],
                'begindate'=>$currentForm['date_mdt']
            );
            // Mike added begindate above SRS-212 not working correctly without the date.

            $where =  "id_student = '$studentId'";
         //   $this->writevar1($data,' This is line 219  '.$where);
            $this->update($data,$where);
        }
        // this is the end of form_022

        }  // end of the if to seeif the district is using edfi.
    }



}  // This is the end of the class



