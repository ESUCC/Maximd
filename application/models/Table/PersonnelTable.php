<?php

/**
 * PersonnelTable
 *
 * @author jesse
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_PersonnelTable extends Model_Table_AbstractIepForm
{
    /**
     * The default table name
     */
    protected $_name = 'iep_personnel';

// 	protected $_referenceMap = array(
// 		'ContactTypes' => array(
// 			'columns' => array('contacttype_id'),
// 			'refTableClass' => 'ContactTypesTable',
// 			'refColumns'	=> array('id')
// 		)
// 	);

    public function getNameByUsername($userName)
    {
        $select = $this->db->select();
        $select->from('iep_personnel')->where('user_name = ?', $userName);
        $result = $select->query()->fetchAll();
        return '' . @$result[0]['name_first'] . ' ' . @$result[0]['name_last'];
    }

    public function getById($idPersonnel)
    {
        $select = $this->db->select();
        $select->from('iep_personnel')->where('id_personnel = ?', $idPersonnel);
        $result = $select->query()->fetchAll();
        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    static public function getUserName($idPersonnel)
    {
        if ('' == $idPersonnel || null == $idPersonnel) return '';

        $table = new Model_Table_PersonnelTable();
        $select = $table->db->select();
        $select->from('iep_personnel')->where('id_personnel = ?', $idPersonnel);
        $result = $select->query()->fetchAll();
        if ($result) {
            return $result[0]['name_first'] . (strlen($result[0]['name_middle']) > 0 ? $result[0]['name_middle'] . ' ' : ' ') . $result[0]['name_last'];
        } else {
            return false;
        }
    }

    function generateUserName($nameFirst, $nameLast)
    {
        /**
         * generate user_name
         */
        $tmpUserName = strtolower(str_replace(" ", "", substr($nameFirst, 0, 1) . $nameLast));
        $maxAttempts = 1000;
        for ($i = 1; $i < $maxAttempts; $i++) {
            $existingUser = $this->fetchRow($this->select()->where('user_name = ?', $tmpUserName));
            if (null == $existingUser) {
                return $tmpUserName;
            }
            $tmpUserName = strtolower(str_replace(" ", "", substr($nameFirst, 0, 1) . $nameLast . $i));
        }
        return null;
    }


    public function getCaseManagers($id_county, $id_district, $id_school)
    {
        /**
         * select distinct id_personnel
        from iep_privileges pv where pv.status='Active' AND (( pv.class<=" . UC_ADM . " AND pv.id_county='$id_county' AND
        pv.id_district='$id_district' ) OR (pv.class<=" . UC_CM . " AND pv.id_county='$id_county' AND pv.id_district='$id_district'
        AND pv.id_school='$id_school'))) and (pr.id_personnel=pr.id_personnel_master)
         */
        $select = $this->db->select()
            ->distinct()
            ->from('iep_privileges', array('id_personnel'))
            ->where("( status = 'Active' and class <= " . UC_ADM . " and
                id_county = '".$id_county."' and id_district = '".$id_district."' )")
            ->orWhere("( status = 'Active' and class <= " . UC_CM . " and
                id_county = '".$id_county."' and id_district = '".$id_district."' and id_school = '".$id_school."' )");
//        echo $select; die;
        $privRecords  = $select->query()->fetchAll();

        $personnelDetails = array();
        foreach ($privRecords as $privRecord) {
            $personnel = $this->find($privRecord['id_personnel'])->current();
            if(''!=$personnel['name_middle']) {
                $name = $personnel['name_first'] .' ' . $personnel['name_middle'] . ' ' . $personnel['name_last'];
            } else {
                $name = $personnel['name_first'] .' ' . $personnel['name_last'];
            }
            if(''!=$personnel['id_personnel']) {
                $personnelDetails[$personnel['id_personnel']] = $name;
            }
        }
        asort($personnelDetails);
        return $personnelDetails;
    }
    public function getEiCaseManagers($id_county, $id_district, $id_school)
    {
        /**
         * select distinct id_personnel
        from iep_privileges pv where pv.status='Active' AND (( pv.class<=" . UC_ADM . " AND pv.id_county='$id_county' AND
        pv.id_district='$id_district' ) OR (pv.class<=" . UC_CM . " AND pv.id_county='$id_county' AND pv.id_district='$id_district'
        AND pv.id_school='$id_school'))) and (pr.id_personnel=pr.id_personnel_master)
         */
        $select = $this->db->select()
            ->distinct()
            ->from('iep_privileges', array('id_personnel'))
            ->where("( status = 'Active' and class = " . UC_ASM . "  and id_county = '".$id_county."' and id_district = '".$id_district."' and id_school = '".$id_school."' )")
            ->orWhere("( status = 'Active' and class = " . UC_SM . " and id_county = '".$id_county."' and id_district = '".$id_district."' and id_school = '".$id_school."' )")
//            ->orWhere("( status = 'Active' and class = " . UC_SC . "  and id_county = '".$id_county."' and id_district = '".$id_district."' )")
            ->orWhere("( status = 'Active' and class = " . UC_SC . " and id_county = '".$id_county."' and id_district = '".$id_district."' and id_school = '".$id_school."' )");
        $privRecords  = $select->query()->fetchAll();

        $personnelDetails = array();
        foreach ($privRecords as $privRecord) {
            $personnel = $this->find($privRecord['id_personnel'])->current();
            if(''!=$personnel['name_middle']) {
                $name = $personnel['name_first'] .' ' . $personnel['name_middle'] . ' ' . $personnel['name_last'];
            } else {
                $name = $personnel['name_first'] .' ' . $personnel['name_last'];
            }
            if(''!=$personnel['id_personnel']) {
                $personnelDetails[$personnel['id_personnel']] = $name;
            }
        }
        asort($personnelDetails);
        return $personnelDetails;
    }

    public function getServiceCoordinatorsOptions($id_county, $id_district, $id_school)
    {
        $select = $this->db->select()
            ->distinct()
            ->from('iep_privileges', array('id_personnel'))
            ->where("( status = 'Active' and class = " . UC_SC . " and
                id_county = '".$id_county."' and id_district = '".$id_district."' and id_school = '".$id_school."' )");
        $privRecords  = $select->query()->fetchAll();

        $personnelDetails = array();
        foreach ($privRecords as $privRecord) {
            $personnel = $this->find($privRecord['id_personnel'])->current();
            if(''!=$personnel['name_middle']) {
                $name = $personnel['name_first'] .' ' . $personnel['name_middle'] . ' ' . $personnel['name_last'];
            } else {
                $name = $personnel['name_first'] .' ' . $personnel['name_last'];
            }
            if(''!=$personnel['id_personnel']) {
                $personnelDetails[$personnel['id_personnel']] = $name;
            }
        }
        asort($personnelDetails);
        return $personnelDetails;
    }

    public function getEICMsOptions($id_county, $id_district, $id_school)
    {
        $select = $this->db->select()
            ->distinct()
            ->from('iep_privileges', array('id_personnel'))
            ->where("( status = 'Active' and (
            (class = " . UC_ADM . " and id_county = '".$id_county."' and id_district = '".$id_district."' ) OR
            (class = " . UC_CM . " and id_county = '".$id_county."' and id_district = '".$id_district."' and id_school = '".$id_school."' ) OR
            (class = " . UC_SC . " and id_county = '".$id_county."' and id_district = '".$id_district."' and id_school = '".$id_school."' ) OR
            )");
        $privRecords  = $select->query()->fetchAll();

        $personnelDetails = array();
        foreach ($privRecords as $privRecord) {
            $personnel = $this->find($privRecord['id_personnel'])->current();
            if(''!=$personnel['name_middle']) {
                $name = $personnel['name_first'] .' ' . $personnel['name_middle'] . ' ' . $personnel['name_last'];
            } else {
                $name = $personnel['name_first'] .' ' . $personnel['name_last'];
            }
            if(''!=$personnel['id_personnel']) {
                $personnelDetails[$personnel['id_personnel']] = $name;
            }
        }
        asort($personnelDetails);
        return $personnelDetails;
    }

    public function getStudentTeamOptions($id_county, $id_district, $id_school)
    {
        $select = $this->db->select()
            ->distinct()
            ->from('iep_privileges', array('id_personnel'))
            ->where("( status = 'Active' and id_county = '".$id_county."' and id_district = '".$id_district."' and id_school = '".$id_school."' ) OR " .
            " ( status = 'Active' and class <= " . UC_ADM . " and id_county = '".$id_county."' and id_district = '".$id_district."' )");
        $privRecords  = $select->query()->fetchAll();

        $personnelDetails = array();
        foreach ($privRecords as $privRecord) {
            $personnel = $this->find($privRecord['id_personnel'])->current();
            if(''!=$personnel['name_middle']) {
                $name = $personnel['name_first'] .' ' . $personnel['name_middle'] . ' ' . $personnel['name_last'];
            } else {
                $name = $personnel['name_first'] .' ' . $personnel['name_last'];
            }
            if(''!=$personnel['id_personnel']) {
                $personnelDetails[$personnel['id_personnel']] = $name;
            }
        }
        asort($personnelDetails);
        return $personnelDetails;
    }

    public function checkAccess($checkAccessToPersonnelId, $sessIdUser) {
        /**
         * check if user with id sessIdUser has access to
         * personnel with id checkAccessToPersonnelId
         */


    }
    public function validateAccess($personnelBeingCheckedId, $sessIdUser, $type = 'editPersonnel') {
        /**
         * check if user with id sessIdUser has access to
         * personnel with id checkAccessToPersonnelId
         */

        /**
         * limit types
         */
        
       
        switch($type) {
            case 'editPersonnel':
            case 'removePrivilege':
                break;
            default:
                throw new Exception('Type not allowed');
                break;
        }


        $personnelObj = new Model_Table_PersonnelTable();

        if($currentUser = $this->findUserWithPrivs($sessIdUser)) {
        } else {
            return false;
        }
        $currentUserPrivHelper = new My_Classes_privCheck($currentUser->privs);

        /**
         * ASM or Higher for any access at all
         */
        if($currentUserPrivHelper->getMinPriv() == UC_SA) {
            return true;
        } elseif($currentUserPrivHelper->getMinPriv() > UC_ASM) {
            return false;
        }


        /**
         * personnel being checked
         */
        if($personnelRecords = $personnelObj->find($personnelBeingCheckedId)) {
            $personnel = $personnelRecords->current();
            $personnelBeingCheckedPrivs = $this->findUserWithPrivs($personnelBeingCheckedId, null);
            if(0==count($personnelBeingCheckedPrivs)) {
                /**
                 * personnel being checked has no privs
                 */
                if($currentUserPrivHelper->getMinPriv() !== UC_SA) {
                    // admin access
                    return true;
                } else {
                    // otherwise none
                    return false;
                }
            }
        } else {
            return false;
        }

        /**
         * compare current user privs to personnel being accessed privs
         */
//        Zend_Debug::dump($personnelBeingCheckedPrivs->privs);die;
        foreach($currentUser->privs as $key => $currentUserPriv) {
            if($currentUserPriv['class'] > UC_ASM) {
                // not ASM or higher priv - no access granted for this priv
                continue;

            } elseif($currentUserPriv['class'] < UC_ASM) {
                // school access
//                Zend_Debug::dump($currentUserPriv, 'districtaccess');
                foreach($personnelBeingCheckedPrivs->privs as $personnelBeingCheckedPriv) {
                    if($currentUserPriv['id_county'] == $personnelBeingCheckedPriv['id_county']
                        && $currentUserPriv['id_district'] == $personnelBeingCheckedPriv['id_district'] ) {
                        return true;
                    }

                }

            } elseif($currentUserPriv['class'] < UC_ADM) {
                // district access
                // check for access at any school in the district
                foreach($personnelBeingCheckedPrivs->privs as $personnelBeingCheckedPriv) {
                    if($currentUserPriv['id_county'] == $personnelBeingCheckedPriv['id_county']
                        && $currentUserPriv['id_district'] == $personnelBeingCheckedPriv['id_district']
                        && $currentUserPriv['id_school'] == $personnelBeingCheckedPriv['id_school'] ) {
                        return true;
                    }

                }

            }
        }

        if ('editPersonnel' == $type && $sessIdUser == $personnelBeingCheckedId) {
            // editing your own record is allowed
            $valid = true;
        }

        // note that you cannot remove your own privileges
        return $valid;
    }
    public function validatePrivAccess($id_county, $id_district, $id_school, $class, $sessIdUser, $personnelBeingCheckedId)
    {
        $personnelObj = new Model_Table_PersonnelTable();
        if($currentUser = $this->findUserWithPrivs($sessIdUser)) {
        } else {
            return false;
        }
        $currentUserPrivHelper = new My_Classes_privCheck($currentUser->privs);
        if($currentUserPrivHelper->getMinPriv() == UC_SA) {
            return true;
        }
        if ($sessIdUser == $personnelBeingCheckedId) {
            // editing your own priv records is NOT allowed
            return false;
        }

        /**
         * compare current user privs to priv being accessed
         */
        foreach($currentUser->privs as $currentUserPriv) {
            /**
             * dm/adm access
             */
            if($currentUserPriv['id_county'] == $id_county
                && $currentUserPriv['id_district'] == $id_district
                && ($currentUserPriv['class'] == UC_DM || $currentUserPriv['class'] == UC_ADM)
            ) {
                return true;
            }

            if($currentUserPriv['id_county'] == $id_county
                && $currentUserPriv['id_district'] == $id_district
                && $currentUserPriv['id_school'] == $id_school
                && $currentUserPriv['class'] <= UC_ASM
                && $currentUserPriv['class'] <= UC_ASM
            ) {
                return true;
            }
        }

        // note that you cannot remove your own privileges
        return false;
    }
    public static function findUserWithPrivs($idUser, $status = 'Active')
    {
        $personnelService = new App_Service_PersonnelService();
        $user = $personnelService->GetPersonnel($idUser);
        $privs = $personnelService->getPrivilegesByUser($idUser, $status);

        if ($user)
        {
            $account = new stdClass();
            foreach($user as $key => $value) {
                $account->$key = $value;
            }
            $account->privs = $privs;
            return $account;
        }
        return false;
    }

}
