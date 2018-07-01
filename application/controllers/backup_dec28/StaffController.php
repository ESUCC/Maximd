<?php 

class StaffController extends Zend_Controller_Action 
{
    
    protected  $justAap ='t';
    protected  $idOfStaff ='';
   
    public $IdSchool;
    public $IdPersonnel;
    public $IdStudent;
    public $globalPrvileges;
   
    public function indexAction() 
    {
       include("Writeit.php");
       
        $sessUser = new Zend_Session_Namespace ( 'user' );
        $this->view->id_personnel = $sessUser->sessIdUser;
        $id_personnel=$this->view->id_personnel;
        $this->view->name_full=$_SESSION["user"]["user"]->user["name_full"];
        
        
        // Get the demographic info on the user and the privileges for that user.
        $personnelObj = new Model_Table_PersonnelTable();
        $privilegesObj = new Model_Table_IepPrivileges();
        
        // Many staff members have access to staff at other school districts.
        //Need to get this info in order to display the schools in the district from /personnelm/index.phtml
        $temp=$privilegesObj->getPrivileges($id_personnel);
        $this->view->privileges = $temp;
      //  writevar( $this->view->privileges,'this is the list of the privileges');
        
       /*
        foreach($this->view->privileges as $key => $priv) {
            $this->view->privileges[$key]['access'] = $personnelObj->validatePrivAccess(
                $priv['id_county'],
                $priv['id_district'],
                $priv['id_school'],
                $priv['class'],
                $this->usersession->sessIdUser,
                $id_personnel
                );
        }
        */
        // Get a list of the schools and throw them to the idex view
        
        $id_district=$_SESSION["user"]["user"]->user["id_district"];
        $id_county=$_SESSION["user"]["user"]->user["id_county"];
        
        // Get a list of the schols from the model and bring it back
        $schoolList= new Model_Table_School();
        
        $listOfSchools= $schoolList->districtSchools($id_county,$id_district);
        $this->view->schools=$listOfSchools;
        
        // writevar($this->view->schools,'this is the school list');
        
        // Get a list of users registered as staff members at the school
        
        //Get a list of users registered at different schools, but still are assigned with privileges at this school
        writevar($temp,'this is the list of districts in the indexAction');
      
    }
    
    public function index2Action()
    {
        //  include("Writeit.php");
        $sessUser = new Zend_Session_Namespace ( 'user' );
        $this->view->id_personnel = $sessUser->sessIdUser;
        $id_personnel=$this->view->id_personnel;
        $this->view->name_full=$_SESSION["user"]["user"]->user["name_full"];
    
    
        // Get the demographic info on the user and the privileges for that user.
        $personnelObj = new Model_Table_PersonnelTable();
        $privilegesObj = new Model_Table_IepPrivileges();
    
        // Many staff members have access to staff at other school districts.
        //Need to get this info in order to display the schools in the district from /personnelm/index.phtml
    
        $this->view->privileges = $privilegesObj->getPrivileges($id_personnel);
        //  writevar( $this->view->privileges,'this is the list of the privileges');
    
        /*
         foreach($this->view->privileges as $key => $priv) {
         $this->view->privileges[$key]['access'] = $personnelObj->validatePrivAccess(
         $priv['id_county'],
         $priv['id_district'],
         $priv['id_school'],
         $priv['class'],
         $this->usersession->sessIdUser,
         $id_personnel
         );
         }
         */
        // Get a list of the schools and throw them to the idex view
    
        $id_district=$_SESSION["user"]["user"]->user["id_district"];
        $id_county=$_SESSION["user"]["user"]->user["id_county"];
    
        // Get a list of the schols from the model and bring it back
        $schoolList= new Model_Table_School();
    
        $listOfSchools= $schoolList->districtSchools($id_county,$id_district);
        $this->view->schools=$listOfSchools;
    
        // writevar($this->view->schools,'this is the school list');
    
        // Get a list of users registered as staff members at the school
    
        //Get a list of users registered at different schools, but still are assigned with privileges at this school
    
    }
    
    public function updateStafAddAction()
    {
        include ("Writeit.php");
        $user_id  = $_SESSION["user"]["user"]->user["id_personnel"];
        $Id=$this->_getAllParams();
    
        //writevar($Id,'this is the ids array ');
    
    
        $updateStudentTeam=new Model_Table_StudentTeam();
        $updateStudentTeam->setRights($Id);
    
    
        //  $params= array('id_student'=>$Id['id_student']);
        writevar($Id,'this is the id of the student in updateaction');
    
        $this->redirect("staff/team/id_county/".$Id['id_county']."/id_district/".$Id['id_district']."/id_school/".$Id['id_school']."/id_student/".$Id['id_student']."/id_personnel/".$user_id."/status/true");
        //$this->redirect("staff/team/id_county/id_district/id_student/".$Id['id_student']."/id_personnel/".$Id['id_personnel']."/id_school/".$Id['id_school']);
        //$this->redirect("/staff/team/id_student/".$Id['id_student']."/id_personnel/".$user_id);
        //    $this->redirect('/student/seach');
    }
    
    
    public function updateAction()
    {
      include ("Writeit.php");
      $user_id  = $_SESSION["user"]["user"]->user["id_personnel"];
    $Id=$this->_getAllParams();
    
   //writevar($Id,'this is the ids array '); 
  
  
   $updateStudentTeam=new Model_Table_StudentTeam();
    $updateStudentTeam->setRights($Id); 
    
    
  //  $params= array('id_student'=>$Id['id_student']);
 writevar($Id,'this is the id of the student in updateaction');
  
 $this->redirect("staff/team/id_county/".$Id['id_county']."/id_district/".$Id['id_district']."/id_school/".$Id['id_school']."/id_student/".$Id['id_student']."/id_personnel/".$user_id."/status/true");
   //$this->redirect("staff/team/id_county/id_district/id_student/".$Id['id_student']."/id_personnel/".$Id['id_personnel']."/id_school/".$Id['id_school']);
   //$this->redirect("/staff/team/id_student/".$Id['id_student']."/id_personnel/".$user_id);
  //    $this->redirect('/student/seach');  
    }
    
    public function updatesAction()
    {
        include ("Writeit.php");
          
       
        $StudentsOfTeamMember=$this->_getAllParams();
     //   writevar($StudentsOfTeamMember,'the whole list before going to the db.');
        $county_sv=$StudentsOfTeamMember['id_county'];
        $district_sv=$StudentsOfTeamMember['id_district'];
        $school_sv=$StudentsOfTeamMember['id_school'];
        $user_id=$StudentsOfTeamMember['id_personnel'];
     // writevar($StudentsOfTeamMember,'here is the list');die();
        
    /*
     * Get a list of students 
     * 
     */
  
        
        $updateTeamMemberStudents = new Model_Table_StudentTeam();
        
        $updateTeamMemberStudents->setRightsAStaff($StudentsOfTeamMember);
        
     
       $teamMemberId=$StudentsOfTeamMember['id_personnel'];
    //  writevar($StudentsOfTeamMember,'this is the array');
      
     
       $this->redirect('/staff/team2/id_personnel/'.$user_id."/id_county/".$county_sv."/id_district/".$district_sv."/id_school/".$school_sv);
       
     
        
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
    
            
            // set up the links in the view.
            
             $this->view->id_school=$school_sv;
             $this->view->id_county=$county_sv;
             $this->view->id_district=$district_sv;
             $this->view->user_id=$user_id;
            
            
            
            
                //clicked on a user 
             $this->view->id_school=$school_sv;
  
            // Get a school list
            $schoolName = new Model_Table_School();
            $t=$schoolName->districtSchools($county_sv,$district_sv);
            $cnt=count($t);
           // writevar($t,'this is a list of schools');
            $this->view->schoolName=$t;
            
         
                
            // Get a list of teachers at a school from the iep_student_team table using the iep_personnel to get their names
          
           
            $districtStaff = new Model_Table_IepPersonnel();
           
            $nameStaffMember=$districtStaff->getIepPersonnel($user_id);
            $staffMemberFullName = $nameStaffMember['name_first']." ".$nameStaffMember['name_last'];
            $this->view->staffMemberFullName=$staffMemberFullName;
            $staffMemberId=$nameStaffMember['id_personnel'];
            $this->view->staffMemberId=$staffMemberId;
            
            
            
            $staffList=$districtStaff->getNameFromPrivTable($county_sv,$district_sv,$school_sv);
            $this->view->districtList=$staffList;
            
            $paginator = Zend_Paginator::factory($staffList);
            $paginator->setCurrentPageNumber($this->_getParam('page'));
            $paginator->setItemCountPerPage(10);
            $this->view->paginator = $paginator;
           
            
            
            $distManagerStaff  = new Model_Table_IepPersonnel();
            $this->view->districtAdmin=$distManagerStaff->getAdmins($county_sv,$district_sv);
            
            
            
            // Get a list of Students at the school 
            $studentsAtSchool=new Model_Table_StudentTable2();
            $schoolStudents=$studentsAtSchool->getStudentList($county_sv,$district_sv,$school_sv);
       //     writevar($schoolStudents,'this is the array of other students');
            $this->view->studentList=$schoolStudents;
            
            $paginator1 = Zend_Paginator::factory($schoolStudents);
            $paginator1->setCurrentPageNumber($this->_getParam('page'));
            $paginator1->setItemCountPerPage(10);
            $this->view->paginator1 = $paginator1;
            
         //
         
         // Find students associted with each staff member.
           
            $studentOnTeam=new Model_Table_IepStudent();
            $this->view->studentsOfTeam=$studentOnTeam->getTeamStudents($user_id,$school_sv,$county_sv,$district_sv);
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


public function addotherstaffAction() {
    include("Writeit.php");
    $sessUser = new Zend_Session_Namespace ( 'user' );
    $user_id = $sessUser->sessIdUser;
   
    $personnelObj = new Model_Table_PersonnelTable();
    $privilegesObj = new Model_Table_IepPrivileges();
    
 
   /* Many staff members have access to staff at other school districts.
    * need to list all the districts the logged in user has access to.
    * Need to get this info in order to display the schools in the district from /personnelm/index.phtml */
   
    $temp=$privilegesObj->getPrivileges($user_id);
    
     $this->view->listOfDistricts=$temp;
     
     
    /*
     * This takes the parameters from the /district/view/name_district/
     */
    $identity=$this->_getAllParams();
  //  writevar($identity,'this is the identity');
    if($identity['id']!="0" )
    {
      $arrayId=explode(" ",$identity['id']);
      $localCounty=$arrayId[0];
      $localDistrict=$arrayId[1];
      
      
    }
    else {
        
            $localCounty = $_SESSION["user"]["user"]->user["id_county"];
            $localDistrict = $_SESSION["user"]["user"]->user["id_district"];
           
    }
    
    $addStaffId=$identity['id_personnel'];
    $addStaffDistrict=$identity['id_district'];
    $addStaffCounty=$identity['id_county'];
    
    $this->view->identityRemote=$identity;
   
  
    
    
        
 
    $iepRemoteDistrict=new Model_Table_IepDistrict();
    $this->view->identityRemoteDistrict=$iepRemoteDistrict->getIepDistrictName($addStaffCounty,$addStaffDistrict);
   // writevar($this->view->identityRemoteDistrict,'this is the remote district');
    
    // addStaff variable is where the staff member resides to be added   
    $iep_district = new Model_Table_IepDistrict();
    $this->view->identityLocalDistrict=$iep_district->getIepDistrictName($localCounty,$localDistrict);
    
    
    
    
   
   

    $schoolList= new Model_Table_IepSchoolm();
    $this->view->schooList=$schoolList->getIepSchoolInfo($localCounty,$localDistrict);
    
   //writevar($this->view->schooList,'this is the school list');
    //  writevar($this->view->identityLocalDistrict,'local district identity');

}  // end of addotherstaffAction


public function addremotestaffAction() {
    include("Writeit.php");
    $newRights=$this->_getAllParams();
    writevar($newRights,'this is the post data for the remote staff');
      die();
      $class=$newRights['class'];
      $id_local_county=$newRights['id_local_county'];
      $id_local_district=$newRights['id_local_district'];
      $id_personnel=$newRights['id_personnel'];
      $id_county=$newRights['id_county'];
      $id_district=$newRights['id_district'];
      $id_school=$newRights['id_school'];
      
      
      
    $check= new Model_Table_IepPrivileges();
    $checkPrivileges=$check->getPrivilegesByUserM($id_personnel);
   
   writevar($checkPrivileges,'these are the privileges as they currently exxist');
    //  writevar($checkPrivileges[0]['class'],'these are the privileges as they currently exxist');
   
    
    /*$county_sv = $_SESSION["user"]["user"]->user["id_county"];
    $district_sv = $_SESSION["user"]["user"]->user["id_district"];
   */
    
    

    // get  a list of the schools

     
    /*
     * Check to see if the other district id has privileges in curren district as inactive or removed
     */
    $y=0;
    $alreadyExists='no';
    $found='no';
    $rowCount=count($checkPrivileges);
   // writevar($rowCount,'this is the row count');
     
    for($x=0;$x<$rowCount;$x++){
        if($id_local_county==$checkPrivileges[$x]['id_county']&& $id_local_district==$checkPrivileges[$x]['id_district']&& 
           $id_personnel==$checkPrivileges[$x]['id_personnel']&& $checkPrivileges[$x]['class']==$class){
            $alreadyExists='yes';
            $found='yes';
            $checkPriv[$y]=$checkPrivileges[$x];
      //      writevar($checkPriv[$y],'this is the array result');
            $y=$y+1;
        }
       
    }
      
      $x=0;
      
      if($alreadyExists=='no'&& $class !='2'){
         $insertNew= new Model_Table_IepPrivileges();
         writevar($checkPrivileges[$x]['class'],'this is the class');
         $insertNew->updatePrivilegesByUserM($checkPrivileges[$x]['id_personnel'],$id_local_county,$id_local_district,$checkPrivileges[$x]['class'],
                                                   $id_school);
          }
          $updateOld= new Model_Table_IepPrivileges();
      if($alreadyExists=='yes'&& $class!=2){
          foreach($checkPriv as $exist){
              writevar($exist['id_personnel'],'this exists variable');
              $status="Active";
              $updateOld->updatePrivilegesByUserM2($exist['id_personnel'],$exist['id_county'],
                  $exist['id_district'],$exist['id_school'],$exist['class'],$status);
             
          }
      }
    
    $this->redirect('/personnelm/edit/id_personnel/'.$id_personnel);

   
} //end of function
} // end of class

    