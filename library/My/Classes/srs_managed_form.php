<?php


class srs_managed_form
{
	var $request;
	var $response;
	var $formNumber;
	var $controllerPath;


	function __construct ($request, $response, $controllerPath, $formNumber)
	{
		$this->request = $request;
		$this->response = $response;
		$this->formNumber = $formNumber;
		$this->controllerPath = $controllerPath;
		$this->setPageCount();
	}

	public function existsFile($path)
	{
//		echo "<B>$path</B><BR>";
		return file_exists($path);
	}

	public function existsFileMsg($path)
	{
		
		if($this->existsFile($path))
		{
			return $path . " exists";
		} else {
			return '<span style="color:red"><B>' . $path . '</B> DOES NOT EXIST</span>';
		}
		
	}

	public function existsFileForm()
	{
		$path = APPLICATION_PATH . '/forms/Form' . $this->getFormNumber() . '.php';
		return $this->existsFile($path);
	}

	public function existsFileViewScriptFolder()
	{
		$path = APPLICATION_PATH . '/views/scripts/form' . $this->getFormNumber() . '/';
		return $this->existsFile($path);
	}

	public function existsFormViewScriptFile($mode, $page, $version)
	{
		$path = APPLICATION_PATH . '/views/scripts/'.
				'form' . $this->getFormNumber() . 
				'/form' . $this->getFormNumber() . '_' . $mode . '_page' . $page . '_version' . $version . '.phtml';
//		echo "path:$path<BR>";
		return $this->existsFile($path);
	}

	public function existsFormJsScriptFile($mode, $page, $version)
	{
		$path = APPLICATION_PATH . '/../public/js-src/srs_forms/'.
				'form' . $this->getFormNumber() . 
				'_p' . $page . '_v' . $version . '.js';
//		echo "path:$path<BR>";
		return $this->existsFile($path);
	}

	public function setPageCount()
	{
		$this->pageCount = $this->getPageCount();
	}
	public function getPageCount()
	{
		require_once(APPLICATION_PATH.'/controllers/'.$this->controllerPath);
		$controllerName = basename($this->controllerPath, '.php');
		$tempController = new $controllerName($this->request, $this->response);
		
		if(isset($tempController->view->pageCount))
		{
			$pageCount = $tempController->view->pageCount;
		} else {
			$pageCount = 1;
		}
		return $pageCount;
	}

	public function getFormNumber()
	{
		return $this->formNumber;
	}





}
