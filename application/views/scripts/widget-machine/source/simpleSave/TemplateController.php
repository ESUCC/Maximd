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
		
		// fields to save
        $saveData = array(
			'name'=> $this->params['name']        
        );
        
        // save
        $results = Model_Table_{ControllerName}::save($this->params['id_{UnderscoreName}'], $saveData);
		return $this->returnAjax('result', array(array('result' => 'success', 'id_{UnderscoreName}' => $this->params['id_{UnderscoreName}'])));
    }
	
}
