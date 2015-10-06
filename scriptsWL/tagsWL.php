<?php 	ini_set('display_errors', 'On'); error_reporting(E_ALL);	
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
$pageName	= 'tagsWL.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2015-05-12';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2015-05-12 13:00  beta releasse 2.7 version
# --------------------------------------- version ----------------------
$tagsScript     = $pageName;
$startEcho      = '<!-- ';      
$endEcho        = ' -->';
#
$test           = false;
if ($test) {echo '<pre>Start test'.PHP_EOL;$startEcho      = ''; $endEcho        = '';}
#
$fileToLoad     = array ($SITE['wsTags'], $SITE['ydayTags'] );              // normaly tags.wl.txt tagsyday.wl.txt

$uoms		= $uomTemp.$uomBaro.$uomWind.$uomRain.$uomSnow.$uomDistance.$uomPerHour.$uomHeight;
$from		= array('/',' ','&deg;','.php');
$to		= '';
#
for ($i = 0; $i < count($fileToLoad); $i++) {
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
#                if ($content  == '' ) { echo $startEcho.$tagsScript.'('.__LINE__.'):empty line '.$n.' name '.$name .$endEcho.PHP_EOL; continue;}
                $wx[$name]=$content;
        }
}
#print_r ($wx);  echo '------------------halt1'; exit;
$ws['tags_processed']	= $pageName.'-'.$pageVersion;
$ws['tags_originated']	= $SITE['wsTags'].'-'.$wx['pagename'];
$ws['tags_today']	= $ws['tags_originated'];
$ws['tags_today_time']  = $wx['date'].' '.$wx['time'];
$ws['tags_yday']	= $SITE['ydayTags'];
$ws['tags_yday_time']	= $wx['dateYday'].$wx['timeYday'];
# ----------------------------------------------------------------------
$ymd                    = substr(wl_ymd    ($wx['date']),0,8);
$ws['actTime']		= wl_time    ($wx['time']);
# ------------------------------------------ temperature ---------------
$string = strtoupper($wx['fromtemp']);
$pos =  strpos('  '.$string,'C');
if ($pos > 0) {$from  = 'C';} else {$from = 'F';}
$from_temp      = $from;

$string = strtoupper($wx['fromtempYday']);
$pos =  strpos('  '.$string,'C');
if ($pos > 0) {$fromtempYday  = 'C';} else {$fromtempYday = 'F';}

$ws['tempAct']		= wsConvertTemperature  ($wx['tempAct'],$from);
$ws['tempDelta']	= '0';
$ws['tempToday']	= $ws['tempAct'];

$ws['tempMinToday']	= wsConvertTemperature  ($wx['tempMinToday'],$from);
$ws['tempMinTodayTime']	= wl_time               ($wx['tempMinTodayTime']);
$ws['tempMinYday']	= wsConvertTemperature  ($wx['tempMinYday'],$fromtempYday);
$ws['tempMinYdayTime']	= wl_time               ($wx['tempMinYdayTime']);
$ws['tempMinMonth']	= wsConvertTemperature  ($wx['tempMinMonth'],$from);
$ws['tempMinYear']	= wsConvertTemperature  ($wx['tempMinYear'],$from);
$ws['tempMaxToday']	= wsConvertTemperature  ($wx['tempMaxToday'],$from);
$ws['tempMaxTodayTime']	= wl_time               ($wx['tempMaxTodayTime']);
$ws['tempMaxYday']	= wsConvertTemperature  ($wx['tempMaxYday'],$fromtempYday);
$ws['tempMaxYdayTime']	= wl_time               ($wx['tempMaxYdayTime']);
$ws['tempMaxMonth']	= wsConvertTemperature  ($wx['tempMaxMonth'],$from);
$ws['tempMaxYear']	= wsConvertTemperature  ($wx['tempMaxYear'],$from);

$ws['dewpAct']  	= wsConvertTemperature  ($wx['dewpAct'],$from);
$ws['dewpDelta']	= '0';

$ws['dewpMinToday']  	= wsConvertTemperature  ($wx['dewpMinToday'],$from);
$ws['dewpMinTodayTime']	= wl_time               ($wx['dewpMinTodayTime']);
$ws['dewpMinYday']  	= wsConvertTemperature  ($wx['dewpMinYday'],$fromtempYday);
$ws['dewpMinYdayTime']	= wl_time               ($wx['dewpMinYdayTime']);

$ws['dewpMaxToday']  	= wsConvertTemperature  ($wx['dewpMaxToday'],$from);
$ws['dewpMaxTodayTime']	= wl_time               ($wx['dewpMaxTodayTime']);
$ws['dewpMaxYday']  	= wsConvertTemperature  ($wx['dewpMaxYday'],$fromtempYday);
$ws['dewpMaxYdayTime']	= wl_time               ($wx['dewpMaxYdayTime']);

$ws['heatAct']  	= wsConvertTemperature  ($wx['heatAct'],$from);
$ws['heatDelta']	= '0';

$ws['heatMaxToday']	= wsConvertTemperature  ($wx['heatMaxToday'],$from);
$ws['heatMaxTodayTime'] = wl_time               ($wx['heatMaxTodayTime']);
$ws['heatMaxYday']	= wsConvertTemperature  ($wx['heatMaxYday'],$fromtempYday);
$ws['heatMaxYdayTime']  = wl_time               ($wx['heatMaxYdayTime']);
$ws['heatMaxMonth']	= wsConvertTemperature  ($wx['heatAct'],$from);
$ws['heatMaxYear']	= wsConvertTemperature  ($wx['heatAct'],$from);

$ws['chilAct']		= wsConvertTemperature  ($wx['chilAct'],$from);
$ws['chilDelta']	= '0';

$ws['chilMinToday']	= wsConvertTemperature  ($wx['chilMinToday'],$from);
$ws['chilMinTodayTime']	= wl_time               ($wx['chilMinTodayTime']);
$ws['chilMinYday']	= wsConvertTemperature  ($wx['chilMinYday'],$fromtempYday);
$ws['chilMinYdayTime']	= wl_time               ($wx['chilMinYdayTime']);
$ws['chilMinMonth']	= wsConvertTemperature  ($wx['chilMinMonth'],$from);
$ws['chilMinYear']	= wsConvertTemperature  ($wx['chilMinYear'],$from);


$ws['tempActInside']	= wsConvertTemperature  ($wx['tempActInside'],$from);
$ws['tempActExtra1']	= wsConvertTemperature  ($wx['tempActExtra1'],$from);	
$ws['tempActExtra2']	= wsConvertTemperature  ($wx['tempActExtra2'],$from);	
# ------------------------------------------ pressure / baro -----------
$from                   = trim(strtolower($wx['frombaro']));     // ' hPa', of ' mb', of ' inHg'
$frombaroYday           = trim(strtolower($wx['frombaroYday']));

$ws['baroAct'] 		= wsConvertBaro ($wx['baroAct'],$from);
$ws['baroDelta']        = '0';
$ws['baroTrend']	=                $wx['baroTrend'];

$ws['baroMinToday']	= wsConvertBaro ($wx['baroMinToday'],$from);
$ws['baroMinTodayTime']	= wl_time       ($wx['baroMinTodayTime']);
$ws['baroMinYday']	= wsConvertBaro ($wx['baroMinYday'],$frombaroYday);
$ws['baroMinYdayTime']	= wl_time       ($wx['baroMinYdayTime']);
$ws['baroMinMonth']	= wsConvertBaro ($wx['baroMinMonth'],$from);
$ws['baroMinYear'] 	= wsConvertBaro ($wx['baroMinYear'],$from);	

$ws['baroMaxToday']	= wsConvertBaro ($wx['baroMaxToday'],$from);
$ws['baroMaxTodayTime']	= wl_time       ($wx['baroMaxTodayTime']);
$ws['baroMaxYday']	= wsConvertBaro ($wx['baroMaxYday'],$frombaroYday);
$ws['baroMaxYdayTime']	= wl_time       ($wx['baroMaxYdayTime']);
$ws['baroMaxMonth']	= wsConvertBaro ($wx['baroMaxMonth'],$from);
$ws['baroMaxYear'] 	= wsConvertBaro ($wx['baroMaxYear'],$from);
# ------------------------------------------ humidity  -----------------
$ws['humiAct']		=                $wx['humiAct']*1.0;
$ws['humiDelta']	= '0';

$ws['humiMinToday'] 	=                $wx['humiMinToday']*1.0;
$ws['humiMinTodayTime']	= wl_time       ($wx['humiMinTodayTime']);
$ws['humiMinYday'] 	=                $wx['humiMinYday']*1.0;
$ws['humiMinYdayTime']	= wl_time       ($wx['humiMinYdayTime']);

$ws['humiMaxToday']	=               $wx['humiMaxToday']*1.0;
$ws['humiMaxTodayTime'] = wl_time       ($wx['humiMaxTodayTime']);
$ws['humiMaxYday']	=                $wx['humiMaxYday']*1.0;
$ws['humiMaxYdayTime']	= wl_time       ($wx['humiMaxYdayTime']);

$ws['humiInAct']	=                $wx['humiInAct']*1.0;
$ws['humiActExtra1']	=                $wx['humiActExtra1']*1.0;
$ws['humiActExtra2']	=                $wx['humiActExtra2']*1.0;
# ------------------------------------------ rain  ---------------------
$from                   = trim(strtolower($wx['fromrain']));     // 'mm',  'in'
$fromrainYday           = trim(strtolower($wx['fromrainYday'])); 


$ws['rainRateAct'] 	= wsConvertRainfall     ($wx['rainRateAct'],$from);
$ws['rainRateToday'] 	= wsConvertRainfall     ($wx['rainRateMaxToday'],$from);

$ws['rainToday']	= wsConvertRainfall     ($wx['rainToday'],$from);
$ws['rainYday']	        = wsConvertRainfall     ($wx['rainYday'],$fromrainYday);
$ws['rainMonth']	= wsConvertRainfall     ($wx['rainMonth'],$from);
$ws['rainYear']		= wsConvertRainfall     ($wx['rainYear'],$from);
# ------------------------------------------ EVAPOTRANSPIRATION --------
$ws['etToday'] 		= wsConvertRainfall     ($wx['etToday'],$from);
$ws['etYday'] 		= wsConvertRainfall     ($wx['etYday'],$fromrainYday);
$ws['etMonth'] 		= wsConvertRainfall     ($wx['etMonth'],$from);
$ws['etYear'] 		= wsConvertRainfall     ($wx['etYear'],$from);
# ------------------------------------------ wind  ---------------------
$string                 = trim(strtolower       ($wx['fromwind']));    
$from                   = str_replace ('hr','h',$string);

$string                 = trim(strtolower       ($wx['fromwindYday']));    
$fromwindYday           = str_replace ('hr','h',$string);

$ws['windAct']		= wsConvertWindspeed    ($wx['windAct'], $from);
$ws['gustAct']		= wsConvertWindspeed    ($wx['gustAct'], $from);
$ws['windActDsc']	= wsConvertWinddir      ($wx['windActDsc']); 
$ws['windActDir']	=                        $wx['windActDir']; 
$ws['windBeafort']	= wsBeaufortNumber      ($wx['windAct'],$from);

$ws['gustMaxToday']	        = wsConvertWindspeed    ($wx['gustMaxToday'], $from);
$ws['gustMaxTodayTime']		= wl_time               ($wx['gustMaxTodayTime']);
$ws['gustMaxYday']	        = wsConvertWindspeed    ($wx['gustMaxYday'], $fromwindYday);
$ws['gustMaxYdayTime']		= wl_time               ($wx['gustMaxYTime']);
$ws['gustMaxMonth']	        = wsConvertWindspeed    ($wx['gustMaxMonth'], $from);
$ws['gustMaxYear']	        = wsConvertWindspeed    ($wx['gustMaxYear'], $from);	

if ($ws['gustAct'] < $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}
# ------------------------------------------  UV   ---------------------
$ws['uvAct']			=                        $wx['uvAct']*1.0;
$ws['uvMaxToday']		=                        $wx['uvMaxToday']*1.0;
$ws['uvMaxTodayTime'] 		= wl_time               ($wx['uvMaxTodayTime']);
$ws['uvMaxYday']		=                        $wx['uvMaxYday']*1.0;
$ws['uvMaxTodayTime'] 		= wl_time               ($wx['uvMaxYdayTime']);
$ws['uvMaxMonth']		=                        $wx['uvMaxMonth']*1.0;
$ws['uvMaxYear']		=                        $wx['uvMaxYear']*1.0;
# ------------------------------------------ Solar  --------------------
$ws['solarAct']			=                        $wx['solarAct']*1.0;
#$ws['solarActPerc']		=                        $wx['solarActPerc']*1.0;

$ws['solarMaxToday']		=                        $wx['solarMaxToday']*1.0;
$ws['solarMaxTodayTime'] 	= wl_time               ($wx['solarMaxTodayTime']);
$ws['solarMaxYday']		=                        $wx['solarMaxYday']*1.0;
$ws['solarMaxYdayTime'] 	= wl_time               ($wx['solarMaxYdayTime']);
$ws['solarMaxMonth']		=                        $wx['solarMaxMonth']*1.0;
$ws['solarMaxYear']		=                        $wx['solarMaxYear']*1.0;
#------------------------------------------cloudheight------------------
# ------------------------------------------ forecasts -----------------
$ws['fcstTxt']                  = trim($wx['fcstTxt']);
# ------------------------------------------  moon ---------------------
$skipMoonPage = true; include ($SITE['moonSet']); $skipMoonPage = false;
# ------------------------------------------ some more -----------------
$ws['wsVersion']		= $wx['wsVersion'];
$ws['wsHardware'] 		= $wx['wsHardware'];
$ws['wsUptime']		        = $wx['wsUptime'];
#--------------------------------------------soil moisture -------------
if ($SITE['soilUsed'] &&  $SITE['soilCount']	>=  '0') {$soils = floor($SITE['soilCount']);  $doSoil = true; } else {$doSoil = false;}
if ($doSoil) {
        if ($soils > 4) {echo $startEcho.' reset nr of soil sensors from '.$SITE['soilCount'].' to max 4 '.$endEcho.PHP_EOL; $soils  = 4;}
        $soilTempAct            = explode ('#',$wx['soilTempAct']);
        $soilTempMaxToday       = explode ('#',$wx['soilTempMaxToday']);
        $soilTempMaxTodayTime   = explode ('#',$wx['soilTempMaxTodayTime']);
        $soilTempMaxYday        = explode ('#',$wx['soilTempMaxYday']);
        $soilTempMaxYdayTime    = explode ('#',$wx['soilTempMaxYdayTime']);

        $soilTempMinToday       = explode ('#',$wx['soilTempMinToday']);
        $soilTempMinTodayTime   = explode ('#',$wx['soilTempMinTodayTime']);
        $soilTempMinYday        = explode ('#',$wx['soilTempMinYday']);
        $soilTempMinYdayTime    = explode ('#',$wx['soilTempMinYdayTime']);

        $soilMoistAct           = explode ('#',$wx['soilMoistAct']);
        $soilMoistMaxToday      = explode ('#',$wx['soilMoistMaxToday']);
        $soilMoistMaxTodayTime  = explode ('#',$wx['soilMoistMaxTodayTime']);  
        $soilMoistMaxYday       = explode ('#',$wx['soilMoistMaxYday']);
        $soilMoistMaxYdayTime   = explode ('#',$wx['soilMoistMaxYdayTime']);  

        $soilMoistMinToday      = explode ('#',$wx['soilMoistMinToday']);
        $soilMoistMinTodayTime  = explode ('#',$wx['soilMoistMinTodayTime']); 
        $soilMoistMinYday       = explode ('#',$wx['soilMoistMinYday']);
        $soilMoistMinYdayTime   = explode ('#',$wx['soilMoistMinYdayTime']); 

        
        for  ($n = 1; $n <= $soils; $n++) {
                $i                              = $n - 1;	
                $ws['soilTempAct'][$n]          = wsConvertTemperature  ($soilTempAct[$i],$from_temp);
                $ws['soilTempMaxToday'][$n]     = wsConvertTemperature  ($soilTempMaxToday[$i],$from_temp);
                $ws['soilTempMaxTodayTime'][$n] = wl_time               ($soilTempMaxTodayTime[$i]);
                $ws['soilTempMaxYday'][$n]      = wsConvertTemperature  ($soilTempMaxYday[$i],$fromtempYday);
                $ws['soilTempMaxYdayTime'][$n]  = wl_time               ($soilTempMaxYdayTime[$i]);

                $ws['soilTempMinToday'][$n]     = wsConvertTemperature  ($soilTempMinToday[$i],$from_temp);
                $ws['soilTempMinTodayTime'][$n] = wl_time               ($soilTempMinTodayTime[$i]);
                $ws['soilTempMinYday'][$n]      = wsConvertTemperature  ($soilTempMinYday[$i],$fromtempYday);
                $ws['soilTempMinYdayTime'][$n]  = wl_time               ($soilTempMinYdayTime[$i]);

                $ws['moistAct'][$n]	        =                 $soilMoistAct[$i]*1.0;
                $ws['moistMaxToday'][$n]	=                 $soilMoistMaxToday[$i]*1.0;
                $ws['moistMaxTodayTime'][$n]	= wl_time        ($soilMoistMaxTodayTime[$i]);
                $ws['moistMaxYday'][$n]	        =                $soilMoistMaxYday[$i]*1.0;
                $ws['moistMaxYdayTime'][$n]	= wl_time        ($soilMoistMaxYdayTime[$i]);

                $ws['moistMinToday'][$n]	=                 $soilMoistMinToday[$i]*1.0;
                $ws['moistMinTodayTime'][$n]	= wl_time        ($soilMoistMinTodayTime[$i]);
                $ws['moistMinYday'][$n]	        =                 $soilMoistMinYday[$i]*1.0;
                $ws['moistMinYdayTime'][$n]	= wl_time        ($soilMoistMinYdayTime[$i]);
        }
} // eo dosoil   
    
if ($SITE['leafUsed'] &&  $SITE['leafCount']	>=  '0') {$doleaf = true; $leafs = floor($SITE['leafCount']); } else {$doleaf = false; $leafs = 0;}
if ($doleaf) {
        if ($leafs > 2) { echo $startEcho.' reset nr of leaf sensors from '.$SITE['leafCount'].' to max 2 '.$endEcho.PHP_EOL;$leafs  = 2;}
        $arr_leaf_temp                  = explode ('#',$wx['leafTempAct']);
        $arr_leaf_wet                   = explode ('#',$wx['leafWetAct']);
        if ($leafs > count($arr_leaf_wet) ) {$leafs = count($arr_leaf_wet);}
        for  ($n = 1; $n <= $leafs; $n++) {
                $i                      = $n - 1;
                $ws['leafActTemp'][$n]	= $arr_leaf_temp[$i]*1.0;
                $ws['leafActWet'][$n]	= $arr_leaf_wet[$i]*1.0;
        }
} // eo doleaf
$ws['check_ok']         = '3.00';

if ($test) {echo '<pre>'; print_r($ws); exit;}

#
function wl_ymd    ($date){  
        global $SITE, $ymd, $ws;
        list ($year_nr, $month_nr, $day_nr) = $SITE['tags_ymd'];
#        print_r ($SITE['tags_ymd']); echo '<br />$year_nr = '.$year_nr; echo exit;
        $arr    = explode ($SITE['tags_ymd_sep'],$SITE['tags_ymd_sep'].$date);
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
                $string = 'switch date seperator from '.$SITE['tags_ymd_sep'].' to '.$alt_char.' please adjust your settings ';
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
#        $year   = (int) substr($date,6,4);
        if ($year < 99 && $year > 69) {$year = '19'.$year;} else {$year = '20'.$year;}
        $string = $year.$month.$day;
        $int    = strtotime($string.'T000000');
        $return = date ('Ymd',$int).'000000';
#       echo $date.' | '.$string.' | '.$return; exit;
        return $return;
}
function wl_time    ($time){  // for todays time stamps: remove ':' in time and combine to YYYYMMDDHHMMSS
        global $ymd;            // 13:30   03:30 
        if (trim($time) == '?') {return $ymd.'000000';}
        $result = str_replace ('-','',$time);
        if (trim($result) == ''){return $ymd.'000000';}
        $int = strtotime($time);
        return ($ymd.strftime('%H%M%S',$int) );
}
// end of tagsWL.php
?>