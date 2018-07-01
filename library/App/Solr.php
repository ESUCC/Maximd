<?php
class App_Solr
{
    public static function indexBinary($archiveData, $host, $port)
    {
        $brokenPath = explode('/', $archiveData['tmpPdfPath']);
        $index = count($brokenPath) - 1;
        $filename = $brokenPath[$index];
        
        $url = 'http://' . $host . ':' . $port . '/solr/update/extract?literal.id='
             . $filename . '&literal.studentId=' . $archiveData['studentId'] 
             . '&literal.countyId=' . $archiveData['countyId']
             . '&literal.districtId=' . $archiveData['districtId']
             . '&literal.schoolId=' . $archiveData['schoolId']
             . '&literal.formNumber=' . $archiveData['formNumber']
             . '&uprefix=attr_&fmap.content=attr_content&commit=true';
                        
        $postData = array($filename => '@' . $archiveData['tmpPdfPath']);
        return self::sendRequest($url, $postData);
    }
    
    public static function queryJson(array $queryParams, array $fieldsToReturn, $host, $port)
    {
        $url = 'http://' . $host . ':' . $port . '/solr/select/?rows=500&q=';
        
        $paramsCounter = 0;
        $urlParams = '';
        foreach($queryParams as $key => $value) {
            if(!empty($value)) {
                if($paramsCounter == 0) {
                    $urlParams = $urlParams . 'attr_' . $key . ':' . $value;
                } else {
                    $urlParams = $urlParams . ' AND attr_' . $key . ':' . $value; 
                }
            }
            $paramsCounter++;
        }
        $url = $url . urlencode($urlParams);
        
        if(sizeof($fieldsToReturn) > 0) {
            $url = $url . '&fl=';
            $fieldsCounter = 0;
            $urlParams2 = '';
            foreach($fieldsToReturn as $value) {
                if($fieldsCounter == 0) {
                    $urlParams2 = $urlParams2 . $value;
                } else {
                    $urlParams2 = $urlParams2 . ',' . $value;
                }
                $fieldsCounter++;
            }
            $url = $url . urlencode($urlParams2) . '&wt=json';
        }
        
        return self::sendRequest($url, null);
    } 
    
    private static function sendRequest($url, $postData) 
    {
       
        if($postData == null) {
            $opts = array('http' => 
                            array('method'  => 'POST', 
                                  'header' => "Content-Type: application/json\r\n" 
                                              . "Authorization: Basic "
                                              . base64_encode("administrator:37e640ca9950029cf3fd72d8b664344a") 
                                              . "\r\n"));
            $context  = stream_context_create($opts);
//<<<<<<< master
         
          //   Michael D changed this to see if we could not get it to stop in the dev environment
        //   $result = @file_get_contents($url, false, $context);
         
           //  Mike  made the result false*/
          $result = false; 
          
       
//=======
            $result = @file_get_contents($url, false, $context);
//>>>>>>> 23d43c8 Tried changing the jQueryUploadHandler.php line 33.  Changed the 0
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable        
            curl_setopt($ch, CURLOPT_POST, 1); // set POST request
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // add POST fields
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, 'administrator:37e640ca9950029cf3fd72d8b664344a');
            $result = curl_exec($ch);
            if($result === false)
            {
                echo 'Curl error: ' . curl_error($ch) . "\n";
                die;
            }
            curl_close($ch);
        }      
        return $result;
    }
}