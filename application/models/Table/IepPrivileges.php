<?php
//class iep_privileges extends Zend_Db_Table_Abstract {

class Model_Table_IepPrivileges extends Zend_Db_Table_Abstract {
 
    protected $_name = 'iep_privileges';
    protected $_primary = 'id_privileges';
    protected $_sequence = 'iep_priv_id_priv_seq';
    
    public function init() {
    
        $db = Zend_Registry::get('db');
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        
        //$table = new neb_esu();
        $this->className = get_class($this);

    }
       
    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
     
    public function getListOfAdmins($id_county,$id_district){
        $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $database = Zend_Db::factory($dbConfig->db2);
        $sql=$sql=('SELECT t.name_first,t.name_last,t.id_district,t.id_county,t.id_personnel,p.class
               from iep_personnel t,iep_privileges p
               where t.id_personnel=p.id_personnel and p.status=\'Active\' and p.id_county=\''.$id_county.'\'  and
               p.id_district=\''.$id_district.'\'and (p.class=\'2\' or p.class=\'3\') order by t.name_last');
        $result=$database->fetchAll($sql);
        return $result;
    }
    
    public function updatePrivilegesByUserM($id,$id_county,$id_district,$class,$id_school) {
    
         
    
        $data=array(
            'id_personnel' =>$id,
            'id_county'=>$id_county,
            'id_district'=>$id_district,
            'class'=>$class,
            'status'=>'Active',
            'id_school'=>$id_school  );
        $this->insert($data);
    }
    
    public function updatePrivilegesByUserMinactive($id,$id_county,$id_district,$class,$id_school) {
    
         
    if($id_school!='000'){
        $data=array(
            'id_personnel' =>$id,
            'id_county'=>$id_county,
            'id_district'=>$id_district,
            'class'=>$class,
            'status'=>'Inactive',
            'id_school'=>$id_school  );
        
     //   $this->writevar1($data,'this is the data');
    }
    
    if($id_school=='000'){
        $data=array(
            'id_personnel' =>$id,
            'id_county'=>$id_county,
            'id_district'=>$id_district,
            'class'=>$class,
            'status'=>'Inactive');
    
       // $this->writevar1($data,'this is the data');
    }
        
        $this->insert($data);
    }
    
    //Added 7-12-2017 in order to make staff active or inactive
    public function updatePrivs($arrayPrivs){
        $x=0;
        //  writevar($arrayPrivs,'this is the privileges');die();
        While($x <=$arrayPrivs['count']){
            $id="ID_".$x;
            $class="CLASS_".$x;
            $status="S_".$x;
            /*       writevar($arrayPrivs[$id],'this is the person');
             writevar($arrayPrivs[$class],'this is the class');
             writevar($arrayPrivs[$status],'this is the status');
             writevar($arrayPrivs['id_county'],'this is the id_county');
             writevar($arrayPrivs['id_district'],'this is the id_district');
             writevar($arrayPrivs['id_school'],'this is the id_school');
             */
            $this->updatePrivilegesByUserM2($arrayPrivs[$id],$arrayPrivs['id_county'],$arrayPrivs['id_district'],$arrayPrivs['id_school'],
                $arrayPrivs[$class],$arrayPrivs[$status]);
    
            $x=$x+1;
        }
         
    }
    // Mike added 7-12-2017
    public function updatePrivilegesByUserM2($id,$idCounty,$idDistrict,$idSchool,$class,$status){
    
        $data=array(
            'status'=>$status
        );
    
        $where = array(
            'id_personnel =?'=>$id,
            'id_district =?'=>$idDistrict,
            'id_school =?'=>$idSchool,
            'id_county =?'=>$idCounty,
            'class =?'=>$class );
        //   writevar($data,'this is the data');
        //  writevar($where,'this is the where clause');
        $this->update($data,$where);
         
    
    }
    public function updatePrivilegesByUserN($id,$id_county,$id_district,$class,$id_school) {
    
        
        $allowChange=false;
        
        $listPrivs=$_SESSION['user']['user']->privs;
        
        foreach($listPrivs as $priv) {
        if($priv['class']==1 and $priv['status']=='Active'){
            
            $allowChange=true;
        }
          // check to see if Dm
        if($priv['id_district']==$id_district && $priv['id_county']==$id_county
           && $priv['class']==2 && $priv['status']=='Active' && ($class !=2 && $class !=1))$allowChange=true;
          
          // check the  Associate District Manager
        if($priv['id_district']==$id_district && $priv['id_county']==$id_county
                  && $priv['class']==3 && $priv['status']=='Active' 
                  && $class !=2 && $class !=1 && $class !=3) $allowChange=true;
        
        // check the School Manager          
        if($priv['id_district']==$id_district && $priv['id_county']==$id_county
                      && $priv['class']==4 && $priv['status']=='Active'
                      && $class !=2 && $class !=1 && $class !=3 
                      && $class !=4 && $id_school==$priv['id_school']) $allowChange=true;
        
        // Check associate school manger              
        if($priv['id_district']==$id_district && $priv['id_county']==$id_county
            && $priv['class']==5 && $priv['status']=='Active'
            && $class !=2 && $class !=1 && $class !=3
            && $class !=4 && $class !=5 && $id_school==$priv['id_school']) $allowChange=true;
                      
         }
        
        
        
       
        
    
        $data=array(
            'id_personnel' =>$id,
            'id_county'=>$id_county,
            'id_district'=>$id_district,
            'class'=>$class,
            'status'=>'Active',
            'id_school'=>$id_school  );
        
        if($allowChange==true){
            $this->insert($data);
            return true;
        }
        
        if($allowChange==false){
            return false;
        }
    }
    
    //
    // get method 
    //
    /*
     * Mike added the setDemo function 4-18-2017 so that people without any privileges can at 
     * last login.
     * 
     */
    public function setDemo($id_personnel){
        $data=array(
            'id_personnel' =>$id_personnel,
            'id_county'=>'99',
            'id_district'=>'6666',
            'class'=>'7',
            'status'=>'Active',
            'id_school'=>'001'  );
    
    
         
    
        $this->insert($data);
    }
    
    static public function getPrivilegesByUser($id) {
        
        try
        {
            $table = new Table_IepPrivileges();
            $row = $table->fetchRow("id_personnel = '$id'");
            $data = $row->toArray();

            //
            // dates must be massaged into a nice format
            //
            //if(isset($data['iep_date'])) $data['iep_date'] = $this->date_massage($data['iep_date']); 
            //if(isset($data['date_notice'])) $data['date_notice'] = $this->date_massage($data['date_notice']); 
            
            return $data;
        }
        catch (Zend_Db_Statement_Exception $e) {
            // generate error
            throw $e;
        }
        return false;
    }
	public function serialize()
	{
		return serialize($this);
	}
    public function getPrivilegesByUser_numericKeys($id) {
        
        try
        {
            #$table = new $this->className();
            #$row = $table->fetchRow("id_personnel = '$id'");
            #$data = $row->toArray();


            $db = Zend_Registry::get('db');
            $id = $db->quote($id);

            $select = $db->select()
                         ->from( $this->className
                               )
                         ->where( "id_personnel = $id and status = 'Active'" );
                         //->group( "" );
                         //->order( "" );
            $results = $db->fetchAll($select);


            //
            // dates must be massaged into a nice format
            //
            //if(isset($data['iep_date'])) $data['iep_date'] = $this->date_massage($data['iep_date']); 
            //if(isset($data['date_notice'])) $data['date_notice'] = $this->date_massage($data['date_notice']); 
            
            return $results;
        }
        catch (Zend_Db_Statement_Exception $e) {
            // generate error
            throw $e;
        }
        return false;
    }

    //
    // save 
    //
    public function saveForm($id, $data) {
        unset($data['submit']);         // make sure submit button is removed
        unset($data['id_privileges']);    // make sure key isn't written
        unset($data['status']);         // don't allow status to be saved.
        
        //
        // dates must be null not empty string
        //
        //if(isset($data['iep_date']) && $data['iep_date']=="") $data['iep_date'] = null; 
        //if(isset($data['date_notice']) && $data['date_notice']=="") $data['date_notice'] = null; 

        #echo "<PRE>";
        #print_r($data);die();

        try
        {
            $table = new $this->className();
            $table->update($data, "id_privileges = '$id'");
            return true;
        }
        catch (Zend_Db_Statement_Exception $e) {
            // generate error
            throw $e;
        }
        return false;
    }

        
    //
    // insert method 
    //
    public function inserForm($data = null) {
        unset($data['submit']); // make sure submit button is removed
        unset($data['id_privileges']); // make sure key isn't written

        try
        {
            $table = new $this->className();
    
            //$data['type'] = 'county';            
            $table->insert($data);
        }
        catch (Zend_Db_Statement_Exception $e) {
            // generate error
            throw $e;
        }
        return false;

    }


    function date_massage($dateField, $dateFormat = 'm/d/Y') {
        
        if(empty($dateField) ) {
            return;
        }
    
        # strtotime mishandles dates with '-' 
        $dateField=str_replace("-","/",$dateField);
        date_default_timezone_set('GMT');
        return date($dateFormat, strtotime($dateField));
    
    }

    //
    // count subforms
    //
    function countSubforms($subTableName, $subTablePkName, $subTablePkValue)
    {
        #echo "subTableName: $subTableName<BR>";
        #echo "subTablePkName: $subTablePkName<BR>";
        #echo "subTablePkValue: $subTablePkValue<BR>";
        try
        {
            $db = Zend_Registry::get('db');
            $subTablePkValue = $db->quote($subTablePkValue);

            $select = $db->select()
                         ->from( $subTableName,
                                 array('count' => 'count(1)',
                               ))
                         ->where( "$subTablePkName = $subTablePkValue" );
                         //->order( "" );
            $results = $db->fetchAll($select);
            
            if(count($results) > 0) 
            {
                //print_r($results);
                return $results[0]['count'];
            }
            
            return false;
        }
        catch (Zend_Db_Statement_Exception $e) {
            // generate error
            echo "error: $e";
            die();
        }

        return false;

        
    }

    function updateSubformsOrder($subTableName, $subTablePkName, $fkName, $fkValue, $orderKeyName)
    {
        #echo "subTableName: $subTableName<BR>";
        #echo "fkName: $fkName<BR>";
        #echo "fkValue: $fkValue<BR>";
//         try
//         {
//             $db = Zend_Registry::get('db');
//             $fkValue = $db->quote($fkValue);
// 
//             $select = $db->select()
//                          ->from( $subTableName,
//                                  array($orderKeyName, $subTablePkName,
//                                ))
//                          ->where( "$fkName = $fkValue" )
//                          ->order( $orderKeyName );
//             $results = $db->fetchAll($select);
//             
//             if(count($results) > 0) 
//             {
//                 //print_r($results);
//                 //return $results[0]['count'];
//                 //
//                 // update each item so order is sequential
//                 //
//                 $objForm004Consent = new iep_privileges_consent();
//                 $i = 1;
//                 foreach($results as $row)
//                 {
//                     // update with key
//                     $objForm004Consent->saveForm($row['id_privileges_consent'], array($orderKeyName => $i));
//                     $i++;
//                 }
//             }
//             
//             return false;
//         }
//         catch (Zend_Db_Statement_Exception $e) {
//             // generate error
//             echo "error: $e";
//             die();
//         }
// 
//         return false;
// 
        
    }
    
    /* Mike added this on 5-9-2017 because did not want to interfere with getPrivileges function below
    This function takes into accout status==inactive where the other one does not.
    It is called from about line 40 in staffController.php
            $temp=$privilegesObj->getPrivilegesmike($id_personnel);

    
    */
    public function getPrivilegesmike($id_personnel)
    {
        $select = $this->_db->select()
        ->from($this->_name, array('*',
            'name_county' => 'get_name_county(id_county)',
            'name_district' => 'get_name_district(id_county, id_district)',
            'name_school' => 'get_name_school(id_county, id_district, id_school)',
            'class_description' => 'get_class_description(class)',
        ))
        ->where("id_personnel = ?", $id_personnel)
        ->where("status != 'Inactive' ")
        ->where("status != 'Removed'");
        $results = $this->_db->fetchAll($select);
        return $results;
    }

    public function getPrivileges($id_personnel)
    {
        $select = $this->_db->select()
            ->from($this->_name, array('*',
                    'name_county' => 'get_name_county(id_county)',
                    'name_district' => 'get_name_district(id_county, id_district)',
                    'name_school' => 'get_name_school(id_county, id_district, id_school)',
                    'class_description' => 'get_class_description(class)',
                ))
            ->where("id_personnel = ?", $id_personnel)
            ->where("status != 'Removed'");
        $results = $this->_db->fetchAll($select);
        return $results;
    }
    public function getPrivilege($id_privilege)
    {
        $select = $this->select()
            ->from($this->_name, array('*',
                    'name_county' => 'get_name_county(id_county)',
                    'name_district' => 'get_name_district(id_county, id_district)',
                    'name_school' => 'get_name_school(id_county, id_district, id_school)',
                    'class_description' => 'get_class_description(class)',
                ))
            ->where("id_privileges = ?", $id_privilege);
        return $this->fetchAll($select);
    }

}