<?php

class App_Service_StudentService 
{
	protected $db;
	
	protected $student;
	
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
		
		require_once('Table/StudentTable.php');
		$this->student = new StudentTable();
		
	}
//	public function NewStudent($name_first , $user_name, $password)
//	{
//		$params = array(
//			'name_first' 	=> $name_first,
//			'user_name' => $user_name,
//			'password' => $password
//		);
//		
//		$this->student->insert($params);
//		
//	}
	/**
	 * @return Zend_Db_Table_Rowset
	 */	
	public function GetStudent($id)
	{
		return $this->student->find($id);	
	}
	    
	public function GetStudentInfo($id)
	{
		return $this->student->studentInfo($id);	
	}
	    
	private function getWhere($id)
	{
		return $this->student->getAdapter()->quoteInto('id = ?',$id);
	}
	public function SaveStudent($id, $name, $email, $contacttype_id)
	{ 		
		$params = array(
			'name' 	=> $name,
			'email' => $email,
			'contacttype_id' => $contacttype_id
		);
		
		$this->student->update($params, $this->getWhere($id));
		return true;
	}
	public function DeleteStudent($id)
	{
		$this->student->delete($this->getWhere($id));
	}
	public function GetAllStudents()
	{
			
		$select = $this->student->select();
		$select->order('name');
		return $this->student->fetchAll($select);
	}
		
	
}
