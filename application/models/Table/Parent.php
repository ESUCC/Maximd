<?php
/**
 * Model_Table_Parent
 *
 * @author jlavere
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Parent extends Model_Table_AbstractIepForm
{

//    protected $_name = 'iep_school';
//    protected $_primary = array('id_county', 'id_district', 'id_school');

    public function studentDetails($id_student)
    {

	    $newQuery = false;
            $db = Zend_Registry::get('db');
            $select = $db->select()
                         ->from( 'iep_student',
                                    array('name_first', 'name_last', 'id_school')
                         )
	       ->where('iep_student.id_student = ?', $id_student);
     	    $result = $db->fetchRow($select);

	return $result;
    }    

    public function parentList($options)
    {

	    $newQuery = false;

            $db = Zend_Registry::get('db');

            $select = $db->select()
                         ->from( 'iep_guardian',
                                    array('*')
                         )
	       ->where('iep_guardian.id_student = ?', $options['id_student'])
               ->where('iep_guardian.status = ?', 'Active')
	       ->order('name_last asc');

            $stmt = $db->query($select);

            if (!Model_CacheManager::isCached(Zend_Registry::get('searchCache'), $options['key'])) {
                $toCache = $stmt->fetchAll();
                // Save result to new cache file
                Model_CacheManager::getCacheForKey(Zend_Registry::get('searchCache'), $options['key'], $toCache);
		$newQuery = true;
            }


	    // Read cache file
            $count = count($cacheResults = Model_CacheManager::getCacheForKey(Zend_Registry::get('searchCache'), $options['key'], false));

            // read result from cache file
            $cacheResults = Model_CacheManager::getCacheForKey(Zend_Registry::get('searchCache'), $options['key'], false);

            return array(
                $newQuery,
                $paginator = Zend_Paginator::factory($cacheResults)->setItemCountPerPage($options['maxRecs'])->setCurrentPageNumber(empty($options['page']) ? 1 : $options['page']),
                $options['key'],
                $count
            );


    }

    public function parentView($options)
    {
            $db = Zend_Registry::get('db');
            $select = $db->select()
                         ->from( 'iep_guardian',
                                    array('*', "to_char(date_expiration, 'MM/DD/YYYY') as date_expiration", "to_char(date_last_pw_change, 'MM/DD/YYYY') as date_last_pw_change")
                         )
	       ->where('iep_guardian.id_student = ?', $options['id_student'])
	       ->where('iep_guardian.id_guardian = ?', $options['id_guardian'])
               ->where('iep_guardian.status = ?', 'Active');
     	    $result = $db->fetchRow($select);

	return $result;
    }

    public function parentSave($options)
    {
        $db = Zend_Registry::get('db');

		if (intval($options["id_guardian"] * 1) > 0) 
		{
 	 		$data = array(
			    'name_first'        => $options["name_first"],
			    'name_middle'       => $options["name_middle"],
			    'name_last'         => $options["name_last"],
			    'status'            => $options["status"],
			    'relation_to_child' => $options["relation_to_child"],
			    'address_street1'   => $options["address_street1"],
			    'address_street2'   => $options["address_street2"],
			    'address_city'      => $options["address_city"],
			    'address_state'     => $options["address_state"],
			    'address_zip'       => $options["address_zip"],
			    'phone_home'        => $options["phone_home"],
			    'phone_work'        => $options["phone_work"],
			    'email_address'     => $options["email_address"],
			    'online_access'     => $options["online_access"],
			    'user_name'         => $options["user_name"],
			    'password'          => $options["password"]
			);

	 		if ($options["date_expiration"] != "") 
	 			$data['date_expiration'] = $options["date_expiration"];
	 		if (intval($options["password_change"] * 1)  == 1) 
	 		{
                $data['date_last_pw_change'] = date("m/d/Y", time()); 
	       		$data['password_reset_flag'] = 'True';
         	}

			$where['id_guardian = ?'] = $options["id_guardian"];
			$where['id_student = ?'] = $options["id_student"];
			$db->update('iep_guardian', $data, $where);

	 		$id = $options["id_guardian"];
		} 
		else 
		{
	  		$data = array(
			    'id_author'           => 0,
			    'id_author_last_mod'  => 0,
			    'id_student'          => $options["id_student"],
			    'name_first'          => $options["name_first"],
			    'name_middle'         => $options["name_middle"],
			    'name_last'           => $options["name_last"],
			    'status'              => $options["status"],
			    'relation_to_child'   => $options["relation_to_child"],
			    'address_street1'     => $options["address_street1"],
			    'address_street2'     => $options["address_street2"],
			    'address_city'        => $options["address_city"],
			    'address_state'       => $options["address_state"],
			    'address_zip'         => $options["address_zip"],
			    'phone_home'          => $options["phone_home"],
			    'phone_work'          => $options["phone_work"],
			    'email_address'       => $options["email_address"],
			    'online_access'       => $options["online_access"],
			    'password'            => $options["password"],
			    'password_reset_flag' => 'True',
			    'date_last_pw_change' => date("m/d/Y", time())
			);
         
	 		$db->insert('iep_guardian', $data);
 	 		$id = $db->lastInsertId('iep_guardian', 'id_guardian');
	 		$options["user_name"] = $options["user_name"].$id;


	 		$data = array(
	    		'user_name'  => $options["user_name"]
         	);
	 		$where['id_guardian = ?'] = $id;
	 		$where['id_student = ?'] = $options["id_student"];
	 		$db->update('iep_guardian', $data, $where);
        }

        $key = $options["key"];
        if (Model_CacheManager::isCached(Zend_Registry::get('searchCache'), $key)) Model_CacheManager::removeCache(Zend_Registry::get('searchCache'), $key);
        return $id;
  	}
}
