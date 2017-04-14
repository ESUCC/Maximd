<?php
class SProxyHTTPSPlugin
{
var $oSrvVars = null;
var $rCURL = null;
function SproxyHTTPSPlugin($oSrvVars){
$this->oSrvVars = $oSrvVars;
}
function isCURLExists(){
return extension_loaded('curl');
}
function makeRequest($sParamsString){
$sMethod = (empty($sParamsString))?('GET'):('POST');
$rCurl = curl_init();
curl_setopt($rCurl, CURLOPT_URL, $this->oSrvVars->getURI('full'));
curl_setopt($rCurl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($rCurl, CURLOPT_HEADER, 1);
curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($rCurl, CURLOPT_TIMEOUT, 30);
curl_setopt($rCurl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($rCurl, CURLOPT_SSL_VERIFYHOST, 0);
$aHeadersList = $this->_getHTTPHeaders($sMethod);
curl_setopt($rCurl, CURLOPT_HTTPHEADER, $aHeadersList);
if($sMethod === 'POST'){
curl_setopt($rCurl, CURLOPT_POST, 1);
curl_setopt($rCurl, CURLOPT_POSTFIELDS, $sParamsString);
}
$sResponseText = curl_exec($rCurl);
$sErr = curl_error($rCurl);
curl_close($rCurl);
if(!empty($sErr)){
return false;
}
return $sResponseText;
}
function _getHTTPHeaders($sMethod){
$aRequestHeader = array();
$aRequestHeader[] = $sMethod . " " . $this->oSrvVars->getURI('full') . " HTTP/1.1";
$aRequestHeader[] = "Host: " . $this->oSrvVars->getURI('host');
$aRequestHeader[] = "Content-Type: application/x-www-form-urlencoded";
$aRequestHeader[] = "Accept: */*";
$aRequestHeader[] = "User-Agent: " .$_SERVER['HTTP_USER_AGENT'];
$aRequestHeader[] = "Pragma: no-cache";
$sCookies = '';
foreach ($_COOKIE as $sKey => $sValue){
$sCookies .= $sKey . '=' . $sValue . '; ';
}
$aRequestHeader[] = "Cookie: " . $sCookies;
$aRequestHeader[] = "Connection: close";
return $aRequestHeader;
}
}
