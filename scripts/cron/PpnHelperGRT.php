<?php
class PpnHelperGRT
{
    static public function archiveFormToPdf(
        $modelName,
        $formNumber,
        $usersession,
        $document,
        $legacySiteSessionId = '',
        $path = null,
        $shortName = null,
        $overwrite = true
    ) {

        if (!$document) {
            return false;
        }

        try {
            $config = Zend_Registry::get('config');
            $docRoot = trim($config->DOC_ROOT, "/");

            if (is_null($shortName)) {
                $shortName = 'form-' . $formNumber . "-" . $document . "-archived";
            }
            $sessUser = new Zend_Session_Namespace('user');

            // just to get version number
            $modelform = new $modelName ($formNumber, $usersession);
            $dbData = $modelform->find($document, 'print', 'all', null, true);

            if (is_null($path)) {
                $path = realpath($config->archivePath) . '/' . $dbData['id_student']
                    . '/' . $dbData['id_county'] . '_' . $dbData['id_district']
                    . '_' . $dbData['id_school'];

            }
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $tmpPDFpath = $path . '/' . $shortName . ".pdf";

            if(file_exists($tmpPDFpath) && false === $overwrite) {
                return false;
            }

            if (App_Application::isCli()) {
                $sid = 'Archive0123456789012345678901234567890Archive';
            }
            // new site
            $url = $docRoot . '/form004/print/summary/1/document/' . $document;
            if (!isset($sid) && isset($_COOKIE['PHPSESSID'])) {
                $sid = $_COOKIE['PHPSESSID'];
            }
            $client = $sessUser->newSiteClient;
            // prepare client and get pdf from print action (zf or old site)
            $client->setUri($url);
            $body = $client->request()->getBody();

            try {
                $pdf = Zend_Pdf::parse($body);
                $pdf->save($tmpPDFpath);

            } catch (Exception $e) {
                return false;
            }

            return file_exists($tmpPDFpath);

        } catch (Exception $e) {
            echo "Error trying to archive a form to pdf.\n\n";
            return false;
        }
    }

    public static function getStudentsForArchiving()
    {
        $stmt = "SELECT id_student, id_student_local ";
        $stmt .= "FROM iep_student s ";
        $stmt .= "WHERE s.id_county = '77' and s.id_district = '0037' and s.status = 'Active' ";
//        echo "$stmt\n";
        $result = Zend_Registry::get('db')->query($stmt);
        return $result->fetchAll();
    }



    static function mostRecentFinalForm($studentId, $formNo)
    {
        $modelName = 'Model_Table_Form' . substr('000' . $formNo, -3, 3);
        try {
            $formObj = new $modelName();
            $form = $formObj->mostRecentFinalForm($studentId);

        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        if (count($form)) {
            return $form;
        } else {
            return null;
        }
    }

    static function setMissingIdStudentLocals($county, $district) {
        $student = new Model_Table_StudentTable();
        list($found, $not_found) = $student->setMissingIdStudentLocals($county, $district);
        $mail = new Zend_Mail();
        $mailBody  = "";
        if (!empty($found)) {
        $mailBody .= "The export was able to match and update local student Ids for the following students: \r\n\r\n";
            foreach ($found as $f) {
                $mailBody .= $f['name_first'] . ',' . $f['name_middle'] . ',' . $f['name_last'] . ',' . $f['dob'] . "\r\n";
            }
        }
        if (!empty($not_found)) {
            $mailBody .= "The export was UNABLE to match local student Ids for the following students: \r\n\r\n";
            foreach ($not_found as $n) {
                //$mailBody .= $n['name_first'] . ',' . $n['name_middle'] . ',' . $n['name_last'] . ',' . $n['dob'] . "\r\n";
$mailBody .= $n['id_student'].','.$n['name_first'] . ',' . $n['name_middle'] . ',' . $n['name_last'] . ',' . $n['dob'] .','.$n['unique_id_state'].','.$n['id_student_local']."\r\n";
            }
        }
        if (empty($found) && empty($not_found)) {
            $mailBody .= "All local student Ids are up to date";
        }

	echo $mailBody;

        //$mailTransport = new Zend_Mail_Transport_Sendmail();
        //$mail->setSubject('Missing Local Student Id Report for Papillion Summary PDF Export');
        //$mail->setBodyText($mailBody);
        //$mail->addTo('wigglaff@gmail.com', 'Steve Bennett');
        //$mail->send($mailTransport);
    }
}
