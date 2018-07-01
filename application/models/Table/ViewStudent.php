<?php
class Model_Table_ViewStudent extends Zend_Db_Table_Abstract {
 
    protected $_name = 'view_student';
    protected $_primary = 'id_student';
    
    protected $_dependentTables = array(
	               'Model_Table_SchoolReportDates'
    );
    
}