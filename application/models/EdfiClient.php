<?php

/**
 *
 * Handles HTTP API calls to EdFi
 *
 * @author odiaz@doublelinepartners.com
 * @version 1.0
 *
 */

class Model_EdfiClient  {

    public function writevar1($var1,$var2) {
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }


    /*Holds current toke for reques*/
    var $currentToken = "";
    /*Points to EdFi URL*/
    var $currentAPIUrl = "";
    /*Current studen information*/
    var $currentStudent;

    function __construct() {
        /**/
	}


/*Gets authorization code for client*/
function getAuthCode($edfiBaseUrl, $edfiClientId){

	$edfiApiCodeUrl = "$edfiBaseUrl/oauth/authorize";
	$data = "Client_id=$edfiClientId&Response_type=code";
	$urlWithData = "$edfiApiCodeUrl?$data";

  //  $this->writevar1("Request to ",$edfiApiCodeUrl);

    try
    {

        $curl = curl_init();

	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_URL, $edfiApiCodeUrl);
	    curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	    $result = curl_exec($curl);
	    $jsonResult = json_decode($result);
	    curl_close($curl);

	    return $jsonResult->code;
    }
    catch(Exception $e) {
    // $this->writevar1("", 'Message: ' .$e->getMessage());
        return "";
    }
}

 /*Get the acceso token*/
 function getAuthToken($edfiBaseUrl,$edfiClientId,$edfiClientSecret,$authCode){

	$edfiApiTokenUrl = "$edfiBaseUrl/oauth/token";
	$paramsToPost = "Client_id=$edfiClientId&Client_secret=$edfiClientSecret&Code=$authCode&Grant_type=authorization_code";
	//$this->writevar1($paramsToPost,'parameters to post');
	//$this->writevar1($edfiApiTokenUrl,'edfi token url');

      try
    {
       $curl = curl_init();

	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_URL, "$edfiApiTokenUrl");
	    curl_setopt($curl, CURLOPT_POST, 1);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $paramsToPost);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	    $result = curl_exec($curl);

	    /*$this->writevar1($result,'this is the curl');
	     * this is what it looks like
	     * this is the curl
          string(109) "{
           "access_token": "de5199b992d34cb3aa575b7cc8cd3ede",
           "expires_in": 18599,
           "token_type": "bearer"
            }"
	    */
	    $jsonResult = json_decode($result);
	  //  $this->writevar1($jsonResult,'this is the json result for the token');
	    /*
	     * This is what the writevar looks like
	     * this is the json result for the token
           object(stdClass)#1674 (3) {
             ["access_token"]=>
             string(32) "de5199b992d34cb3aa575b7cc8cd3ede"
             ["expires_in"]=>
             int(18599)
             ["token_type"]=>
             string(6) "bearer"
            }

	     */
	    curl_close($curl);

	    return $jsonResult->access_token;
    }
    catch(Exception $e) {
     //   $this->writevar1("", 'Message: ' .$e->getMessage());
        return "";
    }


 }

 /*Authenticate and returns barier token or empty string if an error occurs */
 function edfiApiAuthenticate($edfiBaseUrl,$edfiClientId,$edfiClientSecret){
    $this->currentToken="";

  //  $this->writevar1($edfiBaseUrl,'the base url');
    try {
         $authCode = $this->getAuthCode($edfiBaseUrl, $edfiClientId);
         if($authCode!=""){

            $this->currentAPIUrl = $edfiBaseUrl;
            $this->currentToken = $this->getAuthToken($edfiBaseUrl,$edfiClientId,$edfiClientSecret,$authCode);
         }
    }
    catch(Exception $e) {
  //   $this->writevar1("", 'Message: ' .$e->getMessage());
    }
  // $this->writevar1($this->currentToken,'current token line 114');
    return $this->currentToken;
 }


/*                Student                 */
/*========================================*/

/*Check if a student exists and if so keeps response in cache variable
 * This actually checks on the edfi site.
 */

function studentExists($id_student){

	$authorization = "Authorization: Bearer " . $this->currentToken;
    $url = $this->currentAPIUrl . "/api/v2.0/2018/students?studentUniqueId=" . $id_student;

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
	curl_setopt($curl, CURLOPT_URL, "$url");

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec($curl);

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);
   // $this->writevar1($httpCode,'this is hte http code');



   // Mike added this 11-15-2017 because it was not taking into account that the student does not exists in
   // the SIS .IT was not changing the db to show an E in the edfipublishstatus
    if ($httpCode=='404') return '404';



    if($httpCode == 200) {
        $this->currentStudent=$result;

      // Pull this from edfi

        return true;
    } else {
        return false;
    }

}

/*Updates Student info*/
function updateCurrentStudent(){
    $eresponse = new edfi_response();

    $jsonResult = json_decode($this->currentStudent);


    $id_student = $jsonResult->id;
    $data_string = $this->currentStudent;
	$authorization = "Authorization: Bearer " . $this->currentToken;
    $url = $this->currentAPIUrl . "/api/v2.0/2018/students/" . $id_student;
	$curl = curl_init();
	$payloadLength = 'Content-Length: ' . strlen($data_string);




	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization , $payloadLength ));
	curl_setopt($curl, CURLOPT_URL, "$url");
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

	$result = curl_exec($curl);

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    $eresponse->set_resultCode($httpCode, $result);
    return $eresponse;

}

/*Updates Student Special Education Program*/
function updateStudentSpecialEducationProgramAssociation($data){
    $eresponse = new edfi_response();


    $jsonResult = json_decode($this->currentStudent);
   // $this->writevar1($data,'this is the data result');

    $id_student = $jsonResult->id;
	$authorization = "Authorization: Bearer " . $this->currentToken;
    $url = $this->currentAPIUrl . "/api/v2.0/2018/studentSpecialEducationProgramAssociations";
	$curl = curl_init();
	$payloadLength = 'Content-Length: ' . strlen($data);

  //  $this->writevar1("","Preparing to post  " . $id_student . " token=" . $this->currentToken . " opx=" );

//	$this->writevar1("","..................................................");
 // $this->writevar1("Student data to upload", $data);
//	$this->writevar1("", "..................................................");

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization , $payloadLength ));
	curl_setopt($curl, CURLOPT_URL, "$url");
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	$result = curl_exec($curl);

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

  //  $this->writevar1("",  $httpCode . " < return HTTP CODE");

    $eresponse->set_resultCode($httpCode, $result);

    return $eresponse;

}

/*========================================*/


}


/*Simplifies notification to clinte class*/
class edfi_response{

    /**/
    var $resultCode = "0";
    var $errorMessage ="";
    var $publishStatus="";



    function set_resultCode($resultCode, $result){

		$this->resultCode=$resultCode;
	//	$this->writevar1($resultCode,'this is the result code');
        $jsonResult = json_decode($result);

    switch ($resultCode) {
        case 200:
        case 201:
        case 202:
        case 204:
            $this->publishStatus="S";
            break;

        case 401:
            $this->publishStatus="";
            $this->errorMessage=$jsonResult->message;
            break;

        case 400:
            /* Error */
            $this->publishStatus="E";
            $this->errorMessage=$jsonResult->message;
            break;

        case 403:
            /* Error */
            $this->publishStatus="E";
            $this->errorMessage=$jsonResult->message;
            break;

        case 404:
            /* Error */
            $this->publishStatus="E";
            $this->errorMessage=$jsonResult->message;
            break;

        case 409:
            /* Inconsistent state */
            $this->publishStatus="E";
            $this->errorMessage=$jsonResult->message;
            break;

        case 412:
            /* Inconsistent state */
            $this->publishStatus="E";
            $this->errorMessage=$jsonResult->message;
            break;

        case 500:
            /* Error */
             $this->publishStatus="E";
             $this->errorMessage=$jsonResult->message;;
            break;

        }

	}

    function set_errorMessage($errorMessage){
		$this->errorMessage=$errorMessage;
	}

    function set_publishStatus($publishStatus){
		$this->publishStatus=$publishStatus;
	}

   function get_resultCode(){
		return $this->resultCode;
	}

    function get_errorMessage(){
		return $this->errorMessage;
	}

    function get_publishStatus(){
		return $this->publishStatus;
	}

}

?>
