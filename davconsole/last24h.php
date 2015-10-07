<?php ini_set('display_errors', 'On');   
error_reporting(E_ALL);
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'last24h.php';
$pageVersion	= '0.00 2015-06-12';
#
if (!isset($SITE)){echo "<h3>invalid call to $pageName <h3>"; exit;}	//  page to load without menu system//
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------------------------
if (!isset ($SITE['wuId']) || $SITE['wuId'] == false) {return false;}
#
$cachefile	= $pageName.'_'.$uomTemp.$uomRain.$uomBaro.$uomWind;
$from		= array('.', '&deg;', '/');
$cachefile	= $SITE['cacheDir'].str_replace($from, '', $cachefile);
if (file_exists($cachefile) ) {$filetime = filemtime($cachefile);} else {$filetime = 0;}
if ( (time() - $filetime) < 7200) {	# load from cache
	echo "<!-- wu weatherarray $cachefile loaded from cache  -->".PHP_EOL;
	$arr 		=  unserialize(file_get_contents($cachefile));
	if (is_array ($arr) ) {
		$temp	= $arr['temp'];	$tmax 	= $arr['tmax'];	$tmin 	= $arr['tmin'];
		$hum 	= $arr['hum'];	$hmax 	= $arr['hmax'];	$hmin 	= $arr['hmin'];
		$wind	= $arr['wind'];	$wmax 	= $arr['wmax'];	$wmin 	= $arr['wmin'];
		$rain	= $arr['rain'];	$rmax 	= $arr['rmax'];	$rmin 	= $arr['rmin'];
		$baro	= $arr['wind'];	$wmax 	= $arr['wmax'];	$wmin 	= $arr['wmin'];
		$solr	= $arr['solr'];	$smax 	= $arr['smax'];	$smin 	= $arr['smin'];
		return;
	} // eo is array cache
} // eo cache valid
# load from wu
$WUID   = trim($SITE['wuId']);
#
$now	= time();
$yest	= $now	- 24*3600;
$text	= date ('Y-m-d-',$now);
list ($year,$month,$day) = explode ('-',$text);
$url1	= 'http://www.wunderground.com/weatherstation/WXDailyHistory.asp?ID='.$WUID.'&graphspan=day&day=' .  $day . '&year=' . $year .  '&month=' . $month . '&format=1';
$text	= date ('Y-m-d-',$yest);
list ($year,$month,$day) = explode ('-',$text);
$url2	= 'http://www.wunderground.com/weatherstation/WXDailyHistory.asp?ID='.$WUID.'&graphspan=day&day=' .  $day . '&year=' . $year .  '&month=' . $month . '&format=1';
#echo '<pre>'.$url1.PHP_EOL.$url2; exit;
$raw 	= wuDayDataCurl ($url1);
$raw 	.= wuDayDataCurl ($url2);
$arr    = explode ("\n",$raw);
$end    = count ($arr);
#echo '<pre> lines'.$end; print_r($arr);exit;
/*
Time,TemperatureC,DewpointC,PressurehPa,WindDirection,WindDirectionDegrees,WindSpeedKMH,WindSpeedGustKMH,Humidity,HourlyPrecipMM,Conditions,Clouds,dailyrainMM,SolarRadiationWatts/m^2,SoftwareType,DateUTC
2015-06-12 00:03:00,20.1,11.7,1012.1,NE,39,1.6,4.8,59,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-11 22:03:00, 

Time,TemperatureC,DewpointC,PressurehPa,WindDirection,WindDirectionDegrees,WindSpeedKMH,WindSpeedGustKMH,Humidity,HourlyPrecipMM,Conditions,Clouds,dailyrainMM,SoftwareType,DateUTC

Time,TemperatureC,DewpointC,PressurehPa,WindDirection,WindDirectionDegrees,WindSpeedKMH,WindSpeedGustKMH,Humidity,HourlyPrecipMM,Conditions,Clouds,dailyrainMM,SolarRadiationWatts/m^2,SoftwareType,DateUTC<br>
2014-05-21 00:02:00,15.4,13.3,1011.1,WNW,294,8.0,17.7,88,10.9,,,10.9,0,weatherlink.com 1.10,2014-05-20 22:02:00,
<br>
2014-05-21 00:17:00,15.3,13.9,1011.1,West,281,1.6,11.3,90,10.9,,,10.9,0,weatherlink.com 1.10,2014-05-20 22:17:00,
<br>
2014-05-21 01:36:00,14.8,13.3,1010.4,NW,317,0.0,1.6,92,0.8,,,11.9,0,weatherlink.com 1.10,2014-05-20 23:36:00,
Array
(
    [0] => Time
    [1] => TemperatureC
    [2] => DewpointC
    [3] => PressurehPa
    [4] => WindDirection
    [5] => WindDirectionDegrees
    [6] => WindSpeedKMH
    [7] => WindSpeedGustKMH
    [8] => Humidity
    [9] => HourlyPrecipMM
    [10] => Conditions
    [11] => Clouds
    [12] => dailyrainMM
    [13] => SolarRadiationWatts/m^2	[13] => SoftwareType
    [14] => SoftwareType		[14] => DateUTC
    [15] => DateUTC
)
Array
(
    [0] => 2014-05-21 00:02:00
    [1] => 15.4
    [2] => 13.3
    [3] => 1011.1
    [4] => WNW
    [5] => 294
    [6] => 8.0
    [7] => 17.7
    [8] => 88
    [9] => 10.9
    [10] => 
    [11] => 
    [12] => 10.9
    [13] => 0
    [14] => weatherlink.com 1.10
    [15] => 2014-05-20 22:02:00
    [16] => 
)

*/
$units          = '';
$line           = trim($arr [0]);

if (strlen ($line) < 20) {$line   = trim($arr [1]);}

$arr2 = explode (',',$line.',,,');
if (trim(strtolower ($arr2[0]))  == 'time') {
	$units	= 'found'; 
	$wu_unit_temp	= str_replace ('temperature', 	'',	strtolower($arr2[1]) );
	$wu_unit_baro	= str_replace ('pressure', 	'',	strtolower($arr2[3]) );
	$wu_unit_wind	= str_replace ('windspeedgust', '',	strtolower($arr2[7]) );
	$wu_unit_rain	= str_replace ('hourlyprecip', '',	strtolower($arr2[9]) );
	if (substr(trim($arr2[13]),0,5) == 'Solar') {$sol_available = true;} else {$sol_available = false;}
} 
else  {	echo '<!-- units not found - set to decimal -->'.PHP_EOL;
	$wu_unit_temp	= 'c';
	$wu_unit_baro	= 'hpa';
	$wu_unit_wind	= 'kmh';
	$wu_unit_rain	= 'mm';
}
$lasthour	= -1;
$graph_time	= time()*1000;
$tmax		= $hmax	= $wmax	= $rmax	= $bmax	= $smax	= -100;
$tmin		= $hmin	= $wmin	= $rmin	= $bmin	= $smin	= 9999;
$tstr		= $hstr	= $wstr	= $rstr	= $bstr	= $sstr	= ']';
$t_hr		= $h_hr	= $w_hr	= $r_hr	= $b_hr	= $s_hr	= -100;	

$first		= '';	# replaced with ',' after first entries in string
$hours_found	= 0;
for ($n = $end-1; $n > 1 ; $n=$n-1) {
        $line           = trim($arr [$n]);
        if ($line == '<br>') {continue;}
        $arrLine        = explode (',', $line);
        if (count ($arrLine) < 15 ) {continue;}
        if (!is_numeric($arrLine[1]) ) {continue;}
# calc hour
	$date_time	= $arrLine[0];	# 2014-05-21 00:17:00
	list ($wudate, $wutime) = explode (' ',$date_time.'  ');
	list ($wuhour)		= explode (':',$wutime.':');
	if 	($lasthour == -1) 	{$lasthour = $wuhour;}
	elseif 	($wuhour <> $lasthour) 	{
		$lasthour 	= $wuhour;
		$graph_time	= $graph_time - 3600000;
		$temp		= wsConvertTemperature($t_hr, $wu_unit_temp);
		$tstr		= '['.$graph_time.','.$temp.']'.$first.$tstr;  
		$hstr		= '['.$graph_time.','.$h_hr.']'.$first.$hstr;
		$wind		= wsConvertWindspeed($w_hr, $wu_unit_wind);
		$wstr		= '['.$graph_time.','.$wind.']'.$first.$wstr;
		$baro		= wsConvertBaro($b_hr, $wu_unit_baro);
		$bstr		= '['.$graph_time.','.$baro.']'.$first.$bstr;
		$rain		= wsConvertRainfall($r_hr, $wu_unit_rain);
		$rstr		= '['.$graph_time.','.$rain.']'.$first.$rstr;
		if ($sol_available) {
			$sstr	= '['.$graph_time.','.$s_hr.']'.$first.$sstr;
		}
		$t_hr		= $h_hr	= $w_hr = $b_hr	= $r_hr	= $s_hr	= -100;
		$lasthour 	= $wuhour;
		$first		= ',';
		$hours_found++;
		if ($hours_found > 24) {break;}
        }
        $value   = $arrLine[1];   					# [1] => TemperatureC  
        if (is_numeric ($value) && $value > -100 && $value < 150) {  	# else skip errors in data
		if ($value > $t_hr)     {$t_hr  = $value;}
		if ($value > $tmax)     {$tmax  = $value;}
		if ($value < $tmin)     {$tmin  = $value;}
	}
	$value   = $arrLine[8];						# [8] => Humidity
        if (is_numeric ($value) && $value > -100 && $value < 150) {
 		if ($value > $h_hr)     {$h_hr  = $value;}
		if ($value > $hmax)     {$hmax  = $value;}
		if ($value < $hmin)     {$hmin  = $value;}       
        }
	$value   = $arrLine[6];						# [6] => WindSpeedKMH
        if (is_numeric ($value) && $value > -100 && $value < 250) { 
 		if ($value > $w_hr)     {$w_hr  = $value;}
		if ($value > $wmax)     {$wmax  = $value;}
		if ($value < $wmin)     {$wmin  = $value;}       
        }
	$value   = $arrLine[3];						# [3] => PressurehPa
        if (is_numeric ($value) && $value > -100 && $value < 2000) { 
 		if ($value > $b_hr)     {$b_hr  = $value;}
		if ($value > $bmax)     {$bmax  = $value;}
		if ($value < $bmin)     {$bmin  = $value;}       
        }
	$value   = $arrLine[9];						#  [9] => HourlyPrecipMM
        if (is_numeric ($value) && $value > -100 && $value < 2000) { 
 		if ($value > $r_hr)     {$r_hr  = $value;}
		if ($value > $rmax)     {$rmax  = $value;}
		if ($value < $rmin)     {$rmin  = $value;}       
        }
        if ($sol_available) {
		$value   = $arrLine[13];					#  [13] => SolarRadiationWatts/m^2
		if (is_numeric ($value) && $value > -100 && $value < 2000) { 
			if ($value > $s_hr)     {$s_hr  = $value;}
			if ($value > $smax)     {$smax  = $value;}
			if ($value < $smin)     {$smin  = $value;}       
		}
	}
} // eo for every line
$arr = array();
$arr['temp']	= '['.$tstr;
$arr['tmax']	= wsConvertTemperature($tmax, $wu_unit_temp);
$arr['tmin']	= wsConvertTemperature($tmin, $wu_unit_temp);
$arr['hum']	= '['.$hstr;
$arr['hmax']	= $hmax;
$arr['hmin']	= $hmin;
$arr['wind']	= '['.$wstr;
$arr['wmax']	= wsConvertWindspeed($wmax, $wu_unit_wind);
$arr['wmin']	= wsConvertWindspeed($wmin, $wu_unit_wind);
$arr['baro']	= '['.$bstr;
$arr['bmax']	= wsConvertBaro($bmax, $wu_unit_baro);
$arr['bmin']	= wsConvertBaro($bmin, $wu_unit_baro);
$arr['rain']	= '['.$rstr;
$arr['rmax']	= wsConvertRainfall($rmax, $wu_unit_rain);
$arr['rmin']	= wsConvertRainfall($rmin, $wu_unit_rain);
$arr['solr']	= '['.$sstr;
$arr['smax']	= $smax;
$arr['smin']	= $smin;

 $string = serialize($arr);
if (file_put_contents($cachefile, $string)){
	echo "<!-- wu weatherarray $cachefile saved to cache  -->".PHP_EOL;
}
else {	echo PHP_EOL."<!-- 
WARNING Could not save data ($cachefile) to cache (".$SITE['cacheDir']."). 
WARNING Please make sure your cache directory exists and is writable. -->".PHP_EOL;
}

$temp	= $arr['temp'];	
$hum 	= $arr['hum'];	
$wind	= $arr['wind'];
$rain	= $arr['rain'];
$baro	= $arr['wind'];	
$solr	= $arr['solr'];	
return;
#

function wuDayDataCurl ($string) {
$raw = 'Time,TemperatureC,DewpointC,PressurehPa,WindDirection,WindDirectionDegrees,WindSpeedKMH,WindSpeedGustKMH,Humidity,HourlyPrecipMM,Conditions,Clouds,dailyrainMM,SolarRadiationWatts/m^2,SoftwareType,DateUTC
2015-06-12 00:03:00,20.1,11.7,1012.1,NE,39,1.6,4.8,59,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-11 22:03:00, 
2015-06-12 00:18:00,19.9,11.7,1012.1,ENE,71,1.6,4.8,60,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-11 22:18:00, 
2015-06-12 00:33:00,19.7,11.7,1012.1,NE,46,1.6,6.4,61,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-11 22:33:00, 
2015-06-12 00:48:00,19.5,11.7,1012.1,ENE,60,0.0,1.6,62,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-11 22:48:00, 
2015-06-12 01:03:00,19.1,11.7,1012.1,ENE,59,0.0,1.6,63,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-11 23:03:00, 
2015-06-12 01:18:00,18.8,11.7,1011.7,ENE,59,0.0,0.0,64,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-11 23:18:00, 
2015-06-12 01:34:00,18.4,11.7,1011.7,ENE,59,0.0,0.0,66,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-11 23:34:00, 
2015-06-12 01:49:00,18.0,12.2,1011.7,ENE,59,0.0,0.0,68,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-11 23:49:00, 
2015-06-12 02:05:00,17.7,11.7,1011.7,ENE,59,0.0,0.0,69,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 00:05:00, 
2015-06-12 02:20:00,17.4,12.2,1011.4,ENE,59,0.0,1.6,71,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 00:20:00, 
2015-06-12 02:35:00,17.3,12.2,1011.4,ENE,59,0.0,1.6,72,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 00:35:00, 
2015-06-12 02:50:00,17.0,12.2,1011.1,ENE,61,0.0,1.6,73,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 00:50:00, 
2015-06-12 03:05:00,16.9,12.2,1011.1,ENE,57,0.0,3.2,74,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 01:05:00, 
2015-06-12 03:15:00,16.9,12.2,1011.1,ENE,57,1.6,3.2,75,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 01:15:00, 
2015-06-12 03:30:00,16.9,12.2,1011.1,ENE,59,0.0,3.2,74,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 01:30:00, 
2015-06-12 03:46:00,16.8,12.2,1011.1,NE,44,0.0,1.6,75,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 01:46:00, 
2015-06-12 04:01:00,16.7,12.2,1010.7,NE,44,0.0,1.6,75,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 02:01:00, 
2015-06-12 04:17:00,16.4,12.2,1010.7,NE,44,0.0,0.0,76,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 02:17:00, 
2015-06-12 04:32:00,16.2,12.2,1010.7,NE,44,0.0,0.0,77,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 02:32:00, 
2015-06-12 04:46:00,15.9,12.2,1010.7,NE,44,0.0,3.2,78,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 02:46:00, 
2015-06-12 05:02:00,15.9,12.2,1010.4,NE,44,0.0,3.2,78,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 03:02:00, 
2015-06-12 05:32:00,15.9,12.2,1010.4,NE,47,0.0,3.2,79,0.0,,,0.0,7,weatherlink.com 1.10,2015-06-12 03:32:00, 
2015-06-12 05:47:00,16.1,12.2,1010.0,ENE,78,0.0,4.8,79,0.0,,,0.0,12,weatherlink.com 1.10,2015-06-12 03:47:00, 
2015-06-12 06:03:00,16.2,12.2,1010.0,ENE,69,0.0,4.8,78,0.0,,,0.0,21,weatherlink.com 1.10,2015-06-12 04:03:00, 
2015-06-12 06:18:00,16.3,12.8,1010.0,NNE,19,0.0,1.6,78,0.0,,,0.0,32,weatherlink.com 1.10,2015-06-12 04:18:00, 
2015-06-12 06:33:00,16.5,12.8,1010.0,ENE,73,1.6,3.2,78,0.0,,,0.0,40,weatherlink.com 1.10,2015-06-12 04:33:00, 
2015-06-12 06:49:00,16.7,12.8,1010.0,ESE,105,0.0,6.4,78,0.0,,,0.0,93,weatherlink.com 1.10,2015-06-12 04:49:00, 
2015-06-12 07:04:00,17.2,13.3,1010.0,East,94,0.0,3.2,77,0.0,,,0.0,155,weatherlink.com 1.10,2015-06-12 05:04:00, 
2015-06-12 07:19:00,17.9,13.9,1010.0,North,355,1.6,4.8,76,0.0,,,0.0,137,weatherlink.com 1.10,2015-06-12 05:19:00, 
2015-06-12 07:34:00,18.3,13.3,1010.0,NE,42,0.0,3.2,74,0.0,,,0.0,93,weatherlink.com 1.10,2015-06-12 05:34:00, 
2015-06-12 07:50:00,18.5,13.9,1009.7,ENE,76,1.6,3.2,74,0.0,,,0.0,128,weatherlink.com 1.10,2015-06-12 05:50:00, 
2015-06-12 08:05:00,19.0,13.9,1009.7,NNE,13,4.8,6.4,73,0.0,,,0.0,274,weatherlink.com 1.10,2015-06-12 06:05:00, 
2015-06-12 08:20:00,19.3,13.9,1009.7,NNE,33,3.2,4.8,71,0.0,,,0.0,81,weatherlink.com 1.10,2015-06-12 06:20:00, 
2015-06-12 08:51:00,20.2,14.4,1010.0,ESE,103,3.2,8.0,70,0.0,,,0.0,383,weatherlink.com 1.10,2015-06-12 06:51:00, 
2015-06-12 09:06:00,21.3,15.0,1010.0,ESE,105,0.0,4.8,68,0.0,,,0.0,441,weatherlink.com 1.10,2015-06-12 07:06:00, 
2015-06-12 09:22:00,22.3,15.0,1010.4,South,183,3.2,9.7,64,0.0,,,0.0,515,weatherlink.com 1.10,2015-06-12 07:22:00, 
2015-06-12 09:37:00,22.8,15.6,1010.0,NE,34,3.2,3.2,64,0.0,,,0.0,515,weatherlink.com 1.10,2015-06-12 07:37:00, 
2015-06-12 09:52:00,23.0,15.6,1010.4,SSW,192,1.6,4.8,64,0.0,,,0.0,603,weatherlink.com 1.10,2015-06-12 07:52:00, 
2015-06-12 10:08:00,23.2,15.6,1010.0,ENE,76,1.6,4.8,62,0.0,,,0.0,178,weatherlink.com 1.10,2015-06-12 08:08:00, 
2015-06-12 10:23:00,22.9,16.1,1010.0,NE,38,6.4,6.4,65,0.0,,,0.0,230,weatherlink.com 1.10,2015-06-12 08:23:00, 
2015-06-12 10:38:00,22.9,16.1,1009.7,ENE,68,0.0,6.4,65,0.0,,,0.0,350,weatherlink.com 1.10,2015-06-12 08:38:00, 
2015-06-12 10:53:00,23.6,16.1,1009.7,NE,55,1.6,6.4,63,0.0,,,0.0,536,weatherlink.com 1.10,2015-06-12 08:53:00, 
2015-06-12 11:08:00,24.3,16.1,1009.4,ESE,104,3.2,4.8,60,0.0,,,0.0,315,weatherlink.com 1.10,2015-06-12 09:08:00, 
2015-06-12 11:24:00,24.7,16.1,1009.4,ESE,104,3.2,4.8,60,0.0,,,0.0,963,weatherlink.com 1.10,2015-06-12 09:24:00, 
2015-06-12 11:39:00,25.8,15.6,1009.0,SSE,148,0.0,8.0,53,0.0,,,0.0,353,weatherlink.com 1.10,2015-06-12 09:39:00, 
2015-06-12 11:49:00,25.5,15.0,1009.0,ENE,76,3.2,9.7,52,0.0,,,0.0,308,weatherlink.com 1.10,2015-06-12 09:49:00, 
2015-06-12 12:04:00,25.6,15.0,1009.0,NE,39,1.6,8.0,53,0.0,,,0.0,327,weatherlink.com 1.10,2015-06-12 10:04:00, 
2015-06-12 12:19:00,25.7,15.6,1008.7,SSW,213,0.0,8.0,54,0.0,,,0.0,626,weatherlink.com 1.10,2015-06-12 10:19:00, 
2015-06-12 12:35:00,26.6,15.6,1008.7,NE,43,1.6,9.7,51,0.0,,,0.0,566,weatherlink.com 1.10,2015-06-12 10:35:00, 
2015-06-12 12:50:00,26.8,16.1,1008.7,NE,38,1.6,4.8,52,0.0,,,0.0,432,weatherlink.com 1.10,2015-06-12 10:50:00, 
2015-06-12 13:06:00,27.2,17.2,1008.7,SW,217,0.0,3.2,54,0.0,,,0.0,510,weatherlink.com 1.10,2015-06-12 11:06:00, 
2015-06-12 13:21:00,28.1,17.8,1008.4,NE,49,1.6,8.0,53,0.0,,,0.0,983,weatherlink.com 1.10,2015-06-12 11:21:00, 
2015-06-12 13:36:00,28.4,17.8,1008.7,WSW,241,1.6,4.8,52,0.0,,,0.0,668,weatherlink.com 1.10,2015-06-12 11:36:00, 
2015-06-12 13:51:00,28.8,18.3,1008.4,South,178,0.0,9.7,53,0.0,,,0.0,515,weatherlink.com 1.10,2015-06-12 11:51:00, 
2015-06-12 14:06:00,28.7,18.3,1008.4,SW,232,1.6,6.4,54,0.0,,,0.0,990,weatherlink.com 1.10,2015-06-12 12:06:00, 
2015-06-12 14:22:00,29.1,18.3,1008.4,South,178,1.6,8.0,53,0.0,,,0.0,918,weatherlink.com 1.10,2015-06-12 12:22:00, 
2015-06-12 14:37:00,29.4,18.3,1008.4,NW,312,1.6,6.4,52,0.0,,,0.0,387,weatherlink.com 1.10,2015-06-12 12:37:00, 
2015-06-12 14:52:00,29.3,18.9,1008.0,WSW,248,3.2,11.3,54,0.0,,,0.0,853,weatherlink.com 1.10,2015-06-12 12:52:00, 
2015-06-12 15:08:00,30.1,18.3,1007.7,SE,137,0.0,9.7,50,0.0,,,0.0,782,weatherlink.com 1.10,2015-06-12 13:08:00, 
2015-06-12 15:23:00,30.6,17.8,1007.7,ENE,59,3.2,8.0,47,0.0,,,0.0,663,weatherlink.com 1.10,2015-06-12 13:23:00, 
2015-06-12 15:38:00,30.8,17.8,1007.7,SW,233,3.2,11.3,45,0.0,,,0.0,775,weatherlink.com 1.10,2015-06-12 13:38:00, 
2015-06-12 15:54:00,30.3,17.2,1007.3,West,281,6.4,12.9,46,0.0,,,0.0,443,weatherlink.com 1.10,2015-06-12 13:54:00, 
2015-06-12 16:09:00,30.2,16.1,1007.3,SW,217,3.2,14.5,42,0.0,,,0.0,325,weatherlink.com 1.10,2015-06-12 14:09:00, 
2015-06-12 16:25:00,29.8,15.0,1007.7,SW,233,6.4,12.9,41,0.0,,,0.0,341,weatherlink.com 1.10,2015-06-12 14:25:00, 
2015-06-12 16:40:00,29.2,13.9,1007.3,SW,226,3.2,8.0,39,0.0,,,0.0,193,weatherlink.com 1.10,2015-06-12 14:40:00, 
2015-06-12 16:55:00,28.6,13.9,1007.3,West,259,3.2,8.0,40,0.0,,,0.0,156,weatherlink.com 1.10,2015-06-12 14:55:00, 
2015-06-12 17:11:00,28.3,14.4,1007.0,SW,232,3.2,9.7,42,0.0,,,0.0,283,weatherlink.com 1.10,2015-06-12 15:11:00, 
2015-06-12 17:26:00,28.4,14.4,1007.0,SW,228,4.8,9.7,42,0.0,,,0.0,246,weatherlink.com 1.10,2015-06-12 15:26:00, 
2015-06-12 17:41:00,28.3,13.9,1006.7,SSE,163,0.0,9.7,42,0.0,,,0.0,295,weatherlink.com 1.10,2015-06-12 15:41:00, 
2015-06-12 17:56:00,28.7,14.4,1006.7,South,179,3.2,8.0,42,0.0,,,0.0,552,weatherlink.com 1.10,2015-06-12 15:56:00, 
2015-06-12 18:12:00,28.7,15.6,1006.3,SSW,213,4.8,9.7,45,0.0,,,0.0,450,weatherlink.com 1.10,2015-06-12 16:12:00, 
2015-06-12 18:22:00,29.0,16.1,1006.0,SSE,151,0.0,9.7,45,0.0,,,0.0,411,weatherlink.com 1.10,2015-06-12 16:22:00, 
2015-06-12 18:37:00,29.0,15.6,1006.0,South,182,1.6,8.0,44,0.0,,,0.0,381,weatherlink.com 1.10,2015-06-12 16:37:00, 
2015-06-12 18:52:00,29.3,15.6,1006.0,East,95,0.0,6.4,44,0.0,,,0.0,418,weatherlink.com 1.10,2015-06-12 16:52:00, 
2015-06-12 19:08:00,29.5,15.6,1006.0,SE,138,0.0,9.7,43,0.0,,,0.0,151,weatherlink.com 1.10,2015-06-12 17:08:00, 
2015-06-12 19:23:00,29.1,16.1,1006.0,ESE,103,0.0,6.4,45,0.0,,,0.0,378,weatherlink.com 1.10,2015-06-12 17:23:00, 
2015-06-12 19:38:00,29.4,16.1,1006.0,SW,236,1.6,6.4,44,0.0,,,0.0,169,weatherlink.com 1.10,2015-06-12 17:38:00, 
2015-06-12 19:54:00,29.2,16.1,1006.0,SSE,157,0.0,4.8,46,0.0,,,0.0,134,weatherlink.com 1.10,2015-06-12 17:54:00, 
2015-06-12 20:09:00,28.6,16.7,1006.3,West,263,3.2,11.3,48,0.0,,,0.0,114,weatherlink.com 1.10,2015-06-12 18:09:00, 
2015-06-12 20:25:00,28.0,16.7,1006.3,SSE,156,0.0,4.8,50,0.0,,,0.0,74,weatherlink.com 1.10,2015-06-12 18:25:00, 
2015-06-12 20:40:00,27.5,16.7,1006.3,SSW,200,0.0,6.4,52,0.0,,,0.0,81,weatherlink.com 1.10,2015-06-12 18:40:00, 
2015-06-12 20:55:00,27.1,17.2,1006.3,WSW,245,0.0,6.4,54,0.0,,,0.0,47,weatherlink.com 1.10,2015-06-12 18:55:00, 
2015-06-12 21:11:00,26.7,16.7,1006.7,SW,217,1.6,8.0,55,0.0,,,0.0,26,weatherlink.com 1.10,2015-06-12 19:11:00, 
2015-06-12 21:26:00,26.3,16.7,1006.7,SW,227,1.6,4.8,56,0.0,,,0.0,21,weatherlink.com 1.10,2015-06-12 19:26:00, 
2015-06-12 21:42:00,25.9,16.7,1006.7,SSW,196,0.0,1.6,57,0.0,,,0.0,11,weatherlink.com 1.10,2015-06-12 19:42:00, 
2015-06-12 21:57:00,25.7,16.7,1006.7,South,183,1.6,3.2,58,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 19:57:00, 
2015-06-12 22:12:00,25.3,16.7,1007.0,SSE,160,0.0,1.6,59,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 20:12:00, 
2015-06-12 22:28:00,24.8,16.7,1007.0,South,171,0.0,1.6,61,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 20:28:00, 
2015-06-12 22:43:00,24.4,16.7,1007.0,South,171,0.0,1.6,62,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 20:43:00, 
2015-06-12 22:58:00,24.2,16.7,1007.0,South,185,0.0,1.6,63,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 20:58:00, 
2015-06-12 23:13:00,23.7,16.7,1007.0,SSW,192,0.0,3.2,64,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 21:13:00, 
2015-06-12 23:28:00,23.2,16.7,1007.0,SW,228,0.0,3.2,66,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 21:28:00, 
2015-06-12 23:44:00,22.9,16.7,1007.0,SW,228,0.0,1.6,67,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 21:44:00, 
2015-06-12 23:54:00,22.7,16.1,1007.0,WSW,240,0.0,4.8,67,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 21:54:00, 
Time,TemperatureC,DewpointC,PressurehPa,WindDirection,WindDirectionDegrees,WindSpeedKMH,WindSpeedGustKMH,Humidity,HourlyPrecipMM,Conditions,Clouds,dailyrainMM,SolarRadiationWatts/m^2,SoftwareType,DateUTC
2015-06-13 00:09:00,22.5,16.1,1007.0,WSW,240,0.0,1.6,68,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 22:09:00, 
2015-06-13 00:24:00,22.2,16.1,1007.0,WSW,240,0.0,1.6,69,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 22:24:00, 
2015-06-13 00:44:00,21.7,16.1,1006.7,SW,217,1.6,6.4,71,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 22:44:00, 
2015-06-13 00:59:00,21.8,16.1,1007.0,SW,226,3.2,11.3,70,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 22:59:00, 
2015-06-13 01:10:00,21.8,16.1,1007.0,WSW,243,3.2,11.3,70,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 23:10:00, 
2015-06-13 01:25:00,21.4,16.1,1007.0,WSW,240,1.6,8.0,72,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 23:25:00, 
2015-06-13 01:40:00,21.0,16.1,1007.0,SW,217,0.0,8.0,73,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 23:40:00, 
2015-06-13 01:55:00,20.6,15.6,1007.0,SW,215,1.6,6.4,74,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-12 23:55:00, 
2015-06-13 02:10:00,19.9,15.6,1007.0,SW,232,1.6,4.8,76,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 00:10:00, 
2015-06-13 02:26:00,19.5,15.6,1007.3,SW,235,0.0,12.9,77,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 00:26:00, 
2015-06-13 02:41:00,19.2,15.0,1007.3,West,273,6.4,9.7,77,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 00:41:00, 
2015-06-13 02:56:00,19.1,15.0,1007.3,WSW,243,9.7,12.9,78,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 00:56:00, 
2015-06-13 03:11:00,18.8,15.0,1007.3,SW,224,6.4,12.9,78,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 01:11:00, 
2015-06-13 03:26:00,18.6,15.0,1007.7,West,271,8.0,12.9,79,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 01:26:00, 
2015-06-13 03:41:00,18.3,15.0,1007.7,West,275,0.0,16.1,80,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 01:41:00, 
2015-06-13 03:56:00,17.9,14.4,1007.7,SW,214,3.2,12.9,81,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 01:56:00, 
2015-06-13 04:11:00,17.7,14.4,1007.7,South,189,4.8,17.7,82,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 02:11:00, 
2015-06-13 04:27:00,17.7,15.0,1007.3,SW,227,1.6,9.7,83,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 02:27:00, 
2015-06-13 04:42:00,17.5,14.4,1007.7,SSW,213,3.2,14.5,83,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 02:42:00, 
2015-06-13 04:57:00,17.4,14.4,1007.7,SW,222,3.2,11.3,83,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 02:57:00, 
2015-06-13 05:12:00,17.4,14.4,1007.7,South,190,4.8,16.1,83,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 03:12:00, 
2015-06-13 05:28:00,17.4,14.4,1007.7,SW,235,9.7,11.3,83,0.0,,,0.0,0,weatherlink.com 1.10,2015-06-13 03:28:00, 
2015-06-13 05:43:00,17.3,14.4,1008.0,WNW,285,17.7,17.7,83,0.0,,,0.0,5,weatherlink.com 1.10,2015-06-13 03:43:00, 
2015-06-13 05:58:00,16.9,13.9,1007.7,SSW,213,6.4,11.3,83,0.0,,,0.0,12,weatherlink.com 1.10,2015-06-13 03:58:00, 
2015-06-13 06:13:00,16.7,13.9,1008.0,SW,214,1.6,17.7,83,0.0,,,0.0,28,weatherlink.com 1.10,2015-06-13 04:13:00, 
2015-06-13 06:28:00,16.6,13.9,1008.0,SW,225,8.0,16.1,84,0.0,,,0.0,23,weatherlink.com 1.10,2015-06-13 04:28:00, 
2015-06-13 06:44:00,16.5,13.9,1008.4,SW,227,8.0,11.3,83,0.0,,,0.0,39,weatherlink.com 1.10,2015-06-13 04:44:00, 
2015-06-13 07:00:00,16.6,13.9,1008.4,West,263,9.7,16.1,83,0.0,,,0.0,53,weatherlink.com 1.10,2015-06-13 05:00:00, 
2015-06-13 07:15:00,16.5,13.3,1008.7,SW,234,4.8,16.1,82,0.0,,,0.0,47,weatherlink.com 1.10,2015-06-13 05:15:00, 
2015-06-13 07:30:00,16.4,12.8,1008.7,SSW,206,3.2,14.5,80,0.0,,,0.0,42,weatherlink.com 1.10,2015-06-13 05:30:00, 
2015-06-13 07:46:00,16.3,12.8,1008.7,SW,225,6.4,12.9,81,0.0,,,0.0,47,weatherlink.com 1.10,2015-06-13 05:46:00, 
2015-06-13 08:01:00,16.1,13.3,1009.0,SSW,208,1.6,9.7,83,0.0,,,0.0,44,weatherlink.com 1.10,2015-06-13 06:01:00, 
2015-06-13 08:16:00,15.9,13.9,1009.4,SW,218,1.6,8.0,86,0.3,,,0.3,54,weatherlink.com 1.10,2015-06-13 06:16:00, 
2015-06-13 08:32:00,15.8,13.3,1009.4,SW,229,0.0,12.9,86,0.3,,,0.3,95,weatherlink.com 1.10,2015-06-13 06:32:00, 
2015-06-13 08:47:00,15.6,13.3,1009.7,SW,214,1.6,12.9,85,0.5,,,0.5,102,weatherlink.com 1.10,2015-06-13 06:47:00, 
2015-06-13 09:02:00,15.3,12.8,1009.7,SW,219,1.6,11.3,85,0.5,,,0.5,42,weatherlink.com 1.10,2015-06-13 07:02:00, 
2015-06-13 09:17:00,15.2,12.8,1009.7,SW,226,3.2,12.9,86,0.3,,,0.5,86,weatherlink.com 1.10,2015-06-13 07:17:00, 
2015-06-13 09:32:00,15.2,13.3,1009.7,SW,221,3.2,9.7,87,0.5,,,0.5,179,weatherlink.com 1.10,2015-06-13 07:32:00, 
2015-06-13 09:48:00,15.4,13.3,1009.7,SE,136,0.0,6.4,87,0.3,,,0.5,183,weatherlink.com 1.10,2015-06-13 07:48:00, 
2015-06-13 10:03:00,15.9,13.3,1010.0,SSW,205,3.2,8.0,85,0.3,,,0.5,281,weatherlink.com 1.10,2015-06-13 08:03:00, 
2015-06-13 10:18:00,16.2,13.3,1009.7,SW,219,6.4,12.9,83,0.3,,,0.5,330,weatherlink.com 1.10,2015-06-13 08:18:00, 
2015-06-13 10:33:00,16.7,13.3,1009.7,SW,219,8.0,11.3,81,0.0,,,0.5,425,weatherlink.com 1.10,2015-06-13 08:33:00, 
2015-06-13 10:49:00,17.4,13.3,1009.7,SSW,208,3.2,14.5,78,0.0,,,0.5,475,weatherlink.com 1.10,2015-06-13 08:49:00, 
2015-06-13 11:04:00,17.8,13.3,1010.0,SW,218,4.8,12.9,76,0.0,,,0.5,427,weatherlink.com 1.10,2015-06-13 09:04:00, 
2015-06-13 11:14:00,18.1,13.3,1010.0,SSW,206,14.5,19.3,74,0.0,,,0.5,601,weatherlink.com 1.10,2015-06-13 09:14:00, 
';
#return $raw;
	echo  "<!-- Weather data loaded from url: $string  -->".PHP_EOL;
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_URL, $string);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 20);
	$rawData = curl_exec ($ch);
	curl_close ($ch);
	if (empty($rawData)){
		echo "<!-- ERROR Weather data loaded from url: $string - FAILED  -->".PHP_EOL;
		return false;
	}
return $rawData;
}
