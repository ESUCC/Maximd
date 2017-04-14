<?php

class Model_Table_AbstractIepForm extends Zend_Db_Table_Abstract
{
    public function init($initDb = null)
    {
        if(!is_null($initDb)) {
            $this->db = $db = $initDb;
        } else {
            $this->db = $db = Zend_Registry::get('db');
        }

        Zend_Db_Table_Abstract::setDefaultAdapter($db);

        //$table = new neb_esu();
        $this->className = get_class($this);

    }

    /**
     * @return the $_primary
     */
    public function get_primary() {
        return $this->_primary;
    }


//    public function find($id)
//    {
//    	$result = parent::find($id);
//
//    	foreach($result as $r)
//    		if(!isset($r['id']))
////    	Zend_debug::dump($result);
////    	die();
//		return $result;
//    }

    public function getForm($id) {

        try {
            $table = new $this->className();
            $row = $table->fetchRow("$this->_primary = '$id'");

            if(count($row) == 0) {
                echo "error - this form is not found<br/>";
                return false;
            }
            $data = $row->toArray();

            // dates must be massaged into a nice format
            if(isset($data['iep_date'])) $data['iep_date'] =
                $table->date_massage($data['iep_date']);

            if(isset($data['date_notice'])) $data['date_notice'] =
                $table->date_massage($data['date_notice']);

            return $data;
        }
        catch (Zend_Db_Statement_Exception $e) {
            // generate error
            throw $e;
        }
        return false;
    }

    public function checkout($idForm, $idUser)
    {
        $data = array();
        $data['zend_checkout_user'] = $idUser;
        $usersession = new Zend_Session_Namespace('user');
        if(1010818 == $usersession->id_personnel || 1000254  == $usersession->id_personnel) {
            $zend_checkout_duration = '6 minutes';
        } else {
            $zend_checkout_duration = '20 minutes';
        }
        $data['zend_checkout_time'] = My_Helper_Date::date_at_timezone("r", "America/Chicago", strtotime('now+'.$zend_checkout_duration));
        if($this->saveForm($idForm, $data)) return Date('Y-m-d G:i:s', strtotime($data['zend_checkout_time']));
        return false;
    }

    public function checkoutComplete($idForm)
    {
        $data = array();
        $data['zend_checkout_user'] = null;
        $data['zend_checkout_time'] = My_Helper_Date::date_at_timezone("r", "America/Chicago", strtotime('now-10 seconds'));
        if($this->saveForm($idForm, $data)) return Date('Y-m-d G:i:s', strtotime($data['zend_checkout_time']));
        return false;
    }

    // save
    public function saveForm($id, $data) {
        //
        // security: switch to stored procedures
        //

//	        unset($data['submit']);         // make sure submit button is removed
        $pkName = $this->_primary;
        if(is_array($pkName))
        {
            $pkName = array_shift($pkName);

        }
        unset($data[$pkName]);    // make sure key isn't written
//	        unset($data['status']);         // don't allow status to be saved.

        // remove page control fields
        unset($data['navPage']);
        unset($data['returnResult']);
        unset($data['changePageAction']);
        unset($data['page']);

        foreach($data as $k => $d)
        {
//	        	if(is_array($d)) echo "$k is an array<BR/>";
//	        	if(is_array($d)) Zend_debug::dump($k);
            if(is_array($d)) $data[$k] = implode("\n", $d);
//	        	if(is_array($d)) Zend_debug::dump($data[$k]);
        }

        if(count($data) <= 0) return true;

        // dates must be null not empty string
        if(isset($data['iep_date']) && $data['iep_date']=="") $data['iep_date'] = null;
        if(isset($data['date_notice']) && $data['date_notice']=="") $data['date_notice'] = null;

        try {
            $table = new $this->className();
            $where = $table->getAdapter()->quoteInto($pkName.' = ?', $id);
            $table->update($data, $where);
//            print_r($data);
//            print_r($where);
//            echo "success";die();
            return true;
        } catch (Zend_Db_Statement_Exception $e) {
//	        	echo "$id\n";
//	            print_r($data);die();
            // generate error
            throw $e;
        }
        return false;
    }

    // insert method
    public function inserForm($data = null) {

        unset($data['submit']); // make sure submit button is removed
        unset($data[$this->_primary]); // make sure key isn't written

//	        try {
        $table = new $this->className();
//	            print_r($table);
//	            print_r($data);//die();
        $result = $table->insert($data);
//	            print_r($result);
        return $result;

//	        } catch (Zend_Db_Statement_Exception $e) {
//	            // generate error
//	            throw $e;
//	        }
        return false;
    }

    static function date_massage($dateField, $dateFormat = 'm/d/Y') {

        if(empty($dateField) ) {
            return;
        }

        # strtotime mishandles dates with '-'
        $dateField=str_replace("-","/",$dateField);
        date_default_timezone_set('GMT');
        return date($dateFormat, strtotime($dateField));

    }

    // count subforms
    function countSubforms($subTableName, $subTablePkName, $subTablePkValue)
    {
        #echo "subTableName: $subTableName<BR>";
        #echo "subTablePkName: $subTablePkName<BR>";
        #echo "subTablePkValue: $subTablePkValue<BR>";
        try {
            $db = Zend_Registry::get('db');
            $subTablePkValue = $db->quote($subTablePkValue);

            $select = $db->select()
                ->from( $subTableName,
                    array('count' => 'count(1)',
                    ))
                ->where( "$subTablePkName = $subTablePkValue" );
            //->order( "" );
            $results = $db->fetchAll($select);

            if(count($results) > 0)  {
                return $results[0]['count'];
            }
            return false;

        } catch (Zend_Db_Statement_Exception $e) {
            // generate error
            echo "error: $e";
            die();
        }
        return false;
    }

    function getChildRecords($subTableName, $subTablePkName, $subTablePkValue, $sortField = null, $status = null)
    {
//	        echo "subTableName: $subTableName<BR>";
//	        echo "subTablePkName: $subTablePkName<BR>";
//	        echo "subTablePkValue: $subTablePkValue<BR>";
        try {
            $db = Zend_Registry::get('db');
            $subTablePkValue = $db->quote($subTablePkValue);

            if(null == $sortField) {
                $select = $db->select()
                    ->from( $subTableName,
                        array('*',
                            //'count' => 'count(1)',
                        ))
                    ->where( "$subTablePkName = $subTablePkValue" )
                ;
                if(null !== $status) $select->where('status = ?', $status);
            } else {
                $select = $db->select()
                    ->from( $subTableName,
                        array('*',
                            //'count' => 'count(1)',
                        ))
                    ->where( "$subTablePkName = $subTablePkValue"
                    )
                    ->order( $sortField );

                if(null !== $status) $select->where('status = ?', $status);
            }
            $results = $db->fetchAll($select);

            if(count($results) > 0) {
//	                print_r($results);
                return $results;
            }
            return false;

        } catch (Zend_Db_Statement_Exception $e) {
            // generate error
            echo "error: $e";
            die();
        }
        return false;
    }

    function updateSubformsOrder($subTableName, $subTablePkName, $fkName, $fkValue, $orderKeyName)
    {
        #echo "subTableName: $subTableName<BR>";
        #echo "fkName: $fkName<BR>";
        #echo "fkValue: $fkValue<BR>";
        try
        {
            $db = Zend_Registry::get('db');
            $fkValue = $db->quote($fkValue);

            $select = $db->select()
                ->from( $subTableName,
                    array($orderKeyName, $subTablePkName,
                    ))
                ->where( "$fkName = $fkValue" )
                ->order( $orderKeyName );
            $results = $db->fetchAll($select);

            if(count($results) > 0)
            {
                //
                // update each item so order is sequential
                //

                // ditch anything that is not a number
                $number = preg_replace("/[^0-9]/", '', $subTableName);
                $posLastUnderscore = strrpos($subTableName, "_");
                //get string after last underscore
                $stringAfterLastUnderscore = substr($subTableName, $posLastUnderscore+1);
                $subform = "DbTable_iepForm".$number.ucfirst($stringAfterLastUnderscore);
                //$objForm = new DbTable_iepForm024Consent();
                $objForm = new $subform();
                $i = 1;
                foreach($results as $row)
                {
                    // update with key
                    //$objForm->saveForm($row['id_form_024_consent'], array($orderKeyName => $i));
                    $objForm->saveForm($row[$subTableName], array($orderKeyName => $i));
                    $i++;
                }
            }

            return false;
        }
        catch (Zend_Db_Statement_Exception $e) {
            // generate error
            echo "error: $e";
            die();
        }

        return false;
    }

    function mostRecentForm($id_student)
    {
        if(null == $id_student) return false;
        $table = new $this->className();

        if('Model_Table_Form002' == get_class($this) || 'Model_Table_Form022' == get_class($this)) {
            $row = $table->fetchRow("id_student = '$id_student' AND date_mdt IS NOT NULL", 'date_mdt desc');
        } elseif(in_array('date_conference', $this->info('cols'))) {
            $row = $table->fetchRow("id_student = '$id_student' AND date_conference IS NOT NULL", 'date_conference desc');
        } elseif(in_array('date_notice', $this->info('cols'))) {
            $row = $table->fetchRow("id_student = '$id_student' AND date_notice IS NOT NULL", 'date_notice desc');
        } else {
            $row = $table->fetchRow("id_student = '$id_student'");
        }
        return $row;
    }

    function mostRecentFinalForm($id_student, $forcedDateField = null)
    {
        $table = new $this->className();
        if('Model_Table_Form002' == get_class($this) || 'Model_Table_Form022' == get_class($this)) {
            $row = $table->fetchRow("id_student = '$id_student' AND status = 'Final' and date_mdt IS NOT NULL",
                'date_mdt desc');
        } elseif(!is_null($forcedDateField) && in_array($forcedDateField, $this->info('cols'))) {
            $row = $table->fetchRow("id_student = '$id_student' and status = 'Final' AND '" . $forcedDateField . "' IS NOT NULL", $forcedDateField . ' desc');
        } elseif(in_array('date_conference', $this->info('cols'))) {
            $row = $table->fetchRow("id_student = '$id_student' and status = 'Final' AND date_conference IS NOT NULL", 'date_conference desc');
        } elseif(in_array('date_notice', $this->info('cols'))) {
            $row = $table->fetchRow("id_student = '$id_student' and status = 'Final' AND date_notice IS NOT NULL", 'date_notice desc');
        } else {
            Zend_Debug::dump($id_student, 'inside');
            $row = $table->fetchRow("id_student = '$id_student' and status = 'Final'");
        }
        return $row;
    }
    function getAllFinalForms($id_student, $sortField = "date_notice asc")
    {
        $table = new $this->className();
        if(in_array('date_conference', $this->info('cols'))) {
            $allRows = $table->fetchAll("id_student = '$id_student' and status = 'Final'", 'date_conference desc');
        } elseif(in_array('date_notice', $this->info('cols'))) {
            $allRows = $table->fetchAll("id_student = '$id_student' and status = 'Final'", 'date_notice desc');
        } else {
            $allRows = $table->fetchAll("id_student = '$id_student' and status = 'Final'");
        }
        return $allRows;
    }

    function finalForms($id_student, $sortField = "date_notice asc")
    {
        $table = new $this->className();
        $row = $table->fetchAll("id_student = '$id_student' and status = 'Final'");
        return $row;
    }

    function mostRecentDraftForm($id_student, $sortField = "date_notice asc")
    {
        $table = new $this->className();
        $select = $table->select()
            ->where( 'id_student = ?', $id_student )
            ->where( 'status = ?', 'Draft' )
            ->order( $sortField );
        $row = $table->fetchRow($select);
        if($row) {
            $row = $row->toArray();
        }
        return $row;
    }

    function getWhere($fieldName, $fieldValue, $sortField = "date_notice asc")
    {
        $table = new $this->className();
        $select = $table->select()
            ->where( $fieldName.' = ?', $fieldValue )
            ->order( $sortField );
        return $table->fetchAll($select);
    }

    public function getUniqueHash($tableName, $field, $count = 1) {
        if(10<$count) return null;
        $model = new $tableName;
        $uniqueHash = md5(uniqid(mt_rand(), true));

        $result = $model->fetchAll("$field = '$uniqueHash'");
        if(0==$result->count()) {
            return $uniqueHash;
        } else {
            return getUniqueHash($tableName, $field, $count++);
        }
    }

    public function getDependentTables() {
        return $this->_dependentTables;
    }
    public function getPrimaryKeyName() {
        return $this->_primary;
    }
    public function getTableName() {
        return $this->_name;
    }
}
