<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
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
$pageName	= 'trends.php';
$pageVersion	= '3.20 2015-09-18';
#-------------------------------------------------------------------------------
# 3.20 2015-09-18 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#
if (isset ($ws['trendsExist']) ) {$wsTrends = unserialize(file_get_contents($ws['trendsExist']));}

$tempLow	= preg_match('|F|i',$uomTemp)?'32':'0';
$tempXLow	= preg_match('|F|i',$uomTemp)?'5':'-15';
$tempHigh	= preg_match('|F|i',$uomTemp)?'77':'25';
$tempXHigh	= preg_match('|F|i',$uomTemp)?'86':'30';

$labelRainWeek	= langtransstr('this current week');
$timeOnlyFormat	= $SITE['timeOnlyFormat'];
$dateOnlyFormat	= $SITE['dateOnlyFormat'];
$haveUV		= $SITE['UV'];
$haveSolar	= $SITE['SOLAR'];
$graphImageDir	= $SITE['graphImageDir'];
if ($SITE['commaDecimal']) {$decimal = ',';} else {$decimal = '.';}
#
$wp     = $SITE['WXsoftware'];
#
# Meteohub has very special arrays for trends
if ($wp == 'MH') {
	$uomTempSeq	= $ws['tempFrom'];
	$uomBaroSeq	= $ws['baroFrom'];
	$uomWindSeq	= $ws['windFrom'];
	$uomRainSeq	= $ws['rainFrom'];
# trendwaardes tot een uur vanuit de minuten tabel -------------------------------------
	ws_message (  '<!-- module trends.php ('.__LINE__.'): Meteohub convert seqmin1 minute array');
	$arr_temp       = wsConvertArray('temp',explode(" ", $ws['seqmin1_temp']),$uomTempSeq,$uomTemp);
	$arr_wind       = wsConvertArray('wind',explode(" ", $ws['seqmin1_windspeed']),$uomWindSeq,$uomWind);
	$arr_gust       = wsConvertArray('wind',explode(" ", $ws['seqmin1_gustspeed']),$uomWindSeq,$uomWind);

	if ($haveSolar) {$arr_solar	= explode(" ", $ws['seqmin1_solar']);}
	if ($haveUV) 	{$arr_uv	= explode(" ", $ws['seqmin1_UV']);}

	$arr_windDir    =explode(" ", $ws['seqmin1_windmaindir']);
	for ($i=0; $i < count($arr_windDir); $i++) {
		$arr_windDir[$i]        =langtransstr(wsConvertWinddir($arr_windDir[$i]));
	}
	
	$arr_hum        = explode(" ", $ws['seqmin1_hum']);
	$arr_baro       = wsConvertArray('baro',explode(" ", $ws['seqmin1_press']),$uomBaroSeq,$uomBaro);
	$arr_rain       = wsConvertArray('rain',explode(" ", $ws['seqmin1_raintotal']),$uomRainSeq,$uomRain);
# waardes vanaf 1 uur oud uit 15 minuten tabel  ----------------------------------------
	ws_message (  '<!-- module trends.php ('.__LINE__.'): Meteohub convert seqmin15 minutes array');
	$arr_temp2      = wsConvertArray('temp',explode(" ", $ws['seqmin15_temp']),$uomTempSeq,$uomTemp);
	$arr_wind2      = wsConvertArray('wind',explode(" ", $ws['seqmin15_windspeed']),$uomWindSeq,$uomWind);
	$arr_gust2      = wsConvertArray('wind',explode(" ", $ws['seqmin15_gustspeed']),$uomWindSeq,$uomWind);

	if ($haveSolar) {$arr_solar2	= explode(" ", $ws['seqmin15_solar']);}
	if ($haveUV) 	{$arr_uv2	= explode(" ", $ws['seqmin15_UV']);}

	$arr_windDir2   = explode(" ", $ws['seqmin15_windmaindir']);
	for ($i=0; $i < count($arr_windDir2); $i++) {
		$arr_windDir2[$i]       = wsConvertWinddir($arr_windDir2[$i]);
	}
	$arr_hum2       = explode(" ", $ws['seqmin15_hum']);
	$arr_baro2      = wsConvertArray('baro',explode(" ", $ws['seqmin15_press']),$uomBaroSeq,$uomBaro);
	$arr_rain2      = wsConvertArray('rain',explode(" ", $ws['seqmin15_raintotal']),$uomRainSeq,$uomRain);
#
# calculate raindays | rain last 7 days ------------------------------------------------
	$arrRain        = wsConvertArray('rain',explode(" ", $ws['seqday1_rain_total']),$uomRainSeq,$uomRain);
	$noRain         = langtransstr('more than 30');
	for ($i=0; $i <= count($arrRain); $i++) {
		if (1.0*$arrRain[$i] <> 0){$ws['rainDaysWithNo'] = $i; break;}
	}
	$ws['rainWeek'] = $arrRain[0]+$arrRain[1]+$arrRain[2]+$arrRain[3]+$arrRain[4]+$arrRain[5]+$arrRain[6];
	$labelRainWeek	= langtransstr('over last 7 days');
#
# calculate rain this+last  month ------------------------------------------------------
	$arrRain        = explode(" ", $ws['seqmonth1_rain_total']);
	$raintodatemonthago= 1.0*wsConvertRainfall($arrRain[0],$uomRainSeq,$uomRain);
# calculate  extreme temps -------------------------------------------------------------
	$arrMin         = explode(" ", $ws['seqday1_tempMin_total']);
	$arrMax         = explode(" ", $ws['seqday1_tempMax_total']);
	$minTemp	= wsConvertTemperature($tempLow,$uomTemp,$uomTempSeq);
	$minXTemp	= wsConvertTemperature($tempXLow,$uomTemp,$uomTempSeq);
	$maxTemp	= wsConvertTemperature($tempHigh,$uomTemp,$uomTempSeq);
	$maxXTemp	= wsConvertTemperature($tempXHigh,$uomTemp,$uomTempSeq);
	$ws['daysLow']  = $ws['daysXLow'] = $ws['daysHigh'] = $ws['daysXHigh']=0;
	$cntArr         = count($arrMin);
	$d              = 1.0*string_date($ws['actTime'], 'j') - 1;  // number of days in this month with values
#echo '<pre>temphigh = '.$maxTemp.' - count = '.$d.PHP_EOL; print_r($arrMax); exit;
	if ($d <= $cntArr) {$cntArr = $d;}
	for ($i=0; $i < $cntArr; $i++) {
		if (1.0*$arrMin[$i] <=  $minTemp)  {$ws['daysLow']++;}
		if (1.0*$arrMin[$i] <=  $minXTemp) {$ws['daysXLow']++;}	
		if (1.0*$arrMax[$i] >=  $maxTemp)  {$ws['daysHigh']++;}
		if (1.0*$arrMax[$i] >=  $maxXTemp) {$ws['daysXHigh']++;}	
	}
# calculate  rain last two hours -------------------------------------------------------
	$countRain      = 0;
        for ($n=0;$n <= 59; $n++) {     // first hour minute array
                if (isset ($arr_rain[$n]) ) {
                        $countRain = $countRain + 1.0*$arr_rain[$n]; 
                }
        }
        for ($n=4;$n <= 7; $n++) {      // second hour quarter array
                if (isset ($arr_rain2[$n]) ) {       
                        $countRain = $countRain + 1.0*$arr_rain2[$n]; 
                }
        }
        $meteohub_rain  = $countRain;
# fill the trend array with values -----------------------------------------------------
	$i = 0;
	$wsTrends[$i] ['min']	= 0;
	$i=1;
	$wsTrends[$i] ['min']	= 5;
	$wsTrends[$i] ['temp']	= $arr_temp[4];
	$wsTrends[$i] ['wind']	= $arr_wind[4];
	$wsTrends[$i] ['gust']	= $arr_gust[4];
	$wsTrends[$i] ['dir']	= $arr_windDir[4];
	$wsTrends[$i] ['hum']	= round($arr_hum[4]);
	$wsTrends[$i] ['dew']	= '%dew5minuteago%';
	$wsTrends[$i] ['baro']	= $arr_baro[4];
	for ($n=0;$n <= 4; $n++) {$countRain = $countRain - 1.0*$arr_rain[$n]; } // count all rain in this 5 min period and subtract from total rain
        $wsTrends[$i] ['rain']	= $countRain;
	if ($haveSolar) {$wsTrends[$i] ['sol']	= $arr_solar[4];}
	if ($haveUV) 	{$wsTrends[$i] ['uv']	= $arr_uv[4];}
	$i=2;
	$wsTrends[$i] ['min']	= 10;
	$wsTrends[$i] ['temp']	= $arr_temp[9];
	$wsTrends[$i] ['wind']	= $arr_wind[9];
	$wsTrends[$i] ['gust']	= $arr_gust[9];
	$wsTrends[$i] ['dir']	= $arr_windDir[9];
	$wsTrends[$i] ['hum']	= round($arr_hum[9]);
	$wsTrends[$i] ['dew']	= '%dew10minuteago%';
	$wsTrends[$i] ['baro']	= $arr_baro[9];
	for ($n=5;$n <= 9; $n++) {$countRain = $countRain - 1.0*$arr_rain[$n]; }
	$wsTrends[$i] ['rain']	= $countRain;
	if ($haveSolar) {$wsTrends[$i] ['sol']	= $arr_solar[9];}
	if ($haveUV) 	{$wsTrends[$i] ['uv']	= $arr_uv[9];}
	$i=3;
	$wsTrends[$i] ['min']	= 15;
	$wsTrends[$i] ['temp']	= $arr_temp[14];
	$wsTrends[$i] ['wind']	= $arr_wind[14];
	$wsTrends[$i] ['gust']	= $arr_gust[14];
	$wsTrends[$i] ['dir']	= $arr_windDir[14];
	$wsTrends[$i] ['hum']	= round($arr_hum[14]);
	$wsTrends[$i] ['dew']	= '%dew15minuteago%';
	$wsTrends[$i] ['baro']	= $arr_baro[14];
	for ($n=10;$n <= 14; $n++) {$countRain = $countRain - 1.0*$arr_rain[$n]; }
	$wsTrends[$i] ['rain']	= $countRain;
	if ($haveSolar) {$wsTrends[$i] ['sol']	= $arr_solar[14];}
	if ($haveUV) 	{$wsTrends[$i] ['uv']	= $arr_uv[14];}
	$i=4;
	$wsTrends[$i] ['min']	= 20;
	$wsTrends[$i] ['temp']	= $arr_temp[19];
	$wsTrends[$i] ['wind']	= $arr_wind[19];
	$wsTrends[$i] ['gust']	= $arr_gust[19];
	$wsTrends[$i] ['dir']	= $arr_windDir[19];
	$wsTrends[$i] ['hum']	= round($arr_hum[19]);
	$wsTrends[$i] ['dew']	= '%dew20minuteago%';
	$wsTrends[$i] ['baro']	= $arr_baro[19];
	for ($n=15; $n <= 19; $n++) {$countRain = $countRain - 1.0*$arr_rain[$n]; }
	$wsTrends[$i] ['rain']	= $countRain;
	if ($haveSolar) {$wsTrends[$i] ['sol']	= $arr_solar[19];}
	if ($haveUV) 	{$wsTrends[$i] ['uv']	= $arr_uv[19];}
	$i=5;
	$wsTrends[$i] ['min']	= 30;
	$wsTrends[$i] ['temp']	= $arr_temp[29];
	$wsTrends[$i] ['wind']	= $arr_wind[29];
	$wsTrends[$i] ['gust']	= $arr_gust[29];
	$wsTrends[$i] ['dir']	= $arr_windDir[29];
	$wsTrends[$i] ['hum']	= round($arr_hum[29]);
	$wsTrends[$i] ['dew']	= '%dew30minuteago%';
	$wsTrends[$i] ['baro']	= $arr_baro[29];
	for ($n=20;$n <= 29; $n++) {$countRain = $countRain - 1.0*$arr_rain[$n]; }
	$wsTrends[$i] ['rain']	= $countRain;
	if ($haveSolar) {$wsTrends[$i] ['sol']	= $arr_solar[29];}
	if ($haveUV) 	{$wsTrends[$i] ['uv']	= $arr_uv[29];}
	$i=6;
	$wsTrends[$i] ['min']	= 45;
	$wsTrends[$i] ['temp']	= $arr_temp[44];
	$wsTrends[$i] ['wind']	= $arr_wind[44];
	$wsTrends[$i] ['gust']	= $arr_gust[44];
	$wsTrends[$i] ['dir']	= $arr_windDir[44];
	$wsTrends[$i] ['hum']	= round($arr_hum[44]);
	$wsTrends[$i] ['dew']	= '%dew45minuteago%';
	$wsTrends[$i] ['baro']	= $arr_baro[44];
	for ($n=30;$n <= 44; $n++) {$countRain = $countRain - 1.0*$arr_rain[$n]; }
	$wsTrends[$i] ['rain']	= $countRain;
	if ($haveSolar) {$wsTrends[$i] ['sol']	= $arr_solar[44];}
	if ($haveUV) 	{$wsTrends[$i] ['uv']	= $arr_uv[44];}
	$i=7;
	$wsTrends[$i] ['min']	= 60;
	$wsTrends[$i] ['temp']	= $arr_temp2[3];
	$wsTrends[$i] ['wind']	= $arr_wind2[3];
	$wsTrends[$i] ['gust']	= $arr_gust2[3];
	$wsTrends[$i] ['dir']	= $arr_windDir2[3];
	$wsTrends[$i] ['hum']	= round($arr_hum2[3]);
	$wsTrends[$i] ['dew']	= '%dew60minuteago%';
	$wsTrends[$i] ['baro']	= $arr_baro2[3];
	for ($n=45;$n <= 58; $n++) {if (isset ($arr_rain[$n]) ) { $countRain = $countRain - 1.0*$arr_rain[$n]; }}
	$wsTrends[$i] ['rain']	= $countRain;
	if ($haveSolar) {$wsTrends[$i] ['sol']	= $arr_solar2[3];}
	if ($haveUV) 	{$wsTrends[$i] ['uv']	= $arr_uv2[3];}
	$i=8;
	$wsTrends[$i] ['min']	= 75;
	$wsTrends[$i] ['temp']	= $arr_temp2[4];
	$wsTrends[$i] ['wind']	= $arr_wind2[4];
	$wsTrends[$i] ['gust']	= $arr_gust2[4];
	$wsTrends[$i] ['dir']	= $arr_windDir2[4];
	$wsTrends[$i] ['hum']	= round($arr_hum2[4]);
	$wsTrends[$i] ['dew']	= '%dew75minuteago%';
	$wsTrends[$i] ['baro']	= $arr_baro2[4];
	$countRain = $countRain - 1.0*$arr_rain2[4];
	$wsTrends[$i] ['rain']	= $countRain;
	if ($haveSolar) {$wsTrends[$i] ['sol']	= $arr_solar2[4];}
	if ($haveUV) 	{$wsTrends[$i] ['uv']	= $arr_uv2[4];}
	$i=9;
	$wsTrends[$i] ['min']	= 90;
	$wsTrends[$i] ['temp']	= $arr_temp2[5];
	$wsTrends[$i] ['wind']	= $arr_wind2[5];
	$wsTrends[$i] ['gust']	= $arr_gust2[5];
	$wsTrends[$i] ['dir']	= $arr_windDir2[5];
	$wsTrends[$i] ['hum']	= round($arr_hum2[5]);
	$wsTrends[$i] ['dew']	= '%dew90minuteago%';
	$wsTrends[$i] ['baro']	= $arr_baro2[5];
	$countRain = $countRain - 1.0*$arr_rain2[5];
	$wsTrends[$i] ['rain']	= $countRain;
	if ($haveSolar) {$wsTrends[$i] ['sol']	= $arr_solar2[5];}
	if ($haveUV) 	{$wsTrends[$i] ['uv']	= $arr_uv2[5];}
	$i=10;
	$wsTrends[$i] ['min']	= 105;
	$wsTrends[$i] ['temp']	= $arr_temp2[6];
	$wsTrends[$i] ['wind']	= $arr_wind2[6];
	$wsTrends[$i] ['gust']	= $arr_gust2[6];
	$wsTrends[$i] ['dir']	= $arr_windDir2[6];
	$wsTrends[$i] ['hum']	= round($arr_hum2[6]);
	$wsTrends[$i] ['dew']	= '%dew105minuteago%';
	$wsTrends[$i] ['baro']	= $arr_baro2[6];
	$countRain = $countRain - 1.0*$arr_rain2[6];
	$wsTrends[$i] ['rain']	= $countRain;
	if ($haveSolar) {$wsTrends[$i] ['sol']	= $arr_solar2[6];}
	if ($haveUV) 	{$wsTrends[$i] ['uv']	= $arr_uv2[6];}
	$i=11;
	$wsTrends[$i] ['min']	= 120;
	$wsTrends[$i] ['temp']	= $arr_temp2[7];
	$wsTrends[$i] ['wind']	= $arr_wind2[7];
	$wsTrends[$i] ['gust']	= $arr_gust2[7];
	$wsTrends[$i] ['dir']	= $arr_windDir2[7];
	$wsTrends[$i] ['hum']	= round($arr_hum2[7]);
	$wsTrends[$i] ['dew']	= '%dew120minuteago%';
	$wsTrends[$i] ['baro']	= $arr_baro2[7];
	$countRain = $countRain - 1.0*$arr_rain2[7];
	$wsTrends[$i] ['rain']	= $countRain;
	if ($haveSolar) {$wsTrends[$i] ['sol']	= $arr_solar2[7];}
	if ($haveUV) 	{$wsTrends[$i] ['uv']	= $arr_uv2[7];}
	$graphWind		= $graphImageDir.'windrose.png';
}
# WSWIN has far to large arrays so we make them smaller
if ($wp == 'WS') {
	ws_message (  '<!-- module trends.php ('.__LINE__.'): wswin compress arrays ');
        $from_temp      = $ws['tempFrom'];
        $from_baro      = $ws['baroFrom'];
        $from_rain      = $ws['rainFrom'];
        $from_wind      = $ws['windFrom'];
        $min_array      = array (0,5,10,15,20,30,45,60,75,90,105,120);
        $trends_count   = count($min_array);
        $loc_array      = array(); 
        for ($n = 0; $n < $trends_count; $n++) { $loc_array[$n]   = round($min_array[$n] / 5);}  
	$arr_temp       = explode(' ',$ws['tempTrends']);
	$arr_wind       = explode(' ',$ws['windTrends']);
	$arr_gust       = explode(' ',$ws['gustTrends']);
	$arr_wind_dir   = explode(' ',$ws['wdirTrends']);
	$arr_hum        = explode(' ',$ws['humiTrends']);
	$arr_baro       = explode(' ',$ws['baroTrends']);
	$arr_rain       = explode(' ',$ws['rainTrends']);
	$arr_uv         = explode(' ',$ws['uvTrends']);
	$arr_solar      = explode(' ',$ws['solarTrends']);
        $temps_count    = count($arr_temp);
        for ($n = 0; $n < $trends_count; $n++) {
                $wsTrends[$n] ['min']	= $min_array[$n];
                $pointer                = $temps_count - $loc_array[$n] - 1;
                $wsTrends[$n] ['temp']	= wsConvertTemperature($arr_temp[$pointer],$from_temp);
                $wsTrends[$n] ['wind']  = wsConvertWindspeed($arr_wind[$pointer], $from_wind);
                $wsTrends[$n] ['gust']  = wsConvertWindspeed($arr_gust[$pointer], $from_wind);
                $wsTrends[$n] ['dir']   = wsConvertWinddir ($arr_wind_dir[$pointer]);
                $wsTrends[$n] ['hum']	= $arr_hum[$pointer]*1.0;
                $wsTrends[$n] ['baro']	= wsConvertBaro($arr_baro[$pointer],$from_baro);
                $wsTrends[$n] ['rain']	= wsConvertRainfall($arr_rain[$pointer],$from_rain);
                if ($haveUV)    {$wsTrends[$n] ['uv']	= $arr_uv[$pointer]*1.0; }      
                if ($haveSolar) {$wsTrends[$n] ['sol']	= $arr_solar[$pointer]*1.0;} 
        }
# echo '<pre>'; print_r($wsTrends); exit;
 }
# generic settings for CW and WD
if ($wp == 'WD' || $wp == 'CW') {
	$labelRainWeek	        = langtransstr('this current week');
	$tempLow		= preg_match('|F|i',$uomTemp)?'32':'0';
	$tempXLow		= preg_match('|F|i',$uomTemp)?'5':'-15';
	$tempHigh		= preg_match('|F|i',$uomTemp)?'77':'25';
	$tempXHigh		= preg_match('|F|i',$uomTemp)?'86':'30';
	if ($SITE['WXsoftware']     == 'WD') {
		$graphWind	= $graphImageDir.'dirplot.gif';
	}
} 
# generic settings for CU
if ($wp == 'CU' ) {
	$graphWind		= $graphImageDir.'windrose.png';
	$now 			= strtotime(date('Ymd').'T000000');
	$lastTip		= strtotime(substr($ws['lastRainTip'],0,8).'T000000');	
	$ws['rainDaysWithNo']   = ($now - $lastTip) / (24*60*60);
}
# these programs use standard trends
if ($wp == 'CW' || $wp == 'MB' || $wp == 'CU' || $wp == 'WD') {
        $from_temp              = $ws['tempFrom'];
        $from_baro              = $ws['baroFrom'];
        $from_rain              = $ws['rainFrom'];
        $from_wind              = $ws['windFrom'];
	$minutes                = explode(' ',$ws['trendsMinutes']);
	$tempTrends             = explode('#',$ws['tempTrends']);
	$windTrends             = explode('#',$ws['windTrends']);
	$gustTrends             = explode('#',$ws['gustTrends']);
	$wdirTrends             = explode('#',$ws['wdirTrends']);
	$humiTrends             = explode('#',$ws['humiTrends']);
	$baroTrends             = explode('#',$ws['baroTrends']);
	$rainTrends             = explode('#',$ws['rainTrends']);
	if ($haveUV)    {$uvTrends      = explode('#',$ws['uvTrends']);}
	if ($haveSolar) {$solarTrends   = explode('#',$ws['solarTrends']);}
        $end_trends             = count($minutes);
        for ($i = 0; $i < $end_trends; $i++) {
                $wsTrends[$i] ['min']	= $minutes[$i];
                $wsTrends[$i] ['temp']	= wsConvertTemperature ($tempTrends[$i],$from_temp) ;
                $wsTrends[$i] ['wind']	= wsConvertWindspeed($windTrends[$i], $from_wind);
                $wsTrends[$i] ['gust']	= wsConvertWindspeed($gustTrends[$i], $from_wind);
                $wsTrends[$i] ['dir']	= wsConvertWinddir($wdirTrends[$i]);
                $wsTrends[$i] ['hum']	= $humiTrends[$i];
                $wsTrends[$i] ['baro']	= wsConvertBaro ($baroTrends[$i], $from_baro);
                $wsTrends[$i] ['rain']  = wsConvertRainfall ($rainTrends[$i], $from_rain);
                if ($haveUV)    {$wsTrends[$i] ['uv']   = $uvTrends[$i];}
                if ($haveSolar) {$wsTrends[$i] ['sol']  = $solarTrends[$i];}
        }
}
if ($wp == 'MB') {      // clean rain
        $count  = count($wsTrends);
        for ($i = 1; $i < $count; $i++) {
                $wsTrends[$i] ['rain']  = $ws['rainToday'] - $wsTrends[$i] ['rain'];
        }
}
#echo '<pre>'; print_r($wsTrends) exit;
#
# now we can output the trends page ----------------------------------------------------
?>
<!-- trends-inc.php -->
<div class="blockDiv">
<h3 class="blockHead"><?php langtrans('Trends as of'); ?> <span class="ajax" id="ajaxdate"><?php echo $vars['ajaxdate']; ?></span> 
<span class="ajax" id="ajaxindicator"><?php langtrans('at'); ?></span> 
<span class="ajax" id="ajaxtime"><?php echo $vars['ajaxtime'] ?></span> </h3>
<table class="genericTable trendTable" style= "width: 100%; margin: 0 auto;">
<tr class="table-top">
<th><?php echo langtrans('MINUTES'); ?></th>
<th><?php echo langtrans('TEMP'); ?></th>
<th colspan="3"><?php echo langtrans('WIND').' ('.$uomWind.')' ?></th>
<th><?php echo langtrans('HUMIDITY'); ?></th>
<th><?php echo langtrans('PRESSURE'); ?></th>
<th><?php echo langtrans('RAIN'); ?></th>
<?php
if ($haveSolar) {echo '<th style="text-transform: uppercase;">'.langtransstr('SOLAR').'</th>'.PHP_EOL;}
if ($haveUV) 	{echo '<th>'.langtransstr('UV').'</th>'.PHP_EOL;}
?>
</tr>
<tr class="table-top">
<th><?php echo langtrans('AGO'); ?></th>
<th><?php echo $uomTemp; ?></th>
<th><?php echo langtrans('SPEED'); ?></th>
<th><?php echo langtrans('GUST'); ?></th>
<th><?php echo langtrans('DIR'); ?></th>
<th>%</th>
<th><?php echo $uomBaro; ?></th>
<th><?php echo $uomRain; ?></th>
<?php
if ($haveSolar) {echo '<th>w/m<sup>2</sup></th>'.PHP_EOL;}
if ($haveUV) 	{echo '<th>'.langtransstr('index').'</th>'.PHP_EOL;}
?>
</tr>
<tr class="row-light">
<td>&nbsp;&nbsp;0</td>
<td><span class="ajax" id="ajaxtempNoU"><?php echo wsNumber ($vars['ajaxtempNoU'],$decTemp); ?></span></td>
<td><span class="ajax" id="ajaxwindNoU"><?php echo wsNumber ($vars['ajaxwindNoU'],$decWind); ?></span></td>
<td><span class="ajax" id="ajaxgustNoU"><?php echo wsNumber ($vars['ajaxgustNoU'],$decWind); ?></span></td>
<td><span class="ajax" id="ajaxwinddirNoU"><?php echo $vars['ajaxwinddirNoU']; ?></span></td>
<td><span class="ajax" id="ajaxhumidityNoU"><?php echo $vars['ajaxhumidityNoU']; ?></span></td>
<td><span class="ajax" id="ajaxbaroNoU"><?php echo wsNumber ($vars['ajaxbaroNoU'],$decBaro); ?></span></td>
<td>
<?php 
if ($SITE['WXsoftware'] == 'MH') {
        echo '<span class="ajax" id="none">'.wsNumber ($meteohub_rain,$decPrecip).'</span></td>'.PHP_EOL;} 
else {  echo '<span class="ajax" id="ajaxrainrateNoU">'.wsNumber ($vars['ajaxrainNoU'],$decPrecip).'</span></td>'.PHP_EOL;}
if ($haveSolar) {echo '<td><span class="ajax" id="ajaxsolar">'.wsNumber ($vars['ajaxsolar'],0).'</span></td>'.PHP_EOL;}
if ($haveUV) 	{echo '<td><span class="ajax" id="ajaxuv">'.wsNumber ($vars['ajaxuv']).'</span></td>'.PHP_EOL;}
?>

</tr>
<?php
$rowclass='row-dark';
for ($i = 1; $i < count ($wsTrends); $i++){
	echo '<tr class="'.$rowclass.'">'.PHP_EOL;
	echo '<td>&nbsp;&nbsp;'.$wsTrends[$i] ['min'].'</td>'.PHP_EOL;
	echo '<td>'.wsNumber ($wsTrends[$i] ['temp'],$decTemp).'</td>'.PHP_EOL;
	echo '<td>'.wsNumber ($wsTrends[$i] ['wind'],$decWind).'</td>'.PHP_EOL;
	echo '<td>'.wsNumber ($wsTrends[$i] ['gust'],$decWind).'</td>'.PHP_EOL;
	echo '<td>'.langtransstr($wsTrends[$i] ['dir']).'</td>'.PHP_EOL;
	echo '<td>'.$wsTrends[$i] ['hum'].'</td>'.PHP_EOL;
	echo '<td>'.wsNumber ($wsTrends[$i] ['baro'],$decBaro).'</td>'.PHP_EOL;
	echo '<td>'.wsNumber ($wsTrends[$i] ['rain'],$decPrecip).'</td>'.PHP_EOL;
	if ($haveSolar) {echo '<td>'.wsNumber ($wsTrends[$i] ['sol'],0).'</td>'.PHP_EOL;}
	if ($haveUV) 	{echo '<td>'.wsNumber ($wsTrends[$i] ['uv']).'</td>'.PHP_EOL;}
	if ($rowclass == 'row-dark'){$rowclass='row-light';} else {$rowclass='row-dark';}
}
?>
</table>
</div>
<div class="blockDiv">
<h3 class="blockHead"><?php langtrans('Records and Stats'); ?></h3> 
<table class="genericTable trendTable"  style= " text-align: left; width: 100%; margin: 0 auto;">
<tr class="table-top">
<th colspan="2" style="width: 50%;">&nbsp;&nbsp;<?php echo langtrans('RAIN'); ?></th>
<th colspan="2" style="width: 50%;">&nbsp;&nbsp;<?php 
$rainDaysWithNo = false;
if (isset ($ws['rainDaysWithNo']) && (string) $ws['rainDaysWithNo'] <> 'n/a' ) {$rainDaysWithNo = true;}
if ($rainDaysWithNo) {echo langtrans('RAIN HISTORY');} else {echo '&nbsp;';}
?></th>
</tr>
<?php // wsNumber ($ws['rainMonth'])
# rain and rain history  - today
echo '
<tr class="row-light">
<td>&nbsp;&nbsp;'.langtransstr('Today').'</td>
<td>'.wsNumber ($ws['rainToday'],$decPrecip) . $uomRain.' ('.wsNumber ($ws['rainHour'],$decPrecip) . $uomRain.' '.langtransstr('last hour').')</td>';
 if  ($rainDaysWithNo) {
	echo '
<td>&nbsp;&nbsp;'.langtransstr('Today').'</td>
<td>'.round($ws['rainDaysWithNo']) . ' ';
	if (round($ws['rainDaysWithNo']) <>1 ) {echo langtransstr('days since last rain');} else { echo langtransstr('day since last rain ');}
	echo '
</td>';
} else {
	echo '
<td>&nbsp;</td><td><!-- no rainDaysWithNo --></td>';
}
echo '
</tr>';
# rain  - yesterday   and week 
echo '
<tr class="row-dark">
<td>&nbsp;&nbsp;'.langtransstr('Yesterday').'</td>
<td>'.wsNumber ($ws['rainYday'],$decPrecip) . $uomRain.'</td>';
if (isset ($ws['rainWeek']) ) {
	echo '
<td>&nbsp;&nbsp;'.langtransstr('Week').'</td>
<td>'. wsNumber ($ws['rainWeek'],$decPrecip) . $uomRain.' '.$labelRainWeek.'</td>';
} else {
	echo '<td>&nbsp;</td><td><!-- no rainWeek --></td>';
}  
echo '
</tr>';
# rain   - month 
echo '
<tr class="row-light">
<td>&nbsp;&nbsp;'.langtransstr('Month').'&nbsp;</td>
<td>'.wsNumber ($ws['rainMonth'],$decPrecip) . $uomRain;
if (isset ($ws['rainDayMnth'])  && $ws['rainDayMnth'] <> 'n/a') {
	if (trim($ws['rainDayMnth']) == '') {$ws['rainDayMnth'] = 0;}
	echo ' ('.$ws['rainDayMnth'] . ' '; 
	if ($ws['rainDayMnth'] <> 1) {echo langtransstr('rain days this month');} else {echo langtransstr('rain day this month');}
	echo ')';
} else {echo '<!-- no rainDayMnth -->';}
echo '	
</td>';
if(isset($raintodatemonthago)) { // wsNumber ($raintodatemonthago)
echo '
<td>&nbsp;&nbsp;'.langtransstr('Month').'&nbsp;</td>
<td>'.wsNumber ($raintodatemonthago,$decPrecip) . $uomRain.' '.langtransstr('last month').'</td>';
} else {
	echo '<td>&nbsp;</td><td><!-- no raintodatemonthago --></td>';
}   // end if isset($raintodatemonthago) 
echo '
</tr>
<tr class="row-dark">
<td>&nbsp;&nbsp;'.langtransstr('Year').'</td>
<td>'.wsNumber ($ws['rainYear'],$decPrecip) . $uomRain; 
if (isset ($ws['rainDayYear'])  && $ws['rainDayYear'] <> 'n/a'){
	echo '  ('.$ws['rainDayYear']. ' ';
	if ($ws['rainDayYear'] <> 1) {echo  langtransstr('rain days this year');} else {echo langtransstr('rain day this year'); }
	echo ')
</td>'; 
} else {
	echo '</td>';
}   // end if isset($rainDayYear)
if( isset($raintodateyearago) ) { 
	echo '
<td>&nbsp;&nbsp;'.langtransstr('Year').'</td>
<td>'.wsNumber ($raintodateyearago,$decPrecip) . $uomRain.' '.langtransstr('total last year at this time').'</td>';
} else {
	echo '<td>&nbsp;</td><td><!-- no raintodateyearago  --></td>';
}  // end if isset($raintodateyearago) 
echo '
</tr>
<tr class="row-light"><td colspan="4"></td></tr>
<tr class="table-top">
<th colspan="2">&nbsp;&nbsp;'.langtransstr('TEMPERATURE HIGHS').'</th>
<th colspan="2">&nbsp;&nbsp;'.langtransstr('TEMPERATURE LOWS').'</th>
</tr>
<tr class="row-light">
<td>&nbsp;&nbsp;'.langtransstr('Today').'&nbsp;&nbsp;</td>
<td>'.wsNumber($ws['tempMaxToday'],$decTemp).$uomTemp.' '.langtransstr('at').' '.string_date ($ws['tempMaxTodayTime'], $timeOnlyFormat).'</td>
<td>&nbsp;&nbsp;'.langtransstr('Today').'&nbsp;&nbsp;</td>
<td>'.wsNumber($ws['tempMinToday'],$decTemp).$uomTemp.' '.langtransstr('at').' '.string_date ($ws['tempMinTodayTime'], $timeOnlyFormat).'</td>
</tr>';

if(isset($ws['tempMaxYday']) and isset($ws['tempMinYdayTime'])) {
	echo '
<tr class="row-dark">
<td>&nbsp;&nbsp;'.langtransstr('Yesterday').'&nbsp;&nbsp;</td>
<td>'.wsNumber($ws['tempMaxYday'],$decTemp).$uomTemp.' '.langtransstr('at').' '.string_date ($ws['tempMaxYdayTime'], $timeOnlyFormat).'</td>
<td>&nbsp;&nbsp;'.langtransstr('Yesterday').'&nbsp;&nbsp;</td>
<td>'.wsNumber($ws['tempMinYday'],$decTemp).$uomTemp.' '.langtransstr('at').' '.string_date ($ws['tempMinYdayTime'], $timeOnlyFormat).'</td>
</tr>';
} // end for yesterday min/max temp row
if(isset($ws['tempMaxMonth']) and isset($ws['tempMaxMonthTime'])) {
	echo '
<tr class="row-light">
<td>&nbsp;&nbsp;'.langtransstr('Month').'&nbsp;&nbsp;</td>
<td>'.wsNumber($ws['tempMaxMonth'],$decTemp).$uomTemp.' '.langtransstr('on').' '.string_date ($ws['tempMaxMonthTime'], $dateOnlyFormat).'</td>
<td>&nbsp;&nbsp;'.langtransstr('Month').'&nbsp;&nbsp;</td>
<td>'.wsNumber($ws['tempMinMonth'],$decTemp).$uomTemp.' '.langtransstr('on').' '.string_date ($ws['tempMinMonthTime'], $dateOnlyFormat).'</td>
</tr>';
}
if(isset($ws['tempMaxYear']) and isset($ws['tempMaxYearTime'])) {
	echo '
<tr class="row-dark">
<td>&nbsp;&nbsp;'.langtransstr('Year').'</td>
<td>'.wsNumber($ws['tempMaxYear'],$decTemp).$uomTemp.' '. langtransstr('on').' '.string_date ($ws['tempMaxYearTime'], $dateOnlyFormat).'</td>
<td>&nbsp;&nbsp;'.langtransstr('Year').'</td>
<td>'.wsNumber($ws['tempMinYear'],$decTemp).$uomTemp.' '. langtransstr('on').' '.string_date ($ws['tempMinYearTime'], $dateOnlyFormat).'</td>
</tr>';
}
if (isset($ws['daysHigh']) and isset($ws['daysLow']) ) {
	echo '
<tr class="row-light">
<td>&nbsp;&nbsp;Max &gt; '.wsNumber($tempHigh,$decTemp).$uomTemp.'</td><td>'.$ws['daysHigh'].' '.langtransstr('days').' '.langtransstr('this month').'</td>
<td>&nbsp;&nbsp;Min &lt; '.wsNumber($tempLow,$decTemp).$uomTemp.'</td><td>'.$ws['daysLow'].' '.langtransstr('days').' '.langtransstr('this month').'</td>
</tr>
<tr class="row-dark">
<td>&nbsp;&nbsp;Max &gt; '.wsNumber($tempXHigh,$decTemp).$uomTemp.'</td><td>'.$ws['daysXHigh'].' '.langtransstr('days').' '.langtransstr('this month').'</td>
<td>&nbsp;&nbsp;Min &lt; '.wsNumber($tempXLow,$decTemp).$uomTemp.'</td><td>'.$ws['daysXLow'].' '.langtransstr('days').' '.langtransstr('this month').'</td>
</tr>';
}
echo '
<tr class="row-light"><td colspan="4"></td></tr>
<tr class="table-top">
<th colspan="2">&nbsp;&nbsp;'.langtransstr('BAROMETER LOWS').'</th>
<th colspan="2">&nbsp;&nbsp;'.langtransstr('WIND CHILL LOWS').'</th>
</tr>
<tr class="row-light">
<td>&nbsp;&nbsp;'.langtransstr('Today').'</td>
<td>'.wsNumber($ws['baroMinToday'],$decBaro). $uomBaro.' '.langtransstr('at') .' '. string_date ($ws['baroMinTodayTime'], $timeOnlyFormat).'</td>
<td>&nbsp;&nbsp;'.langtransstr('Today').'</td>
<td>'.wsNumber($ws['chilMinToday'],$decTemp). $uomTemp.' '.langtransstr('at') .' '. string_date ($ws['chilMinTodayTime'], $timeOnlyFormat).'</td>
</tr>';
if(isset($ws['baroMinYday']) and isset($ws['chilMinYday'])) {
	echo '
<tr class="row-dark">
<td>&nbsp;&nbsp;'.langtransstr('Yesterday').'</td>
<td>'.wsNumber($ws['baroMinYday'],$decBaro). $uomBaro.' '.langtransstr('at') .' '.string_date ($ws['baroMinYdayTime'], $timeOnlyFormat).'</td>
<td>&nbsp;&nbsp;'.langtransstr('Yesterday').'</td>
<td>'.wsNumber($ws['chilMinYday'],$decTemp). $uomTemp.' '.langtransstr('at') .' '.string_date ($ws['chilMinYdayTime'], $timeOnlyFormat).'</td>
</tr>';
} // end both minbaro and minwindchill for yesterday exist
echo '
<tr class="row-light">
<td>&nbsp;&nbsp;'.langtransstr('Month').'&nbsp;</td>
<td>'.wsNumber($ws['baroMinMonth'],$decBaro). $uomBaro.' '.langtransstr('on') .' '. string_date ($ws['baroMinMonthTime'], $dateOnlyFormat).'</td>
<td>&nbsp;&nbsp;'.langtransstr('Month').'&nbsp;</td>
<td>'.wsNumber($ws['chilMinMonth'],$decTemp).$uomTemp.' '.langtransstr('on') .' '. string_date ($ws['chilMinMonthTime'], $dateOnlyFormat).'</td>
</tr>
<tr class="row-dark">
<td>&nbsp;&nbsp;'.langtransstr('Year').'</td>
<td>'.wsNumber($ws['baroMinYear'],$decBaro). $uomBaro.' '.langtransstr('on') .' '. string_date ($ws['baroMinYearTime'], $dateOnlyFormat).'</td>
<td>&nbsp;&nbsp;'.langtransstr('Year').'</td>
<td>'.wsNumber($ws['chilMinYear'],$decTemp). $uomTemp.' '.langtransstr('on') .' '. string_date ($ws['chilMinYearTime'], $dateOnlyFormat).'</td>
</tr>
<tr class="row-light"><td colspan="4"></td></tr>';

if ($haveSolar && isset($ws['etToday']) ) {	# Following section only valid if station has Solar sensor
	echo '
<tr class="table-top">
<th colspan="2">&nbsp;&nbsp;'.langtransstr('EVAPOTRANSPIRATION').'</th>
<th colspan="2">&nbsp;&nbsp;'.langtransstr('RAIN').'</th>
</tr>
<tr class="row-light">
<td>&nbsp;&nbsp;'.langtransstr('Today').'</td>
<td>'.wsNumber($ws['etToday'],$decPrecip). $uomRain.'</td>
<td>&nbsp;&nbsp;'.langtransstr('Today').'</td>
<td>'.wsNumber($ws['rainToday'],$decPrecip). $uomRain.'</td>
</tr>';
	if (isset ($ws['etYday']) ) {
		echo '
<tr class="row-dark">
<td>&nbsp;&nbsp;'.langtransstr('Yesterday').'</td>
<td>'.wsNumber($ws['etYday'],$decPrecip). $uomRain.'</td>
<td>&nbsp;&nbsp;'.langtransstr('Yesterday').'</td>
<td>'.wsNumber($ws['rainYday'],$decPrecip).$uomRain.'</td>
</tr>';
	}
	if (isset ($ws['etMonth']) ) {
		echo '
<tr class="row-light">
<td>&nbsp;&nbsp;'.langtransstr('Month').'</td>
<td>'.wsNumber($ws['etMonth'],$decPrecip). $uomRain.'</td>
<td>&nbsp;&nbsp;'.langtransstr('Month').'&nbsp;</td>
<td>'.wsNumber($ws['rainMonth'],$decPrecip). $uomRain.'</td>
</tr>';
	}
	if (isset ($ws['etYear']) ) {
		echo '
<tr class="row-dark">
<td>&nbsp;&nbsp;'.langtransstr('Year').'</td>
<td>'.wsNumber($ws['etYear'],$decPrecip). $uomRain.'</td>
<td>&nbsp;&nbsp;'.langtransstr('Year').'&nbsp;</td>
<td>'.wsNumber($ws['rainYear'],$decPrecip). $uomRain.'</td>
</tr>
<tr class="row-light"><td colspan="4"></td>
</tr>';
	}

} // end if haveSolar  and etToday
####################### solar and UV depending on $haveSolar and $haveUV settings
if ($haveSolar or $haveUV) {
	echo '
<tr class="table-top">';
	if ($haveSolar) {
		echo '<th colspan="2">&nbsp;&nbsp;'.langtransstr('SOLAR HIGHS').'</th>';
	}
	if ($haveUV) {
		echo '<th colspan="2">&nbsp;&nbsp;'.langtransstr('UV HIGHS').'</th>';
	}
	if (!$haveSolar or !$haveUV) {
		echo '
<td colspan="2">&nbsp;<!-- only uv or solar --></td>';   
	}
	echo '	
</tr>
<tr class="row-light">';
	if ($haveSolar) {
		echo '
<td>&nbsp;&nbsp;'.langtransstr('Today').'</td>
<td>'.wsNumber($ws['solarMaxToday'],0).' W/m<sup>2</sup> ';
		if (isset ($ws['solarMaxTodayTime']) ) {
			echo langtransstr('at').' '.string_date($ws['solarMaxTodayTime'], $timeOnlyFormat);
		}
		echo '</td>';
	} 
    if ($haveUV) {
    	echo '
<td>&nbsp;&nbsp;'.langtransstr('Today').'</td>
<td>'.wsNumber($ws['uvMaxToday']).' index ';
		if (isset ($ws['uvMaxTodayTime']) ) {
			echo langtransstr('at').' '.string_date($ws['uvMaxTodayTime'], $timeOnlyFormat);
		}
		echo '</td>';
	} 
	if (!$haveSolar or !$haveUV) {
		echo '
<td colspan="2">&nbsp;<!-- only uv or solar --></td>';   
	}
	echo '
</tr>
<tr class="row-dark">';
	if ($haveSolar and isset($ws['solarMaxYday'])) {
		echo '
<td>&nbsp;&nbsp;'.langtransstr('Yesterday').'</td>
<td>'.wsNumber($ws['solarMaxYday'],0).' W/m<sup>2</sup> ';
		if (isset ($ws['solarMaxYdayTime']) ) {
			echo langtransstr('at').' '.string_date($ws['solarMaxYdayTime'], $timeOnlyFormat);
		}
		echo '</td>';
	}
    if ($haveUV and isset($ws['uvMaxYday'])) {
    	echo '
<td>&nbsp;&nbsp;'.langtransstr('Yesterday').'</td>
<td>'.wsNumber($ws['uvMaxYday']).' index ';
		if (isset ($ws['uvMaxYdayTime']) ) {
 			echo langtransstr('at').' '.string_date($ws['uvMaxYdayTime'] , $timeOnlyFormat);
 		}	
 		echo '</td>';
 	}
	if (!$haveSolar or !$haveUV) {
		echo '
<td colspan="2">&nbsp;<!-- only uv or solar --></td>';   
	}
	echo '
</tr>';
} // end $haveSolar or $haveUV
#################### end conditional Solar and/or UV display
echo '
</table>
</div>';
$windLines = 0;
echo '
<div class="blockDiv">
<h3 class="blockHead">'.langtransstr('Wind Data').'</h3> 
<table class="genericTable trendTable"  style= " text-align: left; width: 100%; margin: 0 auto;">';
$string = '';
if (isset ($graphWind) && $graphWind <> '') {
$string .= '
<tr class="row-light"><td colspan="2" style="width: 50%;">&nbsp;</td> 
<td class="tdimage" rowspan="##" style="background-color: transparent;">
<img src="'. $graphWind.'" width="300" alt="Wind direction plot" style="padding: 5px;"/>
</td>
</tr>';
$extra  = '';
} 
else {  $extra  = '<td>&nbsp;</td><td>&nbsp;</td>'; }
#
$string .= '
<tr class="table-top">
<th colspan="2" style="width: 50%;">&nbsp;&nbsp;'.langtransstr('CURRENT').'</th>'.
$extra.'</tr>';
$windLines++;
$string .= '
<tr class="row-light">
<td>&nbsp;&nbsp;'.langtransstr('Now').'</td>
<td>'.wsNumber($ws['windAct'],$decWind).$uomWind.' '.langtransstr($ws['windActDsc']).'</td>'.
$extra.'
</tr>';
$windLines++;
$string .= '
<tr class="row-dark">
<td>&nbsp;&nbsp;'. langtransstr('Gust').'</td>
<td>'.wsNumber($ws['gustAct'],$decWind).$uomWind.' '.langtransstr($ws['windActDsc']).'</td>'.
$extra.'
</tr>';
$windLines++;
$string .= '
<tr class="row-light">';
if (isset ($ws['gustMaxHour']) ) {
	$string .='
<td>&nbsp;&nbsp;'.langtransstr('Gust/hr').'</td>
<td>'.wsNumber($ws['gustMaxHour'],$decWind).$uomWind.'</td>';
} else {
	$string .= '<td colspan="2">&nbsp;</td>';
}
$string .= $extra.'
</tr>';
$windLines++;
$string .= '
<tr class="table-top">
<th colspan="2">&nbsp;&nbsp;'.langtransstr('WIND GUST HIGHS').'</th>'.
$extra.'
</tr>';
$windLines++;
$string .= '
<tr class="row-light">
<td>&nbsp;&nbsp;'.langtransstr('Today').'</td>
<td>'.wsNumber($ws['gustMaxToday'],$decWind). $uomWind.' '.langtransstr($ws['windActDsc']).' '.langtransstr('at').' '.string_date($ws['gustMaxTodayTime'] , $timeOnlyFormat).'</td>'.
$extra.'
</tr>';
if (isset($ws['gustMaxYday'])) { 
	$windLines++;
	$string .= '
<tr class="row-dark">
<td>&nbsp;&nbsp;'.langtransstr('Yesterday').'</td>
<td>'.wsNumber($ws['gustMaxYday'],$decWind). $uomWind.' '.langtransstr('at').' '.string_date($ws['gustMaxYdayTime'] , $timeOnlyFormat).'</td>'.
$extra.'
</tr>';
} // end $maxgustyest
$windLines++;
$string .= '
<tr class="row-light">
<td>&nbsp;&nbsp;'.langtransstr('Month').'</td>
<td>'.wsNumber($ws['gustMaxMonth'],$decWind). $uomWind.' '.langtransstr('on').' '.string_date($ws['gustMaxMonthTime'] , $dateOnlyFormat).'</td>'.
$extra.'
</tr>';
$windLines++;
$string .= '
<tr class="row-dark">
<td>&nbsp;&nbsp;'.langtransstr('Year').'</td>
<td>'.wsNumber($ws['gustMaxYear'],$decWind). $uomWind.' '.langtransstr('on').' '.string_date($ws['gustMaxYearTime'] , $dateOnlyFormat).'</td>'.
$extra.'
</tr>';
for ($i = $windLines; $i < 9; $i++) {
        $windLines++;
        $class = 'class="row-light"';
	$string .= '<tr><td colspan="2" '.$class.'>&nbsp;</td>'.$extra.'</tr>'.PHP_EOL;
	if ($class == 'class="row-light"') {$class = 'class="row-dark"';} else {$class = 'class="row-light"';}
}
$cnt    = (string) ($windLines + 2);
$string = str_replace ('##',$cnt,$string); 
echo $string.'
</table>
</div>
<!-- end of trends-inc.php -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-09-18 release 2.8 version 
