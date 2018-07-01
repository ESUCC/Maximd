<?php

class Zend_View_Helper_UserRole extends Zend_View_Helper_Abstract
{

    /**
     * Returns the user role menu
     * 
     * @return string
     */
    public function UserRole($id_student)
    {
        $student_auth = new App_Auth_StudentAuthenticator();
        $access = $student_auth->validateStudentAccess($id_student, new Zend_Session_Namespace('user'));
        return $access->description;
    }
}