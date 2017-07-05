<?php

class Model_Table_IepPersonnelInfo extends Zend_Db_Table_Abstract

{

    protected $_name = 'iep_personnel';

    public function iepPersonnelInfo($id_county,$id_district)
    {
        $where = "id_district = '$id_district' and id_county='$id_county' ";
        $row= $this->fetchRow($where);
       // $row = $this->fetchRow('id_county = ' . "'" . $id_county . "' "."and".' id_county = ' . "'" . $id_county . "')";
        // writeit($row,"this is the row var in line 14 model\n");
        if (! $row) {
            throw new Exception("Could not find row $name_county");
        }
        // writeit($row->toArray(),"this is the row to array line 23 model_ieppersonnel.php \n");
        return $row->toArray();
    }
    
    public function updatePassword($id_personnel,$password)
    {
        $data = array(
            'password'=>$password
            );
       $where= "id_personnel = '$id_personnel'";
        $this->update($data,$where);
    }
} 
  