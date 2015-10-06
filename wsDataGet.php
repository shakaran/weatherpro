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
#
$pageName	= 'wsDataGet.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.20 2015-09-28  Calgary version';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ( '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->");
#-----------------------------------------------------------------------
# 3.20 2015-09-05 release 2.8 version = 2.7 + missing WD-CCN + 24 hours light/dark
# ----------------------------------------------------------------------
#
#               get current weather values and yesterday values 
$fileToLoad     = $SITE['wsTags'];
$useUpload	= true;
$uoms		= $uomTemp.$uomBaro.$uomWind.$uomRain.$uomSnow.$uomDistance.$uomPerHour.$uomHeight;
$from		= array('/',' ','&deg;','.php','.htm','.txt','.');
$to		= '';
$cachedFile	= $SITE['cacheDir'].str_replace ($from, $to, $fileToLoad.$uoms);  // add uoms
#
if (file_exists($fileToLoad)){
        $fileTimeUpload	= filemtime($fileToLoad); } 
else {  $fileTimeUpload	= 11;   // no upload found, set date time to nill to force use of cache
        ws_message (  '<!-- module wsDataGet.php ('.__LINE__.'):  no upload of tags file  '.$fileToLoad.' found - try cache -->');
}
if ($save_to_cache &&  file_exists($cachedFile) ){
	$fileTimeCache	= filemtime($cachedFile); 
	if ($fileTimeCache > $fileTimeUpload) {
		ws_message (  '<!-- module wsDataGet.php ('.__LINE__.'):
	cache =	     = '.$cachedFile.' 
        data file    = '.$fileToLoad.'
        cache time   = '.date('c',$fileTimeCache).' from unix time '.$fileTimeCache.'
        data  time   = '.date('c',$fileTimeUpload).' from unix time '.$fileTimeUpload.'  -->');	
		$useUpload	= false;
		ws_message (  '<!-- module wsDataGet.php ('.__LINE__.'): '.$fileToLoad.' older than cached file, cache used  -->');
	} 
	else {  ws_message (  '<!-- module wsDataGet.php ('.__LINE__.'): '.$fileToLoad.' will be loaded, more recent than cached file.  -->');
	}
}
switch ($useUpload) {
  case true:
	ws_message (  '<!-- module wsDataGet.php ('.__LINE__.'): loading '.$SITE['process'].' -->');
	include($SITE['process']);				// current weatherdata
	if (isset ($SITE['wsYTags'])  && $SITE['wsYTags']  <> 'no') {
		ws_message (  '<!--  module wsDataGet.php ('.__LINE__.'): loading '.$SITE['wsYTags'].' -->');
		include($SITE['wsYTags']); }   		        // yesterday values 
	if (!isset ($ws['actTime']) ) {
		$useUpload = false;				// check if correct file was read
		ws_message (  '<!--  module wsDataGet.php ('.__LINE__.'): uploaded file incorrect - reverting to cached file -->');
	        $ws 	= unserialize(file_get_contents($cachedFile));
	        ws_message (  '<!--  module wsDataGet.php ('.__LINE__.'): loading tag files from '.$cachedFile.' -->');
	        if (!isset ($ws['actTime']) ) { 
	        	exit ('<h3 style="text-align: center;">module wsDataGet.php: loading '.$SITE['process'].' - uploaded file incorrect<br /> 
reverting to cached file - loading tag files from '.$cachedFile.' - no valid tags file found <br />
program halts </h3>'); 
		}
		break; 
	}
# save site information in ws array
	if (!$save_to_cache) {break;}
#	
        $ws['fileTime'] = $fileTimeUpload;
#
        if (!isset ($wsTrends) ) { 
                ws_message (  '<!-- module wsDataGet.php ('.__LINE__.'): no trends to cache -->');
        } else { $ws['trendsExist'] = $cacheTrends = $cachedFile.'_trends';       
                if (!file_put_contents($cacheTrends, serialize($wsTrends)) ) {
                      	exit ('<h3 style="text-align: center;">Module wsDataGet.php: Could not save data (trends) to cache ('.$cacheTrends.').<br />Please make sure your cache directory exists and is writable.<br />Program ends<h3>');
                } 
                else  { ws_message (  '<!-- module wsDataGet.php ('.__LINE__.'): data (trends) saved to cache ('.$cacheTrends.')  -->');
                }
        }
#
        if (!file_put_contents($cachedFile, serialize($ws)) )
                { exit ('<h3 style="text-align: center;">module wsDataGet.php:- Could not save tags from '.$ws['actTime'].' to cache ('.$cachedFile.'). Please make sure your cache directory exists and is writable.</h3>');} 
        else    { ws_message (   '<!-- module wsDataGet.php: tags from '.$ws['actTime'].' - saved to cache ('.$cachedFile.')  -->');}
#
   break;
   default:
	$ws = unserialize(file_get_contents($cachedFile));
	ws_message (   '<!-- module wsDataGet.php ('.__LINE__.'): loading tag files from '.$cachedFile.' -->');
	if (!isset ($ws['actTime']) ) { exit ('<h3>no valid tags file found - program halts </h3>'); }
} // eo switch
if (isset ($realtime) && $realtime) {return;}
# 
# now calculate missing items for dashboard - day night switch and so on
#
$nowInt		= time();
$now		= date($SITE["timeOnlyFormat"],$nowInt);
$lat		= $SITE['latitude'];
$long		= $SITE['longitude'];
$sunriseInt	= date_sunrise($nowInt, SUNFUNCS_RET_TIMESTAMP, $lat, $long);   // standard time integer
$sunsetInt	= date_sunset ($nowInt, SUNFUNCS_RET_TIMESTAMP, $lat, $long);
$one_day        = 24*60*60;
if ($sunriseInt == false || $sunsetInt == false) {	// 24 hours dark or 24 hours light
	$string		= date('c',$nowInt);  #2004-02-12T15:19:21+00:00
	list($today)	= explode ('T',$string);
	$today00	= strtotime($today.'T00:00:00');
	$today24	= $today00 + $one_day;
	$this_month	= date ('m',$nowInt);
	if ($this_month > 5 && $this_month < 9 && $SITE['latitude'] > 0) {$summer = true;} else {$summer = false;}
	if ($sunriseInt == false && $summer == true) 	{$sunriseInt = $today00;} 
	if ($sunriseInt == false && $summer == false) 	{$sunriseInt = $today24;} 		
	if ($sunsetInt == false  && $summer == true)	{$sunsetInt  = $today24;}	
	if ($sunsetInt == false  && $summer == false)	{$sunsetInt  = $today00;}	
}
$sunriseIntYday	= date_sunrise(($nowInt - $one_day), SUNFUNCS_RET_TIMESTAMP, $lat, $long);   // standard time integer
$sunsetIntYday	= date_sunset (($nowInt - $one_day), SUNFUNCS_RET_TIMESTAMP, $lat, $long);
if ($sunriseIntYday == false || $sunsetIntYday == false) {
	$string		= date('c',$nowInt - $one_day);  #2004-02-12T15:19:21+00:00
	list($yday)	= explode ('T',$string);
	$yday00		= strtotime($yday.'T00:00:00');
	$yday24		= $yday00 + $one_day;
	$this_month	= date ('m',$nowInt);
	if ($this_month > 5 && $this_month < 9 && $SITE['latitude'] > 0) {$summer = true;} else {$summer = false;}
	if ($sunriseIntYday == false && $summer == true) 	{$sunriseIntYday = $yday00;} 
	if ($sunriseIntYday == false && $summer == false) 	{$sunriseIntYday = $yday24;}  		
	if ($sunsetIntYday  == false  && $summer == true)	{$sunsetIntYday  = $yday24;} 	
	if ($sunsetIntYday  == false  && $summer == false)	{$sunsetIntYday  = $yday00;} 		
}
$daylight       = abs($sunsetInt - $sunriseInt);
$daylightYday   = abs($sunsetIntYday - $sunriseIntYday);
$daylight_hrs   = floor ($daylight/3600);
$daylight_min   = floor ( ($daylight - (3600 * $daylight_hrs) ) / 60  );    
$daylight_txt   = langtransstr('Daylight hh:mm') .' '.$daylight_hrs.':'.substr('0'.$daylight_min,-2);
$less_more      = round ( ($daylight - $daylightYday) / 60 );
#echo "daylight = $daylight  - daylightYday = $daylightYday".PHP_EOL; exit;
if (abs($less_more) == 1) 
     	{$lessmoreTxt = langtransstr('minute'); } 
else 	{$lessmoreTxt = langtransstr('minute');}
if ($less_more < 0) 
        {$daylight_trend        =  ' ' .$less_more.' '.$lessmoreTxt;} 
else    {$daylight_trend        =  '+ '.$less_more.' '.$lessmoreTxt;} 
$ws['daylight_text']            = $daylight_txt;
$ws['daylight_trend']           = $daylight_trend;
$ws['daylight']                 = $ws['daylight_text'] .' ('. $ws['daylight_trend'].')';
#
$sunrise	= $ws['sunrise']	= date($SITE["timeOnlyFormat"],$sunriseInt);
$sunset		= $ws['sunset']		= date($SITE["timeOnlyFormat"],$sunsetInt);

if (isset ($ws['moonrise'])  && $ws['moonrise'] <> 0){
	$string = ' Thu, 21 Dec 2000 '.$ws['moonrise'];
	$ws['moonrise'] = date($SITE["timeOnlyFormat"],strtotime($string));}
#
if (isset ($ws['moonset'])  && $ws['moonset'] <> 0){
	$string = ' Thu, 21 Dec 2000 '.$ws['moonset'];
	$ws['moonset'] = date($SITE["timeOnlyFormat"],strtotime($string));}
#
if (($nowInt >= $sunriseInt) && ($nowInt <= $sunsetInt)) {
	$dayNight = 'daylight';} 
else {  $dayNight = 'nighttime';}
#
ws_message (  "<!-- module wsDataGet.php (".__LINE__."): time  is $nowInt => $now  | it is  $dayNight | sunrise at $sunriseInt => $sunrise  | sunset at  $sunsetInt => $sunset -->");
ws_message (  '<!-- module wsDataGet.php ('.__LINE__.'): loading  wsIconUrl.php -->');
include_once    'wsIconUrl.php';
#---------------------- and now get the current conditions from some source --------------
ws_message ( '<!-- module wsDataGet.php ('.__LINE__.'): $SITE["curCondFrom"] = '.$SITE['curCondFrom'].'  -->');
#
switch ($SITE['curCondFrom']) {	
	case 'yahoo':
		ws_message (  '<!-- module wsDataGet.php ('.__LINE__.'): loading  yahoo.weather2.php -->');
                include_once('forecasts/yahoo.weather2.php');
                $weather 	= new yahooWeather();
                $yahooArray	= $weather->getWeatherData($SITE['yaPlaceID']);
                $condDesc       = cleanCurCond($yahooArray['ccn']['1']['text']);
                $ccnIconNr	= $iconIn 	= $yahooArray['ccn']['1']['icon'];
                $ccnIconUrl	= $urlIn	= $yahooArray['ccn']['1']['iconUrl'];
                wsChangeIcon ('yahoo',$iconIn, $ccnIconNr, $urlIn, $ccnIconUrl);		
                $headerClass    = wsHeaderLookup ('yahoo',$iconIn);
	break;
	case 'wd':
		if (isset ($ws['wdCurCond']) && isset ($ws['wdCurIcon'])) {
			$condDesc       = cleanCurCond($ws['wdCurCond']);
			$ccnIconNr	= $ccnIconUrl = $urlIn	= $notUsed	= '';
			wsChangeIcon ('wd',$ws['wdCurIcon'], $ccnIconNr, $urlIn, $ccnIconUrl);
			$headerClass= wsHeaderLookup ('wd',$ws['wdCurIcon']);	
			break;
		}
	default:
	        $script = $SITE['metarDir'].'wsMetarTxt.php';
		ws_message (  '<!-- module wsDataGet.php ('.__LINE__.'): loading '.$script.' -->');
                include $script;
                $mtr   = mtr_conditions($SITE["METAR"]);
                $extra  = $long_text      = '';
                if (isset ($mtr['conditions']) && $mtr['conditions'] <> '' ) {
                        $end     = count ($mtr['conditions']);
                        for ($n1 = 0; $n1 < $end; $n1++) {
                                $text = $mtr['conditions'][$n1];
                                if (trim($text) == 'Clear'  && $dayNight <> 'nighttime') {
                                        $text = 'Sunny';
                                }
                                $text           = langtransstr($text);
                                $long_text     .= $extra.$text;
                                $extra          = ', ';
                        }
                }
                if (isset ($mtr['covers_max']) && $mtr['covers_max'] <> '') {
                        $text           = $mtr['covers_max'];
                        if (trim($text) == 'Clear'  && $dayNight <> 'nighttime') {
                                $text = 'Sunny';
                        }
                        $text           = langtransstr($mtr['covers_max']);
                        $long_text     .= $extra.$text;
                }
                $condDesc       = $long_text ;
                $icon   = $mtr['max-icon'];
                if ($dayNight == 'nighttime') {
                        $icon   .='n';
                }
                $iconNrPr = $icon;
                wsChangeIcon ('default',$icon, $icon, '', $ccnIconUrl);
                $ccnIconNr      = $iconNrPr;
                $headerClass    = wsHeaderLookup ('default',$iconNrPr);
# added for calgary 
                $ws['myMetarVisib'] = $ws['visibility_prefix'] = $ws['visibility_sm'] = '';
                if (isset ($mtr['visibility_prefix']) ) {
                        $ws['myMetarVisib']     = langtransstr ($mtr['visibility_prefix']).' ';
                        $ws['visibility_prefix']= $mtr['visibility_prefix'];
                } 
                if (isset ($mtr['visibility_sm']) ) {
                        $ws['myMetarVisib']    .= $mtr['visibility_sm'];
                        $ws['visibility_sm']    = $mtr['visibility_sm'];
                }                     
# end added for calgary	
	break;
}  // eo switch
if ( ($SITE['wuKey'] == '' || $SITE['wuKey'] == 'none' || $SITE['wuKey'] == 'false' || $SITE['wuKey'] == false)  && $SITE['wuMember'] == false) {  
        ws_message (   '<!-- module wsDataGet.php ('.__LINE__.'): no almanac loaded as wuKey is not set -->');
}
else {  ws_message (   '<!-- module wsDataGet.php ('.__LINE__.'): loading wualmanac.php -->');
        include './wuforecast/wualmanac.php';
}
return;
#-----------------------------------------------------------------------------------------
function cleanCurCond($words) {                 // used for WD and Yahoo
	$arrTxt = explode ('/',$words);		// AM Light Snow/ Drizzle late
	$cndTxt = '';
	$iEnd   = count($arrTxt)-1;
	for ($i2 = 0; $i2 <= $iEnd; $i2++ ) {
	        $text   = $arrTxt[$i2];
		if ($text <> '') {
			if (substr($text,0,2) == 'AM') {
				$cndTxt .= langtransstr('Morning'). ': ';
				$text   = substr($text,2);
			}
			if (substr($text,0,2) == 'PM') {
				$cndTxt .= langtransstr('Afternoon'). ': ';
				$text   = substr($text,2);
			}
			if (substr($text,-5) == ' Late') {
				$cndTxt .= langtransstr('Late'). ': ';
				$text   = substr($text,0,strlen($text)-5);
			}				
			if (substr($text,0,2) == 'PM') {
				$cndTxt.= langtransstr('Afternoon'). ': ';
				$text   = substr($text,2);
			}			
			$cndTxt         .=langtransstr($text);
			if ($iEnd > $i2) { $cndTxt  .= '<br />';}
		}
	}
	return( $cndTxt);
}
# ----------------------  version history
# 3.20 2015-09-05 release 2.8 version 
