<?php

/**
 * Form004
 *
 * @author jlavere
 * @version
 */

class Model_Table_Form004 extends Model_Table_AbstractIepForm
{
    protected $_name = 'iep_form_004';
    protected $_primary = 'id_form_004';
    protected $spEdTime;
    protected $regEdTime;

    protected $_dependentTables = array(
				'Model_Table_Form004TeamMember',
				'Model_Table_Form004TeamOther',
				'Model_Table_Form004TeamDistrict',
				'Model_Table_Form004Goal',
    			'Model_Table_Form004RelatedService',
    			'Model_Table_Form004SupplementalService',
    			'Model_Table_Form004ProgramModifications',
    			'Model_Table_Form004AssistiveTechnology',
    			'Model_Table_Form004SchoolSupport',
    			'Model_Table_Form004SupplementalForm',
    			'Model_Table_Form004SecondaryGoal',
                'Model_Table_Form004AccomodationsChecklist',
                'Model_Table_Form010',
    );


    // Mike added this 3-12-2018 so that pwn would work correctly
    public function pwnUpdate($data) {

        $dataSelect=array(
            'pwn_describe_action'=>$data['pwn_describe_action'],
            'pwn_describe_reason'=>$data['pwn_describe_reason'],
            'pwn_options_other'=>$data['pwn_options_other'],
            'pwn_other_factors'=>$data['pwn_other_factors'],
            'pwn_justify_action'=>$data['pwn_justify_action']
        );

        $idForm=$data['id_form_004'];
        $where =  "id_form_004 = '$idForm' ";
        $this->update($dataSelect,$where);

    }


    // Mike added 10-17-2017 in order to get csv to work
    public function getMostRecentIepState($id_student){

        $sql="select * from iep_form_004 where id_student='$id_student' and status='Final' order by date_conference DESC";
        $forms=$this->db->fetchAll($sql);
        if (!empty($forms)) {

            return $forms;
        }
        else {
            return null;
        }

    }   // end of the function


    function dupe($document) {


        $sessUser = new Zend_Session_Namespace('user');
    	$stmt = $this->db->query("SELECT dupe_iep_zend_to_zend('$document', '$sessUser->sessIdUser')");
//     	Zend_Debug::dump($stmt);
//     	die();
    	$result = $stmt->fetchAll();
        if(false!=$result) {
            return $result[0]['dupe_iep_zend_to_zend'];
        } else {
        	return false;
        }
    }
    function dupeFull($document) {

        $sessUser = new Zend_Session_Namespace('user');
    	$stmt = $this->db->query("SELECT dupe_iep_full('$document', '$sessUser->sessIdUser')");
    	$result = $stmt->fetchAll();
        if(false!=$result) {
            return $result[0]['dupe_iep_full'];
        } else {
        	return false;
        }
    }

    function removeDupeMenu($id_student){
        $stmt = $this->db->query("SELECT status from iep_form_004 where id_student= ?",$id_student);
        $result=$stmt->fetchAll();
        $t_f=true;

        foreach($result as $reslt){
            //      $this->writevar1($reslt['status'],'this is the result');
            if ($reslt['status']!='Final'){
                $t_f=false;
            }
        }
        // $this->writevar1($t_f,'this is the boolean in the model returned');
        return $t_f;


    }


    function buildFteMinutes($iep)
    {
        $this->spEdTime = 0;
        $this->regEdTime = 0;

        if('h' == $iep->primary_service_tpd_unit) {
            $minMultiplier = 60;
        } elseif('m' == $iep->primary_service_tpd_unit) {
            $minMultiplier = 1;
        } elseif('mw' == $iep->primary_service_tpd_unit) {
            $minMultiplier = 1/5;
        }

        if('w' == $iep->primary_service_days_unit) {
            $daysMultiplier = 1;
        } elseif('m' == $iep->primary_service_days_unit) {
            $daysMultiplier = 1/4;
        } elseif('q' == $iep->primary_service_days_unit) {
            $daysMultiplier = 1/9;
        } elseif('s' == $iep->primary_service_days_unit) {
            $daysMultiplier = 1/18;
        } elseif('y' == $iep->primary_service_days_unit) {
            $daysMultiplier = 1/36;
        } elseif('wm' == $iep->primary_service_days_unit) {
            $daysMultiplier = 1/4;
        } elseif('ws' == $iep->primary_service_days_unit) {
            $daysMultiplier = 1/18;
        } elseif('wy' == $iep->primary_service_days_unit) {
            $daysMultiplier = 1/36;
        }


        // last friday october - this gets october and then backs up to the previous friday
        $fromTime = strtotime($iep->primary_service_from);
        $toTime =  strtotime($iep->primary_service_to);
        $matchTime = strtotime('last friday october ' . date('Y', $fromTime));
        if($matchTime < $fromTime) {
            $matchTime = strtotime('last friday october ' . date('Y', $toTime));
        }

        if($matchTime >= $fromTime && $matchTime <= $toTime ) {
            if('Special Education' == $iep->fte_special_education_time) {
                $this->spEdTime += ($iep->fte_qualifying_minutes * $minMultiplier * $daysMultiplier);
            } elseif('Special Education with regular Ed Peers' == $iep->fte_special_education_time) {
                $this->regEdTime += ($iep->fte_qualifying_minutes * $minMultiplier * $daysMultiplier);
            }
        }

        $additionalServices = $iep->findDependentRowset('Model_Table_Form004RelatedService', 'Model_Table_Form004');
        foreach ($additionalServices as $additionalService) {
            if('Active' != $additionalService->status) {
                continue;
            }
            if('h' == $iep->related_service_tpd) {
                $minMultiplier = 60;
            } elseif('m' == $iep->related_service_tpd) {
                $minMultiplier = 1;
            } elseif('mw' == $iep->related_service_tpd) {
                $minMultiplier = 1/5;
            }

            if('w' == $iep->related_service_tpd_unit) {
                $daysMultiplier = 1;
            } elseif('m' == $iep->related_service_tpd_unit) {
                $daysMultiplier = 1/4;
            } elseif('q' == $iep->related_service_tpd_unit) {
                $daysMultiplier = 1/9;
            } elseif('s' == $iep->related_service_tpd_unit) {
                $daysMultiplier = 1/18;
            } elseif('y' == $iep->related_service_tpd_unit) {
                $daysMultiplier = 1/36;
            } elseif('wm' == $iep->related_service_tpd_unit) {
                $daysMultiplier = 1/4;
            } elseif('ws' == $iep->related_service_tpd_unit) {
                $daysMultiplier = 1/18;
            } elseif('wy' == $iep->related_service_tpd_unit) {
                $daysMultiplier = 1/36;
            }


            $fromTime = strtotime($additionalService->related_service_from_date);
            $toTime =  strtotime($additionalService->related_service_to_date);
            $matchTime = strtotime('last friday october ' . date('Y', $fromTime));
            if($matchTime < $fromTime) {
                $matchTime = strtotime('last friday october ' . date('Y', $toTime));
            }

            if($matchTime >= $fromTime && $matchTime <= $toTime ) {
                if('Special Education' == $additionalService->fte_special_education_time) {
                    $this->spEdTime += ($additionalService->fte_qualifying_minutes * $minMultiplier * $daysMultiplier);
                } elseif('Special Education with regular Ed Peers' == $additionalService->fte_special_education_time) {
                    $this->regEdTime += ($additionalService->fte_qualifying_minutes * $minMultiplier * $daysMultiplier);
                }
            }
        }

        $iep->fte_total_qualifying_min_se = $this->spEdTime;
        $iep->fte_total_qualifying_min_re = $this->regEdTime;
        return $iep;
    }
}

