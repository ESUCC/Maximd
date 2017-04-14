<?php
class App_Email_PasswordHelper {

    static function getEmailConfig() {
        $front = Zend_Controller_Front::getInstance();
        $bootstrap = $front->getParam("bootstrap");
        $emailConfig = $bootstrap->getOptions();
        return $emailConfig['email'];
    }
    static function initEmail($emailConfig=null) {
        if(is_null($emailConfig)) $emailConfig = App_Email_PasswordHelper::getEmailConfig();
        $transport = new Zend_Mail_Transport_Smtp($emailConfig['host'], $emailConfig);
        Zend_Mail::setDefaultTransport($transport);
    }

    static function sendEmailChangeConfirm($emailConfig=null, $emailAddress, $name, $hash) {
        if(is_null($emailConfig)) $emailConfig = App_Email_PasswordHelper::getEmailConfig();

        $transport = Zend_Mail::getDefaultTransport();
        $mail = new Zend_Mail();

        $msgTxt = "A request to change your email to $emailAddress has been received by the ";
        $msgTxt .= "Nebraska SRS system. Please click the following link to confirm you new email address: \r\n\r\n";
        $msgTxt .= "http://".$_SERVER['HTTP_HOST'] . "/index/confirm-email-update/hash/$hash\r\n\r\n";

        $mail->setBodyText($msgTxt);
        $mail->setFrom($emailConfig['from']);
        $mail->setSubject('Nebraska SRS - Email Changed');
        $mail->addTo($emailAddress, $name);
        $sent = true;
        try {
            $mail->send($transport);
        } catch (Exception $e){
            $sent = false;
        }
        return $sent;
    }

    static function sendLoginInfo($emailConfig=null, $emailAddress, $name, $username, $password) {

        if(empty($username)) return false;
        if(empty($password)) return false;
        if(empty($emailAddress)) return false;
        if(is_null($emailConfig)) $emailConfig = App_Email_PasswordHelper::getEmailConfig();

        $transport = Zend_Mail::getDefaultTransport();
        $mail = new Zend_Mail();

        $msgTxt = "\r\n";
        $msgTxt .= "The following is your login info to the Nebraska SRS System:\r\n";
        $msgTxt .= "User Name: $username\r\n";
        $msgTxt .= "Password: $password\r\n\r\n";

        $mail->setBodyText($msgTxt);
        $mail->setFrom($emailConfig['from']);
        $mail->setSubject('Nebraska SRS - Login Info');
        $mail->addTo($emailAddress, $name);

        $sent = true;
        try {
            $mail->send($transport);
        } catch (Exception $e){
            $sent = false;
        }
        return $sent;
    }

}
