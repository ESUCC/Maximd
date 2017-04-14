<?php

class Zend_View_Helper_IsOldStyleForm extends Zend_View_Helper_Abstract
{

    /**
     * Returns true if form should redirect to iep.unl.edu form
     * 
     * @return string
     */
    public function IsOldStyleForm($form_id)
    {
    	$old_forms = array('016');
        if (in_array($form_id, $old_forms)) {
        	return true;
        }
        return false;
    }
}