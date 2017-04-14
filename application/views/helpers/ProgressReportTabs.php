<?php
/**
 * Helper for displaying a subform 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <mdanahy@esucc.org> 
 * @version   $Id: $
 */
class Zend_View_Helper_ProgressReportTabs extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_retString;

    /**
     * build bar of links to form options
     * 
     * @return string
     */
    public function progressReportTabs($view, $subform_index) {
    	
		$this->_retString = '<div id="ddtabs1">';
		$this->_retString .= '<ul class="basictab">';
	    
		$autoFillArr = false;
		$progReportCount = 1;
        if(isset($view->db_form_data[$subform_index])) {
            $progReportCount = $view->db_form_data[$subform_index]['count'];
            $currentPgRpt = $view->db_form_data['id_form_010'];
        }
        $tabNum = 1;
        $prclick = 'edit';
        
        if(!isset($view->db_form_data[$subform_index . '_' . $tabNum])) {
        	// no progress report data
        	return null;
        }
        
        for($tabNum = 1; $tabNum <= $progReportCount; $tabNum++) {
            $pg = $view->db_form_data[$subform_index . '_' . $tabNum];
            
            if($currentPgRpt == $pg['id_form_010'] && 'Final' == $pg['status']) {   
              // document being viewed and is FINAL
                $this->_retString .=  "<li class=\"selected\" style=\"white-space:nowrap;\"><a href=\"#\"><B>#$tabNum</B> (".$this->date_or_message(($pg['date_notice'])).") ".$pg['status']."</a></li> ";
            
            } elseif($currentPgRpt == $pg['id_form_010'] && 'Final' != $pg['status']) {   
              // document being viewed and NOT FINAL
                $this->_retString .= "<li class=\"selected\" style=\"white-space:nowrap;\">";
                $this->_retString .= "<a href=\"#\"><B>#$tabNum</B> (".$this->date_or_message(($pg['date_notice'])).") ".$pg['status']."</a>";
//                $this->_retString .= $this->url(array(
//				    'controller' => 'form010',
//				    'action' => 'create',
//				    'parent_key' => 'id_form_004',
//				    'parent_id' => $view->db_form_data['id_form_004'],
//				    'student' => $view->db_form_data['student_data']['id_student'],
//				));
                $this->_retString .= "</li> ";
            
            } elseif('Final' == $pg['status']) {   
              // document not being viewed and is FINAL
                $this->_retString .=  "<li class=\"final\" style=\"white-space:nowrap;\"><a href=\"";
                    $this->_retString .= $view->url(array(
                      'controller' => 'form010',
                      'action' => 'view',
                      'document' => $pg['id_form_010'],
                    ));
                $this->_retString .= "\">";
                $this->_retString .= "<B>#$tabNum</B> (".$this->date_or_message(($pg['date_notice'])).") ".$pg['status']."</a>";
                $this->_retString .= "</li> ";
            
            } elseif('Final' != $pg['status']) {   
              	// document not being viewed and not final
            	$this->_retString .= "<li class=\"draft\" style=\"white-space:nowrap;\"><a href=\"";
            	$this->_retString .= $view->url(array(
            			'controller' => 'form010',
            			'action' => $prclick,
            			'document' => $pg['id_form_010'],
            	));
            	$this->_retString .= "\"><B>#$tabNum</B> (".$this->date_or_message(($pg['date_notice'])).") ".$pg['status']."</a>";
            	$this->_retString .= "</li> ";
            }
        }
        
        $this->_retString .= '</ul>';
        $this->_retString .= '</div>';
        return $this->_retString;
    }

	// function added to help with display for progres report (form 10) tabs
	function date_or_message($date, $msg="No Date Notice")
	{
	    if('' == $date) return $msg;
	    $date = new Zend_Date($date, Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
	    return $date->toString(Zend_Date::MONTH . '/' . Zend_Date::DAY . '/' . Zend_Date::YEAR);
	}
    

}
