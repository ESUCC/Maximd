<?php
require_once 'Zend/Db/Table/Abstract.php';

/**
* My_Session_SaveHandler_Db -> copied from Hoge_Session_SaveHandler_Db
* http://f-pig.blogspot.com/2008/01/try-zend-framework-vol11-save-session.html
*/
class My_Session_SaveHandler_Db extends Zend_Db_Table_Abstract implements Zend_Session_SaveHandler_Interface
{
   /**
    * constatns
    */
   // session table name
   const TABLE_NAME         = 'sessions';
   // primary key column name
   const COLUMN_PRIMARY_KEY = 'id';
   // lifetime column name
   const COLUMN_LIFETIME    = 'lifetime';
   // data column name
   const COLUMN_DATA        = 'data';

   /**
    * primary key
    * @var string
    */
   protected $_primary = self::COLUMN_PRIMARY_KEY;

   /**
    * table name
    * @var string
    */
   protected $_name = self::TABLE_NAME;

   /**
    * table columns.
    * @var array
    */
   protected $_columnMap = array(
       self::COLUMN_PRIMARY_KEY => self::COLUMN_PRIMARY_KEY,
       self::COLUMN_LIFETIME    => self::COLUMN_LIFETIME,
       self::COLUMN_DATA        => self::COLUMN_DATA
   );

   /**
    * session maxlifetime
    * @var null|intger
    */
   protected $_lifetime = null;

   /**
    * constructor
    * @param string $table      Session table name
    * @param array  $columnMap  Session table column names
    */
   public function __construct($table = null, $columnMap = null)
   {
       // set table name
       if ($table) {
           $this->_name = $table;
       }

       if ($columnMap) {
           // set primary key name
           if (isset($columnMap[self::COLUMN_PRIMARY_KEY])) {
               $this->_columnMap[self::COLUMN_PRIMARY_KEY] = $columnMap[self::COLUMN_PRIMARY_KEY];
               $this->_primary = $columnMap[self::COLUMN_PRIMARY_KEY];
           }
           // set lifetime column name
           if (isset($columnMap[self::COLUMN_LIFETIME])) {
               $this->_columnMap[self::COLUMN_LIFETIME] = $columnMap[self::COLUMN_LIFETIME];
           }
           // set session data column name
           if (isset($columnMap[self::COLUMN_DATA])) {
               $this->_columnMap[self::COLUMN_DATA] = $columnMap[self::COLUMN_DATA];
           }
       }
       
       parent::__construct();
   }

   /**
    * Set session max lifetime.
    * @param $lifetime
    */
   public function setLifetime($lifetime)
   {
       $this->_lifetime = $lifetime;
   }

   /**
    * Open Session - retrieve resources
    *
    * @param string $save_path
    * @param string $name
    */
   public function open($save_path, $name)
   {
       return true;
   }

   /**
    * Close Session - free resources
    *
    */
   public function close()
   {
       return true;
   }

   /**
    * Read session data
    *
    * @param string $id
    * @return string
    */
   public function read($id)
   {
       $return = '';
       $where = $this->getAdapter()->quoteInto($this->_columnMap[self::COLUMN_PRIMARY_KEY] . "=?", $id);
       if ($row = $this->fetchRow($where)) {
       
           // custom srs addition
           if($row->expiration > time() && $row->status == "Active") {
           } else {
                // this is how the system knows that the session is expired.
                $row->{$this->_columnMap[self::COLUMN_DATA]} = 'expired|a:1:{s:3:"exp";i:1;}' . $row->{$this->_columnMap[self::COLUMN_DATA]};
                #Zend_Session::destroy(1);
                #return false;
           }
           
           $return = $row->{$this->_columnMap[self::COLUMN_DATA]};
       }
       
       return $return;
   }

   /**
    * Write Session - commit data to resource
    *
    * @param string $id
    * @param mixed $data
    * @return bool
    */
   public function write($id, $data)
   {
       $return = false;
       date_default_timezone_set('America/Chicago');


       // custom srs addition
       // get timeout from config and write it into the session record
       $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
       $expiration = time() + $config->srsSession->lifeSeconds;

       // when the user is authenticated, siteaccessgranted flag is set to true
       $defaultNamespace = new Zend_Session_Namespace();
       if(isset($defaultNamespace->siteaccessgranted) && true === $defaultNamespace->siteaccessgranted) {
			$siteaccessgranted = 't';
       } else {
       		$siteaccessgranted = 'f';
       }
       
       
       $dataSet = array(
           $this->_columnMap[self::COLUMN_PRIMARY_KEY] => $id,
           $this->_columnMap[self::COLUMN_LIFETIME]    => date("Y-m-d H:i:s", time()),
           $this->_columnMap[self::COLUMN_DATA]        => $data,
           
           // custom srs addition
           'expiration'        => $expiration,
           'siteaccessgranted' => $siteaccessgranted
        );
//        Zend_Debug::dump((isset($data['siteaccessgranted'])?$data['siteaccessgranted']:false));die();
        // put the user id in the session record
        $sessUser = new Zend_Session_Namespace('user');
        if(isset($sessUser->id_personnel)) {
            $dataSet['id_user'] = $sessUser->id_personnel;
        }
       
       $where = $this->getAdapter()->quoteInto($this->_columnMap[self::COLUMN_PRIMARY_KEY] . "=?", $id);
               
       if ($this->fetchRow($where))
       {
           $return = ($this->update($dataSet, $where)) ? true : false;
       } else {
           $return = ($this->insert($dataSet)) ? true: false;
       }

       return $return;
   }

   /**
    * Destroy Session - remove data from resource for
    * given session id
    *
    * @param string $id
    * @return bool
    */
   public function destroy($id)
   {
  
       $dataSet = array(
           // custom srs addition
//           'expiration'        => NULL,
           'note'            => 'Expired by destroy().',
       	   'status'            => 'Expired',
           'siteaccessgranted' => 'f'
       );
       $where = $this->getAdapter()->quoteInto($this->_columnMap[self::COLUMN_PRIMARY_KEY] . "=?", $id);
       $return = ($this->update($dataSet, $where)) ? true : false;
       return $return;
       //echo $return; die();
       //return ($this->delete($where)) ? true : false;
       //return true;
   }

   /**
    * Garbage Collection - remove old session data older
    * than $maxlifetime (in seconds)
    *
    * @param int $maxlifetime
    * @return bool
    */
   public function gc($maxlifetime)
   {
//        $lifetime = ($this->_lifetime) ? $this->_lifetime : $maxlifetime;
//        $expiry = date("Y-m-d H:i:s", mktime() - $lifetime);
//        $where = $this->getAdapter()->quoteInto($this->_columnMap[self::COLUMN_LIFETIME] . "<=?", $expiry);         
//        return ($this->delete($where)) ? true : false;

       $dataSet = array(
           // custom srs addition
//           'expiration'        => NULL,
           'note'            => 'Expired by garbage collector.',
           'status'            => 'Expired'
           );
       $where = $this->getAdapter()->quoteInto($this->_columnMap[self::COLUMN_LIFETIME] . "<=?", $expiry);
       $return = ($this->update($dataSet, $where)) ? true : false;
   }

}
