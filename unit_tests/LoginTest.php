<?php
require_once 'PHPUnit/Framework/TestCase.php';
class LoginTest extends PHPUnit_Framework_TestCase
{
    private $config;
    private $httpParams;

    public function setUp()
    {
        parent::setUp();
        $this->config = Zend_Registry::get('config');

        $this->httpParams = array(
            'maxredirects' => 5,
            'timeout' => 600,
        );

    }

    public function tearDown()
    {
        parent::tearDown();
        $this->config = null;
    }

    public function testLoginOldSite()
    {
        $this->assertTrue($this->loginOldSite());
    }

    public function testLoginIepweb02Site()
    {
        $this->assertTrue($this->loginIepweb03Site());
    }

    public function testLoginIepweb03Site()
    {
        $this->assertTrue($this->loginIepweb03Site());
    }

    public function loginOldSite()
    {
        // new HTTP request to old site
        $oldSiteClient = new Zend_Http_Client('https://iep.esucc.org/logon.php?option=1', $this->httpParams);
        $oldSiteClient->setMethod(Zend_Http_Client::POST);
        $oldSiteClient->setCookieJar();
        $oldSiteClient->setParameterPost('userName', 'archiver');
        $oldSiteClient->setParameterPost('password', 'thisIsTheLoginForTheArchiver123');
        $oldSiteClient->setParameterPost('ferpa', '1');
        $oldSiteClient->setParameterPost('count', '1');
        $response = $oldSiteClient->request();

        $dom = new Zend_Dom_Query($response->getBody());
        if ($dom->query('#ferpa')->count() >= 1) {
            return false;
        }
        return true;
    }

    public function loginIepweb02Site()
    {
        // new HTTP request to new
        $newSiteClient = new Zend_Http_Client('https://iepweb03.esucc.org/login', $this->httpParams);
        $newSiteClient->setMethod(Zend_Http_Client::POST);
        $newSiteClient->setCookieJar();
        $newSiteClient->setParameterPost('email', 'archiver');
        $newSiteClient->setParameterPost('password', 'thisIsTheLoginForTheArchiver123');
        $newSiteClient->setParameterPost('submit', 'Continue');
        $newSiteClient->setParameterPost('agree', 't');
        $response = $newSiteClient->request();

        $dom = new Zend_Dom_Query($response->getBody());
        if ($dom->query('#agree')->count() >= 1) {
            return false;
        }
        return true;
    }

    public function loginIepweb03Site()
    {
        // new HTTP request to new
        $newSiteClient = new Zend_Http_Client('https://iepweb03.esucc.org/login', $this->httpParams);
        $newSiteClient->setMethod(Zend_Http_Client::POST);
        $newSiteClient->setCookieJar();
        $newSiteClient->setParameterPost('email', 'archiver');
        $newSiteClient->setParameterPost('password', 'thisIsTheLoginForTheArchiver123');
        $newSiteClient->setParameterPost('submit', 'Continue');
        $newSiteClient->setParameterPost('agree', 't');
        $response = $newSiteClient->request();

        $dom = new Zend_Dom_Query($response->getBody());
        if ($dom->query('#agree')->count() >= 1) {
            return false;
        }
        return true;
    }

}

