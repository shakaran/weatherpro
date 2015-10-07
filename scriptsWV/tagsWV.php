<?php 	ini_set('display_errors', 'On'); error_reporting(E_ALL);	
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'tagsWV.php';
$pageVersion	= '3.11 2015-07-21';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.11 2015-07-21 releasse 2.7 version removed unneeded  date separator message + arrow correction 
# ----------------------------------------------------------------------
$tagsWVIEW      = $pageName;
$startEcho      = '<!-- ';      
$endEcho        = ' -->';
#
$test           = false;
if ($test) {echo '<pre>Start test  WV'.PHP_EOL;$startEcho      = '';           $endEcho        = '';
}
#
$fileToLoad     = array( $SITE['wsTags']);                      
if (isset ($SITE['ydayTags']) ){$fileToLoad[] = $SITE['ydayTags'];}
#
for ($i = 0; $i < count ($fileToLoad); $i++) {
        $arr    = file($fileToLoad[$i],FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
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
#                if ($content  == '' ) {echo $startEcho.$tagsScript.'('.__LINE__.'):empty line '.$n.' name '.$name .$endEcho.PHP_EOL; continue;}
                $wx[$name]=$content;
        }
}
#print_r($wx); exit;
$ws['tags_processed']	= $pageName.'-'.$pageVersion;
$ws['tags_originated']	= $SITE['wsTags'].'-'.  $wx['pagename'].'-'.$wx['pagenameYday'];
$ws['tags_today']	= $SITE['wsTags'].'-'.  $wx['pagename'].'-'.$wx['version'];
$ws['tags_yday']	= $SITE['ydayTags'].'-'.$wx['pagenameYday'].'-'.$wx['versionYday'];
$ws['tags_today_time']	= $wx['datetime'];
$ws['tags_yday_time']	= $wx['datetimeYday'];
# ----------------------------------------------------------------------
$ymd                    = substr(wview_ymd ($wx['date']),0,8);
$ws['actTime']		= wview_time ($wx['time']);
# ------------------------------------------ temperature ---------------
$string = strtoupper($wx['fromtemp']);
$pos =  strpos('  '.$string,'C');
if ($pos > 0) {$from_temp  = 'C';} else {$from_temp = 'F';}
$from                   = $from_temp;
$fromYday               = $wx['fromtempYday'];

$ws['tempAct']		= wsConvertTemperature  ($wx['tempAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['tempAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['tempDelta'],$from_temp,$from_temp);
$ws['tempDelta']	= $ws['tempAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['tempMinToday']	= wsConvertTemperature  ($wx['tempMinToday'],$from);
$ws['tempMinTodayTime']	= wview_time            ($wx['tempMinTodayTime']);
$ws['tempMinYday']      = wsConvertTemperature  ($wx['tempMinYday'],$fromYday);
$ws['tempMinYdayTime']  =                        $wx['tempMinYdayTime'];
$ws['tempMinMonth']	= wsConvertTemperature  ($wx['tempMinMonth'],$from);
$ws['tempMinMonthTime']	= wview_ymd             ($wx['tempMinMonthTime']);
$ws['tempMinYear']	= wsConvertTemperature  ($wx['tempMinYear'],$from);
$ws['tempMinYearTime']	= wview_ymd             ($wx['tempMinYearTime']);
$ws['tempMinAll']	= wsConvertTemperature  ($wx['tempMinAll'],$from);
$ws['tempMinAllTime']	= wview_ymd             ($wx['tempMinAllTime']);

$ws['tempMaxToday']	= wsConvertTemperature  ($wx['tempMaxToday'],$from);
$ws['tempMaxTodayTime']	= wview_time            ($wx['tempMaxTodayTime']);
$ws['tempMaxYday']      = wsConvertTemperature  ($wx['tempMaxYday'],$fromYday);
$ws['tempMaxYdayTime']  =                        $wx['tempMaxYdayTime'];
$ws['tempMaxMonth']	= wsConvertTemperature  ($wx['tempMaxMonth'],$from);
$ws['tempMaxMonthTime']	= wview_ymd             ($wx['tempMaxMonthTime']);
$ws['tempMaxYear']	= wsConvertTemperature  ($wx['tempMaxYear'],$from);
$ws['tempMaxYearTime']	= wview_ymd             ($wx['tempMaxYearTime']);
$ws['tempMaxAll']	= wsConvertTemperature  ($wx['tempMaxAll'],$from);
$ws['tempMaxAllTime']	= wview_ymd             ($wx['tempMaxAllTime']);

$ws['dewpAct']  	= wsConvertTemperature  ($wx['dewpAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['dewpAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['dewpDelta'],$from_temp,$from_temp);
$ws['dewpDelta']	= $ws['dewpAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['dewpMinToday']  	= wsConvertTemperature  ($wx['dewpMinToday'],$from);
$ws['dewpMinTodayTime']	= wview_time            ($wx['dewpMinTodayTime']);
$ws['dewpMinYday']  	= wsConvertTemperature  ($wx['dewpMinYday'],$fromYday);
$ws['dewpMinYdayTime']	=                        $wx['dewpMinYdayTime'];
$ws['dewpMinMonth']  	= wsConvertTemperature  ($wx['dewpMinMonth'],$from);
$ws['dewpMinMonthTime']	= wview_ymd             ($wx['dewpMinMonthTime']);
$ws['dewpMinYear']  	= wsConvertTemperature  ($wx['dewpMinYear'],$from);
$ws['dewpMinYearTime']	= wview_ymd             ($wx['dewpMinYearTime']);
$ws['dewpMinAll']  	= wsConvertTemperature  ($wx['dewpMinAll'],$from);
$ws['dewpMinAllTime']	= wview_ymd             ($wx['dewpMinAllTime']);

$ws['dewpMaxToday']	= wsConvertTemperature  ($wx['dewpMaxToday'],$from);
$ws['dewpMaxTodayTime']	= wview_time            ($wx['dewpMaxTodayTime']);
$ws['dewpMaxYday']  	= wsConvertTemperature  ($wx['dewpMaxYday'],$fromYday);
$ws['dewpMaxYdayTime']	=                        $wx['dewpMaxYdayTime'];
$ws['dewpMaxMonth']  	= wsConvertTemperature  ($wx['dewpMaxMonth'],$from);
$ws['dewpMaxMonthTime']	= wview_ymd             ($wx['dewpMaxMonthTime']);
$ws['dewpMaxYear']  	= wsConvertTemperature  ($wx['dewpMaxYear'],$from);
$ws['dewpMaxYearTime']	= wview_ymd             ($wx['dewpMaxYearTime']);
$ws['dewpMaxAll']  	= wsConvertTemperature  ($wx['dewpMaxAll'],$from);
$ws['dewpMaxAllTime']	= wview_ymd             ($wx['dewpMaxAllTime']);

$ws['appTemp']          = wsConvertTemperature($wx['apptemp'],$from);

$ws['heatAct']  	= wsConvertTemperature  ($wx['heatAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['heatAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['heatDelta'],$from_temp,$from_temp);
$ws['heatDelta']	= $ws['heatAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['heatMaxToday']  	= wsConvertTemperature  ($wx['heatMaxToday'],$from);
$ws['heatMaxTodayTime']	= wview_time            ($wx['heatMaxTodayTime']);
$ws['heatMaxYday']  	= wsConvertTemperature  ($wx['heatMaxYday'],$fromYday);
$ws['heatMaxYdayTime']	=                        $wx['heatMaxYdayTime'];
$ws['heatMaxMonth']	= wsConvertTemperature  ($wx['heatMaxMonth'],$from);
$ws['heatMaxMonthTime'] = wview_ymd             ($wx['heatMaxMonthTime']);
$ws['heatMaxYear']	= wsConvertTemperature  ($wx['heatMaxYear'],$from);
$ws['heatMaxYearTime']	= wview_ymd             ($wx['heatMaxYearTime']);
$ws['heatMaxAll']	= wsConvertTemperature  ($wx['heatMaxAll'],$from);
$ws['heatMaxAllTime']	= wview_ymd             ($wx['heatMaxAllTime']);

$ws['chilAct']		= wsConvertTemperature  ($wx['chilAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['chilAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['chilDelta'],$from_temp,$from_temp);
$ws['chilDelta']	= $ws['chilAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['chilMinToday']	= wsConvertTemperature  ($wx['chilMinToday'],$from);
$ws['chilMinTodayTime']	= wview_time            ($wx['chilMinTodayTime']);
$ws['chilMinYday']      = wsConvertTemperature  ($wx['chilMinYday'],$fromYday);
$ws['chilMinYdayTime']  =                        $wx['chilMinYdayTime'];
$ws['chilMinMonth']	= wsConvertTemperature  ($wx['chilMinMonth'],$from);
$ws['chilMinMonthTime'] = wview_ymd             ($wx['chilMinMonthTime']);
$ws['chilMinYear']	= wsConvertTemperature  ($wx['chilMinYear'],$from);
$ws['chilMinYearTime']	= wview_ymd             ($wx['chilMinYearTime']);
$ws['chilMinAll']  	= wsConvertTemperature  ($wx['chilMinAll'],$from);
$ws['chilMinAllTime']	= wview_ymd             ($wx['chilMinAllTime']);

$ws['chilMaxToday']	= wsConvertTemperature  ($wx['chilMaxToday'],$from);
$ws['chilMaxTodayTime']	= wview_time            ($wx['chilMaxTodayTime']);

$ws['tempActInside']	= wsConvertTemperature  ($wx['tempActInside'],$from);
$ws['tempActExtra1']	= wsConvertTemperature  ($wx['tempActExtra1'],$from);	
$ws['tempActExtra2']	= wsConvertTemperature  ($wx['tempActExtra2'],$from);	
$ws['tempToday']	= $ws['tempAct'];
# ------------------------------------------ pressure / baro -----------
$from_baro              = $from  = trim(strtolower($wx['frombaro']));     // ' hPa', of ' mb', of ' inHg'
$from_Yday              = $wx['frombaroYday'];

$ws['baroAct'] 		= wsConvertBaro ($wx['baroAct'],$from);
$ws['baroDelta']	= wsConvertBaro ($wx['baroDelta'],$from);

$ws['baroMinToday']	= wsConvertBaro ($wx['baroMinToday'],$from);
$ws['baroMinTodayTime']	= wview_time    ($wx['baroMinTodayTime']);
$ws['baroMinYday']      = wsConvertBaro ($wx['baroMinYday'],$from_Yday);
$ws['baroMinYdayTime']  =                $wx['baroMinYdayTime'];
$ws['baroMinMonth']	= wsConvertBaro ($wx['baroMinMonth'],$from);
$ws['baroMinMonthTime']	= wview_ymd     ($wx['baroMinMonthTime']);
$ws['baroMinYear'] 	= wsConvertBaro ($wx['baroMinYear'],$from);	
$ws['baroMinYearTime']	= wview_ymd     ($wx['baroMinYearTime']);
$ws['baroMinAll'] 	= wsConvertBaro ($wx['baroMinAll'],$from);	
$ws['baroMinAllTime']	= wview_ymd     ($wx['baroMinAllTime']);

$ws['baroMaxToday']	= wsConvertBaro ($wx['baroMaxToday'],$from);
$ws['baroMaxTodayTime']	= wview_time    ($wx['baroMaxTodayTime']);
$ws['baroMaxYday']      = wsConvertBaro ($wx['baroMaxYday'],$from_Yday);
$ws['baroMaxYdayTime']  =                $wx['baroMaxYdayTime'];
$ws['baroMaxMonth']	= wsConvertBaro ($wx['baroMaxMonth'],$from);
$ws['baroMaxMonthTime']	= wview_ymd     ($wx['baroMaxMonthTime']);
$ws['baroMaxYear'] 	= wsConvertBaro ($wx['baroMaxYear'],$from);
$ws['baroMaxYearTime']	= wview_ymd     ($wx['baroMaxYearTime']);
$ws['baroMaxAll'] 	= wsConvertBaro ($wx['baroMaxAll'],$from);
$ws['baroMaxAllTime']	= wview_ymd     ($wx['baroMaxAllTime']);

# ------------------------------------------ humidity  -----------------
$ws['humiAct']			=                $wx['humiAct']*1.0;
$ws['humiDelta']		=                $wx['humiDelta']*1.0;

$ws['humiMinToday'] 		=                $wx['humiMinToday']*1.0;
$ws['humiMinTodayTime']	        = wview_time    ($wx['humiMinTodayTime']);
$ws['humiMinYday']              =                $wx['humiMinYday'];
$ws['humiMinYdayTime']          =                $wx['humiMinYdayTime'];
$ws['humiMinMonth']	        =                $wx['humiMinMonth'];
$ws['humiMinMonthTime']	        = wview_ymd     ($wx['humiMinMonthTime']);
$ws['humiMinYear'] 	        =                $wx['humiMinYear'];	
$ws['humiMinYearTime']	        = wview_ymd     ($wx['humiMinYearTime']);
$ws['humiMinAll'] 	        =                $wx['humiMinAll'];	
$ws['humiMinAllTime']	        = wview_ymd     ($wx['humiMinAllTime']);

$ws['humiMaxToday']		=                $wx['humiMaxToday']*1.0;
$ws['humiMaxTodayTime'] 	= wview_time    ($wx['humiMaxTodayTime']);
$ws['humiMaxYday']              =                $wx['humiMaxYday'];
$ws['humiMaxYdayTime']          =                $wx['humiMaxYdayTime'];
$ws['humiMaxMonth']	        =                $wx['humiMaxMonth'];
$ws['humiMaxMonthTime']	        = wview_ymd     ($wx['humiMaxMonthTime']);
$ws['humiMaxYear'] 	        =                $wx['humiMaxYear'];	
$ws['humiMaxYearTime']	        = wview_ymd     ($wx['humiMaxYearTime']);
$ws['humiMaxAll'] 	        =                $wx['humiMaxAll'];	
$ws['humiMaxAllTime']	        = wview_ymd     ($wx['humiMaxAllTime']);

$ws['humiInAct']		=                $wx['humiInAct']*1.0;
$ws['humiActExtra1']		=                $wx['humiExtra1']*1.0;
$ws['humiActExtra2']		=                $wx['humiExtra1']*1.0;
# ------------------------------------------ rain  ---------------------
$from_rain                      = $from = trim(strtolower($wx['fromrain']));     // 'mm',  'in'
$fromYday                       = $wx['fromrainYday'];

$ws['rainRateAct'] 	        = wsConvertRainfall($wx['rainRateAct'],$from);
$ws['rainRateMaxToday'] 	= $ws['rainRateAct'];
$ws['rainRateToday'] 	        = $ws['rainRateAct'];

#$ws['lastRained']               = $wx['lastRained'];

$ws['rainToday']	        = wsConvertRainfall($wx['rainToday'],$from);
$ws['rainHour']                 = wsConvertRainfall($wx['rainHour'],$from);
$ws['rainYday']                 = wsConvertRainfall($wx['rainYday'],$fromYday);
$ws['rainMonth']	        = wsConvertRainfall($wx['rainMonth'],$from);
$ws['rainYear']		        = wsConvertRainfall($wx['rainYear'],$from);


$ws['rainDayMnth'] 		= $wx['rainDayMnth']*1.0;
$ws['rainDayYear'] 		= $wx['rainDayYear']*1.0;
$ws['rainDaysWithNo'] 		= $wx['rainDaysWithNo']*1.0;
# ------------------------------------------ EVAPOTRANSPIRATION --------
$ws['etToday'] 		        = wsConvertRainfall($wx['etToday'],$from);
$ws['etYday']                   = wsConvertRainfall($wx['etYday'],$fromYday);
# ------------------------------------------ wind  ---------------------
$from                           = trim(strtolower($wx['fromwind']));   // =' km/h', =' kts', =' m/s', =' mph'
$fromYday                       = $wx['fromwindYday'];

$ws['windAct']		        = wsConvertWindspeed    ($wx['windAct'], $from);

$ws['windActDsc']		=                        $wx['windActDsc']; 
$ws['windActDir']		=                        $wx['windActDir']; 
$ws['windAvgDir']               =                        $wx['windAvgDir'];
$ws['windBeafort']		= wsBeaufortNumber      ($wx['windAct'],$from);

$ws['gustAct']		        = wsConvertWindspeed    ($wx['gustAct'], $from);
$ws['gustMaxToday']	        = wsConvertWindspeed    ($wx['gustMaxToday'], $from);
$ws['gustMaxTodayTime']		= wview_time            ($wx['gustMaxTodayTime']);
$ws['gustMaxYday']              = wsConvertWindspeed    ($wx['gustMaxYday'],$fromYday);
$ws['gustMaxYdayTime']          =                        $wx['gustMaxYdayTime'];
$ws['gustMaxMonth']	        = wsConvertWindspeed    ($wx['gustMaxMonth'], $from);
$ws['gustMaxMonthTime']		= wview_ymd             ($wx['gustMaxMonthTime']);
$ws['gustMaxYear']	        = wsConvertWindspeed    ($wx['gustMaxYear'], $from);	
$ws['gustMaxYearTime']		= wview_ymd             ($wx['gustMaxYearTime']);
$ws['gustMaxAll']	        = wsConvertWindspeed    ($wx['gustMaxAll'], $from);	
$ws['gustMaxAllTime']		= wview_ymd             ($wx['gustMaxAllTime']);

if ($ws['gustAct'] < $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}
# ------------------------------------------  UV   ---------------------
$ws['uvAct']			=                $wx['uvAct']*1.0;

$ws['uvMaxToday']		=                $wx['uvMaxToday']*1.0;
$ws['uvMaxTodayTime'] 		= wview_time    ($wx['uvMaxTodayTime']);
$ws['uvMaxYday']                =                $wx['uvMaxYday'];
$ws['uvMaxYdayTime']            =                $wx['uvMaxYdayTime'];
$ws['uvMaxMonth']		=                $wx['uvMaxMonth']*1.0;
$ws['uvMaxMonthTime'] 		= wview_ymd     ($wx['uvMaxMonthTime']);
$ws['uvMaxYear']		=                $wx['uvMaxYear']*1.0;
$ws['uvMaxYearTime'] 		= wview_ymd     ($wx['uvMaxYearTime']);
$ws['uvMaxAll']		        =                $wx['uvMaxAll']*1.0;
$ws['uvMaxAllTime'] 		= wview_ymd     ($wx['uvMaxAllTime']);
# ------------------------------------------ Solar  --------------------
$ws['solarAct']			=                $wx['solarAct']*1.0;
$ws['solarActPerc']		= '';           #$wx['solarActPerc']*1.0;

$ws['solarMaxToday']		=                $wx['solarMaxToday']*1.0;
$ws['solarMaxTodayTime'] 	= wview_time    ($wx['solarMaxTodayTime']);
$ws['solarMaxYday']             =                $wx['solarMaxYday'];
$ws['solarMaxYdayTime']         =                $wx['solarMaxYdayTime'];
$ws['solarMaxMonth']		=                $wx['solarMaxMonth']*1.0;
$ws['solarMaxMonthTime'] 	= wview_ymd     ($wx['solarMaxMonthTime']);
$ws['solarMaxYear']		=                $wx['solarMaxYear']*1.0;
$ws['solarMaxYearTime'] 	= wview_ymd     ($wx['solarMaxYearTime']);
$ws['solarMaxYear']		=                $wx['solarMaxAll']*1.0;
$ws['solarMaxYearTime'] 	= wview_ymd     ($wx['solarMaxAllTime']);
# ------------------------------------------ forecasts -----------------
if ($wx['fcstRule'] <> '<!--forecastRule-->') {
        $ws['fcstTxt']  = trim($wx['fcstRule']); }
else {  $ws['fcstTxt']  = ''; }
# ------------------------------------------  moon ---------------------
if (trim ($wx['moonrise']) == '--:--' ) 
        { $ws['moonrise']       = '< 00:00'; }
else    { $ws['moonrise']       = date($SITE['timeOnlyFormat'],strtotime($wx['moonrise']));}
if (trim ($wx['moonset']) == '--:--' ) 
        { $ws['moonset']        = '> 24:00'; }
else    { $ws['moonset']        = date($SITE['timeOnlyFormat'],strtotime($wx['moonset']));}
#$ws['lunarPhase']		= '';

$ws['lunarPhaseTxt']		= $wx['lunarPhasePerc'];
$string         = str_replace ('%','',$wx['lunarPhasePerc']);
$arr            = explode (' ',$string);
for ($n = 0; $n < count($arr); $n++) {
        $x      = trim ($arr[$n]);
        if (  is_numeric ($x) ) {$ws['lunarPhasePerc'] = $x; break;}
}
$ws['lunarAge']			= ''; # $wx['lunarAge'];
# ------------------------------------------ some more -----------------
$ws['wsVersion']		= $wx['wsVersion'];
$ws['wsHardware'] 		= '';
$ws['wsUptime']			= $wx['wsUptime'];
# ------------------------------------------ soil moist ----------------
# no soil/moist
if ($SITE['soilUsed'] &&  $SITE['soilCount']	>=  '0') {
        $soils = floor($SITE['soilCount']);
        if ($soils > 4) { echo '<!-- reset nr of soil sensors from '.$SITE['soilCount'].' to max 4 -->'.PHP_EOL; $soils  = 4;}
        for  ($n = 1; $n <= $soils; $n++) {	
                $from   = $from_temp; 
                # Temp sensor 1 actual value
                $ws['soilTempAct'][$n]		= wsConvertTemperature($wx['soilTempAct['.$n.']'],$from);
                # Moisture sensor 1 actual value
                $ws['moistAct'][$n]		= $wx['moistAct_'.$n];
        }
}
if ($SITE['leafUsed'] &&  $SITE['leafCount']	>=  '0') {
        $leafs = round($SITE['leafCount']); 
        if ($leafs > 2) {
                echo '<!-- reset nr of leaf sensors from '.$SITE['leafCount'].' to max 2 -->'.PHP_EOL;
                $leafs  = 2;
        }
        for  ($n = 1; $n <= $leafs; $n++) {
                $ws['leafTempAct'][1]		= $wx['leafTempAct_'.$n];
                $ws['leafWetAct'][1]		= $wx['leafWetAct_'.$n];
        }
}
# ----------------------------------------------------------------------
$ws['check_ok']         = '3.00';

if ($test) {print_r ($ws); exit;}

# ---------------------------- F U N C T I O N S  ----------------------

function wview_ymd ($date){  // for todays 
        global $SITE, $ymd, $ws;
        if (substr(trim($date),0,4) == '<!--') {return '';}
        list ($year_nr, $month_nr, $day_nr) = $SITE['tags_ymd'];
# print_r ($SITE['tags_ymd']); echo '<br />$year_nr = '.$year_nr; # exit;
        $arr    = explode ($SITE['tags_ymd_sep'],$SITE['tags_ymd_sep'].$date);
# print_r ($arr); # exit;
        if (count ($arr) < 4 ) {
                $alt_char       = $SITE['tags_ymd_sep'];
                $from           = array ('/','-');
                $wait           = array ('1','2');
                $to             = array ('-','/');
                $alt_char       = str_replace   ($from, $wait, $alt_char);
                $alt_char       = str_replace   ($wait, $to, $alt_char);
                $arr            = explode       ($alt_char,$alt_char.$date);
                $start          = '<!-- '; $end = ' -->'.PHP_EOL;  
                if (isset ($SITE['wsDebug'])  && $SITE['wsDebug']) {$start  = '';  $end = '<br />'.PHP_EOL;}
                $string = 'switch date seperator from '.$SITE['tags_ymd_sep'].' to '.$alt_char.' please adjust your settings for  '.$date;
                $SITE['tags_ymd_sep']   = $alt_char;
                if (isset ($ws['error'][$string]) ) 
                      { $ws['error'][$string]++;}
                else  { $ws['error'][$string] = 1;
                        echo $start.$string.$end;
                }
                if (count ($arr) < 4)  {
                        $string = 'Error invalid date separator:  '.$SITE['tags_ymd_sep'].' or '.
                                $alt_char.' character. Or invalid date: '.$date.' - check your settings ';
                        if (isset ($ws['error'][$string]) ) {
                                $ws['error'][$string]++;} 
                        else {  $ws['error'][$string]=1;
                                echo $start.$string.$end;} 
                        return '19700101000000';
                }
        }
        $year   = $arr[$year_nr];
        $month  = substr('00'.$arr[$month_nr], -2);
        $day    = substr('00'.$arr[$day_nr], -2);
        if (strlen (trim($year)) <> 4) {
                if ($year < 99 && $year > 69) {$year = '19'.$year;} else {$year = '20'.$year;}
        }
        $string = $year.$month.$day;
        $int    = strtotime($string.'T000000');
        $return = date ('Ymd',$int).'000000';
#  echo $date.' | '.$string.' | '.$return; exit;
        return $return;
}
#
function wview_time ($time){  // for todays time stamps: remove ':' in time and combine to YYYYMMDDHHMMSS
        global $ymd;
        if (trim($time) == '?') {return $ymd.'000000';}
        $result = str_replace ('-','',$time);
        if (trim($result) == ''){return $ymd.'000000';}
        $return = $ymd.substr($time,0,2).substr($time,3,2).substr($time.':00',6,2);
        return $return;
}
# end of tagsWV.php
