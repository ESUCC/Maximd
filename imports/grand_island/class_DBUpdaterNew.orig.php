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

	//var	$databaseName;
	//var $databaseUser;
	//var $databaseHost;
	//var $databasePort;
	var $request;			// the DBRequest object
	
	var $databaseTable;
	var $pKeyName;			// name of primary key in above table. This can be a string or an array of strings
	var $sourceFile;		// the source text file for the update
	var $sourceFields;		// an array of the fields contained in the source file
	var $extraInsertFields;		// array of additional fields that will need to be inserted into (not relevant for update or delete)
	var $extraUpdateFields;		// array of additional fields that will need to be updated into (not relevant for insert or delete)
	
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
	
	var	$insertCount = 0;
	var	$updateCount = 0;
	var	$errorCount = 0;
	var $currentLine = 0;
	
	function DBUpdater() {
        $this->init(); // HANDLE ERRORS HERE!
	}
	
	// "abstract" base function, must be overridden by subclasses
	function init() {
        return NOT_IMPLEMENTED;
	}
	
	// takes an array of file names. all retrieval parameters have to be correctly set for this to work.
	function getFiles ( $fileNameArray ) {
	    // right now we assume all the parameters are validly set
	    // this is obviously dangerous
	    if ( $this->fileRetrievalMethod == LOCAL_PATH_RETRIEVE ) {
	        return $this->getLocalFiles( $fileNameArray );
	    } elseif ( $this->fileRetrievalMethod == FTP_RETRIEVE ) {
	        return $this->getFTPFiles( $fileNameArray );
	    } else { // unknown retrieval method
	        return BAD_PARAM;
	    }
	}
	
	// get files from a local path. Returns true if all files moved successfully, false if some moves fail, or BAD_PARAM
	function getLocalFiles ( $fileNameArray ) {
	    if ( $this->fromDir == '' || $this->toDir == '' ) {
	        return BAD_PARAM;
	    }
	    $overallResult = true;
	    $this->fileErrors='';
	    foreach ( $fileNameArray as $currentFileName ) {
	        $startPath = ($this->fromDir) . "/$currentFileName";
	        $moveOutput = array();
	        $moveResult = null; // initialize
	        $command = "mv $startPath " . $this->toDir;
            $command .= " 2>&1"; // must capture stderr! PHP system() calls only grab stdout
	        exec( $command, $moveOutput, $moveResult );
	        //echo "move result = $moveResult<br />";
	        //echo "move command = $command<br />";
	        //print_r($moveOutput);
	        if ( $moveResult > 0 ) { // signals failure of move command
                $errorText = $moveOutput[0] . "\n";
                $this->fileErrors .= $errorText;
                $overallResult = false;                
	        } else {
                $overallResult = true;
            }
	    }
	    return $overallResult;
	}
	
	// get files from a local path. Returns true if all files moved successfully, false if some moves fail, or BAD_PARAM
	function getFTPFiles ( $fileNameArray ) {
	    if ( $this->fromDir == '' || $this->toDir == '' || $this->remoteHost == '' || $this->remoteUser == '' ) {
	        return BAD_PARAM;
	    }
	    $overallResult = true;
	    $this->fileErrors='';
	    $curlPath = `which curl 2>&1`;
	    echo "curlPath = $curlPath";
	    if ( strstr($curlPath, "no curl in") != false ) {
	        $this->fileErrors .= "cURL command not found in path\n";
	        return false;
	    } exit;
	    foreach ( $fileNameArray as $currentFileName ) {
	        $startPath = ($this->fromDir) . "/$currentFileName";
	        $moveOutput = array();
	        $moveResult = null; // initialize
	        $command = "mv $startPath " . $this->toDir;
            $command .= " 2>&1"; // must capture stderr! PHP system() calls only grab stdout
	        exec( $command, $moveOutput, $moveResult );
	        //echo "move result = $moveResult<br />";
	        //echo "move command = $command<br />";
	        //print_r($moveOutput);
	        if ( $moveResult > 0 ) { // signals failure of move command
                $errorText = $moveOutput[0] . "\n";
                $this->fileErrors .= $errorText;
                $overallResult = false;                
	        } else {
                $overallResult = true;
            }
	    }
	    return $overallResult;
	}
	
	
	// basic logging interface to put error messages somewhere
	function writeLog( $message ) {
		fwrite ( $this->logFP, $message . "\n");
		return;
	}
	
	// assuming the source file has been opened successfully, process a line of it
	function processLine ( $lineBuf ) {
		if ( $lineBuf =='' ) {
			return;
		}
		$this->currentLine++;
		echo("Processing line " . $this->currentLine . "\n");
		$massagedLine = $this->preprocessLine( $lineBuf );
		//$this->writeLog ( $massagedLine );
		//print_r( $bufArray ); exit;
		$bufArray = explode( $this->separator, $massagedLine );
		$dataFieldCount = count( $bufArray );
		if ( $dataFieldCount != count( $this->sourceFields ) ) {
			echo( "<br />Incorrect number of fields: source has " . count($this->sourceFields) . ", but line has $dataFieldCount\n");
			$this->errorCount++;
			$this->writeLog( $this->currentLine . ": ERROR: Incorrect number of fields");
			$this->writeLog ( $massagedLine );
		}
		
		$dataArray = array();
		$i = 0;
		while( $i < $dataFieldCount ) {
			if ( $this->sourceFields[$i] == 'change_type') {
				$changeType = strtolower( trim( $bufArray[$i] ) );		
			} else {
				$dataArray[ $this->sourceFields[$i]] = str_replace( '\044', ',', $bufArray[$i]); // un-escape any escaped commas in the fields	
			}
			$i++;
		}
		
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
		
		$unique = $this->checkUniqueness($dataArray);
		//$this->writeLog( "uniqueness check = $unique");
		if ( $unique == -1 ) { // some error checking for uniqueness
			$this->writeLog( $this->currentLine . ": Error checking record for uniqueness");
			return;
		} elseif ( $unique == 0 ) {
			$changeType = 'update'; // if the record is already there, force it to an update
		} elseif ( $unique == 1 ) {
			$changeType = 'create'; // if the record isn't there, force it to an insert
		}
		
		if ( $changeType == 'create' ) {
			$sqlStmt = $this->buildInsertStmt( $dataArray );
		} elseif ( $changeType == 'update' ) { 
			$sqlStmt = $this->buildUpdateStmt( $dataArray );
		}
		//echo $sqlStmt;
		
		$this->request->sqlStmt = $sqlStmt;
		$this->request->sqlExec();
		if ( $this->request->errorId == ERROR_NO_ERROR ) {
			if ( $changeType == 'create' ) {
				$this->insertCount++;
			} elseif ( $changeType == 'update' ) { 
				$this->updateCount++;
			}
			//$this->writeLog($sqlStmt);
		} else { // got some sql error
			$this->writeLog( $this->currentLine . ": " . $this->request->errorMsg );
			$this->errorCount++;
			//$this->writeLog( "ERROR: " . $this->request->errorMsg );
			$this->writeLog("\n");
		}
		//$this->writeLog("\n");
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
				$paramArray[$key] = $dataArray[$value];
			}
			$newParams = implode( ",",  $paramArray );
			$ret =  "$name( $newParams )";
			//echo "<br />$ret<br />";
			return $ret;
		}
	}
	
	// process the source file line by line
	function processFile() {
		//echo $this->logFile;
		if ( !$this->logFP = fopen( $this->logFile, 'a+' ) ) {
			$this->writeLog ("error opening log file");
			echo ("error opening log file");
			exit;
		}
		if ( !$fp = fopen( $this->sourceFile, 'r' ) ) {
			$this->writeLog ("error opening source file");
			exit;
		}
		while ( !feof( $fp ) ) {
			$lineBuf = fgets( $fp, 10000 );  // need max length in PHP < 4.2.0
			$this->processLine( $lineBuf );
		}
		$summaryString = "Inserts: " . $this->insertCount . ", Updates: " . $this->updateCount . ", Errors: " . $this->errorCount ;
		$this->writeLog( $summaryString . "\n");
		echo $summaryString;
		fclose( $this->logFP );
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
		return $output;
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
			reset( $this->pKeyName );
			while (list($index, $keyName) = each( $this->pKeyName )) {
				//echo "got key<br />";
				if ( $j++ ) { $sqlStmt .= " AND "; }
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
			reset( $this->pKeyName );
			while (list($index, $keyName) = each( $this->pKeyName )) {
				//echo "got key<br />";
				if ( $j++ ) { $sqlStmt .= " AND "; }
				$sqlStmt .= "$keyName='" . addslashes($dataArray[ $keyName ]) . "'";
			}
		}
		//$this->writeLog( "stmt = $sqlStmt");
		$this->request->errorNoResults = false; // no results is fine
		$this->request->sqlStmt = $sqlStmt;
		$this->request->sqlExec();
		if ( $this->request->errorId == ERROR_NO_ERROR ) {
			//$this->writeLog( "got row count of " . $this->rows );
			if ( $this->request->rows > 0 ) {
				return 0;
			} else {
				return 1;
			}
		} else { // got some sql error
			echo("error = " . $this->request->errorId);
			return -1;
		}
	}
}

$c = get_defined_constants();
//print_r($c);
if (0) {$new = new DBUpdater;
$new->fileRetrievalMethod = LOCAL_PATH_RETRIEVE;
$new->fromDir="/home/grandisland";
$new->toDir="/usr/apachesecure0/htdocs/srs/grand_island";
$result = $new->getFiles( array("ferkel.txt", "lark.txt") );
echo "<br />result = $result";
if ($result == false ) {
    echo "<br />Errors = " . nl2br($new->fileErrors);
    exit;
} }
$new = new DBUpdater;
$new->remoteHost = "agamemnon.fmpro.com";
$new->remoteUser='slane';
$new->remotePass='ithaka';
$new->fileRetrievalMethod = FTP_RETRIEVE;
$new->fromDir="/home/slane";
$new->toDir="/usr/apachesecure0/htdocs/srs/grand_island";
$result = $new->getFiles( array("ferkel.txt", "lark.txt") );
if ($result == false ) {
    echo "<br />Errors = " . nl2br($new->fileErrors);
    exit;
}