<?php

/**
 * Model_Table_PrivilegeTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_PrivilegeTable extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_privileges';
    protected $_primary = 'id_privileges';
    protected $_sequence = "iep_priv_id_priv_seq";
    

    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    
    function viewOrEdit($id_district,$id_county,$id_school,$class){
       
        $staffId=$_SESSION["user"]["id_personnel"];
        $privs= $_SESSION["user"]["user"]->privs;
        $privCount= count($privs);
        $value='view';
        for($x=0;$x<$privCount;$x++){
         $id_dist=$_SESSION["user"]["user"]->privs[$x]["id_district"];
         $id_cty= $_SESSION["user"]["user"]->privs[$x]["id_county"];
         $id_schl=$_SESSION["user"]["user"]->privs[$x]["id_school"];
         $id_class=$_SESSION["user"]["user"]->privs[$x]["class"];
         $id_status=$_SESSION["user"]["user"]->privs[$x]["status"];
         
         if($id_dist==$id_district && $id_cty==$id_county && $id_class <='3'){
          $value='edit';  
         }
         if($id_dist==$id_district && $id_cty==$id_county && ($id_class =='5'|| $id_class=='4') 
            && $id_school==$id_school) {
                $value='edit';
            }  
        }
        return($value);
    }
    
     function getUserInfo2 ($classLevel)
    {
      //   include("Writeit.php");
        $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $database = Zend_Db::factory($dbConfig->db2);
        
        $staffId=$_SESSION["user"]["id_personnel"];
        $cnt= $_SESSION["user"]["user"]->privs;
        $privCount= count($cnt);
      //  $this->writevar1($privCount,'this is the prvi count');
        $y=0;
        $superAdmin=false;
    //   $this->writevar1($classLevel,'this is the class level');
        $gotOne=0;
        for($x=0;$x<$privCount;$x++)
        {
           
           $status=$_SESSION["user"]["user"]->privs[$x]['status'];
            
           $check=$_SESSION["user"]["user"]->privs[$x]["class"];
           
           if($status=='Active'&& $check=='1')$superAdmin=true;

           
           
            if ($check<=$classLevel && $status=='Active') {
            $gotOne=1;
            $array[$y]['id_personnel']=$staffId;
            $array[$y]['class']=$_SESSION["user"]["user"]->privs[$x]["class"];
            $array[$y]['id_district']=$_SESSION["user"]["user"]->privs[$x]["id_district"];
            $array[$y]['id_county']=$_SESSION["user"]["user"]->privs[$x]["id_county"];
         
           // Mike added this 2-15-2017 so that we can extend the function to get more.
            $array[$y]['id_school']=$_SESSION["user"]["user"]->privs[$x]["id_school"];
             $array[$y]['class']=$_SESSION["user"]["user"]->privs[$x]["class"];
            $array[$y]['status']=$_SESSION["user"]["user"]->privs[$x]["status"];
            $sql=('SELECT use_edfi,name_district,id_district,id_county from iep_district where id_district =\''.$array[$y]['id_district'].'\' 
                  and id_county = \''.$array[$y]['id_county'].'\' order by name_district');
             $t=$database->fetchall($sql);
             $count=count($t);
          //   $this->writevar1($array[$y],'hope this has an school id in it');
             if($t) {
                 $array[$y]['name_district']=$t[0]['name_district'];
                 $array[$y]['use_edfi']=$t[0]['use_edfi'];
                 
          //       $this->writevar1($array[$y],'this is this school district');
            //     $this->writevar1($_SESSION["user"]["user"]->privs[$x]["class"],'class level');
                 $y=$y+1;
                 }
   
              }
           
           }
           //   $this->writevar1($array,'this is the whole thing');
            //  $this->writevar1($gotOne,'this is the value of got');
     
           
     // Mike added this 9-7-2017 so that all the districts would show up in "Edit the Following Districts"
     // tab.  
     if($superAdmin==true){
         $dist=new Model_Table_IepDistrict();
         $array=$dist->getAllDistricts();
         return($array);
         
     }
    // end of Mike add       
           
    if($gotOne==1) {
        return($array);
    }
    else {
        return(0);
    }
    
}  // This is the end of the funciton
    
    function getListOfDistricts($id){
        
    }

    // 	protected $_referenceMap = array(
// 		'ContactTypes' => array(
// 			'columns' => array('contacttype_id'),
// 			'refTableClass' => 'ContactTypesTable',
// 			'refColumns'	=> array('id')
// 		)
// 	);
}
