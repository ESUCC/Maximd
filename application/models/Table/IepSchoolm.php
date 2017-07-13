<?php

class Model_Table_IepSchoolm  extends Zend_Db_Table_Abstract

{

    protected $_name = 'iep_school';

    public function getIepSchoolInfo($id_county,$id_district) {
      
        $all = $this->fetchAll($this->select()
               ->where('id_county = ?',$id_county)
               ->where('id_district = ?',$id_district));
               
        return $all->toArray();
         
    }
} 
  