<?php
/**
 * Model_Table_Admin
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Announcements extends Model_Table_AbstractIepForm
{

    public function announcementsList()
    {

        $db = Zend_Registry::get('db');
        $select = $db->select()
                   ->from( 'iep_messages_new', array('*', "to_char(display_until_date, 'MM/DD/YYYY') as display_until_date_c") )
                   ->where('display_until_date = ?', date('m/d/Y'))
                   ->order('display_until_date desc');
        $result = $db->fetchAll($select);
       return array($result);
    }

}
