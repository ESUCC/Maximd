<?php
class {ControllerName}Controller extends My_Form_AbstractFormController
{
    public function indexAction(){
		$this->view->dojo()->requireModule('soliant.widget.{WidgetName}');
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
    	
		$searchObj = new Model_Table_{ControllerName}();
		$results = $searchObj->search($this->params['id_{UnderscoreName}']);

		return $this->returnAjax('id_{UnderscoreName}', array($results));
	}

	public function saveAction () {
		$this->initAjax();
		
//		Zend_Debug::dump($this->params);
		// fields to save
        $error = false;
        if(!is_array($this->params['id_{UnderscoreName}']) && '' == $this->params['id_{UnderscoreName}']) {
        	return $this->returnAjax('result', array(array('result' => 'error')));
        	
        } elseif(!is_array($this->params['id_{UnderscoreName}']) && '' != $this->params['id_{UnderscoreName}']) {
        	$id = $this->params['id_{UnderscoreName}'];
	        $saveData = array(
				'name'=> $this->params['name']
	        );
       		$results = Model_Table_WidgetList::save($id, $saveData);
			if(!$results) {
				$error = true;
			}
        	
        } else {
	        for($i=0; $i<count($this->params['id_{UnderscoreName}']); $i++) {
	        	$id = $this->params['id_{UnderscoreName}'][$i];
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

		$searchObj = new Model_Table_{ControllerName}();
		$results = $searchObj->relatedRecords('{ForeignKey}', $this->params['{ForeignKey}']);
		if(!$results) {
			return $this->returnAjax('result', array(array('result' => 'error')));
		} else {
			return $this->returnAjax('id_{UnderscoreName}', $results);
		}
	}
	public function addrowAction() {
		$this->initAjax();
		
		// get searches and make it look nice
		$searchObj = new Model_Table_{ControllerName}();
		$rowId = $searchObj->insertRow(array('{ForeignKey}'=>$this->getRequest()->getParam('id_{UnderscoreName}')));
		$row = $searchObj->find($rowId)->toArray();
		
		if(!$row) {
			return $this->returnAjax('result', array('result' => 'error'));
		} else {
			return $this->returnAjax('{ForeignKey}', $row);
		}
	}
	public function removerowAction() {
		$this->initAjax();
		
		// get searches and make it look nice
		$searchObj = new Model_Table_{ControllerName}();
		$result = $searchObj->deleteRow($this->getRequest()->getParam('id_{UnderscoreName}'));
        
        if($result) {
        	return $this->returnAjax('result', array(array('result' => 'success', 'id_{UnderscoreName}' => $this->getRequest()->getParam('id_{UnderscoreName}'))));
        } else {
			return $this->returnAjax('result', array(array('result' => 'error', 'id_{UnderscoreName}' => $this->getRequest()->getParam('id_{UnderscoreName}'))));
        }
		        
	}
	
}
