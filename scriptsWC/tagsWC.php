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
$pageName	= 'tagsWC.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.20 2015-09-28';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.20 2015-09-28 releasse 2.8 version heat errors removed
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
#        if ($content  == '' ) { echo $startEcho.$tagsScript.'('.__LINE__.'):empty line '.$n.' name '.$name .$endEcho.PHP_EOL; continue;}
        $wx[$name]=$content;
}
#print_r ($wx);  echo '------------------halt1'; exit;
$ws['tags_processed']	= $pageName.'-'.$pageVersion;
$ws['tags_originated']	= $SITE['wsTags'].' ('.$wx['pagename'].' - '.$wx['version'].')';
$ws['tags_today']	= $ws['tags_originated'];
$ws['tags_yday']	= $ws['tags_originated'];
$ws['tags_today_time']	= $wx['datetime'];
$ws['tags_yday_time']	= 'n/a';
# ----------------------------------------------------------------------
$date_arr               = explode ('/',trim($wx['date']));
$ymd                    = '20'.$date_arr[2].$date_arr[1].$date_arr[0];
$hms                    = str_replace (':','',trim($wx['time']) );
$ws['actTime']		= $ymd.$hms;
# ------------------------------------------ temperature ---------------
$skip	                = array('?','&#176;',';');
$repl	                = array ('','','');
$from_temp = $from	= str_replace($skip,$repl,$wx['fromtemp']); 	

$ws['tempAct']		= wsConvertTemperature  ($wx['tempAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['tempAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['tempDelta'],$from_temp,$from_temp);
$ws['tempDelta']	= $ws['tempAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['tempMinToday']	= wsConvertTemperature  ($wx['tempMinToday'],$from);
$ws['tempMinTodayTime']	= wc_time               ($wx['tempMinTodayTime']);
$ws['tempMinYday']	= wsConvertTemperature  ($wx['tempMinYday'],$from);
$ws['tempMinYdayTime']	= wc_time               ($wx['tempMinYdayTime']);
$ws['tempMinMonth']	= wsConvertTemperature  ($wx['tempMinMonth'],$from);
$ws['tempMinMonthTime']	= wc_ymd                ($wx['tempMinMonthTime']);
$ws['tempMinYear']	= wsConvertTemperature  ($wx['tempMinYear'],$from);
$ws['tempMinYearTime']	= wc_ymd                ($wx['tempMinYearTime']);
$ws['tempMinAll']	= wsConvertTemperature  ($wx['tempMinAll'],$from);
$ws['tempMinAllTime']	= wc_ymd                ($wx['tempMinAllTime']);

$ws['tempMaxToday']	= wsConvertTemperature  ($wx['tempMaxToday'],$from);
$ws['tempMaxTodayTime']	= wc_time               ($wx['tempMaxTodayTime']);
$ws['tempMaxYday']	= wsConvertTemperature  ($wx['tempMaxYday'],$from);
$ws['tempMaxYdayTime']	= wc_time               ($wx['tempMaxYdayTime']);
$ws['tempMaxMonth']	= wsConvertTemperature  ($wx['tempMaxMonth'],$from);
$ws['tempMaxMonthTime']	= wc_ymd                ($wx['tempMaxMonthTime']);
$ws['tempMaxYear']	= wsConvertTemperature  ($wx['tempMaxYear'],$from);
$ws['tempMaxYearTime']	= wc_ymd                ($wx['tempMaxYearTime']);
$ws['tempMaxAll']	= wsConvertTemperature  ($wx['tempMaxAll'],$from);
$ws['tempMaxAllTime']	= wc_ymd                ($wx['tempMaxAllTime']);

$ws['dewpAct']  	= wsConvertTemperature  ($wx['dewpAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['dewpAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['dewpDelta'],$from_temp,$from_temp);
$ws['dewpDelta']	= $ws['dewpAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['dewpMinToday']  	= wsConvertTemperature  ($wx['dewpMinToday'],$from);
$ws['dewpMinTodayTime']	= wc_time               ($wx['dewpMinTodayTime']);
$ws['dewpMinYday']  	= wsConvertTemperature  ($wx['dewpMinYday'],$from);
$ws['dewpMinYdayTime']	= wc_time               ($wx['dewpMinYdayTime']);
$ws['dewpMinMonth']	= wsConvertTemperature  ($wx['dewpMinMonth'],$from);
$ws['dewpMinMonthTime']	= wc_ymd                ($wx['dewpMinMonthTime']);
$ws['dewpMinYear']	= wsConvertTemperature  ($wx['dewpMinYear'],$from);
$ws['dewpMinYearTime']	= wc_ymd                ($wx['dewpMinYearTime']);
$ws['dewpMinAll']	= wsConvertTemperature  ($wx['dewpMinAll'],$from);
$ws['dewpMinAllTime']	= wc_ymd                ($wx['dewpMinAllTime']);


$ws['dewpMaxToday']  	= wsConvertTemperature  ($wx['dewpMaxToday'],$from);
$ws['dewpMaxTodayTime']	= wc_time               ($wx['dewpMaxTodayTime']);
$ws['dewpMaxYday']  	= wsConvertTemperature  ($wx['dewpMaxYday'],$from);
$ws['dewpMaxYdayTime']	= wc_time               ($wx['dewpMaxYdayTime']);
$ws['dewpMaxMonth']	= wsConvertTemperature  ($wx['dewpMaxMonth'],$from);
$ws['dewpMaxMonthTime']	= wc_ymd                ($wx['dewpMaxMonthTime']);
$ws['dewpMaxYear']	= wsConvertTemperature  ($wx['dewpMaxYear'],$from);
$ws['dewpMaxYearTime']	= wc_ymd                ($wx['dewpMaxYearTime']);
$ws['dewpMaxAll']	= wsConvertTemperature  ($wx['dewpMaxAll'],$from);
$ws['dewpMaxAllTime']	= wc_ymd                ($wx['dewpMaxAllTime']);



$ws['appTemp']  	        = wsConvertTemperature  ($wx['appTempAct'],$from);

$ws['appTempMinToday']          = wsConvertTemperature  ($wx['appTempMinToday'],$from);
$ws['appTempMinTodayTime']	= wc_time               ($wx['appTempMinTodayTime']);
$ws['appTempMinYday']  	        = wsConvertTemperature  ($wx['appTempMinYday'],$from);
$ws['appTempMinYdayTime']	= wc_time               ($wx['appTempMinYdayTime']);
$ws['appTempMinMonth']	        = wsConvertTemperature  ($wx['appTempMinMonth'],$from);
$ws['appTempMinMonthTime']	= wc_ymd                ($wx['appTempMinMonthTime']);
$ws['appTempMinYear']	        = wsConvertTemperature  ($wx['appTempMinYear'],$from);
$ws['appTempMinYearTime']	= wc_ymd                ($wx['appTempMinYearTime']);

$ws['appTempMaxToday']  	= wsConvertTemperature  ($wx['appTempMaxToday'],$from);
$ws['appTempMaxTodayTime']	= wc_time               ($wx['appTempMaxTodayTime']);
$ws['appTempMaxYday']  	        = wsConvertTemperature  ($wx['appTempMaxYday'],$from);
$ws['appTempMaxYdayTime']	= wc_time               ($wx['appTempMaxYdayTime']);
$ws['appTempMaxMonth']	        = wsConvertTemperature  ($wx['appTempMaxMonth'],$from);
$ws['appTempMaxMonthTime']	= wc_ymd                ($wx['appTempMaxMonthTime']);
$ws['appTempMaxYear']	        = wsConvertTemperature  ($wx['appTempMaxYear'],$from);
$ws['appTempMaxYearTime']	= wc_ymd                ($wx['appTempMaxYearTime']);

$ws['heatAct']  	= wsConvertTemperature  ($wx['heatAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['heatAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['heatDelta'],$from_temp,$from_temp);
$ws['heatDelta']	= $ws['heatAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['heatMaxToday']	= wsConvertTemperature  ($wx['heatMaxToday'],$from);
$ws['heatMaxTodayTime'] = wc_time               ($wx['heatMaxTodayTime']);
$ws['heatMaxYday']	= wsConvertTemperature  ($wx['heatMaxYday'],$from);
$ws['heatMaxYdayTime']  = wc_time               ($wx['heatMaxYdayTime']);
$ws['heatMaxMonth']	= wsConvertTemperature  ($wx['heatMaxMonth'],$from);
$ws['heatMaxMonthTime'] = wc_ymd                ($wx['heatMaxMonthTime']);
$ws['heatMaxYear']	= wsConvertTemperature  ($wx['heatMaxYear'],$from);
$ws['heatMaxYearTime']	= wc_ymd                ($wx['heatMaxYearTime']);
$ws['heatMaxAll']	= wsConvertTemperature  ($wx['heatMaxAll'],$from);
$ws['heatMaxAllTime']	= wc_ymd                ($wx['heatMaxAllTime']);

$ws['chilAct']		= wsConvertTemperature  ($wx['chilAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['chilAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['chilDelta'],$from_temp,$from_temp);
$ws['chilDelta']	= $ws['chilAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);

$ws['chilMinToday']	= wsConvertTemperature  ($wx['chilMinToday'],$from);
$ws['chilMinTodayTime']	= wc_time               ($wx['chilMinTodayTime']);
$ws['chilMinYday']	= wsConvertTemperature  ($wx['chilMinYday'],$from);
$ws['chilMinYdayTime']	= wc_time               ($wx['chilMinYdayTime']);
$ws['chilMinMonth']	= wsConvertTemperature  ($wx['chilMinMonth'],$from);
$ws['chilMinMonthTime'] = wc_ymd                ($wx['chilMinMonthTime']);
$ws['chilMinYear']	= wsConvertTemperature  ($wx['chilMinYear'],$from);
$ws['chilMinYearTime']	= wc_ymd                ($wx['chilMinYearTime']);
$ws['chilMinAll']	= wsConvertTemperature  ($wx['chilMinAll'],$from);
$ws['chilMinAllTime']	= wc_ymd                ($wx['chilMinAllTime']);

$ws['chilMaxToday']	= wsConvertTemperature  ($wx['chilMaxToday'],$from);
$ws['chilMaxTodayTime']	= wc_time               ($wx['chilMaxTodayTime']);

$ws['tempActInside']	= wsConvertTemperature  ($wx['tempActInside'],$from);
$ws['tempActExtra1']	= wsConvertTemperature  ($wx['tempActExtra1'],$from);	
$ws['tempActExtra2']	= wsConvertTemperature  ($wx['tempActExtra2'],$from);

$ws['hudxAct']		= wsConvertTemperature  ($wx['hudxAct'],$from);	
# ------------------------------------------ pressure / baro -----------
$from_baro      = $from  = trim(strtolower($wx['frombaro']));     // ' hPa', of ' mb', of ' inHg'

$ws['baroAct'] 		        = wsConvertBaro ($wx['baroAct'],$from);
$ws['baroDelta']	        = wsConvertBaro ($wx['baroDelta'],$from);
$ws['baroTrend']	        =                $wx['baroTrend'];

$ws['baroMinToday']	        = wsConvertBaro ($wx['baroMinToday'],$from);
$ws['baroMinTodayTime']		= wc_time       ($wx['baroMinTodayTime']);
$ws['baroMinYday']	        = wsConvertBaro ($wx['baroMinYday'],$from);
$ws['baroMinYdayTime']		= wc_time       ($wx['baroMinYdayTime']);
$ws['baroMinMonth']	        = wsConvertBaro ($wx['baroMinMonth'],$from);
$ws['baroMinMonthTime']		= wc_ymd        ($wx['baroMinMonthTime']);
$ws['baroMinYear'] 	        = wsConvertBaro ($wx['baroMinYear'],$from);	
$ws['baroMinYearTime']		= wc_ymd        ($wx['baroMinYearTime']);
$ws['baroMinAll'] 	        = wsConvertBaro ($wx['baroMinAll'],$from);	
$ws['baroMinAllTime']		= wc_ymd        ($wx['baroMinAllTime']);

$ws['baroMaxToday']	        = wsConvertBaro ($wx['baroMaxToday'],$from);
$ws['baroMaxTodayTime']		= wc_time       ($wx['baroMaxTodayTime']);
$ws['baroMaxYday']	        = wsConvertBaro ($wx['baroMaxYday'],$from);
$ws['baroMaxYdayTime']		= wc_time       ($wx['baroMaxYdayTime']);
$ws['baroMaxMonth']	        = wsConvertBaro ($wx['baroMaxMonth'],$from);
$ws['baroMaxMonthTime']		= wc_ymd        ($wx['baroMaxMonthTime']);
$ws['baroMaxYear'] 	        = wsConvertBaro ($wx['baroMaxYear'],$from);
$ws['baroMaxYearTime']		= wc_ymd        ($wx['baroMaxYearTime']);
$ws['baroMaxAll'] 	        = wsConvertBaro ($wx['baroMaxAll'],$from);
$ws['baroMaxAllTime']		= wc_ymd        ($wx['baroMaxAllTime']);
# ------------------------------------------ humidity  -----------------
$ws['humiAct']			=                $wx['humiAct']*1.0;
$ws['humiDelta']		=                $wx['humiDelta']*1.0;

$ws['humiMinToday'] 		=                $wx['humiMinToday']*1.0;
$ws['humiMinTodayTime']	        = wc_time       ($wx['humiMinTodayTime']);
$ws['humiMinYday'] 		=                $wx['humiMinYday']*1.0;
$ws['humiMinYdayTime']	        = wc_time       ($wx['humiMinYdayTime']);
$ws['humiMinMonth'] 		=                $wx['humiMinMonth']*1.0;
$ws['humiMinMonthTime']	        = wc_time       ($wx['humiMinMonthTime']);
$ws['humiMinYear'] 		=                $wx['humiMinYear']*1.0;
$ws['humiMinYearTime']	        = wc_time       ($wx['humiMinYearTime']);
$ws['humiMinAll'] 		=                $wx['humiMinAll']*1.0;
$ws['humiMinAllTime']	        = wc_time       ($wx['humiMinAllTime']);

$ws['humiMaxToday']		=               $wx['humiMaxToday']*1.0;
$ws['humiMaxTodayTime'] 	= wc_time       ($wx['humiMaxTodayTime']);
$ws['humiMaxYday']		=                $wx['humiMaxYday']*1.0;
$ws['humiMaxYdayTime']	        = wc_time       ($wx['humiMinYdayTime']);
$ws['humiMaxMonth'] 		=                $wx['humiMaxMonth']*1.0;
$ws['humiMaxMonthTime']	        = wc_time       ($wx['humiMaxMonthTime']);
$ws['humiMaxYear'] 		=                $wx['humiMaxYear']*1.0;
$ws['humiMaxYearTime']	        = wc_time       ($wx['humiMaxYearTime']);
$ws['humiMaxAll'] 		=                $wx['humiMaxAll']*1.0;
$ws['humiMaxAllTime']	        = wc_time       ($wx['humiMaxAllTime']);

$ws['humiInAct']		=                $wx['humiInAct']*1.0;
$ws['humiActExtra1']		=                $wx['humiActExtra1']*1.0;
$ws['humiActExtra2']		=                $wx['humiActExtra2']*1.0;
# ------------------------------------------ rain  ---------------------
$from_rain      = $from  = trim(strtolower($wx['fromrain']));     // 'mm',  'in'

$ws['rainRateAct'] 	        = wsConvertRainfall     ($wx['rainRateAct'],$from);
$ws['rainRateToday'] 	        = wsConvertRainfall     ($wx['rainRateMaxToday'],$from);
$ws['rainRateYday'] 	        = wsConvertRainfall     ($wx['rainRateMaxYday'],$from);

$ws['lastRained']               = wc_ymd                ($wx['lastRained']);

$ws['rainToday']	        = wsConvertRainfall     ($wx['rainToday'],$from);
$ws['rainYday']	                = wsConvertRainfall     ($wx['rainYday'], $from);
$ws['rainWeek']	                = wsConvertRainfall     ($wx['rainWeek'], $from);
$ws['rainMonth']	        = wsConvertRainfall     ($wx['rainMonth'],$from);
$ws['rainYear']		        = wsConvertRainfall     ($wx['rainYear'],$from);
$ws['rainAll']		        = wsConvertRainfall     ($wx['rainAll'],$from);

$ws['rainDayMnth'] 		= $wx['rainDayMnth']*1.0;
$ws['rainDayYear'] 		= $wx['rainDayYear']*1.0;
# ------------------------------------------ EVAPOTRANSPIRATION --------
$ws['etToday'] 		        = wsConvertRainfall     ($wx['etToday'],$from);
$ws['etYday'] 		        = wsConvertRainfall     ($wx['etYday'],$from);
$ws['etMonth'] 		        = wsConvertRainfall     ($wx['etMonth'],$from);
$ws['etYear'] 		        = wsConvertRainfall     ($wx['etYear'],$from);
# ------------------------------------------ wind  ---------------------
$from_wind      = $from =       trim(strtolower($wx['fromwind']));        

$ws['windAct']		        = wsConvertWindspeed    ($wx['windAct'], $from);
$ws['gustAct']		        = wsConvertWindspeed    ($wx['gustAct'], $from);
$ws['windActDsc']		= wsConvertWinddir      ($wx['windActDsc']); 
$ws['windActDir']		=                        $wx['windAvgDir']; 
$ws['windBeafort']		= wsBeaufortNumber      ($wx['windAct'],$from);

$ws['windAvgDir']               = $wx['windAvgDir'];

$ws['gustMaxToday']	        = wsConvertWindspeed    ($wx['gustMaxToday'], $from);
$ws['gustMaxTodayTime']		= wc_time               ($wx['gustMaxTodayTime']);
$ws['gustMaxYday']	        = wsConvertWindspeed    ($wx['gustMaxYday'], $from);
$ws['gustMaxYdayTime']		= wc_time               ($wx['gustMaxYTime']);
$ws['gustMaxMonth']	        = wsConvertWindspeed    ($wx['gustMaxMonth'], $from);
$ws['gustMaxMonthTime']		= wc_ymd                ($wx['gustMaxMonthTime']);
$ws['gustMaxYear']	        = wsConvertWindspeed    ($wx['gustMaxYear'], $from);	
$ws['gustMaxYearTime']		= wc_ymd                ($wx['gustMaxYearTime']);
$ws['gustMaxAll']	        = wsConvertWindspeed    ($wx['gustMaxAll'], $from);	
$ws['gustMaxAllTime']		= wc_ymd                ($wx['gustMaxAllTime']);

if ($ws['gustAct'] < $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}
# ------------------------------------------  UV   ---------------------
$ws['uvAct']			=                        $wx['uvAct']*1.0;
$ws['uvMaxToday']		=                        $wx['uvMaxToday']*1.0;
$ws['uvMaxTodayTime'] 		= wc_time               ($wx['uvMaxTodayTime']);
$ws['uvMaxYday']		=                        $wx['uvMaxYday']*1.0;
$ws['uvMaxYdayTime'] 		= wc_time               ($wx['uvMaxYdayTime']);
$ws['uvMaxMonth']		=                        $wx['uvMaxMonth']*1.0;
$ws['uvMaxMonthTime'] 		= wc_ymd                ($wx['uvMaxMonthTime']);
$ws['uvMaxYear']		=                        $wx['uvMaxYear']*1.0;
$ws['uvMaxYearTime'] 		= wc_ymd                ($wx['uvMaxYearTime']);
$ws['uvMaxAll']		        =                        $wx['uvMaxAll']*1.0;
$ws['uvMaxAllTime'] 		= wc_ymd                ($wx['uvMaxAllTime']);
# ------------------------------------------ Solar  --------------------
$ws['solarAct']			=                        $wx['solarAct']*1.0;
$max_solar			=                        $wx['solarActMaxPossible']*1.0;
if ($max_solar > 0) {$ws['solarActPerc'] = round((100*$ws['solarAct']/$max_solar),0);} else {$ws['solarActPerc'] = 0;}

$ws['solarMaxToday']		=                        $wx['solarMaxToday']*1.0;
$ws['solarMaxTodayTime'] 	= wc_time               ($wx['solarMaxTodayTime']);
$ws['solarMaxYday']		=                        $wx['solarMaxYday']*1.0;
$ws['solarMaxYdayTime'] 	= wc_time               ($wx['solarMaxYdayTime']);
$ws['solarMaxMonth']		=                        $wx['solarMaxMonth']*1.0;
$ws['solarMaxMonthTime'] 	= wc_ymd                ($wx['solarMaxMonthTime']);
$ws['solarMaxYear']		=                        $wx['solarMaxYear']*1.0;
$ws['solarMaxYearTime'] 	= wc_ymd                ($wx['solarMaxYearTime']);
$ws['solarMaxAll']		=                        $wx['solarMaxAll']*1.0;
$ws['solarMaxAllTime'] 	        = wc_ymd                ($wx['solarMaxAllTime']);

#------------------------------------------cloudheight------------------
$from_height                    = trim(strtolower       ($wx['fromheight']));
#$ws['cloudHeight']	        = wsConvertDistance     ($wx['cloudHeight'], $from_height); // from WC release 2.1 only
# ------------------------------------------ forecasts -----------------
$ws['fcstTxt']                  = trim($wx['fcstTxt']);
# ------------------------------------------  moon ---------------------
# ------------------------------------------ some more -----------------
$ws['wsVersion']		= $wx['wsVersion'];
$ws['wsHardware'] 		= $wx['wsHardware'];
$ws['wsUptime']			= $wx['wsUptime'];
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
        $soilTempMaxYdayTime   = explode ('#',$wx['soilTempMaxYdayTime']);
        $soilTempMaxMonthTime   = explode ('#',$wx['soilTempMaxMonthTime']);
        $soilTempMaxYearTime    = explode ('#',$wx['soilTempMaxYearTime']);
        $soilTempMaxAlltimeTime = explode ('#',$wx['soilTempMaxAllTime']);
        
        $soilTempMinToday       = explode ('#',$wx['soilTempMinToday']);
        $soilTempMinYday        = explode ('#',$wx['soilTempMinYday']);
        $soilTempMinMonth       = explode ('#',$wx['soilTempMinMonth']);
        $soilTempMinYear        = explode ('#',$wx['soilTempMinYear']);
        $soilTempMinAlltime     = explode ('#',$wx['soilTempMinAll']);
        $soilTempMinTodayTime   = explode ('#',$wx['soilTempMinTodayTime']);
        $soilTempMinYdayTime    = explode ('#',$wx['soilTempMinYdayTime']);
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
                $ws['soilTempMaxTodayTime'][$n] = wc_ymd                ($soilTempMaxTodayTime[$i]);
                $ws['soilTempMaxYday'][$n]      = wsConvertTemperature  ($soilTempMaxYday[$i],$from_temp); 
                $ws['soilTempMaxYdayTime'][$n]  = wc_ymd                ($soilTempMaxYdayTime[$i]);
                $ws['soilTempMaxMonth'][$n]     = wsConvertTemperature  ($soilTempMaxMonth[$i],$from_temp);
                $ws['soilTempMaxMonthTime'][$n] = wc_ymd                ($soilTempMaxMonthTime[$i]);
                $ws['soilTempMaxYear'][$n]      = wsConvertTemperature  ($soilTempMaxYear[$i],$from_temp);
                $ws['soilTempMaxYearTime'][$n]  = wc_ymd                ($soilTempMaxYearTime[$i]); 
                $ws['soilTempMaxAll'][$n]       = wsConvertTemperature  ($soilTempMaxAlltime[$i],$from_temp);
                $ws['soilTempMaxAllTime'][$n]   = wc_ymd                ($soilTempMaxAlltimeTime[$i]);
 
                
                $ws['soilTempMinToday'][$n]     = wsConvertTemperature  ($soilTempMinToday[$i],$from_temp);
                $ws['soilTempMinTodayTime'][$n] = wc_ymd                ($soilTempMinTodayTime[$i]);
                $ws['soilTempMinYday'][$n]      = wsConvertTemperature  ($soilTempMinYday[$i],$from_temp);
                $ws['soilTempMinYdayTime'][$n]  = wc_ymd                ($soilTempMinYdayTime[$i]);
                $ws['soilTempMinMonth'][$n]     = wsConvertTemperature  ($soilTempMinMonth[$i],$from_temp);
                $ws['soilTempMinMonthTime'][$n] = wc_ymd                ($soilTempMinMonthTime[$i]);
                $ws['soilTempMinYear'][$n]      = wsConvertTemperature  ($soilTempMinYear[$i],$from_temp);
                $ws['soilTempMinYearTime'][$n]  = wc_ymd                ($soilTempMinYearTime[$i]);
                $ws['soilTempMinAll'][$n]       = wsConvertTemperature  ($soilTempMinAlltime[$i],$from_temp);
                $ws['soilTempMinAllTime'][$n]   = wc_ymd                ($soilTempMinAlltimeTime[$i]);

                $ws['moistAct'][$n]	        =                $soilMoistAct[$i]*1.0;
 
                $ws['moistMaxToday'][$n]	=                $soilMoistMaxToday[$i]*1.0;
                $ws['moistMaxTodayTime'][$n]	= wc_ymd        ($soilMoistMaxTodayTime[$i]);
                $ws['moistMaxYday'][$n]	        =                $soilMoistMaxYday[$i]*1.0;
                $ws['moistMaxYdayTime'][$n]	= wc_ymd        ($soilMoistMaxYdayTime[$i]);
                $ws['moistMaxMonth'][$n]	=                $soilMoistMaxMonth[$i]*1.0;
                $ws['moistMaxMonthTime'][$n]	= wc_ymd        ($soilMoistMaxMonthTime[$i]); 
                $ws['moistMaxYear'][$n]	        =                $soilMoistMaxYear[$i]*1.0;
                $ws['moistMaxYearTime'][$n]	= wc_ymd        ($soilMoistMaxYearTime[$i]);
                $ws['moistMaxAll'][$n]	        =                $soilMoistMaxAll[$i]*1.0;
                $ws['moistMaxAllTime'][$n]	= wc_ymd        ($soilMoistMaxAllTime[$i]);
                
                $ws['moistMinToday'][$n]	=                $soilMoistMinToday[$i]*1.0;
                $ws['moistMinTodayTime'][$n]	= wc_ymd        ($soilMoistMinTodayTime[$i]);
                $ws['moistMinYday'][$n]	        =                $soilMoistMinYday[$i]*1.0;
                $ws['moistMinYdayTime'][$n]	= wc_ymd        ($soilMoistMinYdayTime[$i]);
                $ws['moistMinMonth'][$n]	=                $soilMoistMinMonth[$i]*1.0;
                $ws['moistMinMonthTime'][$n]	= wc_ymd        ($soilMoistMinMonthTime[$i]); 
                $ws['moistMinYear'][$n]	        =                $soilMoistMinYear[$i]*1.0;
                $ws['moistMinYearTime'][$n]	= wc_ymd        ($soilMoistMinYearTime[$i]);
                $ws['moistMinAll'][$n]	        =                $soilMoistMinAll[$i]*1.0;
                $ws['moistMinAllTime'][$n]	= wc_ymd        ($soilMoistMinAllTime[$i]);
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
#-----------------------------------------------------------------------
# retrieve missing moon entries
$skipMoonPage           = true; 
include ($SITE['moonSet']);
$skipMoonPage           = false;
#-----------------------------------------------------------------------
$ws['check_ok']         = '3.00';
if ($test) {echo '<pre>'; print_r($ws); exit;}
return;

function wc_ymd    ($date){ 
        $unixDate       = strtotime($date);
        return          date('YmdHis',$unixDate);
}
function wc_time    ($time){  // for todays time stamps: remove ':' in time and combine to YYYYMMDDHHMMSS
        global $ymd;            // 13:30   03:30 
        if (trim($time) == '?') {return $ymd.'000000';}
        $result = str_replace (':','',$time);
        if (trim($result) == ''){return $ymd.'000000';}  
        $int = strtotime('20150101'.$time);
        return ($ymd.strftime('%H%M%S',$int) );
}
# ----------------------  version history
# 3.20 2015-09-28 releasse 2.8 version heat errors removed
