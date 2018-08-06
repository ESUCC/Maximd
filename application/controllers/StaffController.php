<?php
class StaffController extends Zend_Controller_Action
{

    protected  $justAap ='t';
    protected  $idOfStaff ='';

    public $IdSchool;
    public $IdPersonnel;
    public $IdStudent;
    public $globalPrvileges;


    function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }

    public function indexAction()
    {

        $sessUser = new Zend_Session_Namespace ( 'user' );
        $this->view->id_personnel = $sessUser->sessIdUser;
        $id_personnel=$this->view->id_personnel;
        $this->view->name_full=$_SESSION["user"]["user"]->user["name_full"];


        // Get the demographic info on the user and the privileges for that user.
        $personnelObj = new Model_Table_PersonnelTable();
        $privilegesObj = new Model_Table_IepPrivileges();


        // Mike added this 2-14-2017 because it was showing multiples of the same district in the pull down.
        $temp=$privilegesObj->getPrivilegesmike($id_personnel);

        //$this->writevar1($temp,'this is the value of the temp variable');

        $newTemp[0]=$temp[0];

        $x=0;

        // Mike added this 7-19-2017 in order to Check for superadmin

        $privileges=$_SESSION['user']['user']->privs;
        // $privileges=$this->privsUser;
        $superAdmin=false;


        foreach($privileges as $privs){
            if($privs['class']==1 && $privs['status']=='Active') $superAdmin=true;
        }
        // End of Mike add 7-19-2017

        $this->view->superAdmin=$superAdmin;
   //     $this->writevar1($superAdmin,'this is superadmin');
        if($superAdmin == true){
            $allDists=new Model_Table_IepDistrict();
            $this->view->allDist =$allDists->getAllDistricts();

        }

        foreach($temp as $temporary){
            $found='no';
          //  writevar($temporary['name_district'],'name of the district');
             foreach($newTemp as $newt){
                 if($newt['name_district']==$temporary['name_district']) $found='yes';

              }

            if($found=='no'){
                $x=$x+1;
                $newTemp[$x]=$temporary;

            }

          }





        $this->view->privileges = $newTemp;




        // Get a list of the schools and throw them to the idex view

        $id_district=$_SESSION["user"]["user"]->user["id_district"];
        $id_county=$_SESSION["user"]["user"]->user["id_county"];

        // Get a list of the schols from the model and bring it back
        $schoolList= new Model_Table_School();

        $listOfSchools= $schoolList->districtSchools($id_county,$id_district);
        $this->view->schools=$listOfSchools;



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




        // Mike added this 2-14-2017 because it was showing multiples of the same district in the pull down.
        $temp=$privilegesObj->getPrivileges($id_personnel);

        $newTemp[0]=$temp[0];
        //   writevar($temp,'lets make sure we got it.');

        $x=0;
        foreach($temp as $temporary){
            $found='no';
            foreach($newTemp as $newt){
                if($newt['name_district']==$temporary['name_district']) $found='yes';
            }

            if($found=='no'){
                $x=$x+1;
                $newTemp[$x]=$temporary;

            }
        }

        $this->view->privileges = $newTemp;

        $id_district=$_SESSION["user"]["user"]->user["id_district"];
        $id_county=$_SESSION["user"]["user"]->user["id_county"];

        // Get a list of the schols from the model and bring it back
        $schoolList= new Model_Table_School();

        $listOfSchools= $schoolList->districtSchools($id_county,$id_district);
        $this->view->schools=$listOfSchools;
   }

    public function updateStafAddAction()
    {
        //include ("Writeit.php");
        $user_id  = $_SESSION["user"]["user"]->user["id_personnel"];
        $Id=$this->_getAllParams();

        //writevar($Id,'this is the ids array ');


        $updateStudentTeam=new Model_Table_StudentTeam();
        $updateStudentTeam->setRights($Id);


        //  $params= array('id_student'=>$Id['id_student']);
     //   writevar($Id,'this is the id of the student in updateaction');

        $this->redirect("staff/team/id_county/".$Id['id_county']."/id_district/".$Id['id_district']."/id_school/".$Id['id_school']."/id_student/".$Id['id_student']."/id_personnel/".$user_id."/status/true");
        //$this->redirect("staff/team/id_county/id_district/id_student/".$Id['id_student']."/id_personnel/".$Id['id_personnel']."/id_school/".$Id['id_school']);
        //$this->redirect("/staff/team/id_student/".$Id['id_student']."/id_personnel/".$user_id);
        //    $this->redirect('/student/seach');
    }


    public function updateAction()
    {

    //  include ("Writeit.php");
      $user_id  = $_SESSION["user"]["user"]->user["id_personnel"];
    $Id=$this->_getAllParams();

  //   writevar($Id,'this is the ids array ');

   $classLevel=10;
   $iep_priv = new Model_Table_PrivilegeTable();
    $UserPrivTable=$iep_priv->getUserInfo2($classLevel);
 //    writevar($UserPrivTable,'these are the privileges for this user');

   /*
    * Mike added the following section on 2-15-2017 so that only those authorized to do so can update the iep_student_team table.
    * case managers, school and associate school managers can update teams if they have the correct rights to the district school only
    * DMs and ADMS can update anything.
    *
    */

   if($UserPrivTable !=0){
       // check to see if rights to the school

       $updateStudentTeam=new Model_Table_StudentTeam();

       // Mike had to put this in to take into account multiple class entries for the same person and for the same district and school
       // Unfortunately, there are a lot.
       $incremented=0;

       foreach($UserPrivTable as $userPriv){
           // check on rights to the School and Asst School admins and Case Managers
           if($userPriv['id_district']==$Id['id_district'] && $userPriv['id_county']==$Id['id_county']
               && $userPriv['id_school']==$Id['id_school'] && $userPriv['class']<=6 && $incremented==0
               &&$userPriv['status']=='Active') {
           //        writevar($userPriv,'this is the userPrivs to make sure the if works.');
                   $updateStudentTeam->setRights($Id);
                   $incremented=1;
           }

           // Check rights of District Manager and Associate District Manager
           if($userPriv['id_district']==$Id['id_district'] && $userPriv['id_county']==$Id['id_county']
               && $userPriv['class']<=3 && $userPriv['status']=='Active' && $incremented==0) {
               $updateStudentTeam->setRights($Id);
               $incremented=1;
               }

       }
    }  // end of the if statments.


  //  $params= array('id_student'=>$Id['id_student']);
 //writevar($Id,'this is the id of the student in updateaction');

 $this->redirect("staff/team/id_county/".$Id['id_county']."/id_district/".$Id['id_district']."/id_school/".$Id['id_school']."/id_student/".$Id['id_student']."/id_personnel/".$user_id."/status/true");
   //$this->redirect("staff/team/id_county/id_district/id_student/".$Id['id_student']."/id_personnel/".$Id['id_personnel']."/id_school/".$Id['id_school']);
   //$this->redirect("/staff/team/id_student/".$Id['id_student']."/id_personnel/".$user_id);
  //    $this->redirect('/student/seach');
    }

    public function updatesAction()
    {
     //   include ("Writeit.php");


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

           // include("Writeit.php");
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
          //  $this->writevar1($t,'this is a list of schools');
            $this->view->schoolName=$t;



            // Get a list of teachers at a school from the iep_student_team table using the iep_personnel to get their names


            $districtStaff = new Model_Table_IepPersonnel();

            $nameStaffMember=$districtStaff->getIepPersonnel($user_id);
            $staffMemberFullName = $nameStaffMember['name_first']." ".$nameStaffMember['name_last'];
            $this->view->staffMemberFullName=$staffMemberFullName;
            $staffMemberId=$nameStaffMember['id_personnel'];
            //$this->writevar1($nameStaffMember,'this is the name of the staff member line 372');

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

     $user_id=$this->getRequest()->getParam('id_personnel');

     // if coming from the index.phtml page there is no personnel id that is captured. Thus set one!
     if($user_id<1){
        $user_id  = $_SESSION["user"]["user"]->user["id_personnel"];

     }


     $studentId=$this->getRequest()->getParam('id_student');
     $county_sv= $this->getRequest()->getParam('id_county');
     $district_sv = $this->getRequest()->getParam('id_district');
     $school_sv=$this->getRequest()->getParam('id_school');



     $menuLink=false;
     if($studentId==null ){
         $menuLink=true;
     }
     $this->view->menuLink=$menuLink;





   //  $this->writevar1($school_sv,'this is the school in staffcontroller');
     $schoolName = new Model_Table_School();
     $t=$schoolName->districtSchools($county_sv,$district_sv);
     $cnt=count($t);

     // added by Mike Aug 31


     $x=0;
     $finalList=null;
     $siteAdmin=false;
     $schoolAd='000';
     $schoolCase='';

     foreach($t as $schol){

         foreach($_SESSION["user"]["user"]->privs as $privs ) {

         // Mike added this if class==1  9-5-2017 in order to make slane or class=1 work
          if($privs['class']==1 && $privs['status']=='Active' ){
              $siteAdmin=true;
              $x=$x+1;
          }

          if($privs['class']<=3 && $privs['id_county']==$schol['id_county']&&
              $privs['id_district']==$schol['id_district']&&
               $privs['status']=='Active') {
                  $siteAdmin=true;

                  $x=$x+1;
              }

              if($privs['class']<=5 && $privs['class'] >3 &&
                  $privs['id_county']==$schol['id_county']&&
                  $privs['id_district']==$schol['id_district']&&
                  $privs['id_school']==$schol['id_school']&&
                  $privs['status']=='Active') {
                    $finalList[$x]=$schol;
                    $schoolAd=$schol['id_school'];
                    $x=$x+1;

                  }

              if($privs['class']==6  &&
                  $privs['id_county']==$schol['id_county']&&
                  $privs['id_district']==$schol['id_district']&&
                  $privs['id_school']==$schol['id_school']&&
                  $privs['status']=='Active') {

             //     $school_sv=$schol['id_school'];
                  $finalList[$x]=$schol;
                  $schoolAd=$schol['id_school'];
                  $schoolCase=$schol['id_school']."-".$schoolCase;
                  $x=$x+1;
                // $this->writevar1($schol,'this is the school in the loop'." ".$privs['status']);
              }


         }

        }



        if($siteAdmin==false){
            $this->view->schoolName=$finalList;
       //     $this->writevar1($finalList,'this is the final list of schools for mark');
        }
        if($siteAdmin==true) {
            $this->view->schoolName=$t;

           // $this->writevar1($this->view->schoolName,'this is the final list of schools for admin');
        }


     // end Mike add Aug 31
  //   $this->view->schoolName=$t;
     $this->view->user_id=$user_id;
     $this->view->id_district=$district_sv;
     $this->view->id_county=$county_sv;
     $this->view->id_school=$school_sv;

   // $this->writevar1($school_sv,'id of the school2');

     $student = new Model_Table_StudentTable2();
     $studentList=$student->getStudentList($county_sv, $district_sv,$school_sv);

     $z=0;



       //  $this->writevar1($studentList,'this is the student list');

       $subMenuLink=false;


       // Mike added 9-1-2017
       $schoolCase=explode("-",$schoolCase);

       $IsCaseMgr=false;
       if($studentId==''){



          foreach($schoolCase as $slCase){
           if($slCase==$school_sv){

               foreach($studentList as $stu){
                   if ($stu['id_case_mgr']==$user_id){
                       $studentId=$stu['id_student'];
                       $IsCaseMgr=true;
                   }


                 }
           }
         }
           if($IsCaseMgr==false) $studentId=$studentList[1]['id_student'];
       }


       $found='false';
       $num=count($studentList);
       $x=0;




       //get the students name
       $cseMgr= new Model_Table_IepPersonnel();
       $studentName= new Model_Table_StudentTable2();
  //     writevar($studentId,'this is the student id');
       $nameStudentFull= $studentName->fetchRow($studentName->select()
                                     ->where('id_student =?',$studentId));

     //  $this->writevar1($nameStudentFull,'student full name');
       $this->view->nameStudentFull=$nameStudentFull['name_first']." ".$nameStudentFull['name_last'];
       $this->view->idStudentFull=$nameStudentFull['id_student'];
       $caseMgr=$cseMgr->getIepPersonnel($nameStudentFull['id_case_mgr']);
       $this->view->caseManager=$caseMgr['name_first'].' '.$caseMgr['name_last'];



     //  $this->writevar1($nameStudentFull['id_student'],'this is the id of the student');

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

          //  $this->writevar1($studentData[0]['id_school'],'this is the student name');
           $this->view->nameStudentFull=$nameStudent;
         //  $this->writevar1($nameStudent,'this is another full name of  the student');
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
        $this->view->studentList = $studentList;


        // Mike added 7-25-2017 in order to get the list of students
        // for case managers.
        $case_mgr=false;
        $allowView=false;
        $this->view->case_mgr=false;

        // Mike added 8-4-2017 so that case managers dont see any staff for a student at the
        // top of the Student Team.
        $this->view->initialList=true;

        // Mike added 8-30-2017 DM case manager variable because did not take into account
        // could be something else in the same district.  In case that had issues
        // jkarnatz1 could she was case manager and district manager in NB School Distr=
        $Admin_case_mgr=false;

        foreach($_SESSION["user"]["user"]->privs as $privs ) {

            if($privs['id_county']==$county_sv && $privs['id_district']==$district_sv
                && $privs['id_school']==$school_sv && $privs['class']=='6'
                && $privs['status']=='Active') {
                    $case_mgr=true;
                    $this->view->case_mgr=$case_mgr;


            }

            if($privs['id_county']==$county_sv && $privs['id_district']==$district_sv
                    && $privs['id_school']==$school_sv && $privs['class']<='5'
                    && $privs['status']=='Active') {
                        $allowView=true;
                        $Admin_case_mgr=true;

            }


            if($privs['id_county']==$county_sv && $privs['id_district']==$district_sv
               && $privs['class']<=3 && $privs['status']=='Active') {
                   $Admin_case_mgr=true;
                   $allowView=true;
               }


               // Mike added this 9-5-2017 in order to make slane work or class==1
               if($privs['class']==1 && $privs['status']=='Active') {
                       $Admin_case_mgr=true;
                       $allowView=true;
                   }



        }

        $x=0;

        if ($Admin_case_mgr==true) $case_mgr=false;


        if($case_mgr==true){


            $studentListCaseMgr=null;
            foreach($studentList as $stuList){
                if ($stuList['id_case_mgr']==$_SESSION['user']['id_personnel']) {
                    $allowView=true;
                    $studentListCaseMgr[$x]=$stuList;
                    $x=$x+1;

               //    $this->writevar1($studentListCaseMgr,'this is the student list');
                }

            }
            $studentList=$studentListCaseMgr;
            $this->view->studentList=$studentList;
            $checkHack=true;


           foreach($this->view->studentList as $stu){
                if($stu['id_student']==$studentId) {
                    $checkHack=false;
                   // $this->writevar1($stu['id_student'],' equals '.$studentId);
                }
            }
         //   $this->writevar1($studentId,'this is the student id');
            if ($checkHack==true && $subMenuLink==false) {
               $this->_redirect( '/student/search');
              }



         //   $this->writevar1($studentList,'this is the student list');
          //  $this->view->nameStudentFull=$studentList[0]['name_first']." ".$studentList[0]['name_last'];

            // Mike added this 8-4-2017 so that nothing shows up when teachers click the
            // Students Team button from the nav menu

         //   $this->writevar1($allowView,'allow view and menulink');

            if($menuLink==true) {
                $this->view->nameStudentFull="Please Select a Student!";
                $this->view->initialList=false;

            }



        }

        if($allowView==false){

            $studentList=null;
            $studentList[0]['id_student']='00000000';
            $studentList[1]['name_last']='You do not have rights to view students on this page';
            $this->view->nameStudentFull="NO RIGHTS";
            $this->view->user_id='0000';
            $this->view->studentId='0000';
            $this->view->studentId='0000';

        }

        $this->view->allowView=$allowView;

       // End of Mike add 7-25-2017 case manager configuration

      //  $this->writevar1($studentList,'list of the students');
        $paginator2 = Zend_Paginator::factory($studentList);
        $paginator2->setCurrentPageNumber($this->_getParam('page'));
        $paginator2->setItemCountPerPage(35);
        $this->view->paginator2 = $paginator2;


        // Get a list of all the team_members for a particular studetn
        $teamMembers = new Model_Table_StudentTeam();
        $staffMemberRights= $teamMembers->fetchAll($teamMembers->select()
        ->where('id_student =?',$studentId));
       // $this->writevar1($staffMemberRights,'these are the staff member rights');

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




    $this->_helper->layout()->disableLayout();



   // include("Writeit.php");
    $sessUser = new Zend_Session_Namespace ( 'user' );
    $user_id = $sessUser->sessIdUser;

    $personnelObj = new Model_Table_PersonnelTable();
    $privilegesObj = new Model_Table_IepPrivileges();

   /* This was added by Mike on 7-7-2017 so that only districts that they have rights to
    * are they then allowed to change privileges.
    * Many staff members have access to staff at other school districts.
    * need to list all the districts the logged in user has access to.
    * Need to get this info in order to display the schools in the district from /personnelm/index.phtml */

    $allPrivs=$privilegesObj->getPrivileges($user_id);
  //  asort($temp);
  $x=0;

  $superAdmin=false;
  $distInclude=array();
   $nameDistrict=array();
    foreach($allPrivs as $distView){

       if($distView['class']==1 && $distView['status']=='Active') $superAdmin=true;

       if($distView['class']<= 6 && $distView['status']=='Active') {
         $nameDistrict[$x]=$distView['name_district'];
         $distInclude[$x]=$distView;
       // $this->writevar1($distInclude,'this is the dist include');
         $x=$x+1;
      }
    }
    /*  Mike finished this July 7th
     * Mike needs to finish this so that districts only pop up once.
     * The class<= 6 helps a lot on dups.
     */



    //$this->writevar1($distInclude,'this is a list of district schools');
    // $this->view->listOfDistricts=$allPrivs;

    // Mike on 7-17-2017 encased this in superAdin or not

    if($superAdmin==false){
        $this->view->listOfDistricts=$distInclude;
    }

    if($superAdmin==true){
        $allDist=new Model_Table_IepDistrict();
        $allDistricts=$allDist->getAllDistricts();
        $this->view->listOfDistricts=$allDistricts;
    }



    /*
     * This takes the parameters from the /district/view/name_district/
     */
    $identity=$this->_getAllParams();

   //:q! $this->writevar1($identity,'line 734 staff controller');

   // echo "You do not have the correct rights to add this to a district";
   // die();


  // writevar($identity,'this is the identity');
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

    //writevar($this->view->schooList,'this is the school list');
    //  writevar($this->view->identityLocalDistrict,'local district identity');

}  // end of addotherstaffAction

public function addotherstaffsaveAction() {

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $request = $this->getRequest();
      //$this->writevar1($request,'this is add other staff to priv table');
//	echo $request->id_personnel."|".$request->id_county."|".$request->id_district."|".$request->id_school."|".$request->class;

        $privilegesObj = new Model_Table_IepPrivileges();



        $okToSave=$privilegesObj->updatePrivilegesByUserN($request->id_personnel,$request->id_county,$request->id_district,$request->class,$request->id_school);
        if($okToSave==true) {


          // echo $this->url(array('controller'=>'personnelm','action'=>'edit','id_personnel',$request->id_personnel));



             echo "<br><br><br><center>Saved</center>";
             echo '<center><a href="https://iepweb02dev.nebraskacloud.org/personnelm/edit/id_personnel/'.$request->id_personnel.'">Saved Privileges--Click to Continue</a>';
           // $this->_redirect('https://iepweb02dev.nebraskacloud.org/personnelm/edit/id_personnel/'.$request->id_personnel);
           }
        if($okToSave==false) echo "<br><br><br><center><font color=\"red\">You do not have the
            correct privileges <br>to add this staff member at this  Privilege Level!!</center>";
}

public function addotherstaffschoollistAction() {


        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $request = $this->getRequest();
        $schoolList = new Model_Table_IepSchoolm();

	    $result = $this->view->schooList=$schoolList->getIepSchoolInfo($request->id_county, $request->id_district);
	//$this->writevar1($result,'this is the school list');

	$sessUser = new Zend_Session_Namespace ( 'user' );
	$user_id = $sessUser->sessIdUser;
	$privilegesObj = new Model_Table_IepPrivileges();
	$allPrivs=$privilegesObj->getPrivileges($user_id);

	$finalResult=array();
	$x=0;
	$gotOne='no';

	$superAdmin=false;



	foreach($result as $resultCheck){
	    foreach($allPrivs as $privs){
	     $found='no';

	     // Mike added this July 17th in order for the list of schools to work
	     // for the superadmin
	     if($privs['class']==1 && $privs['status']=='Active') $superAdmin=true;
	     // end of Mike add

	     if($privs['class']<=3 && $privs['status']=='Active'
	         && $privs['id_county']==$resultCheck['id_county']
	         && $privs['id_district']==$resultCheck['id_district']) {
	         $finalResult[$x]=$resultCheck;
	         $x=$x+1;
	         $found='yes';
	         $gotOne='yes';
	     }

	     if($privs['class']<=5 && $privs['status']=='Active'
	        && $privs['id_school']==$resultCheck['id_school']
	         && $found=='no') {
	            $finalResult[$x]=$resultCheck;
	            $x=$x+1;
	            $gotOne='yes';
	        }
	 }
	}

	//echo "You don't have privileges to add staff to any of the district schools";

	//$this->writevar1($result,'this is the result');
  //  $this->writevar1($finalResult,'this is the final result');
    if($gotOne=='no'&& $superAdmin==false){
        $finalResult[0]['name_school']='You do not have rights to add user to this district!';

    }



// Mike changed this Jul7 17th in order for the list of schools to come out for the
// superAdmin
    if($gotOne!='no')
    $this->_helper->json->sendJson($finalResult);

    if($superAdmin==true)
      $this->_helper->json->sendJson($result);
}


public function addremotestaffAction() {
   // include("Writeit.php");
    $newRights=$this->_getAllParams();
   //  writevar($newRights,'this is the post data for the remote staff');

      $class=$newRights['class'];
      $id_local_county=$newRights['id_local_county'];
      $id_local_district=$newRights['id_local_district'];
      $id_personnel=$newRights['id_personnel'];
      $id_county=$newRights['id_county'];
      $id_district=$newRights['id_district'];
      $id_school=$newRights['id_school'];



    $check= new Model_Table_IepPrivileges();
    $checkPrivileges=$check->getPrivilegesByUserM($id_personnel);

  //  writevar($checkPrivileges,'these are the privileges as they currently exxist');
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

