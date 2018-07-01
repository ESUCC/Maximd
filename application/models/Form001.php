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
		
	    /*
	     * Mike replaced this with the catch try
	     if(false === parent::buildDbForm($id, $accessMode, $page, $versionNumber, $checkout))
	     {
	      
	     return false;
	     }
	     */
	     
	    try {
	        	
	        $this->buildDbForm($id, $accessMode, $page, $versionNumber, $checkout);
	        	
	        	
	        	
	    }
	    catch (App_Exception_Checkout $e) {
	        $this->writevar1($e->getMessage(),'this is the error message');
	        //    $this->view->scott=$e->getMessage();
	        $t[0]['message']=$e->getMessage();
	        return $t;
	    }	
//	    Zend_debug::dump($this->formAccessObj);
//		echo "<PRE>";
//		print_r($this->db_form_data);
		return $this->db_form_data;
	}	
}
