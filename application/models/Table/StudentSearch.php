<?php

/**
 * StudentTable
 *  
 * @author jesse
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_StudentSearch extends Model_Table_AbstractIepForm {
	/**
	 * The default table name 
	 */
	protected $_name = 'student_search';
	protected $_primary = 'id_student_search';
	
	public function save($id, $data) {
		$table = new Model_Table_StudentSearch();
        $where = $table->getAdapter()->quoteInto('id_student_search = ?', $id);
        $saveData = array(
			'limitto'=> $data['limitto'],        
			'sort_order'=> $data['sort_order'],        
			'recs_per'=> $data['recs_per'],        
			'status'=> $data['status']        
        );
//        Zend_Debug::dump($saveData);die();
        $table->update($saveData, $where);
        
        // save sub forms
       	// subform conditions
		$rowsTable = new Model_Table_StudentSearchRow();
        
		if(is_array($data['id_student_search_rows'])) {
	       	$rowCount = count($data['id_student_search_rows']);
	       	for($i=0; $i<$rowCount;$i++) {
		        $where = $rowsTable->getAdapter()->quoteInto('id_student_search_rows = ?', $data['id_student_search_rows'][$i]);
		        $saveRowData = array(
					'search_field'=> $data['search_field'][$i],
					'search_value'=> $data['search_value'][$i]
		        );
		        $rowsTable->update($saveRowData, $where);
	       	}
		} elseif('' != $data['id_student_search_rows']) {
	        $where = $rowsTable->getAdapter()->quoteInto('id_student_search_rows = ?', $data['id_student_search_rows']);
	        $saveRowData = array(
				'search_field'=> $data['search_field'],
				'search_value'=> $data['search_value']
	        );
	        $rowsTable->update($saveRowData, $where);
		}
	}

	public function mySearches($id_personnel, $prefix) {
	    $db = Zend_Registry::get('db');
	    
	    $select = $db->select()
	                 ->from( array('ss'=>$this->_name),
			            array(
			                '*',
			            )
	                 )
	                ->where("ss.id_personnel = '$id_personnel'" );
		$results = $db->fetchAll($select);
		if(!$results) {
			// no search row exists
			// insert search row and search row row
			$newId = $this->insert(array('id_personnel'=>$id_personnel));
			$search = $this->find($newId);
			$rowO = new Model_Table_StudentSearchRow();
			$rowO->insertRow(array('id_student_search'=>$search[0]->id_student_search));
			$results = $db->fetchAll($select);
		} 
		// for each search found, attach the prefix to the key
		$rowO = new Model_Table_StudentSearchRow(); 
		foreach($results as $key => $searchData) {
			$searchRows = $rowO->fetchAll("id_student_search = '{$searchData['id_student_search']}'")->toArray();
			$results[$key]['subforms'][$prefix] = $searchRows;//$this->array_add_prefix($searchRows, $prefix);
		}
	    return $results;
	    
	}
	public function getSearch($id_student_search, $prefix) {
	    $db = Zend_Registry::get('db');
	    
		$results = $this->find($id_student_search)->toArray();
		if(!$results) {
			// no search row exists
			// insert search row and search row row
			$newId = $this->insert(array('id_personnel'=>$id_personnel));
			$search = $this->find($newId);
			$rowO = new Model_Table_StudentSearchRow();
			$rowO->insertRow(array('id_student_search'=>$search[0]->id_student_search));
			$results = $db->fetchAll($select);
		}

		// for each search found, attach the
		$rowO = new Model_Table_StudentSearchRow(); 
		foreach($results as $key => $searchData) {
			$searchRows = $rowO->fetchAll("id_student_search = '{$searchData['id_student_search']}'")->toArray();
			$results[$key]['subforms'][$prefix] = $searchRows;//$this->array_add_prefix($searchRows, $prefix);
		}
				
	    return $results[0]; //array_merge($results[0], $this->prefixIndex($results, $prefix));
	    
	}
	/*
     * put a prefix in front of each key
     */
	function array_add_prefix($array, $prefix, $startingIndex = 1, $addUnderscore = true) {
		$retArr = array();
		foreach ($array as $k => $v) {
			if($addUnderscore) {
				$retArr[$prefix.'_'.$startingIndex] = $v;
			} else {
		   		$retArr[$prefix.$startingIndex] = $v;
			}
			$startingIndex++;
		}
//		$retArr[$prefix.'_count'] = $startingIndex-1; 
		return $retArr;
	}



	public function studentsList($id_student_A, $id_student_B) {
	    $db = Zend_Registry::get('db');

            $select = $db->select()
                         ->from( array('s' => 'iep_student'), array('*'))
			 ->joinLeft( array('c' => 'iep_county'), 'c.id_county = s.id_county', array('c.name_county'))
			 ->joinLeft( array('sc' => 'iep_school'), 'sc.id_school = s.id_school and s.id_county = sc.id_county and s.id_district = sc.id_district', array('sc.name_school'))
			 ->joinLeft( array('p' => 'iep_personnel'), 'p.id_personnel = s.id_case_mgr', array('mgr_name_first' => 'p.name_first', 'mgr_name_last' => 'p.name_last'))
        		 ->where( "s.id_student = ?", $id_student_A );
	    $resultA = $db->fetchAll($select);

	    if (count($resultA) == 0) $resultA[0]["id_student"] = 0;

            $select = $db->select()
                         ->from( array('s' => 'iep_student'), array('*'))
			 ->joinLeft( array('c' => 'iep_county'), 'c.id_county = s.id_county', array('c.name_county'))
			 ->joinLeft( array('sc' => 'iep_school'), 'sc.id_school = s.id_school and s.id_county = sc.id_county and s.id_district = sc.id_district', array('sc.name_school'))
			 ->joinLeft( array('p' => 'iep_personnel'), 'p.id_personnel = s.id_case_mgr', array('mgr_name_first' => 'p.name_first', 'mgr_name_last' => 'p.name_last'))
            		 ->where( "id_student = ?", $id_student_B );
	    $resultB = $db->fetchAll($select);
	    if (count($resultB) == 0) $resultB[0]["id_student"] = 0;

	    return array_merge($resultA, $resultB);
	    
	}

	public function studentsMerge($id_student_A, $id_student_B, $cm_merge) {
	    $database = Zend_Registry::get('db');


//	    print $id_student_A . "|" . $id_student_B;

	    if($cm_merge == 1) {
		$sqlStmt = "SELECT merge_students_cm(" . $id_student_A . ", " . $id_student_B . ")";
	      } else {
		$sqlStmt = "SELECT merge_students(" . $id_student_A . ", " . $id_student_B . ")";
	    }
	    $result = $database->fetchRow($sqlStmt);

	    // Get new unique_id_state
	    $sqlStmt = "select unique_id_state as uniquieid from iep_student where id_student = '" . $id_student_B . "'";
	    $resultUIDS = $database->fetchRow($sqlStmt);

	    // Delete parent
	    $sqlStmt = "delete from iep_guardian where id_student = '" . $id_student_B . "'";
	    $result = $database->fetchRow($sqlStmt);

	    // Delete student team
	    $sqlStmt = "UPDATE iep_student_team SET status = 'Inactive' where id_student = '" . $id_student_B . "'";
	    $result = $database->fetchRow($sqlStmt);

	    // DELETE LOGS
	    $sqlStmt = "UPDATE iep_log SET id_student = '" . $id_student_A . "' where id_student = '" . $id_student_B . "'";
	    $result = $database->fetchRow($sqlStmt);

	    // update snapshots
	    $sqlStmt = "UPDATE iep_sesis_snapshot2 SET id_student = '" . $id_student_A . "' where id_student = '" . $id_student_B . "'";
	    $result = $database->fetchRow($sqlStmt);

	    // Delete student  <- doesn't work???
    	    $sqlStmt = "select remove_student (".$id_student_B.")";
	    $result = $database->fetchRow($sqlStmt);


	    print $resultUIDS["uniquieid"];
	    // UPDATE THE state id
	    if( $resultUIDS["uniquieid"] ) {
		$sqlStmt = "update iep_student set unique_id_state = '" . $resultUIDS["uniquieid"] . "' where unique_id_state is null and id_student = '" . $id_student_A . "'";
		$result = $database->fetchRow($sqlStmt);
	    }


	    return true;
	}

}