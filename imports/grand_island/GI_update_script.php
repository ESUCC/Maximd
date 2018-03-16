#!/usr/local/bin/php
<?php

// new sl 2003-04-22: ini directives to allow long exec time
// sl 2005-08-15 modified to talk to db on iepdata.unl.edu
// jl 2009-11-05 modified to talk to db on iepdatastage.nebraskacloud.org

ini_set("max_execution_time", 79200); // 24 hours
//ini_set("memory_limit", "128MB"); // got RAM to burn on the main machine so ...

/* somewhere along the way, prepend gets included
and it will kick out an error if it's set to trap for security */
$secureTempOff = true;


// *******************************************************************

// WHERE TO DOWNLOAD/MOVE THE FILES TO FOR PROCESSING
$toDir = "/var/www/html/srs/grand_island";

// THIS IS THE DATA THAT WILL BE PUT ON EACH INSERTED OR UPDATED RECORD IN THE DATA_SOURCE FIELD
$data_source_name = "GI";

// WHERE TO ARCHIVE THE FILES
$archiveLoc = "/var/www/html/srs/grand_island/uploads/archive";

$emailTo = "srshelp@esu1.org";
$emailCC = "tom@harvill.net,mshellha@hotmail.com, mdanahy@esucc.org, mshellha@esu10.org, pdobrovo@gips.org";


// *******************************************************************
	// INCLUDE THE DBUPDATER CLASS
	require_once('class_DBUpdaterNew.php');
	// CREATE THE INITIAL 
	$updateOBJ = new DBUpdater();


            
    // *******************************************************************
        // set up student and parent file args
        $_Passed_Options = $_SERVER[argv];
        $_Student_File = $_Passed_Options[1];
        $_Parent_File =  $_Passed_Options[2];
        
        if ( $_Parent_File == '' ||  $_Student_File == '' ) {
            $updateOBJ->addLog("Parent or student file name missing!\n");
            echo "\n" .  $updateOBJ->logBuffer . "\n";
            exit(1);
        }
        $fileListArr = array($_Student_File, $_Parent_File);
    
    
    // *******************************************************************
        // FTP SETTINGS
    
        $run='remote';
        if($run == 'remote') {
            $updateOBJ->fileRetrievalMethod = FTP_RETRIEVE;
            $updateOBJ->fromDir="/";
        } else {
            $updateOBJ->fileRetrievalMethod = LOCAL_PATH_RETRIEVE;
            $updateOBJ->fromDir="/var/www/html/srs/grand_island/uploads/archive";
        }
        
        $updateOBJ->remoteHost = "204.234.22.34"; //was 204.234.22.28
        $updateOBJ->remoteUser='anonymous';
        $updateOBJ->remotePass='slane@moyergroup.com';
        $updateOBJ->toDir = $toDir;
        
    // *******************************************************************
        // CREATE DBREQUEST OBJECT
        $updateOBJ->request = &new DBRequest(''); // pass it a null query to start with
        $updateOBJ->request->replaceNULL = false; // do not replace nulls
        $updateOBJ->separator = ',';
        $updateOBJ->abortAllonError = false;		// default to false
    // *******************************************************************
        // DB CONNECT SETTINGS
        // 20070810 jlavere - added HOST_ENVIRONMENT case at time of dev live merge
        if($HOST_ENVIRONMENT == "xanthos")
        {
            $updateOBJ->db = "iep_engine_rebuild";
            $updateOBJ->pass = "devpass";
            $updateOBJ->host = "xanthos.soliantconsulting.com";
            $updateOBJ->port = '';
        } else {
//             $updateOBJ->db = "iep_db";
//             $updateOBJ->host = "iepdatastage.nebraskacloud.org";
//             $updateOBJ->port = '5432';
            $updateOBJ->db = "nebraska_srs";
            $updateOBJ->host = "iepdatastage.nebraskacloud.org";
            $updateOBJ->port = '5434';
        }
//        $updateOBJ->user = "postgres";
        $updateOBJ->user = "psql-primary";
        // CONNECT TO THE DB
        if( !$updateOBJ->dbConnect() ) {
            $updateOBJ->addLog("Unable to connect to database\n");
            echo "\n" .  $updateOBJ->logBuffer . "\n";
            exit(1);
        }
    // *******************************************************************
        // LOG SETTINGS
        $updateOBJ->title = "GI Update Log";
        $updateOBJ->timeString = strftime( "%Y-%m-%d %T", time() );
        $logString = "********** GRAND ISLAND STUDENT UPDATE of " . $updateOBJ->timeString . " BEGIN   **********\n";
        $updateOBJ->addLog($logString);
        echo $logString;
    
    
    
    // *******************************************************************
    // FTP GET THE FILES
    // *******************************************************************
        $result = $updateOBJ->getFiles( $fileListArr );
        // sl added check for BAD_PARAM, which getFiles can also return
        if ( ($result === false) || ($result === BAD_PARAM ) ) {
            $updateOBJ->addLog("********** Whoops, either we got a bad result ($result) or a bad getFiles parameter (" . BAD_PARAM . ")   **********\n");
            echo "<BR /><hr />" . nl2br ( $updateOBJ->logBuffer)  . "<BR /><hr /><br />";
            exit(1);
        }
    
        
    $countOfDistricts = $updateOBJ->countOfMatchingDistrictImportCodes($data_source_name);    
    echo "countOfDistricts: $countOfDistricts\n";
    
    if($countOfDistricts < 7) {
        echo "updating seven known GI districts with import code\n";
        $districtList = array(  array(	'id_county' 		=> 	'47', 'id_district' 	=> 	'0100'),    //Centura Public Schools
								array(	'id_county' 		=> 	'40', 'id_district' 	=> 	'0002'),    //Grand Island Public Schools
								array(	'id_county' 		=> 	'40', 'id_district' 	=> 	'0083'),    //Wood River Rural Schools
                                array(	'id_county' 		=> 	'47', 'id_district' 	=> 	'0001'),    //St Paul Public Schools
								array(	'id_county' 		=> 	'61', 'id_district' 	=> 	'0004'),    //Central City Public Schools
								array(	'id_county' 		=> 	'40', 'id_district' 	=> 	'0082'),    //Northwest High School
								array(	'id_county' 		=> 	'61', 'id_district' 	=> 	'0049'),    //Palmer Public Schools
                             );
        $updateOBJ->updateDistrictImportCodes($districtList, $data_source_name);
        $countOfDistricts = $updateOBJ->countOfMatchingDistrictImportCodes($data_source_name);    
        echo "countOfDistricts: $countOfDistricts\n";
    }
    
    if($countOfDistricts == 7) {

        
        // *******************************************************************
        // PROCESS THE STUDENT FILE
        // *******************************************************************
            $updateOBJ->sourceFile = "$toDir/$_Student_File";
            $updateOBJ->databaseTable = "iep_student";
            $updateOBJ->pKeyName = array("id_student_local", "data_source"); // changed sl 2003-05-03
            $data_source_name = "GI";
            $updateOBJ->sourceFields = array(
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
                "gender",
                "id_student_local",
                "unique_id_state",
                "ell_student"
                );
            
            $updateOBJ->extraFields = array (
                array( "data_source", $data_source_name),
                array( "last_auto_update", $updateOBJ->timeString ),
                array( "id_author_last_mod", 0 )
            );
            
            $updateOBJ->extraInsertFields = array (
                array( "status", "Inactive")
            );
            
            //print_r($updateOBJ);
            //
            // PROCESS THE STUDENT FILE ASSOCIATED WITH THIS CLASS
            // *******************************************************************
            // *******************************************************************
            $updateOBJ->addLog("********** Begin processing student file **********");
            $updateOBJ->processFile();
            $updateOBJ->addLog("********** Done processing student file  ********** ");
            // *******************************************************************
            // *******************************************************************
        
        
        // *******************************************************************
        // PROCESS THE GUARDIAN FILE
        // *******************************************************************
            // RESET INSERT AND UPDATE COUNTS
            // LEAVE ERRORS AND LOG TO ACCUMULATE DATA
            $updateOBJ->insertCount = 0;
            $updateOBJ->updateCount = 0;
            //
            // UPDATE SETTINGS FOR PARENT FILE
            $updateOBJ->sourceFile = "$toDir/$_Parent_File";
            $updateOBJ->databaseTable = "iep_guardian";
            //$updateOBJ->databaseTable = "iep_gi_guardian_temp";
            $updateOBJ->pKeyName = array("id_guardian_local", "id_student_local", "data_source");
        
            $updateOBJ->sourceFields = array(
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
            );
            //
            $updateOBJ->extraFields = array (
                array( "data_source", $data_source_name),
                array( "last_auto_update", $updateOBJ->timeString ),
                array( "id_author_last_mod", 0 )
            );
            
            $updateOBJ->extraInsertFields = array (
                array( "status", "Inactive"),
                array( "id_student",  "get_master_student_from_local_w(id_student_local, '$data_source_name'::text)")
            );
            //
            // PROCESS THE GUARDIAN FILE ASSOCIATED WITH THIS CLASS
            // *******************************************************************
            // *******************************************************************
            $updateOBJ->addLog("********** Begin processing guardian file **********");
            $updateOBJ->processFile($parseGuardianID = true);
            $updateOBJ->addLog("********** Done processing guardian file  **********");
            // *******************************************************************
            // *******************************************************************
        
        
        
        
        
        
        
        // *******************************************************************
        // ARCHIVE THE FILES
        // *******************************************************************
            foreach($fileListArr as $fileToArchive) {
                // MOVE THE STUDENT FILE TO ARCHIVE LOCATION
                $studentFileArchive = str_replace(" ", "-", $updateOBJ->timeString) . "-" . $fileToArchive; // replace spaces with '-'
                $moveCommand1 = "mv $toDir/$fileToArchive $archiveLoc/$studentFileArchive";
                $moveCommand1 .= " 2>&1"; // must capture stderr! PHP system() calls only grab stdout
                //echo "move command = $moveCommand1<BR>";
                exec( $moveCommand1, $moveOutput1, $moveResult1 );
                //
                if ( $moveResult1 > 0 ) { 						// signals failure of move command
                    $updateOBJ->addLog("Moving file to archive location failed with result: $moveResult1");
                    $errorText = $moveOutput1[0];
                    $updateOBJ->addLog($errorText);
                }
            }
        //
        // *******************************************************************
            // it appears that get_guardians_main will convert null fields to non-null by inserting '' into them instead if no guardian records are found
            // so we change this update to check for existence of guardian records first 2003-02-24 sl/jl
            // hmm, this only updates student records if the student record itself was updated, not if just the parent was. sl 2003-11-13
            //$updateStudentStmt = "UPDATE iep_student set id_list_guardian = get_guardians(id_student) where last_auto_update='".$updateOBJ->timeString."' and exists (select 1 from iep_guardian g where g.id_student = iep_student.id_student )";
            
            // This is still not very correct, since it omits data source.
            /*$updateStudentStmt = "UPDATE iep_student set id_list_guardian = get_guardians(id_student) ";
            $updateStudentStmt .= "where (last_auto_update='". $updateOBJ->timeString ."' and exists (select 1 from iep_guardian g where g.id_student = iep_student.id_student ))";
            $updateStudentStmt .= " OR id_student_local IN (SELECT id_student_local from iep_guardian where last_auto_update ='" . $updateOBJ->timeString . "' )";*/
            
            //2003-11-16 better to just do them all
            
            $updateStudentStmt = "UPDATE iep_student set id_list_guardian = get_guardians(id_student) where data_source='GI'";
            echo "\nUpdate guardian list SQL = $updateStudentStmt\n";
            $updateReq = new DBRequest( $updateStudentStmt );
            $updateReq->dbH = $updateOBJ->request->dbH; // set this to the database connection we made in the above DBUpdater (updateOBJ)
            $result = $updateReq->sqlExec();
            $logString = "\nUpdated student guardian lists, error msg = " . $updateReq->errorMsg;
            $updateOBJ->addLog($logString);
            echo $logString;
            
            //2003-11-16 need to handle problem with zero-length vs null lists
            $updateStudentStmt = "UPDATE iep_student set id_list_guardian = null where length(id_list_guardian)=0";
            echo "\nUpdate zero-length guardian list SQL = $updateStudentStmt\n";
            $updateReq = new DBRequest( $updateStudentStmt );
            $updateReq->dbH = $updateOBJ->request->dbH; // set this to the database connection we made in the above DBUpdater (updateOBJ)
            $result = $updateReq->sqlExec();
            $logString = "\nUpdated zero-length guardian lists, error msg = " . $updateReq->errorMsg;
            $updateOBJ->addLog($logString);
            echo $logString;
            
            //
            // UPDATE USER_NAME
            //$updateStudentStmt = "UPDATE iep_guardian set user_name = new_user_name(name_first, name_last, 'iep_guardian') where user_name is null AND last_auto_update='".$updateOBJ->timeString."'";
            // sl 2003-05-18 made this a blanket update just to cast the net wider, removed the restriction to this particular data load
            $updateStudentStmt = "UPDATE iep_guardian set user_name = new_user_name(name_first, name_last, 'iep_guardian') where user_name is null";
            $updateReq->sqlStmt = $updateStudentStmt;
            $result = $updateReq->sqlExec();
            $updateOBJ->addLog("Updating user name, error msg = " . $updateReq->errorMsg);
            $updateReq->errorMsg = '';
            //
            // UPDATE PASSWORD
            //$updateStudentStmt = "UPDATE iep_guardian set password = new_password(7, 1) where password is null AND last_auto_update='".$updateOBJ->timeString."'";
            // sl 2003-05-18 made this a blanket update just to cast the net wider, removed the restriction to this particular data load	
            $updateStudentStmt = "UPDATE iep_guardian set password = new_password(7, 1) where password is null";
            $updateReq->sqlStmt = $updateStudentStmt;
            $result = $updateReq->sqlExec();
            $updateOBJ->addLog("Updating passwords, error msg = " . $updateReq->errorMsg);
            $updateReq->errorMsg = '';
            //
            // changed criteria to length=0 because get_guardians_main converts id_list_guardian to zero-length non-null if no guardians found sl/jl 2003-02-24
            // 2003-11-16 not sure why this is even still here
            /*$updateStudentStmt = "UPDATE iep_student set id_list_guardian=get_guardians(id_student) where id_student_local is not null and (length(id_list_guardian) = 0 or id_list_guardian is null)";
            $updateReq->sqlStmt = $updateStudentStmt;
            $result = $updateReq->sqlExec();
            $updateOBJ->addLog("Updating all student guardian lists, error msg = " . $updateReq->errorMsg);
            $updateReq->errorMsg = '';*/
            
            // clean up GI gender formats
            $updateStudentStmt = "UPDATE iep_student set gender='Female' where gender='F';";
            $updateReq->sqlStmt = $updateStudentStmt;
            $result = $updateReq->sqlExec();
            $updateOBJ->addLog("\nUpdating gender  female, error msg = " . $updateReq->errorMsg);
            $updateReq->errorMsg = '';
        
            // clean up GRADES FOR EI 02 STUDENTS
            $updateStudentStmt = "UPDATE iep_student set grade='EI 0-2' where grade='B2';";
            $updateReq->sqlStmt = $updateStudentStmt;
            $result = $updateReq->sqlExec();
            $updateOBJ->addLog("\nUpdating grade B2 to EI 0-2, error msg = " . $updateReq->errorMsg);
            $updateReq->errorMsg = '';
            // SPECIAL EXTRA CASE BECAUSE GI IS SENDING OVER O-2
            $updateStudentStmt = "UPDATE iep_student set grade='EI 0-2' where grade='EI O-2';";
            $updateReq->sqlStmt = $updateStudentStmt;
            $result = $updateReq->sqlExec();
            $updateOBJ->addLog("\nUpdating grade EI O-2 to EI 0-2, error msg = " . $updateReq->errorMsg);
            $updateReq->errorMsg = '';
        
            
            $updateStudentStmt = "UPDATE iep_student set gender='Male' where gender='M';";
            $updateReq->sqlStmt = $updateStudentStmt;
            $result = $updateReq->sqlExec();
            $updateOBJ->addLog("\nUpdating gender male, error msg = " . $updateReq->errorMsg);
            $updateReq->errorMsg = '';
            
            $logString = "********** GRAND ISLAND STUDENT UPDATE of " . $updateOBJ->timeString . " COMPLETE!   **********\n";
            $updateOBJ->addLog($logString);
        // *******************************************************************
        //
        // EMAIL THE RESULTS
        //		
        $subject = "GI Update Log";
        
        
            $logString = "Sending confirmation email to: $emailTo and $emailCC";
            $headers = "Cc: $emailCC\r\n";
            $updateOBJ->addLog($logString);
            $result = mail( $emailTo, $subject, $updateOBJ->logBuffer, $headers);
            $logString = "Mail result = $result";
            $updateOBJ->addLog($logString);
        
        
        
        echo $updateOBJ->logBuffer;


    } else {
        
        $logString = "The District code was not found in all seven Grand Island districts. Please enter the district import code GI and run the import again.";
        $headers = "Cc: $emailCC\r\n";
        $updateOBJ->addLog($logString);
        $result = mail( $emailTo, $subject, $logString, $headers);
        $logString = "Mail result = $result";
        $updateOBJ->addLog($logString);
        
    }
