<?php
/**
 * 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   Paste
 * @author    Jesse LaVere 
 */
class Zend_View_Helper_IfspGoalHistory extends Zend_View_Helper_Abstract
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
    public function ifspGoalHistory($view, $form, $radioElementName, $printIdsFieldName, $dateFieldName, $textFieldName, $subformIndexName = null, $subformIndex = null)
    {
    	
    	$uniqueIdentifier = $textFieldName.'_'.$subformIndexName.$subformIndex;
		$radioIdentifier = $subformIndexName.'_'.$subformIndex.'-'.$radioElementName;
		$rowIdentifier = $subformIndexName."_".$subformIndex;
		
		//dimHistoryRowgoal_progress('ifsp_history_goal_progress_ifsp_goals11', this.checked)
		$this->_retString = '';
		
    	$this->_retString .= '<script type="text/javascript">';
    	$this->_retString .= 'function adjustIfspHistoryDisplay'.$uniqueIdentifier.'() {';
		$this->_retString .= '	if(dojo.byId("'.$radioIdentifier.'-all").checked) {';
		$this->_retString .= '		dojo.query(".ifsp_history_'.$uniqueIdentifier.'").removeClass("hideme");';

		$this->_retString .= '	} else if(dojo.byId("'.$radioIdentifier.'-sinceannual").checked) {';
		$this->_retString .= '		dojo.query(".ifsp_history_'.$uniqueIdentifier.'").addClass("hideme");';
		$this->_retString .= '		dojo.query(".sinceAnnual_'.$uniqueIdentifier.'").removeClass("hideme");';

		$this->_retString .= '	} else if(dojo.byId("'.$radioIdentifier.'-none").checked) {';
		$this->_retString .= '		dojo.query(".ifsp_history_'.$uniqueIdentifier.'").addClass("hideme");';
		$this->_retString .= '	}';

		$this->_retString .= '}';
		
		$this->_retString .= 'function dimHistoryRow'.$uniqueIdentifier.'(rowid, checked) {';
		$this->_retString .= '	if(checked) {';
		$this->_retString .= '		dojo.query("#"+rowid).removeClass("historyDimmed");';
		$this->_retString .= '	} else {';
		$this->_retString .= '		dojo.query("#"+rowid).addClass("historyDimmed");';
		$this->_retString .= '	}';
		$this->_retString .= '}';
		
		$this->_retString .= 'dojo.addOnLoad(adjustIfspHistoryDisplay'.$uniqueIdentifier.');';
		$this->_retString .= '</script>'; 

		
//		Zend_Debug::dump($view->db_form_data);
        if(!isset($view->db_form_data[$rowIdentifier]) || count($view->db_form_data[$rowIdentifier]) == 0) {
	        $this->_retString .= '		<table cellpadding="2" cellspacing="0" style="margin-bottom:1px;" border="0" width="100%">';
	        $this->_retString .= '			<tr> ';
	        $this->_retString .= '            	<td>No previous IFSP goal data to display.</td>';
	        $this->_retString .= '			</tr>';
	        $this->_retString .= '		</table>';
        } else {
	    	$this->_retString .= '	<table class="formSectionHead" cellspacing="0" cellpadding="0">';
	        $this->_retString .= '		<tr> ';
	        $this->_retString .= '        	<td>Previous IFSPs</td>';
	        if('print' != $view->mode) {
		        $this->_retString .= '            <td>';
		            $form->element->$radioElementName->setAttrib('onclick', 'adjustIfspHistoryDisplay'.$uniqueIdentifier.'();');
		            $this->_retString .= $form->element->$radioElementName;
		        $this->_retString .= '            </td>';
	        }
	        $this->_retString .= '        </tr>';
	        $this->_retString .= '	</table>';
        	
	        $this->_retString .= '		<table cellpadding="2" cellspacing="2" style="margin-bottom:1px;" border="0" width="100%">';
	        $this->_retString .= '	        <tr class="historyHeader"> ';
            if('edit' == $view->mode) {
        		$this->_retString .= '	            	<td valign="top" style="width:50px;">Print?</td>';
            }
	        $this->_retString .= '	            <td valign="top" class="printBoldLabel" style="width:100px;">Date:</td>';
	        $this->_retString .= '	            <td class="printBoldLabel">Previous IFSP Text</td>';
	        $this->_retString .= '	        </tr>';
	        
	        
	        $sinceAnnualClass = 'sinceAnnual_'.$uniqueIdentifier;
	        if(isset($view->db_form_data['ifsp_history'])) {
		        foreach($view->db_form_data['ifsp_history'] as $key => $ifsp) {
		        	
		        	if(!isset($ifsp[$rowIdentifier])) continue;
		        	$rowArr = $ifsp[$rowIdentifier];
	//	        	Zend_Debug::dump($rowArr);
		            $checked = ""; 
		            $printCss = "noprint historyDimmed"; // do not print is default
		            $rowId = "ifsp_history_".$uniqueIdentifier.$rowArr['id_form_013']; // do not print is default
		            if('Annual' == $rowArr['ifsptype']) {
		                $sinceAnnualClass = ''; // sinceAnnual class not attached after first annual reached
		            }
	//				Zend_Debug::dump($view->db_form_data[$rowIdentifier][$printIdsFieldName]);
		            if(substr_count($view->db_form_data[$rowIdentifier][$printIdsFieldName], $rowArr['id_form_013']."\n") > 0) {
		                $checked = " checked";
		                $printCss = ""; // print lines that are checked
		            }
		            $this->_retString .= "<tr id=\"$rowId\" class=\"$printCss $sinceAnnualClass ifsp_history_$uniqueIdentifier\">";
		            if('edit' == $view->mode) {
		                $this->_retString .= "<td><input onClick=\"dimHistoryRow".$uniqueIdentifier."('$rowId', this.checked)\" $checked 
		                						type=\"checkbox\" 
		                						name=\"".$rowIdentifier."[$printIdsFieldName][]\" 
		                						value=\"".$rowArr['id_form_013']."\"></TD>";
		            }
			        $this->_retString .= '			        <td valign="top" align="">';
			        $this->_retString .= $rowArr[$dateFieldName];
			        $this->_retString .= '			        </td>';
			        $this->_retString .= '			        <td valign="top">';
			        $this->_retString .= $rowArr[$textFieldName];
			        $this->_retString .= '			        </td>';
			        $this->_retString .= '			    </tr>';
		        }
	        }
	        
	        $this->_retString .= '		</table>';
        }
    	
        return $this->_retString;
    }
}
