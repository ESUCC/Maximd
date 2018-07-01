<?php 
require_once 'Zend/Loader.php';

class JsonController extends Zend_Controller_Action
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
    
 function indexAction() {
    
  
      
  $jsn='{
    
    "educationOrganizationReference": {
      "educationOrganizationId": 255901,
      "link": {
        "rel": "EducationOrganization",
        "href": "/educationOrganizations?educationOrganizationId=255901"
      }
    },
    "programReference": {
      "educationOrganizationId": 255901,
      "type": "Special Education",
      "name": "Special Education",
      "link": {
        "rel": "Program",
        "href": "/programs?educationOrganizationId=255901&type=Special+Education&name=Special+Education"
      }
    },
    "studentReference": {
      "studentUniqueId": "604901",
      "link": {
        "rel": "Student",
        "href": "/students?studentUniqueId=604901"
      }
    },
    "beginDate": "2010-08-30T00:00:00",
    "reasonExitedDescriptor": "05",
    "specialEducationSettingDescriptor": "09",
    "levelOfProgramParticipationDescriptor": "01",
    "placementTypeDescriptor": "0",
    "specialEducationPercentage": 0,
    "toTakeAlternateAssessment": false,
    "services": [
      {
        "serviceDescriptor": "3",
        "serviceBeginDate": "2010-08-30T00:00:00"
      }
    ],
    "serviceProviders": [],
    "_etag": "636178420442570000"
  },
  {
    
    "educationOrganizationReference": {
      "educationOrganizationId": 255901,
      "link": {
        "rel": "EducationOrganization",
        "href": "/educationOrganizations?educationOrganizationId=255901"
      }
    },
    "programReference": {
      "educationOrganizationId": 255901,
      "type": "Special Education",
      "name": "Special Education",
      "link": {
        "rel": "Program",
        "href": "/programs?educationOrganizationId=255901&type=Special+Education&name=Special+Education"
      }
    },
    "studentReference": {
      "studentUniqueId": "604934",
      "link": {
        "rel": "Student",
        "href": "/students?studentUniqueId=604934"
      }
    },
    "beginDate": "2010-08-30T00:00:00",
    "reasonExitedDescriptor": "05",
    "specialEducationSettingDescriptor": "18",
    "levelOfProgramParticipationDescriptor": "01",
    "placementTypeDescriptor": "0",
    "specialEducationPercentage": 0,
    "toTakeAlternateAssessment": false,
    "services": [
      {
        "serviceDescriptor": "2",
        "serviceBeginDate": "2010-08-30T00:00:00"
      }
    ],
    "serviceProviders": [],
    "_etag": "636178420442570000"
  }';

  
  //$jsa=json_decode($jsn);
  //$this->writevar1($jsa,'this is the json to array data');
   
    // $uri="https://iepweb03.esucc.org/json2";
   //   $uri="https://162.127.3.12:443/ng/api/api/v2.0/2017/students";
     //$uri="https://sandbox.nebraskacloud.org:443/ng/api/api/v2.0/2017/studentSpecialEducationProgramAssociations";
 // $uri='https://sandbox.nebraskacloud.org:443/ng/api/api/v2.0/2017/studentSpecialEducationProgramAssociations';
     // $uri="http://205.202.242.154/mike";
   
  
     //$cmd1="https://sandbox.nebraskacloud.org/ng/api/oauth/authorize -d  Client_id=76SHlPF7oBjl&Response_type=code\"";
     $link1="https://sandbox.nebraskacloud.org/ng/api/oauth/authorize";
      
   
     $client1=new Zend_Http_Client($link1);
     $client1->setParameterGet(array(
         
         'Client_id='=>'76SHlPF7oBjl',
         'Response_Type'=>'code',));
     
     
     $keys1=$client1->request();
     
     //$this->writevar1($client1,'this is client1');
     
    $response= $keys1->getBody();
     echo $response;
     
     
     $resp=json_decode($response,true);
    // $this->writevar1($resp,'this is the response');
     echo $resp['code'];  
     
    /*
     * https://sandbox.nebraskacloud.org/ng/api/oauth/token -H "Content-Type: application/json" -d 
     * "{'Client_id':'76SHlPF7oBjl','Client_secret':'LUBEeA5SmBKwWvi6Ov9lsvpJ','Code':'eb45039dcdda4f91878046a4b39198da','Grant_type':'authorization_code'}"

      
     curl https://sandbox.nebraskacloud.org/ng/api/oauth/token -H 
     "Content-Type: application/json" 
     
      -d "{'Client_id':'76SHlPF7oBjl',
     'Client_secret':'LUBEeA5SmBKwWvi6Ov9lsvpJ',
     'Code':'7d91d93f465e4f6cad149102b8b2ec21',
     'Grant_type':'authorization_code'}"
     */
  //   $link2="http://iepd.nebraskacloud.org/ng/api/oauth/tokin";
     
     
     $link2="http://sandbox.nebraskacloud.org/ng/api/oauth/token/";
     
     
     //Zend_Uri::check($link2);
     $this->writevar1($link2,'this is link 2 ');
     
     $client2= new Zend_Http_Client($link2);
     $client2->setHeaders('Content-type','application/json');
     
      
  /*  $client2->setParameterPost(array(
         'Client_id'=>'76SHlPF7oBjl',
         'Client_secret'=>'LUBEeA5SmBKwWvi6Ov9lsvpJ',
         'Code'=>$resp['code'],
         'Grant_type'=>'authorization_code'));
   */   
     $t=(array(
         'Client_id'=>'76SHlPF7oBjl', 
         'Client_secret'=>'LUBEeA5SmBKwWvi6Ov9lsvpJ',
         'Code'=>$resp['code'],
         'Grant_type'=>'authorization_code'));
     
     $client2_encode=json_encode($t); 
     
  
     $client2->setParameterPost('token', $client2_encode);
     $client2->setRawData($client2_encode,'application/json');
    
     
     $keys2=$client2->request('POST');
     $this->writevar1($keys2,'this is round two of the auth');
     
     

     
     
   
   
   die();
    $client = new Zend_Http_Client($uri);
   
    $client->setParameterPost('student', $jsn);
    $client->setRawData($json,'application/json');
     if ($client->request('POST')==true){
     //$t=$client->getRequest()->getParam('code');
     //$this->writevar1($t,'this  is the post');
     
        $this->redirect("/json2");
     }
     else {
         $this->redirect("/district");
     }
  }
 
  
  function index2Action()
  {
      
      $curl = curl_init();
      // The get request
   //    curl_setopt($curl, CURLOPT_URL, 'https://sandbox.nebraskacloud.org/ng/api/oauth/authorize/?Client_id=wfA54sCmDTJ0&Response_type=code');
      
    curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL =>'https://sandbox.nebraskacloud.org/ng/api/oauth/authorize/?Client_id=wfA54sCmDTJ0&Response_type=code'
));
    


       
    /*
       curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => 'https://sandbox.nebraskacloud.org/ng/api/oauth/authorize',
          CURLOPT_USERAGENT => 'Codular Sample cURL Request',
          CURLOPT_POST => 1,
          CURLOPT_POSTFIELDS => array(
              'Client_id' => 'wfA54sCmDTJ0',
              'Response_Type' => 'code'
          )          
      )); 
      */
      $resul = curl_exec($curl);
      echo $resul;
      $result=json_decode($resul);
      $this->writevar1($result,'this is the result');
      die();
  }
}
?>