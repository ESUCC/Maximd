<?php
class LoginManager
{
    private $username;
    private $password;
    private $httpParams;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->httpParams = array('maxredirects' => 5 , 'timeout' => 600);
    }
    
    public function loginNewSite($config)
    {
        $newSiteClient = $this->setup($config->DOC_ROOT . 'login');
        $newSiteClient->setParameterPost('submit', 'Continue');
        $newSiteClient->setParameterPost('agree', 't');
        $response = $newSiteClient->request('POST');
        //echo($response);
        return $newSiteClient;
    }

    public function loginOldSite()
    {
        $oldSiteClient = $this->setup('https://iep.nebraskacloud.orgu/logon.php?option=1');        
        $oldSiteClient->setParameterPost('ferpa', '1');
        $response = $oldSiteClient->request('POST');
        //echo($response);
        return $oldSiteClient;
    }
    
    private function setup($url) 
    {
        $client = new Zend_Http_Client($url, $this->httpParams);
        $client->setCookieJar();
        $client->setParameterPost('userName', $this->username);
        $response = $client->setParameterPost('password', $this->password);
        return $client;
    }
}