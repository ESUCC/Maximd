 <?php 
 class EdfiExport {
 
 public function __construct() {
        
     parent::__construct();
  
        
        
     
        /**
         * get the main application AND import config files
         */
        $appConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
      
        /** create database connection */
        $dbConfig = $appConfig->db2;
        $db = Zend_Db::factory($dbConfig);    // returns instance of Zend_Db_Adapter class
      //  print_r($db);die(); prints what you would expect 
        Zend_Registry::set('db', $db);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);

        return true;
    
   }
 }
    ?>