<?php
ini_set ( 'max_execution_time', 90 );
?>
<?php

define ( 'SPROXY_DEBUG_MODE_FILE', 1 );
define ( 'SPROXY_DEBUG_MODE_DUMP', 2 );
define ( 'SPROXY_DEBUG_MODE_HTML', 3 );
define ( 'SPROXY_DEBUG_MODE_ARRAY', 4 );
class SProxyUtils {
	function __writeFile($sFilePath, $sFileContent) {
		$sFilePath = SProxyUtils::__convertDelimitersToUnixStyle ( $sFilePath );
		$aFilePathInfo = pathinfo ( $sFilePath );
		$sDirPath = (isset ( $aFilePathInfo ['dirname'] )) ? (trim ( $aFilePathInfo ['dirname'] )) : '';
		$bIsDirExists = SProxyUtils::__makeDir ( $sDirPath );
		if ($bIsDirExists) {
			$rFile = fopen ( $sFilePath, 'wb' );
			if ($rFile) {
				if (flock ( $rFile, LOCK_EX )) {
					fwrite ( $rFile, $sFileContent );
					flock ( $rFile, LOCK_UN );
				}
				fclose ( $rFile );
			}
		}
		if (file_exists ( $sFilePath )) {
			return true;
		} else {
			return false;
		}
	}
	function __readFile($sFilePath) {
		$sFileContent = "";
		$sFilePath = SProxyUtils::__convertDelimitersToUnixStyle ( $sFilePath );
		if (file_exists ( $sFilePath )) {
			$rFile = fopen ( $sFilePath, 'rb' );
			if ($rFile && filesize ( $sFilePath ) > 0) {
				if (flock ( $rFile, LOCK_EX )) {
					$sFileContent = fread ( $rFile, filesize ( $sFilePath ) );
					flock ( $rFile, LOCK_UN );
				}
				fclose ( $rFile );
			}
		}
		return $sFileContent;
	}
	function __makeDir($sDirPath, $iMode = 0777) {
		$sDirPath = SProxyUtils::__convertDelimitersToUnixStyle ( $sDirPath );
		if (is_dir ( $sDirPath )) {
			return true;
		}
		$sDirComplexPath = '';
		$aDirPathAtoms = explode ( '/', $sDirPath );
		foreach ( $aDirPathAtoms as $sPathAtom ) {
			$sDirComplexPath .= $sPathAtom . '/';
			if (is_dir ( $sDirComplexPath ) === false) {
				@mkdir ( $sDirComplexPath, $iMode );
			}
		}
		if (is_dir ( $sDirPath )) {
			return true;
		} else {
			return false;
		}
	}
	function __convertDelimitersToUnixStyle($sFileName) {
		return str_replace ( '\\', '/', $sFileName );
	}
	function __addTrailingDelim($sPath) {
		$sPath = SProxyUtils::__convertDelimitersToUnixStyle ( $sPath );
		if (substr ( $sPath, strlen ( $sPath ) - 1, 1 ) != '/') {
			$sPath .= '/';
		}
		return $sPath;
	}
	function __splitResponse($sResponseText, $sPrimaryHTTPStatus = '') {
		$aResponse = array ();
		$aResponse ['content'] = '';
		$aResponse ['status'] = '';
		$aResponse ['primaryStatus'] = $sPrimaryHTTPStatus;
		$aResponse ['headers'] = array ();
		$iHeaderEndPos = strpos ( $sResponseText, "\r\n\r\n" ) + 4;
		$sResponseHeader = substr ( $sResponseText, 0, $iHeaderEndPos );
		$aResponse ['content'] = substr ( $sResponseText, $iHeaderEndPos );
		$aResponse ['status'] = substr ( $sResponseHeader, 9, 3 ); //200 etc.
		if ($aResponse ['status'] == '100') {
			return SProxyUtils::__splitResponse ( $aResponse ['content'], $aResponse ['status'] );
		}
		$aHeaderAtoms = explode ( "\r\n", $sResponseHeader );
		foreach ( $aHeaderAtoms as $sAtom ) {
			if (strlen ( trim ( $sAtom ) ) > 0) {
				$aResponse ['headers'] [] = trim ( $sAtom );
			}
		}
		return $aResponse;
	}
	function __escape($sHTML) {
		return str_replace ( array ("&", "\"", "\r", "\n", "\t" ), array ("&amp;", "&quot;", "&#13;", "&#10;", "&#9;" ), $sHTML );
	}
	function __escapeXML($sXML) {
		return str_replace ( array ('&', '"', "'", '<', '>' ), array ('&amp;', '&quot;', '&apos;', '&lt;', '&gt;' ), $sXML );
	}
	function __createParamsString($aParams) {
		$sParamsString = '';
		$i = 0;
		foreach ( $aParams as $sKey => $sValue ) {
			if (get_magic_quotes_gpc () == 1) {
				$sParamsString .= $sKey . '=' . urlencode ( stripslashes ( $aParams [$sKey] ) );
			} else {
				$sParamsString .= $sKey . '=' . urlencode ( $aParams [$sKey] );
			}
			if ($i ++ < count ( $aParams ) - 1) {
				$sParamsString .= '&';
			}
		}
		return $sParamsString;
	}
	function __debug($mValue, $iType = SPROXY_DEBUG_MODE_FILE) {
		switch ($iType) {
			case SPROXY_DEBUG_MODE_HTML :
				print '<pre>';
				print_r ( htmlspecialchars ( $mValue ) );
				print '</pre>';
				break;
			case SPROXY_DEBUG_MODE_FILE :
				$sPath = 'tmp/log.txt';
				$rLog = fopen ( $sPath, 'a+' );
				if ($rLog) {
					fwrite ( $rLog, "====================================\n" );
					fwrite ( $rLog, print_r ( $mValue, true ) . "\n" );
					fclose ( $rLog );
				}
				break;
			case SPROXY_DEBUG_MODE_DUMP :
				var_dump ( $mValue );
				break;
			case SPROXY_DEBUG_MODE_ARRAY :
			default :
				print '<pre>';
				print_r ( $mValue );
				print '</pre>';
				break;
		}
	}
}
?>
<?php

class SProxyDataStorage {
	var $aData = array ();
	var $aExcludeList = array ();
	function SProxyDataStorage($aInitalList, $aExcludeList) {
		$this->aExcludeList = $aExcludeList;
		$this->_initialFill ( $aInitalList );
	}
	function get($sName) {
		return isset ( $this->aData [$sName] ) ? ($this->aData [$sName]) : '';
	}
	function set($sName, $sValue) {
		$this->aData [$sName] = $sValue;
	}
	function setIfNotExists($sName, $sValue, $bEmptyCheck = true) {
		if (! isset ( $this->aData [$sName] ) || ($bEmptyCheck && empty ( $this->aData [$sName] ))) {
			$this->aData [$sName] = $sValue;
		}
	}
	function getAll($bUseExcludeList = true) {
		$aOutput = array ();
		if ($bUseExcludeList) {
			foreach ( $this->aData as $sDataKey => $sDataValue ) {
				if (in_array ( $sDataKey, $this->aExcludeList ) == false) {
					$aOutput [$sDataKey] = $sDataValue;
				}
			}
			return $aOutput;
		} else {
			return $this->aData;
		}
	}
	function isExists($sName) {
		return isset ( $this->aData [$sName] );
	}
	function getURI($sMode) {
		$sPort = $this->get ( 'ssrv_port' );
		switch ($sMode) {
			case 'host' :
				return $this->get ( 'ssrv_host' ) . ((! empty ( $sPort )) ? (':' . $sPort) : (''));
				break;
			case 'full' :
				return $this->get ( 'protocol' ) . $this->get ( 'ssrv_host' ) . ((! empty ( $sPort )) ? (':' . $sPort) : ('')) . $this->get ( 'sp_vdir' ) . $this->get ( 'rel_script_path' );
				break;
			case 'root' :
			default :
				return $this->get ( 'protocol' ) . $this->get ( 'ssrv_host' ) . ((! empty ( $sPort )) ? (':' . $sPort) : ('')) . $this->get ( 'sp_vdir' );
				break;
		}
	}
	function _initialFill($aInitalList) {
		$this->aData = $aInitalList;
		$this->setIfNotExists ( 'settings', 'sproxy', true );
		$this->setIfNotExists ( 'service_host', $this->get ( 'default_service_host' ), true );
		$sServiceHost = $this->get ( 'service_host' );
		$sSProxyProtocol = (isset ( $_SERVER ['HTTPS'] ) && strtolower ( $_SERVER ['HTTPS'] ) == 'on') ? ('https://') : ('http://');
		if (preg_match ( "/^(http[s]?:\/\/)?([^\/?#:]+)(?::(\d{2,4}))*(\/[^?#]+[\/]?)*/i", $sServiceHost, $aMatches )) {
			$this->set ( 'protocol', $aMatches [1] );
			$this->set ( 'ssrv_host', $aMatches [2] );
			$this->set ( 'ssrv_port', $aMatches [3] );
			$this->set ( 'sp_vdir', $aMatches [4] );
		}
		$this->set ( 'ssrv_path', $this->get ( 'sp_vdir' ) . $this->get ( 'rel_script_path' ) );
		$this->set ( 'service_host', $this->getURI ( 'root' ) );
		$this->set ( 'achk_file', substr ( $_SERVER ['PHP_SELF'], strrpos ( $_SERVER ['PHP_SELF'], '/' ) + 1 ) );
		$this->setIfNotExists ( 'sproxy_url', $sSProxyProtocol . $_SERVER ['HTTP_HOST'] . $_SERVER ['PHP_SELF'], true );
		$this->setIfNotExists ( 'trg_url', $this->get ( 'sproxy_url' ), true );
		$this->setIfNotExists ( 'cmd', '', false );
		$this->setIfNotExists ( 'schema', $this->get ( 'def_schema' ), true );
		$this->setIfNotExists ( 'cache_dir_name', $this->get ( 'def_cache_dir_name' ), true );
		$this->setIfNotExists ( 'cache_path', $this->get ( 'def_cache_path' ), true );
		$this->setIfNotExists ( 'no_cache', $this->get ( 'default_no_cache' ), true );
		$sCachePath = $this->get ( 'cache_path' );
		$sCachePath = SProxyUtils::__addTrailingDelim ( $sCachePath );
		$this->set ( 'cache_path', $sCachePath );
	}
}
?>
<?php

define ( 'SPROXY_ERR_OPEN_SOCKET_CONNECTION', 101 );
define ( 'SPROXY_ERR_INVALID_HTTP_RESPONSE_STATUS', 102 );
define ( 'SPROXY_ERR_UNABLE_CACHE_FILE', 103 );
define ( 'SPROXY_ERR_PROTOCOL_NOT_SUPPORTED', 104 );
define ( 'SPROXY_ERR_HTTPS_PLUGIN_REQUIRED', 105 );
define ( 'SPROXY_ERR_CURL_NOT_INSTALLED', 106 );
define ( 'SPROXY_ERR_OPEN_CURL_CONNECTION', 107 );
define ( 'SPROXY_ERR_INI_FILE_ERROR', 108 );
class SProxy {
	var $sIniPath = 'sproxy.ini';
	var $aDefaultSrvVars = array ('def_cache_dir_name' => 'sproxy_cache', 'def_cache_path' => './', 'def_schema' => '18', 'default_no_cache' => 'false', 'default_service_host' => 'http://www.spellchecker.net/spellcheck2', 'rel_script_path' => '/script/ssrv.cgi' );
	var $aIniVars = array ();
	var $aInternalSrvVars = array ('achk_file', 'cache_dir_name', 'cache_path', 'def_cache_dir_name', 'def_cache_path', 'default_no_cache', 'default_service_host', 'mime_type', 'no_cache', 'protocol', 'rel_script_path', 'sp_vdir', 'ssrv_host', 'ssrv_path', 'ssrv_port' );
	var $oSrvVars = null;
	var $sConnectionProtocol = 'http://';
	function SProxy() {
		$this->aIniVars = $this->_getIniVars ( $this->sIniPath );
		if (count ( $this->aIniVars ) == 0) {
			$sMsgText = 'Error: Sproxy ini file does not exists or write protected ';
			$this->_proccessErrorLight ( $sMsgText, SPROXY_ERR_INI_FILE_ERROR );
			die ();
		}
		$aInitalList = $this->aIniVars + $this->aDefaultSrvVars + $_REQUEST;
		if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
			$aInitalList = $_GET + $aInitalList;
		} elseif ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			$aInitalList = $_POST + $aInitalList;
		}
		$this->oSrvVars = new SProxyDataStorage ( $aInitalList, $this->aInternalSrvVars );
		$this->sConnectionProtocol = $this->oSrvVars->get ( 'protocol' );
		$this->_commandManager ();
	}
	function _commandManager() {
		$sCmd = $this->oSrvVars->get ( 'cmd' );
		$sFileContent = '';
		switch (strtolower ( $sCmd )) {
			case 'script' :
				$sFileContent = $this->_handleScriptCommand ();
				break;
			case 'ver' :
				$sFileContent = $this->_handleVerCommand ();
				break;
			case 'xver' :
				$sFileContent = $this->_handleXverCommand ();
				break;
			case 'error_conversion' :
				$sFileContent = $this->_handleErrorConversionCommand ();
				break;
			default :
				$this->_doCheck ();
				break;
		}
		$this->_display ( $sFileContent );
	}
	function _handleScriptCommand() {
		$sFilePath = $this->_getFilePath ();
		if (preg_match ( "/^false$/", $this->oSrvVars->get ( "no_cache" ) ) && $this->_isFileActual ( $sFilePath )) {
			return $this->_readDataFromFile ( $sFilePath );
		}
		$this->_doCheck ();
		return $this->_readDataFromFile ( $sFilePath );
	}
	function _handleVerCommand() {
		$this->oSrvVars->set ( 'mime_type', 'text/html' );
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="www.spellchecker.net" />
<meta name="copyright" content="&copy; 2000-2007 SpellChecker.net, Inc. All rights reserved." />
<title>Script info</title>
<style type="text/css">
html,body{
margin:5px;
padding:0px;
font-size:12px;
font-family:Verdana, Arial, Helvetica, sans-serif;
line-height:150%;
}
table, table td{
border:0px solid #000000;
}
table{
border-width:1px 0px 0px 1px;
border-collapse:collapse;
margin:10px auto;
}
table td{
border-width:0px 1px 1px 0px;
padding:3px;
}
a{
color:#000000;
}
</style>
</head>
<body>
<table>
<tr>
<td colspan="2" style="text-align:center;">
<strong>&copy; 2000-2007 SpellChecker.net, Inc.<br />All rights reserved.<br />
<a href="http://www.spellchecker.net/" target="_blank">www.spellchecker.net</a></strong>
</td>
</tr>
<tr>
<td><strong>Script name:</strong></td>
<td><#achk_file#></td>
</tr>
<tr>
<td><strong>Script version:</strong></td>
<td>80409.593</td>
</tr>
</table>
</body>
</html>';
	}
	function _handleXverCommand() {
		$this->oSrvVars->set ( 'mime_type', 'text/xml' );
		return '<?xml version="1.0" encoding="utf-8"?>
<info>
<script>sproxy</script>
<platform>' . SProxyUtils::__escapeXML ( $_SERVER ['SERVER_SOFTWARE'] ) . '</platform>
<version>80409.593</version>
</info>';
	}
	function _handleErrorConversionCommand() {
		$this->oSrvVars->set ( 'doc', 'error.xsl' );
		$this->oSrvVars->set ( 'cmd', 'script' );
		$sFilePath = $this->_getFilePath ();
		if (preg_match ( "/^false$/", $this->oSrvVars->get ( "no_cache" ) ) && $this->_isFileActual ( $sFilePath )) {
			return $this->_readDataFromFile ( $sFilePath );
		}
		$bIsShowInternalTemplate = false;
		$aAllParams = $this->oSrvVars->getAll ( true );
		$sParamsString = SProxyUtils::__createParamsString ( $aAllParams );
		$sResponseText = "";
		if ($this->sConnectionProtocol == 'http://') {
			$sResponseText = $this->_makeRequest ( $sParamsString );
		} elseif ($this->sConnectionProtocol == 'https://') {
			if (file_exists ( 'sproxy_https_plugin.php' )) {
				require_once ('sproxy_https_plugin.php');
				if (class_exists ( 'SProxyHTTPSPlugin' )) {
					$oPluginHTTPS = new SProxyHTTPSPlugin ( $this->oSrvVars );
					if ($oPluginHTTPS->isCURLExists ()) {
						$sResponseText = $oPluginHTTPS->makeRequest ( $sParamsString );
					}
				}
			}
		}
		if (! empty ( $sResponseText )) {
			$aResponse = SProxyUtils::__splitResponse ( $sResponseText );
			$aCacheControl = preg_grep ( "/^cache-control:\s*no-cache/i", $aResponse ['headers'] );
			$bIsCacheAllowed = (count ( $aCacheControl ) == 0) ? (true) : (false);
			if ($aResponse ['status'] == "200" && $bIsCacheAllowed) {
				if (! SProxyUtils::__writeFile ( $sFilePath, $aResponse ['content'] )) {
					$bIsShowInternalTemplate = true;
				}
			} else {
				if (! SProxyUtils::__writeFile ( $sFilePath, $this->_getDefaultErrorXSLTemplate () )) {
					$bIsShowInternalTemplate = true;
				}
			}
		} else {
			if (! SProxyUtils::__writeFile ( $sFilePath, $this->_getDefaultErrorXSLTemplate () )) {
				$bIsShowInternalTemplate = true;
			}
		}
		$fileContent = '';
		if ($bIsShowInternalTemplate == false) {
			$fileContent = $this->_readDataFromFile ( $sFilePath );
		}
		if (! empty ( $fileContent )) {
			return $fileContent;
		}
		return $this->_getDefaultErrorXSLTemplate ();
	}
	function _isFileActual($sFilePath) {
		if (file_exists ( $sFilePath ) && file_exists ( $this->sIniPath )) {
			$iLastAccessFileTime = fileatime ( $sFilePath );
			$iLastModifyFileTime = filemtime ( $sFilePath );
			$iLastModifyIniTime = filemtime ( $this->sIniPath );
			if ($iLastModifyFileTime > $iLastModifyIniTime) {
				if ((gmdate ( "Ymd", $iLastAccessFileTime ) == gmdate ( "Ymd" ))) {
					return true;
				}
				$this->oSrvVars->set ( 'change_date', gmdate ( "Ymd", $iLastModifyFileTime ) );
			}
		}
		return false;
	}
	function _doCheck() {
		$aAllParams = $this->oSrvVars->getAll ( true );
		$sParamsString = SProxyUtils::__createParamsString ( $aAllParams );
		if ($this->sConnectionProtocol == 'http://') {
			$sResponseText = $this->_makeRequest ( $sParamsString );
			if ($sResponseText !== false) {
				$this->_responseProcessing ( $sResponseText );
			}
		} elseif ($this->sConnectionProtocol == 'https://') {
			if (file_exists ( 'sproxy_https_plugin.php' )) {
				require_once ('sproxy_https_plugin.php');
				if (class_exists ( 'SProxyHTTPSPlugin' )) {
					$sResponseText = false;
					$oPluginHTTPS = new SProxyHTTPSPlugin ( $this->oSrvVars );
					if ($oPluginHTTPS->isCURLExists ()) {
						$sResponseText = $oPluginHTTPS->makeRequest ( $sParamsString );
					} else {
						$sMsgText = 'Error: Client URL Library (CURL) not installed';
						$this->_processErrors ( $sMsgText, SPROXY_ERR_CURL_NOT_INSTALLED );
						return;
					}
					if ($sResponseText !== false) {
						$this->_responseProcessing ( $sResponseText );
					} else {
						$sMsgText = 'Connection error:';
						$this->_processErrors ( $sMsgText, SPROXY_ERR_OPEN_CURL_CONNECTION );
					}
					return;
				}
			}
			$sMsgText = 'Error: SProxy HTTPS Extension  required:';
			$this->_processErrors ( $sMsgText, SPROXY_ERR_HTTPS_PLUGIN_REQUIRED );
		} else {
			$sMsgText = 'Error: specified protocol ' . $this->sConnectionProtocol . ' not supported:';
			$this->_processErrors ( $sMsgText, SPROXY_ERR_PROTOCOL_NOT_SUPPORTED );
		}
	}
	function _makeRequest($sParamsString) {
		$rSocket = null;
		$sRequestHeader = '';
		$sResponseText = '';
		$sHost = $this->oSrvVars->get ( 'ssrv_host' );
		$sPort = $this->oSrvVars->get ( 'ssrv_port' );
		$sPort = (empty ( $sPort ) ? (80) : $sPort);
		$sMethod = (empty ( $sParamsString )) ? ('GET') : ('POST');
		$rSocket = @fsockopen ( $sHost, $sPort, &$iErrorCode, &$sErrorMsg, 30 );
		if (! $rSocket) {
			$sMsgText = 'Error: Service host name is unknown';
			$this->_processErrors ( $sMsgText, SPROXY_ERR_OPEN_SOCKET_CONNECTION );
			return false;
		}
		$sRequestHeader = $sMethod . " " . $this->oSrvVars->getURI ( 'full' ) . " HTTP/1.1\r\n";
		$sRequestHeader .= "Host: " . $this->oSrvVars->getURI ( 'host' ) . "\r\n";
		$sRequestHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$sRequestHeader .= "Accept: */*\r\n";
		$sRequestHeader .= "User-Agent: " . $_SERVER ['HTTP_USER_AGENT'] . "\r\n";
		if (! empty ( $sParamsString )) {
			$sRequestHeader .= "Content-Length: " . strlen ( $sParamsString ) . "\r\n";
		}
		$sRequestHeader .= "Pragma: no-cache\r\n";
		$sCookies = '';
		foreach ( $_COOKIE as $sKey => $sValue ) {
			$sCookies .= $sKey . '=' . $sValue . '; ';
		}
		$sRequestHeader .= "Cookie: " . $sCookies . "\r\n";
		$sRequestHeader .= "Connection: close\r\n";
		$sRequestHeader .= "\r\n";
		if (! empty ( $sParamsString )) {
			$sRequestHeader .= $sParamsString . "\r\n";
		}
		fputs ( $rSocket, $sRequestHeader );
		$sResponseText = '';
		while ( ! feof ( $rSocket ) ) {
			$sResponseText .= fgets ( $rSocket, 128 );
		}
		fclose ( $rSocket );
		return $sResponseText;
	}
	function _responseProcessing($sResponseText) {
		$aResponse = SProxyUtils::__splitResponse ( $sResponseText );
		if ($aResponse ['status'] != '304' && $aResponse ['status'] != '200' && $aResponse ['status'] != '100') {
			$sMsgText = '';
			switch ($aResponse ['status']) {
				case "403" :
					$sMsgText .= 'Error: Forbidden';
					break;
				case "404" :
					$sMsgText .= 'Error: WebSpellChecker service is not found on the service host';
					break;
				case "405" :
				case "302" :
					$sMsgText .= 'Error: Specified path is absent on the service host';
					break;
				case "500" :
					$sMsgText .= 'Error: Internal Server Error';
					break;
				default :
					$sMsgText .= 'Error:';
					if (isset ( $aResponse ['headers'] [0] ) && ! empty ( $aResponse ['headers'] [0] )) {
						$sMsgText .= ' ' . trim ( $aResponse ['headers'] [0] );
					}
					break;
			}
			$this->_processErrors ( $sMsgText, SPROXY_ERR_INVALID_HTTP_RESPONSE_STATUS );
			return;
		}
		$aCacheControl = preg_grep ( "/^cache-control:\s*no-cache/i", $aResponse ['headers'] );
		$bIsCacheAllowed = (count ( $aCacheControl ) == 0) ? (true) : (false);
		if ($this->oSrvVars->get ( 'cmd' ) == 'script' && ($aResponse ['status'] == '200') && $bIsCacheAllowed) {
			$sFilePath = $this->_getFilePath ();
			if (SProxyUtils::__writeFile ( $sFilePath, $aResponse ['content'] ) === false) {
				$this->_processErrors ( 'Error: SpellChecker can`t cache file', SPROXY_ERR_UNABLE_CACHE_FILE );
			}
		} elseif ($this->oSrvVars->get ( 'cmd' ) == 'script' && $aResponse ['status'] == '304') {
		} elseif ($aResponse ['status'] != '302') { //all status codes besides redirect
			$this->_transmitResponseHeader ( $aResponse ['headers'] );
			if (isset ( $aResponse ['primaryStatus'] ) && $aResponse ['primaryStatus'] == '100') {
				header ( "Expect: 100-continue" );
			}
			print $aResponse ['content'];
			die ();
		}
	}
	function _getFilePath() {
		$sDocId = $this->oSrvVars->get ( 'doc' );
		$sFileDirPath = $this->oSrvVars->get ( 'cache_path' ) . $this->oSrvVars->get ( 'cache_dir_name' ) . '/' . $this->oSrvVars->get ( 'schema' ) . '/';
		$sFilePath = '';
		switch ($sDocId) {
			case "scayt_core" :
				$this->oSrvVars->set ( 'mime_type', 'text/javascript' );
				$sFilePath = $sFileDirPath . 'scayt_' . $this->oSrvVars->get ( 'name' ) . '.js';
				break;
			case "scayt_lang" :
				$this->oSrvVars->set ( 'mime_type', 'text/javascript' );
				$sFilePath = $sFileDirPath . 'scayt_' . $this->oSrvVars->get ( 'slang' ) . '.js';
				break;
			case "scayt_styles" :
				$this->oSrvVars->set ( 'mime_type', 'text/css' );
				$sFilePath = $sFileDirPath . 'scayt.css';
				break;
			case "image" :
				$this->oSrvVars->set ( 'mime_type', 'image/gif' );
				$sFilePath = $sFileDirPath . $this->oSrvVars->get ( 'img' ) . '.gif';
				break;
			case "wsc" :
			case "scayt" :
			case "scayt_sp_init" :
				$this->oSrvVars->set ( 'mime_type', 'text/javascript' );
				$sPluginId = $this->oSrvVars->get ( 'plugin' );
				$sFilePath = $sFileDirPath . $this->oSrvVars->get ( 'doc' ) . ((empty ( $sPluginId )) ? '' : '_' . $sPluginId) . '.js';
				break;
			case "error.xsl" :
				$this->oSrvVars->set ( 'mime_type', 'text/xsl' );
				$sFilePath = $sFileDirPath . $this->oSrvVars->get ( 'doc' );
				break;
			case "wsc_core" :
				$this->oSrvVars->set ( 'mime_type', 'text/html' );
				$sFilePath = $sFileDirPath . $this->oSrvVars->get ( 'name' ) . '.html';
				break;
		}
		return $sFilePath;
	}
	function _readDataFromFile($sFilePath) {
		$sFilePath = SProxyUtils::__convertDelimitersToUnixStyle ( $sFilePath );
		if (file_exists ( $sFilePath ) === false) {
			return '';
		}
		$sContent = SProxyUtils::__readFile ( $sFilePath );
		if (strlen ( $sContent ) == 0) {
			sleep ( 5 );
			$sContent = SProxyUtils::__readFile ( $sFilePath );
		}
		return ($sContent);
	}
	function _replacePatterns($sContent) {
		$aUniqueArray = array ();
		$aOut = array ();
		preg_match_all ( "/<\#(\w+)\#>/i", $sContent, $aOut );
		if (count ( $aOut ) == 2) {
			for($i = 0; $i < count ( $aOut [0] ); $i ++) {
				$aUniqueArray [$aOut [1] [$i]] = $aOut [0] [$i];
			}
		}
		foreach ( $aUniqueArray as $sKey => $sValue ) {
			$sContent = str_replace ( $sValue, $this->oSrvVars->get ( $sKey ), $sContent );
		}
		return $sContent;
	}
	function _processErrors($sMsgText = '', $iErrorCode) {
		$sURL = $this->oSrvVars->getURI ( 'full' );
		$sMsgText .= ' (' . $iErrorCode . ')';
		$sMimeType = ($this->oSrvVars->get ( 'cmd' ) == 'script') ? $this->oSrvVars->get ( 'mime_type' ) : 'text/xml';
		$sMsg = '';
		switch ($sMimeType) {
			case 'text/javascript' :
				$sMsgText = ereg_replace ( "\r", '', $sMsgText );
				$sMsgText = ereg_replace ( "\n", '', $sMsgText );
				$sMsg = "alert(\"" . trim ( stripslashes ( $sMsgText ) ) . "\");";
				break;
			case 'text/html' :
				$sMsg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Error Message</title>
</head>
<body>
<div>
<b>' . $sMsgText . '</b>
</div>
</body>
</html>';
				break;
			default :
				$sMsg = '<?xml version="1.0" encoding="utf-8"?>
<?xml-stylesheet type="text/xsl" href="' . $this->{'oSrvVars'}->get ( 'sproxy_url' ) . '?cmd=error_conversion"?>
<error>
<message>' . $sMsgText . '</message>
<critical>1</critical>
<command>' . $this->{'oSrvVars'}->get ( 'cmd' ) . '</command>
<ref>
<text>' . $sURL . '</text>
<url>' . $sURL . '</url>
</ref>
</error>';
				break;
		}
		$this->_printHeader ( $sMimeType );
		print $sMsg;
		die ();
	}
	function _proccessErrorLight($sMsgText = '', $iErrorCode) {
		$this->_printHeader ( "text/javascript" );
		$sMsgText .= ' (' . $iErrorCode . ')';
		$sMsgText = ereg_replace ( "\r", '', $sMsgText );
		$sMsgText = ereg_replace ( "\n", '', $sMsgText );
		$sMsg = "alert(\"" . trim ( stripslashes ( $sMsgText ) ) . "\");";
		print $sMsg;
		die ();
	}
	function _display($sFileContent) {
		if (! empty ( $sFileContent )) {
			$sMimeType = $this->oSrvVars->get ( 'mime_type' );
			$this->_printHeader ( $sMimeType );
			$this->oSrvVars->set ( 'text', SProxyUtils::__escape ( $this->oSrvVars->get ( 'text' ) ) );
			if (strpos ( $sMimeType, 'text' ) === false) {
				print $sFileContent;
			} else {
				print $this->_replacePatterns ( $sFileContent );
			}
		}
	}
	function _getIniVars($sIniPath) {
		$sIniPath = SProxyUtils::__convertDelimitersToUnixStyle ( $sIniPath );
		$aIniVars = array ();
		if (file_exists ( $sIniPath )) {
			$sIniContent = SProxyUtils::__readFile ( $sIniPath );
			$aIniVarsParamValue = preg_split ( "/\n/", $sIniContent );
			for($i = 0; $i < count ( $aIniVarsParamValue ); $i ++) {
				$sLine = $aIniVarsParamValue [$i];
				if (preg_match_all ( "/^([a-zA-Z_][a-zA-Z0-9_]*)(?:\s*=\s*)(.*?)(?:\s*)$/", $sLine, $mMatches )) {
					if (isset ( $mMatches [1] [0] ) && isset ( $mMatches [2] [0] )) {
						$aIniVars [$mMatches [1] [0]] = $mMatches [2] [0];
					}
				}
			}
		}
		return $aIniVars;
	}
	function _printHeader($sContentType) {
		header ( 'Content-Type: ' . $sContentType . '; charset=utf-8' );
		header ( 'Cache-Control: no-cache;' );
		header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
	}
	function _transmitResponseHeader($aHeaders) {
		$sCookiePattern = "/^set-cookie:/i";
		$aHeaderCookies = preg_grep ( $sCookiePattern, $aHeaders );
		$aHeaderNotCookies = preg_grep ( $sCookiePattern, $aHeaders, PREG_GREP_INVERT );
		foreach ( $aHeaderNotCookies as $sHeaderAtom ) {
			header ( $sHeaderAtom );
		}
		header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
		foreach ( $aHeaderCookies as $sCookie ) {
			$sCookie = preg_replace ( $sCookiePattern, '', $sCookie );
			$sCookie = preg_replace ( "/\n/", '', $sCookie );
			$sCookie = preg_replace ( "/\r/", '', $sCookie );
			$sCookie = trim ( $sCookie );
			$aCookieParamValuePair = explode ( ';', $sCookie );
			$aCookieAtoms = array ();
			foreach ( $aCookieParamValuePair as $sPair ) {
				$aAtom = explode ( '=', $sPair );
				if (isset ( $aAtom [0] ) && isset ( $aAtom [1] )) {
					$sKey = trim ( $aAtom [0] );
					if (! empty ( $sKey )) {
						$sValue = trim ( $aAtom [1] );
						$aCookieAtoms [$sKey] = $sValue;
					}
				}
			}
			if (count ( $aCookieAtoms ) > 0) {
				$aCookieAtomsKeys = array_keys ( $aCookieAtoms );
				$sCookieName = $aCookieAtomsKeys [0];
				$sCookieValue = $aCookieAtoms [$sCookieName];
				$sCookieExpire = false;
				if (isset ( $aCookieAtoms ['expires'] ) && ! empty ( $aCookieAtoms ['expires'] )) {
					$iTimeStamp = strtotime ( $aCookieAtoms ['expires'] );
					if ($iTimeStamp === - 1) {
						$iTimeStamp = time ();
					}
					$sCookieExpire = $iTimeStamp;
				}
				$sCookiePath = false;
				if (isset ( $aCookieAtoms ['path'] ) && ! empty ( $aCookieAtoms ['path'] )) {
					$sCookiePath = $aCookieAtoms ['path'];
				}
				$sCookieDomain = false;
				if (isset ( $aCookieAtoms ['domain'] ) && ! empty ( $aCookieAtoms ['domain'] )) {
					$sCookieDomain = $aCookieAtoms ['domain'];
				}
				if ($sCookieDomain) {
					setcookie ( $sCookieName, $sCookieValue, $sCookieExpire, $sCookiePath, $sCookieDomain );
				} elseif ($sCookiePath) {
					setcookie ( $sCookieName, $sCookieValue, $sCookieExpire, $sCookiePath );
				} elseif ($sCookieExpire) {
					setcookie ( $sCookieName, $sCookieValue, $sCookieExpire );
				} else {
					setcookie ( $sCookieName, $sCookieValue );
				}
			}
		}
	}
	function _getDefaultErrorXSLTemplate() {
		return '<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"/>
<xsl:template match="error">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="www.spellchecker.net" />
<meta name="copyright" content="&#168; 2000-2007 SpellChecker.net, Inc. All rights reserved." />
<title>WebSpellChecker: Default Error page</title>
<style type="text/css">
html,body{
background:#FFFFFF;
color:#000000;
padding:0px;
margin:0px;
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:12px;
text-align:center;
}
body{
margin:10px;
}
div.criticalError{
color:#FF0000;
}
</style>
</head>
<body>
<div>
<xsl:if test="critical/text()=1">
<xsl:attribute name="class">criticalError</xsl:attribute>
</xsl:if>
<xsl:apply-templates select="message" />
</div>
</body>
</html>
</xsl:template>
<xsl:template match="message">
<xsl:value-of select="text()" />
</xsl:template>
</xsl:stylesheet>';
	}
}
?>
<?php

$oSProxy = new SProxy ();
