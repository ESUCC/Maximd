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
        
        
        /*Demo data remove on valid  $edfiDistrictarray param*/
	    $edfiDistrictarray=array(
		   // array($id_district,$id_county,"Ivclm8boYPBH", "i677vvbvE6VpRW6EQBtL3eiK")
		    
	      //  array($id_district,$id_county,"7gRhbgUz4zrq", "K1ZdbInI8EODEvScB3dxbJF8")
	         array($id_district,$id_county,"g3uiYKK0Pros","bjRB3D3ahbsV33YgXxApZLyG")
	    );

	  // $this->writevar1($edfiDistrictarray,'this is the district array');
    //:q!    $this->writevar1("",'!!!!! USING DEMO DATA REMOVE  LINES 34-36 EdfiODSController !!!!!');
         
         
     //   $this->writevar1($districtStudents,' districts for sync process');

        /*Get config parameters from Zend*/
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
        $APIUrl="https://sandbox.nebraskacloud.org/ng/api/";

       // $this->writevar1($connstr,' DB connection');
      //  $this->writevar1($APIUrl,' Edfi API Url');
     //   $this->writevar1($edfiDistrictarray,'this is the edfi district array line 32');
	    $sync= new Model_EdfiSync();
	    $sync->set_DbConnString($connstr);
	    $sync->set_APIUrl($APIUrl);
	    $sync->receiveEdfiSync($edfiDistrictarray,$APIUrl);

    } 

	

} 

  

?>