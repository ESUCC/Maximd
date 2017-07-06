<?php

class Model_Table_IepPersonnel extends Zend_Db_Table_Abstract
{

    protected $_name = 'iep_personnel'; 
    protected $_primary = 'id_personnel';
    
    public function getPassword($user){
        $row = $this->fetchRow('user_name = '."'". $user."'");
        return $row['password'];
    }
    public function getNameFromPrivTable($cty,$district,$school,$status='true') {
        $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $database = Zend_Db::factory($dbConfig->db2);
        // writevar($database,'this is the database'); die();
    
        $user_id  = $_SESSION["user"]["user"]->user["id_personnel"];
       
       if($status=='true'){
        $sql =('SELECT p.name_first,p.name_last,p.id_personnel, p.user_name,r.class,r.status,d.name_district,r.id_school from iep_personnel p,
               iep_privileges r, iep_district d where p.id_county=d.id_county and p.id_district=d.id_district and p.id_personnel=r.id_personnel
               and r.status= \'Active\' and r.id_county=\''.$cty.'\' and r.id_district=\''.$district.'\'
               and r.id_school=\''.$school.'\'order  by p.name_last');
         
       }
       if($status=='false')
       {
           $sql =('SELECT p.name_first,p.name_last,p.id_personnel, p.user_name,r.class,r.status,d.name_district,r.id_school from iep_personnel p,
               iep_privileges r, iep_district d where p.id_county=d.id_county and p.id_district=d.id_district and p.id_personnel=r.id_personnel
               and r.id_county=\''.$cty.'\' and r.id_district=\''.$district.'\' and r.status =\'Inactive\'
               and r.id_school=\''.$school.'\'order  by p.name_last');
       }
       
       if($status=='removed')
       {
           $sql =('SELECT p.name_first,p.name_last,p.id_personnel, p.user_name,r.class,r.status,d.name_district,r.id_school from iep_personnel p,
               iep_privileges r, iep_district d where p.id_county=d.id_county and p.id_district=d.id_district and p.id_personnel=r.id_personnel
               and r.id_county=\''.$cty.'\' and r.id_district=\''.$district.'\' and r.status =\'Removed\'
               and r.id_school=\''.$school.'\'order  by p.name_last');
       }
        $result=$database->fetchAll($sql);
    
        return $result;
         }
    
       

         public function getAdmins($cty,$district) {
             $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
             $database = Zend_Db::factory($dbConfig->db2);
       
              
             $sql =('SELECT p.name_first,p.name_last,p.id_personnel, p.user_name,r.class,r.status,d.name_district,r.id_school from iep_personnel p,
               iep_privileges r, iep_district d where  p.id_county=d.id_county and p.id_district=d.id_district and p.id_personnel=r.id_personnel
               and r.status= \'Active\' and r.id_county=\''.$cty.'\' and r.id_district=\''.$district.'\'
               and (r.class=\'2\' or r.class=\'3\')  order  by p.name_last');
              
             $result=$database->fetchAll($sql);
          
         
             return $result;
         }   
         
         
    
    public function getIepPersonnel($id_personnel)
    {
        
       //NOTE: in the district view sometimes this does not work. If you put in an else statement after ! $row and throw no exception with else return then it works.
      
        $id_personnel = (int) $id_personnel;
        $row = $this->fetchRow('id_personnel = ' . $id_personnel);
        // writeit($row,"this is the row var in line 14 model\n");
      if (! $row) {
       //  throw new Exception("Could not find row $id_personnel");
       }
      else {
        return $row->toArray();
      }
    }

    public function searchIepPersonnel($name_first, $name_last, $id_county, $conjunct, $id_district)
    {
        $d = array();
        
        // $outf= new Zend_Writeit;
        // $id_district=$dis_nun->districtAction($id_district);
        
        $d = $this->district($id_district);
        $id_district = $d[0];
        $id_county = $d[1];
        // $id_district=$this->district($id_district);
        $id_district = '\'' . $id_district . '\'';
        $id_county = '\'' . $id_county . '\'';
        $name_first = '\'' . $name_first . '\'';
        $name_last = '\'' . $name_last . '\'';
        $searchnames = '(name_first = ' . $name_first . ")";
        
        // $row=$this->fetchAll($this->select()->where($seachnames));
        $row = $this->fetchAll($this->select()
            ->where("(name_first= $name_first or name_last= $name_last) $conjunct (id_district= $id_district and id_county=$id_county)")
            ->order('name_last'));
        // $row = $this->fetchAll('name_first = ' . $t);
        // $row=$this->fetchAll($this->select()->where("name_first= $name_first and id_county= $id_county"));
        // fetchAll($iep_personnel->select()->where("id_district='0001' and id_county='77' ")->order("name_first"));
        if (! $row) {
            throw new Exception("Could not find row $name_first");
        }
        // die(); This returns an sql statement that is correct in zendserver.
        return $row;
    }

    public function addIepPersonnel($name_first, $name_last, $id_county, $id_district, $email_address)
    {
        $data = array(
            'name_first' => $name_first,
            'name_last' => $name_last,
            'id_county' => $id_county,
            'id_district' => $id_district,
            'email_address' => $email_address,
            ''
        );
        $this->insert($data);
    }

    public function updateIepPersonnel($id_personnel, $name_first, $name_last, $id_county, $id_district, $email_address)
    {
        $data = array(
            'name_first' => $name_first,
            'name_last' => $name_last,
            'id_county' => $id_county,
            'id_district' => $id_district,
            'email_address' => $email_address
        );
        $this->update($data, 'id_personnel= ' . (int) $id_personnel);
    }
    
    public function updateIepPersonnelPassReset($personnelInfo)
    {
        $data = array(
            'password_reset_flag' => $personnelInfo['password_reset_flag']
        );
        $this->update($data, 'id_personnel= ' . (int) $personnelInfo['id_personnel']);
    }
    

    public function deleteIepPersonnel($id_personnel) 
    {
        $this->delete('id_personnel =' . (int) $id_personnel);
    } 

    public function district($districtName)
    {
        $dd = array();
        switch ($districtName) {
            case "Adams Central Public Schools":
                $dd[0] = '0090'; 
                $dd[1] = '01';
                return ($dd);
                break;
            case "Ainsworth Community Schools":
                $dd[0] = '0010';
                $dd[1] = '09';
                return ($dd);
                break;
            case "Allen Consolidated Schools":
                $dd[0] = '0070';
                $dd[1] = '26';
                return ($dd);
                break;
            case "Alliance Public Schools":
                $dd[0] = '0006';
                $dd[1] = '07';
                return ($dd);
                break;
            case "Alma Public Schools":
                $dd[0] = '0002';
                $dd[1] = '42';
                return ($dd);
                break;
            case "Amherst Public Schools":
                $dd[0] = '0119';
                $dd[1] = '10';
                return ($dd);
                break;
            case "Anselmo-Merna Public Schools":
                $dd[0] = '0015';
                $dd[1] = '21';
                return ($dd);
                break;
            case "Ansley Public Schools":
                $dd[0] = '0044';
                $dd[1] = '21';
                return ($dd);
                break;
            case "Arapahoe Public Schools":
                $dd[0] = '0018';
                $dd[1] = '33';
                return ($dd);
                break;
            case "Arcadia Public Schools":
                $dd[0] = '0021';
                $dd[1] = '88';
                return ($dd);
                break;
            case "Arlington Public Schools":
                $dd[0] = '0024';
                $dd[1] = '89';
                return ($dd);
                break;
            case "Arnold Public Schools":
                $dd[0] = '0089';
                $dd[1] = '21';
                return ($dd);
                break;
            case "Arthur County Schools":
                $dd[0] = '0500';
                $dd[1] = '03';
                return ($dd);
                break;
            case "Ashland-Greenwood Public Schs":
                $dd[0] = '0001';
                $dd[1] = '78';
                return ($dd);
                break;
            case "Auburn Public Schools":
                $dd[0] = '0029';
                $dd[1] = '64';
                return ($dd);
                break;
            case "Aurora Public Schools":
                $dd[0] = '0504';
                $dd[1] = '41';
                return ($dd);
                break;
            case "Axtell Community Schools":
                $dd[0] = '0501';
                $dd[1] = '50';
                return ($dd);
                break;
            case "Bancroft-Rosalie Comm Schools":
                $dd[0] = '0020';
                $dd[1] = '20';
                return ($dd);
                break;
            case "Banner County Public Schools":
                $dd[0] = '0001';
                $dd[1] = '04';
                return ($dd);
                break;
            case "Battle Creek Public Schools":
                $dd[0] = '0005';
                $dd[1] = '59';
                return ($dd);
                break;
            case "Bayard Public Schools":
                $dd[0] = '0021';
                $dd[1] = '62';
                return ($dd);
                break;
            case "Beatrice Public Schools":
                $dd[0] = '0015';
                $dd[1] = '34';
                return ($dd);
                break;
            case "Bellevue Public Schools":
                $dd[0] = '0001';
                $dd[1] = '77';
                return ($dd);
                break;
            case "Bennington Public Schools":
                $dd[0] = '0059';
                $dd[1] = '28';
                return ($dd);
                break;
            case "Bertrand Public Schools":
                $dd[0] = '0054';
                $dd[1] = '69';
                return ($dd);
                break;
            case "Blair Community Schools":
                $dd[0] = '0001';
                $dd[1] = '89';
                return ($dd);
                break;
            case "Bloomfield Community Schools":
                $dd[0] = '0586';
                $dd[1] = '54';
                return ($dd);
                break;
            case "Blue Hill Public Schools":
                $dd[0] = '0074';
                $dd[1] = '91';
                return ($dd);
                break;
            case "Boone Central School District #1":
                $dd[0] = '0001';
                $dd[1] = '06';
                return ($dd);
                break;
            case "Brady Public Schools":
                $dd[0] = '0006';
                $dd[1] = '56';
                return ($dd);
                break;
            case "Bridgeport Public Schools":
                $dd[0] = '0063';
                $dd[1] = '62';
                return ($dd);
                break;
            case "Broken Bow Public Schools":
                $dd[0] = '0025';
                $dd[1] = '21';
                return ($dd);
                break;
            case "Brook Valley":
                $dd[0] = '0303';
                $dd[1] = '99';
                return ($dd);
                break;
            case "Bruning-Davenport Unified Sch":
                $dd[0] = '2001';
                $dd[1] = '85';
                return ($dd);
                break;
            case "Burwell Public Schools":
                $dd[0] = '0100';
                $dd[1] = '36';
                return ($dd);
                break;
            case "Callaway Public Schools":
                $dd[0] = '0180';
                $dd[1] = '21';
                return ($dd);
                break;
            case "Cambridge Public Schools":
                $dd[0] = '0021';
                $dd[1] = '33';
                return ($dd);
                break;
            case "Cedar Bluffs Public Schools":
                $dd[0] = '0107';
                $dd[1] = '78';
                return ($dd);
                break;
            case "Cedar Rapids Public Schools":
                $dd[0] = '0006';
                $dd[1] = '06';
                return ($dd);
                break;
            case "Centennial Public Schools":
                $dd[0] = '0567';
                $dd[1] = '80';
                return ($dd);
                break;
            case "Center Public School":
                $dd[0] = '0028';
                $dd[1] = '10';
                return ($dd);
                break;
            case "Central City Public Schools":
                $dd[0] = '0004';
                $dd[1] = '61';
                return ($dd);
                break;
            case "Central Valley Public Schools":
                $dd[0] = '0060';
                $dd[1] = '39';
                return ($dd);
                break;
            case "Centura Public Schools":
                $dd[0] = '0100';
                $dd[1] = '47';
                return ($dd);
                break;
            case "Chadron Public Schools":
                $dd[0] = '0002';
                $dd[1] = '23';
                return ($dd);
                break;
            case "Chadron State College":
                $dd[0] = '9000';
                $dd[1] = '23';
                return ($dd);
                break;
            case "Chambers Public Schools":
                $dd[0] = '0137';
                $dd[1] = '45';
                return ($dd);
                break;
            case "Chase County School":
                $dd[0] = '0010';
                $dd[1] = '15';
                return ($dd);
                break;
            case "Cheney Public School":
                $dd[0] = '0153';
                $dd[1] = '55';
                return ($dd);
                break;
            case "Clarkson Public Schools":
                $dd[0] = '0058';
                $dd[1] = '19';
                return ($dd);
                break;
            case "Clear Creek Public School":
                $dd[0] = '0003';
                $dd[1] = '78';
                return ($dd);
                break;
            case "Cody-Kilgore Public Schs":
                $dd[0] = '0030';
                $dd[1] = '16';
                return ($dd);
                break;
            case "Coleridge Community Schools":
                $dd[0] = '0541';
                $dd[1] = '14';
                return ($dd);
                break;
            case "Columbus Public Schools":
                $dd[0] = '0001';
                $dd[1] = '71';
                return ($dd);
                break;
            case "Concordia University":
                $dd[0] = '8888';
                $dd[1] = '80';
                return ($dd);
                break;
            case "Conestoga Public Schools":
                $dd[0] = '0056';
                $dd[1] = '13';
                return ($dd);
                break;
            case "Cozad Community Schools":
                $dd[0] = '0011';
                $dd[1] = '24';
                return ($dd);
                break;
            case "Crawford Public Schools":
                $dd[0] = '0071';
                $dd[1] = '23';
                return ($dd);
                break;
            case "Creek Valley Public Schools":
                $dd[0] = '0025';
                $dd[1] = '25';
                return ($dd);
                break;
            case "Creighton Public Schools":
                $dd[0] = '0013';
                $dd[1] = '54';
                return ($dd);
                break;
            case "Crete Public Schools":
                $dd[0] = '0002';
                $dd[1] = '76';
                return ($dd);
                break;
            case "Crofton Community Schools":
                $dd[0] = '0096';
                $dd[1] = '54';
                return ($dd);
                break;
            case "Cross County Community Schools":
                $dd[0] = '0015';
                $dd[1] = '72';
                return ($dd);
                break;
            case "David City Public Schools":
                $dd[0] = '0056';
                $dd[1] = '12';
                return ($dd);
                break;
            case "DELETE THESE KIDS":
                $dd[0] = '7834';
                $dd[1] = '99';
                return ($dd);
                break;
            case "Demo District":
                $dd[0] = '9999';
                $dd[1] = '99';
                return ($dd);
                break;
            case "Deshler Public Schools":
                $dd[0] = '0060';
                $dd[1] = '85';
                return ($dd);
                break;
            case "Diller-Odell Public Schools":
                $dd[0] = '0100';
                $dd[1] = '34';
                return ($dd);
                break;
            case "Dist 015 - Dawson County":
                $dd[0] = '0015';
                $dd[1] = '24';
                return ($dd);
                break;
            case "Dist 033 - Platte County":
                $dd[0] = '0033';
                $dd[1] = '71';
                return ($dd);
                break;
            case "Dist 039 - Box Butte County":
                $dd[0] = '0039';
                $dd[1] = '07';
                return ($dd);
                break;
            case "Dist 041 - Red Willow County":
                $dd[0] = '0041';
                $dd[1] = '73';
                return ($dd);
                break;
            case "Dist 042 - Box Butte County":
                $dd[0] = '0042';
                $dd[1] = '07';
                return ($dd);
                break;
            case "Dist 044 - Box Butte County":
                $dd[0] = '0044';
                $dd[1] = '07';
                return ($dd);
                break;
            case "Dist 065 - Buffalo County":
                $dd[0] = '0065';
                $dd[1] = '10';
                return ($dd);
                break;
            case "Dist 082 - Cuming County":
                $dd[0] = '0082';
                $dd[1] = '20';
                return ($dd);
                break;
            case "Dist 505 - Colfax County":
                $dd[0] = '0505';
                $dd[1] = '19';
                return ($dd);
                break;
            case "District 145 - Waverly Public Schools":
                $dd[0] = '0145';
                $dd[1] = '55';
                return ($dd);
                break;
            case "DISTRICT A":
                $dd[0] = '0101';
                $dd[1] = '99';
                return ($dd);
                break;
            case "DISTRICT B":
                $dd[0] = '0202';
                $dd[1] = '99';
                return ($dd);
                break;
            case "DOANE COLLEGE":
                $dd[0] = '5959';
                $dd[1] = '76';
                return ($dd);
                break;
            case "Dodge Public Schools":
                $dd[0] = '0046';
                $dd[1] = '27';
                return ($dd);
                break;
            case "Doniphan-Trumbull":
                $dd[0] = '0126';
                $dd[1] = '40';
                return ($dd);
                break;
            case "Dorchester Public Schools":
                $dd[0] = '0044';
                $dd[1] = '76';
                return ($dd);
                break;
            case "Douglas Co. West Comm. Schools":
                $dd[0] = '0015';
                $dd[1] = '28';
                return ($dd);
                break;
            case "Dundy County Public Schools":
                $dd[0] = '0117';
                $dd[1] = '29';
                return ($dd);
                break;
            case "EARTH DISTRICT":
                $dd[0] = '7755';
                $dd[1] = '99';
                return ($dd);
                break;
            case "East Butler Public Schools":
                $dd[0] = '0502';
                $dd[1] = '12';
                return ($dd);
                break;
            case "Elba Public Schools":
                $dd[0] = '0103';
                $dd[1] = '47';
                return ($dd);
                break;
            case "Elgin Public Schools":
                $dd[0] = '0018';
                $dd[1] = '02';
                return ($dd);
                break;
            case "Elkhorn Public Schools":
                $dd[0] = '0010';
                $dd[1] = '28';
                return ($dd);
                break;
            case "Elkhorn Valley Schools":
                $dd[0] = '0080';
                $dd[1] = '59';
                return ($dd);
                break;
            case "Elm Creek Public Schools":
                $dd[0] = '0009';
                $dd[1] = '10';
                return ($dd);
                break;
            case "Elmwood-Murdock Public Schools":
                $dd[0] = '0097';
                $dd[1] = '13';
                return ($dd);
                break;
            case "Elwood Public Schools":
                $dd[0] = '0030';
                $dd[1] = '37';
                return ($dd);
                break;
            case "Emerson-Hubbard Public Schs":
                $dd[0] = '0561';
                $dd[1] = '87';
                return ($dd);
                break;
            case "Eustis-Farnam Public Schools":
                $dd[0] = '0095';
                $dd[1] = '32';
                return ($dd);
                break;
            case "Ewing Public Schools":
                $dd[0] = '0029';
                $dd[1] = '45';
                return ($dd);
                break;
            case "Exeter-Milligan Public Schools":
                $dd[0] = '0001';
                $dd[1] = '30';
                return ($dd);
                break;
            case "Extension Public School":
                $dd[0] = '0129';
                $dd[1] = '81';
                return ($dd);
                break;
            case "Fairbury Public Schools":
                $dd[0] = '0008';
                $dd[1] = '48';
                return ($dd);
                break;
            case "Falls City Public Schools":
                $dd[0] = '0056';
                $dd[1] = '74';
                return ($dd);
                break;
            case "Fillmore Central Public Schs":
                $dd[0] = '0025';
                $dd[1] = '30';
                return ($dd);
                break;
            case "FORMS_TEST_DIST":
                $dd[0] = '0009';
                $dd[1] = '99';
                return ($dd);
                break;
            case "Fort Calhoun Community Schs":
                $dd[0] = '0003';
                $dd[1] = '89';
                return ($dd);
                break;
            case "Franklin Public Schools":
                $dd[0] = '0506';
                $dd[1] = '31';
                return ($dd);
                break;
            case "Freeman Public Schools":
                $dd[0] = '0034';
                $dd[1] = '34';
                return ($dd);
                break;
            case "Fremont Public Schools":
                $dd[0] = '0001';
                $dd[1] = '27';
                return ($dd);
                break;
            case "Friend Public Schools":
                $dd[0] = '0068';
                $dd[1] = '76';
                return ($dd);
                break;
            case "Fullerton Public Schools":
                $dd[0] = '0001';
                $dd[1] = '63';
                return ($dd);
                break;
            case "Garden County Schools":
                $dd[0] = '0001';
                $dd[1] = '35';
                return ($dd);
                break;
            case "Garfield Public School":
                $dd[0] = '0003';
                $dd[1] = '12';
                return ($dd);
                break;
            case "Geneva North School":
                $dd[0] = '0600';
                $dd[1] = '30';
                return ($dd);
                break;
            case "Gering Public Schools":
                $dd[0] = '0016';
                $dd[1] = '79';
                return ($dd);
                break;
            case "Gibbon Public Schools":
                $dd[0] = '0002';
                $dd[1] = '10';
                return ($dd);
                break;
            case "Giltner Public Schools":
                $dd[0] = '0002';
                $dd[1] = '41';
                return ($dd);
                break;
            case "Golden Rule Public School":
                $dd[0] = '0133';
                $dd[1] = '81';
                return ($dd);
                break;
            case "Good Cheer Public School":
                $dd[0] = '0048';
                $dd[1] = '59';
                return ($dd);
                break;
            case "GORDON-RUSHVILLE PUBLIC SCHOOLS":
                $dd[0] = '0010';
                $dd[1] = '81';
                return ($dd);
                break;
            case "Gothenburg Public Schools":
                $dd[0] = '0020';
                $dd[1] = '24';
                return ($dd);
                break;
            case "Grand Island Public Schools":
                $dd[0] = '0002';
                $dd[1] = '40';
                return ($dd);
                break;
            case "Greeley-Wolbach":
                $dd[0] = '0010';
                $dd[1] = '39';
                return ($dd);
                break;
            case "Gretna Public Schools":
                $dd[0] = '0037';
                $dd[1] = '77';
                return ($dd);
                break;
            case "Hampton Public Schools":
                $dd[0] = '0091';
                $dd[1] = '41';
                return ($dd);
                break;
            case "Hartington-Newcastle Public Schools":
                $dd[0] = '0008';
                $dd[1] = '14';
                return ($dd);
                break;
            case "Harvard Public Schools":
                $dd[0] = '0011';
                $dd[1] = '18';
                return ($dd);
                break;
            case "Hastings Public Schools":
                $dd[0] = '0018';
                $dd[1] = '01';
                return ($dd);
                break;
            case "Hayes Center Public Schools":
                $dd[0] = '0079';
                $dd[1] = '43';
                return ($dd);
                break;
            case "Hay Springs Public Schools":
                $dd[0] = '0003';
                $dd[1] = '81';
                return ($dd);
                break;
            case "Heartland Community Schools":
                $dd[0] = '0096';
                $dd[1] = '93';
                return ($dd);
                break;
            case "Hemingford Public Schools":
                $dd[0] = '0010';
                $dd[1] = '07';
                return ($dd);
                break;
            case "Hershey Public Schools":
                $dd[0] = '0037';
                $dd[1] = '56';
                return ($dd);
                break;
            case "High Plains Community Schools":
                $dd[0] = '0075';
                $dd[1] = '72';
                return ($dd);
                break;
            case "Hitchcock County School District":
                $dd[0] = '0070';
                $dd[1] = '44';
                return ($dd);
                break;
            case "Hodor Public Schools":
                $dd[0] = '6868';
                $dd[1] = '99';
                return ($dd);
                break;
            case "Holdrege Public Schools":
                $dd[0] = '0044';
                $dd[1] = '69';
                return ($dd);
                break;
            case "Homer Community Schools":
                $dd[0] = '0031';
                $dd[1] = '22';
                return ($dd);
                break;
            case "Howells-Dodge Consolidated":
                $dd[0] = '0070';
                $dd[1] = '19';
                return ($dd);
                break;
            case "Howells-Dodge Unified":
                $dd[0] = '2001';
                $dd[1] = '19';
                return ($dd);
                break;
            case "Howells Public Schools":
                $dd[0] = '0059';
                $dd[1] = '19';
                return ($dd);
                break;
            case "H.T.R.S. Public Schools":
                $dd[0] = '0070';
                $dd[1] = '74';
                return ($dd);
                break;
            case "Humphrey Public Schools":
                $dd[0] = '0067';
                $dd[1] = '71';
                return ($dd);
                break;
            case "Hyannis Area Schools":
                $dd[0] = '0011';
                $dd[1] = '38';
                return ($dd);
                break;
            case "IEPWEB Test District 1":
                $dd[0] = '7685';
                $dd[1] = '99';
                return ($dd);
                break;
            case "IEPWEB Test District 2":
                $dd[0] = '8695';
                $dd[1] = '99';
                return ($dd);
                break;
            case "IFSP DISTRICT":
                $dd[0] = '4545';
                $dd[1] = '99';
                return ($dd);
                break;
            case "Inactive Real Students":
                $dd[0] = '5555';
                $dd[1] = '99';
                return ($dd);
                break;
            case "Johnson-Brock Public Schools":
                $dd[0] = '0023';
                $dd[1] = '64';
                return ($dd);
                break;
            case "Johnson County Central":
                $dd[0] = '0050';
                $dd[1] = '49';
                return ($dd);
                break;
            case "Johnstown Public School":
                $dd[0] = '0009';
                $dd[1] = '09';
                return ($dd);
                break;
            case "Kearney Public Schools":
                $dd[0] = '0007';
                $dd[1] = '10';
                return ($dd);
                break;
            case "Kenesaw Public Schools":
                $dd[0] = '0003';
                $dd[1] = '01';
                return ($dd);
                break;
            case "Keya Paha County Public Schools":
                $dd[0] = '0100';
                $dd[1] = '52';
                return ($dd);
                break;
            case "Kimball Public Schools":
                $dd[0] = '0001';
                $dd[1] = '53';
                return ($dd);
                break;
            case "Lake Alice Public School":
                $dd[0] = '0065';
                $dd[1] = '79';
                return ($dd);
                break;
            case "Lake Minatare Public School":
                $dd[0] = '0064';
                $dd[1] = '79';
                return ($dd);
                break;
            case "Lakeview Community Schools":
                $dd[0] = '0005';
                $dd[1] = '71';
                return ($dd);
                break;
            case "Laurel-Concord-Coleridge School":
                $dd[0] = '0054';
                $dd[1] = '14';
                return ($dd);
                break;
            case "Leigh Community Schools":
                $dd[0] = '0039';
                $dd[1] = '19';
                return ($dd);
                break;
            case "Lewiston Consolidated Schools":
                $dd[0] = '0069';
                $dd[1] = '67';
                return ($dd);
                break;
            case "Lexington Public Schools":
                $dd[0] = '0001';
                $dd[1] = '24';
                return ($dd);
                break;
            case "Leyton Public Schools":
                $dd[0] = '0003';
                $dd[1] = '17';
                return ($dd);
                break;
            case "Lincoln Public Schools ":
                $dd[0] = '0001';
                $dd[1] = '55';
                return ($dd);
                break;
            case "Litchfield Public Schools":
                $dd[0] = '0015';
                $dd[1] = '82';
                return ($dd);
                break;
            case "Logan View Public Schools":
                $dd[0] = '0594';
                $dd[1] = '27';
                return ($dd);
                break;
            case "Log Test":
                $dd[0] = '0123';
                $dd[1] = '99';
                return ($dd);
                break;
            case "Loomis Public Schools":
                $dd[0] = '0055';
                $dd[1] = '69';
                return ($dd);
                break;
            case "Lorenzo Public School":
                $dd[0] = '0033';
                $dd[1] = '17';
                return ($dd);
                break;
            case "Louisville Public Schools":
                $dd[0] = '0032';
                $dd[1] = '13';
                return ($dd);
                break;
            case "Loup City Public Schools":
                $dd[0] = '0001';
                $dd[1] = '82';
                return ($dd);
                break;
            case "Loup County Public Schools":
                $dd[0] = '0025';
                $dd[1] = '58';
                return ($dd);
                break;
            case "Lynch Public Schools":
                $dd[0] = '0036';
                $dd[1] = '08';
                return ($dd);
                break;
            case "Lyons-Decatur Northeast Schs":
                $dd[0] = '0020';
                $dd[1] = '11';
                return ($dd);
                break;
            case "Madison Public Schools":
                $dd[0] = '0001';
                $dd[1] = '59';
                return ($dd);
                break;
            case "Malcolm Public Schools":
                $dd[0] = '0148';
                $dd[1] = '55';
                return ($dd);
                break;
            case "Malmo Public School":
                $dd[0] = '0036';
                $dd[1] = '78';
                return ($dd);
                break;
            case "MARS DISTRICT":
                $dd[0] = '6677';
                $dd[1] = '99';
                return ($dd);
                break;
            case "Maxwell Public Schools":
                $dd[0] = '0007';
                $dd[1] = '56';
                return ($dd);
                break;
            case "Maywood Public Schools":
                $dd[0] = '0046';
                $dd[1] = '32';
                return ($dd);
                break;
            case "McCook Public Schools":
                $dd[0] = '0017';
                $dd[1] = '73';
                return ($dd);
                break;
            case "Mc Cool Junction Public Schs":
                $dd[0] = '0083';
                $dd[1] = '93';
                return ($dd);
                break;
            case "McPherson County Schools":
                $dd[0] = '0090';
                $dd[1] = '60';
                return ($dd);
                break;
            case "Mead Public Schools":
                $dd[0] = '0072';
                $dd[1] = '78';
                return ($dd);
                break;
            case "Medicine Valley Public Schools":
                $dd[0] = '0125';
                $dd[1] = '32';
                return ($dd);
                break;
            case "Meridian Public":
                $dd[0] = '0303';
                $dd[1] = '48';
                return ($dd);
                break;
            case "MIDLAND":
                $dd[0] = '9999';
                $dd[1] = '27';
                return ($dd);
                break;
            case "Milford Public Schools":
                $dd[0] = '0005';
                $dd[1] = '80';
                return ($dd);
                break;
            case "Millard Public Schools":
                $dd[0] = '0017';
                $dd[1] = '28';
                return ($dd);
                break;
            case "Minatare Public Schools":
                $dd[0] = '0002';
                $dd[1] = '79';
                return ($dd);
                break;
            case "Minden Public Schools":
                $dd[0] = '0503';
                $dd[1] = '50';
                return ($dd);
                break;
            case "Mitchell Public Schools":
                $dd[0] = '0031';
                $dd[1] = '79';
                return ($dd);
                break;
            case "Morrill Public Schools":
                $dd[0] = '0011';
                $dd[1] = '79';
                return ($dd);
                break;
            case "Mullen Public Schools":
                $dd[0] = '0001';
                $dd[1] = '46';
                return ($dd);
                break;
            case "name_district":
                $dd[0] = 'id_district';
                $dd[1] = 'id_county';
                return ($dd);
                break;
            case "NDE-DEMO":
                $dd[0] = '5494';
                $dd[1] = '99';
                return ($dd);
                break;
            case "NDE-DEMO":
                $dd[0] = '7890';
                $dd[1] = '55';
                return ($dd);
                break;
            case "Nebraska City Public Schools":
                $dd[0] = '0111';
                $dd[1] = '66';
                return ($dd);
                break;
            case "Nebraska Unified District # 1":
                $dd[0] = '2001';
                $dd[1] = '02';
                return ($dd);
                break;
            case "Nebraska Wesleyan University":
                $dd[0] = '9000';
                $dd[1] = '55';
                return ($dd);
                break;
            case "Neligh-Oakdale Schools":
                $dd[0] = '0009';
                $dd[1] = '02';
                return ($dd);
                break;
            case "Nemaha Valley Schools":
                $dd[0] = '0501';
                $dd[1] = '49';
                return ($dd);
                break;
            case "Newcastle Public Schools":
                $dd[0] = '0024';
                $dd[1] = '26';
                return ($dd);
                break;
            case "Newman Grove Public Schools":
                $dd[0] = '0013';
                $dd[1] = '59';
                return ($dd);
                break;
            case "Niobrara Public Schools":
                $dd[0] = '0501';
                $dd[1] = '54';
                return ($dd);
                break;
            case "Norfolk Public Schools":
                $dd[0] = '0002';
                $dd[1] = '59';
                return ($dd);
                break;
            case "Norris School Dist 160":
                $dd[0] = '0160';
                $dd[1] = '55';
                return ($dd);
                break;
            case "North Bend Central Public Schs":
                $dd[0] = '0595';
                $dd[1] = '27';
                return ($dd);
                break;
            case "North Loup Scotia Public Schs":
                $dd[0] = '0501';
                $dd[1] = '39';
                return ($dd);
                break;
            case "North Platt DEMO":
                $dd[0] = '5556';
                $dd[1] = '99';
                return ($dd);
                break;
            case "North Platte Public Schools":
                $dd[0] = '0001';
                $dd[1] = '56';
                return ($dd);
                break;
            case "Northwest Schools":
                $dd[0] = '0082';
                $dd[1] = '40';
                return ($dd);
                break;
            case "NSSRS":
                $dd[0] = '4848';
                $dd[1] = '99';
                return ($dd);
                break;
            case "Oakland Craig Public Schools":
                $dd[0] = '0014';
                $dd[1] = '11';
                return ($dd);
                break;
            case "Odessa Public School":
                $dd[0] = '0012';
                $dd[1] = '10';
                return ($dd);
                break;
            case "Ogallala Public Schools":
                $dd[0] = '0001';
                $dd[1] = '51';
                return ($dd);
                break;
            case "Omaha Public Schools":
                $dd[0] = '0001';
                $dd[1] = '28';
                return ($dd);
                break;
            case "Oneill Public Schools":
                $dd[0] = '0007';
                $dd[1] = '45';
                return ($dd);
                break;
            case "Ord Public Schools":
                $dd[0] = '0005';
                $dd[1] = '88';
                return ($dd);
                break;
            case "Osceola Public Schools":
                $dd[0] = '0019';
                $dd[1] = '72';
                return ($dd);
                break;
            case "Osmond Public Schools":
                $dd[0] = '0542';
                $dd[1] = '70';
                return ($dd);
                break;
            case "Overton Public Schools":
                $dd[0] = '0004';
                $dd[1] = '24';
                return ($dd);
                break;
            case "Palmer Public Schools":
                $dd[0] = '0049';
                $dd[1] = '61';
                return ($dd);
                break;
            case "Palmyra District O R 1":
                $dd[0] = '0501';
                $dd[1] = '66';
                return ($dd);
                break;
            case "Papillion La Vista Community Schools":
                $dd[0] = '0027';
                $dd[1] = '77';
                return ($dd);
                break;
            case "Pawnee City Public Schools":
                $dd[0] = '0001';
                $dd[1] = '67';
                return ($dd);
                break;
            case "Paxton Consolidated Schools":
                $dd[0] = '0006';
                $dd[1] = '51';
                return ($dd);
                break;
            case "Pender Public Schools":
                $dd[0] = '0001';
                $dd[1] = '87';
                return ($dd);
                break;
            case "Perkins County Schools":
                $dd[0] = '0020';
                $dd[1] = '68';
                return ($dd);
                break;
            case "Peru State College":
                $dd[0] = '0099';
                $dd[1] = '64';
                return ($dd);
                break;
            case "Pierce Public Schools":
                $dd[0] = '0002';
                $dd[1] = '70';
                return ($dd);
                break;
            case "Plainview Public School":
                $dd[0] = '0016';
                $dd[1] = '40';
                return ($dd);
                break;
            case "Plainview Public Schools":
                $dd[0] = '0005';
                $dd[1] = '70';
                return ($dd);
                break;
            case "Platteville Public School":
                $dd[0] = '0011';
                $dd[1] = '78';
                return ($dd);
                break;
            case "Plattsmouth Community Schools":
                $dd[0] = '0001';
                $dd[1] = '13';
                return ($dd);
                break;
            case "Pleasanton Public Schools":
                $dd[0] = '0105';
                $dd[1] = '10';
                return ($dd);
                break;
            case "Pleasant View Public School":
                $dd[0] = '0089';
                $dd[1] = '45';
                return ($dd);
                break;
            case "Ponca Public Schools":
                $dd[0] = '0001';
                $dd[1] = '26';
                return ($dd);
                break;
            case "Potter-Dix Public Schools":
                $dd[0] = '0009';
                $dd[1] = '17';
                return ($dd);
                break;
            case "Ralston Public Schools":
                $dd[0] = '0054';
                $dd[1] = '28';
                return ($dd);
                break;
            case "Randolph Public Schools":
                $dd[0] = '0045';
                $dd[1] = '14';
                return ($dd);
                break;
            case "Ravenna Public Schools":
                $dd[0] = '0069';
                $dd[1] = '10';
                return ($dd);
                break;
            case "Raymond Central Schools":
                $dd[0] = '0161';
                $dd[1] = '55';
                return ($dd);
                break;
            case "Red Cloud Community Schools":
                $dd[0] = '0002';
                $dd[1] = '91';
                return ($dd);
                break;
            case "Rising City Public Schools":
                $dd[0] = '0032';
                $dd[1] = '12';
                return ($dd);
                break;
            case "Riverside Public Schools":
                $dd[0] = '0075';
                $dd[1] = '06';
                return ($dd);
                break;
            case "Rock County Public Schools":
                $dd[0] = '0100';
                $dd[1] = '75';
                return ($dd);
                break;
            case "Rokeby Public School":
                $dd[0] = '0152';
                $dd[1] = '55';
                return ($dd);
                break;
            case "Sandhills Public Schools":
                $dd[0] = '0071';
                $dd[1] = '05';
                return ($dd);
                break;
            case "Santee Community Schools":
                $dd[0] = '0505';
                $dd[1] = '54';
                return ($dd);
                break;
            case "Sargent Public Schools":
                $dd[0] = '0084';
                $dd[1] = '21';
                return ($dd);
                break;
            case "Schuyler Community Schools":
                $dd[0] = '0123';
                $dd[1] = '19';
                return ($dd);
                break;
            case "Schuyler Grade Schools":
                $dd[0] = '0002';
                $dd[1] = '19';
                return ($dd);
                break;
            case "Scottsbluff Public Schools":
                $dd[0] = '0032';
                $dd[1] = '79';
                return ($dd);
                break;
            case "Scribner-Snyder Community Schs":
                $dd[0] = '0062';
                $dd[1] = '27';
                return ($dd);
                break;
            case "Se Nebraska Consolidated Schs":
                $dd[0] = '0501';
                $dd[1] = '74';
                return ($dd);
                break;
            case "SESIS Public Schools":
                $dd[0] = '8888';
                $dd[1] = '99';
                return ($dd);
                break;
            case "SESISville Public Schools":
                $dd[0] = '8889';
                $dd[1] = '99';
                return ($dd);
                break;
            case "Seward Public Schools":
                $dd[0] = '0009';
                $dd[1] = '80';
                return ($dd);
                break;
            case "Shady Nook Public School":
                $dd[0] = '0002';
                $dd[1] = '63';
                return ($dd);
                break;
            case "Shelby - Rising City Public Schools":
                $dd[0] = '0032';
                $dd[1] = '72';
                return ($dd);
                break;
            case "Shelton Public Schools":
                $dd[0] = '0019';
                $dd[1] = '10';
                return ($dd);
                break;
            case "Shickley Public Schools":
                $dd[0] = '0054';
                $dd[1] = '30';
                return ($dd);
                break;
            case "Sidney Public Schools":
                $dd[0] = '0001';
                $dd[1] = '17';
                return ($dd);
                break;
            case "Silver Lake Public Schools":
                $dd[0] = '0123';
                $dd[1] = '01';
                return ($dd);
                break;
            case "Sioux County Public Schools":
                $dd[0] = '0500';
                $dd[1] = '83';
                return ($dd);
                break;
            case "So Central Ne Unified System 5":
                $dd[0] = '2005';
                $dd[1] = '65';
                return ($dd);
                break;
            case "Southern Public Schools":
                $dd[0] = '0001';
                $dd[1] = '34';
                return ($dd);
                break;
            case "Southern Valley Schools":
                $dd[0] = '0540';
                $dd[1] = '33';
                return ($dd);
                break;
            case "South Platte Public Schools":
                $dd[0] = '0095';
                $dd[1] = '25';
                return ($dd);
                break;
            case "South Sioux City Community Schools":
                $dd[0] = '0011';
                $dd[1] = '22';
                return ($dd);
                break;
            case "Southwest Public Schools":
                $dd[0] = '0179';
                $dd[1] = '73';
                return ($dd);
                break;
            case "Spalding Public Schools":
                $dd[0] = '0055';
                $dd[1] = '39';
                return ($dd);
                break;
            case "Springfield Platteview Community Schools":
                $dd[0] = '0046';
                $dd[1] = '77';
                return ($dd);
                break;
            case "Stanton Community Schools":
                $dd[0] = '0003';
                $dd[1] = '84';
                return ($dd);
                break;
            case "Stapleton Public Schools":
                $dd[0] = '0501';
                $dd[1] = '57';
                return ($dd);
                break;
            case "Starview Public School":
                $dd[0] = '0049';
                $dd[1] = '45';
                return ($dd);
                break;
            case "St Edward Public Schools":
                $dd[0] = '0017';
                $dd[1] = '06';
                return ($dd);
                break;
            case "Sterling Public Schools":
                $dd[0] = '0033';
                $dd[1] = '49';
                return ($dd);
                break;
            case "St Paul Public Schools":
                $dd[0] = '0001';
                $dd[1] = '47';
                return ($dd);
                break;
            case "Stuart Public Schools":
                $dd[0] = '0044';
                $dd[1] = '45';
                return ($dd);
                break;
            case "Sumner-Eddyville-Miller Schs":
                $dd[0] = '0101';
                $dd[1] = '24';
                return ($dd);
                break;
            case "Superior Public Schools":
                $dd[0] = '0011';
                $dd[1] = '65';
                return ($dd);
                break;
            case "Sutherland Public Schools":
                $dd[0] = '0055';
                $dd[1] = '56';
                return ($dd);
                break;
            case "Sutton Public Schools":
                $dd[0] = '0002';
                $dd[1] = '18';
                return ($dd);
                break;
            case "Syracuse-Dunbar-Avoca Schools":
                $dd[0] = '0027';
                $dd[1] = '66';
                return ($dd);
                break;
            case "Table Center Public School":
                $dd[0] = '0041';
                $dd[1] = '23';
                return ($dd);
                break;
            case "Tecumseh Public Schools":
                $dd[0] = '0032';
                $dd[1] = '49';
                return ($dd);
                break;
            case "Tekamah-Herman Community Schs":
                $dd[0] = '0001';
                $dd[1] = '11';
                return ($dd);
                break;
            case "Thayer Central Comm Schools":
                $dd[0] = '0070';
                $dd[1] = '85';
                return ($dd);
                break;
            case "Thedford Rural High School":
                $dd[0] = '0001';
                $dd[1] = '86';
                return ($dd);
                break;
            case "Touhy Public School":
                $dd[0] = '0111';
                $dd[1] = '78';
                return ($dd);
                break;
            case "Tri County Public Schools":
                $dd[0] = '0300';
                $dd[1] = '48';
                return ($dd);
                break;
            case "Twin River Public Schools":
                $dd[0] = '0030';
                $dd[1] = '63';
                return ($dd);
                break;
            case "Umo N Ho N Nation Public Schs":
                $dd[0] = '0016';
                $dd[1] = '87';
                return ($dd);
                break;
            case "University of NE at Kearney":
                $dd[0] = '9000';
                $dd[1] = '10';
                return ($dd);
                break;
            case "University of Nebraska-Lincoln":
                $dd[0] = '9900';
                $dd[1] = '55';
                return ($dd);
                break;
            case "University of Nebraska-Omaha":
                $dd[0] = '9900';
                $dd[1] = '28';
                return ($dd);
                break;
            case "University of South Dakota":
                $dd[0] = '0001';
                $dd[1] = '98';
                return ($dd);
                break;
            case "Uta Halee Academy":
                $dd[0] = '4008';
                $dd[1] = '28';
                return ($dd);
                break;
            case "Valentine Community Schools":
                $dd[0] = '0006';
                $dd[1] = '16';
                return ($dd);
                break;
            case "Valley Star Public School":
                $dd[0] = '0028';
                $dd[1] = '23';
                return ($dd);
                break;
            case "Wahoo Public Schools":
                $dd[0] = '0039';
                $dd[1] = '78';
                return ($dd);
                break;
            case "Wakefield Public Schools":
                $dd[0] = '0560';
                $dd[1] = '90';
                return ($dd);
                break;
            case "Wallace Public Sch Dist 65 R":
                $dd[0] = '0565';
                $dd[1] = '56';
                return ($dd);
                break;
            case "Walthill Public Schools":
                $dd[0] = '0013';
                $dd[1] = '87';
                return ($dd);
                break;
            case "Wauneta-Palisade Public Schs":
                $dd[0] = '0536';
                $dd[1] = '15';
                return ($dd);
                break;
            case "Wausa Public Schools":
                $dd[0] = '0576';
                $dd[1] = '54';
                return ($dd);
                break;
            case "Wayne Community Schools":
                $dd[0] = '0017';
                $dd[1] = '90';
                return ($dd);
                break;
            case "Wayne State College":
                $dd[0] = '9900';
                $dd[1] = '90';
                return ($dd);
                break;
            case "Weeping Water Public Schools":
                $dd[0] = '0022';
                $dd[1] = '13';
                return ($dd);
                break;
            case "West Boyd":
                $dd[0] = '0050';
                $dd[1] = '08';
                return ($dd);
                break;
            case "West Holt Public Schools":
                $dd[0] = '0239';
                $dd[1] = '45';
                return ($dd);
                break;
            case "West Point Public Schools":
                $dd[0] = '0001';
                $dd[1] = '20';
                return ($dd);
                break;
            case "Westside Community Schools":
                $dd[0] = '0066';
                $dd[1] = '28';
                return ($dd);
                break;
            case "Wheeler Central Schools":
                $dd[0] = '0045';
                $dd[1] = '92';
                return ($dd);
                break;
            case "Wilber-Clatonia Public Schools":
                $dd[0] = '0082';
                $dd[1] = '76';
                return ($dd);
                break;
            case "Wilcox-Hildreth Public Schools":
                $dd[0] = '0001';
                $dd[1] = '50';
                return ($dd);
                break;
            case "Winnebago Public Schools":
                $dd[0] = '0017';
                $dd[1] = '87';
                return ($dd);
                break;
            case "Winside Public Schools":
                $dd[0] = '0595';
                $dd[1] = '90';
                return ($dd);
                break;
            case "Wisner-Pilger Public Schools":
                $dd[0] = '0030';
                $dd[1] = '20';
                return ($dd);
                break;
            case "Wood River Rural Schools":
                $dd[0] = '0083';
                $dd[1] = '40';
                return ($dd);
                break;
            case "Wynot Public Schools":
                $dd[0] = '0101';
                $dd[1] = '14';
                return ($dd);
                break;
            case "x":
                $dd[0] = '6789';
                $dd[1] = '99';
                return ($dd);
                break;
            case "York College":
                $dd[0] = '1111';
                $dd[1] = '99';
                return ($dd);
                break;
            case "York Public Schools":
                $dd[0] = '0012';
                $dd[1] = '93';
                return ($dd);
                break;
            case "Yutan Public Schools":
                $dd[0] = '0009';
                $dd[1] = '78';
                return ($dd);
                break;
        }
    }
}

