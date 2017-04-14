<?php
/**
 * BuildController 
 * 
 * @uses      Zend_Controller_Action
 * @package   Paste
 * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version   $Id: $
 */
class BuildController extends Zend_Controller_Action {
	/**
	 */
	public function indexAction() {
		/*
    	 * Zend_Dojo build layer support
    	 * Introduction
    	 * Dojo build layers provide a clean path from development to production 
    	 * when using Dojo for your UI layer. In development, you can have 
    	 * load-on-demand, rapid application prototyping; a build layer takes 
    	 * all Dojo dependencies and compiles them to a single file, optionally 
    	 * stripping whitespace and comments, and performing code heuristics to 
    	 * allow further minification of variable names. Additionally, it can do 
    	 * CSS minification.
    	 * 
    	 * In order to create a build layer, you would traditionally create a 
    	 * JavaScript file that has dojo.require statements for each dependency, 
    	 * and optionally some additional code that might run when the script 
    	 * is loaded. As an example:
    	 * 
    	 * This script is generally referred to as a "layer" script. 
    	 * 
    	 * In this app our layer script is: /js/srs_forms/includes.js
    	 *  
    	 * The layer is added in our Initialize.php plugin at startup
    	 *  
    	 * $view->dojo()->addLayer($this->config->dojoPath.'/../srs_forms/includes.js'.$refreshCode)
    	 * 
    	 */
		
		echo "build";
		die ();
	}
}
