<?php
class Zend_View_Helper_NavigationSubmenu extends Zend_View_Helper_Abstract
{
    public $returnText; 
   
    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    public function navigationSubmenu($type = 'student')
    {

        $front = Zend_Controller_Front::getInstance();
        $actionName = $front->getRequest()->getActionName();
        $type = strtolower($type);
        switch($type) {
            case 'home':
            default:            
                return $this->navigationSubmenuHome($actionName);
                break;
            case 'student':
            case 'studentlog':
            case 'staff':
                return $this->navigationSubmenuStudent($actionName, $type);
                break;
            case 'school':
                return $this->navigationSubmenuSchool($actionName);
                break;
            case 'district':
                return $this->navigationSubmenuDistrict($actionName);
                break;
            case 'admin':
                return $this->navigationSubmenuAdmin($actionName);
                break;
            case 'personnel':
                return $this->navigationSubmenuPersonnel($actionName);
                break;
            case 'parent':
                return $this->navigationSubmenuParent($actionName);
                break;
            case 'report':
                return $this->navigationSubmenuReport($actionName);
                break;
        }
    }
    public function navigationSubmenuReport($actionName)
    {
        $session = new Zend_Session_Namespace ( 'user' );
        $this->returnText .= '<ul id="nav2">';
        if(!$session->parent) {
            $this->returnText .= '  <li '.(($actionName == 'nssrs') ? 'class="current"' : '').'><a href="/report/nssrs">NSSRS Report</a></li>';
            $this->returnText .= '  <li '.(($actionName == 'eval-report') ? 'class="current"' : '').'><a href="/report/eval-report">Evaluation Report</a></li>';
            $this->returnText .= '  <li '.(($actionName == 'create-nssrs-export') ? 'class="current"' : '').'><a href="/report/create-nssrs-export">Create NSSRS Export</a></li>';
        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }

    public function navigationSubmenuStudent($actionName, $submenu = '' )
    {
        $session = new Zend_Session_Namespace ( 'user' );
        $this->returnText .= '<ul id="nav2">';
        $this->returnText .= '  <li '.(($actionName == 'search') ? 'class="current"' : '').'><a href="/student/search/">Student List</a></li>';
        if(!$session->parent) {
            $this->returnText .= '  <li '.(($actionName == 'studentadd') ? 'class="current"' : '').'><a href="/student/studentadd">New Student</a></li>';
            $this->returnText .= '  <li '.(($actionName == 'transfer-center') ? 'class="current"' : '').'><a href="/student/transfer-center">Transfer Students</a></li>';
            $this->returnText .= '  <li '.(($actionName == 'studentadmin') ? 'class="current"' : '').'><a href="/student/studentadmin/">Student Admin</a></li>';
            $this->returnText .= '  <li><a href="/srs.php?&area=student&sub=helper_pg">Progess Report Helper</a></li>';
            $this->returnText .= '  <li><a href="/staff">Student\'s  Team</a></li>';
            $this->returnText .= '  <li><a href="/staff/index2">Team\'s Students</a></li>';
	        if ($submenu == 'studentlog') $this->returnText .= '  <li '.(($actionName == 'studentlog') ? 'class="current"' : '').'><a href="#">Student Log</a></li>';
        }

        $this->returnText .= '</ul>';
        return $this->returnText;
    }

    public function navigationSubmenuSchool($actionName)
    {
        $session = new Zend_Session_Namespace ( 'user' );
        $this->returnText .= '<ul id="nav2">';
        $this->returnText .= '  <li '.(($actionName == 'search') ? 'class="current"' : '').'><a href="/school/search">School List</a></li>';
        if(!$session->parent) {
//            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/srs.php?&area=student&sub=student&option=new">New School</a></li>';
//            $this->returnText .= '  <li><a href="/student/transfer-center">Transfer Students</a></li>';
//            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/srs.php?&area=student&sub=admin">Student Admin</a></li>';
//            $this->returnText .= '  <li><a href="https://iepd.nebraskacloud.org/srs.php?&area=student&sub=helper_pg">Progess Report Helper</a></li>';
        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }

    public function navigationSubmenuDistrict($actionName)
    {
        $session = new Zend_Session_Namespace ( 'user' );
        $this->returnText .= '<ul id="nav2">';
        $this->returnText .= '  <li '.(($actionName == '') ? 'class="current"' : '').'><a href="/district/">District List</a></li>';
        $this->returnText .= '</ul>';
        return $this->returnText;
    }


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



    public function navigationSubmenuParent($actionName)
    {
        
        $session = new Zend_Session_Namespace ( 'user' );
        $this->returnText .= '<ul id="nav2">';
        $this->returnText .= '  <li><a href="#">Parents List</a></li>';
        $this->returnText .= '</ul>';
        return $this->returnText;
    }


    public function navigationSubmenuPersonnel($actionName)
    { 
        //echo $this->staffmember['name_first'];
      //  $this->writevar1($_SESSION['user']['user']->user['id_personnel'],'this is the id personnel');
        $session = new Zend_Session_Namespace ( 'user' );
        $privCheck = new My_Classes_privCheck($session->user->privs);
        $admin = 1==$privCheck->getMinPriv()?true:false; 
        $this->returnText .= '<ul id="nav2">';
        if(!$session->parent) {
            $this->returnText .= '  <li class="pwchange" style="display: none"><a class="pwchange2" href="#">Change Password</a></li>';
            $this->returnText .= '  <li class="current"><a class="openWindow mike tooltip" data-tip-type="html" data-tip-source="tooltip-sidebar3" title="var" href="/new_privilege.php?personnel='.$session->sessIdUser.'">New Privileges</a></li>';
            $this->returnText .= '  <li><a class="mike2" href="/srs.php?area=personnel&sub=list">Personnel List</a></li>';
        }
        if($admin) {
            $this->returnText .= '   <li><a href="/admin/server">Personnel Admin</a></li>';       
        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }


    public function navigationSubmenuHome($actionName)
    {
        
        $session = new Zend_Session_Namespace ( 'user' );
        $privCheck = new My_Classes_privCheck($session->user->privs);
        $admin = 1==$privCheck->getMinPriv()?true:false;
        $this->returnText .= '<ul id="nav2">';
        $this->returnText .= '  <li><a href="/home">Welcome</a></li>';
        if(!$session->parent) {
            $this->returnText .= '  <li '.(($actionName == 'message-center') ? 'class="current"' : '').'><a href="/home/message-center">My Messages</a></li>';
            $this->returnText .= '  <li><a href="/new_privilege.php?personnel='.$session->sessIdUser.'">Announcements</a></li>';
            $this->returnText .= '  <li '.(($actionName == 'edit') ? 'class="current"' : '').'><a href="/personnelm/edit/id_personnel/'.$session->sessIdUser.'">Edit Profile</a></li>';
            $this->returnText .= '  <li><a href="https://docs.google.com/forms/d/1qwgwOVAAcLCgn9JR8FbBnEOLGLH3c-hWpM5HxNbvgOo/viewform">Suggestions</a></li>';

            $this->returnText .= '  <li><a class="pwchange2" rel="/passwordchange/changepassword/id_personnel/' . $session->user->user["id_personnel"] . '/name_first/'.$session->user->user["name_first"].'/name_last/'.$session->user->user["name_last"].'" href="#" title="'.$session->user->user["name_first"].' '.$session->user->user["name_last"].'">Change Password</a></li>';

//            $this->returnText .= '  <li '.(($actionName == 'submenuPassword') ? 'class="current"' : '').'><a href="/Passwordchange/subpassword">Changes Password</a></li>'; // Remove 05 october 2017

            $this->returnText .= '  <li '.(($actionName == 'new-account-request') ? 'class="current"' : '').'><a href="/login/new-account-request">New permission request</a></li>';
            
        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }
}
