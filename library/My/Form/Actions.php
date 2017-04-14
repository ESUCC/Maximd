<?php

class My_Form_Actions {
	
	private $type;
	private $putInto;
	private $code = array();
	
	public function __construct($type, $putInto) {
		$this->type = $type;
		$this->putInto = $putInto;
	}
	
	public function setCode($code) {
		$this->code = $code;
	}
	
	public function setPutInto($putInto) {
		$this->putInto = $putInto;
	}
	
	public function setType($type) {
		$this->type = $type;
	}
	
	
	public function getCode() {
		return $this->code;
	}
	
	public function getPutInto() {
		return $this->putInto;
	}
	
	public function getType() {
		return $this->type;
	}

}