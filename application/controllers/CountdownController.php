<?php

class CountdownController extends My_Form_AbstractFormController {
	public function countdownAction() {
        $countDownTo = $this->getParam('countto');
        $duration = strtotime($countDownTo) - strtotime('now');
        $this->view->diff = $duration;
		header ( "Content-Type: text/javascript" );
	}
}
