#!/usr/local/bin/php
<?php
/**
 * This is a php file called by cron to build nssrs files 
 * for all districts that have the nssrs_send_tonight flag set
 *
 * file is run nightly with cron and the following commmand
 * /usr/local/bin/php -c /opt/backup/scripts/php.ini /usr/apachesecure0/htdocs/srs/cron_scripts/build_nssrs_exports.php
 * /usr/local/bin/php -c /opt/backup/scripts/php.ini /var/www/srs/cron_scripts/build_nssrs_exports.php
 */

$onLive = true;
$onXanthos = !$onLive;
if($onXanthos == true) {
    $path = '/var/www/srs';
    set_include_path(get_include_path() . PATH_SEPARATOR . $path);
    $path = '/var/www/srs/lib';
    set_include_path(get_include_path() . PATH_SEPARATOR . $path);

    $nssrsDir = "/var/www/srs/nssrs_files/";

    $DB_NAME        = "iep_engine_rebuild";
    $DB_USER_NAME   = "postgres";
    $DB_HOST		= "localhost";
    $DB_PORT        = "";
    $DB_PASSWORD    = "devpass";
} else {
    $nssrsDir = "/var/www/html/srs/nssrs_files/";

//     $DB_NAME        = "iep_db";
//     $DB_USER_NAME   = "postgres";
//     $DB_HOST		= "iepdata01.esucc.org";
//     $DB_PORT        = "5432";

    $DB_NAME        = "nebraska_srs";
    $DB_USER_NAME   = "psql-primary";
    $DB_HOST		= "iepdata01.esucc.org";
    $DB_PORT        = "5434";

}


/**
 * function general is needed for sqlExec
 * pear and mail classes used for emails/attachments
 */
require_once("iep_function_general.inc");
require_once("PEAR.php");
require_once('Mail.php');      // These two files are part of Pear,
require_once('Mail/mime.php'); // and are required for the Mail_Mime class        


require_once('iep_nssrs_collection.inc');

/**
 * db user and pass
 * establish a persistent connection to the db
 */
// establish persistent db connection
$passCode = isset($DB_PASSWORD)?"password=$DB_PASSWORD":"";
$portCode = isset($DB_PORT)?"port=$DB_PORT":"";
$connString = "dbname = $DB_NAME user = $DB_USER_NAME host=$DB_HOST $passCode $portCode";
if (!$dbH = @pg_connect($connString)) {
    echo "errorID: " . $errorID . "\n";
    echo "errorMsg: " . $errorMsg . "\n\n";
    exit;
}

ini_set("memory_limit", "64M");

/**
 * GET DISTRICTS
 * select all districts that use nssrs, have an email and have the flag set for building
 */
$getDistrictsToBuild = "select id_county, id_district, email_nssrs, get_name_county(id_county) as county_name, 
                get_name_district(id_county, id_district) as district_name 
                from iep_district where use_nssrs = 't' and email_nssrs is not null and nssrs_send_tonight = 't';";
if(!$result = sqlExec($getDistrictsToBuild, $errorID = "", $errorMsg = "")) {
    echo "errorID: " . $errorID . "\n";
    echo "errorMsg: " . $errorMsg . "\n\n";
    echo "Probably no records flagged\n";
} else {
    $rows = pg_numrows($result); // will always be positive
    /**
     * BUILD THE FILES
     */
    for($rowNum = 0; $rowNum < $rows; $rowNum++) {			
        $ad = pg_fetch_array($result, ($rowNum), PGSQL_ASSOC);

        /**
         * FILENAME USED TO CREATE AND DOWNLOAD FILE
         */
        //$downloadname = $ad['id_county']."_".$ad['id_district']."_NSSRS.txt";
        $downloadname = $ad['id_county']."-".$ad['id_district']."_special_ed_snap_".date("Ymdhi").".csv";
        $nssrsFilename = $nssrsDir . $downloadname;

        $collectObj = new nssrs_collection($ad['id_county'], $ad['id_district'], $nssrsFilename, true);

        /**
         * EMAIL FILE
         */
        $dumpCommand = "cat $nssrsFilename";
        $logContent = `$dumpCommand`;

        
        $subject = "{$ad['county_name']} - {$ad['district_name']} NSSRS FILE " . date_massage("yesterday");
        $from = "nssrs@iep.esucc.org";
        $to = $ad['email_nssrs']; //'mdanahy@esucc.org';

        #$result = mail( $to, $subject, $logContent);


        $headers = array('From'    => $from, 'Subject' => $subject); 
        $mime = new Mail_Mime();
            // create a new instance of the Mail_Mime class

        if(isset($logContent)) {
            $mime->setTxtBody("NSSRS file attached.");
                // set our plaintext message
            //$mime->setHtmlBody($htmlMessage);
                // set our HTML message
            $mime->addAttachment($nssrsFilename);
                // attach the file to the email
        } else {
            $mime->setTxtBody("NSSRS process ran but no complete students were found.");
        }
        
    
        $body = $mime->get();
            // This builds the email message and returns it to $body.
            // This should only be called after all the text, html and
            // attachments are set.
    
        $hdrs = $mime->headers($headers);
            // This builds the corresponding headers for the plaintext,
            // HTML and any other required headers. It also includes
            // the headers we created earlier by passing them as an argument.
    
        $mail = &Mail::factory('mail');
            // Creates an instance of the mail backend that we can use
            // to send the email.
    
        $mail->send($to, $hdrs, $body);
            // Send our email, according to the address in $to, the email
            // headers in $hdrs, and the message body in $body. 


        $getDistrictsToBuild = "UPDATE iep_district SET nssrs_send_tonight = NULL where nssrs_send_tonight is not null and id_county = '".$ad['id_county']."' and id_district = '".$ad['id_district']."';";
        if(!$updateresult = sqlExec($getDistrictsToBuild, $errorID = "", $errorMsg = "")) {
            echo "errorID: " . $errorID . "\n";
            echo "errorMsg: " . $errorMsg . "\n\n";
        }


    }
    
    /**
     * UPDATE DISTRICTS
     * clear all flags
     */
//     $getDistrictsToBuild = "UPDATE iep_district SET nssrs_send_tonight = NULL where nssrs_send_tonight is not null;";
//     if(!$result = sqlExec($getDistrictsToBuild, $errorID = "", $errorMsg = "")) {
//         echo "errorID: " . $errorID . "\n";
//         echo "errorMsg: " . $errorMsg . "\n\n";
//     }

}
?>
