<?php
class WidgetListController extends My_Form_AbstractFormController
{
    public function indexAction(){
		$this->view->dojo()->requireModule('soliant.widget.WidgetList');
    }
    public function initAjax(){
        // we disable the layout because we're returning ajax
		$this->_helper->layout->disableLayout(true);

		$this->params = json_decode ( $this->getRequest ()->getParam ( 'data' ), true );
    }
    public function returnAjax($id, $results){
        // return ajax data
        $data = new Zend_Dojo_Data($id, $results);
        $this->view->data = $data->toJson();
        return $this->render('data');
    }
    public function searchAction(){
		$this->initAjax();
    	
		$searchObj = new Model_Table_WidgetList();
		$results = $searchObj->search($this->params['id_widget_list']);

		return $this->returnAjax('id_widget_list', array($results));
	}

	public function saveAction () {
		$this->initAjax();
		
//		Zend_Debug::dump($this->params);
		// fields to save
        $error = false;
        if(!is_array($this->params['id_widget_list']) && '' == $this->params['id_widget_list']) {
        	return $this->returnAjax('result', array(array('result' => 'error')));
        	
        } elseif(!is_array($this->params['id_widget_list']) && '' != $this->params['id_widget_list']) {
        	$id = $this->params['id_widget_list'];
	        $saveData = array(
				'name'=> $this->params['name']
	        );
       		$results = Model_Table_WidgetList::save($id, $saveData);
			if(!$results) {
				$error = true;
			}
        	
        } else {
	        for($i=0; $i<count($this->params['id_widget_list']); $i++) {
	        	$id = $this->params['id_widget_list'][$i];
		        $saveData = array(
					'name'=> $this->params['name'][$i]        
		        );
		        // save
		        $results = Model_Table_WidgetList::save($id, $saveData);
				if(!$results) {
					$error = true;
				}
	        }
        }
        
		if(!$error) {
			return $this->returnAjax('result', array(array('result' => 'success')));
		} else {
			return $this->returnAjax('result', array(array('result' => 'error')));
		}
        
		
    }
	
    public function relatedRecordsAction(){
		$this->initAjax();

		$searchObj = new Model_Table_WidgetList();
		$results = $searchObj->relatedRecords('id_foreign_key', $this->params['id_foreign_key']);
		if(!$results) {
			return $this->returnAjax('result', array(array('result' => 'error')));
		} else {
			return $this->returnAjax('id_widget_list', $results);
		}
	}
	public function addrowAction() {
		$this->initAjax();
		
		// get searches and make it look nice
		$searchObj = new Model_Table_WidgetList();
		$rowId = $searchObj->insertRow(array('id_foreign_key'=>$this->getRequest()->getParam('id_widget_list')));
		$row = $searchObj->find($rowId)->toArray();
		
		if(!$row) {
			return $this->returnAjax('result', array('result' => 'error'));
		} else {
			return $this->returnAjax('id_foreign_key', $row);
		}
	}
	public function removerowAction() {
		$this->initAjax();
		
		// get searches and make it look nice
		$searchObj = new Model_Table_WidgetList();
		$result = $searchObj->deleteRow($this->getRequest()->getParam('id_widget_list'));
        
        if($result) {
        	return $this->returnAjax('result', array(array('result' => 'success', 'id_widget_list' => $this->getRequest()->getParam('id_widget_list'))));
        } else {
			return $this->returnAjax('result', array(array('result' => 'error', 'id_widget_list' => $this->getRequest()->getParam('id_widget_list'))));
        }
		        
	}
	
}
