<?php #	ini_set('display_errors', 'On'); error_reporting(E_ALL);	
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
$pageName	= 'tagsVWS.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.11 2015-07-21';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.11 2015-07-21 releasse 2.7 version + no skip empty values + arrow correction  
# ----------------------------------------------------------------------
$tagsVWS      = $pageName;
$startEcho      = '<!-- ';      
$endEcho        = ' -->';
#
$test           = false;
if ($test) {echo '<pre>Start test'.PHP_EOL;$startEcho      = '';           $endEcho        = '';
}
#
$fileToLoad     = $SITE['wsTags'];                      // normaly tags.htx
$uoms		= $uomTemp.$uomBaro.$uomWind.$uomRain.$uomSnow.$uomDistance.$uomPerHour.$uomHeight;
$from		= array('/',' ','&deg;','.php');
$to		= '';
#
$arr    = file($SITE['vws_day_txt'],FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$end    = count ($arr);
for ($n = 0; $n < $end; $n++) {
        $line   = trim ($arr[$n]);
        if ($line  == '' ) {continue;}
        $substr = substr($line,1,5);
        if ($substr  == '-----')  {continue;}
        if (substr($line,0,1) <> '|') {continue;}
        list ($skip,$name, $content) = explode ('|',$line.'|');
        $name   = trim($name);
        $content= trim($content);
        if ($content  == '' ) {
#               echo $startEcho.$tagsVWS.'('.__LINE__.'):empty line '.$n.' name '.$name .$endEcho.PHP_EOL; continue;
        }
        $wx[$name]=$content;
}
# ----------------------------------------------------------------------
$ymd                    = substr(vws_ymd   ($wx['date']),0,8);
$ws['actTime']		= vws_time   ($wx['time']);
#
$ws['tags_processed']	= $pageName.' - '.$pageVersion;
$ws['tags_originated']	= $SITE['wsTags'].' ('.$wx['pagename'].' - '.$wx['version'].')';
$ws['tags_today']	= $ws['tags_originated'];
$ws['tags_yday']	= $ws['tags_originated'];
$ws['tags_today_time']	= $ws['actTime'];
$ws['tags_yday_time']	= 'n/a';
# ------------------------------------------ temperature ---------------
$string = strtoupper($wx['fromtemp']);
$pos =  strpos('  '.$string,'C');
if ($pos > 0) {$from  = 'C';} else {$from = 'F';}
$from_temp      = $from;
$ws['tempAct']		= wsConvertTemperature  ($wx['tempAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['tempAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['tempDelta'],$from_temp,$from_temp);
$ws['tempDelta']	= $ws['tempAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);
$ws['tempToday']	= $ws['tempAct'];

$ws['tempMinToday']	= wsConvertTemperature  ($wx['tempMinToday'],$from);
$ws['tempMinTodayTime']	= vws_time              ($wx['tempMinTodayTime']);
$ws['tempMinYday']	= wsConvertTemperature  ($wx['tempMinYday'],$from);
$ws['tempMinYdayTime']	= vws_time              ($wx['tempMinYdayTime']);
$ws['tempMinMonth']	= wsConvertTemperature  ($wx['tempMinMonth'],$from);
$ws['tempMinMonthTime']	= vws_ymd               ($wx['tempMinMonthTime']);
$ws['tempMinYear']	= wsConvertTemperature  ($wx['tempMinYear'],$from);
$ws['tempMinYearTime']	= vws_ymd               ($wx['tempMinYearTime']);

$ws['tempMaxToday']	= wsConvertTemperature  ($wx['tempMaxToday'],$from);
$ws['tempMaxTodayTime']	= vws_time              ($wx['tempMaxTodayTime']);
$ws['tempMaxYday']	= wsConvertTemperature  ($wx['tempMaxYday'],$from);
$ws['tempMaxYdayTime']	= vws_time              ($wx['tempMaxYdayTime']);
$ws['tempMaxMonth']	= wsConvertTemperature  ($wx['tempMaxMonth'],$from);
$ws['tempMaxMonthTime']	= vws_ymd               ($wx['tempMaxMonthTime']);
$ws['tempMaxYear']	= wsConvertTemperature  ($wx['tempMaxYear'],$from);
$ws['tempMaxYearTime']	= vws_ymd               ($wx['tempMaxYearTime']);

$ws['dewpAct']  	= wsConvertTemperature  ($wx['dewpAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['dewpAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['dewpDelta'],$from_temp,$from_temp);
$ws['dewpDelta']	= $ws['dewpAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['dewpMinToday']  	= wsConvertTemperature  ($wx['dewpMinToday'],$from);
$ws['dewpMinTodayTime']	= vws_time              ($wx['dewpMinTodayTime']);
$ws['dewpMinYday']  	= wsConvertTemperature  ($wx['dewpMinYday'],$from);
$ws['dewpMinYdayTime']	= vws_time              ($wx['dewpMinYdayTime']);
$ws['dewpMinMonth']	= wsConvertTemperature  ($wx['dewpMinMonth'],$from);
$ws['dewpMinMonthTime']	= vws_ymd               ($wx['dewpMinMonthTime']);
$ws['dewpMinYear']	= wsConvertTemperature  ($wx['dewpMinYear'],$from);
$ws['dewpMinYearTime']	= vws_ymd               ($wx['dewpMinYearTime']);

$ws['dewpMaxToday']  	= wsConvertTemperature  ($wx['dewpMaxToday'],$from);
$ws['dewpMaxTodayTime']	= vws_time              ($wx['dewpMaxTodayTime']);
$ws['dewpMaxYday']  	= wsConvertTemperature  ($wx['dewpMaxYday'],$from);
$ws['dewpMaxYdayTime']	= vws_time              ($wx['dewpMaxYdayTime']);
$ws['dewpMaxMonth']	= wsConvertTemperature  ($wx['dewpMaxMonth'],$from);
$ws['dewpMaxMonthTime']	= vws_ymd               ($wx['dewpMaxMonthTime']);
$ws['dewpMaxYear']	= wsConvertTemperature  ($wx['dewpMaxYear'],$from);
$ws['dewpMaxYearTime']	= vws_ymd               ($wx['dewpMaxYearTime']);

$ws['heatAct']  	= wsConvertTemperature  ($wx['heatAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['heatAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['heatDelta'],$from_temp,$from_temp);
$ws['heatDelta']	= $ws['heatAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['heatMaxToday']	= wsConvertTemperature  ($wx['heatMaxToday'],$from);
$ws['heatMaxTodayTime'] = vws_time              ($wx['heatMaxTodayTime']);
$ws['heatMaxYday']	= wsConvertTemperature  ($wx['heatMaxYday'],$from);
$ws['heatMaxYdayTime']  = vws_time              ($wx['heatMaxYdayTime']);
$ws['heatMaxMonth']	= wsConvertTemperature  ($wx['heatAct'],$from);
$ws['heatMaxMonthTime'] = vws_time              ($wx['heatMaxMonthTime']);
$ws['heatMaxYear']	= wsConvertTemperature  ($wx['heatAct'],$from);
$ws['heatMaxYearTime']	= vws_time              ($wx['heatMaxYearTime']);

$ws['chilAct']		= wsConvertTemperature  ($wx['chilAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['chilAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['chilDelta'],$from_temp,$from_temp);
$ws['chilDelta']	= $ws['chilAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['chilMinToday']	= wsConvertTemperature  ($wx['chilMinToday'],$from);
$ws['chilMinTodayTime']	= vws_time              ($wx['chilMinTodayTime']);
$ws['chilMinYday']	= wsConvertTemperature  ($wx['chilMinYday'],$from);
$ws['chilMinYdayTime']	= vws_time              ($wx['chilMinYdayTime']);
$ws['chilMinMonth']	= wsConvertTemperature  ($wx['chilMinMonth'],$from);
$ws['chilMinMonthTime'] = vws_ymd               ($wx['chilMinMonthTime']);
$ws['chilMinYear']	= wsConvertTemperature  ($wx['chilMinYear'],$from);
$ws['chilMinYearTime']	= vws_ymd               ($wx['chilMinYearTime']);

$ws['chilMaxToday']	= wsConvertTemperature  ($wx['chilMaxToday'],$from);
$ws['chilMaxTodayTime']	= vws_time              ($wx['chilMaxTodayTime']);

$ws['appTemp']          = wsConvertTemperature  ($wx['appTemp'],$from);

$ws['tempActInside']	= wsConvertTemperature  ($wx['tempActInside'],$from);
$ws['tempActExtra1']	= wsConvertTemperature  ($wx['tempActExtra1'],$from);	
$ws['tempActExtra2']	= wsConvertTemperature  ($wx['tempActExtra2'],$from);	
# ------------------------------------------ pressure / baro -----------
$from_baro      = $from  = trim(strtolower($wx['frombaro']));     // ' hPa', of ' mb', of ' inHg'

$ws['baroAct'] 		        = wsConvertBaro ($wx['baroAct'],$from);
$ws['baroDelta']	        = wsConvertBaro ($wx['baroDelta'],$from);

$ws['baroMinToday']	        = wsConvertBaro ($wx['baroMinToday'],$from);
$ws['baroMinTodayTime']		= vws_time      ($wx['baroMinTodayTime']);
$ws['baroMinYday']	        = wsConvertBaro ($wx['baroMinYday'],$from);
$ws['baroMinYdayTime']		= vws_time      ($wx['baroMinYdayTime']);
$ws['baroMinMonth']	        = wsConvertBaro ($wx['baroMinMonth'],$from);
$ws['baroMinMonthTime']		= vws_ymd       ($wx['baroMinMonthTime']);
$ws['baroMinYear'] 	        = wsConvertBaro ($wx['baroMinYear'],$from);	
$ws['baroMinYearTime']		= vws_ymd       ($wx['baroMinYearTime']);

$ws['baroMaxToday']	        = wsConvertBaro ($wx['baroMaxToday'],$from);
$ws['baroMaxTodayTime']		= vws_time      ($wx['baroMaxTodayTime']);
$ws['baroMaxYday']	        = wsConvertBaro ($wx['baroMaxYday'],$from);
$ws['baroMaxYdayTime']		= vws_time      ($wx['baroMaxYdayTime']);
$ws['baroMaxMonth']	        = wsConvertBaro ($wx['baroMaxMonth'],$from);
$ws['baroMaxMonthTime']		= vws_ymd       ($wx['baroMaxMonthTime']);
$ws['baroMaxYear'] 	        = wsConvertBaro ($wx['baroMaxYear'],$from);
$ws['baroMaxYearTime']		= vws_ymd       ($wx['baroMaxYearTime']);
# ------------------------------------------ humidity  -----------------

$ws['humiAct']			=                $wx['humiAct']*1.0;
$ws['humiDelta']		=                $wx['humiDelta']*1.0;

$ws['humiMinToday'] 		=                $wx['humiMinToday']*1.0;
$ws['humiMinTodayTime']	        = vws_time      ($wx['humiMinTodayTime']);
$ws['humiMinYday'] 		=                $wx['humiMinYday']*1.0;
$ws['humiMinYdayTime']	        = vws_time      ($wx['humiMinYdayTime']);
$ws['humiMinMonth']	        =                $wx['humiMinMonth'];
$ws['humiMinMonthTime']		= vws_ymd       ($wx['humiMinMonthTime']);
$ws['humiMinYear'] 	        =                $wx['humiMinYear'];	
$ws['humiMinYearTime']		= vws_ymd       ($wx['humiMinYearTime']);

$ws['humiMaxToday']		=               $wx['humiMaxToday']*1.0;
$ws['humiMaxTodayTime'] 	= vws_time      ($wx['humiMaxTodayTime']);
$ws['humiMaxYday']		=                $wx['humiMaxYday']*1.0;
$ws['humiMaxYdayTime']	        = vws_time      ($wx['humiMaxYdayTime']);
$ws['humiMaxMonth']	        =                $wx['humiMaxMonth'];
$ws['humiMaxMonthTime']		= vws_ymd       ($wx['humiMaxMonthTime']);
$ws['humiMaxYear'] 	        =                $wx['humiMaxYear'];	
$ws['humiMaxYearTime']		= vws_ymd       ($wx['humiMaxYearTime']);

$ws['humiInAct']		=                $wx['humiInAct']*1.0;
$ws['humiActExtra1']		=                $wx['humiActExtra1']*1.0;
$ws['humiActExtra2']		=                $wx['humiActExtra2']*1.0;

# ------------------------------------------ rain  ---------------------
$from_rain      = $from  = trim(strtolower($wx['fromrain']));     // 'mm',  'in'

$ws['rainRateAct'] 	        = wsConvertRainfall     ($wx['rainRateAct'],$from);
$ws['rainRateToday'] 	        = wsConvertRainfall     ($wx['rainRateToday'],$from);

$ws['lastRained']               = vws_ymd               ($wx['lastRained']);;

$ws['rainToday']	        = wsConvertRainfall     ($wx['rainToday'],$from);
$ws['rainYday']	                = wsConvertRainfall (   ($wx['rainYdayHigh']- $wx['rainYdayLow']) , $from);
$ws['rainMonth']	        = wsConvertRainfall     ($wx['rainMonth'],$from);
$ws['rainYear']		        = wsConvertRainfall     ($wx['rainYear'],$from);

$ws['rainHour']                 = wsConvertRainfall     ($wx['rainHour'],$from);

#$ws['rainDayMnth'] 		= $wx['rainDayMnth']*1.0;
#$ws['rainDayYear'] 		= $wx['rainDayYear']*1.0;
#$ws['rainDaysWithNo'] 		= $wx['rainDaysWithNo']*1.0;
# ------------------------------------------ EVAPOTRANSPIRATION --------
$ws['etToday'] 		        = wsConvertRainfall     ($wx['etToday'],$from);
$ws['etYday'] 		        = wsConvertRainfall     ($wx['etYday'],$from);
$ws['etMonth'] 		        = wsConvertRainfall     ($wx['etMonth'],$from);
$ws['etYear'] 		        = wsConvertRainfall     ($wx['etYear'],$from);
# ------------------------------------------ wind  ---------------------
$string                         = trim(strtolower       ($wx['fromwind']));    // VWS  km/hr
$string                         = str_replace ('hr','h',$string);
$from_wind                      = $from  = $string;                     // =' km/h', =' kts', =' m/s', =' mph'

$ws['windAct']		        = wsConvertWindspeed    ($wx['windAct'], $from);
$ws['gustAct']		        = wsConvertWindspeed    ($wx['gustAct'], $from);
$ws['windActDsc']		= wsConvertWinddir      ($wx['windActDsc']); 
$ws['windActDir']		=                        $wx['windAvgDir']; 
$ws['windBeafort']		= wsBeaufortNumber      ($wx['windAct'],$from);

$ws['windAvgDir']               = $wx['windAvgDir'];

$ws['gustMaxToday']	        = wsConvertWindspeed    ($wx['gustMaxToday'], $from);
$ws['gustMaxTodayTime']		= vws_time              ($wx['gustMaxTodayTime']);
$ws['gustMaxYday']	        = wsConvertWindspeed    ($wx['gustMaxYday'], $from);
$ws['gustMaxYdayTime']		= vws_time              ($wx['gustMaxYTime']);
$ws['gustMaxMonth']	        = wsConvertWindspeed    ($wx['gustMaxMonth'], $from);
$ws['gustMaxMonthTime']		= vws_ymd               ($wx['gustMaxMonthTime']);
$ws['gustMaxYear']	        = wsConvertWindspeed    ($wx['gustMaxYear'], $from);	
$ws['gustMaxYearTime']		= vws_ymd               ($wx['gustMaxYearTime']);

if ($ws['gustAct'] < $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}
# ------------------------------------------  UV   ---------------------
$ws['uvAct']			=                        $wx['uvAct']*1.0;
$ws['uvMaxToday']		=                        $wx['uvMaxToday']*1.0;
$ws['uvMaxTodayTime'] 		= vws_time              ($wx['uvMaxTodayTime']);
$ws['uvMaxYday']		=                        $wx['uvMaxYday']*1.0;
$ws['uvMaxYdayTime'] 		= vws_time              ($wx['uvMaxYdayTime']);
$ws['uvMaxMonth']		=                        $wx['uvMaxMonth']*1.0;
$ws['uvMaxMonthTime'] 		= vws_ymd               ($wx['uvMaxMonthTime']);
$ws['uvMaxYear']		=                        $wx['uvMaxYear']*1.0;
$ws['uvMaxYearTime'] 		= vws_ymd               ($wx['uvMaxYearTime']);
# ------------------------------------------ Solar  --------------------
$ws['solarAct']			=                        $wx['solarAct']*1.0;
$ws['solarActPerc']		=                        $wx['solarActPerc']*1.0;

$ws['solarMaxToday']		=                        $wx['solarMaxToday']*1.0;
$ws['solarMaxTodayTime'] 	= vws_time              ($wx['solarMaxTodayTime']);
$ws['solarMaxYday']		=                        $wx['solarMaxYday']*1.0;
$ws['solarMaxYdayTime'] 	= vws_time              ($wx['solarMaxYdayTime']);
$ws['solarMaxMonth']		=                        $wx['solarMaxMonth']*1.0;
$ws['solarMaxMonthTime'] 	= vws_ymd               ($wx['solarMaxMonthTime']);
$ws['solarMaxYear']		=                        $wx['solarMaxYear']*1.0;
$ws['solarMaxYearTime'] 	= vws_ymd               ($wx['solarMaxYearTime']);
#------------------------------------------cloudheight------------------
$from_height                    = trim(strtolower       ($wx['fromheight']));
$ws['cloudHeight']	        = wsConvertDistance     ($wx['cloudHeight'], $from_height);
# ------------------------------------------ forecasts -----------------
$ws['fcstTxt']                  = trim($wx['fcstTxt']);
# ------------------------------------------  moon ---------------------
$ws['moonrise']	= date($SITE['timeOnlyFormat'],strtotime($wx['moonrise']));
$ws['moonset']	= date($SITE['timeOnlyFormat'],strtotime($wx['moonset']));

$string         = str_replace ('%','',                   $wx['lunarPhasePerc']);
$string         = trim($string).' ';
$arr            = explode (' ', $string);
for ($n = 0; $n < count($arr); $n++) {
        $x      = trim ($arr[$n]);
        if (  is_numeric ($x) ) {$ws['lunarPhasePerc'] = $x; break;}
}
$ws['lunarAge']			=                       $wx['lunarAge'];
# ------------------------------------------ some more -----------------
$ws['wsVersion']		= $wx['wsVersion'];
$ws['wsHardware'] 		= '';
$ws['wsUptime']			= '';
#-----------------------------------------------------------------------
$ws['check_ok']                 = '3.00';

#echo '<pre>'; print_r($ws); exit;

function vws_ymd   ($date){  
        global $SITE, $ymd;
        list ($year_nr, $month_nr, $day_nr) = $SITE['tags_ymd'];
#       print_r ($SITE['tags_ymd']); echo '<br />$year_nr = '.$year_nr.'<br />';
        $arr    = explode ($SITE['tags_ymd_sep'],$SITE['tags_ymd_sep'].$date);
        $year   = $arr[$year_nr];
        $month  = substr('00'.$arr[$month_nr], -2);
        $day    = substr('00'.$arr[$day_nr], -2);
        if ($year < 99 && $year > 69) {$year = '19'.$year;} else {$year = '20'.$year;}
        $string = $year.$month.$day;
        $int    = strtotime($string.'T000000');
        $return = date ('Ymd',$int).'000000';
#       echo $date.' | '.$string.' | '.$return; exit;
        return $return;
}
#
function vws_time   ($time){  // for todays time stamps: remove ':' in time and combine to YYYYMMDDHHMMSS
        global $ymd;            // 13:30   03:30 
        if (trim($time) == '?') {return $ymd.'000000';}
        $result = str_replace ('-','',$time);
        if (trim($result) == ''){return $ymd.'000000';}
        $int = strtotime($time);
        return ($ymd.strftime('%H%M%S',$int) );
}
// end of tagsVWS.php
?>