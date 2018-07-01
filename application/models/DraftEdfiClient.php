<?php

/**
 * 
 * Handles HTTP API calls to EdFi 
 *
 * @author odiaz@doublelinepartners.com
 * @version 1.0
 *
 */ 

class Model_DraftEdfiClient  {

    var $edfiBaseUrl = null;
    var $edfiClientId = null;
    var $edfiClientSecret = null;


    /* Holds current token for request */
    var $currentToken;

    function __construct($edfiBaseUrl = null, $edfiClientId = null, $edfiClientSecret = null) {
        $config = Zend_Registry::get( 'config' );

        if (!$edfiBaseUrl) {
            if ($config->edfi->baseUrl)
                $edfiBaseUrl = $config->edfi->baseUrl;
            else 
                throw new Exception("EdFi engine misconfigured");
        } else {
            $this->edfiBaseUrl = $edfiBaseUrl;
        }

        if (!$edfiClientId) {
            if (!$config->edfi->clientId)
                $edfiClientId = $config->edfi->clientId;
            else 
                throw new Exception("EdFi engine misconfigured");
        } else {
            $this->edfiClientId = $edfiClientId;
        }

        if (!$edfiClientSecret) {
            if (!$config->edfi->clientSecret)
                $edfiClientSecret = $config->edfi->clientSecret;
            else
                throw new Exception("EdFi engine misconfigured");
        } else {
            $this->edfiClientSecret = $edfiClientSecret;
        }
    }

    /**
     * Compose a query string from an associative array.
     * Example: key1=val1&key2=val2
     * <code>
     * $query = array('key1' => 'val1', 'key2' => 'val2');
     * </code>
     *
     * @return string $newQuery
     */
    private function composeQueryString($query)
    {
        if (!is_array($query)) {
            throw new Exception('"$query" must be an associative array');
        }
        
        if (!count($query)) {
            return "";
        }
        
        $newQuery = array();
        foreach ($query as $k => $v) {
            $tmp = $k . "=" . $v;
            array_push($newQuery, $tmp);
        }
        $newQuery = implode("&", $newQuery);
        return $newQuery;
    }
    
    private function edfiAPIGet($apiEndpoint, $parameters) {

        $authorization = "Authorization: Bearer " . $this->edfiApiAuthenticate();
        //$url = $this->currentAPIUrl . "/api/v2.0/2017/students?studentUniqueId=" . $studentId;
        $queryString = $this->composeQueryString($parameters);
        $url = $this->edfiBaseUrl . $apiEndpoint . "?" . $queryString;

        $curl = curl_init();
         
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($curl, CURLOPT_URL, "$url");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
             
        $result = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $result;
    }


    private function edfiAPIPost() {
        throw new Exception("Not implemented");
    }

    private function edfiAPIDelete() {
        throw new Exception("Not implemented");
    }

    private function edfiAPIPut() {
        throw new Exception("Not implemented");
    }


    /* Gets authorization code for client */
    protected function getAuthCode() {
	 
        $edfiApiCodeUrl = $this->edfiBaseUrl . "/oauth/authorize";	
        $data = "client_id=" . $this->edfiClientId . "&response_type=code";
        $urlWithData = "$edfiApiCodeUrl?$data";
	   
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

    /*Get the acceso token*/
    protected function getAuthToken($authCode) {
        
        $edfiApiTokenUrl = "$this->edfiBaseUrl/oauth/token";
        $paramsToPost = "Client_id=$this->edfiClientId&Client_secret=$this->edfiClientSecret&Code=$authCode&Grant_type=authorization_code";
	
        $curl = curl_init();
	 
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_URL, "$edfiApiTokenUrl");
	    curl_setopt($curl, CURLOPT_POST, 1);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $paramsToPost);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
		 
        $result = curl_exec($curl);
	    
        $jsonResult = json_decode($result);
	  
        curl_close($curl);
	     
	    return $jsonResult->access_token;
    }

    /* Authenticate and returns barier token or empty string if an error occurs */
    protected function edfiApiAuthenticate() {
    
        if (!isset($this->currentToken)) {
            $authCode = $this->getAuthCode();
            $this->currentToken = $this->getAuthToken($authCode);
        }

        return $this->currentToken;
    }

    /* Get students. */
    public function getStudent($studentId) {
        $student = $this->edfiAPIGet("/api/v2.0/2017/students", array('studentUniqueId' => $studentId));
        return json_decode($student);
    }
    
    public function getParents($studentId) {
        $result = $this->edfiAPIGet("/api/v2.0/2017/studentParentAssociations", array('studentUniqueId' => $studentId));
        $parentAssociations = json_decode($result);
        $parents = array();
        foreach ($parentAssociations as $value => $parentAssociation) 
        {
            $parentUniqueId = $parentAssociation->parentReference->parentUniqueId;
            $parents[] = $this->getParent($parentUniqueId);
        }
        
        return $parents;
    }
    
    public function getParent($parentId)
    {
        $parent = $this->edfiAPIGet("/api/v2.0/2017/parents", array('parentUniqueId' => $parentId));
        return json_decode($parent);
    }
}
