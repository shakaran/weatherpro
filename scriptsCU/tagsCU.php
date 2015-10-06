<?php 	# ini_set('display_errors', 'On'); error_reporting(E_ALL);	
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
#  display source of script if requested so
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
$pageName	= 'tagsCU.php';
$pageVersion	= '3.11 2015-07-21';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.11 2015-07-21 releasse 2.7/8 version plus arrow correction
# --------------------------------------- version ----------------------
$tagsScript     = $pageName;
$startEcho      = '<!-- ';      
$endEcho        = ' -->';
#
$test           = false;
if ($test) {echo '<pre>Start test'.PHP_EOL;$startEcho      = ''; $endEcho        = '';}
#
$fileToLoad     = $SITE['wsTags'];              // normaly tagsCU.txt
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
        if ($content  == '' ) {
#                echo $startEcho.$tagsScript.'('.__LINE__.'):empty line '.$n.' name '.$name .$endEcho.PHP_EOL; continue;
        }
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
$ymd                    = $wx['date'];
$ws['actTime']		= cu_time   ($wx['time']);
# ------------------------------------------ temperature ---------------
$from_temp              = $wx['fromtemp'];
$ws['tempAct']		= wsConvertTemperature  ($wx['tempAct'],$from_temp);
$temp1hourAgo		= wsConvertTemperature  ($wx['tempAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['tempDelta'],$from_temp,$from_temp);
$ws['tempDelta']	= $ws['tempAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['tempMinToday']	= wsConvertTemperature  ($wx['tempMinToday'],$from_temp);
$ws['tempMinTodayTime']	= cu_time               ($wx['tempMinTodayTime']);
$ws['tempMinYday']	= wsConvertTemperature  ($wx['tempMinYday'],$from_temp);
$ws['tempMinYdayTime']	= cu_time               ($wx['tempMinYdayTime']);
$ws['tempMinMonth']	= wsConvertTemperature  ($wx['tempMinMonth'],$from_temp);
$ws['tempMinMonthTime']	=                        $wx['tempMinMonthTime'];
$ws['tempMinYear']	= wsConvertTemperature  ($wx['tempMinYear'],$from_temp);
$ws['tempMinYearTime']	=                        $wx['tempMinYearTime'];
$ws['tempMinAll']	= wsConvertTemperature  ($wx['tempMinAll'],$from_temp);
$ws['tempMinAllTime']	=                        $wx['tempMinAllTime'];

$ws['tempMaxToday']	= wsConvertTemperature  ($wx['tempMaxToday'],$from_temp);
$ws['tempMaxTodayTime']	= cu_time               ($wx['tempMaxTodayTime']);
$ws['tempMaxYday']	= wsConvertTemperature  ($wx['tempMaxYday'],$from_temp);
$ws['tempMaxYdayTime']	= cu_time               ($wx['tempMaxYdayTime']);
$ws['tempMaxMonth']	= wsConvertTemperature  ($wx['tempMaxMonth'],$from_temp);
$ws['tempMaxMonthTime']	=                        $wx['tempMaxMonthTime'];
$ws['tempMaxYear']	= wsConvertTemperature  ($wx['tempMaxYear'],$from_temp);
$ws['tempMaxYearTime']	=                        $wx['tempMaxYearTime'];
$ws['tempMaxAll']	= wsConvertTemperature  ($wx['tempMaxAll'],$from_temp);
$ws['tempMaxAllTime']	=                        $wx['tempMaxAllTime'];

$ws['dewpAct']  	= wsConvertTemperature  ($wx['dewpAct'],$from_temp);		
$ws['dewpDelta']	= $ws['dewpAct']
			- wsConvertTemperature  ($wx['dewpDelta'],$from_temp); // no delta but real value
$ws['dewpMinToday']  	= wsConvertTemperature  ($wx['dewpMinToday'],$from_temp);
$ws['dewpMinTodayTime']	= cu_time               ($wx['dewpMinTodayTime']);
$ws['dewpMinYday']  	= wsConvertTemperature  ($wx['dewpMinYday'],$from_temp);
$ws['dewpMinYdayTime']	= cu_time               ($wx['dewpMinYdayTime']);
$ws['dewpMinMonth']	= wsConvertTemperature  ($wx['dewpMinMonth'],$from_temp);
$ws['dewpMinMonthTime']	=                        $wx['dewpMinMonthTime'];
$ws['dewpMinYear']	= wsConvertTemperature  ($wx['dewpMinYear'],$from_temp);
$ws['dewpMinYearTime']	=                        $wx['dewpMinYearTime'];
$ws['dewpMinAll']	= wsConvertTemperature  ($wx['dewpMinAll'],$from_temp);
$ws['dewpMinAllTime']	=                        $wx['dewpMinAllTime'];

$ws['dewpMaxToday']  	= wsConvertTemperature  ($wx['dewpMaxToday'],$from_temp);
$ws['dewpMaxTodayTime']	= cu_time               ($wx['dewpMaxTodayTime']);
$ws['dewpMaxYday']  	= wsConvertTemperature  ($wx['dewpMaxYday'],$from_temp);
$ws['dewpMaxYdayTime']	= cu_time               ($wx['dewpMaxYdayTime']);
$ws['dewpMaxMonth']	= wsConvertTemperature  ($wx['dewpMaxMonth'],$from_temp);
$ws['dewpMaxMonthTime']	=                        $wx['dewpMaxMonthTime'];
$ws['dewpMaxYear']	= wsConvertTemperature  ($wx['dewpMaxYear'],$from_temp);
$ws['dewpMaxYearTime']	=                        $wx['dewpMaxYearTime'];
$ws['dewpMaxAll']	= wsConvertTemperature  ($wx['dewpMaxAll'],$from_temp);
$ws['dewpMaxAllTime']	=                        $wx['dewpMaxAllTime'];

$ws['appTemp']  	        = wsConvertTemperature  ($wx['appTemp'],$from_temp);

$ws['appTempMinToday']  	= wsConvertTemperature  ($wx['appTempMinToday'],$from_temp);
$ws['appTempMinTodayTime']	= cu_time               ($wx['appTempMinTodayTime']);
$ws['appTempMinYday']  	        = wsConvertTemperature  ($wx['appTempMinYday'],$from_temp);
$ws['appTempMinYdayTime']	= cu_time               ($wx['appTempMinYdayTime']);
$ws['appTempMinMonth']	        = wsConvertTemperature  ($wx['appTempMinMonth'],$from_temp);
$ws['appTempMinMonthTime']	=                        $wx['appTempMinMonthTime'];
$ws['appTempMinYear']	        = wsConvertTemperature  ($wx['appTempMinYear'],$from_temp);
$ws['appTempMinYearTime']	=                        $wx['appTempMinYearTime'];
$ws['appTempMinAll']	        = wsConvertTemperature  ($wx['appTempMinAll'],$from_temp);
$ws['appTempMinAllTime']	=                        $wx['appTempMinAllTime'];

$ws['appTempMaxToday']  	= wsConvertTemperature  ($wx['appTempMaxToday'],$from_temp);
$ws['appTempMaxTodayTime']	= cu_time               ($wx['appTempMaxTodayTime']);
$ws['appTempMaxYday']  	        = wsConvertTemperature  ($wx['appTempMaxYday'],$from_temp);
$ws['appTempMaxYdayTime']	= cu_time               ($wx['appTempMaxYdayTime']);
$ws['appTempMaxMonth']	        = wsConvertTemperature  ($wx['appTempMaxMonth'],$from_temp);
$ws['appTempMaxMonthTime']	=                        $wx['appTempMaxMonthTime'];
$ws['appTempMaxYear']	        = wsConvertTemperature  ($wx['appTempMaxYear'],$from_temp);
$ws['appTempMaxYearTime']	=                        $wx['appTempMaxYearTime'];
$ws['appTempMaxAll']	        = wsConvertTemperature  ($wx['appTempMaxAll'],$from_temp);
$ws['appTempMaxAllTime']	=                        $wx['appTempMaxAllTime'];

$ws['heatAct']  	= wsConvertTemperature  ($wx['heatAct'],$from_temp);
$ws['heatDelta']	= $ws['heatAct'] 
			- wsConvertTemperature  ($wx['heatDelta'],$from_temp);  // no delta but real value
$ws['heatMaxToday']  	= wsConvertTemperature  ($wx['heatMaxToday'],$from_temp);
$ws['heatMaxTodayTime']	= cu_time               ($wx['heatMaxTodayTime']);
$ws['heatMaxYday']  	= wsConvertTemperature  ($wx['heatMaxYday'],$from_temp);
$ws['heatMaxYdayTime']	= cu_time               ($wx['heatMaxYdayTime']);
$ws['heatMaxMonth']	= wsConvertTemperature  ($wx['heatMaxMonth'],$from_temp);
$ws['heatMaxMonthTime'] =                        $wx['heatMaxMonthTime'];
$ws['heatMaxYear']	= wsConvertTemperature  ($wx['heatMaxYear'],$from_temp);
$ws['heatMaxYearTime']	=                        $wx['heatMaxYearTime'];
$ws['heatMaxAll']	= wsConvertTemperature  ($wx['heatMaxAll'],$from_temp);
$ws['heatMaxAllTime']	=                        $wx['heatMaxAllTime'];

$ws['chilAct']  	= wsConvertTemperature  ($wx['chilAct'],$from_temp);
$ws['chilDelta']	= $ws['chilAct'] 
			- wsConvertTemperature  ($wx['chilDelta'],$from_temp);  // no delta but real value
$ws['chilMinToday']  	= wsConvertTemperature  ($wx['chilMinToday'],$from_temp);
$ws['chilMinTodayTime']	= cu_time               ($wx['chilMinTodayTime']);
$ws['chilMinYday']  	= wsConvertTemperature  ($wx['chilMinYday'],$from_temp);
$ws['chilMinYdayTime']	= cu_time               ($wx['chilMinYdayTime']);
$ws['chilMinMonth']  	= wsConvertTemperature  ($wx['chilMinMonth'],$from_temp);
$ws['chilMinMonthTime']	=                        $wx['chilMinMonthTime'];
$ws['chilMinYear']  	= wsConvertTemperature  ($wx['chilMinYear'],$from_temp);
$ws['chilMinYearTime']	=                        $wx['chilMinYearTime'];
$ws['chilMinAll']  	= wsConvertTemperature  ($wx['chilMinAll'],$from_temp);
$ws['chilMinAllTime']	=                        $wx['chilMinAllTime'];

#$ws['chilMaxToday']  	= wsConvertTemperature  ($wx['chilMinToday'],$from_temp);
#$ws['chilMaxTodayTime']= cu_time               ($wx['chilMaxTodayTime']);

$ws['tempActInside']	= wsConvertTemperature  ($wx['tempActInside'],$from_temp);
$ws['tempActExtra1']	= wsConvertTemperature  ($wx['tempActExtra1'],$from_temp);	
$ws['tempActExtra2']	= wsConvertTemperature  ($wx['tempActExtra2'],$from_temp);	
$ws['tempToday']	= $ws['tempAct'];

$ws['hudxAct'] 	        = wsConvertTemperature  ($wx['hudxAct'],$from_temp);
# ------------------------------------------ pressure / baro -----------
$from_baro              = trim(strtolower($wx['frombaro']));     // ' hPa', of ' mb', of ' inHg'

$ws['baroAct'] 	        = wsConvertBaro ($wx['baroAct'],$from_baro);
$ws['baroDelta']	= wsConvertBaro ($wx['baroDelta'],$from_baro);
$ws['baroTrend']	= langtransstr  ($wx['baroTrend']);

$ws['baroMinToday']	= wsConvertBaro ($wx['baroMinToday'],$from_baro);
$ws['baroMinTodayTime']	= cu_time       ($wx['baroMinTodayTime']);
$ws['baroMinYday']	= wsConvertBaro ($wx['baroMinYday'],$from_baro);
$ws['baroMinYdayTime']	= cu_time       ($wx['baroMinYdayTime']);
$ws['baroMinMonth']	= wsConvertBaro ($wx['baroMinMonth'],$from_baro);
$ws['baroMinMonthTime']	=                $wx['baroMinMonthTime'];
$ws['baroMinYear'] 	= wsConvertBaro ($wx['baroMinYear'],$from_baro);	
$ws['baroMinYearTime']	=                $wx['baroMinYearTime'];
$ws['baroMinAll'] 	= wsConvertBaro ($wx['baroMinAll'],$from_baro);	
$ws['baroMinAllTime']	=                $wx['baroMinAllTime'];

$ws['baroMaxToday']	= wsConvertBaro ($wx['baroMaxToday'],$from_baro);
$ws['baroMaxTodayTime'] = cu_time       ($wx['baroMaxTodayTime']);
$ws['baroMaxYday']	= wsConvertBaro ($wx['baroMaxYday'],$from_baro);
$ws['baroMaxYdayTime']	= cu_time       ($wx['baroMaxYdayTime']);
$ws['baroMaxMonth']	= wsConvertBaro ($wx['baroMaxMonth'],$from_baro);
$ws['baroMaxMonthTime']	=                $wx['baroMaxMonthTime'];
$ws['baroMaxYear'] 	= wsConvertBaro ($wx['baroMaxYear'],$from_baro);
$ws['baroMaxYearTime']	=                $wx['baroMaxYearTime'];
$ws['baroMaxAll'] 	= wsConvertBaro ($wx['baroMaxAll'],$from_baro);
$ws['baroMaxAllTime']	=                $wx['baroMaxAllTime'];
# ------------------------------------------ humidity  -----------------
$ws['humiAct']			= $wx['humiAct']*1.0;
$ws['humiDelta']		= $ws['humiAct'] - $wx['humiDelta']*1.0;

$ws['humiMinToday'] 		=                $wx['humiMinToday']*1.0;
$ws['humiMinTodayTime']	        = cu_time       ($wx['humiMinTodayTime']);
$ws['humiMinYday'] 		=                $wx['humiMinYday']*1.0;
$ws['humiMinYdayTime']	        = cu_time       ($wx['humiMinYdayTime']);
$ws['humiMinMonth'] 		=                $wx['humiMinMonth']*1.0;
$ws['humiMinMonthTime']	        =                $wx['humiMinMonthTime'];
$ws['humiMinYear'] 		=                $wx['humiMinYear']*1.0;
$ws['humiMinYearTime']	        =                $wx['humiMinYearTime'];
$ws['humiMinAll'] 		=                $wx['humiMinAll']*1.0;
$ws['humiMinAllTime']	        =                $wx['humiMinAllTime'];

$ws['humiMaxToday']		=                $wx['humiMaxToday']*1.0;
$ws['humiMaxTodayTime']	        = cu_time       ($wx['humiMaxTodayTime']);
$ws['humiMaxYday']		=                $wx['humiMaxYday']*1.0;
$ws['humiMaxYdayTime']	        = cu_time       ($wx['humiMaxYdayTime']);
$ws['humiMaxMonth'] 		=                $wx['humiMaxMonth']*1.0;
$ws['humiMaxMonthTime']	        =                $wx['humiMaxMonthTime'];
$ws['humiMaxYear'] 		=                $wx['humiMaxYear']*1.0;
$ws['humiMaxYearTime']	        =                $wx['humiMaxYearTime'];
$ws['humiMaxAll'] 		=                $wx['humiMaxAll']*1.0;
$ws['humiMaxAllTime']	        =                $wx['humiMaxAllTime'];

$ws['humiInAct']		=                $wx['humiInAct']*1.0;
$ws['humiActExtra1']		=                $wx['humiActExtra1']*1.0;
$ws['humiActExtra2']		=                $wx['humiActExtra2']*1.0;
# ------------------------------------------ rain  ---------------------
$from_rain      = trim(strtolower($wx['fromrain']));     // 'mm',  'in'

$ws['rainRateAct'] 	        = wsConvertRainfall     ($wx['rainRateAct'],$from_rain);
$ws['rainHourAct']              = $ws['rainHour'] = wsConvertRainfall     ($wx['rainHourAct'],$from_rain);

$ws['rainRateMaxToday'] 	= wsConvertRainfall     ($wx['rainRateMaxToday'],$from_rain);
$ws['rainRateMaxYday'] 	        = wsConvertRainfall     ($wx['rainRateMaxYday'],$from_rain);

$ws['lastRainTip']              = $ws['lastRained']    = date('YmdHis',strtotime($wx['lastRained']));

$ws['rainToday']	        = wsConvertRainfall     ($wx['rainToday'],$from_rain);
$ws['rainYday']	                = wsConvertRainfall     ($wx['rainYday'],$from_rain);
#$ws['rainWeek']                = wsConvertRainfall     ($wx['rainWeek']),$from_rain);
$ws['rainMonth']	        = wsConvertRainfall     ($wx['rainMonth'],$from_rain);
$ws['rainYear']		        = wsConvertRainfall     ($wx['rainYear'],$from_rain);

#$ws['rainTodayLow']	        = wsConvertRainfall     ($wx['rainTodayLow'],$from_rain);
#$ws['rainYdayLow']	        = wsConvertRainfall     ($wx['rainYdayLow'],$from_rain);
#$ws['rainYdayHigh']	        = wsConvertRainfall     ($wx['rainYdayHigh'],$from_rain);
#$ws['rainRateYday']	        = wsConvertRainfall     ($wx['rainRateYday'],$from_rain);
#$ws['rainDayMnth'] 		=                        $wx['rainDayMnth']*1.0;
#$ws['rainDayYear'] 		=                        $wx['rainDayYear']*1.0;
#$ws['rainDaysWithNo'] 		=                        $wx['rainDaysWithNo']*1.0;
# ------------------------------------------ EVAPOTRANSPIRATION --------
$ws['etToday'] 		        = wsConvertRainfall     ($wx['etToday'],$from_rain);
#$ws['etYday'] 		        = wsConvertRainfall     ($wx['etYday'],$from_rain);
#$ws['etMonth'] 		= wsConvertRainfall     ($wx['etMonth'],$from_rain);
#$ws['etYear'] 		        = wsConvertRainfal      l($wx['etYear'],$from_rain);
# ------------------------------------------ wind  ---------------------
$from_wind      = trim(strtolower($wx['fromwind']));       //  "m/s", "mph", "km/h", "kts" ?? 'kmh' 'Bft'??  windrun  "km", "miles",

$ws['windAct']		        = wsConvertWindspeed    ($wx['windAct'], $from_wind);
$ws['gustAct']		        = wsConvertWindspeed    ($wx['gustAct'], $from_wind);

$ws['windActDsc']		= wsConvertWinddir      ($wx['windActDir']); // $wx['windActDsc']
$ws['windActDir']		=                        $wx['windActDir']; 
$ws['windAvgDir']               =                       $wx['windAvgDir'];

$ws['windBeafort']		=                        $wx['windBeafort'];

$ws['gustMaxToday']	        = wsConvertWindspeed    ($wx['gustMaxToday'], $from_wind);
$ws['gustMaxTodayTime']         = cu_time               ($wx['gustMaxTodayTime']);
$ws['gustMaxYday']	        = wsConvertWindspeed    ($wx['gustMaxYday'], $from_wind);
$ws['gustMaxYdayTime']          = cu_time               ($wx['gustMaxYTime']);
$ws['gustMaxMonth']	        = wsConvertWindspeed    ($wx['gustMaxMonth'], $from_wind);
$ws['gustMaxMonthTime']	        =                        $wx['gustMaxMonthTime'];
$ws['gustMaxYear']	        = wsConvertWindspeed    ($wx['gustMaxYear'], $from_wind);	
$ws['gustMaxYearTime']	        =                        $wx['gustMaxYearTime'];
$ws['gustMaxAll']	        = wsConvertWindspeed    ($wx['gustMaxAll'], $from_wind);	
$ws['gustMaxAllTime']	        =                        $wx['gustMaxAllTime'];

if ($ws['gustAct'] < $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}

$from_distance                  = trim(strtolower($wx['fromdistance']));

$ws['windrunToday']             = wsConvertDistance     ($wx['windrunToday'],$from_distance);
# ------------------------------------------  UV   ---------------------
$ws['uvAct']			=                        $wx['uvAct']*1.0;
$ws['uvMaxToday']		=                        $wx['uvMaxToday']*1.0;
$ws['uvMaxTodayTime'] 		= cu_time               ($wx['uvMaxTodayTime']);
$ws['uvMaxYday']		=                        $wx['uvMaxYday']*1.0;
$ws['uvMaxYdayTime'] 		= cu_time               ($wx['uvMaxYdayTime']);

#$ws['uvMaxMonth']		= $wx['uvMaxMonth']*1.0;
#$ws['uvMaxMonthTime'] 		= $wx['uvMaxMonthTime'];
#$ws['uvMaxYear']		= $wx['uvMaxYear']*1.0;
#$ws['uvMaxYearTime'] 		= $wx['uvMaxYearTime'];
# ------------------------------------------ Solar  --------------------
$ws['solarAct']			=                        $wx['solarAct']*1.0;
$ws['solarMaxToday']		=                        $wx['solarMaxToday']*1.0;
$ws['solarMaxTodayTime'] 	= cu_time               ($wx['solarMaxTodayTime']);
$ws['solarMaxYday']		=                        $wx['solarMaxYday']*1.0;
$ws['solarMaxYdayTime'] 	= cu_time               ($wx['solarMaxYdayTime']);

#$ws['solarActPerc']		= $wx['solarActPerc']*1.0;

#$ws['solarMaxMonth']		= $wx['solarMaxMonth']*1.0;
#$ws['solarMaxMonthTime'] 	= $wx['solarMaxMonthTime'];
#$ws['solarMaxYear']		= $wx['solarMaxYear']*1.0;
#$ws['solarMaxYearTime'] 	= $wx['solarMaxYearTime'];
#------------------------------------------cloudheight------------------
$from_height                    = trim(strtolower($wx['fromheight']));
$ws['cloudHeight']	        = wsConvertDistance    ($wx['cloudHeight'], $from_height);
# ------------------------------------------ forecasts -----------------
$ws['fcstTxt']                  = trim($wx['fcstTxt']);
# ------------------------------------------  moon ---------------------
$ws['moonrise']			= $wx['moonrise'];
$ws['moonset']			= $wx['moonset'];
$ws['lunarPhasePerc']           =                        $wx['lunarPhasePerc']*1.0;
$ws['lunarAge']			=                        $wx['lunarAge']*1.0;
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
$ws['wsHardware'] 		= $wx['wsHardware'];
$ws['wsUptime']			= $wx['wsUptime'];
#-----------------------------------------------------------------------
if ($SITE['soilUsed'] &&  $SITE['soilCount']	>=  '0') {$soils = floor($SITE['soilCount']);  } else {$soils = 0;}
if ($soils > 4) {echo $startEcho.' reset nr of soil sensors from '.$SITE['soilCount'].' to max 4 '.$endEcho.PHP_EOL; $soils  = 4;}
$arr_temp                       = explode (' ',$wx['soilTempAct']);
$arr_moist                      = explode (' ',$wx['soilMoistAct']);
$arr_leaf                       = explode (' ',$wx['leafWetAct']);
for  ($n = 1; $n <= $soils; $n++) {
        $i                      = $n - 1;	
        $ws['soilTempAct'][$n]  = wsConvertTemperature  ($arr_temp[$i],$from_temp);
        $ws['moistAct'][$n]	= $arr_moist[$i];
}
if ($SITE['leafUsed'] &&  $SITE['leafCount']	>=  '0') {$leafs = floor($SITE['leafCount']); } else {$leafs = 0;}
if ($leafs > 2) { echo $startEcho.' reset nr of leaf sensors from '.$SITE['leafCount'].' to max 2 '.$endEcho.PHP_EOL;$leafs  = 2;}
for  ($n = 1; $n <= $leafs; $n++) {
        $i                      = $n - 1;
        $ws['leafAct'][$n]	= $arr_leaf[$i];
}
$ws['check_ok']         = '3.00';
#echo '<pre>'; print_r($ws); exit;
#
function cu_time   ($time){  // for todays time stamps: remove ':' in time and combine to YYYYMMDDHHMMSS
        global $ymd;            // 13:30   03:30 
        if (trim($time) == '?') {return $ymd.'000000';}
        $result = str_replace ('-','',$time);
        if (trim($result) == ''){return $ymd.'000000';}
        $int = strtotime($time);
        return ($ymd.strftime('%H%M%S',$int) );
}
// end of tagsVWS.php
#echo '<pre>'; print_r($ws); exit;
?>