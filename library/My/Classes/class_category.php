<?php

require_once ("iep_function_general.php");

class category {
	var $categoryName;
	
	function __construct($categoryName = '') {
		$this->categoryName = $categoryName;
	}
	
	function rowsToList($rowArray, $eleName) {
		// array([0] -> text 0, [1] -> text 1, etc)
		// to array(text 0, text 1, etc
		//
		$retArr = array ();
		foreach ( $rowArray as $key => $data ) {
			$retArr [] = $data [$eleName];
		}
		return $retArr;
	}
	
	function getCategories($phrase) {
		
		$tableName = "cat_key_piviot";
		
		//$keyName = "id_category";
		//SELECT category FROM cat_key_piviot WHERE kw_index  @@ to_tsquery('english', 'singing');
		$sqlStmt = "SELECT id_category, category from $tableName ";
		$sqlStmt .= "where kw_index @@ to_tsquery('english', '" . str_replace ( " ", " & ", $phrase ) . "') ";
		$sqlStmt .= "order by category ";
		$sqlStmt .= ";";
		
		//echo "<span style=\"font-size:small\">Step 1 - Choose Relevant Categories: <span style=\"color:red\"><B>$sqlStmt</B></span></span><BR>";
		

		$result = My_Classes_iepFunctionGeneral::sqlExecToArray ( $sqlStmt, $errorId, $errorMsg, true, false );
		if (false === $result) {
			echo "false<BR>";
			return false;
		} else {
			if (count ( $result ) > 0) {
				return $result;
				
			//return $this->rowsToList($result, 'category');
			} else {
				return false;
			}
		}
		return $result;
	}
	
	function getSubCategories($catLevel, $phrase) {
		
		//         $sqlStmt = "SELECT id_subcategory, full_category from cat_key_subcategory ";
		//         $sqlStmt .= "where kw_index @@ to_tsquery('english', '" . str_replace(" ", " & ", $phrase) . "') ";
		//         $sqlStmt .= "AND keywords ILIKE '%$phrase%'  ";
		//         $sqlStmt .= "order by full_category ";
		//         $sqlStmt .= ";";
		

		$sqlStmt = "select ";
		$sqlStmt .= "    id_subcategory, ";
		$sqlStmt .= "    kw_count, ";
		$sqlStmt .= "    full_category, ";
		//        $sqlStmt .= "    ts_rank_cd(kw_index, fsQuery, 8) as freq_rank ";
		$sqlStmt .= "    iCountInString(keywords, E'$phrase') as freq_rank ";
		
		$sqlStmt .= "from ";
		$sqlStmt .= "    cat_key_subcategory, ";
		$sqlStmt .= "    to_tsquery('english', '" . str_replace ( " ", " & ", $phrase ) . "') as fsQuery ";
		
		$sqlStmt .= "where ";
		$sqlStmt .= "    kw_index @@ fsQuery AND ";
		$sqlStmt .= "    keywords ILIKE '%$phrase%' ";
		
		$sqlStmt .= "order by ";
		$sqlStmt .= "    freq_rank desc;";
		
		echo "sqlStmt: $sqlStmt<BR>";
		//echo "<span style=\"font-size:small\">Step 1 - Choose Relevant Categories: <span style=\"color:red\"><B>$sqlStmt</B></span></span><BR>";
		

		$result = My_Classes_iepFunctionGeneral::sqlExecToArray ( $sqlStmt, $errorId, $errorMsg, true, false );
		if (false === $result) {
			echo "false<BR>";
			return false;
		} else {
			if (count ( $result ) > 0) {
				
				return $result;
				
			//return $this->rowsToList($result, 'category');
			} else {
				return false;
			}
		}
		return $result;
	}
	
	// SELECT id_subcategory, full_category FROM cat_key_subcategory WHERE kw_index @@ to_tsquery('english', '(cat&food)') AND keywords ILIKE '%cat food%' order by full_category;
	// SELECT id_subcategory, full_category from cat_key_subcategory where kw_index @@ to_tsquery('english', 'cat & food') keywords ILIKE '%cat food%' order by full_category ;
	

	function getCategoryByID($id_category) {
		
		$tableName = "cat_key_piviot";
		
		//$keyName = "id_category";
		//SELECT category FROM cat_key_piviot WHERE kw_index  @@ to_tsquery('english', 'singing');
		$sqlStmt = "SELECT id_category, category from cat_key_piviot ";
		$sqlStmt .= "where id_category = '$id_category' ";
		$sqlStmt .= ";";
		
		//echo "sqlStmt: $sqlStmt<BR>";
		

		$result = My_Classes_iepFunctionGeneral::sqlExecToArray ( $sqlStmt, $errorId, $errorMsg, true, false );
		if (false === $result) {
			echo "false<BR>";
			return false;
		} else {
			if (count ( $result ) > 0) {
				return $result [0];
				
			//return $this->rowsToList($result, 'category');
			} else {
				return false;
			}
		}
		return $result;
	}
	
	function getSubsByCategoryNameAndPhrase($tier_one_category, $phrase) {
		
		$tableName = "cat_key_piviot";
		
		//$keyName = "id_category";
		//SELECT category FROM cat_key_piviot WHERE kw_index  @@ to_tsquery('english', 'singing');
		$sqlStmt = "SELECT distinct full_category from cat_key_subcategory ";
		$sqlStmt .= "where tier_one_category = '$tier_one_category' and ";
		$sqlStmt .= "kw_index @@ to_tsquery('english', '" . str_replace ( " ", " & ", $phrase ) . "') ";
		$sqlStmt .= ";";
		
		echo "<span style=\"font-size:small\">Step 2 - Continue to Redefine Search: <span style=\"color:red\"><B>$sqlStmt</B></span></span><BR>";
		
		$result = My_Classes_iepFunctionGeneral::sqlExecToArray ( $sqlStmt, $errorId, $errorMsg, true, false );
		if (false === $result) {
			echo "false<BR>";
			return false;
		} else {
			if (count ( $result ) > 0) {
				return $result;
				
			//return $this->rowsToList($result, 'category');
			} else {
				return false;
			}
		}
		return $result;
	}
	
	function getSubsByCategoryNameAndPhraseArray($tier_one_categoryArr, $phrase) {
		
		$tableName = "cat_key_piviot";
		
		//$keyName = "id_category";
		//SELECT category FROM cat_key_piviot WHERE kw_index  @@ to_tsquery('english', 'singing');
		$sqlStmt = "SELECT distinct full_category from cat_key_subcategory ";
		$sqlStmt .= "where (";
		
		$addOrr = false;
		foreach ( $tier_one_categoryArr as $tier_one_category ) {
			if ($addOrr)
				$sqlStmt .= " OR ";
			$sqlStmt .= "tier_one_category = '$tier_one_category' ";
			$addOrr = true;
		}
		
		$sqlStmt .= ") and ";
		$sqlStmt .= "kw_index @@ to_tsquery('english', '" . str_replace ( " ", " & ", $phrase ) . "') ";
		$sqlStmt .= ";";
		
		echo "<span style=\"font-size:small\">Step 2 - Continue to Redefine Search: <span style=\"color:red\"><B>$sqlStmt</B></span></span><BR>";
		
		$result = My_Classes_iepFunctionGeneral::sqlExecToArray ( $sqlStmt, $errorId, $errorMsg, true, false );
		if (false === $result) {
			echo "false<BR>";
			return false;
		} else {
			if (count ( $result ) > 0) {
				return $result;
				
			//return $this->rowsToList($result, 'category');
			} else {
				return false;
			}
		}
		return $result;
	}
	
	function getKeywordsFromCategory($categoryName = '') {
		
		set_time_limit ( 600 );
		
		$tableName = "tenmil";
		$keyName = "id_kw_phrase";
		
		$sqlStmt = "SELECT * from $tableName ";
		if ('' != $document)
			$sqlStmt .= "where category = '$categoryName' ";
			//$sqlStmt .= "order by category ";
		$sqlStmt .= ";";
		
		//echo "sqlStmt: $sqlStmt<BR>";
		

		$result = My_Classes_iepFunctionGeneral::sqlExecToArray ( $sqlStmt, $errorId, $errorMsg, true, false );
		if (false === $result) {
			echo "getKeywordsFromCategory failed<BR>";
			return false;
		} else {
			$retVal = $result;
		}
		return $result;
	}
	
	function updatePivotCategory($catName) {
		
		set_time_limit ( 600 );
		
		$tableName = "tenmil";
		$keyName = "id_kw_phrase";
		
		$sqlStmt = "UPDATE cat_key_piviot set ";
		$sqlStmt .= "keywords = concat_kw_for_category('$catName') ";
		$sqlStmt .= "where category = '$catName' ";
		$sqlStmt .= ";";
		
		// echo "$sqlStmt<BR>";
		//return true;
		$result = My_Classes_iepFunctionGeneral::sqlExecToArray ( $sqlStmt, $errorId, $errorMsg, true, false );
		if (false === $result) {
			#echo "getKeywordsFromCategory failed<BR>";
			return false;
		} else {
			#$retVal  = $result;
			return true;
		}
		return $result;
	}
	
	function searchForm($currentSearch = 'cat food') {
		$output = "<form method=\"post\">";
		$output .= "    <input type=\"text\" name=\"searchPhrase\" id=\"searchPhrase\" size=\"50\" value=\"$currentSearch\">";
		$output .= "    <input type=\"hidden\" name=\"keywordSearch\" id=\"keywordSearch\" value=\"true\">";
		$output .= "    <BR />";
		$output .= "    <input type=\"button\" name=\"searchPhrase\" id=\"searchPhrase\" onclick=\"javascript:this.form.submit();\" value=\"Search\">";
		$output .= "</form>";
		
		return $output;
	}
	
	function getCategoryFromFullCategory($full_category, $level = 1, $prePost = '', $style = true) {
		$level = $level - 1;
		$retData = '';
		if (substr_count ( $full_category, '::' ) > 0) {
			$catArr = explode ( '::', $full_category );
			
			#pre_print_r($catArr);
			#echo "cat[$level]: " . $catArr[$level] . "<BR>";
			

			if ('post' == $prePost && count ( $catArr ) > $level) {
				
				for($i = $level; $i < count ( $catArr ); $i ++) {
					$retData .= $catArr [$i] . "::";
					if ($i != count ( $catArr ))
						$retData .= "::";
				}
				
				return $retData;
			
			} elseif ('pre' == $prePost && $level > 0) {
				for($i = 0; $i <= $level; $i ++) {
					$retData .= $catArr [$i];
					if ($i != $level)
						$retData .= "::";
				}
			
			} else {
				$retData .= $catArr [$level];
			}
			
			return $retData;
		} else {
			return $full_category;
		}
	}
	
//     function getCategoriesOLD( $document ='') {
//         $tableName = "cat_key_piviot";
//         //$keyName = "id_category";
//         $sqlStmt = "SELECT * from $tableName ";
//         if('' != $document) {
//             $sqlStmt .= "where id_category = '$document' and keywords is null ";
//         } else {
//             $sqlStmt .= "where keywords is null ";
//         }
//         $sqlStmt .= "order by category ";
//         $sqlStmt .= ";";
//         
//         echo "sqlStmt: $sqlStmt<BR>";
//         
//         $result = My_Classes_iepFunctionGeneral::sqlExecToArray($sqlStmt, $errorId, $errorMsg, true, false);
//         if (false === $result) {
//             echo "false<BR>";
//             return false;
//         } else {
//             $retVal  = $result;
//         }
//         return $result;
//     }
//     


}


