<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wuweather3.php';
$pageVersion	= '3.20 2015-07-29';
#-------------------------------------------------------------------------------
# 3.20 2015-07-29 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#
$apiUrlpart = array(
	0 =>  'http://api.wunderground.com/api/',
	1 => '$wuKey',	
	2 => '/conditions/forecast10day/lang:',	
	3 => '$lang)',
	4 => '/q/',
	5 => '$location',
        6 => '.xml');

/*  At the moment only lat lon is supported.  All possibillities are:
	CA/San_Francisco	US state/city
	60290			US zipcode
	Australia/Sydney	country/city
	37.8,-122.4		latitude,longitude		// this one is used
	KJFK			airport code
	pws:KCASANFR70		PWS id
*/
$weatherApiUrl	= '';
$rawData	= '';			                        // to store retieved xml 
#----------------------------------------------------------------------------------------------
# check if data (for this location) is in cache	
#
$cacheFile	= '';
$enableCache 	= true;
#
if (isset ($_REQUEST['force']) && strtolower($_REQUEST['force']) == 'wufct') {
        ws_message (  '<!-- module wuweather3.php ('.__LINE__.'):  cache file not used, force was set',true);
}	
elseif ($enableCache && !empty($cachePath)){			// if cache is enabled
	$cachePath 	= $myCache;
	$from		= array('php', '/', ' ', ',', '.');
	$to		= array('',    '',  '',  '_', '' );
	$string		= $pageName.'_'.$lang.'_'.$userLocation;
	$string		= str_replace($from,$to,$string);
	$cacheFile	= $cachePath.$string.'.htx';	        // sometimes txt is cached by the webserver
	$returnArray    = loadFromCache($cacheFile);  		// load from cache returns data only when its data is valid
	if (!empty($returnArray)) {				// if data is in cache and valid 
		return $returnArray;				//      return data to calling program
	}  // eo valid data
}  // eo check cache
#----------------------------------------------------------------------------------------------
# combine everything into required url
#----------------------------------------------------------------------------------------------
# http://api.wunderground.com/api/4a35d63826422341/forecast10day/lang:EN/q/51.112,-113.956.xml
#
$apiUrlpart[1]  = $wuKey;
$apiUrlpart[3]  = strtoupper($lang);
if ($apiUrlpart[3] == 'DE') {$apiUrlpart[3] = 'DL';}            // Deutsch = 'de' in templates - for WU it is 'DL'
if ($apiUrlpart[3] == 'DA') {$apiUrlpart[3] = 'DK';}            // Danish  = 'da' in templates - for WU it is 'DK'
$apiUrlpart[5] = $userLocation;
$weatherApiUrl = '';
for ($i = 0; $i < count($apiUrlpart); $i++){
	$weatherApiUrl .= $apiUrlpart[$i];
}
#----------------------------------------------------------------------------------------------		
if (!wu_makeRequest()){   						// load xml from url and process
        $errors         = '<h3>load from WU failed, we try with twice as old cached data <h3>'.PHP_EOL;
        $cacheTime      = $cacheTime * 2;
	$returnArray    = loadFromCache($cacheFile);  		// load from cache returns data only when its data is valid
	if (!empty($returnArray)) {				// if data is in cache and valid 
                ws_message (  '<!-- module wuforecast3.php ('.__LINE__.'): '.$errors.' -->');
		return $returnArray;				//      return data to calling program
	}  // eo valid data
	echo $errors.PHP_EOL;	
	echo '<h3>  no data loaded </h3>'.PHP_EOL;              // if something went wrong.
	echo '<h3>  program halted </h3>'.PHP_EOL;
	$returnArray = false;
	return;
}
#echo '<pre>'.$rawData; exit;
$xml 		= new SimpleXMLElement($rawData); 	        // process xml
$returnArray 	= array();
#--------------------------------------------------------------------------------------------------
# first, get and save request info / units etc
#--------------------------------------------------------------------------------------------------
$string = '';
for ($i = 0; $i < count($xml->features->feature); $i++){
	if ($i <> 0) {$string .= '-';}
	$string .= (string) $xml->features->feature[$i];
}
$returnArray['request']['type'] 		= $string;
$string						= (string) $xml->current_observation->display_location->full;
if ($charset <> 'UTF-8') { $string              = utf8_decode($string);}
$returnArray['request']['city'] 		= $string;
$returnArray['request']['updated'] 		= (string) $xml->current_observation->observation_time;
$returnArray['request']['time']			= (string) $xml->current_observation->local_epoch;
$returnArray['request']['logo'] 		= (string) $xml->current_observation->image->url;
$returnArray['request']['link'] 		= (string) $xml->current_observation->image->link;
$returnArray['request']['m']['uomTemp'] 	= 'c';
$returnArray['request']['m']['uomDistance']     = 'km';
$returnArray['request']['m']['uomBaro'] 	= 'mb';
$returnArray['request']['m']['uomWind'] 	= 'kmh';
$returnArray['request']['m']['uomRain'] 	= 'mm';
$returnArray['request']['m']['uomSnow'] 	= 'cm';
$returnArray['request']['e']['uomTemp'] 	= 'f';
$returnArray['request']['e']['uomDistance']     = 'mi';
$returnArray['request']['e']['uomBaro'] 	= 'in';
$returnArray['request']['e']['uomWind'] 	= 'mph';
$returnArray['request']['e']['uomRain'] 	= 'in';
$returnArray['request']['e']['uomSnow'] 	= 'in';
#
#--------------------------------------------------------------------------------------------------
# get current condition descriptions
#--------------------------------------------------------------------------------------------------
$from	= array('-999.00','-999.0','0.00','-25375');	// invalid values in xml
#
$returnArray['ccn']['url'] 		= (string) $xml->current_observation->ob_url;
$returnArray['ccn']['humidity'] 	= (string) $xml->current_observation->relative_humidity;
$returnArray['ccn']['description']	= (string) $xml->current_observation->weather;
$returnArray['ccn']['windDir']		= (string) $xml->current_observation->wind_dir;
$returnArray['ccn']['windDeg']		= (string) $xml->current_observation->wind_degrees;
$returnArray['ccn']['date'] 		= (string) $xml->current_observation->local_time_rfc822;
$returnArray['ccn']['timestamp'] 	= (string) $xml->current_observation->local_epoch;
$returnArray['ccn']['baroTrend']	= (string) $xml->current_observation->pressure_trend;
$returnArray['ccn']['UV']		= (string) $xml->current_observation->UV;

$now			= time();
$sunrise		= date_sunrise ($now,SUNFUNCS_RET_TIMESTAMP, $myLatitude, $myLongitude);
$sunset  		= date_sunset  ($now,SUNFUNCS_RET_TIMESTAMP, $myLatitude, $myLongitude);
if (($now >= $sunrise) && ($now <= $sunset)) {
	$dayNight	= 'daylight';
	$icon 		= (string) $xml->current_observation->icon;
} else {
	$dayNight	= 'nighttime';				
	$icon 		= 'nt_'.(string) $xml->current_observation->icon;
}
$returnArray['ccn']['icon'] 		= $icon;
$returnArray['ccn']['dayNight'] 	= $dayNight;	
$returnArray['ccn']['iconUrl']		= $iconsUrl. $icon.$iconsExt;
# temperature C and F
$returnArray['ccn']['m']['temp'] 	= (string) $xml->current_observation->temp_c;
$returnArray['ccn']['e']['temp'] 	= (string) $xml->current_observation->temp_f;
$string 				= (string) $xml->current_observation->dewpoint_c;
if ( ($string <> 'NA') && ($string <> 'N/A') ){
$returnArray['ccn']['m']['dewp'] 	= $string;
$returnArray['ccn']['e']['dewp'] 	= (string) $xml->current_observation->dewpoint_f;
}
$string 				= (string) $xml->current_observation->windchill_c;
if ( ($string <> 'NA') && ($string <> 'N/A') ){
$returnArray['ccn']['m']['windChill']	= $string;
$returnArray['ccn']['e']['windChill']	= (string) $xml->current_observation->windchill_f;
}
$string 				= (string) $xml->current_observation->heat_index_c;
if ( ($string <> 'NA') && ($string <> 'N/A') ){
$returnArray['ccn']['m']['heatIndex']	= $string;
$returnArray['ccn']['e']['heatIndex']	= (string) $xml->current_observation->heat_index_f;
}
$string 				= (string) $xml->current_observation->feelslike_c;
if ( ($string <> 'NA') && ($string <> 'N/A') ){
$returnArray['ccn']['m']['feelslike']	= $string;
$returnArray['ccn']['e']['feelslike']	= (string) $xml->current_observation->feelslike_f;
}
# pressure 	mb (=hap) and in						= 	
$returnArray['ccn']['m']['baro'] 	= (string) $xml->current_observation->pressure_mb;	
$returnArray['ccn']['e']['baro'] 	= (string) $xml->current_observation->pressure_in;
# wind  kph  mph 
$string 				= (string) $xml->current_observation->wind_kph;
$returnArray['ccn']['m']['wind'] 	= str_replace ($from,'0',$string);

$string 				= (string) $xml->current_observation->wind_gust_kph;
$returnArray['ccn']['m']['gust']	= str_replace ($from,'0',$string);

$string 				= (string) $xml->current_observation->wind_mph;
$returnArray['ccn']['e']['wind'] 	= str_replace ($from,'0',$string);

$string 				= (string) $xml->current_observation->wind_gust_mph;
$returnArray['ccn']['e']['gust']	= str_replace ($from,'0',$string);
# visibillity km mi
$string 				= (string) $xml->current_observation->visibility_km;
if ( ($string <> 'NA') && ($string <> 'N/A') ){
$returnArray['ccn']['m']['visib']	= $string;
$returnArray['ccn']['e']['visib']	= (string) $xml->current_observation->visibility_mi;
}
if (isset($xml->current_observation->precip_today_metric)) {
	$string				= (string) $xml->current_observation->precip_today_metric;	
} else {
	$string				= (string) $xml->current_observation->precip_today_mm;
}
$returnArray['ccn']['m']['rain']	= str_replace ($from,'0',$string);

if (isset($xml->current_observation->precip_1hr_metric) ) {
	$string				= (string) $xml->current_observation->precip_1hr_metric;
} else {
	$string				= (string) $xml->current_observation->precip_1hr_mm;
}
$returnArray['ccn']['m']['rainHour']	= str_replace ($from,'0',$string);

$string					= (string) $xml->current_observation->precip_today_in;
$returnArray['ccn']['e']['rain']	= str_replace ($from,'0',$string);
$string					= (string) $xml->current_observation->precip_1hr_in;
$returnArray['ccn']['e']['rainHour']	= str_replace ($from,'0',$string);
#--------------------------------------------------------------------------------------------------
#  get text forecast
#--------------------------------------------------------------------------------------------------
$i	= 0;
$end 	= count($xml->forecast->txt_forecast->forecastdays->forecastday) - 1;
for ($i = 0; $i <= $end; $i++){
	$data	= $xml->forecast->txt_forecast->forecastdays->forecastday[$i];
	$returnArray['txt_forecast'][$i]['period']	= (string) $data->period;
	$icon						= (string) $data->icon;
	$returnArray['txt_forecast'][$i]['icon']	= $icon;
	$returnArray['txt_forecast'][$i]['icon_url']	= $iconsUrl. $icon.$iconsExt;
	$returnArray['txt_forecast'][$i]['daypart']	= (string) $data->title;
	$returnArray['txt_forecast'][$i]['pop']		= (string) $data->pop;
	
	$returnArray['txt_forecast'][$i]['m']['fcttext']= (string) $data->fcttext_metric;
	$returnArray['txt_forecast'][$i]['e']['fcttext']= (string) $data->fcttext;
}
#--------------------------------------------------------------------------------------------------
#  get detailed forecast info
#--------------------------------------------------------------------------------------------------
$i	= 0;
$end 	= count($xml->forecast->simpleforecast->forecastdays->forecastday) - 1;
for ($i = 0; $i <= $end; $i++){
	$data	= $xml->forecast->simpleforecast->forecastdays->forecastday[$i];
	$month	=substr('00'.$data->date->month,-2,2);
	$day	=substr('00'.$data->date->day,-2,2);
	$year	=substr('00'.$data->date->year,-2,2);
	$returnArray['forecast'][$i]['weekday'] 	= (string) $data->date->weekday;
	$returnArray['forecast'][$i]['date']		= (string) $data->date->year.'-'.$month.'-'.$day;	
	$returnArray['forecast'][$i]['timestamp']	= mktime('12',	'00',	'00',	$month,	$day,	$year);  // (string) $data->date->epoch; worng timezone!
	$returnArray['forecast'][$i]['icon'] 		= (string) $data->icon;
	$returnArray['forecast'][$i]['skyicon'] 	= (string) $data->skyicon;
	$returnArray['forecast'][$i]['condition']	= (string) $data->conditions;
	$returnArray['forecast'][$i]['chancerain']	= (string) $data->pop; 
	$icon						= (string) $data->icon;
	$returnArray['forecast'][$i]['iconUrl'] 	= $iconsUrl. $icon.$iconsExt;
	$string						= (string) $data->avewind->dir;
	$returnArray['forecast'][$i]['windDir']		= $string;
	if ($string <> '' && isset ($winddirstoenglish[$string]))
	     {  $returnArray['forecast'][$i]['windDirEn']	= $winddirstoenglish[$string];} 
	else {  $returnArray['forecast'][$i]['windDirEn']	= '';}
	$returnArray['forecast'][$i]['windDeg']		= (string) $data->avewind->degrees;	
	$returnArray['forecast'][$i]['windMaxDir']	= (string) $data->maxwind->dir;	 
	$returnArray['forecast'][$i]['windMaxDeg']	= (string) $data->maxwind->degrees;		
# humid
	$returnArray['forecast'][$i]['humidity']	= (string) $data->avehumidity;
	$returnArray['forecast'][$i]['humidVar']	= '';
	if (! ($data->minhumidity == 0 || $data->maxhumidity == 0) ) {
		$returnArray['forecast'][$i]['humidVar']= (string) $data->minhumidity.'-'.$data->maxhumidity;
	}
# temp
	$returnArray['forecast'][$i]['m']['tempLow']	= (string) $data->low->celsius;
	$returnArray['forecast'][$i]['m']['tempHigh'] 	= (string) $data->high->celsius;
	$returnArray['forecast'][$i]['e']['tempLow']	= (string) $data->low->fahrenheit;
	$returnArray['forecast'][$i]['e']['tempHigh'] 	= (string) $data->high->fahrenheit;
# rain
	$amount						= (string) $data->qpf_allday->mm;
	if ($amount	 == 0) {$amount			= (string) $data->qpf_day->mm + (string) $data->qpf_night->mm;}
	$returnArray['forecast'][$i]['m']['rain']	= $amount;
	$amount						= (string) $data->qpf_allday->in;
	if ($amount	 == 0) {$amount			= (string) $data->qpf_day->in + (string) $data->qpf_night->in;}
	$returnArray['forecast'][$i]['e']['rain']	= $amount;
	
	$returnArray['forecast'][$i]['m']['rainDay']	= (string) $data->qpf_day->mm;
	$returnArray['forecast'][$i]['e']['rainDay']	= (string) $data->qpf_day->in;
	
	$returnArray['forecast'][$i]['m']['rainNight']	= (string) $data->qpf_night->mm;
	$returnArray['forecast'][$i]['e']['rainNight']	= (string) $data->qpf_night->in;
# snow
	$amount						= (string) $data->snow_allday->cm;
	if ($amount	 == 0) {$amount			= (string) $data->snow_day->cm + (string) $data->snow_night->cm;}
	$returnArray['forecast'][$i]['m']['snow']	= $amount;
	$amount						= (string) $data->snow_allday->in;
	if ($amount	 == 0) {$amount			= (string) $data->snow_day->in + (string) $data->snow_night->in;}
	$returnArray['forecast'][$i]['e']['snow']	= $amount;
	
	$returnArray['forecast'][$i]['m']['snowDay']	= (string) $data->snow_day->cm;
	$returnArray['forecast'][$i]['e']['snowDay']	= (string) $data->snow_day->in;	
	$returnArray['forecast'][$i]['m']['snowNight']	= (string) $data->snow_night->cm;
	$returnArray['forecast'][$i]['e']['snowNight']	= (string) $data->snow_night->cm;
# wind	
	$returnArray['forecast'][$i]['m']['wind']	= (string) $data->avewind->kph;
	$returnArray['forecast'][$i]['e']['wind']	= (string) $data->avewind->mph;
	$returnArray['forecast'][$i]['m']['windMax']	= (string) $data->maxwind->kph;
	$returnArray['forecast'][$i]['e']['windMax']	= (string) $data->maxwind->mph;
}  // eo for loop forecast
// eo wu_makeRequest processing
#--------------------------------------------------------------------------------------------------
#  write to cache
#--------------------------------------------------------------------------------------------------
if (!isset ($returnArray['forecast']) || count($returnArray['forecast'])  < 3  ) { return $returnArray;}
if ($enableCache && !empty($cachePath)){
#print_r ($returnArray); exit;
	writeToCache($returnArray);
}		
return $returnArray;	
#--------------------------------------------------------------------------------------------------
#  functions	
#--------------------------------------------------------------------------------------------------
function loadFromCache($cacheFile){
	global $cacheTime;
	if (file_exists($cacheFile)){
		$file_time      = filemtime($cacheFile);
		$now            = time();
		$diff           = ($now - $file_time);
		ws_message (  '<!-- module wuforecast3.php ('.__LINE__.'): '.$cacheFile." 
	cache time=  $file_time - 
	current time = $now - 
	difference  =  $diff - 
	Diff allowed = $cacheTime -->");	
		if ($diff <= $cacheTime){
			ws_message (  '<!-- module wuforecast3.php ('.__LINE__.'): '.$cacheFile.' loaded from cache -->');
			$returnArray    =  unserialize(file_get_contents($cacheFile));
			return $returnArray;
		}  // eo filte time ok -> get file from cache
	}  // eo file exists
}  // eof loadFromCache
#--------------------------------------------------------------------------------------------------
function writeToCache($data){
	global $cachePath, $cacheFile;
	if (!file_exists($cachePath)){
		// attempt to make the dir
		mkdir($cachePath, 0777);
	}
	if (!file_put_contents($cacheFile, serialize($data))){
		exit ("<h3>Could not save $cacheFile. Please make sure your cache directory exists and is writable.<br />Program halts.");
	} else {ws_message (  '<!-- module wuforecast3.php ('.__LINE__.'): '.$cacheFile.' saved to cache -->');}
}
#--------------------------------------------------------------------------------------------------
function wu_makeRequest(){
	global  $rawData, $weatherApiUrl, $errors, $wuKey;
	$string = str_replace ($wuKey,'_key_',$weatherApiUrl);
        ws_message (  '<!-- module wuforecast3.php ('.__LINE__.'): retrieving wu: '.$string.' -->');
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_URL, $weatherApiUrl);
        curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
        $rawData        = curl_exec ($ch);
        curl_close ($ch);
	$dataOK = true;
	$pos    = strpos ($rawData,'<description>this key does not exist</description>');
	if ($pos <> false) {
	        $errors .= '<h3>Error reported by WU: this key does not exist<br />'.$weatherApiUrl.'<h3>'.PHP_EOL;
	        return false;
	}
	$pos    = strpos ($rawData,'<error>');
	$string = substr ($rawData,$pos+8,20);
	if ($pos <> false) {
	        $errors .=  '<h3>Error reported by WU with text: '.$string.'<br />'.PHP_EOL.$weatherApiUrl.'<br /> can not process more - sorry <h3>'.PHP_EOL;
	        return false;
	}
	$pos    = strpos ($rawData,'Internal Server Error');
	if ($pos <> false) {
	        $errors .=  '<h3>Errors reported by WU - can not process any more - sorry <h3>'.PHP_EOL;
	        $errors .=  $rawData;
	        return false;
	}
	$rawData = str_replace ('333333','', $rawData);
	if (trim($rawData ) == '') {
		return false;
	} else {		
		return true;
	}
} // eof wu_makeRequest
# ----------------------  version history
# 3.20 2015-07-29 release 2.8 version 
