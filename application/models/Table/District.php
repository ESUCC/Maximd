<?php

/**
 * iep_district
 *
 * @author jlavere
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_District extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name
	 */
    protected $_name = 'iep_district';
	protected $_primary = array('id_county', 'id_district');

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

	function getSchoolDistrictAutoFill($stateId) {

	    $db = Zend_Registry::get('db');
	    $sql="select s.id_school,d.name_school from iep_student s,iep_school d where s.unique_id_state='$stateId' and s.id_district=d.id_district and s.id_county=d.id_county";
	  //  $this->writevar1($sql,'thisis the sql');
	    $select=$db->fetchAll($sql);
	 //   $this->writevar1($select,'this is the info');
	    return($select);
	}

	// End of Mike add 1-26-2018


	function getAllDists() {


	    $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
	    $database = Zend_Db::factory($dbConfig->db2);
	    $sql='SELECT name_district,id_county,id_district,edfi_key,edfi_secret from iep_district';
	  //  $this->writevar1($sql,'the sql command');
	    $select=$database->fetchAll($sql);
	    return ($select);

	}

	function getKeys($id_county,$id_district){
	    $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
	    $database = Zend_Db::factory($dbConfig->db2);
	    $sql='SELECT edfi_key,edfi_secret from iep_district where id_county=\''.$id_county.'\' and id_district=\''.$id_district.'\'';
	    $select=$database->fetchrow($sql);
	    return ($select);
	}


	// Mike added 11-10-2017 this so that we can check for district being an edfi district

	function getDistrictUseEdfi($idDistrict, $idCounty)
	{

	//    $this->writevar1($idCounty." ".$idDistrict,' this is the district and county ');
	    $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
	    $database = Zend_Db::factory($dbConfig->db2);

	    /*sql2('SELECT t.name_first,t.name_last,t.id_district,t.id_county,t.id_personnel,p.class
	     from iep_personnel t,iep_privileges p
	     where t.id_personnel=p.id_personnel and p.status=\'Active\' and p.id_county=\''.$id_county.'\'  and
	     p.id_district=\''.$id_district.'\'and (p.class=\'2\' or p.class=\'3\') order by t.name_last');*/

	    $sql=('SELECT use_edfi from iep_district where id_district=\''.$idDistrict.'\' and id_county=\''.$idCounty.'\'');
	    // $this->writevar1($sql,'this is the sql statement');
	    $result=$database->fetchAll($sql);
	//    $this->writevar1($result,'this is the result lilne 45' );
	    $result=$result[0]['use_edfi'];

	    return $result;


	}

    function getDistrict($idCounty, $idDistrict)
    {
        $table = new $this->className();
        $select = $table->select()
            ->where( 'id_county = ?', $idCounty )
            ->where( 'id_district= ?', $idDistrict );
            return $table->fetchRow($select);


    }
    static function districtMultiOtions($idCounty, $privLimited = false) {
    	$retArray = array();

    	if(strlen($idCounty)<2) {
    		return false;
    	}

    	$table = new Model_Table_District();
    	$select = $table->select()
    		->where( "status ='Active'" )
    		->where( 'id_county = ?', $idCounty )
    		->order('name_district');
    	$districts = $table->fetchAll($select);

        if($privLimited) {
            /**
             * setup accessible districts
             */
            $usersession = new Zend_Session_Namespace ( 'user' );
            foreach($districts as $district) {
                foreach ($usersession->user->privs as $priv) {
                    if(1==$priv['class']) {
                        $retArray[$district['id_district']] = $district['name_district'];
                    } elseif((2==$priv['class'] || 3==$priv['class']) && $priv['id_county'] == $idCounty) {
                        $retArray[$district['id_district']] = $district['name_district'];
                    } elseif($priv['id_county'] == $idCounty && $district['id_district'] == $priv['id_district']) {
                        $retArray[$district['id_district']] = $district['name_district'];
                    }
                }
            }
        } else {
            foreach ($districts as $c) {
                $retArray[$c['id_district']] = $c['name_district'];
            }
        }


        asort($retArray);
        return $retArray;
    }

    static function districtMultiOptionsAll() {
    	$retArray = array();

    	$table = new Model_Table_District();
    	$districts = $table->fetchAll("status ='Active'");

    	foreach ($districts as $c) {
    		$retArray[$c['id_district']] = $c['name_district'];
    	}
        asort($retArray);
    	return $retArray;
    }
    static function allCountyDistrictArray() {
        /**
         * build an array of all county/districts
         */
        $districtObj = new Model_Table_District();
        $select = $districtObj->select()
            ->from('iep_district', array('id_county', 'id_district', 'name_district'))
            ->where( "status ='Active'" )
            ->order( "name_district" );
        $districts = $districtObj->fetchAll($select);
        $retArray = array();
        foreach($districts as $d) {
            $retArray[$d->id_county.'-'.$d->id_district] = $d->toArray();
        }
    	return $retArray;
    }

    static function getNonPublicDistricts($idCounty) {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(
                'iep_school_non_public',
                array(
                    'id_district',
                    'name',
                )
            )
            ->where("agency_record_type_code = ?", 'D')
            ->where("id_county = ?", $idCounty)
            ->group(array('id_district', 'name'))
            ->order('name');
        $results = $db->fetchAll($select);
        if($rowCount = count($results)) {
            $resultArray = array();
            foreach($results as $npDistrict) {
                //Array
                //(
                //    [01] => ADAMS
                //    [02] => ANTELOPE
                //)
                $resultArray[$npDistrict['id_district']] = $npDistrict['name'];
            }
            return $resultArray;
        } else {
            return array();
        }
    }

}

