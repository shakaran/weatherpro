<?php 	ini_set('display_errors', 'On'); error_reporting(E_ALL);	
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'tagsWS.php';
$pageVersion	= '3.11 2015-07-21';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.11 2015-07-21 releasse 2.7 version + lunar age calculation + plus arrow correction
# --------------------------------------- version ----------------------
$tagsScript     = $pageName;
$startEcho      = '<!-- ';      
$endEcho        = ' -->';
#
$test           = false;
if ($test) {echo '<pre>Start test'.PHP_EOL;   $startEcho      = '';           $endEcho        = '';}
#
$fileToLoad     = array();
$fileToLoad[]   = $SITE['wsTags'];

if (isset ($SITE['ydayTags']) ){$fileToLoad[] = $SITE['ydayTags'];}
# echo print_r ($fileToLoad); exit;
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
#if ($test) {print_r ($wx); exit;}
$ws['tags_processed']	= $pageName.'-'.$pageVersion;
$ws['tags_originated']	= $SITE['wsTags'].'-'.  $wx['pagename'].'-'.$wx['pagenameYday'];
$ws['tags_today']	= $SITE['wsTags'].'-'.  $wx['pagename'].'-'.$wx['version'];
$ws['tags_yday']	= $SITE['ydayTags'].'-'.$wx['pagenameYday'].'-'.$wx['versionYday'];
$ws['tags_today_time']	= wswindate ($wx['date'].$wx['time']);
$ws['tags_yday_time']	= $wx['datetimeYday'];
# ----------------------------------------------------------------------
$ws['actTime']		= wswindate ($wx['date'].$wx['time']);
# ------------------------------------------ temperature ---------------
$ws['tempMinTodayTime']	= wswindate ($wx['tempMinTodayTime']);
$ws['tempMinMonthTime']	= wswindate ($wx['tempMinMonthTime']);
$ws['tempMinYearTime']	= wswindate ($wx['tempMinYearTime']);
$ws['tempMaxTodayTime']	= wswindate ($wx['tempMaxTodayTime']);
$ws['tempMaxMonthTime']	= wswindate ($wx['tempMaxMonthTime']);
$ws['tempMaxYearTime']	= wswindate ($wx['tempMaxYearTime']);
$ws['dewpMinTodayTime']	= wswindate ($wx['dewpMinTodayTime']);
$ws['dewpMaxTodayTime']	= wswindate ($wx['dewpMaxTodayTime']);
#$ws['heatMaxTodayTime']= wswindate ($wx['?']);
#$ws['heatMaxMonthTime']= wswindate ($wx['?']);
#$ws['heatMaxYearTime']	= wswindate ($wx['?']);
$ws['chilMinTodayTime']	= wswindate ($wx['chilMinTodayTime']);
$ws['chilMinMonthTime'] = wswindate ($wx['chilMinMonthTime']);
$ws['chilMinYearTime']	= wswindate ($wx['chilMinYearTime']);
$ws['chilMaxTodayTime']	= wswindate ($wx['chilMaxTodayTime']);

$string = strtoupper($wx['fromtemp']);
$pos =  strpos('  '.$string,'C');
if ($pos > 0) {$from  = 'C';} else {$from = 'F';}
$from_temp              = $from;
$ws['tempAct']		= wsConvertTemperature($wx['tempAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['tempAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['tempDelta'],$from_temp,$from_temp);
$ws['tempDelta']	= $ws['tempAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);
$ws['tempToday']	= $ws['tempAct'];
$ws['tempMinToday']	= wsConvertTemperature($wx['tempMinToday'],$from);
$ws['tempMinMonth']	= wsConvertTemperature($wx['tempMinMonth'],$from);
$ws['tempMinYear']	= wsConvertTemperature($wx['tempMinYear'],$from);
$ws['tempMaxToday']	= wsConvertTemperature($wx['tempMaxToday'],$from);
$ws['tempMaxMonth']	= wsConvertTemperature($wx['tempMaxMonth'],$from);
$ws['tempMaxYear']	= wsConvertTemperature($wx['tempMaxYear'],$from);
$ws['dewpAct']  	= wsConvertTemperature($wx['dewpAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['dewpAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['dewpDelta'],$from_temp,$from_temp);
$ws['dewpDelta']	= $ws['dewpAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);
$ws['dewpMinToday']  	= wsConvertTemperature($wx['dewpMinToday'],$from);
$ws['dewpMaxToday']  	= wsConvertTemperature($wx['dewpMaxToday'],$from);

$ws['tempActInside']	= wsConvertTemperature($wx['tempActInside'],$from);
$ws['tempActExtra1']	= wsConvertTemperature($wx['tempActExtra1'],$from);
$ws['tempActExtra2']	= wsConvertTemperature($wx['tempActExtra2'],$from);

$ws['heatAct']  	= wsConvertTemperature($wx['heatAct'],$from);
#$ws['heatDelta']	= '--';
$ws['heatMaxToday']	= '--';
$ws['heatMaxMonth']	= '--';
$ws['heatMaxYear']	= '--';

$ws['chilAct']		= wsConvertTemperature($wx['chilAct'],$from);
$temp1hourAgo		= wsConvertTemperature  ($wx['chilAct'],$from_temp,$from_temp) 
			- wsConvertTemperature  ($wx['chilDelta'],$from_temp,$from_temp);
$ws['chilDelta']	= $ws['chilAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$from_temp);
$ws['chilMinToday']	= wsConvertTemperature($wx['chilMinToday'],$from);
$ws['chilMinMonth']	= wsConvertTemperature($wx['chilMinMonth'],$from);
$ws['chilMinYear']	= wsConvertTemperature($wx['chilMinYear'],$from);
$ws['chilMaxToday']	= wsConvertTemperature($wx['chilMaxToday'],$from);

$ws['appTemp']          = wsConvertTemperature($wx['apptemp'],$from);

#$ws['hudxAct'] 	= '--';
#$ws['hudxDelta'] 	= '--';
#$ws['hudxMaxToday'] 	= '--';
#$ws['hudxMaxMonth'] 	= '--';
#$ws['hudxMaxYear'] 	= '--';
# ------------------------------------------ pressure / baro -----------
$ws['baroMinTodayTime']	= wswindate ($wx['baroMinTodayTime']);
$ws['baroMinMonthTime']	= wswindate ($wx['baroMinMonthTime']);
$ws['baroMinYearTime']	= wswindate ($wx['baroMinYearTime']);
$ws['baroMaxTodayTime']	= wswindate ($wx['baroMaxTodayTime']);
$ws['baroMaxYearTime']	= wswindate ($wx['baroMaxMonthTime']);
$ws['baroMaxMonthTime']	= wswindate ($wx['baroMaxYearTime']);

$from_baro      = $from = trim(strtolower($wx['frombaro']));    // ' hPa', of ' mb', of ' inHg'
if ($from_baro == 'mbar') {$from_baro = 'mb';}                  // wswin supports hPa   mmHg (1 mmHg = 133,322 Pa.) mbar  inHg      
                                                                // 1000	mbar (hPa)  29.53  inHg  750.1  mmHg
$ws['baroAct'] 		= wsConvertBaro($wx['baroAct'],$from);
$ws['baroDelta']	= wsConvertBaro($wx['baroDelta'],$from);
$ws['baroMinToday']	= wsConvertBaro($wx['baroMinToday'],$from);
$ws['baroMinMonth']	= wsConvertBaro($wx['baroMinMonth'],$from);
$ws['baroMinYear'] 	= wsConvertBaro($wx['baroMinYear'],$from);	
$ws['baroMaxToday']	= wsConvertBaro($wx['baroMaxToday'],$from);
$ws['baroMaxMonth']	= wsConvertBaro($wx['baroMaxMonth'],$from);
$ws['baroMaxYear'] 	= wsConvertBaro($wx['baroMaxYear'],$from);
# ------------------------------------------ humidity  -----------------
                                                                //  uom = % - wswin supports also g/m3 - not convertable
$ws['humiMinTodayTime']	= wswindate ($wx['humiMinTodayTime']);
$ws['humiMaxTodayTime'] = wswindate ($wx['humiMaxTodayTime']);
$ws['humiAct']		= $wx['humiAct']*1.0;
$ws['humiDelta']	= $wx['humiDelta']*1.0;
$ws['humiMinToday'] 	= $wx['humiMinToday']*1.0;
$ws['humiMaxToday']	= $wx['humiMaxToday']*1.0;

$ws['humiInAct']	= $wx['humiInAct']*1.0;
# ------------------------------------------ rain  ---------------------
$ws['rainDayMnth'] 	= $wx['rainDayMnth']*1.0;
$ws['rainDayYear'] 	= $wx['rainDayYear']*1.0;
$ws['rainDaysWithNo'] 	= $wx['rainDaysWithNo']*1.0;

$from_rain      = $from = trim(strtolower($wx['fromrain']));            //template 'mm',  'in'   //wswin supports l/m2  mm    ZS    inch
$pos            =  strpos('  '.$string,'l/m');
if ($pos > 0) { $from_rain = 'mm'; }                                    //  ZS unsupported ??
$ws['rainRateAct'] 	= wsConvertRainfall($wx['rainRateAct'],$from);
$ws['rainRateToday'] 	= $ws['rainRateAct'];
#$ws['rainHour']	= '0.00';
$ws['rainToday']	= wsConvertRainfall($wx['rainToday'],$from);
$ws['rainMonth']	= wsConvertRainfall($wx['rainMonth'],$from);
$ws['rainYear']		= wsConvertRainfall($wx['rainYear'],$from);
$ws['rainHour']         = wsConvertRainfall($wx['rainHour'],$from);
$ws['lastRained']       = $wx['lastRained'];
# ------------------------------------------ EVAPOTRANSPIRATION --------
$ws['etToday'] 		= wsConvertRainfall($wx['etToday'],$from);
$ws['etMonth'] 		= wsConvertRainfall($wx['etMonth'],$from);
$ws['etMonth'] 		= wsConvertRainfall($wx['etYear'],$from);
# ------------------------------------------ wind  ---------------------
$ws['gustMaxTodayTime']	= wswindate ($wx['gustMaxTodayTime']);
$ws['gustMaxMonthTime']	= wswindate ($wx['gustMaxMonthTime']);
$ws['gustMaxYearTime']	= wswindate ($wx['gustMaxYearTime']);

$string         = trim(strtolower($wx['fromwind']));                    // wswin supports:km/h  m/s   mph   Knots  Beaufort
$from_arr       = array ('knoten', 'knots', 'knopen');
$from_wind      = $from  = str_replace ($from_arr,'kts',$string);  // =' km/h', =' kts', =' m/s', =' mph'
$ws['windActDsc']	= wsConvertWinddir ($wx['windActDsc']); 
$ws['windActDir']	= $wx['windActDsc']; 
$ws['windBeafort']	= wsBeaufortNumber ($wx['windAct'],$from);
$ws['windAct']		= wsConvertWindspeed($wx['windAct'], $from);
$ws['gustAct']		= wsConvertWindspeed($wx['gustAct'], $from);
$ws['windAvgDir']       = $wx['windAvgDir'];
#$ws['gustMaxHour']	= wsConvertWindspeed($wx['xx'], $from);	
$ws['gustMaxToday']	= wsConvertWindspeed($wx['gustMaxToday'], $from);
$ws['gustMaxMonth']	= wsConvertWindspeed($wx['gustMaxMonth'], $from);
$ws['gustMaxYear']	= wsConvertWindspeed($wx['gustMaxYear'], $from);	

if ($ws['gustAct'] < $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}
# ------------------------------------------  UV   ---------------------
$ws['uvMaxTodayTime'] 	= wswindate ($wx['uvMaxTodayTime']);
$ws['uvMaxMonthTime'] 	= wswindate ($wx['uvMaxMonthTime']);
$ws['uvMaxYearTime'] 	= wswindate ($wx['uvMaxYearTime']);
$ws['uvAct']		= $wx['uvAct']*1.0;
$ws['uvMaxToday']	= $wx['uvMaxToday']*1.0;
$ws['uvMaxMonth']	= $wx['uvMaxMonth']*1.0;
$ws['uvMaxYear']	= $wx['uvMaxYear']*1.0;
# ------------------------------------------ Solar  --------------------
$ws['solarMaxTodayTime'] = wswindate ($wx['solarMaxTodayTime']);
$ws['solarMaxMonthTime']= wswindate ($wx['solarMaxMonthTime']);
$ws['solarMaxYearTime'] = wswindate ($wx['solarMaxYearTime']);
$ws['solarAct']		= $wx['solarAct']*1.0;
$ws['solarActPerc']	= $wx['solarActPerc']*1.0;
$ws['solarMaxToday']	= $wx['solarMaxToday']*1.0;
$ws['solarMaxMonth']	= $wx['solarMaxMonth']*1.0;
$ws['solarMaxYear']	= $wx['solarMaxYear']*1.0;
# ------------------------------------------ forecasts -----------------
#$ws['fcstWD'] 	  	= '';
$ws['fcstTxt']          = '';
$fct_found      = false;
$from = array ('-',' ');
if (isset ($wx['fcstTxt']) ){
        $string                 = trim (str_replace($from,'',$wx['fcstTxt']) );
        if ($string <> '') {
                $from 		= array ('hrs.', 'temp.');
                $to   		= array ('hours', 'temperature');
                $value 		= str_replace ($from, $to,trim($wx['fcstTxt']) );		// "Partly cloudy with little temperature change.",
                $ws['fcstTxt']  = trim($value); 
                $fct_found      = true;
        }
}
if (!$fct_found && isset ($wx['fcstTxt1']) ){
         $string                 = trim (str_replace($from,'',$wx['fcstTxt1']) );
         if ($string <> '') { 
                $ws['fcstTxt']  = trim($wx['fcstTxt1']);
                $fct_found      = true;
        }
}
if (!$fct_found && isset ($wx['fcstTxt2']) ){
         $string                 = trim (str_replace($from,'',$wx['fcstTxt2']) );
         if ($string <> '') { 
                $ws['fcstTxt']  = trim($wx['fcstTxt2']);
                $fct_found      = true;
        }
}
# ------------------------------------------  moon ---------------------
$ws['moonrise']		= date($SITE['timeOnlyFormat'],strtotime($wx['moonrise']));
$ws['moonset']		= date($SITE['timeOnlyFormat'],strtotime($wx['moonset']));
#$ws['lunarPhase']	= '0';
$ws['lunarPhasePerc']	= $wx['lunarPhasePerc']*1.0;

#$ws['lunarAge']		= $wx['lunarAge'];
#'23 dagen, 11 uren, 24 minuten'
list ($string) = explode (' ',trim($wx['lunarAge']) );
$ws['lunarAge']			= $string*1.0;
if ($SITE['wsDebug']) {echo '<!-- lunar age = '.$ws['lunarAge'].' calculated from '.$wx['lunarAge'].' -->'.PHP_EOL;}
# ------------------------------------------ some more -----------------
$ws['wsVersion']		= $wx['wsVersion'];
$ws['wsHardware'] 		= '';
$ws['wsUptime']			= '';
# ------------------------------------------ soil moist ----------------
#
#  WARNING      Soil tags must be processed one tag / line as otherwise errors occur in translation.
#               So a non standard soil/mois/leaf setup is used compared to the other weatherprograms.
#print_r ($ws); exit;
if ($SITE['soilUsed'] &&  $SITE['soilCount']	>=  '0') {$soils = floor($SITE['soilCount']);  $doSoil = true; } else {$doSoil = false;}

if ($doSoil) {
        $soils = round($SITE['soilCount']);
        if ($soils > 4) {echo '<!-- reset nr of soil sensors from '.$SITE['soilCount'].' to max 4 -->'.PHP_EOL;$soils  = 4;}
        for  ($n = 1; $n <= $soils; $n++) {	
                $from   = $from_temp; 
                # Temp sensor 1 actual value
                $ws['soilTempAct'][$n]		= wsConvertTemperature($wx['soilTempAct['.$n.']'],$from);
                # Temp sensor 1 maximum value for today month  year  alltime
                $ws['soilTempMaxToday'][$n]	= wsConvertTemperature($wx['soilTempMaxToday['.$n.']'],$from);
                $ws['soilTempMaxYday'][$n]	= wsConvertTemperature($wx['soilTempMaxYday_'.$n],$from);
                $ws['soilTempMaxMonth'][$n]	= wsConvertTemperature($wx['soilTempMaxMonth['.$n.']'],$from);
                $ws['soilTempMaxYear'][$n]	= wsConvertTemperature($wx['soilTempMaxYear['.$n.']'],$from);
        #       $ws['soilTempMaxAlltime'][$n]   = wsConvertTemperature($wx[''],$from);
                # Temp sensor 1 minimum values 
                $ws['soilTempMinToday'][$n]	= wsConvertTemperature($wx['soilTempMinToday['.$n.']'],$from);
                $ws['soilTempMinYday'][$n]	= wsConvertTemperature($wx['soilTempMinYday_'.$n],$from);
                $ws['soilTempMinMonth'][$n]	= wsConvertTemperature($wx['soilTempMinMonth['.$n.']'],$from);
                $ws['soilTempMinYear'][$n]	= wsConvertTemperature($wx['soilTempMinYear['.$n.']'],$from);
         #      $ws['soilTempMinAlltime'][$n]   = wsConvertTemperature($wx[''],$from);
                #
                $ws['soilTempMaxTodayTime'][$n]	= wswindate ($wx['soilTempMaxTodayTime['.$n.']']);
                $ws['soilTempMaxYdayTime'][$n]	= wswindate ($wx['soilTempMaxYdayTime_'.$n]);
                $ws['soilTempMaxMonthTime'][$n]	= wswindate ($wx['soilTempMaxMonthTime['.$n.']']);
                $ws['soilTempMaxYearTime'][$n]	= wswindate ($wx['soilTempMaxYearTime['.$n.']']);
        #       $ws['soilTempMaxAlltimeTime'][$n]= wswindate ($wx['']);
                $ws['soilTempMinTodayTime'][$n]	= wswindate ($wx['soilTempMinTodayTime['.$n.']']);
                $ws['soilTempMinYdayTime'][$n]	= wswindate ($wx['soilTempMinYdayTime_'.$n]);
                $ws['soilTempMinMonthTime'][$n]	= wswindate ($wx['soilTempMinMonthTime['.$n.']']);
                $ws['soilTempMinYearTime'][$n]	= wswindate ($wx['soilTempMinYearTime['.$n.']']);
        #       $ws['soilTempMinAlltimeTime'][$n]= wswindate ($wx['']);
                #
                # Moisture sensor 1 actual value
                $ws['moistAct'][$n]		= $wx['moistAct['.$n.']'];
                # Moisture sensor 1 max values for today month and year alltime
                $ws['moistMaxToday'][$n]	= $wx['moistMaxToday['.$n.']'];
                $ws['moistMaxYday'][$n]	        = $wx['moistMaxYday_'.$n];
                $ws['moistMaxMonth'][$n]	= $wx['moistMaxMonth['.$n.']'];
                $ws['moistMaxYear'][$n]		= $wx['moistMaxYear['.$n.']'];
        #       $ws['moistMaxAlltime'][$n]	= $wx['moistAct['.$n.']'];
                $ws['moistMaxTodayTime'][$n]	= wswindate ($wx['moistMaxTodayTime['.$n.']']);
                $ws['moistMaxYdayTime'][$n]	= wswindate ($wx['moistMaxYdayTime_'.$n]);
                $ws['moistMaxMonthTime'][$n]	= wswindate ($wx['moistMaxMonthTime['.$n.']']);
                $ws['moistMaxYearTime'][$n]	= wswindate ($wx['moistMaxYearTime['.$n.']']);
        #       $ws['moistMaxAlltimeTime'][$n]	= wswindate ($wx['soilTempMaxTodayTime['.$n.']']);
                # Moisture sensor 1 min values for today month and year alltime
                $ws['moistMinToday'][$n]	= $wx['moistMinToday['.$n.']'];
                $ws['moistMinYday'][$n]	        = $wx['moistMinYday_'.$n];
                $ws['moistMinMonth'][$n]	= $wx['moistMinMonth['.$n.']'];
                $ws['moistMinYear'][$n]		= $wx['moistMinYear['.$n.']'];
        #        $ws['moistMinAlltime'][$n]	= $wx['moistAct['.$n.']'];
                $ws['moistMinTodayTime'][$n]	= wswindate ($wx['moistMinTodayTime['.$n.']']);
                $ws['moistMinYdayTime'][$n]	= wswindate ($wx['moistMinYdayTime_'.$n]);
                $ws['moistMinMonthTime'][$n]	= wswindate ($wx['moistMinMonthTime['.$n.']']);
                $ws['moistMinYearTime'][$n]	= wswindate ($wx['moistMinYearTime['.$n.']']);
        #        $ws['moistMinAlltimeTime'][$n]	= wswindate ($wx['soilTempMaxTodayTime['.$n.']']);
        }
}
#

if ($SITE['leafUsed'] &&  $SITE['leafCount'] >=  '0') {
        $leafs = round($SITE['leafCount']);
        if ($leafs > 4) {
                echo '<!-- reset nr of leaf sensors from '.$SITE['leafCount'].' to max 4 -->'.PHP_EOL;
                $leafs  = 4;
        }
        for  ($n = 1; $n <= $leafs; $n++) {
                $ws['leafTempAct'][$n]		= $wx['leafTempAct['.$n.']'];
                $ws['leafWetAct'][$n]		= $wx['leafWetAct['.$n.']'];
                $ws['leafWetMaxToday'][$n]	= $wx['leafWetMaxToday['.$n.']'];
                $ws['leafWetMaxYday'][$n]	= $wx['leafWetMaxYday_'.$n];
                $ws['leafWetMinToday'][$n]	= $wx['leafWetMinToday['.$n.']'];
                $ws['leafWetMinYday'][$n]	= $wx['leafWetMinYday_'.$n];
                $ws['leafWetMaxTodayTime'][$n]	= $wx['leafWetMaxTodayTime['.$n.']'];
                $ws['leafWetMaxYdayTime'][$n]	= $wx['leafWetMaxYdayTime_'.$n];
                $ws['leafWetMinTodayTime'][$n]	= $wx['leafWetMinTodayTime['.$n.']'];
                $ws['leafWetMinYdayTime'][$n]	= $wx['leafWetMinYdayTime_'.$n];
        }
} // eo leaf used
#print_r ($ws); exit;
# ------------------------------------------ for trendpage -------------
$ws['tempFrom']         = $from_temp;
$ws['baroFrom']         = $from_baro;
$ws['rainFrom']         = $from_rain;
$ws['windFrom']         = $from_wind;
$ws['tempTrends']       = $wx['tempArray'];	
$ws['windTrends']       = $wx['windArray'];
$ws['gustTrends']       = $wx['gustArray'];	
$ws['wdirTrends']       = $wx['windDircArray']; // ? in dir
$ws['humiTrends']       = $wx['humArray'];	// ns
$ws['baroTrends']       = $wx['baroArray'];	
$ws['rainTrends']       = $wx['rainArray'];
$ws['uvTrends']         = $wx['uvArray'];
$ws['solarTrends']      = $wx['solarArray'];	

# ---------------------------------------     Yesterday ----------------
$from                   = $wx['fromtempYday'];
$ws['tempMinYday']      = wsConvertTemperature($wx['tempMinYday'],$from);
$ws['tempMinYdayTime']  = $wx['tempMinYdayTime'];
$ws['tempMaxYday']      = wsConvertTemperature($wx['tempMaxYday'],$from);
$ws['tempMaxYdayTime']  = $wx['tempMaxYdayTime'];
$ws['dewpMinYday']      = wsConvertTemperature($wx['dewpMinYday'],$from);
$ws['dewpMinYdayTime']  = $wx['dewpMinYdayTime'];
$ws['dewpMaxYday']      = wsConvertTemperature($wx['dewpMaxYday'],$from);
$ws['dewpMaxYdayTime']  = $wx['dewpMaxYdayTime'];
$ws['chilMinYday']      = wsConvertTemperature($wx['chilMinYday'],$from);
$ws['chilMinYdayTime']  = $wx['chilMinYdayTime'];
$from                   = $wx['frombaroYday'];
$ws['baroMinYday']      = wsConvertBaro($wx['baroMinYday'],$from);
$ws['baroMinYdayTime']  = $wx['baroMinYdayTime'];
$ws['baroMaxYday']      = wsConvertBaro($wx['baroMaxYday'],$from);
$ws['baroMaxYdayTime']  = $wx['baroMaxYdayTime'];
# hum
$ws['humiMinYday']      = $wx['humiMinYday'];
$ws['humiMinYdayTime']  = $wx['humiMinYdayTime'];
$ws['humiMaxYday']      = $wx['humiMaxYday'];
$ws['humiMaxYdayTime']  = $wx['humiMaxYdayTime'];
$from                   = $wx['fromrainYday'];
$ws['rainRateYday']     = wsConvertRainfall($wx['rainRateYday'],$from);
$ws['rainYday']         = wsConvertRainfall($wx['rainYday'],$from);
$ws['etYday']           = wsConvertRainfall($wx['etYday'],$from);
$from                   = $wx['fromwindYday'];
$ws['gustMaxYday']      = wsConvertWindspeed($wx['gustMaxYday'],$from);
$ws['gustMaxYdayTime']  = $wx['gustMaxYdayTime'];
# sun
$ws['uvMaxYday']        = $wx['uvMaxYday'];
$ws['uvMaxYdayTime']    = $wx['uvMaxYdayTime'];
$ws['solarMaxYday']     = $wx['solarMaxYday'];
$ws['solarMaxYdayTime'] = $wx['solarMaxYdayTime'];

# ---------------------
$ws['check_ok']         = '3.00';
#
if ($test) {print_r ($ws); exit;}


function wswindate ($datetime){  // for todays time stamps: remove ':' in time and combine to YYYYMMDDHHMMSS
        $from = array ('T',':');
        $string = str_replace ($from,'',$datetime);
        return substr($string.'000000',0,14);
}
// end of todaytagsScript.php