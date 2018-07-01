<?php

/**
 * Model_Table_County
 *  
 * @author jlavere
 * @version 
 */
	
class Model_Table_County extends Zend_Db_Table_Abstract
{
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_county';
    protected $_primary = 'id_county';
    
    static function countyMultiOtions($privLimited = false) {
        $retArray = array();
        $countyObj = new Model_Table_County();
        $counties = $countyObj->fetchAll("status ='Active'", 'name_county');
        if($privLimited) {
            $usersession = new Zend_Session_Namespace ( 'user' );
            foreach ($counties as $c) {
                foreach ($usersession->user->privs as $priv) {
                    if(1==$priv['class']) {
                        // admin show all
                        $retArray[$c['id_county']] = $c['name_county'];
                    } elseif($priv['id_county'] == $c['id_county']) {
                        $retArray[$c['id_county']] = $c['name_county'];
                    }
                }
            }
        } else {
            foreach ($counties as $c) {
                $retArray[$c['id_county']] = $c['name_county'];
            }
        }

    	return $retArray;
    }

    static function countyMultiOtionsLimited($id_county) {
    	$retArray = array();
    	$countyObj = new Model_Table_County();
    	$counties = $countyObj->fetchAll("status = 'Active' and id_county = '$id_county'", 'name_county');
    	foreach ($counties as $c) {
    		$retArray[$c['id_county']] = $c['name_county'];
    	}
    	return $retArray;
    }

    static function getNonPublicCounties() {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from(
                'iep_school_non_public',
                array(
                    'id_county',
                    'county_name',
                )
            )
            ->group(array('id_county', 'county_name'))
            ->order('county_name');
        $results = $db->fetchAll($select);
        if($rowCount = count($results)) {
            foreach($results as $npCounty) {
                //Array
                //(
                //    [01] => ADAMS
                //    [02] => ANTELOPE
                //)
                if(count(Model_Table_District::getNonPublicDistricts($npCounty['id_county']))>0) {
                    $resultArray[$npCounty['id_county']] = $npCounty['county_name'];
                }
            }
            return $resultArray;
        } else {
            return array();
        }
    }

}

