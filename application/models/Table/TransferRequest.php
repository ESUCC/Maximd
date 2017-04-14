<?php


require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_TransferRequest extends Zend_Db_Table_Abstract
{
    /**
     * The default table name
     */
    protected $_name = 'iep_transfer_request';
    protected $_primary = 'id_transfer_request';
    protected $_sequence = 'iep_transfer__id_transfer_r_seq';

    public function confirmTransferRequest($idTransferRequest) {
        $tr = $this->getTransferRequest($idTransferRequest);
        if(empty($tr)) {
            return false;
        }
        $tr = $tr[0];
        /**
         * add error checking
         */
        foreach(explode('||', trim($tr['student_id_list'], '|')) as $studentId) {
            $this->suspendForms($tr, $studentId);
            $transferred = $this->confirmStudentTransfer($studentId, $tr['id_county_to'], $tr['id_district_to'], $tr['id_school_to'], $tr['id_transfer_request']);
            if($transferred) {
                $this->insertNssrsTransfer($tr, $studentId);
            }

        }

        /**
         * send confirmation email
         */
        $usersession = new Zend_Session_Namespace ( 'user' );
        $this->sendNotificationEmail($tr, $usersession->user);

        return true;
    }

    /**
     * @param $tr
     * @param $studentId
    update iep_form_001 set status = 'Draft' where status = 'Suspended';
    update iep_form_002 set status = 'Draft' where status = 'Suspended';
    update iep_form_003 set status = 'Draft' where status = 'Suspended';
    update iep_form_004 set status = 'Draft' where status = 'Suspended';
    update iep_form_005 set status = 'Draft' where status = 'Suspended';
    update iep_form_006 set status = 'Draft' where status = 'Suspended';
    update iep_form_007 set status = 'Draft' where status = 'Suspended';
    update iep_form_008 set status = 'Draft' where status = 'Suspended';
    update iep_form_009 set status = 'Draft' where status = 'Suspended';
    update iep_form_010 set status = 'Draft' where status = 'Suspended';
    update iep_form_011 set status = 'Draft' where status = 'Suspended';
    update iep_form_012 set status = 'Draft' where status = 'Suspended';
    update iep_form_013 set status = 'Draft' where status = 'Suspended';
    update iep_form_014 set status = 'Draft' where status = 'Suspended';
    update iep_form_015 set status = 'Draft' where status = 'Suspended';
    update iep_form_016 set status = 'Draft' where status = 'Suspended';
    update iep_form_017 set status = 'Draft' where status = 'Suspended';
    update iep_form_018 set status = 'Draft' where status = 'Suspended';
    update iep_form_019 set status = 'Draft' where status = 'Suspended';
    update iep_form_020 set status = 'Draft' where status = 'Suspended';
    update iep_form_021 set status = 'Draft' where status = 'Suspended';
    update iep_form_022 set status = 'Draft' where status = 'Suspended';
    update iep_form_023 set status = 'Draft' where status = 'Suspended';
    update iep_form_024 set status = 'Draft' where status = 'Suspended';
    update iep_form_025 set status = 'Draft' where status = 'Suspended';

     */


    public function suspendForms($tr, $studentId)
    {
        $config = Zend_Registry::get('config');
        /**
         * check if transferred out of district
         */
        if( ($tr['id_county_to'] != $tr['id_county_from']) || ($tr['id_district_to'] != $tr['id_district_from']) )
        {
            /**
             * get draft forms of each type and suspend
             */
            for($i = 1; $i <= $config->forms->count; $i++)
            {
                $formNum = substr('000'.$i, -3, 3);
                $formObjName = 'Model_Form'.$formNum; // <- notice not Model_Table_Form0XX
                $formObj = new $formObjName($formNum, new Zend_Session_Namespace ( 'user' ));
                $select = $formObj->table->select()
                    ->where("id_student = ?", $studentId)
                    ->where("status = 'Draft'");
                $draftForms = $formObj->table->fetchAll($select);

                /**
                 * update status
                 */
                foreach($draftForms as $form) {
                    $form->status = 'Suspended';
                    $formObj->suspendForm($form['id_form_' . $formNum]);
                }

//                $formNum = substr('000'.$i, -3, 3);
//                $formObjName = 'Model_Table_Form'.$formNum;
//                $formObj = new $formObjName();
//                $select = $formObj->select()
//                    ->where("id_student = ?", $studentId)
//                    ->where("status = 'Draft'");
//                $draftForms = $formObj->fetchAll($select);
//
//                /**
//                 * update status
//                 */
//                foreach($draftForms as $form) {
//                    $form->status = 'Suspended';
//                    $formObj->suspendForm($form['id_form_' . $formNum]);
//                }
            }
        }
    }
    public function insertNssrsTransfer($transfer, $studentId)
    {
        // track transfers at the nssrs level
        if( ($transfer['id_county_to'] != $transfer['id_county_from']) || ($transfer['id_district_to'] != $transfer['id_district_from']) )
        {
            // out of district
            $sesisObj = new App_Student_Sesis();
            $sesisData = $sesisObj->sesis_collection($studentId);
            $sesisData['033'] = Model_Table_StudentTable::getEntryDate($studentId);
            $sesisData['052'] = '1';
            $sesisData['001'] = $transfer['id_county_from'] . '-' . $transfer['id_district_from'];
            $sesisData['002'] = $transfer['id_school_from'];

            $nssrs_transfer = new App_Student_NssrsTransfer();
            if(!$nssrs_transfer->insert_transfer($sesisData, $sesisObj->studentData)) {
                $this->sendTransferErrorEmail($studentId);
            }
        }
    }
    public function deleteTransferRequest($idTransferRequest) {
        $tr = $this->getTransferRequest($idTransferRequest);
        if(empty($tr)) {
            return false;
        }
        $transferRequest = $this->find($idTransferRequest)->current();
        $transferRequest->transfer_type = 'Cancelled';
        $transferRequest->save();
    }
    public function getTransferRequest($idTransferRequest) {
        $db = Zend_Registry::get('db');
        $idTransferRequest = $db->quote($idTransferRequest);

        $usersession = new Zend_Session_Namespace ( 'user' );

        /**
         * limit access
         */
        $where = "";
        foreach ($usersession->user->privs as $priv) {
            $searchCounty = $priv['id_county'];
            $searchDistrict = $priv['id_district'];
            $searchSchool = $priv['id_school'];
            if(UC_SA == $priv['class']) {
                // admin show all
                $where .= (strlen($where)>0?" OR ":"")."(1=1)";
            } elseif(UC_DM == $priv['class'] || UC_ADM ==$priv['class']) {
                $where .= (strlen($where)>0?" OR ":"")."(id_county_to = '$searchCounty' AND id_district_to = '$searchDistrict')";
            } elseif(UC_SM == $priv['class'] || UC_ASM ==$priv['class']) {
                $where .= (strlen($where)>0?" OR ":"")."(id_county_to = '$searchCounty' AND id_district_to = '$searchDistrict' AND id_school_to = '$searchSchool')";
            }
        }
        $where = "($where) and transfer_type = 'initiate' and id_transfer_request = $idTransferRequest";

        /**
         * fetch data;
         */
        $select = $db->select()
            ->from( 'iep_transfer_request',
                array(
                    '*',
                    'name_county_from' =>
                    new Zend_Db_Expr("get_name_county(id_county_from)"),
                    'name_district_from' =>
                    new Zend_Db_Expr("get_name_district(id_county_from, id_district_from)"),
                    'name_school_from' =>
                    new Zend_Db_Expr("get_name_school(id_county_from, id_district_from, id_school_from)"),
                    'name_county_to' =>
                    new Zend_Db_Expr("get_name_county(id_county_to)"),
                    'name_district_to' =>
                    new Zend_Db_Expr("get_name_district(id_county_to, id_district_to)"),
                    'name_school_to' =>
                    new Zend_Db_Expr("get_name_school(id_county_to, id_district_to, id_school_to)"),
                    'get_case_manager' =>
                    new Zend_Db_Expr("get_name_school(id_county_to, id_district_to, id_school_to)"),
                )
            )
            ->where($where);
        $transferRequests = $db->fetchAll($select);
        return $transferRequests;

    }
    public function getMyTransferRequests($transferTypes = null, $limit = 100, $order = 'timestamp_last_mod desc') {
        if(is_null($transferTypes)) {
            $transferTypes = array('request');
        }
        $db = Zend_Registry::get('db');
        $usersession = new Zend_Session_Namespace ( 'user' );

        /**
         * limit access by privs
         */
        $where = "";
        foreach ($usersession->user->privs as $priv) {
            $searchCounty = $priv['id_county'];
            $searchDistrict = $priv['id_district'];
            $searchSchool = $priv['id_school'];
            if(UC_SA == $priv['class']) {
                // admin show all
                $where .= (strlen($where)>0?" OR ":"")."(1=1)";
            } elseif(UC_DM == $priv['class'] || UC_ADM ==$priv['class']) {
                $where .= (strlen($where)>0?" OR ":"")."(id_county_to = '$searchCounty' AND id_district_to = '$searchDistrict')";
            } elseif(UC_SM == $priv['class'] || UC_ASM ==$priv['class']) {
                $where .= (strlen($where)>0?" OR ":"")."(id_county_to = '$searchCounty' AND id_district_to = '$searchDistrict' AND id_school_to = '$searchSchool')";
            }
        }
        $whereTransferType = '';
        while($type = array_pop($transferTypes)) {
            $whereTransferType .= (strlen($whereTransferType)>0?" OR ":"")." transfer_type = '$type' ";
        }

        $where = "($where) and ($whereTransferType)  and timestamp_last_mod > now()-interval '6 months' ";

        /**
         * fetch data;
         */
        $select = $db->select()
            ->from( 'iep_transfer_request',
                array(
                    '*',
                    'name_county_from' =>
                    new Zend_Db_Expr("get_name_county(id_county_from)"),
                    'name_district_from' =>
                    new Zend_Db_Expr("get_name_district(id_county_from, id_district_from)"),
                    'name_school_from' =>
                    new Zend_Db_Expr("get_name_school(id_county_from, id_district_from, id_school_from)"),
                    'name_county_to' =>
                    new Zend_Db_Expr("get_name_county(id_county_to)"),
                    'name_district_to' =>
                    new Zend_Db_Expr("get_name_district(id_county_to, id_district_to)"),
                    'name_school_to' =>
                    new Zend_Db_Expr("get_name_school(id_county_to, id_district_to, id_school_to)"),
                )
            )
            ->limit($limit)
            ->where( $where )
            ->order( $order );
        //echo $select;
        $transferRequests = $db->fetchAll($select);
        return $transferRequests;
    }

    public function confirmStudentTransfer($studentId, $countyTo, $districtTo, $schoolTo, $transferRequestId)
    {
        // SELECT transfer_student_plpgsql( $studentId, '$county', '$district', '$school', '$transferRequestId') from iep_student where id_student = $studentId"
        $db = Zend_Registry::get('db');
        $stmt = $db->prepare("SELECT transfer_student_plpgsql(?, ?, ?, ?, ?)");
        $result = $stmt->execute(array($studentId, $countyTo, $districtTo, $schoolTo, $transferRequestId));
        return $result;
    }
    public function insertStudentTransferRequest($id_author, $id_county_from, $id_district_from, $id_school_from,
        $id_county_to, $id_district_to, $id_school_to, $student_count, $student_id_list, $student_name_list, $transfer_type) {
        // $request->sqlStmt = "INSERT INTO iep_transfer_request ( id_author, id_county_from, id_district_from, id_school_from, id_county_to, id_district_to, id_school_to, student_count, student_name_list, transfer_type) values ( '$sessIdUser', '$id_county_from', '$id_district_from', '$id_school_from', '$id_county_to', '$id_district_to', '$id_school_to', '$trueStudentCount', '$studentStringNoSlashes', 'request')";
        $data = array();
        $data['id_author'] = $id_author;
        $data['id_county_from'] = $id_county_from;
        $data['id_district_from'] = $id_district_from;
        $data['id_school_from'] = $id_school_from;
        $data['id_county_to'] = $id_county_to;
        $data['id_district_to'] = $id_district_to;
        $data['id_school_to'] = $id_school_to;
        $data['student_count'] = $student_count;
        $data['student_id_list'] = $student_id_list;
        $data['student_name_list'] = $student_name_list;
        $data['transfer_type'] = $transfer_type;
        $data['timestamp_created'] = date('Y-m-d H:i:s'); 
        return $this->insert($data);
    }

//    public function buildNotifictionFromTransferRequestIds($insertedTransferRequestIds=null)
      
     public function buildNotifictionFromTransferRequestIds($insertedTransferRequestIds) 

   {
        $message = '';
        foreach($insertedTransferRequestIds as $idTransferRequest) {
            $tr = $this->getTransferRequest($idTransferRequest);
            if(empty($tr)) {
                continue;
            }
            $tr = $tr[0];

            if(''!=$message) {
                $message .= "\n\n";
            } else {
                $message = "You have successfully initiated a transfer for the following students:\n\n";
            }
            $message .= $tr['student_name_list'] . " to ".$tr['name_school_to'].", ".$tr['name_district_to'].". ";
            $schoolObj = new Model_Table_School();
            $schoolMgr = $schoolObj->getCurrentManager( $tr['id_county_from'], $tr['id_district_from'], $tr['id_school_from']);
// Mike Try
            $myf2=fopen("/tmp/schoomanager.txt","w");
            fwrite($myf2,$tr['id_county_from']);fwrite($myf2,$tr['id_district_from']);fwrite($myf2,$tr['id_school_from']);
	    fwrite($myf2,$schoolMgr['email_address']);
            fclose($myf2);

            if(!empty($schoolMgr['name_last'])) {
                $message .= "A notice of this pending transfer has been sent to the SRS School Manager, ";
                $message .= $schoolMgr['name_first']." ".$schoolMgr['name_last'] . " at ".$schoolMgr['email_address'].".";
            }

//Mike
         $mail = new Zend_Mail();
        $fullname= $schoolMgr['name_first'] ." ".$schoolMgr['name_last'];
        $transport = new Zend_Mail_Transport_Smtp('localhost');
        $mail->setDefaultTransport($transport);
        $mail->setSubject("SRS Student Transfer Completed");
        $mail->setBodyText($message);
        $mail->setFrom('mdanahy@esu2.org', '<mdanahy@esu2.org>');
        if(!empty($schoolMgr['email_address'])) {
            $mail->addTo($schoolMgr['email_address'], '<'.$fullname.'>');
        }
     try {
         $mail->send($transport);
     } catch(Zend_Mail_Exception $e) {
        $log = $transport->getConnection()->getLog();
       $myfile =fopen("/tmp/mailtest.txt","w");
       fwrite($myfile,$log);fwrite($myfile,$e);
       fclose($myfile);

      }






        }
        return $message;
    }

    public function sendNotificationEmail($tr, $userSessionUser)
    {
        $schoolObj = new Model_Table_School();
        $schoolMgr = $schoolObj->getCurrentManager( $tr['id_county_from'], $tr['id_district_from'], $tr['id_school_from']);
        if(empty($schoolMgr['name'])) {
            return false;
        }

        $message = "Dear " . $schoolMgr['name'] . ":\n\n";
        $message .= $userSessionUser->user['name_full']. " has completed the transfer of the following students from your school, " .
            strtoupper( $tr['name_school_from']) . ", to " . strtoupper( $tr['name_school_to']) . ":\n\n";
        $names = $tr['student_name_list'];
        $nameArray = explode( ",", $names );
        $nameCount = count($nameArray);
        for ( $i = 1; $i <= $nameCount; $i++ ) {
            $message .= substr("    " . $i, -4)  . ". " . trim($nameArray[$i-1]) . "\n";
        }
        $message .= "\n\nIf you have any questions concerning the transfer, please contact {$userSessionUser->user['name_full']} " .
            ($userSessionUser->user['email_address'] != ''?"(".$userSessionUser->user['email_address'].")":"");

        //
        // SEND CONFIRMATION EMAIL
        //
        $mail = new Zend_Mail();
        $mail->setSubject("SRS Student Transfer Completed");
        $mail->setBodyText($message);
        $mail->setFrom('noreply@nebraskasrs.com', '<noreply@nebraskasrs.com>');
        if(!empty($schoolMgr['email_address'])) {
            $mail->addTo($schoolMgr['email_address'], '<'.$schoolMgr['name'].'>');
        }
        $mail->send();
    }
    public function sendTransferErrorEmail($studentId)
    {
        $usersession = new Zend_Session_Namespace ( 'user' );
        $message = "There was an error while trying to insert a record into nssrs_transfer by ".$usersession->user->user['name_full']." for student: $studentId<BR>";

        // SEND ERROR EMAIL
        $mail = new Zend_Mail();
        $mail->setSubject("SRS Nssrs Transfer Error");
        $mail->setBodyText($message);
        $mail->setFrom('noreply@nebraskasrs.com', '<noreply@nebraskasrs.com>');
        $mail->addTo('jlavere@soliantconsulting.com', '<Jesse LaVere>');
        $mail->send();
    }

}

