<?php
//class iep_privileges extends Zend_Db_Table_Abstract {

class Model_Table_IepPrivileges extends Zend_Db_Table_Abstract {
 
    protected $_name = 'iep_privileges';
    protected $_primary = 'id_privileges';
    
    public function init() {
    
        $db = Zend_Registry::get('db');
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        
        //$table = new neb_esu();
        $this->className = get_class($this);

    }
        
    //
    // get method 
    //
    
    public function getPrivilegesByUserM($id) {
    
        try
        {
            $table = new Model_Table_IepPrivileges();
            $row = $table->fetchAll($this->select()
                ->where('id_personnel = ?',$id));
    
    
            return $row->toArray();
        }
        catch (Zend_Db_Statement_Exception $e) {
            // generate error
            throw $e;
        }
        return false;
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