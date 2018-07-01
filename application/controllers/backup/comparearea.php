 
 <?php 
 
 class StaffController extends Zend_Controller_Action
 {
 public function team2Action()
        
        {
            
            include("Writeit.php");
            
            $county_sv = $_SESSION["user"]["user"]->user["id_county"];
            $district_sv = $_SESSION["user"]["user"]->user["id_district"];
            $user_id  = $_SESSION["user"]["user"]->user["id_personnel"];
            $privm = $_SESSION['user']['user']->privs[0]['class'];
            $this->idOfStaff=$user_id;
            $userb_id=$user_id;
           
            
            $id=$this->getRequest()->getParam('id_personnel');
            $sch=$this->getRequest()->getParam('id_school');
            
            // This part checks to see where the user clicked
            if($id=='' && $sch==''){
                //clicked from the Submenu on /student/search
                
                $user_id=$userb_id;               
                $school_sv1='001';
            }
            else {
                //clicked on a user in the /staff/team2 page
                $school_sv1=$sch;
                $user_id=$id;
            }
             $this->view->id_school=$school_sv1;
  
            // Get a school list
            $schoolName = new Model_Table_School();
            $dstSchools=$schoolName->districtSchools($county_sv,$district_sv);
            $cnt=count($dstSchools);
           // writevar($t,'this is a list of schools');
           
         //  $privm = $_SESSION['user']['user']->privs[0]['class'];
        
          
            
            
            $this->view->schoolName=$dstSchools;
            
          
                
            // Get a list of teachers at a school from the iep_student_team table using the iep_personnel to get their names
          
           
            $districtStaff = new Model_Table_IepPersonnel();
           
            $nameStaffMember=$districtStaff->getIepPersonnel($user_id);
            $staffMemberFullName = $nameStaffMember['name_first']." ".$nameStaffMember['name_last'];
            $this->view->staffMemberFullName=$staffMemberFullName;
            $staffMemberId=$nameStaffMember['id_personnel'];
            $this->view->staffMemberId=$staffMemberId;
            //
            
            $staffList=$districtStaff->getNameFromPrivTable($county_sv,$district_sv,$school_sv1);
            $this->view->districtList=$staffList;
            
            $paginator = Zend_Paginator::factory($staffList);
            $paginator->setCurrentPageNumber($this->_getParam('page'));
            $paginator->setItemCountPerPage(10);
            $this->view->paginator = $paginator;
            //
            
            $distManagerStaff  = new Model_Table_IepPersonnel();
            $this->view->districtAdmin=$distManagerStaff->getAdmins($county_sv,$district_sv);
       //     writevar($this->view->districtAdmin,'list of district admins');
            
            // Get a list of Students at the school 
            $studentsAtSchool=new Model_Table_StudentTable2();
            $schoolStudents=$studentsAtSchool->getStudentList($county_sv,$district_sv,$school_sv1);
       //     writevar($schoolStudents,'this is the array of other students');
            $this->view->studentList=$schoolStudents;
            
            $paginator1 = Zend_Paginator::factory($schoolStudents);
            $paginator1->setCurrentPageNumber($this->_getParam('page'));
            $paginator1->setItemCountPerPage(10);
            $this->view->paginator1 = $paginator1;
            
         //
         
         // Find students associted with each staff member.
           
            $studentOnTeam=new Model_Table_IepStudent();
            $this->view->studentsOfTeam=$studentOnTeam->getTeamStudents($user_id,$school_sv1);
         //   writevar($this->view->studentsOfTeam,'this is the students on the teammember');
            $districtNames=new Model_Table_District();
            $this->view->nameDistricts=$districtNames->getDistrictList();
          //  writevar($this->view->nameDistricts,'this is the name of the districts');
            
            
        $user_id='';
        $school_sv1='';
        
        } // end of teame funciton
        
      
        
    public function teamAction() 
    {
     include("Writeit.php");
   //  include("Writeit.php");
     $user_id=$this->getRequest()->getParam('id_personnel');
     
     // if coming from the index.phtml page there is no personnel id that is captured. Thus set one!
     if($user_id<1){
         $user_id  = $_SESSION["user"]["user"]->user["id_personnel"];
     } 
  
      
     $studentId=$this->getRequest()->getParam('id_student');
     $county_sv= $this->getRequest()->getParam('id_county');
     $district_sv = $this->getRequest()->getParam('id_district');
     $school_sv=$this->getRequest()->getParam('id_school');
       
       
     $schoolName = new Model_Table_School();
     $t=$schoolName->districtSchools($county_sv,$district_sv);
     $cnt=count($t);        
     $this->view->schoolName=$t;
     $this->view->user_id=$user_id;
     $this->view->id_district=$district_sv;
     $this->view->id_county=$county_sv;
     $this->view->id_school=$school_sv;

       $student = new Model_Table_StudentTable2();
       $studentList=$student->getStudentList($county_sv, $district_sv,$school_sv);
     //  writevar($studentList,'this is the student list');
      
       
       if($studentId==''){
           $studentId=$studentList[1]['id_student'];
       }
       
       
       $found='false';
       $num=count($studentList); 
       $x=0;
                
         
       
       
       //get the students name
       $studentName= new Model_Table_StudentTable2();
  //     writevar($studentId,'this is the student id');
       $nameStudentFull= $studentName->fetchRow($studentName->select()
                                     ->where('id_student =?',$studentId));
       $this->view->nameStudentFulla=$nameStudentFull['name_first']." ".$nameStudentFull['name_last'];
       writevar($this->view->nameStudentFulla,'this is the name of the student');
    //   
                
                
         // This displays the student name at the top of the second column.        
     /*  While($found=='false' && $x<$num) {
                    $teamMembers = new Model_Table_StudentTeam();
                  //  writevar($studentList[$x]['id_student'],'student id'); die();
                    
                    $staffMemberRights= $teamMembers->fetchRow($teamMembers->select()
                        ->where('id_student =?',$studentList[$x]['id_student']));
                    
                   //  writevar($staffMemberRights['id_student'],'this is staff member rights');die();
                    if($staffMemberRights['id_student']==$studentList[$x]['id_student']&& $staffMemberRights['status']=='Active') {
                
                        $user_id=$staffMemberRights['id_personnel'];
                        $studentId=$staffMemberRights['id_student'];
                        $nameStudent=$studentList[$x]['name_first']." ".$studentList[$x]['name_last'];
                        $this->view->id_user=$user_id;
                        $this->view->studentId=$studentList[$x]['id_student'];
                        $this->view->nameStudentFull=$nameStudent;
                        $found='true';
                        
                    }
                    $x=$x+1;
                    
                    // This works 11-20-2016
                } 
                */
           // }
            
            
          
            $findName=new Model_Table_StudentTable2();
            $teamMembers = new Model_Table_StudentTeam();
             
            // This section means somebody clicked on one of the students in column 1.
           $studentData=$findName->studentInfo2($studentId);
           $nameStudent=$studentData[0]['name_first']." ".$studentData[0]['name_last'];
         //  writevar($studentData[0]['name_first'],'this is the student name');
           $this->view->nameStudentFull=$nameStudent;
            $this->view->user_id=$user_id; 
            $this->view->studentId=$studentId;
            $this->view->studentId=$studentData[0]['id_student'];
          //  writevar($this->view->studentId,'this is the student id ');
        
        
        // end of finding the student name
        
        
        
        
      
        // This gets the list of staff members from the iep_privileges table and not the iep_personnel table
        // Mike D 9-29-2016
        $districtStaff = new Model_Table_IepPersonnel();
        $staffList=$districtStaff->getNameFromPrivTable($county_sv,$district_sv,$school_sv);
        $this->view->districtList=$staffList;
     //   writevar($staffList,'this is the staff list');
     
        
        $paginator = Zend_Paginator::factory($staffList);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(20);
        $this->view->paginator = $paginator;
        
        
        $student = new Model_Table_StudentTable2();
        $studentList=$student->getStudentList($county_sv, $district_sv,$school_sv);  
        
        $paginator2 = Zend_Paginator::factory($studentList);
        $paginator2->setCurrentPageNumber($this->_getParam('page'));
        $paginator2->setItemCountPerPage(35);
        $this->view->paginator2 = $paginator2;       

        
        // Get a list of all the team_members for a particular studetn
        $teamMembers = new Model_Table_StudentTeam(); 
        $staffMemberRights= $teamMembers->fetchAll($teamMembers->select()
        ->where('id_student =?',$studentId));
        
        //   $rowCount = count($staffMemberRights);
        // set the condition for create or update and tack it onto the stafflist array
        
     //  writevar($staffMemberRights,'this is staff member rights');
        foreach($staffMemberRights as $Rights) {
          
            foreach($staffList as $Staff)  {
                //   writevar($Staff['id_personnel'],'this is id personnel');
                $staffInTable['id_personnel']=$Staff['id_personnel'];
                $staffInTable['exist']='f';
               // writevar($staffInTable,'here is the staff in table');
            
            }
            
           
            
        
        
        
        
      //  writevar($staffMemberRights,'these are the staff member rights');
        $this->view->staffMemberRights=$staffMemberRights;
       
            
       
        
   
        $this->idOfStaff=$user_id;
  
       
 
       $teamMembers = new Model_Table_StudentTeam();

       $studentsOfTeamMember= $teamMembers->getStudentsOfTeamMember($user_id);
       $countTotalStudents=count($studentList);
       $countStudentsOfTeamMember=count($studentsOfTeamMember);
     
       // x is the outer and y is the inner.  Going to assign one big array and pass it to the view.
       $x=0;
       
       while($x < $countTotalStudents) {
            $y=0;
           $found= 'no';
        
          
           while($y<$countStudentsOfTeamMember and $found=='no') {
               if($studentList[$x]['id_student']==$studentsOfTeamMember[$y]['id_student']) {
                  // writevar($studentsOfTeamMember[$y]['id_student'],'this is the inner loop');
              //    writevar($x,'this is x in found person in team member list');
                  $teacherVu[$x]['id_personnel'] = $studentsOfTeamMember[$y]['id_personnel']; 
                  $teacherVu[$x]['id_student']= $studentList[$x]['id_student'];
                  $teacherVu[$x]['name_last']=$studentList[$x]['name_last'];
                  $teacherVu[$x]['name_first']=$studentList[$x]['name_first'];
                  $teacherVu[$x]['id_school']=$studentList[$x]['id_school'];
                  $teacherVu[$x]['id_district']=$studentList[$x]['id_district'];
                  $teacherVu[$x]['id_county']=$studentList[$x]['id_county'];
                  
                  $teacherVu[$x]['id_personnel']=$user_id;
                  $teacherVu[$x]['exists']="yes";
                  $teacherVu[$x]['flag_edit']=$studentsOfTeamMember[$y]['flag_edit'];
                 // writevar($teacherVu[$x]['flag_edit'],'this is the flag edit ');
                  $teacherVu[$x]['flag_view']=$studentsOfTeamMember[$y]['flag_view'];
                  $teacherVu[$x]['flag_create']=$studentsOfTeamMember[$y]['flag_create'];
                  $found='yes';
               }
                  
               //  writevar($teacherVu[$x], 'the student or personnel'); // seems to work.
               
               $y=$y+1;
                 // writevar($y,'this is y right at the end of the loop');
           //    writevar($found,'this is the found variable inside the inner loop at the bottom');
           }
         
           
       //      writevar($found,'this is found variable before it goes to find nothing in the not found');
        
           if($found=='no') {
               
            //   writevar($x,'x inside the no found loop');
       //        $teacherVu[$x]['id_personnel'] = $studentsOfTeamMember[$y]['id_personnel'];
               $teacherVu[$x]['id_student']= $studentList[$x]['id_student'];
               $teacherVu[$x]['name_last']=$studentList[$x]['name_last'];
               $teacherVu[$x]['name_first']=$studentList[$x]['name_first'];
               $teacherVu[$x]['id_school']=$studentList[$x]['id_school'];
               $teacherVu[$x]['id_district']=$studentList[$x]['id_district'];
               $teacherVu[$x]['id_county']=$studentList[$x]['id_county'];
               
          
               $teacherVu[$x]['id_personnel']=$user_id;
               $teacherVu[$x]['status']='Active';
               $teacherVu[$x]['flag_view']='';
               $teacherVu[$x]['flag_edit']='';
               $teacherVu[$x]['flag_create']='';
               $teacherVu[$x]['exists']='no'; 
           } 
       
         //  writevar($teacherVu[$x]['flag_edit'],'this is the flag edit ');
           $x=$x+1;
           $found='no';
          // writevar($x,'this is at the bottom of the loop');
       }  
       
       $paginator3 = Zend_Paginator::factory($teacherVu);
       $paginator3->setCurrentPageNumber($this->_getParam('page'));
       $paginator->setItemCountPerPage(25);
       
       $this->view->paginator3 = $paginator3;
    
       
       
   $this->view->teacherView= $teacherVu;
  //  writevar($teacherVu,'this is the teacher list ');
     
    }
   
    $school_sv='';
    $studentId = '';
    $user_id = '';
    $school_sv1='';
    $nameStudent='';
    
} // end of team funciton


 }
