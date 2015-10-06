<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { //--self downloader --
   $filenameReal = __FILE__;	# display source of script if requested so
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header("Content-type: text/plain");
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
$pageName	= 'noaaWarning.php';
$pageVersion	= '3.20 2015-07-17';
#-------------------------------------------------------------------------------
# 3.20 2015-07-17 release 2.8 version 
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');;
#--------------------------------------------------------------------------------------------------
$warnScriptName = $pageName;
$wrnStrings	= '';
$detail_page	= 'noaaDetailPage';
#-----------------------------------------------------------------------------------------
# Now check if we want to include warnings on every page
#-----------------------------------------------------------------------------------------
if (!$SITE['warnings'] == true) { return; }
include ('noaaArrays.php');
$wrnStrings	.= '<!-- warnings and other info goes here  -->'.PHP_EOL;
if (0 == date('I') ) {				
	$SITE['warnOffset']     = 3600; 		// wether or not we are in DST
} 
else {	$SITE['warnOffset']	= 0;			// 0 no difference between meteoalarm and real world or between handheld and pc site; 3600 = 1 hour difference
}
#
$alarm 			= new noaaAlarm ();
$arrNoaaWarnings 	= $alarm->getMeteoAlarm ($SITE['warnAreaNoaa']);
if (isset ($mobi) && $mobi) {return;}
#echo '<pre>'; print_r($arrNoaaWarnings); echo '</pre>'; #exit;
if (isset ($SITE['pages'][$detail_page]) ) {
	$wrnHref 	= '<a href="'.$SITE['pages'][$detail_page].'&amp;lang='.$lang.$extraP.$skiptopText.'">';
} 
else {	$wrnHref 	= '<a href="'.$SITE['noaawarningURL'].'">';
}
if (!$arrNoaaWarnings) {
	ws_message (  '<!-- module '.$warnScriptName.'   no warnings  retrieved -->');
	return;
}
if ($arrNoaaWarnings['general']['msg']	== 'no warnings' && !$SITE['warningGreen']) {  	// there are no warnings more severe than green and we do not display them either
	ws_message (  '<!-- module '.$warnScriptName.' no warnings in order and no green box needed  -->');
	return;
} 
if ($arrNoaaWarnings['general']['msg'] == 'no warnings' && $SITE['warningGreen']) {  	// there are no warnings more severe than green but we display a message
	$wrnStrings	.= '
<!-- no warnings in order -->
<div class="warnBox" style="background-color: '.$noaaSeverity['None']['colorCode'].';">			
	<p style="font-weight: bold; margin: 2px 0px 2px 0px; min-height: 0px;">'.langtransstr($arrNoaaWarnings['general']['title']).'</p>
</div>
<!-- end warnings -->'.PHP_EOL;
	return;
} 
$levelMax	        = 1;
$colorMax               = 'white';
$arrayLevel['Green']	= 0;
$arrayLevel['White']	= 1;
$arrayLevel['Yellow']	= 2;
$arrayLevel['Orange']	= 3;
$arrayLevel['Red']	= 4;
$count_warn             = count($arrNoaaWarnings['warn']);
for ($i = 0; $i < $count_warn ; $i++) {
	$unknown	= 'Unknown';
	$severity	= $arrNoaaWarnings['warn'][$i]['severity'];
	if ($severity == $unknown) {
		$severity 	= '-';
		$severityTxt	= '';
		$color		= $noaaSeverity[$unknown]['color'];
		$level		= '';
	} else {
		$severityTxt	= langtransstr($noaaSeverity[$severity]['explanation']);
		$color		= $noaaSeverity[$severity]['color'];
		$level		= langtransstr('level').':&nbsp;<span style="font-weight: bold;">'.langtransstr($severity);
	}
	$urgency		= $arrNoaaWarnings['warn'][$i]['urgency'];
	if ($urgency == $unknown) {
		$urgency 	= '-';
		$urgencyTxt	= '';
	} else {
		$urgencyTxt	= langtransstr($noaaUrgency[$urgency]['explanation']);
	}
	
	$types			= $arrNoaaWarnings['warn'][$i]['types'];
	if (isset ($arrayLevel[$color]) ){
	        if ($arrayLevel[$color] > $levelMax) {
	                $levelMax       = $arrayLevel[$color];
	                $colorMax       = $color;
	        }
	}
	$wrnStrings		.= '<div class="warnBox wrn'.$color.'" >'.PHP_EOL;
	$wrnStrings		.= '<table class="genericTable">'.PHP_EOL;
	if ($types == 'Thunderstorms') 					{$SITE['wrnLightning']	= true;}
	if ($types == 'Rain'  || $types == 'Snow/Ice') 	{$SITE['wrnRain'] 		= true;  $wrnSide = $types;}
	$wrnStrings		.= '
<tr>
	<td rowspan="2">'.'<img src="'.$SITE['warnImg'].$arrNoaaWarnings['warn'][$i]['img'].'" title="'.$arrNoaaWarnings['warn'][$i]['summary'].'" alt=""/></td>
	<td>'.langtransstr('from').':&nbsp;'.$arrNoaaWarnings['warn'][$i]['from'].'</td>
	<td>'.langtransstr('until').':&nbsp;'.$arrNoaaWarnings['warn'][$i]['until'].'</td>
	<td>'.'<span style="font-weight: bold;">'.$arrNoaaWarnings['warn'][$i]['event'].'</span>&nbsp;'.$level.'</span></td>
	<td rowspan="2">'.$wrnHref.'<img src="img/i_symbol.png" alt=" " /></a></td>
</tr>'.PHP_EOL;
#
	if ($severityTxt <> '' || $urgencyTxt <> '') {
		$wrnStrings	.= '<tr>
	<td colspan = "3">'.$severityTxt.' - '.$urgencyTxt.'</td>
</tr>'.PHP_EOL;
}
	if ($SITE['warningsXtnd']) {     // all weatherwarning text on every page
		$wrnStrings	.= '<tr><td colspan = "5"><small>'.$arrNoaaWarnings['warn'][$i]['summary'].'</small></td></tr>'.PHP_EOL;
	}
	$wrnStrings	.= '</table></div>'.PHP_EOL;
}  // eo for loop every warning	
$wrnStrings	.= '<!-- end warnings -->'.PHP_EOL;
if (isset ($SITE['warningDetail']) && $SITE['warningDetail'] < $count_warn ) {	
	$wrnStrings = '<div class="warnBox wrn'.$colorMax.'" >'.
langtransstr('wrnMultiple_warnings_in_effect').'&nbsp;&nbsp;
<a href="javascript:hideshow(document.getElementById(\'warnExtra\'))"><img src="img/i_symbol.png" alt=" " style="margin-top: 2px; width: 18px;"></a>
&nbsp;&nbsp;'.
langtransstr('wrnCheck_here').'
</div>
<div id="warnExtra" style="display: none;">'.$wrnStrings.'</div>
<script type="text/javascript">
  function hideshow(which){
    if (!document.getElementById)
    return
    if (which.style.display=="block")
    which.style.display="none"
    else
    which.style.display="block"
  }
</script>'.PHP_EOL;	// save detailed warnings	
}
return;
#--------------------------------------------------------------------------------------------------
# retrieve weather alarm information from noaa nws  
# and return array with retrieved data in the desired language
#--------------------------------------------------------------------------------------------------
class noaaAlarm {
	# public variables
	# private variables
	private $area		= 'CAZ095';  	// test europe  GR016   BE004   USA  CAZ095
	private $langAlarm	= 'en_US';	// only to retreive data, translated by langtransstr
	private $enableCache= true;		// cache should be anabled when frequent request are made. Keep in mind that the data is only refreshed every ????
	private $cachePath	= '../cache/';	// cache dir is created when not available
	private $cacheTime 	= 900; 		// Cache expiration time Default: 300 seconds = advised by noaa nws.eu
	private $iconUrl 	= 'http://www.meteoalarm.eu/';  //  Location of the weather icons 
	private $cache_file	= '';
	private $timeOffset	= 0;		// times on some warnpages differ from each other with this offset
	private $timeFormat 	= 'd.m.Y h:i';	// 28.07.2012 05:00 CET 
	private $timeOnlyFormat = 'H:i';
	private $dataOK		= false;
#						http://alerts.weather.gov/cap/wwaatmget.php?x=CAZ095&y=0
	private $urlPart 	= array(
		'http://alerts.weather.gov/cap/wwaatmget.php?x=',
		'$area',
		'&y=0'
	);
	private $fullUrl 	= '';
// 						URL should become http://alerts.weather.gov/cap/wwaatmget.php?x=CAZ095&y=0
	private $rawData	= '';
	private $charset	= 'ISO-8859-1'; // output charset, input is utf8
#-------------------------------------------------------------------------------
# public functions	
#-------------------------------------------------------------------------------
	public function getMeteoAlarm($area = '') {
		global $SITE, $warnScriptName, $noaaEvents, $noaaEventIcons;
		$this->charset	= $SITE['charset']; 
		#
		# check environment setting
#		if ( isset ($SITE['warnOffset']) && ($SITE['warnOffset'] > -3601) && ($SITE['warnOffset'] < 7200) ) { $this->timeOffset = $SITE['warnOffset']; } 
		if ( isset ($SITE['timeFormat']) ) 	{ $this->timeFormat 	= $SITE['timeFormat']; }
		if ( isset ($SITE['timeOnlyFormat']) )	{ $this->timeOnlyFormat = $SITE['timeOnlyFormat']; }
		#
		$this->area 		= trim($area);  	// user input by call of this function
		$this->langAlarm 	='en_US';
		$this->cachePath 	= $SITE['cacheDir'];
#-------------------------------------------------------------------------------
# check if data (for this location) is in cache
#-------------------------------------------------------------------------------
		$from			= array ('.', 'php');
		$string 		= $warnScriptName.'_'.$this->area.'_'.$this->langAlarm;
		$string 		= str_replace ($from,'',$this->cachePath .$string);
		$this->cache_file	= $string.'.txt';
		$returnArray=$this->loadFromCache();  	// load from cache returns data only when its data is valid
		if (!empty($returnArray)) {				// if data is in cache and valid return data to calling program
			return $returnArray;
		}  // eo return to calling program
#-------------------------------------------------------------------------------
# combine user constants and input (1)location (2)units for temp etc  to required url
#-------------------------------------------------------------------------------
		$this->urlPart[1]	= $this->area;
		$this->fullUrl		= '';
		for ($i = 0; $i < count($this->urlPart); $i++){
			$this->fullUrl .= $this->urlPart[$i];
		}
# echo $this->fullUrl; exit;
		#----------------------------------------------------------------------------------------------
		if (!$this->makeRequest()){ 	// no good data loaded
			return false;
		}
#						// we have to check if there are warnings
		$this->rawData = Str_replace('cap:','cap_',$this->rawData);			
		$xml 			= new SimpleXMLElement($this->rawData);
		$color			= '';
		$strNoWarn		= 'here are no active';
		$pos = strpos($this->rawData, $strNoWarn);
		if ($pos <> false) {	// no warnings this time
			$returnArray['general']['msg']		= 'no warnings';
			$returnArray['general']['title']	= (string) $xml->entry->title;
			$this->writeToCache($returnArray);
			$this->rawData  = '';
			return $returnArray;
		}
#						// we have to process every  warning
		$returnArray['general']['id']		= (string) $xml->id;
		$returnArray['general']['title']	= (string) $xml->title;
		$returnArray['general']['link']		= (string) $xml->link['href'];
		$endWarn				= count($xml->entry);
		$returnArray['general']['msg']		= (string) $endWarn;				
		for ($cntWarn = 0; $cntWarn < $endWarn; $cntWarn++) {
			$data				= 	$xml->entry[$cntWarn];
#					$returnArray[$cntWarn]['color']	= (string) $data->cap_effective;
			$unixFrom					= strtotime ((string) $data->cap_effective);
			$returnArray['warn'][$cntWarn]['from']		= date($SITE['timeFormat'],$unixFrom);
			$unixTo						= strtotime ((string) $data->cap_expires);
			$From						= date('Ymd',$unixFrom);
			$To						= date('Ymd',$unixTo);
			if ($From <> $To) {
				$returnArray['warn'][$cntWarn]['until']	=  date($SITE['timeFormat'],$unixTo);
			} 
			else {	$returnArray['warn'][$cntWarn]['until']	=  date($SITE['timeOnlyFormat'],$unixTo);
			}
			
			$event						= (string) $data->cap_event;
			$returnArray['warn'][$cntWarn]['event']		= $event;
			$type						= $noaaEvents[$event]['types'];
			$returnArray['warn'][$cntWarn]['types']		= $type;
			$returnArray['warn'][$cntWarn]['severity']	= (string) $data->cap_severity;
			$returnArray['warn'][$cntWarn]['areaDesc']	= (string) $data->cap_areaDesc;
			$returnArray['warn'][$cntWarn]['img']		= $noaaEventIcons[$type];
			$returnArray['warn'][$cntWarn]['summary']	= (string) $data->summary;
			$returnArray['warn'][$cntWarn]['link']		= (string) $data->link['href'];
			$returnArray['warn'][$cntWarn]['urgency']	= (string) $data->cap_urgency;
		} // eo for all warnings
# print_r ($xml); print_r ($returnArray);   exit;
		$this->writeToCache($returnArray);
		$this->rawData  = '';
		return $returnArray;

	}  // eof getMeteoAlarm
//
	private function loadFromCache(){
		global $cron_all, $warnScriptName;
		if (file_exists($this->cache_file)){	
			$file_time 	= filemtime($this->cache_file);
			$now 		= time();
			$diff 		= ($now - $file_time);
			ws_message (  "<!-- module $warnScriptName (".__LINE__."): ($this->cache_file) 
	cache time   =  $file_time 
	current time =  $now 
	difference   =  $diff 
	Diff allowed =  $this->cacheTime -->");
			if (isset ($cron_all) ) {		// runnig a cron job
				$this->cacheTime = 300;		// advised noaa cache time
				ws_message (  "<!-- module $warnScriptName (".__LINE__."): max cache set to 300 seconds as cron job is runnig -->");
			}	
			if ($diff <= $this->cacheTime){
				ws_message (  "<!-- module $warnScriptName (".__LINE__."): ($this->cache_file) loaded from cache -->");
				$returnArray =  unserialize(file_get_contents($this->cache_file));
				return $returnArray;
			}
		}	
	} // eof loadFromCache
	
	private function writeToCache($data){
		global $warnScriptName;
		if (!file_put_contents($this->cache_file, serialize($data))){   
			exit ("<h3>Could not save data to cache $this->cache_file. Please make sure your cache directory exists and is writable. Program halts. </h3>");
		} 
		else {	ws_message (  "<!-- module $warnScriptName (".__LINE__."): ($this->cache_file) saved to cache  -->");
		}
	} // eof writeToCache

	private function makeRequest(){	
		global $SITE, $warnScriptName;
		$test	= false;
		if ($test) {
			$testFile = 'noaa/noaaWarnCap.xml';
			ws_message (  "<!-- module $warnScriptName (".__LINE__."): TESTING   Severe weatherdata loaded from test file: $testFile  -->",true);
			$this->rawData  = file_get_contents($testFile);
		} 
		else {	ws_message (  "<!-- module $warnScriptName (".__LINE__."): loaded from $this->fullUrl  -->");
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_URL, $this->fullUrl);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $SITE['curlFollow']);
			$this->rawData = curl_exec ($ch);
			curl_close ($ch);
		}
		if (empty($this->rawData)){
			ws_message (  "<!-- module $warnScriptName (".__LINE__."): ERROR Severe weatherdata empty,($this->fullUrl)  could not be loaded  -->",true);
			$this->dataOK = false;
			return false;
		}
		$dataError 	= strpos($this->rawData, 'invalid ugc' ,0);	
		$dataDetect	= strpos($this->rawData, 'InformationToHelpDiagnose:' ,0);
		if($dataError > 0  || $dataDetect > 0) {   // error string found
			ws_message (  "<!-- module $warnScriptName (".__LINE__."): ERROR Severe weatherdata ($this->fullUrl) Service Unavailable  -->",true);
			$this->dataOK = false;
			return false;
		}
		$dataOK = strpos($this->rawData, $this->urlPart[1] ,0);	
#		echo $this->rawData; exit;
		if(!$dataOK) {   // no good data found
			ws_message (  "<!-- module $warnScriptName (".__LINE__."): ERROR Severe weatherdata ($this->fullUrl) Service returns no good data -->",true);
			$this->dataOK = false;
			return false;
		}		
		$this->dataOK = true;
		return true;	
	} // eof makeRequest
} // eo class
# ----------------------  version history
# 3.20 2015-07-17 release 2.8 version 
