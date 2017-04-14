<?php
class StudentSearchController extends My_Form_AbstractFormController
{

    public function init(){
        $this->_redirector = $this->_helper->getHelper('Redirector');
        
        // we disable the layout because we're returning ajax
        $this->_helper->layout->disableLayout(true);
    }
	public function getsearchheaderAction() {
        // get searches and make it look nice
		$searchObj = new Model_Table_StudentSearch();

		// get the list of all searches for this user
		$mySearches = $searchObj->mySearches($this->usersession->sessIdUser, 'my_searches');
		
		// get the main search options (limit/status/sort)
		// as well as the the search row data
		$mySearch = $searchObj->getSearch($mySearches[0]['id_student_search'], 'my_searches');
	    $data = new Zend_Dojo_Data('id_student_search', array($mySearch));
        $this->view->data = $data->toJson();
        return $this->render('data');
				
	}
	public function getrowsAction() {
        // get searches and make it look nice
		$searchObj = new Model_Table_StudentSearch();

		// get the list of all searches for this user
		$mySearches = $searchObj->mySearches($this->usersession->sessIdUser, 'my_searches');
		
		// get the main search options (limit/status/sort)
		// as well as the the search row data
		$mySearch = $searchObj->getSearch($mySearches[0]['id_student_search'], 'my_searches');
        if(0 < count($mySearch['subforms']['my_searches'])) {
	        $data = new Zend_Dojo_Data('id_student_search_rows', $mySearch['subforms']['my_searches']);
        } else {
        	$data = new Zend_Dojo_Data('id_student_search_rows', array());
        }
        $this->view->data = $data->toJson();
        return $this->render('data');
				
	}
	public function addrowAction() {
		// get searches and make it look nice
		$searchObj = new Model_Table_StudentSearchRow();
		$rowId = $searchObj->insertRow(array('id_student_search'=>$this->getRequest()->getParam('id_student_search')));
		$row = $searchObj->find($rowId)->toArray();
		
		$data = new Zend_Dojo_Data('id_student_search_rows', $row);
        $this->view->data = $data->toJson();
        return $this->render('data');
	}
	
	public function saveAction () {
		$params = json_decode ( $this->getRequest ()->getParam ( 'data' ), true );
		
        $results = Model_Table_StudentSearch::save($params['id_student_search'], $params);
		
        $data = new Zend_Dojo_Data('result', array());
        $data->addItem(array('result' => 'success', 'id_student_search' => $params['id_student_search']));
        $this->view->data = $data->toJson();
        return $this->render('data');
    }
	
	public function removerowAction() {
		// get searches and make it look nice
		$searchObj = new Model_Table_StudentSearchRow();
		$result = $searchObj->deleteRow($this->getRequest()->getParam('id_student_search_row'));
        
		$data = new Zend_Dojo_Data('result', array());
        $data->addItem(array('result' => 'success', 'id_student_search_row' => $this->getRequest()->getParam('id_student_search_row')));
        $this->view->data = $data->toJson();
        return $this->render('data');
		
	}
	public function searchAction () {
		$params = json_decode ( $this->getRequest ()->getParam ( 'data' ), true );

		$sessUser = new Zend_Session_Namespace('user');
        $results = Model_StudentSearch::search($sessUser->id_personnel, $params);
		if(0 < count($results)) {
	        $data = new Zend_Dojo_Data('id_student', $results->toArray());
        } else {
        	$data = new Zend_Dojo_Data('id_student', array());
        }
        $this->view->data = $data->toJson();
        return $this->render('data');
    }

}
