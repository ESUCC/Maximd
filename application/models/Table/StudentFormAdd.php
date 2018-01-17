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

    function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }

    public function studentFormCountyList($userid)
    {
        //  Mike added July 27th the superuser function SRS-105

        $db = Zend_Registry::get('db');
        $superAdmin=false;

        foreach($_SESSION['user']['user']->privs as $privs){
            if($privs['class']=='1' && $privs['status']=='Active') {
              $superAdmin=true;
              $select = $db->select()
              ->from( array('c' => 'iep_county') , array('c.id_county', 'c.name_county'));
              $county_row = $db->fetchAll($select);

           // $this->writevar1($county_row,'this is the county row');

            }
        }

        if($superAdmin==false) {

        $county_row = array();
        $select = $db->select()
                   ->distinct()
                   ->from( array('r' => 'iep_privileges'), array() )
                   ->joinLeft( array('c' => 'iep_county'), 'c.id_county = r.id_county', array('c.id_county', 'c.name_county') )
                   ->where( 'r.id_personnel = ?', $userid )
                   ->order( 'c.name_county asc' );
        $county_row = $db->fetchAll($select);
        }


    //  $this->writevar1($select,'this is the sql command');
    return array($county_row);
    }




    public function studentDistrictList($id_county, $userid)
    {
        // Mike added July 27th the superuser function SRS-105
        $db = Zend_Registry::get('db');


        $county_row = array();

        $superAdmin=false;

        foreach($_SESSION['user']['user']->privs as $privs){
            if($privs['class']=='1' && $privs['status']=='Active') {
                $superAdmin=true;
                $select = $db->select()
                ->from( array('d' => 'iep_district') , array('d.id_district', 'd.name_district'))
                ->where('d.id_county = ?',$id_county);

                $district_row = $db->fetchAll($select);

               // $this->writevar1($county_row,'this is the county row');

            }
        }


         if($superAdmin==false){

         $select = $db->select()
                   ->distinct()
                   ->from( array('r' => 'iep_privileges'), array() )
                   ->joinLeft( array('d' => 'iep_district'), 'd.id_district = r.id_district', array('d.id_district', 'd.name_district'))
                   ->where( 'd.id_county = ?', $id_county )
                   ->where( 'r.id_personnel = ?', $userid )
                   ->order( 'd.name_district asc' );
         $district_row = $db->fetchAll($select);
         }


      //   $this->writevar1($district_row,'this is hte list of districts');
      return array( $district_row );
    }

    public function studentSchoolList($id_county, $id_district)
    {

        $db = Zend_Registry::get('db');
	$school_row = array();

         $select = $db->select()
                   ->from( array('s' => 'iep_school'), array('id_school', 'name_school'))
                   ->where('s.id_district = ?', $id_district)
                   ->where('s.id_county = ?', $id_county)
                   ->where('s.status = ?', 'Active')
                   ->order('s.name_school asc');
         $school_row = $db->fetchAll($select);

      return array( $school_row );
    }

    public function studentManagersList($id_county, $id_district, $id_school)
    {

        $db = Zend_Registry::get('db');
        $select = $db->select()
                   ->distinct()
                   ->from( array('p' => 'iep_personnel'), array('p.id_personnel', 'p.name_first', 'p.name_last') )
                   ->joinLeft(array('r' => 'iep_privileges'), 'p.id_personnel = r.id_personnel', array() )
                   ->where('r.status = \'Active\' and p.id_county = \''.$id_county.'\' and ((p.id_district=\''.$id_district.'\' and r.id_school = \''.$id_school.'\' and r.class >= 4)
											or (r.class = 2 or r.class = 3))')
                   ->order('p.name_first asc');


       // Mike added this 8-4-2017 so that we only get  case managers at the school.
       // Mike changed this 10-17-2017 so that school managers and ASM as well as case managers get in the
       // mix
       $select2="select p.id_personnel,p.name_first,p.name_last,r.id_personnel,r.class from iep_personnel p,iep_privileges r
                 where p.id_personnel=r.id_personnel and  r.class <= '6' and r.id_district='".
                       $id_district."' and r.id_county='".$id_county."' and r.id_school='".$id_school."' order by p.name_last asc";


                       // testing 10-17-2017



       // Jira Ticket 131.  Mike had to come up with it. Request was made to include district managers.
         $mike="select  DISTINCT on (p.name_last,p.name_first) p.name_last,p.name_first,p.id_personnel,
                        r.class,r.status,p.status,r.id_school from iep_personnel p, iep_privileges r
                        where r.id_personnel=p.id_personnel and p.id_county='".$id_county."' and p.id_district='".$id_district."'
                        and r.status='Active'and p.status='Active'
                        and ((r.id_school='".$id_school."' and r.class <='6') or (r.class<='3')) order by p.name_last,p.name_first " ;



        $result = $db->fetchAll($mike);
        $num=count($result);
       //$this->writevar1($result,'this is the result');
      //  $this->writevar1($num,'this is the numbers');
        return array($result);
    }

    /* This was added to test studentMangersList above
    public function studentManagersListm($id_county, $id_district, $id_school)
    {

        $db = Zend_Registry::get('db');


        $select = $db->select()
        ->distinct()
        ->from( array('p' => 'iep_personnel'), array('p.id_personnel', 'p.name_first', 'p.name_last') )
        ->joinLeft(array('r' => 'iep_privileges'), 'p.id_personnel = r.id_personnel', array() )
        ->where('r.status = \'Active\' and p.id_county = \''.$id_county.'\' and ((p.id_district=\''.$id_district.'\' and r.id_school = \''.$id_school.'\' and r.class=\'6\')')
                 ->order('p.name_first asc');


    $select2="select p.id_personnel,p.name_first,p.name_last,r.id_personnel from iep_personnel p,iep_privileges r where p.id_personnel=r.id_personnel and r.class='6' and r.id_district='".
              $id_district."' and r.id_county='".$id_county."' and r.id_school='".$id_school."' order by p.name_last asc";

     //$this->writevar1($select2,'this is the select2 ');

     $result = $db->fetchAll($select2);
     $this->writevar1(array($result),'this is the result after query old');

    return array($result);
    }
*/
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
                   ->from( array('s' => 'iep_student'), array('s.unique_id_state', 's.id_county', 's.id_district', 's.id_school'))
                    ->where('s.unique_id_state = ?', $options["unique_id_state"]);
                 //  ->from( array('s' => 'iep_student'), array('count' => 'COUNT(s.unique_id_state)'))
                  // ->where('s.unique_id_state = ?', $options["unique_id_state"]);

       $result = $db->fetchRow($select);

       return array($result);
    }

    public function studentSave($options)
    {


    $data = array(
        'id_author_last_mod'         => '0',
        'status'                     => 'Active',
        'timestamp_created'          => date('m/d/Y H:i:s', time()),
        'id_county'                  => $options["id_county_school"],
        'id_district'                => $options["id_district_school"],
        'date_web_notify'            => $options["date_web_notify"],
        'name_first'                 => $options["name_first"],
        'name_middle'                => $options["name_middle"],
        'name_last'                  => $options["name_last"],
        'unique_id_state'            => $options["unique_id_state"],  // int
        'exclude_from_nssrs_report'  => $options["exclude_from_nssrs_report"],    // boolean
        'id_school'                  => $options["id_school"],
        'id_case_mgr'                => $options["case_manager"], // int
        'pub_school_student'         => $options["pub_school_student"],  // boolean
        'dob'                        => $options["dob"], // date
        'grade'                      => $options["grade"], // char
        'gender'                     => $options["gender"], // char
        'ethnic_group'               => $options["ethnic_group"], // char
        'primary_language'           => $options["primary_language"], // char
        'ell_student'                => $options["ell_student"],   // boolean
        'ward'                       => $options["ward"],   // boolean
        'address_street1'            => $options["address_street1"], // char
        'address_street2'            => $options["address_street2"], // char
        'address_city'               => $options["address_city"], // char
        'address_state'              => $options["address_state"], // char
        'address_zip'                => $options["address_zip"], // char
        'phone'                      => $options["phone"], // char
        'email_address'              => $options["email_address"], // char
        'ward_surrogate'             => $options["ward_surrogate"],   // boolean
        'ward_surrogate_nn'          => $options["ward_surrogate_nn"],   // boolean
        'ward_surrogate_other'       => $options["ward_surrogate_other"], // char
        'program_provider_name'      => $options["program_provider_name"], // char
        'program_provider_code'      => $options["program_provider_code"], // char
        'program_provider_id_school' => $options["program_provider_id_school"], // char
	'pub_school_student'         => $options["pub_school_student"]  // boolean
   );

     if ($options["alternate_assessment"] != "") $data['alternate_assessment'] = $options["alternate_assessment"];   // boolean
     if ($options["parental_placement"] != "") $data['parental_placement'] = $options["parental_placement"];
     if ($options["nonpubcounty"] != "") $data['nonpubcounty'] = $options["nonpubcounty"];
     if ($options["nonpubdistrict"] != "") $data['nonpubdistrict'] = $options["nonpubdistrict"];
     if ($options["nonpubschool"] != "") $data['nonpubschool'] = $options["nonpubschool"];

     $db = Zend_Registry::get('db');
     $db->insert('iep_student', $data);


     $id = $db->lastInsertId('iep_student', 'id_student');

     return $id;
   }

   public function studentParentAdd($id_student, $options, $result)
   {
      // $this->writevar1($options,'these are the options');
      // $this->writevar1($result,'this is the result');

       foreach($result as $indx => $val) {

           $data = array(
               'id_author'		=> '0',
               'id_author_last_mod'	=> '0',
               'timestamp_created'	=> date('Y-m-d G:i:s'),
               'timestamp_last_mod'	=> date('Y-m-d G:i:s'),
               'address_street1'	=> $val->addresses[0]->streetNumberName,
               'address_street2'	=> '',
               'address_city'		=> $val->addresses[0]->city,
               'address_state'		=> $val->addresses[0]->stateAbbreviationType,
               'address_zip'		=> $val->addresses[0]->postalCode,
               'email_address'		=> '',
               //		'id_guardian'
               'id_student'		=> $id_student,
               'name_first'		=> $val->firstName,
               'name_last'		=> $val->lastSurname,
               'name_middle'		=> '',
               'relation_to_child'	=> '',
               'xxxprimary_language'	=> '',
               'phone_home'		=> $val->telephones[0]->telephoneNumber,
               'phone_work'		=> '',
               'status'		=> 'Active',
               //		'user_name'		=> '', // generate auto
           //		'password'		=> '', // generate auto
               'password_reset_flag'	=> 'false',
               'date_last_pw_change'	=> date('Y-m-d G:i:s'),
               //		'date_expiration'
           //		'checkout_id_user'
           //		'checkout_time'
           //		'online_access'
           //		'id_student_local'
           //		'id_guardian_local'
           //		'last_auto_update'
           //		'last_login'
           //		'data_source'
               'unique_id_state'	=> $options["unique_id_state"]
           );

           $db = Zend_Registry::get('db');
           $this->writevar1($data,'this is what gets inserted into the db');
           $db->insert('iep_guardian', $data);

       }
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