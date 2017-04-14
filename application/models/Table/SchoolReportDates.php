<?php

/**
 * Form001
 *  
 * @author jlavere
 * @version 
 */
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_SchoolReportDates extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_school_report_date';
    protected $_primary = 'id_school_report_date';
    protected $_sequence = 'iep_school_report_date_id_school_report_date_seq';
    protected $_referenceMap    = array(
        'Model_Table_ViewAllStudent' => array(
            'columns'           => array('id_county', 'id_district', 'id_school'),
            'refTableClass'     => 'Model_Table_ViewAllStudent',
            'refColumns'        => array('id_county', 'id_district', 'id_school')
        )
    );
    //    protected $_dependentTables = array(
//							'Form004TeamMember', 
//    						);

}

