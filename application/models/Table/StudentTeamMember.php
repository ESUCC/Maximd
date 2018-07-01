<?php

/**
 * StudentTeamMember
 *
 * @author jlavere
 * @version
 */

class Model_Table_StudentTeamMember extends Model_Table_AbstractIepForm
{
    /**
     * The default table name
     */
    protected $_name = 'iep_student_team';
    protected $_primary = 'id_student_team';
    protected $_sequence = 'iep_student_team_id_seq';

    protected $_referenceMap    = array(
        'Model_Table_IepStudent' => array(
            'columns'           => array('id_student'),
            'refTableClass'     => 'Model_Table_IepStudent',
            'refColumns'        => array('id_student')
        ),
    );

    public function studentsWithPersonnelAsTeamMember($id_personnel) {
        $quotedId = $this->_db->quote($id_personnel);
        $db = Zend_Registry::get('db');

        /**
         * get the student team and student records
         */
        $select = $db->select()
            ->from( array('team'=>'iep_student_team'),
                array(
                    'id_student',
                    'id_personnel'
                ))
            ->join(array('s' => 'iep_student'),
                'team.id_student= s.id_student',
                array(
                    'name_student_full'=> new Zend_Db_Expr('CASE WHEN s.name_middle IS NOT NULL THEN s.name_first || \' \' || s.name_middle || \' \' || s.name_last ELSE s.name_first || \' \' || s.name_last END'),
                    'id_student',
                    'dob',
                    'countyName' => 'get_name_county(s.id_county)',
                    'districtName' => 'get_name_district(s.id_county, s.id_district)',
                )
            )
            ->where("team.id_personnel = ?", $id_personnel )
            ->where("team.status = ?", 'Active')
            ->order("s.name_last" )
            ->order("s.name_first" )
            ->order("s.name_middle" );

        $results = $db->fetchAll($select);
//        echo $select;die;
        $iepObj = new Model_Table_Form004();
        if(count($results)) {
            foreach ($results as $key => $teamMemberStudentInfo) {
                $results[$key]['timeData'] = array();
                $mostRecentFinalForm = $iepObj->mostRecentFinalForm($teamMemberStudentInfo['id_student']);

                if($mostRecentFinalForm['primary_disability_drop']=='Physical Therapy') {
                    $results[$key]['timeData'][] = array(
                        'service_tpd' => $mostRecentFinalForm['primary_service_tpd'],
                        'service_tpd_unit' => $mostRecentFinalForm['primary_service_tpd_unit'],
                        'service_days_value' => $mostRecentFinalForm['primary_service_days_value'],
                        'service_days_unit' => $mostRecentFinalForm['primary_service_days_unit'],
                        'service_mpy' => $mostRecentFinalForm['primary_service_mpy'],
                    );
                }
                if($mostRecentFinalForm) {
                    $quotedForm004Id = $this->_db->quote($mostRecentFinalForm->id_form_004);
                    $select         = $iepObj->select()->where("status = 'Active' and related_service_drop = 'Physical Therapy'");
                    $postSecGoals = $mostRecentFinalForm->findDependentRowset('Model_Table_Form004RelatedService', 'Model_Table_Form004', $select);
                    foreach ($postSecGoals as $k => $goal) {
                        $results[$key]['timeData'][] = array(
                            'service_tpd' => $goal['related_service_tpd'],
                            'service_tpd_unit' => $goal['related_service_tpd_unit'],
                            'service_days_value' => $goal['related_service_days_value'],
                            'service_days_unit' => $goal['related_service_days_unit'],
                            'service_mpy' => $goal['related_service_mpy'],
                        );
                    }

                }

            }
        }
        return $results;

    }

}

