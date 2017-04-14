<?php

require_once ('class_form_element.php');
require_once ('class_category.php');
require_once ('class_result_tree.php');

class interface_web {
	var $loggedIn;
	var $web_userObj;
	var $categoryObj;
	
	function __construct($login = '', $username = '', $password = '') {
		
		//         echo "id_web_user: $id_web_user<BR>";
		//         echo "login: $login<BR>";
		//         echo "username: $username<BR>";
		//         echo "password: $password<BR>";    
		

		//        $web_userObj = new web_user($username, $password);
		//        $this->web_userObj = $web_userObj;
		//        
		$this->categoryObj = new category ( );
		//        
	//        if($this->web_userObj->loggedIn) {
	//            $this->id_web_user = $id_web_user;
	//        } else {
	//            return;
	//        }
	

	}
	
	//
	// the is the outermost container of the interface
	//
	function shell($subCategoryResults) {
		//pre_print_r($_POST);
		

		//
		// search the subcat table for this exact phrase
		//
		//        if(true == $_POST['keywordSearch'])
		//        {
		//            //
		//            // when an original search term is entered,
		//            // we search the subcat table for results
		//            //
		//            //echo "keywordSearch: " . $_POST['keywordSearch'] . "<BR>";
		//            
		//            // default to AND search on phrase
		//            //$this->subCategoryResults = $this->categoryObj->getSubCategories(1, $_POST['searchPhrase']);
		//            
		//            $_SESSION['subCategoryResults'] = $this->subCategoryResults;
		//            
		//        } else {
		//            //
		//            // when an original search term is entered,
		//            // we search the subcat table for results
		//            //
		//            $this->subCategoryResults = $_SESSION['subCategoryResults'];
		//        }
		

		$this->subCategoryResults = $subCategoryResults;
		//
		// build result tree object
		//
		$this->rObj = new result_tree ( );
		
		$this->rObj->processResultSet ( $this->subCategoryResults );
		
		$this->rObj->hideNodes ( $_POST ['catArr'], ($_POST ['showCatLev'] - 1) );
		
		$this->rObj->nodeSum ( 'kw_count' );
		$this->rObj->nodeSum ( 'freq_rank' );
		
		//$foundArr = $this->rObj->getNodeArr(3);
		//pre_print_r($foundArr);
		

		//         if(true == $_POST['keywordSearch'] || $_POST['catLevel'] > 1)
		//         {
		//             if(isset($_POST['catLevel'])) 
		//             {
		//                 $catLevel = $_POST['catLevel'];
		//             } else {
		//                 $catLevel = 1;
		//             }
		//             
		//             $aggArray = array();
		//             // aggregate results based on cat level
		//             for($i = 0; $i<count($this->subCategoryResults); $i++)
		//             {
		//                 if($catLevel > 1) $parentCategory = $this->categoryObj->getCategoryFromFullCategory($this->subCategoryResults[$i]['full_category'], $catLevel-1, '', false);
		//                 #$categoryExact = $this->categoryObj->getCategoryFromFullCategory($this->subCategoryResults[$i]['full_category'], $catLevel, '', false);
		//                 $category = $this->categoryObj->getCategoryFromFullCategory($this->subCategoryResults[$i]['full_category'], $catLevel, 'pre', false);
		//                 #echo "categoryExact: $categoryExact<BR>";
		//                 #echo "category: $category<BR>";
		//                 if('' != $category)
		//                 {
		//                     if(isset($aggArray[$category]))
		//                     {
		//                         //
		//                         // store subcategories 
		//                         //
		//                         $aggArray[$category]['full_category_array'][] = $this->subCategoryResults[$i]['full_category'];
		//                         $aggArray[$category]['aggCount'] += 1;                        
		//                         $aggArray[$category]['kw_count'] += $this->subCategoryResults[$i]['kw_count'];;
		//                     } else {
		//                         //
		//                         // store subcategories 
		//                         //
		//                         $aggArray[$category] = $this->subCategoryResults[$i];
		//                         $aggArray[$category]['aggCount'] = 1;
		//                         $aggArray[$category]['kw_count'] = $this->subCategoryResults[$i]['kw_count'];;
		//                         
		//                         $aggArray[$category]['full_category_array'] = array();
		//                         $aggArray[$category]['full_category_array'][] = $this->subCategoryResults[$i]['full_category'];
		//                         
		//                         $aggArray[$category]['parentCategory'] = $parentCategory;
		//                         
		//                         
		//                         //explode('::', $full_category)
		//                     }
		//                     if($catLevel > 1) 
		//                     {
		//                         if(array_search($parentCategory, $_POST['catArr'])===false) {
		//                             unset($aggArray[$category]);
		//                         }
		//                     }
		//                 }
		//             }
		//             
		//             
		//             $this->aggArray = $aggArray;
		//         }
		

		$output = "<table style=\"border:solid 2px black;width:100%;\">";
		
		$output .= "<tr>";
		$output .= "<td style=\"text-align:center;\">";
		$output .= "<a href=\"search.php\">Start Over</a>";
		$output .= "</td>";
		$output .= "</tr>";
		
		$output .= "<td style=\"text-align:center;\">";
		$output .= $this->body ();
		$output .= "</td>";
		$output .= "</tr>";
		$output .= "</table>";
		
		return $output;
	
	}
	
	//
	// the is the outermost container of the interface
	//
	function visitor_shell() {
		$output = "<table style=\"border:solid 2px black;width:100%;\">";
		
		$output .= "<tr>";
		$output .= "<td style=\"text-align:center;\">";
		$output .= $this->bar_interface_management ();
		$output .= "</td>";
		$output .= "</tr>";
		$output .= "</table>";
		
		return $output;
	
	}
	
	//
	// interface management bar
	//
	function bar_interface_management() {
		$output = "<table style=\"border:solid 2px black;width:100%;\">";
		
		$output .= "<tr>";
		$output .= "<td style=\"text-align:center;width:90%;\">";
		$output .= "Welcome to INFO dot COM<BR>";
		$output .= "</td>";
		$output .= "<td style=\"text-align:center;\">";
		$output .= $this->web_userObj->display_web_user_logon_panel ( 'edit', 'search.php' );
		$output .= "</td>";
		$output .= "</tr>";
		
		$output .= "</table>";
		
		return $output;
	
	}
	
	//
	// step 1
	//
	function bar_step_one($expand = false, $searchPhrase = '') {
		
		if (isset ( $_POST ['showCatLev'] ) && $_POST ['showCatLev'] > 0) {
			$showCatLev = $_POST ['showCatLev'];
		} else {
			$showCatLev = 1; // 1 or higher
		}
		
		$output = "<div style=\"border:solid 2px black;width:100%;text-align:left;\">";
		$output .= "Step 1 - Choose Relevant Categories";
		$output .= "</div>";
		
		$output .= "<form method=\"post\" name=\"refineCategoryOne\">";
		$output .= "<div style=\"border:solid 2px black;width:100%;text-align:left;\">";
		
		$output .= "<table class=\"treeTable\" style=\"border:solid 2px black;width:100%;\" >";
		
		$output .= "<tr>";
		
		$output .= "<td style=\"text-align:right;\" colspan=\"8\"><B>";
		$output .= "Search Term: " . $_POST ['searchPhrase'];
		$output .= "</B></td>";
		
		$output .= "</tr>";
		
		$output .= "<tr>";
		
		$output .= "<td style=\"text-align:right;\" colspan=\"8\"><B>";
		
		$output .= "    <input type=\"hidden\" name=\"showCatLev\" id=\"showCatLev\" value=\"" . ($showCatLev + 1) . "\">";
		$output .= "    <input type=\"button\" name=\"refineCategoryOne\" id=\"refineCategoryOne\" onclick=\"javascript:history.go(-1);\" value=\"Back\">";
		$output .= "    <input type=\"button\" name=\"showCatLev\" id=\"showCatLev\" onclick=\"javascript:this.form.submit();\" value=\"Continue Refining Search\">";
		$output .= "</B></td>";
		
		$output .= "</tr>";
		
		$output .= "<tr class=\"search\" >";
		
		//
		// header
		//
		$output .= "<td style=\"\"><B>";
		$output .= "Category";
		$output .= "</B></td>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "Match Rate for <BR />Search Term";
		$output .= "</B></td>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "View <BR />Subcategories";
		$output .= "</B></td>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "Exact Matches Containing <BR />Search Term";
		$output .= "</B></td>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "&nbsp;";
		$output .= "</B></td>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "Keywords Related <BR />to Category";
		$output .= "</B></td>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "&nbsp";
		$output .= "</B></td>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "Total Keywords <BR />in Category";
		$output .= "</B></td>";
		
		$output .= "</tr>";
		
		$output .= $this->rObj->display_tree_rows ( $showCatLev );
		
		$output .= "    <input type=\"hidden\" name=\"searchPhrase\" id=\"searchPhrase\" value=\"$searchPhrase\">";
		
		$output .= "<tr>";
		
		$output .= "<td style=\"text-align:right;\" colspan=\"8\"><B>";
		
		$output .= "    <input type=\"hidden\" name=\"showCatLev\" id=\"showCatLev\" value=\"" . ($showCatLev + 1) . "\">";
		$output .= "    <input type=\"button\" name=\"refineCategoryOne\" id=\"refineCategoryOne\" onclick=\"javascript:catLevel = document.getElementById('showCatLev');catLevel -=2;this.form.submit();\" value=\"Back\">";
		//                    $output .= "    <input type=\"button\" name=\"refineCategoryOne\" id=\"refineCategoryOne\" onclick=\"javascript:history.go(-1);\" value=\"Back\">";
		$output .= "    <input type=\"button\" name=\"showCatLev\" id=\"showCatLev\" onclick=\"javascript:this.form.submit();\" value=\"Continue Refining Search\">";
		$output .= "</td></B>";
		
		$output .= "</tr>";
		
		$output .= "</TABLE>";
		
		$output .= "</div>";
		$output .= "</form>";
		
		return $output;
	
	}
	
	//
	// step 2
	//
	function bar_step_two($catLevel, $expand = false) {
		#$this->searchResults_lvlTwo = array();
		

		$output = "<div style=\"border:solid 2px black;width:100%;text-align:left;\">";
		$output .= "Step 2 - Continue to Redefine Search";
		$output .= "</div>";
		
		//         if(true == $_POST['refineCategoryOne'])
		//         {
		// 
		//             if(count($_POST['catArr']) > 1 ) {
		//                     $this->searchResults_lvlTwo = $this->categoryObj->getSubsByCategoryNameAndPhraseArray($_POST['catArr'], $_POST['searchPhrase']);
		//             } else {
		//                 if(0 < count($_POST['catArr'])) // && true == $_POST['refineCategoryTwo']
		//                 {
		//                     foreach($_POST['catArr'] as $categoryName)
		//                     {
		//                         $this->searchResults_lvlTwo = array_merge($this->searchResults_lvlTwo, $this->categoryObj->getSubsByCategoryNameAndPhrase($categoryName, $_POST['searchPhrase']));
		//                     }
		//                 }
		//             }
		// 
		//             $output .= $this->display_second_lvl_category_results($_POST['catArr']);
		

		if ('' != $this->aggArray && $expand) {
			$output .= $this->display_category_results ( $catLevel, $_POST ['searchPhrase'] );
		} else {
		}
		// 
		//         } else {
		//             $output .= "no rows were found";
		//         }
		

		return $output;
	
	}
	
	//
	// step 3
	//
	function bar_continue_two($expand = false) {
		$output = "<div style=\"border:solid 2px black;width:100%;text-align:left;\">";
		$output .= "Step 3 - Continue to Redefine Search";
		$output .= "</div>";
		
		return $output;
	
	}
	
	//
	// step 4
	//
	function bar_redefine_purchase($expand = false) {
		$output = "<div style=\"border:solid 2px black;width:100%;text-align:left;\">";
		$output .= "Step 4 - Refine Keword Selections and Purchase";
		$output .= "</div>";
		
		return $output;
	
	}
	
	//
	// hood management bar
	//
	function body() {
		
		$output = "<table style=\"border:solid 2px black;width:100%;\">";
		
		//pre_print_r($_POST);
		

		if (true == $_POST ['keywordSearch'] || 0 < $_POST ['showCatLev']) {
			
			$output .= "<tr>";
			$output .= "<td style=\"text-align:center;width:20%;\">";
			if (true == $_POST ['refineCategoryOne']) {
				$output .= $this->bar_step_one ( false, $_POST ['searchPhrase'] );
			} else {
				$output .= $this->bar_step_one ( true, $_POST ['searchPhrase'] );
			}
			$output .= "</td>";
			$output .= "</tr>";
			
			$output .= "<tr>";
			$output .= "<td style=\"text-align:center;width:20%;\">";
			if ($_POST ['catLevel'] > 1) {
				$output .= $this->bar_step_two ( $_POST ['catLevel'], true );
			}
			$output .= "</td>";
			$output .= "</tr>";
			
			$output .= "<tr>";
			$output .= "<td style=\"text-align:center;width:20%;\">";
			$output .= $this->bar_continue_two ();
			$output .= "</td>";
			$output .= "</tr>";
			
			$output .= "<tr>";
			$output .= "<td style=\"text-align:center;width:20%;\">";
			$output .= $this->bar_redefine_purchase ();
			$output .= "</td>";
			$output .= "</tr>";
			
			$output .= "<tr>";
			$output .= "<td style=\"text-align:center;\">";
			
			$output .= "</td>";
			$output .= "</tr>";
		} else {
			
			$output .= "<tr>";
			$output .= "<td style=\"text-align:center;\">";
			$output .= $this->search_interface ( $_POST ['searchPhrase'] );
			$output .= "</td>";
			$output .= "</tr>";
		
		}
		
		$output .= "</table>";
		
		return $output;
	
	}
	
	//
	// search interface
	//
	function search_interface($currentSearch = '') {
		$output = "<table style=\"border:solid 2px black;\">";
		
		$output .= "<tr>";
		$output .= "<td style=\"text-align:center;\">";
		$output .= "What's your business?";
		$output .= "</td>";
		$output .= "</tr>";
		
		$output .= "<tr>";
		$output .= "<td style=\"text-align:center;\">";
		$output .= "Search generally (\"plummer\") or specifically (\"plummer potable water 60601\")";
		$output .= "</td>";
		$output .= "</tr>";
		
		$output .= "<tr>";
		$output .= "<td style=\"text-align:center;\">";
		
		if ('' != $currentSearch) {
			$this->subCategoryResults = $this->categoryObj->getCategories ( $currentSearch );
			
			$output .= $this->categoryObj->searchForm ( $currentSearch );
		} else {
			
			$output .= $this->categoryObj->searchForm ();
		
		}
		
		$output .= "</td>";
		$output .= "</tr>";
		
		$output .= "</table>";
		
		return $output;
	
	}
	
	//
	// search interface
	//
	function display_category_results($catLevel = 1, $currentSearch = '') {
		//echo "display_category_results: $currentSearch<BR>";
		#pre_print_r($this->subCategoryResults);
		

		$output = "<form method=\"post\" name=\"refineCategoryOne\">";
		$output .= "<input type=\"hidden\" name=\"searchPhrase\" id=\"searchPhrase\" size=\"50\" value=\"$currentSearch\">";
		$output .= "<input type=\"hidden\" name=\"refineCategoryOne\" id=\"refineCategoryOne\" value=\"true\">";
		$output .= "<input type=\"hidden\" name=\"catLevel\" id=\"catLevel\" value=\"" . ($catLevel + 1) . "\">";
		
		$output .= "<table style=\"border:solid 2px black;width:100%;\">";
		
		$output .= "<tr class=\"search\" >";
		
		//
		// header
		//
		$output .= "<td style=\"\"><B>";
		$output .= "Category";
		$output .= "</td></B>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "Match Rate for <BR />Search Term";
		$output .= "</td></B>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "View <BR />Subcategories";
		$output .= "</td></B>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "Exact Matches Containing <BR />Search Term";
		$output .= "</td></B>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "&nbsp;";
		$output .= "</td></B>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "Keywords Related <BR />to Category";
		$output .= "</td></B>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "&nbsp";
		$output .= "</td></B>";
		
		$output .= "<td style=\"\"><B>";
		$output .= "Total Keywords <BR />in Category";
		$output .= "</td></B>";
		
		$output .= "</tr>";
		
		foreach ( $this->rObj->nodeArr as $nodeObj ) {
			
			$output .= "<tr>";
			$output .= "<td style=\"text-align:left\">";
			#                            $output .= "<span style=\"color:red;font-size:small;\">";
			#                            if(substr_count($row['full_category'], '::') > 0) $output .= $this->categoryObj->getCategoryFromFullCategory($row['full_category'], $catLevel-1, 'pre');
			#                            if($catLevel > 1) $output .= "::";
			#                            $output .= "</span>";
			#                            $output .= "<span style=\"\">" . $this->categoryObj->getCategoryFromFullCategory($row['full_category'], $catLevel) . "</span>";
			$output .= "</td>";
			
			$output .= "<td style=\"\">";
			$output .= $row ['freq_rank'];
			$output .= "</td>";
			
			$output .= "<td style=\"\">";
			#                            if($catLevel < (substr_count($row['full_category'], '::')+1)) $output .= "<input type=\"checkbox\" checked name=\"catArr[]\" value=\"".$this->categoryObj->getCategoryFromFullCategory($row['full_category'], $catLevel)."\">";
			$output .= "</td>";
			
			$output .= "<td style=\"\">";
			$output .= "view samples";
			$output .= "</td>";
			
			$output .= "<td style=\"\">";
			$output .= "+";
			$output .= "</td>";
			
			$output .= "<td style=\"\">";
			$output .= "&nbsp;";
			$output .= "</td>";
			
			$output .= "<td style=\"\">";
			$output .= "=";
			$output .= "</td>";
			
			$output .= "<td style=\"\">";
			$output .= $row ['kw_count'];
			$output .= "</td>";
			$output .= "</tr>";
		}
		
		$output .= "<tr>";
		
		$output .= "<td style=\"text-align:right;\" colspan=\"8\"><B>";
		if ($catLevel >= 2)
			$output .= "        <input type=\"button\" name=\"refineCategoryOne\" id=\"refineCategoryOne\" onclick=\"javascript:history.go(-1);\" value=\"Back\">";
		$output .= "        <input type=\"button\" name=\"refineCategoryOne\" id=\"refineCategoryOne\" onclick=\"javascript:this.form.submit();\" value=\"Continue Refining Search\">";
		$output .= "</td></B>";
		
		$output .= "</tr>";
		
		$output .= "</table>";
		$output .= "</form>";
		
		return $output;
	
	}

}


