<?php
/**
 * Helper for displaying a subform 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <mdanahy@esucc.org> 
 * @version   $Id: $
 */
class Zend_View_Helper_Subform extends Zend_View_Helper_Abstract
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
    public function subform($view, $subform_index, $displayHeader = true, $rowContainer = true, $tableContainer = true, $pageBreak = false, $pageBreakRows = false)
    {
    	$this->_retString = "";
		if(isset($view->db_form_data[$subform_index])) {
//			Zend_Debug::dump($view->form->getSubForm($subform_index));
			if($pageBreak) $this->_retString .= '<div style="page-break-before: always;">'; 
			if($displayHeader)
			{
//				$this->_retString .= '<table width="100%" class="sc_subSectionHead">';
//				$this->_retString .= '<tr><td>';
				$this->_retString .= $view->form->getSubForm($subform_index); // header row
//				$this->_retString .= '</td>';
//				$this->_retString .= '</tr>';
//				$this->_retString .= '</table>';
			}
            if('print' == $view->mode)
            {
            	$styleAdd = ' width="100%" cellpadding="0" cellspacing="0" ';
            } else {
            	$styleAdd = "";
            }
            if($tableContainer) $this->_retString .= '<table id="'.$subform_index.'_parent" '.$styleAdd.'>';

            /*
             * Custom setup for Parents Guardian table
             */
            if ($subform_index == 'ifsp_parents')
                $this->_retString = substr($this->_retString, 0, -1) . 
                        ' class="formInputTable">';

			if($view->db_form_data[$subform_index]['count'] > 0) {
				for($x=1; $x <= $view->db_form_data[$subform_index]['count']; $x++)
				{
					$idcode = 'id="'.$subform_index.'_container_'.$x.'"';
					if($pageBreakRows) {
						$idcode .= ' style="page-break-before: always;"';
					}
					if($rowContainer) $this->_retString .= '<tr '.$idcode.'><td>';
					$this->_retString .= $view->form->getSubForm($subform_index.'_'.$x);
					if($rowContainer) $this->_retString .= '</td></tr>';
				}
			} else {
			}
			// if the display header is not on, we still need to include a couple fields
			// count and override
            if(!$displayHeader) {
                 $this->_retString .= $view->form->getSubForm($subform_index)->getElement('count');
                $this->_retString .= $view->form->getSubForm($subform_index)->getElement('override');
            }
            if($tableContainer) $this->_retString .= '</table>';
            if($pageBreak) $this->_retString .= '</div>';
		}
    	return $this->_retString;
    }


}
