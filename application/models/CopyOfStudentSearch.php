<?php
//class Model_StudentSearch {
//
//	/*
//	 * search for students
//	 */
//	public static function search($id_personnel, $params) {
//        
//        $myStu = new Model_Table_MyStudents();
//        $select = $myStu->select()
//        				->from($myStu, 
//        				array(	'*', 
//        						'name_county'=>'get_name_county(id_county)',
//        						'name_district'=>'get_name_district(id_county, id_district)',
//        						'name_school'=>'get_name_school(id_county, id_district, id_school)'
//        				))
////        				->columns(array('*', 'get_county_name(id_county)'))
//        	  			->where('id_personnel = ?',$id_personnel);
//		
//       	
//       	// add student id condition
//       	if(null != $params['status'] && 'All' != $params['status']) $select->where('status = ?', $params['status']);
//		
//       	// add student id condition
//       	if(null != $params['id_student']) $select->where('id_student = ?',$params['id_student']);
//       	
//       	// add name_first condition
//       	if(null != $params['name_first']) $select->where('name_first = ?',$params['name_first']);
//       	
//       	// add name_first condition
//       	if(null != $params['name_last']) $select->where('name_last = ?',$params['name_last']);
//       	
//       	// add name_first condition
//       	if(null != $params['name_case_mgr_first']) $select->where('get_name_first(id_case_mgr) = ?',$params['name_case_mgr_first']);
//       	
//       	// add name_first condition
//       	if(null != $params['name_case_mgr_last']) $select->where('get_name_last(id_case_mgr) = ?',$params['name_case_mgr_last']);
//       	
//       	// add limiter
//       	if(isset($params['limitto'])) {
//	       	if('all' == $params['limitto']) {
//	       		// let it be
//	       	} elseif('caseload' == $params['limitto']) {
//	       		$select->where("id_case_mgr = ? OR id_list_team like '?'",$id_personnel, $id_personnel.'%');
//	       	}
//       		
//       	}
//       	
//       	// add recs per pags
//       	$select->limit($params['recsPer']);
//       	
//       	// add order
//       	if(isset($params['orderby'])) {
//	       	if('name' == $params['orderby']) {
//	       		$select->order(array('name_last', 'name_first'));
//	       	} elseif('school' == $params['orderby']) {
//	       		$select->order('get_name_school(id_county, id_district, id_school)');
//	       	}
//       		
//       	}
////		echo $select;die();
//       	// fetch records
//        $results = $myStu->fetchAll($select);
//		
//        return $results;
//	
//	}
//}