<?php
class Zend_View_Helper_NavigationSubmenu extends Zend_View_Helper_Abstract
{
    public $returnText;
    public function navigationSubmenu($type = 'student')
    {

        switch(strtolower($type)) {
            default:
            case 'student':
                return $this->navigationSubmenuStudent();
                break;
            case 'school':
                return $this->navigationSubmenuSchool();
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
        $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/srs.php?&area=student&sub=list">Student List</a></li>';
        if(!$session->parent) {
            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/srs.php?&area=student&sub=student&option=new">New Student</a></li>';
            $this->returnText .= '  <li><a href="/student/transfer-center">Transfer Students</a></li>';
            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/srs.php?&area=student&sub=admin">Student Admin</a></li>';
            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/srs.php?&area=student&sub=helper_pg">Progess Report Helper</a></li>';
            $this->returnText .= '  <li><a href="https://iepweb03.esucc.org/staff/team">Student\'s  Team</a></li>';
            $this->returnText .= '  <li><a href="https://iepweb03.esucc.org/staff/team2">Team\'s Students</a></li>';
        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }

    public function navigationSubmenuSchool()
    {
        $session = new Zend_Session_Namespace ( 'user' );
        $this->returnText .= '<ul id="nav2">';
        $this->returnText .= '  <li><a href="/school/search">School List</a></li>';
        if(!$session->parent) {
//            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/srs.php?&area=student&sub=student&option=new">New School</a></li>';
//            $this->returnText .= '  <li><a href="/student/transfer-center">Transfer Students</a></li>';
//            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/srs.php?&area=student&sub=admin">Student Admin</a></li>';
//            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/srs.php?&area=student&sub=helper_pg">Progess Report Helper</a></li>';
        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }

    public function navigationSubmenuPersonnel()
    {
        $session = new Zend_Session_Namespace ( 'user' );
        $privCheck = new My_Classes_privCheck($session->user->privs);
        $admin = 1==$privCheck->getMinPriv()?true:false;
        $this->returnText .= '<ul id="nav2">';
        $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/srs.php?area=personnel&sub=list">Personnel List</a></li>';
        if(!$session->parent) {
            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/new_privilege.php?personnel='.$session->sessIdUser.'">New Privilege</a></li>';
        }
        if($admin) {
            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/srs.php?&area=personnel&sub=admin">Personnel Admin</a></li>';
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
            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/new_privilege.php?personnel='.$session->sessIdUser.'">Announcements</a></li>';
            $this->returnText .= '  <li><a href="/personnel/edit/id_personnel/'.$session->sessIdUser.'">Edit Profile</a></li>';
//            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/new_privilege.php?personnel='.$session->sessIdUser.'">Password</a></li>';
            $this->returnText .= '  <li><a href="https://docs.google.com/forms/d/1qwgwOVAAcLCgn9JR8FbBnEOLGLH3c-hWpM5HxNbvgOo/viewform">Suggestions</a></li>';
        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }
}
