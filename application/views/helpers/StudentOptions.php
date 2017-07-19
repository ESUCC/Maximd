<?php

class Zend_View_Helper_StudentOptions extends Zend_View_Helper_Abstract
{

    /**
     * Returns the student options menu for search
     * 
     * @return string
     */
    
    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    public function StudentOptions($id_student, $id_district = false)
    {
        
    	$limitedRollout = new Model_LimitedRollout(
    			Zend_Registry::get('limited-rollout'),
				new Model_Table_IepStudent()
		);
    	  
    	$iepUrls = Zend_Registry::get('iep-urls');
    	
        $options = '<option value="">Choose...</option>';
        $student_auth = new App_Auth_StudentAuthenticator();
        $access = $student_auth->validateStudentAccess($id_student, new Zend_Session_Namespace('user'));
        
        $this->writevar1($access,'this is the access ');
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
        }

        if ($limitedRollout->studentIsInLimitedRolloutDistrictForResource($id_student, 'edit-student', $id_district)) {
	        if ($accessArrayObj->accessArray ['view'] ['access']) {
	            $options .= '<option value="/student/view/id_student/'.$id_student.'">View Student</option>';
	        }
	        if ($accessArrayObj->accessArray ['edit'] ['access']) {
	            $options .= '<option value="/student/edit/id_student/'.$id_student.'">Edit Student</option>';        
	        }
        } else {
	        if ($accessArrayObj->accessArray ['view'] ['access']) {
	            $options .= '<option value="'.$iepUrls['studentOptions'].$id_student.'&option=view">View Student</option>';
	        }
	        if ($accessArrayObj->accessArray ['edit'] ['access']) {
	            $options .= '<option value="'.$iepUrls['studentOptions'].$id_student.'&option=edit">Edit Student</option>';
	        }
        }
        
        if (isset ( $accessArrayObj->accessArray ['charting'] ) && $accessArrayObj->accessArray ['charting'] ['access']) {
            $options .= '<option value="'.$iepUrls['studentOptions'].$id_student.'&option=charting">Student Charting</option>';
        }
        if ($accessArrayObj->accessArray ['parents'] ['access']) {
            $options .= '<option value="'.$iepUrls['studentOptions'].$id_student.'&option=parents">Parent/Guardians</option>';
        }
        if ($accessArrayObj->accessArray ['team'] ['access']) {
            $options .= '<option value="'.$iepUrls['studentOptions'].$id_student.'&option=team">Student Team</option>';
        }
        
        if ($accessArrayObj->accessArray ['forms'] ['access']) {
        	$options .= '<option value="/student/search-forms/id_student/'.$id_student.'">Student Forms</option>';
        }
    	
        if ($accessArrayObj->accessArray ['log'] ['access']) {
            $options .= '<option value="'.$iepUrls['studentOptions'].$id_student.'&option=log">Student Log</option>';
        }
        if (!empty($accessArrayObj->accessArray ['delete'] ['access'])) {
            $options .= '<option value="'.$iepUrls['studentOptions'].$id_student.'&option=delete">Delete Student</option>';
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