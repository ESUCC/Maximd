<?php

/**
 * Model_Table_PrivilegeTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_StudentChartTemplate extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
    protected $_name = 'student_chart_template';
    protected $_primary = 'id_student_chart_template';
    protected $_sequence = 'student_chart_template_id_student_chart_template_seq';

    public function getMyCharts($idPersonnel) {

        $where = $this->getAdapter()->quoteInto('status = \'Active\' and id_personnel = ?', $idPersonnel);
        return $this->fetchAll($where);
    }
}
