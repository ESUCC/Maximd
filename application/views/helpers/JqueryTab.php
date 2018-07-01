<?php
/**
 * Helper for displaying a subform 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <jlavere@soliantconsulting.com> 
 * @version   $Id: $
 */
class Zend_View_Helper_JqueryTab extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_retString;

    protected function buildHeaderRow($view, $subform_index) {
        $headerTxt = '<tr><td>';
        $headerTxt .= $view->form->getSubForm($subform_index); // header row
        $headerTxt .= '</td>';
        $headerTxt .= '</tr>';
        return $headerTxt;
    }
    protected function buildRowsPrint($view, $data, $subform_index, $title, $pageBreak) {
        $tabContents = ''; // reset
        for ($x = 1; $x <= $data[$subform_index]['count']; $x ++) {
            if ($x > 1 && $pageBreak) {
                $tabContents .= '<div style="page-break-before: always;">';
                $tabContents .= $view->form->getSubForm($subform_index . '_' . $x) . "<BR />";
                $tabContents .= '</div>';
            } else {
                $tabContents .= $view->form->getSubForm($subform_index . '_' . $x) . "<BR />";
            }
        }
        return $tabContents;;
    }
    protected function buildRows($view, $data, $subform_index, $title, $pageBreak) {
        $tabContents = ''; // reset
        $tabHeader = '<ul>'; // reset
        for ($x = 1; $x <= $data[$subform_index]['count']; $x ++) {
            $tabHeader .= '<li class="tabHeader"><a href="#' . $subform_index . '_' . $x . '"><span>' . $title . ' ' . $x . '</span></a></li>';
            $tabContents .= '<div id="' . $subform_index . '_' . $x .'" class="tab tab-hide" >';
            $tabContents .= $view->form->getSubForm($subform_index . '_' . $x);
            $tabContents .= '</div>';
        }
        $tabHeader .= '</ul>';
        return $tabHeader.$tabContents;;
    }
    /**
     * build bar of links to form options
     * 
     * @return string
     */
    public function jqueryTab($view, $subform_index, $displayHeader = true, $title = "Tab", $pageBreak = false)
    {
        if('tinyMce'!=$view->db_form_data['form_editor_type']) {
            return $view->subformTab($view, $subform_index, $displayHeader, $title, $pageBreak);
        } else {
            $this->_retString = "";
        	if(isset($view->db_form_data[$subform_index])) {
                
                $this->_retString .= '<table id="'.$subform_index.'_parent" style="width:100%;">';
    			if($displayHeader)
    			{
    				$this->_retString .= $this->buildHeaderRow($view, $subform_index);
    			}
    			
			    if('print' != $view->mode)
				{
					// not print mode
				    $this->_retString .= '<tr><td>';
					$this->_retString .= '<div id="'.$subform_index.'_tab_container_parent" >';
					$this->_retString .= '<div id="'.$subform_index.'_tab_container" class="jTabContainer ui-tabs" >';
				    $this->_retString .= $this->buildRows($view, $view->db_form_data, $subform_index, $title, $pageBreak);
					$this->_retString .= '</div>';
					$this->_retString .= '</div><!-- end tab container parent -->';
					$this->_retString .= '</td></tr>';
				} else {
				    $this->_retString .= '<tr><td>';
				    $this->_retString .= $this->buildRowsPrint($view, $view->db_form_data, $subform_index, $title, $pageBreak);
				    $this->_retString .= '</td></tr>';
				}
				$this->_retString .= '</table>';
                if($pageBreak) {
                    $this->_retString = '<div style="page-break-before: always;">' .$this->_retString.'</div>';
                }
        	}
        }
        return $this->_retString;
    }


}
