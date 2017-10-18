<?php
class Model_SearchStudent
{
	const ADMIN = 1;
	const USER = 2;
//	const PARENT = 3;
	protected $_student;
	protected $_session; 
	protected $_cache;

    protected $overrideSelectStmt = null;
    protected $overrideFromStmt = null;
    protected $overrideWhereStmt = null;
    protected $overrideOrderLimitStmt = null;

    protected $sqlStmt = null;
    protected $fromStmt = null;
    protected $whereStmt = null;
    protected $orderLimitStmt = null;
    protected $binds = null;
    public function __construct(Model_Table_StudentTable $student, Zend_Session_Namespace $session, Zend_Cache_Core $cache)
	{
		$this->_setStudent($student);
		$this->_setSession($session);
		$this->_setCache($cache);
	}

	function writevar1($var1,$var2) {
	
	    ob_start();
	    var_dump($var1);
	    $data = ob_get_clean();
	    $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
	    $fp = fopen("/tmp/textfile.txt", "a");
	    fwrite($fp, $data2);
	    fclose($fp);
	}
	
    public function reset()
    {
        $this->overrideSelectStmt = null;
        $this->overrideFromStmt = null;
        $this->overrideWhereStmt = null;
        $this->overrideOrderLimitStmt = null;
        $this->binds = null;
    }

	public function getFormatToRender($request) {
		switch($request->getParam('format')) {
			case 'School List':
				return 'school-list.phtml';
			break;
			case 'Phonebook':
				return 'phonebook.phtml';
			break;
			case 'MDT/IEP Report':
				return 'mdt-iep-report.phtml';
			break;
			default:
				return 'school-list.phtml';
		}
	}
	

	public function searchStudent($options, $sort, $returnRaw = false)
	{
	  //  include("Writeit.php");  
	  //  writevar($options,"this is the opions array line 57 search student.php\n");
        if(!isset($options['showAll'])) $options['showAll'] ='';
     //   writevar($options['defaultCacheResult'],"this is the cache result line 60\n");
        if(!isset($options['defaultCacheResult'])) $options['defaultCacheResult'] ='';
	//Mike commented this out it was 200m
	    ini_set('memory_limit', '200M');
	    /*
	     * Check to make sure a search field was added.
	     */
	//    writevar($options['searchValue'], "this is the search value line 65 mdl \n");
	   
	    
	    if ((empty($options['searchValue']) || empty($options['searchField'])) && '1' != $options['showAll'] && '1' != $options['defaultCacheResult'])
	        return array('error' => 'You must enter at least one search field above. (101)');
	    
        $options['searchValues'] = $this->buildSearchOptions($options['searchValue'], $options['searchField']);
        
        if(empty($options['searchValues']) && '1' != $options['showAll'] && '1' != $options['defaultCacheResult'])
            return array('error' => 'You must enter at least one search field above. (201)');

        if($this->_session->parent) {
            $admin = false;
        } else {
            // get best priv
            $privCheck = new My_Classes_privCheck($this->_session->user->privs);
            $admin = 1==$privCheck->getMinPriv()?true:false;
        }

	    $binds = array();

		$sqlStmt = "SELECT s.*, s.id_student, " 
			 . " get_name_personnel(id_case_mgr) AS name_case_mgr, ";

		/*
		 * Included in all 3 formats (Search List, Phonebook, MDT/IEP Report).
		 * There appears to be an IEP Report format that is no longer included.  
		 */
		switch ($options['format']) {
		    case 'nssrs-report':
		    case 'Phonebook':
		    case 'School List':
		    case 'MDT/IEP Report':   
		    default:
        		$sqlStmt .= " get_most_recent_mdt_disability_primary(id_student) as mdt_primary_disability, " 
                     .  " get_most_recent_mdt_date_conference(id_student) as mdt_date_conference, "
        			 .  " get_most_recent_determination_notice(id_student) as det_notice_date, "
        			 .  " most_recent_final_mdt_id(id_student) as mdt_id, "
                     .  " get_most_recent_mdt_draft_id(id_student) as mdt_draft_id, "
        			 .  " rpt_draft_form_type(id_student) as draft_form_type, " 
                     .  " rpt_draft_date_created(id_student) as draft_iep_date_created, "
                     .  " rpt_draft_id(id_student) as draft_iep_id, "
        			 .  " rpt_final_form_type(id_student) as form_type, "
                     .  " rpt_final_date_created(id_student) as iep_date_conference, "
                     .  " rpt_final_date_created_duration(id_student) as iep_date_conference_duration, "
                     .  " rpt_final_id(id_student) as iep_id, "
        			 .  " mdtorform001_draft_form_type(id_student) as mdtorform001_draft_form_type, "
                     .  " mdtorform001_draft_date_created(id_student) as mdtorform001_draft_date_created, "
                     .  " mdtorform001_draft_id(id_student) as mdtorform001_draft_id, "
        			 .  " mdtorform001_final_form_type(id_student) as mdtorform001_final_form_type, "
                     .  " mdtorform001_final_date_created(id_student) as mdtorform001_final_date_created, "
                     .  " mdtorform001_final_id(id_student) as mdtorform001_final_id, ";
		        break;
		}
		
		/*
		 * Preface these fields with table alias so we don't get 
		 * an error with joined tables.  Old code had an if statment 
		 * for this but there's no reason to not go ahead and do this.
		 */
		$sqlStmt .= " get_name_county(s.id_county) as name_county, date_part('year',age(dob)) AS age, dob AS dob,"
			 .  " get_name_district(s.id_county, s.id_district) as name_district, " 
		     .  " get_name_school(s.id_county, s.id_district, s.id_school) as name_school, "
		     .  " CASE WHEN s.name_middle IS NOT NULL THEN s.name_first || ' ' || s.name_last ELSE s.name_first || ' ' || s.name_last END AS name_full, "
		     .  " s.name_last || ', ' || s.name_first as name_last_first, "
		     .  " s.address_street1 || ', ' || s.address_city || ' ' || CAST(s.address_state AS TEXT) || ', ' || CAST(s.address_zip AS TEXT) as address\n";
		
		/*
		 * Control access to students
		 * that user has access to.
		 */
        if($this->_session->parent) {
			$fromStmt = " FROM iep_student AS s ";
        } else {
			$fromStmt = " FROM my_students AS s ";
        }
		/*
		 * Add join for personnel based on search form
		*/
		if (array_key_exists('case_load_first_name', $options['searchValues']) || array_key_exists('case_load_last_name', $options['searchValues']))
			$fromStmt .= " LEFT JOIN iep_personnel p ON s.id_case_mgr=p.id_personnel ";
		
		/*
		 * Add join for student team based on search form
		*/
		if (array_key_exists('team_member_first_name', $options['searchValues']) || array_key_exists('team_member_first_name', $options['searchValues']))
			$fromStmt .= " LEFT JOIN iep_personnel p ON s.id_case_mgr=p.id_personnel LEFT JOIN iep_student_team st ON s.id_student = st.id_student LEFT JOIN iep_personnel team ON st.id_personnel = team.id_personnel ";
		
		/*
		 * Check privileges set here for ($sessUserMinPriv == UC_PG)
		 * @todo make sure id_guardian is set in session when logged in a guardian
		 */
        if($this->_session->parent) {
			$binds[] = $this->_session->id_guardian;
			$whereStmt = " WHERE s.id_student IN (SELECT id_student FROM iep_guardian WHERE id_guardian = ?) ";
		} else { 
			$binds[] = $this->_session->id_personnel;
			$whereStmt = " WHERE s.id_personnel = ? ";
		}
		
		/*
		 * Limit the results to the users case load. This is defined
		 * by checking to see if the sessionId of the user is 1017886
		 * If it is that user the HTML for Search Limits will show up
		 * which gives a form element for limiting case load.
		 */
		if (!empty($options['search_limit_caseload'])) {
			if (1 == $options['search_limit_caseload']) {
				$binds[] = $this->_session->sessIdUser;
				$whereStmt .= " AND (id_ei_case_mgr = ? OR id_list_team ilike '%?%')";
			}
		}
		
		/*
		 * bunch of stuff to exclude based on some variables
		 * I think all of these will be custom URL's to the search page
		 */
		if("reports" == $options['controller'] && "transportation" == $options['action'] && "Qualified for Transport" == $options['tranStatus']) {
			$whereStmt .= " and 't' = (select transportation_yn from iep_form_004 where id_form_004 = (select most_recent_final_iep_id(s.id_student))) ";
		} elseif("reports" == $options['controller'] && "transportation" == $options['action'] && "Did Not Qualify" == $options['tranStatus']) {
			$whereStmt .= " and 'f' = (select transportation_yn from iep_form_004 where id_form_004 = (select most_recent_final_iep_id(s.id_student))) ";
		} elseif("student" == $options['controller'] && "search" == $options['action'] && "No NSSRS ID#" == $options['searchOther']) {
			$whereStmt .= " and unique_id_state is null ";
		} elseif("reports" == $options['controller'] && "nssrs" == $options['action'] && "No NSSRS ID#" ==$options['searchOther']) {
			$whereStmt .= " and unique_id_state is null ";
		} elseif("reports" == $options['controller'] && "nssrs_transfers" == $options['action'] && "No NSSRS ID#" == $options['searchOther']) {
			$whereStmt .= " and unique_id_state is null ";
		} elseif("reports" == $options['controller'] && "nssrs" == $options['action'] && "excluded" == $options['format']) {
			$whereStmt .= " and exclude_from_nssrs_report = true ";
		}
		
		/*
		 * Limit the results to search status
		 */
		if (isset($options['searchStatus']) && 'All' !== $options['searchStatus']) {
			$binds[] = $options['searchStatus'];
		    $whereStmt .= "AND s.status = ? ";
		}

		/*
		 * Filter the results to search status
		 */
		if (isset($options['searchFilter'])) {
			switch ($options['searchFilter']) {
				case 'Missing NSSRS#':
					$whereStmt .= "AND s.unique_id_state IS NULL";
					break;
				case 'CM Only':
					$whereStmt .= "AND s.id_case_mgr IS NOT NULL";
					break;
				case 'Team Member Only':
					$whereStmt .= "AND (SELECT COUNT(*) FROM iep_student_team AS sts WHERE sts.id_student = s.id_student AND sts.status = 'Active') > 0 ";
					break;
				case 'None':
				default:
					break;
			}
		}
		
		/*
		 * Search on fields if min priv is not PG
		 */
        if(false==$this->_session->parent) {
		    $counter = 1;
		  //  $this->writevar1($options,'these are the options');
		    
			foreach($options['searchValues'] AS $key => $value) {
			    $value = empty($value) ? "NULL" : strtolower($value);
			   // var_dump();
			    if ( $counter > 1 ) {
			        $conditional = " AND ";
			    } else {
			        $conditional = " AND ";
			    }
			    
				switch ($key) {
					case 'pub_school_student':
						if ($value == "T" || $value == "t" || $value == "true") {
                            $whereStmt .= " $conditional ";
                            $whereStmt .= " pub_school_student = 'TRUE'\n";
                        } elseif($value == "F" || $value == "f" || $value == "false") {
                            $whereStmt .= " $conditional ";
                            $whereStmt .= " pub_school_student = 'FALSE'\n";
                        }
						break;
					case 'onteam':
					    $whereStmt .= " $conditional ";
					    $binds[] = $value;
                        $whereStmt .= " id_student IN (SELECT id_student FROM iep_student_team WHERE id_personnel = ? AND status = 'Active') ";
						break;
					case 'primaryOrRelatedService':
					    $whereStmt .= " $conditional ";
                        $binds[] = $value;
                        $whereStmt .= " ( id_student IN (SELECT id_student FROM iep_form_004 where lower(primary_disability_drop) = ?) OR ";
                        $binds[] = $value;
                        $whereStmt .= " id_student IN (SELECT id_student FROM form_004_related_service where lower(related_service_drop) = ?) ) ";
						break;
					case 'isCM':
						$whereStmt .= " $conditional "; 
						$binds[] = $value;
                        $whereStmt .= " id_case_mgr = ? ";
						break;
					case 'isSC':
						$whereStmt .= " $conditional ";
						$binds[] = $value;
                        $whereStmt .= " id_ser_cord = ? ";
						break;
					case 'isECIM':
						$whereStmt .= " $conditional ";
						$binds[] = $value;
                        $whereStmt .= " id_ei_case_mgr = ? ";
						break;
					case 'id_student':
					case 'ss.id_student':
						$whereStmt .= " $conditional ";
                        if ( substr_count($value, ",") > 0 ) {
                            $whereStmt .= " id_student in ($value) \n";
                        }  else {
                            $binds[] = $value;
                            $whereStmt .= " id_student = ? \n";
                        }
						break;
					case 's.grade':
						$whereStmt .= " $conditional ";
                        if ( substr_count($value, ",") > 0 ) {
                            $whereStmt .= " $key in ($value) \n";

                        }  elseif('ei' == $value || 'ei 0-2' == $value) {
                            $whereStmt .= " ($key ilike 'ei 0-2') \n";

                        }  elseif('ecse' == $value) {
                            $binds[] = $value;
                            $whereStmt .= " $key ilike ? \n";

                        }  elseif('k' == $value) {
                            $binds[] = $value;
                            $whereStmt .= " $key ilike ? \n";

                        }  else {
                            $binds[] = $value;
                            $whereStmt .= " $key = ? \n";
                        }
						break;
					case 's.gradegreaterthan':
						$whereStmt .= " $conditional ";

                        if('ei' == $value || 'ei 0-2' == $value) {
                            $binds[] = $value;
                            $whereStmt .= "(";
                            $whereStmt .= " s.grade ilike 'ecse' OR \n";
                            $whereStmt .= " s.grade = 'K' OR \n";
                            $whereStmt .= " s.grade > ? \n";
                            $whereStmt .= ") ";

                        }  elseif('ecse' == $value) {
                            $binds[] = $value;
                            $whereStmt .= "(";
                            $whereStmt .= " s.grade = 'K' OR \n";
                            $whereStmt .= " s.grade > ? \n";
                            $whereStmt .= ") ";

                        }  elseif('K' == $value) {
                            $binds[] = $value;
                            $whereStmt .= "(";
                            $whereStmt .= " s.grade > ? \n";
                            $whereStmt .= ") ";

                        }  else {
                            $binds[] = $value;
                            $whereStmt .= "(";
                            $whereStmt .= " grade_to_integer(s.grade) > grade_to_integer(?) \n";
                            $whereStmt .= ") ";
                        }
						break;
					case 's.gradelessthan':
						$whereStmt .= " $conditional ";
                        // Updating this conditional for SRSSUPP-388
                        if('ei' == $value || 'ei 0-2' == $value) {
                            $whereStmt .= " s.grade ilike 'ei 0-2' \n";
    
                        }  elseif('ecse' == $value) {
                            $whereStmt .= "(";
                            $whereStmt .= " s.grade ilike 'ei 0-2' \n";
                            $whereStmt .= ") ";
    
                        }  elseif('K' == $value) {
                            $whereStmt .= "(";
                            $whereStmt .= " s.grade ilike 'ecse' OR \n";
                            $whereStmt .= " s.grade ilike 'ei 0-2' \n";
                            $whereStmt .= ") ";
    
                        }  else {
                            $binds[] = $value;
                            $whereStmt .= "(";
                            $whereStmt .= " grade_to_integer(s.grade) < grade_to_integer(?) \n";
                            $whereStmt .= ") ";
                        }
						break;

					case 'case_load_first_name':
					    $whereStmt .= " AND ";
					    $binds[] = $value;
					    $whereStmt .= " (p.name_first ilike ?) ";
					    break;
					case 'case_load_last_name':
						$whereStmt .= " AND ";
						$binds[] = $value;
                        $whereStmt .= " (p.name_last ilike ?) ";
						break;
					case 'team_member_last_name':
					    $whereStmt .= " AND ";
					    $binds[] = $value;
					    $whereStmt .= " (team.name_last ilike ?) ";
					    break;
					case 'team_member_first_name':
						$whereStmt .= " AND ";
						$binds[] = $value;
                        $whereStmt .= " (team.name_first ilike ?) ";
						break;
					case 'ward':
                        $whereStmt .= " $conditional ";
                        if ($value == "T" || $value == "t" || $value == "true" || $value == "Y" || $value == "y" || $value == "Yes" || $value == "yes") {
                            $whereStmt .= " ward = 't'\n";
                        } elseif($value == "F" || $value == "f" || $value == "false" || $value == "N" || $value == "n" || $value == "No" || $value == "no") {
                            $whereStmt .= " ward = 'f'\n";
                        }
                        break;
					case 'ell_student':
                        $whereStmt .= " $conditional ";
                        if ($value == "T" || $value == "t" || $value == "true" || $value == "Y" || $value == "y" || $value == "Yes" || $value == "yes") {
                            $whereStmt .= " ell_student = 't'\n";
                        } elseif($value == "F" || $value == "f" || $value == "false" || $value == "N" || $value == "n" || $value == "No" || $value == "no") {
                            $whereStmt .= " ell_student = 'f'\n";
                        }
                        break;
                      //  $this->writevar1('this is the key value pair',$key." ".$value);
                                    case 's.alternate_assessment':
                        $whereStmt .= " $conditional ";
                        if ($value == "T" || $value == "t" || $value == "true" || $value == "Y" || $value == "y" || $value == "Yes" || $value == "yes") {
                            $whereStmt .= " alternate_assessment = 't'\n";
                        } elseif($value == "F" || $value == "f" || $value == "false" || $value == "N" || $value == "n" || $value == "No" || $value == "no") {
                            $whereStmt .= " alternate_assessment = 'f'\n";
                        }
                        break;                              


					default:
						// scrub ss values to s
						if('ss.' == substr($key, 0, 3)) {
							$sf = str_replace('ss.', 's.', $key);
						} elseif('cm.name_first' == $key) {
							$sf = 'case_mgr_name_first';
						} elseif('cm.name_last' == $key) {
							$sf = 'case_mgr_name_last';
						} else {
							$sf = $key;
						}
						$whereStmt .= " AND ";
						/*
						 * use exact match for numeric search 
						 */
						if (is_numeric($value)) {
							$whereStmt .= " $sf = ? ";
							$binds[] = $value;
						} else {
							$whereStmt .= " lower($sf) LIKE ? ";
							$binds[] = $value.'%';
						}
						break;
				}
				$counter++;
			}
		}

        // build orderLimit stmt
		if (!empty($sort)) {
		    $orderLimitStmt = " ORDER BY ";
    		switch ($sort['field']) {
    		    case 'name_full':
    		        $orderLimitStmt .= "lower(name_last) {$sort['direction']}, lower(name_first) {$sort['direction']}";
    		        break;
    		    case 'mdt':
    		        $orderLimitStmt .= 'mdtorform001_final_date_created ' . $sort['direction'];
    		        break;
    		    case 'iep':
    		        $orderLimitStmt .= 'rpt_date_sort(id_student) ' . $sort['direction'];
    		        break;
    		    case 'manager':
    		        $orderLimitStmt .= 'name_case_mgr ' . $sort['direction'];
    		        break;
    		    default:
    		        $orderLimitStmt .= $sort['field'] . ' ' . $sort['direction'];
    		        break;
    		}
    		$orderLimitStmt .= ", s.name_last";
		} else {
		    $orderLimitStmt = " ORDER BY s.name_last ";
		}
		     
		
		/*
		 * Limit all result sets to 2500
		 */
		$orderLimitStmt .= " LIMIT 2500;\n";
// Mike changed this 3-16-2016: Figure 200 should be good enough
        // apply overrides
     //   writexport($this->overrideSelectStmt,"this is the overide select statement from line 465 SearchStudent 465 \n");
        if(!is_null($this->getOverrideSelectStmt())) {
            $sqlStmt = $this->getOverrideSelectStmt();
        }
        if(!is_null($this->getOverrideFromStmt())) {
            $fromStmt = ' ' . $this->getOverrideFromStmt();
        }
        if(!is_null($this->getOverrideWhereStmt())) {
            $whereStmt = ' ' . $this->getOverrideWhereStmt();
        }
        if(!is_null($this->getOverrideOrderLimitStmt())) {
            $orderLimitStmt = ' ' . $this->getOverrideOrderLimitStmt();
        }
        if(!is_null($this->getBinds())) {
            $binds = $this->getBinds();
            $this->setBinds($binds);
        }

        $this->setSqlStmt($sqlStmt);
        $this->setFromStmt($fromStmt);
        $this->setWhereStmt($whereStmt);
        $this->setOrderLimitStmt($orderLimitStmt);

        /*
         * Check to see if there is a cache key set
         */
		if (empty($options['key'])) {
		    $key = Model_CacheManager::generateCacheKey();
        } else {
		    $key = $options['key'];
        }
		$this->_session->user->searchCacheKey = $key;
		$this->_session->user->searchPage = $options['page'];

        if($returnRaw) {
            return $this->_student->getAdapter()->fetchAll($sqlStmt . $fromStmt . $whereStmt . $orderLimitStmt, $binds);
        } else {
            if (!Model_CacheManager::isCached(Zend_Registry::get('searchCache'), $key)) {
                $toCache = $this->_student
                    ->getAdapter()
                    ->fetchAll($sqlStmt . $fromStmt . $whereStmt . $orderLimitStmt, $binds);
            } else {
                $toCache = false;
            }
            Zend_Paginator::setCache(Zend_Registry::get('searchCache'));
            $maxResultsExceeded = false;
            if (($count = count($cacheResults = Model_CacheManager::getCacheForKey(Zend_Registry::get('searchCache'), $key, $toCache))) >= 2500)
                $maxResultsExceeded = true;
// Mike put in 250 from 2500 in line 508 
            return array(
                $maxResultsExceeded,
                $paginator = Zend_Paginator::factory($cacheResults)->setItemCountPerPage($options['maxRecs'])
                    ->setCurrentPageNumber(empty($options['page']) ? 1 : $options['page']),
                $key,
                $count
            );
        }
	}
	
	public function getSortStatus($request) {
	    
	}
	
	protected function buildSearchOptions($searchValues, $searchFields) {
	    $retArray = array();
	    foreach ($searchFields AS $key => $value) 
	        if (strlen($searchValues[$key]) > 0 && strlen($searchFields[$key]) > 0)
	    	    $retArray[$searchFields[$key]] = $searchValues[$key];
	    return $retArray;
	}
	
	protected function _setStudent(Model_Table_StudentTable $student)
	{
	    $this->_student = $student;
	}
	
	protected function _setSession(Zend_Session_Namespace $session)
	{
	    $this->_session = $session;
	}
	
	protected function _setCache(Zend_Cache_Core $cache)
	{
	    $this->_cache = $cache;
	}

    /**
     * @return mixed
     */
    public function getOverrideSelectStmt()
    {
        return $this->overrideSelectStmt;
    }

    /**
     * @param mixed $overrideSelectStmt
     */
    public function setOverrideSelectStmt($overrideSelectStmt)
    {
        $this->overrideSelectStmt = $overrideSelectStmt;
    }

    /**
     * @return mixed
     */
    public function getOverrideFromStmt()
    {
        return $this->overrideFromStmt;
    }

    /**
     * @param mixed $overrideFromStmt
     */
    public function setOverrideFromStmt($overrideFromStmt)
    {
        $this->overrideFromStmt = $overrideFromStmt;
    }

    /**
     * @return mixed
     */
    public function getOverrideWhereStmt()
    {
        return $this->overrideWhereStmt;
    }

    /**
     * @param mixed $overrideWhereStmt
     */
    public function setOverrideWhereStmt($overrideWhereStmt)
    {
        $this->overrideWhereStmt = $overrideWhereStmt;
    }

    /**
     * @return mixed
     */
    public function getOverrideOrderLimitStmt()
    {
        return $this->overrideOrderLimitStmt;
    }

    /**
     * @param mixed $overrideOrderLimitStmt
     */
    public function setOverrideOrderLimitStmt($overrideOrderLimitStmt)
    {
        $this->overrideOrderLimitStmt = $overrideOrderLimitStmt;
    }

    /**
     * @return mixed
     */
    public function getBinds()
    {
        return $this->binds;
    }

    /**
     * @param mixed $binds
     */
    public function setBinds($binds)
    {
        $this->binds = $binds;
    }

    /**
     * @return null
     */
    public function getSqlStmt()
    {
        return $this->sqlStmt;
    }

    /**
     * @param null $sqlStmt
     */
    public function setSqlStmt($sqlStmt)
    {
        $this->sqlStmt = $sqlStmt;
    }

    /**
     * @return null
     */
    public function getFromStmt()
    {
        return $this->fromStmt;
    }

    /**
     * @param null $fromStmt
     */
    public function setFromStmt($fromStmt)
    {
        $this->fromStmt = $fromStmt;
    }

    /**
     * @return null
     */
    public function getWhereStmt()
    {
        return $this->whereStmt;
    }

    /**
     * @param null $whereStmt
     */
    public function setWhereStmt($whereStmt)
    {
        $this->whereStmt = $whereStmt;
    }

    /**
     * @return null
     */
    public function getOrderLimitStmt()
    {
        return $this->orderLimitStmt;
    }

    /**
     * @param null $orderLimitStmt
     */
    public function setOrderLimitStmt($orderLimitStmt)
    {
        $this->orderLimitStmt = $orderLimitStmt;
    }


}
