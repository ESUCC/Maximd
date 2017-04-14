<?php
// not used at the moment
class My_Form_AbstractForm extends Zend_Dojo_Form {
	
	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	public $accessMode;
	public $page;
	public $version;
	public $data;
	
    /**
     * $formHelper - App_Form_FormHelper
     *
     * @var App_Form_FormHelper
     */
	public $formHelper;
	
    /**
     * $form - Zend_Dojo_Form
     *
     * @var Zend_Dojo_Form
     */
	public $form;
	
	
	public function __construct($options = null)
	{
		$this->formHelper = new App_Form_FormHelper();
		
		try {
			$this->accessMode = $options['mode'];
			$this->page = $options['page'];
			$this->version = $options['version'];
		} catch (Exception $e) {
			echo "$e";die();
			return $e;
		}
		parent::__construct();
	}
	
	public function buildForm()
	{
		// build the name of the function used to build this form
		// edit_p1_v1
		$buildFunctionName = $this->accessMode . '_p' . $this->page . '_v' . $this->version;
		
		// call the internal function to build the zend form
		$this->form = $this->$buildFunctionName();
	}	
	
	private function initialize() {
		$this->setAction('search/search')->setMethod('post')->setAttrib('class', 'srchFrmHome');
      	$this->id_student = new App_Form_Element_Hidden('id_student');
		$this->id_student->ignore = true;
		$this->id_form_004 = new App_Form_Element_Hidden('id_form_004');
      	$this->id_form_004->ignore = true;
		$this->page = new App_Form_Element_Hidden('page');
		$this->page->ignore = true;
		$this->zend_checkout_time = new App_Form_Element_Hidden('zend_checkout_time');
	}
	
	
}

