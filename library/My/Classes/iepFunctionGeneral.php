<?php
/**************** CHANGES ***********/
// 2003-03-23 sl edits to reflect new interface to old getUserPrivClass function
// 030401-JL added id_guardian to log for better log display
// 030411-JL moved functions from student_student
// 030428-JL added some sesis helper functions



// ================================================================================================================================
// database and general functions
// ================================================================================================================================

class My_Classes_iepFunctionGeneral {

    public static function xmlRpcslqExec($sqlStmt, &$errorId, &$errorMsg, $errorCapture = true, $errorNoResults = true, $replaceNULL = true)
    {
        global $dbH, $ERROR_SQL_EXEC, $ERROR_NO_RESULTS, $sessIdUser, $DB_NAME, $DB_USER_NAME, $DB_HOST, $DB_PORT, $DB_PASSWORD;
    
        /*
        * This allows us to use the functions that make
        * making XML-RPC requests easy.
        */
        //require_once("lib/xmlrpc_utils/utils.php");
        $path = 'lib/ZendFramework/library';
    
        set_include_path(get_include_path() . PATH_SEPARATOR . $path);
    
        /*
        * The difference between this call and the last is we pass
        * in an array as the 'args' element of the array passed as
        * the argument of the xu_rpc_http_concise() function.
        */ 
        $sqlExecArgs =  array();
        
        $sqlExecArgs['sqlStmt'] = $sqlStmt;
        $sqlExecArgs['errorId'] = $errorId;
        $sqlExecArgs['errorMsg'] = $errorMsg;
        $sqlExecArgs['errorCapture'] = $errorCapture;
        $sqlExecArgs['errorId'] = $errorId;
        $sqlExecArgs['errorNoResults'] = $errorNoResults;
        $sqlExecArgs['replaceNULL'] = $replaceNULL;
    
        $sqlExecArgs['ERROR_SQL_EXEC'] = $ERROR_SQL_EXEC;
        $sqlExecArgs['ERROR_NO_RESULTS'] = $ERROR_NO_RESULTS;
        $sqlExecArgs['sessIdUser'] = $sessIdUser;
    
        $sqlExecArgs['DB_NAME'] = $DB_NAME;
        $sqlExecArgs['DB_USER_NAME'] = $DB_USER_NAME;
        $sqlExecArgs['DB_HOST'] = $DB_HOST;
        $sqlExecArgs['DB_PORT'] = $DB_PORT;
        $sqlExecArgs['DB_PASSWORD'] = $DB_PASSWORD;
        
        $host = "srs-dev";
        //$host = "localhost";
        $uri = "/xmlrpc_server.php";
        $port = 8001;
    	//$port = 5432;
    
        require_once 'Zend/XmlRpc/Client.php';
        $client = new Zend_XmlRpc_Client("http://$host:$port$uri");
        
        //var_dump($errorNoResults);
    
        try {
        
            $result =  $client->call('psql.sqlExec_func', array($sqlExecArgs));
            
            $resultData = $result['resultData'];
            $resultOther = $result['resultOther'];
            $affectedRows = $result['affectedRows'];
            
            #echo "errorId: |".$resultOther['errorId']."|<BR>";
            #echo "errorMsg: |".$resultOther['errorMsg']."|<BR>";
    
            if('' != $resultOther['errorId'] || '' != $resultOther['errorMsg']) 
            {
                echo "<B>error</B>: $sqlStmt<BR>";
                echo "<B>errorId</B>: {$resultOther['errorId']}<BR>";
                echo "<B>errorMsg</B>: {$resultOther['errorMsg']}<BR>";
                //pre_print_r($result);
                
                $errorId = $resultOther['errorId'];
                $errorMsg = $resultOther['errorMsg'];
                return false;
    
            } else {
                $stmtType = strtoupper(substr($sqlStmt, 0, 3));
                #echo "affectedRows: $affectedRows<BR>";
                #echo "stmtType: $stmtType<BR>";
                if($stmtType == "UPD") {
                    if($errorNoResults && 0 == $affectedRows) {
                        #echo "ret: false<BR>";
                        return false;
                    } else {
                        //pre_print_r($result['params']);
                        #echo "ret: true<BR>";
                        return true;
                    }
                }
    //             echo "sqlStmt: $sqlStmt<BR>";
    //             echo "result: $result<BR>";
    //             var_dump($resultData);
    //             var_dump($resultOther);
    //             pre_print_r($result);
                #if($stmtType == "INS") pre_print_r($result);
                return $resultData;
            }
      
        } catch (Zend_XmlRpc_Client_HttpException $e) {
            
            echo "Zend_XmlRpc_Client_HttpException code: " . $e->getCode() . "<BR>";
            echo "Zend_XmlRpc_Client_HttpException getMessage: " . $e->getMessage() . "<BR>";
            return false;    
        }     
    }
    
    public static function sqlExecToArray($sqlStmt, &$errorId, &$errorMsg, $errorCapture = true, $errorNoResults = true, $replaceNULL = true)
    {
        global $dbH, $ERROR_SQL_EXEC, $ERROR_NO_RESULTS, $sessIdUser, $DB_NAME, $DB_USER_NAME, $DB_HOST, $DB_PORT, $DB_PASSWORD;
    
        $stmtType = strtoupper(substr($sqlStmt, 0, 3));
        if ($replaceNULL) {
            // edited 9/2/02 sl to account for fact that the old ways fails when single quote is at very end of data
            $sqlStmt = str_replace("\'", "\apos", $sqlStmt);
            $sqlStmt = str_replace("''", "NULL", $sqlStmt);
            $sqlStmt = str_replace("\apos", "\'", $sqlStmt);
        }
        #echo "sqlStmt: $sqlStmt<BR>";
        #echo "dbh: $dbH<BR>";
        if (!$result = pg_exec($dbH, $sqlStmt)) {
            if ($errorCapture) {
                if ( (substr_count( pg_errormessage() , "referential integrity violation")) ) {
                    $errorId = ERROR_INTEGRITY_CONSTRAINT;
                    $errorMsg = "<br/>Database Said: " . pg_errormessage();
                    $errorMsg .= "<br/><br/>SQL Statement: " . str_replace("\n", "<br/>", $sqlStmt);
                } else {
                    $errorId = $ERROR_SQL_EXEC;
                    $errorMsg = "<br/>Database Said: " . pg_errormessage();
                    $errorMsg .= "<br/><br/>SQL Statement: " . str_replace("\n", "<br/>", $sqlStmt);
                }
            }
            return false;
        } elseif ($stmtType == "SEL") {
            $rows = pg_numrows($result);
            if ($errorNoResults && $rows == 0) {
                $errorId = $ERROR_NO_RESULTS;
                $errorMsg = "SQL Statement: " . str_replace("\n", "<br/>", $sqlStmt);
                return false;
            } else {
                return self::pg2php($result, PGSQL_ASSOC);
            }
        } elseif ($stmtType == "INS" || $stmtType == "UPD" || $stmtType == "DEL") {
            $tuples = pg_cmdtuples($result);
            if ($errorNoResults && $tuples == 0) {
                $errorId = $ERROR_NO_RESULTS;
                $errorMsg = "SQL Statement: " . str_replace("\n", "<br/>", $sqlStmt);
                return false;
            } else {
                if ($tuples) {
                    return self::pg2php($result, PGSQL_ASSOC);
                } else {
                    return true;
                }
            }
        } else {
            return true;
        }    
    
    }
    
    public static function pg2php($pgResult, $arrayType = PGSQL_BOTH) {
        #
        # return array types PGSQL_BOTH, PGSQL_ASSOC, PGSQL_NUM
        #
        $rows = pg_numrows($pgResult);
        if(0 == $rows) return array();
        for($i = 0; $i < $rows; $i++) {
            $data[] = pg_fetch_array($pgResult, $i, $arrayType);
        }
        //return "freak";
        return $data;
    }
        
    public static function buildInsertStmt($arrFieldList, $arrData, $tableName, $pkeyName) {
    
        reset($arrFieldList);
    
        $sqlStmt = 	"INSERT INTO $tableName (";
        //while (list($fieldName, $value) = each($arrFieldList)) {
        foreach($arrFieldList as $fieldName) {
            if ($i++) {
                $sqlStmt .= ", ";
            }
            $sqlStmt .= $fieldName;
        }
        $sqlStmt .= ")\n VALUES ( ";
        reset($arrFieldList);
        $i = 0;
        //while (list($fieldName, $value) = each($arrFieldList)) {
        foreach($arrFieldList as $fieldName) {
            if ($i++) {
                $sqlStmt .= ", ";
            }
            // edit  9/5/2002 sl to account for need to have all fields be quote-escaped
            // mgic-quotes in PHP are on, and this handles GPC (get-post-cookie) data
            // and most db values are posted back to a page before submission and get quote-escaped BUT
            // at least for forms, see include_form_head 1 around lines 330-345, more data is added to the array
            // before submission, and this will not be quote-escaped
            // Could have tried to handle each case, or turn off magic quotes, but best is just to strip all 
            // slashes here and then add them back.
            if (is_array($arrData[$fieldName])) { //echo "imploding";
                $dataElement = implode("|", $arrData[$fieldName]);
            } else {
                $dataElement = $arrData[$fieldName];
            }
            $sqlStmt .= "'" . addslashes( stripslashes( $dataElement ) ) . "'";
            
        }
        $sqlStmt .= ");\n";
        return $sqlStmt;
    }

	/* use the field list array that's passed from the page to build an update statement */
	public static function buildUpdateStmt(&$document, &$arrFieldList, &$arrData, &$tableName, &$pkeyName) {
		//debugLog( "saving");
		global $sessIdUser;
		
		reset($arrFieldList);
		$sqlStmt = 	"UPDATE $tableName\nSET ";
		while (list($fieldName, $value) = each($arrFieldList)) {
			//print( "field = $fieldName, value = $value" );
			if ($i++) {
				$sqlStmt .= ", ";
			}
			if (is_array($arrData[$fieldName])) { //echo "imploding";
				//debugLog( "     imploding array");
				//$sqlStmt .= $fieldName . " = '" . implode("|", $arrData[$fieldName]) . "'";
				$dataElement = implode("|", $arrData[$fieldName]);
			} else {
				//$sqlStmt .= $fieldName . " = '" . $arrData[$fieldName] . "'";
				$dataElement = $arrData[$fieldName];
			}
			$sqlStmt .= $fieldName . " = '" . addslashes( stripslashes( $dataElement ) ) . "'";
			
		}
		$sqlStmt .= "\nWHERE $pkeyName = $document;\n";
		//debugLog ( "update stmt = $sqlStmt");
		return $sqlStmt;
	}
	// ================================================================================================================================
	// END database functions
	// ================================================================================================================================
	
	// ================================================================================================================================
	// general functions
	// ================================================================================================================================
	
	public static function pre_print_r($data, $return = false) {
	     $output = "=========================== data ==================<BR>";
	     $output .= "<span style=\"font-size:small;\">";
	     $output .= "<pre>";
	     $output .= print_r($data, true);
	     $output .= "</pre>";
	     $output .= "</span>";
	     $output .= "=========================== end data ==================<BR>";
	     if($return)
	     {
	        return $output;
	     } else {
	        echo $output;
	     }
	}
	
	public static function priceKeywords($kwCount, $payRate = 1) {
		if ($kwCount == 0) {
			$total = 0;
		} elseif ($kwCount <= 1000) {
			$total = 150;
		} elseif ($kwCount <= 10000) {
			$thousands = floor(($kwCount - 1000) / 1000);
			$remainder = $kwCount % 1000;
			if ($remainder > 0) {
				$total = (150 + ($thousands * 50) + 50);
			} else {
				$total = (150 + ($thousands * 50));
			}
		} else {
			$thousands = floor(($kwCount - 10000) / 1000);
			$remainder = $kwCount % 1000;
			// alert( 'kwCount: ' + kwCount + '\n' +
			// '(kwCount-10000): ' + (kwCount-10000) + '\n' +
			// 'thousands: ' + thousands + '\n' +
			// 'remainder: ' + remainder + '\n'
			// );
			if ($remainder > 0) {
				$total = (150 + 450 + ($thousands * 30) + 30);
			} else {
				$total = (150 + 450 + ($thousands * 30));
			}
		}
	    if($payRate != '') $total = round($total * $payRate, 2);
	
	    // apply discount
	    return $total;
		
	}

	// ================================================================================================================================
	// END general functions
	// ================================================================================================================================

	// ================================================================================================================================
	// functions that work on the global array
	// ================================================================================================================================
    public static function globalBackup($newvalue, $name, $backupName)
    {
        $GLOBALS[$backupName] = $GLOBALS[$name];
        $GLOBALS[$name] = $GLOBALS[$newvalue];
    }

    public static function globalRestore($name, $backupName) {
        $GLOBALS[$name] = $GLOBALS[$backupName];
        unset($GLOBALS[$backupName]);
    } 

    public static function replaceSpacesInQuotes($str, &$quotedPhrases, $replacement='_')
    {
        $quoteOn = false;
        $quoteChar = '"';
        $spaceChar = ' ';
        $retString = '';
                
        for($i=0;$i<strlen($str);$i++) {
            if($str[$i] == $quoteChar) {
                $quoteOn = !$quoteOn;
                if($quoteOn){
                    // init phrase
                    $currentPhrase = '';
                } else {
                    // quote being turned off
                    // add word to phraseArr
                    $quotedPhrases[] = $currentPhrase;
                }
//                $retString .= $str[$i];
                $retString .= "''";
            } elseif($str[$i] == $spaceChar && $quoteOn) {
                $str[$i] = $replacement;
                if($quoteOn) $currentPhrase .= $str[$i];
                $retString .= $replacement;
            } else {
                if($quoteOn) $currentPhrase .= $str[$i];
                $retString .= $str[$i];
            }
        }
        return $retString;
    }
    
    public static function replaceDisallowedCharacters($str)
    {
        #echo "str: $str<BR>";
        $illegal = array('|', '&');
        $str = str_replace($illegal, "", $str);

        #echo "str: |$str|<BR>";
        //
        // ' ' replace multiple spaces with one space
        //
        $pattern = '/ +/i';
        $replacement = ' ';
        $str = preg_replace($pattern, $replacement, $str);

        #echo "str: |$str|<BR>";
        #die('test');
        return $str;
    }
    
	/* return javascript for displaying text in window status onMouseOver and onMouseOut */
	public static function windowStatus($displayText) {
		return "onMouseOver=\"javascript:window.status='$displayText'; return true\" onMouseOut=\"javascript:window.status=''; return true;\"";
	}

	public static function getPrivClass($id_county, $id_district, $id_school) {
		global $sessUserPrivs, $sessUserMinPriv;
		
		$sessUser = new Zend_Session_Namespace('user');
		$sessUserMinPriv = $sessUser->sessUserMinPriv;
		$sessUserPrivs = $sessUser->sessUserPrivs;
	//	Zend_debug::dump($sessUser->sessUserPrivs);die();
		
		if(!isset($sessUserPrivs))
		{
		    // use zend session
	    	$session = new Zend_Session_Namespace();
	
		    $sessUserMinPriv = $session->sessUserMinPriv;
		    $sessUserPrivs = $session->sessUserPrivs;
		}
		
		// IF PARENT, RETURN UC_SA
		if($sessUserMinPriv == UC_PG) {
			return UC_PG;
		}
	
		// IF SUPER USER IS MIN PRIV, RETUN UC_SA
		if($sessUserMinPriv == UC_SA) {
			return UC_SA;
		}
		//disassemble($sessUserPrivs, 0, "=====sessUserPrivs=====");
		$privCount = count($sessUserPrivs);
		//debugLog("privCount: ".$privCount);
	
		//debugLog("id_district: ".$id_district);
		//debugLog("id_school: ".$id_school);
		
		// loop to check for super user
		for ($h = 0; $h < $privCount; $h++) {
			//debugLog("sess_id_county: ".$sessUserPrivs[$h]['id_county']."=".$id_county);
			//debugLog("sess_id_district: ".$sessUserPrivs[$h]['id_district']."=".$id_district);
			//debugLog("sess_id_school: ".$sessUserPrivs[$h]['id_school']."=".$id_school);
			//debugLog("sess_id_class: ".$sessUserPrivs[$h]['class']);
			
			//debugLog("Dist Length: ".strlen(trim($sessUserPrivs[$h]['id_district'])));
			
			
			if( strlen(trim( $sessUserPrivs[$h]['id_district'])) == 0 && strlen(trim( $sessUserPrivs[$h]['id_school'])) == 0){
				$activeClass = $sessUserPrivs[$h]['class'];
				//debugLog("sess_class SU: ".$sessUserPrivs[$h]['class']);
				//debugLog("sess_class SU: ".$activeClass);
				return $activeClass;
			}
		}
		//debugLog("GPC: NO SUPER ACCESS");
	
		// loop to check for district
		for ($i = 0; $i < $privCount; $i++) {
			//debugLog("sess_id_district: ".$sessUserPrivs[$i]['id_district']." = ".$id_district);
			//debugLog("sess_id_school: ".$sessUserPrivs[$i]['id_school']." = ".$id_school);
			
			if($sessUserPrivs[$i]['id_county'] == $id_county && $sessUserPrivs[$i]['id_district'] == $id_district && strlen(trim( $sessUserPrivs[$i]['id_school'])) == 0){
				$activeClass = $sessUserPrivs[$i]['class'];
				//debugLog("sess_class dist: ".$sessUserPrivs[$i]['class']);
				return $activeClass;
			}
		}
		//debugLog("GPC: NO DISTRICT ACCESS");
		//
		//
		//
		// loop to check for school
		// edited 2003-03-05 sl/jl to check all school-level privs and not bail on first match
		$activeClass = 1000;
		for ($j = 0; $j < $privCount; $j++) {
			//debugLog("sess_id_district: ".$sessUserPrivs[$j]['id_district']." = ".$id_district);
			//debugLog("sess_id_school: ".$sessUserPrivs[$j]['id_school']." = ".$id_school);
			
			if( $sessUserPrivs[$j]['id_county'] == $id_county && $sessUserPrivs[$j]['id_district'] == $id_district && $sessUserPrivs[$j]['id_school'] == $id_school){
				$activeClass = min( $activeClass, $sessUserPrivs[$j]['class']);
			}
		}
		if ($activeClass != 1000 ) {
		    return $activeClass;
		}
		//debugLog("GPC: NO ACCESS");
		//
		//
		//
		// ALL CHECKS FAILED, NO ACCESS
		return 0;
	}

	public static function buildOptionListAccess($arrData, $area, $view, $page='', $menuLimiterArr ='', $sessPrivCheckObj= null) {
		
        $DOC_ROOT = DOC_ROOT;           // set in initialize function and set in application.ini
        $NONZEND_ROOT = NONZEND_ROOT;   // set in initialize function and set in application.ini
        
		//echo "NONZEND_ROOT: ".NONZEND_ROOT."<BR>";
		//echo "zendRoot: ".zendRoot."<BR>";
		if(isset($arrData['form_no']) && $arrData['form_no']!='') {
			$formNum = $arrData['form_no'];
		} else {
			$formNum = substr($view, -3, 3);
		}
		#
		# ON IEPS, WE HAVE TO BUILD A LINK TO CREATE PROGRESS REPORTS
		# DATA IS ADDED TO arrData BEFORE IT'S PASSED INTO THIS FUNCTION 
		#
		#
		# FORM ARRAYS PASSED FROM FORM CENTER WILL HAVE THEIR IDS IN THE ID FIELD
		# BUT FORM ARRAYS PASSED FROM THE FORM PAGE WILL HAVE THEM IN THEIR KEY NAMES (ID_FORM_013 FOR EXAMPLE)
		# PARSE OUT THE FORM ID HERE
		#
		if(isset($arrData['id']) && $arrData['id'] != '') {
			$formID = $arrData['id'];
		} else {
			$formID = "id_form_$formNum";
			$formID = $arrData[$formID];
		}
		
		
		// 20070327 jlavere
		// adding a menu item
		// add item in access_definitions folder in lib in the class that will have the item
		// add the item to the $menuLimiterArr in buildOptionListAccess (this function)
		// add to iep_class_buildAccess.php accessDescriptions function
		// add code for the sql call in include form head 1
		#########################################################################################
		##
		# IF ACCESS HAS BEEN GRANTED, AND IT MUST TO BE HERE, THE USER HAS A $sessPrivCheckObj->accessObJ						
		#
		# menuLimiterArr IS THE LIST THAT MANUALLY LIMITS THE OPTIONS DISPLAYED IN THE SUB MENU
		# ARRAY SHOULD CONTAIN ALL POSSIBLE VALUES - FILTERING BASED ON CLASS AND ACESS WILL BE DONE IN THE SCRIPT
		#
		if($menuLimiterArr == '') {
			$menuLimiterArr = array_flip(array( 'view', 'edit', 'delete', 'log', 'dupe', 'dupe_form_004', 'dupe_form_013', 'dupe_form_013_update', 'print', 'finalize', 'createpr', 'dupe_form_013_update', 'archive'));
		}
		#
		# BUILD THE SUB MENU BASED ON STUDENT/AREA/SUB
		#
		#log_print_r($arrData);
		#log_print_r($formNum, 'formNum');
        #var_dump($sessPrivCheckObj);
		$availableMenus = '';//$sessPrivCheckObj->accessObj->availableFormOptions($sessPrivCheckObj->accessArray, $formNum, $menuLimiterArr, $arrData['status']);
        if(isset($availableMenus['dupe']) && '' == $availableMenus['dupe']) {
            unset($availableMenus['dupe']); // 20080421 jlavere - if dupe element has no value, it wont be shown, so we need to remove it from the array
            // this removes the extra | char on the right of the form options for individual forms
        }
        
		#pre_print_r($availableMenus);
		# availableMenus IS BUILT AT THE TIME ACCESS IS GRANTED IN STUDENT_STUDENT
		#
		$JS_redirectCode = "onChange=\"javascript: goToURL('$DOC_ROOT', '$area', '$view', 'document', $formID, '&page=$page&option=this.value');\" ";
		#echo "<td align=\"right\" class=\"bts\" style=\"padding-left:10px;\">";
		if($availableMenus == false) {
			return 'Menu Could Not Be Built';
		} else {
			require_once('My/Classes/class_form_element.php');
			$formInput = new form_element('function', 'return');
			#$formInput->form_input_select( 'options', '', true, $JS_redirectCode, array_flip($availableMenus), 'Choose...', '');
			#
			#<a href="javascript: goToURL('http://209.242.196.95/srs-dev', 'student', 'form_004', 'document', 1014658, '&page=&option=log');" onMouseOver="javascript:window.status='Form Log'; return true" onMouseOut="javascript:window.status=''; return true;">log</a>
			####
			## ARRAY OF LINKS
			#$formText = ($option =="forms"?("form_" . $formNo):$sub);
			#
			$id_student = isset($arrData['id_student'])?$arrData['id_student']:"";
            
            
            //
            // 
            //
            
            if('024' == $formNum)
            {
#               require_once('class_session_tokenizer.php');
#                $sessToke = new session_tokenizer();
#                $token = $sessToke->update_token();

                $webroot = zendRoot;
                $zendController = str_replace('_', '', $view);

                $linkArray = array();
                $linkArray['view'] = "javascript: goToURLZend('$webroot', '$area', '$zendController/view', 'document', ".$formID.", '/page/$page/option/view');";
                $linkArray['delete'] = "javascript: goToURLZend('$webroot', '$area', '$zendController', 'document', ".$formID.", '/page/$page/option/delete');";
                if('010' == $formNum) {
                    $linkArray['edit'] = "javascript: goToURLZend('$webroot', '$area', '$zendController', 'document', ".$formID.", '/page/$page/option/edit/prclick/edit');";
                } else {
                    $linkArray['edit'] = "javascript: goToURLZend('$webroot', '$area', '$zendController', 'document', ".$formID.", '/page/$page/option/edit');";
                }
                $linkArray['log'] = "javascript: goToURLZend('$webroot', '$area', '$zendController', 'document', ".$formID.", '/page/$page/option/log');";
                $linkArray['finalize'] = "javascript: goToURLZend('$webroot', '$area', '$zendController', 'document', ".$formID.", '/page/$page/option/finalize');";
                $linkArray['archive'] = "javascript: goToURLZend('$webroot', '$area', '$zendController', 'document', ".$formID.", '/page/$page/option/archive');";
                $linkArray['dupe'] = "javascript: goToURLZend('$webroot', '$area', '$zendController', 'document', ".$formID.", '/page/$page/option/dupe');";
                $linkArray['dupe_form_004'] = "javascript: goToURLZend('$webroot', '$area', '$zendController', 'document', ".$formID.", '/page/$page/option/dupe_form_004');";
                $linkArray['dupe_form_013'] = "javascript: goToURLZend('$webroot', '$area', '$zendController', 'document', ".$formID.", '/page/$page/option/dupe_form_013');";
                $linkArray['dupe_form_013_update'] = "javascript: goToURLZend('$webroot', '$area', '$zendController', 'document', ".$formID.", '/page/$page/option/dupe_form_013_update');";
                $linkArray['createpr'] = "javascript: goToURLZend('$webroot', '$area', 'form_010', 'document', '', '/student/".$id_student."/iepID/".$formID."/option/new');";
                #
                # THERE'S NO PRINT PAGE FOR THE NOTES PAGE
                # SO WE JUST GO TO THE FORM TO BE PRINTED
                #
                if(0 && $formNum == "017") {
                    $linkArray['print'] = "javascript:print('$webroot/srs.php?area=$area&sub=form_$formNum$sub&page=$page&option=print&document=$formID', 'Printing');\"";
                } else {
                    $linkArray['print'] = "javascript:print('$webroot/form_print.php?form=form_$formNum&document=".$formID."', 'Printing');\"";
                }

            } else {
                $webroot = $DOC_ROOT;

                $linkArray = array();
                $linkArray['view'] = "javascript: goToURL('$webroot', '$area', '$view', 'document', ".$formID.", '&page=$page&option=view');";
                $linkArray['delete'] = "javascript: goToURL('$webroot', '$area', '$view', 'document', ".$formID.", '&page=$page&option=delete');";
                if('010' == $formNum) {
                    $linkArray['edit'] = "javascript: goToURL('$webroot', '$area', '$view', 'document', ".$formID.", '&page=$page&option=edit&prclick=edit');";
                } else {
                    $linkArray['edit'] = "javascript: goToURL('$webroot', '$area', '$view', 'document', ".$formID.", '&page=$page&option=edit');";
                }
                $linkArray['log'] = "javascript: goToURL('$webroot', '$area', '$view', 'document', ".$formID.", '&page=$page&option=log');";
                $linkArray['finalize'] = "javascript: goToURL('$webroot', '$area', '$view', 'document', ".$formID.", '&page=$page&option=finalize');";
                $linkArray['archive'] = "javascript: goToURL('$webroot', '$area', '$view', 'document', ".$formID.", '&page=$page&option=archive');";
                $linkArray['dupe'] = "javascript: goToURL('$webroot', '$area', '$view', 'document', ".$formID.", '&page=$page&option=dupe');";
                $linkArray['dupe_form_004'] = "javascript: goToURL('$webroot', '$area', '$view', 'document', ".$formID.", '&page=$page&option=dupe_form_004');";
                $linkArray['dupe_form_013'] = "javascript: goToURL('$webroot', '$area', '$view', 'document', ".$formID.", '&page=$page&option=dupe_form_013');";
                $linkArray['dupe_form_013_update'] = "javascript: goToURL('$webroot', '$area', '$view', 'document', ".$formID.", '&page=$page&option=dupe_form_013_update');";
                $linkArray['createpr'] = "javascript: goToURL('$webroot', '$area', 'form_010', 'document', '', '&student=".$id_student."&iepID=".$formID."&option=new');";
                #
                # THERE'S NO PRINT PAGE FOR THE NOTES PAGE
                # SO WE JUST GO TO THE FORM TO BE PRINTED
                #
                if(0 && $formNum == "017") {
                    $linkArray['print'] = "javascript:print('$webroot/srs.php?area=$area&sub=form_$formNum$sub&page=$page&option=print&document=$formID', 'Printing');\"";
                } else {
                    $linkArray['print'] = "javascript:print('$webroot/form_print.php?form=form_$formNum&document=".$formID."', 'Printing');\"";
                }

            }



			#http://209.242.196.95/srs-dev/srs.php?area=student&sub=form_001&student=1000098&option=new
			#http://209.242.196.95/srs-dev/srs.php?area=student&sub=form_004&page=&student=1000098option=new
			##
			# PUT THIS IN FOR EVERY ELEMENT
			#
			$internalExtra = "onMouseOver\"javascript:window.status='Form Log'; return true\" onMouseOut=\"javascript:window.status=''; return true;\"";
			
			# #################################################################################
			# PRINT LINK NEEDS TO HAVE AN ID SO IT CAN BE TURNED OFF WHEN THE PAGE IS MODIFIED
			#
			$preExtra = array();
			$preExtra['print'] = "<span id=\"hrefPrint\">";

			$postExtra = array();
			$postExtra['print'] = "</span>";
			# #################################################################################
			# #################################################################################
			#
			# BUILD THE LINK LIST
			#

			$retLinkList = $formInput->form_link_list( true, $availableMenus, $linkArray, $internalExtra, $preExtra, $postExtra);

			return $retLinkList;
		}
		#
		##
		#########################################################################################
	}

	public static function date_massage($dateField, $dateFormat = 'm/d/Y') {
		
		if(empty($dateField) ) {
			return;
		}
	
	    # strtotime mishandles dates with '-' 
	    $dateField=str_replace("-","/",$dateField);
	    date_default_timezone_set('GMT');
		return date($dateFormat, strtotime($dateField));
	
	}
	
	/* encode special html characters with option to convert dates for display */
	public static function htmlEncode($str, $formatDate = false) {
	
		if (empty($str)) {
			return $str;
		}
		
		$str = htmlspecialchars($str);
		$str = stripslashes($str);
		
		if ($formatDate) {
			$str = displayDate($str);
		}
		
		return $str;
	}

            
                
    /* build html select list of numbers */
    public static function dropDownButtonNumbers($name, $qty, $offset = 1, $currentValue = false, $defaultValue = false, $defaultLabel = false, $attributes = false) {
        
        $arrLabel = array();
        $arrValue = array();
        
        for($i = 0; $i < $qty; $i++) {
            $arrLabel[$i] = $i + $offset;
            $arrValue[$i] = $i + $offset;
        }
    
        $strHTML = "<div dojoType=\"dijit.form.DropDownButton\" name=\"$name\" id=\"$name\"";
        
        if (!empty($attributes)) {
            $strHTML .= " $attributes";
        }
//        Zend_Debug::dump($attributes);
        $strHTML .= ">";
        $strHTML .= "<span>Page</span>";
        
        if($currentValue == "" && $defaultValue != "") {
            $currentValue = $defaultValue;
        }
        
        if($defaultLabel != "none") {
            $strHTML .= "<option value=\"\" selected=\"selected\">$defaultLabel</option>";
        }
        
        $strHTML .= '<div dojoType="dijit.Menu" id="Page">';
        
        
        $count = count($arrLabel);
        for($i = 0; $i < $count; $i++) {
//            if($currentValue == $arrValue[$i]) {
//                $strHTML .= "<option value=\"$arrValue[$i]\" selected=\"selected\">$arrLabel[$i]</option>";
//            } else {
//                $strHTML .= "<option value=\"$arrValue[$i]\">$arrLabel[$i]</option>";
//            }
            $strHTML .= '<div dojoType="dijit.MenuItem" id="navPage_'.$arrLabel[$i].'" label="'.$arrLabel[$i].'"></div>';
        }
        $strHTML .= "</div>";
        
        $strHTML .= "</div>";
        
        return $strHTML;    
    }
            
	/* build html select list of numbers */
	public static function valueListNumbers($name, $qty, $offset = 1, $currentValue = false, $defaultValue = false, $defaultLabel = false, $attributes = false) {
		
		$arrLabel = array();
		$arrValue = array();
		
		for($i = 0; $i < $qty; $i++) {
			$arrLabel[$i] = $i + $offset;
			$arrValue[$i] = $i + $offset;
		}
	
		$strHTML = "<select name=\"$name\" id=\"$name\"";
	
		if (!empty($attributes)) {
			$strHTML .= " $attributes";
		}
		
		$strHTML .= ">";
		
		if($currentValue == "" && $defaultValue != "") {
			$currentValue = $defaultValue;
		}
		
		if($defaultLabel != "none") {
			$strHTML .= "<option value=\"\" selected=\"selected\">$defaultLabel</option>";
		}
		
		$count = count($arrLabel);
		for($i = 0; $i < $count; $i++) {
			if($currentValue == $arrValue[$i]) {
				$strHTML .= "<option value=\"$arrValue[$i]\" selected=\"selected\">$arrLabel[$i]</option>";
			} else {
				$strHTML .= "<option value=\"$arrValue[$i]\">$arrLabel[$i]</option>";
			}
		}
		
		$strHTML .= "</select>";
		
		return $strHTML;	
	}
}

