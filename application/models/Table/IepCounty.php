<?php

class Model_Table_IepCounty extends Zend_Db_Table_Abstract

{

    protected $_name = 'iep_county';

    public function getIepCounty($id_county)
    {
        $row = $this->fetchRow('id_county = ' . "'" . $id_county . "'");
        // writeit($row,"this is the row var in line 14 model\n");
        if (! $row) {
            throw new Exception("Could not find row $name_county");
        }
        // writeit($row->toArray(),"this is the row to array line 23 model_ieppersonnel.php \n");
        return $row->toArray();
    }



    public function getCountyList()
    {
        $db = Zend_Registry::get('db');
        $county_row = array();

/*
        $select = $db->select()
                   ->distinct()
                   ->from( array('r' => 'iep_privileges'), array() )
                   ->joinLeft( array('c' => 'iep_county'), 'c.id_county = r.id_county', array('c.id_county', 'c.name_county') )
                   ->where( 'r.id_personnel = ?', $userid )
                   ->order( 'c.name_county asc' );
*/

        $county_row = $db->fetchAll($select);
        return array($county_row);
}


} 
  