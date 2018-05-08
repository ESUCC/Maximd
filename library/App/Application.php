<?php
/*
 * 20110824 currently being used to archive pdfs
 */
class App_Application {

    function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }

    static public function archiveFormToPdf(
        $modelName,
        $formNumber,
        $usersession,
        $document,
        $legacySiteSessionId = ''
    ) {

    //    writevar1($modelName.' '.$document,'this is the modelName and formNumber');

        if(!$document) return false;

		try {
			$config = Zend_Registry::get ('config');
            $sessUser = new Zend_Session_Namespace('user');

        //    writevar1($config,'this is the config.');

            $modelform = new $modelName ($formNumber, $usersession);
			$dbData = $modelform->find ($document, 'print', 'all', null, true);
         //    writevar1($dbData,'this is the db data in Application.php line 38');

            $formDate = strtotime('now');
            if(isset($dbData['date_conference']) && !empty($dbData['date_conference'])) {
                $formDate = strtotime($dbData['date_conference']);

            } elseif(isset($dbData['date_notice']) && !empty($dbData['date_notice'])) {
                $formDate = strtotime($dbData['date_notice']);
            }

            /**
             * this is the definition of the format of the filename of the archived pdf
             * /archivePath/studentId/C-D-S/form-formNumber-formId-archived(YYYYMMDD)
             */

    //        writevar1($dbData,'this is the db data');


            $path = realpath($config->archivePath);
          //  writevar1($path,'this is the realpath');
         //   $path .= '/' . substr($dbData['id_student'], 0, 4);
         //   $path .= '/' . substr($dbData['id_student'], 4);
         //   $path .= '/' . $dbData['id_student'];
         //   $path .= '/' . $dbData['id_county'] . '_' . $dbData['id_district']. '_' . $dbData['id_school'];
          //  $birthdate=$dbData['dob'];
          //  $yearArr =explode("-",$birthdate);
         //   $birthYear=$yearArr[0];
        //    $year18=$birthYear+18;
           //   $formDate=
// Mike added this 3-2-2018 because some of the forms have no district or county number in them.  iep_form_002 is an exmple; over 8000.
         if($dbData['id_district']==Null ||$dbData['id_district']==''){
             $stuDistrict=new Model_Table_StudentTable2();
             $stuInfo=$stuDistrict->getStudentDistrict($dbData['id_student']);
             $dbData['id_county']=$stuInfo['id_county'];
             $dbData['id_district']=$stuInfo['id_district'];
             $dbData['id_school']=$stuInfo['id_school'];

         }

              $formDte=date('Y-m-d',$formDate);

              $formDateArchive=explode('-',$formDte);
              $formDateA=$formDateArchive[0];
           // writevar1($year18,'year of birth plus 18');
            $path .= '/' . $dbData['id_county'];
            $path .= '/' . $dbData['id_district'];

            $path .= '/' . $dbData['id_school'];
            $path .= '/' . $formDateA;  // This is the year of the form date meeting.  Mike 12-20-2018

            $path .= '/' . $dbData['id_student'];












           // $shortName = 'form-'.$formNumber."-".$document."-archived(" . date('Ymd', $formDate) . ")";;
            $shortName = $dbData['id_student']."-".$formNumber."-".$document."-archived_" . date('Ymd', $formDate) . "";;
      //      writevar1($path,'this is the path name');
        //    writevar1($shortName,'this is the short name of the file ');

            //"-archived(" . date('Ymd', $formDate) . ")";;
            /*
             * This is where Mike writes to table entry called mikeArchive  2-20-2018
             *
             *
             */
            $MetaDbData=array (
            'path_location'=>$path,
            'file_name'=>$shortName,
            'id_student'=>$dbData['id_student'],
            'form_type'=>$formNumber,
            'form_id'=>$document,
            'id_county'=>$dbData['id_county'],
            'id_district'=>$dbData['id_district'],
            'id_school'=>$dbData['id_school'],
            'version_number'=>$dbData['version_number']
             );

     //    writevar1($MetaDbData['id_school'],'this is the data to be put in the db line 116 applicaion.php');
         $t=false;
            $metaData=new Model_Table_ArchiveNew();
         //   writevar1($metaData,'this is the archiveNew data');
         //    writevar1($MetaDbData,'this is the data to be archived');

             try{
                 $t=  $metaData->addTo($MetaDbData);
             }
             catch (Exception $e) {
                 writevar1($e->getMessage(),'this is the problem1');
             }

        //  writevar1($t,'this is true or false');


         //    $db = Zend_Registry::get('db');
          //   $db->insert('iep_archive_meta_data', $MetaDbData);

          ///   $db = Zend_Registry::get('db');
             ////
             ////         $db->insert('neb_user', $data);
             ///


			if(!is_dir($path)) {
			    mkdir($path, 0777, true);
			}
			$tmpPDFpath = $path . '/' . $shortName . ".pdf";
       //     writevar1($sid,'this is the sis id in line 58');
			// new site
			if($dbData['version_number'] >= 9) {
				$url = $config->DOC_ROOT.'form'.$formNumber.'/print/document/'.$document.'/page';
				if(!isset($sid)) $sid = $_COOKIE['PHPSESSID'];
				$client = $sessUser->newSiteClient;
			} else {
                // old site
				$url = 'https://iep.nebraskacloud.orgu/form_print.php?form=form_'.$formNumber.'&document='.$document.'&archive=true';
				if(!isset($sid)) $sid = trim($legacySiteSessionId);
                $client = $sessUser->oldSiteClient;
			}
			// prepare client and get pdf from print action (zf or old site)
			$httpParams = array(
                'maxredirects' => 5,
                'timeout'  => 600,
			);
            $client->setUri($url);

//			$client = new Zend_Http_Client($url, $httpParams);
            if($dbData['version_number'] >= 9) {
                $cookie = new Zend_Http_Cookie('PHPSESSID-ARCHIVE', $sid, $config->DOC_ROOT);
            } else {
                $cookie = new Zend_Http_Cookie('PHPSESSID-ARCHIVE', $sid, 'iep.nebraskacloud.orgu');
            }

			$client->setCookie($cookie);

            $body = $client->request()->getBody();
           // writevar1($body,'this is the body');  // prints out the pdf of the form.
        //    writevar1($tmpPDFpath,'this is the temp pdf path');

//        echo "==================================================================================================\n";
         // $tmpPDFpath="/root/pdfs".$tmpPDFpath;
          echo "Store path: $tmpPDFpath\n";
        //  writevar1($tmpPDFpath,'this is the temp pdf path');







//        echo "url: $url\n";
//        echo "sid: $sid\n";
            try {
                $pdf = Zend_Pdf::parse($body);
                $pdf->save($tmpPDFpath);

            } catch (Exception $e) {
                Zend_Debug::dump($body);
//                die;
            }

            if(!file_exists($tmpPDFpath)) {
                // update the form with an archive flag = true
                $modelName = 'Model_Table_Form'.$formNumber;
                $tableObj = new $modelName();

         //       writevar1($modelName,'this is the model name');
                //$updateForm = $tableObj->find($document)->current();
                return array('tmpPdfPath'=>$tmpPDFpath, 'studentId' => $dbData['id_student'] ,
                    'countyId' => $dbData['id_county'], 'districtId' => $dbData['id_district'] ,
                    'schoolId' => $dbData['id_school'], 'formNumber' => $formNumber);

            } else {
                // update the form with an archive flag = true
                $modelName = 'Model_Table_Form'.$formNumber;
                $tableObj = new $modelName();
                //$updateForm = $tableObj->find($document)->current();
                return array('tmpPdfPath'=>$tmpPDFpath, 'studentId' => $dbData['id_student'] ,
                    'countyId' => $dbData['id_county'], 'districtId' => $dbData['id_district'] ,
                    'schoolId' => $dbData['id_school'], 'formNumber' => $formNumber);

            }

		} catch (Exception $e) {
			throw new Exception ( 'Error trying to archive a form to pdf.' . $e );
			return false;
		}
	}
	static public function updatePdfArchiveFlag($formNumber, $document) {
		// update the form with an archive flag = true
		$modelName = 'Model_Table_Form'.$formNumber;
		$tableObj = new $modelName();
		$updateForm = $tableObj->find($document)->current();

		$updateForm->pdf_archived = true;
		if($updateForm->save()) {
//			echo "Form archived.";
			return true;
		}
		return false;
	}
	/*
	 * $archiveDependencies is a list of tables that should also be moved
	 */
	static public function archiveFormsForTable($formNumber, $delete = false) {

	    // Start a transaction explicitly.
	    $db = Zend_Registry::get('db');

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/archive.ini', APPLICATION_ENV);
        $dbArchive = Zend_Db::factory($config->dbArchive);    // returns instance of Zend_Db_Adapter class


        $db->beginTransaction();
        $dbArchive->beginTransaction();
        echo "opening transaction...<BR>";

	    try {

	        // Attempt to execute one or more queries:
			// update the form with an archive flag = true
			$modelName = 'Model_Table_Form'.$formNumber;
			$tableObj = new $modelName();
			$updateForms = $tableObj->fetchAll("pdf_archived = true");
	    	$archiveDependencies = $tableObj->getDependentTables();

			if($updateForms->count() > 0) {
				foreach($updateForms as $formToUpdate) {
					echo "archive form {$formToUpdate['id_form_'.$formNumber]}<BR>";


					$keyName = 'id_form_'.$formNumber;

					// MAIN FORM - MOVE
					// move form row to archive
					App_Application::moveTableEntriesWithKey($db, $dbArchive, 'iep_form_'.$formNumber, $keyName, $formToUpdate[$keyName]);
					// ------------------------------------------------------------------------------------------------------
					// move dependent rows to archive
					foreach($archiveDependencies as $subModelName) {
						$subModel = new $subModelName();
						$info = $subModel->info();
						$tableName = $info['name'];
						echo "archive dependent table row: $tableName<BR>";
						// SUBFORMS - MOVE
						App_Application::moveTableEntriesWithKey($db, $dbArchive, $tableName, $keyName, $formToUpdate[$keyName]);

						// SUBFORMS - DELETE
						if($delete) $db->delete($tableName, 'id_form_'.$formNumber." = ".$formToUpdate[$keyName]);
					}
					// ------------------------------------------------------------------------------------------------------
					// MAIN FORM - DELETE
					if($delete) $db->delete('iep_form_'.$formNumber, 'id_form_'.$formNumber." = ".$formToUpdate[$keyName]);
					// ------------------------------------------------------------------------------------------------------
				}
			}

	        // If all succeed, commit the transaction and all changes
	        // are committed at once.
	        $dbArchive->commit();
	        $db->commit();
	     	echo "transaction committed...<BR>";
	    } catch (Exception $e) {
	        // If any of the queries failed and threw an exception,
	        // we want to roll back the whole transaction, reversing
	        // changes made in the transaction, even those that succeeded.
	        // Thus all changes are committed together, or none are.
	        $dbArchive->rollBack();
	        $db->rollBack();
	        echo "transaction rolled back...<BR>";
	        echo $e->getMessage();
	    }
	}
	/*
	 * $archiveDependencies is a list of tables that should also be moved
	 */
	static public function unarchiveForm($formNumber, $document, $delete = false) {

	    // Start a transaction explicitly.
	    $db = $db = Zend_Registry::get('db');

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/archive.ini', APPLICATION_ENV);
        $dbArchive = Zend_Db::factory($config->dbArchive);    // returns instance of Zend_Db_Adapter class


        $db->beginTransaction();
        $dbArchive->beginTransaction();
        echo "opening transaction...<BR>";

	    try {
			$modelName = 'Model_Table_Form'.$formNumber;
			$tableObj = new $modelName();
	    	$archiveDependencies = $tableObj->getDependentTables();

			$keyName = 'id_form_'.$formNumber;
			// move form row to archive
			echo "unarchive FORM row<BR>";
			$rowsInserted = App_Application::moveTableEntriesWithKey($dbArchive, $db, 'iep_form_'.$formNumber, $keyName, $document, false);
			if(0 === $rowsInserted) {
				echo "no form in archive<BR>";
		        $dbArchive->rollBack();
		        $db->rollBack();
		        return false;
			}

			// move dependent rows to archive
			foreach($archiveDependencies as $subModelName) {
				$subModel = new $subModelName();
				$info = $subModel->info();
				$tableName = $info['name'];
				echo "unarchive dependent table row: $tableName<BR>";
				App_Application::moveTableEntriesWithKey($dbArchive, $db, $tableName, $keyName, $document);

				// delete dependent rows from archive
				if($delete) $dbArchive->delete($tableName, 'id_form_'.$formNumber." = ".$document);
			}

			// delete form row from archive
			if($delete) $dbArchive->delete('iep_form_'.$formNumber, 'id_form_'.$formNumber." = ".$document);

	        // If all succeed, commit the transaction and all changes
	        // are committed at once.
	        $dbArchive->commit();
	        $db->commit();
	     	echo "transaction committed...<BR>";
	    } catch (Exception $e) {
	        // If any of the queries failed and threw an exception,
	        // we want to roll back the whole transaction, reversing
	        // changes made in the transaction, even those that succeeded.
	        // Thus all changes are committed together, or none are.
	        $dbArchive->rollBack();
	        $db->rollBack();
	        echo "transaction rolled back...<BR>";
	        echo $e->getMessage();
	    }
	}

	function moveTableEntriesWithKey(&$db, &$dbArchive, $tableName, $keyName, $keyValue, $deleteFirst = true)
	{
		// remove any existing entries related to this form
		if($deleteFirst) $dbArchive->delete($tableName, "$keyName = '$keyValue'");

		// insert new entries
		$rowsInserted = 0;
        if (false !== ($result = $db->query("SELECT * FROM $tableName where $keyName = '$keyValue';"))) {
            while ( false !== ($row = $result->fetch())) {
                array_walk($row, function (&$value, $key) {
                    if(strlen($value)== 0) $value = null;
                } );
                $insertResult = $dbArchive->insert ( $tableName, $row );
                $rowsInserted++;
            }
        }
        return $rowsInserted;
	}

	function formsToBeArchived($tableName, $keyName, $beforeDate, $dateField = 'date_notice')
	{
		$db = Zend_Registry::get('db');
//		Zend_Debug::dump("SELECT pdf_archived, $keyName as id, $dateField as date FROM $tableName where status = 'Final' and $dateField < '$beforeDate' order by $dateField desc;");
		$result = $db->query("SELECT pdf_archived, $keyName as id, $dateField as date FROM $tableName where status = 'Final' and $dateField < '$beforeDate' order by $dateField desc;");
//		$result = $db->query("SELECT * FROM $tableName where status = 'Final' and $dateField < '$beforeDate';");
		return $result->fetchAll();
	}
	static function isCli() {
		return php_sapi_name()==="cli";
	}
}