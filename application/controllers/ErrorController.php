<?php
//require_once 'Zend/Controller/Action.php';

/**
 * ErrorController 
 * 
 * @uses      Zend_Controller_Action
 * @package   Paste
 * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version   $Id: $
 */
class ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {
        $this->_helper->viewRenderer->setViewSuffix('phtml');
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                $this->view->code    = 404;
                if ($errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER) {
                    $this->view->info = sprintf(
                                            'Unable to find controller "%s" in module "%s"', 
                                            $errors->request->getControllerName(),
                                            $errors->request->getModuleName()
                                        );
                }
                if ($errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION) {
                    $this->view->info = sprintf(
                                            'Unable to find action "%s" in controller "%s" in module "%s"', 
                                            $errors->request->getActionName(),
                                            $errors->request->getControllerName(),
                                            $errors->request->getModuleName()
                                        );
                }
                break;
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
            default:
            	
            	switch(get_class($errors->exception)) {
            		case "App_Exception_NoAccess";
            		$this->_forward('no-access');
            		break;
                    case "App_Exception_Checkout";
                    die();
                    $this->_forward('checkout'); 
                    break;
            	}
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                $this->view->code    = 500;
                $this->view->info    = $errors->exception;
                break;
        }
        //Store error information in the database
        $config = Zend_Registry::get('config');
        if (1 == $config->errors->logging) {
            $databaseAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
            $errorLog = new Model_Table_DbErrorLog();
            $userRequest = $this->view->escape(var_export($errors->request->getParams(), true));
            
            /*
             * Check to see if this error has happended within
            * config minutes.  If it has then we just update
            * the DB with a repetition count.
            */
            $userRequest = $errors->request->getParams();
            
            $errorLogId = $errorLog->hasRecentErrorCount(
                            $this->view->escape($errors->exception->getMessage()),
                            $this->view->escape(var_export($userRequest, true)), 
                            $config->errors->repetitionTimeMinutes);
            
            if (false === $errorLogId) {
                $username = $_SESSION['user']['user']->username;
                $host = $_SERVER['HTTP_HOST'];
                $browser = $_SERVER['HTTP_USER_AGENT'];
                $errorLog->writeErrorToLog(
                                $errors->exception->getMessage(),
                                $this->view->escape(var_export($userRequest, true)),
                                $this->view->escape(var_export($_SESSION, true)),
                                $this->view->escape($errors->exception->getTraceAsString()),
                                $this->view->escape($username),
                                $this->view->escape($host),
                                $this->view->escape($browser),
                                $databaseAdapter);
                
//                $errorLog->sendNotificationEmail($errors->exception->getMessage(), $username, $host, $browser);
            } else {
                $errorLog->incrementErrorLog($errorLogId);
            }
        }
    }
    public function noAccessAction() {
        //goes here if the page was not found
        
    }
    public function checkoutAction()
    {
    
    }
    public function timeoutAction()
    {
        $i = 0;
        //set_time_limit(120);
        echo "starting loop";
        while ($i<60)
        {
            sleep (5);
            echo "at loop $i<br>\n";
            flush();
            $i++;
        }
        echo "finished loop";
    }
}
