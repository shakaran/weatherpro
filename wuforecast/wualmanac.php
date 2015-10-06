<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
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
$pageName	= 'wualmanac.php';
$pageVersion	= '3.20 2015-09-15';
#-------------------------------------------------------------------------------
# 3.20 2015-09-15 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ( '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->");
#-------------------------------------------------------------------------------
#
$key            = $SITE['wuKey'];
$lat            = $SITE['latitude'];
$lon            = $SITE['longitude'];
$metar_almanac  = $SITE['METAR'];
#
$load_first     = 'latlon';
# extra settings when problems with lat lon data
#-------------------------------------------------------------------------------
#$load_first     = 'metar';
#$metar_almanac  = $SITE['METAR'];
#$metar_almanac  = 'ABCD';
#
#
if ($SITE['uomTemp'] == '&deg;F') 
        {$uom_almanac  = 'F';} 
else    {$uom_almanac  = 'C';}

$cacheDir       = $SITE['cacheDir'];
$cacheFile      = $cacheDir.'wu_almanac_'.$uom_almanac;
$test_cache 	= true;

if (isset ($_REQUEST['force']) && $_REQUEST['force'] == 'almanac') {
	ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): Using cached data skipped as force is used  -->',true);
	$test_cache = false;
} 
if ($test_cache && file_exists ($cacheFile) ) {
        $rawData        = file_get_contents($cacheFile);
        $arr_almanac    = unserialize ($rawData);
        $today          = date ('Ymd');
        if (isset ($arr_almanac['today']) && $arr_almanac['today'] == $today ) {
        	ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): almanac loaded from '.$cacheFile.' -->');
       		foreach ($arr_almanac as $key => $value) {
                        $ws[$key]       = $value;
                }
                return;
        }
        $arr_almanac    = array();
}
#
$load_almanac	= true;
#
$weatherApiUrl  = 'http://api.wunderground.com/api/'.$key.'/almanac/lang:EN/q/'.$lat.','.$lon.'.xml';
$fakeURL	= 'http://api.wunderground.com/api/--key--/almanac/lang:EN/q/'.$lat.','.$lon.'.xml';

$weatherApiUrl_2='http://api.wunderground.com/api/'.$key.'/almanac/lang:EN/q/'.$metar_almanac.'.xml';
$fakeURL_2	= 'http://api.wunderground.com/api/--key--/almanac/lang:EN/q/'.$metar_almanac.'.xml';
#
if (isset ($load_first) && trim($load_first) <> '' && trim($load_first) <> 'latlon') {
        ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): we will try loading almanac for metar first. -->'); 
        $weatherApiUrl  = 'http://api.wunderground.com/api/'.$key.'/almanac/lang:EN/q/'.$metar_almanac.'.xml';
        $fakeURL	= 'http://api.wunderground.com/api/--key--/almanac/lang:EN/q/'.$metar_almanac.'.xml';
        $weatherApiUrl_2= 'http://api.wunderground.com/api/'.$key.'/almanac/lang:EN/q/'.$lat.','.$lon.'.xml';
        $fakeURL_2	= 'http://api.wunderground.com/api/--key--/almanac/lang:EN/q/'.$lat.','.$lon.'.xml';
} 
else {  ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): we will try loading almanac for lat-lon first. -->'); 
        $weatherApiUrl_2= 'http://api.wunderground.com/api/'.$key.'/almanac/lang:EN/q/'.$metar_almanac.'.xml';
        $fakeURL_2	= 'http://api.wunderground.com/api/--key--/almanac/lang:EN/q/'.$metar_almanac.'.xml';
        $weatherApiUrl  = 'http://api.wunderground.com/api/'.$key.'/almanac/lang:EN/q/'.$lat.','.$lon.'.xml';
        $fakeURL 	= 'http://api.wunderground.com/api/--key--/almanac/lang:EN/q/'.$lat.','.$lon.'.xml';
}
#
ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): loading wu: '.$fakeURL.' -->');
$rawData 	= wsAlmanacDataCurl ($weatherApiUrl);
$look_for       = '<almanac>';				# if not available skip rest
$pos            = strpos ($rawData,$look_for);
if ($pos < 10) {
        ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): data not available -->'); 
        $load_almanac = false;
}
else {  $look_for       = '<airport_code></airport_code>';	# if empty, no almanac available
        $pos            = strpos ($rawData,$look_for);
        if ($pos > 10) {
                ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): data not complete -->'); 
                $load_almanac = false;
        }
}
if ($load_almanac == false) {   // try anothere way to obtain data
        $load_almanac	= true;	
        ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): loading wu: '.$fakeURL_2.' -->');
        $rawData 	= wsAlmanacDataCurl ($weatherApiUrl_2);
        $look_for       = '<almanac>';				# if not available skip rest
        $pos            = strpos ($rawData,$look_for);
        if ($pos < 10) {
                ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): data not available -->'); 
                $load_almanac = false;
        }
        else {  $look_for       = '<airport_code></airport_code>';	# if empty, no almanac available
                $pos            = strpos ($rawData,$look_for);
                if ($pos > 10) {
                        ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): data not complete -->'); 
                        $load_almanac = false;
                }
        }
}

#
if ($load_almanac) {
	$xml 		= new SimpleXMLElement($rawData);
	#
	if (!isset ($xml->almanac) ) {
	        ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): almanac not complete ? -->'); 
	        $load_almanac = false;
	}
}
if ($load_almanac) {
	#
	$almanac                = $xml->almanac;
	$arr_almanac['today']   = date ('Ymd');
	#
	if (isset ($almanac->temp_high->normal->$uom_almanac) ){
		$string         = trim($almanac->temp_high->normal->$uom_almanac);
		if ($string <> '') { $arr_almanac['normal_high']     = $string;}	
	}
	if (isset ($almanac->temp_high->record->$uom_almanac) ) {
	        $string         = (string) $almanac->temp_high->record->$uom_almanac;
	        if ($string <> '') { $arr_almanac['record_high']     = $string;}
	}
	if (isset ($almanac->temp_high->recordyear) )  {    
		$string         = (string) $almanac->temp_high->recordyear;
		if ($string <> '') { $arr_almanac['record_high_year']     = $string;}
	}
	if (isset ($almanac->temp_low->normal->$uom_almanac) )  {   
		$string         = (string) $almanac->temp_low->normal->$uom_almanac;
		if ($string <> '') { $arr_almanac['normal_low']     = $string;}
	}
	if (isset ($almanac->temp_low->record->$uom_almanac) ){
	        $string         = (string) $almanac->temp_low->record->$uom_almanac;
	        if ($string <> '') { $arr_almanac['record_low']     = $string; }
	}
	if (isset ($almanac->temp_low->recordyear) ) {
	        $string         = (string) $almanac->temp_low->recordyear;
		if ($string <> '') { $arr_almanac['record_low_year'] = $string;}
	}
	# load year ago script
}
#
if ($SITE['wuMember'] == false) { 
	ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): This station is not a member of WU -->');
	wsAlmanacSaveCache ();
	return;
}
#
$now    	= time();
$year   	= date ('Y',$now) - 1;
$month  	= date ('m',$now);
$day    	= date ('d',$now);
if ( ($month == 2) && ($day == 29) ) {$day = 28;}
list ($startDay,$startMonth,$startYear) = explode ('-', $SITE['wuStart'].'- - -');  //  ##### no checking on this ??
$start          = $startYear.$startMonth.$startDay;
$yearAgo        = $year.$month.$day;
if ($start > $yearAgo) {
	ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): '.$SITE['wuID'].'not a wu member 1 year ago station-start = '.$start.' - 1yearAgo = '.$yearAgo.' -->'); 
	wsAlmanacSaveCache ();
	return;
}    
$WUID   = trim($SITE['wuID']);
#
if ($uom_almanac  == 'F') {$wuUnits = 'english'; } else {$wuUnits = 'metric';}
$weatherApiUrl 	= 'http://www.wunderground.com/weatherstation/WXDailyHistory.asp?ID='.$WUID.'&graphspan=day&day=' .  $day . '&year=' . $year .  '&month=' . $month . '&format=1&units='.$wuUnits;
ws_message (  	'<!-- module wualmanac.php ('.__LINE__.'): loading wu: '.$weatherApiUrl.' -->');
$rawData 	= wsAlmanacDataCurl ($weatherApiUrl);
$from		= array ('<br>', '<br />');
$rawData 	= str_replace ($from,'',$rawData);
$arr    	= explode ("\n",$rawData);
$end    	= count ($arr);
if ($end < 2) {
	ws_message ('<!-- module wualmanac.php ('.__LINE__.'): No valid data returned for this station -->'); 
	wsAlmanacSaveCache ();
	return;
}
$lowTemp        = +999;
$highTemp       = -999;
$lowTempTime    = '';
$highTempTime   = '';
#
$units          = wsAlmanacFindUnits ($arr);
#
for ($n = 2; $n < $end ; $n++) {
        $line           = trim($arr [$n]);
        if ($line == '')  {continue;}
        $arrLine        = explode (',', $line);
        if (count ($arrLine) < 15 ) {continue;}
        $temp           = $arrLine[1];
        if (!is_numeric ($temp) ) {continue;}        
        if ($temp < -100 || $temp > 150) {continue;} 
        if ($temp > $highTemp) {
                $highTemp       = $temp;
                $highTempTime   = $arrLine[0];
        } 
        elseif  ($temp < $lowTemp) {
                $lowTemp        = $temp;
                $lowTempTime    = $arrLine[0];
        }
}
$unix           = strtotime ($highTempTime);
$highTempTime   = date ('YmdHj', $unix ).'00';
$unix           = strtotime ($lowTempTime);
$lowTempTime    = date ('YmdHj', $unix ).'00';
$arr_almanac['last_year_low']   = wsConvertTemperature($lowTemp, $units);
$arr_almanac['last_year_lowT']  = $lowTempTime;
$arr_almanac['last_year_high']  = wsConvertTemperature($highTemp, $units);
$arr_almanac['last_year_highT'] = $highTempTime;
#
wsAlmanacSaveCache ();
return;


# ---------------------------------------- almanac functions ---------------------------
function wsAlmanacFindUnits ($arr) {
	$units          = '';
	for ($n = 0; $n < 2; $n++) {
		$line           = trim($arr [$n]);
		if (strlen ($line) < 20 ) {continue;}
		$arr2 	= explode (',',$line.',');
		if (trim(strtolower ($arr2[0]))  == 'time') {
			if (trim(strtolower ($arr2[1]))  == 'temperaturef') {$units = 'f';} else {$units = 'c';}
			return $units;
		}
	}
	if ($units == '') {ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): units not found - set to c -->'); $units = 'c';}
	return $units;
}  // eof wsAlmanacFindUnits
#
function wsAlmanacSaveCache () {	
	global $arr_almanac, $cacheFile, $ws;
# echo '<pre>'; print_r($arr_almanac); exit;
	foreach ($arr_almanac as $key => $value) {
		$ws[$key]       = $value;
	}
	if (!file_put_contents($cacheFile, serialize($arr_almanac))){
		echo '<h3 style="text-align: center">Module wualmanac.php ('.__LINE__.'): Program halts as cache is not available.</h3>';
		exit;	
	} else {ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): ('.$cacheFile.') saved to cache -->');}
} // eof wsAlmanacSaveCache
#
function wsAlmanacDataCurl ($string) {
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_URL, $string);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 20);
	$rawData = curl_exec ($ch);
	curl_close ($ch);
	if (empty($rawData)){
		ws_message ( '<!-- module wualmanac.php ('.__LINE__.'): ERROR Weather data loaded from url  - FAILED  -->');
		return false;
	}
	return $rawData;
}
# ----------------------  version history
# 3.20 2015-09-15 release 2.8 version 
