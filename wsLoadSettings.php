<?php  
ini_set('display_errors', 'On');   
error_reporting(E_ALL);
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsLoadSettings.php';
$pageVersion	= '3.20 2015-10-01';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) { $SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName; }
if (!isset($pathString)) {$pathString='';}
if (!function_exists ('ws_message') )
{
        function ws_message ($message,$always=false,&$string=false) 
        {
                global $wsDebug, $SITE;
                $echo	= $always;
                if ( $echo == false && isset ($wsDebug) && $wsDebug == true ) 			{$echo = true;}
                if ( $echo == false && isset ($SIE['wsDebug']) && $SIE['wsDebug'] == true ) 	{$echo = true;}
                if ( $echo == true  && $string === false) {echo $message.PHP_EOL;}
                if ( $echo == true  && $string <> false) {$string .= $message.PHP_EOL;}
        }
}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->',true,$pathString);
#---------------------------------------------------------------------------
# '3.20 2015-10-01 Release 2.8 version, error in date format removed
#---------------------------------------------------------------------------
$ws_arr_supp_wx         = array ('CU', 'CW', 'DW', 'MB', 'MH', 'VW','WC', 'WD', 'WL', 'WS', 'WV', 'MP');
# Set all defaults
$SITE['noaaPage']       = $SITE['ecPage']       = $SITE['cwopPage']     = 'no';  // set to default / no values
$SITE['canada']         = $SITE['europe']       = $SITE['america']      = $SITE['other'] = false;
$SITE['belgium']        = $SITE['netherlands']  = $SITE['warnArea']     = false; // set to default / no values
#
$SITE['wdPage'] =       $SITE['cwPage']         = $SITE['wdlPage']      = $SITE['wlPage'] = 'no';
$SITE['wcPage'] =       $SITE['cuPage']         = $SITE['mhPage']       = $SITE['mbPage'] = 'no';
$SITE['MeteoplugPage'] =$SITE['cltrPage']       = $SITE['trendPage']    = $SITE['mwPage'] = 'no';
$SITE['graphsPage'] =   $SITE['wsYTags']  = 'no';

$SITE['cookieName']	= 'weatherv4';

$SITE['colorStyles']	= array ('weather adapted', 'green','blue','pastel','red','orange','none','ws_clouds','ws_cloudsn','ws_mist','ws_moon','ws_pclouds','ws_rain','ws_snow','ws_storm','ws_sun','ws_thunder');
if (!defined('ENT_HTML5')) {define ('ENT_HTML5' , (16|32) );}
$SITE['htmlVersion']	= ENT_HTML5; 	// html version of this template
$SITE['commaDecimal']	= false; 	// most europeans use a comma for a decimal point. others a point
$SITE['curlFollow']	= 'false';	// for sites using safe mode
#---------------------------------------------------------------------------
$SITE['imgDir']		= 'img/';		// directory under topfolder used for general images 
$SITE['cacheDir']	= 'cache/';		// directory to cache files 
$SITE['metarDir']	= 'metar/';		// directory for metar scripts 
$SITE['javascriptsDir']	= 'javaScripts/';	// all general javascripts
$SITE['forecastsDir']	= 'forecasts/';		//
$SITE['iconsDir']	= 'wsIcons/';
$SITE['defaultIconsDir']= $SITE['iconsDir'].'default_icons/';
$SITE['defaultIconsSml']= $SITE['iconsDir'].'default_icons_small/';
$SITE['windIcons']	= $SITE['iconsDir'].'windIcons/';
$SITE['windIconsSmall']	= $SITE['iconsDir'].'windIconsSmall/';
$SITE['yrnoFcst']       = 'wsyrnofct/startMobi.php'; 
#---------------------------------------------------------------------------
# load user settings
#---------------------------------------------------------------------------
ws_message ( '<!-- module wsLoadSettings.php ('.__LINE__.'): loading wsUserSettings.php  -->',true,$pathString);
include '_my_texts/wsUserSettings.php';
#
if (isset($_REQUEST['debug']) || $SITE['wsDebug'] == true) {
        $SITE['wsDebug']        = true;
	$wsDebug	        = true;
	ini_set('display_errors', 'On');   error_reporting(E_ALL);	
	ws_message ( '<!-- module wsLoadSettings.php  ('.__LINE__.'): debug is switched on by user request - error reporting ALL -->',true,$pathString);
	$SITE['colorNumber']	= '3';
} 
else {  $SITE['wsDebug']        = false;
        ini_set('display_errors', NULL);  error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED); 
        $wsDebug = false;
}
if ($SITE['region'] <> 'europe') { //  only works  in europe
	$SITE['ewnID'] 	= false; $SITE['ewnMember'] = false;
	$SITE['hwaID']  = false; $SITE['hwaMember'] = false;
}      //  only works  in europe
#
if (isset($_REQUEST['wp']))  { 
        $wp     = substr(strtoupper($_REQUEST['wp']).$SITE['WXsoftware'],0,2);
        if (in_array ($wp, $ws_arr_supp_wx )) {
                $SITE['switch_wp']      = $SITE['WXsoftware']   = $wp;
                ws_message ( '<!-- module wsLoadSettings.php  ('.__LINE__.'): user switches the weatherprogram to '.$SITE['WXsoftware'].' -->',true,$pathString);
                $extraP                 ='&amp;wp='.$SITE['WXsoftware'];
        }       
}
if (!isset ($SITE['WXsoftware']) )      {
        $SITE['WXsoftware']    = 'CU';         
        ws_print_warning( 'WARNING - Please set your weather_program. Defaulted to '.$SITE['WXsoftware']);
}
if ($SITE['sideDisplay'] == false && $SITE['menuPlace'] == 'V') {$SITE['sideDisplay'] = true; };	
# 
if (!isset ($SITE['curCondFrom']))      {
        $SITE['curCondFrom']   = 'metar';      
        ws_print_warning( 'WARNING - Please set your current conddition source. Defaulted to '.$SITE['curCondFrom']);
}
if ($SITE['curCondFrom'] == 'wd' && ($SITE['WXsoftware'] <> 'WD' && $SITE['WXsoftware'] <> 'CW')) {$SITE['curCondFrom'] = 'yahoo';}
#
if ($SITE['skipTop']) {$skiptopText = '#data-area';} else {$skiptopText = '';}
#
#---------------------------------------------------------------------------
#      W A R N I N G S
#---------------------------------------------------------------------------
$SITE['warningsEuro']	= $SITE['warningsNOAA']	= $SITE['warningsNWS']	= $SITE['warningsec']	= false;  
$SITE['warnImg']        = false;  // set all default no
#---------------------------------------------------------------------------
#
if ($SITE['region'] ==	'america') {            # U S A
        $SITE['america']        = true;
	if ($SITE['useCurly'] == true) {
		$SITE['warningScript']	= './usa/nws-alerts/wsnws-alerts.php';
		$SITE['warningsNWS']	= true;
	} else {
		$SITE['warningScript']	= './usa/noaa_warn/noaaWarning.php';
		$SITE['warningsNOAA']	=  $SITE['warnings'] ;
		$SITE['warnImg']	= './wsIcons/NOAA_Icons_small/';
	}
} elseif ($SITE['region'] == 'canada') {         #   C A N A D A
         $SITE['canada']                = true;
	 $SITE['warningScript']	        = './canada/ec_warning.php';
	 $SITE['warningsec']	        = $SITE['warnings'];
} elseif ($SITE['region'] ==	'europe') {     #  E U R O P E
        $SITE['europe']         = true;
	$SITE['warningScript']	= './europe/wrnWarningv3.php';		// Euro
	$SITE['warningsEuro']	= $SITE['warnPage'];
	$SITE['warnImg']	= './img/wrnImages/warn_';
} else {        // region = other
        $SITE['other']          = true;
        $SITE['warningsMenu'] 	= false;
        $SITE['warnings'] 	= false;
        $SITE['warnPage']       = false;
        $SITE['warningScript']	= './other/warning.php';   
}
#							        // for more detailed info when there is a warning displayed
$SITE['noaawarningURL']	= 'http://alerts.weather.gov/cap/wwaatmget.php?x='.$SITE['warnArea'].'&y=1';
$SITE['EUwarningURL']	= 'http://www.meteoalarm.eu/index3.php?area='.$SITE['warnArea'].'&day=0&lang=ne_NL';
#
if  ($SITE['warnings'] <> true) {
        $SITE['warnPage']       = false;
        $SITE['warningsEuro']	= $SITE['warningsNOAA']	= $SITE['warningsNWS']	= $SITE['warningsec']	= false;  
}
#---------------------------------------------------------------------------
# mobile site
# detection which mobile device is used is done in index.php by loading mobi/ws_check_mobi.php
#---------------------------------------------------------------------------
$SITE['mobileDir']	= 'mobi/';
$SITE['mobileSite']	= $SITE['mobileDir'].'mobi.php';  	// for switching to mobile site; set to "" when no mobile support is needed
#---------------------------------------------------------------------------
# Ajax
#---------------------------------------------------------------------------
$SITE['imgAjaxDir']	= 'ajaxImages/';		// directory for ajaxImages with trailing slash
$SITE['wsAjaxScript']   = $SITE['javascriptsDir'].'ajax.js';	// for AJAX enabled display
$SITE['wsAjaxDataLoad'] = 'wsAjaxDataLoad_v3.php';      // load fresh data for ajax page at users site
$SITE['ajaxGizmojs']    = $SITE['javascriptsDir'].'ajaxgizmo.js'; 	// rotate gizmo info
$SITE['ajaxGizmo'] 	= 'wsAjaxGizmo.php'; 		// default Giozmo
#---------------------------------------------------------------------------
# Which scripts to use to process the supplied data
#---------------------------------------------------------------------------
$SITE['functions']	= 'wsFunctions.php';  		// weather functions, override
$SITE['langFunc']	= 'wsLangFunctions.php';	// general functions for languages
$SITE['menuXml'] 	= 'wsMenuData.xml';		// menu processes 'incMenuDataWilsele.xml';
$SITE['menuLoad'] 	= 'wsMenuLoad.php';
$SITE['spidersTxt']	= 'spiders.txt';
$SITE['uvScript']	= 'uvforecastv3.php';	        // worldwide forecast script for UV Index
$SITE['sideInc']	= 'wsSideColom.php';
$SITE['bottomInc']	= 'wsBottom.php';
$SITE['footer']  	= 'wsFooter.php';
#---------------------------------------------------------------------------
#  script names for conversion of weatherprogram data to website/ajax data
#---------------------------------------------------------------------------
$SITE['getData']	= 'wsDataGet.php';		// data from ws tags to intermidiate array
$SITE['loadData']	= 'wsDataLoad.php';		// load data into variables (mostly ajax)
#---------------------------------------------------------------------------
# Website CSS files
#---------------------------------------------------------------------------
$SITE['CSSscreen']	= 'styleMain30.css';		// general stylesheet all pages
$SITE['CSSprint']	= 'stylePrint20.css';		// general stylesheet all pages for printing
$SITE['CSStable']	= 'styleTable20.css';		// general stylesheet all tables
$SITE['CSSmenuHor']	= 'styleMenuDrop20.css';	// stylesheet horizontal drop down menus
$SITE['CSSmenuVer']	= 'styleMenuVert20x.css';	// stylesheet vertical fly-out menus
$SITE['CSSmood']	= 'styleMood20.css';		// stylesheet for adapting colors based on current  weathercondition

$SITE['noChoiceBackup']	= $SITE['noChoice'];
$SITE['menuPlaceBackup']= $SITE['menuPlace'];
$SITE['colorBackup']  	= $SITE['colorNumber'];
$SITE['headerBackup']  	= $SITE['header'];
$SITE['langBackup']	= $SITE['lang'];

if (isset($_REQUEST['ipad']) )	{       // modify standard settings if page formatting ipad is requested
        $SITE['ipad']           = true;
        $SITE['sideDisplay']	= false;
        $SITE['bottomDisplay']	= false;											
        $SITE['stripAll']	= true;	
        $SITE['stripMenu']	= true;	
        $SITE['bannerTop']	= false;
        $SITE['bannerBottom']	= false;
        $SITE['warnings']       = false;
        $SITE['warningTxt']     = false;
} else {$SITE['ipad']           = false;}
#
$SITE['supported_regions'] = array ('america','canada','europe','other');
if (!in_array($SITE['region'], $SITE['supported_regions']) ) {$SITE['region'] = 'europe';}
#
$SITE['supportedUnits'] = array ();
$SITE['supportedUnits'] ['temp'] = array('&deg;C', '&deg;F');
$SITE['supportedUnits'] ['baro'] = array(' hPa',   ' inHg',' mb' );
$SITE['supportedUnits'] ['wind'] = array(' km/h',  ' mph', ' kts', ' m/s',	);
$SITE['supportedUnits'] ['rain'] = array(' mm',	   ' in' );
$SITE['supportedUnits'] ['snow'] = array(' cm',	   ' in' );
#
$region	= $SITE['region'];
#
if (!in_array($SITE['uomTemp'], $SITE['supportedUnits'] ['temp']) ){
        if ($region == 'europe') 
                {$SITE['uomTemp'] = $SITE['supportedUnits']['temp'][0];
        } else  {$SITE['uomTemp'] = $SITE['supportedUnits']['temp'][1];}
}
if (!in_array($SITE['uomBaro'], $SITE['supportedUnits'] ['baro']) ){
        if ($region == 'europe') 
                {$SITE['uomBaro'] = $SITE['supportedUnits']['baro'][0];
        } else  {$SITE['uomBaro'] = $SITE['supportedUnits']['baro'][1];}
}
if (!in_array($SITE['uomWind'], $SITE['supportedUnits'] ['wind']) ){
        if ($region == 'europe') 
                {$SITE['uomWind'] = $SITE['supportedUnits']['wind'][0];
        } else  {$SITE['uomWind'] = $SITE['supportedUnits']['wind'][1];}
}
if (!in_array($SITE['uomRain'], $SITE['supportedUnits'] ['rain']) ){
        if ($region == 'europe') 
                {$SITE['uomRain'] = $SITE['supportedUnits']['rain'][0];
        } else  {$SITE['uomRain'] = $SITE['supportedUnits']['rain'][1];}
}
if (!in_array($SITE['uomSnow'], $SITE['supportedUnits'] ['snow']) ){
        if ($region == 'europe') 
                {$SITE['uomSnow'] = $SITE['supportedUnits']['snow'][0];
        } else  {$SITE['uomSnow'] = $SITE['supportedUnits']['snow'][1];}
}
#
# ------------------------   check if we are allowed to set cookie and process all cookies
#
$uomBackup = array ();
$uomBackup['uomTemp']   = $SITE['uomTemp'];
$uomBackup['uomBaro'] 	= $SITE['uomBaro'];
$uomBackup['uomWind'] 	= $SITE['uomWind'];
$uomBackup['uomRain']	= $SITE['uomRain'];
$uomBackup['uomSnow']	= $SITE['uomSnow'];
$uomBackup['uomDistance']= $SITE['uomDistance'];
$uomBackup['uomPerHour']= $SITE['uomPerHour'];
$uomBackup['uomHeight'] = $SITE['uomHeight'];

if (!isset ($SITE['fctOrg'] ) ) { $SITE['fctOrg'] = 'yahoo';}
$SITE['fctOrgBackup']	= $SITE['fctOrg'];

#
if ($SITE['cookieSupport'] == true) {
	ws_message ( '<!-- module wsLoadSettings.php ('.__LINE__.'): loading wsCookie.php   -->',false,$pathString);
	include 'wsCookie.php' ;
}
$uomTemp	= $SITE['uomTemp'];
$uomBaro	= $SITE['uomBaro'];
$uomRain	= $SITE['uomRain'];
$uomSnow	= $SITE['uomSnow'];
$uomDistance    = $SITE['uomDistance'];
$uomWind	= $SITE['uomWind'];
$uomPerHour	= $SITE['uomPerHour'];
$uomHeight	= $SITE['uomHeight'];

# set the Timezone abbreviation automatically based on $SITE['tz'];
if (!function_exists('date_default_timezone_set')) {
	 putenv("TZ=" . $SITE['tz']);
} else {
	 date_default_timezone_set($SITE['tz']);
}
$SITE['tzName']	= date("T",time());
#
#---------------------------------------------------------------------------
# Automatic Info we might need
#---------------------------------------------------------------------------
if(isset($_SERVER['REMOTE_ADDR']))   {$SITE['REMOTE_ADDR']	= $_SERVER['REMOTE_ADDR'];}
if(isset($_SERVER['REMOTE_HOST']))   {$SITE['REMOTE_HOST']	= $_SERVER['REMOTE_HOST'];}
if(isset($_SERVER['DOCUMENT_ROOT'])) {$SITE['WEBROOT']		= $_SERVER['DOCUMENT_ROOT'];}
if(isset($_SERVER['REQUEST_URI']))   {$SITE['REQURI']		= $_SERVER['REQUEST_URI'];}
if(isset($_SERVER['SERVER_NAME']))   {$SITE['SERVERNAME']	= $_SERVER['SERVER_NAME'];}
#---------------------------------------------------------------------------
#
if ($SITE['fctOrg'] == 'yahoo') {$SITE['yahooPage'] = true;}
if ($SITE['fctOrg'] == 'metno') {$SITE['metnoPage'] = true;}
if ($SITE['fctOrg'] == 'wxsim') {$SITE['wxsimPage'] = true;}
if ($SITE['fctOrg'] == 'hwa')   {$SITE['hwaPage']   = true;}
if ($SITE['fctOrg'] == 'noaa')  {$SITE['noaaPage']  = true;}
if ($SITE['fctOrg'] == 'ec')    {$SITE['ecPage']    = true;}
#
if ($SITE['region'] == 'america')       {$SITE['hwaPage'] = $SITE['ecPage']  = false; }
if ($SITE['region'] == 'canada')        {$SITE['hwaPage'] = $SITE['noaaPage']= false; }
if ($SITE['region'] == 'other')         {$SITE['hwaPage'] = $SITE['noaaPage']= $SITE['ecPage']  = false; }
if ($SITE['region'] == 'europe')        {$SITE['noaaPage']= $SITE['ecPage']  = false; }
if ($SITE['belgium'] == false && $SITE['netherlands'] == false) {$SITE['hwaPage'] = false;}
#
$string1  = '';
if(isset($_SERVER['HTTP_HOST']))   {$string1     .= $_SERVER['HTTP_HOST'];}
if(isset($_SERVER['PHP_SELF']))    {$string1     .= $_SERVER['PHP_SELF'];}
$SITE['siteUrl']= 'http://'.str_replace ('index.php','',$string1);
#
if (isset ($index) && $index) {check_topfolder ($wsDebug);}
#
if (!isset ($SITE['uploadDir']) )       {$SITE['uploadDir']	=  'upload'.$SITE['WXsoftware'].'/';}
if (!isset ($SITE['clientrawDir']) )    {$SITE['clientrawDir']	=  'upload'.$SITE['WXsoftware'].'/';}
if (!isset ($SITE['graphImageDir']) )   {$SITE['graphImageDir']	=  'upload'.$SITE['WXsoftware'].'/';}

$SITE['MeteoplugPage'] 	= $SITE['wdlPage'] = $SITE['cltrPage'] = 'no';
$SITE['generatePage']   = 'no';
if ($SITE['meteoplug']) {$SITE['MeteoplugPage'] = 'yes';}
if ($SITE['wd_live'])   {$SITE['wdlPage']       = $SITE['cltrPage'] = 'yes';}
if ($SITE['meteoware']) {$SITE['mwPage']        = 'yes';}
#
if (!isset ($SITE['wuID']) || !$SITE['wuID'] || $SITE['wuID'] == '') { $SITE['wuHistPage'] = 'no'; } else {$SITE['wuHistPage'] = 'yes';}
#
$SITE['meteowareFile']  = './mwlive/mwliveRT.php?wp='.$SITE['WXsoftware'];
$SITE['yrnoXmlName']	= 'yowindowRT.php?wp='.$SITE['WXsoftware'];
$SITE['alltime_values'] = true;                // weatherprogram supplies all-time values for temp and so on
#---------------------------------------------------------------------------
$save_to_cache          = true;

$SITE['wp_scripts']     = $SITE['uploadDir'];

switch ($SITE['WXsoftware']) {
  
    case 'MH':  #               required settings for     M E T E O H U B   
#
	$SITE['generatePage']	= 'yes';
	$SITE['trendPage'] 	= 'yes';	 
	$SITE['graphsPage'] 	= 'yes';
#
	$SITE['WXsoftwareURL']  = 'http://wiki.meteohub.de/Main_Page';
	$SITE['WXsoftwareLongName']= 'Meteohub';
	$SITE['WXsoftwareIcon'] = 'img/meteohub.jpg';
	
	$SITE['realtime']	= 'cltrw';
        $SITE['realtime_file']	= $SITE['clientrawDir'].'clientraw.txt';
	$SITE['wp_scripts']     = 'scriptsMH/';
	$SITE['wsTags'] 	= $SITE['uploadDir'].'tagsMH.txt';	
	$SITE['ydayTags']  	= $SITE['uploadDir'].'tagsYdayMH.txt';
	
	$SITE['process']        = $SITE['wp_scripts'].'tagsMH.php'; 
	$SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'tags.mh.html';	
	$SITE['wsYTagsSrc']  	= $SITE['wp_scripts'].'tagsyday.mh.html';	
    break;
#---------------------------------------------------------------------------
case 'MB':      #               required settings for   M E T E O B R I D G E   
#
	$SITE['mbPage'] 	= 'yes';
	$SITE['trendPage'] 	= 'yes';
	$SITE['graphsPage'] 	= 'yes';
	$SITE['cltrPage']       = $SITE['wdlPage']      = 'no';
	$SITE['wd_live']        = false;
#
	$SITE['WXsoftwareURL']  = 'http://www.meteobridge.com/wiki/index.php/Main_Page';
	$SITE['WXsoftwareLongName']= 'Meteobridge';
	$SITE['WXsoftwareIcon'] = 'img/meteobridge.jpg';

	$SITE['realtime']	= 'http';
        $SITE['realtime_file']	= $SITE['clientrawDir'].'realtime.txt';
	$SITE['wp_scripts']     = 'scriptsMB/';
	$SITE['wsTags'] 	= $SITE['uploadDir'].'tagsMB.txt';
	$SITE['process']        = $SITE['wp_scripts'].'tagsMB.php'; 
	$SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'tags.mb.txt';			
        break;

#---------------------------------------------------------------------------
case 'MP':      #               required settings for  Meteoplug
#
#        $SITE['DavisVP']  	= false;  	// false as Meteoplug does not support Davis forecast text in xml
#
	$SITE['generatePage']	= 'yes';
	$SITE['MeteoplugPage'] 	= $SITE['meteoplug'] 	= true;
	$SITE['wdlPage'] 	= $SITE['cltrPage'] 	= 'no';
	$SITE['wd_live']        = false;
	$SITE['alltime_values'] = true;
#
	$SITE['WXsoftwareURL']  = 'http://wiki.meteoplug.com/Main_Page';
	$SITE['WXsoftwareLongName'] = 'Meteoplug';
	$SITE['WXsoftwareIcon'] = 'img/meteoplug.jpg';
#
        $SITE['realtime']	= 'none';
	$SITE['wp_scripts']     = 'scriptsMP/';       
	$SITE['wsTags'] 	= $SITE['uploadDir'].'tagsMP.txt'; 
	$SITE['moonSet']  	= 'inc/astronomy.php'; 	// to calculate moon set & rise values
        $SITE['cacheMP']        = 300;                  // cache time max allowed in seconds
	$SITE['process']        = $SITE['wp_scripts'].'tagsMP.php'; 
        $SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'tags.mp.txt';
        $save_to_cache          = false;
        $SITE['meteoplugID']  	= '';
        
 #       echo 'halt'; exit;
        break;

#---------------------------------------------------------------------------
case 'WD':      #               required settings for   W E A T H E R   D I S P L A Y  
#
	$SITE['wdPage'] 	= 'yes';
	$SITE['trendPage'] 	= 'yes';
	$SITE['graphsPage'] 	= 'yes';
	$SITE['MeteoplugPage'] 	= 'no';
#
	$SITE['WXsoftwareURL']  = 'http://www.weather-display.com/';
	$SITE['WXsoftwareLongName'] = 'Weather Display';
	$SITE['WXsoftwareIcon'] = 'img/wd.jpg';
#
	$SITE['realtime']	= 'cltrw';
	$SITE['realtime_file']	= $SITE['clientrawDir'].'clientraw.txt';
	$SITE['wp_scripts']     = 'scriptsWD/';
	if (isset ($SITE['use_testtags'])  && $SITE['use_testtags']) {
	        $SITE['wsTags'] 	= $SITE['uploadDir'].'testtags.php'; 
 	        $SITE['process']        = 'ws_testtags.php';
  	        $SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'testtags.txt';
  	} else {
 	        $SITE['wsTags'] 	= $SITE['uploadDir'].'tagsWD.txt'; 
 	        $SITE['process']        = $SITE['wp_scripts'].'tagsWD.php';
  	        $SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'tags.wd.txt'; 	
  	}
        break;
#---------------------------------------------------------------------------
case 'CW':      #               required settings for   C O N S O L E  WD  (= on raspberryPI)
#
	$SITE['cwPage'] 	= 'yes';
	$SITE['trendPage'] 	= 'yes';
	$SITE['wxsimPage']	= 'no';
	$SITE['alltime_values'] = false;
	$SITE['MeteoplugPage'] 	= 'no';
#
	$SITE['WXsoftwareURL']  = 'http://www.weather-display.com/';
	$SITE['WXsoftwareLongName'] = 'consoleWD';	
	$SITE['WXsoftwareIcon'] = 'img/consolewd.png';
#
	$SITE['realtime']	= 'cltrw';
	$SITE['realtime_file']	= $SITE['clientrawDir'].'clientraw.txt';
	$SITE['wp_scripts']     = 'scriptsCW/';
	if (isset ($SITE['use_testtags'])  && $SITE['use_testtags']) {
	        $SITE['wsTags'] 	= $SITE['uploadDir'].'testtags.php'; 
 	        $SITE['process']        = 'ws_testtags.php';
  	        $SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'wxlocal-testtags.html';
  	} else {
 	        $SITE['wsTags'] 	= $SITE['uploadDir'].'tagsCW.txt'; 
 	        $SITE['process']        = $SITE['wp_scripts'].'tagsCW.php';
  	        $SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'wxlocal.html'; 	
  	}
        break;
#---------------------------------------------------------------------------
case 'CU':      #               required settings for   C U M U L U S
#
	$SITE['cuPage'] 	= 'yes';
	$SITE['trendPage'] 	= 'yes';
	$SITE['graphsPage'] 	= 'yes';
	$SITE['MeteoplugPage'] 	= $SITE['wdlPage'] = $SITE['cltrPage'] = 'no';
	$SITE['wd_live']        = $SITE['meteoplug']= false;
#
	$SITE['WXsoftwareURL']  = 'http://sandaysoft.com/products/cumulus';	
	$SITE['WXsoftwareLongName'] = 'Cumulus';
	$SITE['WXsoftwareIcon'] = 'img/cumulus.gif';
#
	$SITE['realtime']	= 'json';
	$SITE['realtime_file']	= $SITE['clientrawDir'].'realtimeTags.txt';

	$SITE['wsTags'] 	= $SITE['uploadDir'].'tagsCU.txt'; 	
	$SITE['wp_scripts']     = 'scriptsCU/';       
	$SITE['process']        = $SITE['wp_scripts'].'tagsCU.php';
	$SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'tags.cu.txt';
        break;
#---------------------------------------------------------------------------
case 'WL':      #               required settings for   W E A T H E R L I N K
#
	$SITE['wlPage'] 	= 'yes';			
	$SITE['graphsPage'] 	= 'yes';
	$SITE['alltime_values'] = false;
	$SITE['MeteoplugPage'] 	= $SITE['wdlPage'] = $SITE['cltrPage'] = 'no';
	$SITE['wd_live']        = $SITE['meteoplug']= false;
#
	$SITE['WXsoftwareURL']  = 'http://www.davisnet.com/index.asp';	
	$SITE['WXsoftwareLongName'] = 'WeatherLink';
	$SITE['WXsoftwareIcon'] = 'img/weatherlink.png';
#
	$SITE['realtime']	= 'weatherlink';
	$SITE['realtime_file']	= $SITE['clientrawDir'].'realtimev3.txt';
	$SITE['wp_scripts']     = 'scriptsWL/';
	$SITE['wsTags'] 	= $SITE['uploadDir'].'tags.wl.txt'; 
	$SITE['ydayTags']  	= $SITE['uploadDir'].'tagsyday.wl.txt';
	$SITE['moonSet']  	= 'inc/astronomy.php'; 	// to calculate moon set & rise values
	
	$SITE['process']        = $SITE['wp_scripts'].'tagsWL.php';  
	$SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'tags.wl.htx';	
	$SITE['wsYTagsSrc']  	= $SITE['wp_scripts'].'tagsyday.wl.htx';	

        if (!ws_date_format()) {ws_print_warning( 'WARNING - Please set the date format correct in your settings');} 
        break;
#---------------------------------------------------------------------------
case 'WC':      #               required settings for   W E A T H E R   C A T  (for Mac)
#
	$SITE['wcPage'] 	= 'yes';			
	$SITE['graphsPage'] 	= 'yes';
	$SITE['MeteoplugPage'] 	= $SITE['wdlPage'] = $SITE['cltrPage'] = 'no';
	$SITE['wd_live']        = $SITE['meteoplug']= false;
#
	$SITE['WXsoftwareURL']  = 'http://trixology.com/weathercat/';	
	$SITE['WXsoftwareLongName'] = 'WeatherCat';
	$SITE['WXsoftwareIcon'] = 'img/weathercat.png';
#
	$SITE['realtime']	= 'json';
	$SITE['realtime_file']	= $SITE['clientrawDir'].'realtime.wc.txt';
	$SITE['wp_scripts']     = 'scriptsWC/';
	$SITE['wsTags'] 	= $SITE['uploadDir'].'tags.wc.txt'; 
	$SITE['moonSet']  	= 'inc/astronomy.php'; 	// to calculate moon set & rise values
	$SITE['process']        = $SITE['wp_scripts'].'tagsWC.php';
	$SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'tags.wc.txt';		
        break;
#---------------------------------------------------------------------------
case 'DW':      #               required settings for   WeatherLink.com
#---------------------------------------------------------------------------
#
        $SITE['DavisVP']  	= false;  	// false as wl.com does not support Davis forecast text in xml
#
	$SITE['MeteoplugPage'] 	= $SITE['wdlPage'] = $SITE['cltrPage'] = 'no';
	$SITE['wd_live']        = $SITE['meteoplug']= false;
	$SITE['alltime_values'] = false;
#
	$SITE['WXsoftwareURL']  = 'http://www.weatherlink.com/user/'.$SITE['wlink_key'].'/index.php';	
	$SITE['WXsoftwareLongName'] = 'Davis Weatherlink.Com';
	$SITE['WXsoftwareIcon'] = 'img/wl_top.png';
#
        $SITE['realtime']	= 'none';
	$SITE['wp_scripts']     = 'scriptsDW/';       
	$SITE['wsTags'] 	= $SITE['wp_scripts'].'tagsWLCOM.php'; 
	$SITE['ydayTags']  	= $SITE['uploadDir'].'tagsydayWLCOM.txt';
	$SITE['moonSet']  	= 'inc/astronomy.php'; 	// to calculate moon set & rise values
        $SITE['cacheDW']        = 140;                  // cache time max allowed in seconds
	$SITE['process']        = $SITE['wp_scripts'].'tagsWLCOM.php'; 
        $SITE['wsTagsSrc'] 	= '';
        $SITE['wsYTagsSrc']  	= $SITE['wp_scripts'].'tagsyday.cron.txt';	
        $save_to_cache          = false;
        $SITE['weatherlinkID']  = $SITE['wlink_key'];
        break;
#---------------------------------------------------------------------------
case 'WS':      #               required settings for   WSWIN / 
#
        $SITE['trendPage'] 	= 'yes';		// trendpage 
	$SITE['MeteoplugPage'] 	= $SITE['cltrPage'] = 'no';
	$SITE['wd_live']        = $SITE['meteoplug']= false;
	$SITE['alltime_values'] = false;
	$SITE['WXsoftwareURL']  = 'http://www.pc-wetterstation.de/en1index.html';	
	$SITE['WXsoftwareLongName'] = 'WSWIN';
	$SITE['WXsoftwareIcon'] = 'img/wswin.gif';

        $SITE['realtime']	= 'cltrw';
        $SITE['realtime_file']	= $SITE['clientrawDir'].'clientraw.txt';

	$SITE['wp_scripts']     = 'scriptsWS/';	
	$SITE['wsTags'] 	= $SITE['uploadDir'].'tagsws.txt'; 
	$SITE['ydayTags']  	= $SITE['uploadDir'].'tagsydayWS.txt';

        $SITE['process']        = $SITE['wp_scripts'].'tagsWS.php';
        $SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'tags.ws.txt';
        $SITE['wsYTagsSrc']  	= $SITE['wp_scripts'].'tagsyday.cron.txt';	

        break;
#---------------------------------------------------------------------------
case 'WV':      #               required settings for   WVIEW / 
#
	$SITE['WXsoftwareURL']  = 'http://www.wviewweather.com/';	
	$SITE['WXsoftwareLongName'] = 'WVIEW';
	$SITE['WXsoftwareIcon'] = 'img/wview.png';
	$SITE['alltime_values'] = false;
	$SITE['MeteoplugPage'] 	= $SITE['wdlPage'] = $SITE['cltrPage'] = 'no';
	$SITE['wd_live']        = $SITE['meteoplug']= false;
        $SITE['realtime']	= 'none';         
	$SITE['wsTags'] 	= $SITE['uploadDir'].'tagsWV.htm'; 
	
#       $SITE['wview_day_txt']  = $SITE['uploadDir'].'tags.htm';
#	$SITE['wsCronTags']  	= $SITE['uploadDir'].'yesterdayTagsWVIEW.php';
	$SITE['ydayTags']  	= $SITE['uploadDir'].'tagsydayWV.txt';
	$SITE['moonSet']  	= 'inc/astronomy.php'; 	// to calculate moon set & rise values
	$SITE['wp_scripts']     = 'scriptsWV/';
        $SITE['process']        = $SITE['wp_scripts'].'tagsWV.php'; 
        $SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'tagsWV.htx';
        $SITE['wsYTagsSrc']  	= $SITE['wp_scripts'].'tagsyday.cron.txt';		
#
        if (!ws_date_format()) {ws_print_warning( 'WARNING - Please set the date format correct in your settings');} 
        $SITE['soilUsed']       = false;      
       
        break;
#---------------------------------------------------------------------------
case 'VW':      #               required settings for   VWS / 
#
	$SITE['alltime_values'] = false;
	$SITE['MeteoplugPage'] 	= $SITE['wdlPage'] = $SITE['cltrPage'] = 'no';
	$SITE['wd_live']        = $SITE['meteoplug']= false;
	$SITE['WXsoftwareURL']  = 'http://www.ambientweather.com/virtualstation.html';	
	$SITE['WXsoftwareLongName'] = 'VWS';
	$SITE['WXsoftwareIcon'] = 'img/vws.gif';

        if (!ws_date_format()) {ws_print_warning( 'WARNING - Please set the date format correct in your settings');} 
#
	$SITE['wsTags'] 	= $SITE['uploadDir'].'tagsVW.txt';        // ##### location and name of the uploaded tags file 
        $SITE['vws_day_txt']    = $SITE['wsTags'];

        $SITE['wp_scripts']     = 'scriptsVW/';
        $SITE['process']        = $SITE['wp_scripts'].'tagsVWS.php';
        $SITE['wsTagsSrc'] 	= $SITE['wp_scripts'].'tags.vws.htx';

# ---- VWS realtime ---------------------------------------------------------      		
        $SITE['realtime']	= 'wflash';             // type of realtime file supported
        $SITE['wflash_folder']  = $SITE['clientrawDir'];   // location of wflash files => if we started to use wflash
        $SITE['realtime_file']	= $SITE['wflash_folder'].'wflash.txt';  // default names of realtime files
        $SITE['realtime_file2'] = $SITE['wflash_folder'].'wflash2.txt';       
#    
        $SITE['soilUsed']       = false; 
        $SITE['clientrawDir'] 	= false; 
        $SITE['MeteoplugPage'] 	= $SITE['wdlPage'] = $SITE['cltrPage'] = 'no'; 
        $SITE['trendPage'] 	= 'no';
        $SITE['wd_live']        = $SITE['meteoplug']= false;

        break;
#---------------------------------------------------------------------------
default:
	echo '<H3>Other software not supported (yet)</h3>'.PHP_EOL; exit;
}
#---------------------------------------------------------------------------
$SITE['wsRealTime'] 	= 1*60+30;	// number of seconds before realtime (or clntraw) data is considered obsolete
$SITE['wsNormTime'] 	= 5*60+30;      // number of seconds before (tags) data is considered obsolete
$SITE['wsFtpTime'] 	= 60*60+30;	// number of seconds before all data (graphs) is considered obsolete
$SITE['wsDataTime'] 	= 5*60+30;      // number of seconds before all data (actual internal time) is considered obsolete
#---------------------------------------------------------------------------
$SITE['steelTime']      = $SITE['wsSteelTime']; // 2015-10-01  older gauge scripts
$SITE['langDir']	= 'lang/';		// all language translation files are store here

return;
# --------------- functions for checking settings --------------------------------------
function ws_print_warning ($message) {
        global $SITE;
        if ($SITE['wsDebug']) {
        	if (!isset ($SITE['message']) ) {$SITE['message']='';}
        	$SITE['message'] .=  $message.'<br />'.PHP_EOL;
        }
}
function ws_check_setting (&$setting) {
        global $SITE;
        if (!isset ($setting) )         {return false;}
        elseif ($setting == false)      {return false;}
        elseif ($setting === true)      {return true;}
        elseif ($setting == 'yes')      {return true;}
        elseif ($setting == '1')        {return true;}
        else return false;         
}
function check_topfolder ($check=true) {
        global $SITE;
        if ($check <> true) {return true;}
        
	$docRoot 		= $_SERVER['DOCUMENT_ROOT'].'/';
#	$docRoot 		= str_replace ('//','/',$docRoot);
	$string 		= $_SERVER['SCRIPT_FILENAME'];
	$folders		= str_replace($docRoot , '', $string);
	$folders		= str_replace('\\' , '/', $folders);
	$arr 			= explode ('/', $folders);
	$count			= count($arr);
	$n			= $count - 1;
	switch ($count) {
		case 2:
			$FIND['topfolder']	= $arr['0'].'/';
			break;
		case 1:
			$FIND['topfolder']	= './';
			break;
		default:
                        $end                    = $count - 2;
			$FIND['topfolder']	= '';
			for ($i = 0; $i <= ($end); $i++) {		// assemble the topfolder 
				$FIND['topfolder'] .= $arr[$i].'/';
			}
			$end++;
	}
	if ($SITE["topfolder"]	<> $FIND['topfolder']) {
		ws_print_warning ('WARNING - Change wsUserSettings.php:  $SITE["topfolder"]  from : '.$SITE["topfolder"].' to: '.$FIND['topfolder']);
        }
}
function ws_date_format() {
        global $SITE, $my_date_format, $my_char_sep, $my_month, $my_year, $my_day;
        $my_date_format = $SITE['my_date_format'];
        $my_char_sep    = $SITE["my_char_sep"];
        $my_day         = $SITE["my_day"];
        $my_month       = $SITE["my_month"];
        $my_year        = $SITE["my_year"];
        if     ($my_date_format == 'dd-mm-yyyy')  {$SITE['tags_ymd'] = array (3,2,1); $SITE['tags_ymd_sep']   = '-'; return true;}
        elseif ($my_date_format == 'dd-mm-yy')    {$SITE['tags_ymd'] = array (3,2,1); $SITE['tags_ymd_sep']   = '-'; return true;}
        elseif ($my_date_format == 'dd/mm/yyyy')  {$SITE['tags_ymd'] = array (3,2,1); $SITE['tags_ymd_sep']   = '/'; return true;}
        elseif ($my_date_format == 'dd/mm/yy')    {$SITE['tags_ymd'] = array (3,2,1); $SITE['tags_ymd_sep']   = '/'; return true;}
       
        elseif ($my_date_format == 'mm-dd-yyyy')  {$SITE['tags_ymd'] = array (3,1,2); $SITE['tags_ymd_sep']   = '-'; return true;}
        elseif ($my_date_format == 'mm-dd-yy')    {$SITE['tags_ymd'] = array (3,1,2); $SITE['tags_ymd_sep']   = '-'; return true;}
        elseif ($my_date_format == 'mm/dd/yyyy')  {$SITE['tags_ymd'] = array (3,1,2); $SITE['tags_ymd_sep']   = '/'; return true;}
        elseif ($my_date_format == 'mm/dd/yy')    {$SITE['tags_ymd'] = array (3,1,2); $SITE['tags_ymd_sep']   = '/'; return true;}
# no valid setting found so far
        if (!isset ($my_char_sep) )               {$SITE['tags_ymd'] = array (3,2,1); $SITE['tags_ymd_sep']   = '/'; return false;}
        
        $SITE['tags_ymd_sep']   = trim($my_char_sep);
        if (strlen ($SITE['tags_ymd_sep']) <> 1)  {$SITE['tags_ymd'] = array (3,2,1); $SITE['tags_ymd_sep']   = '/'; return false;}
# the separator is Ok, lets check the yy mm dd 
        if (!isset ($my_month) || !isset ($my_year) || !isset ($my_day) ) 
                                                  {$SITE['tags_ymd'] = array (3,2,1); $SITE['tags_ymd_sep']   = '/'; return false;}
        if (!is_numeric($my_month) || !is_numeric($my_year) || !is_numeric($my_day) )
                                                  {$SITE['tags_ymd'] = array (3,2,1); $SITE['tags_ymd_sep']   = '/'; return false;}
        $my_month      = (int) $my_month;     
        $my_year       = (int) $my_year;     
        $my_day        = (int) $my_day; 
        $total         =  $my_month +  $my_year  + $my_day;
        if ($total <> 6)                        {$SITE['tags_ymd'] = array (3,2,1); $SITE['tags_ymd_sep']   = '/'; return false;}
        $error = false;
        if ($my_month > 3 || $my_month < 1)     {$error = true;}
        if ($my_year > 3  || $my_year < 1)      {$error = true;}
        if ($my_day > 3   || $my_day  < 1)      {$error = true;}
        if ($error)                             {$SITE['tags_ymd'] = array (3,2,1); $SITE['tags_ymd_sep']   = '/'; return false;}
        $SITE['tags_ymd']       = array ($my_year,$my_month,$my_day);
        return true;
}
# ----------------------  version history
# 3.20 2015-10-01 release 2.8 version 