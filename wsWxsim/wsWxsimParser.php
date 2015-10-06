<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;
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
$pageName	= 'wsWxsimParser.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.01 2015-03-18';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------------------------
# 3.01 2015-03-18 2.7 beta release version 
#-----------------------------------------------------------------------------------------
#  first get cache filetime if it exist
#
$cacheUOM	= $uomsTo[0].$uomsTo[1].$uomsTo[2].$uomsTo[3].$uomsTo[4].$uomsTo[5];
$from		= array ('/','&deg;',' ','.');
$cacheFile 	= $cacheFolder.str_replace ($from, '',$pageName.'_'.$lang.'_'.$cacheUOM);;
$cacheTime 	= 0;
if (file_exists($cacheFile)){	
	$cacheTime = filemtime($cacheFile);
}
#  check if input file exist and compare file time with cache
#
if (!file_exists($plaintext)){
	echo '<h3 style="text-align; center;">Error  input file not found '.$plaintext.'<h3>'.PHP_EOL;
	$wxsimERROR	= true;
	return;
}
$fileTime       = filemtime($plaintext);
if ($fileTime < $cacheTime) {
	$wsWxsimPlain   = array();
	echo '<!-- Cached file '.$cacheFile.' will be loaded. filetime: '.$fileTime.' cachetime: '.$cacheTime.' -->'.PHP_EOL;
	$wsWxsimPlain   = unserialize(file_get_contents($cacheFile));
#	print_r ($wsWxsimPlain); exit;
	return;
}
#-------------------------------------------------------------------------------------
#  cache to old or not there, so we have to process plaintext.txt
#
echo '<!-- Newer file '.$plaintext.' will be loaded. filetime: '.$fileTime.' cachetime: '.$cacheTime.' -->'.PHP_EOL;
#
$doPrint        = false;	// set plaintext-parser.php to process but not print
$saveTemp       = $uomTemp;	// save current value of $uomTemp as plaintext-parser.php needs to know what the input file temp unit is
$uomTemp        = $uoms[0];	// set to temp unit expected in input. IMPORTANT  should be set correctly in wsWxsimSettings.php
#
include($scriptFolder."plaintext-parser.php");  // load the saratoga (slightly modified) plaintext parser
#
$uomTemp  = $saveTemp;		// reset uomTemp to the template value
#
$wsWxsimPlain = array();	// output array, we are going to save this to the cache so that we do not process plaintext every time
For ($i = 0; $i	< count($WXSIMday); $i++) {  // for every group of parsed fields make one array entry 
	$wsWxsimPlain[$i]['dayPart']	        = $WXSIMdayUT[$i];
	if ($i == 0) {		// save some general values
		$wsWxsimPlain[0]['updateTxt']	= $WXSIMupdated;        // translated
		$wsWxsimPlain[0]['updateInt']	= $d;			// int
		$wsWxsimPlain[0]['city']	= $WXSIMcity;
	}
	$wsWxsimPlain[$i]['icon']		= $WXSIMicon[$i];	
	if (isset ($SITE['textLowerCase']) && $SITE['textLowerCase']) {
		$wsWxsimPlain[$i]['time']	= strtolower($WXSIMday[$i]);
		$wsWxsimPlain[$i]['text']	= strtolower($WXSIMtext[$i]);	// this is the original text part for this day translated to the requested lang
		$wsWxsimPlain[$i]['cond']	= strtolower($WXSIMcond[$i]);
	} else {
		$wsWxsimPlain[$i]['time']	= $WXSIMday[$i];
		$wsWxsimPlain[$i]['text']	= $WXSIMtext[$i]	;	// this is the original text part for this day translated to the requested lang
		$wsWxsimPlain[$i]['cond']	= $WXSIMcond[$i];	
	}
	$wsWxsimPlain[$i]['temp']		= wsConvertTemperature($WXSIMtempClean[$i], $uoms[0]);
	if ($WXSIMhumidex[$i] <> '') {
		$wsWxsimPlain[$i]['humidex']	= wsConvertTemperature($WXSIMhumidex[$i], $uoms[0]);
	} else {
		$wsWxsimPlain[$i]['humidex']	= '';
	}
	if ($WXSIMwindchill[$i] <> '') {
		$wsWxsimPlain[$i]['chill']	= wsConvertTemperature($WXSIMwindchill[$i], $uoms[0]);
	} else {
		$wsWxsimPlain[$i]['chill']	= '';
	}
	if ($WXSIMwindchill[$i] <> '') {
		$wsWxsimPlain[$i]['heat']	= wsConvertTemperature($WXSIMheatidx[$i], $uoms[0]);
	} else {
		$wsWxsimPlain[$i]['heat']	= '';
	}
	$wsWxsimPlain[$i]['frost']		= $WXSIMfrost[$i];
	$wind 					= explode ('-',$WXSIMwind[$i]);		// 14-30&rarr;20-34    or 11&rarr;19-30
	$wind1	                                = $wind[0];
	if (wsFound($WXSIMwind[$i],'&rarr;') ) { 
		$arr 	                        = explode ('&rarr;',$WXSIMwind[$i]);
		$wind 	                        = explode ('-',$arr[1]);
		if ($wind[0] > $wind1) {$wind1  = $wind[0];}
	}
	echo '<!-- PP wind = '.$WXSIMwind[$i].' result wind = '.$wind1. 'units = '.$WXSIMwindunits[$i].' -->'.PHP_EOL;
	if (wsFound($WXSIMwindunits[$i],'kp') ) {$WXSIMwindunits[$i] = 'kmh';}
	if (wsFound($WXSIMwindunits[$i],'km') ) {$WXSIMwindunits[$i] = 'kmh';}
	$wsWxsimPlain[$i]['windSpeed']	        = wsConvertWindspeed($wind1, $WXSIMwindunits[$i]);
	$wsWxsimPlain[$i]['gust']		= wsConvertWindspeed($WXSIMgust[$i], $WXSIMwindunits[$i]);
	$wsWxsimPlain[$i]['windDir']	        = $WXSIMwinddiricon[$i];
	$from					= array ('&lt;','mm.', 'cm.', 'in.', 'mm', 'cm', 'in','&gt;', '+');
	$string 				= str_replace ($from, '', $WXSIMprecip[$i]);
	$wsWxsimPlain[$i]['rain']		= wsConvertRainfall($string, $uoms[1]);
	$wsWxsimPlain[$i]['rainExtra']	        =  '';
	if (wsFound ($WXSIMprecip[$i], '&lt;') ) {$wsWxsimPlain[$i]['rainExtra'] = langtransstr('&lt;');}
	if (wsFound ($WXSIMprecip[$i], '&gt;') ) {$wsWxsimPlain[$i]['rainExtra'] = langtransstr('&gt;');}
	$string 				= str_replace ($from, '', $WXSIMsnow[$i]);	
	$wsWxsimPlain[$i]['snow']		= wsConvertRainfall($string, $uoms[4],$SITE['uomSnow']);
	$wsWxsimPlain[$i]['snowExtra']	        =  '';
	if (wsFound ($WXSIMsnow[$i], '&lt;') ) {$wsWxsimPlain[$i]['snowExtra'] = langtransstr('&lt;');}
	if (wsFound ($WXSIMsnow[$i], '&gt;') ) {$wsWxsimPlain[$i]['snowExtra'] = langtransstr('&gt;');}		
	$wsWxsimPlain[$i]['pop']		= $WXSIMpop[$i];
	$wsWxsimPlain[$i]['UV']			= $WXSIMuv[$i];
}
# save array in cache
#echo '<pre> date '.$d.PHP_EOL; print_r($wsWxsimPlain); exit;
$fileTime       = time();		// save the time we processed this file and saved to cache
if (!file_put_contents($cacheFile, serialize($wsWxsimPlain))){   
	echo PHP_EOL."<!-- Could not save data to cache $cacheFile. Please make sure your cache directory exists and is writable. -->".PHP_EOL;
} else {
	echo "<!-- weatherdata ($cacheFile) saved to cache  -->".PHP_EOL;
	}
?>