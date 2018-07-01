<?php
class Model_Logger {
	public static function log($message) {
		$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . "/../temp/usage.txt");
		
		$logger = new Zend_Log($writer);
		$logger->log($message, 1);
	}
	
	/*
	 * Tracks access to student forms.
	 * 
	 * @param int $documentId
	 * @param int $logType 
	 * 	(1 => 'viewed record', 2 => 'viewed record with edit privs',
	 *   3 => 'saved record', 4 => 'deleted record', 
	 *   5 => 'custom' (should include notes))
	 * @param string $tableName
	 * @param string $studentId
	 * @param string $page
	 * @param string $notes
	 */
	public static function writeLog(
			$documentId, $logType, $tableName,
			$studentId, $page, $notes = '' 
	) {
		$userSess = new Zend_Session_Namespace('user');
		/*
		 * Don't log access for admins
		 */
		$student_auth = new App_Auth_StudentAuthenticator();
		if ('Admin' == $student_auth->validateStudentAccess($studentId, $userSess))
			return true;
			
		$columnMapping = array(
				'id_rel_record' => 'id_rel_record',
				'id_student' => 'id_student',
				'type' => 'type',
				'table_name' => 'table_name',
				'page' => 'page',
				'id_guardian' => 'id_guardian',
				'id_author' => 'id_author',
				'notes' => 'message');
		
		$writer = new Zend_Log_Writer_Db(
				Zend_Db_Table_Abstract::getDefaultAdapter(), 
				'iep_log', 
				$columnMapping
		);
		
		$logger = new Zend_Log($writer);
		$logger->setEventItem('id_rel_record', (string)$documentId);
		$logger->setEventItem('id_student', (int)$studentId);
		$logger->setEventItem('type', (int)$logType);
		$logger->setEventItem('table_name', (string)$tableName);
		$logger->setEventItem('page', (int)$page);
		$logger->setEventItem('id_guardian', $userSess->parent ? (int)$userSess->sessIdUser : 0);
		$logger->setEventItem('id_author', $userSess->parent ? 0 : (int)$userSess->sessIdUser);
		$logger->info($notes);
	}
}