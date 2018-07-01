<?php
/**
 * Base search controller
/ * @author sbennett
 *
 */

class My_Base_SearchController extends App_Zend_Controller_Action_Abstract 
{
    
    protected $_searchTypesModel;
	protected $_searchFieldsModel;
	protected $_searchFormatsModel;
	protected $_searchColumnsModel;
	protected $_searchFormatColumnsModel;
	protected $_savedSearchModel; 
	protected $_searchForm;
	protected $_user;
	
	public function init() {
	    
		$this->_user = new Zend_Session_Namespace('user');

		//writevar($this->_user,'this is the user namespace');
		/*
		 * Don't run default stuff unless its not an AJAX request
		 */
		if (!$this->getRequest()->isXmlHttpRequest() &&
			!in_array($this->_request->getActionName(), 
					array('print-results','export-result-list-to-csv'))) {
			$this->view->hideLeftBar = true;
			$this->view->headLink()->appendStylesheet('/css/default_search.css');
			$this->view->headScript()->appendFile('/js/default-search.js');
			$this->buildSearchFormForAction($this->getRequest()->getActionName());
			$this->renderScript('search/default.phtml');
		} else {
			$this->_helper->layout()->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
		}
	}
	
	public function printResultsAction() {
	
		$searchCache = Zend_Registry::get('searchCache');
	
		try {
			$results = $searchCache->load($this->getRequest()->getParam('id'));
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		$searchTypes = new Model_Table_SearchTypes();
		$searchTypes->setRowForTypeId($this->_request->getParam('type_id'));
		$this->view->type = $searchTypes->getDisplayName();
		
		$fileName = $this->getRequest()->getParam('id');
		for($i=0;$i<6;$i++) {
			$fieldName = 'formatColumn'.$i;
			$formatFields[] = $this->getRequest()->getParam($fieldName);
		}
		
		$searchColumns = new Model_Table_SearchColumns();
		$this->view->searchColumns = $searchColumns->getSearchColumnsForTypeId(
				$this->_request->getParam('type_id')
		);
		$this->view->searchColumnCSSIds = $searchColumns->getSearchColumnCSSIdsForTypeId(
				$this->_request->getParam('type_id')
		);
	
		$this->view->results = $results;
		$this->view->formatFields = $formatFields;
		$this->renderScript('search/print-search-table.phtml');
	}
	
	public function exportResultListToCsvAction() {
	
		$searchCache = Zend_Registry::get('searchCache');
	
		try {
			$results = $searchCache->load($this->getRequest()->getParam('id'));
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		$searchTypes = new Model_Table_SearchTypes();
		$searchTypes->setRowForTypeId($this->_request->getParam('type_id'));
		$this->view->type = $searchTypes->getDisplayName();
	
		$fileName = $this->getRequest()->getParam('id');
		for($i=0;$i<6;$i++) {
			$fieldName = 'formatColumn'.$i;
			$formatFields[] = $this->getRequest()->getParam($fieldName);
		}
		
		$searchColumns = new Model_Table_SearchColumns();
		$this->view->searchColumns = $searchColumns->getSearchColumnsForTypeId(
				$this->_request->getParam('type_id')
		);
		$this->view->searchColumnCSSIds = $searchColumns->getSearchColumnCSSIdsForTypeId(
				$this->_request->getParam('type_id')
		);
	
		header("Cache-Control: must-revalidate, " .
				"post-check=0, pre-check=0");
		header("Pragma: hack");
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename={$fileName}-" .
		@date("mdY") . ".csv");
	
		$fp = fopen('php://output', 'w');
	
		$heading = array();
		foreach ($formatFields AS $field) {
			if (!empty($field)) {
				switch ($field) {
					default:
						$heading[] = $this->view->searchColumns[$field];
						break;
				}
			}
		}
		fputcsv($fp, $heading);
	
		$row = array();
		foreach ($results AS $result) {
			foreach ($formatFields AS $field) {
				if (!empty($field)) {
					switch ($field) {
						case 'role':
							$row[] = $this->view->UserRole($result['id_student']);
							break;
						case 'iep':
							$iepTimeAddition = "-1 day +1 year";
							$ifspTimeAddition = "-1 day +182 days";
							$iep_due_date = '';
	
							// Dupe IEP link
							if(!empty($result['iep_id']) && "form_004" == $result['form_type']) {
								// build display field for the date
								$iep_due_date = date("m/d/y", strtotime($result['iep_date_conference'] . $iepTimeAddition));
							} elseif(isset($result['iep_id']) && "form_013" == $result['form_type']) {
								// add astrik if ifsp
								$iep_due_date = date("m/d/y", strtotime($result['iep_date_conference'] . $ifspTimeAddition))."*";
							}
							$row[] = $iep_due_date;
							break;
						case 'mdt':
							$mdtTimeAddition = "+3 years -1 day";
							$det_noticeTimeAddition = "+3 years -1 day";
							$mdt_due_date = '';
	
							if(!empty($result['mdtorform001_final_id']) && "form_002" == $result['mdtorform001_final_form_type']) {
								$mdt_due_date = date("m/d/y", strtotime($result['mdtorform001_final_date_created'] . $mdtTimeAddition));
							} elseif(isset($result['mdtorform001_final_id']) && "form_012" == $result['mdtorform001_final_form_type']) {
								$mdt_due_date = date("m/d/y", strtotime($result['mdtorform001_final_date_created'] . $det_noticeTimeAddition))."*";
							}
							$row[] = $mdt_due_date;
							break;
						default:
							$row[] = $result[$field];
							break;
					}
				}
			}
			fputcsv($fp, $row);
			$row = array();
		}
	
		fclose($fp);
		exit;
	}
	
	public function runSearchAction() {
		include("Writeit.php");
		$this->view->page = $this->getRequest()->getParam('page');
	 //   writevar($this->view->page,'this is the whole page view');  // THIS RETURNS THE NUMBER 1
		for($i=0;$i<6;$i++) {
			$fieldName = 'formatColumn'.$i;
			$formatFields[] = $this->_request->getParam($fieldName);
		//	writevar($formatFields,'this is the format fields array');  //This loops 5 times getting the field associative array name only for the field heading.
		/*
		 *this is the format fields array and what it looks like after the 5th iteration
array(6) {
  [0]=>
  string(15) "name_last_first"
  [1]=>
  string(12) "id_personnel"
  [2]=>
  string(7) "address"
  [3]=>
  string(10) "phone_work"
  [4]=>
  string(13) "email_address"
  [5]=>
  string(0) ""
}
 
		 */
		
		}
		$this->view->formatFields = $formatFields;
		
		$searchColumns = new Model_Table_SearchColumns();
		$this->view->searchColumns = $searchColumns->getSearchColumnsForTypeId(
				$this->_request->getParam('type_id'));
	// writevar($this->view->searchColumns,'this is the search columns');
	
		$this->view->searchColumnCSSIds = $searchColumns->getSearchColumnCSSIdsForTypeId(
				$this->_request->getParam('type_id'));
		// writevar($this->view->searchColumnCSSIds,'this is the searchColumnCSSIDS');
		
		
		/*
		 * Setup field sorting.
		*/
		$sort = array();
		foreach ($formatFields AS $field) {
		   // writevar($field,'this is the for in the field');
		    // if once clicks on the field heading then it will sort based on what is passwd for the field heading
			if (!empty($field)) {
				if (0 === strpos($this->getRequest()->getParam('sort'), $field)) {
					if (strpos($this->getRequest()->getParam('sort'), 'desc') > 0) {
						$this->view->{$field.'Sort'} = $field.'Sort';
						$sort['field'] = $field;
						$sort['direction'] = 'DESC';
					} elseif (strpos($this->getRequest()->getParam('sort'), 'asc') > 0) {
						$this->view->{$field.'Sort'} = $field.'Sort-desc';
						$sort['field'] = $field;
						$sort['direction'] = 'ASC';
					} else
						$this->view->{$field.'Sort'} = $field.'Sort-asc';
				} else {
					$this->view->{$field.'Sort'} = $field.'Sort-asc';
				}
			}
		}
		
		/*
		 * Set the model to the name of the search action and 
		 * run the default search function as well as the search 
		 * model function.
		 */
		$searchTypes = new Model_Table_SearchTypes();
		$searchTypes->setRowForTypeId($this->_request->getParam('type_id'));  
		$searchModelName = 'Model_Search'.ucwords($searchTypes->getDisplayName());
		 writevar($searchModelName,'this is the variable for controller action'); // it will return just Model_SearchPersonnel or Model_SearchStudents
		$search = new Model_DefaultSearch(
				new $searchModelName,  
				new Zend_Session_Namespace('user'),
				Zend_Registry::get('searchCache')
		);
	//	writevar($searchTypes->getDisplayName(),'this is the search types display name'); // this returns "studdent" when we hit show all on the student page
		$this->view->type = $searchTypes->getDisplayName(); //this comes from a function in Models_Tables_SearchTypes  Veery small table in the databas
		
		
		//writevar($this->view->type,'this is the what type is being returned to the view');
	    // returns it only upon hitting the search or Show Allm
		/*
		 * Check to see if we generated any errors and set view variables. 
		 */
		if (!array_key_exists('error', ($searchResults = $search->search($this->getRequest()->getParams(), $sort)))) {
			$this->view->rpp = $this->_request->getParam('maxRecs');
			$this->view->maxResultsExceeded = $searchResults[0];
			$this->view->paginator = $searchResults[1];
			$this->view->key = $searchResults[2];
			$this->view->resultCount = $searchResults[3];
			$this->view->showAll = $this->_request->getParam('showAll');
			$this->view->defaultCacheResult = $this->getRequest()->getParam('defaultCacheResult');
		   
			
		    $viewScript = 'search/default-search-table.phtml';
		} else {
			$this->view->error = $searchResults['error'];
			$viewScript = 'search/default-search-error.phtml';
		}
		
	    echo Zend_Json::encode(
				array('result' => $this->view->render($viewScript)));
	 //    $J=  Zend_Json::encode(
		//		array('result' => $this->view->render($viewScript)));
	     
	 //   writevar($viewScript,'this is the viewScript vari');  // THIS RETURNS "search/default-search-table.phtml"
	 //  writevar($J,' this is the viewscript var json encoded'); 
	}
	
	/**
	 * Ajax call for adding a new user format.
	 */
	public function addNewUserSearchFormatAction() {
		
		$searchFormats = new Model_Table_SearchFormats();
        echo Zend_Json::encode(
            $searchFormats->addNewUserSearchFormat(
                $this->_user->sessIdUser,
            	$this->_request->getParam('type_id'),
                $this->_request->getParam('format')
            )
        );
        exit;
	}
	
	/**
	 * Ajax call for returning the columns in a format.
	 */
	public function getFormatColumnsAction() {
	
		$searchFormatColumns = new Model_Table_SearchFormatColumns();
		echo Zend_Json::encode(
			$searchFormatColumns->getColumnsForFormat(
					$this->_request->getParam('format_id')
			)
		);
		exit;
	}
	
	/**
	 * Ajax call for updating user defined columns.
	 */
	public function updateColumnForFormatAction()
	{
		$searchColumns = new Model_Table_SearchColumns();
		$columnId = $searchColumns->getColumnIdFromTypeAndValue(
				$this->_request->getParam('type_id'),
				$this->_request->getParam('columnValue')
		);
		$searchFormatColumns = new Model_Table_SearchFormatColumns();
		$searchFormatColumns->updateColumnForFormat(
				$this->_request->getParam('format_id'),
				$columnId,
				substr($this->getRequest()->getParam('column'), -1, 1)
		);
		echo Zend_Json::encode(array('success' => 1));
		exit;
	}
	
	/**
	 * Build out the search form for a given action.    
	 * @param string $method
	 */
	protected function buildSearchFormForAction($method) {
		/*
		 * Set all of our Models
		 */
	    include("Writeit.php");
		$this->setSearchTypesModel(new Model_Table_SearchTypes());
		$this->setSearchFieldsModel(new Model_Table_SearchFields());
		$this->setSearchFormatsModel(new Model_Table_SearchFormats());
		$this->setSearchColumnsModel(new Model_Table_SearchColumns());
		$this->setSearchFormatColumnsModel(
				new Model_Table_SearchFormatColumns()
		);
		// writevar($method,'this is the method passed into the buildsearchformaction');
		// pass in student or personnel from the helper/navigation tab main
		/*
		 * Check to see if the search type needs to be built.
		 */
		$searchDefinition = My_SearchHelper::getDefenitionForSearchType($method);
	//	writevar($searchDefinition,'this is the search definition');  // This setsup that gawd awfull sql query to passz to the model somewhere
		$this->_searchTypesModel->setRowForType($method);
		if (!$this->_searchTypesModel->isSearchType()) {
			$this->buildSearchType(
				$method,
				My_SearchHelper::getDefenitionForSearchType($method)
			);
		}
		
		/*
		 * Setup the default search form.
		 */
		$searchTypeId = $this->_searchTypesModel->getRowId();
		$this->_searchForm = new Form_DefaultSearch(
				$this->_searchFormatsModel
				     ->getSearchFormatsForTypeId($searchTypeId),
				$this->_searchColumnsModel
				     ->getSearchColumnsForTypeId($searchTypeId),
				$this->_searchFieldsModel
				     ->getSearchFieldsForTypeId($searchTypeId)
		);
		$this->_searchForm->type_id->setValue($searchTypeId);
		$this->_searchForm->format->addMultiOptions(
				$this->_searchFormatsModel
				     ->getSearchFormatsForTypeIdAndUser(
				     		$searchTypeId,
				     		$this->_user->sessIdUser
				)
		);
		$this->_searchForm->format->addMultiOptions(
				array(
						'custom' => '(Create Custom Format)'
				)
		);
		/*
		 * Add the active select if its a student search. 
		 */
		if ($method == 'student') {
			$this->_searchForm->addActiveOption();
		}
		/*
		 * Grab either the default search format id or 
		 * the format id used on the last user search.
		 */
		if (!empty($this->_user->user->searchCacheKey[$searchTypeId])) {
			if (Model_CacheManager::isCached(
					Zend_Registry::get('searchCache'), 
					$this->_user->user->searchCacheKey[$searchTypeId]
			)) {
				$this->setSavedSearchModel(new Model_Table_SavedSearch());
				$this->_savedSearchModel->setRow(
						$this->_user->sessIdUser,
						$searchTypeId,
						$this->_user->user->searchCacheKey[$searchTypeId]
				);
				
				$this->_searchForm->maxRecs->setValue(
						$this->_savedSearchModel->getRpp()
				);
				$formatId = $this->_savedSearchModel->getLastSearchFormat();
				
				if ($formatId !== false) {
					$savedFields = new Model_Table_SavedFields();
					$fields = $savedFields->getSavedFields(
							$this->_savedSearchModel->getLastSearchId()
					);
					
					$this->view->populateSearchFields = $fields;
					
					$this->view->cacheKey = $this->_user->user->searchCacheKey[$searchTypeId];
					$this->view->headScript()->appendFile('/js/defaultSearchToKey.js');
				} else {
					$this->resetToDefaultSearch($searchTypeId);
				}	
			} else {
				$this->resetToDefaultSearch($searchTypeId);
			}
		} else {
			$this->resetToDefaultSearch($searchTypeId);
		}
		
		if (empty($formatId)) {
			$formatId = $this->_searchFormatsModel
			->getDefaultSearchFormatForTypeId(
					$searchTypeId
			);
		}
		$this->_searchForm->format->setValue($formatId);
		$this->_searchForm->defaultFormatColumns(
				$this->_searchFormatColumnsModel
				     ->getColumnsForFormat($formatId)	
		);
		
		$this->view->searchForm = $this->_searchForm;
		$this->view->formTitle = $this->_searchTypesModel
									  ->getDisplayName();
	}
	
	/**
	 * Reset the search to default all
	 */
	private function resetToDefaultSearch($searchTypeId) {
		if (!empty($this->_user->user->searchCacheKey[$searchTypeId])) {
			unset($this->_user->user->searchCacheKey[$searchTypeId]);
		}
		
		/*
		 * Don't default to show all for admin
		*/
		$privCheck = new My_Classes_privCheck($this->_user->user->privs);
		if (2 != $privCheck->getMinPriv() && 1 != $privCheck->getMinPriv()) {
			$this->view->headScript()->appendFile('/js/defaultSearchAll.js');
		}
	}
	
	/**
	 * Build the definition into the database.
	 * @param mixed $searchDefinition
	 */
	private function buildSearchType($method, $searchDefinition) {
		$this->_searchTypesModel->addSearchType($method, $searchDefinition);
		$this->_searchFieldsModel->addSearchFieldsForTypeId(
				$this->_searchTypesModel->getRowId(),
				$searchDefinition
		);
		$this->_searchColumnsModel->addSearchColumnsForTypeId(
				$this->_searchTypesModel->getRowId(),
				$searchDefinition
		);
		$this->_searchFormatsModel->addSearchFormatsForTypeId(
				$this->_searchTypesModel->getRowId(),
				$searchDefinition,
				$this->_searchFormatColumnsModel,
				$this->_searchColumnsModel
		);
	}
	
	/**
	 * Setter for SearchTypes Model
	 * @param Model_Table_SearchTypes $searchTypes
	 */
	public function setSearchTypesModel(
			Model_Table_SearchTypes $searchTypes) {
		$this->_searchTypesModel = $searchTypes;
	}
	
	/**
	 * Setter for SearchFields Model
	 * @param Model_Table_SearchFields $searchFields
	 */
	public function setSearchFieldsModel(
			Model_Table_SearchFields $searchFields) {
		$this->_searchFieldsModel = $searchFields;
	}
	
	/**
	 * Setter for SearchFormats Model
	 * @param Model_Table_SearchFormats $searchFormats
	 */
	public function setSearchFormatsModel(
			Model_Table_SearchFormats $searchFormats) {
		$this->_searchFormatsModel = $searchFormats;
	}
	
	/**
	 * Setter for SearchColumns Model
	 * @param Model_Table_SearchColumns $searchColumns
	 */
	public function setSearchColumnsModel(
			Model_Table_SearchColumns $searchColumns) {
		$this->_searchColumnsModel = $searchColumns;
	}
	
	/**
	 * Setter for SearchFormatColumns Model
	 * @param Model_Table_SearchFormatColumns $searchFormatColumns
	 */
	public function setSearchFormatColumnsModel(
			Model_Table_SearchFormatColumns $searchFormatColumns) {
		$this->_searchFormatColumnsModel = $searchFormatColumns;
	}
	
	/**
	 * Setter for SavedSearch Model
	 * @param Model_Table_SavedSearch $savedSearch
	 */
	public function setSavedSearchModel(
			Model_Table_SavedSearch $savedSearch) {
		$this->_savedSearchModel = $savedSearch;
	}
}