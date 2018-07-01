<?php

class My_View_Helper_StudentSearch extends Zend_View_Helper_Abstract
{
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    //
	// function should be priv aware
	//
	public function studentSearch($maxRows, $searchFieldsArr)
	{
//		$view = $this->element->page->getView();
		
        $session = new Zend_Session_Namespace('student');
        $searchField = $session->searchfield;
        $searchValue = $session->searchvalue;
        
//        Zend_debug::dump($searchField);
//		Zend_debug::dump($searchValue);
		
		$showRowsSEARCH = 2;
		if(!isset($sort)) $sort = "";
		if(!isset($search_status)) $search_status = "";
		
		//
		// header table
		//
		$retText = "<div>";
		$retText .= "<table style=\"width:475px;\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
		$retText .= "	<tr class=\"bgDark\" style=\"height:19px;\">";
		$retText .= "		<td colspan=\"3\" style=\"padding-left:8;\"><span class=\"btsbWhite\">Search Students</span></td>";
		$retText .= "	</tr>";
		$retText .= "</table>";

		//
		// search table
		//
		$retText .= "<table style=\"width:475px; margin-top:1px;\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" id=\"SEARCHTABLECONTAINER\"><TR><TD>";
		$retText .= "    <table style=\"width:475px; margin-top:1px;\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" id=\"SEARCHTABLE\">";
		//
		// labels row
		//
		$retText .= "        <tr class=\"bgLight2\" style=\"height:21px;\">";
		$retText .= "            <td class=\"bts\" align=\"left\" style=\"padding-left:8; width:40px;\">Row</td>";
		$retText .= "            <td class=\"bts\" align=\"left\" style=\"padding-left:8;\">Search This Field:</td>";
		$retText .= "            <td class=\"bts\" align=\"left\" style=\"padding-left:8;\">For This Value:</td>";
		$retText .= "        </tr>";

		//
		// variable search rows
		//
		for ($i=1; $i <= $maxRows; $i++) {
			
			$showRowsSEARCH >= $i? $v = $i - 1 : $v = "";
			if(!isset($searchValue[$v])) $searchValue[$v] = "";
			if(!isset($searchField[$v])) $searchField[$v] = "";

			$retText .= "<tr class=\"bgLight\" id=\"SEARCH$i\" style=\"height:30px;\">";//display:inline; 
			$retText .= "<td class=\"bts\" align=\"left\" style=\"padding-left:8; width:40px;\">$i.</td>";
			$retText .= "<td class=\"bts\" align=\"left\" style=\"padding-left:8;\">";
				
			$retText .= "<select name=\"searchFieldArr[]\" id=\"searchFieldArr[]\">";
			foreach($searchFieldsArr as $value => $label)
			{
				$selected = ($value == $searchField[($i-1)])?' selected ':'';
				$retText .= "<option value=\"$value\" label=\"$label\" $selected>$label</option>";
			}
			$retText .= "</select>";
			
			$retText .= "</td>";
			$retText .= "<td class=\"bgLight\" align=\"left\" style=\"padding-left:8;\">";
			$retText .= "<SPAN id=\"replaceme$i\">";
			$retText .= "<input type=\"text\" id=\"searchValue$i\" name=\"searchValueArr[]\" value=\"".$searchValue[$v]."\" class=\"bts\" style=\"width:160px;\">";
			$retText .= "</SPAN></td>";
			$retText .= "</tr>";
		}

		$retText .= "</table>";

		$retText .= "</td></tr>";
		$retText .= "</table>";

		
		$retText .= "<table style=\"width:475px; margin-top:5px;\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
		$retText .= "		<tr class=\"bgLGrey\">";
		$retText .= "			<td class=\"bts\"  style=\"height:25px; padding-left:8;\" nowrap=\"nowrap\"><span class=\"btsb\">Type</span>:</td>";
		$retText .= "			<td class=\"bts\" style=\"padding-left:4;\">";
		$retText .= "				<select name=\"searchType\" size=\"1\">";
		$retText .= "					<option value=\"AND\" ";
		if( $session->sessCurrentStudentSearchType == "AND" ) { $retText .=  "selected"; }
		$retText .= "					>AND";
		$retText .= "					<option value=\"OR\" ";
		if( $session->sessCurrentStudent == "OR" ) { $retText .=  "selected"; }
		$retText .= "					>OR";
		$retText .= "				</select>";
		$retText .= "			</td>";
		$retText .= "			<td style=\"width:500px;\">&nbsp;</td>";
		$retText .= "			<td class=\"bts\" style=\"padding-left:8;\" nowrap=\"nowrap\"><span class=\"btsb\">Sort</span>:</td>";
		$retText .= "			<td class=\"bts\" style=\"padding-left:4;\">";

//		if($area == "reports" && $sub == "evaluation_date_report") {
//			$retText .= "                <input name=\"sort\" id=\"sort\" type=\"hidden\" value=\"days_since_eval\">";
//			$retText .= "                <div id=\"studentListSortDiv\">days since the Notice of Eval</div>";
//		} else {
			$retText .= "                <div id=\"studentListSortDiv\">";
			$arrLabel = array("Name", "School");
			$arrValue = array("Name", "School");

			// 200620 jlavere - javascript added to change sort menu when format changed.
//			echo valueListCustom("sort", $sort, $arrLabel, $arrValue, "School", "none", "id=\"sort\" ");
			$retText .= $this->view->formMultiSelectArray('sort', array_combine($arrLabel, $arrValue), $sort);
			$retText .= "                </div>";
//		}
		$retText .= "			</td>";
		$retText .= "        </tr>";

		$retText .= "		<tr class=\"bgLGrey\">";
		$retText .= "			<td class=\"bts\"  style=\"height:25px; padding-left:8;\" nowrap=\"nowrap\"><span class=\"btsb\">&nbsp;</span></td>";
		$retText .= "			<td class=\"bts\" style=\"padding-left:4;\">&nbsp;</td>";
		$retText .= "			<td style=\"width:500px;\">&nbsp;</td>";
		$retText .= "			<td class=\"bts\" style=\"padding-left:8;\" nowrap=\"nowrap\"><span class=\"btsb\">Status</span>:</td>";
		$retText .= "			<td class=\"bts\" style=\"padding-left:4;\">";
		$arrLabel = array("All", "Active","Inactive","Never Qualified","No Longer Qualifies", "Transferred to Non-SRS District");
		$arrValue = array("All", "Active","Inactive","Never Qualified","No Longer Qualifies", "Transferred to Non-SRS District");
		//echo valueListCustom("search_status", $search_status, $arrLabel, $arrValue, "Active", "", "");
			$retText .= $this->view->formMultiSelectArray('search_status', array_combine($arrLabel, $arrValue), $search_status);
		$retText .= "            </td>";
		$retText .= "        </tr>";

		$retText .= "    <tr class=\"bgLGrey\">";
		$retText .= "        <td class=\"bts\" style=\"height:25px; padding-left:8; padding-top:6px;\" nowrap=\"nowrap\"><span class=\"btsb\">Format</span>:</td>";
		$retText .= "            <td class=\"bts\" style=\"padding-left:4;\">";
		$arrLabel = array("Phonebook", "School List", "MDT/IEP Report");
		$arrValue = array("Phonebook", "School List", "MDT/IEP Report");
		//echo valueListCustom("format", "$format", $arrLabel, $arrValue, "School List", "none", "id=\"studentListFormat\" onChange=\"StudentListChangeSortMenu(this.id);\" " );
		$retText .= $this->view->formMultiSelectArray('format', array_combine($arrLabel, $arrValue), $session->sessCurrentStudentFormat);
		$retText .= "		</td>";
		$retText .= "            <td style=\"width:500px;\">&nbsp;</td>";
		$retText .= "		<td class=\"bts\" style=\"padding-left:8;\" nowrap=\"nowrap\"><span class=\"btsb\">Records Per Page</span>:</td>";
		$retText .= "		<td class=\"bts\" style=\"padding-left:4;\">";
		#
		# OVERRIDE MODE FOR THIS VALUE LIST TO MAKE SURE IT'S ALWAYS AVAILABLE
		#
//		$saveMode = $mode;
//		$mode = 'edit';
		//echo valueListArray("recordsPerPage", "maxRecs", $maxRecs, "15", "none");
		$retText .=$this->view->formMultiSelectOneToNum('recordsPerPage', '15', $session->sessCurrentStudentMaxRecs);
//		$mode = $saveMode;
		$retText .= "	    </td>";
		$retText .= "	</tr>";
		$retText .= "</table>";
		$retText .= "<table style=\"width:475px; margin-top:8px;\" align=\"center\">";
		$retText .= "	<tr>";
		$retText .= "		<td>";
		$retText .= "		<span id=\"moreRows\">";
		$retText .= "<a href=\"javascript:addRow('SEARCH', ".($session->sessCurrentStudentMaxRecs + 1).", document.forms['form0'], 'SEARCHTABLE', 'student');\" ". My_Classes_iepFunctionGeneral::windowStatus('More Search Rows') . ">";
		$retText .= "		<img src=\"images/button_more_search_rows.gif\" alt=\"More Search Rows\" title=\"Click here to add search rows\">";
		$retText .= "		</a>";
		$retText .= "		</span>";
		$retText .= "		</td>";
		$retText .= "        <td style=\"padding-left:4px;\">";
		$retText .= "        <span id=\"lessRows\">";
		$retText .= "<a href=\"javascript:subRow('SEARCH',".$session->sessCurrentStudentMaxRecs.", ".'2'.", document.forms['form0'], 'SEARCHTABLE', 'student');\" ". My_Classes_iepFunctionGeneral::windowStatus('Fewer Search Rows') . ">";
		$retText .= "        <img src=\"images/button_less_search_rows.gif\" alt=\"Fewer Search Rows\" title=\"Click here to subtract search rows\">";
		$retText .= "        </a>";
		$retText .= "        </span>";
		$retText .= "        </td>";
		$retText .= "		<td align=\"right\" style=\"width:100%;\">";
		$retText .= "<a href=\"javascript:document.forms['form0'].showAll.value=1; document.forms['form0'].pos.value=0; document.forms['form0'].submit();\" ". My_Classes_iepFunctionGeneral::windowStatus('Show All') . ">";
		$retText .= "		<img src=\"images/button_show_all.gif\" alt=\"Show All\" title=\"Click here to show all records\">";
		$retText .= "		</a>";
		$retText .= "		</td>";
		$retText .= "		<td align=\"right\" style=\"padding-left:4px;\">";
		$retText .= "<a href=\"javascript:document.forms['form0'].pos.value=0; document.forms['form0'].submit()\"".My_Classes_iepFunctionGeneral::windowStatus('Search') . "><img src=\"images/button_search.gif\" alt=\"Search\" title=\"Click here to search\">";
		$retText .= "		</a>";
		$retText .= "		</td>";
		$retText .= "	</tr>";
		$retText .= "</table>";
		$retText .= "<table style=\"width:475px; margin-top:8px;\" align=\"center\">";
		$retText .= "	<tr>";
		$retText .= "		<td>";
		#
		# $errorArr MAY BE FILLED WITH PROBELMS FROM THE STUDENT SEARCH
		#
		if(isset($errorArr) && count($errorArr) > 0 ) {
			foreach($errorArr as $errorText) {
				$retText .= "<font color=\"#FF0000\">Error: $errorText<BR></font>";
			}
		}
		$retText .= "		</td>";
		$retText .= "	</tr>";
		$retText .= "</table>";


		$retText .= "</div>";

		return $retText;
	}

}