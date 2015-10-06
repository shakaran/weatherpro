<?php
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
$pageName	= 'testtags.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '2.6 2014-10-10 11:55:45 ';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2014-09-22 release version 3.01 added ET conversion
# --------------------------------------- version ----------------------
$ws['tags.php']		= $pageName.'-'.$pageVersion;
# --------------------------------------- conditions--------------------
$weathercond 		= '%weathercond%';
$ws['wdCurCond'] 	= 'Sunny/Dry';
$ws['wdCurIcon']	= '2';			// Current icon number
$ws['wdMetarCcn']	= 'Dry';		// Current weather conditions from selected METAR
$ws['wdMetarCld']	= '%metarcloudreport%';		// Cloud METAR label from the metargif
# ------------------------------------------ units ---------------------
$wdDist 	= '%uomdistance%'; 	// = 'mi','km'  (for windrun variables)
# ------------------------------------------- date - time --------------
$wdDatefmt 	= 'd/m/y'; 		//  = 'd/m/y', 'm/d/y'
$wdSeconds	= '00';
$wdMinute 	= '03';	// Current minute '20'
$wdHour 	= '11';	// Current hour '17'
$wdDay 		= '15';		// Current day  '15'
$wdMonth 	= '04';	// Current month '06'
$wdYear 	= '2015';	// Current year '2012'
$ymd		= (string) $wdYear.$wdMonth.$wdDay;   // 
$ws['actTime']	= (string) $ymd.$wdHour.$wdMinute.$wdSeconds; // '20120523113945';
# ------------------------------------------ temperature ---------------
$to 	= $SITE['uomTemp'];
$from	= 'C'; 		//  = 'C', 'F',  (or  '°C', '°F', or '&deg;C', '&deg;F' )

$ws['tempMinTodayTime']	= wdDate('07:09');
$ws['tempMinYdayTime']	= wdDate('03:47');
$ws['tempMinMonthTime']	= wdYMD('2015','4','5');
$ws['tempMinYearTime']	= wdYMD('2015','2','7');
$ws['tempMaxTodayTime']	= wdDate('11:02'); 
$ws['tempMaxYdayTime']	= wdDate('15:18'); //yday
$ws['tempMaxMonthTime']	= wdYMD('2015','4','10');
$ws['tempMaxYearTime']	= wdYMD('2015','4','10');
$ws['dewpMinTodayTime']	= wdDate('06:55');
$ws['dewpMinYdayTime']	= wdDate('%mindewyestt%'); //yday
$ws['dewpMaxTodayTime']	= wdDate('11:00');
$ws['dewpMaxYdayTime']	= wdDate('%maxdewyestt%');  // yday
$ws['heatMaxTodayTime']	= wdDate('11:01');
$ws['heatMaxYdayTime']	= wdDate('%maxheatyestt%');
$ws['heatMaxMonthTime']	= wdYMD('2015','4','9');
$ws['heatMaxYearTime']	= wdYMD('2015','4','9');
$ws['chilMinTodayTime']	= wdDate('07:09');
$ws['chilMinYdayTime']	= wdDate('06:18'); // yday
$ws['chilMinMonthTime'] = wdYMD('2015','4','7','12','00');
$ws['chilMinYearTime']	= wdYMD('2015','2','6','12','00'); 

$ws['tempAct']		= wsConvertTemperature('20.3&deg;C', $from);  // convert and clean of units
$ws['tempActInside']	= wsConvertTemperature('22.1', $from);
$ws['tempActExtra1']	= wsConvertTemperature('%none%', $from);	
$ws['tempDelta']	= wsConvertTemperature('+3.4', $from);	
$ws['tempToday']	= wsConvertTemperature('%avtempsincemidnight%', $from);
$ws['tempMinToday']	= wsConvertTemperature('10.1&deg;C', $from);
$ws['tempMinYday']	= wsConvertTemperature('5.7', $from);
$ws['tempMinMonth']	= wsConvertTemperature('0.3', $from);
$ws['tempMinYear']	= wsConvertTemperature('-3.8', $from);
$ws['tempMaxToday']	= wsConvertTemperature('20.3&deg;C', $from);
$ws['tempMaxYday']	= wsConvertTemperature('23.2', $from);
$ws['tempMaxMonth']	= wsConvertTemperature('23.7', $from);
$ws['tempMaxYear']	= wsConvertTemperature('23.7', $from);

$ws['dewpAct']  	= wsConvertTemperature('11.5&deg;C', $from);
$ws['dewpDelta']	= wsConvertTemperature('+1.5', $from);
$ws['dewpMinToday']  	= wsConvertTemperature('7.6', $from);
$ws['dewpMinYday']  	= wsConvertTemperature('%mindewyest%', $from);
$ws['dewpMaxToday']  	= wsConvertTemperature('11.5', $from);
$ws['dewpMaxYday']  	= wsConvertTemperature('%maxdewyest%', $from);

$ws['heatAct']  	= wsConvertTemperature('24.8&deg;C', $from);
$ws['heatDelta']	= 0;
$ws['heatMaxToday']	= wsConvertTemperature('24.8', $from);
$ws['heatMaxYday']	= wsConvertTemperature('%maxheatyest%', $from);
$ws['heatMaxMonth']	= wsConvertTemperature('%mrecordhighheatindex%', $from);
$ws['heatMaxYear']	= wsConvertTemperature('%yrecordhighheatindex%', $from);
	
$ws['chilAct']		= wsConvertTemperature('20.3&deg;C', $from);
$ws['chilDelta']	= 0;
$ws['chilMinToday']	= wsConvertTemperature('10.1', $from);
$ws['chilMinYday']	= wsConvertTemperature('5.5', $from);
$ws['chilMinMonth']	= wsConvertTemperature('0.3', $from);
$ws['chilMinYear']	= wsConvertTemperature('-7.4', $from);

$ws['hudxAct'] 		= wsConvertTemperature('22.3', 'C');
$ws['hudxDelta'] 	= 0;
$ws['hudxMaxToday'] 	= wsConvertTemperature('%todayhihumidex%',  'C');

# ------------------------------------------ extreme temp   ------------
$ws['daysXHigh']	= '0';
$ws['daysHigh']		= '0';
$ws['daysLow']		= '0';
$ws['daysXLow']		= '0';
# ------------------------------------------ pressure / baro -----------
$to 	= $SITE['uomBaro'];
$from	= 'hPa'; 		//  = 'inHg', 'hPa', 'kPa', 'mb'

$ws['baroMinTodayTime']	= wdDate('10:56');
$ws['baroMinYdayTime']	= wdDate('00:06'); // ytd
$ws['baroMinMonthTime']	= wdYMD('2015','4','2');
$ws['baroMinYearTime']	= wdYMD('2015','1','29');
$ws['baroMaxTodayTime']	= wdDate('00:01');
$ws['baroMaxYdayTime']	= wdDate('%maxbaroyestt%');  // ytd
$ws['baroMaxMonthTime']	= wdYMD('2015','4','7');
$ws['baroMaxYearTime']	= wdYMD('%yrecordhighbaroyear%','%yrecordhighbaromonth%','%yrecordhighbaroday%');

$ws['baroAct'] 		= wsConvertBaro('1018.2 hpa', $from);
$ws['baroDelta']	= wsConvertBaro('-0.4', $from);
$ws['baroMinToday']	= wsConvertBaro('1018.2', $from);
$ws['baroMinYday']	= wsConvertBaro('1030.3', $from);
$ws['baroMinMonth']	= wsConvertBaro('1010.8', $from);
$ws['baroMinYear'] 	= wsConvertBaro('972.6', $from);	
$ws['baroMaxToday']	= wsConvertBaro('1021.8', $from);
$ws['baroMaxYday']	= wsConvertBaro('%maxbaroyest%', $from);
$ws['baroMaxMonth']	= wsConvertBaro('%mrecordhighbaro%', $from);
$ws['baroMaxYear'] 	= wsConvertBaro('%yrecordhighbaro%', $from);

# ------------------------------------------ humidity  -----------------
$ws['humiMinTodayTime ']= wdDate('10:46');
$ws['humiMinYdayTime ']	= wdDate('%minhumyestt%');   // ytd
$ws['humiMaxTodayTime'] = wdDate('07:32');
$ws['humiMaxYdayTime'] 	= wdDate('%maxhumyestt%');   // ytd

$ws['humiAct']		= '57'*1.0;
$ws['humiExtra']	= '%none%'*1.0;
$ws['humiDelta']	= '-7'*1.0;
$ws['humiMinToday'] 	= '56'*1.0;
$ws['humiMinYday'] 	= '%minhumyest%'*1.0;
$ws['humiMaxToday']	= '86'*1.0;
$ws['humiMaxYday']	= '%maxhumyest%'*1.0;

# ------------------------------------------ rain  ---------------------
$to 	= $SITE['uomRain'];
$from	= 'mm'; 		//  = 'mm', 'in'

$ws['rainDayMnth'] 	= '4';  	// 4 		Days with rain for the month
$ws['rainDayYear'] 	= '65';	// 65	Days with rain for the year

$ws['rainRateAct'] 	= wsConvertRainfall('0.0', $from);
$ws['rainRateToday'] 	= wsConvertRainfall('0.0', $from);		
$ws['rainHour']		= wsConvertRainfall('0.0', $from);
$ws['rainToday']	= wsConvertRainfall('0.0', $from);
$ws['rainYday']		= wsConvertRainfall('0.0', $from);
$ws['rainMonth']	= wsConvertRainfall('2.0 mm', $from);
$ws['rainYear']		= wsConvertRainfall('168.4 mm', $from);
$ws['rainDaysWithNo']	= '2';
$ws['rainWeek']		= wsConvertRainfall('1.8', $from);
# ------------------------------------------ EVAPOTRANSPIRATION --------
$ws['etToday'] 		= wsConvertRainfall('0.0', $from);
$ws['etYday'] 		= wsConvertRainfall('0.0', $from);
$ws['etMonth'] 		= wsConvertRainfall('0.0', $from);

# ------------------------------------------ wind  ---------------------
$to 	= $SITE['uomWind'];
$from	= 'kts'; 		//  = 'kts','mph','kmh','km/h','m/s','Bft'

$ws['windActDsc']	= 'WSW';
$ws['windBeafort']	= '0';
$ws['gustMaxTodayTime']	= wdDate('08:58');
$ws['gustMaxYdayTime']	= wdDate('16:59');
$ws['gustMaxMonthTime']	= wdYMD('2015','4','1');
$ws['gustMaxYearTime']	= wdYMD('2015','3','31');

$ws['windAct']		= wsConvertWindspeed('1.1', $from);
$ws['gustAct']		= wsConvertWindspeed('0.0', $from);
$ws['gustMaxHour']	= wsConvertWindspeed('19:12', $from);	
$ws['gustMaxToday']	= wsConvertWindspeed('5.2 kts', $from);
$ws['gustMaxYday']	= wsConvertWindspeed('13.0', $from); 
$ws['gustMaxMonth']	= wsConvertWindspeed('18.3', $from);
$ws['gustMaxYear']	= wsConvertWindspeed('25.2', $from);	

if ($ws['gustAct'] <= $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}
# ------------------------------------------  UV   ---------------------
$ws['uvMaxTodayTime'] 	= wdDate('10:59');
$ws['uvMaxYdayTime'] 	= wdDate('13:01');
$ws['uvMaxMonthTime'] 	= wdYMD('2015','4','11');
$ws['uvMaxYearTime'] 	= wdYMD('2015','4','11');

$ws['uvAct']		= '2.0';
$ws['uvMaxToday']	= '2.0';
$ws['uvMaxYday']	= '3.7';
$ws['uvMaxMonth']	= '4.0';
$ws['uvMaxYear']	= '4.0';
# ------------------------------------------ Solar  --------------------
$ws['solarMaxTodayTime']= wdDate('11:02');
$ws['solarMaxYdayTime'] = wdDate('14:10');
$ws['solarMaxMonthTime']= wdYMD('2015','4','8');
$ws['solarMaxYearTime'] = wdYMD('2015','4','8');

$ws['solarAct']		= '591'*1.0;
$ws['solActPerc']	= '%currentsolarpctplain%';
$ws['solarMaxToday']	= '591';
$ws['solarMaxYday']	= '759.0';
$ws['solarMaxMonth']	= '1002.0';
$ws['solarMaxYear']	= '1002.0';
# ------------------------------------------ cloud height --------------
$to	= $SITE['uomHeight'];
$from	= 'ft';

$ws['cloudHeight']	= wsConvertDistance('3625',$from);
# ------------------------------------------ forecasts -----------------
$ws['fcstWD'] 	  	= '2';
$ws['fcstTxt'] 	        = str_replace('_',' ','increasing_clouds_with_little_temp._change._precipitation_possible_within_24_to_48_hrs.');

# ------------------------------------------ sun and moon --------------
$ws['sunrise']		= date($SITE['timeOnlyFormat'],strtotime('06:45:00'));
$ws['sunset']		= date($SITE['timeOnlyFormat'],strtotime('20:33:00'));
$ws['moonrise']		= date($SITE['timeOnlyFormat'],strtotime('05:03:00'));
$ws['moonset']		= date($SITE['timeOnlyFormat'],strtotime('16:24:00'));
$ws['lunarPhasePerc']	= '16.18%'*1.0;
$ws['lunarAge']		= substr('27', 9, 3); // Waning Crescent Moon %moonlunation% 
# ------------------------------------------ some more -----------------
$ws['wsVersion']	= 'version WD_console_9.5 b ---';
$ws['wsHardware'] 	= '';   // unknown
$ws['wsUptime']		= ' 11:03:06 up 337 days,  2:41,  0 users,  load average: 0.65, 0.62, 0.59';

if (!isset ($wsTrends)){$wsTrends = array() ;}
$i=0;
$wsTrends[$i] ['min']	= 0;
$wsTrends[$i] ['temp']	= '%temp0minuteago%';
$wsTrends[$i] ['wind']	= '1';
$wsTrends[$i] ['gust']	= '3';
$wsTrends[$i] ['dir']	= langtransstr('WSW');
$wsTrends[$i] ['hum']	= '57';
$wsTrends[$i] ['dew']	= '11.4';
$wsTrends[$i] ['baro']	= '1018.2';
$wsTrends[$i] ['rain']	= '0.0';
$wsTrends[$i] ['sol']	= '%VPsolar0minuteago%';
$wsTrends[$i] ['uv']	= '%VPuv0minuteago%';
$i=1;
$wsTrends[$i] ['min']	= 5;
$wsTrends[$i] ['temp']	= '19.8';
$wsTrends[$i] ['wind']	= '1';
$wsTrends[$i] ['gust']	= '3';
$wsTrends[$i] ['dir']	= langtransstr('WSW');
$wsTrends[$i] ['hum']	= '57';
$wsTrends[$i] ['dew']	= '11.0';
$wsTrends[$i] ['baro']	= '1018.2';
$wsTrends[$i] ['rain']	= '0.0';
$wsTrends[$i] ['sol']	= '580';
$wsTrends[$i] ['uv']	= '1.9';
$i=2;
$wsTrends[$i] ['min']	= 10;
$wsTrends[$i] ['temp']	= '19.5';
$wsTrends[$i] ['wind']	= '1';
$wsTrends[$i] ['gust']	= '3';
$wsTrends[$i] ['dir']	= langtransstr('SW');
$wsTrends[$i] ['hum']	= '57';
$wsTrends[$i] ['dew']	= '10.8';
$wsTrends[$i] ['baro']	= '1018.3';
$wsTrends[$i] ['rain']	= '0.0';
$wsTrends[$i] ['sol']	= '570';
$wsTrends[$i] ['uv']	= '1.9';
$i=3;
$wsTrends[$i] ['min']	= 15;
$wsTrends[$i] ['temp']	= '19.3';
$wsTrends[$i] ['wind']	= '2';
$wsTrends[$i] ['gust']	= '3';
$wsTrends[$i] ['dir']	= langtransstr('WSW');
$wsTrends[$i] ['hum']	= '56';
$wsTrends[$i] ['dew']	= '10.3';
$wsTrends[$i] ['baro']	= '1018.3';
$wsTrends[$i] ['rain']	= '0.0';
$wsTrends[$i] ['sol']	= '561';
$wsTrends[$i] ['uv']	= '1.8';
$i=4;
$wsTrends[$i] ['min']	= 20;
$wsTrends[$i] ['temp']	= '19.0';
$wsTrends[$i] ['wind']	= '1';
$wsTrends[$i] ['gust']	= '3';
$wsTrends[$i] ['dir']	= langtransstr('SSW');
$wsTrends[$i] ['hum']	= '57';
$wsTrends[$i] ['dew']	= '10.3';
$wsTrends[$i] ['baro']	= '1018.3';
$wsTrends[$i] ['rain']	= '0.0';
$wsTrends[$i] ['sol']	= '550';
$wsTrends[$i] ['uv']	= '1.7';
$i=5;
$wsTrends[$i] ['min']	= 30;
$wsTrends[$i] ['temp']	= '18.5';
$wsTrends[$i] ['wind']	= '2';
$wsTrends[$i] ['gust']	= '3';
$wsTrends[$i] ['dir']	= langtransstr('WSW');
$wsTrends[$i] ['hum']	= '58';
$wsTrends[$i] ['dew']	= '10.1';
$wsTrends[$i] ['baro']	= '1018.4';
$wsTrends[$i] ['rain']	= '0.0';
$wsTrends[$i] ['sol']	= '526';
$wsTrends[$i] ['uv']	= '1.6';
$i=6;
$wsTrends[$i] ['min']	= 45;
$wsTrends[$i] ['temp']	= '17.6';
$wsTrends[$i] ['wind']	= '1';
$wsTrends[$i] ['gust']	= '2';
$wsTrends[$i] ['dir']	= langtransstr('SSW');
$wsTrends[$i] ['hum']	= '62';
$wsTrends[$i] ['dew']	= '10.2';
$wsTrends[$i] ['baro']	= '1018.6';
$wsTrends[$i] ['rain']	= '0.0';
$wsTrends[$i] ['sol']	= '492';
$wsTrends[$i] ['uv']	= '1.4';
$i=7;
$wsTrends[$i] ['min']	= 60;
$wsTrends[$i] ['temp']	= '16.7';
$wsTrends[$i] ['wind']	= '1';
$wsTrends[$i] ['gust']	= '2';
$wsTrends[$i] ['dir']	= langtransstr('SW');
$wsTrends[$i] ['hum']	= '64';
$wsTrends[$i] ['dew']	= '9.9';
$wsTrends[$i] ['baro']	= '1018.6';
$wsTrends[$i] ['rain']	= '0.0';
$wsTrends[$i] ['sol']	= '457';
$wsTrends[$i] ['uv']	= '1.0';
$i=8;
$wsTrends[$i] ['min']	= 75;
$wsTrends[$i] ['temp']	= '15.8';
$wsTrends[$i] ['wind']	= '1';
$wsTrends[$i] ['gust']	= '3';
$wsTrends[$i] ['dir']	= langtransstr('SW');
$wsTrends[$i] ['hum']	= '68';
$wsTrends[$i] ['dew']	= '9.9';
$wsTrends[$i] ['baro']	= '1018.6';
$wsTrends[$i] ['rain']	= '0.0';
$wsTrends[$i] ['sol']	= '418';
$wsTrends[$i] ['uv']	= '1.1';
$i=9;
$wsTrends[$i] ['min']	= 90;
$wsTrends[$i] ['temp']	= '14.9';
$wsTrends[$i] ['wind']	= '1';
$wsTrends[$i] ['gust']	= '4';
$wsTrends[$i] ['dir']	= langtransstr('SW');
$wsTrends[$i] ['hum']	= '70';
$wsTrends[$i] ['dew']	= '9.5';
$wsTrends[$i] ['baro']	= '1018.7';
$wsTrends[$i] ['rain']	= '0.0';
$wsTrends[$i] ['sol']	= '380';
$wsTrends[$i] ['uv']	= '0.9';
$i=10;
$wsTrends[$i] ['min']	= 105;
$wsTrends[$i] ['temp']	= '14.0';
$wsTrends[$i] ['wind']	= '1';
$wsTrends[$i] ['gust']	= '3';
$wsTrends[$i] ['dir']	= langtransstr('WSW');
$wsTrends[$i] ['hum']	= '73';
$wsTrends[$i] ['dew']	= '9.2';
$wsTrends[$i] ['baro']	= '1018.7';
$wsTrends[$i] ['rain']	= '0.0';
$wsTrends[$i] ['sol']	= '338';
$wsTrends[$i] ['uv']	= '0.8';
$i=11;
$wsTrends[$i] ['min']	= 120;
$wsTrends[$i] ['temp']	= '13.2';
$wsTrends[$i] ['wind']	= '2';
$wsTrends[$i] ['gust']	= '3';
$wsTrends[$i] ['dir']	= langtransstr('SW');
$wsTrends[$i] ['hum']	= '76';
$wsTrends[$i] ['dew']	= '9.0';
$wsTrends[$i] ['baro']	= '1018.9';
$wsTrends[$i] ['rain']	= '0.0';
$wsTrends[$i] ['sol']	= '294';
$wsTrends[$i] ['uv']	= '0.7';

#--------------------- soil leaf measurement ---------------------------
$to 	= $SITE['uomTemp'];
$from	= 'C'; 		//  = 'C', 'F',  (or  '°C', '°F', or '&deg;C', '&deg;F' )

# Temp sensor 1 actual value
$ws['soilTempAct'][1]		= wsConvertTemperature('10.6', $from);  // convert and clean of units
# Temp sensor 1 maximum value for today month and year
$ws['soilTempMaxToday'][1]	= wsConvertTemperature('11.1', $from);
$ws['soilTempMaxMonth'][1]	= wsConvertTemperature('%mrecordhighsoil%', $from);
$ws['soilTempMaxMonthTime'][1]	= wdYMD('%mrecordhighsoilyear%','%mrecordhighsoilmonth%','%mrecordhighsoilday%');
$ws['soilTempMaxYear'][1]	= wsConvertTemperature('%yrecordhighsoil%', $from);
$ws['soilTempMaxYearTime'][1]	= wdYMD('%yrecordhighsoilyear%','%yrecordhighsoilmonth%','%yrecordhighsoilday%');
$ws['soilTempMaxAlltime'][1]	= wsConvertTemperature('%recordhighsoil%', $from);
$ws['soilTempMaxAlltimeTime'][1]= wdYMD('%recordhighsoilyear%','%recordhighsoilmonth%','%recordhighsoilday%');
# Temp sensor 1 minimum value for today month and year
$ws['soilTempMinToday'][1]	= wsConvertTemperature('10.6', $from);
$ws['soilTempMinMonth'][1]	= wsConvertTemperature('%mrecordlowsoil%', $from);
$ws['soilTempMinMonthTime'][1]	= wdYMD('%mrecordlowsoilyear%','%mrecordlowsoilmonth%','%mrecordlowsoilday%');
$ws['soilTempMinYear'][1]	= wsConvertTemperature('%yrecordlowsoil%', $from);
$ws['soilTempMinYearTime'][1]	= wdYMD('%yrecordlowsoilyear%','%yrecordlowsoilmonth%','%yrecordlowsoilday%');
$ws['soilTempMinAlltime'][1]	= wsConvertTemperature('%recordlowsoil%', $from);
$ws['soilTempMinAlltimeTime'][1]= wdYMD('%recordlowsoilyear%','%recordlowsoilmonth%','%recordlowsoilday%');
# Temp sensor 2 actual value & Values and time for  min and max  for today - month and year 
$ws['soilTempAct'][2]		= wsConvertTemperature('10', $from);  // convert and clean of units
$ws['soilTempMaxToday'][2]	= wsConvertTemperature('10.0', $from);
$ws['soilTempMaxMonth'][2]	= wsConvertTemperature('%mrecordhighsoil2%', $from);
$ws['soilTempMaxMonthTime'][2]	= wdYMD('%mrecordhighsoilyear2%','%mrecordhighsoilmonth2%','%mrecordhighsoilday2%');
$ws['soilTempMaxYear'][2]	= wsConvertTemperature('%yrecordhighsoil2%', $from);
$ws['soilTempMaxYearTime'][2]	= wdYMD('%yrecordhighsoilyear2%','%yrecordhighsoilmonth2%','%yrecordhighsoilday2%');
$ws['soilTempMaxAlltime'][2]	= wsConvertTemperature('%recordhighsoil2%', $from);
$ws['soilTempMaxAlltimeTime'][2]= wdYMD('%recordhighsoilyear2%','%recordhighsoilmonth2%','%recordhighsoilday2%');
$ws['soilTempMinToday'][2]	= wsConvertTemperature('10.0', $from);
$ws['soilTempMinMonth'][2]	= wsConvertTemperature('%mrecordlowsoil2%', $from);
$ws['soilTempMinMonthTime'][2]	= wdYMD('%mrecordlowsoilyear2%','%mrecordlowsoilmonth2%','%mrecordlowsoilday2%');
$ws['soilTempMinYear'][2]	= wsConvertTemperature('%yrecordlowsoil2%', $from);
$ws['soilTempMinYearTime'][2]	= wdYMD('%yrecordlowsoilyear2%','%yrecordlowsoilmonth2%','%yrecordlowsoilday2%');
$ws['soilTempMinAlltime'][2]	= wsConvertTemperature('%recordlowsoil2%', $from);
$ws['soilTempMinAlltimeTime'][2]= wdYMD('%recordlowsoilyear2%','%recordlowsoilmonth2%','%recordlowsoilday2%');
# Temp sensor 3 actual value & Values and time for  min and max  for today - month and year 
$ws['soilTempAct'][3]		= wsConvertTemperature('255', $from);  // convert and clean of units
$ws['soilTempMaxToday'][3]	= wsConvertTemperature('-50.0', $from);
$ws['soilTempMaxMonth'][3]	= wsConvertTemperature('%mrecordhighsoil3%', $from);
$ws['soilTempMaxMonthTime'][3]	= wdYMD('%mrecordhighsoilyear3%','%mrecordhighsoilmonth3%','%mrecordhighsoilday3%');
$ws['soilTempMaxYear'][3]	= wsConvertTemperature('%yrecordhighsoil3%', $from);
$ws['soilTempMaxYearTime'][3]	= wdYMD('%yrecordhighsoilyear3%','%yrecordhighsoilmonth3%','%yrecordhighsoilday3%');
$ws['soilTempMaxAlltime'][3]	= wsConvertTemperature('%recordhighsoil3%', $from);
$ws['soilTempMaxAlltimeTime'][3]= wdYMD('%recordhighsoilyear3%','%recordhighsoilmonth3%','%recordhighsoilday3%');
$ws['soilTempMinToday'][3]	= wsConvertTemperature('100.0', $from);
$ws['soilTempMinMonth'][3]	= wsConvertTemperature('%mrecordlowsoil3%', $from);
$ws['soilTempMinMonthTime'][3]	= wdYMD('%mrecordlowsoilyear3%','%mrecordlowsoilmonth3%','%mrecordlowsoilday3%');
$ws['soilTempMinYear'][3]	= wsConvertTemperature('%yrecordlowsoil3%', $from);
$ws['soilTempMinYearTime'][3]	= wdYMD('%yrecordlowsoilyear3%','%yrecordlowsoilmonth3%','%yrecordlowsoilday3%');
$ws['soilTempMinAlltime'][3]	= wsConvertTemperature('%recordlowsoil3%', $from);
$ws['soilTempMinAlltimeTime'][3]= wdYMD('%recordlowsoilyear3%','%recordlowsoilmonth3%','%recordlowsoilday3%');
# Temp sensor 4 actual value & Values and time for  min and max  for today - month and year 
$ws['soilTempAct'][4]		= wsConvertTemperature('255', $from);  // convert and clean of units
$ws['soilTempMaxToday'][4]	= wsConvertTemperature('-50.0', $from);
$ws['soilTempMaxMonth'][4]	= wsConvertTemperature('%mrecordhighsoil3%', $from);
$ws['soilTempMaxMonthTime'][4]	= wdYMD('%mrecordhighsoilyear3%','%mrecordhighsoilmonth3%','%mrecordhighsoilday3%');
$ws['soilTempMaxYear'][4]	= wsConvertTemperature('%yrecordhighsoil3%', $from);
$ws['soilTempMaxYearTime'][4]	= wdYMD('%yrecordhighsoilyear3%','%yrecordhighsoilmonth3%','%yrecordhighsoilday3%');
$ws['soilTempMaxAlltime'][4]	= wsConvertTemperature('%recordhighsoil3%', $from);
$ws['soilTempMaxAlltimeTime'][4]= wdYMD('%recordhighsoilyear3%','%recordhighsoilmonth3%','%recordhighsoilday3%');
$ws['soilTempMinToday'][4]	= wsConvertTemperature('100.0', $from);
$ws['soilTempMinMonth'][4]	= wsConvertTemperature('%mrecordlowsoil3%', $from);
$ws['soilTempMinMonthTime'][4]	= wdYMD('%mrecordlowsoilyear3%','%mrecordlowsoilmonth3%','%mrecordlowsoilday3%');
$ws['soilTempMinYear'][4]	= wsConvertTemperature('%yrecordlowsoil3%', $from);
$ws['soilTempMinYearTime'][4]	= wdYMD('%yrecordlowsoilyear3%','%yrecordlowsoilmonth3%','%yrecordlowsoilday3%');
$ws['soilTempMinAlltime'][4]	= wsConvertTemperature('%recordlowsoil3%', $from);
$ws['soilTempMinAlltimeTime'][4]= wdYMD('%recordlowsoilyear3%','%recordlowsoilmonth3%','%recordlowsoilday3%');
#
# Moisture sensor 1 actual value
$ws['moistAct'][1]		= '16.0';
# Moisture sensor 1 maximum value for today month and year
$ws['moistMaxToday'][1]		= '16.0';
$ws['moistMaxMonth'][1]		= '%mrecordhighsoilmoist%';
$ws['moistMaxMonthTime'][1]	= wdYMD('%mrecordhighsoilmoistyear%','%mrecordhighsoilmoistmonth%','%mrecordhighsoilmoistday%');
$ws['moistMaxYear'][1]		= '%yrecordhighsoilmoist%';
$ws['moistMaxYearTime'][1]	= wdYMD('%yrecordhighsoilmoistyear%','%yrecordhighsoilmoistmonth%','%yrecordhighsoilmoistday%');
$ws['moistMaxAlltime'][1]	= '%recordhighsoilmoist%';
$ws['moistMaxAlltimeTime'][1]	= wdYMD('%recordhighsoilmoistyear%','%recordhighsoilmoistmonth%','%recordhighsoilmoistday%');

# Moisture sensor 1 mimimum value for today  
$ws['moistMinToday'][1]		= '0.0';
# Moisture sensor 1 date/time maximum occured
# Moisture sensor 2 actual value & Values and time for  min and max  for today - month and year 
$ws['moistAct'][2]		= '14.0';
$ws['moistMaxToday'][2]		= '15.0';
$ws['moistMinToday'][2]		= '0.0';
# Moisture sensor 3 actual value & Values and time for  min and max  for today - month and year 
$ws['moistAct'][3]		= '255.0';
$ws['moistMaxToday'][3]		= '0.0';
$ws['moistMinToday'][3]		= '0.0';
# Moisture sensor 4 actual value & Values and time for  min and max  for today - month and year 
$ws['moistAct'][4]		= '255.0';
$ws['moistMaxToday'][4]		= '0.0';
$ws['moistMinToday'][4]		= '0.0';
#
$ws['moistAvMonth'][1]	        = '%avtempjansoil%';	// Average soil temperature for january from your data
$ws['moistAvMonth'][2]	        = '%avtempfebsoil%';
$ws['moistAvMonth'][3]	        = '%avtempmarsoil%';
$ws['moistAvMonth'][4]	        = '%avtempaprsoil%';
$ws['moistAvMonth'][5]	        = '%avtempmaysoil%';
$ws['moistAvMonth'][6]	        = '%avtempjunsoil%';
$ws['moistAvMonth'][7]	        = '%avtempjulsoil%';
$ws['moistAvMonth'][8]	        = '%avtempaugsoil%';
$ws['moistAvMonth'][9]	        = '%avtempsepsoil%';
$ws['moistAvMonth'][10]	        = '%avtempoctsoil%';
$ws['moistAvMonth'][11]	        = '%avtempnovsoil%';
$ws['moistAvMonth'][12] 	= '%avtempdecsoil%';

$ws['moistAvMonthThisyear'][1]	= '%avtempjannowsoil%'; // Average soil temperature for january from your data, this year
$ws['moistAvMonthThisyear'][2]	= '%avtempfebnowsoil%';
$ws['moistAvMonthThisyear'][3]	= '%avtempmarnowsoil%';
$ws['moistAvMonthThisyear'][4]	= '%avtempaprnowsoil%';
$ws['moistAvMonthThisyear'][5]	= '%avtempmaynowsoil%';
$ws['moistAvMonthThisyear'][6]	= '%avtempjunnowsoil%';
$ws['moistAvMonthThisyear'][7]	= '%avtempjulnowsoil%';
$ws['moistAvMonthThisyear'][8]	= '%avtempaugnowsoil%';
$ws['moistAvMonthThisyear'][9]	= '%avtempsepnowsoil%';
$ws['moistAvMonthThisyear'][10]	= '%avtempoctnowsoil%';
$ws['moistAvMonthThisyear'][11]	= '%avtempnovnowsoil%';
$ws['moistAvMonthThisyear'][12]	= '%avtempdecnowsoil%';

#----------------------------------------------------------------------
# leaf sensor 1 - 4
$ws['leafAct'][1]		= '15.0';
$ws['leafWetLast10'][1] 	= '%leafminlast10min%';	// Minutes last 10 minutes leaf wetness was above zero
$ws['leafWetLast60'][1] 	= '%leafminlast60min%';	// Minutes last 10 minutes leaf wetness was above zero
$ws['leafMaxToday'][1]		= '%hiVPleaf%';
$ws['leafMinToday'][1]		= '%loVPleaf%';
$ws['leafMaxMonth'][1]		= '%mrecordhighleaf%';
$ws['leafMaxYear'][1]		= '%yrecordhighleaf%';
$ws['leafMaxMonthTime'][1]	= wdYMD('%mrecordhighleafyear%','%mrecordhighleafmonth%','%mrecordhighleafday%');
$ws['leafMaxYearTime'][1]	= wdYMD('%yrecordhighleafyear%','%yrecordhighleafmonth%','%yrecordhighleafday%');
$ws['leafAct'][2]		= '255.0';
$ws['leafHighToday'][2]		= '%hiVPleaf2%';
$ws['leafLowToday'][2]		= '%loVPleaf2%';
$ws['leafWetLast10'][2] 	= '%leafminlast10min2%';	// Minutes last 10 minutes leaf wetness was above zero
$ws['leafWetLast60'][2] 	= '%leafminlast60min2%';	// Minutes last 10 minutes leaf wetness was above zero
$ws['leafAct'][3]	        = '255.0';
$ws['leafAct'][4]	        = '0.0';
#-----------------------------------------------------------------------------------------
$ws['check_ok']         = '3.00';
// end of testtags.txt/testtags.php
?>
