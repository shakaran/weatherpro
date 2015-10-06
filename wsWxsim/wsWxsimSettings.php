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
$pageName	= 'wsWxsimSettings.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.02 2015-09-06';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------------------------
# 3.02 2015-09-06 minor adaptions 2.8 
#-----------------------------------------------------------------------------------------
# Hours when wxsim runs on the PC  You find that in wxsim ==> menu start ==> autorun
$updateHour 	= array(2,4,6,8,10,12,14,16,18,20);     // Hours when wxsim runs
$updateMin 	= 15;				        // minutes after the hour when wxsim uploads
#
# uom           = unit of measurement = units for temp baro etc.
$uoms 		= array ('C', 'mm','km/h', 'hPa','cm','km');    // for non US users
# $uoms         = array('F','in','mph','inhg','mb','mi');       // for US uoms 
#
# IMPORTANT! Units used as by wxsim, array ('C', 'mm','km/h', 'hPa','cm','km');
# temperature, precipitation, wind, pressure, snowdepth, visibility
# you find the units used by your copy ofWXSIM at the near end of the lastret.txt file
# as in the following example
/*
Units:                                                                                              
temperature: Celsius, precipitation: mm, snow: cm, wind: m/s                                        
thickness: meters, solar energy: Watt x hr/m^2, visibility: km                                      
Convective basis: N.W. European                                                                     
*/
# normaly you should set the units in WXSIM equal to the units in the rest of your scripts
#   
#-----------------------------------------------------------------------------------------
$wxsimDebug	= false; 		// for extra debug info inside html source
$wxsimERROR	= false;		// if unrecoverable error is found, we stop
#-------------------------------------------------------------------------------------
#
$dataFolder   	= $SITE['wxsimData'];
# names of the files uploaded by wxsim
$plaintext 	= $dataFolder.'plaintext.txt';
$fileToUse      = $dataFolder.$SITE['wxsim_file'];
if (!isset ($SITE['wxsimDir']) )  {$SITE['wxsimDir'] = './wsWxsim/';}
$scriptFolder 	= $SITE['wxsimDir'];
$cacheFolder	= $SITE['cacheDir'];
$javaFolder	= $SITE['javascriptsDir'];
#
# location of the icons used
$windIcons 	= $scriptFolder.'windIcons/'; 		// wind icons for  tables
$windIconsSmall = $scriptFolder.'windIconsSmall/'; 	// wind icons for  graphs (small version of the icons for the tables)
#
if (isset($SITE['wxsimIconsOwn']) && ($SITE['wxsimIconsOwn'] 	== true) ){ // other icons for graphs
	$iconsSmall	= './wsyrnofct/img/yrno_icons/'; 
	$SITE['wxsimIconsDir']= $iconsSmall;
}		
else  { $iconsSmall	= $SITE['defaultIconsSml']; }
#
$tempSimple     = $SITE['tempSimple'];
#
#  location of general icons as sunrise sunset, for template users
$img		= $SITE['imgDir'];
# date and timesettings
$timezone 	= $SITE['tz'];
$lat 		= $SITE['latitude'];
$long		= $SITE['longitude'];
$dateTimeFormat = $SITE['timeFormat'];
$timeFormat 	= $SITE['timeOnlyFormat'];
$dateFormat 	= $SITE['dateOnlyFormat'];
$dateLongFormat = isset($SITE['dateLongFormat'])? $SITE['dateLongFormat'] : 'l d F Y';
#
$zenith 	= 90+50/60;             // Zenit-setting for php suntimes
# Zenith' is the angle that the centre of the Sun makes to a line perpendicular to the Earth's surface. 
# The best Overall figure for zenith is 90+(50/60) degrees for true sunrise/sunset
$minuteOffset 	= 0;		        // will be calculated to make all timestamps multiple of 10 minutes 
# uoms wanted for output, normally the same as the input, for template users these units are loaded from the site-settings

$text   = array ('&deg;', ' ');						// 
# uomsto is the requested uom for the output to the visitor of the website
$uomsTo	= array(
        str_replace($text, '', $SITE['uomTemp']),
        str_replace(' ', '',   $SITE['uomRain']),
        str_replace(' ', '',   $SITE['uomWind']),
        str_replace(' ', '',   $SITE['uomBaro']),
        str_replace(' ', '',   $SITE['uomSnow']),
        str_replace(' ', '',   $SITE['uomDistance'])	);	
#
# When to display  extra information about temperature and so on in tables
$windchillDiff		= -4;		# How many degrees lower than temperature windchill needs to be before shown
$heatDiff 		= 4;		# How many degrees higher than temperature heat needs to be before shown
if ($uoms[0] <> $uomsTo[0]) {
	if ($uoms[0] == 'C') {
	$windchillDiff 	= 9*$windchillDiff/5;
	$heatDiff 	= 9*$heatDiff/5;}
	else {
	$windchillDiff 	= 5*$windchillDiff/9;
	$heatDiff 	= 5*$heatDiff/9;}
}
$minUV			= 1;		# Min UV level to be shown in hour-table
$noRain			= '-';	        # Show 0 mm rain as "No precip" or "-" in the tables?
#$noRain		= langtransstr('No precip');	
$thunderProb 		= true;		# Show thunderstormprobability % on hourtable?
$thunderCover 		= 35;		# Minimal cloudcover when thunderstorm probability is shown
#
if ($lat > 0) {
        $frostStart	= 10;		# Month when start show frost   Northern hemisphere
        $frostEnd 	= 4;}		# Month when stop show frost
else {  $frostStart	= 4;		# Month when start show frost   Soutern 
        $frostEnd 	= 10;	}	# Month when stop show frost
#
$topCount		= 8;		# How many forecasts shown in top-forecast (if used)
$windlimit		= 6;		# At wich (average) windspeed in Beaufort the extra Beaufort text is shown?
#
# Soil & Grass-forecast
$sgdepth1               = '4';
$sgdepth2               = '8';
if (isset($SITE['soilUsed']) && $SITE['soilUsed'] == true) { # we have soilsensors in use
	$sgdepth1	= $SITE['soilDepth_1'];
	if ($SITE['soilCount'] > 1) {
		$sgdepth2	= $SITE['soilDepth_2'];
	}
}
$sgdepth1               = wsConvertRainfall( $sgdepth1, $uoms[4], $uomsTo[4] ).$SITE['uomSnow']; # Depth for Soil 1
$sgdepth2               = wsConvertRainfall( $sgdepth2, $uoms[4], $uomsTo[4] ).$SITE['uomSnow']; # Depth for Soil 2
#
$soilWanted	        = true;		// set to false if no soil table is wanted even if wxsim supplies the data
#
# array with all possible fields and their specifications
$fields = array ();
# for every field there are the folowings specs
# loc 		= location in data row = fieldnumber. 0 = not used
# unit		= unit of measurement. For lastret.txt equals to defaults in settings. latest.csv contains own uom
# nameTxt	= fieldname used in  lastret.txt
# nameCsv	= fieldname used in latest.csv
$fields['temp'] 	= array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'AIR',	'nameCsv' => 'Temperature'	);
$fields['hum'] 		= array ('loc' => 0, 'unit' => '%',	'nameTxt' => '%RH',	'nameCsv' => 'Rel.Hum.'		);
$fields['tempMax'] 	= array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'TMAX',	'nameCsv' => 'Hi Temp'		);
$fields['tempMin'] 	= array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'TMIN',	'nameCsv' => 'Low Temp'		);
$fields['temp15M'] 	= array ('loc' => 0, 'unit' => 0,	'nameTxt' => '15M',	'nameCsv' => '15 m'			);  // removed (50 ft) Temperature
$fields['t850'] 	= array ('loc' => 0, 'unit' => 0,	'nameTxt' => '850T',	'nameCsv' => 'T_850 mb'		);
$fields['dew'] 		= array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'DEW',	'nameCsv' => 'Dew Pt.'		);
$fields['wBulb'] 	= array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'WET',	'nameCsv' => 'Wet Bulb'		);
$fields['chill'] 	= array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'WCF',	'nameCsv' => 'Wind Chl'		);
$fields['heat'] 	= array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'HT.I',	'nameCsv' => 'Heat Ind'		);
$fields['windSpeed']    = array ('loc' => 0, 'unit' => 2,	'nameTxt' => 'W.SP',	'nameCsv' => 'Wind Spd.'	);
$fields['gust']		= array ('loc' => 0, 'unit' => 2,	'nameTxt' => 'G10M',	'nameCsv' => '10 min Gust'	);
$fields['gust1Hr']	= array ('loc' => 0, 'unit' => 2,	'nameTxt' => 'G1HR',	'nameCsv' => '1 hr Gust'	);
$fields['windDir'] 	= array ('loc' => 0, 'unit' => '&deg;',	'nameTxt' => 'W.DIR',	'nameCsv' => 'Wind Dir.'	);
$fields['baro'] 	= array ('loc' => 0, 'unit' => 3,	'nameTxt' => 'SLP',	'nameCsv' => 'S.L.P.'		);
$fields['rain'] 	= array ('loc' => 0, 'unit' => 1,	'nameTxt' => 'PTOT',	'nameCsv' => 'Tot.Prcp'		);
$fields['snow'] 	= array ('loc' => 0, 'unit' => 4,	'nameTxt' => 'SN.C',	'nameCsv' => 'Snow Dpth'	);
$fields['visib'] 	= array ('loc' => 0, 'unit' => 5,	'nameTxt' => 'VIS',	'nameCsv' => 'VIS'		);
$fields['thunder'] 	= array ('loc' => 0, 'unit' => '%',	'nameTxt' => 'SWXO',	'nameCsv' => 'Severe index'	);
$fields['UV'] 		= array ('loc' => 0, 'unit' => 'index',	'nameTxt' => 'UVI',	'nameCsv' => 'UV Index'		);
$fields['solar'] 	= array ('loc' => 0, 'unit' => 'W/m^2',	'nameTxt' => 'S.IR',	'nameCsv' => 'Solar Rad'	);
$fields['tempGrass']    = array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'GRS',	'nameCsv' => 'Grass Temperature');
$fields['tempSurf']	= array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'SURF',	'nameCsv' => 'Soil Surface Temperature'	);
$fields['tempSoil1']    = array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'TSO1',	'nameCsv' => 'Soil Temperature Depth 1'	);
$fields['tempSoil2']    = array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'TSO2',	'nameCsv' => 'Soil Temperature Depth 2'	);
$fields['tempSoil3']    = array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'TSO3',	'nameCsv' => 'Soil Temperature Depth 3'	);
$fields['moist1']	= array ('loc' => 0, 'unit' => 'cb',  	'nameTxt' => 'SMT1',	'nameCsv' => 'Soil Tension Depth 1'	);
$fields['moist2']	= array ('loc' => 0, 'unit' => 'cb',	'nameTxt' => 'SMT2',	'nameCsv' => 'Soil Tension Depth 2'	);
$fields['moist3']	= array ('loc' => 0, 'unit' => 'cb',	'nameTxt' => 'SMT3',	'nameCsv' => 'Soil Tension Depth 3'	);
$fields['tscd'] 	= array ('loc' => 0, 'unit' => 'index',	'nameTxt' => 'TSCD',	'nameCsv' => 'Convection index'		);
$fields['skyCover']	= array ('loc' => 0, 'unit' => '%',	'nameTxt' => 'L.CD',	'nameCsv' => 'Sky Cov'		);
$fields['skyCover2']    = array ('loc' => 0, 'unit' => '%',	'nameTxt' => 'SKY',	'nameCsv' => 'Lower cloud cover');
$fields['level'] 	= array ('loc' => 0, 'unit' => 0,	'nameTxt' => 'LVL1',	'nameCsv' => 'T_Lvl 1'		);
$fields['vst'] 		= array ('loc' => 0, 'unit' => '?',	'nameTxt' => 'VST',	'nameCsv' => 'Vis Trans'	);
$fields['thk'] 		= array ('loc' => 0, 'unit' => '?',	'nameTxt' => 'THK',	'nameCsv' => '10-5 Thk'		);
$fields['tsmo'] 	= array ('loc' => 0, 'unit' => '?',	'nameTxt' => 'TSMO',	'nameCsv' => 'Convection index'	);
$fields['irt'] 		= array ('loc' => 0, 'unit' => '?',	'nameTxt' => 'I.RT',	'nameCsv' => 'xxxxxxxx'		);
$fields['cnd1'] 	= array ('loc' => 0, 'unit' => '?',	'nameTxt' => 'xxx',	'nameCsv' => 'WX Type 1'	);
$fields['cnd2'] 	= array ('loc' => 0, 'unit' => '?',	'nameTxt' => 'xxx',	'nameCsv' => 'WX Type 2'	);
#
if ($wxsimDebug) {echo '<pre>$fields = <br />';print_r ($fields);}
#
$longDays		= array ("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
$myLongDays		= array ();
for ($i = 0; $i < count($longDays); $i++) {$myLongDays[$i]	= langtransstr($longDays[$i]); }
$longMonths		= array ("January","February","March","April","May","June","July","August","September","October","November","December");
$myLongMonths	        = array ();
for ($i = 0; $i < count($longMonths); $i++) {$myLongMonths[$i]	= langtransstr($longMonths[$i]); }
#
# Returns whether needle was found in haystack
function wsFound($haystack, $needle){
$pos = strpos($haystack, $needle);
   if ($pos === false) {
   return false;
   } else {
   return true;
   }
}
?>