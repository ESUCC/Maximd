
<?php
/**
 * Model_Table_StudentFormAdd
 *
 * @author jlavere
 * @version
 * 
 * maxim modified this nov 1 so I put it in this backup
 */
require_once 'Zend/Db/Table/Abstract.php';
class Model_Table_StudentFormAdd extends Model_Table_AbstractIepForm
{
    public function studentFormAdd($options)
    {
        $db = Zend_Registry::get('db');
    
        $select = $db->select()
                   ->from( array('c' => 'iep_county'), 'name_county')
                   ->where("c.id_county = '".$options['id_county']."'");
        $county_row = $db->fetchRow($select);
        $select = $db->select()
                   ->from( array('d' => 'iep_district'), 'name_district')
                   ->where("d.id_district = '".$options['id_district']."'");
        $district_row = $db->fetchRow($select);
        $select = $db->select()
                   ->from( array('s' => 'iep_school'), array('id_school', 'name_school'))
                   ->where('s.id_district = ?', $options['id_district'])
                   ->where('s.id_county = ?', $options['id_county'])
                   ->where('s.status = ?', 'Active')
                   ->order('s.name_school asc');
        $school_list = $db->fetchAll($select);
    return array(
            $county_row,
            $district_row,
            $school_list
        );
    }
    public function studentManagersList($id_school)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
                   ->distinct()
                   ->from( array('p' => 'iep_personnel'), array('p.id_personnel', 'p.name_first', 'p.name_last') )
                   ->join( array('r' => 'iep_privileges'), 'p.id_personnel = r.id_personnel', array() )  
                   ->where('p.status = \'Active\' and (p.id_school = \''.$id_school.'\' and (r.class >= 4 and r.class <= 10)) or (r.class >= 0 and r.class <= 3)')
                   ->order('p.name_last asc');
        $result = $db->fetchAll($select);
       return array($result);
    }
    public function studentSesisList($do, $id)
    {
//select id_county,name_county from iep_county order by name_county
//select name_district,id_district from iep_district where id_county='11'
//select id_school,name_school from iep_school where id_district=.0014. and id_count='11'
        $db = Zend_Registry::get('db');
        switch ($do) {
          case "district":
            $select = $db->select()
                   ->from( array('p' => 'iep_district') , array('p.name_district', 'p.id_district'))
                   ->where('p.id_county = ?', $id)
                   ->order('p.name_district asc');
          break;
          case "school":
            $select = $db->select()
                   ->from( array('p' => 'iep_school') , array('id_school', 'name_school'))
                   ->where('p.id_district = ?', $id)
                   ->where('p.id_county = ?', $id)
                   ->order('p.name_school asc');
          break;
          case "county":
          default: 
            $select = $db->select()
                   ->from( array('p' => 'iep_county') , array('p.id_county', 'p.name_county'))
                   ->order('p.name_county asc');
        }
        $result = $db->fetchAll($select);
       return array($result);
    }
}