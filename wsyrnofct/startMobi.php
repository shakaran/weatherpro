<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'startMobi.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
# ---------THE FOLLOWING SETTINGS ARE COPIED FROM YOUR SETTINGS  -------
#
$yrnoID		= $SITE['yrnoID'];
$iconsOwn	= $SITE['yrnoIconsOwn'];
$yourArea	= $SITE['yourArea'];
$organ		= $SITE['organ'];
$latitude	= $SITE['latitude'];
$longitude	= $SITE['longitude'];
$charset        = $SITE['charset'];
$lower          = $SITE['textLowerCase'];
$tempSimple	= $SITE['tempSimple'];  
#
$uomTemp	= $SITE['uomTemp'];
$uomRain	= $SITE['uomRain'];
$uomWind 	= $SITE['uomWind'];
$uomBaro	= $SITE['uomBaro'];
$uomSnow        = $SITE['uomSnow'];
$uomDistance    = $SITE['uomDistance'];
#
$timeFormat	= $SITE['timeFormat'];
$timeOnlyFormat	= $SITE['timeOnlyFormat'];
$hourOnlyFormat	= $SITE['hourOnlyFormat'];
$dateOnlyFormat	= $SITE['dateOnlyFormat'];
$dateLongFormat	= $SITE['dateLongFormat'];
$timezone	= $SITE['tz'];
#
$defaultWidth	= '98%';
$insideTemplate = true;
$scriptDir      = './wsyrnofct/';
#
# --------------- END OF SETTINGS ----------------------------------------
#
# print version of script in the html of the generated page
#
$pageVersion	= '3.00 2014-09-13';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#
if ($SITE['uomTemp'] == '&deg;C') {$metric = true;} else {$metric = false;}
#
$script		= $scriptDir.'yrnosettings.php';	// we need hard coded directory path here because settings are not loaded yet.
echo '<!-- trying to load '.$script.' -->'.PHP_EOL;
include $script;
#
$script	        = $scriptDir.'yrnoCreateArr.php';
echo '<!-- trying to load '.$script.' -->'.PHP_EOL;
include $script;
$weather 	= new yrnoWeather ();
$returnArray 	= $weather->getWeatherData($yrnoID);
unset($weather);