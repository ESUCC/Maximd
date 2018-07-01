<?php

class Zend_View_Helper_StudentOptions extends Zend_View_Helper_Abstract
{

    /**
     * Returns the student options menu for search
     * 
     * @return string
     */ 
    public function StudentOptions($id_student, $id_district = false, $action = '')
    {
      //  include("Writeit.php");
    	$limitedRollout = new Model_LimitedRollout(
    			Zend_Registry::get('limited-rollout'),
				new Model_Table_IepStudent()
		);
    	
    	$iepUrls = Zend_Registry::get('iep-urls');
    	
        $options = '<option value="">Choose...</option>';
        $student_auth = new App_Auth_StudentAuthenticator();
        $access = $student_auth->validateStudentAccess($id_student, new Zend_Session_Namespace('user'));
       
        
        /* Mike added 2-7-2017 this section so that it would be able to get the Student's Team and Team's Students link ii
         * in the student pull down. 
        */
        $studentDemo=new Model_Table_IepStudent();
        $demographics=$studentDemo->getUserById($id_student);
        $cty=$demographics['id_county'];
        $dist=$demographics['id_district'];
        $school=$demographics['id_school'];
        // End Mike Add
        
        
      //  writevar($access,'this is access');
        if ('Team Member' == $access->description) {
            if ('viewaccess' == $access->access_level) {
                $accessArrayClassName = 'App_Auth_Role_' . str_replace ( ' ', '', $access->description ) . 'View';
                $accessArrayObj = new $accessArrayClassName ();
            } else {
                $accessArrayClassName = 'App_Auth_Role_' . str_replace ( ' ', '', $access->description ) . 'Edit';
                $accessArrayObj = new $accessArrayClassName ();
            }
        } else {
            $accessArrayClassName = 'App_Auth_Role_' . str_replace ( ' ', '', $access->description );
            $accessArrayObj = new $accessArrayClassName ();
           // writevar($accessArrayClassName,'this is the access role');
        }

        if ($limitedRollout->studentIsInLimitedRolloutDistrictForResource($id_student, 'edit-student', $id_district)) {
	        if ($accessArrayObj->accessArray ['view'] ['access']) {
	            $options .= '<option value="/student/view/id_student/'.$id_student.'">View Student</option>';
	        }
	        if ($accessArrayObj->accessArray ['edit'] ['access']) {
	            $options .= '<option value="/student/edit/id_student/'.$id_student.'">Edit Student</option>';        
	        }
        } else {
	       /* Mike commented this out 2-21-2017 because both were in here.
	        * 
	        * if ($accessArrayObj->accessArray ['view'] ['access']) {
	            $options .= '<option value="'.$iepUrls['studentOptions'].$id_student.'&option=view">View Student</option>';
	        }*/
	        if ($accessArrayObj->accessArray ['edit'] ['access']) {
	         //   $options .= '<option value="'.$iepUrls['studentOptions'].$id_student.'&option=edit">Edit Student</option>';
	            $options .= '<option value="/student/edit/id_student/'.$id_student.'">Edit Student</option>';
	        }
        }
         
        if (isset ( $accessArrayObj->accessArray ['charting'] ) && $accessArrayObj->accessArray ['charting'] ['access']) {
            $options .= '<option value="'.$iepUrls['studentOptions'].$id_student.'&option=charting">Student Charting</option>';
        }
        if ($accessArrayObj->accessArray ['parents'] ['access']) {
            $options .= '<option value="/parent/search/id_student/'.$id_student.'/">Parent/Guardians</option>';
        }
        
        // Mike added these Sept 12th
        if ($accessArrayObj->accessArray['team']['access'] && $action != "team" ) {

            $staff_id=$_SESSION["user"]["user"]->user["id_personnel"];
            
            // Mike Changed this so that the Student's Team Pull down would work.

            $options .= '<option value="/staff/team/id_county/'.$cty.'/id_district/'.$dist.'/id_student/'.$id_student.'/id_personnel/'.$staff_id.'/id_school/'.$school.'">Student\'s Team</option>';       

    	   // $options .= '<option value="https://'.$_SERVER['HTTP_HOST'].'/staff/team/id_student/'.$id_student.'/id_personnel/'.$staff_id.'">Student\'s Team</option>';
           // $options .= '<option value="'.$iepUrls['studentOptions'].$id_student.'&option=team">Student Team</option>';
        }
        
        if ($accessArrayObj->accessArray ['team'] ['access'] && $action != "team2" ) {
            $staff_id=$_SESSION["user"]["user"]->user["id_personnel"];
            
            // Mike changed this 2-7-2017 so that we would be able to see the students from a team perspective
            $options .= '<option value="/staff/team2/id_personnel/'.$staff_id.'/id_county/'.$cty.'/id_district/'.$dist.'/id_school/'.$school.'">Team\'s Students</option>';

            //$options .= '<option value="https://'.$_SERVER['HTTP_HOST'].'/staff/team2/id_student/'.$id_student.'/id_personnel/'.$staff_id.'">Team\'s Students</option>';
            // $options .= '<option value="'.$iepUrls['studentOptions'].$id_student.'&option=team">Student Team</option>';
        }
        
        
        
        if ($accessArrayObj->accessArray ['forms'] ['access']) {
        	$options .= '<option value="/student/search-forms/id_student/'.$id_student.'">Student Forms</option>';
        }
    	
        if ($accessArrayObj->accessArray ['log'] ['access']) {
            $options .= '<option value="/student/log/id_student/'.$id_student.'">Student Log</option>';
        }
        if (!empty($accessArrayObj->accessArray ['delete'] ['access'])) {
            $options .= '<option value="/student/delete/id_student/'.$id_student.'">Delete Student</option>';
        }
        if (!empty($accessArrayObj->accessArray ['delete'] ['access'])) {
            $options .= '<option value="/report/student-reports/id_student/'.$id_student.'">Student Reports</option>';
        }

        /*
         * Add Form Type options
         */
        $options .= '<optgroup label="Most Recent...">'
                  . '<option value="type/IEP/id_student/'.$id_student.'">IEP</option>'
                  . '<option value="type/MDT/id_student/'.$id_student.'">MDT</option>'
                  . '<option value="type/IFSP/id_student/'.$id_student.'">IFSP</option>'
                  . '<option value="type/Progress Report/id_student/'.$id_student.'">Progress Report</option>'
                  . '</optgroup>';
        
        return '<select id="student_options" class="studentOptions" name="student_options" ignore="1">'.$options.'</select>';
    }
}