<?php 	# ini_set('display_errors', 'On'); error_reporting(E_ALL);	
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
$pageName	= 'tagsMB.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.11 2015-07-21';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.11 2015-07-21 releasse 2.7 version plus arrow correction
# ----------------------------------------------------------------------
$tagsScript     = $pageName;
$startEcho      = '<!-- ';      
$endEcho        = ' -->';
#
$test           = false;
if ($test) {echo '<pre>Start test'.PHP_EOL;$startEcho      = ''; $endEcho        = '';}
#
$fileToLoad     = $SITE['wsTags'];              // normaly tagsMB.txt
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
#        if ($content  == '' ) {echo $startEcho.$tagsScript.'('.__LINE__.'):empty line '.$n.' name '.$name .$endEcho.PHP_EOL; continue; }
        $wx[$name]=$content;
}
#print_r ($wx);  echo '------------------halt1'; exit;
$ws['tags_processed']	= $pageName.'-'.$pageVersion;
$ws['tags_originated']	= $SITE['wsTags'].'-'.  $wx['pagename'];
$ws['tags_today']	= $ws['tags_originated'];
$ws['tags_yday']	= $ws['tags_originated'];
$ws['tags_today_time']	= $wx['time'].$wx['time'];
$ws['tags_yday_time']	= 'n/a';
# ----------------------------------------------------------------------
$ymd                    = str_replace(' ','',$wx['date']);
$ws['actTime']		= $ymd. str_replace(' ','',$wx['time']);
# ------------------------------------------ temperature ---------------
$from_temp              = $wx['fromtemp'];
$ws['tempAct']		= wsConvertTemperature  ($wx['tempAct'],$from_temp);
$ws['tempDelta']	= $ws['tempAct'] - wsConvertTemperature  ($wx['tempDelta'],$from_temp);

$ws['tempMinToday']	= wsConvertTemperature  ($wx['tempMinToday'],$from_temp);
$ws['tempMinTodayTime']	= mbdate                ($wx['tempMinTodayTime']);
$ws['tempMinYday']	= wsConvertTemperature  ($wx['tempMinYday'],$from_temp);
$ws['tempMinYdayTime']	= mbdate                ($wx['tempMinYdayTime']);
$ws['tempMinMonth']	= wsConvertTemperature  ($wx['tempMinMonth'],$from_temp);
$ws['tempMinMonthTime']	= mb_ymd                ($wx['tempMinMonthTime']);
$ws['tempMinYear']	= wsConvertTemperature  ($wx['tempMinYear'],$from_temp);
$ws['tempMinYearTime']	= mb_ymd                ($wx['tempMinYearTime']);
$ws['tempMinAll']	= wsConvertTemperature  ($wx['tempMinAll'],$from_temp);
$ws['tempMinAllTime']	= mb_ymd                ($wx['tempMinAllTime']);


$ws['tempMaxToday']	= wsConvertTemperature  ($wx['tempMaxToday'],$from_temp);
$ws['tempMaxTodayTime']	= mbdate                ($wx['tempMaxTodayTime']);
$ws['tempMaxYday']	= wsConvertTemperature  ($wx['tempMaxYday'],$from_temp);
$ws['tempMaxYdayTime']	= mbdate                ($wx['tempMaxYdayTime']);
$ws['tempMaxMonth']	= wsConvertTemperature  ($wx['tempMaxMonth'],$from_temp);
$ws['tempMaxMonthTime']	= mb_ymd                ($wx['tempMaxMonthTime']);
$ws['tempMaxYear']	= wsConvertTemperature  ($wx['tempMaxYear'],$from_temp);
$ws['tempMaxYearTime']	= mb_ymd                ($wx['tempMaxYearTime']);
$ws['tempMaxAll']	= wsConvertTemperature  ($wx['tempMaxAll'],$from_temp);
$ws['tempMaxAllTime']	= mb_ymd                ($wx['tempMaxAllTime']);

$ws['dewpAct']  	= wsConvertTemperature  ($wx['dewpAct'],$from_temp);
$ws['dewpDelta']	= $ws['dewpAct'] - wsConvertTemperature  ($wx['dewpDelta'],$from_temp);

$ws['dewpMinToday']  	= wsConvertTemperature  ($wx['dewpMinToday'],$from_temp);
$ws['dewpMinTodayTime']	= mbdate                ($wx['dewpMinTodayTime']);
$ws['dewpMinYday']  	= wsConvertTemperature  ($wx['dewpMinYday'],$from_temp);
$ws['dewpMinYdayTime']	= mbdate                ($wx['dewpMinYdayTime']);
$ws['dewpMinMonth']  	= wsConvertTemperature  ($wx['dewpMinMonth'],$from_temp);
$ws['dewpMinMonthTime']	= mbdate                ($wx['dewpMinMonthTime']);
$ws['dewpMinYear']  	= wsConvertTemperature  ($wx['dewpMinYear'],$from_temp);
$ws['dewpMinYearTime']	= mbdate                ($wx['dewpMinYearTime']);
$ws['dewpMinAll']  	= wsConvertTemperature  ($wx['dewpMinAll'],$from_temp);
$ws['dewpMinAllTime']	= mbdate                ($wx['dewpMinAllTime']);

$ws['dewpMaxToday']  	= wsConvertTemperature  ($wx['dewpMaxToday'],$from_temp);
$ws['dewpMaxTodayTime']	= mbdate                ($wx['dewpMaxTodayTime']);
$ws['dewpMaxYday']  	= wsConvertTemperature  ($wx['dewpMaxYday'],$from_temp);
$ws['dewpMaxYdayTime']	= mbdate                ($wx['dewpMaxYdayTime']);
$ws['dewpMaxMonth']  	= wsConvertTemperature  ($wx['dewpMaxMonth'],$from_temp);
$ws['dewpMaxMonthTime']	= mbdate                ($wx['dewpMaxMonthTime']);
$ws['dewpMaxYear']  	= wsConvertTemperature  ($wx['dewpMaxYear'],$from_temp);
$ws['dewpMaxYearTime']	= mbdate                ($wx['dewpMaxYearTime']);
$ws['dewpMaxAll']  	= wsConvertTemperature  ($wx['dewpMaxAll'],$from_temp);
$ws['dewpMaxAllTime']	= mbdate                ($wx['dewpMaxAllTime']);

$ws['appTemp']  	        = 'n/a'; #wsConvertTemperature  ($wx['appTemp'],$from_temp);

$ws['appTempMinToday']  	= 'n/a'; #wsConvertTemperature  ($wx['appTempMinToday'],$from_temp);
$ws['appTempMinTodayTime']	= 'n/a'; #mbdate                ($wx['appTempMinTodayTime']);
$ws['appTempMinYday']  	        = 'n/a'; #wsConvertTemperature  ($wx['appTempMinYday'],$from_temp);
$ws['appTempMinYdayTime']	= 'n/a'; #mbdate                ($wx['appTempMinYdayTime']);

$ws['appTempMaxToday']  	= 'n/a'; #wsConvertTemperature  ($wx['appTempMaxToday'],$from_temp);
$ws['appTempMaxTodayTime']	= 'n/a'; #mbdate                ($wx['appTempMaxTodayTime']);
$ws['appTempMaxYday']  	        = 'n/a'; #wsConvertTemperature  ($wx['appTempMaxYday'],$from_temp);
$ws['appTempMaxYdayTime']	= 'n/a'; #mbdate                ($wx['appTempMaxYdayTime']);

$ws['heatAct']  	= wsConvertTemperature  ($wx['heatAct'],$from_temp);
$ws['heatDelta']	= $ws['heatAct'] - wsConvertTemperature  ($wx['heatDelta'],$from_temp);

$ws['heatMaxToday']  	= wsConvertTemperature  ($wx['heatMaxToday'],$from_temp);
$ws['heatMaxTodayTime']	= mbdate                ($wx['heatMaxTodayTime']);
$ws['heatMaxYday']  	= wsConvertTemperature  ($wx['heatMaxYday'],$from_temp);
$ws['heatMaxYdayTime']	= mbdate                ($wx['heatMaxYdayTime']);
$ws['heatMaxMonth']	= wsConvertTemperature  ($wx['heatMaxMonth'],$from_temp);
$ws['heatMaxMonthTime'] = mb_ymd                ($wx['heatMaxMonthTime']);
$ws['heatMaxYear']	= wsConvertTemperature  ($wx['heatMaxYear'],$from_temp);
$ws['heatMaxYearTime']	= mb_ymd                ($wx['heatMaxYearTime']);
$ws['heatMaxAll']	= wsConvertTemperature  ($wx['heatMaxAll'],$from_temp);
$ws['heatMaxAllTime']	= mb_ymd                ($wx['heatMaxAllTime']);

$ws['chilAct']  	= wsConvertTemperature  ($wx['chilAct'],$from_temp);
$ws['chilDelta']	= $ws['chilAct'] - wsConvertTemperature  ($wx['chilDelta'],$from_temp);

$ws['chilMinToday']  	= wsConvertTemperature  ($wx['chilMinToday'],$from_temp);
$ws['chilMinTodayTime']	= mbdate                ($wx['chilMinTodayTime']);
$ws['chilMinYday']  	= wsConvertTemperature  ($wx['chilMinYday'],$from_temp);
$ws['chilMinYdayTime']	= mbdate                ($wx['chilMinYdayTime']);
$ws['chilMinMonth']  	= wsConvertTemperature  ($wx['chilMinMonth'],$from_temp);
$ws['chilMinMonthTime']	= mb_ymd                ($wx['chilMinMonthTime']);
$ws['chilMinYear']  	= wsConvertTemperature  ($wx['chilMinYear'],$from_temp);
$ws['chilMinYearTime']	= mb_ymd                ($wx['chilMinYearTime']);
$ws['chilMinAll']  	= wsConvertTemperature  ($wx['chilMinAll'],$from_temp);
$ws['chilMinAllTime']	= mb_ymd                ($wx['chilMinAllTime']);

$ws['chilMaxToday']  	= wsConvertTemperature  ($wx['chilMinToday'],$from_temp);
$ws['chilMaxTodayTime'] = mbdate                ($wx['chilMaxTodayTime']);

$ws['tempActInside']	= wsConvertTemperature  ($wx['tempActInside'],$from_temp);
$ws['tempActExtra1']	= wsConvertTemperature  ($wx['tempActExtra1'],$from_temp);	
$ws['tempActExtra2']	= wsConvertTemperature  ($wx['tempActExtra2'],$from_temp);	
$ws['tempToday']	= $ws['tempAct'];

if (isset ($wx['fromhudx']) ) {$from = $wx['fromhudx'];} else {$from = $from_temp;}
$ws['hudxAct'] 	        = wsConvertTemperature  ($wx['hudxAct'],$from_temp);

# ------------------------------------------ pressure / baro -----------
$from_baro              = trim(strtolower($wx['frombaro']));     // ' hPa', of ' mb', of ' inHg'

$ws['baroAct'] 	        = wsConvertBaro ($wx['baroAct'],$from_baro);
$ws['baroDelta']	= $ws['baroAct'] - wsConvertBaro ($wx['baroDelta'],$from_baro);
$ws['baroTrend']	= langtransstr  (wsBarotrendText($ws['baroDelta']));

$ws['baroMinToday']	= wsConvertBaro ($wx['baroMinToday'],$from_baro);
$ws['baroMinTodayTime']	= mbdate        ($wx['baroMinTodayTime']);
$ws['baroMinYday']	= wsConvertBaro ($wx['baroMinYday'],$from_baro);
$ws['baroMinYdayTime']	= mbdate        ($wx['baroMinYdayTime']);
$ws['baroMinMonth']	= wsConvertBaro ($wx['baroMinMonth'],$from_baro);
$ws['baroMinMonthTime']	= mb_ymd        ($wx['baroMinMonthTime']);
$ws['baroMinYear'] 	= wsConvertBaro ($wx['baroMinYear'],$from_baro);	
$ws['baroMinYearTime']	= mb_ymd        ($wx['baroMinYearTime']);
$ws['baroMinAll'] 	= wsConvertBaro ($wx['baroMinAll'],$from_baro);	
$ws['baroMinAllTime']	= mb_ymd        ($wx['baroMinAllTime']);

$ws['baroMaxToday']	= wsConvertBaro ($wx['baroMaxToday'],$from_baro);
$ws['baroMaxTodayTime'] = mbdate        ($wx['baroMaxTodayTime']);
$ws['baroMaxYday']	= wsConvertBaro ($wx['baroMaxYday'],$from_baro);
$ws['baroMaxYdayTime']	= mb_ymd        ($wx['baroMaxYdayTime']);
$ws['baroMaxMonth']	= wsConvertBaro ($wx['baroMaxMonth'],$from_baro);
$ws['baroMaxMonthTime']= mb_ymd        ($wx['baroMaxMonthTime']);
$ws['baroMaxYear']	= wsConvertBaro ($wx['baroMaxYear'],$from_baro);
$ws['baroMaxYearTime']	= mb_ymd        ($wx['baroMaxYearTime']);
$ws['baroMaxAll']	= wsConvertBaro ($wx['baroMaxAll'],$from_baro);
$ws['baroMaxAllTime']	= mb_ymd        ($wx['baroMaxAllTime']);
# ------------------------------------------ humidity  -----------------
$ws['humiAct']			= $wx['humiAct']*1.0;
$ws['humiDelta']		= $ws['humiAct'] - $wx['humiDelta']*1.0;

$ws['humiMinToday'] 		=                $wx['humiMinToday']*1.0;
$ws['humiMinTodayTime']	        = mbdate        ($wx['humiMinTodayTime']);
$ws['humiMinYday'] 		=                $wx['humiMinYday']*1.0;
$ws['humiMinYdayTime']	        = mbdate        ($wx['humiMinYdayTime']);
$ws['humiMinMonth'] 		=                $wx['humiMinMonth']*1.0;
$ws['humiMinMonthTime']	        = mbdate        ($wx['humiMinMonthTime']);
$ws['humiMinYear'] 		=                $wx['humiMinYear']*1.0;
$ws['humiMinYearTime']	        = mbdate        ($wx['humiMinYearTime']);
$ws['humiMinAll'] 		=                $wx['humiMinAll']*1.0;
$ws['humiMinAllTime']	        = mbdate        ($wx['humiMinAllTime']);

$ws['humiMaxToday']		=                $wx['humiMaxToday']*1.0;
$ws['humiMaxTodayTime']	        = mbdate        ($wx['humiMaxTodayTime']);
$ws['humiMaxYday']		=                $wx['humiMaxYday']*1.0;
$ws['humiMaxYdayTime']	        = mbdate        ($wx['humiMaxYdayTime']);
$ws['humiMaxMonth']		=                $wx['humiMaxMonth']*1.0;
$ws['humiMaxMonthTime']	        = mbdate        ($wx['humiMaxMonthTime']);
$ws['humiMaxYear']		=                $wx['humiMaxYear']*1.0;
$ws['humiMaxYearTime']	        = mbdate        ($wx['humiMaxYearTime']);
$ws['humiMaxAll']		=                $wx['humiMaxAll']*1.0;
$ws['humiMaxAllTime']	        = mbdate        ($wx['humiMaxAllTime']);

$ws['humiInAct']		=                $wx['humiInAct']*1.0;
$ws['humiInDelta']		= $ws['humiInAct'] - $wx['humiDelta']*1.0;
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
#$ws['rainWeek']                 = wsConvertRainfall     ($wx['rainWeek'],$from_rain);
$ws['rainMonth']	        = wsConvertRainfall     ($wx['rainMonth'],$from_rain);
$ws['rainYear']		        = wsConvertRainfall     ($wx['rainYear'],$from_rain);
$ws['rainAll']		        = wsConvertRainfall     ($wx['rainAll'],$from_rain);

$ws['rainTodayLow']	        = wsConvertRainfall     ($wx['rainTodayLow'],$from_rain);
$ws['rainYdayLow']	        = wsConvertRainfall     ($wx['rainYdayLow'],$from_rain);
$ws['rainYdayHigh']	        = wsConvertRainfall     ($wx['rainYdayHigh'],$from_rain);
$ws['rainRateYday']	        = wsConvertRainfall     ($wx['rainRateYday'],$from_rain);
$ws['rainDayMnth'] 		= 'n/a'; #               $wx['rainDayMnth']*1.0;
$ws['rainDayYear'] 		= 'n/a'; #               $wx['rainDayYear']*1.0;
$ws['rainDaysWithNo'] 		= 'n/a'; #               $wx['rainDaysWithNo']*1.0;
# ------------------------------------------ EVAPOTRANSPIRATION --------
$ws['etToday'] 		        = wsConvertRainfall     ($wx['etToday'],$from_rain);
$ws['etYday'] 		        = wsConvertRainfall     ($wx['etYday'],$from_rain);
$ws['etMonth'] 		        = wsConvertRainfall     ($wx['etMonth'],$from_rain);
$ws['etYear'] 		        = wsConvertRainfall     ($wx['etYear'],$from_rain);
$ws['etAll'] 		        = wsConvertRainfall     ($wx['etAll'],$from_rain);
# ------------------------------------------ wind  ---------------------
$from_wind      = trim(strtolower($wx['fromwind']));       //  "m/s", "mph", "km/h", "kts" ?? 'kmh' 'Bft'??  windrun  "km", "miles",

$ws['windAct']		        = wsConvertWindspeed    ($wx['windAct'], $from_wind);
$ws['gustAct']		        = wsConvertWindspeed    ($wx['gustAct'], $from_wind);

$ws['windActDsc']		=                        $wx['windActDsc'];  //NNE
$ws['windActDir']		=                        $wx['windActDir']*1.0;  // 20 deg
$ws['windAvgDir']               =                        $wx['windAvgDir']*1.0;

$ws['windBeafort']		=                        $wx['windBeafort'];

$ws['gustMaxToday']	        = wsConvertWindspeed    ($wx['gustMaxToday'], $from_wind);
$ws['gustMaxTodayTime']         = mbdate                ($wx['gustMaxTodayTime']);
$ws['gustMaxYday']	        = wsConvertWindspeed    ($wx['gustMaxYday'], $from_wind);
$ws['gustMaxYdayTime']          = mbdate                ($wx['gustMaxYTime']);
$ws['gustMaxMonth']	        = wsConvertWindspeed    ($wx['gustMaxMonth'], $from_wind);
$ws['gustMaxMonthTime']	        = mb_ymd                ($wx['gustMaxMonthTime']);
$ws['gustMaxYear']	        = wsConvertWindspeed    ($wx['gustMaxYear'], $from_wind);	
$ws['gustMaxYearTime']	        = mb_ymd                ($wx['gustMaxYearTime']);
$ws['gustMaxAll']	        = wsConvertWindspeed    ($wx['gustMaxAll'], $from_wind);	
$ws['gustMaxAllTime']	        = mb_ymd                ($wx['gustMaxAllTime']);

if ($ws['gustAct'] < $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}

#$from_distance                  = trim(strtolower($wx['fromdistance']));
#$ws['windrunToday']             = wsConvertDistance     ($wx['windrunToday'],$from_distance);
# ------------------------------------------  UV   ---------------------
$ws['uvAct']			=                        $wx['uvAct']*1.0;
$ws['uvMaxToday']		=                        $wx['uvMaxToday']*1.0;
$ws['uvMaxTodayTime'] 		= mbdate                ($wx['uvMaxTodayTime']);
$ws['uvMaxYday']		=                        $wx['uvMaxYday']*1.0;
$ws['uvMaxYdayTime'] 		= mbdate                ($wx['uvMaxYdayTime']);
$ws['uvMaxMonth']		=                        $wx['uvMaxMonth']*1.0;
$ws['uvMaxMonthTime'] 		= mb_ymd                ($wx['uvMaxMonthTime']);
$ws['uvMaxYear']		=                        $wx['uvMaxYear']*1.0;
$ws['uvMaxYearTime'] 		= mb_ymd                ($wx['uvMaxYearTime']);
$ws['uvMaxAll']		=                                $wx['uvMaxAll']*1.0;
$ws['uvMaxAllTime'] 		= mb_ymd                ($wx['uvMaxAllTime']);
# ------------------------------------------ Solar  --------------------
$ws['solarAct']			=                        $wx['solarAct']*1.0;
#$ws['solarActPerc']		=                        $wx['solarActPerc']*1.0;

$ws['solarMaxToday']		=                        $wx['solarMaxToday']*1.0;
$ws['solarMaxTodayTime'] 	= mbdate                ($wx['solarMaxTodayTime']);
$ws['solarMaxYday']		=                        $wx['solarMaxYday']*1.0;
$ws['solarMaxYdayTime'] 	= mbdate                ($wx['solarMaxYdayTime']);
$ws['solarMaxMonth']		=                        $wx['solarMaxMonth']*1.0;
$ws['solarMaxMonthTime'] 	= mb_ymd                ($wx['solarMaxMonthTime']);
$ws['solarMaxYear']		=                        $wx['solarMaxYear']*1.0;
$ws['solarMaxYearTime'] 	= mb_ymd                ($wx['solarMaxYearTime']);
$ws['solarMaxAll']		=                        $wx['solarMaxAll']*1.0;
$ws['solarMaxAllTime'] 	        = mb_ymd                ($wx['solarMaxAllTime']);
#------------------------------------------cloudheight------------------
#$from_height                    = trim(strtolower($wx['fromheight']));
#$ws['cloudHeight']	        = wsConvertDistance     ($wx['cloudHeight'], $from_height);
# ------------------------------------------ forecasts -----------------
$ws['fcstTxt']                  = trim(str_replace ('_',' ',$wx['fcstTxt']) );
# ------------------------------------------  moon ---------------------
$ws['moonrise']			=                        $wx['moonrise'];
$ws['moonset']			=                        $wx['moonset'];
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
if (!isset($wx['wsHardware']) ) {$wx['wsHardware'] = 'unknown';}
$ws['wsHardware'] 		= $wx['wsHardware'];
$ws['wsUptime']			= $wx['wsUptime'];
$ws['latitude']			= $wx['latitude'];
$ws['longitude']		= $wx['longitude'];
#--------------------------------------------soil moisture -------------
if ($SITE['soilUsed'] &&  $SITE['soilCount']	>=  '0') {
        $soils = floor($SITE['soilCount']);  
        if ($soils > 4) {echo $startEcho.' reset nr of soil sensors from '.$SITE['soilCount'].' to max 4 '.$endEcho.PHP_EOL; $soils  = 4;}
        $soilTempAct            = explode ('#',$wx['soilTempAct']);
 
        $soilTempMaxToday       = explode ('#',$wx['soilTempMaxToday']);
        $soilTempMaxYday        = explode ('#',$wx['soilTempMaxYday']);
        $soilTempMaxMonth       = explode ('#',$wx['soilTempMaxMonth']);
        $soilTempMaxYear        = explode ('#',$wx['soilTempMaxYear']);
        $soilTempMaxAll         = explode ('#',$wx['soilTempMaxAll']);
        $soilTempMaxTodayTime   = explode ('#',$wx['soilTempMaxTodayTime']);
        $soilTempMaxYdayTime    = explode ('#',$wx['soilTempMaxYdayTime']);
        $soilTempMaxMonthTime   = explode ('#',$wx['soilTempMaxMonthTime']);
        $soilTempMaxYearTime    = explode ('#',$wx['soilTempMaxYearTime']);
        $soilTempMaxAllTime     = explode ('#',$wx['soilTempMaxAllTime']);
        
        $soilTempMinToday       = explode ('#',$wx['soilTempMinToday']);
        $soilTempMinYday       = explode ('#',$wx['soilTempMinYday']);
        $soilTempMinMonth       = explode ('#',$wx['soilTempMinMonth']);
        $soilTempMinYear        = explode ('#',$wx['soilTempMinYear']);
        $soilTempMinAll         = explode ('#',$wx['soilTempMinAlltime']);
        $soilTempMinTodayTime   = explode ('#',$wx['soilTempMinTodayTime']);
        $soilTempMinYdayTime    = explode ('#',$wx['soilTempMinYdayTime']);
        $soilTempMinMonthTime   = explode ('#',$wx['soilTempMinMonthTime']);
        $soilTempMinYearTime    = explode ('#',$wx['soilTempMinYearTime']);
        $soilTempMinAllTime     = explode ('#',$wx['soilTempMinAllTime']);

        $soilMoistAct           = explode ('#',$wx['soilMoistAct']);

        $soilMoistMaxToday      = explode ('#',$wx['soilMoistMaxToday']);
        $soilMoistMaxYday      = explode ('#',$wx['soilMoistMaxYday']);
        $soilMoistMaxMonth      = explode ('#',$wx['soilMoistMaxMonth']);
        $soilMoistMaxYear       = explode ('#',$wx['soilMoistMaxYear']);
        $soilMoistMaxAll        = explode ('#',$wx['soilMoistMaxAll']);
        $soilMoistMaxTodayTime  = explode ('#',$wx['soilMoistMaxTodayTime']);
        $soilMoistMaxYdayTime   = explode ('#',$wx['soilMoistMaxYdayTime']);
        $soilMoistMaxMonthTime  = explode ('#',$wx['soilMoistMaxMonthTime']); 
        $soilMoistMaxYearTime   = explode ('#',$wx['soilMoistMaxYearTime']);
        $soilMoistMaxAllTime    = explode ('#',$wx['soilMoistMaxAllTime']);

        $soilMoistMinToday      = explode ('#',$wx['soilMoistMinToday']);
        $soilMoistMinYday       = explode ('#',$wx['soilMoistMinYday']);
        $soilMoistMinMonth      = explode ('#',$wx['soilMoistMinMonth']);
        $soilMoistMinYear       = explode ('#',$wx['soilMoistMinYear']);
        $soilMoistMinAll        = explode ('#',$wx['soilMoistMinAll']);
        $soilMoistMinTodayTime  = explode ('#',$wx['soilMoistMinTodayTime']); 
        $soilMoistMinYdayTime   = explode ('#',$wx['soilMoistMinYdayTime']); 
        $soilMoistMinMonthTime  = explode ('#',$wx['soilMoistMinMonthTime']); 
        $soilMoistMinYearTime   = explode ('#',$wx['soilMoistMinYearTime']);
        $soilMoistMinAllTime    = explode ('#',$wx['soilMoistMinAllTime']);

        
        for  ($n = 1; $n <= $soils; $n++) {
                $i                              = $n - 1;	
                $ws['soilTempAct'][$n]          = wsConvertTemperature  ($soilTempAct[$i],$from_temp);

                $ws['soilTempMaxToday'][$n]     = wsConvertTemperature  ($soilTempMaxToday[$i],$from_temp);
                $ws['soilTempMaxTodayTime'][$n] = mb_ymd                ($soilTempMaxTodayTime[$i]);
                $ws['soilTempMaxYday'][$n]      = wsConvertTemperature  ($soilTempMaxYday[$i],$from_temp);
                $ws['soilTempMaxYdayTime'][$n]  = mb_ymd                ($soilTempMaxYdayTime[$i]);
                $ws['soilTempMaxMonth'][$n]     = wsConvertTemperature  ($soilTempMaxMonth[$i],$from_temp);
                $ws['soilTempMaxMonthTime'][$n] = mb_ymd                ($soilTempMaxMonthTime[$i]);
                $ws['soilTempMaxYear'][$n]      = wsConvertTemperature  ($soilTempMaxYear[$i],$from_temp);
                $ws['soilTempMaxYearTime'][$n]  = mb_ymd                ($soilTempMaxYearTime[$i]); 
                $ws['soilTempMaxAll'][$n]       = wsConvertTemperature  ($soilTempMaxAll[$i],$from_temp);
                $ws['soilTempMaxAllTime'][$n]   = mb_ymd                ($soilTempMaxAllTime[$i]);
 
                
                $ws['soilTempMinToday'][$n]     = wsConvertTemperature  ($soilTempMinToday[$i],$from_temp);
                $ws['soilTempMinTodayTime'][$n] = mb_ymd                ($soilTempMinTodayTime[$i]);
                $ws['soilTempMinYday'][$n]      = wsConvertTemperature  ($soilTempMinYday[$i],$from_temp);
                $ws['soilTempMinYdayTime'][$n]  = mb_ymd                ($soilTempMinYdayTime[$i]);
                $ws['soilTempMinMonth'][$n]     = wsConvertTemperature  ($soilTempMinMonth[$i],$from_temp);
                $ws['soilTempMinMonthTime'][$n] = mb_ymd                ($soilTempMinMonthTime[$i]);
                $ws['soilTempMinYear'][$n]      = wsConvertTemperature  ($soilTempMinYear[$i],$from_temp);
                $ws['soilTempMinYearTime'][$n]  = mb_ymd                ($soilTempMinYearTime[$i]);
                $ws['soilTempMinAll'][$n]       = wsConvertTemperature  ($soilTempMinAll[$i],$from_temp);
                $ws['soilTempMinAllTime'][$n]   = mb_ymd                ($soilTempMinAllTime[$i]);

                $ws['moistAct'][$n]	        =                $soilMoistAct[$i]*1.0;
 
                $ws['moistMaxToday'][$n]	=                $soilMoistMaxToday[$i]*1.0;
                $ws['moistMaxTodayTime'][$n]	= mb_ymd        ($soilMoistMaxTodayTime[$i]);
                $ws['moistMaxYday'][$n]	        =                $soilMoistMaxYday[$i]*1.0;
                $ws['moistMaxYdayTime'][$n]	= mb_ymd        ($soilMoistMaxYdayTime[$i]);
                $ws['moistMaxMonth'][$n]	=                $soilMoistMaxMonth[$i]*1.0;
                $ws['moistMaxMonthTime'][$n]	= mb_ymd        ($soilMoistMaxMonthTime[$i]); 
                $ws['moistMaxYear'][$n]	        =                $soilMoistMaxYear[$i]*1.0;
                $ws['moistMaxYearTime'][$n]	= mb_ymd        ($soilMoistMaxYearTime[$i]);
                $ws['moistMaxAll'][$n]	        =                $soilMoistMaxAll[$i]*1.0;
                $ws['moistMaxAllTime'][$n]	= mb_ymd        ($soilMoistMaxAllTime[$i]);
                
                $ws['moistMinToday'][$n]	=                $soilMoistMinToday[$i]*1.0;
                $ws['moistMinTodayTime'][$n]	= mb_ymd        ($soilMoistMinTodayTime[$i]);
                $ws['moistMinYday'][$n]	        =                $soilMoistMinYday[$i]*1.0;
                $ws['moistMinYdayTime'][$n]	= mb_ymd        ($soilMoistMinYdayTime[$i]);
                $ws['moistMinMonth'][$n]	=                $soilMoistMinMonth[$i]*1.0;
                $ws['moistMinMonthTime'][$n]	= mb_ymd        ($soilMoistMinMonthTime[$i]); 
                $ws['moistMinYear'][$n]	        =                $soilMoistMinYear[$i]*1.0;
                $ws['moistMinYearTime'][$n]	= mb_ymd        ($soilMoistMinYearTime[$i]);
                $ws['moistMinAll'][$n]	        =                $soilMoistMinAll[$i]*1.0;
                $ws['moistMinAllTime'][$n]	= mb_ymd        ($soilMoistMinAllTime[$i]);
        }
}
if ($SITE['leafUsed'] &&  $SITE['leafCount'] >=  '0') {
        $leafs                  = floor($SITE['leafCount']);
        if ($leafs > 4) {       echo $startEcho.' reset nr of leaf sensors from '.$SITE['leafCount'].' to max 4 '.$endEcho.PHP_EOL;$leafs  = 4;}
        
        $leafTempAct            = explode ('#',$wx['leafTempAct']);
        $leafWetAct             = explode ('#',$wx['leafWetAct']);

        $leafWetMaxToday        = explode ('#',$wx['leafWetMaxToday']); 
        $leafWetMaxYday         = explode ('#',$wx['leafWetMaxYday']);
        $leafWetMaxMonth        = explode ('#',$wx['leafWetMaxMonth']);
        $leafWetMaxYear         = explode ('#',$wx['leafWetMaxYear']);
        $leafWetMaxAll          = explode ('#',$wx['leafWetMaxAll']);

        $leafWetMinToday        = explode ('#',$wx['leafWetMinToday']); 
        $leafWetMinYday         = explode ('#',$wx['leafWetMinYday']);
        $leafWetMinMonth        = explode ('#',$wx['leafWetMinMonth']);
        $leafWetMinYear         = explode ('#',$wx['leafWetMinYear']);
        $leafWetMinAll          = explode ('#',$wx['leafWetMinAll']);
        
        $leafWetMaxTodayTime    = explode ('#',$wx['leafWetMaxTodayTime']); 
        $leafWetMaxYdayTime     = explode ('#',$wx['leafWetMaxYdayTime']);
        $leafWetMaxMonthTime    = explode ('#',$wx['leafWetMaxMonthTime']);
        $leafWetMaxYearTime     = explode ('#',$wx['leafWetMaxYearTime']);
        $leafWetMaxAllTime      = explode ('#',$wx['leafWetMaxAllTime']);

        $leafWetMinTodayTime    = explode ('#',$wx['leafWetMinTodayTime']); 
        $leafWetMinYdayTime     = explode ('#',$wx['leafWetMinYdayTime']);
        $leafWetMinMonthTime    = explode ('#',$wx['leafWetMinMonthTime']);
        $leafWetMinYearTime     = explode ('#',$wx['leafWetMinYearTime']);
        $leafWetMinAllTime      = explode ('#',$wx['leafWetMinAllTime']);
        for  ($n = 1; $n <= $leafs; $n++) {
                $i                      = $n - 1;
                $ws['leafTempAct'][$n]	        = $leafTempAct[$i]*1.0;
                $ws['leafWetAct'][$n]	        = $leafWetAct[$i]*1.0;

                $ws['leafWetMaxToday'][$n]	= $leafWetMaxToday[$i]*1.0;
                $ws['leafWetMaxYday'][$n]	= $leafWetMaxYday[$i]*1.0;
                $ws['leafWetMaxMonth'][$n]	= $leafWetMaxMonth[$i]*1.0;
                $ws['leafWetMaxYear'][$n]	= $leafWetMaxYear[$i]*1.0;
                $ws['leafWetMaxAll'][$n]	= $leafWetMaxAll[$i]*1.0;

                $ws['leafWetMinToday'][$n]	= $leafWetMinToday[$i]*1.0;
                $ws['leafWetMinYday'][$n]	= $leafWetMinYday[$i]*1.0;
                $ws['leafWetMinMonth'][$n]	= $leafWetMinMonth[$i]*1.0;
                $ws['leafWetMinYear'][$n]	= $leafWetMinYear[$i]*1.0;
                $ws['leafWetMinAll'][$n]	= $leafWetMinAll[$i]*1.0;
 
                $ws['leafWetMaxTodayTime'][$n]	= mb_ymd        ($leafWetMaxTodayTime[$i]);
                $ws['leafWetMaxYdayTime'][$n]	= mb_ymd        ($leafWetMaxYdayTime[$i]);
                $ws['leafWetMaxMonthTime'][$n]	= mb_ymd        ($leafWetMaxMonthTime[$i]);
                $ws['leafWetMaxYearTime'][$n]	= mb_ymd        ($leafWetMaxYearTime[$i]);
                $ws['leafWetMaxAllTime'][$n]	= mb_ymd        ($leafWetMaxAllTime[$i]);

                $ws['leafWetMinTodayTime'][$n]	= mb_ymd        ($leafWetMinTodayTime[$i]);
                $ws['leafWetMinYdayTime'][$n]	= mb_ymd        ($leafWetMinYdayTime[$i]);
                $ws['leafWetMinMonthTime'][$n]	= mb_ymd        ($leafWetMinMonthTime[$i]);
                $ws['leafWetMinYearTime'][$n]	= mb_ymd        ($leafWetMinYearTime[$i]);
                $ws['leafWetMinAllTime'][$n]	= mb_ymd        ($leafWetMinAllTime[$i]);
        }
} // eo doleaf

$ws['wsDashboardDec']	= $wx['wsDashboardDec'];
$ws['wsDashboardImp']	= $wx['wsDashboardImp'];
$ws['wsPhoneDec']	= $wx['wsPhoneDec'];
$ws['wsPhoneImp']	= $wx['wsPhoneImp'];
$ws['wsPhoneGr1Dec']	= $wx['wsPhoneGr1Dec'];
$ws['wsPhoneGr1Imp']	= $wx['wsPhoneGr1Imp'];
$ws['wsPhoneGr2Dec']	= $wx['wsPhoneGr2Dec'];
$ws['wsPhoneGr2Imp']	= $wx['wsPhoneGr2Imp'];

$ws['check_ok']         = '3.00';
#
function mbdate   ($time){  // for todays time stamps: remove ':' in time and combine to YYYYMMDDHHMMSS
        return $time;
}
function mb_ymd ($string) {
        return $string;
        }
function mb_untranslated ($field) {
        $pos =  strpos ('  '.$field,'[');
        if ($pos > 0) {return true; } else {return false; }
}
// end of tagsVWS.php
#echo '<pre>'; print_r($ws); exit;
?>