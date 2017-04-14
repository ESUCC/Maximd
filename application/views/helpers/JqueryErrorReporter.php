<?php

/**
 * Helper for displaying jquery error reporting button
 * 
 */
class Zend_View_Helper_JqueryErrorReporter extends Zend_View_Helper_Abstract
{

    /**
     *
     * @var string
     */
    protected $retString;

    /**
     * Return base URL of application
     *
     * @return string
     */
    public function jqueryErrorReporter ()
    {
        $userId = '0000000';
        $userName='error getting session';
        try {
            $sessUser = new Zend_Session_Namespace ( 'user' );
            $userId= $sessUser->sessIdUser;
            $userName= @$sessUser->user->user['name_first'] . ' ' .@$sessUser->user->user['name_first'];
        } catch (Exception $e) {
            // no user?
        }
        $this->view->jQuery()->addJavascriptFile('/js/view_helpers/jqueryErrorReporter.js');
        $this->retString = <<<EOS
            <div style="float:right;text-align:right;">
                <button id="view-helper-error-reporter-create-button" type="button">Report Error</button>
            <div>
    	    <div id="view-helper-error-reporter-form-dialog" title="Error Report Form" style="float:right;display: none;">
    		    <div id="view-helper-error-reporter-user-id-display">User: {$userName}</div>
    		    <div id="view-helper-error-reporter-form-number-display">Form:</div>
    		    <div id="view-helper-error-reporter-form-id-display">Form ID:</div>
    		    <div style="padding-top:10px;padding-bottom:5px;">Please provide a complete description of the problems you are having.</div> 
    			<textarea id="view-helper-error-reporter-description" name="errorDescription" style="min-height:100px;width:320px;"></textarea>
                
    			<input type="hidden" id="view-helper-error-reporter-user-id" value="{$userId}">
    			<input type="hidden" id="view-helper-error-reporter-form-number">
    			<input type="hidden" id="view-helper-error-reporter-form-id">
    			<input type="hidden" id="view-helper-error-reporter-page-number">
    			<input type="hidden" id="view-helper-error-reporter-student-id" value="{$this->view->db_form_data['student_data']['id_student']}">
    			<input type="hidden" id="view-helper-error-reporter-user-id" value="{$userId}">
    	    </div>
EOS;
        return $this->retString;
    }
    
    /**
     * @var Zend_View_Interface
     */
    public $view;
    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }
    
}
