<?php
/**
 * Helper for generating the pr helper list of student links 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <jlavere@soliantconsulting.com> 
 * @version   $Id: $
 */
class Zend_View_Helper_PrHelper extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_retString;

    /**
     * wrap data in bold
     * 
     * @return string
     */
    public function prHelper($currentStudent, $studentList)
    {
//    	Zend_Debug::dump(Zend_Controller_Front::getInstance()->getRequest()->getControllerName());
//    	Zend_Debug::dump(Zend_Controller_Front::getInstance()->getRequest()->getActionName());
    	if(count($studentList) <= 0 ) {
    		return "<table style=\"overflow-y:auto;\"><tr><td><p>". str_repeat("<BR>", 30)."</P></td></tr></table>";
    	}
    	$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
    	$DOC_ROOT = $config->DOC_ROOT;
    	 
    	$mode = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
    	$area = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
		$usersession = new Zend_Session_Namespace('user');
    	
		$this->_retString = '';
    	foreach ($studentList as $studentData) {

    		$noProgReports = false;
		    $modelform   = new Model_Table_Form010();
		    $pgData = $modelform->mostRecentDraftForm($studentData['id_student']);
		    $pgFinalData = $modelform->mostRecentFinalForm($studentData['id_student']);
		    
		    if(strlen($studentData['name_full'])>20) {
		    	$studentData['name_full'] = substr($studentData['name_full'], 0, 16) . '...';
		    }
//		    Zend_Debug::dump($pgData);
//		    Zend_Debug::dump($pgFinalData);
		    if(isset($pgData['status'])) {
				// confirm access through acl
				// this confirms user access to this area
				$this->acl = new App_Acl();
				if(!$this->acl->isAllowed($usersession->user->role, App_Resources::USER_SECTION)) { 
						$this->_redirect('/home');
				}
				        
		    } elseif(isset($pgFinalData['status'])) {
				// confirm access through acl
				// this confirms user access to this area
				$this->acl = new App_Acl();
				if(!$this->acl->isAllowed($usersession->user->role, App_Resources::GUARDIAN_SECTION)) { 
						$this->_redirect('/home');
				}
		    
		    } else {
		    	// no progress reports
		        //if(1010818 == $sessIdUser) echo "no progress reports: {$studentData['name_full']}<BR>";
		        $noProgReports = true;
		    }

            
            if($currentStudent == $studentData['id_student']) {
                $currentFormID = $pgData['id_form_010'];

                    $this->_retString .= "<p style=\"color:black\"><B>" . $studentData['name_full'] . "</B></p>";
    
            } else {
                
                // get nssrs complete status for this student 
                    if($noProgReports) {
                        //
                        // display of student with no prog reports
                        //
                        $studentLinkColor = "black";
                        $this->_retString .= "<p>" . $studentData['name_full'] . "</p>";

                    } elseif('edit' == $mode) {
                        //
                        // edit links
                        //
//                        if('student' == $area && 'helper_pg' == $sub && isset($pgData['id_form_010'])) { // link to the pg
//                            //
//                            // NOT SUPPORTED YET IN ZF SITE
//                            // display for the helper_pg page
//                            // student has a draft progress report
//                            //
//                            $studentLinkColor = "green";
//                            $this->_retString .= "<a href=\"javascript:goToURL('$DOC_ROOT', 'student', 'form_010', 'document', '".$pgData['id_form_010']."', '&page=&option=edit');\" style=\"color:$studentLinkColor\">" . $studentData['name_full'] . "</a>";                        
//                            
//                        } elseif('student' == $area && 'helper_pg' == $sub && isset($pgFinalData['id_form_010'])) { // link to the student form center
//                            //
//							  // NOT SUPPORTED YET IN ZF SITE
//                            // display for the helper_pg page
//                            // student has a finalized progress report
//                            //
//                            $studentLinkColor = "red";
//                            $this->_retString .= "<a href=\"javascript:goToURL('$DOC_ROOT', 'student', 'student', 'student', '".$studentData['id_student']."', '&option=forms');\" style=\"color:$studentLinkColor\"><i>" . $studentData['name_full'] . "</i></a>";
//                                                                    
//                        } else
                        if(isset($pgData['id_form_010'])) {
                            //
                            // progress report display - save and redirect
                            // student has a draft progress report
                            //
                            $studentLinkColor = "green";
                            $this->_retString .= "<p><a href=\"javascript:prHelperRedirect('".$pgData['id_form_010']."', '".$pgData['id_student']."');\" style=\"color:$studentLinkColor\">" . $studentData['name_full'] . "</a></p>";
                            
                        } else {
                            //
                            // progress report display - save and redirect
                            // student has a finalized progress report
                            //
                            $studentLinkColor = "red";
                            $this->_retString .= "<p><a href=\"javascript:document.forms[0].goto_id_form_010.value='".$pgFinalData['id_form_010']."';document.forms[0].goto_id_student.value='".$pgFinalData['id_student']."';  recordAction(document.forms[0], 'save');\" style=\"color:$studentLinkColor\">" . $studentData['name_full'] . "</a></p>";
                        }
                        
                    } elseif('view' == $mode) { // || '' == $view
                        if('student' == $area && 'helper_pg' == $sub && isset($pgData['id_form_010'])) { // link to the pg
                            $studentLinkColor = "green";
                            $this->_retString .= "<p><a href=\"javascript:goToURL('$DOC_ROOT', 'student', 'form_010', 'document', '".$pgData['id_form_010']."', '&page=&option=edit');\" style=\"color:$studentLinkColor\">" . $studentData['name_full'] . "</a></p>";                        
//                            $this->_retString .= "<p><a href=\"javascript:prHelperRedirect('".$pgData['id_form_010']."', '".$pgData['id_student']."');\" style=\"color:$studentLinkColor\">" . $studentData['name_full'] . "cc</a></p>";
                            
                        } elseif('student' == $area && 'helper_pg' == $sub && isset($pgFinalData['id_form_010'])) { // link to the student form center
                            $studentLinkColor = "red";
                            $this->_retString .= "<p><a href=\"javascript:goToURL('$DOC_ROOT', 'student', 'form_010', 'document', '".$pgFinalData['id_form_010']."', '&page=&option=view');\" style=\"color:$studentLinkColor\">" . $studentData['name_full'] . "</a></p>";                        
                            
                        } elseif(isset($pgData['id_form_010'])) {
                            $studentLinkColor = "green";
//                            $this->_retString .= "<p><a href=\"javascript:goToURL('$DOC_ROOT', 'student', 'form_010', 'document', '".$pgData['id_form_010']."', '&page=&option=edit');\" style=\"color:$studentLinkColor\">" . $studentData['name_full'] . "</a></p>";                        
                            $this->_retString .= "<p><a href=\"javascript:prHelperRedirect('".$pgData['id_form_010']."', '".$pgData['id_student']."');\" style=\"color:$studentLinkColor\">" . $studentData['name_full'] . "</a></p>";
                            
                        } else {
                            $studentLinkColor = "red";
                            $this->_retString .= "<p><a href=\"javascript:goToURL('$DOC_ROOT', 'student', 'student', 'student', '".$studentData['id_student']."', '&option=forms');\" style=\"color:$studentLinkColor\"><i>" . $studentData['name_full'] . "</i></a></p>";
                        }
                    }
                    
                    $this->_retString .= "</TD>";
                $this->_retString .= "</TR>";
        
            }
		    
    	}
		$this->_retString .= "</table>";	
    	return $this->_retString;
    }


}
