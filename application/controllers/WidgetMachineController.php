<?php
class WidgetMachineController extends My_Form_AbstractFormController
{
	var $createPath = '/Users/jlavere/WidgetMachineCreatedFiles';
	var $templatePath = '/usr/local/zend/apache2/htdocs/srs-zf/application/views/scripts/widget-machine/source';
    public function indexAction() {
    	
        // get searches and make it look nice
    	$searchObj = new Model_Table_WidgetMachine();
    	
		// get the list of all searches for this user
		$this->view->mySearches = $searchObj->mySearches($this->usersession->sessIdUser, 'my_widgets');
    }
    public function doReplaces(&$source) {
    	$source = str_replace('{ControllerName}', $this->controllerName, $source);
    	$source = str_replace('{TableName}', $this->dashesWidgetName, $source);
		
    	$source = str_replace('{WidgetName}', $this->widgetName, $source);
    	$source = str_replace('{DashesName}', $this->dashesWidgetName, $source);
    	$source = str_replace('{UnderscoreName}', $this->underscoresWidgetName, $source);

    	$source = str_replace('{ForeignKey}', $this->foreignKey, $source);
    	
//    	$source = str_replace('{PrimaryKey}', $this->getRequest()->getParam('id_widget_machine'), $source);

    	$source = str_replace('{PrimaryKey}', '1', $source);
		return $source;
    }
    public function buildFilesAction() {
    	
        // get searches and make it look nice
    	$searchObj = new Model_Table_WidgetMachine();
		$mySearch = $searchObj->getSearch($this->getRequest()->getParam('id_widget_machine'), 'my_widgets');
		
		Zend_Debug::dump($mySearch, 'mySearch');
		
		$this->templatePath = $this->templatePath.'/'.$mySearch['type'];
		
		$this->widgetName = str_replace(' ', '', ucwords(strtolower($mySearch['name'])));
		$this->controllerName = $this->widgetName;
		$this->dashesWidgetName = str_replace(' ', '-', (strtolower($mySearch['name']))); // this-has-dashes
		$this->underscoresWidgetName = str_replace(' ', '_', (strtolower($mySearch['name']))); // this_has_underscores
		$this->foreignKey = 'id_foreign_key';

//		Zend_Debug::dump($viewscriptFolderName, 'viewscriptFolderName');

		// build the create path based on the widget name
		$widgetParentPath = $this->createPath.'/'.$this->widgetName;
		if(!file_exists($widgetParentPath)) {
			mkdir($widgetParentPath, 0777);
		}
		$this->createPath = $widgetParentPath;
		
		
		/*
		 * create the view script folder
		 */
		$viewscriptFolderPath = $this->createPath.'/'.$this->dashesWidgetName;
		if(!file_exists($viewscriptFolderPath)) {
			mkdir($viewscriptFolderPath, 0777);
		}
		$viewscriptPath = $viewscriptFolderPath.'/index.phtml';
		$source = file_get_contents($this->templatePath.'/TemplateIndex.phtml');
		$this->doReplaces($source);
		$result = file_put_contents($viewscriptPath, $source);
		if(false === $result) {
		} else {
			Zend_Debug::dump('Widget css created.');
		}

		$viewscriptPath = $viewscriptFolderPath.'/data.phtml';
		$source = file_get_contents($this->templatePath.'/data.phtml');
		$result = file_put_contents($viewscriptPath, $source);
		if(false === $result) {
		} else {
			Zend_Debug::dump('Viewscript for data created.');
		}
		
		
		
		
		/*
		 * create the controller from template
		 */
		$controllerPath = $this->createPath.'/'.$this->widgetName.'Controller.php';
		$source = file_get_contents($this->templatePath.'/TemplateController.php');
		$this->doReplaces($source);
    	$result = file_put_contents($controllerPath, $source);
		if(false === $result) {
		} else {
			Zend_Debug::dump('Controller created.');
		}

		/*
		 * create the model from template
		 */
		$modelPath = $this->createPath.'/'.$this->widgetName.'.php';
		$source = file_get_contents($this->templatePath.'/TemplateModel.php');
		$this->doReplaces($source);
		$result = file_put_contents($modelPath, $source);
		if(false === $result) {
		} else {
			Zend_Debug::dump('Model created.');
		}
    
		/*
		 * create the widget from template
		 */
		$widgetFolderPath = $this->createPath.'/widget';
		if(!file_exists($widgetFolderPath)) {
			mkdir($widgetFolderPath, 0777);
		}
		$widgetSubFolderPath = $widgetFolderPath.'/'.$this->widgetName;
		if(!file_exists($widgetSubFolderPath)) {
			mkdir($widgetSubFolderPath, 0777);
		}
		
		$widgetPath = $widgetFolderPath.'/'.$this->widgetName.'.js';
		$source = file_get_contents($this->templatePath.'/TemplateWidget.js');
		$this->doReplaces($source);
    	$result = file_put_contents($widgetPath, $source);
		if(false === $result) {
		} else {
			Zend_Debug::dump('Widget JS created.');
		}
    
		$widgetPath = $widgetSubFolderPath.'/'.$this->widgetName.'.css';
		$source = file_get_contents($this->templatePath.'/TemplateWidget.css');
		$this->doReplaces($source);
    	$result = file_put_contents($widgetPath, $source);
		if(false === $result) {
		} else {
			Zend_Debug::dump('Widget css created.');
		}
    
		$widgetPath = $widgetSubFolderPath.'/'.$this->widgetName.'.html';
		$source = file_get_contents($this->templatePath.'/TemplateWidget.html');
		$this->doReplaces($source);
    	$result = file_put_contents($widgetPath, $source);
		if(false === $result) {
		} else {
			Zend_Debug::dump('Widget html created.');
		}
    
    
    }
    

}
