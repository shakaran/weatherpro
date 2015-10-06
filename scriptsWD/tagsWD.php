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
$pageName	= 'tagsWD.php';
$pageVersion	= '3.11 2015-07-21';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.11 2015-07-21 releasse 2.7 version + missing WD CCN + gustmaxhour / lunar age calculation + arrow correction 
# --------------------------------------- version ----------------------
$tagsScript     = $pageName;
$startEcho      = '<!-- ';      
$endEcho        = ' -->';
#
$test           = false;
if ($test) {echo '<pre>Start test'.PHP_EOL;$startEcho      = ''; $endEcho        = '';}
#
$fileToLoad     = $SITE['wsTags'];              // normaly tagsWD.txt
$uoms		= $uomTemp.$uomBaro.$uomWind.$uomRain.$uomSnow.$uomDistance.$uomPerHour.$uomHeight;
$from		= array('/',' ','&deg;','.php');
$to		= '';
#
$arr    = file($fileToLoad,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
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
#        if ($content  == '' ) { echo $startEcho.$tagsScript.'('.__LINE__.'):empty line '.$n.' name '.$name .$endEcho.PHP_EOL; continue;}
        $wx[$name]=$content;
}
#print_r ($wx);  echo '------------------halt1'; exit;
$ws['tags_processed']	= $pageName.'-'.$pageVersion;
$ws['tags_originated']	= $SITE['wsTags'].'-'.  $wx['pagename'];
$ws['tags_today']	= $ws['tags_originated'];
$ws['tags_yday']	= $ws['tags_originated'];
$ws['tags_today_time']	= $wx['datetime'];
$ws['tags_yday_time']	= 'n/a';
# ----------------------------------------------------------------------
$ymd                    = str_replace(' ','',$wx['date']);
$ws['actTime']		= $ymd. str_replace(' ','',$wx['time']);
# ------------------------------------------ CCN -----------------------
if (isset ($wx['wdCurCond']) ) {
	$ws['wdCurCond'] = $wx['wdCurCond'];
	$ws['wdCurIcon'] = $wx['wdCurIcon'];
}	
# ------------------------------------------ temperature ---------------
$from_temp              = $wx['fromtemp'];
$ws['tempAct']		= wsConvertTemperature  ($wx['tempAct'],$from_temp);
$temp1hourAgo		= wsConvertTemperature  ($wx['tempAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['tempDelta'],$from_temp,$from_temp);
$ws['tempDelta']	= $ws['tempAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['tempMinToday']	= wsConvertTemperature  ($wx['tempMinToday'],$from_temp);
$ws['tempMinTodayTime']	= wdDate                ($wx['tempMinTodayTime']);
$ws['tempMinYday']	= wsConvertTemperature  ($wx['tempMinYday'],$from_temp);
$ws['tempMinYdayTime']	= wdDate                ($wx['tempMinYdayTime']);
$ws['tempMinMonth']	= wsConvertTemperature  ($wx['tempMinMonth'],$from_temp);
$ws['tempMinMonthTime']	= wd_ymd                ($wx['tempMinMonthTime']);
$ws['tempMinYear']	= wsConvertTemperature  ($wx['tempMinYear'],$from_temp);
$ws['tempMinYearTime']	= wd_ymd                ($wx['tempMinYearTime']);
$ws['tempMinAll']	= wsConvertTemperature  ($wx['tempMinAll'],$from_temp);
$ws['tempMinAllTime']	= wd_ymd                ($wx['tempMinAllTime']);
$ws['tempMinRecordToday']       = wsConvertTemperature  ($wx['tempMinRecordToday'],$from_temp);
$ws['tempMinRecordTodayYear']	= trim                  ($wx['tempMinRecordTodayYear']);


$ws['tempMaxToday']	= wsConvertTemperature  ($wx['tempMaxToday'],$from_temp);
$ws['tempMaxTodayTime']	= wdDate                ($wx['tempMaxTodayTime']);
$ws['tempMaxYday']	= wsConvertTemperature  ($wx['tempMaxYday'],$from_temp);
$ws['tempMaxYdayTime']	= wdDate                ($wx['tempMaxYdayTime']);
$ws['tempMaxMonth']	= wsConvertTemperature  ($wx['tempMaxMonth'],$from_temp);
$ws['tempMaxMonthTime']	= wd_ymd                ($wx['tempMaxMonthTime']);
$ws['tempMaxYear']	= wsConvertTemperature  ($wx['tempMaxYear'],$from_temp);
$ws['tempMaxYearTime']	= wd_ymd                ($wx['tempMaxYearTime']);
$ws['tempMaxAll']	= wsConvertTemperature  ($wx['tempMaxAll'],$from_temp);
$ws['tempMaxAllTime']	= wd_ymd                ($wx['tempMaxAllTime']);
$ws['tempMaxRecordToday']       = wsConvertTemperature  ($wx['tempMaxRecordToday'],$from_temp);
$ws['tempMaxRecordTodayYear']	= trim                  ($wx['tempMaxRecordTodayYear']);

$ws['dewpAct']  	= wsConvertTemperature  ($wx['dewpAct'],$from_temp);
$temp1hourAgo		= wsConvertTemperature  ($wx['dewpAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['dewpDelta'],$from_temp,$from_temp);
$ws['dewpDelta']	= $ws['dewpAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['dewpMinToday']  	= wsConvertTemperature  ($wx['dewpMinToday'],$from_temp);
$ws['dewpMinTodayTime']	= wdDate                ($wx['dewpMinTodayTime']);
$ws['dewpMinYday']  	= wsConvertTemperature  ($wx['dewpMinYday'],$from_temp);
$ws['dewpMinYdayTime']	= wdDate                ($wx['dewpMinYdayTime']);
$ws['dewpMinMonth']	= wsConvertTemperature  ($wx['dewpMinMonth'],$from_temp);
$ws['dewpMinMonthTime']	= wd_ymd                ($wx['dewpMinMonthTime']);
$ws['dewpMinYear']	= wsConvertTemperature  ($wx['dewpMinYear'],$from_temp);
$ws['dewpMinYearTime']	= wd_ymd                ($wx['dewpMinYearTime']);
$ws['dewpMinAll']	= wsConvertTemperature  ($wx['dewpMinAll'],$from_temp);
$ws['dewpMinAllTime']	= wd_ymd                ($wx['dewpMinAllTime']);

$ws['dewpMaxToday']  	= wsConvertTemperature  ($wx['dewpMaxToday'],$from_temp);
$ws['dewpMaxTodayTime']	= wdDate                ($wx['dewpMaxTodayTime']);
$ws['dewpMaxYday']  	= wsConvertTemperature  ($wx['dewpMaxYday'],$from_temp);
$ws['dewpMaxYdayTime']	= wdDate                ($wx['dewpMaxYdayTime']);
$ws['dewpMaxMonth']	= wsConvertTemperature  ($wx['dewpMaxMonth'],$from_temp);
$ws['dewpMaxMonthTime']	= wd_ymd                ($wx['dewpMaxMonthTime']);
$ws['dewpMaxYear']	= wsConvertTemperature  ($wx['dewpMaxYear'],$from_temp);
$ws['dewpMaxYearTime']	= wd_ymd                ($wx['dewpMaxYearTime']);
$ws['dewpMaxAll']	= wsConvertTemperature  ($wx['dewpMaxAll'],$from_temp);
$ws['dewpMaxAllTime']	= wd_ymd                ($wx['dewpMaxAllTime']);

$ws['appTemp']  	        = wsConvertTemperature  ($wx['appTemp'],$from_temp);
/*
$ws['appTempMinToday']  	= wsConvertTemperature  ($wx['appTempMinToday'],$from_temp);
$ws['appTempMinTodayTime']	= wdDate                ($wx['appTempMinTodayTime']);
$ws['appTempMinYday']  	        = wsConvertTemperature  ($wx['appTempMinYday'],$from_temp);
$ws['appTempMinYdayTime']	= wdDate                ($wx['appTempMinYdayTime']);

$ws['appTempMaxToday']  	= wsConvertTemperature  ($wx['appTempMaxToday'],$from_temp);
$ws['appTempMaxTodayTime']	= wdDate                ($wx['appTempMaxTodayTime']);
$ws['appTempMaxYday']  	        = wsConvertTemperature  ($wx['appTempMaxYday'],$from_temp);
$ws['appTempMaxYdayTime']	= wdDate                ($wx['appTempMaxYdayTime']);
*/
$ws['heatAct']  	= wsConvertTemperature  ($wx['heatAct'],$from_temp);
$ws['heatDelta']	= ''; #wsConvertTemperature  ($wx['heatDelta'],$from_temp) - $ws['heatAct'];

$ws['heatMaxToday']  	= wsConvertTemperature  ($wx['heatMaxToday'],$from_temp);
$ws['heatMaxTodayTime']	= wdDate                ($wx['heatMaxTodayTime']);
$ws['heatMaxYday']  	= wsConvertTemperature  ($wx['heatMaxYday'],$from_temp);
$ws['heatMaxYdayTime']	= wdDate                ($wx['heatMaxYdayTime']);
$ws['heatMaxMonth']	= wsConvertTemperature  ($wx['heatMaxMonth'],$from_temp);
$ws['heatMaxMonthTime'] = wd_ymd                ($wx['heatMaxMonthTime']);
$ws['heatMaxYear']	= wsConvertTemperature  ($wx['heatMaxYear'],$from_temp);
$ws['heatMaxYearTime']	= wd_ymd                ($wx['heatMaxYearTime']);
$ws['heatMaxAll']	= wsConvertTemperature  ($wx['heatMaxAll'],$from_temp);
$ws['heatMaxAllTime']	= wd_ymd                ($wx['heatMaxAllTime']);

$ws['chilAct']  	= wsConvertTemperature  ($wx['chilAct'],$from_temp);
$ws['chilDelta']	= ''; #wsConvertTemperature  ($wx['chilDelta'],$from_temp) - $ws['chilAct'];

$ws['chilMinToday']  	= wsConvertTemperature  ($wx['chilMinToday'],$from_temp);
$ws['chilMinTodayTime']	= wdDate                ($wx['chilMinTodayTime']);
$ws['chilMinYday']  	= wsConvertTemperature  ($wx['chilMinYday'],$from_temp);
$ws['chilMinYdayTime']	= wdDate                ($wx['chilMinYdayTime']);
$ws['chilMinMonth']  	= wsConvertTemperature  ($wx['chilMinMonth'],$from_temp);
$ws['chilMinMonthTime']	= wd_ymd                ($wx['chilMinMonthTime']);
$ws['chilMinYear']  	= wsConvertTemperature  ($wx['chilMinYear'],$from_temp);
$ws['chilMinYearTime']	= wd_ymd                ($wx['chilMinYearTime']);
$ws['chilMinAll']  	= wsConvertTemperature  ($wx['chilMinAll'],$from_temp);
$ws['chilMinAllTime']	= wd_ymd                ($wx['chilMinAllTime']);

$ws['chilMaxToday']  	= wsConvertTemperature  ($wx['chilMinToday'],$from_temp);
$ws['chilMaxTodayTime'] = wdDate                ($wx['chilMaxTodayTime']);

$ws['tempActInside']	= wsConvertTemperature  ($wx['tempActInside'],$from_temp);
$ws['tempActExtra1']	= wsConvertTemperature  ($wx['tempActExtra1'],$from_temp);	
$ws['tempActExtra2']	= wsConvertTemperature  ($wx['tempActExtra2'],$from_temp);	
$ws['tempToday']	= $ws['tempAct'];

if (isset ($wx['fromhudx']) ) {$from = $wx['fromhudx'];} else {$from = $from_temp;}
$ws['hudxAct'] 	        = wsConvertTemperature  ($wx['hudxAct'],$from_temp);


# ------------------------------------------ pressure / baro -----------
$from_baro              = trim(strtolower($wx['frombaro']));     // ' hPa', of ' mb', of ' inHg'

$ws['baroAct'] 	        = wsConvertBaro ($wx['baroAct'],$from_baro);
$ws['baroDelta']	= wsConvertBaro ($wx['baroDelta'],$from_baro);
$ws['baroTrend']	= langtransstr  ($wx['baroTrend']);

$ws['baroMinToday']	= wsConvertBaro ($wx['baroMinToday'],$from_baro);
$ws['baroMinTodayTime']	= wdDate        ($wx['baroMinTodayTime']);
$ws['baroMinYday']	= wsConvertBaro ($wx['baroMinYday'],$from_baro);
$ws['baroMinYdayTime']	= wdDate        ($wx['baroMinYdayTime']);
$ws['baroMinMonth']	= wsConvertBaro ($wx['baroMinMonth'],$from_baro);
$ws['baroMinMonthTime']	= wd_ymd        ($wx['baroMinMonthTime']);
$ws['baroMinYear'] 	= wsConvertBaro ($wx['baroMinYear'],$from_baro);	
$ws['baroMinYearTime']	= wd_ymd        ($wx['baroMinYearTime']);
$ws['baroMinAll'] 	= wsConvertBaro ($wx['baroMinAll'],$from_baro);	
$ws['baroMinAllTime']	= wd_ymd        ($wx['baroMinAllTime']);

$ws['baroMaxToday']	= wsConvertBaro ($wx['baroMaxToday'],$from_baro);
$ws['baroMaxTodayTime'] = wdDate        ($wx['baroMaxTodayTime']);
$ws['baroMaxYday']	= wsConvertBaro ($wx['baroMaxYday'],$from_baro);
$ws['baroMaxYdayTime']	= wdDate        ($wx['baroMaxYdayTime']);
$ws['baroMaxMonth']	= wsConvertBaro ($wx['baroMaxMonth'],$from_baro);
$ws['baroMaxMonthTime']= wd_ymd         ($wx['baroMaxMonthTime']);
$ws['baroMaxYear']	= wsConvertBaro ($wx['baroMaxYear'],$from_baro);
$ws['baroMaxYearTime']	= wd_ymd        ($wx['baroMaxYearTime']);
$ws['baroMaxAll']	= wsConvertBaro ($wx['baroMaxAll'],$from_baro);
$ws['baroMaxAllTime']	= wd_ymd        ($wx['baroMaxAllTime']);
# ------------------------------------------ humidity  -----------------
$ws['humiAct']			= $wx['humiAct']*1.0;
$ws['humiDelta']		= $wx['humiDelta']*1.0;

$ws['humiMinToday'] 		=                $wx['humiMinToday']*1.0;
$ws['humiMinTodayTime']	        = wdDate        ($wx['humiMinTodayTime']);
$ws['humiMinYday'] 		=                $wx['humiMinYday']*1.0;
$ws['humiMinYdayTime']	        = wdDate        ($wx['humiMinYdayTime']);
$ws['humiMinMonth']	        =                $wx['humiMinMonth']*1.0;
$ws['humiMinMonthTime']	        = wd_ymd        ($wx['humiMinMonthTime']);
$ws['humiMinYear'] 	        =                $wx['humiMinYear']*1.0;	
$ws['humiMinYearTime']	        = wd_ymd        ($wx['humiMinYearTime']);
$ws['humiMinAll'] 	        =                $wx['humiMinAll']*1.0;	
$ws['humiMinAllTime']	        = wd_ymd        ($wx['humiMinAllTime']);

$ws['humiMaxToday']		=                $wx['humiMaxToday']*1.0;
$ws['humiMaxTodayTime']	        = wdDate        ($wx['humiMaxTodayTime']);
$ws['humiMaxYday']		=                $wx['humiMaxYday']*1.0;
$ws['humiMaxYdayTime']	        = wdDate        ($wx['humiMaxYdayTime']);
$ws['humiMaxMonth']	        =                $wx['humiMaxMonth']*1.0;
$ws['humiMaxMonthTime']	        = wd_ymd        ($wx['humiMaxMonthTime']);
$ws['humiMaxYear'] 	        =                $wx['humiMaxYear']*1.0;	
$ws['humiMaxYearTime']	        = wd_ymd        ($wx['humiMaxYearTime']);
$ws['humiMaxAll'] 	        =                $wx['humiMaxAll']*1.0;	
$ws['humiMaxAllTime']	        = wd_ymd        ($wx['humiMaxAllTime']);

$ws['humiInAct']		=                $wx['humiInAct']*1.0;
$ws['humiActExtra1']		=                $wx['humiActExtra1']*1.0;
$ws['humiActExtra2']		=                $wx['humiActExtra2']*1.0;
# ------------------------------------------ rain  ---------------------
$from_rain      = trim(strtolower($wx['fromrain']));     // 'mm',  'in'

$ws['rainRateAct'] 	        = wsConvertRainfall     ($wx['rainRateAct'],$from_rain);
$ws['rainHourAct']              = $ws['rainHour'] = wsConvertRainfall     ($wx['rainHourAct'],$from_rain);

$ws['rainRateMaxToday'] 	= wsConvertRainfall     ($wx['rainRateMaxToday'],$from_rain);
$ws['rainRateMaxYday'] 	        = wsConvertRainfall     ($wx['rainRateMaxYday'],$from_rain);

$ws['lastRainTip']              = ''; #$ws['lastRained']    = date('YmdHis',strtotime($wx['lastRained']));

$ws['rainToday']	        = wsConvertRainfall     ($wx['rainToday'],$from_rain);
$ws['rainYday']	                = wsConvertRainfall     ($wx['rainYday'],$from_rain);
$ws['rainWeek']                 = wsConvertRainfall     ($wx['rainWeek'],$from_rain);
$ws['rainMonth']	        = wsConvertRainfall     ($wx['rainMonth'],$from_rain);
$ws['rainYear']		        = wsConvertRainfall     ($wx['rainYear'],$from_rain);

$ws['rainTodayLow']	        = wsConvertRainfall     ($wx['rainTodayLow'],$from_rain);
$ws['rainYdayLow']	        = wsConvertRainfall     ($wx['rainYdayLow'],$from_rain);
$ws['rainYdayHigh']	        = wsConvertRainfall     ($wx['rainYdayHigh'],$from_rain);
$ws['rainRateYday']	        = wsConvertRainfall     ($wx['rainRateYday'],$from_rain);
$ws['rainDayMnth'] 		=                        $wx['rainDayMnth']*1.0;
$ws['rainDayYear'] 		=                        $wx['rainDayYear']*1.0;
$ws['rainDaysWithNo'] 		=                        $wx['rainDaysWithNo']*1.0;
# ------------------------------------------ EVAPOTRANSPIRATION --------
$ws['etToday'] 		        = wsConvertRainfall     ($wx['etToday'],$from_rain);
$ws['etYday'] 		        = wsConvertRainfall     ($wx['etYday'],$from_rain);
$ws['etMonth'] 		        = wsConvertRainfall     ($wx['etMonth'],$from_rain);
$ws['etYear'] 		        = wsConvertRainfall     ($wx['etYear'],$from_rain);
# ------------------------------------------ wind  ---------------------
$from_wind      = trim(strtolower($wx['fromwind']));       //  "m/s", "mph", "km/h", "kts" ?? 'kmh' 'Bft'??  windrun  "km", "miles",

$ws['windAct']		        = wsConvertWindspeed    ($wx['windAct'], $from_wind);
$ws['gustAct']		        = wsConvertWindspeed    ($wx['gustAct'], $from_wind);
$ws['gustMaxHour']		= wsConvertWindspeed    ($wx['gustMaxHour'], $from_wind);

$ws['windActDsc']		=                        $wx['windActDsc'];  //NNE
$ws['windActDir']		=                        $wx['windActDir']*1.0;  // 20 deg
$ws['windAvgDir']               =                        $wx['windAvgDir']*1.0;

$ws['windBeafort']		=                        $wx['windBeafort'];

$ws['gustMaxToday']	        = wsConvertWindspeed    ($wx['gustMaxToday'], $from_wind);
$ws['gustMaxTodayTime']         = wdDate                ($wx['gustMaxTodayTime']);
$ws['gustMaxYday']	        = wsConvertWindspeed    ($wx['gustMaxYday'], $from_wind);
$ws['gustMaxYdayTime']          = wdDate                ($wx['gustMaxYTime']);
$ws['gustMaxMonth']	        = wsConvertWindspeed    ($wx['gustMaxMonth'], $from_wind);
$ws['gustMaxMonthTime']	        = wd_ymd                ($wx['gustMaxMonthTime']);
$ws['gustMaxYear']	        = wsConvertWindspeed    ($wx['gustMaxYear'], $from_wind);	
$ws['gustMaxYearTime']	        = wd_ymd                ($wx['gustMaxYearTime']);
$ws['gustMaxAll']	        = wsConvertWindspeed    ($wx['gustMaxAll'], $from_wind);	
$ws['gustMaxAllTime']	        = wd_ymd                ($wx['gustMaxAllTime']);

if ($ws['gustAct'] < $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}

$from_distance                  = trim(strtolower($wx['fromdistance']));

$ws['windrunToday']             = wsConvertDistance     ($wx['windrunToday'],$from_distance);
# ------------------------------------------  UV   ---------------------
$ws['uvAct']			=                        $wx['uvAct']*1.0;
$ws['uvMaxToday']		=                        $wx['uvMaxToday']*1.0;
$ws['uvMaxTodayTime'] 		= wdDate                ($wx['uvMaxTodayTime']);
$ws['uvMaxYday']		=                        $wx['uvMaxYday']*1.0;
$ws['uvMaxYdayTime'] 		= wdDate                ($wx['uvMaxYdayTime']);
$ws['uvMaxMonth']		=                        $wx['uvMaxMonth']*1.0;
$ws['uvMaxMonthTime'] 		= wd_ymd                ($wx['uvMaxMonthTime']);
$ws['uvMaxYear']		=                        $wx['uvMaxYear']*1.0;
$ws['uvMaxYearTime'] 		= wd_ymd                ($wx['uvMaxYearTime']);
$ws['uvMaxAll']		        =                        $wx['uvMaxAll']*1.0;
$ws['uvMaxAllTime'] 		= wd_ymd                ($wx['uvMaxAllTime']);
# ------------------------------------------ Solar  --------------------
$ws['solarAct']			=                        $wx['solarAct']*1.0;
$ws['solarActPerc']		=                        $wx['solarActPerc']*1.0;
$ws['solarMaxToday']		=                        $wx['solarMaxToday']*1.0;
$ws['solarMaxTodayTime'] 	= wdDate                ($wx['solarMaxTodayTime']);
$ws['solarMaxYday']		=                        $wx['solarMaxYday']*1.0;
$ws['solarMaxYdayTime'] 	= wdDate                ($wx['solarMaxYdayTime']);
$ws['solarMaxMonth']		=                        $wx['solarMaxMonth']*1.0;
$ws['solarMaxMonthTime'] 	= wd_ymd                ($wx['solarMaxMonthTime']);
$ws['solarMaxYear']		=                        $wx['solarMaxYear']*1.0;
$ws['solarMaxYearTime'] 	= wd_ymd                ($wx['solarMaxYearTime']);
$ws['solarMaxAll']		=                        $wx['solarMaxAll']*1.0;
$ws['solarMaxAllTime'] 	        = wd_ymd                ($wx['solarMaxAllTime']);
#------------------------------------------cloudheight------------------
$from_height                    = trim(strtolower($wx['fromheight']));
$ws['cloudHeight']	        = wsConvertDistance     ($wx['cloudHeight'], $from_height);
# ------------------------------------------ forecasts -----------------
$ws['fcstTxt']                  = trim(str_replace ('_',' ',$wx['fcstTxt']) );
# ------------------------------------------  moon ---------------------
$ws['moonrise']			= $wx['moonrise'];
$ws['moonset']			= $wx['moonset'];
$ws['lunarPhasePerc']           = $wx['lunarPhasePerc']*1.0;
# Moon age: 21 days,5 hours,59 minutes,60%
$from = array ('Moon age:','days');
$string	= str_replace ($from, '', $wx['lunarAge']);
list ($string) = explode (',',trim($string) );
$ws['lunarAge']			= $string*1.0;
if ($SITE['wsDebug']) {echo '<!-- lunar age = '.$ws['lunarAge'].' calculated from '.$wx['lunarAge'].' -->'.PHP_EOL;}
# ------------------------------------------  trends   -----------------
$ws['tempFrom']                 = $from_temp;
$ws['baroFrom']                 = $from_baro;
$ws['rainFrom']                 = $from_rain;
$ws['windFrom']                 = $from_wind;
$ws['trendsMinutes']            = $wx['trendsMinutes'];
$ws['tempTrends']               = $wx['tempArray'];	
$ws['windTrends']               = $wx['windArray'];
$ws['gustTrends']               = $wx['gustArray'];	
$ws['wdirTrends']               = $wx['wdirArray'];
$ws['humiTrends']               = $wx['humiArray'];	
$ws['baroTrends']               = $wx['baroArray'];	
$ws['rainTrends']               = $wx['rainArray'];
if($SITE['UV'])         {$ws['uvTrends']        = $wx['uvArray'];}
if($SITE['SOLAR'])      {$ws['solarTrends']     = $wx['solarArray'];}	
# ------------------------------------------ some more -----------------
$ws['wsVersion']		= $wx['wsVersion'].'-'.$wx['wsBuild'];
$ws['wsHardware'] 		= '';                   # $wx['wsHardware'];
$ws['wsUptime']			= $wx['wsUptime'];
#--------------------------------------------soil moisture -------------
if ($SITE['soilUsed'] &&  $SITE['soilCount']	>=  '0') {$soils = floor($SITE['soilCount']);  $doSoil = true; } else {$doSoil = false;}
if ($doSoil) {
        if ($soils > 4) {echo $startEcho.' reset nr of soil sensors from '.$SITE['soilCount'].' to max 4 '.$endEcho.PHP_EOL; $soils  = 4;}
        $soilTempAct            = explode ('#',$wx['soilTempAct']);
        $soilTempMaxToday       = explode ('#',$wx['soilTempMaxToday']);
        $soilTempMaxMonth       = explode ('#',$wx['soilTempMaxMonth']);
        $soilTempMaxYear        = explode ('#',$wx['soilTempMaxYear']);
        $soilTempMaxAll     = explode ('#',$wx['soilTempMaxAll']);
#        $soilTempMaxTodayTime   = explode ('#',$wx['soilTempMaxTodayTime']);
        $soilTempMaxMonthTime   = explode ('#',$wx['soilTempMaxMonthTime']);
        $soilTempMaxYearTime    = explode ('#',$wx['soilTempMaxYearTime']);
        $soilTempMaxAllTime = explode ('#',$wx['soilTempMaxAllTime']);
        
        $soilTempMinToday       = explode ('#',$wx['soilTempMinToday']);
        $soilTempMinMonth       = explode ('#',$wx['soilTempMinMonth']);
        $soilTempMinYear        = explode ('#',$wx['soilTempMinYear']);
        $soilTempMinAll     = explode ('#',$wx['soilTempMinAll']);
#        $soilTempMinTodayTime   = explode ('#',$wx['soilTempMinTodayTime']);
        $soilTempMinMonthTime   = explode ('#',$wx['soilTempMinMonthTime']);
        $soilTempMinYearTime    = explode ('#',$wx['soilTempMinYearTime']);
        $soilTempMinAllTime = explode ('#',$wx['soilTempMinAllTime']);

        $soilMoistAct           = explode ('#',$wx['soilMoistAct']);

        $soilMoistMaxToday      = explode ('#',$wx['soilMoistMaxToday']);
#        $soilMoistMaxTodayTime  = explode ('#',$wx['soilMoistMaxTodayTime']);  
        $soilMoistMaxMonth      = explode ('#',$wx['soilMoistMaxMonth']);
        $soilMoistMaxMonthTime  = explode ('#',$wx['soilMoistMaxMonthTime']); 
        $soilMoistMaxYear       = explode ('#',$wx['soilMoistMaxYear']);
        $soilMoistMaxYearTime   = explode ('#',$wx['soilMoistMaxYearTime']);
        $soilMoistMaxAll        = explode ('#',$wx['soilMoistMaxAll']);
        $soilMoistMaxAllTime    = explode ('#',$wx['soilMoistMaxAllTime']);

        $soilMoistMinToday      = explode ('#',$wx['soilMoistMinToday']);
#        $soilMoistMinTodayTime  = explode ('#',$wx['soilMoistMinTodayTime']); 
#        $soilMoistMinMonth      = explode ('#',$wx['soilMoistMinMonth']);
#        $soilMoistMinMonthTime  = explode ('#',$wx['soilMoistMinMonthTime']); 
#        $soilMoistMinYear       = explode ('#',$wx['soilMoistMinYear']);
#        $soilMoistMinYearTime   = explode ('#',$wx['soilMoistMinYearTime']);
#        $soilMoistMinAll        = explode ('#',$wx['soilMoistMinAll']);
#        $soilMoistMinAllTime    = explode ('#',$wx['soilMoistMinAllTime']);
   
        for  ($n = 1; $n <= $soils; $n++) {
                $i                              = $n - 1;	
                $ws['soilTempAct'][$n]          = wsConvertTemperature  ($soilTempAct[$i],$from_temp);
                $ws['soilTempMaxToday'][$n]     = wsConvertTemperature  ($soilTempMaxToday[$i],$from_temp);
#                $ws['soilTempMaxTodayTime'][$n] = wd_ymd                ($soilTempMaxTodayTime[$i]);
                $ws['soilTempMaxMonth'][$n]     = wsConvertTemperature  ($soilTempMaxMonth[$i],$from_temp);
                $ws['soilTempMaxMonthTime'][$n] = wd_ymd                ($soilTempMaxMonthTime[$i]);
                $ws['soilTempMaxYear'][$n]      = wsConvertTemperature  ($soilTempMaxYear[$i],$from_temp);
                $ws['soilTempMaxYearTime'][$n]  = wd_ymd                ($soilTempMaxYearTime[$i]); 
                $ws['soilTempMaxAll'][$n]       = wsConvertTemperature  ($soilTempMaxAll[$i],$from_temp);
                $ws['soilTempMaxAllTime'][$n]   = wd_ymd                ($soilTempMaxAllTime[$i]);
        
                $ws['soilTempMinToday'][$n]     = wsConvertTemperature  ($soilTempMinToday[$i],$from_temp);
#                $ws['soilTempMinTodayTime'][$n] = wd_ymd                ($soilTempMinTodayTime[$i]);
                $ws['soilTempMinMonth'][$n]     = wsConvertTemperature  ($soilTempMinMonth[$i],$from_temp);
                $ws['soilTempMinMonthTime'][$n] = wd_ymd                ($soilTempMinMonthTime[$i]);
                $ws['soilTempMinYear'][$n]      = wsConvertTemperature  ($soilTempMinYear[$i],$from_temp);
                $ws['soilTempMinYearTime'][$n]  = wd_ymd                ($soilTempMinYearTime[$i]);
                $ws['soilTempMinAll'][$n]   = wsConvertTemperature  ($soilTempMinAll[$i],$from_temp);
                $ws['soilTempMinAllTime'][$n]= wd_ymd               ($soilTempMinAllTime[$i]);

                $ws['moistAct'][$n]	        =                $soilMoistAct[$i]*1.0;
                $ws['moistMaxToday'][$n]	=                $soilMoistMaxToday[$i]*1.0;
#                $ws['moistMaxTodayTime'][$n]	= wd_ymd        ($soilMoistMaxTodayTime[$i]);
                if ($n == 1) {                   
                        $ws['moistMaxMonth'][$n]	=                $soilMoistMaxMonth[$i]*1.0;
                        $ws['moistMaxMonthTime'][$n]	= wd_ymd        ($soilMoistMaxMonthTime[$i]); 
                        $ws['moistMaxYear'][$n]	        =                $soilMoistMaxYear[$i]*1.0;
                        $ws['moistMaxYearTime'][$n]	= wd_ymd        ($soilMoistMaxYearTime[$i]);
                        $ws['moistMaxAll'][$n]	=                $soilMoistMaxAll[$i]*1.0;
                        $ws['moistMaxAllTime'][$n]	= wd_ymd        ($soilMoistMaxAllTime[$i]);
                }
                $ws['moistMinToday'][$n]	=                $soilMoistMinToday[$i]*1.0;
#                $ws['moistMinTodayTime'][$n]	= wd_ymd        ($soilMoistMinTodayTime[$i]);
#                $ws['moistMinMonth'][$n]	=                $soilMoistMinMonth[$i]*1.0;
#                $ws['moistMinMonthTime'][$n]	= wd_ymd        ($soilMoistMinMonthTime[$i]); 
#                $ws['moistMinYear'][$n]	        =                $soilMoistMinYear[$i]*1.0;
#                $ws['moistMinYearTime'][$n]	= wd_ymd        ($soilMoistMinYearTime[$i]);
 #               $ws['moistMinAll'][$n]	=                $soilMoistMinAll[$i]*1.0;
 #               $ws['moistMinAllTime'][$n]	= wd_ymd        ($soilMoistMinAllTime[$i]);
        }      
        if ($SITE['leafUsed'] &&  $SITE['leafCount']	>=  '0') {$doleaf = true; $leafs = floor($SITE['leafCount']); } else {$doleaf = false; $leafs = 0;}
        if ($doleaf) {
                if ($leafs > 2) { echo $startEcho.' reset nr of leaf sensors from '.$SITE['leafCount'].' to max 2 '.$endEcho.PHP_EOL;$leafs  = 2;}
#                $arr_leaf_temp                  = explode ('#',$wx['leafTempAct']);
                $arr_leaf_wet                   = explode ('#',$wx['leafWetAct']);
                if ($leafs > count($arr_leaf_wet) ) {$leafs = count($arr_leaf_wet);}
                for  ($n = 1; $n <= $leafs; $n++) {
                        $i                      = $n - 1;
                        $ws['leafActWet'][$n]	= $arr_leaf_wet[$i];
                }
        } // eo doleaf

} // eo dosoil
$ws['check_ok']         = '3.00';
if ($test) {echo '<pre>'; print_r($ws); exit;}
return;
#
function wd_ymd ($string) {
        global $ymd;
        if ($string == '--' || trim($string) == '') {return '19700101010101';}
        list ($year,$month,$day) = explode (' ',$string);
        return wdYMD($year,$month,$day);
}
// end of tagsVWS.php
?>