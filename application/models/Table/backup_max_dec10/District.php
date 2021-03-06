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

    function getDistrict($idCounty, $idDistrict)
    {
        $table = new $this->className();
        $select = $table->select()            
            ->where( 'id_county = ?', $idCounty )
            ->where( 'id_district= ?', $idDistrict );
            return $table->fetchRow($select);


    }
    
    function getDistrictList()
    {
        $list=new $this->className();
        $select =$list->select('name_district')
         ->order('name_district');
        
         $results= $list->fetchAll($select);
         return $results;
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

