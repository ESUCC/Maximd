<?php

/**
 *
 * Handles HTTP API calls to EdFi
 *
 * @author odiaz@doublelinepartners.com
 * @version 1.0
 *
 */

class Model_EdfiOds
{

   function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }

    public function receiveFromOdsController($id_county,$id_district){

     // $id_student
        /*Al these are on /models/ */
	 /*   include("EdfiSync.php");
	    include("EdfiClient.php");
        include("EdfiJsonModels.php");
      */
      //  $this->writevar1("",'Running for student ' . $id_student . "@" . $id_county . ":" . $id_district);

       // $id_county='25';
       // $id_district='5901';
      //  $keyedfi='B25B1648BF20';
     //   $secret='E83D35F66F1945E9';

     //   $secret = '68EB93F573E94C00';
    //    $keyedfi = '5E3CA3F25BE5';

    //    $keyedfi='6BA1704F69654457';
    //    $secret='9138D4CA4CFE';

        /*
         *
         *
* SRS DISTRICT = 79-0011 (Morrill)


SRS DISTRICT = 87-0017 (Winnebago)

         *
         */


        /*
         * this is new as of Oct 3rd.  Mike did this so that we can retrieve secret and key from
         * the db and attached each district to the ods. That way we are not putting data on the
         * ods that does not belong.
         */
        $getSecretKey= new Model_Table_IepDistrict();
        $secretKey=$getSecretKey->getEdfiSecretKey($id_county,$id_district);
        $keyedfi=$secretKey['edfi_key'];
        $secret=$secretKey['edfi_secret'];



        /*Demo data remove on valid  $edfiDistrictarray param
         *  // array($id_district,$id_county,"Ivclm8boYPBH", "i677vvbvE6VpRW6EQBtL3eiK")

	      //  array($id_district,$id_county,"7gRhbgUz4zrq", "K1ZdbInI8EODEvScB3dxbJF8")

	      /*
	        array($id_district,$id_county,"g3uiYKK0Pros","bjRB3D3ahbsV33YgXxApZLyG")
	        This was last to work in the sandbox 9-14-2017
	      */
	    // array($id_district,$id_county,$secret,$keyedfi)

	    $edfiDistrictarray=array( array($id_district,$id_county,$keyedfi,$secret));



    //:q!    $this->writevar1("",'!!!!! USING DEMO DATA REMOVE  LINES 34-36 EdfiODSController !!!!!');


     //   $this->writevar1($districtStudents,' districts for sync process');

        /*Get config parameters from Zend*/

	    /*
	     * Not written by Mike
	     */
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $db2 = $config->getOption('db2');


        $host = $db2['params']['host'];
	    $user = $db2['params']['username'];
	    $pass =  $db2['params']['password'];
	    $db = $db2['params']['dbname'];
	    $port = $db2['params']['port'];

        /*
         * Parameters can be set manually for CRON call
        $host = "205.202.242.121";
	    $user = "psql-primary";
	    $pass = "sddsdsd";
	    $db = "nebraska_srs";
	    $port = "5434";
	   */

        /*Connection string for database*/
        $connstr = "host=" . $host . " port=" . $port . " dbname=" . $db . " user=" . $user . " password=" . $pass;

        /*Edfi API base URL*/

        // base url for sandbox removed 9-14-2017
      //  $APIUrl="https://sandbox.nebraskacloud.org/ng/api/";

        // base url for staging added 9-14-2017
        // NOte:try both of these to see if we still get errors.
        // Unfortunately can't see json data on ods. 10-9-2017


        //$APIUrl="https://adviserods.nebraskacloud.org/api/api/v2.0/";

        $APIUrl="https://adviserods.nebraskacloud.org/api";

        $sync= new Model_EdfiSync();
	    $sync->set_DbConnString($connstr);
	    $sync->set_APIUrl($APIUrl);
	    $sync->receiveEdfiSync($edfiDistrictarray,$APIUrl);

    }



}



?>
