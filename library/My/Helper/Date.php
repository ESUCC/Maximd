<?php
/**
 * @category   My_Helper
 * @package    My_Helper_Auth
 */
class My_Helper_Date {
	
	public static function date_at_timezone($format, $locale, $timestamp=null){
	   
	    if(is_null($timestamp)) $timestamp = time();
	   
	    //Prepare to calculate the time zone offset
	    $current = time();
	   
	    //Switch to new time zone locale
	    $tz = date_default_timezone_get();
	    date_default_timezone_set($locale);
	   
	    //Calculate the offset
	    $offset = time() - $current;
	   
	    //Get the date in the new locale
	    $output = date($format, $timestamp - $offset);
	   
	    //Restore the previous time zone
	    date_default_timezone_set($tz);
	   
	    return $output;
	   
	}
}