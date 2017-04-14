<?php
require_once 'PHPUnit/Framework/TestCase.php';
class BootstrapTest extends PHPUnit_Framework_TestCase
{
    private $_module;
    public function setUp()
    {
//        include_once();
        parent::setUp();
    }
    public function tearDown() {
        parent::tearDown();
    }

    public function testSame()
    {
        $this->assertSame('TEST', 'TEST');
    }
    public function testNotSame()
    {
        $this->assertNotSame('12TEST', 'TEST');
    }
    public function testConfigValue()
    {
        $config = Zend_Registry::get('config');
        $this->assertNotSame('12TEST', $config->appPath);
    }
}