<?php
/**
 * Model_Table_StudentFormAdd
 *
 * @author jlavere
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_StudentFormAdd extends Model_Table_AbstractIepForm
{

    public function studentFormAdd($options)
    {

        $db = Zend_Registry::get('db');
    
        $select = $db->select()
                   ->from( array('c' => 'iep_county'), 'name_county')
                   ->where("c.id_county = '".$options['id_county']."'");
        $county_row = $db->fetchRow($select);

        $select = $db->select()
                   ->from( array('d' => 'iep_district'), 'name_district')
                   ->where("d.id_district = '".$options['id_district']."'");
        $district_row = $db->fetchRow($select);


        $select = $db->select()
                   ->from( array('s' => 'iep_school'), array('id_school', 'name_school'))
                   ->where('s.id_district = ?', $options['id_district'])
                   ->where('s.id_county = ?', $options['id_county'])
                   ->where('s.status = ?', 'Active')
                   ->order('s.name_school asc');
        $school_list = $db->fetchAll($select);

    return array(
            $county_row,
            $district_row,
            $school_list
        );


    }


    public function studentManagersList($id_school)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select()
                   ->distinct()
                   ->from( array('p' => 'iep_personnel'), array('p.id_personnel', 'p.name_first', 'p.name_last') )
                   ->join( array('r' => 'iep_privileges'), 'p.id_personnel = r.id_personnel', array() )  
                   ->where('p.status = \'Active\' and (p.id_school = \''.$id_school.'\' and (r.class >= 4 and r.class <= 10)) or (r.class >= 0 and r.class <= 3)')
                   ->order('p.name_last asc');

        $result = $db->fetchAll($select);

       return array($result);
    }


    public function studentSesisList($do_action, $id_county, $id_district)
    {

//select id_county,name_county from iep_county order by name_county
//select name_district,id_district from iep_district where id_county='11'
//select id_school,name_school from iep_school where id_district=.0014. and id_count='11'


        $db = Zend_Registry::get('db');

          if ($do_action == "district" && $id_county != "") {
            $select = $db->select()
                   ->from( array('p' => 'iep_district') , array('p.name_district', 'p.id_district'))
                   ->where('p.id_county = ?', $id_county)
                   ->order('p.name_district asc');
         } else if ($do_action == "school" && $id_county != "" && $id_district != ""){
            $select = $db->select()
                   ->from( array('p' => 'iep_school') , array('p.id_school', 'p.name_school'))
                   ->where('p.id_district = ?', $id_district)
                   ->where('p.id_county = ?',  $id_county)
                   ->order('p.name_school asc');
         } else {
            $select = $db->select()
                   ->from( array('p' => 'iep_county') , array('p.id_county', 'p.name_county'))
                   ->order('p.name_county asc');
        } 

        $result = $db->fetchAll($select);

       return array($result);
    }



    public function studentNssrsCheck($options)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select()
                   ->from( array('s' => 'iep_student'), array('count' => 'COUNT(s.unique_id_state)'))
                   ->where('s.unique_id_state = ?', $options["unique_id_state"]);

       $result = $db->fetchRow($select);

       return array($result);
    }

    public function studentSave($options)
    {


    $data = array(
        
        'id_author_last_mod'         => '0',
        'status'                     => 'Active',
        'timestamp_created'          => date('m/d/Y H:i:s', time()),
        'id_county'                  => $options["id_county"],
        'id_district'                => $options["id_district"],
        'date_web_notify'            => $options["date_web_notify"],
        'name_first'                 => $options["name_first"],
        'name_middle'                => $options["name_middle"],
        'name_last'                  => $options["name_last"],
        'unique_id_state'            => $options["unique_id_state"],  // int
        'exclude_from_nssrs_report'  => $options["exclude_from_nssrs_report"],    // boolean
        'id_school'                  => $options["id_school"],
        'id_case_mgr'                => $options["case_manager"],
        'pub_school_student'         => $options["pub_school_student"],  // boolean
        'dob'                        => $options["dob"],
        'grade'                      => $options["grade"],
        'gender'                     => $options["gender"],
        'ethnic_group'               => $options["ethnic_group"],
        'primary_language'           => $options["primary_language"],
        'ell_student'                => $options["ell_student"],   // boolean
        'ward'                       => $options["ward"],   // boolean
        'address_street1'            => $options["address_street1"],
        'address_street2'            => $options["address_street2"],
        'address_city'               => $options["address_city"],
        'address_state'              => $options["address_state"],
        'address_zip'                => $options["address_zip"],
        'phone'                      => $options["phone"],
        'email_address'              => $options["email_address"],
        'ward_surrogate'             => $options["ward_surrogate"],   // boolean
        'ward_surrogate_nn'          => $options["ward_surrogate_nn"],   // boolean
        'ward_surrogate_other'       => $options["ward_surrogate_other"],
        'program_provider_name'      => $options["program_provider_name"],
        'program_provider_code'      => $options["program_provider_code"],
        'program_provider_id_school' => $options["program_provider_id_school"]
   );


    if ($options["sesis_exit_date"] != "") $data['sesis_exit_date'] = $options["sesis_exit_date"];
    if ($options["alternate_assessment"] != "") $data['alternate_assessment'] = $options["alternate_assessment"];   // boolean

         $db = Zend_Registry::get('db');
         $db->insert('iep_student', $data);
         $id = $db->lastInsertId('iep_student', 'id_student');

       return $id;
   }


    static function schoolMultiOtions($idCounty, $idDistrict, $privLimited = false)
    {
        $retArray = array();
    
        if (strlen($idCounty) < 2 || strlen($idDistrict) < 4) {
            return false;
        }
    
        $table = new Model_Table_School();
        $select = $table->select()
        ->where("status ='Active'")
        ->where('id_county = ?', $idCounty)
        ->where('id_district = ?', $idDistrict)
        ->order('name_school');
        $schools = $table->fetchAll($select);
    
        if($privLimited) {
            /**
             * setup accessible districts
             */
            $usersession = new Zend_Session_Namespace ( 'user' );
            foreach($schools as $school) {
                foreach ($usersession->user->privs as $priv) {
                    if(1==$priv['class']){
                        $retArray[$school['id_school']] = $school['name_school'];
                    } elseif((2==$priv['class'] || 3==$priv['class']) && $priv['id_county'] == $idCounty && $priv['id_district'] == $idDistrict) {
                        $retArray[$school['id_school']] = $school['name_school'];
                    } elseif($priv['id_county'] == $idCounty && $priv['id_district'] == $idDistrict && $priv['id_school'] == $school['id_school']) {
                        $retArray[$school['id_school']] = $school['name_school'];
                    }
                }
            }
        } else {
            foreach ($schools as $c) {
                $retArray[$c['id_school']] = $c['name_school'];
            }
        }
    
        return $retArray;
    }


}