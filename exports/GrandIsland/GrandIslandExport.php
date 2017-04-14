<?php

/**
 * require the main export factory
 */
require_once('ExportFactoryGi.php');

class GrandIslandExport extends ExportFactoryGi {

    var $delimiter = ':'; // inside of fields not the file format

    public function __construct() {
        parent::__construct();

        echo "\n\nGrand Island Begin Export\n";
        $finalLog = "\n\nBegin Export...\n";
        $finalLog .= $this->dumpLog();
        $this->clearMetaData();

        /**
         * get the main application AND import config files
         */
        $appConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $exportConfig = new Zend_Config_Ini('GrandIsland/export.ini', APPLICATION_ENV);
        $this->exportConfig = $exportConfig;
        $this->dataSource = $exportConfig->data_source;
        $this->initEmail($exportConfig->email);

        /** create database connection */
        $dbConfig = $appConfig->db2;
        $db = Zend_Db::factory($dbConfig);    // returns instance of Zend_Db_Adapter class
        Zend_Registry::set('db', $db);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);

        /**
         * logging helper
         * must be fired after pre-flight file
         */
        $emptyDataSourceCount = $this->countEmptyDataSource();
        $finalLog .= "\n\nPre-export students with an empty data_source field: " . count($emptyDataSourceCount)."\n";

        $finalLog .= $this->dumpLog();
        $this->clearMetaData();

        /**
         * export students
         */
        $success = $this->exportStudents();
        if($success) {
            /**
             * FTP RESULTS
             */
            $conn_id = ftp_connect($this->exportConfig->ftp->host) or die ("Cannot connect to host");
            // login with username and password
            $login_result = ftp_login($conn_id, $this->exportConfig->ftp->username, $this->exportConfig->ftp->password);
            if($login_result) {
                ftp_pasv($conn_id, true); // turn on passive mode
                ftp_login($conn_id, $this->exportConfig->ftp->username, $this->exportConfig->ftp->password) or die("Cannot login");
                /**
                 * upload file
                 */
                $exportPath = realpath(APPLICATION_PATH . '/../' . $this->exportConfig->studentExportFile->filepath .'/') .'/'. $this->exportConfig->studentExportFile->filename;

                $upload = ftp_put($conn_id, $this->exportConfig->studentExportFile->filename, $exportPath, FTP_BINARY);
                if($upload) {
                    $finalLog .= "FTP Upload Successful\n";
                } else {
                    $finalLog .= "FTP Upload FAILED!!\n";
                    $success = false;
                }
                // close the FTP stream
                ftp_close($conn_id);
            }
        }
        /**
         * EMAIL THE RESULTS
         */
        $this->sendNotificationEmail($this->exportConfig->email, $finalLog, $success);

        echo $finalLog;
        return true;
    }

    public function initialVerificationDate($student) {
        $mdt = $this->lastMdt($student);
        if(is_object($mdt)) {
            return $mdt->initial_verification_date;
        } else {
            return null;
        }
    }

    public function placementType($student) {
        switch($student->pub_school_student) {
            case 't':
                return 1;
            case 'f':
                return 0;
            default:
                return null;
        }
    }
    public function primarySetting($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $iep->primary_service_location;
        } else {
            return null;
        }
    }
    public function schoolAgedIndicator($student) {
        return $student->grade;
    }
    public function specialEducationPercentage($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $iep->special_ed_non_peer_percent;
        } else {
            return null;
        }
    }
    public function placementReason ($student) {
        switch($student->parental_placement) {
            case 't':
                return 1;
            case 'f':
                return 0;
            default:
                return null;
        }
    }
    public function verifiedDisability ($student) {
        $mdt = $this->lastMdt($student);
        if(is_object($mdt)) {
            switch ($mdt->disability_primary) {
                case 'MHSP':
                    return '16';
                case 'BD':
                    return '01';
                case 'TBI':
                    return '14';
                case 'AU':
                    return '13';
                case 'HI':
                    return '03';
                case 'OI':
                    return '08';
                case 'OHI':
                    return '09';
                case 'DD':
                    return '15';
                case 'MH':
                    return '16';
                case 'MHMI':
                    return '16';
                case 'MULTI':
                    return '07';
                case 'DB':
                    return '02';
                case 'SLI':
                    return '11';
                case 'MHMO':
                    return '16';
                case 'VI':
                    return '12';
                case 'SLD':
                    return '10';
                default:
                    return null;
            }
        } else {
            return null;
        }
    }
    public function caseManager ($student) {
        if(null==$student->id_case_mgr) {
            return null;
        }
        $cm = $this->getPersonnel($student->id_case_mgr);
        if($cm) {
            return $cm->name_first . ' ' . $cm->name_last;
        } else {
            return null;
        }
    }

    public function studentStrengths ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->student_strengths);
        } else {
            return null;
        }
    }
    public function resultsPerfermorance ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->results_perf);
        } else {
            return null;
        }
    }

    public function behavioralStrategies ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->behavioral_strategies);
        } else {
            return null;
        }
    }

    public function primaryDisability ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->primary_disability);
        } else {
            return null;
        }
    }


    public function assessmentDesc ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->assessment_desc);
        } else {
            return null;
        }
    }

    public function resultsEvaluation ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->results_evaluation);
        } else {
            return null;
        }
    }

    public function resultsPerf ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->results_perf);
        } else {
            return null;
        }
    }

    public function languageNeeds ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->language_needs);
        } else {
            return null;
        }
    }

    public function brailleInstruction ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->braille_instruction);
        } else {
            return null;
        }
    }

    public function commNeeds ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->comm_needs);
        } else {
            return null;
        }
    }

    public function deafCommNeeds ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->deaf_comm_needs);
        } else {
            return null;
        }
    }

    public function assistiveTech ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->assistive_tech);
        } else {
            return null;
        }
    }

    public function presentLevPerf ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->present_lev_perf);
        } else {
            return null;
        }
    }

    public function specialEdPeerPercentWithRegEd ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->special_ed_peer_percent);
        } else {
            return null;
        }
    }

    public function specialEdPeerPercentNotRegEd ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->special_ed_non_peer_percent);
        } else {
            return null;
        }
    }

    public function regularEducationPeers ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->reg_ed_percent);
        } else {
            return null;
        }
    }

    public function transportationRadio ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            switch($iep->transportation_yn) {
                case 1:
                case 't':
                    return 1;
                case 0;
                case 'f':
                    return null;
                default:
                    return null;
            }
        } else {
            return null;
        }
    }

    public function assessment ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->assessment_accom);
        } else {
            return null;
        }
    }

    public function extSchoolYearRadio ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->ext_school_year_yn);
        } else {
            return null;
        }
    }

    /**
     * related table data
     */
    public function iepParticipants ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            if(9 <= $iep->version_number) {
                $participants = $iep->findDependentRowset('Model_Table_Form004TeamMember');
                if($participants->count()) {
                    $retString = '';
                    foreach ($participants as $p) {
                        if(''==$p->participant_name) continue;
                        if(''!=$retString) $retString .= $this->delimiter;
                        $retString .= $p->participant_name;
                    }
                    return $retString;
                }
            } else {
                return str_replace(";", $this->delimiter, trim($iep->participant_names, ';'));
            }
            return null;
        } else {
            return null;
        }
    }
    public function programModifications ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            if(9 <= $iep->version_number) {
                $progMods = $iep->findDependentRowset('Model_Table_Form004ProgramModifications');
                if($progMods->count()) {
                    $retString = '';
                    foreach ($progMods as $progMod) {
                        if('Active'!=$progMod->status) continue;
                        if(''!=$retString) $retString .= $this->delimiter;
                        $retString .= $this->removeReturns($progMod->prog_mod);
                    }
                    return $retString;
                }
                return null;
            } else {
                return str_replace("|", $this->delimiter, trim($iep->prog_mod, '|'));
            }
            return null;

        } else {
            return null;
        }
    }

    public function reviewDates ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            if(9 <= $iep->version_number) {
                $goals = $iep->findDependentRowset('Model_Table_Form004Goal');
                if($goals->count()) {
                    $retString = '';
                    foreach ($goals as $goal) {
                        if('Active'!=$goal->status) continue;
                        if(''!=$retString) $retString .= $this->delimiter;
                        $retString .= $this->removeReturns($goal->progress_date1).',';
                        $retString .= $this->removeReturns($goal->progress_date2).',';
                        $retString .= $this->removeReturns($goal->progress_date3).',';
                        $retString .= $this->removeReturns($goal->progress_date4).',';
                        $retString .= $this->removeReturns($goal->progress_date5).',';
                        $retString .= $this->removeReturns($goal->progress_date6);
                    }
                    return $retString;
                }
            }
            return null;

        } else {
            return null;
        }
    }
    public function postSecondary ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            if(9 <= $iep->version_number) {
                $goals = $iep->findDependentRowset('Model_Table_Form004SecondaryGoal');
                if($goals->count()) {
                    $retString = '';
                    foreach ($goals as $goal) {
                        if('deleted'==strtolower($goal->status)) continue;
                        if(''!=$retString) $retString .= $this->delimiter;
                        $retString .= $this->removeReturns($goal->post_secondary);
                    }
                    return $retString;
                }
            }
            return null;
        } else {
            return null;
        }
    }
    public function iepTeam ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            if(9 <= $iep->version_number) {
                $teamMembers = $iep->findDependentRowset('Model_Table_Form004TeamMember');
                if($teamMembers->count()) {
                    $teamArray = array();
                    if($teamMembers->count()) {
                        $retString = '';
                        foreach ($teamMembers as $teamMember) {
                            if('Active'!=$teamMember->status) continue;
                            if(''!=$retString) $retString .= $this->delimiter;
                            $retString .= $this->removeReturns($teamMember->participant_name);
                        }
                        return $retString;
                    }
                }
            }
            return '';
        } elseif(!is_null($iep)) {
            if(!is_null($iep->participant_names)) {
                $teamArray = explode(';', $iep->participant_names);
                $retString = '';
                foreach ($teamArray as $teamMember) {
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $this->removeReturns($teamMember->participant_name);
                }
                return $retString;
            }
        }
        return '';
    }

    public function measurableAnnualGoal ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            if(9 <= $iep->version_number) {
                $goals = $iep->findDependentRowset('Model_Table_Form004Goal');
                if($goals->count()) {
                    $retString = '';
                    foreach ($goals as $goal) {
                        if('Active'!=$goal->status) continue;
                        if(''!=$retString) $retString .= $this->delimiter;
                        $retString .= $this->removeReturns($goal->measurable_ann_goal);
                    }
                    return $retString;
                }
            }
            return null;
        } else {
            return null;
        }
    }

    public function relatedServices($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $relatedServices = $iep->findDependentRowset('Model_Table_Form004RelatedService');
                $retString = '';
                foreach ($relatedServices as $relatedService) {
                    if('Active'!=$relatedService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $relatedService->related_service_drop;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->related_service_drop, '|'));
            }
        } else {
            return null;
        }
    }

    public function relatedServicesFromDate($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $relatedServices = $iep->findDependentRowset('Model_Table_Form004RelatedService');
                $retString = '';
                foreach ($relatedServices as $relatedService) {
                    if('Active'!=$relatedService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $relatedService->related_service_from_date;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->related_service_from_date, '|'));
            }
        } else {
            return null;
        }
    }

    public function relatedServicesToDate($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $relatedServices = $iep->findDependentRowset('Model_Table_Form004RelatedService');
                $retString = '';
                foreach ($relatedServices as $relatedService) {
                    if('Active'!=$relatedService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $relatedService->related_service_to_date;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->related_service_to_date, '|'));
            }
        } else {
            return null;
        }
    }

    public function primaryService($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $iep->primary_disability_drop;
        } else {
            return null;
        }
    }

    public function primaryServiceTpd($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $iep->primary_service_tpd;
        } else {
            return null;
        }
    }
    public function primaryServiceTpdUnit($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $iep->primary_service_tpd_unit;
        } else {
            return null;
        }
    }
    public function primaryServiceDaysValue($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $iep->primary_service_days_value;
        } else {
            return null;
        }
    }
    public function primaryServiceDaysUnit($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $iep->primary_service_days_unit;
        } else {
            return null;
        }
    }

    public function primaryServiceFromDate($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $iep->primary_service_from;
        } else {
            return null;
        }
    }

    public function primaryServiceToDate($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $iep->primary_service_to;
        } else {
            return null;
        }
    }

    public function relatedServiceLocation($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $relatedServices = $iep->findDependentRowset('Model_Table_Form004RelatedService');
                $retString = '';
                foreach ($relatedServices as $relatedService) {
                    if('Active'!=$relatedService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $relatedService->related_service_location;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->related_service_location, '|'));
            }
        } else {
            return null;
        }
    }

    public function relatedServiceFreqSmall($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $relatedServices = $iep->findDependentRowset('Model_Table_Form004RelatedService');
                $retString = '';
                foreach ($relatedServices as $relatedService) {
                    if('Active'!=$relatedService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $relatedService->related_service_tpd;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->related_service_tpd, '|'));
            }
        } else {
            return null;
        }
    }
    public function relatedServicesFreqSmallUnit($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $relatedServices = $iep->findDependentRowset('Model_Table_Form004RelatedService');
                $retString = '';
                foreach ($relatedServices as $relatedService) {
                    if('Active'!=$relatedService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $relatedService->related_service_tpd_unit;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->related_service_tpd_unit, '|'));
            }
        } else {
            return null;
        }
    }
    public function relatedServiceFreqMed($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $relatedServices = $iep->findDependentRowset('Model_Table_Form004RelatedService');
                $retString = '';
                foreach ($relatedServices as $relatedService) {
                    if('Active'!=$relatedService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $relatedService->related_service_days_value;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->related_service_days_value, '|'));
            }
        } else {
            return null;
        }
    }
    public function relatedServicesFreqMedUnit($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $relatedServices = $iep->findDependentRowset('Model_Table_Form004RelatedService');
                $retString = '';
                foreach ($relatedServices as $relatedService) {
                    if('Active'!=$relatedService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $relatedService->related_service_days_unit;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->related_service_days_unit, '|'));
            }
        } else {
            return null;
        }
    }

    /**
     * supp
     */
    public function supplementaryService($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $suppServices = $iep->findDependentRowset('Model_Table_Form004SupplementalService');
                $retString = '';
                foreach ($suppServices as $suppService) {
                    if('Active'!=$suppService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $suppService->supp_service;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->supp_service, '|'));
            }
        } else {
            return null;
        }
    }

    public function supplementaryServiceLocation($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $suppServices = $iep->findDependentRowset('Model_Table_Form004SupplementalService');
                $retString = '';
                foreach ($suppServices as $suppService) {
                    if('Active'!=$suppService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $suppService->supp_service_location;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->supp_service_location, '|'));
            }
        } else {
            return null;
        }
    }

    public function supplementaryServiceFreqSmall($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $suppServices = $iep->findDependentRowset('Model_Table_Form004SupplementalService');
                $retString = '';
                foreach ($suppServices as $suppService) {
                    if('Active'!=$suppService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $suppService->supp_service_tpd;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }
    public function supplementaryServiceFreqMed($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $suppServices = $iep->findDependentRowset('Model_Table_Form004SupplementalService');
                $retString = '';
                foreach ($suppServices as $suppService) {
                    if('Active'!=$suppService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $suppService->supp_service_days_value;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    /**
     * program modifications
     */
    public function progModService($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $progMods = $iep->findDependentRowset('Model_Table_Form004ProgramModifications');
                $retString = '';
                foreach ($progMods as $progMod) {
                    if('Active'!=$progMod->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $this->removeReturns($progMod->prog_mod);
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->prog_mod, '|'));
            }
        } else {
            return null;
        }
    }

    public function progModServiceLocation($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $progMods = $iep->findDependentRowset('Model_Table_Form004ProgramModifications');
                $retString = '';
                foreach ($progMods as $progMod) {
                    if('Active'!=$progMod->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $progMod->prog_mod_location;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->prog_mod_location, '|'));
            }
        } else {
            return null;
        }
    }

    public function progModServiceFreqSmall($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $progMods = $iep->findDependentRowset('Model_Table_Form004ProgramModifications');
                $retString = '';
                foreach ($progMods as $progMod) {
                    if('Active'!=$progMod->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $progMod->prog_mod_tpd;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }
    public function progModServiceFreqMed($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $progMods = $iep->findDependentRowset('Model_Table_Form004ProgramModifications');
                $retString = '';
                foreach ($progMods as $progMod) {
                    if('Active'!=$progMod->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $progMod->prog_mod_days_value;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    /**
     * ass tech
     */
    public function assistTechService($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $assistTechs = $iep->findDependentRowset('Model_Table_Form004AssistiveTechnology');
                $retString = '';
                foreach ($assistTechs as $assistTech) {
                    if('Active'!=$assistTech->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $assistTech->ass_tech;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->ass_tech, '|'));
            }
        } else {
            return null;
        }
    }

    public function assistTechServiceLocation($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $assistTechs = $iep->findDependentRowset('Model_Table_Form004AssistiveTechnology');
                $retString = '';
                foreach ($assistTechs as $assistTech) {
                    if('Active'!=$assistTech->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $assistTech->assist_tech_location;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->ass_tech_location, '|'));
            }
        } else {
            return null;
        }
    }

    public function assistTechServiceFreqSmall($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $assistTechs = $iep->findDependentRowset('Model_Table_Form004AssistiveTechnology');
                $retString = '';
                foreach ($assistTechs as $assistTech) {
                    if('Active'!=$assistTech->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $assistTech->assist_tech_tpd;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }
    public function assistTechServiceFreqMed($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $assistTechs = $iep->findDependentRowset('Model_Table_Form004AssistiveTechnology');
                $retString = '';
                foreach ($assistTechs as $assistTech) {
                    if('Active'!=$assistTech->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $assistTech->assist_tech_days_value;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    /**
     * ass tech
     */
    public function schoolSupportService($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $schoolSupports = $iep->findDependentRowset('Model_Table_Form004SchoolSupport');
                $retString = '';
                foreach ($schoolSupports as $schoolSupport) {
                    if('Active'!=$schoolSupport->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $schoolSupport->supports;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->supports, '|'));
            }
        } else {
            return null;
        }
    }

    public function schoolSupportServiceLocation($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $schoolSupports = $iep->findDependentRowset('Model_Table_Form004SchoolSupport');
                $retString = '';
                foreach ($schoolSupports as $schoolSupport) {
                    if('Active'!=$schoolSupport->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $schoolSupport->school_supp_location;
                }
                return $retString;
            } else {
                return str_replace(";", $this->delimiter, trim($iep->supports_location, '|'));
            }
        } else {
            return null;
        }
    }

    public function schoolSupportServiceFreqSmall($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $schoolSupports = $iep->findDependentRowset('Model_Table_Form004SchoolSupport');
                $retString = '';
                foreach ($schoolSupports as $schoolSupport) {
                    if('Active'!=$schoolSupport->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $schoolSupport->school_supp_tpd;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }
    public function schoolSupportServiceFreqMed($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 <= $iep->version_number) {
                $schoolSupports = $iep->findDependentRowset('Model_Table_Form004SchoolSupport');
                $retString = '';
                foreach ($schoolSupports as $schoolSupport) {
                    if('Active'!=$schoolSupport->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $schoolSupport->school_supp_days_value;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }
    public function transition_16_course_study($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_16_course_study);
        } else {
            return null;
        }
    }
    public function transition_16_instruction($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_16_instruction);
        } else {
            return null;
        }
    }
    public function transition_16_rel_services($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_16_rel_services);
        } else {
            return null;
        }
    }
    public function transition_16_comm_exp($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_16_comm_exp);
        } else {
            return null;
        }
    }
    public function transition_16_emp_options($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_16_emp_options);
        } else {
            return null;
        }
    }
    public function transition_16_dly_liv_skills($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_16_dly_liv_skills);
        } else {
            return null;
        }
    }
    public function transition_16_func_voc_eval($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_16_func_voc_eval);
        } else {
            return null;
        }
    }
    public function transition_16_inter_agency_link($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_16_inter_agency_link);
        } else {
            return null;
        }
    }
    public function transition_activity1($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_activity1);
        } else {
            return null;
        }
    }
    public function transition_activity2($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_activity2);
        } else {
            return null;
        }
    }
    public function transition_activity3($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_activity3);
        } else {
            return null;
        }
    }
    public function transition_agency1($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_agency1);
        } else {
            return null;
        }
    }
    public function transition_agency2($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_agency2);
        } else {
            return null;
        }
    }
    public function transition_agency3($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_agency3);
        } else {
            return null;
        }
    }
    public function transition_date1($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_date1);
        } else {
            return null;
        }
    }
    public function transition_date2($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_date2);
        } else {
            return null;
        }
    }
    public function transition_date3($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            return $this->removeReturns($iep->transition_date3);
        } else {
            return null;
        }
    }
    public function form006Last3DateNotice($student) {
        try {
            $form006Obj = new Model_Table_Form006();
            $form006Forms = $form006Obj->getAllFinalForms($student->id_student);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        if(count($form006Forms)) {
            $retString = '';
            foreach ($form006Forms as $form) {
                if(''!=$retString) $retString .= $this->delimiter;
                $retString .= $form->date_notice;
            }
            return $retString;
        } else {
            return null;
        }
    }
    public function pdfFileNames($student)
    {
        $notesPage = $this->mostRecentFinalForm($student, '017');

        $retString = '';
        if ($handle = @opendir(APPLICATION_PATH . '/user_images/uploaded_pdf/PDF_017_'.$notesPage->id_form_017)) {
//            echo "Directory handle: $handle\n";
//            echo "Entries:\n";

            /* This is the correct way to loop over the directory. */
            while (false !== ($entry = readdir($handle))) {
                if('.'!=substr($entry, 0, 1)) {
//                    echo "$entry\n";
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $entry;
                }
            }
            closedir($handle);
        }
        return $retString;
    }

    public function iepIfspBeginDate($student) {
        $iep = $this->mostRecentFinalForm($student, '004');
        $ifsp = $this->mostRecentFinalForm($student, '013');

        if(is_object($iep) && is_object($ifsp)) {
            if($iep['finalized_date'] >= $ifsp['finalized_date']) {
                // iep is the form to use
                return $this->iepBeginDate($iep);
            } else {
                // ifsp is the form to use
                return $this->ifspBeginDate($ifsp);
            }
        } elseif(is_object($iep)) {
            return $this->iepBeginDate($iep);
        } elseif(is_object($ifsp)) {
            return $this->ifspBeginDate($ifsp);
        } else {
            return null;
        }
    }
    public function iepBeginDate($iep) {
        return $iep['effect_from_date'];
    }
    public function ifspBeginDate($ifsp) {
        return $ifsp['meeting_date'];
    }
    public function iepIfspEndDate($student) {
        $iep = $this->mostRecentFinalForm($student, '004');
        $ifsp = $this->mostRecentFinalForm($student, '013');

        $date = date_parse($iep['finalized_date']);
        if (count($date["errors"]) == 0 && checkdate($date["month"], $date["day"], $date["year"])) {
            $iepValidDate = true;
        } else {
            $iepValidDate = false;
        }

        $date = date_parse($ifsp['finalized_date']);
        if (count($date["errors"]) == 0 && checkdate($date["month"], $date["day"], $date["year"])) {
            $ifspValidDate = true;
        } else {
            $ifspValidDate = false;
        }
        if($iepValidDate && $ifspValidDate) {
            if($iep['finalized_date'] >= $ifsp['finalized_date']) {
                // iep is the form to use
                return $this->iepEndDate($iep);
            } else {
                // ifsp is the form to use
                return $this->ifspEndDate($ifsp);
            }
        } elseif($iepValidDate) {
            return $this->iepEndDate($iep);
        } elseif($ifspValidDate) {
            return $this->ifspEndDate($ifsp);
        } else {
            return null;
        }
    }
    public function iepEndDate($iep) {
        return $iep['effect_to_date'];
    }
    public function ifspEndDate($ifsp) {
        return $this->date_massage(strtotime($ifsp['meeting_date'].'+182 days') );
    }

    function buildEntryDate($student) {

        $id_student = $student->id_student;
        $mdtData = $this->mostRecentFinalForm($student, '002');

        if(!empty($mdtData['initial_verification_date'])) return $this->date_massage(strtotime($mdtData['initial_verification_date']), 'Y-m-d');
        if(!empty($mdtData['initial_verification_date_sesis'])) return $this->date_massage(strtotime($mdtData['initial_verification_date_sesis']), 'Y-m-d');

        $mdtData = $this->mostRecentFinalForm($student, '022');
        if( !empty($mdtData['initial_verification_date'])) return $this->date_massage(strtotime($mdtData['initial_verification_date']), 'Y-m-d');
        return null;
    }

    public function spedSetting($student) {
        $setting = $this->primarySetting($student);
        return (int) $setting;
//        switch((int) $setting ) {
//            case 1:
//            case 8:
//                return 'Home';
//                break;
//            case 2:
//                return 'Community Based';
//                break;
//            case 3:
//                return 'Other';
//                break;
//            case 5:
//                return 'Seperate School';
//                break;
//            case 6:
//                return 'Separate Class';
//                break;
//            case 7:
//                return 'Residential Facility';
//                break;
//            case 9:
//                return 'Service Provider Location';
//                break;
//            case 10:
//                return 'Public School';
//                break;
//            case 13:
//                return 'Home/Hospital';
//                break;
//            case 14:
//                return 'Private School';
//                break;
//            case 15:
//                return 'Correction/Detention Facility';
//                break;
//            case 16:
//                return 'Regular Early Childhood Program, 10+ h/wk; Services at EC Program';
//                break;
//            case 17:
//                return 'Regular Early Childhood Program, 10+ h/wk; Services outside EC Program';
//                break;
//            case 18:
//                return 'Regular Early Childhood Program, <10 h/wk; Services at EC Program';
//                break;
//            case 19:
//                return 'Regular Early Childhood Program, <10 h/wk; Services outside EC Program';
//                break;
//        }
        return null;
    }
    public function getAccomodationsChecklist($student)
    {
        try {
            $form004AccObj = new Model_Table_Form004AccomodationsChecklist();
            $form004AccChecklist = $form004AccObj->fetchRow("id_student = '".$student->id_student."'");
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        if (count($form004AccChecklist)) {
            return $form004AccChecklist;
        } else {
            return null;
        }
    }

    public function accomodationsChecklist($student) {
        $accChecklist = $this->getAccomodationsChecklist($student);
        $retString = '';
        if(is_object($accChecklist)) {
            if(!empty($accChecklist['ass_adapt_worksheet'])) $retString .= $accChecklist['ass_adapt_worksheet'] . $this->delimiter;
            if(!empty($accChecklist['ass_allow_copying'])) $retString .= $accChecklist['ass_allow_copying'] . $this->delimiter;
            if(!empty($accChecklist['ass_allo_use_resource'])) $retString .= $accChecklist['ass_allo_use_resource'] . $this->delimiter;
            if(!empty($accChecklist['ass_avoide_penalizing'])) $retString .= $accChecklist['ass_avoide_penalizing'] . $this->delimiter;
            if(!empty($accChecklist['ass_give_directions'])) $retString .= $accChecklist['ass_give_directions'] . $this->delimiter;
            if(!empty($accChecklist['ass_give_oral_cues'])) $retString .= $accChecklist['ass_give_oral_cues'] . $this->delimiter;
            if(!empty($accChecklist['ass_lower_diff_level'])) $retString .= $accChecklist['ass_lower_diff_level'] . $this->delimiter;
            if(!empty($accChecklist['ass_other'])) $retString .= $accChecklist['ass_other'] . $this->delimiter;
            if(!empty($accChecklist['ass_provide_alternate'])) $retString .= $accChecklist['ass_provide_alternate'] . $this->delimiter;
            if(!empty($accChecklist['ass_provide_oral_directions'])) $retString .= $accChecklist['ass_provide_oral_directions'] . $this->delimiter;
            if(!empty($accChecklist['ass_read_directions'])) $retString .= $accChecklist['ass_read_directions'] . $this->delimiter;
            if(!empty($accChecklist['ass_record_assignment'])) $retString .= $accChecklist['ass_record_assignment'] . $this->delimiter;
            if(!empty($accChecklist['ass_redo_for_grade'])) $retString .= $accChecklist['ass_redo_for_grade'] . $this->delimiter;
            if(!empty($accChecklist['ass_reduce_paper_tasks'])) $retString .= $accChecklist['ass_reduce_paper_tasks'] . $this->delimiter;
            if(!empty($accChecklist['ass_shorten_assign'])) $retString .= $accChecklist['ass_shorten_assign'] . $this->delimiter;
            if(!empty($accChecklist['env_alter_physical_room'])) $retString .= $accChecklist['env_alter_physical_room'] . $this->delimiter;
            if(!empty($accChecklist['env_avoid_distr'])) $retString .= $accChecklist['env_avoid_distr'] . $this->delimiter;
            if(!empty($accChecklist['env_define_areas'])) $retString .= $accChecklist['env_define_areas'] . $this->delimiter;
            if(!empty($accChecklist['env_increase_distance'])) $retString .= $accChecklist['env_increase_distance'] . $this->delimiter;
            if(!empty($accChecklist['env_other'])) $retString .= $accChecklist['env_other'] . $this->delimiter;
            if(!empty($accChecklist['env_planned_seating'])) $retString .= $accChecklist['env_planned_seating'] . $this->delimiter;
            if(!empty($accChecklist['env_pref_seating'])) $retString .= $accChecklist['env_pref_seating'] . $this->delimiter;
            if(!empty($accChecklist['env_reduce_distractions'])) $retString .= $accChecklist['env_reduce_distractions'] . $this->delimiter;
            if(!empty($accChecklist['env_seat_near_role'])) $retString .= $accChecklist['env_seat_near_role'] . $this->delimiter;
            if(!empty($accChecklist['env_seat_near_teacher'])) $retString .= $accChecklist['env_seat_near_teacher'] . $this->delimiter;
            if(!empty($accChecklist['env_teach_pos_rules'])) $retString .= $accChecklist['env_teach_pos_rules'] . $this->delimiter;
            if(!empty($accChecklist['grade_attendance'])) $retString .= $accChecklist['grade_attendance'] . $this->delimiter;
            if(!empty($accChecklist['grade_commensurate_effort'])) $retString .= $accChecklist['grade_commensurate_effort'] . $this->delimiter;
            if(!empty($accChecklist['grade_graded_on_skills'])) $retString .= $accChecklist['grade_graded_on_skills'] . $this->delimiter;
            if(!empty($accChecklist['grade_modified_grading'])) $retString .= $accChecklist['grade_modified_grading'] . $this->delimiter;
            if(!empty($accChecklist['grade_oral_presentation'])) $retString .= $accChecklist['grade_oral_presentation'] . $this->delimiter;
            if(!empty($accChecklist['grade_other'])) $retString .= $accChecklist['grade_other'] . $this->delimiter;
            if(!empty($accChecklist['grade_pass_fail'])) $retString .= $accChecklist['grade_pass_fail'] . $this->delimiter;
            if(!empty($accChecklist['grade_regular_grading'])) $retString .= $accChecklist['grade_regular_grading'] . $this->delimiter;
            if(!empty($accChecklist['lessson_emph_info'])) $retString .= $accChecklist['lessson_emph_info'] . $this->delimiter;
            if(!empty($accChecklist['lessson_functional_app'])) $retString .= $accChecklist['lessson_functional_app'] . $this->delimiter;
            if(!empty($accChecklist['lessson_make_use_voc'])) $retString .= $accChecklist['lessson_make_use_voc'] . $this->delimiter;
            if(!empty($accChecklist['lessson_oral_intrepreter'])) $retString .= $accChecklist['lessson_oral_intrepreter'] . $this->delimiter;
            if(!empty($accChecklist['lessson_other'])) $retString .= $accChecklist['lessson_other'] . $this->delimiter;
            if(!empty($accChecklist['lessson_present_demo'])) $retString .= $accChecklist['lessson_present_demo'] . $this->delimiter;
            if(!empty($accChecklist['lessson_preteach_voc'])) $retString .= $accChecklist['lessson_preteach_voc'] . $this->delimiter;
            if(!empty($accChecklist['lessson_reduce_lang'])) $retString .= $accChecklist['lessson_reduce_lang'] . $this->delimiter;
            if(!empty($accChecklist['lessson_sign_lang'])) $retString .= $accChecklist['lessson_sign_lang'] . $this->delimiter;
            if(!empty($accChecklist['lessson_sm_grp_inst'])) $retString .= $accChecklist['lessson_sm_grp_inst'] . $this->delimiter;
            if(!empty($accChecklist['lessson_spec_curr'])) $retString .= $accChecklist['lessson_spec_curr'] . $this->delimiter;
            if(!empty($accChecklist['lessson_tape_lectures'])) $retString .= $accChecklist['lessson_tape_lectures'] . $this->delimiter;
            if(!empty($accChecklist['lessson_teacher_emph'])) $retString .= $accChecklist['lessson_teacher_emph'] . $this->delimiter;
            if(!empty($accChecklist['lessson_teacher_provides'])) $retString .= $accChecklist['lessson_teacher_provides'] . $this->delimiter;
            if(!empty($accChecklist['lessson_total_comm'])) $retString .= $accChecklist['lessson_total_comm'] . $this->delimiter;
            if(!empty($accChecklist['lessson_utilize_manip'])) $retString .= $accChecklist['lessson_utilize_manip'] . $this->delimiter;
            if(!empty($accChecklist['lessson_visual_sequences'])) $retString .= $accChecklist['lessson_visual_sequences'] . $this->delimiter;
            if(!empty($accChecklist['mat_arrangement'])) $retString .= $accChecklist['mat_arrangement'] . $this->delimiter;
            if(!empty($accChecklist['mat_enlarge_notes'])) $retString .= $accChecklist['mat_enlarge_notes'] . $this->delimiter;
            if(!empty($accChecklist['mat_highlighted_texts'])) $retString .= $accChecklist['mat_highlighted_texts'] . $this->delimiter;
            if(!empty($accChecklist['mat_large_print'])) $retString .= $accChecklist['mat_large_print'] . $this->delimiter;
            if(!empty($accChecklist['mat_note_taking'])) $retString .= $accChecklist['mat_note_taking'] . $this->delimiter;
            if(!empty($accChecklist['mat_other'])) $retString .= $accChecklist['mat_other'] . $this->delimiter;
            if(!empty($accChecklist['mat_special_equip'])) $retString .= $accChecklist['mat_special_equip'] . $this->delimiter;
            if(!empty($accChecklist['mat_taped_texts'])) $retString .= $accChecklist['mat_taped_texts'] . $this->delimiter;
            if(!empty($accChecklist['mat_type_handwritten'])) $retString .= $accChecklist['mat_type_handwritten'] . $this->delimiter;
            if(!empty($accChecklist['mat_use_supp_mats'])) $retString .= $accChecklist['mat_use_supp_mats'] . $this->delimiter;
            if(!empty($accChecklist['mot_allow_movement'])) $retString .= $accChecklist['mot_allow_movement'] . $this->delimiter;
            if(!empty($accChecklist['mot_concrete_reinforcement'])) $retString .= $accChecklist['mot_concrete_reinforcement'] . $this->delimiter;
            if(!empty($accChecklist['mot_increase_rewards'])) $retString .= $accChecklist['mot_increase_rewards'] . $this->delimiter;
            if(!empty($accChecklist['mot_nonverbal'])) $retString .= $accChecklist['mot_nonverbal'] . $this->delimiter;
            if(!empty($accChecklist['mot_offer_choice'])) $retString .= $accChecklist['mot_offer_choice'] . $this->delimiter;
            if(!empty($accChecklist['mot_other'])) $retString .= $accChecklist['mot_other'] . $this->delimiter;
            if(!empty($accChecklist['mot_positive_reinforcement'])) $retString .= $accChecklist['mot_positive_reinforcement'] . $this->delimiter;
            if(!empty($accChecklist['mot_use_contracts'])) $retString .= $accChecklist['mot_use_contracts'] . $this->delimiter;
            if(!empty($accChecklist['mot_use_strengths_often'])) $retString .= $accChecklist['mot_use_strengths_often'] . $this->delimiter;
            if(!empty($accChecklist['mot_verbal'])) $retString .= $accChecklist['mot_verbal'] . $this->delimiter;
            if(!empty($accChecklist['pacing_allow_breaks'])) $retString .= $accChecklist['pacing_allow_breaks'] . $this->delimiter;
            if(!empty($accChecklist['pacing_extended_time'])) $retString .= $accChecklist['pacing_extended_time'] . $this->delimiter;
            if(!empty($accChecklist['pacing_omit_assignments'])) $retString .= $accChecklist['pacing_omit_assignments'] . $this->delimiter;
            if(!empty($accChecklist['pacing_other'])) $retString .= $accChecklist['pacing_other'] . $this->delimiter;
            if(!empty($accChecklist['pacing_school_texts'])) $retString .= $accChecklist['pacing_school_texts'] . $this->delimiter;
            if(!empty($accChecklist['pacing_vary_activity'])) $retString .= $accChecklist['pacing_vary_activity'] . $this->delimiter;
            if(!empty($accChecklist['self_man_assignment_book'])) $retString .= $accChecklist['self_man_assignment_book'] . $this->delimiter;
            if(!empty($accChecklist['self_man_behavior_manage'])) $retString .= $accChecklist['self_man_behavior_manage'] . $this->delimiter;
            if(!empty($accChecklist['self_man_con_reinforcement'])) $retString .= $accChecklist['self_man_con_reinforcement'] . $this->delimiter;
            if(!empty($accChecklist['self_man_daily_schedule'])) $retString .= $accChecklist['self_man_daily_schedule'] . $this->delimiter;
            if(!empty($accChecklist['self_man_long_term_assign'])) $retString .= $accChecklist['self_man_long_term_assign'] . $this->delimiter;
            if(!empty($accChecklist['self_man_other'])) $retString .= $accChecklist['self_man_other'] . $this->delimiter;
            if(!empty($accChecklist['self_man_peer_tutoring'])) $retString .= $accChecklist['self_man_peer_tutoring'] . $this->delimiter;
            if(!empty($accChecklist['self_man_plan_general'])) $retString .= $accChecklist['self_man_plan_general'] . $this->delimiter;
            if(!empty($accChecklist['self_man_pos_reinforcement'])) $retString .= $accChecklist['self_man_pos_reinforcement'] . $this->delimiter;
            if(!empty($accChecklist['self_man_redo_assignment'])) $retString .= $accChecklist['self_man_redo_assignment'] . $this->delimiter;
            if(!empty($accChecklist['self_man_repeated_review'])) $retString .= $accChecklist['self_man_repeated_review'] . $this->delimiter;
            if(!empty($accChecklist['self_man_repeat_directions'])) $retString .= $accChecklist['self_man_repeat_directions'] . $this->delimiter;
            if(!empty($accChecklist['self_man_req_par_reinforcement'])) $retString .= $accChecklist['self_man_req_par_reinforcement'] . $this->delimiter;
            if(!empty($accChecklist['self_man_study_sheets'])) $retString .= $accChecklist['self_man_study_sheets'] . $this->delimiter;
            if(!empty($accChecklist['self_man_teach_skill_sev'])) $retString .= $accChecklist['self_man_teach_skill_sev'] . $this->delimiter;
            if(!empty($accChecklist['self_man_teach_study_skills'])) $retString .= $accChecklist['self_man_teach_study_skills'] . $this->delimiter;
            if(!empty($accChecklist['self_man_understand_review'])) $retString .= $accChecklist['self_man_understand_review'] . $this->delimiter;
            if(!empty($accChecklist['self_man_voc_files'])) $retString .= $accChecklist['self_man_voc_files'] . $this->delimiter;
            if(!empty($accChecklist['soc_coop_learning_groups'])) $retString .= $accChecklist['soc_coop_learning_groups'] . $this->delimiter;
            if(!empty($accChecklist['soc_multiple_peers'])) $retString .= $accChecklist['soc_multiple_peers'] . $this->delimiter;
            if(!empty($accChecklist['soc_other'])) $retString .= $accChecklist['soc_other'] . $this->delimiter;
            if(!empty($accChecklist['soc_peer_advocacy'])) $retString .= $accChecklist['soc_peer_advocacy'] . $this->delimiter;
            if(!empty($accChecklist['soc_perr_tutoring'])) $retString .= $accChecklist['soc_perr_tutoring'] . $this->delimiter;
            if(!empty($accChecklist['soc_shared_experience'])) $retString .= $accChecklist['soc_shared_experience'] . $this->delimiter;
            if(!empty($accChecklist['soc_social_process'])) $retString .= $accChecklist['soc_social_process'] . $this->delimiter;
            if(!empty($accChecklist['soc_structure_activities'])) $retString .= $accChecklist['soc_structure_activities'] . $this->delimiter;
            if(!empty($accChecklist['soc_teach_friendship'])) $retString .= $accChecklist['soc_teach_friendship'] . $this->delimiter;
            if(!empty($accChecklist['soc_teach_social_com'])) $retString .= $accChecklist['soc_teach_social_com'] . $this->delimiter;
            if(!empty($accChecklist['testing_allow_students'])) $retString .= $accChecklist['testing_allow_students'] . $this->delimiter;
            if(!empty($accChecklist['testing_app_settings'])) $retString .= $accChecklist['testing_app_settings'] . $this->delimiter;
            if(!empty($accChecklist['testing_check_understand'])) $retString .= $accChecklist['testing_check_understand'] . $this->delimiter;
            if(!empty($accChecklist['testing_circle_items'])) $retString .= $accChecklist['testing_circle_items'] . $this->delimiter;
            if(!empty($accChecklist['testing_color_coded'])) $retString .= $accChecklist['testing_color_coded'] . $this->delimiter;
            if(!empty($accChecklist['testing_correct_test'])) $retString .= $accChecklist['testing_correct_test'] . $this->delimiter;
            if(!empty($accChecklist['testing_divide_test'])) $retString .= $accChecklist['testing_divide_test'] . $this->delimiter;
            if(!empty($accChecklist['testing_extended_time'])) $retString .= $accChecklist['testing_extended_time'] . $this->delimiter;
            if(!empty($accChecklist['testing_flash_cards'])) $retString .= $accChecklist['testing_flash_cards'] . $this->delimiter;
            if(!empty($accChecklist['testing_mod_format'])) $retString .= $accChecklist['testing_mod_format'] . $this->delimiter;
            if(!empty($accChecklist['testing_mult_choice'])) $retString .= $accChecklist['testing_mult_choice'] . $this->delimiter;
            if(!empty($accChecklist['testing_oral'])) $retString .= $accChecklist['testing_oral'] . $this->delimiter;
            if(!empty($accChecklist['testing_other'])) $retString .= $accChecklist['testing_other'] . $this->delimiter;
            if(!empty($accChecklist['testing_para_test'])) $retString .= $accChecklist['testing_para_test'] . $this->delimiter;
            if(!empty($accChecklist['testing_prev_lang'])) $retString .= $accChecklist['testing_prev_lang'] . $this->delimiter;
            if(!empty($accChecklist['testing_provide_reminders'])) $retString .= $accChecklist['testing_provide_reminders'] . $this->delimiter;
            if(!empty($accChecklist['testing_provide_study'])) $retString .= $accChecklist['testing_provide_study'] . $this->delimiter;
            if(!empty($accChecklist['testing_provide_visual'])) $retString .= $accChecklist['testing_provide_visual'] . $this->delimiter;
            if(!empty($accChecklist['testing_read_test'])) $retString .= $accChecklist['testing_read_test'] . $this->delimiter;
            if(!empty($accChecklist['testing_reteach_material'])) $retString .= $accChecklist['testing_reteach_material'] . $this->delimiter;
            if(!empty($accChecklist['testing_retest_options'])) $retString .= $accChecklist['testing_retest_options'] . $this->delimiter;
            if(!empty($accChecklist['testing_shorten_length'])) $retString .= $accChecklist['testing_shorten_length'] . $this->delimiter;
            if(!empty($accChecklist['testing_short_ans'])) $retString .= $accChecklist['testing_short_ans'] . $this->delimiter;
            if(!empty($accChecklist['testing_sign_directions'])) $retString .= $accChecklist['testing_sign_directions'] . $this->delimiter;
            if(!empty($accChecklist['testing_sign_test'])) $retString .= $accChecklist['testing_sign_test'] . $this->delimiter;
            if(!empty($accChecklist['testing_taped'])) $retString .= $accChecklist['testing_taped'] . $this->delimiter;
            if(!empty($accChecklist['testing_test_admin'])) $retString .= $accChecklist['testing_test_admin'] . $this->delimiter;
            if(!empty($accChecklist['testing_use_more_objective'])) $retString .= $accChecklist['testing_use_more_objective'] . $this->delimiter;
            if(!empty($accChecklist['testing_word_bank'])) $retString .= $accChecklist['testing_word_bank'] . $this->delimiter;
            if(!empty($accChecklist['writing_allow_computer'])) $retString .= $accChecklist['writing_allow_computer'] . $this->delimiter;
            if(!empty($accChecklist['writing_allow_flow_chart'])) $retString .= $accChecklist['writing_allow_flow_chart'] . $this->delimiter;
            if(!empty($accChecklist['writing_dictate_ideas'])) $retString .= $accChecklist['writing_dictate_ideas'] . $this->delimiter;
            if(!empty($accChecklist['writing_grade_content'])) $retString .= $accChecklist['writing_grade_content'] . $this->delimiter;
            if(!empty($accChecklist['writing_other'])) $retString .= $accChecklist['writing_other'] . $this->delimiter;
            if(!empty($accChecklist['writing_provide_structure'])) $retString .= $accChecklist['writing_provide_structure'] . $this->delimiter;
            if(!empty($accChecklist['writing_shorten_assignment'])) $retString .= $accChecklist['writing_shorten_assignment'] . $this->delimiter;
            if(!empty($accChecklist['writing_use_tape_recorder'])) $retString .= $accChecklist['writing_use_tape_recorder'] . $this->delimiter;
            if(!empty($accChecklist['writing_visual_rep_ideas'])) $retString .= $accChecklist['writing_visual_rep_ideas'] . $this->delimiter;
            if(!empty($accChecklist['other'])) $retString .= $accChecklist['other'] . $this->delimiter;
            if(!empty($accChecklist['env_comp_tech_work'])) $retString .= $accChecklist['env_comp_tech_work'] . $this->delimiter;
            if(!empty($accChecklist['ass_provide_electronic'])) $retString .= $accChecklist['ass_provide_electronic'] . $this->delimiter;
            if(!empty($accChecklist['testing_utilize_writing_sys'])) $retString .= $accChecklist['testing_utilize_writing_sys'] . $this->delimiter;
            if(!empty($accChecklist['asstech_supp_writ_device'])) $retString .= $accChecklist['asstech_supp_writ_device'] . $this->delimiter;
            if(!empty($accChecklist['asstech_pro_writ_sw'])) $retString .= $accChecklist['asstech_pro_writ_sw'] . $this->delimiter;
            if(!empty($accChecklist['asstech_speech_gen'])) $retString .= $accChecklist['asstech_speech_gen'] . $this->delimiter;
            if(!empty($accChecklist['asstech_aug_options'])) $retString .= $accChecklist['asstech_aug_options'] . $this->delimiter;
            if(!empty($accChecklist['asstech_enlarged_print'])) $retString .= $accChecklist['asstech_enlarged_print'] . $this->delimiter;
            if(!empty($accChecklist['asstech_braille'])) $retString .= $accChecklist['asstech_braille'] . $this->delimiter;
            if(!empty($accChecklist['asstech_aud_trainer'])) $retString .= $accChecklist['asstech_aud_trainer'] . $this->delimiter;
            if(!empty($accChecklist['asstech_other'])) $retString .= $accChecklist['asstech_other'] . $this->delimiter;
            if(!empty($accChecklist['asstech_physical_access'])) $retString .= $accChecklist['asstech_physical_access'] . $this->delimiter;
            if(!empty($accChecklist['asstech_other_text'])) $retString .= $accChecklist['asstech_other_text'] . $this->delimiter;

            return $retString;
        } else {
            return null;
        }
    }
    public function date_massage($date, $format = 'Y-m-d') {
        return date($format, $date);
    }

}
