<?php

class App_Service_PersonnelService 
{
	protected $db;
	
	protected $personnel;
	
	/**
	 * Contact Types Table
	 *
	 * @var ContactTypesTable
	 */
//	protected $contactTypes;
	
	/**
	 * Contact Types Table
	 *
	 * @var TaskTable
	 */
	protected $task;
	
	function __construct()
	{

		$this->db = Zend_Registry::get('db');
		Zend_Db_Table_Abstract::setDefaultAdapter($this->db);
		
//		require_once('Table/PersonnelTable.php');
		$this->personnel = new Model_Table_PersonnelTable();
		
	}
	public function NewPersonnel($name_first , $user_name, $password)
	{
		$params = array(
			'name_first' 	=> $name_first,
			'user_name' => $user_name,
			'password' => $password
		);
		
		$this->personnel->insert($params);
		
	}
	/**
	 * @return Zend_Db_Table_Rowset
	 */	
	public function GetPersonnel($id)
	{
		return $this->personnel->find($id);	
	}
	
    static public function getUser($userName = null) {
    
        if(null == $userName)
        {
            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                // Identity exists; get it
                $userName = $auth->getIdentity();
            } else {
                return false;
            }
        }
        
        $db = Zend_Registry::get('db');

        $userName = $db->quote($userName);

        $select = $db->select()
                     ->from( array('u' => 'iep_personnel'),
                             array('*')
                           )
                     ->where( "user_name = $userName" );
                     //->order( "" );
        $result = $db->fetchAll($select);
        
        return $result[0];
    }

    static public function getPrivilegesByUser($id, $status = 'Active') {
        
        try
        {
            $table = new Model_Table_PrivilegeTable();
        	
//            $table = new iep_privileges();
            if(is_null($status)) {
                $result = $table->fetchAll("id_personnel = '$id'", "class");
            } else {
                $result = $table->fetchAll("id_personnel = '$id' and status = '$status'", "class");
            }
            $data = $result->toArray();
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
    
// 	public function GetTask($id)
// 	{
// 		return $this->task->find($id);	
// 	}
// 	public function GetAllTasks()
// 	{
// 		return $this->task->fetchAll();
// 	}
	private function getWhere($id)
	{
		return $this->personnel->getAdapter()->quoteInto('id = ?',$id);
	}
	public function SavePersonnel($id, $name, $email, $contacttype_id)
	{ 		
		$params = array(
			'name' 	=> $name,
			'email' => $email,
			'contacttype_id' => $contacttype_id
		);
		
		$this->personnel->update($params, $this->getWhere($id));
		return true;
	}
	public function DeletePersonnel($id)
	{
		$this->personnel->delete($this->getWhere($id));
	}
	public function GetAllPersonnels()
	{
			
		$select = $this->personnel->select();
		$select->order('name');
		return $this->personnel->fetchAll($select);
	}
// 	public function GetContactTypeByName($name)
// 	{
// 		$where = $this->contactTypes->getAdapter()->quoteInto('LOWER(name) = ?', strtolower($name));
// 		
// 		return $this->contactTypes->fetchRow($where);
// 	}
// 		
// 	public function GetAllContactTypes()
// 	{
// 		return $this->contactTypes->fetchAll();
// 	}
		
	
}
