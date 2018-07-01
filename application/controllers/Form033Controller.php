<?php

class Form033Controller extends My_Form_AbstractFormController {

    protected $identity;
	protected $version = 9;
	protected $startPage = 1;

    public function init(){
        $this->_redirector = $this->_helper->getHelper('Redirector');

        // form parameters
        $this->view->pageCount = 1;
        parent::setPrimaryKeyName('id_form_033');
        parent::setFormNumber('033');
        parent::setModelName('Model_Form033');
        parent::setFormClass('Form_Form033');
        parent::setFormTitle('Annual Transition Notice');
        parent::setFormRev('08/15');
    }
}
