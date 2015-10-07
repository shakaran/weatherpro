<?php 	ini_set('display_errors', 'On'); error_reporting(E_ALL);	
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'tagsMP.php';
$pageVersion	= '0.01 2015-07-10';
#-----------------------------------------------------------------------
# 0.01 2015-07-10  beta version
# --------------------------------------- version ----------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
# --------------------------------------- version ----------------------
$tagsScript     = $pageName;
if (!isset ($SITE['meteoplug_cache']) ) {$SITE['meteoplug_cache'] = 300;}
$startEcho      = '<!-- ';      
$endEcho        = ' -->';
#
$test           = false;
if ($test) {echo '<pre>Start test'.PHP_EOL;$startEcho      = ''; $endEcho        = '';}
#
$uoms		= $uomTemp.$uomBaro.$uomWind.$uomRain.$uomSnow.$uomDistance.$uomPerHour.$uomHeight;
$from		= array('/',' ','&deg;','.php');
$to		= '';
#
$mp_load	= '';
if (is_file ('_my_texts/mp_links.txt') ) {
	include '_my_texts/mp_links.txt';
}
if (trim($mp_load) == '' || $mp_load == false || $mp_load == 'test') {$test = true; $mp_load = 'test';}
$draw_part	= $mp_load;
#
$cachefileMP	= $SITE['cacheDir'].str_replace ($from, $to, $tagsScript.'_'.$draw_part.'_'.$uoms);  // add uoms
$loaded_current = false;
$local_current  = './uploadMP/test1.txt';
#
$url            = 'http://www.meteoplug.com/cgi-bin/meteochart.cgi?draw='.$draw_part;
#
if (isset($_REQUEST['force']) && strtolower($_REQUEST['force']) == 'mp') {
        echo $startEcho.$tagsScript .': data freshly loaded while "force" was used.'.$endEcho.PHP_EOL;
        $loaded_current =  false;
}elseif (file_exists($cachefileMP) ){
	$file_time      = filemtime($cachefileMP);
	$now            = time();
	$diff           = ($now-$file_time);
	$cacheAllowed   = $SITE['meteoplug_cache'];
        echo  "<!-- 
$tagsScript ($cachefileMP)
        cache time   = ".date('c',$file_time)." from unix time $file_time
        current time = ".date('c',$now)." from unix time $now 
        difference   = $diff (seconds)
        diff allowed = $cacheAllowed (seconds) -->".PHP_EOL;		
	if ($diff <= $cacheAllowed){
		$ws     =  unserialize(file_get_contents($cachefileMP));
                echo $startEcho.$tagsScript.': data loaded from '.$cachefileMP.$endEcho.PHP_EOL;
                $loaded_current =   true;
 #               print_r ($ws); # exit;
                return;  
	} else {
		echo $startEcho.$tagsScript.": data to old, will be loaded from url ".$endEcho.PHP_EOL;
	}
}
if ($test) {
        echo $startEcho.$tagsScript.': data loaded from test-file at '.$local_current.$endEcho.PHP_EOL;
        $string = file_get_contents($local_current);
        $loaded_current = false;
}
elseif ($loaded_current == false) {
        echo $startEcho.$tagsScript.': data loaded from url: '.$url.$endEcho.PHP_EOL;
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 4);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 20);
        $string = curl_exec ($ch);
        file_put_contents($SITE['wsTags'], $string);
        echo '<!-- data saved to '.$SITE['wsTags'].'  -->'.PHP_EOL;

 #       echo $string; exit;
        curl_close ($ch);
} 
if ($loaded_current == false && trim($string) == '') {
	if ($diff <= 3* $cacheAllowed){
		$ws     =  unserialize(file_get_contents($cachefileWLC));
                echo $startEcho.$tagsScript.': data loaded from '.$cachefileMP.' after upping cache time'.$endEcho.PHP_EOL;
                $loaded_current =   true;
 #               print_r ($ws); # exit;
                return;         // ?????
        }
        echo '<H3 input file from Meteoplug has no contents - program ends, please reload page </h3>'; return;
}
$arr    = explode ("\n",$string);
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
#	if ($content  == '' ) { echo $startEcho.$tagsScript.'('.__LINE__.'):empty line '.$n.' name '.$name .$endEcho.PHP_EOL; continue;}
	$wx[$name]=$content;
}

#print_r ($wx);  echo '------------------halt1'; exit;

$ws['tags_processed']	= $pageName.'-'.$pageVersion;
$ws['tags_originated']	= $SITE['wsTags'].'-'.  $wx['pagename'];
$ws['tags_today']	= $SITE['wsTags'].'-'.  $wx['pagename'];
$ws['tags_today_time']	= $wx['datetime'];
# ----------------------------------------------------------------------
$ws['actTime']		= mpdate                ($wx['datetime']);
# ------------------------------------------ temperature ---------------
$from_temp              = $wx['fromtemp'];
$ws['tempAct']		= wsConvertTemperature  ($wx['tempAct'],$from_temp);
$ws['tempDelta']	= $ws['tempAct'] - wsConvertTemperature  ($wx['tempDelta'],$from_temp);
$ws['tempMinToday']	= wsConvertTemperature  ($wx['tempMinToday'],$from_temp);
$ws['tempMinTodayTime']	= mpdate                ($wx['tempMinTodayTime']);
$ws['tempMinYday']	= wsConvertTemperature  ($wx['tempMinYday'],$from_temp);
$ws['tempMinYdayTime']	= mpdate                ($wx['tempMinYdayTime']);
$ws['tempMinMonth']	= wsConvertTemperature  ($wx['tempMinMonth'],$from_temp);
$ws['tempMinMonthTime']	= mpdate                ($wx['tempMinMonthTime']);
$ws['tempMinYear']	= wsConvertTemperature  ($wx['tempMinYear'],$from_temp);
$ws['tempMinYearTime']	= mpdate                ($wx['tempMinYearTime']);
$ws['tempMinAll']	= wsConvertTemperature  ($wx['tempMinAll'],$from_temp);
$ws['tempMinAllTime']	= mpdate                ($wx['tempMinAllTime']);

$ws['tempMaxToday']	= wsConvertTemperature  ($wx['tempMaxToday'],$from_temp);
$ws['tempMaxTodayTime']	= mpdate                ($wx['tempMaxTodayTime']);
$ws['tempMaxYday']	= wsConvertTemperature  ($wx['tempMaxYday'],$from_temp);
$ws['tempMaxYdayTime']	= mpdate                ($wx['tempMaxYdayTime']);
$ws['tempMaxMonth']	= wsConvertTemperature  ($wx['tempMaxMonth'],$from_temp);
$ws['tempMaxMonthTime']	= mpdate                ($wx['tempMaxMonthTime']);
$ws['tempMaxYear']	= wsConvertTemperature  ($wx['tempMaxYear'],$from_temp);
$ws['tempMaxYearTime']	= mpdate                ($wx['tempMaxYearTime']);
$ws['tempMaxAll']	= wsConvertTemperature  ($wx['tempMaxAll'],$from_temp);
$ws['tempMaxAllTime']	= mpdate                ($wx['tempMaxAllTime']);

$ws['dewpAct']  	= wsConvertTemperature  ($wx['dewpAct'],$from_temp);
$ws['dewpDelta']	= $ws['dewpAct'] - wsConvertTemperature  ($wx['dewpDelta'],$from_temp);

$ws['dewpMinToday']  	= wsConvertTemperature  ($wx['dewpMinToday'],$from_temp);
$ws['dewpMinTodayTime']	= mpdate                ($wx['dewpMinTodayTime']);
$ws['dewpMinYday']  	= wsConvertTemperature  ($wx['dewpMinYday'],$from_temp);
$ws['dewpMinYdayTime']	= mpdate                ($wx['dewpMinYdayTime']);
$ws['dewpMinMonth']  	= wsConvertTemperature  ($wx['dewpMinMonth'],$from_temp);
$ws['dewpMinMonthTime']	= mpdate                ($wx['dewpMinMonthTime']);
$ws['dewpMinYear']  	= wsConvertTemperature  ($wx['dewpMinYear'],$from_temp);
$ws['dewpMinYearTime']	= mpdate                ($wx['dewpMinYearTime']);
$ws['dewpMinAll']  	= wsConvertTemperature  ($wx['dewpMinAll'],$from_temp);
$ws['dewpMinAllTime']	= mpdate                ($wx['dewpMinAllTime']);

$ws['dewpMaxToday']  	= wsConvertTemperature  ($wx['dewpMaxToday'],$from_temp);
$ws['dewpMaxTodayTime']	= mpdate                ($wx['dewpMaxTodayTime']);
$ws['dewpMaxYday']  	= wsConvertTemperature  ($wx['dewpMaxYday'],$from_temp);
$ws['dewpMaxYdayTime']	= mpdate                ($wx['dewpMaxYdayTime']);
$ws['dewpMaxMonth']  	= wsConvertTemperature  ($wx['dewpMaxMonth'],$from_temp);
$ws['dewpMaxMonthTime']	= mpdate                ($wx['dewpMaxMonthTime']);
$ws['dewpMaxYear']  	= wsConvertTemperature  ($wx['dewpMaxYear'],$from_temp);
$ws['dewpMaxYearTime']	= mpdate                ($wx['dewpMaxYearTime']);
$ws['dewpMaxAll']  	= wsConvertTemperature  ($wx['dewpMaxAll'],$from_temp);
$ws['dewpMaxAllTime']	= mpdate                ($wx['dewpMaxAllTime']);

$ws['appTemp']  	        = '';

$ws['appTempMinToday']  	= '';
$ws['appTempMinTodayTime']	= '';
$ws['appTempMinYday']  	        = '';
$ws['appTempMinYdayTime']	= '';

$ws['appTempMaxToday']  	= '';
$ws['appTempMaxTodayTime']	= '';
$ws['appTempMaxYday']  	        = '';
$ws['appTempMaxYdayTime']	= '';

$ws['heatAct']  	= wsConvertTemperature  ($wx['heatAct'],$from_temp);
$ws['heatDelta']	= $ws['heatAct'] - wsConvertTemperature  ($wx['heatDelta'],$from_temp);

$ws['heatMaxToday']  	= wsConvertTemperature  ($wx['heatMaxToday'],$from_temp);
$ws['heatMaxTodayTime']	= mpdate                ($wx['heatMaxTodayTime']);
$ws['heatMaxYday']  	= wsConvertTemperature  ($wx['heatMaxYday'],$from_temp);
$ws['heatMaxYdayTime']	= mpdate                ($wx['heatMaxYdayTime']);
$ws['heatMaxMonth']	= wsConvertTemperature  ($wx['heatMaxMonth'],$from_temp);
$ws['heatMaxMonthTime'] = mpdate                ($wx['heatMaxMonthTime']);
$ws['heatMaxYear']	= wsConvertTemperature  ($wx['heatMaxYear'],$from_temp);
$ws['heatMaxYearTime']	= mpdate                ($wx['heatMaxYearTime']);
$ws['heatMaxAll']	= wsConvertTemperature  ($wx['heatMaxAll'],$from_temp);
$ws['heatMaxAllTime']	= mpdate                ($wx['heatMaxAllTime']);

$ws['chilAct']  	= wsConvertTemperature  ($wx['chilAct'],$from_temp);
$ws['chilDelta']	= $ws['chilAct'] - wsConvertTemperature  ($wx['chilDelta'],$from_temp);

$ws['chilMinToday']  	= wsConvertTemperature  ($wx['chilMinToday'],$from_temp);
$ws['chilMinTodayTime']	= mpdate                ($wx['chilMinTodayTime']);
$ws['chilMinYday']  	= wsConvertTemperature  ($wx['chilMinYday'],$from_temp);
$ws['chilMinYdayTime']	= mpdate                ($wx['chilMinYdayTime']);
$ws['chilMinMonth']  	= wsConvertTemperature  ($wx['chilMinMonth'],$from_temp);
$ws['chilMinMonthTime']	= mpdate                ($wx['chilMinMonthTime']);
$ws['chilMinYear']  	= wsConvertTemperature  ($wx['chilMinYear'],$from_temp);
$ws['chilMinYearTime']	= mpdate                ($wx['chilMinYearTime']);
$ws['chilMinAll']  	= wsConvertTemperature  ($wx['chilMinAll'],$from_temp);
$ws['chilMinAllTime']	= mpdate                ($wx['chilMinAllTime']);

$ws['chilMaxToday']  	= wsConvertTemperature  ($wx['chilMinToday'],$from_temp);
$ws['chilMaxTodayTime'] = mpdate                ($wx['chilMaxTodayTime']);

$ws['tempActInside']	= wsConvertTemperature  ($wx['tempActInside'],$from_temp);
$ws['tempActExtra1']	= wsConvertTemperature  ($wx['tempActExtra1'],$from_temp);	
$ws['tempActExtra2']	= wsConvertTemperature  ($wx['tempActExtra2'],$from_temp);	
$ws['tempToday']	= $ws['tempAct'];

if (isset ($wx['fromhudx']) ) {$from = $wx['fromhudx'];} else {$from = $from_temp;}
$ws['hudxAct'] 	        = wsConvertTemperature  ($wx['hudxAct'],$from);
$ws['hudxDelta']	= $ws['hudxAct'] - wsConvertTemperature  ($wx['hudxDelta'],$from);
$ws['hudxMaxToday']  	= wsConvertTemperature  ($wx['hudxMaxToday'],$from);
$ws['hudxMaxTodayTime']	= mpdate                ($wx['hudxMaxTodayTime']);
$ws['hudxMaxYday']  	= wsConvertTemperature  ($wx['hudxMaxYday'],$from);
$ws['hudxMaxYdayTime']	= mpdate                ($wx['hudxMaxYdayTime']);
$ws['hudxMaxMonth']	= wsConvertTemperature  ($wx['hudxMaxMonth'],$from);
$ws['hudxMaxMonthTime'] = mpdate                ($wx['hudxMaxMonthTime']);
$ws['hudxMaxYear']	= wsConvertTemperature  ($wx['hudxMaxYear'],$from);
$ws['hudxMaxYearTime']	= mpdate                ($wx['hudxMaxYearTime']);
$ws['hudxMaxAll']	= wsConvertTemperature  ($wx['hudxMaxAll'],$from);
$ws['hudxMaxAllTime']	= mpdate                ($wx['hudxMaxAllTime']);

# ------------------------------------------ pressure / baro -----------
$from_baro              = trim(strtolower($wx['frombaro']));     // ' hPa', of ' mb', of ' inHg'

$ws['baroAct'] 	        = wsConvertBaro ($wx['baroAct'],$from_baro);
$ws['baroDelta']	= $ws['baroAct'] - wsConvertBaro ($wx['baroDelta'],$from_baro);
$ws['baroTrend']	= langtransstr  (wsBarotrendText($ws['baroDelta']));

$ws['baroMinToday']	= wsConvertBaro ($wx['baroMinToday'],$from_baro);
$ws['baroMinTodayTime']	= mpdate        ($wx['baroMinTodayTime']);
$ws['baroMinYday']	= wsConvertBaro ($wx['baroMinYday'],$from_baro);
$ws['baroMinYdayTime']	= mpdate        ($wx['baroMinYdayTime']);
$ws['baroMinMonth']	= wsConvertBaro ($wx['baroMinMonth'],$from_baro);
$ws['baroMinMonthTime']	= mpdate        ($wx['baroMinMonthTime']);
$ws['baroMinYear'] 	= wsConvertBaro ($wx['baroMinYear'],$from_baro);	
$ws['baroMinYearTime']	= mpdate        ($wx['baroMinYearTime']);
$ws['baroMinAll'] 	= wsConvertBaro ($wx['baroMinAll'],$from_baro);	
$ws['baroMinAllTime']	= mpdate        ($wx['baroMinAllTime']);

$ws['baroMaxToday']	= wsConvertBaro ($wx['baroMaxToday'],$from_baro);
$ws['baroMaxTodayTime'] = mpdate        ($wx['baroMaxTodayTime']);
$ws['baroMaxYday']	= wsConvertBaro ($wx['baroMaxYday'],$from_baro);
$ws['baroMaxYdayTime']	= mpdate        ($wx['baroMaxYdayTime']);
$ws['baroMaxMonth']	= wsConvertBaro ($wx['baroMaxMonth'],$from_baro);
$ws['baroMaxMonthTime']= mpdate        ($wx['baroMaxMonthTime']);
$ws['baroMaxYear']	= wsConvertBaro ($wx['baroMaxYear'],$from_baro);
$ws['baroMaxYearTime']	= mpdate        ($wx['baroMaxYearTime']);
$ws['baroMaxAll']	= wsConvertBaro ($wx['baroMaxAll'],$from_baro);
$ws['baroMaxAllTime']	= mpdate        ($wx['baroMaxAllTime']);
# ------------------------------------------ humidity  -----------------
$ws['humiAct']			= $wx['humiAct']*1.0;
$ws['humiDelta']		= $ws['humiAct'] - $wx['humiDelta']*1.0;

$ws['humiMinToday'] 		=                $wx['humiMinToday']*1.0;
$ws['humiMinTodayTime']	        = mpdate        ($wx['humiMinTodayTime']);
$ws['humiMinYday'] 		=                $wx['humiMinYday']*1.0;
$ws['humiMinYdayTime']	        = mpdate        ($wx['humiMinYdayTime']);
$ws['humiMinMonth']	        =                $wx['humiMinMonth'];
$ws['humiMinMonthTime']	        = mpdate        ($wx['humiMinMonthTime']);
$ws['humiMinYear'] 	        =                $wx['humiMinYear'];	
$ws['humiMinYearTime']	        = mpdate        ($wx['humiMinYearTime']);
$ws['humiMinAll'] 	        =                $wx['humiMinAll'];	
$ws['humiMinAllTime']	        = mpdate        ($wx['humiMinAllTime']);

$ws['humiMaxToday']		=                $wx['humiMaxToday']*1.0;
$ws['humiMaxTodayTime']	        = mpdate        ($wx['humiMaxTodayTime']);
$ws['humiMaxYday']		=                $wx['humiMaxYday']*1.0;
$ws['humiMaxYdayTime']	        = mpdate        ($wx['humiMaxYdayTime']);
$ws['humiMaxMonth']	        =                $wx['humiMaxMonth'];
$ws['humiMaxMonthTime']	        = mpdate        ($wx['humiMaxMonthTime']);
$ws['humiMaxYear'] 	        =                $wx['humiMaxYear'];	
$ws['humiMaxYearTime']	        = mpdate        ($wx['humiMaxYearTime']);
$ws['humiMaxAll'] 	        =                $wx['humiMaxAll'];	
$ws['humiMaxAllTime']	        = mpdate        ($wx['humiMaxAllTime']);

$ws['humiInAct']		=                $wx['humiInAct']*1.0;
$ws['humiActExtra1']		=                $wx['humiActExtra1']*1.0;
$ws['humiActExtra2']		=                $wx['humiActExtra2']*1.0;
# ------------------------------------------ rain  ---------------------
$from_rain      = trim(strtolower($wx['fromrain']));     // 'mm',  'in'

$ws['rainRateAct'] 	        = wsConvertRainfall     ($wx['rainRateAct'],$from_rain);
$ws['rainHourAct']              = $ws['rainHour'] = wsConvertRainfall     ($wx['rainHourAct'],$from_rain);

$ws['rainRateMaxToday'] 	= wsConvertRainfall     ($wx['rainRateMaxToday'],$from_rain);
$ws['rainRateMaxYday'] 	        = ''; #wsConvertRainfall     ($wx['rainRateMaxYday'],$from_rain);

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
$ws['etToday'] 		        = ''; # wsConvertRainfall     ($wx['etToday'],$from_rain);
$ws['etYday'] 		        = '';
$ws['etMonth'] 		        = '';
$ws['etYear'] 		        = '';
$ws['etAll'] 		        = '';
# ------------------------------------------ wind  ---------------------
$from_wind      = trim(strtolower($wx['fromwind']));       //  "m/s", "mph", "km/h", "kts" ?? 'kmh' 'Bft'??  windrun  "km", "miles",

$ws['windAct']		        = wsConvertWindspeed    ($wx['windAct'], $from_wind);
$ws['gustAct']		        = wsConvertWindspeed    ($wx['gustAct'], $from_wind);

$ws['windActDsc']		=                        $wx['windActDsc'];  //NNE
$ws['windActDir']		=                        $wx['windActDir']*22.5;  // 20 deg
$ws['windAvgDir']               =                        $wx['windAvgDir']*22.5;

$ws['windBeafort']		= wsBeaufortNumber      ($wx['windAct'], $from_wind);

$ws['gustMaxToday']	        = wsConvertWindspeed    ($wx['gustMaxToday'], $from_wind);
$ws['gustMaxTodayTime']         = mpdate                ($wx['gustMaxTodayTime']);
$ws['gustMaxYday']	        = wsConvertWindspeed    ($wx['gustMaxYday'], $from_wind);
$ws['gustMaxYdayTime']          = mpdate                ($wx['gustMaxYdayTime']);
$ws['gustMaxMonth']	        = wsConvertWindspeed    ($wx['gustMaxMonth'], $from_wind);
$ws['gustMaxMonthTime']	        = mpdate                ($wx['gustMaxMonthTime']);
$ws['gustMaxYear']	        = wsConvertWindspeed    ($wx['gustMaxYear'], $from_wind);	
$ws['gustMaxYearTime']	        = mpdate                ($wx['gustMaxYearTime']);
$ws['gustMaxAll']	        = wsConvertWindspeed    ($wx['gustMaxAll'], $from_wind);	
$ws['gustMaxAllTime']	        = mpdate                ($wx['gustMaxAllTime']);

if ($ws['gustAct'] < $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}

#$from_distance                  = trim(strtolower($wx['fromdistance']));
#$ws['windrunToday']             = wsConvertDistance     ($wx['windrunToday'],$from_distance);
# ------------------------------------------  UV   ---------------------
$ws['uvAct']			=                        $wx['uvAct']*1.0;
$ws['uvMaxToday']		=                        $wx['uvMaxToday']*1.0;
$ws['uvMaxTodayTime'] 		= mpdate                ($wx['uvMaxTodayTime']);
$ws['uvMaxYday']		=                        $wx['uvMaxYday']*1.0;
$ws['uvMaxYdayTime'] 		= mpdate                ($wx['uvMaxYdayTime']);
$ws['uvMaxMonth']		=                        $wx['uvMaxMonth']*1.0;
$ws['uvMaxMonthTime'] 		= mpdate                ($wx['uvMaxMonthTime']);
$ws['uvMaxYear']		=                        $wx['uvMaxYear']*1.0;
$ws['uvMaxYearTime'] 		= mpdate                ($wx['uvMaxYearTime']);
$ws['uvMaxAll']		        =                        $wx['uvMaxAll']*1.0;
$ws['uvMaxAllTime'] 		= mpdate                ($wx['uvMaxAllTime']);
# ------------------------------------------ Solar  --------------------
$ws['solarAct']			=                        $wx['solarAct']*1.0;
$ws['solarActPerc']		= ''; #                       $wx['solarActPerc']*1.0;
$ws['solarMaxToday']		=                        $wx['solarMaxToday']*1.0;
$ws['solarMaxTodayTime'] 	= mpdate                ($wx['solarMaxTodayTime']);
$ws['solarMaxYday']		=                        $wx['solarMaxYday']*1.0;
$ws['solarMaxYdayTime'] 	= mpdate                ($wx['solarMaxYdayTime']);
$ws['solarMaxMonth']		=                        $wx['solarMaxMonth']*1.0;
$ws['solarMaxMonthTime'] 	= mpdate                ($wx['solarMaxMonthTime']);
$ws['solarMaxYear']		=                        $wx['solarMaxYear']*1.0;
$ws['solarMaxYearTime'] 	= mpdate                ($wx['solarMaxYearTime']);
$ws['solarMaxAll']		=                        $wx['solarMaxAll']*1.0;
$ws['solarMaxAllTime'] 	        = mpdate                ($wx['solarMaxAllTime']);
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
$ws['tempFrom']                 = '&deg;C';     // always these value, 
$ws['baroFrom']                 = ' hPa';       // no others available
$ws['rainFrom']                 = ' kts';       // for seq  fields from mh
$ws['windFrom']                 = ' mm';        // 
# ------------------------------------------ some more -----------------
$ws['wsVersion']		= '';
$ws['wsHardware'] 		= '';
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
                $ws['soilTempMaxTodayTime'][$n] = mpdate                ($soilTempMaxTodayTime[$i]);
                $ws['soilTempMaxYday'][$n]      = wsConvertTemperature  ($soilTempMaxYday[$i],$from_temp);
                $ws['soilTempMaxYdayTime'][$n]  = mpdate                ($soilTempMaxYdayTime[$i]);
                $ws['soilTempMaxMonth'][$n]     = wsConvertTemperature  ($soilTempMaxMonth[$i],$from_temp);
                $ws['soilTempMaxMonthTime'][$n] = mpdate                ($soilTempMaxMonthTime[$i]);
                $ws['soilTempMaxYear'][$n]      = wsConvertTemperature  ($soilTempMaxYear[$i],$from_temp);
                $ws['soilTempMaxYearTime'][$n]  = mpdate                ($soilTempMaxYearTime[$i]); 
                $ws['soilTempMaxAll'][$n]       = wsConvertTemperature  ($soilTempMaxAlltime[$i],$from_temp);
                $ws['soilTempMaxAllTime'][$n]   = mpdate                ($soilTempMaxAlltimeTime[$i]);
 
                
                $ws['soilTempMinToday'][$n]     = wsConvertTemperature  ($soilTempMinToday[$i],$from_temp);
                $ws['soilTempMinTodayTime'][$n] = mpdate                ($soilTempMinTodayTime[$i]);
                $ws['soilTempMinYday'][$n]      = wsConvertTemperature  ($soilTempMinYday[$i],$from_temp);
                $ws['soilTempMinYdayTime'][$n]  = mpdate                ($soilTempMinYdayTime[$i]);
                $ws['soilTempMinMonth'][$n]     = wsConvertTemperature  ($soilTempMinMonth[$i],$from_temp);
                $ws['soilTempMinMonthTime'][$n] = mpdate                ($soilTempMinMonthTime[$i]);
                $ws['soilTempMinYear'][$n]      = wsConvertTemperature  ($soilTempMinYear[$i],$from_temp);
                $ws['soilTempMinYearTime'][$n]  = mpdate                ($soilTempMinYearTime[$i]);
                $ws['soilTempMinAll'][$n]       = wsConvertTemperature  ($soilTempMinAlltime[$i],$from_temp);
                $ws['soilTempMinAllTime'][$n]   = mpdate               ($soilTempMinAlltimeTime[$i]);

                $ws['moistAct'][$n]	        =                $soilMoistAct[$i]*1.0;
 
                $ws['moistMaxToday'][$n]	=                $soilMoistMaxToday[$i]*1.0;
                $ws['moistMaxTodayTime'][$n]	= mpdate        ($soilMoistMaxTodayTime[$i]);
                $ws['moistMaxYday'][$n]	        =                $soilMoistMaxYday[$i]*1.0;
                $ws['moistMaxYdayTime'][$n]	= mpdate        ($soilMoistMaxYdayTime[$i]);
                $ws['moistMaxMonth'][$n]	=                $soilMoistMaxMonth[$i]*1.0;
                $ws['moistMaxMonthTime'][$n]	= mpdate        ($soilMoistMaxMonthTime[$i]); 
                $ws['moistMaxYear'][$n]	        =                $soilMoistMaxYear[$i]*1.0;
                $ws['moistMaxYearTime'][$n]	= mpdate        ($soilMoistMaxYearTime[$i]);
                $ws['moistMaxAll'][$n]	        =                $soilMoistMaxAll[$i]*1.0;
                $ws['moistMaxAllTime'][$n]	= mpdate        ($soilMoistMaxAllTime[$i]);
                
                $ws['moistMinToday'][$n]	=                $soilMoistMinToday[$i]*1.0;
                $ws['moistMinTodayTime'][$n]	= mpdate        ($soilMoistMinTodayTime[$i]);
                $ws['moistMinYday'][$n]	        =                $soilMoistMinYday[$i]*1.0;
                $ws['moistMinYdayTime'][$n]	= mpdate        ($soilMoistMinYdayTime[$i]);
                $ws['moistMinMonth'][$n]	=                $soilMoistMinMonth[$i]*1.0;
                $ws['moistMinMonthTime'][$n]	= mpdate        ($soilMoistMinMonthTime[$i]); 
                $ws['moistMinYear'][$n]	        =                $soilMoistMinYear[$i]*1.0;
                $ws['moistMinYearTime'][$n]	= mpdate        ($soilMoistMinYearTime[$i]);
                $ws['moistMinAll'][$n]	        =                $soilMoistMinAll[$i]*1.0;
                $ws['moistMinAllTime'][$n]	= mpdate        ($soilMoistMinAllTime[$i]);
        }
} // eo dosoil
if (!file_put_contents($cachefileMP, serialize($ws))){   
        echo $startEcho.$tagsScript.": <br />Could not save (".$cachefileMP.") to cache. Please make sure your cache directory exists and is writable.".$endEcho.PHP_EOL;
} else {
        echo $startEcho.$tagsScript.": $cachefileMP saved to cache".$endEcho.PHP_EOL;
}
if (isset ($SITE['moonSet']) && $SITE['moonSet'] <> '') {
	$skipMoonPage = true; include ($SITE['moonSet']); $skipMoonPage = false;
}
$ws['check_ok']         = '3.00';
#
function mpdate   ($time){  // 2012-02-04 09:50:52
	#			2012-02-04 09:50:52
        $int 	= strtotime($time);
        return date('YmdHis',$int);
}
function mp_untranslated ($field) {
        $pos =  strpos ('  '.$field,'[');
        if ($pos > 0) {return true; } else {return false; }
}
// end of tagsMP.php
#echo '<pre>'; print_r($ws); exit;
