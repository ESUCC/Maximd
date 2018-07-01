<?php 

class StaffController extends Zend_Controller_Action 
{
    
    protected  $justAap ='t';
    protected  $idOfStaff ='';
   
    public $IdSchool;
    public $IdPersonnel;
    public $IdStudent;
    
    public function indexAction()
    {
        include("Writeit.php"); 
       
      
     
       // $data= "We managed to get here \n";
   //     writevar($data,'we managed to get here');
      //  $this->view->somedata=$data;
    
        //   header("Location:https://iepd.nebraskacloud.org/srs.php?area=student&sub=student&student=".$request->id_student."&option=team");
        
    
    }
    
    public function updateAction()
    {
      include ("Writeit.php");
      $user_id  = $_SESSION["user"]["user"]->user["id_personnel"];
    $Id=$this->_getAllParams();
    
   writevar($Id,'this is the ids array '); 
  
   $updateStudentTeam=new Model_Table_StudentTeam();
    $updateStudentTeam->setRights($Id);
    
    
  //  $params= array('id_student'=>$Id['id_student']);
 // writevar($Id['id_student'],'this is the id of the student in updateaction');

 
   $this->redirect("staff/team/id_student".$Id['id_student']."/id_personnel/".$Id['id_personnel']."/id_school/".$Id['id_school']);
   //$this->redirect("/staff/team/id_student/".$Id['id_student']."/id_personnel/".$user_id);
  //    $this->redirect('/student/seach');  
    }
    
    public function updatesAction()
    {
        include ("Writeit.php");
        
       
        $StudentsOfTeamMember=$this->_getAllParams();
    
    /*
     * Get a list of students 
     * 
     */
  
        
        $updateTeamMemberStudents = new Model_Table_StudentTeam();
        $updateTeamMemberStudents->setRightsAStaff($StudentsOfTeamMember);
        
     
       $teamMemberId=$StudentsOfTeamMember['id_personnel'];
   //   writevar($StudentsOfTeamMember,'this is the array');
     
       $this->redirect('/staff/team/id_student/'.$StudentsOfTeamMember['0_id_student']."/id_personnel/".$teamMemberId);
       
     
        
    }
    
    public function makeTeamList($students,$idS) {
        $inc=0;
        $teamList= new Model_Table_StudentTeam();
        $list=$teamList->getStudentsOnTeam($idS);
        
        foreach($students as $student) {  // all the students
        
            $found='no';
             foreach($list as $studentTeamList){   
                /*     writevar($idS,'id of staff ');
                  writevar($student['id_student'],'id of student from iep_student ');
                  writevar($studentTeamlist['id_student'],'this is the list of from the team table');
               */   
              
                if($student['id_student']==$studentTeamList['id_student'] and $studentTeamList['id_personnel']==$idS)                
                { 
                  /*  writevar($student['id_student'],'this is the student id');
                   writevar($list[$inc]['id_student'],'this is the list of students from ');
                    die(); */
                    $result[$inc]['id_personnel']=$idS;
                    $result[$inc]['position']="top";
                    $result[$inc]['name_first']=$student['name_first'];
                    $result[$inc]['name_last']=$student['name_last'];
                    $result[$inc]['id_student']=$student['id_student'];
                    $result[$inc]['flag_view']=$studentTeamList['flag_view'];
                    $result[$inc]['flag_edit']=$studentTeamList['flag_edit'];
                    $result[$inc]['flag_create']=$studentTeamList['flag_create'];
                    $result[$inc]['exists']='yes';
                    $found='yes';
                //    writevar($studentTeamList,'this is the result when it gets in there'); 
                    }
                 
               
               
            }
             
            if ($found=='no' ){
                $result[$inc]['id_personnel']=$idS;
                $result[$inc]['position']="bottom";
                $result[$inc]['name_first']=$student['name_first'];
                $result[$inc]['name_last']=$student['name_last'];
                $result[$inc]['id_student']=$student['id_student'];
                $result[$inc]['flag_view']='';
                $result[$inc]['flag_edit']='';
                $result[$inc]['flag_create']='';
                $result[$inc]['exists']='no';
                $found='no';          
             }
             $found="no";
             $inc=$inc+1;
        }
     //writevar($result,'these are the results');
       return $result; 
    }
    
    public function makeStudentTeamMemberList($students,$idStaff){
        
       
           $totalStudents=count($students);
        //  writevar($idStaff,'this is staff id');  // this works
          $teamMemberList= new Model_Table_StudentTeam();
          $idList = $teamMemberList->getStudentsOfTeamMember($idStaff);
        // writevar($idList,'this is the list of ids');
          
         
        }
    
        

        public function team2Action()
        
        {
            $county_sv = $_SESSION["user"]["user"]->user["id_county"];
            $district_sv = $_SESSION["user"]["user"]->user["id_district"];
            $user_id  = $_SESSION["user"]["user"]->user["id_personnel"];
            $privm = $_SESSION['user']['user']->privs[0]['class'];
            $this->idOfStaff=$user_id;
            $userb_id=$user_id;
            /*
             * NOte: this will work fine as long as each staff member is only a member of one district.  If multiple districts then
             * $_SESSION['user]['user']->privs[x]['class][ along with a different district and county id need to be gathered. This is important if somebody is a floating staff member.
             *
             */
            // Get all the staff
             
             
            include("Writeit.php");
        
        
            $field = $this->getRequest()->getParam('id_student');
            $user_id = $this->getRequest()->getParam('id_personnel');
        
        
            $this->view->user_id=$user_id;
        
            // Mike put this in to call  from the submenu item created in NavigationSubmenu.php line ~66 because not passing any parameters
            if($field=='' && $user_id =='') {
        
                $user_id=$userb_id;
                 
                $teamMembers = new Model_Table_StudentTeam();
                //    writevar($user_id,' this is user id');
                $studentsOfTeamMember= $teamMembers->getStudentsOfTeamMember($user_id);
                //   writevar($studentsOfTeamMember[0]['id_student'],'this is student of team member ');
                $field=$studentsOfTeamMember[0]['id_student'];
                 
            }
        
            $iep_district = new Model_Table_IepDistrict();
            $nameManager = new Model_Table_IepPersonnel();
            $userPrivileges = new Model_Table_IepPrivileges;
        
        
        
            $student = new Model_Table_StudentTable2();
            $infoStudent =$student->studentInfo($field);
            //  writevar($infoStudent,'this is the list of students');
             
            $studentList=$student->getStudentList($county_sv, $district_sv);
            //  writevar($studentList,'this is the student list ');
            $this->view->studentList=$studentList;
        
        
        
            $paginator2 = Zend_Paginator::factory($studentList);
            $paginator2->setCurrentPageNumber($this->_getParam('page'));
            $paginator2->setItemCountPerPage(35);
            $this->view->paginator2 = $paginator2;
             
        
        
            //  writevar($infoStudent,'this is the student');  // this writes out all the student info from the student table for this kid
        
            $Rights='';
        
            // Get a complete staff list
            $districtStaff = new Model_Table_IepPersonnel();
            $staffList = $districtStaff->fetchAll($districtStaff->select()
                ->where( 'id_district = ?', $district_sv)
                ->where('id_county =?',$county_sv)
                ->where('status =?',"Active")
        
                ->order('name_last'));
        
            // add the create-edit field and the default is create
        
             
            //  writevar($staffList, "this is the staff list");
        
            $this->view->districtList=$staffList;
             
        
            $paginator = Zend_Paginator::factory($staffList);
            $paginator->setCurrentPageNumber($this->_getParam('page'));
            $paginator->setItemCountPerPage(20);
            $this->view->paginator = $paginator;
        
        
             
        
        
        
        
            // Get a list of all the team_members for a particular studetn
            $teamMembers = new Model_Table_StudentTeam();
            //writevar($field,'this is the student id field for ka');
            $staffMemberRights= $teamMembers->fetchAll($teamMembers->select()
                ->where('id_student =?',$field));
        
            //   $rowCount = count($staffMemberRights);
            //   writevar($teamMembers['id_personnel'],'existing team members');
        
            // set the condition for create or update and tack it onto the stafflist array
        
            foreach($staffMemberRights as $Rights) {
        
                foreach($staffList as $Staff)  {
                    //   writevar($Staff['id_personnel'],'this is id personnel');
                    $staffInTable['id_personnel']=$Staff['id_personnel'];
                    $staffInTable['exist']='f';
                    // writevar($staffInTable,'here is the staff in table');
        
                }
        
                 
        
        
        
        
        
        
                $this->view->staffMemberRights=$staffMemberRights;
                 
        
                 
        
                 
                $this->idOfStaff=$user_id;
        
                 
                //  writevar($data,'we managed to get here');
                $this->view->somedata=$infoStudent;
                // writevar($studentList,'this is the infoStudent array');
        
                $getList = new Model_Table_StudentTable2();
                $studentList= $getList->getStudentList($county_sv,$district_sv);
        
                $ViewTeamList = $this->makeStudentTeamMemberList($studentList,$this->idOfStaff);
                 
                $this->view->STeamList=$ViewTeamList;
        
                 
                 
                /* Now get the list of students for a team member
                 *
                 *
                  
                 */
                $teamMembers = new Model_Table_StudentTeam();
                //    writevar($user_id,' this is user id');
                $studentsOfTeamMember= $teamMembers->getStudentsOfTeamMember($user_id);
                // writevar($studentsOfTeamMember,'these are the students of this team member');  // itworks with the iep_team_member table
        
                // INITIALIZE an array we can pass to the vie
                $countTotalStudents=count($studentList);
                $countStudentsOfTeamMember=count($studentsOfTeamMember);
                //  writevar($countTotalStudents,'this is a count of the total number of students');
                //  writevar($countStudentsOfTeamMember,'this is a count of the students for a team member');
                // x is the outer and y is the inner.  Going to assign one big array and pass it to the view.
                $x=0;
                 
                while($x < $countTotalStudents) {
                    //   writevar($x,'x at the top');
                    //   writevar($studentList[$x]['id_student'],'this is the student x right at top');
                    $y=0;
                    $found= 'no';
                    //  writevar($studentList[$x]['id_student'],'this is the student list ');
        
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
             
        
        
        } // end of teame funciton
        
        
    public function teamAction() 
    {
     include("Writeit.php");
        $county_sv = $_SESSION["user"]["user"]->user["id_county"];
        $district_sv = $_SESSION["user"]["user"]->user["id_district"];
        $user_id  = $_SESSION["user"]["user"]->user["id_personnel"];
        $privm = $_SESSION['user']['user']->privs[0]['class'];
       
        $this->idOfStaff=$user_id;
        $userb_id=$user_id;
        /*
         * NOte: this will work fine as long as each staff member is only a member of one district.  If multiple districts then 
         * $_SESSION['user]['user']->privs[x]['class][ along with a different district and county id need to be gathered. This is important if somebody is a floating staff member.
         * 
        */
       // Get all the staff
       
          $schoolName = new Model_Table_School();
         $t=$schoolName->districtSchools($county_sv,$district_sv);
         $cnt=count($t);        
         $this->view->schoolName=$t;
         
        
         
         
         $studentId = $this->getRequest()->getParam('id_student');
         $user_id = $this->getRequest()->getParam('id_personnel');
         $school_sv1=$this->getRequest()->getParam('id_school');
         $this->view->id_school=$school_sv1;
         $this->view->id_user=$user_id;
         $school_sv=$school_sv1;
            
       
         
       
       
     
      
        
        //Setup finding the students name to be displayed at top of second column;
        // Once when you hit the menu Team Students tab and one for when you click on the student in the list.
        
        $findName=new Model_Table_StudentTable2();
        $teamMembers = new Model_Table_StudentTeam();
        
        if($studentId=='' && $user_id =='') {
           
           /* Check to see if the id school is sent via the school link on team.phtml.
           */
            
            if($school_sv !='') {
                /*
                 Somebody entered the school on the right hand side.  We havew the school number
                 so we  need to find any student at that school with team members.
                 */
               
                
                $student = new Model_Table_StudentTable2();
                $studentList=$student->getStudentList($county_sv, $district_sv,$school_sv);
                
                $found='false';
                $num=count($studentList);
                $x=0;
                
                While($found=='false' && $x<$num){   
                $teamMembers = new Model_Table_StudentTeam();
                $staffMemberRights= $teamMembers->fetchrow($teamMembers->select()
                    ->where('id_student =?',$studentList[$x]['id_student']));
                
                   if($staffMemberRights['id_student']==$studentList[$x]['id_student']&& $staffMemberRights=='Active') {
                    
                    $user_id=$staffMemberRights['id_personnel'];
                    $studentId=$staffMemberRights['id_student'];
                    $nameStudent=$studentList[$x]['name_first']." ".$studentList['name_last'];
                    $this->view->id_user=$user_id;
                    $this->view->nameStudentFull=$nameStudent;
                    $found='true';
                    
                    }
                   $x=$x+1;
                 }
                
                }
                
              else {
               $user_id=$userb_id;           
                       
               $studentsOfTeamMember= $teamMembers->getStudentsOfTeamMember($user_id);           
               $studentId=$studentsOfTeamMember[0]['id_student'];           
               $nameStudent=$studentData[0]['name_first']." ".$studentData[0]['name_last'];
               $school_sv=$studentData[0]['id_school'];      
               $IdSchool= $school_sv1;
               $this->view->id_school=$school_sv1;            
               $this->view->id_user=$user_id;
               $this->view->nameStudentFull=$nameStudent;
             }
            }   // end of missing the ids 
          
             
     
        
           
    
      
        // This gets the list of staff members from the iep_privileges table and not the iep_personnel table
        // Mike D 9-29-2016
        $districtStaff = new Model_Table_IepPersonnel();
        $staffList=$districtStaff->getNameFromPrivTable($county_sv,$district_sv,$school_sv);
        $this->view->districtList=$staffList;
       
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
  
       
      //  writevar($data,'we managed to get here');
     //  $this->view->somedata=$infoStudent;
       // writevar($studentList,'this is the infoStudent array');
      
        
    /* Mike took this out 10-8-2016
     *     
        $getList = new Model_Table_StudentTable2();
        $studentList= $getList->getStudentList($county_sv,$district_sv,$school_sv);
        
       $ViewTeamList = $this->makeStudentTeamMemberList($studentList,$this->idOfStaff);
      
       $this->view->STeamList=$ViewTeamList;
    */
       
       
       /* Now get the list of students for a team member
        * 
        * 
   
        */
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


public function addotherstaffAction() {
    include("Writeit.php");
     
     
    /*
     * This takes the parameters from the /district/view/name_district/
     */
    $identity=$this->_getAllParams();
    // writevar($identity,'thi is the users identity');
    $this->view->identity=$identity;

    $iep_district = new Model_Table_IepDistrict();
    $this->view->identityRemoteDistrict=$iep_district->getIepDistrictName($identity['id_county'],$identity['id_district']);
    //  writevar($this->view->identityRemoteDistrict,'this is the identity of the remote district');

    // figure out the district name
    $county_sv = $_SESSION["user"]["user"]->user["id_county"];
    $district_sv = $_SESSION["user"]["user"]->user["id_district"];
    $this->view->identityLocalDistrict=$iep_district->getIepDistrictName($county_sv,$district_sv);

    $schoolList= new Model_Table_IepSchoolm();
    $this->view->schooList=$schoolList->getIepSchoolInfo($county_sv,$district_sv);
    //  writevar($this->view->schooList,'this is the school list');
    //  writevar($this->view->identityLocalDistrict,'local district identity');

}  // end of addotherstaffAction
public function addremotestaffAction() {
    include("Writeit.php");
    $newRights=$this->_getAllParams();
    //  writevar($newRights,'this is the post data for the remote staff');
    $check= new Model_Table_IepPrivileges();
    $checkPrivileges=$check->getPrivilegesByUserM($newRights['id_personnel']);

    // writevar($checkPrivileges,'these are the privileges as they currently exxist');die();
    //  writevar($checkPrivileges[0]['class'],'these are the privileges as they currently exxist');
    $county_sv = $_SESSION["user"]["user"]->user["id_county"];
    $district_sv = $_SESSION["user"]["user"]->user["id_district"];


    // get  a list of the schools

     
    /*
     * Check to see if the other district id has privileges in curren district as inactive or removed
     */
    $x=0;
    $alreadyExists='no';
    $rowCount=count($checkPrivileges);
   // writevar($rowCount,'this is the row count');
     
    for($x=0;$x<$rowCount;$x++){
/*
        writevar($county_sv,'this is the session variable for county');
        writevar($district_sv,'this is the session variable for district');
        writevar($checkPrivileges[$x]['id_county'],'this is the  variable for county');
        writevar($checkPrivileges[$x]['id_district'],'this is the  variable for district');
        writevar($checkPrivileges[$x]['id_school'],'this is the id of the school'); */
        if($county_sv==$checkPrivileges[$x]['id_county']&& $district_sv==$checkPrivileges[$x]['id_district']){
            $alreadyExists='yes';
        }

    }

     

    writevar($alreadyExists,'already exists');
    if ($alreadyExists=='no'){
        $addTeams=new Model_Table_IepPrivileges();
        $addTeams->updatePrivilegesByUserM($newRights['id_personnel'],$county_sv,$district_sv,$newRights['class'],$newRights['id_school']);

    }
    $this->redirect('/staff/team/');

} //end of function
} // end of class

    