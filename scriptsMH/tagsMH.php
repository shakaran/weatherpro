<?php 	#ini_set('display_errors', 'On'); error_reporting(E_ALL);	
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
$pageName	= 'tagsMH.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.11 2015-07-21';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.11 2015-07-21 releasse 2.7 version + use empty fields +  arrow correction  
# ----------------------------------------------------------------------
$tagsScript     = $pageName;
$startEcho      = '<!-- ';      
$endEcho        = ' -->';
#
$test           = false;
if ($test) {echo '<pre>Start test'.PHP_EOL;$startEcho      = ''; $endEcho        = '';}
#
$fileToLoad     = array ($SITE['wsTags'], $SITE['ydayTags'] );              // normaly tagsMH.txt

$uoms		= $uomTemp.$uomBaro.$uomWind.$uomRain.$uomSnow.$uomDistance.$uomPerHour.$uomHeight;
$from		= array('/',' ','&deg;','.php');
$to		= '';
#
for ($i = 0; $i < count($fileToLoad); $i++) {
        if ($SITE['wsDebug']) {echo  $startEcho.$tagsScript.' loading '.$fileToLoad[$i].$endEcho.PHP_EOL; }
        $arr    = file($fileToLoad[$i],FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $end    = count ($arr);
        if ($SITE['wsDebug']) {echo  $startEcho.$tagsScript.' contains '.$end.' lines'.$endEcho.PHP_EOL; }
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
#                        echo $startEcho.$tagsScript.'('.__LINE__.'):empty line '.$n.' name '.$name .$endEcho.PHP_EOL; continue;
                }
                $wx[$name]=$content;
        }
}
#print_r ($wx);  echo '------------------halt1'; exit;
$ws['tags_processed']	= $pageName.'-'.$pageVersion;
$ws['tags_originated']	= $SITE['wsTags'].'-'.  $wx['pagename'].'-'.$wx['pagenameYday'];
$ws['tags_today']	= $SITE['wsTags'].'-'.  $wx['pagename'];
$ws['tags_yday']	= $SITE['ydayTags'].'-'. $wx['pagenameYday'];
$ws['tags_today_time']	= $wx['datetime'];
$ws['tags_yday_time']	= $wx['datetimeYday'];
# ----------------------------------------------------------------------
$ws['actTime']		= $wx['datetime'];
$ymd                    = substr($wx['datetime'],0,8);
# ------------------------------------------ temperature ---------------
$from_temp              = $wx['fromtemp'];
$ws['tempAct']		= wsConvertTemperature  ($wx['tempAct'],$from_temp);
$temp1hourAgo		= wsConvertTemperature  ($wx['tempAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['tempDelta'],$from_temp,$from_temp);
$ws['tempDelta']	= $ws['tempAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['tempMinToday']	= wsConvertTemperature  ($wx['tempMinToday'],$from_temp);
$ws['tempMinTodayTime']	= mhdate                ($wx['tempMinTodayTime']);
$ws['tempMinYday']	= wsConvertTemperature  ($wx['tempMinYday'],$from_temp);
$ws['tempMinYdayTime']	= mhdate                ($wx['tempMinYdayTime']);
$ws['tempMinMonth']	= wsConvertTemperature  ($wx['tempMinMonth'],$from_temp);
$ws['tempMinMonthTime']	= mh_ymd                ($wx['tempMinMonthTime']);
$ws['tempMinYear']	= wsConvertTemperature  ($wx['tempMinYear'],$from_temp);
$ws['tempMinYearTime']	= mh_ymd                ($wx['tempMinYearTime']);
$ws['tempMinAll']	= wsConvertTemperature  ($wx['tempMinAll'],$from_temp);
$ws['tempMinAllTime']	= mh_ymd                ($wx['tempMinAllTime']);

$ws['tempMaxToday']	= wsConvertTemperature  ($wx['tempMaxToday'],$from_temp);
$ws['tempMaxTodayTime']	= mhdate                ($wx['tempMaxTodayTime']);
$ws['tempMaxYday']	= wsConvertTemperature  ($wx['tempMaxYday'],$from_temp);
$ws['tempMaxYdayTime']	= mhdate                ($wx['tempMaxYdayTime']);
$ws['tempMaxMonth']	= wsConvertTemperature  ($wx['tempMaxMonth'],$from_temp);
$ws['tempMaxMonthTime']	= mh_ymd                ($wx['tempMaxMonthTime']);
$ws['tempMaxYear']	= wsConvertTemperature  ($wx['tempMaxYear'],$from_temp);
$ws['tempMaxYearTime']	= mh_ymd                ($wx['tempMaxYearTime']);
$ws['tempMaxAll']	= wsConvertTemperature  ($wx['tempMaxAll'],$from_temp);
$ws['tempMaxAllTime']	= mh_ymd                ($wx['tempMaxAllTime']);

$ws['dewpAct']  	= wsConvertTemperature  ($wx['dewpAct'],$from_temp);
$temp1hourAgo		= wsConvertTemperature  ($wx['dewpAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['dewpDelta'],$from_temp,$from_temp);
$ws['dewpDelta']	= $ws['dewpAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['dewpMinToday']  	= wsConvertTemperature  ($wx['dewpMinToday'],$from_temp);
$ws['dewpMinTodayTime']	= mhdate                ($wx['dewpMinTodayTime']);
$ws['dewpMinYday']  	= wsConvertTemperature  ($wx['dewpMinYday'],$from_temp);
$ws['dewpMinYdayTime']	= mhdate                ($wx['dewpMinYdayTime']);
$ws['dewpMinMonth']  	= wsConvertTemperature  ($wx['dewpMinMonth'],$from_temp);
$ws['dewpMinMonthTime']	= mhdate                ($wx['dewpMinMonthTime']);
$ws['dewpMinYear']  	= wsConvertTemperature  ($wx['dewpMinYear'],$from_temp);
$ws['dewpMinYearTime']	= mhdate                ($wx['dewpMinYearTime']);
$ws['dewpMinAll']  	= wsConvertTemperature  ($wx['dewpMinAll'],$from_temp);
$ws['dewpMinAllTime']	= mhdate                ($wx['dewpMinAllTime']);

$ws['dewpMaxToday']  	= wsConvertTemperature  ($wx['dewpMaxToday'],$from_temp);
$ws['dewpMaxTodayTime']	= mhdate                ($wx['dewpMaxTodayTime']);
$ws['dewpMaxYday']  	= wsConvertTemperature  ($wx['dewpMaxYday'],$from_temp);
$ws['dewpMaxYdayTime']	= mhdate                ($wx['dewpMaxYdayTime']);
$ws['dewpMaxMonth']  	= wsConvertTemperature  ($wx['dewpMaxMonth'],$from_temp);
$ws['dewpMaxMonthTime']	= mhdate                ($wx['dewpMaxMonthTime']);
$ws['dewpMaxYear']  	= wsConvertTemperature  ($wx['dewpMaxYear'],$from_temp);
$ws['dewpMaxYearTime']	= mhdate                ($wx['dewpMaxYearTime']);
$ws['dewpMaxAll']  	= wsConvertTemperature  ($wx['dewpMaxAll'],$from_temp);
$ws['dewpMaxAllTime']	= mhdate                ($wx['dewpMaxAllTime']);

$ws['appTemp']  	        = wsConvertTemperature  ($wx['appTemp'],$from_temp);

$ws['appTempMinToday']  	= wsConvertTemperature  ($wx['appTempMinToday'],$from_temp);
$ws['appTempMinTodayTime']	= mhdate                ($wx['appTempMinTodayTime']);
$ws['appTempMinYday']  	        = wsConvertTemperature  ($wx['appTempMinYday'],$from_temp);
$ws['appTempMinYdayTime']	= mhdate                ($wx['appTempMinYdayTime']);

$ws['appTempMaxToday']  	= wsConvertTemperature  ($wx['appTempMaxToday'],$from_temp);
$ws['appTempMaxTodayTime']	= mhdate                ($wx['appTempMaxTodayTime']);
$ws['appTempMaxYday']  	        = wsConvertTemperature  ($wx['appTempMaxYday'],$from_temp);
$ws['appTempMaxYdayTime']	= mhdate                ($wx['appTempMaxYdayTime']);

$ws['heatAct']  	= wsConvertTemperature  ($wx['heatAct'],$from_temp);
$temp1hourAgo		= wsConvertTemperature  ($wx['heatAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['heatDelta'],$from_temp,$from_temp);
$ws['heatDelta']	= $ws['heatAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['heatMaxToday']  	= wsConvertTemperature  ($wx['heatMaxToday'],$from_temp);
$ws['heatMaxTodayTime']	= mhdate                ($wx['heatMaxTodayTime']);
$ws['heatMaxYday']  	= wsConvertTemperature  ($wx['heatMaxYday'],$from_temp);
$ws['heatMaxYdayTime']	= mhdate                ($wx['heatMaxYdayTime']);
$ws['heatMaxMonth']	= wsConvertTemperature  ($wx['heatMaxMonth'],$from_temp);
$ws['heatMaxMonthTime'] = mh_ymd                ($wx['heatMaxMonthTime']);
$ws['heatMaxYear']	= wsConvertTemperature  ($wx['heatMaxYear'],$from_temp);
$ws['heatMaxYearTime']	= mh_ymd                ($wx['heatMaxYearTime']);
$ws['heatMaxAll']	= wsConvertTemperature  ($wx['heatMaxAll'],$from_temp);
$ws['heatMaxAllTime']	= mh_ymd                ($wx['heatMaxAllTime']);

$ws['chilAct']  	= wsConvertTemperature  ($wx['chilAct'],$from_temp);
$temp1hourAgo		= wsConvertTemperature  ($wx['chilAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['chilDelta'],$from_temp,$from_temp);
$ws['chilDelta']	= $ws['chilAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['chilMinToday']  	= wsConvertTemperature  ($wx['chilMinToday'],$from_temp);
$ws['chilMinTodayTime']	= mhdate                ($wx['chilMinTodayTime']);
$ws['chilMinYday']  	= wsConvertTemperature  ($wx['chilMinYday'],$from_temp);
$ws['chilMinYdayTime']	= mhdate                ($wx['chilMinYdayTime']);
$ws['chilMinMonth']  	= wsConvertTemperature  ($wx['chilMinMonth'],$from_temp);
$ws['chilMinMonthTime']	= mh_ymd                ($wx['chilMinMonthTime']);
$ws['chilMinYear']  	= wsConvertTemperature  ($wx['chilMinYear'],$from_temp);
$ws['chilMinYearTime']	= mh_ymd                ($wx['chilMinYearTime']);
$ws['chilMinAll']  	= wsConvertTemperature  ($wx['chilMinAll'],$from_temp);
$ws['chilMinAllTime']	= mh_ymd                ($wx['chilMinAllTime']);

$ws['chilMaxToday']  	= wsConvertTemperature  ($wx['chilMinToday'],$from_temp);
$ws['chilMaxTodayTime'] = mhdate                ($wx['chilMaxTodayTime']);

$ws['tempActInside']	= wsConvertTemperature  ($wx['tempActInside'],$from_temp);
$ws['tempActExtra1']	= wsConvertTemperature  ($wx['tempActExtra1'],$from_temp);	
$ws['tempActExtra2']	= wsConvertTemperature  ($wx['tempActExtra2'],$from_temp);	
$ws['tempToday']	= $ws['tempAct'];

if (isset ($wx['fromhudx']) ) {$from = $wx['fromhudx'];} else {$from = $from_temp;}
$ws['hudxAct'] 	        = wsConvertTemperature  ($wx['hudxAct'],$from);
$ws['hudxDelta']	= wsConvertTemperature  ($wx['hudxDelta'],$from);
$ws['hudxMaxToday']  	= wsConvertTemperature  ($wx['hudxMaxToday'],$from);
$ws['hudxMaxTodayTime']	= mhdate                ($wx['hudxMaxTodayTime']);
$ws['hudxMaxYday']  	= wsConvertTemperature  ($wx['hudxMaxYday'],$from);
$ws['hudxMaxYdayTime']	= mhdate                ($wx['hudxMaxYdayTime']);
$ws['hudxMaxMonth']	= wsConvertTemperature  ($wx['hudxMaxMonth'],$from);
$ws['hudxMaxMonthTime'] = mh_ymd                ($wx['hudxMaxMonthTime']);
$ws['hudxMaxYear']	= wsConvertTemperature  ($wx['hudxMaxYear'],$from);
$ws['hudxMaxYearTime']	= mh_ymd                ($wx['hudxMaxYearTime']);
$ws['hudxMaxAll']	= wsConvertTemperature  ($wx['hudxMaxAll'],$from);
$ws['hudxMaxAllTime']	= mh_ymd                ($wx['hudxMaxAllTime']);

# ------------------------------------------ pressure / baro -----------
$from_baro              = trim(strtolower($wx['frombaro']));     // ' hPa', of ' mb', of ' inHg'

$ws['baroAct'] 	        = wsConvertBaro ($wx['baroAct'],$from_baro);
$ws['baroDelta']	= wsConvertBaro ($wx['baroDelta'],$from_baro);
$ws['baroTrend']	= langtransstr  (wsBarotrendText($ws['baroDelta']));

$ws['baroMinToday']	= wsConvertBaro ($wx['baroMinToday'],$from_baro);
$ws['baroMinTodayTime']	= mhdate        ($wx['baroMinTodayTime']);
$ws['baroMinYday']	= wsConvertBaro ($wx['baroMinYday'],$from_baro);
$ws['baroMinYdayTime']	= mhdate        ($wx['baroMinYdayTime']);
$ws['baroMinMonth']	= wsConvertBaro ($wx['baroMinMonth'],$from_baro);
$ws['baroMinMonthTime']	= mh_ymd        ($wx['baroMinMonthTime']);
$ws['baroMinYear'] 	= wsConvertBaro ($wx['baroMinYear'],$from_baro);	
$ws['baroMinYearTime']	= mh_ymd        ($wx['baroMinYearTime']);
$ws['baroMinAll'] 	= wsConvertBaro ($wx['baroMinAll'],$from_baro);	
$ws['baroMinAllTime']	= mh_ymd        ($wx['baroMinAllTime']);

$ws['baroMaxToday']	= wsConvertBaro ($wx['baroMaxToday'],$from_baro);
$ws['baroMaxTodayTime'] = mhdate        ($wx['baroMaxTodayTime']);
$ws['baroMaxYday']	= wsConvertBaro ($wx['baroMaxYday'],$from_baro);
$ws['baroMaxYdayTime']	= mh_ymd        ($wx['baroMaxYdayTime']);
$ws['baroMaxMonth']	= wsConvertBaro ($wx['baroMaxMonth'],$from_baro);
$ws['baroMaxMonthTime']= mh_ymd        ($wx['baroMaxMonthTime']);
$ws['baroMaxYear']	= wsConvertBaro ($wx['baroMaxYear'],$from_baro);
$ws['baroMaxYearTime']	= mh_ymd        ($wx['baroMaxYearTime']);
$ws['baroMaxAll']	= wsConvertBaro ($wx['baroMaxAll'],$from_baro);
$ws['baroMaxAllTime']	= mh_ymd        ($wx['baroMaxAllTime']);
# ------------------------------------------ humidity  -----------------
$ws['humiAct']			= $wx['humiAct']*1.0;
$ws['humiDelta']		= $wx['humiDelta']*1.0;

$ws['humiMinToday'] 		=                $wx['humiMinToday']*1.0;
$ws['humiMinTodayTime']	        = mhdate        ($wx['humiMinTodayTime']);
$ws['humiMinYday'] 		=                $wx['humiMinYday']*1.0;
$ws['humiMinYdayTime']	        = mhdate        ($wx['humiMinYdayTime']);
$ws['humiMinMonth']	        =                $wx['humiMinMonth'];
$ws['humiMinMonthTime']	        = mh_ymd        ($wx['humiMinMonthTime']);
$ws['humiMinYear'] 	        =                $wx['humiMinYear'];	
$ws['humiMinYearTime']	        = mh_ymd        ($wx['humiMinYearTime']);
$ws['humiMinAll'] 	        =                $wx['humiMinAll'];	
$ws['humiMinAllTime']	        = mh_ymd        ($wx['humiMinAllTime']);

$ws['humiMaxToday']		=                $wx['humiMaxToday']*1.0;
$ws['humiMaxTodayTime']	        = mhdate        ($wx['humiMaxTodayTime']);
$ws['humiMaxYday']		=                $wx['humiMaxYday']*1.0;
$ws['humiMaxYdayTime']	        = mhdate        ($wx['humiMaxYdayTime']);
$ws['humiMaxMonth']	        =                $wx['humiMaxMonth'];
$ws['humiMaxMonthTime']	        = mh_ymd        ($wx['humiMaxMonthTime']);
$ws['humiMaxYear'] 	        =                $wx['humiMaxYear'];	
$ws['humiMaxYearTime']	        = mh_ymd        ($wx['humiMaxYearTime']);
$ws['humiMaxAll'] 	        =                $wx['humiMaxAll'];	
$ws['humiMaxAllTime']	        = mh_ymd        ($wx['humiMaxAllTime']);

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
#$ws['rainWeek']                 = wsConvertRainfall     ($wx['rainWeek'],$from_rain);
$ws['rainMonth']	        = wsConvertRainfall     ($wx['rainMonth'],$from_rain);
$ws['rainYear']		        = wsConvertRainfall     ($wx['rainYear'],$from_rain);
$ws['rainAll']		        = wsConvertRainfall     ($wx['rainAll'],$from_rain);

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
$ws['etAll'] 		        = wsConvertRainfall     ($wx['etAll'],$from_rain);
# ------------------------------------------ wind  ---------------------
$from_wind      = trim(strtolower($wx['fromwind']));       //  "m/s", "mph", "km/h", "kts" ?? 'kmh' 'Bft'??  windrun  "km", "miles",

$ws['windAct']		        = wsConvertWindspeed    ($wx['windAct'], $from_wind);
$ws['gustAct']		        = wsConvertWindspeed    ($wx['gustAct'], $from_wind);

$ws['windActDsc']		=                        $wx['windActDsc'];  //NNE
$ws['windActDir']		=                        $wx['windActDir']*1.0;  // 20 deg
#$ws['windAvgDir']               =                        $wx['windAvgDir']*1.0;

$ws['windBeafort']		= wsBeaufortNumber      ($wx['windAct'], $from_wind);

$ws['gustMaxToday']	        = wsConvertWindspeed    ($wx['gustMaxToday'], $from_wind);
$ws['gustMaxTodayTime']         = mhdate                ($wx['gustMaxTodayTime']);
$ws['gustMaxYday']	        = wsConvertWindspeed    ($wx['gustMaxYday'], $from_wind);
$ws['gustMaxYdayTime']          = mhdate                ($wx['gustMaxYTime']);
$ws['gustMaxMonth']	        = wsConvertWindspeed    ($wx['gustMaxMonth'], $from_wind);
$ws['gustMaxMonthTime']	        = mh_ymd                ($wx['gustMaxMonthTime']);
$ws['gustMaxYear']	        = wsConvertWindspeed    ($wx['gustMaxYear'], $from_wind);	
$ws['gustMaxYearTime']	        = mh_ymd                ($wx['gustMaxYearTime']);
$ws['gustMaxAll']	        = wsConvertWindspeed    ($wx['gustMaxAll'], $from_wind);	
$ws['gustMaxAllTime']	        = mh_ymd                ($wx['gustMaxAllTime']);

if ($ws['gustAct'] < $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}

#$from_distance                  = trim(strtolower($wx['fromdistance']));
#$ws['windrunToday']             = wsConvertDistance     ($wx['windrunToday'],$from_distance);
# ------------------------------------------  UV   ---------------------
$ws['uvAct']			=                        $wx['uvAct']*1.0;
$ws['uvMaxToday']		=                        $wx['uvMaxToday']*1.0;
$ws['uvMaxTodayTime'] 		= mhdate                ($wx['uvMaxTodayTime']);
$ws['uvMaxYday']		=                        $wx['uvMaxYday']*1.0;
$ws['uvMaxYdayTime'] 		= mhdate                ($wx['uvMaxYdayTime']);
$ws['uvMaxMonth']		=                        $wx['uvMaxMonth']*1.0;
$ws['uvMaxMonthTime'] 		= mh_ymd                ($wx['uvMaxMonthTime']);
$ws['uvMaxYear']		=                        $wx['uvMaxYear']*1.0;
$ws['uvMaxYearTime'] 		= mh_ymd                ($wx['uvMaxYearTime']);
$ws['uvMaxAll']		        =                        $wx['uvMaxAll']*1.0;
$ws['uvMaxAllTime'] 		= mh_ymd                ($wx['uvMaxAllTime']);
# ------------------------------------------ Solar  --------------------
$ws['solarAct']			=                        $wx['solarAct']*1.0;
$ws['solarActPerc']		=                        $wx['solarActPerc']*1.0;
$ws['solarMaxToday']		=                        $wx['solarMaxToday']*1.0;
$ws['solarMaxTodayTime'] 	= mhdate                ($wx['solarMaxTodayTime']);
$ws['solarMaxYday']		=                        $wx['solarMaxYday']*1.0;
$ws['solarMaxYdayTime'] 	= mhdate                ($wx['solarMaxYdayTime']);
$ws['solarMaxMonth']		=                        $wx['solarMaxMonth']*1.0;
$ws['solarMaxMonthTime'] 	= mh_ymd                ($wx['solarMaxMonthTime']);
$ws['solarMaxYear']		=                        $wx['solarMaxYear']*1.0;
$ws['solarMaxYearTime'] 	= mh_ymd                ($wx['solarMaxYearTime']);
$ws['solarMaxAll']		=                        $wx['solarMaxAll']*1.0;
$ws['solarMaxAllTime'] 	        = mh_ymd                ($wx['solarMaxAllTime']);
#------------------------------------------cloudheight------------------
$from_height                    = trim(strtolower($wx['fromheight']));
$ws['cloudHeight']	        = wsConvertDistance     ($wx['cloudHeight'], $from_height);
# ------------------------------------------ forecasts -----------------
$ws['fcstTxt']                  = trim(str_replace ('_',' ',$wx['fcstTxt']) );
# ------------------------------------------  moon ---------------------
$ws['moonrise']			=                        $wx['moonrise'];
$ws['moonset']			=                        $wx['moonset'];
$ws['lunarPhasePerc']           =                        $wx['lunarPhasePerc']*1.0;
$ws['lunarAge']			=                        $wx['lunarAge']*1.0;
# ------------------------------------------  trends   -----------------
$ws['tempFrom']                 = '&deg;C';     // always these value, 
$ws['baroFrom']                 = ' hPa';       // no others available
$ws['windFrom']                 = ' kts';       // for seq  fields from mh
$ws['rainFrom']                 = ' mm';        // 
#
$ws['seqmin1_temp']             = $wx['seqmin1_temp'];
$ws['seqmin1_windspeed']        = $wx['seqmin1_windspeed'];
$ws['seqmin1_gustspeed']        = $wx['seqmin1_gustspeed'];
$ws['seqmin1_windmaindir']      = $wx['seqmin1_windmaindir'];
$ws['seqmin1_hum']              = $wx['seqmin1_hum'];
$ws['seqmin1_press']            = $wx['seqmin1_press'];
$ws['seqmin1_raintotal']        = $wx['seqmin1_raintotal'];
$ws['seqmin1_UV']               = $wx['seqmin1_UV'];
$ws['seqmin1_solar']            = $wx['seqmin1_solar'];

$ws['seqmin15_temp']            = $wx['seqmin15_temp'];
$ws['seqmin15_windspeed']       = $wx['seqmin15_windspeed'];
$ws['seqmin15_gustspeed']       = $wx['seqmin15_gustspeed'];
$ws['seqmin15_windmaindir']     = $wx['seqmin15_windmaindir'];
$ws['seqmin15_hum']             = $wx['seqmin15_hum'];
$ws['seqmin15_press']           = $wx['seqmin15_press'];
$ws['seqmin15_raintotal']       = $wx['seqmin15_raintotal'];
$ws['seqmin15_UV']              = $wx['seqmin15_UV'];
$ws['seqmin15_solar']           = $wx['seqmin15_solar'];

$ws['seqday1_rain_total']       = $wx['seqday1_rain_total'];
$ws['seqmonth1_rain_total']     = $wx['seqmonth1_rain_total'];
$ws['seqday1_tempMin_total']    = $wx['seqday1_tempMin_total'];
$ws['seqday1_tempMax_total']    = $wx['seqday1_tempMax_total'];
# ------------------------------------------ some more -----------------
$ws['wsVersion']		= $wx['wsVersion'].'-'.$wx['wsBuild'];
$ws['wsHardware'] 		= $wx['wsHardware'];
if (isset ($wx['wsUptime']) && trim($wx['wsUptime']) <> '') {
	$ws['wsUptime']			= $wx['wsUptime'];
	$ws['wsRootUse']                = $wx['wsRootUse'];
	$ws['wsDataUse']                = $wx['wsDataUse'];
}
#--------------------------------------------soil moisture -------------
if ($SITE['soilUsed'] &&  $SITE['soilCount']	>=  '0') {$soils = floor($SITE['soilCount']);  $doSoil = true; } else {$doSoil = false;}
if ($doSoil) {
        if ($soils > 4) {echo $startEcho.' reset nr of soil sensors from '.$SITE['soilCount'].' to max 4 '.$endEcho.PHP_EOL; $soils  = 4;}
        $soilTempAct            = explode ('#',$wx['soilTempAct']);
        $soilTempMaxToday       = explode ('#',$wx['soilTempMaxToday']);
        $soilTempMaxYday        = explode ('#',$wx['soilTempMaxYday']);
        $soilTempMaxMonth       = explode ('#',$wx['soilTempMaxMonth']);
        $soilTempMaxYear        = explode ('#',$wx['soilTempMaxYear']);
        $soilTempMaxAlltime     = explode ('#',$wx['soilTempMaxAll']);
        $soilTempMaxTodayTime   = explode ('#',$wx['soilTempMaxTodayTime']);
        $soilTempMaxYdayTime    = explode ('#',$wx['soilTempMaxYdayTime']);        
        $soilTempMaxMonthTime   = explode ('#',$wx['soilTempMaxMonthTime']);
        $soilTempMaxYearTime    = explode ('#',$wx['soilTempMaxYearTime']);
        $soilTempMaxAlltimeTime = explode ('#',$wx['soilTempMaxAllTime']);
        
        $soilTempMinToday       = explode ('#',$wx['soilTempMinToday']);
        $soilTempMinYday        = explode ('#',$wx['soilTempMinYday']);
        $soilTempMinMonth       = explode ('#',$wx['soilTempMinMonth']);
        $soilTempMinYear        = explode ('#',$wx['soilTempMinYear']);
        $soilTempMinAlltime     = explode ('#',$wx['soilTempMinAll']);
        $soilTempMinTodayTime   = explode ('#',$wx['soilTempMinTodayTime']);
        $soilTempMinYdayTime   = explode ('#',$wx['soilTempMinYdayTime']);
        $soilTempMinMonthTime   = explode ('#',$wx['soilTempMinMonthTime']);
        $soilTempMinYearTime    = explode ('#',$wx['soilTempMinYearTime']);
        $soilTempMinAlltimeTime = explode ('#',$wx['soilTempMinAllTime']);

        $soilMoistAct           = explode ('#',$wx['soilMoistAct']);

        $soilMoistMaxToday      = explode ('#',$wx['soilMoistMaxToday']);
        $soilMoistMaxTodayTime  = explode ('#',$wx['soilMoistMaxTodayTime']);  
        $soilMoistMaxYday       = explode ('#',$wx['soilMoistMaxYday']);
        $soilMoistMaxYdayTime   = explode ('#',$wx['soilMoistMaxYdayTime']);  
        $soilMoistMaxMonth      = explode ('#',$wx['soilMoistMaxMonth']);
        $soilMoistMaxMonthTime  = explode ('#',$wx['soilMoistMaxMonthTime']); 
        $soilMoistMaxYear       = explode ('#',$wx['soilMoistMaxYear']);
        $soilMoistMaxYearTime   = explode ('#',$wx['soilMoistMaxYearTime']);
        $soilMoistMaxAll        = explode ('#',$wx['soilMoistMaxAll']);
        $soilMoistMaxAllTime    = explode ('#',$wx['soilMoistMaxAllTime']);

        $soilMoistMinToday      = explode ('#',$wx['soilMoistMinToday']);
        $soilMoistMinTodayTime  = explode ('#',$wx['soilMoistMinTodayTime']); 
        $soilMoistMinYday       = explode ('#',$wx['soilMoistMinYday']);
        $soilMoistMinYdayTime   = explode ('#',$wx['soilMoistMinYdayTime']); 
        $soilMoistMinMonth      = explode ('#',$wx['soilMoistMinMonth']);
        $soilMoistMinMonthTime  = explode ('#',$wx['soilMoistMinMonthTime']); 
        $soilMoistMinYear       = explode ('#',$wx['soilMoistMinYear']);
        $soilMoistMinYearTime   = explode ('#',$wx['soilMoistMinYearTime']);
        $soilMoistMinAll        = explode ('#',$wx['soilMoistMinAll']);
        $soilMoistMinAllTime    = explode ('#',$wx['soilMoistMinAllTime']);

        
        for  ($n = 1; $n <= $soils; $n++) {
                $i                              = $n - 1;	
                $ws['soilTempAct'][$n]          = wsConvertTemperature  ($soilTempAct[$i],$from_temp);
                $ws['soilTempMaxToday'][$n]     = wsConvertTemperature  ($soilTempMaxToday[$i],$from_temp);
                $ws['soilTempMaxTodayTime'][$n] = mh_ymd                ($soilTempMaxTodayTime[$i]);
                $ws['soilTempMaxYday'][$n]      = wsConvertTemperature  ($soilTempMaxYday[$i],$from_temp);
                $ws['soilTempMaxYdayTime'][$n]  = mh_ymd                ($soilTempMaxYdayTime[$i]);
                $ws['soilTempMaxMonth'][$n]     = wsConvertTemperature  ($soilTempMaxMonth[$i],$from_temp);
                $ws['soilTempMaxMonthTime'][$n] = mh_ymd                ($soilTempMaxMonthTime[$i]);
                $ws['soilTempMaxYear'][$n]      = wsConvertTemperature  ($soilTempMaxYear[$i],$from_temp);
                $ws['soilTempMaxYearTime'][$n]  = mh_ymd                ($soilTempMaxYearTime[$i]); 
                $ws['soilTempMaxAll'][$n]       = wsConvertTemperature  ($soilTempMaxAlltime[$i],$from_temp);
                $ws['soilTempMaxAllTime'][$n]   = mh_ymd                ($soilTempMaxAlltimeTime[$i]);
 
                
                $ws['soilTempMinToday'][$n]     = wsConvertTemperature  ($soilTempMinToday[$i],$from_temp);
                $ws['soilTempMinTodayTime'][$n] = mh_ymd                ($soilTempMinTodayTime[$i]);
                $ws['soilTempMinYday'][$n]      = wsConvertTemperature  ($soilTempMinYday[$i],$from_temp);
                $ws['soilTempMinYdayTime'][$n]  = mh_ymd                ($soilTempMinYdayTime[$i]);
                $ws['soilTempMinMonth'][$n]     = wsConvertTemperature  ($soilTempMinMonth[$i],$from_temp);
                $ws['soilTempMinMonthTime'][$n] = mh_ymd                ($soilTempMinMonthTime[$i]);
                $ws['soilTempMinYear'][$n]      = wsConvertTemperature  ($soilTempMinYear[$i],$from_temp);
                $ws['soilTempMinYearTime'][$n]  = mh_ymd                ($soilTempMinYearTime[$i]);
                $ws['soilTempMinAll'][$n]       = wsConvertTemperature  ($soilTempMinAlltime[$i],$from_temp);
                $ws['soilTempMinAllTime'][$n]   = mh_ymd               ($soilTempMinAlltimeTime[$i]);

                $ws['moistAct'][$n]	        =                $soilMoistAct[$i]*1.0;
 
                $ws['moistMaxToday'][$n]	=                $soilMoistMaxToday[$i]*1.0;
                $ws['moistMaxTodayTime'][$n]	= mh_ymd        ($soilMoistMaxTodayTime[$i]);
                $ws['moistMaxYday'][$n]	        =                $soilMoistMaxYday[$i]*1.0;
                $ws['moistMaxYdayTime'][$n]	= mh_ymd        ($soilMoistMaxYdayTime[$i]);
                $ws['moistMaxMonth'][$n]	=                $soilMoistMaxMonth[$i]*1.0;
                $ws['moistMaxMonthTime'][$n]	= mh_ymd        ($soilMoistMaxMonthTime[$i]); 
                $ws['moistMaxYear'][$n]	        =                $soilMoistMaxYear[$i]*1.0;
                $ws['moistMaxYearTime'][$n]	= mh_ymd        ($soilMoistMaxYearTime[$i]);
                $ws['moistMaxAll'][$n]	        =                $soilMoistMaxAll[$i]*1.0;
                $ws['moistMaxAllTime'][$n]	= mh_ymd        ($soilMoistMaxAllTime[$i]);
                
                $ws['moistMinToday'][$n]	=                $soilMoistMinToday[$i]*1.0;
                $ws['moistMinTodayTime'][$n]	= mh_ymd        ($soilMoistMinTodayTime[$i]);
                $ws['moistMinYday'][$n]	        =                $soilMoistMinYday[$i]*1.0;
                $ws['moistMinYdayTime'][$n]	= mh_ymd        ($soilMoistMinYdayTime[$i]);
                $ws['moistMinMonth'][$n]	=                $soilMoistMinMonth[$i]*1.0;
                $ws['moistMinMonthTime'][$n]	= mh_ymd        ($soilMoistMinMonthTime[$i]); 
                $ws['moistMinYear'][$n]	        =                $soilMoistMinYear[$i]*1.0;
                $ws['moistMinYearTime'][$n]	= mh_ymd        ($soilMoistMinYearTime[$i]);
                $ws['moistMinAll'][$n]	        =                $soilMoistMinAll[$i]*1.0;
                $ws['moistMinAllTime'][$n]	= mh_ymd        ($soilMoistMinAllTime[$i]);
        }
} // eo dosoil
/*
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
*/
$ws['check_ok']         = '3.00';
#
function mhdate   ($time){  // for todays time stamps: remove ':' in time and combine to YYYYMMDDHHMMSS
        return $time;
}
function mh_ymd ($string) {
        return $string;
        }
function mb_untranslated ($field) {
        $pos =  strpos ('  '.$field,'[');
        if ($pos > 0) {return true; } else {return false; }
}
// end of tagsVWS.php
#echo '<pre>'; print_r($ws); exit;
