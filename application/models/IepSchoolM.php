<?php

class Application_Model_DbTable_IeSchoolM  extends Zend_Db_Table_Abstract

{

    protected $_name = 'iep_school';

    public function getIepSchoolName($id_school)
    {
        $row = $this->fetchRow('id_school = ' . "'" . $id_school . "'");
        // writeit($row,"this is the row var in line 14 model\n");
        if (! $row) {
            throw new Exception("Could not find row $name_district");
        }
        // writeit($row->toArray(),"this is the row to array line 23 model_ieppersonnel.php \n");
        return $row->toArray();
    }

  
} 
  