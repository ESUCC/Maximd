<?php

/**
 * require the main export factory
 */
require_once('ExportFactoryBellevue.php'); 

class BellevueExport extends ExportFactoryBellevue {

    var $delimiter = ':'; // inside of fields not the file format

    public function __construct() {
        parent::__construct();
  
        echo "\n\nBegin Export Student's\n";
        $finalLog = "\n\nBegin Exports Students...\n";
        $finalLog .= $this->dumpLog();
        // print_r($finalLog); dumped /usr/local/zend/var/apps/https/iepweb02.nebraskacloud.org/80/1.0.0_18/application
        $this->clearMetaData();clear;
     
        /**
         * get the main application AND import config files
         */
        $appConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
      //  print_r($appConfig);die(); 
        $exportConfig = new Zend_Config_Ini('Bellevue/export.ini', APPLICATION_ENV);
       // print_r($exportConfig); die(); 
        $this->exportConfig = $exportConfig;
        $this->dataSource = $exportConfig->data_source;
     //   print_r($this->dataSource);die(); /usr/local/zend/var/apps/https/iepweb02.nebraskacloud.org/80/1.0.0_18/application is what is printed
        $this->initEmail($exportConfig->email);

        /** create database connection */
        $dbConfig = $appConfig->db2;
        $db = Zend_Db::factory($dbConfig);    // returns instance of Zend_Db_Adapter class
      //  print_r($db);die(); prints what you would expect 
        Zend_Registry::set('db', $db);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);

        /**
         * logging helper
         * must be fired after pre-flight file
         */
        $emptyDataSources = $this->countEmptyDataSource();
       // print_r($emptyDataSources);die(); //returns this: SELECT "iep_student".* FROM "iep_student" WHERE (id_county = '77') AND (id_district = '0001') AND (data_source = NULL)0
        $finalLog .= "\n\nPre-export students with an empty data_source field: " . count($emptyDataSources)."\n";

        $finalLog .= $this->dumpLog();
       //  print_r($finalLog);die(); returned Pre-export students with an empty data_source field: 1
        
        $this->clearMetaData();

        /**
         * export students
         */
        
       $success = $this->exportStudents(); 
       print_r($success);
       
       //exportStudents is in ExportFactory.php
       //  print_r($success); echo "\n"; die(); This prints a 1 or a 0 to the screen all activity is passed to exportStudents in ExportFactory.php
        if($success) {
            /**
             * FTP RESULTS
             */
            $conn_id = ftp_ssl_connect($this->exportConfig->ftp->host) or die ("Cannot connect to host");
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

        //echo $finalLog;
        return true;
    }

    public function initialVerificationDate($student) {
        $mdt = $this->lastMdt($student);
     //   print_r($mdt);
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
    // Mike added this function 6-9-2016
    
    public function nssrsServiceId($student) {
        
        $num=999;
     //   $mike = $this->lastIep($student->related_service_drop);
     //  print_r($mike);
      //  print_r($iep);
    // print_r($this->buildExportLine($student)->retArr[36]);
        return $num;
    }

    public function studentStrengths ($student) {
        
        $iep = $this->lastIep($student);
    //   echo var_dump($iep);
        if(is_object($iep)) {
           // echo var_dump($iep->student_strengths);
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

        $iepTeamMembers = array(
            0 => array('sortnum'=> 1, 'positin_desc' => 'Parent', ),
            1 => array('sortnum'=> 2, 'positin_desc' => 'Student (whenever appropriate, or if the student is 16 years of age or older)', ),
            2 => array('sortnum'=> 3, 'positin_desc' => 'Regular education teacher', ),
            3 => array('sortnum'=> 4, 'positin_desc' => 'Special education teacher or provider', ),
            4 => array('sortnum'=> 5, 'positin_desc' => 'School district representative', ),
            5 => array('sortnum'=> 6, 'positin_desc' => 'Individual to interpret evaluation results', ),
            6 => array('sortnum'=> 7, 'positin_desc' => 'Service agency representative (If child is receiving services from an approved Service Agency)', ),
            7 => array('sortnum'=> 8, 'positin_desc' => 'Nonpublic representative (if student is attending a nonpublic school)', ),
            8 => array('sortnum'=> 9, 'positin_desc' => 'Other agency representative (when transition services are being provided or will be provided by another agency for children age 16 and older)', ),
            9 => array('sortnum'=> 10, 'positin_desc' => 'Speech Language Pathologist', ),
            10 => array('sortnum'=> 11, 'positin_desc' => 'Hearing Resource Teacher')
        );

        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            if(9 >= $iep->version_number) {
                $participants = $iep->findDependentRowset('Model_Table_Form004TeamMember');
                if($participants->count()) {
                    $retString = '';
                    $position = 0;
                    foreach ($participants as $p) {
                        if(''==$p->participant_name) continue;
                        if(''!=$retString) $retString .= $this->delimiter;
                        $retString .= $p->participant_name . ' / ' . $iepTeamMembers[$position];
                        $position++;
                    }
                    return $retString;
                }
            } else {
                return 'build iepParticipants for pre v9 forms';
            }
            return null;
        } else {
            return null;
        }
    }
    public function iepParticipantsOther ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            if(9 >= $iep->version_number) {
                $participants = $iep->findDependentRowset('Model_Table_Form004TeamOther');
                if($participants->count()) {
                    $retString = '';
                    foreach ($participants as $p) {
                        if(''==$p->participant_name) continue;
                        if(''!=$retString) $retString .= $this->delimiter;
                        if('Other (Please Specify)' == $p->relationship_desc) {
                            $retString .= $p->participant_name . ' / ' . $p->relationship_desc . '(' . $p->relationship_other . ')';
                        } else {
                            $retString .= $p->participant_name . ' / ' . $p->relationship_desc;
                        }
                    }
                    return $retString;
                }
            } else {
                return 'build iepParticipantsOther for pre v9 forms';
            }
            return null;
        } else {
            return null;
        }
    }
    public function iepParticipantsDistrict ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            if(9 >= $iep->version_number) {
                $participants = $iep->findDependentRowset('Model_Table_Form004TeamDistrict');
                if($participants->count()) {
                    $retString = '';
                    foreach ($participants as $p) {
                        if(''==$p->participant_name) continue;
                        if(''!=$retString) $retString .= $this->delimiter;
                        if('Other (Please Specify)' == $p->relationship_desc) {
                            $retString .= $p->participant_name . ' / ' . $p->relationship_desc . '(' . $p->relationship_other . ')';
                        } else {
                            $retString .= $p->participant_name . ' / ' . $p->relationship_desc;
                        }
                    }
                    return $retString;
                }
            } else {
                return 'build iepParticipantsDistrict for pre v9 forms';
            }
            return null;
        } else {
            return null;
        }
    }
    public function programModifications ($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            if(9 >= $iep->version_number) {
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
                return 'build for pre v9 forms';
            }
            return null;

        } else {
            return null;
        }
    }

    public function reviewDates ($student, $firstGoalOnly = true) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            if(9 >= $iep->version_number) {
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
                        if($firstGoalOnly) {
                            return $retString;
                        }
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
            if(9 >= $iep->version_number) {
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
            if(9 >= $iep->version_number) {
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
            if(9 >= $iep->version_number) {
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
            if(9 >= $iep->version_number) {
                $relatedServices = $iep->findDependentRowset('Model_Table_Form004RelatedService');
                $retString = '';
                foreach ($relatedServices as $relatedService) {
                    if('Active'!=$relatedService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $relatedService->related_service_drop;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    public function relatedServiceLocation($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 >= $iep->version_number) {
                $relatedServices = $iep->findDependentRowset('Model_Table_Form004RelatedService');
                $retString = '';
                foreach ($relatedServices as $relatedService) {
                    if('Active'!=$relatedService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $relatedService->related_service_location;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    public function relatedServiceFreqSmall($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 >= $iep->version_number) {
                $relatedServices = $iep->findDependentRowset('Model_Table_Form004RelatedService');
                $retString = '';
                foreach ($relatedServices as $relatedService) {
                    if('Active'!=$relatedService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $relatedService->related_service_tpd;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }
    public function relatedServiceFreqMed($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 >= $iep->version_number) {
                $relatedServices = $iep->findDependentRowset('Model_Table_Form004RelatedService');
                $retString = '';
                foreach ($relatedServices as $relatedService) {
                    if('Active'!=$relatedService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $relatedService->related_service_days_value;
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
     * supp
     */
    public function supplementaryService($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 >= $iep->version_number) {
                $suppServices = $iep->findDependentRowset('Model_Table_Form004SupplementalService');
                $retString = '';
                foreach ($suppServices as $suppService) {
                    if('Active'!=$suppService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $suppService->supp_service;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    public function supplementaryServiceLocation($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 >= $iep->version_number) {
                $suppServices = $iep->findDependentRowset('Model_Table_Form004SupplementalService');
                $retString = '';
                foreach ($suppServices as $suppService) {
                    if('Active'!=$suppService->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $suppService->supp_service_location;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    public function supplementaryServiceFreqSmall($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 >= $iep->version_number) {
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
            if(9 >= $iep->version_number) {
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
            if(9 >= $iep->version_number) {
                $progMods = $iep->findDependentRowset('Model_Table_Form004ProgramModifications');
                $retString = '';
                foreach ($progMods as $progMod) {
                    if('Active'!=$progMod->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $this->removeReturns($progMod->prog_mod);
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    public function progModServiceLocation($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 >= $iep->version_number) {
                $progMods = $iep->findDependentRowset('Model_Table_Form004ProgramModifications');
                $retString = '';
                foreach ($progMods as $progMod) {
                    if('Active'!=$progMod->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $progMod->prog_mod_location;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    public function progModServiceFreqSmall($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 >= $iep->version_number) {
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
            if(9 >= $iep->version_number) {
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
            if(9 >= $iep->version_number) {
                $assistTechs = $iep->findDependentRowset('Model_Table_Form004AssistiveTechnology');
                $retString = '';
                foreach ($assistTechs as $assistTech) {
                    if('Active'!=$assistTech->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $assistTech->ass_tech;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    public function assistTechServiceLocation($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 >= $iep->version_number) {
                $assistTechs = $iep->findDependentRowset('Model_Table_Form004AssistiveTechnology');
                $retString = '';
                foreach ($assistTechs as $assistTech) {
                    if('Active'!=$assistTech->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $assistTech->assist_tech_location;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    public function assistTechServiceFreqSmall($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 >= $iep->version_number) {
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
            if(9 >= $iep->version_number) {
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
            if(9 >= $iep->version_number) {
                $schoolSupports = $iep->findDependentRowset('Model_Table_Form004SchoolSupport');
                $retString = '';
                foreach ($schoolSupports as $schoolSupport) {
                    if('Active'!=$schoolSupport->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $schoolSupport->supports;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    public function schoolSupportServiceLocation($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 >= $iep->version_number) {
                $schoolSupports = $iep->findDependentRowset('Model_Table_Form004SchoolSupport');
                $retString = '';
                foreach ($schoolSupports as $schoolSupport) {
                    if('Active'!=$schoolSupport->status) continue;
                    if(''!=$retString) $retString .= $this->delimiter;
                    $retString .= $schoolSupport->school_supp_location;
                }
                return $retString;
            } else {
                return 'build for pre v9 forms';
            }
        } else {
            return null;
        }
    }

    public function schoolSupportServiceFreqSmall($student) {
        $iep = $this->lastIep($student);
        if(is_object($iep)) {
            /** don't forget to version check */
            if(9 >= $iep->version_number) {
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
            if(9 >= $iep->version_number) {
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
}
