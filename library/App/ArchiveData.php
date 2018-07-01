<?php
/*
 * App_ArchiveData
 */
class App_ArchiveData
{
	public $view;
	public $formNumber;
	public $usersession;

	function __construct($options = null)
	{
		$this->usersession = new Zend_Session_Namespace ( 'user' );
		
	}
	public function setFormNumber($formNumber) {
		$this->formNumber = $formNumber;
	}
	public function getFormNumber() {
		return $this->formNumber;
	}

	public function createPdf($view, $formNumber, $documentId, $formClass)
	{
	}
}
