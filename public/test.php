<html>
    <head>
       <title>PHP Test</title>
   </head>
 <body>
  
 <?php
  
 // TODO: Replace URL, Client ID, and Client Secret with your values
 $edfiBaseUrl = "https://api.ed-fi.org/api"; // NOTE: No trailing slash!
 $edfiClientId = "g3uiYKK0Pros";
 $edfiClientSecret = "bjRB3D3ahbsV33YgXxApZLyG";
  
 function getAuthCode($edfiBaseUrl, $edfiClientId){
   
   $edfiApiCodeUrl = "$edfiBaseUrl/oauth/authorize"; 
   $data = "Client_id=$edfiClientId&Response_type=code";
   $urlWithData = "$edfiApiCodeUrl?$data";
  
   $curl = curl_init();
   
   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($curl, CURLOPT_URL, $edfiApiCodeUrl);
   curl_setopt($curl, CURLOPT_POST, 1);
   curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
  
    // Receive server response.
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
   $result = curl_exec($curl);
  
   $jsonResult = json_decode($result);
  
   curl_close($curl);
  
   return $jsonResult->code;
 }
  
 function getAuthToken($edfiBaseUrl,$edfiClientId,$edfiClientSecret,$authCode){
   
   $edfiApiTokenUrl = "$edfiBaseUrl/oauth/token";
   $paramsToPost = "Client_id=$edfiClientId&Client_secret=$edfiClientSecret&Code=$authCode&Grant_type=authorization_code";
   
   $curl = curl_init();
   
   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($curl, CURLOPT_URL, "$edfiApiTokenUrl");
   curl_setopt($curl, CURLOPT_POST, 1);
   curl_setopt($curl, CURLOPT_POSTFIELDS, $paramsToPost);
  
    // Receive server response
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
   $result = curl_exec($curl);
  
   $jsonResult = json_decode($result);
  
   curl_close($curl);
  
   return $jsonResult->access_token;
 }
  
 function edfiApiAuthenticate($edfiBaseUrl,$edfiClientId,$edfiClientSecret,$authCode){
   $authCode = getAuthCode($edfiBaseUrl, $edfiClientId);
   $accessToken = getAuthToken($edfiBaseUrl,$edfiClientId,$edfiClientSecret,$authCode);
   return $accessToken;
 }
  
 function edfiApiGet($token,$edfiResourceUrl,$data){
   
   $authorization = "Authorization: Bearer $token";
  
   $curl = curl_init();
   
   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
   curl_setopt($curl, CURLOPT_URL, "$edfiResourceUrl?$data");
    
    // Receive server response
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
   $result = curl_exec($curl);
  
   $jsonResult = json_decode($result);
  
   curl_close($curl);
  
   return $jsonResult;
 }
 ?>
  
 <?php
 $accessToken = edfiApiAuthenticate($edfiBaseUrl,$edfiClientId,$edfiClientSecret,$authCode);
 echo "<p>token: $accessToken</p>";
  
 //$jsonStudents = edfiApiGet($accessToken,"https://api.ed-fi.org/api/api/v2.0/2017/students","");
 $jsonStudents = edfiApiGet($accessToken,"https://api.ed-fi.org/api/api/v2.0/2017/students","limit=10");
 ?>
  
 <table>
   <thead>
     <tr>
           <th>FirstName</th>
           <th>Middle</th>
           <th>Last</th>
     </tr>
   </thead>
   <tbody>
    <?php
   foreach ($jsonStudents as $student => $s) {
            // Output a row
            $row = "<tr>";
             $row .= "<td>$s->firstName</td>";
             $row .= "<td>$s->middleName</td>";
             $row .= "<td>$s->lastSurname</td>";
             $row .= "</tr>";
             echo $row;
         }?>
   </tbody>
</table>
<hr>
<?php echo json_encode($jsonStudents); ?>
