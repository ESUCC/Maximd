<?php

/**
 * iep_district
 *
 * @author jlavere
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_EdFiReport extends Zend_Db_Table_Abstract

{


    function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }

    public function getDistrictsResume($page, $maxRecs, $sortfield)
    {
        $db = Zend_Registry::get('db');

        $sql="select id_county, id_district, name_district, 0 as cok, 0 as cerr, 0 as cpen, 0 as chold, 0 as cnostate from iep_district order by " . $sortfield;
        $result = $db->fetchAll($sql);


        $paginator = Zend_Paginator::factory($result)->setItemCountPerPage($maxRecs)->setCurrentPageNumber(empty($page) ? 1 : $page);

        foreach($paginator as $index => &$district){
            $idd=$district["id_district"];
            $idc=$district["id_county"];
            $status= $this->getStatusForDistrict($idd, $idc);
            $district["cok"] =$status["cok"];
            $district["cerr"] =$status["cerr"];
            $district["cpen"] =$status["cpen"];
            $district["chold"] =$status["chold"];

            $db2 = Zend_Registry::get('db');
            $sql2="select count(*) as total from iep_student where id_county='" . $idc .  "' and id_district='" . $idd ."' and unique_id_state < 1000000000 " ;
            $total_nostateid=$db2->fetchCol($sql2);
            $district["cnostate"] =$total_nostateid[0];
        }

        return $paginator;

    }


     public function getDistrictsDetail($id_district, $id_county, $page, $maxRecs, $sortfield)
    {
        $db = Zend_Registry::get('db');
        $organizationalId=$id_county.$id_district.'000';
      //  $sql="select * from edfi where educationorganzationid like '" . $id_county . $id_district . "%' order by " . $sortfield;
      //  $sql="select * from edfi where educationorganzationid like '" . $id_county . $id_district . "%' order by edfipublishstatus,name_last" . $sortfield;
        $sql="select * from edfi where educationorganzationid='".$organizationalId."' order by edfipublishstatus,id_school,name_last ";


        $result = $db->fetchAll($sql);

        $paginator = Zend_Paginator::factory($result)->setItemCountPerPage($maxRecs)->setCurrentPageNumber(empty($page) ? 1 : $page);

        return $paginator;

    }


    // Mike added this 3-16-2018 so that we can search easily in jquery.  COme back to this latter.
    public function getDistrictsDetailNoPag($id_district, $id_county, $page, $maxRecs, $sortfield)
    {
        $db = Zend_Registry::get('db');
        $organizationalId=$id_county.$id_district.'000';
        //  $sql="select * from edfi where educationorganzationid like '" . $id_county . $id_district . "%' order by " . $sortfield;
        //  $sql="select * from edfi where educationorganzationid like '" . $id_county . $id_district . "%' order by edfipublishstatus,name_last" . $sortfield;
        $sql="select * from edfi where educationorganzationid='".$organizationalId."' order by edfipublishstatus,id_school,name_last ";


        $result = $db->fetchAll($sql);



        return $result;

    }

    public function getStatusForDistrict($iddistrict, $county){

        $count_ok=0;
        $count_error=0;
        $count_pending=0;
        $count_hold=0;

        $db = Zend_Registry::get('db');
        $sql=   "select e.edfipublishstatus, count(e.edfipublishstatus) as count ".
                "from iep_district d, iep_student s , edfi e ".
                "where d.id_district='" . $iddistrict . "' and d.id_county='" . $county . "' and s.id_district=d.id_district and e.id_student=s.id_student ".
                "group by e.edfipublishstatus";

        $results = $db->fetchAll($sql);

       foreach($results as $r){

            switch($r["edfipublishstatus"])
            {
                case "W":
                    $count_pending=$r["count"];
                break;

                case "S":
                    $count_ok=$r["count"];
                break;

                case "E":
                    $count_error=$r["count"];
                break;

                case "H":
                    $count_hold=$r["count"];
                break;
            }
        }

        return array("cok" => $count_ok,"cerr" => $count_error,"cpen" =>  $count_pending, "chold" => $count_hold);

    }


}
