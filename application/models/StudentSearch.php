<?php
class Model_StudentSearch {

	/*
	 * search for students
	 */
	public static function search($id_personnel, $params) {

        $myStu = new Model_Table_MyStudents();
        $select = $myStu->select()
        				->from($myStu, 
        				array(	'*',
        						'name_county'=>'get_name_county(id_county)',
        						'name_district'=>'get_name_district(id_county, id_district)',
        						'name_school'=>'get_name_school(id_county, id_district, id_school)',
        						'team_member_names'=>'get_team_member_names(id_student)'

        				))
//        				->columns(array('*', 'get_county_name(id_county)'))
        	  			->where('id_personnel = ?',$id_personnel);
		
       	
       	// add student id condition
       	if(isset($params['status']) && null != $params['status'] && 'All' != $params['status']) $select->where('status = ?', $params['status']);
		
       	// no search criteria
       	if(!isset($params['id_student_search_rows']) || 0 == count($params['id_student_search_rows'])) {
       		return array();
       	}
       	
       	// subform conditions
       	if(is_array($params['id_student_search_rows'])) {
	       	$rowCount = count($params['id_student_search_rows']);
	       	for($i=0; $i<$rowCount;$i++) {
	       		$searchValue = $params['search_value'][$i];
	       		switch ($params['search_field'][$i]) {
	       			case 'id_student':
	       				$select->where('id_student = ?',$searchValue);
	       				break;
	       			case 'name_first':
	       				$select->where('name_first ilike ?',"%".$searchValue."%");
	       				break;
	       			case 'name_last':
	       				$select->where('name_last ilike ?',"%".$searchValue."%");
	       				break;
	       			case 'name_case_mgr_first':
	       				$select->where('get_name_first(id_case_mgr) ilike ?',"%".$searchValue."%");
	       				break;
	       			case 'name_case_mgr_last':
	       				$select->where('get_name_last(id_case_mgr) ilike ?',"%".$searchValue."%");
	       				break;
	       		}
	       		
	       	}
       	} elseif('' != $params['id_student_search_rows']) {
       		
       		$searchValue = $params['search_value'];
       		switch ($params['search_field']) {
       			case 'id_student':
       				$select->where('id_student = ?',$searchValue);
       				break;
       			case 'name_first':
       				$select->where('name_first ilike ?',"%".$searchValue."%");
       				break;
       			case 'name_last':
       				$select->where('name_last ilike ?',"%".$searchValue."%");
       				break;
       			case 'name_case_mgr_first':
       				$select->where('get_name_first(id_case_mgr) ilike ?',"%".$searchValue."%");
       				break;
       			case 'name_case_mgr_last':
       				$select->where('get_name_last(id_case_mgr) ilike ?',"%".$searchValue."%");
       				break;
       		}
       	} else {
       		return;
       	}
       	// add limiter
       	if(isset($params['limitto'])) {
	       	if('all' == $params['limitto']) {
	       		// let it be
	       	} elseif('caseload' == $params['limitto']) {
	       		$select->where("id_case_mgr = ? OR id_list_team like '?'",$id_personnel, $id_personnel.'%');
	       	}
       		
       	}
       	
       	// add recs per pags
       	$select->limit($params['recs_per']);
       	
       	// add order
       	if(isset($params['sort_order'])) {
	       	if('name' == $params['sort_order']) {
	       		$select->order(array('name_last', 'name_first'));
	       	} elseif('school' == $params['sort_order']) {
	       		$select->order('get_name_school(id_county, id_district, id_school)');
	       	}
       		
       	}
//       	Zend_Debug::dump($params);
// 		echo $select;die();
       	// fetch records
        $results = $myStu->fetchAll($select);

        return $results;
	
	}

    public static function getMyStudent($id_personnel, $id_student)
    {
        $db = Zend_Registry::get('db');
        $pid = $db->quote($id_personnel);
        $sid = $db->quote($id_student);

        $myStu = new Model_Table_MyStudents();
        $select = $myStu->select()
            ->from(
                $myStu,
                array(
                    '*',
                    'name_county' => 'get_name_county(id_county)',
                    'name_district' => 'get_name_district(id_county, id_district)',
                    'name_school' => 'get_name_school(id_county, id_district, id_school)',
                    'team_member_names' => 'get_team_member_names(id_student)',
                    'name_student_full' => new Zend_Db_Expr("CASE WHEN name_middle IS NOT NULL THEN name_first || ' ' || name_middle || ' ' || name_last ELSE name_first || ' ' || name_last END"),

                )
            )
            ->where('id_personnel = ?', $pid);


        $select->where('id_student = ?', $sid);
        $results = $myStu->fetchAll($select);
        if(1 == count($results)) {
            return $results->current();
        } else {
            return false;
        }
    }
}