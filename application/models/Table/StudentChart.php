<?php

/**
 * Model_Table_PrivilegeTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_StudentChart extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
    protected $_name = 'student_chart';
    protected $_primary = 'id_student_chart';
    protected $_sequence = 'student_chart_id_student_chart_seq';

}
