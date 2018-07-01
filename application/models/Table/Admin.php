<?php
/**
 * Model_Table_Admin
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_Admin extends Model_Table_AbstractIepForm
{

    public function sessionList($options)
    {

            $users_count = array();
	    $newQuery = false;
            $db = Zend_Registry::get('db');

            $select_1 = $db->select()
                         ->from( array('l' => 'iep_session'),
                                    array('timestamp_created', 'id_user', 'ip')
                         )
	       ->join(array('p' => 'iep_personnel') , 'p.id_personnel = l.id_user', array('name_first', 'name_middle', 'name_last'))
               ->join(array('s' => 'iep_school'), 's.id_school = p.id_school', array('name_school', 'phone_main'))
               ->where('s.id_district = p.id_district')
               ->where('s.id_county = p.id_county')
               ->where("l.timestamp_created >= DATE '".$options['session_date']."' and l.timestamp_created < DATE '".$options['session_date']."' + INTERVAL '1 day'");

            $select_2 = $db->select()
                         ->from( array('l' => 'iep_session'),
                                    array('timestamp_created', 'id_user', 'ip')
                         )
	       ->join(array('p' => 'iep_personnel') , 'p.id_personnel = l.id_user', array('name_first', 'name_middle', 'name_last', 'id_school', 'id_school'))
               ->where('p.id_school IS NULL')
               ->where("l.timestamp_created >= DATE '".$options['session_date']."' and l.timestamp_created < DATE '".$options['session_date']."' + INTERVAL '1 day'");


            $select = $db->select()->union(array($select_1, $select_2))
               ->order('timestamp_created desc');

            $stmt = $db->query($select);
            $result = $stmt->fetchAll();
            $count = count($result);

	    // users count
            foreach($result as $key => $value) $users_count[$value['id_user']] = 1;

            return array(
                $newQuery,
                $paginator = Zend_Paginator::factory($result)->setItemCountPerPage($options['maxRecs'])->setCurrentPageNumber(empty($options['page']) ? 1 : $options['page']),
                $count,
                count($users_count)
            );
  }


    public function announcementAdd($options)
    {
       $data = array(
            'msg_title'           => $options['msg_title'],
            'message_text'        => $options['message_text'],
            'create_date'         => $options['create_date'],
            'display_until_date'  => $options['display_until_date'],
            'msg_type'            => $options['msg_type']
         );
         $db = Zend_Registry::get('db');
         $db->insert('iep_messages_new', $data);
    }


    public function reportingLoad()
    {
            $db = Zend_Registry::get('db');
            $select = $db->select()
                         ->from( 'admin_settings', array("to_char(nssrs_submition_date, 'MM/DD/YYYY') as nssrs_submition_date", "to_char(nssrs_school_year, 'MM/DD/YYYY') as nssrs_school_year","to_char(october_cutoff, 'MM/DD/YYYY') as october_cutoff","to_char(transfer_report_cutoff, 'MM/DD/YYYY') as transfer_report_cutoff"));
            $result = $db->fetchRow($select);

      return $result;

    }

    public function reportingSave($options)
    {
       $data = array(
            'nssrs_submition_date'    => $options['nssrs_submition_date'],
            'nssrs_school_year'       => $options['nssrs_school_year'],
            'october_cutoff'          => $options['october_cutoff'],
            'transfer_report_cutoff'  => $options['transfer_report_cutoff']
         );

         $db = Zend_Registry::get('db');
         $db->update('admin_settings', $data);
    }

    public function checkPrivs($options)
    {
            $db = Zend_Registry::get('db');
            $select = $db->select()
                         ->from( 'iep_personnel', 'class' )
               ->where('id_personnel = ?', $options["id_user"]);
            $result = $db->fetchRow($select);

      return $result;

   }

    public function countyList()
    {

        $db = Zend_Registry::get('db');
        $select = $db->select()
                   ->from( array('c' => 'iep_county'), array('c.id_county', 'c.name_county') )
                   ->order('c.name_county asc');

        $result = $db->fetchAll($select);

       return array($result);
    }

    public function districtList($id_county)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select()
                   ->from( array('d' => 'iep_district'), array('d.id_district', 'd.name_district') )
                   ->where('id_county = ?', $id_county)
                   ->order('d.name_district asc');

        $result = $db->fetchAll($select);

       return array($result);
    }

    public function dataadminAdd($options)
    {

       $db = Zend_Registry::get('db');
       if ($options['doaction'] == 'createdistrict') {

            $db = Zend_Registry::get('db');
            $select = $db->select()
                         ->from( 'iep_district', 'max(id_district::integer) as max_id_district' )
               ->where('id_county = ?', $options["id_county"]);
            $result = $db->fetchRow($select);

         $next_id = $result["max_id_district"] + 1;
         $id_district  = substr('0000'.$next_id, -4, 4);
         $data = array(
	    'id_author'           => $options['id_user'],
	    'id_author_last_mod'  => $options['id_user'],
            'id_county'           => $options['id_county'],
            'id_district'         => $id_district,
            'name_district'       => $options['name_district'],
            'address_street1'     => $options['address_street1'],
            'address_street2'     => $options['address_street2'],
            'address_city'        => $options['address_city'],
            'address_state'       => $options['address_state'],
            'address_zip'         => $options['address_zip']
         );
         $db->insert('iep_district', $data);

        } else if ($options['doaction'] == 'createschool') {

          $select = $db->select()
                         ->from( 'iep_school', 'max(id_school::integer) as max_id_school' )
               ->where('id_county = ?', $options["id_county"])
               ->where('id_district = ?', $options["id_district"]);
         $result = $db->fetchRow($select);

         $next_id = $result["max_id_school"] + 1;
         $id_school = substr('000'.$next_id, -3, 3);

         $data = array(
	    'id_school'           => $id_school,
            'id_author'           => $options['id_user'],
	    'id_author_last_mod'  => $options['id_user'],
            'id_county'           => $options['id_county'],
            'id_district'         => $options['id_district'],
            'name_school'         => $options['name_school'],
            'address_street1'     => $options['address_street1'],
            'address_street2'     => $options['address_street2'],
            'address_city'        => $options['address_city'],
            'address_state'       => $options['address_state'],
            'address_zip'         => $options['address_zip']
         );
         $db->insert('iep_school', $data);

       }
	return;
    }


}
