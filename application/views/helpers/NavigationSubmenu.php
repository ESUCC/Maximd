<?php
class Zend_View_Helper_NavigationSubmenu extends Zend_View_Helper_Abstract
{
    public $returnText;

    public function navigationSubmenuAdmin($actionName)
    {
        $session = new Zend_Session_Namespace ( 'user' );
        $this->returnText .= '<ul id="nav2">';

        if(!$session->parent) {
            $this->returnText .= '  <li '.(($actionName == 'server') ? 'class="current"' : '').'><a href="/admin/server">Server</a></li>';
            $this->returnText .= '  <li '.(($actionName == 'sessions') ? 'class="current"' : '').'><a href="/admin/sessions">Sessions</a></li>';
            $this->returnText .= '  <li '.(($actionName == 'announcements') ? 'class="current"' : '').'><a href="/admin/announcements">Announcements</a></li>';
            $this->returnText .= '  <li '.(($actionName == 'dataadmin') ? 'class="current"' : '').'><a href="/admin/dataadmin">Data Admin</a></li>';
            $this->returnText .= '  <li '.(($actionName == 'reporting') ? 'class="current"' : '').'><a href="/admin/reporting">Reporting Admin</a></li>';
        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }



    public function navigationSubmenu($type = 'student')
    {
        switch(strtolower($type)) {
            default:
            case 'student':
                return $this->navigationSubmenuStudent();
                break;
            case 'personnel':
                return $this->navigationSubmenuPersonnel();
                break;
            case 'report':
                return $this->navigationSubmenuReport();
                break;
            case 'home':
                return $this->navigationSubmenuHome();
                break;
            case 'admin':
                return $this->navigationSubmenuAdmin($actionName);
                break;
        }
    }
    public function navigationSubmenuReport()
    {
        $session = new Zend_Session_Namespace ( 'user' );
        $this->returnText .= '<ul id="nav2">';
        if(!$session->parent) {
            $this->returnText .= '  <li><a href="/report/nssrs">NSSRS Report</a></li>';
            $this->returnText .= '  <li><a href="/report/eval-report">Evaluation Report</a></li>';
            $this->returnText .= '  <li><a href="/report/create-nssrs-export">Create NSSRS Export</a></li>';
        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }
    public function navigationSubmenuStudent()
    {
        $session = new Zend_Session_Namespace ( 'user' );
        $this->returnText .= '<ul id="nav2">';
        $this->returnText .= '  <li><a href="https://iepdev.nebraskacloud.org/srs.php?&area=student&sub=list">Student List</a></li>';
        if(!$session->parent) {
          //  $this->returnText .= '  <li '.(($actionName == 'studentadd') ? 'class="current"' : '').'><a href="/student/studentadd"><b><i><font color="green">New Student</a></font></b></i></li>';

            /*
             * SRS-269 taken out 7-27-2018 by Mike
            $this->returnText .= '  <li ><a href="/student/studentadd"><b><i><font color="green">New Student</a></font></b></i></li>';

            $this->returnText .= '  <li><a href="https://iepdev.nebraskacloud.org/srs.php?&area=student&sub=student&option=new">New Student</a></li>';

            $this->returnText .= '  <li ><a href="http://srsz3.nebraskacloud.org/student/addedit"><b><i><font color="green">New Student</a></font></b></i></li>';
*/
            $this->returnText .= '  <li ><a href="/student/studentadd"><b><i><font color="green">New Student</a></font></b></i></li>';
// Mike had to put this back until we can find a way to secure srsz3 7-30-2018 SRS-269

        // SRS-269 The students team link changed
        //   $this->returnText .= '  <li><a href="/staff"><b><i><font color="green">Student\'s  Team</b></i></font></a></li>';
            $this->returnText .= '  <li><a href="http://srsz3.nebraskacloud.org/staff"><b><i><font color="green">Student\'s  Team</b></i></font></a></li>';

            $this->returnText .= '  <li><a href="/student/transfer-center">Transfer Students</a></li>';

         // SRS-269 Student admin link no longer goes to iep
            //$this->returnText .= '  <li><a href="https://iepdev.nebraskacloud.org/srs.php?&area=student&sub=admin">Student Admin</a></li>';
            $this->returnText .= '  <li><a href="http://srsz3.nebraskacloud.org/student/index3">Student Admin</a></li>';


            $this->returnText .= '  <li><a href="https://iepdev.nebraskacloud.org/srs.php?&area=student&sub=helper_pg">Progess Report Helper</a></li>';

         //   $this->returnText .= '  <li ><a href="https://iepweb02m.nebraskacloud.org/ods/advisorset/id_county/87/id_district/0017"><b><i><font color="green">ODS Link</a></font></b></i></li>';

            // Mike added this Jul 5th for password change
          //  $this->returnText .= '  <li '.(($actionName == 'submenuPassword') ? 'class="current"' : '').'><a href="/Passwordchange/subpassword"><font color="green"><b><i>Changes Password</i></b></font></a></li>';
       //     $this->returnText .= '  <li><a href="/Passwordchange/subpassword"><font color="green"><b><i>Change Password</i></b></font></a></li>';

        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }


    public function navigationSubmenuPersonnel()
    {
        // Mike Added this July 26th so that New Privileges would show up only
        // in the correct place.

        $showNewPrivs=false;
        foreach($_SESSION['user']['user']->privs as $privs){
           if ($privs['class'] <='5' && $privs['status']=='Active') $showNewPrivs=true;
        }
        // End of Mike Add
        $session = new Zend_Session_Namespace ( 'user' );

        $privCheck = new My_Classes_privCheck($session->user->privs);
        $admin = 1==$privCheck->getMinPriv()?true:false;
        $this->returnText .= '<ul id="nav2">';
        $this->returnText .= '  <li><a href="https://iepdev.nebraskacloud.org/srs.php?area=personnel&sub=list">Personnel List</a></li>';
        if(!$session->parent) {


             $this->returnText .= '  <li><a class="pwchange2  pwchange tooltip" data-tip-type="html" data-tip-source="tooltip-sidebar2" href="https://iepweb02m.nebraskacloud.org/Passwordchange/subpassword"><font color="green"><b><i>Change Password</font></b></i></a></li>';

             // Mike had to take out the class namess up to tooltip on 9-8-2017 because it was creating 2 links in the html doc
             $this->returnText .= '  <li><a class="tooltip" data-tip-type="html" data-tip-source="tooltip-sidebar2" href="/login/new-account-request"><font color="green"><b>New Privilege Request</b></a></li>';

            // Mike added this 7-5-2017 in order to get the new privileges to work
            // It skips the first one so you will not get two
            $this->returnText .= '  <li><a href="https://iepdev.nebraskacloud.org/new_privilege.php?personnel='.$session->sessIdUser.'">New Privilege Request</a></li>';

// Mike added this July 26 so that only the New Privilege tab would show up
// where appropriate


            if($showNewPrivs==true){
           $this->returnText .= '  <li ><a id="hideme" class="openWindow mike tooltip" data-tip-type="html" data-tip-source="tooltip-sidebar3"   title="var" href="https://iepdev.nebraskacloud.org/new_privilege.php?personnel='.$session->sessIdUser.'"><font color="green"><b><i>New Privileges</font></b></i></a></li>';
            }
// End of Mike add
        }



        if($admin) {
            $this->returnText .= '  <li><a href="https://iepdev.nebraskacloud.org/srs.php?&area=personnel&sub=admin">Personnel Admin</a></li>';
        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }
    public function navigationSubmenuHome()
    {
        $session = new Zend_Session_Namespace ( 'user' );
        $privCheck = new My_Classes_privCheck($session->user->privs);
        $admin = 1==$privCheck->getMinPriv()?true:false;
        $this->returnText .= '<ul id="blueNavSubbar">';
        $this->returnText .= '  <li><a href="/home">Welcome</a></li>';
        if(!$session->parent) {
            $this->returnText .= '  <li><a href="/home/message-center">My Messages</a></li>';
            $this->returnText .= '  <li><a href="https://iepdev.nebraskacloud.org/new_privilege.php?personnel='.$session->sessIdUser.'">Announcements</a></li>';
            $this->returnText .= '  <li><a href="/personnel/edit/id_personnel/'.$session->sessIdUser.'">Edit Profile</a></li>';
//            $this->returnText .= '  <li><a href="https://iepdev.nebraskacloud.org/new_privilege.php?personnel='.$session->sessIdUser.'">Password</a></li>';
            $this->returnText .= '  <li><a href="https://docs.google.com/forms/d/1qwgwOVAAcLCgn9JR8FbBnEOLGLH3c-hWpM5HxNbvgOo/viewform">Suggestions</a></li>';

        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }
}
