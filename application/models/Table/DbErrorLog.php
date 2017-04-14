<?php

class Model_Table_DbErrorLog extends Zend_Db_Table_Abstract
{
    protected $_name= 'db_error_log';

    public function hasRecentErrorCount($msgShort, $request, $repetitionTime)
    {
    	try {
            $select = $this->select()->from('db_error_log',  array('count(*) AS count',
                                      'error_log_id AS id'))
                ->where('error_log_date BETWEEN (NOW() - INTERVAL \''.$repetitionTime .' MINUTES\') AND NOW()')
                ->where('error_log_msg_short = ?', $msgShort)
                ->where('error_log_request = ?', $request)
                ->group('error_log_id');         
            $result = $this->fetchRow($select);                                                        
            if ($result && $result->count > 0) {          
                return $result->id;
            } else {     
                return false;  
            }                               
    	} catch (Exception $e) { 
    	    return false; 
    	}
    }
        
    public function incrementErrorLog($id) 
    {
        $sql = "UPDATE db_error_log SET error_log_repetition_count =
                	    error_log_repetition_count + 1 WHERE error_log_id = ?";
        $this->getAdapter()->query($sql, array($id));
    }

    
    public function writeErrorToLog($message, $userRequest, $sessionString, 
                    $traceString, $username, $host, $browser, $databaseAdapter)
    {
        $columnMapping = array(
            'error_log_env' => 'environment',
            'error_log_lvl' => 'priority', 
            'error_log_msg_short' => 'message',
            'error_log_username' => 'username',
            'error_log_host' => 'host',
            'error_log_browser' => 'browser',
            'error_log_status' => 'status',
            'error_log_repetition_count' => 'count',
            'error_log_trace' => 'trace',
            'error_log_request' => 'request',
            'error_log_session' => 'session');
        try {
	        $writer = new Zend_Log_Writer_Db($databaseAdapter, 'db_error_log', $columnMapping);
                        
	        $logger = new Zend_Log($writer);
	        $logger->setEventItem('environment', APPLICATION_ENV);
	        $logger->setEventItem('username', $username);
	        $logger->setEventItem('host', $host);
	        $logger->setEventItem('browser', $browser);
	        $logger->setEventItem('status', 'pending');
	        $logger->setEventItem('count', 1);
	        $logger->setEventItem('trace', $traceString);
	        $logger->setEventItem('request', $userRequest);
	        $logger->setEventItem('session', $sessionString); 
	        $logger->info($message);
	        
	    } catch (Exception $e) {
	        //We couldn't store the error in the database
	        $checkThis = $e->getMessage();
	    }
    }
    
    public function sendNotificationEmail($message, $username, $host, $browser)
    {
        $config = Zend_Registry::get('config');
    	try {
    	    $errorid = $this->getAdapter()->lastInsertId('db_error_log', 'error_log_id');
    	} catch (Exception $e) { 
    	    $errorid = 'dberror'; 
    	}
        if ($config->errors->email->sendEmailNotification) {
            $mail = new Zend_Mail();
            $msgTxt = "The following error occurred on " . ucfirst(APPLICATION_ENV) 
                    . " at " . date('h:i:sA D F dS Y') . "\r\n \r\n";
            $msgTxt .= "Msg: " . $message . " \r\n \r\n";
            $msgTxt .= "Database Log ID: " . $errorid . "\r\n \r\n";
            $msgTxt .= "Username: " . $username . "\r\n \r\n";
            $msgTxt .= "Host: " . $host . "\r\n \r\n";
            $msgTxt .= "Browser: " . $browser . "\r\n \r\n";
            $msgTxt .= "~System Mailer"; 
            $mail->setBodyText($msgTxt);
            $mail->setFrom('noreply@nebraskasrs.com', '<noreply@nebraskasrs.com>');
            $mail->setSubject('Nebraska SRS ' . ucfirst(APPLICATION_ENV) 
                            . ' System Error');
            if (!empty($config->errors->email->To)) 
                $mail->addTo($config->errors->email->To,
                    '<'.$config->errors->email->To.'>');
            if (!empty($config->errors->email->Cc))
                $mail->addCc($config->errors->email->Cc,
                    '<'.$config->errors->email->Cc.'>');
            $mail->send();
        }
    }
}