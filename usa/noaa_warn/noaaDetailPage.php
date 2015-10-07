<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'noaaDetailPage.php';	
$pageVersion	= '3.20 2015-07-19';
#-----------------------------------------------------------------------
# 3.20 2015-07-19 release 2.8 version 
#----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#------------------------------------------------------------------------
include 'noaaArrays.php';			// all constants
echo '
<div class="blockDiv">
<h3 class="blockHead">'.langtransstr('noaa warnings details').'</h3>'.PHP_EOL;
if ($arrNoaaWarnings['general']['msg'] == 'no warnings') {  // there are no warnings 
	echo '<!-- no warnings in order --><br />
<p style="font-weight: bold; margin: 2px 0px 2px 0px; min-height: 0px; text-align: center;">'.langtransstr($arrNoaaWarnings['general']['title']).'</p><br />
<!-- end warnings -->
</div>'.PHP_EOL;
	return;
}
$style_label	= 'width: 100px; font-weight: bold;  text-align: right; ';
#
$countWarns = count($arrNoaaWarnings['warn']);
if ($countWarns > 1) {echo PHP_EOL.'<div class="tabber">'.PHP_EOL;}
#
for ($i = 0; $i < $countWarns; $i++) {
	$message	= $arrNoaaWarnings['warn'][$i]['event'];
	$link		= $arrNoaaWarnings['warn'][$i]['link'];
	if ($countWarns > 1) {
		echo '<div class="tabbertab" style="padding: 0;">'.PHP_EOL;
		echo '<h3>'. langtransstr($message).'</h3>'.PHP_EOL;
	}
	$arrAlert 	= array();
	$detail 	= new noaaDetailAlarm ();
	$arrNoaaDetailWarnings 	= $detail->getNoaaDetailAlarm ($link);  
# echo '<pre>detail page'; print_r ($arrNoaaDetailWarnings); exit;
	echo '
<table style="width: 100%; background-color: #e0e0e0; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; border-spacing: 4px;">
<tbody>
<tr>
  <td style="'.$style_label.' vertical-align: top;">Message:</td>
  <td><small>'.$arrNoaaDetailWarnings['identifier'].'<br />from '.$arrNoaaDetailWarnings['sender'].'</small></td>
</tr>
<tr>
  <td style="'.$style_label.'">Sent:</td>
  <td>'.$arrNoaaDetailWarnings['sent'].'</td>
</tr>
<tr>
  <td style="'.$style_label.'">Effective:</td>
  <td>'.$arrNoaaWarnings['warn'][$i]['from'].'</td>
</tr>
<tr>
  <td style="'.$style_label.'">Expires:</td>
  <td>'.$arrNoaaWarnings['warn'][$i]['until'].'</td>
</tr>
<tr>
  <td colspan="2">
    <table style="background-color: #ffffff; width: 100%; padding: 5px; border: 1px solid black;">
      <tbody>
      <tr>
        <td style="'.$style_label.'">Event:</td>
        <td style="font-weight: bold;">'.$arrNoaaWarnings['warn'][$i]['event'].'</td>
     </tr>
     <tr>
        <td style="'.$style_label.' vertical-align: top;">Alert:</td>
        <td style="vertical-align: top;"><pre style="font-size: 1.2em; margin: 1px;">'.$arrNoaaDetailWarnings['description'].'</pre></td>
     </tr>'.PHP_EOL;
	if ($arrNoaaDetailWarnings['instruction'] <> '') {
		echo
'     <tr>
        <td style="'.$style_label.' vertical-align: top;">Instructions:</td>
        <td style="font-size: 1.2em;">'.$arrNoaaDetailWarnings['instruction'].'</td>
      </tr>'.PHP_EOL;
	} 
	$string = str_replace (';','<br />',$arrNoaaWarnings['warn'][$i]['areaDesc']);
	echo 
'     <tr>
        <td style="'.$style_label.' vertical-align: top;">Target Area:</td>
        <td style=" font-weight: bold; background-color: #e0e0e0;">'.$string.'</td>
      </tr>
      </tbody>
    </table>
  </td>
</tr>
<tr>
  <td style="'.$style_label.'">Forecast Office:</td>
  <td>'.$arrNoaaDetailWarnings['senderName'].'</td>
</tr>
</tbody>
</table>'.PHP_EOL;
	if ($countWarns > 1) { echo '</div>'.PHP_EOL;}
} // eo for every warning
if ($countWarns > 1) {
	echo '</div>
<script type="text/javascript" src="javaScripts/tabber.js"></script>'.PHP_EOL;
}
echo '</div>'.PHP_EOL;
#
return; 
#----------------------------------------------------------------------
class noaaDetailAlarm {
	private $url		= 'http://';  	// will receive the full URL to retrieve  
#				   http://alerts.weather.gov/cap/wwacapget.php?x=OR1253B463ECCC.HeatAdvisory.1253B4828C40OR.PQRNPWPQR.da8e2f96733bfc2193bd065fed3dbaf8
	private $enableCache    = false;	
	private $cachePath	= 'cache/';
	private $cacheExtra	= 'noaa_details/';	
	private $cacheTime 	= 600; 		// Cache expiration time Default: 300 seconds = advised by noaa nws.eu
	private $cache_file	= '';
	private $timeFormat     = 'd.m.Y h:i';	// 28.07.2012 05:00 CET 
	private $timeOnlyFormat = 'H:i';
	private $fullUrl 	= '';		// URL should become http://alerts.weather.gov/cap/wwaatmget.php?x=CAZ095&y=0
	private $rawData	= '';
#-------------------------------------------------------------------------------
# public function getNoaaDetailAlarm	
#-------------------------------------------------------------------------------
	public function getNoaaDetailAlarm($url = '') {
		global $SITE, $pageName, $noaaEvents, $noaaEventIcons;
		$this->timeFormat 	= $SITE['timeFormat']; 
		$this->timeOnlyFormat 	= $SITE['timeOnlyFormat'];
		$this->cachePath	= $SITE['cacheDir'].$this->cacheExtra;
		$url 			= trim($url);  // user input by call of this function
		if (strlen($url) <= 10) {
			echo '<!-- ERROR in:'.$pageFile.' - invalid warnarea given: '.$url.' -->'.PHP_EOL;
		}
		$this->fullUrl		= $url;
#-------------------------------------------------------------------------------
# check if data (for this location) is in cache
#-------------------------------------------------------------------------------
		$return			= $this->loadFromCache();
		if (!empty($return)) {		// if data is in cache and valid return data to calling program
			return $return;
		}  // eo return to calling program
#-------------------------------------------------------------------------------
		if (!$this->makeRequest()){	// errors during retrieve
			return false;
		}	
		$this->rawData 			= str_replace('cap:','cap_',$this->rawData);			
		$xml 				= new SimpleXMLElement($this->rawData);
		$returnArray['identifier']	= (string) $xml->identifier;
		$returnArray['sender']		= (string) $xml->sender;
		$unixFrom			= strtotime ((string) $xml->sent);
		$returnArray['sent']		= date($SITE['timeFormat'],$unixFrom);
		$returnArray['description']	= (string) $xml->info->description;
		$returnArray['instruction']	= (string) $xml->info->instruction;
		$returnArray['senderName']	= (string) $xml->info->senderName;
#echo '<pre>'; print_r ($returnArray); exit;
		$this->writeToCache($returnArray);
		$this->rawData  = '';
		return $returnArray;
	}  // eof getNoaaDetailAlarm
#-------------------------------------------------------------------------------
# private function loadFromCache  ! normaly not used as old data will fill cache rapidly	
#-------------------------------------------------------------------------------
	private function loadFromCache(){
		global $SITE, $pageName;
		if ($this->enableCache == false) { 
			return false;
		}
		$string 		= $pageName.'_'.$this->url;
		$from 			= array('/',':','http','?','=','.');
		$this->cache_file	= $this->cachePath .str_replace ($from,'',$string);
		if (file_exists($this->cache_file)){	
			$file_time = filectime($this->cache_file);
			$now 		= time();
			$diff 		= ($now-$file_time);		
			if ($diff <= $this->cacheTime){
				echo "<!-- Severe weatherdata ($this->cache_file) loaded from cache -->".PHP_EOL;
				$returnArray =  unserialize(file_get_contents($this->cache_file));
				return $returnArray;
			}
		}	
	} // eof loadFromCache
#-------------------------------------------------------------------------------
# private function writeToCache  ! normaly not used as old data will fill cache rapidly	
#-------------------------------------------------------------------------------
	private function writeToCache($data){
		if ($this->enableCache == false)        {
			return true;
		}
		if (!file_put_contents($this->cache_file, serialize($data))){   
			echo PHP_EOL."<h3> Could not save data to cache $this->cache_file. Please make sure your cache directory exists and is writable. </h3>".PHP_EOL;
		} 
		else {echo "<!-- Severe weatherdata ($this->cache_file) saved to cache  -->".PHP_EOL;
		}
	} // eof writeToCache
#-------------------------------------------------------------------------------
# private function makeRequest  
#-------------------------------------------------------------------------------
	private function makeRequest(){	
		global $SITE;
		if ('test' == 'none') {
			$testFile = 'usa/noaa_warn/noaaWarnDetail.xml';
			echo "<!-- TESTING   Severe weatherdata loaded from test file: $testFile  -->".PHP_EOL;
			$this->rawData  = file_get_contents($testFile);
		} 
		else {	echo "<!-- Severe weatherdata loaded from $this->fullUrl  -->".PHP_EOL;
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_URL, $this->fullUrl);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
			$this->rawData = curl_exec ($ch);
			curl_close ($ch);
		}
		if (empty($this->rawData)){
			echo "<!-- ERROR Severe weatherdata empty,($this->fullUrl)  could not be loaded  -->".PHP_EOL;
			return false;
		}
		$dataError 	= strpos($this->rawData, 'Service Unavailable' ,0);	
		$dataDetect	= strpos($this->rawData, 'InformationToHelpDiagnose:' ,0);
		if($dataError > 0  || $dataDetect > 0) {   // error string found
			echo "<!-- ERROR Severe weatherdata ($this->fullUrl) Service Unavailable  -->".PHP_EOL;
			return false;
		}
		$search 	= substr($this->url,-20);
		$dataOK 	= strpos($this->rawData, $search ,0);	
#		echo $this->rawData; exit;
		if(!$dataOK) {   // no good data found
			echo "<!-- ERROR Severe weatherdata ($this->fullUrl) Service returns no good data -->".PHP_EOL;
			return false;
		}		
		return true;	
	} // eof makeRequest
} // eo class
# ----------------------  version history
# 3.20 2015-07-19 release 2.8 version 

