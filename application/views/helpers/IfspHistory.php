<?php
/**
 *
 *
 * @uses      Zend_View_Helper_Abstract
 * @package   Paste
 * @author    Jesse LaVere
 */
class Zend_View_Helper_IfspHistory extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_retString;

    /**
     * Return base URL of application
     *
     * @return string
     */
    public function ifspHistory($view, $form, $radioElementName, $printIdsFieldName, $dateFieldName, $textFieldName)
    {
        $this->_retString = '';

        if ('print' != $view->mode) {
            $this->_retString .= '<script type="text/javascript">';
            $this->_retString .= 'function adjustIfspHistoryDisplay' . $textFieldName . '() {';
            $this->_retString .= '	if(dojo.byId("' . $radioElementName . '-all").checked) {';
            $this->_retString .= '		dojo.query(".ifsp_history' . $textFieldName . '").removeClass("hideme");';
            $this->_retString .= '	} else if(dojo.byId("' . $radioElementName . '-sinceannual").checked) {';
            $this->_retString .= '		dojo.query(".ifsp_history' . $textFieldName . '").addClass("hideme");';
            $this->_retString .= '		dojo.query(".sinceAnnual' . $textFieldName . '").removeClass("hideme");';
            $this->_retString .= '	} else if(dojo.byId("' . $radioElementName . '-none").checked) {';
            $this->_retString .= '		dojo.query(".ifsp_history' . $textFieldName . '").addClass("hideme");';
            $this->_retString .= '	}';

            $this->_retString .= '	';
            $this->_retString .= '}';

            $this->_retString .= 'function dimHistoryRow' . $textFieldName . '(rowid, checked) {';
            $this->_retString .= '	if(checked) {';
            $this->_retString .= '		dojo.query("#"+rowid).removeClass("historyDimmed");';
            $this->_retString .= '	} else {';
            $this->_retString .= '		dojo.query("#"+rowid).addClass("historyDimmed");';
            $this->_retString .= '	}';
            $this->_retString .= '}';

            $this->_retString .= 'dojo.addOnLoad(adjustIfspHistoryDisplay' . $textFieldName . ');';
            $this->_retString .= '</script>';
        }


        $this->_retString .= '	<table class="formSectionHead" cellspacing="0" cellpadding="0">';
        $this->_retString .= '		<tr> ';
        $this->_retString .= '        	<td>Previous IFSPs</td>';
        $this->_retString .= '            <td>';
        if ('print' != $view->mode) {
            $this->_retString .= $form->element->$radioElementName;
        }
        $this->_retString .= '            </td>';
        $this->_retString .= '        </tr>';
        $this->_retString .= '	</table>';
        if (count($view->db_form_data['ifsp_history']) == 0) {
            $this->_retString .= '		<table cellpadding="2" cellspacing="0" style="margin-bottom:1px;" border="0" width="100%">';
            $this->_retString .= '			<tr> ';
            $this->_retString .= '            	<td>No previous IFSPs to display.</td>';
            $this->_retString .= '			</tr>';
            $this->_retString .= '		</table>';
        } else {
            $this->_retString .= '		<table cellpadding="2" cellspacing="2" style="margin-bottom:1px;" border="0" width="100%">';
            $this->_retString .= '	        <tr class="historyHeader"> ';
            if ('edit' == $view->mode) {
                $this->_retString .= '	            	<td valign="top" style="width:50px;">Print?</td>';
            }
            if ('print' == $view->mode) {
                $this->_retString .= '	            <td valign="top" style="width:119px;">Date:</td>';
            } else {
                $this->_retString .= '	            <td valign="top" style="width:100px;">Date:</td>';
            }
            $this->_retString .= '	            <td>Previous IFSP Text</td>';
            $this->_retString .= '	        </tr>';


            // set flags for proper display of sinceannual rows
            $sinceannual = true;
            foreach (array_reverse($view->db_form_data['ifsp_history'], true) as $key => $rowArr) {
                if ('Annual' == $rowArr['ifsptype']) {
                    $sinceannual = false;
                }
                $view->db_form_data['ifsp_history'][$key]['sinceannual'] = $sinceannual;
            }
            // build the rows
            foreach ($view->db_form_data['ifsp_history'] as $key => $rowArr) {
                $checked = "";
                $printCss = "noprint historyDimmed"; // do not print is default
                $rowId = "ifsp_history_" . $textFieldName . $rowArr['id_form_013']; // do not print is default
                if ($rowArr['sinceannual']) {
                    $sinceAnnualClass = 'sinceAnnual' . $textFieldName;
                } else {
                    $sinceAnnualClass = '';
                }
                if (substr_count($view->db_form_data[$printIdsFieldName], $rowArr['id_form_013'] . "\n") > 0
                    && ('all' == $form->element->$radioElementName->getValue()
                        || ('sinceannual' == strtolower($form->element->$radioElementName->getValue()) && $rowArr['sinceannual']))
                ) {
                    $checked = " checked";
                    $printCss = ""; // print lines that are checked and in the display set
                }

                $this->_retString .= "<tr id=\"$rowId\" class=\"$printCss $sinceAnnualClass ifsp_history$textFieldName\">";
                if ('edit' == $view->mode) {
                    $this->_retString .= "<td><input onClick=\"modified();dimHistoryRow" . $textFieldName . "('$rowId', this.checked)\" $checked type=\"checkbox\" name=\"" . $printIdsFieldName . "[]\" value=\"" . $rowArr['id_form_013'] . "\"></td>";
                }
                $this->_retString .= '<td  style="width:112px;" valign="top" align="webCenterPrintLeft">';

                $date = new Zend_Date($rowArr[$dateFieldName], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);

                $this->_retString .= $date->toString(
                    Zend_Date::MONTH . '/' . Zend_Date::DAY . '/' . Zend_Date::YEAR
                );
                $this->_retString .= '</td>';
                $this->_retString .= '<td valign="top">';
                $this->_retString .= $rowArr[$textFieldName];
                $this->_retString .= '</td>';
                $this->_retString .= '</tr>';
            }

            $this->_retString .= '		</table>';
        }

        return $this->_retString;
    }
}
