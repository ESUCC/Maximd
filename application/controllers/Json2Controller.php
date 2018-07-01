<?php 
class Json2Controller extends Zend_Controller_Action
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
   //   $tt=$this->getRequest()->getParam();
        $tt= $this->getRequest()->getRawBody();
    
      //$tt=$this->_getAllParams();
        $uri="https://iepweb03.esucc.org/json2";
        $client = new Zend_Http_Client($uri);
        
     // $tt=$client->request->getBody;
      
   // $this->writevar1($tt,'this is the value of the json as raw data');
        
        
      $ttt=json_decode($tt);
    // $this->writevar1($ttt,'this is the value of the json as real json');
     
      $tttt=json_decode($ttt);
    //  $this->writevar1($tttt,'this is the value of the json as an array');
      die();
  }
  
}
?>