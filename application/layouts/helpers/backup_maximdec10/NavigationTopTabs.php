<?php
/**
 * Helper for retrieving base URL
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   Paste
 * @author    Matthew Weier O'Phinney <matthew@weierophinney.net> 
 * @copyright Copyright (C) 2008 - Present, Matthew Weier O'Phinney
 * @license   New  BSD {@link http://framework.zend.com/license/new-bsd}
 * @version   $Id: $
 */
class Zend_View_Helper_NavigationTopTabs extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $returnText;

    public function navigationTopTabs($mode = null)
    {
        $front = Zend_Controller_Front::getInstance();
        $controllerName = $front->getRequest()->getControllerName();
        
        $this->_session = new Zend_Session_Namespace ( 'user' );

        if($this->_session->parent) {
            $admin = false;
        } else {
            $privCheck = new My_Classes_privCheck($this->_session->user->privs);
            $admin = 1==$privCheck->getMinPriv()?true:false;
        }
        if(is_null($mode)) {
            return $this->navigationForms($controllerName, $admin, $this->_session->parent);
        } elseif('simple'==$mode) {
            return $this->navigationSimple($controllerName, $admin, $this->_session->parent);
        }
    }

    public function navigationForms($controllerName, $admin, $parent=false)
    {

    	$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);

		$this->returnText .= '<ul id="nav">';
		$this->returnText .= '  <li><a href="javascript:checkEditedStatus(\'https://iep.unl.edu/srs.php?area=home&sub=home\');">Home</a></li>';
		
		$this->returnText .= '  <li '.(($controllerName != 'student') ? 'class="current"' : '').'><a href="javascript:checkEditedStatus(\'/student/search\');">Students</a></li>';
	
		//$this->returnText .= '  <li><a href="javascript:checkEditedStatus(\'https://iep.unl.edu/srs.php?area=personnel&sub=list\');">Personnel</a></li>';
		$this->returnText .= '  <li><a href="javascript:checkEditedStatus(\'https://iepweb03.esucc.org/personnelm\');>Personnel</a></li>';
		
		
		$this->returnText .= '  <li '.(($controllerName != 'school') ? 'class="current"' : '').'><a href="javascript:checkEditedStatus(\'/school/search\');">Schools</a></li>';
    	        $this->returnText .= '  <li><a href="javascript:checkEditedStatus(\'https://iep.unl.edu/srs.php?area=district&sub=list\');">Districts</a></li>';
		$this->returnText .= '  <li '.(($controllerName != 'admin') ? 'class="current"' : '').'><a href="javascript:checkEditedStatus(\'/admin/server\');">Admin</a></li>';
		$this->returnText .= '  <li><a href="javascript:checkEditedStatus(\'https://iep.unl.edu/srs.php?area=reports&sub=reports\');">Reports</a></li>';
		$this->returnText .= '  <li><a href="javascript:checkEditedStatus(\'https://iep.unl.edu/srs.php?area=help&sub=tutorials\');">Help</a></li>';
		// $this->returnText .= '  <li><a href="javascript:checkEditedStatus(\''.$config->DOC_ROOT .'personnel/stylescope\');">Stylescope</a></li>';
		if ($admin)
		    $this->returnText .= '  <li '.(($controllerName == 'translation') ? 'class="current"' : '').'><a href="/translation">Translation</a></li>';
		$this->returnText .= '</ul>';
        return $this->returnText;
    }

    public function navigationSimple($controllerName, $admin, $parent=false)
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);


        if('home' == $controllerName) {
            $this->returnText .= '<ul id="blueNav">';
        } else {
            $this->returnText .= '<ul id="nav">';
        }


//        $this->returnText .= '  <li><a href="https://iepweb03.esucc.org/home">Home</a></li>';

        $this->returnText .= '  <li '.(($controllerName == 'home') ? 'class="current"' : '').'><a href="/home">Home</a></li>'; // Changed 2 august 2016

        // Michael 5-5-2016 it was this:  $this->returnText .= '  <li><a href="https://iep.unl.edu/srs.php?area=home&sub=home">Home</a></li>';
        $this->returnText .= '  <li '.(($controllerName == 'student') ? 'class="current"' : '').'><a href="/student/search">Students</a></li>';

        if(!$parent) {
            
            //$this->returnText .= '  <li '.(($controllerName == 'personnel') ? 'class="current"' : '').'><a href="https://iep.unl.edu/srs.php?area=personnel&sub=list">Personnel</a></li>';
            $this->returnText .= '  <li '.(($controllerName == 'personnel') ? 'class="current"' : '').'><a href="https://iepweb03.esucc.org/personnelm">Personnel</a></li>';
            
            $this->returnText .= '  <li '.(($controllerName == 'school') ? 'class="current"' : '').'><a href="/school/search">Schools</a></li>';
        
        // Mike Try this
         // $this->returnText .= '  <li><a href="https://iep.unl.edu/srs.php?area=district&sub=list">Districts</a></li>';
        //  $this->returnText .= ' <li> <a href="https://iepweb03.esucc.org/District">Districts</a></li>'; 
             $this->returnText .= ' <li> <a href="https://iepweb03.esucc.org/District">Districts</a></li>';

            $this->returnText .= '  <li '.(($controllerName == 'admin') ? 'class="current"' : '').'><a href="/admin/server">Admin</a></li>';

            $this->returnText .= '  <li '.(($controllerName == 'reports') ? 'class="current"' : '').'><a href="/report">Reports</a></li>';
            $this->returnText .= '  <li><a href="https://iep.unl.edu/srs.php?area=help&sub=tutorials">Help</a></li>';
            // $this->returnText .= '  <li><a href="'.$config->DOC_ROOT .'personnel/stylescope">Stylescope</a></li>';
        }
        //$this->returnText .= '  <li><a href="https://iep.unl.edu/srs.php?area=help&sub=tutorials">Help</a></li>';

        if ($controllerName == 'parent')
            $this->returnText .= '  <li '.(($controllerName == 'parent') ? 'class="current"' : '').'><a href="#">Parent</a></li>';
        if(!$parent) {
            //$this->returnText .= '  <li><a href="'.$config->DOC_ROOT .'personnel/stylescope">Stylescope</a></li>';
        }
        if ($admin) {
            $this->returnText .= '  <li '.(($controllerName == 'translation') ? 'class="current"' : '').'><a href="/translation">Translation</a></li>';
        }
        $this->returnText .= '</ul>';
        return $this->returnText;
    }


}
