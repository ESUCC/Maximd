<?php

 class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
 {
 	protected function _initAutoload()
 	{
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace(array('App_'));
        $autoloader->registerNamespace(array('My_'));
        $autoloader->registerNamespace('DbTable_');
 		
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
 			'namespace' => '',
 			'basePath' => APPLICATION_PATH)
 		);
 
 		return $moduleLoader;		
 	}
 	protected function _initControllerPlugins()
	{
		$front = Zend_Controller_Front::getInstance();
		
		// VERY IMPORTANT
		// MUCH OF THE APPLICATION IS INITIALIZED IN THE PLUGIN
		// IN library/My/Plugin/Initialize.php
		//
		// Notice that the path to the plugin is also in the name of the plugin
		// My_Plugin_ViewSetup is in library/My/Plugin/ViewSetup.php
		//->registerPlugin(new My_Plugin_Initialize(APPLICATION_ENV))
		//      ->registerPlugin(new My_Plugin_ViewSetup())

        $plugin = new Zend_Controller_Plugin_ErrorHandler();
		$plugin->setErrorHandlerController('error')
		       ->setErrorHandlerAction('error');
       		
		$front->registerPlugin(new My_Plugin_Initialize(APPLICATION_ENV))
		      ->registerPlugin(new My_Plugin_ViewSetup())
		      ->addControllerDirectory(APPLICATION_PATH . '/controllers');
//			  ->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());

        $front->registerPlugin($plugin);
		
        $router = $front->getRouter(); // returns a rewrite router by default
		
		$request = $front->getRequest();

		require_once 'Zend/Controller/Action/HelperBroker.php';
		Zend_Controller_Action_HelperBroker::addPrefix('My_Helper');
	}
	
	protected function _initViewHelpers()
	{
		$view = new Zend_View();
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
	
		// load jQuery
		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
//		$view->jQuery()->addStylesheet('/css/smoothness/jquery-ui-1.8.23.custom.css')
//		->setLocalPath('/js/jquery/jquery-1.8.1.js')
//		->setUiLocalPath('/js/jquery-ui-1.8.23.custom.min.js');

        $view->jQuery()->addStylesheet('/js/jquery-ui-1.8.23.custom/css/smoothness/jquery-ui-1.8.23.custom.css')
            ->setLocalPath('/js/jquery/jquery-1.8.2.js')
            ->setUiLocalPath('/js/jquery-ui-1.8.23.custom/js/jquery-ui-1.8.23.custom.min.js');

        $view->jQuery()->enable();
        $view->jQuery()->uiEnable();

        $viewRenderer->setView($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

	}
	
	/**
	 *
	 */
	public function _initTranslateCache()
	{
	    /*
	     * Get the resource options from the config file.
	    */
	    $options = $this->getOptions();
	
	    /*
	     * Call the cache factory with options and set the
	    * resulting object in the registry.
	    */
	    Zend_Registry::set(
	            'translateCache',
	            Zend_Cache::factory(
	                    $options['translateCache']['frontEnd'],
	                    $options['translateCache']['backEnd'],
	                    $options['translateCache']['frontEndOptions'],
	                    $options['translateCache']['backEndOptions']
	            )
	    );
	}
	
	/**
	 *
	 */
	public function _initTranslate()
	{
	    /*
	     * Give the cache object to zend translate so
	    * we can cache the language files.
	    */
	    $front = Zend_Controller_Front::getInstance();
	    $front->registerPlugin(new My_Plugin_Translate());
	}
	
	/**
	 * Set the cache object in registry for search.
	 */
	protected function _initSearchCache()
	{
	    $options = $this->getOptions();
	    Zend_Registry::set(
	            'searchCache',
	            Zend_Cache::factory(
	                    $options['searchCache']['frontEnd'],
	                    $options['searchCache']['backEnd'],
	                    $options['searchCache']['frontEndOptions'],
	                    $options['searchCache']['backEndOptions']
	            )
	    );
	}
	
	/**
	 * Initialize the limited rollout ini
	 */
	public function _initLimitedRollout() {
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/limited-rollout.ini', APPLICATION_ENV);
		Zend_Registry::set(
			'limited-rollout',
			$config->toArray()
		);
	}
	
	/**
	 * Initialize the limited rollout ini
	 */
	public function _initCreateOldForms() {
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/create-old-forms.ini', APPLICATION_ENV);
		Zend_Registry::set(
		'create-old-forms',
		$config->toArray()
		);
	}
	
	/**
	 * Initialize IEP URLs
	 */
	public function _initIepUrls() {
		$options = $this->getOptions();
		Zend_Registry::set(
			'iep-urls',
			array('studentOptions' => $options['iep']['urls']['studentOptions'])
		);
	}
	
 }
