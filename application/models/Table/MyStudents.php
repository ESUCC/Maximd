<?php
class Model_Table_MyStudents extends Zend_Db_Table_Abstract {
 
    protected $_name = 'my_students';
    protected $_primary = array('id_personnel', 'id_student');

    public function init() {

        $this->db = $db = Zend_Registry::get('db');
        Zend_Db_Table_Abstract::setDefaultAdapter($db);

        //$table = new neb_esu();
        $this->className = get_class($this);

    }

    public function getStudentsAboutToDevDelay($idPersonnel)
    {
        $select = $this->db->select();
        $select->from('my_students')
            ->where('id_personnel = ?', $idPersonnel)
            ->where('dob >= ?', date('m/d/Y', strtotime('now - 9 years')))
            ->where('dob <= ?', date('m/d/Y', strtotime('now - 9 years + 2 weeks')))
            ->where('primary_disability = ?', 'DD')
            ->order('dob');
        //echo $select;
        return $select->query()->fetchAll();
    }

}