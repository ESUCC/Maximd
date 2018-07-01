<?php

class My_Helper_CurlPage { // was My_Helper_Aux but aux.php not allowed in windows
	

	public static function curlPageURL() {
		$pageURL = 'http';
		if (isset ( $_SERVER ["HTTPS"] )) {
			if ($_SERVER ["HTTPS"] == "on") {
				$pageURL .= "s";
			}
		}
		$pageURL .= "://";
		if ($_SERVER ["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
		}
		return $pageURL;
	}
}