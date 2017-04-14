<?
/******************************************
** Description   : Generic Class to apply updates to a database table
** Version       : 1.0
** Author        : Steve Lane
** Author Email  : slane@moyergroup.com
** Created       : 2003-04-06
** Last Modified : 
*'
	========================================================================
	INFO:
	========================================================================
	This class is designed to process text files containing changes (create, edit, delete)
	to a database table. At the moment it's designed for Postgres, and works
	with the DBRequest class.
	It is assumed that one column of the source file holds a change type ("create,
	"update", or "delete").
	
	Modifications are designed to roll in the functions normally performed by the upload scripts
	such as finding the files, dowloading or moving them, and managing the logs.


	****************************************/
global $debug;
$debug = true;
function dp($call,$cname) { 
	// call: the variable you want to print_r 
	// cname: the label for your debugging output 
	global $debug; 
	if ($debug){ 
		echo $cname.":<pre>"; 
		if (!is_array($call) && !is_object($call)) { $call=htmlspecialchars($call); } 
		print_r($call); 
		if ( is_array($call) || is_object($call)) { reset($call); } 
		echo "</pre><hr>";
	}
}
	
require_once ("class_DBRequest.inc");

define("ERROR_NO_ERROR", 0);		// no error
define("ERROR_DB_CONNECT", 100);	// database connection error
define("ERROR_SQL_EXEC", 101);		// sql execution error
define("ERROR_NO_RESULTS", 110);	// either no rows returned or no tuples affected by sql stmt
define( "NOT_IMPLEMENTED", -1 );
define( "BAD_PARAM", -2 );
define("FTP_RETRIEVE", 1);
define("LOCAL_PATH_RETRIEVE", 2);

class DBUpdater {

	var $request;			// the DBRequest object
	
	var $db;
	var $user;
	var $host;
	var $pass;
	var $port;
	var $databaseTable;
	var $pKeyName;			// name of primary key in above table. This can be a string or an array of strings
	var $sourceFile;		// the source text file for the update
	var $sourceFields;		// an array of the fields contained in the source file
	var $extraInsertFields;		// array of additional fields that will need to be inserted into (not relevant for update or delete)
	var $extraUpdateFields;		// array of additional fields that will need to be updated into (not relevant for insert or delete)
	var $extraFields;		// array of additional fields that will need to be both updated and inserted into
	
	var $abortAllOnError;	// boolean: do we perform the whole process as one transaction?
	var $separator;			// character used as field delim in source files
	
	// vars dealing with finding and getting the files
	var $fileRetrievalMethod;   // do we pull the files by FTP, local retrieval or some other method? Use the defined constants
	var $remoteUser;            // user name on remote system from which we retrieve files
	var $remotePass;            // user password on remote system from which we retrieve files
	var $remoteHost;
	var $fromDir;              // this can be used for remote OR local path to a file
	var $toDir;                // local path where we'll drop files for further processing
	var $fileErrors;           // string holding any errors from moving files

	var $logFileName;		// name of file for all error logging
	var	$logFP;				// file pointer to the open log file
	
	var $logBuffer;			// inClass buffer to build log
	var $timeString;		// timeString to be entered on all DB records
	
	var	$insertCount = 0;
	var	$updateCount = 0;
	var	$errorCount = 0;
	var $currentLine = 0;
	
	var $echoLogEntries = true; // sl 2003-11-11 flag to say whether to also echo out log entries
	
	function DBUpdater() {
        $this->init(); // HANDLE ERRORS HERE!
	}
	
	// "abstract" base function, must be overridden by subclasses
	function init() {
        return NOT_IMPLEMENTED;
	}
	
	function addLog( $message, $addBreak = false ) {
	    $message = date("Y-m-d H:i:s") . " " . $message;
	    $message .= $addBreak?"\n\n":"\n"; // add extra break if necessary
		$this->logBuffer .= $message;
		if ( $this->echoLogEntries == true ) {
		    echo $message;
		}
	}
	
	// tries to connect to database, returns false on failure
	function dbConnect() {
	    // create connect string
		$connectString = "dbname = " . $this->db . " user = " . $this->user . " host=" . $this->host;
		if ( isset( $this->port ) || $this->port != '' ) {
			$connectString .= " port=" . $this->port ;
		}
		//echo $connectString."<BR>";
		// use it to connect to the database. Postgres-specific
		if (!$dbH = @pg_connect( $connectString ) ) {
			$errorId = ERROR_DB_CONNECT;
			echo ("Could not connect to database: $connectString\n");
			return false;
		}
		$this->request->dbH = $dbH;
		return true;
	}

	// takes an array of file names. all retrieval parameters have to be correctly set for this to work.
	function getFiles ( $fileNameArray ) {
		//echo "getting files<BR>";
	    // right now we assume all the parameters are validly set
	    // this is obviously dangerous
	    if ( $this->fileRetrievalMethod == LOCAL_PATH_RETRIEVE ) {
	        $result =  $this->getLocalFiles( $fileNameArray );
	    } elseif ( $this->fileRetrievalMethod == FTP_RETRIEVE ) {
	        $result =  $this->getFTPFiles( $fileNameArray );
	    } else { // unknown retrieval method
	        $this->addLog( "Bad file retrieval params: unknown retrieval method(" . $this->fileRetrievalMethod . ")" );
			$result = BAD_PARAM;
	    }
	    //$this->addLog("Getting ready to return $result from getFiles()");
	    return $result;
	
	}
	
	// get files from a local path. Returns true if all files moved successfully, false if some moves fail, or BAD_PARAM
	function getLocalFiles ( $fileNameArray ) {
		if ( $this->fromDir == '' || $this->toDir == '' ) {
		    $this->addLog( "Bad file retrieval params: fromDir or toDir is missing\n" );
			return BAD_PARAM;
		}
		$overallResult = true;
		$this->fileErrors='';
		foreach ( $fileNameArray as $currentFileName ) {
			$startPath = ($this->fromDir) . "/$currentFileName";
			$this->addLog("Getting local file $startPath");
			$moveOutput = array();
			$moveResult = null; // initialize
			$command = "mv $startPath " . $this->toDir;
			$command .= " 2>&1"; 							// must capture stderr! PHP system() calls only grab stdout
			exec( $command, $moveOutput, $moveResult );
			//echo "move result = $moveResult<br />";
			//echo "move command = $command<br />";
			//print_r($moveOutput);
			if ( $moveResult > 0 ) { 						// signals failure of move command
				$errorText = $moveOutput[0];
				$this->fileErrors .= $errorText . "\n";
				$this->addLog( $errorText );
				$overallResult = false;                
			}
		}
		// LOG THE RESULTS OF THE MOVE
		if($overallResult) {
			$this->addLog("Download Successful!");
		} else {
			$this->addLog("Download failed!\n********** $timeStringLog END   **********");
		}
		return $overallResult;
	}
	
	// get files from a local path. Returns true if all files moved successfully, false if some moves fail, or BAD_PARAM
	function getFTPFiles ( $fileNameArray ) {
		if ( $this->fromDir == '' || $this->toDir == '' || $this->remoteHost == '' || $this->remoteUser == '' ) {
			$this->addLog( "Bad file retrieval params: fromDir or toDir or remoteHost or remoteUser is missing.\n" );
			return BAD_PARAM;
		}
		$overallResult = true;
		$this->fileErrors='';
		//
		// CHECK TO MAKE SURE CURL IS AVAILABLE
		//$curlPath = `which curl 2>&1`;
		$curlPath = "/usr/bin/curl";
		//echo "curlPath = $curlPath";
		if ( strstr($curlPath, "no curl in") != false ) {
			$this->fileErrors .= "cURL command not found in path: message = $curlPath\n";
			$this->addLog( "cURL command not found in path: message = $curlPath\n");
			return false;
			exit;
		}
		//
		// LOOP THROUGH THE LIST OF FILES AND GET THEM WITH CURL
		foreach ( $fileNameArray as $currentFileName ) {
			$startPath = ($this->fromDir) . "/$currentFileName";
			$transferOutput = array();
			$transferResult = null; // initialize
			//
			// BUILD THE CURL CMD
			$command = "$curlPath -u " . $this->remoteUser . ":" . $this->remotePass . " ftp://" . $this->remoteHost;
			if(isset($this->fromDir)) {
				$command .= "/../.." . $startPath;
			} else {
				$command .= $currentFileName;
			}
			$command .= " -o " . $this->toDir . "/" . $currentFileName; // sets the output filepath and name
			$command .= " 2>&1"; // must capture stderr! PHP system() calls only grab stdout
			$this->addLog("Getting remote file: $currentFileName");
			
			//
			// EXECUTE THE COMMAND
			echo "command: $command\n";
			exec( $command, $transferOutput, $transferResult );
			//
			// $transferResult WILL CONTAIN 0 IF THE ACTION IS SUCCESSFUL
			if ( $transferResult > 0 ) { // signals failure of move command
				$errorText = $transferOutput[0] . "\n";
				$this->fileErrors .= $errorText;
				$this->addLog( $errorText );
				$overallResult = false;                
			}
		}
		// LOG THE RESULTS OF THE MOVE
		if($overallResult) {
			$this->addLog("Download Successful!");
		} else {
			$this->addLog("Download failed!\n********** $timeStringLog END   **********");
		}
		return $overallResult;
	}
	
	
	// basic logging interface to put error messages somewhere
	function writeLog( $message ) {
		fwrite ( $this->logFP, $message . "\n");
		return;
	}
	
	// assuming the source file has been opened successfully, process a line of it
	function processLine ( $lineBuf, $parseGuardianID = false) {
		if ( $lineBuf =='' ) {
			return;
		}
		#
		# INCREMENT THE LINE COUNTER
		#
		$this->currentLine++;
		
		echo("Processing line " . $this->currentLine . "<BR>");
		#
		# PRE-PROCESS LINE
		#
		$massagedLine = $this->preprocessLine( $lineBuf );
		//$this->writeLog ( $massagedLine );
		//print_r( $bufArray ); exit;
		#
		# EXPLODE THE LINE
		#
		$bufArray = explode( $this->separator, $massagedLine );
		$dataFieldCount = count( $bufArray );
		#
		# MAKE SURE WE HAVE THE RIGHT NUMBER OF FIELDS
		#
		if ( $dataFieldCount != count( $this->sourceFields ) ) {
			//echo( "<br />Incorrect number of fields: source has " . count($this->sourceFields) . ", but line has $dataFieldCount\n");
			$this->errorCount++;
			$this->addLog( $this->currentLine . ": ERROR: Incorrect number of fields" );
			$this->addLog( $massagedLine );
			//$this->writeLog( $this->currentLine . ": ERROR: Incorrect number of fields");
			//$this->writeLog ( $massagedLine );
		}
		#
		# FILL DATA ARRAY
		#
		$dataArray = array();
		$i = 0;
		while( $i < $dataFieldCount ) {
		    // removed checks for changeType sl 2003-04-20
			/*if ( $this->sourceFields[$i] == 'change_type') {
				$changeType = strtolower( trim( $bufArray[$i] ) );		
			} else {*/
			
			#
			# SET THE NEW KEY AND DATA
			#
			if($parseGuardianID && $this->sourceFields[$i] == 'id_guardian_local') {
				#
				# NEEDED A UNIQUE KEY FOR PARENTS, BUT KEY IS TOO BIG AND CONTAINS DUPLICATE STUDENT ID DATA
				# IF THE PARSE GUARDIAN ID IS SET, WE ATTEMPT TO STRIP OUT THAT STUDENT ID FROM THE GUARDIAN ID
				#
				#echo "source: ".$this->sourceFields[$i] . "  data: ".$bufArray[$i] . "<BR>";
				#echo "id_student_local: ".$bufArray[($i+1)] . "<BR>";
				#echo "source: ".substr($bufArray[$i], -6, 4). "<BR>";
				#
				# 
				#
				$guardianID = $bufArray[$i];
				$studentID = $bufArray[($i+1)];
				$lenStudentID = count($studentID);
				#
				# IF THE STUDENT ID IS EMBEDDED IN THE GUARDIAN ID
				#
				if( substr_count($guardianID, $studentID) == 1) {
					#
					# REPLACE SUBSTRING
					#
					$guardianID = str_replace($studentID, '', $guardianID);
					//echo "replacing ONE found student ID in guardian ID<BR>";
					#
					#
				} elseif( substr_count($guardianID, $studentID) > 1) {
					#
					# FROM WHAT I CAN TELL, THE STUDENT ID SHOULD BE JUST BEFORE A 2 DIGIT CODE
					#
					if( substr($guardianID, -($lenStudentID +2), $lenStudentID) == $studentID ) {
						//echo "replacing default student substring in guardian ID<BR>";
						$guardianID = substr($guardianID, -($lenStudentID +2), $lenStudentID);
					}
					#
					#
				}
				#
				# SET THE KEY AND DATA
				//echo "OLD GUARDIAN: " . $bufArray[$i] . " : : : : : NEW(".$studentID."): " . $guardianID . "<BR>";
				#
				$dataArray[ $this->sourceFields[$i]] = str_replace( '\044', ',', $guardianID); // un-escape any escaped commas in the fields
				#
				#
			} else {
				#
				# SET THE KEY AND DATA
				#
				$dataArray[ $this->sourceFields[$i]] = str_replace( '\044', ',', $bufArray[$i]); // un-escape any escaped commas in the fields
			}
			
			
			
			/*}*/
			$i++;
		}
		
		// sl 2003-04-05 new code to add certain fields before determing whether something is an update or an insert
		// we need some of these fields, particularly data source, to determine uniqueness, so we need to insert them now.
		$extraFieldArray = $this->extraFields; // grab the new extraFields array
		// the following is code copied from below. Would be better to factor it out
		$extraFieldCount = count( $extraFieldArray );
		//echo "got $extraFieldCount extra fields<br />";
		$i = 0;
		while( $i < $extraFieldCount ) {
			//echo ("count = $i, name = " . $extraFieldArray[$i][0] . ", value = " .$extraFieldArray[$i][1] );
			$fieldName = $extraFieldArray[$i][0];
			$fieldValue =  $extraFieldArray[$i][1];
			$fieldValue = $this->checkFunction( $fieldValue, $dataArray ); // if this field is a function call, fill in the params
			$dataArray[ $extraFieldArray[$i][0]] = $fieldValue;
			$i++;
		}
		
		// sl 2003-04-20 moved this code from below to set changeType based on uniqueness
		// no longer going to depend on them sending a value for this
		
		$unique = $this->checkUniqueness($dataArray);
		//echo "unique: $unique<BR>";
		

		//$this->writeLog( "uniqueness check = $unique");
		if ( $unique == -1 ) { // some error checking for uniqueness
			$this->addLog( $this->currentLine . ": Error checking record for uniqueness");
			//$this->writeLog( $this->currentLine . ": Error checking record for uniqueness");
			return;
		} elseif ( $unique == 0 ) {
			$changeType = 'update'; // if the record is already there, force it to an update
		} elseif ( $unique == 1 ) {
			$changeType = 'create'; // if the record isn't there, force it to an insert
		}
        // end moved code 2003-04-20 sl
		
		// add any extra values necessary
		if ( $changeType == 'create' ) {
			$extraFieldArray = $this->extraInsertFields;
		} elseif ( $changeType == 'update' ) {
			$extraFieldArray = $this->extraUpdateFields;
		}
		$extraFieldCount = count( $extraFieldArray );
		//echo "got $extraFieldCount extra fields<br />";
		$i = 0;
		while( $i < $extraFieldCount ) {
			//echo ("count = $i, name = " . $extraFieldArray[$i][0] . ", value = " .$extraFieldArray[$i][1] );
			$fieldName = $extraFieldArray[$i][0];
			$fieldValue =  $extraFieldArray[$i][1];
			$fieldValue = $this->checkFunction( $fieldValue, $dataArray ); // if this field is a function call, fill in the params
			$dataArray[ $extraFieldArray[$i][0]] = $fieldValue;
			$i++;
		}
		
		if ( $changeType == 'create' ) {
			$sqlStmt = $this->buildInsertStmt( $dataArray );
		} elseif ( $changeType == 'update' ) { 
			$sqlStmt = $this->buildUpdateStmt( $dataArray );
		}
		echo "Sql = " . $sqlStmt . "\n";
		
		$this->request->sqlStmt = $sqlStmt;
		//echo "sqlStmt: " . $sqlStmt . "<BR>";
		//$this->request->errorId == ERROR_NO_ERROR;
		$this->request->sqlExec();
		echo "sql exec successful\n";
		if ( $this->request->errorId == ERROR_NO_ERROR ) {
			if ( $changeType == 'create' ) {
				$this->insertCount++;
			} elseif ( $changeType == 'update' ) { 
				$this->updateCount++;
			}
			//$this->writeLog($sqlStmt);
		} else { // got some sql error
			$this->addLog( $this->currentLine . ": " . $this->request->errorMsg );
			//$this->writeLog( $this->currentLine . ": " . $this->request->errorMsg );
			$this->errorCount++;
			//$this->writeLog( "ERROR: " . $this->request->errorMsg );
			$this->addLog("\n");
			//$this->writeLog("\n");
		}
		//$this->writeLog("\n");
		echo "###### INSERTS " . $this->insertCount . " UPDATES " . $this->updateCount . " ERRORS " . $this->errorCount . "\n";
		return;
	}
	
	
	/* The "extra" fields can contain function calls. If used, these calls have to have parameters that refer to the original source fields.
	This function will check to see if the field value is in fact a function call. If so it will replace the param names with their values
	from the data array and hand the result back*/
	
	function checkFunction( $fieldValue, $dataArray ) {
		//echo ("check function, value = $fieldValue<br />");
		$isMatch = preg_match( "/([^\(]+)\(([^\)]+)\)/", $fieldValue, $matches ); // regexp searches for function_name(param1, param2 ...)
		if ( ! $isMatch ) {
			return $fieldValue;
		} else {
			//echo ("got regex match<br />");
			$name = $matches[1]; $params = $matches[2]; // first subexpression is function name, second is param list
			//echo ("name = $name, params = $params");
			$paramArray = explode( ",", $params );
			while (list($key, $value) = each($paramArray)) {
			    if ( strpos( $value, '::') != false ) { // literal value, pass it through
			        $paramArray[$key] = $value;
				} else {
				    $paramArray[$key] = $dataArray[$value];
				}
			}
			$newParams = implode( ",",  $paramArray );
			$ret =  "$name( $newParams )";
			//echo "<br />$ret<br />";
			return $ret;
		}
	}
	
	// process the source file line by line
	function processFile($parseGuardianID = false) {
		#
		# OPEN SOURCE FILE
		#
    	$this->addLog( "Opening source file " . $this->sourceFile );
		if ( !$fp = fopen( $this->sourceFile, 'r' ) ) {
			//$this->writeLog ("error opening source file");
			$this->addLog( "error opening source file " . $this->sourceFile );
			return false;
		}
		#
		# PROCESS EACH LINE
		#
		while ( !feof( $fp ) ) {
			$lineBuf = fgets( $fp, 10000 );  // need max length in PHP < 4.2.0
			$this->processLine( $lineBuf , $parseGuardianID);
		}
		$summaryString = "Total inserts: " . $this->insertCount . ", Total updates: " . $this->updateCount . ", Total errors: " . $this->errorCount ;
		#
		# WRITE RESULTS TO LOG
		#
		$this->addLog( $summaryString, true );
		//
		//echo "summaryString: " . $summaryString . "<BR>";
		#
		# CLOSE THE FILE
		#
		fclose( $fp);
		
	}
	
	// initial massage on the line, in this case to handle commas inside quotes
	// all commas inside fields will be ASCII-converted: \044
	function preprocessLine ( $lineBuf ) { 
	
		$count = 0;
		$inQuotes = 0;
		$output = '';
		$length = strlen( $lineBuf );
		while($count < $length ) {
			$currentChar = $lineBuf[$count++];
			if ($currentChar == '"' ) {
				$inQuotes = 1 - $inQuotes; // flip the flag for being in or out of quotes
			} else {
				if ( $currentChar != ',' ) { // if it's not a comma, pass it through
					$output .= $currentChar;
				} else { // process a comma
					if ( $inQuotes == 1 ) { // escape any commas inside quotes (turn to ASCII code)
						$output .= '\044';
					} else {
						$output .= $currentChar;
					}
				}
			}
		}
		return trim($output); // sl added trim 2003-04-20 to deal with possible trailing CR\LF problem
	}
	
	function buildInsertStmt( $dataArray ) {
	
		reset($dataArray);

		$sqlStmt = 	"INSERT INTO " . $this->databaseTable;
		$fieldList = "(";
		$valueList = "(";
		while (list($fieldName, $value) = each($dataArray)) {
			//echo ("name = $fieldName, value = $value<br />");
			if ( $i++ ) {
				$fieldList .= ", ";
				$valueList .= ", ";
			}
			$fieldList .= $fieldName;
			if ( ( empty( $value ) || $value == '') && $value != '0') { 
				$valueList  .= 'NULL';
			} else {
				 // if we're using a function call, do not encode or quote it. Check only the extra fields! Source fields may contain data that looks like a function call
				if (preg_match( "/([^\(]+)\(([^\)]+)\)/", $value ) && ( ! in_array( $fieldName, $this->sourceFields ) ) ) {
					$valueList .= $value;
				} else {
					$valueList .= "'" . addslashes( $value ) . "'"; // assume the raw values are not previously slash-encoded, and do it here
				}
			}
		}
		$fieldList .= ") ";
		$valueList .= ")";
		$sqlStmt .= " \n$fieldList VALUES $valueList;\n";
		reset($dataArray);
		//echo $sqlStmt; exit;
		return $sqlStmt;
	}
	
	function buildUpdateStmt( $dataArray ) {
	
		reset($dataArray);

		$sqlStmt = 	"UPDATE " . $this->databaseTable . " SET ";
		while (list($fieldName, $value) = each($dataArray)) {
			//echo ("name = $fieldName, value = $value<br />");
			/*if ( $fieldName == $this->pKeyName ) {
				$keyVal = $value; // grab the value of the primary key
			}*/
			if ( $i++ ) {
				$sqlStmt .= ", ";
			}
			$sqlStmt .= "$fieldName=" ;
			if ( (  empty( $value ) || $value == '')  && $value != '0' ) { // don't bother inserting into this column if no data is supplied
				$sqlStmt .= 'NULL';
			} else {
				if (preg_match( "/([^\(]+)\(([^\)]+)\)/", $value ) && ( ! in_array( $fieldName, $this->sourceFields ) ) ) { // if we're using a function call, do not encode or quote it
					$sqlStmt .= $value;
				} else {
					$sqlStmt .= "'" . addslashes( $value ) . "'"; // assume the raw values are not previously slash-encoded, and do it here
				}
			}
		}
		if ( ! is_array( $this->pKeyName ) ) { // just a single key
			$sqlStmt .= " WHERE " . $this->pKeyName . "='" . $dataArray[ $this->pKeyName ] . "'";
		} else {
			//echo "got array<br />";						// need to match on multiple keys
			//print_r( $this->pKeyName );
			$sqlStmt .= " WHERE ";
			reset( $this->pKeyName ); $j = 0;
			while (list($index, $keyName) = each( $this->pKeyName )) {
				//echo "got key<br />";
				if ( $j++ > 0) { $sqlStmt .= " AND "; }
				$sqlStmt .= "$keyName='" . addslashes($dataArray[ $keyName ]) . "'";
			}
		}
		//echo $sqlStmt; exit;
		reset($dataArray);
		return $sqlStmt;
	}
	
	// take an array corresponding to a single database record and check if it's already been inserted
	function checkUniqueness( $dataArray ) {
	
		$sqlStmt = "select 1 from " . $this->databaseTable . " ";
		if ( ! is_array( $this->pKeyName ) ) { // just a single key
			$sqlStmt .= " WHERE " . $this->pKeyName . "='" . $dataArray[ $this->pKeyName ] . "'";
		} else {
			//echo "got array<br />";						// need to match on multiple keys
			//print_r( $this->pKeyName );
			$sqlStmt .= " WHERE ";
			reset( $this->pKeyName ); $j = 0;
			while (list($index, $keyName) = each( $this->pKeyName )) {
				//echo "got key<br />";
				if ( $j++ > 0) { $sqlStmt .= " AND "; }
				$sqlStmt .= "$keyName='" . addslashes($dataArray[ $keyName ]) . "'";
			}
		}
		//echo ( "unique check stmt = $sqlStmt\n");
		$this->request->errorNoResults = false; // no results is fine
		$this->request->sqlStmt = $sqlStmt . ";";
		//echo "<BR><BR>checkUniqueness: " . $sqlStmt. "<BR>";
		#
		# ECECUTE THE UNIQUENESS CHECK
		#
		$this->request->sqlExec();
		if ( $this->request->errorId == ERROR_NO_ERROR ) {
			//ECHO  "got row count of " . $this->request->rows ."<BR>";
			if ( $this->request->rows > 0 ) {
				return 0;	// ROWS FOUND, RETURN FALSE
			} else {
				return 1;	// RETURN TRUE
			}
		} else { // got some sql error
			echo("error = " . $this->request->errorId);
			return -1;
		}
	}

	function districtImportCodeExists( $code ) {
	
		$sqlStmt = "select count(1) from iep_district where district_import_code = '$code';";
		$this->request->errorNoResults = true; // no results is bad
		$this->request->sqlStmt = $sqlStmt;
		$this->request->sqlExec();
		if ( $this->request->errorId == ERROR_NO_ERROR ) {
            
			if ( $this->request->rows == 1 ) {
				echo "districtImportCodeExists: 1\n";
				return 1;
			} else {
				echo "districtImportCodeExists: 0\n";
				return 0;
			}
		} else { // got some sql error
			echo("error = " . $this->request->errorId);
			return -1;
		}
	}

	function countOfMatchingDistrictImportCodes( $code ) {
	
		$sqlStmt = "select 1 from iep_district where district_import_code = '$code';";
		$this->request->errorNoResults = true; // no results is bad
		$this->request->sqlStmt = $sqlStmt;
		$this->request->sqlExec();
		if ( $this->request->errorId == ERROR_NO_ERROR ) {
            return $this->request->rows;
		} else { // got some sql error
			echo("error = " . $this->request->errorId);
			return -1;
		}
	}
	function updateDistrictImportCodes( $districtArrayList, $code ) {
	    
	    print_r($districtArrayList);
		$sqlStmt = "update iep_district set pref_district_imports = 't', district_import_code = '$code' where ";
		$addAnd = false;
		foreach($districtArrayList as $districtArr)
		{
		    if($addAnd) $sqlStmt .= " OR ";
		    $sqlStmt .= "(id_county = '".$districtArr['id_county']."' and id_district = '".$districtArr['id_district']."')";
		    $addAnd = true;
		}
		//echo $sqlStmt;
		//return true;
		$this->request->errorNoResults = true; // no results is bad
		$this->request->sqlStmt = $sqlStmt;
		$this->request->sqlExec();
		if ( $this->request->errorId == ERROR_NO_ERROR ) {
            return true;
            return $this->request->rows;
		} else { // got some sql error
			echo("error = " . $this->request->errorId);
			return -1;
		}
	}


}

class DBUpdater_LPS_student extends DBUpdater {
	function DBUpdater_LPS_student() {
        $this->init(); // HANDLE ERRORS HERE!
	}
	
	// "abstract" base function, must be overridden by subclasses
	function init() {
		$db = "iep_shang";
		$user = "postgres";
		$host = "localhost";
		$port = '';
		$table = "iep_student";
		$pkey = "id_student_local";
		$fileName = "STUUPDT.TXT";

		// create DBRequest object
		$this->request = new DBRequest(''); // pass it a null query to start with
		$this->request->replaceNULL = false; // do not replace nulls
		$this->databaseTable = $table;
		$this->pKeyName = $pkey;
		$this->sourceFile = $this->toDir . $fileName;
		//echo "sourceFile: " . $this->sourceFile . "<BR>";
		$this->separator = ',';
		$this->abortAllonError = false;		// default to false
		//$this->logFile = '/usr/apachesecure0/htdocs/srs/lps/lps_update_log_temp.txt';

		// create connect string
		$connectString = "dbname = $db user = $user host=$host";
		if ( isset( $port ) || $port != '' ) {
			$connectString .= " port=$port" ;
		}
		//echo $connectString."<BR>";
		// use it to connect to the database. Postgres-specific
		if (!$dbH = @pg_connect( $connectString ) ) {
			$errorId = ERROR_DB_CONNECT;
			echo ("Failed in constructor with error = $errorId");
			exit;
		}
		$this->request->dbH = $dbH;

		$this->timeString = strftime( "%Y-%m-%d %T", time() );
		$this->logBuffer = "********** $timeStringLog BEGIN   **********\n";

		$this->sourceFields = array(
				"address_street1",
				"address_street2",
				"address_city",
				"address_state",
				"address_zip",
				"dob",
				"email_address",
				"ethnic_group",
				"grade",
				"id_county",
				"id_district",
				"id_school",
				"name_first",
				"name_middle",
				"name_last",
				"phone",
				"primary_language",
				"ward",
				"ward_surrogate",
				"ward_surrogate_nn",
				"gender",
				"id_student_local",
				"change_type"
				);

		$this->extraInsertFields = array (
			array( "status", "Inactive"),
			array( "last_auto_update", $this->timeString ),
			array( "id_author_last_mod", 0 )
		);
		
		$this->extraUpdateFields = array (
			array( "last_auto_update", $this->timeString ),
			array( "id_author_last_mod", 0 )
		);

		//dp($this->sourceFields, "sourceFields");
		//dp($this->extraInsertFields, "extraInsertFields");
		//dp($this->extraUpdateFields, "extraUpdateFields");
		//return NOT_IMPLEMENTED;
	}
}


class DBUpdater_LPS_guardian extends DBUpdater {
	function DBUpdater_LPS_guardian() {
        $this->init(); // HANDLE ERRORS HERE!
	}
	
	// "abstract" base function, must be overridden by subclasses
	function init() {
		$db = "iep_shang";
		$user = "postgres";
		$host = "localhost";
		$port = '';
		$table = "iep_guardian";
		$pkey = array("id_guardian_local", "id_student_local");
		$fileName = "PARUPDT.TXT";

		// create DBRequest object
		$this->request = new DBRequest(''); // pass it a null query to start with
		$this->request->replaceNULL = false; // do not replace nulls
		$this->databaseTable = $table;
		$this->pKeyName = $pkey;
		$this->sourceFile = $this->toDir . $fileName;
		//echo "sourceFile: " . $this->sourceFile . "<BR>";
		$this->separator = ',';
		$this->abortAllonError = false;		// default to false
		//$this->logFile = '/usr/apachesecure0/htdocs/srs/lps/lps_update_log_temp.txt';

		// create connect string
		$connectString = "dbname = $db user = $user host=$host";
		if ( isset( $port ) || $port != '' ) {
			$connectString .= " port=$port" ;
		}
		//echo $connectString."<BR>";
		// use it to connect to the database. Postgres-specific
		if (!$dbH = @pg_connect( $connectString ) ) {
			$errorId = ERROR_DB_CONNECT;
			echo ("Failed in constructor with error = $errorId");
			exit;
		}
		$this->request->dbH = $dbH;

		$this->timeString = strftime( "%Y-%m-%d %T", time() );
		$this->logBuffer = "********** $timeStringLog BEGIN   **********\n";

		$this->sourceFields = array(
			"address_street1",
			"address_street2",
			"address_city",
			"address_state",
			"address_zip",
			"email_address",
			"id_guardian_local",
			"id_student_local",
			"name_first",
			"name_middle",
			"name_last",
			"phone_home",
			"phone_work",
			"change_type"
		);

		$this->extraInsertFields = array (
			array( "status", "Inactive"),
			array( "last_auto_update", $timeString ),
			array( "id_author_last_mod", 0 ),
			array( "id_student", "get_master_student_from_local(id_student_local)" )
		);

		
		$this->extraUpdateFields = array (
			array( "last_auto_update", $timeString ),
			array( "id_author_last_mod", 0 )
		);

		//dp($this->sourceFields, "sourceFields");
		//dp($this->extraInsertFields, "extraInsertFields");
		//dp($this->extraUpdateFields, "extraUpdateFields");
		//return NOT_IMPLEMENTED;
	}
}






$c = get_defined_constants();
//print_r($c);
if (0) {$new = new DBUpdater;
$new->fileRetrievalMethod = LOCAL_PATH_RETRIEVE;
$new->fromDir="/home/grandisland";
$new->toDir="/var/www/html/srs/grand_island";
$result = $new->getFiles( array("ferkel.txt", "lark.txt") );
echo "<br />result = $result";
if ($result == false ) {
    echo "<br />Errors = " . nl2br($new->fileErrors);
    exit;
} }
/*
$testCase = 1;
// TRYING TO BUILD A SWITCH ON ALL THE POSSIBLE ERRORS AND CASES
switch($testCase) {
	case 1: 
		echo "page top<BR />";
		$new = new DBUpdater_LPS_guardian;
		//
		// FTP SETTINGS
		$new->fileRetrievalMethod = FTP_RETRIEVE;
		$new->remoteHost = "agamemnon.fmpro.com";
		$new->remoteUser='slane';
		$new->remotePass='ithaka';
		$new->fromDir="/home/jlavere";
		$new->toDir="/usr/apache1327/htdocs/srs-dev/grand_island";
		// FTP GET
		$result = $new->getFiles( array("STUUPDT.TXT", "PARUPDT2.TXT") );
		if ($result == false ) {
			//$new->logBuffer .= "********** $timeStringLog END   **********";
			echo "<BR /><hr />" . str_replace ( "\n", "<BR>", $new->logBuffer)  . "<BR /><hr /><br />";
			//echo "<br />Errors = " . nl2br($new->fileErrors);
			exit;
		}
		//
		//$new->sourceFile = "/usr/apache1327/htdocs/srs-dev/";
		$new->processFile();
		
		echo "<BR /><hr />" . str_replace ( "\n", "<BR>", $new->logBuffer)  . "<BR /><hr /><br />";
		break;

	case 2:
		//
		echo "page top<BR />";
		$new = new DBUpdater_LPS_guardian;
		//
		// FTP SETTINGS
		$new->fileRetrievalMethod = FTP_RETRIEVE;
		$new->remoteHost = "agamemnon.fmpro.com";
		$new->remoteUser='slane';
		$new->remotePass='ithaka';
		$new->fromDir="/home/jlavere";
		$new->toDir="/usr/apache1327/htdocs/srs-dev/grand_island";
		// FTP GET
		$result = $new->getFiles( array("STUUPDT.TXT", "PARUPDT.TXT") );
		if ($result == false ) {
			//$new->logBuffer .= "********** $timeStringLog END   **********";
			echo "<BR /><hr />" . str_replace ( "\n", "<BR>", $new->logBuffer)  . "<BR /><hr /><br />";
			//echo "<br />Errors = " . nl2br($new->fileErrors);
			exit;
		}
		//
		//$new->sourceFile = "/usr/apache1327/htdocs/srs-dev/";
		$new->processFile();
		
		echo "<BR /><hr />" . str_replace ( "\n", "<BR>", $new->logBuffer)  . "<BR /><hr /><br />";
		break;
	case 3:
		$new = new DBUpdater_LPS_guardian;
		$new->fileRetrievalMethod = LOCAL_PATH_RETRIEVE;
		$new->fromDir="/home/jlavere";
		$new->toDir="/usr/apache1327/htdocs/srs-dev/grand_island";
		$result = $new->getFiles( array("STUUPDT.TXT", "PARUPDT.TXT") );
		echo "<br />result = $result";
		if ($result == false ) {
			echo "<br />Errors = " . nl2br($new->fileErrors);
			exit;
		}
		break;
}

*/