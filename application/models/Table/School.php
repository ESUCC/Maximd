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

    // Mike D built this function 9-29-2016
    public function districtSchools($id_county,$id_dist){
         
        $all = $this->fetchAll($this->select()
            ->where('id_county = ?',$id_county)
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
            }
            return $resultArray;
        } else {
            return array();
        }
    }

}

