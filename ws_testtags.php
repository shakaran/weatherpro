<?php #	ini_set('display_errors', 'On');  error_reporting(E_ALL);	
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ws_testtags.php';
#if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.11 2015-05-25';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.11 2015-05-25   added missing ymd global / added soil most saratoga / added missing uoms / comp 2.7 release
# --------------------------------------- version ----------------------
$ws['tags.php']		= $pageName.'-'.$pageVersion;
# --------------------------------------- conditions--------------------
convert_testtags();
function convert_testtags() {

global $ymd, $ws, $wsTrends, $SITE, $pageFile, $my_date_format;

echo '<!-- module '.$pageFile.' loading  '.$SITE['wsTags'].' -->'.PHP_EOL;
$ws['originated'] 	= 'leuven testtags.php -';
include $SITE['wsTags'];                // load testtags.php
if (isset ($ws['actTime']) ) {          // it is a leuven one
        $ws['wsVersion']        = str_replace('---','',$ws['wsVersion']);
        if (!isset ($ws['tempActExtra2']) ) {$ws['tempActExtra2']	= '';}
        return; 
} 
# the testtags.php is a Saratoga one ! not leuven
$ws['originated'] 	= 'saratoga testtags.php - '.$date.' - '.$time;
#
echo '<!-- module '.$pageFile.' probable Saratoga testtags - converting -->'.PHP_EOL;
#
ob_start();
$weathercond 		= $weathercond;
$ws['wdCurCond'] 	= $Currentsolardescription;
$ws['wdCurIcon']	= $iconnumber;			// Current icon number
$ws['wdMetarCcn']	= $weatherreport;		// Current weather conditions from selected METAR
$ws['wdMetarCld']	= '';		                // Cloud METAR label from the metargif
# ------------------------------------------ units ---------------------
if (!isset ($uomtemp) )         {$from_temp = $SITE['uomTemp'];} else   {$from_temp = $uomtemp;}        //  = 'C', 'F',  (or  '�C', '�F', or '&deg;C', '&deg;F' )
if (!isset ($uombaro) )         {$from_baro = $SITE['uomBaro'];} else   {$from_baro = $uombaro;}        //  = 'inHg', 'hPa', 'kPa', 'mb'
if (!isset ($uomrain) )         {$from_rain = $SITE['uomRain'];} else   {$from_rain = $uomrain;}        //  = 'mm', 'in'$from	= $uomrain; 		
if (!isset ($uomwind) )         {$from_wind = $SITE['uomWind'];} else   {$from_wind = $uomwind;}        //  = 'kts','mph','kmh','km/h','m/s','Bft'	


if (!isset ($uomdistance) )     {$wdDist = $SITE['uomDistance'];}else   {$wdDist = $uomdistance; }      // = 'mi','km'  (for windrun variables)
# ------------------------------------------- date - time --------------
if (!isset ($datefmt) )         {$wdDatefmt = $SITE['uomDistance'];}    else {$wdDatefmt  = $datefmt; }	//  = 'd/m/y', 'm/d/y'
$wdSeconds	        = '00';
$wdMinute 	        = $time_minute; 
$wdHour 	        = $time_hour;
$wdDay 		        = $date_day;	
$wdMonth 	        = $date_month;
$wdYear 	        = $date_year;
$ymd		        = (string) $wdYear.$wdMonth.$wdDay;   // 
$ws['actTime']	        = (string) $ymd.$wdHour.$wdMinute.$wdSeconds; // '20120523113945';
# ------------------------------------------ temperature ---------------------------------
$from                   = $from_temp;
$ws['tempMinTodayTime']	= wdDate($mintempt);
$ws['tempMinYdayTime']	= wdDate($mintempyestt);
$ws['tempMinMonthTime']	= wdYMD($mrecordlowtempyear,$mrecordlowtempmonth,$mrecordlowtempday);
$ws['tempMinYearTime']	= wdYMD($yrecordlowtempyear,$yrecordlowtempmonth,$yrecordlowtempday);
$ws['tempMaxTodayTime']	= wdDate($maxtempt); 
$ws['tempMaxYdayTime']	= wdDate($maxtempyestt); //yday
$ws['tempMaxMonthTime']	= wdYMD($mrecordhightempyear,$mrecordhightempmonth,$mrecordhightempday);
$ws['tempMaxYearTime']	= wdYMD($yrecordhightempyear,$yrecordhightempmonth,$yrecordhightempday);
$ws['dewpMinTodayTime']	= wdDate($mindewt);
$ws['dewpMinYdayTime']	= wdDate($mindewyestt); //yday
$ws['dewpMaxTodayTime']	= wdDate($maxdewt);
$ws['dewpMaxYdayTime']	= wdDate($maxdewyestt);  // yday
$ws['heatMaxTodayTime']	= wdDate($maxheatt);
$ws['heatMaxYdayTime']	= wdDate($maxheatyestt);
$ws['heatMaxMonthTime']	= wdYMD($mrecordhighheatindexyear,$mrecordhighheatindexmonth,$mrecordhighheatindexday);
$ws['heatMaxYearTime']	= wdYMD($yrecordhighheatindexyear,$yrecordhighheatindexmonth,$yrecordhighheatindexday);
$ws['chilMinTodayTime']	= wdDate($minwindcht);
$ws['chilMinYdayTime']	= wdDate($minchillyestt); // yday
$ws['chilMinMonthTime'] = wdYMD($mrecordlowchillyear,$mrecordlowchillmonth,$mrecordlowchillday);
$ws['chilMinYearTime']	= wdYMD($yrecordlowchillyear,$yrecordlowchillmonth,$yrecordlowchillday); 

$ws['tempAct']		= wsConvertTemperature($temperature, $from);  // convert and clean of units
if (isset ($indoortemp) ) 
        {$ws['tempActInside']	= wsConvertTemperature($indoortemp, $from);}
else    {$ws['tempActInside']	= '';}
if (isset ($generalextratemp1) ) 
        {$ws['tempActExtra1']	= wsConvertTemperature($generalextratemp1, $from);}
else    {$ws['tempActExtra1']	= '';}
if (isset ($generalextratemp2) ) 
        {$ws['tempActExtra2']	= wsConvertTemperature($generalextratemp2, $from);}
else    {$ws['tempActExtra2']	= '';}
$ws['tempDelta']	= wsConvertTemperature($tempchangehour, $from);	

$ws['tempToday']	= wsConvertTemperature($avtempsincemidnight, $from);
$ws['tempMinToday']	= wsConvertTemperature($mintemp, $from);
$ws['tempMinYday']	= wsConvertTemperature($mintempyest, $from);
$ws['tempMinMonth']	= wsConvertTemperature($mrecordlowtemp, $from);
$ws['tempMinYear']	= wsConvertTemperature($yrecordlowtemp, $from);
$ws['tempMaxToday']	= wsConvertTemperature($maxtemp, $from);
$ws['tempMaxYday']	= wsConvertTemperature($maxtempyest, $from);
$ws['tempMaxMonth']	= wsConvertTemperature($mrecordhightemp, $from);
$ws['tempMaxYear']	= wsConvertTemperature($yrecordhightemp, $from);

$ws['dewpAct']  	= wsConvertTemperature($dewpt, $from);
$ws['dewpDelta']	= wsConvertTemperature($dewchangelasthour, $from);
$ws['dewpMinToday']  	= wsConvertTemperature($mindew, $from);
$ws['dewpMinYday']  	= wsConvertTemperature($mindewyest, $from);
$ws['dewpMaxToday']  	= wsConvertTemperature($maxdew, $from);
$ws['dewpMaxYday']  	= wsConvertTemperature($maxdewyest, $from);

$ws['heatAct']  	= wsConvertTemperature($heati, $from);
$ws['heatDelta']	= 0;
$ws['heatMaxToday']	= wsConvertTemperature($maxheat, $from);
$ws['heatMaxYday']	= wsConvertTemperature($maxheatyest, $from);
$ws['heatMaxMonth']	= wsConvertTemperature($mrecordhighheatindex, $from);
$ws['heatMaxYear']	= wsConvertTemperature($yrecordhighheatindex, $from);
	
$ws['chilAct']		= wsConvertTemperature($windch, $from);
$ws['chilDelta']	= 0;
$ws['chilMinToday']	= wsConvertTemperature($minwindch, $from);
$ws['chilMinYday']	= wsConvertTemperature($minchillyest, $from);
$ws['chilMinMonth']	= wsConvertTemperature($mrecordlowchill, $from);
$ws['chilMinYear']	= wsConvertTemperature($yrecordlowchill, $from);

$ws['hudxAct'] 		= wsConvertTemperature($humidexcelsius, 'C');
$ws['hudxDelta'] 	= 0;
$ws['hudxMaxToday'] 	= wsConvertTemperature($todayhihumidex,  'C');

# ------------------------------------------ extreme temp   ------------------------------
$ws['daysXHigh']	= $daysTmaxGT30C;
$ws['daysHigh']		= $daysTmaxGT25C;
$ws['daysLow']		= $daysTminLT0C;
$ws['daysXLow']		= $daysTminLTm15C;
# ------------------------------------------ pressure / baro -----------------------------
$from	= $from_baro;
$ws['baroMinTodayTime']	= wdDate($lowbarot);
$ws['baroMinYdayTime']	= wdDate($minbaroyestt); // ytd
$ws['baroMinMonthTime']	= wdYMD($mrecordlowbaroyear,$mrecordlowbaromonth,$mrecordlowbaroday);
$ws['baroMinYearTime']	= wdYMD($yrecordlowbaroyear,$yrecordlowbaromonth,$yrecordlowbaroday);
$ws['baroMaxTodayTime']	= wdDate($highbarot);
$ws['baroMaxYdayTime']	= wdDate($maxbaroyestt);  // ytd
$ws['baroMaxMonthTime']	= wdYMD($mrecordhighbaroyear,$mrecordhighbaromonth,$mrecordhighbaroday);
$ws['baroMaxYearTime']	= wdYMD($yrecordhighbaroyear,$yrecordhighbaromonth,$yrecordhighbaroday);

$ws['baroAct'] 		= wsConvertBaro($baro, $from);
$ws['baroDelta']	= wsConvertBaro($trend, $from);
$ws['baroMinToday']	= wsConvertBaro($lowbaro, $from);
$ws['baroMinYday']	= wsConvertBaro($minbaroyest, $from);
$ws['baroMinMonth']	= wsConvertBaro($mrecordlowbaro, $from);
$ws['baroMinYear'] 	= wsConvertBaro($yrecordlowbaro, $from);	
$ws['baroMaxToday']	= wsConvertBaro($highbaro, $from);
$ws['baroMaxYday']	= wsConvertBaro($maxbaroyest, $from);
$ws['baroMaxMonth']	= wsConvertBaro($mrecordhighbaro, $from);
$ws['baroMaxYear'] 	= wsConvertBaro($yrecordhighbaro, $from);

# ------------------------------------------ humidity  -----------------------------------
$ws['humiMinTodayTime ']= wdDate($lowhumt);
$ws['humiMinYdayTime ']	= wdDate($minhumyestt);   // ytd
$ws['humiMaxTodayTime'] = wdDate($highhumt);
$ws['humiMaxYdayTime'] 	= wdDate($maxhumyestt);   // ytd

$ws['humiAct']		= $humidity*1.0;
$ws['humiExtra']	= ''; # $generalextrahum1*1.0;
$ws['humiDelta']	= $humchangelasthour*1.0;
$ws['humiMinToday'] 	= $lowhum*1.0;
$ws['humiMinYday'] 	= $minhumyest*1.0;
$ws['humiMaxToday']	= $highhum*1.0;
$ws['humiMaxYday']	= $maxhumyest*1.0;

# ------------------------------------------ rain  ---------------------------------------
$from   = $from_rain;
$ws['rainDayMnth'] 	= $dayswithrain;  	// %dayswithrain% 		Days with rain for the month
$ws['rainDayYear'] 	= $dayswithrainyear;	// %dayswithrainyear%	Days with rain for the year

$ws['rainRateAct'] 	= wsConvertRainfall($currentrainratehr, $from);
$ws['rainRateToday'] 	= wsConvertRainfall($maxrainrate, $from);		
$ws['rainHour']		= wsConvertRainfall($hourrn, $from);
$ws['rainToday']	= wsConvertRainfall($dayrn, $from);
$ws['rainYday']		= wsConvertRainfall($yesterdayrain, $from);
$ws['rainMonth']	= wsConvertRainfall($monthrn, $from);
$ws['rainYear']		= wsConvertRainfall($yearrn, $from);
$ws['rainDaysWithNo']	= $dayswithnorain;
$ws['rainWeek']		= wsConvertRainfall($raincurrentweek, $from);
# ------------------------------------------ EVAPOTRANSPIRATION --------------------------
$ws['etToday'] 		= wsConvertRainfall($VPet, $from);
$ws['etYday'] 		= wsConvertRainfall($yesterdaydaviset, $from);
$ws['etMonth'] 		= wsConvertRainfall($VPetmonth, $from);
# ------------------------------------------ wind  ---------------------------------------
$from                   = $from_wind;
$ws['windActDsc']	= $dirlabel;
$ws['windBeafort']	= $beaufortnum;
$ws['gustMaxTodayTime']	= wdDate($maxgstt);
$ws['gustMaxYdayTime']	= wdDate($maxgustyestt);
$ws['gustMaxMonthTime']	= wdYMD($mrecordhighgustyear,$mrecordhighgustmonth,$mrecordhighgustday);
$ws['gustMaxYearTime']	= wdYMD($yrecordhighgustyear,$yrecordhighgustmonth,$yrecordhighgustday);

$ws['windAct']		= wsConvertWindspeed($avgspd, $from);
$ws['gustAct']		= wsConvertWindspeed($gstspd, $from);
$ws['gustMaxHour']	= wsConvertWindspeed($maxgsthr, $from);	
$ws['gustMaxToday']	= wsConvertWindspeed($maxgst, $from);
$ws['gustMaxYday']	= wsConvertWindspeed($maxgustyest, $from); 
$ws['gustMaxMonth']	= wsConvertWindspeed($mrecordwindgust, $from);
$ws['gustMaxYear']	= wsConvertWindspeed($yrecordwindgust, $from);	

if ($ws['gustAct'] <= $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}
# ------------------------------------------  UV   ---------------------------------------
$ws['uvMaxTodayTime'] 	= wdDate($highuvtime);
$ws['uvMaxYdayTime'] 	= wdDate($highuvyesttime);
$ws['uvMaxMonthTime'] 	= wdYMD($mrecordhighuvyear,$mrecordhighuvmonth,$mrecordhighuvday);
$ws['uvMaxYearTime'] 	= wdYMD($yrecordhighuvyear,$yrecordhighuvmonth,$yrecordhighuvday);

$ws['uvAct']		= $VPuv;
$ws['uvMaxToday']	= $highuv;
$ws['uvMaxYday']	= $highuvyest;
$ws['uvMaxMonth']	= $mrecordhighuv;
$ws['uvMaxYear']	= $yrecordhighuv;
# ------------------------------------------ Solar  --------------------------------------
$ws['solarMaxTodayTime'] = wdDate($highsolartime);
$ws['solarMaxYdayTime']  = wdDate($highsolaryesttime);
$ws['solarMaxMonthTime'] = wdYMD($mrecordhighsolaryear,$mrecordhighsolarmonth,$mrecordhighsolarday);
$ws['solarMaxYearTime']  = wdYMD($yrecordhighsolaryear,$yrecordhighsolarmonth,$yrecordhighsolarday);

$ws['solarAct']		= $VPsolar*1.0;
$ws['solActPerc']	= $currentsolarpercent;
$ws['solarMaxToday']	= $highsolar;
$ws['solarMaxYday']	= $highsolaryest;
$ws['solarMaxMonth']	= $mrecordhighsolar;
$ws['solarMaxYear']	= $yrecordhighsolar;
# ------------------------------------------ cloud height --------------------------------
$from	                = 'ft';
$ws['cloudHeight']	= wsConvertDistance($cloudheightfeet,$from);
# ------------------------------------------ forecasts -----------------------------------
$ws['fcstWD'] 	  	= $iconnumber;
$ws['fcstTxt'] 	  	= $vpforecasttext;
# ------------------------------------------ sun and moon --------------------------------
$ws['sunrise']		= date($SITE['timeOnlyFormat'],strtotime($sunrise));
$ws['sunset']		= date($SITE['timeOnlyFormat'],strtotime($sunset));
$ws['moonrise']		= date($SITE['timeOnlyFormat'],strtotime($moonrise));
$ws['moonset']		= date($SITE['timeOnlyFormat'],strtotime($moonset));
$ws['lunarPhasePerc']	= $moonphase*1.0;
$ws['lunarAge']		= substr($moonage, 9, 3); // %moonphasename% %moonlunation% 
# ------------------------------------------ some more -----------------------------------
$ws['wsVersion']	= "version $wdversiononly b $wdbuild";
$ws['wsHardware'] 	= '';   // unknown
$ws['wsUptime']		= $windowsuptime;

if (!isset ($wsTrends)){$wsTrends = array() ;}
$i=0;
$wsTrends[$i] ['min']	= 0;
$wsTrends[$i] ['temp']	= $temp0minuteago;
$wsTrends[$i] ['wind']	= $wind0minuteago;
$wsTrends[$i] ['gust']	= $gust0minuteago;
$wsTrends[$i] ['dir']	= langtransstr($dir0minuteago);
$wsTrends[$i] ['hum']	= $hum0minuteago;
$wsTrends[$i] ['dew']	= $dew0minuteago;
$wsTrends[$i] ['baro']	= $baro0minuteago;
$wsTrends[$i] ['rain']	= $rain0minuteago;
$wsTrends[$i] ['sol']	= $VPsolar0minuteago;
$wsTrends[$i] ['uv']	= $VPuv0minuteago;
$i=1;
$wsTrends[$i] ['min']	= 5;
$wsTrends[$i] ['temp']	= $temp5minuteago;
$wsTrends[$i] ['wind']	= $wind5minuteago;
$wsTrends[$i] ['gust']	= $gust5minuteago;
$wsTrends[$i] ['dir']	= langtransstr($dir5minuteago);
$wsTrends[$i] ['hum']	= $hum5minuteago;
$wsTrends[$i] ['dew']	= $dew5minuteago;
$wsTrends[$i] ['baro']	= $baro5minuteago;
$wsTrends[$i] ['rain']	= $rain5minuteago;
$wsTrends[$i] ['sol']	= $VPsolar5minuteago;
$wsTrends[$i] ['uv']	= $VPuv5minuteago;
$i=2;
$wsTrends[$i] ['min']	= 10;
$wsTrends[$i] ['temp']	= $temp10minuteago;
$wsTrends[$i] ['wind']	= $wind10minuteago;
$wsTrends[$i] ['gust']	= $gust10minuteago;
$wsTrends[$i] ['dir']	= langtransstr($dir10minuteago);
$wsTrends[$i] ['hum']	= $hum10minuteago;
$wsTrends[$i] ['dew']	= $dew10minuteago;
$wsTrends[$i] ['baro']	= $baro10minuteago;
$wsTrends[$i] ['rain']	= $rain10minuteago;
$wsTrends[$i] ['sol']	= $VPsolar10minuteago;
$wsTrends[$i] ['uv']	= $VPuv10minuteago;
$i=3;
$wsTrends[$i] ['min']	= 15;
$wsTrends[$i] ['temp']	= $temp15minuteago;
$wsTrends[$i] ['wind']	= $wind15minuteago;
$wsTrends[$i] ['gust']	= $gust15minuteago;
$wsTrends[$i] ['dir']	= langtransstr($dir15minuteago);
$wsTrends[$i] ['hum']	= $hum15minuteago;
$wsTrends[$i] ['dew']	= $dew15minuteago;
$wsTrends[$i] ['baro']	= $baro15minuteago;
$wsTrends[$i] ['rain']	= $rain15minuteago;
$wsTrends[$i] ['sol']	= $VPsolar15minuteago;
$wsTrends[$i] ['uv']	= $VPuv15minuteago;
$i=4;
$wsTrends[$i] ['min']	= 20;
$wsTrends[$i] ['temp']	= $temp20minuteago;
$wsTrends[$i] ['wind']	= $wind20minuteago;
$wsTrends[$i] ['gust']	= $gust20minuteago;
$wsTrends[$i] ['dir']	= langtransstr($dir20minuteago);
$wsTrends[$i] ['hum']	= $hum20minuteago;
$wsTrends[$i] ['dew']	= $dew20minuteago;
$wsTrends[$i] ['baro']	= $baro20minuteago;
$wsTrends[$i] ['rain']	= $rain20minuteago;
$wsTrends[$i] ['sol']	= $VPsolar20minuteago;
$wsTrends[$i] ['uv']	= $VPuv20minuteago;
$i=5;
$wsTrends[$i] ['min']	= 30;
$wsTrends[$i] ['temp']	= $temp30minuteago;
$wsTrends[$i] ['wind']	= $wind30minuteago;
$wsTrends[$i] ['gust']	= $gust30minuteago;
$wsTrends[$i] ['dir']	= langtransstr($dir30minuteago);
$wsTrends[$i] ['hum']	= $hum30minuteago;
$wsTrends[$i] ['dew']	= $dew30minuteago;
$wsTrends[$i] ['baro']	= $baro30minuteago;
$wsTrends[$i] ['rain']	= $rain30minuteago;
$wsTrends[$i] ['sol']	= $VPsolar30minuteago;
$wsTrends[$i] ['uv']	= $VPuv30minuteago;
$i=6;
$wsTrends[$i] ['min']	= 45;
$wsTrends[$i] ['temp']	= $temp45minuteago;
$wsTrends[$i] ['wind']	= $wind45minuteago;
$wsTrends[$i] ['gust']	= $gust45minuteago;
$wsTrends[$i] ['dir']	= langtransstr($dir45minuteago);
$wsTrends[$i] ['hum']	= $hum45minuteago;
$wsTrends[$i] ['dew']	= $dew45minuteago;
$wsTrends[$i] ['baro']	= $baro45minuteago;
$wsTrends[$i] ['rain']	= $rain45minuteago;
$wsTrends[$i] ['sol']	= $VPsolar45minuteago;
$wsTrends[$i] ['uv']	= $VPuv45minuteago;
$i=7;
$wsTrends[$i] ['min']	= 60;
$wsTrends[$i] ['temp']	= $temp60minuteago;
$wsTrends[$i] ['wind']	= $wind60minuteago;
$wsTrends[$i] ['gust']	= $gust60minuteago;
$wsTrends[$i] ['dir']	= langtransstr($dir60minuteago);
$wsTrends[$i] ['hum']	= $hum60minuteago;
$wsTrends[$i] ['dew']	= $dew60minuteago;
$wsTrends[$i] ['baro']	= $baro60minuteago;
$wsTrends[$i] ['rain']	= $rain60minuteago;
$wsTrends[$i] ['sol']	= $VPsolar60minuteago;
$wsTrends[$i] ['uv']	= $VPuv60minuteago;
$i=8;
$wsTrends[$i] ['min']	= 75;
$wsTrends[$i] ['temp']	= $temp75minuteago;
$wsTrends[$i] ['wind']	= $wind75minuteago;
$wsTrends[$i] ['gust']	= $gust75minuteago;
$wsTrends[$i] ['dir']	= langtransstr($dir75minuteago);
$wsTrends[$i] ['hum']	= $hum75minuteago;
$wsTrends[$i] ['dew']	= $dew75minuteago;
$wsTrends[$i] ['baro']	= $baro75minuteago;
$wsTrends[$i] ['rain']	= $rain75minuteago;
$wsTrends[$i] ['sol']	= $VPsolar75minuteago;
$wsTrends[$i] ['uv']	= $VPuv75minuteago;
$i=9;
$wsTrends[$i] ['min']	= 90;
$wsTrends[$i] ['temp']	= $temp90minuteago;
$wsTrends[$i] ['wind']	= $wind90minuteago;
$wsTrends[$i] ['gust']	= $gust90minuteago;
$wsTrends[$i] ['dir']	= langtransstr($dir90minuteago);
$wsTrends[$i] ['hum']	= $hum90minuteago;
$wsTrends[$i] ['dew']	= $dew90minuteago;
$wsTrends[$i] ['baro']	= $baro90minuteago;
$wsTrends[$i] ['rain']	= $rain90minuteago;
$wsTrends[$i] ['sol']	= $VPsolar90minuteago;
$wsTrends[$i] ['uv']	= $VPuv90minuteago;
$i=10;
$wsTrends[$i] ['min']	= 105;
$wsTrends[$i] ['temp']	= $temp105minuteago;
$wsTrends[$i] ['wind']	= $wind105minuteago;
$wsTrends[$i] ['gust']	= $gust105minuteago;
$wsTrends[$i] ['dir']	= langtransstr($dir105minuteago);
$wsTrends[$i] ['hum']	= $hum105minuteago;
$wsTrends[$i] ['dew']	= $dew105minuteago;
$wsTrends[$i] ['baro']	= $baro105minuteago;
$wsTrends[$i] ['rain']	= $rain105minuteago;
$wsTrends[$i] ['sol']	= $VPsolar105minuteago;
$wsTrends[$i] ['uv']	= $VPuv105minuteago;
$i=11;
$wsTrends[$i] ['min']	= 120;
$wsTrends[$i] ['temp']	= $temp120minuteago;
$wsTrends[$i] ['wind']	= $wind120minuteago;
$wsTrends[$i] ['gust']	= $gust120minuteago;
$wsTrends[$i] ['dir']	= langtransstr($dir120minuteago);
$wsTrends[$i] ['hum']	= $hum120minuteago;
$wsTrends[$i] ['dew']	= $dew120minuteago;
$wsTrends[$i] ['baro']	= $baro120minuteago;
$wsTrends[$i] ['rain']	= $rain120minuteago;
$wsTrends[$i] ['sol']	= $VPsolar120minuteago;
$wsTrends[$i] ['uv']	= $VPuv120minuteago;

for ($i = 0; $i < 12; $i++) {
        $wsTrends[$i]['temp']	= wsConvertTemperature($wsTrends[$i]['temp']    , $from_temp);
        $wsTrends[$i]['dew']	= wsConvertTemperature($wsTrends[$i]['dew']     , $from_temp);
        $wsTrends[$i]['baro']	= wsConvertBaro($wsTrends[$i]['baro']           , $from_baro);
        $wsTrends[$i]['rain']   = wsConvertRainfall($wsTrends[$i]['rain']       , $from_rain);
        $wsTrends[$i]['wind']	= wsConvertWindspeed($wsTrends[$i]['wind']      , $from_wind);
        $wsTrends[$i]['gust']	= wsConvertWindspeed($wsTrends[$i]['gust']      , $from_wind);
}

$ws['check_ok']         = '3.00';

if (!isset ($SITE['soilUsed']) || $SITE['soilUsed'] == false) {ob_clean(); return; }
$from	= $uomtemp;
# Temp sensor 1 actual value
$ws['soilTempAct'][1]		= wsConvertTemperature($soiltemp, $from);  // convert and clean of units
# Temp sensor 1 maximum value for today month and year
$ws['soilTempMaxToday'][1]	= wsConvertTemperature($maxsoiltemp, $from);
$ws['soilTempMaxMonth'][1]	= wsConvertTemperature($mrecordhighsoil, $from);
$ws['soilTempMaxMonthTime'][1]	= wdYMD($mrecordhighsoilyear,$mrecordhighsoilmonth,$mrecordhighsoilday);
$ws['soilTempMaxYear'][1]	= wsConvertTemperature($yrecordhighsoil, $from);
$ws['soilTempMaxYearTime'][1]	= wdYMD($yrecordhighsoilyear,$yrecordhighsoilmonth,$yrecordhighsoilday);
$ws['soilTempMaxAlltime'][1]	= wsConvertTemperature($recordhighsoil, $from);
$ws['soilTempMaxAlltimeTime'][1]= wdYMD($recordhighsoilyear,$recordhighsoilmonth,$recordhighsoilday);
# Temp sensor 1 minimum value for today month and year
$ws['soilTempMinToday'][1]	= wsConvertTemperature($minsoiltemp, $from);
$ws['soilTempMinMonth'][1]	= wsConvertTemperature($mrecordlowsoil, $from);
$ws['soilTempMinMonthTime'][1]	= wdYMD($mrecordlowsoilyear,$mrecordlowsoilmonth,$mrecordlowsoilday);
$ws['soilTempMinYear'][1]	= wsConvertTemperature($yrecordlowsoil, $from);
$ws['soilTempMinYearTime'][1]	= wdYMD($yrecordlowsoilyear,$yrecordlowsoilmonth,$yrecordlowsoilday);
$ws['soilTempMinAlltime'][1]	= wsConvertTemperature($recordlowsoil, $from);
$ws['soilTempMinAlltimeTime'][1]= wdYMD($recordlowsoilyear,$recordlowsoilmonth,$recordlowsoilday);
# Temp sensor 2 actual value & Values and time for  min and max  for today - month and year 
$ws['soilTempAct'][2]		= wsConvertTemperature($VPsoiltemp2, $from);  // convert and clean of units
$ws['soilTempMaxToday'][2]	= wsConvertTemperature($hiVPsoiltemp2, $from);
$ws['soilTempMaxMonth'][2]	= wsConvertTemperature($mrecordhighsoil2, $from);
$ws['soilTempMaxMonthTime'][2]	= wdYMD($mrecordhighsoilyear2,$mrecordhighsoilmonth2,$mrecordhighsoilday2);
$ws['soilTempMaxYear'][2]	= wsConvertTemperature($yrecordhighsoil2, $from);
$ws['soilTempMaxYearTime'][2]	= wdYMD($yrecordhighsoilyear2,$yrecordhighsoilmonth2,$yrecordhighsoilday2);
$ws['soilTempMaxAlltime'][2]	= wsConvertTemperature($recordhighsoil2, $from);
$ws['soilTempMaxAlltimeTime'][2]= wdYMD($recordhighsoilyear2,$recordhighsoilmonth2,$recordhighsoilday2);
$ws['soilTempMinToday'][2]	= wsConvertTemperature($loVPsoiltemp2, $from);
$ws['soilTempMinMonth'][2]	= wsConvertTemperature($mrecordlowsoil2, $from);
$ws['soilTempMinMonthTime'][2]	= wdYMD($mrecordlowsoilyear2,$mrecordlowsoilmonth2,$mrecordlowsoilday2);
$ws['soilTempMinYear'][2]	= wsConvertTemperature($yrecordlowsoil2, $from);
$ws['soilTempMinYearTime'][2]	= wdYMD($yrecordlowsoilyear2,$yrecordlowsoilmonth2,$yrecordlowsoilday2);
$ws['soilTempMinAlltime'][2]	= wsConvertTemperature($recordlowsoil2, $from);
$ws['soilTempMinAlltimeTime'][2]= wdYMD($recordlowsoilyear2,$recordlowsoilmonth2,$recordlowsoilday2);
# Temp sensor 3 actual value & Values and time for  min and max  for today - month and year 
$ws['soilTempAct'][3]		= wsConvertTemperature($VPsoiltemp3, $from);  // convert and clean of units
$ws['soilTempMaxToday'][3]	= wsConvertTemperature($hiVPsoiltemp3, $from);
$ws['soilTempMaxMonth'][3]	= wsConvertTemperature($mrecordhighsoil3, $from);
$ws['soilTempMaxMonthTime'][3]	= wdYMD($mrecordhighsoilyear3,$mrecordhighsoilmonth3,$mrecordhighsoilday3);
$ws['soilTempMaxYear'][3]	= wsConvertTemperature($yrecordhighsoil3, $from);
$ws['soilTempMaxYearTime'][3]	= wdYMD($yrecordhighsoilyear3,$yrecordhighsoilmonth3,$yrecordhighsoilday3);
$ws['soilTempMaxAlltime'][3]	= wsConvertTemperature($recordhighsoil3, $from);
$ws['soilTempMaxAlltimeTime'][3]= wdYMD($recordhighsoilyear3,$recordhighsoilmonth3,$recordhighsoilday3);
$ws['soilTempMinToday'][3]	= wsConvertTemperature($loVPsoiltemp3, $from);
$ws['soilTempMinMonth'][3]	= wsConvertTemperature($mrecordlowsoil3, $from);
$ws['soilTempMinMonthTime'][3]	= wdYMD($mrecordlowsoilyear3,$mrecordlowsoilmonth3,$mrecordlowsoilday3);
$ws['soilTempMinYear'][3]	= wsConvertTemperature($yrecordlowsoil3, $from);
$ws['soilTempMinYearTime'][3]	= wdYMD($yrecordlowsoilyear3,$yrecordlowsoilmonth3,$yrecordlowsoilday3);
$ws['soilTempMinAlltime'][3]	= wsConvertTemperature($recordlowsoil3, $from);
$ws['soilTempMinAlltimeTime'][3]= wdYMD($recordlowsoilyear3,$recordlowsoilmonth3,$recordlowsoilday3);
# Temp sensor 4 actual value & Values and time for  min and max  for today - month and year 
$ws['soilTempAct'][4]		= wsConvertTemperature($VPsoiltemp4, $from);  // convert and clean of units
$ws['soilTempMaxToday'][4]	= wsConvertTemperature($hiVPsoiltemp4, $from);
$ws['soilTempMaxMonth'][4]	= wsConvertTemperature($mrecordhighsoil4, $from);
$ws['soilTempMaxMonthTime'][4]	= wdYMD($mrecordhighsoilyear4,$mrecordhighsoilmonth4,$mrecordhighsoilday4);
$ws['soilTempMaxYear'][4]	= wsConvertTemperature($yrecordhighsoil4, $from);
$ws['soilTempMaxYearTime'][4]	= wdYMD($yrecordhighsoilyear4,$yrecordhighsoilmonth4,$yrecordhighsoilday4);
$ws['soilTempMaxAlltime'][4]	= wsConvertTemperature($recordhighsoil4, $from);
$ws['soilTempMaxAlltimeTime'][4]= wdYMD($recordhighsoilyear4,$recordhighsoilmonth4,$recordhighsoilday4);
$ws['soilTempMinToday'][4]	= wsConvertTemperature($loVPsoiltemp4, $from);
$ws['soilTempMinMonth'][4]	= wsConvertTemperature($mrecordlowsoil4, $from);
$ws['soilTempMinMonthTime'][4]	= wdYMD($mrecordlowsoilyear4,$mrecordlowsoilmonth4,$mrecordlowsoilday4);
$ws['soilTempMinYear'][4]	= wsConvertTemperature($yrecordlowsoil4, $from);
$ws['soilTempMinYearTime'][4]	= wdYMD($yrecordlowsoilyear4,$yrecordlowsoilmonth4,$yrecordlowsoilday4);
$ws['soilTempMinAlltime'][4]	= wsConvertTemperature($recordlowsoil4, $from);
$ws['soilTempMinAlltimeTime'][4]= wdYMD($recordlowsoilyear4,$recordlowsoilmonth4,$recordlowsoilday4);
#
# Moisture sensor 1 actual value
$ws['moistAct'][1]		= $VPsoilmoisture;
# Moisture sensor 1 maximum value for today month and year
$ws['moistMaxToday'][1]		= $hiVPsoilmoisture;
$ws['moistMaxMonth'][1]		= $mrecordhighsoilmoist;
$ws['moistMaxMonthTime'][1]	= wdYMD($mrecordhighsoilmoistyear,$mrecordhighsoilmoistmonth,$mrecordhighsoilmoistday);
$ws['moistMaxYear'][1]		= $yrecordhighsoilmoist;
$ws['moistMaxYearTime'][1]	= wdYMD($yrecordhighsoilmoistyear,$yrecordhighsoilmoistmonth,$yrecordhighsoilmoistday);
$ws['moistMaxAlltime'][1]	= $recordhighsoilmoist;
$ws['moistMaxAlltimeTime'][1]	= wdYMD($recordhighsoilmoistyear,$recordhighsoilmoistmonth,$recordhighsoilmoistday);

# Moisture sensor 1 mimimum value for today  
$ws['moistMinToday'][1]		= $loVPsoilmoisture;
# Moisture sensor 1 date/time maximum occured
# Moisture sensor 2 actual value & Values and time for  min and max  for today - month and year 
$ws['moistAct'][2]		= $VPsoilmoisture2;
$ws['moistMaxToday'][2]		= $hiVPsoilmoisture2;
$ws['moistMinToday'][2]		= $loVPsoilmoisture2;
# Moisture sensor 3 actual value & Values and time for  min and max  for today - month and year 
$ws['moistAct'][3]		= $VPsoilmoisture3;
$ws['moistMaxToday'][3]		= $hiVPsoilmoisture3;
$ws['moistMinToday'][3]		= $loVPsoilmoisture3;
# Moisture sensor 4 actual value & Values and time for  min and max  for today - month and year 
$ws['moistAct'][4]		= $VPsoilmoisture4;
$ws['moistMaxToday'][4]		= $hiVPsoilmoisture4;
$ws['moistMinToday'][4]		= $loVPsoilmoisture4;
#
$ws['moistAvMonth'][1]	        = $avtempjansoil;	// Average soil temperature for january from your data
$ws['moistAvMonth'][2]	        = $avtempfebsoil;
$ws['moistAvMonth'][3]	        = $avtempmarsoil;
$ws['moistAvMonth'][4]	        = $avtempaprsoil;
$ws['moistAvMonth'][5]	        = $avtempmaysoil;
$ws['moistAvMonth'][6]	        = $avtempjunsoil;
$ws['moistAvMonth'][7]	        = $avtempjulsoil;
$ws['moistAvMonth'][8]	        = $avtempaugsoil;
$ws['moistAvMonth'][9]	        = $avtempsepsoil;
$ws['moistAvMonth'][10]	        = $avtempoctsoil;
$ws['moistAvMonth'][11]	        = $avtempnovsoil;
$ws['moistAvMonth'][12]	        = $avtempdecsoil;

$ws['moistAvMonthThisyear'][1]	= $avtempjannowsoil; // Average soil temperature for january from your data, this year
$ws['moistAvMonthThisyear'][2]	= $avtempfebnowsoil;
$ws['moistAvMonthThisyear'][3]	= $avtempmarnowsoil;
$ws['moistAvMonthThisyear'][4]	= $avtempaprnowsoil;
$ws['moistAvMonthThisyear'][5]	= $avtempmaynowsoil;
$ws['moistAvMonthThisyear'][6]	= $avtempjunnowsoil;
$ws['moistAvMonthThisyear'][7]	= $avtempjulnowsoil;
$ws['moistAvMonthThisyear'][8]	= $avtempaugnowsoil;
$ws['moistAvMonthThisyear'][9]	= $avtempsepnowsoil;
$ws['moistAvMonthThisyear'][10]	= $avtempoctnowsoil;
$ws['moistAvMonthThisyear'][11]	= $avtempnovnowsoil;
$ws['moistAvMonthThisyear'][12]	= $avtempdecnowsoil;

#-----------------------------------------------------------------------------------------
# leaf sensor 1 - 4
$ws['leafAct'][1]		= $VPleaf;
$ws['leafWetLast10'][1] 	= $leafminlast10min;	// Minutes last 10 minutes leaf wetness was above zero
$ws['leafWetLast60'][1] 	= $leafminlast60min;	// Minutes last 10 minutes leaf wetness was above zero
$ws['leafMaxToday'][1]		= $hiVPleaf;
$ws['leafMinToday'][1]		= $loVPleaf;
$ws['leafMaxMonth'][1]		= $mrecordhighleaf;
$ws['leafMaxYear'][1]		= $yrecordhighleaf;
$ws['leafMaxMonthTime'][1]	= wdYMD($mrecordhighleafyear,$mrecordhighleafmonth,$mrecordhighleafday);
$ws['leafMaxYearTime'][1]	= wdYMD($yrecordhighleafyear,$yrecordhighleafmonth,$yrecordhighleafday);
$ws['leafAct'][2]		= $VPleaf2;
$ws['leafHighToday'][2]		= $hiVPleaf2;
$ws['leafLowToday'][2]		= $loVPleaf2;
$ws['leafWetLast10'][2] 	= $leafminlast10min2;	// Minutes last 10 minutes leaf wetness was above zero
$ws['leafWetLast60'][2] 	= $leafminlast60min2;	// Minutes last 10 minutes leaf wetness was above zero
$ws['leafAct'][3]	        = $VPleaf3;
$ws['leafAct'][4]	        = $VPleaf4;
#-----------------------------------------------------------------------------------------
ob_clean();
}

// end of saratoga.php
?>
