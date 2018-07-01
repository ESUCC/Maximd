<?php
class ExceptiontestController extends Zend_Controller_Action
{
    /**
     * Home page; display site entrance form
     * 
     * @return void
     */
    public function indexAction() {
        throw new Zend_Exception('booo hooo');
    }
	
}