<?php

/**
 * Model_Table_School
 *
 * @author jlavere
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_School extends Model_Table_AbstractIepForm
{
    /**
     * The default table name
     */
    protected $_name = 'iep_school';
    protected $_primary = array('id_county', 'id_district', 'id_school');

    function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }

// Mike added this 1-26-2018 so that the autofill on the state id would identify to the end user the location of the school
// with the state id trying to be used.

    function getSchoolAutoFill($stateId) {

        $db = Zend_Registry::get('db');
        $sql="select s.id_school,d.name_school,d.id_school_mgr,d.phone_main from iep_student s,iep_school d where s.unique_id_state='$stateId' and s.id_district=d.id_district and s.id_county=d.id_county";
        $select=$db->fetchAll($sql);
       //$this->writevar1($select,'this is the result of the db search');
        return($select);
    }

// End of Mike add 1-26-2018

    // Mike added this 8-9-2017 to make the Edit school maxim pop up work

    public function schoolList($options)
    {

        $newQuery = false;

        $db = Zend_Registry::get('db');

        $select = $db->select()
        ->from( 'iep_school',
            array('*')
            )
            ->join('iep_district',  '(iep_school.id_district=iep_district.id_district AND iep_school.id_county=iep_district.id_county)')
            ->join('iep_county', 'iep_school.id_county=iep_county.id_county')
            ->where('iep_school.status = ?', 'Active');

            //	       $select->order(array('name_school asc', 'name_district asc'));

            switch ($options["sort"]){
                case "11": $select->order('name_school asc'); break;
                case "12": $select->order('name_school desc'); break;
                case "21": $select->order('name_district asc'); break;
                case "22": $select->order('name_district desc'); break;
                case "31": $select->order('name_county asc'); break;
                case "32": $select->order('name_county desc'); break;
                default: $select->order('name_school asc');

            }

            // id_district, id_county, class
            $where_area = "";
            $x = 0;
            foreach($options['privsUser'] as $valPrivs){
                list ($id_district, $id_county, $class) = $valPrivs;
                $where_area .= "(iep_school.id_district='".$id_district."' AND iep_school.id_county='".$id_county."')";
               // $this->writevar1($where_area,'this is the where area');
                $x++;
                if (count($options['privsUser']) != $x) $where_area .= " or ";
            }

            // Mike changed this 8-10-2017 so that uers with 1 priv did not see all schools in NE

            if (count($options['privsUser']) > 0) $select->where($where_area);
            $stmt = $db->query($select);
           // $this->writevar1($where_area,'this is the sql where area statement');

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
    // Mike added this 8-9-2017 to make the Edit school maxim pop up work
    public function schoolView($options)
    {

        $db = Zend_Registry::get('db');

        $select = $db->select()
        ->from( array('s' => 'iep_school'), array('*'))
        ->where('s.status = ?', 'Active')
        ->where("s.id_school = '".$options['id_school']."'")
        ->where("s.id_county = '".$options['id_county']."'")
        ->where("s.id_district = '".$options['id_district']."'");
        $school_row = $db->fetchRow($select);

        $select = $db->select()
        ->from( array('c' => 'iep_county'), 'name_county')
        ->where("c.id_county = '".$options['id_county']."'");
        $county_row = $db->fetchRow($select);


        /* Mike changed this 9-8-2017
         * added the following where
         *  ->where("d.id_county= '".$options['id_county']."'")
         *  Need to mention to Maxim.
        */
        $select = $db->select()
        ->from( array('d' => 'iep_district'), 'name_district')
        ->where("d.id_district = '".$options['id_district']."'")
        ->where("d.id_county= '".$options['id_county']."'");
        $district_row = $db->fetchRow($select);


        $select = $db->select()
        ->distinct()
        ->from( array('pl' => 'iep_personnel'), array('pl.name_last', 'pl.name_first', 'pl.name_middle') )
        ->join(array('pr' => 'iep_privileges'), 'pr.id_personnel = pl.id_personnel', 'pr.id_personnel')
        ->where("pr.status='Active'")
        ->where("( pr.class<='4' AND pr.id_county='".$options['id_county']."' AND pr.id_district='".$options['id_district']."' AND pr.id_school='".$options['id_school']."'")
        ->orWhere("pr.class<='3' AND pr.id_county='".$options['id_county']."' AND pr.id_district='".$options['id_district']."')")
        ->order('pl.name_last');
        $stmt = $db->query($select);
        $schoolmgr_row = $stmt->fetchAll();

        $select = $db->select()
        ->distinct()
        ->from( array('pl' => 'iep_personnel'), array('pl.name_last', 'pl.name_first', 'pl.name_middle') )
        ->join(array('pr' => 'iep_privileges'), 'pr.id_personnel = pl.id_personnel', 'pr.id_personnel')
        ->where("pr.status='Active'")
        ->where("pr.class<='4' AND pr.id_county='".$options['id_county']."' AND pr.id_district='".$options['id_district']."'")
        ->order('pl.name_last');
        $stmt = $db->query($select);
        $schoolsprv_row = $stmt->fetchAll();

        $select = $db->select()
        ->from( 'iep_school_report_date', array('year_identifier', "to_char(date_report1, 'MM/DD/YYYY') as date_report1", "to_char(date_report2, 'MM/DD/YYYY') as date_report2", "to_char(date_report3, 'MM/DD/YYYY') as date_report3", "to_char(date_report4, 'MM/DD/YYYY') as date_report4", "to_char(date_report5, 'MM/DD/YYYY') as date_report5", "to_char(date_report6, 'MM/DD/YYYY') as date_report6") )
        ->where( 'id_county = ?', $options['id_county'] )
        ->where( 'id_district = ?', $options['id_district'] )
        ->where( 'id_school = ?', $options['id_school'] )
        ->order('year_identifier asc')
        ->limitPage(0, 20);


        $stmt = $db->query($select);
        $reports_row = $stmt->fetchAll();


        return array(
            $school_row,
            $county_row,
            $district_row,
            $schoolmgr_row,
            $schoolsprv_row,
            $reports_row
        );

    }

    // Mike added this 8-9-2017 to make the Edit school maxim pop up work
    public function schoolSave($options, $options_reports)
    {

        $db = Zend_Registry::get('db');

        $data = array(
            'name_school'      => $options["name_school"],
            'status'           => $options["status"],
            'id_school_mgr'    => $options["schoolmng"],
            'id_account_sprv'  => $options["schoolsprv"],
            'address_street1'  => $options["address_street1"],
            'address_street2'  => $options["address_street2"],
            'address_city'     => $options["address_city"],
            'address_state'    => $options["address_state"],
            'address_zip'      => $options["address_zip"],
            'phone_main'       => $options["phone_main"],
            'minutes_per_week' => $options["minutes_per_week"]
        );

        $where['id_school = ?'] = $options["id_school"];
        $where['id_county = ?'] = $options["id_county"];
        $where['id_district = ?'] = $options["id_district"];
        $db->update('iep_school', $data, $where);

        // Reports Save
        foreach($options_reports as $index => $value){
            $select = $db->select()
            ->from( 'iep_school_report_date', 'count(*) as cnt')
            ->where( 'id_county = ?', $options['id_county'] )
            ->where( 'id_district = ?', $options['id_district'] )
            ->where( 'id_school = ?', $options['id_school'] )
            ->where( 'year_identifier = ?', intval($index));
         	  $report_row = $db->fetchRow($select);


            $options_reports[$index][0] == "" ? $date1 = "" : $date1 = Zend_Locale_Format::getDate($options_reports[$index][0], array('date_format' => 'MM-dd-yyyy', 'fix_date' => true) );
            $options_reports[$index][1] == "" ? $date2 = "" : $date2 = Zend_Locale_Format::getDate($options_reports[$index][1], array('date_format' => 'MM-dd-yyyy', 'fix_date' => true) );
            $options_reports[$index][2] == "" ? $date3 = "" : $date3 = Zend_Locale_Format::getDate($options_reports[$index][2], array('date_format' => 'MM-dd-yyyy', 'fix_date' => true) );
            $options_reports[$index][3] == "" ? $date4 = "" : $date4 = Zend_Locale_Format::getDate($options_reports[$index][3], array('date_format' => 'MM-dd-yyyy', 'fix_date' => true) );
            $options_reports[$index][4] == "" ? $date5 = "" : $date5 = Zend_Locale_Format::getDate($options_reports[$index][4], array('date_format' => 'MM-dd-yyyy', 'fix_date' => true) );
            $options_reports[$index][5] == "" ? $date6 = "" : $date6 = Zend_Locale_Format::getDate($options_reports[$index][5], array('date_format' => 'MM-dd-yyyy', 'fix_date' => true) );

            $date1 != "" ? $date_report1 = $date1["year"]."-".$date1["month"]."-".$date1["day"] : $date_report1 = NULL;
            $date2 != "" ? $date_report2 = $date2["year"]."-".$date2["month"]."-".$date2["day"] : $date_report2 = NULL;
            $date3 != "" ? $date_report3 = $date3["year"]."-".$date3["month"]."-".$date3["day"] : $date_report3 = NULL;
            $date4 != "" ? $date_report4 = $date4["year"]."-".$date4["month"]."-".$date4["day"] : $date_report4 = NULL;
            $date5 != "" ? $date_report5 = $date5["year"]."-".$date5["month"]."-".$date5["day"] : $date_report5 = NULL;
            $date6 != "" ? $date_report6 = $date6["year"]."-".$date6["month"]."-".$date6["day"] : $date_report6 = NULL;


            if ($report_row["cnt"] == 1){

                $data = array(
                    'date_report1'     => $date_report1,
                    'date_report2'     => $date_report2,
                    'date_report3'     => $date_report3,
                    'date_report4'     => $date_report4,
                    'date_report5'     => $date_report5,
                    'date_report6'     => $date_report6
                );

                $where['id_school = ?'] = $options["id_school"];
                $where['id_county = ?'] = $options["id_county"];
                $where['id_district = ?'] = $options["id_district"];
                $where['year_identifier = ?'] = $index;
                $db->update('iep_school_report_date', $data, $where);

            } else {

                $data = array(
                    'id_school_report_date'     => $options["id_school"],
                    'date_report1'     => $date_report1,
                    'date_report2'     => $date_report2,
                    'date_report3'     => $date_report3,
                    'date_report4'     => $date_report4,
                    'date_report5'     => $date_report5,
                    'date_report6'     => $date_report6,
                    'id_school'        => $options["id_school"],
                    'id_county'        => $options["id_county"],
                    'id_district'      => $options["id_district"],
                    'year_identifier'  => $index
                );

                $db->insert('iep_school_report_date', $data);
            }

        }


        // Remove old cache files after save
        $usersession = new Zend_Session_Namespace ('user');
        $key = $usersession->user->user['id_county'].$usersession->user->user['id_district'].$usersession->user->user['id_personnel'];
        if (Model_CacheManager::isCached(Zend_Registry::get('searchCache'), $key."11")) Model_CacheManager::removeCache(Zend_Registry::get('searchCache'), $key."11");
        if (Model_CacheManager::isCached(Zend_Registry::get('searchCache'), $key."12")) Model_CacheManager::removeCache(Zend_Registry::get('searchCache'), $key."11");
        if (Model_CacheManager::isCached(Zend_Registry::get('searchCache'), $key."21")) Model_CacheManager::removeCache(Zend_Registry::get('searchCache'), $key."21");
        if (Model_CacheManager::isCached(Zend_Registry::get('searchCache'), $key."22")) Model_CacheManager::removeCache(Zend_Registry::get('searchCache'), $key."22");
        if (Model_CacheManager::isCached(Zend_Registry::get('searchCache'), $key."31")) Model_CacheManager::removeCache(Zend_Registry::get('searchCache'), $key."31");
        if (Model_CacheManager::isCached(Zend_Registry::get('searchCache'), $key."32")) Model_CacheManager::removeCache(Zend_Registry::get('searchCache'), $key."32");

    }



    /**
     * The default table name
     */
    //    protected $_name = 'iep_school';
    //    protected $_primary = array('id_county', 'id_district', 'id_school');


    // Mike D built this function 9-29-2016
    public function districtSchools($id_county,$id_dist){

        $all = $this->fetchAll($this->select()
            ->where('id_county = ?',$id_county)
            ->where('status = ?','Active')
            ->where('id_district = ?',$id_dist));

        return $all->toArray();
        // return $all;
    }


    function getSchool($idCounty, $idDistrict, $idSchool)
    {
        $table = new $this->className();
        return $table->find($idCounty, $idDistrict, $idSchool);
    }

    function getCurrentManager($idCounty, $idDistrict, $idSchool)
    {
        $table = new Model_Table_School();
        $select = $table->select()
            ->from(array('s' => 'iep_school'),
                array(
                    's.id_county',
                    's.id_district',
                    's.id_school',
                    's.name_school',
                ))
            ->setIntegrityCheck(false)
            ->joinLeft(
                array('p' => 'iep_personnel'),
                's.id_school_mgr = p.id_personnel',
                array(
                    'p.name_first',
                    'p.name_last',
                    'p.email_address')
            )
            ->where("p.status ='Active'")
            ->where('s.id_county = ?', $idCounty)
            ->where('s.id_district = ?', $idDistrict)
            ->where('s.id_school = ?', $idSchool);
        return $table->fetchRow($select);
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

    static function getSchools($idCounty, $idDistrict)
    {
        if (strlen($idCounty) < 2 || strlen($idDistrict) < 4) {
            return false;
        }
        $table = new Model_Table_School();
        $select = $table->select()
            ->from(array('s' => 'iep_school'),
            array(
                's.id_county',
                's.id_district',
                's.id_school',
                's.name_school',
            ))
            ->setIntegrityCheck(false)
            ->joinLeft(
            array('p' => 'iep_personnel'),
            's.id_account_sprv=p.id_personnel',
            array(
                'p.name_first',
                'p.name_last',
                'p.phone_work')
        )
            ->where("s.status ='Active'")
            ->where('s.id_county = ?', $idCounty)
            ->where('s.id_district = ?', $idDistrict)
            ->order('s.name_school');
        $schools = $table->fetchAll($select);

        if (0 == $schools->count()) {
            return false;
        }
        return $schools->toArray();
    }
    static function getNonPublicSchools($idCounty, $idDistrict) {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(
                'iep_school_non_public',
                array(
                    'id_school',
                    'name',
                )
            )
            ->where("agency_record_type_code = ?", 'S')
            ->where("id_county = ?", $idCounty)
            ->where("id_district = ?", $idDistrict)
            ->group(array('id_school', 'name'))
            ->order('name');
        $results = $db->fetchAll($select);
        if($rowCount = count($results)) {
            $resultArray = array();
            foreach($results as $npSchool) {
                //Array
                //(
                //    [01] => ADAMS
                //    [02] => ANTELOPE
                //)
                $resultArray[$npSchool['id_school']] = $npSchool['name'];
           //     $resultArray[$npSchool['name']] = $npSchool['name'];


            }
            return $resultArray;
        } else {
            return array();
        }
    }

}

