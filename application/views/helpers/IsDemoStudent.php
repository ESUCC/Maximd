<?php

class Zend_View_Helper_IsDemoStudent extends Zend_View_Helper_Abstract
{

    /**
     * Returns true if user is in demo county, district or school
     * 
     * @return string
     */
    public function IsDemoStudent($county, $district, $school)
    {
    	$counties = array(99);
    	$districts = array(9999);
    	$schools = array(999);
        if (in_array($county, $counties) || in_array($district, $districts) || in_array($school, $schools)) {
        	return true;
        }
        return false;
    }
}