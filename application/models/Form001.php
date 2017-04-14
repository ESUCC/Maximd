<?php

/**
 * Form001 model
 *
 */
class Model_Form001 extends Model_AbstractForm
{

    /**
     * $subformIndexToModel - array
     *
     * @var array
     */
	var $subformIndexToModel = array();
	
    /**
     * $db_form_data - array
     *
     * @var array
     */
	var $db_form_data = array();
	
	public function find($id, $accessMode = "view", $page =1, $versionNumber = 1, $checkout = 0)
	{
		
		if(false === parent::buildDbForm($id, $accessMode, $page, $versionNumber, $checkout))
		{
			return false;
		}
        		
//	    Zend_debug::dump($this->formAccessObj);
//		echo "<PRE>";
//		print_r($this->db_form_data);
		return $this->db_form_data;
	}	
}
