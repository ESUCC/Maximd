<?php

class Model_Table_SaveErrorLog extends Zend_Db_Table_Abstract
{

    protected $_name= 'save_error_log';

    public function hasRecentErrorCount(
        $msgShort,
        $request,
        $repetitionTime
    )
    {
        $result = $this->fetchRow(
            $this->select()
            ->from(
                'error_log', 
                array(
                    'count(*) AS count',
                    'GROUP_CONCAT(error_log_id) AS ids'
                )
            )
            ->where(
                'error_log_date BETWEEN'.
                ' DATE_SUB(NOW(), INTERVAL ? MINUTE) AND NOW()',
                $repetitionTime
            )
            ->where(
                'error_log_msg_short = ?', $msgShort
            )
            ->where('error_log_request = ?', $request)
        );
                                                     
        if ($result->count > 0) {
            $sql = "UPDATE error_log SET error_log_repetition_count = 
            	    error_log_repetition_count + 1 WHERE error_log_id IN (?)";
            $this->getAdapter()->query($sql, array($result->ids));
            return true;
        }
        else     
            return false;                                 
    }
    
    public function writeErrorToLog(
        $message, $userSess, $userRequest,
        $formNum, $keyName, $databaseAdapter, $browser
    )
    {
        $columnMapping = array(
            'error_log_env' => 'environment',
            'id_form' => 'id_form',
            'form_number' => 'form_number',
            'id_personnel' => 'id_personnel',
            'error_message' => 'error_message',
            'operating_system' => 'operating_system',
            'browser' => 'browser',
            'browser_version' => 'browser_version',
		);
        
        
        $writer = new Zend_Log_Writer_Db(
            $databaseAdapter, $this->_name, $columnMapping
        );
                        
        $logger = new Zend_Log($writer);
        $logger->setEventItem('environment', APPLICATION_ENV);
        
        $logger->setEventItem(
            'id_form', 
            $userRequest->getParam($keyName)
        );
        $logger->setEventItem(
            'form_number', 
            $formNum
        );
        $logger->setEventItem(
            'id_personnel', 
            $userSess->sessIdUser
        );
        
        
        $logger->setEventItem(
            'error_message', 
            $message
        );
        $logger->setEventItem(
            'operating_system', 
            $browser->getPlatform()
        );
        $logger->setEventItem(
            'browser', 
            $browser->getBrowser()
        );
        $logger->setEventItem(
            'browser_version', 
            $browser->getVersion()
        );
        
        $logger->info($message);
    }
    
    public function sendNotificationEmail($message)
    {
        $config = Zend_Registry::get('Application_Ini');
        
        if ($config->errors->email->sendEmailNotification) {
            $mail = new Zend_Mail();
            $msgTxt = "The following error occurred on " 
                    . ucfirst(APPLICATION_ENV) 
                    . " at " . date('h:i:sA D F dS Y') 
                    . "\r\n \r\n";
            $msgTxt .= "Msg: " 
                    . $message
                    . " \r\n \r\n";
            $msgTxt .= "Database Log ID: "
                    . $this->getAdapter()->lastInsertId('error_log')
                    . "\r\n \r\n";
            $msgTxt .= "~System Mailer"; 
            $mail->setBodyText($msgTxt);
            $mail->setFrom(
                'noreply@optumhealth.com', 
                '<noreply@optumhealth.com>'
            );
            $mail->setSubject(
                'Optum Health ' . 
                ucfirst(APPLICATION_ENV) . 
                ' System Error'
            );
            if (!empty($config->errors->email->To)) 
                $mail->addTo(
                    $config->errors->email->To,
                    '<'.$config->errors->email->To.'>'
                );
            if (!empty($config->errors->email->Cc))
                $mail->addCc(
                    $config->errors->email->Cc,
                    '<'.$config->errors->email->Cc.'>'
                );
            $mail->send();
        }
    }
}