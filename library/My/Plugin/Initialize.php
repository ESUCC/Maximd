<?php
/** Zend_Controller_Plugin_Abstract */
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * Plugin to initialize application state
 * 
 * @uses       Zend_Controller_Plugin_Abstract
 * @category   My
 * @package    My_Plugin
 * @license    New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version    $Id: $
 */
class My_Plugin_Initialize extends Zend_Controller_Plugin_Abstract
{
    /**
     * Constructor
     * 
     * @param  string $basePath Base path of application
     * @param  string $env Application environment
     * @return void
     */
    public function __construct($env = 'production')
    {
        $this->env   = $env;
        $this->initConfig();
        $this->front = Zend_Controller_Front::getInstance();
    }
    
    /**
     * Route Startup handler
     * 
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
    	$this->initControllers()
             ->initLog()
             ->initDb()
             ->initView()
             ->initCache();
    }

    /**
     * Initialize configuration
     * 
     * @return My_Plugin_Initialize
     */
    public function initConfig()
    {
        $this->config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $this->env);
        Zend_Registry::set('config', $this->config);
        return $this;
    }

    /**
     * Initialize controller directories
     * 
     * @return My_Plugin_Initialize
     */
    public function initControllers()
    {
        $this->front->setControllerDirectory($this->config->appPath . '/controllers', 'default');
        return $this;
    }

    /**
     * Initialize logger(s)
     * 
     * @return My_Plugin_Initialize
     */
    public function initLog()
    {
        $writer = new Zend_Log_Writer_Firebug();
        $log    = new Zend_Log($writer);

        $writer->setPriorityStyle(8, 'TABLE');
        $log->addPriority('TABLE', 8);

        Zend_Registry::set('log', $log);
        return $this;
    }

    /**
     * Initialize caching
     * 
     * @return My_Plugin_Initialize
     */
    public function initCache()
    {
        $config = $this->config->cache;
        $this->cache = $this->_getCache($config);
        Zend_Registry::set('cache', $this->cache);
        return $this;
    }

    /**
     * Initialize database
     * 
     * @return My_Plugin_Initialize
     */
    public function initDb()
    {
//        $config   = $this->config->db;
//        $cache    = $this->_getCache($config->cache);
//        $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
//        $db       = Zend_Db::factory($config->cxn);
//
//        $profiler->setEnabled($config->profiler->enabled);
//        $db->setProfiler($profiler);
//        Zend_Db_Table_Abstract::setDefaultAdapter($db);
//        Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
        
        /*
         * 20081117 jlavere - reconfigure to use postgres
         */
        $config   = $this->config->db2;
//        print_r($config);
        $db = Zend_Db::factory($config);    // returns instance of Zend_Db_Adapter class
        Zend_Registry::set('db', $db);        
        
        Zend_Db_Table_Abstract::setDefaultAdapter($db);

        //
        // 20090414 jlavere - convert session handler to use db
        //
		require_once 'My/Session/SaveHandler/Db.php';
        $table = 'iep_session_zend';
        $columns = array('id'=>'id_session', 'lifetime'=>'timestamp_last_mod', 'data'=> 'zfvalue');
        $saveHandler = new My_Session_SaveHandler_Db($table, $columns);
        Zend_Session::setSaveHandler($saveHandler);

        return $this;
    }

    /**
     * Initialize view and layouts
     * 
     * @return My_Plugin_Initialize
     */
    public function initView()
    {
        $layout = Zend_Layout::startMvc(array(
            'layoutPath' => $this->config->appPath . '/layouts/scripts'
        ));

        $view = $layout->getView();
        $view->addHelperPath($this->config->basePath . '/library/My/View/Helper/', 'My_View_Helper');
        $view->addHelperPath($this->config->basePath . '/library/Zend/View/Helper/', 'Zend_View_Helper');
        $view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
        
        // give a default setting for this variable because it's not set in the test environment        
        if (!isset($_SERVER['HTTP_HOST'])) { $_SERVER['HTTP_HOST'] = "xanthos.soliantconsulting.com"; }

        if($this->config->secure)
        {
            $view->url_server = 'https://' . $_SERVER['HTTP_HOST'];
        } else {
            $view->url_server = 'http://' . $_SERVER['HTTP_HOST'];
        }
        
        /*
         * 20090421 jlavere - add DOC_ROOT to config and then into view here
         */

        $view->DOC_ROOT = $this->config->DOC_ROOT;

        // PDF CREATION
        define("PRINCE_PATH", $this->config->prince->path);
        define("TEMP_DIR", $this->config->prince->temp);

        define("appPath", $this->config->appPath);
        define("libPath", $this->config->libPath);
        define("pubPath", $this->config->pubPath);
        
        // zend webserver
        define("zendRoot", $this->config->zendRoot);
        define("DOC_ROOT", $this->config->DOC_ROOT);
        define("NONZEND_ROOT", $this->config->NONZEND_ROOT);

        // added all privs as constants to facilitate future function writing sl 1-11-2003
        define( "UC_SA",  1);       // system admin
        define( "UC_DM",  2);       // district manager
        define( "UC_ADM", 3);       // associate district manager
        define( "UC_SM",  4);       // school manager
        define( "UC_ASM", 5);       // associate school manager
        define( "UC_CM",  6);       // case manager
        define( "UC_SS",  7);       // school staff
        define( "UC_SP",  8);       // specialist
        define( "UC_PG",  9);       // parent/guardian
        define( "UC_SC",  10);      // service coordinator
        
        
        /*
         * 20081117 jlavere - add helper folder
         */
        $view->addHelperPath('My/Helper/', 'My_Helper');
                
        Zend_Dojo::enableView($view);
        $view->doctype('XHTML1_STRICT');
        $view->headTitle($this->config->appTitle); // overridden in abstract form controller
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');
		
        // get refresh code for externals
        // changing this code will cause clients 
        // to get fresh coppies of the external files
        $refreshCode = '?refreshCode=' . $this->config->externals->refresh;
        
        if('production' == $this->env) {
	        // include the compressed version from the dojo custom build
	        $view->headLink()->appendStylesheet($this->config->dojoPath.'/../srs_forms/styles.css'.$refreshCode);
	        $view->headLink()->appendStylesheet('/css/srs_style_additions.css'.$refreshCode); // custom additions
        	$view->headLink()->appendStylesheet('/css/srs_style_forms.css'.$refreshCode); // custom additions
	        
	        $view->dojo()->setDjConfigOption('usePlainJson', true)
	            ->setDjConfigOption('isDebug', $this->config->view->dojo->isDebug)
	            ->setLocalPath($this->config->dojoPath.'/dojo.js'.$refreshCode)
				->addLayer($this->config->dojoPath.'/../srs_forms/includes.js'.$refreshCode)
	            ->disable();
	        
        } elseif(file_exists($this->config->pubPath.$this->config->dojoPath.'/../srs_forms/styles.css') &&
        		 file_exists($this->config->pubPath.$this->config->dojoPath.'/../srs_forms/includes.js')) {
	        
			// include the compressed version from the dojo custom build
	        $view->headLink()->appendStylesheet($this->config->dojoPath.'/../srs_forms/styles.css'.$refreshCode);
	        $view->headLink()->appendStylesheet('/css/srs_style_additions.css'.$refreshCode); // custom additions
        	$view->headLink()->appendStylesheet('/css/srs_style_forms.css'.$refreshCode); // custom additions

	        $view->dojo()->setDjConfigOption('usePlainJson', true)
	            ->setDjConfigOption('isDebug', $this->config->view->dojo->isDebug)
	            ->setLocalPath($this->config->dojoPath.'/dojo.js'.$refreshCode)
				->addLayer($this->config->dojoPath.'/../srs_forms/includes.js'.$refreshCode)
	            ->disable();
        	
        } else {
        	// these get rolled into production srs_forms/includes.js
	        $view->headScript()->appendFile('/js/srs_forms/date_format.js'.$refreshCode);
	        $view->headScript()->appendFile('/js/srs_forms/timer_countdown_multiple.js'.$refreshCode);
        	
	        // these get rolled into production srs_forms/styles.css
	        $view->headLink()->appendStylesheet($this->config->dojoPath.'/resources/dojo.css');
	        $view->headLink()->appendStylesheet($this->config->dojoPath.'/../dijit/themes/tundra/tundra.css');
	        $view->headLink()->appendStylesheet('/css/srs_style_additions.css'.$refreshCode); // custom additions
        	$view->headLink()->appendStylesheet('/css/srs_style_forms.css'.$refreshCode); // custom additions

	        $view->dojo()->setDjConfigOption('usePlainJson', true)
	            ->setDjConfigOption('isDebug', $this->config->view->dojo->isDebug)
	            ->setLocalPath($this->config->dojoPath.'/dojo.js')
				->registerModulePath('soliant', '../../soliant')
	            ->disable();
        }
        
        

        return $this;
    }

    /**
     * Retrieve cache object based on configuration
     * 
     * @param  Zend_Config $config 
     * @return Zend_Cache
     */
    protected function _getCache(Zend_Config $config)
    {
        $cache = Zend_Cache::factory(
            $config->frontendName,
            $config->backendName,
            $config->frontendOptions->toArray(),
            $config->backendOptions->toArray()
        );
        return $cache;
    }
}
