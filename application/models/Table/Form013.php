<?php

/**
 * Form013
 *  
 * @author jlavere
 * @version 
 */
	
class Model_Table_Form013 extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_form_013';
    protected $_primary = 'id_form_013';
    protected $_dependentTables = array(
                'Model_Table_Form013Parents',
                'Model_Table_Form013Goals',
                'Model_Table_Form013Services',
                'Model_Table_Form013TranPlan',
                'Model_Table_Form013TeamOther',
                'Model_Table_Form013TeamMembers',
    );

    function getMostRecentIfspState($id_student){
        $sql="select * from iep_form_013 where id_student='$id_student' and status='Final' order by meeting_date DESC";
        $forms=$this->db->fetchAll($sql);
    
        if (!empty($forms)) {
            return $forms;
        }
        else {
            return null;
        }
    
    }   // end of the function
    
    function dupe($document, $ifspType, $dupeFull = 0) {

        $sessUser = new Zend_Session_Namespace('user');
    	$stmt = $this->db->query("SELECT dupe_ifsp('$document', '$sessUser->sessIdUser', '$ifspType', 'iep_form_013', 'id_form_013', '$dupeFull')");
        if($result = $stmt->fetchAll()) {
            return $result[0]['dupe_ifsp'];
        } else {
        	return false;
        }
    }
//    function dupeFull($document, $ifspType) {
//
//    	$sessUser = new Zend_Session_Namespace('user');
//    	$stmt = $this->db->query("SELECT dupe_ifsp('$document', '$sessUser->sessIdUser', '$ifspType', 'iep_form_013', 'id_form_013', '1')");
//        if($result = $stmt->fetchAll()) {
//            return $result[0]['dupe_iep_full'];
//        } else {
//        	return false;
//        }
//    }

    
  
    
    function getArchivedIfsps($masterID, $limitFinalized=true) {

        $db = Zend_Registry::get('db');

        $id = $db->quote(intval($masterID));
//        Zend_Debug::dump($id);
        $select = $db->select()
            ->from( 'iep_form_013',
                array(
                    '*',
                    'form_no' => new Zend_Db_Expr("CAST('013' as CHAR( 3 ) )"),
                    'timestamp_created',
                    'create_date' => new Zend_Db_Expr("to_char(timestamp_created,'MM/DD/YYYY')"),
                    'id' => 'id_form_013',
                    'status',
                    'title' => '',
                    'page_status',
                    'id_case_mgr',
                    'date_null' => new Zend_Db_Expr("CASE WHEN date_notice IS NULL THEN 0 ELSE 1 END")
                )
            )
        ->where("ifsp_master_parent(id_form_013) = ifsp_master_parent(?)", $id)
        ->where("id_form_013 != ?", $id);

        if($limitFinalized) {
            $select->where("status = 'Final'");

        }
//        echo $select;die;
        $results = $db->fetchAll($select);
        return $results;


    }

    /**
     * @param $pkForm013
     * @param string $status
     * @return array|bool of ifsp services
     */
    function getServices($pkForm013, $status = 'Final') {
        
        $form013ServicesObj = new Model_Table_Form013Services();
        $mostRecent013ServicesRows = $form013ServicesObj->fetchAll(
            $form013ServicesObj->select()
                ->where("id_form_013 = ?", $pkForm013)
                ->where("status != 'deleted' or status is null")
        );
        if(count($mostRecent013ServicesRows)) {
            $mostRecent013Services = $mostRecent013ServicesRows->toArray();
            return $mostRecent013Services;
        }
        return false;

    }

}

