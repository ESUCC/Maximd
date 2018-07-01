<?php

class Application_Model_IepDistrict extends Zend_Db_Table_Abstract

{

    protected $_name = 'iep_district';

    public function getIepDistrict($name_district)
    {
        $row = $this->fetchRow('name_district = ' . "'" . $name_district . "'");
        // writeit($row,"this is the row var in line 14 model\n");
        if (! $row) {
            throw new Exception("Could not find row $name_district");
        }
        // writeit($row->toArray(),"this is the row to array line 23 model_ieppersonnel.php \n");
        return $row->toArray();
    }

    public function updateIepDistrict($name_district, $id_district,$address_zip, $id_county, $phone_main, $address_street1, $add_resouce1)
    {
        /*
         * $data = array(
         * 'name_district' => $name_district,
         * 'id_district' => $id_district,
         * 'id_county' => $id_county,
         * 'phone_main'=> $phone_main
         * );
         */
        $data = array(
            'phone_main' => $phone_main,
            'address_street1' => $address_street1
        )
        ;
        $where = "id_district = '$id_district' and id_county='$id_county' ";
        // $where[] = "id_county = '$id_county'";
        
        // $this->update($data, 'name_district = '."'". $name_district ."'");
        $this->update($data, $where);
    }

    public function sortIepDistrict($conjunct)
    {
        // $d=array();
        
        // $outf= new Zend_Writeit;
        
        /*
         * $id_district=$dis_nun->districtAction($id_district);
         *
         * $d=$this->district($id_district);
         * $id_district=$d[0];
         * $id_county=$d[1];
         * // $id_district=$this->district($id_district);
         * $id_district='\''.$id_district . '\'';
         * $id_county='\''.$id_county . '\'';
         * $name_first ='\''.$name_first . '\'';
         * $name_last = '\''.$name_last . '\'';
         * $searchnames='(name_first = ' . $name_first.")";
         */
        $row = $this->fetchAll($this->select()
            ->order($conjunct));
        // $row=$this->fetchAll($this->select()->where("(name_first= $name_first or name_last= $name_last) $conjunct (id_district= $id_district and id_county=$id_county)")->order('name_last'));
        // $row = $this->fetchAll('name_first = ' . $t);
        // $row=$this->fetchAll($this->select()->where("name_first= $name_first and id_county= $id_county"));
        // fetchAll($iep_personnel->select()->where("id_district='0001' and id_county='77' ")->order("name_first"));
        if (! $row) {
            throw new Exception("Could not find row $name_first");
        }
        // die(); This returns an sql statement that is correct in zendserver.
        return $row;
    }
} 
  