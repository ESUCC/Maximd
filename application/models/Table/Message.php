<?php
/**
 * iep_message
 * @author jlavere
 */
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Message extends Model_Table_AbstractIepForm
{
    protected $_name = 'iep_message';
    protected $_primary = 'id_message';
}
