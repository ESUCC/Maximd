<?php


class My_Validate_SingleEmailConfirmation extends Zend_Validate_Abstract
{
    const NOT_MATCH = 'notMatch'; 
    protected $_messageTemplates = array(self::NOT_MATCH => 'There is already a user in the system with this email.'); 

    public function __construct()
    { 
    }

    public function isValid($value)
    {
        $value = (string) $value;
        $this->_setValue($value);

        if ($this->getLoggedInUser($value)) {
            return true;
        }
    
        $this->_error(self::NOT_MATCH);
        return false;
    }

    
    public function getLoggedInUser($email)
    {        
        $sqlStmt = "select ";
        $sqlStmt .= "    count(1) ";
        $sqlStmt .= "from ";
        $sqlStmt .= "    webuser ";
        $sqlStmt .= "where ";
        $sqlStmt .= "    email = '".pg_escape_string($email)."';";
        //echo "sql: $sqlStmt<BR>";
        $db = Zend_Registry::get('db');
        $result = $db->fetchAll($sqlStmt);
        
        if($result[0]['count'] == 0) return true;
        
        return false;
    }
    
}
