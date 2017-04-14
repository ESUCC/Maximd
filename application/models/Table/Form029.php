<?php

/**
 * Form003
 *  
 * @author jlavere
 * @version 
 */
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Form029 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_029';
    protected $_primary = 'id_form_029';
	protected $_dependentTables = array(
    	    'Model_Table_Form029SpecialEdTeacher',
    	    'Model_Table_Form029SpecialKnowledge',
    	    'Model_Table_Form029SchoolRepresentative',
    	    'Model_Table_Form029EvalResults',
	        'Model_Table_Form029GenEdTeacher',
			'Model_Table_Form029OtherAttendee',
			'Model_Table_Form029OutsideAgency',
			'Model_Table_Form029TeamMemberAbsences',
			'Model_Table_Form029ContactAttempts'
	);
    
}

