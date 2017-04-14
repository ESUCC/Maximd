<?php

class HtmlpurifierController extends My_Form_AbstractFormController {


	
	public function textworkshopAction() {

		
		if(1 == $this->getrequest()->getParam('submitted')) {
			$sessPure->options = $this->getrequest()->getParams();
		}
		
		$formObjNoFilter = new Form_HtmlPurifier();
		$this->view->form = $formObjNoFilter->example(); 

		$formObjWithFilter = new Form_HtmlPurifier();
		$this->view->filterForm = $formObjWithFilter->example(); 
		
		
		if(1 == $this->getrequest()->getParam('submitted')) {
			
			
			$this->view->form->populate($this->getrequest()->getParams());
			$this->view->form->paste_html->setValue(($this->getrequest()->getParam('paste_in')));
			
			$this->view->filterForm->processed->setValue($this->getrequest()->getParam('paste_in'));
			$this->view->filterForm->processed_html->setValue(($this->view->filterForm->processed->getValue()));
		}
        
	}
    
}