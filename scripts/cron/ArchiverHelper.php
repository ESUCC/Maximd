<?php
class ArchiverHelper
{
    protected $log = null;

    /**
     * @param null $log
     */
    public function setLog($log)
    {
        $this->log = $log;
    }

    /**
     * @param null $log
     */
    static public function addLogStatic($message, $newLine = true)
    {
//        $this->log .= $message;
//        if($newLine) $this->log .= "\n";

    }

    /**
     * @return null
     */
    public function getLog()
    {
        return $this->log;
    }

    public static function seetupDb($config)
    {
        $db = Zend_Db::factory($config->db2);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        Zend_Registry::set('db', $db);
        
        self::addLogStatic("Database config set up.");    }

    public static function setupTranslate($config, $formNumber, $session)
    {
        // Call the cache factory with options and set the
        // resulting object in the registry.
        $frontEnd = $config->translateCache->frontEnd;
        $backEnd = $config->translateCache->backEnd;
        $frontEndOptions = $config->translateCache->frontEndOptions;
        $backEndOptions = $config->translateCache->backEndOptions;

        $cache = Zend_Cache::factory($frontEnd, $backEnd, $frontEndOptions->toArray(),
                        $backEndOptions->toArray());
        self::addLogStatic("Translate cache set.");

        Zend_Registry::set('translateCache', $cache);
        self::addLogStatic("Translate cache added to the registry.");

        // Give the cache object to zend translate so
        // we can cache the language files.
        Zend_Translate::setCache(Zend_Registry::get('translateCache'));
        $translate = new Zend_Translate(array('adapter' => 'tmx',
        'content' => APPLICATION_PATH . '/translation/global.tmx',
        'scan' => Zend_Translate::LOCALE_FILENAME,
        'disableNotices' => true));

        Zend_Registry::set('locale', $session->locale);

        if (! $translate->isAvailable(Zend_Registry::get('locale')))
            $translate->setLocale('en');
        else
            $translate->setLocale(Zend_Registry::get('locale'));

        for ($i = 0; $i < 9; $i ++) {
            self::addTmxToTranslation(APPLICATION_PATH . '/translation/form' .
                            $formNumber . '/page' . $i .'.tmx', $translate);
        }

        Zend_Registry::set('Zend_Translate', $translate);
        self::addLogStatic("Zend Translate configured.");

    }
    
    public static function setupRegistry()
    {
        if (!class_exists('Zend_Registry', false) || !Zend_Registry::isRegistered('config')) {
            if (!class_exists('Zend_Registry')) {
                $paths = array('.', '/usr/local/zend/share/ZendFramework/library',
                APPLICATION_PATH . '/../library');
                ini_set('include_path', implode(PATH_SEPARATOR, $paths));
                require_once 'Zend/Loader/Autoloader.php';
                $loader = Zend_Loader_Autoloader::getInstance();
                $loader->registerNamespace(array('App_'));
                $loader->registerNamespace(array('My_'));
            }
        }
    }
    
    private static function addTmxToTranslation($tmxFile, $translate) 
    {
        if (file_exists($tmxFile)) {
            $actionTranslation = new Zend_Translate(array('adapter' => 'tmx',
                                                          'content' => $tmxFile,
                                                          'scan' => Zend_Translate::LOCALE_FILENAME,
                                                          'disableNotices' => true));
    
            $translate->addTranslation(array('content' => $actionTranslation));
        }
    }
    
    public static function formsToBeArchived($tableName, $keyName, $dateField = 'date_notice', $beforeDate)
    {
        $columnExistsQuery = "SELECT attname FROM pg_attribute WHERE attrelid =
        (SELECT oid FROM pg_class WHERE relname = '$tableName') AND attname = '$dateField';";
        $exists = Zend_Registry::get('db')->query($columnExistsQuery)->fetch();

        $limitToStudent = null;
        $studentSql = '';
        if(!is_null($limitToStudent)) {
            $studentSql = " and id_student = '$limitToStudent' ";
        }

        if(null!=$beforeDate && $exists!=false) {
            $stmt = "SELECT version_number, pdf_archived, $keyName as id, $keyName, id_student, $dateField as date FROM $tableName
            where status = 'Final' and $dateField < '$beforeDate' $studentSql order by $dateField desc;";
        } else {
            $stmt = "SELECT version_number, pdf_archived, $keyName as id, $keyName, id_student, date_notice as date FROM $tableName
            where status = 'Final' and date_notice < '$beforeDate' $studentSql order by date_notice desc;";
//            echo "Column $dateField does not exist for table $tableName, so we are using date_notice.\n";
        }
//        echo "$stmt\n";
        $result = Zend_Registry::get('db')->query($stmt);
        return $result->fetchAll();
    }
    public static function formsToBeMovedToArchivDb($tableName, $keyName)
    {
        $stmt = "SELECT $keyName, id_student FROM $tableName
        where pdf_archived = true;";
//        echo "$stmt\n";
        $result = Zend_Registry::get('db')->query($stmt);
        return $result->fetchAll();
    }

    public static function getQueueStatus() {
        $result = Zend_Registry::get('db')->query("select * from srs_queue where queue_name = 'archive' ;");
	    return $result->fetch();
    }
}